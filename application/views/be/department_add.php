		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="#" class="breadcrumb-item">Company Setup</a>
							<a href="<?php echo base_url();?>be/departments" class="breadcrumb-item">Departments</a>
							<?php if (isset($department)): ?>
								<span class="breadcrumb-item active">Edit Department</span>
							<?php else: ?>
								<span class="breadcrumb-item active">New Department</span>
							<?php endif; ?>
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
							<div class="card-header header-elements-inline">
								<h5 class="card-title"><i class="icon-info22"></i> Department Information</h5>
								<div class="header-elements">
									<a href="<?php echo base_url();?>be/departments" class="btn btn-sm btn-primary"><i class="icon-arrow-left15"></i> Departments</a>
								</div>
							</div>
							<div class="card-body">
								<?php if (isset($department)): ?>
                        			<?php foreach($department as $row): ?>
                        				<form id="frm_edit_department" name="frm_edit_department" method="post" onsubmit="return update_department();" autocomplete="off" enctype="multipart/form-data">
											<!-- <legend class="font-weight-semibold text-uppercase font-size-sm">Enter your information</legend> -->
											<div id="div_error" class="alert alert-danger display-none font-13"></div>
		                   					<div id="div_success" class="alert alert-success display-none font-13"></div>

		                   					<input id="department_id" name="department_id" type="hidden" class="form-control" placeholder="" value="<?php echo $row->department_id; ?>">
		                   					<div class="row">
												<div class="col-md-10">
													<div class="form-group">
														<label>Department Name <span class="text-danger">*</span></label>
														<input id="department_name" name="department_name" type="text" class="form-control" placeholder="" value="<?php echo $row->department_name; ?>">
													</div>
												</div>
												<div class="col-md-2">
													<div class="form-group mb-3">
														<label>Sort Key <span class="text-danger">*</span></label>
														<input id="sort_key" name="sort_key" type="number" class="form-control" placeholder="" value="<?php echo $row->sort_key; ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="form-group mb-3">
														<label>Description</label>
														<textarea id="description" name="description" rows="2" cols="3" class="form-control ckeditor"><?php echo $row->description; ?></textarea>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<?php if($row->cover_image != '' && file_exists("./uploads/department_cover_images/" . $row->cover_image)): ?>
														<div class="form-group">
															<label class="font-weight-semibold">Cover Image:</label>
															<div class="row">
																<div class="col-md-6 text-right">
																	<a href="#">
																		<img src="<?php echo base_url();?>uploads/department_cover_images/<?php echo $row->cover_image; ?>" class="img-fluid" alt="">
																	</a>
																	<a href="javascript:void(0);" onclick="return delete_department_cover_image(<?php echo $row->department_id; ?>);" class="btn btn-sm btn-danger rounded-round text-white badge-icon mt-10" title="Delete Image"><i class="icon-cancel-circle2"></i> Delete Image</a>
																</div>
																<div class="col-md-6">
																	<input id="cover_image" name="cover_image" type="file" class="form-control h-auto mt-3">
																	<span class="form-text text-muted">Accepted formats: gif, png, jpg</span>
																</div>
															</div>															
														</div>		
													<?php else: ?>
														<div class="form-group">
															<label>Cover Image:</label>
															<input id="cover_image" name="cover_image" type="file" class="form-control h-auto">
															<span class="form-text text-muted">Accepted formats: gif, png, jpg</span>
														</div>
													<?php endif; ?>		
												</div>
											</div>		
											<div class="text-right">
												<button id="btn_edit_department" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> UPDATE</button>
											</div>
										</form>
                        			<?php endforeach; ?>
                        		<?php else: ?>
                        			<form id="frm_add_department" name="frm_add_department" method="post" onsubmit="return save_department();" autocomplete="off" enctype="multipart/form-data">

										<div id="div_error" class="alert alert-danger display-none font-13"></div>
	                   					<div id="div_success" class="alert alert-success display-none font-13"></div>

	                   					<div class="row">
											<div class="col-md-10">
												<div class="form-group">
													<label>Department Name <span class="text-danger">*</span></label>
													<input id="department_name" name="department_name" type="text" class="form-control" placeholder="">
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group mb-3">
													<label>Sort Key <span class="text-danger">*</span></label>
													<input id="sort_key" name="sort_key" type="number" class="form-control" placeholder="" value="0">
												</div>
											</div>
										</div>										
										<div class="row">
											<div class="col-md-12">
												<div class="form-group mb-3">
													<label>Description</label>
													<textarea id="description" name="description" rows="2" cols="3" class="form-control ckeditor"></textarea>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label>Cover Image:</label>
											<input id="cover_image" name="cover_image" type="file" class="form-control h-auto">
											<span class="form-text text-muted">Accepted formats: gif, png, jpg</span>
										</div>
										<div class="text-right">
											<button id="btn_add_department" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE</button>
										</div>
									</form>
                        		<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="col-md-6">
					</div>
				</div>
			</div>
			<!-- /content area -->


            <script type="text/javascript">
                CKEDITOR.replace( 'description', {
                    height: 300
                } );
            </script>
