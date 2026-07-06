		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="#" class="breadcrumb-item">Reports</a>
							<span class="breadcrumb-item active">Expenses Report</span>
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
								<h5 class="card-title font-weight-600"><i class="icon-graph mr-1"></i> Expenses Report</h5>			
							</div>
							<div class="card-body">
								<form method="post" class="form" id="frm_filter_expenses_report" name="frm_filter_expenses_report" method="post">									
									<div class="spinner2 display-none" id="expenses_report_loader">
				                        <div class="rect1"></div>
				                        <div class="rect2"></div>
				                        <div class="rect3"></div>
				                    </div>
				                    <div class="row">
										<div class="col-sm-2 font-weight-600">
											<div class="form-group mb-2">
												<input type="hidden" id="er_date_from" name="date_from" value="">
												<input type="hidden" id="er_date_to" name="date_to" value="">
												<div class="input-group">
													<input type="text" id="er_date_from_to" class="form-control daterangepicker" value="2013-01-08 - 2013-01-08">
													<span class="input-group-append">
														<span class="input-group-text"><i class="icon-calendar22"></i></span>
													</span>
												</div>
											</div>
										</div>
										<div class="col-sm-2 font-weight-600">
											<div class="form-group mb-2">
												<select id="er_outlet_id" name="outlet_id" data-placeholder="Select Outlet" class="form-control select" data-fouc>
													<option value="">Select Outlet</option>
													<?php foreach ($outlets as $row): ?>
														<option value="<?php echo $row->outlet_id; ?>"><?php echo $row->outlet_name; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
										<div class="col-sm-2 font-weight-600">
											<div class="form-group mb-2">
												<select id="er_system_user_id" name="system_user_id" data-placeholder="Select User" class="form-control select" data-fouc>
													<option value="">Select User</option>
													<?php foreach ($system_users as $row): ?>
														<option value="<?php echo $row->system_user_id; ?>"><?php echo $row->first_name . ' ' . $row->last_name . ' (' . $row->email_address . ')'; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
										<div class="col-sm-2 font-weight-600">
											<div class="form-group mb-2">
												<select id="er_status" name="status" data-placeholder="Select Status" class="form-control select" data-fouc>
													<option value="">Select Status</option>
													<option value="0">Active</option>
													<option value="1">Void</option>
												</select>
											</div>
										</div>
										<div class="col-sm-2">
											<button id="btn_expenses_report_filter" type="button" onclick="filter_expenses_report();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-spinner9"></i> Generate Report</button>
										</div>
									</div>
									<div class="row">
										<div class="col-md-12 text-right">
											<button id="btn_sales_summary_export" type="button" onclick="export_expenses_report();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-file-pdf"></i> Export</button>
										</div>
									</div>
									<div id="expenses_report_div" style="min-height: 400px;">
										
									</div>
								</form>
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

				    filter_expenses_report();

				});

				function assign_dates() {
					$('#er_date_from').val($('#er_date_from_to').data('daterangepicker').startDate.format('YYYY-MM-DD'));
				   	$('#er_date_to').val($('#er_date_from_to').data('daterangepicker').endDate.format('YYYY-MM-DD'));					
				}
			</script>


	