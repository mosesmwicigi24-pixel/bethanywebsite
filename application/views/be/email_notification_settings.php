					<div class="w-100">
						<div class="row">
							<div class="col-lg-12">
								<?php if ($email_notification_settings_exists == true): ?>
                        			<?php foreach($email_notification_settings as $row): ?>
                        				<form id="frm_email_notification_settings" name="frm_email_notification_settings" method="post" onsubmit="return save_email_notification_settings();" autocomplete="off" enctype="multipart/form-data">	

                        					<fieldset <?php if ($sbr_email_notification_settings_edit == false){ echo 'disabled'; } ?>>						

			                   					<div class="row">

													<div class="col-md-7">
														<div class="card rounded-top-0">
															<div class="card-header alpha-grey text-primary header-elements-inline pt-2 pb-2">
																<h6 class="card-title font-weight-bold"><i class="icon-envelop"></i> Email Notification Settings</h6>			
															</div>
															<div class="card-body">

																<p><i>Enter the recipient Emails below where notification emails will be sent</i></p>

																<div id="div_error" class="alert alert-danger display-none font-13"></div>
			                   									<div id="div_success" class="alert alert-success display-none font-13"></div>

																<div class="form-group mb-2">
																	<label>Recipient Email <span class="text-danger">*</span></label>
																	<input id="email_address" name="email_address" type="email" class="form-control" value="<?php echo $row->email_address; ?>" placeholder="">
																</div>
																<div class="form-group mb-2">
																	<label>CC</label>
																	<input id="cc_email_address" name="cc_email_address" type="email" class="form-control" value="<?php echo $row->cc_email_address; ?>" placeholder="">
																</div>
																<div class="form-group mb-2">
																	<label>BCC</label>
																	<input id="bcc_email_address" name="bcc_email_address" type="email" class="form-control" value="<?php echo $row->bcc_email_address; ?>" placeholder="">
																</div>

																<div class="text-right">
																	<button id="btn_email_notification_settings" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE SETTINGS</button>
																</div>

															</div>
														</div>
													</div>
												</div>
											</fieldset>
										</form>
                        			<?php endforeach; ?>
                        		<?php else: ?>		                        			
                        			<form id="frm_email_notification_settings" name="frm_email_notification_settings" method="post" onsubmit="return save_email_notification_settings();" autocomplete="off" enctype="multipart/form-data">
                        				<fieldset <?php if ($sbr_email_notification_settings_edit == false){ echo 'disabled'; } ?>>
											<div class="row">
												<div class="col-md-7">
													<div class="card rounded-top-0">
														<div class="card-header alpha-grey text-success-800 header-elements-inline pt-2 pb-2">
															<h6 class="card-title text-uppercase"><i class="icon-envelop"></i> Email Notification Settings</h6>			
														</div>
														<div class="card-body">

															<p><i>Enter the recipient Emails below where notification emails will be sent</i></p>

															<div id="div_error" class="alert alert-danger display-none font-13"></div>
		                   									<div id="div_success" class="alert alert-success display-none font-13"></div>

															<div class="form-group mb-2">
																<label>Recipient Email <span class="text-danger">*</span></label>
																<input id="email_address" name="email_address" type="email" class="form-control" placeholder="">
															</div>
															<div class="form-group mb-2">
																<label>CC</label>
																<input id="cc_email_address" name="cc_email_address" type="email" class="form-control" placeholder="">
															</div>
															<div class="form-group mb-2">
																<label>BCC</label>
																<input id="bcc_email_address" name="bcc_email_address" type="email" class="form-control" placeholder="">
															</div>
															
															<div class="text-right">
																<button id="btn_email_notification_settings" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE SETTINGS</button>
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
			


				



		