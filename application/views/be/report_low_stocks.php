		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="#" class="breadcrumb-item">Reports</a>
							<span class="breadcrumb-item active">Items on Reorder Level Report</span>
						</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content pt-0">
				<div class="row">
					<div class="col-md-12">
						<div class="card rounded-top-0">							
							<div class="card-header bg-transparent header-elements-inline p-2">
								<h5 class="card-title font-weight-600"><i class="icon-warning mr-1"></i> Items on Reorder Level Report</h5>			
							</div>
							<div class="card-body">
								<form method="post" class="form" id="frm_filter_low_stocks_report" name="frm_filter_low_stocks_report" method="post">									
									<div class="spinner2 display-none" id="low_stocks_report_loader">
				                        <div class="rect1"></div>
				                        <div class="rect2"></div>
				                        <div class="rect3"></div>
				                    </div>
									<div id="low_stocks_report_div" style="min-height: 400px;">
										
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>

			
			<script type="text/javascript">
				$(document).ready(function() {

				    filter_low_stocks_report();

				});
			</script>


	