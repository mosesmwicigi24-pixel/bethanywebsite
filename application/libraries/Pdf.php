<?php
//require_once dirname(__FILE__) . '/tcpdf/tcpdf.php';
require_once(APPPATH."third_party/tcpdf/tcpdf.php");

class Pdf extends TCPDF
{
    function __construct()
    {
        parent::__construct();
    }
}