		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="#" class="breadcrumb-item">Reports</a>
							<span class="breadcrumb-item active">Customer Reports</span>
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
								<h5 class="card-title font-weight-600"><i class="icon-file-presentation2 mr-1"></i> Customer Reports</h5>			
							</div>
							<div class="card-body">

								
								<ul class="nav nav-tabs nav-tabs-solid rounded bg-light font-14">
									<li class="nav-item"><a href="#tab-credit-list-report" class="nav-link rounded-left active" data-toggle="tab">Credit List Report</a></li>
									<li class="nav-item"><a href="#tab-aging-report" class="nav-link" data-toggle="tab">Customer Aging Report</a></li>
									<!-- <li class="nav-item"><a href="#tab-aging-report" class="nav-link" data-toggle="tab">Sales Transactions</a></li> -->
								</ul>

								<div class="tab-content">
									<!-- <div class="tab-pane fade show active" id="tab-sales-summary">
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
									</div> -->

									<div class="tab-pane fade show active" id="tab-credit-list-report">
										<form method="post" class="form" id="frm_filter_credit_list_report" name="frm_filter_credit_list_report" method="post">
											<div class="mb-3">
												<div class="spinner2 display-none" id="credit_list_report_loader">
							                        <div class="rect1"></div>
							                        <div class="rect2"></div>
							                        <div class="rect3"></div>
							                    </div>
												<div class="row">													
													<div class="col-sm-2 font-weight-600">
														<div class="form-group mb-2">
															<select id="clr_customer_id" name="customer_id" data-placeholder="Filter by Customer" class="form-control select" data-fouc>
																<option value="">Filter by Customer</option>
																<?php foreach ($customers as $row): ?>
																	<option value="<?php echo $row->customer_id; ?>"><?php echo $row->first_name . ' ' . $row->last_name; ?></option>
																<?php endforeach; ?>
															</select>
														</div>
													</div>
													<div class="col-sm-2 font-weight-600">
														<div class="form-group mb-2">
															<select id="clr_system_user_id" name="system_user_id" data-placeholder="Filter by User" class="form-control select" data-fouc>
																<option value="">Filter by User</option>
																<?php foreach ($system_users as $row): ?>
																	<option value="<?php echo $row->system_user_id; ?>"><?php echo $row->first_name . ' ' . $row->last_name; ?></option>
																<?php endforeach; ?>
															</select>
														</div>
													</div>
													<div class="col-sm-2">
														<div class="form-group mb-2 pt-1">
															<div class="custom-control custom-control-left custom-checkbox custom-control-inline">
																<input type="checkbox" class="custom-control-input" id="clr_chk_cash_sales" name="chk_cash_sales">
																<label class="custom-control-label" for="clr_chk_cash_sales">Include Pending Cash Sales</label>
															</div>
														</div>
													</div>
													<div class="col-sm-2">
														<button id="btn_sales_summary_filter" type="button" onclick="filter_credit_list_report();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-spinner9"></i> Generate Report</button>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12 text-right">
													<button id="btn_credit_list_report_export" type="button" onclick="export_credit_list_report();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-file-pdf"></i> Export</button>
												</div>
											</div>
											<div id="credit_list_report_div" style="min-height: 400px;">

											</div>
										</form>
									</div>

									<div class="tab-pane fade" id="tab-aging-report">
										<form method="post" class="form" id="frm_filter_customer_aging_report" name="frm_filter_customer_aging_report" method="post">
											<div class="mb-3">
												<div class="spinner2 display-none" id="customer_aging_report_loader">
							                        <div class="rect1"></div>
							                        <div class="rect2"></div>
							                        <div class="rect3"></div>
							                    </div>
												<div class="row">
													<div class="col-sm-2 pt-1 text-right font-weight-bold">
														<p>Aging Report as of:</p>
													</div>
													<div class="col-sm-2 font-weight-600">
														<div class="form-group mb-2">
															<input type="text" id="car_date" name="car_date" class="form-control pickadate" value="<?php echo date('Y-m-d'); ?>">
														</div>
													</div>													
													<div class="col-sm-2">
														<button id="btn_sales_summary_filter" type="button" onclick="filter_customer_aging_report();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-spinner9"></i> Generate Report</button>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12 text-right">
													<button id="btn_sales_summary_export" type="button" onclick="export_customer_aging_report();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-file-pdf"></i> Export</button>
												</div>
											</div>
											<div id="customer_aging_report_div" style="min-height: 400px;">

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
					

				    filter_credit_list_report();
				    filter_customer_aging_report();

				 //    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
				 //    	switch ($(e.target).html()) {
				 //          case 'Sales Summary':
				 //            filter_sales_summary();
				 //            break;

				 //          case 'Sales By Items':
				 //            filter_credit_list_report();
				 //            break;

				 //           case 'Sales Transactions':
				 //            filter_customer_aging_report();
				 //            filter_online_customer_aging_report();
				 //            break;
				 //        }
					// });
				});
				
			</script>


	