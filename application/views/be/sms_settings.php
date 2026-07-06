					<div class="w-100">
						<div class="row">
							<div class="col-lg-12">
								<?php if ($sms_settings_exists == true): ?>
                        			<?php foreach($sms_settings as $row): ?>
                        				<form id="frm_sms_settings" name="frm_sms_settings" method="post" onsubmit="return save_sms_settings();" autocomplete="off" enctype="multipart/form-data">	

                        					<fieldset <?php if ($sbr_bulk_sms_settings_edit == false){ echo 'disabled'; } ?>>						

			                   					<div class="row">

													<div class="col-md-5">
														<div class="card rounded-top-0">
															<div class="card-header alpha-grey text-primary header-elements-inline pt-2 pb-2">
																<h6 class="card-title font-weight-bold"><i class="icon-envelop"></i> Bulk SMS Settings</h6>			
															</div>
															<div class="card-body">

																<p><i>Input your Africastalking API Settings here to enable you send Bulk SMSes.<br>
																You can get them here > <a href="https://africastalking.com" target="_blank">Africa's Talking</a>.</i></p>

																<div id="div_error" class="alert alert-danger display-none font-13"></div>
			                   									<div id="div_success" class="alert alert-success display-none font-13"></div>

																<div class="form-group mb-2">
																	<label>API Username <span class="text-danger">*</span></label>
																	<input id="api_username" name="api_username" type="text" class="form-control" placeholder="" value="<?php echo $row->api_username; ?>">
																</div>
																<div class="form-group mb-2">
																	<label>API Key <span class="text-danger">*</span></label>
																	<input id="api_key" name="api_key" type="text" class="form-control" placeholder="" value="<?php echo $row->api_key; ?>">
																</div>
																<div class="form-group mb-2">
																	<div class="row">
																		<div class="col-md-8">
																			<label>Sender ID</label>
																			<input id="sender_id" name="sender_id" type="text" class="form-control" placeholder="" value="<?php echo $row->sender_id; ?>">
																		</div>
																		<div class="col-md-4">
																			<label class="d-block text-primary">User Sender ID?</label>
																			<div class="form-check">
																				<label class="form-check-label font-weight-semibold">
																					<input id="use_sender_id" name="use_sender_id" type="checkbox" class="form-check-input" <?php if ($row->use_sender_id == 1){ echo 'checked'; } ?>>
																					Yes
																				</label>
																			</div>
																		</div>
																	</div>
																	
																</div>																
																<div class="form-group">
																	<div class="row">
																		<div class="col-md-8">
																			<label>Short Code</label>
																			<input id="short_code" name="short_code" type="number" class="form-control" placeholder="" value="<?php echo $row->short_code; ?>">
																		</div>
																		<div class="col-md-4">
																			<label class="d-block text-primary">User Short Code?</label>
																			<div class="form-check">
																				<label class="form-check-label font-weight-semibold">
																					<input id="use_short_code" name="use_short_code" type="checkbox" class="form-check-input" <?php if ($row->use_short_code == 1){ echo 'checked'; } ?>>
																					Yes
																				</label>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="text-right">
																	<button id="btn_sms_settings" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE SMS SETTINGS</button>
																</div>

															</div>
														</div>
													</div>
												</div>
											</fieldset>
										</form>
                        			<?php endforeach; ?>
                        		<?php else: ?>		                        			
                        			<form id="frm_sms_settings" name="frm_sms_settings" method="post" onsubmit="return save_sms_settings();" autocomplete="off" enctype="multipart/form-data">
                        				<fieldset <?php if ($sbr_bulk_sms_settings_edit == false){ echo 'disabled'; } ?>>
											<div class="row">
												<div class="col-md-5">
													<div class="card rounded-top-0">
														<div class="card-header alpha-grey text-success-800 header-elements-inline pt-2 pb-2">
															<h6 class="card-title text-uppercase"><i class="icon-envelop"></i> Bulk SMS Settings</h6>			
														</div>
														<div class="card-body">

															<p><i>Input your Africastalking API Settings here to enable you send Bulk SMSes.<br>
															You can get them here > <a href="https://africastalking.com" target="_blank">Africa's Talking</a>.</i></p>

															<div id="div_error" class="alert alert-danger display-none font-13"></div>
		                   									<div id="div_success" class="alert alert-success display-none font-13"></div>

															<div class="form-group mb-2">
																<label>API Username <span class="text-danger">*</span></label>
																<input id="api_username" name="api_username" type="text" class="form-control" placeholder="">
															</div>
															<div class="form-group mb-2">
																<label>API Key <span class="text-danger">*</span></label>
																<input id="api_key" name="api_key" type="text" class="form-control" placeholder="">
															</div>
															<div class="form-group mb-2">
																<div class="row">
																	<div class="col-md-8">
																		<label>Sender ID</label>
																		<input id="sender_id" name="sender_id" type="text" class="form-control" placeholder="">
																	</div>
																	<div class="col-md-4">
																		<label class="d-block text-primary">User Sender ID?</label>
																		<div class="form-check">
																			<label class="form-check-label font-weight-semibold">
																				<input id="use_sender_id" name="use_sender_id" type="checkbox" class="form-check-input">
																				Yes
																			</label>
																		</div>
																	</div>
																</div>
																
															</div>																
															<div class="form-group">
																<div class="row">
																	<div class="col-md-8">
																		<label>Short Code</label>
																		<input id="short_code" name="short_code" type="number" class="form-control" placeholder="">
																	</div>
																	<div class="col-md-4">
																		<label class="d-block text-primary">User Short Code?</label>
																		<div class="form-check">
																			<label class="form-check-label font-weight-semibold">
																				<input id="use_short_code" name="use_short_code" type="checkbox" class="form-check-input">
																				Yes
																			</label>
																		</div>
																	</div>
																</div>
															</div>
															<div class="text-right">
																<button id="btn_sms_settings" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE SMS SETTINGS</button>
															</div>										
														</div>
													</div>
												</div>
											</div>
										</div>												
									</form>
                        		<?php endif; ?>
							</div>							
						</div>
					</div>
				</div>
			</div>


				



		