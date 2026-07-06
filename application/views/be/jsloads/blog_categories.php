								<script type="text/javascript">
									$('.datatable-basic').DataTable();
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th>Name</th>
											<th>Description</th>
											<th>Sort Key</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($blog_categories as $row): ?>
											<tr>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_blog_category" onclick="blog_category_edit_load(<?php echo $row->blog_category_id; ?>);" class="font-weight-bold"><?php echo $row->blog_category_name ?></a>
														</div>
													</div>
												</td>
												<td><?php echo $row->description; ?></td>
												<td><?php echo $row->sort_key; ?></td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_blog_category" class="dropdown-item" onclick="blog_category_edit_load(<?php echo $row->blog_category_id; ?>);"><i class="icon-pencil6"></i> View/Edit Blog Category</a>
																<?php if ($sbr_blog_categories_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_blog_category(<?php echo $row->blog_category_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete Blog Category</a>
																<?php endif; ?>
															</div>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>