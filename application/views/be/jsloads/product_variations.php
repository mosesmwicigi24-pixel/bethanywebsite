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