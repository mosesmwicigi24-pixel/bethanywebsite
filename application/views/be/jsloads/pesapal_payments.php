								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 0, "desc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ ] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th style="width: 120px">Date</th>
											<th style="width: 100px">Payment Method</th>
                                            <th style="width: 200px">Customer</th>
                                            <th style="width: 120px">Amount</th>
                                            <th style="width: 150px">Payment For</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($pesapal_payments as $row): ?>
											<?php $sort_date = strtotime($row->created_on); ?>
                                            <tr>
                                            	<td data-sort="<?php echo $sort_date; ?>"><?php echo date('d M, Y H:i:s', strtotime($row->created_on)); ?></td>
                                            	<td><?php echo $row->payment_method; ?></td>
                                                <td><a href="<?php echo base_url(); ?>be/customers/edit/<?php echo $row->customer_id; ?>"><?php echo '<b>' . $row->first_name . ' ' . $row->last_name . '</b><br><i>' . $row->email_address . '</i>'; ?></a></td>
                                                <td><?php echo number_format((float)$row->transaction_amount, 2, '.', ','); ?></td>
                                                <td>
                                            		Online Order : <a href="<?php echo base_url(); ?>be/sales/online_order/<?php echo $row->ord_order_number; ?>"><b><?php echo $row->ord_order_number; ?></b></a>
                                                </td>
                                            </tr>
										<?php endforeach; ?>
									</tbody>
								</table>



								