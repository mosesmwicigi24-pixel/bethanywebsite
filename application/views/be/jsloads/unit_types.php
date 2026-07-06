								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										"order": [[ 3, "asc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 0, 3, 4 ] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_unit_types_0" name="chk_unit_type_id0" value="0"></th>
											<th style="width: 120px">Name</th>
											<th style="width: 180px">Description</th>
											<th style="width: 90px">Sort Key</th>
											<th style="width: 90px">Status</th>
											<th style="width: 90px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($unit_types as $row): ?>
											<tr>
												<td class="text-center"><input type="checkbox" id="chk_unit_types_<?php echo $row->unit_type_id; ?>" name="chk_unit_type_id" value="<?php echo $row->unit_type_id; ?>"></td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_unit_type" onclick="unit_type_edit_load(<?php echo $row->unit_type_id; ?>);" class="font-weight-bold"><?php echo $row->unit_type_name; ?></a>
														</div>
													</div>													
												</td>
												<td><?php echo $row->unit_type_description; ?></td>
												<td><?php echo $row->sort_key; ?></td>
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
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_unit_type" onclick="unit_type_edit_load(<?php echo $row->unit_type_id; ?>);" class="dropdown-item"><i class="icon-pencil6"></i> View/Edit</a>
																<?php if ($sbr_units_of_measure_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_unit_type(<?php echo $row->unit_type_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete</a>
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
									$("#chk_unit_types_0").on('change', function() {
								        if($("#chk_unit_types_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_unit_type_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_unit_type_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								