		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<span class="breadcrumb-item active">About Us</span>
						</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content pt-0">

				<div class="row">
					<div class="col-lg-10">
						<div class="card rounded-top-0">
							<div class="card-header header-elements-inline">
								<h5 class="card-title"><i class="icon-info22"></i> About Us</h5>
							</div>
							<div class="card-body">
								<?php if ($about_us_exists == true): ?>
                        			<?php foreach($about_us as $row): ?>
                        				<form id="frm_about_us" name="frm_about_us" method="post" onsubmit="return save_about_us();" autocomplete="off" enctype="multipart/form-data">
                        					<fieldset <?php if ($sbr_about_us_edit == false){ echo 'disabled'; } ?>>
												<!-- <legend class="font-weight-semibold text-uppercase font-size-sm">Enter your information</legend> -->
												<div id="div_error" class="alert alert-danger display-none font-13"></div>
			                   					<div id="div_success" class="alert alert-success display-none font-13"></div>

												<div class="row">
													<div class="col-md-12">
														<?php if($row->cover_image != '' && file_exists("./uploads/about_us_cover_image/" . $row->cover_image)): ?>
															<div class="form-group">
																<label class="font-weight-bold">Cover Image</label>
																<div class="row">
																	<div class="col-md-4 text-right">
																		<a href="#">
																			<img src="<?php echo base_url();?>uploads/about_us_cover_image/<?php echo $row->cover_image; ?>" class="img-fluid" alt="">
																		</a>
																		<a href="javascript:void(0);" onclick="return delete_about_us_image();" class="btn btn-sm btn-danger rounded-round text-white badge-icon mt-10" title="Delete Image"><i class="icon-cancel-circle2"></i> Delete Image</a>
																	</div>
																	<div class="col-md-8">
																		<small>(Please note that the image you select here will replace the current image)</small>
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

												<div class="row">
													<div class="col-md-12">
														<div class="form-group mb-3">
															<label>About Us</label>
															<textarea id="about_us" name="about_us" rows="3" cols="3" class="form-control ckeditor"><?php echo $row->about_us; ?></textarea>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group mb-3">
															<label>Mission</label>
															<textarea id="mission" name="mission" rows="3" cols="3" class="form-control ckeditor"><?php echo $row->mission; ?></textarea>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group mb-3">
															<label>Vision</label>
															<textarea id="vision" name="vision" rows="3" cols="3" class="form-control ckeditor"><?php echo $row->vision; ?></textarea>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group mb-3">
															<label>Core Values</label>
															<textarea id="core_values" name="core_values" rows="3" cols="3" class="form-control ckeditor"><?php echo $row->core_values; ?></textarea>
														</div>
													</div>
												</div>

												<div class="text-right">
													<button id="btn_about_us" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save About Us</button>
												</div>
											</fieldset>
										</form>
                        			<?php endforeach; ?>
                        		<?php else: ?>
                        			<form id="frm_about_us" name="frm_about_us" method="post" onsubmit="return save_about_us();" autocomplete="off" enctype="multipart/form-data">
                        				<fieldset <?php if ($sbr_about_us_edit == false){ echo 'disabled'; } ?>>
											<div id="div_error" class="alert alert-danger display-none font-13"></div>
		                   					<div id="div_success" class="alert alert-success display-none font-13"></div>

											<div class="form-group">
												<label>Cover Image</label>
												<input id="cover_image" name="cover_image" type="file" class="form-control h-auto">
												<span class="form-text text-muted">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="form-group mb-3">
														<label>About Us</label>
														<textarea id="about_us" name="about_us" rows="3" cols="3" class="form-control ckeditor"></textarea>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="form-group mb-3">
														<label>Mission</label>
														<textarea id="mission" name="mission" rows="3" cols="3" class="form-control ckeditor"></textarea>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="form-group mb-3">
														<label>Vision</label>
														<textarea id="vision" name="vision" rows="3" cols="3" class="form-control ckeditor"></textarea>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="form-group mb-3">
														<label>Core Values</label>
														<textarea id="core_values" name="core_values" rows="3" cols="3" class="form-control ckeditor"></textarea>
													</div>
												</div>
											</div>
											<div class="text-right">
												<button id="btn_about_us" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save About Us</button>
											</div>
										</fieldset>
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
                CKEDITOR.replace( 'about_us', {
                    height: 300
                });
                CKEDITOR.replace( 'mission', {
                    height: 100
                });
                CKEDITOR.replace( 'vision', {
                    height: 100
                });
                CKEDITOR.replace( 'core_values', {
                    height: 150
                });

            </script>
