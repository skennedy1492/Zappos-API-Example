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
 

        // Change calling jquery from $. to jQuery. so other libraries are not a conflict
        jQuery.noConflict();

        // Creates Main and Carousel flexsliders (i.e., slidshow) for each grouping of items
        function mainsliderInit() {
        
                jQuery(".mainslider, .carouselslider").css("display","none");
                
                // loop through 3 main/carousel flexslider pairings and generate objects.
                for ( var i = 1; i < 2; i++ )
                {

                        // After the first carousel loads, hide the "loading" blank screen. Others hide themselves after loading. Hiding before loading creates malfunctioning flexsliders
                        startCondition = "";
                        if ( i === 1 )
                        {
                                startCondition = 'start: function(slider) { jQuery("#state-select-container").hide(); jQuery("#loading-block").hide(); }';
                        }
                        else
                        {
                                startCondition = 'start: function(slider) { jQuery("#main-slider' + i + '").css("display","none");}';
                        }

                        eval('jQuery("#main-slider' + i + '").flexslider({ \
                                animation: "slide", \
                                controlNav: false, \
                                directionNav: true, \
                                animationLoop: false, \
                                pauseOnAction: false, \
                                slideshow: false, \
                                sync: "#main-carousel' + i +'", \
                                ' + startCondition + ' \
                        });');

                        eval('jQuery("#main-carousel' + i + '").flexslider({ \
                                animation: "slide", \
                                selector: ".slides > li.slide", \
                                controlNav: true, \
                                directionNav: false, \
                                animationLoop: false, \
                                slideshow: false, \
                                itemWidth: 140, \
                                itemMargin: 20, \
                                move: 3, \
                                minItems: 3, \
                                maxItems: 6, \
                                asNavFor: "#main-slider' + i + '", \
                                ' + startCondition.replace('#main-slider' + i,'#main-carousel' + i) + ' \
                        });');
                             
                }
                
                
                jQuery("#main-slider1").addClass("active").show().next().addClass("active").show();

                // Setup click events for the Information Detail boxes for each slide in the flexsliders
                jQuery(".box-mainslider-minimize").click( function() {
                        jQuery(this).parent().animate({height:"toggle", width:"toggle"},500).prev().animate({width:"toggle", height:"toggle"},500);  
                });

                jQuery(".box-mainslider-open").click ( function() {
                        jQuery(this).next().animate({height:"toggle", width:"toggle"},500).prev().animate({width:"toggle", height:"toggle"},500);
                });
                
        }

        // Top navigation menu setup
        function navigationMenuInit () {
        
                jQuery("#navigation li").click(function(event){
                
                        event.preventDefault();
                        var actionID = jQuery(this).attr('id');

                        // Only act on valid clicks where actionID is found
                        if ( typeof actionID === "undefined" || actionID === false ) return;

                        if ( ("main-slider" + actionID) != jQuery(".mainslider.active").attr("id") ) 
                        {  
                                var apiType = "state";
                                var slidesRefresh = true;
                                
                                switch ( actionID )
                                {
                                        case '1':
                                                slidesRefresh = false;
                                        case '2':
                                                apiType = "popular";
                                                // Refresh slides if empty
                                                if (jQuery("#main-slider2 ul li").size() > 0) 
                                                {
                                                        slidesRefresh = false;
                                                }
                                                break;
                                        case '3':
                                                // Refresh slides if empty
                                                if (jQuery("#main-slider3 ul li").size() > 0) 
                                                {
                                                        slidesRefresh = false;
                                                }
                                                apiType = "state";
                                                break;
                                }

                                jQuery("#data-results .active").animate({width:"toggle", height:"toggle"},100).removeClass("active");
                                jQuery("#state-label").hide();
                                
                                jQuery(".nav-item-active").removeClass("nav-item-active");
                                jQuery(this).addClass("nav-item-active");
                                
                                if ( !slidesRefresh )
                                {       
                                        toggleSlider( actionID );
                                }
                                else
                                {
                                        // If "state" slide is being loaded and refresh is needed, prompt user to select state. Otherwise, refresh normally
                                        if (actionID === '3' )
                                        {
                                                stateSelectShow();
                                        }
                                        else
                                        {
                                                getSlideData(actionID, apiType, '');
                                        }
                                }
                        }
                });
        }
        
        // Ajax call requesting slide data based on API request
        function getSlideData( slideNum, apiType, userInput )
        {
                var date = new Date();
        
                jQuery("#loading-block").show();
                
                jQuery.ajax({ 
                        url: "/ajax-load.php",     
                        data: {"action": apiType, "val": userInput, "ts": date.getTime()},      // ts variable prevents caching problem in iPhone
                        type: "post",
                        success: function(output) {
                                var jsonData = jQuery.parseJSON( output );
                                resetSlider ( slideNum, jsonData );
                        }
                });        
        }
        
        // Makes a slide visibile
        function toggleSlider ( actionID )
        {
                // Hide label displaying current state selection if the State flexslider is not active
                if ( actionID === '3' ) 
                {
                        jQuery("#state-label").show();
                }
                
                jQuery("#main-slider" + actionID).animate({width:"toggle", height:"toggle"},500).addClass("active");
                jQuery("#main-carousel" + actionID).animate({width:"toggle", height:"toggle"},500).addClass("active");
        }
        
        // Remove data, action events (click, hover, etc), and flexslider object from current main/carousel flexslider grouping of slides
        function resetSlider ( sliderNum, newData )
        {

                var mainSlider = jQuery("#main-slider" + sliderNum);
                var carouselSlider = jQuery("#main-carousel" + sliderNum);

                // Clear all existing event handlers on flexsliders and children elements
                mainSlider.find('*').andSelf().unbind();        
                carouselSlider.find('*').andSelf().unbind();

                // Delete Flexslider object and existing HTML
                mainSlider.toggle().removeData("flexslider").html('');
                carouselSlider.removeData("flexslider").html('');

                // imageURLKey & productURLKey values change based on API type.
                var imageURLKey = newData["details"]["imageURLKey"];
                var productURLKey = newData["details"]["productURLKey"];
                var mainSliderHTML = carouselSliderHTML = '<ul class="slides">';
               
                // For each new data set create a new slide for including in a new flexslider
                jQuery.each(newData, function( index ) 
                {
                        if ( !isNaN(parseInt(index)) )
                        {

                                var imageURL = newData[index][imageURLKey];
                                var originalPrice = salePercent = '';
                                var locationData = '';

                                // Create a "sale" object for displaying on carousel flexsliders when there is a sale on the item
                                if ( parseInt(newData[index]["salePercent"]) > 0 ) 
                                {
                                        originalPrice = '<li class="orig-price">Orig: ' + newData[index]["originalPrice"] + '</li>';
                                        salePercent = ' <div class="item-sale-marker"> \
                                                                <span class="item-sale-number"> ' + newData[index]["salePercent"] + '</span>\
                                                                <div class="item-sale-label"> \
                                                                        <span class="item-sale-percent">%</span> \
                                                                        <span class="item-sale-off">off</span> \
                                                                </div> \
                                                        </div>';
                                }
                                
                                // When location city/state data is available via the State API call, display with item details
                                if ( newData[index]["location"] )
                                {
                                        locationData += '<li>&nbsp;</li><li>' + capitalizeFirstLetter(newData[index]["location"]["city"]) + ', ' + newData[index]["location"]["state"] + '</li>';
                                        locationData += '<li>' + newData[index]["date"] + '</li>';
                                }

                                // Create the HTML for the main flexslider and details information box
                                mainSliderHTML += ' \
                                        <li> \
                                                <div style="background-repeat: no-repeat; background-image: url(' + imageURL.replace('t-THUMBNAIL', 'p-MULTIVIEW') + ')"> \
                                                        <div class="container"> \
                                                                <div class="box-mainslider-open"><span class="open-icon"></span></div> \
                                                                        <div class="box-mainslider"> \
                                                                        <div class="box-mainslider-minimize"><span class="minimize-icon"></span></div> \
                                                                        <h3>' + newData[index]["productName"] + '</h3> \
                                                                        <p> \
                                                                                <ul class="item-details"> \
                                                                                        <li class="sale-price">Price: ' + newData[index]["price"] + '</li> \
                                                                                        ' + originalPrice + ' \
                                                                                        <li>By: ' + newData[index]["brandName"] + '</li> \
                                                                                        ' + locationData + '\
                                                                                </ul> \
                                                                                <a href="' + newData[index][productURLKey] + '" target="_blank" class="box-click">click here</a> \
                                                                        </p> \
                                                                </div> \
                                                        </div> \
                                                </div>  \
                                        </li>';
                                        
                                // Create the HTML carousel flexslider   
                                carouselSliderHTML += ' \
                                        <li class="slide easyBox"> \
                                                <img src="' + newData[index][imageURLKey] + '" /> \
                                                ' + salePercent + ' \
                                        </li>';
                        
                        }
                });    

                mainSliderHTML += "</ul>";
                carouselSliderHTML += "</ul>";

                // Create the Main/Carousel flexsliders and syncronize the two objects
                eval('mainSlider.html(mainSliderHTML).show().addClass("active").delay(750).flexslider({ \
                        animation: "slide", \
                        controlNav: false, \
                        directionNav: true, \
                        animationLoop: false, \
                        pauseOnAction: false, \
                        slideshow: false, \
                        sync: "#main-carousel' + sliderNum +'", \
                        start: function(slider) { \
                                jQuery("#loading-block").hide(); \
                        } \
                })');
                
                eval('carouselSlider.html(carouselSliderHTML).show().addClass("active").delay(750).flexslider({ \
                        animation: "slide", \
                        selector: ".slides > li.slide", \
                        controlNav: true, \
                        directionNav: false, \
                        animationLoop: false, \
                        slideshow: false, \
                        itemWidth: 140, \
                        itemMargin: 20, \
                        move: 3, \
                        minItems: 3, \
                        maxItems: 6, \
                        asNavFor: "#main-slider' + sliderNum + '"\
                })');
                
                // Setup click events for the Information Detail boxes for each slide in the flexsliders
                mainSlider.find(".box-mainslider-minimize").click( function() {
                        jQuery(this).parent().animate({height:"toggle", width:"toggle"},500).prev().animate({width:"toggle", height:"toggle"},500);  
                });

                mainSlider.find('.box-mainslider-open').click ( function() {
                        jQuery(this).next().animate({height:"toggle", width:"toggle"},500).prev().animate({width:"toggle", height:"toggle"},500);
                });
        }
    
        // Create a map of the United States for user to select a state from
        function mapUSInit () 
        {
                /*** Causes extra load time and not the right implementation. But libraries only needed when US Map plugin is used. TODO: find a better implementation ***
                jQuery.getScript("assets/js/raphael.js", function(data, textStatus, jqxhr) { console.log (textStatus); });
                jQuery.getScript("assets/js/color.jquery.js", function(data, textStatus, jqxhr) { console.log (textStatus); });
                jQuery.getScript("assets/js/jquery.usmap.js", function(data, textStatus, jqxhr) { console.log (textStatus); });
                ******/

                jQuery("#state-map").usmap({
                        "stateStyles": {
                                fill: "#FCAF99",        // color inside states 
                                "stroke-width": 1,
                                "stroke" : "#F56238"    // line color between states
                        },
                        "stateHoverStyles": {
                                fill: "#F56238"         // color on hover over state objects
                        },
                        "click" : function(event, data) {
                        
                                jQuery("#state-select").val( data.name ).attr('selected',true);
                                
                                // User has selected state, hide selection window and display results
                                userStateSelect();
                        }
                });
        }
        
        // Show the state select user input window
        function stateSelectShow ()
        {
                jQuery("#state-select-container").show().addClass("active");          
        }
        
        // Hide the state select user input window
        function stateSelectHide ()
        {
                jQuery("#state-select-container").hide().removeClass("active");
                            
        }

        // Hide the state select user input window, change the "state-select" label to show user text version of selection, refresh slide data for "State" flexslider
        function userStateSelect()
        {
                var userInput = jQuery("#state-select").val();
                jQuery("#state-label").html(jQuery("#state-select option:selected").text() + "&nbsp;&nbsp;<a id='state-label-change' href='#'>Change</a>").show()
                        // Bind a click event to the "change" link next to the state-label allowing user to modify the state data
                        .find("#state-label-change").click(function() {
                                jQuery("#data-results .active").animate({width:"toggle", height:"toggle"},100).removeClass("active");
                                jQuery(this).parent().find("*").andSelf().unbind().html('');
                                stateSelectShow();
                        });
                        
                stateSelectHide();
                
                // Get new data for "State slider
                getSlideData(3, "state", userInput);
        }
    
        // Button to smoothly scroll user to the top of the window 
        function scrollToTop(i) {
                if (i == "show") 
                {
                        if (jQuery(this).scrollTop() != 0) 
                        {
                                jQuery("#toTop").fadeIn();
                        } 
                        else 
                        {
                               jQuery("#toTop").fadeOut();
                        }
                }
                if (i == "click") {
                        jQuery("#toTop").click(function () {
                                jQuery("body,html").animate({scrollTop: 0}, 500);
                                return false;
                        });
                }
        }
        
        // Make modifications to the page based on whether the browser is mobile or Internet Explorer
        function browserInit () {
        
                // determine if the user's browser is IE
                var browserIE = false;
                if (navigator.userAgent.toLowerCase().match(/mozilla/i) && navigator.userAgent.toLowerCase().match(/msie/i) )
                {
                        browserIE = true;
                }
                
                // US Map functionality is only generated and displayed in non-mobile browsers. Reduces javascript errors and page load time
                if ( !jQuery.browser.mobile ) 
                {
                        jQuery(".nav-icon").show();

                        // US Map functionality does not work in Internet Explorer. If user's browser is IE, hide the map and display basic Select input
                        if ( !browserIE ) 
                        {
                                jQuery("#state-map").removeClass("hide");
                                mapUSInit();
                        }
                        else
                        {
                                jQuery("#state-select-submit").click(userStateSelect);
                                jQuery("#state-drop-down").removeClass("hide");
                        }
                }
                // If mobile, display basic select input
                else
                {
                        jQuery(".nav-icon").remove();
                        jQuery("#state-select-submit").click(userStateSelect);
                        jQuery("#state-drop-down").removeClass("hide");
                }
                
        }
        
        // Capitalize the first letter of each word in a string
        // *** CREDIT: http://stackoverflow.com/questions/4878756/javascript-how-to-capitalize-first-letter-of-each-word-like-a-2-word-city
        function capitalizeFirstLetter (str)
        {
                return str.replace(/\w\S*/g, function(txt){return txt.charAt(0).toUpperCase() + txt.substr(1).toLowerCase();});
        }

        // When page initially loads, make sure user starts at the top of the page
        jQuery(document).ready(function () {
                scrollToTop("click");
        });

        // When user scrolls make the "ToTop" button visible
        jQuery(window).scroll(function () {
                scrollToTop("show");
        });

        // When page loads initialize the various objects and click events
        jQuery(window).load(function () {
                mainsliderInit();
                navigationMenuInit();
                browserInit();
        });

