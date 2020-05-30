<?php
     class UserModel extends CI_Model{
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
                    if(array_key_exists('select', $options)){
                         $this->db->select($options['select']);
                    }
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
          public function isThisDataExist($tabel, $column, $data, $exceptionWhere = false){
               if($exceptionWhere !== false && is_array($exceptionWhere)){
                    $this->db->where($exceptionWhere);
               }
               $this->db->where($column, $data);
               $isThisDataExist    =    $this->db->get($tabel);

               if($isThisDataExist->num_rows() >= 1){
                    return true;
               }else{
                    return false;
               }
          }
          public function detailUser($idUser = false){
               if($idUser === false){
                    $idUser   =    $this->session->userdata('idUser');
               }

               $detailUser    =    $this->getData('ts_user', ['where' => ['idUser' => $idUser]], true);
               return $detailUser;
          }
     }
?>