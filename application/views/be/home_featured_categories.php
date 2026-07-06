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
							<span class="breadcrumb-item active">Featured Categories</span>
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
						<div class="">
							<div class="card-body">
								<div class="row">
									<div class="col-md-5">
										<div class="card rounded-top-0">
											<div class="card-header alpha-grey text-success-800 header-elements-inline pt-2 pb-2">
												<h6 class="card-title text-uppercase"><i class="icon-checkmark4 mr-1"></i> Featured Categories</h6>			
											</div>
											<div class="card-body">
												<form id="frm_home_featured_product_categories" name="frm_home_featured_product_categories" method="post" onsubmit="return save_home_featured_product_categories();" autocomplete="off" enctype="multipart/form-data">

													<fieldset <?php if ($sbr_featured_categories_edit == false){ echo 'disabled'; } ?>>

														<div id="div_add_error" class="alert alert-danger display-none font-13"></div>
					                   					<div id="div_add_success" class="alert alert-success display-none font-13"></div>

					                   					<div class="row">
					                   						<div class="col-md-12">
					                   							<div class="form-group mb-3">
																	<label>Select Featured Categories <span class="error">*</span></label>
																	<select id="product_category_id" name="product_category_id[]" class="form-control select-multiple" multiple="multiple" data-placeholder="Select Featured Categories" data-fouc>
																		<option value="">Select Featured Categories</option>
																		<?php $level_count = 0; ?>
																		<?php foreach ($product_categories as $row): ?>
																			<option value="<?php echo $row->product_category_id; ?>" <?php foreach ($home_featured_product_categories as $row2){ if ($row->product_category_id == $row2->product_category_id){ echo 'selected'; }} ?> ><?php echo $row->product_category_name; ?></option>
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
																					$selected = '';
																					foreach ($home_featured_product_categories as $row2){ if ($sub_category->product_category_id == $row2->product_category_id){ $selected = 'selected'; }}
																					for($i = 0; $i < $level_count; $i++){$mdash = $mdash . '&mdash;'; $mspace = $mspace . '&nbsp;&nbsp;';}
																					echo '<option value="' . $sub_category->product_category_id . '" ' . $selected . '>' . $mspace . $mdash . ' ' . $sub_category->product_category_name . '</option>';
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
														<div class="text-right">
															<button id="btn_home_featured_product_categories" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE</button>
														</div>
													</fieldset>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>							
						</div>
					</div>
				</div>
			</div>

	