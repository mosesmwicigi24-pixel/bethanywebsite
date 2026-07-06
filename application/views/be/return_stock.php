		<!-- Main content -->
		<div class="content-wrapper">

			<?php if (!isset($goods_return_note)): ?>

				<!-- Page header -->
				<div class="page-header">
					<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
						<div class="d-flex">
							<div class="breadcrumb">
								<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
								<a href="#" class="breadcrumb-item">Inventory</a>
								<a href="<?php echo base_url();?>be/inventory/goods_return_notes" class="breadcrumb-item">Goods Return Notes</a>
								<span class="breadcrumb-item active">Return Stock</span>
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

					<form id="frm_add_goods_return_note" name="frm_add_goods_return_note" method="post" onsubmit="return save_goods_return_note();" autocomplete="false">
						<div class="spinner2 display-none" id="return_stock_loader">
	                        <div class="rect1"></div>
	                        <div class="rect2"></div>
	                        <div class="rect3"></div>
	                    </div>
						<div class="row">
							<div class="col-md-1"></div>
							<div class="col-md-10">
								<div class="card rounded-top-0">
									<div class="card-header bg-transparent header-elements-inline p-2">
										<h5 class="card-title font-weight-bold"><i class="icon-plus-circle2"></i> Return Stock</h5>		
										<div class="header-elements">
											<a href="<?php echo base_url(); ?>be/inventory/goods_return_notes" class="btn btn-sm btn-primary ml-2"><i class="icon-list3 mr-1"></i> Goods Returned List</a>	
										</div>
									</div>
							
									<div class="card-body">
										<div id="div_add_goods_return_note_error" class="alert alert-danger display-none font-13"></div>
		                   				<div id="div_add_goods_return_note_success" class="alert alert-success display-none font-13"></div>

										<div class="form-group mb-2">
											<div class="row">
												<div class="col-sm-4">
													<label>Returned From <span class="error">*</span></label>
													<select id="outlet_id" name="outlet_id" data-placeholder="Select Outlet" class="form-control select" data-fouc>
														<option value="">Select Outlet</option>
														<?php foreach ($outlets as $row): ?>
															<option value="<?php echo $row->outlet_id; ?>"><?php echo $row->outlet_name; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-sm-4">
													<label>Supplier <span class="error">*</span></label>
													<select id="supplier_id" name="supplier_id" data-placeholder="Select Supplier" class="form-control select" data-fouc>
														<option value="">Select Supplier</option>
														<?php foreach ($suppliers as $row): ?>
															<option value="<?php echo $row->supplier_id; ?>"><?php echo $row->supplier_name; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-sm-4">
													<label>Return Date <span class="error">*</span></label>
													<input id="return_date" name="return_date" type="text" placeholder="" class="form-control pickadate">
												</div>
											</div>
										</div>
										<div class="form-group mb-2">
											<div class="row">
												<div class="col-sm-12">
													<label>Reason <span class="error">*</span></label>
													<textarea name="return_reason" id="return_reason" rows="2" cols="3" class="form-control" placeholder=""></textarea>
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
														<input id="goods_return_note_quick_search" name="goods_return_note_quick_search" type="text" class="form-control border-left-0" placeholder="Search by product name, product code or product barcode">
													</div>
												</div>
												<div class="col-sm-6">
													<label>Or Select a Product Here</label>
													<div class="form-group form-group-feedback form-group-feedback-right mb-2">
														<select id="goods_return_note_product_id" name="product_id" data-placeholder="Select Product" class="form-control select" data-fouc>
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
														<th width="300px">Product Name</th>
														<th width="150px">Unit</th>
														<th width="150px">Returned Qty</th>
														<th width="150px">Cost</th>														
														<th width="150px">Total (<?php echo $default_currency; ?>)</th>
														<th width="20px"></th>
													</tr>
												</thead>
												<tbody id="goods_return_note_details_table">
													<tr>
														<td colspan="4" class="text-right"><strong>Total Qty:</strong></td>
														<td colspan="2"><strong><span id="gren_label_total_detail_qty">0</span></strong><input id="gren_total_detail_qty" name="gren_total_detail_qty" type="hidden" class="form-control" value="0"></td>
														
													</tr>
													<tr class="display-none">
														<td colspan="4" class="text-right"><strong>Subtotal:</strong></td>
														<td colspan="2"><strong><span id="gren_label_total_detail_subtotal">0.00</span></strong><input id="gren_total_detail_subtotal" name="gren_total_detail_subtotal" type="hidden" class="form-control" value="0"></td>
														
													</tr>
													<tr class="display-none">
														<td colspan="4" class="text-right"><strong>Freight:</strong></td>
														<td colspan="2"><input id="gren_freight_cost" name="freight_cost" type="number" class="form-control" value="0.00" min="0"></td>
														
													</tr>
													<tr>
														<td colspan="4" class="text-right"><strong>Total:</strong></td>
														<td colspan="2"><strong><span id="gren_label_total_detail_total">0.00</span></strong><input id="gren_total_detail_total" name="gren_total_detail_total" type="hidden" class="form-control" value="0"></td>
														
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
									<button type="submit" id="btn_add_goods_return_note" class="btn bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> RETURN STOCK</button>
								</div>
							</div>
							<div class="col-md-1"></div>	
						</div>
					</form>
				</div>
			<?php else: ?>
				<?php foreach ($goods_return_note as $row): ?>
					<!-- Page header -->
					<div class="page-header">
						<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
							<div class="d-flex">
								<div class="breadcrumb">
									<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
									<a href="<?php echo base_url();?>be/inventory/goods_return_notes" class="breadcrumb-item">Goods Return Notes</a>
									<span class="breadcrumb-item active">Edit Goods Return Note (<?php echo $row->goods_return_note_number; ?>)</span>
								</div>

								<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
							</div>
						</div>
					</div>
					<!-- /page header -->


					<!-- Content area -->
					<div class="content pt-0">
						<form id="frm_edit_goods_return_note" name="frm_edit_goods_return_note" method="post" onsubmit="return update_goods_return_note();" autocomplete="false">
							<div class="spinner2 display-none" id="return_stock_loader">
		                        <div class="rect1"></div>
		                        <div class="rect2"></div>
		                        <div class="rect3"></div>
		                    </div>
							<div class="row">
								<div class="col-md-1"></div>
								<div class="col-md-10">
									<div class="card rounded-top-0">
										<div class="card-header bg-transparent header-elements-inline p-2">
											<h5 class="card-title font-weight-bold">Edit Goods Return Note <b>(<?php echo $row->goods_return_note_number; ?>)</b></h5>		
											<div class="header-elements">
												<a href="<?php echo base_url(); ?>be/inventory/goods_return_notes" class="btn btn-sm btn-primary ml-2"><i class="icon-list3 mr-1"></i> Goods Returned List</a>	
											</div>
										</div>
								
										<div class="card-body">
											<div id="div_edit_goods_return_note_error" class="alert alert-danger display-none font-13"></div>
			                   				<div id="div_edit_goods_return_note_success" class="alert alert-success display-none font-13"></div>

			                   				<input id="goods_return_note_id" name="goods_return_note_id" type="hidden" value="<?php echo $row->goods_return_note_id; ?>">

											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-4">
														<label>Returned From <span class="error">*</span></label>
														<input id="outlet_id" name="outlet_id" type="hidden" class="form-control" value="<?php echo $row->outlet_id; ?>">
														<input id="outlet_name" name="outlet_name" type="text" readonly class="form-control" value="<?php echo $row->outlet_name; ?>">
													</div>
													<div class="col-sm-4">
														<label>Supplier <span class="error">*</span></label>
														<select id="supplier_id" name="supplier_id" data-placeholder="Select Supplier" class="form-control select" data-fouc>
															<option value="">Select Supplier</option>
															<?php foreach ($suppliers as $row2): ?>
																<option value="<?php echo $row2->supplier_id; ?>" <?php if ($row2->supplier_id == $row->supplier_id){ echo 'selected';} ?>><?php echo $row2->supplier_name; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
													<div class="col-sm-4">
														<label>Return Date <span class="error">*</span></label>
														<input id="return_date" name="return_date" type="text" placeholder="" class="form-control pickadate" value="<?php echo $row->return_date; ?>">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-12">
														<label>Reason <span class="error">*</span></label>
														<textarea name="return_reason" id="return_reason" rows="2" cols="3" class="form-control" placeholder=""><?php echo $row->return_reason; ?></textarea>
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
															<input id="goods_return_note_quick_search" name="goods_return_note_quick_search" type="text" class="form-control border-left-0" placeholder="Search by product name, product code or product barcode">
														</div>
													</div>
													<div class="col-sm-6">
														<label>Or Select a Product Here</label>
														<div class="form-group form-group-feedback form-group-feedback-right mb-2">
															<select id="goods_return_note_product_id" name="product_id" data-placeholder="Select Product" class="form-control select" data-fouc>
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
															<th width="300px">Product Name</th>
															<th width="150px">Unit</th>
															<th width="150px">Returned Qty</th>
															<th width="150px">Cost</th>														
															<th width="150px">Total (<?php echo $default_currency; ?>)</th>
															<th width="20px"></th>
														</tr>
													</thead>
													<tbody id="goods_return_note_details_table">
														<?php foreach ($goods_return_note_details as $row2): ?>
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
																	<input id="gren_detail_id_<?php echo $line_id; ?>" name="gren_detail_id[]" type="hidden" class="form-control gren_detail_id" value="<?php echo $line_id; ?>">
																	<input id="gren_detail_product_id_<?php echo $line_id; ?>" name="gren_detail_product_id[]" type="hidden" class="gren_detail_product_id" value="<?php echo $row2->product_id; ?>">
																	<input id="gren_detail_product_variation_id_<?php echo $line_id; ?>" name="gren_detail_product_variation_id[]" type="hidden" class="gren_detail_product_variation_id" value="<?php echo $row2->product_variation_id; ?>">
																</td>
																<td>
																	<select class="form-control select-basic gren_unit_id" data-placeholder="Select Unit" id="gren_unit_id_<?php echo $line_id; ?>" name="gren_unit_id[]" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
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
																	<input id="gren_detail_qty_<?php echo $line_id; ?>" name="gren_detail_qty[]" type="number" class="form-control gren_detail_qty" min="1" value="<?php echo $row2->returned_quantity; ?>" autocomplete="off">
																</td>
																<td>
																	<input id="gren_detail_cost_<?php echo $line_id; ?>" name="gren_detail_cost[]" type="number" class="form-control gren_detail_cost" value="<?php echo $row2->unit_price; ?>" autocomplete="off">
																</td>
																<td>
																	<?php echo $default_currency; ?> <span id="gren_label_detail_total_<?php echo $line_id; ?>"><?php echo number_format($row2->detail_total_amount,2); ?></span>
																	<input id="gren_detail_total_<?php echo $line_id; ?>" name="gren_detail_total[]" type="hidden" class="form-control gren_detail_total" value="<?php echo $row2->detail_total_amount; ?>">
																</td>
																<td><a href="javascript:void(0);" class="gren_detail_remove" title="Remove product"><i class="icon-cancel-circle2 text-danger"></i></a></td>
															</tr>
														<?php endforeach; ?>
														<tr>
															<td colspan="4" class="text-right"><strong>Total Qty:</strong></td>
															<td colspan="2"><strong><span id="gren_label_total_detail_qty"><?php echo number_format($row->total_quantity,2); ?></span></strong><input id="gren_total_detail_qty" name="gren_total_detail_qty" type="hidden" class="form-control" value="<?php echo $row->total_quantity; ?>"></td>
															
														</tr>
														<tr class="display-none">
															<td colspan="4" class="text-right"><strong>Subtotal:</strong></td>
															<td colspan="2"><strong><?php echo $default_currency; ?> <span id="gren_label_total_detail_subtotal"><?php echo number_format($row->sub_total,2); ?></span></strong><input id="gren_total_detail_subtotal" name="gren_total_detail_subtotal" type="hidden" class="form-control" value="<?php echo $row->sub_total; ?>"></td>
															
														</tr>
														<tr class="display-none">
															<td colspan="4" class="text-right"><strong>Freight:</strong></td>
															<td colspan="2"><input id="gren_freight_cost" name="freight_cost" type="number" class="form-control" value="<?php echo $row->freight_cost; ?>" min="0"></td>
															
														</tr>
														<tr>
															<td colspan="4" class="text-right"><strong>Total:</strong></td>
															<td colspan="2"><strong><?php echo $default_currency; ?> <span id="gren_label_total_detail_total"><?php echo number_format($row->total_amount,2); ?></span></strong><input id="gren_total_detail_total" name="gren_total_detail_total" type="hidden" class="form-control" value="<?php echo $row->total_amount; ?>"></td>
															
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
										<button type="submit" id="btn_edit_goods_return_note" class="btn bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
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
					//GOODS return NOTE ADD
				    $('#goods_return_note_quick_search').autocomplete({
				        minLength: 1,
				        source: baseDir + 'be/inventory/get_auto_purchase_order_products',
				        search: function() {
				            $(this).parent().addClass('ui-autocomplete-processing');
				        },
				        open: function() {
				            $(this).parent().removeClass('ui-autocomplete-processing');
				        },
				        focus: function( event, ui ) {
				            return false;
				        },
				        select: function( event, ui ) {
				            $('#goods_return_note_quick_search').val(ui.item.label);

				            var product_id = ui.item.id;
				            var product_variation_id = '0';
				            var line_id = '' + product_id + product_variation_id;

				            var product_unit_select = '<select class="form-control select-basic gren_unit_id" data-placeholder="Select Unit" id="gren_unit_id_' + line_id + '" name="gren_unit_id[]" style="width: 100%;" tabindex="-1" aria-hidden="true" required> \
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
				            	if($('#gren_detail_qty_' + line_id).length && $('#gren_detail_qty_' + line_id).val().length){
					            	var detailqty = parseFloat($('#gren_detail_qty_' + line_id).val()) + 1;
					            	$('#gren_detail_qty_' + line_id).val(detailqty);
					            }else{
					            	$('#goods_return_note_details_table tr:first').before('<tr> \
					            		<td>' + ui.item.label + '<br><div class="text-muted font-size-sm pt-0"><b>SKU:</b>' + ui.item.desc + '</div> \
					            			<input id="gren_detail_id_' + line_id + '" name="gren_detail_id[]" type="hidden" class="form-control gren_detail_id" value="' + line_id + '"> \
					            			<input id="gren_detail_product_id_' + line_id + '" name="gren_detail_product_id[]" type="hidden" class="gren_detail_product_id" value="' + product_id + '"> \
					            			<input id="gren_detail_product_variation_id_' + line_id + '" name="gren_detail_product_variation_id[]" type="hidden" class="gren_detail_product_variation_id" value="' + product_variation_id + '"> \
					            		</td> \
					            		<td>' + product_unit_select + '</td> \
					            		<td><input id="gren_detail_qty_' + line_id + '" name="gren_detail_qty[]" type="number" class="form-control gren_detail_qty" min="1" value="1" autocomplete="off"></td> \
					            		<td><input id="gren_detail_cost_' + line_id + '" name="gren_detail_cost[]" type="number" class="form-control gren_detail_cost" value="' + ui.item.price + '" autocomplete="off"></td> \
					            		<td><span id="gren_label_detail_total_' + line_id + '">0.00</span><input id="gren_detail_total_' + line_id + '" name="gren_detail_total[]" type="hidden" class="form-control gren_detail_total" value="0"></td> \
					            		<td><a href="javascript:void(0);" class="gren_detail_remove" title="Remove product"><i class="icon-cancel-circle2 text-danger"></i></a></td> \
					            	</tr>');
					            }
					            calculate_gren_detail_total(line_id);
				            	calculate_gren_totals();
				            } else {
				            	$.ajax({
						            type: 'POST',
						            url: baseDir + 'be/products/loadjs_select_product_variations',
						            data: { product_id: product_id, context: 'return_stock'},
						            beforeSend: function(){
						                $("#return_stock_loader").fadeIn("fast");
						            },
						            success: function(res){
						            	$("#return_stock_loader").fadeOut("fast");
						                $('#div_po_select_product_variation').html(res);
						                $('#modal_po_select_product_variation').modal('toggle');
						            },
						            error: function(){
						            	$("#return_stock_loader").fadeOut("fast");
					            	}
						        });
				            }
				            $('.select-basic').select2({
	                            placeholder: "Enter at least 1 character"
	                        });
				            $('#goods_return_note_quick_search').val('');
				            return false;
				        }
				    }).autocomplete('instance')._renderItem = function(ul, item) {
				        return $('<li>').append('<span class="font-weight-semibold pb-0">' + item.label + '</span>' + '<div class="text-muted font-size-sm pt-0"><b>SKU:</b>' + item.desc + '</div>').appendTo(ul);
				    };

				});

				$("#goods_return_note_product_id").on('change', function() {
		    		if (this.value != ''){
			    		$.ajax({
			                type: 'POST',
			                url: baseDir+'be/products/get_product/' + $("#goods_return_note_product_id").val(),
			                data: '',
			                dataType: 'json',
			                success: function(res){
			                    $.each(res, function(index, element) {
			                    	var product_id = element.product_id;
						            var product_variation_id = '0';
						            var line_id = '' + product_id + product_variation_id;

						            var product_unit_select = '<select class="form-control select-basic gren_unit_id" data-placeholder="Select Unit" id="gren_unit_id_' + line_id + '" name="gren_unit_id[]" style="width: 100%;" tabindex="-1" aria-hidden="true" required> \
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
				                    	if($('#gren_detail_qty_' + line_id).length && $('#gren_detail_qty_' + line_id).val().length){
							            	var detailqty = parseFloat($('#gren_detail_qty_' + line_id).val()) + 1;
							            	$('#gren_detail_qty_' + line_id).val(detailqty);
							            }else{
							            	var productPrice = 0;
											if (element.sale_price > 0){ productPrice = element.sale_price; } else { productPrice = element.regular_price; }
							            	$('#goods_return_note_details_table tr:first').before('<tr> \
							            		<td>' + element.product_name + '<br><div class="text-muted font-size-sm pt-0"><b>SKU:</b>' + element.product_sku_code + '</div> \
							            			<input id="gren_detail_id_' + line_id + '" name="gren_detail_id[]" type="hidden" class="form-control gren_detail_id" value="' + line_id + '"> \
							            			<input id="gren_detail_product_id_' + line_id + '" name="gren_detail_product_id[]" type="hidden" class="gren_detail_product_id" value="' + product_id + '"> \
							            			<input id="gren_detail_product_variation_id_' + line_id + '" name="gren_detail_product_variation_id[]" type="hidden" class="gren_detail_product_variation_id" value="' + product_variation_id + '"></td> \
							            		</td> \
							            		<td>' + product_unit_select + '</td> \
							            		<td><input id="gren_detail_qty_' + line_id + '" name="gren_detail_qty[]" type="number" class="form-control gren_detail_qty" min="1" value="1" autocomplete="off"></td> \
							            		<td><input id="gren_detail_cost_' + line_id + '" name="gren_detail_cost[]" type="number" class="form-control gren_detail_cost" value="' + productPrice + '" autocomplete="off"></td> \
							            		<td><span id="gren_label_detail_total_' + line_id + '">0.00</span><input id="gren_detail_total_' + line_id + '" name="gren_detail_total[]" type="hidden" class="form-control gren_detail_total" value="0"></td> \
							            		<td><a href="javascript:void(0);" class="gren_detail_remove" title="Remove product"><i class="icon-cancel-circle2 text-danger"></i></a></td> \
							            	</tr>');
							            }
										calculate_gren_detail_total(line_id);
					            		calculate_gren_totals();
					            	} else {
					            		$.ajax({
								            type: 'POST',
								            url: baseDir + 'be/products/loadjs_select_product_variations',
								            data: { product_id: product_id, context: 'return_stock'},
								            beforeSend: function(){
								                $("#return_stock_loader").fadeIn("fast");
								            },
								            success: function(res){
								            	$("#return_stock_loader").fadeOut("fast");
								                $('#div_po_select_product_variation').html(res);
								                $('#modal_po_select_product_variation').modal('toggle');
								            },
								            error: function(){
								            	$("#return_stock_loader").fadeOut("fast");
							            	}
								        });
					            	}
					            	$('.select-basic').select2({
			                            placeholder: "Enter at least 1 character"
			                        });
						            $("#goods_return_note_product_id").val('').change();
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

	                var product_unit_select = '<select class="form-control select-basic gren_unit_id" data-placeholder="Select Unit" id="gren_unit_id_' + line_id + '" name="gren_unit_id[]" style="width: 100%;" tabindex="-1" aria-hidden="true" required> \
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

					if($('#gren_detail_qty_' + line_id).length && $('#gren_detail_qty_' + line_id).val().length){
		            	var detailqty = parseFloat($('#gren_detail_qty_' + line_id).val()) + 1;
		            	$('#gren_detail_qty_' + line_id).val(detailqty);
		            }else{
						$('#goods_return_note_details_table tr:first').before('<tr> \
		            		<td>' + product_name + '<br><i class="badge badge-mark ml-2"></i> ' + variation_description + '<br><div class="text-muted font-size-sm pt-0"><b>SKU:</b>' + product_sku_code + '</div> \
		            			<input id="gren_detail_id_' + line_id + '" name="gren_detail_id[]" type="hidden" class="form-control gren_detail_id" value="' + line_id + '"> \
		            			<input id="gren_detail_product_id_' + line_id + '" name="gren_detail_product_id[]" type="hidden" class="gren_detail_product_id" value="' + product_id + '"> \
		            			<input id="gren_detail_product_variation_id_' + line_id + '" name="gren_detail_product_variation_id[]" type="hidden" class="gren_detail_product_variation_id" value="' + product_variation_id + '"> \
		            		</td> \
		            		<td>' + product_unit_select + '</td> \
		            		<td><input id="gren_detail_qty_' + line_id + '" name="gren_detail_qty[]" type="number" class="form-control gren_detail_qty" min="1" value="1" autocomplete="off"></td> \
		            		<td><input id="gren_detail_cost_' + line_id + '" name="gren_detail_cost[]" type="number" class="form-control gren_detail_cost" value="' + product_price + '" autocomplete="off"></td> \
		            		<td><span id="gren_label_detail_total_' + line_id + '">0.00</span><input id="gren_detail_total_' + line_id + '" name="gren_detail_total[]" type="hidden" class="form-control gren_detail_total" value="0"></td> \
		            		<td><a href="javascript:void(0);" class="gren_detail_remove" title="Remove product"><i class="icon-cancel-circle2 text-danger"></i></a></td> \
		            	</tr>');
					}
					$('.select-basic').select2({
                        placeholder: "Enter at least 1 character"
                    });
					$('#modal_po_select_product_variation').modal('toggle');

					calculate_gren_detail_total(line_id);
		            calculate_gren_totals();

				});
			</script>

