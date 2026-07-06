<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('pos/auth_model');
		$this->load->model('pos/main_model');
	}

	function index(){
		if($this->session->userdata('bgs_pos_active')){
            redirect('pos');
        }else{
			redirect('pos/auth/login');
		}
	}
	function login() {
		if($this->session->userdata('bgs_pos_active')){
            redirect('pos');
        }else{
			$this->load->view('pos/login');
		}
	}
	function outlet_select() {
		if($this->session->userdata('bgs_pos_active')){
			// if($this->session->userdata('pos_outlet_id')){
			// 	redirect('pos');
			// } else {
				$q = $this->auth_model->get_user_outlets();
		        $data['user_outlets'] = $q['records'];
		        $data['num_user_outlets'] = $q['record_count'];
				$this->load->view('pos/outlet_select', $data);
			//}
        }else{
			redirect('pos/auth/login');
		}
	}
	function select_outlet($outlet_id) {
		if($this->session->userdata('bgs_pos_active')){
			$this->session->set_userdata('pos_outlet_id', $outlet_id);
			$resp = array('status' => 'SUCCESS','message' => '');
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue.');
		}
		echo json_encode($resp);
	}

	function reset_password() {
		if($this->session->userdata('bgs_pos_active')){
            redirect('pos');
        }else{
			$this->load->view('pos/reset_password');
		}
	}

	function profile() {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				$data['cur'] = 'Auth';
				$data['cur_sub'] = 'Profile';
				$data['cur_cur_sub'] = '';

				$data['active_outlet'] = $this->main_model->get_active_outlet();

				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['profile'] = $this->auth_model->get_profile();

				// $data['customers'] = $this->main_model->get_customers_list();

				$data['page_title'] = 'Account Profile | ';
				$data['main_content'] = 'pos/profile';
				$this->load->view('pos/includes/template',$data);
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function update_profile(){
		if($this->session->userdata('bgs_pos_active')){
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

	function change_password() {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				$data['cur'] = 'Auth';
				$data['cur_sub'] = 'Change Password';
				$data['cur_cur_sub'] = '';

				$data['active_outlet'] = $this->main_model->get_active_outlet();
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['profile'] = $this->auth_model->get_profile();

				$data['page_title'] = 'Change Password | ';
				$data['main_content'] = 'pos/change_password';
				$this->load->view('pos/includes/template',$data);
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function submit_change_password(){
		if($this->session->userdata('bgs_pos_active')){
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

	function submit_reset_password(){
		$q = $this->auth_model->reset_password();
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
		echo json_encode($resp);
	}
	
	

	// }
	function validate_login(){
		$q = $this->auth_model->validate_login();

		if($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}
		else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
		echo json_encode($resp);
	}

	function logout(){
		//$this->session->sess_destroy();
        $this->session->unset_userdata('bgs_pos_active');
        $this->session->unset_userdata('pos_system_user_id');
        $this->session->unset_userdata('pos_user_email_address');
        $this->session->unset_userdata('pos_user_first_name');
        $this->session->unset_userdata('pos_user_last_name');
        $this->session->unset_userdata('pos_user_profile_picture');
        $this->session->unset_userdata('pos_user_role');
        $this->session->unset_userdata('pos_outlet_id');
        $this->session->unset_userdata('pos_user_is_super_admin');
		$this->index();
	}

	function recover(){
		if($this->session->userdata('bgs_pos_active')){
            redirect('be');
        }
		else{
			$this->load->view('pos/recover');
		}		
	}
	function validate_recover_password(){
		$q = $this->auth_model->recover_password();
		if($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
		echo json_encode($resp);	
	}

	function access_denied() {
		if($this->session->userdata('bgs_pos_active')){
			if($this->session->userdata('pos_outlet_id')) {
				$data['cur'] = '';
				$data['cur_sub'] = '';
				$data['cur_cur_sub'] = '';

				$data['active_outlet'] = $this->main_model->get_active_outlet();
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['page_title'] = 'Access Denied | ';
				$data['main_content'] = 'pos/access_denied';
				$this->load->view('pos/includes/template',$data);
			} else {
				redirect('pos/auth/outlet_select');
			}
        }
		else{
			redirect('be/auth');
		}		
	}
}
