<?php
class Faqs_model extends CI_Model {
	
	function get_faqs_list(){
		$this->db->from('faqs');
		$this->db->where( array('is_deleted'=>0));
		return $this->db->get()->result();
	}
	function save($data){
		$insert = $this->db->insert('faqs', $data);
		$insert_id = $this->db->insert_id();
		if ($insert){
			$arr_return = array('res' => true,'dt' => 'FAQ added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not add FAQ successfully. Please try again.');
		}
		return $arr_return;
	}
	function get_faq($faq_id){
		$this->db->from('faqs');
		$this->db->where( array('faq_id'=>$faq_id));
		return $this->db->get()->result();
	}
	function get_faq2($faq_id){
		$this->db->from('faqs');
		$this->db->where( array('faq_id'=>$faq_id));
		return $this->db->get()->result_array();
	}
	function update($data,$faq_id){
		$this->db->where(array('faq_id'=>$faq_id));
		$update = $this->db->update('faqs', $data);
		if ($update){
			$arr_return = array('res' => true,'dt' => 'FAQ updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not update FAQ successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete($faq_id){
		$data = array(
			'is_deleted'=> 1
		);			
		$this->db->where( array('faq_id'=>$faq_id));
		$delupdate = $this->db->update('faqs', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'FAQ deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => 'Error deleting FAQ');
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
			$this->db->where( array('faq_id'=>$value));
			$res = $this->db->update('faqs', $data);
			
			if ($res){}else{
				$msg_err2 = $msg_err2 . 'Error deleting faq';
			}
		}
		if ($msg_err == $msg_err2){
			$arr_return = array('res' => true,'dt'=>'Bulk Transaction(s) completed successfully');
		}else{
			$arr_return = array('res' => false,'dt' => 'Bulk Transaction(s) could not be completed successfully');
		}

		return $arr_return;
	}

}