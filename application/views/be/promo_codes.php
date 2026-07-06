		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="#" class="breadcrumb-item">Promotions</a>
							<span class="breadcrumb-item active">Promo Codes</span>
						</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content pt-0">
				<div class="row">
					<div class="col-lg-12">
						<div class="card rounded-top-0">
							<div class="card-header bg-transparent header-elements-inline p-2">
								<h6 class="card-title font-weight-600">Promo Codes</h6>			
								<!-- <div class="header-elements">
									<a href="<?php echo base_url();?>be/promo_codes/add" class="btn btn-sm btn-primary"><i class="icon-plus-circle2"></i> Add New promo_code</a>
								</div> -->			
							</div>

							<div class="card-body">
								<div class="row">
									<div class="col-md-4">
										<div class="card rounded-top-0">
											<div class="card-body">
												<form id="frm_add_promo_code" name="frm_add_promo_code" method="post" onsubmit="return save_promo_code();" autocomplete="off" enctype="multipart/form-data">

													<fieldset <?php if ($sbr_promo_codes_add == false){ echo 'disabled'; } ?>>

														<h6 class="text-primary mb-20 font-weight-600"><i class="icon-plus-circle2"></i> Add New Promo Code</h6>

														<div id="div_add_error" class="alert alert-danger display-none font-13"></div>
					                   					<div id="div_add_success" class="alert alert-success display-none font-13"></div>

					                   					<div class="row">
															<div class="col-md-8">
																<div class="form-group">
																	<label>Promo Code Name <span class="text-danger">*</span></label>
																	<input id="add_promo_code_name" name="promo_code_name" type="text" class="form-control" placeholder="">
																</div>
															</div>
															<div class="col-md-4">
																<div class="form-group">
																	<label>Promo Code <span class="text-danger">*</span></label>
																	<input id="add_promo_code" name="promo_code" type="text" class="form-control" placeholder="">
																</div>
															</div>
														</div>
														<div class="row mb-2">
															<div class="col-sm-8">
																<label>Mode <span class="error">*</span></label>
																<select id="add_promo_mode" name="promo_mode" class="form-control form-control-select2" data-placeholder="Select Mode" data-fouc>
																	<option value="">Select Mode</option>
																	<option value="Percentage">Percentage</option>
																	<option value="Amount">Amount</option>
																</select>
															</div>
															<div class="col-md-4">
																<div id="div_add_promo_value">
																	<div class="form-group">
																		<label>Value <span class="text-danger">*</span></label>
																		<input id="add_promo_value" name="promo_value" type="number" class="form-control" placeholder="" min="0">
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-8">
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
															<div class="col-md-4">
																<div class="form-group">
																	<label>Sort Key <span class="text-danger">*</span></label>
																	<input id="add_sort_key" name="sort_key" type="number" class="form-control" placeholder="" value="0">
																</div>
															</div>
														</div>
														<div class="text-right">
															<button id="btn_add_promo_code" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE NEW PROMO CODE</button>
														</div>
													</fieldset>
												</form>
											</div>
										</div>
									</div>
									<!-- <div class="col-md-1"></div> -->
									<div class="col-md-8">
										<form method="post" class="form">
											<div id="promo_codes_div" style="min-height: 400px;">
									
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-2 font-weight-600">
														<select id="sl_promo_codes_bulk_action" name="sl_promo_codes_bulk_action" class="form-control form-control-select2" data-placeholder="Bulk Action" data-fouc>
															<option value="">Bulk Action</option>
															<option value="Delete">Delete</option>
														</select>
													</div>
													<div class="col-sm-2">
														<button id="btn_promo_codes_bulk_action" type="button" class="btn btn-sm btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> APPLY</button>
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


			<!-- Edit modal form -->
			<div id="modal_edit_promo_code" class="modal fade">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-pencil6"></i> Edit Promo Code</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_edit_promo_code" name="frm_edit_promo_code" method="post" onsubmit="return update_promo_code();">
							<fieldset <?php if ($sbr_promo_codes_edit == false){ echo 'disabled'; } ?>>
								<div class="modal-body">

									<div id="div_edit_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_edit_success" class="alert alert-success display-none font-13"></div>

	                   				<input id="edit_promo_code_id" name="promo_code_id" type="hidden" placeholder="" class="form-control">

									
									<div class="row">
										<div class="col-md-7">
											<div class="form-group mb-2">
												<label>Promo Code Name <span class="error">*</span></label>
												<input id="edit_promo_code_name" name="promo_code_name" type="text" placeholder="" class="form-control">
											</div>
										</div>
										<div class="col-md-5">
											<div class="form-group">
												<label>Promo Code <span class="text-danger">*</span></label>
												<input id="edit_promo_code" name="promo_code" type="text" class="form-control" placeholder="">
											</div>
										</div>
									</div>

									<div class="row mb-2">
										<div class="col-sm-7">
											<label>Mode <span class="error">*</span></label>
											<select id="edit_promo_mode" name="promo_mode" class="form-control form-control-select2" data-placeholder="Select Mode" data-fouc>
												<option value="">Select Mode</option>
												<option value="Percentage">Percentage</option>
												<option value="Amount">Amount</option>
											</select>
										</div>
										<div class="col-md-5">
											<div id="div_edit_promo_value">
												<div class="form-group">
													<label>Value <span class="text-danger">*</span></label>
													<input id="edit_promo_value" name="promo_value" type="number" class="form-control" placeholder="" min="0">
												</div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-8">
											<label class="d-block">Status <span class="error">*</span></label>
											<div class="form-check form-check-inline form-check-right">
												<label class="form-check-label font-weight-semibold">
													Active
													<input type="radio" class="form-check-input" id="edit_is_active_1" name="is_active" value="1">
												</label>
											</div>

											<div class="form-check form-check-inline form-check-right">
												<label class="form-check-label font-weight-semibold">
													Inactive
													<input type="radio" class="form-check-input" id="edit_is_active_0" name="is_active" value="0">
												</label>
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Sort Key <span class="text-danger">*</span></label>
												<input id="edit_sort_key" name="sort_key" type="number" class="form-control" placeholder="" value="">
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">								
									<button type="submit" id="btn_update_promo_code" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				load_promo_codes();
			</script>
	