<?php
class Sales_model extends CI_Model {
	public function __construct(){
        parent::__construct();

        $this->load->model('be/store_information_model');
        $this->load->model('be/currencies_model');
        $this->load->library("Pdf");
    }
	
	function get_online_sales(){
        $online_sale_status = $this->input->post('online_sale_status');
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

		$this->db->select("order_summary.*, customers.customer_id, customers.first_name, customers.last_name, customers.phone_number, customers.email_address, pesapal_payments.pesapal_payment_id");
		$this->db->from('order_summary');
		$this->db->join('customers', 'customers.customer_id = order_summary.ord_customer_id', 'left outer');
		$this->db->join('pesapal_payments', 'pesapal_payments.merchant_reference_id = order_summary.ord_merchant_reference_id', 'left outer');

        if ($online_sale_status != ''){
            $this->db->where( array('order_summary.ord_order_status' => $online_sale_status));
        }

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(order_summary.ord_date, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(order_summary.ord_date, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }

		//$this->db->where( array('order_summary.is_deleted'=>0));
		return $this->db->get()->result();
	}
	function get_online_order($ord_order_number){
		$this->db->select("os.*, c.customer_id, c.first_name, c.last_name, c.phone_number, c.email_address, sc.country_name AS 'shipping_country_name', sr.region_name as 'shipping_region_name', pl.pickup_location_name, pl.pickup_location_address, pl.close_to, pl.pickup_period, pl.opening_hours, sz.shipping_zone_name, sz.shipping_method, sz.shipping_fee, pp.pesapal_payment_id");
        $this->db->from('order_summary os');
        $this->db->join('customers c', 'c.customer_id = os.ord_customer_id', 'left outer');
        $this->db->join('countries sc', 'sc.country_id = os.ord_shipping_country_id', 'left outer');
        $this->db->join('regions sr', 'sr.region_id = os.ord_shipping_region_id', 'left outer');
        $this->db->join('pickup_locations pl', 'pl.pickup_location_id = os.ord_pickup_location_id', 'left outer');
        $this->db->join('shipping_zones sz', 'sz.shipping_zone_id = os.ord_shipping_zone_id', 'left outer');
        $this->db->join('pesapal_payments pp', 'pp.merchant_reference_id = os.ord_merchant_reference_id', 'left outer');

        $this->db->where( array('os.ord_order_number'=>$ord_order_number));
        return $this->db->get()->result();
	}

	function get_online_order_details($ord_order_number) {
        $this->db->select("od.*, p.product_sku_code, p.product_reference_id, p.product_name, p.product_description, p.product_barcode, p.product_image, u.unit_name, b.brand_name, tr.tax_rate_code, ps.product_size_code, pc.product_color");
        $this->db->from('order_details od');
        $this->db->join('products p', 'p.product_id = od.ord_det_product_id');
        $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        $this->db->join('tax_rates tr', 'tr.tax_rate_id = p.tax_rate_id', 'left outer');
        $this->db->join('product_sizes ps', 'ps.product_size_id = od.ord_det_product_size_id', 'left outer');
        $this->db->join('product_colors pc', 'pc.product_color_id = od.ord_det_product_color_id', 'left outer');

        $this->db->where( array('od.ord_det_order_number_fk'=>$ord_order_number));
        return $this->db->get()->result();
    }
    function get_online_order_paybill_payments($ord_order_number) {
        $this->db->select("pap.*");
        $this->db->from('paybill_payments pap');

        $this->db->where( array('pap.ord_order_number' => $ord_order_number));
        return $this->db->get()->result();
    }

    function get_online_order_pesapal_payments($ord_order_number) {
        $this->db->select("pep.*");
        $this->db->from('pesapal_payments pep');

        $this->db->where( array('pep.ord_order_number' => $ord_order_number));
        return $this->db->get()->result();
    }

    function submit_dispatch_order(){

        $ord_order_number = $this->input->post('ord_order_number');
        $outlet_id = $this->input->post('outlet_id');
        $system_user_id = $this->session->userdata('system_user_id');

        if ($this->validate_user_dispatch($system_user_id, $outlet_id) == false){
            $arr_return = array('res' => false,'dt' => 'Sorry, Access Denied. You do not have sufficient rights to dispatch from this Outlet.');
        } else {
            $data = array(
                'ord_order_status'=> 2,
                'ord_dispatch_outlet_id'=> $outlet_id,
                'ord_dispatch_system_user_id'=> $system_user_id,
                'ord_dispatch_date'=> date('Y-m-d H:i:s')
            );          
            $this->db->where( array('ord_order_number'=>$ord_order_number));
            $update = $this->db->update('order_summary', $data);
            
            if ($update){

                ///////UPDATE STOCK
                // $outlet_id = 0;

                //GET ORDER DETAILS
                $order_details = $this->get_online_order_details($ord_order_number);

                foreach ($order_details as $row) {
                    $product_id = $row->ord_det_product_id;
                    $product_variation_id = $row->ord_det_product_variation_id;
                    $ord_det_id = $row->ord_det_id;
                    $quantity = $row->ord_det_quantity;
                    $unit_price = $row->ord_det_price;

                    $available_stock = 0;

                    $this->db->select("*");
                    $this->db->from('outlet_products');
                    $this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                    $outlet_product = $this->db->get()->result();

                    foreach ($outlet_product as $row2) {
                        $available_stock = $row2->available_stock;
                    }

                    $data = array(
                        'available_stock' =>  $available_stock - $quantity
                    );
                    $this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                    $this->db->update('outlet_products', $data);

                    //STOCK TRACKER
                    $data = array(
                        'outlet_id' => $outlet_id,
                        'product_id' => $product_id,
                        'product_variation_id' => $product_variation_id,
                        'transaction_id' => $ord_det_id,
                        'transaction_type' => 'OUT',
                        'transaction_description' => 'Online Sale',
                        'quantity' => $quantity,
                        'unit_price' => $unit_price
                    );
                    $this->db->insert('stock_tracker', $data);
                } 


                $arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Order dispatched successfully');
            }else{
                $arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error dispatching order');
            }
        }		
		return $arr_return;
    }


    function validate_user_dispatch($system_user_id, $outlet_id) {
        $access_valid = false;

        $this->db->where('system_user_id',$system_user_id);
        $this->db->where('is_deleted',0, 'is_active',1);
        $system_user = $this->db->get('system_users')->result();

        foreach ($system_user as $row) {
            if ($row->is_super_admin == 1) {
                $access_valid = true;
            }
        }

        if ($access_valid == false) {
            $this->db->select("suo.*");
            $this->db->from('system_user_outlets suo');
            $this->db->where( array('suo.system_user_id' => $system_user_id, 'suo.outlet_id' => $outlet_id));
            $system_user_outlets = $this->db->get()->result();

            foreach ($system_user_outlets as $row) {
                $access_valid = true;
            }

        }
        return $access_valid;
    }

    function complete_order($ord_order_number){
		$data = array(
			'ord_order_status'=> 3,
            'ord_completion_system_user_id'=> $this->session->userdata('system_user_id'),
            'ord_completion_date'=> date('Y-m-d H:i:s')

		);			
		$this->db->where( array('ord_order_number'=>$ord_order_number));
		$update = $this->db->update('order_summary', $data);
		
		if ($update){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Order completed successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error completing order');
		}
		return $arr_return;
    }
    function submit_send_online_order_via_email() {

        $ord_order_number = $this->input->post('ord_order_number');

        try {

            ob_start();

            $mail          = new PHPMailer();
            $mail->IsSMTP();

            $use_ssl = $this->input->post('chk_use_ssl');

            if($use_ssl == 'on'){
                $mail->SMTPSecure = 'ssl';
                $mail->SMTPAuth   = true;
            }            
            $mail->Host       = $this->input->post('mail_server_name');
            $mail->Port       = $this->input->post('mail_server_port');
            $mail->Username   = $this->input->post('sender_username');
            $mail->Password   = $this->input->post('sender_password');
            
            $mail->SetFrom($this->input->post('sender_email_address'), $this->input->post('sender_name'));
            $email_to = $this->input->post('recipient_email_address'); 
             
            $mail->Subject = $this->input->post('email_subject');

            $email_message = $this->input->post('email_message'); 

            
            $message = file_get_contents(base_url().'email_temp/emheader');
            $message .= file_get_contents(base_url().'email_temp/embody');
            $message .= file_get_contents(base_url().'email_temp/emfooter');
            $logo = base_url().'assets/fe/img/logo.png';
            
            $replacements = array(
                '({logo})' => $logo, 
                '({message_subject})' => '', 
                '({message_body})' => nl2br( stripslashes( $email_message ) )
            );
            $message = preg_replace(array_keys( $replacements ), array_values( $replacements ), $message );
            
            $plaintext = $message;
            $plaintext = strip_tags( stripslashes( $plaintext ), '<p><br><h2><h3><h1><h4>' );
            $plaintext = str_replace( array( '<p>', '<br />', '<br>', '<h1>', '<h2>', '<h3>', '<h4>' ), PHP_EOL, $plaintext );
            $plaintext = str_replace( array( '</p>', '</h1>', '</h2>', '</h3>', '</h4>' ), '', $plaintext );
            $plaintext = html_entity_decode( stripslashes( $plaintext ) );
        
            
            $mail->MsgHTML( stripslashes( $message ) ); 

            $attachment = $this->generate_online_order_pdf($ord_order_number);
            $mail->AddStringAttachment($attachment, 'Bethany House Purchase Order-'. $ord_order_number . '.pdf', 'base64', 'application/pdf');
            
            $mail->AltBody = $plaintext;
            $mail->AddAddress($email_to, "");

            if( !$mail->Send() ){
                $arr_return = array('res' => false,'dt' => $mail->ErrorInfo);
            }else{
                $arr_return = array('res' => true,'dt' => 'Email Sent successfully');
            }
            ob_get_clean();
        } catch (phpmailerException $e) {
            $arr_return = array('res' => false,'dt' =>  $e->errorMessage());
        } catch (Exception $e) {
            $arr_return = array('res' => false,'dt' =>  $e->getMessage());
        }        
        return $arr_return;
    }

    function generate_online_order_pdf($ord_order_number){
    	$order = $this->get_online_order($ord_order_number);
        $order_details = $this->get_online_order_details($ord_order_number);
        $store_information = $this->store_information_model->get_store_information();
        $default_currency = $this->currencies_model->get_default_currency();

        $paybill_payments = $this->get_online_order_paybill_payments($ord_order_number);
        $pesapal_payments = $this->get_online_order_pesapal_payments($ord_order_number);

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
                $txt = $txt . '<tr>                     
                        <td width="215">' . $row2->ord_det_item_name . '<br><b>SKU Code:</b>' . $row2->ord_det_product_sku_code . '</td>
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

        	$attachment = $pdf->Output($filename, 'S');
        }

        return $attachment;
    }
    function get_customer_by_email_address($customer_email_address){
        $this->db->from('customers');
        $this->db->where( array('email_address' => $customer_email_address));
        return $this->db->get()->result();
    }
    function get_stripped_email_message($email_message,$customer_email_address){
        $stripped_email_message= $email_message;

        $customer = $this->get_customer_by_email_address($customer_email_address);

        foreach ($customer as $row){
            $replacements = array(
                '({{first_name}})'          => $row->first_name, 
                '({{last_name}})'           => $row->last_name, 
                '({{phone_number}})'        => $row->phone_number, 
                '({{email_address}})'       => $row->email_address
            );
            $stripped_email_message = preg_replace(array_keys( $replacements ), array_values( $replacements ), $email_message);
        }
        return $stripped_email_message;
    }

    function submit_send_online_order_customer_email() {

        $customer_id = $this->input->post('customer_id');
        $customer_email_address = $this->input->post('recipient_email_address');

        try {

            ob_start();

            $mail          = new PHPMailer();
            $mail->IsSMTP();

            $use_ssl = $this->input->post('chk_use_ssl');

            if($use_ssl == 'on'){
                $mail->SMTPSecure = 'ssl';
                $mail->SMTPAuth   = true;
            }            
            $mail->Host       = $this->input->post('mail_server_name');
            $mail->Port       = $this->input->post('mail_server_port');
            $mail->Username   = $this->input->post('sender_username');
            $mail->Password   = $this->input->post('sender_password');
            
            $mail->SetFrom($this->input->post('sender_email_address'), $this->input->post('sender_name'));
            $email_to = $this->input->post('recipient_email_address'); 
             
            $mail->Subject = $this->input->post('email_subject');

            //$email_message = $this->input->post('email_message'); 
            $email_message = $this->get_stripped_email_message($this->input->post('email_message'), $customer_email_address);
            $email_message = preg_replace("/(^[\r\n]*|[\r\n]+)[\s\t]*[\r\n]+/", "", $email_message);
            
            $message = file_get_contents(base_url().'email_temp/emheader');
            $message .= file_get_contents(base_url().'email_temp/embody');
            $message .= file_get_contents(base_url().'email_temp/emfooter');
            $logo = base_url().'assets/fe/img/logo.png';
            
            $replacements = array(
                '({logo})' => $logo, 
                '({message_subject})' => '', 
                '({message_body})' => nl2br( stripslashes( $email_message ) )
            );
            $message = preg_replace(array_keys( $replacements ), array_values( $replacements ), $message );
            
            $plaintext = $message;
            $plaintext = strip_tags( stripslashes( $plaintext ), '<p><br><h2><h3><h1><h4>' );
            $plaintext = str_replace( array( '<p>', '<br />', '<br>', '<h1>', '<h2>', '<h3>', '<h4>' ), PHP_EOL, $plaintext );
            $plaintext = str_replace( array( '</p>', '</h1>', '</h2>', '</h3>', '</h4>' ), '', $plaintext );
            $plaintext = html_entity_decode( stripslashes( $plaintext ) );
        
            
            $mail->MsgHTML( stripslashes( $message ) ); 

            $mail->AltBody = $plaintext;
            $mail->AddAddress($email_to, "");

            if( !$mail->Send() ){
                $arr_return = array('res' => false,'dt' => $mail->ErrorInfo);
            }else{
                $arr_return = array('res' => true,'dt' => 'Email Sent successfully');
            }
            ob_get_clean();
        } catch (phpmailerException $e) {
            $arr_return = array('res' => false,'dt' =>  $e->errorMessage());
        } catch (Exception $e) {
            $arr_return = array('res' => false,'dt' =>  $e->getMessage());
        }        
        return $arr_return;
    }

}