		<!-- Main content -->
		<div class="content-wrapper">

			<?php if (!isset($goods_receipt_note)): ?>

				<!-- Page header -->
				<div class="page-header">
					<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
						<div class="d-flex">
							<div class="breadcrumb">
								<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
								<a href="#" class="breadcrumb-item">Inventory</a>
								<a href="<?php echo base_url();?>be/inventory/goods_receipt_notes" class="breadcrumb-item">Goods Receipt Notes</a>
								<span class="breadcrumb-item active">Receive Stock</span>
							</div>

							<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
						</div>
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content pt-0">
					<!-- <div class="mb-3">S
						<h5 class="mb-0 font-weight-semibold">
							New Purchase Order
						</h5>
					</div> -->

					<form id="frm_add_goods_receipt_note" name="frm_add_goods_receipt_note" method="post" onsubmit="return save_goods_receipt_note();" autocomplete="false">
						<div class="spinner2 display-none" id="goods_receipt_note_loader">
	                        <div class="rect1"></div>
	                        <div class="rect2"></div>
	                        <div class="rect3"></div>
	                    </div>
						<div class="row">
							<div class="col-md-1"></div>
							<div class="col-md-10">
								<div class="card rounded-top-0">
									<div class="card-header bg-transparent header-elements-inline p-2">
										<h5 class="card-title font-weight-bold"><i class="icon-plus-circle2"></i> Receive Stock</h5>		
										<div class="header-elements">
											<a href="<?php echo base_url(); ?>be/inventory/goods_receipt_notes" class="btn btn-sm btn-primary ml-2"><i class="icon-list3 mr-1"></i> Goods Received List</a>	
										</div>
									</div>
							
									<div class="card-body">
										<div id="div_add_goods_receipt_note_error" class="alert alert-danger display-none font-13"></div>
		                   				<div id="div_add_goods_receipt_note_success" class="alert alert-success display-none font-13"></div>

										<div class="form-group mb-3">
											<div class="row">
												<div class="col-sm-3">
													<label>Received To <span class="error">*</span></label>
													<select id="outlet_id" name="outlet_id" data-placeholder="Select Outlet" class="form-control select" data-fouc>
														<option value="">Select Outlet</option>
														<?php foreach ($outlets as $row): ?>
															<option value="<?php echo $row->outlet_id; ?>"><?php echo $row->outlet_name; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-sm-3">
													<label>Purchase Order <span class="error">*</span></label>
													<select id="goods_receipt_note_purchase_order_id" name="purchase_order_id" data-placeholder="Select Purchase Order" class="form-control select" data-fouc>
														<option value="">Select Purchase Order</option>
														<?php foreach ($purchase_orders as $row): ?>
															<option value="<?php echo $row->purchase_order_id; ?>"><?php echo $row->purchase_order_number; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-sm-3">
													<label>Supplier</label>
													<input id="supplier_name" name="supplier_name" type="text" placeholder="" readonly class="form-control">
													<input id="supplier_id" name="supplier_id" type="hidden">
												</div>
												<div class="col-sm-3">
													<label>Receival Date <span class="error">*</span></label>
													<input id="receival_date" name="receival_date" type="text" placeholder="" class="form-control pickadate">
												</div>
											</div>
										</div>
										
										<div class="table-responsive mb-3">
											<table class="table table-bordered table-striped">
												<thead>
													<tr>
														<th>Product Name</th>
														<th width="150px">Unit</th>
														<th width="120px">Ordered Qty</th>
														<th width="120px">Received Qty</th>
														<th width="120px">Qty to Receive</th>
														<th width="150px">Cost (<?php echo $default_currency; ?>)</th>														
														<th width="150px">Total (<?php echo $default_currency; ?>)</th>
														<th width="50px">Action</th>
													</tr>
												</thead>
												<tbody id="goods_receipt_note_details_table">
													<tr>
														<td colspan="6" class="text-right"><strong>Total Qty:</strong></td>
														<td colspan="2"><strong><span id="grn_label_total_detail_qty">0</span></strong><input id="grn_total_detail_qty" name="grn_total_detail_qty" type="hidden" class="form-control" value="0"></td>
														
													</tr>
													<tr class="display-none">
														<td colspan="6" class="text-right"><strong>SubTotal:</strong></td>
														<td colspan="2"><strong><?php echo $default_currency; ?> <span id="grn_label_total_detail_subtotal">0.00</span></strong><input id="grn_total_detail_subtotal" name="grn_total_detail_subtotal" type="hidden" class="form-control" value="0"></td>
														
													</tr>
													<tr class="display-none">
														<td colspan="6" class="text-right"><strong>Freight:</strong></td>
														<td colspan="2"><input id="grn_freight_cost" name="freight_cost" type="number" class="form-control" value="0.00" min="0"></td>
														
													</tr>
													<tr>
														<td colspan="6" class="text-right"><strong>Grand Total:</strong></td>
														<td colspan="2"><strong><span id="grn_label_total_detail_total">0.00</span></strong><input id="grn_total_detail_total" name="grn_total_detail_total" type="hidden" class="form-control" value="0"></td>
														
													</tr>
												</tbody>
											</table>
										</div>
									
										<div class="form-group">
											<div class="row">
												<div class="col-sm-12">
													<label>Remarks</label>
													<textarea name="remark" id="remark" rows="3" cols="3" class="form-control" placeholder=""></textarea>
												</div>
											</div>
										</div>
									</div>
								</div>							
								<hr>
								<div class="text-right">
									<button type="submit" id="btn_add_goods_receipt_note" class="btn bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> ACCEPT STOCK</button>
								</div>
							</div>
							<div class="col-md-1"></div>	
						</div>
					</form>
				</div>
			<?php else: ?>
				<?php foreach ($goods_receipt_note as $row): ?>
					<!-- Page header -->
					<div class="page-header">
						<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
							<div class="d-flex">
								<div class="breadcrumb">
									<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
									<a href="<?php echo base_url();?>be/inventory/goods_receipt_notes" class="breadcrumb-item">Goods Receipt Notes</a>
									<span class="breadcrumb-item active">Edit Goods Receipt Note (<?php echo $row->goods_receipt_note_number; ?>)</span>
								</div>

								<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
							</div>
						</div>
					</div>
					<!-- /page header -->


					<!-- Content area -->
					<div class="content pt-0">
						<form id="frm_edit_goods_receipt_note" name="frm_edit_goods_receipt_note" method="post" onsubmit="return update_goods_receipt_note();" autocomplete="false">
							<div class="row">
								<div class="col-md-1"></div>
								<div class="col-md-10">
									<div class="card rounded-top-0">
										<div class="card-header bg-transparent header-elements-inline p-2">
											<h5 class="card-title font-weight-bold">Edit Goods Receipt Note <b>(<?php echo $row->goods_receipt_note_number; ?>)</b></h5>		
											<div class="header-elements">
												<a href="<?php echo base_url(); ?>be/inventory/goods_receipt_notes" class="btn btn-sm btn-primary ml-2"><i class="icon-list3 mr-1"></i> Goods Received List</a>	
											</div>
										</div>
								
										<div class="card-body">
											<div id="div_edit_goods_receipt_note_error" class="alert alert-danger display-none font-13"></div>
			                   				<div id="div_edit_goods_receipt_note_success" class="alert alert-success display-none font-13"></div>

			                   				<input id="goods_receipt_note_id" name="goods_receipt_note_id" type="hidden" value="<?php echo $row->goods_receipt_note_id; ?>">

											<div class="form-group mb-3">
												<div class="row">
													<div class="col-sm-3">
														<label>Received To <span class="error">*</span></label>
														<input id="outlet_id" name="outlet_id" type="hidden" class="form-control" value="<?php echo $row->outlet_id; ?>">
														<input id="outlet_name" name="outlet_name" type="text" readonly class="form-control" value="<?php echo $row->outlet_name; ?>">
													</div>
													<div class="col-sm-3">
														<label>Purchase Order <span class="error">*</span></label>
														<input id="purchase_order_id" name="purchase_order_id" type="hidden" class="form-control" value="<?php echo $row->purchase_order_id; ?>">
														<input id="purchase_order_number" name="purchase_order_number" type="text" readonly class="form-control" value="<?php echo $row->purchase_order_number; ?>">
													</div>
													<div class="col-sm-3">
														<label>Supplier <span class="error">*</span></label>
														<input id="supplier_name" name="supplier_name" type="text" placeholder="" readonly class="form-control" value="<?php echo $row->supplier_name; ?>">
														<input id="supplier_id" name="supplier_id" type="hidden" value="<?php echo $row->supplier_id; ?>">
													</div>
													<div class="col-sm-3">
														<label>Receival Date <span class="error">*</span></label>
														<input id="receival_date" name="receival_date" type="text" placeholder="" class="form-control pickadate" value="<?php echo $row->receival_date; ?>">
													</div>
												</div>
											</div>
											<div class="form-group mb-1">
												<div class="row">
													<!-- <div class="col-sm-6">
														<label>Product Quick Search</label>
														<div class="input-group">
															<span class="input-group-prepend">
																<span class="input-group-text bg-primary border-primary text-white">
																	<i class="icon-search4"></i>
																</span>
															</span>
															<input id="goods_receipt_note_quick_search" name="goods_receipt_note_quick_search" type="text" class="form-control form-control-lg border-left-0" placeholder="Search by product name, product code or product barcode">
														</div>
													</div> -->
													<div class="col-sm-12">
														<label>Select a Product Here</label>
														<div class="form-group form-group-feedback form-group-feedback-right mb-2">
															<select id="goods_receipt_note_product_id" name="product_id" data-placeholder="Select Product" class="form-control select" data-fouc>
																<option value="">Select Product</option>
																<?php foreach ($purchase_order_products as $row2): ?>																	
																	<?php
																		$var_desc = '';
																		if(!empty($row2->attributes)){												                		
												                			foreach ($row2->attributes as $row3){
												                				$var_desc = $var_desc . $row3->product_attribute_name . ' : <b>' . $row3->product_attribute_value . '</b>, ';
												                			}
												                			$var_desc = substr($var_desc,0,-2);
																		}
												                	?>
																	<option value="<?php echo $row2->purchase_order_detail_id; ?>"><?php echo $row2->product_name; ?> <?php if ($var_desc != ''){ echo '(' . $var_desc . ')'; } ?></option>
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
															<th>Product</th>
															<th width="150px">Unit</th>
															<th width="120px" class="text-center">Ordered Qty</th>
															<th width="120px" class="text-center">Total Received Qty</th>
															<th width="120px" class="text-center">Qty Received</th>
															<th width="150px">Cost (<?php echo $default_currency; ?>)</th>														
															<th width="150px">Total (<?php echo $default_currency; ?>)</th>
															<th width="20px">Action</th>
														</tr>
													</thead>
													<tbody id="goods_receipt_note_details_table">
														<?php foreach ($goods_receipt_note_details as $row2): ?>
															<?php
																$line_id = $row2->product_id . $row2->product_variation_id;
																$max_qty = $row2->detail_quantity - ($row2->total_received_quantity - $row2->received_quantity);
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
																	<input id="grn_detail_id_<?php echo $line_id; ?>" name="grn_detail_id[]" type="hidden" class="form-control grn_detail_id" value="<?php echo $line_id; ?>">
																	<input id="grn_detail_product_id_<?php echo $line_id; ?>" name="grn_detail_product_id[]" type="hidden" class="grn_detail_product_id" value="<?php echo $row2->product_id; ?>">
																	<input id="grn_detail_product_variation_id_<?php echo $line_id; ?>" name="grn_detail_product_variation_id[]" type="hidden" class="grn_detail_product_variation_id" value="<?php echo $row2->product_variation_id; ?>">
																</td>
																<td>
																	<?php echo $row2->unit_name . ' (' . $row2->unit_code . ')'; ?>
																	<input id="grn_unit_id_<?php echo $line_id; ?>" name="grn_unit_id[]" type="hidden" class="form-control grn_unit_id" value="<?php echo $row2->unit_id; ?>"></td>
																<td class="text-center"><?php echo number_format($row2->detail_quantity,2); ?></td>
																<td class="text-center"><?php echo number_format($row2->total_received_quantity,2); ?></td>
																<td class="text-center">
																	<input id="grn_detail_qty_<?php echo $line_id; ?>" name="grn_detail_qty[]" type="number" class="form-control grn_detail_qty" required min="1" max="<?php echo $max_qty; ?>" value="<?php echo $row2->received_quantity; ?>" autocomplete="off">
																</td>
																<td>
																	<input id="grn_detail_cost_<?php echo $line_id; ?>" name="grn_detail_cost[]" type="number" class="form-control grn_detail_cost" value="<?php echo $row2->unit_price; ?>" required autocomplete="off">
																</td>
																<td>
																	<span id="grn_label_detail_total_<?php echo $line_id; ?>"><?php echo number_format($row2->detail_total_amount,2); ?></span>
																	<input id="grn_detail_total_<?php echo $line_id; ?>" name="grn_detail_total[]" type="hidden" class="form-control grn_detail_total" value="<?php echo $row2->detail_total_amount; ?>">
																</td>
																<td><a href="javascript:void(0);" class="grn_detail_remove" title="Remove product"><i class="icon-cancel-circle2 text-danger"></i></a></td>
															</tr>
														<?php endforeach; ?>
														<tr>
															<td colspan="6" class="text-right"><strong>Total Qty:</strong></td>
															<td colspan="2"><strong><span id="grn_label_total_detail_qty"><?php echo number_format($row->total_quantity,2); ?></span></strong><input id="grn_total_detail_qty" name="grn_total_detail_qty" type="hidden" class="form-control" value="<?php echo $row->total_quantity; ?>"></td>
															
														</tr>
														<tr class="display-none">
															<td colspan="6" class="text-right"><strong>Subtotal:</strong></td>
															<td colspan="2"><strong><?php echo $default_currency; ?> <span id="grn_label_total_detail_subtotal"><?php echo number_format($row->sub_total,2); ?></span></strong><input id="grn_total_detail_subtotal" name="grn_total_detail_subtotal" type="hidden" class="form-control" value="<?php echo $row->sub_total; ?>"></td>
															
														</tr>
														<tr class="display-none">
															<td colspan="6" class="text-right"><strong>Freight:</strong></td>
															<td colspan="2"><input id="grn_freight_cost" name="freight_cost" type="number" class="form-control" value="<?php echo $row->freight_cost; ?>" min="0"></td>
															
														</tr>
														<tr>
															<td colspan="6" class="text-right"><strong><big>Grand Total:</big></strong></td>
															<td colspan="2"><strong><big><?php echo $default_currency; ?> <span id="grn_label_total_detail_total"><?php echo number_format($row->total_amount,2); ?></span></strong></big><input id="grn_total_detail_total" name="grn_total_detail_total" type="hidden" class="form-control" value="<?php echo $row->total_amount; ?>"></td>
															
														</tr>
													</tbody>
												</table>
											</div>
										
											<div class="form-group">
												<div class="row">
													<div class="col-sm-12">
														<label>Remarks</label>
														<textarea name="remark" id="remark" rows="3" cols="3" class="form-control" placeholder=""><?php echo $row->remark; ?></textarea>
													</div>
												</div>
											</div>
										</div>
									</div>							
									<hr>
									<div class="text-right">
										<button type="submit" id="btn_edit_goods_receipt_note" class="btn bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
									</div>
								</div>
								<div class="col-md-1"></div>	
							</div>
						</form>
					</div>

					<div id="modal_grn_select_product_variation" class="modal fade">
						<div class="modal-dialog">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title text-primary"><i class="icon-plus-circle2"></i> Select Variation</h5>
									<button type="button" class="close" data-dismiss="modal">&times;</button>
								</div>
								<div class="modal-body" style="min-height: 200px;">
									<div id="div_grn_select_product_variation">
										
									</div>
								</div>
							</div>
						</div>
					</div>

					<script type="text/javascript">
						$(document).ready(function() {
							//GOODS RECEIPT NOTE ADD

						    $("#goods_receipt_note_product_id").on('change', function() {
						    	var purchaseOrderID = $("#purchase_order_id").val();

						    	if (purchaseOrderID == '' || purchaseOrderID == null) {
						    		swal({
				                        type: 'warning',
				                        title: 'Please select Purchase Order first'
				                    });
						    	} else {
						    		if (this.value != ''){
						    			var purchase_order_detail_id = $("#goods_receipt_note_product_id").val();
							    		$.ajax({
							                type: 'POST',
							                url: baseDir+'be/inventory/get_purchase_order_detail/' + purchase_order_detail_id,
							                data: '',
							                dataType: 'json',
							                success: function(res){
							                    $.each(res, function(index, element) {
							                    	
							                    	var product_id = element.product_id;
										            var product_variation_id = element.product_variation_id;
										            var line_id = '' + product_id + product_variation_id;
										            var qty_to_receive = element.detail_quantity - element.received_quantity;
										            var max_qty = element.detail_quantity - (element.total_received_quantity - element.received_quantity);
										            var variation_description = '';
							                        if (element.attributes != null && element.attributes != '') {
							                            $.each(element.attributes, function(index2, element2) {
							                                variation_description = variation_description + element2.product_attribute_name + ' : <b>' + element2.product_attribute_value + '</b>, ';
							                            }); 
							                            variation_description = '<br><i class="badge badge-mark ml-2"></i> ' + variation_description;
							                            variation_description = variation_description.substring(0, variation_description.length -2);
							                        }

							                    	
						                    		if($('#grn_detail_qty_' + line_id).length && $('#grn_detail_qty_' + line_id).val().length){
										            	var detailqty = parseFloat($('#grn_detail_qty_' + line_id).val()) + 1;
										            	$('#grn_detail_qty_' + line_id).val(detailqty);
										            }else{

										            	$('#goods_receipt_note_details_table tr:first').before('<tr> \
								                            <td>' + element.product_name + variation_description + '<br><div class="text-muted font-size-sm pt-0"><b>SKU:</b>' + element.product_sku_code + '</div> \
								                                <input id="grn_detail_id_' + line_id + '" name="grn_detail_id[]" type="hidden" class="form-control grn_detail_id" value="' + line_id + '"> \
								                                <input id="grn_detail_product_id_' + line_id + '" name="grn_detail_product_id[]" type="hidden" class="grn_detail_product_id" value="' + product_id + '">\
								                                <input id="grn_detail_product_variation_id_' + line_id + '" name="grn_detail_product_variation_id[]" type="hidden" class="grn_detail_product_variation_id" value="' + product_variation_id + '"></td> \
								                            <td>' + element.unit_name + ' (' + element.unit_code + ')<input id="grn_unit_id_' + line_id + '" name="grn_unit_id[]" type="hidden" class="form-control grn_unit_id" value="' + element.unit_id + '"></td> \
								                            <td class="text-center">' + element.detail_quantity + '</td> \
								                            <td class="text-center">' + element.received_quantity + '</td> \
								                            <td class="text-center"><input id="grn_detail_qty_' + line_id + '" name="grn_detail_qty[]" type="number" class="form-control grn_detail_qty" required min="0" max="' + max_qty + '" value="' + element.received_quantity + '" autocomplete="off"></td> \
								                            <td><input id="grn_detail_cost_' + line_id + '" name="grn_detail_cost[]" type="number" class="form-control grn_detail_cost" value="' + element.unit_price + '" required autocomplete="off"></td> \
								                            <td><span id="grn_label_detail_total_' + line_id + '">0.00</span><input id="grn_detail_total_' + line_id + '" name="grn_detail_total[]" type="hidden" class="form-control grn_detail_total" value="0"></td> \
								                            <td><a href="javascript:void(0);" class="grn_detail_remove" title="Remove product"><i class="icon-cancel-circle2 text-danger"></i></a></td> \
								                        </tr>');
										            }
							                    	
						                    	
													calculate_grn_detail_total(line_id);
						            				calculate_grn_totals();
										            $("#goods_receipt_note_product_id").val('').change();
							                    });
							                },
							                error: function(){
							                }
							            });
							    	}
						    	}
						    });

						});
					</script>
				<?php endforeach; ?>
			<?php endif; ?>

			

