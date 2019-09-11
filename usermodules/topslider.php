<?php
if (!defined('included')) {
    exit();
}
$flag = 1;
$rstb = $dbf->query("select *, (select pagelink from $ai_pagemaster where id=a.link_pageid) as pagelink from $ai_topslider a where status=1 and pageid=$pageid order by position limit 5");
if (count($rstb) == 0) {
    $rstb = $dbf->query("select *, (select pagelink from $ai_pagemaster where id=a.link_pageid) as pagelink from $ai_topslider a where status=1 and pageid=0 order by position limit 5");
}
$sql2 = "SELECT * from $ai_hotelmaster a where package_link = '$pagefile'";
$rs3 = $dbf->query($sql2, false);

?>

<!-- Banner start -->
<div class="banner banner-style-3 banner-max-height">
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
              <?php
            if (count($rstb) > 0) {
               $c=0;
                foreach ($rstb as $rs) {
                    ?>
            <div class="item <?php if($c==0){echo 'active';} ?>">
                <img src="files/topslider/<?php echo $rs['pic']; ?>" alt="<?php echo $rs['slider_head']; ?>">
                <div class="carousel-caption banner-slider-inner banner-top-align">
                    <div class="banner-content banner-content-left text-left">
                        <h1 data-animation="animated fadeInDown delay-05s"><?php echo $rs['slider_head']; ?></h1>
                        <p data-animation="animated fadeInUp delay-1s"><?php echo $rs['slider_details']; ?></p>
                        <a href="#" class="btn btn-md btn-theme" data-animation="animated fadeInUp delay-15s">Get Started Now</a>
                        <a href="#" class="btn btn-md border-btn-theme" data-animation="animated fadeInUp delay-15s">Learn More</a>
                    </div>
                </div>
            </div>
            
                <?php $c++; }
            }
                ?>
            
            
        </div>

        <!-- Controls -->
        <a class="left carousel-control" href="#carousel-example-generic" role="button" data-slide="prev">
            <span class="slider-mover-left" aria-hidden="true">
                <i class="fa fa-angle-left"></i>
            </span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#carousel-example-generic" role="button" data-slide="next">
            <span class="slider-mover-right" aria-hidden="true">
                <i class="fa fa-angle-right"></i>
            </span>
            <span class="sr-only">Next</span>
        </a>

        <!-- Search area box start -->
        <div class="search-area-box-3 hidden-xs hidden-sm">
            <div class="search-contents">
                <form method="GET">
                    <div class="col-lg-12 col-pad col-pad-2">
                        <div class="search-your-rooms">
                            <h2 class="hidden-xs hidden-sm">Search Your <span>Rooms</span></h2>
                        </div>
                    </div>
                    <div class="search-your-details">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <input type="text" class="btn-default datepicker" placeholder="Check In">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <input type="text" class="btn-default datepicker" placeholder="Check Out">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <select class="selectpicker search-fields form-control-2" name="room">
                                    <option>Room</option>
                                    <option>Single Room</option>
                                    <option>Double Room</option>
                                    <option>Deluxe Room</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <select class="selectpicker search-fields form-control-2" name="adults">
                                    <option>Adult</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <select class="selectpicker search-fields form-control-2" name="children">
                                    <option>Child</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <button class="search-button btn-theme">Search</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- Search area box end -->
    </div>
</div>
<!-- Banner end -->
<!-- Search area box 2 start -->
<div class="search-area-box-2 hidden-lg hidden-md">
    <div class="container">
        <div class="search-contents">
            <form method="GET">
                <div class="row">
                    <div class="col-md-3 col-pad col-pad-2">
                        <div class="search-your-rooms">
                            <h3 class="hidden-xs hidden-sm">Search</h3>
                            <h2 class="hidden-xs hidden-sm">Your <span>Rooms</span></h2>
                            <h2 class="hidden-lg hidden-md">Search Your <span>Rooms</span></h2>
                        </div>
                    </div>
                    <div class="search-your-details">
                        <div class="col-md-2 col-sm-4 col-xs-6 col-pad">
                            <div class="form-group">
                                <input type="text" class="btn-default datepicker" placeholder="Check In">
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6 col-pad">
                            <div class="form-group">
                                <input type="text" class="btn-default datepicker" placeholder="Check Out">
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-4 col-xs-6 col-pad">
                            <div class="form-group">
                                <select class="selectpicker search-fields form-control-2" name="room">
                                    <option>Room</option>
                                    <option>Single Room</option>
                                    <option>Double Room</option>
                                    <option>Deluxe Room</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-4 col-xs-6 col-pad">
                            <div class="form-group">
                                <select class="selectpicker search-fields form-control-2" name="adults">
                                    <option>Adult</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-1 col-sm-4 col-xs-6 col-pad">
                            <div class="form-group">
                                <select class="selectpicker search-fields form-control-2" name="children">
                                    <option>Child</option>
                                    <option>1</option>
                                    <option>2</option>
                                    <option>3</option>
                                    <option>4</option>
                                    <option>5</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-4 col-xs-6 col-pad">
                            <div class="form-group">
                                <button class="search-button btn-theme">Search</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Search area box 2 end -->