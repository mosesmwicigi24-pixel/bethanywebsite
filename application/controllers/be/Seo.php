<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Seo extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/seo_model');
		$this->load->model('be/auth_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('seo_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['seo'] = $this->seo_model->get_seo();
				$data['seo_exists'] = $this->seo_model->seo_exists();

				$data['cur'] = 'SEO';
				$data['cur_sub'] = 'Search Engine Optimization';
				$data['cur_cur_sub'] = '';

				$data['sbr_seo_edit'] = $this->auth_model->validate_user_access('seo_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Search Engine Optimization | ';
				$data['main_content'] = 'be/seo';
				$this->load->view('be/includes/template',$data);
			}
        }
		else {
            redirect('be/auth');
		}
	}
	function save(){
		if ( ! $this->session->userdata('bgs_be_active') ||
		     $this->auth_model->validate_user_access('seo_edit', $this->session->userdata('system_user_id')) == false) {
			echo json_encode(array('status' => 'ERR', 'message' => 'Access denied.'));
			return;
		}
		$data = array(
			'site_title' => $this->input->post('site_title'),
			'site_description' => $this->input->post('site_description'),
			'site_keywords' => $this->input->post('site_keywords'),
			'sitemap_link' => $this->input->post('sitemap_link')
		);	
		$q = $this->seo_model->save($data);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);
	}

}