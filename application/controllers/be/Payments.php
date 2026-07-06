<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Payments extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/inventory_model');
		$this->load->model('be/suppliers_model');
		$this->load->model('be/outlets_model');
		$this->load->model('be/tax_rates_model');
		$this->load->model('be/products_model');
		$this->load->model('be/store_information_model');
		$this->load->model('be/payments_model');
		$this->load->model('be/auth_model');
	}

	//PAYBILL PAYMENTS
	function paybill() {
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('paybill_payments_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['page_title'] = 'Paybill Payments | ';

				$data['cur'] = 'Payments';
				$data['cur_sub'] = 'Paybill Payments';
				$data['cur_cur_sub'] = '';

				$data['main_content'] = 'be/paybill_payments';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function filter_js_paybill_payments() {
		$data['paybill_payments'] = $this->payments_model->get_paybill_payments();
		$this->load->view('be/jsloads/paybill_payments',$data);
	}

	function paybill_payment($paybill_payment_id) {
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('paybill_payments_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['page_title'] = 'Paybill Payment Details | ';

				$data['paybill_payment'] = $this->payments_model->get_paybill_payment($paybill_payment_id);

				$data['cur'] = 'Payments';
				$data['cur_sub'] = 'Paybill Payments';
				$data['cur_cur_sub'] = '';

				$data['main_content'] = 'be/paybill_payment_details';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function get_paybill_payment($paybill_payment_id) {
		$paybill_payment = $this->payments_model->get_paybill_payment($paybill_payment_id);
		echo json_encode($paybill_payment);
	}

	function get_assign_paybill_payment_transactions() {
		$transactions = $this->payments_model->get_assign_paybill_payment_transactions();
		echo json_encode($transactions);
	}

	function submit_paybill_payment_assign_transaction() {

		if($this->session->userdata('bgs_be_active')){
			
			$q = $this->payments_model->submit_paybill_payment_assign_transaction();

			if($q['status'] == TRUE){
				$resp = array('status' => 'SUCCESS','message' => $q['message'],'status_code' => $q['status_code']);			
			}else{					
				$resp = array('status' => 'ERR','message' => $q['message'],'status_code' => $q['status_code']);			
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue','status_code' => 1);			
    	}
		echo json_encode($resp);

	}

	function submit_paybill_overpayment_assign_transaction() {
		if($this->session->userdata('bgs_be_active')){
			
			$q = $this->payments_model->submit_paybill_overpayment_assign_transaction();

			if($q['status'] == TRUE){
				$resp = array('status' => 'SUCCESS','message' => $q['message']);			
			}else{					
				$resp = array('status' => 'ERR','message' => $q['message']);			
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);
	}


	//PESAPAL PAYMENTS
	function pesapal() {
		if($this->session->userdata('bgs_be_active')) {
			// if ($this->auth_model->validate_user_access('paybill_payments_view', $this->session->userdata('system_user_id')) == false){
			// 	redirect('be/auth/access_denied');
			// } else {
				$data['page_title'] = 'Pesapal Payments | ';

				$data['cur'] = 'Payments';
				$data['cur_sub'] = 'Pesapal Payments';
				$data['cur_cur_sub'] = '';

				$data['main_content'] = 'be/pesapal_payments';
				$this->load->view('be/includes/template',$data);
			//}
        } 
		else {
            redirect('be/auth');
		}
	}

	function filter_js_pesapal_payments() {
		$data['pesapal_payments'] = $this->payments_model->get_pesapal_payments();
		$this->load->view('be/jsloads/pesapal_payments',$data);
	}



}