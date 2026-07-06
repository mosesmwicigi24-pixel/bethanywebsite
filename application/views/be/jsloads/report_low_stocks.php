								<div class="d-md-flex">
									<ul class="nav nav-tabs nav-tabs-vertical flex-column mr-md-3 wmin-md-200 mb-md-0 border-bottom-0">
										<?php $i = 0; ?>
										<?php foreach ($low_stocks_report as $row): ?>
											<li class="nav-item"><a href="#outlet-tab<?php echo $row->outlet_id; ?>" class="nav-link <?php if ($i == 0){ echo 'active'; } ?>" data-toggle="tab"><i class="icon-office mr-2"></i> <?php echo $row->outlet_name; ?></a></li>
											<?php $i++; ?>
										<?php endforeach; ?>
									</ul>

									<div class="tab-content w-100">
										<?php $i = 0; ?>
										<?php foreach ($low_stocks_report as $row): ?>
											<div class="tab-pane fade <?php if ($i == 0){ echo 'show active'; } ?>" id="outlet-tab<?php echo $row->outlet_id; ?>">
												<script type="text/javascript">
													$('#table_report_low_stocks_<?php echo $row->outlet_id; ?>').DataTable({
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
														<button id="btn_sales_by_items_export" type="button" onclick="export_report_low_stocks(<?php echo $row->outlet_id; ?>);" class="btn btn-primary btn-sm font-weight-600"><i class="icon-file-pdf"></i> Export</button>
													</div>
												</div>
												<table class="table table-bordered" id="table_report_low_stocks_<?php echo $row->outlet_id; ?>">
													<thead>
														<tr>
															<th style="width: 250px">Product</th>
															<th style="width: 100px" class="text-center">Reorder Level</th>
															<th style="width: 100px" class="text-center">Current Stock</th>
															<th style="width: 100px" class="text-center">Difference</th>
				                                            <th style="width: 100px" class="text-center">Selling Price (<?php echo $default_currency; ?>)<?php echo $default_currency; ?></th>
														</tr>
													</thead>
													<tbody>	
														<?php if(!empty($row->inventory)): ?>
															<?php foreach ($row->inventory as $row2): ?>
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
														<?php endif; ?>
													</tbody>
												</table>
											</div>
											<?php $i++; ?>
										<?php endforeach; ?>
									</div>
								</div>