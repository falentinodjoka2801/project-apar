<?php $this->load->view('components/head'); ?>
<?php 
     $level    =    $this->session->userdata('level');
?>
<body class="h-100">
     <div class="container-fluid">
          <div class="row">
               <?php $this->load->view('components/sidebar'); ?>
               <main class="main-content col-lg-10 col-md-9 col-sm-12 p-0 offset-lg-2 offset-md-3">
                    <?php $this->load->view('components/navbar'); ?>
                    <div class="main-content-container container-fluid p-4">
                    
                              <!-- <div class="col-12 text-center">
                                   <h2>Selamat Datang di Aplikasi PLN !</h2>
                                   <h3 class="mb-3 mt-3">
                                        Hello,  <span class='text-success'><?=$this->session->userdata('username')?></span>
                                   </h3>
                                   <img src='<?=base_url('assets/img/'.$level.'.png')?>' 
                                        alt='User <?=$level?> Icon' data-toggle='tooltip' data-placement='right' 
                                        title='<?=ucwords($level.' Icon')?>' />
                                   <p class='mb-1'>Now you can access the application</p>
                                   <p>You sign in as  <span class='badge badge-success'><?=$level?></span></p>
                                   <a href='<?=site_url('Auth/logout')?>'>
                                        <button type="button" class="btn btn-danger btn-pill">Logout</button>
                                   </a>
                              </div> -->
                              <div class="error__content pt-5 pb-0 col">
                                   <h2 class="text-default mb-0" style='line-height:4rem;'>Selamat Datang di Aplikasi PLN !</h2>
                                   <a href="#/user-profile-lite">
                                        <div class="w-auto mb-4 mt-4 cp" style="position: relative;">
                                             <img src='<?=base_url('assets/img/'.$level.'.png')?>' 
                                                  alt='User <?=$level?> Icon' data-toggle='tooltip' data-placement='right' 
                                                  title='<?=ucwords($level.' Icon')?>' />
                                        </div>
                                   </a>
                                   <h3><?=$this->session->userdata('username')?>, your privileges is <span class='badge badge-success'><?=$level?></span></h3>
                                   <p class="mt-3">
                                        Now, you can access this application.
                                   </p>
                              </div>
                    </div>
                    <?php $this->load->view('components/footer'); ?>
               </main>
          </div>
     </div>
</body>
</html>
<script language='Javascript'>
     $('[data-toggle=tooltip]').tooltip();
</script>