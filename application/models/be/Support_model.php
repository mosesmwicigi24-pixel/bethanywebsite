<?php
class Support_model extends CI_Model {

	public function __construct(){
        parent::__construct();
        $this->load->library('phpmailer');
        $this->load->model('be/email_accounts_model');
        $this->load->model('be/email_notification_settings_model');
    }
	
	//NOTIFICATIONS
	function get_notifications(){
		$this->db->select("notifications.*");
		$this->db->from('notifications');
		return $this->db->get()->result();
	}

	function read_notification($notification_type, $transaction_id){
		$data = array(
			'is_read'=> 1
		);			
		$this->db->where( array('notification_type' => $notification_type, 'notification_ref_id' => $transaction_id));
		$this->db->update('notifications', $data);
	}

	function get_ajax_notifications(){
		$this->db->select("*");
		$this->db->from('notifications');
		$this->db->where( array('is_read' => 0));
		$this->db->order_by("notification_id","desc");
        $this->db->limit(10);

		$query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        $arr_return = array('records' => $records, 'record_count' => $record_count);

        return $arr_return;
	}

	function get_num_unread_notifications(){
		$this->db->select("*");
		$this->db->from('notifications');
		$this->db->where( array('is_read' => 0));
		return $this->db->count_all_results();
	}

	function update_read_notification($notification_id){
		$data = array(
			'is_read'=> 1
		);			
		$this->db->where( array('notification_id' => $notification_id));
		$this->db->update('notifications', $data);
	}

	function send_notification_emails(){
		$this->db->select('*');
        $this->db->from('notifications');        
        $this->db->where( array('email_sent'=>0));
        $notifications = $this->db->get()->result();

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

        foreach($notifications as $row){

            $mail          = new PHPMailer();
            $mail->IsSMTP();
            if ($mail_use_ssl == 1){
                $mail->SMTPSecure = 'ssl';
                $mail->SMTPAuth   = true;
            }
            // $mail->SMTPDebug = 2;
            $mail->SMTPOptions = array(
			    'ssl' => array(
			        'verify_peer' => false,
			        'verify_peer_name' => false,
			        'allow_self_signed' => true
			    )
			);
            $mail->Host       = $mail_host;
            $mail->Port       = $mail_port;
            $mail->Username   = $mail_username;
            $mail->Password   = $mail_password;
            
            $mail->SetFrom($mail_sender, $mail_sender_name);
            $email_to = $recipient_email_address; 
             
            $mail->Subject = 'You have a new notification from Bethany House : ' . $row->notification_type;

			$email_message =  $row->notification_details . "<br/><br/>";

            $email_message .= "Regards,<br />";
            $email_message .= "Bethany House<br />";
            $email_message .= "_________________________________________________<br />";
            $email_message .= "Note: This is a system generated mail. Please do NOT reply to it.";
            
            $message = file_get_contents(base_url().'email_temp/emheader');
            $message .= file_get_contents(base_url().'email_temp/embody');
            $message .= file_get_contents(base_url().'email_temp/emfooter');
            $logo = base_url().'assets/fe/img/logo.png';
            
            $replacements = array(
                '({logo})' => $logo, 
                '({message_subject})' => 'You have a new notification from Bethany House : ' . $row->notification_type, 
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
                $data = array('email_sent'=> 1);           
                $this->db->where(array('notification_id' => $row->notification_id));
                $this->db->update('notifications', $data);
            }
        }
	}

}