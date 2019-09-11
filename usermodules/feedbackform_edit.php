<?php 

if (!defined('included')) {
    exit();
}
if (!class_exists('CheckCaptcha')) {
    include(ROOT_PATH . "complex/checkme.php");
}
echo 'sagar1';

$contact_form = false;
$rateval = array(1 => 'Terrible', 2 => 'Poor', 3 => 'Average', 4 => 'Very good', 5 => 'Excellent');
$msg = '';
$fromname = '';
$emailmail = '';
$to = '';
$subject = '';
$content = '';
$datetime = '';
$status = '0';
$body_Find = '';
$body_Replace = '';
$mobile = '';
$formbutton = 'Submit';
$hidden = false;
$formShow = true;
$process = false;
$hasError = true;
$process_cap = true;


if (!empty($_POST)) {
    
    if (isset($_POST['no_cap']) && is_numeric($_POST['no_cap'])) {
        $process_cap = true;
    } else {
        $process_cap = false;
    }

    $fromname = @strip_tags($_POST['name']);
    $emailmail = @strip_tags($_POST['email']);
    $content = @strip_tags($_POST['message']);
    $mobile = @strip_tags($_POST['phone']);
    $pageLink = @$_SERVER['HTTP_REFERER'];

    if (!$gen->validateMe($fromname) || !$gen->validateMe($emailmail, 'email') || !is_numeric($mobile) || !$gen->validateMe($content)) {
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
        $email = $emailmail;
        //$subject = "Feedback from $subject";
        $subject = "$subject from $website";
        $find = array("{SUBJECT}", "{NAME}", "{EMAIL}", "{MOBILE}", "{MESSAGE}", "{PAGE}");
        $replace = array($subject, $fromname, $email, $mobile, $content, $pageLink);
//        print_r($replace);

        $Mailbody = $content; // str_replace($find, $replace, $sysmsg['contact_mail_body']);
        // Prepare the SQL for the Record Addition
        $mSql = "INSERT into $ai_feedback(
                name,
                email,
                phone,
                comment,
                date,
                time,                
                status,
                ip,
                browser
                ) 
                values (				
                :fromname,
                :email,
                :mobile, 
                :Mailbody,
                now(),
                now(),
                :status,
                :ip,
                :browser                                
                )";
        $params = array(
            ':fromname' => $fromname,
            ':email' => $email,
            ':mobile' => $mobile,
            ':Mailbody' => $Mailbody,
            ':status' => $status,
            ':ip' => $ip,
            ':browser' => $browser
        );

        if (!empty($mSql)) {

            $mResult = $dbf->execute($mSql, $params);
            if ($mResult) {
                $msg = $sysmsg['contact_message_success'];
                $formShow = false;
                $hidden = false;
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
