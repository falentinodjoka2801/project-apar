<?php

     defined('BASEPATH') OR exit('No direct script access allowed');

     class Gedung extends CI_Controller {
          public function __construct(){
               parent::__construct();
               $this->load->database();
               $this->load->library('session');
               $this->load->model('AuthModel', 'am');
          }
          public function convertToJSON($data){
               header('Content-Type:application/json');

               echo json_encode($data);
          }
          public function index(){
               $isLogin  =    $this->am->isLogin();
               if($isLogin !== true){
                    redirect('Auth');
                    exit();
               }

               $dataWebsite 	=	[
                    'title' => 'Data Gedung | Aplikasi PLN'
               ];
               $this->load->view('gedung/index', $dataWebsite);
          }
          public function listDataGedung(){
               $isLogin  =    $this->am->isLogin();
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

                    $this->convertToJSON(['listDataGedung' => $listDataGedung]);
               }
          }
          public function addDataGedung(){
               $isLogin  =    $this->am->isLogin();
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
          public function addGedung(){
               $isLogin  =    $this->am->isLogin();
               if($isLogin !== true){
                    redirect('Auth');
                    exit();
               }

               $dataWebsite 	=	[
                    'title' => 'Add Data Gedung | Aplikasi PLN'
               ];
               $this->load->view('gedung/addDataGedung', $dataWebsite);
          }
          public function deleteDataGedung(){
               $isLogin  =    $this->am->isLogin();
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