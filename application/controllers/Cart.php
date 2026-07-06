<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cart extends CI_Controller {
    function __construct(){
		parent::__construct();
		$this->flexi = new stdClass;
        $this->load->library('flexi_cart');
        $this->load->library('flexi_cart_admin');
        $this->load->library('flexi_cart_lite');
		$this->load->model('cart_model');
        $this->load->model('main_model');
        $this->load->model('be/currencies_model');
	}

	function index() {
		$meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $data['page_title'] = 'Cart';
        $data['cur'] = '';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        
        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['cart_data'] = $this->flexi_cart->cart_items();

		$data['main_content'] = 'fe/cart';
		$this->load->view('fe/includes/template',$data);

	}

	function add() {
		$product_id = $this->input->post('product_id');

		$q = $this->cart_model->add($product_id);
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);
	}

	function loadjs_cart() {
		$data['cart_data'] = $this->flexi_cart->cart_items();
		$this->load->view('fe/jsloads/ajax_cart',$data);
	}

	function loadjs_mobile_cart() {
		$data['cart_data'] = $this->flexi_cart->cart_items();
		$this->load->view('fe/jsloads/ajax_mobile_cart',$data);
	}

	function loadjs_main_cart() {
		$data['cart_data'] = $this->flexi_cart->cart_items();
		$this->load->view('fe/jsloads/ajax_main_cart',$data);
	}

	function remove($row_id) {
		if ($this->flexi_cart->delete_items($row_id)){
			$resp = array('status' => 'SUCCESS','message' => $this->flexi_cart->get_messages());
		} else {
			$resp = array('status' => 'ERR','message' => $this->flexi_cart->get_messages());
		}
		echo json_encode($resp);
	}

	function update_item_quantity() {
		$q = $this->cart_model->update_item_quantity();
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt']);
		}
			
		echo json_encode($resp);
	}


}