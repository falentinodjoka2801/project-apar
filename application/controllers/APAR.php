<?php

     defined('BASEPATH') OR exit('No direct script access allowed');

     class APAR extends CI_Controller {
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
          public function listAPAR(){        
               $isLogin  =    $this->am->isLogin();
               if($isLogin){
                    $where              =    $this->input->get('where');
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

                    $this->db->limit($limitData);

                    if($where !== null && strlen($where) >= 1){
                         $whereArray    =    json_decode($where, true);
                         if(is_array($whereArray)){
                              $this->db->where($whereArray);
                         }
                    }

                    $this->db->order_by($orderByField, $orderByOrientation);
                    $listAPAR      =    $this->db->get('view_laporan_apar');

                    if($listAPAR->num_rows() >= 1){
                         $listAPAR =    $listAPAR->result_array();
                    }else{
                         $listAPAR =    [];
                    }

                    $this->convertToJSON(['listAPAR' => $listAPAR]);
               }
          }
          public function listAPARRusak(){        
               $isLogin  =    $this->am->isLogin();
               if($isLogin){
                    $jenisAPAR     =    trim($this->input->get('jenisAPAR'));
                    $jenisAPAR     =    strtolower($jenisAPAR);

                    if($jenisAPAR === 'par' || $jenisAPAR === 'pab'){
                         $listAPARRusak =    $this->db->query('select * from view_laporan_apar where jenisAPAR="'.strtoupper($jenisAPAR).'" AND 
                              (cond_pressure="NOT OK" OR cond_nozzle="NOT OK" OR cond_segel="NOT OK" OR cond_hose="NOT OK" 
                              OR cond_physically="NOT OK")');
                    }else if($jenisAPAR === 'plr'){
                         $listAPARRusak =    $this->db->query('select * from view_laporan_apar where jenisAPAR="'.strtoupper($jenisAPAR).'" AND 
                              (cond_physically="NOT OK" OR cond_valve="NOT OK")');
                    }else if($jenisAPAR === 'hbx'){
                         $listAPARRusak =    $this->db->query('select * from view_laporan_apar where jenisAPAR="'.strtoupper($jenisAPAR).'" AND 
                              (cond_nozzle="NOT OK" OR cond_hose="NOT OK" OR cond_valve="NOT OK"  OR cond_physically="NOT OK")');
                    }else{
                         $listAPARRusak =    $this->db->query('select * from view_laporan_apar where cond_pressure="NOT OK" OR cond_nozzle="NOT OK" OR cond_segel="NOT OK" OR cond_hose="NOT OK" OR cond_valve="NOT OK"  OR cond_physically="NOT OK"');
                    }

                    if($listAPARRusak->num_rows() >= 1){
                         $listAPARRusak =    $listAPARRusak->result_array();
                    }else{
                         $listAPARRusak =    [];
                    }

                    $this->convertToJSON(['listAPARRusak' => $listAPARRusak]);
               }
          }
          
          //tidak ada di api
          public function index(){
               $isLogin  =    $this->am->isLogin();
               if($isLogin !== true){
                    redirect('auth');
                    exit();
               }

               $dataWebsite 	=	[
                    'title' => 'Home | HAFIZA'
               ];
               $this->load->view('index', $dataWebsite);
          }
          
          //tidak ada di api
          public function addDataAPAR(){
               $isLogin  =    $this->am->isLogin();
               if($isLogin !== true){
                    redirect('auth');
                    exit();
               }

               $dataWebsite 	=	[
                    'title' => 'Add Data Asset | HAFIZA'
               ];
               $this->load->view('APAR/addDataAPAR', $dataWebsite);
          }
          
          public function addAPAR(){
               $isLogin  =    $this->am->isLogin();
               if($isLogin === true){
                    $kodeGedung    =    $this->input->post('kodeGedung');
                    $lantaiGedung  =    $this->input->post('lantaiGedung');
                    $nomorTabung   =    $this->input->post('nomorTabung');
                    $jenisAPAR     =    $this->input->post('jenisAPAR');
                    $kriteria      =    $this->input->post('kriteria');
                    $keterangan    =    $this->input->post('keterangan');

                    $statusAdd     =    false;
                    $message       =    '';

                    $lantai2Digit  =    str_pad((int) abs($lantaiGedung), 2, '0', STR_PAD_LEFT);
                    $nomorTabung3Digit  =    str_pad((int) abs($nomorTabung), 3, '0', STR_PAD_LEFT);

                    $this->db->select('id');
                    $this->db->where('nomorTabung', $nomorTabung3Digit);
                    $this->db->where('lantaiGedung', $lantai2Digit);
                    $this->db->where('kodeGedung', $kodeGedung);
                    $nomorAPARSudahAdaDiGedungSekianLantaiSekian      =    $this->db->get('view_laporan_apar');

                    if($nomorAPARSudahAdaDiGedungSekianLantaiSekian->num_rows() <= 0){
                         if(strtolower($jenisAPAR)  === 'par' || strtolower($jenisAPAR) === 'pab'){
                              $pressure      =    $kriteria['pressure'];
                              $nozzle        =    $kriteria['nozzle'];
                              $segel         =    $kriteria['segel'];
                              $hose          =    $kriteria['hose'];
                              $physically    =    $kriteria['physically'];
                              $valve         =    '';
                         }else if(strtolower($jenisAPAR) === 'plr'){
                              $pressure      =    '';
                              $nozzle        =    '';
                              $segel         =    '';
                              $hose          =    '';
                              $physically    =    $kriteria['physically'];
                              $valve         =    $kriteria['valve'];
                         }else if(strtolower($jenisAPAR) === 'hbx'){
                              $pressure      =    '';
                              $nozzle        =    $kriteria['nozzle'];
                              $segel         =    '';
                              $hose          =    $kriteria['hose'];
                              $physically    =    $kriteria['physically'];
                              $valve         =    $kriteria['valve'];
                         }else{
                              $pressure      =    '';
                              $nozzle        =    '';
                              $segel         =    '';
                              $hose          =    '';
                              $physically    =    '';
                              $valve         =    '';
                         }

                         $qrCode   =    $lantai2Digit.$kodeGedung.$nomorTabung3Digit.$jenisAPAR;

                         $dataAPAR      =    [
                              'kodeGedung'        =>   $kodeGedung,
                              'lantaiGedung'      =>   $lantai2Digit,
                              'nomorTabung'       =>   $nomorTabung3Digit,
                              'jenisAPAR'         =>   $jenisAPAR, 
                              'qrCode'            =>   $qrCode,
                              'cond_pressure'     =>   $pressure,
                              'cond_nozzle'       =>   $nozzle,
                              'cond_segel'        =>   $segel,
                              'cond_hose'         =>   $hose,
                              'cond_physically'   =>   $physically,
                              'cond_valve'        =>   $valve,
                              'keterangan'        =>   $keterangan
                         ];

                         $addAPAR       =    $this->db->insert('pp_laporan_apar', $dataAPAR);
                         if($addAPAR){
                              $statusAdd     =    true;
                              $message       =    $qrCode;
                         }else{
                              $message  =    'Tidak dapat menyimpan ke database !';
                         }
                    }else{
                         $this->db->select('namaGedung');
                         $this->db->where('kodeGedung', $kodeGedung);
                         $detailGedung  =    $this->db->get('pp_gedung')->row();

                         $message  =    ucwords('Asset dengan nomor '.$nomorTabung.' di gedung '.$detailGedung->namaGedung.' lantai '.$lantaiGedung.' sudah ada !');
                    }
                    echo json_encode(['addAPAR' => $statusAdd, 'message' => $message]);
               }
          }
          
          //tidak ada di api
          public function print($whatKindOfPrint, $dataToPrint){
               $whatKindOfPrint    =    strtolower($whatKindOfPrint);

               if($whatKindOfPrint === 'qrcode'){
                    $dataWebsite 	=	[
                         'title'        =>   'Print | HAFIZA',
                         'dataToPrint'  =>   $dataToPrint
                    ];
                    $this->load->view('APAR/print', $dataWebsite);
               }
               if($whatKindOfPrint === 'qrcodeperjenisapar'){
                    $dataWebsite 	=	[
                         'title'        =>   'Print Per Jenis Asset | HAFIZA',
                         'jenisAPAR'    =>   $dataToPrint
                    ];
                    $this->load->view('APAR/printperjenisapar', $dataWebsite);
               }
          }

          //tidak ada di api
          public function laporan(){
               $isLogin  =    $this->am->isLogin();
               if($isLogin !== true){
                    redirect('auth');
                    exit();
               }

               $dataWebsite 	=	[
                    'title' => 'Laporan Data Asset | HAFIZA'
               ];
               $this->load->view('APAR/laporan', $dataWebsite);
          }

          //tidak ada di api
          public function exportData($exportTo, $jenisLaporan, $jenisAPAR = 'all'){
               $exportTo      =    strtolower($exportTo);
               $jenisLaporan  =    strtolower($jenisLaporan);

               if($exportTo === 'excel'){
                    
               }else if($exportTo === 'pdf'){
                    if($jenisLaporan === 'laporanaparrusak'){
                         $options  =    [
                              'jenisAPAR'    =>   $jenisAPAR
                         ];
                         $this->load->view('APAR/laporanAPARRusak_PDF', $options);
                    }else{
                         redirect('mistake');
                    }
               }else{
                    redirect('mistake');
               }
          }

          public function ubahNilaiKriteria(){
               $isLogin  =    $this->am->isLogin();
               $statusUbahNilaiKriteria =    false;

               if($isLogin === true){
                    $idDataAPAR    =    trim($this->input->get('idAPAR'));
                    $kolom         =    trim($this->input->get('kriteriaKey'));

                    $this->db->select($kolom);
                    $this->db->where('id', $idDataAPAR);
                    $detailDataAPAR     =    $this->db->get('view_laporan_apar')->row();

                    $nilaiKolom    =    strtolower($detailDataAPAR->$kolom);

                    $nilaiKolomBaru     =    ($nilaiKolom === 'ok')? 'Not OK' : 'OK';
                    $this->db->where('id', $idDataAPAR);
                    $ubahNilaiKriteria  =    $this->db->update('pp_laporan_apar', [
                         $kolom         =>   $nilaiKolomBaru,
                         'lastUpdate'   =>   date('Y-m-d H:i:s')
                    ]);

                    if($ubahNilaiKriteria){
                         $statusUbahNilaiKriteria =    true;
                    }
               }

               $this->convertToJSON(['statusUbahNilaiKriteria' => $statusUbahNilaiKriteria]);
          }
          public function deleteAPAR(){
               $isLogin  =    $this->am->isLogin();
               $statusDeleteAPAR   =    false;

               if($isLogin === true){
                    $idAPAR   =    $this->input->post('idAPAR');

                    if($idAPAR !== null){
                         $this->db->where('id', $idAPAR);
                         $deleteAPAR    =    $this->db->delete('pp_laporan_apar');

                         if($deleteAPAR){
                              $statusDeleteAPAR   =    true;
                         }
                    }
               }

               $statusDeleteAPAR   =    ['statusDeleteAPAR' => $statusDeleteAPAR];
               $this->convertToJSON($statusDeleteAPAR);
          }
          public function editDataAPAR(){
               $isLogin  =    $this->am->isLogin();

               $statusEditAPAR   =    false;
               $message  =    '';

               if($isLogin === true){
                    $idAPAR        =    trim($this->input->post('idAPAR'));

                    $jenisAPAR     =    trim($this->input->post('jenisAPAR'));
                    $kodeGedung    =    trim($this->input->post('kodeGedung'));
                    $lantaiGedung  =    trim($this->input->post('lantaiGedung'));
                    $nomorTabung   =    trim($this->input->post('nomorTabung'));
                    $keterangan    =    trim($this->input->post('keterangan'));

                    $lantai2Digit  =    str_pad((int) $lantaiGedung, 2, '0', STR_PAD_LEFT);
                    $nomorTabung3Digit  =    str_pad((int) $nomorTabung, 3, '0', STR_PAD_LEFT);

                    $this->db->where('id !=', $idAPAR);
                    $this->db->where('nomorTabung', $nomorTabung3Digit);
                    $this->db->where('lantaiGedung', $lantai2Digit);
                    $this->db->where('kodeGedung', $kodeGedung);
                    $nomorAPARSudahAdaDiGedungSekianLantaiSekian      =    $this->db->get('view_laporan_apar');
                    
                    if($nomorAPARSudahAdaDiGedungSekianLantaiSekian->num_rows() <= 0){
                         $qrCode   =    $lantai2Digit.$kodeGedung.$nomorTabung3Digit.$jenisAPAR;

                         if(strtolower($jenisAPAR)  === 'par' || strtolower($jenisAPAR) === 'pab'){
                              $pressure      =    'OK';
                              $nozzle        =    'OK';
                              $segel         =    'OK';
                              $hose          =    'OK';
                              $physically    =    'OK';
                              $valve         =    '';
                         }else if(strtolower($jenisAPAR) === 'plr'){
                              $pressure      =    '';
                              $nozzle        =    '';
                              $segel         =    '';
                              $hose          =    '';
                              $physically    =    'OK';
                              $valve         =    'OK';
                         }else if(strtolower($jenisAPAR) === 'hbx'){
                              $pressure      =    '';
                              $nozzle        =    'OK';
                              $segel         =    '';
                              $hose          =    'OK';
                              $physically    =    'OK';
                              $valve         =    'OK';
                         }else{
                              $pressure      =    '';
                              $nozzle        =    '';
                              $segel         =    '';
                              $hose          =    '';
                              $physically    =    '';
                              $valve         =    '';
                         }

                         $dataAPAR      =    [
                              'jenisAPAR'    =>   $jenisAPAR,
                              'kodeGedung'   =>   $kodeGedung,
                              'lantaiGedung' =>   $lantai2Digit,
                              'nomorTabung'  =>   $nomorTabung3Digit,
                              'keterangan'   =>   $keterangan,
                              'qrCode'       =>   $qrCode,
                              'cond_pressure'     =>   $pressure,
                              'cond_nozzle'       =>   $nozzle,
                              'cond_segel'        =>   $segel,
                              'cond_hose'         =>   $hose,
                              'cond_physically'   =>   $physically,
                              'cond_valve'        =>   $valve,
                              'lastUpdate'   =>   date('Y-m-d H:i:s')
                         ];
                         #here

                         $this->db->where('id', $idAPAR);
                         $editDataAPAR  =    $this->db->update('pp_laporan_apar', $dataAPAR);

                         if($editDataAPAR){
                              $statusEditAPAR     =    true;
                         }else{
                              $message  =    'Tidak dapat menyimpan perubahan data';
                         }
                    }else{
                         $this->db->select('namaGedung');
                         $this->db->where('kodeGedung', $kodeGedung);
                         $detailGedung  =    $this->db->get('pp_gedung')->row();

                         $message  =    ucwords('Asset dengan nomor '.$nomorTabung3Digit.' di gedung '.$detailGedung->namaGedung.' lantai '.$lantai2Digit.' sudah ada !');
                    }
               }else{
                    $message  =    'Session anda hilang, silahkan login kembali';
               }

               $statusEditAPAR   =    ['statusEditAPAR' => $statusEditAPAR, 'message' => $message];
               $this->convertToJSON($statusEditAPAR);
          }
     }