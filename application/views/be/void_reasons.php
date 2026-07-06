					<div class="w-100">
						<div class="row">
							<div class="col-lg-12">
								<div class="card rounded-top-0">
									<div class="card-header bg-transparent header-elements-inline p-2">
										<h5 class="card-title font-weight-bold"><i class="icon-warning mr-2"></i> Void Reasons</h5>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-lg-12">
												
												<div class="row">
													<div class="col-md-4">
														<div class="card rounded-top-0">
															<div class="card-header alpha-grey text-primary header-elements-inline pt-2 pb-2">
																<h6 class="card-title font-weight-bold"><i class="icon-plus-circle2 mr-1"></i> New Void Reason</h6>			
															</div>
															<div class="card-body">
																<form id="frm_add_void_reason" name="frm_add_void_reason" method="post" onsubmit="return save_void_reason();" autocomplete="off" enctype="multipart/form-data">

																	<fieldset <?php if ($sbr_void_reasons_add == false){ echo 'disabled'; } ?>>

																		<div id="div_add_error" class="alert alert-danger display-none font-13"></div>
									                   					<div id="div_add_success" class="alert alert-success display-none font-13"></div>

																		<div class="form-group">
																			<div class="row">
																				<div class="col-sm-12">
																					<label>Void Reason <span class="error">*</span></label>
																					<textarea name="void_reason" id="add_void_reason" rows="3" cols="3" class="form-control" placeholder=""></textarea>
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
																								
																		<div class="text-right">
																			<button id="btn_add_void_reason" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE</button>
																		</div>
																	</fieldset>
																</form>
															</div>
														</div>
													</div>
													<!-- <div class="col-md-1"></div> -->
													<div class="col-md-8">
														<form method="post" class="form">
															<div id="void_reasons_div" style="min-height: 500px;">
													
															</div>
															<div class="form-group mb-2">
																<div class="row">
																	<div class="col-sm-2 font-weight-600">
																		<select id="sl_void_reasons_bulk_action" name="sl_void_reasons_bulk_action" class="form-control form-control-select2" data-placeholder="Bulk Action" data-fouc>
																			<option value="">Bulk Action</option>
																			<option value="Delete">Delete</option>
																		</select>
																	</div>
																	<div class="col-sm-2">
																		<button id="btn_void_reasons_bulk_action" type="button" class="btn btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> APPLY</button>
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

			<div id="modal_edit_void_reason" class="modal fade" tabindex="-1">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-pencil6"></i> Edit Void Reason</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_edit_void_reason" name="frm_edit_void_reason" method="post" onsubmit="return update_void_reason();">
							<fieldset <?php if ($sbr_void_reasons_edit == false){ echo 'disabled'; } ?>>
								<div class="modal-body">

									<div id="div_edit_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_edit_success" class="alert alert-success display-none font-13"></div>

	                   				<input id="edit_void_reason_id" name="void_reason_id" type="hidden" placeholder="" class="form-control">

									<div class="form-group">
										<div class="row">
											<div class="col-sm-12">
												<label>Void Reason <span class="error">*</span></label>
												<textarea name="void_reason" id="edit_void_reason" rows="3" cols="3" class="form-control" placeholder=""></textarea>
											</div>
										</div>
									</div>

									<div class="form-group mb-3 mb-md-2">
										<div class="row">
											<div class="col-sm-6">
												<label>Sort Key <span class="error">*</span></label>
												<input id="edit_sort_key" name="sort_key" type="number" class="form-control" min="0" value="0">
											</div>
											<div class="col-sm-6">
												<label class="d-block">Status <span class="error">*</span></label>
												<div class="form-check form-check-inline form-check-right">
													<label class="form-check-label font-weight-semibold">
														Active
														<input type="radio" class="form-check-input" name="is_active" id="edit_is_active_1" value="1">
													</label>
												</div>

												<div class="form-check form-check-inline form-check-right">
													<label class="form-check-label font-weight-semibold">
														Inactive
														<input type="radio" class="form-check-input" name="is_active" id="edit_is_active_0" value="0">
													</label>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">								
									<button type="submit" id="btn_edit_void_reason" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CLOSE</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				load_void_reasons();
			</script>


				



		