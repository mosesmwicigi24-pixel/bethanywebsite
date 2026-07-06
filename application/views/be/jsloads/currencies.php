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
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_currencies_0" name="chk_currency_id0" value="0"></th>
											<th style="width: 200px">Country</th>
											<th style="width: 200px">Currency</th>
											<th style="width: 150px" class="text-center">Exchange Rate</th>
											<th style="width: 120px" class="text-center">Default Currency</th>
											<th style="width: 100px" class="text-center">Sort Key</th>
											<th style="width: 80px" class="text-center">Status</th>
											<th style="width: 80px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($currencies as $row): ?>
											<tr>
												<td class="text-center"><input type="checkbox" id="chk_currencies_<?php echo $row->currency_id; ?>" name="chk_currency_id" value="<?php echo $row->currency_id; ?>"></td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_currency" onclick="currency_edit_load(<?php echo $row->currency_id; ?>);" class="font-weight-bold"><?php echo $row->country_name; ?></a>
														</div>
													</div>
												</td>
												<td><?php echo '<b>Name:</b> ' . $row->currency_name . '<br><b>Symbol:</b> ' . $row->currency_symbol; ?></td>
												<td class="text-center"><?php echo $row->exchange_rate; ?></td>
												<td class="text-center">
													<?php if ($row->default_currency == 0): ?>
														<span class="badge badge-secondary">No</span>
													<?php elseif ($row->default_currency == 1): ?>
														<span class="badge badge-success">Yes</span>
													<?php endif; ?>
												</td>
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
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_currency" class="dropdown-item" onclick="currency_edit_load(<?php echo $row->currency_id; ?>);"><i class="icon-pencil6"></i> View/Edit Currency</a>
																<?php if ($sbr_currencies_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_currency(<?php echo $row->currency_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete Currency</a>
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
									$("#chk_currencies_0").on('change', function() {
								        if($("#chk_currencies_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_currency_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_currency_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>