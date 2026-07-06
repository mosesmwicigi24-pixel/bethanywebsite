<?php
class Products_model extends CI_Model {

	function __construct(){
		parent::__construct();		
		$this->load->model('be/outlets_model');
	}
	
	function get_products_list(){
		$this->db->select("p.*, b.brand_name, u.unit_code, u.unit_name, (SELECT GROUP_CONCAT(pca.product_category_name SEPARATOR ',')) AS 'product_category_name'");
		$this->db->from('products p');
		$this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
		$this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');
		$this->db->join('product_product_categories ppca', 'ppca.product_id = p.product_id', 'left outer');
		$this->db->join('product_categories pca', 'pca.product_category_id = ppca.product_category_id', 'left outer');

		$this->db->where( array('p.is_deleted'=>0, 'p.is_draft'=>0));
		$this->db->group_by('p.product_id');
		return $this->db->get()->result();
	}
	function generate_product_sku($length = 9) {
    	$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
       	$randomString .= $characters[rand(0, strlen($characters) - 1)];
    	}
    	return $randomString;
	}
	
	function get_product_sku(){
		$product_sku = $this->generate_product_sku();
		$checktrue = $this->check_sku_exists($product_sku);
		while ($checktrue == true){
			$product_sku = $this->generate_product_sku();
			$checktrue = $this->check_sku_exists($product_sku);
		}
		return $product_sku;
	}
	function check_sku_exists($sku){
		$this->db->from('products');
		$this->db->where( array('product_sku_code'=>$sku));
		$numrows = $this->db->get()->num_rows();
		if ($numrows > 0){
			return true;
		}else{
			return false;
		}
	}
	function get_product_sku_code($product_id){
		$sku_code = '';
		$this->db->from('products');
		$this->db->where( array('product_id'=>$product_id));
		$result = $this->db->get()->result();
		foreach ($result as $r){
			$sku_code = $r->product_sku_code;
		}

		if ($sku_code == ''){
			$sku_code = $this->get_product_sku();
		}

		return $sku_code;
	}
	function get_unit($unit_id){
		$this->db->from('units');
		$this->db->where( array('unit_id'=>$unit_id));
		return $this->db->get()->result();
	}
	function get_add_product_related_units($unit_id){
		$unit_type_id = 0;
		$unit = $this->get_unit($unit_id);
		foreach ($unit as $row) {
			$unit_type_id = $row->unit_type_id;
		}
		$this->db->select("u.*, ur.conversion_factor, '0' as unit_related_id, '' as product_conversion_factor, '0.00' as unit_price, '0.00' as unit_minimum_selling_price, '1' as universal_prices");
		$this->db->from('units u');
		$this->db->join('units_related ur', 'ur.related_unit_id = u.unit_id AND ur.unit_id = ' . $unit_id, 'left outer');
		$this->db->where( array('u.is_deleted'=>0, 'u.unit_type_id'=>$unit_type_id));
		$this->db->where( array('u.unit_id != '=>$unit_id));
		$this->db->group_by('u.unit_id');
		$product_related_units = $this->db->get()->result();

		$i = 0;
        foreach ($product_related_units as $row) {
        	if ($row->unit_related_id != null){
            	$product_related_units[$i]->outlet_prices = $this->get_product_related_units_outlet_prices($row->unit_related_id);
            }
            $i++;
        }
        return $product_related_units;
	}
	function get_num_add_product_related_units($unit_id){
		$unit_type_id = 0;
		$unit = $this->get_unit($unit_id);
		foreach ($unit as $row) {
			$unit_type_id = $row->unit_type_id;
		}
		$this->db->select("u.*, ur.conversion_factor, '0' as unit_related_id, '' as product_conversion_factor, '0.00' as unit_price, '0.00' as unit_minimum_selling_price, '1' as universal_prices");
		$this->db->from('units u');
		$this->db->join('units_related ur', 'ur.related_unit_id = u.unit_id AND ur.unit_id = ' . $unit_id, 'left outer');
		$this->db->where( array('u.is_deleted'=>0, 'u.unit_type_id'=>$unit_type_id));
		$this->db->where( array('u.unit_id != '=>$unit_id));
		$this->db->group_by('u.unit_id');
		// return $this->db->get()->result();
		return $this->db->count_all_results();
	}
	function get_edit_product_related_units($unit_id, $product_id){
		$unit_type_id = 0;
		$unit = $this->get_unit($unit_id);
		foreach ($unit as $row) {
			$unit_type_id = $row->unit_type_id;
		}
		$this->db->select("u.*, ur.conversion_factor, pur.unit_related_id, pur.conversion_factor AS 'product_conversion_factor', IFNULL(pur.unit_price,0) AS unit_price, IFNULL(pur.unit_minimum_selling_price,0) AS unit_minimum_selling_price, IFNULL(pur.universal_prices,1) AS universal_prices");
		$this->db->from('units u');
		$this->db->join('units_related ur', 'ur.related_unit_id = u.unit_id AND ur.unit_id = ' . $unit_id, 'left outer');
		$this->db->join('product_related_units pur', 'pur.related_unit_id = u.unit_id AND pur.product_id = ' . $product_id, 'left outer');
		$this->db->where( array('u.is_deleted'=>0, 'u.unit_type_id'=>$unit_type_id));
		$this->db->where( array('u.unit_id != '=>$unit_id));
		$this->db->group_by('u.unit_id');

		$product_related_units = $this->db->get()->result();

		$i = 0;
        foreach ($product_related_units as $row) {
        	if ($row->unit_related_id != null){
            	$product_related_units[$i]->outlet_prices = $this->get_product_related_units_outlet_prices($row->unit_related_id);
            }
            $i++;
        }

        return $product_related_units;
	}
	function get_num_edit_product_related_units($unit_id, $product_id){
		$unit_type_id = 0;
		$unit = $this->get_unit($unit_id);
		foreach ($unit as $row) {
			$unit_type_id = $row->unit_type_id;
		}
		$this->db->select("u.*, ur.conversion_factor, ur.related_unit_id, pur.conversion_factor AS 'product_conversion_factor', IFNULL(pur.unit_price,0) AS unit_price, IFNULL(pur.unit_minimum_selling_price,0) AS unit_minimum_selling_price, IFNULL(pur.universal_prices,1) AS universal_prices");
		$this->db->from('units u');
		$this->db->join('units_related ur', 'ur.related_unit_id = u.unit_id AND ur.unit_id = ' . $unit_id, 'left outer');
		$this->db->join('product_related_units pur', 'pur.related_unit_id = u.unit_id AND pur.product_id = ' . $product_id, 'left outer');
		$this->db->where( array('u.is_deleted'=>0, 'u.unit_type_id'=>$unit_type_id));
		$this->db->where( array('u.unit_id != '=>$unit_id));
		$this->db->group_by('u.unit_id');
		return $this->db->count_all_results();
	}
	function get_product_related_units_outlet_prices($unit_related_id) {
		$this->db->select("pruop.*");
		$this->db->from('product_related_units_outlet_prices pruop');
		$this->db->where( array('pruop.unit_related_id' => $unit_related_id));

		return $this->db->get()->result();

	}
	function product_exists(){
		$product_barcode = $this->input->post('product_barcode');

    	$msg = '';
    	$msg2 = '';

		if ($product_barcode != '' && $product_barcode != null){
	    	//PRODUCT BARCODE
	    	$this->db->where(array('product_barcode' => $product_barcode, 'is_deleted' => 0));
			$query = $this->db->get('products');

			if ($query->num_rows() > 0){
				$msg = '<i class="icon-cancel-circle2"></i> Duplicate Product Barcode: The Product Barcode you entered has already been defined.<br>';
			}
		}

		if ($msg == $msg2){
			$arr_return = array('res' => true,'dt' => '');
		}else{
			$arr_return = array('res' => false,'dt' => $msg);
		}

		return $arr_return;

	}

	function draft_exists() {
		$this->db->select("*");
        $this->db->from('products');       
        $this->db->where( array('is_deleted' => 0,'is_draft' => 1, 'created_by' => $this->session->userdata('system_user_id')));
        $this->db->order_by("product_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {

        	$records = $query->result();
            $product_id = 0;

            foreach ($records as $row) {
                $product_id = $row->product_id;
            }
            $arr_return = array('res' => true,'product_id' => $product_id);
        } else {
        	$arr_return = array('res' => false,'product_id' => '');
        }
        return $arr_return;
	}

	function autosave(){

		$this->db->select("*");
        $this->db->from('products');       
        $this->db->where( array('is_deleted' => 0,'is_draft' => 1, 'created_by' => $this->session->userdata('system_user_id')));
        $this->db->order_by("product_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {

        	$records = $query->result();
            $product_id = 0;

            foreach ($records as $row) {
                $product_id = $row->product_id;
            }

            $q = $this->products_model->product_update_exists();

			if($q['res'] == true){
				$product_barcode = $this->input->post('product_barcode');
			} else {
				$product_barcode = '';
			}

			$product_sku = $this->get_product_sku_code($product_id);		
			$product_reference_id = url_title($this->input->post('product_name'),'-',TRUE) . '-' . strtolower($product_sku);

			if($this->input->post('negative_inventory') == 'on'){
				$negative_inventory = 1;
			} else {
				$negative_inventory = 0;
			}

			if ($this->input->post('chk_product_outlet_regular_prices') == 'on') {
				$universal_regular_price = 1;
			} else {
				$universal_regular_price = 0;
			}

			if ($this->input->post('chk_product_outlet_sale_prices') == 'on') {
				$universal_sale_price = 1;
			} else {
				$universal_sale_price = 0;
			}

			if ($this->input->post('chk_product_outlet_minimum_prices') == 'on') {
				$universal_minimum_price = 1;
			} else {
				$universal_minimum_price = 0;
			}

			$product_type = $this->input->post('product_type');

			if ($product_type == ''){
				$product_type = 'Simple';
			} else {
				$product_type = $this->input->post('product_type');
			}
			
			$regular_price = $this->input->post('regular_price');
			$sale_price = $this->input->post('sale_price');
			$minimum_selling_price = $this->input->post('minimum_selling_price');			

			$data = array(
				'product_reference_id' => $product_reference_id,
				'product_sku_code' => $product_sku,
				'product_type' => $product_type,			
				'product_name' => $this->input->post('product_name'),
				'product_barcode' => $product_barcode,
				'product_short_description' => $this->input->post('product_short_description'),
				'product_description' => $this->input->post('product_description'),
				'tax_rate_id' => $this->input->post('tax_rate_id'),
				'brand_id' => $this->input->post('brand_id'),
				'unit_id' => $this->input->post('unit_id'),
				'regular_price' => $regular_price,
				'sale_price' => $sale_price,
				'minimum_selling_price' => $minimum_selling_price,
				'negative_inventory' => $negative_inventory,
				'universal_regular_price' => $universal_regular_price,
				'universal_sale_price' => $universal_sale_price,
				'universal_minimum_price' => $universal_minimum_price,		
				'is_featured' => $this->input->post('is_featured'),
				'is_new_arrival' => $this->input->post('is_new_arrival'),
				'is_special_offer' => $this->input->post('is_special_offer'),
				'is_deal_of_the_week' => $this->input->post('is_deal_of_the_week'),
				'is_online' => $this->input->post('is_online'),
				'seo_description' => $this->input->post('seo_description'),
				'seo_keywords' => $this->input->post('seo_keywords')
			);

			$this->db->where(array('product_id'=>$product_id));
			$update = $this->db->update('products', $data);
			if ($update){

				//RELATED UNITS
				$this->update_product_related_units($product_id);

				//PRODUCT CATEGORIES
				// if ($this->input->post('product_category_id') != ''){
					$this->update_product_categories($product_id);
				// }

				//SUPPLIERS
				// if ($this->input->post('supplier_id') != ''){
					$this->update_product_suppliers($product_id);
				// }

				//OUTLET PRODUCTS
				$this->save_outlet_products($product_id);

				//OUTLET PRICES
				$this->save_outlet_prices($product_id);

				//STOCK TRACKER
				$this->save_product_stock_tracker($product_id);

				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Product updated successfully.','product_id' => $product_id);
			}else{
				$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Product successfully. Please try again.','product_id' => $product_id);
			}
        } else {
        	$q = $this->product_exists();

			if($q['res'] == true){
				$product_barcode = $this->input->post('product_barcode');
			} else {
				$product_barcode = '';
			}

			$product_sku = $this->get_product_sku();
			$product_reference_id = url_title($this->input->post('product_name'),'-',TRUE) . '-' . strtolower($product_sku);

			if($this->input->post('negative_inventory') == 'on'){
				$negative_inventory = 1;
			} else {
				$negative_inventory = 0;
			}

			if ($this->input->post('chk_product_outlet_regular_prices') == 'on') {
				$universal_regular_price = 1;
			} else {
				$universal_regular_price = 0;
			}

			if ($this->input->post('chk_product_outlet_sale_prices') == 'on') {
				$universal_sale_price = 1;
			} else {
				$universal_sale_price = 0;
			}

			if ($this->input->post('chk_product_outlet_minimum_prices') == 'on') {
				$universal_minimum_price = 1;
			} else {
				$universal_minimum_price = 0;
			}

			$product_type = $this->input->post('product_type');

			if ($product_type == ''){
				$product_type = 'Simple';
			} else {
				$product_type = $this->input->post('product_type');
			}

			$regular_price = $this->input->post('regular_price');
			$sale_price = $this->input->post('sale_price');
			$minimum_selling_price = $this->input->post('minimum_selling_price');

			$data = array(
				'product_reference_id' => $product_reference_id,
				'product_sku_code' => $product_sku,
				'product_type' => $product_type,			
				'product_name' => $this->input->post('product_name'),
				'product_barcode' => $product_barcode,
				'product_short_description' => $this->input->post('product_short_description'),
				'product_description' => $this->input->post('product_description'),
				'tax_rate_id' => $this->input->post('tax_rate_id'),
				'brand_id' => $this->input->post('brand_id'),
				'unit_id' => $this->input->post('unit_id'),
				'regular_price' => $regular_price,
				'sale_price' => $sale_price,
				'minimum_selling_price' => $minimum_selling_price,
				'universal_regular_price' => $universal_regular_price,
				'universal_sale_price' => $universal_sale_price,
				'universal_minimum_price' => $universal_minimum_price,
				'negative_inventory' => $negative_inventory,		
				'is_featured' => $this->input->post('is_featured'),
				'is_new_arrival' => $this->input->post('is_new_arrival'),
				'is_special_offer' => $this->input->post('is_special_offer'),
				'is_deal_of_the_week' => $this->input->post('is_deal_of_the_week'),
				'is_online' => $this->input->post('is_online'),
				'seo_description' => $this->input->post('seo_description'),
				'seo_keywords' => $this->input->post('seo_keywords'),
				'created_by' => $this->session->userdata('system_user_id'),
				'created_on' => date("Y-m-d H:i:s", time())
			);

			$insert = $this->db->insert('products', $data);
			$insert_id = $this->db->insert_id();
			if ($insert){

				//RELATED UNITS
				$this->save_product_related_units($insert_id);

				//PRODUCT CATEGORIES
				//if ($this->input->post('product_category_id') != ''){
					$this->save_product_categories($insert_id);
				//}

				//PRODUCT SUPPLIERS
				//if ($this->input->post('supplier_id') != ''){
					$this->save_product_suppliers($insert_id);
				//}

				//OUTLET PRODUCTS
				$this->save_outlet_products($insert_id);

				//OUTLET PRICES
				$this->save_outlet_prices($insert_id);

				//STOCK TRACKER
				$this->save_product_stock_tracker($insert_id);

				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Product added successfully.','product_id' => $insert_id);
			}else{
				$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Product successfully. Please try again.','product_id' => '');
			}
        }
		return $arr_return;
	}

	function save(){

		$this->db->select("*");
        $this->db->from('products');       
        $this->db->where( array('is_deleted' => 0,'is_draft' => 1, 'created_by' => $this->session->userdata('system_user_id')));
        $this->db->order_by("product_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {

        	$records = $query->result();
            $product_id = 0;

            foreach ($records as $row) {
                $product_id = $row->product_id;
            }


            $q = $this->products_model->product_update_exists();

			if($q['res'] == true){

				$product_type = $this->input->post('product_type');

				$product_barcode = $this->input->post('product_barcode');

				$product_sku = $this->get_product_sku_code($product_id);		
				$product_reference_id = url_title($this->input->post('product_name'),'-',TRUE) . '-' . strtolower($product_sku);

				if($this->input->post('negative_inventory') == 'on'){
					$negative_inventory = 1;
				} else {
					$negative_inventory = 0;
				}	

				if ($this->input->post('chk_product_outlet_regular_prices') == 'on') {
					$universal_regular_price = 1;
				} else {
					$universal_regular_price = 0;
				}

				if ($this->input->post('chk_product_outlet_sale_prices') == 'on') {
					$universal_sale_price = 1;
				} else {
					$universal_sale_price = 0;
				}

				if ($this->input->post('chk_product_outlet_minimum_prices') == 'on') {
					$universal_minimum_price = 1;
				} else {
					$universal_minimum_price = 0;
				}		
				
				$regular_price = $this->input->post('regular_price');
				$sale_price = $this->input->post('sale_price');
				$minimum_selling_price = $this->input->post('minimum_selling_price');			

				$data = array(
					'product_reference_id' => $product_reference_id,
					'product_sku_code' => $product_sku,
					'product_type' => $product_type,			
					'product_name' => $this->input->post('product_name'),
					'product_barcode' => $product_barcode,
					'product_short_description' => $this->input->post('product_short_description'),
					'product_description' => $this->input->post('product_description'),
					'tax_rate_id' => $this->input->post('tax_rate_id'),
					'brand_id' => $this->input->post('brand_id'),
					'unit_id' => $this->input->post('unit_id'),
					'regular_price' => $regular_price,
					'sale_price' => $sale_price,
					'minimum_selling_price' => $minimum_selling_price,
					'universal_regular_price' => $universal_regular_price,
					'universal_sale_price' => $universal_sale_price,
					'universal_minimum_price' => $universal_minimum_price,
					'negative_inventory' => $negative_inventory,		
					'is_featured' => $this->input->post('is_featured'),
					'is_new_arrival' => $this->input->post('is_new_arrival'),
					'is_special_offer' => $this->input->post('is_special_offer'),
					'is_deal_of_the_week' => $this->input->post('is_deal_of_the_week'),
					'is_online' => $this->input->post('is_online'),
					'seo_description' => $this->input->post('seo_description'),
					'seo_keywords' => $this->input->post('seo_keywords'),
					'is_draft' => 0
				);


				if ($product_type == '' || $product_type == 'Simple'){
					$this->db->where(array('product_id'=>$product_id));
					$update = $this->db->update('products', $data);
					if ($update){

						//RELATED UNITS
						$this->update_product_related_units($product_id);

						//PRODUCT CATEGORIES
						$this->update_product_categories($product_id);

						//SUPPLIERS
						$this->update_product_suppliers($product_id);

						//OUTLET PRODUCTS
						$this->save_outlet_products($product_id);

						//OUTLET PRICES
						$this->save_outlet_prices($product_id);

						//STOCK TRACKER
						$this->save_product_stock_tracker($product_id);

						$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Product published successfully.','product_id' => $product_id);
					}else{
						$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not publish Product successfully. Please try again.','product_id' => $product_id);
					}
				} else {
					$num_product_variations = $this->get_num_product_variations($product_id);
					if ($num_product_variations > 0){
						$this->db->where(array('product_id'=>$product_id));
					$update = $this->db->update('products', $data);
					if ($update){

						//RELATED UNITS
						$this->update_product_related_units($product_id);

						//PRODUCT CATEGORIES
						$this->update_product_categories($product_id);

						//SUPPLIERS
						$this->update_product_suppliers($product_id);

						//OUTLET PRODUCTS
						$this->save_outlet_products($product_id);

						//OUTLET PRICES
						$this->save_outlet_prices($product_id);

						//STOCK TRACKER
						$this->save_product_stock_tracker($product_id);

						$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Product published successfully.','product_id' => $product_id);
					}else{
						$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not publish Product successfully. Please try again.','product_id' => $product_id);
					}
					} else {
						$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Please add product variations first before proceeding. Please try again.','product_id' => '');
					}
				}				
			} else {
				$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Duplicate Barcode. Please enter a different barcode and try again.','product_id' => $product_id);
			}			
        } else {

        	$product_type = $this->input->post('product_type');

			if ($product_type == '' || $product_type == 'Simple'){
				$product_type = 'Simple';

				$q = $this->product_exists();

				if($q['res'] == true){
					$product_barcode = $this->input->post('product_barcode');
				} else {
					$product_barcode = '';
				}

				$product_sku = $this->get_product_sku();
				$product_reference_id = url_title($this->input->post('product_name'),'-',TRUE) . '-' . strtolower($product_sku);

				if($this->input->post('negative_inventory') == 'on'){
					$negative_inventory = 1;
				} else {
					$negative_inventory = 0;
				}

				if ($this->input->post('chk_product_outlet_regular_prices') == 'on') {
					$universal_regular_price = 1;
				} else {
					$universal_regular_price = 0;
				}

				if ($this->input->post('chk_product_outlet_sale_prices') == 'on') {
					$universal_sale_price = 1;
				} else {
					$universal_sale_price = 0;
				}

				if ($this->input->post('chk_product_outlet_minimum_prices') == 'on') {
					$universal_minimum_price = 1;
				} else {
					$universal_minimum_price = 0;
				}			

				$regular_price = $this->input->post('regular_price');
				$sale_price = $this->input->post('sale_price');
				$minimum_selling_price = $this->input->post('minimum_selling_price');

				$data = array(
					'product_reference_id' => $product_reference_id,
					'product_sku_code' => $product_sku,
					'product_type' => $product_type,			
					'product_name' => $this->input->post('product_name'),
					'product_barcode' => $product_barcode,
					'product_short_description' => $this->input->post('product_short_description'),
					'product_description' => $this->input->post('product_description'),
					'tax_rate_id' => $this->input->post('tax_rate_id'),
					'brand_id' => $this->input->post('brand_id'),
					'unit_id' => $this->input->post('unit_id'),
					'regular_price' => $regular_price,
					'sale_price' => $sale_price,
					'minimum_selling_price' => $minimum_selling_price,
					'universal_regular_price' => $universal_regular_price,
					'universal_sale_price' => $universal_sale_price,
					'universal_minimum_price' => $universal_minimum_price,
					'negative_inventory' => $negative_inventory,		
					'is_featured' => $this->input->post('is_featured'),
					'is_new_arrival' => $this->input->post('is_new_arrival'),
					'is_special_offer' => $this->input->post('is_special_offer'),
					'is_deal_of_the_week' => $this->input->post('is_deal_of_the_week'),
					'is_online' => $this->input->post('is_online'),
					'seo_description' => $this->input->post('seo_description'),
					'seo_keywords' => $this->input->post('seo_keywords'),
					'is_draft' => 0,
					'created_by' => $this->session->userdata('system_user_id'),
					'created_on' => date("Y-m-d H:i:s", time())
				);

				$insert = $this->db->insert('products', $data);
				$insert_id = $this->db->insert_id();
				if ($insert){

					//RELATED UNITS
					$this->save_product_related_units($insert_id);

					//PRODUCT CATEGORIES
					$this->save_product_categories($insert_id);

					//PRODUCT SUPPLIERS
					$this->save_product_suppliers($insert_id);

					//OUTLET PRODUCTS
					$this->save_outlet_products($insert_id);

					//OUTLET PRICES
					$this->save_outlet_prices($insert_id);

					//STOCK TRACKER
					$this->save_product_stock_tracker($insert_id);

					$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Product added successfully.','product_id' => $insert_id);
				}else{
					$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Product successfully. Please try again.','product_id' => '');
				}
			} else {
				$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Please add product variations first before proceeding. Please try again.','product_id' => '');
			}
        }
		return $arr_return;
	}
	function save_product_related_units($product_id) {
		$related_unit_id = $this->input->post('ru_unit_id');
		$conversion_factor = $this->input->post('ru_conversion_factor');
		$unit_price = $this->input->post('ru_unit_price');
		$chk_ru_unit_id = $this->input->post('chk_ru_unit_id');
		$unit_price = $this->input->post('ru_unit_price');
		$unit_minimum_selling_price = $this->input->post('ru_unit_minimum_selling_price');

		if ($related_unit_id !== null && $related_unit_id !== '') {
			foreach ($related_unit_id as $key => $temp_id){
				if ($chk_ru_unit_id[$key] != 'off') {
				// if ($conversion_factor[$key] != '' && $conversion_factor[$key] != null){
					$new_data = array(
						'product_id' => $product_id,
						'unit_id' => $this->input->post('unit_id'),
						'related_unit_id' => $temp_id,
						'conversion_factor' => $conversion_factor[$key],
						'unit_price' => $unit_price[$key],
						'unit_minimum_selling_price' => $unit_minimum_selling_price[$key]
					);
					$insert = $this->db->insert('product_related_units', $new_data);
					$insert_id = $this->db->insert_id();

					//UPDATE OUTLET PRICES
					$outlets = $this->outlets_model->get_outlets_list();
					foreach ($outlets as $row2) {
						if($this->input->post('chk_related_unit_outlet_unit_prices_' . $temp_id) == 'on'){
							$outlet_unit_price = $unit_price[$key];
							$outlet_minimum_price = $unit_minimum_selling_price[$key];
						} else {
							$outlet_unit_price = $this->input->post('related_unit_outlet_unit_price_' . $temp_id . '_' . $row2->outlet_id);
							$outlet_minimum_price = $this->input->post('related_unit_outlet_minimum_selling_price_' . $temp_id . '_' . $row2->outlet_id);
						}

				        $this->db->from('product_related_units_outlet_prices');       
				        $this->db->where( array('unit_related_id' => $insert_id, 'outlet_id' => $row2->outlet_id));
				        $query = $this->db->get();
				        $record_count = $query->num_rows();

				        if ($record_count > 0) {
				        	$data = array(
								'unit_price' => $outlet_unit_price,
								'minimum_selling_price' => $outlet_minimum_price
							);
							$this->db->where('unit_related_id', $insert_id);
							$this->db->where('outlet_id', $row2->outlet_id);
							$this->db->update('product_related_units_outlet_prices', $data);
				        } else {
				        	$data = array(
				        		'unit_related_id' => $insert_id,
				        		'product_id' => $product_id,
				        		'outlet_id' => $row2->outlet_id,
								'unit_price' => $outlet_unit_price,
								'minimum_selling_price' => $outlet_minimum_price
							);
							$this->db->insert('product_related_units_outlet_prices', $data);
				        }
					}				
				}
			}
		}
	}
	function save_outlet_products($product_id){
		$this->db->from('outlets');
		$this->db->where( array('is_deleted'=>0));
		$outlets = $this->db->get()->result();

		foreach ($outlets as $row){
			
			$opening_stock = $this->input->post('opening_stock_'.$row->outlet_id);
			$reorder_level = $this->input->post('reorder_level_'.$row->outlet_id);
			
			if ($opening_stock == '' || $opening_stock == null){ $opening_stock = 0; }
			if ($reorder_level == '' || $reorder_level == null){ $reorder_level = 0; }

			$this->db->from('outlet_products');
			$this->db->where( array('outlet_id' => $row->outlet_id, 'product_id' => $product_id, 'product_variation_id' => 0));
			$query = $this->db->get();

			if ($query->num_rows() > 0){

				$outlet_product = $query->result();

				$current_available_stock = 0;
				$current_opening_stock = 0;
				$available_stock = 0;

				foreach ($outlet_product as $row2){
					$current_available_stock = $row2->available_stock;
					$current_opening_stock = $row2->opening_stock;
				}
				$available_stock = (((float)$current_available_stock - (float)$current_opening_stock) + (float)$opening_stock);
				$data = array(
					'opening_stock' => $opening_stock,
					'available_stock' => $available_stock,
					'reorder_level' => $reorder_level,
					'regular_price' => $this->input->post('regular_price'),
					'sale_price' => $this->input->post('sale_price')
				);	
				$this->db->where(array('product_id'=>$product_id, 'product_variation_id' => 0, 'outlet_id'=>$row->outlet_id));
				$this->db->update('outlet_products', $data);
			}else{
				$data = array(
					'outlet_id' => $row->outlet_id,
					'product_id' => $product_id,
					'opening_stock' => $opening_stock,
					'available_stock' => $opening_stock,
					'reorder_level' => $reorder_level,
					'regular_price' => $this->input->post('regular_price'),
					'sale_price' => $this->input->post('sale_price')
				);	

				$this->db->insert('outlet_products', $data);
			}	
		}			
	}
	function save_outlet_prices($product_id){
		$this->db->from('outlets');
		$this->db->where( array('is_deleted'=>0));
		$outlets = $this->db->get()->result();

		foreach ($outlets as $row){

			if ($this->input->post('chk_product_outlet_regular_prices') == 'on') {
				$regular_price = $this->input->post('regular_price');
			} else {
				$regular_price = $this->input->post('outlet_regular_price_'.$row->outlet_id);
			}

			if ($this->input->post('chk_product_outlet_sale_prices') == 'on') {
				$sale_price = $this->input->post('sale_price');
			} else {
				$sale_price = $this->input->post('outlet_sale_price_'.$row->outlet_id);
			}

			if ($this->input->post('chk_product_outlet_minimum_prices') == 'on') {
				$minimum_selling_price = $this->input->post('minimum_selling_price');
			} else {
				$minimum_selling_price = $this->input->post('outlet_minimum_price_'.$row->outlet_id);
			}

			$data = array(
				'regular_price' => $regular_price,
				'sale_price' => $sale_price,
				'minimum_selling_price' => $minimum_selling_price
			);	
			$this->db->where(array('product_id'=>$product_id, 'product_variation_id' => 0, 'outlet_id'=>$row->outlet_id));
			$this->db->update('outlet_products', $data);
		}			
	}
	function save_product_stock_tracker($product_id){
		$this->db->from('outlets');
		$this->db->where( array('is_deleted'=>0));
		$outlets = $this->db->get()->result();

		foreach ($outlets as $row){
			
			$opening_stock = $this->input->post('opening_stock_'.$row->outlet_id);
			
			if ($opening_stock == '' || $opening_stock == null){ $opening_stock = 0; }

			$this->db->from('stock_tracker');
			$this->db->where( array('outlet_id' => $row->outlet_id, 'product_id' => $product_id, 'product_variation_id' => 0, 'transaction_description' => 'Opening Stock'));
			$query = $this->db->get();

			if ($query->num_rows() > 0){

				$data = array(
					'quantity' => $opening_stock,
					'unit_price' => $this->input->post('regular_price')
				);	

				$this->db->where( array('outlet_id' => $row->outlet_id, 'product_id' => $product_id, 'product_variation_id' => 0, 'transaction_description' => 'Opening Stock'));
				$this->db->update('stock_tracker', $data);
			}else{
				$product = $this->get_product($product_id);
				foreach ($product as $row2){
					$created_on = $row2->created_on;
				}
				$data = array(
					'outlet_id' => $row->outlet_id,
					'product_id' => $product_id,
					'product_variation_id' => 0,
					'transaction_id' => 0,
					'transaction_type' => 'IN',
					'transaction_description' => 'Opening Stock',
					'quantity' => $opening_stock,
					'unit_price' => $this->input->post('regular_price'),
					'created_on' => $created_on
				);	

				$this->db->insert('stock_tracker', $data);
			}	
		}			
	}
	function save_product_categories($product_id){
		$product_category_id = $this->input->post('product_category_id');		
		foreach ($product_category_id as $temp_id){
			$new_data = array(
				'product_id' => $product_id,
				'product_category_id' => $temp_id
			);
			$insert = $this->db->insert('product_product_categories', $new_data);
		}				
	}
	function save_product_suppliers($product_id){
		$supplier_id = $this->input->post('supplier_id');		
		foreach ($supplier_id as $temp_id){
			$new_data = array(
				'product_id' => $product_id,
				'supplier_id' => $temp_id
			);
			$insert = $this->db->insert('product_suppliers', $new_data);
		}				
	}
	function save_product_sizes($product_id){
		$product_size_id = $this->input->post('product_size_id');		
		foreach ($product_size_id as $temp_id){
			$new_data = array(
				'product_id' => $product_id,
				'product_size_id' => $temp_id
			);
			$insert = $this->db->insert('product_product_sizes', $new_data);
		}				
	}
	function save_product_colors($product_id){
		$product_color = $this->input->post('product_color');		
		foreach ($product_color as $temp_id){
			if ($temp_id != '' && $temp_id != null){
				$new_data = array(
					'product_id' => $product_id,
					'product_color' => $temp_id
				);
				$insert = $this->db->insert('product_colors', $new_data);
			}
		}				
	}
	function save_product_attributes($product_id){
		$product_attribute_name = $this->input->post('product_attribute_name');		
		$product_attribute_value = $this->input->post('product_attribute_value');		

		foreach ($product_attribute_name as $key => $temp_id){
			if ($temp_id != '' && $temp_id != null && $product_attribute_value[$key] != '' && $product_attribute_value[$key] != null){
				$new_data = array(
					'product_id' => $product_id,
					'product_attribute_name' => $temp_id,
					'product_attribute_value' => $product_attribute_value[$key]
				);
				$insert = $this->db->insert('product_attributes', $new_data);				
			}
		}				
	}

	function get_product($product_id){
		$this->db->select("p.*, u.unit_code, u.unit_name, b.brand_reference_id, b.brand_name, tr.tax_rate_name, tr.tax_rate_code, tr.tax_rate_value");
        $this->db->from('products p');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');
        $this->db->join('tax_rates tr', 'tr.tax_rate_id = p.tax_rate_id', 'left outer');
        $this->db->where( array('p.product_id'=>$product_id));
        $product = $this->db->get()->result();

        $i = 0;
        foreach ($product as $row) {
            $product[$i]->units = $this->get_product_units($row->product_id);
            $i++;
        }

        return $product;
	}
	function get_product_product_categories($product_id){
		$this->db->from('product_product_categories');
		$this->db->where( array('product_id'=>$product_id));
		return $this->db->get()->result();
	}
	function get_product_images($product_id){
		$this->db->from('product_images');
		$this->db->where( array('product_id'=>$product_id, 'is_deleted'=>0));
		return $this->db->get()->result();
	}
	function get_product_num_images($product_id){
		$this->db->from('product_images');
		$this->db->where( array('product_id'=>$product_id, 'is_deleted'=>0));
		return $this->db->count_all_results();
	}
	function get_product_suppliers($product_id){
		$this->db->from('product_suppliers');
		$this->db->where( array('product_id'=>$product_id, 'is_deleted'=>0));
		return $this->db->get()->result();
	}
	function get_product_product_sizes($product_id){
		$this->db->from('product_product_sizes');
		$this->db->where( array('product_id'=>$product_id, 'is_deleted'=>0));
		return $this->db->get()->result();
	}
	function get_product_colors($product_id){
		$this->db->from('product_colors');
		$this->db->where( array('product_id'=>$product_id, 'is_deleted'=>0));
		return $this->db->get()->result();
	}

	function get_product_attributes($product_id){
		$this->db->from('product_attributes');
		$this->db->where( array('product_id'=>$product_id, 'is_deleted'=>0));

		$product_attributes = $this->db->get()->result();

        $i = 0;
        foreach($product_attributes as $row){
            $product_attributes[$i]->values = $this->get_product_attribute_values($row->product_attribute_id);
            $i++;
        }
        return $product_attributes;
	}

	function get_product_attribute($product_attribute_id){
		$this->db->from('product_attributes');
		$this->db->where( array('product_attribute_id' => $product_attribute_id));

		$product_attributes = $this->db->get()->result();

        $i = 0;
        foreach($product_attributes as $row){
            $product_attributes[$i]->values = $this->get_product_attribute_values($row->product_attribute_id);
            $i++;
        }
        return $product_attributes;
	}

	function get_num_product_attributes($product_id){
		$this->db->from('product_attributes');
		$this->db->where( array('product_id'=>$product_id, 'is_deleted'=>0));
		return $this->db->count_all_results();
	}

	function get_product_variations($product_id){
		$this->db->from('product_variations');
		$this->db->where( array('product_id'=>$product_id, 'is_deleted'=>0));

		$product_variations = $this->db->get()->result();

        $i = 0;
        foreach($product_variations as $row){
            $product_variations[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $product_variations[$i]->outlet_products = $this->get_variation_outlet_products($product_id,$row->product_variation_id);
            $i++;
        }
        return $product_variations;
	}

	function get_product_variation($product_variation_id){
		$this->db->from('product_variations');
		$this->db->where( array('product_variation_id' => $product_variation_id));

		$product_variations = $this->db->get()->result();

        $i = 0;
        foreach($product_variations as $row){
            $product_variations[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $i++;
        }
        return $product_variations;
	}

	function get_num_product_variations($product_id){
		$this->db->from('product_variations');
		$this->db->where( array('product_id'=>$product_id, 'is_deleted'=>0));
		return $this->db->count_all_results();
	}

	function get_outlet_products($product_id){
		$this->db->from('outlet_products');
		$this->db->where( array('product_id'=>$product_id, 'product_variation_id'=>0));
		return $this->db->get()->result();
	}

	function get_product_units($product_id) {
        $query = $this->db->query("select p.product_id, p.product_name, p.regular_price, u.unit_id, u.unit_code, u.unit_name, pru.unit_price, pru.conversion_factor from products p join units u on p.unit_id = u.unit_id left outer join (SELECT unit_price, conversion_factor, related_unit_id FROM product_related_units where product_id = " . $product_id . ") pru on pru.related_unit_id = u.unit_id where u.is_deleted = 0 and p.product_id = " . $product_id . " UNION SELECT p.product_id, p.product_name, p.regular_price, u.unit_id, u.unit_code, u.unit_name, pru.unit_price, pru.conversion_factor from product_related_units pru JOIN products p on p.product_id = pru.product_id join units u on u.unit_id = pru.related_unit_id where pru.product_id = " . $product_id);
        return $query->result();
    }

	function get_variation_outlet_products($product_id, $product_variation_id){
		$this->db->from('outlet_products');
		$this->db->where( array('product_id'=>$product_id, 'product_variation_id'=>$product_variation_id));
		return $this->db->get()->result();
	}

	function get_products_by_outlet($outlet_id) {
		$this->db->select("*");
		$this->db->from('products p');
		$this->db->join('outlet_products op', 'op.product_id = p.product_id');
		$this->db->where( array('p.is_deleted'=>0, 'product_variation_id'=>0, 'op.outlet_id'=>$outlet_id));
		return $this->db->get()->result();
	}

	function get_products_by_purchase_order($purchase_order_id){
		$this->db->select("*");
		$this->db->from('products p');
		$this->db->join('purchase_order_details pod', 'pod.product_id = p.product_id');
		$this->db->where( array('p.is_deleted'=>0, 'pod.purchase_order_id' => $purchase_order_id));
		return $this->db->get()->result();
	}

	function get_purchase_order_products_by_goods_receipt_note_id($goods_receipt_note_id){

		$purchase_order_id = 0;
		$this->db->select("*");
		$this->db->from('goods_receipt_notes grn');
		$this->db->where( array('grn.goods_receipt_note_id' => $goods_receipt_note_id));
		$goods_receipt_note = $this->db->get()->result();

		foreach ($goods_receipt_note as $row) {
			$purchase_order_id = $row->purchase_order_id;
		}

		$this->db->select("*");
		$this->db->from('products p');
		$this->db->join('purchase_order_details pod', 'pod.product_id = p.product_id');
		$this->db->where( array('p.is_deleted'=>0, 'pod.purchase_order_id' => $purchase_order_id));

		// $this->db->group_by('p.product_id');

		$products = $this->db->get()->result();

        $i = 0;
        foreach($products as $row){
            $products[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $i++;
        }
        return $products;


		return $this->db->get()->result();
	}

	function get_outlet_product($outlet_id, $product_id){
		$this->db->select("p.*, op.*, u.unit_code, u.unit_name, b.brand_reference_id, b.brand_name, tr.tax_rate_name, tr.tax_rate_code, tr.tax_rate_value");
        $this->db->from('products p');
        $this->db->join('outlet_products op', 'op.product_id = p.product_id');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');
        $this->db->join('tax_rates tr', 'tr.tax_rate_id = p.tax_rate_id', 'left outer');
        $this->db->where( array('p.product_id'=>$product_id, 'op.outlet_id'=>$outlet_id));
        $this->db->group_by('p.product_id');
        $product = $this->db->get()->result();

        $i = 0;
        foreach ($product as $row) {
            $product[$i]->units = $this->get_product_units($row->product_id);
            $i++;
        }
        return $product;
	}

	function get_outlet_product_variations($outlet_id, $product_id){

		$this->db->select("product_variations.*, op.opening_stock, op.available_stock, op.reorder_level, op.regular_price, op.sale_price");
		$this->db->from('product_variations');
		$this->db->join('outlet_products op', 'op.product_id = product_variations.product_id and op.product_variation_id = product_variations.product_variation_id');
		$this->db->where( array('product_variations.product_id' => $product_id, 'product_variations.is_deleted'=>0, 'op.outlet_id'=>$outlet_id));
		// $this->db->group_by('product_variations.product_id');
		$this->db->group_by(array('product_id', 'product_variation_id'));

		$product_variations = $this->db->get()->result();

        $i = 0;
        foreach($product_variations as $row){
            $product_variations[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $product_variations[$i]->outlet_products = $this->get_variation_outlet_products($product_id,$row->product_variation_id);
            $i++;
        }
        return $product_variations;
	}

	function get_num_outlet_product_variations($outlet_id, $product_id){
		$this->db->select("product_variations.*, op.opening_stock, op.available_stock, op.reorder_level, op.regular_price, op.sale_price");
		$this->db->from('product_variations');
		$this->db->join('outlet_products op', 'op.product_id = product_variations.product_id and op.product_variation_id = product_variations.product_variation_id');
		$this->db->where( array('product_variations.product_id' => $product_id, 'product_variations.is_deleted'=>0, 'op.outlet_id'=>$outlet_id));

		//$this->db->group_by('op.product_id');

        return $this->db->count_all_results();
	}

	function get_purchase_order_product($purchase_order_id, $product_id){
		$this->db->select("*");
		$this->db->from('products p');
		$this->db->join('purchase_order_details pod', 'pod.product_id = p.product_id');
		$this->db->where( array('p.product_id'=>$product_id, 'pod.purchase_order_id' => $purchase_order_id));
		return $this->db->get()->result();
	}

	function product_update_exists(){
		$product_id = $this->input->post('product_id');
		$product_barcode = $this->input->post('product_barcode');

    	$msg = '';
    	$msg2 = '';

		if ($product_barcode != '' && $product_barcode != null){
	    	//PRODUCT BARCODE
	    	$this->db->where(array('product_id != ' => $product_id, 'product_barcode' => $product_barcode, 'is_deleted' => 0));
			$query = $this->db->get('products');

			if ($query->num_rows() > 0){
				$msg = '<i class="icon-cancel-circle2"></i> Duplicate Product Barcode: The Product Barcode you entered has already been defined.<br>';
			}
		}

		if ($msg == $msg2){
			$arr_return = array('res' => true,'dt' => '');
		}else{
			$arr_return = array('res' => false,'dt' => $msg);
		}

		return $arr_return;
	}
	function update($data,$product_id){
		$this->db->where(array('product_id'=>$product_id));
		$update = $this->db->update('products', $data);
		if ($update){

			//RELATED UNITS
			$this->update_product_related_units($product_id);

			//PRODUCT CATEGORIES
			if ($this->input->post('product_category_id') != ''){
				$this->update_product_categories($product_id);
			}

			//SUPPLIERS
			if ($this->input->post('supplier_id') != ''){
				$this->update_product_suppliers($product_id);
			}

			//OUTLET PRODUCTS
			$this->save_outlet_products($product_id);

			//OUTLET PRICES
			$this->save_outlet_prices($product_id);

			//STOCK TRACKER
			$this->save_product_stock_tracker($product_id);

			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Product updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Product successfully. Please try again.');
		}
		return $arr_return;
	}
	function get_product_related_units($product_id){
		$this->db->from('product_related_units');
		$this->db->where( array('product_id'=>$product_id));
		return $this->db->get()->result();
	}
	function update_product_related_units($product_id) {
		$related_unit_id = $this->input->post('ru_unit_id');
		$conversion_factor = $this->input->post('ru_conversion_factor');
		$unit_price = $this->input->post('ru_unit_price');
		$chk_ru_unit_id = $this->input->post('chk_ru_unit_id');
		$unit_price = $this->input->post('ru_unit_price');
		$unit_minimum_selling_price = $this->input->post('ru_unit_minimum_selling_price');

		// CHECK CURRENT & OLDER UNIT TYPE
		$old_unit_id = 0;
		$old_unit_type_id = 0;
		$product = $this->get_product($product_id);
		foreach ($product as $row) {
			$old_unit_id = $row->unit_id;
		}
		$old_unit = $this->get_unit($old_unit_id);
		foreach ($old_unit as $row) {
			$old_unit_type_id = $row->unit_type_id;
		}
		
		$unit_id = $this->input->post('unit_id');
		$unit_type_id = 0;
		$unit = $this->get_unit($unit_id);
		foreach ($unit as $row) {
			$unit_type_id = $row->unit_type_id;
		}

		if ($old_unit_type_id !== $unit_type_id) {
			$this->db->where('product_id', $product_id);
		   	$this->db->delete('product_related_units');

		   	$this->db->where('product_id', $product_id);
		   	$this->db->delete('product_related_units_outlet_prices');
		}

		$related_units = $this->get_product_related_units($product_id);

		// print_r($chk_ru_unit_id);

		foreach ($related_units as $row){
			$found = false;
			$new_related_unit_id = '';
			$new_conversion_factor = '';
			$new_unit_price = 0;
			$new_unit_minimum_selling_price = 0;
			if ($related_unit_id !== null && $related_unit_id !== '') {
				foreach ($related_unit_id as $key => $temp_id){
					if ($chk_ru_unit_id[$key] != 'off') {
						if ($row->related_unit_id == $temp_id){
							$new_related_unit_id = $temp_id;
							$new_conversion_factor = $conversion_factor[$key];
							$new_unit_price = $unit_price[$key];
							$new_unit_minimum_selling_price = $unit_minimum_selling_price[$key];
							$found = true;
							break;
						}
					}
				}
			}
			if ($found == false){
			   	$this->db->where('product_id', $product_id);
			   	$this->db->where('related_unit_id', $row->related_unit_id);			   
			   	$this->db->delete('product_related_units'); 	

			   	$this->db->where('unit_related_id', $row->unit_related_id);
		   		$this->db->delete('product_related_units_outlet_prices');
			} else {
				if($this->input->post('chk_related_unit_outlet_unit_prices_' . $row->related_unit_id) == 'on'){
					$universal_prices = 1;
				} else {
					$universal_prices = 0;
				}
				$data = array(
					'unit_id' => $unit_id,
					'related_unit_id' => $new_related_unit_id,
					'conversion_factor' => $new_conversion_factor,
					'unit_price' => $new_unit_price,
					'unit_minimum_selling_price' => $new_unit_minimum_selling_price,
					'universal_prices' => $universal_prices
				);
			   	$this->db->where('unit_related_id', $row->unit_related_id);
				$this->db->update('product_related_units', $data);

				//UPDATE OUTLET PRICES
				$outlets = $this->outlets_model->get_outlets_list();
				foreach ($outlets as $row2) {
					if($this->input->post('chk_related_unit_outlet_unit_prices_' . $row->related_unit_id) == 'on'){
						$outlet_unit_price = $new_unit_price;
						$outlet_minimum_price = $new_unit_minimum_selling_price;
					} else {
						$outlet_unit_price = $this->input->post('related_unit_outlet_unit_price_' . $row->related_unit_id . '_' . $row2->outlet_id);
						$outlet_minimum_price = $this->input->post('related_unit_outlet_minimum_selling_price_' . $row->related_unit_id . '_' . $row2->outlet_id);
					}

			        $this->db->from('product_related_units_outlet_prices');       
			        $this->db->where( array('unit_related_id' => $row->unit_related_id, 'outlet_id' => $row2->outlet_id));
			        $query = $this->db->get();
			        $record_count = $query->num_rows();

			        if ($record_count > 0) {
			        	$data = array(
							'unit_price' => $outlet_unit_price,
							'minimum_selling_price' => $outlet_minimum_price
						);
						$this->db->where('unit_related_id', $row->unit_related_id);
						$this->db->where('outlet_id', $row2->outlet_id);
						$this->db->update('product_related_units_outlet_prices', $data);
			        } else {
			        	$data = array(
			        		'unit_related_id' => $row->unit_related_id,
			        		'product_id' => $product_id,
			        		'outlet_id' => $row2->outlet_id,
							'unit_price' => $outlet_unit_price,
							'minimum_selling_price' => $outlet_minimum_price
						);
						$this->db->insert('product_related_units_outlet_prices', $data);
			        }
				}
			}
		}


		$related_units = $this->get_product_related_units($product_id);

		if ($related_unit_id !== null && $related_unit_id !== '') {
			foreach ($related_unit_id as $key => $temp_id){
				// $this->debug_to_console($chk_ru_unit_id[$key]);
				if ($chk_ru_unit_id[$key] != 'off') {
					$found = false;					
					foreach ($related_units as $row){
						if ($row->related_unit_id == $temp_id){
							$found = true;
							break;
						}
					}

					if ($found == false){
						// $this->debug_to_console('Reached here');
						$new_data = array(
							'product_id' => $product_id,
							'unit_id' => $unit_id,
							'related_unit_id' => $temp_id,
							'conversion_factor' => $conversion_factor[$key],
							'unit_price' => $unit_price[$key],
							'unit_minimum_selling_price' => $unit_minimum_selling_price[$key]
						);
						$insert = $this->db->insert('product_related_units', $new_data);
						$insert_id = $this->db->insert_id();

						//UPDATE OUTLET PRICES
						$outlets = $this->outlets_model->get_outlets_list();
						foreach ($outlets as $row2) {
							if($this->input->post('chk_related_unit_outlet_unit_prices_' . $temp_id) == 'on'){
								$outlet_unit_price = $unit_price[$key];
								$outlet_minimum_price = $unit_minimum_selling_price[$key];
							} else {
								$outlet_unit_price = $this->input->post('related_unit_outlet_unit_price_' . $temp_id . '_' . $row2->outlet_id);
								$outlet_minimum_price = $this->input->post('related_unit_outlet_minimum_selling_price_' . $temp_id . '_' . $row2->outlet_id);
							}

					        $this->db->from('product_related_units_outlet_prices');       
					        $this->db->where( array('unit_related_id' => $insert_id, 'outlet_id' => $row2->outlet_id));
					        $query = $this->db->get();
					        $record_count = $query->num_rows();

					        if ($record_count > 0) {
					        	$data = array(
									'unit_price' => $outlet_unit_price,
									'minimum_selling_price' => $outlet_minimum_price
								);
								$this->db->where('unit_related_id', $insert_id);
								$this->db->where('outlet_id', $row2->outlet_id);
								$this->db->update('product_related_units_outlet_prices', $data);
					        } else {
					        	$data = array(
					        		'unit_related_id' => $insert_id,
					        		'product_id' => $product_id,
					        		'outlet_id' => $row2->outlet_id,
									'unit_price' => $outlet_unit_price,
									'minimum_selling_price' => $outlet_minimum_price
								);
								$this->db->insert('product_related_units_outlet_prices', $data);
					        }
						}

					}
				}
			}	
		}
	}
	function update_product_categories($product_id){
		$product_category_id = $this->input->post('product_category_id');
		$product_product_categories = $this->get_product_product_categories($product_id);

		foreach ($product_product_categories as $row){
			$found = false;
			if ($this->input->post('product_category_id') != ''){
				foreach ($product_category_id as $temp_id){
					if ($row->product_category_id == $temp_id){
						$found = true;
						break;
					}
				}
			}
			if ($found == false){
			   $this->db->where('product_id', $product_id);
			   $this->db->where('product_category_id', $row->product_category_id);			   
			   $this->db->delete('product_product_categories'); 				
			}
		}

		$product_product_categories = $this->get_product_product_categories($product_id);
		
		if ($this->input->post('product_category_id') != ''){
			foreach ($product_category_id as $temp_id){
				$found = false;
				foreach ($product_product_categories as $row){
					if ($row->product_category_id == $temp_id){
						$found = true;
						break;
					}
				}
				if ($found == false){
					$new_data = array(
						'product_id' => $product_id,
						'product_category_id' => $temp_id
					);
					$this->db->insert('product_product_categories', $new_data);
				}
			}	
		}			
	}
	function update_product_suppliers($product_id){
		$supplier_id = $this->input->post('supplier_id');
		$product_suppliers = $this->get_product_suppliers($product_id);

		foreach ($product_suppliers as $row){
			$found = false;
			if ($this->input->post('supplier_id') != ''){
				foreach ($supplier_id as $temp_id){
					if ($row->supplier_id == $temp_id){
						$found = true;
						break;
					}
				}
			}
			if ($found == false){
			   $this->db->where('product_id', $product_id);
			   $this->db->where('supplier_id', $row->supplier_id);			   
			   $this->db->delete('product_suppliers'); 				
			}
		}

		$product_suppliers = $this->get_product_suppliers($product_id);

		if ($this->input->post('supplier_id') != ''){
			foreach ($supplier_id as $temp_id){
				$found = false;
				foreach ($product_suppliers as $row){
					if ($row->supplier_id == $temp_id){
						$found = true;
						break;
					}
				}
				if ($found == false){
					$new_data = array(
						'product_id' => $product_id,
						'supplier_id' => $temp_id
					);
					$this->db->insert('product_suppliers', $new_data);
				}
			}
		}				
	}
	function update_product_sizes($product_id){
		$product_size_id = $this->input->post('product_size_id');
		$product_product_sizes = $this->get_product_product_sizes($product_id);

		foreach ($product_product_sizes as $row){
			$found = false;
			foreach ($product_size_id as $temp_id){
				if ($row->product_size_id == $temp_id){
					$found = true;
					break;
				}
			}
			if ($found == false){
			   $this->db->where('product_id', $product_id);
			   $this->db->where('product_size_id', $row->product_size_id);			   
			   $this->db->delete('product_product_sizes'); 				
			}
		}

		$product_product_sizes = $this->get_product_product_sizes($product_id);
	
		foreach ($product_size_id as $temp_id){
			$found = false;
			foreach ($product_product_sizes as $row){
				if ($row->product_size_id == $temp_id){
					$found = true;
					break;
				}
			}
			if ($found == false){
				$new_data = array(
					'product_id' => $product_id,
					'product_size_id' => $temp_id
				);
				$this->db->insert('product_product_sizes', $new_data);
			}
		}				
	}
	function update_product_colors($product_id){
		$product_color = $this->input->post('product_color');
		$product_colors = $this->get_product_colors($product_id);

		foreach ($product_colors as $row){
			$found = false;
			foreach ($product_color as $temp_id){
				if ($row->product_color == $temp_id){
					$found = true;
					break;
				}
			}
			if ($found == false){
			   $this->db->where('product_id', $product_id);
			   $this->db->where('product_color', $row->product_color);			   
			   $this->db->delete('product_colors'); 				
			}
		}

		$product_colors = $this->get_product_colors($product_id);

		if ($this->input->post('product_color') != ''){	
			foreach ($product_color as $temp_id){
				if ($temp_id != '' && $temp_id != null){
					$found = false;
					foreach ($product_colors as $row){
						if ($row->product_color == $temp_id){
							$found = true;
							break;
						}
					}
					if ($found == false){
						$new_data = array(
							'product_id' => $product_id,
							'product_color' => $temp_id
						);
						$this->db->insert('product_colors', $new_data);
					}
				}
			}	
		}			
	}
	function update_product_attributes($product_id){
		$product_attribute_name = $this->input->post('product_attribute_name');
		$product_attribute_value = $this->input->post('product_attribute_value');

		$product_attributes = $this->get_product_attributes($product_id);

		foreach ($product_attributes as $row){
			$found = false;
			foreach ($product_attribute_name as $temp_id){
				if ($row->product_attribute_name == $temp_id){
					$found = true;
					break;
				}
			}
			if ($found == false){
			   $this->db->where('product_id', $product_id);
			   $this->db->where('product_attribute_name', $row->product_attribute_name);			   
			   $this->db->delete('product_attributes'); 				
			}
		}

		$product_attributes = $this->get_product_attributes($product_id);

		if ($this->input->post('product_attribute_name') != ''){	
			foreach ($product_attribute_name as $key => $temp_id){
				if ($temp_id != '' && $temp_id != null && $product_attribute_value[$key] != '' && $product_attribute_value[$key] != null){
					$found = false;
					foreach ($product_attributes as $row){
						if ($row->product_attribute_name == $temp_id){
							$found = true;
							break;
						}
					}
					if ($found == false){
						$new_data = array(
							'product_id' => $product_id,
							'product_attribute_name' => $temp_id,
							'product_attribute_value' => $product_attribute_value[$key]
						);
						$this->db->insert('product_attributes', $new_data);
					}
				}
			}
		}				
	}
	
	function delete($product_id){
		$data = array(
			'is_deleted'=> 1
		);			
		$this->db->where( array('product_id'=>$product_id));
		$delupdate = $this->db->update('products', $data);
		
		if ($delupdate){

			//STOCK TRACKER
			$this->db->where( array('product_id' => $product_id));
			$this->db->update('stock_tracker', $data);

			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Product deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Product');
		}
		return $arr_return;
	}
	function set_product_online_status($product_id, $is_online){
		$data = array(
			'is_online'=> $is_online
		);			
		$this->db->where( array('product_id'=>$product_id));
		$delupdate = $this->db->update('products', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Product online status changed successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error changing Product online status');
		}
		return $arr_return;
	}
	function set_product_featured_status($product_id, $is_featured){
		$data = array(
			'is_featured'=> $is_featured
		);			
		$this->db->where( array('product_id'=>$product_id));
		$delupdate = $this->db->update('products', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Product Featured status changed successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error changing Product Featured status');
		}
		return $arr_return;
	}
	function set_product_new_arrival_status($product_id, $is_new_arrival){
		$data = array(
			'is_new_arrival'=> $is_new_arrival
		);			
		$this->db->where( array('product_id'=>$product_id));
		$delupdate = $this->db->update('products', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Product New Arrival status changed successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error changing Product New Arrival status');
		}
		return $arr_return;
	}
	function set_product_special_offer_status($product_id, $is_special_offer){
		$data = array(
			'is_special_offer'=> $is_special_offer
		);			
		$this->db->where( array('product_id'=>$product_id));
		$delupdate = $this->db->update('products', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Product Special Offer status changed successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error changing Product Special Offer status');
		}
		return $arr_return;
	}
	function set_product_deal_of_the_week_status($product_id, $is_deal_of_the_week){
		$data = array(
			'is_deal_of_the_week'=> $is_deal_of_the_week
		);			
		$this->db->where( array('product_id'=>$product_id));
		$delupdate = $this->db->update('products', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Product Deal of the Week status changed successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error changing Product Deal of the Week status');
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
			$this->db->where( array('product_id'=>$value));
			$res = $this->db->update('products', $data);
			
			if ($res){}else{
				$msg_err2 = $msg_err2 . 'Error deleting Product';
			}
		}
		if ($msg_err == $msg_err2){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Bulk Transaction(s) completed successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Bulk Transaction(s) could not be completed successfully');
		}

		return $arr_return;
	}

	
   //UPLOAD GALLERY IMAGES
   function upload_product_gallery_images($product_id){
   		//$product_gallery_image = $this->input->post('product_gallery_image');	

   		$arr_return = array('res' => false,'dt' => '');	
   		//$count = 1;

   		$files = $_FILES;
   		if (! empty($_FILES['product_gallery_image']['name'])){
	    	$pgi = count($_FILES['product_gallery_image']['name']);

	    	for($i = 0; $i < $pgi; $i++) {
	    		$file_name = $_FILES['product_gallery_image']['name'][$i];
	    		if ($file_name != ''){

	    			$_FILES['product_gallery_image']['name']= $files['product_gallery_image']['name'][$i];
			        $_FILES['product_gallery_image']['type']= $files['product_gallery_image']['type'][$i];
			        $_FILES['product_gallery_image']['tmp_name']= $files['product_gallery_image']['tmp_name'][$i];
			        $_FILES['product_gallery_image']['error']= $files['product_gallery_image']['error'][$i];
			        $_FILES['product_gallery_image']['size']= $files['product_gallery_image']['size'][$i];

					$upload_config['upload_path'] = './uploads/product_images/';
					$upload_config['allowed_types'] = 'gif|jpg|jpeg|png';
					$upload_config['max_size']	= '0';
					$upload_config['max_width']  = '0';
					$upload_config['max_height']  = '0';
					
					$this->load->library('upload');
					$this->upload->initialize($upload_config);
					
					$q = $this->upload->do_upload('product_gallery_image');
				
					if($q){				
						$det = $this->upload->data();

						$image_data = array(
							'product_id' => $product_id,
							'image_filename' => $det['file_name']
						);
						$this->db->insert('product_images', $image_data);
						$insert_id = $this->db->insert_id();
						$this->createGalleryImageThumbnail($insert_id, $det['file_name']);
						$this->resize_crop_image(800, 800, "./uploads/product_images/" . $det['file_name'], "./uploads/product_images/" . $det['file_name']);

						$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Image uploaded successfully');
					}else{
						$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Image :' . $this->upload->display_errors());
					}    			
	    		}else{
					$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Image uploaded successfully');
				}
	    	}
	    }

		return $arr_return;
	}

	function createGalleryImageThumbnail($product_image_id, $filename){
      	$source_path = './uploads/product_images/' . $filename;
      	$target_path = './uploads/product_images/thumbs/';
      
      	$config_manip = array(
          	'image_library' => 'gd2',
          	'source_image' => $source_path,
          	'new_image' => $target_path,
          	'create_thumb'    => TRUE,
          	'maintain_ratio' => TRUE,
          	'width' => 300,
          	'height' => 300
      	);
   
      	$this->load->library('image_lib');
      	$this->image_lib->initialize($config_manip);

      	if ($this->image_lib->resize()) {
      		$source_image_name = $this->image_lib->dest_image;
        	$extension = strrchr($source_image_name , '.');
        	$name = substr($source_image_name , 0, -strlen($extension));
        	$thumb_image_name = $name.'_thumb'.$extension;

			$this->db->where(array('product_image_id'=>$product_image_id));				
			$this->db->update('product_images', array('image_filename_thumb' => $thumb_image_name));

			$this->resize_crop_image(400, 400, "./uploads/product_images/" . $filename, "./uploads/product_images/thumbs/" . $thumb_image_name);
      	}   
      	$this->image_lib->clear();
   }

	// function createCoverImageThumbnail($product_id, $filename){
	// 	$this->image_lib->clear();
 //      	$source_path = './uploads/product_images/' . $filename;
 //      	$target_path = './uploads/product_images/thumbs/';
      
 //      	$config_manip = array(
 //          	'image_library' => 'gd2',
 //          	'source_image' => $source_path,
 //          	'new_image' => $target_path,
 //          	'create_thumb'    => TRUE,
 //          	'maintain_ratio' => TRUE,
 //          	'width' => 500,
 //          	'height' => 300
 //      	);
   
 //      	$this->load->library('image_lib', $config_manip);
 //      	if ($this->image_lib->resize()) {
 //      		$source_image_name = $this->image_lib->dest_image;
 //        	$extension = strrchr($source_image_name , '.');
 //        	$name = substr($source_image_name , 0, -strlen($extension));
 //        	$thumb_image_name = $name.'_thumb'.$extension;

	// 		$this->db->where(array('product_id'=>$product_id));				
	// 		$this->db->update('products', array('product_thumb_cover_image' => $thumb_image_name));
 //      	}   
 //      	$this->image_lib->clear();
 //   }

   function delete_main_image($product_id) {
		$data = array(
			'product_image'=> '',
			'product_image_thumb'=> ''
		);	
		$this->db->where(array('product_id'=>$product_id));		
		$update = $this->db->update('products', $data);
		
		if ($update){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Product Image deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => 'Error deleting Main Image. Please try again.');
		}
		return $arr_return;
   }

   

	function delete_product_image($product_image_id){
		$data = array('is_deleted' => 1);			
		$this->db->where(array('product_image_id'=>$product_image_id));		
		
		if ($this->db->update('product_images', $data)){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Image deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting product image');
		}

		return $arr_return;
	}

	

	function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 200){
        $stamp = imagecreatefrompng("./assets/fe/img/stamp.png");
	    $imgsize = getimagesize($source_file);
	    $width = $imgsize[0];
	    $height = $imgsize[1];
	    $mime = $imgsize['mime'];
	 
	    switch($mime){
	        case 'image/gif':
	            $image_create = "imagecreatefromgif";
	            $image = "imagegif";
	            break;
	 
	        case 'image/png':
	            $image_create = "imagecreatefrompng";
	            $image = "imagepng";
	            $quality = 9;
	            break;
	 
	        case 'image/jpeg':
	            $image_create = "imagecreatefromjpeg";
	            $image = "imagejpeg";
	            $quality = 200;
	            break;
	 
	        default:
	            return false;
	            break;
	    }

        $dst_img = imagecreatetruecolor($max_width, $max_height);
	    $src_img = $image_create($source_file);

        if ($exif = @exif_read_data($source_file) and !empty($exif['Orientation'])) {
            switch ($exif['Orientation']) {
            case 8:
                $src_img = imagerotate($src_img, 90, 0);
                $new_width = $height;
                $new_height = $width;
                $width = $new_width;
                $height = $new_height;
                break;
            case 3:
                $src_img = imagerotate($src_img, 180, 0);
                break;
            case 6:
                $src_img = imagerotate($src_img, -90, 0);
                $new_width = $height;
                $new_height = $width;
                $width = $new_width;
                $height = $new_height;
                break;
            }
        }
	     
	    $width_new = $height * $max_width / $max_height;
	    $height_new = $width * $max_height / $max_width;
	    //if the new width is greater than the actual width of the image, then the height is too large and the rest cut off, or vice versa
	    if($width_new > $width){
	        //cut point by height
	        $h_point = (($height - $height_new) / 2);
	        //copy image
	        imagecopyresampled($dst_img, $src_img, 0, 0, 0, $h_point, $max_width, $max_height, $width, $height_new);
	    }else{
	        //cut point by width
	        $w_point = (($width - $width_new) / 2);
	        imagecopyresampled($dst_img, $src_img, 0, 0, $w_point, 0, $max_width, $max_height, $width_new, $height);
	    }

        $margin_right = 10;
        $margin_bottom = 10;
        $swidth = imagesx($stamp);
        $sheight = imagesy($stamp);

        $imgx = imagesx($dst_img);
        $imgy = imagesy($dst_img);
        $centerX = round($imgx/2 - $swidth/2);
        $centerY = round($imgy/2 - $sheight/2);

        imagecopy($dst_img, $stamp, $centerX, $centerY, 0, 0, imagesx($stamp), imagesy($stamp));
	     
	    $image($dst_img, $dst_dir, $quality);
	 
	    if($dst_img)imagedestroy($dst_img);
	    if($src_img)imagedestroy($src_img);
	}

	//PRODUCT ATTRIBUTES
	function attribute_add_valid(){

        $product_id = 0;

        $this->db->select("*");
        $this->db->from('products');       
        $this->db->where( array('is_deleted' => 0, 'is_draft' => 1, 'created_by' => $this->session->userdata('system_user_id')));
        $this->db->order_by("product_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {
            $records = $query->result();
            
            foreach ($records as $row) {
                $product_id = $row->product_id;
            }

            $arr_return = array('res' => true,'dt' => 'Product Available.', 'product_id' => $product_id);

        } else {
            $arr_return = array('res' => false,'dt' => 'Please enter product name first before adding attributes.', 'product_id' => '');
        }
        return $arr_return;
    }

    function save_attribute(){
    	$product_id = $this->input->post('product_id');
    	$product_attribute_name = $this->input->post('product_attribute_name');

    	$this->db->where(array('product_attribute_name' => $product_attribute_name, 'product_id' => $product_id, 'is_deleted' => 0));
		$query = $this->db->get('product_attributes');

		if ($query->num_rows() > 0){
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> You have already added this attribute. Please add a different one.');
		} else {
			$data = array(
				'product_id' => $product_id,
				'product_attribute_name' => $product_attribute_name,
				'created_on' => date("Y-m-d H:i:s", time())
			);
			$insert = $this->db->insert('product_attributes', $data);
			$insert_id = $this->db->insert_id();

			if ($insert){

				$this->save_product_attribute_values($insert_id);

				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Product attribute added successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add product attribute successfully. Please try again.');
			}
		}
		return $arr_return;
    }

    function get_product_attribute_values($product_attribute_id) {
    	$this->db->from('product_attribute_values');
		$this->db->where( array('product_attribute_id' => $product_attribute_id, 'is_deleted'=>0));
		return $this->db->get()->result();
    }

    function save_product_attribute_values($product_attribute_id){

    	$product_attribute_values = $this->input->post('product_attribute_values');

    	$pavArray = explode(',', $product_attribute_values);

    	$productAttributeValues = $this->get_product_attribute_values($product_attribute_id);

		foreach ($productAttributeValues as $row){
			$found = false;
			foreach($pavArray as $item) {
	  			if ($row->product_attribute_value == $item){
					$found = true;
					break;
				}
	  		}
			if ($found == false){
			   $this->db->where('product_attribute_id', $product_attribute_id);
			   $this->db->where('product_attribute_value', $row->product_attribute_value);			   
			   $this->db->delete('product_attribute_values'); 				
			}
		}

		$productAttributeValues = $this->get_product_attribute_values($product_attribute_id);
	
		foreach($pavArray as $item) {
			$found = false;
			foreach ($productAttributeValues as $row){
				if ($row->product_attribute_value == $item){
					$found = true;
					break;
				}
			}
			if ($found == false){
				$new_data = array(
					'product_attribute_id' => $product_attribute_id,
					'product_attribute_value' => $item
				);
				$this->db->insert('product_attribute_values', $new_data);
			}
		}
    }

    function update_attribute() {

    	$product_attribute_id = $this->input->post('product_attribute_id');
    	$product_id = $this->input->post('product_id');
    	$product_attribute_name = $this->input->post('product_attribute_name');


    	$this->db->where(array('product_attribute_name' => $product_attribute_name, 'product_id' => $product_id, 'is_deleted' => 0, 'product_attribute_id !=' => $product_attribute_id));
		$query = $this->db->get('product_attributes');

		if ($query->num_rows() > 0){
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> You have already added this attribute. Please add a different one.');
		} else {
			$data = array(
				'product_attribute_name' => $product_attribute_name
			);
			$this->db->where(array('product_attribute_id' => $product_attribute_id));
			$update = $this->db->update('product_attributes', $data);
			if ($update){

				$this->save_product_attribute_values($product_attribute_id);

				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Product attribute updated successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update product attribute successfully. Please try again.');
			}
		}
		return $arr_return;
    }

    function delete_attribute($product_attribute_id){
    	$data = array(
			'is_deleted'=> 1
		);				
		$this->db->where( array('product_attribute_id' => $product_attribute_id));
		$delupdate = $this->db->update('product_attributes', $data);
		
		if ($delupdate){
			$data = array(
				'is_deleted'=> 1
			);				
			$this->db->where( array('product_attribute_id' => $product_attribute_id));
			$this->db->update('product_attribute_values', $data);

			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Attribute deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting product attribute. Please try again');
		}
		return $arr_return;
    }

    //PRODUCT VARIATIONS
    function variation_add_valid(){
    	$msg_err = '';

    	//DRAFT 
    	$product_id = 0;
        $this->db->select("*");
        $this->db->from('products');       
        $this->db->where( array('is_deleted' => 0, 'is_draft' => 1, 'created_by' => $this->session->userdata('system_user_id')));
        $this->db->order_by("product_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {
            $records = $query->result();
            
            foreach ($records as $row) {
                $product_id = $row->product_id;
            }

            //PRODUCT ATTRIBUTES
            $num_product_attributes = $this->get_num_product_attributes($product_id);
            if ($num_product_attributes > 0){
            	$product_attributes = $this->get_product_attributes($product_id);
            } else {
            	$msg_err = $msg_err . 'Before you can add a product variation you need to add some variation attributes on the <span class="font-weight-bold">Attributes</span> tab.';
            }
        } else {
        	$msg_err = $msg_err . 'Please enter product name first before adding variations.';
        }

        if ($msg_err == ''){
        	$arr_return = array('res' => true,'dt' => 'Valid', 'product_id' => $product_id);
        } else {
        	$arr_return = array('res' => false,'dt' => $msg_err, 'product_id' => '');
        }

        return $arr_return;
    }

    function save_variation(){
    	$product_id = $this->input->post('product_id');
    	$is_enabled = $this->input->post('is_enabled');
    	$product_variation_universal_prices = $this->input->post('product_variation_universal_prices');

		if($is_enabled == 'on'){
			$is_enabled = 1;
		}else{
			$is_enabled = 0;
		}

		if($product_variation_universal_prices == 'on'){
			$product_variation_universal_prices = 1;
		}else{
			$product_variation_universal_prices = 0;
		}

		$data = array(
			'product_id' => $product_id,
			'product_variation_description' => $this->input->post('product_variation_description'),
			'product_variation_regular_price' => $this->input->post('product_variation_regular_price'),
			'product_variation_sale_price' => $this->input->post('product_variation_sale_price'),
			'product_variation_minimum_selling_price' => $this->input->post('product_variation_minimum_selling_price'),
			'product_variation_universal_prices' => $product_variation_universal_prices,
			'is_enabled' => $is_enabled,
			'created_on' => date("Y-m-d H:i:s", time())
		);
		$insert = $this->db->insert('product_variations', $data);
		$insert_id = $this->db->insert_id();

		if ($insert){

			$this->save_product_variation_attributes($insert_id);
			$this->save_outlet_products_variation($product_id, $insert_id);
			$this->save_product_variation_stock_tracker($product_id, $insert_id);
			$this->upload_product_variation_image($insert_id);

			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Product variation added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add product variation successfully. Please try again.');
		}
		return $arr_return;
    }

    function get_product_variation_attributes($product_variation_id) {
    	$this->db->select('pva.*, pa.product_attribute_name, pav.product_attribute_value');
		$this->db->from('product_variation_attributes pva');
		$this->db->join('product_attributes pa', 'pa.product_attribute_id = pva.product_attribute_id', 'left outer');
		$this->db->join('product_attribute_values pav', 'pav.product_attribute_value_id = pva.product_attribute_value_id', 'left outer');

		$this->db->where( array('pva.product_variation_id' => $product_variation_id, 'pva.is_deleted'=>0));
		return $this->db->get()->result();
    }

    function save_product_variation_attributes($product_variation_id){

    	$product_attribute_id = $this->input->post('product_attribute_id');
    	$product_attribute_value_id = $this->input->post('product_attribute_value_id');

    	$productVariationAttributes= $this->get_product_variation_attributes($product_variation_id);

		foreach ($productVariationAttributes as $row){
			$found = false;
			foreach( $product_attribute_id as $key => $n ) {
				if ($product_attribute_value_id[$key] != '' && $product_attribute_value_id[$key] != null){
		  			if ($row->product_attribute_id == $n && $row->product_attribute_value_id == $product_attribute_value_id[$key]){
						$found = true;
						break;
					}
				}
	  		}
			if ($found == false){
				$data = array(
					'is_deleted'=> 1
				);
			   	$this->db->where('product_variation_id', $product_variation_id);
			   	$this->db->where('product_attribute_id', $row->product_attribute_id);
			   	$this->db->where('product_attribute_value_id', $row->product_attribute_value_id);
				$this->db->update('product_variation_attributes', $data);
			}
		}

		$productVariationAttributes = $this->get_product_variation_attributes($product_variation_id);
	
		foreach( $product_attribute_id as $key => $n ) {
			if ($product_attribute_value_id[$key] != '' && $product_attribute_value_id[$key] != null){
				$found = false;
				foreach ($productVariationAttributes as $row){
					if ($row->product_attribute_id == $n && $row->product_attribute_value_id == $product_attribute_value_id[$key]){
						$found = true;
						break;
					}
				}
				if ($found == false){
					$new_data = array(
						'product_variation_id' => $product_variation_id,
						'product_attribute_id' => $n,
						'product_attribute_value_id' => $product_attribute_value_id[$key]
					);
					$this->db->insert('product_variation_attributes', $new_data);
				}
			}
		}
    }

    function save_outlet_products_variation($product_id, $product_variation_id){
		$this->db->from('outlets');
		$this->db->where( array('is_deleted'=>0));
		$outlets = $this->db->get()->result();

		foreach ($outlets as $row){
			
			$opening_stock = $this->input->post('opening_stock_'.$row->outlet_id);
			$reorder_level = $this->input->post('reorder_level_'.$row->outlet_id);
			$regular_price = $this->input->post('regular_price_'.$row->outlet_id);
			$sale_price = $this->input->post('sale_price_'.$row->outlet_id);
			$minimum_selling_price = $this->input->post('minimum_selling_price_'.$row->outlet_id);
			
			if ($opening_stock == '' || $opening_stock == null){ $opening_stock = 0; }
			if ($reorder_level == '' || $reorder_level == null){ $reorder_level = 0; }
			if ($regular_price == '' || $regular_price == null){ $regular_price = 0; }
			if ($sale_price == '' || $sale_price == null){ $sale_price = 0; }
			if ($minimum_selling_price == '' || $minimum_selling_price == null){ $minimum_selling_price = 0; }

			$this->db->from('outlet_products');
			$this->db->where( array('outlet_id' => $row->outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
			$query = $this->db->get();

			if ($query->num_rows() > 0){

				$outlet_product = $query->result();

				$current_available_stock = 0;
				$current_opening_stock = 0;
				$available_stock = 0;

				foreach ($outlet_product as $row2){
					$current_available_stock = $row2->available_stock;
					$current_opening_stock = $row2->opening_stock;
				}
				$available_stock = (((float)$current_available_stock - (float)$current_opening_stock) + (float)$opening_stock);
				$data = array(
					'opening_stock' => $opening_stock,
					'available_stock' => $available_stock,
					'reorder_level' => $reorder_level,
					'regular_price' => $regular_price,
					'sale_price' => $sale_price,
					'minimum_selling_price' => $minimum_selling_price
				);	
				$this->db->where(array('product_id'=>$product_id, 'product_variation_id' => $product_variation_id, 'outlet_id'=>$row->outlet_id));
				$this->db->update('outlet_products', $data);
			}else{
				$data = array(
					'outlet_id' => $row->outlet_id,
					'product_id' => $product_id,
					'product_variation_id' => $product_variation_id,
					'opening_stock' => $opening_stock,
					'available_stock' => $opening_stock,
					'reorder_level' => $reorder_level,
					'regular_price' => $regular_price,
					'sale_price' => $sale_price,
					'minimum_selling_price' => $minimum_selling_price
				);	

				$this->db->insert('outlet_products', $data);
			}	
		}			
	}

	function save_product_variation_stock_tracker($product_id, $product_variation_id){
		$this->db->from('outlets');
		$this->db->where( array('is_deleted'=>0));
		$outlets = $this->db->get()->result();

		foreach ($outlets as $row){
			
			$opening_stock = $this->input->post('opening_stock_'.$row->outlet_id);
			
			if ($opening_stock == '' || $opening_stock == null){ $opening_stock = 0; }

			$this->db->from('stock_tracker');
			$this->db->where( array('outlet_id' => $row->outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_description' => 'Opening Stock'));
			$query = $this->db->get();

			if ($query->num_rows() > 0){

				$data = array(
					'quantity' => $opening_stock,
					'unit_price' => $this->input->post('regular_price')
				);	

				$this->db->where( array('outlet_id' => $row->outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_description' => 'Opening Stock'));
				$this->db->update('stock_tracker', $data);
			}else{
				$product = $this->get_product($product_id);
				foreach ($product as $row2){
					$created_on = $row2->created_on;
				}
				$data = array(
					'outlet_id' => $row->outlet_id,
					'product_id' => $product_id,
					'product_variation_id' => $product_variation_id,
					'transaction_id' => 0,
					'transaction_type' => 'IN',
					'transaction_description' => 'Opening Stock',
					'quantity' => $opening_stock,
					'unit_price' => $this->input->post('product_variation_regular_price'),
					'created_on' => $created_on
				);	

				$this->db->insert('stock_tracker', $data);
			}	
		}			
	}

    function upload_product_variation_image($product_variation_id){
		if(basename($_FILES['product_variation_image']['name'])!=''){
		
			$upload_config['upload_path'] = './uploads/product_images/';
			$upload_config['allowed_types'] = 'gif|jpg|jpeg|png';
			$upload_config['max_size']	= '0';
			$upload_config['max_width']  = '0';
			$upload_config['max_height']  = '0';
			
			$this->load->library('upload');
			$this->upload->initialize($upload_config);
			
			$q = $this->upload->do_upload('product_variation_image');
		
			if($q){				
				$det = $this->upload->data();	
				$this->db->where(array('product_variation_id'=>$product_variation_id));				
				$this->db->update('product_variations', array('product_variation_image' => $det['file_name']));
				$this->createVariationImageThumbnail($product_variation_id, $det['file_name']);
				$this->resize_crop_image(800, 800, "./uploads/product_images/" . $det['file_name'], "./uploads/product_images/" . $det['file_name']);
				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Image uploaded successfully');
			}else{
				$arr_return = array('res' => false,'dt' => $this->upload->display_errors());
			}
		}else{
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Image uploaded successfully');
		}
		return $arr_return;
	}

	function createVariationImageThumbnail($product_variation_id, $filename){
      	$source_path = './uploads/product_images/' . $filename;
      	$target_path = './uploads/product_images/thumbs/';
      
      	$config_manip = array(
          	'image_library' => 'gd2',
          	'source_image' => $source_path,
          	'new_image' => $target_path,
          	'create_thumb'    => TRUE,
          	'maintain_ratio' => TRUE,
          	'width' => 300,
          	'height' => 300
      	);
   
      	$this->load->library('image_lib');
      	$this->image_lib->initialize($config_manip);

      	if ($this->image_lib->resize()) {
      		$source_image_name = $this->image_lib->dest_image;
        	$extension = strrchr($source_image_name , '.');
        	$name = substr($source_image_name , 0, -strlen($extension));
        	$thumb_image_name = $name.'_thumb'.$extension;

			$this->db->where(array('product_variation_id'=>$product_variation_id));				
			$this->db->update('product_variations', array('product_variation_image_thumb' => $thumb_image_name));

			$this->resize_crop_image(400, 400, "./uploads/product_images/" . $filename, "./uploads/product_images/thumbs/" . $thumb_image_name);
      	}   
      	$this->image_lib->clear();
   }

   function update_variation(){
    	$product_id = $this->input->post('product_id');
    	$product_variation_id = $this->input->post('product_variation_id');
    	$is_enabled = $this->input->post('is_enabled');
    	$product_variation_universal_prices = $this->input->post('product_variation_universal_prices');

		if($is_enabled == 'on'){
			$is_enabled = 1;
		}else{
			$is_enabled = 0;
		}

		if($product_variation_universal_prices == 'on'){
			$product_variation_universal_prices = 1;
		}else{
			$product_variation_universal_prices = 0;
		}

		$data = array(
			'product_variation_description' => $this->input->post('product_variation_description'),
			'product_variation_regular_price' => $this->input->post('product_variation_regular_price'),
			'product_variation_sale_price' => $this->input->post('product_variation_sale_price'),
			'product_variation_minimum_selling_price' => $this->input->post('product_variation_minimum_selling_price'),
			'product_variation_universal_prices' => $product_variation_universal_prices,
			'is_enabled' => $is_enabled
		);
		$this->db->where('product_variation_id', $product_variation_id);
		$update = $this->db->update('product_variations', $data);

		if ($update){

			$this->save_product_variation_attributes($product_variation_id);
			$this->save_outlet_products_variation($product_id, $product_variation_id);
			$this->save_product_variation_stock_tracker($product_id, $product_variation_id);
			$this->upload_product_variation_image($product_variation_id);

			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Product variation updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not updae product variation successfully. Please try again.');
		}
		return $arr_return;
    }

    function delete_variation($product_variation_id){
    	$data = array(
			'is_deleted'=> 1
		);				
		$this->db->where( array('product_variation_id' => $product_variation_id));
		$delupdate = $this->db->update('product_variations', $data);
		
		if ($delupdate){
			$data = array(
				'is_deleted'=> 1
			);				
			$this->db->where( array('product_variation_id' => $product_variation_id));
			$this->db->update('product_variation_attributes', $data);

			//STOCK TRACKER
			$this->db->where( array('product_variation_id' => $product_variation_id));
			$this->db->update('stock_tracker', $data);

			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Variation deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting product Variation. Please try again');
		}
		return $arr_return;
    }

    //PRODUCT IMAGE
    function set_product_image_valid(){
    	$product_id = 0;

        $this->db->select("*");
        $this->db->from('products');       
        $this->db->where( array('is_deleted' => 0, 'is_draft' => 1, 'created_by' => $this->session->userdata('system_user_id')));
        $this->db->order_by("product_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {
            $records = $query->result();
            
            foreach ($records as $row) {
                $product_id = $row->product_id;
            }

            $arr_return = array('res' => true,'dt' => 'Product Available.', 'product_id' => $product_id);

        } else {
            $arr_return = array('res' => false,'dt' => 'Please enter product name first before setting the image.', 'product_id' => '');
        }
        return $arr_return;
    }

    function upload_set_product_image(){
    	$product_id = $this->input->post('product_id');

    	$q = $this->upload_product_image($product_id);

		if($q['res'] == true){
			$arr_return = array('res' => true,'dt' => $q['dt']);
		}else{
			$arr_return = array('res' => false,'dt' => $q['dt']);
		}
		return $arr_return;
    }

    //PRODUCT IMAGE
	function upload_product_image($product_id){
		if(basename($_FILES['product_image']['name'])!=''){
		
			$upload_config['upload_path'] = './uploads/product_images/';
			$upload_config['allowed_types'] = 'gif|jpg|jpeg|png';
			$upload_config['max_size']	= '0';
			$upload_config['max_width']  = '0';
			$upload_config['max_height']  = '0';
			
			$this->load->library('upload');
			$this->upload->initialize($upload_config);
			
			$q = $this->upload->do_upload('product_image');
		
			if($q){				
				$det = $this->upload->data();	
				$this->db->where(array('product_id'=>$product_id));				
				$this->db->update('products', array('product_image' => $det['file_name']));
				$this->createMainImageThumbnail($product_id, $det['file_name']);
				$this->resize_crop_image(800, 800, "./uploads/product_images/" . $det['file_name'], "./uploads/product_images/" . $det['file_name']);
				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Product Image uploaded successfully');
			}else{
				$arr_return = array('res' => false,'dt' => $this->upload->display_errors());
			}
		}else{
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Product Image uploaded successfully');
		}
		return $arr_return;
	}

	function createMainImageThumbnail($product_id, $filename){
      	$source_path = './uploads/product_images/' . $filename;
      	$target_path = './uploads/product_images/thumbs/';
      
      	$config_manip = array(
          	'image_library' => 'gd2',
          	'source_image' => $source_path,
          	'new_image' => $target_path,
          	'create_thumb'    => TRUE,
          	'maintain_ratio' => TRUE,
          	'width' => 300,
          	'height' => 300
      	);
   
      	$this->load->library('image_lib');
      	$this->image_lib->initialize($config_manip);

      	if ($this->image_lib->resize()) {
      		$source_image_name = $this->image_lib->dest_image;
        	$extension = strrchr($source_image_name , '.');
        	$name = substr($source_image_name , 0, -strlen($extension));
        	$thumb_image_name = $name.'_thumb'.$extension;

			$this->db->where(array('product_id'=>$product_id));				
			$this->db->update('products', array('product_image_thumb' => $thumb_image_name));

			$this->resize_crop_image(400, 400, "./uploads/product_images/" . $filename, "./uploads/product_images/thumbs/" . $thumb_image_name);
      	}   
      	$this->image_lib->clear();
   }

   //PRODUCT GALLERY IMAGES
   function upload_add_product_gallery_image($product_id){

   		$product_gallery_image = $this->input->post('product_gallery_image');

			$images = array();

			$files = $_FILES['product_gallery_image'];

			foreach ($files['name'] as $key => $image) {
            $_FILES['product_gallery_image[]']['name']= $files['name'][$key];
            $_FILES['product_gallery_image[]']['type']= $files['type'][$key];
            $_FILES['product_gallery_image[]']['tmp_name']= $files['tmp_name'][$key];
            $_FILES['product_gallery_image[]']['error']= $files['error'][$key];
            $_FILES['product_gallery_image[]']['size']= $files['size'][$key];

            $fileName = $image;

            $upload_config['upload_path'] = './uploads/product_images/';
			$upload_config['allowed_types'] = 'gif|jpg|jpeg|png';
			$upload_config['file_name'] = $fileName;
			$upload_config['max_size']	= '0';
			$upload_config['max_width']  = '0';
			$upload_config['max_height']  = '0';
				
			$this->load->library('upload');
			$this->upload->initialize($upload_config);
			$q = $this->upload->do_upload('product_gallery_image[]');

			if($q){				
				$det = $this->upload->data();

				$image_data = array(
					'product_id' => $product_id,
					'image_filename' => $det['file_name']
				);
				$this->db->insert('product_images', $image_data);
				$insert_id = $this->db->insert_id();
				$this->createGalleryImageThumbnail($insert_id, $det['file_name']);
				$this->resize_crop_image(800, 800, "./uploads/product_images/" . $det['file_name'], "./uploads/product_images/" . $det['file_name']);

				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Image(s) added successfully');
			}else{
				$arr_return = array('res' => false,'dt' => $this->upload->display_errors());
			}
        }
		return $arr_return;
	}

	function upload_edit_product_gallery_image($product_image_id){
		if(basename($_FILES['product_gallery_image']['name'])!=''){
			
			$upload_config['upload_path'] = './uploads/product_images/';
			$upload_config['allowed_types'] = 'gif|jpg|jpeg|png';
			//$upload_config['file_name'] = $imagefilename;
			$upload_config['max_size']	= '0';
			$upload_config['max_width']  = '0';
			$upload_config['max_height']  = '0';
			
			$this->load->library('upload');
			$this->upload->initialize($upload_config);
			
			$q = $this->upload->do_upload('product_gallery_image');
		
			if($q){				
				$det = $this->upload->data();					
				$this->db->where(array('product_image_id'=>$product_image_id));
				$this->db->update('product_images', array('image_filename' => $det['file_name']));
				$this->createGalleryImageThumbnail($product_image_id, $det['file_name']);
				$this->resize_crop_image(800, 800, "./uploads/product_images/" . $det['file_name'], "./uploads/product_images/" . $det['file_name']);
				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Image updated successfully');
			}else{
				$arr_return = array('res' => false,'dt' => $this->upload->display_errors());
			}
		}else{
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Image updated successfully');
		}
		return $arr_return;

	}

	function debug_to_console($str, $context = 'Debug in Console') {

        // Buffering to solve problems frameworks, like header() in this and not a solid return.
        ob_start();

        $output  = 'console.info(\'' . $context . ':\');';
        $output .= 'console.log(' . $str . ');';
        $output  = sprintf('<script>%s</script>', $output);

        echo $output;
    }


}