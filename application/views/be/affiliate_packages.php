		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<span class="breadcrumb-item active">Affiliate Packages</span>
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
								<h5 class="card-title font-weight-bold"><!-- <i class="icon-users"></i> -->Affiliate Packages</h5>			
							</div>

							<div class="card-body">
								<div class="row">
									<div class="col-md-4">
										<div class="card rounded-top-0">
											<div class="card-body">
												<form id="frm_add_affiliate_package" name="frm_add_affiliate_package" method="post" onsubmit="return save_affiliate_package();" autocomplete="off" enctype="multipart/form-data">

													<fieldset <?php if ($sbr_affiliate_packages_add == false){ echo 'disabled'; } ?>>

														<h6 class="text-primary mb-20 font-weight-600"><i class="icon-plus-circle2"></i> Add New Package</h6>

														<div id="div_add_error" class="alert alert-danger display-none font-13"></div>
					                   					<div id="div_add_success" class="alert alert-success display-none font-13"></div>

					                   					<div class="row">
															<div class="col-md-8">
																<div class="form-group">
																	<label>Package Name <span class="text-danger">*</span></label>
																	<input id="add_affiliate_package_name" name="affiliate_package_name" type="text" class="form-control" placeholder="">
																</div>
															</div>
															<div class="col-md-4">
																<div class="form-group">
																	<label>Colour Code <span class="text-danger">*</span></label>
																	<input id="add_affiliate_package_colour_code" name="affiliate_package_colour_code" type="text" class="form-control colorpicker-show-input"  data-preferred-format="hex" value="#000000" placeholder="">
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-4">
																<div class="form-group">
																	<label>Commission(%) <span class="text-danger">*</span></label>
																	<input id="add_commission" name="commission" type="number" class="form-control" placeholder="">
																</div>
															</div>
															<div class="col-md-4">
																<div class="form-group">
																	<label>Minimum Pay Out <span class="text-danger">*</span></label>
																	<input id="add_minimum_pay_out" name="minimum_pay_out" type="number" class="form-control" placeholder="">
																</div>
															</div>
															<div class="col-md-4">
																<div class="form-group mb-3">
																	<label class="d-block">Status <span class="text-danger">*</span></label>
																	<div class="form-check">
																		<label class="form-check-label">
																			<input type="radio" class="form-check-input" id="add_is_active_1" name="is_active" value="1" checked>
																			Active
																		</label>
																	</div>

																	<div class="form-check">
																		<label class="form-check-label">
																			<input type="radio" class="form-check-input" id="add_is_active_0" name="is_active" value="0">
																			Inactive
																		</label>
																	</div>
																</div>
															</div>
														</div>
														<div class="row">
															<div class="col-md-12">
																<div class="form-group mb-3">
																	<label>Package Features</label>
																	<textarea id="add_affiliate_package_features" name="affiliate_package_features" rows="2" cols="3" class="form-control ckeditor"></textarea>
																</div>
															</div>
														</div>
														<div class="text-right">
															<button id="btn_add_affiliate_package" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE NEW PACKAGE</button>
														</div>
													</fieldset>
												</form>
											</div>
										</div>
									</div>
									<!-- <div class="col-md-1"></div> -->
									<div class="col-md-8">
										<form method="post" class="form">
											<div id="affiliate_packages_div" style="min-height: 400px;">
									
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-2 font-weight-600">
														<select id="sl_affiliate_packages_bulk_action" name="sl_affiliate_packages_bulk_action" class="form-control form-control-select2" data-placeholder="Bulk Action" data-fouc>
															<option value="">Bulk Action</option>
															<option value="Delete">Delete</option>
														</select>
													</div>
													<div class="col-sm-2">
														<button id="btn_affiliate_packages_bulk_action" type="button" class="btn btn-sm btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> APPLY</button>
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
			<div id="modal_edit_affiliate_package" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-pencil6"></i> Edit Package</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_edit_affiliate_package" name="frm_edit_affiliate_package" method="post" onsubmit="return update_affiliate_package();">
							<fieldset <?php if ($sbr_affiliate_packages_edit == false){ echo 'disabled'; } ?>>
								<div class="modal-body">

									<div id="div_edit_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_edit_success" class="alert alert-success display-none font-13"></div>

	                   				<input id="edit_affiliate_package_id" name="affiliate_package_id" type="hidden" placeholder="" class="form-control">

									
									<div class="row">
										<div class="col-sm-8">
											<div class="form-group mb-2">
												<label>Package Name <span class="error">*</span></label>
												<input id="edit_affiliate_package_name" name="affiliate_package_name" type="text" placeholder="" class="form-control">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group mb-2">
												<label>Colour Code <span class="text-danger">*</span></label><br>
												<input id="edit_affiliate_package_colour_code" name="affiliate_package_colour_code" type="text" class="form-control colorpicker-show-input"  data-preferred-format="hex" value="#000000" placeholder="">
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-md-4">
											<div class="form-group">
												<label>Commission(%) <span class="text-danger">*</span></label>
												<input id="edit_commission" name="commission" type="number" class="form-control" placeholder="">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group">
												<label>Minimum Pay Out <span class="text-danger">*</span></label>
												<input id="edit_minimum_pay_out" name="minimum_pay_out" type="number" class="form-control" placeholder="">
											</div>
										</div>
										<div class="col-md-4">
											<div class="form-group mb-3">
												<label class="d-block">Status <span class="text-danger">*</span></label>
												<div class="form-check">
													<label class="form-check-label">
														<input type="radio" class="form-check-input" id="edit_is_active_1" name="is_active" value="1" checked>
														Active
													</label>
												</div>

												<div class="form-check">
													<label class="form-check-label">
														<input type="radio" class="form-check-input" id="edit_is_active_0" name="is_active" value="0">
														Inactive
													</label>
												</div>
											</div>
										</div>
									</div>

									<div class="row">
										<div class="col-md-12">
											<div class="form-group mb-3">
												<label>Package Features</label>
												<textarea id="edit_affiliate_package_features" name="affiliate_package_features" rows="2" cols="3" class="form-control ckeditor"></textarea>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">								
									<button type="submit" id="btn_update_affiliate_package" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				CKEDITOR.replace( 'add_affiliate_package_features', {
                    height: 120,
                    toolbar: [
						{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] }
					]
                });
                CKEDITOR.replace( 'edit_affiliate_package_features', {
                    height: 120,
                    toolbar: [
						{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] }
					]
                });
				load_affiliate_packages();
			</script>
	