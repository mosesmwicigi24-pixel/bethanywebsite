<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Checkout_model extends CI_Model{


    public function __construct(){
        parent::__construct();

        // $this->load->library('flexi_cart');
        // $this->load->library('flexi_cart_admin');

        $this->load->model('be/store_information_model');
        $this->load->model('be/currencies_model');
        $this->load->model('be/email_accounts_model');
        $this->load->model('be/email_notification_settings_model');
        $this->load->model('account_model');
        $this->load->model('affiliates_model');
        $this->load->library("Pdf");
    }

    function submit_login() {
        $password = md5($this->input->post('login_password'));
        $this->db->where('email_address', $this->input->post('login_email_address'));
        $this->db->where('password', $password);
        $this->db->from('customers');
    
        $query = $this->db->get();
        
        if($query->num_rows() > 0){
            foreach ($query->result() as $row){
                $this->session->set_userdata('bgs_fe_login_state', TRUE);
                $this->session->set_userdata('customer_id', $row->customer_id);
                $this->session->set_userdata('customer_email_address', $row->email_address);
                $this->session->set_userdata('customer_phone_number', $row->phone_number);
                $this->session->set_userdata('customer_first_name', $row->first_name);
                $this->session->set_userdata('customer_last_name', $row->last_name);
                $this->session->set_userdata('customer_profile_picture_thumb', $row->profile_picture_thumb);

                $data = array (             
                    'last_login' => date("Y-m-d H:i:s")
                );          

                $this->db->where('email_address', $row->email_address);
                $this->db->update('customers', $data);
            }   
            $arr_return = array('res' => true,'dt' => "<i class='icon-checkmark-circled'></i> Successful. Please wait while you're being logged in...");
        }else{
            $arr_return = array('res' => false,'dt' => '<i class="icon-cross-circle"></i> Login not successful. Please check your entries and try again.');
        }
        return $arr_return;
    }
    function validate_register_customer(){

        $email_address = $this->input->post('register_email_address');

        $msg = '';
        $msg2 = '';

        //EMAIL ADDRESS
        $this->db->where(array('email_address' => $email_address, 'is_deleted' => 0));
        $query = $this->db->get('customers');

        if ($query->num_rows() > 0){
            $msg = '<i class="icon-cross-circle"></i> Duplicate Email Address: The Email Address you entered is being used by another account.<br>';
        }

        if ($msg == $msg2){
            $arr_return = array('res' => true,'dt' => '');
        }else{
            $arr_return = array('res' => false,'dt' => $msg);
        }

        return $arr_return;
    }

    function submit_register($data) {
        $insert = $this->db->insert('customers', $data);
        $insert_id = $this->db->insert_id();
        if ($insert){
            $this->session->set_userdata('bgs_fe_login_state', TRUE);
            $this->session->set_userdata('customer_id', $insert_id);
            $this->session->set_userdata('customer_email_address', $this->input->post('register_email_address'));
            $this->session->set_userdata('customer_phone_number', '');
            $this->session->set_userdata('customer_first_name', $this->input->post('register_first_name'));
            $this->session->set_userdata('customer_last_name', $this->input->post('register_last_name'));
            $this->session->set_userdata('customer_profile_picture_thumb', '');

            $data = array (             
                'last_login' => date("Y-m-d H:i:s")
            );          

            $this->db->where('customer_id', $insert_id);
            $this->db->update('customers', $data);

            //NOTIFICATION
            $data = array(
                'notification_type' => 'Customer Account Creation',
                'notification_ref_id' => $insert_id,
                'notification_details' => 'A new customer account has been created: Name: <b>' .  $this->input->post('register_first_name') . ' ' . $this->input->post('register_last_name') . '</b>, Email: <b>' . $this->input->post('register_email_address') . '</b>',
                'notification_ref_link' => 'be/customers/edit/' . $insert_id
            );
            $this->db->insert('notifications',$data);

            $arr_return = array('res' => true,'dt' => '<i class="ion ion-checkmark-circled"></i> Registration successful. Please wait while you are being logged in.');
        }else{
            $arr_return = array('res' => false,'dt' => 'icon-cross-circle"></i> Registration not successful. Please try again.');
        }
        return $arr_return;     
    }

    function submit_order(){

        $shipping_mode = $this->input->post('chk_shipping_delivery_method');

        if ($shipping_mode == 'Delivery'){
            $ord_pickup_location_id = 0;
            $ord_shipping_zone_id = $this->input->post('shipping_shipping_zone_id');
        } else {
            $ord_shipping_zone_id = 0;
            $ord_pickup_location_id = $this->input->post('shipping_pickup_location_id');
        }

        $ord_affiliate_code = '';
        $ord_affiliate_click_id = 0;

        if ($this->session->userdata('referral_affiliate_code')) {
            $ord_affiliate_code = $this->session->userdata('referral_affiliate_code');
        }
        if ($this->session->userdata('referral_affiliate_click_id')) {
            $ord_affiliate_click_id = $this->session->userdata('referral_affiliate_click_id');
        }

        $sae = $this->input->post('sae');

        if ($sae == '1') {
            $custom_summary_data = array(
                'ord_shipping_method' => $this->input->post('chk_shipping_delivery_method'),
                'ord_customer_id' =>$this->session->userdata('customer_id'),
                'ord_shipping_first_name' => $this->input->post('shipping_first_name'),
                'ord_shipping_last_name' => $this->input->post('shipping_last_name'),
                'ord_shipping_email_address' => $this->input->post('shipping_email_address'),
                'ord_shipping_phone_number' => $this->input->post('shipping_phone_number'),
                'ord_shipping_street_address' => $this->input->post('shipping_street_address'),
                'ord_shipping_country_id' => $this->input->post('shipping_country_id'),
                'ord_shipping_region_id' => $this->input->post('shipping_region_id'),
                'ord_pickup_location_id' => $ord_pickup_location_id,
                'ord_shipping_zone_id' => $ord_shipping_zone_id,
                'ord_affiliate_code' => $ord_affiliate_code,
                'ord_affiliate_click_id' => $ord_affiliate_click_id
            );
        } elseif ($sae == '2') {
            $chk_different_shipping_address = $this->input->post('chk_different_shipping_address');
            if($chk_different_shipping_address == 'on'){
                $custom_summary_data = array(
                    'ord_shipping_method' => $this->input->post('chk_shipping_delivery_method'),
                    'ord_customer_id' =>$this->session->userdata('customer_id'),
                    'ord_shipping_first_name' => $this->input->post('shipping_first_name'),
                    'ord_shipping_last_name' => $this->input->post('shipping_last_name'),
                    'ord_shipping_email_address' => $this->input->post('shipping_email_address'),
                    'ord_shipping_phone_number' => $this->input->post('shipping_phone_number'),
                    'ord_shipping_street_address' => $this->input->post('shipping_street_address'),
                    'ord_shipping_country_id' => $this->input->post('shipping_country_id'),
                    'ord_shipping_region_id' => $this->input->post('shipping_region_id'),
                    'ord_pickup_location_id' => $ord_pickup_location_id,
                    'ord_shipping_zone_id' => $ord_shipping_zone_id,
                    'ord_affiliate_code' => $ord_affiliate_code,
                    'ord_affiliate_click_id' => $ord_affiliate_click_id
                );
            } else {
                $account = $this->account_model->get_account();
                foreach ($account as $row) {
                    $custom_summary_data = array(
                        'ord_shipping_method' => $this->input->post('chk_shipping_delivery_method'),
                        'ord_customer_id' =>$this->session->userdata('customer_id'),
                        'ord_shipping_first_name' => $row->shipping_first_name,
                        'ord_shipping_last_name' => $row->shipping_last_name,
                        'ord_shipping_email_address' => $row->shipping_email_address,
                        'ord_shipping_phone_number' => $row->shipping_phone_number,
                        'ord_shipping_street_address' => $row->shipping_street_address,
                        'ord_shipping_country_id' => $row->shipping_country_id,
                        'ord_shipping_region_id' => $row->shipping_region_id,
                        'ord_pickup_location_id' => $ord_pickup_location_id,
                        'ord_shipping_zone_id' => $ord_shipping_zone_id,
                        'ord_affiliate_code' => $ord_affiliate_code,
                        'ord_affiliate_click_id' => $ord_affiliate_click_id
                    );
                }
            }
        }
        
        
        $custom_item_data = array();
        foreach($this->flexi_cart_admin->cart_items(TRUE, FALSE, TRUE) as $row_id => $item){
            if (isset($item['product_id']) && ! empty($item['product_id'])){
                $custom_item_data[$row_id]['ord_det_product_id'] = $item['product_id'];
            }
            if (isset($item['product_code']) && ! empty($item['product_code'])){
                $custom_item_data[$row_id]['ord_det_product_sku_code'] = $item['product_code'];
            }
            if (isset($item['product_image']) && ! empty($item['product_image'])){
                $custom_item_data[$row_id]['ord_det_product_image'] = $item['product_image'];
            }  
            if (isset($item['product_size_id']) && ! empty($item['product_size_id'])){
                $custom_item_data[$row_id]['ord_det_product_size_id'] = $item['product_size_id'];
            }                   
            if (isset($item['product_color_id']) && ! empty($item['product_color_id'])){
                $custom_item_data[$row_id]['ord_det_product_color_id'] = $item['product_color_id'];
            }
            if (isset($item['product_variation_id']) && ! empty($item['product_variation_id'])){
                $custom_item_data[$row_id]['ord_det_product_variation_id'] = $item['product_variation_id'];
            }
            if (isset($item['product_variation_description']) && ! empty($item['product_variation_description'])){
                $custom_item_data[$row_id]['ord_det_product_variation_description'] = $item['product_variation_description'];
            } 
        }

        if ($this->flexi_cart_admin->save_order($custom_summary_data, $custom_item_data)){

            $order_number = $this->flexi_cart->order_number();

            //NOTIFICATION
            $data = array(
                'notification_type' => 'Online Order Creation',
                'notification_ref_id' => $order_number,
                'notification_details' => 'You have a new online order - Ref #: <b>' . $order_number . '</b> from <b>' .  $this->input->post('shipping_first_name') . ' ' . $this->input->post('shipping_last_name') . '</b>, Email: <b>' . $this->input->post('shipping_email_address') . '</b>',
                'notification_ref_link' => 'be/sales/online_order/' . $order_number
            );
            $this->db->insert('notifications',$data);

            $arr_return = array('res' => true,'dt' => $this->flexi_cart->get_messages());            
        }else{
            $arr_return = array('res' => false,'dt' => $this->flexi_cart->get_messages());
        }

        return $arr_return;

        //return $this->flexi_cart_admin->save_order($custom_summary_data, $custom_item_data);
    }

    function get_order($order_number) {
        $this->db->select("os.*, sc.country_name AS 'shipping_country_name', sr.region_name as 'shipping_region_name', pl.pickup_location_name, pl.pickup_location_address, pl.close_to, pl.pickup_period, pl.opening_hours, sz.shipping_zone_name");
        $this->db->from('order_summary os');
        $this->db->join('countries sc', 'sc.country_id = os.ord_shipping_country_id', 'left outer');
        $this->db->join('regions sr', 'sr.region_id = os.ord_shipping_region_id', 'left outer');
        $this->db->join('pickup_locations pl', 'pl.pickup_location_id = os.ord_pickup_location_id', 'left outer');
        $this->db->join('shipping_zones sz', 'sz.shipping_zone_id = os.ord_shipping_zone_id', 'left outer');

        $this->db->where( array('os.ord_order_number'=>$order_number));
        return $this->db->get()->result();
    }

    function get_order_details($order_number) {
        $this->db->select("od.*, p.product_sku_code, p.product_reference_id, p.product_name, p.product_description, p.product_barcode, p.product_image, u.unit_name, b.brand_name, tr.tax_rate_code, ps.product_size_code, pc.product_color");
        $this->db->from('order_details od');
        $this->db->join('products p', 'p.product_id = od.ord_det_product_id');
        $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        $this->db->join('tax_rates tr', 'tr.tax_rate_id = p.tax_rate_id', 'left outer');
        $this->db->join('product_sizes ps', 'ps.product_size_id = od.ord_det_product_size_id', 'left outer');
        $this->db->join('product_colors pc', 'pc.product_color_id = od.ord_det_product_color_id', 'left outer');

        $this->db->where( array('od.ord_det_order_number_fk'=>$order_number));
        return $this->db->get()->result();

    }

    function check_payment_exists($bill_reference_number){
        $this->db->from('paybill_payments');
        $this->db->where( array('bill_reference_number'=>$bill_reference_number, 'transaction_completed'=>0));
        return $this->db->count_all_results();
    }

    function get_payment($bill_reference_number){       
        $this->db->from('paybill_payments');
        $this->db->where( array('bill_reference_number'=>$bill_reference_number, 'transaction_completed'=>0));
        return $this->db->get()->result();
    }

    function validate_promo_code($promo_code){

        $this->session->unset_userdata('checkout_promo_code_id');
        $promo_info = '';

        $this->db->from('promo_codes');
        $this->db->where('promo_code', $promo_code);
        $this->db->where('is_deleted', 0);
        $this->db->where('is_active', 1);

        $query = $this->db->get();
        if ($query->num_rows() > 0){
            foreach ($query->result() as $row) {
                if ($row->promo_mode == 'Percentage') {
                    $promo_info = '<b>PROMO CODE:</b> ' . $row->promo_code_name . ' [' . $row->promo_code . '] - ' . number_format($row->promo_value, 1) . '% Off <a href="javascript:void(0)" class="badge badge-danger btn-remove-promo-code"><i class="icon-cross-circle"></i> Remove Promo Code</a>';
                    $discount = array('id' => $row->promo_code, 'value' => $row->promo_value, 'column' => 'total', 'calculation' => 1, 'tax_method' => 1, 'description' => $row->promo_code_name);
                } elseif ($row->promo_mode == 'Amount') {
                    $promo_info = '<b>PROMO CODE:</b> ' . $row->promo_code_name . ' [' . $row->promo_code . '] - KES ' . number_format($row->promo_value, 0) . ' Off <a href="javascript:void(0)" class="badge badge-danger btn-remove-promo-code"><i class="icon-cross-circle"></i> Remove Promo Code</a>';
                    $discount = array('id' => $row->promo_code, 'value' => $row->promo_value, 'column' => 'total', 'calculation' => 2, 'tax_method' => 1, 'description' => $row->promo_code_name);
                }
                $this->session->set_userdata('checkout_promo_code_id', $row->promo_code_id);
                $this->flexi_cart->set_discount($discount);
                $arr_return = array('res' => true, 'dt' => '<i class="icon-checkmark-circled"></i> Promo code successful', 'promo_info' => $promo_info, 'promo_code_id' => $row->promo_code_id, 'promo_code_name' => $row->promo_code_name, 'promo_mode' => $row->promo_mode, 'promo_value' => $row->promo_value);
            }
            
        } else {
            $discount = array('id' => '', 'value' => 0, 'column' => 'total', 'calculation' => 1, 'tax_method' => 1, 'description' => '');
            $this->flexi_cart->set_discount($discount);
            $arr_return = array('res' => false, 'dt' => '<i class="icon-cross-circle"></i> Invalid promo code.', 'promo_info' => '', 'promo_code_id' => '', 'promo_code_name' => '', 'promo_mode' => '', 'promo_value' => '');
        }

        return $arr_return;
    }

    function get_promo_code($promo_code_id) {
        $this->db->from('promo_codes');
        $this->db->where('promo_code_id', $promo_code_id);
        return $this->db->get()->result();
    }

    function complete_order($order_number) {

        //UPDATE ORDER SUMMARY
        $data = array(
            'ord_payment_method' => 'Mpesa',
            'ord_order_status' => 1
        );
        $this->db->where(array('ord_order_number'=> $order_number));
        $update = $this->db->update('order_summary', $data);

        //UPDATE PAYMENTS TABLE
        $order = $this->get_order($order_number);
        foreach($order as $row){
            $customer_id = $row->ord_customer_id;
            $affiliate_code = $row->ord_affiliate_code;
            $order_amount = $row->ord_total;
            $affiliate_click_id = $row->ord_affiliate_click_id;
        }

        $data = array(
            'ord_order_number' => $order_number,
            'customer_id' => $customer_id,
            'transaction_completed' => 1
        );
        $this->db->where(array('bill_reference_number'=>$order_number));
        $update = $this->db->update('paybill_payments', $data);

        //AFFILIATE REFERRAL
        if ($affiliate_code != '') {
            $affiliate = $this->affiliates_model->get_affiliate_by_code($affiliate_code);
            foreach ($affiliate as $row) {
                $affiliate_id = $row->affiliate_id;
                $affiliate_package_id = $row->affiliate_package_id;
                $commission = $row->commission;
                $total_commissions = $row->total_commissions;
                $commissions_balance = $row->commissions_balance;
            }

            $commission_amount = (((float)$commission/100) * (float)$order_amount);
            $commissions_balance = $commissions_balance + $commission_amount;

            //AFFILIATE REFERRALS TABLE
            $data = array(
                'affiliate_id' => $affiliate_id,
                'ord_order_number' => $order_number,
                'order_amount' => $order_amount,
                'affiliate_package_id' => $affiliate_package_id,
                'commission' => $commission,
                'commission_amount' => $commission_amount,
                'commissions_balance' => $commissions_balance,
                'affiliate_click_id' => $affiliate_click_id
            );  

            $this->db->insert('affiliate_referrals', $data);

            //AFFILIATES TABLE
            $data = array(
                'total_commissions' => $total_commissions + $commission_amount,
                'commissions_balance' => $commissions_balance + $commission_amount
            ); 
            $this->db->where( array('affiliate_code' => $affiliate_code));
            $this->db->update('affiliates', $data);
        }
    }

    function complete_pesapal_payment($order_number, $REFERENCE, $PESAPAL_TRANSACTION_TRACKING_ID) {

        //ORDER SUMMARY
        $data = array(
            'ord_payment_method' => 'Pesapal',
            'ord_merchant_reference_id' => $REFERENCE,
            'ord_transaction_tracking_id' => $PESAPAL_TRANSACTION_TRACKING_ID,
            'ord_order_status' => 1
        );          
        $this->db->where( array('ord_order_number'=>$REFERENCE));
        $this->db->update('order_summary', $data);

        $order = $this->get_order($order_number);
        foreach($order as $row){
            $customer_id = $row->ord_customer_id;
            $affiliate_code = $row->ord_affiliate_code;
            $order_amount = $row->ord_total;
            $affiliate_click_id = $row->ord_affiliate_click_id;
        }

        //AFFILIATE REFERRAL
        if ($affiliate_code != '') {
            $affiliate = $this->affiliates_model->get_affiliate_by_code($affiliate_code);
            foreach ($affiliate as $row) {
                $affiliate_id = $row->affiliate_id;
                $affiliate_package_id = $row->affiliate_package_id;
                $commission = $row->commission;
                $total_commissions = $row->total_commissions;
                $commissions_balance = $row->commissions_balance;
            }

            $commission_amount = (((float)$commission/100) * (float)$order_amount);
            $commissions_balance = $commissions_balance + $commission_amount;

            //AFFILIATE REFERRALS TABLE
            $data = array(
                'affiliate_id' => $affiliate_id,
                'ord_order_number' => $order_number,
                'order_amount' => $order_amount,
                'affiliate_package_id' => $affiliate_package_id,
                'commission' => $commission,
                'commission_amount' => $commission_amount,
                'commissions_balance' => $commissions_balance,
                'affiliate_click_id' => $affiliate_click_id
            ); 

            $this->db->insert('affiliate_referrals', $data);

            //AFFILIATES TABLE
            $data = array(
                'total_commissions' => $total_commissions + $commission_amount,
                'commissions_balance' => $commissions_balance
            ); 
            $this->db->where( array('affiliate_code' => $affiliate_code));
            $this->db->update('affiliates', $data);
        }
    }

    function get_order_paybill_payments($order_number) {
        $this->db->select("pap.*");
        $this->db->from('paybill_payments pap');

        $this->db->where( array('pap.ord_order_number' => $order_number));
        return $this->db->get()->result();
    }

    function get_order_pesapal_payments($order_number) {
        $this->db->select("pep.*");
        $this->db->from('pesapal_payments pep');

        $this->db->where( array('pep.ord_order_number' => $order_number));
        return $this->db->get()->result();
    }

    function generate_order_pdf($order_number) {
        
        $order = $this->get_order($order_number);
        $order_details = $this->get_order_details($order_number);
        $store_information = $this->store_information_model->get_store_information();
        $default_currency = $this->currencies_model->get_default_currency();
        $paybill_payments = $this->get_order_paybill_payments($order_number);
        $pesapal_payments = $this->get_order_pesapal_payments($order_number);

        $attachment = '';

        foreach ($order as $row) {
            $filename='Bethany House Order - '.$row->ord_order_number.'.pdf';

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
            //foreach ($business as $row){
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

            //if ($num_order_payments > 0 || $num_cash_payments > 0){
                
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

            //}
            
            $pdf->Ln(8);
            $pdf->SetFont('dejavusans', '', 8);
            $txt = 'Document Generated on ' . date('l, M d Y g:i A');
            $pdf->Write(0, $txt, '', 0, 'C', true, 0, false, false, 0);

            //Close and output PDF document
            $attachment = $pdf->Output($filename, 'S');
        }

        return $attachment;
    }

    //ORDERS
    function send_customer_order_creation_emails() {
        $this->db->select('*');
        $this->db->from('order_summary');        
        $this->db->where( array('ord_customer_email_sent'=>0));
        $orders = $this->db->get()->result();

        $mail_host = '';
        $mail_port = 465;
        $mail_username = '';
        $mail_password = '';
        $mail_sender = '';
        $mail_sender_name = '';
        $mail_use_ssl = 1;

        $defaul_email_address = $this->email_accounts_model->get_default_email_account();
        foreach ($defaul_email_address as $row) {
            $mail_host = $row->mail_server_name;
            $mail_port = $row->mail_server_port;
            $mail_username = $row->user_name;
            $mail_password = $row->password;
            $mail_sender = $row->sender_email_address;
            $mail_sender_name = $row->sender_name;
            $mail_use_ssl = $row->use_ssl;
        }

        foreach($orders as $row){
            $first_name = $row->ord_shipping_first_name;
            $last_name = $row->ord_shipping_last_name;
            $order_date = $row->ord_date;
            $order_email = $row->ord_shipping_email_address;
            $order_shipping_total = $row->ord_shipping_total;
            $order_item_summary_total = $row->ord_item_summary_total;
            $order_total = $row->ord_total;
            $order_number = $row->ord_order_number;

            $order_details = $this->get_order_details($row->ord_order_number);


            $mail          = new PHPMailer();
            $mail->IsSMTP();
            if ($mail_use_ssl == 1){
                $mail->SMTPSecure = 'ssl';
                $mail->SMTPAuth   = true;
            }
            $mail->Host       = $mail_host;
            $mail->Port       = $mail_port;
            $mail->Username   = $mail_username;
            $mail->Password   = $mail_password;
            
            $mail->SetFrom($mail_sender, $mail_sender_name);
            $email_to = $order_email; 

            $mail->Subject = 'Order Created Successfully - Order No: ' . $order_number;
            $email_message = "Hello ". $first_name . ", <br /><br />You have successfully created an order on Bethany House. A summary of the order is shown below:<br /><br />";
            $email_message .= "Order Summary:<br /><br />"; 
            $email_message .= "<strong>Customer Name:</strong> ".$first_name." " .$last_name."<br />"; 
            $email_message .= "<strong>Customer Email:</strong> ".$order_email."<br />"; 
            $email_message .= "<strong>Order Ref No:</strong> ".$order_number."<br />"; 
            $email_message .= "<strong>Order Date:</strong> ".date('jS M Y', strtotime($order_date))."<br />";  
            $email_message .= "<strong>Items Total:</strong> ".$order_item_summary_total."<br />";
            $email_message .= "<strong>Shipping:</strong> ".$order_shipping_total."<br />";      
            $email_message .= "<strong>Order Total:</strong> ".$order_total."<br /><br />";  
            $email_message .= "Order Details:<br /><br />"; 
            $itemcount = 0;
            foreach ($order_details as $od){
                $itemcount++;
                $email_message .= "<strong>&nbsp;".$itemcount.".&nbsp;".$od->ord_det_item_name."</strong><br />"; 
                $email_message .= "&nbsp;<strong>Qty:</strong>".$od->ord_det_quantity."&nbsp;&nbsp;<strong>Price:</strong>".$od->ord_det_price."<br />"; 
                $email_message .= "&nbsp;<strong>Total Price:</strong>".$od->ord_det_price_total."<br /><br />"; 
            }
            $email_message .= "Please click on the link below to check the status of your order.<br /><br />";
            $email_message .= "<a href='".base_url()."account/order/" . $order_number . "' style='color:#EE7202 !important'>Check Order Status</a><br /><br />";

            $email_message .= "Regards,<br />";
            $email_message .= "System Support Officer<br />";
            $email_message .= "Bethany House<br />";
            $email_message .= "_________________________________________________<br />";
            $email_message .= "Note: This is a system generated mail. Please do NOT reply to it.";

            
            $message = file_get_contents(base_url().'email_temp/emheader');
            $message .= file_get_contents(base_url().'email_temp/embody');
            $message .= file_get_contents(base_url().'email_temp/emfooter');
            $logo = base_url().'assets/fe/img/logo.png';
            
            $replacements = array(
                '({logo})' => $logo, 
                '({message_subject})' => 'Order Created Successfully - Order No: ' . $order_number, 
                '({message_body})' => nl2br( stripslashes( $email_message ) )
            );
            $message = preg_replace(array_keys( $replacements ), array_values( $replacements ), $message );
            
            $plaintext = $message;
            $plaintext = strip_tags( stripslashes( $plaintext ), '<p><br><h2><h3><h1><h4>' );
            $plaintext = str_replace( array( '<p>', '<br />', '<br>', '<h1>', '<h2>', '<h3>', '<h4>' ), PHP_EOL, $plaintext );
            $plaintext = str_replace( array( '</p>', '</h1>', '</h2>', '</h3>', '</h4>' ), '', $plaintext );
            $plaintext = html_entity_decode( stripslashes( $plaintext ) );
        
            
            $mail->MsgHTML( stripslashes( $message ) ); 

            $attachment = $this->generate_order_pdf($row->ord_order_number);
            $mail->AddStringAttachment($attachment, 'Bethany House Order-'.$row->ord_order_number.'.pdf', 'base64', 'application/pdf');
            
            $mail->AltBody = $plaintext;
            $mail->AddAddress($email_to, "");
        
            if( !$mail->Send() ){
                //$arr_return = array('res' => TRUE,'dt' => 'Not Sent successfully.');
            }else{
                $data = array('ord_customer_email_sent'=> 1);           
                $this->db->where(array('ord_order_number' => $row->ord_order_number));
                $this->db->update('order_summary', $data);

                //$arr_return = array('res' => TRUE,'dt' => 'Sent successfully. An Email has been sent to you with your New Password details.');
            }
        }

    }

    function send_admin_order_creation_emails() {
        $this->db->select("os.*, sc.country_name AS 'shipping_country_name', sr.region_name as 'shipping_region_name', pl.pickup_location_name, pl.pickup_location_address, pl.close_to, pl.pickup_period, pl.opening_hours, sz.shipping_zone_name, c.first_name, c.last_name, c.phone_number, c.email_address");
        $this->db->from('order_summary os');
        $this->db->join('customers c', 'c.customer_id = os.ord_customer_id', 'left outer');
        $this->db->join('countries sc', 'sc.country_id = os.ord_shipping_country_id', 'left outer');
        $this->db->join('regions sr', 'sr.region_id = os.ord_shipping_region_id', 'left outer');
        $this->db->join('pickup_locations pl', 'pl.pickup_location_id = os.ord_pickup_location_id', 'left outer');
        $this->db->join('shipping_zones sz', 'sz.shipping_zone_id = os.ord_shipping_zone_id', 'left outer');

        $this->db->where( array('os.ord_order_status != '=>0, 'os.ord_order_status != '=>4, 'os.ord_admin_email_sent'=>0));
        $orders = $this->db->get()->result();

        $store_information = $this->store_information_model->get_store_information();
        $default_currency = $this->currencies_model->get_default_currency();

        $mail_host = '';
        $mail_port = 465;
        $mail_username = '';
        $mail_password = '';
        $mail_sender = '';
        $mail_sender_name = '';
        $mail_use_ssl = 1;

        $defaul_email_address = $this->email_accounts_model->get_default_email_account();
        foreach ($defaul_email_address as $row) {
            $mail_host = $row->mail_server_name;
            $mail_port = $row->mail_server_port;
            $mail_username = $row->user_name;
            $mail_password = $row->password;
            $mail_sender = $row->sender_email_address;
            $mail_sender_name = $row->sender_name;
            $mail_use_ssl = $row->use_ssl;
        }

        $recipient_email_address = '';
        $cc_email_address = '';
        $bcc_email_address = '';

        $email_notification_settings = $this->email_notification_settings_model->get_email_notification_settings();
        foreach ($email_notification_settings as $row) {
            $recipient_email_address = $row->email_address;
            $cc_email_address = $row->cc_email_address;
            $bcc_email_address = $row->bcc_email_address;
        }

        foreach($orders as $row){

            $order_details = $this->get_order_details($row->ord_order_number);
            $paybill_payments = $this->get_order_paybill_payments($row->ord_order_number);
            $pesapal_payments = $this->get_order_pesapal_payments($row->ord_order_number);


            $mail          = new PHPMailer();
            $mail->IsSMTP();
            if ($mail_use_ssl == 1){
                $mail->SMTPSecure = 'ssl';
                $mail->SMTPAuth   = true;
            }
            $mail->Host       = $mail_host;
            $mail->Port       = $mail_port;
            $mail->Username   = $mail_username;
            $mail->Password   = $mail_password;
            
            $mail->SetFrom($mail_sender, $mail_sender_name);
            $email_to = $recipient_email_address;

            $mail->Subject = 'You have a new Bethany House Order No: ' . $row->ord_order_number;


            $email_message = '<table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tbody>
                    <tr>
                        <td bgcolor="#f2f2f2" style="font-size: 0px;">&nbsp;</td>
                        <td bgcolor="#ffffff" width="660" align="center">
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td align="center" width="600" valign="top">
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                <tbody>
                                                    <tr>
                                                        <td bgcolor="#f2f2f2" style="padding-top: 10px;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td bgcolor="#f2f2f2" style="padding-top: 10px;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" valign="top" bgcolor="#ffffff">
                                                            <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 10px;" width="100%">
                                                                <tbody>
                                                                    <tr valign="bottom">
                                                                        <td width="20" align="center" valign="top">&nbsp;</td>
                                                                        <td align="left" height="64">
                                                                            <img
                                                                                alt=""
                                                                                style="height: 46px;"
                                                                                height="46"
                                                                                border="0"
                                                                                src="' . base_url() . 'assets/fe/img/logo.png"
                                                                                class="CToWUd"
                                                                            />
                                                                        </td>
                                                                        <td width="40" align="center" valign="top">&nbsp;</td>
                                                                        <td align="right">
                                                                            <span>
                                                                                <span style="display: inline;">' .
                                                                                    date('M d, Y g:i A', strtotime($row->ord_date)) .
                                                                                '</span>

                                                                                <span style="display: inline;">
                                                                                    <span style="display: inline;">
                                                                                        <br />
                                                                                        Order No:
                                                                                        <a
                                                                                            href="' . base_url() . 'be/sales/online_order/' . $row->ord_order_number . '"
                                                                                            style="text-decoration: none;"
                                                                                            target="_blank"
                                                                                            data-saferedirecturl="' . base_url() . 'be/sales/online_order/' . $row->ord_order_number . '"
                                                                                        >' .
                                                                                            $row->ord_order_number . '&nbsp;
                                                                                        </a>
                                                                                    </span>
                                                                                </span>
                                                                            </span>
                                                                        </td>
                                                                        <td width="20" align="center" valign="top">&nbsp;</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>

                                                            <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 10px;" width="100%">
                                                                <tbody>
                                                                    <tr valign="bottom">
                                                                        <td width="20" align="center" valign="top">&nbsp;</td>
                                                                        <td valign="top" style="font-family: Calibri, Trebuchet, Arial, sans serif; font-size: 15px; line-height: 22px; color: #333333;">
                                                                            <div style="color: #333 !important; font-family: arial, helvetica, sans-serif; font-size: 12px;">
                                                                                <span style="color: #333333 !important; font-weight: bold; font-family: arial, helvetica, sans-serif;">Hello Bethany House,</span><br />
                                                                                <p style="font-size: 14px; color: #c88039; font-weight: bold; text-decoration: none;">
                                                                                    Below are the details for this order: Order #' . $row->ord_order_number . ' raised on ' . date('M d, Y', strtotime($row->ord_date)) . '
                                                                                </p>
                                                                                <table cellpadding="5">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td valign="top"></td>
                                                                                            <td></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                                <br />
                                                                                <table
                                                                                    align="left"
                                                                                    border="0"
                                                                                    cellpadding="0"
                                                                                    cellspacing="0"
                                                                                    style="color: #666666 !important; font-family: arial, helvetica, sans-serif; font-size: 11px; margin-bottom: 20px; clear: both;"
                                                                                    width="100%"
                                                                                >
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td style="padding-top: 15px; padding-right: 10px;" valign="top" width="50%">
                                                                                                <span style="color: #333333; font-weight: bold;">
                                                                                                    Shipping Info
                                                                                                </span>
                                                                                                <br />

                                                                                                <span style="display: inline;">' .
                                                                                                    $row->ord_shipping_first_name . ' ' . $row->ord_shipping_last_name . '
                                                                                                </span>
                                                                                                <br />

                                                                                                <a href="mailto:' . $row->ord_shipping_email_address . '" target="_blank">' . $row->ord_shipping_email_address . '</a>
                                                                                                <br />

                                                                                                <span style="display: inline;">
                                                                                                    <span style="display: inline;">' . 
                                                                                                        $row->ord_shipping_phone_number . '
                                                                                                    </span>
                                                                                                </span>
                                                                                                <br />
                                                                                                <span style="display: inline;"><b>Location:</b> ' .
                                                                                                    $row->ord_shipping_street_address . ', ' . $row->shipping_region_name . ', ' . $row->shipping_country_name . '
                                                                                                </span>
                                                                                            </td>
                                                                                            <td style="padding-top: 15px;" valign="top" width="50%">
                                                                                                <span style="color: #333333; font-weight: bold;">
                                                                                                    Delivery Info
                                                                                                </span>';

                                                                                                if ($row->ord_shipping_method == 'Delivery') {
                                                                                                    $email_message = $email_message . '<br /><span style="display: inline;"><b>Shipping Zone:</b> ' . $row->shipping_zone_name . '</span>';
                                                                                                    if ($row->ord_shipping_method == 0){
                                                                                                        $email_message = $email_message . '<br /><span style="display: inline;"><b>Shipping Fee:</b> Free</span>';
                                                                                                    }else{
                                                                                                        $email_message = $email_message . '<br /><span style="display: inline;"><b>Shipping Fee:</b> ' . $default_currency . ' ' . number_format($row->ord_shipping_total,2) . '</span>';
                                                                                                    }
                                                                                                } elseif ($row->ord_shipping_method == 'Pickup') {
                                                                                                    $email_message = $email_message . '<br /><span style="display: inline;"><b>Pickup Location:</b> ' . $row->pickup_location_name . ', <br/>' . $row->pickup_location_address . '</span>';
                                                                                                    if ($row->close_to != ''){
                                                                                                       $email_message = $email_message . '<br /><span style="display: inline;"><b>Close To:</b> ' . $row->close_to . '</span>'; 
                                                                                                    }
                                                                                                    $email_message = $email_message . '<br /><span style="display: inline;"><b>Opening Hours:</b> ' . $row->opening_hours . '</span>';
                                                                                                    $email_message = $email_message . '<br /><span style="display: inline;"><b>Pickup Period:</b> ' . $row->pickup_period . '</span>';
                                                                                                    $email_message = $email_message . '<br /><span style="display: inline;"><b>Shipping Fee:</b> ' . $default_currency . ' ' . number_format($row->ord_shipping_total,2) . '</span>';
                                                                                                } 

                                                                                                $email_message = $email_message . '</td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <td><span style="display: inline;"> </span></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td style="padding-top: 15px; padding-right: 10px;" valign="top" width="50%">
                                                                                                <span style="display: inline;"> </span>
                                                                                            </td>
                                                                                            <td style="padding-top: 15px;" valign="top" width="50%">
                                                                                                <span style="display: inline;"> </span>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>

                                                                                <span style="display: inline;">
                                                                                    <table
                                                                                        align="center"
                                                                                        border="0"
                                                                                        cellpadding="0"
                                                                                        cellspacing="0"
                                                                                        style="clear: both; color: #666666 !important; font-family: arial, helvetica, sans-serif; font-size: 11px;"
                                                                                        width="100%"
                                                                                    >
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td style="border: 1px solid #ccc; border-right: none; border-left: none; padding: 5px 10px 5px 10px !important; color: #333333 !important;" width="40%">
                                                                                                    Description
                                                                                                </td>
                                                                                                <td
                                                                                                    align="right"
                                                                                                    style="border: 1px solid #ccc; border-right: none; border-left: none; padding: 5px 10px 5px 10px !important; color: #333333 !important;"
                                                                                                    width="20%"
                                                                                                >
                                                                                                    Unit price
                                                                                                </td>
                                                                                                <td
                                                                                                    align="right"
                                                                                                    style="border: 1px solid #ccc; border-right: none; border-left: none; padding: 5px 10px 5px 10px !important; color: #333333 !important;"
                                                                                                    width="15%"
                                                                                                >
                                                                                                    Qty
                                                                                                </td>
                                                                                                <td
                                                                                                    align="right"
                                                                                                    style="border: 1px solid #ccc; border-right: none; border-left: none; padding: 5px 10px 5px 10px !important; color: #333333 !important;"
                                                                                                    width="25%"
                                                                                                >
                                                                                                    Amount
                                                                                                </td>
                                                                                            </tr>';
                                                                                            foreach ($order_details as $row2) {
                                                                                                $email_message = $email_message . '<tr>
                                                                                                    <td style="padding: 10px;" width="40%">' .
                                                                                                        $row2->ord_det_item_name . '<br><b>SKU Code:</b>' . $row2->ord_det_product_sku_code . '
                                                                                                    </td>
                                                                                                    <td align="right" style="padding: 10px;" width="20%">
                                                                                                        <span style="display: inline;">' .
                                                                                                            $default_currency . ' ' . number_format($row2->ord_det_price,2) . '
                                                                                                        </span>
                                                                                                    </td>
                                                                                                    <td align="right" style="padding: 10px;" width="15%">' .
                                                                                                        number_format($row2->ord_det_quantity,0) . '
                                                                                                    </td>
                                                                                                    <td align="right" style="padding: 10px;" width="25%">
                                                                                                        <span style="display: inline;">' .
                                                                                                            $default_currency . ' ' . number_format($row2->ord_det_price_total,2) . '
                                                                                                        </span>
                                                                                                    </td>
                                                                                                </tr>';
                                                                                            }
                                                                                            
                                                                                        $email_message = $email_message . '</tbody>
                                                                                    </table>
                                                                                </span>

                                                                                <table
                                                                                    border="0"
                                                                                    cellpadding="0"
                                                                                    cellspacing="0"
                                                                                    style="border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; clear: both; color: #666666 !important; font-family: arial, helvetica, sans-serif; font-size: 11px;"
                                                                                    width="100%"
                                                                                >
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td><span style="display: inline;"> </span></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <table
                                                                                                    align="right"
                                                                                                    border="0"
                                                                                                    cellpadding="0"
                                                                                                    cellspacing="0"
                                                                                                    style="color: #666666 !important; font-family: arial, helvetica, sans-serif; font-size: 11px; margin-top: 20px; clear: both; width: 100%;"
                                                                                                >
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td style="width: 75%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <strong>
                                                                                                                    No. of Items
                                                                                                                </strong>
                                                                                                            </td>
                                                                                                            <td style="width: 25%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <span style="display: inline;">' . 
                                                                                                                    number_format($row->ord_total_items,0) . '
                                                                                                                </span>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td style="width: 75%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <strong>
                                                                                                                    Subtotal
                                                                                                                </strong>
                                                                                                            </td>
                                                                                                            <td style="width: 25%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <span style="display: inline;">' .
                                                                                                                    $default_currency . ' ' . number_format($row->ord_item_summary_total,2) . '
                                                                                                                </span>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td style="width: 75%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <strong>
                                                                                                                    Tax
                                                                                                                </strong>
                                                                                                            </td>
                                                                                                            <td style="width: 25%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <span style="display: inline;">' .
                                                                                                                    $default_currency . ' ' . number_format($row->ord_tax_total,2) . '
                                                                                                                </span>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td style="width: 75%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <strong>
                                                                                                                    Shipping
                                                                                                                </strong>
                                                                                                            </td>
                                                                                                            <td style="width: 25%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <span style="display: inline;">' .
                                                                                                                    $default_currency . ' ' . number_format($row->ord_shipping_total,2) . '
                                                                                                                </span>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td style="width: 75%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <strong>
                                                                                                                    Discount
                                                                                                                </strong>
                                                                                                            </td>
                                                                                                            <td style="width: 25%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <span style="display: inline;">' .
                                                                                                                    $default_currency . ' ' . number_format($row->ord_savings_total,2) . '
                                                                                                                </span>
                                                                                                            </td>
                                                                                                        </tr>

                                                                                                        <tr>
                                                                                                            <td style="width: 75%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <span style="color: #333333 !important; font-weight: bold;">
                                                                                                                    Total
                                                                                                                </span>
                                                                                                            </td>
                                                                                                            <td style="width: 25%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <span style="display: inline;">' .
                                                                                                                    $default_currency . ' ' . number_format($row->ord_total,2) . '
                                                                                                                </span>
                                                                                                            </td>
                                                                                                        </tr>

                                                                                                        <tr>
                                                                                                            <td><span style="display: inline;"> </span></td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <td style="color: #757575; padding-bottom: 20px; padding-left: 10px;">
                                                                                                <br />
                                                                                                <span style="color: #333333; font-weight: bold;">
                                                                                                    Payments
                                                                                                </span>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                                <span style="display: inline;">
                                                                                    <table
                                                                                        align="center"
                                                                                        border="0"
                                                                                        cellpadding="0"
                                                                                        cellspacing="0"
                                                                                        style="clear: both; color: #666666 !important; font-family: arial, helvetica, sans-serif; font-size: 11px;"
                                                                                        width="100%"
                                                                                    >
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td style="border: 1px solid #ccc; border-right: none; border-left: none; padding: 5px 10px 5px 10px !important; color: #333333 !important;" width="25%">
                                                                                                    Receipt #
                                                                                                </td>
                                                                                                <td
                                                                                                    align="right"
                                                                                                    style="border: 1px solid #ccc; border-right: none; border-left: none; padding: 5px 10px 5px 10px !important; color: #333333 !important;"
                                                                                                    width="25%"
                                                                                                >
                                                                                                    Reference #
                                                                                                </td>
                                                                                                <td
                                                                                                    align="right"
                                                                                                    style="border: 1px solid #ccc; border-right: none; border-left: none; padding: 5px 10px 5px 10px !important; color: #333333 !important;"
                                                                                                    width="25%"
                                                                                                >
                                                                                                    Amount
                                                                                                </td>
                                                                                                <td
                                                                                                    align="right"
                                                                                                    style="border: 1px solid #ccc; border-right: none; border-left: none; padding: 5px 10px 5px 10px !important; color: #333333 !important;"
                                                                                                    width="25%"
                                                                                                >
                                                                                                    Paid On
                                                                                                </td>
                                                                                            </tr>';

                                                                                            foreach ($paybill_payments as $row2){
                                                                                                $email_message = $email_message . '<tr>
                                                                                                    <td style="padding: 10px;" width="25%">MPE-' .
                                                                                                        $row2->paybill_payment_id . '
                                                                                                    </td>
                                                                                                    <td align="right" style="padding: 10px;" width="25%">
                                                                                                        <span style="display: inline;">' .
                                                                                                            $row2->transaction_id . '
                                                                                                        </span>
                                                                                                    </td>
                                                                                                    <td align="right" style="padding: 10px;" width="25%">' .
                                                                                                        $default_currency . ' ' . number_format($row2->transaction_amount,2) . '
                                                                                                    </td>
                                                                                                    <td align="right" style="padding: 10px;" width="25%">
                                                                                                        <span style="display: inline;">' .
                                                                                                            date('M d, Y g:i A', strtotime($row2->transaction_time)) . '
                                                                                                        </span>
                                                                                                    </td>
                                                                                                </tr>';
                                                                                            }

                                                                                            foreach ($pesapal_payments as $row2){
                                                                                                $email_message = $email_message . '<tr>
                                                                                                    <td style="padding: 10px;" width="25%">PES-' .
                                                                                                        $row2->pesapal_payment_id . '
                                                                                                    </td>
                                                                                                    <td align="right" style="padding: 10px;" width="25%">
                                                                                                        <span style="display: inline;">
                                                                                                            <br />
                                                                                                        </span>
                                                                                                    </td>
                                                                                                    <td align="right" style="padding: 10px;" width="25%">' .
                                                                                                        $default_currency . ' ' . number_format($row2->transaction_amount,2) . '
                                                                                                    </td>
                                                                                                    <td align="right" style="padding: 10px;" width="25%">
                                                                                                        <span style="display: inline;">' .
                                                                                                            date('M d, Y g:i A', strtotime($row2->created_on)) . '
                                                                                                        </span>
                                                                                                    </td>
                                                                                                </tr>';
                                                                                            }

                                                                                            $email_message = $email_message . '</tbody>
                                                                                    </table>
                                                                                </span>

                                                                                <br />
                                                                            </div>
                                                                            <table>
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                            <span style="font-weight: bold; color: #444;"> </span>
                                                                            <span> </span>
                                                                        </td>
                                                                        <td width="20" align="center" valign="top">&nbsp;</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td align="center" width="600" valign="top">
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                <tbody>
                                                    <tr>
                                                        <td align="center" valign="top" bgcolor="#f2f2f2">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                <tbody>
                                                                    <tr valign="bottom">
                                                                        <td width="20" align="center" valign="top">&nbsp;</td>
                                                                        <td>
                                                                            <span>
                                                                                <table
                                                                                    border="0"
                                                                                    cellpadding="0"
                                                                                    cellspacing="0"
                                                                                    id="m_-3412486042196783110emailFooter"
                                                                                    style="padding-top: 20px; font: 12px Arial, Verdana, Helvetica, sans-serif; color: #292929;"
                                                                                    width="100%"
                                                                                >
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <p style="font-size: 11px;">Copyright © ' . date('Y') . ' Bethany House. All rights reserved.</p>
                                                                                                <p style="font-size: 11px;">
                                                                                                    We would like to hear from you. For any questions, suggestions or comments please contact us at: info@bethanyhouse.co.ke.
                                                                                                </p>
                                                                                                <p style="font-size: 11px;">
                                                                                                    Please note that product prices and availability are subject to change. Prices and availability were accurate at the time this email was sent however, they may differ from those you see when you visit https://bethanyhouse.co.ke.
                                                                                                </p>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </span>
                                                                        </td>
                                                                        <td width="20" align="center" valign="top">&nbsp;</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td bgcolor="#f2f2f2" style="font-size: 0px;">&nbsp;</td>
                    </tr>
                </tbody>
            </table>';
            
            $plaintext = $email_message;
            $plaintext = strip_tags( stripslashes( $plaintext ), '<p><br /><br><h2><h3><h1><h4><span>' );
            $plaintext = str_replace( array( '<p>', '<br />', '<br>', '<h1>', '<h2>', '<h3>', '<h4>', '<span>' ), PHP_EOL, $plaintext );
            $plaintext = str_replace( array( '</p>', '</h1>', '</h2>', '</h3>', '</h4>', '</span>' ), '', $plaintext );
            $plaintext = html_entity_decode( stripslashes( $plaintext ) );
        
            
            $mail->MsgHTML( stripslashes( $email_message ) ); 

            $attachment = $this->generate_order_pdf($row->ord_order_number);
            $mail->AddStringAttachment($attachment, 'Bethany House Order-'.$row->ord_order_number.'.pdf', 'base64', 'application/pdf');
            
            $mail->AltBody = $plaintext;
            $mail->AddAddress($email_to, "");
            if ($cc_email_address != '') {
                $mail->addCC($cc_email_address, "");
            }
            if ($bcc_email_address != '') {
                $mail->addBCC($bcc_email_address, "");
            }
        
            if( !$mail->Send() ){
                //$arr_return = array('res' => TRUE,'dt' => 'Not Sent successfully.');
            }else{
                $data = array('ord_admin_email_sent'=> 1);           
                $this->db->where(array('ord_order_number' => $row->ord_order_number));
                $this->db->update('order_summary', $data);

                //$arr_return = array('res' => TRUE,'dt' => 'Sent successfully. An Email has been sent to you with your New Password details.');
            }
        }
    }


    //RECEIPTS
    function send_customer_receipts() {
        $this->db->select("os.*, sc.country_name AS 'shipping_country_name', sr.region_name as 'shipping_region_name', pl.pickup_location_name, pl.pickup_location_address, pl.close_to, pl.pickup_period, pl.opening_hours, sz.shipping_zone_name, c.first_name, c.last_name, c.phone_number, c.email_address");
        $this->db->from('order_summary os');
        $this->db->join('customers c', 'c.customer_id = os.ord_customer_id', 'left outer');
        $this->db->join('countries sc', 'sc.country_id = os.ord_shipping_country_id', 'left outer');
        $this->db->join('regions sr', 'sr.region_id = os.ord_shipping_region_id', 'left outer');
        $this->db->join('pickup_locations pl', 'pl.pickup_location_id = os.ord_pickup_location_id', 'left outer');
        $this->db->join('shipping_zones sz', 'sz.shipping_zone_id = os.ord_shipping_zone_id', 'left outer');

        $this->db->where( array('os.ord_order_status != '=>0, 'os.ord_order_status != '=>4, 'os.ord_customer_email_sent'=>0));
        $orders = $this->db->get()->result();

        $store_information = $this->store_information_model->get_store_information();
        $default_currency = $this->currencies_model->get_default_currency();

        $mail_host = '';
        $mail_port = 465;
        $mail_username = '';
        $mail_password = '';
        $mail_sender = '';
        $mail_sender_name = '';
        $mail_use_ssl = 1;

        $defaul_email_address = $this->email_accounts_model->get_default_email_account();
        foreach ($defaul_email_address as $row) {
            $mail_host = $row->mail_server_name;
            $mail_port = $row->mail_server_port;
            $mail_username = $row->user_name;
            $mail_password = $row->password;
            $mail_sender = $row->sender_email_address;
            $mail_sender_name = $row->sender_name;
            $mail_use_ssl = $row->use_ssl;
        }

        foreach($orders as $row){

            $order_details = $this->get_order_details($row->ord_order_number);
            $paybill_payments = $this->get_order_paybill_payments($row->ord_order_number);
            $pesapal_payments = $this->get_order_pesapal_payments($row->ord_order_number);


            $mail          = new PHPMailer();
            $mail->IsSMTP();
            if ($mail_use_ssl == 1){
                $mail->SMTPSecure = 'ssl';
                $mail->SMTPAuth   = true;
            }
            $mail->Host       = $mail_host;
            $mail->Port       = $mail_port;
            $mail->Username   = $mail_username;
            $mail->Password   = $mail_password;
            
            $mail->SetFrom($mail_sender, $mail_sender_name);
            $email_to = $row->email_address; 

            $mail->Subject = 'Bethany House Payment Receipt - Order No: ' . $row->ord_order_number;


            $email_message = '<table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tbody>
                    <tr>
                        <td bgcolor="#f2f2f2" style="font-size: 0px;">&nbsp;</td>
                        <td bgcolor="#ffffff" width="660" align="center">
                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td align="center" width="600" valign="top">
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                <tbody>
                                                    <tr>
                                                        <td bgcolor="#f2f2f2" style="padding-top: 10px;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td bgcolor="#f2f2f2" style="padding-top: 10px;"></td>
                                                    </tr>
                                                    <tr>
                                                        <td align="center" valign="top" bgcolor="#ffffff">
                                                            <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 10px;" width="100%">
                                                                <tbody>
                                                                    <tr valign="bottom">
                                                                        <td width="20" align="center" valign="top">&nbsp;</td>
                                                                        <td align="left" height="64">
                                                                            <img
                                                                                alt=""
                                                                                style="height: 46px;"
                                                                                height="46"
                                                                                border="0"
                                                                                src="' . base_url() . 'assets/fe/img/logo.png"
                                                                                class="CToWUd"
                                                                            />
                                                                        </td>
                                                                        <td width="40" align="center" valign="top">&nbsp;</td>
                                                                        <td align="right">
                                                                            <span>
                                                                                <span style="display: inline;">' .
                                                                                    date('M d, Y g:i A', strtotime($row->ord_date)) .
                                                                                '</span>

                                                                                <span style="display: inline;">
                                                                                    <span style="display: inline;">
                                                                                        <br />
                                                                                        Order No:
                                                                                        <a
                                                                                            href="' . base_url() . 'account/order/' . $row->ord_order_number . '"
                                                                                            style="text-decoration: none;"
                                                                                            target="_blank"
                                                                                            data-saferedirecturl="' . base_url() . 'account/order/' . $row->ord_order_number . '"
                                                                                        >' .
                                                                                            $row->ord_order_number . '&nbsp;
                                                                                        </a>
                                                                                    </span>
                                                                                </span>
                                                                            </span>
                                                                        </td>
                                                                        <td width="20" align="center" valign="top">&nbsp;</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>

                                                            <table border="0" cellpadding="0" cellspacing="0" style="margin-bottom: 10px;" width="100%">
                                                                <tbody>
                                                                    <tr valign="bottom">
                                                                        <td width="20" align="center" valign="top">&nbsp;</td>
                                                                        <td valign="top" style="font-family: Calibri, Trebuchet, Arial, sans serif; font-size: 15px; line-height: 22px; color: #333333;">
                                                                            <div style="color: #333 !important; font-family: arial, helvetica, sans-serif; font-size: 12px;">
                                                                                <span style="color: #333333 !important; font-weight: bold; font-family: arial, helvetica, sans-serif;">Hello ' . $row->first_name . ' ' . $row->last_name . ',</span><br />
                                                                                <p style="font-size: 14px; color: #c88039; font-weight: bold; text-decoration: none;">
                                                                                    This is a payment receipt for Order #' . $row->ord_order_number . ' raised on ' . date('M d, Y', strtotime($row->ord_date)) . '
                                                                                </p>
                                                                                <table cellpadding="5">
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td valign="top"></td>
                                                                                            <td></td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                                <br />
                                                                                <b>Note:</b> This email will serve as an official receipt for this payment.
                                                                                <table
                                                                                    align="left"
                                                                                    border="0"
                                                                                    cellpadding="0"
                                                                                    cellspacing="0"
                                                                                    style="color: #666666 !important; font-family: arial, helvetica, sans-serif; font-size: 11px; margin-bottom: 20px; clear: both;"
                                                                                    width="100%"
                                                                                >
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td style="padding-top: 15px; padding-right: 10px;" valign="top" width="50%">
                                                                                                <span style="color: #333333; font-weight: bold;">
                                                                                                    Shipping Info
                                                                                                </span>
                                                                                                <br />

                                                                                                <span style="display: inline;">' .
                                                                                                    $row->ord_shipping_first_name . ' ' . $row->ord_shipping_last_name . '
                                                                                                </span>
                                                                                                <br />

                                                                                                <a href="mailto:' . $row->ord_shipping_email_address . '" target="_blank">' . $row->ord_shipping_email_address . '</a>
                                                                                                <br />

                                                                                                <span style="display: inline;">
                                                                                                    <span style="display: inline;">' . 
                                                                                                        $row->ord_shipping_phone_number . '
                                                                                                    </span>
                                                                                                </span>
                                                                                                <br />
                                                                                                <span style="display: inline;"><b>Location:</b> ' .
                                                                                                    $row->ord_shipping_street_address . ', ' . $row->shipping_region_name . ', ' . $row->shipping_country_name . '
                                                                                                </span>
                                                                                            </td>
                                                                                            <td style="padding-top: 15px;" valign="top" width="50%">
                                                                                                <span style="color: #333333; font-weight: bold;">
                                                                                                    Delivery Info
                                                                                                </span>';

                                                                                                if ($row->ord_shipping_method == 'Delivery') {
                                                                                                    $email_message = $email_message . '<br /><span style="display: inline;"><b>Shipping Zone:</b> ' . $row->shipping_zone_name . '</span>';
                                                                                                    if ($row->ord_shipping_method == 0){
                                                                                                        $email_message = $email_message . '<br /><span style="display: inline;"><b>Shipping Fee:</b> Free</span>';
                                                                                                    }else{
                                                                                                        $email_message = $email_message . '<br /><span style="display: inline;"><b>Shipping Fee:</b> ' . $default_currency . ' ' . number_format($row->ord_shipping_total,2) . '</span>';
                                                                                                    }
                                                                                                } elseif ($row->ord_shipping_method == 'Pickup') {
                                                                                                    $email_message = $email_message . '<br /><span style="display: inline;"><b>Pickup Location:</b> ' . $row->pickup_location_name . ', <br/>' . $row->pickup_location_address . '</span>';
                                                                                                    if ($row->close_to != ''){
                                                                                                       $email_message = $email_message . '<br /><span style="display: inline;"><b>Close To:</b> ' . $row->close_to . '</span>'; 
                                                                                                    }
                                                                                                    $email_message = $email_message . '<br /><span style="display: inline;"><b>Opening Hours:</b> ' . $row->opening_hours . '</span>';
                                                                                                    $email_message = $email_message . '<br /><span style="display: inline;"><b>Pickup Period:</b> ' . $row->pickup_period . '</span>';
                                                                                                    $email_message = $email_message . '<br /><span style="display: inline;"><b>Shipping Fee:</b> ' . $default_currency . ' ' . number_format($row->ord_shipping_total,2) . '</span>';
                                                                                                } 

                                                                                                $email_message = $email_message . '</td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <td><span style="display: inline;"> </span></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td style="padding-top: 15px; padding-right: 10px;" valign="top" width="50%">
                                                                                                <span style="display: inline;"> </span>
                                                                                            </td>
                                                                                            <td style="padding-top: 15px;" valign="top" width="50%">
                                                                                                <span style="display: inline;"> </span>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>

                                                                                <span style="display: inline;">
                                                                                    <table
                                                                                        align="center"
                                                                                        border="0"
                                                                                        cellpadding="0"
                                                                                        cellspacing="0"
                                                                                        style="clear: both; color: #666666 !important; font-family: arial, helvetica, sans-serif; font-size: 11px;"
                                                                                        width="100%"
                                                                                    >
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td style="border: 1px solid #ccc; border-right: none; border-left: none; padding: 5px 10px 5px 10px !important; color: #333333 !important;" width="40%">
                                                                                                    Description
                                                                                                </td>
                                                                                                <td
                                                                                                    align="right"
                                                                                                    style="border: 1px solid #ccc; border-right: none; border-left: none; padding: 5px 10px 5px 10px !important; color: #333333 !important;"
                                                                                                    width="20%"
                                                                                                >
                                                                                                    Unit price
                                                                                                </td>
                                                                                                <td
                                                                                                    align="right"
                                                                                                    style="border: 1px solid #ccc; border-right: none; border-left: none; padding: 5px 10px 5px 10px !important; color: #333333 !important;"
                                                                                                    width="15%"
                                                                                                >
                                                                                                    Qty
                                                                                                </td>
                                                                                                <td
                                                                                                    align="right"
                                                                                                    style="border: 1px solid #ccc; border-right: none; border-left: none; padding: 5px 10px 5px 10px !important; color: #333333 !important;"
                                                                                                    width="25%"
                                                                                                >
                                                                                                    Amount
                                                                                                </td>
                                                                                            </tr>';
                                                                                            foreach ($order_details as $row2) {
                                                                                                $email_message = $email_message . '<tr>
                                                                                                    <td style="padding: 10px;" width="40%">' .
                                                                                                        $row2->ord_det_item_name . '<br><b>SKU Code:</b>' . $row2->ord_det_product_sku_code . '
                                                                                                    </td>
                                                                                                    <td align="right" style="padding: 10px;" width="20%">
                                                                                                        <span style="display: inline;">' .
                                                                                                            $default_currency . ' ' . number_format($row2->ord_det_price,2) . '
                                                                                                        </span>
                                                                                                    </td>
                                                                                                    <td align="right" style="padding: 10px;" width="15%">' .
                                                                                                        number_format($row2->ord_det_quantity,0) . '
                                                                                                    </td>
                                                                                                    <td align="right" style="padding: 10px;" width="25%">
                                                                                                        <span style="display: inline;">' .
                                                                                                            $default_currency . ' ' . number_format($row2->ord_det_price_total,2) . '
                                                                                                        </span>
                                                                                                    </td>
                                                                                                </tr>';
                                                                                            }
                                                                                            
                                                                                        $email_message = $email_message . '</tbody>
                                                                                    </table>
                                                                                </span>

                                                                                <table
                                                                                    border="0"
                                                                                    cellpadding="0"
                                                                                    cellspacing="0"
                                                                                    style="border-top: 1px solid #ccc; border-bottom: 1px solid #ccc; clear: both; color: #666666 !important; font-family: arial, helvetica, sans-serif; font-size: 11px;"
                                                                                    width="100%"
                                                                                >
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td><span style="display: inline;"> </span></td>
                                                                                        </tr>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <table
                                                                                                    align="right"
                                                                                                    border="0"
                                                                                                    cellpadding="0"
                                                                                                    cellspacing="0"
                                                                                                    style="color: #666666 !important; font-family: arial, helvetica, sans-serif; font-size: 11px; margin-top: 20px; clear: both; width: 100%;"
                                                                                                >
                                                                                                    <tbody>
                                                                                                        <tr>
                                                                                                            <td style="width: 75%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <strong>
                                                                                                                    No. of Items
                                                                                                                </strong>
                                                                                                            </td>
                                                                                                            <td style="width: 25%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <span style="display: inline;">' . 
                                                                                                                    number_format($row->ord_total_items,0) . '
                                                                                                                </span>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td style="width: 75%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <strong>
                                                                                                                    Subtotal
                                                                                                                </strong>
                                                                                                            </td>
                                                                                                            <td style="width: 25%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <span style="display: inline;">' .
                                                                                                                    $default_currency . ' ' . number_format($row->ord_item_summary_total,2) . '
                                                                                                                </span>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td style="width: 75%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <strong>
                                                                                                                    Tax
                                                                                                                </strong>
                                                                                                            </td>
                                                                                                            <td style="width: 25%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <span style="display: inline;">' .
                                                                                                                    $default_currency . ' ' . number_format($row->ord_tax_total,2) . '
                                                                                                                </span>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td style="width: 75%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <strong>
                                                                                                                    Shipping
                                                                                                                </strong>
                                                                                                            </td>
                                                                                                            <td style="width: 25%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <span style="display: inline;">' .
                                                                                                                    $default_currency . ' ' . number_format($row->ord_shipping_total,2) . '
                                                                                                                </span>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td style="width: 75%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <strong>
                                                                                                                    Discount
                                                                                                                </strong>
                                                                                                            </td>
                                                                                                            <td style="width: 25%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <span style="display: inline;">' .
                                                                                                                    $default_currency . ' ' . number_format($row->ord_savings_total,2) . '
                                                                                                                </span>
                                                                                                            </td>
                                                                                                        </tr>
                                                                                                        <tr>
                                                                                                            <td style="width: 75%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <span style="color: #333333 !important; font-weight: bold;">
                                                                                                                    Total
                                                                                                                </span>
                                                                                                            </td>
                                                                                                            <td style="width: 25%; text-align: right; padding: 0 10px 0 0;">
                                                                                                                <span style="display: inline;">' .
                                                                                                                    $default_currency . ' ' . number_format($row->ord_total,2) . '
                                                                                                                </span>
                                                                                                            </td>
                                                                                                        </tr>

                                                                                                        <tr>
                                                                                                            <td><span style="display: inline;"> </span></td>
                                                                                                        </tr>
                                                                                                    </tbody>
                                                                                                </table>
                                                                                            </td>
                                                                                        </tr>

                                                                                        <tr>
                                                                                            <td style="color: #757575; padding-bottom: 20px; padding-left: 10px;">
                                                                                                <br />
                                                                                                <span style="color: #333333; font-weight: bold;">
                                                                                                    Payments
                                                                                                </span>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                                <span style="display: inline;">
                                                                                    <table
                                                                                        align="center"
                                                                                        border="0"
                                                                                        cellpadding="0"
                                                                                        cellspacing="0"
                                                                                        style="clear: both; color: #666666 !important; font-family: arial, helvetica, sans-serif; font-size: 11px;"
                                                                                        width="100%"
                                                                                    >
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td style="border: 1px solid #ccc; border-right: none; border-left: none; padding: 5px 10px 5px 10px !important; color: #333333 !important;" width="25%">
                                                                                                    Receipt #
                                                                                                </td>
                                                                                                <td
                                                                                                    align="right"
                                                                                                    style="border: 1px solid #ccc; border-right: none; border-left: none; padding: 5px 10px 5px 10px !important; color: #333333 !important;"
                                                                                                    width="25%"
                                                                                                >
                                                                                                    Reference #
                                                                                                </td>
                                                                                                <td
                                                                                                    align="right"
                                                                                                    style="border: 1px solid #ccc; border-right: none; border-left: none; padding: 5px 10px 5px 10px !important; color: #333333 !important;"
                                                                                                    width="25%"
                                                                                                >
                                                                                                    Amount
                                                                                                </td>
                                                                                                <td
                                                                                                    align="right"
                                                                                                    style="border: 1px solid #ccc; border-right: none; border-left: none; padding: 5px 10px 5px 10px !important; color: #333333 !important;"
                                                                                                    width="25%"
                                                                                                >
                                                                                                    Paid On
                                                                                                </td>
                                                                                            </tr>';

                                                                                            foreach ($paybill_payments as $row2){
                                                                                                $email_message = $email_message . '<tr>
                                                                                                    <td style="padding: 10px;" width="25%">MPE-' .
                                                                                                        $row2->paybill_payment_id . '
                                                                                                    </td>
                                                                                                    <td align="right" style="padding: 10px;" width="25%">
                                                                                                        <span style="display: inline;">' .
                                                                                                            $row2->transaction_id . '
                                                                                                        </span>
                                                                                                    </td>
                                                                                                    <td align="right" style="padding: 10px;" width="25%">' .
                                                                                                        $default_currency . ' ' . number_format($row2->transaction_amount,2) . '
                                                                                                    </td>
                                                                                                    <td align="right" style="padding: 10px;" width="25%">
                                                                                                        <span style="display: inline;">' .
                                                                                                            date('M d, Y g:i A', strtotime($row2->transaction_time)) . '
                                                                                                        </span>
                                                                                                    </td>
                                                                                                </tr>';
                                                                                            }

                                                                                            foreach ($pesapal_payments as $row2){
                                                                                                $email_message = $email_message . '<tr>
                                                                                                    <td style="padding: 10px;" width="25%">PES-' .
                                                                                                        $row2->pesapal_payment_id . '
                                                                                                    </td>
                                                                                                    <td align="right" style="padding: 10px;" width="25%">
                                                                                                        <span style="display: inline;">
                                                                                                            <br />
                                                                                                        </span>
                                                                                                    </td>
                                                                                                    <td align="right" style="padding: 10px;" width="25%">' .
                                                                                                        $default_currency . ' ' . number_format($row2->transaction_amount,2) . '
                                                                                                    </td>
                                                                                                    <td align="right" style="padding: 10px;" width="25%">
                                                                                                        <span style="display: inline;">' .
                                                                                                            date('M d, Y g:i A', strtotime($row2->created_on)) . '
                                                                                                        </span>
                                                                                                    </td>
                                                                                                </tr>';
                                                                                            }

                                                                                            $email_message = $email_message . '</tbody>
                                                                                    </table>
                                                                                </span>

                                                                                <br />
                                                                                <span style="clear: left; font-weight: bold; color: #333333;">Issues with this Order?</span><br />
                                                                                <span style="display: inline;">You have 90 days from the date of the order to open a dispute with us.</span>
                                                                                
                                                                                <br />
                                                                                <br />
                                                                                <span style="font-size: 11px; color: #333;">
                                                                                    Please do not reply to this email. This mailbox is not monitored and you will not receive a response.
                                                                                    <span style="display: inline;">For assistance, please send an email to <strong>info@bethanyhouse.co.ke</strong> or call via <strong>+254 727 891989</strong>.</span>
                                                                                </span>
                                                                                <br />
                                                                            </div>
                                                                            <table>
                                                                                <tbody>
                                                                                    <tr>
                                                                                        <td></td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                            <span style="font-weight: bold; color: #444;"> </span>
                                                                            <span> </span>
                                                                        </td>
                                                                        <td width="20" align="center" valign="top">&nbsp;</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                                <tbody>
                                    <tr>
                                        <td align="center" width="600" valign="top">
                                            <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                <tbody>
                                                    <tr>
                                                        <td align="center" valign="top" bgcolor="#f2f2f2">
                                                            <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                                <tbody>
                                                                    <tr valign="bottom">
                                                                        <td width="20" align="center" valign="top">&nbsp;</td>
                                                                        <td>
                                                                            <span>
                                                                                <table
                                                                                    border="0"
                                                                                    cellpadding="0"
                                                                                    cellspacing="0"
                                                                                    id="m_-3412486042196783110emailFooter"
                                                                                    style="padding-top: 20px; font: 12px Arial, Verdana, Helvetica, sans-serif; color: #292929;"
                                                                                    width="100%"
                                                                                >
                                                                                    <tbody>
                                                                                        <tr>
                                                                                            <td>
                                                                                                <p style="font-size: 11px;">Copyright © ' . date('Y') . ' Bethany House. All rights reserved.</p>
                                                                                                <p style="font-size: 11px;">
                                                                                                    We would like to hear from you. For any questions, suggestions or comments please contact us at: info@bethanyhouse.co.ke.
                                                                                                </p>
                                                                                                <p style="font-size: 11px;">
                                                                                                    Please note that product prices and availability are subject to change. Prices and availability were accurate at the time this email was sent however, they may differ from those you see when you visit https://bethanyhouse.co.ke.
                                                                                                </p>
                                                                                            </td>
                                                                                        </tr>
                                                                                    </tbody>
                                                                                </table>
                                                                            </span>
                                                                        </td>
                                                                        <td width="20" align="center" valign="top">&nbsp;</td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                        <td bgcolor="#f2f2f2" style="font-size: 0px;">&nbsp;</td>
                    </tr>
                </tbody>
            </table>';
            
            $plaintext = $email_message;
            $plaintext = strip_tags( stripslashes( $plaintext ), '<p><br /><br><h2><h3><h1><h4><span>' );
            $plaintext = str_replace( array( '<p>', '<br />', '<br>', '<h1>', '<h2>', '<h3>', '<h4>', '<span>' ), PHP_EOL, $plaintext );
            $plaintext = str_replace( array( '</p>', '</h1>', '</h2>', '</h3>', '</h4>', '</span>' ), '', $plaintext );
            $plaintext = html_entity_decode( stripslashes( $plaintext ) );
        
            
            $mail->MsgHTML( stripslashes( $email_message ) ); 

            $attachment = $this->generate_order_pdf($row->ord_order_number);
            $mail->AddStringAttachment($attachment, 'Bethany House Order-'.$row->ord_order_number.'.pdf', 'base64', 'application/pdf');
            
            $mail->AltBody = $plaintext;
            $mail->AddAddress($email_to, "");
        
            if( !$mail->Send() ){
                //$arr_return = array('res' => TRUE,'dt' => 'Not Sent successfully.');
            }else{
                $data = array('ord_customer_email_sent'=> 1);           
                $this->db->where(array('ord_order_number' => $row->ord_order_number));
                $this->db->update('order_summary', $data);

                //$arr_return = array('res' => TRUE,'dt' => 'Sent successfully. An Email has been sent to you with your New Password details.');
            }
        }

    }

    //ORDER DISPATCH EMAILS
    function send_order_dispatch_emails(){
        $this->db->select("os.*, sc.country_name AS 'shipping_country_name', sr.region_name as 'shipping_region_name', pl.pickup_location_name, pl.pickup_location_address, pl.close_to, pl.pickup_period, pl.opening_hours, sz.shipping_zone_name, c.first_name, c.last_name, c.phone_number, c.email_address");
        $this->db->from('order_summary os');
        $this->db->join('customers c', 'c.customer_id = os.ord_customer_id', 'left outer');
        $this->db->join('countries sc', 'sc.country_id = os.ord_shipping_country_id', 'left outer');
        $this->db->join('regions sr', 'sr.region_id = os.ord_shipping_region_id', 'left outer');
        $this->db->join('pickup_locations pl', 'pl.pickup_location_id = os.ord_pickup_location_id', 'left outer');
        $this->db->join('shipping_zones sz', 'sz.shipping_zone_id = os.ord_shipping_zone_id', 'left outer');

        $this->db->where( array('os.ord_order_status' => 2, 'os.ord_dispatch_email_sent' => 0));
        $orders = $this->db->get()->result();

        $mail_host = '';
        $mail_port = 465;
        $mail_username = '';
        $mail_password = '';
        $mail_sender = '';
        $mail_sender_name = '';
        $mail_use_ssl = 1;

        $defaul_email_address = $this->email_accounts_model->get_default_email_account();
        foreach ($defaul_email_address as $row) {
            $mail_host = $row->mail_server_name;
            $mail_port = $row->mail_server_port;
            $mail_username = $row->user_name;
            $mail_password = $row->password;
            $mail_sender = $row->sender_email_address;
            $mail_sender_name = $row->sender_name;
            $mail_use_ssl = $row->use_ssl;
        }

        foreach($orders as $row){

            $mail          = new PHPMailer();
            $mail->IsSMTP();
            if ($mail_use_ssl == 1){
                $mail->SMTPSecure = 'ssl';
                $mail->SMTPAuth   = true;
            }
            $mail->Host       = $mail_host;
            $mail->Port       = $mail_port;
            $mail->Username   = $mail_username;
            $mail->Password   = $mail_password;
            
            $mail->SetFrom($mail_sender, $mail_sender_name);
            $email_to = $row->email_address; 

            $mail->Subject = 'Your Bethany House Order Has Been Dispatched! - Order No: ' . $row->ord_order_number;
           

            $email_message = "Hello ". $row->first_name . ", <br /><br />Your Bethany House Order No " . $row->ord_order_number . " has been dispatched.<br/><br/>";

            $email_message .= '
                <table cellpadding="0" cellspacing="0" border="0" width="100%">
                    <tbody>
                        <tr>
                            <td style="padding-top: 15px; padding-right: 10px;" valign="top" width="50%">
                                <span style="color: #333333; font-weight: bold;">
                                    Billing Info
                                </span>
                                <br>

                                <span style="display: inline;">' .
                                    $row->ord_shipping_first_name . ' ' . $row->ord_shipping_last_name . '
                                </span>
                                <br>

                                <a href="mailto:' . $row->ord_shipping_email_address . '" target="_blank">' . $row->ord_shipping_email_address . '</a>
                                <br>

                                <span style="display: inline;">
                                    <span style="display: inline;">' . 
                                        $row->ord_shipping_phone_number . '
                                    </span>
                                </span>
                                <br>
                                <span style="display: inline;"><b>Location:</b> ' .
                                    $row->ord_shipping_street_address . ', ' . $row->shipping_region_name . ', ' . $row->shipping_country_name . '
                                </span>
                            </td>
                            <td style="padding-top: 15px;" valign="top" width="50%">
                                <span style="color: #333333; font-weight: bold;">
                                    Delivery Info
                                </span>';

                                if ($row->ord_shipping_method == 'Delivery') {
                                    $email_message = $email_message . '<br><span style="display: inline;"><b>Shipping Zone:</b> ' . $row->shipping_zone_name . '</span>';
                                } elseif ($row->ord_shipping_method == 'Pickup') {
                                    $email_message = $email_message . '<br><span style="display: inline;"><b>Pickup Location:</b> ' . $row->pickup_location_name . ', <br>' . $row->pickup_location_address . '</span>';
                                    if ($row->close_to != ''){
                                       $email_message = $email_message . '<br><span style="display: inline;"><b>Close To:</b> ' . $row->close_to . '</span>'; 
                                    }
                                    $email_message = $email_message . '<br><span style="display: inline;"><b>Opening Hours:</b> ' . $row->opening_hours . '</span>';
                                    $email_message = $email_message . '<br><span style="display: inline;"><b>Pickup Period:</b> ' . $row->pickup_period . '</span>';
                                } 

                                $email_message = $email_message . '</td>
                        </tr>
                    </tbody>
                </table>';

            $email_message .= "Regards,<br />";
            $email_message .= "Sales Department<br />";
            $email_message .= "Bethany House<br />";
            $email_message .= "_________________________________________________<br />";
            $email_message .= "Note: This is a system generated mail. Please do NOT reply to it.";

            
            $message = file_get_contents(base_url().'email_temp/emheader');
            $message .= file_get_contents(base_url().'email_temp/embody');
            $message .= file_get_contents(base_url().'email_temp/emfooter');
            $logo = base_url().'assets/fe/img/logo.png';
            
            $replacements = array(
                '({logo})' => $logo, 
                '({message_subject})' => 'Your Bethany House Order Has Been Dispatched! - Order No: ' . $row->ord_order_number, 
                '({message_body})' => stripslashes( $email_message ) 
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
            }else{
                $data = array('ord_dispatch_email_sent'=> 1);           
                $this->db->where(array('ord_order_number' => $row->ord_order_number));
                $this->db->update('order_summary', $data);
            }
        }

    }



    function debug_to_console($str, $context = 'Debug in Console') {

        // Buffering to solve problems frameworks, like header() in this and not a solid return.
        ob_start();

        $output  = 'console.info(\'' . $context . ':\');';
        $output .= 'console.log(' . $str . ');';
        $output  = sprintf('<script>%s</script>', $output);

        echo $output;
    }


}