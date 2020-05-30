<?php

use PHPMailer\PHPMailer\PHPMailer;

defined('BASEPATH') OR exit('No direct script access allowed');

class Mobile_Auth extends CI_Controller {
     var $isLogin   =    false;
     
     public function __construct(){
          parent::__construct();
		$environment	=	'development';

		if($environment === 'production'){

		}else{
			$this->isLogin		=	true;
		}

		$this->load->model('Mobile_Auth_Model', 'am');
     }
	public function convertToJSON($data){
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');

		echo json_encode($data);	
     }
     public function autentikasi(){
          $username      =    $this->input->post('emailOrUsername');
          $password      =    $this->input->post('password');

          $autentikasi   =    $this->am->prosesAutentikasi($username, $password);

          $this->convertToJSON($autentikasi);
     }
     public function recovery_password(){
          $emailOrUsername    =    $this->input->post('emailOrUsername');

          $recoveryPassword   =    false;
          $message            =    '';

          if(!is_null($emailOrUsername)){
               $emailOrUsername    =    trim($emailOrUsername);

               $this->db->select('id, email, nama, username');
               $this->db->where('username', $emailOrUsername);
               $this->db->or_where('email', $emailOrUsername);
               $detailUser    =    $this->db->get('pp_user');

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
                         
                         lingkungan hosting harus sesuai domainnya
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
          }else{
               $message  =    'Email atau Username tidak boleh kosong !';
          }

          echo json_encode(['recoveryPassword' => $recoveryPassword, 'message' => $message]);
     }
     public function logout(){
          $this->am->logout();
     }
}
