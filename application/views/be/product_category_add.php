		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="#" class="breadcrumb-item">Products</a>
							<a href="<?php echo base_url();?>be/product_categories" class="breadcrumb-item">Product Categories</a>
							<?php if (isset($product_category)): ?>
								<span class="breadcrumb-item active">Edit Product Category</span>
							<?php else: ?>
								<span class="breadcrumb-item active">New Product Category</span>
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
					<div class="col-lg-4">
						<div class="card rounded-top-0">
							<div class="card-header alpha-grey text-success-800 header-elements-inline pt-2 pb-2">
								<?php if (isset($product_category)): ?>
									<h6 class="card-title text-uppercase"><i class="icon-pencil6"></i> Edit Product Category</h6>
								<?php else: ?>
									<h6 class="card-title text-uppercase"><i class="icon-plus-circle2"></i> New Product Category</h6>
								<?php endif; ?>
								<div class="header-elements">
									<a href="<?php echo base_url();?>be/product_categories" class="btn btn-sm btn-primary"><i class="icon-arrow-left15"></i> Product Categories</a>
								</div>
							</div>
							<div class="card-body">
								<?php if (isset($product_category)): ?>
                        			<?php foreach($product_category as $row): ?>
                        				<?php
											function fetch_sub_categories($sub_categories, $level_count, $product_category_parent_id){
												$level_count = $level_count + 1;
												foreach($sub_categories as $sub_category){
													$mdash = '';
													$mspace = '';
													$sel = '';
													for($i = 0; $i < $level_count; $i++){$mdash = $mdash . '&mdash;'; $mspace = $mspace . '&nbsp;&nbsp;';}
													if ($product_category_parent_id == $sub_category->product_category_id) { $sel = 'selected'; }
													echo '<option value="' . $sub_category->product_category_id . '" ' . $sel . '>' . $mspace . $mdash . ' ' . $sub_category->product_category_name . '</option>';
													if(!empty($sub_category->sub)){
														fetch_sub_categories($sub_category->sub, $level_count, $product_category_parent_id);
													}
												}
											}
										?>
                        				<form id="frm_edit_product_category" name="frm_edit_product_category" method="post" onsubmit="return update_product_category();" autocomplete="off" enctype="multipart/form-data">
											<!-- <legend class="font-weight-semibold text-uppercase font-size-sm">Enter your information</legend> -->
											<div id="div_edit_error" class="alert alert-danger display-none font-13"></div>
		                   					<div id="div_edit_success" class="alert alert-success display-none font-13"></div>

		                   					<input id="product_category_id" name="product_category_id" type="hidden" class="form-control" placeholder="" value="<?php echo $row->product_category_id; ?>">
		                   					<div class="row">
		                   						<div class="col-md-12">
		                   							<div class="form-group mb-3">
														<label>Parent Category <span class="error">*</span></label>
														<select id="product_category_parent_id" name="product_category_parent_id" class="form-control form-control-select2" data-placeholder="Select Parent Category" data-fouc>
															<option value="0">None</option>
															<?php $level_count = 0; ?>
															<?php foreach ($product_categories as $row2): ?>
																<option value="<?php echo $row2->product_category_id; ?>" <?php if ($row2->product_category_id == $row->product_category_parent_id) { echo 'selected'; } ?>><?php echo $row2->product_category_name; ?></option>
																<?php
																	if(!empty($row2->sub)){
																		fetch_sub_categories($row2->sub, $level_count, $row->product_category_parent_id);
																	}
																?>
															<?php endforeach; ?>
															
														</select>
		                   							</div>
		                   						</div>
		                   					</div>
		                   					<div class="row">
												<div class="col-md-10">
													<div class="form-group">
														<label>Category Name <span class="text-danger">*</span></label>
														<input id="edit_product_category_name" name="product_category_name" type="text" class="form-control" placeholder="" value="<?php echo $row->product_category_name; ?>">
													</div>
												</div>
												<div class="col-md-2">
													<div class="form-group mb-3">
														<label>Sort Key <span class="text-danger">*</span></label>
														<input id="edit_sort_key" name="sort_key" type="number" class="form-control" placeholder="" value="<?php echo $row->sort_key; ?>">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="form-group mb-3">
														<label>Category Icon</label>
														<select id="icon_id" name="icon_id" class="form-control form-control-select3" data-placeholder="Select Category Icon" data-fouc>
															<option value="">Select Category Icon</option>
															<?php foreach ($icons as $row2): ?>
																<option value="<?php echo $row2->icon_id; ?>" data-icon="<?php echo $row2->icon_name; ?>" <?php if ($row2->icon_id == $row->icon_id){ echo 'selected'; } ?>><?php echo $row2->icon_name; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="form-group mb-3">
														<label>Description</label>
														<textarea id="edit_description" name="description" rows="2" cols="3" class="form-control ckeditor"><?php echo $row->description; ?></textarea>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<?php if($row->cover_image != '' && file_exists("./uploads/product_category_cover_images/" . $row->cover_image)): ?>
														<div class="form-group">
															<label class="font-weight-semibold">Cover Image:</label>
															<div class="row">
																<div class="col-md-6 text-right">
																	<a href="#">
																		<img src="<?php echo base_url();?>uploads/product_category_cover_images/<?php echo $row->cover_image; ?>" class="img-fluid" alt="">
																	</a>
																	<a href="javascript:void(0);" onclick="return delete_product_category_cover_image(<?php echo $row->product_category_id; ?>);" class="btn btn-sm btn-danger rounded-round text-white badge-icon mt-10" title="Delete Image"><i class="icon-cancel-circle2"></i> Delete Image</a>
																</div>
																<div class="col-md-6">
																	<input id="edit_cover_image" name="cover_image" type="file" class="form-control h-auto mt-3">
																	<span class="form-text text-muted">Accepted formats: gif, png, jpg</span>
																</div>
															</div>															
														</div>		
													<?php else: ?>
														<div class="form-group">
															<label>Cover Image:</label>
															<input id="edit_cover_image" name="cover_image" type="file" class="form-control h-auto">
															<span class="form-text text-muted">Accepted formats: gif, png, jpg</span>
														</div>
													<?php endif; ?>		
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<h5 class="text-primary"><i class="icon-wrench"></i> SEO</h5>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="form-group mb-3">
														<label>Title</label>
														<input id="add_seo_title" name="seo_title" type="text" class="form-control" placeholder="" value="<?php echo $row->seo_title; ?>">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-12">
														<label>Description</label>
														<textarea id="edit_seo_description" name="seo_description" class="form-control" rows="2" ><?php echo $row->seo_description; ?></textarea>
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-12">
														<label>Keywords</label>
														<textarea id="edit_seo_keywords" name="seo_keywords" class="form-control" rows="1" ><?php echo $row->seo_keywords; ?></textarea>
													</div>
												</div>
											</div>		
											<div class="text-right">
												<button id="btn_edit_product_category" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> UPDATE CATEGORY</button>
											</div>
										</form>
										<script type="text/javascript">
							                CKEDITOR.replace( 'edit_description', {
							                    height: 100,
							                    toolbar: [
													{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] }
												]
							                } );
							            </script>
                        			<?php endforeach; ?>

                        		<?php else: ?>
                        			<form id="frm_add_product_category" name="frm_add_product_category" method="post" onsubmit="return save_product_category();" autocomplete="off" enctype="multipart/form-data">

										<div id="div_add_error" class="alert alert-danger display-none font-13"></div>
	                   					<div id="div_add_success" class="alert alert-success display-none font-13"></div>

	                   					<div class="row">
	                   						<div class="col-md-12">
	                   							<div class="form-group mb-3">
													<label>Parent Category <span class="error">*</span></label>
													<select id="product_category_parent_id" name="product_category_parent_id" class="form-control form-control-select2" data-placeholder="Select Parent Category" data-fouc>
														<option value="0">None</option>
														<?php $level_count = 0; ?>
														<?php foreach ($product_categories as $row): ?>
															<option value="<?php echo $row->product_category_id; ?>"><?php echo $row->product_category_name; ?></option>
															<?php
																if(!empty($row->sub)){
																	fetch_sub_categories($row->sub, $level_count);
																}
															?>
														<?php endforeach; ?>
														<?php
															function fetch_sub_categories($sub_categories, $level_count){
																$level_count = $level_count + 1;
																foreach($sub_categories as $sub_category){
																	$mdash = '';
																	$mspace = '';
																	for($i = 0; $i < $level_count; $i++){$mdash = $mdash . '&mdash;'; $mspace = $mspace . '&nbsp;&nbsp;';}
																	echo '<option value="' . $sub_category->product_category_id . '">' . $mspace . $mdash . ' ' . $sub_category->product_category_name . '</option>';
																	if(!empty($sub_category->sub)){
																		fetch_sub_categories($sub_category->sub, $level_count);
																	}
																}
															}
														?>
													</select>
	                   							</div>
	                   						</div>
	                   					</div>

	                   					<div class="row">
											<div class="col-md-10">
												<div class="form-group">
													<label>Category Name <span class="text-danger">*</span></label>
													<input id="add_product_category_name" name="product_category_name" type="text" class="form-control" placeholder="">
												</div>
											</div>
											<div class="col-md-2">
												<div class="form-group mb-3">
													<label>Sort Key <span class="text-danger">*</span></label>
													<input id="add_sort_key" name="sort_key" type="number" class="form-control" placeholder="" value="0">
												</div>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="form-group mb-3">
													<label>Category Icon</label>
													<select id="icon_id" name="icon_id" class="form-control form-control-select3" data-placeholder="Select Category Icon" data-fouc>
														<option value="">Select Category Icon</option>
														<?php foreach ($icons as $row): ?>
															<option value="<?php echo $row->icon_id; ?>" data-icon="<?php echo $row->icon_name; ?>"><?php echo $row->icon_name; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
											</div>
										</div>										
										<div class="row">
											<div class="col-md-12">
												<div class="form-group mb-3">
													<label>Description</label>
													<textarea id="add_description" name="description" rows="2" cols="3" class="form-control ckeditor"></textarea>
												</div>
											</div>
										</div>
										<div class="form-group">
											<label>Cover Image:</label>
											<input id="add_cover_image" name="cover_image" type="file" class="form-control h-auto">
											<span class="form-text text-muted">Accepted formats: gif, png, jpg</span>
										</div>
										<div class="row">
											<div class="col-md-12">
												<h5 class="text-primary"><i class="icon-wrench"></i> SEO</h5>
											</div>
										</div>
										<div class="row">
											<div class="col-md-12">
												<div class="form-group mb-3">
													<label>Title</label>
													<input id="add_seo_title" name="seo_title" type="text" class="form-control" placeholder="">
												</div>
											</div>
										</div>
										<div class="form-group mb-2">
											<div class="row">
												<div class="col-sm-12">
													<label>Description</label>
													<textarea id="add_seo_description" name="seo_description" class="form-control" rows="2" ></textarea>
												</div>
											</div>
										</div>
										<div class="form-group mb-2">
											<div class="row">
												<div class="col-sm-12">
													<label>Keywords</label>
													<textarea id="add_seo_keywords" name="seo_keywords" class="form-control" rows="1" ></textarea>
												</div>
											</div>
										</div>
										<div class="text-right">
											<button id="btn_add_product_category" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE NEW CATEGORY</button>
										</div>
									</form>

									<script type="text/javascript">
						                CKEDITOR.replace( 'add_description', {
						                    height: 100,
						                    toolbar: [
												{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] }
											]
						                } );
						            </script>
                        		<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="col-md-6">
					</div>
				</div>
			</div>
			<!-- /content area -->


            
