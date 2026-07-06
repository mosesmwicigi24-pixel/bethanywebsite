		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="#" class="breadcrumb-item">Payments</a>
							<span class="breadcrumb-item active">Pesapal Payments</span>
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
							<div class="spinner2 display-none" id="pesapal_payments_loader">
		                        <div class="rect1"></div>
		                        <div class="rect2"></div>
		                        <div class="rect3"></div>
		                    </div>
							<div class="card-header bg-transparent header-elements-inline p-2">
								<h5 class="card-title font-weight-bold"><i class="icon-cash mr-1"></i> Pesapal Payments</h5>			
							</div>

							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<form method="post" class="form" id="frm_filter_pesapal_payments" name="frm_filter_pesapal_payments" method="post">
											<div class="">
												<div class="row">
													<!-- <div class="col-sm-3 font-weight-600">
														<div class="form-group mb-2">
															<select id="pesapal_payment_status" name="pesapal_payment_status" class="form-control select" data-placeholder="Filter by Status" data-fouc>
																<option value="">Filter by Status</option>
																<option value="Open">Open</option>
		                                                        <option value="Partially Received">Partially Received</option>
		                                                        <option value="Closed">Closed</option>
		                                                        <option value="Void">Void</option>
															</select>
														</div>
													</div> -->
													<div class="col-sm-2 font-weight-600">
														<div class="form-group mb-2">
															<input type="hidden" id="date_from" name="date_from" value="">
															<input type="hidden" id="date_to" name="date_to" value="">
															<div class="input-group">
																<input type="text" id="date_from_to" class="form-control daterangepicker" value="2013-01-08 - 2013-01-08">
																<span class="input-group-append">
																	<span class="input-group-text"><i class="icon-calendar22"></i></span>
																</span>
															</div>
														</div>
													</div>
													<div class="col-sm-2">
														<button id="btn_pesapal_payments_filter" type="button" onclick="filter_pesapal_payments();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> FILTER</button>
													</div>
												</div>
											</div>
											<div id="pesapal_payments_div" style="min-height: 400px;">
									
											</div>
										</form>
									</div>
								</div>
							</div>

							
						</div>
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

				    filter_pesapal_payments();
				});
				function assign_dates() {
					$('#date_from').val($('#date_from_to').data('daterangepicker').startDate.format('YYYY-MM-DD'));
				   	$('#date_to').val($('#date_from_to').data('daterangepicker').endDate.format('YYYY-MM-DD'));					
				}
				// filter_pesapal_payments();
			</script>
	