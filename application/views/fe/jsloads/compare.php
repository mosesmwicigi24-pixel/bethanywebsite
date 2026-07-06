                    <?php if (isset($compare_products)): ?>
                        <div class="table-responsive">
                            <table class="table ps-table--compare">
                                <tbody>
                                    <tr>
                                        <td class="heading" rowspan="2" style="width: 200px;">Product</td>
                                        <?php foreach ($compare_products as $row): ?>
                                            <td><a href="javascript:void(0);" class="btn-remove-compare-product" data-product-id="<?php echo $row->product_id; ?>"><i class="icon-cross-circle"></i> Remove</a></td>
                                        <?php endforeach; ?>                                        
                                    </tr>
                                    <tr>
                                        <?php foreach ($compare_products as $row): ?>
                                            <td>
                                                <div class="ps-product--compare">
                                                    <div class="ps-product__thumbnail">
                                                        <a href="<?php echo base_url(); ?>product/<?php echo $row->product_reference_id; ?>">
                                                            <?php if($row->product_image_thumb != '' && file_exists("./uploads/product_images/thumbs/" . $row->product_image_thumb)): ?>
                                                                <img src="<?php echo base_url();?>uploads/product_images/thumbs/<?php echo $row->product_image_thumb; ?>" alt="">
                                                            <?php else: ?>
                                                                <img src="<?php echo base_url();?>assets/fe/img/product-placeholder.jpg" alt="">
                                                            <?php endif; ?>
                                                        </a>
                                                    </div>
                                                    <div class="ps-product__content">
                                                        <a href="<?php echo base_url(); ?>product/<?php echo $row->product_reference_id; ?>"><?php echo $row->product_name; ?></a>
                                                    </div>
                                                </div>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td class="heading">Price</td>
                                        <?php foreach ($compare_products as $row): ?>
                                            <?php 
                                                if ($row->sale_price > 0) {
                                                    $offer_rate = (($row->regular_price - $row->sale_price)/$row->regular_price)*100;
                                                }
                                            ?>
                                            <td>
                                                <?php if ($row->sale_price > 0): ?>
                                                    <h4 class="price sale"><?php echo $default_currency; ?> <?php echo number_format($row->sale_price,2); ?> <small><del><?php echo $default_currency; ?> <?php echo number_format($row->regular_price,2); ?></del> (-<?php echo ceil($offer_rate); ?>%)</small></h4>
                                                <?php else: ?>
                                                    <h4 class="price sale"><?php echo $default_currency; ?> <?php echo number_format($row->regular_price,2); ?></h4>
                                                <?php endif; ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td class="heading">SKU</td>
                                        <?php foreach ($compare_products as $row): ?>
                                            <td><span><?php echo $row->product_sku_code; ?></span></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td class="heading">Short Description</td>
                                        <?php foreach ($compare_products as $row): ?>
                                            <td><span><?php if ($row->product_short_description != '') { echo $row->product_short_description; } else { echo '--'; } ?></span></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td class="heading">Description</td>
                                        <?php foreach ($compare_products as $row): ?>
                                            <td><span><?php if ($row->product_description != '') { echo $row->product_description; } else { echo '--'; } ?></span></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td class="heading">Brand</td>
                                        <?php foreach ($compare_products as $row): ?>
                                            <td><span><?php if ($row->brand_name != '') { echo $row->brand_name; } else { echo '--'; } ?></span></td>
                                        <?php endforeach; ?>
                                    </tr>
                                    <tr>
                                        <td class="heading"></td>
                                        <?php foreach ($compare_products as $row): ?>
                                            <td><a class="ps-btn ps-btn--sm ps-btn--rounded btn-product-addtocart" href="javascript:void(0);" data-product-id="<?php echo $row->product_id; ?>">Add To Cart</a></td>
                                        <?php endforeach; ?>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center"><i class="icon-notification-circle"></i> You have no items in your comparison list.</div>
                        <div class="mt-30 text-center">
                            <a class="ps-btn ps-btn--rounded ps-btn--black" href="<?php echo base_url(); ?>shop"><i class="icon-arrow-left"></i> Back to Shop</a>
                        </div>
                    <?php endif; ?>