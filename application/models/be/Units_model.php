<?php
class Units_model extends CI_Model {
	
	function get_units_list(){
		$this->db->select("u.*, ut.unit_type_name, ut.unit_type_description");
		$this->db->from('units u');
		$this->db->join('unit_types ut', 'ut.unit_type_id = u.unit_type_id', 'left outer');
		$this->db->where( array('u.is_deleted'=>0));
		return $this->db->get()->result();
	}
	function save($data){
		$insert = $this->db->insert('units', $data);
		$insert_id = $this->db->insert_id();
		if ($insert){
			$this->save_related_units($insert_id);
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Unit added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Unit successfully. Please try again.');
		}
		return $arr_return;
	}
	function save_related_units($unit_id) {
		$related_unit_id = $this->input->post('ru_unit_id');
		$conversion_factor = $this->input->post('ru_conversion_factor');

		if ($related_unit_id !== null && $related_unit_id !== '') {
			foreach ($related_unit_id as $key => $temp_id){
				if ($conversion_factor[$key] != '' && $conversion_factor[$key] != null){
					$new_data = array(
						'unit_id' => $unit_id,
						'related_unit_id' => $temp_id,
						'conversion_factor' => $conversion_factor[$key]
					);
					$insert = $this->db->insert('units_related', $new_data);				
				}
			}
		}
	}
	function unit_exists($unit_name){
		$this->db->where(array('unit_name' => $unit_name, 'is_deleted' => 0));
		$query = $this->db->get('units');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}

	}
	function get_unit($unit_id){
		$this->db->from('units');
		$this->db->where( array('unit_id'=>$unit_id));
		return $this->db->get()->result();
	}
	function get_unit2($unit_id){
		$this->db->from('units');
		$this->db->where( array('unit_id'=>$unit_id));
		return $this->db->get()->result_array();
	}
	function get_units_by_type($unit_type_id){
		$this->db->from('units');
		$this->db->where( array('is_deleted'=>0, 'unit_type_id'=>$unit_type_id));
		return $this->db->get()->result();
	}
	function get_num_units_by_type($unit_type_id){
		$this->db->from('units');
		$this->db->where( array('is_deleted'=>0, 'unit_type_id'=>$unit_type_id));
		return $this->db->count_all_results();
	}
	function get_related_units($unit_id){
		$this->db->from('units_related');
		$this->db->where( array('unit_id'=>$unit_id));
		return $this->db->get()->result();
	}
	function get_edit_units_by_type($unit_type_id, $unit_id){
		$this->db->select("u.*, ur.conversion_factor");
		$this->db->from('units u');
		$this->db->join('units_related ur', 'ur.related_unit_id = u.unit_id AND ur.unit_id = ' . $unit_id, 'left outer');
		$this->db->where( array('u.is_deleted'=>0, 'u.unit_type_id'=>$unit_type_id));
		$this->db->where( array('u.unit_id != '=>$unit_id));
		$this->db->group_by('u.unit_id');
		return $this->db->get()->result();
	}
	function get_num_edit_units_by_type($unit_type_id, $unit_id){
		$this->db->select("u.*, ur.conversion_factor");
		$this->db->from('units u');
		$this->db->join('units_related ur', 'ur.related_unit_id = u.unit_id AND ur.unit_id = ' . $unit_id, 'left outer');
		$this->db->where( array('u.is_deleted'=>0, 'u.unit_type_id'=>$unit_type_id));
		$this->db->where( array('u.unit_id != '=>$unit_id));
		$this->db->group_by('u.unit_id');
		return $this->db->count_all_results();
	}
	function unit_update_exists($unit_id,$unit_name){
		$this->db->where(array('unit_name' => $unit_name, 'is_deleted' => 0, 'unit_id !=' => $unit_id));
		$query = $this->db->get('units');
		if ($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function update($data,$unit_id){
		$this->db->where(array('unit_id'=>$unit_id));
		$update = $this->db->update('units', $data);
		if ($update){
			$this->update_related_units($unit_id);
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Unit updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Unit successfully. Please try again.');
		}
		return $arr_return;
	}
	function update_related_units($unit_id) {
		$related_unit_id = $this->input->post('ru_unit_id');
		$conversion_factor = $this->input->post('ru_conversion_factor');

		// CHECK CURRENT & OLDER UNIT TYPE
		$unit_type_id = $this->input->post('unit_type_id');

		$unit = $this->get_unit($unit_id);
		foreach ($unit as $row) {
			$old_unit_type_id = $row->unit_type_id;

			if ($old_unit_type_id !== $unit_type_id) {
				$this->db->where('unit_id', $unit_id);
			   	$this->db->delete('units_related');
			}
		}

		$related_units = $this->get_related_units($unit_id);

		foreach ($related_units as $row){
			$found = false;
			$new_conversion_factor = '';
			if ($related_unit_id !== null && $related_unit_id !== '') {
				foreach ($related_unit_id as $key => $temp_id){
					if ($row->related_unit_id == $temp_id){
						$new_conversion_factor = $conversion_factor[$key];
						$found = true;
						break;
					}
				}
			}
			if ($found == false){
			   $this->db->where('unit_id', $unit_id);
			   $this->db->where('related_unit_id', $row->related_unit_id);			   
			   $this->db->delete('units_related'); 				
			} else {
				if ($new_conversion_factor == '') {
					$this->db->where('unit_id', $unit_id);
			   		$this->db->where('related_unit_id', $row->related_unit_id);			   
			   		$this->db->delete('units_related'); 
				} else {
					$data = array(
						'conversion_factor' => $new_conversion_factor
					);	

					$this->db->where('unit_id', $unit_id);
				   	$this->db->where('related_unit_id', $row->related_unit_id);
					$this->db->update('units_related', $data);
				}				
			}
		}


		$related_units = $this->get_related_units($unit_id);

		if ($related_unit_id !== null && $related_unit_id !== '') {
			foreach ($related_unit_id as $key => $temp_id){
				if ($conversion_factor[$key] != '' && $conversion_factor[$key] != null){
					$found = false;
					foreach ($related_units as $row){
						if ($row->related_unit_id == $temp_id){
							$found = true;
							break;
						}
					}
					if ($found == false){
						$new_data = array(
							'unit_id' => $unit_id,
							'related_unit_id' => $temp_id,
							'conversion_factor' => $conversion_factor[$key]
						);
						$insert = $this->db->insert('units_related', $new_data);
					}
				}
			}	
		}

	}
	function delete($unit_id){
		$data = array(
			'is_deleted'=> 1
		);				
		$this->db->where( array('unit_id'=>$unit_id));
		$delupdate = $this->db->update('units', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Unit deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Unit');
		}
		return $arr_return;
	}


	//UNIT TYPES
	function get_unit_types_list(){
		$this->db->from('unit_types');
		$this->db->where( array('is_deleted'=>0));
		return $this->db->get()->result();
	}
	function get_active_unit_types(){
		$this->db->select("ut.*");
		$this->db->from('unit_types ut');
		$this->db->join('units u', 'u.unit_type_id = ut.unit_type_id AND u.is_deleted = 0');
		$this->db->where( array('ut.is_deleted'=>0));
		$this->db->group_by('ut.unit_type_id');
		return $this->db->get()->result();

		$this->db->from('unit_types');
		$this->db->where( array('is_deleted'=>0));
		return $this->db->get()->result();
	}
	function save_unit_type($data){
		$insert = $this->db->insert('unit_types', $data);
		if ($insert){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Unit Type added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Unit Type successfully. Please try again.');
		}
		return $arr_return;
	}
	function unit_type_exists($unit_type_name){
		$this->db->where(array('unit_type_name' => $unit_type_name, 'is_deleted' => 0));
		$query = $this->db->get('unit_types');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}

	}
	function get_unit_type($unit_type_id){
		$this->db->from('unit_types');
		$this->db->where( array('unit_type_id'=>$unit_type_id));
		return $this->db->get()->result();
	}
	function get_unit_type2($unit_type_id){
		$this->db->from('unit_types');
		$this->db->where( array('unit_type_id'=>$unit_type_id));
		return $this->db->get()->result_array();
	}
	function unit_type_update_exists($unit_type_id,$unit_type_name){
		$this->db->where(array('unit_type_name' => $unit_type_name, 'is_deleted' => 0, 'unit_type_id !=' => $unit_type_id));
		$query = $this->db->get('unit_types');
		if ($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function update_unit_type($data,$unit_type_id){
		$this->db->where(array('unit_type_id'=>$unit_type_id));
		$update = $this->db->update('unit_types', $data);
		if ($update){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Unit Type updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Unit Type successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete_unit_type($unit_type_id){
		$data = array(
			'is_deleted'=> 1
		);				
		$this->db->where( array('unit_type_id'=>$unit_type_id));
		$delupdate = $this->db->update('unit_types', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Unit Type deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Unit Type');
		}
		return $arr_return;
	}


}