<?php

class Affiliates_model extends CI_Model {

	public function __construct(){
        parent::__construct();
        $this->load->library('phpmailer');
        $this->load->model('be/email_accounts_model');
        $this->load->model('be/email_notification_settings_model');
    }

	function generate_affiliate_code($length = 6) {
    	$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
       	$randomString .= $characters[rand(0, strlen($characters) - 1)];
    	}
    	return $randomString;
	}	
	function get_affiliate_code(){
		$affiliate_code = $this->generate_affiliate_code();
		$checktrue = $this->check_affiliate_code_exists($affiliate_code);
		while ($checktrue == true){
			$affiliate_code = $this->generate_affiliate_code();
			$checktrue = $this->check_affiliate_code_exists($affiliate_code);
		}
		return $affiliate_code;
	}
	function check_affiliate_code_exists($affiliate_code){
		$this->db->from('affiliates');
		$this->db->where( array('affiliate_code'=>$affiliate_code));
		$numrows = $this->db->get()->num_rows();
		if ($numrows > 0){
			return true;
		}else{
			return false;
		}
	}
	function get_existing_affiliate_code($affiliate_id){
		$affiliate_code = '';
		$this->db->from('affiliates');
		$this->db->where( array('affiliate_id'=>$affiliate_id));
		$result = $this->db->get()->result();
		foreach ($result as $r){
			$affiliate_code = $r->affiliate_code;
		}

		if ($affiliate_code == ''){
			$affiliate_code = $this->get_affiliate_code();
		}

		return $affiliate_code;
	}

    function get_affiliate_account(){
        $this->db->select("a.*, c.country_name, ap.affiliate_package_name, ap.affiliate_package_colour_code, ap.commission, ap.minimum_pay_out, ap.affiliate_package_features");
        $this->db->from('affiliates a');
        $this->db->join('countries c', 'c.country_id = a.country_id', 'left outer');
        $this->db->join('affiliate_packages ap', 'ap.affiliate_package_id = a.affiliate_package_id', 'left outer');
        $this->db->where( array('a.affiliate_id'=>$this->session->userdata('affiliate_id')));
        return $this->db->get()->result();
    }

    function get_affiliate_by_code($affiliate_code) {
        $this->db->select("a.*, c.country_name, ap.affiliate_package_name, ap.affiliate_package_colour_code, ap.commission, ap.minimum_pay_out, ap.affiliate_package_features");
        $this->db->from('affiliates a');
        $this->db->join('countries c', 'c.country_id = a.country_id', 'left outer');
        $this->db->join('affiliate_packages ap', 'ap.affiliate_package_id = a.affiliate_package_id', 'left outer');
        $this->db->where( array('a.affiliate_code'=>$affiliate_code));
        return $this->db->get()->result();
    }

    function get_affiliate($affiliate_id) {
        $this->db->select("a.*, c.country_name, ap.affiliate_package_name, ap.affiliate_package_colour_code, ap.commission, ap.minimum_pay_out, ap.affiliate_package_features");
        $this->db->from('affiliates a');
        $this->db->join('countries c', 'c.country_id = a.country_id', 'left outer');
        $this->db->join('affiliate_packages ap', 'ap.affiliate_package_id = a.affiliate_package_id', 'left outer');
        $this->db->where( array('a.affiliate_id' => $affiliate_id));
        return $this->db->get()->result();
    }

    function get_account_total_clicks(){
        $this->db->select('affiliate_clicks.*');
        $this->db->from('affiliate_clicks');
        $this->db->where( array('affiliate_id' => $this->session->userdata('affiliate_id')));
        return $this->db->count_all_results();
    }

    function get_account_total_referrals(){
        $this->db->select('affiliate_referrals.*');
        $this->db->from('affiliate_referrals');
        $this->db->where( array('affiliate_id' => $this->session->userdata('affiliate_id')));
        return $this->db->count_all_results();
    }

    function get_account_referrals(){
        $this->db->select('affiliate_referrals.*');
        $this->db->from('affiliate_referrals');
        $this->db->where( array('affiliate_id' => $this->session->userdata('affiliate_id')));
        return $this->db->get()->result();
    }

    function get_account_clicks() {
        $this->db->select('affiliate_clicks.*');
        $this->db->from('affiliate_clicks');
        $this->db->where( array('affiliate_id' => $this->session->userdata('affiliate_id')));
        return $this->db->get()->result();
    }

	function new_password($length = 9){
    	$characters = '@&$#+0123456789abcdefghiklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
       		$randomString .= $characters[rand(0, strlen($characters) - 1)];
    	}
    	return $randomString;
	}

	function validate_register_affiliate(){

        $email_address = $this->input->post('register_email_address');
        $phone_number = $this->input->post('register_phone_number');

        $msg = '';
        $msg2 = '';

        //EMAIL ADDRESS
        $this->db->where(array('email_address' => $email_address, 'is_deleted' => 0));
        $query = $this->db->get('affiliates');

        if ($query->num_rows() > 0){
            $msg = '<b><i class="icon-cross-circle"></i> Duplicate Email Address:</b> The Email Address you entered is being used by another account.<br>';
        }else{
            //PHONE NUMBER
            $this->db->where(array('phone_number' => $phone_number, 'phone_number !=' => '', 'is_deleted' => 0));
            $query = $this->db->get('affiliates');

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

	function submit_register($data, $affiliate_code) {
        $insert = $this->db->insert('affiliates', $data);
        $insert_id = $this->db->insert_id();

        if ($insert){

            //NOTIFICATION
            $data = array(
                'notification_type' => 'Affiliate Account Creation',
                'notification_ref_id' => $insert_id,
                'notification_details' => 'A new affiliate account has been created - Ref #: <b>' . $affiliate_code . '</b>, Name: <b>' .  $this->input->post('register_first_name') . ' ' . $this->input->post('register_last_name') . '</b>, Email: <b>' . $this->input->post('register_email_address') . '</b>',
                'notification_ref_link' => 'be/affiliates/manage/' . $insert_id
            );
            $this->db->insert('notifications',$data);

            $arr_return = array('res' => true,'dt' => '<i class="icon-checkmark-circle"></i> Your Affiliate Registration has been submitted successfully. Please wait while we review your application and get back to you.');
        }else{
            $arr_return = array('res' => false,'dt' => '<i class="icon-cross-circle"></i> Affiliate Registration not successful. Please try again.');
        }
        return $arr_return;     
    }

    function submit_login() {
        $password = md5($this->input->post('login_password'));
        $this->db->where('email_address', $this->input->post('login_email_address'));
        $this->db->where('password', $password);
        $this->db->from('affiliates');
    
        $query = $this->db->get();
        
        if($query->num_rows() > 0){

            $affiliate = $query->result();

            foreach ($affiliate as $row) {
                $is_verified = $row->is_verified;
                $affiliate_status = $row->affiliate_status;
            }

            if ($is_verified == 0) {
                $arr_return = array('res' => false,'dt' => '<i class="icon-cross-circle"></i> Login not successful. Please verify your email address first then try again.');
            } elseif ($affiliate_status == 2) {
                $arr_return = array('res' => false,'dt' => '<i class="icon-cross-circle"></i> Login not successful because your account has been suspended. For any clarifications please reach us on info@bethanyhouse.co.ke.');
            } elseif ($affiliate_status == 3) {
                $arr_return = array('res' => false,'dt' => '<i class="icon-cross-circle"></i> Login not successful because your account has been deleted. For any clarifications please reach us on info@bethanyhouse.co.ke.');
            } else {
                foreach ($affiliate as $row){
                    $this->session->set_userdata('bgs_affiliate_login_state', TRUE);
                    $this->session->set_userdata('affiliate_id', $row->affiliate_id);
                    $this->session->set_userdata('affiliate_email_address', $row->email_address);
                    $this->session->set_userdata('affiliate_phone_number', $row->phone_number);
                    $this->session->set_userdata('affiliate_first_name', $row->first_name);
                    $this->session->set_userdata('affiliate_last_name', $row->last_name);
                    $this->session->set_userdata('affiliate_code', $row->affiliate_code);

                    $data = array (             
                        'last_login' => date("Y-m-d H:i:s")
                    );          

                    $this->db->where('email_address', $row->email_address);
                    $this->db->update('affiliates', $data);
                }   
                $arr_return = array('res' => true,'dt' => "<i class='icon-checkmark-circle'></i> Successful. Please wait while you're being logged in...");
            }
        }else{
            $arr_return = array('res' => false,'dt' => '<i class="icon-cross-circle"></i> Login not successful. Please check your entries and try again.');
        }
        return $arr_return;
    }

    function old_password_valid($old_password, $affiliate_id){
        $this->db->where('password',md5($old_password));
        $this->db->where('affiliate_id',$affiliate_id);

        $query = $this->db->get('affiliates');
        if ($query->num_rows() > 0){
            return true;
        }else{
            return false;
        }
    }

    function update_password($data,$affiliate_id){

        $this->db->where(array('affiliate_id' => $affiliate_id));
        $update = $this->db->update('affiliates', $data);

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

                $affiliate = $this->get_affiliate($affiliate_id);

                foreach ($affiliate as $row){
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
                 
                $mail->Subject = 'Affiliate Password Change - ' . date('d-m-Y');

                $email_message = "Hello ". $first_name . ", <br /><br />Please find your current affiliate password for Bethany House below.<br /><br />"; 
                $email_message .= "<strong>Login Email Address:</strong> ".$email_address."<br /><br />"; 
                $email_message .= "<strong>New Password:</strong> ".$password."<br /><br /><br />"; 
                $email_message .= "You can use the following link to login.<br /><br />"; 

                $email_message .= "<b><a href='".base_url()."affiliates/login' style='color:#09274c !important'>Bethany House Affiliate Login</a></b><br /><br />";

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
                    '({message_subject})' => 'Affiliate Password Change - ' . date('d-m-Y'), 
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

    function send_affiliate_registration_emails(){

    	$this->db->select('*');
        $this->db->from('affiliates');        
        $this->db->where( array('affiliate_registration_email'=>0));
        $affiliates = $this->db->get()->result();

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

        foreach($affiliates as $row){

            $first_name = $row->first_name;
            $last_name = $row->last_name;
            $email_address = $row->email_address;
            $affiliate_code = $row->affiliate_code;

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
             
            $mail->Subject = 'Affiliate Registration Confirmation for ' . $first_name . ' ' . $last_name . ' - Bethany House';

			$email_message = "Hello ". $first_name . ", <br /><br />You have successfully created your Bethany House affiliate account. Please be patient as we process your application.<br/><br/>";
			$email_message .= "To confirm that you are not a robot, click on the following link or copy-paste it in your browser to verify this Email Address:<br /><br />"; 
			$email_message .= "<a href='".base_url()."affiliates/activate/".$affiliate_code."' style='color:#EE7202 !important'>Verify Account</a><br /><br />";

			// if ($row->acc_temp_pass != ''){
			// 	$email_message .= "We have also created a one time password for you. Kindly use the password below to log in and remember to change it immediately you log in:<br />";
			// 	$email_message .= "<strong>Password:</strong> ".$row->temp_pass."<br /><br />";
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
                '({message_subject})' => 'Affiliate Registration Confirmation for ' . $first_name . ' ' . $last_name . ' - Bethany House', 
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
                $data = array('affiliate_registration_email'=> 1);           
                $this->db->where(array('affiliate_id' => $row->affiliate_id));
                $this->db->update('affiliates', $data);

                //$arr_return = array('res' => TRUE,'dt' => 'Sent successfully. An Email has been sent to you with your New Password details.');
            }
        }
    }

    function send_admin_registration_emails(){
    	$this->db->select('*');
        $this->db->from('affiliates');        
        $this->db->where( array('admin_registration_email'=>0));
        $affiliates = $this->db->get()->result();

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

        foreach($affiliates as $row){

            $first_name = $row->first_name;
            $last_name = $row->last_name;
            $email_address = $row->email_address;
            $phone_number = $row->phone_number;
            $affiliate_code = $row->affiliate_code;

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
             
			$mail->Subject = 'New Affiliate Account Registered - #' . $affiliate_code;

			$email_message = "Hello Bethany House, <br /><br />A new affiliate account has been created on your website " .  base_url() . "<br /><br />"; 
			$email_message .= "The account details are as follows:<br /><br />"; 
			$email_message .= "First Name: " . $first_name . " <br />";
			$email_message .= "Last Name: " . $last_name . " <br />";
			$email_message .= "Email Address: " . $email_address . " <br />";
			$email_message .= "Phone Number: " . $phone_number . " <br />";
			$email_message .= "Affiliate Code: " . $affiliate_code . " <br /><br />";
			$email_message .= "Please log into your website backend to process this application. <br /><br />";

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
                '({message_subject})' => 'New Affiliate Account Registered - #' . $affiliate_code, 
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
            if ($cc_email_address != '') {
                $mail->addCC($cc_email_address, "");
            }
            if ($bcc_email_address != '') {
                $mail->addBCC($bcc_email_address, "");
            }
        
            if( !$mail->Send() ){
                //$arr_return = array('res' => TRUE,'dt' => 'Not Sent successfully.');
            }else{
                $data = array('admin_registration_email'=> 1);           
                $this->db->where(array('affiliate_id' => $row->affiliate_id));
                $this->db->update('affiliates', $data);

                //$arr_return = array('res' => TRUE,'dt' => 'Sent successfully. An Email has been sent to you with your New Password details.');
            }
        }
    }

    function send_affiliate_approval_emails(){
    	$this->db->select('*');
        $this->db->from('affiliates');        
        $this->db->where( array('affiliate_status'=>1, 'affiliate_approval_email'=>0));
        $affiliates = $this->db->get()->result();

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

        foreach($affiliates as $row){

            $first_name = $row->first_name;
            $last_name = $row->last_name;
            $email_address = $row->email_address;
            $affiliate_code = $row->affiliate_code;
            $temp_pass = $row->temp_pass;

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
             
            $mail->Subject = 'Affiliate Account Approved Successfully - Bethany House';

			$email_message = "Hello ". $first_name . ", <br /><br />Your Bethany House affiliate account has been approved successfully. You can now log into your account and start earning with us by sharing the link we have created for you.<br/><br/>";

			$email_message .= "<strong>Your Affiliate URL Link:</strong> <a href='". $row->short_url ."' style='color:#EE7202 !important'>" . $row->short_url . "</a><br /><br />";

			$email_message .= "We have also created a one-time password for you. Please remember to change it immediately you log in. Your login credentials are as below:<br /><br />";
			
			$email_message .= "<strong>Email:</strong> ".$row->email_address."<br />";
			$email_message .= "<strong>Password:</strong> ".$row->temp_pass."<br />";
			$email_message .= "<strong>Login URL:</strong> <a href='".base_url()."affiliates/login' style='color:#EE7202 !important'>Affiliate Account Login</a><br /><br />";

			if ($row->is_verified == 0){
				$email_message .= "Please note that you have to verify this email address first before you log in. You can click on the link below to verify:<br />";
				$email_message .= "<a href='".base_url()."affiliates/activate/".$affiliate_code."' style='color:#EE7202 !important'>Verify Account</a><br /><br />";
			}

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
                '({message_subject})' => 'Congratulations! Affiliate Account Approved - Bethany House', 
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
            }else{
                $data = array('affiliate_approval_email'=> 1);           
                $this->db->where(array('affiliate_id' => $row->affiliate_id));
                $this->db->update('affiliates', $data);
            }
        }

    }

    function activate_account($affiliate_code){
		$data = array('is_verified'=> 1);			
		$this->db->where(array('affiliate_code'=>$affiliate_code));
		$this->db->update('affiliates', $data);

	}

    function insert_affiliate_click($data) {
        $insert = $this->db->insert('affiliate_clicks', $data);
        if ($insert){
            $arr_return = array('res' => true,'dt' => '<i class="icon-checkmark-circle"></i> Affiliate click inserted successfully.');
        }else{
            $arr_return = array('res' => false,'dt' => '<i class="icon-cross-circle"></i> Could not insert Affiliate Click successfully.');
        }
        return $arr_return;     
    }
	

	//AFFILIATE TERMS
	function get_affiliate_terms(){
		$this->db->from('affiliate_terms');
		return $this->db->get()->result();
	}



}