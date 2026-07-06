								<div class="row">
									<div class="col-sm-12">
										<h5><b><?php foreach ($product as $row){ echo $row->product_name; } ?></b> variations<br><small>(Click on a variation to select)</small></h5>
										<?php foreach ($product as $row): ?>
											<input type="hidden" id="spv_product_name" value="<?php echo $row->product_name; ?>">
											<input type="hidden" id="spv_product_sku_code" value="<?php echo $row->product_sku_code; ?>">
											<input type="hidden" id="spv_unit_id" value="<?php echo $row->unit_id; ?>">
											<input type="hidden" id="spv_product_units" value='<?php echo json_encode($row->units); ?>'>
										<?php endforeach; ?>
										<?php foreach ($product_variations as $row): ?>
											<?php if ($row->product_variation_sale_price > 0){ $prod_price = $row->product_variation_sale_price; } else { $prod_price = $row->product_variation_regular_price; } ?>
											<a href="javascript:;" class="lnk-select-product-variation" 
												data-product-id="<?php echo $row->product_id; ?>" 
												data-product-variation-id="<?php echo $row->product_variation_id; ?>" 
												data-product-price="<?php echo $prod_price; ?>" 
												data-variation-description="<?php if (!empty($row->attributes)){ foreach ($row->attributes as $row2){ echo $row2->product_attribute_name . ' : <b>' . $row2->product_attribute_value . '</b>, '; }} ?>" 
												data-context="<?php echo $context; ?>" 
												data-available-stock="<?php if ($context == 'adjust_stock'){ echo $row->available_stock; } ?>" >
												<div class="card border-left-3 border-left-green-400 rounded-0 lnk-card">
													<div class="card-body p-2">
														<div class="row">
															<div class="col-md-6">
																<?php if(!empty($row->attributes)): ?>
																	<?php foreach($row->attributes as $row2): ?>
																		<p class="font-size-lg mb-0 text-default"><span class="badge"><i class="badge badge-mark border-danger mr-2"></i> <?php echo $row2->product_attribute_name; ?>:</span> <span class="font-weight-bold"><?php echo $row2->product_attribute_value; ?></span></p>
																	<?php endforeach; ?>
																<?php endif; ?>
															</div>
															<div class="col-md-6">
																<p class="font-size-lg mb-0 text-default"><span class="badge"><i class="badge badge-mark border-danger mr-2"></i>  Price:</span> <span class="font-weight-bold"> <?php if ($row->product_variation_sale_price > 0){ echo $default_currency . ' ' . number_format($row->product_variation_sale_price) . ' <strike class="text-muted"><small>' . $default_currency . ' ' . $row->product_variation_regular_price . '</small></strike>'; } else { echo $default_currency . ' ' . number_format($row->product_variation_regular_price); } ?></span></p>
															</div>
														</div>
													</div>
												</div>
											</a>
										<?php endforeach; ?>
									</div>
								</div>
