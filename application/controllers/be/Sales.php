<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sales extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/inventory_model');
		$this->load->model('be/suppliers_model');
		$this->load->model('be/outlets_model');
		$this->load->model('be/tax_rates_model');
		$this->load->model('be/products_model');
		$this->load->model('be/store_information_model');
		$this->load->model('be/sales_model');
		$this->load->model('api_model');
		$this->load->model('checkout_model');
		$this->load->model('be/currencies_model');
		$this->load->model('be/auth_model');
		$this->load->model('be/email_accounts_model');
		$this->load->model('be/email_templates_model');
		$this->load->model('be/support_model');
		$this->load->library("Pdf");
	}

	function online() {
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('online_sales_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['page_title'] = 'Online Orders | ';

				$data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();
				$data['email_templates'] = $this->email_templates_model->get_email_templates_list();
				$data['outlets'] = $this->outlets_model->get_outlets_list();

				$data['cur'] = 'Sales';
				$data['cur_sub'] = 'Online Sales';
				$data['cur_cur_sub'] = '';

				$data['main_content'] = 'be/online_sales';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}

	function filter_js_online_sales() {
		$data['online_sales'] = $this->sales_model->get_online_sales();
		$data['sbr_online_sales_view'] = $this->auth_model->validate_user_access('online_sales_view', $this->session->userdata('system_user_id'));
		$data['sbr_online_sales_manage'] = $this->auth_model->validate_user_access('online_sales_manage', $this->session->userdata('system_user_id'));

		$this->load->view('be/jsloads/online_sales',$data);
	}

	function online_order($ord_order_number) {
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('online_sales_view', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['online_order'] = $this->sales_model->get_online_order($ord_order_number);
				$data['online_order_details'] = $this->sales_model->get_online_order_details($ord_order_number);
				$data['store_information'] = $this->store_information_model->get_store_information();
				$data['email_accounts'] = $this->email_accounts_model->get_email_accounts_list();
				$data['email_templates'] = $this->email_templates_model->get_email_templates_list();
				$data['outlets'] = $this->outlets_model->get_outlets_list();

				$data['page_title'] = 'Online Order | ';

				$data['cur'] = 'Sales';
				$data['cur_sub'] = 'Online Sales';
				$data['cur_cur_sub'] = '';

				$this->support_model->read_notification('Online Order Creation', $ord_order_number);
				$this->support_model->read_notification('Online Order Cancellation', $ord_order_number);

				$data['sbr_online_sales_print'] = $this->auth_model->validate_user_access('online_sales_print', $this->session->userdata('system_user_id'));
				$data['sbr_online_sales_manage'] = $this->auth_model->validate_user_access('online_sales_manage', $this->session->userdata('system_user_id'));

				$data['main_content'] = 'be/online_order';
				$this->load->view('be/includes/template',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function get_online_order($ord_order_number) {
		$online_order = $this->sales_model->get_online_order($ord_order_number);
		echo json_encode($online_order);
	}
	function submit_send_online_order_via_email() {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->sales_model->submit_send_online_order_via_email();

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
	function submit_send_online_order_customer_email() {
		if($this->session->userdata('bgs_be_active')){

			$q = $this->sales_model->submit_send_online_order_customer_email();

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
	function online_order_print($ord_order_number) {
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('online_sales_print', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {
				$data['online_order'] = $this->sales_model->get_online_order($ord_order_number);
				$data['online_order_details'] = $this->sales_model->get_online_order_details($ord_order_number);
				$data['store_information'] = $this->store_information_model->get_store_information();
				$data['default_currency'] = $this->currencies_model->get_default_currency();

				$data['page_title'] = 'Online Order Print | ';

				$data['cur'] = 'Sales';
				$data['cur_sub'] = 'Online Sales';
				$data['cur_cur_sub'] = '';

				$this->load->view('be/online_order_print',$data);
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function online_order_pdf($ord_order_number) {
		if($this->session->userdata('bgs_be_active')) {
			if ($this->auth_model->validate_user_access('online_sales_print', $this->session->userdata('system_user_id')) == false){
				redirect('be/auth/access_denied');
			} else {

				$order = $this->sales_model->get_online_order($ord_order_number);
		        $order_details = $this->sales_model->get_online_order_details($ord_order_number);
		        $store_information = $this->store_information_model->get_store_information();
		        $default_currency = $this->currencies_model->get_default_currency();

		        $paybill_payments = $this->sales_model->get_online_order_paybill_payments($ord_order_number);
		        $pesapal_payments = $this->sales_model->get_online_order_pesapal_payments($ord_order_number);

		        foreach ($order as $row) {
		            $filename='Bethany House Online Order - '.$row->ord_order_number.'.pdf';

		            // create new PDF document
		            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


		            // set document information
		            $pdf->SetCreator(PDF_CREATOR);
		            $pdf->SetAuthor('Bethany House');
		            $pdf->SetTitle('Bethany House - '.$row->ord_order_number);
		            $pdf->SetSubject('Bethany House - '.$row->ord_order_number);
		            //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

		            // remove default header/footer
		            $pdf->setPrintHeader(false);
		            $pdf->setPrintFooter(false);

		            // set default monospaced font
		            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

		            // set margins
		            $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

		            // set auto page breaks
		            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

		            // set image scale factor
		            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

		            // set font
		            $pdf->SetFont('dejavusans', 'B', 12);

		            // add a page
		            $pdf->AddPage();

		            $pdf->Image('assets/be/images/logo-2.png', 15, 15, 30, 0, 'PNG', '', 'C', true, 300, '', false, false, 0, false, false, false);

		            $pdf->Ln(10);

		            foreach ($store_information as $row2) {

		                $pdf->SetFont('dejavusans', '', 9);

		                $left_txt = '<span style="font-size: large;">' . $row2->store_name . '</span>';
		                if ($row2->physical_address != ''){
		                    $left_txt = $left_txt . '<br>' . $row2->physical_address;
		                }
		                if ($row2->postal_address != ''){
		                    $left_txt = $left_txt . '<br>' . $row2->postal_address;
		                }
		                $left_txt = $left_txt . '<br><b>Email:</b> ' . $row2->email_address;
		                $left_txt = $left_txt . '<br><b>Phone:</b> ' . $row2->phone_number;
		                if ($row2->mobile_number != ''){
		                    $left_txt = $left_txt . '/' . $row2->mobile_number;
		                }

		                $pdf->SetFillColor(255, 255, 255);
		                $pdf->setCellHeightRatio(1.5);
		                $pdf->MultiCell(90, 0, $left_txt, 0, 'L', 1, 0, '', '', true, 0, true, true, 0);

		            }

		            $right_txt = '<span style="font-size: large;"><b>Customer Order</b></span>';
		            $right_txt = $right_txt . '<br><br>Reference #: <b>' . $row->ord_order_number . '</b>';
		            $right_txt = $right_txt . '<br>Order Date: <b>' . date('M d, Y g:i A', strtotime($row->ord_date)) . '</b>';

		            if ($row->ord_order_status == 0){
		                $right_txt = $right_txt . '<br><br>Status: <b  style="color: #292b2c">Awaiting Payment</b>';
		            }elseif ($row->ord_order_status == 1){
		                $right_txt = $right_txt . '<br><br>Status: <b  style="color: #5bc0de">Processing</b>';
		            }elseif ($row->ord_order_status == 2){
		                $right_txt = $right_txt . '<br><br>Status: <b  style="color: #f0ad4e">Dispatched</b>';
		            }elseif ($row->ord_order_status == 3){
		                $right_txt = $right_txt . '<br><br>Status: <b  style="color: #5cb85c">Completed</b>';
		            }elseif ($row->ord_order_status == 4){
		                $right_txt = $right_txt . '<br><br>Status: <b  style="color: #c90000">Cancelled</b>';

		            }

		            $pdf->MultiCell(90, 0, $right_txt, 0, 'R', 1, 1, '', '', true, 0, true, true, 0);

		            $pdf->Ln(10);


		            $left_txt = '<span style="font-size: large;">ADDRESS</span>';
		            $left_txt = $left_txt . '<br>' . $row->ord_shipping_first_name . ' ' . $row->ord_shipping_last_name;
		            if ($row->ord_shipping_email_address != ''){
		                $left_txt = $left_txt . '<br><b>E:</b> ' . $row->ord_shipping_email_address;
		            }
		            if ($row->ord_shipping_phone_number != ''){
		                $left_txt = $left_txt . '<br><b>P:</b> ' . $row->ord_shipping_phone_number;
		            }
		            //if ($row->sender_phone_number != ''){
		                $left_txt = $left_txt . '<br><b>Location:</b> ' . $row->ord_shipping_street_address . ', ' . $row->shipping_region_name . ', ' . $row->shipping_country_name;
		            //}       

		            $pdf->SetFillColor(255, 255, 255);
		            $pdf->MultiCell(90, 0, $left_txt, 0, 'L', 1, 0, '', '', true, 0, true, true, 0);


		            $right_txt = '<span style="font-size: large;">DELIVERY</span>';  
		            if ($row->ord_shipping_method == 'Delivery') {
		                $right_txt = $right_txt . '<br><b>Shipping Zone:</b> ' . $row->shipping_zone_name;
		                if ($row->ord_shipping_method == 0){
		                    $right_txt = $right_txt . '<br><b>Shipping Fee:</b> Free';
		                }else{
		                    $right_txt = $right_txt . '<br><b>Shipping Fee:</b> ' . $default_currency . ' ' . number_format($row->ord_shipping_total,2);
		                }
		            } elseif ($row->ord_shipping_method == 'Pickup') {
		                $right_txt = $right_txt . '<br><b>Pickup Location:</b>';
		                $right_txt = $right_txt . $row->pickup_location_name . ', <br/>' . $row->pickup_location_address;
		                if ($row->close_to != ''){
		                   $right_txt = $right_txt . '<br><b>Close To:</b> ' . $row->close_to; 
		                }
		                $right_txt = $right_txt . '<br><b>Opening Hours:</b> ' . $row->opening_hours;
		                $right_txt = $right_txt . '<br><b>Opening Hours:</b> ' . $row->pickup_period;
		                $right_txt = $right_txt . '<br><b>Opening Hours:</b> ' . $default_currency . ' ' . number_format($row->ord_shipping_total,2);
		            } 

		            $pdf->MultiCell(90, 0, $right_txt, 0, 'R', 1, 1, '', '', true, 0, true, true, 0);

		            $pdf->Ln(12);

		            $pdf->SetFont('dejavusans', '', 8);
		            $txt = '<table border="1" cellpadding="5" cellspacing="0">
		                        <tbody>';               
		            $txt = $txt . '<tr>                     
		                        <td width="215"><b>ITEM</b></td>
		                        <td width="215"><b>UNIT PRICE</b></td>
		                        <td width="100"><b>QTY</b></td>    
		                        <td width="100"><b>TOTAL PRICE</b></td>
		                    </tr>';
		            foreach ($order_details as $row2) {
		            	$product_variation_description = '';
		            	if ($row2->ord_det_product_variation_description != ''){ $product_variation_description = '<br>' . $row2->ord_det_product_variation_description; }
		                $txt = $txt . '<tr>                     
		                        <td width="215">' . $row2->ord_det_item_name . $product_variation_description . '<br><b>SKU Code:</b>' . $row2->ord_det_product_sku_code . '</td>
		                        <td width="215">' . $default_currency . ' ' . number_format($row2->ord_det_price,2) . '</td>
		                        <td width="100">' . number_format($row2->ord_det_quantity,0) . '</td>
		                        <td width="100">' . $default_currency . ' ' . number_format($row2->ord_det_price_total,2) . '</td>    
		                    </tr>';  
		            }
		            $txt = $txt . '<tr>                     
		                        <td width="530"><b>No. of Items</b></td>
		                        <td width="100">' . number_format($row->ord_total_items,0) . '</td>    
		                    </tr>';
		            $txt = $txt . '<tr>                     
		                        <td width="530"><b>Sub Total</b></td>
		                        <td width="100">' . $default_currency . ' ' . number_format($row->ord_item_summary_total,2) . '</td>    
		                    </tr>';
		            $txt = $txt . '<tr>                     
		                        <td width="530"><b>Total Tax</b></td>
		                        <td width="100">' . $default_currency . ' ' . number_format($row->ord_tax_total,2) . '</td>    
		                    </tr>';
		            $txt = $txt . '<tr>                     
		                        <td width="530"><b>Shipping</b></td>
		                        <td width="100">' . $default_currency . ' ' . number_format($row->ord_shipping_total,2) . '</td>    
		                    </tr>';
		            $txt = $txt . '<tr>                     
		                        <td width="530"><b>Discount</b></td>
		                        <td width="100">' . $default_currency . ' ' . number_format($row->ord_savings_total,2) . '</td>    
		                    </tr>';
		            $txt = $txt . '<tr>                     
		                        <td width="530"><b>Order Total</b></td>
		                        <td width="100"><b>' . $default_currency . ' ' . number_format($row->ord_total,2) . '</b></td>    
		                    </tr>';
		            
		            $txt = $txt . '</tbody>
		                    </table>';

		            $pdf->writeHTML($txt, true, false, false, false, '');

	                $pdf->Ln(5);
	                $pdf->SetFont('dejavusans', 'B', 10);

	                $txt = 'PAYMENTS';
	                $pdf->Write(0, $txt, '', 0, '', true, 0, false, false, 0);

	                $pdf->Ln(2);
	                $pdf->SetFont('dejavusans', '', 8);

	                $txt = '<table border="1" cellpadding="5" cellspacing="0">
	                        <tbody>';
	                $txt = $txt . '<tr>                     
	                        <td width="160"><b>Receipt #</b></td>
	                        <td width="160"><b>Reference #</b></td>
	                        <td width="150"><b>Amount</b></td>    
	                        <td width="150"><b>Paid On</b></td>
	                    </tr>';

	                foreach($paybill_payments as $row2){
	                    $txt = $txt . '<tr>                     
	                        <td width="160">MPE-' . $row2->paybill_payment_id . '</td>
	                        <td width="160">' . $row2->transaction_id . '</td>
	                        <td width="150">KES ' . number_format($row2->transaction_amount,2) . '</td>    
	                        <td width="150">' . date('M d, Y g:i A', strtotime($row2->transaction_time)) . '</td>
	                    </tr>';
	                }

	                foreach($pesapal_payments as $row2){
	                    $txt = $txt . '<tr>                     
	                        <td width="160">PES-' . $row2->pesapal_payment_id . '</td>
	                        <td width="160"></td>
	                        <td width="150">KES ' . number_format($row2->transaction_amount,2) . '</td>    
	                        <td width="150">' . date('M d, Y g:i A', strtotime($row2->created_on)) . '</td>
	                    </tr>';
	                }
	                $txt = $txt . '</tbody>
	                        </table>';

	                $pdf->writeHTML($txt, true, false, false, false, '');
		            
		            $pdf->Ln(8);
		            $pdf->SetFont('dejavusans', '', 8);
		            $txt = 'Document Generated on ' . date('l, M d Y g:i A');
		            $pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

		            //Close and output PDF document
		            $pdf->Output($filename, 'I');
		        }
			}
        } 
		else {
            redirect('be/auth');
		}
	}
	function submit_dispatch_order(){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->sales_model->submit_dispatch_order();
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
	function verify_pesapal_payment($ord_order_number) {

		$context = $this->input->post('context');

		$online_order = $this->sales_model->get_online_order($ord_order_number);

		foreach ($online_order as $row) {
			$ord_transaction_tracking_id = $row->ord_transaction_tracking_id;
			$ord_merchant_reference_id = $row->ord_merchant_reference_id;
		}


		$this->load->view('fe/OAuth/OAuth'); 
			
		$CONSUMER_KEY = '';
	    $CONSUMER_SECRET = '';
	    $ENVIRONMENT = '';

	    $pesapal_settings = $this->api_model->get_pesapal_settings();
	    foreach($pesapal_settings as $row){
	    	$CONSUMER_KEY = $row->consumer_key;
	    	$CONSUMER_SECRET = $row->consumer_secret;
	    	$ENVIRONMENT = $row->environment;
	    }

		$consumer_key=$CONSUMER_KEY;
		$consumer_secret=$CONSUMER_SECRET;

		if ($ENVIRONMENT == 'LIVE') {
			$statusrequestAPI = 'https://www.pesapal.com/api/querypaymentdetails';
		} else {
			$statusrequestAPI = 'https://demo.pesapal.com/api/querypaymentdetails';
		}

		// Parameters sent to you by PesaPal IPN
		$pesapalNotification="CHANGE";
		$pesapalTrackingId = $ord_transaction_tracking_id;
		$pesapal_merchant_reference = $ord_merchant_reference_id;

		if($pesapalNotification == "CHANGE" && $pesapalTrackingId!=''){
   			$token = $params = NULL;
   			$consumer = new OAuthConsumer($consumer_key, $consumer_secret);
   			$signature_method = new OAuthSignatureMethod_HMAC_SHA1();

   			//get transaction status
   			$request_status = OAuthRequest::from_consumer_and_token($consumer, $token, "GET", $statusrequestAPI, $params);
   			$request_status->set_parameter("pesapal_merchant_reference", $pesapal_merchant_reference);
   			$request_status->set_parameter("pesapal_transaction_tracking_id",$pesapalTrackingId);
   			$request_status->sign_request($signature_method, $consumer, $token);

   			$ch = curl_init();
   			curl_setopt($ch, CURLOPT_URL, $request_status);
   			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
   			curl_setopt($ch, CURLOPT_HEADER, 1);
   			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
   				
			if(defined('CURL_PROXY_REQUIRED')) if (CURL_PROXY_REQUIRED == 'True'){
      			$proxy_tunnel_flag = (defined('CURL_PROXY_TUNNEL_FLAG') && strtoupper(CURL_PROXY_TUNNEL_FLAG) == 'FALSE') ? false : true;
      			curl_setopt ($ch, CURLOPT_HTTPPROXYTUNNEL, $proxy_tunnel_flag);
      			curl_setopt ($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
      			curl_setopt ($ch, CURLOPT_PROXY, CURL_PROXY_SERVER_DETAILS);
   			}

   			$response = curl_exec($ch);
   			$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
   			$raw_header  = substr($response, 0, $header_size - 4);
   			$headerArray = explode("\r\n\r\n", $raw_header);
   			$header      = $headerArray[count($headerArray) - 1];

   			$resStatus = '';

   			//transaction status
			// if ($response != ''){
			// 	$elements = preg_split("/=/",substr($response, $header_size));
			// 	$status = explode (",", $elements[1]);//$elements[1];
			// 	$resStatus = $status[2];
			// }

			//transaction status
			if ($response != ''){
				$elements = preg_split("/=/",substr($response, $header_size));
				$status = explode (",", $elements[1]);//$elements[1];

				// print_r($elements);

				$resStatus = $status[2];

				if ($status[2] == 'COMPLETED'){
					$order = $this->checkout_model->get_order($status[3]);
			        foreach($order as $row){
			        	$transaction_amount = $row->ord_total;
			        	$ord_order_number = $row->ord_order_number;
			            $customer_id = $row->ord_customer_id;
			        }

					$data = array(
		                'payment_method' => $status[1],
		                'transaction_tracking_id' => $status[0],
		                'merchant_reference_id' => $status[3],
		                'transaction_amount' => $transaction_amount,
		                'ord_order_number' => $ord_order_number,
		                'customer_id' => $customer_id,
		                'transaction_completed' => 1
		            );

		            $q = $this->api_model->submit_pesapal_payment($data, $status[0]);
				}
			}

   			curl_close ($ch);
			
			$data['context'] = $context;
			$data['ord_order_number'] = $ord_order_number;
			$data['order_status'] = $resStatus;
			$data['outlets'] = $this->outlets_model->get_outlets_list();
		
			$this->load->view('be/jsloads/pesapal_order_status',$data);
		}
	}

	function complete_order($ord_order_number){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->sales_model->complete_order($ord_order_number);
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

}