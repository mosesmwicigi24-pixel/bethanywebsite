		<!-- Main content -->
		<div class="content-wrapper">

			<?php if (!isset($credit_note)): ?>

				<!-- Page header -->
				<div class="page-header">
					<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
						<div class="d-flex">
							<div class="breadcrumb">
								<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
								<a href="#" class="breadcrumb-item">Inventory</a>
								<a href="<?php echo base_url();?>be/inventory/credit_notes" class="breadcrumb-item">Credit Notes</a>
								<span class="breadcrumb-item active">New Credit Note</span>
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

					<form id="frm_add_credit_note" name="frm_add_credit_note" method="post" onsubmit="return save_credit_note();" autocomplete="false">
						<div class="row">
							<div class="col-md-1"></div>
							<div class="col-md-10">
								<div class="card rounded-top-0">
									<div class="card-header bg-transparent header-elements-inline p-2">
										<h5 class="card-title font-weight-bold"><i class="icon-plus-circle2"></i> New Credit Note</h5>		
										<div class="header-elements">
											<a href="<?php echo base_url(); ?>be/inventory/credit_notes" class="btn btn-sm btn-primary ml-2"><i class="icon-list3 mr-1"></i> Credit Notes List</a>	
										</div>
									</div>
							
									<div class="card-body">
										<div id="div_add_credit_note_error" class="alert alert-danger display-none font-13"></div>
		                   				<div id="div_add_credit_note_success" class="alert alert-success display-none font-13"></div>

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
										<div class="form-group mb-1">
											<div class="row">
												<div class="col-sm-6">
													<label>Product Quick Search</label>
													<div class="input-group">
														<span class="input-group-prepend">
															<span class="input-group-text bg-primary border-primary text-white">
																<i class="icon-search4"></i>
															</span>
														</span>
														<input id="credit_note_quick_search" name="credit_note_quick_search" type="text" class="form-control form-control-lg border-left-0" placeholder="Search by product name, product code or product barcode">
													</div>
												</div>
												<div class="col-sm-6">
													<label>Or Select a Product Here</label>
													<div class="form-group form-group-feedback form-group-feedback-right mb-2">
														<select id="credit_note_product_id" name="product_id" data-placeholder="Select Product" class="form-control select" data-fouc>
															<option value="">Select Product</option>
															<?php foreach ($products as $row2): ?>
																<option value="<?php echo $row2->product_id; ?>"><?php echo $row2->product_name; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
								<div class="card rounded-top-0">
									<div class="card-body">		
										<div class="table-responsive">
											<table class="table">
												<thead>
													<tr>
														<th>Product Name</th>
														<th width="120px">Returned Qty</th>
														<th width="120px">Cost</th>														
														<th width="120px">Total</th>
														<th width="20px"></th>
													</tr>
												</thead>
												<tbody id="credit_note_details_table">
													<tr>
														<td colspan="3"><strong>Total Qty</strong></td>
														<td><strong><span id="gren_label_total_detail_qty">0</span></strong><input id="gren_total_detail_qty" name="gren_total_detail_qty" type="hidden" class="form-control" value="0"></td>
														<td></td>
													</tr>
													<tr>
														<td colspan="3"><strong>SubTotal</strong></td>
														<td><strong><span id="gren_label_total_detail_subtotal">KES0.00</span></strong><input id="gren_total_detail_subtotal" name="gren_total_detail_subtotal" type="hidden" class="form-control" value="0"></td>
														<td></td>
													</tr>
													<tr>
														<td colspan="3"><strong>Freight</strong></td>
														<td><input id="gren_freight_cost" name="freight_cost" type="number" class="form-control" value="0.00" min="0"></td>
														<td></td>
													</tr>
													<tr>
														<td colspan="3"><strong>Total</strong></td>
														<td><strong><span id="gren_label_total_detail_total">KES0.00</span></strong><input id="gren_total_detail_total" name="gren_total_detail_total" type="hidden" class="form-control" value="0"></td>
														<td></td>
													</tr>
												</tbody>
											</table>
										</div>
									</div>
								</div>	
								<div class="card rounded-top-0">
									<div class="card-body">
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
									<button type="submit" id="btn_add_credit_note" class="btn bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> RETURN STOCK</button>
								</div>
							</div>
							<div class="col-md-1"></div>	
						</div>
					</form>
				</div>
			<?php else: ?>
				<?php foreach ($credit_note as $row): ?>
					<!-- Page header -->
					<div class="page-header">
						<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
							<div class="d-flex">
								<div class="breadcrumb">
									<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
									<a href="<?php echo base_url();?>be/inventory/credit_notes" class="breadcrumb-item">Credit Notes</a>
									<span class="breadcrumb-item active">Edit Credit Note (<?php echo $row->credit_note_number; ?>)</span>
								</div>

								<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
							</div>
						</div>
					</div>
					<!-- /page header -->


					<!-- Content area -->
					<div class="content pt-0">
						<form id="frm_edit_credit_note" name="frm_edit_credit_note" method="post" onsubmit="return update_credit_note();" autocomplete="false">
							<div class="row">
								<div class="col-md-1"></div>
								<div class="col-md-10">
									<div class="card rounded-top-0">
										<div class="card-header bg-transparent header-elements-inline p-2">
											<h5 class="card-title font-weight-bold">Edit Credit Note <b>(<?php echo $row->credit_note_number; ?>)</b></h5>		
											<div class="header-elements">
												<a href="<?php echo base_url(); ?>be/inventory/credit_notes" class="btn btn-sm btn-primary ml-2"><i class="icon-list3 mr-1"></i> Credit Notes List</a>	
											</div>
										</div>
								
										<div class="card-body">
											<div id="div_edit_credit_note_error" class="alert alert-danger display-none font-13"></div>
			                   				<div id="div_edit_credit_note_success" class="alert alert-success display-none font-13"></div>

			                   				<input id="credit_note_id" name="credit_note_id" type="hidden" value="<?php echo $row->credit_note_id; ?>">

											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-4">
														<label>Returned From <span class="error">*</span></label>
														<select id="outlet_id" name="outlet_id" data-placeholder="Select Outlet" class="form-control select" data-fouc>
															<option value="">Select Outlet</option>
															<?php foreach ($outlets as $row2): ?>
																<option value="<?php echo $row2->outlet_id; ?>" <?php if ($row2->outlet_id == $row->outlet_id){ echo 'selected';} ?>><?php echo $row2->outlet_name; ?></option>
															<?php endforeach; ?>
														</select>
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
											<div class="form-group mb-1">
												<div class="row">
													<div class="col-sm-6">
														<label>Product Quick Search</label>
														<div class="input-group">
															<span class="input-group-prepend">
																<span class="input-group-text bg-primary border-primary text-white">
																	<i class="icon-search4"></i>
																</span>
															</span>
															<input id="credit_note_quick_search" name="credit_note_quick_search" type="text" class="form-control form-control-lg border-left-0" placeholder="Search by product name, product code or product barcode">
														</div>
													</div>
													<div class="col-sm-6">
														<label>Or Select a Product Here</label>
														<div class="form-group form-group-feedback form-group-feedback-right mb-2">
															<select id="credit_note_product_id" name="product_id" data-placeholder="Select Product" class="form-control select" data-fouc>
																<option value="">Select Product</option>
																<?php foreach ($products as $row2): ?>
																	<option value="<?php echo $row2->product_id; ?>"><?php echo $row2->product_name; ?></option>
																<?php endforeach; ?>
															</select>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="card rounded-top-0">
										<div class="card-body">		
											<div class="table-responsive">
												<table class="table">
													<thead>
														<tr>
															<th>Product Name</th>
															<th width="120px">Returned Qty</th>
															<th width="120px">Cost</th>														
															<th width="120px">Total</th>
															<th width="20px"></th>
														</tr>
													</thead>
													<tbody id="credit_note_details_table">
														<?php foreach ($credit_note_details as $row2): ?>
															<tr>
																<td>
																	<?php echo $row2->product_name; ?><br>
																	<div class="text-muted font-size-sm pt-0"><b>SKU:</b><?php echo $row2->product_sku_code; ?></div>
																	<input id="gren_detail_id_<?php echo $row2->product_id; ?>" name="gren_detail_id[]" type="hidden" class="form-control gren_detail_id" value="<?php echo $row2->product_id; ?>">
																</td>
																<td>
																	<input id="gren_detail_qty_<?php echo $row2->product_id; ?>" name="gren_detail_qty[]" type="number" class="form-control gren_detail_qty" min="1" value="<?php echo $row2->returned_quantity; ?>" autocomplete="off">
																</td>
																<td>
																	<input id="gren_detail_cost_<?php echo $row2->product_id; ?>" name="gren_detail_cost[]" type="number" class="form-control gren_detail_cost" value="<?php echo $row2->unit_price; ?>" autocomplete="off">
																</td>
																<td>
																	<span id="gren_label_detail_total_<?php echo $row2->product_id; ?>">KES<?php echo number_format($row2->detail_total_amount,2); ?></span>
																	<input id="gren_detail_total_<?php echo $row2->product_id; ?>" name="gren_detail_total[]" type="hidden" class="form-control gren_detail_total" value="<?php echo $row2->detail_total_amount; ?>">
																</td>
																<td><a href="javascript:void(0);" class="gren_detail_remove" title="Remove product"><i class="icon-cancel-circle2 text-danger"></i></a></td>
															</tr>
														<?php endforeach; ?>
														<tr>
															<td colspan="3"><strong>Total Qty</strong></td>
															<td><strong><span id="gren_label_total_detail_qty"><?php echo number_format($row->total_quantity); ?></span></strong><input id="gren_total_detail_qty" name="gren_total_detail_qty" type="hidden" class="form-control" value="<?php echo $row->total_quantity; ?>"></td>
															<td></td>
														</tr>
														<tr>
															<td colspan="3"><strong>SubTotal</strong></td>
															<td><strong><span id="gren_label_total_detail_subtotal">KES<?php echo number_format($row->sub_total,2); ?></span></strong><input id="gren_total_detail_subtotal" name="gren_total_detail_subtotal" type="hidden" class="form-control" value="<?php echo $row->sub_total; ?>"></td>
															<td></td>
														</tr>
														<tr>
															<td colspan="3"><strong>Freight</strong></td>
															<td><input id="gren_freight_cost" name="freight_cost" type="number" class="form-control" value="<?php echo $row->freight_cost; ?>" min="0"></td>
															<td></td>
														</tr>
														<tr>
															<td colspan="3"><strong>Total</strong></td>
															<td><strong><span id="gren_label_total_detail_total">KES<?php echo number_format($row->total_amount,2); ?></span></strong><input id="gren_total_detail_total" name="gren_total_detail_total" type="hidden" class="form-control" value="<?php echo $row->total_amount; ?>"></td>
															<td></td>
														</tr>
													</tbody>
												</table>
											</div>
										</div>
									</div>	
									<div class="card rounded-top-0">
										<div class="card-body">
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
										<button type="submit" id="btn_edit_credit_note" class="btn bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
									</div>
								</div>
								<div class="col-md-1"></div>	
							</div>
						</form>
					</div>
				<?php endforeach; ?>
			<?php endif; ?>

			<script type="text/javascript">
				$(document).ready(function() {
					//Credit Note ADD
				    $('#credit_note_quick_search').autocomplete({
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
				            $('#credit_note_quick_search').val(ui.item.label);
				            if($('#gren_detail_qty_' + ui.item.id).length && $('#gren_detail_qty_' + ui.item.id).val().length){
				            	var detailqty = parseInt($('#gren_detail_qty_' + ui.item.id).val()) + 1;
				            	$('#gren_detail_qty_' + ui.item.id).val(detailqty);
				            }else{
				            	// $('#credit_note_details_table tr:first').before('<tr><td>' + ui.item.label + '<br><div class="text-muted font-size-sm pt-0"><b>SKU:</b>' + ui.item.desc + '</div><input id="gren_detail_id_' + ui.item.id + '" name="gren_detail_id[]" type="hidden" class="form-control gren_detail_id" value="' + ui.item.id + '"></td><td><input id="gren_detail_qty_' + ui.item.id + '" name="gren_detail_qty[]" type="number" class="form-control gren_detail_qty" min="1" value="1" autocomplete="off"></td><td><a href="javascript:void(0);" class="gren_detail_remove" title="Remove product"><i class="icon-cancel-circle2 text-danger"></i></a></td></tr>');

				            	$('#credit_note_details_table tr:first').before('<tr><td>' + ui.item.label + '<br><div class="text-muted font-size-sm pt-0"><b>SKU:</b>' + ui.item.desc + '</div><input id="gren_detail_id_' + ui.item.id + '" name="gren_detail_id[]" type="hidden" class="form-control gren_detail_id" value="' + ui.item.id + '"></td><td><input id="gren_detail_qty_' + ui.item.id + '" name="gren_detail_qty[]" type="number" class="form-control gren_detail_qty" min="1" value="1" autocomplete="off"></td><td><input id="gren_detail_cost_' + ui.item.id + '" name="gren_detail_cost[]" type="number" class="form-control gren_detail_cost" value="' + ui.item.price + '" autocomplete="off"></td><td><span id="gren_label_detail_total_' + ui.item.id + '">0.00</span><input id="gren_detail_total_' + ui.item.id + '" name="gren_detail_total[]" type="hidden" class="form-control gren_detail_total" value="0"></td><td><a href="javascript:void(0);" class="gren_detail_remove" title="Remove product"><i class="icon-cancel-circle2 text-danger"></i></a></td></tr>');
				            }
				            calculate_gren_detail_total(ui.item.id);
				            calculate_gren_totals();

				            $('#credit_note_quick_search').val('');
				            return false;
				        }
				    }).autocomplete('instance')._renderItem = function(ul, item) {
				        return $('<li>').append('<span class="font-weight-semibold pb-0">' + item.label + '</span>' + '<div class="text-muted font-size-sm pt-0"><b>SKU:</b>' + item.desc + '</div>').appendTo(ul);
				    };

				});

				$("#credit_note_product_id").on('change', function() {
		    		if (this.value != ''){
			    		$.ajax({
			                type: 'POST',
			                url: baseDir+'be/products/get_product/' + $("#credit_note_product_id").val(),
			                data: '',
			                dataType: 'json',
			                success: function(res){
			                    $.each(res, function(index, element) {
			                    	
			                    	if($('#gren_detail_qty_' + element.product_id).length && $('#gren_detail_qty_' + element.product_id).val().length){
						            	var detailqty = parseInt($('#gren_detail_qty_' + element.product_id).val()) + 1;
						            	$('#gren_detail_qty_' + element.product_id).val(detailqty);
						            }else{
						            	var productPrice = 0;
										if (element.sale_price > 0){ productPrice = element.sale_price; } else { productPrice = element.regular_price; }
						            	$('#credit_note_details_table tr:first').before('<tr><td>' + element.product_name + '<br><div class="text-muted font-size-sm pt-0"><b>SKU:</b>' + element.product_sku_code + '</div><input id="gren_detail_id_' + element.product_id + '" name="gren_detail_id[]" type="hidden" class="form-control gren_detail_id" value="' + element.product_id + '"></td><td><input id="gren_detail_qty_' + element.product_id + '" name="gren_detail_qty[]" type="number" class="form-control gren_detail_qty" min="1" value="1" autocomplete="off"></td><td><input id="gren_detail_cost_' + element.product_id + '" name="gren_detail_cost[]" type="number" class="form-control gren_detail_cost" value="' + productPrice + '" autocomplete="off"></td><td><span id="gren_label_detail_total_' + element.product_id + '">0.00</span><input id="gren_detail_total_' + element.product_id + '" name="gren_detail_total[]" type="hidden" class="form-control gren_detail_total" value="0"></td><td><a href="javascript:void(0);" class="gren_detail_remove" title="Remove product"><i class="icon-cancel-circle2 text-danger"></i></a></td></tr>');
						            }
									calculate_gren_detail_total(element.product_id);
				            		calculate_gren_totals();
						            $("#credit_note_product_id").val('').change();
			                    });
			                },
			                error: function(){
			                }
			            });
			    	}
			    });
			</script>

