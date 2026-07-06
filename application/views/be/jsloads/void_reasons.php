								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 2, "asc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 0, 3, 4] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_void_reasons_0" name="chk_void_reason_id0" value="0"></th>
											<th style="width: 200px">Void Reason</th>
											<th style="width: 120px" class="text-center">Sort Key</th>
											<th style="width: 90px" class="text-center">Status</th>
											<th style="width: 90px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($void_reasons as $row): ?>
											<tr>
												<td class="text-center"><input type="checkbox" id="chk_void_reasons_<?php echo $row->void_reason_id; ?>" name="chk_void_reason_id" value="<?php echo $row->void_reason_id; ?>"></td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_void_reason" onclick="void_reason_edit_load(<?php echo $row->void_reason_id; ?>);" class="font-weight-bold"><?php echo $row->void_reason; ?></a>
														</div>
													</div>													
												</td>
												<td class="text-center"><?php echo $row->sort_key; ?></td>
												<td class="text-center">
													<?php if ($row->is_active == 1): ?>
														<span class="badge badge-success">Active</span>
													<?php else: ?>
														<span class="badge badge-secondary">Inactive</span>
													<?php endif; ?>
												</td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_void_reason" onclick="void_reason_edit_load(<?php echo $row->void_reason_id; ?>);" class="dropdown-item"><i class="icon-pencil6"></i> Edit Void Reason</a>
																<?php if ($sbr_void_reasons_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_void_reason(<?php echo $row->void_reason_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete Void Reason</a>
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
									$("#chk_void_reasons_0").on('change', function() {
								        if($("#chk_void_reasons_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_void_reason_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_void_reason_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								