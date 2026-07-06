		<!-- Main content -->
		<div class="content-wrapper">

			<?php if (!isset($system_user)): ?>

				<!-- Page header -->
				<div class="page-header">
					<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
						<div class="d-flex">
							<div class="breadcrumb">
								<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
								<a href="#" class="breadcrumb-item">User Management</a>
								<a href="<?php echo base_url();?>be/system_users" class="breadcrumb-item">System Users</a>
								<span class="breadcrumb-item active">New System User</span>
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
							<div class="mb-3">
								<h5 class="mb-0 font-weight-semibold">
									<i class="icon-plus-circle2"></i> NEW SYSTEM USER
									<a href="<?php echo base_url();?>be/system_users" class="btn btn-sm bg-success-400 text-success-400 border-success-400 float-right"><i class="icon-arrow-left15"></i> Back to System Users</a>
								</h5>
							</div>
						</div>
					</div>

					<form id="frm_add_system_user" name="frm_add_system_user" method="post" onsubmit="return save_system_user();" autocomplete="false">

						<div id="div_add_error" class="alert alert-danger display-none font-13"></div>
           				<div id="div_add_success" class="alert alert-success display-none font-13"></div>

						<div class="row">
							<div class="col-md-4">
								<div class="card rounded-top-0">
									<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
										<h6 class="card-title font-weight-600 text-success text-uppercase">User Information</h6>			
									</div>
							
									<div class="card-body">

										<div class="form-group mb-2">
											<div class="row">
												<div class="col-md-6">
													<label>First Name <span class="error">*</span></label>
													<input id="first_name" name="first_name" type="text" placeholder="" class="form-control form-control-lg font-weight-bold">
												</div>
												<div class="col-md-6">
													<label>Last Name <span class="error">*</span></label>
													<input id="last_name" name="last_name" type="text" placeholder="" class="form-control form-control-lg font-weight-bold">
												</div>
											</div>
										</div>
										<div class="form-group mb-2">
											<div class="row">
												<div class="col-md-6">
													<label>Email Address <span class="error">*</span></label>
													<input id="email_address" name="email_address" type="email" placeholder="" class="form-control">
												</div>
												<div class="col-md-6">
													<label>Phone Number <span class="error">*</span></label>
													<input id="phone_number" name="phone_number" type="text" placeholder="" class="form-control">
												</div>
											</div>
										</div>
										<div class="form-group mb-2">
											<div class="row">
												<div class="col-md-6">
													<label>User Role <span class="error">*</span></label>
													<select id="user_role_id" name="user_role_id" class="form-control form-control-select2" data-placeholder="Select User Role" data-fouc>
														<option value="">Select User Role</option>
														<?php foreach ($user_roles as $row2): ?>
															<option value="<?php echo $row2->user_role_id; ?>"><?php echo $row2->user_role_name; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-md-6">
													<label>Address</label>
													<input id="address" name="address" type="text" placeholder="" class="form-control">
												</div>
											</div>
										</div>
										<div class="form-group mb-2">
											<div class="row">
												<div class="col-md-6">
													<label>Gender</label>
													<select id="gender" name="gender" class="form-control form-control-select2" data-placeholder="Select Gender" data-fouc>
														<option value="">Select Gender</option>
														<option value="Female">Female</option>
														<option value="Male">Male</option>
													</select>
												</div>
												<div class="col-md-6">
													<label>Birth Date</label>
													<input id="birth_date" name="birth_date" type="text" placeholder="" class="form-control pickadatemax" autocomplete="off">
												</div>
											</div>
										</div>
										<div class="form-group mb-2">
											<div class="row">
												<div class="col-md-6">
													<label>Password <span class="error">*</span></label>
													<input id="password" name="password" type="password" placeholder="" class="form-control">
												</div>
												<div class="col-md-6">
													<label>Confirm Password <span class="error">*</span></label>
													<input id="confirm_password" name="confirm_password" type="password" placeholder="" class="form-control">
												</div>
											</div>
										</div>
										<div class="form-group mb-2">
											<div class="row">										
												<div class="col-sm-8">
													<label class="d-block">Status <span class="error">*</span></label>
													<div class="form-check form-check-inline form-check-right">
														<label class="form-check-label font-weight-semibold">
															Active
															<input type="radio" class="form-check-input" id="is_active_1" name="is_active" value="1" checked>
														</label>
													</div>

													<div class="form-check form-check-inline form-check-right">
														<label class="form-check-label font-weight-semibold">
															Inactive
															<input type="radio" class="form-check-input" id="is_active_0" name="is_active" value="0">
														</label>
													</div>
												</div>
												<div class="col-sm-4">
													<label>Sort Key <span class="error">*</span></label>
													<input id="add_sort_key" name="sort_key" type="number" class="form-control" min="0" value="0">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-8">
								<div class="card rounded-top-0">
									<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
										<h6 class="card-title font-weight-600 text-success text-uppercase">User Permissions &amp; Access</h6>			
									</div>
							
									<div class="card-body">
										<div class="row">
											<div class="col-md-6">
												<div class="table-responsive">
													<table class="table table-bordered table-lg">
														<tbody>
															<!-- Store Information -->
															<tr class="table-active">
																<th rowspan="3" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_store_information" name="sbr_store_information" type="checkbox" class="chk_sbr_overall form-check-input"> Store Information
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_store_information_view" name="sbr_store_information_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_store_information_edit" name="sbr_store_information_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Outlets -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_outlets" name="sbr_outlets" type="checkbox" class="chk_sbr_overall form-check-input"> Outlets
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_outlets_view" name="sbr_outlets_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_outlets_add" name="sbr_outlets_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_outlets_edit" name="sbr_outlets_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_outlets_delete" name="sbr_outlets_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Countries -->
															<tr class="table-active">
																<th rowspan="6" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_countries" name="sbr_countries" type="checkbox" class="chk_sbr_overall form-check-input"> Countries
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_countries_view" name="sbr_countries_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_countries_add" name="sbr_countries_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_countries_edit" name="sbr_countries_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_countries_delete" name="sbr_countries_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_countries_manage" name="sbr_countries_manage" type="checkbox" class="form-check-input"> Manage (Regions)
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Regions -->
															<tr class="table-active">
																<th rowspan="4" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_regions" name="sbr_regions" type="checkbox" class="chk_sbr_overall form-check-input"> Regions
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_regions_add" name="sbr_regions_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_regions_edit" name="sbr_regions_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_regions_delete" name="sbr_regions_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Shipping Zones -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_shipping_zones" name="sbr_shipping_zones" type="checkbox" class="chk_sbr_overall form-check-input"> Shipping Zones
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_shipping_zones_view" name="sbr_shipping_zones_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_shipping_zones_add" name="sbr_shipping_zones_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_shipping_zones_edit" name="sbr_shipping_zones_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_shipping_zones_delete" name="sbr_shipping_zones_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Pickup Locations -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_pickup_locations" name="sbr_pickup_locations" type="checkbox" class="chk_sbr_overall form-check-input"> Pickup Locations
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_pickup_locations_view" name="sbr_pickup_locations_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_pickup_locations_add" name="sbr_pickup_locations_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_pickup_locations_edit" name="sbr_pickup_locations_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_pickup_locations_delete" name="sbr_pickup_locations_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Currencies -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_currencies" name="sbr_currencies" type="checkbox" class="chk_sbr_overall form-check-input"> Currencies
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_currencies_view" name="sbr_currencies_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_currencies_add" name="sbr_currencies_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_currencies_edit" name="sbr_currencies_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_currencies_delete" name="sbr_currencies_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Banks -->
															<tr class="table-active">
																<th rowspan="6" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_banks" name="sbr_banks" type="checkbox" class="chk_sbr_overall form-check-input"> Banks
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_banks_view" name="sbr_banks_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_banks_add" name="sbr_banks_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_banks_edit" name="sbr_banks_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_banks_delete" name="sbr_banks_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_banks_manage" name="sbr_banks_manage" type="checkbox" class="form-check-input"> Manage (Branches)
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Bank Branches -->
															<tr class="table-active">
																<th rowspan="4" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_bank_branches" name="sbr_bank_branches" type="checkbox" class="chk_sbr_overall form-check-input"> Bank Branches
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_bank_branches_add" name="sbr_bank_branches_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_bank_branches_edit" name="sbr_bank_branches_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_bank_branches_delete" name="sbr_bank_branches_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Tax Rates -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_tax_rates" name="sbr_tax_rates" type="checkbox" class="chk_sbr_overall form-check-input"> Tax Rates
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_tax_rates_view" name="sbr_tax_rates_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_tax_rates_add" name="sbr_tax_rates_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_tax_rates_edit" name="sbr_tax_rates_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_tax_rates_delete" name="sbr_tax_rates_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Credit Terms -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_credit_terms" name="sbr_credit_terms" type="checkbox" class="chk_sbr_overall form-check-input"> Credit Terms
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_credit_terms_view" name="sbr_credit_terms_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_credit_terms_add" name="sbr_credit_terms_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_credit_terms_edit" name="sbr_credit_terms_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_credit_terms_delete" name="sbr_credit_terms_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Mpesa Settings -->
															<tr class="table-active">
																<th rowspan="3" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_mpesa_settings" name="sbr_mpesa_settings" type="checkbox" class="chk_sbr_overall form-check-input"> Mpesa Settings
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_mpesa_settings_view" name="sbr_mpesa_settings_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_mpesa_settings_edit" name="sbr_mpesa_settings_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Pesapal Settings -->
															<tr class="table-active">
																<th rowspan="3" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_pesapal_settings" name="sbr_pesapal_settings" type="checkbox" class="chk_sbr_overall form-check-input"> Pesapal Settings
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_pesapal_settings_view" name="sbr_pesapal_settings_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_pesapal_settings_edit" name="sbr_pesapal_settings_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Email Accounts -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_email_accounts" name="sbr_email_accounts" type="checkbox" class="chk_sbr_overall form-check-input"> Email Accounts
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_email_accounts_view" name="sbr_email_accounts_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_email_accounts_add" name="sbr_email_accounts_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_email_accounts_edit" name="sbr_email_accounts_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_email_accounts_delete" name="sbr_email_accounts_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Bulk SMS Settings -->
															<tr class="table-active">
																<th rowspan="3" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_bulk_sms_settings" name="sbr_bulk_sms_settings" type="checkbox" class="chk_sbr_overall form-check-input"> Bulk SMS Settings
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_bulk_sms_settings_view" name="sbr_bulk_sms_settings_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_bulk_sms_settings_edit" name="sbr_bulk_sms_settings_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Prefixes -->
															<tr class="table-active">
																<th rowspan="3" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_prefixes" name="sbr_prefixes" type="checkbox" class="chk_sbr_overall form-check-input"> Prefixes
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_prefixes_view" name="sbr_prefixes_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_prefixes_edit" name="sbr_prefixes_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Void Reasons -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_void_reasons" name="sbr_void_reasons" type="checkbox" class="chk_sbr_overall form-check-input"> Void Reasons
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_void_reasons_view" name="sbr_void_reasons_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_void_reasons_add" name="sbr_void_reasons_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_void_reasons_edit" name="sbr_void_reasons_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_void_reasons_delete" name="sbr_void_reasons_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Bitly Settings -->
															<tr class="table-active">
																<th rowspan="3" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_bitly_settings" name="sbr_bitly_settings" type="checkbox" class="chk_sbr_overall form-check-input"> Bitly Settings
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_bitly_settings_view" name="sbr_bitly_settings_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_bitly_settings_edit" name="sbr_bitly_settings_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Products -->
															<tr class="table-active">
																<th rowspan="6" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_products" name="sbr_products" type="checkbox" class="chk_sbr_overall form-check-input"> Products
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_products_list" name="sbr_products_list" type="checkbox" class="form-check-input"> List
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_products_view" name="sbr_products_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_products_add" name="sbr_products_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_products_edit" name="sbr_products_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_products_delete" name="sbr_products_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Product Categories -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_product_categories" name="sbr_product_categories" type="checkbox" class="chk_sbr_overall form-check-input"> Product Categories
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_product_categories_view" name="sbr_product_categories_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_product_categories_add" name="sbr_product_categories_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_product_categories_edit" name="sbr_product_categories_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_product_categories_delete" name="sbr_product_categories_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Units of Measure -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_units_of_measure" name="sbr_units_of_measure" type="checkbox" class="chk_sbr_overall form-check-input"> Units of Measure
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_units_of_measure_view" name="sbr_units_of_measure_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_units_of_measure_add" name="sbr_units_of_measure_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_units_of_measure_edit" name="sbr_units_of_measure_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_units_of_measure_delete" name="sbr_units_of_measure_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Product Sizes -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_product_sizes" name="sbr_product_sizes" type="checkbox" class="chk_sbr_overall form-check-input"> Product Sizes
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_product_sizes_view" name="sbr_product_sizes_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_product_sizes_add" name="sbr_product_sizes_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_product_sizes_edit" name="sbr_product_sizes_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_product_sizes_delete" name="sbr_product_sizes_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Product Categories -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_brands" name="sbr_brands" type="checkbox" class="chk_sbr_overall form-check-input"> Brands
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_brands_view" name="sbr_brands_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_brands_add" name="sbr_brands_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_brands_edit" name="sbr_brands_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_brands_delete" name="sbr_brands_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Purchase Orders -->
															<tr class="table-active">
																<th rowspan="7" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_purchase_orders" name="sbr_purchase_orders" type="checkbox" class="chk_sbr_overall form-check-input"> Purchase Orders
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_purchase_orders_list" name="sbr_purchase_orders_list" type="checkbox" class="form-check-input"> List
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_purchase_orders_view" name="sbr_purchase_orders_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_purchase_orders_add" name="sbr_purchase_orders_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_purchase_orders_edit" name="sbr_purchase_orders_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_purchase_orders_void" name="sbr_purchase_orders_void" type="checkbox" class="form-check-input"> Void
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_purchase_orders_print" name="sbr_purchase_orders_print" type="checkbox" class="form-check-input"> Print
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Goods Receiived -->
															<tr class="table-active">
																<th rowspan="7" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_goods_received" name="sbr_goods_received" type="checkbox" class="chk_sbr_overall form-check-input"> Goods Received
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_goods_received_list" name="sbr_goods_received_list" type="checkbox" class="form-check-input"> List
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_goods_received_view" name="sbr_goods_received_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_goods_received_add" name="sbr_goods_received_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_goods_received_edit" name="sbr_goods_received_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_goods_received_void" name="sbr_goods_received_void" type="checkbox" class="form-check-input"> Void
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_goods_received_print" name="sbr_goods_received_print" type="checkbox" class="form-check-input"> Print
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Stock Adjustments -->
															<tr class="table-active">
																<th rowspan="7" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_stock_adjustments" name="sbr_stock_adjustments" type="checkbox" class="chk_sbr_overall form-check-input"> Stock Adjustments
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_stock_adjustments_list" name="sbr_stock_adjustments_list" type="checkbox" class="form-check-input"> List
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_stock_adjustments_view" name="sbr_stock_adjustments_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_stock_adjustments_add" name="sbr_stock_adjustments_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_stock_adjustments_edit" name="sbr_stock_adjustments_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_stock_adjustments_void" name="sbr_stock_adjustments_void" type="checkbox" class="form-check-input"> Void
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_stock_adjustments_print" name="sbr_stock_adjustments_print" type="checkbox" class="form-check-input"> Print
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Goods Returned -->
															<tr class="table-active">
																<th rowspan="7" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_goods_returned" name="sbr_goods_returned" type="checkbox" class="chk_sbr_overall form-check-input"> Goods Returned
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_goods_returned_list" name="sbr_goods_returned_list" type="checkbox" class="form-check-input"> List
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_goods_returned_view" name="sbr_goods_returned_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_goods_returned_add" name="sbr_goods_returned_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_goods_returned_edit" name="sbr_goods_returned_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_goods_returned_void" name="sbr_goods_returned_void" type="checkbox" class="form-check-input"> Void
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_goods_returned_print" name="sbr_goods_returned_print" type="checkbox" class="form-check-input"> Print
																		</label>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
											<div class="col-md-6">
												<div class="table-responsive">
													<table class="table table-bordered table-lg">
														<tbody>
															<!-- Stock Transfers -->
															<tr class="table-active">
																<th rowspan="7" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_stock_transfers" name="sbr_stock_transfers" type="checkbox" class="chk_sbr_overall form-check-input"> Stock Transfers
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_stock_transfers_list" name="sbr_stock_transfers_list" type="checkbox" class="form-check-input"> List
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_stock_transfers_view" name="sbr_stock_transfers_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_stock_transfers_add" name="sbr_stock_transfers_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_stock_transfers_edit" name="sbr_stock_transfers_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_stock_transfers_void" name="sbr_stock_transfers_void" type="checkbox" class="form-check-input"> Void
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_stock_transfers_print" name="sbr_stock_transfers_print" type="checkbox" class="form-check-input"> Print
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Online Sales -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_online_sales" name="sbr_online_sales" type="checkbox" class="chk_sbr_overall form-check-input"> Online Sales
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_online_sales_list" name="sbr_online_sales_list" type="checkbox" class="form-check-input"> List
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_online_sales_view" name="sbr_online_sales_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_online_sales_view" name="sbr_online_sales_view" type="checkbox" class="form-check-input"> Manage
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_online_sales_print" name="sbr_online_sales_print" type="checkbox" class="form-check-input"> Print
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Payments -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_payments" name="sbr_payments" type="checkbox" class="chk_sbr_overall form-check-input"> Payments
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_payments_list" name="sbr_payments_list" type="checkbox" class="form-check-input"> List
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_payments_view" name="sbr_payments_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_payments_view" name="sbr_payments_view" type="checkbox" class="form-check-input"> Manage
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_payments_print" name="sbr_payments_print" type="checkbox" class="form-check-input"> Print
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Customers -->
															<tr class="table-active">
																<th rowspan="6" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_customers" name="sbr_customers" type="checkbox" class="chk_sbr_overall form-check-input"> Customers
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_customers_list" name="sbr_customers_list" type="checkbox" class="form-check-input"> List
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_customers_view" name="sbr_customers_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_customers_add" name="sbr_customers_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_customers_edit" name="sbr_customers_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_customers_delete" name="sbr_customers_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Customer Groups -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_customer_groups" name="sbr_customer_groups" type="checkbox" class="chk_sbr_overall form-check-input"> Customer Groups
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_customer_groups_view" name="sbr_customer_groups_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_customer_groups_add" name="sbr_customer_groups_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_customer_groups_edit" name="sbr_customer_groups_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_customer_groups_delete" name="sbr_customer_groups_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Suppliers -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_suppliers" name="sbr_suppliers" type="checkbox" class="chk_sbr_overall form-check-input"> Suppliers
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_suppliers_view" name="sbr_suppliers_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_suppliers_add" name="sbr_suppliers_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_suppliers_edit" name="sbr_suppliers_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_suppliers_delete" name="sbr_suppliers_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Affiliate Accounts -->
															<tr class="table-active">
																<th rowspan="4" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_affiliate_accounts" name="sbr_affiliate_accounts" type="checkbox" class="chk_sbr_overall form-check-input"> Affiliate Accounts
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_affiliate_accounts_list" name="sbr_affiliate_accounts_list" type="checkbox" class="form-check-input"> List
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_affiliate_accounts_view" name="sbr_affiliate_accounts_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_affiliate_accounts_manage" name="sbr_affiliate_accounts_manage" type="checkbox" class="form-check-input"> Manage
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Affiliate Packages -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_affiliate_packages" name="sbr_affiliate_packages" type="checkbox" class="chk_sbr_overall form-check-input"> Affiliate Packages
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_affiliate_packages_view" name="sbr_affiliate_packages_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_affiliate_packages_add" name="sbr_affiliate_packages_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_affiliate_packages_edit" name="sbr_affiliate_packages_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_affiliate_packages_delete" name="sbr_affiliate_packages_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Affiliate Terms -->
															<tr class="table-active">
																<th rowspan="3" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_affiliate_terms" name="sbr_affiliate_terms" type="checkbox" class="chk_sbr_overall form-check-input"> Affiliate Terms
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_affiliate_terms_view" name="sbr_affiliate_terms_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_affiliate_terms_edit" name="sbr_affiliate_terms_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Promo Codes -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_promo_codes" name="sbr_promo_codes" type="checkbox" class="chk_sbr_overall form-check-input"> Promo Codes
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_promo_codes_view" name="sbr_promo_codes_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_promo_codes_add" name="sbr_promo_codes_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_promo_codes_edit" name="sbr_promo_codes_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_promo_codes_delete" name="sbr_promo_codes_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Home Sliders -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_home_sliders" name="sbr_home_sliders" type="checkbox" class="chk_sbr_overall form-check-input"> Home Sliders
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_home_sliders_view" name="sbr_home_sliders_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_home_sliders_add" name="sbr_home_sliders_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_home_sliders_edit" name="sbr_home_sliders_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_home_sliders_delete" name="sbr_home_sliders_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Top Categories -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_top_categories" name="sbr_top_categories" type="checkbox" class="chk_sbr_overall form-check-input"> Top Categories
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_top_categories_view" name="sbr_top_categories_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_top_categories_add" name="sbr_top_categories_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_top_categories_edit" name="sbr_top_categories_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_top_categories_delete" name="sbr_top_categories_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Featured Categories -->
															<tr class="table-active">
																<th rowspan="3" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_featured_categories" name="sbr_featured_categories" type="checkbox" class="chk_sbr_overall form-check-input"> Featured Categories
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_featured_categories_view" name="sbr_featured_categories_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_featured_categories_edit" name="sbr_featured_categories_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Promo Banners -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_promo_banners" name="sbr_promo_banners" type="checkbox" class="chk_sbr_overall form-check-input"> Promo Banners
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_promo_banners_view" name="sbr_promo_banners_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_promo_banners_add" name="sbr_promo_banners_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_promo_banners_edit" name="sbr_promo_banners_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_promo_banners_delete" name="sbr_promo_banners_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Testimonials -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_testimonials" name="sbr_testimonials" type="checkbox" class="chk_sbr_overall form-check-input"> Testimonials
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_testimonials_view" name="sbr_testimonials_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_testimonials_add" name="sbr_testimonials_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_testimonials_edit" name="sbr_testimonials_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_testimonials_delete" name="sbr_testimonials_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- About Us -->
															<tr class="table-active">
																<th rowspan="3" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_about_us" name="sbr_about_us" type="checkbox" class="chk_sbr_overall form-check-input"> About Us
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_about_us_view" name="sbr_about_us_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_about_us_edit" name="sbr_about_us_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>

															<!-- How To Shop -->
															<tr class="table-active">
																<th rowspan="3" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_how_to_shop" name="sbr_how_to_shop" type="checkbox" class="chk_sbr_overall form-check-input"> How To Shop
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_how_to_shop_view" name="sbr_how_to_shop_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_how_to_shop_edit" name="sbr_how_to_shop_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>

															<!-- FAQs -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_faqs" name="sbr_faqs" type="checkbox" class="chk_sbr_overall form-check-input"> FAQs
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_faqs_view" name="sbr_faqs_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_faqs_add" name="sbr_faqs_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_faqs_edit" name="sbr_faqs_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_faqs_delete" name="sbr_faqs_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Return Policy -->
															<tr class="table-active">
																<th rowspan="3" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_return_policy" name="sbr_return_policy" type="checkbox" class="chk_sbr_overall form-check-input"> Return Policy
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_return_policy_view" name="sbr_return_policy_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_return_policy_edit" name="sbr_return_policy_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Privacy Policy -->
															<tr class="table-active">
																<th rowspan="3" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_privacy_policy" name="sbr_privacy_policy" type="checkbox" class="chk_sbr_overall form-check-input"> Privacy Policy
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_privacy_policy_view" name="sbr_privacy_policy_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_privacy_policy_edit" name="sbr_privacy_policy_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Terms and Conditions -->
															<tr class="table-active">
																<th rowspan="3" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_terms_and_conditions" name="sbr_terms_and_conditions" type="checkbox" class="chk_sbr_overall form-check-input"> Terms and Conditions
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_terms_and_conditions_view" name="sbr_terms_and_conditions_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_terms_and_conditions_edit" name="sbr_terms_and_conditions_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Blog -->
															<tr class="table-active">
																<th rowspan="6" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_blog" name="sbr_blog" type="checkbox" class="chk_sbr_overall form-check-input"> Blog
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_blog_list" name="sbr_blog_list" type="checkbox" class="form-check-input"> List
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_blog_view" name="sbr_blog_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_blog_add" name="sbr_blog_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_blog_edit" name="sbr_blog_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_blog_delete" name="sbr_blog_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Blog Categories -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_blog_categories" name="sbr_blog_categories" type="checkbox" class="chk_sbr_overall form-check-input"> Blog Categories
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_blog_categories_view" name="sbr_blog_categories_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_blog_categories_add" name="sbr_blog_categories_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_blog_categories_edit" name="sbr_blog_categories_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_blog_categories_delete" name="sbr_blog_categories_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- System Users -->
															<tr class="table-active">
																<th rowspan="6" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_system_users" name="sbr_system_users" type="checkbox" class="chk_sbr_overall form-check-input"> System Users
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_system_users_list" name="sbr_system_users_list" type="checkbox" class="form-check-input"> List
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_system_users_view" name="sbr_system_users_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_system_users_add" name="sbr_system_users_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_system_users_edit" name="sbr_system_users_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_system_users_delete" name="sbr_system_users_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- User Roles -->
															<tr class="table-active">
																<th rowspan="5" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_user_roles" name="sbr_user_roles" type="checkbox" class="chk_sbr_overall form-check-input"> User Roles
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_user_roles_view" name="sbr_user_roles_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_user_roles_add" name="sbr_user_roles_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_user_roles_edit" name="sbr_user_roles_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_user_roles_delete" name="sbr_user_roles_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>

															<!-- Departments -->
															<tr class="table-active">
																<th rowspan="6" class="font-weight-semibold font-14">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold text-secondary">
																			<input id="sbr_departments" name="sbr_departments" type="checkbox" class="chk_sbr_overall form-check-input"> Departments
																		</label>
																	</div>
																</th>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_departments_view" name="sbr_departments_view" type="checkbox" class="form-check-input"> View
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_departments_add" name="sbr_departments_add" type="checkbox" class="form-check-input"> Add
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_departments_edit" name="sbr_departments_edit" type="checkbox" class="form-check-input"> Edit
																		</label>
																	</div>
																</td>
															</tr>
															<tr>
																<td>
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_departments_delete" name="sbr_departments_delete" type="checkbox" class="form-check-input"> Delete
																		</label>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
								
							</div>

							<div class="clearfix"></div>
							<div class="col-md-12">
								<hr>
								<div class="text-right">
									<button type="submit" id="btn_add_system_user" class="btn btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> SAVE SYSTEM USER</button>
								</div>
							</div>
						</div>
					</form>
				</div>

			<?php else: ?>
				<?php foreach ($system_user as $row): ?>
					<!-- Page header -->
					<div class="page-header">
						<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
							<div class="d-flex">
								<div class="breadcrumb">
									<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
									<a href="<?php echo base_url();?>be/system_users" class="breadcrumb-item">system_users</a>
									<span class="breadcrumb-item active">Edit system_user (<?php echo $row->first_name . ' ' . $row->last_name; ?>)</span>
								</div>

								<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
							</div>
						</div>
					</div>
					<!-- /page header -->


					<div class="content pt-0">
						<div class="row">
							<div class="col-md-12">
								<div class="mb-3">
									<h5 class="mb-0 font-weight-semibold">
										<i class="icon-pencil6"></i> Edit system_user (<?php echo $row->first_name . ' ' . $row->last_name; ?>)
										<a href="<?php echo base_url();?>be/system_users" class="btn btn-sm bg-success-400 text-success-400 border-success-400 float-right"><i class="icon-arrow-left15"></i> Back to system_users</a>
									</h5>
								</div>
							</div>
						</div>

						<form id="frm_edit_system_user" name="frm_edit_system_user" method="post" onsubmit="return update_system_user();" autocomplete="false">

							<div id="div_edit_error" class="alert alert-danger display-none font-13"></div>
               				<div id="div_edit_success" class="alert alert-success display-none font-13"></div>

							<div class="row">

								<div class="col-md-4">
									<div class="card rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h6 class="card-title font-weight-600 text-success text-uppercase">Contact Information</h6>			
										</div>
								
										<div class="card-body">

			                   				<input type="hidden" id="system_user_id" name="system_user_id" value="<?php echo $row->system_user_id; ?>">

											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-6">
														<label>First Name <span class="error">*</span></label>
														<input id="first_name" name="first_name" type="text" placeholder="" class="form-control form-control-lg font-weight-bold" value="<?php echo $row->first_name; ?>">
													</div>
													<div class="col-md-6">
														<label>Last Name <span class="error">*</span></label>
														<input id="last_name" name="last_name" type="text" placeholder="" class="form-control form-control-lg font-weight-bold" value="<?php echo $row->last_name; ?>">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-6">
														<label>Email Address <span class="error">*</span></label>
														<input id="email_address" name="email_address" type="email" placeholder="" class="form-control" value="<?php echo $row->email_address; ?>">
													</div>
													<div class="col-md-6">
														<label>Phone Number <span class="error">*</span></label>
														<input id="phone_number" name="phone_number" type="text" placeholder="" class="form-control" value="<?php echo $row->phone_number; ?>">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-6">
														<label>User Role</label>
														<select id="user_role_id" name="user_role_id" class="form-control form-control-select2" data-placeholder="Select User Role" data-fouc>
															<option value="">Select User Role</option>
															<?php foreach ($user_roles as $row2): ?>
																<option value="<?php echo $row2->user_role_id; ?>" <?php if ($row2->user_role_id == $row->user_role_id){ echo 'selected'; } ?>><?php echo $row2->user_role_name; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
													<div class="col-md-6">
														<label>system_user Code <small>(Optional)</small></label>
														<input id="address name="address" type="text" placeholder="" class="form-control" value="<?php echo $row->address; ?>">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-6">
														<label>Gender</label>
														<select id="gender" name="gender" class="form-control form-control-select2" data-placeholder="Select Gender" data-fouc>
															<option value="">Select Gender</option>
															<option value="Female" <?php if ($row->gender == 'Female'){ echo 'selected'; } ?>>Female</option>
															<option value="Male" <?php if ($row->gender == 'Male'){ echo 'selected'; } ?>>Male</option>
														</select>
													</div>
													<div class="col-md-6">
														<label>Birth Date</label>
														<input id="birth_date" name="birth_date" type="text" placeholder="" class="form-control pickadatemax" autocomplete="off" value="<?php echo $row->birth_date; ?>">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">										
													<div class="col-sm-8">
														<label class="d-block">Status <span class="error">*</span></label>
														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																Active
																<input type="radio" class="form-check-input" id="is_active_1" name="is_active" value="1" <?php if ($row->is_active == 1){ echo 'checked'; } ?>>
															</label>
														</div>

														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																Inactive
																<input type="radio" class="form-check-input" id="is_active_0" name="is_active" value="0" <?php if ($row->is_active == 0){ echo 'checked'; } ?>>
															</label>
														</div>
													</div>
													<div class="col-sm-4">
														<label>Sort Key <span class="error">*</span></label>
														<input id="add_sort_key" name="sort_key" type="number" class="form-control" min="0" value="<?php echo $row->sort_key; ?>">
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h6 class="card-title font-weight-600 text-success text-uppercase">Credit Information</h6>			
										</div>							
										<div class="card-body">		
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-6">
														<label>Opening Balance <span class="error">*</span></label>
														<div class="input-group">
															<span class="input-group-prepend">
																<span class="input-group-text"><?php echo $default_currency; ?></span>
															</span>
															<input id="opening_balance" name="opening_balance" type="number" placeholder="" value="<?php echo $row->opening_balance; ?>" class="form-control" min="0">
														</div>
													</div>
													<div class="col-sm-6">
														<label>Credit Limit</label>
														<div class="input-group">
															<span class="input-group-prepend">
																<span class="input-group-text"><?php echo $default_currency; ?></span>
															</span>
															<input id="credit_limit" name="credit_limit" type="number" placeholder="" value="<?php echo $row->credit_limit; ?>" class="form-control" min="0">
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h6 class="card-title font-weight-600 text-success text-uppercase">Loyalty Information</h6>			
										</div>							
										<div class="card-body">		
											<div class="form-group mb-3">
												<div class="row">										
													<div class="col-md-6">
														<label>Enroll for Loyalty Program</label>
														<div class="form-check form-check-left form-check-switchery">
															<label class="form-check-label">
																<input id="loyalty_enrolled" name="loyalty_enrolled" type="checkbox" class="form-check-input-switchery" <?php if ($row->loyalty_enrolled == 1){ echo 'checked'; } ?> data-fouc>
															</label>
														</div>
													</div>
													<div class="col-md-6">
														<label>Enrollment Date</label>
														<input id="loyalty_enrollment_date" name="loyalty_enrollment_date" type="text" placeholder="" class="form-control pickadate" autocomplete="off" value="<?php echo $row->loyalty_enrollment_date; ?>">
													</div>
												</div>
											</div>
											<div class="form-group mb-3">
												<div class="row">
													<div class="col-sm-12">
														<label>Referred By</label>
														<select id="reference_system_user_id" name="reference_system_user_id" data-placeholder="Select system_user" class="form-control select" data-fouc>
															<option value="">Select system_user</option>
															<?php foreach ($system_users as $row2): ?>
																<?php if ($row2->system_user_id != $row->system_user_id): ?>
																	<option value="<?php echo $row2->system_user_id; ?>" <?php if ($row2->system_user_id == $row->reference_system_user_id){ echo 'selected'; } ?>><?php echo $row2->first_name . ' ' . $row2->last_name . ' (' . $row2->email_address . ')'; ?></option>
																<?php endif; ?>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="card rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h6 class="card-title font-weight-600 text-success text-uppercase">Billing Information</h6>			
										</div>
								
										<div class="card-body">
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-12">
														<label>First Name</label>
														<input id="billing_first_name" name="billing_first_name" type="text" placeholder="" class="form-control" value="<?php echo $row->billing_first_name; ?>">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-12">
														<label>Last Name</label>
														<input id="billing_last_name" name="billing_last_name" type="text" placeholder="" class="form-control" value="<?php echo $row->billing_last_name; ?>">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-12">
														<label>Email Address</label>
														<input id="billing_email_address" name="billing_email_address" type="email" placeholder="" class="form-control" value="<?php echo $row->billing_email_address; ?>">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-12">
														<label>Phone Number</label>
														<input id="billing_phone_number" name="billing_phone_number" type="text" placeholder="" class="form-control" value="<?php echo $row->billing_phone_number; ?>">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-12">
														<label>Street Address</label>
														<input id="billing_street_address" name="billing_street_address" type="text" placeholder="" class="form-control" value="<?php echo $row->billing_street_address; ?>">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-12">
														<label>Country</label>
														<select id="billing_country_id" name="billing_country_id" class="form-control form-control-select2" data-placeholder="Select Country" data-fouc>
															<option value="">Select Country</option>
															<?php foreach ($countries as $row2): ?>
																<option value="<?php echo $row2->country_id; ?>" <?php if ($row2->country_id == $row->billing_country_id){ echo 'selected'; } ?>><?php echo $row2->country_name; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											</div>			
											<div class="row mb-2">
												<div class="col-sm-12">
													<label>City</label>
													<div class="form-group form-group-feedback form-group-feedback-right mb-2">
														<select id="billing_region_id" name="billing_region_id" class="form-control select" data-placeholder="Select City" data-fouc>
															<option value="">Select City</option>
														</select>
														<div id="billing_region_loader" class="form-control-feedback display-none">
															<i class="icon-spinner2 spinner text-success"></i>
														</div>
													</div>
												</div>
											</div>							
											<div class="form-group mb-3">
												<div class="row">
													<div class="col-md-12">
														<label>Postal/ZIP Code</label>
														<input id="billing_postal_code" name="billing_postal_code" type="text" placeholder="" class="form-control" value="<?php echo $row->billing_postal_code; ?>">
													</div>
												</div>
											</div>
											<hr>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-12">
														<div class="form-check form-check-left">
															<label class="form-check-label text-primary font-weight-bold font-14">															
																<input id="system_user_use_same_shipping_address" name="system_user_use_same_shipping_address" type="checkbox" class="form-check-input">
																Use Same Shipping Information
															</label>
														</div>
													</div>
												</div>
												
											</div>
										</div>
									</div>
									
								</div>
								<div id="div_system_user_shipping_information" class="col-md-3">
									<div class="card rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h6 class="card-title font-weight-600 text-success text-uppercase">Shipping Information</h6>			
										</div>
								
										<div class="card-body">
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-12">
														<label>First Name</label>
														<input id="shipping_first_name" name="shipping_first_name" type="text" placeholder="" class="form-control" value="<?php echo $row->shipping_first_name; ?>">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-12">
														<label>Last Name</label>
														<input id="shipping_last_name" name="shipping_last_name" type="text" placeholder="" class="form-control" value="<?php echo $row->shipping_last_name; ?>">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-12">
														<label>Email Address</label>
														<input id="shipping_email_address" name="shipping_email_address" type="email" placeholder="" class="form-control" value="<?php echo $row->shipping_email_address; ?>">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-12">
														<label>Phone Number</label>
														<input id="shipping_phone_number" name="shipping_phone_number" type="text" placeholder="" class="form-control" value="<?php echo $row->shipping_phone_number; ?>">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-12">
														<label>Street Address</label>
														<input id="shipping_street_address" name="shipping_street_address" type="text" placeholder="" class="form-control" value="<?php echo $row->shipping_street_address; ?>">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-12">
														<label>Country</label>
														<select id="shipping_country_id" name="shipping_country_id" class="form-control form-control-select2" data-placeholder="Select Country" data-fouc>
															<option value="">Select Country</option>
															<?php foreach ($countries as $row2): ?>
																<option value="<?php echo $row2->country_id; ?>" <?php if ($row2->country_id == $row->shipping_country_id){ echo 'selected'; } ?>><?php echo $row2->country_name; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											</div>										
											<div class="row mb-2">
												<div class="col-sm-12">
													<label>City</label>
													<div class="form-group form-group-feedback form-group-feedback-right mb-2">
														<select id="shipping_region_id" name="shipping_region_id" class="form-control select" data-placeholder="Select City" data-fouc>
															<option value="">Select City</option>
														</select>
														<div id="shipping_region_loader" class="form-control-feedback display-none">
															<i class="icon-spinner2 spinner text-success"></i>
														</div>
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-12">
														<label>Postal/ZIP Code</label>
														<input id="shipping_postal_code" name="shipping_postal_code" type="text" placeholder="" class="form-control" value="<?php echo $row->shipping_postal_code; ?>">
													</div>
												</div>
											</div>
										</div>
									</div>
									
								</div>	

								<div class="col-md-2">
									<div class="card rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h6 class="card-title font-weight-600 text-success text-uppercase">Profile Picture</h6>			
										</div>
								
										<div class="card-body">
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-12">
														<?php if($row->profile_picture != '' && file_exists("./uploads/system_user_profile_pictures/" . $row->profile_picture)): ?>
															<div class="card-img-actions d-inline-block mb-2">
																<img class="card-img img-fluid" src="<?php echo base_url(); ?>uploads/system_user_profile_pictures/<?php echo $row->profile_picture; ?>" alt="">
															</div>
															<div class="form-group mb-2">
																<div class="row">
																	<div class="col-sm-12">
																		<label>Change Profile Picture</label>
																		<input id="profile_picture" name="profile_picture" type="file" class="form-control">
																	</div>
																</div>
															</div>
														<?php else: ?>
															<div class="form-group mb-2">
																<div class="row">
																	<div class="col-sm-12">
																		<label>Select Profile Picture</label>
																		<input id="profile_picture" name="profile_picture" type="file" class="form-control">
																	</div>
																</div>
															</div>
														<?php endif; ?>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<script type="text/javascript">
									cur_billing_region_id = '<?php echo $row->billing_region_id; ?>';
									cur_shipping_region_id = '<?php echo $row->shipping_region_id; ?>';

									$(document).ready(function() {
										$("#billing_country_id").trigger("change");
										$("#shipping_country_id").trigger("change");
									});


								</script>
								<div class="clearfix"></div>
								<div class="col-md-12">
									<hr>
									<div class="text-right">
										<button type="submit" id="btn_edit_system_user" class="btn btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE system_user</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
