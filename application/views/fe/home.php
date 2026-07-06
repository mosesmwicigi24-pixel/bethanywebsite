    <div id="homepage-9">
        <div class="ps-home-banner">
            <div class="ps-carousel--nav-inside owl-slider" data-owl-auto="true" data-owl-loop="true" data-owl-speed="5000" data-owl-gap="0" data-owl-nav="true" data-owl-dots="true" data-owl-item="1" data-owl-item-xs="1" data-owl-item-sm="1" data-owl-item-md="1" data-owl-item-lg="1" data-owl-duration="1000" data-owl-mousedrag="on" data-owl-animate-in="fadeIn" data-owl-animate-out="fadeOut">
                <?php foreach ($home_sliders as $row): ?>
                    <?php if($row->home_slider_image_thumb != '' && file_exists("./uploads/home_sliders/thumbs/" . $row->home_slider_image_thumb)): ?>
                        <div class="ps-banner--organic" data-background="<?php echo base_url();?>uploads/home_sliders/thumbs/<?php echo $row->home_slider_image_thumb; ?>">
                            <img class="lazyload" data-src="<?php echo base_url();?>uploads/home_sliders/thumbs/<?php echo $row->home_slider_image_thumb; ?>" src="<?php echo slider_placeholder; ?>" alt="">
                            <div class="ps-banner__content">
                                <?php if ($row->home_slider_title != ''): ?>
                                    <h3><?php echo $row->home_slider_title; ?></h3>
                                <?php endif; ?>
                                <?php if ($row->home_slider_description != ''): ?>
                                    <h4><?php echo $row->home_slider_description; ?></h4>
                                <?php endif; ?>
                                <?php if ($row->home_slider_link != ''): ?>
                                    <a class="ps-btn" href="<?php echo $row->home_slider_link; ?>">Shop Now</a>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>
        </div>
        <div class="ps-site-features bg-light">
            <div class="container">
                <div class="ps-block--site-features ps-block--site-features-2">
                    <div class="ps-block__item">
                        <div class="ps-block__left"><i class="icon-truck"></i></div>
                        <div class="ps-block__right">
                            <h4>Free Delivery</h4>
                            <p>Within Nairobi &amp; for orders above KES 2,000/-</p>
                        </div>
                    </div>
                    <div class="ps-block__item">
                        <div class="ps-block__left"><i class="icon-rocket"></i></div>
                        <div class="ps-block__right">
                            <h4>International Shipping</h4>
                            <p>We ship across East Africa. We can organize shipping for other regions as well</p>
                        </div>
                    </div>
                    <div class="ps-block__item">
                        <div class="ps-block__left"><i class="icon-credit-card"></i></div>
                        <div class="ps-block__right">
                            <h4>Secure Payment</h4>
                            <p>100% secure payment</p>
                        </div>
                    </div>
                    <div class="ps-block__item">
                        <div class="ps-block__left"><i class="icon-bubbles"></i></div>
                        <div class="ps-block__right">
                            <h4>24/7 Support</h4>
                            <p>Dedicated support</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="ps-home-categories mt-30">
            <div class="container">
                <div class="ps-section__header">
                    <h3>Top Categories</h3>
                </div>
                <div class="ps-section__content">
                    <div class="row align-content-lg-stretch ">
                        <?php foreach ($home_top_product_categories as $row): ?>
                            <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12 ">
                                <div class="ps-block--category-2" data-mh="categories">
                                    <div class="ps-block__thumbnail img-fluid">
                                        <?php if($row->thumb_image != '' && file_exists("./uploads/product_category_cover_images/thumbs/" . $row->thumb_image)): ?>
                                            <img class="lazyload" data-src="<?php echo base_url();?>uploads/product_category_cover_images/thumbs/<?php echo $row->thumb_image; ?>" src="<?php echo product_placeholder; ?>" alt="">
                                        <?php endif; ?>
                                    </div>
                                    <div class="ps-block__content">
                                        <h5><a href="<?php echo base_url();?>category/<?php echo $row->product_category_reference_id; ?>"><?php echo $row->product_category_name; ?></a></h5>
                                        <ul>
                                            <?php if(!empty($row->sub)): ?>
                                                <?php foreach ($row->sub as $row2): ?>
                                                    <li><a href="<?php echo base_url();?>category/<?php echo $row2->product_category_reference_id; ?>"><?php echo $row2->product_category_name; ?></a></li>
                                                <?php endforeach; ?>
                                            <?php endif; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="ps-home-promotion mb-30">
            <div class="container">
                <div class="row">
                    <?php foreach ($home_promo_banners as $row): ?>
                        <?php if($row->home_promo_banner_image_thumb != '' && file_exists("./uploads/home_promo_banners/thumbs/" . $row->home_promo_banner_image_thumb)): ?>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                                <?php if ($row->home_promo_banner_link != ''): ?>
                                    <a class="ps-collection" href="<?php echo $row->home_promo_banner_link; ?>">
                                        <img class="lazyload" data-src="<?php echo base_url();?>uploads/home_promo_banners/<?php echo $row->home_promo_banner_image; ?>" src="<?php echo slider_placeholder; ?>" alt="">
                                    </a>
                                <?php else: ?>
                                    <img class="lazyload" data-src="<?php echo base_url();?>uploads/home_promo_banners/<?php echo $row->home_promo_banner_image; ?>" src="<?php echo slider_placeholder; ?>" alt="">
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
        <?php if ($num_home_featured_product_categories > 0): ?>
            <?php foreach ($home_featured_product_categories as $row): ?>
                <div class="ps-product-list ps-clothings mb-20">
                    <div class="container">
                        <div class="ps-section__header">
                            <h3><?php echo $row->product_category_name; ?></h3>
                            <ul class="ps-section__links">
                                <li><a href="<?php echo base_url();?>category/<?php echo $row->product_category_reference_id; ?>">View All <i class="icon-chevron-right"></i></a></li>
                            </ul>
                        </div>
                        <?php if(!empty($row->prods)): ?>
                            <div class="ps-section__content pt-0">
                                <div class="ps-carousel--nav owl-slider" data-owl-auto="false" data-owl-loop="false" data-owl-speed="10000" data-owl-gap="15" data-owl-nav="true" data-owl-dots="true" data-owl-item="5" data-owl-item-xs="2" data-owl-item-sm="2" data-owl-item-md="3" data-owl-item-lg="5" data-owl-item-xl="5" data-owl-duration="1000" data-owl-mousedrag="on">
                                    <?php foreach ($row->prods as $row2): ?>                                        
                                        <div class="ps-product">
                                            <div class="ps-product__thumbnail">
                                                <a href="<?php echo base_url(); ?>product/<?php echo $row2->product_reference_id; ?>">
                                                    <?php if($row2->product_image_thumb != '' && file_exists("./uploads/product_images/thumbs/" . $row2->product_image_thumb)): ?>
                                                        <img class="lazyload" data-src="<?php echo base_url();?>uploads/product_images/thumbs/<?php echo $row2->product_image_thumb; ?>" src="<?php echo product_placeholder; ?>" alt="">
                                                    <?php else: ?>
                                                        <img class="lazyload" data-src="<?php echo base_url();?>assets/fe/img/product-placeholder.jpg" src="<?php echo product_placeholder; ?>" alt="">
                                                    <?php endif; ?>
                                                </a>
                                                <?php 
                                                    if ($row2->sale_price > 0) {
                                                        $offer_rate = (($row2->regular_price - $row2->sale_price)/$row2->regular_price)*100;
                                                        echo '<div class="ps-product__badge">-' . ceil($offer_rate) . '%</div>';
                                                    }
                                                ?>
                                                <ul class="ps-product__actions">
                                                    <?php if ($row2->product_type == 'Simple'): ?>
                                                        <li>
                                                            <a href="javascript:void(0);" class="btn-product-addtocart" data-product-id="<?php echo $row2->product_id; ?>" data-toggle="tooltip" data-placement="top" title="Add to Cart"><i class="icon-cart-plus"></i></a>
                                                        </li>
                                                    <?php endif; ?>
                                                    <li>
                                                        <a href="javascript:void(0);" class="btn-product-quickview" data-product-id="<?php echo $row2->product_id; ?>" data-placement="top" title="Quick View" data-toggle="modal" data-target="#product-quickview"><i class="icon-eye"></i></a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0);" class="btn-product-favorite" data-product-id="<?php echo $row2->product_id; ?>" data-toggle="tooltip" data-placement="top" title="Add to Favorites"><i class="icon-heart"></i></a>
                                                    </li>
                                                    <li>
                                                        <a href="javascript:void(0);" class="btn-product-compare" data-product-id="<?php echo $row2->product_id; ?>" data-toggle="tooltip" data-placement="top" title="Compare"><i class="icon-chart-bars"></i></a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="ps-product__container">
                                                <!-- <a class="ps-product__vendor" href="#">Go Pro</a> -->
                                                <div class="ps-product__content">
                                                    <a class="ps-product__title" href="#"><?php echo $row2->product_name; ?></a>
                                                    <?php if ($row2->sale_price > 0): ?>
                                                        <p class="ps-product__price sale"><?php echo $default_currency; ?> <?php echo number_format($row2->sale_price,2); ?> <del><?php echo $default_currency; ?> <?php echo number_format($row2->regular_price,2); ?> </del></p>
                                                    <?php else: ?>
                                                        <p class="ps-product__price sale"><?php echo $default_currency; ?> <?php echo number_format($row2->regular_price,2); ?></p>
                                                    <?php endif; ?>
                                                    <?php if ($row2->product_type == 'Simple'): ?>
                                                        <a href="<?php echo base_url(); ?>product/<?php echo $row2->product_reference_id; ?>" class="ps-btn ps-btn--sm ps-btn--rounded ps-btn--fullwidth"><i class="icon-eye"></i> View Product</a>
                                                    <?php else: ?>
                                                        <a href="<?php echo base_url(); ?>product/<?php echo $row2->product_reference_id; ?>" class="ps-btn ps-btn--sm ps-btn--rounded ps-btn--fullwidth"><i class="icon-link"></i> Select Option</a>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="ps-section__content bg-light-yellow">
            <div class="ps-download-app">
                <div class="container">
                    <div class="ps-block--download-app bg-light-yellow">
                        <div class="row text-center">
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 ">
                                <div class="ps-block__thumbnail text-center">
                                    <img class="lazyload" data-src="<?php echo base_url();?>assets/fe/img/affiliate.png" src="<?php echo product_placeholder; ?>" alt="">
                                </div>
                            </div>
                            <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 col-12 mt-60">
                                <div class="ps-block__content p-30">
                                    <h3>Bethany House Affiliate Program</h3>
                                    <p>We have come up with a way to give you a chance to be a part of these exciting times and take part in facilitating churches to access communion wares, while you make extra income. All you’ll need to do is register for an affiliate account, generate and share the links and start earning.</p>
                                    <p class="download-link text-center">
                                        <a href="<?php echo base_url();?>affiliates" class="ps-btn ps-btn--rounded">Become Our Affiliate Partner</a>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php if ($num_home_testimonials > 0): ?>
            <div class="ps-client-say bg--cover" data-background="<?php echo base_url();?>assets/fe/img/bg/testimonial-bg.jpg">
                <div class="container">
                    <div class="ps-section__header">
                        <h3>What Our Clients Say</h3>
                    </div>
                    <div class="ps-section__content">
                        <div class="ps-carousel--testimonials owl-slider" data-owl-auto="true" data-owl-loop="true" data-owl-speed="10000" data-owl-gap="30" data-owl-nav="false" data-owl-dots="false" data-owl-item="2" data-owl-item-xs="1" data-owl-item-sm="1" data-owl-item-md="1" data-owl-item-lg="2" data-owl-duration="1000" data-owl-mousedrag="on">
                            <?php foreach ($home_testimonials as $row): ?>
                                <div class="ps-block--testimonial">
                                    <div class="ps-block__header">
                                        <?php if($row->testimonial_image_thumb != '' && file_exists("./uploads/testimonial_images/thumbs/" . $row->testimonial_image_thumb)): ?>
                                            <img class="lazyload" data-src="<?php echo base_url();?>uploads/testimonial_images/thumbs/<?php echo $row->testimonial_image_thumb; ?>" src="<?php echo product_placeholder; ?>" alt="">
                                        <?php else: ?>
                                            <img class="lazyload" data-src="<?php echo base_url();?>assets/fe/img/avi.png" src="<?php echo product_placeholder; ?>" alt="">
                                        <?php endif; ?>
                                    </div>
                                    <div class="ps-block__content"><i class="icon-quote-close"></i>
                                        <h4><?php echo $row->testimonial_name; ?><span><?php echo $row->testimonial_title; ?></span></h4>
                                        <p><?php echo $row->testimonial_description; ?></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>        

        <?php if ($num_home_blog_articles > 0): ?>
            <div class="ps-section--default ps-home-blog">
                <div class="container">
                    <div class="ps-section__header">
                        <h3>From Our Blog</h3>
                        <ul class="ps-section__links">
                            <li><a href="<?php echo base_url();?>blog">See All <i class="icon-chevron-right"></i></a></li>
                        </ul>
                    </div>
                    <div class="ps-section__content">
                        <div class="row">
                            <?php foreach ($home_blog_articles as $row): ?>
                                <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12 ">
                                    <div class="ps-post">
                                        <div class="ps-post__thumbnail">
                                            <a class="ps-post__overlay" href="<?php echo base_url(); ?>blog/<?php echo $row->blog_article_reference_id; ?>"></a>
                                            <?php if($row->thumb_image != '' && file_exists("./uploads/blog_article_cover_images/thumbs/" . $row->thumb_image)): ?>
                                                <img class="lazyload" data-src="<?php echo base_url();?>uploads/blog_article_cover_images/thumbs/<?php echo $row->thumb_image; ?>" src="<?php echo blog_placeholder; ?>" alt="">
                                            <?php else: ?>
                                                <img class="lazyload" data-src="<?php echo base_url();?>assets/fe/img/placeholder.png" src="<?php echo blog_placeholder; ?>" alt="">
                                            <?php endif; ?>
                                        </div>
                                        <div class="ps-post__content">
                                            <!-- <a class="ps-post__meta" href="#">Tips & Tricks</a> -->
                                            <a class="ps-post__title" href="<?php echo base_url(); ?>blog/<?php echo $row->blog_article_reference_id; ?>"><?php echo $row->blog_article_title; ?></a>
                                            <p><?php echo date('M d, Y', strtotime($row->blog_article_date)); ?> by<a href="#"> <?php echo $row->blog_article_author; ?></a></p>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
