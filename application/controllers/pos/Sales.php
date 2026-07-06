<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->model('pos/main_model');
		$this->load->model('be/currencies_model');
		$this->load->model('pos/auth_model');
		$this->load->model('be/products_model');
		$this->load->library("Pdf");
		$this->load->library("Dpdf");
	}

	function index(){
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				redirect('pos/sales/new_sale');
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function new_sale() {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_sales_orders_add', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$data['cur'] = 'Sales Orders';
					$data['cur_sub'] = 'New Sales Order';
					$data['cur_cur_sub'] = '';

					$data['active_outlet'] = $this->main_model->get_active_outlet();
					$data['product_categories'] = $this->main_model->get_nested_product_categories();
					$data['credit_terms'] = $this->main_model->get_credit_terms();

					$q = $this->main_model->get_pending_sale();
					$data['pending_sale'] = $q['records'];
					$data['num_pending_sale'] = $q['record_count'];

					$data['default_currency'] = $this->currencies_model->get_default_currency();

					$data['page_title'] = 'New Sale | ';
					$data['main_content'] = 'pos/sales_new';
					$this->load->view('pos/includes/template',$data);
				}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function sales_list() {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_sales_orders_view', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$data['cur'] = 'Sales Orders';
					$data['cur_sub'] = 'Sales Orders';
					$data['cur_cur_sub'] = '';

					$data['active_outlet'] = $this->main_model->get_active_outlet();
					$data['default_currency'] = $this->currencies_model->get_default_currency();
					$data['email_accounts'] = $this->main_model->get_email_accounts();

					$data['sbr_pos_sales_orders_add'] = $this->auth_model->validate_user_access('pos_sales_orders_add', $this->session->userdata('pos_system_user_id'));

					$data['page_title'] = 'Sales List | ';
					$data['main_content'] = 'pos/sales_list';
					$this->load->view('pos/includes/template',$data);
				}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	//HOLD LIST
	function hold_list() {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_sales_orders_view', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$data['cur'] = 'Sales Orders';
					$data['cur_sub'] = 'Hold List';
					$data['cur_cur_sub'] = '';

					$data['active_outlet'] = $this->main_model->get_active_outlet();
					$data['default_currency'] = $this->currencies_model->get_default_currency();

					$data['email_accounts'] = $this->main_model->get_email_accounts();

					$data['page_title'] = 'Hold List | ';
					$data['main_content'] = 'pos/sales_hold_list';
					$this->load->view('pos/includes/template',$data);
				}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function load_ajax_sales_list() {
		$data['sales_list'] = $this->main_model->get_sales_list();
		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$data['sbr_pos_sales_orders_view'] = $this->auth_model->validate_user_access('pos_sales_orders_view', $this->session->userdata('pos_system_user_id'));
		$data['sbr_pos_sales_orders_edit'] = $this->auth_model->validate_user_access('pos_sales_orders_edit', $this->session->userdata('pos_system_user_id'));
		$data['sbr_pos_sales_orders_delete'] = $this->auth_model->validate_user_access('pos_sales_orders_delete', $this->session->userdata('pos_system_user_id'));
		$data['sbr_pos_sales_orders_print'] = $this->auth_model->validate_user_access('pos_sales_orders_print', $this->session->userdata('pos_system_user_id'));

		$this->load->view('pos/jsloads/sales_list',$data);
	}

	function load_ajax_sales_hold_list() {
		$data['sales_hold_list'] = $this->main_model->get_sales_hold_list();
		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$data['sbr_pos_sales_orders_edit'] = $this->auth_model->validate_user_access('pos_sales_orders_edit', $this->session->userdata('pos_system_user_id'));
		$data['sbr_pos_sales_orders_delete'] = $this->auth_model->validate_user_access('pos_sales_orders_delete', $this->session->userdata('pos_system_user_id'));

		$this->load->view('pos/jsloads/sales_hold_list',$data);
	}

	function view($pos_sale_id) {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_sales_orders_view', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$data['cur'] = 'View Sale';
					$data['cur_sub'] = '';
					$data['cur_cur_sub'] = '';

					$data['active_outlet'] = $this->main_model->get_active_outlet();
					$data['product_categories'] = $this->main_model->get_nested_product_categories();
					
					$data['pos_sale'] = $this->main_model->get_pos_sale($pos_sale_id);
					$data['pos_sale_details'] = $this->main_model->get_pos_sale_details($pos_sale_id);
					$data['pos_sale_tax_details'] = $this->main_model->get_pos_sale_tax_details($pos_sale_id);
					$data['pos_sale_payments'] = $this->main_model->get_pos_sale_payments($pos_sale_id);
					$data['num_pos_sale_payments'] = $this->main_model->get_num_pos_sale_payments($pos_sale_id);

					$data['default_currency'] = $this->currencies_model->get_default_currency();

					$data['store_information'] = $this->main_model->get_store_information();
					$data['email_accounts'] = $this->main_model->get_email_accounts();

					$data['sbr_pos_sales_orders_edit'] = $this->auth_model->validate_user_access('pos_sales_orders_edit', $this->session->userdata('pos_system_user_id'));
					$data['sbr_pos_sales_orders_delete'] = $this->auth_model->validate_user_access('pos_sales_orders_delete', $this->session->userdata('pos_system_user_id'));
					$data['sbr_pos_sales_orders_print'] = $this->auth_model->validate_user_access('pos_sales_orders_print', $this->session->userdata('pos_system_user_id'));

					$data['page_title'] = 'View Sale | ';
					$data['main_content'] = 'pos/sales_view';
					$this->load->view('pos/includes/template',$data);
				}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function edit($pos_sale_id) {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_sales_orders_edit', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$data['cur'] = 'Sales Orders';
					$data['cur_sub'] = 'Edit Sale';
					$data['cur_cur_sub'] = '';

					$data['active_outlet'] = $this->main_model->get_active_outlet();
					$data['product_categories'] = $this->main_model->get_nested_product_categories();
					$data['credit_terms'] = $this->main_model->get_credit_terms();
					
					$data['pos_sale'] = $this->main_model->get_pos_sale($pos_sale_id);
					$data['pos_sale_details'] = $this->main_model->get_pos_sale_details($pos_sale_id);
					$data['num_pos_sale_details'] = $this->main_model->get_num_pos_sale_details($pos_sale_id);

					$data['pos_sale_payments'] = $this->main_model->get_pos_sale_payments($pos_sale_id);
					$data['num_pos_sale_payments'] = $this->main_model->get_num_pos_sale_payments($pos_sale_id);

					$data['default_currency'] = $this->currencies_model->get_default_currency();

					$data['store_information'] = $this->main_model->get_store_information();

					$data['page_title'] = 'Edit Sale | ';
					$data['main_content'] = 'pos/sales_edit';
					$this->load->view('pos/includes/template',$data);
				}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function print_sale($pos_sale_id) {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_sales_orders_print', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$data['cur'] = 'Print Sale';
					$data['cur_sub'] = '';
					$data['cur_cur_sub'] = '';

					$data['active_outlet'] = $this->main_model->get_active_outlet();
					
					$data['pos_sale'] = $this->main_model->get_pos_sale($pos_sale_id);
					$data['pos_sale_details'] = $this->main_model->get_pos_sale_details($pos_sale_id);
					$data['num_pos_sale_details'] = $this->main_model->get_num_pos_sale_details($pos_sale_id);

					$data['pos_sale_payments'] = $this->main_model->get_pos_sale_payments($pos_sale_id);
					$data['num_pos_sale_payments'] = $this->main_model->get_num_pos_sale_payments($pos_sale_id);

					$data['default_currency'] = $this->currencies_model->get_default_currency();

					$data['store_information'] = $this->main_model->get_store_information();

					$data['page_title'] = 'Print Sale | ';
					//$data['main_content'] = 'pos/sales_edit';
					$this->load->view('pos/sales_print',$data);
				}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function print_a4($pos_sale_id) {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_sales_orders_print', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$dompdf = new Dpdf();
					$pos_sale = $this->main_model->get_pos_sale($pos_sale_id);
					$data['pos_sale'] = $pos_sale;
					$data['pos_sale_details'] = $this->main_model->get_pos_sale_details($pos_sale_id);
					$data['num_pos_sale_details'] = $this->main_model->get_num_pos_sale_details($pos_sale_id);
					$data['pos_sale_tax_details'] = $this->main_model->get_pos_sale_tax_details($pos_sale_id);
					$data['pos_sale_payments'] = $this->main_model->get_pos_sale_payments($pos_sale_id);
					$data['num_pos_sale_payments'] = $this->main_model->get_num_pos_sale_payments($pos_sale_id);

					$data['default_currency'] = $this->currencies_model->get_default_currency();

					$data['store_information'] = $this->main_model->get_store_information();

					$data['page_title'] = 'Print Sale | ';

					$this->load->view('pos/sales_print',$data);
				// 	$html_content = $this->load->view('pos/sales_print',$data,true);
				// 	foreach ($pos_sale as $row) {
				// 		$pos_sale_number = $row->pos_sale_number;
				// 	}
				// 	$dompdf->loadHtml($html_content);

				// 	$dompdf->render();

				// 	$dompdf->stream($pos_sale_number . ".pdf", array("Attachment" => false));
				}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function print_thermal($pos_sale_id) {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_sales_orders_print', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$data['cur'] = 'Transactions';
					$data['cur_sub'] = 'Cash Sales';
					$data['cur_cur_sub'] = '';

					$data['active_outlet'] = $this->main_model->get_active_outlet();
					
					$data['pos_sale'] = $this->main_model->get_pos_sale($pos_sale_id);
					$data['pos_sale_details'] = $this->main_model->get_pos_sale_details($pos_sale_id);
					$data['pos_sale_tax_details'] = $this->main_model->get_pos_sale_tax_details($pos_sale_id);
					$data['num_pos_sale_details'] = $this->main_model->get_num_pos_sale_details($pos_sale_id);

					$data['pos_sale_payments'] = $this->main_model->get_pos_sale_payments($pos_sale_id);
					$data['num_pos_sale_payments'] = $this->main_model->get_num_pos_sale_payments($pos_sale_id);

					$data['default_currency'] = $this->currencies_model->get_default_currency();

					$data['store_information'] = $this->main_model->get_store_information();

					$data['page_title'] = 'Print Sales Order | ';
					//$data['main_content'] = 'pos/sales_edit';
					$this->load->view('pos/thermal_print',$data);
				}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function print_receipt($pos_sale_id) {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_sales_orders_print', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$data['cur'] = 'Print Sale';
					$data['cur_sub'] = '';
					$data['cur_cur_sub'] = '';

					$data['active_outlet'] = $this->main_model->get_active_outlet();
					
					$data['pos_sale'] = $this->main_model->get_pos_sale($pos_sale_id);
					$data['pos_sale_details'] = $this->main_model->get_pos_sale_details($pos_sale_id);
					$data['num_pos_sale_details'] = $this->main_model->get_num_pos_sale_details($pos_sale_id);

					$data['pos_sale_payments'] = $this->main_model->get_pos_sale_payments($pos_sale_id);
					$data['num_pos_sale_payments'] = $this->main_model->get_num_pos_sale_payments($pos_sale_id);

					$data['default_currency'] = $this->currencies_model->get_default_currency();

					$data['store_information'] = $this->main_model->get_store_information();

					$data['page_title'] = 'Print Receipt | ';
					//$data['main_content'] = 'pos/sales_edit';
					$this->load->view('pos/receipt_print',$data);
				}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function pdf($pos_sale_id) {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_sales_orders_print', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
				
					$pos_sale = $this->main_model->get_pos_sale($pos_sale_id);
					$pos_sale_details = $this->main_model->get_pos_sale_details($pos_sale_id);
					$num_pos_sale_details = $this->main_model->get_num_pos_sale_details($pos_sale_id);
					$pos_sale_payments = $this->main_model->get_pos_sale_payments($pos_sale_id);
					$num_pos_sale_payments = $this->main_model->get_num_pos_sale_payments($pos_sale_id);
					$default_currency = $this->currencies_model->get_default_currency();
					$store_information = $this->main_model->get_store_information();

					foreach ($pos_sale as $row) {

						$payment_balance = $row->total_sale - $row->total_paid;

						$filename='Bethany House Sales Order - '.$row->pos_sale_number.'.pdf';

			            // create new PDF document
			            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


			            // set document information
			            $pdf->SetCreator(PDF_CREATOR);
			            $pdf->SetAuthor('Bethany House');
			            $pdf->SetTitle('Bethany House Sales Order - '.$row->pos_sale_number);
			            $pdf->SetSubject('Bethany House Sales Order - '.$row->pos_sale_number);
			            //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

			            // remove default header/footer
			            $pdf->setPrintHeader(false);
			            $pdf->setPrintFooter(false);

			            // set default monospaced font
			            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

			            // set margins
			            //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

			            // set auto page breaks
			            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

			            // set image scale factor
			            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

			            // set font
			            $pdf->SetFont('helvetica', '', 8);

			            $pdf->setCellHeightRatio(1.6);

			            // add a page
			            $pdf->AddPage();

			            $pdf->Ln(10);

			            $txt = '<table border="1" cellpadding="5" cellspacing="0">';
			            $txt = $txt . '<thead>';
			            $store_logo = '';
	                    foreach ($store_information as $row2){
	                        if($row2->store_logo != '' && file_exists("./uploads/store_logo/" . $row2->store_logo)){
	                            $store_logo = base_url() . 'uploads/store_logo/' . $row2->store_logo;
	                        } else {
	                            $store_logo = base_url() . 'assets/fe/img/logo.png';
	                        }
	                    }
			            $txt = $txt . '<tr>
			            	<td rowspan="2" width="136"><img src="' . $store_logo . '"  width="200px"></td>
			            	<td rowspan="2" width="200">';

			            	foreach ($store_information as $row2){
	                            $txt = $txt . '<b>' . $row2->store_name . '</b><br />
	                            <b>Phone:</b> ' . $row2->phone_number . '<br />
	                            <b>Address:</b> ' . $row2->physical_address . '<br />
	                            <b>Email:</b> ' . $row2->email_address;
	                        }
			            	$txt = $txt . '</td>
			            	<td width="112"><b>Sale No:</b><br/>' . $row->pos_sale_number . '</td>
			            	<td width="112"><b>Date:</b><br/>' . date('d M, Y', strtotime($row->created_on)) . '</td>
			            	<td width="113"><b>Status:</b><br/>';
			            		if ($row->is_void == 1) {
			            			$txt = $txt . 'VOID';
			            		} else {
			            			if ($payment_balance == $row->total_sale){
			            				$txt = $txt . 'UNPAID';
			            			} elseif ($payment_balance > 0){
			            				$txt = $txt . 'PARTIALLY PAID';
			            			} else {
			            				$txt = $txt . 'PAID';
			            			}
			            		}
			            	$txt = $txt . '</td>
			            	</tr>
			            	<tr>';
			            	if ($row->customer_id == 0){ $customer_name =  $row->customer_name; } else { $customer_name = $row->first_name . ' ' . $row->last_name; }

			            		$txt = $txt . '<td colspan="3"><b>Customer</b><br/>' . $customer_name . '</td>
			            	</tr></thead></table>';


		                $pdf->writeHTML($txt, true, false, false, false, '');

		                $txt = '<table border="1" cellpadding="5" cellspacing="0">
		               		<thead>
		               			<tr>
		               				<td width="30"><b>#</b></td>
		               				<td width="160"><b>Item Description</b></td>
		               				<td width="80"><b>Unit Cost</b></td>
		               				<td width="50"><b>Qty</b></td>
		               				<td width="70"><b>Tax</b></td>
		               				<td width="65"><b>Tax Amt</b></td>
		               				<td width="70"><b>Disc.</b></td>
		               				<td width="68"><b>Disc. Amt</b></td>
		               				<td width="80"><b>Amount</b></td>
		               			</tr>
		               		</thead>
		               		<tbody>';
		               	$count = 1;
		               	foreach ($pos_sale_details as $row2){
		               		$variation_description = '';
		               		if(!empty($row2->attributes)){
		            			foreach ($row2->attributes as $row3){
		            				$variation_description = $variation_description . $row3->product_attribute_name . ' : <b>' . $row3->product_attribute_value . '</b>, ';
		            			}
		            			$variation_description =  '~ ' . substr($variation_description,0,-2) . '<br>';
		            		}															
		               		$txt = $txt . '<tr>
		               			<td width="30">' . $count . '</td>
		           				<td width="160">'. $row2->product_name . '<br>' . $variation_description . '<i>SKU: ' . $row2->product_sku_code . '</i></td>
	               				<td width="80">' . $default_currency . ' ' . number_format($row2->unit_price,2) . '</td>
	               				<td width="50">' . number_format($row2->quantity,2) . '</td>
	               				<td width="70">' . number_format($row2->tax_rate_value,2) . '%<br><i>' . $row2->tax_rate_name . ' [' . $row2->tax_rate_code . ']</i></td>
	               				<td width="65">' . $default_currency . ' ' . number_format($row2->unit_tax,2) . '</td>
	               				<td width="70">' . $row2->discount_type . '<br><i>[' . number_format($row2->discount_value,2) . ']</i></td>
	               				<td width="68">' . $default_currency . ' ' . number_format($row2->discount_amount,2) . '</td>
	               				<td width="80">' . $default_currency . ' ' . number_format($row2->sub_total,2) . '</td>
		               		</tr>';
		               		$count++;
		               	}
		               	$txt = $txt . '<tr>
		               		<td colspan="8" align="right"><b>Total Qty</b></td>
		               		<td><b>' . number_format($row->total_quantity,2) . '</b></td>
		               	</tr>';
		               	$txt = $txt . '<tr>
		               		<td colspan="8" align="right"><b>Subtotal</b></td>
		               		<td><b>' . $default_currency . ' ' . number_format($row->sub_total,2) . '</b></td>
		               	</tr>';
		               	$txt = $txt . '<tr>
		               		<td colspan="8" align="right"><b>Discount On All</b></td>
		               		<td><b>' . $default_currency . ' ' . number_format($row->overall_discount,2) . '</b></td>
		               	</tr>';
		               	$txt = $txt . '<tr>
		               		<td colspan="8" align="right"><b>Delivery Fee</b></td>
		               		<td><b>' . $default_currency . ' ' . number_format($row->delivery_fee,2) . '</b></td>
		               	</tr>';
		               	$txt = $txt . '<tr>
		               		<td colspan="8" align="right"><b>Grand Total</b></td>
		               		<td><b>' . $default_currency . ' ' . number_format($row->total_sale,2) . '</b></td>
		               	</tr>';
		               	$txt = $txt . '<tr>
		               		<td colspan="8" align="right"><b>Paid Amount</b></td>
		               		<td><b>' . $default_currency . ' ' . number_format($row->total_paid,2) . '</b></td>
		               	</tr>';
		               	if ($payment_balance < 0) {
		               		$txt = $txt . '<tr>
			               		<td colspan="8" align="right"><b>Change</b></td>
			               		<td><b>' . $default_currency . ' ' . number_format(($payment_balance * -1),2) . '</b></td>
			               	</tr>';
		               	} elseif ($payment_balance > 0) {
		               		$txt = $txt . '<tr>
			               		<td colspan="8" align="right"><b>Balance</b></td>
			               		<td><b>' . $default_currency . ' ' . number_format($payment_balance,2) . '</b></td>
			               	</tr>';
		               	}
		               	
		               $txt = $txt . '</tbody></table>';

		               	$pdf->writeHTML($txt, true, false, false, false, '');

		               	$txt = '<table border="1" cellpadding="5" cellspacing="0">
		               			<tr>
		               				<td colspan="2"><b>Note:</b> '. $row->comments . '</td>
		               			</tr>
		               			<tr>
		               				<td colspan="2"><b>Terms & Conditions</b><br>1) No warranty for damaged or burnt goods.<br />
	                                    2) For warranty/repairs/replacement bring sale order copy.<br />
	                                    3) Goods once sold will not be taken back.<br />
	                                    4) Warranty at the sole discretion of the respective service center.<br />
	                                    5) Cheque bouncing attracts an unconditional fine of KES. 5,000.00
		               				</td>
		               			</tr>
		               			<tr>
		               				<td><b>Customer Signature</b><br><br><br></td>
		               				<td><b>Authorised Signatory</b><br><br><br></td>
		               			</tr>
		               			<tr>
		               				<td colspan="2" align="center"><small>Printed On: '. date('d-m-Y') . '</small></td>
		               			</tr>
		               		<tbody>';
		               	$txt = $txt . '</tbody></table>';
		               	$pdf->writeHTML($txt, true, false, false, false, '');


		            	$pdf->Output($filename, 'I');
					}
				}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function get_pending_sale_info() {
		$q = $this->main_model->get_pending_sale();
		$data['pending_sale'] = $q['records'];
		$data['num_pending_sale'] = $q['record_count'];

		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$this->load->view('pos/jsloads/pending_sale',$data);
	}

	function get_edit_sale_info($pos_sale_id) {
		$data['pos_sale'] = $this->main_model->get_pos_sale($pos_sale_id);
		$data['pos_sale_details'] = $this->main_model->get_pos_sale_details($pos_sale_id);
		$data['num_pos_sale_details'] = $this->main_model->get_num_pos_sale_details($pos_sale_id);

		$data['pos_sale_payments'] = $this->main_model->get_pos_sale_payments($pos_sale_id);
		$data['num_pos_sale_payments'] = $this->main_model->get_num_pos_sale_payments($pos_sale_id);

		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$this->load->view('pos/jsloads/edit_sale_info',$data);
	}

	function load_sale_products() {
		$context = $this->input->post('context');
		$q = $this->main_model->get_sale_products();
		$data['products'] = $q['records'];
		$data['num_products'] = $q['record_count'];
		$data['context'] = $context;
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$this->load->view('pos/jsloads/sale_products',$data);
	}

	function sale_product_search() {
		$search_result = '';
        $search_keyword = $this->input->post('search_keyword');
        $context = $this->input->post('context');

        $q = $this->main_model->get_sale_product_search_results($search_keyword);

        $search_results = $q['records'];
        $num_search_results = $this->main_model->get_num_sale_product_search_results($search_keyword);

        if ($num_search_results > 0) {
            $search_result .= '<ul>';

            foreach ($search_results as $row) {
                $product_price = 0;
                if ($row->sale_price > 0) {
                    $product_price = $row->sale_price;
                }else {
                    $product_price = $row->regular_price;
                }
                if ($row->product_image_thumb != '' && file_exists("./uploads/product_images/thumbs/" . $row->product_image_thumb)) {
                    $search_result .= '<li><a class="sale_product_search_option" data-product-id="' . $row->product_id . '" data-product-type="' . $row->product_type . '" data-context="' . $context . '"><div class="row"><div class="col-xs-2"><img src="' . base_url() . 'uploads/product_images/thumbs/' . $row->product_image_thumb . '" style="max-width:40px;" class="img-thumbnail"></div><div class="col-xs-6 text-left"><div class="white-space-normal"><strong>' . $row->product_name . '</strong></div><div class="text-muted"><small><strong>SKU: ' . $row->product_sku_code . '</strong> | <strong>Qty: </strong>' . $row->available_stock . '</small></div></div><div class="col-xs-4 text-right">KES ' . number_format($product_price) . '</div></div></a></li>';
                }else {
                    $search_result .= '<li><a class="sale_product_search_option" data-product-id="' . $row->product_id . '" data-product-type="' . $row->product_type . '" data-context="' . $context . '"><div class="row"><div class="col-xs-2"><img src="' . base_url() . 'assets/fe/img/placeholder.png" style="max-width:40px;" class="img-thumbnail"></div><div class="col-xs-6 text-left"><div class="white-space-normal"><strong>' . $row->product_name . '</strong></div><div class="text-muted"><small><strong>SKU: ' . $row->product_sku_code . '</strong> | <strong>Qty: </strong>' . $row->available_stock . '</small></div></div><div class="col-xs-4 text-right">KES ' . number_format($product_price) . '</div></div></a></li>';
                }
                
            }

            $search_result .= '</ul>';
        }
        echo $search_result;
	}

	function loadjs_select_product_variations(){
		$product_id = $this->input->post('product_id');
		$context = $this->input->post('context');
		$transaction_context = $this->input->post('transaction_context');
		
		$data['context'] =  $context;
		$data['transaction_context'] =  $transaction_context;
		$data['product_id'] =  $product_id;

		if ($context == 'Edit Sale') {
			$data['pos_sale_id'] =  $this->input->post('transaction_id');
		} elseif ($context == 'Edit Sales Return') {
			$data['pos_sales_return_id'] =  $this->input->post('transaction_id');
		} elseif ($context == 'Edit Quotation') {
			$data['pos_quotation_id'] =  $this->input->post('transaction_id');
		}

		$data['product'] = $this->main_model->get_product($product_id);
		$data['product_variations'] = $this->main_model->get_product_variations($product_id);
		$data['num_product_variations'] = $this->main_model->get_num_product_variations($product_id);

		$data['default_currency'] = $this->currencies_model->get_default_currency();


		$this->load->view('pos/jsloads/select_product_variations',$data);

	}

	// function loadjs_select_product_variations(){
	// 	$product_id = $this->input->post('product_id');
	// 	$context = $this->input->post('context');
	// 	$data['context'] =  $context;
	// 	$data['product_id'] =  $product_id;

	// 	if ($context == 'New Quotation'){
	// 		$data['product'] = $this->products_model->get_product($product_id);
	// 		$data['product_variations'] = $this->products_model->get_product_variations($product_id);
	// 		$data['num_product_variations'] = $this->products_model->get_num_product_variations($product_id);
	// 	}
	// 	$data['default_currency'] = $this->currencies_model->get_default_currency();


	// 	$this->load->view('pos/jsloads/select_product_variations',$data);

	// }

	function loadjs_select_payment_mpesa_transactions() {
		$context = $this->input->post('context');
		
		$data['context'] =  $context;

		if ($context == 'Edit Sale') {
			$data['pos_sale_id'] =  $this->input->post('pos_sale_id');
		}

		$data['paybill_payments'] = $this->main_model->get_pending_paybill_payments();
		$data['default_currency'] = $this->currencies_model->get_default_currency();


		$this->load->view('pos/jsloads/select_mpesa_payment_transactions',$data);
	}

	function sale_customer_search() {
		$search_result = '';
        $search_keyword = $this->input->post('search_keyword');
        $context = $this->input->post('context');

        $q = $this->main_model->get_sale_customer_search_results($search_keyword);

        $search_results = $q['records'];
        $num_search_results = $this->main_model->get_num_sale_customer_search_results($search_keyword);

        if ($num_search_results > 0) {
            $search_result .= '<ul>';

            foreach ($search_results as $row) {
                if ($row->profile_picture_thumb != '' && file_exists("./uploads/customer_profile_pictures/thumbs/" . $row->profile_picture_thumb)) {
                    $search_result .= '<li><a class="sale_customer_search_option" data-customer-id="' . $row->customer_id . '" data-context="' . $context . '"><div class="row"><div class="col-xs-2"><img src="' . base_url() . 'uploads/customer_profile_pictures/thumbs/' . $row->profile_picture_thumb . '" style="max-width:40px;" class="img-thumbnail"></div><div class="col-xs-10 text-left"><div class="white-space-normal"><strong>' . $row->first_name . ' ' . $row->last_name . '</strong></div><div class="text-muted"><small><strong>Email: ' . $row->email_address . '</strong> <br> <strong>Phone: </strong>' . $row->phone_number . '</small></div></div></div></a></li>';
                }else {
                    $search_result .= '<li><a class="sale_customer_search_option" data-customer-id="' . $row->customer_id . '" data-context="' . $context . '"><div class="row"><div class="col-xs-2"><img src="' . base_url() . 'assets/pos/images/user.png" style="max-width:40px;" class="img-thumbnail"></div><div class="col-xs-10 text-left"><div class="white-space-normal"><strong>' . $row->first_name . ' ' . $row->last_name . '</strong></div><div class="text-muted"><small><strong>Email: ' . $row->email_address . '</strong> <br> <strong>Phone: </strong>' . $row->phone_number . '</small></div></div></div></a></li>';
                }
                
            }

            $search_result .= '</ul>';
        }
        echo $search_result;
	}

	function submit_sale_add_customer() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->submit_sale_add_customer();

			if($q['res'] == true){
				
				$customer_id = $q['id'];
				$transaction_type = $this->input->post('transaction_type');
				$pos_sale_id = $this->input->post('pos_sale_id');

				if ($transaction_type == 'Add') {
					$q = $this->main_model->save_sale_customer($customer_id);

					if ($q['res'] == true){
						$customer_info = '';
						$pos_sale_id = $q['pos_sale_id'];

						$customer = $this->main_model->get_customer($customer_id);

						foreach ($customer as $row) {
							$customer_profile_picture = '';
							if ($row->profile_picture_thumb != '' && file_exists("./uploads/customer_profile_pictures/thumbs/" . $row->profile_picture_thumb)) {
								$customer_profile_picture = base_url() . 'uploads/customer_profile_pictures/thumbs/' . $row->profile_picture_thumb;
							} else {
								$customer_profile_picture = base_url() . 'assets/pos/images/user.png';
							}
							$customer_info = '<div class="customer-badge">
				                                <div class="avatar">
				                                    <img src="' . $customer_profile_picture . '" alt="" />
				                                </div>
				                                <div class="details">
				                                    <a href="#" class="name">' . $row->first_name . ' ' . $row->last_name . '</a>                                    

				                                    <span class="email">
				                                        <a><i class="ion-ios-telephone pr-1">' . $row->phone_number . '</i></a>
				                                    </span>
				                                    <br>
				                                    <span class="email">
				                                        <a href="mailto:' . $row->email_address . '"><i class="ion-email pr-1">' . $row->email_address . '</i></a>
				                                    </span>

				                                    <a href="' . base_url() .'pos/sales/customer_edit/' . $row->customer_id . '" id="edit_customer" class="btn btn-edit btn-primary pull-right" title="Edit Customer"><i class="ti-pencil-alt"></i></a>
				                                </div>
				                            </div>

				                            <div class="customer-action-buttons btn-group btn-group-justified">
				                                <a href="#" id="btn_detatch_customer" data-pos-sale-id="' . $pos_sale_id . '" class="btn"><i class="ion-close-circled"></i> Detach Customer</a>
				                            </div>';
						}
						$resp = array('status' => 'SUCCESS','data' => $customer_info);
					}else{
						$resp = array('status' => 'ERR','data' => $q['dt']);
					}
				} elseif ($transaction_type == 'Edit') {
					$q = $this->main_model->edit_sale_select_customer($customer_id);

					if ($q['res'] == true){
						$customer_info = '';
						$pos_sale_id = $q['pos_sale_id'];

						$customer = $this->main_model->get_customer($customer_id);

						foreach ($customer as $row) {
							$customer_profile_picture = '';
							if ($row->profile_picture_thumb != '' && file_exists("./uploads/customer_profile_pictures/thumbs/" . $row->profile_picture_thumb)) {
								$customer_profile_picture = base_url() . 'uploads/customer_profile_pictures/thumbs/' . $row->profile_picture_thumb;
							} else {
								$customer_profile_picture = base_url() . 'assets/pos/images/user.png';
							}
							$customer_info = '<div class="customer-badge">
				                                <div class="avatar">
				                                    <img src="' . $customer_profile_picture . '" alt="" />
				                                </div>
				                                <div class="details">
				                                    <a href="#" class="name">' . $row->first_name . ' ' . $row->last_name . '</a>                                    

				                                    <span class="email">
				                                        <a><i class="ion-ios-telephone pr-1">' . $row->phone_number . '</i></a>
				                                    </span>
				                                    <br>
				                                    <span class="email">
				                                        <a href="mailto:' . $row->email_address . '"><i class="ion-email pr-1">' . $row->email_address . '</i></a>
				                                    </span>

				                                    <a href="' . base_url() .'pos/sales/customer_edit/' . $row->customer_id . '" id="edit_customer" class="btn btn-edit btn-primary pull-right" title="Edit Customer"><i class="ti-pencil-alt"></i></a>
				                                </div>
				                            </div>

				                            <div class="customer-action-buttons btn-group btn-group-justified">
				                                <a href="#" id="btn_detatch_customer" data-pos-sale-id="' . $pos_sale_id . '" class="btn"><i class="ion-close-circled"></i> Detach Customer</a>
				                            </div>';
						}
						$resp = array('status' => 'SUCCESS','data' => $customer_info);
					}else{
						$resp = array('status' => 'ERR','data' => $q['dt']);
					}
				}
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue');
		}

		echo json_encode($resp);
	}

	function select_customer($customer_id) {

		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->save_sale_customer($customer_id);

			if ($q['res'] == true){
				$customer_info = '';
				$pos_sale_id = $q['pos_sale_id'];

				$customer = $this->main_model->get_customer($customer_id);

				foreach ($customer as $row) {
					$customer_profile_picture = '';
					if ($row->profile_picture_thumb != '' && file_exists("./uploads/customer_profile_pictures/thumbs/" . $row->profile_picture_thumb)) {
						$customer_profile_picture = base_url() . 'uploads/customer_profile_pictures/thumbs/' . $row->profile_picture_thumb;
					} else {
						$customer_profile_picture = base_url() . 'assets/pos/images/user.png';
					}
					$customer_info = '<div class="customer-badge">
		                                <div class="avatar">
		                                    <img src="' . $customer_profile_picture . '" alt="" />
		                                </div>
		                                <div class="details">
		                                    <a href="#" class="name">' . $row->first_name . ' ' . $row->last_name . '</a>                                    

		                                    <span class="email">
		                                        <a><i class="ion-ios-telephone pr-1">' . $row->phone_number . '</i></a>
		                                    </span>
		                                    <br>
		                                    <span class="email">
		                                        <a href="mailto:' . $row->email_address . '"><i class="ion-email pr-1">' . $row->email_address . '</i></a>
		                                    </span>

		                                    <a href="' . base_url() .'pos/sales/customer_edit/' . $row->customer_id . '" id="edit_customer" class="btn btn-edit btn-primary pull-right" title="Edit Customer"><i class="ti-pencil-alt"></i></a>
		                                </div>
		                            </div>

		                            <div class="customer-action-buttons btn-group btn-group-justified">
		                                <a href="#" id="btn_detatch_customer" data-pos-sale-id="' . $pos_sale_id . '" class="btn"><i class="ion-close-circled"></i> Detach Customer</a>
		                            </div>';
				}
				$resp = array('status' => 'SUCCESS','data' => $customer_info);
			}else{
				$resp = array('status' => 'ERR','data' => $q['dt']);
			}			
		} else {
			$resp = array('status' => 'ERR','data' => 'Your session seems to have expired. Please login again to continue');
		}
		echo json_encode($resp);
	}

	function edit_sale_select_customer($customer_id) {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->edit_sale_select_customer($customer_id);

			if ($q['res'] == true){
				$customer_info = '';
				$pos_sale_id = $q['pos_sale_id'];

				$customer = $this->main_model->get_customer($customer_id);

				foreach ($customer as $row) {
					$customer_profile_picture = '';
					if ($row->profile_picture_thumb != '' && file_exists("./uploads/customer_profile_pictures/thumbs/" . $row->profile_picture_thumb)) {
						$customer_profile_picture = base_url() . 'uploads/customer_profile_pictures/thumbs/' . $row->profile_picture_thumb;
					} else {
						$customer_profile_picture = base_url() . 'assets/pos/images/user.png';
					}
					$customer_info = '<div class="customer-badge">
		                                <div class="avatar">
		                                    <img src="' . $customer_profile_picture . '" alt="" />
		                                </div>
		                                <div class="details">
		                                    <a href="#" class="name">' . $row->first_name . ' ' . $row->last_name . '</a>                                    

		                                    <span class="email">
		                                        <a><i class="ion-ios-telephone pr-1">' . $row->phone_number . '</i></a>
		                                    </span>
		                                    <br>
		                                    <span class="email">
		                                        <a href="mailto:' . $row->email_address . '"><i class="ion-email pr-1">' . $row->email_address . '</i></a>
		                                    </span>

		                                    <a href="' . base_url() .'pos/sales/customer_edit/' . $row->customer_id . '" id="edit_customer" class="btn btn-edit btn-primary pull-right" title="Edit Customer"><i class="ti-pencil-alt"></i></a>
		                                </div>
		                            </div>

		                            <div class="customer-action-buttons btn-group btn-group-justified">
		                                <a href="#" id="btn_detatch_customer" data-pos-sale-id="' . $pos_sale_id . '" class="btn"><i class="ion-close-circled"></i> Detach Customer</a>
		                            </div>';
				}
				$resp = array('status' => 'SUCCESS','data' => $customer_info);
			}else{
				$resp = array('status' => 'ERR','data' => $q['dt']);
			}			
		} else {
			$resp = array('status' => 'ERR','data' => 'Your session seems to have expired. Please login again to continue');
		}
		echo json_encode($resp);
	}

	function detatch_customer($pos_sale_id) {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->detatch_sale_customer($pos_sale_id);

			if ($q['res'] == true){

				$pos_sale = $this->main_model->get_pos_sale($pos_sale_id);
				foreach ($pos_sale as $row) {
					if ($row->sale_type == 'CASH SALE') {
						$customer_info = '<div id="div_sale_search_customer" class="customer-form form-group">
                                    <div class="input-group contacts register-input-group">
                                        <div class="input-group-addon">
                                            <a href="javascript:;" class="lnk_add_customer none" title="New Customer" id="new-customer" tabindex="-1"><i class="ion-person-add"></i></a>
                                        </div>
                                        <input type="text" id="txt_sale_customer_search" name="customer" class="form-control add-customer-input keyboardLeft ui-autocomplete-input" data-title="Customer Name" placeholder="Search customer by name, email or phone..." autocomplete="off" />
                                    </div>
                                    <div id="sale_customers_suggestion" class="display-none"></div>

                                    <div class="mt-1">
                                        <div class="text-center"> - OR - </div>
                                        <a href="javascript:;" class="btn btn-sm btn-outline-info mt-1 mb-1 lnk_add_customer"><i class="ion-person mr-1"></i>Add Customer</a>
                                        <a href="javascript:;" class="btn btn-sm btn-outline-info mt-1 mb-1 lnk_enter_customer_name"><i class="ion-person mr-1"></i>Enter Customer Name</a>
                                    </div>
                                </div>';
					} else {
						$customer_info = '<div id="div_sale_search_customer" class="customer-form form-group">
                                    <div class="input-group contacts register-input-group">
                                        <div class="input-group-addon">
                                            <a href="javascript:;" class="lnk_add_customer none" title="New Customer" id="new-customer" tabindex="-1"><i class="ion-person-add"></i></a>
                                        </div>
                                        <input type="text" id="txt_sale_customer_search" name="customer" class="form-control add-customer-input keyboardLeft ui-autocomplete-input" data-title="Customer Name" placeholder="Search customer by name, email or phone..." autocomplete="off" />
                                    </div>
                                    <div id="sale_customers_suggestion" class="display-none"></div>

                                    <div class="mt-1">
                                        <div class="text-center"> - OR - </div>
                                        <a href="javascript:;" class="btn btn-sm btn-outline-info mt-1 mb-1 lnk_add_customer"><i class="ion-person mr-1"></i>Add Customer</a>
                                    </div>
                                </div>';
					}
				}				

				$resp = array('status' => 'SUCCESS','data' => $customer_info);				
			}else{
				$resp = array('status' => 'ERR','data' => $q['dt']);
			}			
		} else {
			$resp = array('status' => 'ERR','data' => 'Your session seems to have expired. Please login again to continue');
		}
		echo json_encode($resp);
	}

	function remove_customer_name($pos_sale_id) {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->remove_customer_name($pos_sale_id);

			if ($q['res'] == true){
				$pos_sale = $this->main_model->get_pos_sale($pos_sale_id);
				foreach ($pos_sale as $row) {
					if ($row->sale_type == 'CASH SALE') {
						$customer_info = '<div id="div_sale_search_customer" class="customer-form form-group">
                                    <div class="input-group contacts register-input-group">
                                        <div class="input-group-addon">
                                            <a href="javascript:;" class="lnk_add_customer none" title="New Customer" id="new-customer" tabindex="-1"><i class="ion-person-add"></i></a>
                                        </div>
                                        <input type="text" id="txt_sale_customer_search" name="customer" class="form-control add-customer-input keyboardLeft ui-autocomplete-input" data-title="Customer Name" placeholder="Search customer by name, email or phone..." autocomplete="off" />
                                    </div>
                                    <div id="sale_customers_suggestion" class="display-none"></div>

                                    <div class="mt-1">
                                        <div class="text-center"> - OR - </div>
                                        <a href="javascript:;" class="btn btn-sm btn-outline-info mt-1 mb-1 lnk_add_customer"><i class="ion-person mr-1"></i>Add Customer</a>
                                        <a href="javascript:;" class="btn btn-sm btn-outline-info mt-1 mb-1 lnk_enter_customer_name"><i class="ion-person mr-1"></i>Enter Customer Name</a>
                                    </div>
                                </div>';
					} else {
						$customer_info = '<div id="div_sale_search_customer" class="customer-form form-group">
                                    <div class="input-group contacts register-input-group">
                                        <div class="input-group-addon">
                                            <a href="javascript:;" class="lnk_add_customer none" title="New Customer" id="new-customer" tabindex="-1"><i class="ion-person-add"></i></a>
                                        </div>
                                        <input type="text" id="txt_sale_customer_search" name="customer" class="form-control add-customer-input keyboardLeft ui-autocomplete-input" data-title="Customer Name" placeholder="Search customer by name, email or phone..." autocomplete="off" />
                                    </div>
                                    <div id="sale_customers_suggestion" class="display-none"></div>

                                    <div class="mt-1">
                                        <div class="text-center"> - OR - </div>
                                        <a href="javascript:;" class="btn btn-sm btn-outline-info mt-1 mb-1 lnk_add_customer"><i class="ion-person mr-1"></i>Add Customer</a>
                                    </div>
                                </div>';
					}
				}
				$resp = array('status' => 'SUCCESS','data' => $customer_info);				
			}else{
				$resp = array('status' => 'ERR','data' => $q['dt']);
			}			
		} else {
			$resp = array('status' => 'ERR','data' => 'Your session seems to have expired. Please login again to continue');
		}
		echo json_encode($resp);
	}

	function sale_add_product() {
		if($this->session->userdata('pos_system_user_id')){

			$product_id = $this->input->post('product_id');
			$product_variation_id = $this->input->post('product_variation_id');

			$q = $this->main_model->sale_add_product($product_id, $product_variation_id);

			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','data' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','data' => $q['dt']);
			}			
		} else {
			$resp = array('status' => 'ERR','data' => 'Your session seems to have expired. Please login again to continue');
		}
		echo json_encode($resp);
	}

	function edit_sale_add_product() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->edit_sale_add_product();

			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','data' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','data' => $q['dt']);
			}			
		} else {
			$resp = array('status' => 'ERR','data' => 'Your session seems to have expired. Please login again to continue');
		}
		echo json_encode($resp);

	}

	function fetch_modify_pos_sale_details($pos_sale_detail_id) {
		if($this->session->userdata('pos_system_user_id')){

			$pos_sale_detail = $this->main_model->get_pos_sale_detail($pos_sale_detail_id);

			$product_id = 0;
			foreach ($pos_sale_detail as $row) {
				$product_id = $row->product_id;
			}

			$product_units = $this->main_model->get_product_units($product_id);

			$resp = array('status' => 'SUCCESS','message' => 'Successfully Retrieved', 'data' => $pos_sale_detail, 'units' => $product_units);

		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '', 'units' => '');
		}

		echo json_encode($resp);
	}

	function fetch_modify_product_unit_details() {

		$unit_id = $this->input->post('unit_id');
		$product_id = $this->input->post('product_id');
		$unit_price = $this->input->post('unit_price');

		$is_base_unit = false;
		$conversion_factor = 1;
		$base_unit_id = 0;

		if ($product_id !== '') {
			$product = $this->main_model->get_product($product_id);
			foreach ($product as $row) {
				$base_unit_id = $row->unit_id;
			}
			if ($base_unit_id == $unit_id || $unit_id == '') {
				$is_base_unit = true;
				$conversion_factor = 1;
			} else {
				$is_base_unit = false;
				$product_unit = $this->main_model->get_product_related_unit($product_id, $unit_id);
				foreach ($product_unit as $row) {
					$conversion_factor = $row->conversion_factor;
					$unit_price = $row->unit_price;
				}
			}
		}
		$resp = array('is_base_unit' => $is_base_unit,'conversion_factor' => $conversion_factor,'base_unit_id' => $base_unit_id,'base_unit_id' => $base_unit_id,'unit_price' => $unit_price);
		echo json_encode($resp);

	}

	function submit_modify_sales_item() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->submit_modify_sales_item();

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

	function remove_pos_sale_item($pos_sale_detail_id) {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->remove_pos_sale_item($pos_sale_detail_id);

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

	function sale_change_type_valid() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->sale_change_type_valid();

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'], 'data' => $q['data']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'], 'data' => $q['data']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}

		echo json_encode($resp);
	}

	function submit_change_sale_type() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->submit_change_sale_type();

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
 
	function sale_set_discount_valid() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->sale_set_discount_valid();

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'], 'data' => $q['data']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'], 'data' => $q['data']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}

		echo json_encode($resp);
	}

	function edit_sale_set_discount_valid($pos_sale_id) {
		if($this->session->userdata('pos_system_user_id')){

			$pos_sale = $this->main_model->get_pos_sale($pos_sale_id);

			$resp = array('status' => 'SUCCESS','message' => '', 'data' => $pos_sale);

		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}

		echo json_encode($resp);
	}

	function submit_sale_overall_discount() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->submit_sale_overall_discount();

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

	function sale_set_delivery_fee_valid() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->sale_set_delivery_fee_valid();

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'], 'data' => $q['data']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'], 'data' => $q['data']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}

		echo json_encode($resp);
	}

	function edit_sale_set_delivery_fee_valid($pos_sale_id) {
		if($this->session->userdata('pos_system_user_id')){

			$pos_sale = $this->main_model->get_pos_sale($pos_sale_id);

			$resp = array('status' => 'SUCCESS','message' => '', 'data' => $pos_sale);
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}

		echo json_encode($resp);
	}

	function submit_sale_delivery_fee() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->submit_sale_delivery_fee();

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

	function sale_set_date_valid() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->sale_set_date_valid();

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'], 'data' => $q['data']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'], 'data' => $q['data']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}

		echo json_encode($resp);
	}

	function edit_sale_set_date_valid($pos_sale_id) {
		if($this->session->userdata('pos_system_user_id')){

			$pos_sale = $this->main_model->get_pos_sale($pos_sale_id);

			$resp = array('status' => 'SUCCESS','message' => '', 'data' => $pos_sale);
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}

		echo json_encode($resp);
	}

	function sale_set_comments_valid() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->sale_set_comments_valid();

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'], 'data' => $q['data'], 'default_comments' => $q['default_comments']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'], 'data' => $q['data'], 'default_comments' => $q['default_comments']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '', 'default_comments' => '');
		}

		echo json_encode($resp);
	}

	function edit_sale_set_comments_valid($pos_sale_id) {
		if($this->session->userdata('pos_system_user_id')){

			$pos_sale = $this->main_model->get_pos_sale($pos_sale_id);

			$resp = array('status' => 'SUCCESS','message' => '', 'data' => $pos_sale);
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}

		echo json_encode($resp);
	}

	function submit_enter_customer_name() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->submit_enter_customer_name();

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

	function submit_sale_comments() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->submit_sale_comments();

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

	function submit_sale_date() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->submit_sale_date();

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

	function sale_add_customer_valid() {
		if($this->session->userdata('pos_system_user_id')){

			$resp = array('status' => 'SUCCESS','message' => '', 'data' => '');
			
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}

		echo json_encode($resp);
	}

	function sale_enter_customer_name_valid() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->sale_enter_customer_name_valid();

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'], 'data' => $q['data']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'], 'data' => $q['data']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}

		echo json_encode($resp);
	}

	function edit_sale_enter_customer_name_valid($pos_sale_id) {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->edit_sale_enter_customer_name_valid($pos_sale_id);

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'], 'data' => $q['data']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'], 'data' => $q['data']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}

		echo json_encode($resp);
	}

	function sale_make_payment_valid() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->sale_make_payment_valid();

			if($q['res'] == true){
				$mpesa_settings = $this->main_model->get_mpesa_settings();
				$resp = array('status' => 'SUCCESS','message' => $q['dt'], 'data' => $q['data'], 'mpesa' => $mpesa_settings);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'], 'data' => $q['data'], 'mpesa' => '');
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '', 'mpesa' => '');
		}

		echo json_encode($resp);
	}

	function edit_sale_make_payment_valid($pos_sale_id) {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->edit_sale_make_payment_valid($pos_sale_id);

			if($q['res'] == true){
				$mpesa_settings = $this->main_model->get_mpesa_settings();
				$resp = array('status' => 'SUCCESS','message' => $q['dt'], 'data' => $q['data'], 'mpesa' => $mpesa_settings);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'], 'data' => $q['data'], 'mpesa' => '');
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '', 'mpesa' => '');
		}

		echo json_encode($resp);
	}

	function submit_sale_payment() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->submit_sale_payment();

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

	function sale_payment_void_valid($pos_payment_id) {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->sale_payment_void_valid($pos_payment_id);

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

	function submit_void_sale_payment() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->submit_void_sale_payment();

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

	function sale_payment_modify_valid($pos_payment_id) {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->sale_payment_modify_valid($pos_payment_id);

			if($q['res'] == true){
				$mpesa_settings = $this->main_model->get_mpesa_settings();
				$resp = array('status' => 'SUCCESS','message' => $q['dt'], 'data' => $q['data'], 'sale' => $q['sale'], 'mpesa' => $mpesa_settings);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'], 'data' => $q['data'], 'sale' => $q['sale'], 'mpesa' => '');
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '', 'sale' => '', 'mpesa' => '');
		}

		echo json_encode($resp);
	}

	function submit_modify_sale_payment() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->submit_modify_sale_payment();

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

	function sale_complete_valid() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->sale_complete_valid();

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'], 'data' => $q['data']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'], 'data' => $q['data']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}

		echo json_encode($resp);
	}

	function complete_sale($pos_sale_id) {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->complete_sale($pos_sale_id);

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

	function sale_hold_valid() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->sale_hold_valid();

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'], 'data' => $q['data']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'], 'data' => $q['data']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}

		echo json_encode($resp);
	}

	function hold_sale($pos_sale_id) {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->hold_sale($pos_sale_id);

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

	function sale_cancel_valid() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->sale_cancel_valid();

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'], 'data' => $q['data']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'], 'data' => $q['data']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}

		echo json_encode($resp);
	}

	function cancel_sale($pos_sale_id) {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->cancel_sale($pos_sale_id);

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

	function get_email_account($email_account_id) {
		$email_account = $this->main_model->get_email_account($email_account_id);

		echo json_encode($email_account);
	}

	function get_pos_sale($pos_sale_id) {
		$pos_sale = $this->main_model->get_pos_sale($pos_sale_id);

		echo json_encode($pos_sale);
	}

	function submit_send_sale_order_via_email() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->submit_send_sale_order_via_email();

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

	function sale_void_valid($pos_sale_id) {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->sale_void_valid($pos_sale_id);

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

	function submit_void_pos_sale() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->submit_void_pos_sale();

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

	function resume_held_sale($pos_sale_id) {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->resume_held_sale($pos_sale_id);

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

	function cancel_held_sale($pos_sale_id) {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->cancel_held_sale($pos_sale_id);

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

	//SALES RETURNS
	function sales_returns() {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_sales_orders_view', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$data['cur'] = 'Sales Returns';
					$data['cur_sub'] = 'Sales Returns';
					$data['cur_cur_sub'] = '';

					$data['active_outlet'] = $this->main_model->get_active_outlet();
					$data['default_currency'] = $this->currencies_model->get_default_currency();
					$data['email_accounts'] = $this->main_model->get_email_accounts();

					$data['sbr_pos_sales_returns_add'] = $this->auth_model->validate_user_access('pos_sales_returns_add', $this->session->userdata('pos_system_user_id'));

					$data['page_title'] = 'Sales Returns List | ';
					$data['main_content'] = 'pos/sales_returns_list';
					$this->load->view('pos/includes/template',$data);
				}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function fetch_approve_sales_return($pos_sales_return_id) {
		$data['pos_sales_return'] = $this->main_model->get_pos_sales_return($pos_sales_return_id);
		$data['return_context'] = $this->input->post('return_context');
		$this->load->view('pos/jsloads/approve_sales_return',$data);
	}

	function approve_pos_sales_return(){
		if($this->session->userdata('bgs_pos_active')){
			$pos_sales_return_id = $this->input->post('pos_sales_return_id');
			$pos_sales_return_status = $this->input->post('pos_sales_return_status');
			
			if ($pos_sales_return_status == '1') {
				$approve_settlement = $this->input->post('approve_settlement');
				$rejection_reason = '';
				$approval_user = $this->session->userdata('pos_system_user_id');
				$approval_date = date('Y-m-d H:i:s');
				$rejection_user = 0;
				$rejection_date = '';
			} elseif ($pos_sales_return_status == '2') {
				$approve_settlement = '';
				$rejection_reason = $this->input->post('rejection_reason');
				$rejection_user = $this->session->userdata('pos_system_user_id');
				$rejection_date = date('Y-m-d H:i:s');
				$approval_user = 0;
				$approval_date = '';
			}

			$data = array(
				'return_status'=> $pos_sales_return_status,
				'return_settlement' => $approve_settlement,
				'approval_user' => $approval_user,
				'approval_date' => $approval_date,
				'rejection_user' => $rejection_user,
				'rejection_date' => $rejection_date,
				'rejection_reason' => $rejection_reason,
			);			
			$q = $this->main_model->approve_pos_sales_return($data, $pos_sales_return_id);
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

	function sales_return_approval_refund_success() {
		$this->session->set_flashdata('refund_now', 'successful');
	}

	function load_ajax_sales_returns_list() {
		$data['sales_returns_list'] = $this->main_model->get_sales_returns_list();
		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$data['sbr_pos_sales_returns_view'] = $this->auth_model->validate_user_access('pos_sales_returns_view', $this->session->userdata('pos_system_user_id'));
		$data['sbr_pos_sales_returns_edit'] = $this->auth_model->validate_user_access('pos_sales_returns_edit', $this->session->userdata('pos_system_user_id'));
		$data['sbr_pos_sales_returns_delete'] = $this->auth_model->validate_user_access('pos_sales_returns_delete', $this->session->userdata('pos_system_user_id'));
		$data['sbr_pos_sales_returns_print'] = $this->auth_model->validate_user_access('pos_sales_returns_print', $this->session->userdata('pos_system_user_id'));
		$data['sbr_pos_sales_returns_manage'] = $this->auth_model->validate_user_access('pos_sales_returns_manage', $this->session->userdata('pos_system_user_id'));

		$this->load->view('pos/jsloads/sales_returns_list',$data);
	}

	function new_sales_return() {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_sales_orders_add', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$data['cur'] = 'Sales Returns';
					$data['cur_sub'] = 'New Sales Return';
					$data['cur_cur_sub'] = '';

					$data['active_outlet'] = $this->main_model->get_active_outlet();
					$data['product_categories'] = $this->main_model->get_nested_product_categories();

					$q = $this->main_model->get_pending_sales_return();
					$data['pending_sales_return'] = $q['records'];
					$data['num_pending_sales_return'] = $q['record_count'];

					$data['default_currency'] = $this->currencies_model->get_default_currency();

					$data['page_title'] = 'New Sales Return | ';
					$data['main_content'] = 'pos/sales_return_new';
					$this->load->view('pos/includes/template',$data);
				}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function view_return($pos_sales_return_id) {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_sales_orders_view', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$data['cur'] = 'View Sales Return';
					$data['cur_sub'] = '';
					$data['cur_cur_sub'] = '';

					$data['active_outlet'] = $this->main_model->get_active_outlet();
					
					$data['pos_sales_return'] = $this->main_model->get_pos_sales_return($pos_sales_return_id);
					$data['pos_sales_return_details'] = $this->main_model->get_pos_sales_return_details($pos_sales_return_id);
					$data['pos_sales_return_tax_details'] = $this->main_model->get_pos_sales_return_tax_details($pos_sales_return_id);
					$data['pos_sales_return_refunds'] = $this->main_model->get_pos_sales_return_refunds($pos_sales_return_id);
					$data['num_pos_sales_return_refunds'] = $this->main_model->get_num_pos_sales_return_refunds($pos_sales_return_id);

					$data['default_currency'] = $this->currencies_model->get_default_currency();
					$data['store_information'] = $this->main_model->get_store_information();
					$data['email_accounts'] = $this->main_model->get_email_accounts();

					$data['sbr_pos_sales_returns_edit'] = $this->auth_model->validate_user_access('pos_sales_returns_edit', $this->session->userdata('pos_system_user_id'));
					$data['sbr_pos_sales_returns_delete'] = $this->auth_model->validate_user_access('pos_sales_returns_delete', $this->session->userdata('pos_system_user_id'));
					$data['sbr_pos_sales_returns_print'] = $this->auth_model->validate_user_access('pos_sales_returns_print', $this->session->userdata('pos_system_user_id'));
					$data['sbr_pos_sales_returns_manage'] = $this->auth_model->validate_user_access('pos_sales_returns_manage', $this->session->userdata('pos_system_user_id'));

					$data['page_title'] = 'View Sales Return | ';
					$data['main_content'] = 'pos/sales_return_view';
					$this->load->view('pos/includes/template',$data);
				}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function edit_return($pos_sales_return_id) {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_sales_orders_edit', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$data['cur'] = 'Edit Sales Return';
					$data['cur_sub'] = '';
					$data['cur_cur_sub'] = '';

					$data['active_outlet'] = $this->main_model->get_active_outlet();
					$data['product_categories'] = $this->main_model->get_nested_product_categories();
					$data['credit_terms'] = $this->main_model->get_credit_terms();
					
					$data['pos_sales_return'] = $this->main_model->get_pos_sales_return($pos_sales_return_id);
					$data['pos_sales_return_details'] = $this->main_model->get_pos_sales_return_details($pos_sales_return_id);
					$data['num_pos_sales_return_details'] = $this->main_model->get_num_pos_sales_return_details($pos_sales_return_id);

					// $data['pos_sales_return_payments'] = $this->main_model->get_pos_sales_return_payments($pos_sales_return_id);
					// $data['num_pos_sales_return_payments'] = $this->main_model->get_num_pos_sales_return_payments($pos_sales_return_id);

					$data['default_currency'] = $this->currencies_model->get_default_currency();
					$data['store_information'] = $this->main_model->get_store_information();

					$data['page_title'] = 'Edit Sales Return | ';
					$data['main_content'] = 'pos/sales_return_edit';
					$this->load->view('pos/includes/template',$data);
				}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function sales_return_add_product() {
		if($this->session->userdata('pos_system_user_id')){

			$product_id = $this->input->post('product_id');
			$product_variation_id = $this->input->post('product_variation_id');

			$q = $this->main_model->sales_return_add_product($product_id, $product_variation_id);

			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','data' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','data' => $q['dt']);
			}			
		} else {
			$resp = array('status' => 'ERR','data' => 'Your session seems to have expired. Please login again to continue');
		}
		echo json_encode($resp);
	}

	function edit_sales_return_add_product() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->edit_sales_return_add_product();

			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','data' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','data' => $q['dt']);
			}			
		} else {
			$resp = array('status' => 'ERR','data' => 'Your session seems to have expired. Please login again to continue');
		}
		echo json_encode($resp);

	}

	function get_pending_sales_return_info() {
		$q = $this->main_model->get_pending_sales_return();
		$data['pending_sales_return'] = $q['records'];
		$data['num_pending_sales_return'] = $q['record_count'];

		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$this->load->view('pos/jsloads/pending_sales_return',$data);
	}

	function get_edit_sales_return_info($pos_sales_return_id) {
		$data['pos_sales_return'] = $this->main_model->get_pos_sales_return($pos_sales_return_id);
		$data['pos_sales_return_details'] = $this->main_model->get_pos_sales_return_details($pos_sales_return_id);
		$data['num_pos_sales_return_details'] = $this->main_model->get_num_pos_sales_return_details($pos_sales_return_id);

		// $data['pos_sale_payments'] = $this->main_model->get_pos_sale_payments($pos_sale_id);
		// $data['num_pos_sale_payments'] = $this->main_model->get_num_pos_sale_payments($pos_sale_id);

		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$this->load->view('pos/jsloads/edit_sales_return_info',$data);
	}

	function fetch_modify_pos_sales_return_details($pos_sales_return_detail_id) {
		if($this->session->userdata('pos_system_user_id')){

			$pos_sales_return_detail = $this->main_model->get_pos_sales_return_detail($pos_sales_return_detail_id);

			$product_id = 0;
			foreach ($pos_sales_return_detail as $row) {
				$product_id = $row->product_id;
			}

			$product_units = $this->main_model->get_product_units($product_id);

			$resp = array('status' => 'SUCCESS','message' => 'Successfully Retrieved', 'data' => $pos_sales_return_detail, 'units' => $product_units);

		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '', 'units' => '');
		}

		echo json_encode($resp);
	}

	function submit_modify_sales_return_item() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->submit_modify_sales_return_item();

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

	function remove_pos_sales_return_item($pos_sales_return_detail_id) {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->remove_pos_sales_return_item($pos_sales_return_detail_id);

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

	function sales_return_set_date_valid() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->sales_return_set_date_valid();

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'], 'data' => $q['data']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'], 'data' => $q['data']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}

		echo json_encode($resp);
	}

	function edit_sales_return_set_date_valid($pos_sales_return_id) {
		if($this->session->userdata('pos_system_user_id')){

			$pos_sales_return = $this->main_model->get_pos_sales_return($pos_sales_return_id);

			$resp = array('status' => 'SUCCESS','message' => '', 'data' => $pos_sales_return);
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}

		echo json_encode($resp);
	}

	function submit_sales_return_date() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->submit_sales_return_date();

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

	function sales_return_select_customer($customer_id) {

		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->save_sales_return_customer($customer_id);

			if ($q['res'] == true){
				$customer_info = '';
				$pos_sales_return_id = $q['pos_sales_return_id'];

				$customer = $this->main_model->get_customer($customer_id);

				foreach ($customer as $row) {
					$customer_profile_picture = '';
					if ($row->profile_picture_thumb != '' && file_exists("./uploads/customer_profile_pictures/thumbs/" . $row->profile_picture_thumb)) {
						$customer_profile_picture = base_url() . 'uploads/customer_profile_pictures/thumbs/' . $row->profile_picture_thumb;
					} else {
						$customer_profile_picture = base_url() . 'assets/pos/images/user.png';
					}
					$customer_info = '<div class="customer-badge">
		                                <div class="avatar">
		                                    <img src="' . $customer_profile_picture . '" alt="" />
		                                </div>
		                                <div class="details">
		                                    <a href="#" class="name">' . $row->first_name . ' ' . $row->last_name . '</a>                                    

		                                    <span class="email">
		                                        <a><i class="ion-ios-telephone pr-1">' . $row->phone_number . '</i></a>
		                                    </span>
		                                    <br>
		                                    <span class="email">
		                                        <a href="mailto:' . $row->email_address . '"><i class="ion-email pr-1">' . $row->email_address . '</i></a>
		                                    </span>

		                                </div>
		                            </div>

		                            <div class="customer-action-buttons btn-group btn-group-justified">
		                                <a href="#" id="btn_return_detatch_customer" data-pos-sales-return-id="' . $pos_sales_return_id . '" class="btn"><i class="ion-close-circled"></i> Detach Customer</a>
		                            </div>';
				}
				$resp = array('status' => 'SUCCESS','data' => $customer_info);
			}else{
				$resp = array('status' => 'ERR','data' => $q['dt']);
			}			
		} else {
			$resp = array('status' => 'ERR','data' => 'Your session seems to have expired. Please login again to continue');
		}
		echo json_encode($resp);
	}

	function edit_sales_return_select_customer($customer_id) {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->edit_sales_return_select_customer($customer_id);

			if ($q['res'] == true){
				$customer_info = '';
				$pos_sales_return_id = $q['pos_sales_return_id'];

				$customer = $this->main_model->get_customer($customer_id);

				foreach ($customer as $row) {
					$customer_profile_picture = '';
					if ($row->profile_picture_thumb != '' && file_exists("./uploads/customer_profile_pictures/thumbs/" . $row->profile_picture_thumb)) {
						$customer_profile_picture = base_url() . 'uploads/customer_profile_pictures/thumbs/' . $row->profile_picture_thumb;
					} else {
						$customer_profile_picture = base_url() . 'assets/pos/images/user.png';
					}
					$customer_info = '<div class="customer-badge">
		                                <div class="avatar">
		                                    <img src="' . $customer_profile_picture . '" alt="" />
		                                </div>
		                                <div class="details">
		                                    <a href="#" class="name">' . $row->first_name . ' ' . $row->last_name . '</a>                                    

		                                    <span class="email">
		                                        <a><i class="ion-ios-telephone pr-1">' . $row->phone_number . '</i></a>
		                                    </span>
		                                    <br>
		                                    <span class="email">
		                                        <a href="mailto:' . $row->email_address . '"><i class="ion-email pr-1">' . $row->email_address . '</i></a>
		                                    </span>

		                                </div>
		                            </div>

		                            <div class="customer-action-buttons btn-group btn-group-justified">
		                                <a href="#" id="btn_return_detatch_customer" data-pos-sales-return-id="' . $pos_sales_return_id . '" class="btn"><i class="ion-close-circled"></i> Detach Customer</a>
		                            </div>';
				}
				$resp = array('status' => 'SUCCESS','data' => $customer_info);
			}else{
				$resp = array('status' => 'ERR','data' => $q['dt']);
			}			
		} else {
			$resp = array('status' => 'ERR','data' => 'Your session seems to have expired. Please login again to continue');
		}
		echo json_encode($resp);
	}

	function sales_return_detatch_customer($pos_sales_return_id) {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->detatch_sales_return_customer($pos_sales_return_id);

			if ($q['res'] == true){

				$pos_sales_return = $this->main_model->get_pos_sales_return($pos_sales_return_id);
				foreach ($pos_sales_return as $row) {
					$customer_info = '<div id="div_sales_return_search_customer" class="customer-form form-group">
                                <div class="input-group contacts register-input-group">
                                    <div class="input-group-addon">
                                        <a href="javascript:;" class="lnk_add_customer none" title="New Customer" id="new-customer" tabindex="-1"><i class="ion-person-add"></i></a>
                                    </div>
                                    <input type="text" id="txt_sales_return_customer_search" name="customer" class="form-control add-customer-input keyboardLeft ui-autocomplete-input" data-title="Customer Name" placeholder="Search customer by name, email or phone..." autocomplete="off" />
                                </div>
                                <div id="sales_return_customers_suggestion" class="display-none"></div>

                                <div class="mt-1">
                                    <div class="text-center"> - OR - </div>
                                    <a href="javascript:;" class="btn btn-sm btn-outline-info mt-1 mb-1 lnk_return_add_customer"><i class="ion-person mr-1"></i>Add Customer</a>
                                </div>
                            </div>';
				}				

				$resp = array('status' => 'SUCCESS','data' => $customer_info);				
			}else{
				$resp = array('status' => 'ERR','data' => $q['dt']);
			}			
		} else {
			$resp = array('status' => 'ERR','data' => 'Your session seems to have expired. Please login again to continue');
		}
		echo json_encode($resp);
	}

	function sales_return_add_customer_valid() {
		if($this->session->userdata('pos_system_user_id')){

			$resp = array('status' => 'SUCCESS','message' => '', 'data' => '');
			
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}

		echo json_encode($resp);
	}

	function submit_sales_return_add_customer() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->submit_sale_add_customer();

			if($q['res'] == true){
				
				$customer_id = $q['id'];
				$transaction_type = $this->input->post('transaction_type');
				$pos_sales_return_id = $this->input->post('pos_sales_return_id');

				if ($transaction_type == 'Add') {
					$q = $this->main_model->save_sales_return_customer($customer_id);

					if ($q['res'] == true){
						$customer_info = '';
						$pos_sales_return_id = $q['pos_sales_return_id'];

						$customer = $this->main_model->get_customer($customer_id);

						foreach ($customer as $row) {
							$customer_profile_picture = '';
							if ($row->profile_picture_thumb != '' && file_exists("./uploads/customer_profile_pictures/thumbs/" . $row->profile_picture_thumb)) {
								$customer_profile_picture = base_url() . 'uploads/customer_profile_pictures/thumbs/' . $row->profile_picture_thumb;
							} else {
								$customer_profile_picture = base_url() . 'assets/pos/images/user.png';
							}
							$customer_info = '<div class="customer-badge">
				                                <div class="avatar">
				                                    <img src="' . $customer_profile_picture . '" alt="" />
				                                </div>
				                                <div class="details">
				                                    <a href="#" class="name">' . $row->first_name . ' ' . $row->last_name . '</a>                                    

				                                    <span class="email">
				                                        <a><i class="ion-ios-telephone pr-1">' . $row->phone_number . '</i></a>
				                                    </span>
				                                    <br>
				                                    <span class="email">
				                                        <a href="mailto:' . $row->email_address . '"><i class="ion-email pr-1">' . $row->email_address . '</i></a>
				                                    </span>
				                                </div>
				                            </div>

				                            <div class="customer-action-buttons btn-group btn-group-justified">
				                                <a href="#" id="btn_detatch_customer" data-pos-sales_return-id="' . $pos_sales_return_id . '" class="btn"><i class="ion-close-circled"></i> Detach Customer</a>
				                            </div>';
						}
						$resp = array('status' => 'SUCCESS','data' => $customer_info);
					}else{
						$resp = array('status' => 'ERR','data' => $q['dt']);
					}
				} elseif ($transaction_type == 'Edit') {
					$q = $this->main_model->edit_sales_return_select_customer($customer_id);

					if ($q['res'] == true){
						$customer_info = '';
						$pos_sales_return_id = $q['pos_sales_return_id'];

						$customer = $this->main_model->get_customer($customer_id);

						foreach ($customer as $row) {
							$customer_profile_picture = '';
							if ($row->profile_picture_thumb != '' && file_exists("./uploads/customer_profile_pictures/thumbs/" . $row->profile_picture_thumb)) {
								$customer_profile_picture = base_url() . 'uploads/customer_profile_pictures/thumbs/' . $row->profile_picture_thumb;
							} else {
								$customer_profile_picture = base_url() . 'assets/pos/images/user.png';
							}
							$customer_info = '<div class="customer-badge">
				                                <div class="avatar">
				                                    <img src="' . $customer_profile_picture . '" alt="" />
				                                </div>
				                                <div class="details">
				                                    <a href="#" class="name">' . $row->first_name . ' ' . $row->last_name . '</a>                                    

				                                    <span class="email">
				                                        <a><i class="ion-ios-telephone pr-1">' . $row->phone_number . '</i></a>
				                                    </span>
				                                    <br>
				                                    <span class="email">
				                                        <a href="mailto:' . $row->email_address . '"><i class="ion-email pr-1">' . $row->email_address . '</i></a>
				                                    </span>
				                                </div>
				                            </div>

				                            <div class="customer-action-buttons btn-group btn-group-justified">
				                                <a href="#" id="btn_detatch_customer" data-pos-sales_return-id="' . $pos_sales_return_id . '" class="btn"><i class="ion-close-circled"></i> Detach Customer</a>
				                            </div>';
						}
						$resp = array('status' => 'SUCCESS','data' => $customer_info);
					}else{
						$resp = array('status' => 'ERR','data' => $q['dt']);
					}
				}
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue');
		}

		echo json_encode($resp);
	}

	function sales_return_set_comments_valid() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->sales_return_set_comments_valid();

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'], 'data' => $q['data']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'], 'data' => $q['data']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}

		echo json_encode($resp);
	}

	function edit_sales_return_set_comments_valid($pos_sales_return_id) {
		if($this->session->userdata('pos_system_user_id')){

			$pos_sales_return = $this->main_model->get_pos_sales_return($pos_sales_return_id);

			$resp = array('status' => 'SUCCESS','message' => '', 'data' => $pos_sales_return);
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}

		echo json_encode($resp);
	}

	function submit_sales_return_comments() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->submit_sales_return_comments();

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

	function sales_return_complete_valid() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->sales_return_complete_valid();

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'], 'data' => $q['data']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'], 'data' => $q['data']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}

		echo json_encode($resp);
	}

	function complete_sales_return($pos_sales_return_id) {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->complete_sales_return($pos_sales_return_id);

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

	function sales_return_make_refund_valid($pos_sales_return_id) {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->sales_return_make_refund_valid($pos_sales_return_id);

			if($q['res'] == true){
				$mpesa_settings = $this->main_model->get_mpesa_settings();
				$resp = array('status' => 'SUCCESS','message' => $q['dt'], 'data' => $q['data'], 'mpesa' => $mpesa_settings);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'], 'data' => $q['data'], 'mpesa' => '');
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '', 'mpesa' => '');
		}

		echo json_encode($resp);
	}

	function submit_sales_return_refund() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->submit_sales_return_refund();

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

	function print_return_a4($pos_sales_return_id) {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_sales_returns_orders_print', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$dompdf = new Dpdf();
					$pos_sales_return = $this->main_model->get_pos_sales_return($pos_sales_return_id);
					$data['pos_sales_return'] = $pos_sales_return;
					$data['pos_sales_return_details'] = $this->main_model->get_pos_sales_return_details($pos_sales_return_id);
					$data['num_pos_sales_return_details'] = $this->main_model->get_num_pos_sales_return_details($pos_sales_return_id);
					$data['pos_sales_return_tax_details'] = $this->main_model->get_pos_sales_return_tax_details($pos_sales_return_id);
					$data['pos_sales_return_refunds'] = $this->main_model->get_pos_sales_return_refunds($pos_sales_return_id);
					$data['num_pos_sales_return_refunds'] = $this->main_model->get_num_pos_sales_return_refunds($pos_sales_return_id);

					$data['default_currency'] = $this->currencies_model->get_default_currency();
					$data['store_information'] = $this->main_model->get_store_information();

					$data['page_title'] = 'Print sales_return | ';

					// $this->load->view('pos/sales_return_print',$data);
					$html_content = $this->load->view('pos/sales_return_print',$data,true);
					foreach ($pos_sales_return as $row) {
						$pos_sales_return_number = $row->pos_sales_return_number;
					}
					$dompdf->loadHtml($html_content);
					$dompdf->render();
					$dompdf->stream($pos_sales_return_number . ".pdf", array("Attachment" => false));
					}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}




	//PRODUCTS
	function products() {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_products_view', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$data['cur'] = 'Products';
					$data['cur_sub'] = '';
					$data['cur_cur_sub'] = '';

					$data['active_outlet'] = $this->main_model->get_active_outlet();
					$data['default_currency'] = $this->currencies_model->get_default_currency();

					//$data['email_accounts'] = $this->main_model->get_email_accounts();

					$data['page_title'] = 'Products List | ';
					$data['main_content'] = 'pos/products_list';
					$this->load->view('pos/includes/template',$data);
				}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function load_ajax_products_list(){
		$data['products'] = $this->main_model->get_products_list();
		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$this->load->view('pos/jsloads/products_list',$data);
	}

	//LOW STOCK LIST
	function low_stock() {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				$data['cur'] = 'Low Stock List';
				$data['cur_sub'] = '';
				$data['cur_cur_sub'] = '';

				$data['active_outlet'] = $this->main_model->get_active_outlet();
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				//$data['email_accounts'] = $this->main_model->get_email_accounts();

				$data['page_title'] = 'Low Stock List | ';
				$data['main_content'] = 'pos/low_stock_list';
				$this->load->view('pos/includes/template',$data);
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function load_ajax_low_stock_list() {
		$data['low_stock_list'] = $this->main_model->get_low_stock_list();
		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$this->load->view('pos/jsloads/low_stock_list',$data);
	}

	//CUSTOMERS
	function customers() {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_customers_view', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$data['cur'] = 'Customers';
					$data['cur_sub'] = '';
					$data['cur_cur_sub'] = '';

					$data['active_outlet'] = $this->main_model->get_active_outlet();
					$data['default_currency'] = $this->currencies_model->get_default_currency();

					//$data['email_accounts'] = $this->main_model->get_email_accounts();
					$data['sbr_pos_customers_add'] = $this->auth_model->validate_user_access('pos_customers_add', $this->session->userdata('pos_system_user_id'));

					$data['page_title'] = 'Customers | ';
					$data['main_content'] = 'pos/customers_list';
					$this->load->view('pos/includes/template',$data);
				}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function load_ajax_customers_list() {
		$data['customers'] = $this->main_model->get_customers_list();
		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$data['sbr_pos_customers_edit'] = $this->auth_model->validate_user_access('pos_customers_edit', $this->session->userdata('pos_system_user_id'));

		$this->load->view('pos/jsloads/customers_list',$data);
	}

	function customer_new() {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_customers_add', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$data['cur'] = 'Customers';
					$data['cur_sub'] = '';
					$data['cur_cur_sub'] = '';

					$data['active_outlet'] = $this->main_model->get_active_outlet();
					$data['default_currency'] = $this->currencies_model->get_default_currency();

					//$data['email_accounts'] = $this->main_model->get_email_accounts();

					$data['page_title'] = 'New Customer | ';
					$data['main_content'] = 'pos/customer_new';
					$this->load->view('pos/includes/template',$data);
				}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function save_customer() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->save_customer();

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

	function customer_edit($customer_id) {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_customers_edit', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$data['cur'] = 'Customers';
					$data['cur_sub'] = '';
					$data['cur_cur_sub'] = '';

					$data['active_outlet'] = $this->main_model->get_active_outlet();
					$data['default_currency'] = $this->currencies_model->get_default_currency();

					$data['customer'] = $this->main_model->get_customer($customer_id);

					$data['page_title'] = 'Edit Customer | ';
					$data['main_content'] = 'pos/customer_new';
					$this->load->view('pos/includes/template',$data);
				}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function update_customer() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->update_customer();

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

	function expenses() {
		if($this->session->userdata('bgs_pos_active')) {
			if($this->session->userdata('pos_outlet_id')) {
				if ($this->auth_model->validate_user_access('pos_expenses_view', $this->session->userdata('pos_system_user_id')) == false){
					redirect('pos/auth/access_denied');
				} else {
					$data['cur'] = 'Expenses';
					$data['cur_sub'] = '';
					$data['cur_cur_sub'] = '';

					$data['active_outlet'] = $this->main_model->get_active_outlet();
					$data['default_currency'] = $this->currencies_model->get_default_currency();

					//$data['email_accounts'] = $this->main_model->get_email_accounts();

					$data['sbr_pos_expenses_add'] = $this->auth_model->validate_user_access('pos_expenses_add', $this->session->userdata('pos_system_user_id'));

					$data['page_title'] = 'Expenses List | ';
					$data['main_content'] = 'pos/expenses_list';
					$this->load->view('pos/includes/template',$data);
				}
			} else {
				redirect('pos/auth/outlet_select');
			}
        } else {
            redirect('pos/auth');
		}
	}

	function load_ajax_expenses_list() {
		$data['expenses'] = $this->main_model->get_expenses_list();
		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$data['sbr_pos_expenses_edit'] = $this->auth_model->validate_user_access('pos_expenses_edit', $this->session->userdata('pos_system_user_id'));
		$data['sbr_pos_expenses_delete'] = $this->auth_model->validate_user_access('pos_expenses_delete', $this->session->userdata('pos_system_user_id'));

		$this->load->view('pos/jsloads/expenses_list',$data);
	}

	function save_expense() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->save_expense();

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

	function expense_edit_valid($expense_id) {
		if($this->session->userdata('pos_system_user_id')){

			$expense = $this->main_model->get_expense($expense_id);

			$resp = array('status' => 'SUCCESS','message' => 'Edit Valid', 'data' => $expense);
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}

		echo json_encode($resp);
	}

	function update_expense() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->update_expense();

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

	function expense_void_valid($expense_id) {
		if($this->session->userdata('pos_system_user_id')){

			$expense = $this->main_model->get_expense($expense_id);

			$resp = array('status' => 'SUCCESS','message' => 'Void Valid', 'data' => $expense);
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '');
		}

		echo json_encode($resp);
	}

	function submit_void_expense() {
		if($this->session->userdata('pos_system_user_id')){

			$q = $this->main_model->submit_void_expense();

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