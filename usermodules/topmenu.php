<?php
if (!defined('included')) {
    exit();
}
$rst = $dbf->query("select id,link_name,link_title,pagelink,target,destinationid,packageid,(select  pagelink from $ai_pagemaster where id=a.otherpage_id) as parentpagelink,mega_menu from $ai_pagemaster a where status=1 and parent=0 and position='Top' order by link_order");
if (count($rst) > 0) {
    if ($deviceType != 'computer') {
        ?>
        <ul class="nav navbar-nav">
            <?php
            foreach ($rst as $rs) {
                $pagelink = '';
                $link_title = '';
                $link_name = '';
                $megamenu = $rs['mega_menu'];
               
                if (!empty($rs['packageid']) && $rs['packageid'] != 0) {
                    $s = "select * from $ai_hotelmaster where id = $rs[packageid]";
                    $prs = $dbf->query($s, false);
                    $pagelink = $prs['package_link'];
                    $link_title = $prs['link_title'];
                    $link_name = $prs['hotel_name'];
                } else {
                    $pagelink = empty($rs['pagelink']) ? $rs['parentpagelink'] : $rs['pagelink'];
                    $link_title = $rs['link_title'];
                    $link_name = $rs['link_name'];
                }
                $isdestination = false;

                $t = 0;
                if (!empty($rs['destinationid']) && $rs['destinationid'] != 0) {
                    $t = $dbf->GetOneVal("select count(id) from $ai_hotelmaster where destinations = '$rs[destinationid]' and status=1");
                    $isdestination = true;
                } else {
                    $t = $dbf->GetOneVal("select count(id) from $ai_pagemaster where  status=1 and parent=$rs[id]");
                }
                if ($megamenu == 0) {
                    $liclass = $t == 0 ? '' : 'class="dropdown"'; //megamenu submenu
                    $aclass = $t == 0 ? '' : 'data-toggle="dropdown" data-submenu="" aria-expanded="false"'; //show-submenu-mega
                } else {
                    $liclass = $t == 0 ? '' : 'class="dropdown"';
                    $aclass = $t == 0 ? '' : 'data-toggle="dropdown" data-submenu="" aria-expanded="false"';
                }
                $drop = $t == 0 ? '' : '<span class="caret"></span>';
                ?>
                <li itemscope itemtype="http://www.schema.org/SiteNavigationElement" <?php echo $liclass; ?>>
                    <a <?php echo $aclass; ?> itemprop="url" title="<?php echo $link_title; ?>" href="<?php echo $gen->make_link($pagelink, ''); ?>" <?php
                    if (!empty($rs['target'])) {
                        echo 'target="' . $rs['target'] . '"';
                    }
                    ?>>
                        <span itemprop="name"><?php echo $link_name . ' ' . $drop; ?></span></a>
                    <?php
                    if ($t > 0) {
                        $sd = '';
                        if ($isdestination) {
                            $sd = "select id,hotel_name as link_name,link_title,priority as link_order,package_link as pagelink, '' as target, '' as parentpagelink,0 as destinationid,0 as packageid from $ai_hotelmaster where destinations ='$rs[destinationid]'and status=1 order by totalrooms "//limit 3
                                    . " union select id,link_name,link_title,link_order,pagelink,target,(select  pagelink from $ai_pagemaster where id=a.parent) as parentpagelink,destinationid,packageid from $ai_pagemaster a where  status=1 and parent=$rs[id]  order by link_order "; //limit 3
                        } else {
                            $sd = "select id,link_name,link_title,pagelink,link_order, target,destinationid,packageid,(select  pagelink from $ai_pagemaster where id=a.parent) as parentpagelink from $ai_pagemaster a where  status=1 and parent=$rs[id]  order by link_order "; //limit 3
                        }
                        $rst1 = $dbf->query($sd);

                        $style = 'class="col-sm-3"';


                        /* if (count($rst1) == 1) {
                          $style = 'class="col-sm-12"';
                          }
                          else if (count($rst1) == 2) {
                          $style = 'class="col-sm-6"';
                          } */

                        $i = 0;
                        if (count($rst1) > 0) {
                            if ($i != 0) {
                                $style = 'class="col-sm-8"';
                            }
                            $i++;
                            if ($megamenu == 1) {
                                ?>
                                <div class="menu-wrapper">

                                    <?php
                                } else {
                                    ?>
                                    <ul>
                                        <?php
                                    }
                                    foreach ($rst1 as $rs1) {
                                        $pagelink1 = empty($rs1['pagelink']) ? $rs1['parentpagelink'] : $rs1['pagelink'];
                                        $isdestination1 = false;
                                        if (!empty($rs1['destinationid']) && $rs1['destinationid'] != 0) {
                                            $isdestination1 = true;
                                        }
                                        if ($megamenu == 1) {
                                            ?>
                                            <div <?php echo $style; ?>> <ul> <?php } ?>

                                                <li itemscope itemtype="http://www.schema.org/SiteNavigationElement" class="dropdown-header">
                                                    <?php # echo $rs1['link_name'];  ?>
                                                 <!--  <a itemprop="url" title="<?php echo $rs1['link_title']; ?>" href="<?php echo $gen->make_link($pagelink1, ''); ?>" <?php
                                                    if (!empty($rs1['target'])) {
                                                        echo 'target="' . $rs1['target'] . '"';
                                                    }
                                                    ?>>
                                                        <span itemprop="name"><?php echo $rs1['link_name']; ?></span>
                                                    </a>-->
                                                </li>
                                                <?php
                                                $sd2 = '';
                                                if ($isdestination1) {
                                                    $sd2 = "select id,hotel_name as link_name,link_title, totalrooms as link_order,package_link as pagelink, '' as target ,'' as parentpagelink,0 as destinationid,0 as packageid from $ai_hotelmaster where destinations ='$rs1[destinationid]' and status=1 ";
//                                                            . " union select id,link_name,link_title,link_order,pagelink, target,(select  pagelink from $ai_pagemaster where id=a.parent) as parentpagelink,destinationid,packageid from $ai_pagemaster a where  status=1 and parent=$rs1[id]  order by link_order limit 7";
                                                } else {
                                                    $sd2 = "select id,link_name,link_title,pagelink,link_order, target,destinationid,packageid,(select  pagelink from $ai_pagemaster where id=a.parent) as parentpagelink from $ai_pagemaster a where  status=1 and parent=$rs1[id]  order by link_order limit 7";
                                                }

                                                $rst2 = $dbf->query($sd2);

                                                if (count($rst2) > 0) {

                                                    foreach ($rst2 as $rs2) {
                                                        $pagelink2 = empty($rs2['pagelink']) ? $rs2['parentpagelink'] : $rs2['pagelink'];
                                                        ?>
                                                        <li itemscope itemtype="http://www.schema.org/SiteNavigationElement"><a itemprop="url" title="<?php echo $rs2['link_title']; ?>" href="<?php echo $gen->make_link($pagelink2, ''); ?>" <?php
                                                            if (!empty($rs2['target'])) {
                                                                echo 'target="' . $rs2['target'] . '"';
                                                            }
                                                            ?>><span itemprop="name"><?php echo $rs2['link_name']; ?></span></a>
                                                        </li>
                                                        <?php
                                                    }
                                                }
                                                if ($megamenu == 1) {
                                                    ?>

                                                </ul></div>
                                            <?php
                                        }
                                    }
                                    if ($megamenu == 1) {
                                        ?>
                                </div>


                                <?php
                            } else {
                                ?>
                        </ul>
                        <?php
                    }
                }
            }
            ?>
            </li>
            <?php
        }
        ?>
        </ul>


        <?php
    } else {
       
        ?>
        <div class="main-menu">
            <nav>
                <ul class="nav navbar-nav">
                    <?php
                    foreach ($rst as $rs) {
                        $pagelink = '';
                        $link_title = '';
                        $link_name = '';
                        $megamenu = $rs['mega_menu'];
                       
                        if (!empty($rs['packageid']) && $rs['packageid'] != 0) {
                            $s = "select * from $ai_hotelmaster where id = $rs[packageid]";
                            $prs = $dbf->query($s, false);
                            $pagelink = $prs['package_link'];
                            $link_title = $prs['link_title'];
                            $link_name = $prs['hotel_name'];
                        } else {
                            $pagelink = empty($rs['pagelink']) ? $rs['parentpagelink'] : $rs['pagelink'];
                            $link_title = $rs['link_title'];
                            $link_name = $rs['link_name'];
                        }
                        $isdestination = false;

                        $t = 0;
                        if (!empty($rs['destinationid']) && $rs['destinationid'] != 0) {
                            $t = $dbf->GetOneVal("select count(id) from $ai_hotelmaster where destinations = '$rs[destinationid]' and status=1");
                            $isdestination = true;
                        } else {
                            $t = $dbf->GetOneVal("select count(id) from $ai_pagemaster where  status=1 and parent=$rs[id]");
                        }
//                        printf($megamenu);
                        if ($megamenu == 0) {
                            $liclass = $t == 0 ? 'class="top-hover"' : 'class="top-hover"'; //megamenu submenu
                            $aclass = $t == 0 ? '' : ''; //show-submenu-mega
                        } else {
                            $liclass = $t == 0 ? '' : 'class="mega-menu-position top-hover"';
                            $aclass = $t == 0 ? '' : '';
                        }
                        $drop = $t == 0 ? '' : '';
                        ?>
                        <li itemscope itemtype="http://www.schema.org/SiteNavigationElement" <?php echo $liclass; ?>>
                            <a <?php echo $aclass; ?> itemprop="url" title="<?php echo $link_title; ?>" href="<?php echo $gen->make_link($pagelink, ''); ?>" <?php
                            if (!empty($rs['target'])) {
                                echo 'target="' . $rs['target'] . '"';
                            }
                            ?>>
                                <span itemprop="name"><?php echo $link_name . ' ' . $drop; ?></span></a>
                            <?php
                            if ($t > 0) {
                                $sd = '';
                                if ($isdestination) {
                                    $sd = "select id,hotel_name as link_name,link_title,priority as link_order,package_link as pagelink, '' as target, '' as parentpagelink,0 as destinationid,0 as packageid from $ai_hotelmaster where destinations ='$rs[destinationid]'and status=1 order by totalrooms "//limit 3
                                            . " union select id,link_name,link_title,link_order,pagelink,target,(select  pagelink from $ai_pagemaster where id=a.parent) as parentpagelink,destinationid,packageid from $ai_pagemaster a where  status=1 and parent=$rs[id]  order by link_order "; //limit 3
                                } else {
                                    $sd = "select id,link_name,link_title,pagelink,link_order, target,destinationid,packageid,(select  pagelink from $ai_pagemaster where id=a.parent) as parentpagelink from $ai_pagemaster a where  status=1 and parent=$rs[id]  order by link_order "; //limit 3
                                }
                                $rst1 = $dbf->query($sd);

                                $style = 'class="col-sm-3"';

                                $i = 0;
                                if (count($rst1) > 0) {
                                    if ($i != 0) {
                                        $style = 'class="col-sm-3"';
                                    }
                                    $i++;
                                    ?>
                                    <ul class="submenu">
                                        <?php
                                        foreach ($rst1 as $rs1) {
                                            $pagelink1 = empty($rs1['pagelink']) ? $rs1['parentpagelink'] : $rs1['pagelink'];
                                            $isdestination1 = false;
                                            if (!empty($rs1['destinationid']) && $rs1['destinationid'] != 0) {
                                                $isdestination1 = true;
                                            }
                                            ?>  

                                                <?php
                                                $sd2 = '';
                                                if ($isdestination1) {
                                                    $sd2 = "select id,hotel_name as link_name,link_title, totalrooms as link_order,package_link as pagelink, '' as target ,'' as parentpagelink,0 as destinationid,0 as packageid from $ai_hotelmaster where destinations ='$rs1[destinationid]' and status=1 ";
//                                                            . " union select id,link_name,link_title,link_order,pagelink, target,(select  pagelink from $ai_pagemaster where id=a.parent) as parentpagelink,destinationid,packageid from $ai_pagemaster a where  status=1 and parent=$rs1[id]  order by link_order limit 7";
                                                } else {
                                                    $sd2 = "select id,link_name,link_title,pagelink,link_order, target,destinationid,packageid,(select  pagelink from $ai_pagemaster where id=a.parent) as parentpagelink from $ai_pagemaster a where  status=1 and parent=$rs1[id]  order by link_order limit 7";
                                                }

                                                $rst2 = $dbf->query($sd2);
                                                 
                                                if (count($rst2) > 0) {

                                                    foreach ($rst2 as $rs2) {
                                                        $pagelink2 = empty($rs2['pagelink']) ? $rs2['parentpagelink'] : $rs2['pagelink'];
                                                        ?>
                                           <li class="abc" itemscope itemtype="http://www.schema.org/SiteNavigationElement"><a itemprop="url" title="<?php echo $rs2['link_title']; ?>" href="<?php echo $gen->make_link($pagelink2, ''); ?>" <?php
                                                        if (!empty($rs2['target'])) {
                                                            echo 'target="' . $rs2['target'] . '"';
                                                        }
                                                        ?>><span itemprop="name"><?php echo $rs2['link_name']; ?></span></a>
                                                    </li>
                                                    <?php
                                                }
                                            }
                                            ?>               
                                    <?php
                                }
                                ?>
                            </ul>
                            <?php
                        }
                    }
                }
                ?>
                </li>
                </ul>
            </nav>
        </div>
        <?php
    }
}
?>





