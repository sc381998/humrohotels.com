<?php

if (!defined('included')) {
    exit();
}
$sql = "SELECT * ,(select pic from $ai_packagedestination where id=a.destinations) as 'destination_img' from $ai_hotelmaster a where status=1 ";
#print_r($_GET);

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

$sql .= " order by totalrooms";

$pageNum = 1;
$rowsPerPage = 9;
if (isset($_GET['page']) && is_numeric($_GET['page'])) { ###
    $pageNum = abs($_GET['page']); ###
}
// counting the offset 
$offset = ($pageNum - 1) * $rowsPerPage;
$sql .= " LIMIT $offset, $rowsPerPage";

#echo $sql;
$pkgresult = $dbf->query($sql);
$status = true;
$message = "";
if (count($pkgresult) > 0) {

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
} else {
    $status = false;
    $message = "No Package";
}
echo json_encode(array('status' => $status, 'messsage' => $message));
?>