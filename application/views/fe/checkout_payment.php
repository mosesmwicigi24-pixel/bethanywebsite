    <div class="ps-page--simple">
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li>Checkout Payment</li>
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
                        <li><a href="javascript:void(0);"><i class="icon-map-marker-check"></i> Address &amp; Shipping Options</a></li>
                        <!-- <li><a href="javascript:void(0);"><i class="icon-truck"></i> Shipping Options</a></li> -->
                        <li class="active"><a href="javascript:void(0);"><i class="icon-credit-card"></i> Payment</a></li>
                        <li><a href="javascript:void(0);"><i class="icon-checkmark-circle"></i> Complete Order</a></li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="ps-checkout ps-section--shopping pt-0">
            <div class="container">
                <div class="ps-section__content">
                    <div class="ps-form--account ps-tab-root">
                        <div class="row">
                            <?php foreach ($order as $row): ?>
                                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 mb-20">
                                    <div class="ps-section__footer">
                                        <div class="ps-block--shopping-total bg-white">
                                            <div class="ps-block__content pb-40">
                                                <div class="ps-block__header mb-10">
                                                    <p class="font-14">SELECT YOUR PAYMENT OPTION</p>
                                                </div>

                                                <div class="custom-control custom-radio mb-20">
                                                    <input type="radio" class="custom-control-input" name="chk_payment_option" id="chk_payment_mpesa" value="Mpesa">
                                                    <label class="custom-control-label font-14" for="chk_payment_mpesa"><strong><img class="nav-tabs-icon2" src="<?php echo base_url();?>assets/fe/img/lipanampesa.png"></strong> <small>(Pay with Mpesa Paybill)</small></label>
                                                </div>

                                                <div id="div_mpesa_payment" class="display-none">
                                                    <ul class="ps-tab-list text-left mb-20">
                                                        <li class="active"><a href="#express-checkout"><img class="nav-tabs-icon" src="<?php echo base_url();?>assets/fe/img/mpesa.png"> MPESA Express Payment</a></li>
                                                         <li><a href="#standard-checkout"><img class="nav-tabs-icon" src="<?php echo base_url();?>assets/fe/img/mpesa.png"> MPESA Standard Payment</a></li>
                                                    </ul>
                                                    <div class="ps-tabs">
                                                        <div class="ps-tab bg-white active" id="express-checkout">
                                                            <div class="ps-form__content pt-0">
                                                                
                                                                <ul class="list-check mb-5">
                                                                    <li>Enter the phone number you wish to use to pay with</li>
                                                                    <li>Click on the 'Send Request' button to generate a payment request on your phone</li>
                                                                    <li>Check your phone number and enter your MPESA PIN on your phone to complete the payment</li>
                                                                    <li>You will get your receipt from MPESA</li>
                                                                    <li>Click the <span class="font-weight-bold">'Complete Order'</span> button once you have received Mpesa Confirmation text</li>
                                                                </ul>

                                                                <form id="frm_send_publish_mpesa_request" name="frm_send_publish_mpesa_request" class="" method="post" onsubmit="return send_publish_mpesa_request();">
                                                                    <input type="hidden" id="order_total" name="order_total" value="<?php echo number_format((float)$row->ord_total,0, '.',''); ?>">
                                                                    <input type="hidden" id="order_id" name="ord_order_number" value="<?php echo $row->ord_order_number; ?>">
                                                                    <input type="hidden" id="vehicle_listing_sku_code" name="vehicle_listing_sku_code" value="<?php //echo $vehicle_listing_sku_code; ?>">

                                                                    
                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <div class="input-group mb-5">
                                                                                <div class="input-group-prepend">
                                                                                    <div class="input-group-text">+254</div>
                                                                                </div>
                                                                                <input id="payment_phone_number" name="payment_phone_number" type="number" class="form-control letter-spacing-2x qty-input" placeholder="700111222" min="0">
                                                                            </div>
                                                                        </div>
                                                                        <div class="col-md-6">
                                                                            <button id="btn_send_publish_mpesa_request" type="submit" class="ps-btn ps-btn--sm ps-btn--rounded ps-btn--black">Send Request to Phone <em class="fa fa-chevron-circle-right"></em></button>
                                                                        </div>
                                                                    </div>
                                                                </form>
                                                            </div>
                                                        </div>
                                                        <div class="ps-tab bg-white" id="standard-checkout">
                                                            <?php
                                                                $short_code = '';

                                                                foreach ($mpesa_settings as $row2){
                                                                    $short_code = $row2->short_code;
                                                                }

                                                            ?>
                                                            <div class="ps-form__content pt-0">
                                                                <ul class="list-check">
                                                                    <li>Go to <span class="font-weight-bold">Safaricom</span> Menu</li>
                                                                    <li>Select <span class="font-weight-bold">M-PESA</span></li>
                                                                    <li>Select <span class="font-weight-bold">Lipa na M-PESA</span></li>
                                                                    <li>Select <span class="font-weight-bold">Paybill</span></li>
                                                                    <li>Enter Business No: <span class="font-weight-bold"><?php echo $short_code; ?></span></li>
                                                                    <li>Enter Account No: <span class="font-weight-bold"><?php echo $row->ord_order_number; ?></span></li>
                                                                    <li>Enter Amount: <span class="font-weight-bold"><?php echo $default_currency; ?> <?php echo number_format((float)$row->ord_total,0, '.',''); ?></span></li>
                                                                    <li>Enter your MPESA <span class="font-weight-bold">PIN</span> and send</li>
                                                                    <li>You will receive a confirmation SMS from MPESA</li>
                                                                    <li>Click the <span class="font-weight-bold">'Complete Order'</span> button once you have received Mpesa Confirmation text</li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        <form id="frm_checkout_payment" name="frm_checkout_payment" class="" method="post" onsubmit="return submit_checkout_payment();">
                                                            <input type="hidden" id="ord_order_number" name="ord_order_number" value="<?php echo $row->ord_order_number; ?>">
                                                            <button id="btn_checkout_payment" type="submit" class="ps-btn ps-btn--rounded pull-right">Complete Order <i class="icon-chevron-right"></i></button>
                                                        </form>
                                                        <div class="clearfix"></div>
                                                    </div>
                                                </div>


                                                <hr class="mt-20 mb-30">

                                                <div class="custom-control custom-radio mb-20">
                                                    <input type="radio" class="custom-control-input" name="chk_payment_option" id="chk_payment_pesapal" value="Pesapal">
                                                    <label class="custom-control-label font-14" for="chk_payment_pesapal"><strong><img class="nav-tabs-icon2" src="<?php echo base_url();?>assets/fe/img/pesapal.png"></strong><small> (Pay with Card, Mpesa or Airtel Money)</small></label>
                                                </div>

                                                <div id="div_pesapal_payment" class="display-none">
                                                    <?php 

                                                        $pesapal_consumer_key = '';
                                                        $pesapal_secret_key = '';
                                                        $pesapal_environment = '';

                                                        foreach ($pesapal_settings as $row2) {
                                                            $pesapal_consumer_key = $row2->consumer_key;
                                                            $pesapal_secret_key = $row2->consumer_secret;
                                                            $pesapal_environment = $row2->environment;
                                                        }

                                                        $this->load->view('fe/OAuth/OAuth'); 
                                                        
                                                        //pesapal params
                                                        $token = $params = NULL;

                                                        /*
                                                        PesaPal Sandbox is at http://demo.pesapal.com. Use this to test your developement and 
                                                        when you are ready to go live change to https://www.pesapal.com.
                                                        */
                                                        
                                                        $consumer_key = $pesapal_consumer_key;//Register a merchant account on
                                                           //demo.pesapal.com and use the merchant key for testing.
                                                           //When you are ready to go live make sure you change the key to the live account
                                                           //registered on www.pesapal.com!
                                                           
                                                        $consumer_secret = $pesapal_secret_key;// Use the secret from your test
                                                            //account on demo.pesapal.com. When you are ready to go live make sure you 
                                                            //change the secret to the live account registered on www.pesapal.com!

                                                        $signature_method = new OAuthSignatureMethod_HMAC_SHA1();

                                                        if ($pesapal_environment == 'LIVE') {
                                                            $iframelink = 'https://www.pesapal.com/API/PostPesapalDirectOrderV4';
                                                        } else {
                                                            $iframelink = 'https://demo.pesapal.com/api/PostPesapalDirectOrderV4';  
                                                        }
                                                        
                                                        //change to      
                                                            //https://www.pesapal.com/API/PostPesapalDirectOrderV4 when you are ready to go live!
                                                        
                                                        //get form details
                                                        $amount = $row->ord_total;;//$_POST['amount'];
                                                        $amount = number_format($amount, 2);//format amount to 2 decimal places
                                                        
                                                        $desc = 'Order Number '. $row->ord_order_number;//$_POST['description'];
                                                        $type = 'MERCHANT';//$_POST['type']; //default value = MERCHANT
                                                        $reference = $row->ord_order_number;//$_POST['reference'];//unique order id of the transaction, generated by merchant
                                                        $first_name = $row->ord_shipping_first_name;//$_POST['first_name'];
                                                        $last_name = $row->ord_shipping_last_name;//$_POST['last_name'];
                                                        $email = $row->ord_shipping_email_address;//$_POST['email'];
                                                        $phonenumber = $row->ord_shipping_phone_number;//ONE of email or phonenumber is required
                                                        
                                                        $callback_url = base_url().'checkout/complete/' . $row->ord_order_number; //redirect url, the page that will handle the response from pesapal.
                                                        
                                                        $post_xml = "<?xml version=\"1.0\" encoding=\"utf-8\"?><PesapalDirectOrderInfo xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" Amount=\"".$amount."\" Description=\"".$desc."\" Type=\"".$type."\" Reference=\"".$reference."\" FirstName=\"".$first_name."\" LastName=\"".$last_name."\" Email=\"".$email."\" PhoneNumber=\"".$phonenumber."\" xmlns=\"http://www.pesapal.com\" />";
                                                        $post_xml = htmlentities($post_xml);
                                                        
                                                        $consumer = new OAuthConsumer($consumer_key, $consumer_secret);

                                                        //post transaction to pesapal
                                                        $iframe_src = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $iframelink, $params);
                                                        $iframe_src->set_parameter("oauth_callback", $callback_url);
                                                        $iframe_src->set_parameter("pesapal_request_data", $post_xml);
                                                        $iframe_src->sign_request($signature_method, $consumer, $token);
                                                        
                                                        //display pesapal - iframe and pass iframe_src
                                                    ?>
                                                    <iframe src="<?php echo $iframe_src;?>" width="100%" height="500"  scrolling="yes" frameBorder="0" id="paymentiFrame">
                                                        <p>Browser unable to load iFrame</p>
                                                    </iframe>
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
                                                            <?php foreach($order_details as $row2): ?>
                                                                <tr>
                                                                    <td><a href="<?php echo base_url(); ?>product/<?php echo $row2->product_reference_id; ?>"> <?php echo $row2->ord_det_item_name;?><?php if ($row2->ord_det_product_variation_description != ''){ echo ' (' . $row2->ord_det_product_variation_description . ')'; }?> ×<?php echo number_format($row2->ord_det_quantity,0);?></a>
                                                                        <p>SKU Code:<strong><?php echo $row2->product_sku_code; ?></strong></p>
                                                                    </td>
                                                                    <td class="text-right"><?php echo number_format($row2->ord_det_price,2); ?></td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                    <h5 class="ps-block__title text-right"><small>Subtotal:</small> <span><?php echo number_format($row->ord_item_summary_total,2);?></span></h5>
                                                    <h5 class="ps-block__title text-right"><small>Tax:</small> <span><?php echo number_format($row->ord_tax_total,2);?></span></h5>
                                                    <h5 class="ps-block__title text-right"><small>Shipping:</small> <span><?php echo number_format($row->ord_shipping_total,2);?></span></h5>
                                                    <h5 class="ps-block__title text-right"><small>Discount:</small> <span><?php echo number_format($row->ord_savings_total,2);?></span></h5>
                                                    <h4 class=" text-right"><small>Total:</small> <span><?php echo number_format($row->ord_total,2);?></span></h4>
                                                </div>
                                            </div>
                                            
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
