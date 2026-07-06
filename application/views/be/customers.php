		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<span class="breadcrumb-item active">Customers</span>
						</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content pt-0">
				<div class="row">
					<div class="col-lg-12">
						<div class="card rounded-top-0">
							<div class="card-header bg-transparent header-elements-inline p-2">
								<h5 class="card-title font-weight-bold"><i class="icon-users4"></i> Customers</h5>			
								<div class="header-elements">
									<?php if ($sbr_customers_add == true): ?>
										<a href="<?php echo base_url();?>be/customers/add" class="btn btn-sm btn-primary"><i class="icon-plus-circle2"></i> New Customer</a>
									<?php endif; ?>
								</div>			
							</div>

							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<form method="post" class="form">
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-2 font-weight-600">
														<select id="customers_fl_group_id" name="customers_fl_group_id" class="form-control form-control-select2" data-placeholder="Filter by Customer Group" data-fouc>
															<option value="">Filter by Customer Group</option>
															<?php foreach ($customer_groups as $row): ?>
																<option value="<?php echo $row->customer_group_id; ?>"><?php echo $row->customer_group_name; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
													<div class="col-sm-2">
														<button id="btn_customers_filter" type="button" class="btn btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> FILTER</button>
													</div>
												</div>
											</div>
											<div id="customers_div" style="min-height: 400px;">
									
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-2 font-weight-600">
														<select id="sl_customers_bulk_action" name="sl_customers_bulk_action" class="form-control form-control-select2" data-placeholder="Bulk Action" data-fouc>
															<option value="">Bulk Action</option>
															<option value="Edit">Edit</option>
															<option value="Delete">Delete</option>
														</select>
													</div>
													<div class="col-sm-2">
														<button id="btn_customers_bulk_action" type="button" class="btn btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> APPLY</button>
													</div>
												</div>
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
				load_customers();
			</script>
	