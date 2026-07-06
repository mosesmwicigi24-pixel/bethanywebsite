<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_roles extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/user_roles_model');
		$this->load->model('be/auth_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('user_roles_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['user_roles'] = $this->user_roles_model->get_user_roles_list();
				$data['page_title'] = 'User Roles | ';

				$data['cur'] = 'System Users';
				$data['cur_sub'] = 'User Roles';
				$data['cur_cur_sub'] = '';

				$data['sbr_user_roles_add'] = $this->auth_model->validate_user_access('user_roles_add', $this->session->userdata('system_user_id'));
				$data['sbr_user_roles_edit'] = $this->auth_model->validate_user_access('user_roles_edit', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/user_roles';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function add(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('user_roles_add', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'System Users';
				$data['cur_sub'] = 'User Roles';
				$data['cur_cur_sub'] = '';

				$data['sbr_user_roles_add'] = $this->auth_model->validate_user_access('user_roles_add', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'New User Role | ';
				$data['main_content'] = 'be/user_role_add';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save(){		
		$user_role_name = $this->input->post('user_role_name');
		if($this->user_roles_model->user_role_exists($user_role_name) == false){
			$use_ssl = $this->input->post('use_ssl');
			if($use_ssl == 'on'){
				$use_ssl = 1;
			}else{
				$use_ssl = 0;
			}
			$data = array(
				'user_role_name' => $this->input->post('user_role_name'),
				'user_role_description' => $this->input->post('user_role_description'),
				'sort_key' => $this->input->post('sort_key'),
				'is_active' => $this->input->post('is_active'),
				'created_on' => date("Y-m-d H:i:s", time())
			);	
			$q = $this->user_roles_model->save($data);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => 'User Role added successfully.');
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'This User Role has already been defined.');
		}
			
		echo json_encode($resp);
	}
	function load_js(){
		$data['user_roles'] = $this->user_roles_model->get_user_roles_list();
		$data['sbr_user_roles_delete'] = $this->auth_model->validate_user_access('user_roles_delete', $this->session->userdata('system_user_id'));
		$this->load->view('be/jsloads/user_roles',$data);

	}
	function edit($user_role_id){
		if($this->session->userdata('bgs_be_active')) {
			$data['cur'] = 'System Users';
			$data['cur_sub'] = 'User Roles';
			$data['cur_cur_sub'] = '';

			//$data['customer_groups'] = $this->customers_model->get_customer_groups_list();
			//$data['customers'] = $this->customers_model->get_customers_list();
			//$data['default_currency'] = $this->currencies_model->get_default_currency();
			//$data['countries'] = $this->locations_model->get_countries_list();

			$data['user_role'] = $this->user_roles_model->get_user_role2($user_role_id);

			$data['sbr_user_roles_edit'] = $this->auth_model->validate_user_access('user_roles_edit', $this->session->userdata('system_user_id'));

			$data['page_title'] = 'Edit User Role | ';
			$data['main_content'] = 'be/user_role_add';
			$this->load->view('be/includes/template',$data);
        } 
		else {
            redirect('be/auth');
		}
	}
	function get_user_role($user_role_id){
		$user_role = $this->user_roles_model->get_user_role($user_role_id);
		echo json_encode($user_role);
	}
	function update(){
		$user_role_id = $this->input->post('user_role_id');
		$user_role_name = $this->input->post('user_role_name');
		if($this->user_roles_model->user_role_update_exists($user_role_id,$user_role_name) == false){
			$use_ssl = $this->input->post('use_ssl');
			if($use_ssl == 'on'){
				$use_ssl = 1;
			}else{
				$use_ssl = 0;
			}
			$data = array(
				'user_role_name' => $this->input->post('user_role_name'),
				'user_role_description' => $this->input->post('user_role_description'),
				'sort_key' => $this->input->post('sort_key'),
				'is_active' => $this->input->post('is_active')
			);	

			$q = $this->user_roles_model->update($data,$user_role_id);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => 'User Role updated successfully.');
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'This User Role has already been defined.');
		}
		echo json_encode($resp);
	}
	function delete($user_role_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->user_roles_model->delete($user_role_id);
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