<?php
if (!defined('included')) {
    exit();
}
if ($dbf->checkIP($ip)) {// Check the IP
    echo '<br />' . $sysmsg['ip_blocked'] . '<br />';
    exit();
}
$name = '';
$email = '';
$rating = '';
$phone = '';
$message = '';
$status = 0;

$rateing = array(
    1 => '<span class="fa fa-star checked"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>',
    2 => '<span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>',
    3 => '<span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>',
    4 => '<span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star"></span>',
    5 => '<span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>'
);
$category_selec = array();
$sql2 = "SELECT *,(select pic from $ai_packagedestination where id=a.destinations) as 'destination_img' from $ai_hotelmaster a where package_link = '$pagefile'";
$rs3 = $dbf->query($sql2, false);
$hotel_id = $rs3['id'];

$sql4 = "SELECT distinct room_type from $ai_tariff where status = 1 and hotel_id = $hotel_id";
$rs = $dbf->query($sql4);

$sqlall = "select * from $ai_hotelmaster where status =1 and id = $hotel_id";
$rsall = $dbf->query($sqlall, false);

$packageqry = $rsall['pkg_category'];
$category_selec = explode("|", $packageqry);

$meta_qry = $rsall['meta_key'];
$meta_selec = explode(",", $meta_qry);

$package_amenties = array();
$package_icon = array();
$sqlc = "SELECT * FROM $ai_amenities  where status=1";
$queryc = $dbf->query($sqlc);
foreach ($queryc as $rsc) {
    $package_amenties[$rsc['id']] = $rsc['amenities'];
    $package_icon[$rsc['id']] = $rsc['icon_file'];
}

$flag = 1;
$sql = "select * from $ai_feedback where status=1";
$sqlCount = "select COUNT(1) " . substr($sql, strpos($sql, "from $ai_feedback"));
$numrows = $dbf->GetOneVal($sqlCount);
$pageNum = 1;
$rowsPerPage = 5;

if (isset($_GET['page']) && is_numeric($_GET['page'])) { ###
    $pageNum = abs($_GET['page']); ###
}

$sql .= " order by rateing desc,date desc ";

// counting the offset 
$offset = ($pageNum - 1) * $rowsPerPage;
$sql .= " LIMIT $offset, $rowsPerPage";
// reading guest feedback
$sql5 = "select * from $ai_feedback where status = 1";
$query2 = $dbf->query($sql5);

$room_type = array();
$sql6 = "select DISTINCT meal_type from $ai_tariff where status = 1 and hotel_id = $hotel_id";
$query3 = $dbf->query($sql6);

$sql8 = "select * from $ai_tariff where status = 1 and hotel_id = $hotel_id";
$query5 = $dbf->query($sql8);
$room_rate = array();
foreach ($query5 as $rs1) {
    if (!in_array($rs1['room_type'], $room_type)) {
        $room_type[] = $rs1['room_type'];
    }
    $meal_type[$rs1['id']] = $rs1['meal_type'];
    $room_rate[$rs1['room_type'] . $rs1['meal_type']] = $rs1['tariff'];
}

$sqimage = "select image_name from $ai_hotel_images where status = 1 and hotel_id = $hotel_id";
$queryimage = $dbf->query($sqimage);

if ($numrows > 0) {
    $rst = $dbf->query($sql);
}
?>

<div class="content-area rooms-detail-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-8 col-md-8 col-xs-12">
                <!--Heading start-->
                <div class="heading-rooms  clearfix sidebar-widget">
                    <div class="pull-left">
                        <h3><?php echo $rsall['hotel_name']; ?></h3>
                        <p>
                            <i class="fa fa-map-marker"></i><?php echo $rsall['hotel_address']; ?>
                        </p>
                    </div>
                    <div class="pull-right">
                        <h3><span></span><?php echo $rsall['totalrooms']; ?></h3>
                        <h5>Rooms</h5>
                    </div>
                </div>
                <!--Heading end-->

                <!-- sidebar start -->
                <div class="rooms-detail-slider sidebar-widget">
                    <!--  Rooms detail slider start -->
                    <div class="rooms-detail-slider simple-slider mb-40 ">
                        <div id="carousel-custom" class="carousel slide" data-ride="carousel">
                            <div class="carousel-outer">
                                <!-- Wrapper for slides -->
                                <div class="carousel-inner">
                                    <?php
                                    foreach ($queryimage as $img1) {
                                        $img = '';
                                        $img = 'files/package/' . $img1['image_name'];
                                        if ($flag) {
                                            ?>
                                            <div class="item active">
                                                <img src="<?php echo $img; ?>" class="thumb-preview" alt="<?php echo $img1['image_name']; ?>">
                                            </div>
                                            <?php
                                            $flag = $flag - 1;
                                        } else {
                                            ?>
                                            <div class="item">
                                                <img src="<?php echo $img; ?>" class="thumb-preview" alt="<?php echo $img1['image_name']; ?>">
                                            </div>
                                        <?php }
                                    }
                                    ?>
                                </div>
                                <a class="left carousel-control" href="#carousel-custom" role="button" data-slide="prev">
                                    <span class="slider-mover-left t-slider-l" aria-hidden="true">
                                        <i class="fa fa-angle-left"></i>
                                    </span>
                                    <span class="sr-only">Previous</span>
                                </a>
                                <a class="right carousel-control" href="#carousel-custom" role="button" data-slide="next">
                                    <span class="slider-mover-right t-slider-r" aria-hidden="true">
                                        <i class="fa fa-angle-right"></i>
                                    </span>
                                    <span class="sr-only">Next</span>
                                </a>
                            </div>
                            <ol class="carousel-indicators thumbs visible-lg visible-md">
                                <?php foreach ($queryimage as $key => $img1) { ?>
                                    <li data-target="#carousel-custom" data-slide-to="<?php echo $key; ?>" class=""><img src="files/package/<?php echo $img1['image_name']; ?>" alt="<?php echo $img1['image_name']; ?>"></li>
<?php } ?>
                            </ol>

                        </div>
                    </div>
                    <!-- Rooms detail slider end -->

                    <!-- Rooms description start -->
                    <div class="panel-box course-panel-box course-description">
                        <ul class="nav nav-tabs">
                            <li class="active"><a href="#tab1default" data-toggle="tab" aria-expanded="true">Overview</a></li>
                            <li class=""><a href="#tab2default" data-toggle="tab" aria-expanded="false">Amenities</a></li>
                            <li class=""><a href="#tab3default" data-toggle="tab" aria-expanded="false">Room &amp; Tariff</a></li>
                            <li class=""><a href="#tab4default" data-toggle="tab" aria-expanded="false">Guest Review</a></li>                                                     
                            <li class=""><a href="#tab5default" data-toggle="tab" aria-expanded="false">Hotel Policy</a></li>
                        </ul>
                        <div class="panel with-nav-tabs panel-default">
                            <div class="panel-body">
                                <div class="tab-content">
                                    <div class="tab-pane fade active in" id="tab1default">
                                        <div class="divv">
                                            <!-- Title -->
                                            <!-- paragraph -->
                                            <p><?php echo $rsall['details']; ?></p>
                                        </div>
                                    </div>
                                    <div class="tab-pane fade" id="tab5default">
                                        <!-- Inside video start  -->
                                        <div class="inside-video-2">
                                            <p><?php echo $rsall['policy'] ?></p>
                                        </div>
                                        <!-- Inside video end -->
                                    </div>
                                    <div class="tab-pane fade features" id="tab2default">
                                        <!-- Rooms features start -->
                                        <div class="rooms-features">
                                            <h3>Hotel Features</h3>
                                            <div class="row">
                                                <?php
                                                foreach ($package_amenties as $cid => $category) {
                                                    ?>
                                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                                        <ul class="condition">
                                                            <?php
                                                            if (in_array($cid, $category_selec)) {
                                                                ?>
                                                                <li><?php echo $package_icon[$cid] . $category; ?></li>
                                                    <?php } ?>
                                                        </ul>
                                                    </div>
<?php } ?>
                                            </div>
                                        </div>
                                        <!-- Rooms features end -->
                                    </div>
                                    <div class="tab-pane fade technical" id="tab3default">
                                        <!-- Advantages start -->
                                        <div class="advantages">
                                            <h3>Tariff</h3>
                                            <table class="table table-border ">
                                                <thead>
                                                    <tr>
                                                        <th>Room Type</th>
                                                        <?php foreach ($melaplanarray as $meal) { ?>
                                                            <th style='text-align:center;'><?php echo strtoupper($meal); ?></th>
<?php } ?>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                    for ($i = 0; $i < count($room_type); $i++) {
                                                        $room_typename = '';
                                                        if (array_key_exists($i, $room_type)) {
                                                            $room_typename = $room_type[$i];
                                                        }
                                                        ?><tr>
                                                            <td><?php echo strtoupper($room_typename); ?></td>
                                                            <?php
                                                            foreach ($melaplanarray as $meal) {
                                                                $tariff = '';
                                                                $key = $room_typename . $meal;
                                                                if (array_key_exists($key, $room_rate)) {
                                                                    $tariff = $room_rate[$key];
                                                                }
                                                                ?>
                                                                <td style='text-align:center;'><?php echo $tariff; ?></td>
                                                        <?php } ?>
                                                        </tr>
<?php } ?>
                                                </tbody>
                                            </table>

                                        </div>
                                        <!-- Advantages end -->
                                    </div>
                                    <div class="tab-pane fade" id="tab4default">
                                        <div class="comments-section sidebar-widget">
                                            <!-- Main Title 2 -->
                                            <div class="main-title-2">
                                                <h1><span>Hotel </span> Reviews</h1>
                                            </div>

                                            <ul class="comments">
                                                <li>
                                                    <?php
                                                    foreach ($rst as $rsp) {
                                                        ?>
                                                        <div class="comment">
                                                            <div class="comment-author">
                                                                <a href="#">
                                                                    <img src="userassets\img\bf7tS.jpg" alt="">
                                                                </a>
                                                            </div>
                                                            <div class="comment-content">
                                                                <div class="comment-meta">
                                                                    <div class="comment-meta-author">
    <?php echo $rsp['name']; ?>
                                                                    </div>
                                                                    <div class="comment-meta-reply">
                                                                        <a href="#">Reply</a>
                                                                    </div>
                                                                    <div class="comment-meta-date">
                                                                        <span class="hidden-xs"><?php echo $rsp['date']; ?></span>
                                                                    </div>
                                                                </div>
                                                                <div class="clearfix"></div>
                                                                <div class="comment-body">
                                                                    <div class="comment-rating">
    <?php echo $rateing[abs($rsp['rateing'])]; ?>
                                                                    </div>
                                                                    <p><?php echo $rsp['comment']; ?></p>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <?php
                                                    }
                                                    ?>
                                                </li>
                                                <div class="row" id="feedbackform" style="display: none;">
                                                    <li>
                                                        <?php
                                                        foreach ($query2 as $rsp2) {
                                                            ?>
                                                            <div class="comment">
                                                                <div class="comment-author">
                                                                    <a href="#">
                                                                        <img src="userassets\img\bf7tS.jpg" alt="">
                                                                    </a>
                                                                </div>
                                                                <div class="comment-content">
                                                                    <div class="comment-meta">
                                                                        <div class="comment-meta-author">
    <?php echo $rsp2['name']; ?>
                                                                        </div>
                                                                        <div class="comment-meta-reply">
                                                                            <a href="#">Reply</a>
                                                                        </div>
                                                                        <div class="comment-meta-date">
                                                                            <span class="hidden-xs"><?php echo $rsp2['date']; ?></span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="clearfix"></div>
                                                                    <div class="comment-body">
                                                                        <div class="comment-rating">
    <?php echo $rateing[abs($rsp2['rateing'])]; ?>
                                                                        </div>
                                                                        <p><?php echo $rsp2['comment']; ?></p>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                            <?php
                                                        }
                                                        ?>
                                                    </li>
                                                </div>


                                            </ul>
                                            <div class="comment-meta-reply" style="float: left;"> 
                                                <a href="javascript:void(0)" onclick="displaytoggle('feedbackform');" >
                                                    View More
                                                </a>
                                            </div>

                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- feedback form 2 start -->
<?php include('feedbackform.php'); ?>    
                <!-- feedback form 2 end -->
            </div>

            <div class="col-lg-4 col-md-4 col-xs-12">
                <div class="sidebar">
                    <!-- Search area box 2 start -->
<?php include('packageqry.php'); ?>
                    <!-- Search area box 2 end -->

                    <!-- Category posts start -->
                    <div class="sidebar-widget category-posts">
                        <div class="main-title-2">
                            <h1>Category</h1>
                        </div>
                        <ul class="list-unstyled list-cat">
                            <?php
                            foreach ($rs as $result) {
                                ?>
                                <li><a href="#"><?php echo strtoupper($result['room_type']); ?> <span>(1)</span></a></li>
                                <?php
                            }
                            ?>
                        </ul>
                    </div>
                    <!-- Category posts end -->
                    <!-- Location start  -->
                    <div class="location">
                        <!-- Main Title 2 -->
                        <div class="main-title-2">
                            <h1>Location</h1>
                        </div>
                        <iframe src="<?php echo $rsall['google_map']; ?>" style="width: 100%" height="250" frameborder="0" style="border:0" allowfullscreen></iframe>

                    </div>
                    <!-- Location end -->

                    <!-- tags box start -->
                    <div class="sidebar-widget tags-box">
                        <div class="main-title-2">
                            <h1>Tags</h1>
                        </div>
                        <ul class="tags">
                            <?php
                            foreach ($meta_selec as $meta) {
                                ?>
                                <li><a href="#"><?php echo $meta; ?></a></li>
<?php } ?>
                        </ul>
                    </div>
                    <!-- tags box end -->
                </div>
            </div>
        </div>
    </div>
</div>