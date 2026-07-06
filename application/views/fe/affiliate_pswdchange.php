
    <div class="ps-page--single">
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li><a href="<?php echo base_url(); ?>affiliates">Affiliate Program</a></li>
                    <li>My Referrals</li>
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
                                    <a href="<?php echo base_url();?>affiliates/account/clicks" class="d-flex  border-bottom"> <span class="icon1 mr-3"><i class="icon icon-mouse-left"></i></span> Clicks </a>
                                    <a href="#" class="d-flex  border-bottom"> <span class="icon1 mr-3"><i class="icon icon-credit-card"></i></span> Withdrawals </a>
                                    <a href="<?php echo base_url();?>affiliates/account/pswdchange" class="active d-flex border-bottom"> <span class="icon1 mr-3"><i class="icon icon-user"></i></span> Change Password </a>
                                    <a href="<?php echo base_url();?>affiliates/logout" class="d-flex"> <span class="icon1 mr-3"><i class="icon icon-lock"></i></span> Logout </a>
                                </div>


                            </div>
                        </div>
                    </div>
                    <div class="ps-section__right">
                        <div class="ps-tabs">
                            <div class="ps-tab bg-white border-none active" id="change-password">
                                <form id="frm_affiliate_change_password" name="frm_affiliate_change_password" class="" method="post" onsubmit="return submit_affiliate_change_password();">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div id="div_change_password_error" class="alert alert-danger display-none font-13"></div>
                                            <div id="div_change_password_success" class="alert alert-success display-none font-13"></div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="form-label text-dark">Old Password <span class="text-danger">*</span></label>
                                                        <input id="old_password" name="old_password" type="password" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="form-label text-dark">New Password <span class="text-danger">*</span></label>
                                                        <input id="new_password" name="new_password" type="password" class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <label class="form-label text-dark">Confirm Password <span class="text-danger">*</span></label>
                                                        <input id="confirm_password" name="confirm_password" type="password" class="form-control">
                                                    </div>
                                                </div>
                                            </div>

                                            <hr>
                                            <div class="form-group text-right">
                                                <button id="btn_affiliate_change_password" class="btn btn-success font-weight-semibold"><i class="icon-check"></i> CHANGE PASSWORD</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

