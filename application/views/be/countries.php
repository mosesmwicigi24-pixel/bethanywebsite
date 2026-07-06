					<div class="w-100">
						<div class="row">
							<div class="col-lg-12">
								<div class="card rounded-top-0">
									<div class="card-header bg-transparent header-elements-inline p-2">
										<h5 class="card-title font-weight-bold"><i class="icon-sphere3 mr-2"></i> Countries</h5>
									</div>

									<div class="card-body">
										<div class="row">
											<div class="col-md-4">
												<div class="card rounded-top-0">
													<div class="card-header alpha-grey text-primary header-elements-inline pt-2 pb-2">
																<h6 class="card-title font-weight-bold"><i class="icon-plus-circle2 mr-1"></i> New Country</h6>			
													</div>
													<div class="card-body">
														<form id="frm_add_country" name="frm_add_country" method="post" onsubmit="return save_country();" autocomplete="off" enctype="multipart/form-data">

															<div id="div_add_error" class="alert alert-danger display-none font-13"></div>
						                   					<div id="div_add_success" class="alert alert-success display-none font-13"></div>

						                   					<fieldset <?php if ($sbr_countries_add == false){ echo 'disabled'; } ?>>
							                   					<div class="form-group mb-2">
								                   					<div class="row">
																		<div class="col-md-8">
																			<div class="form-group">
																				<label>Country Name <span class="text-danger">*</span></label>
																				<input id="add_country_name" name="country_name" type="text" class="form-control" placeholder="e.g. Kenya">
																			</div>
																		</div>
																		<div class="col-md-4">
																			<div class="form-group mb-2">
																				<label>Abbrev.</label>
																				<input id="add_country_abbreviation" name="country_abbreviation" type="text" class="form-control" placeholder="e.g. KE">
																			</div>
																		</div>
																		
																	</div>	
																</div>
																<div class="form-group mb-2">
								                   					<div class="row">
																		<div class="col-md-8">
																			<div class="form-group">
																				<label>Nationality</label>
																				<input id="add_nationality" name="nationality" type="text" class="form-control" placeholder="e.g. Kenyan">
																			</div>
																		</div>
																		<div class="col-md-4">
																			<div class="form-group mb-2">
																				<label>Country Code</label>
																				<input id="add_country_code" name="country_code" type="number" class="form-control" placeholder="e.g. 254">
																			</div>
																		</div>
																	</div>	
																</div>									
																<div class="text-right">
																	<button id="btn_add_country" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE</button>
																</div>
															</fieldset>
														</form>
													</div>
												</div>
											</div>
											<!-- <div class="col-md-1"></div> -->
											<div class="col-md-8">
												<form method="post" class="form">
													<div id="countries_div" style="min-height: 400px;">
											
													</div>
													<div class="form-group mb-2">
														<div class="row">
															<div class="col-sm-2 font-weight-600">
																<select id="sl_countries_bulk_action" name="sl_countries_bulk_action" class="form-control form-control-select2" data-placeholder="Bulk Action" data-fouc>
																	<option value="">Bulk Action</option>
																	<option value="Delete">Delete</option>
																</select>
															</div>
															<div class="col-sm-2">
																<button id="btn_countries_bulk_action" type="button" class="btn btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> APPLY</button>
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



			<!-- Edit modal form -->
			<div id="modal_edit_country" class="modal fade">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-pencil6"></i> Edit Country</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_edit_country" name="frm_edit_country" method="post" onsubmit="return update_country();">
							<fieldset <?php if ($sbr_countries_edit == false){ echo 'disabled'; } ?>>

								<div class="modal-body">

									<div id="div_edit_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_edit_success" class="alert alert-success display-none font-13"></div>

	                   				<input id="edit_country_id" name="country_id" type="hidden" placeholder="" class="form-control">

									<div class="form-group mb-2">
										<div class="row">
											<div class="col-sm-8">
												<label>Country Name <span class="error">*</span></label>
												<input id="edit_country_name" name="country_name" type="text" placeholder="" class="form-control">
											</div>
											<div class="col-sm-4">
												<label>Abbrev</label>
												<input id="edit_country_abbreviation" name="country_abbreviation" type="ntext" placeholder="" class="form-control">
											</div>
										</div>
									</div>
									<div class="form-group mb-2">
										<div class="row">
											<div class="col-sm-8">
												<label>Nationality</label>
												<input id="edit_nationality" name="nationality" type="text" placeholder="" class="form-control">
											</div>
											<div class="col-sm-4">
												<label>Country Code</label>
												<input id="edit_country_code" name="country_code" type="number" placeholder="" class="form-control">
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">								
									<button type="submit" id="btn_edit_country" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CLOSE</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				load_countries();
			</script>
	