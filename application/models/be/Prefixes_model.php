<?php
class Prefixes_model extends CI_Model {
	
	function get_prefixes_list(){
		$this->db->from('prefixes');
		return $this->db->get()->result();
	}
	function get_prefix($prefix_id){
		$this->db->from('prefixes');
		$this->db->where( array('prefix_id'=>$prefix_id));
		return $this->db->get()->result();
	}
	function get_prefix2($prefix_id){
		$this->db->from('prefixes');
		$this->db->where( array('prefix_id'=>$prefix_id));
		return $this->db->get()->result_array();
	}
	function prefix_update_exists($prefix_id,$prefix_name){
		$this->db->where(array('prefix_name' => $prefix_name, 'prefix_id !=' => $prefix_id));
		$query = $this->db->get('prefixes');
		if ($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function update($data,$prefix_id){
		$this->db->where(array('prefix_id'=>$prefix_id));
		$update = $this->db->update('prefixes', $data);
		if ($update){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Prefix updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Prefix successfully. Please try again.');
		}
		return $arr_return;
	}


}