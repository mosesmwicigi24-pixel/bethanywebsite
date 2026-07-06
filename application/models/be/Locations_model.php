<?php
class Locations_model extends CI_Model {
	
	//COUNTRIES
	function generate_country_sku($length = 6) {
    	$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
       	$randomString .= $characters[rand(0, strlen($characters) - 1)];
    	}
    	return $randomString;
	}	
	function get_country_sku(){
		$country_sku = $this->generate_country_sku();
		$checktrue = $this->check_country_sku_exists($country_sku);
		while ($checktrue == true){
			$country_sku = $this->generate_country_sku();
			$checktrue = $this->check_country_sku_exists($country_sku);
		}
		return $country_sku;
	}
	function check_country_sku_exists($sku){
		$this->db->from('countries');
		$this->db->where( array('country_sku_code'=>$sku));
		$numrows = $this->db->get()->num_rows();
		if ($numrows > 0){
			return true;
		}else{
			return false;
		}
	}
	function get_country_sku_code($country_id){
		$sku_code = '';
		$this->db->from('countries');
		$this->db->where( array('country_id'=>$country_id));
		$result = $this->db->get()->result();
		foreach ($result as $r){
			$sku_code = $r->country_sku_code;
		}

		if ($sku_code == ''){
			$sku_code = $this->get_country_sku();
		}

		return $sku_code;
	}

	function get_countries_list(){
		$this->db->from('countries');
		$this->db->where( array('is_deleted'=>0));
		$this->db->order_by("country_name", "asc");
		return $this->db->get()->result();
	}
	function get_nested_countries(){
		$this->db->from('countries');
		$this->db->where( array('is_deleted'=>0));
		$this->db->order_by("country_name", "asc");
		$countries = $this->db->get()->result();

		$i = 0;
		foreach ($countries as $row) {
			$countries[$i]->cr = $this->country_regions($row->country_id);
			$i++;
		}
		return $countries;
	}
	function country_regions($country_id) {
		$this->db->from('regions');
		$this->db->where(array('is_deleted'=>0, 'country_id'=>$country_id));
		$this->db->order_by("region_name", "asc");
		return $this->db->get()->result();
	}

	function save_country($data){
		$insert = $this->db->insert('countries', $data);
		$insert_id = $this->db->insert_id();
		if ($insert){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Country added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Country successfully. Please try again.');
		}
		return $arr_return;
	}
	function country_exists($country_name){
		$this->db->where('country_name',$country_name);
		$this->db->where('is_deleted',0);
		$query = $this->db->get('countries');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	function get_country($country_id){
		$this->db->from('countries');
		$this->db->where( array('country_id'=>$country_id));
		return $this->db->get()->result();
	}
	function get_country2($country_id){
		$this->db->from('countries');
		$this->db->where( array('country_id'=>$country_id));
		return $this->db->get()->result_array();
	}
	function get_country_id_by_region_id($region_id){
		$country_id = 0;		
		$this->db->from('regions');
		$this->db->where( array('region_id'=>$region_id));
		$result = $this->db->get()->result();

		foreach ($result as $row) {
			$country_id = $row->country_id;
		}

		return $country_id;
	}
	function country_update_exists($country_id,$country_name){
		$this->db->where('country_id !=',$country_id);
		$this->db->where('country_name',$country_name);
		$this->db->where('is_deleted',0);

		$q = $this->db->get('countries');

		if ($q->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function update_country($data,$country_id){
		$this->db->where(array('country_id'=>$country_id));
		$update = $this->db->update('countries', $data);
		if ($update){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Country updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Country successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete_country($country_id){
		$data = array(
			'is_deleted'=> 1
		);			
		$this->db->where( array('country_id'=>$country_id));
		$delupdate = $this->db->update('countries', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Country deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Country');
		}
		return $arr_return;
	}
	function delete_bulk_countries($ids){
		$msg_err = '';
		$msg_err2 = '';

		$d_ids = json_decode($ids);

		foreach($d_ids as $value) {
			$data = array(
				'is_deleted'=> 1
			);			
			$this->db->where( array('country_id'=>$value));
			$res = $this->db->update('countries', $data);
			
			if ($res){}else{
				$msg_err2 = $msg_err2 . 'Error deleting Country';
			}
		}
		if ($msg_err == $msg_err2){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Bulk Transaction(s) completed successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Bulk Transaction(s) could not be completed successfully');
		}

		return $arr_return;
	}

	//REGIONS
	function generate_region_sku($length = 6) {
    	$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
       	$randomString .= $characters[rand(0, strlen($characters) - 1)];
    	}
    	return $randomString;
	}	
	function get_region_sku(){
		$region_sku = $this->generate_region_sku();
		$checktrue = $this->check_region_sku_exists($region_sku);
		while ($checktrue == true){
			$region_sku = $this->generate_region_sku();
			$checktrue = $this->check_region_sku_exists($region_sku);
		}
		return $region_sku;
	}
	function check_region_sku_exists($sku){
		$this->db->from('regions');
		$this->db->where( array('region_sku_code'=>$sku));
		$numrows = $this->db->get()->num_rows();
		if ($numrows > 0){
			return true;
		}else{
			return false;
		}
	}
	function get_region_sku_code($region_id){
		$sku_code = '';
		$this->db->from('regions');
		$this->db->where( array('region_id'=>$region_id));
		$result = $this->db->get()->result();
		foreach ($result as $r){
			$sku_code = $r->region_sku_code;
		}

		if ($sku_code == ''){
			$sku_code = $this->get_region_sku();
		}

		return $sku_code;
	}

	function get_regions_list($country_id){
		$this->db->from('regions');
		$this->db->where( array('is_deleted'=>0, 'country_id'=>$country_id));
		return $this->db->get()->result();
	}

	function save_region($data){
		$insert = $this->db->insert('regions', $data);
		$insert_id = $this->db->insert_id();
		if ($insert){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Region added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Region successfully. Please try again.');
		}
		return $arr_return;
	}
	function region_exists($country_id, $region_name){
		$this->db->where('region_name',$region_name);
		$this->db->where('country_id',$country_id);
		$this->db->where('is_deleted',0);
		$query = $this->db->get('regions');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	function get_region($region_id){
		$this->db->from('regions');
		$this->db->where( array('region_id'=>$region_id));
		return $this->db->get()->result();
	}
	function get_region2($region_id){
		$this->db->from('regions');
		$this->db->where( array('region_id'=>$region_id));
		return $this->db->get()->result_array();
	}
	function get_region_id_by_locality_id($locality_id){
		$region_id = 0;		
		$this->db->from('localities');
		$this->db->where( array('locality_id'=>$locality_id));
		$result = $this->db->get()->result();

		foreach ($result as $row) {
			$region_id = $row->region_id;
		}

		return $region_id;
	}
	function get_regions_by_country($country_id){
		$this->db->select('*');
		$this->db->from('regions');
		$this->db->where(array('country_id' => $country_id, 'is_deleted' => 0));
		return $this->db->get()->result();
	}
	function region_update_exists($country_id,$region_id,$region_name){
		$this->db->where('region_id !=',$region_id);
		$this->db->where('country_id',$country_id);
		$this->db->where('region_name',$region_name);
		$this->db->where('is_deleted',0);

		$q = $this->db->get('regions');

		if ($q->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function update_region($data,$region_id){
		$this->db->where(array('region_id'=>$region_id));
		$update = $this->db->update('regions', $data);
		if ($update){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Region updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Region successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete_region($region_id){
		$data = array(
			'is_deleted'=> 1
		);			
		$this->db->where( array('region_id'=>$region_id));
		$delupdate = $this->db->update('regions', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Region deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Region');
		}
		return $arr_return;
	}
	function delete_bulk_regions($ids){
		$msg_err = '';
		$msg_err2 = '';

		$d_ids = json_decode($ids);

		foreach($d_ids as $value) {
			$data = array(
				'is_deleted'=> 1
			);			
			$this->db->where( array('region_id'=>$value));
			$res = $this->db->update('regions', $data);
			
			if ($res){}else{
				$msg_err2 = $msg_err2 . 'Error deleting Region';
			}
		}
		if ($msg_err == $msg_err2){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Bulk Transaction(s) completed successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Bulk Transaction(s) could not be completed successfully');
		}

		return $arr_return;
	}


	//SHIPPING ZONES
	function generate_shipping_zone_sku($length = 6) {
    	$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
       	$randomString .= $characters[rand(0, strlen($characters) - 1)];
    	}
    	return $randomString;
	}	
	function get_shipping_zone_sku(){
		$shipping_zone_sku = $this->generate_shipping_zone_sku();
		$checktrue = $this->check_shipping_zone_sku_exists($shipping_zone_sku);
		while ($checktrue == true){
			$shipping_zone_sku = $this->generate_shipping_zone_sku();
			$checktrue = $this->check_shipping_zone_sku_exists($shipping_zone_sku);
		}
		return $shipping_zone_sku;
	}
	function check_shipping_zone_sku_exists($sku){
		$this->db->from('shipping_zones');
		$this->db->where( array('shipping_zone_sku_code'=>$sku));
		$numrows = $this->db->get()->num_rows();
		if ($numrows > 0){
			return true;
		}else{
			return false;
		}
	}
	function get_shipping_zone_sku_code($shipping_zone_id){
		$sku_code = '';
		$this->db->from('shipping_zones');
		$this->db->where( array('shipping_zone_id'=>$shipping_zone_id));
		$result = $this->db->get()->result();
		foreach ($result as $r){
			$sku_code = $r->shipping_zone_sku_code;
		}

		if ($sku_code == ''){
			$sku_code = $this->get_shipping_zone_sku();
		}

		return $sku_code;
	}

	function get_shipping_zones_list(){

		$this->db->select('shipping_zones.*, countries.country_name, countries.country_code, countries.country_abbreviation, regions.region_name');
		$this->db->from('shipping_zones');
		$this->db->join('countries', 'countries.country_id = shipping_zones.country_id', 'left outer');
		$this->db->join('regions', 'regions.region_id = shipping_zones.region_id', 'left outer');

		$this->db->where( array('shipping_zones.is_deleted'=>0));
		$this->db->order_by("shipping_zones.shipping_zone_name", "asc");
		return $this->db->get()->result();
	}

	function get_shipping_zones_by_region_id($region_id){

		$this->db->select('shipping_zones.*, countries.country_name, countries.country_code, countries.country_abbreviation, regions.region_name');
		$this->db->from('shipping_zones');
		$this->db->join('countries', 'countries.country_id = shipping_zones.country_id', 'left outer');
		$this->db->join('regions', 'regions.region_id = shipping_zones.region_id', 'left outer');

		$this->db->where( array('shipping_zones.is_deleted'=>0, 'shipping_zones.region_id'=>$region_id));
		$this->db->order_by("shipping_zones.shipping_zone_name", "asc");

		return $this->db->get()->result();
	}

	function save_shipping_zone($data){
		$insert = $this->db->insert('shipping_zones', $data);
		$insert_id = $this->db->insert_id();
		if ($insert){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Shipping Zone added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Shipping Zone successfully. Please try again.');
		}
		return $arr_return;
	}
	function shipping_zone_exists($shipping_zone_name){
		$this->db->where('shipping_zone_name',$shipping_zone_name);
		$this->db->where('is_deleted',0);
		$query = $this->db->get('shipping_zones');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	function get_shipping_zone($shipping_zone_id){
		$this->db->from('shipping_zones');
		$this->db->where( array('shipping_zone_id'=>$shipping_zone_id));
		return $this->db->get()->result();
	}
	function get_shipping_zone2($shipping_zone_id){
		$this->db->from('shipping_zones');
		$this->db->where( array('shipping_zone_id'=>$shipping_zone_id));
		return $this->db->get()->result_array();
	}
	function get_shipping_zone_id_by_region_id($region_id){
		$shipping_zone_id = 0;		
		$this->db->from('regions');
		$this->db->where( array('region_id'=>$region_id));
		$result = $this->db->get()->result();

		foreach ($result as $row) {
			$shipping_zone_id = $row->shipping_zone_id;
		}

		return $shipping_zone_id;
	}
	function shipping_zone_update_exists($shipping_zone_id,$shipping_zone_name){
		$this->db->where('shipping_zone_id !=',$shipping_zone_id);
		$this->db->where('shipping_zone_name',$shipping_zone_name);
		$this->db->where('is_deleted',0);

		$q = $this->db->get('shipping_zones');

		if ($q->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function update_shipping_zone($data,$shipping_zone_id){
		$this->db->where(array('shipping_zone_id'=>$shipping_zone_id));
		$update = $this->db->update('shipping_zones', $data);
		if ($update){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Shipping Zone updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Shipping Zone successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete_shipping_zone($shipping_zone_id){
		$data = array(
			'is_deleted'=> 1
		);			
		$this->db->where( array('shipping_zone_id'=>$shipping_zone_id));
		$delupdate = $this->db->update('shipping_zones', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Shipping Zone deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Shipping Zone');
		}
		return $arr_return;
	}
	function delete_bulk_shipping_zones($ids){
		$msg_err = '';
		$msg_err2 = '';

		$d_ids = json_decode($ids);

		foreach($d_ids as $value) {
			$data = array(
				'is_deleted'=> 1
			);			
			$this->db->where( array('shipping_zone_id'=>$value));
			$res = $this->db->update('shipping_zones', $data);
			
			if ($res){}else{
				$msg_err2 = $msg_err2 . 'Error deleting shipping_zone';
			}
		}
		if ($msg_err == $msg_err2){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Bulk Transaction(s) completed successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Bulk Transaction(s) could not be completed successfully');
		}

		return $arr_return;
	}

	//PICKUP LOCATIONS
	function generate_pickup_location_sku($length = 6) {
    	$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
       	$randomString .= $characters[rand(0, strlen($characters) - 1)];
    	}
    	return $randomString;
	}	
	function get_pickup_location_sku(){
		$pickup_location_sku = $this->generate_pickup_location_sku();
		$checktrue = $this->check_pickup_location_sku_exists($pickup_location_sku);
		while ($checktrue == true){
			$pickup_location_sku = $this->generate_pickup_location_sku();
			$checktrue = $this->check_pickup_location_sku_exists($pickup_location_sku);
		}
		return $pickup_location_sku;
	}
	function check_pickup_location_sku_exists($sku){
		$this->db->from('pickup_locations');
		$this->db->where( array('pickup_location_sku_code'=>$sku));
		$numrows = $this->db->get()->num_rows();
		if ($numrows > 0){
			return true;
		}else{
			return false;
		}
	}
	function get_pickup_location_sku_code($pickup_location_id){
		$sku_code = '';
		$this->db->from('pickup_locations');
		$this->db->where( array('pickup_location_id'=>$pickup_location_id));
		$result = $this->db->get()->result();
		foreach ($result as $r){
			$sku_code = $r->pickup_location_sku_code;
		}

		if ($sku_code == ''){
			$sku_code = $this->get_pickup_location_sku();
		}

		return $sku_code;
	}

	function get_pickup_locations_list(){

		$this->db->select('pickup_locations.*, countries.country_name, countries.country_code, countries.country_abbreviation, regions.region_name');
		$this->db->from('pickup_locations');
		$this->db->join('countries', 'countries.country_id = pickup_locations.country_id', 'left outer');
		$this->db->join('regions', 'regions.region_id = pickup_locations.region_id', 'left outer');

		$this->db->where( array('pickup_locations.is_deleted'=>0));
		$this->db->order_by("pickup_locations.pickup_location_name", "asc");
		return $this->db->get()->result();
	}

	function get_pickup_locations_by_region_id($region_id){

		$this->db->select('pickup_locations.*, countries.country_name, countries.country_code, countries.country_abbreviation, regions.region_name');
		$this->db->from('pickup_locations');
		$this->db->join('countries', 'countries.country_id = pickup_locations.country_id', 'left outer');
		$this->db->join('regions', 'regions.region_id = pickup_locations.region_id', 'left outer');

		$this->db->where( array('pickup_locations.is_deleted'=>0, 'pickup_locations.region_id'=>$region_id));
		$this->db->order_by("pickup_locations.pickup_location_name", "asc");
		return $this->db->get()->result();
	}

	function save_pickup_location($data){
		$insert = $this->db->insert('pickup_locations', $data);
		$insert_id = $this->db->insert_id();
		if ($insert){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Pickup Location added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Pickup Location successfully. Please try again.');
		}
		return $arr_return;
	}
	function pickup_location_exists($pickup_location_name){
		$this->db->where('pickup_location_name',$pickup_location_name);
		$this->db->where('is_deleted',0);
		$query = $this->db->get('pickup_locations');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	function get_pickup_location($pickup_location_id){
		$this->db->from('pickup_locations');
		$this->db->where( array('pickup_location_id'=>$pickup_location_id));
		return $this->db->get()->result();
	}
	function get_pickup_location2($pickup_location_id){
		$this->db->from('pickup_locations');
		$this->db->where( array('pickup_location_id'=>$pickup_location_id));
		return $this->db->get()->result_array();
	}
	function get_pickup_location_id_by_region_id($region_id){
		$pickup_location_id = 0;		
		$this->db->from('regions');
		$this->db->where( array('region_id'=>$region_id));
		$result = $this->db->get()->result();

		foreach ($result as $row) {
			$pickup_location_id = $row->pickup_location_id;
		}

		return $pickup_location_id;
	}
	function pickup_location_update_exists($pickup_location_id,$pickup_location_name){
		$this->db->where('pickup_location_id !=',$pickup_location_id);
		$this->db->where('pickup_location_name',$pickup_location_name);
		$this->db->where('is_deleted',0);

		$q = $this->db->get('pickup_locations');

		if ($q->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function update_pickup_location($data,$pickup_location_id){
		$this->db->where(array('pickup_location_id'=>$pickup_location_id));
		$update = $this->db->update('pickup_locations', $data);
		if ($update){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Pickup Location updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Pickup Location successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete_pickup_location($pickup_location_id){
		$data = array(
			'is_deleted'=> 1
		);			
		$this->db->where( array('pickup_location_id'=>$pickup_location_id));
		$delupdate = $this->db->update('pickup_locations', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Pickup Location deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Pickup Location');
		}
		return $arr_return;
	}
	function delete_bulk_pickup_locations($ids){
		$msg_err = '';
		$msg_err2 = '';

		$d_ids = json_decode($ids);

		foreach($d_ids as $value) {
			$data = array(
				'is_deleted'=> 1
			);			
			$this->db->where( array('pickup_location_id'=>$value));
			$res = $this->db->update('pickup_locations', $data);
			
			if ($res){}else{
				$msg_err2 = $msg_err2 . 'Error deleting pickup_location';
			}
		}
		if ($msg_err == $msg_err2){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Bulk Transaction(s) completed successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Bulk Transaction(s) could not be completed successfully');
		}

		return $arr_return;
	}




	//LOCALITIES
	function generate_locality_sku($length = 6) {
    	$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
       	$randomString .= $characters[rand(0, strlen($characters) - 1)];
    	}
    	return $randomString;
	}	
	function get_locality_sku(){
		$locality_sku = $this->generate_locality_sku();
		$checktrue = $this->check_locality_sku_exists($locality_sku);
		while ($checktrue == true){
			$locality_sku = $this->generate_locality_sku();
			$checktrue = $this->check_locality_sku_exists($locality_sku);
		}
		return $locality_sku;
	}
	function check_locality_sku_exists($sku){
		$this->db->from('localities');
		$this->db->where( array('locality_sku_code'=>$sku));
		$numrows = $this->db->get()->num_rows();
		if ($numrows > 0){
			return true;
		}else{
			return false;
		}
	}
	function get_locality_sku_code($locality_id){
		$sku_code = '';
		$this->db->from('localities');
		$this->db->where( array('locality_id'=>$locality_id));
		$result = $this->db->get()->result();
		foreach ($result as $r){
			$sku_code = $r->locality_sku_code;
		}

		if ($sku_code == ''){
			$sku_code = $this->get_locality_sku();
		}

		return $sku_code;
	}

	function get_localities_list($region_id){
		$this->db->from('localities');
		$this->db->where( array('is_deleted'=>0, 'region_id'=>$region_id));
		return $this->db->get()->result();
	}

	function save_locality($data){
		$insert = $this->db->insert('localities', $data);
		$insert_id = $this->db->insert_id();
		if ($insert){
			$arr_return = array('res' => true,'dt' => 'Locality added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not add Locality successfully. Please try again.');
		}
		return $arr_return;
	}
	function locality_exists($region_id, $locality_name){
		$this->db->where('locality_name',$locality_name);
		$this->db->where('region_id',$region_id);
		$this->db->where('is_deleted',0);
		$query = $this->db->get('localities');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}
	}
	function get_locality($locality_id){
		$this->db->from('localities');
		$this->db->where( array('locality_id'=>$locality_id));
		return $this->db->get()->result();
	}
	function get_locality2($locality_id){
		$this->db->from('localities');
		$this->db->where( array('locality_id'=>$locality_id));
		return $this->db->get()->result_array();
	}
	function locality_update_exists($region_id,$locality_id,$locality_name){
		$this->db->where('locality_id !=',$locality_id);
		$this->db->where('region_id',$region_id);
		$this->db->where('locality_name',$locality_name);
		$this->db->where('is_deleted',0);

		$q = $this->db->get('localities');

		if ($q->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function update_locality($data,$locality_id){
		$this->db->where(array('locality_id'=>$locality_id));
		$update = $this->db->update('localities', $data);
		if ($update){
			$arr_return = array('res' => true,'dt' => 'Locality updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not update Locality successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete_locality($locality_id){
		$data = array(
			'is_deleted'=> 1
		);			
		$this->db->where( array('locality_id'=>$locality_id));
		$delupdate = $this->db->update('localities', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'Locality deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => 'Error deleting Locality');
		}
		return $arr_return;
	}
	function delete_bulk_localities($ids){
		$msg_err = '';
		$msg_err2 = '';

		$d_ids = json_decode($ids);

		foreach($d_ids as $value) {
			$data = array(
				'is_deleted'=> 1
			);			
			$this->db->where( array('locality_id'=>$value));
			$res = $this->db->update('localities', $data);
			
			if ($res){}else{
				$msg_err2 = $msg_err2 . 'Error deleting locality';
			}
		}
		if ($msg_err == $msg_err2){
			$arr_return = array('res' => true,'dt'=>'Bulk Transaction(s) completed successfully');
		}else{
			$arr_return = array('res' => false,'dt' => 'Bulk Transaction(s) could not be completed successfully');
		}

		return $arr_return;
	}
	function get_localities_by_country($country_id){
		$this->db->select('lo.*, re.region_id, re.region_name, co.country_id, co.country_name, co.country_abbreviation');
		$this->db->from('localities lo');
		$this->db->join('regions re', 're.region_id = lo.region_id');
		$this->db->join('countries co', 'co.country_id = lo.country_id');
		$this->db->where( array('lo.is_deleted'=>0, 'lo.country_id'=>$country_id));
		$this->db->order_by("re.region_name", "asc");
		$this->db->order_by("lo.locality_name", "asc");
		return $this->db->get()->result();
	}

}