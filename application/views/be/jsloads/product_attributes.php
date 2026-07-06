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