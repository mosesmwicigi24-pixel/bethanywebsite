		<!-- Main content -->
		<div class="content-wrapper">

			<?php if (!isset($stock_adjustment)): ?>

				<!-- Page header -->
				<div class="page-header">
					<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
						<div class="d-flex">
							<div class="breadcrumb">
								<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
								<a href="#" class="breadcrumb-item">Inventory</a>
								<a href="<?php echo base_url();?>be/inventory/stock_adjustments" class="breadcrumb-item">Stock Adjustments</a>
								<span class="breadcrumb-item active">Adjust Stock</span>
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

					<form id="frm_add_stock_adjustment" name="frm_add_stock_adjustment" method="post" onsubmit="return save_stock_adjustment();" autocomplete="false">
						<div class="spinner2 display-none" id="adjust_stock_loader">
	                        <div class="rect1"></div>
	                        <div class="rect2"></div>
	                        <div class="rect3"></div>
	                    </div>
						<div class="row">
							<div class="col-md-1"></div>
							<div class="col-md-10">
								<div class="card rounded-top-0">
									<div class="card-header bg-transparent header-elements-inline p-2">
										<h5 class="card-title font-weight-bold"><i class="icon-plus-circle2"></i> Adjust Stock</h5>		
										<div class="header-elements">
											<a href="<?php echo base_url(); ?>be/inventory/stock_adjustments" class="btn btn-sm btn-primary ml-2"><i class="icon-list3 mr-1"></i> Stock Adjustments List</a>	
										</div>
									</div>
							
									<div class="card-body">
										<div id="div_add_stock_adjustment_error" class="alert alert-danger display-none font-13"></div>
		                   				<div id="div_add_stock_adjustment_success" class="alert alert-success display-none font-13"></div>

										<div class="form-group mb-3">
											<div class="row">
												<div class="col-sm-3">
													<label>Outlet <span class="error">*</span></label>
													<select id="stock_adjustment_outlet_id" name="outlet_id" data-placeholder="Select Outlet" class="form-control select" data-fouc>
														<option value="">Select Outlet</option>
														<?php foreach ($outlets as $row): ?>
															<option value="<?php echo $row->outlet_id; ?>"><?php echo $row->outlet_name; ?></option>
														<?php endforeach; ?>
													</select>
												</div>
												<div class="col-sm-3">
													<label>Adjustment Date <span class="error">*</span></label>
													<input id="adjustment_date" name="adjustment_date" type="text" placeholder="" class="form-control pickadate">
												</div>
											</div>
										</div>
										<div class="form-group mb-4">
											<div class="row">
												<div class="col-sm-6">
													<label>Product Quick Search</label>
													<div class="input-group">
														<span class="input-group-prepend">
															<span class="input-group-text bg-primary text-white">
																<i class="icon-search4"></i>
															</span>
														</span>
														<input id="stock_adjustment_quick_search" name="stock_adjustment_quick_search" type="text" class="form-control border-left-0" placeholder="Search by product name, product code or product barcode">
													</div>
												</div>
												<div class="col-sm-6">
													<label>Or Select a Product Here</label>
													<div class="form-group form-group-feedback form-group-feedback-right mb-2">
														<select id="stock_adjustment_product_id" name="product_id" data-placeholder="Select Product" class="form-control select" data-fouc>
															<option value="">Select Product</option>
														</select>
														<div id="stock_adjustment_product_loader" class="form-control-feedback display-none">
															<i class="icon-spinner2 spinner text-success"></i>
														</div>
													</div>
												</div>
											</div>
										</div>
									
										<div class="table-responsive mb-3">
											<table class="table table-striped table-bordered">
												<thead>
													<tr>
														<th>Product Name</th>
														<th width="150px">Current Stock</th>
														<th width="150px">Adjusted Quantity</th>
														<th width="50px"></th>
													</tr>
												</thead>
												<tbody id="stock_adjustment_details_table">
													<tr>
														<td colspan="2" class="text-right"><strong>Total Qty:</strong></td>
														<td><strong><span id="sadj_label_total_detail_qty">0</span></strong><input id="sadj_total_detail_qty" name="sadj_total_detail_qty" type="hidden" class="form-control" value="0"></td>
														<td></td>
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
									<button type="submit" id="btn_add_stock_adjustment" class="btn bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> ADJUST STOCK</button>
								</div>
							</div>
							<div class="col-md-1"></div>	
						</div>
					</form>
				</div>
			<?php else: ?>
				<?php foreach ($stock_adjustment as $row): ?>
					<!-- Page header -->
					<div class="page-header">
						<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
							<div class="d-flex">
								<div class="breadcrumb">
									<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
									<a href="<?php echo base_url();?>be/inventory/stock_adjustments" class="breadcrumb-item">Stock Adjustments</a>
									<span class="breadcrumb-item active">Edit Stock Adjustment (<?php echo $row->stock_adjustment_number; ?>)</span>
								</div>

								<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
							</div>
						</div>
					</div>
					<!-- /page header -->


					<!-- Content area -->
					<div class="content pt-0">
						<form id="frm_edit_stock_adjustment" name="frm_edit_stock_adjustment" method="post" onsubmit="return update_stock_adjustment();" autocomplete="false">
							<div class="spinner2 display-none" id="adjust_stock_loader">
		                        <div class="rect1"></div>
		                        <div class="rect2"></div>
		                        <div class="rect3"></div>
		                    </div>
							<div class="row">
								<div class="col-md-1"></div>
								<div class="col-md-10">
									<div class="card rounded-top-0">
										<div class="card-header bg-transparent header-elements-inline p-2">
											<h5 class="card-title font-weight-bold">Edit Stock Adjustment <b>(<?php echo $row->stock_adjustment_number; ?>)</b></h5>		
											<div class="header-elements">
												<a href="<?php echo base_url(); ?>be/inventory/stock_adjustments" class="btn btn-sm btn-primary ml-2"><i class="icon-list3 mr-1"></i> Stock Adjustments List</a>	
											</div>
										</div>
								
										<div class="card-body">
											<div id="div_edit_stock_adjustment_error" class="alert alert-danger display-none font-13"></div>
			                   				<div id="div_edit_stock_adjustment_success" class="alert alert-success display-none font-13"></div>

			                   				<input id="stock_adjustment_id" name="stock_adjustment_id" type="hidden" value="<?php echo $row->stock_adjustment_id; ?>">

											<div class="form-group mb-3">
												<div class="row">
													<div class="col-sm-4">
														<label>Outlet <span class="error">*</span></label>
														<input id="outlet_id" name="outlet_id" type="hidden" value="<?php echo $row->outlet_id; ?>">
														<input id="outlet_name" name="outlet_name" readonly type="text" class="form-control" value="<?php echo $row->outlet_name; ?>">
													</div>
													<div class="col-sm-4">
														<label>Adjustment Date <span class="error">*</span></label>
														<input id="adjustment_date" name="adjustment_date" type="text" placeholder="" class="form-control pickadate" value="<?php echo $row->adjustment_date; ?>">
													</div>
												</div>
											</div>
											<!-- <div class="form-group mb-1">
												<div class="row">
													<div class="col-sm-12">
														<label>Product Quick Search</label>
														<div class="input-group">
															<span class="input-group-prepend">
																<span class="input-group-text bg-primary border-primary text-white">
																	<i class="icon-search4"></i>
																</span>
															</span>
															<input id="stock_adjustment_quick_search" name="stock_adjustment_quick_search" type="text" class="form-control border-left-0" placeholder="Search by product name, product code or product barcode">
														</div>
													</div>
												</div>
											</div> -->
											
											<div class="table-responsive mb-3">
												<table class="table table-striped table-bordered">
													<thead>
														<tr>
															<th>Product Name</th>
															<th width="150px">Current Stock</th>
															<th width="150px">Adjusted Quantity</th>
															<th width="50px"></th>
														</tr>
													</thead>
													<tbody id="stock_adjustment_details_table">
														<?php foreach ($stock_adjustment_details as $row2): ?>
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
																	<input id="sadj_detail_id_<?php echo $line_id; ?>" name="sadj_detail_id[]" type="hidden" class="form-control sadj_detail_id" value="<?php echo $line_id; ?>">
																	<input id="sadj_detail_product_id_<?php echo $line_id; ?>" name="sadj_detail_product_id[]" type="hidden" class="sadj_detail_product_id" value="<?php echo $row2->product_id; ?>">
																	<input id="sadj_detail_product_variation_id_<?php echo $line_id; ?>" name="sadj_detail_product_variation_id[]" type="hidden" class="sadj_detail_product_variation_id" value="<?php echo $row2->product_variation_id; ?>">
																</td>
																<td>
																	<span id="sadj_span_detail_stock_<?php echo $line_id; ?>"><?php echo $row2->current_quantity; ?></span>
																	<input id="sadj_detail_stock_<?php echo $line_id; ?>" name="sadj_detail_stock[]" type="hidden" class="form-control sadj_detail_stock" value="<?php echo $row2->current_quantity; ?>" autocomplete="off">
																</td>
																<td>
																	<input id="sadj_detail_qty_<?php echo $line_id; ?>" name="sadj_detail_qty[]" type="number" class="form-control sadj_detail_qty" value="<?php echo $row2->adjusted_quantity; ?>" autocomplete="off">
																</td>
																<td><a href="javascript:void(0);" class="sadj_detail_remove" title="Remove product"><i class="icon-cancel-circle2 text-danger"></i></a></td>
															</tr>
														<?php endforeach; ?>
														<tr>
															<td colspan="2"><strong>Total Qty</strong></td>
															<td><strong><span id="sadj_label_total_detail_qty"><?php echo number_format($row->total_quantity); ?></span></strong><input id="sadj_total_detail_qty" name="sadj_total_detail_qty" type="hidden" class="form-control" value="<?php echo $row->total_quantity; ?>"></td>
															<td></td>
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
										<button type="submit" id="btn_edit_stock_adjustment" class="btn bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
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

					//baseDir + 'be/inventory/get_auto_purchase_order_products',
					//Stock Adjustment ADD
				    $('#stock_adjustment_quick_search').autocomplete({
				        minLength: 1,
				        source: function(request, response) {
					        $.ajax({
					            url: baseDir + 'be/inventory/get_auto_adjust_stock_products',
					            dataType: "json",
					            data: {
					                term : request.term,
					                outlet_id : $("#stock_adjustment_outlet_id").val()
					            },
					            success: function(data) {
					                response(data);
					            }
					        });
					    },
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
				        	var outletID = $("#stock_adjustment_outlet_id").val();

					    	if (outletID == '' || outletID == null) {
					    		swal({
			                        type: 'warning',
			                        title: 'Please select Outlet first'
			                    });
					    	} else {
					    		$('#stock_adjustment_quick_search').val(ui.item.label);

					    		var product_id = ui.item.id;
					            var product_variation_id = '0';
					            var line_id = '' + product_id + product_variation_id;

					            if (ui.item.type == 'Simple') {
						            if($('#sadj_detail_qty_' + line_id).length && $('#sadj_detail_qty_' + line_id).val().length){
						            	var detailqty = parseFloat($('#sadj_detail_qty_' + line_id).val()) + 1;
						            	$('#sadj_detail_qty_' + line_id).val(detailqty);
						            	$('#sadj_detail_stock_' + line_id).val(ui.item.stock);
						            	$('#sadj_span_detail_stock_' + line_id).html(ui.item.stock);
						            }else{
						            	$('#stock_adjustment_details_table tr:first').before('<tr> \
						            		<td>' + ui.item.label + '<br><div class="text-muted font-size-sm pt-0"><b>SKU:</b>&nbsp;' + ui.item.desc + '</div> \
						            			<input id="sadj_detail_id_' + line_id + '" name="sadj_detail_id[]" type="hidden" class="form-control sadj_detail_id" value="' + line_id + '"> \
						            			<input id="sadj_detail_product_id_' + line_id + '" name="sadj_detail_product_id[]" type="hidden" class="sadj_detail_product_id" value="' + product_id + '"> \
					            				<input id="sadj_detail_product_variation_id_' + line_id + '" name="sadj_detail_product_variation_id[]" type="hidden" class="sadj_detail_product_variation_id" value="' + product_variation_id + '"> \
						            		</td> \
						            		<td><span id="sadj_span_detail_stock_' + line_id + '">' + ui.item.stock + '</span><input id="sadj_detail_stock_' + line_id + '" name="sadj_detail_stock[]" type="hidden" class="form-control sadj_detail_stock" value="' + ui.item.stock + '" autocomplete="off"></td> \
						            		<td><input id="sadj_detail_qty_' + line_id + '" name="sadj_detail_qty[]" type="number" class="form-control sadj_detail_qty" value="' + ui.item.stock + '" autocomplete="off"></td> \
						            		<td><a href="javascript:void(0);" class="sadj_detail_remove" title="Remove product"><i class="icon-cancel-circle2 text-danger"></i></a></td> \
						            	</tr>');
						            }
						            calculate_sadj_detail_total(line_id);
						            calculate_sadj_totals();
						        } else {
						        	$.ajax({
							            type: 'POST',
							            url: baseDir + 'be/products/loadjs_select_product_variations',
							            data: { product_id: product_id, context: 'adjust_stock', outlet_id: outletID},
							            beforeSend: function(){
							                $("#adjust_stock_loader").fadeIn("fast");
							            },
							            success: function(res){
							            	$("#adjust_stock_loader").fadeOut("fast");
							                $('#div_po_select_product_variation').html(res);
							                $('#modal_po_select_product_variation').modal('toggle');
							            },
							            error: function(){
							            	$("#adjust_stock_loader").fadeOut("fast");
						            	}
							        });
						        }

					            $('#stock_adjustment_quick_search').val('');
					    	}
				            
				            return false;
				        }
				    }).autocomplete('instance')._renderItem = function(ul, item) {
				        return $('<li>').append('<span class="font-weight-semibold pb-0">' + item.label + '</span>' + '<div class="text-muted font-size-sm pt-0"><b>SKU:</b>' + item.desc + '</div>').appendTo(ul);
				    };

				    $("#stock_adjustment_product_id").on('change', function() {
				    	var outletID = $("#stock_adjustment_outlet_id").val();

				    	if (outletID == '' || outletID == null) {
				    		swal({
		                        type: 'warning',
		                        title: 'Please select Outlet first'
		                    });
				    	} else {
				    		if (this.value != ''){
					    		$.ajax({
					                type: 'POST',
					                url: baseDir+'be/products/get_outlet_product',
					                data: {
					                	outlet_id: $("#stock_adjustment_outlet_id").val(),
					                	product_id: $("#stock_adjustment_product_id").val()
					                },
					                dataType: 'json',
					                success: function(res){
					                    $.each(res, function(index, element) {

					                    	var product_id = element.product_id;
								            var product_variation_id = '0';
								            var line_id = '' + product_id + product_variation_id;

								            if (element.product_type == 'Simple'){
						                    	if($('#sadj_detail_qty_' + line_id).length && $('#sadj_detail_qty_' + line_id).val().length){
									            	var detailqty = parseFloat($('#sadj_detail_qty_' + line_id).val()) + 1;
									            	$('#sadj_detail_qty_' + line_id).val(detailqty);
									            	$('#sadj_detail_stock_' + line_id).val(element.available_stock);
									            	$('#sadj_span_detail_stock_' + line_id).html(element.available_stock);
									            }else{
									            	$('#stock_adjustment_details_table tr:first').before('<tr> \
									            		<td>' + element.product_name + '<br><div class="text-muted font-size-sm pt-0"><b>SKU:</b>&nbsp;' + element.product_sku_code + '</div> \
									            			<input id="sadj_detail_id_' + line_id + '" name="sadj_detail_id[]" type="hidden" class="form-control sadj_detail_id" value="' + line_id + '"> \
									            			<input id="sadj_detail_product_id_' + line_id + '" name="sadj_detail_product_id[]" type="hidden" class="sadj_detail_product_id" value="' + product_id + '"> \
		            										<input id="sadj_detail_product_variation_id_' + line_id + '" name="sadj_detail_product_variation_id[]" type="hidden" class="sadj_detail_product_variation_id" value="' + product_variation_id + '"> \
									            		</td> \
									            		<td><span id="sadj_span_detail_stock_' + line_id + '">' + element.available_stock + '</span><input id="sadj_detail_stock_' + line_id + '" name="sadj_detail_stock[]" type="hidden" class="form-control sadj_detail_stock" value="' + element.available_stock + '" autocomplete="off"></td> \
									            		<td><input id="sadj_detail_qty_' + line_id + '" name="sadj_detail_qty[]" type="number" class="form-control sadj_detail_qty" value="' + element.available_stock + '" autocomplete="off"></td> \
									            		<td><a href="javascript:void(0);" class="sadj_detail_remove" title="Remove product"><i class="icon-cancel-circle2 text-danger"></i></a></td> \
									            	</tr>');
									            }
									            calculate_sadj_detail_total(element.product_id);
									            calculate_sadj_totals();
									        } else {
									        	$.ajax({
										            type: 'POST',
										            url: baseDir + 'be/products/loadjs_select_product_variations',
										            data: { product_id: product_id, context: 'adjust_stock', outlet_id: outletID},
										            beforeSend: function(){
										                $("#adjust_stock_loader").fadeIn("fast");
										            },
										            success: function(res){
										            	$("#adjust_stock_loader").fadeOut("fast");
										                $('#div_po_select_product_variation').html(res);
										                $('#modal_po_select_product_variation').modal('toggle');
										            },
										            error: function(){
										            	$("#adjust_stock_loader").fadeOut("fast");
									            	}
										        });
									        }
								            $("#stock_adjustment_product_id").val('').change();
					                    });
					                },
					                error: function(){
					                }
					            });
					    	}
				    	}
				    });

				    $(document).on('click', '.lnk-select-product-variation', function(e){
						e.preventDefault();

						var product_id = $(this).attr("data-product-id");
						var product_variation_id = $(this).attr("data-product-variation-id");
						var line_id = '' + product_id + product_variation_id;
						var product_name = $('#spv_product_name').val();
						var product_sku_code = $('#spv_product_sku_code').val();
						var product_price = $(this).attr("data-product-price");
						var available_stock = $(this).attr("data-available-stock");
						var variationDescription = $(this).attr("data-variation-description");
						var variation_description = variationDescription.substring(0, variationDescription.length -2);

						if($('#sadj_detail_qty_' + line_id).length && $('#sadj_detail_qty_' + line_id).val().length){
			            	var detailqty = parseFloat($('#sadj_detail_qty_' + line_id).val()) + 1;
			            	$('#sadj_detail_qty_' + line_id).val(detailqty);
			            	$('#sadj_detail_stock_' + line_id).val(available_stock);
			            	$('#sadj_span_detail_stock_' + line_id).html(available_stock);
			            }else{
			            	$('#stock_adjustment_details_table tr:first').before('<tr> \
			            		<td>' + product_name + '<br><i class="badge badge-mark ml-2"></i> ' + variation_description + '<br><div class="text-muted font-size-sm pt-0"><b>SKU:</b>&nbsp;' + product_sku_code + '</div> \
			            			<input id="sadj_detail_id_' + line_id + '" name="sadj_detail_id[]" type="hidden" class="form-control sadj_detail_id" value="' + line_id + '"> \
			            			<input id="sadj_detail_product_id_' + line_id + '" name="sadj_detail_product_id[]" type="hidden" class="sadj_detail_product_id" value="' + product_id + '"> \
		            				<input id="sadj_detail_product_variation_id_' + line_id + '" name="sadj_detail_product_variation_id[]" type="hidden" class="sadj_detail_product_variation_id" value="' + product_variation_id + '"> \
			            		</td> \
			            		<td><span id="sadj_span_detail_stock_' + line_id + '">' + available_stock + '</span><input id="sadj_detail_stock_' + line_id + '" name="sadj_detail_stock[]" type="hidden" class="form-control sadj_detail_stock" value="' + available_stock + '" autocomplete="off"></td> \
			            		<td><input id="sadj_detail_qty_' + line_id + '" name="sadj_detail_qty[]" type="number" class="form-control sadj_detail_qty" value="' + available_stock + '" autocomplete="off"></td> \
			            		<td><a href="javascript:void(0);" class="sadj_detail_remove" title="Remove product"><i class="icon-cancel-circle2 text-danger"></i></a></td> \
			            	</tr>');
			            }

						$('#modal_po_select_product_variation').modal('toggle');

						calculate_sadj_detail_total(line_id);
			            calculate_sadj_totals();

					});

				    

				});
			</script>

