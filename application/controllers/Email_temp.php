<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Email_temp extends CI_Controller {
	function __construct() {
		parent::__construct();

	}	
	function emheader(){
		$this->load->view('email/email-header');		
	}
	function embody(){
		$this->load->view('email/email-body');		
	}
	function emfooter(){
		$this->load->view('email/email-footer');		
	}
	function em(){
		$this->load->view('email/email-header');		
		$this->load->view('email/email-body');		
		$this->load->view('email/email-footer');		
	}
}

