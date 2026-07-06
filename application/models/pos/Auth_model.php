<?php

class Auth_model extends CI_model {
	function __construct(){
		parent::__construct();
		$this->load->helper(array('form', 'url'));
		$this->load->library('phpmailer');
		$this->load->model('be/email_accounts_model');
	}

	function user_exists($email_address){
		$this->db->where('email_address',$email_address);
		$this->db->where('is_deleted',0);
		$query = $this->db->get('system_users');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}		
	}
	// function register_user($data){
	// 	$insert = $this->db->insert('system_users', $data);
	// 	if ($insert){
	// 		$arr_return = array('res' => true,'dt' => 'Registration successful. Please wait while you are being redirected to the login page.');
	// 	}else{
	// 		$arr_return = array('res' => false,'dt' => 'Registration not successful. Please try again.');
	// 	}
	// 	return $arr_return;		
	// }

	//check if the username and password match 
	function validate_login(){		
		$user_password = md5($this->input->post('login_password'));

		$this->db->select('system_users.*, user_roles.user_role_id, user_roles.user_role_name, user_roles.user_role_description');
		$this->db->where('email_address', $this->input->post('login_email_address'));
		$this->db->where('user_password', $user_password);
		$this->db->from('system_users');
		$this->db->join('user_roles', 'system_users.user_role_id = user_roles.user_role_id', 'LEFT OUTER');
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0){
			foreach ($query->result() as $row){
				if ($row->is_deleted == 1){
					$arr_return = array('res' => false,'dt' => 'Sorry. This account has been deleted. Please contact the admin for more assistance.');
				} elseif ($row->is_active == 0){
					$arr_return = array('res' => false,'dt' => 'Sorry. This account is inactive. Please contact the admin for more assistance.');
				} elseif ($this->validate_user_access('pos_login', $row->system_user_id) == false){
					$arr_return = array('res' => false,'dt' => 'Sorry, Access Denied. You do not have the rights to log into the POS.');
				} else {
					$this->session->set_userdata('bgs_pos_active', TRUE);
					$this->session->set_userdata('pos_system_user_id', $row->system_user_id);
					$this->session->set_userdata('pos_user_email_address', $row->email_address);
					$this->session->set_userdata('pos_user_first_name', $row->first_name);
					$this->session->set_userdata('pos_user_last_name', $row->last_name);
					$this->session->set_userdata('pos_user_profile_picture', $row->profile_picture);
					$this->session->set_userdata('pos_user_role', $row->user_role_name);
					$this->session->set_userdata('pos_user_is_super_admin', $row->is_super_admin);			

					$data = array (				
						'last_login' => date("Y-m-d H:i:s")
					);			
					$this->db->where('email_address', $row->email_address);
					$this->db->update('system_users', $data);

					$arr_return = array('res' => true,'dt' => "Login successful. Please wait while you're being redirected to your account...");
				}
			}			
		}else{
			$arr_return = array('res' => false,'dt' => 'Invalid credentials. Please try again.');
		}
		return $arr_return;
	}

	function validate_user_access($user_right, $system_user_id) {
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
	        $this->db->select("su.*, sur.*");
	        $this->db->from('system_users su');
	        $this->db->join('system_user_rights sur', 'su.user_role_id = sur.user_role_id', 'left outer');
	        $this->db->where( array('su.system_user_id' => $system_user_id));
	        $system_user_rights = $this->db->get()->result();

	        foreach ($system_user_rights as $row) {
	        	if ($row->$user_right == 1) {
	        		$access_valid = true;
	        	}
	        }

		}
		return $access_valid;
	}

	function get_user_outlets() {

		$this->db->where('system_user_id',$this->session->userdata('pos_system_user_id'));
		$system_user = $this->db->get('system_users')->result();

		foreach ($system_user as $row) {
			if ($row->is_super_admin == 1) {
				$this->db->from('outlets');
				$this->db->where( array('is_deleted' => 0));
				
				$query = $this->db->get();
		        $record_count = $query->num_rows();
		        $records = $query->result();

		        $arr_return = array('records' => $records, 'record_count' => $record_count);
			} else {
				$this->db->from('outlets');
				$this->db->join('system_user_outlets', 'system_user_outlets.outlet_id = outlets.outlet_id');
				$this->db->where( array('system_user_outlets.system_user_id' => $this->session->userdata('pos_system_user_id')));
				
				$query = $this->db->get();
		        $record_count = $query->num_rows();
		        $records = $query->result();

		        $arr_return = array('records' => $records, 'record_count' => $record_count);
			}
		}		

        return $arr_return;
	}

	function generate_password( $length) {
		$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#";
		$password = substr( str_shuffle( $chars ), 0, $length );
		return $password;
	}

	function reset_password(){
		$email_address = $this->input->post('email_address');

		$this->db->where('email_address', $email_address);
		$this->db->where('is_deleted', 0);
		$query = $this->db->get('system_users');
		
		if ($query->num_rows() < 1){
			$arr_return = array('res' => FALSE,'dt' => 'No account is associated with the email address : ' . $email_address);
			return $arr_return;
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
				return $arr_return;
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

				try {
					ob_start();
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
	                 
	                $mail->Subject = 'Bethany House POS | Password Reset - ' . date('d-m-Y H:i:s');

	                $email_message = "Dear " . $first_name . ",<br /><br />";
	                $email_message .= "Your Bethany House POS account password has been reset. Your login credentials are as shown below:<br /><br />";
	                $email_message .= "<strong>Email Address:</strong> ".$email_address. "<br /><br />"; 
	                $email_message .= "<strong>New Password:</strong> ".$new_user_password."<br /><br /><br />";  

	                $email_message .= "You can use the link below to login.<br /><br />"; 

	                $email_message .= "<a href='".base_url()."pos/auth/login' style='color:#EE7202 !important'>Bethany House POS Account Login</a><br /><br />";

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
						return $arr_return;
					}else{

						$user_data = array(
							'user_password' => md5($new_user_password)
						);			
						$this->db->where(array('email_address'=>$email_address));
						$this->db->update('system_users', $user_data);


						$arr_return = array('res' => TRUE,'dt' => 'Password reset successful. A new Password has been sent to your Email Address.');
						return $arr_return;					
					}
					ob_get_clean();
				} catch (phpmailerException $e) {
					$arr_return = array('res' => FALSE,'dt' => 'There was an error sending your Password recovery Email. Please try again.<br>-> ' . $e->errorMessage());
					return $arr_return;
				} catch (Exception $e) {
					$arr_return = array('res' => FALSE,'dt' => 'There was an error sending your Password recovery Email. Please try again.<br>-> ' . $e->errorMessage());
					return $arr_return;									
				}
			}			
		}
		
	}
	
	function check_super_admin(){
		$this->db->where(array('is_super_admin' => 1,'is_active' => 1,'is_deleted' => 0));
		$query = $this->db->get('system_users');
		if($query->num_rows() > 0){
			return true;
		}else{
			return false;	
		}		
	}
	
	function verify_lock_password($username,$pwd){
		$password = md5($pwd);
		$this->db->where('username', $username);
		$this->db->where('password', $password);
		
		$query = $this->db->get('user_admin');
		
		if($query->num_rows == 1){
			return true;
		}else{
			return false;
		}		
	}
	
	//PROFILE
	function get_profile(){
		$this->db->select('system_users.*, user_roles.user_role_id, user_roles.user_role_name, user_roles.user_role_description');
		$this->db->from('system_users');
		$this->db->join('user_roles', 'user_roles.user_role_id = system_users.user_role_id', 'left outer');
		$this->db->where(array('system_users.system_user_id'=>$this->session->userdata('pos_system_user_id')));
		
		return $this->db->get()->result();
	}
	function update_profile(){
		$system_user_id = $this->input->post('system_user_id');
		$email_address = $this->input->post('email_address');

		$data = array(
			'first_name' => $this->input->post('first_name'),
			'last_name' => $this->input->post('last_name'),
			'phone_number' => $this->input->post('phone_number'),
			'gender' => $this->input->post('gender'),
			'address' => $this->input->post('address')
		);

		$this->db->where(array('system_user_id' => $system_user_id));
		$update = $this->db->update('system_users', $data);

		if ($update){
			$this->session->set_userdata('pos_user_email_address', $this->input->post('email_address'));
			$this->session->set_userdata('pos_user_first_name', $this->input->post('first_name'));
			$this->session->set_userdata('pos_user_last_name', $this->input->post('last_name'));

			$arr_return = array('res' => true,'dt' => 'Profile updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not update Profile successfully. Please try again.');
		}			
		return $arr_return;
	}
	function old_password_valid($old_password, $system_user_id){
		$this->db->where('user_password',md5($old_password));
		$this->db->where('system_user_id',$system_user_id);

		$query = $this->db->get('system_users');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	function change_password(){

		$old_password = $this->input->post('old_password');
		$system_user_id = $this->input->post('system_user_id');

    	if ($this->old_password_valid($old_password, $system_user_id) == false){
    		$arr_return = array('res' => false,'dt' => 'The Old Password you have provided is incorrect.');
    	}else{
    		$data = array(
				'user_password' => md5($this->input->post('new_password'))			
			);
			$this->db->where(array('system_user_id' => $system_user_id));
			$update = $this->db->update('system_users', $data);

			if ($update){

				// $account_full_name = '';
				// $account_email_address = '';
				// $account_phone_number = '';

				// $account_password = $this->input->post('new_password');

				// $account = $this->get_account_by_account_id($account_id);

				// foreach ($account as $row){
				// 	$account_full_name = $row->full_name;
				// 	$account_email_address = $row->email_address;
				// 	$account_phone_number = $row->phone_number;
				// }

				// $mail          = new PHPMailer();
				// $mail->IsSMTP();
				// $mail->SMTPSecure = 'ssl';
				// $mail->SMTPAuth   = true;
				// $mail->Host       = "mail.hypertechsolutions.co.ke";
				// $mail->Port       = 465;
				// $mail->Username   = "info@hypertechsolutions.co.ke";
				// $mail->Password   = "MN7KNC10";
				
				// $mail->SetFrom('info@hypertechsolutions.co.ke', 'Hypertech Solutions Limited');

				// $email_to = $account_email_address; 
				 
				// $mail->Subject = 'CityDrop Password Change ' . date('d-m-Y H:i:s');

				// $email_message = "Hello ". $account_full_name . ", <br /><br />Please find your current password for CityDrop below.<br /><br />"; 
				// $email_message .= "<strong>Username:</strong> ".$account_email_address." or " . $account_phone_number. "<br /><br />"; 
				// $email_message .= "<strong>New Password:</strong> ".$account_password."<br /><br /><br />"; 
				// $email_message .= "You can use the following link to login.<br /><br />"; 

				// $email_message .= "<a href='".base_url()."auth/login' style='color:#EE7202 !important'>CityDrop Login</a><br /><br />";

				// $email_message .= "Regards,<br />";
				// $email_message .= "CityDrop Team<br />";
				// $email_message .= "Email: support@tibs.ac.ke<br />";
				// $email_message .= "_________________________________________________<br />";
				// $email_message .= "Note: This is a system generated mail. Please do NOT reply to it.";

				
				// $message = file_get_contents(base_url().'email_temp/emheader');
				// $message .= file_get_contents(base_url().'email_temp/embody');
				// $message .= file_get_contents(base_url().'email_temp/emfooter');
				// //$logo = base_url().'assets/fe/images/logo-sm.png';
				
				// $replacements = array(
		  //       	//'({logo})' => $logo, 
				// 	'({message_subject})' => 'CityDrop Password Change ' . date('d-m-Y H:i:s'), 
				// 	'({message_body})' => nl2br( stripslashes( $email_message ) )
				// );
				// $message = preg_replace(array_keys( $replacements ), array_values( $replacements ), $message );
				
				// $plaintext = $message;
				// $plaintext = strip_tags( stripslashes( $plaintext ), '<p><br><h2><h3><h1><h4>' );
				// $plaintext = str_replace( array( '<p>', '<br />', '<br>', '<h1>', '<h2>', '<h3>', '<h4>' ), PHP_EOL, $plaintext );
				// $plaintext = str_replace( array( '</p>', '</h1>', '</h2>', '</h3>', '</h4>' ), '', $plaintext );
				// $plaintext = html_entity_decode( stripslashes( $plaintext ) );
			
				
				// $mail->MsgHTML( stripslashes( $message ) ); 
				
				// $mail->AltBody = $plaintext;
				// $mail->AddAddress($email_to, "");
			
				// if( !$mail->Send() ){
				// 	$arr_return = array('res' => TRUE,'dt' => 'Password changed successfully.');
				// }else{
				// 	$arr_return = array('res' => TRUE,'dt' => 'Password changed successfully. An Email has been sent to you with your New Password details.');
				// }

				$arr_return = array('res' => TRUE,'dt' => 'Password changed successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => 'Could not change Password successfully. Please try again.');
			}
		}
		return $arr_return;

	}

}