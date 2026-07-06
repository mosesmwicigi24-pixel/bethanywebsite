    <div class="ps-page--simple">
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li>Checkout Shipping Options</li>
                </ul>
            </div>
        </div>
        <div class="ps-vendor-dashboard pro">
            <div class="container">
                <div class="ps-section__content">
                    <ul class="ps-section__links mt-30 mb-40">
                        <li><a href="javascript:void(0);"><i class="icon-user"></i> Login/Register</a></li>
                        <li><a href="<?php echo base_url(); ?>checkout/address"><i class="icon-map-marker-check"></i> Address Details</a></li>
                        <li class="active"><a href="javascript:void(0);"><i class="icon-truck"></i> Shipping Options</a></li>
                        <li><a href="javascript:void(0);"><i class="icon-credit-card"></i> Payment</a></li>
                        <li><a href="javascript:void(0);"><i class="icon-checkmark-circle"></i> Complete Order</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="ps-checkout ps-section--shopping pt-0">
            <div class="container">
                <div class="ps-section__content">
                    <form id="frm_checkout_shipping" name="frm_checkout_shipping" class="ps-form--checkout" method="post" onsubmit="return submit_checkout_shipping();">
                        <div class="row">
                            <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 mb-20">
                                <div class="ps-section__footer">
                                    <div class="ps-block--shopping-total bg-white">
                                        <div class="ps-block__content pb-40">
                                            <div class="ps-block__header mb-10">
                                                <p class="font-14">SELECT DELIVERY METHOD</p>
                                            </div>
                                            <div class="custom-control custom-radio mb-20">
                                                <input type="radio" class="custom-control-input" name="chk_shipping_delivery_method" id="chk_shipping_pickup_location" value="Pickup" checked>
                                                <label class="custom-control-label font-14" for="chk_shipping_pickup_location"><strong>Pickup Station</strong></label>
                                            </div>

                                            <div id="div_shipping_pickup_station">
                                                <div class="form-group mb-30">
                                                    <label>PICKUP STATIONS NEAR YOU:</label>
                                                    <div class="form-group__content">
                                                        <select id="shipping_pickup_location_id" name="shipping_pickup_location_id" class="form-control" data-placeholder="Select Pickup Station" data-fouc>
                                                            <option value="">Select Pickup Station</option>
                                                            <?php foreach ($pickup_locations as $row2): ?>
                                                                <option value="<?php echo $row2->pickup_location_id; ?>"><?php echo $row2->pickup_location_name . ' ~ ' . $row2->pickup_location_address; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="div_pickup_station_details" class="display-none" style="min-height: 60px">
                                                    
                                                </div>
                                            </div>

                                            <hr class="mt-40 mb-40">

                                            <div class="custom-control custom-radio">
                                                <input type="radio" class="custom-control-input" name="chk_shipping_delivery_method" id="chk_shipping_delivery_location" value="Delivery">
                                                <label class="custom-control-label font-14" for="chk_shipping_delivery_location"><strong>Delivery to your home, office or church</strong></label>
                                            </div>

                                            <div id="div_shipping_shipping_zone" class="display-none">
                                                <div class="form-group mb-30">
                                                    <label>SHIPPING ZONES NEAR YOU:</label>
                                                    <div class="form-group__content">
                                                        <select id="shipping_shipping_zone_id" name="shipping_shipping_zone_id" class="form-control" data-placeholder="Select Shipping Zone" data-fouc>
                                                            <option value="">Select Shipping Zone</option>
                                                            <?php foreach ($shipping_zones as $row2): ?>
                                                                <option value="<?php echo $row2->shipping_zone_id; ?>"><?php echo $row2->shipping_zone_name; ?></option>
                                                            <?php endforeach; ?>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div id="div_shipping_zone_details" class="display-none" style="min-height: 60px">
                                                    
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>


                            </div>
                            <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12  ">
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
                                                <h5 class="ps-block__title text-right">Subtotal <span><?php echo $this->flexi_cart->sub_total();?></span></h5>
                                                <h5 class="ps-block__title text-right">Tax <span><?php echo $this->flexi_cart->tax_total();?></span></h5>
                                                <h4 class=" text-right">Total <span><?php echo $this->flexi_cart->total();?></span></h4>
                                            </div>
                                        </div>
                                        <button id="btn_checkout_shipping" type="submit" class="ps-btn ps-btn--rounded ps-btn--fullwidth mt-20">Proceed to Next Step <i class="icon-chevron-right"></i></button>
                                    </div>
                                </div>
                            </div>

                        </div>

                        
                    </form>
                </div>
            </div>
        </div>
    </div>
