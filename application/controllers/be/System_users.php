<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class System_users extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/system_users_model');
		$this->load->model('be/user_roles_model');
		$this->load->model('be/locations_model');
		$this->load->model('be/currencies_model');
		$this->load->model('be/outlets_model');
		$this->load->model('be/auth_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('system_users_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['system_users'] = $this->system_users_model->get_system_users_list();
				$data['user_roles'] = $this->user_roles_model->get_user_roles_list();
				$data['page_title'] = 'System Users | ';

				$data['cur'] = 'System Users';
				$data['cur_sub'] = 'System Users';
				$data['cur_cur_sub'] = '';

				$data['outlets'] = $this->outlets_model->get_outlets_list();

				$data['sbr_system_users_add'] = $this->auth_model->validate_user_access('system_users_add', $this->session->userdata('system_user_id'));
				$data['sbr_system_users_edit'] = $this->auth_model->validate_user_access('system_users_edit', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/system_users';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function add(){
		if($this->session->userdata('bgs_be_active')) {
			$data['cur'] = 'System Users';
			$data['cur_sub'] = 'System Users';
			$data['cur_cur_sub'] = '';

			//$data['customer_groups'] = $this->customers_model->get_customer_groups_list();
			//$data['customers'] = $this->customers_model->get_customers_list();
			$data['user_roles'] = $this->user_roles_model->get_user_roles_list();
			$data['default_currency'] = $this->currencies_model->get_default_currency();
			$data['countries'] = $this->locations_model->get_countries_list();

			$data['page_title'] = 'New System User | ';
			$data['main_content'] = 'be/system_user_add';
			$this->load->view('be/includes/template',$data);
        } 
		else {
            redirect('be/auth');
		}
	}



	function save(){		
		$email_address = $this->input->post('email_address');
		if($this->system_users_model->system_user_exists($email_address) == false){
			$data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'user_role_id' => $this->input->post('user_role_id'),
				'user_password' => md5($this->input->post('user_password')),
				'email_address' => $this->input->post('email_address'),
				'phone_number' => $this->input->post('phone_number'),
				'address' => $this->input->post('address'),
				'sort_key' => $this->input->post('sort_key'),
				'is_active' => $this->input->post('is_active'),
				'created_on' => date("Y-m-d H:i:s", time())
			);	
			$q = $this->system_users_model->save($data);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => 'System User added successfully.');
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'This System User has already been defined.');
		}
			
		echo json_encode($resp);
	}
	function load_js(){
		$data['system_users'] = $this->system_users_model->get_system_users_list();
		$data['sbr_system_users_delete'] = $this->auth_model->validate_user_access('system_users_delete', $this->session->userdata('system_user_id'));
		$data['sbr_system_users_edit'] = $this->auth_model->validate_user_access('system_users_edit', $this->session->userdata('system_user_id'));
		$this->load->view('be/jsloads/system_users',$data);

	}
	function get_system_user($system_user_id){
		$system_user = $this->system_users_model->get_system_user($system_user_id);
		echo json_encode($system_user);
	}
	function update(){
		$system_user_id = $this->input->post('system_user_id');
		$email_address = $this->input->post('email_address');
		if($this->system_users_model->system_user_update_exists($system_user_id,$email_address) == false){
			$data = array(
				'first_name' => $this->input->post('first_name'),
				'last_name' => $this->input->post('last_name'),
				'user_role_id' => $this->input->post('user_role_id'),
				'email_address' => $this->input->post('email_address'),
				'phone_number' => $this->input->post('phone_number'),
				'address' => $this->input->post('address'),
				'sort_key' => $this->input->post('sort_key'),
				'is_active' => $this->input->post('is_active')
			);	

			$q = $this->system_users_model->update($data,$system_user_id);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => 'System User updated successfully.');
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'This System User has already been defined.');
		}
		echo json_encode($resp);
	}
	function delete($system_user_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->system_users_model->delete($system_user_id);
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

	function change_password(){
		$system_user_id = $this->input->post('system_user_id');

		$data = array(
			'user_password' => md5($this->input->post('user_password'))
		);	

		$q = $this->system_users_model->change_password($data,$system_user_id);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
		echo json_encode($resp);
	}	
	

}