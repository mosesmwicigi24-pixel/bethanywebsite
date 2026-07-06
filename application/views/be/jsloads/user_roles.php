								<script type="text/javascript">
									$('.datatable-basic').DataTable();
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th>Name</th>
											<th>Description</th>
											<th>Sort Key</th>
											<th>Status</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($user_roles as $row): ?>
											<tr>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="<?php echo base_url();?>be/user_roles/edit/<?php echo $row->user_role_id; ?>" class="font-weight-bold"><?php echo $row->user_role_name ?></a>
														</div>
													</div>
												</td>
												<td><?php echo $row->user_role_description; ?></td>
												<td><?php echo $row->sort_key; ?></td>
												<td>
													<?php if ($row->is_active == 0): ?>
														<span class="badge badge-danger">Inactive</span>
													<?php elseif ($row->is_active == 1): ?>
														<span class="badge badge-success">Active</span>
													<?php endif; ?>
												</td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<a href="<?php echo base_url();?>be/user_roles/edit/<?php echo $row->user_role_id; ?>" class="dropdown-item"><i class="icon-pencil6"></i> View/Edit User Role</a>	
																<?php if ($sbr_user_roles_delete == true): ?>															
																	<a href="javascript:void(0);" onclick="delete_user_role(<?php echo $row->user_role_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete User Role</a>
																<?php endif; ?>
															</div>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>