<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Checkout extends CI_Controller {
    function __construct(){
		parent::__construct();
		$this->flexi = new stdClass;
        $this->load->library('flexi_cart');
        $this->load->library('flexi_cart_admin');
        $this->load->library('flexi_cart_lite');
		$this->load->model('checkout_model');
        $this->load->model('cart_model');
        $this->load->model('main_model');
        $this->load->model('be/currencies_model');
        $this->load->model('be/locations_model');
        $this->load->model('account_model');
        $this->load->model('api_model');
        $this->load->library('mpesa');
	}

	function index() {
        if (! $this->flexi_cart->cart_status()){
            redirect('cart');           
        }else{
            // if($this->session->userdata('bgs_fe_login_state')){
            //     redirect('checkout/address');
            // }else{
            //     redirect('checkout/login');
            // }  
            redirect('checkout/address');         
        }
	}
    function login() {
        $meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $data['page_title'] = 'Checkout Login';
        $data['cur'] = '';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        
        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['cart_data'] = $this->flexi_cart->cart_items();

        $data['main_content'] = 'fe/checkout_login';
        $this->load->view('fe/includes/template',$data);
    }

    function submit_login() {
        $q = $this->checkout_model->submit_login();

        if($q['res'] == true){
            $resp = array('status' => 'SUCCESS','message' => $q['dt']);
        }
        else{
            $resp = array('status' => 'ERR','message' => $q['dt']);
        }
        echo json_encode($resp);
    }

    function submit_register() {
        $q = $this->checkout_model->validate_register_customer();

        if($q['res'] == true){
            $data = array(
                'first_name' => $this->input->post('register_first_name'),
                'last_name' => $this->input->post('register_last_name'),
                'email_address' => $this->input->post('register_email_address'),
                'phone_number' => $this->input->post('register_phone_number'),
                'password' => bethany_hash($this->input->post('register_password'))
            );
            $q = $this->checkout_model->submit_register($data);
            if($q['res'] == true){
                $resp = array('status' => 'SUCCESS','message' => $q['dt']);
            }else{
                $resp = array('status' => 'ERR','message' => $q['dt']);
            }
        }else{
            $resp = array('status' => 'ERR','message' => $q['dt']);
        }
        echo json_encode($resp);
    }

    function address() {
        if (! $this->flexi_cart->cart_status()){
            redirect('cart');           
        }else{
            // if($this->session->userdata('bgs_fe_login_state')){
                $meta = $this->main_model->get_global_meta();
                $data['meta'] = $meta;

                $data['page_title'] = 'Checkout Shipping Address';
                $data['cur'] = '';

                $data['store_information'] = $this->main_model->get_store_information();
                $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
                
                $data['default_currency'] = $this->currencies_model->get_default_currency();

                $data['countries'] = $this->locations_model->get_countries_list();

                $shipping_region_id = 0;

                if($this->session->userdata('bgs_fe_login_state')){
                    $account = $this->account_model->get_account();
                    foreach ($account as $row) {
                        $shipping_region_id = $row->shipping_region_id;
                    }
                    $data['account'] = $account;
                }

                if ($this->session->userdata('checkout_promo_code_id')) {
                    $data['promo_code'] = $this->checkout_model->get_promo_code($this->session->userdata('checkout_promo_code_id'));
                }

                $data['pickup_locations'] = $this->locations_model->get_pickup_locations_list(); //get_pickup_locations_by_region_id($shipping_region_id);
                $data['shipping_zones'] = $this->locations_model->get_shipping_zones_list(); //get_shipping_zones_by_region_id($shipping_region_id);

                $data['cart_data'] = $this->flexi_cart->cart_items();

                $data['main_content'] = 'fe/checkout_address';
                $this->load->view('fe/includes/template',$data);
            // }else{
            //     redirect('checkout/login');
            // }           
        }
    }

    function remove_promo_code() {
        $this->session->unset_userdata('checkout_promo_code_id');
        $discount = array('id' => '', 'value' => 0, 'column' => 'total', 'calculation' => 1, 'tax_method' => 1, 'description' => '');
        if ($this->flexi_cart->set_discount($discount)) {
            $resp = array('status' => 'SUCCESS','message' => '<i class="icon-checkmark-circle"></i> Promo code removed successfully');
        } else {
            $resp = array('status' => 'ERR','message' => '<i class="icon-cross-circle"></i> There was an error trying to remove Promo Code. Please try again.');
        }
        echo json_encode($resp);
    }

    function loadjs_checkout_cart() {
        $data['cart_data'] = $this->flexi_cart->cart_items();
        $this->load->view('fe/jsloads/ajax_checkout_cart',$data);
    }

    function submit_checkout_address() {
        // if($this->session->userdata('bgs_fe_login_state')){

            $q = $this->checkout_model->submit_order();

            if($q['res'] == true){

                $order_number = $this->flexi_cart->order_number();
                $this->flexi_cart->destroy_cart();
                $this->session->unset_userdata('checkout_promo_code_id');

                $resp = array('status' => 'SUCCESS','message' => $q['dt'],'redir' => 'checkout/payment/' . $order_number);
            }else{
                $resp = array('status' => 'ERR','message' => $q['dt'],'redir' => '');
            }
        // }else{
        //     $resp = array('status' => 'ERR','message' => '<i class="icon-cross-circle"></i> Your session seems to have expired. Please login again to continue','redir' => 'checkout/login');         
        // }
        echo json_encode($resp);
    }

    function validate_promo_code() {
        $promo_code = $this->input->post('promo_code');

        $q = $this->checkout_model->validate_promo_code($promo_code);

        if($q['res'] == true){
            $resp = array('status' => 'SUCCESS','message' => $q['dt'], 'promo_info' => $q['promo_info'], 'promo_code_id' => $q['promo_code_id'], 'promo_code_name' => $q['promo_code_name'], 'promo_mode' => $q['promo_mode'], 'promo_value' => $q['promo_value']);
        }else{
            $resp = array('status' => 'ERR','message' => $q['dt'], 'promo_info' => '', 'promo_code_id' => '', 'promo_code_name' => '', 'promo_mode' => '', 'promo_value' => '');
        }
        echo json_encode($resp);
    }

    // function shipping() {
    //     if (! $this->flexi_cart->cart_status()){
    //         redirect('cart');           
    //     }else{
    //         if($this->session->userdata('bgs_fe_login_state')){
    //             if ($this->session->userdata('shipping_first_name')) {
    //                 $data['page_title'] = 'Checkout Shipping Options | ';
    //                 $data['cur'] = '';

    //                 $data['store_information'] = $this->main_model->get_store_information();
    //                 $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
                    
    //                 $data['default_currency'] = $this->currencies_model->get_default_currency();

    //                 $data['countries'] = $this->locations_model->get_countries_list();

    //                 $data['account'] = $this->account_model->get_account();

    //                 $data['pickup_locations'] = $this->locations_model->get_pickup_locations_by_region_id($this->session->userdata('shipping_region_id'));
    //                 $data['shipping_zones'] = $this->locations_model->get_shipping_zones_by_region_id($this->session->userdata('shipping_region_id'));

    //                 $data['cart_data'] = $this->flexi_cart->cart_items();

    //                 $data['main_content'] = 'fe/checkout_shipping';
    //                 $this->load->view('fe/includes/template',$data);
    //             }else {
    //                 redirect('checkout/address');
    //             }
    //         }else{
    //             redirect('checkout/login');
    //         }           
    //     }
    // }

    function get_shipping_pickup_location() {
        $pickup_location_id = $this->input->post('pickup_location_id');
        $data['default_currency'] = $this->currencies_model->get_default_currency();
        $data['pickup_location'] = $this->locations_model->get_pickup_location($pickup_location_id);
        $this->load->view('fe/jsloads/ajax_pickup_location_details',$data);
    }

    function get_shipping_shipping_zone() {
        $shipping_zone_id = $this->input->post('shipping_zone_id');
        $data['default_currency'] = $this->currencies_model->get_default_currency();
        $data['shipping_zone'] = $this->locations_model->get_shipping_zone($shipping_zone_id);
        $this->load->view('fe/jsloads/ajax_shipping_zone_details',$data);        
    }

    // function submit_checkout_shipping() {
    //      if($this->session->userdata('bgs_fe_login_state')){
    //         if ($this->session->userdata('shipping_first_name')){

    //             $shipping_fee = 0;

    //             $shipping_mode = $this->input->post('chk_shipping_delivery_method');
                
    //             if ($shipping_mode == 'Delivery'){
    //                 $shipping_zone = $this->locations_model->get_shipping_zone($this->input->post('shipping_shipping_zone_id'));
    //                 foreach ($shipping_zone as $row) {
    //                     $shipping_fee = $row->shipping_fee;
    //                 }
    //             }elseif ($shipping_mode == 'Pickup'){                        
    //                 $pickup_location = $this->locations_model->get_pickup_location($this->input->post('shipping_pickup_location_id'));
    //                 foreach ($pickup_location as $row){
    //                     $shipping_fee = $row->shipping_fee;
    //                 }
    //             }

    //             $this->cart_model->update_shipping_fee($shipping_fee);

    //             $q = $this->checkout_model->submit_order();

    //             if($q['res'] == true){

    //                 $order_number = $this->flexi_cart->order_number();
    //                 $this->flexi_cart->destroy_cart();

    //                 $resp = array('status' => 'SUCCESS','message' => $q['dt'],'redir' => 'checkout/payment/' . $order_number);
    //             }else{
    //                 $resp = array('status' => 'ERR','message' => $q['dt'],'redir' => '');
    //             }
    //         }else{
    //             $resp = array('status' => 'ERR','message' => '<i class="icon-cross-circle"></i> Your shopping cart is empty','redir' => 'checkout/address');
    //         }
    //     }else{
    //         $resp = array('status' => 'ERR','message' => '<i class="icon-cross-circle"></i> Your session seems to have expired. Please login again to continue','redir' => '');         
    //     }

    //     echo json_encode($resp);
    // }

    function checkout_update_shipping_fee() {

        $shipping_fee = 0;

        $shipping_mode = $this->input->post('chk_shipping_delivery_method');
        
        if ($shipping_mode == 'Delivery'){
            $shipping_zone = $this->locations_model->get_shipping_zone($this->input->post('shipping_shipping_zone_id'));
            foreach ($shipping_zone as $row) {
                $shipping_fee = $row->shipping_fee;
            }
        }elseif ($shipping_mode == 'Pickup'){                        
            $pickup_location = $this->locations_model->get_pickup_location($this->input->post('shipping_pickup_location_id'));
            foreach ($pickup_location as $row){
                $shipping_fee = $row->shipping_fee;
            }
        }
        $this->cart_model->update_shipping_fee($shipping_fee);

    }

    function debug_to_console($str, $context = 'Debug in Console') {

        // Buffering to solve problems frameworks, like header() in this and not a solid return.
        ob_start();

        $output  = 'console.info(\'' . $context . ':\');';
        $output .= 'console.log(' . $str . ');';
        $output  = sprintf('<script>%s</script>', $output);

        echo $output;
    }

    function payment($order_number) {
        // if($this->session->userdata('bgs_fe_login_state')){
            $order_status = 0;
            $order = $this->checkout_model->get_order($order_number);
            foreach ($order as $row) {
                $order_status = $row->ord_order_status;
            }

            if ($order_status != 0) {
                redirect('account/order/' . $order_number);
            }else {
                $meta = $this->main_model->get_global_meta();
                $data['meta'] = $meta;

                $data['page_title'] = 'Checkout Payment';
                $data['cur'] = '';

                $data['store_information'] = $this->main_model->get_store_information();
                $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
                
                $data['default_currency'] = $this->currencies_model->get_default_currency();

                $data['order'] = $order;
                $data['order_details'] = $this->checkout_model->get_order_details($order_number);
                $data['mpesa_settings'] = $this->api_model->get_mpesa_settings();
                $data['pesapal_settings'] = $this->api_model->get_pesapal_settings();

                $data['cart_data'] = $this->flexi_cart->cart_items();

                $data['main_content'] = 'fe/checkout_payment';
                $this->load->view('fe/includes/template',$data);
            }

        // }else{
        //     redirect('account/login');
        // }
    }

    function publish_stk_request() {
        $CONSUMER_KEY = '';
        $CONSUMER_SECRET = '';
        $PASSKEY = '';
        $ENVIRONMENT = '';
        $SHORTCODE = 0;

        $mpesa_settings = $this->api_model->get_mpesa_settings();
        foreach($mpesa_settings as $row){
            $CONSUMER_KEY = $row->consumer_key;
            $CONSUMER_SECRET = $row->consumer_secret;
            $PASSKEY = $row->passkey;
            $ENVIRONMENT = $row->environment;
            $SHORTCODE = (int)$row->short_code;
        }

        //ORDER INFO
        $ORDER_NUMBER = $this->input->post('ord_order_number');

        //ORDER AMOUNT
        $AMOUNT = (float)$this->input->post('order_total');

        $MSISDN = '254' . $this->input->post('payment_phone_number');

        $mpesa= new Mpesa();
         $resp = $mpesa->STKPushSimulation($SHORTCODE, $PASSKEY, 'CustomerPayBillOnline', $AMOUNT, $MSISDN, $SHORTCODE, $MSISDN, 'https://bethanyhouse.co.ke/api/stk_cb', $ORDER_NUMBER, 'ORD', 'ORD', $ENVIRONMENT, $CONSUMER_KEY, $CONSUMER_SECRET);
         echo $resp;

    }

    function submit_checkout_payment() {
        $order_number = $this->input->post('ord_order_number');
        $order_total = 0;
        $order = $this->checkout_model->get_order($order_number);
        foreach ($order as $row) {
            $order_total = number_format((float)$row->ord_total,0, '.','');
        }

        if (is_numeric($order_total) || $order_total <= 0){
            
            $payment_exists = $this->checkout_model->check_payment_exists($order_number);
            
            if ($payment_exists > 0){

                $payment = $this->checkout_model->get_payment($order_number);
                
                $amount_paid  = 0;                
                $order_total = (double)$order_total;

                foreach($payment as $row){
                    $amount_paid = $amount_paid + $row->transaction_amount;
                }

                if ($amount_paid == $order_total){
                    $this->checkout_model->complete_order($order_number);
                    $resp = array('status' => 'SUCCESS','message' => '<i class="icon-checkmark4"></i> Order completed successfully. Please wait as you are being redirected to the next page...','redir' => 'checkout/complete/' . $order_number);
                }else{
                    $resp = array('status' => 'ERR','message' => '<i class="fa fa-exclamation-circle"></i> Invalid amount <br><br> Required Amount: ' . number_format((float)$order_total, 2, '.', ',') . '<br> Paid Amount: ' . number_format((float)$amount_paid, 2, '.', ','),'redir' => '');
                }
            }else{
                $resp = array('status' => 'ERR','message' => '<i class="fa fa-exclamation-circle"></i> Transaction not found','redir' => '');
            }
        }else{
            $resp = array('status' => 'ERR','message' => '<i class="fa fa-exclamation-circle"></i> Invalid amount.','redir' => '');
        }
        echo json_encode($resp);
    }

    function complete($order_number) {
        // if($this->session->userdata('bgs_fe_login_state')){

            $REFERENCE = null;
            $PESAPAL_TRANSACTION_TRACKING_ID = null;
        
            if(isset($_GET['pesapal_merchant_reference'])){
                $REFERENCE = $_GET['pesapal_merchant_reference'];
                if(isset($_GET['pesapal_transaction_tracking_id'])){
                    $PESAPAL_TRANSACTION_TRACKING_ID = $_GET['pesapal_transaction_tracking_id'];
                    
                    $this->checkout_model->complete_pesapal_payment($order_number, $REFERENCE, $PESAPAL_TRANSACTION_TRACKING_ID);
                }
            }

            
            $order_status = 0;
            $order = $this->checkout_model->get_order($order_number);
            foreach ($order as $row) {
                $order_status = $row->ord_order_status;
            }

            if ($order_status == 0) {
                redirect('checkout/payment/' . $order_number);
            } elseif ($order_status == 1) {
                $meta = $this->main_model->get_global_meta();
                $data['meta'] = $meta;

                $data['page_title'] = 'Checkout Order Complete';
                $data['cur'] = '';

                $data['store_information'] = $this->main_model->get_store_information();
                $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
                
                $data['default_currency'] = $this->currencies_model->get_default_currency();

                $data['order'] = $order;
                $data['order_details'] = $this->checkout_model->get_order_details($order_number);

                $data['cart_data'] = $this->flexi_cart->cart_items();

                $data['main_content'] = 'fe/checkout_complete';
                $this->load->view('fe/includes/template',$data);
            // } else {
            //     redirect('account/order/' . $order_number);
            // }
        }else{
            redirect('account/login');
        }

    }


}

