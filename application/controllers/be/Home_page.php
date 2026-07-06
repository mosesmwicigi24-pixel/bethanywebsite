<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home_page extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/home_page_model');
		$this->load->model('be/product_categories_model');
		$this->load->model('be/auth_model');
	}

	//TOP CATEGORIES
	function top_categories() {
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('top_categories_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'CMS Content';
				$data['cur_sub'] = 'Home Page';
				$data['cur_cur_sub'] = 'Top Categories';

				$data['product_categories'] = $this->product_categories_model->get_nested_product_categories();

				$data['sbr_top_categories_add'] = $this->auth_model->validate_user_access('top_categories_add', $this->session->userdata('system_user_id'));
				$data['sbr_top_categories_edit'] = $this->auth_model->validate_user_access('top_categories_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Home Top Categories | ';
				$data['main_content'] = 'be/home_top_categories';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function loadjs_top_categories(){
		$data['home_top_product_categories'] = $this->home_page_model->get_home_top_product_categories();
		$data['sbr_top_categories_delete'] = $this->auth_model->validate_user_access('top_categories_delete', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/home_top_product_categories',$data);
	}

	function save_home_top_product_category(){
		$data = array(
			'product_category_id' => $this->input->post('home_top_product_category_id'),
			'position' => $this->input->post('position'),
			'created_on' => date("Y-m-d H:i:s", time())
		);	
		$q = $this->home_page_model->save_home_top_product_category($data);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
		echo json_encode($resp);
	}

	function get_home_top_product_category2($home_top_product_category_id){
		$home_top_product_category = $this->home_page_model->get_home_top_product_category2($home_top_product_category_id);
		echo json_encode($home_top_product_category);
	}

	function update_home_top_product_category() {
		$home_top_product_category_id = $this->input->post('home_top_product_category_id');

		$data = array(
		'product_category_id' => $this->input->post('ht_product_category_id'),
		'position' => $this->input->post('position'),
		);	
		$q = $this->home_page_model->update_home_top_product_category($data,$home_top_product_category_id);

		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}

		echo json_encode($resp);
	}

	function delete_home_top_product_category($home_top_product_category_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->home_page_model->delete_home_top_product_category($home_top_product_category_id);
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

	//FEATURED CATEGORIES
	function featured_categories() {
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('featured_categories_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'CMS Content';
				$data['cur_sub'] = 'Home Page';
				$data['cur_cur_sub'] = 'Featured Categories';

				$data['product_categories'] = $this->product_categories_model->get_nested_product_categories();
				$data['home_featured_product_categories'] = $this->home_page_model->get_home_featured_product_categories();

				$data['sbr_featured_categories_edit'] = $this->auth_model->validate_user_access('featured_categories_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Home Featured Categories | ';
				$data['main_content'] = 'be/home_featured_categories';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function save_home_featured_product_categories() {
		$q = $this->home_page_model->save_home_featured_product_categories();
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
		echo json_encode($resp);	
	}

	//PROMO BANNERS
	function promo_banners(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('promo_banners_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'CMS Content';
				$data['cur_sub'] = 'Home Page';
				$data['cur_cur_sub'] = 'Promo Banners';

				$data['sbr_promo_banners_add'] = $this->auth_model->validate_user_access('promo_banners_add', $this->session->userdata('system_user_id'));
				$data['sbr_promo_banners_edit'] = $this->auth_model->validate_user_access('promo_banners_edit', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Home Promo Banners | ';
				$data['main_content'] = 'be/home_promo_banners';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_promo_banner(){
		$q = $this->home_page_model->save_promo_banner();
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);

	}
	function loadjs_promo_banners(){
		$data['home_promo_banners'] = $this->home_page_model->get_home_promo_banners();
		$data['sbr_promo_banners_edit'] = $this->auth_model->validate_user_access('promo_banners_edit', $this->session->userdata('system_user_id'));
		$data['sbr_promo_banners_delete'] = $this->auth_model->validate_user_access('promo_banners_delete', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/home_promo_banners',$data);
	}
	function get_home_promo_banner($home_promo_banner_id){
		$home_promo_banner = $this->home_page_model->get_home_promo_banner($home_promo_banner_id);
		echo json_encode($home_promo_banner);
	}
	function update_promo_banner(){
		$home_promo_banner_id = $this->input->post('home_promo_banner_id');

		$q = $this->home_page_model->update_promo_banner($home_promo_banner_id);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
		echo json_encode($resp);

	}
	function delete_promo_banner($home_promo_banner_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->home_page_model->delete_promo_banner($home_promo_banner_id);
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