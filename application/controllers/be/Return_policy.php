<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Return_policy extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/return_policy_model');
		$this->load->model('be/auth_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('return_policy_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['return_policy'] = $this->return_policy_model->get_return_policy();
				$data['return_policy_exists'] = $this->return_policy_model->return_policy_exists();

				$data['cur'] = 'CMS Content';
				$data['cur_sub'] = 'Return Policy';
				$data['cur_cur_sub'] = '';

				$data['sbr_return_policy_edit'] = $this->auth_model->validate_user_access('return_policy_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Return Policy | ';
				$data['main_content'] = 'be/return_policy';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save(){
		$data = array(
			'return_policy' => $this->input->post('return_policy')
		);	
		$q = $this->return_policy_model->save($data);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);
	}
}