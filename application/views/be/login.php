
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Login - Bethany House Back End</title>

	<?php
        function auto_version($file){
            if(!file_exists($file)) return $file;
            $mtime = filemtime($file);
            return preg_replace('{\\.([^./]+)$}', ".\$1?$mtime", $file);
        }
    ?>

	<link rel="icon" href="<?php echo base_url();?>assets/be/images/favicon.png">

	<!-- Global stylesheets -->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css" integrity="sha384-UHRtZLI+pbxtHCWp1t77Bi1L4ZtiqrqD80Kn4Z8NTSRyMA2Fd33n5dQ8lWUE00s/" crossorigin="anonymous">
	<link href="https://fonts.googleapis.com/css?family=Roboto:400,300,100,500,700,900" rel="stylesheet" type="text/css">
	<link href="https://fonts.googleapis.com/css?family=Lato:300,300i,400,400i,700,700i" rel="stylesheet">
	<link href="<?php echo base_url();?>assets/be/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/be/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/be/css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">
	
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() . auto_version('assets/be/css/layout.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() . auto_version('assets/be/css/components.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() . auto_version('assets/be/css/colors.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() . auto_version('assets/be/css/custom.css'); ?>">
	<!-- /global stylesheets -->

	<script type="text/javascript">
    	var baseDir = '<?php echo base_url(); ?>';
   	</script>

	<!-- Core JS files -->
	<script src="<?php echo base_url();?>assets/be/js/main/jquery.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/main/bootstrap.bundle.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/loaders/blockui.min.js"></script>
	<!-- /core JS files -->

	<!-- Theme JS files -->
	<script src="<?php echo base_url();?>assets/be/js/plugins/tables/datatables/datatables.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/forms/selects/select2.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/forms/styling/uniform.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/forms/styling/switchery.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/forms/styling/switch.min.js"></script>

	<script src="<?php echo base_url();?>assets/be/js/plugins/visualization/d3/d3.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/visualization/d3/d3_tooltip.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/forms/styling/switchery.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/forms/selects/bootstrap_multiselect.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/ui/moment/moment.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/pickers/daterangepicker.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/jquery-validation/jquery.validate.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/tables/datatables/extensions/jszip/jszip.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/tables/datatables/extensions/buttons.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/jquery-loading-overlay/src/loadingoverlay.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/notifications/sweet_alert.min.js"></script>

	<script src="<?php echo base_url();?>assets/be/js/plugins/extensions/jquery_ui/interactions.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/extensions/jquery_ui/widgets.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/extensions/jquery_ui/effects.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/extensions/mousewheel.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/extensions/jquery_ui/globalize/globalize.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/extensions/jquery_ui/globalize/cultures/globalize.culture.de-DE.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/extensions/jquery_ui/globalize/cultures/globalize.culture.ja-JP.js"></script>

	<script src="<?php echo base_url();?>assets/be/js/plugins/pickers/anytime.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/pickers/pickadate/picker.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/pickers/pickadate/picker.date.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/pickers/pickadate/picker.time.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/pickers/pickadate/legacy.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/notifications/jgrowl.min.js"></script>

	<script src="<?php echo base_url();?>assets/be/js/app.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/demo_pages/form_layouts.js"></script>	
	<script src="<?php echo base_url();?>assets/be/js/demo_pages/form_inputs.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/demo_pages/datatables_basic.js"></script>
	<!-- <script src="<?php echo base_url();?>assets/be/js/demo_pages/extra_sweetalert.js"></script> -->
	<!-- <script src="<?php echo base_url();?>assets/be/js/demo_pages/jqueryui_forms.js"></script> -->
	<script src="<?php echo base_url() . auto_version('assets/be/js/custom.js'); ?>"></script>
	<!-- /theme JS files -->

</head>

<body>

	<!-- Main navbar -->
	<div class="navbar navbar-expand-md navbar-light">
		<div class="navbar-brand">
			<a href="#" class="d-inline-block">
				<img src="<?php echo base_url();?>assets/be/images/logo-2.png" alt="">
			</a>
		</div>
	</div>
	<!-- /main navbar -->


	<!-- Page content -->
	<div class="page-content">

		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Content area -->
			<div class="content d-flex justify-content-center align-items-center">

				<!-- Login form -->
				<form id="frm_login" name="frm_login" method="post" class="login-form" onsubmit="return validate_login();">
					<div class="card mb-0">
						<div class="card-body">
							<div class="text-center mb-3">
								<i class="icon-reading text-slate-300 border-slate-300 border-3 rounded-round p-3 mb-3 mt-1"></i>
								<h5 class="mb-0">Login to your account</h5>
								<span class="d-block text-muted">Enter your credentials below</span>
							</div>

							<div id="div_login_error" class="alert alert-danger display-none font-13"></div>
                   			<div id="div_login_success" class="alert alert-success display-none font-13"></div>

							<div class="form-group form-group-feedback form-group-feedback-left">
								<input type="email" class="form-control" placeholder="Email Address" id="login_email_address" name="login_email_address">
								<div class="form-control-feedback">
									<i class="icon-user text-muted"></i>
								</div>
							</div>

							<div class="form-group form-group-feedback form-group-feedback-left">
								<input id="login_password" name="login_password" type="password" class="form-control" placeholder="Password">
								<div class="form-control-feedback">
									<i class="icon-lock2 text-muted"></i>
								</div>
							</div>

							<div class="form-group">
								<button id="btn_login" type="submit" class="btn btn-sm btn-primary btn-block">Sign In <i class="icon-circle-right2 ml-2"></i></button>
							</div>

							<div class="text-center">
								<a href="<?php echo base_url();?>be/auth/recover">Forgot password?</a>
							</div>
						</div>
					</div>
				</form>
				<!-- /login form -->

			</div>
			<!-- /content area -->


			<!-- Footer -->
			<div class="navbar navbar-expand-lg navbar-light">
				<div class="text-center d-lg-none w-100">
					<button type="button" class="navbar-toggler dropdown-toggle" data-toggle="collapse" data-target="#navbar-footer">
						<i class="icon-unfold mr-2"></i>
						Footer
					</button>
				</div>

				<div class="navbar-collapse collapse text-center" id="navbar-footer">
					<span class="navbar-text margin-0-auto">
						&copy; <?php echo date('Y'); ?> <a href="#">Bethany House CMS</a>. Powered by <a href="https://devlab.africa" target="_blank">Devlab Africa</a>
					</span>

					<!-- <ul class="navbar-nav ml-lg-auto">
						<li class="nav-item"><a href="#" class="navbar-nav-link" target="_blank"><i class="icon-lifebuoy mr-2"></i> Support</a></li>
						<li class="nav-item"><a href="#" class="navbar-nav-link" target="_blank"><i class="icon-file-text2 mr-2"></i> Docs</a></li>
					</ul> -->
				</div>
			</div>
			<!-- /footer -->

		</div>
		<!-- /main content -->

	</div>
	<!-- /page content -->

</body>
</html>
