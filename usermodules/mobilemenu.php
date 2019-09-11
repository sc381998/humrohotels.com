<?php

if (!defined('included')) {
    exit();
}

function MobileMenu($mid, $destinationid) {
    global $ai_pagemaster;
    global $SID;
    global $dbf;
    global $gen;
    global $pagefile;
    global $ai_hotelmaster;
    if ($destinationid == 0) {
        $sd = "select *,(select  pagelink from $ai_pagemaster where id=a.otherpage_id and status=1) as parentpagelink from $ai_pagemaster a where  status=1 and parent=$mid  order by link_order";
        $rst = $dbf->query($sd);
        if (count($rst) > 0) {
            echo '<ul>';
            foreach ($rst as $rs) {

                $pagelink = empty($rs['pagelink']) ? $rs['parentpagelink'] : $rs['pagelink'];
                $actieClass = '';
                if ($rs['pagelink'] == $pagefile) {
                    $actieClass = 'class="mm-selected"';
                }
                echo '<li itemscope itemtype="http://www.schema.org/SiteNavigationElement" ' . $actieClass . ' ><a itemprop="url" href="' . $gen->make_link($pagelink, '') . '"> <span itemprop="name">' . $rs['link_name'] . '</span></a>';
                echo MobileMenu($rs['id'], $rs['destinationid']);
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "";
        }
    }
    else{
        $sd = "select id,hotel_name as link_name,link_title, totalrooms as link_order,package_link as pagelink, '' as target ,'' as parentpagelink,0 as destinationid,0 as packageid from $ai_hotelmaster where destinations ='$destinationid' and status=1";
        $rst = $dbf->query($sd);
        if (count($rst) > 0) {
            echo '<ul>';
            foreach ($rst as $rs) {

                $pagelink = empty($rs['pagelink']) ? $rs['parentpagelink'] : $rs['pagelink'];
                $actieClass = '';
                if ($rs['pagelink'] == $pagefile) {
                    $actieClass = 'class="mm-selected"';
                }
                echo '<li itemscope itemtype="http://www.schema.org/SiteNavigationElement" ' . $actieClass . ' ><a itemprop="url" href="' . $gen->make_link($pagelink, '') . '"> <span itemprop="name">' . $rs['link_name'] . '</span></a>';
               
                echo "</li>";
            }
            echo "</ul>";
        } else {
            echo "";
        }
    }
}

$rstb = $dbf->query("select *,(select  pagelink from $ai_pagemaster where id=a.otherpage_id and status=1) as parentpagelink from $ai_pagemaster a where status=1 and parent=0 and position in ('Top','Left') order by link_order");
if (count($rstb) > 0) {

    echo '<ul>';
    foreach ($rstb as $rs) {
        $pagelink = empty($rs['pagelink']) ? $rs['parentpagelink'] : $rs['pagelink'];
        $actieClass = '';
        if ($rs['pagelink'] == $pagefile) {
            $actieClass = 'class="mm-selected"';
        }
        echo '<li itemscope itemtype="http://www.schema.org/SiteNavigationElement" ' . $actieClass . '><a itemprop="url" href="' . $gen->make_link($pagelink, '') . '"> <span itemprop="name">' . $rs['link_name'] . '</span></a>';

        MobileMenu($rs['id'], $rs['destinationid']);
        echo "</li>";
    }
    echo "</ul>";
}
?>
