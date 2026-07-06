								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										"order": [[ 5, "asc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 0, 6 ] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_bank_branches_0" name="chk_bank_branch_id0" value="0"></th>
											<th style="width: 180px">Branch</th>
											<th style="width: 120px">Account #</th>
											<th style="width: 180px">Phone #</th>
											<th style="width: 150px">Email</th>
											<th style="width: 90px">Sort Key</th>
											<th style="width: 90px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($bank_branches as $row): ?>
											<tr>
												<td class="text-center"><input type="checkbox" id="chk_bank_branches_<?php echo $row->bank_branch_id; ?>" name="chk_bank_branch_id" value="<?php echo $row->bank_branch_id; ?>"></td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<b>Name: </b><a href="javascript:;" data-toggle="modal" data-target="#modal_edit_bank_branch" onclick="bank_branch_edit_load(<?php echo $row->bank_branch_id; ?>);" class="font-weight-bold"><?php echo $row->bank_branch_name; ?></a>
															<?php if ($row->bank_branch_code != ''): ?>
																<br><b>Code: </b><?php echo $row->bank_branch_code; ?>
															<?php endif; ?>
														</div>
													</div>													
												</td>
												<td><?php echo $row->account_number; ?></td>
												<td>
													<?php if ($row->phone_number != ''): ?>
														<b>Phone:</b> <?php echo $row->phone_number; ?><br>
													<?php endif; ?>
													<?php if ($row->mobile_number != ''): ?>
														<b>Mobile:</b> <?php echo $row->mobile_number; ?>
													<?php endif; ?>
												</td>
												<td><?php echo $row->email_address; ?></td>
												<td><?php echo $row->sort_key; ?></td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_bank_branch" onclick="bank_branch_edit_load(<?php echo $row->bank_branch_id; ?>);" class="dropdown-item"><i class="icon-pencil6"></i> Edit Branch</a>
																<?php if ($sbr_bank_branches_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_bank_branch(<?php echo $row->bank_branch_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete Branch</a>
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
									$("#chk_bank_branches_0").on('change', function() {
								        if($("#chk_bank_branches_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_bank_branch_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_bank_branch_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								