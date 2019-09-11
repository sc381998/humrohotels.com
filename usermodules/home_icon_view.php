<?php
if (!defined('included')) {
    exit();
}

if (empty($_GET['panel'])) {
    exit();
}
$panel = abs($_GET['panel']);



$rst = $dbf->query("select *,(select pagelink from $ai_pagemaster where id=a.link_pageid and  status=1 ) as pagelink   from $ai_homeicon a where status=1 and panel=$panel order by position");

if (abs($_GET['panel']) == 1 ) {

    $rst = $dbf->query("select * from $ai_packagedestination where status=1 order by pro");

    if (count($rst) > 0) {
        ?>



        <div class="container margin_60 padd_bottom_20 wow fadeInUp" data-wow-duration="1000ms">

            <div class="main_title">
                <span></span>
                <h2>Top Tour Packages</h2>
                <p>
                    Popular Tour/Travel Packages you may like.
                </p>
            </div>
            <div class="row">
                <?php
                foreach ($rst as $rs) {
                    #echo $rs['icon_head'];

                    $sqls = "SELECT * FROM $ai_hotelmaster where status=1 and  destinations='$rs[id]' order by totalrooms asc limit 5";
                    $rss = $dbf->query($sqls);
                    if (count($rss) > 0) {
                        ?>
                        <div class="col-md-3" itemscope itemtype ="http://schema.org/Product">
                          
                            <h3 itemprop="brand"><?php echo $rs['destinationname']; ?> </h3>

                            <ul class="list_ok">
                                <?php
                                foreach ($rss as $rs2) {
                                    ?>
                                    <li itemprop="name">
                                      
                                        <a itemprop="url" href="<?php echo $gen->make_link($rs2['package_link'], ''); ?>" title="<?php echo $rs2['hotel_name']; ?>">
                                            <?php echo $rs2['hotel_name']; ?>
                                        </a>
                                    </li>
                                    <div itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                        <meta itemprop="price" content="<?php echo $rs2['startingprice']; ?>"/>
                                        <meta itemprop="priceCurrency" content="INR"/>
                                        <link itemprop="availability" href="http://schema.org/InStock" />
                                        <link itemprop="url" href="<?php echo $gen->make_link($rs2['package_link'], ''); ?>" />
                                    </div>
                                    <?php
                                }
                                ?>
                            </ul>  
                           
                        </div>
                        <?php
                    }
                }
                ?>
            </div>

        </div>
        <?php
        
    }
}

if (abs($_GET['panel']) == 2 ) {
    if (count($rst) > 0) {
        ?>
        <div class="bg_gray add_bottom_40 wow fadeInUp" data-wow-duration="1000ms">
            <div class="container">
                <h2>Popular Destinations</h2>
                <div class="row">

                    <?php
                    foreach ($rst as $rs) {
                        $pagelink = $rs['icon_link'];
                        if (abs($rs['link_pageid']) != 0) {
                            $pagelink = $rs['pagelink'];
                        }
                        ?>

                        <div class="col-md-3 col-sm-3" itemscope itemtype ="http://schema.org/Product" >

                            <div class="img_zoom">
                                <a href="#">
                                    <img itemprop="image" style="width:100%;" src="<?php echo $explorerdir . 'homeicons/' . $rs['pic']; ?>" class="img-responsive wp-post-image" alt="<?php echo $rs['icon_head']; ?>" />
                                </a>
                            </div>
                            <h3 itemprop="name"><?php echo $rs['icon_head']; ?></h3>
                            <p itemprop="description">
                                <?php
                                $detailsLimit = 200;
                                $str = preg_replace("/(<\/?)(\w+)([^>]*>)/e", "", $rs['icon_details']);
                                if (strlen($str) > $detailsLimit) {
                                    echo substr($str, 0, strpos($str, ' ', $detailsLimit));
                                } else {
                                    echo $str;
                                }
                                ?>
                            </p>
                            <p>

                                <a itemprop="url" href="<?php echo $rs['icon_head']; ?>.php" class="btn_1" title="<?php echo $rs['icon_head']; ?>">Read more</a>
                            </p>                            

                        </div>

                    <?php } ?>


                </div><!-- End row -->
            </div><!-- End container -->
        </div>







        <?php
    }
}

if (abs($_GET['panel']) == 3) {
    if (count($rst) > 0) {
        ?>

       <div class="our-facilties-section content-area-3">
            <div class="overlay">
        <div class="container">
            <!-- Main title -->
            <div class="main-title">
                <h1>Our Facilties</h1>
                <p>Check out our hotel facilties </p>
            </div>
            <div class="row">
                        <?php
                        foreach ($rst as $rs) {

                            $pagelink = $rs['icon_link'];
                            if (abs($rs['link_pageid']) != 0) {
                                $pagelink = $rs['pagelink'];
                            }
                            ?>
                            <div class="col-md-4 col-sm-6 col-xs-12 wow fadeInUp delay-04s">
                                <div class="services-box-2 media" itemscope itemtype ="http://schema.org/Product">
                                    <div class="media-left">
                                        <?php echo $rs['pic'];  ?>
                                    </div>
                                     <div class="media-body">
                                        <h3 itemprop="name"><?php echo $rs['icon_head']; ?></h3>
                                        <p itemprop="description">
                                            <?php
                                $detailsLimit = 200;
                                $str = preg_replace("/(<\/?)(\w+)([^>]*>)/e", "", $rs['icon_details']);
                                if (strlen($str) > $detailsLimit) {
                                    echo substr($str, 0, strpos($str, ' ', $detailsLimit));
                                } else {
                                    echo $str;
                                }
                                    ?>
                                        </p>
                                    </div>
                                   
                                </div>
                            </div>
        <?php } ?>

                    </div>
                </div>

                
            </div>
        </div>



        <?php
    }
    ?>
<div class="counters">
    <h1>Hotels Statistics</h1>
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-6 bordered-right">
                <div class="counter-box">
                    <h1 class="counter">967</h1>
                    <h5>Guest Stay</h5>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 bordered-right">
                <div class="counter-box">
                    <h1 class="counter">577</h1>
                    <h5>Book Room</h5>
                </div>
            </div>
            <div class="col-md-3 col-sm-6 bordered-right">
                <div class="counter-box">
                    <h1 class="counter">1398</h1>
                    <h5>Member Stay</h5>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="counter-box counter-box-2">
                    <h1 class="counter">376</h1>
                    <h5>Meals Served</h5>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
}
?>