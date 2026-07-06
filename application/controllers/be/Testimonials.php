<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Testimonials extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/testimonials_model');
		$this->load->model('be/auth_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('testimonials_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'CMS Content';
				$data['cur_sub'] = 'Testimonials';
				$data['cur_cur_sub'] = '';

				$data['sbr_testimonials_add'] = $this->auth_model->validate_user_access('testimonials_add', $this->session->userdata('system_user_id'));
				$data['sbr_testimonials_edit'] = $this->auth_model->validate_user_access('testimonials_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Testimonials | ';
				$data['main_content'] = 'be/testimonials';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save(){

		$data = array(
			'testimonial_name' => $this->input->post('testimonial_name'),
			'testimonial_title' => $this->input->post('testimonial_title'),
			'testimonial_description' => $this->input->post('testimonial_description'),
			'is_active' => $this->input->post('is_active')
		);	
		$q = $this->testimonials_model->save($data);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);

	}
	function loadjs(){
		$data['testimonials'] = $this->testimonials_model->get_testimonials_list();
		$data['sbr_testimonials_delete'] = $this->auth_model->validate_user_access('testimonials_delete', $this->session->userdata('system_user_id'));

		$data['num_testimonials'] = $this->testimonials_model->get_num_testimonials();

		$this->load->view('be/jsloads/testimonials',$data);
	}
	function get_testimonial($testimonial_id){
		$testimonial = $this->testimonials_model->get_testimonial($testimonial_id);
		echo json_encode($testimonial);
	}
	function update(){
		$testimonial_id = $this->input->post('testimonial_id');

		$data = array(
			'testimonial_name' => $this->input->post('testimonial_name'),
			'testimonial_title' => $this->input->post('testimonial_title'),
			'testimonial_description' => $this->input->post('testimonial_description'),
			'is_active' => $this->input->post('is_active')
		);	
		$q = $this->testimonials_model->update($data,$testimonial_id);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
		echo json_encode($resp);

	}
	function delete($testimonial_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->testimonials_model->delete($testimonial_id);
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