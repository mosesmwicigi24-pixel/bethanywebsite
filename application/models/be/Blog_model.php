<?php
class Blog_model extends CI_Model {
	
	function get_blog_list(){
		$this->db->select('blog.*');
		$this->db->from('blog');
		$this->db->where( array('blog.is_deleted'=>0));
		return $this->db->get()->result();
	}
	function generate_blog_article_sku($length = 6) {
    	$characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
       	$randomString .= $characters[rand(0, strlen($characters) - 1)];
    	}
    	return $randomString;
	}
	
	function get_blog_article_sku(){
		$blog_article_sku = $this->generate_blog_article_sku();
		$checktrue = $this->check_sku_exists($blog_article_sku);
		while ($checktrue == true){
			$blog_article_sku = $this->generate_blog_article_sku();
			$checktrue = $this->check_sku_exists($blog_article_sku);
		}
		return $blog_article_sku;
	}
	function check_sku_exists($sku){
		$this->db->from('blog');
		$this->db->where( array('blog_article_sku_code'=>$sku));
		$numrows = $this->db->get()->num_rows();
		if ($numrows > 0){
			return true;
		}else{
			return false;
		}
	}
	function get_blog_article_sku_code($blog_article_id){
		$sku_code = '';
		$this->db->from('blog');
		$this->db->where( array('blog_article_id'=>$blog_article_id));
		$result = $this->db->get()->result();
		foreach ($result as $r){
			$sku_code = $r->blog_article_sku_code;
		}

		if ($sku_code == ''){
			$sku_code = $this->get_blog_article_sku();
		}

		return $sku_code;
	}

	function save($data){
		$err = '';
		$err2 = '';

		$insert = $this->db->insert('blog', $data);
		$insert_id = $this->db->insert_id();
		if ($insert){

			//blog ARTICLE CATEGORIES
			if ($this->input->post('blog_category_id')){
				if ($this->input->post('blog_category_id') != ''){
					$this->save_blog_article_categories($insert_id);
				}
			}
			//COVER IMAGE
			$q = $this->upload_cover_image($insert_id);
			if ($q['res'] == false){ $err2 = $err2 . '<br />' . $q['dt']; }

		}else{
			$err = 'Could not add blog Article successfully. Please try again.';
		}
		if ($err == ''){
			if ($err2 == ''){
				$arr_return = array('res' => true,'dt' => 'Blog Article saved successfully');
			}else{
				$arr_return = array('res' => true,'dt' => 'Blog Article saved successfully' . $err2);
			}
		}else{
			$arr_return = array('res' => false,'dt' => $err);
		}

		return $arr_return;
	}
	function save_blog_article_categories($blog_article_id){
		$blog_category_id = $this->input->post('blog_category_id');		
		foreach ($blog_category_id as $temp_id){
			$new_data = array(
				'blog_article_id' => $blog_article_id,
				'blog_category_id' => $temp_id
			);
			$insert = $this->db->insert('blog_article_categories', $new_data);
		}				

	}

	function get_blog_article($blog_article_id){
		$this->db->from('blog');
		$this->db->where( array('blog_article_id'=>$blog_article_id));
		return $this->db->get()->result();
	}
	function get_blog_article_categories($blog_article_id){
		$this->db->select('blog_article_categories.*, blog.blog_article_title, blog_categories.blog_category_name');
		$this->db->from('blog_article_categories');
		$this->db->join('blog', 'blog.blog_article_id = blog_article_categories.blog_article_id');
		$this->db->join('blog_categories', 'blog_categories.blog_category_id = blog_article_categories.blog_category_id');
		$this->db->where( array('blog_article_categories.is_deleted'=>0, 'blog_article_categories.blog_article_id'=>$blog_article_id));
		return $this->db->get()->result();
	}
	function update($data,$blog_article_id){
		$err = '';
		$err2 = '';

		$this->db->where(array('blog_article_id'=>$blog_article_id));
		$update = $this->db->update('blog', $data);
		if ($update){

			//blog CATEGORIES
			$blog_category_id = $this->input->post('blog_category_id');
			if ($blog_category_id != ''){
				$this->update_blog_article_categories($blog_article_id,$blog_category_id);
			}

			//COVER IMAGE
			$q = $this->upload_cover_image($blog_article_id);
			if ($q['res'] == false){ $err2 = $err2 . '<br />' . $q['dt']; }

			//THUMBNAIL
			$blog_article = $this->get_blog_article($blog_article_id);
			foreach ($blog_article as $row) {
				if ($row->cover_image != '' && file_exists("./uploads/blog_article_cover_images/" . $row->cover_image) && $row->thumb_image == '') {
					$this->createThumbnail($blog_article_id, $row->cover_image);
				}
			}
		}else{
			$err = 'Could not update blog Article successfully. Please try again.';
		}
		if ($err == ''){
			if ($err2 == ''){
				$arr_return = array('res' => true,'dt' => 'Blog Article updated successfully');
			}else{
				$arr_return = array('res' => true,'dt' => 'Blog Article updated successfully' . $err2);
			}
		}else{
			$arr_return = array('res' => false,'dt' => $err);
		}

		return $arr_return;
	}
	function update_blog_article_categories($blog_article_id,$blog_category_id){
		$blog_article_categories = $this->get_blog_article_categories($blog_article_id);

		foreach ($blog_article_categories as $row){
			$found = false;
			foreach ($blog_category_id as $temp_id){
				if ($row->blog_category_id == $temp_id){
					$found = true;
					break;
				}
			}
			if ($found == false){
			   $this->db->where('blog_article_id', $blog_article_id);
			   $this->db->where('blog_category_id', $row->blog_category_id);			   
			   $this->db->delete('blog_article_categories'); 				
			}
		}

		$blog_article_categories = $this->get_blog_article_categories($blog_article_id);
	
		foreach ($blog_category_id as $temp_id){
			$found = false;
			foreach ($blog_article_categories as $row){
				if ($row->blog_category_id == $temp_id){
					$found = true;
					break;
				}
			}
			if ($found == false){
				$new_data = array(
					'blog_article_id' => $blog_article_id,
					'blog_category_id' => $temp_id
				);
				$this->db->insert('blog_article_categories', $new_data);
			}
		}				

	}
	function delete($blog_article_id){
		$data = array(
			'is_deleted'=> 1
		);			
		$this->db->where( array('blog_article_id'=>$blog_article_id));
		$delupdate = $this->db->update('blog', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'Blog Article deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => 'Error deleting Blog Article');
		}
		return $arr_return;
	}

	function upload_cover_image($blog_article_id){
		if(basename($_FILES['cover_image']['name'])!=''){
			
			$upload_config['upload_path'] = './uploads/blog_article_cover_images/';
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
				$this->db->where(array('blog_article_id'=>$blog_article_id));				
				$this->db->update('blog', array('cover_image' => $det['file_name']));
				$this->createThumbnail($blog_article_id, $det['file_name']);
				$arr_return = array('res' => true,'dt' => 'Blog Article Cover Image uploaded successfully');
			}else{
				$arr_return = array('res' => false,'dt' => $this->upload->display_errors());
			}
		}else{
			$arr_return = array('res' => true,'dt' => 'Blog Article Cover Image uploaded successfully');
		}
		return $arr_return;
	}

	function createThumbnail($blog_article_id, $filename){
      	$source_path = './uploads/blog_article_cover_images/' . $filename;
      	$target_path = './uploads/blog_article_cover_images/thumbs/';
      
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


			$this->db->where(array('blog_article_id'=>$blog_article_id));				
			$this->db->update('blog', array('thumb_image' => $thumb_image_name));
      	}   
      	$this->image_lib->clear();
   }

   function delete_cover_image($blog_article_id) {
		$data = array(
			'cover_image'=> '',
			'thumb_image'=> ''
		);	
		$this->db->where(array('blog_article_id'=>$blog_article_id));		
		$update = $this->db->update('blog', $data);
		
		if ($update){
			$arr_return = array('res' => true,'dt'=>'Cover Image deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => 'Error deleting Cover Image. Please try again.');
		}
		return $arr_return;

   }

}