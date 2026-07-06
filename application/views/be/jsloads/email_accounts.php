								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 1, "asc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 0, 4, 5] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_email_accounts_0" name="chk_email_account_id0" value="0"></th>
											<th style="width: 150px">Email Address</th>
											<th style="width: 150px">Sender Name</th>
											<th style="width: 100px" class="text-center">Sort Key</th>
											<th style="width: 90px" class="text-center">Status</th>
											<th style="width: 90px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($email_accounts as $row): ?>
											<tr>
												<td class="text-center"><input type="checkbox" id="chk_email_accounts_<?php echo $row->email_account_id; ?>" name="chk_email_account_id" value="<?php echo $row->email_account_id; ?>"></td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<?php if ($row->is_default == 1): ?>
																<i class="icon-checkmark-circle text-success"></i>
															<?php endif; ?>
															<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_email_account" onclick="email_account_edit_load(<?php echo $row->email_account_id; ?>);" class="font-weight-bold"><?php echo $row->sender_email_address; ?></a>
														</div>
													</div>													
												</td>
												<td><?php echo $row->sender_name; ?></td>
												<td class="text-center"><?php echo $row->sort_key; ?></td>
												<td class="text-center">
													<?php if ($row->is_active == 1): ?>
														<span class="badge badge-success">Active</span>
													<?php else: ?>
														<span class="badge badge-danger">Inactive</span>
													<?php endif; ?>
												</td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_email_account" onclick="email_account_edit_load(<?php echo $row->email_account_id; ?>);" class="dropdown-item"><i class="icon-pencil6"></i> Edit Email Account</a>
																<?php if ($sbr_email_accounts_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_email_account(<?php echo $row->email_account_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete Email Account</a>
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
									$("#chk_email_accounts_0").on('change', function() {
								        if($("#chk_email_accounts_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_email_account_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_email_account_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								