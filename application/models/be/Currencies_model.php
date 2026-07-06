<?php
class Currencies_model extends CI_Model {
	
	function get_currencies_list(){
		$this->db->select('currencies.*,countries.country_id, countries.country_name,countries.country_code, countries.nationality');
		$this->db->from('currencies');
		$this->db->join('countries', 'countries.country_id = currencies.country_id');
		$this->db->where( array('currencies.is_deleted'=>0));
		return $this->db->get()->result();
	}
	function get_default_currency() {
		$default_currency = '';
		$this->db->select('currencies.*');
		$this->db->from('currencies');
		$this->db->where( array('currencies.is_deleted'=>0, 'currencies.default_currency'=>1));
		$currency = $this->db->get()->result();

		foreach($currency as $row) {
			$default_currency = $row->currency_symbol;
		}
		return $default_currency;
	}
	function save($data){
		$insert = $this->db->insert('currencies', $data);
		$insert_id = $this->db->insert_id();

		if ($insert){
			$this->update_default_currency($insert_id);
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Currency added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Currency successfully. Please try again.');
		}
		return $arr_return;
	}
	function update_default_currency($currency_id){
		$default_currency = $this->input->post('default_currency');
		if($default_currency == 'on'){
			//SET THIS DEFAULT
			$data = array(
				'default_currency'=> 1
			);				
			$this->db->where( array('currency_id'=>$currency_id));
			$this->db->update('currencies', $data);

			//UNSET OTHER DEFAULTS
			$data = array(
				'default_currency'=> 0
			);				
			$this->db->where( array('currency_id != '=>$currency_id));
			$this->db->update('currencies', $data);
		}
	}
	function currency_exists(){

		$msg = '';
		$msg2 = '';

		//COUNTRIES
		$country_id = $this->input->post('country_id');
		$this->db->where(array('country_id' => $country_id, 'is_deleted' => 0));
		$query = $this->db->get('currencies');
		if ($query->num_rows() > 0){ $msg2 .= '<i class="icon-cancel-circle2"></i> A currency for this country has already been defined. Please select a different country<br>'; }

		//CURRENCY NAME
		$currency_name = $this->input->post('currency_name');
		$this->db->where(array('currency_name' => $currency_name, 'is_deleted' => 0));
		$query = $this->db->get('currencies');
		if ($query->num_rows() > 0){ $msg2 .= '<i class="icon-cancel-circle2"></i> This currency name has already been defined. Please select a different currency name<br>'; }

		//CURRENCY SYMBOL
		$currency_symbol = $this->input->post('currency_symbol');
		$this->db->where(array('currency_symbol' => $currency_symbol, 'is_deleted' => 0));
		$query = $this->db->get('currencies');
		if ($query->num_rows() > 0){ $msg2 .= '<i class="icon-cancel-circle2"></i> This currency symbol has already been defined. Please select a different currency symbol<br>'; }

		if ($msg != $msg2) {
			$arr_return = array('res' => true,'dt' => $msg2);
		}else{
			$arr_return = array('res' => false,'dt' => '');
		}

		return $arr_return;
	}
	function get_currency($currency_id){
		$this->db->from('currencies');
		$this->db->where( array('currency_id'=>$currency_id));
		return $this->db->get()->result();
	}
	function get_currency2($currency_id){
		$this->db->from('currencies');
		$this->db->where( array('currency_id'=>$currency_id));
		return $this->db->get()->result_array();
	}
	function currency_update_exists($currency_id){
		$msg = '';
		$msg2 = '';

		//COUNTRIES
		$country_id = $this->input->post('country_id');
		$this->db->where(array('country_id' => $country_id, 'is_deleted' => 0, 'currency_id !=' => $currency_id));
		$query = $this->db->get('currencies');
		if ($query->num_rows() > 0){ $msg2 .= '<i class="icon-cancel-circle2"></i> A currency for this country has already been defined. Please select a different country<br>'; }

		//CURRENCY NAME
		$currency_name = $this->input->post('currency_name');
		$this->db->where(array('currency_name' => $currency_name, 'is_deleted' => 0, 'currency_id !=' => $currency_id));
		$query = $this->db->get('currencies');
		if ($query->num_rows() > 0){ $msg2 .= '<i class="icon-cancel-circle2"></i> This currency name has already been defined. Please select a different currency name<br>'; }

		//CURRENCY SYMBOL
		$currency_symbol = $this->input->post('currency_symbol');
		$this->db->where(array('currency_symbol' => $currency_symbol, 'is_deleted' => 0, 'currency_id !=' => $currency_id));
		$query = $this->db->get('currencies');
		if ($query->num_rows() > 0){ $msg2 .= '<i class="icon-cancel-circle2"></i> This currency symbol has already been defined. Please select a different currency symbol<br>'; }

		if ($msg != $msg2) {
			$arr_return = array('res' => true,'dt' => $msg2);
		}else{
			$arr_return = array('res' => false,'dt' => '');
		}

		return $arr_return;
	}	
	function update($data,$currency_id){
		$this->db->where(array('currency_id'=>$currency_id));
		$update = $this->db->update('currencies', $data);

		if ($update){
			$this->update_default_currency($currency_id);
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Currency updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update currency successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete($currency_id){
		$data = array(
			'is_deleted'=> 1
		);				
		$this->db->where( array('currency_id'=>$currency_id));
		$delupdate = $this->db->update('currencies', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Currency deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Currency');
		}
		return $arr_return;
	}
	function delete_bulk_currencies($ids){
		$msg_err = '';
		$msg_err2 = '';

		$d_ids = json_decode($ids);

		foreach($d_ids as $value) {
			$data = array(
				'is_deleted'=> 1
			);			
			$this->db->where( array('currency_id'=>$value));
			$res = $this->db->update('currencies', $data);
			
			if ($res){}else{
				$msg_err2 = $msg_err2 . '<i class="icon-cancel-circle2"></i> Error deleting Currency';
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