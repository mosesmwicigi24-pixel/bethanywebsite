		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="#" class="breadcrumb-item">Products</a>
							<span class="breadcrumb-item active">Categories</span>
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
								<h5 class="card-title font-weight-bold"><i class="icon-tree7 mr-2"></i> Product Categories</h5>
							</div>

							<div class="card-body">
								<div class="row">
									<div class="col-md-4">
										<div class="card rounded-top-0">
											<div class="card-header alpha-grey text-success-800 header-elements-inline pt-2 pb-2">
												<h6 class="card-title text-uppercase"><i class="icon-plus-circle2 mr-1"></i> New Category</h6>			
											</div>
											<div class="card-body">
												<form id="frm_add_product_category" name="frm_add_product_category" method="post" onsubmit="return save_product_category();" autocomplete="off" enctype="multipart/form-data">

													<fieldset <?php if ($sbr_product_categories_add == false){ echo 'disabled'; } ?>>

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
															<div class="col-md-9">
																<div class="form-group mb-3">
																	<label>Category Name <span class="error">*</span></label>
																	<input id="add_product_category_name" name="product_category_name" type="text" class="form-control" placeholder="">
																</div>
															</div>
															<div class="col-md-3">
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
															<button id="btn_add_product_category" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE CATEGORY</button>
														</div>
													</fieldset>
												</form>
											</div>
										</div>
									</div>
									<!-- <div class="col-md-1"></div> -->
									<div class="col-md-8">
										<form method="post" class="form">
											<div id="product_categories_div" style="min-height: 400px;">
									
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-2 font-weight-600">
														<select id="sl_product_categories_bulk_action" name="sl_product_categories_bulk_action" class="form-control form-control-select2" data-placeholder="Bulk Action" data-fouc>
															<option value="">Bulk Action</option>
															<option value="Delete">Delete</option>
														</select>
													</div>
													<div class="col-sm-2">
														<button id="btn_product_categories_bulk_action" type="button" class="btn btn-sm btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> APPLY</button>
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

			<script type="text/javascript">
				CKEDITOR.replace( 'add_description', {
                    height: 100,
                    toolbar: [
						{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ] }
					]
                });                
				load_product_categories();
			</script>
	