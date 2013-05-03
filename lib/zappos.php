<?php
/*
 * 
 * Copyright 2013 Sean Kennedy
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 * 
 *     http://www.apache.org/licenses/LICENSE-2.0
 * 
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 * 
 */
 
 
require_once($_SERVER['DOCUMENT_ROOT'].'/../config/config.php');

class Zappos
{


        private $api_data;      // data returned from API in Array format (Zappos provides JSON)
        
        
        // Constructor takes API type and makes immediate call to acquire data
        public function __construct( $apiType, $arguments = array() )
        {
                $this->callAPI( $apiType, $arguments );               
        }

        // Public function to access Zappos data from API
        public function getData() {
                return $this->api_data;
        }
        
        private function callAPI ( $apiType, $arguments ) {
                
                $url = "";
                
                // Determine API URL by API Type
                switch ($apiType) {
                        case "sale":
                                $url = ZAPPOS_ONSALE_URL;
                                break;
                        case "popular":
                                $url = ZAPPOS_POPULAR_URL;
                                break;
                        case "state":
                                $url = ZAPPOS_STATE_URL;
                                break;
                }
                
                $dataFileParam = "";
                
                // Replace URL variables with input from user, if applicable
                foreach ( $arguments as $key => $value )
                {    
                        $url = str_replace("@@".$key."@@", $value, $url);
                        $dataFileParam = "-".$value;
                }
               
                // Data File where acquired information is stored in a flat file
                $dataFile = $_SERVER['DOCUMENT_ROOT']."/tmp-data/zappos-".$apiType.$dataFileParam.".data";
                
                // If file exists already, determine if cache time limit has been reached. If so, rename/save as another file
                if ( file_exists($dataFile) )
                {
                        
                        
                        $dataFileCreateTime = strtotime(date("F d Y H:i:s.", filemtime($dataFile)));
                        $currTime = strtotime(date("F d Y H:i:s."));
                 
                        // If cache limit reached, get a new set of data from Zappos API. If cache limit not reached, use existing file
                        if ( ($currTime - $dataFileCreateTime ) > ZAPPOS_CACHE_TIME)
                        {
                                $dataFileCreateDate = date("Ymd_H_i_s", filemtime($dataFile));
                                rename ($dataFile, $dataFile . "-" . $dataFileCreateDate);
                                
                                $this->curlURLtoFile($url, $dataFile);
                        }
                        
                }
                // File does not exist, get data from Zappos API
                else {
                        $this->curlURLtoFile($url, $dataFile);
                }      
         
                // Read JSON data from flat file
                $fp = fopen($dataFile, "r");
                while (!feof($fp)) {
                        $json_data = fgets($fp);
                }
                fclose($fp);

                // Decode JSON format into Array and store data about API specific keys
                $allData = json_decode($json_data, true);
                
                $this->api_data = $allData['results'];
                $this->api_data['details']['apiType'] = $apiType;
                $this->api_data['details']['imageURLKey'] = ( $apiType <> 'state' )? "thumbnailImageUrl" : "defaultImageUrl";      // State api has different key name for image URL
                $this->api_data['details']['productURLKey'] = ( $apiType <> 'state' )? "productUrl" : "defaultProductUrl";      // State api has different key name for product URL

        }
        
        // Curl URL and store in flat file
        private function curlURLtoFile ($url, $dataFile) {
        
                $ch = curl_init( $url );
                $fp = fopen($dataFile, "w");

                curl_setopt($ch, CURLOPT_FILE, $fp);
                curl_setopt($ch, CURLOPT_HEADER, 0);

                curl_exec($ch);
                curl_close($ch);
                fclose($fp);
                
        }
        
        // Curl URL and return JSON data as Array
        private function curlURLjSON ( $url )
        {
                $ch = curl_init( $url );

                $options = array(
                                CURLOPT_RETURNTRANSFER => true,
                                CURLOPT_HTTPHEADER => array('Content-type: application/json') ,
                        );

                curl_setopt_array( $ch, $options );
                $result =  curl_exec( $ch );
                $data = json_decode( $result, true );
                
                curl_close( $ch );
                
                return $data;
        }
        
        // API Data can contain up to 100 results per call. Return a random set of items up to the "subsetSize"
        public function getDataRandSubset ( $subsetSize ) 
        {
        
                $randIndex = array();
                $randNum = 0;
                $subsetData = array();
                $cacheSize = count($this->api_data);

                // If existing dataset size is smaller than requested, return existing dataset
                if ( $cacheSize > $subsetSize ) 
                {
                        // Pull a random item from the existing dataset without duplicates
                        for ($i = 0; $i < $subsetSize; $i++ ) 
                        {
                                do 
                                {
                                        $randNum = rand(0, $cacheSize);
                                } while ( in_array($randNum, $randIndex) || empty($this->api_data[$randNum]) || !is_array($this->api_data[$randNum]) );        // TODO: Why is empty necessary?
                                
                                $randIndex[$i] = $randNum;
                                $subsetData[$i] = $this->api_data[$randNum];                                
                        } 

                        // Analyze/scrub data before returning
                        $newSubsetData = $this->analyzeData($subsetData, $this->api_data['details']);
                        
                        $newSubsetData['details'] = array_merge($newSubsetData['details'], $this->api_data['details']);
                        
                        return $newSubsetData;
                }

                // Analyze/scrub data before returning
                return $this->analyzeData($this->api_data, $this->api_data['details']);
                  
        }
        
        // Analyzes data and identifies major data points for later use
        private function analyzeData ( $data, $details ) {
  
                $size = count( $data );
                $removed = 0;
                
                for ( $i = 0; $i < $size; $i++ )
                {
                        
                        // When Random items included that are empty no slide created. To be removed when Subset RandNum anamoly corrected
                        // Remove item from dataset and reset FOR LOOP counters
                        if ( empty($data[$i][$details['imageURLKey']]) ) 
                        { 
                        
                                //swap empty index with object in the last index
                                $data[$i] = $data[$size-1];
                                unset($data[$size-1]);
                                
                                $removed++;
                                
                                // for loop counters reset
                                $size--;
                                $i--;
                                continue; 
                        }
                       
                        // Determin any sale percentage on item
                        if ( array_key_exists('originalPrice', $data[$i]) && $data[$i]['originalPrice'] != $data[$i]['price'] ) 
                        { 
                                $sale_price = (float)(str_replace(array('$',','),'',$data[$i]['price'])); 
                                $original_price = (float)(str_replace(array('$',','),'',$data[$i]['originalPrice'])); 
                                $sale_percent = round((1 - ($sale_price / $original_price)) * 100);
                                
                                $data[$i]['salePercent'] = $sale_percent;
                        }
                        else {
                                $data[$i]['salePercent'] = 0;
                        }
      
                        // If API call is for "state", translate Zip Code to City/State for display to user
                        if ( $details['apiType'] == 'state' )
                        {
                                $zipData = $this->curlURLjSON("http://ziptasticapi.com/".$data[$i]['zip']);
                                $data[$i]['location'] = $zipData;
                        }
                }
                
                // Tracking/Troubleshooting items removed from dataset
                $data['details']['removed'] = $removed;
                
                return $data;
        }
}

?>
