<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Promo_codes extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/promo_codes_model');
		$this->load->model('be/auth_model');
	}

	function index(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('promo_codes_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'Promotions';
				$data['cur_sub'] = 'Promo Codes';
				$data['cur_cur_sub'] = '';

				$data['sbr_promo_codes_add'] = $this->auth_model->validate_user_access('promo_codes_add', $this->session->userdata('system_user_id'));
				$data['sbr_promo_codes_edit'] = $this->auth_model->validate_user_access('promo_codes_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Promo Codes | ';
				$data['main_content'] = 'be/promo_codes';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save(){

		$q = $this->promo_codes_model->validate_add_promo_code();

		if($q['res'] == true){

			$promo_mode = $this->input->post('promo_mode');
			if ($promo_mode == 'Admin Listing') {
				$promo_value = 0;
			} else {
				$promo_value = $this->input->post('promo_value');
			}

			$data = array(
				'promo_code_name' => $this->input->post('promo_code_name'),
				'promo_code' => $this->input->post('promo_code'),
				'promo_mode' => $this->input->post('promo_mode'),
				'promo_value' => $promo_value,
				'sort_key' => $this->input->post('sort_key'),
				'is_active' => $this->input->post('is_active'),
				'created_on' => date("Y-m-d H:i:s", time())
			);	

			$q = $this->promo_codes_model->save($data);

			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
		echo json_encode($resp);
	}

	function loadjs(){
		$data['promo_codes'] = $this->promo_codes_model->get_promo_codes_list();
		$data['sbr_promo_codes_delete'] = $this->auth_model->validate_user_access('promo_codes_delete', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/promo_codes',$data);
	}
	function get_promo_code2($promo_code_id){
		$promo_code = $this->promo_codes_model->get_promo_code2($promo_code_id);
		echo json_encode($promo_code);
	}
	function update(){
		$promo_code_id = $this->input->post('promo_code_id');

		$q = $this->promo_codes_model->validate_update_promo_code($promo_code_id);

		if($q['res'] == true){

			$promo_mode = $this->input->post('promo_mode');
			if ($promo_mode == 'Admin Listing') {
				$promo_value = 0;
			} else {
				$promo_value = $this->input->post('promo_value');
			}

			$data = array(
				'promo_code_name' => $this->input->post('promo_code_name'),
				'promo_code' => $this->input->post('promo_code'),
				'promo_mode' => $this->input->post('promo_mode'),
				'promo_value' => $promo_value,
				'sort_key' => $this->input->post('sort_key'),
				'is_active' => $this->input->post('is_active')
			);	

			$q = $this->promo_codes_model->update($data,$promo_code_id);

			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}

		echo json_encode($resp);

	}
	function delete($promo_code_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->promo_codes_model->delete($promo_code_id);
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
			$q = $this->promo_codes_model->delete_bulk($ids);
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