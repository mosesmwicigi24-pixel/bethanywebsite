		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<span class="breadcrumb-item active">Suppliers</span>
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
								<h5 class="card-title font-weight-bold"><i class="icon-users"></i> Suppliers</h5>
							</div>

							<div class="card-body">
								<div class="row">
									<div class="col-md-6">
										<div class="card rounded-top-0">
											<div class="card-header alpha-grey text-success-800 header-elements-inline pt-2 pb-2">
												<h6 class="card-title text-uppercase"><i class="icon-plus-circle2 mr-1"></i> New Supplier</h6>			
											</div>
											<div class="card-body">
												<form id="frm_add_supplier" name="frm_add_supplier" method="post" onsubmit="return save_supplier();" autocomplete="off" enctype="multipart/form-data">
													<fieldset <?php if ($sbr_suppliers_add == false){ echo 'disabled'; } ?>>
														<div id="div_add_error" class="alert alert-danger display-none font-13"></div>
					                   					<div id="div_add_success" class="alert alert-success display-none font-13"></div>

					                   					<div class="row">
															<div class="col-md-7">
																<div class="card rounded-top-0">
																	<div class="card-header alpha-grey text-primary header-elements-inline pt-2 pb-2">
																<h6 class="card-title font-weight-bold">Primary Information</h6>			
																	</div>
																	<div class="card-body">
																		<div class="form-group mb-2">
																			<div class="row">
																				<div class="col-sm-8">
																					<label>Supplier Name <span class="text-danger">*</span></label>
																					<input id="add_supplier_name" name="supplier_name" type="text" placeholder="" class="form-control" autocomplete="off">
																				</div>
																				<div class="col-sm-4">
																					<label>Supplier Code</label>
																					<input id="add_supplier_code" name="supplier_code" type="text" placeholder="" class="form-control" autocomplete="off">
																				</div>
																			</div>
																		</div>
																		<div class="form-group mb-2">
																			<div class="row">
																				<div class="col-sm-6">
																					<label>Phone Number</label>
																					<input id="add_phone_number" name="phone_number" type="text" placeholder="" class="form-control" autocomplete="off">
																				</div>
																				<div class="col-sm-6">
																					<label>Email Address</label>
																					<input id="add_email_address" name="email_address" type="email" placeholder="" class="form-control" autocomplete="off">
																				</div>
																			</div>
																		</div>
																		<div class="form-group mb-2">
																			<div class="row">
																				<div class="col-sm-12">
																					<label>Website</label>
																					<input id="add_website" name="website" type="text" placeholder="" class="form-control" autocomplete="off">
																				</div>
																			</div>
																		</div>
																		<div class="form-group mb-2">
																			<div class="row">
																				<div class="col-sm-6">
																					<label>Postal Address</label>
																					<input id="add_postal_address" name="postal_address" type="text" placeholder="" class="form-control" autocomplete="off">
																				</div>
																				<div class="col-sm-6">
																					<label>Postal Code </label>
																					<input id="add_postal_code" name="postal_code" type="text" placeholder="" class="form-control" autocomplete="off">
																				</div>
																			</div>
																		</div>
																		<div class="row">
																			<div class="col-sm-6">
																				<div class="form-group mb-2">
																					<label>Country</label>
																					<select id="add_supplier_country_id" name="country_id" class="form-control select" data-placeholder="Select Country" data-fouc>
																						<option value="">Select Country</option>
																						<?php foreach ($countries as $row): ?>
																							<option value="<?php echo $row->country_id; ?>"><?php echo $row->country_name; ?></option>
																						<?php endforeach; ?>
																					</select>
																				</div>
																			</div>
																			<div class="col-sm-6">
																				<label>City</label>
																				<div class="form-group form-group-feedback form-group-feedback-right mb-2">
																					<select id="add_supplier_city_id" name="city_id" class="form-control select" data-placeholder="Select City" data-fouc>
																						<option value="">Select City</option>
																					</select>
																					<div id="add_supplier_city_loader" class="form-control-feedback display-none">
																						<i class="icon-spinner2 spinner text-success"></i>
																					</div>
																				</div>
																			</div>
																		</div>
																		<div class="form-group mb-2">
																			<div class="row">
																				<div class="col-sm-12">
																					<label>Supplier Note</label>
																					<textarea id="add_supplier_note" name="supplier_note" rows="3" cols="3" class="form-control" placeholder=""></textarea>
																				</div>
																			</div>
																		</div>

																	</div>
																</div>
															</div>
															<div class="col-md-5">
																<div class="card rounded-top-0">
																	<div class="card-header alpha-grey text-primary header-elements-inline pt-2 pb-2">
																<h6 class="card-title font-weight-bold">Contact Person</h6>			
																	</div>
																	<div class="card-body">
																		<div class="form-group mb-2">
																			<div class="row">
																				<div class="col-sm-12">
																					<label>First Name</label>
																					<input id="add_contact_person_first_name" name="contact_person_first_name" type="text" placeholder="" class="form-control" autocomplete="off">
																				</div>
																			</div>
																		</div>
																		<div class="form-group mb-2">
																			<div class="row">
																				<div class="col-sm-12">
																					<label>Last Name</label>
																					<input id="add_contact_person_last_name" name="contact_person_last_name" type="text" placeholder="" class="form-control" autocomplete="off">
																				</div>
																			</div>
																		</div>
																		<div class="form-group mb-2">
																			<div class="row">
																				<div class="col-sm-12">
																					<label>Mobile Number</label>
																					<input id="add_contact_person_mobile_number" name="contact_person_mobile_number" type="text" placeholder="" class="form-control" autocomplete="off">
																				</div>
																			</div>
																		</div>
																		<div class="form-group mb-2">
																			<div class="row">
																				<div class="col-sm-12">
																					<label>Email Address</label>
																					<input id="add_contact_person_email_address" name="contact_person_email_address" type="email" placeholder="" class="form-control" autocomplete="off">
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
																				<div class="col-sm-4">
																					<label>Sort Key <span class="error">*</span></label>
																					<input id="add_sort_key" name="sort_key" type="number" class="form-control" min="0" value="0">
																				</div>
																			</div>
																		</div>


																	</div>
																</div>
															</div>
														</div>
														<div class="text-right">
															<button id="btn_add_supplier" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE</button>
														</div>
													</fieldset>
												</form>
											</div>
										</div>
									</div>
									<!-- <div class="col-md-1"></div> -->
									<div class="col-md-6">
										<form method="post" class="form">
											<div id="suppliers_div" style="min-height: 400px;">
									
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-2 font-weight-600">
														<select id="sl_suppliers_bulk_action" name="sl_suppliers_bulk_action" class="form-control form-control-select2" data-placeholder="Bulk Action" data-fouc>
															<option value="">Bulk Action</option>
															<option value="Delete">Delete</option>
														</select>
													</div>
													<div class="col-sm-2">
														<button id="btn_suppliers_bulk_action" type="button" class="btn btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> APPLY</button>
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



			<!-- Edit modal form -->
			<div id="modal_edit_supplier" class="modal fade">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-success-400 text-uppercase"><i class="icon-pencil6"></i> Edit Supplier</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_edit_supplier" name="frm_edit_supplier" method="post" onsubmit="return update_supplier();">
							<fieldset <?php if ($sbr_suppliers_edit == false){ echo 'disabled'; } ?>>
								<div class="modal-body">

									<div id="div_edit_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_edit_success" class="alert alert-success display-none font-13"></div>

	                   				<input id="edit_supplier_id" name="supplier_id" type="hidden" placeholder="" class="form-control">

		           					<div class="row">
										<div class="col-md-7">
											<div class="card rounded-top-0">
												<div class="card-header alpha-grey text-success-800 header-elements-inline pt-2 pb-2">
													<h6 class="card-title text-uppercase">Primary Information</h6>			
												</div>
												<div class="card-body">
													<div class="form-group mb-2">
														<div class="row">
															<div class="col-sm-8">
																<label>Supplier Name <span class="text-danger">*</span></label>
																<input id="edit_supplier_name" name="supplier_name" type="text" placeholder="" class="form-control" autocomplete="off">
															</div>
															<div class="col-sm-4">
																<label>Supplier Code</label>
																<input id="edit_supplier_code" name="supplier_code" type="text" placeholder="" class="form-control" autocomplete="off">
															</div>
														</div>
													</div>
													<div class="form-group mb-2">
														<div class="row">
															<div class="col-sm-6">
																<label>Phone Number</label>
																<input id="edit_phone_number" name="phone_number" type="text" placeholder="" class="form-control" autocomplete="off">
															</div>
															<div class="col-sm-6">
																<label>Email Address</label>
																<input id="edit_email_address" name="email_address" type="email" placeholder="" class="form-control" autocomplete="off">
															</div>
														</div>
													</div>
													<div class="form-group mb-2">
														<div class="row">
															<div class="col-sm-12">
																<label>Website</label>
																<input id="edit_website" name="website" type="text" placeholder="" class="form-control" autocomplete="off">
															</div>
														</div>
													</div>
													<div class="form-group mb-2">
														<div class="row">
															<div class="col-sm-6">
																<label>Postal Address</label>
																<input id="edit_postal_address" name="postal_address" type="text" placeholder="" class="form-control" autocomplete="off">
															</div>
															<div class="col-sm-6">
																<label>Postal Code </label>
																<input id="edit_postal_code" name="postal_code" type="text" placeholder="" class="form-control" autocomplete="off">
															</div>
														</div>
													</div>
													<div class="row">
														<div class="col-sm-6">
															<div class="form-group mb-2">
																<label>Country</label>
																<select id="edit_supplier_country_id" name="country_id" class="form-control select" data-placeholder="Select Country" data-fouc>
																	<option value="">Select Country</option>
																	<?php foreach ($countries as $row): ?>
																		<option value="<?php echo $row->country_id; ?>"><?php echo $row->country_name; ?></option>
																	<?php endforeach; ?>
																</select>
															</div>
														</div>
														<div class="col-sm-6">
															<label>City</label>
															<div class="form-group form-group-feedback form-group-feedback-right mb-2">
																<select id="edit_supplier_city_id" name="city_id" class="form-control select" data-placeholder="Select City" data-fouc>
																	<option value="">Select City</option>
																</select>
																<div id="edit_supplier_city_loader" class="form-control-feedback display-none">
																	<i class="icon-spinner2 spinner text-success"></i>
																</div>
															</div>
														</div>
													</div>
													<div class="form-group mb-2">
														<div class="row">
															<div class="col-sm-12">
																<label>Supplier Note</label>
																<textarea id="edit_supplier_note" name="supplier_note" rows="3" cols="3" class="form-control" placeholder=""></textarea>
															</div>
														</div>
													</div>

												</div>
											</div>
										</div>
										<div class="col-md-5">
											<div class="card rounded-top-0">
												<div class="card-header alpha-grey text-success-800 header-elements-inline pt-2 pb-2">
													<h6 class="card-title text-uppercase">Contact Person</h6>			
												</div>
												<div class="card-body">
													<div class="form-group mb-2">
														<div class="row">
															<div class="col-sm-12">
																<label>First Name</label>
																<input id="edit_contact_person_first_name" name="contact_person_first_name" type="text" placeholder="" class="form-control" autocomplete="off">
															</div>
														</div>
													</div>
													<div class="form-group mb-2">
														<div class="row">
															<div class="col-sm-12">
																<label>Last Name</label>
																<input id="edit_contact_person_last_name" name="contact_person_last_name" type="text" placeholder="" class="form-control" autocomplete="off">
															</div>
														</div>
													</div>
													<div class="form-group mb-2">
														<div class="row">
															<div class="col-sm-12">
																<label>Mobile Number</label>
																<input id="edit_contact_person_mobile_number" name="contact_person_mobile_number" type="text" placeholder="" class="form-control" autocomplete="off">
															</div>
														</div>
													</div>
													<div class="form-group mb-2">
														<div class="row">
															<div class="col-sm-12">
																<label>Email Address</label>
																<input id="edit_contact_person_email_address" name="contact_person_email_address" type="email" placeholder="" class="form-control" autocomplete="off">
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
																		<input type="radio" class="form-check-input" id="edit_is_active_1" name="is_active" value="1" checked>
																	</label>
																</div>

																<div class="form-check form-check-inline form-check-right">
																	<label class="form-check-label font-weight-semibold">
																		Inactive
																		<input type="radio" class="form-check-input" id="edit_is_active_0" name="is_active" value="0">
																	</label>
																</div>
															</div>
															<div class="col-sm-4">
																<label>Sort Key <span class="error">*</span></label>
																<input id="edit_sort_key" name="sort_key" type="number" class="form-control" min="0" value="0">
															</div>
														</div>
													</div>


												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">								
									<button type="submit" id="btn_update_supplier" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CLOSE</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				load_suppliers();
			</script>
	