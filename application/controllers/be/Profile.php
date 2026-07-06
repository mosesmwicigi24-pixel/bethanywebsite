<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('be/auth_model');
	}

	function index(){
		if($this->session->userdata('bgs_be_active')) {
			$data['cur'] = 'Profile';
			$data['cur_sub'] = 'Profile';
			$data['cur_cur_sub'] = '';

			$data['profile'] = $this->auth_model->get_profile();

			$data['page_title'] = 'Profile | ';
			$data['main_content'] = 'be/profile';
			$this->load->view('be/includes/template',$data);
        } 
		else {
            redirect('be/auth');
		}
	}
	function update(){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->auth_model->update_profile();
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue');			
    	}	
		echo json_encode($resp);
	}

	function change_password(){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->auth_model->change_password();
			if ($q['res'] == true){
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