<?php
if (!defined('included')) {
    exit();
}
date_default_timezone_set('Asia/Kolkata');
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


//If the form is submitted
if (!empty($_POST['Send'])) {
    if (isset($_POST['no_cap']) && is_numeric($_POST['no_cap'])) {
        $process_cap = true;
    } else {
        $process_cap = false;
    }

    $fromname = $gen->sanitizeMe(@strip_tags($_POST['fromname']));
    $frommail = $gen->sanitizeMe(@strip_tags($_POST['frommail']), 'email');
    $subject = $gen->sanitizeMe(@strip_tags($_POST['subject']));
    $content = $gen->sanitizeMe(@strip_tags($_POST['message']));
    $mobile = $gen->sanitizeMe(@strip_tags($_POST['mobile']), 'number');
    $pageLink = @$_SERVER['HTTP_REFERER'];

    if (!$gen->validateMe($fromname) || !$gen->validateMe($frommail, 'email') || !is_numeric($mobile) || !$gen->validateMe($content)) {
        $msg = $sysmsg['submit_error'];
        $process = false;
        $hasError = true;
    } elseif ($process_cap == true && $chk->validCaptcha() == false) {
        $hasError = true;
        $process = false;
        $msg = $sysmsg['invalid-captcha'];
    } else {
        $hasError = false;
        $process = true;
    }

    // Alls Right Baby Insert to DB & Mail
    if ($process == true && $hasError == false) {
        $to = $contactemail;
        $from = $frommail;
        //$subject = "Feedback from $subject";
        $subject = "$subject from $website";
        $find = array("{SUBJECT}", "{NAME}", "{EMAIL}", "{MOBILE}", "{MESSAGE}", "{PAGE}");
        $replace = array($subject, $fromname, $from, $mobile, $content, $pageLink);

        $Mailbody = str_replace($find, $replace, $sysmsg['contact_mail_body']);

        // Prepare the SQL for the Record Addition
        $mSql = "INSERT into $ai_webmail(
                name,
                email,
                mobile,
                to_email,
                subject, 
                pagelink,
                content,
                date_time,
                status,
                ip,
                browser
                ) 
                values (				
                :fromname,
                :from,
                :mobile,
                :to,
                :subject,
                :pagelink,
                :Mailbody,
                now(),
                :status,
                :ip,
                :browser                                
                )";
        $params = array(
            ':fromname' => $fromname,
            ':from' => $from,
            ':mobile' => $mobile,
            ':to' => $to,
            ':subject' => $subject,
            ':pagelink' => $pageLink,
            ':Mailbody' => $Mailbody,
            ':status' => $status,
            ':ip' => $ip,
            ':browser' => $browser
        );
        //}
        //echo $mSql;
        if (!empty($mSql)) {
            $mResult = $dbf->execute($mSql, $params);
            if ($mResult) {
                $msg = $sysmsg['contact_message_success'];
                $formShow = false;
                $hidden = false;

                // Send Mail to the designated correspondence address
                // Create the mail body
                @$gen->sendmail($to, $subject, $Mailbody, $fromname, $from, $sign);
                //echo $subject.'<hr>'.$body;
            } else {
                $msg .= $sysmsg['email_error'];
            }
        }
    }
}

if (!empty($msg)) {
    echo '<div class="container"><div class="row"><div class="col-md-12">' . $msg . '</div></div></div>';
}
?>

<?php
if ($formShow != false) {
    ?>

    <div class="contact_us padd-7">
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-12 col-xs-12">
                    <div class="sidebar_tags sidebar-padd">
                        <div class="sec-title">
                            <h2>Contact <span>Us</span></h2>                        
                        </div>
                        <div class="default-form-area">
                            <form name="contact_us" method="post" id="contact" class="sky-form" onSubmit="return validate_contactus()">
                                <div class="row clearfix">
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group">
                                            <label class="input-group-addon"><i class="fa fa-user"></i></label>
                                            <input name="fromname" required class="form-control"  type="text" placeholder="Your Name" value="<?php echo @$fromname; ?>">                                            
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group">
                                            <label class="input-group-addon"><i class="fa fa-envelope"></i></label>
                                            <input type="text" required name="frommail"  class="form-control" placeholder="Your Email Address" value="<?php echo $frommail; ?>">
                                        </div>
                                    </div>

                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group">
                                            <label class="input-group-addon"><i class="fa fa-phone"></i></label>
                                            <input type="tel" required name="mobile"  class="form-control" placeholder="Mobile" value="<?php echo @$mobile; ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6 col-xs-12">
                                        <div class="input-group">
                                            <label class="input-group-addon"><i class="fa fa-list"></i></label>
                                            <select id="subject" required name="subject" class="form-control seleect-boxc">
                                                <option value="">Select the subject</option>
                                                <?php echo $gen->combogen($contactsub, $contactsub, $subject, false); ?>	
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-12 col-sm-12 col-xs-12">
                                        <div class="input-group">
                                            <label class="input-group-addon"><i class="fa fa-comment"></i></label>
                                            <textarea name="message" required rows="2"  class="form-control" placeholder="Your Message"></textarea>
                                        </div>
                                    </div>

                                    <div class="col-md-6">                                    
                                        <?php echo $chk->showCaptcha(); ?>                                    
                                    </div>
                                    <div class="col-md-4">
                                        <input name="Send" type="submit" class="btn btn-success btn-block margin-t-15" id="Send" value="Send Query"> 
                                        <input name="pagelink" type="hidden" id="pagelink" value="<?php echo @$_SERVER['HTTP_REFERER']; ?>" />
                                    </div>
                                </div>      
                            </form>

                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-sm-8 col-xs-12" itemscope itemtype="http://schema.org/LocalBusiness">
                    <div class="sec-title">
                        <h2 style="color:#86b535" itemprop="name">Destination <span>Green</span></h2>
                    </div>
                    <div class="default-cinfo">
                        <div class="acc-content collapsed">
                            <ul class="contact-infos">
                                <li>
                                    <div class="icon_box">
                                        <i class="fa fa-map-marker"></i>
                                    </div>
                                    <div class="text-box" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
                                        <p itemprop="streetAddress"><b>Address:</b> <br> <?php echo $contactaddress; ?></p>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon_box">
                                        <i class="fa fa-phone"></i>
                                    </div>
                                    <div class="text-box">
                                        <p><b>Call Us at:</b> <br> <?php echo $contactphone; ?></p>
                                    </div>
                                </li>
                                <li>
                                    <div class="icon_box">
                                        <i class="fa fa-envelope"></i>
                                    </div>
                                    <div class="text-box">
                                        <p><b>Mail Us:</b> <br> <?php echo $contactemail; ?></p>
                                    </div>
                                </li>
                            </ul>
                        </div>

                    </div>  
                </div>
            </div>
        </div>
    </div>

    <?php
}
?>
