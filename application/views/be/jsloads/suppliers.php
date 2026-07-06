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
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_suppliers_0" name="chk_supplier_id0" value="0"></th>
											<th style="width: 200px">Supplier Name</th>
											<th style="width: 150px">Email</th>
											<th style="width: 120px">Phone</th>
											<th style="width: 110px">Sort Key</th>
											<th style="width: 90px">Status</th>
											<th style="width: 90px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($suppliers as $row): ?>
											<tr>												
												<td class="text-center"><input type="checkbox" id="chk_suppliers_<?php echo $row->supplier_id; ?>" name="chk_supplier_id" value="<?php echo $row->supplier_id; ?>"></td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_supplier" onclick="supplier_edit_load(<?php echo $row->supplier_id; ?>);" class="font-weight-bold"><?php echo $row->supplier_name; ?></a>
															<?php if ($row->supplier_code != ''): ?>
																<br><b>Code: </b><?php echo $row->supplier_code; ?>
															<?php endif; ?>
														</div>
													</div>													
												</td>
												<td><?php echo $row->email_address; ?></td>
												<td><?php echo $row->phone_number; ?></td>
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
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_supplier" onclick="supplier_edit_load(<?php echo $row->supplier_id; ?>);" class="dropdown-item"><i class="icon-pencil6"></i> View/Edit Supplier</a>
																<?php if ($sbr_suppliers_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_supplier(<?php echo $row->supplier_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete Supplier</a>
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
									$("#chk_suppliers_0").on('change', function() {
								        if($("#chk_suppliers_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_supplier_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_supplier_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								