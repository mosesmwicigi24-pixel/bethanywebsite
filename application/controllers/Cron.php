<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cron extends CI_Controller {
	
	function __construct() {
		parent::__construct();
		$this->flexi = new stdClass;
        $this->load->library('flexi_cart');
        $this->load->library('flexi_cart_admin');
        $this->load->library('flexi_cart_lite');
		$this->load->model('account_model');
		$this->load->model('checkout_model');
		$this->load->model('affiliates_model');
		$this->load->model('be/support_model');
	}
	function index(){

		$this->account_model->send_customer_registration_emails();		

		$this->checkout_model->send_customer_receipts();
		$this->checkout_model->send_admin_order_creation_emails();

		$this->checkout_model->send_order_dispatch_emails();

		$this->affiliates_model->send_affiliate_registration_emails();
		$this->affiliates_model->send_admin_registration_emails();

		$this->affiliates_model->send_affiliate_approval_emails();

		$this->support_model->send_notification_emails();
	}
}