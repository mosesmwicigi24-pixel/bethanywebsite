<?php
class Outlets_model extends CI_Model {
	
	function get_outlets_list(){
		$this->db->from('outlets');
		$this->db->where( array('is_deleted'=>0));
		return $this->db->get()->result();
	}
	function save($data){
		$insert = $this->db->insert('outlets', $data);
		$insert_id = $this->db->insert_id();

		if ($insert){
			$this->update_main_outlet($insert_id);
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Outlet added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Outlet successfully. Please try again.');
		}
		return $arr_return;
	}
	function outlet_exists($outlet_name){
		$this->db->where(array('outlet_name' => $outlet_name, 'is_deleted' => 0));
		$query = $this->db->get('outlets');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	function update_main_outlet($outlet_id){
		$is_main = $this->input->post('is_main');
		if($is_main == 'on'){
			//SET THIS OUTLET
			$data = array(
				'is_main'=> 1
			);				
			$this->db->where( array('outlet_id'=>$outlet_id));
			$this->db->update('outlets', $data);

			//UNSET OTHER OUTLETS
			$data = array(
				'is_main'=> 0
			);				
			$this->db->where( array('outlet_id != '=>$outlet_id));
			$this->db->update('outlets', $data);
		}
	}
	function get_outlet($outlet_id){
		$this->db->from('outlets');
		$this->db->where( array('outlet_id'=>$outlet_id));
		return $this->db->get()->result();
	}
	function get_outlet2($outlet_id){
		$this->db->from('outlets');
		$this->db->where( array('outlet_id'=>$outlet_id));
		return $this->db->get()->result_array();
	}
	function outlet_update_exists($outlet_id,$outlet_name){
		$this->db->where(array('outlet_name' => $outlet_name, 'is_deleted' => 0, 'outlet_id !=' => $outlet_id));
		$query = $this->db->get('outlets');
		if ($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function update($data,$outlet_id){
		$this->db->where(array('outlet_id'=>$outlet_id));
		$update = $this->db->update('outlets', $data);
		if ($update){
			$this->update_main_outlet($outlet_id);
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Outlet updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Outlet successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete($outlet_id){
		$data = array(
			'is_deleted'=> 1
		);			
		$this->db->where( array('outlet_id'=>$outlet_id));
		$delupdate = $this->db->update('outlets', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Outlet deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Outlet');
		}
		return $arr_return;
	}


	//REGISTERS
	function get_registers_list($outlet_id){
		$this->db->select('registers.*,');
		$this->db->from('registers');
		$this->db->where( array('registers.is_deleted'=>0, 'registers.outlet_id'=>$outlet_id));
		return $this->db->get()->result();
	}
	function save_register($data){
		$insert = $this->db->insert('registers', $data);
		if ($insert){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Cash Register added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Cash Register successfully. Please try again.');
		}
		return $arr_return;
	}
	function register_exists($register_name, $outlet_id){
		$this->db->where(array('register_name' => $register_name, 'outlet_id' => $outlet_id, 'is_deleted' => 0));
		$query = $this->db->get('registers');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}

	}
	function get_register($register_id){
		$this->db->from('registers');
		$this->db->where( array('register_id'=>$register_id));
		return $this->db->get()->result_array();
	}
	function get_register2($register_id){
		$this->db->from('registers');
		$this->db->where( array('register_id'=>$register_id));
		return $this->db->get()->result();
	}
	function register_update_exists($register_id, $register_name, $outlet_id){
		$this->db->where(array('register_name' => $register_name, 'outlet_id' => $outlet_id, 'is_deleted' => 0, 'register_id !=' => $register_id));
		$query = $this->db->get('registers');
		if ($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function update_register($data,$register_id){
		$this->db->where(array('register_id'=>$register_id));
		$update = $this->db->update('registers', $data);
		if ($update){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Cash Register updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Cash Register successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete_register($register_id){
		$data = array(
			'is_deleted'=> 1
		);				
		$this->db->where( array('register_id'=>$register_id));
		$delupdate = $this->db->update('registers', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Cash Register deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Cash Register');
		}
		return $arr_return;
	}


}