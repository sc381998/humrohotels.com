<?php
if (!defined('included')) {
    exit();
}
$rateing = array(
    1 => '<span class="fa fa-star checked"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>',
    2 => '<span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>',
    3 => '<span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star"></span>
                <span class="fa fa-star"></span>',
    4 => '<span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star"></span>',
    5 => '<span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>
                <span class="fa fa-star checked"></span>'
);

$rateval = array(1 => 'Terrible', 2 => 'Poor', 3 => 'Average', 4 => 'Very good', 5 => 'Excellent');
?>

<div class="news-popular-testimonials-section content-area-7">
    <div class="container">
        <div class="row">

            <div class="col-lg-12 col-md-12 col-sm-12">
                <!-- Main title -->
                <div class="main-title">
                    <h1>Testimonials</h1>
                </div>
                <!-- Testimonial 3 start -->
                <div class="testimonials-3 hidden-mb-60">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="text-center">
                                <div id="carousel-custom3" class="carousel slide" data-ride="carousel">
                                    <!-- Indicators -->
                                    <!-- Wrapper for slides -->
                                    <div class="carousel-inner" role="listbox">
                                        <?php
                                        $sql = "select * from $ai_feedback where status=1";                                                                               
                                        $sql .= " order by rateing desc,date desc ";
                                        
                                            $tc = 'active';
                                            $rst = $dbf->query($sql);
                                            foreach ($rst as $rs) {
                                                ?>
                                                <div class="item content <?php echo $tc; ?> clearfix" itemprop="review" itemscope itemtype="http://schema.org/Review">
                                                    <meta itemprop="name" content="<?php echo $rateval[abs($rs['rateing'])] ?>"/>
                                                    <meta itemprop="datePublished" content="<?php echo $rs['date']; ?>"/>
                                                    
                                                    <div class="item-inner">
                                                        <p><?php echo $rs['comment']; ?></p>
                                                        
                                                        <div class="testimonials-avatar">
                                                            <img src="userassets/img/avatar.png" alt="<?php echo $rs['name']; ?>">
                                                        </div>
                                                        
                                                        <div  itemprop="reviewRating" itemscope itemtype="http://schema.org/Rating">
                                                        <meta itemprop="worstRating" content = "1">
                                                        <meta itemprop="ratingValue" content="<?php echo abs($rs['rateing']); ?>"/>
                                                        <meta itemprop="bestRating" content="5"/>
                                                        <span class="rating">(<?php echo abs($rs['rateing']); ?>/5)</span><?php echo $rateing[abs($rs['rateing'])]; ?> 
                                                    </div>
                                                        <div class="author-name" itemprop="author">
                                                            <?php echo $rs['name']; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php $tc=''; } ?>

                                        </div>
                                        <!-- Controls -->
                                        <a class="left carousel-control" href="#carousel-custom3" role="button" data-slide="prev">
                                            <span class="slider-mover-left t-slider-l" aria-hidden="true">
                                                <i class="fa fa-angle-left"></i>
                                            </span>
                                            <span class="sr-only">Previous</span>
                                        </a>
                                        <a class="right carousel-control" href="#carousel-custom3" role="button" data-slide="next">
                                            <span class="slider-mover-right t-slider-r" aria-hidden="true">
                                                <i class="fa fa-angle-right"></i>
                                            </span>
                                            <span class="sr-only">Next</span>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Testimonial 3 end -->
                </div>

            </div>
        </div>
    </div>



