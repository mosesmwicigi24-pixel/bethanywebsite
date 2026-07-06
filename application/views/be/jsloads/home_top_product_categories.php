								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 3, "asc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 0, 2, 4 ] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th style="width: 120px" class="text-center"></th>
											<th style="width: 150px">Main Category</th>
											<th style="width: 300px">Subcategories</th>
											<th style="width: 90px">Position</th>
											<th style="width: 80px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($home_top_product_categories as $row): ?>
											<tr>
												<td class="text-center">
													<div class="media">
														<div class="media-body align-self-center">
															<?php if($row->thumb_image != '' && file_exists("./uploads/product_category_cover_images/thumbs/" . $row->thumb_image)): ?>
																<img src="<?php echo base_url();?>uploads/product_category_cover_images/thumbs/<?php echo $row->thumb_image; ?>" class="" width="100" alt="">
						                                    <?php else: ?>
						                                    	&mdash;
						                                    <?php endif; ?>
														</div>
													</div>
												</td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_home_top_product_category" onclick="home_top_product_category_edit_load(<?php echo $row->home_top_product_category_id; ?>);"><b><?php echo $row->product_category_name; ?></b></a>
														</div>
													</div>													
												</td>
												<td>
													<?php
														if(!empty($row->sub)){
															foreach($row->sub as $subcat){
																echo '<span class="badge badge-success badge-pill">' . $subcat->product_category_name . '</span>';
															}
														}
													?>
												</td>
												<td><?php echo $row->position; ?></td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_home_top_product_category" onclick="home_top_product_category_edit_load(<?php echo $row->home_top_product_category_id; ?>);" class="dropdown-item"><i class="icon-pencil6 text-primary"></i> View/Edit</a>
																<?php if ($sbr_top_categories_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_home_top_product_category(<?php echo $row->home_top_product_category_id; ?>);" class="dropdown-item"><i class="icon-cancel-circle2 text-danger"></i> Delete</a>
																<?php endif; ?>
															</div>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>

