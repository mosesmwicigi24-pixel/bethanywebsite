<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->model('pos/main_model');
		$this->load->model('be/currencies_model');
		$this->load->model('pos/auth_model');
	}

	function index(){
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				$data['cur'] = 'Dashboard';
				$data['cur_sub'] = '';
				$data['cur_cur_sub'] = '';

				$data['active_outlet'] = $this->main_model->get_active_outlet();
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['today_total_sales'] = $this->main_model->get_today_total_sales();
				$data['today_total_sales_payments'] = $this->main_model->get_today_total_sales_payments();
				$data['today_total_sales_due'] = $this->main_model->get_today_total_sales_due();
				$data['today_total_expenses'] = $this->main_model->get_today_total_expenses();

				$data['total_sales_orders'] = $this->main_model->get_total_sales_orders();
				$data['total_held_orders'] = $this->main_model->get_total_held_orders();
				$data['total_products'] = $this->main_model->get_total_products();
				$data['total_customers'] = $this->main_model->get_total_customers();

				$data['low_stock_list'] = $this->main_model->get_dashboard_low_stock_list();

				$data['monthly_sales_statistics'] = $this->main_model->get_monthly_sales_statistics();

				$data['page_title'] = 'Dashboard | ';
				$data['main_content'] = 'pos/dashboard';
				$this->load->view('pos/includes/template',$data);
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function test_printer() {
		//$data['main_content'] = 'pos/test_printer';
		$this->load->view('pos/test_printer');
	}



}