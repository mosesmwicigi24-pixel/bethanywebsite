<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title><?php echo $page_title;?>Bethany House Back End</title>
	<meta name="description" content=" Communion Hosts and Altar Bread · Altar Wine · 
    Holy Water, Oil & Baptismal Accessories · Candles, Candle holders & accessories · 
    Communion Trays. Church Accessories · Church Supplies · Clergy Apparel · 
    Clergy gowns · Holy Communion Ware · Bread plates · Communion bread · 
    Communion cups · Communion Trays.Holy Communion supplies in Kenya based in Nairobi. 
    we supply Holy Communion Elements, Holy Communion items, wine, Communion bread, 
    Pastors gowns, clergy vestments and all church supplies."/>

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
	<link href="<?php echo base_url();?>assets/be/css/icons/icomoon/styles.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/be/css/bootstrap.min.css" rel="stylesheet" type="text/css">
	<link href="<?php echo base_url();?>assets/be/css/bootstrap_limitless.min.css" rel="stylesheet" type="text/css">

	<link rel="stylesheet" type="text/css" href="<?php echo base_url() . auto_version('assets/be/css/layout.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() . auto_version('assets/be/css/components.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() . auto_version('assets/be/css/colors.min.css'); ?>">
	<link rel="stylesheet" type="text/css" href="<?php echo base_url() . auto_version('assets/be/css/custom.css'); ?>">

	<!-- <link href="<?php echo base_url();?>assets/be/css/layout.min.css" rel="stylesheet" type="text/css"> -->
	<!-- <link href="<?php echo base_url();?>assets/be/css/components.min.css" rel="stylesheet" type="text/css"> -->
	<!-- <link href="<?php echo base_url();?>assets/be/css/colors.min.css" rel="stylesheet" type="text/css"> -->
	<!-- <link href="<?php echo base_url();?>assets/be/css/custom.css" rel="stylesheet" type="text/css"> -->
	<!-- /global stylesheets -->

	<!-- Core JS files -->
	<script src="<?php echo base_url();?>assets/be/js/main/jquery.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/main/popper.min.js"></script>
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
	<!-- <script src="<?php echo base_url();?>assets/be/js/plugins/forms/styling/switchery.min.js"></script> -->
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

	<script src="<?php echo base_url(); ?>assets/be/js/plugins/ckeditor/ckeditor.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/pickers/anytime.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/pickers/pickadate/picker.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/pickers/pickadate/picker.date.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/pickers/pickadate/picker.time.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/pickers/pickadate/legacy.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/notifications/jgrowl.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/pickers/color/spectrum.js"></script>

	<script src="<?php echo base_url();?>assets/be/js/plugins/media/fancybox.min.js"></script>

	<script src="<?php echo base_url();?>assets/be/js/plugins/forms/tags/tagsinput.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/forms/tags/tokenfield.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/forms/inputs/typeahead/typeahead.bundle.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/ui/prism.min.js"></script>

	<!-- Theme JS files -->
	<script src="<?php echo base_url();?>assets/be/js/plugins/uploaders/fileinput/plugins/purify.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/uploaders/fileinput/plugins/sortable.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/uploaders/fileinput/fileinput.min.js"></script>

	<script src="<?php echo base_url();?>assets/be/js/plugins/notifications/jgrowl.min.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/plugins/jquery-redirect/jquery.redirect.js"></script>

	<script src="https://www.gstatic.com/charts/loader.js"></script>

	<script src="<?php echo base_url();?>assets/be/js/app.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/demo_pages/form_layouts.js"></script>	
	<script src="<?php echo base_url();?>assets/be/js/demo_pages/form_inputs.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/demo_pages/datatables_basic.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/demo_pages/gallery.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/demo_pages/uploader_bootstrap.js"></script>
	<script src="<?php echo base_url();?>assets/be/js/demo_pages/components_popups.js"></script>
	<!-- <script src="<?php echo base_url();?>assets/be/js/custom.js"></script> -->

	<script src="<?php echo base_url() . auto_version('assets/be/js/custom.js'); ?>"></script>

	<script type="text/javascript">
    	var baseDir = '<?php echo base_url(); ?>';
    	var cur_billing_region_id = '';
    	var cur_shipping_region_id = '';
    	var selValues = [];
    	
    	var cur_locality_id = '';
   	</script>

</head>

<body>

	<!-- Main navbar -->
	<div class="navbar navbar-expand-md navbar-light">

		<!-- Header with logos -->
		<div class="navbar-header navbar-light d-none d-md-flex align-items-md-center">
			<div class="navbar-brand navbar-brand-md">
				<a href="<?php echo base_url();?>be" class="d-inline-block">
					<img src="<?php echo base_url();?>assets/be/images/logo-2.png" alt="">
				</a>
			</div>
			
			<div class="navbar-brand navbar-brand-xs">
				<a href="<?php echo base_url();?>be" class="d-inline-block">
					<img src="<?php echo base_url();?>assets/be/images/logo-mini.png" alt="">
				</a>
			</div>
		</div>
		<!-- /header with logos -->
	

		<!-- Mobile controls -->
		<div class="d-flex flex-1 d-md-none">
			<div class="navbar-brand mr-auto">
				<a href="<?php echo base_url();?>be" class="d-inline-block">
					<img src="<?php echo base_url();?>assets/be/images/logo-2.png" alt="">
				</a>
			</div>	

			<button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
				<i class="icon-tree5"></i>
			</button>

			<button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
				<i class="icon-paragraph-justify3"></i>
			</button>
		</div>
		<!-- /mobile controls -->


		<!-- Navbar content -->
		<div class="collapse navbar-collapse" id="navbar-mobile">
			<ul class="navbar-nav">
				<li class="nav-item">
					<a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
						<i class="icon-paragraph-justify3"></i>
					</a>
				</li>
				<li class="nav-item dropdown">
					<a href="#" class="navbar-nav-link dropdown-toggle caret-0" data-toggle="dropdown">
						<i class="icon-bell2"></i>
						<span class="d-md-none ml-2">NOTIFICATIONS</span>
						<span id="notifications_label_count" class="badge badge-pill bg-primary ml-auto ml-md-0">0</span>
					</a>
					
					<div class="dropdown-menu dropdown-content wmin-md-350">
						<div class="dropdown-content-header">
							<span class="font-weight-bold">NOTIFICATIONS</span>
						</div>

						<div id="notifications_pull_down" class="dropdown-content-body dropdown-scrollable">
							<!-- <p><i>There are no notifications to display yet</i></p> -->
						</div>
						<div class="dropdown-content-footer bg-light">
							<a href="<?php echo base_url(); ?>be/support/notifications" class="text-grey mr-auto">View All Notifications</a>
						</div>
					</div>
				</li>
			</ul>

			

			

			<span class="badge badge-pill ml-md-3 mr-md-auto"><span id="sp_pending_applications_count">&nbsp;</span></span>

			<div class="header-elements">
				<div class="breadcrumb justify-content-center">
					<!-- <a href="#" class="breadcrumb-elements-item">
						<i class="icon-comment-discussion mr-2"></i>
						Support
					</a> -->

					<div class="breadcrumb-elements-item dropdown p-0">
						<a href="<?php echo base_url(); ?>be/settings" class="btn btn-xs btn-primary btn-labeled btn-labeled-left rounded-round" data-popup="tooltip" title="Store Global Settings" data-placement="bottom">
							<b><i class="icon-gear"></i></b>
							Global Settings
						</a>

						<!-- <div class="dropdown-menu dropdown-menu-right">
							<a href="#" class="dropdown-item"><i class="icon-user-lock"></i> Account security</a>
							<a href="#" class="dropdown-item"><i class="icon-statistics"></i> Analytics</a>
							<a href="#" class="dropdown-item"><i class="icon-accessibility"></i> Accessibility</a>
							<div class="dropdown-divider"></div>
							<a href="#" class="dropdown-item"><i class="icon-gear"></i> All settings</a>
						</div> -->
					</div>
				</div>
			</div>

			<ul class="navbar-nav">
				
				
				<li class="nav-item dropdown dropdown-user">
					<a href="#" class="navbar-nav-link d-flex align-items-center dropdown-toggle" data-toggle="dropdown">
						<img src="<?php echo base_url();?>assets/be/images/avi-1.png" class="rounded-circle mr-2" height="34" alt="">
						<span><?php echo $this->session->userdata('user_first_name') . ' ' . $this->session->userdata('user_last_name'); ?></span>
					</a>

					<div class="dropdown-menu dropdown-menu-right">
						<a href="<?php echo base_url();?>be/profile" class="dropdown-item"><i class="icon-user"></i> My Profile</a>
						<div class="dropdown-divider"></div>
						<a href="<?php echo base_url();?>be/auth/logout" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
					</div>
				</li>
			</ul>
		</div>
		
	</div>
	<!-- /main navbar -->

					
	<!-- Page content -->
	<div class="page-content">

		<!-- Main sidebar -->
		<div class="sidebar sidebar-light sidebar-main sidebar-expand-md">

			<!-- Sidebar mobile toggler -->
			<div class="sidebar-mobile-toggler text-center">
				<a href="#" class="sidebar-mobile-main-toggle">
					<i class="icon-arrow-left8"></i>
				</a>
				Navigation
				<a href="#" class="sidebar-mobile-expand">
					<i class="icon-screen-full"></i>
					<i class="icon-screen-normal"></i>
				</a>
			</div>
			<!-- /sidebar mobile toggler -->


			<!-- Sidebar content -->
			<div class="sidebar-content">
				
				<!-- User menu -->
				<div class="sidebar-user">
					<div class="card-body">
						<div class="media">
							<div class="mr-3">
								<a href="#"><img src="<?php echo base_url();?>assets/be/images/avi-1.png" width="38" height="38" class="rounded-circle" alt=""></a>
							</div>

							<div class="media-body">
								<div class="media-title font-weight-semibold"><?php echo $this->session->userdata('user_first_name') . ' ' . $this->session->userdata('user_last_name'); ?></div>
								<div class="font-size-xs opacity-50">
									<i class="icon-user font-size-sm"></i> &nbsp;Administrator
								</div>
							</div>

							<!-- <div class="ml-3 align-self-center">
								<a href="#" class="text-white"><i class="icon-cog3"></i></a>
							</div> -->
						</div>
					</div>
				</div>
				<!-- /user menu -->

				
				<!-- Main navigation -->
				<div class="card card-sidebar-mobile">
					<ul class="nav nav-sidebar" data-nav-type="accordion">
						<li class="nav-item">
							<a href="<?php echo base_url();?>be" class="nav-link <?php if ($cur == 'Dashboard'){echo 'active';} ?>"><i class="icon-home4"></i><span>Dashboard</span></a>
						</li>
						<!-- <li class="nav-item nav-item-submenu <?php if ($cur == 'Store Setup'){echo 'nav-item-expanded nav-item-open';} ?>">
							<a href="#" class="nav-link <?php if ($cur == 'Store Setup'){echo 'active';} ?>"><i class="icon-office"></i> <span>Store Setup</span></a>

							<ul class="nav nav-group-sub" data-submenu-title="Store Setup">
								<li class="nav-item"><a href="<?php echo base_url();?>be/payment_methods" class="nav-link <?php if ($cur_sub == 'Payment Methods'){echo 'active';} ?>">Payment Methods</a></li>
							</ul>
						</li> -->
						<li class="nav-item nav-item-submenu <?php if ($cur == 'Products'){echo 'nav-item-expanded nav-item-open';} ?>">
							<a href="#" class="nav-link <?php if ($cur == 'Products'){echo 'active';} ?>"><i class="icon-grid52"></i> <span>Products</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Products">
								<li class="nav-item"><a href="<?php echo base_url();?>be/products" class="nav-link <?php if ($cur_sub == 'Products'){echo 'active';} ?>">Products</a></li>
								<li class="nav-item"><a href="<?php echo base_url();?>be/products/add" class="nav-link <?php if ($cur_sub == 'New Product'){echo 'active';} ?>">New Product</a></li>
								<li class="nav-item"><a href="<?php echo base_url();?>be/product_categories" class="nav-link <?php if ($cur_sub == 'Product Categories'){echo 'active';} ?>">Product Categories</a></li>
								<li class="nav-item nav-item-submenu <?php if ($cur_sub == 'Units of Measure'){echo 'nav-item-expanded nav-item-open';} ?>">
									<a href="#" class="nav-link <?php if ($cur_sub == 'Units of Measure'){echo 'active';} ?>">Units of Measure</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="<?php echo base_url();?>be/units" class="nav-link <?php if ($cur_cur_sub == 'Units'){echo 'active';} ?>">Units</a></li>
										<li class="nav-item"><a href="<?php echo base_url();?>be/units/types" class="nav-link <?php if ($cur_cur_sub == 'Unit Types'){echo 'active';} ?>">Unit Types</a></li>
									</ul>
								</li>
								<!-- <li class="nav-item"><a href="<?php echo base_url();?>be/units" class="nav-link <?php if ($cur_sub == 'Units'){echo 'active';} ?>">Units of Measure</a></li> -->
								<!-- <li class="nav-item"><a href="<?php echo base_url();?>be/product_sizes" class="nav-link <?php if ($cur_sub == 'Product Sizes'){echo 'active';} ?>">Product Sizes</a></li> -->
								<!-- <li class="nav-item"><a href="<?php echo base_url();?>be/product_colors" class="nav-link <?php if ($cur_sub == 'Product Colors'){echo 'active';} ?>">Product Colors</a></li>-->
								<li class="nav-item"><a href="<?php echo base_url();?>be/brands" class="nav-link <?php if ($cur_sub == 'Brands'){echo 'active';} ?>">Brands</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu <?php if ($cur == 'Inventory'){echo 'nav-item-expanded nav-item-open';} ?>">
							<a href="#" class="nav-link <?php if ($cur == 'Inventory'){echo 'active';} ?>"><i class="icon-cube3"></i> <span>Inventory Management</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Inventory">
								<li class="nav-item nav-item-submenu <?php if ($cur_sub == 'Transactions'){echo 'nav-item-expanded nav-item-open';} ?>">
									<a href="#" class="nav-link <?php if ($cur_sub == 'Transactions'){echo 'active';} ?>">Transactions</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="<?php echo base_url();?>be/inventory/purchase_orders" class="nav-link <?php if ($cur_cur_sub == 'Purchase Orders'){echo 'active';} ?>">Purchase Orders</a></li>
										<li class="nav-item"><a href="<?php echo base_url();?>be/inventory/goods_receipt_notes" class="nav-link <?php if ($cur_cur_sub == 'Goods Receipt Notes'){echo 'active';} ?>">Goods Receipt Notes</a></li>
										<li class="nav-item"><a href="<?php echo base_url();?>be/inventory/goods_return_notes" class="nav-link <?php if ($cur_cur_sub == 'Goods Return Notes'){echo 'active';} ?>">Goods Return Notes</a></li>
										<!-- <li class="nav-item"><a href="<?php echo base_url();?>be/inventory/credit_notes" class="nav-link <?php if ($cur_cur_sub == 'Credit Notes'){echo 'active';} ?>">Credit Notes</a></li> -->
										<li class="nav-item"><a href="<?php echo base_url();?>be/inventory/stock_adjustments" class="nav-link <?php if ($cur_cur_sub == 'Stock Adjustments'){echo 'active';} ?>">Stock Adjustments</a></li>
										<li class="nav-item"><a href="<?php echo base_url();?>be/inventory/stock_transfers" class="nav-link <?php if ($cur_cur_sub == 'Stock Transfers'){echo 'active';} ?>">Stock Transfers</a></li>
										<li class="nav-item"><a href="<?php echo base_url();?>be/inventory/stock_writeoffs" class="nav-link <?php if ($cur_cur_sub == 'Stock Write-offs'){echo 'active';} ?>">Stock Write-offs</a></li>
									</ul>
								</li>
								<li class="nav-item nav-item-submenu <?php if ($cur_sub == 'Stocks'){echo 'nav-item-expanded nav-item-open';} ?>">
									<a href="#" class="nav-link <?php if ($cur_sub == 'Stocks'){echo 'active';} ?>">Stocks</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="<?php echo base_url();?>be/inventory/current_stocks" class="nav-link <?php if ($cur_cur_sub == 'Current Stocks'){echo 'active';} ?>">Current Stocks</a></li>
										<li class="nav-item"><a href="<?php echo base_url();?>be/inventory/low_stocks" class="nav-link <?php if ($cur_cur_sub == 'Low Stocks'){echo 'active';} ?>">Items on Reorder Level</a></li>
									</ul>
								</li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu <?php if ($cur == 'Sales'){echo 'nav-item-expanded nav-item-open';} ?>">
							<a href="#" class="nav-link <?php if ($cur == 'Sales'){echo 'active';} ?>"><i class="icon-cart2"></i> <span>Sales</span></a>
							<ul class="nav nav-group-sub">
								<li class="nav-item"><a href="<?php echo base_url();?>be/sales/online" class="nav-link <?php if ($cur_sub == 'Online Sales'){echo 'active';} ?>">Online Sales</a></li>
								<!-- <li class="nav-item"><a href="#" class="nav-link <?php if ($cur_sub == 'Cash Sales'){echo 'active';} ?>">Cash Sales</a></li>
								<li class="nav-item"><a href="#" class="nav-link <?php if ($cur_sub == 'Credit Sales'){echo 'active';} ?>">Credit Sales</a></li> -->
							</ul>
						</li>
						<li class="nav-item nav-item-submenu <?php if ($cur == 'Payments'){echo 'nav-item-expanded nav-item-open';} ?>">
							<a href="#" class="nav-link <?php if ($cur == 'Payments'){echo 'active';} ?>"><i class="icon-cash"></i> <span>Payments</span></a>
							<ul class="nav nav-group-sub">
								<li class="nav-item"><a href="<?php echo base_url();?>be/payments/paybill" class="nav-link <?php if ($cur_sub == 'Paybill Payments'){echo 'active';} ?>">Paybill Payments</a></li>
								<li class="nav-item"><a href="<?php echo base_url();?>be/payments/pesapal" class="nav-link <?php if ($cur_sub == 'Pesapal Payments'){echo 'active';} ?>">Pesapal Payments</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu <?php if ($cur == 'Customers'){echo 'nav-item-expanded nav-item-open';} ?>">
							<a href="#" class="nav-link <?php if ($cur == 'Customers'){echo 'active';} ?>"><i class="icon-users4"></i> <span>Customers</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Customers">
								<li class="nav-item"><a href="<?php echo base_url();?>be/customers" class="nav-link <?php if ($cur_sub == 'Customers'){echo 'active';} ?>">Customers</a></li>
								<li class="nav-item"><a href="<?php echo base_url();?>be/customers/add" class="nav-link <?php if ($cur_sub == 'New Customer'){echo 'active';} ?>">New Customer</a></li>
								<li class="nav-item"><a href="<?php echo base_url();?>be/customers/groups" class="nav-link <?php if ($cur_sub == 'Customer Groups'){echo 'active';} ?>">Customer Groups</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu <?php if ($cur == 'Reports'){echo 'nav-item-expanded nav-item-open';} ?>">
							<a href="#" class="nav-link <?php if ($cur == 'Reports'){echo 'active';} ?>"><i class="icon-file-presentation2"></i> <span>Reporting</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Reports">
								<li class="nav-item"><a href="<?php echo base_url();?>be/reports/sales" class="nav-link <?php if ($cur_sub == 'Sales'){echo 'active';} ?>">Sales Reports</a></li>
								<li class="nav-item"><a href="<?php echo base_url();?>be/reports/customers" class="nav-link <?php if ($cur_sub == 'Customers'){echo 'active';} ?>">Customer Reports</a></li>
								<li class="nav-item"><a href="<?php echo base_url();?>be/reports/stock" class="nav-link <?php if ($cur_sub == 'Stock'){echo 'active';} ?>">Stock Reports</a></li>
								<li class="nav-item"><a href="<?php echo base_url();?>be/reports/low_stocks" class="nav-link <?php if ($cur_sub == 'Low Stocks'){echo 'active';} ?>">Items on Reorder Level</a></li>
								<li class="nav-item"><a href="<?php echo base_url();?>be/reports/payments" class="nav-link <?php if ($cur_sub == 'Payments'){echo 'active';} ?>">Payments Reports</a></li>
								<li class="nav-item"><a href="<?php echo base_url();?>be/reports/expenses" class="nav-link <?php if ($cur_sub == 'Expenses'){echo 'active';} ?>">Expenses Reports</a></li>
								<li class="nav-item"><a href="<?php echo base_url();?>be/reports/income_statement" class="nav-link <?php if ($cur_sub == 'Income Statement'){echo 'active';} ?>">Income Statement</a></li>
							</ul>
						</li>
						<li class="nav-item"><a href="<?php echo base_url();?>be/suppliers" class="nav-link <?php if ($cur == 'Suppliers'){echo 'active';} ?>"><i class="icon-users"></i> <span>Suppliers</span></a></li>
						<li class="nav-item nav-item-submenu <?php if ($cur == 'Affiliate Program'){echo 'nav-item-expanded nav-item-open';} ?>">
							<a href="#" class="nav-link <?php if ($cur == 'Affiliate Program'){echo 'active';} ?>"><i class="icon-users2"></i> <span>Affiliate Program</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Affiliate Program">
								<li class="nav-item"><a href="<?php echo base_url();?>be/affiliates" class="nav-link <?php if ($cur_sub == 'Affiliate Accounts'){echo 'active';} ?>">Affiliate Accounts</a></li>
								<li class="nav-item"><a href="<?php echo base_url();?>be/affiliate_packages" class="nav-link <?php if ($cur_sub == 'Affiliate Packages'){echo 'active';} ?>">Affiliate Packages</a></li>
								<li class="nav-item"><a href="<?php echo base_url();?>be/affiliates/terms" class="nav-link <?php if ($cur_sub == 'Affiliates T&Cs'){echo 'active';} ?>">Affiliates T&amp;Cs</a></li>
							</ul>
						</li>
						<li class="nav-item nav-item-submenu <?php if ($cur == 'Promotions'){echo 'nav-item-expanded nav-item-open';} ?>">
							<a href="#" class="nav-link <?php if ($cur == 'Promotions'){echo 'active';} ?>"><i class="icon-megaphone"></i> <span>Promotions</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Promotions">
								<li class="nav-item"><a href="<?php echo base_url();?>be/promo_codes" class="nav-link <?php if ($cur_sub == 'Promo Codes'){echo 'active';} ?>">Promo Codes</a></li>
							</ul>
						</li>	
						<li class="nav-item nav-item-submenu <?php if ($cur == 'CMS Content'){echo 'nav-item-expanded nav-item-open';} ?>">
							<a href="#" class="nav-link <?php if ($cur == 'CMS Content'){echo 'active';} ?>"><i class="icon-list3"></i> <span>CMS Content</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="CMS Content">
								<li class="nav-item nav-item-submenu <?php if ($cur_sub == 'Home Page'){echo 'nav-item-expanded nav-item-open';} ?>">
									<a href="#" class="nav-link <?php if ($cur_sub == 'Home Page'){echo 'active';} ?>">Home Page</a>
									<ul class="nav nav-group-sub">
										<li class="nav-item"><a href="<?php echo base_url();?>be/home_sliders" class="nav-link <?php if ($cur_cur_sub == 'Home Sliders'){echo 'active';} ?>">Home Sliders</a></li>
										<li class="nav-item"><a href="<?php echo base_url();?>be/home_page/top_categories" class="nav-link <?php if ($cur_cur_sub == 'Top Categories'){echo 'active';} ?>">Top Categories</a></li>
										<li class="nav-item"><a href="<?php echo base_url();?>be/home_page/featured_categories" class="nav-link <?php if ($cur_cur_sub == 'Featured Categories'){echo 'active';} ?>">Featured Categories</a></li>
										<li class="nav-item"><a href="<?php echo base_url();?>be/home_page/promo_banners" class="nav-link <?php if ($cur_cur_sub == 'Promo Banners'){echo 'active';} ?>">Promo Banners</a></li>
									</ul>
								</li>
								<li class="nav-item"><a href="<?php echo base_url();?>be/testimonials" class="nav-link <?php if ($cur_sub == 'Testimonials'){echo 'active';} ?>">Testimonials</a></li>
								<li class="nav-item"><a href="<?php echo base_url();?>be/about_us" class="nav-link <?php if ($cur_sub == 'About Us'){echo 'active';} ?>">About Us</a></li>
								<li class="nav-item"><a href="<?php echo base_url();?>be/how_to_shop" class="nav-link <?php if ($cur_sub == 'How To Shop'){echo 'active';} ?>">How To Shop</a></li>
								<li class="nav-item"><a href="<?php echo base_url();?>be/faqs" class="nav-link <?php if ($cur_sub == 'FAQs'){echo 'active';} ?>">FAQs</a></li>
								<li class="nav-item"><a href="<?php echo base_url();?>be/return_policy" class="nav-link <?php if ($cur_sub == 'Return Policy'){echo 'active';} ?>">Return Policy</a></li>
								<li class="nav-item"><a href="<?php echo base_url();?>be/privacy_policy" class="nav-link <?php if ($cur_sub == 'Privacy Policy'){echo 'active';} ?>">Privacy Policy</a></li>
								<li class="nav-item"><a href="<?php echo base_url();?>be/terms_and_conditions" class="nav-link <?php if ($cur_sub == 'Terms and Conditions'){echo 'active';} ?>">Terms &amp; Conditions</a></li>
							</ul>
						</li>					
						<li class="nav-item nav-item-submenu <?php if ($cur == 'Blog'){echo 'nav-item-expanded nav-item-open';} ?>">
							<a href="#" class="nav-link <?php if ($cur == 'Blog'){echo 'active';} ?>"><i class="icon-magazine"></i> <span>Blog</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Blog">
								<li class="nav-item"><a href="<?php echo base_url();?>be/blog" class="nav-link <?php if ($cur_sub == 'Blog'){echo 'active';} ?>">Blog Articles</a></li>
								<li class="nav-item"><a href="<?php echo base_url();?>be/blog_categories" class="nav-link <?php if ($cur_sub == 'Blog Categories'){echo 'active';} ?>">Blog Categories</a></li>
							</ul>
						</li>
						<!-- <li class="nav-item">
							<a href="<?php echo base_url();?>be/careers" class="nav-link <?php if ($cur == 'Careers'){echo 'active';} ?>"><i class="icon-wrench"></i><span>Careers</span></a>
						</li> -->
						<li class="nav-item nav-item-submenu <?php if ($cur == 'System Users'){echo 'nav-item-expanded nav-item-open';} ?>">
							<a href="#" class="nav-link <?php if ($cur == 'System Users'){echo 'active';} ?>"><i class="icon-users2"></i> <span>User Management</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="System Users">
								<li class="nav-item"><a href="<?php echo base_url();?>be/system_users" class="nav-link <?php if ($cur_sub == 'System Users'){echo 'active';} ?>">System Users</a></li>
								<li class="nav-item"><a href="<?php echo base_url();?>be/user_roles" class="nav-link <?php if ($cur_sub == 'User Roles'){echo 'active';} ?>">User Roles</a></li>
								<!-- <li class="nav-item"><a href="<?php echo base_url();?>be/departments" class="nav-link <?php if ($cur_sub == 'Departments'){echo 'active';} ?>">Departments</a></li> -->
							</ul>
						</li>
						<li class="nav-item">
							<a href="<?php echo base_url();?>be/seo" class="nav-link <?php if ($cur == 'SEO'){echo 'active';} ?>"><i class="icon-wrench"></i><span>Search Engine Optimization</span></a>
						</li>
						<li class="nav-item nav-item-submenu <?php if ($cur == 'Help & Support'){echo 'nav-item-expanded nav-item-open';} ?>">
							<a href="#" class="nav-link <?php if ($cur == 'Help & Support'){echo 'active';} ?>"><i class="icon-info22"></i> <span>Help & Support</span></a>
							<ul class="nav nav-group-sub" data-submenu-title="Help & Support">
								<li class="nav-item"><a href="<?php echo base_url();?>be/support/notifications" class="nav-link <?php if ($cur_sub == 'Notifications'){echo 'active';} ?>">Notifications</a></li>
							</ul>
						</li>
					</ul>
				</div>
				<!-- /main navigation -->

			</div>
			<!-- /sidebar content -->
			
		</div>
		<!-- /main sidebar -->
