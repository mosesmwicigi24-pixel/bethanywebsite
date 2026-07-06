		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="#" class="breadcrumb-item">Reports</a>
							<span class="breadcrumb-item active">Sales Report</span>
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
								<h5 class="card-title font-weight-600"><i class="icon-file-presentation2 mr-1"></i> Sales Reports</h5>			
							</div>
							<div class="card-body">

								
								<ul class="nav nav-tabs nav-tabs-solid rounded bg-light font-14">
									<li class="nav-item"><a href="#tab-sales-summary" class="nav-link rounded-left active" data-toggle="tab">Sales Summary</a></li>
									<li class="nav-item"><a href="#tab-sales-by-items" class="nav-link" data-toggle="tab">Sales By Items (Summary)</a></li>
									<li class="nav-item"><a href="#tab-sales-transactions" class="nav-link" data-toggle="tab">Sales Transactions</a></li>
									<li class="nav-item"><a href="#tab-item-sales" class="nav-link" data-toggle="tab">Item Sales</a></li>
								</ul>

								<div class="tab-content">
									<div class="tab-pane fade show active" id="tab-sales-summary">
										<form method="post" class="form" id="frm_filter_sales_summary" name="frm_filter_sales_summary" method="post">
											<div class="mb-3">
												<div class="spinner2 display-none" id="sales_summary_loader">
							                        <div class="rect1"></div>
							                        <div class="rect2"></div>
							                        <div class="rect3"></div>
							                    </div>
												<div class="row">
													<div class="col-sm-3 font-weight-600">
														<div class="form-group mb-2">
															<input type="hidden" id="ss_date_from" name="date_from" value="">
															<input type="hidden" id="ss_date_to" name="date_to" value="">
															<div class="input-group">
																<input type="text" id="ss_date_from_to" class="form-control daterangepicker" value="2013-01-08 - 2013-01-08">
																<span class="input-group-append">
																	<span class="input-group-text"><i class="icon-calendar22"></i></span>
																</span>
															</div>
														</div>
													</div>
													<div class="col-sm-3 font-weight-600">
														<div class="form-group mb-2">
															<select id="ss_outlet_id" name="outlet_id" data-placeholder="Select Outlet" class="form-control select" data-fouc>
																<option value="">Select Outlet</option>
																<?php foreach ($outlets as $row): ?>
																	<option value="<?php echo $row->outlet_id; ?>"><?php echo $row->outlet_name; ?></option>
																<?php endforeach; ?>
															</select>
														</div>
													</div>
													<div class="col-sm-2">
														<button id="btn_sales_summary_filter" type="button" onclick="filter_sales_summary();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-spinner9"></i> Generate Report</button>
													</div>
												</div>
											</div>
											<div id="sales_summary_report_div" style="min-height: 400px;">
											</div>
										</form>
									</div>

									<div class="tab-pane fade" id="tab-sales-by-items">
										<form method="post" class="form" id="frm_filter_sales_by_items" name="frm_filter_sales_by_items" method="post">
											<div class="mb-3">
												<div class="spinner2 display-none" id="sales_by_items_loader">
							                        <div class="rect1"></div>
							                        <div class="rect2"></div>
							                        <div class="rect3"></div>
							                    </div>
												<div class="row">
													<div class="col-sm-2 font-weight-600">
														<div class="form-group mb-2">
															<input type="hidden" id="sbi_date_from" name="date_from" value="">
															<input type="hidden" id="sbi_date_to" name="date_to" value="">
															<div class="input-group">
																<input type="text" id="sbi_date_from_to" class="form-control daterangepicker2" value="2013-01-08 - 2013-01-08">
																<span class="input-group-append">
																	<span class="input-group-text"><i class="icon-calendar22"></i></span>
																</span>
															</div>
														</div>
													</div>
													<div class="col-sm-2 font-weight-600">
														<div class="form-group mb-2">
															<select id="sbi_outlet_id" name="outlet_id" data-placeholder="Select Outlet" class="form-control select" data-fouc>
																<option value="">Select Outlet</option>
																<?php foreach ($outlets as $row): ?>
																	<option value="<?php echo $row->outlet_id; ?>"><?php echo $row->outlet_name; ?></option>
																<?php endforeach; ?>
															</select>
														</div>
													</div>
													<div class="col-sm-2 font-weight-600">
														<div class="form-group mb-2">
															<select id="sbi_transaction_type" name="transaction_type" data-placeholder="Select Transaction Type" class="form-control select" data-fouc>
																<option value="">Select Transaction Type</option>
																<option value="POS">POS Sales</option>
																<option value="Online">Online Sales</option>
															</select>
														</div>
													</div>
													<div class="col-sm-2">
														<button id="btn_sales_summary_filter" type="button" onclick="filter_sales_by_items();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-spinner9"></i> Generate Report</button>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12 text-right">
													<button id="btn_sales_by_items_export" type="button" onclick="export_sales_by_items_report();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-file-pdf"></i> Export</button>
												</div>
											</div>
											<div id="sales_by_items_report_div" style="min-height: 400px;">

											</div>
										</form>
									</div>

									<div class="tab-pane fade" id="tab-sales-transactions">

										<ul class="nav nav-tabs nav-tabs-bottom font-13">
											<li class="nav-item"><a href="#bottom-tab1" class="nav-link active" data-toggle="tab">Point of Sale (POS) Sales</a></li>
											<li class="nav-item"><a href="#bottom-tab2" class="nav-link" data-toggle="tab">Online Sales</a></li>
										</ul>

										<div class="tab-content">
											<div class="tab-pane fade show active" id="bottom-tab1">
												<form method="post" class="form" id="frm_filter_sales_transactions" name="frm_filter_sales_transactions" method="post">
													<div class="mb-3">
														<div class="spinner2 display-none" id="sales_transactions_loader">
									                        <div class="rect1"></div>
									                        <div class="rect2"></div>
									                        <div class="rect3"></div>
									                    </div>
														<div class="row">
															<div class="col-sm-2 font-weight-600">
																<div class="form-group mb-2">
																	<input type="hidden" id="st_date_from" name="date_from" value="">
																	<input type="hidden" id="st_date_to" name="date_to" value="">
																	<div class="input-group">
																		<input type="text" id="st_date_from_to" class="form-control daterangepicker3" value="2013-01-08 - 2013-01-08">
																		<span class="input-group-append">
																			<span class="input-group-text"><i class="icon-calendar22"></i></span>
																		</span>
																	</div>
																</div>
															</div>
															<div class="col-sm-2 font-weight-600">
																<div class="form-group mb-2">
																	<select id="st_outlet_id" name="outlet_id" data-placeholder="Select Outlet" class="form-control select" data-fouc>
																		<option value="">Select Outlet</option>
																		<?php foreach ($outlets as $row): ?>
																			<option value="<?php echo $row->outlet_id; ?>"><?php echo $row->outlet_name; ?></option>
																		<?php endforeach; ?>
																	</select>
																</div>
															</div>
															<div class="col-sm-2 font-weight-600">
																<div class="form-group mb-2">
																	<select id="st_sale_type" name="sale_type" data-placeholder="Select Sale Type" class="form-control select" data-fouc>
																		<option value="">Select Sale Type</option>
																		<option value="CASH SALE">CASH</option>
																		<option value="CREDIT SALE">CREDIT</option>
																	</select>
																</div>
															</div>
															<div class="col-sm-2 font-weight-600">
																<div class="form-group mb-2">
																	<select id="st_sale_status" name="sale_status" data-placeholder="Select Sale Status" class="form-control select" data-fouc>
																		<option value="">Select Sale Status</option>
																		<option value="Valid">All Valid (Paid, Partially Paid & Unpaid)</option>
																		<option value="Paid">Paid</option>
																		<option value="Partially Paid">Partially Paid</option>
																		<option value="Unpaid">Unpaid</option>
																		<option value="Void">Void</option>
																	</select>
																</div>
															</div>
															<div class="col-sm-2 font-weight-600">
																<div class="form-group mb-2">
																	<select id="st_system_user_id" name="system_user_id" data-placeholder="Select User" class="form-control select" data-fouc>
																		<option value="">Select User</option>
																		<?php foreach ($system_users as $row): ?>
																			<option value="<?php echo $row->system_user_id; ?>"><?php echo $row->first_name . ' ' . $row->last_name . ' (' . $row->email_address . ')'; ?></option>
																		<?php endforeach; ?>
																	</select>
																</div>
															</div>
															<div class="col-sm-2 text-right">
																<button id="btn_sales_summary_filter" type="button" onclick="filter_sales_transactions();" class="btn btn-primary btn-sm  btn-block font-weight-600"><i class="icon-spinner9"></i> Generate Report</button>
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-12 text-right">
															<button id="btn_sales_summary_export" type="button" onclick="export_sales_transactions();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-file-pdf"></i> Export</button>
														</div>
													</div>
													<div id="sales_transactions_report_div" style="min-height: 400px;">

													</div>

												</form>
											</div>

											<div class="tab-pane fade" id="bottom-tab2">
												<form method="post" class="form" id="frm_filter_online_sales_transactions" name="frm_filter_online_sales_transactions" method="post">
													<div class="mb-3">
														<div class="spinner2 display-none" id="online_sales_transactions_loader">
									                        <div class="rect1"></div>
									                        <div class="rect2"></div>
									                        <div class="rect3"></div>
									                    </div>
														<div class="row">
															<div class="col-sm-3 font-weight-600">
																<div class="form-group mb-2">
																	<input type="hidden" id="ost_date_from" name="date_from" value="">
																	<input type="hidden" id="ost_date_to" name="date_to" value="">
																	<div class="input-group">
																		<input type="text" id="ost_date_from_to" class="form-control daterangepicker4" value="2013-01-08 - 2013-01-08">
																		<span class="input-group-append">
																			<span class="input-group-text"><i class="icon-calendar22"></i></span>
																		</span>
																	</div>
																</div>
															</div>
															<div class="col-sm-3 font-weight-600">
																<div class="form-group mb-2">
																	<select id="ost_outlet_id" name="outlet_id" data-placeholder="Select Outlet" class="form-control select" data-fouc>
																		<option value="">Select Outlet</option>
																		<?php foreach ($outlets as $row): ?>
																			<option value="<?php echo $row->outlet_id; ?>"><?php echo $row->outlet_name; ?></option>
																		<?php endforeach; ?>
																	</select>
																</div>
															</div>
															<div class="col-sm-2">
																<button id="btn_online_sales_summary_filter" type="button" onclick="filter_online_sales_transactions();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-spinner9"></i> Generate Report</button>

															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-md-12 text-right">
															<button id="btn_online_sales_export" type="button" onclick="export_online_sales_transactions();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-file-pdf"></i> Export</button>
														</div>
													</div>
													<div id="online_sales_transactions_report_div" style="min-height: 400px;">

													</div>
												</form>
											</div>
										</div>										
									</div>

									<div class="tab-pane fade" id="tab-item-sales">
										<form method="post" class="form" id="frm_filter_item_sales" name="frm_item_sales" method="post">
											<div class="mb-3">
												<div class="spinner2 display-none" id="item_sales_loader">
							                        <div class="rect1"></div>
							                        <div class="rect2"></div>
							                        <div class="rect3"></div>
							                    </div>
												<div class="row">
													<div class="col-sm-2 font-weight-600">
														<div class="form-group mb-2">
															<input type="hidden" id="is_date_from" name="date_from" value="">
															<input type="hidden" id="is_date_to" name="date_to" value="">
															<div class="input-group">
																<input type="text" id="is_date_from_to" class="form-control daterangepicker5" value="2013-01-08 - 2013-01-08">
																<span class="input-group-append">
																	<span class="input-group-text"><i class="icon-calendar22"></i></span>
																</span>
															</div>
														</div>
													</div>
													<div class="col-sm-2 font-weight-600">
														<div class="form-group mb-2">
															<select id="is_outlet_id" name="outlet_id" data-placeholder="Filter by Outlet" class="form-control select" data-fouc>
																<option value="">Filter by Outlet</option>
																<?php foreach ($outlets as $row): ?>
																	<option value="<?php echo $row->outlet_id; ?>"><?php echo $row->outlet_name; ?></option>
																<?php endforeach; ?>
															</select>
														</div>
													</div>
													<div class="col-sm-2 font-weight-600">
														<div class="form-group mb-2">
															<select id="is_product_id" name="product_id" data-placeholder="Filter by Product" class="form-control select" data-fouc>
																<option value="">Filter by Product</option>
																<?php foreach ($products as $row): ?>
																	<option value="<?php echo $row->product_id; ?>"><?php echo $row->product_name; ?></option>
																<?php endforeach; ?>
															</select>
														</div>
													</div>
													<div class="col-sm-2 font-weight-600">
														<div class="form-group mb-2">
															<select id="is_customer_id" name="customer_id" data-placeholder="Filter by Customer" class="form-control select" data-fouc>
																<option value="">Filter by Customer</option>
																<?php foreach ($customers as $row): ?>
																	<option value="<?php echo $row->customer_id; ?>"><?php echo $row->first_name . ' ' . $row->last_name; ?></option>
																<?php endforeach; ?>
															</select>
														</div>
													</div>
													<!-- <div class="col-sm-2 font-weight-600">
														<div class="form-group mb-2">
															<select id="st_sale_status" name="sale_status" data-placeholder="Select Sale Status" class="form-control select" data-fouc>
																<option value="">Select Sale Status</option>
																<option value="Valid">All Valid (Paid, Partially Paid & Unpaid)</option>
																<option value="Paid">Paid</option>
																<option value="Partially Paid">Partially Paid</option>
																<option value="Unpaid">Unpaid</option>
																<option value="Void">Void</option>
															</select>
														</div>
													</div> -->
													<div class="col-sm-2 font-weight-600">
														<div class="form-group mb-2">
															<select id="is_system_user_id" name="system_user_id" data-placeholder="Filter by User" class="form-control select" data-fouc>
																<option value="">Filter by User</option>
																<?php foreach ($system_users as $row): ?>
																	<option value="<?php echo $row->system_user_id; ?>"><?php echo $row->first_name . ' ' . $row->last_name . ' (' . $row->email_address . ')'; ?></option>
																<?php endforeach; ?>
															</select>
														</div>
													</div>
													<div class="col-sm-2 text-right">
														<button id="btn_sales_summary_filter" type="button" onclick="filter_item_sales();" class="btn btn-primary btn-sm  btn-block font-weight-600"><i class="icon-spinner9"></i> Generate Report</button>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12 text-right">
													<button id="btn_item_sales_export" type="button" onclick="export_item_sales();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-file-pdf"></i> Export</button>
												</div>
											</div>
											<div id="item_sales_report_div" style="min-height: 400px;">

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
				        	assign_dates3();
				        }
				    );
				    assign_dates3();

				    $('.daterangepicker4').daterangepicker({
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
				        	assign_dates4();
				        }
				    );
				    assign_dates4();

				    $('.daterangepicker5').daterangepicker({
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
				        	assign_dates5();
				        }
				    );
				    assign_dates5();

				    filter_sales_summary();

				    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
				    	switch ($(e.target).html()) {
				          case 'Sales Summary':
				            filter_sales_summary();
				            break;

				          case 'Sales By Items (Summary)':
				            filter_sales_by_items();
				            break;

				           case 'Sales Transactions':
				            filter_sales_transactions();
				            filter_online_sales_transactions();
				            break;

				            case 'Item Sales':
				            filter_item_sales();
				            break;
				        }
					});
				});
				function assign_dates() {
					$('#ss_date_from').val($('#ss_date_from_to').data('daterangepicker').startDate.format('YYYY-MM-DD'));
				   	$('#ss_date_to').val($('#ss_date_from_to').data('daterangepicker').endDate.format('YYYY-MM-DD'));					
				}
				function assign_dates2() {
					$('#sbi_date_from').val($('#sbi_date_from_to').data('daterangepicker').startDate.format('YYYY-MM-DD'));
				   	$('#sbi_date_to').val($('#sbi_date_from_to').data('daterangepicker').endDate.format('YYYY-MM-DD'));					
				}
				function assign_dates3() {
					$('#st_date_from').val($('#st_date_from_to').data('daterangepicker').startDate.format('YYYY-MM-DD'));
				   	$('#st_date_to').val($('#st_date_from_to').data('daterangepicker').endDate.format('YYYY-MM-DD'));					
				}
				function assign_dates4() {
					$('#ost_date_from').val($('#ost_date_from_to').data('daterangepicker').startDate.format('YYYY-MM-DD'));
				   	$('#ost_date_to').val($('#ost_date_from_to').data('daterangepicker').endDate.format('YYYY-MM-DD'));					
				}
				function assign_dates5() {
					$('#is_date_from').val($('#is_date_from_to').data('daterangepicker').startDate.format('YYYY-MM-DD'));
				   	$('#is_date_to').val($('#is_date_from_to').data('daterangepicker').endDate.format('YYYY-MM-DD'));					
				}
			</script>


	