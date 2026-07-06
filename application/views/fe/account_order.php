    <div class="ps-page--single">
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li><a href="<?php echo base_url(); ?>account">Account</a></li>
                    <li><a href="<?php echo base_url(); ?>account/orders">My Orders</a></li>
                    <li>Order Details</li>
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
                                    <a href="<?php echo base_url();?>account/orders" class="active d-flex  border-bottom"> <span class="icon1 mr-3"><i class="icon icon-cart"></i></span> Orders </a>
                                    <a href="<?php echo base_url();?>account/favorites" class="d-flex  border-bottom"> <span class="icon1 mr-3"><i class="icon icon-heart"></i></span> Favorites </a>
                                    <a href="<?php echo base_url();?>account/edit" class="d-flex border-bottom"> <span class="icon1 mr-3"><i class="icon icon-user"></i></span> Edit Account </a>
                                    <a href="<?php echo base_url();?>account/address" class="d-flex border-bottom"> <span class="icon1 mr-3"><i class="icon icon-map-marker"></i></span> Address Book </a>
                                    <a href="<?php echo base_url();?>account/logout" class="d-flex"> <span class="icon1 mr-3"><i class="icon icon-lock"></i></span> Logout </a>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="ps-section__right" style="min-height: 400px">
                        <?php foreach ($order as $row): ?>
                            <div class="card">
                                <div class="card-header bg-white">
                                    <h5 class="card-title mt-10 mb-10 font-weight-normal"><i class="icon icon-cart"></i> Order <b>#<?php echo $row->ord_order_number; ?></b> Details
                                        <a href="<?php echo base_url();?>account/orders" class="btn btn-sm btn-success mr-2 pull-right"><i class="fa fa-angle-left"></i> Go Back</a>
                                    </h5>
                                </div>
                                <div class="card-body p-20">
                                    <div class="row">                                
                                        <div class="col-lg-6 mb-30">
                                            <?php foreach ($store_information as $row2): ?>
                                                <?php if($row2->store_logo != '' && file_exists("./uploads/store_logo/" . $row2->store_logo)): ?>
                                                    <img class="lazyload" data-src="<?php echo base_url();?>uploads/store_logo/<?php echo $row2->store_logo; ?>" src="<?php echo logo_placeholder; ?>" class="ht-50" alt="">
                                                <?php else: ?>
                                                    <img class="lazyload" data-src="<?php echo base_url();?>assets/fe/img/logo.png" src="<?php echo logo_placeholder; ?>" class="ht-50" alt="">
                                                <?php endif; ?> 
                                                <p class="h3 mt-20"><?php echo $row2->store_name; ?></p>  
                                                <address>
                                                    <?php if ($row2->physical_address != ''): ?>
                                                        <i class="icon-map-marker"></i> <?php echo $row2->physical_address; ?><br />
                                                    <?php endif; ?>
                                                    <?php if ($row2->email_address != ''): ?>
                                                        <i class="icon-envelope"></i> <?php echo $row2->email_address; ?><br />
                                                    <?php endif; ?>
                                                    <?php if ($row2->phone_number != ''): ?>
                                                        <i class="icon-telephone"></i> <?php echo $row2->phone_number; ?><br />
                                                    <?php endif; ?>
                                                </address>
                                            <?php endforeach ?>
                                        </div>
                                        <div class="col-lg-6 mb-30 text-right">
                                            <p class="h3 mb-10">Order Details</p>
                                            <address>
                                                <span class="font-weight-bold">Order #:</span> <?php echo $row->ord_order_number; ?><br />
                                                <span class="font-weight-bold">Date:</span> <?php echo date('d M, Y H:i:s', strtotime($row->ord_date)); ?><br />
                                                <span class="font-weight-bold">Status:</span> 
                                                <?php if ($row->ord_order_status == 0): ?>
                                                    <span class="badge badge-pill badge-warning"><i class="icon-hourglass"></i> Awaiting Payment</span>
                                                <?php elseif ($row->ord_order_status == 1): ?>
                                                    <span class="badge badge-pill badge-info"><i class="icon-refresh2"></i> Processing</span>
                                                <?php elseif ($row->ord_order_status == 2): ?>
                                                    <span class="badge badge-pill badge-primary"><i class="icon-cart-remove"></i> Dispatched</span>
                                                <?php elseif ($row->ord_order_status == 3): ?>
                                                    <span class="badge badge-pill badge-success"><i class="icon-checkmark-circle"></i> Completed</span>
                                                <?php elseif ($row->ord_order_status == 4): ?>
                                                    <span class="badge badge-pill badge-danger"><i class="icon-cross-circle"></i> Cancelled</span>
                                                <?php endif; ?><br />
                                                <?php if ($row->ord_order_status == 0): ?>
                                                    <a href="<?php echo base_url();?>checkout/payment/<?php echo $row->ord_order_number; ?>" class="btn btn-xs btn-success btn-tooltip mt-10" title="Pay for my order"><i class="icon-credit-card"></i> Pay</a>&nbsp;&nbsp;
                                                    <a href="javascript:void(0);" onclick="cancel_order('<?php echo $row->ord_order_number ?>', 'Order');" class="btn btn-xs btn-danger btn-tooltip mt-10" title="Cancel Order"><i class="icon-cross-circle"></i> Cancel</a>  
                                                <?php elseif ($row->ord_order_status == 4): ?>
                                                    <a href="javascript:void(0);" onclick="restore_order('<?php echo $row->ord_order_number ?>', 'Order');" class="btn btn-xs btn-success btn-tooltip mt-10" title="Restore order"><i class="icon-refresh"></i> Restore</a>  
                                                <?php endif; ?>
                                            </address>
                                        </div>
                                    </div>

                                    <div class="row">                                
                                        <div class="col-lg-6 mb-30">
                                            <p class="h3 mb-10"><i class="icon-map-marker"></i> Address</p>
                                            <address>
                                                <b><?php echo $row->ord_shipping_first_name . ' ' . $row->ord_shipping_last_name; ?></b><br />
                                                <?php echo $row->ord_shipping_email_address . ', ' . $row->ord_shipping_phone_number; ?><br />
                                                <?php echo $row->ord_shipping_street_address . ', ' . $row->shipping_region_name . ', ' . $row->shipping_country_name; ?><br />
                                            </address>
                                        </div>
                                        <div class="col-lg-6 mb-30 text-right">
                                            <p class="h3 mb-10"><i class="icon-truck"></i> Delivery</p>
                                            <b>Shipping Method:</b> <?php echo $row->ord_shipping_method; ?><br />
                                            <?php if ($row->ord_shipping_method == 'Delivery'): ?>
                                                <b>Shipping Zone:</b> <?php echo $row->shipping_zone_name; ?><br />
                                                <?php if ($row->shipping_method == 0): ?>
                                                    <b>Shipping Fee:</b> Free<br />
                                                <?php else: ?>
                                                    <b>Shipping Fee:</b> <?php echo $default_currency; ?> <?php echo number_format($row->ord_shipping_total,2); ?><br />
                                                <?php endif; ?>
                                            <?php elseif ($row->ord_shipping_method == 'Pickup'): ?>
                                                <b>Pickup Location:</b>
                                                <?php echo $row->pickup_location_name . ', <br/>' . $row->pickup_location_address; ?><br />
                                                <?php if ($row->close_to != ''): ?>
                                                    <b>Close to:</b> <?php echo $row->close_to; ?><br />
                                                <?php endif; ?>
                                                <b>Opening Hours:</b> <?php echo $row->opening_hours; ?><br />
                                                <b>Pickup Period:</b> <?php echo $row->pickup_period; ?><br />
                                                <b>Shipping Fee:</b> <?php echo $default_currency; ?> <?php echo number_format($row->ord_shipping_total,2); ?><br />
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="box-border block_content table-responsive">
                                                <table class="table table-bordered table-condensed" style="font-size:90%">
                                                    <tbody>
                                                        <tr class="text-uppercase">
                                                            <th>Item</th>
                                                            <th>Unit Price</th>                                                    
                                                            <th class="text-center">Qty</th>
                                                            <th class="text-right">Total Price</th>
                                                        </tr> 
                                                        <?php foreach ($order_details as $row2): ?>
                                                            <tr>
                                                                <td><?php echo $row2->ord_det_item_name;?><?php if ($row2->ord_det_product_variation_description != ''){ echo '<br>' . $row2->ord_det_product_variation_description; }?><br><b>SKU Code:</b><?php echo $row2->ord_det_product_sku_code;?></td>
                                                                <td><?php echo number_format($row2->ord_det_price,2); ?></td>
                                                                <td class="text-center"><?php echo number_format($row2->ord_det_quantity,0); ?></td>
                                                                <td class="text-right"><?php echo number_format($row2->ord_det_price_total,2); ?></td>
                                                            </tr>   
                                                        <?php endforeach; ?>                                                               
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="3" class="text-right"><strong>No. of Items</strong></th>
                                                            <th class="text-right"><strong><?php echo number_format($row->ord_total_items,0);?></strong></th>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="3" class="text-right"><strong>Sub-Total</strong></th>
                                                            <th class="text-right"><strong><?php echo number_format($row->ord_item_summary_total,2);?></strong></th>
                                                        </tr>                            
                                                        <tr>
                                                            <th colspan="3" class="text-right"><strong>Total Tax</strong></th>
                                                            <th class="text-right"><strong><?php echo number_format($row->ord_tax_total,2);?></strong></th>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="3" class="text-right"><strong>Shipping</strong></th>
                                                            <th class="text-right"><strong><?php echo number_format($row->ord_shipping_total,2);?></strong></th>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="3" class="text-right"><strong>Discount</strong></th>
                                                            <th class="text-right"><strong><?php echo number_format($row->ord_savings_total,2);?></strong></th>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="3" class="text-right"><h5>Order Total</h5></th>
                                                            <th class="text-right"><h5><?php echo number_format($row->ord_total,2);?></h5></th>
                                                        </tr>
                                                    </tfoot>                                            
                                                </table>
                                            </div>                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>