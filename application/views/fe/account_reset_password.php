    <div class="ps-page--my-account">
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li>Reset Password</li>
                </ul>
            </div>
        </div>
        <div class="ps-my-account mt-40">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12"></div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-20">
                        <form class="ps-form--account ps-tab-root" id="frm_reset_password" name="frm_reset_password" method="post" autocomplete="off" onsubmit="return submit_reset_password();">
                            <ul class="ps-tab-list">
                                <li class="active"><a href="javascript:;">Reset Account Password</a></li>
                                <li><span class="d-block text-muted">Enter your Email Address below to reset your password.</span></li>
                            </ul>
                            <div class="ps-tabs">
                                <div class="ps-tab active" id="sign-in">
                                    <div class="ps-form__content pt-40">
                                        <div class="form-group">
                                            <input id="reset_email_address" name="reset_email_address" class="form-control" type="email" placeholder="Email Address">
                                        </div>
                                        <div class="form-group">
                                            <button id="btn_reset_password" class="ps-btn ps-btn--fullwidth">Reset Password</button>
                                        </div>
                                        <div class="form-group">
                                            <p class="text-center"><a href="<?php echo base_url(); ?>account/login"><i class="icon-arrow-left"></i> Return to Login</a></p>
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