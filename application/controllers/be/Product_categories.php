<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_categories extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/product_categories_model');
		$this->load->model('be/icons_model');
		$this->load->model('be/auth_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('product_categories_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'Products';
				$data['cur_sub'] = 'Product Categories';
				$data['cur_cur_sub'] = '';

				$data['product_categories'] = $this->product_categories_model->get_nested_product_categories();
				$data['icons'] = $this->icons_model->get_icons_list();

				$data['sbr_product_categories_view'] = $this->auth_model->validate_user_access('product_categories_view', $this->session->userdata('system_user_id'));
				$data['sbr_product_categories_add'] = $this->auth_model->validate_user_access('product_categories_add', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Product Categories | ';
				$data['main_content'] = 'be/product_categories';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	// function add(){
	// 	if($this->session->userdata('bgs_be_active')) {
	// 		$data['cur'] = 'Products';
	// 		$data['cur_sub'] = 'Product Categories';
	// 		$data['cur_cur_sub'] = '';

	// 		$data['product_categories'] = $this->product_categories_model->get_nested_product_categories();
	// 		$data['icons'] = $this->icons_model->get_icons_list();

	// 		$data['page_title'] = 'New Product Category | ';
	// 		$data['main_content'] = 'be/product_category_add';
	// 		$this->load->view('be/includes/template',$data);
 //        } 
	// 	else {
 //            redirect('be/auth');
	// 	}
	// }
	function save(){
		$product_category_sku = $this->product_categories_model->get_product_category_sku();
		$product_category_reference_id = url_title($this->input->post('product_category_name'),'-',TRUE) . '-' . strtolower($product_category_sku);

		$data = array(
			'product_category_reference_id' => $product_category_reference_id,
			'product_category_sku_code' => $product_category_sku,			
			'product_category_name' => $this->input->post('product_category_name'),
			'product_category_parent_id' => $this->input->post('product_category_parent_id'),
			'icon_id' => $this->input->post('icon_id'),
			'sort_key' => $this->input->post('sort_key'),
			'description' => $this->input->post('description'),
			'seo_title' => $this->input->post('seo_title'),
			'seo_description' => $this->input->post('seo_description'),
			'seo_keywords' => $this->input->post('seo_keywords'),
			'created_on' => date("Y-m-d H:i:s", time())
		);	
		$product_category_name = $this->input->post('product_category_name');
		if($this->product_categories_model->product_category_exists($product_category_name) == false){
			$q = $this->product_categories_model->save($data);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This Product Category has already been defined');
		}
			
		echo json_encode($resp);

	}
	function loadjs(){
		$data['product_categories'] = $this->product_categories_model->get_nested_product_categories();
		$data['sbr_product_categories_edit'] = $this->auth_model->validate_user_access('product_categories_edit', $this->session->userdata('system_user_id'));
		$data['sbr_product_categories_delete'] = $this->auth_model->validate_user_access('product_categories_delete', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/product_categories',$data);
	}
	function get_select_product_categories(){
		$product_categories = $this->product_categories_model->get_nested_product_categories();
		echo json_encode($product_categories);
	}
	function get_product_category_subcategories($product_category_parent_id){
		$product_categories = $this->product_categories_model->get_product_category_subcategories($product_category_parent_id);
		echo json_encode($product_categories);
	}
	function edit($product_category_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('product_categories_edit', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {			
				$data['cur'] = 'Products';
				$data['cur_sub'] = 'Product Categories';
				$data['cur_cur_sub'] = '';

				$data['product_category'] = $this->product_categories_model->get_product_category($product_category_id);
				$data['icons'] = $this->icons_model->get_icons_list();
				$data['product_categories'] = $this->product_categories_model->get_nested_product_categories();

				$data['page_title'] = 'Edit Product Category | ';
				$data['main_content'] = 'be/product_category_add';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function update(){
		$product_category_id = $this->input->post('product_category_id');
		$product_category_name = $this->input->post('product_category_name');

		$product_category_sku = $this->product_categories_model->get_product_category_sku_code($product_category_id);		
		$product_category_reference_id = url_title($this->input->post('product_category_name'),'-',TRUE) . '-' . strtolower($product_category_sku);


		$data = array(
			'product_category_reference_id'		=> $product_category_reference_id,
            'product_category_sku_code'			=> $product_category_sku,			
			'product_category_name' 				=> $this->input->post('product_category_name'),
			'product_category_parent_id' => $this->input->post('product_category_parent_id'),
			'icon_id' => $this->input->post('icon_id'),
			'sort_key' => $this->input->post('sort_key'),
			'description' => $this->input->post('description'),
			'seo_title' => $this->input->post('seo_title'),
			'seo_description' => $this->input->post('seo_description'),
			'seo_keywords' => $this->input->post('seo_keywords')
		);	
		if($this->product_categories_model->product_category_update_exists($product_category_id,$product_category_name) == false){
			$q = $this->product_categories_model->update($data,$product_category_id);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This Product Category has already been defined');
		}
		echo json_encode($resp);

	}
	function delete($product_category_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->product_categories_model->delete($product_category_id);
			if($q['res'] == TRUE){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);			
			}else{					
				$resp = array('status' => 'ERR','message' => $q['dt']);			
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);

	}
	function delete_bulk() {
		if($this->session->userdata('bgs_be_active')){
			$ids = $this->input->post('ids');
			$q = $this->product_categories_model->delete_bulk($ids);
			if($q['res'] == TRUE){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);			
			}else{					
				$resp = array('status' => 'ERR','message' => $q['dt']);			
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);
	}
	function delete_cover_image($product_category_id) {
		if($this->session->userdata('bgs_be_active')){
			$q = $this->product_categories_model->delete_cover_image($product_category_id);
			if($q['res'] == TRUE){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);			
			}else{					
				$resp = array('status' => 'ERR','message' => $q['dt']);			
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);
	}




}