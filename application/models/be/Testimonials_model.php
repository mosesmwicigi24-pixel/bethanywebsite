<?php
class Testimonials_model extends CI_Model {
	
	function get_testimonials_list(){
		$this->db->from('testimonials');
		$this->db->where( array('is_deleted'=>0));
		return $this->db->get()->result();
	}
	function get_num_testimonials(){
		$this->db->from('testimonials');
		$this->db->where( array('is_deleted'=>0));
		return $this->db->count_all_results();
	}

	function save($data){
		$insert = $this->db->insert('testimonials', $data);
		$insert_id = $this->db->insert_id();
		if ($insert){

			$this->upload_testimonial_image($insert_id);

			$arr_return = array('res' => true,'dt' => 'Testimonial added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not add Testimonial successfully. Please try again.');
		}
		return $arr_return;
	}
	function testimonial_exists($testimonial_name){
		$this->db->where('testimonial_name',$testimonial_name);
		$this->db->where('is_deleted',0);
		$query = $this->db->get('testimonials');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}

	}
	function get_testimonial($testimonial_id){
		$this->db->from('testimonials');
		$this->db->where( array('testimonial_id'=>$testimonial_id));
		return $this->db->get()->result_array();
	}
	function get_testimonial2($testimonial_id){
		$this->db->from('testimonials');
		$this->db->where( array('testimonial_id'=>$testimonial_id));
		return $this->db->get()->result();
	}
	function testimonial_update_exists($testimonial_id,$testimonial_name){
		$this->db->where('testimonial_id !=',$testimonial_id);
		$this->db->where('testimonial_name',$testimonial_name);
		$this->db->where('is_deleted',0);

		$q = $this->db->get('testimonials');

		if ($q->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function update($data,$testimonial_id){
		$this->db->where(array('testimonial_id'=>$testimonial_id));
		$update = $this->db->update('testimonials', $data);
		if ($update){

			$this->upload_testimonial_image($testimonial_id);

			//THUMBNAIL
			$testimonial = $this->get_testimonial2($testimonial_id);
			foreach ($testimonial as $row) {
				if ($row->testimonial_image != '' && file_exists("./uploads/testimonial_images/" . $row->testimonial_image) && $row->testimonial_image_thumb == '') {
					$this->createThumbnail($testimonial_id, $row->testimonial_image);
				}
			}

			$arr_return = array('res' => true,'dt' => 'Testimonial updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => 'Could not update Testimonial successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete($testimonial_id){
		$data = array(
			'is_deleted'=> 1
		);			
		$this->db->where( array('testimonial_id'=>$testimonial_id));
		$delupdate = $this->db->update('testimonials', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'Testimonial deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => 'Error deleting Testimonial');
		}
		return $arr_return;
	}

	function upload_testimonial_image($testimonial_id){
		if(basename($_FILES['testimonial_image']['name'])!=''){
			//$imagefilename = url_title(basename($_FILES['national_id']['name']),'-',TRUE);
			
			$upload_config['upload_path'] = './uploads/testimonial_images/';
			$upload_config['allowed_types'] = 'gif|jpg|jpeg|png';
			//$upload_config['file_name'] = $imagefilename;
			$upload_config['max_size']	= '0';
			$upload_config['max_width']  = '0';
			$upload_config['max_height']  = '0';
			
			$this->load->library('upload');
			$this->upload->initialize($upload_config);
			
			$q = $this->upload->do_upload('testimonial_image');
		
			if($q){				
				$det = $this->upload->data();	
				$this->db->where(array('testimonial_id'=>$testimonial_id));				
				$this->db->update('testimonials', array('testimonial_image' => $det['file_name']));
				$this->createThumbnail($testimonial_id, $det['file_name']);
				$arr_return = array('res' => true,'dt' => 'Testimonial Image uploaded successfully');
			}else{
				$arr_return = array('res' => false,'dt' => $this->upload->display_errors());
			}
		}else{
			$arr_return = array('res' => true,'dt' => 'Testimonial Image uploaded successfully');
		}
		return $arr_return;
	}

	function createThumbnail($testimonial_id, $filename){
      	$source_path = './uploads/testimonial_images/' . $filename;
      	$target_path = './uploads/testimonial_images/thumbs/';
      
      	$config_manip = array(
          	'image_library' => 'gd2',
          	'source_image' => $source_path,
          	'new_image' => $target_path,
          	'create_thumb'    => TRUE,
          	'maintain_ratio' => TRUE,
          	'width' => 400,
          	'height' => 400
      	);
   
      	$this->load->library('image_lib', $config_manip);
      	if ($this->image_lib->resize()) {
      		$source_image_name = $this->image_lib->dest_image;
        	$extension = strrchr($source_image_name , '.');
        	$name = substr($source_image_name , 0, -strlen($extension));
        	$thumb_image_name = $name.'_thumb'.$extension;


			$this->db->where(array('testimonial_id'=>$testimonial_id));				
			$this->db->update('testimonials', array('testimonial_image_thumb' => $thumb_image_name));
      	}   
      	$this->image_lib->clear();
   }


}