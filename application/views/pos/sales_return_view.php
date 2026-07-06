<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">

            	<div class="nk-block nk-block-lg">
            		<div class="nk-block-head nk-block-head-sm">
					    <div class="nk-block-between">
					        <div class="nk-block-head-content"><h5 class="nk-block-title page-title"><em class="icon ni ni-eye"></em> View Sales Return</h5></div>
					        <div class="nk-block-head-content">
					            <div class="toggle-wrap nk-block-tools-toggle">
					                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
					                <div class="toggle-expand-content" data-content="pageMenu">
					                    <ul class="nk-block-tools g-3">
					                        <li class="nk-block-tools-opt">
					                            <a href="<?php echo base_url(); ?>pos/sales/sales_returns" class="btn btn-icon btn-sm btn-primary d-md-none"><em class="icon ni ni-chevron-left-c"></em></a>
					                            <a href="<?php echo base_url(); ?>pos/sales/sales_returns" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-chevron-left-c"></em><span>Sales Returns List</span></a>
					                        </li>
					                    </ul>
					                </div>
					            </div>
					        </div>
					    </div>
					</div>

					<div class="row">
						<div class="col-md-2"></div>
						<div class="col-md-8">
		            		<section class="invoice card card-preview p-3">
		            			<div class="spinner display-none" id="sales_returns_list_loader">
			                        <div class="rect1"></div>
			                        <div class="rect2"></div>
			                        <div class="rect3"></div>
			                    </div>
		            			<?php foreach ($pos_sales_return as $row): ?>
		            				<?php $refund_balance = $row->total_amount - $row->total_refunded; ?>
								    <div class="printableArea card-body">
								        <div class="row mb-4">
								            <div class="col-xs-12 text-right">
								                <!-- <h6 class="page-header">Sale #<?php echo $row->pos_sale_number; ?> Details</h6> -->
								                <div class="drodown">
												    <a href="#" class="dropdown-toggle dropdown-indicator btn btn-outline-primary btn-white" data-toggle="dropdown">Action</a>
												    <div class="dropdown-menu dropdown-menu-end">
												        <ul class="link-list-opt no-bdr">
												        	<?php if ($row->is_void == 0): ?>
														        <?php if ($sbr_pos_sales_returns_manage == true && $row->return_status == 0): ?>
														            <li>
														                <a href="javascript:;" data-pos-sales-return-id="<?php echo $row->pos_sales_return_id; ?>" data-context="View Sales Return" class="sales_return_approve"><em class="icon ni ni-check-circle-cut"></em><span>Approve/Reject</span></a>
														            </li>
														        <?php endif; ?>
														        <?php if ($sbr_pos_sales_returns_manage == true && $row->return_status == 1 && $row->return_settlement == 'Refund'): ?>
														        	<?php if ($row->total_refunded < $row->total_amount): ?>
														        		<li>
															                <a href="javascript:;" data-pos-sales-return-id="<?php echo $row->pos_sales_return_id; ?>" data-context="View Sales Return" class="sales_return_refund"><em class="icon ni ni-redo"></em><span>Make a Refund</span></a>
															            </li>
														        	<?php endif; ?>
														        <?php endif; ?>
														        <?php if ($sbr_pos_sales_returns_edit == true && $row->return_status == 0): ?>
														            <li>
														                <a href="<?php echo base_url(); ?>pos/sales/edit_return/<?php echo $row->pos_sales_return_id; ?>"><em class="icon ni ni-edit"></em><span>Edit Sales Return</span></a>
														            </li>
														        <?php endif; ?>
													        <?php endif; ?>
													        <?php if ($sbr_pos_sales_returns_print == true): ?>
													            <li>
													                <a href="<?php echo base_url(); ?>pos/sales/print_return_a4/<?php echo $row->pos_sales_return_id; ?>" target="_blank"><em class="icon ni ni-printer"></em><span>Print</span></a>
													            </li>
													        <?php endif; ?>
												            <li>
												                <a href="javascript:;" data-toggle="modal" data-target="#modal_send_sales_return_order_via_email" data-pos-sales-return-id="<?php echo $row->pos_sales_return_id; ?>" class="lnk_send_sales_return_order_via_email"><em class="icon ni ni-mail"></em><span>Send Via Mail</span></a>
												            </li>
												            <?php if ($sbr_pos_sales_returns_delete == true): ?>
													            <?php if ($row->is_void == 0): ?>
														            <li>
														                <a href="javascript:;" data-pos-sales-return-id="<?php echo $row->pos_sales_return_id; ?>" data-context="View Sales Return" class="lnk_void_pos_sales_return"><em class="icon ni ni-shield-off"></em><span>Void Sales Return</span></a>
														            </li>
														        <?php endif; ?>
														    <?php endif; ?>
														    <!-- <em class="icon ni ni-check-circle-cut"></em> -->
												        </ul>
												    </div>
												</div>
								            </div>
								        </div>
								        <div class="row invoice-info mb-4">
								            <div class="col-sm-6 invoice-col">
								                <?php foreach ($store_information as $row2): ?>
			                                        <?php if($row2->store_logo != '' && file_exists("./uploads/store_logo/" . $row2->store_logo)): ?>
			                                            <img src="<?php echo base_url();?>uploads/store_logo/<?php echo $row2->store_logo; ?>" class="ht-50 mb-3" alt="">
			                                        <?php else: ?>
			                                            <img src="<?php echo base_url();?>assets/fe/img/logo.png" class="ht-50 mb-3" alt="">
			                                        <?php endif; ?> 
			                                        <!-- <p class="h5 mt-3"><?php //echo $row2->store_name; ?></p>   -->
			                                        <address>
			                                            <?php if ($row2->physical_address != ''): ?>
			                                                <?php echo $row2->physical_address; ?><br />
			                                            <?php endif; ?>
			                                            <?php if ($row2->email_address != ''): ?>
			                                                <?php echo $row2->email_address; ?><br />
			                                            <?php endif; ?>
			                                            <?php if ($row2->phone_number != ''): ?>
			                                                <?php echo $row2->phone_number; ?><br />
			                                            <?php endif; ?>
			                                        </address>
			                                    <?php endforeach ?>
								            </div>
								            <div class="col-sm-6 invoice-col text-right">
								            	<h4 class="text-uppercase font-weight-light">Sales Return</h4>
								            	<p class="lead text-info"><?php echo $row->pos_sales_return_number; ?></p>
								            	<p class="font-weight-bold mb-0">CUSTOMER</p>
								            	<p class="lead"><?php echo $row->first_name . ' ' . $row->last_name; ?></p>
								                <p>
								                	<b>Date:</b> <?php echo date('d M, Y', strtotime($row->sales_return_date)); ?><br />
									                <b>Status:</b> 
								                	<?php if ($row->is_void == 1): ?>
		                                                <span class="badge badge-sm badge-dot has-bg badge-secondary d-none d-mb-inline-flex">Void</span>
		                                            <?php else: ?>
		                                                <?php if ($row->return_status == 0): ?>
		                                                    <span class="badge badge-sm badge-dot has-bg badge-secondary d-none d-mb-inline-flex">Pending</span>
		                                                <?php elseif ($row->return_status == 1): ?>
		                                                    <span class="badge badge-sm badge-dot has-bg badge-success d-none d-mb-inline-flex">Approved</span>
		                                                <?php elseif ($row->return_status == 2): ?>
		                                                    <span class="badge badge-sm badge-dot has-bg badge-danger d-none d-mb-inline-flex">Rejected</span>
		                                                <?php endif; ?>
		                                            <?php endif; ?>
		                                            <?php if ($row->return_status == 1): ?>
		                                            	<br><b>Settlement:</b> <?php echo $row->return_settlement; ?>
		                                            	<?php if ($row->return_settlement == 'Refund'): ?>
		                                            		<br><b>Refund Status:</b>
		                                            		<?php if ($refund_balance == $row->total_amount): ?>
			                                                    <span class="badge badge-sm badge-dot has-bg badge-danger d-none d-mb-inline-flex">Not Refunded</span>
			                                                <?php elseif ($refund_balance > 0): ?>
			                                                    <span class="badge badge-sm badge-dot has-bg badge-warning d-none d-mb-inline-flex">Partially Refunded</span>
			                                                <?php else: ?>
			                                                    <span class="badge badge-sm badge-dot has-bg badge-success d-none d-mb-inline-flex">Refunded</span>
			                                                <?php endif; ?>
			                                            <?php endif; ?>
		                                            <?php  endif; ?>
			                                    </p>
								            </div>
								        </div>
								        <!-- Table row -->
								        <div class="row mt-3 mb-3">
								            <div class="col-xs-12 table-responsive">
								                <table class="table table-striped table-bordered">
								                    <thead class="border-top">
								                        <tr>
								                            <th width="5%">#</th>
								                            <th width="37%">Description</th>
								                            <th width="8%">Unit</th>
								                            <th width="10%">Qty</th>
								                            <th width="5%">Tax</th>
								                            <th width="15%">Rate (<?php echo $default_currency; ?>)</th>
								                            <th width="15%">Total (<?php echo $default_currency; ?>)</th>
								                        </tr>
								                    </thead>
								                    <tbody>
								                    	<?php $count = 1; ?>
								                    	<?php foreach ($pos_sales_return_details as $row2): ?>
								                    		<?php
								                                $variation_description = '';
								                                if(!empty($row2->attributes)){
								                                    foreach ($row2->attributes as $row3){
								                                        $variation_description = $variation_description . $row3->product_attribute_name . ' : <b>' . $row3->product_attribute_value . '</b>, ';
								                                    }
								                                    $variation_description =  '~ ' . substr($variation_description,0,-2) . '<br>';
								                                }
								                            ?>
									                        <tr>
									                            <td><?php echo $count; ?></td>
									                            <td><?php echo $row2->product_name; ?><br><?php echo $variation_description; ?></td>
									                            <td><?php echo $row2->unit_name . ' (' . $row2->unit_code . ')'; ?></td>
									                            <td><?php echo number_format($row2->quantity,2); ?></td>
									                            <td><?php echo $row2->tax_rate_code; ?></td>
									                            <td><?php echo number_format(($row2->unit_price - $row2->discount_amount),2); ?></td>
									                            <td><?php echo number_format($row2->sub_total,2); ?></td>
									                        </tr>
									                        <?php $count++; ?>
									                    <?php endforeach; ?>
								                        
								                    </tbody>
								                    <tfoot>
							                            <!-- <tr>
							                                <th colspan="4">Total Quantity</th>
							                                <th><?php echo number_format($row->total_quantity,2); ?></th>
							                            </tr> -->

							                            <tr>
							                                <th colspan="6">Subtotal</th>
							                                <th><?php echo number_format($row->sub_total,2); ?></th>
							                            </tr>                           

							                            <tr>
							                                <th colspan="6">Discount</b></th>
							                                <th><?php echo number_format($row->overall_discount,2); ?></th>
							                            </tr>

							                            <tr>
							                                <th colspan="6">Delivery Fee</th>
							                                <th><?php echo number_format($row->delivery_fee,2); ?></th>
							                            </tr>

							                            <tr>
							                                <th colspan="6"><big>Grand Total</big></th>
							                                <th><big><?php echo number_format($row->total_amount,2); ?></big></th>
							                            </tr>
							                            <?php if ($row->return_status == 1 && $row->return_settlement == 'Refund'): ?>
							                            	<?php $refund_balance = $row->total_amount - $row->total_refunded; ?>
								                            <tr>
								                                <th colspan="6">Refunded</th>
								                                <th><?php echo number_format($row->total_refunded,2); ?></th>
								                            </tr>
								                            <?php if ($refund_balance > 0): ?>
								                                <tr>
								                                    <th colspan="6">Balance</th>
								                                    <th><?php echo number_format(($refund_balance),2); ?></th>
								                                </tr>
								                            <?php endif; ?>
								                        <?php endif; ?>
							                        </tfoot>
								                </table>
								            </div>
								            <!-- /.col -->
								        </div>
								        <!-- /.row -->

								        <div class="row mt-4">
								            <div class="col-md-12">
								                <div class="row mt-3">
								                	<div class="col-md-6">
								                        <div class="table-responsive mb-3">
								                            <h6 class="text-uppercase font-weight-light">Tax Details</h6>
								                            <table class="table table-hover" style="width: 100%;" id="">
								                                <thead>
								                                    <tr class="">
								                                        <th>Code</th>
								                                        <th>Rate</th>
								                                        <th>VATABLE AMT</th>
								                                        <th>VAT AMT</th>
								                                    </tr>
								                                </thead>
								                                <tbody>
								                                	<?php $count = 1; ?>
								                                	<?php foreach ($pos_sales_return_tax_details as $row2): ?>
									                                    <tr>
									                                        <td><?php echo $row2->tax_rate_code; ?></td>
									                                        <td><?php echo $row2->tax_rate_value; ?>%</td>
									                                        <td><?php echo number_format($row2->vatable_amount,2); ?></td>
									                                        <td><?php echo number_format($row2->vat_amount,2); ?></td>
									                                    </tr>
									                                    <?php $count++; ?>
									                                <?php endforeach; ?>
								                                </tbody>
								                            </table>
								                            <p>**Prices inclusive of taxes where applicable</p>
								                        </div>
								                    </div>
								                    <div class="col-md-6">
								                    	<?php if ($num_pos_sales_return_refunds > 0): ?>
									                        <div class="table-responsive">
									                            <h6 class="text-uppercase font-weight-light">refunds</h6>
									                            <table class="table table-hover" style="width: 100%;" id="">
									                                <thead>
									                                    <tr class="">
									                                        <th>Date</th>
									                                        <th>Type</th>
									                                        <th>Ref #</th>
									                                        <th>Amount</th>
									                                    </tr>
									                                </thead>
									                                <tbody>
									                                	<?php $count = 1; ?>
									                                	<?php $total_paid = 0; ?>
									                                	<?php foreach ($pos_sales_return_refunds as $row2): ?>
									                                		<?php $total_paid = $total_paid + $row2->refund_amount; ?>
										                                    <tr>
										                                        <td><?php echo date('d M, Y', strtotime($row2->created_on)); ?></td>
										                                        <td><?php echo $row2->refund_method; ?></td>
										                                        <td><?php echo $row2->reference_number; ?></td>
										                                        <td><?php echo number_format($row2->refund_amount,2); ?></td>
										                                    </tr>
										                                    <?php $count++; ?>
										                                <?php endforeach; ?>
									                                    <tr class="font-weight-bold">
									                                        <td colspan="3">TOTAL</td>
									                                        <td><?php echo number_format($total_paid,2); ?></td>
									                                    </tr>
									                                </tbody>
									                            </table>
									                        </div>
									                    <?php endif; ?>
								                    </div>

								                </div>

								                <?php if ($row->comments != ''): ?>
									            	<h6 class="text-uppercase font-weight-light mt-4">Comments</h6>
									            	<p><?php echo nl2br($row->comments); ?></p>
									            <?php endif; ?>
									            <p>You were served by: <b><?php echo $row->system_user_first_name . ' ' . $row->system_user_last_name; ?></b> | Trnx Time: <b><?php echo date('d M, Y H:i:s', strtotime($row->created_on)); ?></b></p>
								            </div>
								        </div>
								        <!-- /.row -->
								        <!-- <div class="row mt-3">
								        	<div class="col-md-4"></div>
							                <div class="col-md-4">
							                	<div class="row">
							                		<div class="col-md-1"></div>
							                		<div class="col-md-10">
									                    <?php
									                        foreach ($store_information as $row2){ 
									                            $storeName = $row2->store_name;
									                            $phoneNumber = $row2->phone_number;
									                            $emailAddress = $row2->email_address;
									                        }
									                        $svg = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 270 100" width="270" height="100">
									                            <rect x="0" y="0" width="270" height="100" stroke="#303f9f" stroke-width="3px" fill="white"/>
									                            <text transform="matrix(1 0 0 1 135 30)" font-family="Open Sans" font-size="20px" font-weight="bold" fill="#303f9f" text-anchor="middle">' . strtoupper($storeName) . '</text>
									                            <text transform="matrix(1 0 0 1 135 50)" font-size="16px" font-weight="bold" fill="#f44336" text-anchor="middle">' . strtoupper(date('d M Y', strtotime($row->created_on))) . '</text>
									                            <text transform="matrix(1 0 0 1 135 70)" font-size="14px" font-weight="bold" fill="#303f9f" text-anchor="middle">' . $phoneNumber . '</text>
									                            <text transform="matrix(1 0 0 1 135 90)" font-size="14px" font-weight="bold" fill="#303f9f" text-anchor="middle">' . $emailAddress . '</text>
									                        </svg>';
									                    ?>
									                    <img class="img-fluid" src="data:image/svg+xml;base64,<?php echo base64_encode($svg); ?>" />
									                </div>
									            </div>
							                </div>
							            </div> -->
								    </div>
								    <!-- printableArea -->
								    <hr class="mb-0">
								    <!-- this row will not appear when printing -->
								    <div class="row no-print card-body">
								        <div class="col-xs-12 text-center">
								        	<p><small>Powered by Devlab Africa | <a href="https://devlabafrica.com" target="_blank">www.devlabafrica.com</a></small></p>
								        </div>
								    </div>
								<?php endforeach; ?>
							</section>
						</div>
					</div>

            	</div>
            </div>
        </div>
    </div>
</div>



<div class="modal fade in" id="modal_send_sale_order_via_email">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header header-custom">
                <h6 class="modal-title text-center"><em class="icon ni ni-mail mr-1"></em>Send Order via Email</h6>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <form id="frm_send_sale_order_via_email" name="frm_send_sale_order_via_email" method="post" class="is-alter" onsubmit="return submit_send_sale_order_via_email();">
                <div class="modal-body">
                    <div class="spinner display-none" id="send_sale_order_via_email_loader">
                        <div class="rect1"></div>
                        <div class="rect2"></div>
                        <div class="rect3"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div>

                                <input type="hidden" id="send_email_pos_sale_id" name="pos_sale_id">

                                <div class="col-md-12">
                                    <div class="box box-solid bg-lighter">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="sale_payment_method">Sender Email Account</label>
                                                        <select class="form-control select2" data-placeholder="Select Email Address" id="ssove_email_account_id" name="email_account_id">
                                                            <option value="">Select Email Address</option>
                                                            <?php foreach ($email_accounts as $row2): ?>
                                                            	<option value="<?php echo $row2->email_account_id; ?>"><?php echo $row2->sender_name . ' ~ ' . $row2->sender_email_address ; ?></option>
                                                            <?php endforeach; ?>
                                                            
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row display-none">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sender_name">Sender Name<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="sender_name" name="sender_name" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sender_email_address">Sender Email Address<span class="text-danger">*</span></label>
                                                        <input type="email" class="form-control" id="sender_email_address" name="sender_email_address" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row display-none">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="mail_server_name">Mail Server (SMTP)<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="mail_server_name" name="mail_server_name" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="mail_server_port">Mail Server Port<span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control" id="mail_server_port" name="mail_server_port" step="any" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                	<div class="form-group">
                                                		<label for="">&nbsp;</label><br>
                                                		<div class="custom-control custom-checkbox">
														  <input type="checkbox" class="custom-control-input" id="chk_use_ssl" name="chk_use_ssl" />
														  <label class="custom-control-label" for="chk_use_ssl">Use SSL</label>
														</div>
                                                	</div>
                                                </div>
                                            </div>
                                            <div class="row display-none">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sender_username">Username<span class="text-danger">*</span></label>
                                                        <input type="email" class="form-control" id="sender_username" name="sender_username" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sender_password">Password<span class="text-danger">*</span></label>
                                                        <input type="password" class="form-control" id="sender_password" name="sender_password" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="recipient_email_address">Recipient Email Address<span class="text-danger">*</span></label>
                                                        <input type="email" class="form-control" id="recipient_email_address" name="recipient_email_address" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="email_subject">Subject<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="email_subject" name="email_subject" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="email_message">Message</label>
                                                        <textarea type="text" class="form-control" id="email_message" name="email_message" rows="6" placeholder=""></textarea>
                                                    </div>
                                                </div>

                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn_send_pos_sale_order_via_email" class="btn btn-success"><em class="icon ni ni-send mr-1"></em>Send Email</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade in" id="modal_sales_return_refund">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header header-custom">
                <h6 class="modal-title text-center"><em class="icon ni ni-cc-alt mr-1"></em>Make a Refund <span id="refund_header_pos_sales_return_number"></span></h6>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <form id="frm_sales_return_make_refund" name="frm_sales_return_make_refund" method="post" class="is-alter" onsubmit="return submit_sales_return_refund();">
                <div class="modal-body">
                    <div class="spinner display-none" id="sales_return_make_refund_loader">
                        <div class="rect1"></div>
                        <div class="rect2"></div>
                        <div class="rect3"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <div>

                                <input type="hidden" id="refund_pos_sales_return_id" name="pos_sales_return_id">
                                <input type="hidden" id="refund_pos_sales_return_number" name="pos_sales_return_number">
                                <input type="hidden" id="refund_context" name="context">

                                <div class="col-md-12 refunds_div">
                                    <div class="box box-solid bg-lighter">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sales_return_refund_method">Refund Method</label>
                                                        <select class="form-control select2" data-placeholder="Select Refund Method"  id="sales_return_refund_method" name="refund_method">
                                                            <option value="">Select Refund Method</option>
                                                            <option value="Cash">Cash</option>
                                                            <option value="MPESA">MPESA</option>
                                                            <option value="Cheque">Cheque</option>
                                                            <option value="CashApp">CashApp</option>
                                                            <option value="Wave">Wave</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sales_return_refund_amount">Amount</label>
                                                        <input type="number" class="form-control" id="sales_return_refund_amount" name="refund_amount" step="any" min="0">
                                                    </div>
                                                </div>
                                                <div id="div_sales_return_refund_mpesa_btns" class="display-none">
                                                    <div class="col-md-6">
                                                        <button id="btn_sales_return_refund_mpesa_instructions" type="button" tabindex="0"  class="badge badge-sm badge-dot has-bg badge-info" data-toggle="popover" data-placement="top" title="MPESA refund Instructions" data-content="
                                                            - Go to Safaricom Menu <br>
                                                            - Select Lipa na M-PESA - Paybill Option<br>
                                                            - Enter Business No: <b></b><br>
                                                            - Enter Account No: <b></b><br>
                                                            - Enter Amount<br>
                                                            - Enter your MPESA PIN and send<br>
                                                            - You will receive a confirmation SMS from MPESA"><span>MPESA Instructions</span>
                                                        </button>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <button id="btn_sales_return_make_refund_select" type="button" class="badge badge-sm badge-dot has-bg badge-secondary d-none d-mb-inline-flex">Select Transaction <i class="ion-load-c icon-spinner display-none" id="select_mpesa_refund_loader"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="div_sales_return_refund_reference_number">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label id="lbl_refund_reference_number" for="sales_return_refund_reference_number">Reference #</label>
                                                        <input type="text" class="form-control" id="sales_return_refund_reference_number" name="reference_number" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="refund_note_1">Refund Note</label>
                                                        <textarea type="text" class="form-control" id="sales_return_refund_note" name="refund_note" placeholder=""></textarea>
                                                    </div>
                                                </div>

                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-5">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="box box-solid bg-blue">
                                        <div class="box-body">
                                            <div class="row bg-red">
                                                <div class="col-md-12 border-custom-bottom">
                                                    <span class="col-md-6 text-right text-bold">Total Refundable:</span>
                                                    <span class="col-md-6 text-right text-bold custom-font-size" id="div_sales_return_total_amount">KES 0.00</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 border-custom-bottom">
                                                    <span class="col-md-6 text-right text-bold">Total Refunded:</span>
                                                    <span class="col-md-6 text-right text-bold custom-font-size" id="div_sales_return_total_refunded">KES 0.00</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 border-custom-bottom">
                                                    <span class="col-md-6 text-right text-bold">Balance:</span>
                                                    <span class="col-md-6 text-right text-bold custom-font-size" id="div_sales_return_refund_balance">KES 0.00</span>
                                                </div>
                                            </div>
                                            <!-- <div class="row">
                                                <div class="col-md-12 bg-orange pt-1 pb-1">
                                                    <span class="col-md-6 text-right text-bold">Change:</span>
                                                    <span class="col-md-6 text-right text-bold custom-font-size" id="div_sales_return_change">KES 0.00</span>
                                                </div>
                                            </div> -->
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn_submit_sales_return_refund" class="btn btn-success"><i class="ion-checkmark-circled mr-1"></i>Submit refund</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modal_void_pos_sales_return">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="icon ni ni-trash-alt"></i> Void sales_return</h6>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <div class="modal-body">
                <div class="spinner display-none" id="void_sales_return_loader">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                </div>
                <form id="frm_void_pos_sales_return" name="frm_void_pos_sales_return" method="post" class="is-alter" onsubmit="return submit_void_pos_sales_return();">

                    <input type="hidden" id="void_pos_sales_return_id" name="pos_sales_return_id">
                    <input type="hidden" id="void_context" name="context">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid bg-lighter">
                                <div class="box-body">
                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <textarea name="void_reason" cols="40" rows="5" id="pos_sales_return_void_reason" class="form-control" placeholder="Void Reason"></textarea>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" id="btn_void_pos_sales_return" class="btn btn-success"><i class="ion-checkmark-circled mr-1"></i>Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modal_approve_pos_sales_return" class="modal fade">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h6 class="modal-title text-primary"><em class="icon ni ni-check-circle-cut"></em> Approve/Reject Sales Return</h6>
				<button type="button" class="close" data-dismiss="modal">&times;</button>
			</div>
			<div class="modal-body" style="min-height: 200px;">
				<div id="div_approve_pos_sales_return">

				</div>
			</div>
		</div>
	</div>
</div>

<?php if ($this->session->flashdata('refund_now') !== null): ?>
    <script>
    	$(document).ready(function() {
			$(function(){
		    	console.log('Ready to refund');
		    	$(".sales_return_refund").trigger('click');
		   	});
		});
    </script>

<?php endif; ?>