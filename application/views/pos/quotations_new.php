<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
            	<?php if (!isset($pos_quotation)): ?>
            		<div class="nk-block nk-block-lg mb-2">
	                    <div class="nk-block-head nk-block-head-sm">
	                        <div class="nk-block-between">
	                            <div class="nk-block-head-content"><h5 class="nk-block-title page-title"><em class="icon ni ni-plus-c"></em> New Quotation</h5></div>
	                        </div>
	                    </div>
	                </div>
	                <form id="frm_add_quotation" name="frm_add_quotation" method="post" onsubmit="return save_quotation();">
	                    <div id="register_container" class="quotations clearfix">
	                        <div class="row register">
	                            <input type="hidden" id="transaction_type" name="transaction_type" value="Add">	                            

	                            <div class="col-md-12">
	                                <div class="row" id="div_quotation_body">

	                                    <div class="col-lg-8 col-md-7 col-sm-12 col-xs-12 no-padding-right no-padding-left">
	                                        <div class="spinner display-none" id="details_section_loader">
	                                            <div class="rect1"></div>
	                                            <div class="rect2"></div>
	                                            <div class="rect3"></div>
	                                        </div>
	                                        <div class="register-box register-items-form pt-3">
	                                            <div class="row pl-3 pr-3 pb-2">	
	                                            	<div class="col-md-6">
	                                            		<div class="form-group">
			                                                <label for="q_invoice_id">Select Customer</label>
			                                                <select class="form-control select2" data-placeholder="Select Customer" id="q_customer_id" name="customer_id" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
			                                                    <option value="">Select Customer</option>
			                                                    <?php foreach ($customers as $row): ?>
			                                                    	<option value="<?php echo $row->customer_id; ?>"><?php echo $row->first_name . ' ' . $row->last_name; ?> <?php if ($row->email_address != ''){ echo '[' . $row->email_address . ']'; } ?></option>
			                                                    <?php endforeach; ?>
			                                                </select>
			                                            </div>
	                                            	</div>  
	                                            	<div class="col-md-3">
	                                            		<div class="form-group">
			                                                <label for="q_date">Quotation Date</label>
			                                                <input type="text" class="form-control datepicker" id="q_date" name="quotation_date" placeholder="" />
			                                            </div>
	                                            	</div>                                          	
	                                            	<div class="col-md-3">
	                                            		<div class="form-group">
			                                                <label for="q_valid_until">Valid Until</label>
			                                                <input type="text" class="form-control datepicker" id="q_valid_until" name="valid_until" placeholder="" />
			                                            </div>
	                                            	</div>
	                                            </div>
	                                            <div class="row pl-3 pr-3 pb-2">	
	                                            	<div class="col-md-12">
	                                            		<div class="form-group">
			                                                <label for="q_invoice_id">Select Product</label>
			                                                <select class="form-control select2" data-placeholder="Select Product" id="q_product_id" name="product_id" style="width: 100%;" tabindex="-1" aria-hidden="true">
			                                                    <option value="">Select Product</option>
			                                                    <?php foreach ($products as $row): ?>
			                                                    	<option value="<?php echo $row->product_id; ?>"><?php echo $row->product_name; ?></option>
			                                                    <?php endforeach; ?>
			                                                </select>
			                                            </div>
	                                            	</div>
	                                            </div>
	                                        </div>
	                                        <div class="register-box register-items paper-cut">
	                                            <div class="register-items-holder table-responsive">
	                                                <table id="register" class="table table-hover">
	                                                    <thead>
	                                                        <tr class="register-items-header bg-lighter">
	                                                            <th class="item_name_heading fw-bold text-left" width="200px">Item</th>
	                                                            <th class="quotation_unit fw-bold text-left" width="130px">Unit</th>
	                                                            <th class="quotation_price fw-bold text-left" width="110px">Unit Price</th>
	                                                            <th class="quotation_quantity fw-bold text-left" width="80px">Qty</th>
	                                                            <th class="fw-bold text-left" width="100px ">Subtotal</th>
	                                                            <th class="fw-bold  text-center" width="60px"></th>
	                                                        </tr>
	                                                    </thead>

	                                                    <tbody id="quotation_details_table" class="register-item-content">	                                                        
	                                                    	<!-- <tr class="cart_content_area">
                                                                <td colspan="5">
                                                                    <div class="text-center p-1">
                                                                        <h6 class=" text-warning">There are no items added yet</h6>
                                                                    </div>
                                                                </td>
                                                            </tr> -->
	                                                    </tbody>
	                                                </table>
	                                            </div>
	                                        </div>
	                                    </div>

	                                    <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12 p-2">
	                                        <div class="spinner display-none" id="totals_section_loader">
	                                            <div class="rect1"></div>
	                                            <div class="rect2"></div>
	                                            <div class="rect3"></div>
	                                        </div>
	                                        
	                                        

	                                        <div class="register-box register-summary paper-cut">

                                                <ul class="list-group">
                                                    <li class="list-group-item global-discount-group pt-1 pb-1">
                                                        <div class="key">Total Quantity:</div>
                                                        <div class="value pull-right" id="q_label_total_detail_qty">0</div>
                                                        <input type="hidden" id="q_total_detail_qty" name="total_detail_qty">
                                                    </li>
                                                    <li class="sub-total list-group-item pb-1">
                                                        <span class="key">Sub Total:</span>
                                                        <span class="value">                                                            
                                                            <?php echo $default_currency; ?> <span id="q_label_total_detail_subtotal">0.00</span>
                                                            <input type="hidden" id="q_total_detail_subtotal" name="total_detail_subtotal">
                                                        </span>
                                                    </li>
                                                    <li class="list-group-item global-discount-group pt-1 pb-1">
                                                        <div class="key">Discount:</div>
                                                        <div class="value pull-right">
                                                            <input type="number" class="form-control text-right" id="q_discount" name="discount" placeholder="" value="0.00" required />
                                                        </div>
                                                    </li>
                                                    <li class="list-group-item global-discount-group pt-1 pb-1">
                                                        <div class="key">Delivery Fee:</div>
                                                        <div class="value pull-right">
                                                            <input type="number" class="form-control text-right" id="q_delivery_fee" name="delivery_fee" placeholder="" value="0.00" required />
                                                        </div>
                                                    </li>                                                    
                                                </ul>

                                                <div class="comment-block">
                                                	<div class="key mb-1">Comments:</div>
                                                	<textarea class="form-control" id="q_comments" name="comments" rows="3"></textarea>
                                                </div>

                                                <div class="amount-block">
                                                    <div class="total total2 amount return-total">
                                                        <div class="side-heading">
                                                            Total Amount
                                                        </div>
                                                        <div class="amount total-amount text-left" data-speed="1000" data-decimals="2">
                                                            <?php echo $default_currency; ?> <span id="q_label_total_detail_total">0.00</span>
                                                            <input type="hidden" id="q_total_detail_total" name="total_detail_total">
                                                        </div>
                                                    </div>
                                                </div>
	                                            
	                                            <div class="sale_controls text-center pt-1">
	                                                <button type="submit" id="btn_save_quotation" class="btn btn-success mt-1 mb-1 ml-1">
	                                                    <i class="ion-checkmark-circled mr-1"></i>Save Quotation
	                                                </button>
	                                            </div>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
	                </form>

            	<?php else: ?>
            		<?php foreach ($pos_quotation as $row): ?>
            			<div class="nk-block nk-block-lg mb-2">
	                        <div class="nk-block-between">
	                            <div class="nk-block-head-content"><h5 class="nk-block-title"><em class="icon ni ni-edit"></em> Edit Quotation [<?php echo $row->pos_quotation_number; ?>]</h5></div>
	                            <div class="nk-block-head-content">
	                                <div class="toggle-wrap nk-block-tools-toggle">
	                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
	                                    <div class="toggle-expand-content" data-content="pageMenu">
	                                        <ul class="nk-block-tools g-3">
	                                            <li class="nk-block-tools-opt">
	                                                <a href="<?php echo base_url(); ?>pos/quotations" class="btn btn-icon btn-sm btn-primary d-md-none"><em class="icon ni ni-chevron-left-c"></em></a>
	                                                <a href="<?php echo base_url(); ?>pos/quotations" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-chevron-left-c"></em><span>Quotations List</span></a>
	                                            </li>
	                                        </ul>
	                                    </div>
	                                </div>
	                            </div>
	                        </div>
	                    </div>

	                    <form id="frm_edit_quotation" name="frm_edit_quotation" method="post" onsubmit="return update_quotation();">
		                    <div id="register_container" class="quotations clearfix">
		                        <div class="row register">
		                            <input type="hidden" id="pos_quotation_id" name="pos_quotation_id" value="<?php echo $row->pos_quotation_id; ?>">

		                            <div class="col-md-12">
		                                <div class="row" id="div_quotation_body">

		                                    <div class="col-lg-8 col-md-7 col-sm-12 col-xs-12 no-padding-right no-padding-left">
		                                        <div class="spinner display-none" id="details_section_loader">
		                                            <div class="rect1"></div>
		                                            <div class="rect2"></div>
		                                            <div class="rect3"></div>
		                                        </div>
		                                        <div class="register-box register-items-form p-3">
		                                            <div class="row">
		                                            	<div class="col-md-6">
		                                            		<div class="form-group">
				                                                <label for="q_customer_name">Customer</label>
				                                                <input type="text" class="form-control" id="q_customer_name" name="customer_name" readonly placeholder="" value="<?php echo $row->first_name . ' ' . $row->last_name; ?>" required />
				                                                <input type="hidden" id="q_customer_id" name="customer_id" value="<?php echo $row->customer_id; ?>" />
				                                            </div>
		                                            	</div>
		                                            	<div class="col-md-3">
		                                            		<div class="form-group">
				                                                <label for="q_date">Quotation Date</label>
				                                                <input type="text" class="form-control datepicker" id="q_date" name="quotation_date" placeholder="" value="<?php echo $row->quotation_date; ?>" required />
				                                            </div>
		                                            	</div>
		                                            	<div class="col-md-3">
		                                            		<div class="form-group">
				                                                <label for="q_valid_until">Valid Until</label>
				                                                <input type="text" class="form-control datepicker" id="q_valid_until" name="valid_until" placeholder="" value="<?php echo $row->valid_until; ?>" />
				                                            </div>
		                                            	</div>
		                                            </div>
		                                            <div class="row">
		                                            	<div class="col-md-12">
		                                            		<div class="form-group">
				                                                <label for="q_product_id">Select Product</label>
				                                                <select class="form-control select2" data-placeholder="Select Product" id="q_product_id" name="product_id" style="width: 100%;" tabindex="-1" aria-hidden="true">
				                                                    <option value="">Select Product</option>
																	<?php foreach ($products as $row2): ?>																	
																		<option value="<?php echo $row2->product_id; ?>"><?php echo $row2->product_name; ?></option>
																	<?php endforeach; ?>
				                                                </select>
				                                            </div>
				                                        </div>
				                                    </div>
		                                        </div>
		                                        <div class="register-box register-items paper-cut">
		                                            <div class="register-items-holder table-responsive">
		                                                <table id="register" class="table table-hover">
		                                                    <thead>
		                                                        <tr class="register-items-header">
		                                                            <th class="item_name_heading fw-bold text-left" width="200px">Item</th>
		                                                            <th class="quotation_price fw-bold text-left" width="130px">Unit</th>
		                                                            <th class="quotation_price fw-bold text-left" width="110px">Price</th>
		                                                            <th class="quotation_quantity fw-bold text-left" width="80px">Qty</th>
		                                                            <th class="fw-bold text-left" width="100px ">Subtotal</th>
		                                                            <th class="fw-bold  text-center" width="60px"></th>
		                                                        </tr>
		                                                    </thead>

		                                                    <tbody id="quotation_details_table" class="register-item-content">
		                                                    	<?php foreach ($pos_quotation_details as $row2): ?>
																	<?php
																		$line_id = $row2->product_id . $row2->product_variation_id;
																		//$max_qty = $row2->quantity - ($row2->total_dispatched_quantity - $row2->dispatched_quantity);
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
														                			echo '~ ' . substr($variation_description,0,-2);
														                		?><br>
																			<?php endif; ?>
																			<div class="text-muted font-size-sm pt-0"><b>SKU:</b><?php echo $row2->product_sku_code; ?></div>
																			<input id="q_detail_id_<?php echo $line_id; ?>" name="q_detail_id[]" type="hidden" class="form-control q_detail_id" value="<?php echo $line_id; ?>">
																			<input id="q_detail_product_id_<?php echo $line_id; ?>" name="q_detail_product_id[]" type="hidden" class="q_detail_product_id" value="<?php echo $row2->product_id; ?>">
																			<input id="q_detail_product_variation_id_<?php echo $line_id; ?>" name="q_detail_product_variation_id[]" type="hidden" class="q_detail_product_variation_id" value="<?php echo $row2->product_variation_id; ?>">
																		</td>
																		<td>
																			<select class="form-control unit-select q_unit_id" data-placeholder="Select Unit" id="q_unit_id_<?php echo $line_id; ?>" name="q_unit_id[]" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
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
																			<input id="q_detail_cost_<?php echo $line_id; ?>" name="q_detail_cost[]" type="number" class="form-control q_detail_cost" value="<?php echo $row2->unit_price; ?>" autocomplete="off" required>
																		</td>
																		<td><input id="q_detail_qty_<?php echo $line_id; ?>" name="q_detail_qty[]" type="number" class="form-control q_detail_qty" min="1" value="<?php echo number_format($row2->quantity,2); ?>" autocomplete="off" required></td>
																		<td>
																			<span id="q_label_detail_total_<?php echo $line_id; ?>"><?php echo number_format($row2->sub_total,2); ?></span>
																			<input id="q_detail_total_<?php echo $line_id; ?>" name="q_detail_total[]" type="hidden" class="form-control q_detail_total" value="<?php echo $row2->sub_total; ?>">
																		</td>
																		<td><a href="javascript:void(0);" class="q_detail_remove" title="Remove product"><span class="badge rounded-pill bg-transparent bg-outline-danger"><em class="icon ni ni-cross-circle"></em></span></a></td>
																	</tr>
																<?php endforeach; ?>
		                                                    </tbody>
		                                                </table>
		                                            </div>
		                                        </div>
		                                    </div>

		                                    <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
		                                        <div class="spinner display-none" id="totals_section_loader">
		                                            <div class="rect1"></div>
		                                            <div class="rect2"></div>
		                                            <div class="rect3"></div>
		                                        </div>
		                                        
		                                        

		                                        <div class="register-box register-summary paper-cut">

	                                                <ul class="list-group">
	                                                    <li class="list-group-item global-discount-group pt-1 pb-1">
	                                                        <div class="key">Total Quantity:</div>
	                                                        <div class="value pull-right" id="q_label_total_detail_qty"><?php echo number_format($row->total_quantity,2); ?></div>
	                                                        <input type="hidden" id="q_total_detail_qty" name="total_detail_qty" value="<?php echo $row->total_quantity; ?>">
	                                                    </li>
	                                                    <li class="sub-total list-group-item pb-1">
	                                                        <span class="key">Sub Total:</span>
	                                                        <span class="value">                                                            
	                                                            <?php echo $default_currency; ?> <span id="q_label_total_detail_subtotal"><?php echo number_format($row->sub_total,2); ?></span>
	                                                            <input type="hidden" id="q_total_detail_subtotal" name="total_detail_subtotal" value="<?php echo $row->sub_total; ?>">
	                                                        </span>
	                                                    </li>
	                                                    <li class="list-group-item global-discount-group pt-1 pb-1">
	                                                        <div class="key">Discount:</div>
	                                                        <div class="value pull-right">
	                                                            <input type="number" class="form-control text-right" id="q_discount" name="discount" placeholder="" value="<?php echo $row->discount; ?>" required />
	                                                        </div>
	                                                    </li>
	                                                    <li class="list-group-item global-discount-group pt-1 pb-1">
	                                                        <div class="key">Delivery Fee:</div>
	                                                        <div class="value pull-right">
	                                                            <input type="number" class="form-control text-right" id="q_delivery_fee" name="delivery_fee" placeholder="" value="<?php echo $row->delivery_fee; ?>" required />
	                                                        </div>
	                                                    </li>                                                    
	                                                </ul>

	                                                <div class="comment-block">
	                                                	<div class="key mb-1">Comments:</div>
	                                                	<textarea class="form-control" id="q_comments" name="comments" rows="3"><?php echo $row->comments; ?></textarea>
	                                                </div>

	                                                <div class="amount-block">
	                                                    <div class="total total2 amount return-total">
	                                                        <div class="side-heading">
	                                                            Total Amount
	                                                        </div>
	                                                        <div class="amount total-amount text-left" data-speed="1000" data-decimals="2">
	                                                            <?php echo $default_currency; ?> <span id="q_label_total_detail_total"><?php echo number_format($row->total_amount,2); ?></span>
	                                                            <input type="hidden" id="q_total_detail_total" name="total_detail_total" value="<?php echo $row->total_amount; ?>">
	                                                        </div>
	                                                    </div>
	                                                </div>
		                                            
		                                            <div class="sale_controls text-center pt-1">
		                                                <button type="submit" id="btn_update_quotation" class="btn btn-success mt-1 mb-1 ml-1">
		                                                    <i class="ion-checkmark-circled mr-1"></i>Update Quotation
		                                                </button>
		                                            </div>
		                                        </div>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
		                    </div>
		                </form>
            		<?php endforeach; ?>
            	<?php endif; ?>

            </div>
        </div>
    </div>
</div>


<div id="modal_q_select_product_variation" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-primary"><i class="icon-plus-circle2"></i> Select Product Variation</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" style="min-height: 200px;">
                <div id="div_q_select_product_variation">
                    
                </div>
            </div>
        </div>
    </div>
</div>