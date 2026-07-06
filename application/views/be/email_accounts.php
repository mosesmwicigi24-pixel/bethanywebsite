					<div class="w-100">
						<div class="row">
							<div class="col-lg-12">
								<div class="card rounded-top-0">
									<div class="card-header bg-transparent header-elements-inline p-2">
										<h5 class="card-title font-weight-bold"><i class="icon-envelop4 mr-2"></i> Email Settings</h5>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-lg-12">
												
												<div class="row">
													<div class="col-md-4">
														<div class="card rounded-top-0">
															<div class="card-header alpha-grey text-primary header-elements-inline pt-2 pb-2">
																<h6 class="card-title font-weight-bold"><i class="icon-plus-circle2 mr-1"></i> New Email Account</h6>			
															</div>
															<div class="card-body">
																<form id="frm_add_email_account" name="frm_add_email_account" method="post" onsubmit="return save_email_account();" autocomplete="off" enctype="multipart/form-data">

																	<fieldset <?php if ($sbr_email_accounts_add == false){ echo 'disabled'; } ?>>

																		<div id="div_add_error" class="alert alert-danger display-none font-13"></div>
									                   					<div id="div_add_success" class="alert alert-success display-none font-13"></div>

																		<div class="form-group mb-2">
																			<div class="row">
																				<div class="col-sm-12">
																					<div class="form-check form-check-inline form-check-right">
																						<label for="add_default" class="form-check-label font-weight-bold text-primary">Set Default <i class="icon-help text-warning" data-popup="tooltip" title="PS: There can only be one default sender email account, setting this as default will unset any other email accounts that had been defined as default." data-placement="top"></i></label>
																						<input id="add_default" name="default" type="checkbox" class="form-check-input">
																					</div>
																				</div>
																			</div>
																		</div>
																		

																		<div class="form-group mb-2">
																			<div class="row">
																				<div class="col-sm-12">
																					<label>Sender Name <span class="error">*</span></label>
																					<input id="add_sender_name" name="sender_name" type="text" placeholder="" class="form-control">
																				</div>
																			</div>
																		</div>
																		<div class="form-group mb-2">
																			<div class="row">
																				<div class="col-sm-12">
																					<label>Sender Email Address<span class="error">*</span></label>
																					<input id="add_sender_email_address" name="sender_email_address" type="email" placeholder="" class="form-control">
																				</div>
																			</div>
																		</div>
																		<div class="form-group mb-2">
																			<div class="row">
																				<div class="col-sm-8">
																					<label>Mail Server (SMTP) <span class="error">*</span></label>
																					<input id="add_mail_server_name" name="mail_server_name" type="text" placeholder="" class="form-control">
																				</div>
																				<div class="col-sm-4">
																					<label>Mail Server Port <span class="error">*</span></label>
																					<input id="add_mail_server_port" name="mail_server_port" type="number" placeholder="" class="form-control" min="0">
																				</div>
																			</div>
																		</div>
																		<div class="form-group mb-2">
																			<div class="row">
																				<div class="col-sm-6">
																					<label>Username <span class="error">*</span></label>
																					<input id="add_user_name" name="user_name" type="email" placeholder="" class="form-control">
																				</div>
																				<div class="col-sm-6">
																					<label>Password <span class="error">*</span></label>
																					<input id="add_password" name="password" type="password" placeholder="" class="form-control">
																				</div>										
																			</div>
																		</div>
																		<div class="form-group mb-3 mb-md-2">
																			<div class="row">	
																				<div class="col-sm-4">
																					<label>&nbsp;</label>
																					<div class="form-check">
																						<label class="form-check-label">
																							<input type="checkbox" class="form-check-input" name="use_ssl" id="add_use_ssl">
																							Use SSL
																						</label>
																					</div>
																				</div>									
																				<div class="col-sm-4">
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
																				<div class="col-sm-4">
																					<label>Sort Key <span class="error">*</span></label>
																					<input id="add_sort_key" name="sort_key" type="number" class="form-control" min="0" value="0">
																				</div>
																			</div>
																		</div>

																								
																		<div class="text-right">
																			<button id="btn_add_email_account" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE</button>
																		</div>
																	</fieldset>
																</form>
															</div>
														</div>
													</div>
													<!-- <div class="col-md-1"></div> -->
													<div class="col-md-8">
														<form method="post" class="form">
															<div id="email_accounts_div" style="min-height: 500px;">
													
															</div>
															<div class="form-group mb-2">
																<div class="row">
																	<div class="col-sm-2 font-weight-600">
																		<select id="sl_email_accounts_bulk_action" name="sl_email_accounts_bulk_action" class="form-control form-control-select2" data-placeholder="Bulk Action" data-fouc>
																			<option value="">Bulk Action</option>
																			<option value="Delete">Delete</option>
																		</select>
																	</div>
																	<div class="col-sm-2">
																		<button id="btn_email_accounts_bulk_action" type="button" class="btn btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> APPLY</button>
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

			<div id="modal_edit_email_account" class="modal fade" tabindex="-1">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-pencil6"></i> Edit Email Account</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_edit_email_account" name="frm_edit_email_account" method="post" onsubmit="return update_email_account();">
							<fieldset <?php if ($sbr_email_accounts_edit == false){ echo 'disabled'; } ?>>
								<div class="modal-body">

									<div id="div_edit_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_edit_success" class="alert alert-success display-none font-13"></div>

	                   				<input id="edit_email_account_id" name="email_account_id" type="hidden" placeholder="" class="form-control">

	                   				<div class="form-group mb-3">
										<div class="row">
											<div class="col-sm-12">
												<div class="form-check form-check-inline form-check-right">
													<label for="edit_default" class="form-check-label font-weight-bold text-primary">Set Default <i class="icon-help text-warning" data-popup="tooltip" title="PS: There can only be one default sender email account, setting this as default will unset any other email accounts that had been defined as default." data-placement="top"></i></label>
													<input id="edit_default" name="default" type="checkbox" class="form-check-input">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group mb-2">
										<div class="row">
											<div class="col-sm-12">
												<label>Sender Name <span class="error">*</span></label>
												<input id="edit_sender_name" name="sender_name" type="text" placeholder="" class="form-control">
											</div>
										</div>
									</div>
									<div class="form-group mb-2">
										<div class="row">
											<div class="col-sm-12">
												<label>Sender Email Address<span class="error">*</span></label>
												<input id="edit_sender_email_address" name="sender_email_address" type="email" placeholder="" class="form-control">
											</div>
										</div>
									</div>
									<div class="form-group mb-2">
										<div class="row">
											<div class="col-sm-8">
												<label>Mail Server (SMTP) <span class="error">*</span></label>
												<input id="edit_mail_server_name" name="mail_server_name" type="text" placeholder="" class="form-control">
											</div>
											<div class="col-sm-4">
												<label>Mail Server Port <span class="error">*</span></label>
												<input id="edit_mail_server_port" name="mail_server_port" type="number" placeholder="" class="form-control" min="0">
											</div>
										</div>
									</div>
									<div class="form-group mb-2">
										<div class="row">
											<div class="col-sm-6">
												<label>Username <span class="error">*</span></label>
												<input id="edit_user_name" name="user_name" type="email" placeholder="" class="form-control">
											</div>
											<div class="col-sm-6">
												<label>Password <span class="error">*</span></label>
												<input id="edit_password" name="password" type="password" placeholder="" class="form-control">
											</div>										
										</div>
									</div>
									<div class="form-group mb-3 mb-md-2">
										<div class="row">	
											<div class="col-sm-4">
												<label>&nbsp;</label>
												<div class="form-check">
													<label class="form-check-label">
														<input type="checkbox" class="form-check-input" name="use_ssl" id="edit_use_ssl">
														Use SSL
													</label>
												</div>
											</div>									
											<div class="col-sm-4">
												<div class="form-group">
													<label class="d-block">Status <span class="error">*</span></label>
													<div class="form-check">
														<label class="form-check-label font-weight-semibold">
															<input type="radio" class="form-check-input" id="edit_is_active_1" name="is_active" value="1" checked>
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
											<div class="col-sm-4">
												<label>Sort Key <span class="error">*</span></label>
												<input id="edit_sort_key" name="sort_key" type="number" class="form-control" min="0" value="0">
											</div>
										</div>
									</div>

								</div>
								<div class="modal-footer">								
									<button type="submit" id="btn_edit_email_account" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CLOSE</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				load_email_accounts();
			</script>


				



		