<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller {

	function __construct(){
		parent::__construct();
		$this->load->model('be/auth_model');
	}

	function index(){
		if($this->session->userdata('bgs_be_active')){
            redirect('be');
        }
		else{
			if ($this->auth_model->check_super_admin() == false){
				$this->load->view('be/register');
			}else{
				$this->load->view('be/login');
			}
		}
	}
	function validate_register(){
		if($this->auth_model->user_exists($this->input->post('register_email_address')) == false){
			$user_password = md5($this->input->post('register_password'));
			$data = array(
				'first_name' => $this->input->post('register_first_name'),
				'last_name' => $this->input->post('register_last_name'),
				'email_address' => $this->input->post('register_email_address'),
				'phone_number' => $this->input->post('register_phone_number'),
				'user_password' => $user_password,
				'is_super_admin' => 1
			);
			$q = $this->auth_model->register_user($data);
			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'The Email Address you entered already exists. Please enter a different Email Address.');
		}
		echo json_encode($resp);

	}
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
        $this->session->unset_userdata('bgs_be_active');
        $this->session->unset_userdata('system_user_id');
        $this->session->unset_userdata('user_email_address');
        $this->session->unset_userdata('user_first_name');
        $this->session->unset_userdata('user_last_name');
        $this->session->unset_userdata('user_profile_picture');
        $this->session->unset_userdata('user_role');
		$this->index();
	}

	function recover(){
		if($this->session->userdata('bgs_be_active')){
            redirect('be');
        }
		else{
			$this->load->view('be/recover');
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
		if($this->session->userdata('bgs_be_active')){
			//$this->load->view('be/access_denied');
			$data['cur'] = '';
			$data['cur_sub'] = '';
			$data['cur_cur_sub'] = '';

			$data['page_title'] = 'Access Denied | ';
			$data['main_content'] = 'be/access_denied';
			$this->load->view('be/includes/template',$data);

        }
		else{
			redirect('be/auth');
		}		
	}
}
