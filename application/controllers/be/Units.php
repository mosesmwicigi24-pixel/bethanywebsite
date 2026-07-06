<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Units extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/units_model');
		$this->load->model('be/auth_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('units_of_measure_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {

				$data['units'] = $this->units_model->get_units_list();
				$data['page_title'] = 'Units of Measure | ';

				$data['cur'] = 'Products';
				$data['cur_sub'] = 'Units of Measure';
				$data['cur_cur_sub'] = 'Units';

				$data['unit_types'] = $this->units_model->get_unit_types_list();

				$data['sbr_units_of_measure_view'] = $this->auth_model->validate_user_access('units_of_measure_view', $this->session->userdata('system_user_id'));
				$data['sbr_units_of_measure_add'] = $this->auth_model->validate_user_access('units_of_measure_add', $this->session->userdata('system_user_id'));
				$data['sbr_units_of_measure_edit'] = $this->auth_model->validate_user_access('units_of_measure_edit', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/units';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save(){
		$data = array(
			'unit_type_id' => $this->input->post('unit_type_id'),
			'unit_name' => $this->input->post('unit_name'),
			'unit_code' => $this->input->post('unit_code'),
			'sort_key' => $this->input->post('sort_key'),
			'is_active' => $this->input->post('is_active')
		);	
		$unit_name = $this->input->post('unit_name');
		if($this->units_model->unit_exists($unit_name) == false){
			$q = $this->units_model->save($data);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This Unit has already been defined.');
		}
			
		echo json_encode($resp);
	}
	function load_js(){
		$data['units'] = $this->units_model->get_units_list();
		$data['sbr_units_of_measure_delete'] = $this->auth_model->validate_user_access('units_of_measure_delete', $this->session->userdata('system_user_id'));
		$this->load->view('be/jsloads/units',$data);

	}
	function get_unit2($unit_id){
		$unit = $this->units_model->get_unit2($unit_id);
		echo json_encode($unit);
	}
	function get_add_unit_related_units($unit_type_id) {
		$data['related_units'] = $this->units_model->get_units_by_type($unit_type_id);
		$num_related_units = $this->units_model->get_num_units_by_type($unit_type_id);
		$related_units = $this->load->view('be/jsloads/add_unit_related_units',$data,TRUE);
		$resp = array('num_related_units' => $num_related_units,'related_units' => $related_units);
		echo json_encode($resp);
	}
	function get_edit_unit_related_units($unit_type_id) {
		$unit_id = $this->input->post('unit_id');
		$data['related_units'] = $this->units_model->get_edit_units_by_type($unit_type_id, $unit_id);
		$num_related_units = $this->units_model->get_num_edit_units_by_type($unit_type_id, $unit_id);
		$related_units = $this->load->view('be/jsloads/edit_unit_related_units',$data,TRUE);
		$resp = array('num_related_units' => $num_related_units,'related_units' => $related_units);
		echo json_encode($resp);
	}
	function update(){
		$unit_id = $this->input->post('unit_id');
		$unit_name = $this->input->post('unit_name');
		$data = array(
			'unit_type_id' => $this->input->post('unit_type_id'),
			'unit_name' => $this->input->post('unit_name'),
			'unit_code' => $this->input->post('unit_code'),
			'sort_key' => $this->input->post('sort_key'),
			'is_active' => $this->input->post('is_active')
		);	
		if($this->units_model->unit_update_exists($unit_id,$unit_name) == false){
			$q = $this->units_model->update($data,$unit_id);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This Unit has already been defined.');
		}
		echo json_encode($resp);
	}
	function delete($unit_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->units_model->delete($unit_id);
			if($q['res'] == TRUE){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);			
			}else{					
				$resp = array('status' => 'ERR','message' => $q['dt']);			
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);
	}


	//UNIT TYPES
	function types() {
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('units_of_measure_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {

				$data['unit_types'] = $this->units_model->get_unit_types_list();
				$data['page_title'] = 'Units of Measure | ';

				$data['cur'] = 'Products';
				$data['cur_sub'] = 'Units of Measure';
				$data['cur_cur_sub'] = 'Unit Types';

				$data['sbr_units_of_measure_view'] = $this->auth_model->validate_user_access('units_of_measure_view', $this->session->userdata('system_user_id'));
				$data['sbr_units_of_measure_add'] = $this->auth_model->validate_user_access('units_of_measure_add', $this->session->userdata('system_user_id'));
				$data['sbr_units_of_measure_edit'] = $this->auth_model->validate_user_access('units_of_measure_edit', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/unit_types';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_unit_type(){
		$data = array(
			'unit_type_name' => $this->input->post('unit_type_name'),
			'unit_type_description' => $this->input->post('unit_type_description'),
			'sort_key' => $this->input->post('sort_key'),
			'is_active' => $this->input->post('is_active')
		);	
		$unit_type_name = $this->input->post('unit_type_name');
		if($this->units_model->unit_type_exists($unit_type_name) == false){
			$q = $this->units_model->save_unit_type($data);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This unit_type has already been defined.');
		}
			
		echo json_encode($resp);
	}
	function load_js_unit_types(){
		$data['unit_types'] = $this->units_model->get_unit_types_list();
		$data['sbr_units_of_measure_delete'] = $this->auth_model->validate_user_access('units_of_measure_delete', $this->session->userdata('system_user_id'));
		$this->load->view('be/jsloads/unit_types',$data);

	}
	function get_unit_type2($unit_type_id){
		$unit_type = $this->units_model->get_unit_type2($unit_type_id);
		echo json_encode($unit_type);
	}
	function update_unit_type(){
		$unit_type_id = $this->input->post('unit_type_id');
		$unit_type_name = $this->input->post('unit_type_name');
		$data = array(
			'unit_type_name' => $this->input->post('unit_type_name'),
			'unit_type_description' => $this->input->post('unit_type_description'),
			'sort_key' => $this->input->post('sort_key'),
			'is_active' => $this->input->post('is_active')
		);	
		if($this->units_model->unit_type_update_exists($unit_type_id,$unit_type_name) == false){
			$q = $this->units_model->update_unit_type($data,$unit_type_id);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This unit_type has already been defined.');
		}
		echo json_encode($resp);
	}
	function delete_unit_type($unit_type_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->units_model->delete_unit_type($unit_type_id);
			if($q['res'] == TRUE){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);			
			}else{					
				$resp = array('status' => 'ERR','message' => $q['dt']);			
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);
	}


}