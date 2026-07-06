					<div class="w-100">
						<div class="row">
							<div class="col-lg-12">
								<div class="card rounded-top-0">
									<div class="card-header bg-transparent header-elements-inline p-2">
										<h5 class="card-title font-weight-bold"><i class="icon-office mr-2"></i> Outlets</h5>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-lg-12">
												
												<div class="row">
													<div class="col-md-4">
														<div class="card rounded-top-0">
															<div class="card-header alpha-grey text-primary header-elements-inline pt-2 pb-2">
																<h6 class="card-title font-weight-bold"><i class="icon-plus-circle2 mr-1"></i> New Outlet</h6>			
															</div>
															<div class="card-body">
																<form id="frm_add_outlet" name="frm_add_outlet" method="post" onsubmit="return save_outlet();" autocomplete="off" enctype="multipart/form-data">

																	<div id="div_add_error" class="alert alert-danger display-none font-13"></div>
								                   					<div id="div_add_success" class="alert alert-success display-none font-13"></div>
								                   					
								                   					<fieldset <?php if ($sbr_outlets_add == false){ echo 'disabled'; } ?>>
									                   					<div class="form-group">
										                   					<div class="row">
																				<div class="col-md-9">
																					<div class="form-group">
																						<label>Outlet Name <span class="text-danger">*</span></label>
																						<input id="add_outlet_name" name="outlet_name" type="text" class="form-control" placeholder="">
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label class="d-block text-primary">Main Outlet?</label>
																						<div class="form-check">
																							<label class="form-check-label font-weight-semibold">
																								<input id="add_is_main" name="is_main" type="checkbox" class="form-check-input">
																								Yes <i class="icon-help text-warning" data-popup="tooltip" title="This is the central /main outlet. PS: There can only be one main outlet, setting this as main will unset any other outlet that had been defined as main. So be careful!" data-placement="top"></i>
																							</label>
																						</div>
																					</div>
																				</div>																			
																			</div>	
																		</div>
																		<div class="form-group mb-2">
										                   					<div class="row">
																				<div class="col-md-9">
																					<div class="form-group">
																						<label>Physical Location <span class="text-danger">*</span></label>
																						<textarea id="add_outlet_physical_location" name="outlet_physical_location" rows="3" cols="3" class="form-control"></textarea>
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label>Sort Key</label>
																						<input id="add_sort_key" name="sort_key" type="number" class="form-control" placeholder="" value="0">
																					</div>
																				</div>
																			</div>	
																		</div>
																		<div class="form-group mb-2">
										                   					<div class="row">
																				<div class="col-md-9">
																					<div class="form-group">
																						<label>Description</label>
																						<textarea id="add_outlet_description" name="outlet_description" rows="3" cols="3" class="form-control"></textarea>
																					</div>
																				</div>
																				<div class="col-md-3">
																					<div class="form-group">
																						<label class="d-block">Status <span class="error">*</span></label>
																						<div class="form-check">
																							<label class="form-check-label font-weight-semibold">
																								<input type="radio" class="form-check-input" id="add_is_active_1" name="is_active" value="1" checked>
																								Active
																							</label>
																						</div>

																						<div class="form-check">
																							<label class="form-check-label font-weight-semibold">
																								<input type="radio" class="form-check-input" id="add_is_active_0" name="is_active" value="0">
																								Inactive
																							</label>
																						</div>
																					</div>
																				</div>
																			</div>	
																		</div>	
																		<div class="form-group mb-2">
										                   					<div class="row">
																				<div class="col-md-6">
																					<div class="form-group">
																						<label>Contact Person <span class="text-danger">*</span></label>
																						<input id="add_outlet_contact_person" name="outlet_contact_person" type="text" class="form-control" placeholder="">
																					</div>
																				</div>
																				<div class="col-md-6">
																					<div class="form-group">
																						<label>Phone Number <span class="text-danger">*</span></label>
																						<input id="add_outlet_phone_number" name="outlet_phone_number" type="text" class="form-control" placeholder="">
																					</div>
																				</div>
																			</div>	
																		</div>
																		<div class="form-group mb-2">
										                   					<div class="row">
																				<div class="col-md-12">
																					<div class="form-group">
																						<label>Email Address</label>
																						<input id="add_outlet_email_address" name="outlet_email_address" type="text" class="form-control" placeholder="">
																					</div>
																				</div>
																			</div>	
																		</div>
																								
																		<div class="text-right">
																			<button id="btn_add_outlet" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE</button>
																		</div>
																	</fieldset>
																</form>
															</div>
														</div>
													</div>
													<!-- <div class="col-md-1"></div> -->
													<div class="col-md-8">
														<form method="post" class="form">
															<div id="outlets_div" style="min-height: 500px;">
													
															</div>
															<div class="form-group mb-2">
																<div class="row">
																	<div class="col-sm-2 font-weight-600">
																		<select id="sl_outlets_bulk_action" name="sl_outlets_bulk_action" class="form-control form-control-select2" data-placeholder="Bulk Action" data-fouc>
																			<option value="">Bulk Action</option>
																			<option value="Delete">Delete</option>
																		</select>
																	</div>
																	<div class="col-sm-2">
																		<button id="btn_outlets_bulk_action" type="button" class="btn btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> APPLY</button>
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
						</div>



					</div>
				</div>
			</div>

			<div id="modal_edit_outlet" class="modal fade" tabindex="-1">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-pencil6"></i> Edit Outlet</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_edit_outlet" name="frm_edit_outlet" method="post" onsubmit="return update_outlet();">
							<fieldset <?php if ($sbr_outlets_edit == false){ echo 'disabled'; } ?>>
							<div class="modal-body">
								<div id="div_edit_error" class="alert alert-danger display-none font-13"></div>
                   				<div id="div_edit_success" class="alert alert-success display-none font-13"></div>

                   				<input id="edit_outlet_id" name="outlet_id" type="hidden" placeholder="" class="form-control">
                   				
	                   				<div class="form-group">
	                   					<div class="row">
											<div class="col-md-9">
												<div class="form-group">
													<label>Outlet Name <span class="text-danger">*</span></label>
													<input id="edit_outlet_name" name="outlet_name" type="text" class="form-control" placeholder="">
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label class="d-block text-primary">Main Outlet?</label>
													<div class="form-check">
														<label class="form-check-label font-weight-semibold">
															<input id="edit_is_main" name="is_main" type="checkbox" class="form-check-input">
															Yes <i class="icon-help text-warning" data-popup="tooltip" title="This is the central /main outlet. PS: There can only be one main outlet, setting this as main will unset any other outlet that had been defined as main. So be careful!" data-placement="top"></i>
														</label>
													</div>
												</div>
											</div>																			
										</div>	
									</div>
									<div class="form-group mb-2">
	                   					<div class="row">
											<div class="col-md-9">
												<div class="form-group">
													<label>Physical Location <span class="text-danger">*</span></label>
													<textarea id="edit_outlet_physical_location" name="outlet_physical_location" rows="3" cols="3" class="form-control"></textarea>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label>Sort Key</label>
													<input id="edit_sort_key" name="sort_key" type="number" class="form-control" placeholder="" value="0">
												</div>
											</div>
										</div>	
									</div>
									<div class="form-group mb-2">
	                   					<div class="row">
											<div class="col-md-9">
												<div class="form-group">
													<label>Description</label>
													<textarea id="edit_outlet_description" name="outlet_description" rows="3" cols="3" class="form-control"></textarea>
												</div>
											</div>
											<div class="col-md-3">
												<div class="form-group">
													<label class="d-block">Status <span class="error">*</span></label>
													<div class="form-check">
														<label class="form-check-label font-weight-semibold">
															<input type="radio" class="form-check-input" id="edit_is_active_1" name="is_active" value="1">
															Active
														</label>
													</div>

													<div class="form-check">
														<label class="form-check-label font-weight-semibold">
															<input type="radio" class="form-check-input" id="edit_is_active_0" name="is_active" value="0">
															Inactive
														</label>
													</div>
												</div>
											</div>
										</div>	
									</div>	
									<div class="form-group mb-2">
	                   					<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Contact Person <span class="text-danger">*</span></label>
													<input id="edit_outlet_contact_person" name="outlet_contact_person" type="text" class="form-control" placeholder="">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Phone Number <span class="text-danger">*</span></label>
													<input id="edit_outlet_phone_number" name="outlet_phone_number" type="text" class="form-control" placeholder="">
												</div>
											</div>
										</div>	
									</div>
									<div class="form-group mb-2">
	                   					<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label>Email Address</label>
													<input id="edit_outlet_email_address" name="outlet_email_address" type="text" class="form-control" placeholder="">
												</div>
											</div>
										</div>	
									</div>
								</div>

								<div class="modal-footer">								
									<button type="submit" id="btn_edit_outlet" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CLOSE</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				load_outlets();
			</script>


				



		