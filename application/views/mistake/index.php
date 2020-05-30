<!DOCTYPE html>
<html lang="en">
     <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title><?=$title?></title>
          <link href='<?=base_url('assets/img/petir.png')?>' rel='shorcut icon' />
          
          <style type="text/css">
               @font-face{
                    font-family: 'Neue';
                    src  : url(<?=base_url('assets/fonts/ComicNeue-Regular.ttf')?>);
               }
               body{
                    font-family: Neue;
                    background-color: #f9f9f9;
               }
               .mistake-container{
                    position:absolute;
                    left:50%;
                    top:50%;
                    transform: translate(-50%, -50%);
                    width:500px;
                    height: 500px;
                    padding:25px 15px;
                    box-sizing: border-box;
               }
               .mistake-container > *{
                    width: 100%;
               }
               .text-danger{
                    color:#dc3545;
               }
               .text-center{
                    text-align:center;
               }
               img{
                    padding:25px 0 15px 0;
               }
               button{
                    background-color: #28a745;
                    border:none;
                    color:#fff;
                    padding: 10px 50px;
                    cursor: pointer;
                    border-radius:35px;
               }
          </style>
     </head>
     <body>
          <div class='mistake-container text-center col-lg-4 col-md-6 col-xs-12 col-sm-12'>
               <h1 class='text-danger'>Page Not Found</h1>
               <img src='<?=base_url('assets/img/404.png')?>' alt='Page Not Found' class='img-mistake' />
               <p class='text-danger'>
                    Page you requested was not found on this website, maybe you were trying to access an invalid URL.
               </p>
               <br />
               <a href="<?=site_url('apar')?>">
                    <button>Back Home</button>
               </a>
          </div>
     </body>
</html>