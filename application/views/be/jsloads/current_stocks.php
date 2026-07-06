												<script type="text/javascript">
													$('#table_report_stock_<?php echo $outlet_id; ?>').DataTable({
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
				                                 
				                                            totalStockValue = api.column( 5 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
				                                            totalGrossProfit = api.column( 6 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
				                                 
				                                            $( api.column( 5 ).footer() ).html(totalStockValue.formatMoney(2,',','.'));
				                                            $( api.column( 6 ).footer() ).html(totalGrossProfit.formatMoney(2,',','.'));
				                                        }
													});
												</script>
												<div class="row">
													<div class="col-md-12 text-right">
														<button id="btn_sales_by_items_export" type="button" onclick="export_report_stock(<?php echo $outlet_id; ?>);" class="btn btn-primary btn-sm font-weight-600"><i class="icon-file-pdf"></i> Export</button>
													</div>
												</div>
												<table class="table table-bordered" id="table_report_stock_<?php echo $outlet_id; ?>">
													<thead>
														<tr>
															<th style="width: 200px">Product</th>
															<th style="width: 100px" class="text-center">Reorder Level</th>
															<th style="width: 100px" class="text-center">Current Stock</th>
				                                            <th style="width: 100px" class="text-center">Average Cost Price (<?php echo $default_currency; ?>)</th>
				                                            <th style="width: 100px" class="text-center">Selling Price (<?php echo $default_currency; ?>)</th>
				                                            <th style="width: 100px" class="text-center">Stock Value (Est.) (<?php echo $default_currency; ?>)</th>
				                                            <th style="width: 100px" class="text-center">Gross Profit (<?php echo $default_currency; ?>)</th>
				                                            <th style="width: 90px" class="text-center">Margin (%)</th>
														</tr>
													</thead>
													<tbody>
														<?php foreach ($current_stocks as $row2): ?>
															<?php
																if ($row2->sale_price > 0){ $selling_price = $row2->sale_price; } else { $selling_price = $row2->regular_price; }
																$average_cost_price = 0;
																if ($row2->StockIn != 0){
																	$average_cost_price = ($row2->StockIn / $row2->QtyStockIn);
																} else {
																	$average_cost_price = 0;
																}
																if ($selling_price == 0) {
																	$margin = 0;
																} else {
																	$margin = (($selling_price-$average_cost_price)/$selling_price) * 100;
																}
															?>
															<?php
								                                $variation_description = '';
								                                if(!empty($row2->attributes)){
								                                    foreach ($row2->attributes as $row3){
								                                        $variation_description = $variation_description . $row3->product_attribute_name . ' : <b>' . $row3->product_attribute_value . '</b>, ';
								                                    }
								                                    $variation_description =  '<br>~ ' . substr($variation_description,0,-2) . '<br>';
								                                }
								                            ?>
				                                            <tr>
				                                            	<td><?php echo $row2->product_name; ?><?php echo $variation_description; ?></td>
				                                            	<td class="text-center"><?php echo number_format($row2->reorder_level,2); ?></td>
				                                                <td class="text-center"><?php echo number_format($row2->available_stock,2); ?></td>
				                                                <td class="text-center"><?php echo number_format($average_cost_price,2); ?></td>
				                                                <td class="text-center"><?php echo number_format($selling_price,2) ?></td>
				                                                <td class="text-center"><?php echo number_format($average_cost_price * $row2->available_stock,2); ?></td>
				                                                <td class="text-center"><?php echo number_format($selling_price - $average_cost_price,2); ?></td>
				                                                <td class="text-center"><?php echo number_format($margin,2); ?></td>
				                                            </tr>
														<?php endforeach; ?>
													</tbody>
													<tfoot>
					                                    <tr>
					                                        <th class="font-weight-bold" colspan="5" style="text-align:right">Totals:</th>
					                                        <th class="font-weight-bold text-center"></th>
					                                        <th class="font-weight-bold text-center"></th>
					                                        <th class="font-weight-bold text-center"></th>
					                                    </tr>
					                                </tfoot>
												</table>