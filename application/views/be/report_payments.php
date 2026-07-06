		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="#" class="breadcrumb-item">Reports</a>
							<span class="breadcrumb-item active">Payments Report</span>
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
								<h5 class="card-title font-weight-600"><i class="icon-coins mr-1"></i> Payments Reports</h5>			
							</div>
							<div class="card-body">

								
								<ul class="nav nav-tabs nav-tabs-solid rounded bg-light font-14 font-weight-bold">
									<li class="nav-item"><a href="#tab-payments-summary" class="nav-link rounded-left active" data-toggle="tab">Payments Summary</a></li>
									<li class="nav-item"><a href="#tab-pos-payments" class="nav-link" data-toggle="tab">POS Payments</a></li>
									<li class="nav-item"><a href="#tab-online-payments" class="nav-link" data-toggle="tab">Online Payments</a></li>
								</ul>

								<div class="tab-content">
									<div class="tab-pane fade show active" id="tab-payments-summary">
										<form method="post" class="form" id="frm_filter_payments_summary" name="frm_filter_payments_summary" method="post">
											<div class="mb-3">
												<div class="spinner2 display-none" id="payments_summary_loader">
							                        <div class="rect1"></div>
							                        <div class="rect2"></div>
							                        <div class="rect3"></div>
							                    </div>
												<div class="row">
													<div class="col-sm-3 font-weight-600">
														<div class="form-group mb-2">
															<input type="hidden" id="ps_date_from" name="date_from" value="">
															<input type="hidden" id="ps_date_to" name="date_to" value="">
															<div class="input-group">
																<input type="text" id="ps_date_from_to" class="form-control daterangepicker" value="2013-01-08 - 2013-01-08">
																<span class="input-group-append">
																	<span class="input-group-text"><i class="icon-calendar22"></i></span>
																</span>
															</div>
														</div>
													</div>
													<div class="col-sm-2">
														<button id="btn_payments_summary_filter" type="button" onclick="filter_payments_summary();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-spinner9"></i> Generate Report</button>
													</div>
												</div>
											</div>
											<div id="payments_summary_report_div" style="min-height: 400px;">
											</div>
										</form>
									</div>

									<div class="tab-pane fade" id="tab-pos-payments">
										<form method="post" class="form" id="frm_filter_pos_payments" name="frm_filter_pos_payments" method="post">
											<div class="mb-3">
												<div class="spinner2 display-none" id="pos_payments_loader">
							                        <div class="rect1"></div>
							                        <div class="rect2"></div>
							                        <div class="rect3"></div>
							                    </div>
												<div class="row">
													<div class="col-sm-2 font-weight-600">
														<div class="form-group mb-2">
															<input type="hidden" id="pp_date_from" name="date_from" value="">
															<input type="hidden" id="pp_date_to" name="date_to" value="">
															<div class="input-group">
																<input type="text" id="pp_date_from_to" class="form-control daterangepicker2" value="2013-01-08 - 2013-01-08">
																<span class="input-group-append">
																	<span class="input-group-text"><i class="icon-calendar22"></i></span>
																</span>
															</div>
														</div>
													</div>
													<div class="col-sm-2 font-weight-600">
														<div class="form-group mb-2">
															<select id="pp_payment_method" name="payment_method" data-placeholder="Select Payment Method" class="form-control select" data-fouc>
																<option value="">Select Payment Method</option>
																<option value="Cash">Cash</option>
	                                                            <option value="MPESA">MPESA</option>
	                                                            <option value="Cheque">Cheque</option>
	                                                            <option value="CashApp">CashApp</option>
	                                                            <option value="Wave">Wave</option>
															</select>
														</div>
													</div>
													<div class="col-sm-2">
														<button id="btn_payments_summary_filter" type="button" onclick="filter_pos_payments();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-spinner9"></i> Generate Report</button>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12 text-right">
													<button type="button" onclick="export_pos_payments_report();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-file-pdf"></i> Export</button>
												</div>
											</div>
											<div id="pos_payments_div" style="min-height: 400px;">
											</div>
										</form>
									</div>

									<div class="tab-pane fade" id="tab-online-payments">
										<form method="post" class="form" id="frm_filter_online_payments" name="frm_filter_online_payments" method="post">
											<div class="mb-3">
												<div class="spinner2 display-none" id="online_payments_loader">
							                        <div class="rect1"></div>
							                        <div class="rect2"></div>
							                        <div class="rect3"></div>
							                    </div>
												<div class="row">
													<div class="col-sm-3 font-weight-600">
														<div class="form-group mb-2">
															<input type="hidden" id="op_date_from" name="date_from" value="">
															<input type="hidden" id="op_date_to" name="date_to" value="">
															<div class="input-group">
																<input type="text" id="op_date_from_to" class="form-control daterangepicker2" value="2013-01-08 - 2013-01-08">
																<span class="input-group-append">
																	<span class="input-group-text"><i class="icon-calendar22"></i></span>
																</span>
															</div>
														</div>
													</div>
													<div class="col-sm-2">
														<button id="btn_payments_summary_filter" type="button" onclick="filter_online_payments();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-spinner9"></i> Generate Report</button>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12 text-right">
													<button type="button" onclick="export_online_payments_report();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-file-pdf"></i> Export</button>
												</div>
											</div>
											<div id="online_payments_div" style="min-height: 400px;">

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

				    $('.daterangepicker2').daterangepicker({
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
				        	assign_dates2();
				        }
				    );
				    assign_dates2();

				    $('.daterangepicker3').daterangepicker({
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
				        	assign_dates2();
				        }
				    );
				    assign_dates3();

				    filter_payments_summary();

				    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
				    	switch ($(e.target).html()) {
				          case 'Payments Summary':
				            filter_payments_summary();
				            break;

				          case 'POS Payments':
				            filter_pos_payments();
				            break;

				           case 'Online Payments':
				            filter_online_payments();
				            break;
				        }
					});
				});
				function assign_dates() {
					$('#ps_date_from').val($('#ps_date_from_to').data('daterangepicker').startDate.format('YYYY-MM-DD'));
				   	$('#ps_date_to').val($('#ps_date_from_to').data('daterangepicker').endDate.format('YYYY-MM-DD'));					
				}
				function assign_dates2() {
					$('#pp_date_from').val($('#pp_date_from_to').data('daterangepicker').startDate.format('YYYY-MM-DD'));
				   	$('#pp_date_to').val($('#pp_date_from_to').data('daterangepicker').endDate.format('YYYY-MM-DD'));					
				}
				function assign_dates3() {
					$('#op_date_from').val($('#op_date_from_to').data('daterangepicker').startDate.format('YYYY-MM-DD'));
				   	$('#op_date_to').val($('#op_date_from_to').data('daterangepicker').endDate.format('YYYY-MM-DD'));					
				}
			</script>


	