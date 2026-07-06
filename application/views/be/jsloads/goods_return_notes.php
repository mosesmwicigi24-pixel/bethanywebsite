								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 1, "desc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 8 ] }
									    ]
									});
								</script>
								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th>GREN #</th>
											<th>Return Date</th>
											<th>Outlet</th>
											<th>Supplier</th>
											<th>Total Amount (<?php echo $default_currency; ?>)</th>
											<th>Reason</th>
											<th>User</th>
											<th>Status</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($goods_return_notes as $row): ?>
											<?php $return_date = strtotime($row->return_date); ?>
											<tr class="<?php if ($row->is_void == 1){ echo 'text-grey line-through'; } ?>">
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="<?php echo base_url(); ?>be/inventory/goods_return_note_detail/<?php echo $row->goods_return_note_id; ?>" class="font-weight-bold"><?php echo $row->goods_return_note_number; ?></a>
														</div>
													</div>
												</td>
												<td data-sort="<?php echo $return_date; ?>"><?php echo date('d M, Y', strtotime($row->return_date)); ?></td>
												<td><?php echo $row->outlet_name; ?></td>
												<td><?php echo $row->supplier_name; ?></td>
												<td><?php echo number_format($row->total_amount,2); ?></td>
												<td><?php echo $row->return_reason; ?></td>
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
																<?php if ($sbr_goods_returned_view == true): ?>
																	<a href="<?php echo base_url(); ?>be/inventory/goods_return_note_detail/<?php echo $row->goods_return_note_id; ?>" class="dropdown-item"><i class="icon-eye"></i> View Goods Return Note</a>
																<?php endif; ?>
																<?php if ($row->is_void == 0): ?>
																	<?php if ($sbr_goods_returned_edit == true): ?>
																		<a href="<?php echo base_url(); ?>be/inventory/goods_return_note_edit/<?php echo $row->goods_return_note_id; ?>" class="dropdown-item"><i class="icon-pencil6"></i> Edit Goods Return Note</a>
																	<?php endif; ?>
																<?php endif; ?>
																
																<div class="dropdown-divider"></div>
																<?php if ($sbr_goods_returned_edit == true): ?>
																	<a href="<?php echo base_url(); ?>be/inventory/goods_return_note_print/<?php echo $row->goods_return_note_id; ?>" class="dropdown-item" target="_blank"><i class="icon-printer"></i> Print Goods Return Note</a>
																<?php endif; ?>
																<!-- <a href="<?php echo base_url(); ?>be/inventory/goods_return_note_pdf/<?php echo $row->goods_return_note_id; ?>" class="dropdown-item" target="_blank"><i class="icon-file-pdf"></i> Export to PDF</a> -->
																<a href="#" data-toggle="modal" data-target="#modal_send_goods_return_note_via_email" data-goods-return-note-id="<?php echo $row->goods_return_note_id; ?>" class="dropdown-item lnk_send_goods_return_note_via_email"><i class="icon-mail5"></i> Send via Mail</a>
																<?php if ($row->is_void == 0): ?>
																	<?php if ($sbr_goods_returned_delete == true): ?>
																		<a href="javascript:;" class="dropdown-item void-goods-return-note" data-goods-return-note-id="<?php echo $row->goods_return_note_id; ?>" data-context="Goods Return Notes List"><i class="icon-trash-alt"></i> Void Goods Return Note</a>
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