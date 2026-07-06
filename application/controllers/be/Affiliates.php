<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Affiliates extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/affiliates_model');
		$this->load->model('be/locations_model');
		$this->load->model('be/currencies_model');
		$this->load->model('be/affiliate_packages_model');
		$this->load->model('be/support_model');
		$this->load->model('be/auth_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('affiliate_accounts_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'Affiliate Program';
				$data['cur_sub'] = 'Affiliate Accounts';
				$data['cur_cur_sub'] = '';

				$data['page_title'] = 'Affiliate Accounts | ';
				$data['main_content'] = 'be/affiliates';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function manage($affiliate_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('affiliate_accounts_manage', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'Affiliate Program';
				$data['cur_sub'] = 'Affiliate Accounts';
				$data['cur_cur_sub'] = '';

				$data['affiliate_packages'] = $this->affiliate_packages_model->get_affiliate_packages_list();
				$data['default_currency'] = $this->currencies_model->get_default_currency();
				$data['countries'] = $this->locations_model->get_countries_list();

				$data['affiliate'] = $this->affiliates_model->get_affiliate($affiliate_id);
	            $data['total_clicks'] = $this->affiliates_model->get_account_total_clicks($affiliate_id);
	            $data['total_referrals'] = $this->affiliates_model->get_account_total_referrals($affiliate_id);

	            $this->support_model->read_notification('Affiliate Account Creation', $affiliate_id);

				$data['page_title'] = 'Manage Affiliate Account | ';
				$data['main_content'] = 'be/affiliate_manage';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function account_referrals($affiliate_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('affiliate_accounts_manage', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'Affiliate Program';
				$data['cur_sub'] = 'Affiliate Accounts';
				$data['cur_cur_sub'] = '';

				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['affiliate'] = $this->affiliates_model->get_affiliate($affiliate_id);

				$data['page_title'] = 'Affiliate Referrals | ';
				$data['main_content'] = 'be/affiliate_referrals';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function account_clicks($affiliate_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('affiliate_accounts_manage', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'Affiliate Program';
				$data['cur_sub'] = 'Affiliate Accounts';
				$data['cur_cur_sub'] = '';

				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['affiliate'] = $this->affiliates_model->get_affiliate($affiliate_id);

				$data['page_title'] = 'Affiliate Clicks | ';
				$data['main_content'] = 'be/affiliate_clicks';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function approve() {
		if($this->session->userdata('bgs_be_active')){
			$q = $this->affiliates_model->approve_account();
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

	function assign_package() {
		if($this->session->userdata('bgs_be_active')){
			$q = $this->affiliates_model->assign_package();
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

	function loadjs_account_referrals($affiliate_id){
		$data['affiliate_referrals'] = $this->affiliates_model->get_affiliate_referrals($affiliate_id);
		$this->load->view('be/jsloads/affiliate_referrals',$data);
	} 

	function loadjs_account_clicks($affiliate_id){
		$data['affiliate_clicks'] = $this->affiliates_model->get_affiliate_clicks($affiliate_id);
		$this->load->view('be/jsloads/affiliate_clicks',$data);
	} 

	function load_js(){
		$data['affiliates'] = $this->affiliates_model->get_affiliates_list();
		$data['sbr_affiliate_accounts_manage'] = $this->auth_model->validate_user_access('affiliate_accounts_manage', $this->session->userdata('system_user_id'));
		$data['sbr_affiliate_accounts_delete'] = $this->auth_model->validate_user_access('affiliate_accounts_delete', $this->session->userdata('system_user_id'));
		$this->load->view('be/jsloads/affiliates',$data);
	}

	function delete($affiliate_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->affiliates_model->delete($affiliate_id);
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
			$q = $this->affiliates_model->delete_bulk($ids);
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



	//AFFILIATE TERMS AND CONDITIONS
	function terms() {
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('affiliate_terms_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'Affiliate Program';
				$data['cur_sub'] = 'Affiliates T&Cs';
				$data['cur_cur_sub'] = '';

				$data['affiliate_terms'] = $this->affiliates_model->get_affiliate_terms();
				$data['affiliate_terms_exists'] = $this->affiliates_model->affiliate_terms_exists();

				$data['sbr_affiliate_terms_edit'] = $this->auth_model->validate_user_access('affiliate_terms_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Affiliates Terms of Service | ';
				$data['main_content'] = 'be/affiliate_terms';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}

	}

	function save_terms(){
		$data = array(
			'affiliate_terms' => $this->input->post('affiliate_terms')
		);	
		$q = $this->affiliates_model->save_affiliate_terms($data);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);
	}



}