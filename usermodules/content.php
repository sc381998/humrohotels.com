<?php
if (!defined('included')) {
    exit();
}
if ($dbf->checkIP($ip)) {// Check the IP
    echo '<br />' . $sysmsg['ip_blocked'] . '<br />';
} else {

    $ModuleParam = array();
    $TopModules = array();
    $BottomModules = array();

    if (!empty($pageid) && $pageid != 0) {

        #Top modules
        $msl = "select a.*,b.position,b.id as mid from $ai_module a, $ai_page_module b where b.moduleid=a.id and b.pageid=$pageid and b.position='Top' order by module_order";
        #echo $msl;
        $rsm = $dbf->query($msl);

        if (count($rsm) > 0) {

            foreach ($rsm as $rm) {
                if (!empty($rm['module_source'])) {
                    $TopModules[$rm['mid']] = 'usermodules/' . $rm['module_source'] . '.php';
                }
                if (!empty($rm['param'])) {
                    $ModuleParam[$rm['mid']] = $rm['param'];
                }
            }
        }
        #Bottom modules
        $msl = "select a.*,b.position,b.id as mid from $ai_module a, $ai_page_module b where b.moduleid=a.id and b.pageid=$pageid and b.position='Bottom' order by module_order";
        $rsm = $dbf->query($msl);


        if (count($rsm) > 0) {
            foreach ($rsm as $rm) {
                if (!empty($rm['module_source'])) {
                    $BottomModules[$rm['mid']] = 'usermodules/' . $rm['module_source'] . '.php';
                }
                if (!empty($rm['param'])) {
                    $ModuleParam[$rm['mid']] = $rm['param'];
                }
            }
        }
    }


    if (count($TopModules) > 0) {
        foreach ($TopModules as $key => $val) {
            if (array_key_exists($key, $ModuleParam)) {
                $Params = explode(',', $ModuleParam[$key]);
                for ($p = 0; $p < count($Params); $p++) {
                    $Param = explode('=', $Params[$p]);
                    @$ParamName = $Param[0];
                    @$ParamValue = $Param[1];
                    $_GET[$ParamName] = $ParamValue;
                }
            }
            include($TopModules[$key]);
        }
    }

    if ($hotelview == 1) {

        include('usermodules/hoteldetails.php');

        
    } else {

        if ($pageid != 1) {
            $parentarray = array();

            function GetBradcumArray($parentid) {
                global $ai_pagemaster;
                global $record_status;
                global $SID;
                global $dbf;
                global $parentarray;

                $rst = $dbf->query("select a.link_name,a.parent,a.pagelink,a.link_title from $ai_pagemaster a where a.status=1 and a.id=$parentid");
                if (count($rst) > 0) {
                    foreach ($rst as $rs) {
                        $parentarray[] = array($parentid, $rs['link_name'], $rs['pagelink'], $rs['link_title']);
                        GetBradcumArray($rs['parent']);
                    }
                    return $parentarray;
                } else {
                    return $parentarray;
                }
            }

            $pa = GetBradcumArray($pageid);

            $count = 0;
            if (count($pa) > 0) {
                $count++;
                krsort($pa);
                echo '<div id="position" class="breadcumb-wrapper"><div class="container" itemprop="breadcrumb">';
                echo '<ul itemscope itemtype="http://schema.org/BreadcrumbList">';
                echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><a itemprop="item" href="index.php"><span itemprop="name">Home</span></a> <meta itemprop="position" content="1" /></li>';
                $count = 0;
                $Cpos = 1;
                foreach ($pa as $menu) {
                    $count++;
                    $Cpos++;

                    if ($count == count($pa)) {
                        echo '<li>';
                        echo $menu[1];
                    } else {
                        echo '<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';
                        echo '<a itemprop="item" href="' . $gen->make_link($menu[2], '') . '" title="' . $menu[3] . '" ><span itemprop="name">' . $menu[1] . '</span></a><meta itemprop="position" content="' . $Cpos . '" />';
                    }
                    echo '</li>';
                }
                echo '</ul>';
                echo '</div></div>';
            }
        }
        ?>

        <div class="container">
            <div class="row clearfix">
                <div class="col-md-12" id="pagecontent">
                    <?php echo $content; ?>
                </div>

            </div>
        </div>

        <?php
    }


    if (count($BottomModules) > 0) {
        foreach ($BottomModules as $key => $val) {
            if (array_key_exists($key, $ModuleParam)) {
                $Params = explode(',', $ModuleParam[$key]);
                for ($p = 0; $p < count($Params); $p++) {
                    $Param = explode('=', $Params[$p]);
                    @$ParamName = $Param[0];
                    @$ParamValue = $Param[1];
                    $_GET[$ParamName] = $ParamValue;
                }
            }
            include($BottomModules[$key]);
        }
    }
}
?>
