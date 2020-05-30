<?php
     class ExportDataModel extends CI_Model{
          public function __construct(){
               parent::__construct();
               $this->load->database();
          }
          public function exportToExcel($title = false, $header = false, $kolom = [], $dataArray = [], $fileName){
               include   APPPATH.'libraries\ExportData.php';
               
               $excel    =    new ExportDataExcel('browser', $fileName);

               $excel->initialize();
               
               if($title !== false && is_array($title)){
                    $excel->addRow($title);
                    $excel->addRow([]);
               }

               if($header !== false && is_array($header)){
                    foreach($header as $head){
                         $excel->addRow($head);
                    }
                    $excel->addRow([]);
               }

               $excel->addRow($kolom);

               if(count($dataArray) >= 1){
                    foreach($dataArray as $indexData => $data){
                         $excel->addRow($data);
                    }
               }

               $excel->finalize();
          }
          // public function exportToExcel_PHPExcel(){
          //      include   APPPATH.'libraries\PHPExcel-1.8\Classes\PHPExcel.php';
               
          //      $excel    =    new PHPExcel();
          //      $excel->getProperties()->setCreator('Tes Export Data')
          //           ->setLastModifiedBy('Falentino Djoka')
          //           ->setTitle('Data Mahasiswa')
          //           ->setSubject('Mahasiswa')
          //           ->setDescription('Laporan Semua Data Mahasiswa')
          //           ->setKeywords('Data Mahasiswa');

          //      $styleCol      =    [
          //           'font'         =>   ['bold' => true],
          //           'alignment'    =>   [
          //                'horizontal'   =>   PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
          //                'vertical'     =>   PHPExcel_Style_Alignment::VERTICAL_CENTER
          //           ],
          //           'borders'      =>   [
          //                'top'     =>   ['style' => PHPExcel_Style_Border::BORDER_THIN],
          //                'right'   =>   ['style' => PHPExcel_Style_Border::BORDER_THIN],
          //                'bottom'  =>   ['style' => PHPExcel_Style_Border::BORDER_THIN],
          //                'left'    =>   ['style' => PHPExcel_Style_Border::BORDER_THIN]
          //           ]
          //      ];

          //      $styleRow      =    [
          //           'alignment'    =>   [
          //                'vertical'     =>   PHPExcel_Style_Alignment::VERTICAL_CENTER
          //           ],
          //           'borders'      =>   [
          //                'top'     =>   ['style' => PHPExcel_Style_Border::BORDER_THIN],
          //                'right'   =>   ['style' => PHPExcel_Style_Border::BORDER_THIN],
          //                'bottom'  =>   ['style' => PHPExcel_Style_Border::BORDER_THIN],
          //                'left'    =>   ['style' => PHPExcel_Style_Border::BORDER_THIN]
          //           ]
          //      ];

          //      $excel->setActiveSheetIndex(0)->setCellValue('A1', 'Data Mahasiswa');
          //      $excel->getActiveSheet()->mergeCells('A1:FI');
          //      $excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
          //      $excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(15);
          //      $excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
          
          //      header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
          //      header('Content-Disposition: attachment; filename="Data Siswa.xlsx"'); // Set nama file excel nya
          //      header('Cache-Control: max-age=0');
          //      $write = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
          //      $write->save('php://output');
          // }
     }
?>