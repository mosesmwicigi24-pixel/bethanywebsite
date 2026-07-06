										<?php foreach ($shop_products as $row2): ?>
	                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-6 mb-20">
	                                            <div class="ps-product">
	                                                <div class="ps-product__thumbnail">
	                                                	<a href="<?php echo base_url(); ?>product/<?php echo $row2->product_reference_id; ?>">
		                                                    <?php if($row2->product_image_thumb != '' && file_exists("./uploads/product_images/thumbs/" . $row2->product_image_thumb)): ?>
		                                                        <img src="<?php echo base_url();?>uploads/product_images/thumbs/<?php echo $row2->product_image_thumb; ?>" alt="">
		                                                    <?php else: ?>
		                                                        <img src="<?php echo base_url();?>assets/fe/img/product-placeholder.jpg" alt="">
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
	                                    <?php endforeach; ?>