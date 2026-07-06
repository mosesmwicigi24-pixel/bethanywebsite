<?php
class Product_sizes_model extends CI_Model {
	
	function get_product_sizes_list(){
		$this->db->from('product_sizes');
		$this->db->where( array('is_deleted'=>0));
		return $this->db->get()->result();
	}
	function save($data){
		$insert = $this->db->insert('product_sizes', $data);
		if ($insert){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Product Size added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Product Size successfully. Please try again.');
		}
		return $arr_return;
	}
	function product_size_exists($product_size_name){
		$this->db->where(array('product_size_name' => $product_size_name, 'is_deleted' => 0));
		$query = $this->db->get('product_sizes');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}

	}
	function get_product_size($product_size_id){
		$this->db->from('product_sizes');
		$this->db->where( array('product_size_id'=>$product_size_id));
		return $this->db->get()->result();
	}
	function get_product_size2($product_size_id){
		$this->db->from('product_sizes');
		$this->db->where( array('product_size_id'=>$product_size_id));
		return $this->db->get()->result_array();
	}
	function product_size_update_exists($product_size_id,$product_size_name){
		$this->db->where(array('product_size_name' => $product_size_name, 'is_deleted' => 0, 'product_size_id !=' => $product_size_id));
		$query = $this->db->get('product_sizes');
		if ($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function update($data,$product_size_id){
		$this->db->where(array('product_size_id'=>$product_size_id));
		$update = $this->db->update('product_sizes', $data);
		if ($update){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Product Size updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Product Size successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete($product_size_id){
		$data = array(
			'is_deleted'=> 1
		);				
		$this->db->where( array('product_size_id'=>$product_size_id));
		$delupdate = $this->db->update('product_sizes', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Product Size deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Product Size');
		}
		return $arr_return;
	}


}