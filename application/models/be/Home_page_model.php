<?php
class Home_page_model extends CI_Model {

	//HOME TOP CATEGORIES
	function get_home_top_product_categories(){
		$this->db->select('htpc.*, pc.product_category_sku_code, pc.product_category_reference_id, pc.product_category_name, pc.cover_image, pc.thumb_image, i.icon_name');
		$this->db->from('home_top_product_categories htpc');
		$this->db->join('product_categories pc', 'pc.product_category_id = htpc.product_category_id');
		$this->db->join('icons i', 'i.icon_id = pc.icon_id', 'left outer');
        $this->db->where( array('htpc.is_deleted'=>0));
        
        $main_categories = $this->db->get()->result();
        $i = 0;
        foreach($main_categories as $maincat){
            $main_categories[$i]->sub = $this->get_home_top_product_subcategories($maincat->home_top_product_category_id);
            $i++;
        }
        return $main_categories;		
	}

	function get_home_top_product_subcategories($home_top_product_category_id){
		$this->db->select('htps.*, pc.product_category_sku_code, pc.product_category_reference_id, pc.product_category_name, pc.cover_image, pc.thumb_image, i.icon_name');
		$this->db->from('home_top_product_subcategories htps');
		$this->db->join('product_categories pc', 'pc.product_category_id = htps.product_category_id');
		$this->db->join('icons i', 'i.icon_id = pc.icon_id', 'left outer');
        $this->db->where( array('htps.is_deleted'=>0, 'htps.home_top_product_category_id'=>$home_top_product_category_id));

        return $this->db->get()->result();   
	}

	function save_home_top_product_category($data){
		$insert = $this->db->insert('home_top_product_categories', $data);
		$insert_id = $this->db->insert_id();
		if ($insert){

			//SUB CATEGORIES
			if ($this->input->post('home_top_product_subcategory_id') != ''){
				$this->save_home_top_product_subcategories($insert_id);
			}

			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Saved successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not save successfully. Please try again.');
		}
		return $arr_return;

	}

	function save_home_top_product_subcategories($home_top_product_category_id){
		$product_category_id = $this->input->post('home_top_product_subcategory_id');		
		foreach ($product_category_id as $temp_id){
			$new_data = array(
				'home_top_product_category_id' => $home_top_product_category_id,
				'product_category_id' => $temp_id
			);
			$insert = $this->db->insert('home_top_product_subcategories', $new_data);
		}				
	}

	function get_home_top_product_category2($home_top_product_category_id){
		$this->db->select('htpc.*');
		$this->db->from('home_top_product_categories htpc');
        $this->db->where( array('htpc.home_top_product_category_id'=>$home_top_product_category_id));

        $main_category = $this->db->get()->result();
        foreach($main_category as $maincat){
            $main_category[0]->sub = $this->get_home_top_product_subcategories($home_top_product_category_id);
        }
        return $main_category;
	}

	function update_home_top_product_category($data,$home_top_product_category_id){
		$this->db->where(array('home_top_product_category_id'=>$home_top_product_category_id));
		$update = $this->db->update('home_top_product_categories', $data);
		if ($update){

			//SUB CATEGORIES
			if ($this->input->post('home_top_product_subcategory_id') != ''){
				$this->update_home_top_product_subcategories($home_top_product_category_id);
			}

			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update successfully. Please try again.');
		}
		return $arr_return;

	}

	function update_home_top_product_subcategories($home_top_product_category_id){
		$product_category_id = $this->input->post('home_top_product_subcategory_id');
		$product_subcategories = $this->get_home_top_product_subcategories($home_top_product_category_id);

		foreach ($product_subcategories as $row){
			$found = false;
			foreach ($product_category_id as $temp_id){
				if ($row->product_category_id == $temp_id){
					$found = true;
					break;
				}
			}
			if ($found == false){
			   $this->db->where('home_top_product_category_id', $home_top_product_category_id);
			   $this->db->where('product_category_id', $row->product_category_id);			   
			   $this->db->delete('home_top_product_subcategories'); 				
			}
		}

		$product_subcategories = $this->get_home_top_product_subcategories($home_top_product_category_id);
	
		foreach ($product_category_id as $temp_id){
			$found = false;
			foreach ($product_subcategories as $row){
				if ($row->product_category_id == $temp_id){
					$found = true;
					break;
				}
			}
			if ($found == false){
				$new_data = array(
					'home_top_product_category_id' => $home_top_product_category_id,
					'product_category_id' => $temp_id
				);
				$this->db->insert('home_top_product_subcategories', $new_data);
			}
		}
	}

	function delete_home_top_product_category($home_top_product_category_id){

	   $this->db->where('home_top_product_category_id', $home_top_product_category_id);
	   $this->db->delete('home_top_product_categories'); 				

	   $this->db->where('home_top_product_category_id', $home_top_product_category_id);
	   $this->db->delete('home_top_product_subcategories'); 

	   $arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Deleted successfully');	

	   return $arr_return;		

	}

	//HOME FEATURED CATEGORIES
	function get_home_featured_product_categories(){
		$this->db->select('hfpc.*, pc.product_category_sku_code, pc.product_category_reference_id, pc.product_category_name, pc.cover_image, pc.thumb_image, i.icon_name');
		$this->db->from('home_featured_product_categories hfpc');
		$this->db->join('product_categories pc', 'pc.product_category_id = hfpc.product_category_id');
		$this->db->join('icons i', 'i.icon_id = pc.icon_id', 'left outer');
        $this->db->where( array('hfpc.is_deleted'=>0));
        
        return $this->db->get()->result();		
	}

	function save_home_featured_product_categories(){
		if ($this->input->post('product_category_id') != ''){
			$product_category_id = $this->input->post('product_category_id');
			$home_featured_product_categories = $this->get_home_featured_product_categories();

			foreach ($home_featured_product_categories as $row){
				$found = false;
				foreach ($product_category_id as $temp_id){
					if ($row->product_category_id == $temp_id){
						$found = true;
						break;
					}
				}
				if ($found == false){
				   $this->db->where('product_category_id', $row->product_category_id);			   
				   $this->db->delete('home_featured_product_categories'); 				
				}
			}

			$home_featured_product_categories = $this->get_home_featured_product_categories();
		
			foreach ($product_category_id as $temp_id){
				$found = false;
				foreach ($home_featured_product_categories as $row){
					if ($row->product_category_id == $temp_id){
						$found = true;
						break;
					}
				}
				if ($found == false){
					$new_data = array(
						'product_category_id' => $temp_id
					);
					$this->db->insert('home_featured_product_categories', $new_data);
				}
			}
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Saved successfully');
		} else {
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Saved successfully');	
		}
		return $arr_return;
	}

	//PROMO BANNERS
	function get_home_promo_banners(){
		$this->db->from('home_promo_banners');
		$this->db->where( array('is_deleted'=>0));
		return $this->db->get()->result();
	}
	function save_promo_banner(){
		if(basename($_FILES['promo_banner']['name'])!=''){
				
			$upload_config['upload_path'] = './uploads/home_promo_banners/';
			$upload_config['allowed_types'] = 'gif|jpg|jpeg|png';
			//$upload_config['file_name'] = $imagefilename;
			$upload_config['max_size']	= '0';
			$upload_config['max_width']  = '0';
			$upload_config['max_height']  = '0';
				
			$this->load->library('upload');
			$this->upload->initialize($upload_config);
				
			$q = $this->upload->do_upload('promo_banner');
			
			if($q){				
				$det = $this->upload->data();	
				$data = array(
					'home_promo_banner_image' => $det['file_name'],
					'home_promo_banner_link' => $this->input->post('home_promo_banner_link'),
					'is_active' => $this->input->post('is_active')
				);
				$insert = $this->db->insert('home_promo_banners', $data);
				$insert_id = $this->db->insert_id();
				if ($insert){
					$this->createThumbnail($insert_id, $det['file_name']);
					$arr_return = array('res' => true,'dt' => 'Promo Banner added successfully.');
				}else{
					$arr_return = array('res' => false,'dt' => 'Could not add Promo Banner successfully. Please try again.');
				}
			}else{
				$arr_return = array('res' => false,'dt' => $this->upload->display_errors());
			}
		}else{
			$arr_return = array('res' => false,'dt' => 'Please choose an image to upload');
		}    		
		return $arr_return;
	}
	function get_home_promo_banner($home_promo_banner_id){
		$this->db->from('home_promo_banners');
		$this->db->where( array('home_promo_banner_id'=>$home_promo_banner_id));
		return $this->db->get()->result_array();
	}
	function get_home_promo_banner2($home_promo_banner_id){
		$this->db->from('home_promo_banners');
		$this->db->where( array('home_promo_banner_id'=>$home_promo_banner_id));
		return $this->db->get()->result();
	}
	function update_promo_banner($home_promo_banner_id){
		if(basename($_FILES['promo_banner']['name'])!=''){
				
			$upload_config['upload_path'] = './uploads/home_promo_banners/';
			$upload_config['allowed_types'] = 'gif|jpg|jpeg|png';
			//$upload_config['file_name'] = $imagefilename;
			$upload_config['max_size']	= '0';
			$upload_config['max_width']  = '0';
			$upload_config['max_height']  = '0';
				
			$this->load->library('upload');
			$this->upload->initialize($upload_config);
				
			$q = $this->upload->do_upload('promo_banner');
			
			if($q){				
				$det = $this->upload->data();	
				$data = array(
					'home_promo_banner_image' => $det['file_name'],
					'home_promo_banner_link' => $this->input->post('home_promo_banner_link'),
					'is_active' => $this->input->post('is_active')
				);
				$this->db->where( array('home_promo_banner_id'=>$home_promo_banner_id));
				$update = $this->db->update('home_promo_banners', $data);
				$this->createThumbnail($home_promo_banner_id, $det['file_name']);
				if ($update){
					$arr_return = array('res' => true,'dt' => 'Promo Banner updated successfully.');
				}else{
					$arr_return = array('res' => false,'dt' => 'Could not update Promo Banner successfully. Please try again.');
				}
			}else{
				$arr_return = array('res' => false,'dt' => $this->upload->display_errors());
			}
		}else{
			$data = array(
				'home_promo_banner_link' => $this->input->post('home_promo_banner_link'),
				'is_active' => $this->input->post('is_active')
			);
			$this->db->where( array('home_promo_banner_id'=>$home_promo_banner_id));
			$update = $this->db->update('home_promo_banners', $data);
			if ($update){
				//THUMBNAIL
				$home_promo_banner = $this->get_home_promo_banner2($home_promo_banner_id);
				foreach ($home_promo_banner as $row) {
					if ($row->home_promo_banner_image != '' && file_exists("./uploads/home_promo_banners/" . $row->home_promo_banner_image) && $row->home_promo_banner_image_thumb == '') {
						$this->createThumbnail($home_promo_banner_id, $row->home_promo_banner_image);
					}
				}
				$arr_return = array('res' => true,'dt' => 'Promo Banner updated successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => 'Could not update Promo Banner successfully. Please try again.');
			}
		}    		
		return $arr_return;
	}
	function delete_promo_banner($home_promo_banner_id){
		$data = array(
			'is_deleted'=> 1
		);			
		$this->db->where( array('home_promo_banner_id'=>$home_promo_banner_id));
		$delupdate = $this->db->update('home_promo_banners', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'Promo Banner deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => 'Error deleting Promo Banner');
		}
		return $arr_return;
	}

	function createThumbnail($home_promo_banner_id, $filename){
      	$source_path = './uploads/home_promo_banners/' . $filename;
      	$target_path = './uploads/home_promo_banners/thumbs/';
      
      	$config_manip = array(
          	'image_library' => 'gd2',
          	'source_image' => $source_path,
          	'new_image' => $target_path,
          	'create_thumb'    => TRUE,
          	'maintain_ratio' => TRUE,
          	'width' => 570,
          	'height' => 210
      	);
   
      	$this->load->library('image_lib', $config_manip);
      	if ($this->image_lib->resize()) {
      		$source_image_name = $this->image_lib->dest_image;
        	$extension = strrchr($source_image_name , '.');
        	$name = substr($source_image_name , 0, -strlen($extension));
        	$thumb_image_name = $name.'_thumb'.$extension;


			$this->db->where(array('home_promo_banner_id'=>$home_promo_banner_id));				
			$this->db->update('home_promo_banners', array('home_promo_banner_image_thumb' => $thumb_image_name));
      	}   
      	$this->image_lib->clear();
   }

}