								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 1, "desc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 5, 6] }
									    ]
									});

									$('.pop-over').popover({
										placement: 'top'
									});

								</script>
								<table class="table datatable-basic table-striped table-bordered">
									<thead>
										<tr>
											<th style="width: 100px">Order #</th>
											<th style="width: 150px">Order Date</th>
											<th style="width: 250px">Customer</th>
											<th style="width: 100px">Total Amount</th>
											<th style="width: 100px">Shipping Method</th>
											<th style="width: 100px">Payment Method</th>
											<th style="width: 90px" class="text-center">Status</th>
											<th style="width: 90px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($online_sales as $row): ?>
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
												<!-- <td><?php echo date('d M, Y', strtotime($row->online_sale_date)); ?></td> -->
												<td>
													<a class="pop-over" data-html="true" data-popup="popover" title="<b><i class='icon-user'></i> <?php echo $row->first_name . ' ' . $row->last_name; ?></b>" data-trigger="hover" data-content="<i class='icon-envelop4 icon-sm'></i> <?php echo $row->email_address; ?> <br><i class='icon-phone2 icon-sm'></i> <?php echo $row->phone_number; ?>" href="<?php echo base_url(); ?>be/customers/edit/<?php echo $row->customer_id; ?>"><?php echo $row->first_name . ' ' . $row->last_name; ?></a>
												</td>
												<td><?php echo 'KES ' . number_format($row->ord_total,2); ?></td>
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
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown"><b><i class="icon-cog5"></i></b> Action</button>

															<div class="dropdown-menu dropdown-menu-right">
																<?php if ($sbr_online_sales_view == true): ?>
																	<a href="<?php echo base_url(); ?>be/sales/online_order/<?php echo $row->ord_order_number; ?>" class="dropdown-item"><i class="icon-eye"></i> View Sale</a>
																<?php endif; ?>
																<?php if ($sbr_online_sales_manage == true): ?>
																	<?php if ($row->ord_order_status == 1): ?>
																		<?php if ($row->ord_payment_method == 'Pesapal'): ?>
																			<?php if ($row->pesapal_payment_id != '' && $row->pesapal_payment_id != null): ?>
																				<a href="#" data-toggle="modal" data-target="#modal_dispatch_online_order" data-ord-order-number="<?php echo $row->ord_order_number; ?>" class="dropdown-item lnk_dispatch_online_order"><i class="icon-cart-remove"></i> Dispatch Order</a>
																			<?php else: ?>
																				<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_verify_pesapal_payment" onclick="verify_pesapal_payment_load('<?php echo $row->ord_order_number; ?>', 'List');" class="dropdown-item"><i class="icon-cart-remove"></i> Dispatch Order</a>
																			<?php endif; ?>
																		<?php else: ?>
																			<a href="#" data-toggle="modal" data-target="#modal_dispatch_online_order" data-ord-order-number="<?php echo $row->ord_order_number; ?>" class="dropdown-item lnk_dispatch_online_order"><i class="icon-cart-remove"></i> Dispatch Order</a>
																		<?php endif; ?>
																	<?php endif; ?>
																	<?php if ($row->ord_order_status == 2): ?>
																		<a href="javascript:void(0);" onclick="complete_order('<?php echo $row->ord_order_number; ?>');" class="dropdown-item"><i class="icon-checkmark-circle2"></i> Complete Order</a>
																	<?php endif; ?>
																<?php endif; ?>
																<div class="dropdown-divider"></div>

																<a href="<?php echo base_url(); ?>be/sales/online_order_print/<?php echo $row->ord_order_number; ?>" class="dropdown-item" target="_blank"><i class="icon-printer"></i> Print Order</a>
																<a href="<?php echo base_url(); ?>be/sales/online_order_pdf/<?php echo $row->ord_order_number; ?>" class="dropdown-item" target="_blank"><i class="icon-file-pdf"></i> Export to PDF</a>
																<a href="#" data-toggle="modal" data-target="#modal_send_online_order_via_email" data-ord-order-number="<?php echo $row->ord_order_number; ?>" class="dropdown-item lnk_send_online_order_via_email"><i class="icon-mail5"></i> Send Order via Mail [As Attachment]</a>
																<div class="dropdown-divider"></div>
																<a href="#" data-toggle="modal" data-target="#modal_send_online_order_customer_email" data-ord-order-number="<?php echo $row->ord_order_number; ?>" class="dropdown-item lnk_send_online_order_customer_email"><i class="icon-envelop3"></i> Send Customer Email</a>
															</div>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>