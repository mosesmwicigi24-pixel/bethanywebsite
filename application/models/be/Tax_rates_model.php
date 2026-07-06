<?php
class Tax_rates_model extends CI_Model {
	
	function get_tax_rates_list(){
		$this->db->from('tax_rates');
		$this->db->where( array('is_deleted'=>0));
		return $this->db->get()->result();
	}
	function save($data){
		$insert = $this->db->insert('tax_rates', $data);
		if ($insert){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Tax Rate added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Tax Rate successfully. Please try again.');
		}
		return $arr_return;
	}
	function tax_rate_exists(){

		$msg = '';
		$msg2 = '';

		//TAX RATE NAME
		$tax_rate_name = $this->input->post('tax_rate_name');
		$this->db->where(array('tax_rate_name' => $tax_rate_name, 'is_deleted' => 0));
		$query = $this->db->get('tax_rates');
		if ($query->num_rows() > 0){ $msg2 .= '<i class="icon-cancel-circle2"></i> This Tax Rate Name has already been defined.<br>'; }

		//TAX RATE CODE
		$tax_rate_code = $this->input->post('tax_rate_code');
		$this->db->where(array('tax_rate_code' => $tax_rate_code, 'is_deleted' => 0));
		$query = $this->db->get('tax_rates');
		if ($query->num_rows() > 0){ $msg2 .= '<i class="icon-cancel-circle2"></i> This Tax Rate Code has already been defined.<br>'; }

		if ($msg != $msg2) {
			$arr_return = array('res' => true,'dt' => $msg2);
		}else{
			$arr_return = array('res' => false,'dt' => '');
		}

		return $arr_return;
	}
	function get_tax_rate($tax_rate_id){
		$this->db->from('tax_rates');
		$this->db->where( array('tax_rate_id'=>$tax_rate_id));
		return $this->db->get()->result();
	}
	function get_tax_rate2($tax_rate_id){
		$this->db->from('tax_rates');
		$this->db->where( array('tax_rate_id'=>$tax_rate_id));
		return $this->db->get()->result_array();
	}
	function tax_rate_update_exists($tax_rate_id){

		$msg = '';
		$msg2 = '';

		//TAX RATE NAME
		$tax_rate_name = $this->input->post('tax_rate_name');
		$this->db->where(array('tax_rate_name' => $tax_rate_name, 'is_deleted' => 0, 'tax_rate_id !=' => $tax_rate_id));
		$query = $this->db->get('tax_rates');
		if ($query->num_rows() > 0){ $msg2 .= '<i class="icon-cancel-circle2"></i> This Tax Rate Name has already been defined.<br>'; }

		//TAX RATE CODE
		$tax_rate_code = $this->input->post('tax_rate_code');
		$this->db->where(array('tax_rate_code' => $tax_rate_code, 'is_deleted' => 0, 'tax_rate_id !=' => $tax_rate_id));
		$query = $this->db->get('tax_rates');
		if ($query->num_rows() > 0){ $msg2 .= '<i class="icon-cancel-circle2"></i> This Tax Rate Code has already been defined.<br>'; }

		if ($msg != $msg2) {
			$arr_return = array('res' => true,'dt' => $msg2);
		}else{
			$arr_return = array('res' => false,'dt' => '');
		}

		return $arr_return;
	}	
	function update($data,$tax_rate_id){
		$this->db->where(array('tax_rate_id'=>$tax_rate_id));
		$update = $this->db->update('tax_rates', $data);
		if ($update){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Tax Rate updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Tax Rate successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete($tax_rate_id){
		$data = array(
			'is_deleted'=> 1
		);				
		$this->db->where( array('tax_rate_id'=>$tax_rate_id));
		$delupdate = $this->db->update('tax_rates', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Tax Rate deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Rax Rate');
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
			$this->db->where( array('tax_rate_id'=>$value));
			$res = $this->db->update('tax_rates', $data);
			
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