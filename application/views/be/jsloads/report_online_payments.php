
								<script type="text/javascript">
									$('.datatable-basic2').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 4, "desc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ ] }
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
								<table class="table  datatable-basic2 table-bordered">
									<thead>
										<tr>
											<th style="width: 100px">Reference #</th>
                                            <th style="width: 200px">Name</th>
                                            <th style="width: 120px">Phone #</th>
                                            <th style="width: 120px">Account #</th>
                                            <th style="width: 120px">Date</th>
                                            <th style="width: 120px">Amount (<?php echo $default_currency; ?>)</th>
                                            <th style="width: 150px">Payment For</th>
                                            <th style="width: 70px">Status</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($online_payments as $row): ?>
											<?php $sort_date = strtotime($row->transaction_time); ?>
                                            <tr>
                                                <td><b><a href="<?php echo base_url(); ?>be/payments/paybill_payment/<?php echo $row->paybill_payment_id; ?>"><?php echo $row->transaction_id; ?></a></b></td>
                                                <td><?php echo $row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name; ?></td>
                                                <td><?php echo $row->MSISDN; ?></td>
                                                <td><?php echo $row->bill_reference_number; ?></td>
                                                <td data-sort="<?php echo $sort_date; ?>"><?php echo date('d M, Y H:i:s', strtotime($row->transaction_time)); ?></td>
                                                <td><?php echo number_format((float)$row->transaction_amount, 2, '.', ','); ?></td>
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
                                            </tr>
										<?php endforeach; ?>
									</tbody>
									<tfoot>
	                                    <tr>
	                                        <th class="font-weight-bold" colspan="5" style="text-align:right">Total:</th>
	                                        <th class="font-weight-bold" colspan="3"></th>	                                        
	                                    </tr>
	                                </tfoot>
								</table>


								