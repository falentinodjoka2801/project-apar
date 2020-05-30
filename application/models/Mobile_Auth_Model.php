<?php
     class Mobile_Auth_Model extends CI_Model{
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
               if($options !== false && is_array($options)){
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
               if($options !== false && is_array($options)){
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
               $statusAutentikasi  =    false;
               $message            =    '';

               if($username !== null || $password !== null){
                    $username =    trim($username);
                    $password =    trim($password);

                    $prosesAutentikasi  =    $this->db->query('select nama, username, id from pp_user where 
                         (username="'.$username.'" OR email="'.$username.'") AND password="'.md5($password).'"');

                    if($prosesAutentikasi->num_rows() >= 1){
                         $dataUser      =    $prosesAutentikasi->row_array();
     
                         $idUser        =    $dataUser['id'];
                         $randomToken   =    rand(0, 99999);
     
                         //tunggu konfirmasi dari 2Budi
                    }else{
                         $message  =    ucwords('username dan password tidak sesuai');
                    }
               }else{
                    $message  =    'Username atau Password Tidak Boleh Kosong !';
               }

               return    ['statusAutentikasi' => $statusAutentikasi, 'message' => $message];
          }
          public function logout(){
               
          }
     }
?>