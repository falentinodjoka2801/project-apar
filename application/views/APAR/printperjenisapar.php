<?php $this->load->view('components/head'); ?>
<style type="text/css">
     .pagination{
              margin: 0 5px;
              text-decoration: none !important;
    width: 25px;
    height: 25px;
    background: #ff8d00;
    display: inline-block;
    border-radius: 50%;
    color: #fff;
    opacity: .4;
     }
     .pagination.active{
          opacity: 1;
     }
     .qrCodeText{
      font-size: 17pt !important;
     }
</style>
     <script type="text/javascript">
          
     function printBtn(){
          window.print();
     }
     </script>
     <?php 
          $this->db->select('count(qrCode) as jumlahData');
          $this->db->where('jenisAPAR', $jenisAPAR);
          $seluruhDataAPAR  =    $this->db->get('pp_laporan_apar')->row();

          $page          =    (!isset($_GET['page']))? 0 : $_GET['page'];
          $banyakData    =    (!isset($_GET['count']))? 20 : $_GET['count'];

          $indexData     =    ($page) * $banyakData;

          $aparPerJenis  =    $this->db->query('select qrCode, id from pp_laporan_apar where jenisAPAR="'.$jenisAPAR.'" limit '.$indexData.', '.$banyakData)->result_array();
     ?>
     <span style='display:none' id='aparPerJenis'><?=json_encode($aparPerJenis)?></span>
     <div class='col-12'>
          <!-- <div class='row'>
               <div class="col-12 pt-3">
                    <button class="btn btn-success" onclick='printBtn()'>Print</button>
               </div>
          </div>
          <hr> -->

          <div class='row' id='listQRCode'>
          </div>
          <hr />
          <div class="row pb-4">
               <div class='col-lg-12 col-md-12 col-sm-12 col-xs-12 text-center'>
                    <?php
                         $jumlahPagination   =    ceil($seluruhDataAPAR->jumlahData / 20);
                         for($i = 0; $i < $jumlahPagination; $i++){
                              ?>
                                   <a href='?page=<?=$i?>' class='pagination <?=($page == $i)? 'active':''?>'><?=$i+1?></a>
                              <?php
                         }
                    ?>
               </div>
          </div>
     </div>
<script src="<?=base_url('assets/Shards/qr-code/qrcode.min.js')?>"></script>
<script language='Javascript'>
     let listAPARPerJenis     =    $('#aparPerJenis').text();
     listAPARPerJenis    =    JSON.parse(`${listAPARPerJenis}`);

     let listQRCode      =    '';
     for(let i = 0; i < listAPARPerJenis.length; i++){
          listQRCode     +=   `<div class='col-xs-6 col-sm-4 col-md-3 col-lg-2'>
                                   <b id='qrCode-${i}'  class='qrCode text-center'></b>
                                   <p class='mb-0 mt-1 qrCodeText text-center' id='qrCodeText-${i}'></p>
                              </div>`;
     }

     $('#listQRCode').html(listQRCode);

     for(let i = 0; i < listAPARPerJenis.length; i++){
          new QRCode(document.getElementById(`qrCode-${i}`), listAPARPerJenis[i]['qrCode']);
          $(`#qrCodeText-${i}`).text(listAPARPerJenis[i]['qrCode']);
     }
</script>

