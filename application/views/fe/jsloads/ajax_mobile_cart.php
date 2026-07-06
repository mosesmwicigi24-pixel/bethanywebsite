                <?php if ($this->flexi_cart->cart_status()): ?>
                    <div class="ps-cart--mobile">
                        <div class="ps-cart__content">
                            <?php foreach($cart_data as $row): ?>
                                <div class="ps-product--cart-mobile">
                                    <div class="ps-product__thumbnail">
                                        <a href="<?php echo base_url(); ?>product/<?php echo $row['product_reference_id']; ?>">
                                            <?php if($row['product_image'] != '' && file_exists("./uploads/product_images/thumbs/" . $row['product_image'])): ?>
                                                <img src="<?php echo base_url();?>uploads/product_images/thumbs/<?php echo $row['product_image']; ?>" alt="">
                                            <?php else: ?>
                                                <img src="<?php echo base_url();?>assets/fe/img/product-placeholder.jpg" alt="">
                                            <?php endif; ?>
                                        </a>
                                    </div>
                                    <div class="ps-product__content">
                                        <a class="ps-product__remove" href="javascript:void(0);" data-row-id="<?php echo $row['row_id'];?>"><i class="icon-cross"></i></a>
                                        <a href="<?php echo base_url(); ?>product/<?php echo $row['product_reference_id']; ?>"><?php echo $row['name'];?><?php if ($row['product_variation_description'] != ''){ echo '<br>' . $row['product_variation_description']; } ?></a>
                                        <p><small><?php echo $row['quantity'];?> x <?php echo $row['price'];?></small></p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="ps-cart__footer">
                            <h3>Sub Total:<strong><?php echo $this->flexi_cart->total();?></strong></h3>
                            <figure>
                                <a class="ps-btn ps-btn--sm ps-btn--rounded " href="<?php echo base_url(); ?>cart"><i class="icon-cart"></i> View Cart</a>
                                <a class="ps-btn ps-btn--sm ps-btn--rounded ps-btn--black" href="<?php echo base_url(); ?>checkout">Checkout <i class="icon-chevron-right"></i></a>
                            </figure>
                        </div>
                    </div>
                <?php endif; ?>