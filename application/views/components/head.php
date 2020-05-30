<html class="no-js h-100" lang="en">
     <head>
          <meta charset="utf-8">
          <meta http-equiv="x-ua-compatible" content="ie=edge">
          <title><?=$title?></title>

         <!--  <meta name="description" content="A high-quality &amp; free Bootstrap admin dashboard template pack that comes with lots of templates and components."> -->
          <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
          
          <meta http-equiv='cache-control' content='no-cache'>
          <meta http-equiv='expires' content='0'>
          <meta http-equiv='pragma' content='no-cache'>

          <?php if(strtolower($_SERVER['SERVER_NAME']) === 'localhost'){ ?>
               <script type="text/javascript" src='<?=base_url('assets/Shards/reactjs/react.development.js')?>'></script>
               <script type="text/javascript" src='<?=base_url('assets/Shards/reactjs/react.dom.development.js')?>'></script>
          <?php }else{ ?>
               <script src="https://unpkg.com/react@16/umd/react.production.min.js"></script>
               <script src="https://unpkg.com/react-dom@16/umd/react-dom.production.min.js"></script>
          <?php } ?>
          <script type="text/javascript" src='<?=base_url('assets/Shards/reactjs/babel.js')?>'></script>
          <script type="text/javascript" src='<?=base_url('assets/Shards/reactjs/axios.js')?>'></script>

          <script async defer src="<?=base_url('assets/Shards/buttons.js')?>"></script>

          <script src="<?=base_url('assets/Shards/jquery/jquery.js')?>"></script>
          <script src="<?=base_url('assets/Shards/popper/popper.min.js')?>"></script>
          <script src="<?=base_url('assets/Shards/bootstrap/js/bootstrap.min.js')?>"></script>
          <script src="<?=base_url('assets/Shards/bootstrap/js/bootstrap.bundle.min.js')?>"></script>
          <script src="<?=base_url('assets/Shards/scripts/shards.min.js')?>"></script>
          <script src="<?=base_url('assets/Shards/scripts/extras.1.1.0.min.js')?>"></script>
          <!-- <script src="<?=base_url('assets/Shards/chart.js/Chart.min.js')?>"></script> -->

          <script src="<?=base_url('assets/Shards/scripts/shards-dashboards.1.1.0.min.js')?>"></script>
          <script src="<?=base_url('assets/Shards/scripts/jquery.sharrre.min.js')?>"></script>
          <script src="<?=base_url('assets/Shards/scripts/app/app-blog-overview.1.1.0.js')?>"></script>

          <link href="<?=base_url('assets/Shards/font-awesome/all.css')?>" rel="stylesheet">
          <link rel="stylesheet" href="<?=base_url('assets/Shards/bootstrap/css/bootstrap.min.css')?>" />

          <link rel="stylesheet" id="main-stylesheet" data-version="1.1.0" 
               href="<?=base_url('assets/Shards/styles/shards-dashboards.1.1.0.min.css')?>">
          <link rel="stylesheet" href="<?=base_url('assets/Shards/styles/extras.1.1.0.min.css')?>">

          <link rel="shotcut icon" href="<?=base_url('assets/img/petir.png')?>">
          <style type='text/css'>
               @font-face {
                    font-family: 'Material Icons';
                    font-style: normal;
                    font-weight: 400;
                    src: url(<?=base_url('assets/Shards/flUhRq6tzZclQEJ-Vdg-IuiaDsNc.woff2')?>) format('woff2');
               }

               .material-icons {
                    font-family: 'Material Icons';
                    font-weight: normal;
                    font-style: normal;
                    font-size: 24px;
                    line-height: 1;
                    letter-spacing: normal;
                    text-transform: none;
                    display: inline-block;
                    white-space: nowrap;
                    word-wrap: normal;
                    direction: ltr;
                    -webkit-font-feature-settings: 'liga';
                    -webkit-font-smoothing: antialiased;
               }
               .no-border-top{
                    border-top: 0 !important;
               }
               .text-sm{
                    font-size: 80%;
               }
               .text-bold{
                    font-weight:700;
               }
               .cp{
                    cursor: pointer;
               }
               .icon-option{
                    font-size: 12.5pt;
               }
               .vam{
                    vertical-align: middle !important;
               }
               .swal2-container{
                    z-index:1070 !important;
               }
               #qrCode > img{
                    display:unset !important;
                    margin:15px;
               }
               .qrCode > img{
                    display:unset !important;
                    margin:15px 0 0 0;
                    width: 100%;
               }
          </style>
     </head>