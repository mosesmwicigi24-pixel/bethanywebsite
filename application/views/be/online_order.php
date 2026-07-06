		<!-- Main content -->
		<div class="content-wrapper">

				<?php foreach ($online_order as $row): ?>
					<!-- Page header -->
					<div class="page-header">
						<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
							<div class="d-flex">
								<div class="breadcrumb">
									<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
									<a href="#" class="breadcrumb-item">Sales</a>
									<a href="<?php echo base_url();?>be/sales/online" class="breadcrumb-item">Online Orders</a>
									<span class="breadcrumb-item active">View Online Order (<?php echo $row->ord_order_number; ?>)</span>
								</div>

								<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
							</div>
						</div>
					</div>
					<!-- /page header -->


					<!-- Content area -->
					<div class="content pt-0">
						<div class="row">
							<div class="col-md-1"></div>
							<div class="col-md-10">
								<div class="card">
									<div class="card-header bg-transparent header-elements-inline p-2">
										<h5 class="card-title font-weight-bold">Online Order Details <b>(<?php echo $row->ord_order_number; ?>)</b></h5>
										<div class="header-elements">
											<button type="button" class="btn btn-success btn-sm btn-labeled btn-labeled-left dropdown-toggle" data-toggle="dropdown"><b><i class="icon-cog5"></i></b> Action</button>
					                    	<div class="dropdown-menu dropdown-menu-right">
					                    		<?php if ($sbr_online_sales_manage == true): ?>
						                    		<?php if ($row->ord_order_status == 1): ?>
														<?php if ($row->ord_payment_method == 'Pesapal'): ?>
															<?php if ($row->pesapal_payment_id != '' && $row->pesapal_payment_id != null): ?>
																<a href="#" data-toggle="modal" data-target="#modal_dispatch_online_order" data-ord-order-number="<?php echo $row->ord_order_number; ?>" class="dropdown-item lnk_dispatch_online_order"><i class="icon-cart-remove"></i> Dispatch Order</a>
															<?php else: ?>
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_verify_pesapal_payment" onclick="verify_pesapal_payment_load('<?php echo $row->ord_order_number; ?>','View');" class="dropdown-item"><i class="icon-cart-remove"></i> Dispatch Order</a>
															<?php endif; ?>
														<?php else: ?>
															<a href="#" data-toggle="modal" data-target="#modal_dispatch_online_order" data-ord-order-number="<?php echo $row->ord_order_number; ?>" class="dropdown-item lnk_dispatch_online_order"><i class="icon-cart-remove"></i> Dispatch Order</a>
														<?php endif; ?>
													<?php endif; ?>
													<?php if ($row->ord_order_status == 2): ?>
														<a href="javascript:void(0);" onclick="complete_order('<?php echo $row->ord_order_number; ?>');" class="dropdown-item"><i class="icon-checkmark-circle2"></i> Complete Order</a>
													<?php endif; ?>	
													<div class="dropdown-divider"></div>												
												<?php endif; ?>
												
												<?php if ($sbr_online_sales_print == true): ?>
													<a href="<?php echo base_url(); ?>be/sales/online_order_print/<?php echo $row->ord_order_number; ?>" class="dropdown-item" target="_blank"><i class="icon-printer"></i> Print Order</a>
													<a href="<?php echo base_url(); ?>be/sales/online_order_pdf/<?php echo $row->ord_order_number; ?>" class="dropdown-item" target="_blank"><i class="icon-file-pdf"></i> Export to PDF</a>
												<?php endif; ?>
												<a href="#" data-toggle="modal" data-target="#modal_send_online_order_via_email" data-ord-order-number="<?php echo $row->ord_order_number; ?>" class="dropdown-item lnk_send_online_order_via_email"><i class="icon-mail5"></i> Send via Mail</a>
											</div>
					                	</div>
									</div>

									<div class="card-body">
										<div class="row">
											<div class="col-sm-6">
												<div class="mb-4">
													<?php foreach ($store_information as $row2): ?>
														<?php if($row2->store_logo != '' && file_exists("./uploads/store_logo/" . $row2->store_logo)): ?>
															<img src="<?php echo base_url();?>uploads/store_logo/<?php echo $row2->store_logo; ?>" class="mb-1 mt-1" alt="" style="height: 70px;">
														<?php endif; ?>
							 							<ul class="list list-unstyled mb-0">
															<li><h5 class="mt-0 mb-0"><?php echo $row2->store_name; ?></h5></li>
															<li><?php echo $row2->email_address; ?></li>
															<li><?php echo $row2->phone_number; ?></li>
														</ul>
													<?php endforeach; ?>
												</div>
											</div>

											<div class="col-sm-6">
												<div class="mb-4">
													<div class="text-sm-right">
														<h4 class="text-primary mb-2 mt-md-2 text-uppercase">Online Order</h4>
														<ul class="list list-unstyled mb-0">
															<li>Reference #: <span class="font-weight-bold"><?php echo $row->ord_order_number; ?></span></li>
															<li>Order Date: <span class="font-weight-bold"><?php echo date('d M, Y', strtotime($row->ord_date)); ?></span></li>
															<li>Status: 
																<?php if ($row->ord_order_status == 0): ?>
			                                                        <span class="badge badge-pill badge-warning"><i class="icon-hour-glass3 icon-sm"></i> Awaiting Payment</span>
			                                                    <?php elseif ($row->ord_order_status == 1): ?>
			                                                        <span class="badge badge-pill badge-info"><i class="icon-spinner2 icon-sm"></i> Processing</span>
			                                                    <?php elseif ($row->ord_order_status == 2): ?>
			                                                        <span class="badge badge-pill badge-primary"><i class="icon-cart-remove icon-sm"></i> Dispatched</span>
			                                                    <?php elseif ($row->ord_order_status == 3): ?>
			                                                        <span class="badge badge-pill badge-success"><i class="icon-checkmark-circle2 icon-sm"></i> Completed</span>
			                                                    <?php elseif ($row->ord_order_status == 4): ?>
			                                                        <span class="badge badge-pill badge-danger"><i class="icon-cancel-circle2 icon-sm"></i> Cancelled</span>
			                                                    <?php endif; ?>
															</li>
														</ul>
													</div>
												</div>
											</div>
										</div>

										<div class="d-md-flex flex-md-wrap">
											<div class="mb-4 mb-md-2">
												<span class="text-muted font-weight-bold">Customer:</span>
					 							<ul class="list list-unstyled mb-0">
													<li><h5 class="my-2 mb-0"><?php echo $row->ord_shipping_first_name . ' ' . $row->ord_shipping_last_name; ?></h5></li>
													<?php if ($row->ord_shipping_email_address != ''): ?>
														<li><span class="font-weight-semibold"><?php echo $row->ord_shipping_email_address; ?></span></li>
													<?php endif; ?>
													<?php if ($row->ord_shipping_phone_number != ''): ?>
														<li><span class="font-weight-semibold"><?php echo $row->ord_shipping_phone_number; ?></span></li>
													<?php endif; ?>
												</ul>
											</div>
										</div>
									</div>

									<div class="table-responsive">
									    <table class="table table-bordered table-condensed" style="font-size:90%">
                                                    <tbody>
                                                        <tr class="text-uppercase">
                                                            <th>Item</th>
                                                            <th>Unit Price</th>                                                    
                                                            <th class="text-center">Qty</th>
                                                            <th class="text-right">Total Price</th>
                                                        </tr> 
                                                        <?php foreach ($online_order_details as $row2): ?>
                                                            <tr>
                                                                <td><?php echo $row2->ord_det_item_name;?><?php if ($row2->ord_det_product_variation_description != ''){ echo '<br>' . $row2->ord_det_product_variation_description; }?><br><b>SKU Code:</b><?php echo $row2->ord_det_product_sku_code;?></td>
                                                                <td><?php echo number_format($row2->ord_det_price,2); ?></td>
                                                                <td class="text-center"><?php echo number_format($row2->ord_det_quantity,0); ?></td>
                                                                <td class="text-right"><?php echo number_format($row2->ord_det_price_total,2); ?></td>
                                                            </tr>   
                                                        <?php endforeach; ?>                                                               
                                                    </tbody>
                                                    <tfoot>
                                                        <tr>
                                                            <th colspan="3" class="text-right"><strong>No. of Items</strong></th>
                                                            <th class="text-right"><strong><?php echo number_format($row->ord_total_items,0);?></strong></th>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="3" class="text-right"><strong>Sub-Total</strong></th>
                                                            <th class="text-right"><strong><?php echo number_format($row->ord_item_summary_total,2);?></strong></th>
                                                        </tr>                            
                                                        <tr>
                                                            <th colspan="3" class="text-right"><strong>Total Tax</strong></th>
                                                            <th class="text-right"><strong><?php echo number_format($row->ord_tax_total,2);?></strong></th>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="3" class="text-right"><strong>Shipping</strong></th>
                                                            <th class="text-right"><strong><?php echo number_format($row->ord_shipping_total,2);?></strong></th>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="3" class="text-right"><strong>Discount</strong></th>
                                                            <th class="text-right"><strong><?php echo number_format($row->ord_savings_total,2);?></strong></th>
                                                        </tr>
                                                        <tr>
                                                            <th colspan="3" class="text-right"><h5>Order Total</h5></th>
                                                            <th class="text-right"><h5><?php echo number_format($row->ord_total,2);?></h5></th>
                                                        </tr>
                                                    </tfoot>                                            
                                                </table>
									</div>

									<div class="card-body">
										<div class="d-md-flex flex-md-wrap">
											<?php if ($row->ord_shipping_instructions != ''): ?>
												<div class="pt-5 mb-3">
													<p><?php echo $row->ord_shipping_instructions; ?></p>
												</div>
											<?php endif; ?>
										</div>
									</div>

									<div class="card-footer text-center">
										<span class="text-muted">Online Order created on <?php echo date('d M, Y H:i:s', strtotime($row->ord_date)); ?></span>
									</div>
								</div>
							</div>
							<div class="col-md-1"></div>
						</div>

						
					</div>

					<div class="modal fade" id="modal_verify_pesapal_payment" tabindex="-1">
						<div class="modal-dialog modal-sm">
							<div class="modal-content">
								<div class="modal-header">
									<h5 class="modal-title"><i class="icon-warning"></i> Pesapal Payment Verification</h5>
									<button type="button" class="close" data-dismiss="modal">&times;</button>
								</div>

								<div class="modal-body">
									<div id="div_pesapal_payment_verification" style="min-height: 150px">

					                </div>
					            </div>

				            </div>
				        </div>
				    </div>
				<?php endforeach; ?>

				<div class="modal fade in" id="modal_send_online_order_via_email">
				    <div class="modal-dialog">
				        <div class="modal-content">
				            <div class="modal-header header-custom">
				                <h4 class="modal-title text-center"><em class="icon ni ni-mail mr-1"></em>Send Online Order via Email [As Attachment]</h4>
				                <button type="button" class="close" data-dismiss="modal">&times;</button>
				            </div>
				            <form id="frm_send_online_order_via_email" name="frm_send_online_order_via_email" method="post" class="is-alter" onsubmit="return submit_send_online_order_via_email();">
				                <div class="modal-body">
				                    <div class="spinner2 display-none" id="send_online_order_via_email_loader">
				                        <div class="rect1"></div>
				                        <div class="rect2"></div>
				                        <div class="rect3"></div>
				                    </div>
				                    <div class="row">
				                        <div class="col-md-12">
				                            <div>

				                                <input type="hidden" id="send_email_ord_order_number" name="ord_order_number">

				                                <div class="col-md-12">
				                                    <div>
				                                        <div>
				                                            <div class="row">
				                                                <div class="col-md-12">
				                                                    <div class="form-group mb-2">
				                                                        <label for="sale_payment_method">Sender Email Account</label>
				                                                        <select class="form-control form-control-select2" data-placeholder="Select Email Address" id="spo_email_account_id" name="email_account_id">
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
				                                                    <div class="form-group mb-2">
				                                                        <label for="sender_name">Sender Name<span class="text-danger">*</span></label>
				                                                        <input type="text" class="form-control" id="sender_name" name="sender_name" placeholder="">
				                                                    </div>
				                                                </div>
				                                                <div class="col-md-6">
				                                                    <div class="form-group mb-2">
				                                                        <label for="sender_email_address">Sender Email Address<span class="text-danger">*</span></label>
				                                                        <input type="email" class="form-control" id="sender_email_address" name="sender_email_address" placeholder="">
				                                                    </div>
				                                                </div>
				                                            </div>
				                                            <div class="row display-none">
				                                                <div class="col-md-6">
				                                                    <div class="form-group mb-2">
				                                                        <label for="mail_server_name">Mail Server (SMTP)<span class="text-danger">*</span></label>
				                                                        <input type="text" class="form-control" id="mail_server_name" name="mail_server_name" placeholder="">
				                                                    </div>
				                                                </div>
				                                                <div class="col-md-3">
				                                                    <div class="form-group mb-2">
				                                                        <label for="mail_server_port">Mail Server Port<span class="text-danger">*</span></label>
				                                                        <input type="number" class="form-control" id="mail_server_port" name="mail_server_port" placeholder="">
				                                                    </div>
				                                                </div>
				                                                <div class="col-md-3">
				                                                    <div class="form-group mb-2">
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
				                                                    <div class="form-group mb-2">
				                                                        <label for="sender_username">Username<span class="text-danger">*</span></label>
				                                                        <input type="email" class="form-control" id="sender_username" name="sender_username" placeholder="">
				                                                    </div>
				                                                </div>
				                                                <div class="col-md-6">
				                                                    <div class="form-group mb-2">
				                                                        <label for="sender_password">Password<span class="text-danger">*</span></label>
				                                                        <input type="password" class="form-control" id="sender_password" name="sender_password" placeholder="">
				                                                    </div>
				                                                </div>
				                                            </div>
				                                            <div class="row">
				                                                <div class="col-md-12">
				                                                    <div class="form-group mb-2">
				                                                        <label for="recipient_email_address">Recipient Email Address<span class="text-danger">*</span></label>
				                                                        <input type="email" class="form-control" id="recipient_email_address" name="recipient_email_address" placeholder="">
				                                                    </div>
				                                                </div>
				                                            </div>
				                                            <div class="row">
				                                                <div class="col-md-12">
				                                                    <div class="form-group mb-2">
				                                                        <label for="email_subject">Subject<span class="text-danger">*</span></label>
				                                                        <input type="text" class="form-control" id="email_subject" name="email_subject" placeholder="">
				                                                    </div>
				                                                </div>
				                                            </div>
				                                            <div class="row">
				                                                <div class="col-md-12">
				                                                    <div class="form-group mb-2">
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
				                    <button type="submit" id="btn_send_online_order_via_email" class="btn btn-success"><em class="icon ni ni-send mr-1"></em>Send Email</button>
				                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
				                </div>
				            </form>
				        </div>
				    </div>
				</div>

				<div class="modal fade in" id="modal_send_online_order_customer_email">
				    <div class="modal-dialog">
				        <div class="modal-content">
				            <div class="modal-header header-custom">
				                <h4 class="modal-title text-center"><em class="icon ni ni-mail mr-1"></em>Send Customer Email</h4>
				                <button type="button" class="close" data-dismiss="modal">&times;</button>
				            </div>
				            <form id="frm_send_online_order_customer_email" name="frm_send_online_order_customer_email" method="post" class="is-alter" onsubmit="return submit_send_online_order_customer_email();">
				                <div class="modal-body">
				                    <div class="spinner2 display-none" id="send_online_order_customer_email_loader">
				                        <div class="rect1"></div>
				                        <div class="rect2"></div>
				                        <div class="rect3"></div>
				                    </div>
				                    <div class="row">
				                        <div class="col-md-12">
				                            <div>

				                                <input type="hidden" id="send_customer_email_ord_order_number" name="ord_order_number">

				                                <div class="col-md-12">
				                                    <div>
				                                        <div>
				                                            <div class="row">
				                                                <div class="col-md-12">
				                                                    <div class="form-group mb-2">
				                                                        <label for="sooc_email_account_id">Sender Email Account</label>
				                                                        <select class="form-control form-control-select2" data-placeholder="Select Email Address" id="sooc_email_account_id" name="email_account_id">
				                                                            <option value="">Select Email Address</option>
				                                                            <?php foreach ($email_accounts as $row2): ?>
				                                                                <option value="<?php echo $row2->email_account_id; ?>"><?php echo $row2->sender_name . ' ~ ' . $row2->sender_email_address ; ?></option>
				                                                            <?php endforeach; ?>
				                                                            
				                                                        </select>
				                                                    </div>
				                                                </div>
				                                            </div>
				                                            <div class="row">
				                                                <div class="col-md-6">
				                                                    <div class="form-group mb-2">
				                                                        <label for="customer_sender_name">Sender Name<span class="text-danger">*</span></label>
				                                                        <input type="text" class="form-control" id="sender_name" name="sender_name" placeholder="">
				                                                    </div>
				                                                </div>
				                                                <div class="col-md-6">
				                                                    <div class="form-group mb-2">
				                                                        <label for="customer_sender_email_address">Sender Email Address<span class="text-danger">*</span></label>
				                                                        <input type="email" class="form-control" id="sender_email_address" name="sender_email_address" placeholder="">
				                                                    </div>
				                                                </div>
				                                            </div>
				                                            <div class="row">
				                                                <div class="col-md-6">
				                                                    <div class="form-group mb-2">
				                                                        <label for="customer_mail_server_name">Mail Server (SMTP)<span class="text-danger">*</span></label>
				                                                        <input type="text" class="form-control" id="mail_server_name" name="mail_server_name" placeholder="">
				                                                    </div>
				                                                </div>
				                                                <div class="col-md-3">
				                                                    <div class="form-group mb-2">
				                                                        <label for="customer_mail_server_port">Mail Server Port<span class="text-danger">*</span></label>
				                                                        <input type="number" class="form-control" id="mail_server_port" name="mail_server_port" placeholder="">
				                                                    </div>
				                                                </div>
				                                                <div class="col-md-3">
				                                                    <div class="form-group mb-2">
				                                                        <label for="">&nbsp;</label><br>
				                                                        <div class="custom-control custom-checkbox">
				                                                          <input type="checkbox" class="custom-control-input" id="customer_chk_use_ssl" name="chk_use_ssl" />
				                                                          <label class="custom-control-label" for="chk_use_ssl">Use SSL</label>
				                                                        </div>
				                                                    </div>
				                                                </div>
				                                            </div>
				                                            <div class="row">
				                                                <div class="col-md-6">
				                                                    <div class="form-group mb-2">
				                                                        <label for="customer_sender_username">Username<span class="text-danger">*</span></label>
				                                                        <input type="email" class="form-control" id="sender_username" name="sender_username" placeholder="">
				                                                    </div>
				                                                </div>
				                                                <div class="col-md-6">
				                                                    <div class="form-group mb-2">
				                                                        <label for="customer_sender_password">Password<span class="text-danger">*</span></label>
				                                                        <input type="password" class="form-control" id="sender_password" name="sender_password" placeholder="">
				                                                    </div>
				                                                </div>
				                                            </div>
				                                            <div class="row">
				                                                <div class="col-md-12">
				                                                    <div class="form-group mb-2">
				                                                        <label for="customer_recipient_email_address">Recipient Email Address<span class="text-danger">*</span></label>
				                                                        <input type="email" class="form-control" id="recipient_email_address" name="recipient_email_address" placeholder="">
				                                                    </div>
				                                                </div>
				                                            </div>
				                                            <div class="row">
				                                                <div class="col-md-12">
				                                                    <div class="form-group mb-2">
				                                                        <label for="customer_email_subject">Subject<span class="text-danger">*</span></label>
				                                                        <input type="text" class="form-control" id="email_subject" name="email_subject" placeholder="">
				                                                    </div>
				                                                </div>
				                                            </div>
				                                            <div class="row">
				                                                <div class="col-md-12">
				                                                    <div class="form-group mb-2">
				                                                        <label for="customer_email_message">Message</label>
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
				                    <button type="submit" id="btn_send_online_order_customer_email" class="btn btn-success"><em class="icon ni ni-send mr-1"></em>Send Email</button>
				                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
				                </div>
				            </form>
				        </div>
				    </div>
				</div>

				<div class="modal fade in" id="modal_dispatch_online_order">
			    <div class="modal-dialog">
			        <div class="modal-content">
			            <div class="modal-header header-custom">
			                <h4 class="modal-title text-center"><i class="icon-cart-remove"></i> Dispatch Order [<span id="spn_dispatch_order_number"></span>]</h4>
			                <button type="button" class="close" data-dismiss="modal">&times;</button>
			            </div>
			            <form id="frm_dispatch_online_order" name="frm_dispatch_online_order" method="post" class="is-alter" onsubmit="return submit_dispatch_online_order('View');">
			                <div class="modal-body">
			                    <div class="spinner2 display-none" id="dispatch_online_order_loader">
			                        <div class="rect1"></div>
			                        <div class="rect2"></div>
			                        <div class="rect3"></div>
			                    </div>
			                    <div class="row">
			                        <div class="col-md-12">
			                            <div>

			                                <input type="hidden" id="disptch_order_number" name="ord_order_number">

			                                <div class="col-md-12">
			                                    <div>
			                                        <div>
			                                            <div class="row">
			                                                <div class="col-md-12">
			                                                    <div class="form-group">
			                                                        <label for="sale_payment_method">Dispatch Outlet</label>
			                                                        <select class="form-control form-control-select2" data-placeholder="Select Dispatch Outlet" id="dispatch_outlet_id" name="outlet_id">
			                                                            <option value="">Select Dispatch Outlet</option>
			                                                            <?php foreach ($outlets as $row): ?>
			                                                                <option value="<?php echo $row->outlet_id; ?>"><?php echo $row->outlet_name; ?></option>
			                                                            <?php endforeach; ?>
			                                                            
			                                                        </select>
			                                                    </div>
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
			                    <button type="submit" id="btn_dispatch_online_order" class="btn btn-success"><em class="icon-checkmark-circle"></em> Dispatch Order</button>
			                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-cross3"></i> Cancel</button>
			                </div>
			            </form>
			        </div>
			    </div>
			</div>

