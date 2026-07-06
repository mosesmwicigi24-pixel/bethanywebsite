					<div class="w-100">
						<div class="row">
							<div class="col-lg-12">
								<?php if ($bitly_settings_exists == true): ?>
                        			<?php foreach($bitly_settings as $row): ?>
                        				<form id="frm_bitly_settings" name="frm_bitly_settings" method="post" onsubmit="return save_bitly_settings();" autocomplete="off" enctype="multipart/form-data">
                        					<fieldset <?php if ($sbr_bitly_settings_edit == false){ echo 'disabled'; } ?>>										

			                   					<div class="row">

													<div class="col-md-5">
														<div class="card rounded-top-0">
															<div class="card-header alpha-grey text-primary header-elements-inline pt-2 pb-2">
																<h6 class="card-title font-weight-bold"><i class="icon-link2"></i> Bitly Settings</h6>			
															</div>
															<div class="card-body">

																<div id="div_error" class="alert alert-danger display-none font-13"></div>
			                   									<div id="div_success" class="alert alert-success display-none font-13"></div>

																<div class="form-group mb-2">
																	<label>Generic Access Token <span class="text-danger">*</span></label>
																	<input id="generic_access_token" name="generic_access_token" type="text" class="form-control" placeholder="" value="<?php echo $row->generic_access_token; ?>">
																</div>

																<div class="text-right">
																	<button id="btn_bitly_settings" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE BITLY SETTINGS</button>
																</div>

															</div>
														</div>
													</div>
												</div>
											</fieldset>
										</form>
                        			<?php endforeach; ?>
                        		<?php else: ?>		                        			
                        			<form id="frm_bitly_settings" name="frm_bitly_settings" method="post" onsubmit="return save_bitly_settings();" autocomplete="off" enctype="multipart/form-data">
                        				<fieldset <?php if ($sbr_bitly_settings_edit == false){ echo 'disabled'; } ?>>
											<div class="row">
												<div class="col-md-5">
													<div class="card rounded-top-0">
														<div class="card-header alpha-grey text-success-800 header-elements-inline pt-2 pb-2">
															<h6 class="card-title text-uppercase"><i class="icon-link2"></i> Bitly Settings</h6>			
														</div>
														<div class="card-body">

															<div id="div_error" class="alert alert-danger display-none font-13"></div>
		                   									<div id="div_success" class="alert alert-success display-none font-13"></div>

															<div class="form-group mb-2">
																<label>Generic Access Token <span class="text-danger">*</span></label>
																<input id="generic_access_token" name="generic_access_token" type="text" class="form-control" placeholder="">
															</div>

															<div class="text-right">
																<button id="btn_bitly_settings" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE BITLY SETTINGS</button>
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


				



		