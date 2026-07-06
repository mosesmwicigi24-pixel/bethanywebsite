								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 1, "asc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 0, 5, 6] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_banks_0" name="chk_bank_id0" value="0"></th>
											<th style="width: 200px">Bank Name</th>
											<th style="width: 110px">Bank Code</th>
											<th style="width: 200px">Comment</th>
											<th style="width: 90px" class="text-center">Sort Key</th>
											<th style="width: 80px" class="text-center">Status</th>
											<th style="width: 80px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($banks as $row): ?>
											<tr>
												<td class="text-center"><input type="checkbox" id="chk_banks_<?php echo $row->bank_id; ?>" name="chk_bank_id" value="<?php echo $row->bank_id; ?>"></td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_bank" onclick="bank_edit_load(<?php echo $row->bank_id; ?>);" class="font-weight-bold"><?php echo $row->bank_name; ?></a>
														</div>
													</div>													
												</td>
												<td><?php echo $row->bank_code; ?></td>
												<td><?php echo $row->bank_comment; ?></td>
												<td class="text-center"><?php echo $row->sort_key; ?></td>
												<td class="text-center">
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
																<?php if ($sbr_banks_manage == true): ?>
																	<a href="<?php echo base_url();?>be/settings/bank_branches/<?php echo $row->bank_id; ?>" class="dropdown-item"><i class="icon-list"></i> Manage Branches</a>
																<?php endif; ?>
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_bank" onclick="bank_edit_load(<?php echo $row->bank_id; ?>);" class="dropdown-item"><i class="icon-pencil6"></i> View/Edit Bank</a>
																<?php if ($sbr_banks_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_bank(<?php echo $row->bank_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete Bank</a>
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
									$("#chk_banks_0").on('change', function() {
								        if($("#chk_banks_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_bank_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_bank_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								