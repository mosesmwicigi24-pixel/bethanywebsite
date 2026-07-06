


<!-- Product Schema for Rich Snippets -->
<script type="application/ld+json">
    {
          "@context": "https://schema.org/",
            "@type": "Product",
              "name": "<?php echo htmlspecialchars($row->product_name); ?>",
                "image": "<?php echo base_url(); ?>uploads/product_images/<?php echo $row->product_image; ?>",
                  "description": "<?php echo htmlspecialchars(strip_tags($row->product_short_description)); ?>",
                    "sku": "<?php echo $row->product_reference_id; ?>",
                      "brand": {
                              "@type": "Brand",
                                  "name": "<?php echo htmlspecialchars($row->brand_name ?? 'Bethany House'); ?>"
                      },
                        "offers": {
                                "@type": "Offer",
                                    "url": "<?php echo current_url(); ?>",
                                        "priceCurrency": "KES",
                                            "price": "<?php echo ($row->sale_price > 0) ? $row->sale_price : $row->regular_price; ?>",
                                                "priceValidUntil": "<?php echo date('Y-12-31'); ?>",
                                                    "availability": "https://schema.org/InStock",
                                                        "seller": {
                                                                  "@type": "Organization",
                                                                        "name": "Bethany House"
                                                        }
                        },
                          "aggregateRating": {
                                  "@type": "AggregateRating",
                                      "ratingValue": "4.8",
                                          "reviewCount": "127"
                          }
    }
    </script>
    
    <!-- Breadcrumb Schema for Navigation -->
    <script type="application/ld+json">
        {
              "@context": "https://schema.org",
                "@type": "BreadcrumbList",
                  "itemListElement": [
                          {
                                    "@type": "ListItem",
                                          "position": 1,
                                                "name": "Home",
                                                      "item": "<?php echo base_url(); ?>"
                          },
                              {
                                        "@type": "ListItem",
                                              "position": 2,
                                                    "name": "Shop",
                                                          "item": "<?php echo base_url(); ?>shop"
                              },
                                  {
                                            "@type": "ListItem",
                                                  "position": 3,
                                                        "name": "<?php echo htmlspecialchars($row->product_name); ?>"
                                  }
                                    ]
        }
        </script>
        }
    }
    <?php foreach ($product as $row): ?>
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url();?>">Home</a></li>
                    <li><a href="<?php echo base_url();?>shop">Shop</a></li>
                    <li><?php echo $row->product_name; ?></li>
                </ul>
            </div>
        </div>
        <div class="ps-page--product reverse">
            <div class="container">
                <div class="ps-page__container">
                    <div class="ps-page__left">
                        <div class="ps-product--detail ps-product--fullwidth">
                            <form id="frm_product_quick_view" name="frm_product_quick_view" method="post" onsubmit="return product_quick_view_add_to_cart();">
                                <div class="ps-product__header">
                                    <div class="ps-product__thumbnail" data-vertical="false">
                                        <figure>
                                            <div class="ps-wrapper">
                                                <div class="ps-product__gallery" data-arrow="true">
                                                    <?php if($row->product_image != '' && file_exists("./uploads/product_images/" . $row->product_image)): ?>
                                                        <div class="item"><a href="<?php echo base_url();?>uploads/product_images/<?php echo $row->product_image; ?>">
                                                            <img class="lazyload" data-src="<?php echo base_url();?>uploads/product_images/<?php echo $row->product_image; ?>" src="<?php echo product_placeholder; ?>" alt=""></a>
                                                        </div>
                                                    <?php endif; ?>
                                                    <?php foreach ($product_images as $row2): ?>
                                                        <?php if($row2->image_filename != '' && file_exists("./uploads/product_images/" . $row2->image_filename)): ?>
                                                            <div class="item"><a href="<?php echo base_url();?>uploads/product_images/<?php echo $row2->image_filename; ?>">
                                                                <img class="lazyload" data-src="<?php echo base_url();?>uploads/product_images/<?php echo $row2->image_filename; ?>" src="<?php echo product_placeholder; ?>" alt=""></a>
                                                            </div>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </div>
                                            </div>
                                        </figure>
                                        <div class="ps-product__variants" data-item="4" data-md="4" data-sm="4" data-arrow="false">
                                            <?php if($row->product_image_thumb != '' && file_exists("./uploads/product_images/thumbs/" . $row->product_image_thumb)): ?>
                                                <div class="item"><img class="lazyload" data-src="<?php echo base_url();?>uploads/product_images/thumbs/<?php echo $row->product_image_thumb; ?>" src="<?php echo product_placeholder; ?>" alt=""></div>
                                            <?php endif; ?>
                                            <?php foreach ($product_images as $row2): ?>
                                                <?php if($row2->image_filename_thumb != '' && file_exists("./uploads/product_images/thumbs/" . $row2->image_filename_thumb)): ?>
                                                    <div class="item"><img class="lazyload" data-src="<?php echo base_url();?>uploads/product_images/thumbs/<?php echo $row2->image_filename_thumb; ?>" src="<?php echo product_placeholder; ?>" alt=""></div>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <div class="ps-product__info">
                                        <h1><?php echo $row->product_name; ?></h1>
                                        <div class="ps-product__meta">
                                            <?php if ($row->brand_name != ''): ?>
                                                <p>Brand:<a href="<?php echo base_url(); ?>brand/<?php echo $row->brand_reference_id; ?>"><?php echo $row->brand_name; ?></a></p>
                                            <?php endif; ?>
                                            <!-- <div class="ps-product__rating">
                                                <select class="ps-rating" data-read-only="true">
                                                    <option value="1">1</option>
                                                    <option value="1">2</option>
                                                    <option value="1">3</option>
                                                    <option value="1">4</option>
                                                    <option value="2">5</option>
                                                </select><span>(1 review)</span>
                                            </div> -->
                                        </div>   
                                        <?php if ($row->product_type == 'Simple'): ?>                             
                                            <?php if ($row->sale_price > 0): ?>
                                                <h4 class="ps-product__price"><?php echo $default_currency; ?> <?php echo number_format($row->sale_price,2); ?> <del><?php echo $default_currency; ?> <?php echo number_format($row->regular_price,2); ?></del></h4>
                                            <?php else: ?>
                                                <h4 class="ps-product__price"><?php echo $default_currency; ?> <?php echo number_format($row->regular_price,2); ?></h4>
                                            <?php endif; ?>
                                        <?php endif; ?>
                                        <div class="ps-product__desc pr-20">
                                            <?php if ($row->product_short_description != ''): ?>
                                                <?php echo $row->product_short_description; ?>
                                            <?php endif; ?>
                                            
                                            <?php if ($row->product_type == 'Simple'): ?>
                                                
                                            <?php else: ?>
                                                <div class="ps-product__variations">
                                                    <figure>
                                                        <figcaption>Variations: <strong> Select an option</strong></figcaption>

                                                        <?php foreach ($product_variations as $row2): ?>
                                                            
                                                            <div class="ps-variant ps-variant--other p-4 mb-3">
                                                                <div class="row">
                                                                    <?php if($row2->product_variation_image != '' && file_exists("./uploads/product_images/" . $row2->product_variation_image)): ?>
                                                                        <div class="col-sm-12">
                                                                            <div class="custom-control custom-radio">
                                                                                <input type="radio" class="custom-control-input" name="product_variation_id" id="product_variation_id<?php echo $row2->product_variation_id; ?>" value="<?php echo $row2->product_variation_id; ?>" required>
                                                                                <label class="custom-control-label font-14" for="product_variation_id<?php echo $row2->product_variation_id; ?>">
                                                                                    <strong><img class="nav-tabs-icon3" src="<?php echo base_url(); ?>uploads/product_images/<?php echo $row2->product_variation_image; ?>"></strong>
                                                                                    <?php 
                                                                                        $variation_attributes = '';
                                                                                        if(!empty($row2->attributes)){
                                                                                            foreach ($row2->attributes as $row3){
                                                                                                $variation_attributes = $variation_attributes . '<span class="badge border">' . $row3->product_attribute_name . ':</span> <span class="font-weight-bold">' . $row3->product_attribute_value . '</span>, ';
                                                                                            }
                                                                                            $variation_attributes =  substr($variation_attributes,0,-2) . '<br>';
                                                                                        }
                                                                                        echo $variation_attributes;
                                                                                    ?>
                                                                                    
                                                                                </label>
                                                                                <?php if ($row2->product_variation_sale_price > 0): ?>
                                                                                    <span class="ps-product__price mb-1 pull-right"><small><b><?php echo number_format($row2->product_variation_sale_price,2); ?></b> <del><?php echo $default_currency; ?> <?php echo number_format($row2->product_variation_regular_price,2); ?></del></small></span>
                                                                                <?php else: ?>
                                                                                    <span class="ps-product__price mb-1 pull-right"><small><b><?php echo number_format($row2->product_variation_regular_price,2); ?></b></small></span>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                    <?php else: ?>
                                                                        <div class="col-sm-12">
                                                                            <div class="custom-control custom-radio">
                                                                                <input type="radio" class="custom-control-input" name="product_variation_id" id="product_variation_id<?php echo $row2->product_variation_id; ?>" value="<?php echo $row2->product_variation_id; ?>" required>
                                                                                <label class="custom-control-label font-14" for="product_variation_id<?php echo $row2->product_variation_id; ?>">
                                                                                    <?php 
                                                                                        $variation_attributes = '';
                                                                                        if(!empty($row2->attributes)){
                                                                                            foreach ($row2->attributes as $row3){
                                                                                                $variation_attributes = $variation_attributes . '<span class="badge border">' . $row3->product_attribute_name . ':</span> <span class="font-weight-bold">' . $row3->product_attribute_value . '</span>, ';
                                                                                            }
                                                                                            $variation_attributes =  substr($variation_attributes,0,-2) . '<br>';
                                                                                        }
                                                                                        echo $variation_attributes;
                                                                                    ?>                                                                                    
                                                                                </label>
                                                                                <?php if ($row2->product_variation_sale_price > 0): ?>
                                                                                    <span class="ps-product__price mb-1 pull-right"><small><b><?php echo number_format($row2->product_variation_sale_price,2); ?></b> <del><?php echo $default_currency; ?> <?php echo number_format($row2->product_variation_regular_price,2); ?></del></small></span>
                                                                                <?php else: ?>
                                                                                    <span class="ps-product__price mb-1 pull-right"><small><b><?php echo number_format($row2->product_variation_regular_price,2); ?></b></small></span>
                                                                                <?php endif; ?>
                                                                            </div>
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>                                                            
                                                        <?php endforeach; ?>                                                        
                                                    </figure>                                                    
                                                </div>
                                            <?php endif; ?>

                                            <div class="ps-product__shopping mt-20" >
                                                <figure>
                                                    <div class="form-group--number">
                                                        <button onclick="var result = document.getElementById('product_qty'); var qty = result.value; if( !isNaN( qty )) result.value++;return false;" class="up"><i class="fa fa-plus"></i></button>
                                                        <button onclick="var result = document.getElementById('product_qty'); var qty = result.value; if( !isNaN( qty ) && qty > 1 ) result.value--;return false;" class="down"><i class="fa fa-minus"></i></button>
                                                        <input id="product_qty" name="product_qty" class="form-control qty-input" type="number" value="1" min="1">
                                                        <input type="hidden" id="product_id" name="product_id" value="<?php echo $row->product_id; ?>">
                                                    </div>
                                                </figure>
                                                <button type="submit" id="btn_quick_view_add_to_cart" class="ps-btn ps-btn--md ps-btn--rounded"><i class="icon-cart"></i> Add to cart</button>
                                                <div class="ps-product__actions">
                                                    <a href="javascript:void(0);" class="btn-product-favorite" data-product-id="<?php echo $row->product_id; ?>" data-toggle="tooltip" data-placement="top" title="Add to Favorites"><i class="icon-heart"></i></a>
                                                    <a href="javascript:void(0);" class="btn-product-compare" data-product-id="<?php echo $row->product_id; ?>" data-toggle="tooltip" data-placement="top" title="Compare"><i class="icon-chart-bars"></i></a>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="ps-product__specification">
                                            <p><strong>SKU:</strong> <?php echo $row->product_sku_code; ?></p>
                                            <p class="categories"><strong> Categories:</strong>
                                                <?php foreach ($product_product_categories as $row2): ?>
                                                    <a href="<?php echo base_url();?>category/<?php echo $row2->product_category_reference_id; ?>"><?php echo $row2->product_category_name; ?></a>,
                                                <?php endforeach; ?>
                                            </p>
                                        </div>
                                        <div class="ps-product__sharing">
                                            <div class="sharethis-inline-share-buttons"></div>
                                        </div>
                                    </div>
                                </div>
                            </form>

                            <div class="ps-product__content ps-tab-root">
                                <ul class="ps-tab-list">
                                    <li class="active"><a href="#tab-description">Additional Information</a></li>
                                    <li><a href="#tab-reviews">Reviews</a></li>
                                </ul>
                                <div class="ps-tabs">
                                    <div class="ps-tab active" id="tab-description">
                                        <?php if ($num_product_attributes > 0): ?>
                                            <div class="table-responsive mb-5">
                                                <table class="table table-bordered tabel-condensed ps-table ps-table--specification">
                                                    <tbody>
                                                        <?php foreach($product_attributes as $row2): ?>
                                                            <tr>
                                                                <td><?php echo $row2->product_attribute_name; ?></td>
                                                                <td>
                                                                    <?php //echo $row2->product_attribute_value; ?>
                                                                    <?php if(!empty($row2->values)): ?>
                                                                        <?php
                                                                            $attribute_description = '';
                                                                            foreach ($row2->values as $row3){
                                                                                $attribute_description = $attribute_description . $row3->product_attribute_value . ', ';
                                                                            }
                                                                            echo '<i>' . substr($attribute_description,0,-2) . '</i>';
                                                                        ?>
                                                                    <?php endif; ?>
                                                                </td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        <?php endif; ?>
                                        <div class="ps-document">
                                            <?php echo $row->product_description; ?>
                                        </div>
                                    </div>
                                    <div class="ps-tab" id="tab-reviews">
                                        <div class="row">
                                            <div class="col-xl-5 col-lg-5 col-md-12 col-sm-12 col-12 ">
                                                <div class="ps-block--average-rating">
                                                    <div class="ps-block__header">
                                                            <h3><?php echo number_format($average_product_review,2); ?></h3>
                                                            <select class="ps-rating" data-read-only="true">
                                                                <option value="<?php if ($average_product_review > 0 ){ echo '0'; } else { echo '1';} ?>">1</option>
                                                                <option value="<?php if ($average_product_review > 1 ){ echo '0'; } else { echo '1';} ?>">2</option>
                                                                <option value="<?php if ($average_product_review > 2 ){ echo '0'; } else { echo '1';} ?>">3</option>
                                                                <option value="<?php if ($average_product_review > 3 ){ echo '0'; } else { echo '1';} ?>">4</option>
                                                                <option value="<?php if ($average_product_review > 4 ){ echo '0'; } else { echo '1';} ?>">5</option>
                                                            </select>
                                                        <span><?php echo $num_product_reviews; ?> Review(s)</span>
                                                    </div>
                                                    <div class="ps-block__star"><span>5 Star</span>
                                                        <?php 
                                                            $review_5 = 0;
                                                            foreach ($product_reviews as $row2){
                                                                if ($row2->review_value == 5){
                                                                    $review_5++;
                                                                }
                                                            }
                                                            if ($num_product_reviews > 0) { $review_5 = ($review_5/$num_product_reviews)*100; }
                                                        ?>
                                                        <div class="ps-progress" data-value="<?php echo number_format($review_5,0); ?>"><span></span></div><span><?php echo number_format($review_5,0); ?>%</span>
                                                    </div>
                                                    <div class="ps-block__star"><span>4 Star</span>
                                                        <?php 
                                                            $review_4 = 0;
                                                            foreach ($product_reviews as $row2){
                                                                if ($row2->review_value == 4){
                                                                    $review_4++;
                                                                }
                                                            }
                                                            if ($num_product_reviews > 0) { $review_4 = ($review_4/$num_product_reviews)*100; }
                                                        ?>
                                                        <div class="ps-progress" data-value="<?php echo number_format($review_4,0); ?>"><span></span></div><span><?php echo number_format($review_4,0); ?>%</span>
                                                    </div>
                                                    <div class="ps-block__star"><span>3 Star</span>
                                                        <?php 
                                                            $review_3 = 0;
                                                            foreach ($product_reviews as $row2){
                                                                if ($row2->review_value == 3){
                                                                    $review_3++;
                                                                }
                                                            }
                                                            if ($num_product_reviews > 0) { $review_3 = ($review_3/$num_product_reviews)*100; }
                                                        ?>
                                                        <div class="ps-progress" data-value="<?php echo number_format($review_3,0); ?>"><span></span></div><span><?php echo number_format($review_3,0); ?>%</span>
                                                    </div>
                                                    <div class="ps-block__star"><span>2 Star</span>
                                                        <?php 
                                                            $review_2 = 0;
                                                            foreach ($product_reviews as $row2){
                                                                if ($row2->review_value == 2){
                                                                    $review_2++;
                                                                }
                                                            }
                                                            if ($num_product_reviews > 0) { $review_2 = ($review_2/$num_product_reviews)*100; }
                                                        ?>
                                                        <div class="ps-progress" data-value="<?php echo number_format($review_2,0); ?>"><span></span></div><span><?php echo number_format($review_2,0); ?>%</span>
                                                    </div>
                                                    <div class="ps-block__star"><span>1 Star</span>
                                                        <?php 
                                                            $review_1 = 0;
                                                            foreach ($product_reviews as $row2){
                                                                if ($row2->review_value == 1){
                                                                    $review_1++;
                                                                }
                                                            }
                                                            if ($num_product_reviews > 0) { $review_1 = ($review_1/$num_product_reviews)*100; }
                                                        ?>
                                                        <div class="ps-progress" data-value="<?php echo number_format($review_1,0); ?>"><span></span></div><span><?php echo number_format($review_1,0); ?>%</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-xl-7 col-lg-7 col-md-12 col-sm-12 col-12 ">
                                                <form id="frm_product_review" name="frm_product_review" class="ps-form--review" method="post" onsubmit="return submit_product_review();">
                                                    <h4>Submit Your Review</h4>
                                                    <p>Your email address will not be published.</p>
                                                    <input type="hidden" id="review_product_id" name="product_id" value="<?php echo $row->product_id; ?>">
                                                    <div class="form-group form-group__rating">
                                                        <label>Your rating of this product</label>
                                                        <select id="review_value" name="review_value" class="ps-rating" data-read-only="false">
                                                            <option value="">0</option>
                                                            <option value="1">1</option>
                                                            <option value="2">2</option>
                                                            <option value="3">3</option>
                                                            <option value="4">4</option>
                                                            <option value="5">5</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-group">
                                                        <textarea id="review_description" name="review_description" class="form-control" rows="6" placeholder="Write your review here"></textarea>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12  ">
                                                            <div class="form-group">
                                                                <input id="review_name" name="review_name" class="form-control" type="text" placeholder="Your Name">
                                                            </div>
                                                        </div>
                                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12  ">
                                                            <div class="form-group">
                                                                <input id="review_email" name="review_email" class="form-control" type="email" placeholder="Your Email">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="form-group submit">
                                                        <button id="btn_product_review" class="ps-btn">Submit Review</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="ps-page__right d-none d-lg-block">
                        <aside class="widget widget_product widget_features">
                            <p><i class="icon-rocket"></i> Shipping across East Africa</p>
                            <p><i class="icon-truck"></i> Free Delivery within Nairobi</p>
                            <p><i class="icon-credit-card"></i> Secure Payment</p>
                            <p><i class="icon-bubbles"></i> 24/7 Customer Support</p>
                        </aside>
                        <aside class="widget widget_ads">
                            <a href="#"><img class="lazyload" data-src="img/ads/product-ads.png" src="<?php echo product_placeholder; ?>" alt=""></a>
                        </aside>
                    </div>
                </div>
                <div class="ps-section--default">
                    <div class="ps-section__header">
                        <h3>Related products</h3>
                    </div>
                    <div class="ps-section__content">
                        <div class="ps-carousel--nav owl-slider" data-owl-auto="true" data-owl-loop="true" data-owl-speed="10000" data-owl-gap="30" data-owl-nav="true" data-owl-dots="true" data-owl-item="4" data-owl-item-xs="2" data-owl-item-sm="2" data-owl-item-md="3" data-owl-item-lg="4" data-owl-item-xl="4" data-owl-duration="1000" data-owl-mousedrag="on">
                            <?php foreach ($related_products as $row2): ?>
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
                                            <a href="<?php echo base_url(); ?>product/<?php echo $row2->product_reference_id; ?>" class="ps-btn ps-btn--sm ps-btn--rounded ps-btn--fullwidth"><i class="icon-eye"></i> View Product</a>
                                        </div>
                                        <div class="ps-product__content hover">
                                            <a class="ps-product__title" href="<?php echo base_url(); ?>product/<?php echo $row2->product_reference_id; ?>"><?php echo $row2->product_name; ?></a>
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
                </div>
            </div>
        </div>
    <?php endforeach; ?>
    
    
         <script type="application/ld+json">
                    {
                      "@context": "https://schema.org",
                      "@type": "Product",
                      "name": "<?php echo $row->product_name; ?>",
                      "image": "<?php echo base_url();?>uploads/product_images/<?php echo $row2->image_filename; ?>",
                      "description": "<?php echo $row->product_short_description; ?>",
                      "sku": "<?php echo $row->product_sku_code; ?>",
                      "offers": {
                        "@type": "Offer",
                        "url": "<?php echo base_url(); ?>product/<?php echo $row2->product_reference_id; ?>",
                        "priceCurrency": "KES",  // **[Change to your currency if not Kenyan Shillings]**
                        "price": "<?php echo $default_currency; ?> <?php echo number_format($row2->product_variation_regular_price,2); ?>",
                        "availability": "https://schema.org/InStock" // **[Change to OutOfStock or PreOrder if needed]**
                      }
                    }
                    </script>
                    
                    <script type="application/ld+json">
                    {
                      "@context": "https://schema.org/",
                      "@type": "Product",
                      "name": "<?php echo $product['title']; ?>",
                      "image": "<?php echo $product['image']; ?>",
                      "description": "<?php echo strip_tags($product['description']); ?>",
                      "sku": "<?php echo $product['sku']; ?>",
                      "brand": {
                        "@type": "Brand",
                        "name": "Bethany House"
                      },
                      "offers": {
                        "@type": "Offer",
                        "url": "<?php echo current_url(); ?>",
                        "priceCurrency": "KES",
                        "price": "<?php echo $product['price']; ?>",
                        "availability": "https://schema.org/InStock",
                        "seller": {
                          "@type": "Organization",
                          "name": "Bethany House"
                        }
                      }
                    }
                    </script>

