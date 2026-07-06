<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Locations extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/locations_model');
	}
	function index(){
		redirect('be/locations/countries');
	}

	//COUNTRIES

	function countries() {
		if($this->session->userdata('bgs_be_active')) {
			$data['cur'] = 'Locations';
			$data['cur_sub'] = 'Countries';
			$data['cur_cur_sub'] = '';

			$data['page_title'] = 'Countries | ';
			$data['main_content'] = 'be/countries';
			$this->load->view('be/includes/template',$data);
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_country(){
		$country_sku = $this->locations_model->get_country_sku();
		$country_reference_id = url_title($this->input->post('country_name'),'-',TRUE) . '-' . strtolower($country_sku);

		$data = array(
			'country_reference_id' => $country_reference_id,
			'country_sku_code' => $country_sku,			
			'country_name' => $this->input->post('country_name'),
			'country_abbreviation' => $this->input->post('country_abbreviation'),
			'nationality' => $this->input->post('nationality'),
			'country_code' => $this->input->post('country_code'),
			'created_on' => date("Y-m-d H:i:s", time())
		);	
		$country_name = $this->input->post('country_name');
		if($this->locations_model->country_exists($country_name) == false){
			$q = $this->locations_model->save_country($data);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'This Country has already been defined');
		}
			
		echo json_encode($resp);

	}
	function loadjs_countries(){
		$data['countries'] = $this->locations_model->get_countries_list();
		$this->load->view('be/jsloads/countries',$data);
	}
	function get_country($country_id){
		$country = $this->locations_model->get_country($country_id);
		echo json_encode($country);
	}
	function get_country2($country_id){
		$country = $this->locations_model->get_country2($country_id);
		echo json_encode($country);
	}
	function update_country(){
		$country_id = $this->input->post('country_id');
		$country_name = $this->input->post('country_name');

		$country_sku = $this->locations_model->get_country_sku_code($country_id);		
		$country_reference_id = url_title($this->input->post('country_name'),'-',TRUE) . '-' . strtolower($country_sku);

		$data = array(
			'country_reference_id'		=> $country_reference_id,
            'country_sku_code'			=> $country_sku,			
			'country_name' => $this->input->post('country_name'),
			'country_abbreviation' => $this->input->post('country_abbreviation'),
			'nationality' => $this->input->post('nationality'),
			'country_code' => $this->input->post('country_code')
		);	
		if($this->locations_model->country_update_exists($country_id,$country_name) == false){
			$q = $this->locations_model->update_country($data,$country_id);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'This Country has already been defined');
		}
		echo json_encode($resp);

	}
	function delete_country($country_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->locations_model->delete_country($country_id);
			if($q['res'] == TRUE){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);			
			}else{					
				$resp = array('status' => 'ERR','message' => $q['dt']);			
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);

	}
	function delete_bulk_countries() {
		if($this->session->userdata('bgs_be_active')){
			$ids = $this->input->post('ids');
			$q = $this->locations_model->delete_bulk_countries($ids);
			if($q['res'] == TRUE){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);			
			}else{					
				$resp = array('status' => 'ERR','message' => $q['dt']);			
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);
	}

	//REGIONS
	function regions($country_id) {
		if($this->session->userdata('bgs_be_active')) {
			$data['cur'] = 'Locations';
			$data['cur_sub'] = 'Regions';
			$data['cur_cur_sub'] = '';

			$data['country_id'] = $country_id;
			$data['country'] = $this->locations_model->get_country($country_id);

			$data['page_title'] = 'Regions | ';
			$data['main_content'] = 'be/regions';
			$this->load->view('be/includes/template',$data);
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_region(){
		$region_sku = $this->locations_model->get_region_sku();
		$region_reference_id = url_title($this->input->post('region_name'),'-',TRUE) . '-' . strtolower($region_sku);

		$data = array(
			'region_reference_id' => $region_reference_id,
			'region_sku_code' => $region_sku,			
			'region_name' => $this->input->post('region_name'),
			'sort_key' => $this->input->post('sort_key'),
			'country_id' => $this->input->post('country_id'),
			'created_on' => date("Y-m-d H:i:s", time())
		);	
		$region_name = $this->input->post('region_name');
		$country_id = $this->input->post('country_id');
		if($this->locations_model->region_exists($country_id, $region_name) == false){
			$q = $this->locations_model->save_region($data);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'This region has already been defined');
		}
			
		echo json_encode($resp);

	}
	function loadjs_regions($country_id){
		$data['regions'] = $this->locations_model->get_regions_list($country_id);
		$this->load->view('be/jsloads/regions',$data);
	}
	function get_region($region_id){
		$region = $this->locations_model->get_region($region_id);
		echo json_encode($region);
	}
	function get_region2($region_id){
		$region = $this->locations_model->get_region2($region_id);
		echo json_encode($region);
	}
	function update_region(){
		$region_id = $this->input->post('region_id');
		$region_name = $this->input->post('region_name');
		$country_id = $this->input->post('country_id');

		$region_sku = $this->locations_model->get_region_sku_code($region_id);		
		$region_reference_id = url_title($this->input->post('region_name'),'-',TRUE) . '-' . strtolower($region_sku);

		$data = array(
			'region_reference_id'		=> $region_reference_id,
            'region_sku_code'			=> $region_sku,			
			'region_name' => $this->input->post('region_name'),
			'sort_key' => $this->input->post('sort_key'),
			'country_id' => $this->input->post('country_id')
		);	
		if($this->locations_model->region_update_exists($country_id,$region_id,$region_name) == false){
			$q = $this->locations_model->update_region($data,$region_id);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'This region has already been defined');
		}
		echo json_encode($resp);

	}
	function delete_region($region_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->locations_model->delete_region($region_id);
			if($q['res'] == TRUE){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);			
			}else{					
				$resp = array('status' => 'ERR','message' => $q['dt']);			
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);

	}
	function delete_bulk_regions() {
		if($this->session->userdata('bgs_be_active')){
			$ids = $this->input->post('ids');
			$q = $this->locations_model->delete_bulk_regions($ids);
			if($q['res'] == TRUE){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);			
			}else{					
				$resp = array('status' => 'ERR','message' => $q['dt']);			
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);
	}
	function get_regions_by_country($country_id){
		$regions = $this->locations_model->get_regions_by_country($country_id);
		echo json_encode($regions);
	}

	//LOCALITIES
	function localities($region_id) {
		if($this->session->userdata('bgs_be_active')) {
			$data['cur'] = 'Locations';
			$data['cur_sub'] = 'Localities';
			$data['cur_cur_sub'] = '';

			$data['region_id'] = $region_id;
			$data['region'] = $this->locations_model->get_region($region_id);

			$country_id = $this->locations_model->get_country_id_by_region_id($region_id);

			$data['country_id'] = $country_id;
			$data['country'] = $this->locations_model->get_country($country_id);

			$data['page_title'] = 'Localities | ';
			$data['main_content'] = 'be/localities';
			$this->load->view('be/includes/template',$data);
        } 
		else {
            redirect('be/auth');
		}
	}
	function save_locality(){
		$locality_sku = $this->locations_model->get_locality_sku();
		$locality_reference_id = url_title($this->input->post('locality_name'),'-',TRUE) . '-' . strtolower($locality_sku);

		$data = array(
			'locality_reference_id' => $locality_reference_id,
			'locality_sku_code' => $locality_sku,			
			'locality_name' => $this->input->post('locality_name'),
			'sort_key' => $this->input->post('sort_key'),
			'country_id' => $this->input->post('country_id'),
			'region_id' => $this->input->post('region_id'),
			'created_on' => date("Y-m-d H:i:s", time())
		);	
		$locality_name = $this->input->post('locality_name');
		$region_id = $this->input->post('region_id');
		if($this->locations_model->locality_exists($region_id, $locality_name) == false){
			$q = $this->locations_model->save_locality($data);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'This locality has already been defined');
		}
			
		echo json_encode($resp);

	}
	function loadjs_localities($region_id){
		$data['localities'] = $this->locations_model->get_localities_list($region_id);
		$this->load->view('be/jsloads/localities',$data);
	}
	function get_locality($locality_id){
		$locality = $this->locations_model->get_locality($locality_id);
		echo json_encode($locality);
	}
	function get_locality2($locality_id){
		$locality = $this->locations_model->get_locality2($locality_id);
		echo json_encode($locality);
	}
	function update_locality(){
		$locality_id = $this->input->post('locality_id');
		$locality_name = $this->input->post('locality_name');
		$region_id = $this->input->post('region_id');

		$locality_sku = $this->locations_model->get_locality_sku_code($locality_id);		
		$locality_reference_id = url_title($this->input->post('locality_name'),'-',TRUE) . '-' . strtolower($locality_sku);

		$data = array(
			'locality_reference_id'		=> $locality_reference_id,
            'locality_sku_code'			=> $locality_sku,			
			'locality_name' => $this->input->post('locality_name'),
			'sort_key' => $this->input->post('sort_key'),
			'country_id' => $this->input->post('country_id'),
			'region_id' => $this->input->post('region_id')
		);	
		if($this->locations_model->locality_update_exists($region_id,$locality_id,$locality_name) == false){
			$q = $this->locations_model->update_locality($data,$locality_id);
			if ($q['res'] == true){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);
			}else{
				$resp = array('status' => 'ERR','message' => $q['dt']);
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'This locality has already been defined');
		}
		echo json_encode($resp);

	}
	function delete_locality($locality_id){
		if($this->session->userdata('bgs_be_active')){
			$q = $this->locations_model->delete_locality($locality_id);
			if($q['res'] == TRUE){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);			
			}else{					
				$resp = array('status' => 'ERR','message' => $q['dt']);			
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);

	}
	function delete_bulk_localities() {
		if($this->session->userdata('bgs_be_active')){
			$ids = $this->input->post('ids');
			$q = $this->locations_model->delete_bulk_localities($ids);
			if($q['res'] == TRUE){
				$resp = array('status' => 'SUCCESS','message' => $q['dt']);			
			}else{					
				$resp = array('status' => 'ERR','message' => $q['dt']);			
			}
		}else{
			$resp = array('status' => 'ERR','message' => 'Your session seems to have expired. Please login again to continue');			
    	}
		echo json_encode($resp);
	}
	function get_localities_by_country($country_id){
		$localities = $this->locations_model->get_localities_by_country($country_id);
		echo json_encode($localities);
	}


}