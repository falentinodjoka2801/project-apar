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
                                        'pageTitleTitle'    =>   'Add New User',
                                        'pageTitleSubTitle' =>   'Tambah Data User Baru'
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

<script src="<?=base_url('assets/Shards/sweetalert2/sweetalert2.min.js')?>"></script>
<link href="<?=base_url('assets/Shards/sweetalert2/sweetalert2.min.css')?>" rel='stylesheet' />

<script src="<?=base_url('assets/Shards/qr-code/qrcode.min.js')?>"></script>

<script type='text/babel' src="<?=base_url('assets/ReactJS_Components/common/basicURL.js')?>"></script>
<script type='text/babel' src="<?=base_url('assets/ReactJS_Components/User/AddDataUser.js')?>"></script>

<script type='text/babel'>
     ReactDOM.render(<AddDataUser />, document.getElementById('root'));

     $('[data-toggle="tooltip"]').tooltip();
</script>