								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 1, "desc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 9 ] }
									    ],
                                        "footerCallback": function ( row, data, start, end, display ) {
                                            var api = this.api();
                                 
                                            var intVal = function ( i ) {
                                                return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
                                            };
                                 
                                            total = api.column( 4 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                                            totalPaid = api.column( 6 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                                            totalBalance = api.column( 7 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );

                                            $( api.column( 4 ).footer() ).html(total.formatMoney(2,',','.'));
                                            $( api.column( 6 ).footer() ).html(totalPaid.formatMoney(2,',','.'));
                                            $( api.column( 7 ).footer() ).html(totalBalance.formatMoney(2,',','.'));
                                        }
									});
								</script>
								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th>PO #</th>
											<th>Order Date</th>
											<th>Expected Date</th>
											<th>Supplier</th>
											<th>Total Amount (<?php echo $default_currency; ?>)</th>											
											<th>Status</th>
											<th>Total Paid (<?php echo $default_currency; ?>)</th>
											<th>Balance (<?php echo $default_currency; ?>)</th>
											<th>Payment Status</th>
											<th>User</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($purchase_orders as $row): ?>
											<?php $purchase_order_date = strtotime($row->purchase_order_date); ?>
											<tr class="<?php if ($row->is_void == 1){ echo 'text-grey line-through'; } ?>">
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="<?php echo base_url(); ?>be/inventory/purchase_order_detail/<?php echo $row->purchase_order_id; ?>" class="font-weight-bold"><?php echo $row->purchase_order_number; ?></a>
														</div>
													</div>
												</td>
												<td data-sort="<?php echo $purchase_order_date; ?>"><?php echo date('d M, Y', strtotime($row->purchase_order_date)); ?></td>
												<td><?php if ($row->expected_date != ''){ echo date('d M, Y', strtotime($row->expected_date)); } ?></td>
												<td><?php echo $row->supplier_name; ?></td>
												<td><?php echo number_format($row->total_amount,2); ?></td>
												<td>
													<?php if ($row->is_void == 1): ?>
														<span class="badge badge-danger badge-pill font-11">Voided</span>
													<?php else: ?>
														<?php if ($row->total_received_qty == 0): ?>
															<span class="badge bg-grey badge-pill font-11">Unreceived</span>
														<?php elseif ($row->total_received_qty < $row->total_detail_qty): ?>
															<span class="badge badge-info badge-pill font-11">Partially Received</span>
														<?php elseif ($row->total_received_qty == $row->total_detail_qty): ?>
															<span class="badge badge-success badge-pill font-11">Received</span>
														<?php endif; ?>
													<?php endif; ?>
												</td>
												<td>
													<?php echo number_format($row->total_paid,2); ?>
												</td>
												<td>
													<?php 
														if ($row->total_paid >= $row->total_amount){
															echo number_format(0,2);
														} else{
															echo number_format($row->total_amount - $row->total_paid,2);
	                                                    }
													?>
												</td>
												<td>
													<?php if ($row->is_void == 1): ?>
														<?php echo '&mdash;'; ?>
													<?php else: ?>
														<?php if ($row->total_paid == 0): ?>
	                                                        <span class="badge bg-grey badge-pill font-11">Unpaid</span>
	                                                    <?php elseif ($row->total_paid > 0 && $row->total_paid < $row->total_amount): ?>
	                                                        <span class="badge badge-info badge-pill font-11">Partially Paid</span>
	                                                    <?php else: ?>
	                                                        <span class="badge badge-success badge-pill font-11">Paid</span>
	                                                    <?php endif; ?>
	                                                <?php endif; ?>
												</td>
												<td><?php echo $row->first_name . ' ' . $row->last_name; ?></td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<?php if ($sbr_purchase_orders_view == true): ?>
																	<a href="<?php echo base_url(); ?>be/inventory/purchase_order_detail/<?php echo $row->purchase_order_id; ?>" class="dropdown-item"><i class="icon-eye"></i> View Purchase Order</a>
																<?php endif; ?>
																<?php if ($row->is_void == 0): ?>
																	<?php if ($sbr_purchase_orders_edit == true): ?>
																		<a href="<?php echo base_url(); ?>be/inventory/purchase_order_edit/<?php echo $row->purchase_order_id; ?>" class="dropdown-item"><i class="icon-pencil6"></i> Edit Purchase Order</a>
																	<?php endif; ?>
																	<?php //if ($sbr_purchase_orders_edit == true): ?>
																		<a href="javascript:;" href="javascript:;" data-purchase-order-id="<?php echo $row->purchase_order_id; ?>" data-context="Purchase Orders List" class="dropdown-item purchase_order_record_payment"><i class="icon-coins"></i> Record Payment</a>
																	<?php //endif; ?>
																<?php endif; ?>
																
																<div class="dropdown-divider"></div>
																<?php if ($sbr_purchase_orders_print == true): ?>
																	<a href="<?php echo base_url(); ?>be/inventory/purchase_order_print_supplier/<?php echo $row->purchase_order_id; ?>" class="dropdown-item" target="_blank"><i class="icon-printer"></i> Print Supplier's Copy</a>
																	<a href="<?php echo base_url(); ?>be/inventory/purchase_order_print/<?php echo $row->purchase_order_id; ?>" class="dropdown-item" target="_blank"><i class="icon-printer"></i> Print Local Copy</a>
																<?php endif; ?>
																
																<!-- <a href="<?php echo base_url(); ?>be/inventory/purchase_order_pdf/<?php echo $row->purchase_order_id; ?>" class="dropdown-item" target="_blank"><i class="icon-file-pdf"></i> Export to PDF</a> -->
																<a href="#" data-toggle="modal" data-target="#modal_send_purchase_order_via_email" data-purchase-order-id="<?php echo $row->purchase_order_id; ?>" class="dropdown-item lnk_send_purchase_order_via_email"><i class="icon-mail5"></i> Send via Mail</a>
																<?php if ($row->is_void == 0): ?>
																	<?php if ($sbr_purchase_orders_delete == true): ?>
																		<a href="javascript:;" class="dropdown-item void-purchase-order" data-purchase-order-id="<?php echo $row->purchase_order_id; ?>" data-context="Purchase Orders List"><i class="icon-trash-alt"></i> Void Purchase Order</a>
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
                                            <th class="font-weight-bold" colspan="4" style="text-align:right">Totals:</th>
                                            <th class="font-weight-bold" colspan="1"></th>     
                                            <th class="font-weight-bold" colspan="1"></th>  
                                            <th class="font-weight-bold" colspan="1"></th>
                                            <th class="font-weight-bold" colspan="1"></th>                                   
                                        </tr>
                                    </tfoot>
								</table>