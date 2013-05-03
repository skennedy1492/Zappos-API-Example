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
 
        $PAGE_TITLE = "A Few Shoes - Zappos Deals and Popular Items Right Now";
        
        require_once($_SERVER["DOCUMENT_ROOT"]."/../lib/zappos.php");
        require_once(VIEW_PATH."/header.php");
        
?>

<div class="navbar navbar-static-top">
    <div class="navbar-inner">
        <div class="container">
            
            <a class="brand" href="/">AFewShoes.com</a>
            
            <span class="tagline">Zappos Deals and Popular Items Right Now</span>
            <ul class="menuIcon pull-right">
                <li class="responsive"><img height="60" title="This responsive site can be viewed on many different devices" alt="This responsive site can be viewed on many different devices" src="/assets/images/responsive_icon.png" /></li>
            </ul>
            
        </div>
    </div>
</div>


<?php
        
        $zapposData = array();
        
        $zapposSale = new Zappos("sale");
        $zapposData[0] = $zapposSale->getDataRandSubset(10);
        
        $zapposData[1] = array();
        $zapposData[2] = array();
                
        /*** Uncomment lines to pre-load flexslides 2 & 3. Reduces page load time though. ***/
        //$zapposPopular = new Zappos("popular");
        //$zapposData[1] = $zapposPopular->getDataRandSubset(10);

        //$zapposState = new Zappos("state", array("STATE" => "MD"));
        //$zapposData[2] = $zapposState->getDataRandSubset(10);
        
?>
<div class="container">
    <div id="navigation">
            <ul class="nav-item">
                <div class="center">
                <li class="spacer first">&nbsp;</li>
                <li id="1" class="item nav-item-active">
                  <div class="vcenter-inner-first">
                        <div class="vcenter-inner-second">
                                <span class="nav-icon">&#59148</span>&nbsp;&nbsp;On Sale
                        </div>
                  </div>
                  <div class="active-indicate"></div>
                </li>
                <li id="2" class="item">
                  <div class="vcenter-inner-first">
                        <div class="vcenter-inner-second">
                                <span class="nav-icon">&#128165;</span>&nbsp;&nbsp;Popular Items
                        </div>
                  </div>
                  <div class="active-indicate"></div>
                </li>
                <li id="3" class="item">
                  <div class="vcenter-inner-first">
                        <div class="vcenter-inner-second">
                                <span class="nav-icon">&#127758</span>&nbsp;&nbsp;Bought In Your State
                        </div>
                  </div>
                  <div class="active-indicate"></div>
                </li>
                <li class="spacer first">&nbsp;</li>
                </div>
            </ul>
    </div>
</div>
<div id="data-results" class="container">
        <div id="loading-block" class="easyBox">
                <div class="vcenter-inner-first">
                        <div class="vcenter-inner-second">
                                <h3>Loading ...</h3>
                        </div>
                </div>
        </div>
        <div id="state-label"></div>
<?php
        $dataSetCount = count($zapposData);
        for ( $a = 0; $a < $dataSetCount; $a++ ) 
        {
                $currSet = $zapposData[$a];
                $size = count($currSet) - 1;    // Do not process the 'details' key
                $imageURLKey = $currSet["details"]["imageURLKey"];
                $productURLKey = $currSet["details"]["productURLKey"];
                
?>  
        <div id="main-slider<?php echo $a+1; ?>" class="flexslider mainslider easyBox">
          <?php
               if ( $a+1 == 3 ) 
               {
          ?>
                <div id="state-label"></div>
          <?php
               }
          ?>
          <ul class="slides">
          <?php
               for ($i = 0; $i < $size; $i++ ) 
               {
                        $currItem = $currSet[$i];
          ?>
            <li>
                <div style="background-repeat: no-repeat; background-image: url(<?php echo str_replace("t-THUMBNAIL", "p-MULTIVIEW", $currItem[$imageURLKey]); ?>);">
                    <div class="container">
                        <div class="box-mainslider-open"><span class="open-icon"></span></div>
                        <div class="box-mainslider">
                            <div class="box-mainslider-minimize"><span class="minimize-icon"></span></div>
                            <h3><?php echo $currItem["productName"]; ?></h3>
                                        <p>
                                                <ul class="item-details">
                                                        <li class="sale-price">Price: <?php echo $currItem["price"]; ?></li>
                                                        <?php
                                                                if ( $currItem["salePercent"] > 0 ) 
                                                                {
                                                        ?>
                                                        <li class="orig-price">Orig: <?php echo $currItem["originalPrice"]; ?></li>
                                                        <?php
                                                                }
                                                        ?>
                                                        <li>By: <?php echo $currItem["brandName"]; ?></li>
                                                </ul>
                                                <a href="<?php echo $currItem[$productURLKey]; ?>" target="_blank" class="box-click">click here</a>
                                        </p>
                        </div>
                    </div>
                </div>
            </li>
          <?php
          }
          ?>
          </ul> 
        </div>
        <div id="main-carousel<?php echo $a+1; ?>" class="flexslider carouselslider">
          <ul class="slides">
                <?php
                        for ($i = 0; $i < $size; $i++ ) 
                        {
                                $currItem = $currSet[$i];
                ?>
                        <li class="slide easyBox">
                                <img src="<?php echo $currItem[$imageURLKey]; ?>" alt="<?php echo $currItem["productName"]; ?>" />
                                <?php
                                        if ( $currItem["salePercent"] > 0 ) 
                                        { 
                                ?>
                                        <div class="item-sale-marker">
                                                <span class="item-sale-number"><?php echo $currItem["salePercent"]; ?></span>
                                                <div class="item-sale-label">
                                                        <span class="item-sale-percent">%</span>
                                                        <span class="item-sale-off">off</span>
                                                </div>
                                        </div>
                                <?php
                                        }
                                ?>
                        </li>
                <?php
                        }
                ?>                                                  
          </ul>
        </div>
        <?php
        }
        ?>
        <div id="state-select-container" class="contiainer easyBox">
                <div id="state-drop-down" class="vcenter-inner-first hide">
                        <div class="vcenter-inner-second">
                                <h3>Please Select a State</h3>
                                <select id="state-select" name="state" size="1">
                                        <option value="AL">Alabama</option>
                                        <option value="AK">Alaska</option>
                                        <option value="AZ">Arizona</option>
                                        <option value="AR">Arkansas</option>
                                        <option value="CA">California</option>
                                        <option value="CO">Colorado</option>
                                        <option value="CT">Connecticut</option>
                                        <option value="DE">Delaware</option>
                                        <option value="DC">Dist of Columbia</option>
                                        <option value="FL">Florida</option>
                                        <option value="GA">Georgia</option>
                                        <option value="HI">Hawaii</option>
                                        <option value="ID">Idaho</option>
                                        <option value="IL">Illinois</option>
                                        <option value="IN">Indiana</option>
                                        <option value="IA">Iowa</option>
                                        <option value="KS">Kansas</option>
                                        <option value="KY">Kentucky</option>
                                        <option value="LA">Louisiana</option>
                                        <option value="ME">Maine</option>
                                        <option value="MD" selected>Maryland</option>
                                        <option value="MA">Massachusetts</option>
                                        <option value="MI">Michigan</option>
                                        <option value="MN">Minnesota</option>
                                        <option value="MS">Mississippi</option>
                                        <option value="MO">Missouri</option>
                                        <option value="MT">Montana</option>
                                        <option value="NE">Nebraska</option>
                                        <option value="NV">Nevada</option>
                                        <option value="NH">New Hampshire</option>
                                        <option value="NJ">New Jersey</option>
                                        <option value="NM">New Mexico</option>
                                        <option value="NY">New York</option>
                                        <option value="NC">North Carolina</option>
                                        <option value="ND">North Dakota</option>
                                        <option value="OH">Ohio</option>
                                        <option value="OK">Oklahoma</option>
                                        <option value="OR">Oregon</option>
                                        <option value="PA">Pennsylvania</option>
                                        <option value="RI">Rhode Island</option>
                                        <option value="SC">South Carolina</option>
                                        <option value="SD">South Dakota</option>
                                        <option value="TN">Tennessee</option>
                                        <option value="TX">Texas</option>
                                        <option value="UT">Utah</option>
                                        <option value="VT">Vermont</option>
                                        <option value="VA">Virginia</option>
                                        <option value="WA">Washington</option>
                                        <option value="WV">West Virginia</option>
                                        <option value="WI">Wisconsin</option>
                                        <option value="WY">Wyoming</option>
                                </select>
                                <div class="btn-group">
                                        <button id="state-select-submit" class="btn">Submit</button>
                                </div>
                        </div>
                </div>
                <div id="state-map" class="hide"><div id="pick-state-label" style="position: absolute; top: 0px; left:0px; width: 100%; text-align: center; padding-top: 10px;"><h3>Please Select a State</h3></div></div>
        </div>
</div>

<?php
        $includeJqueryJS = true;
        $includeBootstrapJS = true;
        $includeFlexsliderJS = true;
        $includeUSMapJS = true;
        $includeMobileTestJS = true;
        $includeMainJS = true;
        
        require_once(VIEW_PATH."/footer.php");
?>

