		<!-- Main content -->
		<div class="content-wrapper">

			<?php if (!isset($user_role)): ?>

				<!-- Page header -->
				<div class="page-header">
					<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
						<div class="d-flex">
							<div class="breadcrumb">
								<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
								<a href="<?php echo base_url();?>be/user_roles" class="breadcrumb-item">User Roles</a>
								<span class="breadcrumb-item active">New User Role</span>
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
									<i class="icon-plus-circle2"></i> NEW USER ROLE
									<a href="<?php echo base_url();?>be/user_roles" class="btn btn-sm bg-success-400 text-success-400 border-success-400 float-right"><i class="icon-arrow-left15"></i> Back to User Roles</a>
								</h5>
							</div>
						</div>
					</div>

					<form id="frm_add_user_role" name="frm_add_user_role" method="post" onsubmit="return save_user_role();" autocomplete="false">
						<fieldset <?php if ($sbr_user_roles_add == false){ echo 'disabled'; } ?>>
							<div id="div_add_error" class="alert alert-danger display-none font-13"></div>
	           				<div id="div_add_success" class="alert alert-success display-none font-13"></div>

							<div class="row">
								<div class="col-md-4">
									<div class="card rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h6 class="card-title font-weight-600 text-success text-uppercase">User Role Information</h6>			
										</div>
								
										<div class="card-body">

											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-12">
														<label>User Role Name <span class="error">*</span></label>
														<input id="add_user_role_name" name="user_role_name" type="text" placeholder="" class="form-control">
													</div>										
												</div>
											</div>	
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-12">
														<label>Description</label>
														<textarea name="user_role_description" id="add_user_role_description" rows="3" cols="3" class="form-control" placeholder=""></textarea>
													</div>
												</div>
											</div>
											<div class="form-group mb-3 mb-md-2">
												<div class="row">
													<div class="col-sm-6">
														<label>Sort Key <span class="error">*</span></label>
														<input id="add_sort_key" name="sort_key" type="number" class="form-control" min="0" value="0">
													</div>
													<div class="col-sm-6">
														<label class="d-block">Status <span class="error">*</span></label>
														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																Active
																<input type="radio" class="form-check-input" id="add_is_active_1" name="is_active" value="1" checked>
															</label>
														</div>

														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																Inactive
																<input type="radio" class="form-check-input" id="add_is_active_0" name="is_active" value="0">
															</label>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-8">
									<div class="card rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h6 class="card-title font-weight-600 text-success text-uppercase">User Role Permissions</h6>			
										</div>
								
										<div class="card-body">
											<div class="table-responsive">
												<table class="table table-bordered table-lg">
													<tbody>
														<tr class="table-active">
															<th colspan="7" class="font-weight-bold text-secondary font-13">
																Login Access
															</th>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																Backend Login
															</th>
															<td class="text-center"><input id="sbr_backend_login" name="sbr_backend_login" type="checkbox" class=""></td>
															<td colspan="5" class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																POS Login
															</th>
															<td class="text-center"><input id="sbr_pos_login" name="sbr_pos_login" type="checkbox" class=""></td>
															<td colspan="5" class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-bold">
																Role
															</th>
															<td class="font-weight-bold text-center">View</td>
															<td class="font-weight-bold text-center">Add</td>
															<td class="font-weight-bold text-center">Edit</td>
															<td class="font-weight-bold text-center">Delete</td>
															<td class="font-weight-bold text-center">Print</td>
															<td class="font-weight-bold text-center">Manage/Process/Approve</td>
														</tr>
														<tr class="table-active">
															<th colspan="7" class="font-weight-bold text-secondary font-13">
																POS ~ Point of Sale
															</th>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_pos_sales_orders" name="sbr_pos_sales_orders" type="checkbox" class="chk_sbr_overall form-check-input"> Sales Orders
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_pos_sales_orders_view" name="sbr_pos_sales_orders_view" type="checkbox" class="" ></td>
															<td class="treportsext-center"><input id="sbr_pos_sales_orders_add" name="sbr_pos_sales_orders_add" type="checkbox" class="" ></td>
															<td class="text-center"><input id="sbr_pos_sales_orders_edit" name="sbr_pos_sales_orders_edit" type="checkbox" class="" ></td>
															<td class="text-center"><input id="sbr_pos_sales_orders_delete" name="sbr_pos_sales_orders_delete" type="checkbox" class="" ></td>
															<td class="text-center"><input id="sbr_pos_sales_orders_print" name="sbr_pos_sales_orders_print" type="checkbox" class="" ></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_pos_sales_returns" name="sbr_pos_sales_returns" type="checkbox" class="chk_sbr_overall form-check-input"> Sales Returns
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_pos_sales_returns_view" name="sbr_pos_sales_returns_view" type="checkbox" class="" ></td>
															<td class="text-center"><input id="sbr_pos_sales_returns_add" name="sbr_pos_sales_returns_add" type="checkbox" class="" ></td>
															<td class="text-center"><input id="sbr_pos_sales_returns_edit" name="sbr_pos_sales_returns_edit" type="checkbox" class="" ></td>
															<td class="text-center"><input id="sbr_pos_sales_returns_delete" name="sbr_pos_sales_returns_delete" type="checkbox" class="" ></td>
															<td class="text-center"><input id="sbr_pos_sales_returns_print" name="sbr_pos_sales_returns_print" type="checkbox" class="" ></td>
															<td class="text-center"><input id="sbr_pos_sales_returns_manage" name="sbr_pos_sales_returns_manage" type="checkbox" class="" ></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_pos_quotations" name="sbr_pos_quotations" type="checkbox" class="chk_sbr_overall form-check-input"> Quotations
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_pos_quotations_view" name="sbr_pos_quotations_view" type="checkbox" class="" ></td>
															<td class="text-center"><input id="sbr_pos_quotations_add" name="sbr_pos_quotations_add" type="checkbox" class="" ></td>
															<td class="text-center"><input id="sbr_pos_quotations_edit" name="sbr_pos_quotations_edit" type="checkbox" class="" ></td>
															<td class="text-center"><input id="sbr_pos_quotations_delete" name="sbr_pos_quotations_delete" type="checkbox" class="" ></td>
															<td class="text-center"><input id="sbr_pos_quotations_print" name="sbr_pos_quotations_print" type="checkbox" class="" ></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_pos_products" name="sbr_pos_products" type="checkbox" class="chk_sbr_overall form-check-input"> Products
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_pos_products_view" name="sbr_pos_products_view" type="checkbox" class="" ></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_pos_customers" name="sbr_pos_customers" type="checkbox" class="chk_sbr_overall form-check-input"> Customers
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_pos_customers_view" name="sbr_pos_customers_view" type="checkbox" class="" ></td>
															<td class="text-center"><input id="sbr_pos_customers_add" name="sbr_pos_customers_add" type="checkbox" class="" ></td>
															<td class="text-center"><input id="sbr_pos_customers_edit" name="sbr_pos_customers_edit" type="checkbox" class="" ></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_pos_expenses" name="sbr_pos_expenses" type="checkbox" class="chk_sbr_overall form-check-input"> Expenses
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_pos_expenses_view" name="sbr_pos_expenses_view" type="checkbox" class="" ></td>
															<td class="text-center"><input id="sbr_pos_expenses_add" name="sbr_pos_expenses_add" type="checkbox" class="" ></td>
															<td class="text-center"><input id="sbr_pos_expenses_edit" name="sbr_pos_expenses_edit" type="checkbox" class="" ></td>
															<td class="text-center"><input id="sbr_pos_expenses_delete" name="sbr_pos_expenses_delete" type="checkbox" class="" ></td>
															<td class="text-center"></td>
															<td class="text-center"><input id="sbr_pos_expenses_manage" name="sbr_pos_expenses_manage" type="checkbox" class="" ></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_pos_reports" name="sbr_pos_reports" type="checkbox" class="chk_sbr_overall form-check-input"> Reports
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_pos_reports_view" name="sbr_pos_reports_view" type="checkbox" class="" ></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr class="table-active">
															<th colspan="7" class="font-weight-bold text-secondary font-13">
																BE ~ Global Settings
															</th>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_store_information" name="sbr_store_information" type="checkbox" class="chk_sbr_overall form-check-input"> Store Information
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_store_information_view" name="sbr_store_information_view" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"><input id="sbr_store_information_edit" name="sbr_store_information_edit" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_outlets" name="sbr_outlets" type="checkbox" class="chk_sbr_overall form-check-input"> Outlets
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_outlets_view" name="sbr_outlets_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_outlets_add" name="sbr_outlets_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_outlets_edit" name="sbr_outlets_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_outlets_delete" name="sbr_outlets_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_countries" name="sbr_countries" type="checkbox" class="chk_sbr_overall form-check-input"> Countries
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_countries_view" name="sbr_countries_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_countries_add" name="sbr_countries_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_countries_edit" name="sbr_countries_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_countries_delete" name="sbr_countries_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"><input id="sbr_countries_manage" name="sbr_countries_manage" type="checkbox" class=""></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_regions" name="sbr_regions" type="checkbox" class="chk_sbr_overall form-check-input"> Regions
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_regions_view" name="sbr_regions_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_regions_add" name="sbr_regions_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_regions_edit" name="sbr_regions_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_regions_delete" name="sbr_regions_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_shipping_zones" name="sbr_shipping_zones" type="checkbox" class="chk_sbr_overall form-check-input"> Shipping Zones
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_shipping_zones_view" name="sbr_shipping_zones_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_shipping_zones_add" name="sbr_shipping_zones_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_shipping_zones_edit" name="sbr_shipping_zones_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_shipping_zones_delete" name="sbr_shipping_zones_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_pickup_locations" name="sbr_pickup_locations" type="checkbox" class="chk_sbr_overall form-check-input"> Pickup Locations
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_pickup_locations_view" name="sbr_pickup_locations_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_pickup_locations_add" name="sbr_pickup_locations_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_pickup_locations_edit" name="sbr_pickup_locations_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_pickup_locations_delete" name="sbr_pickup_locations_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_currencies" name="sbr_currencies" type="checkbox" class="chk_sbr_overall form-check-input"> Currencies
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_currencies_view" name="sbr_currencies_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_currencies_add" name="sbr_currencies_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_currencies_edit" name="sbr_currencies_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_currencies_delete" name="sbr_currencies_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_banks" name="sbr_banks" type="checkbox" class="chk_sbr_overall form-check-input"> Banks
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_banks_view" name="sbr_banks_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_banks_add" name="sbr_banks_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_banks_edit" name="sbr_banks_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_banks_delete" name="sbr_banks_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"><input id="sbr_banks_manage" name="sbr_banks_manage" type="checkbox" class=""></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_bank_branches" name="sbr_bank_branches" type="checkbox" class="chk_sbr_overall form-check-input"> Bank Branches
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_bank_branches_view" name="sbr_bank_branches_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_bank_branches_add" name="sbr_bank_branches_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_bank_branches_edit" name="sbr_bank_branches_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_bank_branches_delete" name="sbr_bank_branches_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_tax_rates" name="sbr_tax_rates" type="checkbox" class="chk_sbr_overall form-check-input"> Tax Rates
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_tax_rates_view" name="sbr_tax_rates_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_tax_rates_add" name="sbr_tax_rates_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_tax_rates_edit" name="sbr_tax_rates_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_tax_rates_delete" name="sbr_tax_rates_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_credit_terms" name="sbr_credit_terms" type="checkbox" class="chk_sbr_overall form-check-input"> Credit Terms
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_credit_terms_view" name="sbr_credit_terms_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_credit_terms_add" name="sbr_credit_terms_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_credit_terms_edit" name="sbr_credit_terms_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_credit_terms_delete" name="sbr_credit_terms_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_mpesa_settings" name="sbr_mpesa_settings" type="checkbox" class="chk_sbr_overall form-check-input"> Mpesa Settings
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_mpesa_settings_view" name="sbr_mpesa_settings_view" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"><input id="sbr_mpesa_settings_edit" name="sbr_mpesa_settings_edit" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_pesapal_settings" name="sbr_pesapal_settings" type="checkbox" class="chk_sbr_overall form-check-input"> Pesapal Settings
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_pesapal_settings_view" name="sbr_pesapal_settings_view" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"><input id="sbr_pesapal_settings_edit" name="sbr_pesapal_settings_edit" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_email_accounts" name="sbr_email_accounts" type="checkbox" class="chk_sbr_overall form-check-input"> Email Accounts
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_email_accounts_view" name="sbr_email_accounts_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_email_accounts_add" name="sbr_email_accounts_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_email_accounts_edit" name="sbr_email_accounts_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_email_accounts_delete" name="sbr_email_accounts_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_bulk_sms_settings" name="sbr_bulk_sms_settings" type="checkbox" class="chk_sbr_overall form-check-input"> Bulk SMS Settings
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_bulk_sms_settings_view" name="sbr_bulk_sms_settings_view" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"><input id="sbr_bulk_sms_settings_edit" name="sbr_bulk_sms_settings_edit" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_prefixes" name="sbr_prefixes" type="checkbox" class="chk_sbr_overall form-check-input"> Prefixes
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_prefixes_view" name="sbr_prefixes_view" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"><input id="sbr_prefixes_edit" name="sbr_prefixes_edit" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_void_reasons" name="sbr_void_reasons" type="checkbox" class="chk_sbr_overall form-check-input"> Void Reasons
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_void_reasons_view" name="sbr_void_reasons_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_void_reasons_add" name="sbr_void_reasons_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_void_reasons_edit" name="sbr_void_reasons_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_void_reasons_delete" name="sbr_void_reasons_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_bitly_settings" name="sbr_bitly_settings" type="checkbox" class="chk_sbr_overall form-check-input"> Bitly Settings
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_bitly_settings_view" name="sbr_bitly_settings_view" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"><input id="sbr_bitly_settings_edit" name="sbr_bitly_settings_edit" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr class="table-active">
															<th colspan="7" class="font-weight-bold text-secondary font-13">
																BE ~ Products
															</th>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_products" name="sbr_products" type="checkbox" class="chk_sbr_overall form-check-input"> Products
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_products_view" name="sbr_products_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_products_add" name="sbr_products_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_products_edit" name="sbr_products_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_products_delete" name="sbr_products_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_product_categories" name="sbr_product_categories" type="checkbox" class="chk_sbr_overall form-check-input"> Product Categories
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_product_categories_view" name="sbr_product_categories_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_product_categories_add" name="sbr_product_categories_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_product_categories_edit" name="sbr_product_categories_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_product_categories_delete" name="sbr_product_categories_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_units_of_measure" name="sbr_units_of_measure" type="checkbox" class="chk_sbr_overall form-check-input"> Units Of Measure
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_units_of_measure_view" name="sbr_units_of_measure_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_units_of_measure_add" name="sbr_units_of_measure_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_units_of_measure_edit" name="sbr_units_of_measure_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_units_of_measure_delete" name="sbr_units_of_measure_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_product_sizes" name="sbr_product_sizes" type="checkbox" class="chk_sbr_overall form-check-input"> Product Sizes
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_product_sizes_view" name="sbr_product_sizes_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_product_sizes_add" name="sbr_product_sizes_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_product_sizes_edit" name="sbr_product_sizes_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_product_sizes_delete" name="sbr_product_sizes_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_brands" name="sbr_brands" type="checkbox" class="chk_sbr_overall form-check-input"> Brands
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_brands_view" name="sbr_brands_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_brands_add" name="sbr_brands_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_brands_edit" name="sbr_brands_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_brands_delete" name="sbr_brands_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr class="table-active">
															<th colspan="7" class="font-weight-bold text-secondary font-13">
																BE ~ Inventory
															</th>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_purchase_orders" name="sbr_purchase_orders" type="checkbox" class="chk_sbr_overall form-check-input"> Purchase Orders
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_purchase_orders_view" name="sbr_purchase_orders_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_purchase_orders_add" name="sbr_purchase_orders_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_purchase_orders_edit" name="sbr_purchase_orders_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_purchase_orders_delete" name="sbr_purchase_orders_delete" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_purchase_orders_print" name="sbr_purchase_orders_print" type="checkbox" class=""></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_goods_received" name="sbr_goods_received" type="checkbox" class="chk_sbr_overall form-check-input"> Goods Received
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_goods_received_view" name="sbr_goods_received_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_goods_received_add" name="sbr_goods_received_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_goods_received_edit" name="sbr_goods_received_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_goods_received_delete" name="sbr_goods_received_delete" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_goods_received_print" name="sbr_goods_received_print" type="checkbox" class=""></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_stock_adjustments" name="sbr_stock_adjustments" type="checkbox" class="chk_sbr_overall form-check-input"> Stock Adjustments
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_stock_adjustments_view" name="sbr_stock_adjustments_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_stock_adjustments_add" name="sbr_stock_adjustments_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_stock_adjustments_edit" name="sbr_stock_adjustments_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_stock_adjustments_delete" name="sbr_stock_adjustments_delete" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_stock_adjustments_print" name="sbr_stock_adjustments_print" type="checkbox" class=""></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_goods_received" name="sbr_goods_received" type="checkbox" class="chk_sbr_overall form-check-input"> Goods Returned
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_goods_returned_view" name="sbr_goods_returned_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_goods_returned_add" name="sbr_goods_returned_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_goods_returned_edit" name="sbr_goods_returned_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_goods_returned_delete" name="sbr_goods_returned_delete" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_goods_returned_print" name="sbr_goods_returned_print" type="checkbox" class=""></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_stock_transfers" name="sbr_stock_transfers" type="checkbox" class="chk_sbr_overall form-check-input"> Stock Transfers
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_stock_transfers_view" name="sbr_stock_transfers_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_stock_transfers_add" name="sbr_stock_transfers_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_stock_transfers_edit" name="sbr_stock_transfers_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_stock_transfers_delete" name="sbr_stock_transfers_delete" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_stock_transfers_print" name="sbr_stock_transfers_print" type="checkbox" class=""></td>
															<td class="text-center"></td>
														</tr>
														<tr class="table-active">
															<th colspan="7" class="font-weight-bold text-secondary font-13">
																BE ~ Sales
															</th>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_online_sales" name="sbr_online_sales" type="checkbox" class="chk_sbr_overall form-check-input"> Online Sales
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_online_sales_view" name="sbr_online_sales_view" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"><input id="sbr_online_sales_print" name="sbr_online_sales_print" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_online_sales_manage" name="sbr_online_sales_manage" type="checkbox" class=""></td>
														</tr>
														<tr class="table-active">
															<th colspan="7" class="font-weight-bold text-secondary font-13">
																Payments
															</th>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_paybill_payments" name="sbr_paybill_payments" type="checkbox" class="chk_sbr_overall form-check-input"> Paybill Payments
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_paybill_payments_view" name="sbr_paybill_payments_view" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"><input id="sbr_paybill_payments_print" name="sbr_paybill_payments_print" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_paybill_payments_manage" name="sbr_paybill_payments_manage" type="checkbox" class=""></td>
														</tr>
														<tr class="table-active">
															<th colspan="7" class="font-weight-bold text-secondary font-13">
																BE ~ Customers
															</th>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_customers" name="sbr_customers" type="checkbox" class="chk_sbr_overall form-check-input"> Customers
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_customers_view" name="sbr_customers_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_customers_add" name="sbr_customers_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_customers_edit" name="sbr_customers_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_customers_delete" name="sbr_customers_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_customer_groups" name="sbr_customer_groups" type="checkbox" class="chk_sbr_overall form-check-input"> Customer Groups
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_customer_groups_view" name="sbr_customer_groups_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_customer_groups_add" name="sbr_customer_groups_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_customer_groups_edit" name="sbr_customer_groups_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_customer_groups_delete" name="sbr_customer_groups_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr class="table-active">
															<th colspan="7" class="font-weight-bold text-secondary font-13">
																BE ~ Suppliers
															</th>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_suppliers" name="sbr_suppliers" type="checkbox" class="chk_sbr_overall form-check-input"> Suppliers
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_suppliers_view" name="sbr_suppliers_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_suppliers_add" name="sbr_suppliers_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_suppliers_edit" name="sbr_suppliers_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_suppliers_delete" name="sbr_suppliers_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr class="table-active">
															<th colspan="7" class="font-weight-bold text-secondary font-13">
																BE ~ Affiliate Program
															</th>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_affiliate_accounts" name="sbr_affiliate_accounts" type="checkbox" class="chk_sbr_overall form-check-input"> Affiliate Accounts
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_affiliate_accounts_view" name="sbr_affiliate_accounts_view" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"><input id="sbr_affiliate_accounts_delete" name="sbr_affiliate_accounts_delete" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_affiliate_accounts_print" name="sbr_affiliate_accounts_print" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_affiliate_accounts_manage" name="sbr_affiliate_accounts_manage" type="checkbox" class=""></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_affiliate_packages" name="sbr_affiliate_packages" type="checkbox" class="chk_sbr_overall form-check-input"> Affiliate Packages
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_affiliate_packages_view" name="sbr_affiliate_packages_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_affiliate_packages_add" name="sbr_affiliate_packages_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_affiliate_packages_edit" name="sbr_affiliate_packages_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_affiliate_packages_delete" name="sbr_affiliate_packages_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_affiliate_terms" name="sbr_affiliate_terms" type="checkbox" class="chk_sbr_overall form-check-input"> Affiliate Terms
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_affiliate_terms_view" name="sbr_affiliate_terms_view" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"><input id="sbr_affiliate_terms_edit" name="sbr_affiliate_terms_edit" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr class="table-active">
															<th colspan="7" class="font-weight-bold text-secondary font-13">
																BE ~ Promotions
															</th>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_promo_codes" name="sbr_promo_codes" type="checkbox" class="chk_sbr_overall form-check-input"> Promo Codes
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_promo_codes_view" name="sbr_promo_codes_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_promo_codes_add" name="sbr_promo_codes_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_promo_codes_edit" name="sbr_promo_codes_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_promo_codes_delete" name="sbr_promo_codes_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr class="table-active">
															<th colspan="7" class="font-weight-bold text-secondary font-13">
																BE ~ CMS Content
															</th>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_home_sliders" name="sbr_home_sliders" type="checkbox" class="chk_sbr_overall form-check-input"> Home Sliders
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_home_sliders_view" name="sbr_home_sliders_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_home_sliders_add" name="sbr_home_sliders_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_home_sliders_edit" name="sbr_home_sliders_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_home_sliders_delete" name="sbr_home_sliders_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_top_categories" name="sbr_top_categories" type="checkbox" class="chk_sbr_overall form-check-input"> Top Categories
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_top_categories_view" name="sbr_top_categories_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_top_categories_add" name="sbr_top_categories_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_top_categories_edit" name="sbr_top_categories_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_top_categories_delete" name="sbr_top_categories_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_featured_categories" name="sbr_featured_categories" type="checkbox" class="chk_sbr_overall form-check-input"> Featured Categories
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_featured_categories_view" name="sbr_featured_categories_view" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"><input id="sbr_featured_categories_edit" name="sbr_featured_categories_edit" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_promo_banners" name="sbr_promo_banners" type="checkbox" class="chk_sbr_overall form-check-input"> Promo Banners
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_promo_banners_view" name="sbr_promo_banners_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_promo_banners_add" name="sbr_promo_banners_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_promo_banners_edit" name="sbr_promo_banners_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_promo_banners_delete" name="sbr_promo_banners_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_testimonials" name="sbr_testimonials" type="checkbox" class="chk_sbr_overall form-check-input"> Testimonials
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_testimonials_view" name="sbr_testimonials_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_testimonials_add" name="sbr_testimonials_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_testimonials_edit" name="sbr_testimonials_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_testimonials_delete" name="sbr_testimonials_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_about_us" name="sbr_about_us" type="checkbox" class="chk_sbr_overall form-check-input"> About Us
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_about_us_view" name="sbr_about_us_view" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"><input id="sbr_about_us_edit" name="sbr_about_us_edit" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_how_to_shop" name="sbr_how_to_shop" type="checkbox" class="chk_sbr_overall form-check-input"> How to Shop
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_how_to_shop_view" name="sbr_how_to_shop_view" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"><input id="sbr_how_to_shop_edit" name="sbr_how_to_shop_edit" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_faqs" name="sbr_faqs" type="checkbox" class="chk_sbr_overall form-check-input"> FAQs
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_faqs_view" name="sbr_faqs_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_faqs_add" name="sbr_faqs_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_faqs_edit" name="sbr_faqs_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_faqs_delete" name="sbr_faqs_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_return_policy" name="sbr_return_policy" type="checkbox" class="chk_sbr_overall form-check-input"> Return Policy
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_return_policy_view" name="sbr_return_policy_view" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"><input id="sbr_return_policy_edit" name="sbr_return_policy_edit" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_privacy_policy" name="sbr_privacy_policy" type="checkbox" class="chk_sbr_overall form-check-input"> Privacy Policy
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_privacy_policy_view" name="sbr_privacy_policy_view" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"><input id="sbr_privacy_policy_edit" name="sbr_privacy_policy_edit" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_terms_and_conditions" name="sbr_terms_and_conditions" type="checkbox" class="chk_sbr_overall form-check-input"> Terms and Conditions
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_terms_and_conditions_view" name="sbr_terms_and_conditions_view" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"><input id="sbr_terms_and_conditions_edit" name="sbr_terms_and_conditions_edit" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr class="table-active">
															<th colspan="7" class="font-weight-bold text-secondary font-13">
																BE ~ Blog
															</th>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_blog" name="sbr_blog" type="checkbox" class="chk_sbr_overall form-check-input"> Blog Articles
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_blog_view" name="sbr_blog_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_blog_add" name="sbr_blog_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_blog_edit" name="sbr_blog_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_blog_delete" name="sbr_blog_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_blog_categories" name="sbr_blog_categories" type="checkbox" class="chk_sbr_overall form-check-input"> Blog Categories
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_blog_categories_view" name="sbr_blog_categories_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_blog_categories_add" name="sbr_blog_categories_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_blog_categories_edit" name="sbr_blog_categories_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_blog_categories_delete" name="sbr_blog_categories_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr class="table-active">
															<th colspan="7" class="font-weight-bold text-secondary font-13">
																BE ~ User Management
															</th>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_system_users" name="sbr_system_users" type="checkbox" class="chk_sbr_overall form-check-input"> System Users
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_system_users_view" name="sbr_system_users_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_system_users_add" name="sbr_system_users_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_system_users_edit" name="sbr_system_users_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_system_users_delete" name="sbr_system_users_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_user_roles" name="sbr_user_roles" type="checkbox" class="chk_sbr_overall form-check-input"> User Roles
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_user_roles_view" name="sbr_user_roles_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_user_roles_add" name="sbr_user_roles_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_user_roles_edit" name="sbr_user_roles_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_user_roles_delete" name="sbr_user_roles_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>
														<tr>
															<th class="font-weight-semibold">
																<div class="form-check">
																	<label class="form-check-label font-weight-semibold">
																		<input id="sbr_departments" name="sbr_departments" type="checkbox" class="chk_sbr_overall form-check-input"> Departments
																	</label>
																</div>
															</th>
															<td class="text-center"><input id="sbr_departments_view" name="sbr_departments_view" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_departments_add" name="sbr_departments_add" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_departments_edit" name="sbr_departments_edit" type="checkbox" class=""></td>
															<td class="text-center"><input id="sbr_departments_delete" name="sbr_departments_delete" type="checkbox" class=""></td>
															<td class="text-center"></td>
															<td class="text-center"></td>
														</tr>


													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>

								<div class="clearfix"></div>
								<div class="col-md-12">
									<hr>
									<div class="text-right">
										<button type="submit" id="btn_add_user_role" class="btn btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> SAVE USER ROLE</button>
									</div>
								</div>
							</div>
						</form>
					</fieldset>
				</div>

			<?php else: ?>
				<?php foreach ($user_role as $row): ?>
					<!-- Page header -->
					<div class="page-header">
						<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
							<div class="d-flex">
								<div class="breadcrumb">
									<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
									<a href="#" class="breadcrumb-item">System Users</a>
									<a href="<?php echo base_url();?>be/user_roles" class="breadcrumb-item">User Roles</a>
									<span class="breadcrumb-item active">Edit User Role (<?php echo $row->user_role_name; ?>)</span>
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
										<i class="icon-pencil6"></i> Edit User Role (<?php echo $row->user_role_name; ?>)
										<a href="<?php echo base_url();?>be/user_roles" class="btn btn-sm bg-success-400 text-success-400 border-success-400 float-right"><i class="icon-arrow-left15"></i> Back to User Roles</a>
									</h5>
								</div>
							</div>
						</div>

						<form id="frm_edit_user_role" name="frm_edit_user_role" method="post" onsubmit="return update_user_role();" autocomplete="false">
							<fieldset <?php if ($sbr_user_roles_edit == false){ echo 'disabled'; } ?>>
								<div id="div_edit_error" class="alert alert-danger display-none font-13"></div>
	               				<div id="div_edit_success" class="alert alert-success display-none font-13"></div>

								<div class="row">

									<div class="col-md-4">
										<div class="card rounded-top-0">
											<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
												<h6 class="card-title font-weight-600 text-success text-uppercase">User Role Information</h6>			
											</div>
									
											<div class="card-body">

				                   				<input type="hidden" id="user_role_id" name="user_role_id" value="<?php echo $row->user_role_id; ?>">

				                   				<div class="form-group mb-2">
													<div class="row">
														<div class="col-sm-12">
															<label>User Role Name <span class="error">*</span></label>
															<input id="add_user_role_name" name="user_role_name" type="text" placeholder="" value="<?php echo $row->user_role_name; ?>" class="form-control">
														</div>										
													</div>
												</div>	
												<div class="form-group mb-2">
													<div class="row">
														<div class="col-sm-12">
															<label>Description</label>
															<textarea name="user_role_description" id="add_user_role_description" rows="3" cols="3" class="form-control" placeholder=""><?php echo $row->user_role_description; ?></textarea>
														</div>
													</div>
												</div>
												<div class="form-group mb-3 mb-md-2">
													<div class="row">
														<div class="col-sm-6">
															<label>Sort Key <span class="error">*</span></label>
															<input id="add_sort_key" name="sort_key" type="number" class="form-control" min="0" value="<?php echo $row->sort_key; ?>">
														</div>
														<div class="col-sm-6">
															<label class="d-block">Status <span class="error">*</span></label>
															<div class="form-check form-check-inline form-check-right">
																<label class="form-check-label font-weight-semibold">
																	Active
																	<input type="radio" class="form-check-input" id="add_is_active_1" name="is_active" value="1" <?php if ($row->is_active == 1){ echo 'checked'; } ?>>
																</label>
															</div>

															<div class="form-check form-check-inline form-check-right">
																<label class="form-check-label font-weight-semibold">
																	Inactive
																	<input type="radio" class="form-check-input" id="add_is_active_0" name="is_active" value="0" <?php if ($row->is_active == 0){ echo 'checked'; } ?>>
																</label>
															</div>
														</div>
													</div>
												</div>
												
											</div>
										</div>
									</div>
									<div class="col-md-8">
										<div class="card rounded-top-0">
											<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
												<h6 class="card-title font-weight-600 text-success text-uppercase">User Role Permissions</h6>			
											</div>
									
											<div class="card-body">
												<div class="table-responsive">
													<table class="table table-bordered table-lg">
														<tbody>
															<tr class="table-active">
																<th colspan="7" class="font-weight-bold text-secondary font-13">
																	Login Access
																</th>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	Backend Login
																</th>
																<td class="text-center"><input id="sbr_backend_login" name="sbr_backend_login" type="checkbox" class="" <?php if ($row->backend_login == 1){ echo 'checked'; } ?>></td>
																<td colspan="5" class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	POS Login
																</th>
																<td class="text-center"><input id="sbr_pos_login" name="sbr_pos_login" type="checkbox" class="" <?php if ($row->pos_login == 1){ echo 'checked'; } ?>></td>
																<td colspan="5" class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-bold">
																	Role
																</th>
																<td class="font-weight-bold text-center">View</td>
																<td class="font-weight-bold text-center">Add</td>
																<td class="font-weight-bold text-center">Edit</td>
																<td class="font-weight-bold text-center">Delete/Void</td>
																<td class="font-weight-bold text-center">Print</td>
																<td class="font-weight-bold text-center">Manage/Process/Approve</td>
															</tr>
															<tr class="table-active">
																<th colspan="7" class="font-weight-bold text-secondary font-13">
																	POS ~ Point of Sale
																</th>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_pos_sales_orders" name="sbr_pos_sales_orders" type="checkbox" class="chk_sbr_overall form-check-input"> Sales Orders
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_pos_sales_orders_view" name="sbr_pos_sales_orders_view" type="checkbox" class="" <?php if ($row->pos_sales_orders_view == 1){ echo 'checked'; } ?>></td>
																<td class="treportsext-center"><input id="sbr_pos_sales_orders_add" name="sbr_pos_sales_orders_add" type="checkbox" class="" <?php if ($row->pos_sales_orders_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_pos_sales_orders_edit" name="sbr_pos_sales_orders_edit" type="checkbox" class="" <?php if ($row->pos_sales_orders_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_pos_sales_orders_delete" name="sbr_pos_sales_orders_delete" type="checkbox" class="" <?php if ($row->pos_sales_orders_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_pos_sales_orders_print" name="sbr_pos_sales_orders_print" type="checkbox" class="" <?php if ($row->pos_sales_orders_print == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_pos_sales_returns" name="sbr_pos_sales_returns" type="checkbox" class="chk_sbr_overall form-check-input"> Sales Returns
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_pos_sales_returns_view" name="sbr_pos_sales_returns_view" type="checkbox" class="" <?php if ($row->pos_sales_returns_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_pos_sales_returns_add" name="sbr_pos_sales_returns_add" type="checkbox" class="" <?php if ($row->pos_sales_returns_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_pos_sales_returns_edit" name="sbr_pos_sales_returns_edit" type="checkbox" class="" <?php if ($row->pos_sales_returns_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_pos_sales_returns_delete" name="sbr_pos_sales_returns_delete" type="checkbox" class="" <?php if ($row->pos_sales_returns_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_pos_sales_returns_print" name="sbr_pos_sales_returns_print" type="checkbox" class="" <?php if ($row->pos_sales_returns_print == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_pos_sales_returns_manage" name="sbr_pos_sales_returns_manage" type="checkbox" class="" <?php if ($row->pos_sales_returns_manage == 1){ echo 'checked'; } ?>></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_pos_quotations" name="sbr_pos_quotations" type="checkbox" class="chk_sbr_overall form-check-input"> Quotations
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_pos_quotations_view" name="sbr_pos_quotations_view" type="checkbox" class="" <?php if ($row->pos_quotations_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_pos_quotations_add" name="sbr_pos_quotations_add" type="checkbox" class="" <?php if ($row->pos_quotations_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_pos_quotations_edit" name="sbr_pos_quotations_edit" type="checkbox" class="" <?php if ($row->pos_quotations_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_pos_quotations_delete" name="sbr_pos_quotations_delete" type="checkbox" class="" <?php if ($row->pos_quotations_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_pos_quotations_print" name="sbr_pos_quotations_print" type="checkbox" class="" <?php if ($row->pos_quotations_print == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_pos_products" name="sbr_pos_products" type="checkbox" class="chk_sbr_overall form-check-input"> Products
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_pos_products_view" name="sbr_pos_products_view" type="checkbox" class="" <?php if ($row->pos_products_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_pos_customers" name="sbr_pos_customers" type="checkbox" class="chk_sbr_overall form-check-input"> Customers
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_pos_customers_view" name="sbr_pos_customers_view" type="checkbox" class="" <?php if ($row->pos_customers_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_pos_customers_add" name="sbr_pos_customers_add" type="checkbox" class="" <?php if ($row->pos_customers_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_pos_customers_edit" name="sbr_pos_customers_edit" type="checkbox" class="" <?php if ($row->pos_customers_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_pos_expenses" name="sbr_pos_expenses" type="checkbox" class="chk_sbr_overall form-check-input"> Expenses
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_pos_expenses_view" name="sbr_pos_expenses_view" type="checkbox" class="" <?php if ($row->pos_expenses_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_pos_expenses_add" name="sbr_pos_expenses_add" type="checkbox" class="" <?php if ($row->pos_expenses_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_pos_expenses_edit" name="sbr_pos_expenses_edit" type="checkbox" class="" <?php if ($row->pos_expenses_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_pos_expenses_delete" name="sbr_pos_expenses_delete" type="checkbox" class="" <?php if ($row->pos_expenses_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"><input id="sbr_pos_expenses_manage" name="sbr_pos_expenses_manage" type="checkbox" class="" <?php if ($row->pos_expenses_manage == 1){ echo 'checked'; } ?>></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_pos_reports" name="sbr_pos_reports" type="checkbox" class="chk_sbr_overall form-check-input"> Reports
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_pos_reports_view" name="sbr_pos_reports_view" type="checkbox" class="" <?php if ($row->pos_reports_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr class="table-active">
																<th colspan="7" class="font-weight-bold text-secondary font-13">
																	BE ~ Global Settings
																</th>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_store_information" name="sbr_store_information" type="checkbox" class="chk_sbr_overall form-check-input"> Store Information
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_store_information_view" name="sbr_store_information_view" type="checkbox" class="" <?php if ($row->store_information_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"><input id="sbr_store_information_edit" name="sbr_store_information_edit" type="checkbox" class="" <?php if ($row->store_information_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_outlets" name="sbr_outlets" type="checkbox" class="chk_sbr_overall form-check-input"> Outlets
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_outlets_view" name="sbr_outlets_view" type="checkbox" class="" <?php if ($row->outlets_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_outlets_add" name="sbr_outlets_add" type="checkbox" class="" <?php if ($row->outlets_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_outlets_edit" name="sbr_outlets_edit" type="checkbox" class="" <?php if ($row->outlets_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_outlets_delete" name="sbr_outlets_delete" type="checkbox" class="" <?php if ($row->outlets_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_countries" name="sbr_countries" type="checkbox" class="chk_sbr_overall form-check-input"> Countries
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_countries_view" name="sbr_countries_view" type="checkbox" class="" <?php if ($row->countries_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_countries_add" name="sbr_countries_add" type="checkbox" class="" <?php if ($row->countries_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_countries_edit" name="sbr_countries_edit" type="checkbox" class="" <?php if ($row->countries_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_countries_delete" name="sbr_countries_delete" type="checkbox" class="" <?php if ($row->countries_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"><input id="sbr_countries_manage" name="sbr_countries_manage" type="checkbox" class="" <?php if ($row->countries_manage == 1){ echo 'checked'; } ?>></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_regions" name="sbr_regions" type="checkbox" class="chk_sbr_overall form-check-input"> Regions
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_regions_view" name="sbr_regions_view" type="checkbox" class="" <?php if ($row->regions_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_regions_add" name="sbr_regions_add" type="checkbox" class="" <?php if ($row->regions_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_regions_edit" name="sbr_regions_edit" type="checkbox" class="" <?php if ($row->regions_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_regions_delete" name="sbr_regions_delete" type="checkbox" class="" <?php if ($row->regions_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_shipping_zones" name="sbr_shipping_zones" type="checkbox" class="chk_sbr_overall form-check-input"> Shipping Zones
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_shipping_zones_view" name="sbr_shipping_zones_view" type="checkbox" class="" <?php if ($row->shipping_zones_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_shipping_zones_add" name="sbr_shipping_zones_add" type="checkbox" class="" <?php if ($row->shipping_zones_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_shipping_zones_edit" name="sbr_shipping_zones_edit" type="checkbox" class="" <?php if ($row->shipping_zones_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_shipping_zones_delete" name="sbr_shipping_zones_delete" type="checkbox" class="" <?php if ($row->shipping_zones_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_pickup_locations" name="sbr_pickup_locations" type="checkbox" class="chk_sbr_overall form-check-input"> Pickup Locations
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_pickup_locations_view" name="sbr_pickup_locations_view" type="checkbox" class="" <?php if ($row->pickup_locations_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_pickup_locations_add" name="sbr_pickup_locations_add" type="checkbox" class="" <?php if ($row->pickup_locations_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_pickup_locations_edit" name="sbr_pickup_locations_edit" type="checkbox" class="" <?php if ($row->pickup_locations_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_pickup_locations_delete" name="sbr_pickup_locations_delete" type="checkbox" class="" <?php if ($row->pickup_locations_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_currencies" name="sbr_currencies" type="checkbox" class="chk_sbr_overall form-check-input"> Currencies
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_currencies_view" name="sbr_currencies_view" type="checkbox" class="" <?php if ($row->currencies_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_currencies_add" name="sbr_currencies_add" type="checkbox" class="" <?php if ($row->currencies_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_currencies_edit" name="sbr_currencies_edit" type="checkbox" class="" <?php if ($row->currencies_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_currencies_delete" name="sbr_currencies_delete" type="checkbox" class="" <?php if ($row->currencies_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_banks" name="sbr_banks" type="checkbox" class="chk_sbr_overall form-check-input"> Banks
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_banks_view" name="sbr_banks_view" type="checkbox" class="" <?php if ($row->banks_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_banks_add" name="sbr_banks_add" type="checkbox" class="" <?php if ($row->banks_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_banks_edit" name="sbr_banks_edit" type="checkbox" class="" <?php if ($row->banks_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_banks_delete" name="sbr_banks_delete" type="checkbox" class="" <?php if ($row->banks_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"><input id="sbr_banks_manage" name="sbr_banks_manage" type="checkbox" class="" <?php if ($row->banks_manage == 1){ echo 'checked'; } ?>></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_bank_branches" name="sbr_bank_branches" type="checkbox" class="chk_sbr_overall form-check-input"> Bank Branches
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_bank_branches_view" name="sbr_bank_branches_view" type="checkbox" class="" <?php if ($row->bank_branches_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_bank_branches_add" name="sbr_bank_branches_add" type="checkbox" class="" <?php if ($row->bank_branches_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_bank_branches_edit" name="sbr_bank_branches_edit" type="checkbox" class="" <?php if ($row->bank_branches_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_bank_branches_delete" name="sbr_bank_branches_delete" type="checkbox" class="" <?php if ($row->bank_branches_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_tax_rates" name="sbr_tax_rates" type="checkbox" class="chk_sbr_overall form-check-input"> Tax Rates
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_tax_rates_view" name="sbr_tax_rates_view" type="checkbox" class="" <?php if ($row->tax_rates_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_tax_rates_add" name="sbr_tax_rates_add" type="checkbox" class="" <?php if ($row->tax_rates_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_tax_rates_edit" name="sbr_tax_rates_edit" type="checkbox" class="" <?php if ($row->tax_rates_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_tax_rates_delete" name="sbr_tax_rates_delete" type="checkbox" class="" <?php if ($row->tax_rates_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_credit_terms" name="sbr_credit_terms" type="checkbox" class="chk_sbr_overall form-check-input"> Credit Terms
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_credit_terms_view" name="sbr_credit_terms_view" type="checkbox" class="" <?php if ($row->credit_terms_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_credit_terms_add" name="sbr_credit_terms_add" type="checkbox" class="" <?php if ($row->credit_terms_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_credit_terms_edit" name="sbr_credit_terms_edit" type="checkbox" class="" <?php if ($row->credit_terms_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_credit_terms_delete" name="sbr_credit_terms_delete" type="checkbox" class="" <?php if ($row->credit_terms_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_mpesa_settings" name="sbr_mpesa_settings" type="checkbox" class="chk_sbr_overall form-check-input"> Mpesa Settings
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_mpesa_settings_view" name="sbr_mpesa_settings_view" type="checkbox" class="" <?php if ($row->mpesa_settings_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"><input id="sbr_mpesa_settings_edit" name="sbr_mpesa_settings_edit" type="checkbox" class="" <?php if ($row->mpesa_settings_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_pesapal_settings" name="sbr_pesapal_settings" type="checkbox" class="chk_sbr_overall form-check-input"> Pesapal Settings
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_pesapal_settings_view" name="sbr_pesapal_settings_view" type="checkbox" class="" <?php if ($row->pesapal_settings_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"><input id="sbr_pesapal_settings_edit" name="sbr_pesapal_settings_edit" type="checkbox" class="" <?php if ($row->pesapal_settings_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_email_accounts" name="sbr_email_accounts" type="checkbox" class="chk_sbr_overall form-check-input"> Email Accounts
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_email_accounts_view" name="sbr_email_accounts_view" type="checkbox" class="" <?php if ($row->email_accounts_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_email_accounts_add" name="sbr_email_accounts_add" type="checkbox" class="" <?php if ($row->email_accounts_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_email_accounts_edit" name="sbr_email_accounts_edit" type="checkbox" class="" <?php if ($row->email_accounts_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_email_accounts_delete" name="sbr_email_accounts_delete" type="checkbox" class="" <?php if ($row->email_accounts_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_bulk_sms_settings" name="sbr_bulk_sms_settings" type="checkbox" class="chk_sbr_overall form-check-input"> Bulk SMS Settings
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_bulk_sms_settings_view" name="sbr_bulk_sms_settings_view" type="checkbox" class="" <?php if ($row->bulk_sms_settings_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"><input id="sbr_bulk_sms_settings_edit" name="sbr_bulk_sms_settings_edit" type="checkbox" class="" <?php if ($row->bulk_sms_settings_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_prefixes" name="sbr_prefixes" type="checkbox" class="chk_sbr_overall form-check-input"> Prefixes
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_prefixes_view" name="sbr_prefixes_view" type="checkbox" class="" <?php if ($row->prefixes_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"><input id="sbr_prefixes_edit" name="sbr_prefixes_edit" type="checkbox" class="" <?php if ($row->prefixes_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_void_reasons" name="sbr_void_reasons" type="checkbox" class="chk_sbr_overall form-check-input"> Void Reasons
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_void_reasons_view" name="sbr_void_reasons_view" type="checkbox" class="" <?php if ($row->void_reasons_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_void_reasons_add" name="sbr_void_reasons_add" type="checkbox" class="" <?php if ($row->void_reasons_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_void_reasons_edit" name="sbr_void_reasons_edit" type="checkbox" class="" <?php if ($row->void_reasons_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_void_reasons_delete" name="sbr_void_reasons_delete" type="checkbox" class="" <?php if ($row->void_reasons_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_bitly_settings" name="sbr_bitly_settings" type="checkbox" class="chk_sbr_overall form-check-input"> Bitly Settings
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_bitly_settings_view" name="sbr_bitly_settings_view" type="checkbox" class="" <?php if ($row->bitly_settings_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"><input id="sbr_bitly_settings_edit" name="sbr_bitly_settings_edit" type="checkbox" class="" <?php if ($row->bitly_settings_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr class="table-active">
																<th colspan="7" class="font-weight-bold text-secondary font-13">
																	BE ~ Products
																</th>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_products" name="sbr_products" type="checkbox" class="chk_sbr_overall form-check-input"> Products
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_products_view" name="sbr_products_view" type="checkbox" class="" <?php if ($row->products_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_products_add" name="sbr_products_add" type="checkbox" class="" <?php if ($row->products_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_products_edit" name="sbr_products_edit" type="checkbox" class="" <?php if ($row->products_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_products_delete" name="sbr_products_delete" type="checkbox" class="" <?php if ($row->products_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_product_categories" name="sbr_product_categories" type="checkbox" class="chk_sbr_overall form-check-input"> Product Categories
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_product_categories_view" name="sbr_product_categories_view" type="checkbox" class="" <?php if ($row->product_categories_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_product_categories_add" name="sbr_product_categories_add" type="checkbox" class="" <?php if ($row->product_categories_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_product_categories_edit" name="sbr_product_categories_edit" type="checkbox" class="" <?php if ($row->product_categories_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_product_categories_delete" name="sbr_product_categories_delete" type="checkbox" class="" <?php if ($row->product_categories_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_units_of_measure" name="sbr_units_of_measure" type="checkbox" class="chk_sbr_overall form-check-input"> Units Of Measure
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_units_of_measure_view" name="sbr_units_of_measure_view" type="checkbox" class="" <?php if ($row->units_of_measure_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_units_of_measure_add" name="sbr_units_of_measure_add" type="checkbox" class="" <?php if ($row->units_of_measure_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_units_of_measure_edit" name="sbr_units_of_measure_edit" type="checkbox" class="" <?php if ($row->units_of_measure_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_units_of_measure_delete" name="sbr_units_of_measure_delete" type="checkbox" class="" <?php if ($row->units_of_measure_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_product_sizes" name="sbr_product_sizes" type="checkbox" class="chk_sbr_overall form-check-input"> Product Sizes
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_product_sizes_view" name="sbr_product_sizes_view" type="checkbox" class="" <?php if ($row->product_sizes_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_product_sizes_add" name="sbr_product_sizes_add" type="checkbox" class="" <?php if ($row->product_sizes_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_product_sizes_edit" name="sbr_product_sizes_edit" type="checkbox" class="" <?php if ($row->product_sizes_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_product_sizes_delete" name="sbr_product_sizes_delete" type="checkbox" class="" <?php if ($row->product_sizes_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_brands" name="sbr_brands" type="checkbox" class="chk_sbr_overall form-check-input"> Brands
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_brands_view" name="sbr_brands_view" type="checkbox" class="" <?php if ($row->brands_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_brands_add" name="sbr_brands_add" type="checkbox" class="" <?php if ($row->brands_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_brands_edit" name="sbr_brands_edit" type="checkbox" class="" <?php if ($row->brands_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_brands_delete" name="sbr_brands_delete" type="checkbox" class="" <?php if ($row->brands_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr class="table-active">
																<th colspan="7" class="font-weight-bold text-secondary font-13">
																	BE ~ Inventory
																</th>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_purchase_orders" name="sbr_purchase_orders" type="checkbox" class="chk_sbr_overall form-check-input"> Purchase Orders
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_purchase_orders_view" name="sbr_purchase_orders_view" type="checkbox" class="" <?php if ($row->purchase_orders_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_purchase_orders_add" name="sbr_purchase_orders_add" type="checkbox" class="" <?php if ($row->purchase_orders_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_purchase_orders_edit" name="sbr_purchase_orders_edit" type="checkbox" class="" <?php if ($row->purchase_orders_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_purchase_orders_delete" name="sbr_purchase_orders_delete" type="checkbox" class="" <?php if ($row->purchase_orders_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_purchase_orders_print" name="sbr_purchase_orders_print" type="checkbox" class="" <?php if ($row->purchase_orders_print == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_goods_received" name="sbr_goods_received" type="checkbox" class="chk_sbr_overall form-check-input"> Goods Received
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_goods_received_view" name="sbr_goods_received_view" type="checkbox" class="" <?php if ($row->goods_received_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_goods_received_add" name="sbr_goods_received_add" type="checkbox" class="" <?php if ($row->goods_received_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_goods_received_edit" name="sbr_goods_received_edit" type="checkbox" class="" <?php if ($row->goods_received_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_goods_received_delete" name="sbr_goods_received_delete" type="checkbox" class="" <?php if ($row->goods_received_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_goods_received_print" name="sbr_goods_received_print" type="checkbox" class="" <?php if ($row->goods_received_print == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_stock_adjustments" name="sbr_stock_adjustments" type="checkbox" class="chk_sbr_overall form-check-input"> Stock Adjustments
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_stock_adjustments_view" name="sbr_stock_adjustments_view" type="checkbox" class="" <?php if ($row->stock_adjustments_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_stock_adjustments_add" name="sbr_stock_adjustments_add" type="checkbox" class="" <?php if ($row->stock_adjustments_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_stock_adjustments_edit" name="sbr_stock_adjustments_edit" type="checkbox" class="" <?php if ($row->stock_adjustments_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_stock_adjustments_delete" name="sbr_stock_adjustments_delete" type="checkbox" class="" <?php if ($row->stock_adjustments_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_stock_adjustments_print" name="sbr_stock_adjustments_print" type="checkbox" class="" <?php if ($row->stock_adjustments_print == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_goods_received" name="sbr_goods_received" type="checkbox" class="chk_sbr_overall form-check-input"> Goods Returned
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_goods_returned_view" name="sbr_goods_returned_view" type="checkbox" class="" <?php if ($row->goods_returned_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_goods_returned_add" name="sbr_goods_returned_add" type="checkbox" class="" <?php if ($row->goods_returned_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_goods_returned_edit" name="sbr_goods_returned_edit" type="checkbox" class="" <?php if ($row->goods_returned_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_goods_returned_delete" name="sbr_goods_returned_delete" type="checkbox" class="" <?php if ($row->goods_returned_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_goods_returned_print" name="sbr_goods_returned_print" type="checkbox" class="" <?php if ($row->goods_returned_print == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_stock_transfers" name="sbr_stock_transfers" type="checkbox" class="chk_sbr_overall form-check-input"> Stock Transfers
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_stock_transfers_view" name="sbr_stock_transfers_view" type="checkbox" class="" <?php if ($row->stock_transfers_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_stock_transfers_add" name="sbr_stock_transfers_add" type="checkbox" class="" <?php if ($row->stock_transfers_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_stock_transfers_edit" name="sbr_stock_transfers_edit" type="checkbox" class="" <?php if ($row->stock_transfers_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_stock_transfers_delete" name="sbr_stock_transfers_delete" type="checkbox" class="" <?php if ($row->stock_transfers_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_stock_transfers_print" name="sbr_stock_transfers_print" type="checkbox" class="" <?php if ($row->stock_transfers_print == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
															</tr>
															<tr class="table-active">
																<th colspan="7" class="font-weight-bold text-secondary font-13">
																	BE ~ Sales
																</th>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_online_sales" name="sbr_online_sales" type="checkbox" class="chk_sbr_overall form-check-input"> Online Sales
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_online_sales_view" name="sbr_online_sales_view" type="checkbox" class="" <?php if ($row->online_sales_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"><input id="sbr_online_sales_print" name="sbr_online_sales_print" type="checkbox" class="" <?php if ($row->online_sales_print == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_online_sales_manage" name="sbr_online_sales_manage" type="checkbox" class="" <?php if ($row->online_sales_manage == 1){ echo 'checked'; } ?>></td>
															</tr>
															<tr class="table-active">
																<th colspan="7" class="font-weight-bold text-secondary font-13">
																	BE ~ Payments
																</th>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_paybill_payments" name="sbr_paybill_payments" type="checkbox" class="chk_sbr_overall form-check-input"> Paybill Payments
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_paybill_payments_view" name="sbr_paybill_payments_view" type="checkbox" class="" <?php if ($row->paybill_payments_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"><input id="sbr_paybill_payments_print" name="sbr_paybill_payments_print" type="checkbox" class="" <?php if ($row->paybill_payments_print == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_paybill_payments_manage" name="sbr_paybill_payments_manage" type="checkbox" class="" <?php if ($row->paybill_payments_manage == 1){ echo 'checked'; } ?>></td>
															</tr>
															<tr class="table-active">
																<th colspan="7" class="font-weight-bold text-secondary font-13">
																	BE ~ Customers
																</th>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_customers" name="sbr_customers" type="checkbox" class="chk_sbr_overall form-check-input"> Customers
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_customers_view" name="sbr_customers_view" type="checkbox" class="" <?php if ($row->customers_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_customers_add" name="sbr_customers_add" type="checkbox" class="" <?php if ($row->customers_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_customers_edit" name="sbr_customers_edit" type="checkbox" class="" <?php if ($row->customers_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_customers_delete" name="sbr_customers_delete" type="checkbox" class="" <?php if ($row->customers_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_customer_groups" name="sbr_customer_groups" type="checkbox" class="chk_sbr_overall form-check-input"> Customer Groups
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_customer_groups_view" name="sbr_customer_groups_view" type="checkbox" class="" <?php if ($row->customer_groups_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_customer_groups_add" name="sbr_customer_groups_add" type="checkbox" class="" <?php if ($row->customer_groups_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_customer_groups_edit" name="sbr_customer_groups_edit" type="checkbox" class="" <?php if ($row->customer_groups_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_customer_groups_delete" name="sbr_customer_groups_delete" type="checkbox" class="" <?php if ($row->customer_groups_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr class="table-active">
																<th colspan="7" class="font-weight-bold text-secondary font-13">
																	BE ~ Suppliers
																</th>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_suppliers" name="sbr_suppliers" type="checkbox" class="chk_sbr_overall form-check-input"> Suppliers
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_suppliers_view" name="sbr_suppliers_view" type="checkbox" class="" <?php if ($row->suppliers_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_suppliers_add" name="sbr_suppliers_add" type="checkbox" class="" <?php if ($row->suppliers_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_suppliers_edit" name="sbr_suppliers_edit" type="checkbox" class="" <?php if ($row->suppliers_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_suppliers_delete" name="sbr_suppliers_delete" type="checkbox" class="" <?php if ($row->suppliers_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr class="table-active">
																<th colspan="7" class="font-weight-bold text-secondary font-13">
																	BE ~ Affiliate Program
																</th>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_affiliate_accounts" name="sbr_affiliate_accounts" type="checkbox" class="chk_sbr_overall form-check-input"> Affiliate Accounts
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_affiliate_accounts_view" name="sbr_affiliate_accounts_view" type="checkbox" class="" <?php if ($row->affiliate_accounts_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"><input id="sbr_affiliate_accounts_delete" name="sbr_affiliate_accounts_delete" type="checkbox" class="" <?php if ($row->affiliate_accounts_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_affiliate_accounts_print" name="sbr_affiliate_accounts_print" type="checkbox" class="" <?php if ($row->affiliate_accounts_print == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_affiliate_accounts_manage" name="sbr_affiliate_accounts_manage" type="checkbox" class="" <?php if ($row->affiliate_accounts_manage == 1){ echo 'checked'; } ?>></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_affiliate_packages" name="sbr_affiliate_packages" type="checkbox" class="chk_sbr_overall form-check-input"> Affiliate Packages
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_affiliate_packages_view" name="sbr_affiliate_packages_view" type="checkbox" class="" <?php if ($row->affiliate_packages_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_affiliate_packages_add" name="sbr_affiliate_packages_add" type="checkbox" class="" <?php if ($row->affiliate_packages_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_affiliate_packages_edit" name="sbr_affiliate_packages_edit" type="checkbox" class="" <?php if ($row->affiliate_packages_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_affiliate_packages_delete" name="sbr_affiliate_packages_delete" type="checkbox" class="" <?php if ($row->affiliate_packages_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_affiliate_terms" name="sbr_affiliate_terms" type="checkbox" class="chk_sbr_overall form-check-input"> Affiliate Terms
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_affiliate_terms_view" name="sbr_affiliate_terms_view" type="checkbox" class="" <?php if ($row->affiliate_terms_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"><input id="sbr_affiliate_terms_edit" name="sbr_affiliate_terms_edit" type="checkbox" class="" <?php if ($row->affiliate_terms_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr class="table-active">
																<th colspan="7" class="font-weight-bold text-secondary font-13">
																	BE ~ Promotions
																</th>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_promo_codes" name="sbr_promo_codes" type="checkbox" class="chk_sbr_overall form-check-input"> Promo Codes
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_promo_codes_view" name="sbr_promo_codes_view" type="checkbox" class="" <?php if ($row->promo_codes_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_promo_codes_add" name="sbr_promo_codes_add" type="checkbox" class="" <?php if ($row->promo_codes_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_promo_codes_edit" name="sbr_promo_codes_edit" type="checkbox" class="" <?php if ($row->promo_codes_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_promo_codes_delete" name="sbr_promo_codes_delete" type="checkbox" class="" <?php if ($row->promo_codes_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr class="table-active">
																<th colspan="7" class="font-weight-bold text-secondary font-13">
																	BE ~ CMS Content
																</th>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_home_sliders" name="sbr_home_sliders" type="checkbox" class="chk_sbr_overall form-check-input"> Home Sliders
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_home_sliders_view" name="sbr_home_sliders_view" type="checkbox" class="" <?php if ($row->home_sliders_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_home_sliders_add" name="sbr_home_sliders_add" type="checkbox" class="" <?php if ($row->home_sliders_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_home_sliders_edit" name="sbr_home_sliders_edit" type="checkbox" class="" <?php if ($row->home_sliders_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_home_sliders_delete" name="sbr_home_sliders_delete" type="checkbox" class="" <?php if ($row->home_sliders_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_top_categories" name="sbr_top_categories" type="checkbox" class="chk_sbr_overall form-check-input"> Top Categories
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_top_categories_view" name="sbr_top_categories_view" type="checkbox" class="" <?php if ($row->top_categories_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_top_categories_add" name="sbr_top_categories_add" type="checkbox" class="" <?php if ($row->top_categories_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_top_categories_edit" name="sbr_top_categories_edit" type="checkbox" class="" <?php if ($row->top_categories_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_top_categories_delete" name="sbr_top_categories_delete" type="checkbox" class="" <?php if ($row->top_categories_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_featured_categories" name="sbr_featured_categories" type="checkbox" class="chk_sbr_overall form-check-input"> Featured Categories
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_featured_categories_view" name="sbr_featured_categories_view" type="checkbox" class="" <?php if ($row->featured_categories_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"><input id="sbr_featured_categories_edit" name="sbr_featured_categories_edit" type="checkbox" class="" <?php if ($row->featured_categories_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_promo_banners" name="sbr_promo_banners" type="checkbox" class="chk_sbr_overall form-check-input"> Promo Banners
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_promo_banners_view" name="sbr_promo_banners_view" type="checkbox" class="" <?php if ($row->promo_banners_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_promo_banners_add" name="sbr_promo_banners_add" type="checkbox" class="" <?php if ($row->promo_banners_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_promo_banners_edit" name="sbr_promo_banners_edit" type="checkbox" class="" <?php if ($row->promo_banners_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_promo_banners_delete" name="sbr_promo_banners_delete" type="checkbox" class="" <?php if ($row->promo_banners_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_testimonials" name="sbr_testimonials" type="checkbox" class="chk_sbr_overall form-check-input"> Testimonials
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_testimonials_view" name="sbr_testimonials_view" type="checkbox" class="" <?php if ($row->testimonials_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_testimonials_add" name="sbr_testimonials_add" type="checkbox" class="" <?php if ($row->testimonials_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_testimonials_edit" name="sbr_testimonials_edit" type="checkbox" class="" <?php if ($row->testimonials_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_testimonials_delete" name="sbr_testimonials_delete" type="checkbox" class="" <?php if ($row->testimonials_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_about_us" name="sbr_about_us" type="checkbox" class="chk_sbr_overall form-check-input"> About Us
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_about_us_view" name="sbr_about_us_view" type="checkbox" class="" <?php if ($row->about_us_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"><input id="sbr_about_us_edit" name="sbr_about_us_edit" type="checkbox" class="" <?php if ($row->about_us_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_how_to_shop" name="sbr_how_to_shop" type="checkbox" class="chk_sbr_overall form-check-input"> How to Shop
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_how_to_shop_view" name="sbr_how_to_shop_view" type="checkbox" class="" <?php if ($row->how_to_shop_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"><input id="sbr_how_to_shop_edit" name="sbr_how_to_shop_edit" type="checkbox" class="" <?php if ($row->how_to_shop_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_faqs" name="sbr_faqs" type="checkbox" class="chk_sbr_overall form-check-input"> FAQs
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_faqs_view" name="sbr_faqs_view" type="checkbox" class="" <?php if ($row->faqs_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_faqs_add" name="sbr_faqs_add" type="checkbox" class="" <?php if ($row->faqs_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_faqs_edit" name="sbr_faqs_edit" type="checkbox" class="" <?php if ($row->faqs_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_faqs_delete" name="sbr_faqs_delete" type="checkbox" class="" <?php if ($row->faqs_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_return_policy" name="sbr_return_policy" type="checkbox" class="chk_sbr_overall form-check-input"> Return Policy
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_return_policy_view" name="sbr_return_policy_view" type="checkbox" class="" <?php if ($row->return_policy_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"><input id="sbr_return_policy_edit" name="sbr_return_policy_edit" type="checkbox" class="" <?php if ($row->return_policy_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_privacy_policy" name="sbr_privacy_policy" type="checkbox" class="chk_sbr_overall form-check-input"> Privacy Policy
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_privacy_policy_view" name="sbr_privacy_policy_view" type="checkbox" class="" <?php if ($row->privacy_policy_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"><input id="sbr_privacy_policy_edit" name="sbr_privacy_policy_edit" type="checkbox" class="" <?php if ($row->privacy_policy_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_terms_and_conditions" name="sbr_terms_and_conditions" type="checkbox" class="chk_sbr_overall form-check-input"> Terms and Conditions
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_terms_and_conditions_view" name="sbr_terms_and_conditions_view" type="checkbox" class="" <?php if ($row->terms_and_conditions_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"><input id="sbr_terms_and_conditions_edit" name="sbr_terms_and_conditions_edit" type="checkbox" class="" <?php if ($row->terms_and_conditions_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr class="table-active">
																<th colspan="7" class="font-weight-bold text-secondary font-13">
																	BE ~ Blog
																</th>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_blog" name="sbr_blog" type="checkbox" class="chk_sbr_overall form-check-input"> Blog Articles
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_blog_view" name="sbr_blog_view" type="checkbox" class="" <?php if ($row->blog_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_blog_add" name="sbr_blog_add" type="checkbox" class="" <?php if ($row->blog_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_blog_edit" name="sbr_blog_edit" type="checkbox" class="" <?php if ($row->blog_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_blog_delete" name="sbr_blog_delete" type="checkbox" class="" <?php if ($row->blog_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_blog_categories" name="sbr_blog_categories" type="checkbox" class="chk_sbr_overall form-check-input"> Blog Categories
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_blog_categories_view" name="sbr_blog_categories_view" type="checkbox" class="" <?php if ($row->blog_categories_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_blog_categories_add" name="sbr_blog_categories_add" type="checkbox" class="" <?php if ($row->blog_categories_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_blog_categories_edit" name="sbr_blog_categories_edit" type="checkbox" class="" <?php if ($row->blog_categories_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_blog_categories_delete" name="sbr_blog_categories_delete" type="checkbox" class="" <?php if ($row->blog_categories_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr class="table-active">
																<th colspan="7" class="font-weight-bold text-secondary font-13">
																	BE ~ User Management
																</th>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_system_users" name="sbr_system_users" type="checkbox" class="chk_sbr_overall form-check-input"> System Users
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_system_users_view" name="sbr_system_users_view" type="checkbox" class="" <?php if ($row->system_users_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_system_users_add" name="sbr_system_users_add" type="checkbox" class="" <?php if ($row->system_users_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_system_users_edit" name="sbr_system_users_edit" type="checkbox" class="" <?php if ($row->system_users_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_system_users_delete" name="sbr_system_users_delete" type="checkbox" class="" <?php if ($row->system_users_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_user_roles" name="sbr_user_roles" type="checkbox" class="chk_sbr_overall form-check-input"> User Roles
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_user_roles_view" name="sbr_user_roles_view" type="checkbox" class="" <?php if ($row->user_roles_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_user_roles_add" name="sbr_user_roles_add" type="checkbox" class="" <?php if ($row->user_roles_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_user_roles_edit" name="sbr_user_roles_edit" type="checkbox" class="" <?php if ($row->user_roles_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_user_roles_delete" name="sbr_user_roles_delete" type="checkbox" class="" <?php if ($row->user_roles_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>
															<tr>
																<th class="font-weight-semibold">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="sbr_departments" name="sbr_departments" type="checkbox" class="chk_sbr_overall form-check-input"> Departments
																		</label>
																	</div>
																</th>
																<td class="text-center"><input id="sbr_departments_view" name="sbr_departments_view" type="checkbox" class="" <?php if ($row->departments_view == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_departments_add" name="sbr_departments_add" type="checkbox" class="" <?php if ($row->departments_add == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_departments_edit" name="sbr_departments_edit" type="checkbox" class="" <?php if ($row->departments_edit == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"><input id="sbr_departments_delete" name="sbr_departments_delete" type="checkbox" class="" <?php if ($row->departments_delete == 1){ echo 'checked'; } ?>></td>
																<td class="text-center"></td>
																<td class="text-center"></td>
															</tr>


														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>

									<div class="clearfix"></div>
									<div class="col-md-12">
										<hr>
										<div class="text-right">
											<button type="submit" id="btn_edit_user_role" class="btn btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE USER ROLE</button>
										</div>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
