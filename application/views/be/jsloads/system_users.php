								<script type="text/javascript">
									$('.datatable-basic').DataTable();
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th>Email Address</th>
											<th>Full Name</th>
											<th>User Role</th>
											<th>Sort Key</th>
											<th>Status</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($system_users as $row): ?>
											<tr>
												<td>
													<?php if ($row->is_super_admin == 0): ?>
														<div class="media">
															<div class="media-body align-self-center">
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_system_user" onclick="system_user_edit_load(<?php echo $row->system_user_id; ?>);" class="font-weight-bold"><?php echo $row->email_address ?></a>
															</div>
														</div>
													<?php else: ?>
														<div class="media">
															<div class="media-body align-self-center">
																<a href="javascript:void(0);" class="font-weight-bold"><?php echo $row->email_address ?></a>
															</div>
														</div>
													<?php endif; ?>
												</td>
												<td><?php echo $row->first_name . ' ' . $row->last_name; ?></td>
												<td><?php if ($row->is_super_admin == 1){ echo '<b>Super Admin</b>'; }else{ echo $row->user_role_name; } ?></td>
												<td><?php echo $row->sort_key; ?></td>
												<td>
													<?php if ($row->is_active == 0): ?>
														<span class="badge badge-danger">Inactive</span>
													<?php elseif ($row->is_active == 1): ?>
														<span class="badge badge-success">Active</span>
													<?php endif; ?>
												</td>
												<td class="text-center">
													<?php if ($row->is_super_admin == 0): ?>
														<div class="list-icons">
															<div class="dropdown">
																<a href="#" class="list-icons-item" data-toggle="dropdown">
																	<i class="icon-menu9"></i>
																</a>

																<div class="dropdown-menu dropdown-menu-right">
																	<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_system_user" class="dropdown-item" onclick="system_user_edit_load(<?php echo $row->system_user_id; ?>);"><i class="icon-pencil6"></i> View/Edit System User</a>
																	<?php if ($sbr_system_users_edit == true): ?>
																		<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_change_password" class="dropdown-item" onclick="system_user_change_password_load(<?php echo $row->system_user_id; ?>);"><i class="icon-lock2"></i> Change Password</a>
																	<?php endif; ?>
																	<?php if ($sbr_system_users_delete == true): ?>
																		<a href="javascript:void(0);" onclick="delete_system_user(<?php echo $row->system_user_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete System User</a>
																	<?php endif; ?>
																</div>
															</div>
														</div>
													<?php endif; ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>