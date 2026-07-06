								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 4, "asc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 0, 5, 6] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_promo_codes_0" name="chk_promo_code_id0" value="0"></th>
											<th style="width: 200px">Name</th>
											<th style="width: 130px" class="text-center">Code</th>
											<th style="width: 130px" class="text-center">Mode</th>
											<th style="width: 130px" class="text-center">Value</th>
											<th style="width: 120px" class="text-center">Sort Key</th>
											<th style="width: 100px" class="text-center">Status</th>
											<th style="width: 100px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($promo_codes as $row): ?>
											<tr>
												<td class="text-center"><input type="checkbox" id="chk_promo_codes_<?php echo $row->promo_code_id; ?>" name="chk_promo_code_id" value="<?php echo $row->promo_code_id; ?>"></td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_promo_code" onclick="promo_code_edit_load(<?php echo $row->promo_code_id; ?>);" class="font-weight-bold"><?php echo $row->promo_code_name; ?></a>
														</div>
													</div>													
												</td>
												<td class="text-center"><?php echo $row->promo_code; ?></td>
												<td class="text-center"><?php echo $row->promo_mode; ?></td>
												<td class="text-center"><?php echo number_format($row->promo_value,2); ?></td>
												<td class="text-center"><?php echo $row->sort_key; ?></td>
												<td class="text-center">
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
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_promo_code" onclick="promo_code_edit_load(<?php echo $row->promo_code_id; ?>);" class="dropdown-item"><i class="icon-pencil6"></i> View/Edit</a>
																<?php if ($sbr_promo_codes_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_promo_code(<?php echo $row->promo_code_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete</a>
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
									$("#chk_promo_codes_0").on('change', function() {
								        if($("#chk_promo_codes_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_promo_code_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_promo_code_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								