								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 1, "desc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 4 ] }
									    ]
									});
								</script>
								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th style="width: 100px">Reference #</th>
											<th style="width: 100px">Write-off Date</th>
											<th style="width: 150px">Outlet</th>
											<th style="width: 150px">User</th>
											<th style="width: 100px">Status</th>
											<th style="width: 90px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($stock_writeoffs as $row): ?>
											<?php $writeoff_date = strtotime($row->created_on); ?>
											<tr class="<?php if ($row->is_void == 1){ echo 'text-grey line-through'; } ?>">
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="<?php echo base_url(); ?>be/inventory/stock_writeoff_detail/<?php echo $row->stock_writeoff_id; ?>" class="font-weight-bold"><?php echo $row->stock_writeoff_number; ?></a>
														</div>
													</div>
												</td>
												<td data-sort="<?php echo $writeoff_date; ?>"><?php echo date('d M, Y', strtotime($row->writeoff_date)); ?></td>
												<td><?php echo $row->outlet_name; ?></td>
												<td><?php echo $row->first_name . ' ' . $row->last_name; ?></td>
												<td>
													<?php if ($row->is_void == 1): ?>
														<span class="badge badge-danger badge-pill font-11">Voided</span>
													<?php else: ?>
														<span class="badge badge-success badge-pill font-11">Active</span>
													<?php endif; ?>
												</td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<?php if ($sbr_stock_writeoffs_view == true): ?>
																	<a href="<?php echo base_url(); ?>be/inventory/stock_writeoff_detail/<?php echo $row->stock_writeoff_id; ?>" class="dropdown-item"><i class="icon-eye"></i> View Stock Write-off</a>
																<?php endif; ?>
																<?php if ($row->is_void == 0): ?>
																	<?php if ($sbr_stock_writeoffs_edit == true): ?>
																		<a href="<?php echo base_url(); ?>be/inventory/stock_writeoff_edit/<?php echo $row->stock_writeoff_id; ?>" class="dropdown-item"><i class="icon-pencil6"></i> Edit Stock Write-off</a>
																	<?php endif; ?>
																<?php endif; ?>

																<div class="dropdown-divider"></div>
																<?php if ($sbr_stock_writeoffs_print == true): ?>
																	<a href="<?php echo base_url(); ?>be/inventory/stock_writeoff_print/<?php echo $row->stock_writeoff_id; ?>" class="dropdown-item" target="_blank"><i class="icon-printer"></i> Print Stock Write-off</a>
																<?php endif; ?>
																<!-- <a href="<?php echo base_url(); ?>be/inventory/stock_writeoff_pdf/<?php echo $row->stock_writeoff_id; ?>" class="dropdown-item" target="_blank"><i class="icon-file-pdf"></i> Export to PDF</a> -->
																<a href="#" data-toggle="modal" data-target="#modal_send_stock_writeoff_via_email" data-stock-writeoff-id="<?php echo $row->stock_writeoff_id; ?>" class="dropdown-item lnk_send_stock_writeoff_via_email"><i class="icon-mail5"></i> Send via Mail</a>
																<?php if ($row->is_void == 0): ?>
																	<?php if ($sbr_stock_writeoffs_delete == true): ?>
																		<a href="javascript:;" class="dropdown-item void-stock-writeoff" data-stock-writeoff-id="<?php echo $row->stock_writeoff_id; ?>" data-context="Stock Write-offs List"><i class="icon-trash-alt"></i> Void Stock Write-off</a>
																	<?php endif; ?>
																<?php endif; ?>
															</div>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>