					<div class="w-100">
						<div class="row">
							<div class="col-lg-12">
								<?php if ($mpesa_settings_exists == true): ?>
                        			<?php foreach($mpesa_settings as $row): ?>
                        				<form id="frm_mpesa_settings" name="frm_mpesa_settings" method="post" onsubmit="return save_mpesa_settings();" autocomplete="off" enctype="multipart/form-data">		

                        					<fieldset <?php if ($sbr_mpesa_settings_edit == false){ echo 'disabled'; } ?>>								

			                   					<div class="row">

													<div class="col-md-5">
														<div class="card rounded-top-0">
															<div class="card-header alpha-grey text-primary header-elements-inline pt-2 pb-2">
																<h6 class="card-title font-weight-bold"><i class="icon-mobile3"></i> M-Pesa Settings</h6>			
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
																	<label>Passkey <span class="text-danger">*</span></label>
																	<input id="passkey" name="passkey" type="text" class="form-control" placeholder="" value="<?php echo $row->passkey; ?>">
																</div>																
																<div class="form-group mb-2">
																	<label>Environment <span class="text-danger">*</span></label>
																	<select id="environment" name="environment" class="form-control form-control-select2" data-placeholder="Select Environment" data-fouc>
																		<option value="">Select Environment</option>
																		<option value="SANBOX" <?php if ($row->environment == 'SANDBOX'){ echo 'selected'; } ?>>SANDBOX</option>
																		<option value="LIVE" <?php if ($row->environment == 'LIVE'){ echo 'selected'; } ?>>LIVE</option>
																	</select>
																</div>
																<div class="form-group">
																	<label>Short Code <small>(Paybill/Till Number)</small><span class="text-danger">*</span></label>
																	<input id="short_code" name="short_code" type="number" class="form-control" placeholder="" value="<?php echo $row->short_code; ?>">
																</div>

																<div class="text-right">
																	<button id="btn_mpesa_settings" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE MPESA SETTINGS</button>
																</div>

															</div>
														</div>
													</div>
												</div>
											</fieldset>
										</form>
                        			<?php endforeach; ?>
                        		<?php else: ?>		                        			
                        			<form id="frm_mpesa_settings" name="frm_mpesa_settings" method="post" onsubmit="return save_mpesa_settings();" autocomplete="off" enctype="multipart/form-data">
                        				<fieldset <?php if ($sbr_mpesa_settings_edit == false){ echo 'disabled'; } ?>>
											<div class="row">
												<div class="col-md-5">
													<div class="card rounded-top-0">
														<div class="card-header alpha-grey text-success-800 header-elements-inline pt-2 pb-2">
															<h6 class="card-title text-uppercase"><i class="icon-mobile3"></i> M-Pesa Settings</h6>			
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
																<label>Passkey <span class="text-danger">*</span></label>
																<input id="passkey" name="passkey" type="text" class="form-control" placeholder="">
															</div>																
															<div class="form-group mb-2">
																<label>Environment <span class="text-danger">*</span></label>
																<select id="environment" name="environment" class="form-control form-control-select2" data-placeholder="Select Environment" data-fouc>
																	<option value="">Select Environment</option>
																	<option value="SANBOX">SANDBOX</option>
																	<option value="LIVE">LIVE</option>
																</select>
															</div>
															<div class="form-group">
																<label>Short Code <small>(Paybill/Till Number)</small><span class="text-danger">*</span></label>
																<input id="short_code" name="short_code" type="number" class="form-control" placeholder="">
															</div>
															<div class="text-right">
																<button id="btn_mpesa_settings" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE MPESA SETTINGS</button>
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


				



		