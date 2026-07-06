    <div class="ps-page--single">
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li><a href="<?php echo base_url(); ?>account">Account</a></li>
                    <li>Favorites</li>
                </ul>
            </div>
        </div>
        <div class="ps-vendor-store">
            <div class="container">
                <div class="ps-section__container">
                    <div class="ps-section__left">
                        <div class="ps-block--vendor">
                            <div class="ps-block__container">

                                <div class="item1-links  mb-0">
                                    <a href="<?php echo base_url();?>account" class="d-flex border-bottom"> <span class="icon1 mr-3"><i class="icon icon-user"></i></span> My Account </a>
                                    <a href="<?php echo base_url();?>account/orders" class="d-flex  border-bottom"> <span class="icon1 mr-3"><i class="icon icon-cart"></i></span> Orders </a>
                                    <a href="<?php echo base_url();?>account/favorites" class="active d-flex  border-bottom"> <span class="icon1 mr-3"><i class="icon icon-heart"></i></span> Favorites </a>
                                    <a href="<?php echo base_url();?>account/edit" class="d-flex border-bottom"> <span class="icon1 mr-3"><i class="icon icon-user"></i></span> Edit Account </a>
                                    <a href="<?php echo base_url();?>account/address" class="d-flex border-bottom"> <span class="icon1 mr-3"><i class="icon icon-map-marker"></i></span> Address Book </a>
                                    <a href="<?php echo base_url();?>account/logout" class="d-flex"> <span class="icon1 mr-3"><i class="icon icon-lock"></i></span> Logout </a>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="ps-section__right" style="min-height: 400px">

                        <div class="ps-block--container-hightlight mb-20">
                            <div class="ps-section__header">
                                <h5 class="text-white mb-0"><i class="icon icon-heart"></i> My Favorite Products</h5>
                            </div>
                            <div id="div_favorite_products" class="ps-section__content">
                                <?php if ($num_favorite_products > 0): ?>
                                    <div class="row">
                                        <?php foreach ($favorite_products as $row2): ?>
                                            <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-6 mb-20">
                                                <div class="ps-product">
                                                    <div class="ps-product__thumbnail">
                                                        <a href="javascript:void(0);" class="btn-remove-favorite-product" data-favorite-product-id="<?php echo $row2->favorite_product_id; ?>" title="Remove"><i class="icon-cross-circle fa-lg"></i></a>
                                                        <a href="<?php echo base_url(); ?>product/<?php echo $row2->product_reference_id; ?>">
                                                            <?php if($row2->product_image_thumb != '' && file_exists("./uploads/product_images/thumbs/" . $row2->product_image_thumb)): ?>
                                                                <img class="lazyload" data-src="<?php echo base_url();?>uploads/product_images/thumbs/<?php echo $row2->product_image_thumb; ?>" src="<?php echo product_placeholder; ?>">
                                                            <?php else: ?>
                                                                <img class="lazyload" data-src="<?php echo base_url();?>assets/fe/img/product-placeholder.jpg" src="<?php echo product_placeholder; ?>">
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
                                            </div>
                                            <div class="clearfix"></div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else: ?>
                                    <p><i>You have not added any favorite products yet</i></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>