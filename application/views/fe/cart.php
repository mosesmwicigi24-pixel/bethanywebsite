    <div class="ps-page--simple">
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li>Shopping Cart</li>
                </ul>
            </div>
        </div>
        <div class="ps-section--shopping ps-shopping-cart">
            <div class="container">
                <div class="ps-section__header pb-20">
                    <h3>Shopping Cart</h3>
                </div>
                <div id="ajax-main-cart" style="min-height: 300px" class="ps-section__content">

                    <?php if ($this->flexi_cart->cart_status()): ?>
                        <div class="table-responsive">
                            <table class="table ps-table--shopping-cart">
                                <thead>
                                    <tr>
                                        <th>Product name</th>
                                        <th>PRICE</th>
                                        <th>QUANTITY</th>
                                        <th>SUB-TOTAL</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($cart_data as $row): ?>
                                        <tr>
                                            <td>
                                                <div class="ps-product--cart">
                                                    <div class="ps-product__thumbnail">
                                                        <a href="<?php echo base_url(); ?>product/<?php echo $row['product_reference_id']; ?>">
                                                            <?php if($row['product_image'] != '' && file_exists("./uploads/product_images/thumbs/" . $row['product_image'])): ?>
                                                                <img class="lazyload" data-src="<?php echo base_url();?>uploads/product_images/thumbs/<?php echo $row['product_image']; ?>" src="<?php echo product_placeholder; ?>">
                                                            <?php else: ?>
                                                                <img class="lazyload" data-src="<?php echo base_url();?>assets/fe/img/product-placeholder.jpg" src="<?php echo product_placeholder; ?>">
                                                            <?php endif; ?>
                                                        </a>
                                                    </div>
                                                    <div class="ps-product__content">
                                                        <a href="<?php echo base_url(); ?>product/<?php echo $row['product_reference_id']; ?>"><?php echo $row['name'];?><?php if ($row['product_variation_description'] != ''){ echo '<br>' . $row['product_variation_description']; } ?></a>
                                                        <p>SKU #:<strong> <?php echo $row['product_code']; ?></strong></p>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="price"><?php echo $row['price'];?></td>
                                            <td>
                                                <div class="form-group--number">
                                                    <button onclick="var result = $(this).parent().find('.qty-input'); var qty = result.val(); if( !isNaN( qty )) result.val(Number(qty)+1);return false;" class="up">+</button>
                                                    <button onclick="var result = $(this).parent().find('.qty-input'); var qty = result.val(); if( !isNaN( qty ) && Number(qty) > 1 ) result.val(Number(qty)-1);return false;" class="down">-</button>
                                                    <input class="form-control qty-input" type="number" min="1" placeholder="1" value="<?php echo $row['quantity'];?>">
                                                </div>
                                                <button class="btn btn-success cart_product_update" data-row-id="<?php echo $row['row_id'];?>" data-toggle="tooltip" data-placement="top" title="Update Cart"><i class="fa fa-refresh"></i></button>
                                            </td>
                                            <td><?php echo $row['price_total']; ?></td>
                                            <td><a data-toggle="tooltip" data-placement="top" title="Remove from Cart" class="cart_product_remove" data-row-id="<?php echo $row['row_id'];?>" href="javascript:void(0);"><i class="icon-cross"></i></a></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="ps-section__footer">
                            <div class="row">
                                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 col-12 "></div>
                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 ">
                                    <div class="ps-block--shopping-total">
                                        <div class="ps-block__header">
                                            <p>Subtotal <span> <?php echo $this->flexi_cart->sub_total();?></span></p>
                                            <p>Tax <span> <?php echo $this->flexi_cart->tax_total();?></span></p>
                                        </div>
                                        <div class="ps-block__content">
                                            <h3>Total <span><?php echo $this->flexi_cart->total();?></span></h3>
                                            <ul class="ps-block__product text-center">
                                                <li><span class="ps-block__shipping mt-10">Shipping fee not included yet</span></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="ps-section__cart-actions">
                            <a class="ps-btn ps-btn--rounded ps-btn--black" href="<?php echo base_url(); ?>shop"><i class="icon-arrow-left"></i> Back to Shop</a>
                            <a class="ps-btn ps-btn--rounded" href="<?php echo base_url(); ?>checkout">Proceed to Checkout <i class="icon-chevron-right-circle"></i></a>
                        </div>
                    <?php else: ?>
                        <div class="alert alert-info text-center"><i class="icon-notification-circle"></i> Your Shopping Cart is empty</div>
                        <div class="mt-30 text-center">
                            <a class="ps-btn ps-btn--rounded ps-btn--black" href="<?php echo base_url(); ?>shop"><i class="icon-arrow-left"></i> Back to Shop</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>