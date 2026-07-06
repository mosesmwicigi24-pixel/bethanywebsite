<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index(){
		//$this->load->view('receipt');
		//require (base_url() . "vendor/autoload.php");
		// require_once(APPPATH."third_party/Bitly.php");
		// $bitly = new \Kobas\Bitly\Bitly('ce9a7ab68180ed5a82d731ec716388a21ea5c393');
		// $url = $bitly->shortenUrl('https://bethanygiftshop.com');
		// echo $url;
		echo $_SERVER['REMOTE_ADDR'];
	}
}
