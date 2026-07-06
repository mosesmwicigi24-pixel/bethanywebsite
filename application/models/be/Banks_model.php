<?php
class Banks_model extends CI_Model {
	
	function get_banks_list(){
		$this->db->select('banks.*,');
		$this->db->from('banks');
		$this->db->where( array('banks.is_deleted'=>0));
		return $this->db->get()->result();
	}
	function save_bank($data){
		$insert = $this->db->insert('banks', $data);
		if ($insert){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Bank added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Bank successfully. Please try again.');
		}
		return $arr_return;
	}
	function bank_exists($bank_name){
		$this->db->where(array('bank_name' => $bank_name, 'is_deleted' => 0));
		$query = $this->db->get('banks');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}

	}
	function get_bank($bank_id){
		$this->db->from('banks');
		$this->db->where( array('bank_id'=>$bank_id));
		return $this->db->get()->result();
	}
	function get_bank2($bank_id){
		$this->db->from('banks');
		$this->db->where( array('bank_id'=>$bank_id));
		return $this->db->get()->result_array();
	}
	function bank_update_exists($bank_id,$bank_name){
		$this->db->where(array('bank_name' => $bank_name, 'is_deleted' => 0, 'bank_id !=' => $bank_id));
		$query = $this->db->get('banks');
		if ($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function update_bank($data,$bank_id){
		$this->db->where(array('bank_id'=>$bank_id));
		$update = $this->db->update('banks', $data);
		if ($update){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Bank updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Bank successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete_bank($bank_id){
		$data = array(
			'is_deleted'=> 1
		);				
		$this->db->where( array('bank_id'=>$bank_id));
		$delupdate = $this->db->update('banks', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Bank deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Bank');
		}
		return $arr_return;
	}


	//BANK BRANCHES
	function get_bank_branches_list($bank_id){
		$this->db->select('bank_branches.*,');
		$this->db->from('bank_branches');
		$this->db->where( array('bank_branches.is_deleted'=>0, 'bank_branches.bank_id'=>$bank_id));
		return $this->db->get()->result();
	}
	function save_bank_branch($data){
		$insert = $this->db->insert('bank_branches', $data);
		if ($insert){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Bank Branch added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Bank Branch successfully. Please try again.');
		}
		return $arr_return;
	}
	function bank_branch_exists($bank_branch_name, $bank_id){
		$this->db->where(array('bank_branch_name' => $bank_branch_name, 'bank_id' => $bank_id, 'is_deleted' => 0));
		$query = $this->db->get('bank_branches');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}

	}
	function get_bank_branch($bank_branch_id){
		$this->db->from('bank_branches');
		$this->db->where( array('bank_branch_id'=>$bank_branch_id));
		return $this->db->get()->result_array();
	}
	function get_bank_branch2($bank_branch_id){
		$this->db->from('bank_branches');
		$this->db->where( array('bank_branch_id'=>$bank_branch_id));
		return $this->db->get()->result();
	}
	function bank_branch_update_exists($bank_branch_id, $bank_branch_name, $bank_id){
		$this->db->where(array('bank_branch_name' => $bank_branch_name, 'bank_id' => $bank_id, 'is_deleted' => 0, 'bank_branch_id !=' => $bank_branch_id));
		$query = $this->db->get('bank_branches');
		if ($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function update_bank_branch($data,$bank_branch_id){
		$this->db->where(array('bank_branch_id'=>$bank_branch_id));
		$update = $this->db->update('bank_branches', $data);
		if ($update){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Bank Branch updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Bank Branch successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete_bank_branch($bank_branch_id){
		$data = array(
			'is_deleted'=> 1
		);				
		$this->db->where( array('bank_branch_id'=>$bank_branch_id));
		$delupdate = $this->db->update('bank_branches', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Bank Branch deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Bank Branch');
		}
		return $arr_return;
	}



}