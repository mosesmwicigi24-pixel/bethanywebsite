		<!-- Main content -->
		<div class="content-wrapper">

			<?php if (!isset($customer)): ?>

				<!-- Page header -->
				<div class="page-header">
					<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
						<div class="d-flex">
							<div class="breadcrumb">
								<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
								<a href="<?php echo base_url();?>be/customers" class="breadcrumb-item">Customers</a>
								<span class="breadcrumb-item active">New Customer</span>
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
									<i class="icon-plus-circle2"></i> NEW CUSTOMER
									<a href="<?php echo base_url();?>be/customers" class="btn btn-sm bg-success-400 text-success-400 border-success-400 float-right"><i class="icon-arrow-left15"></i> Back to Customers</a>
								</h5>
							</div>
						</div>
					</div>

					<form id="frm_add_customer" name="frm_add_customer" method="post" onsubmit="return save_customer();" autocomplete="false">
						<fieldset <?php if ($sbr_customers_add == false){ echo 'disabled'; } ?>>
							<div id="div_add_error" class="alert alert-danger display-none font-13"></div>
	           				<div id="div_add_success" class="alert alert-success display-none font-13"></div>

							<div class="row">
								<div class="col-md-4">
									<div class="card rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h6 class="card-title font-weight-600 text-success text-uppercase">Contact Information</h6>			
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
														<label>Customer Group</label>
														<select id="customer_group_id" name="customer_group_id" class="form-control form-control-select2" data-placeholder="Select Customer Group" data-fouc>
															<option value="">Select Customer Group</option>
															<?php foreach ($customer_groups as $row2): ?>
																<option value="<?php echo $row2->customer_group_id; ?>"><?php echo $row2->customer_group_name; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
													<div class="col-md-6">
														<label>Customer Code <small>(Optional)</small></label>
														<input id="customer_code name="customer_code" type="text" placeholder="" class="form-control">
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
															<input id="opening_balance" name="opening_balance" type="number" placeholder="" value="0" class="form-control" min="0">
														</div>
													</div>
													<div class="col-sm-6">
														<label>Credit Limit</label>
														<div class="input-group">
															<span class="input-group-prepend">
																<span class="input-group-text"><?php echo $default_currency; ?></span>
															</span>
															<input id="credit_limit" name="credit_limit" type="number" placeholder="" value="0" class="form-control" min="0">
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
																<input id="loyalty_enrolled" name="loyalty_enrolled" type="checkbox" class="form-check-input-switchery" data-fouc>
															</label>
														</div>
													</div>
													<div class="col-md-6">
														<label>Enrollment Date</label>
														<input id="loyalty_enrollment_date" name="loyalty_enrollment_date" type="text" placeholder="" class="form-control pickadate" autocomplete="off">
													</div>
												</div>
											</div>
											<div class="form-group mb-3">
												<div class="row">
													<div class="col-sm-12">
														<label>Referred By</label>
														<select id="reference_customer_id" name="reference_customer_id" data-placeholder="Select Customer" class="form-control select" data-fouc>
															<option value="">Select Customer</option>
															<?php foreach ($customers as $row2): ?>
																<option value="<?php echo $row2->customer_id; ?>"><?php echo $row2->first_name . ' ' . $row2->last_name . ' (' . $row2->email_address . ')'; ?></option>
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
														<input id="billing_first_name" name="billing_first_name" type="text" placeholder="" class="form-control">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-12">
														<label>Last Name</label>
														<input id="billing_last_name" name="billing_last_name" type="text" placeholder="" class="form-control">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-12">
														<label>Email Address</label>
														<input id="billing_email_address" name="billing_email_address" type="email" placeholder="" class="form-control">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-12">
														<label>Phone Number</label>
														<input id="billing_phone_number" name="billing_phone_number" type="text" placeholder="" class="form-control">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-12">
														<label>Street Address</label>
														<input id="billing_street_address" name="billing_street_address" type="text" placeholder="" class="form-control">
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
																<option value="<?php echo $row2->country_id; ?>"><?php echo $row2->country_name; ?></option>
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
														<input id="billing_postal_code" name="billing_postal_code" type="text" placeholder="" class="form-control">
													</div>
												</div>
											</div>
											<hr>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-12">
														<div class="form-check form-check-left">
															<label class="form-check-label text-primary font-weight-bold font-14">															
																<input id="customer_use_same_shipping_address" name="customer_use_same_shipping_address" type="checkbox" class="form-check-input">
																Use Same Shipping Information
															</label>
														</div>
													</div>
												</div>
												
											</div>
										</div>
									</div>
									
								</div>
								<div id="div_customer_shipping_information" class="col-md-3">
									<div class="card rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h6 class="card-title font-weight-600 text-success text-uppercase">Shipping Information</h6>			
										</div>
								
										<div class="card-body">
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-12">
														<label>First Name</label>
														<input id="shipping_first_name" name="shipping_first_name" type="text" placeholder="" class="form-control">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-12">
														<label>Last Name</label>
														<input id="shipping_last_name" name="shipping_last_name" type="text" placeholder="" class="form-control">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-12">
														<label>Email Address</label>
														<input id="shipping_email_address" name="shipping_email_address" type="email" placeholder="" class="form-control">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-12">
														<label>Phone Number</label>
														<input id="shipping_phone_number" name="shipping_phone_number" type="text" placeholder="" class="form-control">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-12">
														<label>Street Address</label>
														<input id="shipping_street_address" name="shipping_street_address" type="text" placeholder="" class="form-control">
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
																<option value="<?php echo $row2->country_id; ?>"><?php echo $row2->country_name; ?></option>
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
														<input id="shipping_postal_code" name="shipping_postal_code" type="text" placeholder="" class="form-control">
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
														<label>Select Profile Picture</label>
														<input id="profile_picture" name="profile_picture" type="file" class="form-control">
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
										<button type="submit" id="btn_add_customer" class="btn btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> SAVE CUSTOMER</button>
									</div>
								</div>
							</div>
						</fieldset>
					</form>
				</div>

			<?php else: ?>
				<?php foreach ($customer as $row): ?>
					<!-- Page header -->
					<div class="page-header">
						<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
							<div class="d-flex">
								<div class="breadcrumb">
									<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
									<a href="<?php echo base_url();?>be/customers" class="breadcrumb-item">Customers</a>
									<span class="breadcrumb-item active">Edit Customer (<?php echo $row->first_name . ' ' . $row->last_name; ?>)</span>
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
										<i class="icon-pencil6"></i> Edit Customer (<?php echo $row->first_name . ' ' . $row->last_name; ?>)
										<a href="<?php echo base_url();?>be/customers" class="btn btn-sm bg-success-400 text-success-400 border-success-400 float-right"><i class="icon-arrow-left15"></i> Back to Customers</a>
									</h5>
								</div>
							</div>
						</div>

						<form id="frm_edit_customer" name="frm_edit_customer" method="post" onsubmit="return update_customer();" autocomplete="false">

							<fieldset <?php if ($sbr_customers_edit == false){ echo 'disabled'; } ?>>

								<div id="div_edit_error" class="alert alert-danger display-none font-13"></div>
	               				<div id="div_edit_success" class="alert alert-success display-none font-13"></div>

								<div class="row">

									<div class="col-md-4">
										<div class="card rounded-top-0">
											<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
												<h6 class="card-title font-weight-600 text-success text-uppercase">Contact Information</h6>			
											</div>
									
											<div class="card-body">

				                   				<input type="hidden" id="customer_id" name="customer_id" value="<?php echo $row->customer_id; ?>">

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
															<label>Customer Group</label>
															<select id="customer_group_id" name="customer_group_id" class="form-control form-control-select2" data-placeholder="Select Customer Group" data-fouc>
																<option value="">Select Customer Group</option>
																<?php foreach ($customer_groups as $row2): ?>
																	<option value="<?php echo $row2->customer_group_id; ?>" <?php if ($row2->customer_group_id == $row->customer_group_id){ echo 'selected'; } ?>><?php echo $row2->customer_group_name; ?></option>
																<?php endforeach; ?>
															</select>
														</div>
														<div class="col-md-6">
															<label>Customer Code <small>(Optional)</small></label>
															<input id="customer_code name="customer_code" type="text" placeholder="" class="form-control" value="<?php echo $row->customer_code; ?>">
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
															<select id="reference_customer_id" name="reference_customer_id" data-placeholder="Select Customer" class="form-control select" data-fouc>
																<option value="">Select Customer</option>
																<?php foreach ($customers as $row2): ?>
																	<?php if ($row2->customer_id != $row->customer_id): ?>
																		<option value="<?php echo $row2->customer_id; ?>" <?php if ($row2->customer_id == $row->reference_customer_id){ echo 'selected'; } ?>><?php echo $row2->first_name . ' ' . $row2->last_name . ' (' . $row2->email_address . ')'; ?></option>
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
																	<input id="customer_use_same_shipping_address" name="customer_use_same_shipping_address" type="checkbox" class="form-check-input">
																	Use Same Shipping Information
																</label>
															</div>
														</div>
													</div>
													
												</div>
											</div>
										</div>
										
									</div>
									<div id="div_customer_shipping_information" class="col-md-3">
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
															<?php if($row->profile_picture != '' && file_exists("./uploads/customer_profile_pictures/" . $row->profile_picture)): ?>
																<div class="card-img-actions d-inline-block mb-2">
																	<img class="card-img img-fluid" src="<?php echo base_url(); ?>uploads/customer_profile_pictures/<?php echo $row->profile_picture; ?>" alt="">
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
											<button type="submit" id="btn_edit_customer" class="btn btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE CUSTOMER</button>
										</div>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>
