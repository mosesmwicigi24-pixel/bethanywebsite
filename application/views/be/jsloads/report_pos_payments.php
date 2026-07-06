
								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 0, "asc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ ] }
									    ],
                                        "footerCallback": function ( row, data, start, end, display ) {
                                            var api = this.api();
                                 
                                            var intVal = function ( i ) {
                                                return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
                                            };
                                 
                                            total = api.column( 3 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );                                 
                                            $( api.column( 3 ).footer() ).html(total.formatMoney(2,',','.'));
                                        }
									});
								</script>
								<table class="table  datatable-basic table-bordered">
									<thead>
										<tr>
											<th class="text-center" style="width: 50px">#</th>
											<th style="width: 100px">Date</th>
											<th style="width: 90px">Sale Order</th>
											<th style="width: 90px">Amount (<?php echo $default_currency; ?>)</th>
											<th style="width: 90px">Payment Method</th>
											<th style="width: 90px">Reference #</th>
											<th style="width: 90px">Payment Note</th>
										</tr>
									</thead>
									<tbody>	
										<?php $count = 1; ?>
										<?php foreach ($pos_payments as $row): ?>
											<tr>	
												<td class="text-center"><?php echo $count; ?></td>
												<td><?php echo date('d M, Y', strtotime($row->created_on)); ?></td>
												<td>#REC<?php echo $row->pos_sale_id; ?></td>
												<td><?php echo number_format($row->payment_amount,2); ?></td>
												<td><?php echo $row->payment_method; ?></td>
												<td><?php echo $row->reference_number; ?></td>
												<td><?php echo $row->payment_note; ?></td>
											</tr>
											<?php $count++; ?>
										<?php endforeach; ?>
									</tbody>
									<tfoot>
	                                    <tr>
	                                        <th class="font-weight-bold" colspan="3" style="text-align:right">Total:</th>
	                                        <th class="font-weight-bold" colspan="4"></th>	                                        
	                                    </tr>
	                                </tfoot>
								</table>


								