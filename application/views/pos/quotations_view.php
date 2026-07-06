<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">

            	<div class="nk-block nk-block-lg">
            		<div class="nk-block-head nk-block-head-sm">
					    <div class="nk-block-between">
					        <div class="nk-block-head-content"><h5 class="nk-block-title page-title"><em class="icon ni ni-eye"></em> View Quotation</h5></div>
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

					<div class="row">
						<div class="col-md-2"></div>
						<div class="col-md-8">
		            		<section class="invoice card card-preview p-3">
		            			<?php foreach ($pos_quotation as $row): ?>
								    <div class="printableArea card-body">
								        <div class="row mb-4">
								            <div class="col-xs-12 text-right">
								                <!-- <h6 class="page-header">quotation #<?php echo $row->pos_quotation_number; ?> Details</h6> -->
								                <div class="drodown">
												    <a href="#" class="dropdown-toggle dropdown-indicator btn btn-outline-primary btn-white" data-toggle="dropdown">Action</a>
												    <div class="dropdown-menu dropdown-menu-end">
												        <ul class="link-list-opt no-bdr">
												        	<?php if ($sbr_pos_quotations_edit == true): ?>
													        	<?php if ($row->is_void == 0): ?>
														            <li>
														                <a href="<?php echo base_url(); ?>pos/quotations/edit/<?php echo $row->pos_quotation_id; ?>"><em class="icon ni ni-edit"></em><span>Edit Quotation</span></a>
														            </li>
														        <?php endif; ?>
														    <?php endif; ?>
														    <?php if ($sbr_pos_quotations_print == true): ?>
													            <li>
													                <a href="<?php echo base_url(); ?>pos/quotations/print_a4/<?php echo $row->pos_quotation_id; ?>" target="_blank"><em class="icon ni ni-printer"></em><span>Print Quotation</span></a>
													            </li>
													        <?php endif; ?>
												            <li>
												                <a href="javascript:;" data-toggle="modal" data-target="#modal_send_quotation_order_via_email" data-pos-quotation-id="<?php echo $row->pos_quotation_id; ?>" class="lnk_send_quotation_order_via_email"><em class="icon ni ni-mail"></em><span>Send Via Mail</span></a>
												            </li>
												            <?php if ($sbr_pos_quotations_delete == true): ?>
													            <?php if ($row->is_void == 0): ?>
														            <li>
														                <a href="javascript:;" data-pos-quotation-id="<?php echo $row->pos_quotation_id; ?>" data-context="View Quotation" class="lnk_void_pos_quotation"><em class="icon ni ni-shield-off"></em><span>Void Quotation</span></a>
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
								            	<h4 class="text-uppercase font-weight-light">Quotation</h4>
								            	<p class="lead text-info"><?php echo $row->pos_quotation_number; ?></p>
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
								                	<b>Quotation Date:</b> <?php echo date('d M, Y', strtotime($row->quotation_date)); ?><br />
								                	<b>Valid Until:</b> <?php if ($row->valid_until != ''){ echo date('d M, Y', strtotime($row->valid_until)); } ?><br />
									                <b>Status:</b> 
								                	<?php if ($row->is_void == 1): ?>
		                                                <span class="badge badge-sm badge-dot has-bg badge-danger d-none d-mb-inline-flex">VOID</span>
		                                            <?php else: ?>
		                                                <span class="badge badge-sm badge-dot has-bg badge-success d-none d-mb-inline-flex">ACTIVE</span>
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
								                    	<?php foreach ($pos_quotation_details as $row2): ?>
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
							                            <tr>
							                                <th colspan="6">Total Quantity</th>
							                                <th><?php echo number_format($row->total_quantity,2); ?></th>
							                            </tr>

							                            <tr>
							                                <th colspan="6">Subtotal</th>
							                                <th><?php echo number_format($row->sub_total,2); ?></th>
							                            </tr>                           

							                            <tr>
							                                <th colspan="6">Discount</b></th>
							                                <th><?php echo number_format($row->discount,2); ?></th>
							                            </tr>

							                            <tr>
							                                <th colspan="6">Delivery Fee</th>
							                                <th><?php echo number_format($row->delivery_fee,2); ?></th>
							                            </tr>

							                            <tr>
							                                <th colspan="6"><big>Grand Total</big></th>
							                                <th><big><?php echo number_format($row->total_amount,2); ?></big></th>
							                            </tr>
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
								                                	<?php foreach ($pos_quotation_tax_details as $row2): ?>
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

<div class="modal fade in" id="modal_send_quotation_order_via_email">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header header-custom">
                <h6 class="modal-title text-center"><em class="icon ni ni-mail mr-1"></em>Send Order via Email</h6>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <form id="frm_send_quotation_order_via_email" name="frm_send_quotation_order_via_email" method="post" class="is-alter" onsubmit="return submit_send_quotation_order_via_email();">
                <div class="modal-body">
                    <div class="spinner display-none" id="send_quotation_order_via_email_loader">
                        <div class="rect1"></div>
                        <div class="rect2"></div>
                        <div class="rect3"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div>

                                <input type="hidden" id="send_email_pos_quotation_id" name="pos_quotation_id">

                                <div class="col-md-12">
                                    <div class="box box-solid bg-lighter">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="quotation_payment_method">Sender Email Account</label>
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
                    <button type="submit" id="btn_send_pos_quotation_order_via_email" class="btn btn-success"><em class="icon ni ni-send mr-1"></em>Send Email</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modal_void_pos_quotation">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="icon ni ni-trash-alt"></i> Void Quotation</h6>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <div class="modal-body">
                <div class="spinner display-none" id="void_quotation_loader">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                </div>
                <form id="frm_void_pos_quotation" name="frm_void_pos_quotation" method="post" class="is-alter" onsubmit="return submit_void_pos_quotation();">

                    <input type="hidden" id="void_pos_quotation_id" name="pos_quotation_id">
                    <input type="hidden" id="void_context" name="context">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid bg-lighter">
                                <div class="box-body">
                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <textarea name="void_reason" cols="40" rows="5" id="pos_quotation_void_reason" class="form-control" placeholder="Void Reason"></textarea>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" id="btn_void_pos_quotation" class="btn btn-success"><i class="ion-checkmark-circled mr-1"></i>Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

