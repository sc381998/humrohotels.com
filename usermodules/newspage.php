<?php
if (!defined('included')) {
    exit();
}

//if (empty($_GET['panel'])) {
//    exit();
//}

$rst = $dbf->query("select * from $ai_news where status=1 order by notify_date desc, entry_date desc");
if (count($rst) > 0) {
    ?>
    <section id="content">
        <div class="container">
            <div class="row">
                <div class="span12" >
                    <h1>News & Updates</h1>
                    <?php foreach ($rst as $rs) { ?>
                        <article>
                            <div class="row">
                                <div class="review_strip_single" >
                                    <div class="post-quote">
                                        <div class="post-heading">
                                            <h3><a href="#"><?php echo $rs['head']; ?></a></h3>
                                        </div>
                                        <blockquote>
                                            <i class="icon-quote-left"></i> <?php echo $rs['details']; ?>...
                                        </blockquote>
                                    </div>
                                    <div class="row">
                                        <ul class="meta-post ">
                                            <li><i class="icon-calendar"></i><?php echo $gen->makedate($rs['notify_date']); ?>
                                            <!--<a href="#" class="pull-right btn_1">Continue reading <i class="icon-angle-right"></i></a></li><br/>                                   </ul>-->
                                    </div>
                                </div>
                            </div>
                        </article>
                    <?php } ?>             


                </div>
            </div>
        </div>
    </section>
    <?php
}
?>