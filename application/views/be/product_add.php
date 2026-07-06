		<!-- Main content -->
		<div class="content-wrapper">

			<?php if (!isset($product)): ?>

				<!-- Page header -->
				<div class="page-header">
					<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
						<div class="d-flex">
							<div class="breadcrumb">
								<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
								<a href="<?php echo base_url();?>be/products" class="breadcrumb-item">Products</a>
								<span class="breadcrumb-item active">New Product</span>
							</div>

							<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
						</div>
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content pt-0">
					<div class="row">
						<div class="col-md-12">
							<div class="mb-3">
								<h5 class="mb-0 font-weight-semibold">
									<i class="icon-plus-circle2"></i> NEW PRODUCT
									<a href="<?php echo base_url();?>be/products" class="btn btn-sm bg-success-400 text-success-400 border-success-400 float-right"><i class="icon-arrow-left15"></i> Back to Products</a>
								</h5>
							</div>
						</div>
					</div>

					<form id="frm_add_product" name="frm_add_product" method="post" onsubmit="return save_product();" autocomplete="false">
						<div class="row">

							<div class="col-md-6">
								<div class="card rounded-top-0">
									<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
										<h6 class="card-title font-weight-600 text-success text-uppercase">Product Information</h6>			
									</div>
							
									<div class="card-body">
										<div id="div_add_error" class="alert alert-danger display-none font-13"></div>
		                   				<div id="div_add_success" class="alert alert-success display-none font-13"></div>

										<div class="form-group mb-2">
											<div class="row">
												<div class="col-sm-8">
													<label>Product Name <span class="error">*</span></label>
													<input id="product_name" name="product_name" type="text" placeholder="" class="form-control form-control-lg font-weight-bold">
												</div>
												<div class="col-sm-4">
													<label>Product Barcode</label>
													<input id="product_barcode" name="product_barcode" type="text" placeholder="" class="form-control form-control-lg font-weight-bold">
												</div>
											</div>
										</div>
										<div class="form-group mb-3">
											<div class="row">
												<div class="col-sm-12">
													<label>Short Description</label>
													<textarea id="product_short_description" name="product_short_description" rows="3" cols="3" class="form-control ckeditor"></textarea>
												</div>
											</div>
										</div>
										<legend class="text-uppercase font-size-sm font-weight-bold">Units &amp; Pricing</legend>
										<div class="form-group mb-3">
											<div class="row">
												<div class="col-sm-3">
													<label>Base Unit of Measure <span class="error">*</span></label>
													<select id="unit_id" name="unit_id" class="form-control form-control-select2" data-placeholder="Select Unit" data-fouc>
														<option value="">Select Unit</option>
														<?php foreach ($units as $row2): ?>
															<option value="<?php echo $row2->unit_id; ?>"><?php echo $row2->unit_name; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-sm-3">
													<label>Regular Price <span class="error">*</span></label>
													<div class="input-group">
														<span class="input-group-prepend">
															<span class="input-group-text"><?php echo $default_currency; ?></span>
														</span>
														<input id="regular_price" name="regular_price" type="number" placeholder="" value="0" class="form-control" min="0">
													</div>
												</div>
												<div class="col-sm-3">
													<label>Sale Price <i class="icon-info22 text-primary" data-popup="tooltip" title="Use this when you're running an offer. Please note that this will override the Regular Price" data-placement="top"></i></label>
													<div class="input-group">
														<span class="input-group-prepend">
															<span class="input-group-text"><?php echo $default_currency; ?></span>
														</span>
														<input id="sale_price" name="sale_price" type="number" placeholder="" value="0" class="form-control" min="0">
													</div>
												</div>
												<div class="col-sm-3">
													<label>Minimum Selling Price <i class="icon-info22 text-primary" data-popup="tooltip" title="Leave 0 if none" data-placement="top"></i></label>
													<div class="input-group">
														<span class="input-group-prepend">
															<span class="input-group-text"><?php echo $default_currency; ?></span>
														</span>
														<input id="minimum_selling_price" name="minimum_selling_price" type="number" placeholder="" value="0" class="form-control" min="0">
													</div>
												</div>
											</div>
										</div>
										<div class="form-group mb-2">
											<div class="row">
												<div class="col-sm-12">
													<!-- <label>Additional Units of Measure</label><br> -->
													<button id="btn_add_product_unit_of_measure" type="button" data-toggle="modal" role="button" data-target="#modal_pa_unit_of_measure" class="btn btn-xs btn-primary"><i class="icon-plus-circle2"></i> Add Unit of Measure</button>
													<!-- <div id="div_units_of_measure"></div> -->
													<div class="table-responsive display-none" id="div_pa_units_of_measure">
														<table class="table">
															<thead>
																<tr>
																	<th width="150px">Unit of Measure</th>
																	<th width="150px">Regular Price</th>
																	<th width="150px">Sale Price</th>
																	<th width="150px">Minimum Selling Price</th>
																	<td width="20px"></td>
																</tr>
															</thead>
															<tbody id="pa_units_of_measure_table">
																
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
										<hr>
										<div class="form-group mb-3">
											<div class="row">
												
												<div class="col-sm-4">
													<label>Tax Rate <span class="error">*</span></label>
													<select id="tax_rate_id" name="tax_rate_id" class="form-control form-control-select2" data-placeholder="Select Tax Rate" data-fouc>
														<option value="">Select Tax Rate</option>
														<?php foreach ($tax_rates as $row2): ?>
															<option value="<?php echo $row2->tax_rate_id; ?>"><?php echo $row2->tax_rate_name; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-sm-4">
													<label class="d-block text-primary">Allow Negative Inventory</label>
													<div class="form-check">
														<label class="form-check-label font-weight-semibold">
															<input id="negative_inventory" name="negative_inventory" type="checkbox" class="form-check-input">
															Yes <i class="icon-info22 text-primary" data-popup="tooltip" title="This allows you to sell this product even when stock is below zero." data-placement="top"></i>
														</label>
													</div>
												</div>
											</div>
										</div>
										<div class="form-group mb-3">
											<div class="row">
												<div class="col-sm-12">
													<label>Description</label>
													<textarea id="product_description" name="product_description" rows="3" cols="3" class="form-control ckeditor"></textarea>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="card rounded-top-0">
									<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
										<h6 class="card-title font-weight-600 text-success text-uppercase">Inventory</h6>			
									</div>							
									<div class="card-body">		
										<div class="table-responsive">
											<table class="table">
												<thead>
													<tr>
														<th>Outlet</th>
														<th>Opening Stock</th>
														<th>Reorder Level</th>
													</tr>
												</thead>
												<tbody>
													<?php foreach ($outlets as $row): ?>
														<tr>
															<td><?php echo $row->outlet_name; ?></td>
															<td width="120"><input id="opening_stock_<?php echo $row->outlet_id; ?>" name="opening_stock_<?php echo $row->outlet_id; ?>" type="number" placeholder="" value="0" class="form-control"></td>
															<td width="120"><input id="reorder_level_<?php echo $row->outlet_id; ?>" name="reorder_level_<?php echo $row->outlet_id; ?>" type="number" placeholder="" value="0" class="form-control"></td>
														</tr>
													<?php endforeach; ?>
												</tbody>
											</table>
										</div>
									</div>
								</div>
							</div>
							<div class="col-md-3">
								<div class="card rounded-top-0">
									<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
										<h6 class="card-title font-weight-600 text-success text-uppercase">Categorize</h6>			
									</div>
							
									<div class="card-body">
										<div class="form-group mb-2">
											<div class="row">
												<div class="col-sm-12">
													<label>Product Categories <span class="error">*</span></label>
													<select id="product_category_id" name="product_category_id[]" multiple="multiple" class="form-control select-multiple" data-placeholder="Select Categories" data-fouc>
														<?php $level_count = 0; ?>
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
										</div>
										<div class="form-group mb-2">
											<div class="row">
												<div class="col-sm-12">
													<label>Brand</label>
													<select id="brand_id" name="brand_id" class="form-control form-control-select2" data-placeholder="Select Brand" data-fouc>
														<option value="">Select Brand</option>
														<?php foreach ($brands as $row2): ?>
															<option value="<?php echo $row2->brand_id; ?>"><?php echo $row2->brand_name; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
											</div>
										</div>										
										<div class="form-group mb-2">
											<div class="row">
												<div class="col-sm-12">
													<label>Product Supplier(s)</label>
													<select id="supplier_id" name="supplier_id[]" multiple="multiple" class="form-control select-multiple" data-placeholder="Select Supplier(s)" data-fouc>
														<?php foreach ($suppliers as $row2): ?>
															<option value="<?php echo $row2->supplier_id; ?>"><?php echo $row2->supplier_name; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="card rounded-top-0">
									<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
										<h6 class="card-title font-weight-600 text-success text-uppercase">Product Features</h6>			
									</div>
							
									<div class="card-body">
										<div class="form-group mb-2">
											<div class="row">
												<div class="col-sm-12">
													<label>Product Sizes</label>
													<select id="product_size_id" name="product_size_id[]" multiple="multiple" class="form-control select-multiple" data-placeholder="Select Product Sizes" data-fouc>
														<?php foreach ($product_sizes as $row2): ?>
															<option value="<?php echo $row2->product_size_id; ?>"><?php echo $row2->product_size_code; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
											</div>
										</div>
										<hr>
										<div class="form-group mb-2">
											<div class="row">
												<div class="col-sm-12">
													<label>Product Colours</label><br>
													<button id="btn_add_product_color" type="button" class="btn btn-xs btn-primary"><i class="icon-plus-circle2"></i> Add Product Color</button>
													<div id="div_product_colors">
				                                    </div>
												</div>
											</div>
										</div>
										<hr>
										<div class="form-group mb-2">
											<div class="row">
												<div class="col-sm-12">
													<label>Product Attributes</label><br>
													<button id="btn_add_product_attribute" type="button" class="btn btn-xs btn-primary"><i class="icon-plus-circle2"></i> Add Product Attribute</button>
													<div id="div_product_attributes">
				                                    </div>
												</div>
											</div>
										</div>
										
									</div>
								</div>
								<div class="card rounded-top-0">
									<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
										<h6 class="card-title font-weight-600 text-success text-uppercase">Product Options</h6>			
									</div>
							
									<div class="card-body">
										<div class="form-group mb-3">
											<div class="row">										
												<div class="col-md-6">
													<label class="d-block">Product Status <span class="error">*</span></label>
													<div class="form-check form-check-inline form-check-right">
														<label class="form-check-label font-weight-semibold">
															Online
															<input type="radio" class="form-check-input" id="is_online1" name="is_online" value="1" checked>
														</label>
													</div>
													<div class="form-check form-check-inline form-check-right">
														<label class="form-check-label font-weight-semibold">
															Offline
															<input type="radio" class="form-check-input" id="is_online0" name="is_online" value="0">
														</label>
													</div>
												</div>
												<div class="col-md-6">
													<label class="d-block">Is Featured <span class="error">*</span></label>
													<div class="form-check form-check-inline form-check-right">
														<label class="form-check-label font-weight-semibold">
															Yes
															<input type="radio" class="form-check-input" id="is_featured1" name="is_featured" value="1">
														</label>
													</div>
													<div class="form-check form-check-inline form-check-right">
														<label class="form-check-label font-weight-semibold">
															No
															<input type="radio" class="form-check-input" id="is_featured0" name="is_featured" value="0" checked>
														</label>
													</div>
												</div>
											</div>
										</div>
										<div class="form-group mb-3">
											<div class="row">										
												<div class="col-md-6">
													<label class="d-block">Is New Arrival <span class="error">*</span></label>
													<div class="form-check form-check-inline form-check-right">
														<label class="form-check-label font-weight-semibold">
															Yes
															<input type="radio" class="form-check-input" id="is_new_arrival1" name="is_new_arrival" value="1">
														</label>
													</div>
													<div class="form-check form-check-inline form-check-right">
														<label class="form-check-label font-weight-semibold">
															No
															<input type="radio" class="form-check-input" id="is_new_arrival0" name="is_new_arrival" value="0" checked>
														</label>
													</div>
												</div>
												<div class="col-md-6">
													<label class="d-block">Is Special Offer <span class="error">*</span></label>
													<div class="form-check form-check-inline form-check-right">
														<label class="form-check-label font-weight-semibold">
															Yes
															<input type="radio" class="form-check-input" id="is_special_offer1" name="is_special_offer" value="1">
														</label>
													</div>
													<div class="form-check form-check-inline form-check-right">
														<label class="form-check-label font-weight-semibold">
															No
															<input type="radio" class="form-check-input" id="is_special_offer0" name="is_special_offer" value="0" checked>
														</label>
													</div>
												</div>
											</div>
										</div>
										<div class="form-group mb-3">
											<div class="row">										
												<div class="col-sm-12">
													<label class="d-block">Is Deal of The Week <span class="error">*</span></label>
													<div class="form-check form-check-inline form-check-right">
														<label class="form-check-label font-weight-semibold">
															Yes
															<input type="radio" class="form-check-input" id="is_deal_of_the_week1" name="is_deal_of_the_week" value="1">
														</label>
													</div>
													<div class="form-check form-check-inline form-check-right">
														<label class="form-check-label font-weight-semibold">
															No
															<input type="radio" class="form-check-input" id="is_deal_of_the_week0" name="is_deal_of_the_week" value="0" checked>
														</label>
													</div>
												</div>
											</div>
										</div>

									</div>
								</div>
								
							</div>	

							<div class="col-md-3">
								<div class="card rounded-top-0">
									<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
										<h6 class="card-title font-weight-600 text-success text-uppercase">Product Images</h6>			
									</div>
							
									<div class="card-body">
										<div class="form-group mb-2">
											<div class="row">
												<div class="col-sm-12">
													<label>Main Image <span class="error">*</span></label>
													<input id="product_image" name="product_image" type="file" class="form-control">
												</div>
											</div>
										</div>
										<hr>
										<div class="form-group mb-2">
											<div class="row">
												<div class="col-sm-12">
													<label>Product Gallery</label><br>
													<button id="btn_add_product_image" type="button" class="btn btn-xs btn-primary"><i class="icon-plus-circle2"></i> Add Product Image</button>
													<div id="div_product_images">
				                                    </div>
												</div>
											</div>
										</div>
										

									</div>
								</div>
								<div class="card rounded-top-0">
									<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
										<h6 class="card-title font-weight-600 text-success text-uppercase">SEO</h6>			
									</div>							
									<div class="card-body">
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
									</div>
								</div>
							</div>

							<div class="clearfix"></div>
							<div class="col-md-12">
								<hr>
								<div class="text-right">
									<button type="submit" id="btn_add_product" class="btn btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> SAVE PRODUCT</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div id="modal_pa_unit_of_measure" class="modal fade">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title text-primary"><i class="icon-plus-circle2"></i> Product Unit of Measure</h5>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>

							<form id="frm_pa_unit_of_measure" name="frm_pa_unit_of_measure" method="post" onsubmit="return pa_unit_of_measure();">
								
								<div class="modal-body">

									<div id="div_pa_unit_of_measure_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_pa_unit_of_measure_success" class="alert alert-success display-none font-13"></div>

	                   				<input type="hidden" id="pa_context" name="pa_context">

									<div class="form-group">
										<div class="row">
											<div class="col-sm-12">
												<label>Unit of Measure <span class="error">*</span></label>
												<select id="pa_unit_id" name="pa_unit_id" class="form-control form-control-select2" data-placeholder="Select Unit" data-fouc>
													<option value="">Select Unit</option>
													<?php foreach ($units as $row2): ?>
														<option value="<?php echo $row2->unit_id; ?>"><?php echo $row2->unit_name; ?></option>
													<?php endforeach; ?>
												</select>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-12">
												<label>Regular Price <span class="error">*</span></label>
												<div class="input-group">
													<span class="input-group-prepend">
														<span class="input-group-text"><?php echo $default_currency; ?></span>
													</span>
													<input id="pa_regular_price" name="pa_regular_price" type="number" placeholder="" value="0" class="form-control" min="0">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-12">
												<label>Sale Price <i class="icon-info22 text-primary" data-popup="tooltip" title="Use this when you're running an offer. Please note that this will override the Regular Price" data-placement="top"></i></label>
												<div class="input-group">
													<span class="input-group-prepend">
														<span class="input-group-text"><?php echo $default_currency; ?></span>
													</span>
													<input id="pa_sale_price" name="pa_sale_price" type="number" placeholder="" value="0" class="form-control" min="0">
												</div>
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-12">
												<label>Minimum Selling Price <i class="icon-info22 text-primary" data-popup="tooltip" title="Leave 0 if none" data-placement="top"></i></label>
												<div class="input-group">
													<span class="input-group-prepend">
														<span class="input-group-text"><?php echo $default_currency; ?></span>
													</span>
													<input id="pa_minimum_selling_price" name="pa_minimum_selling_price" type="number" placeholder="" value="0" class="form-control" min="0">
												</div>
											</div>
										</div>
									</div>
								</div>

								<div class="modal-footer">								
									<button type="submit" id="btn_pa_unit_of_measure" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> ADD</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			<?php else: ?>
				<?php foreach ($product as $row): ?>
					<!-- Page header -->
					<div class="page-header">
						<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
							<div class="d-flex">
								<div class="breadcrumb">
									<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
									<a href="<?php echo base_url();?>be/products" class="breadcrumb-item">Products</a>
									<span class="breadcrumb-item active">Edit Product (<?php echo $row->product_name; ?>)</span>
								</div>

								<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
							</div>
						</div>
					</div>
					<!-- /page header -->


					<div class="content pt-0">
						<div class="row">
							<div class="col-md-12">
								<div class="mb-3">
									<h5 class="mb-0 font-weight-semibold">
										<i class="icon-pencil6"></i> Edit Product (<?php echo $row->product_name; ?>)
										<a href="<?php echo base_url();?>be/products" class="btn btn-sm bg-success-400 text-success-400 border-success-400 float-right"><i class="icon-arrow-left15"></i> Back to Products</a>
									</h5>
								</div>
							</div>
						</div>

						<form id="frm_edit_product" name="frm_edit_product" method="post" onsubmit="return update_product();" autocomplete="false">

							<div class="row">

								<div class="col-md-6">
									<div class="card rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h6 class="card-title font-weight-600 text-success text-uppercase">Product Information</h6>			
										</div>
								
										<div class="card-body">
											<div id="div_edit_error" class="alert alert-danger display-none font-13"></div>
			                   				<div id="div_edit_success" class="alert alert-success display-none font-13"></div>

			                   				<input type="hidden" id="product_id" name="product_id" value="<?php echo $row->product_id; ?>">

											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-8">
														<label>Product Name <span class="error">*</span></label>
														<input id="product_name" name="product_name" type="text" placeholder="" class="form-control form-control-lg font-weight-bold" value="<?php echo $row->product_name; ?>">
													</div>
													<div class="col-sm-4">
														<label>Product Barcode</label>
														<input id="product_barcode" name="product_barcode" type="text" placeholder="" class="form-control form-control-lg font-weight-bold" value="<?php echo $row->product_barcode; ?>">
													</div>
												</div>
											</div>
											<div class="form-group mb-3">
												<div class="row">
													<div class="col-sm-12">
														<label>Short Description</label>
														<textarea id="product_short_description" name="product_short_description" rows="3" cols="3" class="form-control ckeditor"><?php echo $row->product_short_description; ?></textarea>
													</div>
												</div>
											</div>
											<div class="form-group mb-3">
												<div class="row">
													<div class="col-sm-4">
														<label>Regular Price <span class="error">*</span></label>
														<div class="input-group">
															<span class="input-group-prepend">
																<span class="input-group-text"><?php echo $default_currency; ?></span>
															</span>
															<input id="regular_price" name="regular_price" type="number" placeholder="" value="<?php echo $row->regular_price; ?>" class="form-control" min="0">
														</div>
													</div>
													<div class="col-sm-4">
														<label>Sale Price <small>(Use this when you're running an offer)</small></label>
														<div class="input-group">
															<span class="input-group-prepend">
																<span class="input-group-text"><?php echo $default_currency; ?></span>
															</span>
															<input id="sale_price" name="sale_price" type="number" placeholder="" value="<?php echo $row->sale_price; ?>" class="form-control" min="0">
														</div>
													</div>
													<div class="col-sm-4">
														<label>Minimum Selling Price <small>(Leave 0 if none)</small></label>
														<div class="input-group">
															<span class="input-group-prepend">
																<span class="input-group-text"><?php echo $default_currency; ?></span>
															</span>
															<input id="minimum_selling_price" name="minimum_selling_price" type="number" placeholder="" value="<?php echo $row->minimum_selling_price; ?>" class="form-control" min="0">
														</div>
													</div>
													
												</div>
											</div>
											<div class="form-group mb-3">
												<div class="row">
													<div class="col-sm-4">
														<label>Unit of Measure <span class="error">*</span></label>
														<select id="unit_id" name="unit_id" class="form-control form-control-select2" data-placeholder="Select Unit" data-fouc>
															<option value="">Select Unit</option>
															<?php foreach ($units as $row2): ?>
																<option value="<?php echo $row2->unit_id; ?>" <?php if ($row->unit_id == $row2->unit_id){ echo 'selected'; } ?>><?php echo $row2->unit_name; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
													<div class="col-sm-4">
														<label>Tax Rate <span class="error">*</span></label>
														<select id="tax_rate_id" name="tax_rate_id" class="form-control form-control-select2" data-placeholder="Select Tax Rate" data-fouc>
															<option value="">Select Tax Rate</option>
															<?php foreach ($tax_rates as $row2): ?>
																<option value="<?php echo $row2->tax_rate_id; ?>" <?php if ($row->tax_rate_id == $row2->tax_rate_id){ echo 'selected'; } ?>><?php echo $row2->tax_rate_name; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
													<div class="col-sm-4">
														<label class="d-block text-primary">Allow Negative Inventory</label>
														<div class="form-check">
															<label class="form-check-label font-weight-semibold">
																<input id="negative_inventory" name="negative_inventory" type="checkbox" class="form-check-input" <?php if ($row->negative_inventory == 1){ echo 'checked'; } ?>>
																Yes <i class="icon-help text-warning" data-popup="tooltip" title="This allows you to sell this product even when stock is below zero." data-placement="top"></i>
															</label>
														</div>
													</div>
												</div>
											</div>
											<div class="form-group mb-3">
												<div class="row">
													<div class="col-sm-12">
														<label>Description</label>
														<textarea id="product_description" name="product_description" rows="3" cols="3" class="form-control ckeditor"><?php echo $row->product_description; ?></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h6 class="card-title font-weight-600 text-success text-uppercase">Inventory</h6>			
										</div>							
										<div class="card-body">		
											<div class="table-responsive">
												<table class="table">
													<thead>
														<tr>
															<th>Outlet</th>
															<th>Opening Stock</th>
															<th>Available Stock</th>
															<th>Reorder Level</th>
														</tr>
													</thead>
													<tbody>
														<?php foreach ($outlets as $row2): ?>
															<?php
																$cur_opening_stock = 0;
																$cur_available_stock = 0;
																$cur_reorder_level = 0;
																foreach ($outlet_products as $row3) {
																	if ($row2->outlet_id == $row3->outlet_id) {
																		$cur_opening_stock = $row3->opening_stock;
																		$cur_available_stock = $row3->available_stock;
																		$cur_reorder_level = $row3->reorder_level;
																	}
																}
															?>
															<tr>
																<td><?php echo $row2->outlet_name; ?></td>
																<td width="120"><input id="opening_stock_<?php echo $row2->outlet_id; ?>" name="opening_stock_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" value="<?php echo $cur_opening_stock; ?>" class="form-control"></td>
																<td width="120"><input id="available_stock_<?php echo $row2->outlet_id; ?>" name="available_stock_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" value="<?php echo $cur_available_stock; ?>" class="form-control" readonly></td>
																<td width="120"><input id="reorder_level_<?php echo $row2->outlet_id; ?>" name="reorder_level_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" value="<?php echo $cur_reorder_level; ?>" class="form-control"></td>
															</tr>
														<?php endforeach; ?>
													</tbody>
												</table>
											</div>
										</div>
									</div>
								</div>
								<div class="col-md-3">
									<div class="card rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h6 class="card-title font-weight-600 text-success text-uppercase">Categorize</h6>			
										</div>
								
										<div class="card-body">
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-12">
														<label>Product Categories <span class="error">*</span></label>
														<select id="product_category_id" name="product_category_id[]" multiple="multiple" class="form-control select-multiple" data-placeholder="Select Categories" data-fouc>
															<?php $level_count = 0; ?>

															<?php
																function fetch_sub_categories($sub_categories, $level_count, $product_product_categories){
																	$level_count = $level_count + 1;
																	foreach($sub_categories as $sub_category){
																		$mdash = '';
																		$mspace = '';
																		$selected = '';
																		foreach($product_product_categories as $row3){ if($row3->product_category_id == $sub_category->product_category_id){$selected = 'selected'; }}
																		for($i = 0; $i < $level_count; $i++){$mdash = $mdash . '&mdash;'; $mspace = $mspace . '&nbsp;&nbsp;';}
																		echo '<option value="' . $sub_category->product_category_id . '" ' . $selected . '>' . $mspace . $mdash . ' ' . $sub_category->product_category_name . '</option>';
																		if(!empty($sub_category->sub)){
																			fetch_sub_categories($sub_category->sub, $level_count, $product_product_categories);
																		}
																	}
																}
															?>															
															<?php foreach ($product_categories as $row2): ?>
																<option value="<?php echo $row2->product_category_id; ?>" <?php foreach($product_product_categories as $row3){ if($row3->product_category_id == $row2->product_category_id){ echo 'selected'; }} ?>><?php echo $row2->product_category_name; ?></option>
																<?php
																	if(!empty($row2->sub)){
																		fetch_sub_categories($row2->sub, $level_count, $product_product_categories);
																	}
																?>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-12">
														<label>Brand</label>
														<select id="brand_id" name="brand_id" class="form-control form-control-select2" data-placeholder="Select Brand" data-fouc>
															<option value="">Select Brand</option>
															<?php foreach ($brands as $row2): ?>
																<option value="<?php echo $row2->brand_id; ?>" <?php if ($row->brand_id == $row2->brand_id){ echo 'selected'; } ?>><?php echo $row2->brand_name; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											</div>										
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-12">
														<label>Product Supplier(s)</label>
														<select id="supplier_id" name="supplier_id[]" multiple="multiple" class="form-control select-multiple" data-placeholder="Select Supplier(s)" data-fouc>
															<?php foreach ($suppliers as $row2): ?>
																<option value="<?php echo $row2->supplier_id; ?>" <?php foreach($product_suppliers as $row3){ if($row3->supplier_id == $row2->supplier_id){ echo 'selected'; }} ?>><?php echo $row2->supplier_name; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h6 class="card-title font-weight-600 text-success text-uppercase">Product Features</h6>			
										</div>
								
										<div class="card-body">
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-12">
														<label>Product Sizes</label>
														<select id="product_size_id" name="product_size_id[]" multiple="multiple" class="form-control select-multiple" data-placeholder="Select Product Sizes" data-fouc>
															<?php foreach ($product_sizes as $row2): ?>
																<option value="<?php echo $row2->product_size_id; ?>" <?php foreach($product_product_sizes as $row3){ if($row3->product_size_id == $row2->product_size_id){ echo 'selected'; }} ?>><?php echo $row2->product_size_code; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											</div>
											<hr>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-12">
														<label>Product Colours</label><br>
														<button id="btn_add_product_color" type="button" class="btn btn-xs btn-primary"><i class="icon-plus-circle2"></i> Add Product Color</button>
														<div id="div_product_colors">
															<?php foreach ($product_colors as $row2): ?>
																<div class="form-group mt-1 mb-1">
																	<div class="row">
																		<div class="col-md-6">
																			<input type="text" name="product_color[]" class="form-control product-color colorpicker-show-input" data-show-buttons="false" data-color="<?php echo $row2->product_color; ?>" value="<?php echo $row2->product_color; ?>" data-fouc>
																			<a href="javascript:void(0)" class="badge badge-danger remove-color ml-1" title="Remove Color"><i class="icon-cancel-circle2"></i></a>
																		</div>
																	</div>
																</div>
															<?php endforeach; ?>
					                                    </div>
													</div>
												</div>
											</div>
											<hr>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-12">
														<label>Product Attributes</label><br>
														<button id="btn_add_product_attribute" type="button" class="btn btn-xs btn-primary"><i class="icon-plus-circle2"></i> Add Product Attribute</button>
														<div id="div_product_attributes">
															<?php foreach ($product_attributes as $row2): ?>
																<div class="form-group mt-1 mb-1">
																	<div class="row">
																		<div class="col-md-5">
																			<label>Name</label>
																			<input type="text" name="product_attribute_name[]" value="<?php echo $row2->product_attribute_name; ?>" class="form-control product-attribute-name">
																		</div>
																		<div class="col-md-5">
																			<label>Value</label>
																			<input type="text" name="product_attribute_value[]" value="<?php echo $row2->product_attribute_value; ?>" class="form-control">
																		</div>
																		<div class="col-md-2">
																			<label class="d-block">&nbsp;</label>
																			<a href="javascript:void(0)" class="badge badge-danger remove-attribute" title="Remove Attribute"><i class="icon-cancel-circle2"></i></a>
																		</div>
																	</div>
																</div>
															<?php endforeach; ?>
					                                    </div>
													</div>
												</div>
											</div>
											
										</div>
									</div>
									<div class="card rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h6 class="card-title font-weight-600 text-success text-uppercase">Product Options</h6>			
										</div>
								
										<div class="card-body">
											<div class="form-group mb-3">
												<div class="row">										
													<div class="col-md-6">
														<label class="d-block">Product Status <span class="error">*</span></label>
														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																Online
																<input type="radio" class="form-check-input" id="is_online1" name="is_online" value="1" <?php if ($row->is_online == 1){ echo 'checked'; } ?>>
															</label>
														</div>
														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																Offline
																<input type="radio" class="form-check-input" id="is_online0" name="is_online" value="0" <?php if ($row->is_online == 0){ echo 'checked'; } ?>>
															</label>
														</div>
													</div>
													<div class="col-md-6">
														<label class="d-block">Is Featured <span class="error">*</span></label>
														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																Yes
																<input type="radio" class="form-check-input" id="is_featured1" name="is_featured" value="1" <?php if ($row->is_featured == 1){ echo 'checked'; } ?>>
															</label>
														</div>
														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																No
																<input type="radio" class="form-check-input" id="is_featured0" name="is_featured" value="0" <?php if ($row->is_featured == 0){ echo 'checked'; } ?>>
															</label>
														</div>
													</div>
												</div>
											</div>
											<div class="form-group mb-3">
												<div class="row">										
													<div class="col-md-6">
														<label class="d-block">Is New Arrival <span class="error">*</span></label>
														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																Yes
																<input type="radio" class="form-check-input" id="is_new_arrival1" name="is_new_arrival" value="1" <?php if ($row->is_new_arrival == 1){ echo 'checked'; } ?>>
															</label>
														</div>
														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																No
																<input type="radio" class="form-check-input" id="is_new_arrival0" name="is_new_arrival" value="0" <?php if ($row->is_new_arrival == 0){ echo 'checked'; } ?>>
															</label>
														</div>
													</div>
													<div class="col-md-6">
														<label class="d-block">Is Special Offer <span class="error">*</span></label>
														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																Yes
																<input type="radio" class="form-check-input" id="is_special_offer1" name="is_special_offer" value="1" <?php if ($row->is_special_offer == 1){ echo 'checked'; } ?>>
															</label>
														</div>
														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																No
																<input type="radio" class="form-check-input" id="is_special_offer0" name="is_special_offer" value="0" <?php if ($row->is_special_offer == 0){ echo 'checked'; } ?>>
															</label>
														</div>
													</div>
												</div>
											</div>
											<div class="form-group mb-3">
												<div class="row">										
													<div class="col-sm-12">
														<label class="d-block">Is Deal of The Week <span class="error">*</span></label>
														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																Yes
																<input type="radio" class="form-check-input" id="is_deal_of_the_week1" name="is_deal_of_the_week" value="1" <?php if ($row->is_deal_of_the_week == 1){ echo 'checked'; } ?>>
															</label>
														</div>
														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																No
																<input type="radio" class="form-check-input" id="is_deal_of_the_week0" name="is_deal_of_the_week" value="0" <?php if ($row->is_deal_of_the_week == 0){ echo 'checked'; } ?>>
															</label>
														</div>
													</div>
												</div>
											</div>

										</div>
									</div>
									
								</div>	

								<div class="col-md-3">
									<div class="card rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h6 class="card-title font-weight-600 text-success text-uppercase">Product Image</h6>			
										</div>
								
										<div class="card-body">
											<div class="row">
												<div class="col-sm-12">
													<?php if($row->product_image != '' && file_exists("./uploads/product_images/" . $row->product_image)): ?>
														<div class="card-img-actions d-inline-block mb-2">
															<img class="card-img img-fluid" src="<?php echo base_url(); ?>uploads/product_images/<?php echo $row->product_image; ?>" alt="">
														</div>
														<div class="form-group mb-2">
															<div class="row">
																<div class="col-sm-12">
																	<label>Change Product Image</label>
																	<input id="product_image" name="product_image" type="file" class="form-control">
																</div>
															</div>
														</div>
													<?php else: ?>
														<div class="form-group mb-2">
															<div class="row">
																<div class="col-sm-12">
																	<label>Select Product Image</label>
																	<input id="product_image" name="product_image" type="file" class="form-control">
																</div>
															</div>
														</div>
													<?php endif; ?>
												</div>
											</div>
										</div>
									</div>
									<div class="card rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h6 class="card-title font-weight-600 text-success text-uppercase">Product Gallery</h6>			
										</div>								
										<div class="card-body">
											<div class="row">
												<?php $numimages = $product_num_images;?>
	                                            <?php $i = 1; ?>
	                                            <?php foreach ($product_images as $poi): ?>
	                                            	<div class="col-md-6">
														<div class="card">
															<?php if($poi->image_filename != '' && file_exists("./uploads/product_images/" . $poi->image_filename)): ?>
																<div class="card-img-actions mx-1 mt-1">
																	<img class="card-img img-fluid" src="<?php echo base_url(); ?>uploads/product_images/<?php echo $poi->image_filename; ?>" alt="">
																	<div class="card-img-actions-overlay card-img">
																		<a href="<?php echo base_url(); ?>uploads/product_images/<?php echo $poi->image_filename; ?>" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
																			<i class="icon-plus3"></i>
																		</a>
																	</div>
																</div>
															<?php endif; ?>

															<div class="card-body">
																<div class="d-flex align-items-start flex-nowrap">
																	<div class="list-icons list-icons-extended ml-auto">
																		<a data-toggle="modal" role="button" href="#modal_edit_other_image" class="list-icons-item product_edit_other_image" data-id ="<?php echo $poi->product_image_id; ?>" title="Edit Image"><i class="icon-pencil top-0"></i></a>
																		<a href="<?php echo base_url(); ?>uploads/product_images/<?php echo $poi->image_filename; ?>" class="list-icons-item" download title="Download Image"><i class="icon-download top-0"></i></a>
																		<a onclick="delete_product_image(<?php echo $poi->product_image_id; ?>);" href="javascript:void(0);" class="list-icons-item" title="Delete Image"><i class="icon-trash top-0"></i></a>
																	</div>
																</div>
															</div>
														</div>
													</div>

	                                                <?php if($i == 2): ?>
	                                                    <div class="clearfix"></div>
	                                                <?php endif; ?>
	                                                <?php $i++; ?>
	                                            <?php endforeach; ?>
	                                            <div class="col-md-6">
													<div class="card">

														<div class="card-header text-center">
															<h6>Add Product Image</h6>
														</div>

														<div class="card-body">
															<div class="caption text-center">
                                                                <h6><a data-toggle="modal" role="button" href="#modal_add_other_image" class="btn btn-icon btn-success"><i class="icon-plus-circle2" title="Add Image"></i></a></h6>
                                                            </div>
														</div>
													</div>
												</div>
	                                        </div>

										</div>
									</div>
									<div class="card rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h6 class="card-title font-weight-600 text-success text-uppercase">SEO</h6>			
										</div>							
										<div class="card-body">
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-12">
														<label>Description</label>
														<textarea id="seo_description" name="seo_description" class="form-control" rows="2" ><?php echo $row->seo_description; ?></textarea>
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-12">
														<label>Keywords</label>
														<textarea id="seo_keywords" name="seo_keywords" class="form-control" rows="1" ><?php echo $row->seo_keywords; ?></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>


								<div class="clearfix"></div>
								<div class="col-md-12">
									<hr>
									<div class="text-right">
										<button type="submit" id="btn_edit_product" class="btn btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE PRODUCT</button>
									</div>
								</div>
							</div>
						</form>


						<!-- Edit other image modal form -->
						<div id="modal_edit_other_image" class="modal fade">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-primary"><i class="icon-pencil"></i> Edit Product Image</h5>
										<button type="button" class="close" data-dismiss="modal">&times;</button>
									</div>

									<form id="frm_product_edit_other_image" name="frm_product_edit_other_image" method="post" onsubmit="return upload_edit_product_other_image();">
										
										<div class="modal-body">
											<div class="block-inner text-secondary">
	                                            <h6 class="heading-hr"> <small class="display-block">Browse for the image then click Save</small></h6>
	                                        </div>

											<div id="div_product_edit_other_image_error" class="alert alert-danger display-none font-13"></div>
			                   				<div id="div_product_edit_other_image_success" class="alert alert-success display-none font-13"></div>

			                   				<input type="hidden" id="product_edit_other_image_id" name="product_edit_other_image_id" value="<?php echo $row->product_id; ?>">

											<div class="form-group">
												<!-- <label>&nbsp;</label> -->
												<input id="product_edit_other_image" name="product_edit_other_image" type="file" class="form-control">
												<span class="form-text text-muted">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
											</div>
										</div>

										<div class="modal-footer">								
											<button type="submit" id="btn_product_edit_other_image" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> SAVE</button>
											<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
										</div>
									</form>
								</div>
							</div>
						</div>

						<!-- Add other image modal form -->
						<div id="modal_add_other_image" class="modal fade">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-primary"><i class="icon-plus-circle2"></i> Add Product Image</h5>
										<button type="button" class="close" data-dismiss="modal">&times;</button>
									</div>

									<form id="frm_product_add_other_image" name="frm_product_add_other_image" method="post" onsubmit="return upload_add_product_other_image();">
										
										<div class="modal-body">
											<div class="block-inner text-secondary">
	                                            <h6 class="heading-hr"> <small class="display-block">Browse for the image then click Save</small></h6>
	                                        </div>

											<div id="div_product_add_other_image_error" class="alert alert-danger display-none font-13"></div>
			                   				<div id="div_product_add_other_image_success" class="alert alert-success display-none font-13"></div>

			                   				<input type="hidden" id="product_add_other_image_product_id" name="product_add_other_image_product_id" value="<?php echo $row->product_id; ?>">

											<div class="form-group">
												<!-- <label>&nbsp;</label> -->
												<input id="product_add_other_image" name="product_add_other_image" type="file" class="form-control">
												<span class="form-text text-muted">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
											</div>
										</div>

										<div class="modal-footer">								
											<button type="submit" id="btn_product_add_other_image" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> SAVE</button>
											<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
										</div>
									</form>
								</div>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>

			<script type="text/javascript">
				CKEDITOR.replace( 'product_short_description', {
                    height: 100,
                    toolbar: [
						{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo', 'Styles', 'Format', 'Font', 'FontSize', 'NumberedList', 'BulletedList' ] }
					]
                });
				CKEDITOR.replace( 'product_description', {
                    height: 200,
                    toolbar: [
						{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo', 'Styles', 'Format', 'Font', 'FontSize', 'NumberedList', 'BulletedList' ] }
					]
                });
			</script>


