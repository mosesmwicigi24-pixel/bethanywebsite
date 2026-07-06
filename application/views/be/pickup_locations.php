
					<div class="w-100">
						<div class="row">
							<div class="col-lg-12">
								<div class="card rounded-top-0">
									<div class="card-header bg-transparent header-elements-inline p-2">
										<h5 class="card-title font-weight-bold"><i class="icon-cart-remove mr-2"></i> Pickup Locations</h5>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-lg-12">
												
												<div class="row">
													<div class="col-md-4">
														<div class="card rounded-top-0">
															<div class="card-header alpha-grey text-primary header-elements-inline pt-2 pb-2">
																<h6 class="card-title font-weight-bold"><i class="icon-plus-circle2 mr-1"></i> New Pickup Location</h6>			
															</div>
															<div class="card-body">
																<form id="frm_add_pickup_location" name="frm_add_pickup_location" method="post" onsubmit="return save_pickup_location();" autocomplete="off" enctype="multipart/form-data">

																	<div id="div_add_error" class="alert alert-danger display-none font-13"></div>
								                   					<div id="div_add_success" class="alert alert-success display-none font-13"></div>

								                   					<fieldset <?php if ($sbr_pickup_locations_add == false){ echo 'disabled'; } ?>>

									                   					<div class="form-group mb-2">
										                   					<div class="row">
																				<div class="col-md-12">
																					<div class="form-group">
																						<label>Region <span class="error">*</span></label>
																						<select id="add_region_id" name="region_id" class="form-control form-control-select2" data-placeholder="Select Region" data-fouc>
																							<option value="">Select Region</option>
																							<?php foreach ($countries as $row): ?>
																								<optgroup label="<?php echo $row->country_name; ?>">
																									<?php
																										if(!empty($row->cr)){
																											foreach($row->cr as $country_region) {
																												echo '<option value="' . $country_region->region_id . '">' . $country_region->region_name . ', ' . $row->country_name . '</option>';
																											}
																										}
																									?>
																								</optgroup>
																							<?php endforeach; ?>
																						</select>
																					</div>
																				</div>
																			</div>	
																		</div>
									                   					<div class="form-group mb-2">
										                   					<div class="row">
																				<div class="col-md-12">
																					<div class="form-group">
																						<label>Pickup Location Name <span class="text-danger">*</span></label>
																						<input id="add_pickup_location_name" name="pickup_location_name" type="text" class="form-control" placeholder="">
																					</div>
																				</div>								
																			</div>	
																		</div>
																		<div class="form-group mb-2">
										                   					<div class="row">
																				<div class="col-md-6">
																					<div class="form-group">
																						<label>Pickup Location Address <span class="text-danger">*</span></label>
																						<textarea id="add_pickup_location_address" name="pickup_location_address" rows="3" class="form-control"></textarea>
																					</div>
																				</div>
																				<div class="col-md-6">
																					<div class="form-group">
																						<label>Close To</label>
																						<textarea id="add_close_to" name="close_to" rows="3" class="form-control"></textarea>
																					</div>
																				</div>								
																			</div>	
																		</div>
																		<div class="form-group mb-2">
										                   					<div class="row">
																				<div class="col-md-12">
																					<div class="form-group">
																						<label>Opening Hours <span class="text-danger">*</span></label>
																						<input id="add_opening_hours" name="opening_hours" type="text" class="form-control" placeholder="">
																					</div>
																				</div>								
																			</div>	
																		</div>
																		<div class="form-group mb-2">
										                   					<div class="row">
																				<div class="col-md-6">
																					<div class="form-group">
																						<label>Shipping Fee <span class="text-danger">*</span></label>
																						<input id="add_shipping_fee" name="shipping_fee" type="number" class="form-control" placeholder="" value="0">
																					</div>
																				</div>
																				<div class="col-md-6">
																					<div class="form-group">
																						<label>Pickup Period <span class="text-danger">*</span></label>
																						<input id="add_pickup_period" name="pickup_period" type="text" class="form-control" placeholder="">
																					</div>
																				</div>								
																			</div>	
																		</div>
																		<div class="form-group mb-3">
										                   					<div class="row">									                   						
																				<div class="col-sm-6">
																					<label class="d-block">Status <span class="error">*</span></label>
																					<div class="form-check form-check-inline form-check-right">
																						<label class="form-check-label font-weight-semibold">
																							Active
																							<input type="radio" class="form-check-input" id="add_is_active_1" name="is_active" value="1" checked>
																						</label>
																					</div>

																					<div class="form-check form-check-inline form-check-right">
																						<label class="form-check-label font-weight-semibold">
																							Inactive
																							<input type="radio" class="form-check-input" id="add_is_active_0" name="is_active" value="0">
																						</label>
																					</div>
																				</div>
																				<div class="col-md-6">
																					<div class="form-group">
																						<label>Sort Key <span class="error">*</span></label>
																						<input id="add_sort_key" name="sort_key" type="number" class="form-control" placeholder="" value="0">
																					</div>
																				</div>
										                   					</div>
										                   				</div>
																								
																		<div class="text-right">
																			<button id="btn_add_pickup_location" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE</button>
																		</div>
																	</fieldset>
																</form>
															</div>
														</div>
													</div>
													<!-- <div class="col-md-1"></div> -->
													<div class="col-md-8">
														<form method="post" class="form">
															<div id="pickup_locations_div" style="min-height: 500px;">
													
															</div>
															<div class="form-group mb-2">
																<div class="row">
																	<div class="col-sm-2 font-weight-600">
																		<select id="sl_pickup_locations_bulk_action" name="sl_pickup_locations_bulk_action" class="form-control form-control-select2" data-placeholder="Bulk Action" data-fouc>
																			<option value="">Bulk Action</option>
																			<option value="Delete">Delete</option>
																		</select>
																	</div>
																	<div class="col-sm-2">
																		<button id="btn_pickup_locations_bulk_action" type="button" class="btn btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> APPLY</button>
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

			<div id="modal_edit_pickup_location" class="modal fade" tabindex="-1">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-pencil6"></i> Edit Pickup Location</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_edit_pickup_location" name="frm_edit_pickup_location" method="post" onsubmit="return update_pickup_location();">
							<fieldset <?php if ($sbr_pickup_locations_edit == false){ echo 'disabled'; } ?>>
								<div class="modal-body">

									<div id="div_edit_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_edit_success" class="alert alert-success display-none font-13"></div>

	                   				<input id="edit_pickup_location_id" name="pickup_location_id" type="hidden" placeholder="" class="form-control">

	               					<div class="form-group mb-2">
	                   					<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label>Region <span class="error">*</span></label>
													<select id="edit_region_id" name="region_id" class="form-control form-control-select2" data-placeholder="Select Region" data-fouc>
														<option value="">Select Region</option>
														<?php foreach ($countries as $row): ?>
															<optgroup label="<?php echo $row->country_name; ?>">
																<?php
																	if(!empty($row->cr)){
																		foreach($row->cr as $country_region) {
																			echo '<option value="' . $country_region->region_id . '">' . $country_region->region_name . ', ' . $row->country_name . '</option>';
																		}
																	}
																?>
															</optgroup>
														<?php endforeach; ?>
													</select>
												</div>
											</div>
										</div>	
									</div>
	               					<div class="form-group mb-2">
	                   					<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label>Pickup Location Name <span class="text-danger">*</span></label>
													<input id="edit_pickup_location_name" name="pickup_location_name" type="text" class="form-control" placeholder="">
												</div>
											</div>								
										</div>	
									</div>
									<div class="form-group mb-2">
	                   					<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Pickup Location Address <span class="text-danger">*</span></label>
													<textarea id="edit_pickup_location_address" name="pickup_location_address" rows="3" class="form-control"></textarea>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Close To</label>
													<textarea id="edit_close_to" name="close_to" rows="3" class="form-control"></textarea>
												</div>
											</div>								
										</div>	
									</div>
									<div class="form-group mb-2">
	                   					<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label>Opening Hours <span class="text-danger">*</span></label>
													<input id="edit_opening_hours" name="opening_hours" type="text" class="form-control" placeholder="">
												</div>
											</div>								
										</div>	
									</div>
									<div class="form-group mb-2">
	                   					<div class="row">
											<div class="col-md-6">
												<div class="form-group">
													<label>Shipping Fee <span class="text-danger">*</span></label>
													<input id="edit_shipping_fee" name="shipping_fee" type="number" class="form-control" placeholder="" value="0">
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Pickup Period <span class="text-danger">*</span></label>
													<input id="edit_pickup_period" name="pickup_period" type="text" class="form-control" placeholder="">
												</div>
											</div>								
										</div>	
									</div>
									<div class="form-group mb-3">
	                   					<div class="row">									                   						
											<div class="col-sm-6">
												<label class="d-block">Status <span class="error">*</span></label>
												<div class="form-check form-check-inline form-check-right">
													<label class="form-check-label font-weight-semibold">
														Active
														<input type="radio" class="form-check-input" id="edit_is_active_1" name="is_active" value="1" checked>
													</label>
												</div>

												<div class="form-check form-check-inline form-check-right">
													<label class="form-check-label font-weight-semibold">
														Inactive
														<input type="radio" class="form-check-input" id="edit_is_active_0" name="is_active" value="0">
													</label>
												</div>
											</div>
											<div class="col-md-6">
												<div class="form-group">
													<label>Sort Key <span class="error">*</span></label>
													<input id="edit_sort_key" name="sort_key" type="number" class="form-control" placeholder="" value="0">
												</div>
											</div>
	                   					</div>
	                   				</div>
								</div>
								<div class="modal-footer">								
									<button type="submit" id="btn_update_pickup_location" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CLOSE</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				load_pickup_locations();
			</script>


				



		