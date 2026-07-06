    <div class="ps-page--single">
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li>My Account</li>
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
                                    <a href="<?php echo base_url();?>account" class="active d-flex border-bottom"> <span class="icon1 mr-3"><i class="icon icon-user"></i></span> My Account </a>
                                    <a href="<?php echo base_url();?>account/orders" class="d-flex  border-bottom"> <span class="icon1 mr-3"><i class="icon icon-cart"></i></span> Orders </a>
                                    <a href="<?php echo base_url();?>account/favorites" class="d-flex  border-bottom"> <span class="icon1 mr-3"><i class="icon icon-heart"></i></span> Favorites </a>
                                    <a href="<?php echo base_url();?>account/edit" class="d-flex border-bottom"> <span class="icon1 mr-3"><i class="icon icon-user"></i></span> Edit Account </a>
                                    <a href="<?php echo base_url();?>account/address" class="d-flex border-bottom"> <span class="icon1 mr-3"><i class="icon icon-map-marker"></i></span> Address Book </a>
                                    <a href="<?php echo base_url();?>account/logout" class="d-flex"> <span class="icon1 mr-3"><i class="icon icon-lock"></i></span> Logout </a>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="ps-section__right">

                        <div class="card">
                            <div class="card-header bg-white">
                                <h4 class="card-title mt-10 mb-10 font-weight-normal"><i class="icon icon-user"></i> Account Overview</h4>
                            </div>
                            <div class="card-body p-30">

                                <?php foreach ($account as $row): ?>

                                    <p>Hello, <b><?php echo $row->first_name; ?></b>,</p>
                                    <p>From your account you have the ability to view a snapshot of your recent account activity and update your account information. Select an action below to view or edit information.</p>

                                    
                                    <div class="row mt-20">
                                        <div class="col-lg-6 mb-20">
                                            <div class="card">
                                                <div class="card-header bg-white">
                                                    <h5 class="card-title mt-10 mb-10 font-weight-normal text-uppercase">Account Details
                                                        <a href="<?php echo base_url();?>account/edit" class="list-icons-item pull-right text-danger"><i class="fa fa-pencil fa-lg"></i></a>
                                                    </h5>
                                                </div>
                                                <div class="card-body p-20">
                                                    <h5 class="font-weight-500 text-uppercase"><?php echo $row->first_name . ' ' . $row->last_name; ?></h5>
                                                    <p>
                                                        <?php echo $row->email_address; ?>
                                                        <br><?php echo $row->phone_number; ?>
                                                    </p>
                                                </div>
                                            </div>

                                        </div>

                                        <div class="col-lg-6 mb-20">
                                            <div class="card">
                                                <div class="card-header bg-white">
                                                    <h5 class="card-title mt-10 mb-10 font-weight-normal text-uppercase">Address Book
                                                        <a href="<?php echo base_url();?>account/address" class="list-icons-item pull-right text-danger"><i class="fa fa-pencil fa-lg"></i></a>
                                                    </h5>
                                                </div>
                                                <div class="card-body p-20">
                                                    <?php if ($row->shipping_first_name != '' && $row->shipping_last_name != ''): ?>
                                                        <h5 class="font-weight-500">Your default shipping Address is:</h5>
                                                        <p>
                                                            <span class="text-uppercase"><?php echo $row->shipping_first_name . ' ' . $row->shipping_last_name; ?></span>
                                                            <br><i class="icon-map-marker"></i> <?php echo $row->shipping_street_address . ', ' . $row->shipping_region_name . ', ' . $row->shipping_country_name; ?>
                                                            <br><i class="icon-envelope"></i> <?php echo $row->shipping_email_address; ?>
                                                            <br><i class="icon-telephone"></i> <?php echo $row->shipping_phone_number; ?>
                                                        </p>
                                                    <?php else: ?>
                                                        <p><i><i class="icon-notification-circle"></i> You have not added your shipping address yet.</i></p>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row ">
                                        <div class="col-lg-12">
                                            <div class="card">
                                                <div class="card-header bg-white">
                                                    <h5 class="card-title mt-10 mb-10 font-weight-normal text-uppercase">Recent Orders
                                                        <a href="<?php echo base_url();?>account/orders" class="list-icons-item pull-right text-danger">View All</a>
                                                    </h5>
                                                </div>
                                                <div id="div_recent_orders" style="min-height: 100px" class="card-body p-20">
                                                    <?php if ($num_recent_orders > 0): ?>
                                                        <div class="table-responsive">
                                                            <table class="table table-bordered table-striped table-condensed" id="my-orders-table">
                                                                <thead>
                                                                    <tr class="first last">
                                                                        <th class="font-weight-500">Order #</th>
                                                                        <th class="font-weight-500">Order Date</th>
                                                                        <th class="font-weight-500">Shipping Method</th>
                                                                        <th class="font-weight-500">Order Total</th>
                                                                        <th class="font-weight-500 text-center">Status</th>
                                                                        <th class="font-weight-500 text-center">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php foreach ($recent_orders as $row2): ?>
                                                                        <tr class="first odd">
                                                                            <td  <?php if($row2->ord_status == 5):?> style="color: #999"<?php endif; ?>>
                                                                                <a href="<?php echo base_url(); ?>account/order/<?php echo $row2->ord_order_number; ?>"><strong><?php echo $row2->ord_order_number; ?></strong></a>
                                                                            </td>
                                                                            <td  <?php if($row2->ord_status == 5):?> style="color: #999"<?php endif; ?>><?php echo date('jS M Y', strtotime($row2->ord_date)); ?></td>
                                                                            <td  <?php if($row2->ord_status == 5):?> style="color: #999"<?php endif; ?>><?php echo $row2->ord_shipping_method; ?></td>
                                                                            <td  <?php if($row2->ord_status == 5):?> style="color: #999"<?php endif; ?>><span class="price"><?php echo $row2->ord_currency; ?> <?php echo number_format($row2->ord_total,2); ?></span></td>
                                                                            <td class="text-center">
                                                                                <?php if ($row2->ord_order_status == 0): ?>
                                                                                    <span class="badge badge-pill badge-warning">Awaiting Payment</span>
                                                                                <?php elseif ($row2->ord_order_status == 1): ?>
                                                                                    <span class="badge badge-pill badge-info">Processing</span>
                                                                                <?php elseif ($row2->ord_order_status == 2): ?>
                                                                                    <span class="badge badge-pill badge-primary">Dispatched</span>
                                                                                <?php elseif ($row2->ord_order_status == 3): ?>
                                                                                    <span class="badge badge-pill badge-success">Completed</span>
                                                                                <?php elseif ($row2->ord_order_status == 4): ?>
                                                                                    <span class="badge badge-pill badge-danger">Cancelled</span>
                                                                                <?php endif; ?>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <?php if ($row2->ord_order_status == 0): ?>
                                                                                    <a href="<?php echo base_url();?>checkout/payment/<?php echo $row2->ord_order_number; ?>" class="btn btn-xs btn-success btn-tooltip" title="Pay for my order"><i class="icon-credit-card"></i> Pay</a>
                                                                                    <a href="javascript:void(0);" onclick="cancel_order('<?php echo $row2->ord_order_number ?>', 'Account');" class="btn btn-xs btn-danger btn-tooltip" title="Cancel Order"><i class="icon-cross-circle"></i> Cancel</a>  
                                                                                <?php elseif ($row2->ord_order_status == 4): ?>
                                                                                    <a href="javascript:void(0);" onclick="restore_order('<?php echo $row2->ord_order_number ?>', 'Account');" class="btn btn-xs btn-success btn-tooltip" title="Restore order"><i class="icon-refresh"></i> Restore</a>  
                                                                                <?php endif; ?>
                                                                            </td>
                                                                        </tr>
                                                                    <?php endforeach; ?>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    <?php else: ?>
                                                        <p><i>You have not made any orders yet</i></p>
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
            </div>
        </div>
    </div>