<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Account_model extends CI_Model{
    public function __construct(){
        parent::__construct();
        // $this->load->library('flexi_cart');
        // $this->load->library('flexi_cart_admin');
        $this->load->library('phpmailer');
        $this->load->model('be/email_accounts_model');
    }

    function submit_login() {
        $password = (string) $this->input->post('login_password');
        // Fetch by email only, then verify the password in PHP: bcrypt hashes carry a
        // random salt and cannot be matched inside an SQL WHERE. bethany_verify() also
        // accepts legacy md5 hashes so existing customers keep logging in.
        $this->db->where('email_address', $this->input->post('login_email_address'));
        $this->db->from('customers');

        $query = $this->db->get();

        if($query->num_rows() > 0 && bethany_verify($password, $query->row()->password)){
            foreach ($query->result() as $row){
                // Transparently upgrade a legacy md5 hash to bcrypt on successful login.
                if (bethany_needs_rehash($row->password)) {
                    $this->db->where('customer_id', $row->customer_id)
                             ->update('customers', array('password' => bethany_hash($password)));
                }
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
            $arr_return = array('res' => true,'dt' => "<i class='icon-checkmark-circle'></i> Successful. Please wait while you're being logged in...");
        }else{
            $arr_return = array('res' => false,'dt' => '<i class="icon-cross-circle"></i> Login not successful. Please check your entries and try again.');
        }
        return $arr_return;
    }
    function validate_register_customer(){

        $email_address = $this->input->post('register_email_address');
        $phone_number = $this->input->post('register_phone_number');

        $msg = '';
        $msg2 = '';

        //EMAIL ADDRESS
        $this->db->where(array('email_address' => $email_address, 'is_deleted' => 0));
        $query = $this->db->get('customers');

        if ($query->num_rows() > 0){
            $msg = '<b><i class="icon-cross-circle"></i> Duplicate Email Address:</b> The Email Address you entered is being used by another account.<br>';
        }else{
            //PHONE NUMBER
            $this->db->where(array('phone_number' => $phone_number, 'phone_number !=' => '', 'is_deleted' => 0));
            $query = $this->db->get('customers');

            if ($query->num_rows() > 0){
                $msg = '<b><i class="icon-cross-circle"></i> Duplicate Phone Number:</b> The Phone Number you entered is being used by another Account.<br>';
            }
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
            $this->session->set_userdata('customer_phone_number', $this->input->post('register_phone_number'));
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

            $arr_return = array('res' => true,'dt' => '<i class="icon-checkmark-circle"></i> Registration successful. Please wait while you are being logged in.');
        }else{
            $arr_return = array('res' => false,'dt' => '<i class="icon-cross-circle"></i> Registration not successful. Please try again.');
        }
        return $arr_return;     
    }

    function get_account() {
        $this->db->select("c.*, bc.country_name AS 'billing_country_name', br.region_name as 'billing_region_name', sc.country_name AS 'shipping_country_name', sr.region_name as 'shipping_region_name'");
        $this->db->from('customers c');
        $this->db->join('countries bc', 'bc.country_id = c.billing_country_id', 'left outer');
        $this->db->join('regions br', 'br.region_id = c.billing_region_id', 'left outer');
        $this->db->join('countries sc', 'sc.country_id = c.shipping_country_id', 'left outer');
        $this->db->join('regions sr', 'sr.region_id = c.shipping_region_id', 'left outer');
        $this->db->where( array('c.customer_id'=>$this->session->userdata('customer_id')));
        return $this->db->get()->result();
    }

    function get_recent_orders(){
        $this->db->from('order_summary');
        $this->db->where( array('ord_customer_id' => $this->session->userdata('customer_id')));
        $this->db->order_by("ord_order_number","desc");
        $this->db->limit(5); 
        
        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        $arr_return = array('records' => $records, 'record_count' => $record_count);

        return $arr_return;
    }

    function get_orders(){
        $this->db->from('order_summary');
        $this->db->where( array('ord_customer_id' => $this->session->userdata('customer_id')));
        $this->db->order_by("ord_order_number","desc");
        
        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        $arr_return = array('records' => $records, 'record_count' => $record_count);

        return $arr_return;
    }

    function favorite_product($product_id){
        $customer_id = $this->session->userdata('customer_id');

        $this->db->from('favorite_products');
        $this->db->where( array('product_id' => $product_id, 'customer_id' => $customer_id, 'is_deleted' => 0));
        $query = $this->db->get();

        if ($query->num_rows() > 0){
            $arr_return = array('res' => true,'dt' => 'Favorited Successfully');
        }else{
            $data = array(
                'product_id' => $product_id,
                'customer_id' => $customer_id
            );
            $insert = $this->db->insert('favorite_products', $data);
            if ($insert){
                $arr_return = array('res' => true,'dt' => 'Favorited successfully.');
            }else{
                $arr_return = array('res' => false,'dt' => 'Could not favorite product successfully. Please try again.');
            }
        }
        return $arr_return;
    }

    function get_favorite_products(){
        $this->db->select("p.*, b.brand_name, u.unit_code, u.unit_name, fp.favorite_product_id");
        $this->db->from('products p');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');
        $this->db->join('favorite_products fp', 'fp.product_id = p.product_id');

        $this->db->where( array('p.is_deleted'=>0, 'p.is_online'=>1));
        $this->db->where( array('fp.customer_id' => $this->session->userdata('customer_id')));

        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        $arr_return = array('records' => $records, 'record_count' => $record_count);

        return $arr_return;
    }

    function remove_favorite_product($favorite_product_id){
       $this->db->where('favorite_product_id', $favorite_product_id);
       if ($this->db->delete('favorite_products')) {
            $arr_return = array('res' => true,'dt' => 'Removed successfully.');
       } else {
            $arr_return = array('res' => false,'dt' => 'Could not remove favorite product successfully. Please try again.');
       }  

       return  $arr_return;             

    }

    function validate_update_account_duplicate($customer_id){

        $email_address = $this->input->post('email_address');
        $phone_number = $this->input->post('phone_number');

        $msg = '';
        $msg2 = '';

        //EMAIL ADDRESS
        $this->db->where('customer_id !=', $customer_id);
        $this->db->where('email_address', $email_address);
        $query = $this->db->get('customers');
        if ($query->num_rows() > 0){
            $msg = '<i class="icon-cross-circle"></i> Duplicate Email: The Email Address you entered is already being used by another user.<br>';
        }

        //PHONE NUMBER
        $this->db->where('customer_id !=', $customer_id);
        $this->db->where('phone_number', $phone_number);
        $query = $this->db->get('customers');
        if ($query->num_rows() > 0){
            $msg = '<i class="icon-cross-circle "></i> Duplicate Phone No: The Phone Number you entered is already being used by another user.<br>';
        }

        if ($msg == $msg2){
            $arr_return = array('res' => true,'dt' => '');
        }else{
            $arr_return = array('res' => false,'dt' => $msg);
        }

        return $arr_return;
    }

    function update_account($data,$customer_id){
        $this->db->where(array('customer_id' => $customer_id));
        $update = $this->db->update('customers', $data);
        if ($update){

            $this->session->set_userdata('customer_first_name', $this->input->post('first_name'));
            $this->session->set_userdata('customer_last_name', $this->input->post('last_name'));
            $this->session->set_userdata('customer_email_address', $this->input->post('email_address'));
            $this->session->set_userdata('customer_phone_number', $this->input->post('phone_number'));

            $arr_return = array('res' => true,'dt' => '<i class="icon-checkmark-circle"></i> Account updated successfully.');
        }else{
            $arr_return = array('res' => false,'dt' => '<i class="icon-cross-circle"></i> Could not update Account successfully. Please try again.');
        }
        return $arr_return;
    }

    function old_password_valid($old_password, $customer_id){
        $this->db->where('customer_id',$customer_id);
        $query = $this->db->get('customers');
        if ($query->num_rows() > 0){
            return bethany_verify($old_password, $query->row()->password);
        }else{
            return false;
        }
    }

    function get_account_by_customer_id($customer_id){
        $this->db->from('customers');
        $this->db->where( array('customer_id'=>$customer_id));
        return $this->db->get()->result();
    }

    function update_password($data,$customer_id){

        $this->db->where(array('customer_id' => $customer_id));
        $update = $this->db->update('customers', $data);

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

        if ($update){
            try {
                $first_name = '';
                $email_address = '';
                $password = $this->input->post('new_password');

                $customer = $this->get_account_by_customer_id($customer_id);

                foreach ($customer as $row){
                    $first_name = $row->first_name;
                    $email_address = $row->email_address;
                }

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
                $email_to = $email_address; 
                 
                $mail->Subject = 'Password Change - ' . date('d-m-Y');

                $email_message = "Hello ". $first_name . ", <br /><br />Please find your current password for Bethany House below.<br /><br />"; 
                $email_message .= "<strong>Login Email Address:</strong> ".$email_address."<br /><br />"; 
                $email_message .= "<strong>New Password:</strong> ".$password."<br /><br /><br />"; 
                $email_message .= "You can use the following link to login.<br /><br />"; 

                $email_message .= "<b><a href='".base_url()."account/login' style='color:#09274c !important'>Bethany House Login</a></b><br /><br />";

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
                    '({message_subject})' => 'Password Change - ' . date('d-m-Y'), 
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
                    $arr_return = array('res' => false,'dt' => '<i class="icon-checkmark-circle"></i> Password changed successfully.');
                }else{
                    $arr_return = array('res' => true,'dt' => '<i class="icon-checkmark-circle"></i> Password changed successfully. An Email has been sent to you with your New Password details.');
                }
            } catch (phpmailerException $e) {
                $arr_return = array('res' => false,'dt' => 'Password changed successfully but with an error sending Email. <br> ' . $e->errorMessage());
            }
        }else{
            $arr_return = array('res' => false,'dt' => '<i class="icon-cross-circle"></i> Could not change Password successfully. Please try again.');
        }
        return $arr_return;

    }

    function update_address($data,$customer_id) {
        $this->db->where(array('customer_id' => $customer_id));
        $update = $this->db->update('customers', $data);
        if ($update){
            $arr_return = array('res' => true,'dt' => '<i class="icon-checkmark-circle"></i> Shipping address updated successfully.');
        }else{
            $arr_return = array('res' => false,'dt' => '<i class="icon-cross-circle"></i> Could not update shipping address successfully. Please try again.');
        }
        return $arr_return;

    }

    function get_order($order_number) {
        $this->db->select("os.*, sc.country_name AS 'shipping_country_name', sr.region_name as 'shipping_region_name', pl.pickup_location_name, pl.pickup_location_address, pl.close_to, pl.pickup_period, pl.opening_hours, sz.shipping_zone_name, sz.shipping_method, sz.shipping_fee");
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

    function cancel_order($order_number) {
        $data = array(
            'ord_order_status' => 4
        );
        $this->db->where(array('ord_order_number' => $order_number));
        $update = $this->db->update('order_summary', $data);
        if ($update){

            //NOTIFICATION
            $data = array(
                'notification_type' => 'Online Order Cancellation',
                'notification_ref_id' => $order_number,
                'notification_details' => '<b>Order Cancellation:</b> Order #: <b>' . $order_number . '</b> has been cancelled by the user. If you wish to do a follow up email on this please <a href="#">Click Here</a>',
                'notification_ref_link' => 'be/sales/online_order/' . $order_number
            );
            $this->db->insert('notifications',$data);


            $arr_return = array('res' => true,'dt' => '<i class="icon-checkmark-circle"></i> Order cancelled successfully.');
        }else{
            $arr_return = array('res' => false,'dt' => '<i class="icon-cross-circle"></i> Could not cancel order successfully. Please try again.');
        }
        return $arr_return;

    }

    function restore_order($order_number) {
        $data = array(
            'ord_order_status' => 0
        );
        $this->db->where(array('ord_order_number' => $order_number));
        $update = $this->db->update('order_summary', $data);
        if ($update){
            $arr_return = array('res' => true,'dt' => '<i class="icon-checkmark-circle"></i> Order restored successfully.');
        }else{
            $arr_return = array('res' => false,'dt' => '<i class="icon-cross-circle"></i> Could not restore order successfully. Please try again.');
        }
        return $arr_return;
    }


    function send_customer_registration_emails(){
        $this->db->select('*');
        $this->db->from('customers');        
        $this->db->where( array('registration_email_sent'=>0));
        $customers = $this->db->get()->result();

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

        foreach($customers as $row){

            $first_name = $row->first_name;
            $last_name = $row->last_name;
            $email_address = $row->email_address;

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
            $email_to = $email_address; 
             
            $mail->Subject = 'Registration Successful - Bethany House';

            $email_message = "Hello ". $first_name . ", <br /><br />Thank you for successfully creating your account at Bethany House.<br /><br />"; 
            $email_message .= "Bethany House offers a new approach in purchasing Communion wares, providing an accurate assembly of products which can be effortlessly and enjoyably browsed, explored and purchased online, and delivered to your door step.<br /><br />"; 
            $email_message .= "To log into your account, please click on the link provided below.<br /><br />";
            $email_message .= "<a href='".base_url()."account' style='color:#EE7202 !important'>Account Login</a><br /><br />";

            // if ($row->acc_temp_pass != ''){
            //     $email_message .= "We have also created a one time password for you. Kindly use the password below to log in and remember to change it immediately you log in:<br />";
            //     $email_message .= "<strong>Password:</strong> ".$row->acc_temp_pass."<br /><br />";
            // }

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
                '({message_subject})' => 'Registration Successful - Bethany House', 
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
                //$arr_return = array('res' => TRUE,'dt' => 'Not Sent successfully.');
            }else{
                $data = array('registration_email_sent'=> 1);           
                $this->db->where(array('customer_id' => $row->customer_id));
                $this->db->update('customers', $data);

                //$arr_return = array('res' => TRUE,'dt' => 'Sent successfully. An Email has been sent to you with your New Password details.');
            }
        }
    }

    function generate_password( $length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#";
        $password = substr( str_shuffle( $chars ), 0, $length );
        return $password;
    }

    function reset_password(){
        $email_address = $this->input->post('reset_email_address');

        $this->db->where('email_address', $email_address);
        $query = $this->db->get('customers');
        
        if ($query->num_rows() < 1){
            $arr_return = array('res' => FALSE,'dt' => 'No account is associated with the email address : ' . $email_address);
        }else{
            $result = $query->result();
            
            $is_active = 0;
            $first_name = '';
            $email_address = '';
            
            foreach ($result as $row){
                $is_active = $row->is_active;
                $first_name = $row->first_name;
                $email_address = $row->email_address;
            }
            
            if ($is_active == 0){
                $arr_return = array('res' => FALSE,'dt' => 'Sorry. The account associated with the email address ' . $email_address . ' has been suspended. Please contact the admin for more assistance.');
            }else{

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

                $new_user_password = $this->generate_password(8);

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

                $email_to = $email_address; 
                 
                $mail->Subject = 'Bethany House | Password Reset - ' . date('d-m-Y H:i:s');

                $email_message = "Dear " . $first_name . ",<br /><br />";
                $email_message .= "Your Bethany House account password has been reset. Your login credentials are as shown below:<br /><br />";
                $email_message .= "<strong>Email Address:</strong> ".$email_address. "<br /><br />"; 
                $email_message .= "<strong>New Password:</strong> ".$new_user_password."<br /><br /><br />";  

                $email_message .= "You can use the link below to login.<br /><br />"; 

                $email_message .= "<a href='".base_url()."account/login' style='color:#EE7202 !important'>Bethany House Account Login</a><br /><br />";

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
                    '({message_subject})' => 'Bethany House Password Reset', 
                    '({message_body})' => nl2br( stripslashes( $email_message ) )
                );
                $message = preg_replace( array_keys( $replacements ), array_values( $replacements ), $message );
                
                $plaintext = $message;
                $plaintext = strip_tags( stripslashes( $plaintext ), '<p><br><h2><h3><h1><h4>' );
                $plaintext = str_replace( array( '<p>', '<br />', '<br>', '<h1>', '<h2>', '<h3>', '<h4>' ), PHP_EOL, $plaintext );
                $plaintext = str_replace( array( '</p>', '</h1>', '</h2>', '</h3>', '</h4>' ), '', $plaintext );
                $plaintext = html_entity_decode( stripslashes( $plaintext ) );
            
                
                $mail->MsgHTML( stripslashes( $message ) ); 
                
                $mail->AltBody = $plaintext;
                $mail->AddAddress($email_to, "");
            
                if( !$mail->Send() ){
                    $arr_return = array('res' => FALSE,'dt' => 'There was an error sending your Password recovery Email. Please try again.<br>-> ' . $mail->ErrorInfo);
                }else{

                    $user_data = array(
                        'password' => bethany_hash($new_user_password)
                    );
                    $this->db->where(array('email_address'=>$email_address));
                    $this->db->update('customers', $user_data);


                    $arr_return = array('res' => TRUE,'dt' => 'Password reset successful. A new Password has been sent to your Email Address.');
                    
                }
            }           
        }
        return $arr_return;
    }


}