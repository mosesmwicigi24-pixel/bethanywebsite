								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 1, "asc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 0, 6] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_outlets_0" name="chk_outlet_id0" value="0"></th>
											<th style="width: 150px">Name</th>
											<th style="width: 150px">Location</th>
											<th style="width: 150px">Contact Person</th>
											<th style="width: 150px">Phone Number</th>
											<th style="width: 70px" class="text-center">Main</th>
											<th style="width: 70px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($outlets as $row): ?>
											<tr>
												<td class="text-center"><input type="checkbox" id="chk_outlets_<?php echo $row->outlet_id; ?>" name="chk_outlet_id" value="<?php echo $row->outlet_id; ?>"></td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_outlet" onclick="outlet_edit_load(<?php echo $row->outlet_id; ?>);" class="font-weight-bold"><?php echo $row->outlet_name; ?></a>
														</div>
													</div>													
												</td>
												<td><?php echo $row->outlet_physical_location; ?></td>
												<td><?php echo $row->outlet_contact_person; ?></td>
												<td><?php echo $row->outlet_phone_number; ?></td>
												<td>
													<?php if ($row->is_main == 1): ?>
														<span class="badge badge-success">Yes</span>
													<?php else: ?>
														<span class="badge badge-secondary">No</span>
													<?php endif; ?>
												</td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_outlet" onclick="outlet_edit_load(<?php echo $row->outlet_id; ?>);" class="dropdown-item"><i class="icon-pencil6"></i> View/Edit Outlet</a>
																<?php if ($sbr_outlets_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_outlet(<?php echo $row->outlet_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete Outlet</a>
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
									$("#chk_outlets_0").on('change', function() {
								        if($("#chk_outlets_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_outlet_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_outlet_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								