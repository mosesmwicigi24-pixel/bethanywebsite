					<div class="w-100">
						<div class="row">
							<div class="col-lg-12">
								<?php if ($pesapal_settings_exists == true): ?>
                        			<?php foreach($pesapal_settings as $row): ?>
                        				<form id="frm_pesapal_settings" name="frm_pesapal_settings" method="post" onsubmit="return save_pesapal_settings();" autocomplete="off" enctype="multipart/form-data">										
                        					<fieldset <?php if ($sbr_pesapal_settings_edit == false){ echo 'disabled'; } ?>>
			                   					<div class="row">

													<div class="col-md-5">
														<div class="card rounded-top-0">
															<div class="card-header alpha-grey text-primary header-elements-inline pt-2 pb-2">
																<h6 class="card-title font-weight-bold"><i class="icon-cash"></i> Pesapal Settings</h6>			
															</div>
															<div class="card-body">

																<div id="div_error" class="alert alert-danger display-none font-13"></div>
			                   									<div id="div_success" class="alert alert-success display-none font-13"></div>

																<div class="form-group mb-2">
																	<label>Consumer Key <span class="text-danger">*</span></label>
																	<input id="consumer_key" name="consumer_key" type="text" class="form-control" placeholder="" value="<?php echo $row->consumer_key; ?>">
																</div>
																<div class="form-group mb-2">
																	<label>Consumer Secret <span class="text-danger">*</span></label>
																	<input id="consumer_secret" name="consumer_secret" type="text" class="form-control" placeholder="" value="<?php echo $row->consumer_secret; ?>">
																</div>
																<div class="form-group mb-2">
																	<label>Environment <span class="text-danger">*</span></label>
																	<select id="environment" name="environment" class="form-control form-control-select2" data-placeholder="Select Environment" data-fouc>
																		<option value="">Select Environment</option>
																		<option value="SANBOX" <?php if ($row->environment == 'SANDBOX'){ echo 'selected'; } ?>>SANDBOX</option>
																		<option value="LIVE" <?php if ($row->environment == 'LIVE'){ echo 'selected'; } ?>>LIVE</option>
																	</select>
																</div>

																<div class="text-right">
																	<button id="btn_pesapal_settings" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE PESAPAL SETTINGS</button>
																</div>

															</div>
														</div>
													</div>
												</div>
											</fieldset>
										</form>
                        			<?php endforeach; ?>
                        		<?php else: ?>		                        			
                        			<form id="frm_pesapal_settings" name="frm_pesapal_settings" method="post" onsubmit="return save_pesapal_settings();" autocomplete="off" enctype="multipart/form-data">
                        				<fieldset <?php if ($sbr_pesapal_settings_edit == false){ echo 'disabled'; } ?>>
											<div class="row">
												<div class="col-md-5">
													<div class="card rounded-top-0">
														<div class="card-header alpha-grey text-success-800 header-elements-inline pt-2 pb-2">
															<h6 class="card-title text-uppercase"><i class="icon-cash"></i> Pesapal Settings</h6>			
														</div>
														<div class="card-body">

															<div id="div_error" class="alert alert-danger display-none font-13"></div>
		                   									<div id="div_success" class="alert alert-success display-none font-13"></div>

															<div class="form-group mb-2">
																<label>Consumer Key <span class="text-danger">*</span></label>
																<input id="consumer_key" name="consumer_key" type="text" class="form-control" placeholder="">
															</div>
															<div class="form-group mb-2">
																<label>Consumer Secret <span class="text-danger">*</span></label>
																<input id="consumer_secret" name="consumer_secret" type="text" class="form-control" placeholder="">
															</div>
															<div class="form-group mb-2">
																<label>Environment <span class="text-danger">*</span></label>
																<select id="environment" name="environment" class="form-control form-control-select2" data-placeholder="Select Environment" data-fouc>
																	<option value="">Select Environment</option>
																	<option value="SANBOX">SANDBOX</option>
																	<option value="LIVE">LIVE</option>
																</select>
															</div>
															<div class="text-right">
																<button id="btn_pesapal_settings" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE PESAPAL SETTINGS</button>
															</div>										
														</div>
													</div>
												</div>
											</div>
										</fieldset>												
									</form>
                        		<?php endif; ?>
							</div>							
						</div>
					</div>
				</div>
			</div>


				



		