<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Support extends CI_Controller {
	
	function __construct(){
		parent::__construct();		
		$this->load->library('form_validation');	
		$this->load->model('be/support_model');
	}

	function index(){
		
	}

	function notifications() {
		if($this->session->userdata('bgs_be_active')) {
			// if ($this->auth_model->validate_user_access('notifications_view', $this->session->userdata('system_user_id')) == false){
			// 	redirect('be/auth/access_denied');
			// } else {
				$data['cur'] = 'Help & Support';
				$data['cur_sub'] = 'Notifications';
				$data['cur_cur_sub'] = '';

				//$data['sbr_notifications_add'] = $this->auth_model->validate_user_access('notifications_add', $this->session->userdata('system_user_id'));

				$data['page_title'] = 'Notifications | ';
				$data['main_content'] = 'be/notifications';
				$this->load->view('be/includes/template',$data);
			//}
        } 
		else {
            redirect('be/auth');
		}
	}
	function filter_js_notifications() {
		$data['notifications'] = $this->support_model->get_notifications();
		$this->load->view('be/jsloads/notifications',$data);
	}

	function get_ajax_notifications() {
		$q = $this->support_model->get_ajax_notifications();
        $data['notifications'] = $q['records'];
        $data['num_notifications'] = $q['record_count'];

        $notifications_data = $this->load->view('be/jsloads/ajax_notifications',$data, TRUE);
        $notifications_count = $this->support_model->get_num_unread_notifications();

        $arr_return = array('data' => $notifications_data,'count' => $notifications_count);
        echo json_encode($arr_return);
	}

	function update_read_notification($notification_id) {
		$this->support_model->update_read_notification($notification_id);
	}



}