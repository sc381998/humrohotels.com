<?php

# Detect IP Browser of Visitor
$ip = $_SERVER['REMOTE_ADDR'];
$browser = @$_SERVER['HTTP_USER_AGENT'];
// No Show if we missed these both
if (empty($ip) || empty($browser)) {
    exit();
}
session_name('LogID');
session_start();

$templatepage = "template.php";
$pagefile = '';
$SID = '&LogID=' . session_id();

#print_r($_SESSION);

// For included Files
define('included', true);
define('ROOT_PATH', './');
require 'complex/configure.php';
require 'complex/checkme.php';

// Check Website
if (strpos($website, ',') > 0) {
    $websites = explode(',', $website);
    $pos = 0;
    foreach ($websites as $key => $value) {
        $v = str_replace(array('http://', 'www.'), '', $value);
        if (strpos($v, $_SERVER['HTTP_HOST']) === 0) {
            $pos = $key;
        }
    }
    $website = $websites[$pos];
}

if (!file_exists($explorerdir)) {
    mkdir($explorerdir, 0777, true);
}

    if (strlen('http://' . $_SERVER['HTTP_HOST']) < strlen($website)) {
        $website = str_replace('www.', '', $website);
    } elseif (strlen('http://' . $_SERVER['HTTP_HOST']) > strlen($website)) {
        $website = str_replace('http://', 'http://www.', $website);
    } else {
        $website = $website;
    }


require(ROOT_PATH . "complex/mobile.php");
#INITIALIZATION MOBILE DETCT LIBRARY
$detect = new Mobile_Detect;
$deviceType = ($detect->isMobile() ? ($detect->isTablet() ? 'tablet' : 'phone') : 'computer');






# Check is AJAX call
if (!empty($_GET['ajax']) && $_GET['ajax'] == 1 && !empty($_GET['loaderfile'])) {
    # Include the file
    $loaderfile = $_GET['loaderfile'];
    if (is_readable("usermodules/$loaderfile.php")) {
        include("usermodules/$loaderfile.php");
    } else {
        echo '<br />' . '' . $sysmsg['no_data'] . '<br />';
    }
} else {

    $canonical_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    #page content
    if (!empty($_GET['pagefile'])) {
        $pagefile = strtolower($gen->sanitizeMe($_GET['pagefile']));
    } else {
        $pagefile = 'index';
        $canonical_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://") . $website.'/index.php';
    }
    
    
    // $sql = "SELECT * from $ai_pagemaster where status=1 and pagelink = '$pagefile'";
    // $rs2 = $dbf->query($sql, false);

    // $sql2 = "SELECT * from $ai_hotelmaster where status=1 and package_link = '$pagefile'";
    // $rs3 = $dbf->query($sql2, false);
    #echo $sql;


    $pageid = 0;
    $hotelview = 0;
    $pageparentid = 0;
    $subsection = 0;
    $pagetitle = '';
    $pagename = '';
    $position = '';
    $metakey = '';
    $metades = '';
    $content = '';
    $link_position = '';

   //  if (!empty($rs2['link_name'])) {
   //      $pagetitle = $rs2['link_title'];
   //      $pagename = $rs2['link_name'];
   //      $metakey = $rs2['meta_key'];
   //      $metades = $rs2['meta_des'];

   //      $pageid = $rs2['id'];
   //      $position = $rs2['position'];
   //      $subsection = $rs2['submenushow'];
   //      $pageparentid = $rs2['parent'];
   //      $link_position = $rs2['position'];


   //      if ($rs2['linktype'] == 1) {
   //          $content = stripslashes($rs2['main_content']);
   //      } else if ($rs2['linktype'] == 2) {
   //          $content = $rs2['main_content'];
   //      }
   //       else if ($rs2['linktype'] == 4) {
   //          $content = $rs2['main_content'];
   //      }        
   //      else {
   //          $content = '<div id="pagelink"><b><a href="' . $rs2['pagelink'] . '" target="_blank">Please click here to visit the webpage [ ' . $rs2['content'] . ' ] in case it did not open in a new window.</a></b></div>
			// <script language="javascript">window.open("' . $rs2['pagelink'] . '","NewWindow");</script>';
   //      }
   //  } else if (!empty($rs3['hotel_name'])) {

   //      $pagetitle = $rs3['link_title'];
   //      $pagename = $rs3['hotel_name'];
   //      $metakey = $rs3['meta_key'];
   //      $metades = $rs3['meta_des'];
   //      $hotelview = 1;
   //      $pageid = 0;
   //      $position = '';
   //      $subsection = $rs2['submenushow'];
   //      //$pageparentid = $rs2['parent'];
   //      //$link_position = $rs2['position'];
   //  } else {
    
        $pagetitle = $website_name;
        $content = '<br>' . $sysmsg['pagenotfound'];
    // }

    
    if ($deviceType != 'computer') {
        $templatepage = ROOT_PATH . 'template.php';
    }
    if (is_readable($templatepage)) {
        include($templatepage);
    }
}
$pdodb = null;
$dbf = null;
$gen = null;
$AiCrypted = null;
?>