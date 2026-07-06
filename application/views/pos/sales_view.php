<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">

            	<div class="nk-block nk-block-lg">
            		<div class="nk-block-head nk-block-head-sm">
					    <div class="nk-block-between">
					        <div class="nk-block-head-content"><h5 class="nk-block-title page-title"><em class="icon ni ni-eye"></em> View Sales Order</h5></div>
					        <div class="nk-block-head-content">
					            <div class="toggle-wrap nk-block-tools-toggle">
					                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
					                <div class="toggle-expand-content" data-content="pageMenu">
					                    <ul class="nk-block-tools g-3">
					                        <li class="nk-block-tools-opt">
					                            <a href="<?php echo base_url(); ?>pos/sales/sales_list" class="btn btn-icon btn-sm btn-primary d-md-none"><em class="icon ni ni-chevron-left-c"></em></a>
					                            <a href="<?php echo base_url(); ?>pos/sales/sales_list" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-chevron-left-c"></em><span>Sales List</span></a>
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
		            			<?php foreach ($pos_sale as $row): ?>
		            				<?php $payment_balance = $row->total_sale - $row->total_paid; ?>
								    <div class="printableArea card-body">
								        <div class="row mb-4">
								            <div class="col-xs-12 text-right">
								                <!-- <h6 class="page-header">Sale #<?php echo $row->pos_sale_number; ?> Details</h6> -->
								                <div class="drodown">
												    <a href="#" class="dropdown-toggle dropdown-indicator btn btn-outline-primary btn-white" data-toggle="dropdown">Action</a>
												    <div class="dropdown-menu dropdown-menu-end">
												        <ul class="link-list-opt no-bdr">
												        	<?php if ($sbr_pos_sales_orders_edit == true): ?>
													        	<?php if ($row->is_void == 0): ?>
														            <li>
														                <a href="<?php echo base_url(); ?>pos/sales/edit/<?php echo $row->pos_sale_id; ?>"><em class="icon ni ni-edit"></em><span>Edit Sale</span></a>
														            </li>
														            <li>
														                <a href="javascript:;" data-pos-sale-id="<?php echo $row->pos_sale_id; ?>" data-context="View Sale" class="edit_btn_make_payment"><em class="icon ni ni-coin-alt-fill"></em><span>Receive Payment</span></a>
														            </li>
														        <?php endif; ?>
														    <?php endif; ?>
														    <?php if ($sbr_pos_sales_orders_print == true): ?>
													            <li>
													                <a href="<?php echo base_url(); ?>pos/sales/print_thermal/<?php echo $row->pos_sale_id; ?>" target="_blank"><em class="icon ni ni-printer"></em><span>Print on Thermal Paper</span></a>
													            </li>
													            <li>
													                <a href="<?php echo base_url(); ?>pos/sales/print_a4/<?php echo $row->pos_sale_id; ?>" target="_blank"><em class="icon ni ni-printer"></em><span>Print on A4 Paper</span></a>
													            </li>
													        <?php endif; ?>
												            <li>
												                <a href="javascript:;" data-toggle="modal" data-target="#modal_send_sale_order_via_email" data-pos-sale-id="<?php echo $row->pos_sale_id; ?>" class="lnk_send_sale_order_via_email"><em class="icon ni ni-mail"></em><span>Send Via Mail</span></a>
												            </li>
												            <?php if ($sbr_pos_sales_orders_delete == true): ?>
													            <?php if ($row->is_void == 0): ?>
														            <li>
														                <a href="javascript:;" data-pos-sale-id="<?php echo $row->pos_sale_id; ?>" data-context="View Sale" class="lnk_void_pos_sale"><em class="icon ni ni-shield-off"></em><span>Void Sale</span></a>
														            </li>
														        <?php endif; ?>
														    <?php endif; ?>
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
								            	<h4 class="text-uppercase font-weight-light">Sales Order</h4>
								            	<p class="lead text-info"><?php echo $row->pos_sale_number; ?></p>
								            	<p class="font-weight-bold mb-0">CUSTOMER</p>
								            	<p class="lead">
								            		<?php 
								            			if ($row->customer_id != 0){
								            				echo $row->first_name . ' ' . $row->last_name;
								            			} else {
								            				if ($row->customer_name != ''){
								            					echo $row->customer_name;
								            				} else {
								            					echo '-';
								            				}
								            			}
								            		?>
								            	</p>
								                <p>
								                	<b>Sale Date:</b> <?php echo date('d M, Y', strtotime($row->sale_date)); ?><br />
								                	<b>Sale Type:</b> <?php echo $row->sale_type; ?><br />
									                <?php if ($row->sale_type == 'CREDIT SALE'): ?>
									                	<b>Payment Terms:</b> <?php echo $row->credit_days; ?> Days<br />
									                	<b>Due Date:</b> <?php echo date('d M, Y', strtotime($row->credit_due_date)); ?><br />
									                <?php endif; ?>
									                <b>Status:</b> 
								                	<?php if ($row->is_void == 1): ?>
		                                                <span class="badge badge-sm badge-dot has-bg badge-secondary d-none d-mb-inline-flex">VOID</span>
		                                            <?php else: ?>
		                                                <?php if ($payment_balance == $row->total_sale): ?>
		                                                    <span class="badge badge-sm badge-dot has-bg badge-danger d-none d-mb-inline-flex">UNPAID</span>
		                                                <?php elseif ($payment_balance > 0): ?>
		                                                    <span class="badge badge-sm badge-dot has-bg badge-warning d-none d-mb-inline-flex">PARTIALLY PAID</span>
		                                                <?php else: ?>
		                                                    <span class="badge badge-sm badge-dot has-bg badge-success d-none d-mb-inline-flex">PAID</span>
		                                                <?php endif; ?>
		                                            <?php endif; ?>
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
								                    	<?php foreach ($pos_sale_details as $row2): ?>
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
							                                <th><big><?php echo number_format($row->total_sale,2); ?></big></th>
							                            </tr>

							                            <tr>
							                                <th colspan="6">Paid Amount</th>
							                                <th><?php echo number_format($row->total_paid,2); ?></th>
							                            </tr>
							                            <?php if ($payment_balance < 0): ?>
							                                <tr>
							                                    <th colspan="6">Change</th>
							                                    <th><?php echo number_format(($payment_balance * -1),2); ?></th>
							                                </tr>
							                            <?php elseif ($payment_balance > 0): ?>
							                                <tr>
							                                    <th colspan="6">Balance</th>
							                                    <th><?php echo number_format($payment_balance,2); ?></th>
							                                </tr>
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
								                        <div class="table-responsive">
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
								                                	<?php foreach ($pos_sale_tax_details as $row2): ?>
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
								                    	<?php if ($num_pos_sale_payments > 0): ?>
									                        <div class="table-responsive">
									                            <h6 class="text-uppercase font-weight-light">Payments</h6>
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
									                                	<?php foreach ($pos_sale_payments as $row2): ?>
									                                		<?php $total_paid = $total_paid + $row2->payment_amount; ?>
										                                    <tr>
										                                        <td><?php echo date('d M, Y', strtotime($row2->created_on)); ?></td>
										                                        <td><?php echo $row2->payment_method; ?></td>
										                                        <td><?php echo $row2->reference_number; ?></td>
										                                        <td><?php echo number_format($row2->payment_amount,2); ?></td>
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
								        <div class="row mt-3">
								        	<div class="col-md-4"></div>
							                <div class="col-md-4">
							                	<div class="row">
							                		<div class="col-md-1"></div>
							                		<div class="col-md-10">
									                    <?php
									                        foreach ($store_information as $row2){ 
									                            $storeName = $row2->store_name;
									                            $phoneNumber = $row2->phone_number;
									                             $phoneNumber1 = $row2->mobile_number;
									                            $emailAddress = $row2->email_address;
									                        }
									                        $svg = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 270 100" width="270" height="100">
									                            <rect x="0" y="0" width="270" height="100" stroke="#303f9f" stroke-width="3px" fill="white"/>
									                            <text transform="matrix(1 0 0 1 135 30)" font-family="Open Sans" font-size="20px" font-weight="bold" fill="#303f9f" text-anchor="middle">' . strtoupper($storeName) . '</text>
									                            <text transform="matrix(1 0 0 1 135 50)" font-size="16px" font-weight="bold" fill="#f44336" text-anchor="middle">' . strtoupper(date('d M Y', strtotime($row->created_on))) . '</text>
									                            <text transform="matrix(1 0 0 1 135 70)" font-size="14px" font-weight="bold" fill="#303f9f" text-anchor="middle">' . $phoneNumber . '</text>
									                             <text transform="matrix(1 0 0 1 135 90)" font-size="14px" font-weight="bold" fill="#303f9f" text-anchor="middle">' . $phoneNumber1 . '</text>
									                            <text transform="matrix(1 0 0 1 135 110)" font-size="14px" font-weight="bold" fill="#303f9f" text-anchor="middle">' . $emailAddress . '</text>
									                        </svg>';
									                    ?>
									                    <img class="img-fluid" src="data:image/svg+xml;base64,<?php echo base64_encode($svg); ?>" />
									                </div>
									            </div>
							                </div>
							            </div>
								    </div>
								    <!-- printableArea -->
								    <hr class="mb-0">
								    <!-- this row will not appear when printing -->
								    <div class="row no-print card-body">
								        <div class="col-xs-12 text-center">
								        	<p><small>Powered by Gerd Africa | <a href="https://gerdafrica.com" target="_blank">www.gerdafrica.com</a></small></p>
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

<div class="modal fade in" id="modal_sale_payment">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header header-custom">
                <h6 class="modal-title text-center"><em class="icon ni ni-cc-alt mr-1"></em>Make Payment <span id="payment_header_pos_sale_number"></span></h6>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <form id="frm_sale_make_payment" name="frm_sale_make_payment" method="post" class="is-alter" onsubmit="return submit_sale_payment();">
                <div class="modal-body">
                    <div class="spinner display-none" id="sale_make_payment_loader">
                        <div class="rect1"></div>
                        <div class="rect2"></div>
                        <div class="rect3"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <div>

                                <input type="hidden" id="payment_pos_sale_id" name="pos_sale_id">
                                <input type="hidden" id="payment_pos_sale_number" name="pos_sale_number">
                                <input type="hidden" id="payment_context" name="context">

                                <div class="col-md-12 payments_div">
                                    <div class="box box-solid bg-lighter">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sale_payment_method">Payment Method</label>
                                                        <select class="form-control select2" data-placeholder="Select Paymnent Method"  id="sale_payment_method" name="payment_method">
                                                            <option value="">Select Payment Method</option>
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
                                                        <label for="sale_payment_amount">Amount</label>
                                                        <input type="number" class="form-control" id="sale_payment_amount" name="payment_amount" step="any" min="0">
                                                    </div>
                                                </div>
                                                <div id="div_sale_payment_mpesa_btns" class="display-none">
                                                    <div class="col-md-6">
                                                        <button id="btn_sale_payment_mpesa_instructions" type="button" tabindex="0"  class="badge badge-sm badge-dot has-bg badge-info" data-toggle="popover" data-placement="top" title="MPESA Payment Instructions" data-content="
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
                                                        <button id="btn_sale_make_payment_select" type="button" class="badge badge-sm badge-dot has-bg badge-secondary d-none d-mb-inline-flex">Select Transaction <i class="ion-load-c icon-spinner display-none" id="select_mpesa_payment_loader"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="div_sale_payment_reference_number">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label id="lbl_payment_reference_number" for="sale_payment_reference_number">Reference #</label>
                                                        <input type="text" class="form-control" id="sale_payment_reference_number" name="reference_number" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="payment_note_1">Payment Note</label>
                                                        <textarea type="text" class="form-control" id="sale_payment_note" name="payment_note" placeholder=""></textarea>
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
                                            <div class="row">
                                                <div class="col-md-12 border-custom-bottom">
                                                    <span class="col-md-6 text-right text-bold">Subtotal:</span>
                                                    <span class="col-md-6 text-right text-bold custom-font-size" id="div_sale_subtotal">KES 0.00</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 border-custom-bottom">
                                                    <span class="col-md-6 text-right text-bold">Discount:</span>
                                                    <span class="col-md-6 text-right text-bold custom-font-size" id="div_sale_overall_discount">KES 0.00</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 border-custom-bottom">
                                                    <span class="col-md-6 text-right text-bold">Delivery Fee:</span>
                                                    <span class="col-md-6 text-right text-bold custom-font-size" id="div_sale_delivery_fee">KES 0.00</span>
                                                </div>
                                            </div>
                                            <div class="row bg-red">
                                                <div class="col-md-12 border-custom-bottom">
                                                    <span class="col-md-6 text-right text-bold">Total Payable:</span>
                                                    <span class="col-md-6 text-right text-bold custom-font-size" id="div_sale_total_sale">KES 0.00</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 border-custom-bottom">
                                                    <span class="col-md-6 text-right text-bold">Total Paid:</span>
                                                    <span class="col-md-6 text-right text-bold custom-font-size" id="div_sale_total_paid">KES 0.00</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 border-custom-bottom">
                                                    <span class="col-md-6 text-right text-bold">Balance:</span>
                                                    <span class="col-md-6 text-right text-bold custom-font-size" id="div_sale_payment_balance">KES 0.00</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 bg-orange pt-1 pb-1">
                                                    <span class="col-md-6 text-right text-bold">Change:</span>
                                                    <span class="col-md-6 text-right text-bold custom-font-size" id="div_sale_change">KES 0.00</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn_submit_sale_payment" class="btn btn-success"><i class="ion-checkmark-circled mr-1"></i>Submit Payment</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modal_void_pos_sale">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="icon ni ni-trash-alt"></i> Void Sale</h6>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <div class="modal-body">
                <div class="spinner display-none" id="void_sale_loader">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                </div>
                <form id="frm_void_pos_sale" name="frm_void_pos_sale" method="post" class="is-alter" onsubmit="return submit_void_pos_sale();">

                    <input type="hidden" id="void_pos_sale_id" name="pos_sale_id">
                    <input type="hidden" id="void_context" name="context">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid bg-lighter">
                                <div class="box-body">
                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <textarea name="void_reason" cols="40" rows="5" id="pos_sale_void_reason" class="form-control" placeholder="Void Reason"></textarea>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" id="btn_void_pos_sale" class="btn btn-success"><i class="ion-checkmark-circled mr-1"></i>Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modal_select_mpesa_payment_transaction" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-primary"><i class="icon-plus-circle2"></i> Select MPESA Transaction</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" style="min-height: 200px;">
                <div id="div_select_mpesa_payment_transactions">
                    
                </div>
            </div>
        </div>
    </div>
</div>