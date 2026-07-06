                                                    <?php if ($num_products > 0): ?>
                                                        <?php foreach ($products as $row): ?>
                                                            <div class="col-md-2 col-xs-4" id="sale_product_<?php echo $row->product_id; ?>" disabled="disabled" data-toggle="tooltip" title="" style="padding-left: 5px; padding-right: 5px; display: block;" data-original-title="<?php echo $row->product_name; ?>">
                                                                <?php 
                                                                    if ($row->universal_sale_price == 1){
                                                                        if ($row->sale_price > 0){
                                                                            $product_price = $row->sale_price;
                                                                        } else {
                                                                            if ($row->universal_regular_price == 1){
                                                                                $product_price = $row->regular_price;
                                                                            } else {
                                                                                $product_price = $row->outlet_regular_price;
                                                                            }
                                                                        }
                                                                    } else {
                                                                        if ($row->outlet_sale_price > 0){
                                                                            $product_price = $row->outlet_sale_price;
                                                                        } else {
                                                                          if ($row->universal_regular_price == 1){
                                                                                $product_price = $row->regular_price;
                                                                            } else {
                                                                                $product_price = $row->outlet_regular_price;
                                                                            }
                                                                        }
                                                                    }
                                                                ?>
                                                                <div
                                                                    class="box box-default item_box bg-primary-dim sale_product_search_option"
                                                                    data-product-id="<?php echo $row->product_id; ?>"
                                                                    data-product-type="<?php echo $row->product_type; ?>"
                                                                    data-item-name="<?php echo $row->product_name; ?>"
                                                                    data-item-available-qty="<?php echo $row->available_stock; ?>"
                                                                    data-item-sales-price="<?php echo $product_price; ?>"
                                                                    data-context="<?php echo $context; ?>"
                                                                    style="min-height: 120px; cursor: pointer;"
                                                                >
                                                                    <span class="label label-danger push-right" style="font-weight: bold;font-family: sans-serif;" data-toggle="tooltip" title="Quantity in Stock: <?php echo $row->available_stock; ?>">Qty: <?php echo $row->available_stock; ?></span>
                                                                    <div class="box-body box-profile">
                                                                        <center>
                                                                            <?php if($row->product_image_thumb != '' && file_exists("./uploads/product_images/thumbs/" . $row->product_image_thumb)): ?>
                                                                                <img class="img-responsive item_image img-thumbnail rounded-circle" style="max-width: 70px;" src="<?php echo base_url();?>uploads/product_images/thumbs/<?php echo $row->product_image_thumb; ?>" alt="" />
                                                                            <?php else: ?>
                                                                                &mdash;
                                                                            <?php endif; ?>
                                                                        </center>
                                                                        <p class="text-center search_item text-center" id="item_0">
                                                                            
                                                                            <?php echo $row->product_name; ?><br />

                                                                            <span class="font-weight-bold"><?php echo $default_currency; ?> <?php echo number_format($product_price,2); ?></span>
                                                                            
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php else: ?>
                                                        <div class="col-md-12">
                                                            <div class="alert alert-danger alert-icon alert-dismissible">
                                                                <em class="icon ni ni-cross-circle"></em> <strong>Sorry!</strong> No Products Found
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>


                                                    