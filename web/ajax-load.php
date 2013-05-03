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
	if(extension_loaded("zlib")){ob_start("ob_gzhandler");}
	
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
	header("Cache-Control: no-store, no-cache, must-revalidate"); 
	header("Cache-Control: post-check=0, pre-check=0", FALSE); 
	header("Pragma: no-cache");
	
	 
        require_once($_SERVER["DOCUMENT_ROOT"]."/../lib/zappos.php");
        
        // Capture API request as $action
        if(isset($_POST["action"]) && !empty($_POST["action"])) 
        {
                // Capture API input variables from user
                if (isset($_POST["val"]) && !empty($_POST["val"]))
                {
                        $inputVal = $_POST["val"];
                }
                
                $action = $_POST["action"];
                
                // Form new Zappos object based on API action type
                switch($action) {
                
                        case "state" : 
                                $zapposObj = new Zappos("state", array("STATE" => $inputVal));
                                break;
                        case "popular" : 
                                $zapposObj = new Zappos("popular");
                                break;
                        case "sale" : 
                                $zapposObj = new Zappos("sale");
                                break;        
                        default: echo "";
                        exit;
                }
                
                // Return random subset of 10 objects as JSON data
                $zapposData = $zapposObj->getDataRandSubset(10);
                echo json_encode($zapposData);
                
        }
        /*** Quick and dirty way to view API data. Uncomment if needed. ***/
        else {
                $zapposObj = new Zappos("popular", array("STATE" => "MD"));
                $zapposData = $zapposObj->getDataRandSubset(10);
                
                echo json_encode($zapposData);       
        }
        /***/
        
        if(extension_loaded("zlib")){ob_end_flush();}
?>
