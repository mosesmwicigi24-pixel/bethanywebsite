												<script type="text/javascript">
													$('#table_report_low_stocks_<?php echo $outlet_id; ?>').DataTable({
														iDisplayLength: 50,
														lengthMenu: [50, 100, 150, 200 ],
														"order": [[ 0, "asc" ]],
													    "columnDefs": [
													        { "orderable": false, "targets": [ ] }
													    ]
													});
												</script>
												<div class="row">
													<div class="col-md-12 text-right">
														<button type="button" onclick="export_report_low_stocks(<?php echo $outlet_id; ?>);" class="btn btn-primary btn-sm font-weight-600"><i class="icon-file-pdf"></i> Export</button>
														<button type="button" onclick="low_stocks_create_purchase_order(<?php echo $outlet_id; ?>);" class="btn btn-outline btn-outline-primary btn-sm font-weight-600"><i class="icon-plus-circle2"></i> Create Purchase Order</button>
													</div>
												</div>
												<table class="table table-bordered" id="table_report_low_stocks_<?php echo $outlet_id; ?>">
													<thead>
														<tr>
															<th style="width: 250px">Product</th>
															<th style="width: 100px" class="text-center">Reorder Level</th>
															<th style="width: 100px" class="text-center">Current Stock</th>
															<th style="width: 100px" class="text-center">Difference</th>
				                                            <th style="width: 100px" class="text-center">Selling Price (<?php echo $default_currency; ?>)</th>
														</tr>
													</thead>
													<tbody>	
														<?php foreach ($low_stocks as $row2): ?>
															<?php
																if ($row2->sale_price > 0){ $selling_price = $row2->sale_price; } else { $selling_price = $row2->regular_price; }
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
				                                                <td class="text-center"><?php echo number_format($row2->reorder_level - $row2->available_stock,2); ?></td>
				                                                <td class="text-center"><?php echo number_format($selling_price,2) ?></td>
				                                            </tr>
														<?php endforeach; ?>
													</tbody>
												</table>