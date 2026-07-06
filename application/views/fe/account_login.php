    <div class="ps-page--my-account">
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li>Account Login</li>
                </ul>
            </div>
        </div>
        <div class="ps-my-account mt-40">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12"></div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-20">
                        <form class="ps-form--account ps-tab-root" id="frm_login" name="frm_login" method="post" autocomplete="off" onsubmit="return submit_login();">
                            <ul class="ps-tab-list">
                                <li class="active"><a href="#sign-in">Log into your Account</a></li>
                            </ul>
                            <div class="ps-tabs">
                                <div class="ps-tab active" id="sign-in">
                                    <div class="ps-form__content pt-40">
                                        <div class="form-group">
                                            <input id="login_email_address" name="login_email_address" class="form-control" type="email" placeholder="Email Address">
                                        </div>
                                        <div class="form-group">
                                            <input id="login_password" name="login_password" class="form-control show-pswd" type="password" placeholder="Password" autocomplete="new-password">
                                            <a class="pull-right mt-10" href="<?php echo base_url(); ?>account/reset_password"><i class="icon-question-circle"></i> Forgot Password?</a>
                                        </div>
                                        <div class="form-group">
                                            <div class="ps-checkbox">
                                                <input class="form-control" type="checkbox" id="remember-me" name="remember-me">
                                                <label for="remember-me">Remember Me</label>
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <button id="btn_login" class="ps-btn ps-btn--fullwidth"><i class="icon-unlock"></i> Login</button>
                                        </div>
                                        <div class="form-group">
                                            <p class="text-center">Don't have an Account? <a href="<?php echo base_url(); ?>account/register"><i class="icon-user-plus"></i> Create Account</a></p>
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