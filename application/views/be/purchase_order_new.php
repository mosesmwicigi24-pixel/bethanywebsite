		<!-- Main content -->
		<div class="content-wrapper">

			<?php if (!isset($purchase_order)): ?>

				<!-- Page header -->
				<div class="page-header">
					<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
						<div class="d-flex">
							<div class="breadcrumb">
								<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
								<a href="#" class="breadcrumb-item">Inventory</a>
								<a href="<?php echo base_url();?>be/inventory/purchase_orders" class="breadcrumb-item">Purchase Orders</a>
								<span class="breadcrumb-item active">New Purchase Order</span>
							</div>

							<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
						</div>
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content pt-0">
					<!-- <div class="mb-3">
						<h5 class="mb-0 font-weight-semibold">
							New Purchase Order
						</h5>
					</div> -->

					<form id="frm_add_purchase_order" name="frm_add_purchase_order" method="post" onsubmit="return save_purchase_order();" autocomplete="false">
						<div class="spinner2 display-none" id="purchase_order_loader">
	                        <div class="rect1"></div>
	                        <div class="rect2"></div>
	                        <div class="rect3"></div>
	                    </div>
						<div class="row">
							<div class="col-md-1"></div>
							<div class="col-md-10">
								<div class="card rounded-top-0">
									<div class="card-header bg-transparent header-elements-inline p-2">
										<h5 class="card-title font-weight-bold"><i class="icon-plus-circle2"></i> New Purchase Order</h5>			
										<div class="header-elements">
											<a href="<?php echo base_url(); ?>be/inventory/purchase_orders" class="btn btn-sm btn-primary ml-2"><i class="icon-list3 mr-1"></i> Purchase Orders List</a>	
										</div>
									</div>
							
									<div class="card-body">
										<div id="div_add_purchase_order_error" class="alert alert-danger display-none font-13"></div>
		                   				<div id="div_add_purchase_order_success" class="alert alert-success display-none font-13"></div>

										<div class="form-group mb-3">
											<div class="row">
												<div class="col-sm-6">
													<label>Supplier <span class="error">*</span></label>
													<select id="supplier_id" name="supplier_id" data-placeholder="Select Supplier" class="form-control select" data-fouc>
														<option value="">Select Supplier</option>
														<?php foreach ($suppliers as $row): ?>
															<option value="<?php echo $row->supplier_id; ?>"><?php echo $row->supplier_name; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-sm-3">
													<label>Order Date <span class="error">*</span></label>
													<input id="purchase_order_date" name="purchase_order_date" type="text" placeholder="" class="form-control pickadate" data-value="<?php echo date('Y-m-d'); ?>">
												</div>
												<div class="col-sm-3">
													<label>Expected Date</label>
													<input id="expected_date" name="expected_date" type="text" placeholder="" class="form-control pickadate">
												</div>
											</div>
										</div>
										
										<div class="form-group mb-4">
											<div class="row">
												<div class="col-sm-6">
													<label>Product Quick Search</label>
													<div class="input-group">
														<span class="input-group-prepend">
															<span class="input-group-text bg-primary border-primary text-white">
																<i class="icon-search4"></i>
															</span>
														</span>
														<input id="purchase_order_quick_search" name="purchase_order_quick_search" type="text" class="form-control border-left-0" placeholder="Search by product name, product sku code or product barcode">
													</div>
												</div>
												<div class="col-sm-6">
													<label>Or Select a Product Here</label>
													<div class="form-group form-group-feedback form-group-feedback-right mb-2">
														<select id="purchase_order_product_id" name="product_id" data-placeholder="Select Product" class="form-control select" data-fouc>
															<option value="">Select Product</option>
															<?php foreach ($products as $row2): ?>
																<option value="<?php echo $row2->product_id; ?>"><?php echo $row2->product_name; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											</div>
										</div>
										<div class="table-responsive mb-3">
											<table class="table table-bordered table-striped">
												<thead>
													<tr>
														<th width="250px">Product</th>
														<th width="120px">Unit</th>
														<th width="100px">Order Qty</th>
														<th width="170px">Cost</th>														
														<th width="120px">Total</th>
														<th width="50px">Remove</th>
													</tr>
												</thead>
												<tbody id="purchase_order_details_table">
													<?php if (isset($low_stocks)): ?>
														<?php foreach ($low_stocks as $row2): ?>
															<?php
																$line_id = $row2->product_id . $row2->product_variation_id;
																// if ($row2->sale_price > 0){ $selling_price = $row2->sale_price; } else { $selling_price = $row2->regular_price; }
															?>
															<tr>
																<td>
																	<?php echo $row2->product_name; ?><br>
																	<?php if(!empty($row2->attributes)): ?>
												                		<?php
												                			$variation_description = '';
												                			foreach ($row2->attributes as $row3){
												                				$variation_description = $variation_description . $row3->product_attribute_name . ' : <b>' . $row3->product_attribute_value . '</b>, ';
												                			}
												                			echo '<i class="badge badge-mark ml-2"></i> ' . substr($variation_description,0,-2);
												                		?><br>
																	<?php endif; ?>
																	<div class="text-muted font-size-sm pt-0"><b>SKU:</b><?php echo $row2->product_sku_code; ?></div>
																	<input id="po_detail_id_<?php echo $line_id; ?>" name="po_detail_id[]" type="hidden" class="form-control po_detail_id" value="<?php echo $line_id; ?>">
																	<input id="po_detail_product_id_<?php echo $line_id; ?>" name="po_detail_product_id[]" type="hidden" class="po_detail_product_id" value="<?php echo $row2->product_id; ?>">
																	<input id="po_detail_product_variation_id_<?php echo $line_id; ?>" name="po_detail_product_variation_id[]" type="hidden" class="po_detail_product_variation_id" value="<?php echo $row2->product_variation_id; ?>">
																</td>
																<td>
																	<select class="form-control select-basic po_unit_id" data-placeholder="Select Unit" id="po_unit_id_<?php echo $line_id; ?>" name="po_unit_id[]" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                        								<option value="">Select Unit</option>
                                        								<?php if(!empty($row2->units)): ?>
                                        									<?php foreach ($row2->units as $row3): ?>
                                        										<?php
	                                        										$unit_price = $row2->regular_price;
														                            $selected = '';

														                            if ($row2->unit_id != $row3->unit_id && $row3->unit_price != 0) {
														                                $unit_price = $row3->unit_price;
														                            }
														                            if ($row2->unit_id == $row3->unit_id) {
														                                $selected = 'selected';
														                            }
                                        											
																              	?>
                                        										<option value="<?php echo $row3->unit_id; ?>" data-unit-price="<?php echo $unit_price; ?>" data-line-id="<?php echo $line_id; ?>" <?php echo $selected; ?>><?php echo $row3->unit_name . ' (' . $row3->unit_code . ')'; ?></option>
                                        									<?php endforeach; ?>
                                        								<?php endif; ?>
                                        							</select>
																</td>
																<td>
																	<input id="po_detail_qty_<?php echo $line_id; ?>" name="po_detail_qty[]" type="number" class="form-control po_detail_qty" min="1" value="<?php echo $row2->reorder_level - $row2->available_stock; ?>" autocomplete="off">
																</td>
																<td>
																	<input id="po_detail_cost_<?php echo $line_id; ?>" name="po_detail_cost[]" type="number" class="form-control po_detail_cost" value="<?php echo $row2->regular_price; ?>" autocomplete="off">
																</td>
																<td>
																	<span id="po_label_detail_total_<?php echo $line_id; ?>"><?php echo number_format(($row2->reorder_level - $row2->available_stock) * $row2->regular_price,2); ?></span>
																	<input id="po_detail_total_<?php echo $line_id; ?>" name="po_detail_total[]" type="hidden" class="form-control po_detail_total" value="<?php echo ($row2->reorder_level - $row2->available_stock) * $row2->regular_price; ?>">
																</td>
																<td><a href="javascript:void(0);" class="po_detail_remove" title="Remove product"><i class="icon-cancel-circle2 text-danger"></i></a></td>
															</tr>
														<?php endforeach; ?>
													<?php endif; ?>
													<tr>
														<td colspan="4" class="text-right"><strong>Total Qty:</strong></td>
														<td><strong><span id="po_label_total_detail_qty">0</span></strong><input id="po_total_detail_qty" name="po_total_detail_qty" type="hidden" class="form-control" value="0"></td>
														<td></td>
													</tr>
													<tr>
														<td colspan="4" class="text-right"><strong>Subtotal:</strong></td>
														<td><strong><?php echo $default_currency; ?> <span id="po_label_total_detail_subtotal">0.00</span></strong><input id="po_total_detail_subtotal" name="po_total_detail_subtotal" type="hidden" class="form-control" value="0"></td>
														<td></td>
													</tr>
													<tr>
														<td colspan="4" class="text-right"><strong>Freight:</strong></td>
														<td><input id="po_freight_cost" name="freight_cost" type="number" class="form-control" value="0.00" min="0"></td>
														<td></td>
													</tr>
													<tr>
														<td colspan="4" class="text-right"><strong>Total:</strong></td>
														<td><strong><?php echo $default_currency; ?> <span id="po_label_total_detail_total">0.00</span></strong><input id="po_total_detail_total" name="po_total_detail_total" type="hidden" class="form-control" value="0"></td>
														<td></td>
													</tr>
												</tbody>
											</table>
										</div>
										<div class="form-group mb-3">
											<div class="row">
												<div class="col-sm-12">
													<label>Purchase Order Note:</label>
													<textarea id="purchase_order_note" name="purchase_order_note" class="form-control" rows="3"></textarea>
												</div>
											</div>
										</div>
									</div>
								</div>								
								<hr>
								<div class="text-right">
									<button type="submit" id="btn_add_purchase_order" class="btn bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> SAVE PURCHASE ORDER</button>
								</div>
							</div>
							<div class="col-md-1"></div>	
						</div>
					</form>
				</div>
				<script>
					$(document).ready(function() {
						calculate_po_totals();
					});
				</script>
			<?php else: ?>
				<?php foreach ($purchase_order as $row): ?>
					<!-- Page header -->
					<div class="page-header">
						<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
							<div class="d-flex">
								<div class="breadcrumb">
									<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
									<a href="<?php echo base_url();?>be/inventory/purchase_orders" class="breadcrumb-item">Purchase Orders</a>
									<span class="breadcrumb-item active">Edit Purchase Order (<?php echo $row->purchase_order_number; ?>)</span>
								</div>

								<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
							</div>
						</div>
					</div>
					<!-- /page header -->


					<!-- Content area -->
					<div class="content pt-0">
						<!-- <div class="mb-3">
							<h5 class="mb-0 font-weight-semibold">
								Edit Purchase Order (<?php echo $row->purchase_order_number; ?>)
							</h5>
						</div> -->

						<form id="frm_edit_purchase_order" name="frm_edit_purchase_order" method="post" onsubmit="return update_purchase_order();" autocomplete="false">
							<div class="spinner2 display-none" id="purchase_order_loader">
		                        <div class="rect1"></div>
		                        <div class="rect2"></div>
		                        <div class="rect3"></div>
		                    </div>
							<div class="row">
								<div class="col-md-1"></div>
								<div class="col-md-10">
									<div class="card rounded-top-0">
										<div class="card-header bg-transparent header-elements-inline p-2">
											<h5 class="card-title font-weight-bold">Edit Purchase Order <b>(<?php echo $row->purchase_order_number; ?>)</b></h5>	
											<div class="header-elements">
												<a href="<?php echo base_url(); ?>be/inventory/purchase_orders" class="btn btn-sm btn-primary ml-2"><i class="icon-list3 mr-1"></i> Purchase Orders List</a>	
											</div>		
										</div>
								
										<div class="card-body">
											<div id="div_edit_purchase_order_error" class="alert alert-danger display-none font-13"></div>
			                   				<div id="div_edit_purchase_order_success" class="alert alert-success display-none font-13"></div>

			                   				<input id="purchase_order_id" name="purchase_order_id" type="hidden" value="<?php echo $row->purchase_order_id; ?>">

											<div class="form-group mb-3">
												<div class="row">
													<div class="col-sm-6">
														<label>Supplier <span class="error">*</span></label>
														<select id="supplier_id" name="supplier_id" data-placeholder="Select Supplier" class="form-control select" data-fouc>
															<option value="">Select Supplier</option>
															<?php foreach ($suppliers as $row2): ?>
																<option value="<?php echo $row2->supplier_id; ?>" <?php if ($row->supplier_id == $row2->supplier_id){echo 'selected';} ?>><?php echo $row2->supplier_name; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
													<div class="col-sm-3">
														<label>Order Date <span class="error">*</span></label>
														<input id="purchase_order_date" name="purchase_order_date" type="text" placeholder="" class="form-control pickadate" value="<?php echo $row->purchase_order_date; ?>" autocomplete="false">
													</div>
													<div class="col-sm-3">
														<label>Expected Date</label>
														<input id="expected_date" name="expected_date" type="text" placeholder="" class="form-control pickadate" value="<?php echo $row->expected_date; ?>" autocomplete="false">
													</div>
												</div>
											</div>
											<div class="form-group mb-4">
												<div class="row">
													<div class="col-sm-6">
														<label>Product Quick Search</label>
														<div class="input-group">
															<span class="input-group-prepend">
																<span class="input-group-text bg-primary border-primary text-white">
																	<i class="icon-search4"></i>
																</span>
															</span>
															<input id="purchase_order_quick_search" name="purchase_order_quick_search" type="text" class="form-control border-left-0" placeholder="Search by product name, product sku code or product barcode">
														</div>
													</div>
													<div class="col-sm-6">
														<label>Or Select a Product Here</label>
														<div class="form-group form-group-feedback form-group-feedback-right mb-2">
															<select id="purchase_order_product_id" name="product_id" data-placeholder="Select Product" class="form-control select" data-fouc>
																<option value="">Select Product</option>
																<?php foreach ($products as $row2): ?>
																	<option value="<?php echo $row2->product_id; ?>"><?php echo $row2->product_name; ?></option>
																<?php endforeach; ?>
															</select>
														</div>
													</div>
												</div>
											</div>
											<div class="table-responsive mb-3">
												<table class="table table-bordered table-striped">
													<thead>
														<tr>
															<th width="250px">Product</th>
															<th width="120px">Unit</th>
															<th width="100px">Order Qty</th>
															<th width="120px">Cost</th>														
															<th width="120px">Total</th>
															<th width="50px">Remove</th>
														</tr>
													</thead>
													<tbody id="purchase_order_details_table">
														<?php foreach ($purchase_order_details as $row2): ?>
															<?php
																$line_id = $row2->product_id . $row2->product_variation_id;
															?>
															<tr>
																<td>
																	<?php echo $row2->product_name; ?><br>
																	<?php if(!empty($row2->attributes)): ?>
												                		<?php
												                			$variation_description = '';
												                			foreach ($row2->attributes as $row3){
												                				$variation_description = $variation_description . $row3->product_attribute_name . ' : <b>' . $row3->product_attribute_value . '</b>, ';
												                			}
												                			echo '<i class="badge badge-mark ml-2"></i> ' . substr($variation_description,0,-2);
												                		?><br>
																	<?php endif; ?>
																	<div class="text-muted font-size-sm pt-0"><b>SKU:</b><?php echo $row2->product_sku_code; ?></div>
																	<input id="po_detail_id_<?php echo $line_id; ?>" name="po_detail_id[]" type="hidden" class="form-control po_detail_id" value="<?php echo $line_id; ?>">
																	<input id="po_detail_product_id_<?php echo $line_id; ?>" name="po_detail_product_id[]" type="hidden" class="po_detail_product_id" value="<?php echo $row2->product_id; ?>">
																	<input id="po_detail_product_variation_id_<?php echo $line_id; ?>" name="po_detail_product_variation_id[]" type="hidden" class="po_detail_product_variation_id" value="<?php echo $row2->product_variation_id; ?>">
																</td>
																<td>
																	<select class="form-control select-basic po_unit_id" data-placeholder="Select Unit" id="po_unit_id_<?php echo $line_id; ?>" name="po_unit_id[]" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                        								<option value="">Select Unit</option>
                                        								<?php if(!empty($row2->units)): ?>
                                        									<?php foreach ($row2->units as $row3): ?>
                                        										<?php
                                        											$unit_price = $row3->regular_price;
																                    $selected = '';
																                    if ($row3->unit_price != '' && $row3->unit_price != 0 && $row3->unit_price != null) {
																                        $unit_price = $row3->unit_price;
																                    }
																                    if ($row2->unit_id == $row3->unit_id) {
																                        $selected = 'selected';
																                    }                                        											
																              	?>
                                        										<option value="<?php echo $row3->unit_id; ?>" data-unit-price="<?php echo $unit_price; ?>" data-line-id="<?php echo $line_id; ?>" <?php echo $selected; ?>><?php echo $row3->unit_name . ' (' . $row3->unit_code . ')'; ?></option>
                                        									<?php endforeach; ?>
                                        								<?php endif; ?>
                                        							</select>
																</td>
																<td>
																	<input id="po_detail_qty_<?php echo $line_id; ?>" name="po_detail_qty[]" type="number" class="form-control po_detail_qty" min="1" value="<?php echo $row2->detail_quantity; ?>" autocomplete="off">
																</td>
																<td>
																	<input id="po_detail_cost_<?php echo $line_id; ?>" name="po_detail_cost[]" type="number" class="form-control po_detail_cost" value="<?php echo $row2->unit_price; ?>" autocomplete="off">
																</td>
																<td>
																	<span id="po_label_detail_total_<?php echo $line_id; ?>"><?php echo number_format($row2->detail_total_amount,2); ?></span>
																	<input id="po_detail_total_<?php echo $line_id; ?>" name="po_detail_total[]" type="hidden" class="form-control po_detail_total" value="<?php echo $row2->detail_total_amount; ?>">
																</td>
																<td><a href="javascript:void(0);" class="po_detail_remove" title="Remove product"><i class="icon-cancel-circle2 text-danger"></i></a></td>
															</tr>
														<?php endforeach; ?>
														<tr>
															<td colspan="4" class="text-right"><strong>Total Qty:</strong></td>
															<td><strong><span id="po_label_total_detail_qty"><?php echo number_format($row->total_quantity); ?></span></strong><input id="po_total_detail_qty" name="po_total_detail_qty" type="hidden" class="form-control" value="<?php echo $row->total_quantity; ?>"></td>
															<td></td>
														</tr>
														<tr>
															<td colspan="4" class="text-right"><strong>Subtotal:</strong></td>
															<td><strong><?php echo $default_currency; ?> <span id="po_label_total_detail_subtotal"><?php echo number_format($row->sub_total,2); ?></span></strong><input id="po_total_detail_subtotal" name="po_total_detail_subtotal" type="hidden" class="form-control" value="<?php echo $row->sub_total; ?>"></td>
															<td></td>
														</tr>
														<tr>
															<td colspan="4" class="text-right"><strong>Freight:</strong></td>
															<td><input id="po_freight_cost" name="freight_cost" type="number" class="form-control" value="<?php echo $row->freight_cost; ?>" min="0"></td>
															<td></td>
														</tr>
														<tr>
															<td colspan="4" class="text-right"><strong>Total:</strong></td>
															<td><strong><?php echo $default_currency; ?> <span id="po_label_total_detail_total"><?php echo number_format($row->total_amount,2); ?></span></strong><input id="po_total_detail_total" name="po_total_detail_total" type="hidden" class="form-control" value="<?php echo $row->total_amount; ?>"></td>
															<td></td>
														</tr>
													</tbody>
												</table>
											</div>
											<div class="form-group mb-3">
												<div class="row">
													<div class="col-sm-12">
														<label>Purchase Order Note:</label>
														<textarea id="purchase_order_note" name="purchase_order_note" class="form-control" rows="3"><?php echo $row->purchase_order_note; ?></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>								
									<hr>
									<div class="text-right">
										<button type="submit" id="btn_edit_purchase_order" class="btn bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE PURCHASE ORDER</button>
									</div>
								</div>
								<div class="col-md-1"></div>	
							</div>
						</form>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>

			<div id="modal_po_select_product_variation" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-plus-circle2"></i> Select Variation</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>
						<div class="modal-body" style="min-height: 200px;">
							<div id="div_po_select_product_variation">
								
							</div>
						</div>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				$(document).ready(function() {
					//PURCHASE ORDER ADD
					$('#purchase_order_quick_search').autocomplete({
				        minLength: 1,
				        source: baseDir + 'be/inventory/get_auto_purchase_order_products',
				        search: function() {
				            $(this).parent().addClass('ui-autocomplete-processing');
				        },
				        open: function() {
				            $(this).parent().removeClass('ui-autocomplete-processing');
				        },
				        focus: function( event, ui ) {
				            //$('#purchase_order_quick_search').val(ui.item.label);
				            return false;
				        },
				        select: function( event, ui ) {
				            $('#purchase_order_quick_search').val(ui.item.label);

				            var product_id = ui.item.id;
				            var product_variation_id = '0';
				            var line_id = '' + product_id + product_variation_id;

				            var product_unit_select = '<select class="form-control select-basic po_unit_id" data-placeholder="Select Unit" id="po_unit_id_' + line_id + '" name="po_unit_id[]" style="width: 100%;" tabindex="-1" aria-hidden="true" required> \
                              	<option value="">Select Unit</option>';
	                        $.each(ui.item.units, function(index2, element2) {
	                            var unit_price = ui.item.price;
	                            var selected = '';
	                            if (ui.item.unit_id != element2.unit_id && element2.unit_price != 0) {
	                                unit_price = element2.unit_price;
	                            }
	                            if (ui.item.unit_id == element2.unit_id) {
	                                selected = 'selected';
	                            }
	                            product_unit_select = product_unit_select + '<option value="' + element2.unit_id + '" data-unit-price="' + unit_price + '" data-line-id="' +line_id + '" ' + selected + '>' + element2.unit_name + ' (' + element2.unit_code + ')</option>';
	                        });
	                        product_unit_select = product_unit_select + '</select>';

				            if (ui.item.type == 'Simple') {
				            	if($('#po_detail_qty_' + line_id).length && $('#po_detail_qty_' + line_id).val().length){
					            	var detailqty = parseFloat($('#po_detail_qty_' + line_id).val()) + 1;
					            	$('#po_detail_qty_' + line_id).val(detailqty);
					            }else{
					            	$('#purchase_order_details_table tr:first').before('<tr> \
					            		<td>' + ui.item.label + '<br><div class="text-muted font-size-sm pt-0"><b>SKU:</b>' + ui.item.desc + '</div><input id="po_detail_id_' + line_id + '" name="po_detail_id[]" type="hidden" class="po_detail_id" value="' + line_id + '"><input id="po_detail_product_id_' + line_id + '" name="po_detail_product_id[]" type="hidden" class="po_detail_product_id" value="' + product_id + '"><input id="po_detail_product_variation_id_' + line_id + '" name="po_detail_product_variation_id[]" type="hidden" class="po_detail_product_variation_id" value="' + product_variation_id + '"></td> \
					            		<td>' + product_unit_select + '</td> \
					            		<td><input id="po_detail_qty_' + line_id + '" name="po_detail_qty[]" type="number" class="form-control po_detail_qty" min="1" value="1" autocomplete="off"></td> \
					            		<td><input id="po_detail_cost_' + line_id + '" name="po_detail_cost[]" type="number" class="form-control po_detail_cost" value="' + ui.item.price + '" autocomplete="off" style="min-width: 120px"></td> \
					            		<td><span id="po_label_detail_total_' + line_id + '">0.00</span><input id="po_detail_total_' + line_id + '" name="po_detail_total[]" type="hidden" class="form-control po_detail_total" value="0"></td> \
					            		<td><a href="javascript:void(0);" class="po_detail_remove" title="Remove product"><i class="icon-cancel-circle2 text-danger"></i></a></td> \
					            	</tr>');
					            }
					            calculate_po_detail_total(line_id);
					            calculate_po_totals();
				            } else {

				            	$.ajax({
						            type: 'POST',
						            url: baseDir + 'be/products/loadjs_select_product_variations',
						            data: { product_id: product_id, context: 'purchase_order_new'},
						            beforeSend: function(){
						                $("#purchase_order_loader").fadeIn("fast");
						            },
						            success: function(res){
						            	$("#purchase_order_loader").fadeOut("fast");
						                $('#div_po_select_product_variation').html(res);
						                $('#modal_po_select_product_variation').modal('toggle');
						            },
						            error: function(){
						            	$("#purchase_order_loader").fadeOut("fast");
					            	}
						        });
				            }
				            $('.select-basic').select2({
	                            placeholder: "Enter at least 1 character"
	                        });
				            $('#purchase_order_quick_search').val('');
				            return false;
				        }
				    }).autocomplete('instance')._renderItem = function(ul, item) {
				        return $('<li>').append('<span class="font-weight-semibold pb-0">' + item.label + '</span>' + '<div class="text-muted font-size-sm pt-0"><b>SKU:</b>&nbsp;' + item.desc + '</div>').appendTo(ul);
				    };
				});

				$("#purchase_order_product_id").on('change', function() {
		    		if (this.value != ''){
			    		$.ajax({
			                type: 'POST',
			                url: baseDir+'be/products/get_product/' + $("#purchase_order_product_id").val(),
			                data: '',
			                dataType: 'json',
			                success: function(res){
			                    $.each(res, function(index, element) {
			                    	var product_id = element.product_id;
						            var product_variation_id = '0';
						            var line_id = '' + product_id + product_variation_id;

						            var product_unit_select = '<select class="form-control select-basic po_unit_id" data-placeholder="Select Unit" id="po_unit_id_' + line_id + '" name="po_unit_id[]" style="width: 100%;" tabindex="-1" aria-hidden="true" required> \
                                                        <option value="">Select Unit</option>';
			                        $.each(element.units, function(index2, element2) {
			                            var unit_price = element.regular_price;
			                            var selected = '';
			                            if (element.unit_id != element2.unit_id && element2.unit_price != 0) {
			                                unit_price = element2.unit_price;
			                            }
			                            if (element.unit_id == element2.unit_id) {
			                                selected = 'selected';
			                            }
			                            product_unit_select = product_unit_select + '<option value="' + element2.unit_id + '" data-unit-price="' + unit_price + '" data-line-id="' +line_id + '" ' + selected + '>' + element2.unit_name + ' (' + element2.unit_code + ')</option>';
			                        });
			                        product_unit_select = product_unit_select + '</select>';

			                    	if (element.product_type == 'Simple'){
				                    	if($('#po_detail_qty_' + line_id).length && $('#po_detail_qty_' + line_id).val().length){
							            	var detailqty = parseFloat($('#po_detail_qty_' + line_id).val()) + 1;
							            	$('#po_detail_qty_' + line_id).val(detailqty);
							            }else{
							            	var productPrice = 0;
											if (element.sale_price > 0){ productPrice = element.sale_price; } else { productPrice = element.regular_price; }
							            	$('#purchase_order_details_table tr:first').before('<tr> \
							            		<td>' + element.product_name + '<br><div class="text-muted font-size-sm pt-0"><b>SKU:</b>' + element.product_sku_code + '</div><input id="po_detail_id_' + line_id + '" name="po_detail_id[]" type="hidden" class="form-control po_detail_id" value="' + line_id + '"><input id="po_detail_product_id_' + line_id + '" name="po_detail_product_id[]" type="hidden" class="po_detail_product_id" value="' + product_id + '"> \
							            		<input id="po_detail_product_variation_id_' + line_id + '" name="po_detail_product_variation_id[]" type="hidden" class="po_detail_product_variation_id" value="' + product_variation_id + '"></td> \
							            		<td>' + product_unit_select + '</td> \
							            		<td><input id="po_detail_qty_' + line_id + '" name="po_detail_qty[]" type="number" class="form-control po_detail_qty" min="1" value="1" autocomplete="off"></td> \
							            		<td><input id="po_detail_cost_' + line_id + '" name="po_detail_cost[]" type="number" class="form-control po_detail_cost" value="' + productPrice + '" autocomplete="off" style="min-width: 120px"></td> \
							            		<td><span id="po_label_detail_total_' + line_id + '">0.00</span><input id="po_detail_total_' + line_id + '" name="po_detail_total[]" type="hidden" class="form-control po_detail_total" value="0"></td> \
							            		<td><a href="javascript:void(0);" class="po_detail_remove" title="Remove product"><i class="icon-cancel-circle2 text-danger"></i></a></td>\
							            	</tr>');
							            }

										calculate_po_detail_total(line_id);
					            		calculate_po_totals();
					            	} else {
					            		$.ajax({
								            type: 'POST',
								            url: baseDir + 'be/products/loadjs_select_product_variations',
								            data: { product_id: product_id, context: 'purchase_order_new'},
								            beforeSend: function(){
								                $("#purchase_order_loader").fadeIn("fast");
								            },
								            success: function(res){
								            	$("#purchase_order_loader").fadeOut("fast");
								                $('#div_po_select_product_variation').html(res);
								                $('#modal_po_select_product_variation').modal('toggle');
								            },
								            error: function(){
								            	$("#purchase_order_loader").fadeOut("fast");
							            	}
								        });
					            	}
					            	$('.select-basic').select2({
			                            placeholder: "Enter at least 1 character"
			                        });
						            $("#purchase_order_product_id").val('').change();
			                    });
			                },
			                error: function(){
			                }
			            });
			    	}
			    });


			    $(document).on('click', '.lnk-select-product-variation', function(e){
					e.preventDefault();

					var product_id = $(this).attr("data-product-id");
					var product_variation_id = $(this).attr("data-product-variation-id");
					var line_id = '' + product_id + product_variation_id;
					var product_name = $('#spv_product_name').val();
					var product_sku_code = $('#spv_product_sku_code').val();
					var unit_id = $('#spv_unit_id').val();
					var product_price = $(this).attr("data-product-price");
					var variationDescription = $(this).attr("data-variation-description");
					var variation_description = variationDescription.substring(0, variationDescription.length -2);

					var product_units = new Array();
	                product_units =  JSON.parse($('#spv_product_units').val());

	                var product_unit_select = '<select class="form-control select-basic po_unit_id" data-placeholder="Select Unit" id="po_unit_id_' + line_id + '" name="po_unit_id[]" style="width: 100%;" tabindex="-1" aria-hidden="true" required> \
	               		<option value="">Select Unit</option>';
	                $.each(product_units, function(index2, element2) {
	                    var unit_price = product_price;
	                    var selected = '';
	                    if (unit_id != element2.unit_id && element2.unit_price != 0) {
	                        unit_price = element2.unit_price;
	                    }
	                    if (unit_id == element2.unit_id) {
	                        selected = 'selected';
	                    }
	                    product_unit_select = product_unit_select + '<option value="' + element2.unit_id + '" data-unit-price="' + unit_price + '" data-line-id="' + line_id + '" ' + selected + '>' + element2.unit_name + ' (' + element2.unit_code + ')</option>';
	                });
	                product_unit_select = product_unit_select + '</select>';

					if($('#po_detail_qty_' + line_id).length && $('#po_detail_qty_' + line_id).val().length){
		            	var detailqty = parseFloat($('#po_detail_qty_' + line_id).val()) + 1;
		            	$('#po_detail_qty_' + line_id).val(detailqty);
		            }else{
						$('#purchase_order_details_table tr:first').before('<tr> \
		            		<td>' + product_name + '<br><i class="badge badge-mark ml-2"></i> ' + variation_description + '<br><div class="text-muted font-size-sm pt-0"><b>SKU:</b>' + product_sku_code + '</div><input id="po_detail_id_' + line_id + '" name="po_detail_id[]" type="hidden" class="po_detail_id" value="' + line_id + '"><input id="po_detail_product_id_' + line_id + '" name="po_detail_product_id[]" type="hidden" class="po_detail_product_id" value="' + product_id + '"><input id="po_detail_product_variation_id_' + line_id + '" name="po_detail_product_variation_id[]" type="hidden" class="po_detail_product_variation_id" value="' + product_variation_id + '"></td> \
		            		<td>' + product_unit_select + '</td> \
		            		<td><input id="po_detail_qty_' + line_id + '" name="po_detail_qty[]" type="number" class="form-control po_detail_qty" min="1" value="1" autocomplete="off"></td> \
		            		<td><input id="po_detail_cost_' + line_id + '" name="po_detail_cost[]" type="number" class="form-control po_detail_cost" value="' + product_price + '" autocomplete="off" style="min-width: 120px"></td> \
		            		<td><span id="po_label_detail_total_' + line_id + '">0.00</span><input id="po_detail_total_' + line_id + '" name="po_detail_total[]" type="hidden" class="form-control po_detail_total" value="0"></td> \
		            		<td><a href="javascript:void(0);" class="po_detail_remove" title="Remove product"><i class="icon-cancel-circle2 text-danger"></i></a></td> \
		            	</tr>');
					}

					$('#modal_po_select_product_variation').modal('toggle');

					calculate_po_detail_total(line_id);
		            calculate_po_totals();

				});
			</script>



