<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/blog_model');
		$this->load->model('be/blog_categories_model');
		$this->load->model('be/auth_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('blog_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'Blog';
				$data['cur_sub'] = 'Blog';
				$data['cur_cur_sub'] = '';

				$data['sbr_blog_add'] = $this->auth_model->validate_user_access('blog_add', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Blog | ';
				$data['main_content'] = 'be/blog';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function add(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('blog_add', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'Blog';
				$data['cur_sub'] = 'Blog';
				$data['cur_cur_sub'] = '';

				$data['blog_categories'] = $this->blog_categories_model->get_blog_categories_list();

				$data['sbr_blog_add'] = $this->auth_model->validate_user_access('blog_add', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'New Article | ';
				$data['main_content'] = 'be/blog_article_add';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save(){
		$blog_article_sku = $this->blog_model->get_blog_article_sku();
		$blog_article_reference_id = url_title($this->input->post('blog_article_title'),'-',TRUE) . '-' . strtolower($blog_article_sku);

		$data = array(
			'blog_article_reference_id' => $blog_article_reference_id,
			'blog_article_sku_code' => $blog_article_sku,			
			'blog_article_title' => $this->input->post('blog_article_title'),
			'blog_article_author' => $this->input->post('blog_article_author'),	
			'blog_article_date' => $this->input->post('blog_article_date'),	
			'blog_article_content' => $this->input->post('blog_article_content'),
			'is_published' => $this->input->post('is_published'),	
			'seo_description' => $this->input->post('seo_description'),
			'seo_keywords' => $this->input->post('seo_keywords')
		);	
		$q = $this->blog_model->save($data);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);

	}
	function loadjs(){
		$data['blog'] = $this->blog_model->get_blog_list();
		$data['sbr_blog_edit'] = $this->auth_model->validate_user_access('blog_edit', $this->session->userdata('system_user_id'));
		$data['sbr_blog_delete'] = $this->auth_model->validate_user_access('blog_delete', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/blog',$data);
	}
	function edit($blog_article_id){
		if($this->session->userdata('bgs_be_active')) {
			$data['cur'] = 'Blog';
			$data['cur_sub'] = 'Blog';
			$data['cur_cur_sub'] = '';

			$data['blog_article'] = $this->blog_model->get_blog_article($blog_article_id);
			$data['blog_article_categories'] = $this->blog_model->get_blog_article_categories($blog_article_id);
			
			$data['blog_categories'] = $this->blog_categories_model->get_blog_categories_list();

			$data['sbr_blog_edit'] = $this->auth_model->validate_user_access('blog_edit', $this->session->userdata('system_user_id'));

			$data['page_title'] = 'Edit Blog Article | ';
			$data['main_content'] = 'be/blog_article_add';
			$this->load->view('be/includes/template',$data);
        } 
		else {
            redirect('be/auth');
		}
	}
	function update(){
		$blog_article_id = $this->input->post('blog_article_id');
		$blog_article_title = $this->input->post('blog_article_title');

		$blog_article_sku = $this->blog_model->get_blog_article_sku_code($blog_article_id);		
		$blog_article_reference_id = url_title($this->input->post('blog_article_title'),'-',TRUE) . '-' . strtolower($blog_article_sku);


		$data = array(
			'blog_article_reference_id'		=> $blog_article_reference_id,
            'blog_article_sku_code'			=> $blog_article_sku,			
			'blog_article_title' => $this->input->post('blog_article_title'),
			'blog_article_author' => $this->input->post('blog_article_author'),	
			'blog_article_date' => $this->input->post('blog_article_date'),	
			'blog_article_content' => $this->input->post('blog_article_content'),
			'is_published' => $this->input->post('is_published'),
			'seo_description' => $this->input->post('seo_description'),
			'seo_keywords' => $this->input->post('seo_keywords')	
		);	
		$q = $this->blog_model->update($data,$blog_article_id);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
		echo json_encode($resp);

	}
	function delete($blog_article_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->blog_model->delete($blog_article_id);
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

	function delete_cover_image($blog_article_id) {
		if($this->session->userdata('bgs_be_active')){
			$q = $this->blog_model->delete_cover_image($blog_article_id);
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