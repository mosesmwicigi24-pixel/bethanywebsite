		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="#" class="breadcrumb-item">Reports</a>
							<span class="breadcrumb-item active">Income Statement</span>
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
						<!-- <div class="card">							
							<div class="card-headerp-2">
								<h5 class="card-title font-weight-600"><i class="icon-graph mr-1"></i> Income Statement</h5>			
							</div>
							<div class="card-body"> -->
								<h4 class="card-title font-weight-600 text-center"><i class="icon-coins mr-1"></i> Income Statement</h4>
								<form method="post" class="form" id="frm_filter_income_statement" name="frm_filter_income_statement" method="post">									
									<div class="spinner2 display-none" id="income_statement_loader">
				                        <div class="rect1"></div>
				                        <div class="rect2"></div>
				                        <div class="rect3"></div>
				                    </div>
				                    <div class="row">
				                    	<div class="col-sm-4"></div>
										<div class="col-sm-2 font-weight-600">
											<div class="form-group mb-2">
												<input type="hidden" id="is_date_from" name="date_from" value="">
												<input type="hidden" id="is_date_to" name="date_to" value="">
												<div class="input-group">
													<input type="text" id="is_date_from_to" class="form-control daterangepicker" value="2013-01-08 - 2013-01-08">
													<span class="input-group-append">
														<span class="input-group-text"><i class="icon-calendar22"></i></span>
													</span>
												</div>
											</div>
										</div>
										<div class="col-sm-1">
											<button id="btn_income_statement_filter" type="button" onclick="filter_income_statement();" class="btn btn-primary btn-sm btn-block font-weight-600"><i class="icon-spinner9"></i> Generate</button>
										</div>
										<div class="col-md-1">
											<button id="btn_sales_summary_export" type="button" onclick="export_income_statement();" class="btn btn-primary btn-sm btn-block font-weight-600"><i class="icon-file-pdf"></i> Export</button>
										</div>
									</div>
									<div id="income_statement_div" style="min-height: 500px;">
										
									</div>
								</form>
							<!-- </div>
						</div> -->

					</div>
				</div>
			</div>

			
			<script type="text/javascript">
				$(document).ready(function() {

					$('.daterangepicker').daterangepicker({
				            startDate: moment().subtract(29, 'days'),
				            endDate: moment(),
				            ranges: {
				                'Today': [moment(), moment()],
				                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				                'This Month': [moment().startOf('month'), moment().endOf('month')],
				                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				            },
				            applyClass: 'btn-sm btn-primary',
				            cancelClass: 'btn-sm btn-danger'
				        },
				        function(start, end) {
				        	assign_dates();
				        }
				    );
				    assign_dates();

				    filter_income_statement();

				});

				function assign_dates() {
					$('#is_date_from').val($('#is_date_from_to').data('daterangepicker').startDate.format('YYYY-MM-DD'));
				   	$('#is_date_to').val($('#is_date_from_to').data('daterangepicker').endDate.format('YYYY-MM-DD'));					
				}
			</script>


	