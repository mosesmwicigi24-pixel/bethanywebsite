					<div class="w-100">
						<div class="row">
							<div class="col-lg-12">
								<div class="card rounded-top-0">
									<div class="card-header bg-transparent header-elements-inline p-2">
										<h5 class="card-title font-weight-bold"><i class="icon-map mr-2"></i> Regions (<?php foreach ($country as $row){ echo $row->country_name; } ?>)</h5>
										<div class="header-elements">
											<a href="<?php echo base_url();?>be/settings/countries" class="btn btn-sm bg-success"><i class="icon-arrow-left15"></i> Countries</a>
										</div>
									</div>
									<div class="card-body">
										<div class="row">
											<div class="col-md-4">
												<div class="card rounded-top-0">
													<div class="card-header alpha-grey text-primary header-elements-inline pt-2 pb-2">
																<h6 class="card-title font-weight-bold"><i class="icon-plus-circle2 mr-1"></i> New Region</h6>			
													</div>
													<div class="card-body">
														<form id="frm_add_region" name="frm_add_region" method="post" onsubmit="return save_region();" autocomplete="off" enctype="multipart/form-data">

															<div id="div_add_error" class="alert alert-danger display-none font-13"></div>
						                   					<div id="div_add_success" class="alert alert-success display-none font-13"></div>

						                   					<fieldset <?php if ($sbr_regions_edit == false){ echo 'disabled'; } ?>>

							                   					<input type="hidden" id="add_country_id" name="country_id" value="<?php echo $country_id; ?>">

							                   					<div class="form-group mb-3">
								                   					<div class="row">
																		<div class="col-md-9">
																			<div class="form-group">
																				<label>Region Name <span class="text-danger">*</span></label>
																				<input id="add_region_name" name="region_name" type="text" class="form-control" placeholder="e.g. Nairobi">
																			</div>
																		</div>
																		<div class="col-md-3">
																			<div class="form-group mb-3">
																				<label>Sort Key <span class="text-danger">*</span></label>
																				<input id="add_sort_key" name="sort_key" type="number" class="form-control" placeholder="" value="0">
																			</div>
																		</div>
																		
																	</div>	
																</div>
																<div class="text-right">
																	<button id="btn_add_region" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE</button>
																</div>
															</fieldset>
														</form>
													</div>
												</div>
											</div>
											<!-- <div class="col-md-1"></div> -->
											<div class="col-md-8">
												<form method="post" class="form">
													<div id="regions_div" style="min-height: 400px;">
											
													</div>
													<div class="form-group mb-2">
														<div class="row">
															<div class="col-sm-2 font-weight-600">
																<select id="sl_regions_bulk_action" name="sl_regions_bulk_action" class="form-control form-control-select2" data-placeholder="Bulk Action" data-fouc>
																	<option value="">Bulk Action</option>
																	<option value="Delete">Delete</option>
																</select>
															</div>
															<div class="col-sm-2">
																<button id="btn_regions_bulk_action" type="button" class="btn btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> APPLY</button>
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
			<div id="modal_edit_region" class="modal fade">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-pencil6"></i> Edit Region</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_edit_region" name="frm_edit_region" method="post" onsubmit="return update_region();">
							<fieldset <?php if ($sbr_regions_edit == false){ echo 'disabled'; } ?>>
								<div class="modal-body">

									<div id="div_edit_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_edit_success" class="alert alert-success display-none font-13"></div>

	                   				<input type="hidden" id="edit_country_id" name="country_id" value="<?php echo $country_id; ?>">
	                   				<input id="edit_region_id" name="region_id" type="hidden" placeholder="" class="form-control">

									<div class="form-group mb-2">
										<div class="row">
											<div class="col-sm-8">
												<label>Region Name <span class="error">*</span></label>
												<input id="edit_region_name" name="region_name" type="text" placeholder="" class="form-control">
											</div>
											<div class="col-sm-4">
												<label>Sort Key <span class="text-danger">*</span></label>
												<input id="edit_sort_key" name="sort_key" type="number" placeholder="" class="form-control">
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">								
									<button type="submit" id="btn_edit_region" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CLOSE</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				load_regions();
			</script>
	