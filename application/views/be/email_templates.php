					<div class="w-100">
						<div class="row">
							<div class="col-lg-12">
								<div class="card rounded-top-0">
									<div class="card-header bg-transparent header-elements-inline p-2">
										<h5 class="card-title font-weight-bold"><i class="icon-envelop4 mr-2"></i> Email Templates</h5>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-lg-12">
												
												<div class="row">
													<div class="col-md-6">
														<div class="card rounded-top-0">
															<div class="card-header alpha-grey text-primary header-elements-inline pt-2 pb-2">
																<h6 class="card-title font-weight-bold"><i class="icon-plus-circle2 mr-1"></i> New Email Template</h6>			
															</div>
															<div class="card-body">
																<div class="row">
																	<div class="col-md-8">
																		<form id="frm_add_email_template" name="frm_add_email_template" method="post" onsubmit="return save_email_template();" autocomplete="off" enctype="multipart/form-data">

																			<fieldset <?php if ($sbr_email_templates_add == false){ echo 'disabled'; } ?>>

																				<div id="div_add_error" class="alert alert-danger display-none font-13"></div>
											                   					<div id="div_add_success" class="alert alert-success display-none font-13"></div>

																				<div class="form-group mb-2">
																					<div class="row">
																						<div class="col-sm-12">
																							<label>Template Name <span class="error">*</span></label>
																							<input id="add_email_template_name" name="email_template_name" type="text" placeholder="" class="form-control">
																						</div>
																					</div>
																				</div>
																				<div class="form-group mb-2">
																					<div class="row">
																						<div class="col-sm-12">
																							<label>Template<span class="error">*</span></label>
																							<textarea id="add_email_template" name="email_template" class="form-control ckeditor"></textarea>
																						</div>
																					</div>
																				</div>

																										
																				<div class="text-right">
																					<button id="btn_add_email_template" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE</button>
																				</div>
																			</fieldset>
																		</form>
																	</div>
																	<div class="col-md-4">
																		<p>Use the following customer data fields to create your templates. Please ensure to copy them as provided (including the double braces).</p>
																		<p class="font-weight-600">{{first_name}}<br>{{last_name}}<br>{{phone_number}}<br>{{email_address}}</p>
																	</div>
																</div>
															</div>
														</div>
													</div>
													<!-- <div class="col-md-1"></div> -->
													<div class="col-md-6">
														<form method="post" class="form">
															<div id="email_templates_div" style="min-height: 500px;">
													
															</div>
															<div class="form-group mb-2">
																<div class="row">
																	<div class="col-sm-2 font-weight-600">
																		<select id="sl_email_templates_bulk_action" name="sl_email_templates_bulk_action" class="form-control form-control-select2" data-placeholder="Bulk Action" data-fouc>
																			<option value="">Bulk Action</option>
																			<option value="Delete">Delete</option>
																		</select>
																	</div>
																	<div class="col-sm-2">
																		<button id="btn_email_templates_bulk_action" type="button" class="btn btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> APPLY</button>
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

			<div id="modal_edit_email_template" class="modal fade" tabindex="-1">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-pencil6"></i> Edit Email Template</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_edit_email_template" name="frm_edit_email_template" method="post" onsubmit="return update_email_template();">
							<fieldset <?php if ($sbr_email_templates_edit == false){ echo 'disabled'; } ?>>
								
								<div class="modal-body">
									<div class="row">
										<div class="col-md-8">
											<div id="div_edit_error" class="alert alert-danger display-none font-13"></div>
			                   				<div id="div_edit_success" class="alert alert-success display-none font-13"></div>

			                   				<input id="edit_email_template_id" name="email_template_id" type="hidden" placeholder="" class="form-control">

											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-12">
														<label>Template Name <span class="error">*</span></label>
														<input id="edit_email_template_name" name="email_template_name" type="text" placeholder="" class="form-control">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-12">
														<label>Template<span class="error">*</span></label>
														<textarea id="edit_email_template" name="email_template" class="form-control ckeditor"></textarea>
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<p>Use the following customer data fields to create your templates. Please ensure to copy them as provided (including the double braces).</p>
											<p class="font-weight-600">{{first_name}}<br>{{last_name}}<br>{{phone_number}}<br>{{email_address}}</p>
										</div>
									</div>
								</div>
								<div class="modal-footer">								
									<button type="submit" id="btn_edit_email_template" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CLOSE</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				CKEDITOR.replace( 'add_email_template', {
                    height: 200,
                    toolbar: [
						{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo', 'Styles', 'Format', 'Font', 'FontSize', 'NumberedList', 'BulletedList' ] }
					]
                });
                CKEDITOR.replace( 'edit_email_template', {
                    height: 250,
                    toolbar: [
						{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo', 'Styles', 'Format', 'Font', 'FontSize', 'NumberedList', 'BulletedList' ] }
					]
                });
				load_email_templates();
			</script>


				



		