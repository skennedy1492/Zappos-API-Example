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
?>
        <!-- Footer - Contact information and site details -->
        <div id="footer">
                <div class="container">
                        <div class="row-fluid">
                                <div class="span3">
                                        <h4 class="medium">About A Few Shoes</h4>

                                        <p>
                                                Not a real website or company.<br /><br />Rather, it is <a href="http://www.linkedin.com/in/stkennedy" target="_blank">Sean Kennedy"s</a> attempt to convince <a href="http://zappos.com" target="_blank">Zappos.com</a> to interview him for an open position.
                                        </p>

                                </div>
                                <div class="span3">
                                        <h4 class="medium">Open Source Code Used</h4>

                                        <ul class="open-source" style="list-style: none;">
                                                <li><a href="http://jquery.com" target="_blank">jQuery</a></li>
                                                <li><a href="https://github.com/woothemes/FlexSlider" target="_blank">Flexslider</a></li>
                                                <li><a href="https://github.com/NewSignature/us-map" target="_blank">US Map</a></li>
                                                <li><a href="http://twitter.github.io/bootstrap/" target="_blank">Bootstrap</a></li>
                                                <li><a href="http://www.entypo.com/" target="_blank">Entypo</a></li>
                                                <li><a href="http://detectmobilebrowsers.com/" target="_blank">Detect Mobile Browsers</a></li>
                                                <li><a href="http://ziptasticapi.com" target="_blank">Ziptastic API</a></li>
                                        </ul>
                                </div>
                                <div class="span2">
                                        <h4 class="medium">Contact Sean</h4>
                                        <ul class="contactIcons">
                                                <li><a href="mailto:sean.t.kennedy@gmail.com" class="entypo mail"><i></i>Email</a></li>
                                                <li><a href="http://www.linkedin.com/in/stkennedy" target="_blank" class="entypo linkedin"><i></i>LinkedIn</a></li>
                                                <li><a href="http://www.twitter.com/mcnerdsalot" target="_blank" class="entypo twitter"><i></i>Twitter</a></li>
                                        </ul>
                                </div>
                                <div class="span4">
                                        <h4 class="medium">More Info</h4>
                                        <p>This site is far from perfect. Quirks, wishlist items, etc. But it is a start to show Zappos API's, open source tech, and my code playing together.</p>
                                        <p>You can view all the code on Github <a href="https://github.com/skennedy1492/Zappos-API-Example" target="_blank">here</a>.</p>
                                        <p>I look forward to your judging.</p>
                                        <p><a href="http://www.youtube.com/watch?v=zkd5dJIVjgM" target="_blank" style="text-decoration: underline;">Do not click here.</a></p>
                                </div>
                        </div>
                </div>

                <div class="footNotes">
                        <div class="container">
                                <div class="row-fluid">
                                        <div class="span5">
                                                <p>Baltimore, Maryland</p>
                                        </div>
                                        <div class="span7 textRight">
                                                <p>© 2013 It’s mine, I tell you, mine! All mine! And ... open sourced too.</p>
                                        </div>
                                </div>
                        </div>
                </div>
        </div>

        <!-- ToTop page button -->
        <a href="#" id="toTop" class="entypo up-open"><i></i> Back to top</a>
        
</div>
<!-- / boxedWrapper -->
        <?php if ( $includeJqueryJS ) { ?> <script src="lib/jquery/jquery.min.js"></script> <?php } ?>
        <?php if ( $includeBootstrapJS ) { ?> <script src="lib/bootstrap/js/bootstrap.min.js"></script><?php } ?>
        <?php if ( $includeFlexsliderJS ) { ?> <script src="lib/flexslider/js/jquery.flexslider-min.js"></script><?php } ?>

        <?php if ( $includeUSMapJS ) { ?> 
                <script src="lib/us-map/js/raphael.js"></script>
                <script src="lib/us-map/js/color.jquery.js"></script>
                <script src="lib/us-map/js/jquery.usmap.js"></script>
        <?php } ?>


        <?php if ( $includeMobileTestJS ) { ?> <script src="lib/jquery/jquery.browser.mobile.js"></script><?php } ?>

        <?php if ( $includeMainJS ) { ?> <script src="assets/js/main.js"></script><?php } ?>

</body>
</html>
<?php
        if(extension_loaded("zlib")){ob_end_flush();}
?>
