<?php
class Credit_terms_model extends CI_Model {
	
	function get_credit_terms_list(){
		$this->db->select('credit_terms.*,');
		$this->db->from('credit_terms');
		$this->db->where( array('credit_terms.is_deleted'=>0));
		return $this->db->get()->result();
	}
	function save($data){
		$insert = $this->db->insert('credit_terms', $data);
		if ($insert){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Payment Term added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Payment Term successfully. Please try again.');
		}
		return $arr_return;
	}
	function credit_term_exists(){

		$msg = '';
		$msg2 = '';

		//Payment Term
		$credit_term = $this->input->post('credit_term');
		$this->db->where(array('credit_term' => $credit_term, 'is_deleted' => 0));
		$query = $this->db->get('credit_terms');
		if ($query->num_rows() > 0){ $msg2 .= '<i class="icon-cancel-circle2"></i> This Payment Term has already been defined.<br>'; }

		if ($msg != $msg2) {
			$arr_return = array('res' => true,'dt' => $msg2);
		}else{
			$arr_return = array('res' => false,'dt' => '');
		}

		return $arr_return;
	}
	function get_credit_term($credit_term_id){
		$this->db->from('credit_terms');
		$this->db->where( array('credit_term_id'=>$credit_term_id));
		return $this->db->get()->result();
	}
	function get_credit_term2($credit_term_id){
		$this->db->from('credit_terms');
		$this->db->where( array('credit_term_id'=>$credit_term_id));
		return $this->db->get()->result_array();
	}
	function credit_term_update_exists($credit_term_id){

		$msg = '';
		$msg2 = '';

		//Payment Term
		$credit_term = $this->input->post('credit_term');
		$this->db->where(array('credit_term' => $credit_term, 'is_deleted' => 0, 'credit_term_id !=' => $credit_term_id));
		$query = $this->db->get('credit_terms');
		if ($query->num_rows() > 0){ $msg2 .= '<i class="icon-cancel-circle2"></i> This Payment Term has already been defined.<br>'; }

		if ($msg != $msg2) {
			$arr_return = array('res' => true,'dt' => $msg2);
		}else{
			$arr_return = array('res' => false,'dt' => '');
		}

		return $arr_return;
	}	
	function update($data,$credit_term_id){
		$this->db->where(array('credit_term_id'=>$credit_term_id));
		$update = $this->db->update('credit_terms', $data);
		if ($update){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Payment Term updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Payment Term successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete($credit_term_id){
		$data = array(
			'is_deleted'=> 1
		);				
		$this->db->where( array('credit_term_id'=>$credit_term_id));
		$delupdate = $this->db->update('credit_terms', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Payment Term deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Payment Term');
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
			$this->db->where( array('credit_term_id'=>$value));
			$res = $this->db->update('credit_terms', $data);
			
			if ($res){}else{
				$msg_err2 = $msg_err2 . '<i class="icon-cancel-circle2"></i> Error deleting Payment Term';
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