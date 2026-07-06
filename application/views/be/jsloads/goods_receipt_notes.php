								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 1, "desc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 7] }
									    ],
                                        "footerCallback": function ( row, data, start, end, display ) {
                                            var api = this.api();
                                 
                                            var intVal = function ( i ) {
                                                return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
                                            };
                                 
                                            total = api.column( 5 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                                            $( api.column( 5 ).footer() ).html(total.formatMoney(2,',','.'));
                                        }
									});
								</script>
								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th>GRN #</th>
											<th>Receival Date</th>
											<th>Outlet</th>
											<th>Purchase Order</th>
											<th>Supplier</th>
											<th>Total Amount (<?php echo $default_currency; ?>)</th>
											<th>User</th>
											<th>Status</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($goods_receipt_notes as $row): ?>
											<?php $receival_date = strtotime($row->receival_date); ?>
											<tr class="<?php if ($row->is_void == 1){ echo 'text-grey line-through'; } ?>">
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="<?php echo base_url(); ?>be/inventory/goods_receipt_note_detail/<?php echo $row->goods_receipt_note_id; ?>" class="font-weight-bold"><?php echo $row->goods_receipt_note_number; ?></a>
														</div>
													</div>
												</td>
												<td data-sort="<?php echo $receival_date; ?>"><?php echo date('d M, Y', strtotime($row->receival_date)); ?></td>
												<td><?php echo $row->outlet_name; ?></td>
												<td><?php echo $row->purchase_order_number; ?></td>
												<td><?php echo $row->supplier_name; ?></td>
												<td><?php echo number_format($row->total_amount,2); ?></td>
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
																<?php if ($sbr_goods_received_view == true): ?>
																	<a href="<?php echo base_url(); ?>be/inventory/goods_receipt_note_detail/<?php echo $row->goods_receipt_note_id; ?>" class="dropdown-item"><i class="icon-eye"></i> View Goods Receipt Note</a>
																<?php endif; ?>
																<?php if ($row->is_void == 0): ?>
																	<?php if ($sbr_goods_received_edit == true): ?>
																		<a href="<?php echo base_url(); ?>be/inventory/goods_receipt_note_edit/<?php echo $row->goods_receipt_note_id; ?>" class="dropdown-item"><i class="icon-pencil6"></i> Edit Goods Receipt Note</a>
																	<?php endif; ?>
																<?php endif; ?>
																
																<div class="dropdown-divider"></div>
																<?php if ($sbr_goods_received_print == true): ?>
																	<a href="<?php echo base_url(); ?>be/inventory/goods_receipt_note_print/<?php echo $row->goods_receipt_note_id; ?>" class="dropdown-item" target="_blank"><i class="icon-printer"></i> Print Goods Receipt Note</a>
																<?php endif; ?>
																<a href="#" data-toggle="modal" data-target="#modal_send_goods_receipt_note_via_email" data-goods-receipt-note-id="<?php echo $row->goods_receipt_note_id; ?>" class="dropdown-item lnk_send_goods_receipt_note_via_email"><i class="icon-mail5"></i> Send via Mail</a>
																<?php if ($row->is_void == 0): ?>
																	<?php if ($sbr_goods_received_delete == true): ?>
																		<a href="javascript:;" class="dropdown-item void-goods-receipt-note" data-goods-receipt-note-id="<?php echo $row->goods_receipt_note_id; ?>" data-context="Goods Receipt Notes List"><i class="icon-trash-alt"></i> Void Goods Receipt Note</a>
																	<?php endif; ?>
																<?php endif; ?>
															</div>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
									<tfoot>
                                        <tr>
                                            <th class="font-weight-bold" colspan="5" style="text-align:right">Totals:</th>
                                            <th class="font-weight-bold" colspan="1"></th>     
                                            <th class="font-weight-bold" colspan="2"></th>                                    
                                        </tr>
                                    </tfoot>
								</table>