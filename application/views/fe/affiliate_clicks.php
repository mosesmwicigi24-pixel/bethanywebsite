
    <div class="ps-page--single">
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li><a href="<?php echo base_url(); ?>affiliates">Affiliate Program</a></li>
                    <li>My Clicks</li>
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
                                    <a href="<?php echo base_url();?>affiliates/account" class="d-flex border-bottom"> <span class="icon1 mr-3"><i class="icon icon-user"></i></span> My Account </a>
                                    <a href="<?php echo base_url();?>affiliates/account/referrals" class="d-flex  border-bottom"> <span class="icon1 mr-3"><i class="icon icon-cart"></i></span> Referrals </a>
                                    <a href="<?php echo base_url();?>affiliates/account/clicks" class="active d-flex  border-bottom"> <span class="icon1 mr-3"><i class="icon icon-mouse-left"></i></span> Clicks </a>
                                    <a href="#" class="d-flex  border-bottom"> <span class="icon1 mr-3"><i class="icon icon-credit-card"></i></span> Withdrawals </a>
                                    <a href="<?php echo base_url();?>affiliates/account/pswdchange" class="d-flex border-bottom"> <span class="icon1 mr-3"><i class="icon icon-user"></i></span> Change Password </a>
                                    <a href="<?php echo base_url();?>affiliates/logout" class="d-flex"> <span class="icon1 mr-3"><i class="icon icon-lock"></i></span> Logout </a>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="ps-section__right">
                        <?php foreach ($affiliate_account as $row): ?>
                            <div class="ps-section__content">
                                <div class="row ">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header bg-white">
                                                <h5 class="card-title mt-10 mb-10 text-uppercase"><i class="icon icon-mouse-left"></i> My Clicks</h5>
                                            </div>
                                            <div id="div_recent_orders" style="min-height: 100px" class="card-body p-20">
                                                <div class="table-responsive">
                                                    <table class="table table-bordered table-striped table-condensed" id="my-orders-table">
                                                        <thead>
                                                            <tr class="first last">
                                                                <th class="font-weight-500">Date</th>
                                                                <th class="font-weight-500">Country</th>
                                                                <th class="font-weight-500">City</th>
                                                                <th class="font-weight-500">IP Address</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($affiliate_clicks as $row2): ?>
                                                                <tr class="first odd">
                                                                    <td><?php echo date('jS M Y H:i:s', strtotime($row2->created_on)); ?></td>
                                                                    <td><?php echo $row2->country; ?></td>
                                                                    <td><?php echo $row2->city; ?></td>
                                                                    <td><?php echo $row2->ip_address; ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
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

