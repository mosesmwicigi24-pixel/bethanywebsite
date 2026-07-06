<?php

class Affiliates_model extends CI_Model {

	public function __construct(){
        parent::__construct();
        $this->load->model('be/bitly_settings_model');
    }

	function get_affiliates_list(){
		$this->db->select("a.*, c.country_name, ap.affiliate_package_name, ap.affiliate_package_colour_code, ap.commission, ap.minimum_pay_out");
		$this->db->from('affiliates a');
		$this->db->join('countries c', 'c.country_id = a.country_id', 'left outer');
		$this->db->join('affiliate_packages ap', 'ap.affiliate_package_id = a.affiliate_package_id', 'left outer');
		$this->db->where( array('a.is_deleted' => 0));
		return $this->db->get()->result();
	}

	function get_affiliate_referrals($affiliate_id){
        $this->db->select('affiliate_referrals.*');
        $this->db->from('affiliate_referrals');
        $this->db->where( array('affiliate_id' => $affiliate_id));
        return $this->db->get()->result();
	}

	function get_affiliate_clicks($affiliate_id){
        $this->db->select('affiliate_clicks.*');
        $this->db->from('affiliate_clicks');
        $this->db->where( array('affiliate_id' => $affiliate_id));
        return $this->db->get()->result();
	}

    function get_account_total_clicks($affiliate_id){
        $this->db->select('affiliate_clicks.*');
        $this->db->from('affiliate_clicks');
        $this->db->where( array('affiliate_id' => $affiliate_id));
        return $this->db->count_all_results();
    }

    function get_account_total_referrals($affiliate_id){
        $this->db->select('affiliate_referrals.*');
        $this->db->from('affiliate_referrals');
        $this->db->where( array('affiliate_id' => $affiliate_id));
        return $this->db->count_all_results();
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
		$this->db->from('affiliates');
		$this->db->where( array('loyalty_number'=>$loyalty_number));
		$numrows = $this->db->get()->num_rows();
		if ($numrows > 0){
			return true;
		}else{
			return false;
		}
	}
	function get_loyalty_number($affiliate_id){
		$loyalty_number = '';
		$this->db->from('affiliates');
		$this->db->where( array('affiliate_id'=>$affiliate_id));
		$result = $this->db->get()->result();
		foreach ($result as $r){
			$loyalty_number = $r->loyalty_number;
		}

		return $loyalty_number;
	}
	function get_edit_new_loyalty_number($affiliate_id){
		$loyalty_number = '';
		$this->db->from('affiliates');
		$this->db->where( array('affiliate_id'=>$affiliate_id));
		$result = $this->db->get()->result();
		foreach ($result as $r){
			$loyalty_number = $r->loyalty_number;
		}

		if ($loyalty_number == ''){
			$loyalty_number = $this->new_loyalty_number();
		}

		return $loyalty_number;
	}

	function affiliate_exists(){

		$affiliate_code = $this->input->post('affiliate_code');
		$email_address = $this->input->post('email_address');
		$phone_number = $this->input->post('phone_number');

    	$msg = '';
    	$msg2 = '';
    	
    	//EMAIL ADDRESS
    	$this->db->where(array('email_address' => $email_address, 'is_deleted' => 0));
		$query = $this->db->get('affiliates');

		if ($query->num_rows() > 0){
			$msg = '<i class="icon-cancel-circle2"></i> Duplicate Email Address: The Email Address you entered has already been defined.<br>';
		}else{

			//PHONE NUMBER
	    	$this->db->where(array('phone_number' => $phone_number, 'is_deleted' => 0));
			$query = $this->db->get('affiliates');

			if ($query->num_rows() > 0){
				$msg = '<i class="icon-cancel-circle2"></i> Duplicate Phone Number: The Phone Number you entered has already been defined.<br>';
			}else{

				//affiliate CODE
				if ($affiliate_code != '' && $affiliate_code != null){
			    	$this->db->where(array('affiliate_code' => $affiliate_code, 'is_deleted' => 0));
					$query = $this->db->get('affiliates');

					if ($query->num_rows() > 0){
						$msg = '<i class="icon-cancel-circle2"></i> Duplicate affiliate Code: The affiliate Code you entered has already been defined.<br>';
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
		$insert = $this->db->insert('affiliates', $data);
		$insert_id = $this->db->insert_id();
		if ($insert){

			//PROFILE PICTURE
			$this->upload_profile_picture($insert_id);

			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> affiliate added successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not add affiliate successfully. Please try again.');
		}
		return $arr_return;
	}

	function get_affiliate($affiliate_id){
		$this->db->select("a.*, c.country_name, ap.affiliate_package_name, ap.affiliate_package_colour_code, ap.commission, ap.minimum_pay_out");
		$this->db->from('affiliates a');
		$this->db->join('countries c', 'c.country_id = a.country_id', 'left outer');
		$this->db->join('affiliate_packages ap', 'ap.affiliate_package_id = a.affiliate_package_id', 'left outer');
		$this->db->where( array('a.affiliate_id'=>$affiliate_id));
		return $this->db->get()->result();
	}
	function affiliate_update_exists(){
		
		$affiliate_id = $this->input->post('affiliate_id');
		$affiliate_code = $this->input->post('affiliate_code');
		$email_address = $this->input->post('email_address');
		$phone_number = $this->input->post('phone_number');


    	$msg = '';
    	$msg2 = '';

    	//EMAIL ADDRESS
    	$this->db->where(array('affiliate_id != ' => $affiliate_id, 'email_address' => $email_address, 'is_deleted' => 0));
		$query = $this->db->get('affiliates');

		if ($query->num_rows() > 0){
			$msg = '<i class="icon-cancel-circle2"></i> Duplicate Email Address: The Email Address you entered has already been defined.<br>';
		}else{

			//PHONE NUMBER
	    	$this->db->where(array('affiliate_id != ' => $affiliate_id, 'phone_number' => $phone_number, 'is_deleted' => 0));
			$query = $this->db->get('affiliates');

			if ($query->num_rows() > 0){
				$msg = '<i class="icon-cancel-circle2"></i> Duplicate Phone Number: The Phone Number you entered has already been defined.<br>';
			}else{

				//affiliate CODE
				if ($affiliate_code != '' && $affiliate_code != null){
			    	$this->db->where(array('affiliate_id != ' => $affiliate_id, 'affiliate_code' => $affiliate_code, 'is_deleted' => 0));
					$query = $this->db->get('affiliates');

					if ($query->num_rows() > 0){
						$msg = '<i class="icon-cancel-circle2"></i> Duplicate affiliate Code: The affiliate Code you entered has already been defined.<br>';
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
	function update($data,$affiliate_id){
		$this->db->where(array('affiliate_id'=>$affiliate_id));
		$update = $this->db->update('affiliates', $data);
		if ($update){

			//PROFILE PICTURE
			$this->upload_profile_picture($affiliate_id);

			$affiliate = $this->get_affiliate($affiliate_id);
			foreach ($affiliate as $row) {
				if ($row->profile_picture != '' && file_exists("./uploads/affiliate_profile_pictures/" . $row->profile_picture)) {
					$this->createProfilePictureThumbnail($affiliate_id, $row->profile_picture);
				}
			}
			
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> affiliate updated successfully.');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Could not update affiliate successfully. Please try again.');
		}
		return $arr_return;
	}

	function delete($affiliate_id){
		$data = array(
			'is_deleted'=> 1
		);			
		$this->db->where( array('affiliate_id'=>$affiliate_id));
		$delupdate = $this->db->update('affiliates', $data);
		
		if ($delupdate){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> affiliate deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => '<i class="icon-cancel-circle2"></i> Error deleting affiliate');
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
			$this->db->where( array('affiliate_id'=>$value));
			$res = $this->db->update('affiliates', $data);
			
			if ($res){}else{
				$msg_err2 = $msg_err2 . 'Error deleting affiliate';
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
	function upload_profile_picture($affiliate_id){
		if(basename($_FILES['profile_picture']['name'])!=''){
		
			$upload_config['upload_path'] = './uploads/affiliate_profile_pictures/';
			$upload_config['allowed_types'] = 'gif|jpg|jpeg|png';
			$upload_config['max_size']	= '0';
			$upload_config['max_width']  = '0';
			$upload_config['max_height']  = '0';
			
			$this->load->library('upload');
			$this->upload->initialize($upload_config);
			
			$q = $this->upload->do_upload('profile_picture');
		
			if($q){				
				$det = $this->upload->data();	
				$this->db->where(array('affiliate_id'=>$affiliate_id));				
				$this->db->update('affiliates', array('profile_picture' => $det['file_name']));
				$this->createProfilePictureThumbnail($affiliate_id, $det['file_name']);
				$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Profile picture uploaded successfully');
			}else{
				$arr_return = array('res' => false,'dt' => $this->upload->display_errors());
			}
		}else{
			$arr_return = array('res' => true,'dt' => '<i class="icon-checkmark4"></i> Profile picture uploaded successfully');
		}
		return $arr_return;
	}

	function createProfilePictureThumbnail($affiliate_id, $filename){
      	$source_path = './uploads/affiliate_profile_pictures/' . $filename;
      	$target_path = './uploads/affiliate_profile_pictures/thumbs/';
      
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

			$this->db->where(array('affiliate_id'=>$affiliate_id));				
			$this->db->update('affiliates', array('profile_picture_thumb' => $thumb_image_name));

			$this->resize_crop_image(400, 400, "./uploads/affiliate_profile_pictures/" . $filename, "./uploads/affiliate_profile_pictures/thumbs/" . $thumb_image_name);
      	}   
      	$this->image_lib->clear();function get_affiliate_by_code($affiliate_code) {
        $this->db->select("a.*, c.country_name, ap.affiliate_package_name, ap.affiliate_package_colour_code, ap.commission, ap.minimum_pay_out, ap.affiliate_package_features");
        $this->db->from('affiliates a');
        $this->db->join('countries c', 'c.country_id = a.country_id', 'left outer');
        $this->db->join('affiliate_packages ap', 'ap.affiliate_package_id = a.affiliate_package_id', 'left outer');
        $this->db->where( array('a.affiliate_code'=>$affiliate_code));
        return $this->db->get()->result();
    }
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

   function delete_profile_picture($affiliate_id) {
		$data = array(
			'profile_picture'=> '',
			'profile_picture_thumb'=> ''
		);	
		$this->db->where(array('affiliate_id'=>$affiliate_id));		
		$update = $this->db->update('affiliates', $data);
		
		if ($update){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Profile picture deleted successfully');
		}else{
			$arr_return = array('res' => false,'dt' => 'Error deleting Profile picture. Please try again.');
		}
		return $arr_return;
   }

   function approve_account() {

		if ($this->bitly_settings_model->bitly_settings_exists() == true) {

			$bitly_settings = $this->bitly_settings_model->get_bitly_settings();
			foreach ($bitly_settings as $row) {
				$generic_access_token = $row->generic_access_token;
			}

			$affiliate = $this->get_affiliate($this->input->post('affiliate_id'));
			foreach ($affiliate as $row) {
				$affiliate_code = $row->affiliate_code;
			}

			try {

				require_once(APPPATH."third_party/Bitly.php");
				$bitly = new \Kobas\Bitly\Bitly($generic_access_token);
				$long_url = base_url() . 'affiliates/refer/' . $affiliate_code;
				//$long_url = 'https://bethanygiftshop.com/affiliates/refer/' . $affiliate_code;
				$short_url = $bitly->shortenUrl($long_url);
				//echo $url;
				
				$data = array(
					'affiliate_status'=> 1,
					'affiliate_package_id'=> $this->input->post('affiliate_package_id'),
					'short_url'=> $short_url,
					'approved_by'=> $this->session->userdata('system_user_id'),
					'approval_date'=> date('Y-m-d')
				);	
				$this->db->where(array('affiliate_id'=>$this->input->post('affiliate_id')));		
				$update = $this->db->update('affiliates', $data);
				
				if ($update){
					$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Affiliate Account approved successfully');
				}else{
					$arr_return = array('res' => false,'dt' => 'Error approving account. Please try again.');
				}
			} catch (Exception $e) {
				$arr_return = array('res' => false,'dt' => $e->getMessage());
			}
		} else {

		}

		return $arr_return;
   }

   function assign_package() {
		$data = array(
			'affiliate_package_id'=> $this->input->post('affiliate_package_id')
		);	
		$this->db->where(array('affiliate_id'=>$this->input->post('affiliate_id')));		
		$update = $this->db->update('affiliates', $data);
		
		if ($update){
			$arr_return = array('res' => true,'dt'=>'<i class="icon-checkmark4"></i> Affiliate package assigned successfully');
		}else{
			$arr_return = array('res' => false,'dt' => 'Error assigning package. Please try again.');
		}
		return $arr_return;

   }
	

	//AFFILIATE TERMS
	function get_affiliate_terms(){
		$this->db->from('affiliate_terms');
		return $this->db->get()->result();
	}
	function affiliate_terms_exists(){
		$query = $this->db->get('affiliate_terms');
		if ($query->num_rows() > 0){
			return true;
		}else{
			return false;
		}		
	}
	
	function save_affiliate_terms($data){
		$found = false;
		$query = $this->db->get('affiliate_terms');
		if ($query->num_rows() > 0){
			$found = true;
		}else{
			$found = false;
		}

		if ($found == false){
			$insert = $this->db->insert('affiliate_terms', $data);
			if ($insert){

				$arr_return = array('res' => true,'dt' => 'Affiliate Terms and Conditions saved successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => 'Could not successfully save Affiliate Terms and Conditions. Please try again.');
			}
		}else{
			$update = $this->db->update('affiliate_terms', $data);
			if ($update){

				$arr_return = array('res' => true,'dt' => 'TAffiliate erms and Conditions updated successfully.');
			}else{
				$arr_return = array('res' => false,'dt' => 'Could not successfully update Affiliate Terms and Conditions. Please try again.');
			}
		}
		return $arr_return;
	}



}