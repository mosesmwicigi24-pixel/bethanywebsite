		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="#" class="breadcrumb-item">CMS Content</a>
							<span class="breadcrumb-item active">Testimonials</span>
						</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content pt-0">
				<div class="row">
					<div class="col-lg-5">
						<div class="card rounded-top-0">
							<div class="card-header bg-transparent header-elements-inline p-2">
								<h6 class="card-title font-weight-600">Testimonials</h6>			
								<div class="header-elements">
									<?php if ($sbr_testimonials_add == true): ?>
										<a href="#"  data-toggle="modal" data-target="#modal_add_testimonial" class="btn btn-sm btn-primary" onclick="testimonial_add_clear();"><i class="icon-plus-circle2"></i> Add New Testimonial</a>
									<?php endif; ?>
								</div>			
							</div>
							<div class="card-body">
								<div id="testimonials_div" style="min-height: 200px;">

								</div>
							</div>
						</div>
					</div>
				</div>
			</div>


			<!-- Add modal form -->
			<div id="modal_add_testimonial" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-plus-circle2"></i> New Testimonial</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_add_testimonial" name="frm_add_testimonial" method="post" onsubmit="return save_testimonial();">
							<fieldset <?php if ($sbr_testimonials_add == false){ echo 'disabled'; } ?>>
								<div class="modal-body">

									<div id="div_add_testimonial_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_add_testimonial_success" class="alert alert-success display-none font-13"></div>

									<div class="form-group">
										<label>Photo</label>
										<input id="add_testimonial_image" name="testimonial_image" type="file" class="form-control h-auto">
										<span class="form-text text-muted">Accepted formats: gif, png, jpg</span>
									</div>

									<div class="form-group mb-2">
										<div class="row">
											<div class="col-md-12">
												<label>Name <span class="error">*</span></label>
												<input id="add_testimonial_name" name="testimonial_name" type="text" placeholder="" class="form-control">
											</div>
										</div>
									</div>	
									<div class="form-group mb-2">
										<div class="row">
											<div class="col-md-8">
												<label>Title <span class="error">*</span></label>
												<input id="add_testimonial_title" name="testimonial_title" type="text" placeholder="" class="form-control">
											</div>
											<div class="col-md-4">
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
										</div>
									</div>
									<div class="form-group mb-3 mb-md-2">
										<div class="row">
											<div class="col-sm-12">
												<label>Description</label>
												<textarea id="add_testimonial_description" name="testimonial_description" rows="8" cols="3" class="form-control"></textarea>
											</div>
										</div>
									</div>
								</div>

								<div class="modal-footer">								
									<button type="submit" id="btn_add_testimonial" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> SAVE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
	
			<!-- Edit modal form -->
			<div id="modal_edit_testimonial" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-pencil6"></i> Edit Testimonial</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_edit_testimonial" name="frm_edit_testimonial" method="post" onsubmit="return update_testimonial();">
							<fieldset <?php if ($sbr_testimonials_edit == false){ echo 'disabled'; } ?>>
								<div class="modal-body">

									<div id="div_edit_testimonial_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_edit_testimonial_success" class="alert alert-success display-none font-13"></div>

	                   				<input id="edit_testimonial_id" name="testimonial_id" type="hidden" placeholder="" class="form-control">

	                                <div class="row mb-2">
	                                    <div class="col-sm-3">
	                                        <div class="block">
	                                            <div class="thumbnail">
	                                                <div class="thumb">                                      
	                                                    <img id="img_testimonial" src="" class="rounded-circle img-fluid" alt="">
	                                                </div>
	                                            </div>
	                                        </div>
	                                    </div>
	                                    <div class="col-sm-9">
	                                    	<div class="form-group">
												<label>Photo <small>(Please note that the image you select here will replace the current image)</small></label>
												<input id="edit_testimonial_image" name="testimonial_image" type="file" class="form-control h-auto">
												<span class="form-text text-muted">Accepted formats: gif, png, jpg</span>
											</div>

	                                    </div>
	                                </div>

									

									<div class="form-group mb-2">
										<div class="row">
											<div class="col-md-12">
												<label>Name <span class="error">*</span></label>
												<input id="edit_testimonial_name" name="testimonial_name" type="text" placeholder="" class="form-control">
											</div>
										</div>
									</div>	
									<div class="form-group mb-2">
										<div class="row">
											<div class="col-md-8">
												<label>Title <span class="error">*</span></label>
												<input id="edit_testimonial_title" name="testimonial_title" type="text" placeholder="" class="form-control">
											</div>
											<div class="col-md-4">
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
										</div>
									</div>	

									<div class="form-group mb-3 mb-md-2">
										<div class="row">
											<div class="col-sm-12">
												<label>Description</label>
												<textarea id="edit_testimonial_description" name="testimonial_description" rows="8" cols="3" class="form-control"></textarea>
											</div>
										</div>
									</div>
								</div>

								<div class="modal-footer">								
									<button type="submit" id="btn_update_testimonial" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				// CKEDITOR.replace( 'add_testimonial_description', {
    //                 height: 150,
    //                 toolbar: [
				// 		{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] }
				// 	]
    //             });
    //             CKEDITOR.replace( 'edit_testimonial_description', {
    //                 height: 150,
    //                 toolbar: [
				// 		{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] }
				// 	]
    //             });

				load_testimonials();
			</script>
	