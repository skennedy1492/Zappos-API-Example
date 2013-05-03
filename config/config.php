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
 
         
        /*
         *      File contains properties needed for the site operation and interaction with Zappos API
         */
         
         
        // Directory locations
        $siteConfig = array (  
            "paths" => array(    
                "images" => $_SERVER["DOCUMENT_ROOT"] . "/images/",
                "views" => $_SERVER["DOCUMENT_ROOT"] . "/views/",
            )  
        );  
        
        // Zappos API properties
        $zapposConfig = array(  
            "api" => array(  
                "key" => "1b3f06945805f324c333ef423f5374382dec0391",  
                "baseUrl" => "http://api.zappos.com/"
            ),  
            "urls" => array(  
                "state" => "Statistics?type=latestStyles&location={\"state\":\"@@STATE@@\"}&limit=100",
                "onSale" => "Search?term=&includes=[\"onSale\"]&filters={\"onSale\":[\"true\"]}&sort={\"productPopularity\":\"desc\"}&limit=100",  
                "popular" => "Search?term=&sort={\"productPopularity\":\"desc\"}&limit=100"
            )
        );
        
        
        // Constants used throughout site
        define("VIEW_PATH", $siteConfig['paths']['views']);
        define("ZAPPOS_STATE_URL", $zapposConfig['api']['baseUrl'] . $zapposConfig['urls']['state'] . "&key=" . $zapposConfig['api']['key']);
        define("ZAPPOS_POPULAR_URL", $zapposConfig['api']['baseUrl'] . $zapposConfig['urls']['popular'] . "&key=" . $zapposConfig['api']['key']);
        define("ZAPPOS_ONSALE_URL", $zapposConfig['api']['baseUrl'] . $zapposConfig['urls']['onSale'] . "&key=" . $zapposConfig['api']['key']);
        define("ZAPPOS_CACHE_TIME", 300); // Time in seconds
        
?>
