<?php
class System_users_model extends CI_Model {
	
	function get_system_users_list(){
		$this->db->select('system_users.*, user_roles.user_role_id, user_roles.user_role_name, user_roles.user_role_description');
		$this->db->from('system_users');
		$this->db->join('user_roles', 'user_roles.user_role_id = system_users.user_role_id', 'left outer');
		$this->db->where( array('system_users.is_deleted'=>0));
		return $this->db->get()->result();
	}
	function save($data){
		$insert = $this->db->insert('system_users', $data);
		$insert_id = $this->db->insert_id();
		if ($insert){

			//SYSTEM USER OUTLETS
			if ($this->input->post('outlet_id') != ''){
				$this->save_system_user_outlets($insert_id);
			}

			$arr_return = array('res' => true,'dt' => 'System User added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not add System User successfully. Please try again.');
		}
		return $arr_return;
	}
	function save_system_user_outlets($system_user_id){
		$outlet_id = $this->input->post('outlet_id');		
		foreach ($outlet_id as $temp_id){
			$new_data = array(
				'system_user_id' => $system_user_id,
				'outlet_id' => $temp_id
			);
			$insert = $this->db->insert('system_user_outlets', $new_data);
		}				
	}
	function system_user_exists($email_address){
		$this->db->where(array('email_address' => $email_address, 'is_deleted' => 0));
		$query = $this->db->get('system_users');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}

	}
	function get_system_user($system_user_id){
		$this->db->from('system_users');
		$this->db->where( array('system_user_id'=>$system_user_id));

		$system_user = $this->db->get()->result();
		$i = 0;
        foreach($system_user as $row){
            $system_user[$i]->outlets = $this->get_system_user_outlets($row->system_user_id);
            $i++;
        }
        return $system_user;

	}
	function get_system_user2($system_user_id){
		$this->db->from('system_users');
		$this->db->where( array('system_user_id'=>$system_user_id));
		return $this->db->get()->result();
	}
	function system_user_update_exists($system_user_id,$email_address){
		$this->db->where(array('email_address' => $email_address, 'is_deleted' => 0, 'system_user_id !=' => $system_user_id));
		$query = $this->db->get('system_users');
		if ($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function get_system_user_outlets($system_user_id){
		$this->db->from('system_user_outlets');
		$this->db->where( array('system_user_id'=>$system_user_id));
		return $this->db->get()->result();
	}
	function update($data,$system_user_id){
		$this->db->where(array('system_user_id'=>$system_user_id));
		$update = $this->db->update('system_users', $data);
		if ($update){

			//SYSTEM USER OUTLETS
			//if ($this->input->post('outlet_id') != ''){
				$this->update_system_user_outlets($system_user_id);
			//}

			$arr_return = array('res' => true,'dt' => 'System User updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not update Void Rreason successfully. Please try again.');
		}
		return $arr_return;
	}

	function update_system_user_outlets($system_user_id){
		if ($this->input->post('outlet_id') != ''){
			$outlet_id = $this->input->post('outlet_id');
			$system_user_outlets = $this->get_system_user_outlets($system_user_id);

			foreach ($system_user_outlets as $row){
				$found = false;
				foreach ($outlet_id as $temp_id){
					if ($row->outlet_id == $temp_id){
						$found = true;
						break;
					}
				}
				if ($found == false){
				   $this->db->where('system_user_id', $system_user_id);
				   $this->db->where('outlet_id', $row->outlet_id);			   
				   $this->db->delete('system_user_outlets'); 				
				}
			}

			$system_user_outlets = $this->get_system_user_outlets($system_user_id);
		
			foreach ($outlet_id as $temp_id){
				$found = false;
				foreach ($system_user_outlets as $row){
					if ($row->outlet_id == $temp_id){
						$found = true;
						break;
					}
				}
				if ($found == false){
					$new_data = array(
						'system_user_id' => $system_user_id,
						'outlet_id' => $temp_id
					);
					$this->db->insert('system_user_outlets', $new_data);
				}
			}
		} else {
			$this->db->where('system_user_id', $system_user_id);
			$this->db->delete('system_user_outlets');
		}				
	}

	function delete($system_user_id){
		$data = array(
			'is_deleted'=> 1
		);				
		$this->db->where( array('system_user_id'=>$system_user_id));
		$delupdate = $this->db->update('system_users', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'System User deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => 'Error deleting System User');
		}
		return $arr_return;
	}
	function change_password($data,$system_user_id){
		$this->db->where(array('system_user_id'=>$system_user_id));
		$update = $this->db->update('system_users', $data);

		if ($update){
			$arr_return = array('res' => true,'dt' => 'Password changed successfully');
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not change password successfully. Please try again.');
		}		
		return $arr_return;

	}

	

}