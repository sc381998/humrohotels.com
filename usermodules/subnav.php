<?php
if (!defined('included')) {
    exit();
}

//if ($pageparentid != 0) {








if ($deviceType == 'computer') {
    echo '<div class="container">';
    if ($subsection == 1) {
        // NAV
        $destinationid = $dbf->GetOneVal("select destinationid from $ai_pagemaster where id=$pageid and status=1");
        $sq = "select id,hotel_name as link_name,link_title,package_link as pagelink,priority as link_order, '' as target, '' as parentpagelink,0 as destinationid,0 as packageid from $ai_hotelmaster where destinations =$destinationid and status=1 "
                . " union select id,link_name,link_title,pagelink,link_order, target,(select  pagelink from $ai_pagemaster where id=a.parent and status=1) as parentpagelink, destinationid,packageid from $ai_pagemaster a where status=1 and parent=$pageid  order by link_order";
        $rst = $dbf->query($sq);


        $i = 0;
        if (count($rst) == 0) {
            $rst = $dbf->query("select *,(select  pagelink from $ai_pagemaster where id=a.parent and status=1) as parentpagelink from $ai_pagemaster a where status=1 and parent=$pageparentid  and position='$link_position' order by link_order");
            //echo   "select * from $ai_pagemaster where status=1 and parent=$pageparentid  and position='$position' order by link_order";
        }

        if (count($rst) > 0) {
            $i++;
            foreach ($rst as $rs) {

                echo '<div class="col-sm-3 submenus">';
                if ($pageid != $rs['id']) {
                    $pagelink = empty($rs['pagelink']) ? $rs['parentpagelink'] : $rs['pagelink'];
                    echo '<a href="' . $gen->make_link($pagelink, '') . '" title="' . $rs['link_title'] . '" >' . $rs['link_name'] . '</a>';
                } else {
                    echo '<span> ' . $rs['link_name'] . '</span>';
                }

                echo '</div>';
            }
        }
    }

   # print_r($_SESSION);
    if (!empty($_SESSION['usrname']) && !empty($_SESSION['group_id']) && !empty($_SESSION['u_id'])) {
        
        $eurl = '';
        $etit = 'EDIT THIS PAGE';

        if ($hotelview == 1 || $pageid == 0) {
            $etit = 'EDIT THIS PACKAGE';
            $packageid = $dbf->GetOneVal("SELECT id from $ai_hotelmaster where package_link = '$pagefile' and status=1");
            $eurl = "ai-control/index.php?sourse=controler&path=hotelmaster&goto=hotelmaster_edit" . $SID . '&amp;id=' . $gen->sanitizeMe($packageid) . '&amp;saltchk=' . $gen->saltGen($packageid);
        } else {
            $eurl = "ai-control/index.php?sourse=controler&path=pagemaster&goto=pagemaster_edit" . $SID . '&amp;id=' . $gen->sanitizeMe($pageid) . '&amp;saltchk=' . $gen->saltGen($pageid);
        }
        ?>
        <div class="col-sm-3 submenus" style="background: #F60;">
            <a target="_blank" style=" color: #FFF;" href="<?php echo $eurl; ?>"><b><i class="fa fa-pencil"></i> <?PHP echo $etit; ?></b></a>
        </div>
        <?php
    }
    echo '</div>';
}
?>
