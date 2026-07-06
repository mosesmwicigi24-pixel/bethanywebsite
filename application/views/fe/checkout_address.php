    <div class="ps-page--simple">
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li>Checkout Address</li>
                </ul>
            </div>
        </div>
        <div class="ps-vendor-dashboard pro">
            <div class="container">
                <div class="ps-section__content">
                    <ul class="ps-section__links mt-30 mb-40">
                        <?php if ($this->session->userdata('customer_id')): ?>
                            <li><a href="javascript:void(0);"><i class="icon-user"></i> Login/Register</a></li>
                        <?php endif; ?>
                        <li class="active"><a href="javascript:void(0);"><i class="icon-map-marker-check"></i> Address &amp; Shipping Options</a></li>
                        <li><a href="javascript:void(0);"><i class="icon-credit-card"></i> Payment</a></li>
                        <li><a href="javascript:void(0);"><i class="icon-checkmark-circle"></i> Complete Order</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="ps-checkout ps-section--shopping pt-0">
            <div class="container">
                <div class="ps-section__content">
                    <form id="frm_checkout_address" name="frm_checkout_address" class="ps-form--checkout" method="post" onsubmit="return submit_checkout_address();">
                        <div class="row">
                            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12  ">
                                <div class="row">                                    
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 mb-20">
                                        <?php if (isset($account)): ?>
                                            <?php foreach ($account as $row): ?>
                                                <div class="ps-form__billing-info">
                                                    <h5 class="ps-form__heading mb-20">Shipping Address</h5>
                                                    <?php if ($row->shipping_first_name == '' || $row->shipping_last_name == ''): ?>
                                                        <p><i>Please add your Shipping Address to proceed</i></p>
                                                        <input type="hidden" id="sae" name="sae" value="1">
                                                    <?php else: ?>
                                                        <address class="mb-20">
                                                            <b><?php echo $row->shipping_first_name . ' ' . $row->shipping_last_name; ?></b><br />
                                                            <?php echo $row->shipping_email_address . ', ' . $row->shipping_phone_number; ?><br />
                                                            <?php echo $row->shipping_street_address . ', ' . $row->shipping_region_name . ', ' . $row->shipping_country_name; ?><br />
                                                        </address>
                                                        <input type="hidden" id="sae" name="sae" value="2">
                                                        <input id="cur_shipping_region_id" name="cur_shipping_region_id" type="hidden" value="<?php echo $row->shipping_region_id ?>">
                                                        <div class="ps-section__footer">
                                                            <div class="ps-block--shopping-total">
                                                                <div class="form-group mb-0">
                                                                    <div class="ps-checkbox">
                                                                        <input class="form-control" type="checkbox" id="chk_different_shipping_address" name="chk_different_shipping_address">
                                                                        <label for="chk_different_shipping_address">Ship to a Different Address?</label>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="ps-form__billing-info">
                                                    <div id="div_shipping_address" class="<?php if ($row->shipping_first_name != '' && $row->shipping_last_name != ''){ echo 'display-none'; } ?>">
                                                        <div class="form-group mb-10">
                                                            <label>First Name<sup>*</sup></label>
                                                            <div class="form-group__content">
                                                                <input id="shipping_first_name" name="shipping_first_name" class="form-control" type="text" value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group mb-10">
                                                            <label>Last Name<sup>*</sup></label>
                                                            <div class="form-group__content">
                                                                <input id="shipping_last_name" name="shipping_last_name" class="form-control" type="text" value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group mb-10">
                                                            <label>Email Address<sup>*</sup></label>
                                                            <div class="form-group__content">
                                                                <input id="shipping_email_address" name="shipping_email_address" class="form-control" type="email" value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group mb-10">
                                                            <label>Phone Number<sup>*</sup></label>
                                                            <div class="form-group__content">
                                                                <input id="shipping_phone_number" name="shipping_phone_number" class="form-control" type="text" value="">
                                                            </div>
                                                        </div>
                                                        <div class="form-group mb-10">
                                                            <label>Delivery Address<sup>*</sup></label>
                                                            <div class="form-group__content">
                                                                <textarea id="shipping_street_address" name="shipping_street_address" class="form-control" rows="1" placeholder="Street Name / Building / Apartment No. / Floor"></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="form-group mb-10">
                                                            <label>Country<sup>*</sup></label>
                                                            <div class="form-group__content">
                                                                <select id="shipping_country_id" name="shipping_country_id" class="form-control" data-placeholder="Select Country" data-fouc>
                                                                    <option value="">Select Country</option>
                                                                    <?php foreach ($countries as $row2): ?>
                                                                        <option value="<?php echo $row2->country_id; ?>"><?php echo $row2->country_name; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <label>Region<sup>*</sup></label>
                                                                <div class="form-group form-group-feedback form-group-feedback-right mb-2">
                                                                    <select id="shipping_region_id" name="shipping_region_id" class="form-control" data-placeholder="Select Region" data-fouc>
                                                                        <option value="">Select Region</option>
                                                                    </select>
                                                                    <div id="shipping_region_loader" class="form-control-feedback display-none">
                                                                        <i class="fa fa-spinner fa-spin text-success"></i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <script type="text/javascript">
                                                    var cur_shipping_region_id = '<?php echo $row->shipping_region_id; ?>';

                                                    $(document).ready(function() {
                                                        $("#shipping_country_id").trigger("change");
                                                    });
                                                </script>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="ps-form__billing-info">
                                                <div id="div_shipping_address" class="<?php //if ($row->shipping_first_name != '' && $row->shipping_last_name != ''){ echo 'display-none'; } ?>">
                                                    <input type="hidden" id="sae" name="sae" value="1">
                                                    <div class="form-group mb-10">
                                                        <label>First Name<sup>*</sup></label>
                                                        <div class="form-group__content">
                                                            <input id="shipping_first_name" name="shipping_first_name" class="form-control" type="text" value="" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-10">
                                                        <label>Last Name<sup>*</sup></label>
                                                        <div class="form-group__content">
                                                            <input id="shipping_last_name" name="shipping_last_name" class="form-control" type="text" value="" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-10">
                                                        <label>Email Address<sup>*</sup></label>
                                                        <div class="form-group__content">
                                                            <input id="shipping_email_address" name="shipping_email_address" class="form-control" type="email" value="" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-10">
                                                        <label>Phone Number<sup>*</sup></label>
                                                        <div class="form-group__content">
                                                            <input id="shipping_phone_number" name="shipping_phone_number" class="form-control" type="text" value="" required>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-10">
                                                        <label>Delivery Address<!-- <sup>*</sup> --></label>
                                                        <div class="form-group__content">
                                                            <textarea id="shipping_street_address" name="shipping_street_address" class="form-control" rows="1" placeholder="Street Name / Building / Apartment No. / Floor"></textarea>
                                                        </div>
                                                    </div>
                                                    <div class="form-group mb-10">
                                                        <label>Country<sup>*</sup></label>
                                                        <div class="form-group__content">
                                                            <select id="shipping_country_id" name="shipping_country_id" class="form-control" data-placeholder="Select Country" required data-fouc>
                                                                <option value="">Select Country</option>
                                                                <?php foreach ($countries as $row2): ?>
                                                                    <option value="<?php echo $row2->country_id; ?>"><?php echo $row2->country_name; ?></option>
                                                                <?php endforeach; ?>
                                                            </select>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <label>Region<sup>*</sup></label>
                                                            <div class="form-group form-group-feedback form-group-feedback-right mb-2">
                                                                <select id="shipping_region_id" name="shipping_region_id" class="form-control" data-placeholder="Select Region" required data-fouc>
                                                                    <option value="">Select Region</option>
                                                                </select>
                                                                <div id="shipping_region_loader" class="form-control-feedback display-none">
                                                                    <i class="fa fa-spinner fa-spin text-success"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
                                        <div class="ps-section__footer">
                                            <div class="ps-block--shopping-total bg-white p-20 mb-10">
                                                <div class="ps-block__content">
                                                    <div class="ps-block__header mb-10">
                                                        <p class="font-14">SELECT DELIVERY METHOD</p>
                                                    </div>
                                                    <div class="custom-control custom-radio mb-10">
                                                        <input type="radio" class="custom-control-input" name="chk_shipping_delivery_method" id="chk_shipping_pickup_location" value="Pickup" checked>
                                                        <label class="custom-control-label font-14" for="chk_shipping_pickup_location"><strong>Pickup Station</strong></label>
                                                    </div>

                                                    <div id="div_shipping_pickup_station">
                                                        <div class="form-group mb-15">
                                                            <label>PICKUP STATIONS NEAR YOU:</label>
                                                            <div class="form-group form-group-feedback form-group-feedback-right">
                                                                <select id="shipping_pickup_location_id" name="shipping_pickup_location_id" class="form-control" data-placeholder="Select Pickup Station" data-fouc>
                                                                    <option value="">Select Pickup Station</option>
                                                                    <?php foreach ($pickup_locations as $row2): ?>
                                                                        <option value="<?php echo $row2->pickup_location_id; ?>"><?php echo $row2->pickup_location_name . ' ~ ' . $row2->pickup_location_address; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <div id="shipping_pickup_location_loader" class="form-control-feedback display-none">
                                                                    <i class="fa fa-spinner fa-spin text-success"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="div_pickup_station_details" class="display-none" style="min-height: 60px">
                                                            
                                                        </div>
                                                    </div>

                                                    <hr class="mt-20 mb-20">

                                                    <div class="custom-control custom-radio">
                                                        <input type="radio" class="custom-control-input" name="chk_shipping_delivery_method" id="chk_shipping_delivery_location" value="Delivery">
                                                        <label class="custom-control-label font-14" for="chk_shipping_delivery_location"><strong>Delivery to your home, office or church</strong></label>
                                                    </div>

                                                    <div id="div_shipping_shipping_zone" class="display-none">
                                                        <div class="form-group mb-15">
                                                            <label>SHIPPING ZONES NEAR YOU:</label>
                                                            <div class="form-group form-group-feedback form-group-feedback-right">
                                                                <select id="shipping_shipping_zone_id" name="shipping_shipping_zone_id" class="form-control" data-placeholder="Select Shipping Zone" data-fouc>
                                                                    <option value="">Select Shipping Zone</option>
                                                                    <?php foreach ($shipping_zones as $row2): ?>
                                                                        <option value="<?php echo $row2->shipping_zone_id; ?>"><?php echo $row2->shipping_zone_name; ?></option>
                                                                    <?php endforeach; ?>
                                                                </select>
                                                                <div id="shipping_shipping_zone_loader" class="form-control-feedback display-none">
                                                                    <i class="fa fa-spinner fa-spin text-success"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div id="div_shipping_zone_details" class="display-none" style="min-height: 60px">
                                                            
                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-md-12 alert alert-primary pt-20 pb-1">
                                            <h5 class="text-center mb-4">Apply Promo Code</h5>

                                            <div id="div_promo_code_error" class="alert alert-danger display-none font-13"></div>
                                            <div id="div_promo_code_success" class="alert alert-success display-none font-13"></div>

                                            <div class="form-group">
                                                <input type="text" name="promo_code" id="promo_code" class="form-control" placeholder="Enter promo code if you have one" value="<?php if (isset($promo_code)){ foreach($promo_code as $row2){ echo $row2->promo_code; }} ?>" />
                                            </div>

                                            <div class="form-group text-center">
                                                <button type="button" id="btn_validate_promo_code" name="btn_validate_promo_code" class="ps-btn ps-btn--sm ps-btn--black" value="Validate Code">Validate Code</button>
                                            </div>

                                            <div id="div_promo_code_info" class="alert alert-success <?php if (!isset($promo_code)){ echo 'display-none'; } ?> text-center fs-11">
                                                <?php if (isset($promo_code)): ?>
                                                    <?php foreach ($promo_code as $row2): ?>
                                                        <?php if ($row2->promo_mode == 'Percentage'): ?>
                                                            <?php echo '<b>PROMO CODE:</b> ' . $row2->promo_code_name . ' [' . $row2->promo_code . '] - ' . number_format($row2->promo_value, 1) . '% Off <a href="javascript:void(0)" class="badge badge-danger btn-remove-promo-code"><i class="icon-cross-circle"></i> Remove Promo Code</a>'; ?>
                                                        <?php elseif ($row2->promo_mode == 'Amount'): ?>
                                                            <?php echo '<b>PROMO CODE:</b> ' . $row2->promo_code_name . ' [' . $row2->promo_code . '] - KES ' . number_format($row2->promo_value, 0) . ' Off <a href="javascript:void(0)" class="badge badge-danger btn-remove-promo-code"><i class="icon-cross-circle"></i> Remove Promo Code</a>'; ?>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?> 
                                                <!-- <b>PROMO CODE:</b> Admin Listing Code [HMMADM20] - 20.1% Off -->
                                            </div>
                                        </div>

                                        <div class="col-md-12 mb-20">
                                            <button id="btn_checkout_address" type="submit" class="ps-btn ps-btn--fullwidth ps-btn--rounded mt-20">Save Order &amp; Proceed to Pay <i class="icon-chevron-right"></i></button>
                                        </div>

                                    </div>
                                    
                                    
                                </div>
                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12  ">
                                <div class="ps-form__total">
                                    <h5 class="ps-form__heading">Your Order</h5>
                                    <div id="div_checkout_cart" style="min-height: 200px" class="content">
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

                    </form>
                </div>
            </div>
        </div>
    </div>