<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Account extends CI_Controller {
    function __construct(){
		parent::__construct();
		$this->flexi = new stdClass;
        $this->load->library('flexi_cart');
        $this->load->library('flexi_cart_admin');
        $this->load->library('flexi_cart_lite');
		$this->load->model('cart_model');
        $this->load->model('main_model');
        $this->load->model('account_model');
        $this->load->model('be/currencies_model');
        $this->load->model('be/locations_model');
	}

    function index() {
        if($this->session->userdata('bgs_fe_login_state')){
            $meta = $this->main_model->get_global_meta();
            $data['meta'] = $meta;

            $data['page_title'] = 'My Account';
            $data['cur'] = '';

            $data['store_information'] = $this->main_model->get_store_information();
            $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
            
            $data['default_currency'] = $this->currencies_model->get_default_currency();

            $data['account'] = $this->account_model->get_account();

            $q = $this->account_model->get_recent_orders();
            $data['recent_orders'] = $q['records'];
            $data['num_recent_orders'] = $q['record_count'];

            $data['cart_data'] = $this->flexi_cart->cart_items();

            $data['main_content'] = 'fe/account';
            $this->load->view('fe/includes/template',$data);
        }else{
            redirect('account/login');
        }           
    }

    function login() {
        if($this->session->userdata('bgs_fe_login_state')){
            redirect('account');
        }else{
            $meta = $this->main_model->get_global_meta();
            $data['meta'] = $meta;

            $data['page_title'] = 'Account Login';
            $data['cur'] = '';

            $data['store_information'] = $this->main_model->get_store_information();
            $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
            
            $data['default_currency'] = $this->currencies_model->get_default_currency();

            $data['cart_data'] = $this->flexi_cart->cart_items();

            $data['main_content'] = 'fe/account_login';
            $this->load->view('fe/includes/template',$data);
        }           
    }
    function submit_login() {
        $q = $this->account_model->submit_login();

        if($q['res'] == true){
            $resp = array('status' => 'SUCCESS','message' => $q['dt']);
        }
        else{
            $resp = array('status' => 'ERR','message' => $q['dt']);
        }
        echo json_encode($resp);
    }

    function register() {
        if($this->session->userdata('bgs_fe_login_state')){
            redirect('account');
        }else{
            $meta = $this->main_model->get_global_meta();
            $data['meta'] = $meta;

            $data['page_title'] = 'Create Account';
            $data['cur'] = '';

            $data['store_information'] = $this->main_model->get_store_information();
            $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
            
            $data['default_currency'] = $this->currencies_model->get_default_currency();

            $data['cart_data'] = $this->flexi_cart->cart_items();

            $data['main_content'] = 'fe/account_register';
            $this->load->view('fe/includes/template',$data);
        }           
    }
    function submit_register() {
        $q = $this->account_model->validate_register_customer();

        if($q['res'] == true){
            $data = array(
                'first_name' => $this->input->post('register_first_name'),
                'last_name' => $this->input->post('register_last_name'),
                'email_address' => $this->input->post('register_email_address'),
                'phone_number' => $this->input->post('register_phone_number'),
                'password' => bethany_hash($this->input->post('register_password'))
            );
            $q = $this->account_model->submit_register($data);
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

    function reset_password() {
        if($this->session->userdata('bgs_fe_login_state')){
            redirect('account');
        }else{
            $meta = $this->main_model->get_global_meta();
            $data['meta'] = $meta;

            $data['page_title'] = 'Reset Password';
            $data['cur'] = '';

            $data['store_information'] = $this->main_model->get_store_information();
            $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
            
            $data['default_currency'] = $this->currencies_model->get_default_currency();

            $data['cart_data'] = $this->flexi_cart->cart_items();

            $data['main_content'] = 'fe/account_reset_password';
            $this->load->view('fe/includes/template',$data);
        }           
    }

    function submit_reset_password() {
        $q = $this->account_model->reset_password();

        if($q['res'] == true){
            $resp = array('status' => 'SUCCESS','message' => $q['dt']);
        }
        else{
            $resp = array('status' => 'ERR','message' => $q['dt']);
        }
        echo json_encode($resp);
    }

    function logout() {
        //$this->session->sess_destroy();
        $this->session->unset_userdata('bgs_fe_login_state');
        $this->session->unset_userdata('customer_id');
        $this->session->unset_userdata('customer_email_address');
        $this->session->unset_userdata('customer_phone_number');
        $this->session->unset_userdata('customer_first_name');
        $this->session->unset_userdata('customer_last_name');
        $this->session->unset_userdata('customer_profile_picture_thumb');
        redirect('account');
    }

    function orders() {
        if($this->session->userdata('bgs_fe_login_state')){
            $meta = $this->main_model->get_global_meta();
            $data['meta'] = $meta;

            $data['page_title'] = 'My Orders';
            $data['cur'] = '';

            $data['store_information'] = $this->main_model->get_store_information();
            $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
            
            $data['default_currency'] = $this->currencies_model->get_default_currency();

            $data['account'] = $this->account_model->get_account();

            $q = $this->account_model->get_orders();
            $data['orders'] = $q['records'];
            $data['num_orders'] = $q['record_count'];

            $data['cart_data'] = $this->flexi_cart->cart_items();

            $data['main_content'] = 'fe/account_orders';
            $this->load->view('fe/includes/template',$data);
        }else{
            redirect('account/login');
        }
    }

    function loadjs_orders() {
        $data['default_currency'] = $this->currencies_model->get_default_currency();
        $q = $this->account_model->get_orders();
        $data['orders'] = $q['records'];
        $data['num_orders'] = $q['record_count'];
        $this->load->view('fe/jsloads/account_orders',$data);
    }

    function loadjs_recent_orders() {
        $data['default_currency'] = $this->currencies_model->get_default_currency();
        $q = $this->account_model->get_recent_orders();
        $data['recent_orders'] = $q['records'];
        $data['num_recent_orders'] = $q['record_count'];
        $this->load->view('fe/jsloads/account_recent_orders',$data);

    }

    function favorites() {
        if($this->session->userdata('bgs_fe_login_state')){
            $meta = $this->main_model->get_global_meta();
            $data['meta'] = $meta;

            $data['page_title'] = 'My Favorite Products';
            $data['cur'] = '';

            $data['store_information'] = $this->main_model->get_store_information();
            $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
            
            $data['default_currency'] = $this->currencies_model->get_default_currency();

            $data['account'] = $this->account_model->get_account();

            $q = $this->account_model->get_favorite_products();
            $data['favorite_products'] = $q['records'];
            $data['num_favorite_products'] = $q['record_count'];

            $data['cart_data'] = $this->flexi_cart->cart_items();

            $data['main_content'] = 'fe/account_favorites';
            $this->load->view('fe/includes/template',$data);
        }else{
            redirect('account/login');
        }
    }

    function favorite_product($product_id) {
        if ($this->session->userdata('hmm_fe_login_state')){
            $q = $this->account_model->favorite_product($product_id);
            if($q['res'] == TRUE){
                $resp = array('status' => 'SUCCESS','message' => $q['dt']);         
            }else{                  
                $resp = array('status' => 'ERR','message' => $q['dt']);         
            }
        }else{
            $resp = array('status' => 'ERR','message' => 'Please login to favorite this product');         
        }
        echo json_encode($resp);
    }

    function remove_favorite_product($favorite_product_id) {
        if($this->session->userdata('bgs_fe_login_state')){
            $q = $this->account_model->remove_favorite_product($favorite_product_id);
            if($q['res'] == TRUE){
                $resp = array('status' => 'SUCCESS','message' => $q['dt']);         
            }else{                  
                $resp = array('status' => 'ERR','message' => $q['dt']);         
            }
        }else{
            $resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> Your session seems to have expired. Please login again to continue');         
        }
        echo json_encode($resp);
    }

    function loadjs_favorite_products() {
        $data['default_currency'] = $this->currencies_model->get_default_currency();        
        $q = $this->account_model->get_favorite_products();
        $data['favorite_products'] = $q['records'];
        $data['num_favorite_products'] = $q['record_count'];
        $this->load->view('fe/jsloads/account_favorites',$data);
   }

   function edit() {
        if($this->session->userdata('bgs_fe_login_state')){
            $meta = $this->main_model->get_global_meta();
            $data['meta'] = $meta;

            $data['page_title'] = 'My Favorite Products';
            $data['cur'] = '';

            $data['store_information'] = $this->main_model->get_store_information();
            $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
            
            $data['default_currency'] = $this->currencies_model->get_default_currency();

            $data['account'] = $this->account_model->get_account();

            $data['cart_data'] = $this->flexi_cart->cart_items();

            $data['main_content'] = 'fe/account_edit';
            $this->load->view('fe/includes/template',$data);
        }else{
            redirect('account/login');
        }
    }

    function update_account() {
        if ($this->session->userdata('bgs_fe_login_state')){

            $customer_id = $this->session->userdata('customer_id');
            $q = $this->account_model->validate_update_account_duplicate($customer_id);
            if ($q['res'] == true){
                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'email_address' => $this->input->post('email_address'),
                    'phone_number' => $this->input->post('phone_number'),
                    'gender' => $this->input->post('gender'),
                    'birth_date' => $this->input->post('birth_date')
                );

                $q = $this->account_model->update_account($data,$customer_id);

                if ($q['res'] == true){
                    $resp = array('status' => 'SUCCESS','message' => $q['dt']);
                }else{
                    $resp = array('status' => 'ERR','message' => $q['dt']);
                }
            }else{
                $resp = array('status' => 'ERR','message' => $q['dt']);
            }
        }else{
            $resp = array('status' => 'ERR','message' => '<i class="ion-close-circled"></i> Your session seems to have expired. Please login again to continue.');
        }
        echo json_encode($resp);
    }

    function change_password() {
        if ($this->session->userdata('bgs_fe_login_state')){

            $old_password = $this->input->post('old_password');
            $customer_id = $this->session->userdata('customer_id');

            if ($this->account_model->old_password_valid($old_password, $customer_id) == false){
                $resp = array('status' => 'ERR','message' => '<i class="ion-close-circled"></i> The Old Password you have provided is incorrect.');
            }else{

                $data = array(
                    'password' => bethany_hash($this->input->post('new_password'))
                );

                $q = $this->account_model->update_password($data,$customer_id);

                if ($q['res'] == true){
                    $resp = array('status' => 'SUCCESS','message' => $q['dt']);
                }else{
                    $resp = array('status' => 'ERR','message' => $q['dt']);
                }
            }
        }else{
            $resp = array('status' => 'ERR','message' => '<i class="ion-close-circled"></i> Your session seems to have expired. Please login again to continue.');
        }
        echo json_encode($resp);
    }

    function address() {
        if($this->session->userdata('bgs_fe_login_state')){
            $meta = $this->main_model->get_global_meta();
            $data['meta'] = $meta;

            $data['page_title'] = 'My Favorite Products';
            $data['cur'] = '';

            $data['store_information'] = $this->main_model->get_store_information();
            $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
            
            $data['default_currency'] = $this->currencies_model->get_default_currency();
            $data['countries'] = $this->locations_model->get_countries_list();

            $data['account'] = $this->account_model->get_account();

            $data['cart_data'] = $this->flexi_cart->cart_items();

            $data['main_content'] = 'fe/account_address';
            $this->load->view('fe/includes/template',$data);
        }else{
            redirect('account/login');
        }
    }

    function update_address() {
        if ($this->session->userdata('bgs_fe_login_state')){

            $customer_id = $this->session->userdata('customer_id');
            $data = array(
                'shipping_first_name'               =>  $this->input->post('shipping_first_name'),
                'shipping_last_name'                =>  $this->input->post('shipping_last_name'),    
                'shipping_email_address'            =>  $this->input->post('shipping_email_address'),   
                'shipping_phone_number'             =>  $this->input->post('shipping_phone_number'),  
                'shipping_street_address'           =>  $this->input->post('shipping_street_address'),   
                'shipping_country_id'               =>  $this->input->post('shipping_country_id'),   
                'shipping_region_id'                =>  $this->input->post('shipping_region_id')
            );

            $q = $this->account_model->update_address($data,$customer_id);

            if ($q['res'] == true){
                $resp = array('status' => 'SUCCESS','message' => $q['dt']);
            }else{
                $resp = array('status' => 'ERR','message' => $q['dt']);
            }
        }else{
            $resp = array('status' => 'ERR','message' => '<i class="ion-close-circled"></i> Your session seems to have expired. Please login again to continue.');
        }
        echo json_encode($resp);

    }

    function order($order_number) {
        if($this->session->userdata('bgs_fe_login_state')){
            $meta = $this->main_model->get_global_meta();
            $data['meta'] = $meta;

            $data['page_title'] = 'Order';
            $data['cur'] = '';

            $data['store_information'] = $this->main_model->get_store_information();
            $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
            
            $data['default_currency'] = $this->currencies_model->get_default_currency();

            $data['account'] = $this->account_model->get_account();
            $data['order'] = $this->account_model->get_order($order_number);
            $data['order_details'] = $this->account_model->get_order_details($order_number);

            $data['cart_data'] = $this->flexi_cart->cart_items();

            $data['main_content'] = 'fe/account_order';
            $this->load->view('fe/includes/template',$data);
        }else{
            redirect('account/login');
        }
    }

    function cancel_order($order_number) {
        if ($this->session->userdata('bgs_fe_login_state')){

            $q = $this->account_model->cancel_order($order_number);

            if ($q['res'] == true){
                $resp = array('status' => 'SUCCESS','message' => $q['dt']);
            }else{
                $resp = array('status' => 'ERR','message' => $q['dt']);
            }
        }else{
            $resp = array('status' => 'ERR','message' => '<i class="ion-close-circled"></i> Your session seems to have expired. Please login again to continue.');
        }
        echo json_encode($resp);

    }

    function restore_order($order_number) {
        if ($this->session->userdata('bgs_fe_login_state')){

            $q = $this->account_model->restore_order($order_number);

            if ($q['res'] == true){
                $resp = array('status' => 'SUCCESS','message' => $q['dt']);
            }else{
                $resp = array('status' => 'ERR','message' => $q['dt']);
            }
        }else{
            $resp = array('status' => 'ERR','message' => '<i class="ion-close-circled"></i> Your session seems to have expired. Please login again to continue.');
        }
        echo json_encode($resp);

    }


}