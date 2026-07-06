<?php
class Email_templates_model extends CI_Model {
	
	function get_email_templates_list(){
		$this->db->select('email_templates.*,');
		$this->db->from('email_templates');
		$this->db->where( array('email_templates.is_deleted'=>0));
		return $this->db->get()->result();
	}
	function save($data){
		$insert = $this->db->insert('email_templates', $data);
		if ($insert){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Email Template added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Email Template successfully. Please try again.');
		}
		return $arr_return;
	}
	function email_template_exists($email_template_name){
		$this->db->where(array('email_template_name' => $email_template_name, 'is_deleted' => 0));
		$query = $this->db->get('email_templates');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}

	}
	function get_email_template($email_template_id){
		$this->db->from('email_templates');
		$this->db->where( array('email_template_id'=>$email_template_id));
		return $this->db->get()->result();
	}
	function get_email_template2($email_template_id){
		$this->db->from('email_templates');
		$this->db->where( array('email_template_id'=>$email_template_id));
		return $this->db->get()->result_array();
	}
	function email_template_update_exists($email_template_id,$email_template_name){
		$this->db->where(array('email_template_name' => $email_template_name, 'is_deleted' => 0, 'email_template_id !=' => $email_template_id));
		$query = $this->db->get('email_templates');
		if ($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function update($data,$email_template_id){
		$this->db->where(array('email_template_id'=>$email_template_id));
		$update = $this->db->update('email_templates', $data);
		if ($update){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Email Template updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Email Template successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete($email_template_id){
		$data = array(
			'is_deleted'=> 1
		);				
		$this->db->where( array('email_template_id'=>$email_template_id));
		$delupdate = $this->db->update('email_templates', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Email Template deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Email Template');
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
			$this->db->where( array('email_template_id'=>$value));
			$res = $this->db->update('email_templates', $data);
			
			if ($res){}else{
				$msg_err2 = $msg_err2 . '<i class="icon-cancel-circle2"></i> Error deleting Email Template';
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