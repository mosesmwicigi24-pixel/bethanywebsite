<?php if (! defined('BASEPATH')) exit('No direct script access allowed');

class Cart_model extends CI_Model{
    public function __construct(){
        parent::__construct();
        $this->load->library('flexi_cart');
        $this->load->library('flexi_cart_admin');
        $this->load->model('main_model');
    }

    function get_product($product_id){
		$this->db->from('products');
		$this->db->where( array('product_id'=>$product_id));
		return $this->db->get()->result();
	}

    function add($product_id){

        $product = $this->get_product($product_id);
        
        foreach ($product as $row){
            $id = $row->product_id;
            $product_id = $row->product_id;
            $product_code = $row->product_sku_code;
            $product_name = $row->product_name;
            if ($row->sale_price > 0) {
            	$price = $row->sale_price;
            }else {
            	$price = $row->regular_price;
            }
            $product_reference_id = $row->product_reference_id;
            $product_image = $row->product_image_thumb;
        }
        $product_size_id = "0";
        // if ($this->input->post('product_size_id') != null && $this->input->post('product_size_id') != '') {
        // 	$product_size_id = $this->input->post('product_size_id');
        // }
        $product_color_id = "0";
        // if ($this->input->post('product_color_id') != null && $this->input->post('product_color_id') != '') {
        // 	$product_color_id = $this->input->post('product_color_id');
        // }

        $product_variation_id = "0";
        $product_variation_description = "";

        if ($this->input->post('product_variation_id') != null && $this->input->post('product_variation_id') != '') {
            $product_variation_id = $this->input->post('product_variation_id');
            $product_variation_attributes = $this->main_model->get_product_variation_attributes($product_variation_id);

            // $variation_description = '';
            foreach ($product_variation_attributes as $row3){
                $product_variation_description = $product_variation_description . $row3->product_attribute_name . ' : <b>' . $row3->product_attribute_value . '</b>, ';
            }
            $product_variation_description =  '~ ' . substr($product_variation_description,0,-2) . '<br>';

            $product_variation = $this->main_model->get_product_variation($product_variation_id);

            foreach ($product_variation as $row2) {
               if ($row2->product_variation_sale_price > 0) {
                    $price = $row2->product_variation_sale_price;
                }else {
                    $price = $row2->product_variation_regular_price;
                } 
            }
        }

        $quantity = 1;
        if ($this->input->post('product_qty') != null && $this->input->post('product_qty') != '') {
            $quantity = $this->input->post('product_qty'); 
        }  
        $id = $id + $product_variation_id;       
        $cart_data = array(
            'id' => $id, 
            'name' => $product_name, 
            'quantity' => $quantity, 
            'price' => $price,
            'tax_rate' => 0,
            'product_id' => $product_id,
            'product_reference_id' => $product_reference_id,
            'product_image' => $product_image,
            'product_code' => $product_code,
            'product_size_id' => $product_size_id,
            'product_color_id' => $product_color_id,
            'product_variation_id' => $product_variation_id,
            'product_variation_description' => $product_variation_description
        );
            
        if ($this->flexi_cart->insert_items($cart_data, TRUE)){
            $arr_return = array('res' => true,'dt' => $this->flexi_cart->get_messages());            
        }else{
            $arr_return = array('res' => false,'dt' => $this->flexi_cart->get_messages());
        }

        return $arr_return;
    }

    function update_item_quantity(){
        $row_id = $this->input->post('row_id');
        $product_qty = $this->input->post('product_qty');

        $item_data = array(
            'row_id' => $row_id,
            'quantity' => $product_qty
        );

        $settings = array();
        if ($this->flexi_cart->update_cart($item_data, $settings, FALSE, TRUE)) {
            $arr_return = array('res' => true,'dt' => $this->flexi_cart->get_messages());            
        }else{
            $arr_return = array('res' => false,'dt' => $this->flexi_cart->get_messages());
        }

        return $arr_return;
    }

    function update_shipping_fee($shipping_fee){

        $items  = array(); 
        $settings = array();
        $cartitems = $this->flexi_cart->cart_items(); 
        $i = 0;
        foreach($cartitems as $row){
            $i++;
            $items[$i]['quantity'] = $row['quantity'];
        }
        $cart_data = $items;
        $shipping_data = array(
            'value' => (float)$shipping_fee,
            'tax_rate' => 0
        );  
        $settings['set_shipping'] = $shipping_data;

        if ($this->flexi_cart->update_cart($cart_data, $settings, FALSE, TRUE)) {
            $arr_return = array('res' => true,'dt' => $this->flexi_cart->get_messages());            
        }else{
            $arr_return = array('res' => false,'dt' => $this->flexi_cart->get_messages());
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