<?php
     ini_set('display_errors', 1);

     include   APPPATH.'libraries/FPDF/MultiCell.php';
     include   APPPATH.'libraries/phpqrcode/qrlib.php';

     $pdf      =    new PDF_MC_Table('L');

     $pdf->AddPage('L', 'Legal');
     $pdf->setPaperSizeWhenPageBreak('Legal');

     $pdf->SetFont('Arial', 'B', 20);
     $pdf->Cell(335, 10, 'QR Code Asset Jenis ('.strtoupper($jenisAPAR).')', 0, 1, 'C');
     $pdf->Ln();

     $this->db->select('qrCode');
     $this->db->where('jenisAPAR', $jenisAPAR);
     $aparPerJenis  =    $this->db->get('pp_laporan_apar')->result_array();

     $temporaryDirectory =    'assets/qrCode';
     if(!file_exists($temporaryDirectory)){
          mkdir($temporaryDirectory);
     }

     $listQRCodePNG      =    glob($temporaryDirectory.'/*.png');
     if(count($listQRCodePNG) >= 1){
          foreach($listQRCodePNG as $index => $qrCode){
               unlink($qrCode);
          }
     }

     foreach($aparPerJenis as $index => $apar){
          $qrCode   =    $apar['qrCode'];
          QRcode::png($qrCode, $temporaryDirectory.'/'.$qrCode.'.png');
     }

     $listQRCodePNGBaru      =    glob($temporaryDirectory.'/*.png');
     if(count($listQRCodePNGBaru) >= 1){

          $pdf->SetWidths([55.5, 55.5, 55.5, 55.5, 55.5, 55.5]);
          $pdf->SetAligns(['C', 'C', 'C', 'C', 'C', 'C']);

          foreach($listQRCodePNGBaru as $index => $qrCodeBaru){
               $dari     =    $index + 1;
               $sampai   =    6 * $dari;

               $data     =    [];
               for($i = ($dari - 1)*6; $i < $sampai; $i++){
                    array_push($data, $pdf->InlineImage($qrCodeBaru, null, $pdf->GetY(), 55.5, 55.5));
               }

               $pdf->Row($data);
          }
     }

     $pdf->Output();
?>