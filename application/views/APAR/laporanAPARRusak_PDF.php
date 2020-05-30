<?php
     ini_set('display_errors', 1);

     include   APPPATH.'libraries/FPDF/MultiCell.php';

     $pdf      =    new PDF_MC_Table('L');

     $pdf->AddPage('L', 'Legal');
     $pdf->setPaperSizeWhenPageBreak('Legal');

     $pdf->SetFont('Arial', 'B', 20);
     $pdf->Cell(335, 10, 'Laporan Asset Rusak ('.strtoupper($jenisAPAR).')', 0, 1, 'C');
     $pdf->SetFont('Arial', '', 12);
     $pdf->Cell(335, 10, 'Per Tanggal '.date('D, d M Y'), 0, 1, 'C');
     $pdf->Ln();

     $pdf->SetFont('Arial', 'B', 14);
     $pdf->Cell(15, 15, 'No.', 'LTR', 0, 'C', 0);
     $pdf->Cell(35, 15, 'KKS', 'LTR', 0, 'C', 0);
     $pdf->Cell(65, 15, 'Lokasi', 'LTR', 0, 'C', 0);
     $pdf->Cell(40, 15, 'Keterangan', 'LTR', 0, 'C', 0);
     $pdf->Cell(45, 15, 'Last Update', 'LTR', 0, 'C', 0);
     $pdf->Cell(135, 15, 'Kriteria', 'LTRB', 0, 'C', 0);
     $pdf->Ln();
     $pdf->Cell(15, 10, '', 'LBR', 0, 'C', 0);
     $pdf->Cell(35, 10, '', 'LBR', 0, 'C', 0);
     $pdf->Cell(65, 10, '', 'LBR', 0, 'C', 0);
     $pdf->Cell(40, 10, '', 'LBR', 0, 'C', 0);
     $pdf->Cell(45, 10, '', 'LBR', 0, 'C', 0);

     $pdf->Cell(25, 10, 'Pressure', 'LTRB', 0, 'C', 0);
     $pdf->Cell(20, 10, 'Nozzle', 'LTRB', 0, 'C', 0);
     $pdf->Cell(20, 10, 'Segel', 'LTRB', 0, 'C', 0);
     $pdf->Cell(20, 10, 'Hose', 'LTRB', 0, 'C', 0);
     $pdf->Cell(30, 10, 'Physically', 'LTRB', 0, 'C', 0);
     $pdf->Cell(20, 10, 'Valve', 'LTRB', 0, 'C', 0);
     $pdf->Ln();
     
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

     $pdf->SetFont('Arial', '', 12);
     $pdf->SetWidths([15,35, 65, 40, 45, 25, 20, 20, 20, 30, 20]);
     $pdf->SetAligns(['C', 'L', 'L','L', 'L', 'C', 'C', 'C', 'C', 'C', 'C']);

     $jumlahHoseRusak    =    0;
     $jumlahNozzleRusak  =    0;
     $jumlahPhysicallyRusak   =    0;
     $jumlahPressureRusak     =    0;
     $jumlahSegelRusak   =    0;
     $jumlahValveRusak   =    0;

     if($listAPARRusak->num_rows() >= 1){
          $listAPARRusak =    $listAPARRusak->result_array();

          foreach($listAPARRusak as $index => $APARRusak){

               if($jenisAPAR  === 'par' || $jenisAPAR === 'pab'){
                    $jumlahPressureRusak      +=    (strtolower($APARRusak['cond_pressure']) === 'not ok')? 1 : 0;
                    $jumlahNozzleRusak        +=    (strtolower($APARRusak['cond_nozzle']) === 'not ok')? 1 : 0;
                    $jumlahSegelRusak         +=    (strtolower($APARRusak['cond_segel']) === 'not ok')? 1 : 0;
                    $jumlahHoseRusak          +=    (strtolower($APARRusak['cond_hose']) === 'not ok')? 1 : 0;
                    $jumlahPhysicallyRusak    +=    (strtolower($APARRusak['cond_physically']) === 'not ok')? 1 : 0;
                    $jumlahValveRusak         +=    0;
               }
               if($jenisAPAR === 'plr'){
                    $jumlahPressureRusak      +=    0;
                    $jumlahNozzleRusak        +=    0;
                    $jumlahSegelRusak         +=    0;
                    $jumlahHoseRusak          +=    0;
                    $jumlahPhysicallyRusak    +=    (strtolower($APARRusak['cond_physically']) === 'not ok')? 1 : 0;
                    $jumlahValveRusak         +=    (strtolower($APARRusak['cond_valve']) === 'not ok')? 1 : 0;
               }
               if($jenisAPAR === 'hbx'){
                    $jumlahPressureRusak      +=    0;
                    $jumlahNozzleRusak        +=    (strtolower($APARRusak['cond_nozzle']) === 'not ok')? 1 : 0;
                    $jumlahSegelRusak         +=    0;
                    $jumlahHoseRusak          +=    (strtolower($APARRusak['cond_hose']) === 'not ok')? 1 : 0;
                    $jumlahPhysicallyRusak    +=    (strtolower($APARRusak['cond_physically']) === 'not ok')? 1 : 0;
                    $jumlahValveRusak         +=    (strtolower($APARRusak['cond_valve']) === 'not ok')? 1 : 0;
               }

               if($jenisAPAR === 'all'){
                    $jumlahPressureRusak      +=    (strtolower($APARRusak['cond_pressure']) === 'not ok')? 1 : 0;
                    $jumlahNozzleRusak        +=    (strtolower($APARRusak['cond_nozzle']) === 'not ok')? 1 : 0;
                    $jumlahSegelRusak         +=    (strtolower($APARRusak['cond_segel']) === 'not ok')? 1 : 0;
                    $jumlahHoseRusak          +=    (strtolower($APARRusak['cond_hose']) === 'not ok')? 1 : 0;
                    $jumlahPhysicallyRusak    +=    (strtolower($APARRusak['cond_physically']) === 'not ok')? 1 : 0;
                    $jumlahValveRusak         +=    (strtolower($APARRusak['cond_valve']) === 'not ok')? 1 : 0;;
               }

               $pdf->Row([
                    $index+1, 
                    // 'Jenis APAR '.$APARRusak['jenisAPAR']." \n Nomor Tabung ".$APARRusak['nomorTabung'],
                    $APARRusak['qrCode'], 
                    $APARRusak['namaGedung'].'('.$APARRusak['kodeGedung'].') Lantai '.$APARRusak['lantaiGedung'],
                    (strlen($APARRusak['keterangan']) >= 1)? $APARRusak['keterangan'] : '-',
                    (strlen($APARRusak['lastUpdate']) == '0000-00-00 00:00:00')? 'Belum Pernah Di Update' : $APARRusak['lastUpdate'],
                    (strlen($APARRusak['cond_pressure']) >= 1)? $APARRusak['cond_pressure'] : '-',
                    (strlen($APARRusak['cond_nozzle']) >= 1)? $APARRusak['cond_nozzle'] : '-',
                    (strlen($APARRusak['cond_segel']) >= 1)? $APARRusak['cond_segel'] : '-',
                    (strlen($APARRusak['cond_hose']) >= 1)? $APARRusak['cond_hose'] : '-',
                    (strlen($APARRusak['cond_physically']) >= 1)? $APARRusak['cond_physically'] : '-',
                    (strlen($APARRusak['cond_valve']) >= 1)? $APARRusak['cond_valve'] : '-'
               ]);
          }
     }
     
     $pdf->Ln();

     $pdf->SetFont('Arial', '', 12);

     $pdf->Cell(120, 10, 'Jumlah Pressure Rusak', '', 0, 'L', 0);
     $pdf->Cell(10, 10, ':', '', 0, 'C', 0);
     $pdf->Cell(215, 10, $jumlahPressureRusak, '', 0, 'L', 0);
     $pdf->Ln();

     $pdf->Cell(120, 10, 'Jumlah Nozzle Rusak', '', 0, 'L', 0);
     $pdf->Cell(10, 10, ':', '', 0, 'C', 0);
     $pdf->Cell(215, 10, $jumlahNozzleRusak, '', 0, 'L', 0);
     $pdf->Ln();
     
     $pdf->Cell(120, 10, 'Jumlah Segel Rusak', '', 0, 'L', 0);
     $pdf->Cell(10, 10, ':', '', 0, 'C', 0);
     $pdf->Cell(215, 10, $jumlahSegelRusak, '', 0, 'L', 0);
     $pdf->Ln();

     $pdf->Cell(120, 10, 'Jumlah Hose Rusak', '', 0, 'L', 0);
     $pdf->Cell(10, 10, ':', '', 0, 'C', 0);
     $pdf->Cell(215, 10, $jumlahHoseRusak, '', 0, 'L', 0);
     $pdf->Ln();

     $pdf->Cell(120, 10, 'Jumlah Physically Rusak', '', 0, 'L', 0);
     $pdf->Cell(10, 10, ':', '', 0, 'C', 0);
     $pdf->Cell(215, 10, $jumlahPhysicallyRusak, '', 0, 'L', 0);
     $pdf->Ln();

     $pdf->Cell(120, 10, 'Jumlah Valve Rusak', '', 0, 'L', 0);
     $pdf->Cell(10, 10, ':', '', 0, 'C', 0);
     $pdf->Cell(215, 10, $jumlahValveRusak, '', 0, 'L', 0);
     $pdf->Ln();

     $pdf->Output();
?>