<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Track extends CI_Controller {
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

    function index(){
        $meta = $this->main_model->get_global_meta();
        $data['meta'] = $meta;

        $data['page_title'] = 'Track Order';
        $data['cur'] = '';

        $data['store_information'] = $this->main_model->get_store_information();
        $data['home_nested_product_categories'] = $this->main_model->get_home_nested_product_categories();
        $data['home_top_product_categories'] = $this->main_model->get_home_top_product_categories();
        
        $data['default_currency'] = $this->currencies_model->get_default_currency();

        $data['cart_data'] = $this->flexi_cart->cart_items();

        $data['main_content'] = 'fe/track';
        $this->load->view('fe/includes/template',$data);
    }











}