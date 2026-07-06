<?php
class Email_accounts_model extends CI_Model {
	
	function get_email_accounts_list(){
		$this->db->select('email_accounts.*,');
		$this->db->from('email_accounts');
		$this->db->where( array('email_accounts.is_deleted'=>0));
		return $this->db->get()->result();
	}
	function save($data){
		$insert = $this->db->insert('email_accounts', $data);
		$insert_id = $this->db->insert_id();
		if ($insert){
			$this->update_default_email_account($insert_id);
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Email Account added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Email Account successfully. Please try again.');
		}
		return $arr_return;
	}
	function update_default_email_account($email_account_id){
		$default_email_account = $this->input->post('default');
		if($default_email_account == 'on'){
			//SET THIS DEFAULT ACCOUNT
			$data = array(
				'is_default'=> 1
			);				
			$this->db->where( array('email_account_id'=>$email_account_id));
			$this->db->update('email_accounts', $data);

			//UNSET OTHER DEFAULTS
			$data = array(
				'is_default'=> 0
			);				
			$this->db->where( array('email_account_id != '=>$email_account_id));
			$this->db->update('email_accounts', $data);
		}
	}
	function email_account_exists($sender_email_address){
		$this->db->where(array('sender_email_address' => $sender_email_address, 'is_deleted' => 0));
		$query = $this->db->get('email_accounts');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}

	}
	function get_email_account($email_account_id){
		$this->db->from('email_accounts');
		$this->db->where( array('email_account_id'=>$email_account_id));
		return $this->db->get()->result();
	}
	function get_email_account2($email_account_id){
		$this->db->from('email_accounts');
		$this->db->where( array('email_account_id'=>$email_account_id));
		return $this->db->get()->result_array();
	}
	function get_default_email_account() {
		$this->db->select('*');
		$this->db->from('email_accounts');
		$this->db->where(array('is_default' => 1));
		return $this->db->get()->result();
	}
	function email_account_update_exists($email_account_id,$sender_email_address){
		$this->db->where(array('sender_email_address' => $sender_email_address, 'is_deleted' => 0, 'email_account_id !=' => $email_account_id));
		$query = $this->db->get('email_accounts');
		if ($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function update($data,$email_account_id){
		$this->db->where(array('email_account_id'=>$email_account_id));
		$update = $this->db->update('email_accounts', $data);
		if ($update){
			$this->update_default_email_account($email_account_id);
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Email Account updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Email Account successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete($email_account_id){
		$data = array(
			'is_deleted'=> 1
		);				
		$this->db->where( array('email_account_id'=>$email_account_id));
		$delupdate = $this->db->update('email_accounts', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Email Account deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Email Account');
		}
		return $arr_return;
	}
	function delete_bulk($ids){
		$msg_err = '';
		$msg_err2 = '';

		$d_ids = json_decode($ids);

		foreach($d_ids as $value) {
			$data = array(
				'is_deleted'=> 1
			);			
			$this->db->where( array('email_account_id'=>$value));
			$res = $this->db->update('email_accounts', $data);
			
			if ($res){}else{
				$msg_err2 = $msg_err2 . '<i class="icon-cancel-circle2"></i> Error deleting Email Account';
			}
		}
		if ($msg_err == $msg_err2){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Bulk Transaction(s) completed successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Bulk Transaction(s) could not be completed successfully');
		}

		return $arr_return;
	}

}