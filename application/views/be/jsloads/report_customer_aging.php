										<script type="text/javascript">
											$('.table-customer-aging-report').DataTable({
												iDisplayLength: 50,
												lengthMenu: [50, 100, 150, 200 ],
											    "columnDefs": [
											        { "orderable": false, "targets": [ ] }
											    ],
											    "footerCallback": function ( row, data, start, end, display ) {
		                                            var api = this.api();
		                                 
		                                            var intVal = function ( i ) {
		                                                return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
		                                            };
		                                 
		                                            total_0_30 = api.column( 2 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
		                                            total_31_60 = api.column( 3 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
		                                            total_61_90 = api.column( 4 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
		                                            total_gt_90 = api.column( 5 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
		                                            total_total = api.column( 6 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
		                                 
		                                            $( api.column( 2 ).footer() ).html(total_0_30.formatMoney(2,',','.'));
		                                            $( api.column( 3 ).footer() ).html(total_31_60.formatMoney(2,',','.'));
		                                            $( api.column( 4 ).footer() ).html(total_61_90.formatMoney(2,',','.'));
		                                            $( api.column( 5 ).footer() ).html(total_gt_90.formatMoney(2,',','.'));
		                                            $( api.column( 6 ).footer() ).html(total_total.formatMoney(2,',','.'));
		                                        }
		                                        
											});											
										</script>
										<table class="table table-striped table-bordered table-customer-aging-report" data-auto-responsive="true">
			                                <thead>
			                                    <tr>
			                                        <th>#</th>
			                                        <th>Customer</th>
			                                        <th>0-30 Days</th>
			                                        <th>31-60 Days</th>
			                                        <th>61-90 Days</th>
			                                        <th>90+ Days</th>
			                                        <th>Total</th>
			                                    </tr>
			                                </thead>
			                                <tbody>    
			                                	<?php $count = 1; ?>                   
			                                    <?php foreach ($customer_aging_report as $row): ?>
			                                        <tr>
			                                            <td><?php echo $count; ?></td>
			                                            <td><?php echo $row->first_name . ' ' . $row->last_name; ?></td>
			                                            <td><?php echo number_format($row->age_0_30,2); ?></td>
			                                            <td><?php echo number_format($row->age_31_60,2); ?></td>
			                                            <td><?php echo number_format(($row->age_61_90),2); ?></td>
			                                            <td><?php echo number_format(($row->age_gt_90),2); ?></td>
			                                            <td><?php echo number_format(($row->total_balance),2); ?></td>
			                                        </tr>
			                                        <?php $count++; ?>
			                                    <?php endforeach; ?>
			                                </tbody>
			                                <tfoot>
			                                    <tr>
			                                        <th colspan="2" style="text-align:right">Totals:</th>
			                                        <th></th>
			                                        <th></th>
			                                        <th></th>
			                                        <th></th>
			                                        <th></th>
			                                    </tr>
			                                </tfoot>
			                            </table>
