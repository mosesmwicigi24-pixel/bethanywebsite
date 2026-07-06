<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Terms_and_conditions extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/terms_and_conditions_model');
		$this->load->model('be/auth_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('terms_and_conditions_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['terms_and_conditions'] = $this->terms_and_conditions_model->get_terms_and_conditions();
				$data['terms_and_conditions_exists'] = $this->terms_and_conditions_model->terms_and_conditions_exists();

				$data['cur'] = 'CMS Content';
				$data['cur_sub'] = 'Terms and Conditions';
				$data['cur_cur_sub'] = '';

				$data['sbr_terms_and_conditions_edit'] = $this->auth_model->validate_user_access('terms_and_conditions_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Terms and Conditions | ';
				$data['main_content'] = 'be/terms_and_conditions';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save(){
		$data = array(
			'terms_and_conditions' => $this->input->post('terms_and_conditions')
		);	
		$q = $this->terms_and_conditions_model->save($data);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);
	}
}