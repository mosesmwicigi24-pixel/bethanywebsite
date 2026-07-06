    <div class="ps-page--my-account">
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li>Checkout Login</li>
                </ul>
            </div>
        </div>
        <div class="ps-vendor-dashboard pro">
            <div class="container">
                <div class="ps-section__content">
                    <ul class="ps-section__links mt-30 mb-40">
                        <li class="active"><a href="javascript:void(0);"><i class="icon-user"></i> Login/Register</a></li>
                        <li><a href="javascript:void(0);"><i class="icon-map-marker-check"></i> Address &amp; Shipping Options</a></li>
                        <!-- <li><a href="javascript:void(0);"><i class="icon-truck"></i> Shipping Options</a></li> -->
                        <li><a href="javascript:void(0);"><i class="icon-credit-card"></i> Payment</a></li>
                        <li><a href="javascript:void(0);"><i class="icon-checkmark-circle"></i> Complete Order</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="ps-my-account">
            <div class="container">
                <div class="row">
                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-20">
                        <form class="ps-form--account ps-tab-root" id="frm_checkout_login" name="frm_checkout_login" method="post" autocomplete="off" onsubmit="return submit_checkout_login();">
                            <ul class="ps-tab-list">
                                <li class=""><a href="#sign-in">Already Registered?<br><small>Login below to proceed with Checkout</small></a></li>
                            </ul>
                            <div class="ps-tabs">
                                <div class="ps-tab active" id="sign-in">
                                    <div class="ps-form__content">
                                        <div class="form-group">
                                            <input id="login_email_address" name="login_email_address" class="form-control" type="email" placeholder="Email Address*">
                                        </div>
                                        <div class="form-group">
                                            <input id="login_password" name="login_password" class="form-control show-pswd" type="password" placeholder="Password*" autocomplete="new-password">
                                            <a class="pull-right mt-10" href=""><i class="icon-question-circle"></i> Forgot Password?</a>
                                        </div>
                                        <div class="form-group">
                                            <div class="ps-checkbox">
                                                <input class="form-control" type="checkbox" id="remember-me" name="remember-me">
                                                <label for="remember-me">Remember Me</label>
                                            </div>

                                        </div>
                                        <div class="form-group">
                                            <button id="btn_checkout_login" class="ps-btn ps-btn--fullwidth"><i class="icon-unlock"></i> Login</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-6 col-sm-12 mb-20">
                        <form class="ps-form--account ps-tab-root" id="frm_checkout_register" name="frm_checkout_register" method="post" autocomplete="off" onsubmit="return submit_checkout_register();">
                            <ul class="ps-tab-list">
                                <li class=""><a href="#sign-up">New Customer?<br><small>Create your Account below to proceed</small></a></li>
                            </ul>
                            <div class="ps-tabs">
                                <div class="ps-tab active" id="sign-up">
                                    <div class="ps-form__content">
                                        <div class="form-group">
                                            <input id="register_first_name" name="register_first_name" class="form-control" type="text" placeholder="First Name*">
                                        </div>
                                        <div class="form-group">
                                            <input id="register_last_name" name="register_last_name" class="form-control" type="text" placeholder="Last Name*">
                                        </div>
                                        <div class="form-group">
                                            <input id="register_email_address" name="register_email_address" class="form-control" type="email" placeholder="Email Address*">
                                        </div>
                                        <div class="form-group">
                                            <input id="register_phone_number" name="register_phone_number" class="form-control" type="text" placeholder="Phone Number*">
                                        </div>
                                        <div class="form-group form-forgot">
                                            <input id="register_password" name="register_password" class="form-control show-pswd" type="password" placeholder="Password*" autocomplete="new-password">
                                        </div>
                                        <div class="form-group submtit">
                                            <button id="btn_checkout_register" class="ps-btn ps-btn--fullwidth"><i class="icon-user-plus"></i> Create Account</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">
                        <div class="ps-form__total">
                            <h5 class="ps-form__heading">Your Order</h5>
                            <div class="content">
                                <div class="ps-block--checkout-total">
                                    <div class="ps-block__content">
                                        <table class="table ps-block__products">
                                            <tbody>
                                                <?php foreach($cart_data as $row): ?>
                                                    <tr>
                                                        <td><a href="<?php echo base_url(); ?>product/<?php echo $row['product_reference_id']; ?>"> <?php echo $row['name'];?><?php if ($row['product_variation_description'] != ''){ echo '<br>' . $row['product_variation_description']; } ?> ×<?php echo $row['quantity'];?></a>
                                                            <p>SKU Code:<strong><?php echo $row['product_code']; ?></strong></p>
                                                        </td>
                                                        <td class="text-right"><?php echo $row['price_total']; ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                        <h5 class="ps-block__title text-right"><small>Subtotal:</small> <span><?php echo $this->flexi_cart->sub_total();?></span></h5>
                                        <h5 class="ps-block__title text-right"><small>Tax:</small> <span><?php echo $this->flexi_cart->tax_total();?></span></h5>
                                        <h5 class="ps-block__title text-right"><small>Shipping:</small> <span><?php echo $this->flexi_cart->shipping_total();?></span></h5>
                                        <h5 class="ps-block__title text-right"><small>Discount:</small> <span><?php echo $this->flexi_cart->cart_savings_total();?></span></h5>
                                        <h4 class=" text-right"><small>Total:</small> <span><?php echo $this->flexi_cart->total();?></span></h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>




                </div>
                
            </div>
        </div>
    </div>