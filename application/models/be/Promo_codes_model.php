<?php
class Promo_codes_model extends CI_Model {
	
	function get_promo_codes_list(){
		$this->db->from('promo_codes');
		$this->db->where( array('is_deleted'=>0));
		return $this->db->get()->result();
	}
	function save($data){
		$insert = $this->db->insert('promo_codes', $data);
		$insert_id = $this->db->insert_id();
		if ($insert){
			$arr_return = array('res' => true,'dt' => 'Promo Code added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not add Promo Code successfully. Please try again.');
		}
		return $arr_return;
	}

	// function promo_code_exists($promo_code_name){
	// 	$this->db->where('promo_code_name',$promo_code_name);
	// 	$this->db->where('is_deleted',0);
	// 	$query = $this->db->get('promo_codes');
	// 	if ($query->num_rows() > 0){
	// 		return true;
	// 	}else{
	// 		return false;
	// 	}

	// }
	function validate_add_promo_code(){

        $promo_code_name = $this->input->post('promo_code_name');
        $promo_code = $this->input->post('promo_code');

        $msg = '';
        $msg2 = '';

        //PROMO CODE NAME
        $this->db->where(array('promo_code_name' => $promo_code_name, 'is_deleted' => 0));
        $query = $this->db->get('promo_codes');

        if ($query->num_rows() > 0){
            $msg = '<b><i class="icon-close2"></i> Duplicate Promo Code Name:</b> The Promo Code Name you entered already exists.<br>';
        }else{
            //PROMO CODE
            $this->db->where(array('promo_code' => $promo_code, 'promo_code !=' => '', 'is_deleted' => 0));
            $query = $this->db->get('promo_codes');

            if ($query->num_rows() > 0){
                $msg = '<b><i class="icon-close2"></i> Duplicate Promo Code:</b> The Promo Code you entered already exists.<br>';
            }
        }

        if ($msg == $msg2){
            $arr_return = array('res' => true,'dt' => '');
        }else{
            $arr_return = array('res' => false,'dt' => $msg);
        }

        return $arr_return;
    }
	function get_promo_code($promo_code_id){
		$this->db->from('promo_codes');
		$this->db->where( array('promo_code_id'=>$promo_code_id));
		return $this->db->get()->result();
	}
	function get_promo_code2($promo_code_id){
		$this->db->from('promo_codes');
		$this->db->where( array('promo_code_id'=>$promo_code_id));
		return $this->db->get()->result_array();
	}
	function get_promo_code_price($promo_code_id, $promo_code_duration){
		$promo_code_price = 0;
		$promo_code = $this->get_promo_code($promo_code_id);
		foreach ($promo_code as $row) {
			if ($promo_code_duration == '1 Week'){
				$promo_code_price = $row->one_week_price;
			}elseif ($promo_code_duration == '2 Weeks') {
				$promo_code_price = $row->two_weeks_price;
			}elseif ($promo_code_duration == '1 Month') {
				$promo_code_price = $row->one_month_price;
			}
		}
		return $promo_code_price;
	}
	function validate_update_promo_code($promo_code_id) {
        $promo_code_name = $this->input->post('promo_code_name');
        $promo_code = $this->input->post('promo_code');

        $msg = '';
        $msg2 = '';

        //PROMO CODE NAME
        $this->db->where(array('promo_code_name' => $promo_code_name, 'is_deleted' => 0));
        $this->db->where('promo_code_id != ',$promo_code_id);
        $query = $this->db->get('promo_codes');

        if ($query->num_rows() > 0){
            $msg = '<b><i class="icon-close2"></i> Duplicate Promo Code Name:</b> The Promo Code Name you entered already exists.<br>';
        }else{
            //PROMO CODE
            $this->db->where(array('promo_code' => $promo_code, 'promo_code !=' => '', 'is_deleted' => 0));
            $this->db->where('promo_code_id !=',$promo_code_id);
            $query = $this->db->get('promo_codes');

            if ($query->num_rows() > 0){
                $msg = '<b><i class="icon-close2"></i> Duplicate Promo Code:</b> The Promo Code you entered already exists.<br>';
            }
        }

        if ($msg == $msg2){
            $arr_return = array('res' => true,'dt' => '');
        }else{
            $arr_return = array('res' => false,'dt' => $msg);
        }

        return $arr_return;
	}
	function update($data,$promo_code_id){
		$this->db->where(array('promo_code_id'=>$promo_code_id));
		$update = $this->db->update('promo_codes', $data);
		if ($update){
			$arr_return = array('res' => true,'dt' => 'Promo Code updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not update Promo Code successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete($promo_code_id){
		$data = array(
			'is_deleted'=> 1
		);			
		$this->db->where( array('promo_code_id'=>$promo_code_id));
		$delupdate = $this->db->update('promo_codes', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'Promo Code deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => 'Error deleting Promo Code');
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
			$this->db->where( array('promo_code_id'=>$value));
			$res = $this->db->update('promo_codes', $data);
			
			if ($res){}else{
				$msg_err2 = $msg_err2 . 'Error deleting Promo Code';
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