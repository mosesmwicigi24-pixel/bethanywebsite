<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class About_us extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/about_us_model');
		$this->load->model('be/auth_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('about_us_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['about_us'] = $this->about_us_model->get_about_us();
				$data['about_us_exists'] = $this->about_us_model->about_us_exists();

				$data['cur'] = 'CMS Content';
				$data['cur_sub'] = 'About Us';
				$data['cur_cur_sub'] = '';

				$data['sbr_about_us_edit'] = $this->auth_model->validate_user_access('about_us_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'About Us | ';
				$data['main_content'] = 'be/about_us';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save(){
		$data = array(
			'about_us' => $this->input->post('about_us'),
			'mission' => $this->input->post('mission'),
			'vision' => $this->input->post('vision'),
			'core_values' => $this->input->post('core_values')
		);	
		$q = $this->about_us_model->save($data);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);
	}
	function set_contact_logo(){

		if ($this->about_us_model->about_us_exists() == true){
			$q = $this->about_us_model->upload_contact_logo();
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}			
		}else{
			$resp = array('status' => 'ERR','message' => 'Please set About Us first before setting Logo.');
		}
		echo json_encode($resp);
	}
	function delete_cover_image() {
		if($this->session->userdata('bgs_be_active')){
			$q = $this->about_us_model->delete_cover_image();
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