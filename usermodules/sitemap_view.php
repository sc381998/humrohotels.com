<?php
if (!defined('included')) {
    exit();
}

$siteURL = $website;

$changeFrequency = 'monthly';



$siteMapTxt = '<?xml version="1.0" encoding="UTF-8"?>
<urlset
      xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
      xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
      xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9
            http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd">

<!-- Created by www.apexinvention.com on ' . date("Y-m-d H:i:s") . ' -->

<url>
  <loc>https://' . $siteURL . '</loc>
  <changefreq>' . $changeFrequency . '</changefreq>
  <priority>1.0</priority>
</url>';

function SiteMapTree($mid) {
    global $ai_pagemaster;
    global $ai_hotelmaster;
    global $SID;
    global $dbf;
    global $gen;
    global $pagefile;
    global $siteMapTxt;
    global $changeFrequency;

    $priority = 0.5;

    $sd = "select id,link_name,link_title,pagelink, target,(select  pagelink from ai_pagemaster where id=a.parent) as parentpagelink,destinationid from $ai_pagemaster a where  status=1 and parent=$mid  order by link_order";

    $rst = $dbf->query($sd);
    if (count($rst) > 0) {
        echo '<ul>';


        foreach ($rst as $rs) {


            $siteMapTxt .= '
                            <url>
                              <loc>' . $gen->make_link($rs['pagelink'], '') . '</loc>
                              <changefreq>' . $changeFrequency . '</changefreq>
                              <priority>' . $priority . '</priority>
                            </url>';
            $t = $dbf->GetOneVal("select count(id) from $ai_pagemaster where parent=$rs[id] and status=1");
            $hasrow = $t == 0 ? '' : 'branch';
            $hasrowicon = $t == 0 ? '' : '<i class="fa fa-chevron-down" aria-hidden="true"></i>';
            echo '<li class="' . $hasrow . '"><a title="' . $rs['link_title'] . '" href="' . $gen->make_link($rs['pagelink'], '') . '">' . $rs['link_name'] . '</a>' . $hasrowicon;

            $pql = $dbf->query("SELECT * FROM $ai_hotelmaster where status=1 and destinations =$rs[destinationid] order by totalrooms");

            if (count($pql) > 0) {
                echo '<ul>';
                foreach ($pql as $prs) {

                    echo '<li><a title="' . $prs['hotel_name'] . '" href="' . $gen->make_link($prs['package_link'], '') . '">' . $prs['hotel_name'] . '</a></li>';

                    $siteMapTxt .= '
                            <url>
                              <loc>' . $gen->make_link($prs['package_link'], '') . '</loc>
                              <changefreq>' . $changeFrequency . '</changefreq>
                              <priority>' . $priority . '</priority>
                            </url>';
                }
                echo '</ul>';
            }

            echo SiteMapTree($rs['id']);
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "";
    }
}

$rst = $dbf->query("select id,link_name,link_title,pagelink,target,(select  pagelink from ai_pagemaster where id=a.parent) as parentpagelink,destinationid from $ai_pagemaster a where status=1 and parent=0 order by link_order");
?>

<div class="container" style="padding: 40px;">
<h1>Site Map</h1>
<?php
if (count($rst) > 0) {


    echo '<ul class="tree">';


    foreach ($rst as $rs) {

        $t = $dbf->GetOneVal("select count(id) from $ai_pagemaster where parent=$rs[id] and status=1");
        $hasrow = $t == 0 ? '' : 'branch';
        $hasrowicon = $t == 0 ? '' : '<i class="fa fa-chevron-down" aria-hidden="true"></i>';
        $pagelink = empty($rs['pagelink']) ? $rs['parentpagelink'] : $rs['pagelink'];
        echo '<li class="' . $hasrow . '"><a title="' . $rs['link_title'] . '" href="' . $gen->make_link($pagelink, '') . '" target="' . $rs['target'] . '">' . $rs['link_name'] . '</a>' . $hasrowicon;
        SiteMapTree($rs['id']);
        echo "</li>";


        $siteMapTxt .= '
                            <url>
                              <loc>' . $gen->make_link($pagelink, '') . '</loc>
                              <changefreq>' . $changeFrequency . '</changefreq>
                              <priority>1.0</priority>
                            </url>
			';
    }
    echo "</ul>";
}
?>

</div>

<style>
    .tree, .tree ul {
        margin:0;
        padding:0;
        list-style:none
    }
    .tree ul {
        margin-left:1em;
        position:relative
    }
    .tree ul ul {
        margin-left:.5em
    }
    .tree ul:before {
        content:"";
        display:block;
        width:0;
        position:absolute;
        top:0;
        bottom:0;
        left:0;
        border-left:1px solid
    }
    .tree li {
        margin: 0px;
        padding: 0 4em;
        /* line-height: 2em; */
        color: #369;
        font-weight: 700;
        position: relative;
    }
    .tree ul li:before {
        content:"";
        display:block;
        width:10px;
        height:0;
        border-top:1px solid;
        margin-top:-1px;
        position:absolute;
        top:1em;
        left:0
    }
    .tree ul li:last-child:before {
        background:#fff;
        height:auto;
        top:1em;
        bottom:0
    }
    .indicator {
        margin-right:5px;
    }
    .tree li a {
/*        text-decoration: none;
        color: #86b535;
        font-size: 12px;
        line-height: 18px;
        background: #f9f8f8;
        margin: 5px 0px;
        padding: 4px 0px;*/
    text-decoration: none;
    border: none;
    font-family: inherit;
    font-size: inherit;
    color: #86b535;
    background: #fff;
    cursor: pointer;
    padding: 5px 20px;
    display: inline-block;
    outline: none;
    font-size: 12px;
    border-radius: 20px;
    font-weight: bold;
    text-transform: uppercase;
/*    margin: 0 0 5px 0;*/
    }
    .tree li a:hover{
            background: #333;
    color: #fff !important;
    }
    .tree li button, .tree li button:active, .tree li button:focus {
        text-decoration: none;
        color:#369;
        border:none;
        background:transparent;
        margin:0px 0px 0px 0px;
        padding:0px 0px 0px 0px;
        outline: 0;
    }
</style>


<?php
$siteMapTxt .= '
</urlset>';
$fname = 'sitemap.xml';
$file = fopen($fname, "w");
fwrite($file, $siteMapTxt);
fclose($file);
?>