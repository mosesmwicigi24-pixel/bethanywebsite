<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Departments extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/departments_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			$data['cur'] = 'Company Setup';
			$data['cur_sub'] = 'Departments';
			$data['cur_cur_sub'] = '';

			$data['page_title'] = 'Departments | ';
			$data['main_content'] = 'be/departments';
			$this->load->view('be/includes/template',$data);
        } 
		else {
            redirect('be/auth');
		}
	}
	function add(){
		if($this->session->userdata('bgs_be_active')) {
			$data['cur'] = 'Company Setup';
			$data['cur_sub'] = 'Departments';
			$data['cur_cur_sub'] = '';

			$data['page_title'] = 'New Department | ';
			$data['main_content'] = 'be/department_add';
			$this->load->view('be/includes/template',$data);
        } 
		else {
            redirect('be/auth');
		}
	}
	function save(){
		$department_sku = $this->departments_model->get_department_sku();
		$department_reference_id = url_title($this->input->post('department_name'),'-',TRUE) . '-' . strtolower($department_sku);

		$data = array(
			'department_reference_id' => $department_reference_id,
			'department_sku_code' => $department_sku,			
			'department_name' => $this->input->post('department_name'),
			'sort_key' => $this->input->post('sort_key'),
			'description' => $this->input->post('description'),
			'created_on' => date("Y-m-d H:i:s", time())
		);	
		$department_name = $this->input->post('department_name');
		if($this->departments_model->department_exists($department_name) == false){
			$q = $this->departments_model->save($data);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'This Department has already been defined');
		}
			
		echo json_encode($resp);

	}
	function loadjs(){
		$data['departments'] = $this->departments_model->get_departments_list();
		$this->load->view('be/jsloads/departments',$data);
	}
	function edit($department_id){
		if($this->session->userdata('bgs_be_active')) {
			$data['cur'] = 'Company Setup';
			$data['cur_sub'] = 'Departments';
			$data['cur_cur_sub'] = '';

			$data['department'] = $this->departments_model->get_department($department_id);

			$data['page_title'] = 'Edit Department | ';
			$data['main_content'] = 'be/department_add';
			$this->load->view('be/includes/template',$data);
        } 
		else {
            redirect('be/auth');
		}
	}
	function update(){
		$department_id = $this->input->post('department_id');
		$department_name = $this->input->post('department_name');

		$department_sku = $this->departments_model->get_department_sku_code($department_id);		
		$department_reference_id = url_title($this->input->post('department_name'),'-',TRUE) . '-' . strtolower($department_sku);


		$data = array(
			'department_reference_id'		=> $department_reference_id,
            'department_sku_code'			=> $department_sku,			
			'department_name' 				=> $this->input->post('department_name'),
			'sort_key' => $this->input->post('sort_key'),
			'description' => $this->input->post('description')
		);	
		if($this->departments_model->department_update_exists($department_id,$department_name) == false){
			$q = $this->departments_model->update($data,$department_id);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'This Department has already been defined');
		}
		echo json_encode($resp);

	}
	function delete($department_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->departments_model->delete($department_id);
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
	function delete_cover_image($department_id) {
		if($this->session->userdata('bgs_be_active')){
			$q = $this->departments_model->delete_cover_image($department_id);
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