<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Product_sizes extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/product_sizes_model');
		$this->load->model('be/auth_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('product_sizes_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {

				$data['product_sizes'] = $this->product_sizes_model->get_product_sizes_list();
				$data['page_title'] = 'Product Sizes | ';

				$data['cur'] = 'Products';
				$data['cur_sub'] = 'Product Sizes';
				$data['cur_cur_sub'] = '';

				$data['sbr_product_sizes_view'] = $this->auth_model->validate_user_access('product_sizes_view', $this->session->userdata('system_user_id'));
				$data['sbr_product_sizes_add'] = $this->auth_model->validate_user_access('product_sizes_add', $this->session->userdata('system_user_id'));
				$data['sbr_product_sizes_edit'] = $this->auth_model->validate_user_access('product_sizes_edit', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/product_sizes';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save(){
		$data = array(
			'product_size_name' => $this->input->post('product_size_name'),
			'product_size_code' => $this->input->post('product_size_code'),
			'sort_key' => $this->input->post('sort_key'),
			'is_active' => $this->input->post('is_active')
		);	
		$product_size_name = $this->input->post('product_size_name');
		if($this->product_sizes_model->product_size_exists($product_size_name) == false){
			$q = $this->product_sizes_model->save($data);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This Product Size has already been defined.');
		}
			
		echo json_encode($resp);
	}
	function load_js(){
		$data['product_sizes'] = $this->product_sizes_model->get_product_sizes_list();
		$data['sbr_product_sizes_delete'] = $this->auth_model->validate_user_access('product_sizes_delete', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/product_sizes',$data);

	}
	function get_product_size2($product_size_id){
		$product_size = $this->product_sizes_model->get_product_size2($product_size_id);
		echo json_encode($product_size);
	}
	function update(){
		$product_size_id = $this->input->post('product_size_id');
		$product_size_name = $this->input->post('product_size_name');
		$data = array(
			'product_size_name' => $this->input->post('product_size_name'),
			'product_size_code' => $this->input->post('product_size_code'),
			'sort_key' => $this->input->post('sort_key'),
			'is_active' => $this->input->post('is_active')
		);	
		if($this->product_sizes_model->product_size_update_exists($product_size_id,$product_size_name) == false){
			$q = $this->product_sizes_model->update($data,$product_size_id);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => '<i class="icon-cancel-circle2"></i> This Product Size has already been defined.');
		}
		echo json_encode($resp);
	}
	function delete($product_size_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->product_sizes_model->delete($product_size_id);
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