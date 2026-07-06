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


															<script type="text/javascript">
																$('[data-popup="popover"]').popover();
															</script>