<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {
	public function __construct(){
		parent::__construct();
		
		$this->load->library('session');
		$this->load->model('AuthModel', 'am');
	}
	public function index(){
		$isLogin 	=	$this->am->isLogin();
		if($isLogin === false){
			redirect('Auth');
			exit();
          }
          
		$dataWebsite 	=	[
			'title' => 'Welcome Page | Aplikasi PLN'
		];
		$this->load->view('welcome', $dataWebsite);
	}
}
