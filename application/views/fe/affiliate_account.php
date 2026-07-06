
    <div class="ps-page--single">
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li><a href="<?php echo base_url(); ?>affiliates">Affiliate Program</a></li>
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
                                    <a href="<?php echo base_url();?>affiliates/account" class="active d-flex border-bottom"> <span class="icon1 mr-3"><i class="icon icon-user"></i></span> My Account </a>
                                    <a href="<?php echo base_url();?>affiliates/account/referrals" class="d-flex  border-bottom"> <span class="icon1 mr-3"><i class="icon icon-cart"></i></span> Referrals </a>
                                    <a href="<?php echo base_url();?>affiliates/account/clicks" class="d-flex  border-bottom"> <span class="icon1 mr-3"><i class="icon icon-mouse-left"></i></span> Clicks </a>
                                    <a href="#" class="d-flex  border-bottom"> <span class="icon1 mr-3"><i class="icon icon-credit-card"></i></span> Withdrawals </a>
                                    <a href="<?php echo base_url();?>affiliates/account/pswdchange" class="d-flex border-bottom"> <span class="icon1 mr-3"><i class="icon icon-user"></i></span> Change Password </a>
                                    <a href="<?php echo base_url();?>affiliates/logout" class="d-flex"> <span class="icon1 mr-3"><i class="icon icon-lock"></i></span> Logout </a>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="ps-section__right">
                        <?php foreach ($affiliate_account as $row): ?>
                            <div class="ps-section__header mb-20">
                                <h4>My Affiliate Account</h4>
                            </div>
                            <div class="ps-section__content">

                                <div class="row mb-20">
                                    <div class="col-md-4 mb-20">
                                        <div class="affiliate-stat alert alert-warning">
                                            <i class="fa fa-users"></i>
                                            <span><?php echo number_format($total_clicks,0); ?></span>
                                            Clicks
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-20">
                                        <div class="affiliate-stat alert alert-success">
                                            <i class="fa fa-shopping-cart"></i>
                                            <span><?php echo number_format($total_referrals,0); ?></span>
                                            Conversions
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <?php if ($total_clicks <= 0){ $conversion_rate = 0; } else { $conversion_rate = (($total_referrals/$total_clicks) * 100); } ?>
                                        <div class="affiliate-stat alert alert-info">
                                            <i class="fa fa-bar-chart-o"></i>
                                            <span><?php echo number_format($conversion_rate,0); ?>%</span>
                                            Rate
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-30">
                                    <div class="col-md-6 text-center">
                                        <h5 class="mb-20">Your Unique Referral Link:</h5>
                                        <div class="alert alert-light border-secondary rounded">
                                            <big><?php echo $row->short_url; ?></big>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-center">
                                        <h5 class="mb-20">Your Affiliate Code:</h5>
                                        <div class="alert alert-light border-secondary rounded">
                                            <big><?php echo $row->affiliate_code; ?></big>
                                        </div>
                                    </div>
                                </div>

                                <div class="row mb-30">
                                    <div class="col-md-6">
                                        <table class="table table-bordered table-rounded">
                                            <tbody>
                                                <tr>
                                                    <td class="text-right">Affiliate Package</td>
                                                    <td><strong><?php echo $row->affiliate_package_name; ?></strong></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">Commission</td>
                                                    <td><strong><?php echo number_format($row->commission); ?>%</strong></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">Minimum Withdrawal:</td>
                                                    <td><strong><?php echo $default_currency; ?> <?php echo number_format($row->minimum_pay_out,2); ?></strong></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-bordered table-rounded">
                                            <tbody>
                                                <tr>
                                                    <td class="text-right">Commissions Balance:</td>
                                                    <td><strong><?php echo $default_currency; ?> <?php echo number_format($row->commissions_balance,2); ?></strong></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">Total Commissions:</td>
                                                    <td><strong><?php echo $default_currency; ?> <?php echo number_format($row->total_commissions,2); ?></strong></td>
                                                </tr>
                                                <tr>
                                                    <td class="text-right">Total Amount Withdrawn:</td>
                                                    <td><strong><?php echo $default_currency; ?> <?php echo number_format($row->withdrawn_commissions,2); ?></strong></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="col-row mb-30">
                                    <div class="col-md-12 text-center">
                                        <div class="mb-20">
                                            <button class="btn btn-success btn-lg rounded" disabled>Request Withdrawal</button>
                                        </div>
                                        <p><small><strong class="badge badge-info">Note:</strong> You will only be able to request a withdrawal as soon as your balance reaches the minimum required amount of <?php echo $default_currency; ?> <?php echo number_format($row->minimum_pay_out,2); ?>.</small></p>
                                    </div>
                                </div>


                                <!-- <div class="row ">
                                    <div class="col-lg-12">
                                        <div class="card">
                                            <div class="card-header bg-white">
                                                <h5 class="card-title mt-10 mb-10 text-uppercase">My Latest Referrals
                                                    <a href="<?php echo base_url();?>affiliates/account/referrals" class="list-icons-item pull-right text-danger">View All</a>
                                                </h5>
                                            </div>
                                            <div id="div_recent_orders" style="min-height: 100px" class="card-body p-20">
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


                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> -->
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

