		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="<?php echo base_url();?>be/blog" class="breadcrumb-item">Blog</a>
							<?php if (isset($blog_article)): ?>
								<span class="breadcrumb-item active">Edit Blog Article</span>
							<?php else: ?>
								<span class="breadcrumb-item active">Add Blog Article</span>
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
					<div class="col-lg-8">
						<div class="card rounded-top-0">
							<div class="card-header header-elements-inline">
								<h5 class="card-title"><i class="icon-info22"></i> Blog Article Information</h5>
								<div class="header-elements">
									<a href="<?php echo base_url();?>be/blog" class="btn btn-sm btn-primary"><i class="icon-arrow-left15"></i> Blog Articles</a>
								</div>
							</div>
							<div class="card-body">
								<?php if (isset($blog_article)): ?>
                        			<?php foreach($blog_article as $row2): ?>
                        				<form id="frm_edit_blog_article" name="frm_edit_blog_article" method="post" onsubmit="return update_blog_article();" autocomplete="off" enctype="multipart/form-data">
											<fieldset <?php if ($sbr_blog_edit == false){ echo 'disabled'; } ?>>
												<div id="div_error" class="alert alert-danger display-none font-13"></div>
			                   					<div id="div_success" class="alert alert-success display-none font-13"></div>

			                   					<input id="blog_article_id" name="blog_article_id" type="hidden" class="form-control" placeholder="" value="<?php echo $row2->blog_article_id; ?>">

												<div class="row">
													<div class="col-lg-12">
														<?php if($row2->cover_image != '' && file_exists("./uploads/blog_article_cover_images/" . $row2->cover_image)): ?>
															<div class="form-group">
																<label class="font-weight-semibold">Cover Image</label>
																<div class="row">
																	<div class="col-lg-6 text-right">
																		<a href="#">
																			<img src="<?php echo base_url();?>uploads/blog_article_cover_images/<?php echo $row2->cover_image; ?>" class="img-fluid" alt="">
																		</a>
																		<a href="javascript:void(0);" onclick="return delete_blog_article_cover_image(<?php echo $row2->blog_article_id; ?>);" class="btn btn-sm btn-danger rounded-round text-white badge-icon mt-10" title="Delete Image"><i class="icon-cancel-circle2"></i> Delete Image</a>
																	</div>
																	<div class="col-lg-6">
																		<input id="cover_image" name="cover_image" type="file" class="form-control h-auto mt-3">
																		<span class="form-text text-muted">Accepted formats: gif, png, jpg</span>
																	</div>
																</div>															
															</div>		
														<?php else: ?>
															<div class="form-group">
																<label>Cover Image</label>
																<input id="cover_image" name="cover_image" type="file" class="form-control h-auto">
																<span class="form-text text-muted">Accepted formats: gif, png, jpg</span>
															</div>
														<?php endif; ?>		
													</div>
												</div>	

												<div class="form-group">
													<div class="row">
														<div class="col-lg-9">
															<label>Article Title <span class="text-danger">*</span></label>
															<input id="blog_article_title" name="blog_article_title" type="text" class="form-control" placeholder="" value="<?php echo $row2->blog_article_title; ?>">
														</div>
														<div class="col-lg-3">
															<label>Article Date <span class="text-danger">*</span></label>
															<input id="blog_article_date" name="blog_article_date" type="text" class="form-control pickadate" placeholder="" value="<?php echo $row2->blog_article_date; ?>">
														</div>
													</div>
												</div>
												<div class="form-group">
													<div class="row">
														<div class="col-lg-9">
															<label>Article Category</label>
															<select id="blog_category_id" name="blog_category_id[]" class="form-control select-fixed-multiple" data-placeholder="Select categories to which the article belongs..."  multiple="multiple" data-fouc>
																<option value="">Select Category</option>
																<?php foreach ($blog_categories as $row): ?>
																	<option value="<?php echo $row->blog_category_id; ?>" 
																		<?php
																			foreach ($blog_article_categories as $row3){
																				if ($row->blog_category_id == $row3->blog_category_id){
																					echo 'selected';
																				}
																			}
																		?>
																	><?php echo $row->blog_category_name; ?></option>
																<?php endforeach; ?>
															</select>
														</div>
														<div class="col-lg-3">
															<label>Article Author <span class="text-danger">*</span></label>
															<input id="blog_article_author" name="blog_article_author" type="text" class="form-control" placeholder="" value="<?php echo $row2->blog_article_author; ?>">
														</div>
													</div>
												</div>		
												<div class="row">
													<div class="col-lg-12">
														<div class="form-group mb-3">
															<label>Article Content <span class="error">*</span></label>
															<textarea id="blog_article_content" name="blog_article_content" rows="2" cols="3" class="form-control ckeditor"><?php echo $row2->blog_article_content; ?></textarea>
														</div>
													</div>
												</div>
												<div class="form-group mb-3">
													<div class="row">
														<div class="col-sm-4">
															<label class="d-block">Is Published <span class="error">*</span></label>
															<div class="form-check form-check-inline form-check-right">
																<label class="form-check-label font-weight-semibold">
																	Yes
																	<input type="radio" class="form-check-input" id="is_published_1" name="is_published" value="1" <?php if ($row2->is_published == 1){ echo 'checked';} ?>>
																</label>
															</div>

															<div class="form-check form-check-inline form-check-right">
																<label class="form-check-label font-weight-semibold">
																	No
																	<input type="radio" class="form-check-input" id="is_published_0" name="is_published" value="0" <?php if ($row2->is_published == 0){ echo 'checked';} ?>>
																</label>
															</div>
														</div>

													</div>
												</div>

												<div class="row">
													<div class="col-md-12">
														<h5 class="text-primary"><i class="icon-wrench"></i> SEO</h5>
													</div>
												</div>
												<div class="form-group mb-2">
													<div class="row">
														<div class="col-sm-12">
															<label>Description</label>
															<textarea id="seo_description" name="seo_description" class="form-control" rows="2" ><?php echo $row2->seo_description; ?></textarea>
														</div>
													</div>
												</div>
												<div class="form-group mb-2">
													<div class="row">
														<div class="col-sm-12">
															<label>Keywords</label>
															<textarea id="seo_keywords" name="seo_keywords" class="form-control" rows="1" ><?php echo $row2->seo_keywords; ?></textarea>
														</div>
													</div>
												</div>


												<div class="text-right">
													<button id="btn_edit_blog_article" type="submit" class="btn btn-success"><i class="icon-checkmark4"></i> UPDATE</button>
												</div>
											</fieldset>
										</form>
                        			<?php endforeach; ?>
                        		<?php else: ?>
                        			<form id="frm_add_blog_article" name="frm_add_blog_article" method="post" onsubmit="return save_blog_article();" autocomplete="off" enctype="multipart/form-data">
                        				<fieldset <?php if ($sbr_blog_add == false){ echo 'disabled'; } ?>>
											<div id="div_error" class="alert alert-danger display-none font-13"></div>
		                   					<div id="div_success" class="alert alert-success display-none font-13"></div>

											<div class="form-group">
												<label>Cover Image:</label>
												<input id="cover_image" name="cover_image" type="file" class="form-control h-auto">
												<span class="form-text text-muted">Accepted formats: gif, png, jpg</span>
											</div>
											<div class="form-group">
												<div class="row">
													<div class="col-lg-9">
														<label>Article Title <span class="text-danger">*</span></label>
														<input id="blog_article_title" name="blog_article_title" type="text" class="form-control" placeholder="">
													</div>
													<div class="col-lg-3">
														<label>Article Date <span class="text-danger">*</span></label>
														<input id="blog_article_date" name="blog_article_date" type="text" class="form-control pickadate" placeholder="">
													</div>
												</div>
											</div>

											<div class="form-group">
												<div class="row">
													<div class="col-lg-9">
														<label>Article Category</label>
														<select id="blog_category_id" name="blog_category_id[]" class="form-control select-fixed-multiple" data-placeholder="Select categories to which the article belongs..."  multiple="multiple" data-fouc>
															<option value="">Select Category</option>
															<?php foreach ($blog_categories as $row): ?>
																<option value="<?php echo $row->blog_category_id; ?>"><?php echo $row->blog_category_name; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
													<div class="col-lg-3">
														<label>Article Author <span class="text-danger">*</span></label>
														<input id="blog_article_author" name="blog_article_author" type="text" class="form-control" placeholder="">
													</div>
												</div>
											</div>		
											<div class="row">
												<div class="col-lg-12">
													<div class="form-group mb-3">
														<label>Article Content <span class="error">*</span></label>
														<textarea id="blog_article_content" name="blog_article_content" rows="2" cols="3" class="form-control ckeditor"></textarea>
													</div>
												</div>
											</div>
											<div class="form-group mb-3">
												<div class="row">
													<div class="col-sm-4">
														<label class="d-block">Is Published <span class="error">*</span></label>
														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																Yes
																<input type="radio" class="form-check-input" id="is_published_1" name="is_published" value="1">
															</label>
														</div>

														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																No
																<input type="radio" class="form-check-input" id="is_published_0" name="is_published" value="0" checked>
															</label>
														</div>
													</div>

												</div>
											</div>

											<div class="row">
												<div class="col-md-12">
													<h5 class="text-primary"><i class="icon-wrench"></i> SEO</h5>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-12">
														<label>Description</label>
														<textarea id="seo_description" name="seo_description" class="form-control" rows="2" ></textarea>
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-12">
														<label>Keywords</label>
														<textarea id="seo_keywords" name="seo_keywords" class="form-control" rows="1" ></textarea>
													</div>
												</div>
											</div>



											<div class="text-right">
												<button id="btn_add_blog_article" type="submit" class="btn btn-success"><i class="icon-checkmark4"></i> SAVE</button>
											</div>
										</fieldset>
									</form>
                        		<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="col-lg-6">
					</div>
				</div>
			</div>
			<!-- /content area -->


            <script type="text/javascript">
                CKEDITOR.replace( 'blog_article_content', {
                    height: 300
                } );
            </script>
