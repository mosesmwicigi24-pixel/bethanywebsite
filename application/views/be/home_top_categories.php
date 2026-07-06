		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<span class="breadcrumb-item active">CMS Content</span>
							<span class="breadcrumb-item active">Home Page</span>
							<span class="breadcrumb-item active">Top Categories</span>
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
								<h5 class="card-title font-weight-bold">Home Top Categories</h5>			
							</div>

							<div class="card-body">
								<div class="row">
									<div class="col-md-4">
										<div class="card rounded-top-0">
											<div class="card-header alpha-grey text-success-800 header-elements-inline pt-2 pb-2">
												<h6 class="card-title text-uppercase"><i class="icon-plus-circle2 mr-1"></i> Add Top Category</h6>			
											</div>
											<div class="card-body">
												<form id="frm_add_home_top_product_category" name="frm_add_home_top_product_category" method="post" onsubmit="return save_home_top_product_category();" autocomplete="off" enctype="multipart/form-data">

													<fieldset <?php if ($sbr_top_categories_edit == false){ echo 'disabled'; } ?>>

														<div id="div_add_error" class="alert alert-danger display-none font-13"></div>
					                   					<div id="div_add_success" class="alert alert-success display-none font-13"></div>

					                   					<div class="row">
					                   						<div class="col-md-9">
					                   							<div class="form-group mb-3">
																	<label>Select Main Category <span class="error">*</span></label>
																	<select id="home_top_product_category_id" name="home_top_product_category_id" class="form-control form-control-select2" data-placeholder="Select Main Category" data-fouc>
																		<option value="">Select Main Category</option>
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
					                   						<div class="col-md-3">
																<div class="form-group mb-3">
																	<label>Position <span class="text-danger">*</span></label>
																	<input id="add_position" name="position" type="number" class="form-control" placeholder="" value="0">
																</div>
															</div>
					                   					</div>
					                   					<div class="row mb-3">
					                   						<div class="col-md-12">
																<label>Subcategories <small>(Select Upto 5 Subcategories)</small> <span class="error">*</span></label>
																<div class="form-group form-group-feedback form-group-feedback-right mb-2">
																	<select id="home_top_product_subcategory_id" name="home_top_product_subcategory_id[]" multiple="multiple" class="form-control select-multiple-max5" data-placeholder="Select Subcategories" data-fouc>
																	</select>
																	<div id="home_top_product_subcategory_loader" class="form-control-feedback display-none">
																		<i class="icon-spinner2 spinner text-success"></i>
																	</div>
																</div>
					                   						</div>
					                   					</div>
														<div class="text-right">
															<button id="btn_add_home_top_product_category" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE</button>
														</div>
													</fieldset>
												</form>
											</div>
										</div>
									</div>
									<!-- <div class="col-md-1"></div> -->
									<div class="col-md-8">
										<form method="post" class="form">
											<div id="home_top_product_categories_div" style="min-height: 400px;">
									
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
			<div id="modal_edit_home_top_product_category" class="modal fade">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-pencil6"></i> Edit Top Category</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_edit_home_top_product_category" name="frm_edit_home_top_product_category" method="post" onsubmit="return update_home_top_product_category();">
							<fieldset <?php if ($sbr_top_categories_add == false){ echo 'disabled'; } ?>>
								<div class="modal-body">

									<div id="div_edit_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_edit_success" class="alert alert-success display-none font-13"></div>

	                   				<input id="edit_home_top_product_category_id" name="home_top_product_category_id" type="hidden" placeholder="" class="form-control">

									<div class="row">
	               						<div class="col-md-9">
	               							<div class="form-group mb-3">
												<label>Select Main Category <span class="error">*</span></label>
												<select id="edit_ht_product_category_id" name="ht_product_category_id" class="form-control form-control-select2" data-placeholder="Select Main Category" data-fouc>
													<option value="">Select Main Category</option>
													<?php $level_count = 0; ?>
													<?php foreach ($product_categories as $row): ?>
														<option value="<?php echo $row->product_category_id; ?>"><?php echo $row->product_category_name; ?></option>
														<?php
															if(!empty($row->sub)){
																fetch_sub_categories($row->sub, $level_count);
															}
														?>
													<?php endforeach; ?>
												</select>
	               							</div>
	               						</div>
	               						<div class="col-md-3">
											<div class="form-group mb-3">
												<label>Position <span class="text-danger">*</span></label>
												<input id="edit_position" name="position" type="number" class="form-control" placeholder="" value="0">
											</div>
										</div>
	               					</div>
	               					<div class="row mb-3">
	               						<div class="col-md-12">
											<label>Subcategories <small>(Select Upto 5 Subcategories)</small> <span class="error">*</span></label>
											<div class="form-group form-group-feedback form-group-feedback-right mb-2">
												<select id="edit_home_top_product_subcategory_id" name="home_top_product_subcategory_id[]" multiple="multiple" class="form-control select-multiple-max5" data-placeholder="Select Subcategories" data-fouc>
												</select>
												<div id="edit_home_top_product_subcategory_loader" class="form-control-feedback display-none">
													<i class="icon-spinner2 spinner text-success"></i>
												</div>
											</div>
	               						</div>
	               					</div>
								</div>
								<div class="modal-footer">								
									<button type="submit" id="btn_update_home_top_product_category" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				load_home_top_product_categories();
			</script>
	