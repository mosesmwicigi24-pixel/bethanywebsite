<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Privacy_policy extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/privacy_policy_model');
		$this->load->model('be/auth_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('privacy_policy_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['privacy_policy'] = $this->privacy_policy_model->get_privacy_policy();
				$data['privacy_policy_exists'] = $this->privacy_policy_model->privacy_policy_exists();

				$data['cur'] = 'CMS Content';
				$data['cur_sub'] = 'Privacy Policy';
				$data['cur_cur_sub'] = '';

				$data['sbr_privacy_policy_edit'] = $this->auth_model->validate_user_access('privacy_policy_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Privacy Policy | ';
				$data['main_content'] = 'be/privacy_policy';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save(){
		$data = array(
			'privacy_policy' => $this->input->post('privacy_policy')
		);	
		$q = $this->privacy_policy_model->save($data);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);
	}
}