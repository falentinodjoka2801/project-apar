<!DOCTYPE html>
<html lang="en">
<head>
	<title><?=$title?></title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
<!--===============================================================================================-->	
	<link rel="icon" type="image/png" href="<?=base_url('assets/img/petir.png')?>"/>
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?=base_url('assets/Login/vendor/bootstrap/css/bootstrap.min.css')?>">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?=base_url('assets/Login/fonts/font-awesome-4.7.0/css/font-awesome.min.css')?>">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?=base_url('assets/Login/fonts/iconic/css/material-design-iconic-font.min.css')?>">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?=base_url('assets/Login/vendor/animate/animate.css')?>">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="<?=base_url('assets/Login/vendor/css-hamburgers/hamburgers.min.css')?>">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?=base_url('assets/Login/vendor/animsition/css/animsition.min.css')?>">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?=base_url('assets/Login/vendor/select2/select2.min.css')?>">
<!--===============================================================================================-->	
	<link rel="stylesheet" type="text/css" href="<?=base_url('assets/Login/vendor/daterangepicker/daterangepicker.css')?>">
<!--===============================================================================================-->
	<link rel="stylesheet" type="text/css" href="<?=base_url('assets/Login/css/util.css')?>">
	<link rel="stylesheet" type="text/css" href="<?=base_url('assets/Login/css/main.css')?>">
<!--===============================================================================================-->
	<style type='text/css'>
		/* .container-login100{
			background:url('<?=base_url('assets/img/backgroundLogin.jpg')?>');
			background-size: cover;
			background-attachment: fixed;
			background-position: center;
		} */
	</style>
</head>
<body>
	
	<div class="limiter">
		<div class="container-login100">
			<div class="wrap-login100">
				<form class="login100-form validate-form" id='formLogin'>
					<span class="login100-form-title p-b-26">
						Portal Login
					</span>
					<img src="<?=base_url('assets/img/login.svg')?>" alt="Login" 
						style='margin:auto; display:block; width:150px;padding-bottom:30px;' id='imgLogin' />

					<div class="wrap-input100 validate-input" data-validate = "Valid email is: a@b.c">
						<input class="input100" type="text" name="emailOrUsername">
						<span class="focus-input100" data-placeholder="Email or Username"></span>
					</div>

					<div class="wrap-input100 validate-input" data-validate="Enter password">
						<span class="btn-show-pass">
							<i class="zmdi zmdi-eye"></i>
						</span>
						<input class="input100" type="password" name="password">
						<span class="focus-input100" data-placeholder="Password"></span>
					</div>

					<div class="container-login100-form-btn">
						<div class="wrap-login100-form-btn">
							<div class="login100-form-bgbtn"></div>
							<button class="login100-form-btn" type='submit' id='btnSubmit'>
								Login
							</button>
						</div>
					</div>

					<div class="text-center p-t-35">
						<a href="<?=site_url('auth/forgot_password')?>">Lupa Password</a>
					</div>
				</form>
			</div>
		</div>
	</div>
	

	<div id="dropDownSelect1"></div>
	
<!--===============================================================================================-->
	<script src="<?=base_url('assets/Login/vendor/jquery/jquery-3.2.1.min.js')?>"></script>
<!--===============================================================================================-->
	<script src="<?=base_url('assets/Login/vendor/animsition/js/animsition.min.js')?>"></script>
<!--===============================================================================================-->
	<script src="<?=base_url('assets/Login/vendor/bootstrap/js/popper.js')?>"></script>
	<script src="<?=base_url('assets/Login/vendor/bootstrap/js/bootstrap.min.js')?>"></script>
<!--===============================================================================================-->
	<script src="<?=base_url('assets/Login/vendor/select2/select2.min.js')?>"></script>
<!--===============================================================================================-->
	<script src="<?=base_url('assets/Login/vendor/daterangepicker/moment.min.js')?>"></script>
	<script src="<?=base_url('assets/Login/vendor/daterangepicker/daterangepicker.js')?>"></script>
<!--===============================================================================================-->
	<script src="<?=base_url('assets/Login/vendor/countdowntime/countdowntime.js')?>"></script>
<!--===============================================================================================-->
	<script src="<?=base_url('assets/Login/js/main.js')?>"></script>

</body>
</html>

<script src="<?=base_url('assets/Shards/sweetalert2/sweetalert2.min.js')?>"></script>
<link href="<?=base_url('assets/Shards/sweetalert2/sweetalert2.min.css')?>" rel='stylesheet' />

<script language='Javascript'>
	$('#formLogin').on('submit', function(e){
		e.preventDefault();

		let imgLogin 	=	$('#imgLogin');
		let btnSubmit 	=	$('#btnSubmit');
		let dataLogin 	=	$(this).serialize();

		let imgLoginSrc 	=	imgLogin.attr('src');
		imgLogin.attr('src', '<?=base_url('assets/img/loading.gif')?>');
		btnSubmit.prop('disabled', true).text('Processing ..');

		$.ajax({
			url 		:	'<?=base_url('auth/autentikasi')?>',
			type 	:	'POST',
			data 	:	dataLogin,
			success	:	function(responseFromServer){
				let JSONResponse 	=	JSON.parse(responseFromServer);
				if(JSONResponse.statusAutentikasi === true){
					window.location.href 	=	'<?=site_url('Welcome')?>';
				}else{
					Swal.fire({
						title 	:	'Autentikasi',
						text 	:	'Autentikasi Gagal ! Username atau Email dan Password  harus sesuai !',
						type 	:	'error'
					});
				}
				
				imgLogin.attr('src', imgLoginSrc);
				btnSubmit.prop('disabled', false).text('Login');
			}
		})
	});
</script>