					<div class="w-100">
						<div class="row">
							<div class="col-lg-12">
								<div class="card rounded-top-0">
									<div class="card-header bg-transparent header-elements-inline p-2">
										<h5 class="card-title font-weight-bold"><i class="icon-cash4 mr-2"></i> Bank Branches (<?php foreach ($bank as $row){ echo $row->bank_name; } ?>)</h5>
										<div class="header-elements">
											<a href="<?php echo base_url();?>be/settings/banks" class="btn btn-sm bg-success"><i class="icon-arrow-left15"></i> Banks</a>
										</div>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-md-4">
												<div class="card rounded-top-0">
													<div class="card-header alpha-grey text-primary header-elements-inline pt-2 pb-2">
																<h6 class="card-title font-weight-bold"><i class="icon-plus-circle2 mr-1"></i> New Bank Branch</h6>			
													</div>
													<div class="card-body">
														<form id="frm_add_bank_branch" name="frm_add_bank_branch" method="post" onsubmit="return save_bank_branch();" autocomplete="off" enctype="multipart/form-data">

															<fieldset <?php if ($sbr_bank_branches_add == false){ echo 'disabled'; } ?>>

																<div id="div_add_error" class="alert alert-danger display-none font-13"></div>
							                   					<div id="div_add_success" class="alert alert-success display-none font-13"></div>

							                   					<input type="hidden" id="add_bank_id" name="bank_id" value="<?php echo $bank_id; ?>">

																<div class="form-group mb-2">
																	<div class="row">
																		<div class="col-sm-8">
																			<label>Branch Name <span class="error">*</span></label>
																			<input id="add_bank_branch_name" name="bank_branch_name" type="text" placeholder="" class="form-control">
																		</div>
																		<div class="col-sm-4">
																			<label>Branch Code</label>
																			<input id="add_bank_branch_code" name="bank_branch_code" type="text" placeholder="" class="form-control" maxlength="10">
																		</div>
																	</div>
																</div>
																<div class="form-group mb-2">
																	<div class="row">
																		<div class="col-sm-12">
																			<label>Account Number</label>
																			<input id="add_account_number" name="account_number" type="text" placeholder="" class="form-control">
																		</div>
																	</div>
																</div>
																<div class="form-group mb-2">
																	<div class="row">
																		<div class="col-sm-6">
																			<label>Phone Number</label>
																			<input id="add_phone_number" name="phone_number" type="text" placeholder="" class="form-control">
																		</div>
																		<div class="col-sm-6">
																			<label>Mobile Number</label>
																			<input id="add_mobile_number" name="mobile_number" type="text" placeholder="" class="form-control">
																		</div>
																	</div>
																</div>
																<div class="form-group mb-2">
																	<div class="row">
																		<div class="col-sm-12">
																			<label>Email Address</label>
																			<input id="add_email_address" name="email_address" type="email" placeholder="" class="form-control">
																		</div>
																	</div>
																</div>
																<div class="form-group mb-2">
																	<div class="row">
																		<div class="col-sm-12">
																			<label>Postal Address</label>
																			<textarea name="postal_address" id="add_postal_address" rows="2" cols="3" class="form-control" placeholder=""></textarea>
																		</div>
																	</div>
																</div>
																<div class="form-group mb-3 mb-md-2">
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

																<div class="text-right">
																	<button id="btn_add_bank_branch" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE</button>
																</div>
															</fieldset>
														</form>
													</div>
												</div>
											</div>
											<!-- <div class="col-md-1"></div> -->
											<div class="col-md-8">
												<form method="post" class="form">
													<div id="bank_branches_div" style="min-height: 400px;">
											
													</div>
													<div class="form-group mb-2">
														<div class="row">
															<div class="col-sm-2 font-weight-600">
																<select id="sl_bank_branches_bulk_action" name="sl_bank_branches_bulk_action" class="form-control form-control-select2" data-placeholder="Bulk Action" data-fouc>
																	<option value="">Bulk Action</option>
																	<option value="Delete">Delete</option>
																</select>
															</div>
															<div class="col-sm-2">
																<button id="btn_bank_branches_bulk_action" type="button" class="btn btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> APPLY</button>
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



			<!-- Edit modal form -->
			<div id="modal_edit_bank_branch" class="modal fade">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-pencil6"></i> Edit Bank Branch</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_edit_bank_branch" name="frm_edit_bank_branch" method="post" onsubmit="return update_bank_branch();">
							<fieldset <?php if ($sbr_bank_branches_edit == false){ echo 'disabled'; } ?>>
								<div class="modal-body">

									<div id="div_edit_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_edit_success" class="alert alert-success display-none font-13"></div>

	                   				<input type="hidden" id="edit_bank_id" name="bank_id" value="<?php echo $bank_id; ?>">
	                   				<input id="edit_bank_branch_id" name="bank_branch_id" type="hidden" placeholder="" class="form-control">

									<div class="form-group mb-2">
										<div class="row">
											<div class="col-sm-8">
												<label>Branch Name <span class="error">*</span></label>
												<input id="edit_bank_branch_name" name="bank_branch_name" type="text" placeholder="" class="form-control">
											</div>
											<div class="col-sm-4">
												<label>Branch Code</label>
												<input id="edit_bank_branch_code" name="bank_branch_code" type="text" placeholder="" class="form-control" maxlength="10">
											</div>
										</div>
									</div>
									<div class="form-group mb-2">
										<div class="row">
											<div class="col-sm-12">
												<label>Account Number</label>
												<input id="edit_account_number" name="account_number" type="text" placeholder="" class="form-control">
											</div>
										</div>
									</div>
									<div class="form-group mb-2">
										<div class="row">
											<div class="col-sm-6">
												<label>Phone Number</label>
												<input id="edit_phone_number" name="phone_number" type="text" placeholder="" class="form-control">
											</div>
											<div class="col-sm-6">
												<label>Mobile Number</label>
												<input id="edit_mobile_number" name="mobile_number" type="text" placeholder="" class="form-control">
											</div>
										</div>
									</div>
									<div class="form-group mb-2">
										<div class="row">
											<div class="col-sm-12">
												<label>Email Address</label>
												<input id="edit_email_address" name="email_address" type="email" placeholder="" class="form-control">
											</div>
										</div>
									</div>
									<div class="form-group mb-2">
										<div class="row">
											<div class="col-sm-12">
												<label>Postal Address</label>
												<textarea name="postal_address" id="edit_postal_address" rows="2" cols="3" class="form-control" placeholder=""></textarea>
											</div>
										</div>
									</div>
									<div class="form-group mb-3 mb-md-2">
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
								<div class="modal-footer">								
									<button type="submit" id="btn_edit_bank_branch" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CLOSE</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				load_bank_branches();
			</script>
	