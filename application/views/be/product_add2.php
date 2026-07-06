		<!-- Main content -->
		<div class="content-wrapper">

			<?php if ($view_mode == 'Add'): ?>

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
								<h5 class="mb-0 font-weight-semibold text-primary">
									<i class="icon-plus-circle2"></i> NEW PRODUCT
									<a href="<?php echo base_url();?>be/products" class="btn btn-sm btn-primary float-right"><i class="icon-arrow-left15"></i> Back to Products</a>
								</h5>
							</div>
						</div>
					</div>

					<form id="frm_add_product" name="frm_add_product" method="post" onsubmit="return save_product();" autocomplete="false">
						<div class="spinner2 display-none" id="product_loader">
	                        <div class="rect1"></div>
	                        <div class="rect2"></div>
	                        <div class="rect3"></div>
	                    </div>
						<div class="row">
							<?php if (!isset($product)): ?>
								<div class="col-md-9">
									<div id="div_add_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_add_success" class="alert alert-success display-none font-13"></div>                   				

	                   				<input type="hidden" id="product_id" name="product_id" value="">
	                   				<input type="hidden" id="publish_status" name="publish_status" value="">

									<div class="form-group mb-2">
										<div class="row">
											<div class="col-sm-12">
												<label class="font-size-lg">Product Name <span class="error">*</span></label>
												<input id="product_name" name="product_name" type="text" placeholder="" class="form-control form-control-lg font-weight-bold">
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
									<div class="card border-top-success rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<div class="row">
												<h5 class="card-title font-weight-600 text-dark font-size-lg">Product Data &mdash;&nbsp;</h5>
												<h6 class="font-weight-bold font-size-lg mb-0">
													<select id="product_type" name="product_type" class="form-control select-basic" data-placeholder="Select Product Type" style="min-width: 200px;" data-fouc>
														<option value="Simple">Simple Product</option>
														<option value="Variable">Variable Product</option>
													</select>
												</h6>
											</div>	
										</div>
								
										<div class="card-body">
											<div class="d-md-flex">
												<ul class="nav nav-tabs nav-tabs-vertical flex-column mr-md-3 wmin-md-200 mb-md-0 alpha-grey">
													<li class="nav-item"><a href="#tab-general" class="nav-link active" data-toggle="tab"><i class="icon-wrench2 mr-2"></i> General</a></li>
													<li class="nav-item"><a href="#tab-units" class="nav-link" data-toggle="tab"><i class="icon-hour-glass mr-2"></i> Units of Measure</a></li>
													<li class="nav-item"><a href="#tab-attributes" class="nav-link" data-toggle="tab"><i class="icon-newspaper mr-2"></i> Attributes</a></li>
													<li class="nav-item display-none" id="lnk-tab-variations"><a href="#tab-variations" class="nav-link" data-toggle="tab"><i class="icon-tree7 mr-2"></i> Variations</a></li>
													<!-- <li class="nav-item"><a href="#tab-linked-products" class="nav-link" data-toggle="tab"><i class="icon-link2 mr-2"></i> Linked Products</a></li> -->
													<li class="nav-item" id="lnk-tab-inventory"><a href="#tab-inventory" class="nav-link" data-toggle="tab"><i class="icon-cube3 mr-2"></i> Inventory</a></li>
												</ul>

												<div class="tab-content w-100">
													<div class="tab-pane fade show active" id="tab-general">
														<div class="form-group mb-2">
															<div class="row">
																<div class="col-sm-3">
																	<label>Product Barcode</label>
																</div>
																<div class="col-sm-4">
																	<input id="product_barcode" name="product_barcode" type="text" placeholder="" class="form-control">
																</div>
															</div>
														</div>
														<div class="form-group mb-2">
															<div class="row">
																<div class="col-sm-3">
																	<label>Tax Rate <span class="error">*</span></label>
																</div>
																<div class="col-sm-4">
																	<select id="tax_rate_id" name="tax_rate_id" class="form-control form-control-select2" data-placeholder="Select Tax Rate" data-fouc>
																		<option value="">Select Tax Rate</option>
																		<?php foreach ($tax_rates as $row2): ?>
																			<option value="<?php echo $row2->tax_rate_id; ?>"><?php echo $row2->tax_rate_name; ?></option>
																		<?php endforeach; ?>
																	</select>
																</div>
															</div>
														</div>
														<div class="form-group mb-3">
															<div class="row">
																<div class="col-sm-3">
																	<label class="d-block">Base Unit of Measure <span class="error">*</span> <i class="icon-info22 text-primary" data-popup="popover" title="" data-trigger="hover" data-content="The base unit of measure should be the smallest increment used to track the item. e.g. If you buy a product in bags of 100 but use one or two at a time, you should select a base unit of 'each' instead of '1 bag of 100'"></i></label>
																</div>
																<div class="col-sm-4">
																	<select id="add_product_unit_id" name="unit_id" class="form-control form-control-select2" data-placeholder="Select Base Unit" data-fouc>
																		<option value="">Select Base Unit</option>
																		<?php foreach ($unit_types as $row3): ?>
																			<optgroup label="<?php echo $row3->unit_type_name; ?> <?php if ($row3->unit_type_description !== ''){ echo '(' . $row3->unit_type_description . ')'; } ?>">
																				<?php foreach ($units as $row2): ?>
																					<?php if ($row2->unit_type_id == $row3->unit_type_id): ?>
																						<option value="<?php echo $row2->unit_id; ?>"><?php echo $row2->unit_name; ?> <?php echo '(' . $row2->unit_code . ')'; ?></option>
																					<?php endif; ?>
																				<?php endforeach; ?>
																			</optgroup>
																		<?php endforeach; ?>
																	</select>
																</div>
															</div>															
														</div>
														<div id="div_simple_product_prices">
															<div class="form-group mb-2">
																<div class="row">
																	<div class="col-sm-3">
																		<label>Regular Price <span class="error">*</span></label>
																	</div>
																	<div class="col-sm-4">
																		<div class="input-group">
																			<span class="input-group-prepend">
																				<span class="input-group-text"><?php echo $default_currency; ?></span>
																			</span>
																			<input id="regular_price" name="regular_price" type="number" placeholder="" value="0" class="form-control" min="0">
																		</div>
																		<div class="text-right mt-1 mb-0"><small><input type="checkbox" id="chk_product_outlet_regular_prices" name="chk_product_outlet_regular_prices" checked> <label for="chk_product_outlet_regular_prices">Use this price across all outlets</label></small></div>
																		<div id="div_product_outlet_regular_prices" class="bg-light display-none">
																			<small>
																				<div class="table-responsive">
																					<table class="table table-condensed">
																						<?php foreach ($outlets as $row2): ?>
																							<tr>
																								<td><?php echo $row2->outlet_name; ?></td>
																								<td><input id="outlet_regular_price_<?php echo $row2->outlet_id; ?>" name="outlet_regular_price_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" class="form-control" min="0" value="0"></td>
																							</tr>
																						<?php endforeach; ?>
																					</table>
																				</div>
																			</small>
																		</div>
																	</div>
																</div>
															</div>
															<div class="form-group mb-2">
																<div class="row">
																	<div class="col-sm-3">
																		<label>Sale Price <i class="icon-info22 text-primary" data-popup="tooltip" title="Use this when you're running an offer. Please note that this will override the Regular Price" data-placement="top"></i></label>
																	</div>
																	<div class="col-sm-4">
																		<div class="input-group">
																			<span class="input-group-prepend">
																				<span class="input-group-text"><?php echo $default_currency; ?></span>
																			</span>
																			<input id="sale_price" name="sale_price" type="number" placeholder="" value="0" class="form-control" min="0">
																		</div>
																		<div class="text-right mt-1 mb-0"><small><input type="checkbox" id="chk_product_outlet_sale_prices" name="chk_product_outlet_sale_prices" checked> <label for="chk_product_outlet_sale_prices">Use this price across all outlets</label></small></div>
																		<div id="div_product_outlet_sale_prices" class="bg-light display-none">
																			<small>
																				<div class="table-responsive">
																					<table class="table table-condensed">
																						<?php foreach ($outlets as $row2): ?>
																							<tr>
																								<td><?php echo $row2->outlet_name; ?></td>
																								<td><input id="outlet_sale_price_<?php echo $row2->outlet_id; ?>" name="outlet_sale_price_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" class="form-control" min="0" value="0"></td>
																							</tr>
																						<?php endforeach; ?>
																					</table>
																				</div>
																			</small>
																		</div>
																	</div>
																</div>
															</div>
															<div class="form-group mb-2">
																<div class="row">
																	<div class="col-sm-3">
																		<label>Minimum Selling Price <i class="icon-info22 text-primary" data-popup="tooltip" title="Leave 0 if none" data-placement="top"></i></label>
																	</div>
																	<div class="col-sm-4">
																		<div class="input-group">
																			<span class="input-group-prepend">
																				<span class="input-group-text"><?php echo $default_currency; ?></span>
																			</span>
																			<input id="minimum_selling_price" name="minimum_selling_price" type="number" placeholder="" value="0" class="form-control" min="0">
																		</div>
																		<div class="text-right mt-1 mb-0"><small><input type="checkbox" id="chk_product_outlet_minimum_prices" name="chk_product_outlet_minimum_prices" checked> <label for="chk_product_outlet_minimum_prices">Use this price across all outlets</label></small></div>
																		<div id="div_product_outlet_minimum_prices" class="bg-light display-none">
																			<small>
																				<div class="table-responsive">
																					<table class="table table-condensed">
																						<?php foreach ($outlets as $row2): ?>
																							<tr>
																								<td><?php echo $row2->outlet_name; ?></td>
																								<td><input id="outlet_minimum_price_<?php echo $row2->outlet_id; ?>" name="outlet_minimum_price_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" class="form-control" min="0" value="0"></td>
																							</tr>
																						<?php endforeach; ?>
																					</table>
																				</div>
																			</small>
																		</div>
																	</div>
																</div>
															</div>
														</div>
														<div class="form-group mb-2">
															<div class="row">
																<div class="col-sm-3">
																	<label class="d-block">Allow Negative Inventory</label>
																</div>
																<div class="col-sm-4">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="negative_inventory" name="negative_inventory" type="checkbox" class="form-check-input">
																			Yes <i class="icon-info22 text-primary" data-popup="tooltip" title="This allows you to sell this product even when stock is below zero." data-placement="top"></i>
																		</label>
																	</div>
																</div>
															</div>
														</div>

													</div>

													<div class="tab-pane fade" id="tab-units">
														<div class="spinner2 display-none" id="units_loader">
										                    <div class="rect1"></div>
										                    <div class="rect2"></div>
										                    <div class="rect3"></div>
										                </div>
														
														<div class="row">
															<div class="col-sm-10">
																<div id="add_related_units">
																	
																</div>
															</div>
														</div>

													</div>

													<div class="tab-pane fade" id="tab-attributes">
														<div class="form-group mb-0">
															<button id="btn_modal_add_product_attribute" type="button"role="button" class="btn btn-xs btn-primary"><i class="icon-plus-circle2"></i> Add Product Attribute</button>
														</div>
														<hr class="mt-2 mb-2">
														<div id="div_product_attributes">
														</div>
													</div>

													<div class="tab-pane fade " id="tab-variations">
														<div class="alert alert-info alert-styled-right alert-dismissible">
															Before you can add a product variation you need to add some variation attributes on the <span class="font-weight-bold">Attributes</span> tab.
													    </div>
													</div>

													<div class="tab-pane fade" id="tab-linked-products">

													</div>
													<div class="tab-pane fade" id="tab-inventory">
														<div class="table-responsive">
															<table class="table">
																<thead>
																	<tr>
																		<th>Outlet</th>
																		<th>Opening Stock</th>
																		<!-- <th>Available Stock</th> -->
																		<th>Reorder Level</th>
																	</tr>
																</thead>
																<tbody>
																	<?php foreach ($outlets as $row): ?>
																		<tr>
																			<td><?php echo $row->outlet_name; ?></td>
																			<td width="200"><input id="opening_stock_<?php echo $row->outlet_id; ?>" name="opening_stock_<?php echo $row->outlet_id; ?>" type="number" placeholder="" value="0" class="form-control"></td>
																			<td width="200"><input id="reorder_level_<?php echo $row->outlet_id; ?>" name="reorder_level_<?php echo $row->outlet_id; ?>" type="number" placeholder="" value="0" class="form-control"></td>
																		</tr>
																	<?php endforeach; ?>
																</tbody>
															</table>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card border-top-success rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h5 class="card-title font-weight-600 text-dark font-size-lg">Product Long Description</h5>			
										</div>							
										<div class="card-body">
											<div class="form-group mb-3">
												<div class="row">
													<div class="col-sm-12">
														<!-- <label>Long Description</label> -->
														<textarea id="product_description" name="product_description" rows="3" cols="3" class="form-control ckeditor"></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card border-top-success rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h5 class="card-title font-weight-600 text-dark font-size-lg">SEO</h5>			
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
								<div class="col-md-3">
									<div class="card border-top-success rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h5 class="card-title font-weight-600 text-dark font-size-lg">Categorize</h5>			
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
									<div class="card border-top-success rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h5 class="card-title font-weight-600 text-dark font-size-lg">Product Image</h5>			
										</div>
								
										<div class="card-body">
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-12">
														<button type="button" id="btn_modal_set_product_image" class="btn btn-lg btn-link"><i class="icon-image2 mr-1"></i> Set Product Image</button>
													</div>
												</div>
											</div>

										</div>
									</div>
									<div class="card border-top-success rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h5 class="card-title font-weight-600 text-dark font-size-lg">Product Gallery</h5>			
										</div>
								
										<div class="card-body">
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-12">
														<button type="button" id="btn_modal_add_product_gallery_image" class="btn btn-lg btn-link"><i class="icon-image2 mr-1"></i> Add Product Gallery Image(s)</button>
														<div id="div_product_gallery_images">

					                                    </div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card border-top-success rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h5 class="card-title font-weight-600 text-dark font-size-lg">Product Options</h5>			
										</div>
								
										<div class="card-body">
											<div class="form-group mb-2">
												<div class="row">										
													<div class="col-md-4">
														<label class="d-block">Product Status <span class="error">*</span>:</label>
													</div>
													<div class="col-md-8">
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
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-4">
														<label class="d-block">Is Featured <span class="error">*</span>:</label>
													</div>
													<div class="col-md-8">
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
											<div class="form-group mb-2">
												<div class="row">										
													<div class="col-md-4">
														<label class="d-block">Is New Arrival <span class="error">*</span>:</label>
													</div>
													<div class="col-md-8">
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
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-4">
														<label class="d-block">Is Special Offer <span class="error">*</span>:</label>
													</div>
													<div class="col-md-8">
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
											<div class="form-group mb-2">
												<div class="row">										
													<div class="col-sm-4">
														<label class="d-block">Is Deal of The Week <span class="error">*</span>:</label>
													</div>
													<div class="col-md-8">
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
							<?php else: ?>
								<?php foreach ($product as $row): ?>
									<div class="col-md-9">
										<div id="div_add_error" class="alert alert-danger display-none font-13"></div>
		                   				<div id="div_add_success" class="alert alert-success display-none font-13"></div>                   				

		                   				<input type="hidden" id="product_id" name="product_id" value="<?php echo $row->product_id; ?>">

										<div class="form-group mb-2">
											<div class="row">
												<div class="col-sm-12">
													<label class="font-size-lg">Product Name <span class="error">*</span></label>
													<input id="product_name" name="product_name" type="text" placeholder="" class="form-control form-control-lg font-weight-bold" value="<?php echo $row->product_name; ?>">
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
										<div class="card border-top-success rounded-top-0">
											<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
												<div class="row">
													<h5 class="card-title font-weight-600 text-dark font-size-lg">Product Data &mdash;&nbsp;</h5>
													<h6 class="font-weight-bold font-size-lg mb-0">
														<select id="product_type" name="product_type" class="form-control select-basic" data-placeholder="Select Product Type" style="min-width: 200px;" data-fouc>
															<option value="Simple" <?php if ($row->product_type == 'Simple'){ echo 'selected'; } ?>>Simple Product</option>
															<option value="Variable" <?php if ($row->product_type == 'Variable'){ echo 'selected'; } ?>>Variable Product</option>
														</select>
													</h6>
												</div>	
											</div>
									
											<div class="card-body">
												<div class="d-md-flex">
													<ul class="nav nav-tabs nav-tabs-vertical flex-column mr-md-3 wmin-md-200 mb-md-0 alpha-grey">
														<li class="nav-item"><a href="#tab-general" class="nav-link active" data-toggle="tab"><i class="icon-wrench2 mr-2"></i> General</a></li>
														<li class="nav-item"><a href="#tab-units" class="nav-link" data-toggle="tab"><i class="icon-hour-glass mr-2"></i> Units of Measure</a></li>
														<li class="nav-item"><a href="#tab-attributes" class="nav-link" data-toggle="tab"><i class="icon-newspaper mr-2"></i> Attributes</a></li>
														<li class="nav-item <?php if ($row->product_type != 'Variable'){ echo 'display-none'; } ?>" id="lnk-tab-variations"><a href="#tab-variations" class="nav-link" data-toggle="tab"><i class="icon-tree7 mr-2"></i> Variations</a></li>
														<!-- <li class="nav-item"><a href="#tab-linked-products" class="nav-link" data-toggle="tab"><i class="icon-link2 mr-2"></i> Linked Products</a></li> -->
														<li class="nav-item <?php if ($row->product_type == 'Variable'){ echo 'display-none'; } ?>" id="lnk-tab-inventory"><a href="#tab-inventory" class="nav-link" data-toggle="tab"><i class="icon-cube3 mr-2"></i> Inventory</a></li>
													</ul>

													<div class="tab-content w-100">
														<div class="tab-pane fade show active" id="tab-general">
															<div class="form-group mb-2">
																<div class="row">
																	<div class="col-sm-3">
																		<label>Product Barcode</label>
																	</div>
																	<div class="col-sm-4">
																		<input id="product_barcode" name="product_barcode" type="text" placeholder="" class="form-control" value="<?php echo $row->product_barcode; ?>">
																	</div>
																</div>
															</div>
															<div class="form-group mb-2">
																<div class="row">
																	<div class="col-sm-3">
																		<label>Tax Rate <span class="error">*</span></label>
																	</div>
																	<div class="col-sm-4">
																		<select id="tax_rate_id" name="tax_rate_id" class="form-control form-control-select2" data-placeholder="Select Tax Rate" data-fouc>
																			<option value="">Select Tax Rate</option>
																			<?php foreach ($tax_rates as $row2): ?>
																				<option value="<?php echo $row2->tax_rate_id; ?>"  <?php if ($row->tax_rate_id == $row2->tax_rate_id){ echo 'selected'; } ?>><?php echo $row2->tax_rate_name; ?></option>
																			<?php endforeach; ?>
																		</select>
																	</div>
																</div>
															</div>
															<div class="form-group mb-3">
																<div class="row">
																	<div class="col-sm-3">
																		<label class="d-block">Base Unit of Measure <span class="error">*</span> <i class="icon-info22 text-primary" data-popup="popover" title="" data-trigger="hover" data-content="The base unit of measure should be the smallest increment used to track the item. e.g. If you buy a product in bags of 100 but use one or two at a time, you should select a base unit of 'each' instead of '1 bag of 100'"></i></label>
																	</div>
																	<div class="col-sm-4">
																		<select id="edit_product_unit_id" name="unit_id" class="form-control form-control-select2" data-placeholder="Select Base Unit" data-fouc>
																			<option value="">Select Base Unit</option>
																			<?php foreach ($unit_types as $row3): ?>
																				<optgroup label="<?php echo $row3->unit_type_name; ?> <?php if ($row3->unit_type_description !== ''){ echo '(' . $row3->unit_type_description . ')'; } ?>">
																					<?php foreach ($units as $row2): ?>
																						<?php if ($row2->unit_type_id == $row3->unit_type_id): ?>
																							<option value="<?php echo $row2->unit_id; ?>" <?php if ($row->unit_id == $row2->unit_id){ echo 'selected'; } ?>><?php echo $row2->unit_name; ?> <?php echo '(' . $row2->unit_code . ')'; ?></option>
																						<?php endif; ?>
																					<?php endforeach; ?>
																				</optgroup>
																			<?php endforeach; ?>
																		</select>
																	</div>
																</div>															
															</div>
															<div id="div_simple_product_prices" class="<?php if ($row->product_type == 'Variable'){ echo 'display-none'; } ?>">
																<div class="form-group mb-2">
																	<div class="row">
																		<div class="col-sm-3">
																			<label>Regular Price <span class="error">*</span></label>
																		</div>
																		<div class="col-sm-4">
																			<div class="input-group">
																				<span class="input-group-prepend">
																					<span class="input-group-text"><?php echo $default_currency; ?></span>
																				</span>
																				<input id="regular_price" name="regular_price" type="number" placeholder="" class="form-control" min="0" value="<?php echo $row->regular_price; ?>">
																			</div>
																			<div class="text-right mt-1 mb-0"><small><input type="checkbox" id="chk_product_outlet_regular_prices" name="chk_product_outlet_regular_prices" <?php if ($row->universal_regular_price == 1){ echo 'checked'; } ?>> <label for="chk_product_outlet_regular_prices">Use this price across all outlets</label></small></div>
																			<div id="div_product_outlet_regular_prices" class="bg-light <?php if ($row->universal_regular_price == 1){ echo 'display-none'; } ?>">
																				<small>
																					<div class="table-responsive">
																						<table class="table table-condensed">
																							<?php foreach ($outlets as $row2): ?>
																								<?php 
																									$outlet_regular_price = 0;

																									foreach ($outlet_products as $row3) {
																										if ($row2->outlet_id == $row3->outlet_id) {
																											$outlet_regular_price = $row3->regular_price;
																										}
																									}
																								?>
																								<tr>
																									<td><?php echo $row2->outlet_name; ?></td>
																									<td><input id="outlet_regular_price_<?php echo $row2->outlet_id; ?>" name="outlet_regular_price_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" class="form-control" min="0" value="<?php echo $outlet_regular_price; ?>"></td>
																								</tr>
																							<?php endforeach; ?>
																						</table>
																					</div>
																				</small>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="form-group mb-2">
																	<div class="row">
																		<div class="col-sm-3">
																			<label>Sale Price <i class="icon-info22 text-primary" data-popup="tooltip" title="Use this when you're running an offer. Please note that this will override the Regular Price" data-placement="top"></i></label>
																		</div>
																		<div class="col-sm-4">
																			<div class="input-group">
																				<span class="input-group-prepend">
																					<span class="input-group-text"><?php echo $default_currency; ?></span>
																				</span>
																				<input id="sale_price" name="sale_price" type="number" placeholder="" class="form-control" min="0" value="<?php echo $row->sale_price; ?>">
																			</div>
																			<div class="text-right mt-1 mb-0"><small><input type="checkbox" id="chk_product_outlet_sale_prices" name="chk_product_outlet_sale_prices" <?php if ($row->universal_sale_price == 1){ echo 'checked'; } ?>> <label for="chk_product_outlet_sale_prices">Use this price across all outlets</label></small></div>
																			<div id="div_product_outlet_sale_prices" class="bg-light <?php if ($row->universal_sale_price == 1){ echo 'display-none'; } ?>">
																				<small>
																					<div class="table-responsive">
																						<table class="table table-condensed">
																							<?php foreach ($outlets as $row2): ?>
																								<?php 
																									$outlet_sale_price = 0;

																									foreach ($outlet_products as $row3) {
																										if ($row2->outlet_id == $row3->outlet_id) {
																											$outlet_sale_price = $row3->sale_price;
																										}
																									}
																								?>
																								<tr>
																									<td><?php echo $row2->outlet_name; ?></td>
																									<td><input id="outlet_sale_price_<?php echo $row2->outlet_id; ?>" name="outlet_sale_price_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" class="form-control" min="0" value="<?php echo $outlet_sale_price; ?>"></td>
																								</tr>
																							<?php endforeach; ?>
																						</table>
																					</div>
																				</small>
																			</div>
																		</div>
																	</div>
																</div>
																<div class="form-group mb-2">
																	<div class="row">
																		<div class="col-sm-3">
																			<label>Minimum Selling Price <i class="icon-info22 text-primary" data-popup="tooltip" title="Leave 0 if none" data-placement="top"></i></label>
																		</div>
																		<div class="col-sm-4">
																			<div class="input-group">
																				<span class="input-group-prepend">
																					<span class="input-group-text"><?php echo $default_currency; ?></span>
																				</span>
																				<input id="minimum_selling_price" name="minimum_selling_price" type="number" placeholder="" class="form-control" min="0" value="<?php echo $row->minimum_selling_price; ?>">
																			</div>
																			<div class="text-right mt-1 mb-0"><small><input type="checkbox" id="chk_product_outlet_minimum_prices" name="chk_product_outlet_minimum_prices" <?php if ($row->universal_minimum_price == 1){ echo 'checked'; } ?>> <label for="chk_product_outlet_minimum_prices">Use this price across all outlets</label></small></div>
																			<div id="div_product_outlet_minimum_prices" class="bg-light <?php if ($row->universal_minimum_price == 1){ echo 'display-none'; } ?>">
																				<small>
																					<div class="table-responsive">
																						<table class="table table-condensed">
																							<?php foreach ($outlets as $row2): ?>
																								<?php 
																									$outlet_minimum_selling_price = 0;

																									foreach ($outlet_products as $row3) {
																										if ($row2->outlet_id == $row3->outlet_id) {
																											$outlet_minimum_selling_price = $row3->minimum_selling_price;
																										}
																									}
																								?>
																								<tr>
																									<td><?php echo $row2->outlet_name; ?></td>
																									<td><input id="outlet_minimum_price_<?php echo $row2->outlet_id; ?>" name="outlet_minimum_price_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" class="form-control" min="0" value="<?php echo $outlet_minimum_selling_price; ?>"></td>
																								</tr>
																							<?php endforeach; ?>
																						</table>
																					</div>
																				</small>
																			</div>

																		</div>
																	</div>
																</div>
															</div>
															<div class="form-group mb-2">
																<div class="row">
																	<div class="col-sm-3">
																		<label class="d-block">Allow Negative Inventory</label>
																	</div>
																	<div class="col-sm-4">
																		<div class="form-check">
																			<label class="form-check-label font-weight-semibold">
																				<input id="negative_inventory" name="negative_inventory" type="checkbox" class="form-check-input" <?php if ($row->negative_inventory == 1){ echo 'checked'; } ?>>
																				Yes <i class="icon-info22 text-primary" data-popup="tooltip" title="This allows you to sell this product even when stock is below zero." data-placement="top"></i>
																			</label>
																		</div>
																	</div>
																</div>
															</div>

														</div>

														<div class="tab-pane fade" id="tab-units">
															<div class="spinner2 display-none" id="units_loader">
											                    <div class="rect1"></div>
											                    <div class="rect2"></div>
											                    <div class="rect3"></div>
											                </div>
															
															<div class="row">
																<div class="col-sm-10">
																	<div id="add_related_units">
																		<?php if ($num_related_units > 0): ?>
																			<div class="row">
																				<div class="col-md-12">
																					<div class="card">
																						<div class="card-header pt-2 pb-0">
																							<p class="font-weight-bold text-uppercase">Related Units</p>
																						</div>
																						<div class="card-body">
																							<div class="row">
																								<div class="col-md-1"><i class="icon-info22 text-primary ml-1" data-popup="popover" title="" data-trigger="hover" data-content="Select checkboxes for the units that apply to activate, then enter the number of base units in this related unit and their corresponding prices."></i></div>
																								<div class="col-md-2">
																									<h6 class="text-grey text-uppercase">&nbsp;</h6>
																								</div>
																								<div class="col-md-3">
																									<h6 class="text-grey text-uppercase"># of Units in</h6>
																								</div>
																								<div class="col-md-3">
																									<h6 class="text-grey text-uppercase">Unit Price</h6>
																								</div>
																								<div class="col-md-3">
																									<h6 class="text-grey text-uppercase">Minimum Selling Price</h6>
																								</div>
																							</div>
																							<?php foreach ($related_units as $row2): ?>
																								<div class="row mb-2">
																									<div class="col-md-1">
																										<input type="hidden" name="chk_ru_unit_id[<?php echo $row2->unit_id; ?>]" class="hid_chk_ru_unit_id" value="off">
																										<input type="checkbox" id="chk_ru_unit_<?php echo $row2->unit_id; ?>" name="chk_ru_unit_id[<?php echo $row2->unit_id; ?>]" value="<?php echo $row2->unit_id; ?>" class="chk_ru_unit_id" <?php if ($row2->product_conversion_factor != null && $row2->product_conversion_factor != ''){ echo 'checked'; } ?>>
																									</div>
																									<div class="col-md-2">
																										<?php echo $row2->unit_name; ?> (<?php echo $row2->unit_code; ?>)
																									</div>
																									<div class="col-md-3">
																										<input name="ru_unit_id[<?php echo $row2->unit_id; ?>]" type="hidden" value="<?php echo $row2->unit_id; ?>">
																										<input name="ru_conversion_factor[<?php echo $row2->unit_id; ?>]" type="number" class="form-control form-control-sm" min="0" value="<?php if ($row2->product_conversion_factor !== null && $row2->product_conversion_factor !== ''){ echo  $row2->product_conversion_factor; } else { echo $row2->conversion_factor; } ?>">
																									</div>
																									<div class="col-md-3">
																										<input name="ru_unit_price[<?php echo $row2->unit_id; ?>]" type="number" class="form-control form-control-sm" min="0" value="<?php echo $row2->unit_price;  ?>">
																									</div>
																									<div class="col-md-3">
																										<input name="ru_unit_minimum_selling_price[<?php echo $row2->unit_id; ?>]" type="number" class="form-control form-control-sm" min="0" value="<?php echo $row2->unit_minimum_selling_price;  ?>">
																									</div>

																									<div class="col-md-3"></div>
																									<div class="col-md-9">
																										<div class="text-right mt-1 mb-0"><small><input type="checkbox" id="chk_related_unit_outlet_unit_prices_<?php echo $row2->unit_id; ?>" name="chk_related_unit_outlet_unit_prices_<?php echo $row2->unit_id; ?>" class="chk_related_unit_outlet_unit_prices" data-unit-id="<?php echo $row2->unit_id; ?>" <?php if ($row2->universal_prices == 1){ echo 'checked'; } ?>> <label for="chk_related_unit_outlet_unit_prices_<?php echo $row2->unit_id; ?>">Use these prices across all outlets</label></small></div>
																										<div id="div_related_unit_outlet_unit_prices_<?php echo $row2->unit_id; ?>" class="bg-light <?php if ($row2->universal_prices == 1){ echo 'display-none'; } ?>">
																											<small>
																												<div class="table-responsive">
																													<table class="table table-condensed">
																														<thead>
																															<tr>
																																<th></th>
																																<th>Unit Price</th>
																																<th>Minimum Selling Price</th>
																															</tr>
																														</thead>
																														<?php foreach ($outlets as $row3): ?>
																															<?php 
																																$related_unit_outlet_unit_price = '0.00';
																																$related_unit_outlet_minimum_selling_price = '0.00';

																																if(!empty($row2->outlet_prices)){
																																	foreach ($row2->outlet_prices as $row4) {
																																		if ($row3->outlet_id == $row4->outlet_id) {
																																			$related_unit_outlet_unit_price = $row4->unit_price;
																																			$related_unit_outlet_minimum_selling_price = $row4->minimum_selling_price;
																																		}
																																	}

																																}
																															?>
																															<tr>
																																<td><?php echo $row3->outlet_name; ?></td>
																																<td><input id="related_unit_outlet_unit_price_<?php echo $row2->unit_id; ?>_<?php echo $row3->outlet_id; ?>" name="related_unit_outlet_unit_price_<?php echo $row2->unit_id; ?>_<?php echo $row3->outlet_id; ?>" type="number" placeholder="" class="form-control" min="0" value="<?php echo $related_unit_outlet_unit_price; ?>"></td>
																																<td><input id="related_unit_outlet_minimum_selling_price_<?php echo $row2->unit_id; ?>_<?php echo $row3->outlet_id; ?>" name="related_unit_outlet_minimum_selling_price_<?php echo $row2->unit_id; ?>_<?php echo $row3->outlet_id; ?>" type="number" placeholder="" class="form-control" min="0" value="<?php echo $related_unit_outlet_minimum_selling_price; ?>"></td>

																															</tr>
																														<?php endforeach; ?>
																													</table>
																												</div>
																											</small>
																										</div>
																									</div>
																								</div>
																							<?php endforeach; ?>
																						</div>
																					</div>
																				</div>
																			</div>
																		<?php endif; ?>
																	</div>
																</div>
															</div>
														</div>

														<div class="tab-pane fade" id="tab-attributes">
															<div class="form-group mb-0">
																<button id="btn_modal_add_product_attribute" type="button" role="button" class="btn btn-xs btn-primary"><i class="icon-plus-circle2"></i> Add Product Attribute</button>
															</div>
															<hr class="mt-2 mb-2">
															<div id="div_product_attributes">
																<?php if ($num_product_attributes > 0): ?>
																	<div class="row">
																		<div class="col-xl-12">
																			<div class="card">
																				<div class="table-responsive">
																					<table class="table text-nowrap">
																						<thead>
																							<tr>
																								<th style="width: 150px">Name</th>
																								<th>Value(s)</th>
																								<th class="text-center" style="width: 20px;">Action</th>
																							</tr>
																						</thead>
																						<tbody>
																							<?php foreach ($product_attributes as $row2): ?>
																								<tr>
																									<td><a href="javascript:;" class="lnk-edit-product-attribute" data-product-attribute-id="<?php echo $row2->product_attribute_id; ?>"><span class="font-weight-bold"><?php echo $row2->product_attribute_name; ?></span></a></td>
																									<td>
																										<?php if(!empty($row2->values)): ?>
																											<?php foreach($row2->values as $row3): ?>
																												<span class="badge badge-pill bg-teal"><?php echo $row3->product_attribute_value; ?></span>
																											<?php endforeach; ?>
																										<?php endif; ?>
																									</td>
																									<td class="text-center">
																										<a href="javascript:;" class="badge badge-info lnk-edit-product-attribute" data-product-attribute-id="<?php echo $row2->product_attribute_id; ?>"><i class="icon-pencil6"></i> Edit</a>
																										<a href="javascript:;" class="badge badge-danger lnk-delete-product-attribute" data-product-attribute-id="<?php echo $row2->product_attribute_id; ?>"><i class="icon-trash-alt"></i> Delete</a>
																									</td>
																								</tr>
																							<?php endforeach; ?>
																						</tbody>
																					</table>
																				</div>
																			</div>
																		</div>
																	</div>
																<?php endif; ?>
															</div>
														</div>

														<div class="tab-pane fade " id="tab-variations">
														    <?php if ($num_product_attributes > 0): ?>
																<div class="form-group mb-0">
																	<button id="btn_modal_add_product_variation" type="button"role="button" class="btn btn-xs btn-primary"><i class="icon-plus-circle2"></i> Add Product Variation</button>
																</div>
																<hr class="mt-2 mb-2">
																<div id="div_product_variations">
																	<?php if ($num_product_variations > 0): ?>
																		<div class="card-group-control card-group-control-right">
																			<?php foreach ($product_variations as $row2): ?>
																				<div class="card mb-0 rounded-bottom-0">
																					<div class="card-header pt-2 pb-2 alpha-grey">
																						<h6 class="card-title">
																							<a data-toggle="collapse" class="text-default font-weight-bold font-size-lg" href="#collapsible-product-variation<?php echo $row2->product_variation_id; ?>">Variation #<?php echo $row2->product_variation_id; ?></a>
																						</h6>

																					</div>

																					<div id="collapsible-product-variation<?php echo $row2->product_variation_id; ?>" class="collapse show">
																						<div class="card-body">
																							<div class="row">
																								<?php if(!empty($row2->attributes)): ?>
																									<?php foreach($row2->attributes as $row3): ?>
																										<div class="col-sm-3">
																                   							<div class="form-group">
																                   								<label><?php echo $row3->product_attribute_name; ?></label>
																                   								<input type="text" readonly id="" class="form-control" value="<?php echo $row3->product_attribute_value; ?>">
																                   							</div>
																                   						</div>
																									<?php endforeach; ?>
																								<?php endif; ?>
																							</div>
																							<hr class="mt-0">
																							<div class="row">
																								<div class="col-sm-2">
																									<?php if($row2->product_variation_image != '' && file_exists("./uploads/product_images/" . $row2->product_variation_image)): ?>
																										<div class="card-img-actions d-inline-block mb-2">
																											<img class="card-img img-fluid" src="<?php echo base_url(); ?>uploads/product_images/<?php echo $row2->product_variation_image; ?>" alt="">
																										</div>
																									<?php else: ?>
																										<div class="card-img-actions d-inline-block mb-2">
																											<img class="card-img img-fluid" src="<?php echo base_url(); ?>assets/fe/img/product-placeholder.jpg" alt="">
																										</div>
																									<?php endif; ?>
																								</div>
																								<div class="col-sm-5">
																									<div class="row mb-2">
																										<div class="col-sm-5">
																											<label>Regular Price</label>
																										</div>
																										<div class="col-sm-7">
																											<input type="number" readonly placeholder="" class="form-control" value="<?php echo $row2->product_variation_regular_price; ?>">
																										</div>
																									</div>
																									<div class="row mb-2">
																										<div class="col-sm-5">
																											<label>Sale Price <i class="icon-info22 text-primary" data-popup="tooltip" title="Use this when you're running an offer. Please note that this will override the Regular Price" data-placement="top"></i></label>
																										</div>
																										<div class="col-sm-7">
																											<input  type="number" readonly placeholder="" class="form-control" value="<?php echo $row2->product_variation_sale_price; ?>">
																										</div>
																									</div>
																									<div class="row mb-2">
																										<div class="col-sm-5">
																											<label>Minimum Selling Price</label>
																										</div>
																										<div class="col-sm-7">
																											<input  type="number" readonly placeholder="" class="form-control" value="<?php echo $row2->product_variation_minimum_selling_price; ?>">
																										</div>
																									</div>
																									<div class="row mb-2">
																										<div class="col-sm-12">
																											<label>Description:</label>
																											<textarea readonly class="form-control" rows="2"><?php echo $row2->product_variation_description; ?></textarea>
																										</div>
																									</div>
																									<div class="row">
																										<div class="col-sm-12">
																											<div class="form-check">
																												<label class="form-check-label">
																													<input type="checkbox" class="form-check-input"  <?php if ($row2->is_enabled == 1){ echo 'checked'; } ?> onclick="return false;">
																													Enabled
																												</label>
																											</div>
																										</div>
																									</div>
																								</div>
																								<div class="col-sm-5">
																									<h5 class="text-primary mb-0">Inventory</h5>
																									<div class="table-responsive">
																										<table class="table">
																											<thead>
																												<tr>
																													<th>Outlet</th>
																													<th>Opening Stock</th>
																													<!-- <th>Available Stock</th> -->
																													<th>Reorder Level</th>
																												</tr>
																											</thead>
																											<tbody>
																												<?php foreach ($outlets as $row3): ?>
																													<?php
																														$cur_opening_stock = 0;
																														$cur_available_stock = 0;
																														$cur_reorder_level = 0;

																														if(!empty($row2->outlet_products)){
																															foreach($row2->outlet_products as $row4){
																																if ($row4->outlet_id == $row3->outlet_id) {
																																	$cur_opening_stock = $row4->opening_stock;
																																	$cur_available_stock = $row4->available_stock;
																																	$cur_reorder_level = $row4->reorder_level;
																																}
																															}
																														}
																													?>
																													<tr>
																														<td><?php echo $row3->outlet_name; ?></td>
																														<td width="200"><?php echo $cur_opening_stock; ?></td>
																														<!-- <td width="100"><?php echo $cur_available_stock; ?></td> -->
																														<td width="200"><?php echo $cur_reorder_level; ?></td>
																													</tr>
																												<?php endforeach; ?>
																											</tbody>
																										</table>
																									</div>
																								</div>
																							</div>
																							<div class="row">
																								<div class="col-sm-12 text-right">
																									<a href="javascript:;" class="badge badge-info font-11 lnk-edit-product-variation" data-product-id="<?php echo $row2->product_id; ?>" data-product-variation-id="<?php echo $row2->product_variation_id; ?>"><i class="icon-pencil6"></i>Edit </a>
																									<a href="javascript:;" class="badge badge-danger font-11 lnk-delete-product-variation" data-product-variation-id="<?php echo $row2->product_variation_id; ?>"><i class="icon-trash-alt"></i> Delete</a>
																								</div>
																							</div>
																						</div>
																					</div>
																				</div>
																			<?php endforeach; ?>
																		</div>
																	<?php endif; ?>
																</div>
															<?php else: ?>
																<div class="alert alert-info alert-styled-right alert-dismissible">
																	Before you can add a product variation you need to add some variation attributes on the <span class="font-weight-bold">Attributes</span> tab.
															    </div>
															<?php endif; ?>
														</div>

														<div class="tab-pane fade" id="tab-linked-products">

														</div>
														<div class="tab-pane fade" id="tab-inventory">
															<div class="table-responsive">
																<table class="table">
																	<thead>
																		<tr>
																			<th>Outlet</th>
																			<th>Opening Stock</th>
																			<!-- <th>Available Stock</th> -->
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
																				<td width="150"><input id="opening_stock_<?php echo $row2->outlet_id; ?>" name="opening_stock_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" value="<?php echo $cur_opening_stock; ?>" class="form-control"></td>
																				<!-- <td width="150"><input id="available_stock_<?php echo $row2->outlet_id; ?>" name="available_stock_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" value="<?php echo $cur_available_stock; ?>" class="form-control" readonly></td> -->
																				<td width="150"><input id="reorder_level_<?php echo $row2->outlet_id; ?>" name="reorder_level_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" value="<?php echo $cur_reorder_level; ?>" class="form-control"></td>
																			</tr>
																		<?php endforeach; ?>
																	</tbody>
																</table>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="card border-top-success rounded-top-0">
											<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
												<h5 class="card-title font-weight-600 text-dark font-size-lg">Product Long Description</h5>			
											</div>							
											<div class="card-body">
												<div class="form-group mb-3">
													<div class="row">
														<div class="col-sm-12">
															<!-- <label>Long Description</label> -->
															<textarea id="product_description" name="product_description" rows="3" cols="3" class="form-control ckeditor"><?php echo $row->product_description; ?></textarea>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="card border-top-success rounded-top-0">
											<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
												<h5 class="card-title font-weight-600 text-dark font-size-lg">SEO</h5>			
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
									<div class="col-md-3">
										<div class="card border-top-success rounded-top-0">
											<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
												<h5 class="card-title font-weight-600 text-dark font-size-lg">Categorize</h5>			
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
										<div class="card border-top-success rounded-top-0">
											<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
												<h5 class="card-title font-weight-600 text-dark font-size-lg">Product Image</h5>			
											</div>
									
											<div class="card-body" id="div_product_main_image">

												<?php if($row->product_image != '' && file_exists("./uploads/product_images/" . $row->product_image)): ?>
													<div class="card-img-actions d-inline-block mb-2">
														<img class="card-img img-fluid" src="<?php echo base_url(); ?>uploads/product_images/<?php echo $row->product_image; ?>" alt="">
													</div>
													<div class="form-group mb-2">
														<div class="row">
															<div class="col-sm-12">
																<button type="button" id="btn_modal_change_product_image" class="btn btn-lg btn-link"><i class="icon-image2 mr-1"></i> Change Product Image</button>
															</div>
														</div>
													</div>
												<?php else: ?>
													<div class="form-group mb-2">
														<div class="row">
															<div class="col-sm-12">
																<button type="button" id="btn_modal_set_product_image" class="btn btn-lg btn-link"><i class="icon-image2 mr-1"></i> Set Product Image</button>
															</div>
														</div>
													</div>
												<?php endif; ?>

											</div>
										</div>
										<div class="card border-top-success rounded-top-0">
											<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
												<h5 class="card-title font-weight-600 text-dark font-size-lg">Product Gallery</h5>			
											</div>
									
											<div class="card-body">
												<div class="form-group mb-2">
													<div class="row">
														<div class="col-sm-12">
															<button type="button" id="btn_modal_add_product_gallery_image" class="btn btn-lg btn-link"><i class="icon-image2 mr-1"></i> Add Product Gallery Image(s)</button>
															<div id="div_product_gallery_images">
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
																							<a role="button" class="list-icons-item lnk_edit_product_gallery_image" data-product-image-id ="<?php echo $poi->product_image_id; ?>" title="Edit Image"><i class="icon-pencil top-0"></i></a>
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
						                                        </div>
						                                    </div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="card border-top-success rounded-top-0">
											<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
												<h5 class="card-title font-weight-600 text-dark font-size-lg">Product Options</h5>			
											</div>
									
											<div class="card-body">
												<div class="form-group mb-2">
													<div class="row">										
														<div class="col-md-4">
															<label class="d-block">Product Status <span class="error">*</span>:</label>
														</div>
														<div class="col-md-8">
															<div class="form-check form-check-inline form-check-right">
																<label class="form-check-label font-weight-semibold">
																	Online
																	<input type="radio" class="form-check-input" id="is_online1" name="is_online" value="1" <?php if ($row->is_online == 1){ echo 'checked'; } ?>
																</label>
															</div>
															<div class="form-check form-check-inline form-check-right">
																<label class="form-check-label font-weight-semibold">
																	Offline
																	<input type="radio" class="form-check-input" id="is_online0" name="is_online" value="0" <?php if ($row->is_online == 0){ echo 'checked'; } ?>>
																</label>
															</div>
														</div>
													</div>
												</div>
												<div class="form-group mb-2">
													<div class="row">
														<div class="col-md-4">
															<label class="d-block">Is Featured <span class="error">*</span>:</label>
														</div>
														<div class="col-md-8">
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
												<div class="form-group mb-2">
													<div class="row">										
														<div class="col-md-4">
															<label class="d-block">Is New Arrival <span class="error">*</span>:</label>
														</div>
														<div class="col-md-8">
															<div class="form-check form-check-inline form-check-right">
																<label class="form-check-label font-weight-semibold">
																	Yes
																	<input type="radio" class="form-check-input" id="is_new_arrival1" name="is_new_arrival" value="1" <?php if ($row->is_new_arrival == 1){ echo 'checked'; } ?>>
																</label>
															</div>
															<div class="form-check form-check-inline form-check-right">
																<label class="form-check-label font-weight-semibold">
																	No
																	<input type="radio" class="form-check-input" id="is_new_arrival0" name="is_new_arrival" value="0"  <?php if ($row->is_new_arrival == 0){ echo 'checked'; } ?>>
																</label>
															</div>
														</div>
													</div>
												</div>
												<div class="form-group mb-2">
													<div class="row">
														<div class="col-md-4">
															<label class="d-block">Is Special Offer <span class="error">*</span>:</label>
														</div>
														<div class="col-md-8">
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
												<div class="form-group mb-2">
													<div class="row">										
														<div class="col-sm-4">
															<label class="d-block">Is Deal of The Week <span class="error">*</span>:</label>
														</div>
														<div class="col-md-8">
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
									
								<?php endforeach; ?>
                   			<?php endif; ?>
								
							<div class="clearfix"></div>
							<div class="col-md-12">
								<hr>
								<div class="text-right">
									<button type="submit" id="btn_save_product_draft" class="btn btn-sm btn-outline-primary border-primary text-primary-400"><i class="icon-file-text2"></i> SAVE DRAFT</button>
									<button type="submit" id="btn_add_product" class="btn btn-sm btn-primary"><i class="icon-checkmark4"></i> PUBLISH PRODUCT</button>
								</div>
							</div>
						</div>
					</form>
				</div>
				<div id="modal_add_product_attribute" class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title text-primary"><i class="icon-plus-circle2"></i> Add Product Attribute</h5>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>

							<form id="frm_add_product_attribute" name="frm_add_product_attribute" method="post" onsubmit="return save_product_attribute();">
								
								<div class="modal-body">

									<div id="div_add_product_attribute_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_add_product_attribute_success" class="alert alert-success display-none font-13"></div>

	                   				<input type="hidden" id="apa_product_id" name="product_id">

									
									<div class="form-group">
										<div class="row">
											<div class="col-sm-3">
												<label>Name <span class="error">*</span></label>
											</div>
											<div class="col-sm-9">
												<input id="add_product_attribute_name" name="product_attribute_name" type="text" placeholder="" class="form-control">
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-3">
												<label>Values <span class="error">*</span></label>
											</div>
											<div class="col-sm-9">
												<input id="add_product_attribute_values" name="product_attribute_values" type="text" class="form-control tagsinput-custom-tag-class" value="" data-fouc>
												<span class="text-muted">Enter attribute values separate by comma (,)</span>
											</div>
										</div>
									</div>
								</div>

								<div class="modal-footer">								
									<button type="submit" id="btn_add_product_attribute" class="btn btn-sm btn-primary"><i class="icon-checkmark4"></i> SAVE</button>
									<button type="button" class="btn  btn-sm btn-danger" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div id="modal_edit_product_attribute" class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title text-primary"><i class="icon-plus-circle2"></i> Edit Product Attribute</h5>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>

							<form id="frm_edit_product_attribute" name="frm_edit_product_attribute" method="post" onsubmit="return update_product_attribute();">
								
								<div class="modal-body">

									<div id="div_edit_product_attribute_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_edit_product_attribute_success" class="alert alert-success display-none font-13"></div>

	                   				<input type="hidden" id="epa_product_id" name="product_id">
	                   				<input type="hidden" id="epa_product_attribute_id" name="product_attribute_id">
									
									<div class="form-group">
										<div class="row">
											<div class="col-sm-3">
												<label>Name <span class="error">*</span></label>
											</div>
											<div class="col-sm-9">
												<input id="edit_product_attribute_name" name="product_attribute_name" type="text" placeholder="" class="form-control">
											</div>
										</div>
									</div>
									<div class="form-group">
										<div class="row">
											<div class="col-sm-3">
												<label>Values <span class="error">*</span></label>
											</div>
											<div class="col-sm-9">
												<input id="edit_product_attribute_values" name="product_attribute_values" type="text" class="form-control tagsinput-custom-tag-class" value="" data-fouc>
												<span class="text-muted">Enter attribute values separate by comma (,)</span>
											</div>
										</div>
									</div>
								</div>

								<div class="modal-footer">								
									<button type="submit" id="btn_edit_product_attribute" class="btn btn-sm btn-primary"><i class="icon-checkmark4"></i> UPDATE</button>
									<button type="button" class="btn  btn-sm btn-danger" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
								</div>
							</form>
						</div>
					</div>
				</div>
				<div id="modal_add_product_variation" class="modal fade">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title text-primary"><i class="icon-plus-circle2"></i> Add Product Variation</h5>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>
							<div id="div_variation_add_form">

							</div>
						</div>
					</div>
				</div>
				<div id="modal_edit_product_variation" class="modal fade">
					<div class="modal-dialog modal-lg">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title text-primary"><i class="icon-plus-circle2"></i> Edit Product Variation</h5>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>
							<div id="div_variation_edit_form">

							</div>
						</div>
					</div>
				</div>

				<div id="modal_set_product_image" class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title text-primary"><i class="icon-plus-circle2"></i> Set Product Image</h5>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>

							<form id="frm_set_product_image" name="frm_set_product_image" method="post" onsubmit="return upload_set_product_image();">
								
								<div class="modal-body">
									<div class="block-inner text-secondary">
                                        <h6 class="heading-hr"> <small class="display-block">Browse for the image then click Save</small></h6>
                                    </div>

									<div id="div_set_product_image_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_set_product_image_success" class="alert alert-success display-none font-13"></div>

	                   				<input type="hidden" id="spi_product_id" name="product_id">

									<div class="form-group">
										<input id="spi_product_image" name="product_image" type="file" accept="application/image" class="form-control">
										<span class="form-text text-muted">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
									</div>
								</div>

								<div class="modal-footer">								
									<button type="submit" id="btn_set_product_image" class="btn btn-sm btn-primary"><i class="icon-checkmark4"></i> SAVE</button>
									<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div id="modal_add_product_gallery_image" class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title text-primary"><i class="icon-plus-circle2"></i> Add Product Gallery Image(s)</h5>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>

							<form id="frm_add_product_gallery_image" name="frm_add_product_gallery_image" method="post" onsubmit="return upload_add_product_gallery_image();">
								
								<div class="modal-body">
									<div class="block-inner text-secondary">
                                        <h6 class="heading-hr"> <small class="display-block">Browse for the image then click Save</small></h6>
                                    </div>

									<div id="div_add_product_gallery_image_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_add_product_gallery_image_success" class="alert alert-success display-none font-13"></div>

	                   				<input type="hidden" id="apgi_product_id" name="product_id">

									<div class="form-group">
										<input id="apgi_product_gallery_image" name="product_gallery_image[]" multiple type="file" accept="application/image" class="form-control">
										<span class="form-text text-muted">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
									</div>
								</div>

								<div class="modal-footer">								
									<button type="submit" id="btn_add_product_gallery_image" class="btn btn-sm btn-primary"><i class="icon-checkmark4"></i> SAVE</button>
									<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div id="modal_edit_product_gallery_image" class="modal fade">
					<div class="modal-dialog">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title text-primary"><i class="icon-pencil"></i> Edit Product Image</h5>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>

							<form id="frm_edit_product_gallery_image" name="frm_edit_product_gallery_image" method="post" onsubmit="return upload_edit_product_gallery_image();">
								
								<div class="modal-body">
									<div class="block-inner text-secondary">
                                        <h6 class="heading-hr"> <small class="display-block">Browse for the image then click Save</small></h6>
                                    </div>

									<div id="div_edit_product_gallery_image_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_edit_product_gallery_image_success" class="alert alert-success display-none font-13"></div>

	                   				<input type="hidden" id="epgi_product_image_id" name="product_image_id">

									<div class="form-group">
										<!-- <label>&nbsp;</label> -->
										<input id="epgi_product_gallery_image" name="product_gallery_image" type="file" accept="application/image" class="form-control">
										<span class="form-text text-muted">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
									</div>
								</div> 

								<div class="modal-footer">								
									<button type="submit" id="btn_edit_product_gallery_image" class="btn btn-sm btn-primary"><i class="icon-checkmark4"></i> SAVE</button>
									<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<script>
					$(document).ready(function() {
						setInterval(function() {
                			autosave_product();
            			}, 10000);
					});
				</script>
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
										<a href="<?php echo base_url();?>be/products" class="btn btn-sm btn-primary float-right"><i class="icon-arrow-left15"></i> Back to Products</a>
									</h5>
								</div>
							</div>
						</div>

						<form id="frm_edit_product" name="frm_edit_product" method="post" onsubmit="return update_product();" autocomplete="false">

							<div class="row">

								<div class="col-md-9">
									<div id="div_edit_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_edit_success" class="alert alert-success display-none font-13"></div>                   				

	                   				<input type="hidden" id="product_id" name="product_id" value="<?php echo $row->product_id; ?>">

									<div class="form-group mb-2">
										<div class="row">
											<div class="col-sm-12">
												<label class="font-size-lg">Product Name <span class="error">*</span></label>
												<input id="product_name" name="product_name" type="text" placeholder="" class="form-control form-control-lg font-weight-bold" value="<?php echo $row->product_name; ?>">
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
									<div class="card border-top-success rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<div class="row">
												<h5 class="card-title font-weight-600 text-dark font-size-lg">Product Data &mdash;&nbsp;</h5>
												<h6 class="font-weight-bold font-size-lg mb-0">
													<select id="product_type" name="product_type" class="form-control select-basic" data-placeholder="Select Product Type" style="min-width: 200px;" data-fouc>
														<option value="Simple" <?php if ($row->product_type == 'Simple'){ echo 'selected'; } ?>>Simple Product</option>
														<option value="Variable" <?php if ($row->product_type == 'Variable'){ echo 'selected'; } ?>>Variable Product</option>
													</select>
												</h6>
											</div>	
										</div>
								
										<div class="card-body">
											<div class="d-md-flex">
												<ul class="nav nav-tabs nav-tabs-vertical flex-column mr-md-3 wmin-md-200 mb-md-0 alpha-grey">
													<li class="nav-item"><a href="#tab-general" class="nav-link active" data-toggle="tab"><i class="icon-wrench2 mr-2"></i> General</a></li>
													<li class="nav-item"><a href="#tab-units" class="nav-link" data-toggle="tab"><i class="icon-hour-glass mr-2"></i> Units of Measure</a></li>
													<li class="nav-item"><a href="#tab-attributes" class="nav-link" data-toggle="tab"><i class="icon-newspaper mr-2"></i> Attributes</a></li>
													<li class="nav-item <?php if ($row->product_type != 'Variable'){ echo 'display-none'; } ?>" id="lnk-tab-variations"><a href="#tab-variations" class="nav-link" data-toggle="tab"><i class="icon-tree7 mr-2"></i> Variations</a></li>
													<!-- <li class="nav-item"><a href="#tab-linked-products" class="nav-link" data-toggle="tab"><i class="icon-link2 mr-2"></i> Linked Products</a></li> -->
													<li class="nav-item <?php if ($row->product_type == 'Variable'){ echo 'display-none'; } ?>" id="lnk-tab-inventory"><a href="#tab-inventory" class="nav-link" data-toggle="tab"><i class="icon-cube3 mr-2"></i> Inventory</a></li>
												</ul>

												<div class="tab-content w-100">
													<div class="tab-pane fade show active" id="tab-general">
														<div class="form-group mb-2">
															<div class="row">
																<div class="col-sm-3">
																	<label>Product Barcode</label>
																</div>
																<div class="col-sm-5">
																	<input id="product_barcode" name="product_barcode" type="text" placeholder="" class="form-control" value="<?php echo $row->product_barcode; ?>">
																</div>
															</div>
														</div>
														<div class="form-group mb-2">
															<div class="row">
																<div class="col-sm-3">
																	<label>Tax Rate <span class="error">*</span></label>
																</div>
																<div class="col-sm-5">
																	<select id="tax_rate_id" name="tax_rate_id" class="form-control form-control-select2" data-placeholder="Select Tax Rate" data-fouc>
																		<option value="">Select Tax Rate</option>
																		<?php foreach ($tax_rates as $row2): ?>
																			<option value="<?php echo $row2->tax_rate_id; ?>"  <?php if ($row->tax_rate_id == $row2->tax_rate_id){ echo 'selected'; } ?>><?php echo $row2->tax_rate_name; ?></option>
																		<?php endforeach; ?>
																	</select>
																</div>
															</div>
														</div>
														<div class="form-group mb-3">
															<div class="row">
																<div class="col-sm-3">
																	<label class="d-block">Base Unit of Measure <span class="error">*</span> <i class="icon-info22 text-primary" data-popup="popover" title="" data-trigger="hover" data-content="The base unit of measure should be the smallest increment used to track the item. e.g. If you buy a product in bags of 100 but use one or two at a time, you should select a base unit of 'each' instead of '1 bag of 100'"></i></label>
																</div>
																<div class="col-sm-5">
																	<select id="edit_product_unit_id" name="unit_id" class="form-control form-control-select2" data-placeholder="Select Base Unit" data-fouc>
																		<option value="">Select Base Unit</option>
																		<?php foreach ($unit_types as $row3): ?>
																			<optgroup label="<?php echo $row3->unit_type_name; ?> <?php if ($row3->unit_type_description !== ''){ echo '(' . $row3->unit_type_description . ')'; } ?>">
																				<?php foreach ($units as $row2): ?>
																					<?php if ($row2->unit_type_id == $row3->unit_type_id): ?>
																						<option value="<?php echo $row2->unit_id; ?>" <?php if ($row->unit_id == $row2->unit_id){ echo 'selected'; } ?>><?php echo $row2->unit_name; ?> <?php echo '(' . $row2->unit_code . ')'; ?></option>
																					<?php endif; ?>
																				<?php endforeach; ?>
																			</optgroup>
																		<?php endforeach; ?>
																	</select>
																</div>
															</div>															
														</div>
														<div id="div_simple_product_prices" class="<?php if ($row->product_type == 'Variable'){ echo 'display-none'; } ?>">
															<div class="form-group mb-2">
																<div class="row">
																	<div class="col-sm-3">
																		<label>Regular Price <span class="error">*</span></label>
																	</div>
																	<div class="col-sm-5">
																		<div class="input-group">
																			<span class="input-group-prepend">
																				<span class="input-group-text"><?php echo $default_currency; ?></span>
																			</span>
																			<input id="regular_price" name="regular_price" type="number" placeholder="" class="form-control" min="0" value="<?php echo $row->regular_price; ?>">
																		</div>
																		<div class="text-right mt-1 mb-0"><small><input type="checkbox" id="chk_product_outlet_regular_prices" name="chk_product_outlet_regular_prices" <?php if ($row->universal_regular_price == 1){ echo 'checked'; } ?>> <label for="chk_product_outlet_regular_prices">Use this price across all outlets</label></small></div>
																		<div id="div_product_outlet_regular_prices" class="bg-light <?php if ($row->universal_regular_price == 1){ echo 'display-none'; } ?>">
																			<small>
																				<div class="table-responsive">
																					<table class="table table-condensed">
																						<?php foreach ($outlets as $row2): ?>
																							<?php 
																								$outlet_regular_price = 0;

																								foreach ($outlet_products as $row3) {
																									if ($row2->outlet_id == $row3->outlet_id) {
																										$outlet_regular_price = $row3->regular_price;
																									}
																								}
																							?>
																							<tr>
																								<td><?php echo $row2->outlet_name; ?></td>
																								<td><input id="outlet_regular_price_<?php echo $row2->outlet_id; ?>" name="outlet_regular_price_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" class="form-control" min="0" value="<?php echo $outlet_regular_price; ?>"></td>
																							</tr>
																						<?php endforeach; ?>
																					</table>
																				</div>
																			</small>
																		</div>

																	</div>
																</div>
															</div>
															<div class="form-group mb-2">
																<div class="row">
																	<div class="col-sm-3">
																		<label>Sale Price <i class="icon-info22 text-primary" data-popup="tooltip" title="Use this when you're running an offer. Please note that this will override the Regular Price" data-placement="top"></i></label>
																	</div>
																	<div class="col-sm-5">
																		<div class="input-group">
																			<span class="input-group-prepend">
																				<span class="input-group-text"><?php echo $default_currency; ?></span>
																			</span>
																			<input id="sale_price" name="sale_price" type="number" placeholder="" class="form-control" min="0" value="<?php echo $row->sale_price; ?>">
																		</div>
																		<div class="text-right mt-1 mb-0"><small><input type="checkbox" id="chk_product_outlet_sale_prices" name="chk_product_outlet_sale_prices" <?php if ($row->universal_sale_price == 1){ echo 'checked'; } ?>> <label for="chk_product_outlet_sale_prices">Use this price across all outlets</label></small></div>
																		<div id="div_product_outlet_sale_prices" class="bg-light <?php if ($row->universal_sale_price == 1){ echo 'display-none'; } ?>">
																			<small>
																				<div class="table-responsive">
																					<table class="table table-condensed">
																						<?php foreach ($outlets as $row2): ?>
																							<?php 
																								$outlet_sale_price = 0;

																								foreach ($outlet_products as $row3) {
																									if ($row2->outlet_id == $row3->outlet_id) {
																										$outlet_sale_price = $row3->sale_price;
																									}
																								}
																							?>
																							<tr>
																								<td><?php echo $row2->outlet_name; ?></td>
																								<td><input id="outlet_sale_price_<?php echo $row2->outlet_id; ?>" name="outlet_sale_price_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" class="form-control" min="0" value="<?php echo $outlet_sale_price; ?>"></td>
																							</tr>
																						<?php endforeach; ?>
																					</table>
																				</div>
																			</small>
																		</div>
																	</div>
																</div>
															</div>
															<div class="form-group mb-2">
																<div class="row">
																	<div class="col-sm-3">
																		<label>Minimum Selling Price <i class="icon-info22 text-primary" data-popup="tooltip" title="Leave 0 if none" data-placement="top"></i></label>
																	</div>
																	<div class="col-sm-5">
																		<div class="input-group">
																			<span class="input-group-prepend">
																				<span class="input-group-text"><?php echo $default_currency; ?></span>
																			</span>
																			<input id="minimum_selling_price" name="minimum_selling_price" type="number" placeholder="" class="form-control" min="0" value="<?php echo $row->minimum_selling_price; ?>">
																		</div>
																		<div class="text-right mt-1 mb-0"><small><input type="checkbox" id="chk_product_outlet_minimum_prices" name="chk_product_outlet_minimum_prices" <?php if ($row->universal_minimum_price == 1){ echo 'checked'; } ?>> <label for="chk_product_outlet_minimum_prices">Use this price across all outlets</label></small></div>
																		<div id="div_product_outlet_minimum_prices" class="bg-light <?php if ($row->universal_minimum_price == 1){ echo 'display-none'; } ?>">
																			<small>
																				<div class="table-responsive">
																					<table class="table table-condensed">
																						<?php foreach ($outlets as $row2): ?>
																							<?php 
																								$outlet_minimum_selling_price = 0;

																								foreach ($outlet_products as $row3) {
																									if ($row2->outlet_id == $row3->outlet_id) {
																										$outlet_minimum_selling_price = $row3->minimum_selling_price;
																									}
																								}
																							?>
																							<tr>
																								<td><?php echo $row2->outlet_name; ?></td>
																								<td><input id="outlet_minimum_price_<?php echo $row2->outlet_id; ?>" name="outlet_minimum_price_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" class="form-control" min="0" value="<?php echo $outlet_minimum_selling_price; ?>"></td>
																							</tr>
																						<?php endforeach; ?>
																					</table>
																				</div>
																			</small>
																		</div>

																	</div>
																</div>
															</div>
														</div>
														<div class="form-group mb-2">
															<div class="row">
																<div class="col-sm-3">
																	<label class="d-block">Allow Negative Inventory</label>
																</div>
																<div class="col-sm-5">
																	<div class="form-check">
																		<label class="form-check-label font-weight-semibold">
																			<input id="negative_inventory" name="negative_inventory" type="checkbox" class="form-check-input" <?php if ($row->negative_inventory == 1){ echo 'checked'; } ?>>
																			Yes <i class="icon-info22 text-primary" data-popup="tooltip" title="This allows you to sell this product even when stock is below zero." data-placement="top"></i>
																		</label>
																	</div>
																</div>
															</div>
														</div>

													</div>

													<div class="tab-pane fade" id="tab-units">
														<div class="spinner2 display-none" id="units_loader">
										                    <div class="rect1"></div>
										                    <div class="rect2"></div>
										                    <div class="rect3"></div>
										                </div>
														
														<div class="row">
															<div class="col-sm-10">
																<div id="add_related_units">
																	<?php if ($num_related_units > 0): ?>
																		<div class="row">
																			<div class="col-md-12">
																				<div class="card">
																					<div class="card-header pt-2 pb-0">
																						<p class="font-weight-bold text-uppercase">Related Units</p>
																					</div>
																					<div class="card-body">
																						<div class="row">
																							<div class="col-md-1"><i class="icon-info22 text-primary ml-1" data-popup="popover" title="" data-trigger="hover" data-content="Select checkboxes for the units that apply to activate, then enter the number of base units in this related unit and their corresponding prices."></i></div>
																							<div class="col-md-2">
																								<h6 class="text-grey text-uppercase">&nbsp;</h6>
																							</div>
																							<div class="col-md-3">
																								<h6 class="text-grey text-uppercase"># of Units in</h6>
																							</div>
																							<div class="col-md-3">
																								<h6 class="text-grey text-uppercase">Unit Price</h6>
																							</div>
																							<div class="col-md-3">
																								<h6 class="text-grey text-uppercase">Minimum Selling Price</h6>
																							</div>
																						</div>
																						<?php foreach ($related_units as $row2): ?>
																							<div class="row mb-2">
																								<div class="col-md-1">
																									<input type="hidden" name="chk_ru_unit_id[<?php echo $row2->unit_id; ?>]" class="hid_chk_ru_unit_id" value="off">
																									<input type="checkbox" id="chk_ru_unit_<?php echo $row2->unit_id; ?>" name="chk_ru_unit_id[<?php echo $row2->unit_id; ?>]" class="chk_ru_unit_id" <?php if ($row2->product_conversion_factor != null && $row2->product_conversion_factor != ''){ echo 'checked'; } ?>>
																								</div>
																								<div class="col-md-2">
																									<?php echo $row2->unit_name; ?> (<?php echo $row2->unit_code; ?>)
																								</div>
																								<div class="col-md-3">
																									<input name="ru_unit_id[<?php echo $row2->unit_id; ?>]" type="hidden" value="<?php echo $row2->unit_id; ?>">
																									<input name="ru_conversion_factor[<?php echo $row2->unit_id; ?>]" type="number" class="form-control form-control-sm" min="0" value="<?php if ($row2->product_conversion_factor !== null && $row2->product_conversion_factor !== ''){ echo  $row2->product_conversion_factor; } else { echo $row2->conversion_factor; } ?>">
																								</div>
																								<div class="col-md-3">
																									<input name="ru_unit_price[<?php echo $row2->unit_id; ?>]" type="number" class="form-control form-control-sm" min="0" value="<?php echo $row2->unit_price;  ?>">
																								</div>
																								<div class="col-md-3">
																									<input name="ru_unit_minimum_selling_price[<?php echo $row2->unit_id; ?>]" type="number" class="form-control form-control-sm" min="0" value="<?php echo $row2->unit_minimum_selling_price;  ?>">
																								</div>
																								<div class="col-md-3"></div>
																								<div class="col-md-9">
																									<div class="text-right mt-1 mb-0"><small><input type="checkbox" id="chk_related_unit_outlet_unit_prices_<?php echo $row2->unit_id; ?>" name="chk_related_unit_outlet_unit_prices_<?php echo $row2->unit_id; ?>" class="chk_related_unit_outlet_unit_prices" data-unit-id="<?php echo $row2->unit_id; ?>" <?php if ($row2->universal_prices == 1){ echo 'checked'; } ?>> <label for="chk_related_unit_outlet_unit_prices_<?php echo $row2->unit_id; ?>">Use these prices across all outlets</label></small></div>
																									<div id="div_related_unit_outlet_unit_prices_<?php echo $row2->unit_id; ?>" class="bg-light <?php if ($row2->universal_prices == 1){ echo 'display-none'; } ?>">
																										<small>
																											<div class="table-responsive">
																												<table class="table table-condensed">
																													<thead>
																														<tr>
																															<th></th>
																															<th>Unit Price</th>
																															<th>Minimum Selling Price</th>
																														</tr>
																													</thead>
																													<?php foreach ($outlets as $row3): ?>
																														<?php 
																															$related_unit_outlet_unit_price = '0.00';
																															$related_unit_outlet_minimum_selling_price = '0.00';

																															if(!empty($row2->outlet_prices)){
																																foreach ($row2->outlet_prices as $row4) {
																																	if ($row3->outlet_id == $row4->outlet_id) {
																																		$related_unit_outlet_unit_price = $row4->unit_price;
																																		$related_unit_outlet_minimum_selling_price = $row4->minimum_selling_price;
																																	}
																																}

																															}
																														?>
																														<tr>
																															<td><?php echo $row3->outlet_name; ?></td>
																															<td><input id="related_unit_outlet_unit_price_<?php echo $row2->unit_id; ?>_<?php echo $row3->outlet_id; ?>" name="related_unit_outlet_unit_price_<?php echo $row2->unit_id; ?>_<?php echo $row3->outlet_id; ?>" type="number" placeholder="" class="form-control" min="0" value="<?php echo $related_unit_outlet_unit_price; ?>"></td>
																															<td><input id="related_unit_outlet_minimum_selling_price_<?php echo $row2->unit_id; ?>_<?php echo $row3->outlet_id; ?>" name="related_unit_outlet_minimum_selling_price_<?php echo $row2->unit_id; ?>_<?php echo $row3->outlet_id; ?>" type="number" placeholder="" class="form-control" min="0" value="<?php echo $related_unit_outlet_minimum_selling_price; ?>"></td>

																														</tr>
																													<?php endforeach; ?>
																												</table>
																											</div>
																										</small>
																									</div>
																								</div>

																							</div>
																						<?php endforeach; ?>
																					</div>
																				</div>
																			</div>
																		</div>
																	<?php endif; ?>
																</div>
															</div>
														</div>
													</div>

													<div class="tab-pane fade" id="tab-attributes">
														<div class="form-group mb-0">
															<button id="btn_modal_edit_add_product_attribute" type="button" role="button" class="btn btn-xs btn-primary"><i class="icon-plus-circle2"></i> Add Product Attribute</button>
														</div>
														<hr class="mt-2 mb-2">
														<div id="div_product_attributes">
															<?php if ($num_product_attributes > 0): ?>
																<div class="row">
																	<div class="col-xl-12">
																		<div class="card">
																			<div class="table-responsive">
																				<table class="table text-nowrap">
																					<thead>
																						<tr>
																							<th style="width: 150px">Name</th>
																							<th>Value(s)</th>
																							<th class="text-center" style="width: 20px;">Action</th>
																						</tr>
																					</thead>
																					<tbody>
																						<?php foreach ($product_attributes as $row2): ?>
																							<tr>
																								<td><a href="javascript:;" class="lnk-edit-product-attribute" data-product-attribute-id="<?php echo $row2->product_attribute_id; ?>"><span class="font-weight-bold"><?php echo $row2->product_attribute_name; ?></span></a></td>
																								<td>
																									<?php if(!empty($row2->values)): ?>
																										<?php foreach($row2->values as $row3): ?>
																											<span class="badge badge-pill bg-teal"><?php echo $row3->product_attribute_value; ?></span>
																										<?php endforeach; ?>
																									<?php endif; ?>
																								</td>
																								<td class="text-center">
																									<a href="javascript:;" class="badge badge-info lnk-edit-product-attribute" data-product-attribute-id="<?php echo $row2->product_attribute_id; ?>"><i class="icon-pencil6"></i> Edit</a>
																									<a href="javascript:;" class="badge badge-danger lnk-delete-product-attribute" data-product-attribute-id="<?php echo $row2->product_attribute_id; ?>"><i class="icon-trash-alt"></i> Delete</a>
																								</td>
																							</tr>
																						<?php endforeach; ?>
																					</tbody>
																				</table>
																			</div>
																		</div>
																	</div>
																</div>
															<?php endif; ?>
														</div>
													</div>

													<div class="tab-pane fade " id="tab-variations">
													    <?php if ($num_product_attributes > 0): ?>
															<div class="form-group mb-0">
																<button id="btn_modal_edit_add_product_variation" type="button"role="button" class="btn btn-xs btn-primary"><i class="icon-plus-circle2"></i> Add Product Variation</button>
															</div>
															<hr class="mt-2 mb-2">
															<div id="div_product_variations">
																<?php if ($num_product_variations > 0): ?>
																	<div class="card-group-control card-group-control-right">
																		<?php foreach ($product_variations as $row2): ?>
																			<div class="card mb-0 rounded-bottom-0">
																				<div class="card-header pt-2 pb-2 alpha-grey">
																					<h6 class="card-title">
																						<a data-toggle="collapse" class="text-default font-weight-bold font-size-lg" href="#collapsible-product-variation<?php echo $row2->product_variation_id; ?>">Variation #<?php echo $row2->product_variation_id; ?></a>
																					</h6>

																				</div>

																				<div id="collapsible-product-variation<?php echo $row2->product_variation_id; ?>" class="collapse show">
																					<div class="card-body">
																						<div class="row">
																							<?php if(!empty($row2->attributes)): ?>
																								<?php foreach($row2->attributes as $row3): ?>
																									<div class="col-sm-3">
															                   							<div class="form-group">
															                   								<label><?php echo $row3->product_attribute_name; ?></label>
															                   								<input type="text" readonly id="" class="form-control" value="<?php echo $row3->product_attribute_value; ?>">
															                   							</div>
															                   						</div>
																								<?php endforeach; ?>
																							<?php endif; ?>
																						</div>
																						<hr class="mt-0">
																						<div class="row">
																							<div class="col-sm-2">
																								<?php if($row2->product_variation_image != '' && file_exists("./uploads/product_images/" . $row2->product_variation_image)): ?>
																									<div class="card-img-actions d-inline-block mb-2">
																										<img class="card-img img-fluid" src="<?php echo base_url(); ?>uploads/product_images/<?php echo $row2->product_variation_image; ?>" alt="">
																									</div>
																								<?php else: ?>
																									<div class="card-img-actions d-inline-block mb-2">
																										<img class="card-img img-fluid" src="<?php echo base_url(); ?>assets/fe/img/product-placeholder.jpg" alt="">
																									</div>
																								<?php endif; ?>
																							</div>
																							<div class="col-sm-10">
																								<div class="row mb-3">
																									<div class="col-sm-4">
																										<label>Regular Price</label>
																										<input type="number" readonly placeholder="" class="form-control" value="<?php echo $row2->product_variation_regular_price; ?>">
																									</div>
																									<div class="col-sm-4">
																										<label>Sale Price <i class="icon-info22 text-primary" data-popup="tooltip" title="Use this when you're running an offer. Please note that this will override the Regular Price" data-placement="top"></i></label>
																										<input  type="number" readonly placeholder="" class="form-control" value="<?php echo $row2->product_variation_sale_price; ?>">
																									</div>
																									<div class="col-sm-4">
																										<label>Minimum Selling Price</label>
																										<input  type="number" readonly placeholder="" class="form-control" value="<?php echo $row2->product_variation_minimum_selling_price; ?>">
																									</div>
																								</div>
																								<div class="row mb-2">
																									<div class="col-sm-4">
																										<div class="form-check">
																											<label class="form-check-label">
																												<input type="checkbox" class="form-check-input"  <?php if ($row2->product_variation_universal_prices == 1){ echo 'checked'; } ?> onclick="return false;">
																												Use these prices across all outlets
																											</label>
																										</div>
																										<div class="form-check">
																											<label class="form-check-label">
																												<input type="checkbox" class="form-check-input"  <?php if ($row2->is_enabled == 1){ echo 'checked'; } ?> onclick="return false;">
																												Enabled
																											</label>
																										</div>
																									</div>
																									<div class="col-sm-8">
																										<label>Description:</label>
																										<textarea readonly class="form-control" rows="2"><?php echo $row2->product_variation_description; ?></textarea>
																									</div>
																								</div>
																								
																							</div>
																							<hr>
																							<div class="col-sm-12">
																								<h5 class="text-primary mb-0">Inventory</h5>
																								<div class="table-responsive">
																									<table class="table">
																										<thead>
																											<tr>
																												<th>Outlet</th>
																												<th>Opening Stock</th>
																												<th>Reorder Level</th>
																												<th>Regular Price</th>
																												<th>Sale Price</th>
																												<th>Minimum Selling Price</th>
																											</tr>
																										</thead>
																										<tbody>
																											<?php foreach ($outlets as $row3): ?>
																												<?php
																													$cur_opening_stock = 0;
																													$cur_available_stock = 0;
																													$cur_reorder_level = 0;
																													$cur_regular_price = 0;
																													$cur_sale_price = 0;
																													$cur_minimum_selling_price = 0;

																													if(!empty($row2->outlet_products)){
																														foreach($row2->outlet_products as $row4){
																															if ($row4->outlet_id == $row3->outlet_id) {
																																$cur_opening_stock = $row4->opening_stock;
																																$cur_available_stock = $row4->available_stock;
																																$cur_reorder_level = $row4->reorder_level;
																																$cur_regular_price = $row4->regular_price;
																																$cur_sale_price = $row4->sale_price;
																																$cur_minimum_selling_price = $row4->minimum_selling_price;
																															}
																														}
																													}
																												?>
																												<tr>
																													<td><?php echo $row3->outlet_name; ?></td>
																													<td><?php echo $cur_opening_stock; ?></td>
																													<td><?php echo $cur_reorder_level; ?></td>
																													<td><?php echo $cur_regular_price; ?></td>
																													<td><?php echo $cur_sale_price; ?></td>
																													<td><?php echo $cur_minimum_selling_price; ?></td>
																												</tr>
																											<?php endforeach; ?>
																										</tbody>
																									</table>
																								</div>
																							</div>
																						</div>
																						<div class="row">
																							<div class="col-sm-12 text-right">
																								<a href="javascript:;" class="badge badge-info font-11 lnk-edit-product-variation" data-product-id="<?php echo $row2->product_id; ?>" data-product-variation-id="<?php echo $row2->product_variation_id; ?>"><i class="icon-pencil6"></i>Edit </a>
																								<a href="javascript:;" class="badge badge-danger font-11 lnk-delete-product-variation" data-product-variation-id="<?php echo $row2->product_variation_id; ?>"><i class="icon-trash-alt"></i> Delete</a>
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																		<?php endforeach; ?>
																	</div>
																<?php endif; ?>
															</div>
														<?php else: ?>
															<div class="alert alert-info alert-styled-right alert-dismissible">
																Before you can add a product variation you need to add some variation attributes on the <span class="font-weight-bold">Attributes</span> tab.
														    </div>
														<?php endif; ?>
													</div>

													<div class="tab-pane fade" id="tab-linked-products">

													</div>
													<div class="tab-pane fade" id="tab-inventory">
														<div class="table-responsive">
															<table class="table">
																<thead>
																	<tr>
																		<th>Outlet</th>
																		<th>Opening Stock</th>
																		<!-- <th>Available Stock</th> -->
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
																			<td width="150"><input id="opening_stock_<?php echo $row2->outlet_id; ?>" name="opening_stock_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" value="<?php echo $cur_opening_stock; ?>" class="form-control"></td>
																			<!-- <td width="150"><input id="available_stock_<?php echo $row2->outlet_id; ?>" name="available_stock_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" value="<?php echo $cur_available_stock; ?>" class="form-control" readonly></td> -->
																			<td width="150"><input id="reorder_level_<?php echo $row2->outlet_id; ?>" name="reorder_level_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" value="<?php echo $cur_reorder_level; ?>" class="form-control"></td>
																		</tr>
																	<?php endforeach; ?>
																</tbody>
															</table>
														</div>
													</div>

												</div>
											</div>
										</div>
									</div>
									<div class="card border-top-success rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h5 class="card-title font-weight-600 text-dark font-size-lg">Product Long Description</h5>			
										</div>							
										<div class="card-body">
											<div class="form-group mb-3">
												<div class="row">
													<div class="col-sm-12">
														<!-- <label>Long Description</label> -->
														<textarea id="product_description" name="product_description" rows="3" cols="3" class="form-control ckeditor"><?php echo $row->product_description; ?></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card border-top-success rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h5 class="card-title font-weight-600 text-dark font-size-lg">SEO</h5>			
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
								<div class="col-md-3">
									<div class="card border-top-success rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h5 class="card-title font-weight-600 text-dark font-size-lg">Categorize</h5>			
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
									<div class="card border-top-success rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h5 class="card-title font-weight-600 text-dark font-size-lg">Product Image</h5>			
										</div>
								
										<div class="card-body" id="div_product_main_image">

											<?php if($row->product_image != '' && file_exists("./uploads/product_images/" . $row->product_image)): ?>
												<div class="card-img-actions d-inline-block mb-2">
													<img class="card-img img-fluid" src="<?php echo base_url(); ?>uploads/product_images/<?php echo $row->product_image; ?>" alt="">
												</div>
												<div class="form-group mb-2">
													<div class="row">
														<div class="col-sm-12">
															<button type="button" id="btn_modal_change_product_image" class="btn btn-lg btn-link"><i class="icon-image2 mr-1"></i> Change Product Image</button>
														</div>
													</div>
												</div>
											<?php else: ?>
												<div class="form-group mb-2">
													<div class="row">
														<div class="col-sm-12">
															<button type="button" id="btn_modal_edit_set_product_image" class="btn btn-lg btn-link"><i class="icon-image2 mr-1"></i> Set Product Image</button>
														</div>
													</div>
												</div>
											<?php endif; ?>

										</div>
									</div>
									<div class="card border-top-success rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h5 class="card-title font-weight-600 text-dark font-size-lg">Product Gallery</h5>			
										</div>
								
										<div class="card-body">
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-12">
														<button type="button" id="btn_modal_edit_add_product_gallery_image" class="btn btn-lg btn-link"><i class="icon-image2 mr-1"></i> Add Product Gallery Image(s)</button>
														<div id="div_product_gallery_images">
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
																						<a href="javascript:;" role="button" class="list-icons-item lnk_edit_product_gallery_image" data-product-image-id ="<?php echo $poi->product_image_id; ?>" title="Edit Image"><i class="icon-pencil top-0"></i></a>
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
					                                        </div>
					                                    </div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card border-top-success rounded-top-0">
										<div class="card-header alpha-grey header-elements-inline pt-2 pb-2">
											<h5 class="card-title font-weight-600 text-dark font-size-lg">Product Options</h5>			
										</div>
								
										<div class="card-body">
											<div class="form-group mb-2">
												<div class="row">										
													<div class="col-md-4">
														<label class="d-block">Product Status <span class="error">*</span>:</label>
													</div>
													<div class="col-md-8">
														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																Online
																<input type="radio" class="form-check-input" id="is_online1" name="is_online" value="1" <?php if ($row->is_online == 1){ echo 'checked'; } ?>
															</label>
														</div>
														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																Offline
																<input type="radio" class="form-check-input" id="is_online0" name="is_online" value="0" <?php if ($row->is_online == 0){ echo 'checked'; } ?>>
															</label>
														</div>
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-4">
														<label class="d-block">Is Featured <span class="error">*</span>:</label>
													</div>
													<div class="col-md-8">
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
											<div class="form-group mb-2">
												<div class="row">										
													<div class="col-md-4">
														<label class="d-block">Is New Arrival <span class="error">*</span>:</label>
													</div>
													<div class="col-md-8">
														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																Yes
																<input type="radio" class="form-check-input" id="is_new_arrival1" name="is_new_arrival" value="1" <?php if ($row->is_new_arrival == 1){ echo 'checked'; } ?>>
															</label>
														</div>
														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																No
																<input type="radio" class="form-check-input" id="is_new_arrival0" name="is_new_arrival" value="0"  <?php if ($row->is_new_arrival == 0){ echo 'checked'; } ?>>
															</label>
														</div>
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-md-4">
														<label class="d-block">Is Special Offer <span class="error">*</span>:</label>
													</div>
													<div class="col-md-8">
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
											<div class="form-group mb-2">
												<div class="row">										
													<div class="col-sm-4">
														<label class="d-block">Is Deal of The Week <span class="error">*</span>:</label>
													</div>
													<div class="col-md-8">
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

								<div class="clearfix"></div>
								<div class="col-md-12">
									<hr>
									<div class="text-right">
										<button type="submit" id="btn_edit_product" class="btn btn-sm btn-primary"><i class="icon-checkmark4"></i> UPDATE PRODUCT</button>
									</div>
								</div>
							</div>
						</form>


						<div id="modal_add_product_attribute" class="modal fade">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-primary"><i class="icon-plus-circle2"></i> Add Product Attribute</h5>
										<button type="button" class="close" data-dismiss="modal">&times;</button>
									</div>

									<form id="frm_add_product_attribute" name="frm_add_product_attribute" method="post" onsubmit="return save_product_attribute();">
										
										<div class="modal-body">

											<div id="div_add_product_attribute_error" class="alert alert-danger display-none font-13"></div>
			                   				<div id="div_add_product_attribute_success" class="alert alert-success display-none font-13"></div>

			                   				<input type="hidden" id="apa_product_id" name="product_id">

											
											<div class="form-group">
												<div class="row">
													<div class="col-sm-3">
														<label>Name <span class="error">*</span></label>
													</div>
													<div class="col-sm-9">
														<input id="add_product_attribute_name" name="product_attribute_name" type="text" placeholder="" class="form-control">
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="row">
													<div class="col-sm-3">
														<label>Values <span class="error">*</span></label>
													</div>
													<div class="col-sm-9">
														<input id="add_product_attribute_values" name="product_attribute_values" type="text" class="form-control tagsinput-custom-tag-class" value="" data-fouc>
														<span class="text-muted">Enter attribute values separate by comma (,)</span>
													</div>
												</div>
											</div>
										</div>

										<div class="modal-footer">								
											<button type="submit" id="btn_add_product_attribute" class="btn btn-sm btn-primary"><i class="icon-checkmark4"></i> SAVE</button>
											<button type="button" class="btn  btn-sm btn-danger" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div id="modal_edit_product_attribute" class="modal fade">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-primary"><i class="icon-plus-circle2"></i> Edit Product Attribute</h5>
										<button type="button" class="close" data-dismiss="modal">&times;</button>
									</div>

									<form id="frm_edit_product_attribute" name="frm_edit_product_attribute" method="post" onsubmit="return update_product_attribute();">
										
										<div class="modal-body">

											<div id="div_edit_product_attribute_error" class="alert alert-danger display-none font-13"></div>
			                   				<div id="div_edit_product_attribute_success" class="alert alert-success display-none font-13"></div>

			                   				<input type="hidden" id="epa_product_id" name="product_id">
			                   				<input type="hidden" id="epa_product_attribute_id" name="product_attribute_id">
											
											<div class="form-group">
												<div class="row">
													<div class="col-sm-3">
														<label>Name <span class="error">*</span></label>
													</div>
													<div class="col-sm-9">
														<input id="edit_product_attribute_name" name="product_attribute_name" type="text" placeholder="" class="form-control">
													</div>
												</div>
											</div>
											<div class="form-group">
												<div class="row">
													<div class="col-sm-3">
														<label>Values <span class="error">*</span></label>
													</div>
													<div class="col-sm-9">
														<input id="edit_product_attribute_values" name="product_attribute_values" type="text" class="form-control tagsinput-custom-tag-class" value="" data-fouc>
														<span class="text-muted">Enter attribute values separate by comma (,)</span>
													</div>
												</div>
											</div>
										</div>

										<div class="modal-footer">								
											<button type="submit" id="btn_edit_product_attribute" class="btn btn-sm btn-primary"><i class="icon-checkmark4"></i> UPDATE</button>
											<button type="button" class="btn  btn-sm btn-danger" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
										</div>
									</form>
								</div>
							</div>
						</div>
						<div id="modal_add_product_variation" class="modal fade">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-primary"><i class="icon-plus-circle2"></i> Add Product Variation</h5>
										<button type="button" class="close" data-dismiss="modal">&times;</button>
									</div>
									<div id="div_variation_add_form">

									</div>
								</div>
							</div>
						</div>
						<div id="modal_edit_product_variation" class="modal fade">
							<div class="modal-dialog modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-primary"><i class="icon-plus-circle2"></i> Edit Product Variation</h5>
										<button type="button" class="close" data-dismiss="modal">&times;</button>
									</div>
									<div id="div_variation_edit_form">

									</div>
								</div>
							</div>
						</div>

						<div id="modal_set_product_image" class="modal fade">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-primary"><i class="icon-plus-circle2"></i> Set Product Image</h5>
										<button type="button" class="close" data-dismiss="modal">&times;</button>
									</div>

									<form id="frm_set_product_image" name="frm_set_product_image" method="post" onsubmit="return upload_set_product_image();">
										
										<div class="modal-body">
											<div class="block-inner text-secondary">
		                                        <h6 class="heading-hr"> <small class="display-block">Browse for the image then click Save</small></h6>
		                                    </div>

											<div id="div_set_product_image_error" class="alert alert-danger display-none font-13"></div>
			                   				<div id="div_set_product_image_success" class="alert alert-success display-none font-13"></div>

			                   				<input type="hidden" id="spi_product_id" name="product_id">

											<div class="form-group">
												<input id="spi_product_image" name="product_image" type="file" accept="application/image" class="form-control">
												<span class="form-text text-muted">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
											</div>
										</div>

										<div class="modal-footer">								
											<button type="submit" id="btn_set_product_image" class="btn btn-sm btn-primary"><i class="icon-checkmark4"></i> SAVE</button>
											<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
										</div>
									</form>
								</div>
							</div>
						</div>

						<div id="modal_add_product_gallery_image" class="modal fade">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-primary"><i class="icon-plus-circle2"></i> Add Product Gallery Image(s)</h5>
										<button type="button" class="close" data-dismiss="modal">&times;</button>
									</div>

									<form id="frm_add_product_gallery_image" name="frm_add_product_gallery_image" method="post" onsubmit="return upload_add_product_gallery_image();">
										
										<div class="modal-body">
											<div class="block-inner text-secondary">
		                                        <h6 class="heading-hr"> <small class="display-block">Browse for the image then click Save</small></h6>
		                                    </div>

											<div id="div_add_product_gallery_image_error" class="alert alert-danger display-none font-13"></div>
			                   				<div id="div_add_product_gallery_image_success" class="alert alert-success display-none font-13"></div>

			                   				<input type="hidden" id="apgi_product_id" name="product_id">

											<div class="form-group">
												<input id="apgi_product_gallery_image" name="product_gallery_image[]" multiple type="file" accept="application/image" class="form-control">
												<span class="form-text text-muted">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
											</div>
										</div>

										<div class="modal-footer">								
											<button type="submit" id="btn_add_product_gallery_image" class="btn btn-sm btn-primary"><i class="icon-checkmark4"></i> SAVE</button>
											<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
										</div>
									</form>
								</div>
							</div>
						</div>

						<div id="modal_edit_product_gallery_image" class="modal fade">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title text-primary"><i class="icon-pencil"></i> Edit Product Image</h5>
										<button type="button" class="close" data-dismiss="modal">&times;</button>
									</div>

									<form id="frm_edit_product_gallery_image" name="frm_edit_product_gallery_image" method="post" onsubmit="return upload_edit_product_gallery_image();">
										
										<div class="modal-body">
											<div class="block-inner text-secondary">
		                                        <h6 class="heading-hr"> <small class="display-block">Browse for the image then click Save</small></h6>
		                                    </div>

											<div id="div_edit_product_gallery_image_error" class="alert alert-danger display-none font-13"></div>
			                   				<div id="div_edit_product_gallery_image_success" class="alert alert-success display-none font-13"></div>

			                   				<input type="hidden" id="epgi_product_image_id" name="product_image_id">

											<div class="form-group">
												<!-- <label>&nbsp;</label> -->
												<input id="epgi_product_gallery_image" name="product_gallery_image" type="file" accept="application/image" class="form-control">
												<span class="form-text text-muted">Accepted formats: gif, png, jpg. Max file size 2Mb</span>
											</div>
										</div> 

										<div class="modal-footer">								
											<button type="submit" id="btn_edit_product_gallery_image" class="btn btn-sm btn-primary"><i class="icon-checkmark4"></i> SAVE</button>
											<button type="button" class="btn btn-sm btn-danger" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
										</div>
									</form>
								</div>
							</div>
						</div>


					</div>
				<?php endforeach; ?>
			<?php endif; ?>

			<script type="text/javascript">
				$(document).ready(function() {
					$(".chk_ru_unit_id").each(function() {
						if ($(this).is(":checked")) {
							$(this).closest('.row').removeClass('text-muted');
							$(this).closest('.row').find('input').prop('readonly', false);
							$(this).closest('.row').find('input[type="number"]').prop('required',true);
							$(this).closest('.row').find('.hid_chk_ru_unit_id').prop('disabled',true);
						} else {
							$(this).closest('.row').addClass('text-muted');
							$(this).closest('.row').find('input').prop('readonly', true);
							$(this).closest('.row').find('input[type="number"]').prop('required',false);
							$(this).closest('.row').find('.hid_chk_ru_unit_id').prop('disabled',false);
						}
					});

					$(document).on('change', '.chk_ru_unit_id', function(e) {
						if ($(this).is(":checked")) {
							$(this).closest('.row').removeClass('text-muted');
							$(this).closest('.row').find('input').prop('readonly', false);
							$(this).closest('.row').find('input[type="number"]').prop('required',true);
							$(this).closest('.row').find('.hid_chk_ru_unit_id').prop('disabled',true);
						} else {
							$(this).closest('.row').addClass('text-muted');
							$(this).closest('.row').find('input').prop('readonly', true);
							$(this).closest('.row').find('input[type="number"]').prop('required',false);
							$(this).closest('.row').find('.hid_chk_ru_unit_id').prop('disabled',false);
						}
					});
				});
				CKEDITOR.replace( 'product_short_description', {
                    height: 100,
                    toolbar: [
						{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo', 'Styles', 'Format', 'Font', 'FontSize', 'NumberedList', 'BulletedList' ] }
					]
                });
				CKEDITOR.replace( 'product_description', {
                    height: 300,
                    toolbar: [
						{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo', 'Styles', 'Format', 'Font', 'FontSize', 'NumberedList', 'BulletedList' ] }
					]
                });
			</script>


