<?php
class Customers_model extends CI_Model {

	function get_customers_list(){
		$this->db->select("c.*, cg.customer_group_name");
		$this->db->from('customers c');
		$this->db->join('customer_groups cg', 'cg.customer_group_id = c.customer_group_id', 'left outer');
		$this->db->where( array('c.is_deleted'=>0));
		return $this->db->get()->result();
	}

	function generate_loyalty_number($length = 9) {
    	$characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ';
    	$randomString = '';
    	for ($i = 0; $i < $length; $i++) {
       		$randomString .= $characters[rand(0, strlen($characters) - 1)];
    	}
    	return $randomString;
	}
	
	function new_loyalty_number(){
		$loyalty_number = $this->generate_loyalty_number();
		$checktrue = $this->check_loyalty_number_exists($loyalty_number);
		while ($checktrue == true){
			$loyalty_number = $this->generate_loyalty_number();
			$checktrue = $this->check_loyalty_number_exists($loyalty_number);
		}
		return $loyalty_number;
	}
	function check_loyalty_number_exists($loyalty_number){
		$this->db->from('customers');
		$this->db->where( array('loyalty_number'=>$loyalty_number));
		$numrows = $this->db->get()->num_rows();
		if ($numrows > 0){
			return true;
		}else{
			return false;
		}
	}
	function get_loyalty_number($customer_id){
		$loyalty_number = '';
		$this->db->from('customers');
		$this->db->where( array('customer_id'=>$customer_id));
		$result = $this->db->get()->result();
		foreach ($result as $r){
			$loyalty_number = $r->loyalty_number;
		}

		return $loyalty_number;
	}
	function get_edit_new_loyalty_number($customer_id){
		$loyalty_number = '';
		$this->db->from('customers');
		$this->db->where( array('customer_id'=>$customer_id));
		$result = $this->db->get()->result();
		foreach ($result as $r){
			$loyalty_number = $r->loyalty_number;
		}

		if ($loyalty_number == ''){
			$loyalty_number = $this->new_loyalty_number();
		}

		return $loyalty_number;
	}

	function customer_exists(){

		$customer_code = $this->input->post('customer_code');
		$email_address = $this->input->post('email_address');
		$phone_number = $this->input->post('phone_number');

    	$msg = '';
    	$msg2 = '';
    	
    	//EMAIL ADDRESS
    	$this->db->where(array('email_address' => $email_address, 'is_deleted' => 0));
		$query = $this->db->get('customers');

		if ($query->num_rows() > 0){
			$msg = '<i class="icon-cancel-circle2"></i> Duplicate Email Address: The Email Address you entered has already been defined.<br>';
		}else{

			//PHONE NUMBER
	    	$this->db->where(array('phone_number' => $phone_number, 'is_deleted' => 0));
			$query = $this->db->get('customers');

			if ($query->num_rows() > 0){
				$msg = '<i class="icon-cancel-circle2"></i> Duplicate Phone Number: The Phone Number you entered has already been defined.<br>';
			}else{

				//CUSTOMER CODE
				if ($customer_code != '' && $customer_code != null){
			    	$this->db->where(array('customer_code' => $customer_code, 'is_deleted' => 0));
					$query = $this->db->get('customers');

					if ($query->num_rows() > 0){
						$msg = '<i class="icon-cancel-circle2"></i> Duplicate Customer Code: The Customer Code you entered has already been defined.<br>';
					}
				}
			}
		}

		if ($msg == $msg2){
			$arr_return = array('res' => true,'dt' => '');
		}else{
			$arr_return = array('res' => false,'dt' => $msg);
		}

		return $arr_return;

	}

	function save($data){
		$insert = $this->db->insert('customers', $data);
		$insert_id = $this->db->insert_id();
		if ($insert){

			//PROFILE PICTURE
			$this->upload_profile_picture($insert_id);

			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Customer added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Customer successfully. Please try again.');
		}
		return $arr_return;
	}

	function get_customer($customer_id){
		$this->db->from('customers');
		$this->db->where( array('customer_id'=>$customer_id));
		return $this->db->get()->result();
	}
	function customer_update_exists(){
		
		$customer_id = $this->input->post('customer_id');
		$customer_code = $this->input->post('customer_code');
		$email_address = $this->input->post('email_address');
		$phone_number = $this->input->post('phone_number');


    	$msg = '';
    	$msg2 = '';

    	//EMAIL ADDRESS
    	$this->db->where(array('customer_id != ' => $customer_id, 'email_address' => $email_address, 'is_deleted' => 0));
		$query = $this->db->get('customers');

		if ($query->num_rows() > 0){
			$msg = '<i class="icon-cancel-circle2"></i> Duplicate Email Address: The Email Address you entered has already been defined.<br>';
		}else{

			//PHONE NUMBER
	    	$this->db->where(array('customer_id != ' => $customer_id, 'phone_number' => $phone_number, 'is_deleted' => 0));
			$query = $this->db->get('customers');

			if ($query->num_rows() > 0){
				$msg = '<i class="icon-cancel-circle2"></i> Duplicate Phone Number: The Phone Number you entered has already been defined.<br>';
			}else{

				//CUSTOMER CODE
				if ($customer_code != '' && $customer_code != null){
			    	$this->db->where(array('customer_id != ' => $customer_id, 'customer_code' => $customer_code, 'is_deleted' => 0));
					$query = $this->db->get('customers');

					if ($query->num_rows() > 0){
						$msg = '<i class="icon-cancel-circle2"></i> Duplicate Customer Code: The Customer Code you entered has already been defined.<br>';
					}
				}
			}
		}

		if ($msg == $msg2){
			$arr_return = array('res' => true,'dt' => '');
		}else{
			$arr_return = array('res' => false,'dt' => $msg);
		}

		return $arr_return;
	}
	function update($data,$customer_id){
		$this->db->where(array('customer_id'=>$customer_id));
		$update = $this->db->update('customers', $data);
		if ($update){

			//PROFILE PICTURE
			$this->upload_profile_picture($customer_id);

			$customer = $this->get_customer($customer_id);
			foreach ($customer as $row) {
				if ($row->profile_picture != '' && file_exists("./uploads/customer_profile_pictures/" . $row->profile_picture)) {
					$this->createProfilePictureThumbnail($customer_id, $row->profile_picture);
				}
			}
			
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Customer updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Customer successfully. Please try again.');
		}
		return $arr_return;
	}

	function delete($customer_id){
		$data = array(
			'is_deleted'=> 1
		);			
		$this->db->where( array('customer_id'=>$customer_id));
		$delupdate = $this->db->update('customers', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Customer deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Customer');
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
			$this->db->where( array('customer_id'=>$value));
			$res = $this->db->update('customers', $data);
			
			if ($res){}else{
				$msg_err2 = $msg_err2 . 'Error deleting Customer';
			}
		}
		if ($msg_err == $msg_err2){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Bulk Transaction(s) completed successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Bulk Transaction(s) could not be completed successfully');
		}

		return $arr_return;
	}

	//PROFILE PICTURE
	function upload_profile_picture($customer_id){
		if(basename($_FILES['profile_picture']['name'])!=''){
		
			$upload_config['upload_path'] = './uploads/customer_profile_pictures/';
			$upload_config['allowed_types'] = 'gif|jpg|jpeg|png';
			$upload_config['max_size']	= '0';
			$upload_config['max_width']  = '0';
			$upload_config['max_height']  = '0';
			
			$this->load->library('upload');
			$this->upload->initialize($upload_config);
			
			$q = $this->upload->do_upload('profile_picture');
		
			if($q){				
				$det = $this->upload->data();	
				$this->db->where(array('customer_id'=>$customer_id));				
				$this->db->update('customers', array('profile_picture' => $det['file_name']));
				$this->createProfilePictureThumbnail($customer_id, $det['file_name']);
				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Profile picture uploaded successfully');
			}else{
				$arr_return = array('res' => false,'dt' => $this->upload->display_errors());
			}
		}else{
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Profile picture uploaded successfully');
		}
		return $arr_return;
	}

	function createProfilePictureThumbnail($customer_id, $filename){
      	$source_path = './uploads/customer_profile_pictures/' . $filename;
      	$target_path = './uploads/customer_profile_pictures/thumbs/';
      
      	$config_manip = array(
          	'image_library' => 'gd2',
          	'source_image' => $source_path,
          	'new_image' => $target_path,
          	'create_thumb'    => TRUE,
          	'maintain_ratio' => TRUE,
          	'width' => 300,
          	'height' => 300
      	);
   
      	$this->load->library('image_lib', $config_manip);
      	if ($this->image_lib->resize()) {
      		$source_image_name = $this->image_lib->dest_image;
        	$extension = strrchr($source_image_name , '.');
        	$name = substr($source_image_name , 0, -strlen($extension));
        	$thumb_image_name = $name.'_thumb'.$extension;

			$this->db->where(array('customer_id'=>$customer_id));				
			$this->db->update('customers', array('profile_picture_thumb' => $thumb_image_name));

			$this->resize_crop_image(400, 400, "./uploads/customer_profile_pictures/" . $filename, "./uploads/customer_profile_pictures/thumbs/" . $thumb_image_name);
      	}   
      	$this->image_lib->clear();
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
	            $quality = 20;
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

   function delete_profile_picture($customer_id) {
		$data = array(
			'profile_picture'=> '',
			'profile_picture_thumb'=> ''
		);	
		$this->db->where(array('customer_id'=>$customer_id));		
		$update = $this->db->update('customers', $data);
		
		if ($update){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Profile picture deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => 'Error deleting Profile picture. Please try again.');
		}
		return $arr_return;
   }


	//CUSTOMER GROUPS
	function get_customer_groups_list(){
		$this->db->select('customer_groups.*,');
		$this->db->from('customer_groups');
		$this->db->where( array('customer_groups.is_deleted'=>0));
		return $this->db->get()->result();
	}
	function save_customer_group($data){
		$insert = $this->db->insert('customer_groups', $data);
		if ($insert){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Customer Group added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add Customer Group successfully. Please try again.');
		}
		return $arr_return;
	}
	function customer_group_exists($customer_group_name){
		$this->db->where(array('customer_group_name' => $customer_group_name, 'is_deleted' => 0));
		$query = $this->db->get('customer_groups');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}

	}
	function get_customer_group($customer_group_id){
		$this->db->from('customer_groups');
		$this->db->where( array('customer_group_id'=>$customer_group_id));
		return $this->db->get()->result();
	}
	function get_customer_group2($customer_group_id){
		$this->db->from('customer_groups');
		$this->db->where( array('customer_group_id'=>$customer_group_id));
		return $this->db->get()->result_array();
	}
	function customer_group_update_exists($customer_group_id,$customer_group_name){
		$this->db->where(array('customer_group_name' => $customer_group_name, 'is_deleted' => 0, 'customer_group_id !=' => $customer_group_id));
		$query = $this->db->get('customer_groups');
		if ($query->num_rows() > 0){
			return TRUE;
		}else{
			return FALSE;
		}
	}	
	function update_customer_group($data,$customer_group_id){
		$this->db->where(array('customer_group_id'=>$customer_group_id));
		$update = $this->db->update('customer_groups', $data);
		if ($update){
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Customer Group updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update Customer Group successfully. Please try again.');
		}
		return $arr_return;
	}
	function delete_customer_group($customer_group_id){
		$data = array(
			'is_deleted'=> 1
		);				
		$this->db->where( array('customer_group_id'=>$customer_group_id));
		$delupdate = $this->db->update('customer_groups', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Customer Group deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting Customer Group');
		}
		return $arr_return;
	}

}