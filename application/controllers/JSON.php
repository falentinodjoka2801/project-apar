<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/* 
     kontroller khusus untuk APAR, untuk kontroller lain menggunakan nama yang sama 
     dengan controller sebelumnya, yang diawali dengan kata Mobile
*/

class JSON extends CI_Controller {
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
		header('Content-Type: application/json');
		header('Access-Control-Allow-Origin: *');

		echo json_encode($data);	
     }
     
     //pengecekan null ok
	public function APAR_listAPAR($singleRow = false){
		if($this->isLogin){

               if(is_string($singleRow)){
                    $singleRow  = strtolower($singleRow);

                    if($singleRow === 'true'){
                         $singleRow  = true;
                    }else{
                         $singleRow  = false;
                    }
               }

			$where              =    $this->input->get('where');
               $limitData          =    $this->input->get('limitData');
               $orderByField       =    $this->input->get('orderByField');
               $orderByOrientation =    $this->input->get('orderByOrientation');
            
               if($limitData === null || $limitData <= 0){
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
               $dataAPAR      =    $this->db->get('view_laporan_apar');

               if($dataAPAR->num_rows() >= 1){
               if($singleRow){
                    $dataAPAR   = $dataAPAR->row_array();
               }else{
                    $dataAPAR =    $dataAPAR->result_array();
                    }
               }else{
                    $dataAPAR =    [];
               }

               $this->convertToJSON(['dataAPAR' => $dataAPAR]);
          }
     }
     
     //pengecekan null ok
	public function APAR_listAPARRusak(){
		if($this->isLogin){
               $jenisAPAR     =    $this->input->get('jenisAPAR');

               if($jenisAPAR !== null){
                    $jenisAPAR     =    strtolower(trim($jenisAPAR));

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
                         $listAPARRusak =    $this->db->query('select * from view_laporan_apar where cond_pressure="NOT OK" OR cond_nozzle="NOT OK" OR 
                              cond_segel="NOT OK" OR cond_hose="NOT OK" OR cond_valve="NOT OK"  OR cond_physically="NOT OK"');
                    }

                    if($listAPARRusak->num_rows() >= 1){
                         $listAPARRusak =    $listAPARRusak->result_array();
                    }else{
                         $listAPARRusak =    [];
                    }
               }

	          $this->convertToJSON(['jenisAPAR' => $listAPARRusak]);
	    }
     }
     
     //pengecekan null ok
	public function APAR_addAPAR(){
          $statusAdd     =    false;
          $message       =    '';

		if($this->isLogin){
			$kodeGedung    =    $this->input->post('kodeGedung');
               $lantaiGedung  =    $this->input->post('lantaiGedung');
               $nomorTabung   =    $this->input->post('nomorTabung');
               $jenisAPAR     =    $this->input->post('jenisAPAR');
               $kriteria      =    $this->input->post('kriteria');
               $keterangan    =    $this->input->post('keterangan');

               if($kodeGedung !== null && $lantaiGedung !== null && $nomorTabung !== null && $jenisAPAR !== null
                    && $kriteria !== null && $keterangan !== null){
                         
                    $kodeGedung    =    trim($kodeGedung);
                    $lantaiGedung  =    trim($lantaiGedung);
                    $nomorTabung   =    trim($nomorTabung);
                    $jenisAPAR     =    trim($jenisAPAR);
                    $kriteria      =    trim($kriteria);
                    $keterangan    =    trim($keterangan);

                    $lantai2Digit  =    str_pad((int) $lantaiGedung, 2, '0', STR_PAD_LEFT);
                    $nomorTabung3Digit  =    str_pad((int) $nomorTabung, 3, '0', STR_PAD_LEFT);

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
               }else{
                    $message  =    'Salah satu nilai yang diperlukan kosong !';
               }
          }else{
               $message  =    'Session anda hilang, silahkan login kembali !';
          }
          
          $this->convertToJSON(['addAPAR' => $statusAdd, 'message' => $message]);
     }

     //pengecekan null ok
     public function APAR_ubahNilaiKriteria(){
          $isLogin  =    $this->isLogin;
          $statusUbahNilaiKriteria =    false;

          if($isLogin === true){
               $idDataAPAR    =    $this->input->post('idAPAR');
               $kolom         =    $this->input->post('kriteriaKey');

               if($idDataAPAR !== null || $kolom !== null){

                    $kolom         =    trim($kolom);
                    $idDataAPAR    =    trim($idDataAPAR);

                    $this->db->select($kolom);
                    $this->db->where('id', $idDataAPAR);
                    $detailDataAPAR     =    $this->db->get('view_laporan_apar')->row();

                    $nilaiKolom    =    strtolower($detailDataAPAR->$kolom);

                    $nilaiKolomBaru     =    ($nilaiKolom === 'ok')? 'Not OK' : 'OK';
                    $this->db->where('id', $idDataAPAR);
                    $ubahNilaiKriteria  =    $this->db->update('pp_laporan_apar', [
                         $kolom => $nilaiKolomBaru,
                         'lastUpdate'   =>   date('Y-m-d H:i:s')
                    ]);

                    if($ubahNilaiKriteria){
                         $statusUbahNilaiKriteria =    true;
                    }
               }
          }

          $this->convertToJSON(['statusUbahNilaiKriteria' => $statusUbahNilaiKriteria]);
     }

     //pengecekan null ok
     public function APAR_deleteAPAR(){
          $isLogin  =    $this->isLogin;
          $statusDeleteAPAR   =    false;

          if($isLogin){
               $idAPAR   =    $this->input->post('idAPAR');

               if($idAPAR !== null){
                    $idAPAR   =    trim($idAPAR);
                    if(strlen($idAPAR) >= 1){
                         $this->db->where('id', $idAPAR);
                         $deleteAPAR    =    $this->db->delete('pp_laporan_apar');

                         if($deleteAPAR){
                              $statusDeleteAPAR   =    true;
                         }
                    }
               }
          }

          $statusDeleteAPAR   =    ['statusDeleteAPAR' => $statusDeleteAPAR];
          $this->convertToJSON($statusDeleteAPAR);
     }

     
     public function APAR_editDataAPAR(){
          $isLogin  =    $this->isLogin;
          $statusEditAPAR   =    false;
          $message  =    '';

          if($isLogin){
               $idAPAR        =    $this->input->post('idAPAR');

               $jenisAPAR     =    $this->input->post('jenisAPAR');
               $kodeGedung    =    $this->input->post('kodeGedung');
               $lantaiGedung  =    $this->input->post('lantaiGedung');
               $nomorTabung   =    $this->input->post('nomorTabung');
               $keterangan    =    $this->input->post('keterangan');

               if(!is_null($idAPAR) && !is_null($jenisAPAR) && !is_null($kodeGedung) 
                    && !is_null($lantaiGedung) && !is_null($nomorTabung) && !is_null($keterangan)){

                    $idAPAR        =    trim($idAPAR);
     
                    $jenisAPAR     =    trim($jenisAPAR);
                    $kodeGedung    =    trim($kodeGedung);
                    $lantaiGedung  =    trim($lantaiGedung);
                    $nomorTabung   =    trim($nomorTabung);
                    $keterangan    =    trim($keterangan);

                    $lantai2Digit  =    str_pad((int) $lantaiGedung, 2, '0', STR_PAD_LEFT);
                    $nomorTabung3Digit  =    str_pad((int) $nomorTabung, 3, '0', STR_PAD_LEFT);

                    $this->db->select('id');
                    $this->db->where('id !=', $idAPAR);
                    $this->db->where('nomorTabung', $nomorTabung3Digit);
                    $this->db->where('lantaiGedung', $lantai2Digit);
                    $this->db->where('kodeGedung', $kodeGedung);
                    $nomorAPARSudahAdaDiGedungSekianLantaiSekian      =    $this->db->get('view_laporan_apar');
                    
                    if($nomorAPARSudahAdaDiGedungSekianLantaiSekian->num_rows() <= 0){
                         $qrCode   =    $lantai2Digit.$kodeGedung.$nomorTabung3Digit.$jenisAPAR;

                         $dataAPAR      =    [
                              'jenisAPAR'    =>   $jenisAPAR,
                              'kodeGedung'   =>   $kodeGedung,
                              'lantaiGedung' =>   $lantai2Digit,
                              'nomorTabung'  =>   $nomorTabung3Digit,
                              'keterangan'   =>   $keterangan,
                              'qrCode'  =>   $qrCode
                         ];

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
                    $message  =    'Data yang dikirim tidak lengkap !';
               }
          }else{
               $message  =    'Session anda hilang, silahkan login kembali !';
          }

          $statusEditAPAR   =    ['statusEditAPAR' => $statusEditAPAR, 'message' => $message];
          $this->convertToJSON($statusEditAPAR);
     }
}
