					<div class="w-100">
						<div class="row">
							<div class="col-lg-12">
								<?php if ($sale_comments_exists == true): ?>
                        			<?php foreach($sale_comments as $row): ?>
                        				<form id="frm_sale_comments" name="frm_sale_comments" method="post" onsubmit="return save_sale_comments();" autocomplete="off" enctype="multipart/form-data">
                        					<fieldset <?php if ($sbr_sale_comments_edit == false){ echo 'disabled'; } ?>>										

			                   					<div class="row">

													<div class="col-md-12">
														<div class="card rounded-top-0">
															<div class="card-header alpha-grey text-primary header-elements-inline pt-2 pb-2">
																<h6 class="card-title font-weight-bold"><i class="icon-file-text"></i> Sale Comments</h6>			
															</div>
															<div class="card-body">

																<div id="div_error" class="alert alert-danger display-none font-13"></div>
			                   									<div id="div_success" class="alert alert-success display-none font-13"></div>

																<div class="form-group mb-2">
																	<label>Cash Sale Comments</label>
																	<textarea id="cash_comments" name="cash_comments" class="form-control" placeholder="" rows="5"><?php echo $row->cash_comments; ?></textarea>
																</div>

																<div class="form-group mb-2">
																	<label>Credit Sale Comments</label>
																	<textarea id="credit_comments" name="credit_comments" class="form-control" placeholder="" rows="5"><?php echo $row->credit_comments; ?></textarea>
																</div>

																<div class="text-right">
																	<button id="btn_sale_comments" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE COMMENTS</button>
																</div>

															</div>
														</div>
													</div>
												</div>
											</fieldset>
										</form>
                        			<?php endforeach; ?>
                        		<?php else: ?>		                        			
                        			<form id="frm_sale_comments" name="frm_sale_comments" method="post" onsubmit="return save_sale_comments();" autocomplete="off" enctype="multipart/form-data">
                        				<fieldset <?php if ($sbr_sale_comments_edit == false){ echo 'disabled'; } ?>>
											<div class="row">
												<div class="col-md-12">
													<div class="card rounded-top-0">
														<div class="card-header alpha-grey text-success-800 header-elements-inline pt-2 pb-2">
															<h6 class="card-title text-uppercase"><i class="icon-file-text"></i> Sale Comments</h6>			
														</div>
														<div class="card-body">

															<div id="div_error" class="alert alert-danger display-none font-13"></div>
		                   									<div id="div_success" class="alert alert-success display-none font-13"></div>

															<div class="form-group mb-2">
																<label>Cash Sale Comments</label>
																<textarea id="cash_comments" name="cash_comments" class="form-control" placeholder="" rows="5"></textarea>
															</div>

															<div class="form-group mb-2">
																<label>Credit Sale Comments</label>
																<textarea id="credit_comments" name="credit_comments" class="form-control" placeholder="" rows="5"></textarea>
															</div>

															<div class="text-right">
																<button id="btn_sale_comments" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE COMMENTS</button>
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


				



		