<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mistake extends CI_Controller {
     public function __construct(){
          parent::__construct();
     }
	public function index(){
		$dataWebsite 	=	[
               'title'        =>   'Page Not Found | Aplikasi Tabungan Sekolah'
          ];

          $this->load->view('mistake/index', $dataWebsite);
     }
     public function not_accessible(){
		$dataWebsite 	=	[
               'title'        =>   'Page Unaccessible | Aplikasi Tabungan Sekolah'
          ];

          $this->load->view('mistake/not_accessible', $dataWebsite);
     }
}
