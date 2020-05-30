<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {
     public function __construct(){
          parent::__construct();

          $this->load->library('session');
		$this->load->model('AuthModel', 'am');

		$isLogin 	=	$this->am->isLogin();
		if($isLogin === false){
               redirect('Auth');
               exit();
		}
     }
	public function index(){
          $userLevel 	=	$this->session->userdata('level');
		if($userLevel !== 'superadmin'){
               redirect('mistake/not_accessible');
               exit();
          }

		$dataWebsite 	=	[
               'title'        =>   'User | Aplikasi PLN'
          ];

		$this->load->view('user/index', $dataWebsite);
     }
     public function convertToJSON($data){
          header('Content-Type:application/json');

          echo json_encode($data);
     }
     public function listDataUser(){
          $isLogin 	=	$this->am->isLogin();
          if($isLogin){
               $limitData          =    $this->input->get('limitData');
               $orderByField       =    $this->input->get('orderByField');
               $orderByOrientation =    $this->input->get('orderByOrientation');
               
               if($limitData === null || strlen($limitData) <= 0){
                    $limitData     =    10;
               }

               if($orderByField === null){
                    $orderByField  =    'id';
               }
               
               if($orderByOrientation === null){
                    $orderByOrientation  =    'desc';
               }

               // $this->db->limit($limitData); 
               /*limit dikomentari karena di viewnya tidak ada filter limit, jadi tampiilkan semua aja*/
               $this->db->order_by($orderByField, $orderByOrientation);
               $listDataUser      =    $this->db->get('pp_user');

               if($listDataUser->num_rows() >= 1){
                    $listDataUser =    $listDataUser->result_array();
               }else{
                    $listDataUser =    [];
               }

               $this->convertToJSON(['listDataUser' => $listDataUser]);
          }
     }
     public function simpanDataUser(){
          $isLogin 	=	$this->am->isLogin();
          $level    =    $this->session->userdata('level');

          $statusPenyimpanan  =    false;
          $message  =    '';

          if($isLogin && $level === 'superadmin'){
               $idUser   =    $this->input->post('idUser');
               $isEdit   =    (is_null($idUser))? false : true;

               if($isEdit){
                    $idUser   =    trim($idUser);
               }

               $nama     =    trim($this->input->post('nama'));
               $alamat   =    trim($this->input->post('alamat'));
               $nomorTelepon  =    trim($this->input->post('nomorTelepon'));
               $email         =    trim($this->input->post('email'));
               $username      =    trim($this->input->post('username'));
               $level      =    trim($this->input->post('level'));

               $this->load->model('UserModel', 'um');

               $exceptionWhere          =    ($isEdit)? ['id != ' => $idUser] : false;

               $isNomorTeleponExist     =    $this->um->isThisDataExist('pp_user', 'nomorTelepon', $nomorTelepon, $exceptionWhere);

               if($isNomorTeleponExist === false){
                    $isEmailExist       =    $this->um->isThisDataExist('pp_user', 'email', $email, $exceptionWhere);
                    if($isEmailExist === false){
                         $isUsernameExist    =    $this->um->isThisDataExist('pp_user', 'username', $username, $exceptionWhere);
                         if($isUsernameExist === false){
                              $dataUser      =    [
                                   'nama'         =>   $nama,
                                   'alamat'       =>   $alamat,
                                   'nomorTelepon' =>   $nomorTelepon,
                                   'email'        =>   $email,
                                   'username'     =>   $username,
                                   'level'   =>   $level
                              ];

                              if($isEdit){
                                   $simpanDataUser     =    $this->um->editData('pp_user', $dataUser, ['where' => ['id' => $idUser]]);
                                   if($simpanDataUser){
                                        $statusPenyimpanan  =    true;
                                   }
                              }else{
                                   $password           =    trim($this->input->post('password'));
                                   $konfirmasiPassword      =    trim($this->input->post('konfirmasiPassword'));

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
               
               echo json_encode(['statusPenyimpanan' => $statusPenyimpanan, 'message' => $message]);
          }else{
               $message  =    'Yang dapat menambahkan dan mengubah data user hanya level superadmin !';
          }
     }
     public function addUser(){
          $isLogin 	=	$this->am->isLogin();
          if($isLogin){
               $level    =    $this->session->userdata('level');

               if($level === 'superadmin'){
                    $dataWebsite 	=	[
                         'title'        =>   'Add New User | Aplikasi PLN'
                    ];

                    $this->load->view('user/addNewUser', $dataWebsite);
               }else{
                    redirect('Mistake/not_accessible');
               }
          }
     }
     public function hapusUser(){
          $isLogin 	=	$this->am->isLogin();
          if($isLogin){
               $this->load->model('UserModel', 'um');

               $idUser   =    trim($this->input->post('idUser'));
               $hapusUser     =    $this->um->deleteData('pp_user', ['id' => $idUser]);

               $statusDeleteUser   =    false;
               if($hapusUser){
                    $statusDeleteUser  =    true;
               }

               $statusDeleteUser   =    ['statusDeleteUser' => $statusDeleteUser];
               $this->convertToJSON($statusDeleteUser);
          }
     }
     public function getLevelUser(){
          $isLogin       =    $this->am->isLogin();
          $getLevelUser  =    '';

          if($isLogin){
               $idUser   =    $this->session->userdata('idUser');

               $this->load->model('UserModel', 'um');
               $options  =    [
                    'select'  =>   'level',
                    'where'   =>   ['id' => $idUser]
               ];
               $user     =    $this->um->getData('pp_user', $options, true);

               $getLevelUser  =    $user['level'];
          }

          $getLevelUser   =    ['getLevelUser' => $getLevelUser];
          $this->convertToJSON($getLevelUser);
     }
     public function cekPasswordAdministrator(){
          $isLogin       =    $this->am->isLogin();
          $cekPasswordAdministrator  =    false;

          if($isLogin){

               $userLevel     =    $this->input->post('userLevel');
               $password      =    $this->input->post('password');

               $userLevel     =    (is_null($userLevel))? '' : trim($userLevel);
               $password      =    (is_null($password))? '' : trim($password);

               if(strtolower($userLevel) === 'superadmin'){
                    $idUser   =    $this->session->userdata('idUser');

                    $this->db->where('id', $idUser);
                    $this->db->where('password', md5($password));
                    $cekPasswordAdministrator     =    $this->db->get('pp_user');
               }else if(strtolower($userLevel) === 'admin'){
                    $this->db->where('password', md5($password));
                    $this->db->where('level', 'superadmin');
                    $cekPasswordAdministrator     =    $this->db->get('pp_user');
               }else{
                    $cekPasswordAdministrator   =    ['cekPasswordAdministrator' => false];
                    $this->convertToJSON($cekPasswordAdministrator);

                    exit();
               }

               $cekPasswordAdministrator     =    ($cekPasswordAdministrator->num_rows() >= 1)? true : false; 
          }

          $cekPasswordAdministrator   =    ['cekPasswordAdministrator' => $cekPasswordAdministrator];
          $this->convertToJSON($cekPasswordAdministrator);
     }
}
