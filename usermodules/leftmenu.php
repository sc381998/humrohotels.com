<?php
if (!defined('included')) {
    exit();
}

$rst = $dbf->query("select id,link_name,link_title,pagelink,target,(select  pagelink from $ai_pagemaster where id=a.otherpage_id and status=1) as parentpagelink from $ai_pagemaster a where status=1 and parent=0 and position='Left' order by link_order");
?>
<div class="leftmenu" id="accordion">

    <?php
    if (count($rst) > 0) {

        $d = 1;
        foreach ($rst as $rs) {

            $t = $dbf->GetOneVal("select count(id) from $ai_pagemaster where parent=$rs[id] and status=1");
            $hasrow = $t == 0 ? '' : ' <a class="drop" data-toggle="collapse" data-parent="#accordion" href="#collapse' . $d . '"><i class="fa fa-angle-down"></i></a>';
            $actieClass = '';
            if ($rs['pagelink'] == $pagefile) {
                $actieClass = 'active';
            }
            ?>

            <div class="panel">
                <?php
                $pagelink = empty($rs['pagelink']) ? $rs['parentpagelink'] : $rs['pagelink'];
				$target = empty($rs['target'])?'':'target="'.$rs['target'].'"';
                echo '<a class="menu" title="' . $rs['link_title'] . '" href="' . $gen->make_link($pagelink, '') . '" ' . $target . '>' . $rs['link_name'] . '</a>';
                echo $hasrow;
                ?>


                <?php
                if ($t > 0) {
                    $sd = "select id,link_name,link_title,pagelink, target,(select  pagelink from $ai_pagemaster where id=a.otherpage_id and status=1) as parentpagelink from $ai_pagemaster a where  status=1 and parent=$rs[id]  order by link_order";
                    $rst1 = $dbf->query($sd);
                    ?>
                    <div id="collapse<?php echo $d; ?>" class="panel-collapse collapse">
                        <ul>
                            <?php
                            foreach ($rst1 as $rs1) {
                                $pagelink = empty($rs1['pagelink']) ? $rs1['parentpagelink'] : $rs1['pagelink'];
                                echo '<li><a title="' . $rs1['link_title'] . '" href="' . $gen->make_link($pagelink, '') . '">' . $rs1['link_name'] . '</a></li>';
                            }
                            ?>
                        </ul>
                    </div>

                    <?php
                }
                $d++;
                ?>

            </div>
            <?php
        }
    }
    ?>

</div>

