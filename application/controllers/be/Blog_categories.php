<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Blog_categories extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/blog_categories_model');
		$this->load->model('be/auth_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('blog_categories_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'Blog';
				$data['cur_sub'] = 'Blog Categories';
				$data['cur_cur_sub'] = '';

				$data['sbr_blog_categories_add'] = $this->auth_model->validate_user_access('blog_categories_add', $this->session->userdata('system_user_id'));
				$data['sbr_blog_categories_edit'] = $this->auth_model->validate_user_access('blog_categories_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'blog Categories | ';
				$data['main_content'] = 'be/blog_categories';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save(){
		$blog_category_sku = $this->blog_categories_model->get_blog_category_sku();
		$blog_category_reference_id = url_title($this->input->post('blog_category_name'),'-',TRUE) . '-' . strtolower($blog_category_sku);

		$data = array(
			'blog_category_reference_id' => $blog_category_reference_id,
			'blog_category_sku_code' => $blog_category_sku,			
			'blog_category_name' => $this->input->post('blog_category_name'),
			'sort_key' => $this->input->post('sort_key'),
			'description' => $this->input->post('description')
		);	
		$blog_category_name = $this->input->post('blog_category_name');
		if($this->blog_categories_model->blog_category_exists($blog_category_name) == false){
			$q = $this->blog_categories_model->save($data);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'This Blog Category has already been defined');
		}
			
		echo json_encode($resp);

	}
	function loadjs(){
		$data['blog_categories'] = $this->blog_categories_model->get_blog_categories_list();
		$data['sbr_blog_categories_delete'] = $this->auth_model->validate_user_access('blog_categories_delete', $this->session->userdata('system_user_id'));
		$this->load->view('be/jsloads/blog_categories',$data);
	}
	function get_blog_category($blog_category_id){
		$blog_category = $this->blog_categories_model->get_blog_category($blog_category_id);
		echo json_encode($blog_category);
	}
	function update(){
		$blog_category_id = $this->input->post('blog_category_id');
		$blog_category_name = $this->input->post('blog_category_name');

		$blog_category_sku = $this->blog_categories_model->get_blog_category_sku_code($blog_category_id);		
		$blog_category_reference_id = url_title($this->input->post('blog_category_name'),'-',TRUE) . '-' . strtolower($blog_category_sku);


		$data = array(
			'blog_category_reference_id'		=> $blog_category_reference_id,
            'blog_category_sku_code'			=> $blog_category_sku,			
			'blog_category_name' 				=> $this->input->post('blog_category_name'),
			'sort_key' => $this->input->post('sort_key'),
			'description' => $this->input->post('description')
		);	
		if($this->blog_categories_model->blog_category_update_exists($blog_category_id,$blog_category_name) == false){
			$q = $this->blog_categories_model->update($data,$blog_category_id);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'This Blog Category has already been defined');
		}
		echo json_encode($resp);

	}
	function delete($blog_category_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->blog_categories_model->delete($blog_category_id);
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