<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inventory extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/inventory_model');
		$this->load->model('be/suppliers_model');
		$this->load->model('be/system_users_model');
		$this->load->model('be/outlets_model');
		$this->load->model('be/tax_rates_model');
		$this->load->model('be/products_model');
		$this->load->model('be/store_information_model');
		$this->load->model('be/currencies_model');
		$this->load->model('be/email_accounts_model');
		$this->load->model('be/auth_model');
		$this->load->library("Pdf");
		$this->load->library("Dpdf");
	}
	function index(){
        redirect('be/inventory/purchase_orders');
	}

	//PURCHASE ORDERS
	function purchase_orders(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('purchase_orders_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {

				$data['purchase_orders'] = $this->inventory_model->get_purchase_orders();
				$data['page_title'] = 'Purchase Orders | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Purchase Orders';

				$data['suppliers'] = $this->suppliers_model->get_suppliers_list();
				$data['system_users'] = $this->system_users_model->get_system_users_list();
				$data['sbr_purchase_orders_add'] = $this->auth_model->validate_user_access('purchase_orders_add', $this->session->userdata('system_user_id'));

				$data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();

				$data['main_content'] = 'be/purchase_orders';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function filter_js_purchase_orders(){
		$data['purchase_orders'] = $this->inventory_model->get_purchase_orders();
		$data['sbr_purchase_orders_view'] = $this->auth_model->validate_user_access('purchase_orders_view', $this->session->userdata('system_user_id'));
		$data['sbr_purchase_orders_edit'] = $this->auth_model->validate_user_access('purchase_orders_edit', $this->session->userdata('system_user_id'));
		$data['sbr_purchase_orders_delete'] = $this->auth_model->validate_user_access('purchase_orders_delete', $this->session->userdata('system_user_id'));
		$data['sbr_purchase_orders_print'] = $this->auth_model->validate_user_access('purchase_orders_print', $this->session->userdata('system_user_id'));
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$this->load->view('be/jsloads/purchase_orders',$data);
	}
	function export_purchases_report() {

		$data['date_from'] = $this->input->post('date_from');
        $data['date_to'] = $this->input->post('date_to');
        $data['purchase_order_status'] = $this->input->post('purchase_order_status');
        $data['purchase_order_status_text'] = $this->input->post('purchase_order_status_text');
        $data['payment_status'] = $this->input->post('payment_status');
        $data['system_user_id'] = $this->input->post('system_user_id');
        $data['system_user_name'] = $this->input->post('system_user_name');
        $data['supplier_id'] = $this->input->post('supplier_id');
        $data['supplier_name'] = $this->input->post('supplier_name');

		$data['purchase_orders'] = $this->inventory_model->get_purchase_orders();
		$data['store_information'] = $this->store_information_model->get_store_information();		
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$data['page_title'] = 'Purchases Report | ';
		
		// $this->load->view('be/purchases_report_print',$data);
		$dompdf = new Dpdf();
		$dompdf->set_paper('letter', 'landscape');
		$html_content = $this->load->view('be/purchases_report_print',$data,true);
		$dompdf->loadHtml($html_content);
		$dompdf->render();
		$dompdf->stream("Purchases Report.pdf", array("Attachment" => false));
	}
	function purchase_order_new(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('purchase_orders_add', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['suppliers'] = $this->suppliers_model->get_suppliers_list();
				$data['tax_rates'] = $this->tax_rates_model->get_tax_rates_list();
				$data['page_title'] = 'New Purchase Order | ';

				$outlet_id = $this->input->post('outlet_id');
				$data['outlet_id'] = $outlet_id;
				if ($outlet_id != '' && $outlet_id != null) {
					$data['low_stocks'] = $this->inventory_model->get_low_stocks();
				}

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Purchase Orders';

				$data['products'] = $this->products_model->get_products_list();
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['main_content'] = 'be/purchase_order_new';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function get_auto_purchase_order_products(){
		$a_json = array();
		$a_json_row = array();
		
		$term = trim(strip_tags($_GET['term'])); 
		
		$products = $this->inventory_model->get_auto_purchase_order_products($term);
		foreach($products as $prod){
			$prod_price = 0;
			// if ($prod->sale_price > 0){ $prod_price = $prod->sale_price; } else { $prod_price = $prod->regular_price; }
			$a_json_row["id"] = $prod->product_id;
			$a_json_row["value"] = htmlentities(stripslashes($prod->product_name));
			$a_json_row["label"] = htmlentities(stripslashes($prod->product_name));
			$a_json_row["desc"] = htmlentities(stripslashes($prod->product_sku_code));
			$a_json_row["type"] = htmlentities(stripslashes($prod->product_type));
			$a_json_row["unit_id"] = htmlentities(stripslashes($prod->unit_id));
			$a_json_row["units"] = $prod->units;
			$a_json_row["price"] = $prod->regular_price;
			array_push($a_json, $a_json_row);
		}
		echo json_encode($a_json);
		flush();
	}

	function save_purchase_order(){
		$q = $this->inventory_model->save_purchase_order();
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt'],'id' => $q['id']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt'],'id' => $q['id']);
		}			
		echo json_encode($resp);
	}
	function purchase_order_edit($purchase_order_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('purchase_orders_edit', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['suppliers'] = $this->suppliers_model->get_suppliers_list();
				$data['tax_rates'] = $this->tax_rates_model->get_tax_rates_list();
				$data['purchase_order'] = $this->inventory_model->get_purchase_order($purchase_order_id);
				$data['purchase_order_details'] = $this->inventory_model->get_purchase_order_details($purchase_order_id);
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['products'] = $this->products_model->get_products_list();

				$data['page_title'] = 'Edit Purchase Order | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Purchase Orders';

				$data['main_content'] = 'be/purchase_order_new';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function update_purchase_order(){

		$purchase_order_id = $this->input->post('purchase_order_id');

		$q = $this->inventory_model->update_purchase_order();

		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt'],'id' => $purchase_order_id);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt'],'id' => $purchase_order_id);
		}
		echo json_encode($resp);
	}

	function purchase_order_detail($purchase_order_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('purchase_orders_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['suppliers'] = $this->suppliers_model->get_suppliers_list();
				$data['tax_rates'] = $this->tax_rates_model->get_tax_rates_list();
				$data['purchase_order'] = $this->inventory_model->get_purchase_order($purchase_order_id);
				$data['purchase_order_details'] = $this->inventory_model->get_purchase_order_details($purchase_order_id);
				$data['purchase_order_tax_details'] = $this->inventory_model->get_purchase_order_tax_details($purchase_order_id);
				$data['num_purchase_order_tax_details'] = $this->inventory_model->get_num_purchase_order_tax_details($purchase_order_id);
				$data['purchase_order_payments'] = $this->inventory_model->get_purchase_order_payments($purchase_order_id);
				$data['num_purchase_order_payments'] = $this->inventory_model->get_num_purchase_order_payments($purchase_order_id);
				$data['default_currency'] = $this->currencies_model->get_default_currency();
				$data['store_information'] = $this->store_information_model->get_store_information();

				$data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();

				$data['page_title'] = 'Purchase Order Detail | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Purchase Orders';

				$data['sbr_purchase_orders_print'] = $this->auth_model->validate_user_access('purchase_orders_print', $this->session->userdata('system_user_id'));
				$data['sbr_purchase_orders_edit'] = $this->auth_model->validate_user_access('purchase_orders_edit', $this->session->userdata('system_user_id'));
				$data['sbr_goods_received_add'] = $this->auth_model->validate_user_access('goods_received_add', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/purchase_order_detail';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function get_purchase_order($purchase_order_id) {
		$purchase_order = $this->inventory_model->get_purchase_order($purchase_order_id);
		echo json_encode($purchase_order);
	}

	function get_purchase_order_details($purchase_order_id) {
		$purchase_order_details = $this->inventory_model->get_purchase_order_details($purchase_order_id);
		echo json_encode($purchase_order_details);
	}

	function get_purchase_order_detail($purchase_order_detail_id) {
		$purchase_order_detail = $this->inventory_model->get_purchase_order_detail($purchase_order_detail_id);
		echo json_encode($purchase_order_detail);
	}

	function purchase_order_print($purchase_order_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('purchase_orders_print', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				// $data['suppliers'] = $this->suppliers_model->get_suppliers_list();
				// $data['tax_rates'] = $this->tax_rates_model->get_tax_rates_list();
				$purchase_order = $this->inventory_model->get_purchase_order($purchase_order_id);
				$data['purchase_order'] = $purchase_order;
				$data['purchase_order_details'] = $this->inventory_model->get_purchase_order_details($purchase_order_id);
				$data['purchase_order_tax_details'] = $this->inventory_model->get_purchase_order_tax_details($purchase_order_id);
				$data['num_purchase_order_tax_details'] = $this->inventory_model->get_num_purchase_order_tax_details($purchase_order_id);
				$data['purchase_order_payments'] = $this->inventory_model->get_purchase_order_payments($purchase_order_id);
				$data['num_purchase_order_payments'] = $this->inventory_model->get_num_purchase_order_payments($purchase_order_id);

				$data['default_currency'] = $this->currencies_model->get_default_currency();
				$data['store_information'] = $this->store_information_model->get_store_information();

				$data['page_title'] = 'Purchase Order Print | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Purchase Orders';

				// $this->load->view('be/purchase_order_print',$data);
				$html_content = $this->load->view('be/purchase_order_print',$data,true);
				foreach ($purchase_order as $row) {
					$purchase_order_number = $row->purchase_order_number;
				}
				$dompdf = new Dpdf();
				$dompdf->loadHtml($html_content);
				$dompdf->render();
				$dompdf->stream($purchase_order_number . ".pdf", array("Attachment" => false));
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function purchase_order_print_supplier($purchase_order_id) {
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('purchase_orders_print', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				// $data['suppliers'] = $this->suppliers_model->get_suppliers_list();
				// $data['tax_rates'] = $this->tax_rates_model->get_tax_rates_list();
				$purchase_order = $this->inventory_model->get_purchase_order($purchase_order_id);
				$data['purchase_order'] = $purchase_order;
				$data['purchase_order_details'] = $this->inventory_model->get_purchase_order_details($purchase_order_id);
				$data['purchase_order_tax_details'] = $this->inventory_model->get_purchase_order_tax_details($purchase_order_id);
				$data['num_purchase_order_tax_details'] = $this->inventory_model->get_num_purchase_order_tax_details($purchase_order_id);
				$data['purchase_order_payments'] = $this->inventory_model->get_purchase_order_payments($purchase_order_id);
				$data['num_purchase_order_payments'] = $this->inventory_model->get_num_purchase_order_payments($purchase_order_id);

				$data['default_currency'] = $this->currencies_model->get_default_currency();
				$data['store_information'] = $this->store_information_model->get_store_information();

				$data['page_title'] = 'Purchase Order Print - Supplier Copy | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Purchase Orders';

				// $this->load->view('be/purchase_order_print_supplier',$data);
				$html_content = $this->load->view('be/purchase_order_print_supplier',$data,true);
				foreach ($purchase_order as $row) {
					$purchase_order_number = $row->purchase_order_number;
				}
				$dompdf = new Dpdf();
				$dompdf->loadHtml($html_content);
				$dompdf->render();
				$dompdf->stream($purchase_order_number . ".pdf", array("Attachment" => false));
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function purchase_order_make_payment_valid($purchase_order_id) {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->purchase_order_make_payment_valid($purchase_order_id);

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

	function submit_purchase_order_payment() {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->submit_purchase_order_payment();

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

	function purchase_payment_void_valid($purchase_payment_id) {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->purchase_payment_void_valid($purchase_payment_id);

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

	function submit_void_purchase_payment() {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->submit_void_purchase_payment();

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

	function purchase_payment_modify_valid($purchase_payment_id) {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->purchase_payment_modify_valid($purchase_payment_id);

			if($q['res'] == true){
				// $mpesa_settings = $this->main_model->get_mpesa_settings();
				$resp = array('status' => 'SUCCESS','message' => $q['dt'], 'data' => $q['data'], 'purchase_order' => $q['purchase_order']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'], 'data' => $q['data'], 'purchase_order' => $q['purchase_order']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue', 'data' => '', 'purchase_order' => '');
		}

		echo json_encode($resp);
	}

	function submit_modify_purchase_payment() {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->submit_modify_purchase_payment();

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


	function purchase_order_pdf($purchase_order_id) {
		if($this->session->userdata('bgs_be_active')) {

			$purchase_order = $this->inventory_model->get_purchase_order($purchase_order_id);
			$purchase_order_details = $this->inventory_model->get_purchase_order_details($purchase_order_id);

			$default_currency = $this->currencies_model->get_default_currency();
			$store_information = $this->store_information_model->get_store_information();

			foreach ($purchase_order as $row) {

				$filename='Bethany House Purchase Order - '.$row->purchase_order_number.'.pdf';

	            // create new PDF document
	            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


	            // set document information
	            $pdf->SetCreator(PDF_CREATOR);
	            $pdf->SetAuthor('Bethany House');
	            $pdf->SetTitle('Bethany House Purchase Order - '.$row->purchase_order_number);
	            $pdf->SetSubject('Bethany House Purchase Order - '.$row->purchase_order_number);
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
                if ($row->is_void == 1){
                    $status = 'Void';
                } else {
                	if ($row->total_received_qty == 0){
                        $status = 'Open';
                	} elseif ($row->total_received_qty < $row->total_detail_qty){
                        $status = 'Partially Received';
                	} elseif ($row->total_received_qty == $row->total_detail_qty){
                        $status = 'Closed';
                    }
                }
	            $txt = $txt . '<tr>
	            	<td rowspan="5" width="224"><img src="' . $store_logo . '"  width="200px"><br /><br />';

	            	// </td>
	            	// <td rowspan="2" width="200">';

	            	foreach ($store_information as $row2){
                        $txt = $txt . '<b>' . $row2->store_name . '</b><br />
                        <b>Phone:</b> ' . $row2->phone_number . '<br />
                        <b>Address:</b> ' . $row2->physical_address . '<br />
                        <b>Email:</b> ' . $row2->email_address;
                    }
	            	$txt = $txt . '</td>
	            	<td rowspan="4"></td>
	            	<td><b>Purchase Order No:</b> ' . $row->purchase_order_number . '</td>
	            	</tr>
	            	<tr>
	            		<td><b>Order Date:</b> ' . date('d M, Y', strtotime($row->created_on)) . '</td>
	            	</tr>
	            	<tr>
	            		<td><b>Expected Date:</b> ' . date('d M, Y', strtotime($row->purchase_order_date)) . '</td>
	            	</tr>
	            	<tr>
	            		<td><b>Status:</b> ' . $status . '</td>
	            	</tr>
	            	<tr>
	            		<td colspan="2"><b>Supplier:</b><br/>' . $row->supplier_name . '<br/>' . $row->email_address . '<br/>' . $row->phone_number . '</td>
	            	</tr></thead></table>';


                $pdf->writeHTML($txt, true, false, false, false, '');

                $txt = '<table border="1" cellpadding="5" cellspacing="0">
               		<thead>
               			<tr>
               				<td width="30"><b>#</b></td>
               				<td width="250"><b>Product Name</b></td>
               				<td width="90"><b>Ordered</b></td>
               				<td width="90"><b>Received</b></td>
               				<td width="100"><b>Unit Cost</b></td>
               				<td width="110"><b>Amount</b></td>
               			</tr>
               		</thead>
               		<tbody>';
               	$count = 1;
               	foreach ($purchase_order_details as $row2){
               		$variation_description = '';
               		if(!empty($row2->attributes)){
            			foreach ($row2->attributes as $row3){
            				$variation_description = $variation_description . $row3->product_attribute_name . ' : <b>' . $row3->product_attribute_value . '</b>, ';
            			}
            			$variation_description =  '~ ' . substr($variation_description,0,-2) . '<br>';
            		}															
               		$txt = $txt . '<tr>
               			<td width="30">' . $count . '</td>
           				<td width="250">'. $row2->product_name . '<br>' . $variation_description . '<i>SKU: ' . $row2->product_sku_code . '</i></td>
           				<td width="90">' . number_format($row2->detail_quantity,2) . '</td>
           				<td width="90">' . number_format($row2->received_quantity,2) . '</td>
           				<td width="100">' . $default_currency . ' ' . number_format($row2->unit_price,2) . '</td>
           				<td width="110">' . $default_currency . ' ' . number_format($row2->detail_total_amount,2) . '</td>
               		</tr>';
               		$count++;
               	}
               	$txt = $txt . '<tr>
               		<td colspan="5" align="right"><b>Subtotal</b></td>
               		<td><b>' . $default_currency . ' ' . number_format($row->sub_total,2) . '</b></td>
               	</tr>';
               	$txt = $txt . '<tr>
               		<td colspan="5" align="right"><b>Freight</b></td>
               		<td><b>' . $default_currency . ' ' . number_format($row->freight_cost,2) . '</b></td>
               	</tr>';
               	$txt = $txt . '<tr>
               		<td colspan="5" align="right"><b>Total</b></td>
               		<td><b>' . $default_currency . ' ' . number_format($row->total_amount,2) . '</b></td>
               	</tr>';
               	
               $txt = $txt . '</tbody></table>';

               	$pdf->writeHTML($txt, true, false, false, false, '');

               	$txt = '<table border="1" cellpadding="5" cellspacing="0">
               			<tr>
               				<td colspan="2" align="left"><b>Note:</b><br/>' . $row->purchase_order_note .  '</td>
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
		else {
            redirect('be/auth');
		}
	}

	function submit_send_purchase_order_via_email() {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->submit_send_purchase_order_via_email();

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

	function purchase_order_void_valid($purchase_order_id) {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->purchase_order_void_valid($purchase_order_id);

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'],'data' => $q['data']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'],'data' => $q['data']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue','data' => '');
		}

		echo json_encode($resp);
	}

	function submit_void_purchase_order() {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->submit_void_purchase_order();

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


	//GOODS RECEIPT NOTES
	function goods_receipt_notes(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('goods_received_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {

				$data['goods_receipt_notes'] = $this->inventory_model->get_goods_receipt_notes();
				$data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();

				$data['page_title'] = 'Goods Receipt Notes | ';

				$data['suppliers'] = $this->suppliers_model->get_suppliers_list();
				$data['system_users'] = $this->system_users_model->get_system_users_list();
				$data['outlets'] = $this->outlets_model->get_outlets_list();

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Goods Receipt Notes';

				$data['sbr_goods_received_add'] = $this->auth_model->validate_user_access('goods_received_add', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/goods_receipt_notes';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function filter_js_goods_receipt_notes(){
		$data['goods_receipt_notes'] = $this->inventory_model->get_goods_receipt_notes();
		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$data['sbr_goods_received_view'] = $this->auth_model->validate_user_access('goods_received_view', $this->session->userdata('system_user_id'));
		$data['sbr_goods_received_edit'] = $this->auth_model->validate_user_access('goods_received_edit', $this->session->userdata('system_user_id'));
		$data['sbr_goods_received_delete'] = $this->auth_model->validate_user_access('goods_received_delete', $this->session->userdata('system_user_id'));
		$data['sbr_goods_received_print'] = $this->auth_model->validate_user_access('goods_received_print', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/goods_receipt_notes',$data);
	}

	function export_goods_receipt_notes() {

		$data['date_from'] = $this->input->post('date_from');
        $data['date_to'] = $this->input->post('date_to');
        $data['status'] = $this->input->post('order_status');
        $data['status_text'] = $this->input->post('status_text');
        $data['outlet_id'] = $this->input->post('outlet_id');
        $data['outlet_name'] = $this->input->post('outlet_name');
        $data['system_user_id'] = $this->input->post('system_user_id');
        $data['system_user_name'] = $this->input->post('system_user_name');
        $data['supplier_id'] = $this->input->post('supplier_id');
        $data['supplier_name'] = $this->input->post('supplier_name');

		$data['goods_receipt_notes'] = $this->inventory_model->get_goods_receipt_notes();
		$data['store_information'] = $this->store_information_model->get_store_information();		
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$data['page_title'] = 'Goods Receipt Notes | ';
		
		// $this->load->view('be/purchases_report_print',$data);
		$dompdf = new Dpdf();
		$dompdf->set_paper('letter', 'landscape');
		$html_content = $this->load->view('be/goods_receipt_notes_print',$data,true);
		$dompdf->loadHtml($html_content);
		$dompdf->render();
		$dompdf->stream("Goods Receipt Notes.pdf", array("Attachment" => false));
	}

	function receive(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('goods_received_add', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['suppliers'] = $this->suppliers_model->get_suppliers_list();
				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$data['purchase_orders'] = $this->inventory_model->get_active_purchase_orders();
				$data['tax_rates'] = $this->tax_rates_model->get_tax_rates_list();
				$data['default_currency'] = $this->currencies_model->get_default_currency();
				$data['page_title'] = 'Receive Stock | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Goods Receipt Notes';

				$data['main_content'] = 'be/receive_stock';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_goods_receipt_note(){
		$q = $this->inventory_model->save_goods_receipt_note();
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt'],'id' => $q['id']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt'],'id' => $q['id']);
		}			
		echo json_encode($resp);
	}
	function goods_receipt_note_edit($goods_receipt_note_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('goods_received_edit', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['suppliers'] = $this->suppliers_model->get_suppliers_list();
				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$data['purchase_orders'] = $this->inventory_model->get_active_purchase_orders();
				$data['tax_rates'] = $this->tax_rates_model->get_tax_rates_list();
				$data['goods_receipt_note'] = $this->inventory_model->get_goods_receipt_note($goods_receipt_note_id);
				$data['goods_receipt_note_details'] = $this->inventory_model->get_goods_receipt_note_details($goods_receipt_note_id);

				$data['purchase_order_products'] = $this->products_model->get_purchase_order_products_by_goods_receipt_note_id($goods_receipt_note_id);
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['page_title'] = 'Edit Goods Receipt Note | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Goods Receipt Notes';

				$data['main_content'] = 'be/receive_stock';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function update_goods_receipt_note(){

		$goods_receipt_note_id = $this->input->post('goods_receipt_note_id');

		$q = $this->inventory_model->update_goods_receipt_note();

		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt'],'id' => $goods_receipt_note_id);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt'],'id' => $goods_receipt_note_id);
		}
		echo json_encode($resp);
	}
	function goods_receipt_note_detail($goods_receipt_note_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('goods_received_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['suppliers'] = $this->suppliers_model->get_suppliers_list();
				$data['tax_rates'] = $this->tax_rates_model->get_tax_rates_list();
				$data['goods_receipt_note'] = $this->inventory_model->get_goods_receipt_note($goods_receipt_note_id);
				$data['goods_receipt_note_details'] = $this->inventory_model->get_goods_receipt_note_details($goods_receipt_note_id);
				$data['store_information'] = $this->store_information_model->get_store_information();
				$data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['page_title'] = 'Goods Receipt Note Detail | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Goods Receipt Notes';

				$data['sbr_goods_received_print'] = $this->auth_model->validate_user_access('goods_received_print', $this->session->userdata('system_user_id'));
				$data['sbr_goods_received_edit'] = $this->auth_model->validate_user_access('goods_received_edit', $this->session->userdata('system_user_id'));
				$data['sbr_goods_received_delete'] = $this->auth_model->validate_user_access('goods_received_delete', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/goods_receipt_note_detail';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function get_goods_receipt_note($goods_receipt_note_id) {
		$goods_receipt_note = $this->inventory_model->get_goods_receipt_note($goods_receipt_note_id);
		echo json_encode($goods_receipt_note);
	}

	function goods_receipt_note_print($goods_receipt_note_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('goods_received_print', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$goods_receipt_note = $this->inventory_model->get_goods_receipt_note($goods_receipt_note_id);		
				$data['goods_receipt_note'] = $goods_receipt_note;
				$data['goods_receipt_note_details'] = $this->inventory_model->get_goods_receipt_note_details($goods_receipt_note_id);
				$data['store_information'] = $this->store_information_model->get_store_information();
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['page_title'] = 'Goods Receipt Note Print | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Goods Receipt Notes';

				// $this->load->view('be/goods_receipt_note_print',$data);
				$html_content = $this->load->view('be/goods_receipt_note_print',$data,true);
				foreach ($goods_receipt_note as $row) {
					$goods_receipt_note_number = $row->goods_receipt_note_number;
				}
				$dompdf = new Dpdf();
				$dompdf->loadHtml($html_content);
				$dompdf->render();
				$dompdf->stream($goods_receipt_note_number . ".pdf", array("Attachment" => false));
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function goods_receipt_note_pdf($goods_receipt_note_id) {
		if($this->session->userdata('bgs_be_active')) {

			$goods_receipt_note = $this->inventory_model->get_goods_receipt_note($goods_receipt_note_id);
			$goods_receipt_note_details = $this->inventory_model->get_goods_receipt_note_details($goods_receipt_note_id);

			$default_currency = $this->currencies_model->get_default_currency();
			$store_information = $this->store_information_model->get_store_information();

			foreach ($goods_receipt_note as $row) {

				$filename='Bethany House Goods Receipt Note - '.$row->goods_receipt_note_number.'.pdf';

	            // create new PDF document
	            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


	            // set document information
	            $pdf->SetCreator(PDF_CREATOR);
	            $pdf->SetAuthor('Bethany House');
	            $pdf->SetTitle('Bethany House Goods Receipt Note - '.$row->goods_receipt_note_number);
	            $pdf->SetSubject('Bethany House Goods Receipt Note - '.$row->goods_receipt_note_number);
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
	            	<td rowspan="5" width="224"><img src="' . $store_logo . '"  width="200px"><br /><br />';

	            	// </td>
	            	// <td rowspan="2" width="200">';

	            	foreach ($store_information as $row2){
                        $txt = $txt . '<b>' . $row2->store_name . '</b><br />
                        <b>Phone:</b> ' . $row2->phone_number . '<br />
                        <b>Address:</b> ' . $row2->physical_address . '<br />
                        <b>Email:</b> ' . $row2->email_address;
                    }
	            	$txt = $txt . '</td>
	            	<td rowspan="4"></td>
	            	<td><b>Goods Receipt Note No:</b> ' . $row->goods_receipt_note_number . '</td>
	            	</tr>
	            	<tr>
	            		<td><b>Outlet:</b> ' . $row->outlet_name . '</td>
	            	</tr>
	            	<tr>
	            		<td><b>Purchase Order:</b> ' . $row->purchase_order_number . '</td>
	            	</tr>
	            	<tr>
	            		<td><b>Receival Date:</b> ' . date('d M, Y', strtotime($row->receival_date)) . '</td>
	            	</tr>
	            	<tr>
	            		<td colspan="2"><b>Supplier:</b><br/>' . $row->supplier_name . '<br/>' . $row->email_address . '<br/>' . $row->phone_number . '</td>
	            	</tr></thead></table>';


                $pdf->writeHTML($txt, true, false, false, false, '');

                $txt = '<table border="1" cellpadding="5" cellspacing="0">
               		<thead>
               			<tr>
               				<td width="30"><b>#</b></td>
               				<td width="340"><b>Product Name</b></td>
               				<td width="90"><b>Received</b></td>
               				<td width="100"><b>Unit Cost</b></td>
               				<td width="110"><b>Amount</b></td>
               			</tr>
               		</thead>
               		<tbody>';
               	$count = 1;
               	foreach ($goods_receipt_note_details as $row2){
               		$variation_description = '';
               		if(!empty($row2->attributes)){
            			foreach ($row2->attributes as $row3){
            				$variation_description = $variation_description . $row3->product_attribute_name . ' : <b>' . $row3->product_attribute_value . '</b>, ';
            			}
            			$variation_description =  '~ ' . substr($variation_description,0,-2) . '<br>';
            		}															
               		$txt = $txt . '<tr>
               			<td width="30">' . $count . '</td>
           				<td width="340">'. $row2->product_name . '<br>' . $variation_description . '<i>SKU: ' . $row2->product_sku_code . '</i></td>
           				<td width="90">' . number_format($row2->received_quantity,2) . '</td>
           				<td width="100">' . $default_currency . ' ' . number_format($row2->unit_price,2) . '</td>
           				<td width="110">' . $default_currency . ' ' . number_format($row2->detail_total_amount,2) . '</td>
               		</tr>';
               		$count++;
               	}
               	$txt = $txt . '<tr>
               		<td colspan="3" align="right"><b>Subtotal</b></td>
               		<td><b>' . $default_currency . ' ' . number_format($row->sub_total,2) . '</b></td>
               	</tr>';
               	$txt = $txt . '<tr>
               		<td colspan="3" align="right"><b>Freight</b></td>
               		<td><b>' . $default_currency . ' ' . number_format($row->freight_cost,2) . '</b></td>
               	</tr>';
               	$txt = $txt . '<tr>
               		<td colspan="3" align="right"><b>Total</b></td>
               		<td><b>' . $default_currency . ' ' . number_format($row->total_amount,2) . '</b></td>
               	</tr>';
               	
               $txt = $txt . '</tbody></table>';

               	$pdf->writeHTML($txt, true, false, false, false, '');

               	$txt = '<table border="1" cellpadding="5" cellspacing="0">
               			<tr>
               				<td colspan="2" align="left"><b>Remark:</b><br/>' . $row->remark .  '</td>
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
		else {
            redirect('be/auth');
		}
	}

	function get_auto_receive_stock_products() {
		$a_json = array();
		$a_json_row = array();
		
		$term = trim(strip_tags($_GET['term'])); 
		$purchase_order_id = trim(strip_tags($_GET['purchase_order_id']));
		
		$products = $this->inventory_model->get_auto_receive_stock_products($term, $purchase_order_id);

		foreach($products as $prod){
			//$prod_price = 0;
			//if ($prod->sale_price > 0){ $prod_price = $prod->sale_price; } else { $prod_price = $prod>regular_price; }
			$a_json_row["id"] = $prod->product_id;
			$a_json_row["value"] = htmlentities(stripslashes($prod->product_name));
			$a_json_row["label"] = htmlentities(stripslashes($prod->product_name));
			$a_json_row["desc"] = htmlentities(stripslashes($prod->product_sku_code));
			$a_json_row["price"] = htmlentities(stripslashes($prod->unit_price));
			$a_json_row["type"] = htmlentities(stripslashes($prod->product_type));
			array_push($a_json, $a_json_row);
		}
		echo json_encode($a_json);
		flush();
	}

	function submit_send_goods_receipt_note_via_email() {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->submit_send_goods_receipt_note_via_email();

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

	function goods_receipt_note_void_valid($goods_receipt_note_id) {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->goods_receipt_note_void_valid($goods_receipt_note_id);

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'],'data' => $q['data']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'],'data' => $q['data']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue','data' => '');
		}

		echo json_encode($resp);
	}

	function submit_void_goods_receipt_note() {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->submit_void_goods_receipt_note();

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

	//GOODS RETURN NOTES
	function goods_return_notes(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('goods_returned_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {

				$data['goods_return_notes'] = $this->inventory_model->get_goods_return_notes();
				$data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();

				$data['suppliers'] = $this->suppliers_model->get_suppliers_list();
				$data['system_users'] = $this->system_users_model->get_system_users_list();
				$data['outlets'] = $this->outlets_model->get_outlets_list();

				$data['page_title'] = 'Goods Return Notes | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Goods Return Notes';

				$data['sbr_goods_returned_add'] = $this->auth_model->validate_user_access('goods_returned_add', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/goods_return_notes';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function filter_js_goods_return_notes(){
		$data['goods_return_notes'] = $this->inventory_model->get_goods_return_notes();
		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$data['sbr_goods_returned_view'] = $this->auth_model->validate_user_access('goods_returned_view', $this->session->userdata('system_user_id'));
		$data['sbr_goods_returned_edit'] = $this->auth_model->validate_user_access('goods_returned_edit', $this->session->userdata('system_user_id'));
		$data['sbr_goods_returned_delete'] = $this->auth_model->validate_user_access('goods_returned_delete', $this->session->userdata('system_user_id'));
		$data['sbr_goods_returned_print'] = $this->auth_model->validate_user_access('goods_returned_print', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/goods_return_notes',$data);
	}

	function export_goods_return_notes() {

		$data['date_from'] = $this->input->post('date_from');
        $data['date_to'] = $this->input->post('date_to');
        $data['status'] = $this->input->post('status');
        $data['status_text'] = $this->input->post('status_text');
        $data['outlet_id'] = $this->input->post('outlet_id');
        $data['outlet_name'] = $this->input->post('outlet_name');
        $data['system_user_id'] = $this->input->post('system_user_id');
        $data['system_user_name'] = $this->input->post('system_user_name');
        $data['supplier_id'] = $this->input->post('supplier_id');
        $data['supplier_name'] = $this->input->post('supplier_name');

		$data['goods_return_notes'] = $this->inventory_model->get_goods_return_notes();
		$data['store_information'] = $this->store_information_model->get_store_information();		
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$data['page_title'] = 'Goods Return Notes | ';
		
		// $this->load->view('be/goods_return_notes_print',$data);
		$dompdf = new Dpdf();
		$dompdf->set_paper('letter', 'landscape');
		$html_content = $this->load->view('be/goods_return_notes_print',$data,true);
		$dompdf->loadHtml($html_content);
		$dompdf->render();
		$dompdf->stream("Goods Return Notes.pdf", array("Attachment" => false));
	}

	function return(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('goods_returned_add', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['suppliers'] = $this->suppliers_model->get_suppliers_list();
				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$data['purchase_orders'] = $this->inventory_model->get_active_purchase_orders();
				$data['tax_rates'] = $this->tax_rates_model->get_tax_rates_list();
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['products'] = $this->products_model->get_products_list();

				$data['page_title'] = 'Return Stock | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Goods Return Notes';

				$data['main_content'] = 'be/return_stock';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_goods_return_note(){
		$q = $this->inventory_model->save_goods_return_note();
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt'],'id' => $q['id']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt'],'id' => $q['id']);
		}			
		echo json_encode($resp);
	}
	function goods_return_note_edit($goods_return_note_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('goods_returned_edit', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['suppliers'] = $this->suppliers_model->get_suppliers_list();
				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$data['purchase_orders'] = $this->inventory_model->get_active_purchase_orders();
				$data['tax_rates'] = $this->tax_rates_model->get_tax_rates_list();
				$data['goods_return_note'] = $this->inventory_model->get_goods_return_note($goods_return_note_id);
				$data['goods_return_note_details'] = $this->inventory_model->get_goods_return_note_details($goods_return_note_id);
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['products'] = $this->products_model->get_products_list();

				$data['page_title'] = 'Edit Goods Return Note | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Goods Return Notes';

				$data['main_content'] = 'be/return_stock';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function update_goods_return_note(){

		$goods_return_note_id = $this->input->post('goods_return_note_id');

		$q = $this->inventory_model->update_goods_return_note();

		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt'],'id' => $goods_return_note_id);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt'],'id' => $goods_return_note_id);
		}
		echo json_encode($resp);
	}
	function goods_return_note_detail($goods_return_note_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('goods_returned_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['suppliers'] = $this->suppliers_model->get_suppliers_list();
				$data['tax_rates'] = $this->tax_rates_model->get_tax_rates_list();
				$data['goods_return_note'] = $this->inventory_model->get_goods_return_note($goods_return_note_id);
				$data['goods_return_note_details'] = $this->inventory_model->get_goods_return_note_details($goods_return_note_id);
				$data['store_information'] = $this->store_information_model->get_store_information();
				$data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['page_title'] = 'Goods Return Note Detail | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Goods Return Notes';

				$data['sbr_goods_returned_print'] = $this->auth_model->validate_user_access('goods_returned_print', $this->session->userdata('system_user_id'));
				$data['sbr_goods_returned_edit'] = $this->auth_model->validate_user_access('goods_returned_edit', $this->session->userdata('system_user_id'));
				$data['sbr_goods_returned_delete'] = $this->auth_model->validate_user_access('goods_returned_delete', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/goods_return_note_detail';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function get_goods_return_note($goods_return_note_id) {
		$goods_return_note = $this->inventory_model->get_goods_return_note($goods_return_note_id);

		echo json_encode($goods_return_note);
	}

	function goods_return_note_print($goods_return_note_id) {
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('goods_returned_print', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$goods_return_note = $this->inventory_model->get_goods_return_note($goods_return_note_id);
				$data['goods_return_note'] = $goods_return_note;
				$data['goods_return_note_details'] = $this->inventory_model->get_goods_return_note_details($goods_return_note_id);
				$data['store_information'] = $this->store_information_model->get_store_information();
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['page_title'] = 'Goods Return Note Print | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Goods Return Notes';

				// $this->load->view('be/goods_return_note_print',$data);
				$html_content = $this->load->view('be/goods_return_note_print',$data,true);
				foreach ($goods_return_note as $row) {
					$goods_return_note_number = $row->goods_return_note_number;
				}
				$dompdf = new Dpdf();
				$dompdf->loadHtml($html_content);
				$dompdf->render();
				$dompdf->stream($goods_return_note_number . ".pdf", array("Attachment" => false));
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function goods_return_note_pdf($goods_return_note_id) {
		if($this->session->userdata('bgs_be_active')) {

			$goods_return_note = $this->inventory_model->get_goods_return_note($goods_return_note_id);
			$goods_return_note_details = $this->inventory_model->get_goods_return_note_details($goods_return_note_id);

			$default_currency = $this->currencies_model->get_default_currency();
			$store_information = $this->store_information_model->get_store_information();

			foreach ($goods_return_note as $row) {

				$filename='Bethany House Goods Return Note - '.$row->goods_return_note_number.'.pdf';

	            // create new PDF document
	            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


	            // set document information
	            $pdf->SetCreator(PDF_CREATOR);
	            $pdf->SetAuthor('Bethany House');
	            $pdf->SetTitle('Bethany House Goods Return Note - '.$row->goods_return_note_number);
	            $pdf->SetSubject('Bethany House Goods Return Note - '.$row->goods_return_note_number);
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
	            	<td rowspan="4" width="224"><img src="' . $store_logo . '"  width="200px"><br /><br />';

	            	// </td>
	            	// <td rowspan="2" width="200">';

	            	foreach ($store_information as $row2){
                        $txt = $txt . '<b>' . $row2->store_name . '</b><br />
                        <b>Phone:</b> ' . $row2->phone_number . '<br />
                        <b>Address:</b> ' . $row2->physical_address . '<br />
                        <b>Email:</b> ' . $row2->email_address;
                    }
	            	$txt = $txt . '</td>
	            	<td rowspan="3"></td>
	            	<td><b>Goods Return Note No:</b> ' . $row->goods_return_note_number . '</td>
	            	</tr>
	            	<tr>
	            		<td><b>Outlet:</b> ' . $row->outlet_name . '</td>
	            	</tr>
	            	<tr>
	            		<td><b>Return Date:</b> ' . date('d M, Y', strtotime($row->return_date)) . '</td>
	            	</tr>
	            	<tr>
	            		<td colspan="2"><b>Supplier:</b><br/>' . $row->supplier_name . '<br/>' . $row->email_address . '<br/>' . $row->phone_number . '</td>
	            	</tr></thead></table>';


                $pdf->writeHTML($txt, true, false, false, false, '');

                $txt = '<table border="1" cellpadding="5" cellspacing="0">
               		<thead>
               			<tr>
               				<td width="30"><b>#</b></td>
               				<td width="340"><b>Product Name</b></td>
               				<td width="90"><b>Returned</b></td>
               				<td width="100"><b>Unit Cost</b></td>
               				<td width="110"><b>Amount</b></td>
               			</tr>
               		</thead>
               		<tbody>';
               	$count = 1;
               	foreach ($goods_return_note_details as $row2){
               		$variation_description = '';
               		if(!empty($row2->attributes)){
            			foreach ($row2->attributes as $row3){
            				$variation_description = $variation_description . $row3->product_attribute_name . ' : <b>' . $row3->product_attribute_value . '</b>, ';
            			}
            			$variation_description =  '~ ' . substr($variation_description,0,-2) . '<br>';
            		}															
               		$txt = $txt . '<tr>
               			<td width="30">' . $count . '</td>
           				<td width="340">'. $row2->product_name . '<br>' . $variation_description . '<i>SKU: ' . $row2->product_sku_code . '</i></td>
           				<td width="90">' . number_format($row2->returned_quantity,2) . '</td>
           				<td width="100">' . $default_currency . ' ' . number_format($row2->unit_price,2) . '</td>
           				<td width="110">' . $default_currency . ' ' . number_format($row2->detail_total_amount,2) . '</td>
               		</tr>';
               		$count++;
               	}
               	$txt = $txt . '<tr>
               		<td colspan="4" align="right"><b>Subtotal</b></td>
               		<td><b>' . $default_currency . ' ' . number_format($row->sub_total,2) . '</b></td>
               	</tr>';
               	$txt = $txt . '<tr>
               		<td colspan="4" align="right"><b>Freight</b></td>
               		<td><b>' . $default_currency . ' ' . number_format($row->freight_cost,2) . '</b></td>
               	</tr>';
               	$txt = $txt . '<tr>
               		<td colspan="4" align="right"><b>Total</b></td>
               		<td><b>' . $default_currency . ' ' . number_format($row->total_amount,2) . '</b></td>
               	</tr>';
               	
               $txt = $txt . '</tbody></table>';

               	$pdf->writeHTML($txt, true, false, false, false, '');

               	$txt = '<table border="1" cellpadding="5" cellspacing="0">
               			<tr>
               				<td colspan="2" align="left"><b>Remark:</b><br/>' . $row->remark .  '</td>
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
		else {
            redirect('be/auth');
		}
	}

	function submit_send_goods_return_note_via_email() {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->submit_send_goods_return_note_via_email();

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

	function goods_return_note_void_valid($goods_return_note_id) {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->goods_return_note_void_valid($goods_return_note_id);

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'],'data' => $q['data']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'],'data' => $q['data']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue','data' => '');
		}

		echo json_encode($resp);
	}

	function submit_void_goods_return_note() {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->submit_void_goods_return_note();

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

	//CREDIT NOTES
	function credit_notes(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('credit_note_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {

				$data['credit_notes'] = $this->inventory_model->get_credit_notes();
				$data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();

				$data['page_title'] = 'Credit Notes | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Credit Notes';

				$data['sbr_credit_note_add'] = $this->auth_model->validate_user_access('credit_note_add', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/credit_notes';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function filter_js_credit_notes(){
		$data['credit_notes'] = $this->inventory_model->get_credit_notes();
		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$data['sbr_credit_note_view'] = $this->auth_model->validate_user_access('credit_note_view', $this->session->userdata('system_user_id'));
		$data['sbr_credit_note_edit'] = $this->auth_model->validate_user_access('credit_note_edit', $this->session->userdata('system_user_id'));
		$data['sbr_credit_note_delete'] = $this->auth_model->validate_user_access('credit_note_delete', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/credit_notes',$data);
	}

	function credit_note_add(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('credit_note_add', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['suppliers'] = $this->suppliers_model->get_suppliers_list();
				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$data['purchase_orders'] = $this->inventory_model->get_active_purchase_orders();
				$data['tax_rates'] = $this->tax_rates_model->get_tax_rates_list();
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['products'] = $this->products_model->get_products_list();

				$data['page_title'] = 'New Credit Note | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Credit Notes';

				$data['main_content'] = 'be/credit_note_add';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_credit_note(){
		$q = $this->inventory_model->save_credit_note();
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt'],'id' => $q['id']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt'],'id' => $q['id']);
		}			
		echo json_encode($resp);
	}
	function credit_note_edit($credit_note_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('credit_note_edit', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['suppliers'] = $this->suppliers_model->get_suppliers_list();
				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$data['purchase_orders'] = $this->inventory_model->get_active_purchase_orders();
				$data['tax_rates'] = $this->tax_rates_model->get_tax_rates_list();
				$data['credit_note'] = $this->inventory_model->get_credit_note($credit_note_id);
				$data['credit_note_details'] = $this->inventory_model->get_credit_note_details($credit_note_id);
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['products'] = $this->products_model->get_products_list();

				$data['page_title'] = 'Edit Credit Note | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Credit Notes';

				$data['main_content'] = 'be/return_stock';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function update_credit_note(){

		$credit_note_id = $this->input->post('credit_note_id');

		$q = $this->inventory_model->update_credit_note();

		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt'],'id' => $credit_note_id);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt'],'id' => $credit_note_id);
		}
		echo json_encode($resp);
	}
	function credit_note_detail($credit_note_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('credit_note_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['suppliers'] = $this->suppliers_model->get_suppliers_list();
				$data['tax_rates'] = $this->tax_rates_model->get_tax_rates_list();
				$data['credit_note'] = $this->inventory_model->get_credit_note($credit_note_id);
				$data['credit_note_details'] = $this->inventory_model->get_credit_note_details($credit_note_id);
				$data['store_information'] = $this->store_information_model->get_store_information();
				$data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['page_title'] = 'Credit Note Detail | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Credit Notes';

				$data['sbr_credit_note_print'] = $this->auth_model->validate_user_access('credit_note_print', $this->session->userdata('system_user_id'));
				$data['sbr_credit_note_edit'] = $this->auth_model->validate_user_access('credit_note_edit', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/credit_note_detail';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function get_credit_note($credit_note_id) {
		$credit_note = $this->inventory_model->get_credit_note($credit_note_id);

		echo json_encode($credit_note);
	}

	function credit_note_print($credit_note_id) {
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('credit_note_print', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['suppliers'] = $this->suppliers_model->get_suppliers_list();
				$data['tax_rates'] = $this->tax_rates_model->get_tax_rates_list();
				$data['credit_note'] = $this->inventory_model->get_credit_note($credit_note_id);
				$data['credit_note_details'] = $this->inventory_model->get_credit_note_details($credit_note_id);
				$data['store_information'] = $this->store_information_model->get_store_information();
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['page_title'] = 'Credit Note Print | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Credit Notes';

				$this->load->view('be/credit_note_print',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function credit_note_pdf($credit_note_id) {
		if($this->session->userdata('bgs_be_active')) {

			$credit_note = $this->inventory_model->get_credit_note($credit_note_id);
			$credit_note_details = $this->inventory_model->get_credit_note_details($credit_note_id);

			$default_currency = $this->currencies_model->get_default_currency();
			$store_information = $this->store_information_model->get_store_information();

			foreach ($credit_note as $row) {

				$filename='Bethany House Credit Note - '.$row->credit_note_number.'.pdf';

	            // create new PDF document
	            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


	            // set document information
	            $pdf->SetCreator(PDF_CREATOR);
	            $pdf->SetAuthor('Bethany House');
	            $pdf->SetTitle('Bethany House Credit Note - '.$row->credit_note_number);
	            $pdf->SetSubject('Bethany House Credit Note - '.$row->credit_note_number);
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
	            	<td rowspan="4" width="224"><img src="' . $store_logo . '"  width="200px"><br /><br />';

	            	// </td>
	            	// <td rowspan="2" width="200">';

	            	foreach ($store_information as $row2){
                        $txt = $txt . '<b>' . $row2->store_name . '</b><br />
                        <b>Phone:</b> ' . $row2->phone_number . '<br />
                        <b>Address:</b> ' . $row2->physical_address . '<br />
                        <b>Email:</b> ' . $row2->email_address;
                    }
	            	$txt = $txt . '</td>
	            	<td rowspan="3"></td>
	            	<td><b>Credit Note No:</b> ' . $row->credit_note_number . '</td>
	            	</tr>
	            	<tr>
	            		<td><b>Outlet:</b> ' . $row->outlet_name . '</td>
	            	</tr>
	            	<tr>
	            		<td><b>Return Date:</b> ' . date('d M, Y', strtotime($row->return_date)) . '</td>
	            	</tr>
	            	<tr>
	            		<td colspan="2"><b>Supplier:</b><br/>' . $row->supplier_name . '<br/>' . $row->email_address . '<br/>' . $row->phone_number . '</td>
	            	</tr></thead></table>';


                $pdf->writeHTML($txt, true, false, false, false, '');

                $txt = '<table border="1" cellpadding="5" cellspacing="0">
               		<thead>
               			<tr>
               				<td width="30"><b>#</b></td>
               				<td width="340"><b>Product Name</b></td>
               				<td width="90"><b>Returned</b></td>
               				<td width="100"><b>Unit Cost</b></td>
               				<td width="110"><b>Amount</b></td>
               			</tr>
               		</thead>
               		<tbody>';
               	$count = 1;
               	foreach ($credit_note_details as $row2){
               		$variation_description = '';
               		if(!empty($row2->attributes)){
            			foreach ($row2->attributes as $row3){
            				$variation_description = $variation_description . $row3->product_attribute_name . ' : <b>' . $row3->product_attribute_value . '</b>, ';
            			}
            			$variation_description =  '~ ' . substr($variation_description,0,-2) . '<br>';
            		}															
               		$txt = $txt . '<tr>
               			<td width="30">' . $count . '</td>
           				<td width="340">'. $row2->product_name . '<br>' . $variation_description . '<i>SKU: ' . $row2->product_sku_code . '</i></td>
           				<td width="90">' . number_format($row2->returned_quantity,2) . '</td>
           				<td width="100">' . $default_currency . ' ' . number_format($row2->unit_price,2) . '</td>
           				<td width="110">' . $default_currency . ' ' . number_format($row2->detail_total_amount,2) . '</td>
               		</tr>';
               		$count++;
               	}
               	$txt = $txt . '<tr>
               		<td colspan="4" align="right"><b>Subtotal</b></td>
               		<td><b>' . $default_currency . ' ' . number_format($row->sub_total,2) . '</b></td>
               	</tr>';
               	$txt = $txt . '<tr>
               		<td colspan="4" align="right"><b>Freight</b></td>
               		<td><b>' . $default_currency . ' ' . number_format($row->freight_cost,2) . '</b></td>
               	</tr>';
               	$txt = $txt . '<tr>
               		<td colspan="4" align="right"><b>Total</b></td>
               		<td><b>' . $default_currency . ' ' . number_format($row->total_amount,2) . '</b></td>
               	</tr>';
               	
               $txt = $txt . '</tbody></table>';

               	$pdf->writeHTML($txt, true, false, false, false, '');

               	$txt = '<table border="1" cellpadding="5" cellspacing="0">
               			<tr>
               				<td colspan="2" align="left"><b>Remark:</b><br/>' . $row->remark .  '</td>
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
		else {
            redirect('be/auth');
		}
	}

	function submit_send_credit_note_via_email() {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->submit_send_credit_note_via_email();

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

	function credit_note_void_valid($credit_note_id) {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->credit_note_void_valid($credit_note_id);

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'],'data' => $q['data']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'],'data' => $q['data']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue','data' => '');
		}

		echo json_encode($resp);
	}

	function submit_void_credit_note() {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->submit_void_credit_note();

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


	//TRANSFERS
	function stock_transfers(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('stock_transfers_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['stock_transfers'] = $this->inventory_model->get_stock_transfers();
				$data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();

				$data['system_users'] = $this->system_users_model->get_system_users_list();
				$data['outlets'] = $this->outlets_model->get_outlets_list();

				$data['page_title'] = 'Stock Transfers | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Stock Transfers';

				$data['sbr_stock_transfers_add'] = $this->auth_model->validate_user_access('stock_transfers_add', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/stock_transfers';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function filter_js_stock_transfers(){
		$data['stock_transfers'] = $this->inventory_model->get_stock_transfers();
		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$data['sbr_stock_transfers_view'] = $this->auth_model->validate_user_access('stock_transfers_view', $this->session->userdata('system_user_id'));
		$data['sbr_stock_transfers_edit'] = $this->auth_model->validate_user_access('stock_transfers_edit', $this->session->userdata('system_user_id'));
		$data['sbr_stock_transfers_delete'] = $this->auth_model->validate_user_access('stock_transfers_delete', $this->session->userdata('system_user_id'));
		$data['sbr_stock_transfers_print'] = $this->auth_model->validate_user_access('stock_transfers_print', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/stock_transfers',$data);
	}
	function export_stock_transfers() {

		$data['date_from'] = $this->input->post('date_from');
        $data['date_to'] = $this->input->post('date_to');
        $data['status'] = $this->input->post('status');
        $data['status_text'] = $this->input->post('status_text');
        $data['source_outlet_id'] = $this->input->post('source_outlet_id');
        $data['source_outlet_name'] = $this->input->post('source_outlet_name');
        $data['destination_outlet_id'] = $this->input->post('destination_outlet_id');
        $data['destination_outlet_name'] = $this->input->post('destination_outlet_name');
        $data['system_user_id'] = $this->input->post('system_user_id');
        $data['system_user_name'] = $this->input->post('system_user_name');

		$data['stock_transfers'] = $this->inventory_model->get_stock_transfers();
		$data['store_information'] = $this->store_information_model->get_store_information();		
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$data['page_title'] = 'Stock Transfers | ';
		
		// $this->load->view('be/goods_return_notes_print',$data);
		$dompdf = new Dpdf();
		$dompdf->set_paper('letter', 'landscape');
		$html_content = $this->load->view('be/stock_transfers_print',$data,true);
		$dompdf->loadHtml($html_content);
		$dompdf->render();
		$dompdf->stream("Stock transfers.pdf", array("Attachment" => false));
	}
	function transfer(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('stock_transfers_add', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$data['products'] = $this->products_model->get_products_list();
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['page_title'] = 'Transfer Stock | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Stock Transfers';

				$data['main_content'] = 'be/transfer_stock';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_stock_transfer(){
		$q = $this->inventory_model->save_stock_transfer();
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt'],'id' => $q['id']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt'],'id' => $q['id']);
		}			
		echo json_encode($resp);
	}
	function stock_transfer_edit($transfer_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('stock_transfers_edit', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$data['stock_transfer'] = $this->inventory_model->get_stock_transfer($transfer_id);
				$data['stock_transfer_details'] = $this->inventory_model->get_stock_transfer_details($transfer_id);
				$data['products'] = $this->products_model->get_products_list();
				$data['default_currency'] = $this->currencies_model->get_default_currency();                                                   

				$data['page_title'] = 'Edit Stock Transfer | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Stock Transfers';

				$data['main_content'] = 'be/transfer_stock';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function update_stock_transfer(){

		$stock_transfer_id = $this->input->post('stock_transfer_id');

		$q = $this->inventory_model->update_stock_transfer();

		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt'],'id' => $stock_transfer_id);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt'],'id' => $stock_transfer_id);
		}
		echo json_encode($resp);
	}
	function stock_transfer_detail($stock_transfer_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('stock_transfers_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['tax_rates'] = $this->tax_rates_model->get_tax_rates_list();
				$data['stock_transfer'] = $this->inventory_model->get_stock_transfer($stock_transfer_id);
				$data['stock_transfer_details'] = $this->inventory_model->get_stock_transfer_details($stock_transfer_id);
				$data['store_information'] = $this->store_information_model->get_store_information();
				$data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['page_title'] = 'Stock Transfer Detail | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Stock Transfers';

				$data['sbr_stock_transfers_print'] = $this->auth_model->validate_user_access('stock_transfers_print', $this->session->userdata('system_user_id'));
				$data['sbr_stock_transfers_edit'] = $this->auth_model->validate_user_access('stock_transfers_edit', $this->session->userdata('system_user_id'));
				$data['sbr_stock_transfers_delete'] = $this->auth_model->validate_user_access('stock_transfers_delete', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/stock_transfer_detail';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function get_stock_transfer($stock_transfer_id) {
		$stock_transfer = $this->inventory_model->get_stock_transfer($stock_transfer_id);
		echo json_encode($stock_transfer);
	}
	function stock_transfer_print($stock_transfer_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('stock_transfers_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$stock_transfer = $this->inventory_model->get_stock_transfer($stock_transfer_id);
				$data['stock_transfer'] = $stock_transfer;
				$data['stock_transfer_details'] = $this->inventory_model->get_stock_transfer_details($stock_transfer_id);
				$data['store_information'] = $this->store_information_model->get_store_information();
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['page_title'] = 'Stock Transfer Print | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Stock Transfers';

				// $this->load->view('be/stock_transfer_print',$data);
				$html_content = $this->load->view('be/stock_transfer_print',$data,true);
				foreach ($stock_transfer as $row) {
					$stock_transfer_number = $row->stock_transfer_number;
				}
				$dompdf = new Dpdf();
				$dompdf->loadHtml($html_content);
				$dompdf->render();
				$dompdf->stream($stock_transfer_number . ".pdf", array("Attachment" => false));
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function stock_transfer_pdf($stock_transfer_id) {
		if($this->session->userdata('bgs_be_active')) {

			$stock_transfer = $this->inventory_model->get_stock_transfer($stock_transfer_id);
			$stock_transfer_details = $this->inventory_model->get_stock_transfer_details($stock_transfer_id);

			$default_currency = $this->currencies_model->get_default_currency();
			$store_information = $this->store_information_model->get_store_information();

			foreach ($stock_transfer as $row) {

				$filename='Bethany House Stock Transfer - '.$row->stock_transfer_number.'.pdf';

	            // create new PDF document
	            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


	            // set document information
	            $pdf->SetCreator(PDF_CREATOR);
	            $pdf->SetAuthor('Bethany House');
	            $pdf->SetTitle('Bethany House Stock Transfer - '.$row->stock_transfer_number);
	            $pdf->SetSubject('Bethany House Stock Transfer - '.$row->stock_transfer_number);
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
	            	<td rowspan="4" width="224"><img src="' . $store_logo . '"  width="200px"><br /><br />';

	            	// </td>
	            	// <td rowspan="2" width="200">';

	            	foreach ($store_information as $row2){
                        $txt = $txt . '<b>' . $row2->store_name . '</b><br />
                        <b>Phone:</b> ' . $row2->phone_number . '<br />
                        <b>Address:</b> ' . $row2->physical_address . '<br />
                        <b>Email:</b> ' . $row2->email_address;
                    }
	            	$txt = $txt . '</td>
	            	<td rowspan="4"></td>
	            	<td><b>Stock Transfer No:</b> ' . $row->stock_transfer_number . '</td>
	            	</tr>
	            	<tr>
	            		<td><b>Source Outlet:</b> ' . $row->source_outlet_name . '</td>
	            	</tr>
	            	<tr>
	            		<td><b>Destination Outlet:</b> ' . $row->destination_outlet_name . '</td>
	            	</tr>
	            	<tr>
	            		<td><b>Transfer Date:</b> ' . date('d M, Y', strtotime($row->transfer_date)) . '</td>
	            	</tr>
	            	</thead></table>';


                $pdf->writeHTML($txt, true, false, false, false, '');

                $txt = '<table border="1" cellpadding="5" cellspacing="0">
               		<thead>
               			<tr>
               				<td width="30"><b>#</b></td>
               				<td width="440"><b>Product Name</b></td>
               				<td width="200"><b>Transferred Qty</b></td>
               			</tr>
               		</thead>
               		<tbody>';
               	$count = 1;
               	foreach ($stock_transfer_details as $row2){
               		$variation_description = '';
               		if(!empty($row2->attributes)){
            			foreach ($row2->attributes as $row3){
            				$variation_description = $variation_description . $row3->product_attribute_name . ' : <b>' . $row3->product_attribute_value . '</b>, ';
            			}
            			$variation_description =  '~ ' . substr($variation_description,0,-2) . '<br>';
            		}															
               		$txt = $txt . '<tr>
               			<td width="30">' . $count . '</td>
           				<td width="440">'. $row2->product_name . '<br>' . $variation_description . '<i>SKU: ' . $row2->product_sku_code . '</i></td>
           				<td width="200">' . number_format($row2->transferred_quantity,2) . '</td>
               		</tr>';
               		$count++;
               	}
               	
               $txt = $txt . '</tbody></table>';

               	$pdf->writeHTML($txt, true, false, false, false, '');

               	$txt = '<table border="1" cellpadding="5" cellspacing="0">
               			<tr>
               				<td colspan="2" align="left"><b>Remark:</b><br/>' . $row->remark .  '</td>
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
		else {
            redirect('be/auth');
		}
	}
	function submit_send_stock_transfer_via_email() {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->submit_send_stock_transfer_via_email();

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

	function stock_transfer_void_valid($stock_transfer_id) {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->stock_transfer_void_valid($stock_transfer_id);

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'],'data' => $q['data']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'],'data' => $q['data']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue','data' => '');
		}

		echo json_encode($resp);
	}

	function submit_void_stock_transfer() {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->submit_void_stock_transfer();

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

	//ADJUSTMENTS
	function stock_adjustments(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('stock_adjustments_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['stock_adjustments'] = $this->inventory_model->get_stock_adjustments();
				$data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();

				$data['system_users'] = $this->system_users_model->get_system_users_list();
				$data['outlets'] = $this->outlets_model->get_outlets_list();

				$data['page_title'] = 'Stock Adjustments | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Stock Adjustments';

				$data['sbr_stock_adjustments_add'] = $this->auth_model->validate_user_access('stock_adjustments_add', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/stock_adjustments';
				$this->load->view('be/includes/template',$data);
			}
        } else {
            redirect('be/auth');
		}
	}
	function filter_js_stock_adjustments(){
		$data['stock_adjustments'] = $this->inventory_model->get_stock_adjustments();
		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$data['sbr_stock_adjustments_view'] = $this->auth_model->validate_user_access('stock_adjustments_view', $this->session->userdata('system_user_id'));
		$data['sbr_stock_adjustments_edit'] = $this->auth_model->validate_user_access('stock_adjustments_edit', $this->session->userdata('system_user_id'));
		$data['sbr_stock_adjustments_delete'] = $this->auth_model->validate_user_access('stock_adjustments_delete', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/stock_adjustments',$data);
	}
	function export_stock_adjustments() {

		$data['date_from'] = $this->input->post('date_from');
        $data['date_to'] = $this->input->post('date_to');
        $data['status'] = $this->input->post('status');
        $data['status_text'] = $this->input->post('status_text');
        $data['outlet_id'] = $this->input->post('outlet_id');
        $data['outlet_name'] = $this->input->post('outlet_name');
        $data['system_user_id'] = $this->input->post('system_user_id');
        $data['system_user_name'] = $this->input->post('system_user_name');

		$data['stock_adjustments'] = $this->inventory_model->get_stock_adjustments();
		$data['store_information'] = $this->store_information_model->get_store_information();		
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$data['page_title'] = 'Stock Adjustments | ';
		
		// $this->load->view('be/goods_return_notes_print',$data);
		$dompdf = new Dpdf();
		$dompdf->set_paper('letter', 'landscape');
		$html_content = $this->load->view('be/stock_adjustments_print',$data,true);
		$dompdf->loadHtml($html_content);
		$dompdf->render();
		$dompdf->stream("Stock Adjustments.pdf", array("Attachment" => false));
	}
	function adjust(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('stock_adjustments_add', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$data['page_title'] = 'Adjust Stock | ';
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Stock Adjustments';

				$data['main_content'] = 'be/adjust_stock';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function get_auto_adjust_stock_products(){
		$a_json = array();
		$a_json_row = array();
		
		$term = trim(strip_tags($_GET['term'])); 
		$outlet_id = trim(strip_tags($_GET['outlet_id']));
		
		$products = $this->inventory_model->get_auto_adjust_stock_products($term, $outlet_id);
		foreach($products as $prod){
			$prod_price = 0;
			if ($prod->sale_price > 0){ $prod_price = $prod->sale_price; } else { $prod_price = $prod>regular_price; }
			$a_json_row["id"] = $prod->product_id;
			$a_json_row["id"] = $prod->product_id;
			$a_json_row["value"] = htmlentities(stripslashes($prod->product_name));
			$a_json_row["label"] = htmlentities(stripslashes($prod->product_name));
			$a_json_row["desc"] = htmlentities(stripslashes($prod->product_sku_code));
			$a_json_row["type"] = htmlentities(stripslashes($prod->product_type));
			$a_json_row["stock"] = htmlentities(stripslashes($prod->available_stock));
			$a_json_row["price"] = $prod_price;
			array_push($a_json, $a_json_row);
		}
		echo json_encode($a_json);
		flush();
	}
	function save_stock_adjustment(){
		$q = $this->inventory_model->save_stock_adjustment();
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt'],'id' => $q['id']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt'],'id' => $q['id']);
		}			
		echo json_encode($resp);
	}
	function stock_adjustment_edit($adjust_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('stock_adjustments_edit', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$data['stock_adjustment'] = $this->inventory_model->get_stock_adjustment($adjust_id);
				$data['stock_adjustment_details'] = $this->inventory_model->get_stock_adjustment_details($adjust_id);
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['page_title'] = 'Edit Stock Adjustment | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Stock Adjustments';

				$data['main_content'] = 'be/adjust_stock';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function update_stock_adjustment(){

		$stock_adjustment_id = $this->input->post('stock_adjustment_id');

		$q = $this->inventory_model->update_stock_adjustment();

		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt'],'id' => $stock_adjustment_id);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt'],'id' => $stock_adjustment_id);
		}
		echo json_encode($resp);
	}
	function stock_adjustment_detail($stock_adjustment_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('stock_adjustments_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['tax_rates'] = $this->tax_rates_model->get_tax_rates_list();
				$data['stock_adjustment'] = $this->inventory_model->get_stock_adjustment($stock_adjustment_id);
				$data['stock_adjustment_details'] = $this->inventory_model->get_stock_adjustment_details($stock_adjustment_id);
				$data['store_information'] = $this->store_information_model->get_store_information();
				$data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['page_title'] = 'Stock adjust Detail | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Stock Adjustments';

				$data['sbr_stock_adjustments_print'] = $this->auth_model->validate_user_access('stock_adjustments_print', $this->session->userdata('system_user_id'));
				$data['sbr_stock_adjustments_edit'] = $this->auth_model->validate_user_access('stock_adjustments_edit', $this->session->userdata('system_user_id'));
				$data['sbr_stock_adjustments_delete'] = $this->auth_model->validate_user_access('stock_adjustments_delete', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/stock_adjustment_detail';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function get_stock_adjustment($stock_adjustment_id) {
		$stock_adjustment = $this->inventory_model->get_stock_adjustment($stock_adjustment_id);
		echo json_encode($stock_adjustment);
	}
	function stock_adjustment_print($stock_adjustment_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('stock_adjustments_print', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$stock_adjustment = $this->inventory_model->get_stock_adjustment($stock_adjustment_id);
				$data['stock_adjustment'] = $stock_adjustment;
				$data['stock_adjustment_details'] = $this->inventory_model->get_stock_adjustment_details($stock_adjustment_id);
				$data['store_information'] = $this->store_information_model->get_store_information();
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['page_title'] = 'Stock adjust Print | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Stock Adjustments';

				// $this->load->view('be/stock_adjustment_print',$data);
				$html_content = $this->load->view('be/stock_adjustment_print',$data,true);
				foreach ($stock_adjustment as $row) {
					$stock_adjustment_number = $row->stock_adjustment_number;
				}
				$dompdf = new Dpdf();
				$dompdf->loadHtml($html_content);
				$dompdf->render();
				$dompdf->stream($stock_adjustment_number . ".pdf", array("Attachment" => false));
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function stock_adjustment_pdf($stock_adjustment_id) {
		if($this->session->userdata('bgs_be_active')) {

			$stock_adjustment = $this->inventory_model->get_stock_adjustment($stock_adjustment_id);
			$stock_adjustment_details = $this->inventory_model->get_stock_adjustment_details($stock_adjustment_id);

			$default_currency = $this->currencies_model->get_default_currency();
			$store_information = $this->store_information_model->get_store_information();

			foreach ($stock_adjustment as $row) {

				$filename='Bethany House Stock Adjustment - '.$row->stock_adjustment_number.'.pdf';

	            // create new PDF document
	            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


	            // set document information
	            $pdf->SetCreator(PDF_CREATOR);
	            $pdf->SetAuthor('Bethany House');
	            $pdf->SetTitle('Bethany House Stock Adjustment - '.$row->stock_adjustment_number);
	            $pdf->SetSubject('Bethany House Stock Adjustment - '.$row->stock_adjustment_number);
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
	            	<td rowspan="3" width="224"><img src="' . $store_logo . '"  width="200px"><br /><br />';

	            	// </td>
	            	// <td rowspan="2" width="200">';

	            	foreach ($store_information as $row2){
                        $txt = $txt . '<b>' . $row2->store_name . '</b><br />
                        <b>Phone:</b> ' . $row2->phone_number . '<br />
                        <b>Address:</b> ' . $row2->physical_address . '<br />
                        <b>Email:</b> ' . $row2->email_address;
                    }
	            	$txt = $txt . '</td>
	            	<td rowspan="3"></td>
	            	<td><b>Stock Adjustment No:</b> ' . $row->stock_adjustment_number . '</td>
	            	</tr>
	            	<tr>
	            		<td><b>Outlet:</b> ' . $row->outlet_name . '</td>
	            	</tr>
	            	<tr>
	            		<td><b>Adjustment Date:</b> ' . date('d M, Y', strtotime($row->adjustment_date)) . '</td>
	            	</tr>
	            	</thead></table>';


                $pdf->writeHTML($txt, true, false, false, false, '');

                $txt = '<table border="1" cellpadding="5" cellspacing="0">
               		<thead>
               			<tr>
               				<td width="30"><b>#</b></td>
               				<td width="340"><b>Product Name</b></td>
               				<td width="150"><b>Current Qty</b></td>
               				<td width="150"><b>Adjusted Qty</b></td>
               			</tr>
               		</thead>
               		<tbody>';
               	$count = 1;
               	foreach ($stock_adjustment_details as $row2){
               		$variation_description = '';
               		if(!empty($row2->attributes)){
            			foreach ($row2->attributes as $row3){
            				$variation_description = $variation_description . $row3->product_attribute_name . ' : <b>' . $row3->product_attribute_value . '</b>, ';
            			}
            			$variation_description =  '~ ' . substr($variation_description,0,-2) . '<br>';
            		}															
               		$txt = $txt . '<tr>
               			<td width="30">' . $count . '</td>
           				<td width="340">'. $row2->product_name . '<br>' . $variation_description . '<i>SKU: ' . $row2->product_sku_code . '</i></td>
           				<td width="150">' . number_format($row2->current_quantity,2) . '</td>
           				<td width="150">' . number_format($row2->adjusted_quantity,2) . '</td>
               		</tr>';
               		$count++;
               	}
               	
               $txt = $txt . '</tbody></table>';

               	$pdf->writeHTML($txt, true, false, false, false, '');

               	$txt = '<table border="1" cellpadding="5" cellspacing="0">
               			<tr>
               				<td colspan="2" align="left"><b>Remark:</b><br/>' . $row->remark .  '</td>
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
		else {
            redirect('be/auth');
		}
	}

	function submit_send_stock_adjustment_via_email() {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->submit_send_stock_adjustment_via_email();

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

	function stock_adjustment_void_valid($stock_adjustment_id) {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->stock_adjustment_void_valid($stock_adjustment_id);

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'],'data' => $q['data']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'],'data' => $q['data']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue','data' => '');
		}

		echo json_encode($resp);
	}

	function submit_void_stock_adjustment() {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->submit_void_stock_adjustment();

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


	//WRITE-OFFS
	function stock_writeoffs(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('stock_writeoffs_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['stock_writeoffs'] = $this->inventory_model->get_stock_writeoffs();
				$data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();

				$data['system_users'] = $this->system_users_model->get_system_users_list();
				$data['outlets'] = $this->outlets_model->get_outlets_list();

				$data['page_title'] = 'Stock Write-offs | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Stock Write-offs';

				$data['sbr_stock_writeoffs_add'] = $this->auth_model->validate_user_access('stock_writeoffs_add', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/stock_writeoffs';
				$this->load->view('be/includes/template',$data);
			}
        } else {
            redirect('be/auth');
		}
	}
	function export_stock_writeoffs() {

		$data['date_from'] = $this->input->post('date_from');
        $data['date_to'] = $this->input->post('date_to');
        $data['status'] = $this->input->post('status');
        $data['status_text'] = $this->input->post('status_text');
        $data['outlet_id'] = $this->input->post('outlet_id');
        $data['outlet_name'] = $this->input->post('outlet_name');
        $data['system_user_id'] = $this->input->post('system_user_id');
        $data['system_user_name'] = $this->input->post('system_user_name');

		$data['stock_writeoffs'] = $this->inventory_model->get_stock_writeoffs();
		$data['store_information'] = $this->store_information_model->get_store_information();		
		$data['default_currency'] = $this->currencies_model->get_default_currency();

		$data['page_title'] = 'Stock Write-offs | ';
		
		// $this->load->view('be/goods_return_notes_print',$data);
		$dompdf = new Dpdf();
		$dompdf->set_paper('letter', 'landscape');
		$html_content = $this->load->view('be/stock_writeoffs_print',$data,true);
		$dompdf->loadHtml($html_content);
		$dompdf->render();
		$dompdf->stream("Stock Write-offs.pdf", array("Attachment" => false));
	}
	function filter_js_stock_writeoffs(){
		$data['stock_writeoffs'] = $this->inventory_model->get_stock_writeoffs();
		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$data['sbr_stock_writeoffs_view'] = $this->auth_model->validate_user_access('stock_writeoffs_view', $this->session->userdata('system_user_id'));
		$data['sbr_stock_writeoffs_edit'] = $this->auth_model->validate_user_access('stock_writeoffs_edit', $this->session->userdata('system_user_id'));
		$data['sbr_stock_writeoffs_delete'] = $this->auth_model->validate_user_access('stock_writeoffs_delete', $this->session->userdata('system_user_id'));
		$data['sbr_stock_writeoffs_print'] = $this->auth_model->validate_user_access('stock_writeoffs_print', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/stock_writeoffs',$data);
	}
	function writeoff(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('stock_writeoffs_add', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$data['page_title'] = 'Write-off Stock | ';
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Stock Write-offs';

				$data['main_content'] = 'be/writeoff_stock';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function get_auto_writeoff_stock_products(){
		$a_json = array();
		$a_json_row = array();
		
		$term = trim(strip_tags($_GET['term'])); 
		$outlet_id = trim(strip_tags($_GET['outlet_id']));
		
		$products = $this->inventory_model->get_auto_writeoff_stock_products($term, $outlet_id);
		foreach($products as $prod){
			$prod_price = 0;
			if ($prod->sale_price > 0){ $prod_price = $prod->sale_price; } else { $prod_price = $prod>regular_price; }
			$a_json_row["id"] = $prod->product_id;
			$a_json_row["value"] = htmlentities(stripslashes($prod->product_name));
			$a_json_row["label"] = htmlentities(stripslashes($prod->product_name));
			$a_json_row["desc"] = htmlentities(stripslashes($prod->product_sku_code));
			$a_json_row["stock"] = htmlentities(stripslashes($prod->available_stock));
			$a_json_row["type"] = htmlentities(stripslashes($prod->product_type));
			$a_json_row["price"] = $prod_price;
			array_push($a_json, $a_json_row);
		}
		echo json_encode($a_json);
		flush();
	}
	function save_stock_writeoff(){
		$q = $this->inventory_model->save_stock_writeoff();
		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt'],'id' => $q['id']);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt'],'id' => $q['id']);
		}			
		echo json_encode($resp);
	}
	function stock_writeoff_edit($writeoff_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('stock_writeoffs_edit', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$data['stock_writeoff'] = $this->inventory_model->get_stock_writeoff($writeoff_id);
				$data['stock_writeoff_details'] = $this->inventory_model->get_stock_writeoff_details($writeoff_id);
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['page_title'] = 'Edit Stock Write-off | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Stock Write-offs';

				$data['main_content'] = 'be/writeoff_stock';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function update_stock_writeoff(){

		$stock_writeoff_id = $this->input->post('stock_writeoff_id');

		$q = $this->inventory_model->update_stock_writeoff();

		if ($q['res'] == true){
			$resp = array('status' => 'SUCCESS','message' => $q['dt'],'id' => $stock_writeoff_id);
		}else{
			$resp = array('status' => 'ERR','message' => $q['dt'],'id' => $stock_writeoff_id);
		}
		echo json_encode($resp);
	}
	function stock_writeoff_detail($stock_writeoff_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('stock_writeoffs_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['tax_rates'] = $this->tax_rates_model->get_tax_rates_list();
				$data['stock_writeoff'] = $this->inventory_model->get_stock_writeoff($stock_writeoff_id);
				$data['stock_writeoff_details'] = $this->inventory_model->get_stock_writeoff_details($stock_writeoff_id);
				$data['store_information'] = $this->store_information_model->get_store_information();
				$data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['page_title'] = 'Stock Write-off Detail | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Stock Write-offs';

				$data['sbr_stock_writeoffs_print'] = $this->auth_model->validate_user_access('stock_writeoffs_print', $this->session->userdata('system_user_id'));
				$data['sbr_stock_writeoffs_edit'] = $this->auth_model->validate_user_access('stock_writeoffs_edit', $this->session->userdata('system_user_id'));
				$data['sbr_stock_writeoffs_delete'] = $this->auth_model->validate_user_access('stock_writeoffs_delete', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/stock_writeoff_detail';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function get_stock_writeoff($stock_writeoff_id) {
		$stock_writeoff = $this->inventory_model->get_stock_writeoff($stock_writeoff_id);
		echo json_encode($stock_writeoff);
	}
	function stock_writeoff_print($stock_writeoff_id){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('stock_writeoffs_print', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$stock_writeoff = $this->inventory_model->get_stock_writeoff($stock_writeoff_id);
				$data['stock_writeoff'] = $stock_writeoff;
				$data['stock_writeoff_details'] = $this->inventory_model->get_stock_writeoff_details($stock_writeoff_id);
				$data['store_information'] = $this->store_information_model->get_store_information();
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['page_title'] = 'Stock Write-off Print | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Transactions';
				$data['cur_cur_sub'] = 'Stock Write-offs';

				// $this->load->view('be/stock_writeoff_print',$data);
				$html_content = $this->load->view('be/stock_writeoff_print',$data,true);
				foreach ($stock_writeoff as $row) {
					$stock_writeoff_number = $row->stock_writeoff_number;
				}
				$dompdf = new Dpdf();
				$dompdf->loadHtml($html_content);
				$dompdf->render();
				$dompdf->stream($stock_writeoff_number . ".pdf", array("Attachment" => false));
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function stock_writeoff_pdf($stock_writeoff_id) {
		if($this->session->userdata('bgs_be_active')) {

			$stock_writeoff = $this->inventory_model->get_stock_writeoff($stock_writeoff_id);
			$stock_writeoff_details = $this->inventory_model->get_stock_writeoff_details($stock_writeoff_id);

			$default_currency = $this->currencies_model->get_default_currency();
			$store_information = $this->store_information_model->get_store_information();

			foreach ($stock_writeoff as $row) {

				$filename='Bethany House Stock Write-off - '.$row->stock_writeoff_number.'.pdf';

	            // create new PDF document
	            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


	            // set document information
	            $pdf->SetCreator(PDF_CREATOR);
	            $pdf->SetAuthor('Bethany House');
	            $pdf->SetTitle('Bethany House Stock Write-off - '.$row->stock_writeoff_number);
	            $pdf->SetSubject('Bethany House Stock Write-off - '.$row->stock_writeoff_number);
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
	            	<td rowspan="3" width="224"><img src="' . $store_logo . '"  width="200px"><br /><br />';

	            	// </td>
	            	// <td rowspan="2" width="200">';

	            	foreach ($store_information as $row2){
                        $txt = $txt . '<b>' . $row2->store_name . '</b><br />
                        <b>Phone:</b> ' . $row2->phone_number . '<br />
                        <b>Address:</b> ' . $row2->physical_address . '<br />
                        <b>Email:</b> ' . $row2->email_address;
                    }
	            	$txt = $txt . '</td>
	            	<td rowspan="3"></td>
	            	<td><b>Stock Write-off No:</b> ' . $row->stock_writeoff_number . '</td>
	            	</tr>
	            	<tr>
	            		<td><b>Outlet:</b> ' . $row->outlet_name . '</td>
	            	</tr>
	            	<tr>
	            		<td><b>Write-off Date:</b> ' . date('d M, Y', strtotime($row->writeoff_date)) . '</td>
	            	</tr>
	            	</thead></table>';


                $pdf->writeHTML($txt, true, false, false, false, '');

                $txt = '<table border="1" cellpadding="5" cellspacing="0">
               		<thead>
               			<tr>
               				<td width="30"><b>#</b></td>
               				<td width="390"><b>Product Name</b></td>
               				<td width="250"><b>Qty</b></td>
               			</tr>
               		</thead>
               		<tbody>';
               	$count = 1;
               	foreach ($stock_writeoff_details as $row2){
               		$variation_description = '';
               		if(!empty($row2->attributes)){
            			foreach ($row2->attributes as $row3){
            				$variation_description = $variation_description . $row3->product_attribute_name . ' : <b>' . $row3->product_attribute_value . '</b>, ';
            			}
            			$variation_description =  '~ ' . substr($variation_description,0,-2) . '<br>';
            		}															
               		$txt = $txt . '<tr>
               			<td width="30">' . $count . '</td>
           				<td width="390">'. $row2->product_name . '<br>' . $variation_description . '<i>SKU: ' . $row2->product_sku_code . '</i></td>
           				<td width="250">' . number_format($row2->writeoff_quantity,2) . '</td>
               		</tr>';
               		$count++;
               	}
               	
               $txt = $txt . '</tbody></table>';

               	$pdf->writeHTML($txt, true, false, false, false, '');

               	$txt = '<table border="1" cellpadding="5" cellspacing="0">
               			<tr>
               				<td colspan="2" align="left"><b>Remark:</b><br/>' . $row->remark .  '</td>
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
		else {
            redirect('be/auth');
		}
	}

	function submit_send_stock_writeoff_via_email() {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->submit_send_stock_writeoff_via_email();

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

	function stock_writeoff_void_valid($stock_writeoff_id) {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->stock_writeoff_void_valid($stock_writeoff_id);

			if($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt'],'data' => $q['data']);
			}
			else{
				$resp = array('status' => 'ERR','message' => $q['dt'],'data' => $q['data']);
			}
		} else {
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue','data' => '');
		}

		echo json_encode($resp);
	}

	function submit_void_stock_writeoff() {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->inventory_model->submit_void_stock_writeoff();

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

	//OUTLET PRODUCTS
	function get_products_by_outlet($outlet_id) {
		$outlet_products = $this->products_model->get_products_by_outlet($outlet_id);
		echo json_encode($outlet_products);
	}

	//PURCHASE ORDER PRODUCTS
	function get_products_by_purchase_order($purchase_order_id) {
		$purchase_order_products = $this->products_model->get_products_by_purchase_order($purchase_order_id);
		echo json_encode($purchase_order_products);
	}

	function update_stock_tracker() {		
		$this->inventory_model->update_stock_tracker();
	}

	//CURRENT STOCKS
	function current_stocks(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('current_stocks_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {

				$data['page_title'] = 'Current Stocks | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Stocks';
				$data['cur_cur_sub'] = 'Current Stocks';

				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();

				$data['main_content'] = 'be/current_stocks';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function get_current_stocks() {
		$data['current_stocks'] = $this->inventory_model->get_current_stocks();
		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$data['outlet_id'] = $this->input->post('outlet_id');

		$this->load->view('be/jsloads/current_stocks',$data);
	}

	//LOW STOCKS
	function low_stocks(){
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('low_stocks_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {

				$data['page_title'] = 'Items on Reorder Level | ';

				$data['cur'] = 'Inventory';
				$data['cur_sub'] = 'Stocks';
				$data['cur_cur_sub'] = 'Low Stocks';

				$data['outlets'] = $this->outlets_model->get_outlets_list();
				$data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();

				$data['main_content'] = 'be/low_stocks';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function get_low_stocks() {
		$data['low_stocks'] = $this->inventory_model->get_low_stocks();
		$data['default_currency'] = $this->currencies_model->get_default_currency();
		$data['outlet_id'] = $this->input->post('outlet_id');

		$this->load->view('be/jsloads/low_stocks',$data);
	}


}