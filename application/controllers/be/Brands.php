<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Brands extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/brands_model');
		$this->load->model('be/auth_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('brands_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {

				$data['cur'] = 'Products';
				$data['cur_sub'] = 'Brands';
				$data['cur_cur_sub'] = '';

				$data['sbr_brands_view'] = $this->auth_model->validate_user_access('brands_view', $this->session->userdata('system_user_id'));
				$data['sbr_brands_add'] = $this->auth_model->validate_user_access('brands_add', $this->session->userdata('system_user_id'));
				$data['sbr_brands_edit'] = $this->auth_model->validate_user_access('brands_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Brands | ';
				$data['main_content'] = 'be/brands';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save(){
		$brand_sku = $this->brands_model->get_brand_sku();
		$brand_reference_id = url_title($this->input->post('brand_name'),'-',TRUE) . '-' . strtolower($brand_sku);

		$data = array(
			'brand_reference_id' => $brand_reference_id,
			'brand_sku_code' => $brand_sku,			
			'brand_name' => $this->input->post('brand_name'),
			'is_active' => $this->input->post('is_active'),
			'sort_key' => $this->input->post('sort_key'),
			'seo_title' => $this->input->post('seo_title'),
			'seo_description' => $this->input->post('seo_description'),
			'seo_keywords' => $this->input->post('seo_keywords'),
			'created_on' => date("Y-m-d H:i:s", time())
		);	
		$brand_name = $this->input->post('brand_name');
		if($this->brands_model->brand_exists($brand_name) == false){
			$q = $this->brands_model->save($data);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This Brand has already been defined');
		}
			
		echo json_encode($resp);

	}
	function load_js(){
		$data['brands'] = $this->brands_model->get_brands_list();
		$data['sbr_brands_delete'] = $this->auth_model->validate_user_access('brands_delete', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/brands',$data);
	}
	function get_brand($brand_id){
		$brand = $this->brands_model->get_brand($brand_id);
		echo json_encode($brand);
	}
	function get_brand2($brand_id){
		$brand = $this->brands_model->get_brand2($brand_id);
		echo json_encode($brand);
	}
	function update(){
		$brand_id = $this->input->post('brand_id');
		$brand_name = $this->input->post('brand_name');

		$brand_sku = $this->brands_model->get_brand_sku_code($brand_id);		
		$brand_reference_id = url_title($this->input->post('brand_name'),'-',TRUE) . '-' . strtolower($brand_sku);

		$data = array(
			'brand_reference_id'		=> $brand_reference_id,
            'brand_sku_code'			=> $brand_sku,			
			'brand_name' => $this->input->post('brand_name'),
			'is_active' => $this->input->post('is_active'),
			'sort_key' => $this->input->post('sort_key'),
			'seo_title' => $this->input->post('seo_title'),
			'seo_description' => $this->input->post('seo_description'),
			'seo_keywords' => $this->input->post('seo_keywords')
		);	
		if($this->brands_model->brand_update_exists($brand_id,$brand_name) == false){
			$q = $this->brands_model->update($data,$brand_id);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This Brand has already been defined');
		}
		echo json_encode($resp);

	}
	function delete($brand_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->brands_model->delete($brand_id);
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
	function delete_logo($brand_id) {
		if($this->session->userdata('bgs_be_active')){
			$q = $this->brands_model->delete_logo($brand_id);
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
	function delete_bulk() {
		if($this->session->userdata('bgs_be_active')){
			$ids = $this->input->post('ids');
			$q = $this->brands_model->delete_bulk($ids);
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