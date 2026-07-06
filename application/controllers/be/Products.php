<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Products extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/products_model');
		$this->load->model('be/product_categories_model');
		$this->load->model('be/product_sizes_model');
		$this->load->model('be/units_model');
		$this->load->model('be/brands_model');
		$this->load->model('be/tax_rates_model');
		$this->load->model('be/outlets_model');
		$this->load->model('be/suppliers_model');
		$this->load->model('be/currencies_model');
		$this->load->model('be/auth_model');
	}
	function index(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('products_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'Products';
				$data['cur_sub'] = 'Products';
				$data['cur_cur_sub'] = '';

				$data['product_categories'] = $this->product_categories_model->get_product_categories_list();

				$data['sbr_products_add'] = $this->auth_model->validate_user_access('products_add', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Products | ';
				$data['main_content'] = 'be/products';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function add(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('products_add', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['cur'] = 'Products';
				$data['cur_sub'] = 'New Product';
				$data['cur_cur_sub'] = '';
				$data['view_mode'] = 'Add';

				$data['product_categories'] = $this->product_categories_model->get_nested_product_categories();
				$data['product_sizes'] = $this->product_sizes_model->get_product_sizes_list();
				$data['unit_types'] = $this->units_model->get_active_unit_types();
				$data['units'] = $this->units_model->get_units_list();
				$data['brands'] = $this->brands_model->get_brands_list();
				$data['tax_rates'] = $this->tax_rates_model->get_tax_rates_list();
				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$data['suppliers'] = $this->suppliers_model->get_suppliers_list();
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$q = $this->products_model->draft_exists();
				if ($q['res'] == true){
					$product_id = $q['product_id'];
					$data['product_id'] = $product_id;
					$product = $this->products_model->get_product($product_id);
					$data['product'] = $product;
					$data['product_product_categories'] = $this->products_model->get_product_product_categories($product_id);
					$data['product_images'] = $this->products_model->get_product_images($product_id);
					$data['product_num_images'] = $this->products_model->get_product_num_images($product_id);
					$data['product_suppliers'] = $this->products_model->get_product_suppliers($product_id);
					$data['product_attributes'] = $this->products_model->get_product_attributes($product_id);
					$data['num_product_attributes'] = $this->products_model->get_num_product_attributes($product_id);
					$data['product_variations'] = $this->products_model->get_product_variations($product_id);
					$data['num_product_variations'] = $this->products_model->get_num_product_variations($product_id);
					// $data['product_product_sizes'] = $this->products_model->get_product_product_sizes($product_id);
					// $data['product_colors'] = $this->products_model->get_product_colors($product_id);
					// $data['product_attributes'] = $this->products_model->get_product_attributes($product_id);
					$data['outlet_products'] = $this->products_model->get_outlet_products($product_id);

					$unit_id = 0;
					foreach ($product as $row) {
						$unit_id = $row->unit_id;
					}

					$data['related_units'] = $this->products_model->get_edit_product_related_units($unit_id, $product_id);
					$data['num_related_units'] = $this->products_model->get_num_edit_product_related_units($unit_id, $product_id);
				}

				$data['page_title'] = 'New Product | ';
				$data['main_content'] = 'be/product_add2';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function autosave() {
		if($this->session->userdata('bgs_be_active')) {
			$q = $this->products_model->autosave();
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'],'product_id' => $q['product_id']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt'],'product_id' => '');
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue','product_id' => '');
		}
		echo json_encode($resp);
	}
	function save(){
		if($this->session->userdata('bgs_be_active')) {
			$q = $this->products_model->save();
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'],'product_id' => $q['product_id']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt'],'product_id' => '');
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue','product_id' => '');
		}
		echo json_encode($resp);
	}
	function load_js(){
		$data['products'] = $this->products_model->get_products_list();
		$data['default_currency'] = $this->currencies_model->get_default_currency();
		//$data['sbr_products_view'] = $this->auth_model->validate_user_access('products_view', $this->session->userdata('system_user_id'));
		$data['sbr_products_edit'] = $this->auth_model->validate_user_access('products_edit', $this->session->userdata('system_user_id'));
		$data['sbr_products_delete'] = $this->auth_model->validate_user_access('products_delete', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/products',$data);
	}
	function get_add_product_related_units($unit_id) {
		$product_id = $this->input->post('product_id');
		if ($product_id !== '' && $product_id !== null) {
			$data['product_id'] = $product_id;
			$data['outlets'] = $this->outlets_model->get_outlets_list();
			$data['related_units'] = $this->products_model->get_edit_product_related_units($unit_id, $product_id);
			$num_related_units = $this->products_model->get_num_edit_product_related_units($unit_id, $product_id);
			$related_units = $this->load->view('be/jsloads/edit_product_unit_related_units',$data,TRUE);
			$resp = array('num_related_units' => $num_related_units,'related_units' => $related_units);
			echo json_encode($resp);
		} else {
			$data['product_id'] = $product_id;
			$data['outlets'] = $this->outlets_model->get_outlets_list();
			$data['related_units'] = $this->products_model->get_add_product_related_units($unit_id);
			$num_related_units = $this->products_model->get_num_add_product_related_units($unit_id);
			$related_units = $this->load->view('be/jsloads/edit_product_unit_related_units',$data,TRUE);
			$resp = array('num_related_units' => $num_related_units,'related_units' => $related_units);
			echo json_encode($resp);
		}
	}
	function get_edit_product_related_units($unit_id) {
		$product_id = $this->input->post('product_id');
		$data['product_id'] = $product_id;
		$data['outlets'] = $this->outlets_model->get_outlets_list();
		$data['related_units'] = $this->products_model->get_edit_product_related_units($unit_id, $product_id);
		$num_related_units = $this->products_model->get_num_edit_product_related_units($unit_id, $product_id);
		$related_units = $this->load->view('be/jsloads/edit_product_unit_related_units',$data,TRUE);
		$resp = array('num_related_units' => $num_related_units,'related_units' => $related_units);
		echo json_encode($resp);
	}
	function edit($product_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('products_edit', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {			
				$data['cur'] = 'Products';
				$data['cur_sub'] = 'Products';
				$data['cur_cur_sub'] = '';
				$data['view_mode'] = 'Edit';

				$data['product_categories'] = $this->product_categories_model->get_nested_product_categories();
				$data['product_sizes'] = $this->product_sizes_model->get_product_sizes_list();
				$data['unit_types'] = $this->units_model->get_active_unit_types();
				$data['units'] = $this->units_model->get_units_list();
				$data['brands'] = $this->brands_model->get_brands_list();
				$data['tax_rates'] = $this->tax_rates_model->get_tax_rates_list();
				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$data['suppliers'] = $this->suppliers_model->get_suppliers_list();
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$product = $this->products_model->get_product($product_id);
				$data['product'] = $product;
				$data['product_product_categories'] = $this->products_model->get_product_product_categories($product_id);
				$data['product_images'] = $this->products_model->get_product_images($product_id);
				$data['product_num_images'] = $this->products_model->get_product_num_images($product_id);
				$data['product_suppliers'] = $this->products_model->get_product_suppliers($product_id);
				$data['product_attributes'] = $this->products_model->get_product_attributes($product_id);
				$data['num_product_attributes'] = $this->products_model->get_num_product_attributes($product_id);
				$data['product_variations'] = $this->products_model->get_product_variations($product_id);
				$data['num_product_variations'] = $this->products_model->get_num_product_variations($product_id);

				$unit_id = 0;
				foreach ($product as $row) {
					$unit_id = $row->unit_id;
				}

				$data['related_units'] = $this->products_model->get_edit_product_related_units($unit_id, $product_id);
				$data['num_related_units'] = $this->products_model->get_num_edit_product_related_units($unit_id, $product_id);

				// $data['product_product_sizes'] = $this->products_model->get_product_product_sizes($product_id);
				// $data['product_colors'] = $this->products_model->get_product_colors($product_id);
				$data['outlet_products'] = $this->products_model->get_outlet_products($product_id);

				$data['page_title'] = 'Edit Product | ';
				$data['main_content'] = 'be/product_add2';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function update(){
		$product_id = $this->input->post('product_id');
		$product_name = $this->input->post('product_name');

		$q = $this->products_model->product_update_exists();
		if($q['res'] == true){

			$product_sku = $this->products_model->get_product_sku_code($product_id);		
			$product_reference_id = url_title($this->input->post('product_name'),'-',TRUE) . '-' . strtolower($product_sku);

			if($this->input->post('negative_inventory') == 'on'){
				$negative_inventory = 1;
			} else {
				$negative_inventory = 0;
			}

			if ($this->input->post('chk_product_outlet_regular_prices') == 'on') {
				$universal_regular_price = 1;
			} else {
				$universal_regular_price = 0;
			}

			if ($this->input->post('chk_product_outlet_sale_prices') == 'on') {
				$universal_sale_price = 1;
			} else {
				$universal_sale_price = 0;
			}

			if ($this->input->post('chk_product_outlet_minimum_prices') == 'on') {
				$universal_minimum_price = 1;
			} else {
				$universal_minimum_price = 0;
			}

			$data = array(
				'product_reference_id' => $product_reference_id,
				'product_sku_code' => $product_sku,			
				'product_name' => $this->input->post('product_name'),
				'product_barcode' => $this->input->post('product_barcode'),
				'product_short_description' => $this->input->post('product_short_description'),
				'product_type' => $this->input->post('product_type'),
				'product_description' => $this->input->post('product_description'),
				'tax_rate_id' => $this->input->post('tax_rate_id'),
				'brand_id' => $this->input->post('brand_id'),
				'unit_id' => $this->input->post('unit_id'),
				'regular_price' => $this->input->post('regular_price'),
				'sale_price' => $this->input->post('sale_price'),
				'minimum_selling_price' => $this->input->post('minimum_selling_price'),
				'universal_regular_price' => $universal_regular_price,
				'universal_sale_price' => $universal_sale_price,
				'universal_minimum_price' => $universal_minimum_price,
				'negative_inventory' => $negative_inventory,		
				'is_featured' => $this->input->post('is_featured'),
				'is_new_arrival' => $this->input->post('is_new_arrival'),
				'is_special_offer' => $this->input->post('is_special_offer'),
				'is_deal_of_the_week' => $this->input->post('is_deal_of_the_week'),
				'is_online' => $this->input->post('is_online'),
				'seo_description' => $this->input->post('seo_description'),
				'seo_keywords' => $this->input->post('seo_keywords')
			);	
			$q = $this->products_model->update($data,$product_id);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
		echo json_encode($resp);

	}
	function delete($product_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->products_model->delete($product_id);
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
	function get_product($product_id) {
		$product = $this->products_model->get_product($product_id);

		echo json_encode($product);
	}
	function set_product_online_status(){
		if($this->session->userdata('bgs_be_active')){
			$product_id = $this->input->post('product_id');
			$is_online = $this->input->post('is_online');

			$q = $this->products_model->set_product_online_status($product_id, $is_online);
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
	function set_product_featured_status(){
		if($this->session->userdata('bgs_be_active')){
			$product_id = $this->input->post('product_id');
			$is_featured = $this->input->post('is_featured');

			$q = $this->products_model->set_product_featured_status($product_id, $is_featured);
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
	function set_product_new_arrival_status(){
		if($this->session->userdata('bgs_be_active')){
			$product_id = $this->input->post('product_id');
			$is_new_arrival = $this->input->post('is_new_arrival');

			$q = $this->products_model->set_product_new_arrival_status($product_id, $is_new_arrival);
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
	function set_product_special_offer_status(){
		if($this->session->userdata('bgs_be_active')){
			$product_id = $this->input->post('product_id');
			$is_special_offer = $this->input->post('is_special_offer');

			$q = $this->products_model->set_product_special_offer_status($product_id, $is_special_offer);
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
	function set_product_deal_of_the_week_status(){
		if($this->session->userdata('bgs_be_active')){
			$product_id = $this->input->post('product_id');
			$is_deal_of_the_week = $this->input->post('is_deal_of_the_week');

			$q = $this->products_model->set_product_deal_of_the_week_status($product_id, $is_deal_of_the_week);
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
			$q = $this->products_model->delete_bulk($ids);
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
	function delete_cover_image($product_id) {
		if($this->session->userdata('bgs_be_active')){
			$q = $this->products_model->delete_cover_image($product_id);
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
	

	function delete_product_image($product_image_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->products_model->delete_product_image($product_image_id);
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

	function upload_add_other_image(){
		if($this->session->userdata('bgs_be_active')){
			$product_id = $this->input->post('product_add_other_image_product_id');		

			$q = $this->products_model->upload_add_other_image($product_id);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);
	}

	function get_outlet_product() {
		$outlet_id = $this->input->post('outlet_id');
		$product_id = $this->input->post('product_id');

		$outlet_product = $this->products_model->get_outlet_product($outlet_id, $product_id);
		
		echo json_encode($outlet_product);
	}

	function get_purchase_order_product() {
		$purchase_order_id = $this->input->post('purchase_order_id');
		$product_id = $this->input->post('product_id');

		$purchase_order_product = $this->products_model->get_purchase_order_product($purchase_order_id, $product_id);
		
		echo json_encode($purchase_order_product);
	}

	//PRODUCT ATTRIBUTES
	function attribute_add_valid(){
		if($this->session->userdata('system_user_id')){

			$q = $this->products_model->attribute_add_valid();

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'], 'product_id' => $q['product_id']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'], 'product_id' => '');
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '', 'product_id' => '');
		}

		echo json_encode($resp);
	}

	function save_attribute() {
		if($this->session->userdata('bgs_be_active')){
			$q = $this->products_model->save_attribute();
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

	function loadjs_product_attributes(){
		$product_id = $this->input->post('product_id');
		$data['product_attributes'] = $this->products_model->get_product_attributes($product_id);
		$data['num_product_attributes'] = $this->products_model->get_num_product_attributes($product_id);
		$data['sbr_products_edit'] = $this->auth_model->validate_user_access('products_edit', $this->session->userdata('system_user_id'));
		$data['sbr_products_delete'] = $this->auth_model->validate_user_access('products_delete', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/product_attributes',$data);
	}
	function get_product_attribute($product_attribute_id){
		$product_attribute = $this->products_model->get_product_attribute($product_attribute_id);
		echo json_encode($product_attribute);
	}

	function update_attribute() {
		if($this->session->userdata('bgs_be_active')){
			$q = $this->products_model->update_attribute();
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

	function delete_attribute($product_attribute_id) {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->products_model->delete_attribute($product_attribute_id);

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

	//PRODUCT VARIATIONS
	function variation_add_valid() {
		if($this->session->userdata('system_user_id')){

			$q = $this->products_model->variation_add_valid();

			if($q['res'] == true){

				$data['product_attributes'] = $this->products_model->get_product_attributes($q['product_id']);
				$data['product_id'] = $q['product_id'];
				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$variation_add_form = $this->load->view('be/jsloads/product_variation_add_form',$data,TRUE);

				$resp = array('status' => 'SUCCESS','message' => $q['dt'], 'product_id' => $q['product_id'], 'variation_add_form' => $variation_add_form);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'], 'product_id' => '', 'variation_add_form' => '');
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '', 'product_id' => '', 'variation_add_form' => '');
		}
		echo json_encode($resp);
	}

	function variation_edit_add_valid() {
		$product_id = $this->input->post('product_id');

		$data['product_attributes'] = $this->products_model->get_product_attributes($product_id);
		$data['product_id'] = $product_id;
		$data['outlets'] = $this->outlets_model->get_outlets_list();
		// $data['outlet_products'] = $this->products_model->get_outlet_products($product_id);
		$variation_add_form = $this->load->view('be/jsloads/product_variation_add_form',$data,TRUE);

		$resp = array('status' => 'SUCCESS','message' => 'Success', 'product_id' => $product_id, 'variation_add_form' => $variation_add_form);

		echo json_encode($resp);
	}

	function save_variation() {
		if($this->session->userdata('bgs_be_active')){
			$q = $this->products_model->save_variation();
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

	function loadjs_product_variations(){
		$product_id = $this->input->post('product_id');
		$data['product_attributes'] = $this->products_model->get_product_attributes($product_id);
		$data['num_product_attributes'] = $this->products_model->get_num_product_attributes($product_id);
		$data['outlets'] = $this->outlets_model->get_outlets_list();
		$data['product_variations'] = $this->products_model->get_product_variations($product_id);
		$data['num_product_variations'] = $this->products_model->get_num_product_variations($product_id);
		$data['sbr_products_edit'] = $this->auth_model->validate_user_access('products_edit', $this->session->userdata('system_user_id'));
		$data['sbr_products_delete'] = $this->auth_model->validate_user_access('products_delete', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/product_variations',$data);
	}

	function get_edit_product_variation($product_variation_id) {
		if($this->session->userdata('system_user_id')){

			$product_id = $this->input->post('product_id');

			$data['product_attributes'] = $this->products_model->get_product_attributes($product_id);
			$data['product_variation'] = $this->products_model->get_product_variation($product_variation_id);
			$data['outlets'] = $this->outlets_model->get_outlets_list();
			$data['variation_outlet_products'] = $this->products_model->get_variation_outlet_products($product_id, $product_variation_id);
			$data['product_id'] = $product_id;
			$variation_edit_form = $this->load->view('be/jsloads/product_variation_edit_form',$data,TRUE);

			$resp = array('status' => 'SUCCESS','message' => 'Valid', 'product_id' => $product_id, 'variation_edit_form' => $variation_edit_form);
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '', 'product_id' => '', 'variation_edit_form' => '');
		}
		echo json_encode($resp);
	}

	function update_variation() {
		if($this->session->userdata('bgs_be_active')){
			$q = $this->products_model->update_variation();
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

	function delete_variation($product_variation_id) {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->products_model->delete_variation($product_variation_id);

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

	function loadjs_select_product_variations(){
		$product_id = $this->input->post('product_id');
		$context = $this->input->post('context');
		$data['context'] =  $context;
		$data['product_id'] =  $product_id;

		if ($context == 'purchase_order_new' || $context == 'return_stock' || $context == 'transfer_stock'){
			$data['product'] = $this->products_model->get_product($product_id);
			$data['product_variations'] = $this->products_model->get_product_variations($product_id);
			$data['num_product_variations'] = $this->products_model->get_num_product_variations($product_id);
		} elseif ($context == 'adjust_stock' || $context == 'writeoff_stock'){
			$outlet_id = $this->input->post('outlet_id');
			$data['product'] = $this->products_model->get_outlet_product($outlet_id,$product_id);
			$data['product_variations'] = $this->products_model->get_outlet_product_variations($outlet_id,$product_id);
			$data['num_product_variations'] = $this->products_model->get_num_outlet_product_variations($outlet_id,$product_id);
		}
		$data['default_currency'] = $this->currencies_model->get_default_currency();


		$this->load->view('be/jsloads/select_product_variations',$data);

	}

	//PRODUCT IMAGE
	function set_product_image_valid() {
		if($this->session->userdata('system_user_id')){

			$q = $this->products_model->set_product_image_valid();

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'], 'product_id' => $q['product_id']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt'], 'product_id' => '');
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '', 'product_id' => '');
		}

		echo json_encode($resp);
	}

	function upload_set_product_image() {
		if($this->session->userdata('bgs_be_active')){
			$q = $this->products_model->upload_set_product_image();
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

	function loadjs_product_main_image() {
		$product_id = $this->input->post('product_id');
		$data['product'] = $this->products_model->get_product($product_id);
		$data['sbr_products_edit'] = $this->auth_model->validate_user_access('products_edit', $this->session->userdata('system_user_id'));
		$data['sbr_products_delete'] = $this->auth_model->validate_user_access('products_delete', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/product_main_image',$data);		
	}

	//PRODUCT GALLERY IMAGES
	function upload_add_product_gallery_image() {
		if($this->session->userdata('bgs_be_active')){
			$product_id = $this->input->post('product_id');		

			$q = $this->products_model->upload_add_product_gallery_image($product_id);

			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);
	}
	function loadjs_product_gallery_images(){
		$product_id = $this->input->post('product_id');
		$data['product_images'] = $this->products_model->get_product_images($product_id);
		$data['product_num_images'] = $this->products_model->get_product_num_images($product_id);
		$data['sbr_products_edit'] = $this->auth_model->validate_user_access('products_edit', $this->session->userdata('system_user_id'));
		$data['sbr_products_delete'] = $this->auth_model->validate_user_access('products_delete', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/product_gallery_images',$data);
	}

	function upload_edit_product_gallery_image(){
		if($this->session->userdata('bgs_be_active')){

			$product_image_id = $this->input->post('product_image_id');		

			$q = $this->products_model->upload_edit_product_gallery_image($product_image_id);
			if ($q['res'] == true){
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