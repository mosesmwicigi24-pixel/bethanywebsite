<?php
class Void_reasons_model extends CI_Model {
	
	function get_void_reasons_list(){
		$this->db->select('void_reasons.*,');
		$this->db->from('void_reasons');
		$this->db->where( array('void_reasons.is_deleted'=>0));
		return $this->db->get()->result();
	}
	function save($data){
		$insert = $this->db->insert('void_reasons', $data);
		if ($insert){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Void Reason added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Void Reason successfully. Please try again.');
		}
		return $arr_return;
	}
	function void_reason_exists(){

		$msg = '';
		$msg2 = '';

		//VOID REASON
		$void_reason = $this->input->post('void_reason');
		$this->db->where(array('void_reason' => $void_reason, 'is_deleted' => 0));
		$query = $this->db->get('void_reasons');
		if ($query->num_rows() > 0){ $msg2 .= '<i class="icon-cancel-circle2"></i> This Void Reason has already been defined.<br>'; }

		if ($msg != $msg2) {
			$arr_return = array('res' => true,'dt' => $msg2);
		}else{
			$arr_return = array('res' => false,'dt' => '');
		}

		return $arr_return;
	}
	function get_void_reason($void_reason_id){
		$this->db->from('void_reasons');
		$this->db->where( array('void_reason_id'=>$void_reason_id));
		return $this->db->get()->result();
	}
	function get_void_reason2($void_reason_id){
		$this->db->from('void_reasons');
		$this->db->where( array('void_reason_id'=>$void_reason_id));
		return $this->db->get()->result_array();
	}
	function void_reason_update_exists($void_reason_id){

		$msg = '';
		$msg2 = '';

		//VOID REASON
		$void_reason = $this->input->post('void_reason');
		$this->db->where(array('void_reason' => $void_reason, 'is_deleted' => 0, 'void_reason_id !=' => $void_reason_id));
		$query = $this->db->get('void_reasons');
		if ($query->num_rows() > 0){ $msg2 .= '<i class="icon-cancel-circle2"></i> This Void Reason has already been defined.<br>'; }

		if ($msg != $msg2) {
			$arr_return = array('res' => true,'dt' => $msg2);
		}else{
			$arr_return = array('res' => false,'dt' => '');
		}

		return $arr_return;
	}	
	function update($data,$void_reason_id){
		$this->db->where(array('void_reason_id'=>$void_reason_id));
		$update = $this->db->update('void_reasons', $data);
		if ($update){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Void Reason updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Void Rreason successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete($void_reason_id){
		$data = array(
			'is_deleted'=> 1
		);				
		$this->db->where( array('void_reason_id'=>$void_reason_id));
		$delupdate = $this->db->update('void_reasons', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Void Reason deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Void Reason');
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
			$this->db->where( array('void_reason_id'=>$value));
			$res = $this->db->update('void_reasons', $data);
			
			if ($res){}else{
				$msg_err2 = $msg_err2 . '<i class="icon-cancel-circle2"></i> Error deleting Tax Rate';
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