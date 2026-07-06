    <div class="ps-page--my-account">
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li><a href="<?php echo base_url(); ?>affiliates">Affiliate Program</a></li>
                    <li>Register</li>
                </ul>
            </div>
        </div>
        <div class="ps-my-account mt-40">
            <div class="container">
                <div class="row">
                    <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12"></div>
                    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 mb-20">
                        <form class="ps-form--account ps-tab-root" id="frm_affiliate_register" name="frm_affiliate_register" method="post" autocomplete="off" onsubmit="return submit_affiliate_register();">
                            <h3 class="text-center">Create New Affiliate Account</h3>
                            <div class="ps-tabs">
                                <div class="active" id="sign-up">
                                    <div class="ps-form__content pt-40">
                                        
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6">
                                                    <input id="register_first_name" name="register_first_name" class="form-control" type="text" placeholder="First Name*">
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <input id="register_last_name" name="register_last_name" class="form-control" type="text" placeholder="Last Name*">
                                                </div>
                                            </div>                                            
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6">
                                                    <input id="register_email_address" name="register_email_address" class="form-control" type="email" placeholder="Email Address*">
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <input id="register_phone_number" name="register_phone_number" class="form-control" type="text" placeholder="Phone Number*">
                                                </div>
                                            </div>                                            
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-12 col-md-12">
                                                    <textarea class="form-control" id="register_physical_address" name="register_physical_address" rows="1" placeholder="Physical Address*"></textarea>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6">
                                                    <input id="register_town" name="register_town" class="form-control" type="text" placeholder="Town*">
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <select id="register_country_id" name="register_country_id" class="form-control" data-placeholder="Select Country" data-fouc>
                                                    <option value="">Select Country*</option>
                                                    <?php foreach ($countries as $row2): ?>
                                                        <option value="<?php echo $row2->country_id; ?>" ><?php echo $row2->country_name; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                                </div>
                                            </div>                                            
                                        </div>
                                        <div class="form-group">
                                            <div class="row">
                                                <div class="col-lg-6 col-md-6">
                                                    <input id="register_company_name" name="register_company_name" class="form-control" type="text" placeholder="Company Name">
                                                </div>
                                                <div class="col-lg-6 col-md-6">
                                                    <input id="register_website" name="register_website" class="form-control" type="text" placeholder="Website URL">
                                                </div>
                                            </div>                                            
                                        </div>
                                        <div class="form-group">
                                            <div class="ps-checkbox">
                                                <input class="form-control" type="checkbox" id="chk_terms" name="chk_terms">
                                                <label for="chk_terms">I have read and agreed to the <a href="<?php echo base_url();?>affiliates/terms" class="text-danger font-weight-bold" target="_blank">Terms and Conditions</a>*</label>
                                            </div>
                                        </div>
                                        <div class="form-group submtit">
                                            <button id="btn_affiliate_register" class="ps-btn ps-btn--fullwidth">Register</button>
                                        </div>
                                        <div class="form-group">
                                            <p class="text-center">Already have an Account? <a href="<?php echo base_url(); ?>affiliates/login"><span class="text-success font-weight-bold"><i class="icon-unlock"></i> Log In</span></a></p>
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