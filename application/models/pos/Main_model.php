<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Main_model extends CI_Model{
    public function __construct(){
        parent::__construct();
        // $this->load->library('flexi_cart');
        // $this->load->library('flexi_cart_admin');
        $this->load->model('be/currencies_model');
        $this->load->library('phpmailer');
    }

    //STORE INFORMATION
    function get_store_information(){
        $this->db->from('store_information');
        return $this->db->get()->result();
    }

    //EMAIL ACCOUNTS
    function get_email_accounts(){
        $this->db->select('email_accounts.*,');
        $this->db->from('email_accounts');
        $this->db->where( array('email_accounts.is_deleted'=>0, 'email_accounts.is_active'=>1));
        return $this->db->get()->result();
    }

    function get_email_account($email_account_id) {
        $this->db->select('email_accounts.*,');
        $this->db->from('email_accounts');
        $this->db->where( array('email_accounts.email_account_id'=> $email_account_id));
        return $this->db->get()->result();

    }

    function get_active_outlet() {
    	$this->db->from('outlets');
		$this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id')));
		return $this->db->get()->result();
    }

    function get_mpesa_settings(){
        $this->db->from('mpesa_settings');
        return $this->db->get()->result();
    }

    function get_credit_terms(){
        $this->db->select('credit_terms.*,');
        $this->db->from('credit_terms');
        $this->db->where( array('credit_terms.is_deleted'=>0, 'credit_terms.is_active'=>1));
        return $this->db->get()->result();
    }

    //NESTED CATEGORIES
    function get_nested_product_categories(){
        $this->db->select('product_categories.*, icons.icon_name');
        $this->db->from('product_categories');
        $this->db->join('icons', 'icons.icon_id = product_categories.icon_id', 'left outer');
        $this->db->where( array('product_categories.is_deleted'=>0, 'product_categories.product_category_parent_id'=>0));
        $this->db->order_by("product_categories.sort_key", "asc");
        
        $parent_category = $this->db->get();
        
        $product_categories = $parent_category->result();
        $i = 0;
        foreach($product_categories as $p_cat){
            $product_categories[$i]->sub = $this->product_sub_categories($p_cat->product_category_id);
            $i++;
        }
        return $product_categories;
    }

    function product_sub_categories($id){
        $this->db->select('product_categories.*, icons.icon_name');
        $this->db->from('product_categories');
        $this->db->join('icons', 'icons.icon_id = product_categories.icon_id', 'left outer');
        $this->db->where( array('product_categories.is_deleted'=>0, 'product_categories.product_category_parent_id'=>$id));
        $this->db->order_by("product_categories.sort_key", "asc");

        $child_category = $this->db->get();
        $product_categories = $child_category->result();
        $i=0;
        foreach($product_categories as $p_cat){
            $product_categories[$i]->sub = $this->product_sub_categories($p_cat->product_category_id);
            $i++;
        }
        return $product_categories;       
    }

    //SALES LIST
    function get_sales_list() {

        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

        $this->db->select("ps.*, c.first_name, c.last_name, c.email_address, c.phone_number, c.credit_limit, c.opening_balance, c.loyalty_enrolled, c.loyalty_number, c.loyalty_enrollment_date, c.profile_picture, c.profile_picture_thumb, su.first_name AS 'system_user_first_name', su.last_name AS 'system_user_last_name', ct.credit_term");
        $this->db->from('pos_sales ps');     
        $this->db->join('customers c', 'c.customer_id = ps.customer_id', 'left outer');  
        $this->db->join('system_users su', 'su.system_user_id = ps.system_user_id', 'left outer');
        $this->db->join('credit_terms ct', 'ct.credit_term_id = ps.credit_term_id', 'left outer');  

        $this->db->where( array('ps.is_held' => 0, 'ps.is_completed' => 1, 'ps.outlet_id' => $this->session->userdata('pos_outlet_id')));

        if ($this->session->userdata('pos_user_is_super_admin') != 1) {
            $this->db->where( array('ps.system_user_id' => $this->session->userdata('pos_system_user_id')));
        }

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(ps.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(ps.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }

        $this->db->order_by("ps.pos_sale_id", "desc");
        
        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        return $records;
    }

    //SALES HOLD LIST
    function get_sales_hold_list(){
        $this->db->select("ps.*, c.first_name, c.last_name, c.email_address, c.phone_number, c.credit_limit, c.opening_balance, c.loyalty_enrolled, c.loyalty_number, c.loyalty_enrollment_date, c.profile_picture, c.profile_picture_thumb, su.first_name AS 'system_user_first_name', su.last_name AS 'system_user_last_name', ct.credit_term");
        $this->db->from('pos_sales ps');     
        $this->db->join('customers c', 'c.customer_id = ps.customer_id', 'left outer');  
        $this->db->join('system_users su', 'su.system_user_id = ps.system_user_id', 'left outer');  
        $this->db->join('credit_terms ct', 'ct.credit_term_id = ps.credit_term_id', 'left outer');

        $this->db->where( array('ps.is_void' => 0, 'ps.is_held' => 1, 'ps.outlet_id' => $this->session->userdata('pos_outlet_id')));

        if ($this->session->userdata('pos_user_is_super_admin') != 1) {
            $this->db->where( array('ps.system_user_id' => $this->session->userdata('pos_system_user_id')));
        }
        $this->db->order_by("ps.pos_sale_id", "desc");
        
        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        $i=0;
        foreach ($records as $row) {
            $records[$i]->details = $this->get_pos_sale_details($row->pos_sale_id);
            $i++;
        }

        return $records;
    }

    //SALE PRODUCTS
    function get_sale_products(){

        $product_category_id = $this->input->post('product_category_id');

        $this->db->select("p.*, op.outlet_product_id, op.outlet_id, op.product_variation_id, op.opening_stock, op.available_stock, op.reorder_level, op.regular_price as 'outlet_regular_price', op.sale_price as 'outlet_sale_price', op.minimum_selling_price as 'outlet_minimum_selling_price', u.unit_name, u.unit_code, b.brand_name");
        $this->db->from('products p');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');
        $this->db->join('product_product_categories ppca', 'ppca.product_id = p.product_id');
        $this->db->join('outlet_products op', 'op.product_id = p.product_id');

        if ($product_category_id == '' || $product_category_id == 'All'){
            
        } else {
            $this->db->where( array('ppca.product_category_id'=>$product_category_id));
        }

        $this->db->where( array('op.outlet_id' => $this->session->userdata('pos_outlet_id')));
        
        $this->db->where( array('p.is_deleted'=>0, 'p.is_online'=>1));

        $this->db->group_by('p.product_id');

        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        $i=0;
        foreach ($records as $row) {
            $records[$i]->units = $this->get_product_related_units($row->product_id);
            $i++;
        }

        $arr_return = array('records' => $records, 'record_count' => $record_count);

        return $arr_return;
    }

    function get_sale_product_search_results($search_keyword){

        $this->db->select("p.*, op.*, b.brand_name");
        $this->db->from('products p');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        // $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');
        $this->db->join('outlet_products op', 'op.product_id = p.product_id');

        $this->db->where( array('op.outlet_id' => $this->session->userdata('pos_outlet_id')));
        
        $this->db->where( array('p.is_deleted'=>0, 'p.is_online'=>1));

        $this->db->like('p.product_name',$search_keyword);

        $this->db->group_by('p.product_id');

        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        $i=0;
        foreach ($records as $row) {
            $records[$i]->units = $this->get_product_related_units($row->product_id);
            $i++;
        }

        $arr_return = array('records' => $records, 'record_count' => $record_count);

        return $arr_return;
    }

    function get_product_related_units($product_id){
        $this->db->from('product_related_units');
        $this->db->where( array('product_id'=>$product_id));
        return $this->db->get()->result();
    }

    function get_num_sale_product_search_results($search_keyword){
        $this->db->select("p.*, op.*, b.brand_name");
        $this->db->from('products p');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        // $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');
        $this->db->join('outlet_products op', 'op.product_id = p.product_id');

        $this->db->where( array('op.outlet_id' => $this->session->userdata('pos_outlet_id')));
        
        $this->db->where( array('p.is_deleted'=>0, 'p.is_online'=>1));

        $this->db->like('p.product_name',$search_keyword);

        // $this->db->group_by('p.product_id');

        return $this->db->count_all_results();
    }

    function get_sale_customer_search_results($search_keyword){

        $this->db->select("c.*");
        $this->db->from('customers c');
       
        $this->db->where( array('c.is_deleted'=>0, 'c.is_active'=>1));
        $this->db->group_start()
        ->like('c.first_name',$search_keyword)
        ->or_like('c.last_name',$search_keyword)
        ->or_like('c.email_address',$search_keyword)
        ->or_like('c.phone_number',$search_keyword)
        ->group_end();

        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        $arr_return = array('records' => $records, 'record_count' => $record_count);

        return $arr_return;
    }

    function get_num_sale_customer_search_results($search_keyword){
        $this->db->select("c.*");
        $this->db->from('customers c');
       
        $this->db->where( array('c.is_deleted'=>0, 'c.is_active'=>1));
        $this->db->group_start()
        ->like('c.first_name',$search_keyword)
        ->or_like('c.last_name',$search_keyword)
        ->or_like('c.email_address',$search_keyword)
        ->or_like('c.phone_number',$search_keyword)
        ->group_end();
        
        return $this->db->count_all_results();
    }


    //GET CUSTOMER
    function get_customer($customer_id){
        $this->db->from('customers');
        $this->db->where( array('customer_id'=>$customer_id));
        return $this->db->get()->result();
    }

    //GET PRODUCT
    function get_product($product_id){

        $this->db->select("p.*, op.outlet_product_id, op.outlet_id, op.product_variation_id, op.opening_stock, op.available_stock, op.reorder_level, op.regular_price as 'outlet_regular_price', op.sale_price as 'outlet_sale_price', op.minimum_selling_price as 'outlet_minimum_selling_price', u.unit_code, u.unit_name, b.brand_reference_id, b.brand_name, tr.tax_rate_name, tr.tax_rate_code, tr.tax_rate_value");
        $this->db->from('products p');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');
        $this->db->join('tax_rates tr', 'tr.tax_rate_id = p.tax_rate_id', 'left outer');
        $this->db->join('outlet_products op', 'op.product_id = p.product_id', 'left outer');

       $this->db->where( array('p.product_id'=>$product_id));
       $this->db->where( array('op.outlet_id' => $this->session->userdata('pos_outlet_id'), 'op.product_variation_id' => 0));

        $product = $this->db->get()->result();

        $i = 0;
        foreach ($product as $row) {
            $product[$i]->units = $this->get_product_units($row->product_id);
            $i++;
        }

        return $product;
    }

    function get_product_variations($product_id){
        $this->db->select("pv.*, op.outlet_product_id, op.outlet_id, op.product_variation_id, op.opening_stock, op.available_stock, op.reorder_level, op.regular_price as 'outlet_regular_price', op.sale_price as 'outlet_sale_price', op.minimum_selling_price as 'outlet_minimum_selling_price'");
        $this->db->from('product_variations pv');
        $this->db->join('outlet_products op', 'op.product_variation_id = pv.product_variation_id', 'left outer');

        $this->db->where( array('pv.product_id'=>$product_id, 'pv.is_deleted'=>0));
        $this->db->where( array('op.outlet_id' => $this->session->userdata('pos_outlet_id')));
        // $this->db->from('product_variations');
        // $this->db->where( array('product_id'=>$product_id, 'is_deleted'=>0));

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

    function get_product_variation($product_variation_id){

        $this->db->select("pv.*, op.outlet_product_id, op.outlet_id, op.product_variation_id, op.opening_stock, op.available_stock, op.reorder_level, op.regular_price as 'outlet_regular_price', op.sale_price as 'outlet_sale_price', op.minimum_selling_price as 'outlet_minimum_selling_price'");
        $this->db->from('product_variations pv');
        $this->db->join('outlet_products op', 'op.product_variation_id = pv.product_variation_id', 'left outer');

        $this->db->where( array('pv.product_variation_id'=>$product_variation_id));
        $this->db->where( array('op.outlet_id' => $this->session->userdata('pos_outlet_id')));

        // $this->db->from('product_variations');
        // $this->db->where( array('product_variation_id' => $product_variation_id));

        $product_variations = $this->db->get()->result();

        $i = 0;
        foreach($product_variations as $row){
            $product_variations[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $i++;
        }
        return $product_variations;
    }


    function get_pending_paybill_payments(){
        $this->db->select("*");
        $this->db->from('paybill_payments');       
        $this->db->where( array('transaction_completed' => 0));
        return $this->db->get()->result();
    }

    //////SALE FUNCTIONS

    //GET PENDING SALE
    function get_pending_sale() {
        $this->db->select("ps.*, c.first_name, c.last_name, c.email_address, c.phone_number, c.credit_limit, c.opening_balance, c.loyalty_enrolled, c.loyalty_number, c.loyalty_enrollment_date, c.profile_picture, c.profile_picture_thumb, ct.credit_term");
        $this->db->from('pos_sales ps');     
        $this->db->join('customers c', 'c.customer_id = ps.customer_id', 'left outer');  
        $this->db->join('credit_terms ct', 'ct.credit_term_id = ps.credit_term_id', 'left outer');
        $this->db->where( array('ps.is_void' => 0, 'ps.is_held' => 0, 'ps.is_completed' => 0, 'ps.system_user_id' => $this->session->userdata('pos_system_user_id'), 'ps.outlet_id' => $this->session->userdata('pos_outlet_id')));
        $this->db->order_by("ps.pos_sale_id", "desc");
        $this->db->limit(1);
        
        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        //$product_categories = $parent_category->result();
        $i = 0;
        foreach($records as $row){
            $records[$i]->details = $this->get_pos_sale_details($row->pos_sale_id);
            $records[$i]->payments = $this->get_pos_sale_payments($row->pos_sale_id);
            $i++;
        }

        $arr_return = array('records' => $records, 'record_count' => $record_count);

        return $arr_return;

    }

    function get_pos_sale_details($pos_sale_id) {
        $this->db->select("psd.*, ps.outlet_id, p.product_name, p.product_sku_code, p.product_reference_id, p.product_barcode, p.product_image, p.product_image_thumb, tr.tax_rate_name, tr.tax_rate_code, tr.tax_rate_value, u.unit_code, u.unit_name");
        $this->db->from('pos_sale_details psd');     
        $this->db->join('units u', 'u.unit_id = psd.unit_id', 'left outer');
        $this->db->join('tax_rates tr', 'tr.tax_rate_id = psd.tax_rate_id', 'left outer');
        $this->db->join('products p', 'p.product_id = psd.product_id');
        $this->db->join('pos_sales ps', 'ps.pos_sale_id = psd.pos_sale_id'); 
        $this->db->where( array('psd.pos_sale_id' => $pos_sale_id)); 

        $pos_sale_details = $this->db->get()->result();

        $i = 0;
        foreach($pos_sale_details as $row){
            $pos_sale_details[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $i++;
        }
        return $pos_sale_details;

        // return $this->db->get()->result();
    }
    function get_pos_sale_tax_details($pos_sale_id){
        $this->db->select("psd.tax_rate_id, tr.tax_rate_code, tr.tax_rate_value, (SELECT COALESCE(SUM(psd2.price_excl_tax * psd2.quantity),0) FROM pos_sale_details psd2 WHERE psd2.pos_sale_id = psd.pos_sale_id AND psd2.tax_rate_id = psd.tax_rate_id) AS 'vatable_amount', (SELECT COALESCE(SUM(psd3.unit_tax * psd3.quantity),0) FROM pos_sale_details psd3 WHERE psd3.pos_sale_id = psd.pos_sale_id AND psd3.tax_rate_id = psd.tax_rate_id) AS 'vat_amount'");
        $this->db->from('pos_sale_details psd');     
        $this->db->join('tax_rates tr', 'tr.tax_rate_id = psd.tax_rate_id');
        $this->db->join('pos_sales ps', 'ps.pos_sale_id = psd.pos_sale_id'); 
        $this->db->group_by('psd.tax_rate_id');
        $this->db->where( array('psd.pos_sale_id' => $pos_sale_id)); 

        return $this->db->get()->result();
    }
    function get_product_variation_attributes($product_variation_id) {
        $this->db->select('pva.*, pa.product_attribute_name, pav.product_attribute_value');
        $this->db->from('product_variation_attributes pva');
        $this->db->join('product_attributes pa', 'pa.product_attribute_id = pva.product_attribute_id', 'left outer');
        $this->db->join('product_attribute_values pav', 'pav.product_attribute_value_id = pva.product_attribute_value_id', 'left outer');

        $this->db->where( array('pva.product_variation_id' => $product_variation_id, 'pva.is_deleted'=>0));
        return $this->db->get()->result();
    }
    function get_num_pos_sale_details($pos_sale_id) {
        $this->db->select("psd.*, p.product_name, p.product_sku_code, p.product_reference_id, p.product_barcode, p.product_image, p.product_image_thumb, tr.tax_rate_name, tr.tax_rate_code, tr.tax_rate_value");
        $this->db->from('pos_sale_details psd');     
        // $this->db->join('units u', 'u.unit_id = psd.unit_id', 'left outer');
        $this->db->join('tax_rates tr', 'tr.tax_rate_id = psd.tax_rate_id', 'left outer');
        $this->db->join('products p', 'p.product_id = psd.product_id');
        $this->db->join('pos_sales ps', 'ps.pos_sale_id = psd.pos_sale_id'); 
        $this->db->where( array('psd.pos_sale_id' => $pos_sale_id)); 

        return $this->db->count_all_results();

    }
    function get_pos_sale_payments($pos_sale_id) {
        $this->db->select("pp.*");
        $this->db->from('pos_payments pp');     
        $this->db->where( array('pp.pos_sale_id' => $pos_sale_id, 'pp.is_void' => 0));

        return $this->db->get()->result();
    }
    function get_num_pos_sale_payments($pos_sale_id) {
        $this->db->select("pp.*");
        $this->db->from('pos_payments pp');     
        $this->db->where( array('pp.pos_sale_id' => $pos_sale_id, 'pp.is_void' => 0));

        return $this->db->count_all_results();
    }

    function get_pos_sale_detail($pos_sale_detail_id){
        $this->db->select("psd.*, u.unit_code, u.unit_name, ps.outlet_id, p.product_name, p.product_sku_code, p.product_reference_id, p.product_barcode, p.product_image, p.product_image_thumb, tr.tax_rate_name, tr.tax_rate_code, tr.tax_rate_value");
        $this->db->from('pos_sale_details psd');     
        $this->db->join('units u', 'u.unit_id = psd.unit_id', 'left outer');
        $this->db->join('tax_rates tr', 'tr.tax_rate_id = psd.tax_rate_id', 'left outer');
        $this->db->join('products p', 'p.product_id = psd.product_id');
        $this->db->join('pos_sales ps', 'ps.pos_sale_id = psd.pos_sale_id');  
        $this->db->where( array('psd.pos_sale_detail_id' => $pos_sale_detail_id));

        $pos_sale_details = $this->db->get()->result();

        $i = 0;
        foreach($pos_sale_details as $row){
            $pos_sale_details[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $i++;
        }
        return $pos_sale_details;
    }

    function get_product_units($product_id) {
        $query = $this->db->query("select p.product_id, p.product_name, p.regular_price, u.unit_id, u.unit_code, u.unit_name, pru.unit_price, pru.conversion_factor, pru.universal_prices, pru.outlet_unit_price from products p join units u on p.unit_id = u.unit_id left outer join (SELECT product_related_units.unit_price, product_related_units.conversion_factor, product_related_units.related_unit_id, product_related_units.universal_prices, pruop.unit_price as 'outlet_unit_price' FROM product_related_units left outer join product_related_units_outlet_prices pruop on product_related_units.unit_related_id = pruop.unit_related_id where product_related_units.product_id = " . $product_id . " AND pruop.outlet_id = " . $this->session->userdata('pos_outlet_id') . ") pru on pru.related_unit_id = u.unit_id where u.is_deleted = 0 and p.product_id = " . $product_id . " UNION SELECT p.product_id, p.product_name, p.regular_price, u.unit_id, u.unit_code, u.unit_name, pru.unit_price, pru.conversion_factor, pru.universal_prices, pruop.unit_price as 'outlet_unit_price' from product_related_units pru JOIN products p on p.product_id = pru.product_id join units u on u.unit_id = pru.related_unit_id left outer join product_related_units_outlet_prices pruop on pru.unit_related_id = pruop.unit_related_id where pru.product_id = " . $product_id . " AND pruop.outlet_id = " . $this->session->userdata('pos_outlet_id'));
        return $query->result();
    }

    function get_product_related_unit($product_id,$unit_id) {
        $this->db->select("*");
        $this->db->from('product_related_units');     
        $this->db->where( array('product_id' => $product_id, 'related_unit_id' => $unit_id));
        return $this->db->get()->result();
    }

    function get_pos_sale($pos_sale_id) {
        $this->db->select("ps.*, c.first_name, c.last_name, c.email_address, c.phone_number, c.credit_limit, c.opening_balance, c.loyalty_enrolled, c.loyalty_number, c.loyalty_enrollment_date, c.profile_picture, c.profile_picture_thumb, su.first_name AS 'system_user_first_name', su.last_name AS 'system_user_last_name', ct.credit_term");
        $this->db->from('pos_sales ps');     
        $this->db->join('customers c', 'c.customer_id = ps.customer_id', 'left outer'); 
        $this->db->join('system_users su', 'su.system_user_id = ps.system_user_id', 'left outer'); 
        $this->db->join('credit_terms ct', 'ct.credit_term_id = ps.credit_term_id', 'left outer');
        $this->db->where( array('ps.pos_sale_id' => $pos_sale_id));

        return $this->db->get()->result();

    }

    //SAVE SALE CUSTOMER
    function save_sale_customer($customer_id){

        $this->db->select("*");
        $this->db->from('pos_sales');       
        $this->db->where( array('is_void' => 0, 'is_held' => 0, 'is_completed' => 0, 'system_user_id' => $this->session->userdata('pos_system_user_id'), 'outlet_id' => $this->session->userdata('pos_outlet_id')));
        $this->db->order_by("pos_sale_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {
            $data = array(
                'customer_id' => $customer_id,
                'system_user_id' =>  $this->session->userdata('pos_system_user_id'),
                'outlet_id' => $this->session->userdata('pos_outlet_id')
            );
            $records = $query->result();
            $pos_sale_id = 0;
            foreach ($records as $row) {
                $pos_sale_id = $row->pos_sale_id;
            }
            $this->db->where(array('pos_sale_id' => $pos_sale_id));
            $res = $this->db->update('pos_sales', $data);

            if ($res){
                $arr_return = array('res' => true,'dt' => 'Sale updated successfully.','pos_sale_id' => $pos_sale_id);
            } else {
                $arr_return = array('res' => false,'dt' => 'Could not update sale successfully. Please try again.','pos_sale_id' => $pos_sale_id);
            }

        } else {
            $comments = '';
            $this->db->select("*");
            $this->db->from('sale_comments');
            $sale_comments = $this->db->get()->result();

            foreach ($sale_comments as $row) {
                $comments = $row->cash_comments;
            }
            
            $data = array(
                'sale_type' => 'CASH SALE',
                'sale_date' => date('Y-m-d'),
                'customer_id' => $customer_id,
                'system_user_id' =>  $this->session->userdata('pos_system_user_id'),
                'outlet_id' => $this->session->userdata('pos_outlet_id'),
                'comments' => $comments
            );

            $res = $this->db->insert('pos_sales', $data);
            $pos_sale_id = $this->db->insert_id();
            if ($res){

                //UPDATE POS SALE NUMBER
                $sale_prefix = '';
                $pos_sale_number = '';
                $this->db->select("*");
                $this->db->from('prefixes'); 
                $this->db->where( array('document_name' => 'Sales Order'));
                $prefix = $this->db->get()->result();

                foreach ($prefix as $row) {
                    $sale_prefix = $row->prefix_name;
                }
                if ($sale_prefix != '') {
                    $pos_sale_number = $sale_prefix . '-' . $pos_sale_id;
                } else {
                    $pos_sale_number = $pos_sale_id;
                }
                $data = array(
                    'pos_sale_number' => $pos_sale_number
                );
                $this->db->where(array('pos_sale_id' => $pos_sale_id));
                $res = $this->db->update('pos_sales', $data);

                $arr_return = array('res' => true,'dt' => 'Sale updated successfully.','pos_sale_id' => $pos_sale_id);
            } else {
                $arr_return = array('res' => false,'dt' => 'Could not update sale successfully. Please try again.','pos_sale_id' => $pos_sale_id);
            }
        }

        return $arr_return;
    }

    function edit_sale_select_customer($customer_id) {
        $pos_sale_id = $this->input->post('pos_sale_id');

        $data = array(
            'customer_id' => $customer_id
        );

        $this->db->where(array('pos_sale_id' => $pos_sale_id));
        $res = $this->db->update('pos_sales', $data);

        if ($res){
            $arr_return = array('res' => true,'dt' => 'Customer information updated successfully.','pos_sale_id' => $pos_sale_id);
        } else {
            $arr_return = array('res' => false,'dt' => 'Could not update customer information successfully. Please try again.','pos_sale_id' => $pos_sale_id);
        }
        return $arr_return;
    }

    //DETATCH CUSTOMER
    function detatch_sale_customer($pos_sale_id) {
         $data = array(
            'customer_id' => 0
        );
        $this->db->where(array('pos_sale_id' => $pos_sale_id));
        $res = $this->db->update('pos_sales', $data);

        if ($res){
            $arr_return = array('res' => true,'dt' => 'Customer detatched successfully.','pos_sale_id' => $pos_sale_id);
        } else {
            $arr_return = array('res' => false,'dt' => 'Could not detatch customer successfully. Please try again.','pos_sale_id' => $pos_sale_id);
        }
        return $arr_return;
    }

    //REMOVE CUSTOMER NAME
    function remove_customer_name($pos_sale_id) {
         $data = array(
            'customer_name' => ''
        );
        $this->db->where(array('pos_sale_id' => $pos_sale_id));
        $res = $this->db->update('pos_sales', $data);

        if ($res){
            $arr_return = array('res' => true,'dt' => 'Customer name removed successfully.','pos_sale_id' => $pos_sale_id);
        } else {
            $arr_return = array('res' => false,'dt' => 'Could not remove customer name successfully. Please try again.','pos_sale_id' => $pos_sale_id);
        }
        return $arr_return;
    }

    //NEW SALE ADD PRODUCT
    function sale_add_product($product_id, $product_variation_id) {

        //CHECK NEGATIVE INVENTORY
        $available_stock = 0;
        $negative_inventory = 0;

        $this->db->select("*");
        $this->db->from('outlet_products');
        $this->db->join('products', 'products.product_id = outlet_products.product_id');
        $this->db->where( array('outlet_products.outlet_id' => $this->session->userdata('pos_outlet_id'), 'outlet_products.product_id' => $product_id, 'outlet_products.product_variation_id' => $product_variation_id));
        $outlet_product = $this->db->get()->result();
        foreach ($outlet_product as $row) {
            $available_stock = $row->available_stock;
            $negative_inventory = $row->negative_inventory;
        }

        if ($available_stock <= 0 && $negative_inventory == 0){
            $arr_return = array('res' => false,'dt' => 'You have insufficient stock quantity for this product: Only ' . $available_stock . ' remaining');
            return $arr_return;
        }

        $pos_sale_id = 0;

        //Check if there's POS Sale
        $this->db->select("*");
        $this->db->from('pos_sales');       
        $this->db->where( array('is_void' => 0, 'is_held' => 0, 'is_completed' => 0, 'system_user_id' => $this->session->userdata('pos_system_user_id'), 'outlet_id' => $this->session->userdata('pos_outlet_id')));
        $this->db->order_by("pos_sale_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {
            $records = $query->result();
            
            foreach ($records as $row) {
                $pos_sale_id = $row->pos_sale_id;
            }
        } else {

            $comments = '';
            $this->db->select("*");
            $this->db->from('sale_comments');
            $sale_comments = $this->db->get()->result();

            foreach ($sale_comments as $row) {
                $comments = $row->cash_comments;
            }

            $data = array(
                'sale_type' => 'CASH SALE',
                'sale_date' => date('Y-m-d'),
                'system_user_id' =>  $this->session->userdata('pos_system_user_id'),
                'outlet_id' => $this->session->userdata('pos_outlet_id'),
                'comments' => $comments
            );

            $res = $this->db->insert('pos_sales', $data);
            $pos_sale_id = $this->db->insert_id();

            //UPDATE POS SALE NUMBER
            $sale_prefix = '';
            $pos_sale_number = '';
            $this->db->select("*");
            $this->db->from('prefixes'); 
            $this->db->where( array('document_name' => 'Sales Order'));
            $prefix = $this->db->get()->result();

            foreach ($prefix as $row) {
                $sale_prefix = $row->prefix_name;
            }
            if ($sale_prefix != '') {
                $pos_sale_number = $sale_prefix . '-' . $pos_sale_id;
            } else {
                $pos_sale_number = $pos_sale_id;
            }
            $data = array(
                'pos_sale_number' => $pos_sale_number
            );
            $this->db->where(array('pos_sale_id' => $pos_sale_id));
            $res = $this->db->update('pos_sales', $data);
        }

        //Check if Sale Product Exists
        $this->db->select("*");
        $this->db->from('pos_sale_details');       
        $this->db->where( array('pos_sale_id' => $pos_sale_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
        $query = $this->db->get();
        $record_count = $query->num_rows();


        $quantity = 0;
        $unit_id = 0;
        $base_unit_id = 0;
        $conversion_factor = 1;
        // $base_unit_price = 0;
        $base_unit_quantity = 0;
        $unit_price = 0;
        $discount_type = '';
        $discount_value = 0;
        $tax_rate_id = 0;
        $tax_rate_value = 0;
        $price_excl_tax = 0;
        $unit_tax = 0;
        $tax_amount = 0;
        $line_total = 0;
        $sub_total = 0;
        $pos_sale_detail_id = 0;

        if ($record_count > 0) {

            $pos_sale_detail = $query->result();
            foreach ($pos_sale_detail as $row) {
                $pos_sale_detail_id = $row->pos_sale_detail_id;
                $base_unit_id = $row->base_unit_id;
                $unit_id = $row->unit_id;
                $conversion_factor = $row->conversion_factor;
                $unit_price = $row->unit_price;
                $quantity = $row->quantity;
                $base_unit_quantity = $row->base_unit_quantity;
                $discount_type = $row->discount_type;
                $discount_value = $row->discount_value;
            }

            $product_unit_id = 0;
            $product = $this->get_product($product_id);

            foreach ($product as $row) {
                $tax_rate_id = $row->tax_rate_id; 
                $tax_rate_value = $row->tax_rate_value;   
                $product_unit_id = $row->unit_id;
                $product_name = $row->product_name;
            }

            $quantity = $quantity + 1;

            if ($base_unit_id !== $unit_id) {
                $base_unit_quantity = ($quantity * $conversion_factor);   

                //CHECK NEGATIVE INVENTORY
                if (($available_stock - $conversion_factor) <= 0 && $negative_inventory == 0){
                    $arr_return = array('res' => false,'dt' => 'You have insufficient stock quantity for this product: Only ' . $available_stock . ' remaining, while the converted required_quantity is ' . $base_unit_quantity);
                    return $arr_return;
                }
            } else {
                $base_unit_quantity = $quantity;
            }

            if ($tax_rate_id == 0) {
                $unit_tax = $unit_price * (0/100);
            } else {
                $unit_tax = $unit_price * ($tax_rate_value/100);
            }
            $price_excl_tax = $unit_price - $unit_tax;
            
            $tax_amount = $unit_tax * $quantity;
            $line_total = $unit_price * $quantity;

            if ($discount_type == 'Percentage') {
                $discount_amount = $line_total * ($discount_value/100);
            } else {
                $discount_amount = $discount_value * $quantity;
            }

            $sub_total = $line_total - $discount_amount;

            $data = array(
                'unit_price' => $unit_price,
                'tax_rate_id' => $tax_rate_id,
                'price_excl_tax' => $price_excl_tax,
                'unit_tax' => $unit_tax,
                'discount_amount' => $discount_amount,
                'quantity' => $quantity,
                'base_unit_quantity' => $base_unit_quantity,
                'tax_amount' => $tax_amount,
                'line_total' => $line_total,
                'sub_total' => $sub_total
            );

            $this->db->where( array('pos_sale_id' => $pos_sale_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
            $res = $this->db->update('pos_sale_details', $data);

            if ($res){

                //Deduct Stock
                $available_stock = 0;
                $reorder_level = 0;
                $outlet_name = '';

                $this->db->select("op.*, o.outlet_name");
                $this->db->from('outlet_products op');
                $this->db->join('outlets o', 'o.outlet_id = op.outlet_id');
                $this->db->where( array('op.outlet_id' => $this->session->userdata('pos_outlet_id'), 'op.product_id' => $product_id, 'op.product_variation_id' => $product_variation_id));
                $outlet_product = $this->db->get()->result();
                foreach ($outlet_product as $row) {
                    $available_stock = $row->available_stock;
                    $reorder_level = $row->reorder_level;
                    $outlet_name = $row->outlet_name;
                }

                $data = array(
                    'available_stock' =>  $available_stock - $conversion_factor
                );
                $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                $res = $this->db->update('outlet_products', $data);

                //REORDER LEVEL NOTIFICATION
                if (($available_stock > $reorder_level) && ($available_stock - $conversion_factor <= $reorder_level)) {
                    //NOTIFICATION
                    $data = array(
                        'notification_type' => 'Reorder Level Limit Reached',
                        'notification_ref_id' => $product_id,
                        'notification_details' => 'The product <b>#' . $product_name . '</b> has reached its reorder level limit for outlet <b>' .$outlet_name . '</b> and needs replenishing',
                        'notification_ref_link' => 'be/inventory/low_stocks'
                    );
                    $this->db->insert('notifications',$data);
                }

                //STOCK TRACKER
                $data = array(
                    'quantity' => $base_unit_quantity,
                    'unit_price' => $unit_price/$conversion_factor
                );
                $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $pos_sale_detail_id, 'transaction_description' => 'POS Sale'));
                $this->db->update('stock_tracker', $data);

                //Update Totals
                $this->calculate_sale_total($pos_sale_id);

                $arr_return = array('res' => true,'dt' => 'Product added successfully.');
            } else {
                $arr_return = array('res' => false,'dt' => 'Could not add product successfully. Please try again.');
            }
        } else {

            $product = $this->get_product($product_id);

            foreach ($product as $row) {
                $tax_rate_id = $row->tax_rate_id;
                $tax_rate_value = $row->tax_rate_value;
                $unit_id = $row->unit_id;
                $base_unit_id = $row->unit_id; 
                $product_name = $row->product_name;               
            }            

            if ($product_variation_id == 0 || $product_variation_id == null || $product_variation_id == '') {
                foreach ($product as $row) {
                    if ($row->universal_sale_price == 1){
                        if ($row->sale_price > 0){
                            $unit_price = $row->sale_price;
                        } else {
                            if ($row->universal_regular_price == 1){
                                $unit_price = $row->regular_price;
                            } else {
                                $unit_price = $row->outlet_regular_price;
                            }
                        }
                    } else {
                        if ($row->outlet_sale_price > 0){
                            $unit_price = $row->outlet_sale_price;
                        } else {
                          if ($row->universal_regular_price == 1){
                                $unit_price = $row->regular_price;
                            } else {
                                $unit_price = $row->outlet_regular_price;
                            }
                        }
                    }
                }
            } else {
                $product_variation = $this->get_product_variation($product_variation_id);

                foreach ($product_variation as $row) {
                    if ($row->product_variation_universal_prices == 1){
                        if ($row->product_variation_sale_price > 0){
                            $unit_price = $row->product_variation_sale_price;
                        } else {
                            $unit_price = $row->product_variation_regular_price;
                        }
                    } else {
                        if ($row->outlet_sale_price > 0){
                            $unit_price = $row->outlet_sale_price;
                        } else {
                            $unit_price = $row->outlet_regular_price;
                        }
                    }
                }
            }

            $base_unit_price = $unit_price;


            // if ($product_variation_id == 0 || $product_variation_id == '') {
            //     foreach ($product as $row) {
            //         if ($row->sale_price > 0){
            //             $unit_price = $row->sale_price;
            //             $base_unit_price = $row->sale_price;
            //         } else {
            //             $unit_price = $row->regular_price;
            //             $base_unit_price = $row->regular_price;
            //         }
            //     }
            // } else {
            //     $product_variation = $this->get_product_variation($product_variation_id);

            //     foreach ($product_variation as $row) {
            //        if ($row->product_variation_sale_price > 0){
            //             $unit_price = $row->product_variation_sale_price;
            //             $base_unit_price = $row->product_variation_sale_price;
            //         } else {
            //             $unit_price = $row->product_variation_regular_price;
            //             $base_unit_price = $row->product_variation_regular_price;
            //         } 
            //     }
            // }

            if ($tax_rate_id == 0) {
                $unit_tax = $unit_price * (0/100);
            } else {
                $unit_tax = $unit_price * ($tax_rate_value/100);
            }
            $price_excl_tax = $unit_price - $unit_tax;

            
            $quantity = 1;
            $base_unit_quantity = 1;
            $tax_amount = $unit_tax * $quantity;
            $line_total = $unit_price * $quantity;
            $sub_total = $unit_price * $quantity;

            $data = array(
                'pos_sale_id' =>  $pos_sale_id,
                'product_id' => $product_id,
                'product_variation_id' => $product_variation_id,
                'unit_price' => $unit_price,
                'base_unit_price' => $base_unit_price,
                'unit_id' => $unit_id,
                'base_unit_id' => $base_unit_id,
                'conversion_factor' => $conversion_factor,
                'tax_rate_id' => $tax_rate_id,
                'price_excl_tax' => $price_excl_tax,
                'unit_tax' => $unit_tax,
                'quantity' => $quantity,
                'base_unit_quantity' => $base_unit_quantity,
                'tax_amount' => $tax_amount,
                'line_total' => $line_total,
                'sub_total' => $sub_total
            );
            $res = $this->db->insert('pos_sale_details', $data);
            $pos_sale_detail_id = $this->db->insert_id();

            if ($res){

                //Deduct Stock
                $available_stock = 0;
                $reorder_level = 0;
                $outlet_name = '';

                $this->db->select("op.*, o.outlet_name");
                $this->db->from('outlet_products op');
                $this->db->join('outlets o', 'o.outlet_id = op.outlet_id');
                $this->db->where( array('op.outlet_id' => $this->session->userdata('pos_outlet_id'), 'op.product_id' => $product_id, 'op.product_variation_id' => $product_variation_id));
                $outlet_product = $this->db->get()->result();
                foreach ($outlet_product as $row) {
                    $available_stock = $row->available_stock;
                    $reorder_level = $row->reorder_level;
                    $outlet_name = $row->outlet_name;
                }

                $data = array(
                    'available_stock' =>  $available_stock - $base_unit_quantity
                );
                $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                $res = $this->db->update('outlet_products', $data);

                //REORDER LEVEL NOTIFICATION
                if (($available_stock > $reorder_level) && ($available_stock - $base_unit_quantity <= $reorder_level)) {
                    //NOTIFICATION
                    $data = array(
                        'notification_type' => 'Reorder Level Limit Reached',
                        'notification_ref_id' => $product_id,
                        'notification_details' => 'The product <b>#' . $product_name . '</b> has reached its reorder level limit for outlet <b>' .$outlet_name . '</b> and needs replenishing',
                        'notification_ref_link' => 'be/inventory/low_stocks'
                    );
                    $this->db->insert('notifications',$data);
                }
                

                //STOCK TRACKER
                $data = array(
                    'outlet_id' => $this->session->userdata('pos_outlet_id'),
                    'product_id' => $product_id,
                    'product_variation_id' => $product_variation_id,
                    'transaction_id' => $pos_sale_detail_id,
                    'transaction_type' => 'OUT',
                    'transaction_description' => 'POS Sale',
                    'quantity' => $base_unit_quantity,
                    'unit_price' => $base_unit_price
                );
                $this->db->insert('stock_tracker', $data);

                //Update Totals
                $this->calculate_sale_total($pos_sale_id);

                $arr_return = array('res' => true,'dt' => 'Product added successfully.');
            } else {
                $arr_return = array('res' => false,'dt' => 'Could not add product successfully. Please try again.');
            }
        }
      return $arr_return;  
    }

    function edit_sale_add_product() {
        $product_id = $this->input->post('product_id');
        $product_variation_id = $this->input->post('product_variation_id');
        $pos_sale_id = $this->input->post('pos_sale_id');

        //CHECK NEGATIVE INVENTORY
        $available_stock = 0;
        $negative_inventory = 0;

        $this->db->select("*");
        $this->db->from('outlet_products');
        $this->db->join('products', 'products.product_id = outlet_products.product_id');
        $this->db->where( array('outlet_products.outlet_id' => $this->session->userdata('pos_outlet_id'), 'outlet_products.product_id' => $product_id, 'outlet_products.product_variation_id' => $product_variation_id));
        $outlet_product = $this->db->get()->result();
        foreach ($outlet_product as $row) {
            $available_stock = $row->available_stock;
            $negative_inventory = $row->negative_inventory;
        }

        // if ($available_stock <= 0 && $negative_inventory == 0){
        //     $arr_return = array('res' => false,'dt' => 'You have insufficient stock quantity for this product: Only ' . $available_stock . ' remaining');
        //     return $arr_return;
        // }

        //Check if Sale Product Exists
        $this->db->select("*");
        $this->db->from('pos_sale_details');       
        $this->db->where( array('pos_sale_id' => $pos_sale_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
        $query = $this->db->get();
        $record_count = $query->num_rows();


        $quantity = 0;
        $unit_id = 0;
        $base_unit_id = 0;
        $conversion_factor = 1;
        $base_unit_quantity = 0;
        $unit_price = 0;
        $discount_type = '';
        $discount_value = 0;
        $tax_rate_id = 0;
        $tax_rate_value = 0;
        $price_excl_tax = 0;
        $unit_tax = 0;
        $tax_amount = 0;
        $line_total = 0;
        $sub_total = 0;
        $pos_sale_detail_id = 0;

        if ($record_count > 0) {

            $pos_sale_detail = $query->result();
            foreach ($pos_sale_detail as $row) {
                $pos_sale_detail_id = $row->pos_sale_detail_id;
                $base_unit_id = $row->base_unit_id;
                $unit_id = $row->unit_id;
                $conversion_factor = $row->conversion_factor;
                $unit_price = $row->unit_price;
                $quantity = $row->quantity;
                $discount_type = $row->discount_type;
                $discount_value = $row->discount_value;
            }

            $product_unit_id = 0;
            $product = $this->get_product($product_id);

            foreach ($product as $row) {
                $tax_rate_id = $row->tax_rate_id;
                $tax_rate_value = $row->tax_rate_value;
                $product_unit_id = $row->unit_id;
                $product_name = $row->product_name;
            }

            $quantity = $quantity + 1;

            if ($base_unit_id !== $unit_id) {
                $base_unit_quantity = ($quantity * $conversion_factor);   

                //CHECK NEGATIVE INVENTORY
                if (($available_stock - $conversion_factor) <= 0 && $negative_inventory == 0){
                    $arr_return = array('res' => false,'dt' => 'You have insufficient stock quantity for this product: Only ' . $available_stock . ' remaining, while the converted required_quantity is ' . $base_unit_quantity);
                    return $arr_return;
                }
            } else {
                $base_unit_quantity = $quantity;
            }

            if ($tax_rate_id == 0) {
                $unit_tax = $unit_price * (0/100);
            } else {
                $unit_tax = $unit_price * ($tax_rate_value/100);
            }
            $price_excl_tax = $unit_price - $unit_tax;
            
            $tax_amount = $unit_tax * $quantity;
            $line_total = $unit_price * $quantity;

            if ($discount_type == 'Percentage') {
                $discount_amount = $line_total * ($discount_value/100);
            } else {
                $discount_amount = $discount_value * $quantity;
            }

            $sub_total = $line_total - $discount_amount;

            $data = array(
                'unit_price' => $unit_price,
                'tax_rate_id' => $tax_rate_id,
                'price_excl_tax' => $price_excl_tax,
                'unit_tax' => $unit_tax,
                'discount_amount' => $discount_amount,
                'quantity' => $quantity,
                'base_unit_quantity' => $base_unit_quantity,
                'tax_amount' => $tax_amount,
                'line_total' => $line_total,
                'sub_total' => $sub_total
            );

            $this->db->where( array('pos_sale_id' => $pos_sale_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
            $res = $this->db->update('pos_sale_details', $data);

            if ($res){

                //Deduct Stock
                $available_stock = 0;
                $reorder_level = 0;
                $outlet_name = '';

                $this->db->select("op.*, o.outlet_name");
                $this->db->from('outlet_products op');
                $this->db->join('outlets o', 'o.outlet_id = op.outlet_id');
                $this->db->where( array('op.outlet_id' => $this->session->userdata('pos_outlet_id'), 'op.product_id' => $product_id, 'op.product_variation_id' => $product_variation_id));
                $outlet_product = $this->db->get()->result();
                foreach ($outlet_product as $row) {
                    $available_stock = $row->available_stock;
                    $reorder_level = $row->reorder_level;
                    $outlet_name = $row->outlet_name;
                }

                $data = array(
                    'available_stock' =>  $available_stock - $conversion_factor
                );
                $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                $res = $this->db->update('outlet_products', $data);

                //REORDER LEVEL NOTIFICATION
                if (($available_stock > $reorder_level) && ($available_stock - $conversion_factor <= $reorder_level)) {
                    //NOTIFICATION
                    $data = array(
                        'notification_type' => 'Reorder Level Limit Reached',
                        'notification_ref_id' => $product_id,
                        'notification_details' => 'The product <b>#' . $product_name . '</b> has reached its reorder level limit for outlet <b>' .$outlet_name . '</b> and needs replenishing',
                        'notification_ref_link' => 'be/inventory/low_stocks'
                    );
                    $this->db->insert('notifications',$data);
                }

                //STOCK TRACKER
                $data = array(
                    'quantity' => $base_unit_quantity,
                    'unit_price' => $unit_price/$conversion_factor
                );
                $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $pos_sale_detail_id, 'transaction_description' => 'POS Sale'));
                $this->db->update('stock_tracker', $data);

                //Update Totals
                $this->calculate_sale_total($pos_sale_id);

                $arr_return = array('res' => true,'dt' => 'Product added successfully.');
            } else {
                $arr_return = array('res' => false,'dt' => 'Could not add product successfully. Please try again.');
            }

        } else {

            $product = $this->get_product($product_id);

            foreach ($product as $row) {
                $tax_rate_id = $row->tax_rate_id;
                $tax_rate_value = $row->tax_rate_value;
                $unit_id = $row->unit_id;
                $base_unit_id = $row->unit_id;
                $product_name = $row->product_name;
            }

            if ($product_variation_id == 0 || $product_variation_id == null || $product_variation_id == '') {
                foreach ($product as $row) {
                    if ($row->universal_sale_price == 1){
                        if ($row->sale_price > 0){
                            $unit_price = $row->sale_price;
                        } else {
                            if ($row->universal_regular_price == 1){
                                $unit_price = $row->regular_price;
                            } else {
                                $unit_price = $row->outlet_regular_price;
                            }
                        }
                    } else {
                        if ($row->outlet_sale_price > 0){
                            $unit_price = $row->outlet_sale_price;
                        } else {
                          if ($row->universal_regular_price == 1){
                                $unit_price = $row->regular_price;
                            } else {
                                $unit_price = $row->outlet_regular_price;
                            }
                        }
                    }
                }
            } else {
                $product_variation = $this->get_product_variation($product_variation_id);

                foreach ($product_variation as $row) {
                    if ($row->product_variation_universal_prices == 1){
                        if ($row->product_variation_sale_price > 0){
                            $unit_price = $row->product_variation_sale_price;
                        } else {
                            $unit_price = $row->product_variation_regular_price;
                        }
                    } else {
                        if ($row->outlet_sale_price > 0){
                            $unit_price = $row->outlet_sale_price;
                        } else {
                            $unit_price = $row->outlet_regular_price;
                        }
                    }
                }
            }

            $base_unit_price = $unit_price;

            // if ($product_variation_id == 0 || $product_variation_id == '') {
            //     foreach ($product as $row) {
            //         if ($row->sale_price > 0){
            //             $unit_price = $row->sale_price;
            //             $base_unit_price = $row->sale_price;
            //         } else {
            //             $unit_price = $row->regular_price;
            //             $base_unit_price = $row->regular_price;
            //         }
            //     }
            // } else {
            //     $product_variation = $this->get_product_variation($product_variation_id);

            //     foreach ($product_variation as $row) {
            //        if ($row->product_variation_sale_price > 0){
            //             $unit_price = $row->product_variation_sale_price;
            //             $base_unit_price = $row->product_variation_sale_price;
            //         } else {
            //             $unit_price = $row->product_variation_regular_price;
            //             $base_unit_price = $row->product_variation_regular_price;
            //         } 
            //     }
            // }

            if ($tax_rate_id == 0) {
                $unit_tax = $unit_price * (0/100);
            } else {
                $unit_tax = $unit_price * ($tax_rate_value/100);
            }
            $price_excl_tax = $unit_price - $unit_tax;

            
            $quantity = 1;
            $base_unit_quantity = 1;
            $tax_amount = $unit_tax * $quantity;
            $line_total = $unit_price * $quantity;
            $sub_total = $unit_price * $quantity;

            $data = array(
                'pos_sale_id' =>  $pos_sale_id,
                'product_id' => $product_id,
                'product_variation_id' => $product_variation_id,
                'unit_price' => $unit_price,
                'base_unit_price' => $base_unit_price,
                'unit_id' => $unit_id,
                'base_unit_id' => $base_unit_id,
                'conversion_factor' => $conversion_factor,
                'tax_rate_id' => $tax_rate_id,
                'price_excl_tax' => $price_excl_tax,
                'unit_tax' => $unit_tax,
                'quantity' => $quantity,
                'base_unit_quantity' => $base_unit_quantity,
                'tax_amount' => $tax_amount,
                'line_total' => $line_total,
                'sub_total' => $sub_total
            );
            $res = $this->db->insert('pos_sale_details', $data);
            $pos_sale_detail_id = $this->db->insert_id();

            if ($res){

                //Deduct Stock
                $available_stock = 0;
                $reorder_level = 0;
                $outlet_name = 0;

                $this->db->select("op.*, o.outlet_name");
                $this->db->from('outlet_products op');
                $this->db->join('outlets o', 'o.outlet_id = op.outlet_id');
                $this->db->where( array('op.outlet_id' => $this->session->userdata('pos_outlet_id'), 'op.product_id' => $product_id, 'op.product_variation_id' => $product_variation_id));
                $outlet_product = $this->db->get()->result();
                foreach ($outlet_product as $row) {
                    $available_stock = $row->available_stock;
                    $reorder_level = $row->reorder_level;
                    $outlet_name = $row->outlet_name;
                }

                $data = array(
                    'available_stock' =>  $available_stock - $base_unit_quantity
                );
                $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                $res = $this->db->update('outlet_products', $data);

                //REORDER LEVEL NOTIFICATION
                if (($available_stock > $reorder_level) && ($available_stock - $base_unit_quantity <= $reorder_level)) {
                    //NOTIFICATION
                    $data = array(
                        'notification_type' => 'Reorder Level Limit Reached',
                        'notification_ref_id' => $product_id,
                        'notification_details' => 'The product <b>#' . $product_name . '</b> has reached its reorder level limit for outlet <b>' .$outlet_name . '</b> and needs replenishing',
                        'notification_ref_link' => 'be/inventory/low_stocks'
                    );
                    $this->db->insert('notifications',$data);
                }

                //STOCK TRACKER
                $data = array(
                    'outlet_id' => $this->session->userdata('pos_outlet_id'),
                    'product_id' => $product_id,
                    'product_variation_id' => $product_variation_id,
                    'transaction_id' => $pos_sale_detail_id,
                    'transaction_type' => 'OUT',
                    'transaction_description' => 'POS Sale',
                    'quantity' => $base_unit_quantity,
                    'unit_price' => $base_unit_price
                );
                $this->db->insert('stock_tracker', $data);

                //Update Totals
                $this->calculate_sale_total($pos_sale_id);

                $arr_return = array('res' => true,'dt' => 'Product added successfully.');
            } else {
                $arr_return = array('res' => false,'dt' => 'Could not add product successfully. Please try again.');
            }
        }
        
        return $arr_return;  

    }


    //CALCULATE SALE TOTALS
    function calculate_sale_total($pos_sale_id) {

        //SALE DETAILS
        $sub_total = 0;
        $total_tax = 0;
        $total_items_discount = 0;
        $total_quantity = 0;

        $this->db->select("*");
        $this->db->from('pos_sale_details');       
        $this->db->where( array('pos_sale_id' => $pos_sale_id));
        $pos_sale_details = $this->db->get()->result();
        
        foreach ($pos_sale_details as $row) {
            $sub_total = $sub_total + $row->sub_total;
            $total_tax = $total_tax + $row->tax_amount;
            $total_items_discount = $total_items_discount + $row->discount_amount;
            $total_quantity = $total_quantity + $row->quantity;
        }

        //SALE
        $overall_discount_type = '';
        $overall_discount_value = 0;
        $overall_discount = 0;
        $delivery_fee = 0;
        $total_sale = 0;
        $total_paid = 0;
        $change_amount = 0;

        $this->db->select("*");
        $this->db->from('pos_sales');       
        $this->db->where( array('pos_sale_id' => $pos_sale_id));
        $pos_sale = $this->db->get()->result();

        foreach ($pos_sale as $row) {
            $overall_discount_type = $row->overall_discount_type;
            $overall_discount_value = $row->overall_discount_value;
            $delivery_fee = $row->delivery_fee;
            $total_paid = $row->total_paid;
        }

        if ($overall_discount_type == 'Percentage') {
            $overall_discount = $sub_total * ($overall_discount_value/100);
        } else {
            $overall_discount = $overall_discount_value;
        }

        $total_sale = ceil(($sub_total - $overall_discount) + $delivery_fee);

        if ($total_paid > $total_sale) {
            $change_amount = $total_paid - $total_sale;
        }

        //UPDATE TABLE
        $data = array(
            'sub_total' =>  $sub_total,
            'total_tax' => $total_tax,
            'total_items_discount' => $total_items_discount,
            'overall_discount' => $overall_discount,
            'total_sale' => $total_sale,
            'change_amount' => $change_amount,
            'total_quantity' => $total_quantity
        );

        $this->db->where( array('pos_sale_id' => $pos_sale_id));
        $this->db->update('pos_sales', $data);
    }


    //UPDATE SALES MODIFY ITEM
    function submit_modify_sales_item(){
        $product_id = $this->input->post('product_id');
        $product_variation_id = $this->input->post('product_variation_id');
        $pos_sale_id = $this->input->post('pos_sale_id');
        $pos_sale_detail_id = $this->input->post('pos_sale_detail_id');

        //$this->debug_to_console($pos_sale_detail_id, 'Context');

        $new_quantity = $this->input->post('quantity');
        $real_quantity = 0;
        $old_quantity = 0;
        $real_old_quantity = 0;
        $new_unit_id = $this->input->post('unit_id');
        $base_unit_id = 0;
        $old_unit_id = 0;
        $conversion_factor = $this->input->post('conversion_factor');     
        $unit_price = $this->input->post('unit_price');
        $discount_type = $this->input->post('discount_type');
        $discount_value = $this->input->post('discount_value');
        $discounted_price = 0;
        $tax_rate_id = 0;
        $tax_rate_value = 0;
        $price_excl_tax = 0;
        $unit_tax = 0;
        $tax_amount = 0;
        $line_total = 0;
        $sub_total = 0;

        $negative_inventory = 0;
        $available_stock = 0;
        $minimum_selling_price = 0;

        $pos_sale_detail = $this->get_pos_sale_detail($pos_sale_detail_id);

        foreach ($pos_sale_detail as $row) {
            $old_quantity = $row->quantity;
            $old_unit_id = $row->unit_id;
        }

        $product = $this->get_product($product_id);

        foreach ($product as $row) {
            $base_unit_id = $row->unit_id;
            $tax_rate_id = $row->tax_rate_id;
            $tax_rate_value = $row->tax_rate_value;
            $negative_inventory = $row->negative_inventory;
            $product_name = $row->product_name;
        }

        if ($base_unit_id == $new_unit_id){
            $real_quantity = $new_quantity;
            if ($product_variation_id == 0 || $product_variation_id == '') {
                foreach ($product as $row) {
                    $minimum_selling_price = $row->minimum_selling_price;
                }
            } else {
                $product_variation = $this->get_product_variation($product_variation_id);

                foreach ($product_variation as $row) {
                   $minimum_selling_price = $row->product_variation_minimum_selling_price;
                }
            }
        } else {
            $real_quantity = $new_quantity * $conversion_factor;
            $this->db->from('product_related_units');
            $this->db->where( array('product_id'=>$product_id, 'related_unit_id'=>$new_unit_id));
            $related_unit = $this->db->get()->result();
            foreach ($related_unit as $row2) {
                $minimum_selling_price = $row2->unit_minimum_selling_price;
            }
        }

        if ($old_unit_id == $base_unit_id) {
            $real_old_quantity = $old_quantity;
        } else {
            $real_old_quantity = $old_quantity * $conversion_factor;
        }

        if ($tax_rate_id == 0) {
            $unit_tax = $unit_price * (0/100);
        } else {
            $unit_tax = $unit_price * ($tax_rate_value/100);
        }
        $price_excl_tax = $unit_price - $unit_tax;

        //DISCOUNTED PRICE
        if ($discount_type == 'Percentage') {
            $discounted_price = $unit_price - ($unit_price * ($discount_value/100));
        } else {
            $discounted_price = ($unit_price - $discount_value);
        }

        //CHECK NEGATIVE INVENTORY
        $this->db->select("*");
        $this->db->from('outlet_products');
        $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
        $outlet_product = $this->db->get()->result();
        foreach ($outlet_product as $row) {
            $available_stock = $row->available_stock;
        }

        if ($negative_inventory == 0 && (($available_stock + $real_old_quantity) - $real_quantity) < 0){
            $required_quantity = $real_quantity - $real_old_quantity;
            $arr_return = array('res' => false,'dt' => 'You have insufficient stock quantity for this product.<br><br> Required: ' . $required_quantity . '<br> Remaining: ' . $available_stock);
            return $arr_return;
        }

        //CHECK MINIMUM SELLING PRICE
        if ($minimum_selling_price != 0 && $minimum_selling_price > $discounted_price) {
            $arr_return = array('res' => false,'dt' => 'The selling price cannot be less than the minimum selling price for this product.<br><br> Selling Price: ' .  number_format($discounted_price,2) . '<br> Minimum Selling Price: ' . number_format($minimum_selling_price,2));
            return $arr_return;
        }

        $tax_amount = $unit_tax * $new_quantity;
        $line_total = $unit_price * $new_quantity;

        if ($discount_type == 'Percentage') {
            $discount_amount = $line_total * ($discount_value/100);
        } else {
            $discount_amount = ($discount_value * $new_quantity);
        }

        $sub_total = $line_total - $discount_amount;

        $data = array(
            'unit_id' => $new_unit_id,
            'conversion_factor' => $conversion_factor,
            'unit_price' => $unit_price,
            'discount_type' => $discount_type,
            'discount_value' => $discount_value,
            'discount_amount' => $discount_amount,
            'tax_rate_id' => $tax_rate_id,
            'price_excl_tax' => $price_excl_tax,
            'unit_tax' => $unit_tax,
            'quantity' => $new_quantity,
            'base_unit_quantity' => $real_quantity,
            'tax_amount' => $tax_amount,
            'line_total' => $line_total,
            'sub_total' => $sub_total
        );

        $this->db->where( array('pos_sale_detail_id' => $pos_sale_detail_id));
        $res = $this->db->update('pos_sale_details', $data);

        if ($res){

            //Deduct Stock
            $available_stock = 0;
            $reorder_level = 0;
            $outlet_name = '';

            $this->db->select("op.*, o.outlet_name");
            $this->db->from('outlet_products op');
            $this->db->join('outlets o', 'o.outlet_id = op.outlet_id');
            $this->db->where( array('op.outlet_id' => $this->session->userdata('pos_outlet_id'), 'op.product_id' => $product_id, 'op.product_variation_id' => $product_variation_id));
            $outlet_product = $this->db->get()->result();
            foreach ($outlet_product as $row) {
                $available_stock = $row->available_stock;
                $reorder_level = $row->reorder_level;
                $outlet_name = $row->outlet_name;
            }

            $data = array(
                'available_stock' =>  ($available_stock + $real_old_quantity) - $real_quantity
            );
            $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
            $res = $this->db->update('outlet_products', $data);

            //REORDER LEVEL NOTIFICATION
            if (($available_stock + $real_old_quantity > $reorder_level) && (($available_stock + $real_old_quantity) - $real_quantity <= $reorder_level)) {
                //NOTIFICATION
                $data = array(
                    'notification_type' => 'Reorder Level Limit Reached',
                    'notification_ref_id' => $product_id,
                    'notification_details' => 'The product <b>#' . $product_name . '</b> has reached its reorder level limit for outlet <b>' .$outlet_name . '</b> and needs replenishing',
                    'notification_ref_link' => 'be/inventory/low_stocks'
                );
                $this->db->insert('notifications',$data);
            }

            //STOCK TRACKER
            $data = array(
                'quantity' => $real_quantity
            );
            $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $pos_sale_detail_id, 'transaction_description' => 'POS Sale'));
            $this->db->update('stock_tracker', $data);

            //Update Totals
            $this->calculate_sale_total($pos_sale_id);

            $arr_return = array('res' => true,'dt' => 'Sale item updated successfully.');
        } else {
            $arr_return = array('res' => false,'dt' => 'Could not update sale item successfully. Please try again.');
        }

        return $arr_return;
    }

    function remove_pos_sale_item($pos_sale_detail_id){

        $old_quantity = 0;
        $product_id = 0;
        $product_variation_id = 0;
        $pos_sale_id = 0;
        $outlet_id = 0;

        $pos_sale_detail = $this->get_pos_sale_detail($pos_sale_detail_id);

        foreach ($pos_sale_detail as $row) {
            $old_quantity = $row->base_unit_quantity;
            $product_id = $row->product_id;
            $product_variation_id = $row->product_variation_id;
            $pos_sale_id = $row->pos_sale_id;
            $outlet_id = $row->outlet_id;
        }

       $this->db->where('pos_sale_detail_id', $pos_sale_detail_id);
       $del = $this->db->delete('pos_sale_details');                 

       if ($del){

            //Deduct Stock
            $available_stock = 0;
            $reorder_level = 0;

            $this->db->select("*");
            $this->db->from('outlet_products');
            $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id));
            $outlet_product = $this->db->get()->result();
            foreach ($outlet_product as $row) {
                $available_stock = $row->available_stock;
                $reorder_level = $row->reorder_level;
            }

            $data = array(
                'available_stock' =>  $available_stock + $old_quantity
            );
            $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id));
            $res = $this->db->update('outlet_products', $data);

            //STOCK TRACKER
            $this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $pos_sale_detail_id, 'transaction_description' => 'POS Sale'));
            $this->db->delete('stock_tracker');

            //Update Totals
            $this->calculate_sale_total($pos_sale_id);

            $arr_return = array('res' => true,'dt' => 'Sale item removed successfully.');
        } else {
            $arr_return = array('res' => false,'dt' => 'Could not remove sale item successfully. Please try again.');
        }

        return $arr_return;
    }

    function sale_change_type_valid() {
        $transaction_type = $this->input->post('transaction_type');

        if ($transaction_type == 'Add'){
            $pos_sale_id = 0;
            $this->db->select("*");
            $this->db->from('pos_sales');       
            $this->db->where( array('is_void' => 0, 'is_held' => 0, 'is_completed' => 0, 'system_user_id' => $this->session->userdata('pos_system_user_id'), 'outlet_id' => $this->session->userdata('pos_outlet_id')));
            $this->db->order_by("pos_sale_id", "desc");
            $this->db->limit(1);
            $query = $this->db->get();
            $record_count = $query->num_rows();

            if ($record_count > 0) {   
                $records = $query->result();         
                $arr_return = array('res' => true,'dt' => 'Items Available.', 'data' => $records);            
            } else {
                $arr_return = array('res' => false,'dt' => 'Please add items to the sale first before setting a Discount.', 'data' => '');
            }
        } elseif ($transaction_type == 'Edit') {
            $pos_sale_id = $this->input->post('pos_sale_id');
            $pos_sale = $this->get_pos_sale($pos_sale_id);
            $arr_return = array('res' => true,'dt' => 'Items Available.', 'data' => $pos_sale);   
        }
        return $arr_return;
    }

    function submit_change_sale_type() {
        $pos_sale_id = $this->input->post('pos_sale_id');
        $sale_type = $this->input->post('sale_type');
        $credit_term_id = $this->input->post('credit_term_id');

        if ($pos_sale_id == '' || $pos_sale_id == null) {
            if ($sale_type == 'CASH SALE') {
                $comments = '';
                $this->db->select("*");
                $this->db->from('sale_comments');
                $sale_comments = $this->db->get()->result();

                foreach ($sale_comments as $row) {
                    $comments = $row->cash_comments;
                }
                $data = array(
                    'sale_type' => $sale_type,
                    'sale_date' => date('Y-m-d'),
                    'system_user_id' =>  $this->session->userdata('pos_system_user_id'),
                    'outlet_id' => $this->session->userdata('pos_outlet_id'),
                    'comments' => $comments
                );
            } elseif ($sale_type == 'CREDIT SALE') {
                $comments = '';
                $this->db->select("*");
                $this->db->from('sale_comments');
                $sale_comments = $this->db->get()->result();

                foreach ($sale_comments as $row) {
                    $comments = $row->credit_comments;
                }

                $sale_date = date('Y-m-d');
                $credit_due_date = '';
                $credit_days = 0;

                //GET CREDIT DAYS
                $this->db->select("*");
                $this->db->from('credit_terms');       
                $this->db->where(array('credit_term_id' => $credit_term_id));
                $credit_term = $this->db->get()->result();

                foreach ($credit_term as $row) {
                    $credit_days = $row->credit_term_days;
                }

                $credit_due_date = date('Y-m-d',strtotime($sale_date. ' + ' . $credit_days . ' days'));

                $data = array(
                    'sale_type' => $sale_type,
                    'sale_date' => $sale_date,
                    'credit_term_id' => $credit_term_id,
                    'credit_days' => $credit_days,
                    'credit_due_date' => $credit_due_date,
                    'system_user_id' =>  $this->session->userdata('pos_system_user_id'),
                    'outlet_id' => $this->session->userdata('pos_outlet_id'),
                    'comments' => $comments
                );
            }

            $res = $this->db->insert('pos_sales', $data);
            $pos_sale_id = $this->db->insert_id();
            if ($res){

                //UPDATE POS SALE NUMBER
                $sale_prefix = '';
                $pos_sale_number = '';
                $this->db->select("*");
                $this->db->from('prefixes'); 
                $this->db->where( array('document_name' => 'Sales Order'));
                $prefix = $this->db->get()->result();

                foreach ($prefix as $row) {
                    $sale_prefix = $row->prefix_name;
                }
                if ($sale_prefix != '') {
                    $pos_sale_number = $sale_prefix . '-' . $pos_sale_id;
                } else {
                    $pos_sale_number = $pos_sale_id;
                }
                $data = array(
                    'pos_sale_number' => $pos_sale_number
                );
                $this->db->where(array('pos_sale_id' => $pos_sale_id));
                $res = $this->db->update('pos_sales', $data);

                $arr_return = array('res' => true,'dt' => 'Sale updated successfully.','pos_sale_id' => $pos_sale_id);
            } else {
                $arr_return = array('res' => false,'dt' => 'Could not update sale successfully. Please try again.','pos_sale_id' => $pos_sale_id);
            }
        } else {
            if ($sale_type == 'CASH SALE') {
                $data = array(
                    'sale_type' => $sale_type,
                    'credit_term_id' => 0,
                    'credit_days' => 0,
                    'credit_due_date' => ''
                );
                $this->db->where( array('pos_sale_id' => $pos_sale_id));
                $update = $this->db->update('pos_sales', $data);
            } elseif ($sale_type == 'CREDIT SALE') {
                $sale_date = '';
                $credit_due_date = '';
                $credit_days = 0;

                //GET SALE DATE
                $this->db->select("*");
                $this->db->from('pos_sales');       
                $this->db->where(array('pos_sale_id' => $pos_sale_id));
                $pos_sale = $this->db->get()->result();

                foreach ($pos_sale as $row) {
                    $sale_date = $row->sale_date;
                }

                //GET CREDIT DAYS
                $this->db->select("*");
                $this->db->from('credit_terms');       
                $this->db->where(array('credit_term_id' => $credit_term_id));
                $credit_term = $this->db->get()->result();

                foreach ($credit_term as $row) {
                    $credit_days = $row->credit_term_days;
                }

                $credit_due_date = date('Y-m-d',strtotime($sale_date. ' + ' . $credit_days . ' days'));

                //UPDATE
                $data = array(
                    'sale_type' => $sale_type,
                    'credit_term_id' => $credit_term_id,
                    'credit_days' => $credit_days,
                    'credit_due_date' => $credit_due_date
                );
                $this->db->where( array('pos_sale_id' => $pos_sale_id));
                $update = $this->db->update('pos_sales', $data);
            }
        }        

        // if ($update) {
        $this->calculate_sale_total($pos_sale_id);
        $arr_return = array('res' => true,'dt' => 'Sale type changed successfully');
        // } else {
        //     $arr_return = array('res' => false,'dt' => 'Could not set Discount successfully. Please try again.');
        // }

        return $arr_return;
    }

    function sale_set_discount_valid(){
        $pos_sale_id = 0;
        $this->db->select("*");
        $this->db->from('pos_sales');       
        $this->db->where( array('is_void' => 0, 'is_held' => 0, 'is_completed' => 0, 'system_user_id' => $this->session->userdata('pos_system_user_id'), 'outlet_id' => $this->session->userdata('pos_outlet_id')));
        $this->db->order_by("pos_sale_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {
            $records = $query->result();
            
            foreach ($records as $row) {
                $pos_sale_id = $row->pos_sale_id;
            }

            //CHECK ADDED ITEMS
            $this->db->select("*");
            $this->db->from('pos_sale_details');       
            $this->db->where( array('pos_sale_id' => $pos_sale_id));
            $query = $this->db->get();
            $record_count = $query->num_rows();

            if ($record_count > 0) {
                $arr_return = array('res' => true,'dt' => 'Items Available.', 'data' => $records);
            } else {
                $arr_return = array('res' => false,'dt' => 'Please add items to the sale first before setting a Discount.', 'data' => '');
            }
        } else {
            $arr_return = array('res' => false,'dt' => 'Please add items to the sale first before setting a Discount.', 'data' => '');
        }
        return $arr_return;
    }

    function submit_sale_overall_discount() {
        $pos_sale_id = $this->input->post('pos_sale_id');

        $data = array(
            'overall_discount_type' => $this->input->post('overall_discount_type'),
            'overall_discount_value' => $this->input->post('overall_discount_value')
        );

        $this->db->where( array('pos_sale_id' => $pos_sale_id));
        $update = $this->db->update('pos_sales', $data);

        if ($update) {
            $this->calculate_sale_total($pos_sale_id);
            $arr_return = array('res' => true,'dt' => 'Discount set successfully');
        } else {
            $arr_return = array('res' => false,'dt' => 'Could not set Discount successfully. Please try again.');
        }

        return $arr_return;
    }

    function sale_set_delivery_fee_valid() {
        $pos_sale_id = 0;
        $this->db->select("*");
        $this->db->from('pos_sales');       
        $this->db->where( array('is_void' => 0, 'is_held' => 0, 'is_completed' => 0, 'system_user_id' => $this->session->userdata('pos_system_user_id'), 'outlet_id' => $this->session->userdata('pos_outlet_id')));
        $this->db->order_by("pos_sale_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {
            $records = $query->result();
            
            foreach ($records as $row) {
                $pos_sale_id = $row->pos_sale_id;
            }

            //CHECK ADDED ITEMS
            $this->db->select("*");
            $this->db->from('pos_sale_details');       
            $this->db->where( array('pos_sale_id' => $pos_sale_id));
            $query = $this->db->get();
            $record_count = $query->num_rows();

            if ($record_count > 0) {
                $arr_return = array('res' => true,'dt' => 'Items Available.', 'data' => $records);
            } else {
                $arr_return = array('res' => false,'dt' => 'Please add items to the sale first before setting Delivery Fee.', 'data' => '');
            }
        } else {
            $arr_return = array('res' => false,'dt' => 'Please add items to the sale first before setting Delivery Fee.', 'data' => '');
        }
        return $arr_return;
    }

    function submit_sale_delivery_fee(){
        $pos_sale_id = $this->input->post('pos_sale_id');

        $data = array(
            'delivery_fee' => $this->input->post('delivery_fee')
        );

        $this->db->where( array('pos_sale_id' => $pos_sale_id));
        $update = $this->db->update('pos_sales', $data);

        if ($update) {
            $this->calculate_sale_total($pos_sale_id);
            $arr_return = array('res' => true,'dt' => 'Delivery Fee set successfully');
        } else {
            $arr_return = array('res' => false,'dt' => 'Could not set Delivery Fee successfully. Please try again.');
        }

        return $arr_return;
    }
    function get_sale_comments(){
        $this->db->from('sale_comments');
        return $this->db->get()->result();
    }
    function sale_set_comments_valid(){
        $pos_sale_id = 0;
        $this->db->select("*");
        $this->db->from('pos_sales');       
        $this->db->where( array('is_void' => 0, 'is_held' => 0, 'is_completed' => 0, 'system_user_id' => $this->session->userdata('pos_system_user_id'), 'outlet_id' => $this->session->userdata('pos_outlet_id')));
        $this->db->order_by("pos_sale_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {
            $records = $query->result();   
            $sale_comments = $this->get_sale_comments();  
            $arr_return = array('res' => true,'dt' => 'Sale Available.', 'data' => $records, 'default_comments' => $sale_comments);
        } else {
            $arr_return = array('res' => false,'dt' => 'Please add items to the sale first before setting comments.', 'data' => '', 'default_comments' => '');
        }
        return $arr_return;
    }

    function sale_set_date_valid(){
        $pos_sale_id = 0;
        $this->db->select("*");
        $this->db->from('pos_sales');       
        $this->db->where( array('is_void' => 0, 'is_held' => 0, 'is_completed' => 0, 'system_user_id' => $this->session->userdata('pos_system_user_id'), 'outlet_id' => $this->session->userdata('pos_outlet_id')));
        $this->db->order_by("pos_sale_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {
            $records = $query->result();            
            $arr_return = array('res' => true,'dt' => 'Sale Available.', 'data' => $records);
        } else {
            $arr_return = array('res' => false,'dt' => 'Please add items to the Sale first before setting date.', 'data' => '');
        }
        return $arr_return;
    }

    function submit_enter_customer_name(){
        $pos_sale_id = $this->input->post('pos_sale_id');

        $data = array(
            'customer_name' => $this->input->post('customer_name')
        );

        $this->db->where( array('pos_sale_id' => $pos_sale_id));
        $update = $this->db->update('pos_sales', $data);

        if ($update) {
            $arr_return = array('res' => true,'dt' => 'Customer name saved successfully');
        } else {
            $arr_return = array('res' => false,'dt' => 'Could not save Customer name successfully. Please try again.');
        }

        return $arr_return;
    }

    function submit_sale_comments(){
        $pos_sale_id = $this->input->post('pos_sale_id');

        $data = array(
            'comments' => $this->input->post('comments')
        );

        $this->db->where( array('pos_sale_id' => $pos_sale_id));
        $update = $this->db->update('pos_sales', $data);

        if ($update) {
            $arr_return = array('res' => true,'dt' => 'Comments saved successfully');
        } else {
            $arr_return = array('res' => false,'dt' => 'Could not save Comments successfully. Please try again.');
        }

        return $arr_return;
    }

    function submit_sale_date(){
        $pos_sale_id = $this->input->post('pos_sale_id');
        $sale_date = $this->input->post('sale_date');
        $credit_due_date = '';
        $credit_days = 0;

        //CREDIT DUE DATE
        $pos_sale = $this->get_pos_sale($pos_sale_id);
        foreach ($pos_sale as $row) {
            if ($row->sale_type == 'CREDIT SALE') {
                $credit_days = $row->credit_days;
                $credit_due_date = date('Y-m-d', strtotime($sale_date . ' + ' . $credit_days . ' days'));
            }
        }

        $data = array(
            'sale_date' => $sale_date,
            'credit_due_date' => $credit_due_date
        );
        $this->db->where( array('pos_sale_id' => $pos_sale_id));
        $update = $this->db->update('pos_sales', $data);

        if ($update) {
            $arr_return = array('res' => true,'dt' => 'Sale date saved successfully');
        } else {
            $arr_return = array('res' => false,'dt' => 'Could not save Sale date successfully. Please try again.');
        }

        return $arr_return;
    }

    function sale_enter_customer_name_valid(){
        $pos_sale_id = 0;
        $this->db->select("*");
        $this->db->from('pos_sales');       
        $this->db->where( array('is_void' => 0, 'is_held' => 0, 'is_completed' => 0, 'system_user_id' => $this->session->userdata('pos_system_user_id'), 'outlet_id' => $this->session->userdata('pos_outlet_id')));
        $this->db->order_by("pos_sale_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {
            $records = $query->result();
            
            foreach ($records as $row) {
                $pos_sale_id = $row->pos_sale_id;
            }

            //CHECK ADDED ITEMS
            $this->db->select("*");
            $this->db->from('pos_sale_details');       
            $this->db->where( array('pos_sale_id' => $pos_sale_id));
            $query = $this->db->get();
            $record_count = $query->num_rows();

            if ($record_count > 0) {
                $arr_return = array('res' => true,'dt' => 'Items Available.', 'data' => $records);
            } else {
                $arr_return = array('res' => false,'dt' => 'Please add items to the sale first before entering customer name.', 'data' => '');
            }
        } else {
            $arr_return = array('res' => false,'dt' => 'Please add items to the sale first before entering customer name.', 'data' => '');
        }
        return $arr_return;
    }

    function submit_sale_add_customer(){
        $email_address = $this->input->post('email_address');
        $phone_number = $this->input->post('phone_number');

        $msg = '';
        $msg2 = '';
        
        //EMAIL ADDRESS
        if ($email_address !== ''){
            $this->db->where(array('email_address' => $email_address, 'is_deleted' => 0));
            $query = $this->db->get('customers');

            if ($query->num_rows() > 0){
                $msg2 = 'Duplicate Email Address: The Email Address you entered is already in the database.';
            }
        }

        if ($msg2 != $msg) {
            $arr_return = array('res' => false,'dt' => $msg2, 'id' => '');
            return $arr_return;
        }

        //PHONE NUMBER
        $this->db->where(array('phone_number' => $phone_number, 'is_deleted' => 0));
        $query = $this->db->get('customers');

        if ($query->num_rows() > 0){
            $arr_return = array('res' => false,'dt' => 'Duplicate Phone Number: The Phone Number you entered is already in the database.', 'id' => '');
        }else{
            $init_password = $this->generate_password(9);
            $data = array(
                'first_name' => $this->input->post('first_name'),
                'last_name' => $this->input->post('last_name'),
                'phone_number' => $this->input->post('phone_number'),
                'email_address' => $this->input->post('email_address'),
                'init_password' => $init_password,
                'password' => md5($init_password),
                'created_on' => date("Y-m-d H:i:s", time())
            );

            $insert = $this->db->insert('customers', $data);
            $insert_id = $this->db->insert_id();
            if ($insert){
                $arr_return = array('res' => true,'dt' => 'Customer added successfully.', 'id' => $insert_id);
            }else{
                $arr_return = array('res' => false,'dt' => 'Could not add Customer successfully. Please try again.', 'id' => '');
            }
        }

        return $arr_return;
    }

    function generate_password( $length) {
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@#";
        $password = substr( str_shuffle( $chars ), 0, $length );
        return $password;
    }

    function edit_sale_enter_customer_name_valid($pos_sale_id){

        //CHECK ADDED ITEMS
        $this->db->select("*");
        $this->db->from('pos_sale_details');       
        $this->db->where( array('pos_sale_id' => $pos_sale_id));
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {

            $this->db->select("*");
            $this->db->from('pos_sales');       
            $this->db->where(array('pos_sale_id' => $pos_sale_id));
            $records = $this->db->get()->result();

            $arr_return = array('res' => true,'dt' => 'Items Available.', 'data' => $records);
        } else {
            $arr_return = array('res' => false,'dt' => 'Please add items to the sale first before entering customer name.', 'data' => '');
        }

        return $arr_return;
    }

    function sale_make_payment_valid(){
        $pos_sale_id = 0;
        $this->db->select("*");
        $this->db->from('pos_sales');       
        $this->db->where( array('is_void' => 0, 'is_held' => 0, 'is_completed' => 0, 'system_user_id' => $this->session->userdata('pos_system_user_id'), 'outlet_id' => $this->session->userdata('pos_outlet_id')));
        $this->db->order_by("pos_sale_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {
            $records = $query->result();
            
            foreach ($records as $row) {
                $pos_sale_id = $row->pos_sale_id;
            }

            //CHECK ADDED ITEMS
            $this->db->select("*");
            $this->db->from('pos_sale_details');       
            $this->db->where( array('pos_sale_id' => $pos_sale_id));
            $query = $this->db->get();
            $record_count = $query->num_rows();

            if ($record_count > 0) {
                $arr_return = array('res' => true,'dt' => 'Items Available.', 'data' => $records);
            } else {
                $arr_return = array('res' => false,'dt' => 'Please add items to the sale first before making a payment.', 'data' => '');
            }
        } else {
            $arr_return = array('res' => false,'dt' => 'Please add items to the sale first before making a payment.', 'data' => '');
        }
        return $arr_return;
    }

    function edit_sale_make_payment_valid($pos_sale_id){

        $pos_sale = $this->get_pos_sale($pos_sale_id);

        //CHECK ADDED ITEMS
        $this->db->select("*");
        $this->db->from('pos_sale_details');       
        $this->db->where( array('pos_sale_id' => $pos_sale_id));
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {
            $arr_return = array('res' => true,'dt' => 'Items Available.', 'data' => $pos_sale);
        } else {
            $arr_return = array('res' => false,'dt' => 'Please add items to the sale first before making a payment.', 'data' => '');
        }

        return $arr_return;

    }

    function submit_sale_payment(){
        $pos_sale_id = $this->input->post('pos_sale_id');
        $pos_sale_number = $this->input->post('pos_sale_number');
        $payment_amount = $this->input->post('payment_amount');
        $reference_number = $this->input->post('reference_number');

        $payment_method = $this->input->post('payment_method');

        if ($payment_method == 'MPESA') {
            $this->db->select("*");
            $this->db->from('paybill_payments');       
            $this->db->where( array('bill_reference_number' => $pos_sale_number, 'transaction_amount' => $payment_amount, 'transaction_completed' => 0));
            $query = $this->db->get();
            $record_count = $query->num_rows();

            if ($record_count > 0) {
                $records = $query->result();

                foreach ($records as $row) {
                    $paybill_payment_id = $row->paybill_payment_id;

                    $data = array(
                        'pos_sale_id' => $pos_sale_id,
                        'payment_amount' => $payment_amount,
                        'payment_method' => $payment_method,
                        'reference_number' => $reference_number,
                        'payment_note' => $this->input->post('payment_note'),
                        'paybill_payment_id' => $paybill_payment_id,
                        'system_user_id' => $this->session->userdata('pos_system_user_id')
                    );
                    $res = $this->db->insert('pos_payments', $data);
                    $pos_payment_id = $this->db->insert_id();

                    //UPDATE PAYBILL PAYMENTS TABLE
                    if ($res) {
                        $data = array(
                            'pos_sale_id' => $pos_sale_id,
                            'transaction_completed' => 1
                        );
                        $this->db->where( array('paybill_payment_id' => $paybill_payment_id));
                        $this->db->update('paybill_payments', $data);
                    }
                }
                $arr_return = array('res' => true,'dt' => 'Payment submitted successfully');
            } else {
               $this->db->select("*");
                $this->db->from('paybill_payments');       
                $this->db->where( array('transaction_id' => $reference_number, 'transaction_amount' => $payment_amount, 'transaction_completed' => 0));
                $query = $this->db->get();
                $record_count = $query->num_rows();

                if ($record_count > 0) {
                    $records = $query->result();

                    foreach ($records as $row) {
                        $paybill_payment_id = $row->paybill_payment_id;

                        $data = array(
                            'pos_sale_id' => $pos_sale_id,
                            'payment_amount' => $payment_amount,
                            'payment_method' => $payment_method,
                            'reference_number' => $reference_number,
                            'payment_note' => $this->input->post('payment_note'),
                            'paybill_payment_id' => $paybill_payment_id,
                            'system_user_id' => $this->session->userdata('pos_system_user_id')
                        );
                        $res = $this->db->insert('pos_payments', $data);
                        $pos_payment_id = $this->db->insert_id();

                        //UPDATE PAYBILL PAYMENTS TABLE
                        if ($res) {
                            $data = array(
                                'pos_sale_id' => $pos_sale_id,
                                'transaction_completed' => 1
                            );
                            $this->db->where( array('paybill_payment_id' => $paybill_payment_id));
                            $this->db->update('paybill_payments', $data);
                        }
                    }
                    $arr_return = array('res' => true,'dt' => 'Payment submitted successfully');
                } else {
                    $arr_return = array('res' => false,'dt' => 'MPESA transaction not found. Please confirm and try again.');
                }
            }

        } else {
            $data = array(
                'pos_sale_id' => $pos_sale_id,
                'payment_amount' => $payment_amount,
                'payment_method' => $payment_method,
                'reference_number' => $reference_number,
                'payment_note' => $this->input->post('payment_note'),
                'system_user_id' => $this->session->userdata('pos_system_user_id')
            );
            $res = $this->db->insert('pos_payments', $data);
            $pos_payment_id = $this->db->insert_id();

            if ($res) {
                $arr_return = array('res' => true,'dt' => 'Payment submitted successfully');
            } else {
                $arr_return = array('res' => false,'dt' => 'There was a problem submitting the payment. Please try again.');
            }
        }

        //UPDATE TOTAL PAID
        $this->calculate_sale_payments($pos_sale_id);
        $this->calculate_sale_total($pos_sale_id);


        return $arr_return;
    }

    function calculate_sale_payments($pos_sale_id){
        $sale_total_paid = 0;
        $sale_total = 0;
        $change_amount = 0;

        $this->db->select("*");
        $this->db->from('pos_payments');       
        $this->db->where( array('pos_sale_id' => $pos_sale_id, 'is_void' => 0));
        $pos_payments = $this->db->get()->result();

        foreach ($pos_payments as $row) {
            $sale_total_paid = $sale_total_paid + $row->payment_amount;
        }

        $this->db->select("*");
        $this->db->from('pos_sales');       
        $this->db->where( array('pos_sale_id' => $pos_sale_id));
        $pos_sale = $this->db->get()->result();

        foreach ($pos_sale as $row) {
            $sale_total = $row->total_sale;
        }

        if ($sale_total_paid > $sale_total) {
            $change_amount = $sale_total_paid - $sale_total;
        } else {
            $change_amount = 0;
        }

        $data = array(
            'total_paid' => $sale_total_paid,
            'change_amount' => $change_amount
        );
        $this->db->where( array('pos_sale_id' => $pos_sale_id));
        $this->db->update('pos_sales', $data);

    }

    function get_sale_payment($pos_payment_id) {
        $this->db->select("pos_payments.*, pos_sales.pos_sale_number, system_users.first_name, system_users.last_name");
        $this->db->from('pos_payments');  
        $this->db->join('pos_sales', 'pos_sales.pos_sale_id = pos_payments.pos_sale_id', 'left outer');
        $this->db->join('system_users', 'system_users.system_user_id = pos_payments.system_user_id', 'left outer');     
        $this->db->where( array('pos_payments.pos_payment_id' => $pos_payment_id));
        return $this->db->get()->result();
    }

    function sale_payment_void_valid($pos_payment_id) {
        $is_void = true;

        $pos_payment = $this->get_sale_payment($pos_payment_id);

        foreach ($pos_payment as $row) {
            if ($row->is_void == 0) {
                $is_void = false;
            } else {
                $is_void = true;
            }
        }        // $query = $this->db->get();
        // $record_count = $query->num_rows();
        // $records = $query->result();

        // $i = 0;
        // foreach($records as $row){
        //     $records[$i]->details = $this->get_pos_sale_details($row->pos_sale_id);
        //     $records[$i]->payments = $this->get_pos_sale_payments($row->pos_sale_id);
        //     $i++;
        // }

        // $arr_return = array('records' => $records, 'record_count' => $record_count);


        if ($is_void == false) {
            $arr_return = array('res' => true,'dt' => 'Not Void.');
        } else {
            $arr_return = array('res' => false,'dt' => 'This payment has already been voided.');
        }
        return $arr_return;
    }

    function submit_void_sale_payment(){
        $pos_payment_id = $this->input->post('pos_payment_id');
        $pos_sale_id = 0;
        $paybill_payment_id = 0;
        $pos_sale_number = '';

        $payment_method = '';
        $payment_date = '';
        $payment_reference = '';
        $payment_amount = '';
        $payment_user = '';

        $pos_payment = $this->get_sale_payment($pos_payment_id);

        foreach ($pos_payment as $row) {
            $pos_sale_id = $row->pos_sale_id;
            $pos_sale_number = $row->pos_sale_number;
            $payment_method = $row->payment_method;
            $payment_date = date('d M, Y', strtotime($row->created_on));
            $payment_reference = $row->reference_number;
            $payment_amount = number_format($row->payment_amount,2);
            $payment_user = $row->first_name . ' ' . $row->last_name;
            $paybill_payment_id = $row->paybill_payment_id;
        }

        $void_date = date('Y-m-d H:i:s');
        $void_reason = $this->input->post('void_reason');

        $data = array(
            'is_void' => 1,
            'void_reason' => $void_reason,
            'void_system_user_id' => $this->session->userdata('pos_system_user_id'),
            'void_date' => $void_date
        );
        $this->db->where( array('pos_payment_id' => $pos_payment_id));
        $void = $this->db->update('pos_payments', $data);

        if ($void) {

            //CHECK IF MPESA
            if ($payment_method = 'MPESA') {
                $data = array(
                    'transaction_completed' => 0,
                    'pos_sale_id' => 0
                );
                $this->db->where( array('paybill_payment_id' => $paybill_payment_id));
                $this->db->update('paybill_payments', $data);
            }

            //NOTIFICATION
            $data = array(
                'notification_type' => 'POS Sale Payment Voided',
                'notification_ref_id' => $pos_sale_id,
                'notification_details' => 'Sale Order #: <b>' . $pos_sale_number . '</b>; Payment Date: <b>' . $payment_date . '</b>; Payment Method: <b>' . $payment_method . '</b>; Ref #: <b>' . $payment_reference . '</b>; Payment Amount: <b>' . $payment_amount . '</b>; User: <b>' . $payment_user . '</b>; Void Reason: <b>' . $void_reason . '</b>; Void User: <b>' . $this->session->userdata('pos_user_first_name') . ' ' . $this->session->userdata('pos_user_last_name') . '</b>; Void Date: <b>' . $void_date . '</b>',
                'notification_ref_link' => 'pos/sales/view/' . $pos_sale_id
            );
            $this->db->insert('notifications',$data);

            $this->calculate_sale_payments($pos_sale_id);
            $this->calculate_sale_total($pos_sale_id);

             $arr_return = array('res' => true,'dt' => 'Payment voided successfully.');
        } else {
            $arr_return = array('res' => false,'dt' => 'There was an error trying to void this payment. Please try again.');
        }

        return $arr_return;
    }

    function sale_payment_modify_valid($pos_payment_id){
        $is_void = false;
        $pos_sale_id = 0;

        $pos_payment = $this->get_sale_payment($pos_payment_id);

        foreach ($pos_payment as $row) {
            $pos_sale_id = $row->pos_sale_id;

            if ($row->is_void == 0) {
                $is_void = false;
            } else {
                $is_void = true;
            }
        }

        if ($is_void == false) {

            $pos_sale = $this->get_pos_sale($pos_sale_id);

            $arr_return = array('res' => true,'dt' => 'Not Void.', 'data' => $pos_payment, 'sale' => $pos_sale);
        } else {
            $arr_return = array('res' => false,'dt' => 'This payment has already been voided, hence cannot be modified.', 'data' => '', 'sale' => '');
        }
        return $arr_return;
    }

    function submit_modify_sale_payment() {
        $pos_payment_id = $this->input->post('pos_payment_id');
        $pos_sale_id = $this->input->post('pos_sale_id');
        $pos_sale_number = $this->input->post('pos_sale_number');

        $payment_method = $this->input->post('payment_method');
        $old_payment_method = $this->input->post('txt_payment_method');
        $payment_amount = $this->input->post('payment_amount');
        $reference_number = $this->input->post('reference_number');

        if ($old_payment_method == 'MPESA') {
            $data = array(
                'reference_number' => $reference_number,
                'payment_note' => $this->input->post('payment_note')
            );
            $this->db->where( array('pos_payment_id' => $pos_payment_id));
            $res = $this->db->update('pos_payments', $data);

            if ($res) {
                $arr_return = array('res' => true,'dt' => 'Payment updated successfully');
            } else {
                $arr_return = array('res' => false,'dt' => 'Could not update payment successfully. Please confirm and try again.');
            }
        } else {
            if ($payment_method == 'MPESA') {
                $this->db->select("*");
                $this->db->from('paybill_payments');       
                $this->db->where( array('bill_reference_number' => $pos_sale_number, 'transaction_amount' => $payment_amount, 'transaction_completed' => 0));
                $query = $this->db->get();
                $record_count = $query->num_rows();

                if ($record_count > 0) {
                    $records = $query->result();

                    foreach ($records as $row) {
                        $paybill_payment_id = $row->paybill_payment_id;

                        $data = array(
                            'payment_amount' => $payment_amount,
                            'payment_method' => $payment_method,
                            'reference_number' => $reference_number,
                            'payment_note' => $this->input->post('payment_note'),
                            'paybill_payment_id' => $paybill_payment_id
                        );
                        $this->db->where( array('pos_payment_id' => $pos_payment_id));
                        $res = $this->db->update('pos_payments', $data);
                        //$res = $this->db->insert('pos_payments', $data);
                        //$pos_payment_id = $this->db->insert_id();

                        //UPDATE PAYBILL PAYMENTS TABLE
                        if ($res) {
                            $data = array(
                                'pos_sale_id' => $pos_sale_id,
                                'transaction_completed' => 1
                            );
                            $this->db->where( array('paybill_payment_id' => $paybill_payment_id));
                            $this->db->update('paybill_payments', $data);
                        }
                    }
                    $arr_return = array('res' => true,'dt' => 'Payment updated successfully');
                } else {
                   $this->db->select("*");
                    $this->db->from('paybill_payments');       
                    $this->db->where( array('transaction_id' => $reference_number, 'transaction_amount' => $payment_amount, 'transaction_completed' => 0));
                    $query = $this->db->get();
                    $record_count = $query->num_rows();

                    if ($record_count > 0) {
                        $records = $query->result();

                        foreach ($records as $row) {
                            $paybill_payment_id = $row->paybill_payment_id;

                            $data = array(
                                'payment_amount' => $payment_amount,
                                'payment_method' => $payment_method,
                                'reference_number' => $reference_number,
                                'payment_note' => $this->input->post('payment_note'),
                                'paybill_payment_id' => $paybill_payment_id
                            );
                            $this->db->where( array('pos_payment_id' => $pos_payment_id));
                            $res = $this->db->update('pos_payments', $data);

                            //UPDATE PAYBILL PAYMENTS TABLE
                            if ($res) {
                                $data = array(
                                    'pos_sale_id' => $pos_sale_id,
                                    'transaction_completed' => 1
                                );
                                $this->db->where( array('paybill_payment_id' => $paybill_payment_id));
                                $this->db->update('paybill_payments', $data);
                            }
                        }
                        $arr_return = array('res' => true,'dt' => 'Payment updated successfully');
                    } else {
                        $arr_return = array('res' => false,'dt' => 'MPESA transaction not found. Please confirm and try again.');
                    }
                }
            } else {
                $data = array(
                    'payment_amount' => $payment_amount,
                    'payment_method' => $payment_method,
                    'reference_number' => $reference_number,
                    'payment_note' => $this->input->post('payment_note')
                );
                $this->db->where( array('pos_payment_id' => $pos_payment_id));
                $res = $this->db->update('pos_payments', $data);

                 if ($res) {
                    $arr_return = array('res' => true,'dt' => 'Payment updated successfully');
                } else {
                    $arr_return = array('res' => false,'dt' => 'Could not update payment successfully. Please confirm and try again.');
                }
            }
        }

        $this->calculate_sale_payments($pos_sale_id);
        $this->calculate_sale_total($pos_sale_id);

        return $arr_return;
    }

    function sale_complete_valid(){
        $pos_sale_id = 0;
        $sale_type = '';
        $customer_id = 0;

        $this->db->select("*");
        $this->db->from('pos_sales');       
        $this->db->where( array('is_void' => 0, 'is_held' => 0, 'is_completed' => 0, 'system_user_id' => $this->session->userdata('pos_system_user_id'), 'outlet_id' => $this->session->userdata('pos_outlet_id')));
        $this->db->order_by("pos_sale_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {
            $records = $query->result();
            
            foreach ($records as $row) {
                $pos_sale_id = $row->pos_sale_id;
                $sale_type = $row->sale_type;
                $customer_id = $row->customer_id;
            }

            if ($sale_type == 'CREDIT SALE' && $customer_id == 0){
                $arr_return = array('res' => false,'dt' => 'Please assign a customer to this Credit Sale.', 'data' => '');
            } else {
                //CHECK ADDED ITEMS
                $this->db->select("*");
                $this->db->from('pos_sale_details');       
                $this->db->where( array('pos_sale_id' => $pos_sale_id));
                $query = $this->db->get();
                $record_count = $query->num_rows();

                if ($record_count > 0) {
                    $arr_return = array('res' => true,'dt' => 'Items Available.', 'data' => $records);
                } else {
                    $arr_return = array('res' => false,'dt' => 'Please add items to the sale first before completing it.', 'data' => '');
                }
            }
        } else {
            $arr_return = array('res' => false,'dt' => 'Please add items to the sale first before completing it.', 'data' => '');
        }
        return $arr_return;
    }

    function complete_sale($pos_sale_id) {
        $data = array(
            'is_completed' => 1,
            'created_on' => date('Y-m-d_H:i:s')
        );
        $this->db->where( array('pos_sale_id' => $pos_sale_id));
        $res = $this->db->update('pos_sales', $data);

         if ($res) {
            $arr_return = array('res' => true,'dt' => 'Sale completed successfully');
        } else {
            $arr_return = array('res' => false,'dt' => 'Could not complete sale successfully. Please try again.');
        }
        return $arr_return;
    }

    function sale_hold_valid(){
        $pos_sale_id = 0;
        $this->db->select("*");
        $this->db->from('pos_sales');       
        $this->db->where( array('is_void' => 0, 'is_held' => 0, 'is_completed' => 0, 'system_user_id' => $this->session->userdata('pos_system_user_id'), 'outlet_id' => $this->session->userdata('pos_outlet_id')));
        $this->db->order_by("pos_sale_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {
            $records = $query->result();
            
            foreach ($records as $row) {
                $pos_sale_id = $row->pos_sale_id;
            }

            //CHECK ADDED ITEMS
            $this->db->select("*");
            $this->db->from('pos_sale_details');       
            $this->db->where( array('pos_sale_id' => $pos_sale_id));
            $query = $this->db->get();
            $record_count = $query->num_rows();

            if ($record_count > 0) {
                $arr_return = array('res' => true,'dt' => 'Items Available.', 'data' => $records);
            } else {
                $arr_return = array('res' => false,'dt' => 'Please add items to the sale first before holding it.', 'data' => '');
            }
        } else {
            $arr_return = array('res' => false,'dt' => 'Please add items to the sale first before holding it.', 'data' => '');
        }
        return $arr_return;
    }

    function hold_sale($pos_sale_id) {
        $data = array(
            'is_held' => 1
        );
        $this->db->where( array('pos_sale_id' => $pos_sale_id));
        $res = $this->db->update('pos_sales', $data);

         if ($res) {
            $arr_return = array('res' => true,'dt' => 'Sale held successfully');
        } else {
            $arr_return = array('res' => false,'dt' => 'Could not hold sale successfully. Please try again.');
        }
        return $arr_return;
    }

    function sale_cancel_valid(){
        $pos_sale_id = 0;
        $this->db->select("*");
        $this->db->from('pos_sales');       
        $this->db->where( array('is_void' => 0, 'is_held' => 0, 'is_completed' => 0, 'system_user_id' => $this->session->userdata('pos_system_user_id'), 'outlet_id' => $this->session->userdata('pos_outlet_id')));
        $this->db->order_by("pos_sale_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {
            $records = $query->result();
            
            foreach ($records as $row) {
                $pos_sale_id = $row->pos_sale_id;
            }

            //CHECK ADDED ITEMS
            $this->db->select("*");
            $this->db->from('pos_sale_details');       
            $this->db->where( array('pos_sale_id' => $pos_sale_id));
            $query = $this->db->get();
            $record_count = $query->num_rows();

            if ($record_count > 0) {
                $arr_return = array('res' => true,'dt' => 'Items Available.', 'data' => $records);
            } else {
                $arr_return = array('res' => false,'dt' => 'This sale cannot be cancelled since there are no items added to it.', 'data' => '');
            }
        } else {
            $arr_return = array('res' => false,'dt' => 'This sale cannot be cancelled since there are no items added to it.', 'data' => '');
        }
        return $arr_return;
    }

    function cancel_sale($pos_sale_id) {
        $data = array(
            'is_void' => 1,
            'void_reason' => 'Cancelled by user',
            'void_system_user_id' => $this->session->userdata('pos_system_user_id'),
            'void_date' => date('Y-m-d H:i:s')
        );
        $this->db->where( array('pos_sale_id' => $pos_sale_id));
        $res = $this->db->update('pos_sales', $data);

         if ($res) {
            $pos_sale_details = $this->get_pos_sale_details($pos_sale_id);

            foreach ($pos_sale_details as $row) {

                $product_id = $row->product_id;
                $product_variation_id = $row->product_variation_id;
                $quantity = $row->base_unit_quantity;
                $pos_sale_detail_id = $row->pos_sale_detail_id;
                $outlet_id = $row->outlet_id;

                $available_stock = 0;

                $this->db->select("*");
                $this->db->from('outlet_products');
                $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                $outlet_product = $this->db->get()->result();
                foreach ($outlet_product as $row) {
                    $available_stock = $row->available_stock;
                }

                $data = array(
                    'available_stock' =>  $available_stock + $quantity
                );
                $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                $this->db->update('outlet_products', $data);

                //STOCK TRACKER
                $this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $pos_sale_detail_id, 'transaction_description' => 'POS Sale'));
                $this->db->delete('stock_tracker');


            }

            $arr_return = array('res' => true,'dt' => 'Sale cancelled successfully');
        } else {
            $arr_return = array('res' => false,'dt' => 'Could not cancel sale successfully. Please try again.');
        }
        return $arr_return;
    }

    function submit_send_sale_order_via_email() {

        $pos_sale_id = $this->input->post('pos_sale_id');

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

            $attachment = $this->generate_order_pdf($pos_sale_id);
            $mail->AddStringAttachment($attachment, 'Bethany House Sale Order-'. $pos_sale_id . '.pdf', 'base64', 'application/pdf');
            
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

    function generate_order_pdf ($pos_sale_id) {
        $pos_sale = $this->get_pos_sale($pos_sale_id);
        $pos_sale_details = $this->get_pos_sale_details($pos_sale_id);
        $default_currency = $this->currencies_model->get_default_currency();
        $store_information = $this->get_store_information();

        $attachment = '';

        foreach ($pos_sale as $row) {

            $payment_balance = $row->total_sale - $row->total_paid;

            $filename='Bethany House Sales Order - '.$row->pos_sale_number.'.pdf';

            // create new PDF document
            $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);


            // set document information
            $pdf->SetCreator(PDF_CREATOR);
            $pdf->SetAuthor('Bethany House');
            $pdf->SetTitle('Bethany House Sales Order - '.$row->pos_sale_number);
            $pdf->SetSubject('Bethany House Sales Order - '.$row->pos_sale_number);
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
                <td rowspan="2" width="136"><img src="' . $store_logo . '"  width="200px"></td>
                <td rowspan="2" width="200">';

                foreach ($store_information as $row2){
                    $txt = $txt . '<b>' . $row2->store_name . '</b><br />
                    <b>Phone:</b> ' . $row2->phone_number . '<br />
                    <b>Phone2:</b> ' . $row2->mobile_number . '<br />
                    <b>Address:</b> ' . $row2->physical_address . '<br />
                    <b>Email:</b> ' . $row2->email_address;
                }
                $txt = $txt . '</td>
                <td width="112"><b>Sale No:</b><br/>' . $row->pos_sale_number . '</td>
                <td width="112"><b>Date:</b><br/>' . date('d M, Y', strtotime($row->created_on)) . '</td>
                <td width="113"><b>Status:</b><br/>';
                    if ($row->is_void == 1) {
                        $txt = $txt . 'VOID';
                    } else {
                        if ($payment_balance == $row->total_sale){
                            $txt = $txt . 'UNPAID';
                        } elseif ($payment_balance > 0){
                            $txt = $txt . 'PARTIALLY PAID';
                        } else {
                            $txt = $txt . 'PAID';
                        }
                    }
                $txt = $txt . '</td>
            </tr>
            <tr>';
            if ($row->customer_id == 0){ $customer_name =  $row->customer_name; } else { $customer_name = $row->first_name . ' ' . $row->last_name; }

                $txt = $txt . '<td colspan="3"><b>Customer</b><br/>' . $customer_name . '</td>
            </tr></thead></table>';


            $pdf->writeHTML($txt, true, false, false, false, '');

            $txt = '<table border="1" cellpadding="5" cellspacing="0">
                <thead>
                    <tr>
                        <td width="30"><b>#</b></td>
                        <td width="160"><b>Item Description</b></td>
                        <td width="80"><b>Unit Cost</b></td>
                        <td width="50"><b>Qty</b></td>
                        <td width="70"><b>Tax</b></td>
                        <td width="65"><b>Tax Amt</b></td>
                        <td width="70"><b>Disc.</b></td>
                        <td width="68"><b>Disc. Amt</b></td>
                        <td width="80"><b>Amount</b></td>
                    </tr>
                </thead>
                <tbody>';
            $count = 1;
            foreach ($pos_sale_details as $row2){
                $txt = $txt . '<tr>
                    <td width="30">' . $count . '</td>
                    <td width="160">'. $row2->product_name . '<br><i>SKU: ' . $row2->product_sku_code . '</i></td>
                    <td width="80">' . $default_currency . ' ' . number_format($row2->unit_price,2) . '</td>
                    <td width="50">' . number_format($row2->quantity) . '</td>
                    <td width="70">' . number_format($row2->tax_rate_value,2) . '%<br><i>' . $row2->tax_rate_name . ' [' . $row2->tax_rate_code . ']</i></td>
                    <td width="65">' . $default_currency . ' ' . number_format($row2->unit_tax,2) . '</td>
                    <td width="70">' . $row2->discount_type . '<br><i>[' . number_format($row2->discount_value,2) . ']</i></td>
                    <td width="68">' . $default_currency . ' ' . number_format($row2->discount_amount,2) . '</td>
                    <td width="80">' . $default_currency . ' ' . number_format($row2->sub_total,2) . '</td>
                </tr>';
                $count++;
            }
            $txt = $txt . '<tr>
                <td colspan="8" align="right"><b>Total Qty</b></td>
                <td><b>' . number_format($row->total_quantity) . '</b></td>
            </tr>';
            $txt = $txt . '<tr>
                <td colspan="8" align="right"><b>Subtotal</b></td>
                <td><b>' . $default_currency . ' ' . number_format($row->sub_total,2) . '</b></td>
            </tr>';
            $txt = $txt . '<tr>
                <td colspan="8" align="right"><b>Discount On All</b></td>
                <td><b>' . $default_currency . ' ' . number_format($row->overall_discount,2) . '</b></td>
            </tr>';
            $txt = $txt . '<tr>
                <td colspan="8" align="right"><b>Delivery Fee</b></td>
                <td><b>' . $default_currency . ' ' . number_format($row->delivery_fee,2) . '</b></td>
            </tr>';
            $txt = $txt . '<tr>
                <td colspan="8" align="right"><b>Grand Total</b></td>
                <td><b>' . $default_currency . ' ' . number_format($row->total_sale,2) . '</b></td>
            </tr>';
            $txt = $txt . '<tr>
                <td colspan="8" align="right"><b>Paid Amount</b></td>
                <td><b>' . $default_currency . ' ' . number_format($row->total_paid,2) . '</b></td>
            </tr>';
            if ($payment_balance < 0) {
                $txt = $txt . '<tr>
                    <td colspan="8" align="right"><b>Change</b></td>
                    <td><b>' . $default_currency . ' ' . number_format(($payment_balance * -1),2) . '</b></td>
                </tr>';
            } elseif ($payment_balance > 0) {
                $txt = $txt . '<tr>
                    <td colspan="8" align="right"><b>Balance</b></td>
                    <td><b>' . $default_currency . ' ' . number_format($payment_balance,2) . '</b></td>
                </tr>';
            }
            
           $txt = $txt . '</tbody></table>';

            $pdf->writeHTML($txt, true, false, false, false, '');

            $txt = '<table border="1" cellpadding="5" cellspacing="0">
                    <tr>
                        <td colspan="2"><b>Note:</b> '. $row->comments . '</td>
                    </tr>
                    <tr>
                        <td colspan="2"><b>Terms & Conditions</b><br>1) No warranty for damaged or burnt goods.<br />
                            2) For warranty/repairs/replacement bring sale order copy.<br />
                            3) Goods once sold will not be taken back.<br />
                            4) Warranty at the sole discretion of the respective service center.<br />
                            5) Cheque bouncing attracts an unconditional fine of KES. 5,000.00
                        </td>
                    </tr>
                    <tr>
                        <td><b>Customer Signature</b><br><br><br></td>
                        <td><b>Authorised Signatory</b><br><br><br></td>
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

    function sale_void_valid($pos_sale_id){
        $is_void = true;

        $pos_sale = $this->get_pos_sale($pos_sale_id);

        foreach ($pos_sale as $row) {
            if ($row->is_void == 0) {
                $is_void = false;
            } else {
                $is_void = true;
            }
        }
                // $query = $this->db->get();
        // $record_count = $query->num_rows();
        // $records = $query->result();

        // $i = 0;
        // foreach($records as $row){
        //     $records[$i]->details = $this->get_pos_sale_details($row->pos_sale_id);
        //     $records[$i]->payments = $this->get_pos_sale_payments($row->pos_sale_id);
        //     $i++;
        // }

        // $arr_return = array('records' => $records, 'record_count' => $record_count);


        if ($is_void == false) {
            $arr_return = array('res' => true,'dt' => 'Not Void.');
        } else {
            $arr_return = array('res' => false,'dt' => 'This sale has already been voided.');
        }
        return $arr_return;

    }

    function submit_void_pos_sale(){
        
        $pos_sale_id = $this->input->post('pos_sale_id');

        $void_date = date('Y-m-d H:i:s');
        $void_reason = $this->input->post('void_reason');

        $data = array(
            'is_void' => 1,
            'void_reason' => $void_reason,
            'void_system_user_id' => $this->session->userdata('pos_system_user_id'),
            'void_date' => $void_date
        );
        $this->db->where( array('pos_sale_id' => $pos_sale_id));
        $void = $this->db->update('pos_sales', $data);

        if ($void) {

            $pos_sale_number = '';
            $pos_sale = $this->get_pos_sale($pos_sale_id);
            foreach ($pos_sale as $row) {
                $pos_sale_number = $row->pos_sale_number;
            }

            //RETURN STOCK
            $pos_sale_details = $this->get_pos_sale_details($pos_sale_id);

            foreach ($pos_sale_details as $row) {

                $product_id = $row->product_id;
                $product_variation_id = $row->product_variation_id;
                $quantity = $row->base_unit_quantity;
                $pos_sale_detail_id = $row->pos_sale_detail_id;
                $outlet_id = $row->outlet_id;

                $available_stock = 0;

                $this->db->select("*");
                $this->db->from('outlet_products');
                $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                $outlet_product = $this->db->get()->result();
                foreach ($outlet_product as $row) {
                    $available_stock = $row->available_stock;
                }

                $data = array(
                    'available_stock' =>  $available_stock + $quantity
                );
                $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                $this->db->update('outlet_products', $data);

                //STOCK TRACKER
                $this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $pos_sale_detail_id, 'transaction_description' => 'POS Sale'));
                $this->db->delete('stock_tracker');
            }

            $get_pos_sale_payments = $this->get_pos_sale_payments($pos_sale_id);

            $data = array(
                'is_void' => 1,
                'void_reason' => 'Sale Order Cancelled: ' . $this->input->post('void_reason'),
                'void_system_user_id' => $this->session->userdata('pos_system_user_id'),
                'void_date' => date('Y-m-d H:i:s')
            );
            $this->db->where( array('pos_sale_id' => $pos_sale_id, 'is_void' => 0));
            $this->db->update('pos_payments', $data);
            
            //NOTIFICATION
            $data = array(
                'notification_type' => 'POS Sale Order Voided',
                'notification_ref_id' => $pos_sale_id,
                'notification_details' => 'Sale Order <b>#' . $pos_sale_number . '</b> has been voided on  <b>' . $void_date . '</b> by <b>' . $this->session->userdata('pos_user_first_name') . ' ' . $this->session->userdata('pos_user_last_name') . '</b>. Void Reason: <b>' . $void_reason . '</b>',
                'notification_ref_link' => 'pos/sales/view/' . $pos_sale_id
            );
            $this->db->insert('notifications',$data);

            $arr_return = array('res' => true,'dt' => 'Sale voided successfully.');
        } else {
            $arr_return = array('res' => false,'dt' => 'There was an error trying to void this sale. Please try again.');
        }

        return $arr_return;

    }

    function resume_held_sale($pos_sale_id){

        $pos_sale = $this->get_pos_sale($pos_sale_id);
        $is_void = 0;
        $is_held = 1;
        $is_completed = 0;
        $system_user_id = 0;

        foreach ($pos_sale as $row) {
            $is_void = $row->is_void;
            $is_held = $row->is_held;
            $is_completed = $row->is_completed;
            $system_user_id = $row->system_user_id;
        }

        if ($is_void == 1) {
            $arr_return = array('res' => false,'dt' => 'This sale has already been marked as void and cannot be resumed.');
        } elseif ($is_completed == 1) {
            $arr_return = array('res' => false,'dt' => 'This sale has already been marked as complete and cannot be resumed.');
        } elseif ($system_user_id != $this->session->userdata('pos_system_user_id')) {
            $arr_return = array('res' => false,'dt' => 'Sorry. Only the owner of this transaction can resume it.');
        } else {

            //HOLD OTHER ACTIVE SALES
            $data = array(
                'is_held' => 1
            );
            $this->db->where( array('is_void' => 0, 'is_held' => 0, 'is_completed' => 0, 'system_user_id' => $this->session->userdata('pos_system_user_id'), 'outlet_id' => $this->session->userdata('pos_outlet_id')));
            $this->db->update('pos_sales', $data);

            //RESUME THIS SALE
            $data = array(
                'is_held' => 0
            );
            $this->db->where( array('pos_sale_id' => $pos_sale_id));
            $update = $this->db->update('pos_sales', $data);

            if ($update) {
                $arr_return = array('res' => true,'dt' => 'Resumed successfully.');
            } else {
                $arr_return = array('res' => false,'dt' => 'Could not resume sale successfully. Please try again.');
            }
        }

        return $arr_return;
    }

    function cancel_held_sale($pos_sale_id){
        $pos_sale = $this->get_pos_sale($pos_sale_id);
        $system_user_id = 0;

        foreach ($pos_sale as $row) {
            $system_user_id = $row->system_user_id;
        }

        if ($system_user_id != $this->session->userdata('pos_system_user_id')) {
            $arr_return = array('res' => false,'dt' => 'Sorry. Only the owner of this transaction can cancel it.');
        } else {
            $data = array(
                'is_void' => 1,
                'void_reason' => 'Cancelled by user',
                'void_system_user_id' => $this->session->userdata('pos_system_user_id'),
                'void_date' => date('Y-m-d H:i:s')
            );
            $this->db->where( array('pos_sale_id' => $pos_sale_id));
            $res = $this->db->update('pos_sales', $data);

             if ($res) {
                $pos_sale_details = $this->get_pos_sale_details($pos_sale_id);

                foreach ($pos_sale_details as $row) {
                    $product_id = $row->product_id;
                    $product_variation_id = $row->product_variation_id;
                    $quantity = $row->base_unit_quantity;
                    $pos_sale_detail_id = $row->pos_sale_detail_id;
                    $outlet_id = $row->outlet_id;

                    $available_stock = 0;

                    $this->db->select("*");
                    $this->db->from('outlet_products');
                    $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                    $outlet_product = $this->db->get()->result();
                    foreach ($outlet_product as $row) {
                        $available_stock = $row->available_stock;
                    }

                    $data = array(
                        'available_stock' =>  $available_stock + $quantity
                    );
                    $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                    $this->db->update('outlet_products', $data);

                    //STOCK TRACKER
                    $this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $pos_sale_detail_id, 'transaction_description' => 'POS Sale'));
                    $this->db->delete('stock_tracker');
                }

                $arr_return = array('res' => true,'dt' => 'Sale cancelled successfully');
            } else {
                $arr_return = array('res' => false,'dt' => 'Could not cancel sale successfully. Please try again.');
            }
        }
        return $arr_return;
    }

    //////// SALES RETURNS ///////
    function get_sales_returns_list() {

        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

        $this->db->select("psr.*, c.first_name, c.last_name, c.email_address, c.phone_number, c.credit_limit, c.opening_balance, c.loyalty_enrolled, c.loyalty_number, c.loyalty_enrollment_date, c.profile_picture, c.profile_picture_thumb, su.first_name AS 'system_user_first_name', su.last_name AS 'system_user_last_name'");
        $this->db->from('pos_sales_returns psr');     
        $this->db->join('customers c', 'c.customer_id = psr.customer_id', 'left outer');  
        $this->db->join('system_users su', 'su.system_user_id = psr.system_user_id', 'left outer');

        $this->db->where( array('psr.is_held' => 0, 'psr.is_completed' => 1, 'psr.outlet_id' => $this->session->userdata('pos_outlet_id')));

        if ($this->session->userdata('pos_user_is_super_admin') != 1) {
            $this->db->where( array('psr.system_user_id' => $this->session->userdata('pos_system_user_id')));
        }

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(psr.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(psr.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }

        $this->db->order_by("psr.pos_sales_return_id", "desc");
        
        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        return $records;
    }

    //GET PENDING SALES RETURN
    function get_pending_sales_return() {
        $this->db->select("psr.*, c.first_name, c.last_name, c.email_address, c.phone_number, c.credit_limit, c.opening_balance, c.loyalty_enrolled, c.loyalty_number, c.loyalty_enrollment_date, c.profile_picture, c.profile_picture_thumb");
        $this->db->from('pos_sales_returns psr');     
        $this->db->join('customers c', 'c.customer_id = psr.customer_id', 'left outer');  
        $this->db->where( array('psr.is_void' => 0, 'psr.is_held' => 0, 'psr.is_completed' => 0, 'psr.system_user_id' => $this->session->userdata('pos_system_user_id'), 'psr.outlet_id' => $this->session->userdata('pos_outlet_id')));
        $this->db->order_by("psr.pos_sales_return_id", "desc");
        $this->db->limit(1);
        
        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        //$product_categories = $parent_category->result();
        $i = 0;
        foreach($records as $row){
            $records[$i]->details = $this->get_pos_sales_return_details($row->pos_sales_return_id);
            // $records[$i]->payments = $this->get_pos_sale_payments($row->pos_sale_id);
            $i++;
        }

        $arr_return = array('records' => $records, 'record_count' => $record_count);

        return $arr_return;
    }

    function get_pos_sales_return_details($pos_sales_return_id) {
        $this->db->select("psrd.*, psr.outlet_id, p.product_name, p.product_sku_code, p.product_reference_id, p.product_barcode, p.product_image, p.product_image_thumb, tr.tax_rate_name, tr.tax_rate_code, tr.tax_rate_value, u.unit_code, u.unit_name");
        $this->db->from('pos_sales_return_details psrd');     
        $this->db->join('units u', 'u.unit_id = psrd.unit_id', 'left outer');
        $this->db->join('tax_rates tr', 'tr.tax_rate_id = psrd.tax_rate_id', 'left outer');
        $this->db->join('products p', 'p.product_id = psrd.product_id');
        $this->db->join('pos_sales_returns psr', 'psr.pos_sales_return_id = psrd.pos_sales_return_id'); 
        $this->db->where( array('psrd.pos_sales_return_id' => $pos_sales_return_id)); 

        $pos_sales_return_details = $this->db->get()->result();

        $i = 0;
        foreach($pos_sales_return_details as $row){
            $pos_sales_return_details[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $i++;
        }
        return $pos_sales_return_details;

        // return $this->db->get()->result();
    }

    function get_num_pos_sales_return_details($pos_sales_return_id) {
        $this->db->select("psdr.*, p.product_name, p.product_sku_code, p.product_reference_id, p.product_barcode, p.product_image, p.product_image_thumb, tr.tax_rate_name, tr.tax_rate_code, tr.tax_rate_value");
        $this->db->from('pos_sales_return_details psdr');     
        // $this->db->join('units u', 'u.unit_id = psdr.unit_id', 'left outer');
        $this->db->join('tax_rates tr', 'tr.tax_rate_id = psdr.tax_rate_id', 'left outer');
        $this->db->join('products p', 'p.product_id = psdr.product_id');
        $this->db->join('pos_sales_returns ps', 'ps.pos_sales_return_id = psdr.pos_sales_return_id'); 
        $this->db->where( array('psdr.pos_sales_return_id' => $pos_sales_return_id)); 

        return $this->db->count_all_results();

    }

    function get_pos_sales_return_tax_details($pos_sales_return_id){
        $this->db->select("psdr.tax_rate_id, tr.tax_rate_code, tr.tax_rate_value, (SELECT COALESCE(SUM(psdr2.price_excl_tax * psdr2.quantity),0) FROM pos_sales_return_details psdr2 WHERE psdr2.pos_sales_return_id = psdr.pos_sales_return_id AND psdr2.tax_rate_id = psdr.tax_rate_id) AS 'vatable_amount', (SELECT COALESCE(SUM(psdr3.unit_tax * psdr3.quantity),0) FROM pos_sales_return_details psdr3 WHERE psdr3.pos_sales_return_id = psdr.pos_sales_return_id AND psdr3.tax_rate_id = psdr.tax_rate_id) AS 'vat_amount'");
        $this->db->from('pos_sales_return_details psdr');     
        $this->db->join('tax_rates tr', 'tr.tax_rate_id = psdr.tax_rate_id');
        $this->db->join('pos_sales_returns ps', 'ps.pos_sales_return_id = psdr.pos_sales_return_id'); 
        $this->db->group_by('psdr.tax_rate_id');
        $this->db->where( array('psdr.pos_sales_return_id' => $pos_sales_return_id)); 

        return $this->db->get()->result();
    }

    //NEW SALES RETURN ADD PRODUCT
    function sales_return_add_product($product_id, $product_variation_id) {

        $pos_sales_return_id = 0;

        //Check if there's POS Sale
        $this->db->select("*");
        $this->db->from('pos_sales_returns');       
        $this->db->where( array('is_void' => 0, 'is_held' => 0, 'is_completed' => 0, 'system_user_id' => $this->session->userdata('pos_system_user_id'), 'outlet_id' => $this->session->userdata('pos_outlet_id')));
        $this->db->order_by("pos_sales_return_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {
            $records = $query->result();
            
            foreach ($records as $row) {
                $pos_sales_return_id = $row->pos_sales_return_id;
            }
        } else {

            $data = array(
                'sales_return_date' => date('Y-m-d'),
                'system_user_id' =>  $this->session->userdata('pos_system_user_id'),
                'outlet_id' => $this->session->userdata('pos_outlet_id')
            );

            $res = $this->db->insert('pos_sales_returns', $data);
            $pos_sales_return_id = $this->db->insert_id();

            //UPDATE DOCUMENT NUMBER
            $sales_return_prefix = '';
            $pos_sales_return_number = '';
            $this->db->select("*");
            $this->db->from('prefixes'); 
            $this->db->where( array('document_name' => 'Sales Return'));
            $prefix = $this->db->get()->result();

            foreach ($prefix as $row) {
                $sales_return_prefix = $row->prefix_name;
            }
            if ($sales_return_prefix != '') {
                $pos_sales_return_number = $sales_return_prefix . '-' . $pos_sales_return_id;
            } else {
                $pos_sales_return_number = $pos_sales_return_id;
            }
            $data = array(
                'pos_sales_return_number' => $pos_sales_return_number
            );
            $this->db->where(array('pos_sales_return_id' => $pos_sales_return_id));
            $res = $this->db->update('pos_sales_returns', $data);
        }

        //Check if Sale Product Exists
        $this->db->select("*");
        $this->db->from('pos_sales_return_details');       
        $this->db->where( array('pos_sales_return_id' => $pos_sales_return_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
        $query = $this->db->get();
        $record_count = $query->num_rows();


        $quantity = 0;
        $unit_id = 0;
        $base_unit_id = 0;
        $conversion_factor = 1;
        // $base_unit_price = 0;
        $base_unit_quantity = 0;
        $unit_price = 0;
        $discount_type = '';
        $discount_value = 0;
        $tax_rate_id = 0;
        $tax_rate_value = 0;
        $price_excl_tax = 0;
        $unit_tax = 0;
        $tax_amount = 0;
        $line_total = 0;
        $sub_total = 0;
        $pos_sales_return_detail_id = 0;

        if ($record_count > 0) {

            $pos_sales_return_detail = $query->result();

            foreach ($pos_sales_return_detail as $row) {
                $pos_sales_return_detail_id = $row->pos_sales_return_detail_id;
                $base_unit_id = $row->base_unit_id;
                $unit_id = $row->unit_id;
                $conversion_factor = $row->conversion_factor;
                $unit_price = $row->unit_price;
                $quantity = $row->quantity;
                $base_unit_quantity = $row->base_unit_quantity;
                $discount_type = $row->discount_type;
                $discount_value = $row->discount_value;
            }

            $product_unit_id = 0;
            $product = $this->get_product($product_id);

            foreach ($product as $row) {
                $tax_rate_id = $row->tax_rate_id; 
                $tax_rate_value = $row->tax_rate_value;   
                $product_unit_id = $row->unit_id;
            }

            $quantity = $quantity + 1;

            if ($base_unit_id !== $unit_id) {
                $base_unit_quantity = ($quantity * $conversion_factor);   
            } else {
                $base_unit_quantity = $quantity;
            }

            if ($tax_rate_id == 0) {
                $unit_tax = $unit_price * (0/100);
            } else {
                $unit_tax = $unit_price * ($tax_rate_value/100);
            }
            $price_excl_tax = $unit_price - $unit_tax;
            
            $tax_amount = $unit_tax * $quantity;
            $line_total = $unit_price * $quantity;

            if ($discount_type == 'Percentage') {
                $discount_amount = $line_total * ($discount_value/100);
            } else {
                $discount_amount = $discount_value * $quantity;
            }

            $sub_total = $line_total - $discount_amount;

            $data = array(
                'unit_price' => $unit_price,
                'tax_rate_id' => $tax_rate_id,
                'price_excl_tax' => $price_excl_tax,
                'unit_tax' => $unit_tax,
                'discount_amount' => $discount_amount,
                'quantity' => $quantity,
                'base_unit_quantity' => $base_unit_quantity,
                'tax_amount' => $tax_amount,
                'line_total' => $line_total,
                'sub_total' => $sub_total
            );

            $this->db->where( array('pos_sales_return_id' => $pos_sales_return_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
            $res = $this->db->update('pos_sales_return_details', $data);

            if ($res){

                // //Return Stock
                // $available_stock = 0;

                // $this->db->select("*");
                // $this->db->from('outlet_products');
                // $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                // $outlet_product = $this->db->get()->result();
                // foreach ($outlet_product as $row) {
                //     $available_stock = $row->available_stock;
                // }

                // $data = array(
                //     'available_stock' =>  $available_stock + $conversion_factor
                // );
                // $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                // $res = $this->db->update('outlet_products', $data);

                // //STOCK TRACKER
                // $data = array(
                //     'quantity' => $base_unit_quantity,
                //     'unit_price' => $unit_price/$conversion_factor
                // );
                // $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $pos_sale_detail_id, 'transaction_description' => 'POS Sales Return'));
                // $this->db->update('stock_tracker', $data);

                //Update Totals
                $this->calculate_sales_return_total($pos_sales_return_id);

                $arr_return = array('res' => true,'dt' => 'Product added successfully.');
            } else {
                $arr_return = array('res' => false,'dt' => 'Could not add product successfully. Please try again.');
            }
        } else {

            $product = $this->get_product($product_id);

            foreach ($product as $row) {
                $tax_rate_id = $row->tax_rate_id;
                $tax_rate_value = $row->tax_rate_value;
                $unit_id = $row->unit_id;
                $base_unit_id = $row->unit_id;
            }

            if ($product_variation_id == 0 || $product_variation_id == null || $product_variation_id == '') {
                foreach ($product as $row) {
                    if ($row->universal_sale_price == 1){
                        if ($row->sale_price > 0){
                            $unit_price = $row->sale_price;
                        } else {
                            if ($row->universal_regular_price == 1){
                                $unit_price = $row->regular_price;
                            } else {
                                $unit_price = $row->outlet_regular_price;
                            }
                        }
                    } else {
                        if ($row->outlet_sale_price > 0){
                            $unit_price = $row->outlet_sale_price;
                        } else {
                          if ($row->universal_regular_price == 1){
                                $unit_price = $row->regular_price;
                            } else {
                                $unit_price = $row->outlet_regular_price;
                            }
                        }
                    }
                }
            } else {
                $product_variation = $this->get_product_variation($product_variation_id);

                foreach ($product_variation as $row) {
                    if ($row->product_variation_universal_prices == 1){
                        if ($row->product_variation_sale_price > 0){
                            $unit_price = $row->product_variation_sale_price;
                        } else {
                            $unit_price = $row->product_variation_regular_price;
                        }
                    } else {
                        if ($row->outlet_sale_price > 0){
                            $unit_price = $row->outlet_sale_price;
                        } else {
                            $unit_price = $row->outlet_regular_price;
                        }
                    }
                }
            }

            $base_unit_price = $unit_price;

            // if ($product_variation_id == 0 || $product_variation_id == '') {
            //     foreach ($product as $row) {
            //         if ($row->sale_price > 0){
            //             $unit_price = $row->sale_price;
            //             $base_unit_price = $row->sale_price;
            //         } else {
            //             $unit_price = $row->regular_price;
            //             $base_unit_price = $row->regular_price;
            //         }
            //     }
            // } else {
            //     $product_variation = $this->get_product_variation($product_variation_id);

            //     foreach ($product_variation as $row) {
            //        if ($row->product_variation_sale_price > 0){
            //             $unit_price = $row->product_variation_sale_price;
            //             $base_unit_price = $row->product_variation_sale_price;
            //         } else {
            //             $unit_price = $row->product_variation_regular_price;
            //             $base_unit_price = $row->product_variation_regular_price;
            //         } 
            //     }
            // }

            if ($tax_rate_id == 0) {
                $unit_tax = $unit_price * (0/100);
            } else {
                $unit_tax = $unit_price * ($tax_rate_value/100);
            }
            $price_excl_tax = $unit_price - $unit_tax;

            
            $quantity = 1;
            $base_unit_quantity = 1;
            $tax_amount = $unit_tax * $quantity;
            $line_total = $unit_price * $quantity;
            $sub_total = $unit_price * $quantity;

            $data = array(
                'pos_sales_return_id' =>  $pos_sales_return_id,
                'product_id' => $product_id,
                'product_variation_id' => $product_variation_id,
                'unit_price' => $unit_price,
                'base_unit_price' => $base_unit_price,
                'unit_id' => $unit_id,
                'base_unit_id' => $base_unit_id,
                'conversion_factor' => $conversion_factor,
                'tax_rate_id' => $tax_rate_id,
                'price_excl_tax' => $price_excl_tax,
                'unit_tax' => $unit_tax,
                'quantity' => $quantity,
                'base_unit_quantity' => $base_unit_quantity,
                'tax_amount' => $tax_amount,
                'line_total' => $line_total,
                'sub_total' => $sub_total
            );
            $res = $this->db->insert('pos_sales_return_details', $data);
            $pos_sales_return_detail_id = $this->db->insert_id();

            if ($res){

                // //Return Stock
                // $available_stock = 0;

                // $this->db->select("*");
                // $this->db->from('outlet_products');
                // $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                // $outlet_product = $this->db->get()->result();
                // foreach ($outlet_product as $row) {
                //     $available_stock = $row->available_stock;
                // }

                // $data = array(
                //     'available_stock' =>  $available_stock + $base_unit_quantity
                // );
                // $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                // $res = $this->db->update('outlet_products', $data);

                // //STOCK TRACKER
                // $data = array(
                //     'outlet_id' => $this->session->userdata('pos_outlet_id'),
                //     'product_id' => $product_id,
                //     'product_variation_id' => $product_variation_id,
                //     'transaction_id' => $pos_sales_return_detail_id,
                //     'transaction_type' => 'IN',
                //     'transaction_description' => 'POS Sales Return',
                //     'quantity' => $base_unit_quantity,
                //     'unit_price' => $base_unit_price
                // );
                // $this->db->insert('stock_tracker', $data);

                //Update Totals
                $this->calculate_sales_return_total($pos_sales_return_id);

                $arr_return = array('res' => true,'dt' => 'Product added successfully.');
            } else {
                $arr_return = array('res' => false,'dt' => 'Could not add product successfully. Please try again.');
            }
        }
      return $arr_return;  
    }

    function edit_sales_return_add_product() {
        $product_id = $this->input->post('product_id');
        $product_variation_id = $this->input->post('product_variation_id');
        $pos_sales_return_id = $this->input->post('pos_sales_return_id');

        //Check if Sale Product Exists
        $this->db->select("*");
        $this->db->from('pos_sales_return_details');       
        $this->db->where( array('pos_sales_return_id' => $pos_sales_return_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
        $query = $this->db->get();
        $record_count = $query->num_rows();

        $quantity = 0;
        $unit_id = 0;
        $base_unit_id = 0;
        $conversion_factor = 1;
        $base_unit_quantity = 0;
        $unit_price = 0;
        $discount_type = '';
        $discount_value = 0;
        $tax_rate_id = 0;
        $tax_rate_value = 0;
        $price_excl_tax = 0;
        $unit_tax = 0;
        $tax_amount = 0;
        $line_total = 0;
        $sub_total = 0;
        $pos_sales_return_detail_id = 0;

        if ($record_count > 0) {

            $pos_sales_return_detail = $query->result();

            foreach ($pos_sales_return_detail as $row) {
                $pos_sales_return_detail_id = $row->pos_sales_return_detail_id;
                $base_unit_id = $row->base_unit_id;
                $unit_id = $row->unit_id;
                $conversion_factor = $row->conversion_factor;
                $unit_price = $row->unit_price;
                $quantity = $row->quantity;
                $discount_type = $row->discount_type;
                $discount_value = $row->discount_value;
            }

            $product_unit_id = 0;
            $product = $this->get_product($product_id);

            foreach ($product as $row) {
                $tax_rate_id = $row->tax_rate_id;
                $tax_rate_value = $row->tax_rate_value;
                $product_unit_id = $row->unit_id;
            }

            $quantity = $quantity + 1;

            if ($base_unit_id !== $unit_id) {
                $base_unit_quantity = ($quantity * $conversion_factor);   
            } else {
                $base_unit_quantity = $quantity;
            }

            if ($tax_rate_id == 0) {
                $unit_tax = $unit_price * (0/100);
            } else {
                $unit_tax = $unit_price * ($tax_rate_value/100);
            }
            $price_excl_tax = $unit_price - $unit_tax;
            
            $tax_amount = $unit_tax * $quantity;
            $line_total = $unit_price * $quantity;

            if ($discount_type == 'Percentage') {
                $discount_amount = $line_total * ($discount_value/100);
            } else {
                $discount_amount = $discount_value * $quantity;
            }

            $sub_total = $line_total - $discount_amount;

            $data = array(
                'unit_price' => $unit_price,
                'tax_rate_id' => $tax_rate_id,
                'price_excl_tax' => $price_excl_tax,
                'unit_tax' => $unit_tax,
                'discount_amount' => $discount_amount,
                'quantity' => $quantity,
                'base_unit_quantity' => $base_unit_quantity,
                'tax_amount' => $tax_amount,
                'line_total' => $line_total,
                'sub_total' => $sub_total
            );

            $this->db->where( array('pos_sales_return_id' => $pos_sales_return_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
            $res = $this->db->update('pos_sales_return_details', $data);

            if ($res){

                // //Return Stock
                // $available_stock = 0;

                // $this->db->select("*");
                // $this->db->from('outlet_products');
                // $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                // $outlet_product = $this->db->get()->result();
                // foreach ($outlet_product as $row) {
                //     $available_stock = $row->available_stock;
                // }

                // $data = array(
                //     'available_stock' =>  $available_stock + $conversion_factor
                // );
                // $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                // $res = $this->db->update('outlet_products', $data);

                // //STOCK TRACKER
                // $data = array(
                //     'quantity' => $base_unit_quantity,
                //     'unit_price' => $unit_price/$conversion_factor
                // );
                // $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $pos_sale_detail_id, 'transaction_description' => 'POS Sales Return'));
                // $this->db->update('stock_tracker', $data);

                //Update Totals
                $this->calculate_sales_return_total($pos_sales_return_id);

                $arr_return = array('res' => true,'dt' => 'Product added successfully.');
            } else {
                $arr_return = array('res' => false,'dt' => 'Could not add product successfully. Please try again.');
            }

        } else {

            $product = $this->get_product($product_id);

            foreach ($product as $row) {
                $tax_rate_id = $row->tax_rate_id;
                $tax_rate_value = $row->tax_rate_value;
                $unit_id = $row->unit_id;
                $base_unit_id = $row->unit_id;
            }

            if ($product_variation_id == 0 || $product_variation_id == null || $product_variation_id == '') {
                foreach ($product as $row) {
                    if ($row->universal_sale_price == 1){
                        if ($row->sale_price > 0){
                            $unit_price = $row->sale_price;
                        } else {
                            if ($row->universal_regular_price == 1){
                                $unit_price = $row->regular_price;
                            } else {
                                $unit_price = $row->outlet_regular_price;
                            }
                        }
                    } else {
                        if ($row->outlet_sale_price > 0){
                            $unit_price = $row->outlet_sale_price;
                        } else {
                          if ($row->universal_regular_price == 1){
                                $unit_price = $row->regular_price;
                            } else {
                                $unit_price = $row->outlet_regular_price;
                            }
                        }
                    }
                }
            } else {
                $product_variation = $this->get_product_variation($product_variation_id);

                foreach ($product_variation as $row) {
                    if ($row->product_variation_universal_prices == 1){
                        if ($row->product_variation_sale_price > 0){
                            $unit_price = $row->product_variation_sale_price;
                        } else {
                            $unit_price = $row->product_variation_regular_price;
                        }
                    } else {
                        if ($row->outlet_sale_price > 0){
                            $unit_price = $row->outlet_sale_price;
                        } else {
                            $unit_price = $row->outlet_regular_price;
                        }
                    }
                }
            }

            $base_unit_price = $unit_price;

            // if ($product_variation_id == 0 || $product_variation_id == '') {
            //     foreach ($product as $row) {
            //         if ($row->sale_price > 0){
            //             $unit_price = $row->sale_price;
            //             $base_unit_price = $row->sale_price;
            //         } else {
            //             $unit_price = $row->regular_price;
            //             $base_unit_price = $row->regular_price;
            //         }
            //     }
            // } else {
            //     $product_variation = $this->get_product_variation($product_variation_id);

            //     foreach ($product_variation as $row) {
            //        if ($row->product_variation_sale_price > 0){
            //             $unit_price = $row->product_variation_sale_price;
            //             $base_unit_price = $row->product_variation_sale_price;
            //         } else {
            //             $unit_price = $row->product_variation_regular_price;
            //             $base_unit_price = $row->product_variation_regular_price;
            //         } 
            //     }
            // }

            if ($tax_rate_id == 0) {
                $unit_tax = $unit_price * (0/100);
            } else {
                $unit_tax = $unit_price * ($tax_rate_value/100);
            }
            $price_excl_tax = $unit_price - $unit_tax;

            
            $quantity = 1;
            $base_unit_quantity = 1;
            $tax_amount = $unit_tax * $quantity;
            $line_total = $unit_price * $quantity;
            $sub_total = $unit_price * $quantity;

            $data = array(
                'pos_sales_return_id' =>  $pos_sales_return_id,
                'product_id' => $product_id,
                'product_variation_id' => $product_variation_id,
                'unit_price' => $unit_price,
                'base_unit_price' => $base_unit_price,
                'unit_id' => $unit_id,
                'base_unit_id' => $base_unit_id,
                'conversion_factor' => $conversion_factor,
                'tax_rate_id' => $tax_rate_id,
                'price_excl_tax' => $price_excl_tax,
                'unit_tax' => $unit_tax,
                'quantity' => $quantity,
                'base_unit_quantity' => $base_unit_quantity,
                'tax_amount' => $tax_amount,
                'line_total' => $line_total,
                'sub_total' => $sub_total
            );
            $res = $this->db->insert('pos_sales_return_details', $data);
            $pos_sales_return_detail_id = $this->db->insert_id();

            if ($res){

                // //Return Stock
                // $available_stock = 0;

                // $this->db->select("*");
                // $this->db->from('outlet_products');
                // $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                // $outlet_product = $this->db->get()->result();
                // foreach ($outlet_product as $row) {
                //     $available_stock = $row->available_stock;
                // }

                // $data = array(
                //     'available_stock' =>  $available_stock + $base_unit_quantity
                // );
                // $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                // $res = $this->db->update('outlet_products', $data);

                // //STOCK TRACKER
                // $data = array(
                //     'outlet_id' => $this->session->userdata('pos_outlet_id'),
                //     'product_id' => $product_id,
                //     'product_variation_id' => $product_variation_id,
                //     'transaction_id' => $pos_sale_detail_id,
                //     'transaction_type' => 'IN',
                //     'transaction_description' => 'POS Sales Return',
                //     'quantity' => $base_unit_quantity,
                //     'unit_price' => $base_unit_price
                // );
                // $this->db->insert('stock_tracker', $data);

                //Update Totals
                $this->calculate_sales_return_total($pos_sales_return_id);

                $arr_return = array('res' => true,'dt' => 'Product added successfully.');
            } else {
                $arr_return = array('res' => false,'dt' => 'Could not add product successfully. Please try again.');
            }
        }
        
        return $arr_return;  

    }


    //CALCULATE SALES RETURN TOTALS
    function calculate_sales_return_total($pos_sales_return_id) {

        //SALE DETAILS
        $sub_total = 0;
        $total_tax = 0;
        $total_items_discount = 0;
        $total_quantity = 0;

        $this->db->select("*");
        $this->db->from('pos_sales_return_details');       
        $this->db->where( array('pos_sales_return_id' => $pos_sales_return_id));
        $pos_sales_return_details = $this->db->get()->result();
        
        foreach ($pos_sales_return_details as $row) {
            $sub_total = $sub_total + $row->sub_total;
            $total_tax = $total_tax + $row->tax_amount;
            $total_items_discount = $total_items_discount + $row->discount_amount;
            $total_quantity = $total_quantity + $row->quantity;
        }

        //SALES RETURN
        $overall_discount_type = '';
        $overall_discount_value = 0;
        $overall_discount = 0;
        $delivery_fee = 0;
        $total_amount = 0;
        $total_refunded = 0;
        $change_amount = 0;

        $this->db->select("*");
        $this->db->from('pos_sales_returns');       
        $this->db->where( array('pos_sales_return_id' => $pos_sales_return_id));
        $pos_sales_return = $this->db->get()->result();

        foreach ($pos_sales_return as $row) {
            $overall_discount_type = $row->overall_discount_type;
            $overall_discount_value = $row->overall_discount_value;
            $delivery_fee = $row->delivery_fee;
            $total_refunded = $row->total_refunded;
        }

        if ($overall_discount_type == 'Percentage') {
            $overall_discount = $sub_total * ($overall_discount_value/100);
        } else {
            $overall_discount = $overall_discount_value;
        }

        $total_amount = ceil(($sub_total - $overall_discount) + $delivery_fee);

        if ($total_refunded > $total_amount) {
            $change_amount = $total_refunded - $total_amount;
        }

        //UPDATE TABLE
        $data = array(
            'sub_total' =>  $sub_total,
            'total_tax' => $total_tax,
            'total_items_discount' => $total_items_discount,
            'overall_discount' => $overall_discount,
            'total_amount' => $total_amount,
            'change_amount' => $change_amount,
            'total_quantity' => $total_quantity
        );

        $this->db->where( array('pos_sales_return_id' => $pos_sales_return_id));
        $this->db->update('pos_sales_returns', $data);
    }

    function get_pos_sales_return_detail($pos_sales_return_detail_id){
        $this->db->select("psrd.*, u.unit_code, u.unit_name, psr.outlet_id, p.product_name, p.product_sku_code, p.product_reference_id, p.product_barcode, p.product_image, p.product_image_thumb, tr.tax_rate_name, tr.tax_rate_code, tr.tax_rate_value");
        $this->db->from('pos_sales_return_details psrd');     
        $this->db->join('units u', 'u.unit_id = psrd.unit_id', 'left outer');
        $this->db->join('tax_rates tr', 'tr.tax_rate_id = psrd.tax_rate_id', 'left outer');
        $this->db->join('products p', 'p.product_id = psrd.product_id');
        $this->db->join('pos_sales_returns psr', 'psr.pos_sales_return_id = psrd.pos_sales_return_id');  
        $this->db->where( array('psrd.pos_sales_return_detail_id' => $pos_sales_return_detail_id));

        $pos_sales_return_details = $this->db->get()->result();

        $i = 0;
        foreach($pos_sales_return_details as $row){
            $pos_sales_return_details[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $i++;
        }
        return $pos_sales_return_details;
    }

    //UPDATE SALES RETURN MODIFY ITEM
    function submit_modify_sales_return_item(){
        $product_id = $this->input->post('product_id');
        $product_variation_id = $this->input->post('product_variation_id');
        $pos_sales_return_id = $this->input->post('pos_sales_return_id');
        $pos_sales_return_detail_id = $this->input->post('pos_sales_return_detail_id');

        //$this->debug_to_console($pos_sale_detail_id, 'Context');

        $new_quantity = $this->input->post('quantity');
        $real_quantity = 0;
        $old_quantity = 0;
        $real_old_quantity = 0;
        $new_unit_id = $this->input->post('unit_id');
        $base_unit_id = 0;
        $old_unit_id = 0;
        $conversion_factor = $this->input->post('conversion_factor');     
        $unit_price = $this->input->post('unit_price');
        // $discount_type = $this->input->post('discount_type');
        // $discount_value = $this->input->post('discount_value');
        // $discounted_price = 0;
        $tax_rate_id = 0;
        $tax_rate_value = 0;
        $price_excl_tax = 0;
        $unit_tax = 0;
        $tax_amount = 0;
        $line_total = 0;
        $sub_total = 0;

        $negative_inventory = 0;
        $available_stock = 0;
        $minimum_selling_price = 0;

        $pos_sales_return_detail = $this->get_pos_sales_return_detail($pos_sales_return_detail_id);

        foreach ($pos_sales_return_detail as $row) {
            $old_quantity = $row->quantity;
            $old_unit_id = $row->unit_id;
        }

        $product = $this->get_product($product_id);

        foreach ($product as $row) {
            $base_unit_id = $row->unit_id;
            $tax_rate_id = $row->tax_rate_id;
            $tax_rate_value = $row->tax_rate_value;
            $negative_inventory = $row->negative_inventory;
        }

        if ($base_unit_id == $new_unit_id){
            $real_quantity = $new_quantity;
            if ($product_variation_id == 0 || $product_variation_id == '') {
                foreach ($product as $row) {
                    $minimum_selling_price = $row->minimum_selling_price;
                }
            } else {
                $product_variation = $this->get_product_variation($product_variation_id);

                foreach ($product_variation as $row) {
                   $minimum_selling_price = $row->product_variation_minimum_selling_price;
                }
            }
        } else {
            $real_quantity = $new_quantity * $conversion_factor;
            $this->db->from('product_related_units');
            $this->db->where( array('product_id'=>$product_id, 'related_unit_id'=>$new_unit_id));
            $related_unit = $this->db->get()->result();
            foreach ($related_unit as $row2) {
                $minimum_selling_price = $row2->unit_minimum_selling_price;
            }
        }

        if ($old_unit_id == $base_unit_id) {
            $real_old_quantity = $old_quantity;
        } else {
            $real_old_quantity = $old_quantity * $conversion_factor;
        }

        if ($tax_rate_id == 0) {
            $unit_tax = $unit_price * (0/100);
        } else {
            $unit_tax = $unit_price * ($tax_rate_value/100);
        }
        $price_excl_tax = $unit_price - $unit_tax;

        //DISCOUNTED PRICE
        // if ($discount_type == 'Percentage') {
        //     $discounted_price = $unit_price - ($unit_price * ($discount_value/100));
        // } else {
        //     $discounted_price = ($unit_price - $discount_value);
        // }

        // //CHECK NEGATIVE INVENTORY
        // $this->db->select("*");
        // $this->db->from('outlet_products');
        // $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
        // $outlet_product = $this->db->get()->result();
        // foreach ($outlet_product as $row) {
        //     $available_stock = $row->available_stock;
        // }

        // if ($negative_inventory == 0 && (($available_stock + $real_old_quantity) - $real_quantity) < 0){
        //     $required_quantity = $real_quantity - $real_old_quantity;
        //     $arr_return = array('res' => false,'dt' => 'You have insufficient stock quantity for this product.<br><br> Required: ' . $required_quantity . '<br> Remaining: ' . $available_stock);
        //     return $arr_return;
        // }

        //CHECK MINIMUM SELLING PRICE
        // if ($minimum_selling_price != 0 && $minimum_selling_price > $discounted_price) {
        //     $arr_return = array('res' => false,'dt' => 'The selling price cannot be less than the minimum selling price for this product.<br><br> Selling Price: ' .  number_format($discounted_price,2) . '<br> Minimum Selling Price: ' . number_format($minimum_selling_price,2));
        //     return $arr_return;
        // }

        $tax_amount = $unit_tax * $new_quantity;
        $line_total = $unit_price * $new_quantity;

        // if ($discount_type == 'Percentage') {
        //     $discount_amount = $line_total * ($discount_value/100);
        // } else {
        //     $discount_amount = ($discount_value * $new_quantity);
        // }

        $sub_total = $line_total;

        $data = array(
            'unit_id' => $new_unit_id,
            'conversion_factor' => $conversion_factor,
            'unit_price' => $unit_price,
            'tax_rate_id' => $tax_rate_id,
            'price_excl_tax' => $price_excl_tax,
            'unit_tax' => $unit_tax,
            'quantity' => $new_quantity,
            'base_unit_quantity' => $real_quantity,
            'tax_amount' => $tax_amount,
            'line_total' => $line_total,
            'sub_total' => $sub_total
        );

        $this->db->where( array('pos_sales_return_detail_id' => $pos_sales_return_detail_id));
        $res = $this->db->update('pos_sales_return_details', $data);

        if ($res){

            // //Deduct Stock
            // $available_stock = 0;

            // $this->db->select("*");
            // $this->db->from('outlet_products');
            // $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
            // $outlet_product = $this->db->get()->result();
            // foreach ($outlet_product as $row) {
            //     $available_stock = $row->available_stock;
            // }

            // $data = array(
            //     'available_stock' =>  ($available_stock + $real_old_quantity) - $real_quantity
            // );
            // $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
            // $res = $this->db->update('outlet_products', $data);

            // //STOCK TRACKER
            // $data = array(
            //     'quantity' => $real_quantity
            // );
            // $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $pos_sale_detail_id, 'transaction_description' => 'POS Sale'));
            // $this->db->update('stock_tracker', $data);

            //Update Totals
            $this->calculate_sales_return_total($pos_sales_return_id);

            $arr_return = array('res' => true,'dt' => 'Sales return item updated successfully.');
        } else {
            $arr_return = array('res' => false,'dt' => 'Could not update sales return item successfully. Please try again.');
        }

        return $arr_return;
    }

    function remove_pos_sales_return_item($pos_sales_return_detail_id){

        $old_quantity = 0;
        $product_id = 0;
        $product_variation_id = 0;
        $pos_sales_return_id = 0;
        $outlet_id = 0;

        $pos_sales_return_detail = $this->get_pos_sales_return_detail($pos_sales_return_detail_id);

        foreach ($pos_sales_return_detail as $row) {
            $old_quantity = $row->base_unit_quantity;
            $product_id = $row->product_id;
            $product_variation_id = $row->product_variation_id;
            $pos_sales_return_id = $row->pos_sales_return_id;
            $outlet_id = $row->outlet_id;
        }

       $this->db->where('pos_sales_return_detail_id', $pos_sales_return_detail_id);
       $del = $this->db->delete('pos_sales_return_details');                 

       if ($del){

            // //Deduct Stock
            // $available_stock = 0;

            // $this->db->select("*");
            // $this->db->from('outlet_products');
            // $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id));
            // $outlet_product = $this->db->get()->result();
            // foreach ($outlet_product as $row) {
            //     $available_stock = $row->available_stock;
            // }

            // $data = array(
            //     'available_stock' =>  $available_stock + $old_quantity
            // );
            // $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id'), 'product_id' => $product_id));
            // $res = $this->db->update('outlet_products', $data);

            // //STOCK TRACKER
            // $this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id, 'transaction_id' => $pos_sale_detail_id, 'transaction_description' => 'POS Sale'));
            // $this->db->delete('stock_tracker');

            //Update Totals
            $this->calculate_sales_return_total($pos_sales_return_id);

            $arr_return = array('res' => true,'dt' => 'Sales return item removed successfully.');
        } else {
            $arr_return = array('res' => false,'dt' => 'Could not remove sales return item successfully. Please try again.');
        }

        return $arr_return;
    }

    function sales_return_set_date_valid(){
        $pos_sales_return_id = 0;
        $this->db->select("*");
        $this->db->from('pos_sales_returns');       
        $this->db->where( array('is_void' => 0, 'is_held' => 0, 'is_completed' => 0, 'system_user_id' => $this->session->userdata('pos_system_user_id'), 'outlet_id' => $this->session->userdata('pos_outlet_id')));
        $this->db->order_by("pos_sales_return_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {
            $records = $query->result();            
            $arr_return = array('res' => true,'dt' => 'Sales Return Available.', 'data' => $records);
        } else {
            $arr_return = array('res' => false,'dt' => 'Please add items to the sales return first before setting date.', 'data' => '');
        }
        return $arr_return;
    }

    function submit_sales_return_date(){
        $pos_sales_return_id = $this->input->post('pos_sales_return_id');
        $sales_return_date = $this->input->post('sales_return_date');

        $data = array(
            'sales_return_date' => $sales_return_date
        );
        $this->db->where( array('pos_sales_return_id' => $pos_sales_return_id));
        $update = $this->db->update('pos_sales_returns', $data);

        if ($update) {
            $arr_return = array('res' => true,'dt' => 'Sales Return date saved successfully');
        } else {
            $arr_return = array('res' => false,'dt' => 'Could not save sales return date successfully. Please try again.');
        }

        return $arr_return;
    }

    //SAVE SALES RETURN CUSTOMER
    function save_sales_return_customer($customer_id){

        $this->db->select("*");
        $this->db->from('pos_sales_returns');       
        $this->db->where( array('is_void' => 0, 'is_held' => 0, 'is_completed' => 0, 'system_user_id' => $this->session->userdata('pos_system_user_id'), 'outlet_id' => $this->session->userdata('pos_outlet_id')));
        $this->db->order_by("pos_sales_return_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {
            $data = array(
                'customer_id' => $customer_id,
                'system_user_id' =>  $this->session->userdata('pos_system_user_id'),
                'outlet_id' => $this->session->userdata('pos_outlet_id')
            );
            $records = $query->result();
            $pos_sales_return_id = 0;
            foreach ($records as $row) {
                $pos_sales_return_id = $row->pos_sales_return_id;
            }
            $this->db->where(array('pos_sales_return_id' => $pos_sales_return_id));
            $res = $this->db->update('pos_sales_returns', $data);

            if ($res){
                $arr_return = array('res' => true,'dt' => 'Sales Return updated successfully.','pos_sales_return_id' => $pos_sales_return_id);
            } else {
                $arr_return = array('res' => false,'dt' => 'Could not update sales return successfully. Please try again.','pos_sales_return_id' => $pos_sales_return_id);
            }

        } else {
            
            $data = array(
                'sales_return_date' => date('Y-m-d'),
                'customer_id' => $customer_id,
                'system_user_id' =>  $this->session->userdata('pos_system_user_id'),
                'outlet_id' => $this->session->userdata('pos_outlet_id')
            );

            $res = $this->db->insert('pos_sales_returns', $data);
            $pos_sales_return_id = $this->db->insert_id();
            if ($res){

                //UPDATE POS sales_return NUMBER
                $sales_return_prefix = '';
                $pos_sales_return_number = '';
                $this->db->select("*");
                $this->db->from('prefixes'); 
                $this->db->where( array('document_name' => 'Sales Return'));
                $prefix = $this->db->get()->result();

                foreach ($prefix as $row) {
                    $sales_return_prefix = $row->prefix_name;
                }
                if ($sales_return_prefix != '') {
                    $pos_sales_return_number = $sales_return_prefix . '-' . $pos_sales_return_id;
                } else {
                    $pos_sales_return_number = $pos_sales_return_id;
                }
                $data = array(
                    'pos_sales_return_number' => $pos_sales_return_number
                );
                $this->db->where(array('pos_sales_return_id' => $pos_sales_return_id));
                $res = $this->db->update('pos_sales_returns', $data);

                $arr_return = array('res' => true,'dt' => 'Sales Return updated successfully.','pos_sales_return_id' => $pos_sales_return_id);
            } else {
                $arr_return = array('res' => false,'dt' => 'Could not update sales return successfully. Please try again.','pos_sales_return_id' => $pos_sales_return_id);
            }
        }

        return $arr_return;
    }

    function edit_sales_return_select_customer($customer_id) {
        $pos_sales_return_id = $this->input->post('pos_sales_return_id');

        $data = array(
            'customer_id' => $customer_id
        );

        $this->db->where(array('pos_sales_return_id' => $pos_sales_return_id));
        $res = $this->db->update('pos_sales_returns', $data);

        if ($res){
            $arr_return = array('res' => true,'dt' => 'Customer information updated successfully.','pos_sales_return_id' => $pos_sales_return_id);
        } else {
            $arr_return = array('res' => false,'dt' => 'Could not update customer information successfully. Please try again.','pos_sales_return_id' => $pos_sales_return_id);
        }
        return $arr_return;
    }

    //DETATCH CUSTOMER
    function detatch_sales_return_customer($pos_sales_return_id) {
         $data = array(
            'customer_id' => 0
        );
        $this->db->where(array('pos_sales_return_id' => $pos_sales_return_id));
        $res = $this->db->update('pos_sales_returns', $data);

        if ($res){
            $arr_return = array('res' => true,'dt' => 'Customer detatched successfully.','pos_sales_return_id' => $pos_sales_return_id);
        } else {
            $arr_return = array('res' => false,'dt' => 'Could not detatch customer successfully. Please try again.','pos_sales_return_id' => $pos_sales_return_id);
        }
        return $arr_return;
    }

    function get_pos_sales_return ($pos_sales_return_id) {
        $this->db->select("psr.*, c.first_name, c.last_name, c.email_address, c.phone_number, c.credit_limit, c.opening_balance, c.loyalty_enrolled, c.loyalty_number, c.loyalty_enrollment_date, c.profile_picture, c.profile_picture_thumb, su.first_name AS 'system_user_first_name', su.last_name AS 'system_user_last_name'");
        $this->db->from('pos_sales_returns psr');     
        $this->db->join('customers c', 'c.customer_id = psr.customer_id', 'left outer'); 
        $this->db->join('system_users su', 'su.system_user_id = psr.system_user_id', 'left outer'); 
        $this->db->where( array('psr.pos_sales_return_id' => $pos_sales_return_id));

        return $this->db->get()->result();

    }

    function sales_return_set_comments_valid(){
        $pos_sales_return_id = 0;
        $this->db->select("*");
        $this->db->from('pos_sales_returns');       
        $this->db->where( array('is_void' => 0, 'is_held' => 0, 'is_completed' => 0, 'system_user_id' => $this->session->userdata('pos_system_user_id'), 'outlet_id' => $this->session->userdata('pos_outlet_id')));
        $this->db->order_by("pos_sales_return_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {
            $records = $query->result();   
            $arr_return = array('res' => true,'dt' => 'Sales Return Available.', 'data' => $records);
        } else {
            $arr_return = array('res' => false,'dt' => 'Please add items to the Sales Return first before setting comments.', 'data' => '');
        }
        return $arr_return;
    }

    function submit_sales_return_comments(){
        $pos_sales_return_id = $this->input->post('pos_sales_return_id');

        $data = array(
            'comments' => $this->input->post('comments')
        );

        $this->db->where( array('pos_sales_return_id' => $pos_sales_return_id));
        $update = $this->db->update('pos_sales_returns', $data);

        if ($update) {
            $arr_return = array('res' => true,'dt' => 'Comments saved successfully');
        } else {
            $arr_return = array('res' => false,'dt' => 'Could not save Comments successfully. Please try again.');
        }

        return $arr_return;
    }

    function sales_return_complete_valid(){
        $pos_sales_return_id = 0;
        $customer_id = 0;

        $this->db->select("*");
        $this->db->from('pos_sales_returns');       
        $this->db->where( array('is_void' => 0, 'is_held' => 0, 'is_completed' => 0, 'system_user_id' => $this->session->userdata('pos_system_user_id'), 'outlet_id' => $this->session->userdata('pos_outlet_id')));
        $this->db->order_by("pos_sales_return_id", "desc");
        $this->db->limit(1);
        $query = $this->db->get();
        $record_count = $query->num_rows();

        if ($record_count > 0) {
            $records = $query->result();
            
            foreach ($records as $row) {
                $pos_sales_return_id = $row->pos_sales_return_id;
                $customer_id = $row->customer_id;
            }

            if ($customer_id == 0){
                $arr_return = array('res' => false,'dt' => 'Please assign a customer to this Sales Return.', 'data' => '');
            } else {
                //CHECK ADDED ITEMS
                $this->db->select("*");
                $this->db->from('pos_sales_return_details');       
                $this->db->where( array('pos_sales_return_id' => $pos_sales_return_id));
                $query = $this->db->get();
                $record_count = $query->num_rows();

                if ($record_count > 0) {
                    $arr_return = array('res' => true,'dt' => 'Items Available.', 'data' => $records);
                } else {
                    $arr_return = array('res' => false,'dt' => 'Please add items to the Sales Return first before completing it.', 'data' => '');
                }
            }
        } else {
            $arr_return = array('res' => false,'dt' => 'Please add items to the Sales Return first before completing it.', 'data' => '');
        }
        return $arr_return;
    }

    function complete_sales_return($pos_sales_return_id) {
        $data = array(
            'is_completed' => 1
        );
        $this->db->where( array('pos_sales_return_id' => $pos_sales_return_id));
        $res = $this->db->update('pos_sales_returns', $data);

         if ($res) {
            $arr_return = array('res' => true,'dt' => 'Sales Return completed successfully');
        } else {
            $arr_return = array('res' => false,'dt' => 'Could not complete Sales Return successfully. Please try again.');
        }
        return $arr_return;
    }

    function approve_pos_sales_return($data, $pos_sales_return_id) {
        $this->db->where( array('pos_sales_return_id' => $pos_sales_return_id));
        $update = $this->db->update('pos_sales_returns', $data);
        
        if ($update){
            if ($this->input->post('pos_sales_return_status') == '1'){

                $outlet_id = 0;

                $pos_sales_return = $this->get_pos_sales_return($pos_sales_return_id);
                foreach ($pos_sales_return as $row) {
                    $outlet_id = $row->outlet_id;
                }

                //RETURN STOCK
                $pos_sales_return_details = $this->get_pos_sales_return_details($pos_sales_return_id);
                foreach ($pos_sales_return_details as $row) {
                    $available_stock = 0;

                    $this->db->select("*");
                    $this->db->from('outlet_products');
                    $this->db->join('products', 'products.product_id = outlet_products.product_id');
                    $this->db->where( array('outlet_products.outlet_id' => $outlet_id, 'outlet_products.product_id' => $row->product_id, 'outlet_products.product_variation_id' => $row->product_variation_id));
                    $outlet_product = $this->db->get()->result();
                    foreach ($outlet_product as $row2) {
                        $available_stock = $row2->available_stock;
                    }

                    $data = array(
                        'available_stock' =>  $available_stock + $row->base_unit_quantity
                    );
                    $this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $row->product_id, 'product_variation_id' => $row->product_variation_id));
                    $this->db->update('outlet_products', $data);

                    //STOCK TRACKER
                    $data = array(
                        'outlet_id' => $outlet_id,
                        'product_id' => $row->product_id,
                        'product_variation_id' => $row->product_variation_id,
                        'transaction_id' => $row->pos_sales_return_detail_id,
                        'transaction_type' => 'IN',
                        'transaction_description' => 'POS Return',
                        'quantity' => $row->base_unit_quantity,
                        'unit_price' => $row->unit_price/$row->conversion_factor
                    );
                    $this->db->insert('stock_tracker', $data);


                    // $data = array(
                    //     'quantity' => $row->base_unit_quantity,
                    //     'unit_price' => $row->unit_price/$row->conversion_factor
                    // );
                    // $this->db->where( array('outlet_id' => $outlet_id, 'product_id' => $row->product_id, 'product_variation_id' => $row->product_variation_id, 'transaction_id' => $row->pos_sales_return_detail_id, 'transaction_description' => 'POS Return'));
                    // $this->db->update('stock_tracker', $data);
                    
                }

                if ($this->input->post('approve_settlement') == 'Credit'){

                    $customer_id = $this->input->post('customer_id');
                    $return_amount = $this->input->post('return_amount');
                    $available_credit = 0;

                    $customer = $this->get_customer($customer_id);

                    foreach ($customer as $row) {
                        $available_credit = $row->available_credit;
                    }

                    $data = array(
                        'available_credit' =>  $available_credit + $return_amount
                    );

                    $this->db->where( array('customer_id' => $customer_id));
                    $res = $this->db->update('customers', $data);

                }
                $arr_return = array('res' => true,'dt'=>'Sales Return approved successfully');
            } elseif ($this->input->post('pos_sales_return_status') == 2) {
                $arr_return = array('res' => true,'dt'=>'Sales Return rejected successfully');
            }
        }else{
            $arr_return = array('res' => false,'dt' => 'There was an error submitting this request. Please try again.');
        }
        return $arr_return;
    }

    function sales_return_make_refund_valid($pos_sales_return_id){

        $pos_sales_return = $this->get_pos_sales_return($pos_sales_return_id);

        foreach ($pos_sales_return as $row) {
            if ($row->return_status == 0) {
                $arr_return = array('res' => false,'dt' => 'This sales return is still pending and a refund cannot be made against it.', 'data' => '');
            } elseif ($row->return_status == 2) {
                $arr_return = array('res' => false,'dt' => 'This sales return has been rejected and a refund cannot be made against it.', 'data' => '');
            } elseif ($row->return_status == 1) {
                if ($row->return_settlement == 'Credit') {
                    $arr_return = array('res' => false,'dt' => 'Please sales return has already been settled with the available credit option and cannot be refunded.', 'data' => '');
                } else if ($row->return_settlement == 'Refund') {
                    //CHECK ADDED ITEMS
                    $this->db->from('pos_sales_return_details');       
                    $this->db->where( array('pos_sales_return_id' => $pos_sales_return_id));
                    $query = $this->db->get();
                    $record_count = $query->num_rows();

                    if ($record_count > 0) {
                        $arr_return = array('res' => true,'dt' => 'Items Available.', 'data' => $pos_sales_return);
                    } else {
                        $arr_return = array('res' => false,'dt' => 'Please add items to the sales_return first before making a refund.', 'data' => '');
                    }
                }
            }
        }
        return $arr_return;
    }

    function submit_sales_return_refund(){
        $pos_sales_return_id = $this->input->post('pos_sales_return_id');
        $pos_sales_return_number = $this->input->post('pos_sales_return_number');
        $refund_amount = $this->input->post('refund_amount');
        $reference_number = $this->input->post('reference_number');
        $refund_method = $this->input->post('refund_method');

        $data = array(
            'pos_sales_return_id' => $pos_sales_return_id,
            'refund_amount' => $refund_amount,
            'refund_method' => $refund_method,
            'reference_number' => $reference_number,
            'refund_note' => $this->input->post('refund_note'),
            'system_user_id' => $this->session->userdata('pos_system_user_id')
        );
        $res = $this->db->insert('pos_refunds', $data);
        $pos_refund_id = $this->db->insert_id();

        if ($res) {
            $arr_return = array('res' => true,'dt' => 'Refund submitted successfully');
        } else {
            $arr_return = array('res' => false,'dt' => 'There was a problem submitting the Refund. Please try again.');
        }

        //UPDATE TOTAL REFUNDS
        $this->calculate_sales_return_refunds($pos_sales_return_id);
        $this->calculate_sales_return_total($pos_sales_return_id);

        return $arr_return;
    }

    function calculate_sales_return_refunds($pos_sales_return_id){
        $sales_return_total_refunded = 0;
        $sales_return_total = 0;
        $change_amount = 0;

        $this->db->select("*");
        $this->db->from('pos_refunds');       
        $this->db->where( array('pos_sales_return_id' => $pos_sales_return_id, 'is_void' => 0));
        $pos_refunds = $this->db->get()->result();

        foreach ($pos_refunds as $row) {
            $sales_return_total_refunded = $sales_return_total_refunded + $row->refund_amount;
        }

        $this->db->select("*");
        $this->db->from('pos_sales_returns');       
        $this->db->where( array('pos_sales_return_id' => $pos_sales_return_id));
        $pos_sales_return = $this->db->get()->result();

        foreach ($pos_sales_return as $row) {
            $sales_return_total = $row->total_amount;
        }

        if ($sales_return_total_refunded > $sales_return_total) {
            $change_amount = $sales_return_total_refunded - $sales_return_total;
        } else {
            $change_amount = 0;
        }

        $data = array(
            'total_refunded' => $sales_return_total_refunded,
            'change_amount' => $change_amount
        );
        $this->db->where( array('pos_sales_return_id' => $pos_sales_return_id));
        $this->db->update('pos_sales_returns', $data);

    }

    function get_pos_sales_return_refunds($pos_sales_return_id) {
        $this->db->select("pr.*");
        $this->db->from('pos_refunds pr');     
        $this->db->where( array('pr.pos_sales_return_id' => $pos_sales_return_id, 'pr.is_void' => 0));

        return $this->db->get()->result();
    }
    function get_num_pos_sales_return_refunds($pos_sales_return_id) {
        $this->db->select("pr.*");
        $this->db->from('pos_refunds pr');     
        $this->db->where( array('pr.pos_sales_return_id' => $pos_sales_return_id, 'pr.is_void' => 0));

        return $this->db->count_all_results();
    }










    function get_products_list(){
        $this->db->select("p.*, op.*, b.brand_name, (SELECT GROUP_CONCAT(pca.product_category_name SEPARATOR ',')) AS 'product_category_name'");
        $this->db->from('products p');
        $this->db->join('outlet_products op', 'op.product_id = p.product_id');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        // $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');
        $this->db->join('product_product_categories ppca', 'ppca.product_id = p.product_id', 'left outer');
        $this->db->join('product_categories pca', 'pca.product_category_id = ppca.product_category_id', 'left outer');

        $this->db->where( array('p.is_deleted' => 0, 'op.outlet_id' => $this->session->userdata('pos_outlet_id')));
        $this->db->group_by('p.product_id');
        return $this->db->get()->result();

    }

    function get_low_stock_list() {
        $this->db->select("p.*, op.*, b.brand_name, (SELECT GROUP_CONCAT(pca.product_category_name SEPARATOR ',')) AS 'product_category_name'");
        $this->db->from('products p');
        $this->db->join('outlet_products op', 'op.product_id = p.product_id');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        // $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');
        $this->db->join('product_product_categories ppca', 'ppca.product_id = p.product_id', 'left outer');
        $this->db->join('product_categories pca', 'pca.product_category_id = ppca.product_category_id', 'left outer');

        $this->db->where( array('p.is_deleted' => 0, 'op.outlet_id' => $this->session->userdata('pos_outlet_id'), 'op.available_stock <= ' => 'op.reorder_level'));
        $this->db->group_by('p.product_id');
        return $this->db->get()->result();
    }

    function get_dashboard_low_stock_list() {
        $this->db->select("p.*, op.*, b.brand_name, (SELECT GROUP_CONCAT(pca.product_category_name SEPARATOR ',')) AS 'product_category_name'");
        $this->db->from('products p');
        $this->db->join('outlet_products op', 'op.product_id = p.product_id');
        $this->db->join('brands b', 'b.brand_id = p.brand_id', 'left outer');
        // $this->db->join('units u', 'u.unit_id = p.unit_id', 'left outer');
        $this->db->join('product_product_categories ppca', 'ppca.product_id = p.product_id', 'left outer');
        $this->db->join('product_categories pca', 'pca.product_category_id = ppca.product_category_id', 'left outer');

        $this->db->where( array('p.is_deleted' => 0, 'op.outlet_id' => $this->session->userdata('pos_outlet_id'), 'op.available_stock <= ' => 'op.reorder_level'));
        $this->db->group_by('p.product_id');
        $this->db->limit(6);
        return $this->db->get()->result();
    }

    function get_customers_list(){
        $this->db->select("c.*, cg.customer_group_name");
        $this->db->from('customers c');
        $this->db->join('customer_groups cg', 'cg.customer_group_id = c.customer_group_id', 'left outer');
        $this->db->where( array('c.is_deleted'=>0));
        return $this->db->get()->result();
    }

    function save_customer(){
        $email_address = $this->input->post('email_address');
        $phone_number = $this->input->post('phone_number');

        $msg = '';
        $msg2 = '';
        
        //EMAIL ADDRESS
        $this->db->where(array('email_address' => $email_address, 'is_deleted' => 0));
        $query = $this->db->get('customers');

        if ($query->num_rows() > 0){
            $arr_return = array('res' => false,'dt' => 'Duplicate Email Address: The Email Address you entered is already in the database.');
        }else{

            //PHONE NUMBER
            $this->db->where(array('phone_number' => $phone_number, 'is_deleted' => 0));
            $query = $this->db->get('customers');

            if ($query->num_rows() > 0){
                $arr_return = array('res' => false,'dt' => 'Duplicate Phone Number: The Phone Number you entered is already in the database.');
            }else{

                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'phone_number' => $this->input->post('phone_number'),
                    'email_address' => $this->input->post('email_address'),
                    'gender' => $this->input->post('gender'),
                    'birth_date' => $this->input->post('birth_date'),
                    'password' => md5($this->input->post('password')),
                    'created_on' => date("Y-m-d H:i:s", time())
                );

                $insert = $this->db->insert('customers', $data);
                $insert_id = $this->db->insert_id();
                if ($insert){
                    $arr_return = array('res' => true,'dt' => 'Customer added successfully.');
                }else{
                    $arr_return = array('res' => false,'dt' => 'Could not add Customer successfully. Please try again.');
                }
            }
        }

        return $arr_return;
    }

    function update_customer(){
        $customer_id = $this->input->post('customer_id');
        $email_address = $this->input->post('email_address');
        $phone_number = $this->input->post('phone_number');


        $msg = '';
        $msg2 = '';

        //EMAIL ADDRESS
        $this->db->where(array('customer_id != ' => $customer_id, 'email_address' => $email_address, 'is_deleted' => 0));
        $query = $this->db->get('customers');

        if ($query->num_rows() > 0){
            $arr_return = array('res' => false,'dt' => 'Duplicate Email Address: The Email Address you entered is already in the database.');
        }else{

            //PHONE NUMBER
            $this->db->where(array('customer_id != ' => $customer_id, 'phone_number' => $phone_number, 'is_deleted' => 0));
            $query = $this->db->get('customers');

            if ($query->num_rows() > 0){
                $arr_return = array('res' => false,'dt' => 'Duplicate Phone Number: The Phone Number you entered is already in the database.');
            }else{

                $data = array(
                    'first_name' => $this->input->post('first_name'),
                    'last_name' => $this->input->post('last_name'),
                    'phone_number' => $this->input->post('phone_number'),
                    'email_address' => $this->input->post('email_address'),
                    'gender' => $this->input->post('gender'),
                    'birth_date' => $this->input->post('birth_date')
                );

                $this->db->where(array('customer_id' => $customer_id));
                $update = $this->db->update('customers', $data);
                if ($update){
                    $arr_return = array('res' => true,'dt' => 'Customer updated successfully.');
                }else{
                    $arr_return = array('res' => false,'dt' => 'Could not update Customer successfully. Please try again.');
                }               
            }
        }

        return $arr_return;
    }

    function get_expenses_list(){
        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

        $this->db->select("e.*, su.first_name AS 'system_user_first_name', su.last_name AS 'system_user_last_name'");
        $this->db->from('expenses e');  
        $this->db->join('system_users su', 'su.system_user_id = e.system_user_id', 'left outer');      
        $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id')));
        if ($this->session->userdata('pos_user_is_super_admin') != 1) {
            $this->db->where( array('e.system_user_id' => $this->session->userdata('pos_system_user_id')));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(e.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(e.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }
        return  $this->db->get()->result();
    }

    function save_expense(){
        $data = array(
            'outlet_id' => $this->session->userdata('pos_outlet_id'),
            'system_user_id' => $this->session->userdata('pos_system_user_id'),
            'expense_date' => $this->input->post('expense_date'),
            'expense_description' => $this->input->post('expense_description'),
            'expense_amount' => $this->input->post('expense_amount'),
            'expense_reference_number' => $this->input->post('expense_reference_number'),
            'expense_note' => $this->input->post('expense_note')
        );

        $insert = $this->db->insert('expenses', $data);
        $insert_id = $this->db->insert_id();
        if ($insert){
            $arr_return = array('res' => true,'dt' => 'Expense saved successfully.');
        }else{
            $arr_return = array('res' => false,'dt' => 'Could not save expense successfully. Please try again.');
        }
        return $arr_return;
    }

    function get_expense($expense_id){
        $this->db->select("e.*, su.first_name AS 'system_user_first_name', su.last_name AS 'system_user_last_name'");
        $this->db->from('expenses e');  
        $this->db->join('system_users su', 'su.system_user_id = e.system_user_id', 'left outer');      
        $this->db->where( array('expense_id' => $expense_id));
        return  $this->db->get()->result();

    }

    function update_expense(){

        $expense_id = $this->input->post('expense_id');

        $data = array(
            'expense_date' => $this->input->post('expense_date'),
            'expense_description' => $this->input->post('expense_description'),
            'expense_amount' => $this->input->post('expense_amount'),
            'expense_reference_number' => $this->input->post('expense_reference_number'),
            'expense_note' => $this->input->post('expense_note')
        );

        $this->db->where(array('expense_id' => $expense_id));
        $update = $this->db->update('expenses', $data);

        if ($update){
            $arr_return = array('res' => true,'dt' => 'Expense updated successfully.');
        }else{
            $arr_return = array('res' => false,'dt' => 'Could not update expense successfully. Please try again.');
        }
        return $arr_return;

    }

    function submit_void_expense(){

        $expense_id = $this->input->post('expense_id');

        $void_date = date('Y-m-d H:i:s');
        $void_reason = $this->input->post('void_reason');

        $data = array(
            'is_void' => 1,
            'void_reason' => $void_reason,
            'void_system_user_id' => $this->session->userdata('pos_system_user_id'),
            'void_date' => $void_date
        );
        $this->db->where( array('expense_id' => $expense_id));
        $void = $this->db->update('expenses', $data);

        if ($void) {

            $expense_description = '';
            $expense_amount = '';
            $expense_user = '';
            $expense_date = '';

            $expense = $this->get_expense($expense_id);
            foreach ($expense as $row) {
                $expense_date = $row->expense_date;
                $expense_description = $row->expense_description;
                $expense_amount = number_format($row->expense_amount,2);
                $expense_user = $row->system_user_first_name . ' ' . $row->system_user_last_name;
            }

            //NOTIFICATION
            $data = array(
                'notification_type' => 'Expense Voided',
                'notification_ref_id' => $expense_id,
                'notification_details' => 'An expense with the following details: Date:<b>' . $expense_date . '</b>; Description:<b>' . $expense_description . '</b>; Amount:<b>' . $expense_amount . '</b>; Expense User:<b>' . $expense_user . '</b>; has been voided on  <b>' . $void_date . '</b> by <b>' . $this->session->userdata('pos_user_first_name') . ' ' . $this->session->userdata('pos_user_last_name') . '</b>. Void Reason: <b>' . $void_reason . '</b>',
                'notification_ref_link' => 'pos/sales/expenses'
            );
            $this->db->insert('notifications',$data);


             $arr_return = array('res' => true,'dt' => 'Expense voided successfully.');
        } else {
            $arr_return = array('res' => false,'dt' => 'There was an error trying to void this expense. Please try again.');
        }

        return $arr_return;
    }

    function get_sales_report() {
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $customer_id = $this->input->post('customer_id');

        $this->db->select("ps.*, c.first_name, c.last_name, c.email_address, c.phone_number, c.credit_limit, c.opening_balance, c.loyalty_enrolled, c.loyalty_number, c.loyalty_enrollment_date, c.profile_picture, c.profile_picture_thumb, su.first_name AS 'system_user_first_name', su.last_name AS 'system_user_last_name'");
        $this->db->from('pos_sales ps');     
        $this->db->join('customers c', 'c.customer_id = ps.customer_id', 'left outer');  
        $this->db->join('system_users su', 'su.system_user_id = ps.system_user_id', 'left outer');  

        $this->db->where( array('ps.is_void' => 0, 'ps.is_held' => 0, 'ps.is_completed' => 1, 'ps.outlet_id' => $this->session->userdata('pos_outlet_id')));

        if ($this->session->userdata('pos_user_is_super_admin') != 1) {
            $this->db->where( array('ps.system_user_id' => $this->session->userdata('pos_system_user_id')));
        }        

        if ($from_date != ''){
            $this->db->where('DATE_FORMAT(ps.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($from_date)));
        }
        if ($to_date != ''){
            $this->db->where('DATE_FORMAT(ps.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($to_date)));
        }
        if ($customer_id != ''){
             $this->db->where('ps.customer_id',$customer_id);
        }
        
        $this->db->order_by("ps.pos_sale_id", "desc");

        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        return $records;

    }

    function get_sales_detailed_report() {
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $customer_id = $this->input->post('customer_id');

        $this->db->select("ps.*, c.first_name, c.last_name, c.email_address, c.phone_number, c.credit_limit, c.opening_balance, c.loyalty_enrolled, c.loyalty_number, c.loyalty_enrollment_date, c.profile_picture, c.profile_picture_thumb, su.first_name AS 'system_user_first_name', su.last_name AS 'system_user_last_name'");
        $this->db->from('pos_sales ps');     
        $this->db->join('customers c', 'c.customer_id = ps.customer_id', 'left outer');  
        $this->db->join('system_users su', 'su.system_user_id = ps.system_user_id', 'left outer');  

        $this->db->where( array('ps.is_void' => 0, 'ps.is_held' => 0, 'ps.is_completed' => 1, 'ps.outlet_id' => $this->session->userdata('pos_outlet_id')));

        if ($this->session->userdata('pos_user_is_super_admin') != 1) {
            $this->db->where( array('ps.system_user_id' => $this->session->userdata('pos_system_user_id')));
        }        

        if ($from_date){
            $this->db->where('DATE_FORMAT(ps.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($from_date)));
        }
        if ($to_date != ''){
            $this->db->where('DATE_FORMAT(ps.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($to_date)));
        }
        if ($customer_id != ''){
             $this->db->where('ps.customer_id',$customer_id);
        }
        
        $this->db->order_by("ps.pos_sale_id", "desc");

        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        $i=0;
        foreach ($records as $row) {
            $records[$i]->details = $this->get_pos_sale_details($row->pos_sale_id);
            $i++;
        }

        return $records;
    }

    function get_payments_report(){
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $customer_id = $this->input->post('customer_id');

        $this->db->select("pp.*, ps.pos_sale_number, c.first_name, c.last_name, c.email_address, c.phone_number, c.credit_limit, c.opening_balance, c.loyalty_enrolled, c.loyalty_number, c.loyalty_enrollment_date, c.profile_picture, c.profile_picture_thumb, su.first_name AS 'system_user_first_name', su.last_name AS 'system_user_last_name'");
        $this->db->from('pos_payments pp');     
        $this->db->join('pos_sales ps', 'ps.pos_sale_id = pp.pos_sale_id');
        $this->db->join('customers c', 'c.customer_id = ps.customer_id', 'left outer');
        $this->db->join('system_users su', 'su.system_user_id = pp.system_user_id', 'left outer');
        $this->db->where( array('pp.is_void' => 0, 'ps.outlet_id' => $this->session->userdata('pos_outlet_id')));

        if ($this->session->userdata('pos_user_is_super_admin') != 1) {
            $this->db->where( array('ps.system_user_id' => $this->session->userdata('pos_system_user_id')));
        }

        if ($from_date){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($from_date)));
        }
        if ($to_date != ''){
            $this->db->where('DATE_FORMAT(pp.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($to_date)));
        }
        if ($customer_id != ''){
             $this->db->where('ps.customer_id',$customer_id);
        }
        
        $this->db->order_by("pp.pos_payment_id", "desc");

        return $this->db->get()->result();

    }

    function get_expense_report(){
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        $this->db->select("e.*, su.first_name AS 'system_user_first_name', su.last_name AS 'system_user_last_name'");
        $this->db->from('expenses e');  
        $this->db->join('system_users su', 'su.system_user_id = e.system_user_id', 'left outer');      
        $this->db->where( array('is_void' => 0, 'outlet_id' => $this->session->userdata('pos_outlet_id')));

        if ($this->session->userdata('pos_user_is_super_admin') != 1) {
            $this->db->where( array('system_user_id' => $this->session->userdata('pos_system_user_id')));
        }

        if ($from_date){
            $this->db->where('DATE_FORMAT(e.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($from_date)));
        }
        if ($to_date != ''){
            $this->db->where('DATE_FORMAT(e.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($to_date)));
        }
        
        $this->db->order_by("e.expense_id", "desc");

        return  $this->db->get()->result();

    }

    //PROFIT & LOSS
    function get_total_purchases() {
        $total_purchases = 0;

        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');


        $this->db->select("COALESCE(SUM(grn.total_amount),0) AS 'total_amount'");
        $this->db->from('goods_receipt_notes grn');
        $this->db->where(array('grn.is_void' => 0, 'grn.outlet_id' => $this->session->userdata('pos_outlet_id')));

        if ($this->session->userdata('pos_user_is_super_admin') != 1) {
            $this->db->where( array('created_by' => $this->session->userdata('pos_system_user_id')));
        }

        $this->db->where('grn.receival_date >= ',date('Y-m-d', strtotime($from_date)));
        $this->db->where('grn.receival_date <= ',date('Y-m-d', strtotime($to_date)));

        $result =  $this->db->get()->result();

        foreach ($result as $row) {
            $total_purchases = $row->total_amount;
        }

        return $total_purchases;
    }

    function get_total_purchase_returns() {
        $total_purchase_returns = 0;

        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');


        $this->db->select("COALESCE(SUM(gren.total_amount),0) AS 'total_amount'");
        $this->db->from('goods_return_notes gren');
        $this->db->where(array('gren.is_void' => 0, 'gren.outlet_id' => $this->session->userdata('pos_outlet_id')));

        if ($this->session->userdata('pos_user_is_super_admin') != 1) {
            $this->db->where( array('created_by' => $this->session->userdata('pos_system_user_id')));
        }

        $this->db->where('gren.return_date >= ',date('Y-m-d', strtotime($from_date)));
        $this->db->where('gren.return_date <= ',date('Y-m-d', strtotime($to_date)));

        $result =  $this->db->get()->result();

        foreach ($result as $row) {
            $total_purchase_returns = $row->total_amount;
        }

        return $total_purchase_returns;

    }
    function get_total_expenses() {
        $total_expenses = 0;

        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');


        $this->db->select("COALESCE(SUM(e.expense_amount),0) AS 'expense_amount'");
        $this->db->from('expenses e');
        $this->db->where(array('e.is_void' => 0, 'e.outlet_id' => $this->session->userdata('pos_outlet_id')));

        if ($this->session->userdata('pos_user_is_super_admin') != 1) {
            $this->db->where( array('system_user_id' => $this->session->userdata('pos_system_user_id')));
        }

        $this->db->where('e.expense_date >= ',date('Y-m-d', strtotime($from_date)));
        $this->db->where('e.expense_date <= ',date('Y-m-d', strtotime($to_date)));

        $result =  $this->db->get()->result();

        foreach ($result as $row) {
            $total_expenses = $row->expense_amount;
        }

        return $total_expenses;
    }

    function get_total_pos_sales() {
        $total_pos_sales = 0;

        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        $this->db->select("COALESCE(SUM(ps.total_sale),0) AS 'total_sale'");
        $this->db->from('pos_sales ps');
        $this->db->where(array('ps.is_void' => 0, 'ps.is_held' => 0, 'ps.is_completed' => 1, 'ps.outlet_id' => $this->session->userdata('pos_outlet_id')));

        if ($this->session->userdata('pos_user_is_super_admin') != 1) {
            $this->db->where( array('ps.system_user_id' => $this->session->userdata('pos_system_user_id')));
        }

        $this->db->where('DATE_FORMAT(ps.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($from_date)));
        $this->db->where('DATE_FORMAT(ps.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($to_date)));

        $result =  $this->db->get()->result();

        foreach ($result as $row) {
            $total_pos_sales = $row->total_sale;
        }

        return $total_pos_sales;

    }

    function get_total_pos_paid_sales() {
        $total_pos_paid_sales = 0;

        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        $this->db->select("COALESCE(SUM(ps.total_paid),0) AS 'total_paid', COALESCE(SUM(ps.change_amount),0) AS 'change_amount'");
        $this->db->from('pos_sales ps');
        $this->db->where(array('ps.is_void' => 0, 'ps.is_held' => 0, 'ps.is_completed' => 1, 'ps.outlet_id' => $this->session->userdata('pos_outlet_id')));

        if ($this->session->userdata('pos_user_is_super_admin') != 1) {
            $this->db->where( array('ps.system_user_id' => $this->session->userdata('pos_system_user_id')));
        }

        $this->db->where('DATE_FORMAT(ps.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($from_date)));
        $this->db->where('DATE_FORMAT(ps.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($to_date)));

        $result =  $this->db->get()->result();

        foreach ($result as $row) {
            $total_pos_paid_sales = $row->total_paid - $row->change_amount;
        }

        return $total_pos_paid_sales;

    }

    function get_total_online_sales() {
        $total_online_sales = 0;
        $main_outlet = false;


        // $this->db->from('outlets');
        // $this->db->where( array('outlet_id' => $this->session->userdata('pos_outlet_id')));
        
        // $outlet = $this->db->get()->result();

        // foreach ($outlet as $row) {
        //     if ($row->is_main == 1){
        //         $main_outlet = true;
        //     }
        // }

        // if ($main_outlet == true) {
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');

        $this->db->select("COALESCE(SUM(os.ord_total),0) AS 'ord_total'");
        $this->db->from('order_summary os');
        $this->db->where(array('os.ord_dispatch_outlet_id' => $this->session->userdata('pos_outlet_id')));

        if ($this->session->userdata('pos_user_is_super_admin') != 1) {
            $this->db->where( array('os.ord_dispatch_system_user_id' => $this->session->userdata('pos_system_user_id')));
        }

        $this->db->group_start()
        ->where('os.ord_order_status',1)
        ->or_where('os.ord_order_status',2)
        ->or_where('os.ord_order_status',3)
        ->group_end();

        $this->db->where('DATE_FORMAT(os.ord_date, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($from_date)));
        $this->db->where('DATE_FORMAT(os.ord_date, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($to_date)));

        $result =  $this->db->get()->result();

        foreach ($result as $row) {
            $total_online_sales = $row->ord_total;
        }
        //}
        
        return $total_online_sales;

    }

    function get_profit_loss_report(){
        $total_purchases = $this->get_total_purchases();
        $total_purchase_returns = $this->get_total_purchase_returns();
        $total_expenses = $this->get_total_expenses();
        $total_pos_sales = $this->get_total_pos_sales();
        $total_pos_paid_sales = $this->get_total_pos_paid_sales();
        $total_online_sales = $this->get_total_online_sales();

        $arr_return = array('total_purchases' => $total_purchases,'total_purchase_returns' => $total_purchase_returns,'total_expenses' => $total_expenses,'total_pos_sales' => $total_pos_sales,'total_pos_paid_sales' => $total_pos_paid_sales,'total_online_sales' => $total_online_sales);

        return $arr_return;
    }

    //DASHBOARD STATISTICS
    function get_today_total_sales() {
        $today_total_sales = 0;

        $this->db->select("COALESCE(SUM(ps.total_sale),0) AS 'total_sale'");
        $this->db->from('pos_sales ps');
        $this->db->where(array('ps.is_void' => 0, 'ps.is_held' => 0, 'ps.is_completed' => 1, 'ps.outlet_id' => $this->session->userdata('pos_outlet_id')));

        if ($this->session->userdata('pos_user_is_super_admin') != 1) {
            $this->db->where( array('ps.system_user_id' => $this->session->userdata('pos_system_user_id')));
        }

        $this->db->where('DATE(ps.created_on)',date('Y-m-d'));

        $result =  $this->db->get()->result();

        foreach ($result as $row) {
            $today_total_sales = $row->total_sale;
        }

        return $today_total_sales;
    }

    function get_today_total_sales_payments(){
        $today_total_sales_payments = 0;
        //$today_total_sales = 0;
        //$today_total_sales_paid = 0;

        $this->db->select("COALESCE(SUM(ps.total_paid),0) AS 'total_paid', COALESCE(SUM(ps.change_amount),0) AS 'change_amount'");
        $this->db->from('pos_sales ps');
        $this->db->where(array('ps.is_void' => 0, 'ps.is_held' => 0, 'ps.is_completed' => 1, 'ps.outlet_id' => $this->session->userdata('pos_outlet_id')));

        if ($this->session->userdata('pos_user_is_super_admin') != 1) {
            $this->db->where( array('ps.system_user_id' => $this->session->userdata('pos_system_user_id')));
        }

        $this->db->where('DATE(ps.created_on)',date('Y-m-d'));

        $result =  $this->db->get()->result();

        foreach ($result as $row) {
            $today_total_sales_payments = $row->total_paid - $row->change_amount;
        }

        return $today_total_sales_payments;
    }

    function get_today_total_sales_due() {
        $today_total_sales_due = 0;
        //$today_total_sales = 0;
        //$today_total_sales_paid = 0;

        $this->db->select("COALESCE(SUM(ps.total_sale),0) AS 'total_sale', COALESCE(SUM(ps.total_paid),0) AS 'total_paid', COALESCE(SUM(ps.change_amount),0) AS 'change_amount'");
        $this->db->from('pos_sales ps');
        $this->db->where(array('ps.is_void' => 0, 'ps.is_held' => 0, 'ps.is_completed' => 1, 'ps.outlet_id' => $this->session->userdata('pos_outlet_id')));

        if ($this->session->userdata('pos_user_is_super_admin') != 1) {
            $this->db->where( array('ps.system_user_id' => $this->session->userdata('pos_system_user_id')));
        }

        $this->db->where('DATE(ps.created_on)',date('Y-m-d'));

        $result =  $this->db->get()->result();

        foreach ($result as $row) {
            $today_total_sales_due = $row->total_sale - ($row->total_paid - $row->change_amount);
        }

        return $today_total_sales_due;
    }

    function get_today_total_expenses() {
        $today_total_expenses = 0;

        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');


        $this->db->select("COALESCE(SUM(e.expense_amount),0) AS 'expense_amount'");
        $this->db->from('expenses e');
        $this->db->where(array('e.is_void' => 0, 'e.outlet_id' => $this->session->userdata('pos_outlet_id')));

        if ($this->session->userdata('pos_user_is_super_admin') != 1) {
            $this->db->where( array('e.system_user_id' => $this->session->userdata('pos_system_user_id')));
        }

        $this->db->where('DATE(e.expense_date)', date('Y-m-d'));

        $result =  $this->db->get()->result();

        foreach ($result as $row) {
            $today_total_expenses = $row->expense_amount;
        }

        return $today_total_expenses;
    }

    function get_total_sales_orders() {
        $total_sales_orders = 0;

        $this->db->select("COALESCE(COUNT(ps.pos_sale_id),0) AS 'total_sales_orders'");
        $this->db->from('pos_sales ps');
        $this->db->where(array('ps.is_void' => 0, 'ps.is_held' => 0, 'ps.is_completed' => 1, 'ps.outlet_id' => $this->session->userdata('pos_outlet_id')));

        if ($this->session->userdata('pos_user_is_super_admin') != 1) {
            $this->db->where( array('ps.system_user_id' => $this->session->userdata('pos_system_user_id')));
        }

        $result =  $this->db->get()->result();

        foreach ($result as $row) {
            $total_sales_orders = $row->total_sales_orders;
        }

        return $total_sales_orders;

    }

    function get_total_held_orders() {
        $total_held_orders = 0;

        $this->db->select("COALESCE(COUNT(ps.pos_sale_id),0) AS 'total_held_orders'");
        $this->db->from('pos_sales ps');
        $this->db->where(array('ps.is_void' => 0, 'ps.is_held' => 1, 'ps.is_completed' => 0, 'ps.outlet_id' => $this->session->userdata('pos_outlet_id')));

        if ($this->session->userdata('pos_user_is_super_admin') != 1) {
            $this->db->where( array('ps.system_user_id' => $this->session->userdata('pos_system_user_id')));
        }

        $result =  $this->db->get()->result();

        foreach ($result as $row) {
            $total_held_orders = $row->total_held_orders;
        }

        return $total_held_orders;

    }

    function get_total_products() {
        $total_products = 0;

        $this->db->select("COALESCE(COUNT(p.product_id),0) AS 'total_products'");
        $this->db->from('products p');
        $this->db->join('outlet_products op', 'op.product_id = p.product_id');
        $this->db->where( array('p.is_deleted' => 0, 'op.outlet_id' => $this->session->userdata('pos_outlet_id')));

        $result =  $this->db->get()->result();

        foreach ($result as $row) {
            $total_products = $row->total_products;
        }

        return $total_products;

    }

    function get_total_customers(){
        $total_customers = 0;

        $this->db->select("COALESCE(COUNT(c.customer_id),0) AS 'total_customers'");
        $this->db->from('customers c');
        $this->db->where( array('c.is_deleted'=>0));

        $result =  $this->db->get()->result();

        foreach ($result as $row) {
            $total_customers = $row->total_customers;
        }

        return $total_customers;
    }

    function get_monthly_sales_stat($month) {
        $monthly_total_sales = 0;

        //$from_date = $this->input->post('from_date');
        //$to_date = $this->input->post('to_date');

        $this->db->select("COALESCE(SUM(ps.total_sale),0) AS 'monthly_total_sales'");
        $this->db->from('pos_sales ps');
        $this->db->where(array('ps.is_void' => 0, 'ps.is_held' => 0, 'ps.is_completed' => 1, 'ps.outlet_id' => $this->session->userdata('pos_outlet_id')));

        if ($this->session->userdata('pos_user_is_super_admin') != 1) {
            $this->db->where( array('ps.system_user_id' => $this->session->userdata('pos_system_user_id')));
        }

        $this->db->where('MONTH(DATE(ps.created_on))',$month);
        $this->db->where('YEAR(DATE(ps.created_on))', date('Y'));

        $result =  $this->db->get()->result();

        foreach ($result as $row) {
            $monthly_total_sales = $row->monthly_total_sales;
        }

        return $monthly_total_sales;
    }

    function get_monthly_sales_statistics(){

        $monthly_statistics = [];

        for ($x = 0; $x <= 11; $x++) {
            $monthly_stat = $this->get_monthly_sales_stat($x+1);
            $monthly_statistics[$x] = $monthly_stat;
        }

        return $monthly_statistics;
    }

    ///// QUOTATIONS /////////
    function get_quotations_list() {

        $date_from = $this->input->post('date_from');
        $date_to = $this->input->post('date_to');

        $this->db->select("q.*, c.first_name, c.last_name, c.email_address, c.phone_number, c.credit_limit, c.opening_balance, c.loyalty_enrolled, c.loyalty_number, c.loyalty_enrollment_date, c.profile_picture, c.profile_picture_thumb, su.first_name AS 'system_user_first_name', su.last_name AS 'system_user_last_name'");
        $this->db->from('pos_quotations q');     
        $this->db->join('customers c', 'c.customer_id = q.customer_id', 'left outer');  
        $this->db->join('system_users su', 'su.system_user_id = q.system_user_id', 'left outer');

        $this->db->where( array('q.outlet_id' => $this->session->userdata('pos_outlet_id')));

        if ($this->session->userdata('pos_user_is_super_admin') != 1) {
            $this->db->where( array('q.system_user_id' => $this->session->userdata('pos_system_user_id')));
        }

        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(q.created_on, "%Y-%m-%d") >= ',date('Y-m-d', strtotime($date_from)));
        }
        if ($date_from != ''){
            $this->db->where('DATE_FORMAT(q.created_on, "%Y-%m-%d") <= ',date('Y-m-d', strtotime($date_to)));
        }

        $this->db->order_by("q.pos_quotation_id", "desc");
        
        $query = $this->db->get();
        $record_count = $query->num_rows();
        $records = $query->result();

        return $records;
    }
    function save_quotation(){
        $data = array(
            'system_user_id' =>  $this->session->userdata('pos_system_user_id'),
            'customer_id' => $this->input->post('customer_id'),
            'quotation_date' => $this->input->post('quotation_date'),
            'valid_until' => $this->input->post('valid_until'),
            'outlet_id' => $this->session->userdata('pos_outlet_id'),            
            'sub_total' => $this->input->post('total_detail_subtotal'),
            'delivery_fee' => $this->input->post('delivery_fee'),
            'discount' => $this->input->post('discount'),
            'total_amount' => $this->input->post('total_detail_total'),
            'total_quantity' => $this->input->post('total_detail_qty'),
            'comments' => $this->input->post('comments'),
            'created_by' => $this->session->userdata('system_user_id')
        );  

        $insert = $this->db->insert('pos_quotations', $data);
        $pos_quotation_id = $this->db->insert_id();
        if ($insert){

            //UPDATE Quotation NUMBER
            $quotation_prefix = '';
            $pos_quotation_number = '';
            $this->db->select("*");
            $this->db->from('prefixes'); 
            $this->db->where( array('document_name' => 'Quotation'));
            $prefix = $this->db->get()->result();

            foreach ($prefix as $row) {
                $quotation_prefix = $row->prefix_name;
            }
            if ($quotation_prefix != '') {
                $pos_quotation_number = $quotation_prefix . '-' . $pos_quotation_id;
            } else {
                $pos_quotation_number = $pos_quotation_id;
            }
            $data = array(
                'pos_quotation_number' => $pos_quotation_number
            );
            $this->db->where(array('pos_quotation_id' => $pos_quotation_id));
            $res = $this->db->update('pos_quotations', $data);

            //Quotation DETAILS
            $this->save_quotation_details($pos_quotation_id);

            $arr_return = array('res' => true,'dt' => 'Quotation created successfully.','id' => $pos_quotation_id);           
        }else{
            $arr_return = array('res' => false,'dt' => 'Could not create Quotation successfully. Please try again.','id' => '');
        }
        return $arr_return;
    }

    function save_quotation_details($pos_quotation_id){
        $q_detail_product_id = $this->input->post('q_detail_product_id');
        $q_detail_product_variation_id = $this->input->post('q_detail_product_variation_id');
        $q_unit_id = $this->input->post('q_unit_id');
        $q_detail_qty = $this->input->post('q_detail_qty');
        $q_detail_cost = $this->input->post('q_detail_cost');
        $q_detail_total = $this->input->post('q_detail_total');
        
        foreach( $q_detail_product_id as $key => $n ) {

            $product_id = $n;
            $product_variation_id = $q_detail_product_variation_id[$key];           
            $quantity = $q_detail_qty[$key];
            $unit_id = $q_unit_id[$key];
            $unit_price = $q_detail_cost[$key];
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

            //Quotation DETAILS
            $new_data = array(
                'pos_quotation_id' => $pos_quotation_id,
                'product_id' => $n,
                'product_variation_id' => $q_detail_product_variation_id[$key],
                'unit_id' => $q_unit_id[$key],
                'unit_price' => $q_detail_cost[$key],
                'tax_rate_id' => $tax_rate_id,
                'price_excl_tax' => $price_excl_tax,
                'unit_tax' => $unit_tax,
                'quantity' => $q_detail_qty[$key],
                'tax_amount' => $tax_amount,
                'line_total' => $q_detail_total[$key],
                'sub_total' => $q_detail_total[$key]
            );
            $insert = $this->db->insert('pos_quotation_details', $new_data);
            $quotation_detail_id = $this->db->insert_id();

        }
    }

    function get_pos_quotation($pos_quotation_id) {
        $this->db->select("q.*, c.first_name, c.last_name, c.email_address, c.phone_number, c.credit_limit, c.opening_balance, c.loyalty_enrolled, c.loyalty_number, c.loyalty_enrollment_date, c.profile_picture, c.profile_picture_thumb, su.first_name AS 'system_user_first_name', su.last_name AS 'system_user_last_name'");
        $this->db->from('pos_quotations q');     
        $this->db->join('customers c', 'c.customer_id = q.customer_id', 'left outer'); 
        $this->db->join('system_users su', 'su.system_user_id = q.system_user_id', 'left outer'); 
        $this->db->where( array('q.pos_quotation_id' => $pos_quotation_id));

        return $this->db->get()->result();

    }

    function get_pos_quotation_details($pos_quotation_id) {
        $this->db->select("qd.*, q.outlet_id, p.product_name, p.product_sku_code, p.product_reference_id, p.product_barcode, p.product_image, p.product_image_thumb, tr.tax_rate_name, tr.tax_rate_code, tr.tax_rate_value, u.unit_code, u.unit_name");
        $this->db->from('pos_quotation_details qd');     
        $this->db->join('units u', 'u.unit_id = qd.unit_id', 'left outer');
        $this->db->join('tax_rates tr', 'tr.tax_rate_id = qd.tax_rate_id', 'left outer');
        $this->db->join('products p', 'p.product_id = qd.product_id');
        $this->db->join('pos_quotations q', 'q.pos_quotation_id = qd.pos_quotation_id'); 
        $this->db->where( array('qd.pos_quotation_id' => $pos_quotation_id)); 

        $pos_quotation_details = $this->db->get()->result();

        $i = 0;
        foreach($pos_quotation_details as $row){
            $pos_quotation_details[$i]->attributes = $this->get_product_variation_attributes($row->product_variation_id);
            $pos_quotation_details[$i]->units = $this->get_product_units($row->product_id);
            $i++;
        }
        return $pos_quotation_details;

        // return $this->db->get()->result();
    }

    function get_num_pos_quotation_details($pos_quotation_id) {
        $this->db->select("qd.*, p.product_name, p.product_sku_code, p.product_reference_id, p.product_barcode, p.product_image, p.product_image_thumb, tr.tax_rate_name, tr.tax_rate_code, tr.tax_rate_value");
        $this->db->from('pos_quotation_details qd');     
        $this->db->join('units u', 'u.unit_id = qd.unit_id', 'left outer');
        $this->db->join('tax_rates tr', 'tr.tax_rate_id = qd.tax_rate_id', 'left outer');
        $this->db->join('products p', 'p.product_id = qd.product_id');
        $this->db->join('pos_quotations q', 'q.pos_quotation_id = qd.pos_quotation_id'); 
        $this->db->where( array('qd.pos_quotation_id' => $pos_quotation_id)); 

        return $this->db->count_all_results();
    }

    function get_pos_quotation_tax_details($pos_quotation_id){
        $this->db->select("qd.tax_rate_id, tr.tax_rate_code, tr.tax_rate_value, (SELECT COALESCE(SUM(qd2.price_excl_tax * qd2.quantity),0) FROM pos_quotation_details qd2 WHERE qd2.pos_quotation_id = qd.pos_quotation_id AND qd2.tax_rate_id = qd.tax_rate_id) AS 'vatable_amount', (SELECT COALESCE(SUM(qd3.unit_tax * qd3.quantity),0) FROM pos_quotation_details qd3 WHERE qd3.pos_quotation_id = qd.pos_quotation_id AND qd3.tax_rate_id = qd.tax_rate_id) AS 'vat_amount'");
        $this->db->from('pos_quotation_details qd');     
        $this->db->join('tax_rates tr', 'tr.tax_rate_id = qd.tax_rate_id');
        $this->db->join('pos_quotations q', 'q.pos_quotation_id = qd.pos_quotation_id'); 
        $this->db->group_by('qd.tax_rate_id');
        $this->db->where( array('qd.pos_quotation_id' => $pos_quotation_id)); 

        return $this->db->get()->result();
    }

    function update_quotation(){
        $pos_quotation_id = $this->input->post('pos_quotation_id');

        $data = array(
            'quotation_date' => $this->input->post('quotation_date'),
            'valid_until' => $this->input->post('valid_until'),
            'sub_total' => $this->input->post('total_detail_subtotal'),
            'delivery_fee' => $this->input->post('delivery_fee'),
            'discount' => $this->input->post('discount'),
            'total_amount' => $this->input->post('total_detail_total'),
            'total_quantity' => $this->input->post('total_detail_qty'),
            'comments' => $this->input->post('comments')
        );  

        $this->db->where(array('pos_quotation_id' => $pos_quotation_id));
        $update = $this->db->update('pos_quotations', $data);
        
        if ($update){

            //Quotation DETAILS
            $this->update_quotation_details($pos_quotation_id);

            $arr_return = array('res' => true,'dt' => 'Quotation updated successfully.','id' => $pos_quotation_id);
        }else{
            $arr_return = array('res' => false,'dt' => 'Could not update Quotation successfully. Please try again.','id' => $pos_quotation_id);
        }
        return $arr_return;
    }

    function update_quotation_details($pos_quotation_id){
        $q_detail_product_id = $this->input->post('q_detail_product_id');
        $q_detail_product_variation_id = $this->input->post('q_detail_product_variation_id');
        $q_unit_id = $this->input->post('q_unit_id');
        $q_detail_qty = $this->input->post('q_detail_qty');
        $q_detail_cost = $this->input->post('q_detail_cost');
        $q_detail_total = $this->input->post('q_detail_total');        

        $pos_quotation_details = $this->get_pos_quotation_details($pos_quotation_id);

        foreach ($pos_quotation_details as $row){

            $found = false;
            $product_id = $row->product_id;
            $product_variation_id = $row->product_variation_id;
            $pos_quotation_detail_id = $row->pos_quotation_detail_id;
            $outlet_id = $this->input->post('outlet_id');

            foreach( $q_detail_product_id as $key => $n ) {
                if ($product_id == $n && $product_variation_id == $q_detail_product_variation_id[$key]){
                    // $already_dispatched_qty = $row->dispatched_quantity;
                    $found = true;
                    break;
                }
            }

            if ($found == false){
               $this->db->where('pos_quotation_detail_id', $pos_quotation_detail_id);
               $this->db->delete('pos_quotation_details');     
            }else{
                foreach( $q_detail_product_id as $key => $n ) {
                    if ($product_id == $n && $product_variation_id == $q_detail_product_variation_id[$key]){

                        $quantity = $q_detail_qty[$key];
                        $unit_id = $q_unit_id[$key];
                        $unit_price = $q_detail_cost[$key];
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

                        //Quotation DETAILS
                        $data = array(
                            'unit_id' => $q_unit_id[$key],
                            'unit_price' => $q_detail_cost[$key],
                            'tax_rate_id' => $tax_rate_id,
                            'price_excl_tax' => $price_excl_tax,
                            'unit_tax' => $unit_tax,
                            'quantity' => $q_detail_qty[$key],
                            'tax_amount' => $tax_amount,
                            'line_total' => $q_detail_total[$key],
                            'sub_total' => $q_detail_total[$key]
                        );

                        $this->db->where(array('pos_quotation_id' => $pos_quotation_id, 'product_id' => $product_id, 'product_variation_id' => $product_variation_id));
                        $this->db->update('pos_quotation_details', $data);
                    }
                }

            }
        }

        $pos_quotation_details = $this->get_pos_quotation_details($pos_quotation_id);

        foreach($q_detail_product_id as $key => $n ) {
            $found = false;
            foreach ($pos_quotation_details as $row){
                if ($row->product_id == $n && $row->product_variation_id == $q_detail_product_variation_id[$key]){
                    $pos_quotation_detail_id = $row->pos_quotation_detail_id;
                    $found = true;
                    break;
                }
            }
            if ($found == false){

                $product_id = $n;
                $product_variation_id = $q_detail_product_variation_id[$key];           
                $quantity = $q_detail_qty[$key];
                $unit_id = $q_unit_id[$key];
                $unit_price = $q_detail_cost[$key];
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

                //Quotation DETAILS
                $new_data = array(
                    'pos_quotation_id' => $pos_quotation_id,
                    'product_id' => $n,
                    'product_variation_id' => $q_detail_product_variation_id[$key],
                    'unit_id' => $q_unit_id[$key],
                    'unit_price' => $q_detail_cost[$key],
                    'tax_rate_id' => $tax_rate_id,
                    'price_excl_tax' => $price_excl_tax,
                    'unit_tax' => $unit_tax,
                    'quantity' => $q_detail_qty[$key],
                    'tax_amount' => $tax_amount,
                    'line_total' => $q_detail_total[$key],
                    'sub_total' => $q_detail_total[$key]
                );

                $this->db->insert('pos_quotation_details', $new_data);
            }
        }
    }

    function quotation_void_valid($pos_quotation_id){
        $is_void = true;

        $pos_quotation = $this->get_pos_quotation($pos_quotation_id);

        foreach ($pos_quotation as $row) {
            if ($row->is_void == 0) {
                $is_void = false;
            } else {
                $is_void = true;
            }
        }
        if ($is_void == false) {
            $arr_return = array('res' => true,'dt' => 'Not Void.');
        } else {
            $arr_return = array('res' => false,'dt' => 'This Quotation has already been voided.');
        }
        return $arr_return;
    }

    function submit_void_pos_quotation(){
        
        $pos_quotation_id = $this->input->post('pos_quotation_id');

        $void_date = date('Y-m-d H:i:s');
        $void_reason = $this->input->post('void_reason');

        $data = array(
            'is_void' => 1,
            'void_reason' => $void_reason,
            'void_system_user_id' => $this->session->userdata('pos_system_user_id'),
            'void_date' => $void_date
        );
        $this->db->where( array('pos_quotation_id' => $pos_quotation_id));
        $void = $this->db->update('pos_quotations', $data);

        if ($void) {

            $pos_quotation_number = '';
            $pos_quotation = $this->get_pos_quotation($pos_quotation_id);
            foreach ($pos_quotation as $row) {
                $pos_quotation_number = $row->pos_quotation_number;
            }

            //NOTIFICATION
            $data = array(
                'notification_type' => 'Quotation Voided',
                'notification_ref_id' => $pos_quotation_id,
                'notification_details' => 'Quotation <b>#' . $pos_quotation_number . '</b> has been voided on  <b>' . $void_date . '</b> by <b>' . $this->session->userdata('pos_user_first_name') . ' ' . $this->session->userdata('pos_user_last_name') . '</b>. Void Reason: <b>' . $void_reason . '</b>',
                'notification_ref_link' => 'pos/quotations/view/' . $pos_quotation_id
            );
            $this->db->insert('notifications',$data);

             $arr_return = array('res' => true,'dt' => 'Quotation voided successfully.');
        } else {
            $arr_return = array('res' => false,'dt' => 'There was an error trying to void this Quotation. Please try again.');
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