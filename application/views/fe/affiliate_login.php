    <div class="ps-page--my-account">
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li><a href="<?php echo base_url(); ?>affiliates">Affiliate Program</a></li>
                    <li>Login</li>
                </ul>
            </div>
        </div>
        <div class="ps-my-account mt-40">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg- col-md-12 col-sm-12"></div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-20">
                        <form class="ps-form--account ps-tab-root" id="frm_affiliate_login" name="frm_affiliate_login" method="post" autocomplete="off" onsubmit="return submit_affiliate_login();">
                            <h3 class="text-center">Affiliate Login</h3>
                            <div class="ps-tabs">
                                <div class="active" id="sign-up">
                                    <div class="ps-form__content pt-40">
                                        
                                        <div class="form-group mb-20">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12">
                                                    <input id="login_email_address" name="login_email_address" class="form-control" type="email" placeholder="Email Address*">
                                                </div>
                                            </div>                                            
                                        </div>

                                        <div class="form-group mb-20">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12">
                                                    <input id="login_password" name="login_password" class="form-control" type="password" placeholder="Password*" autocomplete="new-password">
                                                </div>
                                            </div>                                            
                                        </div>

                                        <div class="form-group mb-20">
                                            <div class="ps-checkbox">
                                                <input class="form-control" type="checkbox" id="chk_terms" name="chk_terms">
                                                <label for="chk_terms">I have read and agreed to the <a href="<?php echo base_url();?>affiliates/terms" class="text-danger font-weight-bold" target="_blank">Terms and Conditions</a>*</label>
                                            </div>
                                        </div>
                                        <div class="form-group submtit">
                                            <button id="btn_affiliate_login" class="ps-btn ps-btn--fullwidth"><i class="icon-unlock"></i> Login</button>
                                        </div>
                                        <div class="form-group">
                                            <p class="text-center">Don't have an Affiliate Account? <a href="<?php echo base_url(); ?>affiliates/register"><span class="text-success font-weight-bold"><i class="icon-user"></i> Create Account</span></a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                
            </div>
        </div>
    </div>