<?php
class Home_sliders_model extends CI_Model {
	
	function get_home_sliders_list(){
		$this->db->from('home_sliders');
		$this->db->where( array('is_deleted'=>0));
		return $this->db->get()->result();
	}
	function save(){
		if(basename($_FILES['slider_image']['name'])!=''){
				
			$upload_config['upload_path'] = './uploads/home_sliders/';
			$upload_config['allowed_types'] = 'gif|jpg|jpeg|png';
			//$upload_config['file_name'] = $imagefilename;
			$upload_config['max_size']	= '0';
			$upload_config['max_width']  = '0';
			$upload_config['max_height']  = '0';
				
			$this->load->library('upload');
			$this->upload->initialize($upload_config);
				
			$q = $this->upload->do_upload('slider_image');
			
			if($q){				
				$det = $this->upload->data();	
				$data = array(
					'home_slider_image' => $det['file_name'],
					'home_slider_title' => $this->input->post('home_slider_title'),
					'home_slider_description' => $this->input->post('home_slider_description'),
					'home_slider_link' => $this->input->post('home_slider_link'),
					'is_active' => $this->input->post('is_active'),
					'sort_key' => $this->input->post('sort_key')
				);
				$insert = $this->db->insert('home_sliders', $data);
				$insert_id = $this->db->insert_id();
				if ($insert){
					$this->createThumbnail($insert_id, $det['file_name']);
					$arr_return = array('res' => true,'dt' => 'Home Slider added successfully.');
				}else{
					$arr_return = array('res' => false,'dt' => 'Could not add Home Slider successfully. Please try again.');
				}
			}else{
				$arr_return = array('res' => false,'dt' => $this->upload->display_errors());
			}
		}else{
			$arr_return = array('res' => false,'dt' => 'Please choose a slider image to upload');
		}    		
		return $arr_return;
	}
	function get_home_slider($home_slider_id){
		$this->db->from('home_sliders');
		$this->db->where( array('home_slider_id'=>$home_slider_id));
		return $this->db->get()->result_array();
	}
	function get_home_slider2($home_slider_id){
		$this->db->from('home_sliders');
		$this->db->where( array('home_slider_id'=>$home_slider_id));
		return $this->db->get()->result();
	}
	function update($home_slider_id){
		if(basename($_FILES['slider_image']['name'])!=''){
				
			$upload_config['upload_path'] = './uploads/home_sliders/';
			$upload_config['allowed_types'] = 'gif|jpg|jpeg|png';
			//$upload_config['file_name'] = $imagefilename;
			$upload_config['max_size']	= '0';
			$upload_config['max_width']  = '0';
			$upload_config['max_height']  = '0';
				
			$this->load->library('upload');
			$this->upload->initialize($upload_config);
				
			$q = $this->upload->do_upload('slider_image');
			
			if($q){				
				$det = $this->upload->data();	
				$data = array(
					'home_slider_image' => $det['file_name'],
					'home_slider_title' => $this->input->post('home_slider_title'),
					'home_slider_description' => $this->input->post('home_slider_description'),
					'home_slider_link' => $this->input->post('home_slider_link'),
					'is_active' => $this->input->post('is_active'),
					'sort_key' => $this->input->post('sort_key')
				);
				$this->db->where( array('home_slider_id'=>$home_slider_id));
				$update = $this->db->update('home_sliders', $data);
				$this->createThumbnail($home_slider_id, $det['file_name']);
				if ($update){
					$arr_return = array('res' => true,'dt' => 'Home Slider updated successfully.');
				}else{
					$arr_return = array('res' => false,'dt' => 'Could not update Home Slider successfully. Please try again.');
				}
			}else{
				$arr_return = array('res' => false,'dt' => $this->upload->display_errors());
			}
		}else{
			$data = array(
				'home_slider_title' => $this->input->post('home_slider_title'),
				'home_slider_description' => $this->input->post('home_slider_description'),
				'home_slider_link' => $this->input->post('home_slider_link'),
				'is_active' => $this->input->post('is_active'),
				'sort_key' => $this->input->post('sort_key')
			);
			$this->db->where( array('home_slider_id'=>$home_slider_id));
			$update = $this->db->update('home_sliders', $data);
			if ($update){
				//THUMBNAIL
				$home_slider = $this->get_home_slider2($home_slider_id);
				foreach ($home_slider as $row) {
					if ($row->home_slider_image != '' && file_exists("./uploads/home_sliders/" . $row->home_slider_image) && $row->home_slider_image_thumb == '') {
						$this->createThumbnail($home_slider_id, $row->home_slider_image);
					}
				}
				$arr_return = array('res' => true,'dt' => 'Home Slider updated successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => 'Could not update Home Slider successfully. Please try again.');
			}
		}    		
		return $arr_return;
	}
	function delete($home_slider_id){
		$data = array(
			'is_deleted'=> 1
		);			
		$this->db->where( array('home_slider_id'=>$home_slider_id));
		$delupdate = $this->db->update('home_sliders', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'Home Slider deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => 'Error deleting Home Slider');
		}
		return $arr_return;
	}

	function upload_cover_image($home_slider_id){
		if(basename($_FILES['cover_image']['name'])!=''){
			//$imagefilename = url_title(basename($_FILES['national_id']['name']),'-',TRUE);
			
			$upload_config['upload_path'] = './uploads/home_slider_cover_images/';
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
				$this->db->where(array('home_slider_id'=>$home_slider_id));				
				$this->db->update('home_sliders', array('cover_image' => $det['file_name']));
				$this->createThumbnail($home_slider_id, $det['file_name']);
				$arr_return = array('res' => true,'dt' => 'Home Slider Cover Image uploaded successfully');
			}else{
				$arr_return = array('res' => false,'dt' => $this->upload->display_errors());
			}
		}else{
			$arr_return = array('res' => true,'dt' => 'Home Slider Cover Image uploaded successfully');
		}
		return $arr_return;
	}

	function createThumbnail($home_slider_id, $filename){
      	$source_path = './uploads/home_sliders/' . $filename;
      	$target_path = './uploads/home_sliders/thumbs/';
      
      	$config_manip = array(
          	'image_library' => 'gd2',
          	'source_image' => $source_path,
          	'new_image' => $target_path,
          	'create_thumb'    => TRUE,
          	'maintain_ratio' => TRUE,
          	'width' => 1920,
          	'height' => 500
      	);
   
      	$this->load->library('image_lib', $config_manip);
      	if ($this->image_lib->resize()) {
      		$source_image_name = $this->image_lib->dest_image;
        	$extension = strrchr($source_image_name , '.');
        	$name = substr($source_image_name , 0, -strlen($extension));
        	$thumb_image_name = $name.'_thumb'.$extension;


			$this->db->where(array('home_slider_id'=>$home_slider_id));				
			$this->db->update('home_sliders', array('home_slider_image_thumb' => $thumb_image_name));
      	}   
      	$this->image_lib->clear();
   }



}