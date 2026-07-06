<?php

class About_us_model extends CI_Model {
	
	function get_about_us(){
		$this->db->from('about_us');
		return $this->db->get()->result();
	}
	function about_us_exists(){
		$query = $this->db->get('about_us');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}		
	}
	
	function save($data){
		$found = false;
		$query = $this->db->get('about_us');
		if ($query->num_rows() > 0){
			$found = true;
		}else{
			$found = false;
		}

		if ($found == false){
			$insert = $this->db->insert('about_us', $data);
			if ($insert){

				$this->upload_cover_image();

				$arr_return = array('res' => true,'dt' => 'About Us saved successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => 'Could not successfully save About Us. Please try again.');
			}
		}else{
			$update = $this->db->update('about_us', $data);
			if ($update){

				$this->upload_cover_image();
				
				$arr_return = array('res' => true,'dt' => 'About Us updated successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => 'Could not successfully update About Us. Please try again.');
			}
		}
		return $arr_return;
	}


	//UPLOAD COVER IMAGE
	function upload_cover_image(){
		if(basename($_FILES['cover_image']['name'])!=''){
			//$imagefilename = url_title(basename($_FILES['national_id']['name']),'-',TRUE);
			
			$upload_config['upload_path'] = './uploads/about_us_cover_image/';
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
				$this->db->update('about_us', array('cover_image' => $det['file_name']));
				$this->resize_crop_image(1920, 500, "./uploads/about_us_cover_image/" . $det['file_name'], "./uploads/about_us_cover_image/" . $det['file_name']);
				$arr_return = array('res' => true,'dt' => 'Cover Image set successfully');
			}else{
				$arr_return = array('res' => false,'dt' => $this->upload->display_errors());
			}
		}else{
			$arr_return = array('res' => true,'dt' => 'Cover Image set successfully');
		}
		return $arr_return;
	}

	function resize_crop_image($max_width, $max_height, $source_file, $dst_dir, $quality = 200){
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
	     
	    $image($dst_img, $dst_dir, $quality);
	 
	    if($dst_img)imagedestroy($dst_img);
	    if($src_img)imagedestroy($src_img);
	}

	function delete_cover_image(){
		$data = array(
			'cover_image'=> ''
		);			
		$update = $this->db->update('about_us', $data);
		
		if ($update){
			$arr_return = array('res' => true,'dt'=>'Image deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => 'Error deleting image. Please try again.');
		}
		return $arr_return;

	}


}