<?php
class Inventory_model extends CI_Model {

	function get_product_units($product_id) {
        $query = $this->db->query("select p.product_id, p.product_name, p.regular_price, u.unit_id, u.unit_code, u.unit_name, pru.unit_price, pru.conversion_factor from products p join units u on p.unit_id = u.unit_id left outer join (SELECT unit_price, conversion_factor, related_unit_id FROM product_related_units where product_id = " . $product_id . ") pru on pru.related_unit_id = u.unit_id where u.is_deleted = 0 and p.product_id = " . $product_id . " UNION SELECT p.product_id, p.product_name, p.regular_price, u.unit_id, u.unit_code, u.unit_name, pru.unit_price, pru.conversion_factor from product_related_units pru JOIN products p on p.product_id = pru.product_id join units u on u.unit_id = pru.related_unit_id where pru.product_id = " . $product_id);
        return $query->result();
    }
	
	function get_purchase_orders(){
		
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $purchase_order_status = $this->input->post('purchase_order_status');
        $payment_status = $this->input->post('payment_status');
        $supplier_id = $this->input->post('supplier_id');
        $system_user_id = $this->input->post('system_user_id');


		$this->db->select("purchase_orders.*, suppliers.supplier_id, suppliers.supplier_name, suppliers.phone_number, suppliers.email_address, system_users.system_user_id, system_users.first_name, system_users.last_name, su2.first_name AS 'void_first_name', su2.last_name AS 'void_last_name', (SELECT SUM(purchase_order_details.detail_quantity) FROM purchase_order_details WHERE purchase_order_details.purchase_order_id = purchase_orders.purchase_order_id) AS 'total_detail_qty', (SELECT SUM(purchase_order_details.received_quantity) FROM purchase_order_details WHERE purchase_order_details.purchase_order_id = purchase_orders.purchase_order_id) AS 'total_received_qty'");
		$this->db->from('purchase_orders');
		$this->db->join('suppliers', 'suppliers.supplier_id = purchase_orders.supplier_id', 'left outer');
		$this->db->join('system_users', 'system_users.system_user_id = purchase_orders.created_by', 'left outer');
		$this->db->join('system_users su2', 'su2.system_user_id = purchase_orders.void_user_id', 'left outer');

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(purchase_orders.purchase_order_date, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(purchase_orders.purchase_order_date, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        if ($supplier_id != ''){
      		$this->db->where( array('purchase_orders.supplier_id' => $supplier_id));
        }
        if ($system_user_id != ''){
      		$this->db->where( array('purchase_orders.created_by' => $system_user_id));
        }
        if ($purchase_order_status == 'Active') {
        	$this->db->where( array('purchase_orders.is_void' => 0));
        }elseif ($purchase_order_status == 'Unreceived') {
        	$this->db->where( array('purchase_orders.is_void' => 0));
        	$this->db->having('total_received_qty',0);
        } elseif ($purchase_order_status == 'Partially Received') {
        	$this->db->where( array('purchase_orders.is_void' => 0));
        	$this->db->having('total_received_qty > ',0);
        	$this->db->having('total_received_qty < purchase_orders.total_quantity');
        } elseif ($purchase_order_status == 'Received') {
        	$this->db->where( array('purchase_orders.is_void' => 0));
        	$this->db->having('total_received_qty >= purchase_orders.total_quantity');
        } elseif ($purchase_order_status == 'Void') {
        	$this->db->where( array('purchase_orders.is_void' => 1));
        }
        if ($payment_status == 'Unpaid') {
        	$this->db->where( array('purchase_orders.is_void' => 0));
        	$this->db->where('purchase_orders.total_paid',0);
        } elseif ($payment_status == 'Partially Paid') {
        	$this->db->where( array('purchase_orders.is_void' => 0));
        	$this->db->where('purchase_orders.total_paid > ',0);
        	$this->db->where('purchase_orders.total_paid < purchase_orders.total_amount');
        } elseif ($payment_status == 'Paid') {
        	$this->db->where( array('purchase_orders.is_void' => 0));
        	$this->db->where('purchase_orders.total_paid >= purchase_orders.total_amount');
        }
        
        if ($this->session->userdata('super_admin') != 1){
            $this->db->where( array('purchase_orders.created_by' => $this->session->userdata('system_user_id')));
        }

        $this->db->order_by("purchase_orders.purchase_order_id", "desc");

		return $this->db->get()->result();
	}
	function get_active_purchase_orders(){
		$this->db->select("purchase_orders.*, suppliers.supplier_id, suppliers.supplier_name, suppliers.phone_number, suppliers.email_address, system_users.system_user_id, system_users.first_name, system_users.last_name, (SELECT SUM(purchase_order_details.detail_quantity) FROM purchase_order_details WHERE purchase_order_details.purchase_order_id = purchase_orders.purchase_order_id) AS 'total_detail_qty', (SELECT SUM(purchase_order_details.received_quantity) FROM purchase_order_details WHERE purchase_order_details.purchase_order_id = purchase_orders.purchase_order_id) AS 'total_received_qty'");
		$this->db->from('purchase_orders');
		$this->db->join('suppliers', 'suppliers.supplier_id = purchase_orders.supplier_id', 'left outer');
		$this->db->join('system_users', 'system_users.system_user_id = purchase_orders.created_by', 'left outer');

		$this->db->where( array('purchase_orders.is_void'=>0));

		$this->db->order_by('purchase_orders.purchase_order_id', 'DESC');

		return $this->db->get()->result();

	}
	function get_purchase_order($purchase_order_id){
		$this->db->select("purchase_orders.*, suppliers.supplier_id, suppliers.supplier_name, suppliers.phone_number, suppliers.email_address, system_users.system_user_id, system_users.first_name, system_users.last_name, su2.first_name AS 'void_first_name', su2.last_name AS 'void_last_name', (SELECT SUM(purchase_order_details.detail_quantity) FROM purchase_order_details WHERE purchase_order_details.purchase_order_id = purchase_orders.purchase_order_id) AS 'total_detail_qty', (SELECT SUM(purchase_order_details.received_quantity) FROM purchase_order_details WHERE purchase_order_details.purchase_order_id = purchase_orders.purchase_order_id) AS 'total_received_qty'");
		$this->db->from('purchase_orders');
		$this->db->join('suppliers', 'suppliers.supplier_id = purchase_orders.supplier_id', 'left outer');
		$this->db->join('system_users', 'system_users.system_user_id = purchase_orders.created_by', 'left outer');
		$this->db->join('system_users su2', 'su2.system_user_id = purchase_orders.void_user_id', 'left outer');

		$this->db->where( array('purchase_orders.purchase_order_id' => $purchase_order_id));
		return $this->db->get()->result();
	}
	function get_purchase_order_details($purchase_order_id){
		$this->db->select('purchase_order_details.*, products.product_id, products.product_sku_code, products.product_barcode, products.product_name, units.unit_name, units.unit_code, tax_rate_name, tax_rate_code, tax_rate_value, suppliers.supplier_id, suppliers.supplier_name');
		$this->db->from('purchase_order_details');
		$this->db->join('products', 'products.product_id = purchase_order_details.product_id', 'left outer');
		$this->db->join('units', 'units.unit_id = purchase_order_details.unit_id', 'left outer');
		$this->db->join('tax_rates', 'tax_rates.tax_rate_id = products.tax_rate_id', 'left outer');
		$this->db->join('purchase_orders', 'purchase_orders.purchase_order_id = purchase_order_details.purchase_order_id', 'left outer');
		$this->db->join('suppliers', 'suppliers.supplier_id = purchase_orders.supplier_id', 'left outer');

		$this->db->where( array('purchase_order_details.purchase_order_id' => $purchase_order_id));

		$purchase_order_details = $this->db->get()->result();

        $i = 0;
        foreach($purchase_order_details as $row){
            $purchase_order_details[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $purchase_order_details[$i]->units = $this->get_product_units($row->product_id);
            $i++;
        }
        return $purchase_order_details;

	}

	function get_purchase_order_detail($purchase_order_detail_id){
		$this->db->select("pod.*, products.product_id, products.product_sku_code, products.product_barcode, products.product_name, suppliers.supplier_id, suppliers.supplier_name, units.unit_name, units.unit_code, tax_rate_name, tax_rate_code, tax_rate_value, (SELECT COALESCE(SUM(grnd.received_quantity),0) FROM goods_receipt_note_details grnd JOIN goods_receipt_notes grn ON grn.goods_receipt_note_id = grnd.goods_receipt_note_id WHERE grn.purchase_order_id = pod.purchase_order_id AND grn.is_void = 0 AND grnd.product_id = pod.product_id AND grnd.product_variation_id = pod.product_variation_id) AS total_received_quantity");
		$this->db->from('purchase_order_details pod');
		$this->db->join('products', 'products.product_id = pod.product_id', 'left outer');
		$this->db->join('units', 'units.unit_id = pod.unit_id', 'left outer');
		$this->db->join('tax_rates', 'tax_rates.tax_rate_id = products.tax_rate_id', 'left outer');
		$this->db->join('purchase_orders', 'purchase_orders.purchase_order_id = pod.purchase_order_id', 'left outer');
		$this->db->join('suppliers', 'suppliers.supplier_id = purchase_orders.supplier_id', 'left outer');

		$this->db->where( array('pod.purchase_order_detail_id' => $purchase_order_detail_id));

		$purchase_order_details = $this->db->get()->result();

        $i = 0;
        foreach($purchase_order_details as $row){
            $purchase_order_details[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $purchase_order_details[$i]->units = $this->get_product_units($row->product_id);
            $i++;
        }
        return $purchase_order_details;

	}

	function get_product_variation_attributes($product_variation_id) {
    	$this->db->select('pva.*, pa.product_attribute_name, pav.product_attribute_value');
		$this->db->from('product_variation_attributes pva');
		$this->db->join('product_attributes pa', 'pa.product_attribute_id = pva.product_attribute_id', 'left outer');
		$this->db->join('product_attribute_values pav', 'pav.product_attribute_value_id = pva.product_attribute_value_id', 'left outer');

		$this->db->where( array('pva.product_variation_id' => $product_variation_id, 'pva.is_deleted'=>0));
		return $this->db->get()->result();
    }

	function get_purchase_order_tax_details($purchase_order_id){
        $this->db->select("pod.tax_rate_id, tr.tax_rate_code, tr.tax_rate_value, (SELECT COALESCE(SUM(pod2.price_excl_tax * pod2.detail_quantity),0) FROM purchase_order_details pod2 WHERE pod2.purchase_order_id = pod.purchase_order_id AND pod2.tax_rate_id = pod.tax_rate_id) AS 'vatable_amount', (SELECT COALESCE(SUM(pod3.unit_tax * pod3.detail_quantity),0) FROM purchase_order_details pod3 WHERE pod3.purchase_order_id = pod.purchase_order_id AND pod3.tax_rate_id = pod.tax_rate_id) AS 'vat_amount'");
        $this->db->from('purchase_order_details pod');     
        $this->db->join('tax_rates tr', 'tr.tax_rate_id = pod.tax_rate_id');
        $this->db->join('purchase_orders ps', 'ps.purchase_order_id = pod.purchase_order_id'); 
        $this->db->group_by('pod.tax_rate_id');
        $this->db->where( array('pod.purchase_order_id' => $purchase_order_id)); 

        return $this->db->get()->result();
    }

    function get_num_purchase_order_tax_details($purchase_order_id){
        $this->db->select("pod.tax_rate_id, tr.tax_rate_code, tr.tax_rate_value, (SELECT COALESCE(SUM(pod2.price_excl_tax * pod2.detail_quantity),0) FROM purchase_order_details pod2 WHERE pod2.purchase_order_id = pod.purchase_order_id AND pod2.tax_rate_id = pod.tax_rate_id) AS 'vatable_amount', (SELECT COALESCE(SUM(pod3.unit_tax * pod3.detail_quantity),0) FROM purchase_order_details pod3 WHERE pod3.purchase_order_id = pod.purchase_order_id AND pod3.tax_rate_id = pod.tax_rate_id) AS 'vat_amount'");
        $this->db->from('purchase_order_details pod');     
        $this->db->join('tax_rates tr', 'tr.tax_rate_id = pod.tax_rate_id');
        $this->db->join('purchase_orders ps', 'ps.purchase_order_id = pod.purchase_order_id'); 
        $this->db->group_by('pod.tax_rate_id');
        $this->db->where( array('pod.purchase_order_id' => $purchase_order_id)); 

        return $this->db->count_all_results();
    }

    function get_purchase_order_payments($purchase_order_id) {
        $this->db->select("pp.*, su.first_name, su.last_name");
        $this->db->from('purchase_payments pp');     
        $this->db->join('system_users su', 'su.system_user_id = pp.system_user_id', 'left outer');
        $this->db->where( array('pp.purchase_order_id' => $purchase_order_id, 'pp.is_void' => 0));
        $this->db->order_by('pp.purchase_payment_id', 'DESC');

        return $this->db->get()->result();
    }
    function get_num_purchase_order_payments($purchase_order_id) {
        $this->db->select("pp.*");
        $this->db->from('purchase_payments pp');     
        $this->db->where( array('pp.purchase_order_id' => $purchase_order_id, 'pp.is_void' => 0));

        return $this->db->count_all_results();
    }	

	function get_auto_purchase_order_products($term){
		$this->db->select('products.*, brands.brand_id, brands.brand_name, units.unit_id, units.unit_name, units.unit_code, tax_rates.tax_rate_name, tax_rates.tax_rate_code, tax_rates.tax_rate_value');
		$this->db->from('products');
		$this->db->join('brands', 'brands.brand_id = products.brand_id', 'left outer');
		$this->db->join('units', 'units.unit_id = products.unit_id', 'left outer');
		$this->db->join('tax_rates', 'tax_rates.tax_rate_id = products.tax_rate_id', 'left outer');

		$this->db->group_start();
        $this->db->like('products.product_name', $term);
        $this->db->or_like('products.product_sku_code', $term);
        $this->db->or_like('products.product_barcode', $term);
        $this->db->group_end();	
		$this->db->where( array('products.is_deleted'=>0));

		$products = $this->db->get()->result();

        $i = 0;
        foreach ($products as $row) {
            $products[$i]->units = $this->get_product_units($row->product_id);
            $i++;
        }

        return $products;

		// return $this->db->get()->result();
	}
	function get_new_purchase_order_number(){
		$purchase_order_number = '';
		$prefix_name = '';
		$current_value = 1;
		$this->db->from('prefixes');
		$this->db->where( array('document_name' => 'Purchase Order'));
		$result = $this->db->get()->result();
		foreach ($result as $row){
			$prefix_name = $row->prefix_name;
			$current_value = $row->current_value;
		}

		$current_value = $current_value + 1;
		$purchase_order_number = $prefix_name . $current_value;

		return $purchase_order_number;

	}
	function save_purchase_order(){
		$data = array(
			'purchase_order_number' => $this->get_new_purchase_order_number(),
			'supplier_id' => $this->input->post('supplier_id'),
			'purchase_order_date' => $this->input->post('purchase_order_date'),
			'expected_date' => $this->input->post('expected_date'),
			'sub_total' => $this->input->post('po_total_detail_subtotal'),
			'freight_cost' => $this->input->post('freight_cost'),
			'total_amount' => $this->input->post('po_total_detail_total'),
			'total_quantity' => $this->input->post('po_total_detail_qty'),
			'purchase_order_note' => $this->input->post('purchase_order_note'),
			'created_by' => $this->session->userdata('system_user_id')
		);	

		$insert = $this->db->insert('purchase_orders', $data);
		$insert_id = $this->db->insert_id();

		if ($insert){

			//PREFIXES
			$this->update_current_purchase_order_number();

			//PURCHASE ORDER DETAILS
			$this->save_purchase_order_details($insert_id);

			$arr_return = array('res' => true,'dt' => 'Purchase Order saved successfully.','id' => $insert_id);			
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not save Purchase Order successfully. Please try again.','id' => '');
		}
		return $arr_return;
	}

	function update_current_purchase_order_number(){
		$current_value = 1;
		$this->db->from('prefixes');
		$this->db->where( array('document_name' => 'Purchase Order'));
		$result = $this->db->get()->result();
		foreach ($result as $row){
			$current_value = $row->current_value;
		}
		$current_value = $current_value + 1;

		$data = array(
			'current_value' => $current_value
		);	
		$this->db->where(array('document_name' => 'Purchase Order'));
		$this->db->update('prefixes', $data);
	}

	function save_purchase_order_details($purchase_order_id){
		$po_detail_product_id = $this->input->post('po_detail_product_id');
		$po_detail_product_variation_id = $this->input->post('po_detail_product_variation_id');
		$po_unit_id = $this->input->post('po_unit_id');
		$po_detail_qty = $this->input->post('po_detail_qty');
		$po_detail_cost = $this->input->post('po_detail_cost');
		$po_detail_total = $this->input->post('po_detail_total');
		
		foreach( $po_detail_product_id as $key => $n ) {

			$product_id = $n;
            $product_variation_id = $po_detail_product_variation_id[$key];           
            $quantity = $po_detail_qty[$key];
            $unit_id = $po_unit_id[$key];
            $unit_price = $po_detail_cost[$key];
            $tax_rate_id = 0;
            $tax_rate_value = 0;
            $price_excl_tax = 0;
            $unit_tax = 0;
            $tax_amount = 0;
            $line_total = 0;
            $sub_total = 0;
            
            $product = $this->get_product($product_id);

            foreach ($product as $row) {
                $tax_rate_id = $row->tax_rate_id;
                $tax_rate_value = $row->tax_rate_value;
                $unit_id = $row->unit_id;
                $base_unit_id = $row->unit_id;
            }

            if ($tax_rate_id == 0) {
                $unit_tax = $unit_price * (0/100);
            } else {
                $unit_tax = $unit_price * ($tax_rate_value/100);
            }
            $price_excl_tax = $unit_price - $unit_tax;

            $tax_amount = $unit_tax * $quantity;
            $line_total = $unit_price * $quantity;
            $sub_total = $unit_price * $quantity;

			$new_data = array(
				'purchase_order_id' => $purchase_order_id,
                'product_id' => $n,
                'product_variation_id' => $po_detail_product_variation_id[$key],
                'unit_id' => $po_unit_id[$key],
                'unit_price' => $po_detail_cost[$key],
                'tax_rate_id' => $tax_rate_id,
                'price_excl_tax' => $price_excl_tax,
                'unit_tax' => $unit_tax,
                'detail_quantity' => $po_detail_qty[$key],
                'detail_tax_amount' => $tax_amount,
                'detail_total_amount' => $po_detail_total[$key],
                'detail_sub_total' => $po_detail_total[$key]
			);
			$insert = $this->db->insert('purchase_order_details', $new_data);
		}
		$this->calculate_purchase_order_total($purchase_order_id);
	}

	function update_purchase_order(){
		$purchase_order_id = $this->input->post('purchase_order_id');

		$data = array(
			'supplier_id' => $this->input->post('supplier_id'),
			'purchase_order_date' => $this->input->post('purchase_order_date'),
			'expected_date' => $this->input->post('expected_date'),
			'sub_total' => $this->input->post('po_total_detail_subtotal'),
			'freight_cost' => $this->input->post('freight_cost'),
			'total_amount' => $this->input->post('po_total_detail_total'),
			'total_quantity' => $this->input->post('po_total_detail_qty'),
			'purchase_order_note' => $this->input->post('purchase_order_note')
		);	

		$this->db->where(array('purchase_order_id' => $purchase_order_id));
		$update = $this->db->update('purchase_orders', $data);
		
		if ($update){

			//PURCHASE ORDER DETAILS
			$this->update_purchase_order_details($purchase_order_id);

			$arr_return = array('res' => true,'dt' => 'Purchase Order updated successfully.','id' => $purchase_order_id);
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not update Purchase Order successfully. Please try again.','id' => $purchase_order_id);
		}
		return $arr_return;
	}

	function update_purchase_order_details($purchase_order_id){
		$po_detail_product_id = $this->input->post('po_detail_product_id');
		$po_detail_product_variation_id = $this->input->post('po_detail_product_variation_id');
		$po_unit_id = $this->input->post('po_unit_id');
		$po_detail_qty = $this->input->post('po_detail_qty');
		$po_detail_cost = $this->input->post('po_detail_cost');
		$po_detail_total = $this->input->post('po_detail_total');

		$purchase_order_details = $this->get_purchase_order_details($purchase_order_id);

		foreach ($purchase_order_details as $row){
			$found = false;
			$product_id = $row->product_id;
            $product_variation_id = $row->product_variation_id;
            $purchase_order_detail_id = $row->purchase_order_detail_id;

			foreach( $po_detail_product_id as $key => $n ) {
				if ($product_id == $n && $product_variation_id == $po_detail_product_variation_id[$key]){
					$found = true;
					break;
				}
			}
			if ($found == false){
			   $this->db->where('purchase_order_detail_id', $purchase_order_detail_id);
			   $this->db->delete('purchase_order_details'); 				
			}else{
				foreach( $po_detail_product_id as $key => $n ) {
                    if ($product_id == $n && $product_variation_id == $po_detail_product_variation_id[$key]){

			            $quantity = $po_detail_qty[$key];
			            $unit_id = $po_unit_id[$key];
			            $unit_price = $po_detail_cost[$key];
			            $tax_rate_id = 0;
			            $tax_rate_value = 0;
			            $price_excl_tax = 0;
			            $unit_tax = 0;
			            $tax_amount = 0;
			            $line_total = 0;
			            $sub_total = 0;
			            
			            $product = $this->get_product($product_id);

			            foreach ($product as $row2) {
			                $tax_rate_id = $row2->tax_rate_id;
			                $tax_rate_value = $row2->tax_rate_value;
			                $unit_id = $row2->unit_id;
			                $base_unit_id = $row2->unit_id;
			            }

			            if ($tax_rate_id == 0) {
			                $unit_tax = $unit_price * (0/100);
			            } else {
			                $unit_tax = $unit_price * ($tax_rate_value/100);
			            }
			            $price_excl_tax = $unit_price - $unit_tax;

			            $tax_amount = $unit_tax * $quantity;
			            $line_total = $unit_price * $quantity;
			            $sub_total = $unit_price * $quantity;

						$data = array(
							'unit_id' => $po_unit_id[$key],
			                'unit_price' => $po_detail_cost[$key],
			                'tax_rate_id' => $tax_rate_id,
			                'price_excl_tax' => $price_excl_tax,
			                'unit_tax' => $unit_tax,
			                'detail_quantity' => $po_detail_qty[$key],
			                'detail_tax_amount' => $tax_amount,
			                'detail_total_amount' => $po_detail_total[$key],
			                'detail_sub_total' => $po_detail_total[$key]
						);
						$this->db->where(array('purchase_order_id' => $purchase_order_id, 'product_id' => $row->product_id, 'product_variation_id' => $row->product_variation_id));
						$this->db->update('purchase_order_details', $data);
					}
				}
			}
		}

		$purchase_order_details = $this->get_purchase_order_details($purchase_order_id);

		foreach( $po_detail_product_id as $key => $n ) {
			$found = false;
			foreach ($purchase_order_details as $row){
				if ($row->product_id == $n && $row->product_variation_id == $po_detail_product_variation_id[$key]){
					$purchase_order_detail_id = $row->purchase_order_detail_id;
					$found = true;
					break;
				}
			}
			if ($found == false){

				$product_id = $n;
	            $product_variation_id = $po_detail_product_variation_id[$key];           
	            $quantity = $po_detail_qty[$key];
	            $unit_id = $po_unit_id[$key];
	            $unit_price = $po_detail_cost[$key];
	            $tax_rate_id = 0;
	            $tax_rate_value = 0;
	            $price_excl_tax = 0;
	            $unit_tax = 0;
	            $tax_amount = 0;
	            $line_total = 0;
	            $sub_total = 0;
	            
	            $product = $this->get_product($product_id);

	            foreach ($product as $row) {
	                $tax_rate_id = $row->tax_rate_id;
	                $tax_rate_value = $row->tax_rate_value;
	                $unit_id = $row->unit_id;
	                $base_unit_id = $row->unit_id;
	            }

	            if ($tax_rate_id == 0) {
	                $unit_tax = $unit_price * (0/100);
	            } else {
	                $unit_tax = $unit_price * ($tax_rate_value/100);
	            }
	            $price_excl_tax = $unit_price - $unit_tax;

	            $tax_amount = $unit_tax * $quantity;
	            $line_total = $unit_price * $quantity;
	            $sub_total = $unit_price * $quantity;

				$new_data = array(
					'purchase_order_id' => $purchase_order_id,
	                'product_id' => $n,
	                'product_variation_id' => $po_detail_product_variation_id[$key],
	                'unit_id' => $po_unit_id[$key],
	                'unit_price' => $po_detail_cost[$key],
	                'tax_rate_id' => $tax_rate_id,
	                'price_excl_tax' => $price_excl_tax,
	                'unit_tax' => $unit_tax,
	                'detail_quantity' => $po_detail_qty[$key],
	                'detail_tax_amount' => $tax_amount,
	                'detail_total_amount' => $po_detail_total[$key],
	                'detail_sub_total' => $po_detail_total[$key]
				);
				$this->db->insert('purchase_order_details', $new_data);
			}
		}
		$this->calculate_purchase_order_total($purchase_order_id);
	}

	function purchase_order_make_payment_valid($purchase_order_id){

        $purchase_order = $this->get_purchase_order($purchase_order_id);

        $this->db->from('purchase_order_details');       
        $this->db->where( array('purchase_order_id' => $purchase_order_id));
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {
            $arr_return = array('res' => true,'dt' => 'Items Available.', 'data' => $purchase_order);
        } else {
            $arr_return = array('res' => false,'dt' => 'Please add items to the purchase order first before recording a payment.', 'data' => '');
        }

        return $arr_return;

    }

    function submit_purchase_order_payment(){
        $purchase_order_id = $this->input->post('purchase_order_id');
        $purchase_order_number = $this->input->post('purchase_order_number');
        $payment_amount = $this->input->post('payment_amount');
        $reference_number = $this->input->post('reference_number');
        $payment_method = $this->input->post('payment_method');
        
        $data = array(
            'purchase_order_id' => $purchase_order_id,
            'payment_amount' => $payment_amount,
            'payment_method' => $payment_method,
            'reference_number' => $reference_number,
            'payment_note' => $this->input->post('payment_note'),
            'system_user_id' => $this->session->userdata('system_user_id')
        );
        $res = $this->db->insert('purchase_payments', $data);
        $purchase_payment_id = $this->db->insert_id();

        if ($res) {
            $arr_return = array('res' => true,'dt' => 'Payment submitted successfully');
        } else {
            $arr_return = array('res' => false,'dt' => 'There was a problem submitting the payment. Please try again.');
        }

        //UPDATE TOTAL PAID
        $this->calculate_purchase_order_payments($purchase_order_id);
        $this->calculate_purchase_order_total($purchase_order_id);

        return $arr_return;
    }

    function calculate_purchase_order_payments($purchase_order_id){
        $total_paid = 0;
        $total_amount = 0;

        $this->db->select("*");
        $this->db->from('purchase_payments');       
        $this->db->where( array('purchase_order_id' => $purchase_order_id, 'is_void' => 0));
        $purchase_payments = $this->db->get()->result();

        foreach ($purchase_payments as $row) {
            $total_paid = $total_paid + $row->payment_amount;
        }

        $data = array(
            'total_paid' => $total_paid
        );
        $this->db->where( array('purchase_order_id' => $purchase_order_id));
        $this->db->update('purchase_orders', $data);
    }

    function calculate_purchase_order_total($purchase_order_id) {

        //purchase_order DETAILS
        $total_tax = 0;
        $total_discount = 0;
        $total_quantity = 0;

        $this->db->from('purchase_order_details');       
        $this->db->where( array('purchase_order_id' => $purchase_order_id));
        $purchase_order_details = $this->db->get()->result();
        
        foreach ($purchase_order_details as $row) {
            $total_tax = $total_tax + $row->detail_tax_amount;
            $total_discount = $total_discount + $row->detail_discount_amount;
            $total_quantity = $total_quantity + $row->detail_quantity;
        }

        //UPDATE TABLE
        $data = array(
            'tax_amount' => $total_tax,
            'discount_amount' => $total_discount,
            'total_quantity' => $total_quantity
        );

        $this->db->where( array('purchase_order_id' => $purchase_order_id));
        $this->db->update('purchase_orders', $data);
    }

    function get_purchase_payment($purchase_payment_id) {
    	$this->db->select("purchase_payments.*, system_users.first_name, system_users.last_name");
        $this->db->from('purchase_payments');  
        $this->db->join('system_users', 'system_users.system_user_id = purchase_payments.system_user_id', 'left outer');     
        $this->db->where( array('purchase_payments.purchase_payment_id' => $purchase_payment_id));
        return $this->db->get()->result();
    }

    function purchase_payment_void_valid($purchase_payment_id) {
        $is_void = true;

        $purchase_payment = $this->get_purchase_payment($purchase_payment_id);

        foreach ($purchase_payment as $row) {
            if ($row->is_void == 0) {
                $is_void = false;
            } else {
                $is_void = true;
            }
        }

        if ($is_void == false) {
            $arr_return = array('res' => true,'dt' => 'Not Void.');
        } else {
            $arr_return = array('res' => false,'dt' => 'This payment has already been voided.');
        }
        return $arr_return;
    }

    function submit_void_purchase_payment(){
        $purchase_payment_id = $this->input->post('purchase_payment_id');
        $purchase_order_id = 0;
        $payment_method = '';
        $payment_date = '';
        $payment_reference = '';
        $payment_amount = '';
        $payment_user = '';

        $purchase_payment = $this->get_purchase_payment($purchase_payment_id);
        foreach ($purchase_payment as $row) {
            $purchase_order_id = $row->purchase_order_id;
            $payment_date = date('d M, Y', strtotime($row->created_on));
            $payment_reference = $row->reference_number;
            $payment_method = $row->payment_method;
            $payment_amount = number_format($row->payment_amount,2);
            $payment_user = $row->first_name . ' ' . $row->last_name;
        }

        $void_date = date('Y-m-d H:i:s');
        $void_reason = $this->input->post('void_reason');

        $data = array(
            'is_void' => 1,
            'void_reason' => $void_reason,
            'void_system_user_id' => $this->session->userdata('system_user_id'),
            'void_date' => $void_date
        );
        $this->db->where( array('purchase_payment_id' => $purchase_payment_id));
        $void = $this->db->update('purchase_payments', $data);

        if ($void) {

        	$purchase_order_number = '';
        	$purchase_order = $this->get_purchase_order($purchase_order_id);
        	foreach ($purchase_order as $row) {
        		$purchase_order_number = $row->purchase_order_number;
        	}

        	//NOTIFICATION
            $data = array(
                'notification_type' => 'Purchase Payment Voided',
                'notification_ref_id' => $purchase_payment_id,
                'notification_details' => '<b>Purchase Payment Voided:</b><br>Purchase Order #: <b>' . $purchase_order_number . '</b>; Payment Date: <b>' . $payment_date . '</b>; Payment Method: <b>' . $payment_method . '</b>; Ref #: <b>' . $payment_reference . '</b>; Payment Amount: <b>' . $payment_amount . '</b>; User: <b>' . $payment_user . '</b>; Void Reason: <b>' . $void_reason . '</b>; Void User: <b>' . $this->session->userdata('user_first_name') . ' ' . $this->session->userdata('user_last_name') . '</b>; Void Date: <b>' . $void_date . '</b>',
                'notification_ref_link' => 'be/inventory/purchase_order_detail/' . $purchase_order_id
            );
            $this->db->insert('notifications',$data);

            $this->calculate_purchase_order_payments($purchase_order_id);
            $this->calculate_purchase_order_total($purchase_order_id);

             $arr_return = array('res' => true,'dt' => 'Payment voided successfully.');
        } else {
            $arr_return = array('res' => false,'dt' => 'There was an error trying to void this payment. Please try again.');
        }

        return $arr_return;
    }

    function purchase_payment_modify_valid($purchase_payment_id){
        $is_void = true;
        $purchase_order_id = 0;

        $purchase_payment = $this->get_purchase_payment($purchase_payment_id);

        foreach ($purchase_payment as $row) {
            $purchase_order_id = $row->purchase_order_id;

            if ($row->is_void == 0) {
                $is_void = false;
            } else {
                $is_void = true;
            }
        }

        if ($is_void == false) {

            $purchase_order = $this->get_purchase_order($purchase_order_id);

            $arr_return = array('res' => true,'dt' => 'Not Void.', 'data' => $purchase_payment, 'purchase_order' => $purchase_order);
        } else {
            $arr_return = array('res' => false,'dt' => 'This payment has already been voided, hence cannot be modified.', 'data' => '', 'purchase_order' => '');
        }
        return $arr_return;
    }

    function submit_modify_purchase_payment() {
        $purchase_payment_id = $this->input->post('purchase_payment_id');
        $purchase_order_id = $this->input->post('purchase_order_id');
        $purchase_order_number = $this->input->post('purchase_order_number');

        $payment_method = $this->input->post('payment_method');
        $old_payment_method = $this->input->post('txt_payment_method');
        $payment_amount = $this->input->post('payment_amount');
        $reference_number = $this->input->post('reference_number');

      	$data = array(
            'payment_amount' => $payment_amount,
            'payment_method' => $payment_method,
            'reference_number' => $reference_number,
            'payment_note' => $this->input->post('payment_note')
        );
        $this->db->where( array('purchase_payment_id' => $purchase_payment_id));
        $res = $this->db->update('purchase_payments', $data);

         if ($res) {
            $arr_return = array('res' => true,'dt' => 'Payment updated successfully');
        } else {
            $arr_return = array('res' => false,'dt' => 'Could not update payment successfully. Please confirm and try again.');
        }

        $this->calculate_purchase_order_payments($purchase_order_id);
        $this->calculate_purchase_order_total($purchase_order_id);

        return $arr_return;
    }


	function submit_send_purchase_order_via_email() {

        $purchase_order_id = $this->input->post('purchase_order_id');

        try {

            ob_start();

            $mail          = new PHPMailer();
            $mail->IsSMTP();

            $use_ssl = $this->input->post('chk_use_ssl');

            if($use_ssl == 'on'){
                $mail->SMTPSecure = 'ssl';
                $mail->SMTPAuth   = true;
            }            
            $mail->Host       = $this->input->post('mail_server_name');
            $mail->Port       = $this->input->post('mail_server_port');
            $mail->Username   = $this->input->post('sender_username');
            $mail->Password   = $this->input->post('sender_password');
            
            $mail->SetFrom($this->input->post('sender_email_address'), $this->input->post('sender_name'));
            $email_to = $this->input->post('recipient_email_address'); 
             
            $mail->Subject = $this->input->post('email_subject');

            $email_message = $this->input->post('email_message'); 

            
            $message = file_get_contents(base_url().'email_temp/emheader');
            $message .= file_get_contents(base_url().'email_temp/embody');
            $message .= file_get_contents(base_url().'email_temp/emfooter');
            $logo = base_url().'assets/fe/img/logo.png';
            
            $replacements = array(
                '({logo})' => $logo, 
                '({message_subject})' => '', 
                '({message_body})' => nl2br( stripslashes( $email_message ) )
            );
            $message = preg_replace(array_keys( $replacements ), array_values( $replacements ), $message );
            
            $plaintext = $message;
            $plaintext = strip_tags( stripslashes( $plaintext ), '<p><br><h2><h3><h1><h4>' );
            $plaintext = str_replace( array( '<p>', '<br />', '<br>', '<h1>', '<h2>', '<h3>', '<h4>' ), PHP_EOL, $plaintext );
            $plaintext = str_replace( array( '</p>', '</h1>', '</h2>', '</h3>', '</h4>' ), '', $plaintext );
            $plaintext = html_entity_decode( stripslashes( $plaintext ) );
        
            
            $mail->MsgHTML( stripslashes( $message ) ); 

            $attachment = $this->generate_purchase_order_pdf($purchase_order_id);
            $mail->AddStringAttachment($attachment, 'Bethany House Purchase Order-'. $purchase_order_id . '.pdf', 'base64', 'application/pdf');
            
            $mail->AltBody = $plaintext;
            $mail->AddAddress($email_to, "");

            if( !$mail->Send() ){
                $arr_return = array('res' => false,'dt' => $mail->ErrorInfo);
            }else{
                $arr_return = array('res' => true,'dt' => 'Email Sent successfully');
            }
            ob_get_clean();
        } catch (phpmailerException $e) {
            $arr_return = array('res' => false,'dt' =>  $e->errorMessage());
        } catch (Exception $e) {
            $arr_return = array('res' => false,'dt' =>  $e->getMessage());
        }        
        return $arr_return;
    }

    function generate_purchase_order_pdf($purchase_order_id){
    	$purchase_order = $this->inventory_model->get_purchase_order($purchase_order_id);
		$purchase_order_details = $this->inventory_model->get_purchase_order_details($purchase_order_id);

		$default_currency = $this->currencies_model->get_default_currency();
		$store_information = $this->store_information_model->get_store_information();

		$attachment = '';

		foreach ($purchase_order as $row) {

			$filename='Bethany House Purchase Order - '.$row->purchase_order_number.'.pdf';

            // create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Bethany House');
            $pdf->SetTitle('Bethany House Purchase Order - '.$row->purchase_order_number);
            $pdf->SetSubject('Bethany House Purchase Order - '.$row->purchase_order_number);
            //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

            // remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set font
            $pdf->SetFont('helvetica', '', 8);

            $pdf->setCellHeightRatio(1.6);

            // add a page
            $pdf->AddPage();

            $pdf->Ln(10);

            $txt = '<table border="1" cellpadding="5" cellspacing="0">';
            $txt = $txt . '<thead>';
            $store_logo = '';
            foreach ($store_information as $row2){
                if($row2->store_logo != '' && file_exists("./uploads/store_logo/" . $row2->store_logo)){
                    $store_logo = base_url() . 'uploads/store_logo/' . $row2->store_logo;
                } else {
                    $store_logo = base_url() . 'assets/fe/img/logo.png';
                }
            }
            if ($row->is_void == 1){
                $status = 'Void';
            } else {
            	if ($row->total_received_qty == 0){
                    $status = 'Open';
            	} elseif ($row->total_received_qty < $row->total_detail_qty){
                    $status = 'Partially Received';
            	} elseif ($row->total_received_qty == $row->total_detail_qty){
                    $status = 'Closed';
                }
            }
            $txt = $txt . '<tr>
            	<td rowspan="5" width="224"><img src="' . $store_logo . '"  width="200px"><br /><br />';

            	// </td>
            	// <td rowspan="2" width="200">';

            	foreach ($store_information as $row2){
                    $txt = $txt . '<b>' . $row2->store_name . '</b><br />
                    <b>Phone:</b> ' . $row2->phone_number . '<br />
                    <b>Address:</b> ' . $row2->physical_address . '<br />
                    <b>Email:</b> ' . $row2->email_address;
                }
            	$txt = $txt . '</td>
            	<td rowspan="4"></td>
            	<td><b>Purchase Order No:</b> ' . $row->purchase_order_number . '</td>
            	</tr>
            	<tr>
            		<td><b>Order Date:</b> ' . date('d M, Y', strtotime($row->created_on)) . '</td>
            	</tr>
            	<tr>
            		<td><b>Expected Date:</b> ' . date('d M, Y', strtotime($row->purchase_order_date)) . '</td>
            	</tr>
            	<tr>
            		<td><b>Status:</b> ' . $status . '</td>
            	</tr>
            	<tr>
            		<td colspan="2"><b>Supplier:</b><br/>' . $row->supplier_name . '<br/>' . $row->email_address . '<br/>' . $row->phone_number . '</td>
            	</tr></thead></table>';


            $pdf->writeHTML($txt, true, false, false, false, '');

            $txt = '<table border="1" cellpadding="5" cellspacing="0">
           		<thead>
           			<tr>
           				<td width="30"><b>#</b></td>
           				<td width="250"><b>Product Name</b></td>
           				<td width="90"><b>Ordered</b></td>
           				<td width="90"><b>Received</b></td>
           				<td width="100"><b>Unit Cost</b></td>
           				<td width="110"><b>Amount</b></td>
           			</tr>
           		</thead>
           		<tbody>';
           	$count = 1;
           	foreach ($purchase_order_details as $row2){
           		$txt = $txt . '<tr>
           			<td width="30">' . $count . '</td>
       				<td width="250">'. $row2->product_name . '<br><i>SKU: ' . $row2->product_sku_code . '</i></td>
       				<td width="90">' . number_format($row2->detail_quantity) . '</td>
       				<td width="90">' . number_format($row2->received_quantity) . '</td>
       				<td width="100">' . $default_currency . ' ' . number_format($row2->unit_price,2) . '</td>
       				<td width="110">' . $default_currency . ' ' . number_format($row2->detail_total_amount,2) . '</td>
           		</tr>';
           		$count++;
           	}
           	$txt = $txt . '<tr>
           		<td colspan="5" align="right"><b>Subtotal</b></td>
           		<td><b>' . $default_currency . ' ' . number_format($row->sub_total,2) . '</b></td>
           	</tr>';
           	$txt = $txt . '<tr>
           		<td colspan="5" align="right"><b>Freight</b></td>
           		<td><b>' . $default_currency . ' ' . number_format($row->freight_cost,2) . '</b></td>
           	</tr>';
           	$txt = $txt . '<tr>
           		<td colspan="5" align="right"><b>Total</b></td>
           		<td><b>' . $default_currency . ' ' . number_format($row->total_amount,2) . '</b></td>
           	</tr>';
           	
           $txt = $txt . '</tbody></table>';

           	$pdf->writeHTML($txt, true, false, false, false, '');

           	$txt = '<table border="1" cellpadding="5" cellspacing="0">
           			<tr>
           				<td colspan="2" align="left"><b>Note:</b><br/>' . $row->purchase_order_note .  '</td>
           			</tr>
           			<tr>
           				<td colspan="2" align="center"><small>Printed On: '. date('d-m-Y') . '</small></td>
           			</tr>
           		<tbody>';
           	$txt = $txt . '</tbody></table>';
           	$pdf->writeHTML($txt, true, false, false, false, '');


        	$attachment = $pdf->Output($filename, 'S');
        }

        return $attachment;
    }
    function purchase_order_void_valid($purchase_order_id){
    	//$is_void = true;
    	$errMsg = '';

        $purchase_order = $this->get_purchase_order($purchase_order_id);

        foreach ($purchase_order as $row) {
            if ($row->is_void == 0) {
                if ($row->total_received_qty > 0){
                	$errMsg = 'This Purchase Order has already been partially or completely received. If you still wish to void it, please void the GRNs associated with it first.';
                } elseif ($row->total_paid > 0) {
                	$errMsg = 'Payments have already been made against this Purchase Order. If you still wish to void it, please void the payments associated with it first.';
                }										
            } else {
                $errMsg = 'This Purchase Order has already been voided.';
            }
        }

        if ($errMsg == '') {
            $arr_return = array('res' => true,'dt' => 'Valid','data' => $purchase_order);
        } else {
            $arr_return = array('res' => false,'dt' => $errMsg,'data' => $purchase_order);
        }
        return $arr_return;
    }
    function submit_void_purchase_order(){
    	$purchase_order_id = $this->input->post('purchase_order_id');

    	$void_date = date('Y-m-d H:i:s');
    	$void_reason = $this->input->post('void_reason');

        $data = array(
            'is_void' => 1,
            'void_reason' => $void_reason,
            'void_user_id' => $this->session->userdata('system_user_id'),
            'void_date' => $void_date
        );
        $this->db->where( array('purchase_order_id' => $purchase_order_id));
        $void = $this->db->update('purchase_orders', $data);

        if ($void) {

        	$purchase_order_number = '';
        	$purchase_order = $this->get_purchase_order($purchase_order_id);
        	foreach ($purchase_order as $row) {
        		$purchase_order_number = $row->purchase_order_number;
        	}

        	//NOTIFICATION
            $data = array(
                'notification_type' => 'Purchase Order Voided',
                'notification_ref_id' => $purchase_order_id,
                'notification_details' => 'Purchase Order <b>#' . $purchase_order_number . '</b> has been voided on  <b>' . $void_date . '</b> by <b>' . $this->session->userdata('user_first_name') . ' ' . $this->session->userdata('user_last_name') . '</b>. Void Reason: <b>' . $void_reason . '</b>',
                'notification_ref_link' => 'be/inventory/purchase_order_detail/' . $purchase_order_id
            );
            $this->db->insert('notifications',$data);

             $arr_return = array('res' => true,'dt' => 'Purchase Order voided successfully.');
        } else {
            $arr_return = array('res' => false,'dt' => 'There was an error trying to void this Purchase Order. Please try again.');
        }

        return $arr_return;

    }

	//GOODS RECEIPT NOTES
	function get_goods_receipt_notes(){
		// $purchase_order_status = $this->input->post('purchase_order_status');
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $status = $this->input->post('status');
        $outlet_id = $this->input->post('outlet_id');
        $supplier_id = $this->input->post('supplier_id');
        $system_user_id = $this->input->post('system_user_id');

		$this->db->select("grn.*, o.outlet_id, o.outlet_name, s.supplier_id, s.supplier_name, s.phone_number, s.email_address, po.purchase_order_id, po.purchase_order_number, su.system_user_id, su.first_name, su.last_name, su2.first_name AS 'void_first_name', su2.last_name AS 'void_last_name', (SELECT SUM(grnd.received_quantity) FROM goods_receipt_note_details grnd WHERE grnd.goods_receipt_note_id = grn.goods_receipt_note_id) AS 'total_received_qty'");
		$this->db->from('goods_receipt_notes grn');
		$this->db->join('outlets o', 'o.outlet_id = grn.outlet_id', 'left outer');
		$this->db->join('suppliers s', 's.supplier_id = grn.supplier_id', 'left outer');
		$this->db->join('purchase_orders po', 'po.purchase_order_id = grn.purchase_order_id', 'left outer');
		$this->db->join('system_users su', 'su.system_user_id = grn.created_by', 'left outer');
		$this->db->join('system_users su2', 'su2.system_user_id = grn.void_user_id', 'left outer');

		// if ($purchase_order_status != ''){
      		// $this->db->where( array('la.loan_status' => $loan_status));
        // }

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(grn.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(grn.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }

        if ($outlet_id != ''){
      		$this->db->where( array('grn.outlet_id' => $outlet_id));
        }

        if ($supplier_id != ''){
      		$this->db->where( array('grn.supplier_id' => $supplier_id));
        }

        if ($system_user_id != ''){
      		$this->db->where( array('grn.created_by' => $system_user_id));
        }

        if ($status == 'Active') {
        	$this->db->where( array('grn.is_void' => 0));
        } elseif ($status == 'Void') {
        	$this->db->where( array('grn.is_void' => 1));
        }

        $this->db->order_by("grn.goods_receipt_note_id", "desc");

		return $this->db->get()->result();
	}

	function get_new_goods_receipt_note_number(){
		$goods_receipt_note_number = '';
		$prefix_name = '';
		$current_value = 1;
		$this->db->from('prefixes');
		$this->db->where( array('document_name' => 'Goods Receipt Note'));
		$result = $this->db->get()->result();
		foreach ($result as $row){
			$prefix_name = $row->prefix_name;
			$current_value = $row->current_value;
		}

		$current_value = $current_value + 1;
		$goods_receipt_note_number = $prefix_name . $current_value;

		return $goods_receipt_note_number;
	}

	function save_goods_receipt_note(){
		$data = array(
			'goods_receipt_note_number' => $this->get_new_goods_receipt_note_number(),
			'receival_date' => $this->input->post('receival_date'),
			'purchase_order_id' => $this->input->post('purchase_order_id'),
			'supplier_id' => $this->input->post('supplier_id'),
			'outlet_id' => $this->input->post('outlet_id'),
			'sub_total' => $this->input->post('grn_total_detail_subtotal'),
			'freight_cost' => $this->input->post('freight_cost'),
			'total_amount' => $this->input->post('grn_total_detail_total'),
			'total_quantity' => $this->input->post('grn_total_detail_qty'),
			'remark' => $this->input->post('remark'),
			'created_by' => $this->session->userdata('system_user_id')
		);	

		$insert = $this->db->insert('goods_receipt_notes', $data);
		$insert_id = $this->db->insert_id();

		if ($insert){

			//PREFIXES
			$this->update_current_goods_receipt_note_number();

			//PURCHASE ORDER DETAILS
			$this->save_goods_receipt_note_details($insert_id);

			$arr_return = array('res' => true,'dt' => 'Goods received successfully.','id' => $insert_id);			
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not receive goods successfully. Please try again.','id' => '');
		}
		return $arr_return;
	}

	function update_current_goods_receipt_note_number(){
		$current_value = 1;
		$this->db->from('prefixes');
		$this->db->where( array('document_name' => 'Goods Receipt Note'));
		$result = $this->db->get()->result();
		foreach ($result as $row){
			$current_value = $row->current_value;
		}
		$current_value = $current_value + 1;

		$data = array(
			'current_value' => $current_value
		);	
		$this->db->where(array('document_name' => 'Goods Receipt Note'));
		$this->db->update('prefixes', $data);
	}

	function save_goods_receipt_note_details($goods_receipt_note_id){
		$grn_detail_product_id = $this->input->post('grn_detail_product_id');
		$grn_detail_product_variation_id = $this->input->post('grn_detail_product_variation_id');
		$grn_unit_id = $this->input->post('grn_unit_id');
		$grn_detail_qty = $this->input->post('grn_detail_qty');
		$grn_detail_cost = $this->input->post('grn_detail_cost');
		$grn_detail_total = $this->input->post('grn_detail_total');
		
		foreach( $grn_detail_product_id as $key => $n ) {

			$product_id = $n;			
			$unit_id = $grn_unit_id[$key];
			$base_unit_id = 0;
			$quantity = $grn_detail_qty[$key];
			$base_quantity = 0;
			$unit_price = $grn_detail_cost[$key];
			$base_price = 0;
			$conversion_factor = 1;

			$product = $this->get_product($product_id);
			foreach ($product as $row) {
				$base_unit_id = $row->unit_id;
			}
        	
        	if ($unit_id != $base_unit_id) {
        		$this->db->from('product_related_units');
            	$this->db->where( array('product_id'=>$product_id, 'related_unit_id'=>$unit_id));
            	$related_unit = $this->db->get()->result();

	            foreach ($related_unit as $row2) {
	                $conversion_factor = $row2->conversion_factor;
	            }
        	}   

        	$base_quantity = $quantity * $conversion_factor;
        	$base_price = ($unit_price/$conversion_factor);

			//GOODS RECEIPT NOTE DETAILS
			$new_data = array(
				'goods_receipt_note_id' => $goods_receipt_note_id,
				'product_id' => $n,
				'product_variation_id' => $grn_detail_product_variation_id[$key],
				'unit_id' => $grn_unit_id[$key],
				'received_quantity' => $grn_detail_qty[$key],
				'unit_price' => $grn_detail_cost[$key],
				'base_unit_id' => $base_unit_id,
				'conversion_factor' => $conversion_factor,
				'base_quantity' => $base_quantity,
				'base_price' => $base_price,
				'detail_total_amount' => $grn_detail_total[$key],
				'detail_sub_total' => $grn_detail_total[$key]
			);
			$insert = $this->db->insert('goods_receipt_note_details', $new_data);
			$goods_receipt_note_detail_id = $this->db->insert_id();

			$outlet_id = $this->input->post('outlet_id');

			//OUTLETS
			$this->db->from('outlet_products');
			$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $n, 'product_variation_id' => $grn_detail_product_variation_id[$key]));
			$query = $this->db->get();

			if ($query->num_rows() > 0){
				$available_stock = 0;
				$result = $query->result();
				foreach ($result as $row){
					$available_stock = $row->available_stock;
				}
				$available_stock = $available_stock + $base_quantity;
				$data = array(
					'available_stock' => $available_stock
				);	
				$this->db->where(array('product_id'=>$n, 'outlet_id'=>$outlet_id, 'product_variation_id' => $grn_detail_product_variation_id[$key]));
				$this->db->update('outlet_products', $data);
			}else{
				$data = array(
					'outlet_id' => $outlet_id,
					'product_id' => $n,
					'product_variation_id' => $grn_detail_product_variation_id[$key],
					'available_stock' => $base_quantity
				);	

				$this->db->insert('outlet_products', $data);
			}	

			//PURCHASE ORDER DETAILS
			$purchase_order_id = $this->input->post('purchase_order_id');
			if ($purchase_order_id != '' && $purchase_order_id != null){
				$this->db->from('purchase_order_details');
				$this->db->where( array('purchase_order_id' => $purchase_order_id, 'product_id' => $n, 'product_variation_id' => $grn_detail_product_variation_id[$key]));
				$result = $this->db->get()->result();

				$ordered_quantity = 0;
				$received_quantity = 0;
				$new_received_quantity = 0;

				foreach ($result as $row){
					$ordered_quantity = $row->detail_quantity;
					$received_quantity = $row->received_quantity;
				}

				if (($received_quantity + $base_quantity) > $ordered_quantity){
					$new_received_quantity = $ordered_quantity;
				}else{
					$new_received_quantity = ($received_quantity + $base_quantity);
				}
				$data = array(
					'received_quantity' => $new_received_quantity
				);	

				$this->db->where( array('purchase_order_id' => $purchase_order_id, 'product_id' => $n, 'product_variation_id' => $grn_detail_product_variation_id[$key]));
				$this->db->update('purchase_order_details', $data);
			}

			//STOCK TRACKER
			$data = array(
				'outlet_id' => $outlet_id,
				'product_id' => $n,
				'product_variation_id' => $grn_detail_product_variation_id[$key],
				'transaction_id' => $goods_receipt_note_detail_id,
				'transaction_type' => 'IN',
				'transaction_description' => 'Goods Received',
				'quantity' => $base_quantity,
				'unit_price' => $base_price
			);	

			$this->db->insert('stock_tracker', $data);
		}
	}

	function get_goods_receipt_note($goods_receipt_note_id){
		$this->db->select("goods_receipt_notes.*, outlets.outlet_id, outlets.outlet_name, suppliers.supplier_id, suppliers.supplier_name, suppliers.phone_number, suppliers.email_address, purchase_orders.purchase_order_id, purchase_orders.purchase_order_number, system_users.system_user_id, system_users.first_name, system_users.last_name, su2.first_name AS 'void_first_name', su2.last_name AS 'void_last_name', (SELECT SUM(goods_receipt_note_details.received_quantity) FROM goods_receipt_note_details WHERE goods_receipt_note_details.goods_receipt_note_id = goods_receipt_notes.goods_receipt_note_id) AS 'total_received_qty'");
		$this->db->from('goods_receipt_notes');
		$this->db->join('outlets', 'outlets.outlet_id = goods_receipt_notes.outlet_id', 'left outer');
		$this->db->join('suppliers', 'suppliers.supplier_id = goods_receipt_notes.supplier_id', 'left outer');
		$this->db->join('purchase_orders', 'purchase_orders.purchase_order_id = goods_receipt_notes.purchase_order_id', 'left outer');
		$this->db->join('system_users', 'system_users.system_user_id = goods_receipt_notes.created_by', 'left outer');
		$this->db->join('system_users su2', 'su2.system_user_id = goods_receipt_notes.void_user_id', 'left outer');

		$this->db->where( array('goods_receipt_notes.goods_receipt_note_id' => $goods_receipt_note_id));
		return $this->db->get()->result();
	}
	function get_goods_receipt_note_details($goods_receipt_note_id){
		$this->db->select("grnd.*, p.product_id, p.product_sku_code, p.product_barcode, p.product_name, pod.received_quantity AS 'total_received_quantity', pod.detail_quantity, u.unit_name, u.unit_code");
		$this->db->from('goods_receipt_note_details grnd');
		$this->db->join('products p', 'p.product_id = grnd.product_id', 'left outer');
		$this->db->join('units u', 'u.unit_id = grnd.unit_id', 'left outer');
		$this->db->join('goods_receipt_notes grn', 'grn.goods_receipt_note_id = grnd.goods_receipt_note_id');
		$this->db->join('purchase_order_details pod', 'pod.purchase_order_id = grn.purchase_order_id AND pod.product_id = grnd.product_id AND pod.product_variation_id = grnd.product_variation_id', 'left outer');

		$this->db->where( array('grnd.goods_receipt_note_id' => $goods_receipt_note_id));

		$goods_receipt_note_details = $this->db->get()->result();

        $i = 0;
        foreach($goods_receipt_note_details as $row){
            $goods_receipt_note_details[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $i++;
        }
        return $goods_receipt_note_details;
	}

	function get_auto_receive_stock_products($term, $purchase_order_id){
		$this->db->select('products.*, brands.brand_id, brands.brand_name, units.unit_id, units.unit_name, units.unit_code, purchase_order_details.*');
		$this->db->from('products');
		$this->db->join('brands', 'brands.brand_id = products.brand_id', 'left outer');
		$this->db->join('units', 'units.unit_id = products.unit_id', 'left outer');
		$this->db->join('purchase_order_details', 'purchase_order_details.product_id = products.product_id');

		$this->db->group_start();
        $this->db->like('products.product_name', $term);
        $this->db->or_like('products.product_sku_code', $term);
        $this->db->or_like('products.product_barcode', $term);
        $this->db->group_end();		

		$this->db->where( array('products.is_deleted'=>0, 'purchase_order_details.purchase_order_id'=>$purchase_order_id));
		return $this->db->get()->result();
	}

	function update_goods_receipt_note(){
		$goods_receipt_note_id = $this->input->post('goods_receipt_note_id');

		$data = array(
			'receival_date' => $this->input->post('receival_date'),
			'sub_total' => $this->input->post('grn_total_detail_subtotal'),
			'freight_cost' => $this->input->post('freight_cost'),
			'total_amount' => $this->input->post('grn_total_detail_total'),
			'total_quantity' => $this->input->post('grn_total_detail_qty'),
			'remark' => $this->input->post('remark')
		);	

		$this->db->where(array('goods_receipt_note_id' => $goods_receipt_note_id));
		$update = $this->db->update('goods_receipt_notes', $data);
		
		if ($update){

			//GOODS RECEIPT Note DETAILS
			$this->update_goods_receipt_note_details($goods_receipt_note_id);

			$arr_return = array('res' => true,'dt' => 'Goods Receipt Note updated successfully.','id' => $goods_receipt_note_id);
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not update Goods Receipt Note successfully. Please try again.','id' => $goods_receipt_note_id);
		}
		return $arr_return;
	}

	function update_goods_receipt_note_details($goods_receipt_note_id){
		$grn_detail_product_id = $this->input->post('grn_detail_product_id');
		$grn_detail_product_variation_id = $this->input->post('grn_detail_product_variation_id');
		$grn_unit_id = $this->input->post('grn_unit_id');
		$grn_detail_qty = $this->input->post('grn_detail_qty');
		$grn_detail_cost = $this->input->post('grn_detail_cost');
		$grn_detail_total = $this->input->post('grn_detail_total');

		$goods_receipt_note_details = $this->get_goods_receipt_note_details($goods_receipt_note_id);

		foreach ($goods_receipt_note_details as $row){

			$found = false;
			$already_received_qty = 0;
			$product_id = $row->product_id;
			$product_variation_id = $row->product_variation_id;
			$goods_receipt_note_detail_id = $row->goods_receipt_note_detail_id;
			$outlet_id = $this->input->post('outlet_id');
			$product_name = $row->product_name;

			foreach( $grn_detail_product_id as $key => $n ) {
				if ($product_id == $n && $product_variation_id == $grn_detail_product_variation_id[$key]){
					$already_received_qty = $row->base_quantity;
					$found = true;
					break;
				}
			}

			if ($found == false){
			   $this->db->where('goods_receipt_note_detail_id', $goods_receipt_note_detail_id);
			   $this->db->delete('goods_receipt_note_details'); 	

			   //OUTLETS
			   	$this->db->select("op.*, o.outlet_name");
				$this->db->from('outlet_products op');
				$this->db->join('outlets o', 'o.outlet_id = op.outlet_id');
				$this->db->where( array('op.outlet_id' => $outlet_id, 'op.product_id' => $product_id, 'op.product_variation_id' => $product_variation_id));
				$query = $this->db->get();

				if ($query->num_rows() > 0){
					$available_stock = 0;
					$reorder_level = 0;
					$outlet_name = '';
					$result = $query->result();
					foreach ($result as $row){
						$available_stock = $row->available_stock;
						$reorder_level = $row->reorder_level;
						$outlet_name = $row->outlet_name;
					}
					$available_stock = $available_stock - $already_received_qty;
					$data = array(
						'available_stock' => $available_stock
					);	
					$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
					$this->db->update('outlet_products', $data);

					//REORDER LEVEL NOTIFICATION
	                if (($available_stock > $reorder_level) && ($available_stock - $already_received_qty <= $reorder_level)) {
	                    //NOTIFICATION
	                    $data = array(
	                        'notification_type' => 'Reorder Level Limit Reached',
	                        'notification_ref_id' => $product_id,
	                        'notification_details' => 'The product <b>#' . $product_name . '</b> has reached its reorder level limit for outlet <b>' . $outlet_name . '</b> and needs replenishing',
	                        'notification_ref_link' => 'be/inventory/low_stocks'
	                    );
	                    $this->db->insert('notifications',$data);
	                }
				}

				//PURCHASE ORDER DETAILS
				$purchase_order_id = $this->input->post('purchase_order_id');
				if ($purchase_order_id != '' && $purchase_order_id != null){

					$this->db->select("pod.*, (SELECT COALESCE(SUM(grnd.received_quantity),0) FROM goods_receipt_note_details grnd JOIN goods_receipt_notes grn ON grn.goods_receipt_note_id = grnd.goods_receipt_note_id WHERE grn.purchase_order_id = pod.purchase_order_id AND grn.is_void = 0 AND grnd.product_id = pod.product_id AND grnd.product_variation_id = pod.product_variation_id) AS 'total_received_quantity'");
					$this->db->from('purchase_order_details pod');
					$this->db->where( array('pod.purchase_order_id' => $purchase_order_id, 'pod.product_id' => $product_id, 'pod.product_variation_id' => $product_variation_id));
					$result = $this->db->get()->result();

					// $ordered_quantity = 0;
					$total_received_quantity = 0;
					// $new_received_quantity = 0;

					foreach ($result as $row){
						// $ordered_quantity = $row->detail_quantity;
						$total_received_quantity = $row->total_received_quantity;
					}
					// $new_received_quantity = $received_quantity - $already_received_qty;
					$data = array(
						'received_quantity' => $total_received_quantity
					);	

					$this->db->where( array('purchase_order_id' => $purchase_order_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
					$this->db->update('purchase_order_details', $data);
				}

				//STOCK CHECKER
				$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $goods_receipt_note_detail_id, 'transaction_description' => 'Goods Received'));
				$this->db->delete('stock_tracker');

			}else{

				foreach( $grn_detail_product_id as $key => $n ) {
					if ($product_id == $n && $product_variation_id == $grn_detail_product_variation_id[$key]){

						$product_id = $n;			
						$unit_id = $grn_unit_id[$key];
						$base_unit_id = 0;
						$quantity = $grn_detail_qty[$key];
						$base_quantity = 0;
						$unit_price = $grn_detail_cost[$key];
						$base_price = 0;
						$conversion_factor = 1;

						$product = $this->get_product($product_id);
						foreach ($product as $row) {
							$base_unit_id = $row->unit_id;
							$product_name = $row->product_name;
						}
			        	
			        	if ($unit_id != $base_unit_id) {
			        		$this->db->from('product_related_units');
			            	$this->db->where( array('product_id'=>$product_id, 'related_unit_id'=>$unit_id));
			            	$related_unit = $this->db->get()->result();

				            foreach ($related_unit as $row2) {
				                $conversion_factor = $row2->conversion_factor;
				            }
			        	}   

			        	$base_quantity = $quantity * $conversion_factor;
			        	$base_price = ($unit_price/$conversion_factor);

						$data = array(
							'unit_id' => $grn_unit_id[$key],
							'received_quantity' => $grn_detail_qty[$key],
							'unit_price' => $grn_detail_cost[$key],
							'base_unit_id' => $base_unit_id,
							'conversion_factor' => $conversion_factor,
							'base_quantity' => $base_quantity,
							'base_price' => $base_price,
							'detail_total_amount' => $grn_detail_total[$key],
							'detail_sub_total' => $grn_detail_total[$key]
						);

						$this->db->where(array('goods_receipt_note_id' => $goods_receipt_note_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
						$this->db->update('goods_receipt_note_details', $data);

						$outlet_id = $this->input->post('outlet_id');

						//OUTLETS
						$this->db->select("op.*, o.outlet_name");
						$this->db->from('outlet_products op');
						$this->db->join('outlets o', 'o.outlet_id = op.outlet_id');
						$this->db->where( array('op.outlet_id' => $outlet_id, 'op.product_id' => $product_id, 'op.product_variation_id' => $product_variation_id));
						$query = $this->db->get();

						if ($query->num_rows() > 0){
							$available_stock = 0;
							$reorder_level = 0;
							$outlet_name = '';
							$result = $query->result();
							foreach ($result as $row){
								$available_stock = $row->available_stock;
								$reorder_level = $row->reorder_level;
								$outlet_name = $row->outlet_name;
							}
							$available_stock = ($available_stock - $already_received_qty) + $base_quantity;
							$data = array(
								'available_stock' => $available_stock
							);	
							$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
							$this->db->update('outlet_products', $data);

							//REORDER LEVEL NOTIFICATION
			                if (($available_stock + $base_quantity > $reorder_level) && (($available_stock - $already_received_qty) + $base_quantity <= $reorder_level)) {
			                    //NOTIFICATION
			                    $data = array(
			                        'notification_type' => 'Reorder Level Limit Reached',
			                        'notification_ref_id' => $product_id,
			                        'notification_details' => 'The product <b>#' . $product_name . '</b> has reached its reorder level limit for outlet <b>' .$outlet_name . '</b> and needs replenishing',
			                        'notification_ref_link' => 'be/inventory/low_stocks'
			                    );
			                    $this->db->insert('notifications',$data);
			                }
						}else{
							$data = array(
								'outlet_id' => $outlet_id,
								'product_id' => $n,
								'product_variation_id' => $grn_detail_product_variation_id[$key],
								'available_stock' => $base_quantity
							);	

							$this->db->insert('outlet_products', $data);
						}

						//PURCHASE ORDER DETAILS
						$purchase_order_id = $this->input->post('purchase_order_id');
						if ($purchase_order_id != '' && $purchase_order_id != null){
							$this->db->select("pod.*, (SELECT COALESCE(SUM(grnd.received_quantity),0) FROM goods_receipt_note_details grnd JOIN goods_receipt_notes grn ON grn.goods_receipt_note_id = grnd.goods_receipt_note_id WHERE grn.purchase_order_id = pod.purchase_order_id AND grn.is_void = 0 AND grnd.product_id = pod.product_id AND grnd.product_variation_id = pod.product_variation_id) AS 'total_received_quantity'");
							$this->db->from('purchase_order_details pod');
							$this->db->where( array('pod.purchase_order_id' => $purchase_order_id, 'pod.product_id' => $product_id, 'pod.product_variation_id' => $product_variation_id));
							$result = $this->db->get()->result();

							// $ordered_quantity = 0;
							$total_received_quantity = 0;
							// $new_received_quantity = 0;

							foreach ($result as $row){
								// $ordered_quantity = $row->detail_quantity;
								$total_received_quantity = $row->total_received_quantity;
							}
							// $new_received_quantity = $received_quantity - $already_received_qty;
							$data = array(
								'received_quantity' => $total_received_quantity
							);	

							$this->db->where( array('purchase_order_id' => $purchase_order_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
							$this->db->update('purchase_order_details', $data);
						}

						//STOCK CHECKER
						$data = array(
							'quantity' => $base_quantity,
							'unit_price' => $base_price
						);
						$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $goods_receipt_note_detail_id, 'transaction_description' => 'Goods Received'));
						$this->db->update('stock_tracker', $data);
					}
				}

			}
		}

		$goods_receipt_note_details = $this->get_goods_receipt_note_details($goods_receipt_note_id);

		foreach($grn_detail_product_id as $key => $n ) {
			$found = false;
			foreach ($goods_receipt_note_details as $row){
				if ($row->product_id == $n && $row->product_variation_id == $grn_detail_product_variation_id[$key]){
					$goods_receipt_note_detail_id = $row->goods_receipt_note_detail_id;
					$found = true;
					break;
				}
			}
			if ($found == false){

				$product_id = $n;			
				$unit_id = $grn_unit_id[$key];
				$base_unit_id = 0;
				$quantity = $grn_detail_qty[$key];
				$base_quantity = 0;
				$unit_price = $grn_detail_cost[$key];
				$base_price = 0;
				$conversion_factor = 1;

				$product = $this->get_product($product_id);
				foreach ($product as $row) {
					$base_unit_id = $row->unit_id;
				}
	        	
	        	if ($unit_id != $base_unit_id) {
	        		$this->db->from('product_related_units');
	            	$this->db->where( array('product_id'=>$product_id, 'related_unit_id'=>$unit_id));
	            	$related_unit = $this->db->get()->result();

		            foreach ($related_unit as $row2) {
		                $conversion_factor = $row2->conversion_factor;
		            }
	        	}   

	        	$base_quantity = $quantity * $conversion_factor;
	        	$base_price = ($unit_price/$conversion_factor);

				$new_data = array(
					'goods_receipt_note_id' => $goods_receipt_note_id,
					'product_id' => $n,
					'product_variation_id' => $grn_detail_product_variation_id[$key],
					'unit_id' => $grn_unit_id[$key],
					'received_quantity' => $grn_detail_qty[$key],
					'unit_price' => $grn_detail_cost[$key],
					'base_unit_id' => $base_unit_id,
					'conversion_factor' => $conversion_factor,
					'base_quantity' => $base_quantity,
					'base_price' => $base_price,
					'detail_total_amount' => $grn_detail_total[$key],
					'detail_sub_total' => $grn_detail_total[$key]
				);
				$this->db->insert('goods_receipt_note_details', $new_data);

				$outlet_id = $this->input->post('outlet_id');

				//OUTLETS
				$this->db->from('outlet_products');
				$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $n, 'product_variation_id' => $grn_detail_product_variation_id[$key]));
				$query = $this->db->get();

				if ($query->num_rows() > 0){
					$available_stock = 0;
					$result = $query->result();
					foreach ($result as $row){
						$available_stock = $row->available_stock;
					}
					$available_stock = $available_stock + $base_quantity;
					$data = array(
						'available_stock' => $available_stock
					);	
					$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $n, 'product_variation_id' => $grn_detail_product_variation_id[$key]));
					$this->db->update('outlet_products', $data);
				}else{
					$data = array(
						'outlet_id' => $outlet_id,
						'product_id' => $n,
						'product_variation_id' => $grn_detail_product_variation_id[$key],
						'available_stock' => $base_quantity
					);

					$this->db->insert('outlet_products', $data);
				}	

				//PURCHASE ORDER DETAILS
				$purchase_order_id = $this->input->post('purchase_order_id');
				if ($purchase_order_id != '' && $purchase_order_id != null){
					$this->db->select("pod.*, (SELECT COALESCE(SUM(grnd.received_quantity),0) FROM goods_receipt_note_details grnd JOIN goods_receipt_notes grn ON grn.goods_receipt_note_id = grnd.goods_receipt_note_id WHERE grn.purchase_order_id = pod.purchase_order_id AND grn.is_void = 0 AND grnd.product_id = pod.product_id AND grnd.product_variation_id = pod.product_variation_id) AS 'total_received_quantity'");
					$this->db->from('purchase_order_details pod');
					$this->db->where( array('pod.purchase_order_id' => $purchase_order_id, 'pod.product_id' => $n, 'pod.product_variation_id' => $grn_detail_product_variation_id[$key]));
					$result = $this->db->get()->result();

					// $ordered_quantity = 0;
					$total_received_quantity = 0;
					// $new_received_quantity = 0;

					foreach ($result as $row){
						// $ordered_quantity = $row->detail_quantity;
						$total_received_quantity = $row->total_received_quantity;
					}
					// $new_received_quantity = $received_quantity - $already_received_qty;
					$data = array(
						'received_quantity' => $total_received_quantity
					);

					$this->db->where( array('purchase_order_id' => $purchase_order_id, 'product_id' => $n, 'product_variation_id' => $grn_detail_product_variation_id[$key]));
					$this->db->update('purchase_order_details', $data);
				}

				//STOCK TRACKER
				$data = array(
					'outlet_id' => $outlet_id,
					'product_id' => $n,
					'product_variation_id' => $grn_detail_product_variation_id[$key],
					'transaction_id' => $goods_receipt_note_detail_id,
					'transaction_type' => 'IN',
					'transaction_description' => 'Goods Received',
					'quantity' => $base_quantity,
					'unit_price' => $base_price
				);
				$this->db->insert('stock_tracker', $data);

			}
		}
	}

	function submit_send_goods_receipt_note_via_email() {

        $goods_receipt_note_id = $this->input->post('goods_receipt_note_id');

        try {

            ob_start();

            $mail          = new PHPMailer();
            $mail->IsSMTP();

            $use_ssl = $this->input->post('chk_use_ssl');

            if($use_ssl == 'on'){
                $mail->SMTPSecure = 'ssl';
                $mail->SMTPAuth   = true;
            }            
            $mail->Host       = $this->input->post('mail_server_name');
            $mail->Port       = $this->input->post('mail_server_port');
            $mail->Username   = $this->input->post('sender_username');
            $mail->Password   = $this->input->post('sender_password');
            
            $mail->SetFrom($this->input->post('sender_email_address'), $this->input->post('sender_name'));
            $email_to = $this->input->post('recipient_email_address'); 
             
            $mail->Subject = $this->input->post('email_subject');

            $email_message = $this->input->post('email_message'); 

            
            $message = file_get_contents(base_url().'email_temp/emheader');
            $message .= file_get_contents(base_url().'email_temp/embody');
            $message .= file_get_contents(base_url().'email_temp/emfooter');
            $logo = base_url().'assets/fe/img/logo.png';
            
            $replacements = array(
                '({logo})' => $logo, 
                '({message_subject})' => '', 
                '({message_body})' => nl2br( stripslashes( $email_message ) )
            );
            $message = preg_replace(array_keys( $replacements ), array_values( $replacements ), $message );
            
            $plaintext = $message;
            $plaintext = strip_tags( stripslashes( $plaintext ), '<p><br><h2><h3><h1><h4>' );
            $plaintext = str_replace( array( '<p>', '<br />', '<br>', '<h1>', '<h2>', '<h3>', '<h4>' ), PHP_EOL, $plaintext );
            $plaintext = str_replace( array( '</p>', '</h1>', '</h2>', '</h3>', '</h4>' ), '', $plaintext );
            $plaintext = html_entity_decode( stripslashes( $plaintext ) );
        
            
            $mail->MsgHTML( stripslashes( $message ) ); 

            $attachment = $this->generate_goods_receipt_note_pdf($goods_receipt_note_id);
            $mail->AddStringAttachment($attachment, 'Bethany House Goods Receipt Note -'. $goods_receipt_note_id . '.pdf', 'base64', 'application/pdf');
            
            $mail->AltBody = $plaintext;
            $mail->AddAddress($email_to, "");

            if( !$mail->Send() ){
                $arr_return = array('res' => false,'dt' => $mail->ErrorInfo);
            }else{
                $arr_return = array('res' => true,'dt' => 'Email Sent successfully');
            }
            ob_get_clean();
        } catch (phpmailerException $e) {
            $arr_return = array('res' => false,'dt' =>  $e->errorMessage());
        } catch (Exception $e) {
            $arr_return = array('res' => false,'dt' =>  $e->getMessage());
        }        
        return $arr_return;
    }

    function generate_goods_receipt_note_pdf($goods_receipt_note_id){
    	$goods_receipt_note = $this->inventory_model->get_goods_receipt_note($goods_receipt_note_id);
		$goods_receipt_note_details = $this->inventory_model->get_goods_receipt_note_details($goods_receipt_note_id);

		$default_currency = $this->currencies_model->get_default_currency();
		$store_information = $this->store_information_model->get_store_information();

		$attachment = '';

		foreach ($goods_receipt_note as $row) {

			$filename='Bethany House Goods Receipt Note - '.$row->goods_receipt_note_number.'.pdf';

            // create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Bethany House');
            $pdf->SetTitle('Bethany House Goods Receipt Note - '.$row->goods_receipt_note_number);
            $pdf->SetSubject('Bethany House Goods Receipt Note - '.$row->goods_receipt_note_number);
            //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

            // remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set font
            $pdf->SetFont('helvetica', '', 8);

            $pdf->setCellHeightRatio(1.6);

            // add a page
            $pdf->AddPage();

            $pdf->Ln(10);

            $txt = '<table border="1" cellpadding="5" cellspacing="0">';
            $txt = $txt . '<thead>';
            $store_logo = '';
            foreach ($store_information as $row2){
                if($row2->store_logo != '' && file_exists("./uploads/store_logo/" . $row2->store_logo)){
                    $store_logo = base_url() . 'uploads/store_logo/' . $row2->store_logo;
                } else {
                    $store_logo = base_url() . 'assets/fe/img/logo.png';
                }
            }
            $txt = $txt . '<tr>
            	<td rowspan="5" width="224"><img src="' . $store_logo . '"  width="200px"><br /><br />';

            	// </td>
            	// <td rowspan="2" width="200">';

            	foreach ($store_information as $row2){
                    $txt = $txt . '<b>' . $row2->store_name . '</b><br />
                    <b>Phone:</b> ' . $row2->phone_number . '<br />
                    <b>Address:</b> ' . $row2->physical_address . '<br />
                    <b>Email:</b> ' . $row2->email_address;
                }
            	$txt = $txt . '</td>
            	<td rowspan="4"></td>
            	<td><b>Goods Receipt Note No:</b> ' . $row->goods_receipt_note_number . '</td>
            	</tr>
            	<tr>
            		<td><b>Outlet:</b> ' . $row->outlet_name . '</td>
            	</tr>
            	<tr>
            		<td><b>Purchase Order:</b> ' . $row->purchase_order_number . '</td>
            	</tr>
            	<tr>
            		<td><b>Receival Date:</b> ' . date('d M, Y', strtotime($row->receival_date)) . '</td>
            	</tr>
            	<tr>
            		<td colspan="2"><b>Supplier:</b><br/>' . $row->supplier_name . '<br/>' . $row->email_address . '<br/>' . $row->phone_number . '</td>
            	</tr></thead></table>';


            $pdf->writeHTML($txt, true, false, false, false, '');

            $txt = '<table border="1" cellpadding="5" cellspacing="0">
           		<thead>
           			<tr>
           				<td width="30"><b>#</b></td>
           				<td width="340"><b>Product Name</b></td>
           				<td width="90"><b>Received</b></td>
           				<td width="100"><b>Unit Cost</b></td>
           				<td width="110"><b>Amount</b></td>
           			</tr>
           		</thead>
           		<tbody>';
           	$count = 1;
           	foreach ($goods_receipt_note_details as $row2){
           		$txt = $txt . '<tr>
           			<td width="30">' . $count . '</td>
       				<td width="340">'. $row2->product_name . '<br><i>SKU: ' . $row2->product_sku_code . '</i></td>
       				<td width="90">' . number_format($row2->received_quantity) . '</td>
       				<td width="100">' . $default_currency . ' ' . number_format($row2->unit_price,2) . '</td>
       				<td width="110">' . $default_currency . ' ' . number_format($row2->detail_total_amount,2) . '</td>
           		</tr>';
           		$count++;
           	}
           	$txt = $txt . '<tr>
           		<td colspan="4" align="right"><b>Subtotal</b></td>
           		<td><b>' . $default_currency . ' ' . number_format($row->sub_total,2) . '</b></td>
           	</tr>';
           	$txt = $txt . '<tr>
           		<td colspan="4" align="right"><b>Freight</b></td>
           		<td><b>' . $default_currency . ' ' . number_format($row->freight_cost,2) . '</b></td>
           	</tr>';
           	$txt = $txt . '<tr>
           		<td colspan="4" align="right"><b>Total</b></td>
           		<td><b>' . $default_currency . ' ' . number_format($row->total_amount,2) . '</b></td>
           	</tr>';
           	
           $txt = $txt . '</tbody></table>';

           	$pdf->writeHTML($txt, true, false, false, false, '');

           	$txt = '<table border="1" cellpadding="5" cellspacing="0">
           			<tr>
           				<td colspan="2" align="left"><b>Remark:</b><br/>' . $row->remark .  '</td>
           			</tr>
           			<tr>
           				<td colspan="2" align="center"><small>Printed On: '. date('d-m-Y') . '</small></td>
           			</tr>
           		<tbody>';
           	$txt = $txt . '</tbody></table>';
           	$pdf->writeHTML($txt, true, false, false, false, '');


        	$attachment = $pdf->Output($filename, 'S');
        }

        return $attachment;
    }

    function goods_receipt_note_void_valid($goods_receipt_note_id){
    	//$is_void = true;
    	$errMsg = '';

        $goods_receipt_note = $this->get_goods_receipt_note($goods_receipt_note_id);

        foreach ($goods_receipt_note as $row) {
            if ($row->is_void == 0) {
            } else {
                $errMsg = 'This Goods Receipt Note has already been voided.';
            }
        }

        if ($errMsg == '') {
            $arr_return = array('res' => true,'dt' => 'Valid','data' => $goods_receipt_note);
        } else {
            $arr_return = array('res' => false,'dt' => $errMsg,'data' => $goods_receipt_note);
        }
        return $arr_return;
    }
    function submit_void_goods_receipt_note(){
    	$goods_receipt_note_id = $this->input->post('goods_receipt_note_id');

    	$void_date = date('Y-m-d H:i:s');
    	$void_reason = $this->input->post('void_reason');

        $data = array(
            'is_void' => 1,
            'void_reason' => $void_reason,
            'void_user_id' => $this->session->userdata('system_user_id'),
            'void_date' => $void_date
        );
        $this->db->where( array('goods_receipt_note_id' => $goods_receipt_note_id));
        $void = $this->db->update('goods_receipt_notes', $data);

        if ($void) {

        	//RETURN STOCK

        	$outlet_id = 0;
        	$purchase_order_id = 0;
        	$goods_receipt_note_number = '';
        	$goods_receipt_note = $this->get_goods_receipt_note($goods_receipt_note_id);
        	foreach ($goods_receipt_note as $row) {
        		$outlet_id = $row->outlet_id;
        		$purchase_order_id = $row->purchase_order_id;
        		$goods_receipt_note_number = $row->goods_receipt_note_number;
        	}

            $goods_receipt_note_details = $this->get_goods_receipt_note_details($goods_receipt_note_id);

            foreach ($goods_receipt_note_details as $row) {
            	$goods_receipt_note_detail_id = $row->goods_receipt_note_detail_id;
                $quantity = $row->base_quantity;
                $product_id = $row->product_id;
                $product_variation_id = $row->product_variation_id;
                $product_name = $row->product_name;


                $available_stock = 0;
                $reorder_level = 0;
                $outlet_name = '';

                $this->db->select("op.*, o.outlet_name");
				$this->db->from('outlet_products op');
				$this->db->join('outlets o', 'o.outlet_id = op.outlet_id');
                $this->db->where( array('op.outlet_id' => $outlet_id, 'op.product_id' => $product_id, 'op.product_variation_id' => $product_variation_id));
                $outlet_product = $this->db->get()->result();
                foreach ($outlet_product as $row2) {
                    $available_stock = $row2->available_stock;
                    $reorder_level = $row2->reorder_level;
                    $outlet_name = $row2->outlet_name;
                }

                $data = array(
                    'available_stock' =>  $available_stock - $quantity
                );
                $this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                $this->db->update('outlet_products', $data);

                //REORDER LEVEL NOTIFICATION
                if (($available_stock > $reorder_level) && ($available_stock - $quantity <= $reorder_level)) {
                    //NOTIFICATION
                    $data = array(
                        'notification_type' => 'Reorder Level Limit Reached',
                        'notification_ref_id' => $product_id,
                        'notification_details' => 'The product <b>#' . $product_name . '</b> has reached its reorder level limit for outlet <b>' . $outlet_name . '</b> and needs replenishing',
                        'notification_ref_link' => 'be/inventory/low_stocks'
                    );
                    $this->db->insert('notifications',$data);
                }

                //UPDATE TOTAL RECEIVED
				$this->db->select("pod.*, (SELECT COALESCE(SUM(grnd.received_quantity),0) FROM goods_receipt_note_details grnd JOIN goods_receipt_notes grn ON grn.goods_receipt_note_id = grnd.goods_receipt_note_id WHERE grn.purchase_order_id = pod.purchase_order_id AND grn.is_void = 0 AND grnd.product_id = pod.product_id AND grnd.product_variation_id = pod.product_variation_id) AS 'total_received_quantity'");
				$this->db->from('purchase_order_details pod');
				$this->db->where( array('pod.purchase_order_id' => $purchase_order_id, 'pod.product_id' => $product_id, 'pod.product_variation_id' => $product_variation_id));
				$result = $this->db->get()->result();

				$total_received_quantity = 0;

				foreach ($result as $row2){
					$total_received_quantity = $row2->total_received_quantity;
				}
				$data = array(
					'received_quantity' => $total_received_quantity
				);

				$this->db->where( array('purchase_order_id' => $purchase_order_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
				$this->db->update('purchase_order_details', $data);


                //STOCK CHECKER
                $this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $goods_receipt_note_detail_id, 'transaction_description' => 'Goods Received'));
				$this->db->delete('stock_tracker');
            }
        	
        	//NOTIFICATION
            $data = array(
                'notification_type' => 'Goods Receipt Note Voided',
                'notification_ref_id' => $goods_receipt_note_id,
                'notification_details' => 'Goods Receipt Note <b>#' . $goods_receipt_note_number . '</b> has been voided on  <b>' . $void_date . '</b> by <b>' . $this->session->userdata('user_first_name') . ' ' . $this->session->userdata('user_last_name') . '</b>. Void Reason: <b>' . $void_reason . '</b>',
                'notification_ref_link' => 'be/inventory/goods_receipt_note_detail/' . $goods_receipt_note_id
            );
            $this->db->insert('notifications',$data);

             $arr_return = array('res' => true,'dt' => 'Goods Receipt Note voided successfully.');
        } else {
            $arr_return = array('res' => false,'dt' => 'There was an error trying to void this Goods Receipt Note. Please try again.');
        }

        return $arr_return;

    }

	//GOODS RETURN NOTES
	function get_goods_return_notes(){
		// $purchase_order_status = $this->input->post('purchase_order_status');
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $status = $this->input->post('status');
        $outlet_id = $this->input->post('outlet_id');
        $supplier_id = $this->input->post('supplier_id');
        $system_user_id = $this->input->post('system_user_id');

		$this->db->select("goods_return_notes.*, outlets.outlet_id, outlets.outlet_name, suppliers.supplier_id, suppliers.supplier_name, suppliers.phone_number, suppliers.email_address, system_users.system_user_id, system_users.first_name, system_users.last_name, su2.first_name AS 'void_first_name', su2.last_name AS 'void_last_name'");
		$this->db->from('goods_return_notes');
		$this->db->join('outlets', 'outlets.outlet_id = goods_return_notes.outlet_id', 'left outer');
		$this->db->join('suppliers', 'suppliers.supplier_id = goods_return_notes.supplier_id', 'left outer');
		$this->db->join('system_users', 'system_users.system_user_id = goods_return_notes.created_by', 'left outer');
		$this->db->join('system_users su2', 'su2.system_user_id = goods_return_notes.void_user_id', 'left outer');

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(goods_return_notes.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(goods_return_notes.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }

        if ($outlet_id != ''){
      		$this->db->where( array('goods_return_notes.outlet_id' => $outlet_id));
        }

        if ($supplier_id != ''){
      		$this->db->where( array('goods_return_notes.supplier_id' => $supplier_id));
        }

        if ($system_user_id != ''){
      		$this->db->where( array('goods_return_notes.created_by' => $system_user_id));
        }

        if ($status == 'Active') {
        	$this->db->where( array('goods_return_notes.is_void' => 0));
        } elseif ($status == 'Void') {
        	$this->db->where( array('goods_return_notes.is_void' => 1));
        }

        $this->db->order_by("goods_return_notes.goods_return_note_id", "desc");

		return $this->db->get()->result();
	}

	function get_new_goods_return_note_number(){
		$goods_return_note_number = '';
		$prefix_name = '';
		$current_value = 1;
		$this->db->from('prefixes');
		$this->db->where( array('document_name' => 'Goods Return Note'));
		$result = $this->db->get()->result();
		foreach ($result as $row){
			$prefix_name = $row->prefix_name;
			$current_value = $row->current_value;
		}

		$current_value = $current_value + 1;
		$goods_return_note_number = $prefix_name . $current_value;

		return $goods_return_note_number;
	}

	function save_goods_return_note(){
		$data = array(
			'goods_return_note_number' => $this->get_new_goods_return_note_number(),
			'return_date' => $this->input->post('return_date'),
			'supplier_id' => $this->input->post('supplier_id'),
			'outlet_id' => $this->input->post('outlet_id'),
			'sub_total' => $this->input->post('gren_total_detail_subtotal'),
			'freight_cost' => $this->input->post('freight_cost'),
			'total_amount' => $this->input->post('gren_total_detail_total'),
			'total_quantity' => $this->input->post('gren_total_detail_qty'),
			'return_reason' => $this->input->post('return_reason'),
			'remark' => $this->input->post('remark'),
			'created_by' => $this->session->userdata('system_user_id')
		);	

		$insert = $this->db->insert('goods_return_notes', $data);
		$insert_id = $this->db->insert_id();

		if ($insert){

			//PREFIXES
			$this->update_current_goods_return_note_number();

			//PURCHASE ORDER DETAILS
			$this->save_goods_return_note_details($insert_id);

			$arr_return = array('res' => true,'dt' => 'Goods returned successfully.','id' => $insert_id);			
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not return goods successfully. Please try again.','id' => '');
		}
		return $arr_return;
	}

	function update_current_goods_return_note_number(){
		$current_value = 1;
		$this->db->from('prefixes');
		$this->db->where( array('document_name' => 'Goods Return Note'));
		$result = $this->db->get()->result();
		foreach ($result as $row){
			$current_value = $row->current_value;
		}
		$current_value = $current_value + 1;

		$data = array(
			'current_value' => $current_value
		);	
		$this->db->where(array('document_name' => 'Goods Return Note'));
		$this->db->update('prefixes', $data);
	}

	function save_goods_return_note_details($goods_return_note_id){
		$gren_detail_product_id = $this->input->post('gren_detail_product_id');
		$gren_detail_product_variation_id = $this->input->post('gren_detail_product_variation_id');
		$gren_unit_id = $this->input->post('gren_unit_id');
		$gren_detail_qty = $this->input->post('gren_detail_qty');
		$gren_detail_cost = $this->input->post('gren_detail_cost');
		$gren_detail_total = $this->input->post('gren_detail_total');
		
		foreach( $gren_detail_product_id as $key => $n ) {

			$product_id = $n;			
			$unit_id = $gren_unit_id[$key];
			$base_unit_id = 0;
			$quantity = $gren_detail_qty[$key];
			$base_quantity = 0;
			$unit_price = $gren_detail_cost[$key];
			$base_price = 0;
			$conversion_factor = 1;

			$product = $this->get_product($product_id);
			foreach ($product as $row) {
				$base_unit_id = $row->unit_id;
				$product_name = $row->product_name;
			}
        	
        	if ($unit_id != $base_unit_id) {
        		$this->db->from('product_related_units');
            	$this->db->where( array('product_id'=>$product_id, 'related_unit_id'=>$unit_id));
            	$related_unit = $this->db->get()->result();

	            foreach ($related_unit as $row2) {
	                $conversion_factor = $row2->conversion_factor;
	            }
        	}   

        	$base_quantity = $quantity * $conversion_factor;
        	$base_price = ($unit_price/$conversion_factor);

			$new_data = array(
				'goods_return_note_id' => $goods_return_note_id,
				'product_id' => $n,
				'product_variation_id' => $gren_detail_product_variation_id[$key],
				'unit_id' => $gren_unit_id[$key],
				'returned_quantity' => $gren_detail_qty[$key],
				'unit_price' => $gren_detail_cost[$key],
				'base_unit_id' => $base_unit_id,
				'base_quantity' => $base_quantity,
				'base_price' => $base_price,
				'conversion_factor' => $conversion_factor,
				'detail_total_amount' => $gren_detail_total[$key],
				'detail_sub_total' => $gren_detail_total[$key]
			);
			$insert = $this->db->insert('goods_return_note_details', $new_data);
			$goods_return_note_detail_id = $this->db->insert_id();

			$outlet_id = $this->input->post('outlet_id');

			//OUTLETS
			$this->db->select("op.*, o.outlet_name");
			$this->db->from('outlet_products op');
			$this->db->join('outlets o', 'o.outlet_id = op.outlet_id');
			$this->db->where( array('op.outlet_id' => $outlet_id, 'op.product_id' => $n, 'op.product_variation_id' => $gren_detail_product_variation_id[$key]));
			$query = $this->db->get();

			if ($query->num_rows() > 0){
				$available_stock = 0;
				$reorder_level = 0;
				$outlet_name = '';
				$result = $query->result();
				foreach ($result as $row){
					$available_stock = $row->available_stock;
					$reorder_level = $row->reorder_level;
					$outlet_name = $row->outlet_name;
				}
				$available_stock = $available_stock - $base_quantity;
				$data = array(
					'available_stock' => $available_stock
				);	
				$this->db->where(array('product_id'=>$n, 'product_variation_id' => $gren_detail_product_variation_id[$key], 'outlet_id'=>$outlet_id));
				$this->db->update('outlet_products', $data);

				//REORDER LEVEL NOTIFICATION
                if (($available_stock > $reorder_level) && ($available_stock - $base_quantity <= $reorder_level)) {
                    //NOTIFICATION
                    $data = array(
                        'notification_type' => 'Reorder Level Limit Reached',
                        'notification_ref_id' => $product_id,
                        'notification_details' => 'The product <b>#' . $product_name . '</b> has reached its reorder level limit for outlet <b>' . $outlet_name . '</b> and needs replenishing',
                        'notification_ref_link' => 'be/inventory/low_stocks'
                    );
                    $this->db->insert('notifications',$data);
                }
			}

			//STOCK TRACKER
			$data = array(
				'outlet_id' => $outlet_id,
				'product_id' => $n,
				'product_variation_id' => $gren_detail_product_variation_id[$key],
				'transaction_id' => $goods_return_note_detail_id,
				'transaction_type' => 'OUT',
				'transaction_description' => 'Goods Returned',
				'quantity' => $base_quantity,
				'unit_price' => $base_price
			);	

			$this->db->insert('stock_tracker', $data);
		}

	}

	function get_goods_return_note($goods_return_note_id){
		$this->db->select("goods_return_notes.*, outlets.outlet_id, outlets.outlet_name, suppliers.supplier_id, suppliers.supplier_name, suppliers.phone_number, suppliers.email_address, system_users.system_user_id, system_users.first_name, system_users.last_name, su2.first_name AS 'void_first_name', su2.last_name AS 'void_last_name'");
		$this->db->from('goods_return_notes');
		$this->db->join('outlets', 'outlets.outlet_id = goods_return_notes.outlet_id', 'left outer');
		$this->db->join('suppliers', 'suppliers.supplier_id = goods_return_notes.supplier_id', 'left outer');
		$this->db->join('system_users', 'system_users.system_user_id = goods_return_notes.created_by', 'left outer');
		$this->db->join('system_users su2', 'su2.system_user_id = goods_return_notes.void_user_id', 'left outer');

		$this->db->where( array('goods_return_notes.goods_return_note_id' => $goods_return_note_id));
		return $this->db->get()->result();
	}
	function get_goods_return_note_details($goods_return_note_id){
		$this->db->select('goods_return_note_details.*, products.product_id, products.product_sku_code, products.product_barcode, products.product_name, units.unit_name, units.unit_code');
		$this->db->from('goods_return_note_details');
		$this->db->join('products', 'products.product_id = goods_return_note_details.product_id', 'left outer');
		$this->db->join('units', 'units.unit_id = goods_return_note_details.unit_id', 'left outer');

		$this->db->where( array('goods_return_note_details.goods_return_note_id' => $goods_return_note_id));

		$goods_return_note_details = $this->db->get()->result();

        $i = 0;
        foreach($goods_return_note_details as $row){
            $goods_return_note_details[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $goods_return_note_details[$i]->units = $this->get_product_units($row->product_id);
            $i++;
        }
        return $goods_return_note_details;
	}

	function update_goods_return_note(){
		$goods_return_note_id = $this->input->post('goods_return_note_id');

		$data = array(
			'return_date' => $this->input->post('return_date'),
			'supplier_id' => $this->input->post('supplier_id'),
			'outlet_id' => $this->input->post('outlet_id'),
			'sub_total' => $this->input->post('gren_total_detail_subtotal'),
			'freight_cost' => $this->input->post('freight_cost'),
			'total_amount' => $this->input->post('gren_total_detail_total'),
			'total_quantity' => $this->input->post('gren_total_detail_qty'),
			'return_reason' => $this->input->post('return_reason'),
			'remark' => $this->input->post('remark')
		);	

		$this->db->where(array('goods_return_note_id' => $goods_return_note_id));
		$update = $this->db->update('goods_return_notes', $data);
		
		if ($update){

			//GOODS RETURN NOTE DETAILS
			$this->update_goods_return_note_details($goods_return_note_id);

			$arr_return = array('res' => true,'dt' => 'Goods Return Note updated successfully.','id' => $goods_return_note_id);
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not update Goods Return Note successfully. Please try again.','id' => $goods_return_note_id);
		}
		return $arr_return;
	}

	function update_goods_return_note_details($goods_return_note_id){

		$gren_detail_product_id = $this->input->post('gren_detail_product_id');
		$gren_detail_product_variation_id = $this->input->post('gren_detail_product_variation_id');
		$gren_unit_id = $this->input->post('gren_unit_id');
		$gren_detail_qty = $this->input->post('gren_detail_qty');
		$gren_detail_cost = $this->input->post('gren_detail_cost');
		$gren_detail_total = $this->input->post('gren_detail_total');

		$goods_return_note_details = $this->get_goods_return_note_details($goods_return_note_id);

		foreach ($goods_return_note_details as $row){

			$found = false;
			$already_returned_qty = 0;
			$product_id = $row->product_id;
			$product_variation_id = $row->product_variation_id;
			$goods_return_note_detail_id = $row->goods_return_note_detail_id;
			$outlet_id = $this->input->post('outlet_id');

			foreach( $gren_detail_product_id as $key => $n ) {
				if ($product_id == $n && $product_variation_id == $gren_detail_product_variation_id[$key]){
					$already_returned_qty = $row->base_quantity;
					$found = true;
					break;
				}
			}

			if ($found == false){
			   $this->db->where('goods_return_note_detail_id', $goods_return_note_detail_id);
			   $this->db->delete('goods_return_note_details'); 	

			   //OUTLETS
				$this->db->from('outlet_products');
				$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
				$query = $this->db->get();

				if ($query->num_rows() > 0){
					$available_stock = 0;
					$result = $query->result();
					foreach ($result as $row){
						$available_stock = $row->available_stock;
					}
					$available_stock = $available_stock + $already_returned_qty;
					$data = array(
						'available_stock' => $available_stock
					);	
					$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
					$this->db->update('outlet_products', $data);
				}

				//STOCK CHECKER
				$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $goods_return_note_detail_id, 'transaction_description' => 'Goods Returned'));
				$this->db->delete('stock_tracker');

			}else{

				foreach( $gren_detail_product_id as $key => $n ) {
					if ($product_id == $n && $product_variation_id == $gren_detail_product_variation_id[$key]){

						$product_id = $n;			
						$unit_id = $gren_unit_id[$key];
						$base_unit_id = 0;
						$quantity = $gren_detail_qty[$key];
						$base_quantity = 0;
						$unit_price = $gren_detail_cost[$key];
						$base_price = 0;
						$conversion_factor = 1;

						$product = $this->get_product($product_id);
						foreach ($product as $row) {
							$base_unit_id = $row->unit_id;
							$product_name = $row->product_name;
						}
			        	
			        	if ($unit_id != $base_unit_id) {
			        		$this->db->from('product_related_units');
			            	$this->db->where( array('product_id'=>$product_id, 'related_unit_id'=>$unit_id));
			            	$related_unit = $this->db->get()->result();

				            foreach ($related_unit as $row2) {
				                $conversion_factor = $row2->conversion_factor;
				            }
			        	}   

			        	$base_quantity = $quantity * $conversion_factor;
			        	$base_price = ($unit_price/$conversion_factor);

						$data = array(
							'unit_id' => $gren_unit_id[$key],
							'returned_quantity' => $gren_detail_qty[$key],
							'unit_price' => $gren_detail_cost[$key],
							'base_unit_id' => $base_unit_id,
							'base_quantity' => $base_quantity,
							'base_price' => $base_price,
							'conversion_factor' => $conversion_factor,
							'detail_total_amount' => $gren_detail_total[$key],
							'detail_sub_total' => $gren_detail_total[$key]
						);

						$this->db->where(array('goods_return_note_id' => $goods_return_note_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
						$this->db->update('goods_return_note_details', $data);

						$outlet_id = $this->input->post('outlet_id');

						//OUTLETS
						$this->db->select("op.*, o.outlet_name");
						$this->db->from('outlet_products op');
						$this->db->join('outlets o', 'o.outlet_id = op.outlet_id');
						$this->db->where( array('op.outlet_id' => $outlet_id, 'op.product_id' => $product_id, 'op.product_variation_id' => $product_variation_id));
						$query = $this->db->get();

						if ($query->num_rows() > 0){
							$available_stock = 0;
							$reorder_level = 0;
							$outlet_name = '';
							$result = $query->result();
							foreach ($result as $row){
								$available_stock = $row->available_stock;
								$reorder_level = $row->reorder_level;
								$outlet_name = $row->outlet_name;
							}
							$available_stock = ($available_stock + $already_returned_qty) - $base_quantity;
							$data = array(
								'available_stock' => $available_stock
							);	
							$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
							$this->db->update('outlet_products', $data);

							//REORDER LEVEL NOTIFICATION
			                if (($available_stock + $already_returned_qty > $reorder_level) && (($available_stock + $already_returned_qty) - $base_quantity <= $reorder_level)) {
			                    //NOTIFICATION
			                    $data = array(
			                        'notification_type' => 'Reorder Level Limit Reached',
			                        'notification_ref_id' => $product_id,
			                        'notification_details' => 'The product <b>#' . $product_name . '</b> has reached its reorder level limit for outlet <b>' . $outlet_name . '</b> and needs replenishing',
			                        'notification_ref_link' => 'be/inventory/low_stocks'
			                    );
			                    $this->db->insert('notifications',$data);
			                }
						}

						//STOCK CHECKER
						$data = array(
							'quantity' => $base_quantity,
							'unit_price' => $base_price
						);
						$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $goods_return_note_detail_id, 'transaction_description' => 'Goods Returned'));
						$this->db->update('stock_tracker', $data);
					}
				}
			}
		}

		$goods_return_note_details = $this->get_goods_return_note_details($goods_return_note_id);

		foreach( $gren_detail_product_id as $key => $n ) {
			$found = false;
			foreach ($goods_return_note_details as $row){
				if ($row->product_id == $n && $row->product_variation_id == $gren_detail_product_variation_id[$key]){
					$goods_return_note_detail_id = $row->goods_return_note_detail_id;
					$found = true;
					break;
				}
			}
			if ($found == false){

				$product_id = $n;			
				$unit_id = $gren_unit_id[$key];
				$base_unit_id = 0;
				$quantity = $gren_detail_qty[$key];
				$base_quantity = 0;
				$unit_price = $gren_detail_cost[$key];
				$base_price = 0;
				$conversion_factor = 1;

				$product = $this->get_product($product_id);
				foreach ($product as $row) {
					$base_unit_id = $row->unit_id;
					$product_name = $row->product_name;
				}
	        	
	        	if ($unit_id != $base_unit_id) {
	        		$this->db->from('product_related_units');
	            	$this->db->where( array('product_id'=>$product_id, 'related_unit_id'=>$unit_id));
	            	$related_unit = $this->db->get()->result();

		            foreach ($related_unit as $row2) {
		                $conversion_factor = $row2->conversion_factor;
		            }
	        	}   

	        	$base_quantity = $quantity * $conversion_factor;
	        	$base_price = ($unit_price/$conversion_factor);

				$new_data = array(
					'goods_return_note_id' => $goods_return_note_id,
					'product_id' => $n,
					'product_variation_id' => $gren_detail_product_variation_id[$key],
					'unit_id' => $gren_unit_id[$key],
					'returned_quantity' => $gren_detail_qty[$key],
					'unit_price' => $gren_detail_cost[$key],
					'base_unit_id' => $base_unit_id,
					'base_quantity' => $base_quantity,
					'base_price' => $base_price,
					'conversion_factor' => $conversion_factor,
					'detail_total_amount' => $gren_detail_total[$key],
					'detail_sub_total' => $gren_detail_total[$key]
				);
				$this->db->insert('goods_return_note_details', $new_data);

				$outlet_id = $this->input->post('outlet_id');

				//OUTLETS
				$this->db->select("op.*, o.outlet_name");
				$this->db->from('outlet_products op');
				$this->db->join('outlets o', 'o.outlet_id = op.outlet_id');
				$this->db->where( array('op.outlet_id' => $outlet_id, 'op.product_id' => $n, 'op.product_variation_id' => $gren_detail_product_variation_id[$key]));
				$query = $this->db->get();

				if ($query->num_rows() > 0){
					$available_stock = 0;
					$reorder_level = 0;
					$outlet_name = '';
					$result = $query->result();
					foreach ($result as $row){
						$available_stock = $row->available_stock;
						$reorder_level = $row->reorder_level;
						$outlet_name = $row->outlet_name;
					}
					$available_stock = $available_stock - $base_quantity;
					$data = array(
						'available_stock' => $available_stock
					);	
					$this->db->where(array('product_id'=>$n, 'product_variation_id' => $gren_detail_product_variation_id[$key], 'outlet_id'=>$outlet_id));
					$this->db->update('outlet_products', $data);

					//REORDER LEVEL NOTIFICATION
	                if (($available_stock > $reorder_level) && ($available_stock - $base_quantity <= $reorder_level)) {
	                    //NOTIFICATION
	                    $data = array(
	                        'notification_type' => 'Reorder Level Limit Reached',
	                        'notification_ref_id' => $n,
	                        'notification_details' => 'The product <b>#' . $product_name . '</b> has reached its reorder level limit for outlet <b>' . $outlet_name . '</b> and needs replenishing',
	                        'notification_ref_link' => 'be/inventory/low_stocks'
	                    );
	                    $this->db->insert('notifications',$data);
	                }
				}

				//STOCK TRACKER
				$data = array(
					'outlet_id' => $outlet_id,
					'product_id' => $n,
					'product_variation_id' => $gren_detail_product_variation_id[$key],
					'transaction_id' => $goods_return_note_detail_id,
					'transaction_type' => 'OUT',
					'transaction_description' => 'Goods Returned',
					'quantity' => $base_quantity,
					'unit_price' => $base_price
				);	

				$this->db->insert('stock_tracker', $data);
			}
		}
	}

	function submit_send_goods_return_note_via_email() {

        $goods_return_note_id = $this->input->post('goods_return_note_id');

        try {

            ob_start();

            $mail          = new PHPMailer();
            $mail->IsSMTP();

            $use_ssl = $this->input->post('chk_use_ssl');

            if($use_ssl == 'on'){
                $mail->SMTPSecure = 'ssl';
                $mail->SMTPAuth   = true;
            }            
            $mail->Host       = $this->input->post('mail_server_name');
            $mail->Port       = $this->input->post('mail_server_port');
            $mail->Username   = $this->input->post('sender_username');
            $mail->Password   = $this->input->post('sender_password');
            
            $mail->SetFrom($this->input->post('sender_email_address'), $this->input->post('sender_name'));
            $email_to = $this->input->post('recipient_email_address'); 
             
            $mail->Subject = $this->input->post('email_subject');

            $email_message = $this->input->post('email_message'); 

            
            $message = file_get_contents(base_url().'email_temp/emheader');
            $message .= file_get_contents(base_url().'email_temp/embody');
            $message .= file_get_contents(base_url().'email_temp/emfooter');
            $logo = base_url().'assets/fe/img/logo.png';
            
            $replacements = array(
                '({logo})' => $logo, 
                '({message_subject})' => '', 
                '({message_body})' => nl2br( stripslashes( $email_message ) )
            );
            $message = preg_replace(array_keys( $replacements ), array_values( $replacements ), $message );
            
            $plaintext = $message;
            $plaintext = strip_tags( stripslashes( $plaintext ), '<p><br><h2><h3><h1><h4>' );
            $plaintext = str_replace( array( '<p>', '<br />', '<br>', '<h1>', '<h2>', '<h3>', '<h4>' ), PHP_EOL, $plaintext );
            $plaintext = str_replace( array( '</p>', '</h1>', '</h2>', '</h3>', '</h4>' ), '', $plaintext );
            $plaintext = html_entity_decode( stripslashes( $plaintext ) );
        
            
            $mail->MsgHTML( stripslashes( $message ) ); 

            $attachment = $this->generate_goods_return_note_pdf($goods_return_note_id);
            $mail->AddStringAttachment($attachment, 'Bethany House Goods Return Note -'. $goods_return_note_id . '.pdf', 'base64', 'application/pdf');
            
            $mail->AltBody = $plaintext;
            $mail->AddAddress($email_to, "");

            if( !$mail->Send() ){
                $arr_return = array('res' => false,'dt' => $mail->ErrorInfo);
            }else{
                $arr_return = array('res' => true,'dt' => 'Email Sent successfully');
            }
            ob_get_clean();
        } catch (phpmailerException $e) {
            $arr_return = array('res' => false,'dt' =>  $e->errorMessage());
        } catch (Exception $e) {
            $arr_return = array('res' => false,'dt' =>  $e->getMessage());
        }        
        return $arr_return;
    }

    function generate_goods_return_note_pdf($goods_return_note_id){
    	$goods_return_note = $this->inventory_model->get_goods_return_note($goods_return_note_id);
		$goods_return_note_details = $this->inventory_model->get_goods_return_note_details($goods_return_note_id);

		$default_currency = $this->currencies_model->get_default_currency();
		$store_information = $this->store_information_model->get_store_information();

		$attachment = '';

		foreach ($goods_return_note as $row) {

			$filename='Bethany House Goods Return Note - '.$row->goods_return_note_number.'.pdf';

            // create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Bethany House');
            $pdf->SetTitle('Bethany House Goods Return Note - '.$row->goods_return_note_number);
            $pdf->SetSubject('Bethany House Goods Return Note - '.$row->goods_return_note_number);
            //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

            // remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set font
            $pdf->SetFont('helvetica', '', 8);

            $pdf->setCellHeightRatio(1.6);

            // add a page
            $pdf->AddPage();

            $pdf->Ln(10);

            $txt = '<table border="1" cellpadding="5" cellspacing="0">';
            $txt = $txt . '<thead>';
            $store_logo = '';
            foreach ($store_information as $row2){
                if($row2->store_logo != '' && file_exists("./uploads/store_logo/" . $row2->store_logo)){
                    $store_logo = base_url() . 'uploads/store_logo/' . $row2->store_logo;
                } else {
                    $store_logo = base_url() . 'assets/fe/img/logo.png';
                }
            }
            $txt = $txt . '<tr>
            	<td rowspan="4" width="224"><img src="' . $store_logo . '"  width="200px"><br /><br />';

            	// </td>
            	// <td rowspan="2" width="200">';

            	foreach ($store_information as $row2){
                    $txt = $txt . '<b>' . $row2->store_name . '</b><br />
                    <b>Phone:</b> ' . $row2->phone_number . '<br />
                    <b>Address:</b> ' . $row2->physical_address . '<br />
                    <b>Email:</b> ' . $row2->email_address;
                }
            	$txt = $txt . '</td>
            	<td rowspan="3"></td>
            	<td><b>Goods Return Note No:</b> ' . $row->goods_return_note_number . '</td>
            	</tr>
            	<tr>
            		<td><b>Outlet:</b> ' . $row->outlet_name . '</td>
            	</tr>
            	<tr>
            		<td><b>Return Date:</b> ' . date('d M, Y', strtotime($row->return_date)) . '</td>
            	</tr>
            	<tr>
            		<td colspan="2"><b>Supplier:</b><br/>' . $row->supplier_name . '<br/>' . $row->email_address . '<br/>' . $row->phone_number . '</td>
            	</tr></thead></table>';


            $pdf->writeHTML($txt, true, false, false, false, '');

            $txt = '<table border="1" cellpadding="5" cellspacing="0">
           		<thead>
           			<tr>
           				<td width="30"><b>#</b></td>
           				<td width="340"><b>Product Name</b></td>
           				<td width="90"><b>Returned</b></td>
           				<td width="100"><b>Unit Cost</b></td>
           				<td width="110"><b>Amount</b></td>
           			</tr>
           		</thead>
           		<tbody>';
           	$count = 1;
           	foreach ($goods_return_note_details as $row2){
           		$txt = $txt . '<tr>
           			<td width="30">' . $count . '</td>
       				<td width="340">'. $row2->product_name . '<br><i>SKU: ' . $row2->product_sku_code . '</i></td>
       				<td width="90">' . number_format($row2->returned_quantity) . '</td>
       				<td width="100">' . $default_currency . ' ' . number_format($row2->unit_price,2) . '</td>
       				<td width="110">' . $default_currency . ' ' . number_format($row2->detail_total_amount,2) . '</td>
           		</tr>';
           		$count++;
           	}
           	$txt = $txt . '<tr>
           		<td colspan="4" align="right"><b>Subtotal</b></td>
           		<td><b>' . $default_currency . ' ' . number_format($row->sub_total,2) . '</b></td>
           	</tr>';
           	$txt = $txt . '<tr>
           		<td colspan="4" align="right"><b>Freight</b></td>
           		<td><b>' . $default_currency . ' ' . number_format($row->freight_cost,2) . '</b></td>
           	</tr>';
           	$txt = $txt . '<tr>
           		<td colspan="4" align="right"><b>Total</b></td>
           		<td><b>' . $default_currency . ' ' . number_format($row->total_amount,2) . '</b></td>
           	</tr>';
           	
           $txt = $txt . '</tbody></table>';

           	$pdf->writeHTML($txt, true, false, false, false, '');

           	$txt = '<table border="1" cellpadding="5" cellspacing="0">
           			<tr>
           				<td colspan="2" align="left"><b>Remark:</b><br/>' . $row->remark .  '</td>
           			</tr>
           			<tr>
           				<td colspan="2" align="center"><small>Printed On: '. date('d-m-Y') . '</small></td>
           			</tr>
           		<tbody>';
           	$txt = $txt . '</tbody></table>';
           	$pdf->writeHTML($txt, true, false, false, false, '');


        	$attachment = $pdf->Output($filename, 'S');
        }

        return $attachment;
    }

    function goods_return_note_void_valid($goods_return_note_id){
    	//$is_void = true;
    	$errMsg = '';

        $goods_return_note = $this->get_goods_return_note($goods_return_note_id);

        foreach ($goods_return_note as $row) {
            if ($row->is_void == 0) {
            } else {
                $errMsg = 'This Goods Return Note has already been voided.';
            }
        }

        if ($errMsg == '') {
            $arr_return = array('res' => true,'dt' => 'Valid','data' => $goods_return_note);
        } else {
            $arr_return = array('res' => false,'dt' => $errMsg,'data' => $goods_return_note);
        }
        return $arr_return;
    }
    function submit_void_goods_return_note(){
    	$goods_return_note_id = $this->input->post('goods_return_note_id');

    	$void_date = date('Y-m-d H:i:s');
    	$void_reason = $this->input->post('void_reason');

        $data = array(
            'is_void' => 1,
            'void_reason' => $void_reason,
            'void_user_id' => $this->session->userdata('system_user_id'),
            'void_date' => $void_date
        );
        
        $this->db->where( array('goods_return_note_id' => $goods_return_note_id));
        $void = $this->db->update('goods_return_notes', $data);

        if ($void) {

        	//RETURN STOCK
        	$outlet_id = 0;
        	$goods_return_note_number = '';
        	$goods_return_note = $this->get_goods_return_note($goods_return_note_id);
        	foreach ($goods_return_note as $row) {
        		$outlet_id = $row->outlet_id;
        		$goods_return_note_number = $row->goods_return_note_number;
        	}

            $goods_return_note_details = $this->get_goods_return_note_details($goods_return_note_id);

            foreach ($goods_return_note_details as $row) {
                $quantity = $row->base_quantity;
                $product_id = $row->product_id;
                $product_variation_id = $row->product_variation_id;
                $goods_return_note_detail_id = $row->goods_return_note_detail_id;

                $available_stock = 0;

                $this->db->select("*");
                $this->db->from('outlet_products');
                $this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                $outlet_product = $this->db->get()->result();
                foreach ($outlet_product as $row2) {
                    $available_stock = $row2->available_stock;
                }

                $data = array(
                    'available_stock' =>  $available_stock + $quantity
                );
                $this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                $this->db->update('outlet_products', $data);

                //STOCK CHECKER
                $this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $goods_return_note_detail_id, 'transaction_description' => 'Goods Returned'));
				$this->db->delete('stock_tracker');
            }

            //NOTIFICATION
            $data = array(
                'notification_type' => 'Goods Return Note Voided',
                'notification_ref_id' => $goods_return_note_id,
                'notification_details' => 'Goods Return Note <b>#' . $goods_return_note_number . '</b> has been voided on  <b>' . $void_date . '</b> by <b>' . $this->session->userdata('user_first_name') . ' ' . $this->session->userdata('user_last_name') . '</b>. Void Reason: <b>' . $void_reason . '</b>',
                'notification_ref_link' => 'be/inventory/goods_return_note_detail/' . $goods_return_note_id
            );
            $this->db->insert('notifications',$data);

             $arr_return = array('res' => true,'dt' => 'Goods Return Note voided successfully.');
        } else {
            $arr_return = array('res' => false,'dt' => 'There was an error trying to void this Goods Return Note. Please try again.');
        }

        return $arr_return;

    }


	//CREDIT NOTES
	function get_credit_notes(){
		$this->db->select("credit_notes.*, outlets.outlet_id, outlets.outlet_name, suppliers.supplier_id, suppliers.supplier_name, suppliers.phone_number, suppliers.email_address, system_users.system_user_id, system_users.first_name, system_users.last_name");
		$this->db->from('credit_notes');
		$this->db->join('outlets', 'outlets.outlet_id = credit_notes.outlet_id', 'left outer');
		$this->db->join('suppliers', 'suppliers.supplier_id = credit_notes.supplier_id', 'left outer');
		$this->db->join('system_users', 'system_users.system_user_id = credit_notes.created_by', 'left outer');

		return $this->db->get()->result();
	}

	function get_new_credit_note_number(){
		$credit_note_number = '';
		$prefix_name = '';
		$current_value = 1;
		$this->db->from('prefixes');
		$this->db->where( array('document_name' => 'Credit Note'));
		$result = $this->db->get()->result();
		foreach ($result as $row){
			$prefix_name = $row->prefix_name;
			$current_value = $row->current_value;
		}

		$current_value = $current_value + 1;
		$credit_note_number = $prefix_name . $current_value;

		return $credit_note_number;
	}

	function save_credit_note(){
		$data = array(
			'credit_note_number' => $this->get_new_credit_note_number(),
			'return_date' => $this->input->post('return_date'),
			'supplier_id' => $this->input->post('supplier_id'),
			'outlet_id' => $this->input->post('outlet_id'),
			'sub_total' => $this->input->post('gren_total_detail_subtotal'),
			'freight_cost' => $this->input->post('freight_cost'),
			'total_amount' => $this->input->post('gren_total_detail_total'),
			'total_quantity' => $this->input->post('gren_total_detail_qty'),
			'return_reason' => $this->input->post('return_reason'),
			'remark' => $this->input->post('remark'),
			'created_by' => $this->session->userdata('system_user_id')
		);	

		$insert = $this->db->insert('credit_notes', $data);
		$insert_id = $this->db->insert_id();

		if ($insert){

			//PREFIXES
			$this->update_current_credit_note_number();

			//PURCHASE ORDER DETAILS
			$this->save_credit_note_details($insert_id);

			$arr_return = array('res' => true,'dt' => 'Credit Note added successfully.','id' => $insert_id);			
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not add Credit Note successfully. Please try again.','id' => '');
		}
		return $arr_return;
	}

	function update_current_credit_note_number(){
		$current_value = 1;
		$this->db->from('prefixes');
		$this->db->where( array('document_name' => 'Credit Note'));
		$result = $this->db->get()->result();
		foreach ($result as $row){
			$current_value = $row->current_value;
		}
		$current_value = $current_value + 1;

		$data = array(
			'current_value' => $current_value
		);	
		$this->db->where(array('document_name' => 'Credit Note'));
		$this->db->update('prefixes', $data);
	}

	function save_credit_note_details($credit_note_id){
		$gren_detail_id = $this->input->post('gren_detail_id');
		$gren_detail_qty = $this->input->post('gren_detail_qty');
		$gren_detail_cost = $this->input->post('gren_detail_cost');
		$gren_detail_total = $this->input->post('gren_detail_total');
		
		foreach( $gren_detail_id as $key => $n ) {

			//Credit Note DETAILS
			$new_data = array(
				'credit_note_id' => $credit_note_id,
				'product_id' => $n,
				'returned_quantity' => $gren_detail_qty[$key],
				'unit_price' => $gren_detail_cost[$key],
				'detail_total_amount' => $gren_detail_total[$key],
				'detail_sub_total' => $gren_detail_total[$key]
			);
			$insert = $this->db->insert('credit_note_details', $new_data);

			$outlet_id = $this->input->post('outlet_id');

			//OUTLETS
			$this->db->from('outlet_products');
			$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $n));
			$query = $this->db->get();

			if ($query->num_rows() > 0){
				$available_stock = 0;
				$result = $query->result();
				foreach ($result as $row){
					$available_stock = $row->available_stock;
				}
				$available_stock = $available_stock - $gren_detail_qty[$key];
				$data = array(
					'available_stock' => $available_stock
				);	
				$this->db->where(array('product_id'=>$n, 'outlet_id'=>$outlet_id));
				$this->db->update('outlet_products', $data);
			}
		}
	}

	function get_credit_note($credit_note_id){
		$this->db->select("credit_notes.*, outlets.outlet_id, outlets.outlet_name, suppliers.supplier_id, suppliers.supplier_name, suppliers.phone_number, suppliers.email_address, system_users.system_user_id, system_users.first_name, system_users.last_name");
		$this->db->from('credit_notes');
		$this->db->join('outlets', 'outlets.outlet_id = credit_notes.outlet_id', 'left outer');
		$this->db->join('suppliers', 'suppliers.supplier_id = credit_notes.supplier_id', 'left outer');
		$this->db->join('system_users', 'system_users.system_user_id = credit_notes.created_by', 'left outer');

		$this->db->where( array('credit_notes.credit_note_id' => $credit_note_id));
		return $this->db->get()->result();
	}
	function get_credit_note_details($credit_note_id){
		$this->db->select('credit_note_details.*, products.product_id, products.product_sku_code, products.product_barcode, products.product_name');
		$this->db->from('credit_note_details');
		$this->db->join('products', 'products.product_id = credit_note_details.product_id', 'left outer');

		$this->db->where( array('credit_note_details.credit_note_id' => $credit_note_id));
		return $this->db->get()->result();
	}

	function update_credit_note(){
		$credit_note_id = $this->input->post('credit_note_id');

		$data = array(
			'return_date' => $this->input->post('return_date'),
			'supplier_id' => $this->input->post('supplier_id'),
			'outlet_id' => $this->input->post('outlet_id'),
			'sub_total' => $this->input->post('gren_total_detail_subtotal'),
			'freight_cost' => $this->input->post('freight_cost'),
			'total_amount' => $this->input->post('gren_total_detail_total'),
			'total_quantity' => $this->input->post('gren_total_detail_qty'),
			'return_reason' => $this->input->post('return_reason'),
			'remark' => $this->input->post('remark')
		);	

		$this->db->where(array('credit_note_id' => $credit_note_id));
		$update = $this->db->update('credit_notes', $data);
		
		if ($update){

			//Credit Note DETAILS
			$this->update_credit_note_details($credit_note_id);

			$arr_return = array('res' => true,'dt' => 'Credit Note updated successfully.','id' => $credit_note_id);
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not update Credit Note successfully. Please try again.','id' => $credit_note_id);
		}
		return $arr_return;
	}

	function update_credit_note_details($credit_note_id){
		$gren_detail_id = $this->input->post('gren_detail_id');
		$gren_detail_qty = $this->input->post('gren_detail_qty');
		$gren_detail_cost = $this->input->post('gren_detail_cost');
		$gren_detail_total = $this->input->post('gren_detail_total');

		$credit_note_details = $this->get_credit_note_details($credit_note_id);

		foreach ($credit_note_details as $row){
			$found = false;
			$already_returned_qty = 0;
			foreach( $gren_detail_id as $key => $n ) {
				if ($row->product_id == $n){
					$already_returned_qty = $row->returned_quantity;
					$found = true;
					break;
				}
			}
			if ($found == false){
			   $this->db->where('credit_note_id', $credit_note_id);
			   $this->db->where('product_id', $row->product_id);			   
			   $this->db->delete('credit_note_details'); 	

			   $outlet_id = $this->input->post('outlet_id');

			   //OUTLETS
				$this->db->from('outlet_products');
				$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $n));
				$query = $this->db->get();

				if ($query->num_rows() > 0){
					$available_stock = 0;
					$result = $query->result();
					foreach ($result as $row){
						$available_stock = $row->available_stock;
					}
					$available_stock = $available_stock + $already_returned_qty;
					$data = array(
						'available_stock' => $available_stock
					);	
					$this->db->where(array('product_id'=>$n, 'outlet_id'=>$outlet_id));
					$this->db->update('outlet_products', $data);
				}
			}else{
				$data = array(
					'returned_quantity' => $gren_detail_qty[$key],
					'unit_price' => $gren_detail_cost[$key],
					'detail_total_amount' => $gren_detail_total[$key],
					'detail_sub_total' => $gren_detail_total[$key]
				);
				$this->db->where(array('credit_note_id' => $credit_note_id, 'product_id' => $row->product_id));
				$this->db->update('credit_note_details', $data);

				$outlet_id = $this->input->post('outlet_id');

				//OUTLETS
				$this->db->from('outlet_products');
				$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $n));
				$query = $this->db->get();

				if ($query->num_rows() > 0){
					$available_stock = 0;
					$result = $query->result();
					foreach ($result as $row){
						$available_stock = $row->available_stock;
					}
					$available_stock = ($available_stock + $already_returned_qty) - $gren_detail_qty[$key];
					$data = array(
						'available_stock' => $available_stock
					);	
					$this->db->where(array('product_id'=>$n, 'outlet_id'=>$outlet_id));
					$this->db->update('outlet_products', $data);
				}
			}
		}

		$credit_note_details = $this->get_credit_note_details($credit_note_id);

		foreach( $gren_detail_id as $key => $n ) {
			$found = false;
			foreach ($credit_note_details as $row){
				if ($row->product_id == $n){
					$found = true;
					break;
				}
			}
			if ($found == false){
				$new_data = array(
					'credit_note_id' => $credit_note_id,
					'product_id' => $n,
					'returned_quantity' => $gren_detail_qty[$key],
					'unit_price' => $gren_detail_cost[$key],
					'detail_total_amount' => $gren_detail_total[$key],
					'detail_sub_total' => $gren_detail_total[$key]

				);
				$this->db->insert('credit_note_details', $new_data);

				$outlet_id = $this->input->post('outlet_id');

				//OUTLETS
				$this->db->from('outlet_products');
				$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $n));
				$query = $this->db->get();

				if ($query->num_rows() > 0){
					$available_stock = 0;
					$result = $query->result();
					foreach ($result as $row){
						$available_stock = $row->available_stock;
					}
					$available_stock = $available_stock - $gren_detail_qty[$key];
					$data = array(
						'available_stock' => $available_stock
					);	
					$this->db->where(array('product_id'=>$n, 'outlet_id'=>$outlet_id));
					$this->db->update('outlet_products', $data);
				}

			}
		}
	}

	function submit_send_credit_note_via_email() {

        $credit_note_id = $this->input->post('credit_note_id');

        try {

            ob_start();

            $mail          = new PHPMailer();
            $mail->IsSMTP();

            $use_ssl = $this->input->post('chk_use_ssl');

            if($use_ssl == 'on'){
                $mail->SMTPSecure = 'ssl';
                $mail->SMTPAuth   = true;
            }            
            $mail->Host       = $this->input->post('mail_server_name');
            $mail->Port       = $this->input->post('mail_server_port');
            $mail->Username   = $this->input->post('sender_username');
            $mail->Password   = $this->input->post('sender_password');
            
            $mail->SetFrom($this->input->post('sender_email_address'), $this->input->post('sender_name'));
            $email_to = $this->input->post('recipient_email_address'); 
             
            $mail->Subject = $this->input->post('email_subject');

            $email_message = $this->input->post('email_message'); 

            
            $message = file_get_contents(base_url().'email_temp/emheader');
            $message .= file_get_contents(base_url().'email_temp/embody');
            $message .= file_get_contents(base_url().'email_temp/emfooter');
            $logo = base_url().'assets/fe/img/logo.png';
            
            $replacements = array(
                '({logo})' => $logo, 
                '({message_subject})' => '', 
                '({message_body})' => nl2br( stripslashes( $email_message ) )
            );
            $message = preg_replace(array_keys( $replacements ), array_values( $replacements ), $message );
            
            $plaintext = $message;
            $plaintext = strip_tags( stripslashes( $plaintext ), '<p><br><h2><h3><h1><h4>' );
            $plaintext = str_replace( array( '<p>', '<br />', '<br>', '<h1>', '<h2>', '<h3>', '<h4>' ), PHP_EOL, $plaintext );
            $plaintext = str_replace( array( '</p>', '</h1>', '</h2>', '</h3>', '</h4>' ), '', $plaintext );
            $plaintext = html_entity_decode( stripslashes( $plaintext ) );
        
            
            $mail->MsgHTML( stripslashes( $message ) ); 

            $attachment = $this->generate_credit_note_pdf($credit_note_id);
            $mail->AddStringAttachment($attachment, 'Bethany House Credit Note -'. $credit_note_id . '.pdf', 'base64', 'application/pdf');
            
            $mail->AltBody = $plaintext;
            $mail->AddAddress($email_to, "");

            if( !$mail->Send() ){
                $arr_return = array('res' => false,'dt' => $mail->ErrorInfo);
            }else{
                $arr_return = array('res' => true,'dt' => 'Email Sent successfully');
            }
            ob_get_clean();
        } catch (phpmailerException $e) {
            $arr_return = array('res' => false,'dt' =>  $e->errorMessage());
        } catch (Exception $e) {
            $arr_return = array('res' => false,'dt' =>  $e->getMessage());
        }        
        return $arr_return;
    }

    function generate_credit_note_pdf($credit_note_id){
    	$credit_note = $this->inventory_model->get_credit_note($credit_note_id);
		$credit_note_details = $this->inventory_model->get_credit_note_details($credit_note_id);

		$default_currency = $this->currencies_model->get_default_currency();
		$store_information = $this->store_information_model->get_store_information();

		$attachment = '';

		foreach ($credit_note as $row) {

			$filename='Bethany House Credit Note - '.$row->credit_note_number.'.pdf';

            // create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Bethany House');
            $pdf->SetTitle('Bethany House Credit Note - '.$row->credit_note_number);
            $pdf->SetSubject('Bethany House Credit Note - '.$row->credit_note_number);
            //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

            // remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set font
            $pdf->SetFont('helvetica', '', 8);

            $pdf->setCellHeightRatio(1.6);

            // add a page
            $pdf->AddPage();

            $pdf->Ln(10);

            $txt = '<table border="1" cellpadding="5" cellspacing="0">';
            $txt = $txt . '<thead>';
            $store_logo = '';
            foreach ($store_information as $row2){
                if($row2->store_logo != '' && file_exists("./uploads/store_logo/" . $row2->store_logo)){
                    $store_logo = base_url() . 'uploads/store_logo/' . $row2->store_logo;
                } else {
                    $store_logo = base_url() . 'assets/fe/img/logo.png';
                }
            }
            $txt = $txt . '<tr>
            	<td rowspan="4" width="224"><img src="' . $store_logo . '"  width="200px"><br /><br />';

            	// </td>
            	// <td rowspan="2" width="200">';

            	foreach ($store_information as $row2){
                    $txt = $txt . '<b>' . $row2->store_name . '</b><br />
                    <b>Phone:</b> ' . $row2->phone_number . '<br />
                    <b>Address:</b> ' . $row2->physical_address . '<br />
                    <b>Email:</b> ' . $row2->email_address;
                }
            	$txt = $txt . '</td>
            	<td rowspan="3"></td>
            	<td><b>Credit Note No:</b> ' . $row->credit_note_number . '</td>
            	</tr>
            	<tr>
            		<td><b>Outlet:</b> ' . $row->outlet_name . '</td>
            	</tr>
            	<tr>
            		<td><b>Return Date:</b> ' . date('d M, Y', strtotime($row->return_date)) . '</td>
            	</tr>
            	<tr>
            		<td colspan="2"><b>Supplier:</b><br/>' . $row->supplier_name . '<br/>' . $row->email_address . '<br/>' . $row->phone_number . '</td>
            	</tr></thead></table>';


            $pdf->writeHTML($txt, true, false, false, false, '');

            $txt = '<table border="1" cellpadding="5" cellspacing="0">
           		<thead>
           			<tr>
           				<td width="30"><b>#</b></td>
           				<td width="340"><b>Product Name</b></td>
           				<td width="90"><b>Returned</b></td>
           				<td width="100"><b>Unit Cost</b></td>
           				<td width="110"><b>Amount</b></td>
           			</tr>
           		</thead>
           		<tbody>';
           	$count = 1;
           	foreach ($credit_note_details as $row2){
           		$txt = $txt . '<tr>
           			<td width="30">' . $count . '</td>
       				<td width="340">'. $row2->product_name . '<br><i>SKU: ' . $row2->product_sku_code . '</i></td>
       				<td width="90">' . number_format($row2->returned_quantity) . '</td>
       				<td width="100">' . $default_currency . ' ' . number_format($row2->unit_price,2) . '</td>
       				<td width="110">' . $default_currency . ' ' . number_format($row2->detail_total_amount,2) . '</td>
           		</tr>';
           		$count++;
           	}
           	$txt = $txt . '<tr>
           		<td colspan="4" align="right"><b>Subtotal</b></td>
           		<td><b>' . $default_currency . ' ' . number_format($row->sub_total,2) . '</b></td>
           	</tr>';
           	$txt = $txt . '<tr>
           		<td colspan="4" align="right"><b>Freight</b></td>
           		<td><b>' . $default_currency . ' ' . number_format($row->freight_cost,2) . '</b></td>
           	</tr>';
           	$txt = $txt . '<tr>
           		<td colspan="4" align="right"><b>Total</b></td>
           		<td><b>' . $default_currency . ' ' . number_format($row->total_amount,2) . '</b></td>
           	</tr>';
           	
           $txt = $txt . '</tbody></table>';

           	$pdf->writeHTML($txt, true, false, false, false, '');

           	$txt = '<table border="1" cellpadding="5" cellspacing="0">
           			<tr>
           				<td colspan="2" align="left"><b>Remark:</b><br/>' . $row->remark .  '</td>
           			</tr>
           			<tr>
           				<td colspan="2" align="center"><small>Printed On: '. date('d-m-Y') . '</small></td>
           			</tr>
           		<tbody>';
           	$txt = $txt . '</tbody></table>';
           	$pdf->writeHTML($txt, true, false, false, false, '');


        	$attachment = $pdf->Output($filename, 'S');
        }

        return $attachment;
    }

    function credit_note_void_valid($credit_note_id){
    	//$is_void = true;
    	$errMsg = '';

        $credit_note = $this->get_credit_note($credit_note_id);

        foreach ($credit_note as $row) {
            if ($row->is_void == 0) {
            } else {
                $errMsg = 'This Credit Note has already been voided.';
            }
        }

        if ($errMsg == '') {
            $arr_return = array('res' => true,'dt' => 'Valid','data' => $credit_note);
        } else {
            $arr_return = array('res' => false,'dt' => $errMsg,'data' => $credit_note);
        }
        return $arr_return;
    }
    function submit_void_credit_note(){
    	$credit_note_id = $this->input->post('credit_note_id');

        $data = array(
            'is_void' => 1,
            'void_reason' => $this->input->post('void_reason'),
            'void_user_id' => $this->session->userdata('system_user_id'),
            'void_date' => date('Y-m-d H:i:s')
        );
        $this->db->where( array('credit_note_id' => $credit_note_id));
        $void = $this->db->update('credit_notes', $data);

        if ($void) {

        	//RETURN STOCK
        	$outlet_id = 0;
        	$credit_note = $this->get_credit_note($credit_note_id);
        	foreach ($credit_note as $row) {
        		$outlet_id = $row->outlet_id;
        	}

            $credit_note_details = $this->get_credit_note_details($credit_note_id);

            foreach ($credit_note_details as $row) {
                $quantity = $row->returned_quantity;
                $product_id = $row->product_id;
                $product_variation_id = $row->product_variation_id;


                $available_stock = 0;

                $this->db->select("*");
                $this->db->from('outlet_products');
                $this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                $outlet_product = $this->db->get()->result();
                foreach ($outlet_product as $row2) {
                    $available_stock = $row2->available_stock;
                }

                $data = array(
                    'available_stock' =>  $available_stock + $quantity
                );
                $this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                $this->db->update('outlet_products', $data);
            }

             $arr_return = array('res' => true,'dt' => 'Credit Note voided successfully.');
        } else {
            $arr_return = array('res' => false,'dt' => 'There was an error trying to void this Credit Note. Please try again.');
        }

        return $arr_return;

    }


	//STOCK TRANSFERS
	function get_stock_transfers(){
		// $purchase_order_status = $this->input->post('purchase_order_status');
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $status = $this->input->post('status');
        $source_outlet_id = $this->input->post('source_outlet_id');
        $destination_outlet_id = $this->input->post('destination_outlet_id');
        $system_user_id = $this->input->post('system_user_id');

		$this->db->select("stock_transfers.*, so.outlet_id as 'source_outlet_id', so.outlet_name as 'source_outlet_name', do.outlet_id as 'destination_outlet_id', do.outlet_name as 'destination_outlet_name', system_users.system_user_id, system_users.first_name, system_users.last_name, su2.first_name AS 'void_first_name', su2.last_name AS 'void_last_name'");
		$this->db->from('stock_transfers');
		$this->db->join('outlets so', 'so.outlet_id = stock_transfers.source_outlet_id', 'left outer');
		$this->db->join('outlets do', 'do.outlet_id = stock_transfers.destination_outlet_id', 'left outer');
		$this->db->join('system_users', 'system_users.system_user_id = stock_transfers.created_by', 'left outer');
		$this->db->join('system_users su2', 'su2.system_user_id = stock_transfers.void_user_id', 'left outer');

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(stock_transfers.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(stock_transfers.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }

        if ($source_outlet_id != ''){
      		$this->db->where( array('stock_transfers.source_outlet_id' => $source_outlet_id));
        }

        if ($destination_outlet_id != ''){
      		$this->db->where( array('stock_transfers.destination_outlet_id' => $destination_outlet_id));
        }

        if ($system_user_id != ''){
      		$this->db->where( array('stock_transfers.created_by' => $system_user_id));
        }

        if ($status == 'Active') {
        	$this->db->where( array('stock_transfers.is_void' => 0));
        } elseif ($status == 'Void') {
        	$this->db->where( array('stock_transfers.is_void' => 1));
        }

        $this->db->order_by("stock_transfers.stock_transfer_id", "desc");

		return $this->db->get()->result();
	}

	function get_new_stock_transfer_number(){
		$stock_transfer_number = '';
		$prefix_name = '';
		$current_value = 1;
		$this->db->from('prefixes');
		$this->db->where( array('document_name' => 'Stock Transfer'));
		$result = $this->db->get()->result();
		foreach ($result as $row){
			$prefix_name = $row->prefix_name;
			$current_value = $row->current_value;
		}

		$current_value = $current_value + 1;
		$stock_transfer_number = $prefix_name . $current_value;

		return $stock_transfer_number;
	}

	function save_stock_transfer(){

		$valid_transfer = $this->validate_transfer_stock();

		if ($valid_transfer == ''){
			$data = array(
				'stock_transfer_number' => $this->get_new_stock_transfer_number(),
				'transfer_date' => $this->input->post('transfer_date'),
				'source_outlet_id' => $this->input->post('source_outlet_id'),
				'destination_outlet_id' => $this->input->post('destination_outlet_id'),
				'total_quantity' => $this->input->post('stck_total_detail_qty'),
				'remark' => $this->input->post('remark'),
				'created_by' => $this->session->userdata('system_user_id')
			);	

			$insert = $this->db->insert('stock_transfers', $data);
			$insert_id = $this->db->insert_id();

			if ($insert){

				//PREFIXES
				$this->update_current_stock_transfer_number();

				//PURCHASE ORDER DETAILS
				$this->save_stock_transfer_details($insert_id);

				$arr_return = array('res' => true,'dt' => 'Stock transfered successfully.','id' => $insert_id);			
			}else{
				$arr_return = array('res' => false,'dt' => 'Could not transfer stock successfully. Please try again.','id' => '');
			}
		}else{
			$arr_return = array('res' => false,'dt' => $valid_transfer,'id' => '');
		}
		return $arr_return;
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

	function get_product_name($product_id){
		$product_name = '';
		$this->db->where(array('product_id' => $product_id));
		$query = $this->db->get('products')->result();

		foreach($query as $row){
			$product_name = $row->product_name;
		}
		return $product_name;
	}

	function get_product_with_variation_name($product_id, $product_variation_id){

		$product_name = '';
		$this->db->where(array('product_id' => $product_id));
		$query = $this->db->get('products')->result();

		foreach($query as $row){
			$product_name = $row->product_name;
		}

		$product_attributes = $this->get_product_variation_attributes($product_variation_id);
		$variation_description = '';
        foreach ($product_attributes as $row){
            $variation_description = $variation_description . $row->product_attribute_name . ' : <b>' . $row->product_attribute_value . '</b>, ';
        }
        if ($variation_description != ''){
        	$variation_description = ' (' . substr($variation_description,0,-2) . ')';
        }

        $product_name = $product_name . $variation_description;

		return $product_name;
	}

	function validate_transfer_stock(){

		$msgerr = '';

		$stck_detail_product_id = $this->input->post('stck_detail_product_id');
		$stck_detail_product_variation_id = $this->input->post('stck_detail_product_variation_id');
		$stck_detail_qty = $this->input->post('stck_detail_qty');
		$source_outlet_id = $this->input->post('source_outlet_id');

		foreach( $stck_detail_product_id as $key => $n ) {

			$product_name = $this->get_product_with_variation_name($n, $stck_detail_product_variation_id[$key]);

			$this->db->from('outlet_products');
			$this->db->where( array('outlet_id' => $source_outlet_id, 'product_id' => $n, 'product_variation_id' => $stck_detail_product_variation_id[$key]));
			$query = $this->db->get();

			if ($query->num_rows() > 0){
				$available_stock = 0;
				$result = $query->result();
				foreach ($result as $row){
					$available_stock = $row->available_stock;
				}

				if ($available_stock < (int)$stck_detail_qty[$key]){
					$msgerr = $msgerr .  "<i class='fa fa-exclamation-circle'></i> The product " . $product_name . " has less available stock [" . $available_stock . "] than the transfer stock [" . $stck_detail_qty[$key] . "]<br>";
				}
			}else{
				$msgerr = $msgerr . "<i class='fa fa-exclamation-circle'></i> The product " . $product_name . " does not exist in the Source Store.<br>";
			}

		}
		return $msgerr;

	}

	function update_current_stock_transfer_number(){
		$current_value = 1;
		$this->db->from('prefixes');
		$this->db->where( array('document_name' => 'Stock Transfer'));
		$result = $this->db->get()->result();
		foreach ($result as $row){
			$current_value = $row->current_value;
		}
		$current_value = $current_value + 1;

		$data = array(
			'current_value' => $current_value
		);	
		$this->db->where(array('document_name' => 'Stock Transfer'));
		$this->db->update('prefixes', $data);
	}

	function save_stock_transfer_details($stock_transfer_id){
		$stck_detail_product_id = $this->input->post('stck_detail_product_id');
		$stck_detail_product_variation_id = $this->input->post('stck_detail_product_variation_id');
		$stck_detail_qty = $this->input->post('stck_detail_qty');
		
		foreach( $stck_detail_product_id as $key => $n ) {

			//STOCK TRANSFER DETAILS
			$new_data = array(
				'stock_transfer_id' => $stock_transfer_id,
				'product_id' => $n,
				'product_variation_id' => $stck_detail_product_variation_id[$key],
				'transferred_quantity' => $stck_detail_qty[$key]
			);
			$insert = $this->db->insert('stock_transfer_details', $new_data);
			$stock_transfer_detail_id = $this->db->insert_id();			

			$source_outlet_id = $this->input->post('source_outlet_id');
			$destination_outlet_id = $this->input->post('destination_outlet_id');

			$product = $this->get_product($n);
			foreach ($product as $row) {
				$product_name = $row->product_name;
			}

			//SOURCE OUTLET
			$this->db->select("op.*, o.outlet_name");
			$this->db->from('outlet_products op');
			$this->db->join('outlets o', 'o.outlet_id = op.outlet_id');
			$this->db->where( array('op.outlet_id' => $source_outlet_id, 'op.product_id' => $n, 'op.product_variation_id' => $stck_detail_product_variation_id[$key]));
			$query = $this->db->get();

			$available_stock = 0;
			$reorder_level = 0;
			$outlet_name = '';
			$unit_price = 0;

			if ($query->num_rows() > 0){				
				$result = $query->result();
				foreach ($result as $row){
					$available_stock = $row->available_stock;
					$reorder_level = $row->reorder_level;
					$outlet_name = $row->outlet_name;
					$unit_price = $row->regular_price;
				}
				$available_stock = $available_stock - $stck_detail_qty[$key];
				$data = array(
					'available_stock' => $available_stock
				);	
				$this->db->where( array('outlet_id' => $source_outlet_id, 'product_id' => $n, 'product_variation_id' => $stck_detail_product_variation_id[$key]));
				$this->db->update('outlet_products', $data);

				//REORDER LEVEL NOTIFICATION
                if (($available_stock > $reorder_level) && ($available_stock - $stck_detail_qty[$key] <= $reorder_level)) {
                    //NOTIFICATION
                    $data = array(
                        'notification_type' => 'Reorder Level Limit Reached',
                        'notification_ref_id' => $n,
                        'notification_details' => 'The product <b>#' . $product_name . '</b> has reached its reorder level limit for outlet <b>' . $outlet_name . '</b> and needs replenishing',
                        'notification_ref_link' => 'be/inventory/low_stocks'
                    );
                    $this->db->insert('notifications',$data);
                }
			}

			//SOURCE STOCK TRACKER
			$data = array(
				'outlet_id' => $source_outlet_id,
				'product_id' => $n,
				'product_variation_id' => $stck_detail_product_variation_id[$key],
				'transaction_id' => $stock_transfer_detail_id,
				'transaction_type' => 'OUT',
				'transaction_description' => 'Stock Transfer Out',
				'quantity' => $stck_detail_qty[$key],
				'unit_price' => $unit_price
			);
			$this->db->insert('stock_tracker', $data);



			///DESTINATION OUTLET
			$this->db->from('outlet_products');
			$this->db->where( array('outlet_id' => $destination_outlet_id, 'product_id' => $n, 'product_variation_id' => $stck_detail_product_variation_id[$key]));
			$query = $this->db->get();

			$available_stock = 0;
			$unit_price = 0;

			if ($query->num_rows() > 0){				
				$result = $query->result();
				foreach ($result as $row){
					$available_stock = $row->available_stock;
					$unit_price = $row->regular_price;
				}
				$available_stock = $available_stock + $stck_detail_qty[$key];
				$data = array(
					'available_stock' => $available_stock
				);	
				$this->db->where( array('outlet_id' => $destination_outlet_id, 'product_id' => $n, 'product_variation_id' => $stck_detail_product_variation_id[$key]));
				$this->db->update('outlet_products', $data);
			}else{
				$data = array(
					'outlet_id' => $destination_outlet_id,
					'product_id' => $n,
					'product_variation_id' => $stck_detail_product_variation_id[$key],
					'available_stock' => $stck_detail_qty[$key]
				);	

				$this->db->insert('outlet_products', $data);
			}

			//DESTINATION STOCK TRACKER
			$data = array(
				'outlet_id' => $destination_outlet_id,
				'product_id' => $n,
				'product_variation_id' => $stck_detail_product_variation_id[$key],
				'transaction_id' => $stock_transfer_detail_id,
				'transaction_type' => 'IN',
				'transaction_description' => 'Stock Transfer In',
				'quantity' => $stck_detail_qty[$key],
				'unit_price' => $unit_price
			);
			$this->db->insert('stock_tracker', $data);

		}
	}

	function get_stock_transfer($stock_transfer_id){
		$this->db->select("stock_transfers.*, so.outlet_id as 'source_outlet_id', so.outlet_name as 'source_outlet_name', do.outlet_id as 'destination_outlet_id', do.outlet_name as 'destination_outlet_name', system_users.system_user_id, system_users.first_name, system_users.last_name, su2.first_name AS 'void_first_name', su2.last_name AS 'void_last_name'");
		$this->db->from('stock_transfers');
		$this->db->join('outlets so', 'so.outlet_id = stock_transfers.source_outlet_id', 'left outer');
		$this->db->join('outlets do', 'do.outlet_id = stock_transfers.destination_outlet_id', 'left outer');
		$this->db->join('system_users', 'system_users.system_user_id = stock_transfers.created_by', 'left outer');
		$this->db->join('system_users su2', 'su2.system_user_id = stock_transfers.void_user_id', 'left outer');

		$this->db->where( array('stock_transfers.stock_transfer_id' => $stock_transfer_id));
		return $this->db->get()->result();
	}
	function get_stock_transfer_details($stock_transfer_id){
		$this->db->select('stock_transfer_details.*, products.product_id, products.product_sku_code, products.product_barcode, products.product_name');
		$this->db->from('stock_transfer_details');
		$this->db->join('products', 'products.product_id = stock_transfer_details.product_id', 'left outer');

		$this->db->where( array('stock_transfer_details.stock_transfer_id' => $stock_transfer_id));
		$stock_transfer_details = $this->db->get()->result();

        $i = 0;
        foreach($stock_transfer_details as $row){
            $stock_transfer_details[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $i++;
        }
        return $stock_transfer_details;
	}

	function update_stock_transfer(){
		$stock_transfer_id = $this->input->post('stock_transfer_id');

		$data = array(
			'transfer_date' => $this->input->post('transfer_date'),
			'source_outlet_id' => $this->input->post('source_outlet_id'),
			'destination_outlet_id' => $this->input->post('destination_outlet_id'),
			'total_quantity' => $this->input->post('stck_total_detail_qty'),
			'remark' => $this->input->post('remark')
		);	

		$this->db->where(array('stock_transfer_id' => $stock_transfer_id));
		$update = $this->db->update('stock_transfers', $data);
		
		if ($update){

			//STOCK TRANSFER DETAILS
			$this->update_stock_transfer_details($stock_transfer_id);

			$arr_return = array('res' => true,'dt' => 'Stock Transfer updated successfully.','id' => $stock_transfer_id);
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not update Stock Transfer successfully. Please try again.','id' => $stock_transfer_id);
		}
		return $arr_return;
	}

	function update_stock_transfer_details($stock_transfer_id){
		$stck_detail_product_id = $this->input->post('stck_detail_product_id');
		$stck_detail_product_variation_id = $this->input->post('stck_detail_product_variation_id');
		$stck_detail_qty = $this->input->post('stck_detail_qty');

		$stock_transfer_details = $this->get_stock_transfer_details($stock_transfer_id);

		foreach ($stock_transfer_details as $row){

			$found = false;
			$already_transferred_qty = 0;
			$product_id = $row->product_id;
			$product_variation_id = $row->product_variation_id;
			$stock_transfer_detail_id = $row->stock_transfer_detail_id;
			$source_outlet_id = $this->input->post('source_outlet_id');
			$destination_outlet_id = $this->input->post('destination_outlet_id');
			$product_name = $row->product_name;

			foreach( $stck_detail_product_id as $key => $n ) {
				if ($product_id == $n && $product_variation_id == $stck_detail_product_variation_id[$key]){
					$already_transferred_qty = $row->transferred_quantity;
					$found = true;
					break;
				}
			}

			if ($found == false){
			   $this->db->where('stock_transfer_detail_id', $stock_transfer_detail_id);
			   $this->db->delete('stock_transfer_details'); 	

			   //SOURCE OUTLET
				$this->db->from('outlet_products');
				$this->db->where( array('outlet_id' => $source_outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
				$query = $this->db->get();

				if ($query->num_rows() > 0){
					$available_stock = 0;
					$result = $query->result();
					foreach ($result as $row){
						$available_stock = $row->available_stock;
					}
					$available_stock = $available_stock + $already_transferred_qty;
					$data = array(
						'available_stock' => $available_stock
					);	
					$this->db->where( array('outlet_id' => $source_outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
					$this->db->update('outlet_products', $data);
				}

				//SOURCE STOCK CHECKER
				$this->db->where( array('outlet_id' => $source_outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $stock_transfer_detail_id, 'transaction_description' => 'Stock Transfer Out'));
				$this->db->delete('stock_tracker');

				//DESTINATION OUTLET
				$this->db->select("op.*, o.outlet_name");
				$this->db->from('outlet_products op');
				$this->db->join('outlets o', 'o.outlet_id = op.outlet_id');
				$this->db->where( array('op.outlet_id' => $destination_outlet_id, 'op.product_id' => $product_id, 'op.product_variation_id' => $product_variation_id));
				$query = $this->db->get();

				if ($query->num_rows() > 0){
					$available_stock = 0;
					$reorder_level = 0;
					$outlet_name = '';
					$result = $query->result();
					foreach ($result as $row){
						$available_stock = $row->available_stock;
						$reorder_level = $row->reorder_level;
						$outlet_name = '';
					}
					$available_stock = $available_stock - $already_transferred_qty;
					$data = array(
						'available_stock' => $available_stock
					);	
					$this->db->where( array('outlet_id' => $destination_outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
					$this->db->update('outlet_products', $data);

					//REORDER LEVEL NOTIFICATION
	                if (($available_stock > $reorder_level) && ($available_stock - $already_transferred_qty <= $reorder_level)) {
	                    //NOTIFICATION
	                    $data = array(
	                        'notification_type' => 'Reorder Level Limit Reached',
	                        'notification_ref_id' => $product_id,
	                        'notification_details' => 'The product <b>#' . $product_name . '</b> has reached its reorder level limit for outlet <b>' . $outlet_name . '</b> and needs replenishing',
	                        'notification_ref_link' => 'be/inventory/low_stocks'
	                    );
	                    $this->db->insert('notifications',$data);
	                }
				}

				//DESTINATION STOCK CHECKER
				$this->db->where( array('outlet_id' => $destination_outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $stock_transfer_detail_id, 'transaction_description' => 'Stock Transfer In'));
				$this->db->delete('stock_tracker');
			}else{

				foreach( $stck_detail_product_id as $key => $n ) {
					if ($product_id == $n && $product_variation_id == $stck_detail_product_variation_id[$key]){

						$data = array(
							'transferred_quantity' => $stck_detail_qty[$key]
						);

						$this->db->where(array('stock_transfer_id' => $stock_transfer_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
						$this->db->update('stock_transfer_details', $data);

						$source_outlet_id = $this->input->post('source_outlet_id');
						$destination_outlet_id = $this->input->post('destination_outlet_id');

						//SOURCE OUTLET
						$this->db->select("op.*, o.outlet_name");
						$this->db->from('outlet_products op');
						$this->db->join('outlets o', 'o.outlet_id = op.outlet_id');
						$this->db->where( array('op.outlet_id' => $source_outlet_id, 'op.product_id' => $product_id, 'op.product_variation_id' => $product_variation_id));
						$query = $this->db->get();

						if ($query->num_rows() > 0){
							$available_stock = 0;
							$reorder_level = 0;
							$outlet_name = '';
							$result = $query->result();
							foreach ($result as $row){
								$available_stock = $row->available_stock;
								$reorder_level = $row->reorder_level;
							}
							$available_stock = ($available_stock + $already_transferred_qty) - $stck_detail_qty[$key];
							$data = array(
								'available_stock' => $available_stock
							);	
							$this->db->where( array('outlet_id' => $source_outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
							$this->db->update('outlet_products', $data);

							//REORDER LEVEL NOTIFICATION
			                if (($available_stock + $already_transferred_qty > $reorder_level) && (($available_stock + $already_transferred_qty) - $stck_detail_qty[$key] <= $reorder_level)) {
			                    //NOTIFICATION
			                    $data = array(
			                        'notification_type' => 'Reorder Level Limit Reached',
			                        'notification_ref_id' => $product_id,
			                        'notification_details' => 'The product <b>#' . $product_name . '</b> has reached its reorder level limit for outlet <b>' . $outlet_name . '</b> and needs replenishing',
			                        'notification_ref_link' => 'be/inventory/low_stocks'
			                    );
			                    $this->db->insert('notifications',$data);
			                }
						}

						//SOURCE STOCK CHECKER
						$data = array(
							'quantity' => $stck_detail_qty[$key]
						);
						$this->db->where( array('outlet_id' => $source_outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $stock_transfer_detail_id, 'transaction_description' => 'Stock Transfer Out'));
						$this->db->update('stock_tracker', $data);

						//DESTINATION OUTLET
						$this->db->select("op.*, o.outlet_name");
						$this->db->from('outlet_products op');
						$this->db->join('outlets o', 'o.outlet_id = op.outlet_id');
						$this->db->where( array('op.outlet_id' => $destination_outlet_id, 'op.product_id' => $product_id, 'op.product_variation_id' => $product_variation_id));
						$query = $this->db->get();

						if ($query->num_rows() > 0){
							$available_stock = 0;
							$reorder_level = 0;
							 $outlet_name = 0;
							$result = $query->result();
							foreach ($result as $row){
								$available_stock = $row->available_stock;
								$reorder_level = $row->reorder_level;
								$outlet_name = $row->outlet_name;
							}
							$available_stock = ($available_stock - $already_transferred_qty) + $stck_detail_qty[$key];
							$data = array(
								'available_stock' => $available_stock
							);	
							$this->db->where( array('outlet_id' => $destination_outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
							$this->db->update('outlet_products', $data);

							//REORDER LEVEL NOTIFICATION
			                if (($available_stock + $stck_detail_qty[$key] > $reorder_level) && (($available_stock - $already_transferred_qty) + $stck_detail_qty[$key] <= $reorder_level)) {
			                    //NOTIFICATION
			                    $data = array(
			                        'notification_type' => 'Reorder Level Limit Reached',
			                        'notification_ref_id' => $product_id,
			                        'notification_details' => 'The product <b>#' . $product_name . '</b> has reached its reorder level limit for outlet <b>' . $outlet_name . '</b> and needs replenishing',
			                        'notification_ref_link' => 'be/inventory/low_stocks'
			                    );
			                    $this->db->insert('notifications',$data);
			                }
						}

						//DESTINATION STOCK CHECKER
						$data = array(
							'quantity' => $stck_detail_qty[$key]
						);
						$this->db->where( array('outlet_id' => $destination_outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $stock_transfer_detail_id, 'transaction_description' => 'Stock Transfer In'));
						$this->db->update('stock_tracker', $data);
					}
				}
			}
		}


		$stock_transfer_details = $this->get_stock_transfer_details($stock_transfer_id);

		foreach( $stck_detail_product_id as $key => $n ) {
			$found = false;
			$product_name = '';
			foreach ($stock_transfer_details as $row){
				if ($row->product_id == $n && $row->product_variation_id == $stck_detail_product_variation_id[$key]){
					$product_name = $row->product_name;
					$found = true;
					break;
				}
			}
			if ($found == false){
				$new_data = array(
					'stock_transfer_id' => $stock_transfer_id,
					'product_id' => $n,
					'product_variation_id' => $stck_detail_product_variation_id[$key],
					'transferred_quantity' => $stck_detail_qty[$key]
				);
				$this->db->insert('stock_transfer_details', $new_data);
				$stock_transfer_detail_id = $this->db->insert_id();			

				$source_outlet_id = $this->input->post('source_outlet_id');
				$destination_outlet_id = $this->input->post('destination_outlet_id');


				//SOURCE OUTLET
				$this->db->select("op.*, o.outlet_name");
				$this->db->from('outlet_products op');
				$this->db->join('outlets o', 'o.outlet_id = op.outlet_id');
				$this->db->where( array('op.outlet_id' => $source_outlet_id, 'op.product_id' => $n, 'op.product_variation_id' => $stck_detail_product_variation_id[$key]));
				$query = $this->db->get();

				$available_stock = 0;
				$reorder_level = 0;
				$outlet_name = '';
				$unit_price = 0;

				if ($query->num_rows() > 0){				
					$result = $query->result();
					foreach ($result as $row){
						$available_stock = $row->available_stock;
						$reorder_level = $row->reorder_level;
						$outlet_name = $row->outlet_name;
						$unit_price = $row->regular_price;
					}
					$available_stock = $available_stock - $stck_detail_qty[$key];
					$data = array(
						'available_stock' => $available_stock
					);	
					$this->db->where( array('outlet_id' => $source_outlet_id, 'product_id' => $n, 'product_variation_id' => $stck_detail_product_variation_id[$key]));
					$this->db->update('outlet_products', $data);

					//REORDER LEVEL NOTIFICATION
	                if (($available_stock > $reorder_level) && (($available_stock -  $stck_detail_qty[$key]) <= $reorder_level)) {
	                    //NOTIFICATION
	                    $data = array(
	                        'notification_type' => 'Reorder Level Limit Reached',
	                        'notification_ref_id' => $n,
	                        'notification_details' => 'The product <b>#' . $product_name . '</b> has reached its reorder level limit for outlet <b>' . $outlet_name . '</b> and needs replenishing',
	                        'notification_ref_link' => 'be/inventory/low_stocks'
	                    );
	                    $this->db->insert('notifications',$data);
	                }
				}

				//SOURCE STOCK TRACKER
				$data = array(
					'outlet_id' => $source_outlet_id,
					'product_id' => $n,
					'product_variation_id' => $stck_detail_product_variation_id[$key],
					'transaction_id' => $stock_transfer_detail_id,
					'transaction_type' => 'OUT',
					'transaction_description' => 'Stock Transfer Out',
					'quantity' => $stck_detail_qty[$key],
					'unit_price' => $unit_price
				);
				$this->db->insert('stock_tracker', $data);


				///DESTINATION OUTLET
				$this->db->from('outlet_products');
				$this->db->where( array('outlet_id' => $destination_outlet_id, 'product_id' => $n, 'product_variation_id' => $stck_detail_product_variation_id[$key]));
				$query = $this->db->get();

				$available_stock = 0;
				$unit_price = 0;

				if ($query->num_rows() > 0){				
					$result = $query->result();
					foreach ($result as $row){
						$available_stock = $row->available_stock;
						$unit_price = $row->regular_price;
					}
					$available_stock = $available_stock + $stck_detail_qty[$key];
					$data = array(
						'available_stock' => $available_stock
					);	
					$this->db->where( array('outlet_id' => $destination_outlet_id, 'product_id' => $n, 'product_variation_id' => $stck_detail_product_variation_id[$key]));
					$this->db->update('outlet_products', $data);
				}else{
					$data = array(
						'outlet_id' => $destination_outlet_id,
						'product_id' => $n,
						'product_variation_id' => $stck_detail_product_variation_id[$key],
						'available_stock' => $stck_detail_qty[$key]
					);	

					$this->db->insert('outlet_products', $data);
				}

				//DESTINATION STOCK TRACKER
				$data = array(
					'outlet_id' => $destination_outlet_id,
					'product_id' => $n,
					'product_variation_id' => $stck_detail_product_variation_id[$key],
					'transaction_id' => $stock_transfer_detail_id,
					'transaction_type' => 'IN',
					'transaction_description' => 'Stock Transfer In',
					'quantity' => $stck_detail_qty[$key],
					'unit_price' => $unit_price
				);
				$this->db->insert('stock_tracker', $data);
			}
		}
	}
	function submit_send_stock_transfer_via_email() {

        $stock_transfer_id = $this->input->post('stock_transfer_id');

        try {

            ob_start();

            $mail          = new PHPMailer();
            $mail->IsSMTP();

            $use_ssl = $this->input->post('chk_use_ssl');

            if($use_ssl == 'on'){
                $mail->SMTPSecure = 'ssl';
                $mail->SMTPAuth   = true;
            }            
            $mail->Host       = $this->input->post('mail_server_name');
            $mail->Port       = $this->input->post('mail_server_port');
            $mail->Username   = $this->input->post('sender_username');
            $mail->Password   = $this->input->post('sender_password');
            
            $mail->SetFrom($this->input->post('sender_email_address'), $this->input->post('sender_name'));
            $email_to = $this->input->post('recipient_email_address'); 
             
            $mail->Subject = $this->input->post('email_subject');

            $email_message = $this->input->post('email_message'); 

            
            $message = file_get_contents(base_url().'email_temp/emheader');
            $message .= file_get_contents(base_url().'email_temp/embody');
            $message .= file_get_contents(base_url().'email_temp/emfooter');
            $logo = base_url().'assets/fe/img/logo.png';
            
            $replacements = array(
                '({logo})' => $logo, 
                '({message_subject})' => '', 
                '({message_body})' => nl2br( stripslashes( $email_message ) )
            );
            $message = preg_replace(array_keys( $replacements ), array_values( $replacements ), $message );
            
            $plaintext = $message;
            $plaintext = strip_tags( stripslashes( $plaintext ), '<p><br><h2><h3><h1><h4>' );
            $plaintext = str_replace( array( '<p>', '<br />', '<br>', '<h1>', '<h2>', '<h3>', '<h4>' ), PHP_EOL, $plaintext );
            $plaintext = str_replace( array( '</p>', '</h1>', '</h2>', '</h3>', '</h4>' ), '', $plaintext );
            $plaintext = html_entity_decode( stripslashes( $plaintext ) );
        
            
            $mail->MsgHTML( stripslashes( $message ) ); 

            $attachment = $this->generate_stock_transfer_pdf($stock_transfer_id);
            $mail->AddStringAttachment($attachment, 'Bethany House Stock Transfer -'. $stock_transfer_id . '.pdf', 'base64', 'application/pdf');
            
            $mail->AltBody = $plaintext;
            $mail->AddAddress($email_to, "");

            if( !$mail->Send() ){
                $arr_return = array('res' => false,'dt' => $mail->ErrorInfo);
            }else{
                $arr_return = array('res' => true,'dt' => 'Email Sent successfully');
            }
            ob_get_clean();
        } catch (phpmailerException $e) {
            $arr_return = array('res' => false,'dt' =>  $e->errorMessage());
        } catch (Exception $e) {
            $arr_return = array('res' => false,'dt' =>  $e->getMessage());
        }        
        return $arr_return;
    }

    function generate_stock_transfer_pdf($stock_transfer_id){
    	$stock_transfer = $this->inventory_model->get_stock_transfer($stock_transfer_id);
		$stock_transfer_details = $this->inventory_model->get_stock_transfer_details($stock_transfer_id);

		$default_currency = $this->currencies_model->get_default_currency();
		$store_information = $this->store_information_model->get_store_information();

		$attachment = '';

		foreach ($stock_transfer as $row) {

			$filename='Bethany House Stock Transfer - '.$row->stock_transfer_number.'.pdf';

            // create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Bethany House');
            $pdf->SetTitle('Bethany House Stock Transfer - '.$row->stock_transfer_number);
            $pdf->SetSubject('Bethany House Stock Transfer - '.$row->stock_transfer_number);
            //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

            // remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set font
            $pdf->SetFont('helvetica', '', 8);

            $pdf->setCellHeightRatio(1.6);

            // add a page
            $pdf->AddPage();

            $pdf->Ln(10);

            $txt = '<table border="1" cellpadding="5" cellspacing="0">';
            $txt = $txt . '<thead>';
            $store_logo = '';
            foreach ($store_information as $row2){
                if($row2->store_logo != '' && file_exists("./uploads/store_logo/" . $row2->store_logo)){
                    $store_logo = base_url() . 'uploads/store_logo/' . $row2->store_logo;
                } else {
                    $store_logo = base_url() . 'assets/fe/img/logo.png';
                }
            }
            $txt = $txt . '<tr>
            	<td rowspan="4" width="224"><img src="' . $store_logo . '"  width="200px"><br /><br />';

            	// </td>
            	// <td rowspan="2" width="200">';

            	foreach ($store_information as $row2){
                    $txt = $txt . '<b>' . $row2->store_name . '</b><br />
                    <b>Phone:</b> ' . $row2->phone_number . '<br />
                    <b>Address:</b> ' . $row2->physical_address . '<br />
                    <b>Email:</b> ' . $row2->email_address;
                }
            	$txt = $txt . '</td>
            	<td rowspan="4"></td>
            	<td><b>Stock Transfer No:</b> ' . $row->stock_transfer_number . '</td>
            	</tr>
            	<tr>
            		<td><b>Source Outlet:</b> ' . $row->source_outlet_name . '</td>
            	</tr>
            	<tr>
            		<td><b>Destination Outlet:</b> ' . $row->destination_outlet_name . '</td>
            	</tr>
            	<tr>
            		<td><b>Transfer Date:</b> ' . date('d M, Y', strtotime($row->transfer_date)) . '</td>
            	</tr>
            	</thead></table>';


            $pdf->writeHTML($txt, true, false, false, false, '');

            $txt = '<table border="1" cellpadding="5" cellspacing="0">
           		<thead>
           			<tr>
           				<td width="30"><b>#</b></td>
           				<td width="440"><b>Product Name</b></td>
           				<td width="200"><b>Transferred Qty</b></td>
           			</tr>
           		</thead>
           		<tbody>';
           	$count = 1;
           	foreach ($stock_transfer_details as $row2){
           		$txt = $txt . '<tr>
           			<td width="30">' . $count . '</td>
       				<td width="440">'. $row2->product_name . '<br><i>SKU: ' . $row2->product_sku_code . '</i></td>
       				<td width="200">' . number_format($row2->transferred_quantity) . '</td>
           		</tr>';
           		$count++;
           	}
           	
           $txt = $txt . '</tbody></table>';

           	$pdf->writeHTML($txt, true, false, false, false, '');

           	$txt = '<table border="1" cellpadding="5" cellspacing="0">
           			<tr>
           				<td colspan="2" align="left"><b>Remark:</b><br/>' . $row->remark .  '</td>
           			</tr>
           			<tr>
           				<td colspan="2" align="center"><small>Printed On: '. date('d-m-Y') . '</small></td>
           			</tr>
           		<tbody>';
           	$txt = $txt . '</tbody></table>';
           	$pdf->writeHTML($txt, true, false, false, false, '');


        	$attachment = $pdf->Output($filename, 'S');
        }

        return $attachment;
    }

    function stock_transfer_void_valid($stock_transfer_id){
    	//$is_void = true;
    	$errMsg = '';

        $stock_transfer = $this->get_stock_transfer($stock_transfer_id);

        foreach ($stock_transfer as $row) {
            if ($row->is_void == 0) {
            } else {
                $errMsg = 'This Stock Transfer has already been voided.';
            }
        }

        if ($errMsg == '') {
            $arr_return = array('res' => true,'dt' => 'Valid','data' => $stock_transfer);
        } else {
            $arr_return = array('res' => false,'dt' => $errMsg,'data' => $stock_transfer);
        }
        return $arr_return;
    }
    function submit_void_stock_transfer(){
    	$stock_transfer_id = $this->input->post('stock_transfer_id');

    	$void_date = date('Y-m-d H:i:s');
    	$void_reason = $this->input->post('void_reason');

        $data = array(
            'is_void' => 1,
            'void_reason' => $void_reason,
            'void_user_id' => $this->session->userdata('system_user_id'),
            'void_date' => $void_date
        );
                
        $this->db->where( array('stock_transfer_id' => $stock_transfer_id));
        $void = $this->db->update('stock_transfers', $data);

        if ($void) {

        	//RETURN STOCK
        	$source_outlet_id = 0;
        	$destination_outlet_id = 0;
        	$stock_transfer_number = '';

        	$stock_transfer = $this->get_stock_transfer($stock_transfer_id);
        	foreach ($stock_transfer as $row) {
        		$source_outlet_id = $row->source_outlet_id;
        		$destination_outlet_id = $row->destination_outlet_id;
        		$stock_transfer_number = $row->stock_transfer_number;
        	}

            $stock_transfer_details = $this->get_stock_transfer_details($stock_transfer_id);

            foreach ($stock_transfer_details as $row) {
                $quantity = $row->transferred_quantity;
                $product_id = $row->product_id;
                $product_name = $row->product_name;
                $product_variation_id = $row->product_variation_id;
                $stock_transfer_detail_id = $row->stock_transfer_detail_id;

                //SOURCE OUTLET
                $available_stock = 0;

                $this->db->select("*");
                $this->db->from('outlet_products');
                $this->db->where( array('outlet_id' => $source_outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                $outlet_product = $this->db->get()->result();
                foreach ($outlet_product as $row2) {
                    $available_stock = $row2->available_stock;
                }
                $data = array(
                    'available_stock' =>  $available_stock + $quantity
                );
                $this->db->where( array('outlet_id' => $source_outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                $this->db->update('outlet_products', $data);

                //DESTINATION OUTLET
                $available_stock = 0;
                $reorder_level = 0;
                $outlet_name = '';

                $this->db->select("op.*, o.outlet_name");
				$this->db->from('outlet_products op');
				$this->db->join('outlets o', 'o.outlet_id = op.outlet_id');
                $this->db->where( array('op.outlet_id' => $destination_outlet_id, 'op.product_id' => $product_id, 'op.product_variation_id' => $product_variation_id));
                $outlet_product = $this->db->get()->result();
                foreach ($outlet_product as $row2) {
                    $available_stock = $row2->available_stock;
                    $reorder_level = $row->reorder_level;
                    $outlet_name = $row->outlet_name;
                }
                $data = array(
                    'available_stock' =>  $available_stock - $quantity
                );
                $this->db->where( array('outlet_id' => $destination_outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                $this->db->update('outlet_products', $data);

                //REORDER LEVEL NOTIFICATION
                if (($available_stock > $reorder_level) && (($available_stock - $quantity) <= $reorder_level)) {
                    //NOTIFICATION
                    $data = array(
                        'notification_type' => 'Reorder Level Limit Reached',
                        'notification_ref_id' => $product_id,
                        'notification_details' => 'The product <b>#' . $product_name . '</b> has reached its reorder level limit for outlet <b>' . $outlet_name . '</b> and needs replenishing',
                        'notification_ref_link' => 'be/inventory/low_stocks'
                    );
                    $this->db->insert('notifications',$data);
                }

                //STOCK CHECKER
                $this->db->where( array('outlet_id' => $source_outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $stock_transfer_detail_id, 'transaction_description' => 'Stock Transfer Out'));
				$this->db->delete('stock_tracker');

				$this->db->where( array('outlet_id' => $destination_outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $stock_transfer_detail_id, 'transaction_description' => 'Stock Transfer In'));
				$this->db->delete('stock_tracker');
            }

            //NOTIFICATION
            $data = array(
                'notification_type' => 'Stock Transfer Voided',
                'notification_ref_id' => $stock_transfer_id,
                'notification_details' => 'Stock Transfer <b>#' . $stock_transfer_number . '</b> has been voided on  <b>' . $void_date . '</b> by <b>' . $this->session->userdata('user_first_name') . ' ' . $this->session->userdata('user_last_name') . '</b>. Void Reason: <b>' . $void_reason . '</b>',
                'notification_ref_link' => 'be/inventory/stock_transfer_detail/' . $stock_transfer_id
            );
            $this->db->insert('notifications',$data);

            $arr_return = array('res' => true,'dt' => 'Stock Transfer voided successfully.');
        } else {
            $arr_return = array('res' => false,'dt' => 'There was an error trying to void this Stock Transfer. Please try again.');
        }

        return $arr_return;

    }


	//STOCK ADJUSTMENTS
	function get_stock_adjustments(){
		// $purchase_order_status = $this->input->post('purchase_order_status');
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $status = $this->input->post('status');
        $outlet_id = $this->input->post('outlet_id');
        $system_user_id = $this->input->post('system_user_id');

		$this->db->select("stock_adjustments.*, outlets.outlet_id, outlets.outlet_name, system_users.system_user_id, system_users.first_name, system_users.last_name, su2.first_name AS 'void_first_name', su2.last_name AS 'void_last_name'");
		$this->db->from('stock_adjustments');
		$this->db->join('outlets', 'outlets.outlet_id = stock_adjustments.outlet_id', 'left outer');
		$this->db->join('system_users', 'system_users.system_user_id = stock_adjustments.created_by', 'left outer');
		$this->db->join('system_users su2', 'su2.system_user_id = stock_adjustments.void_user_id', 'left outer');

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(stock_adjustments.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(stock_adjustments.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }

        if ($outlet_id != ''){
      		$this->db->where( array('stock_adjustments.outlet_id' => $outlet_id));
        }

        if ($system_user_id != ''){
      		$this->db->where( array('stock_adjustments.created_by' => $system_user_id));
        }

        if ($status == 'Active') {
        	$this->db->where( array('stock_adjustments.is_void' => 0));
        } elseif ($status == 'Void') {
        	$this->db->where( array('stock_adjustments.is_void' => 1));
        }

        $this->db->order_by("stock_adjustments.stock_adjustment_id", "desc");

		return $this->db->get()->result();
	}

	function get_new_stock_adjustment_number(){
		$stock_adjustment_number = '';
		$prefix_name = '';
		$current_value = 1;
		$this->db->from('prefixes');
		$this->db->where( array('document_name' => 'Stock Adjustment'));
		$result = $this->db->get()->result();
		foreach ($result as $row){
			$prefix_name = $row->prefix_name;
			$current_value = $row->current_value;
		}

		$current_value = $current_value + 1;
		$stock_adjustment_number = $prefix_name . $current_value;

		return $stock_adjustment_number;
	}

	function save_stock_adjustment(){

		//$valid_adjustment = $this->validate_adjustment_stock();

		//if ($valid_adjustment == ''){
		$data = array(
			'stock_adjustment_number' => $this->get_new_stock_adjustment_number(),
			'adjustment_date' => $this->input->post('adjustment_date'),
			'outlet_id' => $this->input->post('outlet_id'),
			'total_quantity' => $this->input->post('sadj_total_detail_qty'),
			'remark' => $this->input->post('remark'),
			'created_by' => $this->session->userdata('system_user_id')
		);	

		$insert = $this->db->insert('stock_adjustments', $data);
		$insert_id = $this->db->insert_id();

		if ($insert){

			//PREFIXES
			$this->update_current_stock_adjustment_number();

			//PURCHASE ORDER DETAILS
			$this->save_stock_adjustment_details($insert_id);

			$arr_return = array('res' => true,'dt' => 'Stock adjusted successfully.','id' => $insert_id);			
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not adjust stock successfully. Please try again.','id' => '');
		}
		// }else{
		// 	$arr_return = array('res' => false,'dt' => $valid_adjustment,'id' => '');
		// }
		return $arr_return;
	}

	// function validate_adjustment_stock(){

	// 	$msgerr = '';

	// 	$sadj_detail_id = $this->input->post('sadj_detail_id');
	// 	$sadj_detail_qty = $this->input->post('sadj_detail_qty');
	// 	$source_outlet_id = $this->input->post('source_outlet_id');

	// 	foreach( $sadj_detail_id as $key => $n ) {

	// 		$product_name = $this->get_product_name($n);

	// 		$this->db->from('outlet_products');
	// 		$this->db->where( array('outlet_id' => $source_outlet_id, 'product_id' => $n));
	// 		$query = $this->db->get();

	// 		if ($query->num_rows() > 0){
	// 			$available_stock = 0;
	// 			$result = $query->result();
	// 			foreach ($result as $row){
	// 				$available_stock = $row->available_stock;
	// 			}

	// 			if ($available_stock < (int)$sadj_detail_qty[$key]){
	// 				$msgerr = $msgerr .  "<i class='fa fa-exclamation-circle'></i> The product " . $product_name . " has less available stock [" . $available_stock . "] than the adjustment stock [" . $sadj_detail_qty[$key] . "]<br>";
	// 			}
	// 		}else{
	// 			$msgerr = $msgerr . "<i class='fa fa-exclamation-circle'></i> The product " . $product_name . " does not exist in the Source Store.<br>";
	// 		}

	// 	}
	// 	return $msgerr;

	// }

	function get_outlet_product_quantity($outlet_id, $product_id, $product_variation_id){
		$current_qty = 0;

		$this->db->select("*");
		$this->db->from('products p');
		$this->db->join('outlet_products op', 'op.product_id = p.product_id');
		$this->db->where( array('p.product_id'=>$product_id, 'op.product_variation_id'=>$product_variation_id, 'op.outlet_id'=>$outlet_id));
		$outlet_product = $this->db->get()->result();

		foreach ($outlet_product as $row) {
			$current_qty = $row->available_stock;
		}

		return $current_qty;
	}

	function get_outlet_product_price($outlet_id, $product_id, $product_variation_id){
		$price = 0;

		$this->db->select("*");
		$this->db->from('products p');
		$this->db->join('outlet_products op', 'op.product_id = p.product_id');
		$this->db->where( array('p.product_id'=>$product_id, 'op.product_variation_id'=>$product_variation_id, 'op.outlet_id'=>$outlet_id));
		$outlet_product = $this->db->get()->result();

		foreach ($outlet_product as $row) {
			$price = $row->regular_price;
		}

		return $price;
	}

	function update_current_stock_adjustment_number(){
		$current_value = 1;
		$this->db->from('prefixes');
		$this->db->where( array('document_name' => 'Stock Adjustment'));
		$result = $this->db->get()->result();
		foreach ($result as $row){
			$current_value = $row->current_value;
		}
		$current_value = $current_value + 1;

		$data = array(
			'current_value' => $current_value
		);	
		$this->db->where(array('document_name' => 'Stock Adjustment'));
		$this->db->update('prefixes', $data);
	}

	function save_stock_adjustment_details($stock_adjustment_id){

		$sadj_detail_product_id = $this->input->post('sadj_detail_product_id');
		$sadj_detail_product_variation_id = $this->input->post('sadj_detail_product_variation_id');
		$sadj_detail_qty = $this->input->post('sadj_detail_qty');
		
		foreach( $sadj_detail_product_id as $key => $n ) {

			$current_quantity = $this->get_outlet_product_quantity($this->input->post('outlet_id'), $n, $sadj_detail_product_variation_id[$key]);
			$unit_price = $this->get_outlet_product_price($this->input->post('outlet_id'), $n, $sadj_detail_product_variation_id[$key]);

			//STOCK ADJUSTMENT DETAILS
			$new_data = array(
				'stock_adjustment_id' => $stock_adjustment_id,
				'product_id' => $n,
				'product_variation_id' => $sadj_detail_product_variation_id[$key],
				'current_quantity' => $current_quantity,
				'adjusted_quantity' => $sadj_detail_qty[$key]
			);
			$insert = $this->db->insert('stock_adjustment_details', $new_data);
			$stock_adjustment_detail_id = $this->db->insert_id();

			$outlet_id = $this->input->post('outlet_id');

			//OUTLET PRODUCTS
			$this->db->from('outlet_products');
			$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $n, 'product_variation_id' => $sadj_detail_product_variation_id[$key]));
			$query = $this->db->get();

			if ($query->num_rows() > 0){
				$data = array(
					'available_stock' => $sadj_detail_qty[$key]
				);	
				$this->db->where(array('product_id'=>$n, 'product_variation_id' => $sadj_detail_product_variation_id[$key], 'outlet_id'=>$outlet_id));
				$this->db->update('outlet_products', $data);
			}else{
				$data = array(
					'outlet_id' => $outlet_id,
					'product_id' => $n,
					'product_variation_id' => $sadj_detail_product_variation_id[$key],
					'available_stock' => $sadj_detail_qty[$key]
				);	

				$this->db->insert('outlet_products', $data);
			}

			$qty = $sadj_detail_qty[$key] - $current_quantity; 

			//STOCK TRACKER
			$data = array(
				'outlet_id' => $outlet_id,
				'product_id' => $n,
				'product_variation_id' => $sadj_detail_product_variation_id[$key],
				'transaction_id' => $stock_adjustment_detail_id,
				'transaction_type' => 'IN',
				'transaction_description' => 'Stock Adjustment',
				'quantity' => $qty,
				'unit_price' => $unit_price
			);	

			$this->db->insert('stock_tracker', $data);
		}
	}

	function get_auto_adjust_stock_products($term, $outlet_id){
		$this->db->select('products.*, brands.brand_id, brands.brand_name, units.unit_id, units.unit_name, units.unit_code, outlet_products.*');
		$this->db->from('products');
		$this->db->join('brands', 'brands.brand_id = products.brand_id', 'left outer');
		$this->db->join('units', 'units.unit_id = products.unit_id', 'left outer');
		$this->db->join('outlet_products', 'outlet_products.product_id = products.product_id');

		$this->db->group_start();
        $this->db->like('products.product_name', $term);
        $this->db->or_like('products.product_sku_code', $term);
        $this->db->or_like('products.product_barcode', $term);
        $this->db->group_end();		

        $this->db->group_by('products.product_id');

		$this->db->where( array('products.is_deleted'=>0, 'outlet_products.outlet_id'=>$outlet_id));
		return $this->db->get()->result();
	}


	function get_stock_adjustment($stock_adjustment_id){
		$this->db->select("stock_adjustments.*, outlets.outlet_id, outlets.outlet_name, system_users.system_user_id, system_users.first_name, system_users.last_name, su2.first_name AS 'void_first_name', su2.last_name AS 'void_last_name'");
		$this->db->from('stock_adjustments');
		$this->db->join('outlets', 'outlets.outlet_id = stock_adjustments.outlet_id', 'left outer');
		$this->db->join('system_users', 'system_users.system_user_id = stock_adjustments.created_by', 'left outer');
		$this->db->join('system_users su2', 'su2.system_user_id = stock_adjustments.void_user_id', 'left outer');

		$this->db->where( array('stock_adjustments.stock_adjustment_id' => $stock_adjustment_id));
		return $this->db->get()->result();
	}
	function get_stock_adjustment_details($stock_adjustment_id){
		$this->db->select('stock_adjustment_details.*, products.product_id, products.product_sku_code, products.product_barcode, products.product_name');
		$this->db->from('stock_adjustment_details');
		$this->db->join('products', 'products.product_id = stock_adjustment_details.product_id', 'left outer');

		$this->db->where( array('stock_adjustment_details.stock_adjustment_id' => $stock_adjustment_id));

		$stock_adjustment_details = $this->db->get()->result();

        $i = 0;
        foreach($stock_adjustment_details as $row){
            $stock_adjustment_details[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $i++;
        }
        return $stock_adjustment_details;
	}

	function update_stock_adjustment(){
		$stock_adjustment_id = $this->input->post('stock_adjustment_id');

		$data = array(
			'adjustment_date' => $this->input->post('adjustment_date'),
			'outlet_id' => $this->input->post('outlet_id'),
			'total_quantity' => $this->input->post('sadj_total_detail_qty'),
			'remark' => $this->input->post('remark')
		);	

		$this->db->where(array('stock_adjustment_id' => $stock_adjustment_id));
		$update = $this->db->update('stock_adjustments', $data);
		
		if ($update){

			//STOCK ADJUSTMENT DETAILS
			$this->update_stock_adjustment_details($stock_adjustment_id);

			$arr_return = array('res' => true,'dt' => 'Stock Adjustment updated successfully.','id' => $stock_adjustment_id);
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not update Stock Adjustment successfully. Please try again.','id' => $stock_adjustment_id);
		}
		return $arr_return;
	}

	function update_stock_adjustment_details($stock_adjustment_id){

		$sadj_detail_product_id = $this->input->post('sadj_detail_product_id');
		$sadj_detail_product_variation_id = $this->input->post('sadj_detail_product_variation_id');
		$sadj_detail_qty = $this->input->post('sadj_detail_qty');

		$stock_adjustment_details = $this->get_stock_adjustment_details($stock_adjustment_id);

		foreach ($stock_adjustment_details as $row){

			$found = false;
			$already_adjusted_qty = $row->adjusted_quantity;
			$current_adjusted_qty = $row->current_quantity;
			$product_id = $row->product_id;
			$product_variation_id = $row->product_variation_id;
			$stock_adjustment_detail_id = $row->stock_adjustment_detail_id;
			$outlet_id = $this->input->post('outlet_id');

			foreach( $sadj_detail_product_id as $key => $n ) {
				if ($product_id == $n && $product_variation_id == $sadj_detail_product_variation_id[$key]){
					$found = true;
					break;
				}
			}

			if ($found == false){
			   $this->db->where('stock_adjustment_detail_id', $stock_adjustment_detail_id);
			   $this->db->delete('stock_adjustment_details'); 	

			   //OUTLETS
				$this->db->from('outlet_products');
				$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
				$query = $this->db->get();

				if ($query->num_rows() > 0){
					$available_stock = 0;
					$result = $query->result();
					foreach ($result as $row){
						$available_stock = $row->available_stock;
					}
					// $available_stock = $available_stock + $already_returned_qty;
					$available_stock = (0 - $already_adjusted_qty) + $available_stock;
					$data = array(
						'available_stock' => $available_stock
					);	
					$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
					$this->db->update('outlet_products', $data);
				}

				//STOCK CHECKER
				$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $stock_adjustment_detail_id, 'transaction_description' => 'Stock Adjustment'));
				$this->db->delete('stock_tracker');

			}else{

				foreach( $sadj_detail_product_id as $key => $n ) {
					if ($product_id == $n && $product_variation_id == $sadj_detail_product_variation_id[$key]){

						$data = array(
							'adjusted_quantity' => $sadj_detail_qty[$key]
						);

						$this->db->where(array('stock_adjustment_id' => $stock_adjustment_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
						$this->db->update('stock_adjustment_details', $data);

						$outlet_id = $this->input->post('outlet_id');

						//OUTLETS
						$this->db->from('outlet_products');
						$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
						$query = $this->db->get();

						if ($query->num_rows() > 0){
							$available_stock = 0;
							$result = $query->result();
							foreach ($result as $row){
								$available_stock = $row->available_stock;
							}
							$available_stock = ($sadj_detail_qty[$key] - $already_adjusted_qty) + $available_stock;
							$data = array(
								'available_stock' => $available_stock
							);	
							$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
							$this->db->update('outlet_products', $data);
						}

						$qty = $sadj_detail_qty[$key] - $current_adjusted_qty;

						//STOCK CHECKER
						$data = array(
							'quantity' => $qty
						);
						$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $stock_adjustment_detail_id, 'transaction_description' => 'Stock Adjustment'));
						$this->db->update('stock_tracker', $data);
					}
				}
			}
		}

		$stock_adjustment_details = $this->get_stock_adjustment_details($stock_adjustment_id);

		foreach( $sadj_detail_product_id as $key => $n ) {
			$found = false;
			foreach ($stock_adjustment_details as $row){
				if ($row->product_id == $n && $row->product_variation_id == $sadj_detail_product_variation_id[$key]){
					$found = true;
					break;
				}
			}
			if ($found == false){

				$current_quantity = $this->get_outlet_product_quantity($this->input->post('outlet_id'), $n, $sadj_detail_product_variation_id[$key]);
				$unit_price = $this->get_outlet_product_price($this->input->post('outlet_id'), $n, $sadj_detail_product_variation_id[$key]);

				//STOCK ADJUSTMENT DETAILS
				$new_data = array(
					'stock_adjustment_id' => $stock_adjustment_id,
					'product_id' => $n,
					'product_variation_id' => $sadj_detail_product_variation_id[$key],
					'current_quantity' => $current_quantity,
					'adjusted_quantity' => $sadj_detail_qty[$key]
				);
				$insert = $this->db->insert('stock_adjustment_details', $new_data);
				$stock_adjustment_detail_id = $this->db->insert_id();

				$outlet_id = $this->input->post('outlet_id');

				//OUTLET PRODUCTS
				$this->db->from('outlet_products');
				$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $n, 'product_variation_id' => $sadj_detail_product_variation_id[$key]));
				$query = $this->db->get();

				if ($query->num_rows() > 0){
					$data = array(
						'available_stock' => $sadj_detail_qty[$key]
					);	
					$this->db->where(array('product_id'=>$n, 'product_variation_id' => $sadj_detail_product_variation_id[$key], 'outlet_id'=>$outlet_id));
					$this->db->update('outlet_products', $data);
				}else{
					$data = array(
						'outlet_id' => $outlet_id,
						'product_id' => $n,
						'product_variation_id' => $sadj_detail_product_variation_id[$key],
						'available_stock' => $sadj_detail_qty[$key]
					);	

					$this->db->insert('outlet_products', $data);
				}

				$qty = $sadj_detail_qty[$key] - $current_quantity; 

				//STOCK TRACKER
				$data = array(
					'outlet_id' => $outlet_id,
					'product_id' => $n,
					'product_variation_id' => $sadj_detail_product_variation_id[$key],
					'transaction_id' => $stock_adjustment_detail_id,
					'transaction_type' => 'IN',
					'transaction_description' => 'Stock Adjustment',
					'quantity' => $qty,
					'unit_price' => $unit_price
				);	

				$this->db->insert('stock_tracker', $data);
			}
		}
	}

	function submit_send_stock_adjustment_via_email() {

        $stock_adjustment_id = $this->input->post('stock_adjustment_id');

        try {

            ob_start();

            $mail          = new PHPMailer();
            $mail->IsSMTP();

            $use_ssl = $this->input->post('chk_use_ssl');

            if($use_ssl == 'on'){
                $mail->SMTPSecure = 'ssl';
                $mail->SMTPAuth   = true;
            }            
            $mail->Host       = $this->input->post('mail_server_name');
            $mail->Port       = $this->input->post('mail_server_port');
            $mail->Username   = $this->input->post('sender_username');
            $mail->Password   = $this->input->post('sender_password');
            
            $mail->SetFrom($this->input->post('sender_email_address'), $this->input->post('sender_name'));
            $email_to = $this->input->post('recipient_email_address'); 
             
            $mail->Subject = $this->input->post('email_subject');

            $email_message = $this->input->post('email_message'); 

            
            $message = file_get_contents(base_url().'email_temp/emheader');
            $message .= file_get_contents(base_url().'email_temp/embody');
            $message .= file_get_contents(base_url().'email_temp/emfooter');
            $logo = base_url().'assets/fe/img/logo.png';
            
            $replacements = array(
                '({logo})' => $logo, 
                '({message_subject})' => '', 
                '({message_body})' => nl2br( stripslashes( $email_message ) )
            );
            $message = preg_replace(array_keys( $replacements ), array_values( $replacements ), $message );
            
            $plaintext = $message;
            $plaintext = strip_tags( stripslashes( $plaintext ), '<p><br><h2><h3><h1><h4>' );
            $plaintext = str_replace( array( '<p>', '<br />', '<br>', '<h1>', '<h2>', '<h3>', '<h4>' ), PHP_EOL, $plaintext );
            $plaintext = str_replace( array( '</p>', '</h1>', '</h2>', '</h3>', '</h4>' ), '', $plaintext );
            $plaintext = html_entity_decode( stripslashes( $plaintext ) );
        
            
            $mail->MsgHTML( stripslashes( $message ) ); 

            $attachment = $this->generate_stock_adjustment_pdf($stock_adjustment_id);
            $mail->AddStringAttachment($attachment, 'Bethany House Stock Adjustment -'. $stock_adjustment_id . '.pdf', 'base64', 'application/pdf');
            
            $mail->AltBody = $plaintext;
            $mail->AddAddress($email_to, "");

            if( !$mail->Send() ){
                $arr_return = array('res' => false,'dt' => $mail->ErrorInfo);
            }else{
                $arr_return = array('res' => true,'dt' => 'Email Sent successfully');
            }
            ob_get_clean();
        } catch (phpmailerException $e) {
            $arr_return = array('res' => false,'dt' =>  $e->errorMessage());
        } catch (Exception $e) {
            $arr_return = array('res' => false,'dt' =>  $e->getMessage());
        }        
        return $arr_return;
    }

    function generate_stock_adjustment_pdf($stock_adjustment_id){
    	$stock_adjustment = $this->inventory_model->get_stock_adjustment($stock_adjustment_id);
		$stock_adjustment_details = $this->inventory_model->get_stock_adjustment_details($stock_adjustment_id);

		$default_currency = $this->currencies_model->get_default_currency();
		$store_information = $this->store_information_model->get_store_information();

		$attachment = '';

		foreach ($stock_adjustment as $row) {

			$filename='Bethany House Stock Adjustment - '.$row->stock_adjustment_number.'.pdf';

            // create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Bethany House');
            $pdf->SetTitle('Bethany House Stock Adjustment - '.$row->stock_adjustment_number);
            $pdf->SetSubject('Bethany House Stock Adjustment - '.$row->stock_adjustment_number);
            //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

            // remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set font
            $pdf->SetFont('helvetica', '', 8);

            $pdf->setCellHeightRatio(1.6);

            // add a page
            $pdf->AddPage();

            $pdf->Ln(10);

            $txt = '<table border="1" cellpadding="5" cellspacing="0">';
            $txt = $txt . '<thead>';
            $store_logo = '';
            foreach ($store_information as $row2){
                if($row2->store_logo != '' && file_exists("./uploads/store_logo/" . $row2->store_logo)){
                    $store_logo = base_url() . 'uploads/store_logo/' . $row2->store_logo;
                } else {
                    $store_logo = base_url() . 'assets/fe/img/logo.png';
                }
            }
            $txt = $txt . '<tr>
            	<td rowspan="3" width="224"><img src="' . $store_logo . '"  width="200px"><br /><br />';

            	// </td>
            	// <td rowspan="2" width="200">';

            	foreach ($store_information as $row2){
                    $txt = $txt . '<b>' . $row2->store_name . '</b><br />
                    <b>Phone:</b> ' . $row2->phone_number . '<br />
                    <b>Address:</b> ' . $row2->physical_address . '<br />
                    <b>Email:</b> ' . $row2->email_address;
                }
            	$txt = $txt . '</td>
            	<td rowspan="3"></td>
            	<td><b>Stock Adjustment No:</b> ' . $row->stock_adjustment_number . '</td>
            	</tr>
            	<tr>
            		<td><b>Outlet:</b> ' . $row->outlet_name . '</td>
            	</tr>
            	<tr>
            		<td><b>Adjustment Date:</b> ' . date('d M, Y', strtotime($row->adjustment_date)) . '</td>
            	</tr>
            	</thead></table>';


            $pdf->writeHTML($txt, true, false, false, false, '');

            $txt = '<table border="1" cellpadding="5" cellspacing="0">
           		<thead>
           			<tr>
           				<td width="30"><b>#</b></td>
           				<td width="340"><b>Product Name</b></td>
           				<td width="150"><b>Current Qty</b></td>
           				<td width="150"><b>Adjusted Qty</b></td>
           			</tr>
           		</thead>
           		<tbody>';
           	$count = 1;
           	foreach ($stock_adjustment_details as $row2){
           		$txt = $txt . '<tr>
           			<td width="30">' . $count . '</td>
       				<td width="340">'. $row2->product_name . '<br><i>SKU: ' . $row2->product_sku_code . '</i></td>
       				<td width="150">' . number_format($row2->current_quantity) . '</td>
       				<td width="150">' . number_format($row2->adjusted_quantity) . '</td>
           		</tr>';
           		$count++;
           	}
           	
           $txt = $txt . '</tbody></table>';

           	$pdf->writeHTML($txt, true, false, false, false, '');

           	$txt = '<table border="1" cellpadding="5" cellspacing="0">
           			<tr>
           				<td colspan="2" align="left"><b>Remark:</b><br/>' . $row->remark .  '</td>
           			</tr>
           			<tr>
           				<td colspan="2" align="center"><small>Printed On: '. date('d-m-Y') . '</small></td>
           			</tr>
           		<tbody>';
           	$txt = $txt . '</tbody></table>';
           	$pdf->writeHTML($txt, true, false, false, false, '');


        	$attachment = $pdf->Output($filename, 'S');
        }

        return $attachment;
    }

    function stock_adjustment_void_valid($stock_adjustment_id){
    	//$is_void = true;
    	$errMsg = '';

        $stock_adjustment = $this->get_stock_adjustment($stock_adjustment_id);

        foreach ($stock_adjustment as $row) {
            if ($row->is_void == 0) {
            } else {
                $errMsg = 'This Stock Adjustment has already been voided.';
            }
        }

        if ($errMsg == '') {
            $arr_return = array('res' => true,'dt' => 'Valid','data' => $stock_adjustment);
        } else {
            $arr_return = array('res' => false,'dt' => $errMsg,'data' => $stock_adjustment);
        }
        return $arr_return;
    }
    function submit_void_stock_adjustment(){
    	$stock_adjustment_id = $this->input->post('stock_adjustment_id');

    	$void_date = date('Y-m-d H:i:s');
    	$void_reason = $this->input->post('void_reason');

        $data = array(
            'is_void' => 1,
            'void_reason' => $void_reason,
            'void_user_id' => $this->session->userdata('system_user_id'),
            'void_date' => $void_date
        );
        
        $this->db->where( array('stock_adjustment_id' => $stock_adjustment_id));
        $void = $this->db->update('stock_adjustments', $data);

        if ($void) {

        	//RETURN STOCK
        	$outlet_id = 0;
        	$stock_adjustment_number = '';
        	$stock_adjustment = $this->get_stock_adjustment($stock_adjustment_id);
        	foreach ($stock_adjustment as $row) {
        		$outlet_id = $row->outlet_id;
        		$stock_adjustment_number = $row->stock_adjustment_number;
        	}

            $stock_adjustment_details = $this->get_stock_adjustment_details($stock_adjustment_id);

            foreach ($stock_adjustment_details as $row) {
                //$quantity = $row->returned_quantity;
                $product_id = $row->product_id;
                $product_variation_id = $row->product_variation_id;
                $stock_adjustment_detail_id = $row->stock_adjustment_detail_id;


                $available_stock = 0;

                $this->db->select("*");
                $this->db->from('outlet_products');
                $this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                $outlet_product = $this->db->get()->result();
                foreach ($outlet_product as $row2) {
                    $available_stock = $row2->available_stock;
                }

                $data = array(
                    'available_stock' =>  $available_stock + ($row->current_quantity - $row->adjusted_quantity)
                );
                $this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                $this->db->update('outlet_products', $data);

                //STOCK CHECKER
                $this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $stock_adjustment_detail_id, 'transaction_description' => 'Stock Adjustment'));
				$this->db->delete('stock_tracker');
            }

            //NOTIFICATION
            $data = array(
                'notification_type' => 'Stock Adjustment Voided',
                'notification_ref_id' => $stock_adjustment_id,
                'notification_details' => 'Stock Adjustment <b>#' . $stock_adjustment_number . '</b> has been voided on  <b>' . $void_date . '</b> by <b>' . $this->session->userdata('user_first_name') . ' ' . $this->session->userdata('user_last_name') . '</b>. Void Reason: <b>' . $void_reason . '</b>',
                'notification_ref_link' => 'be/inventory/stock_adjustment_detail/' . $stock_adjustment_id
            );
            $this->db->insert('notifications',$data);

            $arr_return = array('res' => true,'dt' => 'Stock Adjustment voided successfully.');
        } else {
            $arr_return = array('res' => false,'dt' => 'There was an error trying to void this Stock Adjustment. Please try again.');
        }

        return $arr_return;

    }

    //STOCK WRITE-OFFS
	function get_stock_writeoffs(){
		// $purchase_order_status = $this->input->post('purchase_order_status');
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');
        $status = $this->input->post('status');
        $outlet_id = $this->input->post('outlet_id');
        $system_user_id = $this->input->post('system_user_id');

		$this->db->select("stock_writeoffs.*, outlets.outlet_id, outlets.outlet_name, system_users.system_user_id, system_users.first_name, system_users.last_name, su2.first_name AS 'void_first_name', su2.last_name AS 'void_last_name'");
		$this->db->from('stock_writeoffs');
		$this->db->join('outlets', 'outlets.outlet_id = stock_writeoffs.outlet_id', 'left outer');
		$this->db->join('system_users', 'system_users.system_user_id = stock_writeoffs.created_by', 'left outer');
		$this->db->join('system_users su2', 'su2.system_user_id = stock_writeoffs.void_user_id', 'left outer');

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(stock_writeoffs.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(stock_writeoffs.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }

        if ($outlet_id != ''){
      		$this->db->where( array('stock_writeoffs.outlet_id' => $outlet_id));
        }

        if ($system_user_id != ''){
      		$this->db->where( array('stock_writeoffs.created_by' => $system_user_id));
        }

        if ($status == 'Active') {
        	$this->db->where( array('stock_writeoffs.is_void' => 0));
        } elseif ($status == 'Void') {
        	$this->db->where( array('stock_writeoffs.is_void' => 1));
        }

        $this->db->order_by("stock_writeoffs.stock_writeoff_id", "desc");

		return $this->db->get()->result();
	}

	function get_new_stock_writeoff_number(){
		$stock_writeoff_number = '';
		$prefix_name = '';
		$current_value = 1;
		$this->db->from('prefixes');
		$this->db->where( array('document_name' => 'Stock Write-off'));
		$result = $this->db->get()->result();
		foreach ($result as $row){
			$prefix_name = $row->prefix_name;
			$current_value = $row->current_value;
		}

		$current_value = $current_value + 1;
		$stock_writeoff_number = $prefix_name . $current_value;

		return $stock_writeoff_number;
	}

	function save_stock_writeoff(){

		$data = array(
			'stock_writeoff_number' => $this->get_new_stock_writeoff_number(),
			'writeoff_date' => $this->input->post('writeoff_date'),
			'outlet_id' => $this->input->post('outlet_id'),
			'total_quantity' => $this->input->post('swri_total_detail_qty'),
			'remark' => $this->input->post('remark'),
			'created_by' => $this->session->userdata('system_user_id')
		);	

		$insert = $this->db->insert('stock_writeoffs', $data);
		$insert_id = $this->db->insert_id();

		if ($insert){

			//PREFIXES
			$this->update_current_stock_writeoff_number();

			//PURCHASE ORDER DETAILS
			$this->save_stock_writeoff_details($insert_id);

			$arr_return = array('res' => true,'dt' => 'Stock written off successfully.','id' => $insert_id);			
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not write off stock successfully. Please try again.','id' => '');
		}
		return $arr_return;
	}

	function update_current_stock_writeoff_number(){
		$current_value = 1;
		$this->db->from('prefixes');
		$this->db->where( array('document_name' => 'Stock Write-off'));
		$result = $this->db->get()->result();
		foreach ($result as $row){
			$current_value = $row->current_value;
		}
		$current_value = $current_value + 1;

		$data = array(
			'current_value' => $current_value
		);	
		$this->db->where(array('document_name' => 'Stock Write-off'));
		$this->db->update('prefixes', $data);
	}

	function save_stock_writeoff_details($stock_writeoff_id){
		$swri_detail_product_id = $this->input->post('swri_detail_product_id');
		$swri_detail_product_variation_id = $this->input->post('swri_detail_product_variation_id');
		$swri_detail_qty = $this->input->post('swri_detail_qty');
		
		foreach( $swri_detail_product_id as $key => $n ) {

			$current_quantity = $this->get_outlet_product_quantity($this->input->post('outlet_id'), $n, $swri_detail_product_variation_id[$key]);

			//Stock Write-off DETAILS
			$new_data = array(
				'stock_writeoff_id' => $stock_writeoff_id,
				'product_id' => $n,
				'product_variation_id' => $swri_detail_product_variation_id[$key],
				'writeoff_quantity' => $swri_detail_qty[$key]
			);
			$insert = $this->db->insert('stock_writeoff_details', $new_data);
			$stock_writeoff_detail_id = $this->db->insert_id();

			$product = $this->get_product($n);
			foreach ($product as $row) {
				$product_name = $row->product_name;
			}

			$outlet_id = $this->input->post('outlet_id');
			$available_stock = 0;
			$reorder_level = 0;
			$outlet_name = '';
			$unit_price = 0;

			//OUTLET PRODUCTS
			$this->db->select("op.*, o.outlet_name");
			$this->db->from('outlet_products op');
			$this->db->join('outlets o', 'o.outlet_id = op.outlet_id');
			$this->db->where( array('op.outlet_id' => $outlet_id, 'op.product_id' => $n, 'op.product_variation_id' => $swri_detail_product_variation_id[$key]));
			$outlet_product = $this->db->get()->result();

			foreach ($outlet_product as $row) {
				$available_stock = $row->available_stock;
				$reorder_level = $row->reorder_level;
				$outlet_name = $row->outlet_name;
			}

			$data = array(
				'available_stock' => $available_stock - $swri_detail_qty[$key]
			);	
			$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $n, 'product_variation_id' => $swri_detail_product_variation_id[$key]));
			$this->db->update('outlet_products', $data);

			//REORDER LEVEL NOTIFICATION
            if (($available_stock > $reorder_level) && (($available_stock - $swri_detail_qty[$key]) <= $reorder_level)) {
                //NOTIFICATION
                $data = array(
                    'notification_type' => 'Reorder Level Limit Reached',
                    'notification_ref_id' => $n,
                    'notification_details' => 'The product <b>#' . $product_name . '</b> has reached its reorder level limit for outlet <b>' . $outlet_name . '</b> and needs replenishing',
                    'notification_ref_link' => 'be/inventory/low_stocks'
                );
                $this->db->insert('notifications',$data);
            }

			//STOCK TRACKER
			$data = array(
				'outlet_id' => $outlet_id,
				'product_id' => $n,
				'product_variation_id' => $swri_detail_product_variation_id[$key],
				'transaction_id' => $stock_writeoff_detail_id,
				'transaction_type' => 'OUT',
				'transaction_description' => 'Stock Writeoff',
				'quantity' => $swri_detail_qty[$key],
				'unit_price' => $unit_price
			);	

			$this->db->insert('stock_tracker', $data);
		}
	}

	function get_auto_writeoff_stock_products($term, $outlet_id){
		$this->db->select('products.*, brands.brand_id, brands.brand_name, units.unit_id, units.unit_name, units.unit_code, outlet_products.*');
		$this->db->from('products');
		$this->db->join('brands', 'brands.brand_id = products.brand_id', 'left outer');
		$this->db->join('units', 'units.unit_id = products.unit_id', 'left outer');
		$this->db->join('outlet_products', 'outlet_products.product_id = products.product_id');

		$this->db->group_start();
        $this->db->like('products.product_name', $term);
        $this->db->or_like('products.product_sku_code', $term);
        $this->db->or_like('products.product_barcode', $term);
        $this->db->group_end();		

        $this->db->group_by('products.product_id');

		$this->db->where( array('products.is_deleted'=>0, 'outlet_products.outlet_id'=>$outlet_id));
		return $this->db->get()->result();
	}


	function get_stock_writeoff($stock_writeoff_id){
		$this->db->select("stock_writeoffs.*, outlets.outlet_id, outlets.outlet_name, system_users.system_user_id, system_users.first_name, system_users.last_name, su2.first_name AS 'void_first_name', su2.last_name AS 'void_last_name'");
		$this->db->from('stock_writeoffs');
		$this->db->join('outlets', 'outlets.outlet_id = stock_writeoffs.outlet_id', 'left outer');
		$this->db->join('system_users', 'system_users.system_user_id = stock_writeoffs.created_by', 'left outer');
		$this->db->join('system_users su2', 'su2.system_user_id = stock_writeoffs.void_user_id', 'left outer');

		$this->db->where( array('stock_writeoffs.stock_writeoff_id' => $stock_writeoff_id));
		return $this->db->get()->result();
	}
	function get_stock_writeoff_details($stock_writeoff_id){
		$this->db->select('stock_writeoff_details.*, products.product_id, products.product_sku_code, products.product_barcode, products.product_name');
		$this->db->from('stock_writeoff_details');
		$this->db->join('products', 'products.product_id = stock_writeoff_details.product_id', 'left outer');

		$this->db->where( array('stock_writeoff_details.stock_writeoff_id' => $stock_writeoff_id));
		
		$stock_writeoff_details = $this->db->get()->result();

        $i = 0;
        foreach($stock_writeoff_details as $row){
            $stock_writeoff_details[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $i++;
        }
        return $stock_writeoff_details;
	}

	function update_stock_writeoff(){
		$stock_writeoff_id = $this->input->post('stock_writeoff_id');

		$data = array(
			'writeoff_date' => $this->input->post('writeoff_date'),
			'outlet_id' => $this->input->post('outlet_id'),
			'total_quantity' => $this->input->post('swri_total_detail_qty'),
			'remark' => $this->input->post('remark')
		);	

		$this->db->where(array('stock_writeoff_id' => $stock_writeoff_id));
		$update = $this->db->update('stock_writeoffs', $data);
		
		if ($update){

			//Stock Write-off DETAILS
			$this->update_stock_writeoff_details($stock_writeoff_id);

			$arr_return = array('res' => true,'dt' => 'Stock Write-off updated successfully.','id' => $stock_writeoff_id);
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not update Stock Write-off successfully. Please try again.','id' => $stock_writeoff_id);
		}
		return $arr_return;
	}

	function update_stock_writeoff_details($stock_writeoff_id){
		$swri_detail_product_id = $this->input->post('swri_detail_product_id');
		$swri_detail_product_variation_id = $this->input->post('swri_detail_product_variation_id');
		$swri_detail_qty = $this->input->post('swri_detail_qty');

		$stock_writeoff_details = $this->get_stock_writeoff_details($stock_writeoff_id);

		foreach ($stock_writeoff_details as $row){

			$found = false;
			$already_writeoff_qty = $row->writeoff_quantity;
			$product_id = $row->product_id;
			$product_name = $row->product_name;
			$product_variation_id = $row->product_variation_id;
			$stock_writeoff_detail_id = $row->stock_writeoff_detail_id;
			$outlet_id = $this->input->post('outlet_id');

			foreach( $swri_detail_product_id as $key => $n ) {
				if ($product_id == $n && $product_variation_id == $swri_detail_product_variation_id[$key]){
					$found = true;
					break;
				}
			}

			if ($found == false){
			   $this->db->where('stock_writeoff_detail_id', $stock_writeoff_detail_id);
			   $this->db->delete('stock_writeoff_details'); 	

			   //OUTLETS
				$this->db->from('outlet_products');
				$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
				$query = $this->db->get();

				if ($query->num_rows() > 0){
					$available_stock = 0;
					$reorder_level = 0;
					$outlet_name = '';
					$result = $query->result();
					foreach ($result as $row){
						$available_stock = $row->available_stock;
						$reorder_level = $row->reorder_level;
						$outlet_name = $row->outlet_name;
					}
					$available_stock = $available_stock + $swri_detail_qty[$key];
					$data = array(
						'available_stock' => $available_stock
					);	
					$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
					$this->db->update('outlet_products', $data);
				}

				//STOCK CHECKER
				$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $stock_writeoff_detail_id, 'transaction_description' => 'Stock Writeoff'));
				$this->db->delete('stock_tracker');

			}else{
				foreach( $swri_detail_product_id as $key => $n ) {
					if ($product_id == $n && $product_variation_id == $swri_detail_product_variation_id[$key]){

						$data = array(
							'writeoff_quantity' => $swri_detail_qty[$key]
						);

						$this->db->where(array('stock_writeoff_id' => $stock_writeoff_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
						$this->db->update('stock_writeoff_details', $data);

						$outlet_id = $this->input->post('outlet_id');

						//OUTLETS
						$this->db->select("op.*, o.outlet_name");
						$this->db->from('outlet_products op');
						$this->db->join('outlets o', 'o.outlet_id = op.outlet_id');
						$this->db->where( array('op.outlet_id' => $outlet_id, 'op.product_id' => $product_id, 'op.product_variation_id' => $product_variation_id));
						$query = $this->db->get();

						if ($query->num_rows() > 0){
							$available_stock = 0;
							$reorder_level = 0;
							$outlet_name = '';
							$result = $query->result();
							foreach ($result as $row){
								$available_stock = $row->available_stock;
								$reorder_level = $row->reorder_level;
								$outlet_name = $row->outlet_name;
							}
							$available_stock = ($available_stock + $already_writeoff_qty) - $swri_detail_qty[$key];
							$data = array(
								'available_stock' => $available_stock
							);	
							$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
							$this->db->update('outlet_products', $data);

							//REORDER LEVEL NOTIFICATION
				            if (($available_stock + $already_writeoff_qty > $reorder_level) && (($available_stock + $already_writeoff_qty) - $swri_detail_qty[$key] <= $reorder_level)) {
				                //NOTIFICATION
				                $data = array(
				                    'notification_type' => 'Reorder Level Limit Reached',
				                    'notification_ref_id' => $product_id,
				                    'notification_details' => 'The product <b>#' . $product_name . '</b> has reached its reorder level limit for outlet <b>' . $outlet_name . '</b> and needs replenishing',
				                    'notification_ref_link' => 'be/inventory/low_stocks'
				                );
				                $this->db->insert('notifications',$data);
				            }
						}

						//STOCK CHECKER
						$data = array(
							'quantity' => $swri_detail_qty[$key]
						);
						$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $stock_writeoff_detail_id, 'transaction_description' => 'Stock Writeoff'));
						$this->db->update('stock_tracker', $data);
					}
				}
			}
		}

		$stock_writeoff_details = $this->get_stock_writeoff_details($stock_writeoff_id);

		foreach( $swri_detail_product_id as $key => $n ) {
			$found = false;
			$product_name = '';
			foreach ($stock_writeoff_details as $row){
				if ($row->product_id == $n && $row->product_variation_id == $swri_detail_product_variation_id[$key]){
					$product_name = $row->row->product_name;
					$found = true;
					break;
				}
			}
			if ($found == false){

				$current_quantity = $this->get_outlet_product_quantity($this->input->post('outlet_id'), $n, $swri_detail_product_variation_id[$key]);

				//Stock Write-off DETAILS
				$new_data = array(
					'stock_writeoff_id' => $stock_writeoff_id,
					'product_id' => $n,
					'product_variation_id' => $swri_detail_product_variation_id[$key],
					'writeoff_quantity' => $swri_detail_qty[$key]
				);
				$insert = $this->db->insert('stock_writeoff_details', $new_data);
				$stock_writeoff_detail_id = $this->db->insert_id();

				$outlet_id = $this->input->post('outlet_id');
				$available_stock = 0;
				$reorder_level = 0;
				$outlet_name = '';
				$unit_price = 0;

				//OUTLET PRODUCTS
				$this->db->select("op.*, o.outlet_name");
				$this->db->from('outlet_products op');
				$this->db->join('outlets o', 'o.outlet_id = op.outlet_id');
				$this->db->where( array('op.outlet_id' => $outlet_id, 'op.product_id' => $n, 'op.product_variation_id' => $swri_detail_product_variation_id[$key]));
				$outlet_product = $this->db->get()->result();

				foreach ($outlet_product as $row) {
					$available_stock = $row->available_stock;
					$reorder_level = $row->reorder_level;
					$outlet_name = $row->outlet_name;
					$unit_price = $row->regular_price;
				}

				$data = array(
					'available_stock' => $available_stock - $swri_detail_qty[$key]
				);	
				$this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $n, 'product_variation_id' => $swri_detail_product_variation_id[$key]));
				$this->db->update('outlet_products', $data);

				//REORDER LEVEL NOTIFICATION
	            if (($available_stock > $reorder_level) && (($available_stock - $swri_detail_qty[$key]) <= $reorder_level)) {
	                //NOTIFICATION
	                $data = array(
	                    'notification_type' => 'Reorder Level Limit Reached',
	                    'notification_ref_id' => $n,
	                    'notification_details' => 'The product <b>#' . $product_name . '</b> has reached its reorder level limit for outlet <b>' . $outlet_name . '</b> and needs replenishing',
	                    'notification_ref_link' => 'be/inventory/low_stocks'
	                );
	                $this->db->insert('notifications',$data);
	            }

				//STOCK TRACKER
				$data = array(
					'outlet_id' => $outlet_id,
					'product_id' => $n,
					'product_variation_id' => $swri_detail_product_variation_id[$key],
					'transaction_id' => $stock_writeoff_detail_id,
					'transaction_type' => 'OUT',
					'transaction_description' => 'Stock Writeoff',
					'quantity' => $swri_detail_qty[$key],
					'unit_price' => $unit_price
				);	

				$this->db->insert('stock_tracker', $data);
			}
		}
	}

	function submit_send_stock_writeoff_via_email() {

        $stock_writeoff_id = $this->input->post('stock_writeoff_id');

        try {

            ob_start();

            $mail          = new PHPMailer();
            $mail->IsSMTP();

            $use_ssl = $this->input->post('chk_use_ssl');

            if($use_ssl == 'on'){
                $mail->SMTPSecure = 'ssl';
                $mail->SMTPAuth   = true;
            }            
            $mail->Host       = $this->input->post('mail_server_name');
            $mail->Port       = $this->input->post('mail_server_port');
            $mail->Username   = $this->input->post('sender_username');
            $mail->Password   = $this->input->post('sender_password');
            
            $mail->SetFrom($this->input->post('sender_email_address'), $this->input->post('sender_name'));
            $email_to = $this->input->post('recipient_email_address'); 
             
            $mail->Subject = $this->input->post('email_subject');

            $email_message = $this->input->post('email_message'); 

            
            $message = file_get_contents(base_url().'email_temp/emheader');
            $message .= file_get_contents(base_url().'email_temp/embody');
            $message .= file_get_contents(base_url().'email_temp/emfooter');
            $logo = base_url().'assets/fe/img/logo.png';
            
            $replacements = array(
                '({logo})' => $logo, 
                '({message_subject})' => '', 
                '({message_body})' => nl2br( stripslashes( $email_message ) )
            );
            $message = preg_replace(array_keys( $replacements ), array_values( $replacements ), $message );
            
            $plaintext = $message;
            $plaintext = strip_tags( stripslashes( $plaintext ), '<p><br><h2><h3><h1><h4>' );
            $plaintext = str_replace( array( '<p>', '<br />', '<br>', '<h1>', '<h2>', '<h3>', '<h4>' ), PHP_EOL, $plaintext );
            $plaintext = str_replace( array( '</p>', '</h1>', '</h2>', '</h3>', '</h4>' ), '', $plaintext );
            $plaintext = html_entity_decode( stripslashes( $plaintext ) );
        
            
            $mail->MsgHTML( stripslashes( $message ) ); 

            $attachment = $this->generate_stock_writeoff_pdf($stock_writeoff_id);
            $mail->AddStringAttachment($attachment, 'Bethany House Stock Write-off -'. $stock_writeoff_id . '.pdf', 'base64', 'application/pdf');
            
            $mail->AltBody = $plaintext;
            $mail->AddAddress($email_to, "");

            if( !$mail->Send() ){
                $arr_return = array('res' => false,'dt' => $mail->ErrorInfo);
            }else{
                $arr_return = array('res' => true,'dt' => 'Email Sent successfully');
            }
            ob_get_clean();
        } catch (phpmailerException $e) {
            $arr_return = array('res' => false,'dt' =>  $e->errorMessage());
        } catch (Exception $e) {
            $arr_return = array('res' => false,'dt' =>  $e->getMessage());
        }        
        return $arr_return;
    }

    function generate_stock_writeoff_pdf($stock_writeoff_id){
    	$stock_writeoff = $this->inventory_model->get_stock_writeoff($stock_writeoff_id);
		$stock_writeoff_details = $this->inventory_model->get_stock_writeoff_details($stock_writeoff_id);

		$default_currency = $this->currencies_model->get_default_currency();
		$store_information = $this->store_information_model->get_store_information();

		$attachment = '';

		foreach ($stock_writeoff as $row) {

			$filename='Bethany House Stock Write-off - '.$row->stock_writeoff_number.'.pdf';

            // create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Bethany House');
            $pdf->SetTitle('Bethany House Stock Write-off - '.$row->stock_writeoff_number);
            $pdf->SetSubject('Bethany House Stock Write-off - '.$row->stock_writeoff_number);
            //$pdf->SetKeywords('TCPDF, PDF, example, test, guide');

            // remove default header/footer
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            // set default monospaced font
            $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

            // set margins
            //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

            // set auto page breaks
            $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

            // set image scale factor
            $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

            // set font
            $pdf->SetFont('helvetica', '', 8);

            $pdf->setCellHeightRatio(1.6);

            // add a page
            $pdf->AddPage();

            $pdf->Ln(10);

            $txt = '<table border="1" cellpadding="5" cellspacing="0">';
            $txt = $txt . '<thead>';
            $store_logo = '';
            foreach ($store_information as $row2){
                if($row2->store_logo != '' && file_exists("./uploads/store_logo/" . $row2->store_logo)){
                    $store_logo = base_url() . 'uploads/store_logo/' . $row2->store_logo;
                } else {
                    $store_logo = base_url() . 'assets/fe/img/logo.png';
                }
            }
            $txt = $txt . '<tr>
            	<td rowspan="3" width="224"><img src="' . $store_logo . '"  width="200px"><br /><br />';

            	// </td>
            	// <td rowspan="2" width="200">';

            	foreach ($store_information as $row2){
                    $txt = $txt . '<b>' . $row2->store_name . '</b><br />
                    <b>Phone:</b> ' . $row2->phone_number . '<br />
                    <b>Address:</b> ' . $row2->physical_address . '<br />
                    <b>Email:</b> ' . $row2->email_address;
                }
            	$txt = $txt . '</td>
            	<td rowspan="3"></td>
            	<td><b>Stock Write-off No:</b> ' . $row->stock_writeoff_number . '</td>
            	</tr>
            	<tr>
            		<td><b>Outlet:</b> ' . $row->outlet_name . '</td>
            	</tr>
            	<tr>
            		<td><b>Write-off Date:</b> ' . date('d M, Y', strtotime($row->writeoff_date)) . '</td>
            	</tr>
            	</thead></table>';


            $pdf->writeHTML($txt, true, false, false, false, '');

            $txt = '<table border="1" cellpadding="5" cellspacing="0">
           		<thead>
           			<tr>
           				<td width="30"><b>#</b></td>
           				<td width="390"><b>Product Name</b></td>
           				<td width="250"><b>Written Off Qty</b></td>
           			</tr>
           		</thead>
           		<tbody>';
           	$count = 1;
           	foreach ($stock_writeoff_details as $row2){
           		$txt = $txt . '<tr>
           			<td width="30">' . $count . '</td>
       				<td width="390">'. $row2->product_name . '<br><i>SKU: ' . $row2->product_sku_code . '</i></td>
       				<td width="250">' . number_format($row2->writeoff_quantity) . '</td>
           		</tr>';
           		$count++;
           	}
           	
           $txt = $txt . '</tbody></table>';

           	$pdf->writeHTML($txt, true, false, false, false, '');

           	$txt = '<table border="1" cellpadding="5" cellspacing="0">
           			<tr>
           				<td colspan="1" align="left"><b>Remark:</b><br/>' . $row->remark .  '</td>
           			</tr>
           			<tr>
           				<td colspan="1" align="center"><small>Printed On: '. date('d-m-Y') . '</small></td>
           			</tr>
           		<tbody>';
           	$txt = $txt . '</tbody></table>';
           	$pdf->writeHTML($txt, true, false, false, false, '');


        	$attachment = $pdf->Output($filename, 'S');
        }

        return $attachment;
    }

    function stock_writeoff_void_valid($stock_writeoff_id){
    	//$is_void = true;
    	$errMsg = '';

        $stock_writeoff = $this->get_stock_writeoff($stock_writeoff_id);

        foreach ($stock_writeoff as $row) {
            if ($row->is_void == 0) {
            } else {
                $errMsg = 'This Stock Write-off has already been voided.';
            }
        }

        if ($errMsg == '') {
            $arr_return = array('res' => true,'dt' => 'Valid','data' => $stock_writeoff);
        } else {
            $arr_return = array('res' => false,'dt' => $errMsg,'data' => $stock_writeoff);
        }
        return $arr_return;
    }
    function submit_void_stock_writeoff(){
    	$stock_writeoff_id = $this->input->post('stock_writeoff_id');

    	$void_date = date('Y-m-d H:i:s');
    	$void_reason = $this->input->post('void_reason');

        $data = array(
            'is_void' => 1,
            'void_reason' => $void_reason,
            'void_user_id' => $this->session->userdata('system_user_id'),
            'void_date' => $void_date
        );
        $this->db->where( array('stock_writeoff_id' => $stock_writeoff_id));
        $void = $this->db->update('stock_writeoffs', $data);

        if ($void) {

        	//RETURN STOCK
        	$outlet_id = 0;
        	$stock_writeoff_number = '';
        	$stock_writeoff = $this->get_stock_writeoff($stock_writeoff_id);
        	foreach ($stock_writeoff as $row) {
        		$outlet_id = $row->outlet_id;
        		$stock_writeoff_number = $row->stock_writeoff_number;
        	}

            $stock_writeoff_details = $this->get_stock_writeoff_details($stock_writeoff_id);

            foreach ($stock_writeoff_details as $row) {
                //$quantity = $row->returned_quantity;
                $product_id = $row->product_id;
                $product_variation_id = $row->product_variation_id;
                $stock_writeoff_detail_id = $row->stock_writeoff_detail_id;

                $available_stock = 0;

                $this->db->select("*");
                $this->db->from('outlet_products');
                $this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                $outlet_product = $this->db->get()->result();
                foreach ($outlet_product as $row2) {
                    $available_stock = $row2->available_stock;
                }

                $data = array(
                    'available_stock' =>  $available_stock + $row->writeoff_quantity
                );
                $this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                $this->db->update('outlet_products', $data);

                //STOCK CHECKER
                $this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $stock_writeoff_detail_id, 'transaction_description' => 'Stock Writeoff'));
				$this->db->delete('stock_tracker');
            }

            //NOTIFICATION
            $data = array(
                'notification_type' => 'Stock Write-off Voided',
                'notification_ref_id' => $stock_writeoff_id,
                'notification_details' => 'Stock Write-off <b>#' . $stock_writeoff_number . '</b> has been voided on  <b>' . $void_date . '</b> by <b>' . $this->session->userdata('user_first_name') . ' ' . $this->session->userdata('user_last_name') . '</b>. Void Reason: <b>' . $void_reason . '</b>',
                'notification_ref_link' => 'be/inventory/stock_writeoff_detail/' . $stock_writeoff_id
            );
            $this->db->insert('notifications',$data);

            $arr_return = array('res' => true,'dt' => 'Stock Write-off voided successfully.');
        } else {
            $arr_return = array('res' => false,'dt' => 'There was an error trying to void this Stock Write-off. Please try again.');
        }

        return $arr_return;

    }

    function update_stock_tracker(){

    	// Opening Stock
    	$this->db->select('op.*, p.created_on');
		$this->db->from('outlet_products op');
		$this->db->join('products p', 'p.product_id = op.product_id');
		$outlet_products = $this->db->get()->result();

		foreach ($outlet_products as $row) {
			$this->db->select('*');
			$this->db->from('stock_tracker');
			$this->db->where( array('product_id' => $row->product_id, 'product_variation_id' => $row->product_variation_id, 'outlet_id' => $row->outlet_id, 'transaction_description' => 'Opening Stock'));
			$tracker_count = $this->db->count_all_results();

			if ($tracker_count <= 0 ){
				$data = array(
					'outlet_id' => $row->outlet_id,
					'product_id' => $row->product_id,
					'product_variation_id' => $row->product_variation_id,
					'transaction_id' => 0,
					'transaction_type' => 'IN',
					'transaction_description' => 'Opening Stock',
					'quantity' => $row->opening_stock,
					'unit_price' => $row->regular_price,
					'created_on' => $row->created_on
				);	

				$this->db->insert('stock_tracker', $data);
			}
		}

		//Goods Receipt Note
    	$this->db->select('grnd.*, grn.receival_date, grn.outlet_id');
		$this->db->from('goods_receipt_note_details grnd');
		$this->db->join('goods_receipt_notes grn', 'grnd.goods_receipt_note_id = grn.goods_receipt_note_id');
		$this->db->where( array('grn.is_void' => 0));
		$goods_received = $this->db->get()->result();

		foreach ($goods_received as $row) {
			$this->db->select('*');
			$this->db->from('stock_tracker');
			$this->db->where( array('product_id' => $row->product_id, 'product_variation_id' => $row->product_variation_id, 'outlet_id' => $row->outlet_id, 'transaction_id' => $row->goods_receipt_note_detail_id, 'transaction_description' => 'Goods Received'));
			$tracker_count = $this->db->count_all_results();

			if ($tracker_count <= 0 ){
				$data = array(
					'outlet_id' => $row->outlet_id,
					'product_id' => $row->product_id,
					'product_variation_id' => $row->product_variation_id,
					'transaction_id' => $row->goods_receipt_note_detail_id,
					'transaction_type' => 'IN',
					'transaction_description' => 'Goods Received',
					'quantity' => $row->received_quantity,
					'unit_price' => $row->unit_price,
					'created_on' => $row->receival_date
				);	

				$this->db->insert('stock_tracker', $data);
			}
		}

		//Goods Return Note
    	$this->db->select('grnd.*, grn.return_date, grn.outlet_id');
		$this->db->from('goods_return_note_details grnd');
		$this->db->join('goods_return_notes grn', 'grnd.goods_return_note_id = grn.goods_return_note_id');
		$this->db->where( array('grn.is_void' => 0));
		$goods_received = $this->db->get()->result();

		foreach ($goods_received as $row) {
			$this->db->select('*');
			$this->db->from('stock_tracker');
			$this->db->where( array('product_id' => $row->product_id, 'product_variation_id' => $row->product_variation_id, 'outlet_id' => $row->outlet_id, 'transaction_id' => $row->goods_return_note_detail_id, 'transaction_description' => 'Goods Returned'));
			$tracker_count = $this->db->count_all_results();

			if ($tracker_count <= 0 ){
				$data = array(
					'outlet_id' => $row->outlet_id,
					'product_id' => $row->product_id,
					'product_variation_id' => $row->product_variation_id,
					'transaction_id' => $row->goods_return_note_detail_id,
					'transaction_type' => 'OUT',
					'transaction_description' => 'Goods Returned',
					'quantity' => $row->returned_quantity,
					'unit_price' => $row->unit_price,
					'created_on' => $row->return_date
				);	

				$this->db->insert('stock_tracker', $data);
			}
		}

		//Stock Adjustments
    	$this->db->select('grnd.*, grn.adjustment_date, grn.outlet_id');
		$this->db->from('stock_adjustment_details grnd');
		$this->db->join('stock_adjustments grn', 'grnd.stock_adjustment_id = grn.stock_adjustment_id');
		$this->db->where( array('grn.is_void' => 0));
		$goods_received = $this->db->get()->result();

		foreach ($goods_received as $row) {
			$this->db->select('*');
			$this->db->from('stock_tracker');
			$this->db->where( array('product_id' => $row->product_id, 'product_variation_id' => $row->product_variation_id, 'outlet_id' => $row->outlet_id, 'transaction_id' => $row->stock_adjustment_detail_id, 'transaction_description' => 'Stock Adjustment'));
			$tracker_count = $this->db->count_all_results();

			if ($tracker_count <= 0 ){
				$unit_price = 0;

				$this->db->select('op.*');
				$this->db->from('outlet_products op');
				$this->db->where( array('product_id' => $row->product_id, 'product_variation_id' => $row->product_variation_id, 'outlet_id' => $row->outlet_id));
				$outlet_product = $this->db->get()->result();

				foreach ($outlet_product as $row2) {
					$unit_price = $row2->regular_price;
				}

				$data = array(
					'outlet_id' => $row->outlet_id,
					'product_id' => $row->product_id,
					'product_variation_id' => $row->product_variation_id,
					'transaction_id' => $row->stock_adjustment_detail_id,
					'transaction_type' => 'IN',
					'transaction_description' => 'Stock Adjustment',
					'quantity' => ($row->adjusted_quantity - $row->current_quantity),
					'unit_price' => $unit_price,
					'created_on' => $row->adjustment_date
				);	

				$this->db->insert('stock_tracker', $data);
			}
		}

		//POS Sales
    	$this->db->select('grnd.*, grn.created_on, grn.outlet_id');
		$this->db->from('pos_sale_details grnd');
		$this->db->join('pos_sales grn', 'grnd.pos_sale_id = grn.pos_sale_id');
		$this->db->where( array('grn.is_void' => 0));
		$goods_received = $this->db->get()->result();

		foreach ($goods_received as $row) {
			$this->db->select('*');
			$this->db->from('stock_tracker');
			$this->db->where( array('product_id' => $row->product_id, 'product_variation_id' => $row->product_variation_id, 'outlet_id' => $row->outlet_id, 'transaction_id' => $row->pos_sale_detail_id, 'transaction_description' => 'POS Sale'));
			$tracker_count = $this->db->count_all_results();

			if ($tracker_count <= 0 ){
				$data = array(
					'outlet_id' => $row->outlet_id,
					'product_id' => $row->product_id,
					'product_variation_id' => $row->product_variation_id,
					'transaction_id' => $row->pos_sale_detail_id,
					'transaction_type' => 'OUT',
					'transaction_description' => 'POS Sale',
					'quantity' => $row->quantity,
					'unit_price' => $row->unit_price,
					'created_on' => $row->created_on
				);
				$this->db->insert('stock_tracker', $data);
			}
		}

		//Online Sales
    	$this->db->select('grnd.*, ord_date, grn.ord_dispatch_outlet_id');
		$this->db->from('order_details grnd');
		$this->db->join('order_summary grn', 'grnd.ord_det_order_number_fk = grn.ord_order_number');
       	$this->db->where(array('grn.ord_order_status != ' => 0));
        $this->db->where(array('grn.ord_order_status != ' => 1));
        $this->db->where(array('grn.ord_order_status != ' => 4));
		$goods_received = $this->db->get()->result();

		foreach ($goods_received as $row) {
			$this->db->select('*');
			$this->db->from('stock_tracker');
			$this->db->where( array('product_id' => $row->ord_det_product_id, 'product_variation_id' => $row->ord_det_product_variation_id, 'outlet_id' => $row->ord_dispatch_outlet_id, 'transaction_id' => $row->ord_det_id, 'transaction_description' => 'Online Sale'));
			$tracker_count = $this->db->count_all_results();

			if ($tracker_count <= 0 ){
				$data = array(
					'outlet_id' => $row->ord_dispatch_outlet_id,
					'product_id' => $row->ord_det_product_id,
					'product_variation_id' => $row->ord_det_product_variation_id,
					'transaction_id' => $row->ord_det_id,
					'transaction_type' => 'OUT',
					'transaction_description' => 'Online Sale',
					'quantity' => $row->ord_det_quantity,
					'unit_price' => $row->ord_det_price,
					'created_on' => $row->ord_date
				);
				$this->db->insert('stock_tracker', $data);
			}
		}

    }


    //STOCKS
    function get_current_stocks(){

    	$outlet_id = $this->input->post('outlet_id');

    	$this->db->select('p.product_name, p.product_id, p.product_sku_code, op.product_variation_id, op.opening_stock, op.available_stock, op.reorder_level, op.regular_price, op.sale_price, IFNull(QtyStockIn.Qty_In, 0) QtyStockIn, IFNull(StockIn.Amount, 0) StockIn');
		$this->db->from('products p');
		$this->db->join('outlet_products op', 'op.product_id = p.product_id');
		$this->db->join('(SELECT SUM(quantity) as Qty_In, product_id, product_variation_id FROM stock_tracker WHERE transaction_type = "IN" AND (transaction_description = "Opening Stock" OR transaction_description = "Goods Received") group by product_variation_id, product_id) QtyStockIn', 'QtyStockIn.product_id = p.product_id AND QtyStockIn.product_variation_id = op.product_variation_id', 'LEFT');
		$this->db->join('(SELECT SUM(quantity * unit_price) as Amount, product_id, product_variation_id FROM stock_tracker WHERE transaction_type = "IN" AND (transaction_description = "Opening Stock" OR transaction_description = "Goods Received") group by product_variation_id, product_id) StockIn', 'StockIn.product_id = p.product_id AND StockIn.product_variation_id = op.product_variation_id', 'LEFT');

		$this->db->where( array('p.is_deleted' => 0));
		$this->db->where( array('op.outlet_id' => $outlet_id));
		$this->db->group_by('op.product_variation_id, p.product_id');
		
		$outlet_inventory = $this->db->get()->result();

        $i = 0;
        foreach($outlet_inventory as $row){
            $outlet_inventory[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $i++;
        }
        return $outlet_inventory;
    }

    function get_low_stocks(){

    	$outlet_id = $this->input->post('outlet_id');

    	$this->db->select('p.product_name, p.product_id, p.product_sku_code, p.unit_id, op.product_variation_id, op.opening_stock, op.available_stock, op.reorder_level, op.regular_price, op.sale_price');
		$this->db->from('products p');
		$this->db->join('outlet_products op', 'op.product_id = p.product_id');
		$this->db->where( array('p.is_deleted' => 0));
		$this->db->where('op.available_stock <= op.reorder_level');
		$this->db->where( array('op.outlet_id' => $outlet_id));

		$this->db->group_by('op.product_variation_id, p.product_id');

		$outlet_inventory = $this->db->get()->result();

        $i = 0;
        foreach($outlet_inventory as $row){
            $outlet_inventory[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $outlet_inventory[$i]->units = $this->get_product_units($row->product_id);
            $i++;
        }
        return $outlet_inventory;
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