<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Quotations extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->model('pos/main_model');
		$this->load->model('pos/auth_model');
		$this->load->model('be/currencies_model');
		$this->load->library("Pdf");
		$this->load->library("Dpdf");
	}

	function index(){
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				redirect('pos/quotations/list');
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function list() {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_quotations_view', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$data['cur'] = 'Quotations';
					$data['cur_sub'] = 'Quotations';
					$data['cur_cur_sub'] = '';

					$data['active_outlet'] = $this->main_model->get_active_outlet();
					$data['default_currency'] = $this->currencies_model->get_default_currency();
					$data['email_accounts'] = $this->main_model->get_email_accounts();

					$data['sbr_pos_quotations_add'] = $this->auth_model->validate_user_access('pos_quotations_add', $this->session->userdata('pos_system_user_id'));

					$data['page_title'] = 'Quotations List | ';
					$data['main_content'] = 'pos/quotations_list';
					$this->load->view('pos/includes/template',$data);
				}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function load_ajax_quotations_list() {
		$data['quotations_list'] = $this->main_model->get_quotations_list();
		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$data['sbr_pos_quotations_view'] = $this->auth_model->validate_user_access('pos_quotations_view', $this->session->userdata('pos_system_user_id'));
		$data['sbr_pos_quotations_edit'] = $this->auth_model->validate_user_access('pos_quotations_edit', $this->session->userdata('pos_system_user_id'));
		$data['sbr_pos_quotations_delete'] = $this->auth_model->validate_user_access('pos_quotations_delete', $this->session->userdata('pos_system_user_id'));
		$data['sbr_pos_quotations_print'] = $this->auth_model->validate_user_access('pos_quotations_print', $this->session->userdata('pos_system_user_id'));

		$this->load->view('pos/jsloads/quotations_list',$data);
	}

	function add() {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_quotations_add', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$data['cur'] = 'Quotations';
					$data['cur_sub'] = 'New Quotation';
					$data['cur_cur_sub'] = '';

					$data['active_outlet'] = $this->main_model->get_active_outlet();
					$data['product_categories'] = $this->main_model->get_nested_product_categories();

					$data['products'] = $this->main_model->get_products_list();
					$data['customers'] = $this->main_model->get_customers_list();

					$data['default_currency'] = $this->currencies_model->get_default_currency();

					$data['page_title'] = 'New Quotation | ';
					$data['main_content'] = 'pos/quotations_new';
					$this->load->view('pos/includes/template',$data);
				}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function get_product_details($product_id){
		$product = $this->main_model->get_product($product_id);
		echo json_encode($product);
	}

	function save() {
		if($this->session->userdata('pos_system_user_id')){
			$q = $this->main_model->save_quotation();
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'],'id' => $q['id']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt'],'id' => $q['id']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}			
		echo json_encode($resp);
	}

	function edit($pos_quotation_id) {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_quotations_edit', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$data['cur'] = 'Edit Quotation';
					$data['cur_sub'] = '';
					$data['cur_cur_sub'] = '';

					$data['active_outlet'] = $this->main_model->get_active_outlet();
					$data['products'] = $this->main_model->get_products_list();
					$data['product_categories'] = $this->main_model->get_nested_product_categories();
					
					$data['pos_quotation'] = $this->main_model->get_pos_quotation($pos_quotation_id);
					$data['pos_quotation_details'] = $this->main_model->get_pos_quotation_details($pos_quotation_id);
					$data['num_pos_quotation_details'] = $this->main_model->get_num_pos_quotation_details($pos_quotation_id);

					$data['default_currency'] = $this->currencies_model->get_default_currency();

					$data['store_information'] = $this->main_model->get_store_information();

					$data['page_title'] = 'Edit Quotation | ';
					$data['main_content'] = 'pos/quotations_new';
					$this->load->view('pos/includes/template',$data);
				}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function update(){
		if($this->session->userdata('pos_system_user_id')){
			$pos_quotation_id = $this->input->post('pos_quotation_id');

			$q = $this->main_model->update_quotation();

			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'],'id' => $pos_quotation_id);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt'],'id' => $pos_quotation_id);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}
		echo json_encode($resp);
	}

	function view($pos_quotation_id) {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_quotations_view', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$data['cur'] = 'View Quotation';
					$data['cur_sub'] = '';
					$data['cur_cur_sub'] = '';

					$data['active_outlet'] = $this->main_model->get_active_outlet();
					$data['product_categories'] = $this->main_model->get_nested_product_categories();
					
					$data['pos_quotation'] = $this->main_model->get_pos_quotation($pos_quotation_id);
					$data['pos_quotation_details'] = $this->main_model->get_pos_quotation_details($pos_quotation_id);
					$data['pos_quotation_tax_details'] = $this->main_model->get_pos_quotation_tax_details($pos_quotation_id);

					$data['default_currency'] = $this->currencies_model->get_default_currency();

					$data['store_information'] = $this->main_model->get_store_information();
					$data['email_accounts'] = $this->main_model->get_email_accounts();

					$data['sbr_pos_quotations_edit'] = $this->auth_model->validate_user_access('pos_quotations_edit', $this->session->userdata('pos_system_user_id'));
					$data['sbr_pos_quotations_delete'] = $this->auth_model->validate_user_access('pos_quotations_delete', $this->session->userdata('pos_system_user_id'));
					$data['sbr_pos_quotations_print'] = $this->auth_model->validate_user_access('pos_quotations_print', $this->session->userdata('pos_system_user_id'));

					$data['page_title'] = 'View Quotation | ';
					$data['main_content'] = 'pos/quotations_view';
					$this->load->view('pos/includes/template',$data);
				}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function print_a4($pos_quotation_id) {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_quotations_print', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$dompdf = new Dpdf();
					$pos_quotation = $this->main_model->get_pos_quotation($pos_quotation_id);
					$data['pos_quotation'] = $pos_quotation;
					$data['pos_quotation_details'] = $this->main_model->get_pos_quotation_details($pos_quotation_id);
					$data['num_pos_quotation_details'] = $this->main_model->get_num_pos_quotation_details($pos_quotation_id);
					$data['pos_quotation_tax_details'] = $this->main_model->get_pos_quotation_tax_details($pos_quotation_id);

					$data['default_currency'] = $this->currencies_model->get_default_currency();

					$data['store_information'] = $this->main_model->get_store_information();

					$data['page_title'] = 'Print quotation | ';

					// $this->load->view('pos/quotations_print',$data);
					$html_content = $this->load->view('pos/quotations_print',$data,true);
					foreach ($pos_quotation as $row) {
						$pos_quotation_number = $row->pos_quotation_number;
					}
					
					$dompdf->loadHtml($html_content);
					$dompdf->render();
					$dompdf->stream($pos_quotation_number . ".pdf", array("Attachment" => false));
				}
			}
		}

	}

	function void_valid($pos_quotation_id) {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->quotation_void_valid($pos_quotation_id);

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue');
		}

		echo json_encode($resp);
	}

	function submit_void() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->submit_void_pos_quotation();

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue');
		}

		echo json_encode($resp);
	}




}