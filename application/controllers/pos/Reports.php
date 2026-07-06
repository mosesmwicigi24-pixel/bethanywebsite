<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->model('pos/main_model');
		$this->load->model('pos/auth_model');
		$this->load->model('be/currencies_model');
		$this->load->library("Pdf");
	}

	function profit_loss() {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				$data['cur'] = 'Reports';
				$data['cur_sub'] = 'Profit & Loss Report';
				$data['cur_cur_sub'] = '';

				$data['active_outlet'] = $this->main_model->get_active_outlet();

				$data['default_currency'] = $this->currencies_model->get_default_currency();

				//$data['customers'] = $this->main_model->get_customers_list();

				$data['page_title'] = 'Profit & Loss Report | ';
				$data['main_content'] = 'pos/profit_loss_report';
				$this->load->view('pos/includes/template',$data);
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function ajax_profit_loss_report() {
		$data['from_date'] = $this->input->post('from_date');
        $data['to_date'] = $this->input->post('to_date');
		$data['profit_loss_report'] = $this->main_model->get_profit_loss_report();
		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$this->load->view('pos/jsloads/profit_loss_report',$data);
	}

	function sales() {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				$data['cur'] = 'Reports';
				$data['cur_sub'] = 'Sales Report';
				$data['cur_cur_sub'] = '';

				$data['active_outlet'] = $this->main_model->get_active_outlet();

				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['customers'] = $this->main_model->get_customers_list();

				$data['page_title'] = 'Sales Report | ';
				$data['main_content'] = 'pos/sales_report';
				$this->load->view('pos/includes/template',$data);
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function ajax_sales_report() {
		$data['sales_report'] = $this->main_model->get_sales_report();
		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$this->load->view('pos/jsloads/sales_report',$data);
	}

	function sales_detailed() {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				$data['cur'] = 'Reports';
				$data['cur_sub'] = 'Sales Detailed Report';
				$data['cur_cur_sub'] = '';

				$data['active_outlet'] = $this->main_model->get_active_outlet();

				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['customers'] = $this->main_model->get_customers_list();

				$data['page_title'] = 'Sales Detailed Report | ';
				$data['main_content'] = 'pos/sales_detailed_report';
				$this->load->view('pos/includes/template',$data);
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function ajax_sales_detailed_report() {
		$data['sales_detailed_report'] = $this->main_model->get_sales_detailed_report();
		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$this->load->view('pos/jsloads/sales_detailed_report',$data);
	}

	function payments() {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				$data['cur'] = 'Reports';
				$data['cur_sub'] = 'Payments Report';
				$data['cur_cur_sub'] = '';

				$data['active_outlet'] = $this->main_model->get_active_outlet();

				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['customers'] = $this->main_model->get_customers_list();

				$data['page_title'] = 'Payments Report | ';
				$data['main_content'] = 'pos/payments_report';
				$this->load->view('pos/includes/template',$data);
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function ajax_payments_report() {
		$data['payments_report'] = $this->main_model->get_payments_report();
		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$this->load->view('pos/jsloads/payments_report',$data);
	}

	function stock() {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				$data['cur'] = 'Reports';
				$data['cur_sub'] = 'Stock Report';
				$data['cur_cur_sub'] = '';

				$data['active_outlet'] = $this->main_model->get_active_outlet();

				$data['products'] = $this->main_model->get_products_list();

				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['page_title'] = 'Stock Report | ';
				$data['main_content'] = 'pos/stock_report';
				$this->load->view('pos/includes/template',$data);
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function expense() {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				$data['cur'] = 'Reports';
				$data['cur_sub'] = 'Expense Report';
				$data['cur_cur_sub'] = '';

				$data['active_outlet'] = $this->main_model->get_active_outlet();

				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['customers'] = $this->main_model->get_customers_list();

				$data['page_title'] = 'Expense Report | ';
				$data['main_content'] = 'pos/expense_report';
				$this->load->view('pos/includes/template',$data);
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function ajax_expense_report() {
		$data['expense_report'] = $this->main_model->get_expense_report();
		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$this->load->view('pos/jsloads/expense_report',$data);
	}


}