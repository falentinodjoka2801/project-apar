<?php $this->load->view('components/head'); ?>
     <body class="h-100">
          <div class="container-fluid">
               <div class="row">
                    <?php $this->load->view('components/sidebar'); ?>
                    <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
                         <?php $this->load->view('components/navbar'); ?>
                         <div class="main-content-container container-fluid px-4 pb-4">
                              <?php 
                                   $dataPageTitle =    [
                                        'pageTitleTitle'    =>   'Laporan APAR',
                                        'pageTitleSubTitle' =>   'Laporan Data APAR'
                                   ];
                                   $this->load->view('components/page-title', $dataPageTitle); 
                              ?>
                              <div id="root">
                              </div>
                         </div>
                         <?php $this->load->view('components/footer'); ?>
                    </main>
               </div>
          </div>
     </body>
</html>

<script src="<?=base_url('assets/Shards/qr-code/qrcode.min.js')?>"></script>

<script type='text/babel' src="<?=base_url('assets/ReactJS_Components/common/basicURL.js')?>"></script>
<script type='text/babel' src="<?=base_url('assets/ReactJS_Components/common/components/LimitData.js')?>"></script>
<script type='text/babel' src="<?=base_url('assets/ReactJS_Components/common/components/TRDataNotFound.js')?>"></script>

<script type='text/babel' src="<?=base_url('assets/ReactJS_Components/APAR/RowAPAR.js')?>"></script>
<script type='text/babel' src="<?=base_url('assets/ReactJS_Components/APAR/LaporanAPAR.js')?>"></script>

<script type='text/babel'>
     ReactDOM.render(<LaporanAPAR />, document.getElementById('root'));
</script>