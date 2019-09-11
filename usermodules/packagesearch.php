<?php
if (!defined('included')) {
    exit();
}

$sql = "SELECT *,(select pic from $ai_packagedestination where id=a.destinations) as 'destination_img' from $ai_hotelmaster a where status=1 ";

$category = '';
$totalrooms = '';
$destination = '';
if (isset($_GET['category']) && is_numeric($_GET['category'])) {
    $category = abs($gen->sanitizeMe($_GET['category']));
    $sql .= " and pkg_category like '%|$category|%'";
}
if (isset($_GET['destination']) && is_numeric($_GET['destination'])) {
    $destination = abs($gen->sanitizeMe($_GET['destination']));
    $sql .= " and destinations = '$destination'";
}
if (isset($_GET['totalrooms']) && is_numeric($_GET['totalrooms'])) {
    $totalrooms = abs($gen->sanitizeMe($_GET['totalrooms']));
    $sql .= " and totalrooms = $totalrooms";
}

$package_category = array();
$sqlc = "SELECT id,categoryname FROM $ai_packagecategory  where status=1";
$queryc = $dbf->query($sqlc);
foreach ($queryc as $rsc) {
    $package_category[$rsc['id']] = $rsc['categoryname'];
}
$package_destination = array();
$sqld = "SELECT id,destinationname FROM $ai_packagedestination  where status=1";
$queryd = $dbf->query($sqld);
foreach ($queryd as $rsd) {
    $package_destination[$rsd['id']] = $rsd['destinationname'];
}
$totalroomsarray = array();
$sqld = "SELECT distinct totalrooms, duration, startingprice FROM $ai_hotelmaster where status=1 order by totalrooms";
$queryd = $dbf->query($sqld);
foreach ($queryd as $rsd) {
    $totalroomsarray[$rsd['totalrooms']] = $rsd['duration'];
}

$sql .= " order by totalrooms LIMIT 0, 9";
#echo $sql;
?>


<div class="container margin_60">
    <div class="row clearfix"> 

        <div class="col-md-12">
            <div class="sidebar_tags sidebar-padd">
                <div class="default-form-area">
                    <div class="row clearfix" id="searchapanel">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="input-group">
                                <label class="input-group-addon"><b>Destination</b></label> 
                                <select class="form-control" name="destination" id="destination" >
                                    <option value="">Select All</option>
                                    <?php echo $gen->combogen($package_destination, $package_destination, $destination, true); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="input-group">
                                <label class="input-group-addon"><b>Category</b></label> 
                                <select class="form-control" name="category" id="category">
                                    <option value="">Select All</option>
                                    <?php echo $gen->combogen($package_category, $package_category, $category, true); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="input-group">
                                <label class="input-group-addon"><b>Duration</b></label> 
                                <select class="form-control" name="totalrooms" id="totalrooms">
                                    <option value="">Select All</option>
                                    <?php echo $gen->combogen($totalroomsarray, $totalroomsarray, $totalrooms, true); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <button class="btn btn-info btn-block " id="searchine" onclick="loadList(1);"  data-loading-text="<i class='fa fa-cog fa-spin fa-fw margin-bottom'></i> Searching...">Search</button>    
                        </div>
                    </div>      

                </div>
            </div>
        </div>
    </div>
    <div class="row clearfix" class="packagecontain" id="packagedata">   
        <?php
        $pkgresult = $dbf->query($sql);
        $message = '';
        if (count($pkgresult) > 0) {
            #print_r($pkgresult[1]);

            foreach ($pkgresult as $rsp) {

                $img = '';
                if (file_exists('files/package/destination/' . $rsp['destination_img']) && !empty($rsp['destination_img'])) {
                    $img = 'files/package/destination/' . $rsp['destination_img'];
                }
                if (file_exists('files/package/' . $rsp['cover_pic']) && !empty($rsp['cover_pic'])) {
                    $img = 'files/package/' . $rsp['cover_pic'];
                }

                $qryy4 = "select * from $ai_packagedetail where pkg_id=$rsp[id] order by iday limit 1";
                $rsesult = $dbf->query($qryy4, false);
                $details ='';
                if (strlen($rsesult['details']) > 300 && strpos($rsesult['details'], ' ', 300) > 0) {
                    $details = substr($rsesult['details'], 0, strpos($rsesult['details'], ' ', 300)) . '...';
                } else {
                    $details = $rsesult['details'];
                }

                $message .= '<div class="strip_all_rooms_list wow fadeIn" data-wow-delay="0.1s">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-4">
                            <div class="img_list">
                               <a> <img  title="' . $rsp['hotel_name'] . '" src="' . $img . '"  itemprop="image" alt="' . $rsp['hotel_name'] . '" /></a>
                            </div>
                        </div>
                        <div class="clearfix visible-xs-block">
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6">
                            <div class="rooms_list_desc">
                                <h3>' . $rsp['hotel_name'] . '</h3>
                                <p><b><i class="icon-clock"></i> Duration - </b>' . $rsp['duration'] . '<br>
                                    <b><i class="icon-moon"></i> Night Halt - </b>' . $rsp['nighthalt'] . '</p>
                                <p><span class="snap2">Day- ' . sprintf("%02d", abs($rsesult['iday'])) . '</span></p>
                                    ' . $details . '
                            </div>
                        </div>
                        <div class="col-lg-2 col-md-2 col-sm-2">
                            <div class="price_list">
                                <div>
                                    <sup>₹</sup>' . $rsp['startingprice'] . '*<span class="normal_price_list"></span><small>/Per Person</small>
                                    <p>
                                        <a class="btn_1" href="' . $gen->make_link($rsp['package_link'], '') . '" title="' . $rsp['hotel_name'] . '">View Package</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>';
            }
        }
        echo $message;
        ?>
    </div>
    <div class="row clearfix" id="searchbtn"> 
        <div class="col-md-2 col-md-offset-10 padd-6">
            <button class="btn btn-info btn-block " onclick="loadList(2);" id="lodeine"  data-loading-text="<i class='fa fa-cog fa-spin fa-fw margin-bottom'></i> Loading...">Load More</button>    
        </div>

    </div>
    <input type="hidden" name="currentpage" id="currentpage" value="2"/>
</div>
</section>
<script>
//    $(window).scroll(function () {
//        if (parseInt($('#currentpage').val()) > 0) {
//            if ($(window).scrollTop() == $(document).height() - $(window).height()) {
//                //This is an Ajax method which will fetch the data from server
//                loadList();
//            }
//        }
//    });
    function loadList(type) {
        page = parseInt($('#currentpage').val());
        if (type == 1) {
            $("#packagedata").html('');
            page = 1;
        }

        var $btnSearch = $('#searchine').button('loading');
        var $btnSearch2 = $('#lodeine').button('loading');

        formData = $("#searchapanel :input").serialize() + "&page=" + page
        $.ajax({
            type: "GET",
            cache: false,
            async: true,
            url: "index.php?ajax=1&loaderfile=packagesearch_list",
            data: formData,
            dataType: "json",
            success: function (data) {
                if (data.status == true) {
                    $('#currentpage').val(page + 1);
                    $("#packagedata").append(data.messsage);
                    loadList('');
                } else {
                    $('#currentpage').val(0);
                    //$("#packagedata").append('<div class="col-md-12"><p class="text text-danger"><i class="fa fa-exclamation-circle"></i> Sorry No Packages Found</p></div>');
                    $('#searchbtn').addClass("hidden");
                }
                $btnSearch.button('reset');
                $btnSearch2.button('reset');
            }
        });
    }

    loadList(2);
</script>


