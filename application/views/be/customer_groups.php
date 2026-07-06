		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="#" class="breadcrumb-item">Customers</a>
							<span class="breadcrumb-item active">Customer Groups</span>
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
								<h5 class="card-title font-weight-bold">Customer Groups</h5>
							</div>

							<div class="card-body">
								<div class="row">
									<div class="col-md-4">
										<div class="card rounded-top-0">
											<div class="card-header alpha-grey text-success-800 header-elements-inline pt-2 pb-2">
												<h6 class="card-title text-uppercase"><i class="icon-plus-circle2 mr-1"></i> New Customer Group</h6>			
											</div>
											<div class="card-body">
												<form id="frm_add_customer_group" name="frm_add_customer_group" method="post" onsubmit="return save_customer_group();" autocomplete="off" enctype="multipart/form-data">
													<fieldset <?php if ($sbr_customer_groups_add == false){ echo 'disabled'; } ?>>
														<div id="div_add_error" class="alert alert-danger display-none font-13"></div>
					                   					<div id="div_add_success" class="alert alert-success display-none font-13"></div>

					                   					<div class="form-group">
						                   					<div class="row">
																<div class="col-md-12">
																	<div class="form-group">
																		<label>Group Name <span class="text-danger">*</span></label>
																		<input id="add_customer_group_name" name="customer_group_name" type="text" class="form-control" placeholder="">
																	</div>
																</div>																														
															</div>	
														</div>
														<div class="row">
															<div class="col-md-12">
																<div class="form-group mb-3">
																	<label>Group Description</label>
																	<textarea id="add_customer_group_description" name="customer_group_description" rows="2" cols="3" class="form-control ckeditor"></textarea>
																</div>
															</div>
														</div>
														<div class="form-group mb-3">
															<div class="row">
																<div class="col-sm-9">
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
																<div class="col-sm-3">
																	<label>Sort Key <span class="error">*</span></label>
																	<input id="add_sort_key" name="sort_key" type="number" class="form-control" min="0" value="0">
																</div>															
															</div>
														</div>									
														<div class="text-right">
															<button id="btn_add_customer_group" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE</button>
														</div>
													</fieldset>
												</form>
											</div>
										</div>
									</div>
									<!-- <div class="col-md-1"></div> -->
									<div class="col-md-8">
										<form method="post" class="form">
											<div id="customer_groups_div" style="min-height: 400px;">
									
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-2 font-weight-600">
														<select id="sl_customer_groups_bulk_action" name="sl_customer_groups_bulk_action" class="form-control form-control-select2" data-placeholder="Bulk Action" data-fouc>
															<option value="">Bulk Action</option>
															<option value="Delete">Delete</option>
														</select>
													</div>
													<div class="col-sm-2">
														<button id="btn_customer_groups_bulk_action" type="button" class="btn btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> APPLY</button>
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
			<div id="modal_edit_customer_group" class="modal fade">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<h6 class="modal-title text-success-400 text-uppercase"><i class="icon-pencil6"></i> Edit Customer Group</h6>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_edit_customer_group" name="frm_edit_customer_group" method="post" onsubmit="return update_customer_group();">
							<fieldset <?php if ($sbr_customer_groups_edit == false){ echo 'disabled'; } ?>>
								<div class="modal-body">

									<div id="div_edit_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_edit_success" class="alert alert-success display-none font-13"></div>

	                   				<input id="edit_customer_group_id" name="customer_group_id" type="hidden" placeholder="" class="form-control">

	               					<div class="form-group">
	                   					<div class="row">
											<div class="col-md-12">
												<div class="form-group">
													<label>Group Name <span class="text-danger">*</span></label>
													<input id="edit_customer_group_name" name="customer_group_name" type="text" class="form-control" placeholder="e.g. Toyota">
												</div>
											</div>																														
										</div>	
									</div>
									<div class="row">
										<div class="col-md-12">
											<div class="form-group mb-3">
												<label>Group Description</label>
												<textarea id="edit_customer_group_description" name="customer_group_description" rows="2" cols="3" class="form-control ckeditor"></textarea>
											</div>
										</div>
									</div>
									<div class="form-group mb-3">
										<div class="row">
											<div class="col-sm-9">
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
											<div class="col-sm-3">
												<label>Sort Key <span class="error">*</span></label>
												<input id="edit_sort_key" name="sort_key" type="number" class="form-control" min="0" value="0">
											</div>															
										</div>
									</div>									
								</div>
								<div class="modal-footer">								
									<button type="submit" id="btn_update_customer_group" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CLOSE</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				CKEDITOR.replace( 'add_customer_group_description', {
                    height: 100,
                    toolbar: [
						{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] }
					]
                } );
                CKEDITOR.replace( 'edit_customer_group_description', {
                    height: 100,
                    toolbar: [
						{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] }
					]
                } );
				load_customer_groups();
			</script>
	