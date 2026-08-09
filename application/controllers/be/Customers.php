<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Customers extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/customers_model');
		$this->load->model('be/locations_model');
		$this->load->model('be/currencies_model');
		$this->load->model('be/auth_model');
		$this->load->model('be/support_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('customers_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'Customers';
				$data['cur_sub'] = 'Customers';
				$data['cur_cur_sub'] = '';

				$data['countries'] = $this->locations_model->get_countries_list();
				$data['customer_groups'] = $this->customers_model->get_customer_groups_list();

				$data['sbr_customers_add'] = $this->auth_model->validate_user_access('customers_add', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Customers | ';
				$data['main_content'] = 'be/customers';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function add(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('customers_add', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'Customers';
				$data['cur_sub'] = 'New Customer';
				$data['cur_cur_sub'] = '';

				$data['customer_groups'] = $this->customers_model->get_customer_groups_list();
				$data['customers'] = $this->customers_model->get_customers_list();
				$data['default_currency'] = $this->currencies_model->get_default_currency();
				$data['countries'] = $this->locations_model->get_countries_list();

				$data['sbr_customers_add'] = $this->auth_model->validate_user_access('customers_add', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'New Customer | ';
				$data['main_content'] = 'be/customer_add';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save(){

		$q = $this->customers_model->customer_exists();

		if($q['res'] == true){

			$loyalty_enrolled = $this->input->post('loyalty_enrolled');
			$loyalty_number = '';
			if($loyalty_enrolled == 'on'){
				$loyalty_number = $this->customers_model->new_loyalty_number();
				$loyalty_enrolled = 1;
			}else{
				$loyalty_enrolled = 0;
			}

			$data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'phone_number' => $this->input->post('phone_number'),
				'email_address' => $this->input->post('email_address'),
				'gender' => $this->input->post('gender'),
				'birth_date' => $this->input->post('birth_date'),
				'customer_group_id' => $this->input->post('customer_group_id'),
				'billing_first_name' => $this->input->post('billing_first_name'),
				'billing_last_name' => $this->input->post('billing_last_name'),		
				'billing_email_address' => $this->input->post('billing_email_address'),
				'billing_phone_number' => $this->input->post('billing_phone_number'),
				'billing_street_address' => $this->input->post('billing_street_address'),
				'billing_country_id' => $this->input->post('billing_country_id'),
				'billing_region_id' => $this->input->post('billing_region_id'),
				'billing_postal_code' => $this->input->post('billing_postal_code'),
				'shipping_first_name' => $this->input->post('shipping_first_name'),
				'shipping_last_name' => $this->input->post('shipping_last_name'),		
				'shipping_email_address' => $this->input->post('shipping_email_address'),
				'shipping_phone_number' => $this->input->post('shipping_phone_number'),
				'shipping_street_address' => $this->input->post('shipping_street_address'),
				'shipping_country_id' => $this->input->post('shipping_country_id'),
				'shipping_region_id' => $this->input->post('shipping_region_id'),
				'shipping_postal_code' => $this->input->post('shipping_postal_code'),
				'customer_code' => $this->input->post('customer_code'),
				'credit_limit' => $this->input->post('credit_limit'),
				'opening_balance' => $this->input->post('opening_balance'),
				'loyalty_enrolled' => $loyalty_enrolled,
				'loyalty_number' => $loyalty_number,
				'loyalty_enrollment_date' => $this->input->post('loyalty_enrollment_date'),
				'reference_customer_id' => $this->input->post('reference_customer_id'),
				'password' => bethany_hash($this->input->post('password')),
				'is_active' => $this->input->post('is_active'),
				'sort_key' => $this->input->post('sort_key'),
				'created_on' => date("Y-m-d H:i:s", time())
			);	
			$q = $this->customers_model->save($data);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}

		echo json_encode($resp);
	}
	function load_js(){
		$data['customers'] = $this->customers_model->get_customers_list();
		$data['sbr_customers_edit'] = $this->auth_model->validate_user_access('customers_edit', $this->session->userdata('system_user_id'));
		$data['sbr_customers_delete'] = $this->auth_model->validate_user_access('customers_edit', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/customers',$data);
	}
	function edit($customer_id){
		if($this->session->userdata('bgs_be_active')) {
			// if ($this->auth_model->validate_user_access('customers_edit', $this->session->userdata('system_user_id')) == false){
			// 	redirect('be/auth/access_denied');
			// } else {
				$data['cur'] = 'Customers';
				$data['cur_sub'] = 'Customers';
				$data['cur_cur_sub'] = '';

				$data['customer_groups'] = $this->customers_model->get_customer_groups_list();
				$data['customers'] = $this->customers_model->get_customers_list();
				$data['default_currency'] = $this->currencies_model->get_default_currency();
				$data['countries'] = $this->locations_model->get_countries_list();

				$data['customer'] = $this->customers_model->get_customer($customer_id);

				$data['sbr_customers_edit'] = $this->auth_model->validate_user_access('customers_edit', $this->session->userdata('system_user_id'));

				$this->support_model->read_notification('Customer Account Creation', $customer_id);

				$data['page_title'] = 'Edit Customer | ';
				$data['main_content'] = 'be/customer_add';
				$this->load->view('be/includes/template',$data);
			//}
        } 
		else {
            redirect('be/auth');
		}
	}
	function update(){

		$customer_id = $this->input->post('customer_id');
		$q = $this->customers_model->customer_update_exists();

		if($q['res'] == true){

			$loyalty_enrolled = $this->input->post('loyalty_enrolled');
			$loyalty_number = '';
			if($loyalty_enrolled == 'on'){
				$loyalty_number = $this->customers_model->get_edit_new_loyalty_number($customer_id);
				$loyalty_enrolled = 1;
			}else{
				$loyalty_enrolled = 0;
				$loyalty_number = $this->customers_model->get_loyalty_number($customer_id);
			}

			$data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'phone_number' => $this->input->post('phone_number'),
				'email_address' => $this->input->post('email_address'),
				'gender' => $this->input->post('gender'),
				'birth_date' => $this->input->post('birth_date'),
				'customer_group_id' => $this->input->post('customer_group_id'),
				'billing_first_name' => $this->input->post('billing_first_name'),
				'billing_last_name' => $this->input->post('billing_last_name'),		
				'billing_email_address' => $this->input->post('billing_email_address'),
				'billing_phone_number' => $this->input->post('billing_phone_number'),
				'billing_street_address' => $this->input->post('billing_street_address'),
				'billing_country_id' => $this->input->post('billing_country_id'),
				'billing_region_id' => $this->input->post('billing_region_id'),
				'billing_postal_code' => $this->input->post('billing_postal_code'),
				'shipping_first_name' => $this->input->post('shipping_first_name'),
				'shipping_last_name' => $this->input->post('shipping_last_name'),		
				'shipping_email_address' => $this->input->post('shipping_email_address'),
				'shipping_phone_number' => $this->input->post('shipping_phone_number'),
				'shipping_street_address' => $this->input->post('shipping_street_address'),
				'shipping_country_id' => $this->input->post('shipping_country_id'),
				'shipping_region_id' => $this->input->post('shipping_region_id'),
				'shipping_postal_code' => $this->input->post('shipping_postal_code'),
				'customer_code' => $this->input->post('customer_code'),
				'credit_limit' => $this->input->post('credit_limit'),
				'opening_balance' => $this->input->post('opening_balance'),
				'loyalty_enrolled' => $loyalty_enrolled,
				'loyalty_number' => $loyalty_number,
				'loyalty_enrollment_date' => $this->input->post('loyalty_enrollment_date'),
				'reference_customer_id' => $this->input->post('reference_customer_id'),
				'is_active' => $this->input->post('is_active'),
				'sort_key' => $this->input->post('sort_key'),
			);	
			$q = $this->customers_model->update($data,$customer_id);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
		echo json_encode($resp);

	}
	function delete($customer_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->customers_model->delete($customer_id);
			if($q['res'] == TRUE){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);			
			}else{					
				$resp = array('status' => 'ERR','message' => $q['dt']);			
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);

	}
	function delete_bulk() {
		if($this->session->userdata('bgs_be_active')){
			$ids = $this->input->post('ids');
			$q = $this->customers_model->delete_bulk($ids);
			if($q['res'] == TRUE){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);			
			}else{					
				$resp = array('status' => 'ERR','message' => $q['dt']);			
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);
	}
	function delete_cover_image($customer_id) {
		if($this->session->userdata('bgs_be_active')){
			$q = $this->customers_model->delete_cover_image($customer_id);
			if($q['res'] == TRUE){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);			
			}else{					
				$resp = array('status' => 'ERR','message' => $q['dt']);			
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);
	}


	//CUSTOMER GROUPS
	function groups(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('customer_groups_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['page_title'] = 'Customer Groups | ';

				$data['cur'] = 'Customers';
				$data['cur_sub'] = 'Customer Groups';
				$data['cur_cur_sub'] = '';

				$data['sbr_customer_groups_add'] = $this->auth_model->validate_user_access('customer_groups_add', $this->session->userdata('system_user_id'));
				$data['sbr_customer_groups_edit'] = $this->auth_model->validate_user_access('customer_groups_edit', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/customer_groups';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_customer_group(){
		$data = array(
			'customer_group_name' => $this->input->post('customer_group_name'),
			'customer_group_description' => $this->input->post('customer_group_description'),
			'sort_key' => $this->input->post('sort_key'),
			'is_active' => $this->input->post('is_active'),
			'created_on' => date("Y-m-d H:i:s", time())
		);	
		$customer_group_name = $this->input->post('customer_group_name');
		if($this->customers_model->customer_group_exists($customer_group_name) == false){
			$q = $this->customers_model->save_customer_group($data);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This Customer Group has already been defined');
		}
			
		echo json_encode($resp);

	}
	function loadjs_customer_groups(){
		$data['customer_groups'] = $this->customers_model->get_customer_groups_list();
		$data['sbr_customer_groups_delete'] = $this->auth_model->validate_user_access('customer_groups_delete', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/customer_groups',$data);
	}
	function get_customer_group($customer_group_id){
		$customer_group = $this->customers_model->get_customer_group($customer_group_id);
		echo json_encode($customer_group);
	}
	function get_customer_group2($customer_group_id){
		$customer_group = $this->customers_model->get_customer_group2($customer_group_id);
		echo json_encode($customer_group);
	}
	function update_customer_group(){
		if($this->session->userdata('bgs_be_active')){
			$customer_group_id = $this->input->post('customer_group_id');
			$customer_group_name = $this->input->post('customer_group_name');

			$data = array(
				'customer_group_name' => $this->input->post('customer_group_name'),
				'customer_group_description' => $this->input->post('customer_group_description'),
				'sort_key' => $this->input->post('sort_key'),
				'is_active' => $this->input->post('is_active')
			);	
			if($this->customers_model->customer_group_update_exists($customer_group_id,$customer_group_name) == false){
				$q = $this->customers_model->update_customer_group($data,$customer_group_id);
				if ($q['res'] == true){
					$resp = array('status' => 'SUCCESS','message' => $q['dt']);
				}else{
					$resp = array('status' => 'ERR','message' => $q['dt']);
				}
			}else{
				$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This Customer Group has already been defined');
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);

	}
	function delete_customer_group($customer_group_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->customers_model->delete_customer_group($customer_group_id);
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
	function delete_bulk_customer_groups() {
		if($this->session->userdata('bgs_be_active')){
			$ids = $this->input->post('ids');
			$q = $this->customers_model->delete_bulk_customer_groups($ids);
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


}