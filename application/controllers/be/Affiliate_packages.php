<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Affiliate_packages extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/affiliate_packages_model');
		$this->load->model('be/currencies_model');
		$this->load->model('be/auth_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('affiliate_packages_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'Affiliate Program';
				$data['cur_sub'] = 'Affiliate Packages';
				$data['cur_cur_sub'] = '';

				$data['sbr_affiliate_packages_add'] = $this->auth_model->validate_user_access('affiliate_packages_add', $this->session->userdata('system_user_id'));
				$data['sbr_affiliate_packages_edit'] = $this->auth_model->validate_user_access('affiliate_packages_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Affiliate Packages | ';
				$data['main_content'] = 'be/affiliate_packages';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save(){
		$data = array(
			'affiliate_package_name' => $this->input->post('affiliate_package_name'),
			'affiliate_package_colour_code' => $this->input->post('affiliate_package_colour_code'),
			'commission' => $this->input->post('commission'),
			'minimum_pay_out' => $this->input->post('minimum_pay_out'),
			'affiliate_package_features' => $this->input->post('affiliate_package_features'),
			'is_active' => $this->input->post('is_active'),
			'created_on' => date("Y-m-d H:i:s", time())
		);	
		$affiliate_package_name = $this->input->post('affiliate_package_name');
		if($this->affiliate_packages_model->affiliate_package_exists($affiliate_package_name) == false){
			$q = $this->affiliate_packages_model->save($data);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'This Affiliate Package has already been defined');
		}
			
		echo json_encode($resp);

	}
	function loadjs(){
		$data['affiliate_packages'] = $this->affiliate_packages_model->get_affiliate_packages_list();
		$data['sbr_affiliate_packages_delete'] = $this->auth_model->validate_user_access('affiliate_packages_delete', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/affiliate_packages',$data);
	}
	function get_affiliate_package2($affiliate_package_id){
		$affiliate_package = $this->affiliate_packages_model->get_affiliate_package2($affiliate_package_id);
		echo json_encode($affiliate_package);
	}
	function get_affiliate_package_details($affiliate_package_id) {
		$data['affiliate_package'] = $this->affiliate_packages_model->get_affiliate_package($affiliate_package_id);
		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$this->load->view('be/jsloads/affiliate_package_details',$data);
	}
	function update(){
		$affiliate_package_id = $this->input->post('affiliate_package_id');
		$affiliate_package_name = $this->input->post('affiliate_package_name');

		$data = array(
			'affiliate_package_name' => $this->input->post('affiliate_package_name'),
			'affiliate_package_colour_code' => $this->input->post('affiliate_package_colour_code'),
			'commission' => $this->input->post('commission'),
			'minimum_pay_out' => $this->input->post('minimum_pay_out'),
			'affiliate_package_features' => $this->input->post('affiliate_package_features'),
			'is_active' => $this->input->post('is_active'),
			'is_active' => $this->input->post('is_active'),
		);	
		if($this->affiliate_packages_model->affiliate_package_update_exists($affiliate_package_id,$affiliate_package_name) == false){
			$q = $this->affiliate_packages_model->update($data,$affiliate_package_id);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'This Affiliate Package has already been defined');
		}
		echo json_encode($resp);

	}
	function delete($affiliate_package_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->affiliate_packages_model->delete($affiliate_package_id);
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
			$q = $this->affiliate_packages_model->delete_bulk($ids);
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
	function get_offer_affiliate_package() {

		$affiliate_package_id = $this->input->post('affiliate_package_id');
		$affiliate_package_duration = $this->input->post('affiliate_package_duration');

		$ret = '';

		if ($affiliate_package_id != '' && $affiliate_package_id != null && $affiliate_package_duration != '' && $affiliate_package_duration != null){
			$affiliate_package = $this->affiliate_packages_model->get_affiliate_package($affiliate_package_id);
			$affiliate_package_price = 0;
			$expiry_date = '';

			foreach ($affiliate_package as $row) {
				if ($affiliate_package_duration == '1 Week'){
					$affiliate_package_price = $row->one_week_price;
					//$due_date = date('l, M d Y',strtotime(date("m/d/Y"). ' + 3 days'));

				}elseif ($affiliate_package_duration == '2 Weeks') {
					$affiliate_package_price = $row->two_weeks_price;

				}elseif ($affiliate_package_duration == '1 Month') {
					$affiliate_package_price = $row->one_month_price;

				}
			}
			$ret = '<div class=" alert alert-info font-weight-400 font-13 mb-30">Offer Price: <b>KES ' . number_format($affiliate_package_price,2) . '</b>.</div>';
		}
		echo $ret;
	}


}