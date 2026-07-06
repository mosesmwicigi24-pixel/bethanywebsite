										
										<script type="text/javascript">
											$('.table-online-sales-transactions').DataTable({
												iDisplayLength: 50,
												lengthMenu: [50, 100, 150, 200 ],
												"order": [[ 1, "desc" ]],
											    "columnDefs": [
											        { "orderable": false, "targets": [ ] }
											    ],
		                                        "footerCallback": function ( row, data, start, end, display ) {
		                                            var api = this.api();
		                                 
		                                            var intVal = function ( i ) {
		                                                return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
		                                            };
		                                 
		                                            total = api.column( 3 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
		                                 
		                                            pageTotal = api.column( 3, { page: 'current'} ).data().reduce( function (a, b) {
		                                                return intVal(a) + intVal(b);
		                                            }, 0 );
		                                 
		                                            $( api.column( 3 ).footer() ).html(total.formatMoney(2,',','.'));
		                                            // '$'+pageTotal +' ( $'+ total +' total)'
		                                        }
											});
										</script>
										<table class="table table-striped table-bordered table-online-sales-transactions">
											<thead>
												<tr>
													<th style="width: 100px">Order #</th>
													<th style="width: 150px">Order Date</th>
													<th style="width: 250px">Customer</th>
													<th style="width: 100px">Total Amount (<?php echo $default_currency; ?>)</th>
													<th style="width: 100px">Shipping Method</th>
													<th style="width: 100px">Payment Method</th>
													<th style="width: 90px" class="text-center">Status</th>
												</tr>
											</thead>
											<tbody>	
												<?php foreach ($online_sales_transactions as $row): ?>
													<?php $ord_date = strtotime($row->ord_date); ?>
													<tr>
														<td>
															<div class="media">
																<div class="media-body align-self-center">
																	<i class="icon-bag"></i> <a href="<?php echo base_url(); ?>be/sales/online_order/<?php echo $row->ord_order_number; ?>" class="font-weight-bold"><?php echo $row->ord_order_number; ?></a>
																</div>
															</div>
														</td>
														<td data-sort="<?php echo $ord_date; ?>"><?php echo date('d M, Y', strtotime($row->ord_date)); ?></td>
														<td>
															<a class="pop-over" data-html="true" data-popup="popover" title="<b><i class='icon-user'></i> <?php echo $row->first_name . ' ' . $row->last_name; ?></b>" data-trigger="hover" data-content="<i class='icon-envelop4 icon-sm'></i> <?php echo $row->email_address; ?> <br><i class='icon-phone2 icon-sm'></i> <?php echo $row->phone_number; ?>" href="<?php echo base_url(); ?>be/customers/edit/<?php echo $row->customer_id; ?>"><?php echo $row->first_name . ' ' . $row->last_name; ?></a>
														</td>
														<td><?php echo number_format($row->ord_total,2); ?></td>
														<td><?php echo $row->ord_shipping_method; ?></td>
														<td><?php echo $row->ord_payment_method; ?></td>
														<td class="text-center">
															<?php if ($row->ord_order_status == 0): ?>
		                                                        <span class="badge badge-pill badge-warning"><i class="icon-hour-glass3 icon-sm"></i> Awaiting Payment</span>
		                                                    <?php elseif ($row->ord_order_status == 1): ?>
		                                                        <span class="badge badge-pill badge-info"><i class="icon-spinner2 icon-sm"></i> Processing</span>
		                                                    <?php elseif ($row->ord_order_status == 2): ?>
		                                                        <span class="badge badge-pill badge-primary"><i class="icon-cart-remove icon-sm"></i> Dispatched</span>
		                                                    <?php elseif ($row->ord_order_status == 3): ?>
		                                                        <span class="badge badge-pill badge-success"><i class="icon-checkmark-circle2 icon-sm"></i> Completed</span>
		                                                    <?php elseif ($row->ord_order_status == 4): ?>
		                                                        <span class="badge badge-pill badge-danger"><i class="icon-cancel-circle2 icon-sm"></i> Cancelled</span>
		                                                    <?php endif; ?>
														</td>
													</tr>
												<?php endforeach; ?>
											</tbody>
											<tfoot>
			                                    <tr>
			                                        <th class="font-weight-bold" colspan="3" style="text-align:right">Total:</th>
			                                        <th class="font-weight-bold"></th>
			                                        <th colspan="3" style="text-align:left"></th>
			                                    </tr>
			                                </tfoot>
										</table>