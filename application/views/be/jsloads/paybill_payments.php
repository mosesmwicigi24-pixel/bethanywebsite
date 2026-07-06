								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 4, "desc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ ] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th style="width: 100px">Reference #</th>
                                            <th style="width: 200px">Name</th>
                                            <th style="width: 120px">Phone #</th>
                                            <th style="width: 120px">Account #</th>
                                            <th style="width: 120px">Date</th>
                                            <th style="width: 120px">Amount</th>
                                            <th style="width: 150px">Payment For</th>
                                            <th style="width: 70px">Status</th>
                                            <th style="width: 70px">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($paybill_payments as $row): ?>
											<?php $sort_date = strtotime($row->transaction_time); ?>
                                            <tr>
                                                <td><b><a href="<?php echo base_url(); ?>be/payments/paybill_payment/<?php echo $row->paybill_payment_id; ?>"><?php echo $row->transaction_id; ?></a></b></td>
                                                <td><?php echo $row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name; ?></td>
                                                <td><?php echo $row->MSISDN; ?></td>
                                                <td><?php echo $row->bill_reference_number; ?></td>
                                                <td data-sort="<?php echo $sort_date; ?>"><?php echo date('d M, Y H:i:s', strtotime($row->transaction_time)); ?></td>
                                                <td>KES <?php echo number_format((float)$row->transaction_amount, 2, '.', ','); ?></td>
                                                <td>
                                                	<?php if ($row->ord_order_number != ''): ?>
                                                        Online Order : <a href="<?php echo base_url(); ?>be/sales/online_order/<?php echo $row->ord_order_number; ?>"><b><?php echo $row->ord_order_number; ?></b></a>
                                                    <?php elseif ($row->pos_sale_id != 0): ?>
                                                        POS Order : <b>SO-<?php echo $row->pos_sale_id; ?></b>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                	<?php if ($row->transaction_completed == 1): ?>
                                                        <span class="badge badge-success"><i class="icon-checkmark2"></i> Closed</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-secondary">Pending</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                	<div class="list-icons">
														<div class="dropdown">
															<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown"><b><i class="icon-cog5"></i></b> Action</button>

															<div class="dropdown-menu dropdown-menu-right">
																<?php //if ($sbr_online_sales_view == true): ?>
																	<a href="<?php echo base_url(); ?>be/payments/paybill_payment/<?php echo $row->paybill_payment_id; ?>" class="dropdown-item"><i class="icon-eye"></i> View Payment</a>
																<?php //endif; ?>

																<?php if ($row->transaction_completed == 0): ?>
																	<div class="dropdown-divider"></div>
	                                                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#modal_assign_paybill_payment_transaction" onclick="return paybill_payment_assign_transaction_load(<?php echo $row->paybill_payment_id;?>);"><i class="icon-credit-card"></i> Assign Transaction</a>
	                                                            <?php endif; ?>
																
															</div>
														</div>
													</div>
                                                </td>
                                            </tr>
										<?php endforeach; ?>
									</tbody>
								</table>

