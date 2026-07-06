<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->model('be/reports_model');
		$this->load->model('be/products_model');
		$this->load->model('be/outlets_model');
		$this->load->model('be/currencies_model');
		$this->load->model('be/store_information_model');
		$this->load->model('be/auth_model');
		$this->load->model('be/system_users_model');
		$this->load->model('be/customers_model');
		$this->load->library("Pdf");
		$this->load->library("Dpdf");
	}

	function sales() {
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('reports_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {

				// $data['reports'] = $this->inventory_model->get_reports();
				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$data['system_users'] = $this->system_users_model->get_system_users_list();
				$data['products'] = $this->products_model->get_products_list();
				$data['customers'] = $this->customers_model->get_customers_list();
				$data['page_title'] = 'Sales Reports | ';

				$data['cur'] = 'Reports';
				$data['cur_sub'] = 'Sales';
				$data['cur_cur_sub'] = '';

				// $data['sbr_reports_add'] = $this->auth_model->validate_user_access('reports_add', $this->session->userdata('system_user_id'));

				// $data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();

				$data['main_content'] = 'be/report_sales';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function get_sales_summary_report() {

		$data['date_from'] = $this->input->post('date_from');
    	$data['date_to'] = $this->input->post('date_to');
		$data['outlet_id'] = $this->input->post('outlet_id');

		$data['total_sales_including_tax'] = $this->reports_model->get_total_sales_including_tax();
		$data['total_sales_tax'] = $this->reports_model->get_total_sales_tax();
		$data['total_sales_excluding_tax'] = $this->reports_model->get_total_sales_excluding_tax();
		$data['cost_of_goods_sold'] = $this->reports_model->get_cost_of_goods_sold();
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$this->load->view('be/jsloads/report_sales_summary',$data);
	}

	function get_credit_list_report() {

		$data['credit_transactions'] = $this->reports_model->get_credit_list_report();
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$this->load->view('be/jsloads/report_credit_list',$data);
	}

	function get_customer_aging_report() {

		$data['customer_aging_report'] = $this->reports_model->get_customer_aging_report();
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$this->load->view('be/jsloads/report_customer_aging',$data);
	}

	function get_pos_sales_summary_chart_data() {
		$chart_data = $this->reports_model->get_pos_sales_summary_chart_data();
		echo json_encode($chart_data, JSON_NUMERIC_CHECK);
	}

	function get_online_sales_summary_chart_data() {
		$chart_data = $this->reports_model->get_online_sales_summary_chart_data();
		echo json_encode($chart_data, JSON_NUMERIC_CHECK);
	}

	function get_sales_by_items_report() {

		$data['date_from'] = $this->input->post('date_from');
    	$data['date_to'] = $this->input->post('date_to');
		$data['outlet_id'] = $this->input->post('outlet_id');
		$data['transaction_type'] = $this->input->post('transaction_type');

		$data['sales_by_items'] = $this->reports_model->get_sales_by_items();
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$this->load->view('be/jsloads/report_sales_by_items',$data);
	}

	function export_sales_by_items_report() {

		$data['date_from'] = $this->input->post('date_from');
        $data['date_to'] = $this->input->post('date_to');
        $data['outlet_id'] = $this->input->post('outlet_id');
        $data['outlet_name'] = $this->input->post('outlet_name');
        $data['transaction_type'] = $this->input->post('transaction_type');

		$data['sales_by_items'] = $this->reports_model->get_sales_by_items();
		$data['store_information'] = $this->store_information_model->get_store_information();		
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$data['page_title'] = 'Sales by Items Report | ';
		
		// $this->load->view('be/report_sales_by_items_print',$data);
		$dompdf = new Dpdf();
		$dompdf->set_paper('letter', 'landscape');
		$html_content = $this->load->view('be/report_sales_by_items_print',$data,true);
		$dompdf->loadHtml($html_content);
		$dompdf->render();
		$dompdf->stream("Sales by Items Report.pdf", array("Attachment" => false));
	}

	function get_sales_transactions_report() {

		$data['pos_sales_transactions'] = $this->reports_model->get_pos_sales_transactions();
		$data['online_sales_transactions'] = $this->reports_model->get_online_sales_transactions();

		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$this->load->view('be/jsloads/report_sales_transactions',$data);
	}

	function export_sales_transactions_report() {

		$data['date_from'] = $this->input->post('date_from');
        $data['date_to'] = $this->input->post('date_to');
        $data['outlet_id'] = $this->input->post('outlet_id');
        $data['outlet_name'] = $this->input->post('outlet_name');
        $data['sale_type'] = $this->input->post('sale_type');
        $data['sale_status'] = $this->input->post('sale_status');
        $data['sale_status_text'] = $this->input->post('sale_status_text');
        $data['system_user_id'] = $this->input->post('system_user_id');
        $data['system_user_name'] = $this->input->post('system_user_name');

		$data['pos_sales_transactions'] =  $this->reports_model->get_pos_sales_transactions();
		$data['store_information'] = $this->store_information_model->get_store_information();		
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$data['page_title'] = 'POS Sales Transactions Report | ';
		
		// $this->load->view('be/report_sales_transactions_print',$data);
		$dompdf = new Dpdf();
		$dompdf->set_paper('letter', 'landscape');
		$html_content = $this->load->view('be/report_sales_transactions_print',$data,true);
		$dompdf->loadHtml($html_content);
		$dompdf->render();
		$dompdf->stream("POS Sales Transactions.pdf", array("Attachment" => false));
	}

	function get_item_sales_report() {

		$data['item_sales'] = $this->reports_model->get_item_sales();
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$this->load->view('be/jsloads/report_item_sales',$data);
	}

	function export_item_sales_report() {

		$data['date_from'] = $this->input->post('date_from');
        $data['date_to'] = $this->input->post('date_to');
        $data['outlet_id'] = $this->input->post('outlet_id');
        $data['outlet_name'] = $this->input->post('outlet_name');
        $data['product_id'] = $this->input->post('product_id');
        $data['product_name'] = $this->input->post('product_name');
        $data['customer_id'] = $this->input->post('customer_id');
        $data['customer_name'] = $this->input->post('customer_name');
        $data['system_user_id'] = $this->input->post('system_user_id');
        $data['system_user_name'] = $this->input->post('system_user_name');

		$data['item_sales'] = $this->reports_model->get_item_sales();
		$data['store_information'] = $this->store_information_model->get_store_information();		
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$data['page_title'] = 'POS Item Sales Report | ';
		
		$this->load->view('be/report_item_sales_print',$data);
// 		$dompdf = new Dpdf();
// 		$dompdf->set_paper('letter', 'landscape');
// 		$html_content = $this->load->view('be/report_item_sales_print',$data,true);
// 		$dompdf->loadHtml($html_content);
// 		$dompdf->render();
// 		$dompdf->stream("POS Item Sales.pdf", array("Attachment" => false));
	}

	function export_credit_list_report() {

        $data['customer_id'] = $this->input->post('customer_id');
        $data['customer_name'] = $this->input->post('customer_name');
        $data['system_user_id'] = $this->input->post('system_user_id');
        $data['system_user_name'] = $this->input->post('system_user_name');
        $data['chk_cash_sales'] = $this->input->post('chk_cash_sales');

		$data['credit_transactions'] =  $this->reports_model->get_credit_list_report();
		$data['store_information'] = $this->store_information_model->get_store_information();		
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$data['page_title'] = 'Credit List Report | ';
		
		// $this->load->view('be/report_credit_list_print',$data);
		$dompdf = new Dpdf();
		$dompdf->set_paper('letter', 'landscape');
		$html_content = $this->load->view('be/report_credit_list_print',$data,true);
		$dompdf->loadHtml($html_content);
		$dompdf->render();
		$dompdf->stream("Credit List Report.pdf", array("Attachment" => false));

	}

	function export_customer_aging_report() {

		$car_date = $this->input->post('car_date');
    	if ($car_date != '') {
    		$car_date = date('Y-m-d', strtotime($car_date));
    	} else {
    		$car_date = date('Y-m-d');
    	}

        $data['car_date'] = $car_date;

		$data['customer_aging_report'] =  $this->reports_model->get_customer_aging_report();
		$data['store_information'] = $this->store_information_model->get_store_information();		
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$data['page_title'] = 'Customer Aging Report | ';
		
		// $this->load->view('be/report_customer_aging_print',$data);
		$dompdf = new Dpdf();
		// $dompdf->set_paper('letter', 'landscape');
		$html_content = $this->load->view('be/report_customer_aging_print',$data,true);
		$dompdf->loadHtml($html_content);
		$dompdf->render();
		$dompdf->stream("Customer Aging Report.pdf", array("Attachment" => false));

	}

	function get_online_sales_transactions_report() {

		$data['online_sales_transactions'] = $this->reports_model->get_online_sales_transactions();
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$this->load->view('be/jsloads/report_online_sales_transactions',$data);
	}

	function export_online_sales_transactions_report() {

		$data['date_from'] = $this->input->post('date_from');
        $data['date_to'] = $this->input->post('date_to');
        $data['outlet_id'] = $this->input->post('outlet_id');
        $data['outlet_name'] = $this->input->post('outlet_name');

		$data['online_sales_transactions'] = $this->reports_model->get_online_sales_transactions();
		$data['store_information'] = $this->store_information_model->get_store_information();		
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$data['page_title'] = 'Online Sales Transactions Report | ';
		
		// $this->load->view('be/report_online_sales_transactions_print',$data);
		$dompdf = new Dpdf();
		$dompdf->set_paper('letter', 'landscape');
		$html_content = $this->load->view('be/report_online_sales_transactions_print',$data,true);
		$dompdf->loadHtml($html_content);
		$dompdf->render();
		$dompdf->stream("Online Sales Transactions.pdf", array("Attachment" => false));
	}

	//CUSTOMER REPORTS
	function customers() {
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('reports_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {

				// $data['reports'] = $this->inventory_model->get_reports();
				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$data['system_users'] = $this->system_users_model->get_system_users_list();
				$data['customers'] = $this->customers_model->get_customers_list();
				$data['page_title'] = 'Customer Reports | ';

				$data['cur'] = 'Reports';
				$data['cur_sub'] = 'Customers';
				$data['cur_cur_sub'] = '';

				// $data['sbr_reports_add'] = $this->auth_model->validate_user_access('reports_add', $this->session->userdata('system_user_id'));

				// $data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();

				$data['main_content'] = 'be/report_customers';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function stock() {
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('reports_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {

				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$data['page_title'] = 'Stock Report | ';

				$data['cur'] = 'Reports';
				$data['cur_sub'] = 'Stock';
				$data['cur_cur_sub'] = '';

				// $data['sbr_reports_add'] = $this->auth_model->validate_user_access('reports_add', $this->session->userdata('system_user_id'));

				// $data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();

				$data['main_content'] = 'be/report_stock';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function get_stock_report() {

		$data['stock_report'] = $this->reports_model->get_stock_report();

		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$this->load->view('be/jsloads/report_stock',$data);
	}

	function export_report_stock() {
		$outlet_id = $this->input->post('outlet_id');

		$data['stock_report'] = $this->reports_model->get_outlet_stock_report($outlet_id);
		$data['store_information'] = $this->store_information_model->get_store_information();		
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$data['page_title'] = 'Stock Report | ';
		
		// $this->load->view('be/report_stock_print',$data);
		$dompdf = new Dpdf();
		$dompdf->set_paper('letter', 'landscape');
		$html_content = $this->load->view('be/report_stock_print',$data,true);
		$dompdf->loadHtml($html_content);
		$dompdf->render();
		$dompdf->stream("Stock Report.pdf", array("Attachment" => false));

	}

	function low_stocks() {
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('reports_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {

				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$data['page_title'] = 'Items on Reorder Level Report | ';

				$data['cur'] = 'Reports';
				$data['cur_sub'] = 'Low Stocks';
				$data['cur_cur_sub'] = '';

				// $data['sbr_reports_add'] = $this->auth_model->validate_user_access('reports_add', $this->session->userdata('system_user_id'));

				// $data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();

				$data['main_content'] = 'be/report_low_stocks';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function get_low_stocks_report() {

		$data['low_stocks_report'] = $this->reports_model->get_low_stocks_report();

		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$this->load->view('be/jsloads/report_low_stocks',$data);
	}

	function export_report_low_stocks() {
		$outlet_id = $this->input->post('outlet_id');

		$data['low_stocks_report'] = $this->reports_model->get_outlet_low_stocks_report($outlet_id);
		$data['store_information'] = $this->store_information_model->get_store_information();		
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$data['page_title'] = 'Items on Reorder Level Report | ';
		
		// $this->load->view('be/report_low_stocks_print',$data);
		$dompdf = new Dpdf();
		$dompdf->set_paper('letter', 'landscape');
		$html_content = $this->load->view('be/report_low_stocks_print',$data,true);
		$dompdf->loadHtml($html_content);
		$dompdf->render();
		$dompdf->stream("Items on Reorder Level Report.pdf", array("Attachment" => false));

	}

	function payments() {
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('reports_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {

				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$data['page_title'] = 'Payments Report | ';

				$data['cur'] = 'Reports';
				$data['cur_sub'] = 'Payments';
				$data['cur_cur_sub'] = '';

				// $data['sbr_reports_add'] = $this->auth_model->validate_user_access('reports_add', $this->session->userdata('system_user_id'));

				// $data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();

				$data['main_content'] = 'be/report_payments';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function get_payments_summary_report() {

		$data['date_from'] = $this->input->post('date_from');
    	$data['date_to'] = $this->input->post('date_to');

		$data['total_payments'] = $this->reports_model->get_total_payments();
		$data['total_pos_payments'] = $this->reports_model->get_total_pos_payments();
		$data['total_online_payments'] = $this->reports_model->get_total_online_payments();
		$data['total_payment_transactions'] = $this->reports_model->get_total_payment_transactions();
		$data['total_payments_balance'] = $this->reports_model->get_total_payments_balance();

		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$this->load->view('be/jsloads/report_payments_summary',$data);
	}

	function get_pos_payments_donut_data() {
		$chart_data = $this->reports_model->get_pos_payments_donut_data();
		echo json_encode($chart_data, JSON_NUMERIC_CHECK);
	}

	function get_online_payments_donut_data() {
		$chart_data = $this->reports_model->get_online_payments_donut_data();
		echo json_encode($chart_data, JSON_NUMERIC_CHECK);
	}

	function get_pos_payments_summary_chart_data() {
		$chart_data = $this->reports_model->get_pos_payments_chart_data();
		echo json_encode($chart_data, JSON_NUMERIC_CHECK);
	}
	function get_online_payments_summary_chart_data() {
		$chart_data = $this->reports_model->get_online_payments_chart_data();
		echo json_encode($chart_data, JSON_NUMERIC_CHECK);
	}

	function get_pos_payments() {
		$data['pos_payments'] = $this->reports_model->get_pos_payments();

		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$this->load->view('be/jsloads/report_pos_payments',$data);

	}

	function export_pos_payments_report() {
		$data['date_from'] = $this->input->post('date_from');
    	$data['date_to'] = $this->input->post('date_to');
    	$data['payment_method'] = $this->input->post('payment_method');

		$data['pos_payments'] = $this->reports_model->get_pos_payments();
		$data['store_information'] = $this->store_information_model->get_store_information();		
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$data['page_title'] = 'POS Payments Report | ';
		
		// $this->load->view('be/report_pos_payments_print',$data);
		$dompdf = new Dpdf();
		$dompdf->set_paper('letter', 'landscape');
		$html_content = $this->load->view('be/report_pos_payments_print',$data,true);
		$dompdf->loadHtml($html_content);
		$dompdf->render();
		$dompdf->stream("POS Payments Report.pdf", array("Attachment" => false));

	}

	function get_online_payments() {
		$data['online_payments'] = $this->reports_model->get_online_payments();

		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$this->load->view('be/jsloads/report_online_payments',$data);
		
	}

	function export_online_payments_report() {
		$data['date_from'] = $this->input->post('date_from');
    	$data['date_to'] = $this->input->post('date_to');

		$data['online_payments'] = $this->reports_model->get_online_payments();
		$data['store_information'] = $this->store_information_model->get_store_information();		
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$data['page_title'] = 'Online Payments Report | ';
		
		// $this->load->view('be/report_online_payments_print',$data);
		$dompdf = new Dpdf();
		$dompdf->set_paper('letter', 'landscape');
		$html_content = $this->load->view('be/report_online_payments_print',$data,true);
		$dompdf->loadHtml($html_content);
		$dompdf->render();
		$dompdf->stream("Online Payments Report.pdf", array("Attachment" => false));

	}

	function expenses() {
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('reports_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {

				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$data['system_users'] = $this->system_users_model->get_system_users_list();
				$data['page_title'] = 'Expenses Report | ';

				$data['cur'] = 'Reports';
				$data['cur_sub'] = 'Expenses';
				$data['cur_cur_sub'] = '';

				// $data['sbr_reports_add'] = $this->auth_model->validate_user_access('reports_add', $this->session->userdata('system_user_id'));

				// $data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();

				$data['main_content'] = 'be/report_expenses';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function get_expenses_report() {
		$data['expenses_report'] = $this->reports_model->get_expenses_report();

		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$this->load->view('be/jsloads/report_expenses_report',$data);
	}

	function export_expenses_report() {

		$data['date_from'] = $this->input->post('date_from');
        $data['date_to'] = $this->input->post('date_to');
        $data['outlet_id'] = $this->input->post('outlet_id');
        $data['outlet_name'] = $this->input->post('outlet_name');
        $data['system_user_id'] = $this->input->post('system_user_id');
        $data['system_user_name'] = $this->input->post('system_user_name');
        $data['status'] = $this->input->post('status');
        $data['status_text'] = $this->input->post('status_text');

		$data['expenses_report'] = $this->reports_model->get_expenses_report();
		$data['store_information'] = $this->store_information_model->get_store_information();		
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$data['page_title'] = 'Expenses Report | ';
		
		// $this->load->view('be/report_sales_transactions_print',$data);
		$dompdf = new Dpdf();
		$dompdf->set_paper('letter', 'landscape');
		$html_content = $this->load->view('be/report_expenses_print',$data,true);
		$dompdf->loadHtml($html_content);
		$dompdf->render();
		$dompdf->stream("Expenses Report.pdf", array("Attachment" => false));
	}

	function income_statement() {
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('reports_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {

				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$data['page_title'] = 'Income Statement | ';

				$data['cur'] = 'Reports';
				$data['cur_sub'] = 'Income Statement';
				$data['cur_cur_sub'] = '';

				// $data['sbr_reports_add'] = $this->auth_model->validate_user_access('reports_add', $this->session->userdata('system_user_id'));

				// $data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();

				$data['main_content'] = 'be/income_statement';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}		
	}


	function get_income_statement() {

		$data['date_from'] = $this->input->post('date_from');
    	$data['date_to'] = $this->input->post('date_to');

		$data['total_sales'] = $this->reports_model->get_total_sales_including_tax();
		$data['total_sales_tax'] = $this->reports_model->get_total_sales_tax();
		$data['cost_of_goods_sold'] = $this->reports_model->get_cost_of_goods_sold();
		$data['total_expenses'] = $this->reports_model->get_total_expenses();

		$data['store_information'] = $this->store_information_model->get_store_information();
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$this->load->view('be/jsloads/report_income_statement',$data);

	}

	function export_income_statement() {

		$data['date_from'] = $this->input->post('date_from');
        $data['date_to'] = $this->input->post('date_to');

		$data['total_sales'] = $this->reports_model->get_total_sales_including_tax();
		$data['total_sales_tax'] = $this->reports_model->get_total_sales_tax();
		$data['cost_of_goods_sold'] = $this->reports_model->get_cost_of_goods_sold();
		$data['total_expenses'] = $this->reports_model->get_total_expenses();
		$data['store_information'] = $this->store_information_model->get_store_information();		
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$data['page_title'] = 'Income Statement Report | ';
		
		// $this->load->view('be/income_statement_print',$data);
		$dompdf = new Dpdf();
		// $dompdf->set_paper('letter', 'landscape');
		$html_content = $this->load->view('be/income_statement_print',$data,true);
		$dompdf->loadHtml($html_content);
		$dompdf->render();
		$dompdf->stream("Income Statement Report.pdf", array("Attachment" => false));
	}



}