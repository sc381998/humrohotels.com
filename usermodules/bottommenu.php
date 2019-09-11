<?php
if (!defined('included')) {
    exit();
}



$rstb = $dbf->query("select *,(select  pagelink from $ai_pagemaster where id=a.otherpage_id) as parentpagelink from $ai_pagemaster a where status=1 and parent=0 and position='Bottom' order by link_order");
if (count($rstb) > 0) {
    $cc = 1;
    $perrow = count($rstb) / 4;
    ?>


    <?php
    foreach ($rstb as $rs) {

        # if ($cc == 1) {
        ?>
        <div class="col-md-3 col-sm-3 col-xs-12">
            <ul>
                <?php
                #}
                $pagelink = empty($rs['pagelink']) ? $rs['parentpagelink'] : $rs['pagelink'];
                echo '<li><a href="' . $gen->make_link($pagelink, '') . '">' . $rs['link_name'] . '</a></li>';
                $cc++;


                #if ($cc > $perrow) {
                #   $cc = 1;
                ?>
            </ul>
        </div>       
        <?php
        # }
    }
    ?>


    <?php
}
?>