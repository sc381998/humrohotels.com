<?php
if (!defined('included')) {
    exit();
}
if (!class_exists('CheckCaptcha')) {
    include(ROOT_PATH . "complex/checkme.php");
}
$contact_form = false;
//$map = $location_map;
// Initialise Main Variables
$msg = '';
$fromname = '';
$frommail = '';
$to = '';
$subject = '';
$content = '';
$datetime = '';
$status = '1';
$body_Find = '';
$body_Replace = '';
$mobile = '';
$formbutton = 'Submit';
$hidden = false;
$formShow = true;
$process = false;
$hasError = true;
$process_cap = true;
$adult = '';
$child = '';
$tdate = '';
$fdate = '';
$room = '';
//If the form is submitted
$sql2 = "SELECT *,(select pic from $ai_packagedestination where id=a.destinations) as 'destination_img' from $ai_hotelmaster a where package_link = '$pagefile'";
$rs3 = $dbf->query($sql2, false);
$hotel_id = $rs3['id'];

if (!empty($_POST['Send'])) {
    if (isset($_POST['no_cap']) && is_numeric($_POST['no_cap'])) {
        $process_cap = false;
    }

    $fromname = @strip_tags($_POST['fromname']);
    $frommail = @strip_tags($_POST['frommail']);
    $room = @strip_tags($_POST['room']);
    $mobile = $gen->sanitizeMe(@strip_tags($_POST['mobile']), 'number');
    $pageLink = @$_SERVER['HTTP_REFERER'];
    $adult = @abs(strip_tags($_POST['adult']));
    $child = @abs(strip_tags($_POST['child']));
    $tdate = $gen->sanitizeMe($_POST['tdate'], 'date');
    $fdate = $gen->sanitizeMe($_POST['fdate'], 'date');

    if (!$gen->validateMe($fromname) || !$gen->validateMe($frommail, 'email') || !is_numeric($mobile)) {
        $msg = $sysmsg['submit_error'];
        $process = false;
        $hasError = true;
//    } elseif ($process_cap == true && $chk->validCaptcha() == false) {
//        $hasError = true;
//        $process = false;
//        $msg = $sysmsg['invalid-captcha'];
    } else {
        $hasError = false;
        $process = true;
    }

    // Alls Right Baby Insert to DB & Mail
    if ($process == true && $hasError == false) {
        $to = $contactemail;
        $from = $frommail;
        //$subject = "Feedback from $subject";
        $subject = "$subject";
        $find = array("{SUBJECT}", "{NAME}", "{EMAIL}", "{MOBILE}", "{MESSAGE}", "{PAGE}", "{ADULT}", "{CHILD}", "{DATE}");
        $replace = array($fromname, $from, $mobile, $pageLink, $adult, $child, $gen->makedate($tdate), $gen->makedate($fdate), $room);

//        $Mailbody = str_replace($find, $replace, $sysmsg['package_mail_body']);
        // Prepare the SQL for the Record Addition
        $mSql = "INSERT into $ai_webpackage(
                name,
                email,
                mobile,
                to_email,
                room,
                pagelink,
                date_time,
                date_time1,
                status,
                ip,
                browser,
                adult,
                child
                ) 
                values (				
                :fromname,
                :from,
                :mobile,
                :to,
                :room,
                :pagelink,
                :date_time,
                :date_time1,
                :status,
                :ip,
                :browser,
                :adult,
                :child
                )";
        $params = array(
            ':fromname' => $fromname,
            ':from' => $from,
            ':mobile' => $mobile,
            ':to' => $to,
            ':room' => $room,
            ':pagelink' => $pageLink,
            ':date_time' => $tdate,
            ':date_time1' => $fdate,
            ':status' => $status,
            ':ip' => $ip,
            ':browser' => $browser,
            ':adult' => $adult,
            ':child' => $child
        );
        //}
        //echo $mSql;

        if (!empty($mSql)) {
            $mResult = $dbf->execute($mSql, $params);
//            unset($_POST);
            $fromname = '';
            $frommail = '';
            $adult = '';
            $child = '';
            $tdate = '';
            $fdate = '';
            $room = '';
            $mobile = '';

            if ($mResult) {
                $msg = $sysmsg['contact_message_success'];
//                $formShow = false;
//                $hidden = false;
                // Send Mail to the designated correspondence address
                // Create the mail body
//                @$gen->sendmail($to, $subject, $Mailbody, $fromname, $from, $sign);
                //echo $subject.'<hr>'.$body;
            } else {
                $msg .= $sysmsg['email_error'];
            }
        }
    }
}
$room_type = array();
$sqlc = "SELECT distinct room_type FROM $ai_tariff  where status=1 and hotel_id=$hotel_id";
$queryc = $dbf->query($sqlc);
foreach ($queryc as $key => $rsc) {
    $room_type[$key] = $rsc['room_type'];
}
//if (!empty($msg)) {
//    echo '<div class="container"><div class="row"><div class="col-md-12">' . $msg . '</div></div></div>';
//}
?>

<?php
if ($formShow != false) {
    ?>

    <link href="userassets/css/date_time_picker.css" rel="stylesheet">
    <script src="userassets/js/bootstrap-datepicker.js"></script>

    <div class="sidebar-widget search-area-box-2 hidden-sm hidden-xs clearfix bg-grey">
        <h3>Book Your Rooms</h3>
        <h1>Send Query</h1>
        <div class="search-contents">
            <form name="contact_us" method="post" id="contact" onSubmit="return validate_contactus()">
                <div class="row">
                    <div class="search-your-details">
                        <div class="col-md-12 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <input type="datetime" name="fdate" data-date-format="dd-M-yyyy" required id="fdate" class="btn-default phonepicker" placeholder="Check In" value="<?php echo $fdate; ?>">
                            </div>
                        </div>
                        <div class="col-md-12 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <input type="datetime" name="tdate" data-date-format="dd-M-yyyy" required id="tdate" class="btn-default phonepicker" placeholder="Check Out" value="<?php echo $tdate; ?>">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <input name="fromname" class="btn-default" required  type="text" placeholder="Name" value="<?php echo $fromname; ?>">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <input type="text" name="frommail"  required class="btn-default" placeholder="Email Address" value="<?php echo $frommail; ?>">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <input type="tel" name="mobile" required class="btn-default" placeholder="Mobile" value="<?php echo $mobile; ?>">
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <select class="selectpicker search-fields form-control-2" name="room">
                                    <option value="">Room</option>
                                    <?php echo strtoupper($gen->combogen($room_type, $room_type, $room, false)); ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <input type="number" name="adult" required  class="btn-default" placeholder="Adult(12+ Yrs)" value="<?php echo $adult; ?>">

                            </div>
                        </div>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <input type="number" name="child"  class="btn-default" placeholder="Child (5-12 Yrs)" value="<?php echo $child; ?>">
                            </div>
                        </div>
                        <!--                        <div class="col-md-12 col-sm-6 col-xs-12">
                                                    <div class="form-group">
                        <?php //echo $chk->showCaptcha();  ?>  
                                                    </div>
                                                </div>-->
                        <div class="col-md-12 col-sm-12 col-xs-12">
                            <div class="form-group mrg-btm-10">
                                <button name="Send" id="Send" value="Book Now" class="search-button btn-theme">Book Now</button>
                                <input name="pagelink" type="hidden" id="pagelink" value="<?php echo @$_SERVER['HTTP_REFERER']; ?>" />
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!--    <div class="container">
            <div class="row">

                <div class="box_style_1 col-md-12 col-sm-12 col-xs-12">

                    <h3>Package : <span><?php //echo $pagename;   ?></span></h3>                        
                </div>
            </div>
        </div>-->

    <script>
       var nowTemp = new Date();
        var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);
        var checkin = $('#fdate').datepicker({

            todayHighlight: true,

            beforeShowDay: function (date) {
                return date.valueOf() >= now.valueOf();
            },
            autoclose: true

        });
          var checkout = $('#tdate').datepicker({

            todayHighlight: true,

            beforeShowDay: function (date) {
                return date.valueOf() >= now.valueOf();
            },
            autoclose: true

        });



    </script>
    <?php
}
?>

