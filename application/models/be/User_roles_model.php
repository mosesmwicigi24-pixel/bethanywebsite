<?php
class User_roles_model extends CI_Model {
	
	function get_user_roles_list(){
		$this->db->select('user_roles.*, system_user_rights.*');
		$this->db->from('user_roles');
		$this->db->join('system_user_rights', 'user_roles.user_role_id = system_user_rights.user_role_id', 'left outer');
		$this->db->where( array('user_roles.is_deleted'=>0));
		return $this->db->get()->result();
	}
	function save($data){
		$insert = $this->db->insert('user_roles', $data);
		$insert_id = $this->db->insert_id();
		if ($insert){

			$this->save_user_role_permissions($insert_id);

			$arr_return = array('res' => true,'dt' => 'User Role added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not add User Role successfully. Please try again.');
		}
		return $arr_return;
	}
	function user_role_exists($user_role_name){
		$this->db->where(array('user_role_name' => $user_role_name, 'is_deleted' => 0));
		$query = $this->db->get('user_roles');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}

	}
	function get_user_role($user_role_id){
		$this->db->select('user_roles.*, system_user_rights.*');
		$this->db->from('user_roles');
		$this->db->join('system_user_rights', 'user_roles.user_role_id = system_user_rights.user_role_id', 'left outer');
		$this->db->where( array('user_roles.user_role_id' => $user_role_id));
		return $this->db->get()->result_array();
	}
	function get_user_role2($user_role_id){
		$this->db->select('user_roles.*, system_user_rights.*');
		$this->db->from('user_roles');
		$this->db->join('system_user_rights', 'user_roles.user_role_id = system_user_rights.user_role_id', 'left outer');
		$this->db->where( array('user_roles.user_role_id' => $user_role_id));
		return $this->db->get()->result();
	}
	function user_role_update_exists($user_role_id,$user_role_name){
		$this->db->where(array('user_role_name' => $user_role_name, 'is_deleted' => 0, 'user_role_id !=' => $user_role_id));
		$query = $this->db->get('user_roles');
		if ($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function update($data,$user_role_id){
		$this->db->where(array('user_role_id'=>$user_role_id));
		$update = $this->db->update('user_roles', $data);
		if ($update){
			$this->save_user_role_permissions($user_role_id);
			$arr_return = array('res' => true,'dt' => 'User Role updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not update Void Rreason successfully. Please try again.');
		}
		return $arr_return;
	}

	function save_user_role_permissions($user_role_id){
		$data = array(
			'backend_login' => $this->input->post('sbr_backend_login') == 'on' ? 1 : 0,
			'pos_login' => $this->input->post('sbr_pos_login') == 'on' ? 1 : 0,			
			'user_role_id' => $user_role_id,
			'pos_sales_orders_view' => $this->input->post('sbr_pos_sales_orders_view') == 'on' ? 1 : 0,
			'pos_sales_orders_add' => $this->input->post('sbr_pos_sales_orders_add') == 'on' ? 1 : 0,
			'pos_sales_orders_edit' => $this->input->post('sbr_pos_sales_orders_edit') == 'on' ? 1 : 0,
			'pos_sales_orders_delete' => $this->input->post('sbr_pos_sales_orders_delete') == 'on' ? 1 : 0,
			'pos_sales_orders_print' => $this->input->post('sbr_pos_sales_orders_print') == 'on' ? 1 : 0,
			'pos_sales_returns_view' => $this->input->post('sbr_pos_sales_returns_view') == 'on' ? 1 : 0,
			'pos_sales_returns_add' => $this->input->post('sbr_pos_sales_returns_add') == 'on' ? 1 : 0,
			'pos_sales_returns_edit' => $this->input->post('sbr_pos_sales_returns_edit') == 'on' ? 1 : 0,
			'pos_sales_returns_delete' => $this->input->post('sbr_pos_sales_returns_delete') == 'on' ? 1 : 0,
			'pos_sales_returns_print' => $this->input->post('sbr_pos_sales_returns_print') == 'on' ? 1 : 0,
			'pos_sales_returns_manage' => $this->input->post('sbr_pos_sales_returns_manage') == 'on' ? 1 : 0,
			'pos_quotations_view' => $this->input->post('sbr_pos_quotations_view') == 'on' ? 1 : 0,
			'pos_quotations_add' => $this->input->post('sbr_pos_quotations_add') == 'on' ? 1 : 0,
			'pos_quotations_edit' => $this->input->post('sbr_pos_quotations_edit') == 'on' ? 1 : 0,
			'pos_quotations_delete' => $this->input->post('sbr_pos_quotations_delete') == 'on' ? 1 : 0,
			'pos_quotations_print' => $this->input->post('sbr_pos_quotations_print') == 'on' ? 1 : 0,
			'pos_products_view' => $this->input->post('sbr_pos_products_view') == 'on' ? 1 : 0,
			'pos_customers_view' => $this->input->post('sbr_pos_customers_view') == 'on' ? 1 : 0,
			'pos_customers_add' => $this->input->post('sbr_pos_customers_add') == 'on' ? 1 : 0,
			'pos_customers_edit' => $this->input->post('sbr_pos_customers_edit') == 'on' ? 1 : 0,
			'pos_expenses_view' => $this->input->post('sbr_pos_expenses_view') == 'on' ? 1 : 0,
			'pos_expenses_add' => $this->input->post('sbr_pos_expenses_add') == 'on' ? 1 : 0,
			'pos_expenses_edit' => $this->input->post('sbr_pos_expenses_edit') == 'on' ? 1 : 0,
			'pos_expenses_delete' => $this->input->post('sbr_pos_expenses_delete') == 'on' ? 1 : 0,
			'pos_expenses_manage' => $this->input->post('sbr_pos_expenses_manage') == 'on' ? 1 : 0,
			'pos_reports_view' => $this->input->post('sbr_pos_reports_view') == 'on' ? 1 : 0,
			'store_information_view' => $this->input->post('sbr_store_information_view') == 'on' ? 1 : 0,
			'store_information_edit' => $this->input->post('sbr_store_information_edit') == 'on' ? 1 : 0,
			'outlets_view' => $this->input->post('sbr_outlets_view') == 'on' ? 1 : 0,
			'outlets_add' => $this->input->post('sbr_outlets_add') == 'on' ? 1 : 0,
			'outlets_edit' => $this->input->post('sbr_outlets_edit') == 'on' ? 1 : 0,
			'outlets_delete' => $this->input->post('sbr_outlets_delete') == 'on' ? 1 : 0,
			'countries_view' => $this->input->post('sbr_countries_view') == 'on' ? 1 : 0,
			'countries_add' => $this->input->post('sbr_countries_add') == 'on' ? 1 : 0,
			'countries_edit' => $this->input->post('sbr_countries_edit') == 'on' ? 1 : 0,
			'countries_delete' => $this->input->post('sbr_countries_delete') == 'on' ? 1 : 0,
			'countries_manage' => $this->input->post('sbr_countries_manage') == 'on' ? 1 : 0,
			'regions_view' => $this->input->post('sbr_regions_view') == 'on' ? 1 : 0,
			'regions_add' => $this->input->post('sbr_regions_add') == 'on' ? 1 : 0,
			'regions_edit' => $this->input->post('sbr_regions_edit') == 'on' ? 1 : 0,
			'regions_delete' => $this->input->post('sbr_regions_delete') == 'on' ? 1 : 0,
			'shipping_zones_view' => $this->input->post('sbr_shipping_zones_view') == 'on' ? 1 : 0,
			'shipping_zones_add' => $this->input->post('sbr_shipping_zones_add') == 'on' ? 1 : 0,
			'shipping_zones_edit' => $this->input->post('sbr_shipping_zones_edit') == 'on' ? 1 : 0,
			'shipping_zones_delete' => $this->input->post('sbr_shipping_zones_delete') == 'on' ? 1 : 0,
			'pickup_locations_view' => $this->input->post('sbr_pickup_locations_view') == 'on' ? 1 : 0,
			'pickup_locations_add' => $this->input->post('sbr_pickup_locations_add') == 'on' ? 1 : 0,
			'pickup_locations_edit' => $this->input->post('sbr_pickup_locations_edit') == 'on' ? 1 : 0,
			'pickup_locations_delete' => $this->input->post('sbr_pickup_locations_delete') == 'on' ? 1 : 0,
			'currencies_view' => $this->input->post('sbr_currencies_view') == 'on' ? 1 : 0,
			'currencies_add' => $this->input->post('sbr_currencies_add') == 'on' ? 1 : 0,
			'currencies_edit' => $this->input->post('sbr_currencies_edit') == 'on' ? 1 : 0,
			'currencies_delete' => $this->input->post('sbr_currencies_delete') == 'on' ? 1 : 0,
			'banks_view' => $this->input->post('sbr_banks_view') == 'on' ? 1 : 0,
			'banks_add' => $this->input->post('sbr_banks_add') == 'on' ? 1 : 0,
			'banks_edit' => $this->input->post('sbr_banks_edit') == 'on' ? 1 : 0,
			'banks_delete' => $this->input->post('sbr_banks_delete') == 'on' ? 1 : 0,
			'banks_manage' => $this->input->post('sbr_banks_manage') == 'on' ? 1 : 0,
			'bank_branches_view' => $this->input->post('sbr_bank_branches_view') == 'on' ? 1 : 0,
			'bank_branches_add' => $this->input->post('sbr_bank_branches_add') == 'on' ? 1 : 0,
			'bank_branches_edit' => $this->input->post('sbr_bank_branches_edit') == 'on' ? 1 : 0,
			'bank_branches_delete' => $this->input->post('sbr_bank_branches_delete') == 'on' ? 1 : 0,
			'tax_rates_view' => $this->input->post('sbr_tax_rates_view') == 'on' ? 1 : 0,
			'tax_rates_add' => $this->input->post('sbr_tax_rates_add') == 'on' ? 1 : 0,
			'tax_rates_edit' => $this->input->post('sbr_tax_rates_edit') == 'on' ? 1 : 0,
			'tax_rates_delete' => $this->input->post('sbr_tax_rates_delete') == 'on' ? 1 : 0,
			'credit_terms_view' => $this->input->post('sbr_credit_terms_view') == 'on' ? 1 : 0,
			'credit_terms_add' => $this->input->post('sbr_credit_terms_add') == 'on' ? 1 : 0,
			'credit_terms_edit' => $this->input->post('sbr_credit_terms_edit') == 'on' ? 1 : 0,
			'credit_terms_delete' => $this->input->post('sbr_credit_terms_delete') == 'on' ? 1 : 0,
			'mpesa_settings_view' => $this->input->post('sbr_mpesa_settings_view') == 'on' ? 1 : 0,
			'mpesa_settings_edit' => $this->input->post('sbr_mpesa_settings_edit') == 'on' ? 1 : 0,
			'pesapal_settings_view' => $this->input->post('sbr_pesapal_settings_view') == 'on' ? 1 : 0,
			'pesapal_settings_edit' => $this->input->post('sbr_pesapal_settings_edit') == 'on' ? 1 : 0,
			'email_accounts_view' => $this->input->post('sbr_email_accounts_view') == 'on' ? 1 : 0,
			'email_accounts_add' => $this->input->post('sbr_email_accounts_add') == 'on' ? 1 : 0,
			'email_accounts_edit' => $this->input->post('sbr_email_accounts_edit') == 'on' ? 1 : 0,
			'email_accounts_delete' => $this->input->post('sbr_email_accounts_delete') == 'on' ? 1 : 0,
			'bulk_sms_settings_view' => $this->input->post('sbr_bulk_sms_settings_view') == 'on' ? 1 : 0,
			'bulk_sms_settings_edit' => $this->input->post('sbr_bulk_sms_settings_edit') == 'on' ? 1 : 0,
			'prefixes_view' => $this->input->post('sbr_prefixes_view') == 'on' ? 1 : 0,
			'prefixes_edit' => $this->input->post('sbr_prefixes_edit') == 'on' ? 1 : 0,
			'void_reasons_view' => $this->input->post('sbr_void_reasons_view') == 'on' ? 1 : 0,
			'void_reasons_add' => $this->input->post('sbr_void_reasons_add') == 'on' ? 1 : 0,
			'void_reasons_edit' => $this->input->post('sbr_void_reasons_edit') == 'on' ? 1 : 0,
			'void_reasons_delete' => $this->input->post('sbr_void_reasons_delete') == 'on' ? 1 : 0,
			'bitly_settings_view' => $this->input->post('sbr_bitly_settings_view') == 'on' ? 1 : 0,
			'bitly_settings_edit' => $this->input->post('sbr_bitly_settings_edit') == 'on' ? 1 : 0,
			'products_view' => $this->input->post('sbr_products_view') == 'on' ? 1 : 0,
			'products_add' => $this->input->post('sbr_products_add') == 'on' ? 1 : 0,
			'products_edit' => $this->input->post('sbr_products_edit') == 'on' ? 1 : 0,
			'products_delete' => $this->input->post('sbr_products_delete') == 'on' ? 1 : 0,
			'product_categories_view' => $this->input->post('sbr_product_categories_view') == 'on' ? 1 : 0,
			'product_categories_add' => $this->input->post('sbr_product_categories_add') == 'on' ? 1 : 0,
			'product_categories_edit' => $this->input->post('sbr_product_categories_edit') == 'on' ? 1 : 0,
			'product_categories_delete' => $this->input->post('sbr_product_categories_delete') == 'on' ? 1 : 0,
			'units_of_measure_view' => $this->input->post('sbr_units_of_measure_view') == 'on' ? 1 : 0,
			'units_of_measure_add' => $this->input->post('sbr_units_of_measure_add') == 'on' ? 1 : 0,
			'units_of_measure_edit' => $this->input->post('sbr_units_of_measure_edit') == 'on' ? 1 : 0,
			'units_of_measure_delete' => $this->input->post('sbr_units_of_measure_delete') == 'on' ? 1 : 0,
			'product_sizes_view' => $this->input->post('sbr_product_sizes_view') == 'on' ? 1 : 0,
			'product_sizes_add' => $this->input->post('sbr_product_sizes_add') == 'on' ? 1 : 0,
			'product_sizes_edit' => $this->input->post('sbr_product_sizes_edit') == 'on' ? 1 : 0,
			'product_sizes_delete' => $this->input->post('sbr_product_sizes_delete') == 'on' ? 1 : 0,
			'brands_view' => $this->input->post('sbr_brands_view') == 'on' ? 1 : 0,
			'brands_add' => $this->input->post('sbr_brands_add') == 'on' ? 1 : 0,
			'brands_edit' => $this->input->post('sbr_brands_edit') == 'on' ? 1 : 0,
			'brands_delete' => $this->input->post('sbr_brands_delete') == 'on' ? 1 : 0,
			'purchase_orders_view' => $this->input->post('sbr_purchase_orders_view') == 'on' ? 1 : 0,
			'purchase_orders_add' => $this->input->post('sbr_purchase_orders_add') == 'on' ? 1 : 0,
			'purchase_orders_edit' => $this->input->post('sbr_purchase_orders_edit') == 'on' ? 1 : 0,
			'purchase_orders_delete' => $this->input->post('sbr_purchase_orders_delete') == 'on' ? 1 : 0,
			'purchase_orders_print' => $this->input->post('sbr_purchase_orders_print') == 'on' ? 1 : 0,
			'goods_received_view' => $this->input->post('sbr_goods_received_view') == 'on' ? 1 : 0,
			'goods_received_add' => $this->input->post('sbr_goods_received_add') == 'on' ? 1 : 0,
			'goods_received_edit' => $this->input->post('sbr_goods_received_edit') == 'on' ? 1 : 0,
			'goods_received_delete' => $this->input->post('sbr_goods_received_delete') == 'on' ? 1 : 0,
			'goods_received_print' => $this->input->post('sbr_goods_received_print') == 'on' ? 1 : 0,
			'stock_adjustments_view' => $this->input->post('sbr_stock_adjustments_view') == 'on' ? 1 : 0,
			'stock_adjustments_add' => $this->input->post('sbr_stock_adjustments_add') == 'on' ? 1 : 0,
			'stock_adjustments_edit' => $this->input->post('sbr_stock_adjustments_edit') == 'on' ? 1 : 0,
			'stock_adjustments_delete' => $this->input->post('sbr_stock_adjustments_delete') == 'on' ? 1 : 0,
			'stock_adjustments_print' => $this->input->post('sbr_stock_adjustments_print') == 'on' ? 1 : 0,
			'goods_returned_view' => $this->input->post('sbr_goods_returned_view') == 'on' ? 1 : 0,
			'goods_returned_add' => $this->input->post('sbr_goods_returned_add') == 'on' ? 1 : 0,
			'goods_returned_edit' => $this->input->post('sbr_goods_returned_edit') == 'on' ? 1 : 0,
			'goods_returned_delete' => $this->input->post('sbr_goods_returned_delete') == 'on' ? 1 : 0,
			'goods_returned_print' => $this->input->post('sbr_goods_returned_print') == 'on' ? 1 : 0,
			'stock_transfers_view' => $this->input->post('sbr_stock_transfers_view') == 'on' ? 1 : 0,
			'stock_transfers_add' => $this->input->post('sbr_stock_transfers_add') == 'on' ? 1 : 0,
			'stock_transfers_edit' => $this->input->post('sbr_stock_transfers_edit') == 'on' ? 1 : 0,
			'stock_transfers_delete' => $this->input->post('sbr_stock_transfers_delete') == 'on' ? 1 : 0,
			'stock_transfers_print' => $this->input->post('sbr_stock_transfers_print') == 'on' ? 1 : 0,
			'online_sales_view' => $this->input->post('sbr_online_sales_view') == 'on' ? 1 : 0,
			'online_sales_print' => $this->input->post('sbr_online_sales_print') == 'on' ? 1 : 0,
			'online_sales_manage' => $this->input->post('sbr_online_sales_manage') == 'on' ? 1 : 0,
			'paybill_payments_view' => $this->input->post('sbr_paybill_payments_view') == 'on' ? 1 : 0,
			'paybill_payments_print' => $this->input->post('sbr_paybill_payments_print') == 'on' ? 1 : 0,
			'paybill_payments_manage' => $this->input->post('sbr_paybill_payments_manage') == 'on' ? 1 : 0,
			'customers_view' => $this->input->post('sbr_customers_view') == 'on' ? 1 : 0,
			'customers_add' => $this->input->post('sbr_customers_add') == 'on' ? 1 : 0,
			'customers_edit' => $this->input->post('sbr_customers_edit') == 'on' ? 1 : 0,
			'customers_delete' => $this->input->post('sbr_customers_delete') == 'on' ? 1 : 0,
			'customer_groups_view' => $this->input->post('sbr_customer_groups_view') == 'on' ? 1 : 0,
			'customer_groups_add' => $this->input->post('sbr_customer_groups_add') == 'on' ? 1 : 0,
			'customer_groups_edit' => $this->input->post('sbr_customer_groups_edit') == 'on' ? 1 : 0,
			'customer_groups_delete' => $this->input->post('sbr_customer_groups_delete') == 'on' ? 1 : 0,
			'suppliers_view' => $this->input->post('sbr_suppliers_view') == 'on' ? 1 : 0,
			'suppliers_add' => $this->input->post('sbr_suppliers_add') == 'on' ? 1 : 0,
			'suppliers_edit' => $this->input->post('sbr_suppliers_edit') == 'on' ? 1 : 0,
			'suppliers_delete' => $this->input->post('sbr_suppliers_delete') == 'on' ? 1 : 0,
			'affiliate_accounts_view' => $this->input->post('sbr_affiliate_accounts_view') == 'on' ? 1 : 0,
			'affiliate_accounts_delete' => $this->input->post('sbr_affiliate_accounts_delete') == 'on' ? 1 : 0,
			'affiliate_accounts_manage' => $this->input->post('sbr_affiliate_accounts_manage') == 'on' ? 1 : 0,
			'affiliate_accounts_print' => $this->input->post('sbr_affiliate_accounts_print') == 'on' ? 1 : 0,
			'affiliate_packages_view' => $this->input->post('sbr_affiliate_packages_view') == 'on' ? 1 : 0,
			'affiliate_packages_add' => $this->input->post('sbr_affiliate_packages_add') == 'on' ? 1 : 0,
			'affiliate_packages_edit' => $this->input->post('sbr_affiliate_packages_edit') == 'on' ? 1 : 0,
			'affiliate_packages_delete' => $this->input->post('sbr_affiliate_packages_delete') == 'on' ? 1 : 0,
			'affiliate_terms_view' => $this->input->post('sbr_affiliate_terms_view') == 'on' ? 1 : 0,
			'affiliate_terms_edit' => $this->input->post('sbr_affiliate_terms_edit') == 'on' ? 1 : 0,
			'promo_codes_view' => $this->input->post('sbr_promo_codes_view') == 'on' ? 1 : 0,
			'promo_codes_add' => $this->input->post('sbr_promo_codes_add') == 'on' ? 1 : 0,
			'promo_codes_edit' => $this->input->post('sbr_promo_codes_edit') == 'on' ? 1 : 0,
			'promo_codes_delete' => $this->input->post('sbr_promo_codes_delete') == 'on' ? 1 : 0,
			'home_sliders_view' => $this->input->post('sbr_home_sliders_view') == 'on' ? 1 : 0,
			'home_sliders_add' => $this->input->post('sbr_home_sliders_add') == 'on' ? 1 : 0,
			'home_sliders_edit' => $this->input->post('sbr_home_sliders_edit') == 'on' ? 1 : 0,
			'home_sliders_delete' => $this->input->post('sbr_home_sliders_delete') == 'on' ? 1 : 0,
			'top_categories_view' => $this->input->post('sbr_top_categories_view') == 'on' ? 1 : 0,
			'top_categories_add' => $this->input->post('sbr_top_categories_add') == 'on' ? 1 : 0,
			'top_categories_edit' => $this->input->post('sbr_top_categories_edit') == 'on' ? 1 : 0,
			'top_categories_delete' => $this->input->post('sbr_top_categories_delete') == 'on' ? 1 : 0,
			'featured_categories_view' => $this->input->post('sbr_featured_categories_view') == 'on' ? 1 : 0,
			'featured_categories_edit' => $this->input->post('sbr_featured_categories_edit') == 'on' ? 1 : 0,
			'promo_banners_view' => $this->input->post('sbr_promo_banners_view') == 'on' ? 1 : 0,
			'promo_banners_add' => $this->input->post('sbr_promo_banners_add') == 'on' ? 1 : 0,
			'promo_banners_edit' => $this->input->post('sbr_promo_banners_edit') == 'on' ? 1 : 0,
			'promo_banners_delete' => $this->input->post('sbr_promo_banners_delete') == 'on' ? 1 : 0,
			'testimonials_view' => $this->input->post('sbr_testimonials_view') == 'on' ? 1 : 0,
			'testimonials_add' => $this->input->post('sbr_testimonials_add') == 'on' ? 1 : 0,
			'testimonials_edit' => $this->input->post('sbr_testimonials_edit') == 'on' ? 1 : 0,
			'testimonials_delete' => $this->input->post('sbr_testimonials_delete') == 'on' ? 1 : 0,
			'about_us_view' => $this->input->post('sbr_about_us_view') == 'on' ? 1 : 0,
			'about_us_edit' => $this->input->post('sbr_about_us_edit') == 'on' ? 1 : 0,
			'how_to_shop_view' => $this->input->post('sbr_how_to_shop_view') == 'on' ? 1 : 0,
			'how_to_shop_edit' => $this->input->post('sbr_how_to_shop_edit') == 'on' ? 1 : 0,
			'faqs_view' => $this->input->post('sbr_faqs_view') == 'on' ? 1 : 0,
			'faqs_add' => $this->input->post('sbr_faqs_add') == 'on' ? 1 : 0,
			'faqs_edit' => $this->input->post('sbr_faqs_edit') == 'on' ? 1 : 0,
			'faqs_delete' => $this->input->post('sbr_faqs_delete') == 'on' ? 1 : 0,
			'return_policy_view' => $this->input->post('sbr_return_policy_view') == 'on' ? 1 : 0,
			'return_policy_edit' => $this->input->post('sbr_return_policy_edit') == 'on' ? 1 : 0,
			'privacy_policy_view' => $this->input->post('sbr_privacy_policy_view') == 'on' ? 1 : 0,
			'privacy_policy_edit' => $this->input->post('sbr_privacy_policy_edit') == 'on' ? 1 : 0,
			'terms_and_conditions_view' => $this->input->post('sbr_terms_and_conditions_view') == 'on' ? 1 : 0,
			'terms_and_conditions_edit' => $this->input->post('sbr_terms_and_conditions_edit') == 'on' ? 1 : 0,
			'blog_view' => $this->input->post('sbr_blog_view') == 'on' ? 1 : 0,
			'blog_add' => $this->input->post('sbr_blog_add') == 'on' ? 1 : 0,
			'blog_edit' => $this->input->post('sbr_blog_edit') == 'on' ? 1 : 0,
			'blog_delete' => $this->input->post('sbr_blog_delete') == 'on' ? 1 : 0,
			'blog_categories_view' => $this->input->post('sbr_blog_categories_view') == 'on' ? 1 : 0,
			'blog_categories_add' => $this->input->post('sbr_blog_categories_add') == 'on' ? 1 : 0,
			'blog_categories_edit' => $this->input->post('sbr_blog_categories_edit') == 'on' ? 1 : 0,
			'blog_categories_delete' => $this->input->post('sbr_blog_categories_delete') == 'on' ? 1 : 0,
			'system_users_view' => $this->input->post('sbr_system_users_view') == 'on' ? 1 : 0,
			'system_users_add' => $this->input->post('sbr_system_users_add') == 'on' ? 1 : 0,
			'system_users_edit' => $this->input->post('sbr_system_users_edit') == 'on' ? 1 : 0,
			'system_users_delete' => $this->input->post('sbr_system_users_delete') == 'on' ? 1 : 0,
			'user_roles_view' => $this->input->post('sbr_user_roles_view') == 'on' ? 1 : 0,
			'user_roles_add' => $this->input->post('sbr_user_roles_add') == 'on' ? 1 : 0,
			'user_roles_edit' => $this->input->post('sbr_user_roles_edit') == 'on' ? 1 : 0,
			'user_roles_delete' => $this->input->post('sbr_user_roles_delete') == 'on' ? 1 : 0,
			'departments_view' => $this->input->post('sbr_departments_view') == 'on' ? 1 : 0,
			'departments_add' => $this->input->post('sbr_departments_add') == 'on' ? 1 : 0,
			'departments_edit' => $this->input->post('sbr_departments_edit') == 'on' ? 1 : 0,
			'departments_delete' => $this->input->post('sbr_departments_delete') == 'on' ? 1 : 0
		);

		$this->db->where(array('user_role_id' => $user_role_id));
		$query = $this->db->get('system_user_rights');
		if ($query->num_rows() > 0){
			$this->db->where( array('user_role_id'=>$user_role_id));
			$this->db->update('system_user_rights', $data);
		}else{
			$this->db->insert('system_user_rights', $data);
		}
	}

	function delete($user_role_id){
		$data = array(
			'is_deleted'=> 1
		);				
		$this->db->where( array('user_role_id'=>$user_role_id));
		$delupdate = $this->db->update('user_roles', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'User Role deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => 'Error deleting User Role');
		}
		return $arr_return;
	}

}