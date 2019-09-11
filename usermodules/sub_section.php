<?php
if (!defined('included')) {
    exit();
}
$destinationid = $dbf->GetOneVal("select destinationid from $ai_pagemaster where id=$pageid and status=1");
$sql = "SELECT * FROM $ai_hotelmaster where status=1 and destinations =$destinationid order by totalrooms";
$subQ = $dbf->query($sql);
if (count($subQ) > 0) {
    ?>
    
        <div class="container">
            <div class="row">
                <?php
                foreach ($subQ as $rsp) {
                    ?>
                    <div class="col-md-4">
                        <div class="box_style_1 wow fadeInUp" data-wow-duration="1500ms">
                            
                                <h3><?php echo $rsp['hotel_name']; ?></h3>
                                <div class="text">
                                    <p><b><i class="icon-clock"></i> </b><?php echo $rsp['duration']; ?><br>
                                        <b><i class="icon-moon"></i> </b><?php echo $rsp['nighthalt']; ?> </p>
                                </div>
                                <a class="btn_1" href="<?php echo $gen->make_link($rsp['package_link'], ''); ?>" title="<?php echo $rsp['hotel_name']; ?>">View +</a>
                           
                        </div>
                    </div>           
                    <?php
                }
                ?>

            </div>
        </div>
    
    <?php
}
$sql = "SELECT link_name, main_content, pagelink,link_title FROM $ai_pagemaster WHERE parent = $pageid and status=1";

$subQ = $dbf->query($sql);
if (count($subQ) > 0) {
    ?>

    
        <div class="container">
            <div class="row">
               
                            <?php
                            $i = 0;
                            foreach ($subQ as $rs) {
                                $content = strip_tags($rs['main_content']);
                                if (!empty($content)) {
                                    $i++;
                                    $page_link = empty($rs['pagelink']) ? $rs['parentpagelink'] : $rs['pagelink'];

                                    if ($i == 1) {
                                        echo '';
                                    }
                                    ?>
                                    <div class="col-md-4 col-sm-4 col-xs-12">
                                        <div class="box_style_1 wow fadeInUp" data-wow-duration="1500ms">

                                           

                                                <h3><?php echo $rs['link_name']; ?></h3>

                                                <p style="height: 100px;">
                                                 
                                                    <?php
                                                    if (strlen($content) > 200 && strpos($content, ' ', 200) > 0) {
                                                        echo substr($content, 0, strpos($content, ' ', 200)) . '...';
                                                    } else {
                                                        echo $content;
                                                    }
                                                    ?>
                                                </p>
                                                <a class="btn_1" href="<?php echo $gen->make_link($page_link, ''); ?>"> View + </a>
                                           
                                        </div>

                                    </div>
            <?php
            if ($i == 3) {
                echo ' ';
                $i = 0;
            }
        }
    }
    ?>
                      

            </div>
        </div>
  


    <?php
}
?>
