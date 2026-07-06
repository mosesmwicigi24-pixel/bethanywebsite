	<div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li><a href="<?php echo base_url(); ?>shop">Shop</a></li>
                <li><?php echo $product_category_name; ?></li>
            </ul>
        </div>
    </div>
    <div class="ps-page--shop" id="shop-sidebar">
        <div class="container">
            <div class="ps-layout--shop">
                <div class="ps-layout__left">
                	<form id="frm_filter_shop" name="frm_filter_shop" method="post" onsubmit="return filter_shop_products();">
                		<?php foreach ($product_category as $row): ?>
                			<input type="hidden" id="filter_shop_product_category_id" name="filter_shop_product_category_id" value="<?php echo $row->product_category_id; ?>">
                		<?php endforeach; ?>
	                    <aside class="widget widget_shop">
	                        <h4 class="widget-title font-weight-bold">Categories</h4>
	                        <ul class="ps-list--categories">
	                        	<?php foreach($nested_product_categories as $row): ?>
	                        		<li class="current-menu-item <?php if(!empty($row->sub)){ echo 'menu-item-has-children'; } ?>">
	                        			<a href="<?php echo base_url();?>category/<?php echo $row->product_category_reference_id; ?>"><b><?php echo $row->product_category_name; ?></b></a><?php if(!empty($row->sub)){ echo '<span class="sub-toggle"><i class="fa fa-angle-down"></i></span>';} ?>
	                        			<?php if(!empty($row->sub)): ?>
	                        				<ul class="sub-menu" style="display: block;">
	                        					<?php foreach($row->sub as $sub_category): ?>
	                        						<li class="current-menu-item <?php if(!empty($sub_category->sub)){ echo 'menu-item-has-children'; } ?>">
	                        							<a href="<?php echo base_url();?>category/<?php echo $sub_category->product_category_reference_id; ?>"><?php echo $sub_category->product_category_name; ?></a><?php if(!empty($sub_category->sub)){ echo '<span class="sub-toggle"><i class="fa fa-angle-down"></i></span>'; } ?>
	                        							<?php if(!empty($sub_category->sub)): ?>
	                        								<ul class="sub-menu" style="display: block;">
	                        									<?php foreach($sub_category->sub as $sub_sub_category): ?>
	                        										<li class="current-menu-item">
	                        											<a href="<?php echo base_url();?>category/<?php echo $sub_sub_category->product_category_reference_id; ?>"><?php echo $sub_sub_category->product_category_name; ?></a>
	                        										</li>
	                        									<?php endforeach; ?>
	                        								</ul>
	                        							<?php endif; ?>
	                        						</li>
	                        					<?php endforeach; ?>
	                        				</ul>
	                        			<?php endif; ?>
	                        		</li>
	                        	<?php endforeach; ?>
	                            </li>
	                        </ul>
	                    </aside>
	                    <aside class="widget widget_shop">
	                        <h4 class="widget-title">BRANDS</h4>
	                        <figure class="ps-custom-scrollbar" data-height="100">
	                        	<?php foreach ($brands as $row): ?>
		                            <div class="ps-checkbox">
		                                <input class="form-control" type="checkbox" id="filter_shop_brand_id_<?php echo $row->brand_id; ?>" name="filter_shop_brand_id[]" value="<?php echo $row->brand_id; ?>">
		                                <label for="filter_shop_brand_id_<?php echo $row->brand_id; ?>"><?php echo $row->brand_name; ?></label>
		                            </div>
		                        <?php endforeach; ?>
	                        </figure>

	                        <figure class="mt-20">
	                            <h4 class="widget-title">Price</h4>
	                            <div class="ps-slider" data-default-min="0" data-default-max="50000" data-max="50000" data-step="100" data-unit="KES"></div>
	                            <p class="ps-slider__meta">Price:<span class="ps-slider__value ps-slider__min"></span>-<span class="ps-slider__value ps-slider__max"></span></p>
	                            <input type="hidden" id="filter_price_range_min" name="filter_price_range_min" />
	                        	<input type="hidden" id="filter_price_range_max" name="filter_price_range_max" />
	                        </figure>

	                        <figure class="pt-10">
	                        	<button id="btn_filter_shop_products" class="ps-btn ps-btn--sm ps-btn--rounded ps-btn--fullwidth ps-btn--black"><i class="icon-funnel"></i> Filter Products</button>
	                        </figure>
		                </aside>

		            </form>
                </div>
                <div class="ps-layout__right">
                    <div class="ps-shopping ps-tab-root">
                        <div class="ps-shopping__header">
                            <p><strong id="shop_products_count"><?php echo $num_shop_products; ?></strong> Product(s) found</p>
                            <div class="ps-shopping__actions">
                                <select id="filter_shop_sort_by" name="filter_shop_sort_by" class="ps-select" data-placeholder="Sort Products">
                                	<option value="">Sort Products</option>
                                    <option value="Newest">Sort by Newest Arrivals</option>
                                    <option value="Price Ascending">Sort by Price: Low to High</option>
                                    <option value="Price Descending">Sort by Price: High to Low</option>
                                </select>
                                <div class="ps-shopping__view">
                                    <p>View</p>
                                    <ul class="ps-tab-list">
                                        <li class="active"><a href="#tab-1"><i class="icon-grid"></i></a></li>
                                        <li><a href="#tab-2"><i class="icon-list4"></i></a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="ps-tabs">
                            <div class="ps-tab active" id="tab-1">
                                <div class="ps-shopping-product">
                                    <div id="div_products" style="min-height: 300px" class="row">
                                    	<?php foreach ($shop_products as $row2): ?>
	                                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-6 mb-20">
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


                                    </div>
                                </div>
                                <!-- <div class="ps-pagination">
                                    <ul class="pagination">
                                        <li class="active"><a href="#">1</a></li>
                                        <li><a href="#">2</a></li>
                                        <li><a href="#">3</a></li>
                                        <li><a href="#">Next Page<i class="icon-chevron-right"></i></a></li>
                                    </ul>
                                </div> -->
                            </div>
                            <div class="ps-tab" id="tab-2">
                                <div class="ps-shopping-product">
                                	<?php foreach ($shop_products as $row2): ?>
	                                    <div class="ps-product ps-product--wide">
	                                        <div class="ps-product__thumbnail">
                                            	<a href="<?php echo base_url(); ?>product/<?php echo $row2->product_reference_id; ?>">
                                                    <?php if($row2->product_image_thumb != '' && file_exists("./uploads/product_images/thumbs/" . $row2->product_image_thumb)): ?>
                                                        <img class="lazyload" data-src="<?php echo base_url();?>uploads/product_images/thumbs/<?php echo $row2->product_image_thumb; ?>" src="<?php echo product_placeholder; ?>" alt="">
                                                    <?php else: ?>
                                                        <img class="lazyload" data-src="<?php echo base_url();?>assets/fe/img/product-placeholder.jpg" src="<?php echo product_placeholder; ?>" alt="">
                                                    <?php endif; ?>
                                                </a>
	                                        </div>
	                                        <div class="ps-product__container">
	                                            <div class="ps-product__content">
	                                            	<a class="ps-product__title" href="<?php echo base_url(); ?>product/<?php echo $row2->product_reference_id; ?>"><?php echo $row2->product_name; ?></a>
	                                            	<div>
	                                            		<?php echo $row2->product_short_description; ?>
	                                            	</div>
	                                            </div>
	                                            <div class="ps-product__shopping">
	                                                <?php if ($row2->sale_price > 0): ?>
                                                        <p class="ps-product__price sale"><?php echo $default_currency; ?> <?php echo number_format($row2->sale_price,2); ?> <del><?php echo $default_currency; ?> <?php echo number_format($row2->regular_price,2); ?> </del></p>
                                                    <?php else: ?>
                                                        <p class="ps-product__price sale"><?php echo $default_currency; ?> <?php echo number_format($row2->regular_price,2); ?></p>
                                                    <?php endif; ?>
	                                                <a class="ps-btn  ps-btn--sm ps-btn--rounded btn-product-addtocart" href="javascript:void(0);" data-product-id="<?php echo $row2->product_id; ?>">Add to cart</a>
	                                                <ul class="ps-product__actions">
	                                                    <li><a href="javascript:void(0);" class="btn-product-favorite" data-product-id="<?php echo $row2->product_id; ?>" data-toggle="tooltip" data-placement="top" title="Add to Favorites"><i class="icon-heart"></i> Favorite</a></li>
	                                                    <li><a href="javascript:void(0);" class="btn-product-compare" data-product-id="<?php echo $row2->product_id; ?>" data-toggle="tooltip" data-placement="top" title="Compare"><i class="icon-chart-bars"></i> Compare</a></li>
	                                                </ul>
	                                            </div>
	                                        </div>
	                                    </div>
	                                <?php endforeach; ?>
                                </div>
                                <!-- <div class="ps-pagination">
                                    <ul class="pagination">
                                        <li class="active"><a href="#">1</a></li>
                                        <li><a href="#">2</a></li>
                                        <li><a href="#">3</a></li>
                                        <li><a href="#">Next Page<i class="icon-chevron-right"></i></a></li>
                                    </ul>
                                </div> -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>