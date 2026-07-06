								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 2, "asc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 0, 1, 9, 10] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_products_0" name="chk_product_id0" value="0"></th>
											<th style="width: 150px" class="text-center">Main Image</th>
											<th style="width: 300px">Product Name</th>
											<th style="width: 120px">Product Type</th>
											<th style="width: 180px">Categories</th>
											<th style="width: 150px">Brand</th>
											<th style="width: 120px">Unit</th>
											<th style="width: 120px">Price</th>
											<th style="width: 90px">Sort Key</th>
											<th style="width: 80px" class="text-center">Status</th>
											<th style="width: 80px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($products as $row): ?>
											<tr>
												<td class="text-center"><input type="checkbox" id="chk_products_<?php echo $row->product_id; ?>" name="chk_product_id" value="<?php echo $row->product_id; ?>"></td>
												<td class="text-center">
													<div class="media">
														<div class="media-body align-self-center">
															<?php if($row->product_image_thumb != '' && file_exists("./uploads/product_images/thumbs/" . $row->product_image_thumb)): ?>
																<img src="<?php echo base_url();?>uploads/product_images/thumbs/<?php echo $row->product_image_thumb; ?>" class="" width="100" alt="">
						                                    <?php else: ?>
						                                    	&mdash;
						                                    <?php endif; ?>
														</div>
													</div>
												</td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<?php if ($sbr_products_edit == true): ?>
																<a href="<?php echo base_url();?>be/products/edit/<?php echo $row->product_id; ?>" class="font-weight-bold"><?php echo $row->product_name; ?></a>
															<?php else: ?>
																<span class="font-weight-bold"><?php echo $row->product_name; ?></span>
															<?php endif; ?>
															<?php echo '<br><b>SKU Code:</b> ' . $row->product_sku_code; ?>
														</div>
													</div>													
												</td>
												<td><?php echo $row->product_type; ?></td>
												<td>
													<?php
														if ($row->product_category_name != '' && $row->product_category_name != null){
                                                            $ocn = explode(',', $row->product_category_name);
                                                            foreach($ocn as $o_c_n){
                                                                echo '<span class="badge badge-success badge-pill mr-1 mb-1">' . $o_c_n . '</span>';
                                                            }
                                                        }
													?>
												</td>
												<td><?php echo $row->brand_name; ?></td>
												<td><?php echo $row->unit_name . ' (' . $row->unit_code . ')'; ?></td>
												<td>
													<?php if ($row->sale_price > 0): ?>
														<?php echo $default_currency . ' ' . number_format($row->sale_price,2); ?>
														<br><span class="text-grey"><strike><?php echo $default_currency . ' ' . number_format($row->regular_price,2); ?></strike></span>
													<?php else: ?>
														<?php echo $default_currency . ' ' . number_format($row->regular_price,2); ?>
													<?php endif; ?>													
												</td>
												<td><?php echo $row->sort_key; ?></td>
												<td class="text-center">
													<?php if ($row->is_online == 1): ?>
														<span class="badge bg-success">Online</span>
													<?php else: ?>
														<span class="badge bg-danger">Offline</span>
													<?php endif; ?>
												</td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<?php if ($sbr_products_edit == true): ?>
																	<a href="<?php echo base_url();?>be/products/edit/<?php echo $row->product_id; ?>" class="dropdown-item"><i class="icon-pencil6 text-primary"></i> Edit Product</a>
																	<?php if ($row->is_online == 0): ?>
																		<a href="javascript:void(0);" onclick="set_product_online_status(<?php echo $row->product_id; ?>, 1);" class="dropdown-item"><i class="icon-switch text-secondary"></i> Set as Online</a>
																	<?php elseif ($row->is_online == 1): ?>
																		<a href="javascript:void(0);" onclick="set_product_online_status(<?php echo $row->product_id; ?>, 0);" class="dropdown-item"><i class="icon-switch text-success"></i> Set as Offline</a>
																	<?php endif; ?>
																	<?php if ($row->is_featured == 0): ?>
																		<a href="javascript:void(0);" onclick="set_product_featured_status(<?php echo $row->product_id; ?>, 1);" class="dropdown-item"><i class="icon-thumbs-down3 text-secondary"></i> Set as Featured</a>
																	<?php elseif ($row->is_featured == 1): ?>
																		<a href="javascript:void(0);" onclick="set_product_featured_status(<?php echo $row->product_id; ?>, 0);" class="dropdown-item"><i class="icon-thumbs-up3 text-success"></i> Unset as Featured</a>
																	<?php endif; ?>
																	<?php if ($row->is_new_arrival == 0): ?>
																		<a href="javascript:void(0);" onclick="set_product_new_arrival_status(<?php echo $row->product_id; ?>, 1);" class="dropdown-item"><i class="icon-thumbs-down3 text-secondary"></i> Set as New Arrival</a>
																	<?php elseif ($row->is_new_arrival == 1): ?>
																		<a href="javascript:void(0);" onclick="set_product_new_arrival_status(<?php echo $row->product_id; ?>, 0);" class="dropdown-item"><i class="icon-thumbs-up3 text-success"></i> Unset as New Arrival</a>
																	<?php endif; ?>
																	<?php if ($row->is_special_offer == 0): ?>
																		<a href="javascript:void(0);" onclick="set_product_special_offer_status(<?php echo $row->product_id; ?>, 1);" class="dropdown-item"><i class="icon-thumbs-down3 text-secondary"></i> Set as Special Offer</a>
																	<?php elseif ($row->is_special_offer == 1): ?>
																		<a href="javascript:void(0);" onclick="set_product_special_offer_status(<?php echo $row->product_id; ?>, 0);" class="dropdown-item"><i class="icon-thumbs-up3 text-success"></i> Unset as Special Offer</a>
																	<?php endif; ?>
																	<?php if ($row->is_deal_of_the_week == 0): ?>
																		<a href="javascript:void(0);" onclick="set_product_deal_of_the_week_status(<?php echo $row->product_id; ?>, 1);" class="dropdown-item"><i class="icon-thumbs-down3 text-secondary"></i> Set as Deal of the Week</a>
																	<?php elseif ($row->is_deal_of_the_week == 1): ?>
																		<a href="javascript:void(0);" onclick="set_product_deal_of_the_week_status(<?php echo $row->product_id; ?>, 0);" class="dropdown-item"><i class="icon-thumbs-up3 text-success"></i> Unset as Deal of the Week</a>
																	<?php endif; ?>
																<?php endif; ?>

																<?php if ($sbr_products_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_product(<?php echo $row->product_id; ?>);" class="dropdown-item"><i class="icon-cancel-circle2 text-danger"></i> Delete Product</a>
																<?php endif; ?>
															</div>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>


								<script type="text/javascript">
									$("#chk_products_0").on('change', function() {
								        if($("#chk_products_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_product_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_product_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								