<?php
class Affiliate_packages_model extends CI_Model {
	
	function get_affiliate_packages_list(){
		$this->db->from('affiliate_packages');
		$this->db->where( array('is_deleted'=>0));
		return $this->db->get()->result();
	}
	function save($data){
		$insert = $this->db->insert('affiliate_packages', $data);
		$insert_id = $this->db->insert_id();
		if ($insert){
			$arr_return = array('res' => true,'dt' => 'Affiliate Package added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not add Affiliate Package successfully. Please try again.');
		}
		return $arr_return;
	}
	function affiliate_package_exists($affiliate_package_name){
		$this->db->where('affiliate_package_name',$affiliate_package_name);
		$this->db->where('is_deleted',0);
		$query = $this->db->get('affiliate_packages');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}

	}
	function get_affiliate_package($affiliate_package_id){
		$this->db->from('affiliate_packages');
		$this->db->where( array('affiliate_package_id'=>$affiliate_package_id));
		return $this->db->get()->result();
	}
	function get_affiliate_package2($affiliate_package_id){
		$this->db->from('affiliate_packages');
		$this->db->where( array('affiliate_package_id'=>$affiliate_package_id));
		return $this->db->get()->result_array();
	}
	function get_affiliate_package_price($affiliate_package_id, $affiliate_package_duration){
		$affiliate_package_price = 0;
		$affiliate_package = $this->get_affiliate_package($affiliate_package_id);
		foreach ($affiliate_package as $row) {
			if ($affiliate_package_duration == '1 Week'){
				$affiliate_package_price = $row->one_week_price;
			}elseif ($affiliate_package_duration == '2 Weeks') {
				$affiliate_package_price = $row->two_weeks_price;
			}elseif ($affiliate_package_duration == '1 Month') {
				$affiliate_package_price = $row->one_month_price;
			}
		}
		return $affiliate_package_price;
	}
	function affiliate_package_update_exists($affiliate_package_id,$affiliate_package_name){
		$this->db->where('affiliate_package_id !=',$affiliate_package_id);
		$this->db->where('affiliate_package_name',$affiliate_package_name);
		$this->db->where('is_deleted',0);

		$q = $this->db->get('affiliate_packages');

		if ($q->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function update($data,$affiliate_package_id){
		$this->db->where(array('affiliate_package_id'=>$affiliate_package_id));
		$update = $this->db->update('affiliate_packages', $data);
		if ($update){
			$arr_return = array('res' => true,'dt' => 'Affiliate Package updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not update Affiliate Package successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete($affiliate_package_id){
		$data = array(
			'is_deleted'=> 1
		);			
		$this->db->where( array('affiliate_package_id'=>$affiliate_package_id));
		$delupdate = $this->db->update('affiliate_packages', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'Affiliate Package deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => 'Error deleting Affiliate Package');
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
			$this->db->where( array('affiliate_package_id'=>$value));
			$res = $this->db->update('affiliate_packages', $data);
			
			if ($res){}else{
				$msg_err2 = $msg_err2 . 'Error deleting affiliate_package';
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