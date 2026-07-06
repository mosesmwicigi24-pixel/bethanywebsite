					<div class="w-100">
						<div class="row">
							<div class="col-lg-8">
								<div class="card rounded-top-0">
									<div class="card-header bg-transparent header-elements-inline p-2">
										<h5 class="card-title font-weight-bold"><i class="icon-googleplus5 mr-2"></i> Prefixes</h5>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-lg-12">
												
												<div class="row">
													<div class="col-md-12">
														<form method="post" class="form">
															<div id="prefixes_div" style="min-height: 500px;">
													
															</div>
															<!-- <div class="form-group mb-2">
																<div class="row">
																	<div class="col-sm-2 font-weight-600">
																		<select id="sl_prefixes_bulk_action" name="sl_prefixes_bulk_action" class="form-control form-control-select2" data-placeholder="Bulk Action" data-fouc>
																			<option value="">Bulk Action</option>
																			<option value="Delete">Delete</option>
																		</select>
																	</div>
																	<div class="col-sm-2">
																		<button id="btn_prefixes_bulk_action" type="button" class="btn btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> APPLY</button>
																	</div>
																</div>
															</div> -->
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

			<div id="modal_edit_prefix" class="modal fade" tabindex="-1">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-pencil6"></i> Edit Prefix</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_edit_prefix" name="frm_edit_prefix" method="post" onsubmit="return update_prefix();">
							<fieldset <?php if ($sbr_prefixes_edit == false){ echo 'disabled'; } ?>>
								<div class="modal-body">

									<div id="div_edit_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_edit_success" class="alert alert-success display-none font-13"></div>

	                   				<input id="edit_prefix_id" name="prefix_id" type="hidden" placeholder="" class="form-control">

									<div class="form-group">
										<div class="row">
											<div class="col-sm-12">
												<label>Document Name <span class="error">*</span></label>
												<input id="edit_document_name" name="document_name" type="text" placeholder="" readonly class="form-control">
											</div>
										</div>
									</div>

									<div class="form-group">
										<div class="row">
											<div class="col-sm-6">
												<label>Prefix <span class="error">*</span></label>
												<input id="edit_prefix_name" name="prefix_name" type="text" placeholder="" maxlength="4" class="form-control">
											</div>
											<div class="col-sm-6">
												<label>Prefix Value (%) <span class="error">*</span></label>
												<input id="edit_current_value" name="current_value" type="number" placeholder="" min="0" class="form-control">
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">								
									<button type="submit" id="btn_edit_prefix" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CLOSE</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				load_prefixes();
			</script>


				



		