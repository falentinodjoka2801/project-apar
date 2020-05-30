<?php

use PHPMailer\PHPMailer\PHPMailer;

defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {
     public function __construct(){
          parent::__construct();

          $this->load->library('session');
		$this->load->model('AuthModel', 'am');
     }
	public function index(){
		$isLogin 	=	$this->am->isLogin();
		if($isLogin){
			redirect('APAR');
			exit();
          }
          
		$dataWebsite 	=	[
               'title'        =>   'Login Page | Aplikasi PLN'
          ];

		$this->load->view('login/index', $dataWebsite);
     }
	public function login(){
          $this->index();
     }
	public function logout(){
          $this->am->logout();

          redirect('auth');
     }
     public function autentikasi(){
          $username      =    trim($this->input->post('emailOrUsername'));
          $password      =    trim($this->input->post('password'));

          $autentikasi   =    $this->am->prosesAutentikasi($username, $password);

          echo json_encode($autentikasi);
     }
     public function forgot_password(){
          $isLogin 	=	$this->am->isLogin();
		if($isLogin){
			redirect('APAR');
			exit();
          }
          
		$dataWebsite 	=	[
               'title'        =>   'Lupa Password | Aplikasi PLN'
          ];

		$this->load->view('login/lupa_password', $dataWebsite);
     }
     public function recovery_password(){
          $emailOrUsername    =    $this->input->post('emailOrUsername');

          $this->db->select('id, email, nama, username');
          $this->db->where('username', $emailOrUsername);
          $this->db->or_where('email', $emailOrUsername);
          $detailUser    =    $this->db->get('pp_user');

          $recoveryPassword   =    false;
          $message            =    '';

          if($detailUser->num_rows() >= 1){
               $detailUser    =    $detailUser->row();

               $email         =    $detailUser->email;

               $passwordBaru  =    rand(00000, 99999);

               $this->db->where('id', $detailUser->id);
               $updatePassword     =    $this->db->update('pp_user', ['password' => md5($passwordBaru)]);

               if($updatePassword){
                    include   APPPATH.'libraries/PHPMailer/src/Exception.php';
                    include   APPPATH.'libraries/PHPMailer/src/PHPMailer.php';
                    include   APPPATH.'libraries/PHPMailer/src/SMTP.php';

                    $phpMailer     =    new PHPMailer();


                    /*
                         
                    $phpMailer->Host         =    'falentinodjoka.com';
                    $phpMailer->SMTPAuth     =    true;
                    $phpMailer->Username     =    'sekolahalfarabimedan@falentinodjoka.com';
                    $phpMailer->Password     =    'sekolahalfarabimedan_09042020';
                    
                    */

                    $phpMailer->isSMTP();
                    $phpMailer->Host         =    'falentinodjoka.com';
                    // $phpMailer->Host         =    'smtp.gmail.com';
                    $phpMailer->SMTPAuth     =    true;
                    $phpMailer->Username     =    'sekolahalfarabimedan@falentinodjoka.com';
                    // $phpMailer->Username     =    'sekolahalfarabimedan@gmail.com';
                    $phpMailer->Password     =    'sekolahalfarabimedan_09042020';
                    $phpMailer->SMTPSecure   =    'tls';
                    $phpMailer->Port         =    587;
                    $phpMailer->setFrom('sekolahalfarabimedan@gmail.com', 'Admin Sekolah Al - Farabi Medan');
                    $phpMailer->addAddress($email, $detailUser->nama);
                    $phpMailer->addReplyTo('sekolahalfarabimedan@gmail.com', 'Admin Sekolah Al - Farabi Medan');
                    $phpMailer->isHTML(true);

                    $phpMailer->Subject      =    'Password Baru '.$detailUser->nama.' ('.$detailUser->username.')';
                    $phpMailer->Body         =    'Password anda telah direset menjadi '.$passwordBaru.'.';

                    if($phpMailer->send()){
                         $recoveryPassword   =    true;
                         $message  =    'Silahkan cek email anda, password baru anda sudah diubah. Password baru anda dikirim melalui email ('.$email.')';
                    }else{
                         $message  =    'Tidak dapat mengirim email ! Terdapat kesalahan dalam setting email !';
                    }
               }else{
                    $message  =    'Tidak Dapat Mengupdate Password di Database !';
               }
          }else{
               $message  =    'Username atau password tidak terdaftar !';
          }

          echo json_encode(['recoveryPassword' => $recoveryPassword, 'message' => $message]);
     }
}
