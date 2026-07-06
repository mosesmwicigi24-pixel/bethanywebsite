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
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_tax_rates_0" name="chk_tax_rate_id0" value="0"></th>
											<th style="width: 200px">Name</th>
											<th style="width: 150px">Code</th>
											<th style="width: 150px">Value (%)</th>
											<th style="width: 120px" class="text-center">Sort Key</th>
											<th style="width: 90px" class="text-center">Status</th>
											<th style="width: 90px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($tax_rates as $row): ?>
											<tr>
												<td class="text-center"><input type="checkbox" id="chk_tax_rates_<?php echo $row->tax_rate_id; ?>" name="chk_tax_rate_id" value="<?php echo $row->tax_rate_id; ?>"></td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_tax_rate" onclick="tax_rate_edit_load(<?php echo $row->tax_rate_id; ?>);" class="font-weight-bold"><?php echo $row->tax_rate_name; ?></a>
														</div>
													</div>													
												</td>
												<td><?php echo $row->tax_rate_code; ?></td>
												<td><?php echo $row->tax_rate_value; ?></td>
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
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_tax_rate" onclick="tax_rate_edit_load(<?php echo $row->tax_rate_id; ?>);" class="dropdown-item"><i class="icon-pencil6"></i> Edit Tax Rate</a>
																<?php if ($sbr_tax_rates_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_tax_rate(<?php echo $row->tax_rate_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete Tax Rate</a>
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
									$("#chk_tax_rates_0").on('change', function() {
								        if($("#chk_tax_rates_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_tax_rate_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_tax_rate_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								