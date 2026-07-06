<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->library('excel');
		
		$this->load->model('be/store_information_model');
		$this->load->model('be/outlets_model');
		$this->load->model('be/locations_model');
		$this->load->model('be/currencies_model');
		$this->load->model('be/banks_model');
		$this->load->model('be/tax_rates_model');
		$this->load->model('be/credit_terms_model');
		$this->load->model('be/mpesa_settings_model');
		$this->load->model('be/pesapal_settings_model');
		$this->load->model('be/email_accounts_model');
		$this->load->model('be/email_notification_settings_model');
		$this->load->model('be/email_templates_model');
		$this->load->model('be/sms_settings_model');
		$this->load->model('be/prefixes_model');
		$this->load->model('be/void_reasons_model');
		$this->load->model('be/bitly_settings_model');
		$this->load->model('be/sale_comments_model');
		$this->load->model('be/lnm_reconciliation_model');
		$this->load->model('be/auth_model');
			
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			$data['cur'] = 'Settings';
			$data['cur_sub'] = 'Global Settings';
			$data['cur_cur_sub'] = '';

			$data['page_title'] = 'Global Settings | ';
			$data['main_content'] = 'be/global_settings';
			$this->load->view('be/includes/settings_template',$data);
        } 
		else {
            redirect('be/auth');
		}
		//redirect('be/settings/store_information');
	}


	//STORE INFORMATION
	function store_information(){
		if($this->session->userdata('bgs_be_active')) {

			if ($this->auth_model->validate_user_access('store_information_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['store_information'] = $this->store_information_model->get_store_information();
				$data['store_information_exists'] = $this->store_information_model->store_information_exists();

				$data['cur'] = 'Settings';
				$data['cur_sub'] = 'Store Information';
				$data['cur_cur_sub'] = '';

				$data['sbr_store_information_view'] = $this->auth_model->validate_user_access('store_information_view', $this->session->userdata('system_user_id'));
				$data['sbr_store_information_edit'] = $this->auth_model->validate_user_access('store_information_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Store Information | ';
				$data['main_content'] = 'be/store_information';
				$this->load->view('be/includes/settings_template',$data);
			}


        } 
		else {
            redirect('be/auth');
		}
	}
	function save_store_information(){
		$data = array(
			'store_name' => $this->input->post('store_name'),
			'email_address' => $this->input->post('email_address'),			
			'phone_number' => $this->input->post('phone_number'),
			'mobile_number' => $this->input->post('mobile_number'),
			'postal_address' => $this->input->post('postal_address'),
			'postal_code' => $this->input->post('postal_code'),
			'physical_address' => $this->input->post('physical_address'),
			'website' => $this->input->post('website'),
			'pin_number' => $this->input->post('pin_number'),
			'registration_number' => $this->input->post('registration_number'),
			'opening_hours' => $this->input->post('opening_hours'),
			'sm_facebook' => $this->input->post('sm_facebook'),
			'sm_twitter' => $this->input->post('sm_twitter'),
			'sm_linkedin' => $this->input->post('sm_linkedin'),
			'sm_youtube' => $this->input->post('sm_youtube'),
			'sm_instagram' => $this->input->post('sm_instagram'),
			'created_on' => date("Y-m-d H:i:s", time())
		);	
		$q = $this->store_information_model->save($data);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);
	}
	function set_store_logo(){

		if ($this->store_information_model->store_information_exists() == true){
			$q = $this->store_information_model->upload_contact_logo();
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}			
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> Please set Store Information first before setting Logo.');
		}
		echo json_encode($resp);
	}

	//OUTLETS
	function outlets(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('outlets_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {			
				$data['page_title'] = 'Outlets | ';

				$data['cur'] = 'Settings';
				$data['cur_sub'] = 'Outlets';
				$data['cur_cur_sub'] = '';

				$data['sbr_outlets_view'] = $this->auth_model->validate_user_access('outlets_view', $this->session->userdata('system_user_id'));
				$data['sbr_outlets_add'] = $this->auth_model->validate_user_access('outlets_add', $this->session->userdata('system_user_id'));
				$data['sbr_outlets_edit'] = $this->auth_model->validate_user_access('outlets_edit', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/outlets';
				$this->load->view('be/includes/settings_template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_outlet(){
		$data = array(
			'outlet_name' => $this->input->post('outlet_name'),
			'outlet_physical_location' => $this->input->post('outlet_physical_location'),
			'outlet_description' => $this->input->post('outlet_description'),
			'outlet_contact_person' => $this->input->post('outlet_contact_person'),
			'outlet_phone_number' => $this->input->post('outlet_phone_number'),
			'outlet_email_address' => $this->input->post('outlet_email_address'),
			'sort_key' => $this->input->post('sort_key'),
			'is_active' => $this->input->post('is_active'),
			'created_on' => date("Y-m-d H:i:s", time())

		);	
		$outlet_name = $this->input->post('outlet_name');
		if($this->outlets_model->outlet_exists($outlet_name) == false){
			$q = $this->outlets_model->save($data);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This Outlet has already been defined.');
		}
			
		echo json_encode($resp);
	}
	function loadjs_outlets(){
		$data['outlets'] = $this->outlets_model->get_outlets_list();
		$data['sbr_outlets_delete'] = $this->auth_model->validate_user_access('outlets_delete', $this->session->userdata('system_user_id'));
		$this->load->view('be/jsloads/outlets',$data);

	}
	function get_outlet2($outlet_id){
		$outlet = $this->outlets_model->get_outlet2($outlet_id);
		echo json_encode($outlet);
	}
	function update_outlet(){
		if($this->session->userdata('bgs_be_active')){
			$outlet_id = $this->input->post('outlet_id');
			$outlet_name = $this->input->post('outlet_name');
			$data = array(
				'outlet_name' => $this->input->post('outlet_name'),
				'outlet_physical_location' => $this->input->post('outlet_physical_location'),
				'outlet_description' => $this->input->post('outlet_description'),
				'outlet_contact_person' => $this->input->post('outlet_contact_person'),
				'outlet_phone_number' => $this->input->post('outlet_phone_number'),
				'outlet_email_address' => $this->input->post('outlet_email_address'),
				'sort_key' => $this->input->post('sort_key'),
				'is_active' => $this->input->post('is_active')
			);	
			if($this->outlets_model->outlet_update_exists($outlet_id,$outlet_name) == false){
				$q = $this->outlets_model->update($data,$outlet_id);
				if ($q['res'] == true){
					$resp = array('status' => 'SUCCESS','message' => $q['dt']);
				}else{
					$resp = array('status' => 'ERR','message' => $q['dt']);
				}
			}else{
				$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This Outlet has already been defined.');
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);
	}
	function delete_outlet($outlet_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->outlets_model->delete($outlet_id);
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


	//COUNTRIES
	function countries(){
		if($this->session->userdata('bgs_be_active')) {

			if ($this->auth_model->validate_user_access('countries_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['page_title'] = 'Countries | ';

				$data['cur'] = 'Settings';
				$data['cur_sub'] = 'Countries';
				$data['cur_cur_sub'] = '';

				$data['sbr_countries_view'] = $this->auth_model->validate_user_access('countries_view', $this->session->userdata('system_user_id'));
				$data['sbr_countries_add'] = $this->auth_model->validate_user_access('countries_add', $this->session->userdata('system_user_id'));
				$data['sbr_countries_edit'] = $this->auth_model->validate_user_access('countries_edit', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/countries';
				$this->load->view('be/includes/settings_template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_country(){
		$country_sku = $this->locations_model->get_country_sku();
		$country_reference_id = url_title($this->input->post('country_name'),'-',TRUE) . '-' . strtolower($country_sku);

		$data = array(
			'country_reference_id' => $country_reference_id,
			'country_sku_code' => $country_sku,			
			'country_name' => $this->input->post('country_name'),
			'country_abbreviation' => $this->input->post('country_abbreviation'),
			'nationality' => $this->input->post('nationality'),
			'country_code' => $this->input->post('country_code'),
			'created_on' => date("Y-m-d H:i:s", time())
		);	
		$country_name = $this->input->post('country_name');
		if($this->locations_model->country_exists($country_name) == false){
			$q = $this->locations_model->save_country($data);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This Country has already been defined');
		}
			
		echo json_encode($resp);

	}
	function loadjs_countries(){
		$data['countries'] = $this->locations_model->get_countries_list();
		$data['sbr_countries_delete'] = $this->auth_model->validate_user_access('countries_delete', $this->session->userdata('system_user_id'));
		$data['sbr_countries_manage'] = $this->auth_model->validate_user_access('countries_manage', $this->session->userdata('system_user_id'));
		$this->load->view('be/jsloads/countries',$data);
	}
	function get_country($country_id){
		$country = $this->locations_model->get_country($country_id);
		echo json_encode($country);
	}
	function get_country2($country_id){
		$country = $this->locations_model->get_country2($country_id);
		echo json_encode($country);
	}
	function update_country(){
		if($this->session->userdata('bgs_be_active')){
			$country_id = $this->input->post('country_id');
			$country_name = $this->input->post('country_name');

			$country_sku = $this->locations_model->get_country_sku_code($country_id);		
			$country_reference_id = url_title($this->input->post('country_name'),'-',TRUE) . '-' . strtolower($country_sku);

			$data = array(
				'country_reference_id'		=> $country_reference_id,
	            'country_sku_code'			=> $country_sku,			
				'country_name' => $this->input->post('country_name'),
				'country_abbreviation' => $this->input->post('country_abbreviation'),
				'nationality' => $this->input->post('nationality'),
				'country_code' => $this->input->post('country_code')
			);	
			if($this->locations_model->country_update_exists($country_id,$country_name) == false){
				$q = $this->locations_model->update_country($data,$country_id);
				if ($q['res'] == true){
					$resp = array('status' => 'SUCCESS','message' => $q['dt']);
				}else{
					$resp = array('status' => 'ERR','message' => $q['dt']);
				}
			}else{
				$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This Country has already been defined');
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);

	}
	function delete_country($country_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->locations_model->delete_country($country_id);
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
	function delete_bulk_countries() {
		if($this->session->userdata('bgs_be_active')){
			$ids = $this->input->post('ids');
			$q = $this->locations_model->delete_bulk_countries($ids);
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

	//REGIONS
	function regions($country_id) {
		if($this->session->userdata('bgs_be_active')) {

			if ($this->auth_model->validate_user_access('regions_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'Settings';
				$data['cur_sub'] = 'Countries';
				$data['cur_cur_sub'] = '';

				$data['country_id'] = $country_id;
				$data['country'] = $this->locations_model->get_country($country_id);

				$data['sbr_regions_view'] = $this->auth_model->validate_user_access('regions_view', $this->session->userdata('system_user_id'));
				$data['sbr_regions_add'] = $this->auth_model->validate_user_access('regions_add', $this->session->userdata('system_user_id'));
				$data['sbr_regions_edit'] = $this->auth_model->validate_user_access('regions_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Regions | ';
				$data['main_content'] = 'be/regions';
				$this->load->view('be/includes/settings_template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_region(){
		$region_sku = $this->locations_model->get_region_sku();
		$region_reference_id = url_title($this->input->post('region_name'),'-',TRUE) . '-' . strtolower($region_sku);

		$data = array(
			'region_reference_id' => $region_reference_id,
			'region_sku_code' => $region_sku,			
			'region_name' => $this->input->post('region_name'),
			'sort_key' => $this->input->post('sort_key'),
			'country_id' => $this->input->post('country_id'),
			'created_on' => date("Y-m-d H:i:s", time())
		);	
		$region_name = $this->input->post('region_name');
		$country_id = $this->input->post('country_id');
		if($this->locations_model->region_exists($country_id, $region_name) == false){
			$q = $this->locations_model->save_region($data);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This region has already been defined');
		}
			
		echo json_encode($resp);

	}
	function loadjs_regions($country_id){
		$data['regions'] = $this->locations_model->get_regions_list($country_id);
		$data['sbr_regions_delete'] = $this->auth_model->validate_user_access('regions_delete', $this->session->userdata('system_user_id'));
		$this->load->view('be/jsloads/regions',$data);
	}
	function get_region($region_id){
		$region = $this->locations_model->get_region($region_id);
		echo json_encode($region);
	}
	function get_region2($region_id){
		$region = $this->locations_model->get_region2($region_id);
		echo json_encode($region);
	}
	function update_region(){
		if($this->session->userdata('bgs_be_active')){
			$region_id = $this->input->post('region_id');
			$region_name = $this->input->post('region_name');
			$country_id = $this->input->post('country_id');

			$region_sku = $this->locations_model->get_region_sku_code($region_id);		
			$region_reference_id = url_title($this->input->post('region_name'),'-',TRUE) . '-' . strtolower($region_sku);

			$data = array(
				'region_reference_id'		=> $region_reference_id,
	            'region_sku_code'			=> $region_sku,			
				'region_name' => $this->input->post('region_name'),
				'sort_key' => $this->input->post('sort_key'),
				'country_id' => $this->input->post('country_id')
			);	
			if($this->locations_model->region_update_exists($country_id,$region_id,$region_name) == false){
				$q = $this->locations_model->update_region($data,$region_id);
				if ($q['res'] == true){
					$resp = array('status' => 'SUCCESS','message' => $q['dt']);
				}else{
					$resp = array('status' => 'ERR','message' => $q['dt']);
				}
			}else{
				$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This region has already been defined');
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);
	}
	function delete_region($region_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->locations_model->delete_region($region_id);
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
	function delete_bulk_regions() {
		if($this->session->userdata('bgs_be_active')){
			$ids = $this->input->post('ids');
			$q = $this->locations_model->delete_bulk_regions($ids);
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


	//SHIPPING ZONES
	function shipping_zones(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('shipping_zones_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['countries'] = $this->locations_model->get_nested_countries();
				$data['page_title'] = 'Shipping Zones | ';

				$data['cur'] = 'Settings';
				$data['cur_sub'] = 'Shipping Zones';
				$data['cur_cur_sub'] = '';

				$data['sbr_shipping_zones_view'] = $this->auth_model->validate_user_access('shipping_zones_view', $this->session->userdata('system_user_id'));
				$data['sbr_shipping_zones_add'] = $this->auth_model->validate_user_access('shipping_zones_add', $this->session->userdata('system_user_id'));
				$data['sbr_shipping_zones_edit'] = $this->auth_model->validate_user_access('shipping_zones_edit', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/shipping_zones';
				$this->load->view('be/includes/settings_template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_shipping_zone(){
		$shipping_zone_sku = $this->locations_model->get_shipping_zone_sku();
		$shipping_zone_reference_id = url_title($this->input->post('shipping_zone_name'),'-',TRUE) . '-' . strtolower($shipping_zone_sku);

		$region_id = $this->input->post('region_id');
		$country_id = $this->locations_model->get_country_id_by_region_id($region_id);

		$shipping_fee = 0;
		if ($this->input->post('shipping_method') != '' && $this->input->post('shipping_method') != null && $this->input->post('shipping_method') != 0) {
			$shipping_fee = $this->input->post('shipping_fee');
		}

		$data = array(
			'shipping_zone_reference_id' => $shipping_zone_reference_id,
			'shipping_zone_sku_code' => $shipping_zone_sku,			
			'shipping_zone_name' => $this->input->post('shipping_zone_name'),
			'country_id' => $country_id,
			'region_id' => $this->input->post('region_id'),
			'shipping_method' => $this->input->post('shipping_method'),
			'shipping_fee' => $shipping_fee,
			'sort_key' => $this->input->post('sort_key'),
			'is_active' => $this->input->post('is_active'),
			'created_on' => date("Y-m-d H:i:s", time())
		);	
		$q = $this->locations_model->save_shipping_zone($data);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);

	}
	function loadjs_shipping_zones(){
		$data['shipping_zones'] = $this->locations_model->get_shipping_zones_list();
		$data['sbr_shipping_zones_delete'] = $this->auth_model->validate_user_access('shipping_zones_delete', $this->session->userdata('system_user_id'));
		$this->load->view('be/jsloads/shipping_zones',$data);
	}
	function get_shipping_zone($shipping_zone_id){
		$shipping_zone = $this->locations_model->get_shipping_zone($shipping_zone_id);
		echo json_encode($shipping_zone);
	}
	function get_shipping_zone2($shipping_zone_id){
		$shipping_zone = $this->locations_model->get_shipping_zone2($shipping_zone_id);
		echo json_encode($shipping_zone);
	}
	function update_shipping_zone(){
		if($this->session->userdata('bgs_be_active')){
			$shipping_zone_id = $this->input->post('shipping_zone_id');
			$shipping_zone_name = $this->input->post('shipping_zone_name');

			$shipping_zone_sku = $this->locations_model->get_shipping_zone_sku_code($shipping_zone_id);		
			$shipping_zone_reference_id = url_title($this->input->post('shipping_zone_name'),'-',TRUE) . '-' . strtolower($shipping_zone_sku);

			$region_id = $this->input->post('region_id');
			$country_id = $this->locations_model->get_country_id_by_region_id($region_id);

			$shipping_fee = 0;
			if ($this->input->post('shipping_method') != '' && $this->input->post('shipping_method') != null && $this->input->post('shipping_method') != 0) {
				$shipping_fee = $this->input->post('shipping_fee');
			}

			$data = array(
				'shipping_zone_reference_id'		=> $shipping_zone_reference_id,
	            'shipping_zone_sku_code'			=> $shipping_zone_sku,			
				'shipping_zone_name' => $this->input->post('shipping_zone_name'),
				'country_id' => $country_id,
				'region_id' => $this->input->post('region_id'),
				'shipping_method' => $this->input->post('shipping_method'),
				'shipping_fee' => $shipping_fee,
				'sort_key' => $this->input->post('sort_key'),
				'is_active' => $this->input->post('is_active'),
			);	
			$q = $this->locations_model->update_shipping_zone($data,$shipping_zone_id);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);

	}
	function delete_shipping_zone($shipping_zone_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->locations_model->delete_shipping_zone($shipping_zone_id);
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
	function delete_bulk_shipping_zones() {
		if($this->session->userdata('bgs_be_active')){
			$ids = $this->input->post('ids');
			$q = $this->locations_model->delete_bulk_shipping_zones($ids);
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
	function get_shipping_zones_by_region($region_id) {
		$regions = $this->locations_model->get_shipping_zones_by_region_id($region_id);
		echo json_encode($regions);
	}

	function get_shipping_zones() {
		$regions = $this->locations_model->get_shipping_zones_list();
		echo json_encode($regions);
	}


	//PICKUP LOCATIONS
	function pickup_locations(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('pickup_locations_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['countries'] = $this->locations_model->get_nested_countries();
				$data['page_title'] = 'Pickup Locations | ';

				$data['cur'] = 'Settings';
				$data['cur_sub'] = 'Pickup Locations';
				$data['cur_cur_sub'] = '';

				$data['sbr_pickup_locations_view'] = $this->auth_model->validate_user_access('pickup_locations_view', $this->session->userdata('system_user_id'));
				$data['sbr_pickup_locations_add'] = $this->auth_model->validate_user_access('pickup_locations_add', $this->session->userdata('system_user_id'));
				$data['sbr_pickup_locations_edit'] = $this->auth_model->validate_user_access('pickup_locations_edit', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/pickup_locations';
				$this->load->view('be/includes/settings_template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_pickup_location(){
		$pickup_location_sku = $this->locations_model->get_pickup_location_sku();
		$pickup_location_reference_id = url_title($this->input->post('pickup_location_name'),'-',TRUE) . '-' . strtolower($pickup_location_sku);

		$region_id = $this->input->post('region_id');
		$country_id = $this->locations_model->get_country_id_by_region_id($region_id);

		$data = array(
			'pickup_location_reference_id' => $pickup_location_reference_id,
			'pickup_location_sku_code' => $pickup_location_sku,			
			'pickup_location_name' => $this->input->post('pickup_location_name'),
			'pickup_location_address' => $this->input->post('pickup_location_address'),
			'close_to' => $this->input->post('close_to'),
			'country_id' => $country_id,
			'region_id' => $this->input->post('region_id'),
			'shipping_fee' => $this->input->post('shipping_fee'),
			'pickup_period' => $this->input->post('pickup_period'),
			'opening_hours' => $this->input->post('opening_hours'),
			'sort_key' => $this->input->post('sort_key'),
			'is_active' => $this->input->post('is_active'),
			'created_on' => date("Y-m-d H:i:s", time())
		);	
		$q = $this->locations_model->save_pickup_location($data);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);

	}
	function loadjs_pickup_locations(){
		$data['pickup_locations'] = $this->locations_model->get_pickup_locations_list();
		$data['sbr_pickup_locations_delete'] = $this->auth_model->validate_user_access('pickup_locations_delete', $this->session->userdata('system_user_id'));
		$this->load->view('be/jsloads/pickup_locations',$data);
	}
	function get_pickup_location($pickup_location_id){
		$pickup_location = $this->locations_model->get_pickup_location($pickup_location_id);
		echo json_encode($pickup_location);
	}
	function get_pickup_location2($pickup_location_id){
		$pickup_location = $this->locations_model->get_pickup_location2($pickup_location_id);
		echo json_encode($pickup_location);
	}
	function update_pickup_location(){
		if($this->session->userdata('bgs_be_active')){
			$pickup_location_id = $this->input->post('pickup_location_id');
			$pickup_location_name = $this->input->post('pickup_location_name');

			$pickup_location_sku = $this->locations_model->get_pickup_location_sku_code($pickup_location_id);		
			$pickup_location_reference_id = url_title($this->input->post('pickup_location_name'),'-',TRUE) . '-' . strtolower($pickup_location_sku);

			$region_id = $this->input->post('region_id');
			$country_id = $this->locations_model->get_country_id_by_region_id($region_id);

			$data = array(
				'pickup_location_reference_id'		=> $pickup_location_reference_id,
	            'pickup_location_sku_code'			=> $pickup_location_sku,			
				'pickup_location_name' => $this->input->post('pickup_location_name'),
				'pickup_location_address' => $this->input->post('pickup_location_address'),
				'close_to' => $this->input->post('close_to'),
				'country_id' => $country_id,
				'region_id' => $this->input->post('region_id'),
				'shipping_fee' => $this->input->post('shipping_fee'),
				'pickup_period' => $this->input->post('pickup_period'),
				'opening_hours' => $this->input->post('opening_hours'),
				'sort_key' => $this->input->post('sort_key'),
				'is_active' => $this->input->post('is_active')
			);	
			$q = $this->locations_model->update_pickup_location($data,$pickup_location_id);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);

	}
	function delete_pickup_location($pickup_location_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->locations_model->delete_pickup_location($pickup_location_id);
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
	function delete_bulk_pickup_locations() {
		if($this->session->userdata('bgs_be_active')){
			$ids = $this->input->post('ids');
			$q = $this->locations_model->delete_bulk_pickup_locations($ids);
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
	function get_pickup_locations_by_region($region_id) {
		$pickup_locations = $this->locations_model->get_pickup_locations_by_region_id($region_id);
		echo json_encode($pickup_locations);
	}
	function get_pickup_locations() {
		$pickup_locations = $this->locations_model->get_pickup_locations_list();
		echo json_encode($pickup_locations);
	}	


	//CURRENCIES
	function currencies(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('currencies_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['countries'] = $this->locations_model->get_countries_list();
				$data['page_title'] = 'Currencies | ';

				$data['cur'] = 'Settings';
				$data['cur_sub'] = 'Currencies';
				$data['cur_cur_sub'] = '';

				$data['sbr_currencies_view'] = $this->auth_model->validate_user_access('currencies_view', $this->session->userdata('system_user_id'));
				$data['sbr_currencies_add'] = $this->auth_model->validate_user_access('currencies_add', $this->session->userdata('system_user_id'));
				$data['sbr_currencies_edit'] = $this->auth_model->validate_user_access('currencies_edit', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/currencies';
				$this->load->view('be/includes/settings_template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_currency(){	

		$q = $this->currencies_model->currency_exists();

		if($q['res'] == false){

			$default_currency = $this->input->post('default_currency');
			
			if($default_currency == 'on'){
				$exchange_rate = 1;
			} else {
				$exchange_rate = $this->input->post('exchange_rate');
			}

			$data = array(
				'country_id' => $this->input->post('country_id'),
				'currency_name' => $this->input->post('currency_name'),
				'currency_symbol' => $this->input->post('currency_symbol'),
				'exchange_rate' => $exchange_rate,
				'sort_key' => $this->input->post('sort_key'),
				'is_active' => $this->input->post('is_active'),
				'created_on' => date("Y-m-d H:i:s", time())
			);	
			$q = $this->currencies_model->save($data);
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
	function loadjs_currencies(){
		$data['currencies'] = $this->currencies_model->get_currencies_list();
		$data['sbr_currencies_delete'] = $this->auth_model->validate_user_access('currencies_delete', $this->session->userdata('system_user_id'));
		$this->load->view('be/jsloads/currencies',$data);

	}
	function get_currency2($currency_id){
		$currency = $this->currencies_model->get_currency2($currency_id);
		echo json_encode($currency);
	}
	function update_currency(){
		if($this->session->userdata('bgs_be_active')){
			$currency_id = $this->input->post('currency_id');
			
			$q = $this->currencies_model->currency_update_exists($currency_id);

			if($q['res'] == false){

				$default_currency = $this->input->post('default_currency');
				
				if($default_currency == 'on'){
					$exchange_rate = 1;
				} else {
					$exchange_rate = $this->input->post('exchange_rate');
				}

				$data = array(
					'country_id' => $this->input->post('country_id'),
					'currency_name' => $this->input->post('currency_name'),
					'currency_symbol' => $this->input->post('currency_symbol'),
					'exchange_rate' => $exchange_rate,
					'sort_key' => $this->input->post('sort_key'),
					'is_active' => $this->input->post('is_active')
				);	

				$q = $this->currencies_model->update($data,$currency_id);
				if ($q['res'] == true){
					$resp = array('status' => 'SUCCESS','message' => $q['dt']);
				}else{
					$resp = array('status' => 'ERR','message' => $q['dt']);
				}
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);
	}
	function delete_currency($currency_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->currencies_model->delete($currency_id);
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
	function delete_bulk_currencies() {
		if($this->session->userdata('bgs_be_active')){
			$ids = $this->input->post('ids');
			$q = $this->currencies_model->delete_bulk_currencies($ids);
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


	//BANKS
	function banks(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('banks_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {			
				$data['page_title'] = 'Banks | ';

				$data['cur'] = 'Settings';
				$data['cur_sub'] = 'Banks';
				$data['cur_cur_sub'] = '';

				$data['sbr_banks_view'] = $this->auth_model->validate_user_access('banks_view', $this->session->userdata('system_user_id'));
				$data['sbr_banks_add'] = $this->auth_model->validate_user_access('banks_add', $this->session->userdata('system_user_id'));
				$data['sbr_banks_edit'] = $this->auth_model->validate_user_access('banks_edit', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/banks';
				$this->load->view('be/includes/settings_template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_bank(){
		$data = array(
			'bank_name' => $this->input->post('bank_name'),
			'bank_code' => $this->input->post('bank_code'),
			'bank_comment' => $this->input->post('bank_comment'),
			'sort_key' => $this->input->post('sort_key'),
			'is_active' => $this->input->post('is_active'),
			'created_on' => date("Y-m-d H:i:s", time())
		);	
		$bank_name = $this->input->post('bank_name');
		if($this->banks_model->bank_exists($bank_name) == false){
			$q = $this->banks_model->save_bank($data);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This Bank has already been defined');
		}
			
		echo json_encode($resp);

	}
	function loadjs_banks(){
		$data['banks'] = $this->banks_model->get_banks_list();
		$data['sbr_banks_delete'] = $this->auth_model->validate_user_access('banks_delete', $this->session->userdata('system_user_id'));
		$data['sbr_banks_manage'] = $this->auth_model->validate_user_access('banks_manage', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/banks',$data);
	}
	function get_bank($bank_id){
		$bank = $this->banks_model->get_bank($bank_id);
		echo json_encode($bank);
	}
	function get_bank2($bank_id){
		$bank = $this->banks_model->get_bank2($bank_id);
		echo json_encode($bank);
	}
	function update_bank(){
		if($this->session->userdata('bgs_be_active')){
			$bank_id = $this->input->post('bank_id');
			$bank_name = $this->input->post('bank_name');

			$data = array(
				'bank_name' => $this->input->post('bank_name'),
				'bank_code' => $this->input->post('bank_code'),
				'bank_comment' => $this->input->post('bank_comment'),
				'sort_key' => $this->input->post('sort_key'),
				'is_active' => $this->input->post('is_active')
			);	
			if($this->banks_model->bank_update_exists($bank_id,$bank_name) == false){
				$q = $this->banks_model->update_bank($data,$bank_id);
				if ($q['res'] == true){
					$resp = array('status' => 'SUCCESS','message' => $q['dt']);
				}else{
					$resp = array('status' => 'ERR','message' => $q['dt']);
				}
			}else{
				$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This Bank has already been defined');
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);

	}
	function delete_bank($bank_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->banks_model->delete_bank($bank_id);
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
	function delete_bulk_banks() {
		if($this->session->userdata('bgs_be_active')){
			$ids = $this->input->post('ids');
			$q = $this->banks_model->delete_bulk_banks($ids);
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


	//BANK BRANCHES
	function bank_branches($bank_id) {
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('bank_branches_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {			
				$data['cur'] = 'Settings';
				$data['cur_sub'] = 'Banks';
				$data['cur_cur_sub'] = '';

				$data['bank_id'] = $bank_id;
				$data['bank'] = $this->banks_model->get_bank($bank_id);

				$data['sbr_bank_branches_view'] = $this->auth_model->validate_user_access('bank_branches_view', $this->session->userdata('system_user_id'));
				$data['sbr_bank_branches_add'] = $this->auth_model->validate_user_access('bank_branches_add', $this->session->userdata('system_user_id'));
				$data['sbr_bank_branches_edit'] = $this->auth_model->validate_user_access('bank_branches_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Bank Branches | ';
				$data['main_content'] = 'be/bank_branches';
				$this->load->view('be/includes/settings_template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_bank_branch(){
		$data = array(
			'bank_branch_name' => $this->input->post('bank_branch_name'),
			'bank_branch_code' => $this->input->post('bank_branch_code'),
			'account_number' => $this->input->post('account_number'),
			'phone_number' => $this->input->post('phone_number'),
			'mobile_number' => $this->input->post('mobile_number'),
			'email_address' => $this->input->post('email_address'),
			'postal_address' => $this->input->post('postal_address'),
			'sort_key' => $this->input->post('sort_key'),
			'is_active' => $this->input->post('is_active'),
			'bank_id' => $this->input->post('bank_id'),
			'created_on' => date("Y-m-d H:i:s", time())
		);	
		$bank_branch_name = $this->input->post('bank_branch_name');
		$bank_id = $this->input->post('bank_id');
		if($this->banks_model->bank_branch_exists($bank_id, $bank_branch_name) == false){
			$q = $this->banks_model->save_bank_branch($data);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This Bank Branch has already been defined');
		}
			
		echo json_encode($resp);

	}
	function loadjs_bank_branches($bank_id){
		$data['bank_branches'] = $this->banks_model->get_bank_branches_list($bank_id);
		$data['sbr_bank_branches_delete'] = $this->auth_model->validate_user_access('bank_branches_delete', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/bank_branches',$data);
	}
	function get_bank_branch($bank_branch_id){
		$bank_branch = $this->banks_model->get_bank_branch($bank_branch_id);
		echo json_encode($bank_branch);
	}
	function get_bank_branch2($bank_branch_id){
		$bank_branch = $this->banks_model->get_bank_branch2($bank_branch_id);
		echo json_encode($bank_branch);
	}
	function update_bank_branch(){
		if($this->session->userdata('bgs_be_active')){
			$bank_branch_id = $this->input->post('bank_branch_id');
			$bank_branch_name = $this->input->post('bank_branch_name');
			$bank_id = $this->input->post('bank_id');

			$data = array(
				'bank_branch_name' => $this->input->post('bank_branch_name'),
				'bank_branch_code' => $this->input->post('bank_branch_code'),
				'account_number' => $this->input->post('account_number'),
				'phone_number' => $this->input->post('phone_number'),
				'mobile_number' => $this->input->post('mobile_number'),
				'email_address' => $this->input->post('email_address'),
				'postal_address' => $this->input->post('postal_address'),
				'sort_key' => $this->input->post('sort_key'),
				'is_active' => $this->input->post('is_active'),
				'bank_id' => $this->input->post('bank_id')
			);	
			if($this->banks_model->bank_branch_update_exists($bank_id,$bank_branch_id,$bank_branch_name) == false){
				$q = $this->banks_model->update_bank_branch($data,$bank_branch_id);
				if ($q['res'] == true){
					$resp = array('status' => 'SUCCESS','message' => $q['dt']);
				}else{
					$resp = array('status' => 'ERR','message' => $q['dt']);
				}
			}else{
				$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This Bank Branch has already been defined');
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);
	}
	function delete_bank_branch($bank_branch_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->banks_model->delete_bank_branch($bank_branch_id);
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
	function delete_bulk_bank_branches() {
		if($this->session->userdata('bgs_be_active')){
			$ids = $this->input->post('ids');
			$q = $this->banks_model->delete_bulk_bank_branches($ids);
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


	//TAX RATES
	function tax_rates(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('tax_rates_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {			

				$data['page_title'] = 'Tax Rates | ';

				$data['cur'] = 'Settings';
				$data['cur_sub'] = 'Tax Rates';
				$data['cur_cur_sub'] = '';

				$data['sbr_tax_rates_view'] = $this->auth_model->validate_user_access('tax_rates_view', $this->session->userdata('system_user_id'));
				$data['sbr_tax_rates_add'] = $this->auth_model->validate_user_access('tax_rates_add', $this->session->userdata('system_user_id'));
				$data['sbr_tax_rates_edit'] = $this->auth_model->validate_user_access('tax_rates_edit', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/tax_rates';
				$this->load->view('be/includes/settings_template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_tax_rate(){

		$q = $this->tax_rates_model->tax_rate_exists();

		if($q['res'] == false){
			$data = array(
				'tax_rate_name' => $this->input->post('tax_rate_name'),
				'tax_rate_code' => $this->input->post('tax_rate_code'),
				'tax_rate_value' => $this->input->post('tax_rate_value'),
				'sort_key' => $this->input->post('sort_key'),
				'is_active' => $this->input->post('is_active'),
				'created_on' => date("Y-m-d H:i:s", time())
			);	
			$q = $this->tax_rates_model->save($data);
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
	function loadjs_tax_rates(){
		$data['tax_rates'] = $this->tax_rates_model->get_tax_rates_list();
		$data['sbr_tax_rates_delete'] = $this->auth_model->validate_user_access('tax_rates_delete', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/tax_rates',$data);
	}
	function get_tax_rate($tax_rate_id){
		$tax_rate = $this->tax_rates_model->get_tax_rate($tax_rate_id);
		echo json_encode($tax_rate);
	}
	function get_tax_rate2($tax_rate_id){
		$tax_rate = $this->tax_rates_model->get_tax_rate2($tax_rate_id);
		echo json_encode($tax_rate);
	}
	function update_tax_rate(){
		if($this->session->userdata('bgs_be_active')){

			$tax_rate_id = $this->input->post('tax_rate_id');

			$q = $this->tax_rates_model->tax_rate_update_exists($tax_rate_id);

			if($q['res'] == false){
				$data = array(
					'tax_rate_name' => $this->input->post('tax_rate_name'),
					'tax_rate_code' => $this->input->post('tax_rate_code'),
					'tax_rate_value' => $this->input->post('tax_rate_value'),
					'sort_key' => $this->input->post('sort_key'),
					'is_active' => $this->input->post('is_active')
				);	
				$q = $this->tax_rates_model->update($data,$tax_rate_id);
				if ($q['res'] == true){
					$resp = array('status' => 'SUCCESS','message' => $q['dt']);
				}else{
					$resp = array('status' => 'ERR','message' => $q['dt']);
				}
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);

	}
	function delete_tax_rate($tax_rate_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->tax_rates_model->delete($tax_rate_id);
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
	function delete_bulk_tax_rates() {
		if($this->session->userdata('bgs_be_active')){
			$ids = $this->input->post('ids');
			$q = $this->tax_rates_model->delete_bulk($ids);
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

	//CREDIT TERMS
	function credit_terms(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('credit_terms_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['page_title'] = 'Credit Payment Terms | ';

				$data['cur'] = 'Settings';
				$data['cur_sub'] = 'Credit Terms';
				$data['cur_cur_sub'] = '';

				$data['sbr_credit_terms_view'] = $this->auth_model->validate_user_access('credit_terms_view', $this->session->userdata('system_user_id'));
				$data['sbr_credit_terms_add'] = $this->auth_model->validate_user_access('credit_terms_add', $this->session->userdata('system_user_id'));
				$data['sbr_credit_terms_edit'] = $this->auth_model->validate_user_access('credit_terms_edit', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/credit_terms';
				$this->load->view('be/includes/settings_template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_credit_term(){

		$q = $this->credit_terms_model->credit_term_exists();

		if($q['res'] == false){
			$data = array(
				'credit_term' => $this->input->post('credit_term'),
				'credit_term_days' => $this->input->post('credit_term_days'),
				'sort_key' => $this->input->post('sort_key'),
				'is_active' => $this->input->post('is_active'),
				'created_on' => date("Y-m-d H:i:s", time())
			);	
			$q = $this->credit_terms_model->save($data);
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
	function loadjs_credit_terms(){
		$data['credit_terms'] = $this->credit_terms_model->get_credit_terms_list();
		$data['sbr_credit_terms_delete'] = $this->auth_model->validate_user_access('credit_terms_delete', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/credit_terms',$data);
	}
	function get_credit_term($credit_term_id){
		$credit_term = $this->credit_terms_model->get_credit_term($credit_term_id);
		echo json_encode($credit_term);
	}
	function get_credit_term2($credit_term_id){
		$credit_term = $this->credit_terms_model->get_credit_term2($credit_term_id);
		echo json_encode($credit_term);
	}
	function update_credit_term(){
		if($this->session->userdata('bgs_be_active')){

			$credit_term_id = $this->input->post('credit_term_id');

			$q = $this->credit_terms_model->credit_term_update_exists($credit_term_id);

			if($q['res'] == false){
				$data = array(
					'credit_term' => $this->input->post('credit_term'),
					'credit_term_days' => $this->input->post('credit_term_days'),
					'sort_key' => $this->input->post('sort_key'),
					'is_active' => $this->input->post('is_active')
				);	
				$q = $this->credit_terms_model->update($data,$credit_term_id);
				if ($q['res'] == true){
					$resp = array('status' => 'SUCCESS','message' => $q['dt']);
				}else{
					$resp = array('status' => 'ERR','message' => $q['dt']);
				}
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);

	}
	function delete_credit_term($credit_term_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->credit_terms_model->delete($credit_term_id);
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
	function delete_bulk_credit_terms() {
		if($this->session->userdata('bgs_be_active')){
			$ids = $this->input->post('ids');
			$q = $this->credit_terms_model->delete_bulk($ids);
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

	//MPESA SETTINGS
	function mpesa_settings(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('mpesa_settings_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['mpesa_settings'] = $this->mpesa_settings_model->get_mpesa_settings();
				$data['mpesa_settings_exists'] = $this->mpesa_settings_model->mpesa_settings_exists();

				$data['cur'] = 'Settings';
				$data['cur_sub'] = 'M-Pesa Settings';
				$data['cur_cur_sub'] = '';

				$data['sbr_mpesa_settings_edit'] = $this->auth_model->validate_user_access('mpesa_settings_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'M-Pesa Settings | ';
				$data['main_content'] = 'be/mpesa_settings';
				$this->load->view('be/includes/settings_template',$data);

			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_mpesa_settings(){
		$data = array(
			'consumer_key' => $this->input->post('consumer_key'),
			'consumer_secret' => $this->input->post('consumer_secret'),			
			'passkey' => $this->input->post('passkey'),
			'environment' => $this->input->post('environment'),
			'short_code' => $this->input->post('short_code')
		);	
		$q = $this->mpesa_settings_model->save($data);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);
	}

	//PESAPAL SETTINGS
	function pesapal_settings(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('pesapal_settings_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {			
				$data['pesapal_settings'] = $this->pesapal_settings_model->get_pesapal_settings();
				$data['pesapal_settings_exists'] = $this->pesapal_settings_model->pesapal_settings_exists();

				$data['cur'] = 'Settings';
				$data['cur_sub'] = 'Pesapal Settings';
				$data['cur_cur_sub'] = '';

				$data['sbr_pesapal_settings_edit'] = $this->auth_model->validate_user_access('pesapal_settings_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Pesapal Settings | ';
				$data['main_content'] = 'be/pesapal_settings';
				$this->load->view('be/includes/settings_template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_pesapal_settings(){
		$data = array(
			'consumer_key' => $this->input->post('consumer_key'),
			'consumer_secret' => $this->input->post('consumer_secret'),			
			'environment' => $this->input->post('environment')
		);	
		$q = $this->pesapal_settings_model->save($data);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);
	}

	//EMAIL ACCOUNTS
	function email_settings(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('email_accounts_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {			

				$data['page_title'] = 'Email Accounts | ';

				$data['cur'] = 'Settings';
				$data['cur_sub'] = 'Email Settings';
				$data['cur_cur_sub'] = '';

				$data['sbr_email_accounts_view'] = $this->auth_model->validate_user_access('email_accounts_view', $this->session->userdata('system_user_id'));
				$data['sbr_email_accounts_add'] = $this->auth_model->validate_user_access('email_accounts_add', $this->session->userdata('system_user_id'));
				$data['sbr_email_accounts_edit'] = $this->auth_model->validate_user_access('email_accounts_edit', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/email_accounts';
				$this->load->view('be/includes/settings_template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_email_account(){
		$sender_email_address = $this->input->post('sender_email_address');
		if($this->email_accounts_model->email_account_exists($sender_email_address) == false){
			$use_ssl = $this->input->post('use_ssl');
			if($use_ssl == 'on'){
				$use_ssl = 1;
			}else{
				$use_ssl = 0;
			}
			$is_default = $this->input->post('default');
			if($is_default == 'on'){
				$is_default = 1;
			}else{
				$is_default = 0;
			}

			$data = array(
				'is_default' => $is_default,
				'sender_name' => $this->input->post('sender_name'),
				'sender_email_address' => $this->input->post('sender_email_address'),
				'mail_server_name' => $this->input->post('mail_server_name'),
				'mail_server_port' => $this->input->post('mail_server_port'),
				'user_name' => $this->input->post('user_name'),
				'password' => $this->input->post('password'),
				'use_ssl' => $use_ssl,
				'sort_key' => $this->input->post('sort_key'),
				'is_active' => $this->input->post('is_active')
			);	
			$q = $this->email_accounts_model->save($data);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This Email Account has already been defined.');
		}
			
		echo json_encode($resp);
	}
	function loadjs_email_accounts(){
		$data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();
		$data['sbr_email_accounts_delete'] = $this->auth_model->validate_user_access('email_accounts_delete', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/email_accounts',$data);
	}
	function get_email_account($email_account_id){
		$email_account = $this->email_accounts_model->get_email_account($email_account_id);
		echo json_encode($email_account);
	}
	function get_email_account2($email_account_id){
		$email_account = $this->email_accounts_model->get_email_account2($email_account_id);
		echo json_encode($email_account);
	}
	function update_email_account(){
		if($this->session->userdata('bgs_be_active')){

			$email_account_id = $this->input->post('email_account_id');
			$sender_email_address = $this->input->post('sender_email_address');
			if($this->email_accounts_model->email_account_update_exists($email_account_id,$sender_email_address) == false){
				$use_ssl = $this->input->post('use_ssl');
				if($use_ssl == 'on'){
					$use_ssl = 1;
				}else{
					$use_ssl = 0;
				}
				$is_default = $this->input->post('default');
				if($is_default == 'on'){
					$is_default = 1;
				}else{
					$is_default = 0;
				}
				$data = array(
					'is_default' => $is_default,
					'sender_name' => $this->input->post('sender_name'),
					'sender_email_address' => $this->input->post('sender_email_address'),
					'mail_server_name' => $this->input->post('mail_server_name'),
					'mail_server_port' => $this->input->post('mail_server_port'),
					'user_name' => $this->input->post('user_name'),
					'password' => $this->input->post('password'),
					'use_ssl' => $use_ssl,
					'sort_key' => $this->input->post('sort_key'),
					'is_active' => $this->input->post('is_active')
				);	

				$q = $this->email_accounts_model->update($data,$email_account_id);
				if ($q['res'] == true){
					$resp = array('status' => 'SUCCESS','message' => $q['dt']);
				}else{
					$resp = array('status' => 'ERR','message' => $q['dt']);
				}
			}else{
				$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This Email Account has already been defined.');
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);

	}
	function delete_email_account($email_account_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->email_accounts_model->delete($email_account_id);
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
	function delete_bulk_email_accounts() {
		if($this->session->userdata('bgs_be_active')){
			$ids = $this->input->post('ids');
			$q = $this->email_accounts_model->delete_bulk($ids);
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

	//EMAIL NOTIFICATION SETTINGS
	function email_notification_settings(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('email_notification_settings_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {			

				$data['email_notification_settings'] = $this->email_notification_settings_model->get_email_notification_settings();
				$data['email_notification_settings_exists'] = $this->email_notification_settings_model->email_notification_settings_exists();

				$data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();

				$data['cur'] = 'Settings';
				$data['cur_sub'] = 'Email Notification Settings';
				$data['cur_cur_sub'] = '';

				$data['sbr_email_notification_settings_edit'] = $this->auth_model->validate_user_access('email_notification_settings_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Email Notfication Settings | ';
				$data['main_content'] = 'be/email_notification_settings';
				$this->load->view('be/includes/settings_template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_email_notification_settings(){

		$data = array(
			'email_address' => $this->input->post('email_address'),			
			'cc_email_address' => $this->input->post('cc_email_address'),
			'bcc_email_address' => $this->input->post('bcc_email_address'),
		);	
		$q = $this->email_notification_settings_model->save($data);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);
	}

	//EMAIL TEMPLATES
	function email_templates(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('email_templates_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {			

				$data['page_title'] = 'Email Templates | ';

				$data['cur'] = 'Settings';
				$data['cur_sub'] = 'Email Templates';
				$data['cur_cur_sub'] = '';

				$data['sbr_email_templates_view'] = $this->auth_model->validate_user_access('email_templates_view', $this->session->userdata('system_user_id'));
				$data['sbr_email_templates_add'] = $this->auth_model->validate_user_access('email_templates_add', $this->session->userdata('system_user_id'));
				$data['sbr_email_templates_edit'] = $this->auth_model->validate_user_access('email_templates_edit', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/email_templates';
				$this->load->view('be/includes/settings_template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_email_template(){
		$email_template_name = $this->input->post('email_template_name');
		if($this->email_templates_model->email_template_exists($email_template_name) == false){
			$data = array(
				'email_template_name' => $this->input->post('email_template_name'),
				'email_template' => $this->input->post('email_template')
			);	
			$q = $this->email_templates_model->save($data);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This Email Template has already been defined.');
		}
			
		echo json_encode($resp);
	}
	function loadjs_email_templates(){
		$data['email_templates'] = $this->email_templates_model->get_email_templates_list();
		$data['sbr_email_templates_delete'] = $this->auth_model->validate_user_access('email_templates_delete', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/email_templates',$data);
	}
	function get_email_template($email_template_id){
		$email_template = $this->email_templates_model->get_email_template($email_template_id);
		echo json_encode($email_template);
	}
	function get_email_template2($email_template_id){
		$email_template = $this->email_templates_model->get_email_template2($email_template_id);
		echo json_encode($email_template);
	}
	function update_email_template(){
		if($this->session->userdata('bgs_be_active')){

			$email_template_id = $this->input->post('email_template_id');
			$email_template_name = $this->input->post('email_template_name');
			if($this->email_templates_model->email_template_update_exists($email_template_id,$email_template_name) == false){

				$data = array(
					'email_template_name' => $this->input->post('email_template_name'),
					'email_template' => $this->input->post('email_template')
				);	

				$q = $this->email_templates_model->update($data,$email_template_id);
				if ($q['res'] == true){
					$resp = array('status' => 'SUCCESS','message' => $q['dt']);
				}else{
					$resp = array('status' => 'ERR','message' => $q['dt']);
				}
			}else{
				$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This Email Template has already been defined.');
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);

	}
	function delete_email_template($email_template_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->email_templates_model->delete($email_template_id);
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
	function delete_bulk_email_templates() {
		if($this->session->userdata('bgs_be_active')){
			$ids = $this->input->post('ids');
			$q = $this->email_templates_model->delete_bulk($ids);
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

	//SMS SETTINGS
	function sms_settings(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('bulk_sms_settings_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {			

				$data['sms_settings'] = $this->sms_settings_model->get_sms_settings();
				$data['sms_settings_exists'] = $this->sms_settings_model->sms_settings_exists();

				$data['cur'] = 'Settings';
				$data['cur_sub'] = 'SMS Settings';
				$data['cur_cur_sub'] = '';

				$data['sbr_bulk_sms_settings_edit'] = $this->auth_model->validate_user_access('bulk_sms_settings_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'SMS Settings | ';
				$data['main_content'] = 'be/sms_settings';
				$this->load->view('be/includes/settings_template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_sms_settings(){

		$use_sender_id = $this->input->post('use_sender_id');
		if($use_sender_id == 'on'){
			$use_sender_id = 1;
		}else{
			$use_sender_id = 0;
		}

		$use_short_code = $this->input->post('use_short_code');
		if($use_short_code == 'on'){
			$use_short_code = 1;
		}else{
			$use_short_code = 0;
		}

		$data = array(
			'api_username' => $this->input->post('api_username'),
			'api_key' => $this->input->post('api_key'),			
			'sender_id' => $this->input->post('sender_id'),
			'use_sender_id' => $use_sender_id,
			'short_code' => $this->input->post('short_code'),
			'use_short_code' => $use_short_code,
		);	
		$q = $this->sms_settings_model->save($data);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);
	}


	//PREFIXES
	function prefixes(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('prefixes_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {			

				$data['prefixes'] = $this->prefixes_model->get_prefixes_list();
				$data['page_title'] = 'Prefixes | ';

				$data['cur'] = 'Settings';
				$data['cur_sub'] = 'Prefixes';
				$data['cur_cur_sub'] = '';

				$data['sbr_prefixes_view'] = $this->auth_model->validate_user_access('prefixes_view', $this->session->userdata('system_user_id'));
				$data['sbr_prefixes_edit'] = $this->auth_model->validate_user_access('prefixes_edit', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/prefixes';
				$this->load->view('be/includes/settings_template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function loadjs_prefixes(){
		$data['prefixes'] = $this->prefixes_model->get_prefixes_list();
		$this->load->view('be/jsloads/prefixes',$data);

	}
	function get_prefix2($prefix_id){
		$prefix = $this->prefixes_model->get_prefix2($prefix_id);
		echo json_encode($prefix);
	}
	function update_prefix(){
		if($this->session->userdata('bgs_be_active')){
			$prefix_id = $this->input->post('prefix_id');
			$prefix_name = $this->input->post('prefix_name');
			$data = array(
				'prefix_name' => $this->input->post('prefix_name'),
				'current_value' => $this->input->post('current_value')
			);	
			if($this->prefixes_model->prefix_update_exists($prefix_id,$prefix_name) == false){
				$q = $this->prefixes_model->update($data,$prefix_id);
				if ($q['res'] == true){
					$resp = array('status' => 'SUCCESS','message' => $q['dt']);
				}else{
					$resp = array('status' => 'ERR','message' => $q['dt']);
				}
			}else{
				$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This Prefix has already been defined.');
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);
	}



	//VOID REASONS
	function void_reasons(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('void_reasons_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {			

				$data['page_title'] = 'Void Reasons | ';

				$data['cur'] = 'Settings';
				$data['cur_sub'] = 'Void Reasons';
				$data['cur_cur_sub'] = '';

				$data['sbr_void_reasons_view'] = $this->auth_model->validate_user_access('void_reasons_view', $this->session->userdata('system_user_id'));
				$data['sbr_void_reasons_add'] = $this->auth_model->validate_user_access('void_reasons_add', $this->session->userdata('system_user_id'));
				$data['sbr_void_reasons_edit'] = $this->auth_model->validate_user_access('void_reasons_edit', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/void_reasons';
				$this->load->view('be/includes/settings_template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_void_reason(){

		$q = $this->void_reasons_model->void_reason_exists();

		if($q['res'] == false){
			$data = array(
				'void_reason' => $this->input->post('void_reason'),
				'sort_key' => $this->input->post('sort_key'),
				'is_active' => $this->input->post('is_active'),
				'created_on' => date("Y-m-d H:i:s", time())
			);	
			$q = $this->void_reasons_model->save($data);
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
	function loadjs_void_reasons(){
		$data['void_reasons'] = $this->void_reasons_model->get_void_reasons_list();
		$data['sbr_void_reasons_delete'] = $this->auth_model->validate_user_access('void_reasons_delete', $this->session->userdata('system_user_id'));
		$this->load->view('be/jsloads/void_reasons',$data);
	}
	function get_void_reason($void_reason_id){
		$void_reason = $this->void_reasons_model->get_void_reason($void_reason_id);
		echo json_encode($void_reason);
	}
	function get_void_reason2($void_reason_id){
		$void_reason = $this->void_reasons_model->get_void_reason2($void_reason_id);
		echo json_encode($void_reason);
	}
	function update_void_reason(){
		if($this->session->userdata('bgs_be_active')){

			$void_reason_id = $this->input->post('void_reason_id');

			$q = $this->void_reasons_model->void_reason_update_exists($void_reason_id);

			if($q['res'] == false){
				$data = array(
					'void_reason' => $this->input->post('void_reason'),
					'sort_key' => $this->input->post('sort_key'),
					'is_active' => $this->input->post('is_active')
				);	
				$q = $this->void_reasons_model->update($data,$void_reason_id);
				if ($q['res'] == true){
					$resp = array('status' => 'SUCCESS','message' => $q['dt']);
				}else{
					$resp = array('status' => 'ERR','message' => $q['dt']);
				}
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);

	}
	function delete_void_reason($void_reason_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->void_reasons_model->delete($void_reason_id);
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
	function delete_bulk_void_reasons() {
		if($this->session->userdata('bgs_be_active')){
			$ids = $this->input->post('ids');
			$q = $this->void_reasons_model->delete_bulk($ids);
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


	//BITLY SETTINGS
	function bitly_settings(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('bitly_settings_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {			

				$data['bitly_settings'] = $this->bitly_settings_model->get_bitly_settings();
				$data['bitly_settings_exists'] = $this->bitly_settings_model->bitly_settings_exists();

				$data['cur'] = 'Settings';
				$data['cur_sub'] = 'Bitly Settings';
				$data['cur_cur_sub'] = '';

				$data['sbr_bitly_settings_edit'] = $this->auth_model->validate_user_access('bitly_settings_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Bitly Settings | ';
				$data['main_content'] = 'be/bitly_settings';
				$this->load->view('be/includes/settings_template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_bitly_settings(){

		$data = array(
			'generic_access_token' => $this->input->post('generic_access_token')
		);	
		$q = $this->bitly_settings_model->save($data);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);
	}

	//SALE COMMENTS
	function sale_comments(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('sale_comments_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {			

				$data['sale_comments'] = $this->sale_comments_model->get_sale_comments();
				$data['sale_comments_exists'] = $this->sale_comments_model->sale_comments_exists();

				$data['cur'] = 'Settings';
				$data['cur_sub'] = 'Sale Comments';
				$data['cur_cur_sub'] = '';

				$data['sbr_sale_comments_edit'] = $this->auth_model->validate_user_access('sale_comments_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Sale Comments | ';
				$data['main_content'] = 'be/sale_comments';
				$this->load->view('be/includes/settings_template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_sale_comments(){

		$data = array(
			'cash_comments' => $this->input->post('cash_comments'),
			'credit_comments' => $this->input->post('credit_comments')
		);	
		$q = $this->sale_comments_model->save($data);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);
	}


	//LNM RECONCILIATION
	function lnm_contacts_reconciliation(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('lnm_reconciliation_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {			

				$data['cur'] = 'Settings';
				$data['cur_sub'] = 'LNM Contacts Reconciliation';
				$data['cur_cur_sub'] = '';

				$data['sbr_lnm_reconciliation_edit'] = $this->auth_model->validate_user_access('lnm_reconciliation_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'LNM Contacts Reconciliation | ';
				$data['main_content'] = 'be/lnm_contacts_reconciliation';
				$this->load->view('be/includes/settings_template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}




		// if($this->session->userdata('aerp_active')) {
		// 	if ($this->auth_model->validate_user_access('lnm_reconciliation_view', $this->session->userdata('system_user_id')) == false){
		// 		redirect('auth/access_denied');
		// 	} else {	

		// 		$data['countries'] = $this->locations_model->get_countries_list();
		// 		$data['page_title'] = 'LNM Contacts Reconciliation';
		// 		$data['page_subtitle'] = 'LNM Contacts Reconciliation';

		// 		$data['cur'] = 'Settings';
		// 		$data['cur_sub'] = 'LNM Contacts Reconciliation';
		// 		$data['cur_cur_sub'] = '';
		// 		$data['cur_mod'] = 'Settings';

		// 		$data['urp_lnm_reconciliation_view'] = $this->auth_model->validate_user_access('lnm_reconciliation_view', $this->session->userdata('system_user_id'));
		// 		$data['urp_lnm_reconciliation_edit'] = $this->auth_model->validate_user_access('lnm_reconciliation_edit', $this->session->userdata('system_user_id'));

		// 		$data['default_currency'] = $this->currencies_model->get_default_currency();

		// 		$data['crumbs'] = [    
    	// 			[
	    //    				'name' => 'Dashboard',     
	    //    				'url' => base_url() . 'dashboard'
	    // 			],
	    // 			[
	    //    				'name' => 'Settings',     
	    //    				'url' => base_url() . 'settings'
	    // 			],
	    // 			[
	    //    				'name' => 'LNM Reconciliation',     
	    //    				'url' => base_url() . 'settings/lnm_reconciliation'
	    // 			]
		// 		];

		// 		$data['main_content'] = 'lnm_reconciliation';
		// 		$this->load->view('includes/template',$data);
		// 	}
        // } 
		// else {
        //     redirect('auth');
		// }
	}

	function lnm_v1_contacts_reconciliation() {
		// $this->api_model->update_valid_phone_numbers();
		$q = $this->lnm_reconciliation_model->reconcile_lnm_v1_contacts();
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);
	}

	function lnm_v2_contacts_reconciliation() {
		if($this->session->userdata('bgs_be_active')){
			//Path of files were you want to upload on localhost (C:/xampp/htdocs/ProjectName/uploads/excel/)	 
	        $configUpload['upload_path'] = './uploads/excel/';
	        $configUpload['allowed_types'] = 'xls|xlsx|csv';
	        $configUpload['max_size'] = '0';
	        $this->load->library('upload');
	        $this->upload->initialize($configUpload);
	        $q = $this->upload->do_upload('excel_file');	
	        
	        if($q){
	        	$upload_data = $this->upload->data(); //Returns array of containing all of the data related to the file you uploaded.

		        $file_name = $upload_data['file_name']; //uploded file name
				$extension=$upload_data['file_ext'];    // uploded file extension
				
				//$objReader =PHPExcel_IOFactory::createReader('Excel5');     //For excel 2003 
		 		$objReader= PHPExcel_IOFactory::createReader('Excel2007');	// For excel 2007 	  
		        
				//Set to read only
		        $objReader->setReadDataOnly(true); 		  
		        
				//Load excel file
				$objPHPExcel=$objReader->load('./uploads/excel/'.$file_name);		 
		        $totalrows=$objPHPExcel->setActiveSheetIndex(0)->getHighestRow();   //Count Number of rows avalable in excel      	 
		        $objWorksheet=$objPHPExcel->setActiveSheetIndex(0);                
		        
				//loop from first data untill last data
		        for($i=2;$i<=$totalrows;$i++){
		        	$transaction_id= $objWorksheet->getCellByColumnAndRow(0,$i)->getValue();			
		            $phone_number= $objWorksheet->getCellByColumnAndRow(1,$i)->getValue(); //Excel Column 1

					$q = $this->lnm_reconciliation_model->reconcile_lnm_v2_contacts($transaction_id,$phone_number);
				}
		        unlink('././uploads/excel/'.$file_name); //File Deleted After uploading in database .	
		     	
		     	//redirect(base_url() . "put link were you want to redirect");
				$resp = array('status' => 'SUCCESS','message' => 'Reconciliation successful.');
		   	} else {
				$resp = array('status' => 'ERR','message' => $this->upload->display_errors());			
	   	}		 
		}else{
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);
	}



}