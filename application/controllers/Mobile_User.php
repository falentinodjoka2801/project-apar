<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mobile_User extends CI_Controller {
     var $isLogin   =    false;
     public function __construct(){
          parent::__construct();
		$environment	=	'development';

		if($environment === 'production'){

		}else{
			$this->isLogin		=	true;
		}
     }
     public function convertToJSON($data){
          header('Content-Type:application/json');

          echo json_encode($data);
     }
     public function listDataUser(){
          $isLogin 	=	$this->isLogin;
          if($isLogin){
               $limitData          =    $this->input->get('limitData');
               $orderByField       =    $this->input->get('orderByField');
               $orderByOrientation =    $this->input->get('orderByOrientation');
               
               if($limitData === null){
                    $limitData     =    10;
               }

               if($orderByField === null){
                    $orderByField  =    'id';
               }else{
                    $orderByField  =    trim($orderByField);
               }
               
               if($orderByOrientation === null){
                    $orderByOrientation      =    'desc';
               }elsE{
                    $orderByOrientation      =    trim($orderByOrientation);
               }

               $this->db->limit($limitData);
               $this->db->order_by($orderByField, $orderByOrientation);
               $listDataUser      =    $this->db->get('pp_user');

               if($listDataUser->num_rows() >= 1){
                    $listDataUser =    $listDataUser->result_array();
               }else{
                    $listDataUser =    [];
               }
          }else{
               $listDataUser  =    [];
          }
          
          $this->convertToJSON(['listDataUser' => $listDataUser]);
     }
     public function simpanDataUser(){
          $isLogin 	=	$this->isLogin;
          $statusPenyimpanan  =    false;
          $message  =    '';

          if($isLogin){
               $idUser   =    trim($this->input->post('idUser'));
               $isEdit   =    (strlen($idUser))? true : false;

               $nama     =    trim($this->input->post('nama'));
               $alamat   =    trim($this->input->post('alamat'));
               $nomorTelepon  =    trim($this->input->post('nomorTelepon'));
               $email         =    trim($this->input->post('email'));
               $username      =    trim($this->input->post('username'));

               $this->load->model('UserModel', 'um');
               $exceptionWhere          =    ($isEdit)? ['id !=' => $idUser] : false;
               $isNomorTeleponExist     =    $this->um->isThisDataExist('pp_user', 'nomorTelepon', $nomorTelepon, $exceptionWhere);
               if($isNomorTeleponExist === false){
                    $exceptionWhere     =    ($isEdit)? ['id !=' => $idUser] : false;
                    $isEmailExist       =    $this->um->isThisDataExist('pp_user', 'email', $email, $exceptionWhere);
                    if($isEmailExist === false){
                         $exceptionWhere     =    ($isEdit)? ['id !=' => $idUser] : false;
                         $isUsernameExist    =    $this->um->isThisDataExist('pp_user', 'username', $username, $exceptionWhere);
                         if($isUsernameExist === false){
                              $dataUser      =    [
                                   'nama'         =>   $nama,
                                   'alamat'       =>   $alamat,
                                   'nomorTelepon' =>   $nomorTelepon,
                                   'email'        =>   $email,
                                   'username'     =>   $username
                              ];

                              if($isEdit){
                                   $simpanDataUser     =    $this->um->editData('pp_user', $dataUser, ['where' => ['id' => $idUser]]);
                              }else{
                                   $password           =    trim($this->input->post('password'));
                                   $konfirmasiPassword =    trim($this->input->post('konfirmasiPassword'));

                                   if($password === $konfirmasiPassword){
                                        $dataUser['password']    =    md5($password);
                                        $dataUser['level']       =    strtolower(trim($this->input->post('level')));

                                        $simpanDataUser     =    $this->um->addData('pp_user', $dataUser);
                                        
                                        if($simpanDataUser){
                                             $statusPenyimpanan  =    true;
                                        }
                                   }else{
                                        $message  =    'Password dan Konfirmasi Password Harus Sama !';
                                   }
                              }
                         }else{
                              $message  =    'Username sudah dipakai, gunakan username yang lain';
                         }
                    }else{
                         $message  =    'Email sudah dipakai, gunakan email yang lain';
                    }
               }else{
                    $message  =    'Nomor telepon sudah dipakai, gunakan nomor telepon yang lain';
               }  
          }else{
               $message  =    'Session anda hilang, silahkan login kembali !';
          }

          $this->convertToJSON(['statusPenyimpanan' => $statusPenyimpanan, 'message' => $message]);
     }
     public function hapusUser(){
          $isLogin 	=	$this->isLogin;
          if($isLogin){
               $this->load->model('UserModel', 'um');

               $idUser   =    trim($this->input->post('idUser'));
               $hapusUser     =    $this->um->deleteData('pp_user', ['id' => $idUser]);

               $statusDeleteUser   =    false;
               if($hapusUser){
                    $statusDeleteUser  =    true;
               }

               $statusDeleteUser   =    ['statusDeleteUser' => $statusDeleteUser];
          }else{
               $statusDeleteUser   =    ['statusDeleteUser' => false];
          }
          
          $this->convertToJSON($statusDeleteUser);
     }
}
