		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="#" class="breadcrumb-item">Products</a>
							<span class="breadcrumb-item active">Product Sizes</span>
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
								<h5 class="card-title font-weight-bold"><i class="icon-stats-bars2 mr-2"></i> Product Sizes</h5>
							</div>

							<div class="card-body">
								<div class="row">
									<div class="col-md-4">
										<div class="card rounded-top-0">
											<div class="card-header alpha-grey text-success-800 header-elements-inline pt-2 pb-2">
												<h6 class="card-title text-uppercase"><i class="icon-plus-circle2 mr-1"></i> New Size</h6>			
											</div>
											<div class="card-body">
												<form id="frm_add_product_size" name="frm_add_product_size" method="post" onsubmit="return save_product_size();" autocomplete="off" enctype="multipart/form-data">

													<fieldset <?php if ($sbr_product_sizes_add == false){ echo 'disabled'; } ?>>

														<div id="div_add_error" class="alert alert-danger display-none font-13"></div>
					                   					<div id="div_add_success" class="alert alert-success display-none font-13"></div>

					                   					<div class="row">
															<div class="col-md-7">
																<div class="form-group mb-3">
																	<label>Name <span class="error">*</span></label>
																	<input id="add_product_size_name" name="product_size_name" type="text" class="form-control" placeholder="">
																</div>
															</div>
															<div class="col-md-5">
																<div class="form-group mb-3">
																	<label>Code <span class="error">*</span></label>
																	<input id="add_product_size_code" name="product_size_code" type="text" class="form-control" placeholder="">
																</div>
															</div>
														</div>
														<div class="form-group mb-3 mb-md-2">
															<div class="row">															
																<div class="col-sm-7">
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
																<div class="col-sm-5">
																	<label>Sort Key <span class="error">*</span></label>
																	<input id="add_sort_key" name="sort_key" type="number" class="form-control" min="0" value="0">
																</div>
															</div>
														</div>
														<div class="text-right">
															<button id="btn_add_product_size" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE SIZE</button>
														</div>
													</fieldset>
												</form>
											</div>
										</div>
									</div>
									<!-- <div class="col-md-1"></div> -->
									<div class="col-md-8">
										<form method="post" class="form">
											<div id="product_sizes_div" style="min-height: 400px;">
									
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-3 font-weight-600">
														<select id="sl_product_sizes_bulk_action" name="sl_product_sizes_bulk_action" class="form-control form-control-select2" data-placeholder="Bulk Action" data-fouc>
															<option value="">Bulk Action</option>
															<option value="Delete">Delete</option>
														</select>
													</div>
													<div class="col-sm-2">
														<button id="btn_product_sizes_bulk_action" type="button" class="btn btn-sm btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> APPLY</button>
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

			<div id="modal_edit_product_size" class="modal fade" tabindex="-1">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<h6 class="modal-title text-success-400 text-uppercase"><i class="icon-pencil6"></i> Edit Size</h6>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_edit_product_size" name="frm_edit_product_size" method="post" onsubmit="return update_product_size();">
							<fieldset <?php if ($sbr_product_sizes_edit == false){ echo 'disabled'; } ?>>
								<div class="modal-body">

									<div id="div_edit_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_edit_success" class="alert alert-success display-none font-13"></div>

	                   				<input id="edit_product_size_id" name="product_size_id" type="hidden" placeholder="" class="form-control">

	 								<div class="form-group">
										<div class="row">
											<div class="col-sm-8">
												<label>Name <span class="error">*</span></label>
												<input id="edit_product_size_name" name="product_size_name" type="text" placeholder="" class="form-control">
											</div>
											<div class="col-sm-4">
												<label>Code <span class="error">*</span></label>
												<input id="edit_product_size_code" name="product_size_code" type="text" placeholder="" maxlength="4" class="form-control">
											</div>
										</div>
									</div>

									<div class="form-group mb-3 mb-md-2">
										<div class="row">										
											<div class="col-sm-8">
												<label class="d-block">Status <span class="error">*</span></label>
												<div class="form-check form-check-inline form-check-right">
													<label class="form-check-label font-weight-semibold">
														Active
														<input type="radio" class="form-check-input" name="is_active" id="edit_is_active_1" value="1">
													</label>
												</div>

												<div class="form-check form-check-inline form-check-right">
													<label class="form-check-label font-weight-semibold">
														Inactive
														<input type="radio" class="form-check-input" name="is_active" id="edit_is_active_0" value="0">
													</label>
												</div>
											</div>
											<div class="col-sm-4">
												<label>Sort Key <span class="error">*</span></label>
												<input id="edit_sort_key" name="sort_key" type="number" class="form-control" min="0" value="0">
											</div>
										</div>
									</div>
								</div>

								<div class="modal-footer">								
									<button type="submit" id="btn_update_product_size" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CLOSE</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				load_product_sizes();
			</script>
	