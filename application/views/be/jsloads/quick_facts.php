								<script type="text/javascript">
									$('.datatable-basic').DataTable();
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th>Title</th>
											<th>Value</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($quick_facts as $row): ?>
											<tr>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_quick_fact" onclick="quick_fact_edit_load(<?php echo $row->quick_fact_id; ?>);" class="font-weight-bold"><?php echo $row->quick_fact_title; ?></a>
														</div>
													</div>
												</td>
												<td><?php echo $row->quick_fact_value; ?></td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_quick_fact" class="dropdown-item" onclick="quick_fact_edit_load(<?php echo $row->quick_fact_id; ?>);"><i class="icon-pencil6"></i> Edit Quick Fact</a>																
																<a href="javascript:void(0);" onclick="delete_quick_fact(<?php echo $row->quick_fact_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete Quick Fact</a>
															</div>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>