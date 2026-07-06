<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Affiliates extends CI_Controller {
    function __construct(){
		parent::__construct();
        $this->flexi = new stdClass;
        $this->load->library('flexi_cart');
        $this->load->library('geoip');     
		$this->load->model('main_model');
        $this->load->model('affiliates_model');
        $this->load->model('be/currencies_model');
        $this->load->model('be/locations_model');
	}

	function index(){
        $meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $data['page_title'] = 'Affiliates';
        $data['cur'] = '';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        $data['home_top_product_categories'] = $this->main_model->get_home_top_product_categories();
        
        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['cart_data'] = $this->flexi_cart->cart_items();

		$data['main_content'] = 'fe/affiliates';
		$this->load->view('fe/includes/template',$data);
	}

    function login() {
        if($this->session->userdata('bgs_affiliate_login_state')){
            redirect('affiliates/account');
        }else{
            $meta = $this->main_model->get_global_meta();
            $data['meta'] = $meta;

            $data['page_title'] = 'Affiliate Login';
            $data['cur'] = '';

            $data['store_information'] = $this->main_model->get_store_information();
            $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
            
            $data['default_currency'] = $this->currencies_model->get_default_currency();

            $data['cart_data'] = $this->flexi_cart->cart_items();

            $data['main_content'] = 'fe/affiliate_login';
            $this->load->view('fe/includes/template',$data);
        }           
    }

    function submit_login() {
        $q = $this->affiliates_model->submit_login();

        if($q['res'] == true){
            $resp = array('status' => 'SUCCESS','message' => $q['dt']);
        }
        else{
            $resp = array('status' => 'ERR','message' => $q['dt']);
        }
        echo json_encode($resp);
    }

    function register() {
        if($this->session->userdata('bgs_affiliate_login_state')){
            redirect('affiliates/account');
        }else{
            $meta = $this->main_model->get_global_meta();
            $data['meta'] = $meta;

            $data['page_title'] = 'Affiliate Register';
            $data['cur'] = '';

            $data['store_information'] = $this->main_model->get_store_information();
            $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
            $data['home_top_product_categories'] = $this->main_model->get_home_top_product_categories();
            
            $data['default_currency'] = $this->currencies_model->get_default_currency();
            $data['countries'] = $this->locations_model->get_countries_list();

            $data['cart_data'] = $this->flexi_cart->cart_items();

            $data['main_content'] = 'fe/affiliate_register';
            $this->load->view('fe/includes/template',$data);
        }
    }

    function submit_register() {
        $q = $this->affiliates_model->validate_register_affiliate();

        if($q['res'] == true){
            $affiliate_code = $this->affiliates_model->get_affiliate_code();
            $new_password = $this->affiliates_model->new_password();

            $data = array(
                'affiliate_code' => $affiliate_code,
                'first_name' => $this->input->post('register_first_name'),
                'last_name' => $this->input->post('register_last_name'),
                'email_address' => $this->input->post('register_email_address'),
                'phone_number' => $this->input->post('register_phone_number'),
                'physical_address' => $this->input->post('register_physical_address'),
                'town' => $this->input->post('register_town'),
                'country_id' => $this->input->post('register_country_id'),
                'company_name' => $this->input->post('register_company_name'),
                'website' => $this->input->post('register_website'),
                'password' => md5($new_password),
                'temp_pass' => $new_password,
            );
            $q = $this->affiliates_model->submit_register($data, $affiliate_code);
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

    function account() {
        if($this->session->userdata('bgs_affiliate_login_state')){
            $meta = $this->main_model->get_global_meta();
            $data['meta'] = $meta;

            $data['page_title'] = 'My Affiliate Account';
            $data['cur'] = '';

            $data['store_information'] = $this->main_model->get_store_information();
            $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
            
            $data['default_currency'] = $this->currencies_model->get_default_currency();

            $data['affiliate_account'] = $this->affiliates_model->get_affiliate_account();

            $data['total_clicks'] = $this->affiliates_model->get_account_total_clicks();
            $data['total_referrals'] = $this->affiliates_model->get_account_total_referrals();

            $data['cart_data'] = $this->flexi_cart->cart_items();

            $data['main_content'] = 'fe/affiliate_account';
            $this->load->view('fe/includes/template',$data);
        }else{
            redirect('affiliates/login');
        }           
    }

    function account_referrals() {
        if($this->session->userdata('bgs_affiliate_login_state')){
            $meta = $this->main_model->get_global_meta();
            $data['meta'] = $meta;

            $data['page_title'] = 'My Referrals';
            $data['cur'] = '';

            $data['store_information'] = $this->main_model->get_store_information();
            $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
            
            $data['default_currency'] = $this->currencies_model->get_default_currency();

            $data['affiliate_account'] = $this->affiliates_model->get_affiliate_account();

            $data['affiliate_referrals'] = $this->affiliates_model->get_account_referrals();
            //$data['total_referrals'] = $this->affiliates_model->get_account_total_referrals();

            $data['cart_data'] = $this->flexi_cart->cart_items();

            $data['main_content'] = 'fe/affiliate_referrals';
            $this->load->view('fe/includes/template',$data);
        }else{
            redirect('affiliates/login');
        }           
    }

    function account_clicks() {
        if($this->session->userdata('bgs_affiliate_login_state')){
            $meta = $this->main_model->get_global_meta();
            $data['meta'] = $meta;

            $data['page_title'] = 'My Referral Clicks';
            $data['cur'] = '';

            $data['store_information'] = $this->main_model->get_store_information();
            $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
            
            $data['default_currency'] = $this->currencies_model->get_default_currency();

            $data['affiliate_account'] = $this->affiliates_model->get_affiliate_account();

            $data['affiliate_clicks'] = $this->affiliates_model->get_account_clicks();

            $data['cart_data'] = $this->flexi_cart->cart_items();

            $data['main_content'] = 'fe/affiliate_clicks';
            $this->load->view('fe/includes/template',$data);
        }else{
            redirect('affiliates/login');
        }           
    }

    function change_password() {
        if($this->session->userdata('bgs_affiliate_login_state')){
            $meta = $this->main_model->get_global_meta();
            $data['meta'] = $meta;

            $data['page_title'] = 'Change Affiliate Password';
            $data['cur'] = '';

            $data['store_information'] = $this->main_model->get_store_information();
            $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
            
            $data['default_currency'] = $this->currencies_model->get_default_currency();

            $data['affiliate_account'] = $this->affiliates_model->get_affiliate_account();

            $data['cart_data'] = $this->flexi_cart->cart_items();

            $data['main_content'] = 'fe/affiliate_pswdchange';
            $this->load->view('fe/includes/template',$data);
        }else{
            redirect('affiliates/login');
        }           
    }

    function submit_change_password() {
        if ($this->session->userdata('bgs_affiliate_login_state')){

            $old_password = $this->input->post('old_password');
            $affiliate_id = $this->session->userdata('affiliate_id');

            if ($this->affiliates_model->old_password_valid($old_password, $affiliate_id) == false){
                $resp = array('status' => 'ERR','message' => '<i class="ion-close-circled"></i> The Old Password you have provided is incorrect.');
            }else{

                $data = array(
                    'password' => md5($this->input->post('new_password'))           
                );

                $q = $this->affiliates_model->update_password($data,$affiliate_id);

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

    function refer($affiliate_code) {

        $this->session->unset_userdata('referral_affiliate_code');
        $this->session->unset_userdata('referral_affiliate_click_id');

        $found = false;

        $affiliate = $this->affiliates_model->get_affiliate_by_code($affiliate_code);

        foreach ($affiliate as $row) {
            if ($row->is_verified == 0 || $row->affiliate_status == 0 || $row->affiliate_status == 2 || $row->affiliate_status == 3) {
                $found = false;
            } else {
                $found = true;

                 $ip_address = $_SERVER['REMOTE_ADDR'];
                $ret = $this->geoip->geolocalization($ip_address);

                $country =  $this->geoip->info()->countryName;
                $city =  $this->geoip->info()->cityName;

                $data = array(
                    'affiliate_id' => $row->affiliate_id,
                    'ip_address' => $ip_address,
                    'country' => $country,
                    'city' => $city
                );

                $this->affiliates_model->insert_affiliate_click($data);
                $insert_id = $this->db->insert_id();

                $this->session->set_userdata('referral_affiliate_code', $row->affiliate_code);
                $this->session->set_userdata('referral_affiliate_click_id', $insert_id);
            }
        }
        if ($found == true) {
            redirect('');
        } else {
            redirect('affiliates/refer/invalid');
        }
    }

    function invalid_referral() {
        $meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $data['page_title'] = 'Invalid Referral Link';
        $data['cur'] = '';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        
        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['cart_data'] = $this->flexi_cart->cart_items();

        $data['main_content'] = 'fe/affiliate_invalid_referral';
        $this->load->view('fe/includes/template',$data);

    }


    function activate($affiliate_code) {
        $this->affiliates_model->activate_account($affiliate_code);
        
        if($this->session->userdata('bgs_affiliate_login_state')){
            $this->session->set_flashdata('verify_success', '<b>Congratulations!</b> You have successfully verified your account.');
            redirect('affiliates/account');
        }else{
            $meta = $this->main_model->get_global_meta();
            $data['meta'] = $meta;

            $data['page_title'] = 'Affiliate Account Verified Successfully';
            $data['cur'] = '';

            $data['store_information'] = $this->main_model->get_store_information();
            $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
            $data['home_top_product_categories'] = $this->main_model->get_home_top_product_categories();

            $data['cart_data'] = $this->flexi_cart->cart_items();

            $data['main_content'] = 'fe/affiliate_verification_success';
            $this->load->view('fe/includes/template',$data);
        }        
    }

    function logout() {
        $this->session->unset_userdata('bgs_affiliate_login_state');
        $this->session->unset_userdata('affiliate_id');
        $this->session->unset_userdata('affiliate_email_address');
        $this->session->unset_userdata('affiliate_phone_number');
        $this->session->unset_userdata('affiliate_first_name');
        $this->session->unset_userdata('affiliate_last_name');
        $this->session->unset_userdata('affiliate_code');
        redirect('affiliates/account');
    }

    function terms() {
        $meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $data['page_title'] = 'Affiliate Program Terms and Conditions';
        $data['cur'] = '';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        $data['home_top_product_categories'] = $this->main_model->get_home_top_product_categories();
        
        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['affiliate_terms'] = $this->affiliates_model->get_affiliate_terms();

        $data['cart_data'] = $this->flexi_cart->cart_items();

        $data['main_content'] = 'fe/affiliate_terms';
        $this->load->view('fe/includes/template',$data);
    }




}