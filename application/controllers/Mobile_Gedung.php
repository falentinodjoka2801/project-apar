<?php

     defined('BASEPATH') OR exit('No direct script access allowed');

     class Mobile_Gedung extends CI_Controller {
          var $isLogin   =    false;

          public function __construct(){
               parent::__construct();
               $this->load->database();

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
          public function listDataGedung(){
               $isLogin  =    $this->isLogin;
               if($isLogin === true){
                    $limitData          =    $this->input->get('limitData');
                    $orderByField       =    $this->input->get('orderByField');
                    $orderByOrientation =    $this->input->get('orderByOrientation');
                    
                    if($limitData === null || strlen($limitData) <= 0){
                         $limitData     =    10;
                    }

                    if($orderByField === null){
                         $orderByField  =    'kodeGedung';
                    }
                    
                    if($orderByOrientation === null){
                         $orderByOrientation  =    'desc';
                    }

                    $this->db->limit($limitData);
                    $this->db->order_by($orderByField, $orderByOrientation);
                    $listDataGedung      =    $this->db->get('pp_gedung');

                    if($listDataGedung->num_rows() >= 1){
                         $listDataGedung =    $listDataGedung->result_array();
                    }else{
                         $listDataGedung =    [];
                    }
               }else{
                    $listDataGedung     =    [];
               }
               
               $this->convertToJSON(['listDataGedung' => $listDataGedung]);
          }
          public function addDataGedung(){
               $isLogin  =    $this->isLogin;
               if($isLogin === true){
                    $kodeGedung    =    trim($this->input->post('kodeGedung'));
                    $namaGedung    =    trim($this->input->post('namaGedung'));

                    $dataGedung    =    [
                         'kodeGedung'   =>   $kodeGedung,
                         'namaGedung'   =>   $namaGedung
                    ];
                    
                    $this->db->where('kodeGedung', $kodeGedung);
                    $isGedungExist      =    $this->db->get('pp_gedung');

                    $this->db->set($dataGedung);
                    if($isGedungExist->num_rows() >= 1){
                         $this->db->where('kodeGedung', $kodeGedung);
                         $addDataGedung      =    $this->db->update('pp_gedung');
                    }else{
                         $addDataGedung      =    $this->db->insert('pp_gedung');
                    }

                    $addDataGedung =    ['addDataGedung' => $addDataGedung];

                    echo json_encode($addDataGedung);
               }
          }
          public function deleteDataGedung(){
               $isLogin  =    $this->isLogin;
               if($isLogin === true){
                    $kodeGedung    =    trim($this->input->post('kodeGedung'));

                    $this->db->where('kodeGedung', $kodeGedung);
                    $deleteDataGedung   =    $this->db->delete('pp_gedung');

                    $statusDeleteGedung      =    false;
                    if($deleteDataGedung){
                         $statusDeleteGedung =    true;
                    }

                    echo json_encode(['statusDeleteGedung' => $statusDeleteGedung]);
               }
          }
     }