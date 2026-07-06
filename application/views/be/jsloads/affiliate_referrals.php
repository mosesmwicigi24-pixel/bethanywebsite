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
											<th style="width: 150px">Order #</th>
											<th style="width: 150px">Order Amount</th>
											<th style="width: 130px">Commission(%)</th>
											<th style="width: 150px">Commission Amount</th>
											<th style="width: 150px">Commission Balance</th>
											<th style="width: 130px">Date</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($affiliate_referrals as $row): ?>
											<tr>
												<td><?php echo $row->ord_order_number; ?></td>
												<td><?php echo number_format($row->order_amount,2); ?></td>
                                                <td><?php echo number_format($row->commission); ?></td>
                                                <td><?php echo number_format($row->commission_amount,2); ?></td>
                                                <td><?php echo number_format($row->commissions_balance,2); ?></td>
                                                <td><?php echo date('jS M Y H:i:s', strtotime($row->created_on)); ?></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>

