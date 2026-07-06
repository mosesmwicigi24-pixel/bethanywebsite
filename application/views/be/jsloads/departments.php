								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 2, "asc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 0, 1, 3] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th style="width: 120px"></th>
											<th>Name</th>
											<th>Sort Key</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($departments as $row): ?>
											<tr>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<?php if($row->thumb_image != '' && file_exists("./uploads/department_cover_images/thumbs/" . $row->thumb_image)): ?>
																<img src="<?php echo base_url();?>uploads/department_cover_images/thumbs/<?php echo $row->thumb_image; ?>" class="" width="100" alt="">
						                                    <?php else: ?>
						                                    	<!-- <img src="<?php echo base_url();?>assets/be/images/placeholder.png" class="" width="100" alt=""> -->
						                                    <?php endif; ?>
														</div>
													</div>
												</td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="<?php echo base_url();?>be/departments/edit/<?php echo $row->department_id; ?>" class="font-weight-bold"><?php echo $row->department_name; ?></a>
														</div>
													</div>													
												</td>
												<td><?php echo $row->sort_key; ?></td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<a href="<?php echo base_url();?>be/departments/edit/<?php echo $row->department_id; ?>" class="dropdown-item"><i class="icon-pencil6"></i> Edit</a>
																<a href="javascript:void(0);" onclick="delete_department(<?php echo $row->department_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete</a>
															</div>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>