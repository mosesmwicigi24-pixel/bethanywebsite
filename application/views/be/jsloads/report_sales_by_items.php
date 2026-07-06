
								<script type="text/javascript">
									$('.table-items-sales-transactions').DataTable({
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
                                 
                                            totalQuantity = api.column( 2 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                                            totalSalesInclTax = api.column( 3 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                                            totalSalesExclTax = api.column( 4 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                                            totalTax = api.column( 5 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                                            totalDiscount = api.column( 6 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                                 
                                            $( api.column( 2 ).footer() ).html(totalQuantity.formatMoney(2,',','.'));
                                            $( api.column( 3 ).footer() ).html(totalSalesInclTax.formatMoney(2,',','.'));
                                            $( api.column( 4 ).footer() ).html(totalSalesExclTax.formatMoney(2,',','.'));
                                            $( api.column( 5 ).footer() ).html(totalTax.formatMoney(2,',','.'));
                                            $( api.column( 6 ).footer() ).html(totalDiscount.formatMoney(2,',','.'));
                                        }
									});
								</script>
								<table class="table table-bordered table-items-sales-transactions">
									<thead>
										<tr>
											<th class="text-center" style="width: 50px">#</th>
											<th style="width: 250px">Name</th>
											<th style="width: 90px" class="text-center">Quantity Sold</th>
											<th style="width: 90px" class="text-center">Sales Incl. Tax (<?php echo $default_currency; ?>)</th>
											<th style="width: 90px" class="text-center">Sales Excl. Tax (<?php echo $default_currency; ?>)</th>
											<th style="width: 90px" class="text-center">Total Tax (<?php echo $default_currency; ?>)</th>
											<th style="width: 90px" class="text-center">Total Discount (<?php echo $default_currency; ?>)</th>
										</tr>
									</thead>
									<tbody>	
										<?php $count = 1; ?>
										<?php foreach ($sales_by_items as $row): ?>
											<?php
				                                $variation_description = '';
				                                if(!empty($row->attributes)){
				                                    foreach ($row->attributes as $row3){
				                                        $variation_description = $variation_description . $row3->product_attribute_name . ' : <b>' . $row3->product_attribute_value . '</b>, ';
				                                    }
				                                    $variation_description =  '<br>~ ' . substr($variation_description,0,-2) . '<br>';
				                                }
				                            ?>
											<tr>	
												<td class="text-center"><?php echo $count; ?></td>
												<td><?php echo $row->product_name; ?><?php echo $variation_description; ?></td>
												<td class="text-center">
													<?php
														if ($transaction_type == ''){
															echo number_format($row->posTotalQuantity + $row->onlineTotalQuantity,2);
														} elseif ($transaction_type == 'POS') {
															echo number_format($row->posTotalQuantity,2);
														} elseif ($transaction_type == 'Online') {
															echo number_format($row->onlineTotalQuantity,2);
														}
													?>
												</td>
												<td class="text-center">
													<?php
														if ($transaction_type == ''){
															echo number_format($row->posTotalSubTotal + $row->onlineTotalSubTotal,2);
														} elseif ($transaction_type == 'POS') {
															echo number_format($row->posTotalSubTotal,2);
														} elseif ($transaction_type == 'Online') {
															echo number_format($row->onlineTotalSubTotal,2);
														}
													?>
												</td>
												<td class="text-center">
													<?php
														if ($transaction_type == ''){
															echo number_format(($row->posTotalSubTotal - $row->posTotalTaxAmount) + ($row->onlineTotalSubTotal - $row->onlineTotalTaxAmount),2);
														} elseif ($transaction_type == 'POS') {
															echo number_format($row->posTotalSubTotal - $row->posTotalTaxAmount,2);
														} elseif ($transaction_type == 'Online') {
															echo number_format($row->onlineTotalSubTotal - $row->onlineTotalTaxAmount,2);
														}
													?>
												</td>
												<td class="text-center">
													<?php
														if ($transaction_type == ''){
															echo number_format($row->posTotalTaxAmount + $row->onlineTotalTaxAmount,2);
														} elseif ($transaction_type == 'POS') {
															echo number_format($row->posTotalTaxAmount,2);
														} elseif ($transaction_type == 'Online') {
															echo number_format($row->onlineTotalTaxAmount,2);
														}
													?>
												</td>
												<td class="text-center">
													<?php
														if ($transaction_type == ''){
															echo number_format($row->posTotalDiscountAmount + $row->onlineTotalDiscountAmount,2);
														} elseif ($transaction_type == 'POS') {
															echo number_format($row->posTotalDiscountAmount,2);
														} elseif ($transaction_type == 'Online') {
															echo number_format($row->onlineTotalDiscountAmount,2);
														}
													?>
												</td>
											</tr>
											<?php $count++; ?>
										<?php endforeach; ?>
									</tbody>
									<tfoot>
	                                    <tr>
	                                        <th class="font-weight-bold" colspan="2" style="text-align:right">Totals:</th>
	                                        <th class="font-weight-bold text-center"></th>
	                                        <th class="font-weight-bold text-center"></th>
	                                        <th class="font-weight-bold text-center"></th>
	                                        <th class="font-weight-bold text-center"></th>
	                                        <th class="font-weight-bold text-center"></th>	                                        
	                                    </tr>
	                                </tfoot>
								</table>


								