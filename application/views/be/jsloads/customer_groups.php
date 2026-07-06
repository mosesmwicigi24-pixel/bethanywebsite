								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 2, "asc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 0, 2, 3] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_customer_groups_0" name="chk_customer_group_id0" value="0"></th>
											<th style="width: 250px">Name</th>
											<th style="width: 100px">Sort Key</th>
											<th style="width: 90px">Status</th>
											<th style="width: 90px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($customer_groups as $row): ?>
											<tr>	
												<td class="text-center"><input type="checkbox" id="chk_customer_groups_<?php echo $row->customer_group_id; ?>" name="chk_customer_group_id" value="<?php echo $row->customer_group_id; ?>"></td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_customer_group" onclick="customer_group_edit_load(<?php echo $row->customer_group_id; ?>);" class="font-weight-bold"><?php echo $row->customer_group_name; ?></a>
														</div>
													</div>													
												</td>
												<td><?php echo $row->sort_key; ?></td>
												<td>
													<?php if ($row->is_active == 1): ?>
														<span class="badge bg-success">Active</span>
													<?php else: ?>
														<span class="badge bg-danger">Inactive</span>
													<?php endif; ?>
												</td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_customer_group" onclick="customer_group_edit_load(<?php echo $row->customer_group_id; ?>);" class="dropdown-item"><i class="icon-pencil6"></i> View/Edit Group</a>
																<?php if ($sbr_customer_groups_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_customer_group(<?php echo $row->customer_group_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete Group</a>
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
									$("#chk_customer_groups_0").on('change', function() {
								        if($("#chk_customer_groups_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_customer_group_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_customer_group_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								