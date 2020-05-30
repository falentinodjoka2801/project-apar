<?php $this->load->view('components/head'); ?>
<div class='col-12'>
     <div id='qrCode'></div>
     <span class='text-sm mb-0 text-sm text-center' id='qrCodeText' style='margin:15px; font-size: 15pt;'></span>
</div>
<script src="<?=base_url('assets/Shards/qr-code/qrcode.min.js')?>"></script>
<script language='Javascript'>
     new QRCode(document.getElementById('qrCode'), "<?=$dataToPrint?>");
     $('#qrCodeText').text("<?=$dataToPrint?>");

     print();
</script>
