<?php
     class AuthModel extends CI_Model{
          public function __construct(){
               parent::__construct();
               $this->load->database();
          }
          public function addData($tabel, $data){
               $addData  =    $this->db->insert($tabel, $data);

               if($addData){
                    return true;
               }else{
                    return false;
               }
          }
          public function getData($tabel, $options = false, $singleRow = false){
               if($options !== false){
                    if(array_key_exists('where', $options)){
                         $this->db->where($options['where']);
                    }
                    if(array_key_exists('orderBy', $options)){
                         $this->db->order_by($options['orderBy']['column'], $options['orderBy']['value']);
                    }
               }

               $getData  =    $this->db->get($tabel);
               if($singleRow){
                    $getData  =    $getData->row_array();
               }else{
                    $getData  =    $getData->result_array();
               }

               return $getData;
          }
          public function deleteData($tabel, $options = false){
               if($options !== false){
                    $this->db->where($options);
               }

               $deleteData  =    $this->db->delete($tabel);

               if($deleteData){
                    return true;
               }else{
                    return false;
               }
          }
          public function editData($tabel, $dataBaru, $options = false){
               if($options !== false){
                    if(array_key_exists('where', $options)){
                         $this->db->where($options['where']);
                    }
               }
               $editData      =    $this->db->update($tabel, $dataBaru);

               if($editData){
                    return true;
               }else{
                    return false;
               }
          }
          public function isThisDataExist($tabel, $column, $data){
               $this->db->where($column, $data);
               $isThisDataExist    =    $this->db->get($tabel);

               if($isThisDataExist->num_rows() >= 1){
                    return true;
               }else{
                    return false;
               }
          }
          public function prosesAutentikasi($username, $password){
               $prosesAutentikasi  =    $this->db->query('select * from pp_user where (username="'.$username.'" OR email="'.$username.'") AND password="'.md5($password).'"');

               $statusAutentikasi  =    false;
               $message            =    '';

               if($prosesAutentikasi->num_rows() >= 1){
                    $dataUser =    $prosesAutentikasi->row_array();

                    $this->load->library('session');

                    $statusAutentikasi  =    true;

                    $this->session->set_userdata('isLogin', true);
                    $this->session->set_userdata('idUser', $dataUser['id']);
                    $this->session->set_userdata('username', $dataUser['username']);
                    $this->session->set_userdata('level', $dataUser['level']);
               }else{
                    $message  =    ucwords('username dan password tidak sesuai');
               }

               return    ['statusAutentikasi' => $statusAutentikasi, 'message' => $message];
          }
          public function isLogin(){
               $this->load->library('session');

               $idUser   =    $this->session->userdata('idUser');
               $username =    $this->session->userdata('username');
               $level    =    $this->session->userdata('level');

               if($idUser == NULL && $username == NULL && $level == NULL){
                    return false;
               }else{
                    return true;
               }
          }
          public function logout(){
               $this->load->library('session');
               $this->load->helper('cookie');

               $this->session->sess_destroy();

               if(get_cookie('idU') !== null){
                    delete_cookie('idU');
               }
               if(get_cookie('u') !== null){
                    delete_cookie('u');
               }
          }
     }
?>