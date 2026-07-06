<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Contact_information extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/contact_information_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			$data['contact_information'] = $this->contact_information_model->get_contact_information();
			$data['contact_information_exists'] = $this->contact_information_model->contact_information_exists();

			$data['cur'] = 'Company Setup';
			$data['cur_sub'] = 'Contact Information';
			$data['cur_cur_sub'] = '';

			$data['page_title'] = 'Contact Information | ';
			$data['main_content'] = 'be/contact_information';
			$this->load->view('be/includes/template',$data);
        } 
		else {
            redirect('be/auth');
		}
	}
	function save(){
		$data = array(
			'company_name' => $this->input->post('company_name'),
			'email_address' => $this->input->post('email_address'),			
			'phone_number' => $this->input->post('phone_number'),
			'mobile_number' => $this->input->post('mobile_number'),
			'postal_address' => $this->input->post('postal_address'),
			'postal_code' => $this->input->post('postal_code'),
			'physical_address' => $this->input->post('physical_address'),
			'website' => $this->input->post('website'),
			'pin_number' => $this->input->post('pin_number'),
			'registration_number' => $this->input->post('registration_number'),
			'sm_facebook' => $this->input->post('sm_facebook'),
			'sm_twitter' => $this->input->post('sm_twitter'),
			'sm_linkedin' => $this->input->post('sm_linkedin'),
			'sm_youtube' => $this->input->post('sm_youtube'),
			'sm_instagram' => $this->input->post('sm_instagram'),
			'created_on' => date("Y-m-d H:i:s", time())
		);	
		$q = $this->contact_information_model->save($data);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);
	}
	function set_contact_logo(){

		if ($this->contact_information_model->contact_information_exists() == true){
			$q = $this->contact_information_model->upload_contact_logo();
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}			
		}else{
			$resp = array('status' => 'ERR','message' => 'Please set Contact Information first before setting Logo.');
		}
		echo json_encode($resp);
	}
}