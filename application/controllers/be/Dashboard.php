<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->model('be/dashboard_model');
	}

	function index(){
		if($this->session->userdata('bgs_be_active')) {

			$data['cur'] = 'Dashboard';
			$data['cur_sub'] = '';
			$data['cur_cur_sub'] = '';

			$data['page_title'] = 'Dashboard | ';
			$data['main_content'] = 'be/dashboard';
			$this->load->view('be/includes/template',$data);
        } 
		else {
            redirect('be/auth');
		}
	}

	function go_back() {
		redirect($_SERVER['HTTP_REFERER']);
	}



}