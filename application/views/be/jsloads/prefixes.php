								<script type="text/javascript">
									$('.datatable-basic').DataTable();
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th style="width: 200px">Document</th>
											<th style="width: 120px">Prefix</th>
											<th style="width: 150px">Current Value</th>
											<th style="width: 90px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($prefixes as $row): ?>
											<tr>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_prefix" onclick="prefix_edit_load(<?php echo $row->prefix_id; ?>);" class="font-weight-bold"><?php echo $row->document_name; ?></a>
														</div>
													</div>
												</td>
												<td><?php echo $row->prefix_name; ?></td>
												<td><?php echo $row->current_value; ?></td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_prefix" class="dropdown-item" onclick="prefix_edit_load(<?php echo $row->prefix_id; ?>);"><i class="icon-pencil6"></i> Edit prefix</a>
															</div>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>