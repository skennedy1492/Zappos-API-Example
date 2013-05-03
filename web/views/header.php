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
	
?>
<!DOCTYPE html>
<html>
        <head>
                <title><?php echo $PAGE_TITLE; ?></title>
                <meta name="description" content="Example website using Zappos API"s and displaying results">
                <meta name="keywords" content="Zappos API, API, Sean Kennedy, Baltimore">
                <meta name="author" content="Sean Kennedy, sean.t.kennedy@gmail.com">

                <meta http-equiv="cache-control" content="max-age=0" />
                <meta http-equiv="cache-control" content="no-cache" />
                <meta http-equiv="expires" content="0" />
                <meta http-equiv="expires" content="Tue, 01 Jan 1980 1:00:00 GMT" />
                <meta http-equiv="pragma" content="no-cache" />
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
                <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
                <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">

                 
                <link rel="stylesheet" type="text/css" href="lib/bootstrap/css/bootstrap.css">
                <link rel="stylesheet" type="text/css" href="lib/flexslider/css/flexslider.css">
                <link rel="stylesheet" type="text/css" href="lib/entypo/css/entypo.css">
                <link rel="stylesheet" type="text/css" href="assets/css/style.css">

        </head>

        <body>
                <div id="boxedWrapper">
