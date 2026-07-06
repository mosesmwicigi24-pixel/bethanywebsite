<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Suppliers extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/suppliers_model');
		$this->load->model('be/locations_model');
		$this->load->model('be/auth_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('suppliers_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'Suppliers';
				$data['cur_sub'] = 'Suppliers';
				$data['cur_cur_sub'] = '';

				$data['countries'] = $this->locations_model->get_countries_list();

				$data['sbr_suppliers_add'] = $this->auth_model->validate_user_access('suppliers_add', $this->session->userdata('system_user_id'));
				$data['sbr_suppliers_edit'] = $this->auth_model->validate_user_access('suppliers_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Suppliers | ';
				$data['main_content'] = 'be/suppliers';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save(){
		$q = $this->suppliers_model->supplier_exists();

		if($q['res'] == true){
			$data = array(
				'supplier_name' => $this->input->post('supplier_name'),
				'supplier_code' => $this->input->post('supplier_code'),
				'phone_number' => $this->input->post('phone_number'),
				'email_address' => $this->input->post('email_address'),
				'website' => $this->input->post('website'),
				'postal_address' => $this->input->post('postal_address'),
				'postal_code' => $this->input->post('postal_code'),
				'region_id' => $this->input->post('city_id'),
				'country_id' => $this->input->post('country_id'),
				'supplier_note' => $this->input->post('supplier_note'),
				'contact_person_first_name' => $this->input->post('contact_person_first_name'),
				'contact_person_last_name' => $this->input->post('contact_person_last_name'),
				'contact_person_mobile_number' => $this->input->post('contact_person_mobile_number'),
				'contact_person_email_address' => $this->input->post('contact_person_email_address'),
				'sort_key' => $this->input->post('sort_key'),
				'is_active' => $this->input->post('is_active'),
				'created_on' => date("Y-m-d H:i:s", time())
			);	
			$q = $this->suppliers_model->save($data);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
		echo json_encode($resp);
	}
	function load_js(){
		$data['suppliers'] = $this->suppliers_model->get_suppliers_list();
		$data['sbr_suppliers_delete'] = $this->auth_model->validate_user_access('suppliers_delete', $this->session->userdata('system_user_id'));
		$this->load->view('be/jsloads/suppliers',$data);
	}
	function get_supplier($supplier_id){
		$supplier = $this->suppliers_model->get_supplier($supplier_id);
		echo json_encode($supplier);
	}
	function get_supplier2($supplier_id){
		$supplier = $this->suppliers_model->get_supplier2($supplier_id);
		echo json_encode($supplier);
	}
	function update(){
		$supplier_id = $this->input->post('supplier_id');

		$q = $this->suppliers_model->supplier_update_exists();

		if($q['res'] == true){
			$data = array(
				'supplier_name' => $this->input->post('supplier_name'),
				'supplier_code' => $this->input->post('supplier_code'),
				'phone_number' => $this->input->post('phone_number'),
				'email_address' => $this->input->post('email_address'),
				'website' => $this->input->post('website'),
				'postal_address' => $this->input->post('postal_address'),
				'postal_code' => $this->input->post('postal_code'),
				'region_id' => $this->input->post('city_id'),
				'country_id' => $this->input->post('country_id'),
				'supplier_note' => $this->input->post('supplier_note'),
				'contact_person_first_name' => $this->input->post('contact_person_first_name'),
				'contact_person_last_name' => $this->input->post('contact_person_last_name'),
				'contact_person_mobile_number' => $this->input->post('contact_person_mobile_number'),
				'contact_person_email_address' => $this->input->post('contact_person_email_address'),
				'sort_key' => $this->input->post('sort_key'),
				'is_active' => $this->input->post('is_active')
			);	
			$q = $this->suppliers_model->update($data,$supplier_id);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
		echo json_encode($resp);

	}
	function delete($supplier_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->suppliers_model->delete($supplier_id);
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
	function delete_logo($supplier_id) {
		if($this->session->userdata('bgs_be_active')){
			$q = $this->suppliers_model->delete_logo($supplier_id);
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
			$q = $this->suppliers_model->delete_bulk($ids);
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