    <div class="ps-page--my-account">
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li>Account Register</li>
                </ul>
            </div>
        </div>
        <div class="ps-my-account mt-40">
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12"></div>
                    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-20">
                        <form class="ps-form--account ps-tab-root" id="frm_register" name="frm_register" method="post" autocomplete="off" onsubmit="return submit_register();">
                            <ul class="ps-tab-list">
                                <li class="active"><a href="#sign-up">Create your Bethany House Account<br><small>It's Free!</small></a></li>
                            </ul>
                            <div class="ps-tabs">
                                <div class="ps-tab active" id="sign-up">
                                    <div class="ps-form__content pt-40">
                                        <div class="form-group">
                                            <input id="register_first_name" name="register_first_name" class="form-control" type="text" placeholder="First Name">
                                        </div>
                                        <div class="form-group">
                                            <input id="register_last_name" name="register_last_name" class="form-control" type="text" placeholder="Last Name">
                                        </div>
                                        <div class="form-group">
                                            <input id="register_email_address" name="register_email_address" class="form-control" type="email" placeholder="Email Address">
                                        </div>
                                        <div class="form-group">
                                            <input id="register_phone_number" name="register_phone_number" class="form-control" type="text" placeholder="Phone Number">
                                        </div>
                                        <div class="form-group form-forgot">
                                            <input id="register_password" name="register_password" class="form-control show-pswd" type="password" placeholder="Password" autocomplete="new-password">
                                        </div>
                                        <div class="form-group submtit">
                                            <button id="btn_register" class="ps-btn ps-btn--fullwidth"><i class="icon-user-plus"></i> Create Account</button>
                                        </div>
                                        <div class="form-group">
                                            <p class="text-center">Already have an Account? <a href="<?php echo base_url(); ?>account/login"><i class="icon-unlock"></i> Log In</a></p>
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