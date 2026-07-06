<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class How_to_shop extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/how_to_shop_model');
		$this->load->model('be/auth_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('how_to_shop_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['how_to_shop'] = $this->how_to_shop_model->get_how_to_shop();
				$data['how_to_shop_exists'] = $this->how_to_shop_model->how_to_shop_exists();

				$data['cur'] = 'CMS Content';
				$data['cur_sub'] = 'How To Shop';
				$data['cur_cur_sub'] = '';

				$data['sbr_how_to_shop_edit'] = $this->auth_model->validate_user_access('how_to_shop_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'How To Shop | ';
				$data['main_content'] = 'be/how_to_shop';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save(){
		$data = array(
			'how_to_shop' => $this->input->post('how_to_shop')
		);	
		$q = $this->how_to_shop_model->save($data);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);
	}
}