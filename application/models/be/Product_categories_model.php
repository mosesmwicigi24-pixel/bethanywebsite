<?php
class Product_categories_model extends CI_Model {
	
	function get_product_categories_list(){
		$this->db->select('product_categories.*, icons.icon_name');
		$this->db->from('product_categories');
		$this->db->join('icons', 'icons.icon_id = product_categories.icon_id', 'left outer');
        $this->db->where(array('product_categories.is_deleted'=>0));
		return $this->db->get()->result();
	}
	function get_nested_product_categories(){
		$this->db->select('product_categories.*, icons.icon_name');
		$this->db->from('product_categories');
		$this->db->join('icons', 'icons.icon_id = product_categories.icon_id', 'left outer');
        $this->db->where( array('product_categories.is_deleted'=>0, 'product_categories.product_category_parent_id'=>0));
        $parent_category = $this->db->get();
        
        $product_categories = $parent_category->result();
        $i = 0;
        foreach($product_categories as $p_cat){
            $product_categories[$i]->sub = $this->offer_sub_categories($p_cat->product_category_id);
            $i++;
        }
        return $product_categories;
	}
	function get_nested_product_subcategories_by_reference_id($product_category_reference_id) {
		$parent_category_id = 0;
		
		$this->db->select('product_categories.*, icons.icon_name');
		$this->db->from('product_categories');
		$this->db->join('icons', 'icons.icon_id = product_categories.icon_id', 'left outer');
        $this->db->where( array('product_categories.is_deleted'=>0, 'product_categories.product_category_parent_id'=>0));
        $parent_category = $this->db->get();
        
        $product_categories = $parent_category->result();
        $i = 0;
        foreach($product_categories as $p_cat){
            $product_categories[$i]->sub = $this->offer_sub_categories($p_cat->product_category_id);
            $i++;
        }
        return $product_categories;
	}
	function offer_sub_categories($id){
		$this->db->select('product_categories.*, icons.icon_name');
		$this->db->from('product_categories');
		$this->db->join('icons', 'icons.icon_id = product_categories.icon_id', 'left outer');
        $this->db->where( array('product_categories.is_deleted'=>0, 'product_categories.product_category_parent_id'=>$id));

        $child_category = $this->db->get();
        $product_categories = $child_category->result();
        $i=0;
        foreach($product_categories as $p_cat){
            $product_categories[$i]->sub = $this->offer_sub_categories($p_cat->product_category_id);
            $i++;
        }
        return $product_categories;       
    }

	function get_edit_nested_product_categories($product_category_id) {
		$this->db->select('*');
        $this->db->from('product_categories');
        $this->db->where( array('is_deleted'=>0, 'product_category_parent_id'=>0, 'product_category_id != '=>$product_category_id));
        $parent_category = $this->db->get();
        
        $product_categories = $parent_category->result();
        $i = 0;
        foreach($product_categories as $p_cat){
            $product_categories[$i]->sub = $this->edit_offer_sub_categories($p_cat->product_category_id, $product_category_id);
            $i++;
        }
        return $product_categories;
	}
	function edit_offer_sub_categories($id, $product_category_id){
        $this->db->select('*');
        $this->db->from('product_categories');
        $this->db->where( array('is_deleted'=>0, 'product_category_parent_id'=>$id, 'product_category_id != '=>$product_category_id));

        $child_category = $this->db->get();
        $product_categories = $child_category->result();
        $i=0;
        foreach($product_categories as $p_cat){
            $product_categories[$i]->sub = $this->edit_offer_sub_categories($p_cat->product_category_id, $product_category_id);
            $i++;
        }
        return $product_categories;       
    }

	function generate_product_category_sku($length = 6) {
    	$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
       	$randomString .= $characters[rand(0, strlen($characters) - 1)];
    	}
    	return $randomString;
	}
	
	function get_product_category_sku(){
		$product_category_sku = $this->generate_product_category_sku();
		$checktrue = $this->check_sku_exists($product_category_sku);
		while ($checktrue == true){
			$product_category_sku = $this->generate_product_category_sku();
			$checktrue = $this->check_sku_exists($product_category_sku);
		}
		return $product_category_sku;
	}
	function check_sku_exists($sku){
		$this->db->from('product_categories');
		$this->db->where( array('product_category_sku_code'=>$sku));
		$numrows = $this->db->get()->num_rows();
		if ($numrows > 0){
			return true;
		}else{
			return false;
		}
	}
	function get_product_category_sku_code($product_category_id){
		$sku_code = '';
		$this->db->from('product_categories');
		$this->db->where( array('product_category_id'=>$product_category_id));
		$result = $this->db->get()->result();
		foreach ($result as $r){
			$sku_code = $r->product_category_sku_code;
		}

		if ($sku_code == ''){
			$sku_code = $this->get_product_category_sku();
		}

		return $sku_code;
	}
	function get_product_category_subcategories($product_category_parent_id){
		$this->db->from('product_categories');
		$this->db->where( array('product_category_parent_id'=>$product_category_parent_id));
		return $this->db->get()->result();
	}

	function save($data){
		$insert = $this->db->insert('product_categories', $data);
		$insert_id = $this->db->insert_id();
		if ($insert){

			$this->upload_cover_image($insert_id);

			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Product Category added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Product Category successfully. Please try again.');
		}
		return $arr_return;
	}
	function product_category_exists($product_category_name){
		$this->db->where('product_category_name',$product_category_name);
		$this->db->where('is_deleted',0);
		$query = $this->db->get('product_categories');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}

	}
	function get_product_category($product_category_id){
		$this->db->from('product_categories');
		$this->db->where( array('product_category_id'=>$product_category_id));
		return $this->db->get()->result();
	}
	function product_category_update_exists($product_category_id,$product_category_name){
		$this->db->where('product_category_id !=',$product_category_id);
		$this->db->where('product_category_name',$product_category_name);
		$this->db->where('is_deleted',0);

		$q = $this->db->get('product_categories');

		if ($q->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function update($data,$product_category_id){
		$this->db->where(array('product_category_id'=>$product_category_id));
		$update = $this->db->update('product_categories', $data);
		if ($update){

			$this->upload_cover_image($product_category_id);

			//THUMBNAIL
			$product_category = $this->get_product_category($product_category_id);
			foreach ($product_category as $row) {
				if ($row->cover_image != '' && file_exists("./uploads/product_category_cover_images/" . $row->cover_image) && $row->thumb_image == '') {
					$this->createThumbnail($product_category_id, $row->cover_image);
				}
			}
			
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Product Category updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Product Category successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete($product_category_id){
		$data = array(
			'is_deleted'=> 1
		);			
		$this->db->where( array('product_category_id'=>$product_category_id));
		$delupdate = $this->db->update('product_categories', $data);
		
		if ($delupdate){

			//DELETE MENU ENTRY
			$this->db->where( array('product_category_id'=>$product_category_id));
			$this->db->delete('menu_categories');

			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Product Category deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Product Category');
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
			$this->db->where( array('product_category_id'=>$value));
			$res = $this->db->update('product_categories', $data);
			
			if ($res){}else{
				$msg_err2 = $msg_err2 . 'Error deleting Product Category';
			}
		}
		if ($msg_err == $msg_err2){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Bulk Transaction(s) completed successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Bulk Transaction(s) could not be completed successfully');
		}

		return $arr_return;
	}

	function upload_cover_image($product_category_id){
		if(basename($_FILES['cover_image']['name'])!=''){
			
			$upload_config['upload_path'] = './uploads/product_category_cover_images/';
			$upload_config['allowed_types'] = 'gif|jpg|jpeg|png';
			//$upload_config['file_name'] = $imagefilename;
			$upload_config['max_size']	= '0';
			$upload_config['max_width']  = '0';
			$upload_config['max_height']  = '0';
			
			$this->load->library('upload');
			$this->upload->initialize($upload_config);
			
			$q = $this->upload->do_upload('cover_image');
		
			if($q){				
				$det = $this->upload->data();	
				$this->db->where(array('product_category_id'=>$product_category_id));				
				$this->db->update('product_categories', array('cover_image' => $det['file_name']));
				$this->createThumbnail($product_category_id, $det['file_name']);
				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Product Category Cover Image uploaded successfully');
			}else{
				$arr_return = array('res' => false,'dt' => $this->upload->display_errors());
			}
		}else{
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Product Category Cover Image uploaded successfully');
		}
		return $arr_return;
	}

	function createThumbnail($product_category_id, $filename){
      	$source_path = './uploads/product_category_cover_images/' . $filename;
      	$target_path = './uploads/product_category_cover_images/thumbs/';
      
      	$config_manip = array(
          	'image_library' => 'gd2',
          	'source_image' => $source_path,
          	'new_image' => $target_path,
          	'create_thumb'    => TRUE,
          	'maintain_ratio' => TRUE,
          	'width' => 500,
          	'height' => 360
      	);
   
      	$this->load->library('image_lib', $config_manip);
      	if ($this->image_lib->resize()) {
      		$source_image_name = $this->image_lib->dest_image;
        	$extension = strrchr($source_image_name , '.');
        	$name = substr($source_image_name , 0, -strlen($extension));
        	$thumb_image_name = $name.'_thumb'.$extension;


			$this->db->where(array('product_category_id'=>$product_category_id));				
			$this->db->update('product_categories', array('thumb_image' => $thumb_image_name));
      	}   
      	$this->image_lib->clear();
   }

   function delete_cover_image($product_category_id) {
		$data = array(
			'cover_image'=> '',
			'thumb_image'=> ''
		);	
		$this->db->where(array('product_category_id'=>$product_category_id));		
		$update = $this->db->update('product_categories', $data);
		
		if ($update){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Cover Image deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Cover Image. Please try again.');
		}
		return $arr_return;

   }


}