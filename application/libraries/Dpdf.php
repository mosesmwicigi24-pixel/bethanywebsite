<?php 
if (!defined('BASEPATH')) exit('No direct script access allowed');  
 
require_once (APPPATH.'third_party/dompdf/autoload.inc.php');

use Dompdf\Dompdf;
use Dompdf\Options;

class Dpdf extends Dompdf
{
 public function __construct()
 {
   parent::__construct();
 } 
}

?>
