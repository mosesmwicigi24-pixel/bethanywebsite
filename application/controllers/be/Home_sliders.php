<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home_sliders extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/home_sliders_model');
		$this->load->model('be/auth_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('home_sliders_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'CMS Content';
				$data['cur_sub'] = 'Home Page';
				$data['cur_cur_sub'] = 'Home Sliders';

				$data['sbr_home_sliders_add'] = $this->auth_model->validate_user_access('home_sliders_add', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Home Sliders | ';
				$data['main_content'] = 'be/home_sliders';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save(){
		$q = $this->home_sliders_model->save();
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);

	}
	function loadjs(){
		$data['home_sliders'] = $this->home_sliders_model->get_home_sliders_list();
		$data['sbr_home_sliders_edit'] = $this->auth_model->validate_user_access('home_sliders_edit', $this->session->userdata('system_user_id'));
		$data['sbr_home_sliders_delete'] = $this->auth_model->validate_user_access('home_sliders_delete', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/home_sliders',$data);
	}
	function get_home_slider($home_slider_id){
		$home_slider = $this->home_sliders_model->get_home_slider($home_slider_id);
		echo json_encode($home_slider);
	}
	function update(){
		$home_slider_id = $this->input->post('home_slider_id');

		$q = $this->home_sliders_model->update($home_slider_id);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
		echo json_encode($resp);

	}
	function delete($home_slider_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->home_sliders_model->delete($home_slider_id);
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