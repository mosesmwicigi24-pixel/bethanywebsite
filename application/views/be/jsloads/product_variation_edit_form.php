							<script>
								$(document).ready(function() {
									$('.select').select2({
								        placeholder: "Enter at least 1 character",
								        allowClear: true
								    });
								    $('[data-popup="tooltip"]').tooltip();
								});

							    //VALIDATE FRM_EDIT_PRODUCT_VARIATION
							    $("#frm_edit_product_variation").validate({
							        ignore: [],
							        errorPlacement: function(error, element) {
							            if (element.parent().parent().attr("class") == "checker" || element.parent().parent().attr("class") == "choice") {
							                error.appendTo(element.parent().parent().parent().parent().parent());
							            } else if (element.parent().parent().attr("class") == "checkbox" || element.parent().parent().attr("class") == "radio") {
							                error.appendTo(element.parent().parent().parent());
							            } else {
							                error.insertAfter(element);
							            }
							        },
							        rules: {
							            product_variation_regular_price: {
							                required: true
							            }
							        },
							        messages: {
							            custom_message: {
							                required: "This field is required",
							            },
							            agree: "Please accept our policy"
							        },
							        success: function(label) {
							            label.parent().removeClass('error');
							            label.remove();
							        }
							    });

							</script>
							<form id="frm_edit_product_variation" name="frm_edit_product_variation" method="post" onsubmit="return update_product_variation();">
								
								<div class="modal-body">

									<div id="div_edit_product_variation_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_edit_product_variation_success" class="alert alert-success display-none font-13"></div>

	                   				<input type="hidden" id="apv_product_id" name="product_id" value="<?php echo $product_id; ?>">	                   				

	                   				<?php foreach ($product_variation as $row): ?>
	                   					<div class="row">
	                   						<div class="col-sm-12">
			                   					<input type="hidden" id="apv_product_variation_id" name="product_variation_id" value="<?php echo $row->product_variation_id; ?>">
				                   				<div class="row">
				                   					<?php foreach ($product_attributes as $row2): ?>
				                   						<div class="col-sm-4">
				                   							<div class="form-group">
				                   								<input type="hidden" id="" name="product_attribute_id[]" value="<?php echo $row2->product_attribute_id; ?>">
				                   								<select id="apv_product_attribute_value_id_<?php echo $row2->product_attribute_id; ?>" name="product_attribute_value_id[]" class="form-control select pavi" data-placeholder="Any <?php echo $row2->product_attribute_name; ?>" data-fouc>
																	<option value="">Any <?php echo $row2->product_attribute_name; ?></option>
																	<?php if(!empty($row2->values)): ?>
																		<?php foreach($row2->values as $row3): ?>
																			<option value="<?php echo $row3->product_attribute_value_id; ?>" <?php if (!empty($row->attributes)){ foreach ($row->attributes as $row4){ if ($row4->product_attribute_value_id == $row3->product_attribute_value_id){ echo 'selected'; }}} ?>><?php echo $row3->product_attribute_value; ?></option>
																		<?php endforeach; ?>
																	<?php endif; ?>
																</select>
				                   							</div>
				                   						</div>
				                   					<?php endforeach; ?>
				                   				</div>
				                   				<hr class="mt-0">	
				                   				<div class="row">
				                   					<div class="col-md-4">
						                   				<div class="form-group">
															<div class="row">
																<div class="col-sm-12">

																	<?php if($row->product_variation_image != '' && file_exists("./uploads/product_images/" . $row->product_variation_image)): ?>
																		<div class="card-img-actions d-inline-block mb-2">
																			<img class="card-img img-fluid w-50" src="<?php echo base_url(); ?>uploads/product_images/<?php echo $row->product_variation_image; ?>" alt="">
																		</div>
																		<div class="form-group mb-2">
																			<div class="row">
																				<div class="col-sm-10">
																					<label>Change Image</label>
																					<input id="edit_product_variation_image" name="product_variation_image" type="file" class="form-control">
																					<a href="javascript:;" class="float-right" onclick="javascript:document.getElementById('edit_product_variation_image').value = null;">Clear Image</a>
																				</div>
																			</div>
																		</div>
																	<?php else: ?>
																		<div class="form-group mb-2">
																			<div class="row">
																				<div class="col-sm-10">
																					<label>Select Image</label>
																					<input id="edit_product_variation_image" name="product_variation_image" type="file" class="form-control">
																					<a href="javascript:;" class="float-right" onclick="javascript:document.getElementById('edit_product_variation_image').value = null;">Clear Image</a>
																				</div>
																			</div>
																		</div>
																	<?php endif; ?>
																</div>
																<!-- <div class="col-sm-12">
																	<label>&nbsp;</label>
																	
																</div> -->
															</div>
														</div>
													</div>
													<div class="col-md-8">
														<div class="form-group">
															<div class="form-check">
																	<label class="form-check-label">
																		<input id="edit_is_enabled" name="is_enabled" type="checkbox" class="form-check-input" <?php if ($row->is_enabled == 1){ echo 'checked'; } ?>>
																		Enabled
																	</label>
																</div>
															</div>
														<div class="form-group mb-1">
															<div class="row">
																<div class="col-sm-4">
																	<label>Regular Price <span class="error">*</span></label>
																	<input id="edit_product_variation_regular_price" name="product_variation_regular_price" type="number" placeholder="" class="form-control" value="<?php echo $row->product_variation_regular_price; ?>">
																</div>
																<div class="col-sm-4">
																	<label>Sale Price <i class="icon-info22 text-primary" data-popup="tooltip" title="Use this when you're running an offer. Please note that this will override the Regular Price" data-placement="top"></i></label>
																	<input id="edit_product_variation_sale_price" name="product_variation_sale_price" type="number" placeholder="" class="form-control" value="<?php echo $row->product_variation_sale_price; ?>">
																</div>
																<div class="col-sm-4">
																	<label>Minimum Selling Price</label>
																	<input id="edit_product_variation_minimum_selling_price" name="product_variation_minimum_selling_price" type="number" placeholder="" class="form-control" value="<?php echo $row->product_variation_minimum_selling_price; ?>">
																</div>
																<div class="col-sm-12">
																	<div class="form-check text-right mt-2">
																		<label class="form-check-label">
																			<input id="edit_product_variation_universal_prices" name="product_variation_universal_prices" type="checkbox" class="form-check-input" <?php if ($row->product_variation_universal_prices == 1){ echo 'checked'; } ?>>
																			Use these prices across all outlets <i class="icon-info22 text-primary" data-popup="tooltip" title="If you uncheck this option, make sure to set outlet prices below" data-placement="top"></i>
																		</label>
																	</div>
																</div>
															</div>										
														</div>
														<div class="form-group">
															<div class="row">
																<div class="col-sm-12">
																	<label>Description</label>
																	<textarea id="edit_product_variation_description" name="product_variation_description" class="form-control" rows="2"><?php echo $row->product_variation_description; ?></textarea>
																</div>
															</div>
														</div>
													</div>
												</div>
												<hr class="mt-0">
											</div>
											
											<div class="col-sm-12">
												<h5 class="text-primary mb-0">Inventory</h5>
												<div class="table-responsive">
													<table class="table">
														<thead>
															<tr>
																<th>Outlet</th>
																<th>Opening Stock</th>
																<!-- <th>Available Stock</th> -->
																<th>Reorder Level</th>
																<th>Regular Price</th>
																<th>Sale Price</th>
																<th>Minimum Selling Price</th>
															</tr>
														</thead>
														<tbody>
															<?php foreach ($outlets as $row2): ?>
																<?php
																	$cur_opening_stock = 0;
																	$cur_available_stock = 0;
																	$cur_reorder_level = 0;
																	$cur_regular_price = 0;
																	$cur_sale_price = 0;
																	$cur_minimum_selling_price = 0;
																	foreach ($variation_outlet_products as $row3) {
																		if ($row2->outlet_id == $row3->outlet_id) {
																			$cur_opening_stock = $row3->opening_stock;
																			$cur_available_stock = $row3->available_stock;
																			$cur_reorder_level = $row3->reorder_level;
																			$cur_regular_price = $row3->regular_price;
																			$cur_sale_price = $row3->sale_price;
																			$cur_minimum_selling_price = $row3->minimum_selling_price;
																		}
																	}
																?>
																<tr>
																	<td><?php echo $row2->outlet_name; ?></td>
																	<td><input id="variation_opening_stock_<?php echo $row2->outlet_id; ?>" name="opening_stock_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" value="<?php echo $cur_opening_stock; ?>" class="form-control"></td>
																	<!-- <td width="100"><input id="variation_available_stock_<?php echo $row2->outlet_id; ?>" name="available_stock_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" value="<?php echo $cur_available_stock; ?>" class="form-control" readonly></td> -->
																	<td><input id="variation_reorder_level_<?php echo $row2->outlet_id; ?>" name="reorder_level_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" value="<?php echo $cur_reorder_level; ?>" class="form-control"></td>
																	<td><input id="variation_regular_price_<?php echo $row2->outlet_id; ?>" name="regular_price_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" value="<?php echo $cur_regular_price; ?>" class="form-control"></td>
																	<td><input id="variation_sale_price_<?php echo $row2->outlet_id; ?>" name="sale_price_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" value="<?php echo $cur_sale_price; ?>" class="form-control"></td>
																	<td><input id="variation_minimum_selling_price_<?php echo $row2->outlet_id; ?>" name="minimum_selling_price_<?php echo $row2->outlet_id; ?>" type="number" placeholder="" value="<?php echo $cur_minimum_selling_price; ?>" class="form-control"></td>
																</tr>
															<?php endforeach; ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									<?php endforeach; ?>
								</div>

								<div class="modal-footer">								
									<button type="submit" id="btn_edit_product_variation" class="btn btn-sm btn-primary"><i class="icon-checkmark4"></i> UPDATE</button>
									<button type="button" class="btn  btn-sm btn-danger" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
								</div>
							</form>