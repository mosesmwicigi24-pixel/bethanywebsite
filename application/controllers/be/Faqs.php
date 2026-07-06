<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Faqs extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/faqs_model');
		$this->load->model('be/auth_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('faqs_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'CMS Content';
				$data['cur_sub'] = 'FAQs';
				$data['cur_cur_sub'] = '';

				$data['sbr_faqs_add'] = $this->auth_model->validate_user_access('faqs_add', $this->session->userdata('system_user_id'));
				$data['sbr_faqs_edit'] = $this->auth_model->validate_user_access('faqs_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'FAQs | ';
				$data['main_content'] = 'be/faqs';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save(){
		$data = array(
			'faq_heading' => $this->input->post('faq_heading'),
			'faq_description' => $this->input->post('faq_description'),
			'sort_key' => $this->input->post('sort_key'),
			'created_on' => date("Y-m-d H:i:s", time())
		);	
		$q = $this->faqs_model->save($data);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);

	}
	function loadjs(){
		$data['faqs'] = $this->faqs_model->get_faqs_list();
		$data['sbr_faqs_delete'] = $this->auth_model->validate_user_access('faqs_delete', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/faqs',$data);
	}
	function get_faq2($faq_id){
		$faq = $this->faqs_model->get_faq2($faq_id);
		echo json_encode($faq);
	}
	function update(){
		$faq_id = $this->input->post('faq_id');

		$data = array(
			'faq_heading' => $this->input->post('faq_heading'),
			'faq_description' => $this->input->post('faq_description'),
			'sort_key' => $this->input->post('sort_key')
		);	
		$q = $this->faqs_model->update($data,$faq_id);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
		echo json_encode($resp);

	}
	function delete($faq_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->faqs_model->delete($faq_id);
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
			$q = $this->faqs_model->delete_bulk($ids);
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


}