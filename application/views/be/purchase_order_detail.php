		<!-- Main content -->
		<div class="content-wrapper">

				<?php foreach ($purchase_order as $row): ?>
					<!-- Page header -->
					<div class="page-header">
						<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
							<div class="d-flex">
								<div class="breadcrumb">
									<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
									<a href="#" class="breadcrumb-item">Inventory</a>
									<a href="<?php echo base_url();?>be/inventory/purchase_orders" class="breadcrumb-item">Purchase Orders</a>
									<span class="breadcrumb-item active">View Purchase Order (<?php echo $row->purchase_order_number; ?>)</span>
								</div>

								<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
							</div>
						</div>
					</div>
					<!-- /page header -->


					<!-- Content area -->
					<div class="content pt-0">
						<div class="row mb-4">
							<div class="col-md-6">
								<h4 class="text-primary font-weight-bold"><i class="icon-eye"></i> View Purchase Order (<?php echo $row->purchase_order_number; ?>)</h4>
							</div>
							<div class="col-md-6 text-right">
								<a href="<?php echo base_url(); ?>be/inventory/purchase_orders" class="btn btn-primary btn-sm"><i class="icon-arrow-left15"></i><span>Purchase Orders List</span></a>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2"></div>
							<div class="col-md-8">
								<div class="invoice card card-preview p-3">
									<div class="spinner2 display-none" id="purchase_orders_loader">
				                        <div class="rect1"></div>
				                        <div class="rect2"></div>
				                        <div class="rect3"></div>
				                    </div>
									<div class="card-body">
										<div class="row mb-2">
								            <div class="col-md-12 text-right">
								            	<button type="button" class="btn btn-primary btn-outline-primary btn-sm badge-pill dropdown-toggle" data-toggle="dropdown"><b><i class="icon-cog5"></i></b> Action</button>
						                    	<div class="dropdown-menu dropdown-menu-right">

													<?php if ($row->is_void == 0): ?>
														<?php if ($sbr_purchase_orders_edit == true): ?>
															<a href="<?php echo base_url(); ?>be/inventory/purchase_order_edit/<?php echo $row->purchase_order_id; ?>" class="dropdown-item"><i class="icon-pencil6"></i> Edit Purchase Order</a>
														<?php endif; ?>
														<?php //if ($sbr_purchase_orders_edit == true): ?>
															<a href="javascript:;" href="javascript:;" data-purchase-order-id="<?php echo $row->purchase_order_id; ?>" data-context="View Purchase Order" class="dropdown-item purchase_order_record_payment"><i class="icon-coins"></i> Record Payment</a>
														<?php //endif; ?>
													<?php endif; ?>
													
													<div class="dropdown-divider"></div>
													<?php if ($sbr_purchase_orders_print == true): ?>
														<a href="<?php echo base_url(); ?>be/inventory/purchase_order_print_supplier/<?php echo $row->purchase_order_id; ?>" class="dropdown-item" target="_blank"><i class="icon-printer"></i> Print Supplier's Copy</a>
														<a href="<?php echo base_url(); ?>be/inventory/purchase_order_print/<?php echo $row->purchase_order_id; ?>" class="dropdown-item" target="_blank"><i class="icon-printer"></i> Print Local Copy</a>
													<?php endif; ?>
													<a href="#" data-toggle="modal" data-target="#modal_send_purchase_order_via_email" data-purchase-order-id="<?php echo $row->purchase_order_id; ?>" class="dropdown-item lnk_send_purchase_order_via_email"><i class="icon-mail5"></i> Send via Mail</a>
													<?php if ($row->is_void == 0): ?>
														<?php if ($sbr_purchase_orders_edit == true): ?>
															<a href="javascript:;" class="dropdown-item void-purchase-order" data-purchase-order-id="<?php echo $row->purchase_order_id; ?>" data-context="View Purchase Order"><i class="icon-trash-alt"></i> Void Purchase Order</a>
														<?php endif; ?>
													<?php endif; ?>

													<?php if ($row->is_void == 0): ?>
														<?php if ($sbr_goods_received_add == true): ?>
															<div class="dropdown-divider"></div>
															<a href="<?php echo base_url();?>be/inventory/receive" class="dropdown-item text-primary font-13 font-weight-bold"><i class="icon-import"></i> Receive Stock</a>
														<?php endif; ?>
													<?php endif; ?>
												</div>
								            </div>
								        </div>
										<div class="row">
											<div class="col-sm-6">
												<div class="mb-4">
													<?php foreach ($store_information as $row2): ?>
														<?php if($row2->store_logo != '' && file_exists("./uploads/store_logo/" . $row2->store_logo)): ?>
															<img src="<?php echo base_url();?>uploads/store_logo/<?php echo $row2->store_logo; ?>" class="mb-1 mt-1" alt="" style="height: 50px;">
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
													<?php endforeach; ?>
												</div>
											</div>

											<div class="col-sm-6">
												<div class="mb-0">
													<div class="text-sm-right">
														<h2 class="text-uppercase font-weight-light mb-0">Purchase Order</h2>
								            			<p class="lead text-info"><?php echo $row->purchase_order_number; ?></p>							            			

								            			<p class="mb-3">
										                	<b>Order Date:</b> <?php echo date('d M, Y', strtotime($row->purchase_order_date)); ?><br />
										                	<?php if ($row->expected_date != ''): ?>
										                		<b>Expected Date:</b> <?php echo date('d M, Y', strtotime($row->expected_date)); ?><br />
										                	<?php endif; ?>											                
											                <b>Status:</b> 
										                	<?php if ($row->is_void == 1): ?>
				                                                <span class="badge badge-danger badge-pill mt-1 font-11">Voided</span>
				                                            <?php else: ?>
				                                            	<?php if ($row->total_received_qty == 0): ?>
																	<span class="badge bg-grey badge-pill mt-1 font-11">Unreceived</span>
																<?php elseif ($row->total_received_qty < $row->total_detail_qty): ?>
																	<span class="badge badge-info badge-pill mt-1 font-11">Partially Received</span>
																<?php elseif ($row->total_received_qty == $row->total_detail_qty): ?>
																	<span class="badge badge-success badge-pill mt-1 font-11">Received</span>
																<?php endif; ?>
				                                            <?php endif; ?>
				                                            <br>
				                                            <b>Payment Status:</b>
				                                            <?php if ($row->is_void == 1): ?>
				                                            	&mdash;
				                                            <?php else: ?>
					                                            <?php if ($row->total_paid == 0): ?>
			                                                        <span class="badge bg-grey badge-pill mt-1 font-11">Unpaid</span>
			                                                    <?php elseif ($row->total_paid > 0 && $row->total_paid < $row->total_amount): ?>
			                                                        <span class="badge badge-info badge-pill mt-1 font-11">Partially Paid</span>
			                                                    <?php else: ?>
			                                                        <span class="badge badge-success badge-pill mt-1 font-11">Paid</span>
			                                                    <?php endif; ?>
			                                                <?php endif; ?>
					                                    </p>	
					                                    <p class="font-weight-bold mb-0">SUPPLIER</p>
										            	<p>
										            		<span class="lead"><?php echo $row->supplier_name; ?></span>
										            		<?php if ($row->email_address != ''): ?>
																<br><span class="font-weight-semibold"><?php echo $row->email_address; ?></span>
															<?php endif; ?>
															<?php if ($row->phone_number != ''): ?>
																<br><span class="font-weight-semibold"><?php echo $row->phone_number; ?></span>
															<?php endif; ?>
										            	</p>													
													</div>
												</div>
											</div>
										</div>									
									</div>

									<div class="table-responsive">
									    <table class="table table-striped table-bordered">
									        <thead>
									            <tr>
									                <th>Product</th>
									                <th>Unit</th>
									                <th>Unit Cost (<?php echo $default_currency; ?>)</th>
									                <th>Ordered</th>
									                <th>Received</th>
									                <th>Tax</th>
									                <th>Total (<?php echo $default_currency; ?>)</th>
									            </tr>
									        </thead>
									        <tbody>
									        	<?php foreach ($purchase_order_details as $row2): ?>
										            <tr>
										                <td>
										                	<h6 class="mb-0"><?php echo $row2->product_name; ?></h6>
										                	<?php if(!empty($row2->attributes)): ?>
										                		<?php
										                			$variation_description = '';
										                			foreach ($row2->attributes as $row3){
										                				$variation_description = $variation_description . $row3->product_attribute_name . ' : <b>' . $row3->product_attribute_value . '</b>, ';
										                			}
										                			echo '<i class="badge badge-mark ml-2"></i> ' . substr($variation_description,0,-2);
										                		?><br>
															<?php endif; ?>
										                	<!-- <span class="text-muted"><strong>SUK: </strong><?php echo $row2->product_sku_code; ?></span> -->
									                	</td>
									                	<td><?php echo $row2->unit_name . ' (' . $row2->unit_code . ')'; ?></td>
									                	<td><?php echo number_format($row2->unit_price,2); ?></td>
										                <td><?php echo number_format($row2->detail_quantity,2); ?></td>
										                <td><?php echo number_format($row2->received_quantity,2); ?></td>
										                <td><?php echo $row2->tax_rate_code; ?></td>
										                <td><?php echo number_format($row2->detail_total_amount,2); ?></td>
										            </tr>
										        <?php endforeach; ?>
									        </tbody>
									        <tfoot>
									        	<tr>
					                                <th colspan="6" class="text-right font-weight-bold">Subtotal:</th>
					                                <th class=" font-weight-bold"><?php echo number_format($row->sub_total,2); ?></th>
					                            </tr>
					                            <!-- <tr>
					                                <th colspan="5" class="text-right font-weight-bold">Total Discount:</th>
					                                <th class=" font-weight-bold"><?php echo number_format($row->discount_amount,2); ?></th>
					                            </tr> -->
					                            <tr>
					                                <th colspan="6" class="text-right font-weight-bold">Total Tax:</th>
					                                <th class=" font-weight-bold"><?php echo number_format($row->tax_amount,2); ?></th>
					                            </tr>
					                            <tr>
					                                <th colspan="6" class="text-right font-weight-bold">Freight:</th>
					                                <th class=" font-weight-bold"><?php echo number_format($row->freight_cost,2); ?></th>
					                            </tr>   
					                            <tr>
					                                <th colspan="6" class="text-right font-weight-bold lead">Total:</th>
					                                <th class="lead font-weight-bold"><?php echo number_format($row->total_amount,2); ?></th>
					                            </tr>
					                            <?php if ($row->is_void == 0 && $row->total_paid > 0): ?>
					                            	<tr>
						                                <th colspan="6" class="text-right font-weight-bold">Total Paid:</th>
						                                <th class=" font-weight-bold"><?php echo number_format($row->total_paid,2); ?></th>
						                            </tr>
						                            <tr>
						                                <th colspan="6" class="text-right font-weight-bold">Balance:</th>
						                                <th class=" font-weight-bold"><?php if ($row->total_paid >= $row->total_amount){ echo number_format(0,2); } else{ echo number_format($row->total_amount - $row->total_paid,2); } ?></th>
						                            </tr>
						                        <?php endif; ?>
									        </tfoot>
									    </table>
									</div>

									<div class="row mt-1">
							            <div class="col-md-12">
							                <div class="row mt-3">
							                	<div class="col-md-5">
							                		<?php if ($num_purchase_order_tax_details > 0): ?>
								                        <div class="table-responsive">
								                            <h5 class="text-uppercase">Tax Details</h5>
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
								                                	<?php foreach ($purchase_order_tax_details as $row2): ?>
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
								                        </div>
								                    <?php endif; ?>
							                    </div>
							                    <div class="col-md-7">
							                    	<?php if ($num_purchase_order_payments > 0): ?>
								                        <div class="table-responsive">
								                            <h5 class="text-uppercase">Payments</h5>
								                            <table class="table table-hover" style="width: 100%;" id="">
								                                <thead>
								                                    <tr class="">
								                                        <th>Date</th>
								                                        <th>Type</th>
								                                        <th>Ref #</th>
								                                        <th>Amount</th>
								                                        <th>User</th>
								                                        <th>Action</th>
								                                    </tr>
								                                </thead>
								                                <tbody>
								                                	<?php $count = 1; ?>
								                                	<?php $total_paid = 0; ?>
								                                	<?php foreach ($purchase_order_payments as $row2): ?>
								                                		<?php $total_paid = $total_paid + $row2->payment_amount; ?>
									                                    <tr>
									                                        <td><?php echo date('d M, Y', strtotime($row2->created_on)); ?></td>
									                                        <td><?php echo $row2->payment_method; ?></td>
									                                        <td><?php echo $row2->reference_number; ?></td>
									                                        <td><?php echo number_format($row2->payment_amount,2); ?></td>
									                                        <td><?php echo $row2->first_name . ' ' . $row2->last_name; ?></td>
									                                        <td>
									                                        	<?php if ($row->is_void == 0): ?>
																					<?php if ($sbr_purchase_orders_edit == true): ?>
											                                        	<a href="javascript:;" class="text-info modify-purchase-payment" data-purchase-payment-id="<?php echo $row2->purchase_payment_id; ?>" title="Modify Payment"><i class="icon-pencil6"></i></a>
											                                        	<a href="javascript:;" class="text-danger void-purchase-payment" data-purchase-payment-id="<?php echo $row2->purchase_payment_id; ?>" title="Void Payment"><i class="icon-trash"></i></a>
											                                        <?php endif; ?>
											                                    <?php endif; ?>
									                                        </td>
									                                    </tr>
									                                    <?php $count++; ?>
									                                <?php endforeach; ?>
								                                    <tr class="font-weight-bold">
								                                        <td colspan="3">TOTAL</td>
								                                        <td><?php echo number_format($total_paid,2); ?></td>
								                                        <td></td>
								                                        <td></td>
								                                    </tr>
								                                </tbody>
								                            </table>
								                        </div>
								                    <?php endif; ?>
							                    </div>

							                </div>							                
							            </div>
							        </div>

									<div class="row mt-4">
							            <div class="col-md-12">
							                <?php if ($row->purchase_order_note != ''): ?>
								            	<h6 class="text-uppercase font-weight-light mt-4">Comments</h6>
								            	<p><?php echo nl2br($row->purchase_order_note); ?></p>
								            <?php endif; ?>
							            </div>
							        </div>	

							        <?php if ($row->is_void == 1): ?>
								        <div class="row mt-2">
								            <div class="col-md-12">
								            	<div class="alert alert-warning bg-light">
								            		<h6 class="font-weight-bold"><i class="icon-info22 mr-1"></i>Void Information</h6>
								            		<p>
								            			<b>Void Reason:</b> <?php echo $row->void_reason; ?><br>
								            			<b>Voided By:</b> <?php echo $row->void_first_name . ' ' . $row->void_last_name; ?><br>
								            			<b>Void Date:</b> <?php echo $row->void_date; ?><br>
								            		</p>
								            	</div>
								            </div>
								        </div>
								    <?php endif; ?>							

									<div class="card-footer text-center">
										<span>Created by: <b><?php echo $row->first_name . ' ' . $row->last_name; ?></b> | Trnx Time: <b><?php echo date('d M, Y H:i:s', strtotime($row->created_on)); ?></b></span>
									</div>
								</div>
							</div>
							<div class="col-md-1"></div>
						</div>
					</div>
				<?php endforeach; ?>

				<div class="modal fade in" id="modal_send_purchase_order_via_email">
				    <div class="modal-dialog modal-lg">
				        <div class="modal-content">
				            <div class="modal-header header-custom">
				                <h4 class="modal-title text-center"><em class="icon ni ni-mail mr-1"></em>Send Purchase Order via Email</h4>
				                <button type="button" class="close" data-dismiss="modal">&times;</button>
				            </div>
				            <form id="frm_send_purchase_order_via_email" name="frm_send_purchase_order_via_email" method="post" class="is-alter" onsubmit="return submit_send_purchase_order_via_email();">
				                <div class="modal-body">
				                    <div class="spinner2 display-none" id="send_purchase_order_via_email_loader">
				                        <div class="rect1"></div>
				                        <div class="rect2"></div>
				                        <div class="rect3"></div>
				                    </div>
				                    <div class="row">
				                        <div class="col-md-12">
				                            <div>

				                                <input type="hidden" id="send_email_purchase_order_id" name="purchase_order_id">

				                                <div class="col-md-12">
				                                    <div>
				                                        <div>
				                                            <div class="row">
				                                                <div class="col-md-12">
				                                                    <div class="form-group">
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
				                                            <div class="row  display-none">
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
				                                                        <input type="number" class="form-control" id="mail_server_port" name="mail_server_port" placeholder="">
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
				                    <button type="submit" id="btn_send_pos_purchase_order_via_email" class="btn btn-success"><em class="icon ni ni-send mr-1"></em>Send Email</button>
				                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
				                </div>
				            </form>
				        </div>
				    </div>
				</div>

				<div class="modal fade" tabindex="-1" id="modal_void_purchase_order">
				    <div class="modal-dialog" role="document">
				        <div class="modal-content">
				            <div class="modal-header">
				                <h4 class="modal-title"><i class="icon ni ni-trash-alt"></i> Void Purchase Order [<span id="void_purchase_order_number"></span>]</h4>
				                <button type="button" class="close" data-dismiss="modal">&times;</button>
				            </div>
				            <div class="modal-body">
				                <div class="spinner display-none" id="void_purchase_order_loader">
				                    <div class="rect1"></div>
				                    <div class="rect2"></div>
				                    <div class="rect3"></div>
				                </div>
				                <form id="frm_void_purchase_order" name="frm_void_purchase_order" method="post" class="is-alter" onsubmit="return submit_void_purchase_order();">

				                    <input type="hidden" id="void_purchase_order_id" name="purchase_order_id">
				                    <input type="hidden" id="void_context" name="context">

				                    <div class="row">
				                        <div class="col-md-12">
				                            <div class="">
				                                <div class="">
				                                    <div class="row mt-1">
				                                        <div class="col-md-12">
				                                            <div class="form-group">
				                                                <textarea name="void_reason" cols="40" rows="5" id="purchase_order_void_reason" class="form-control" placeholder="Void Reason"></textarea>
				                                            </div>
				                                        </div>
				                                        <div class="clearfix"></div>
				                                    </div>
				                                </div>
				                            </div>
				                        </div>
				                    </div>
				                    <div class="form-group text-right">
				                        <button type="submit" id="btn_void_purchase_order" class="btn btn-primary"><i class="icon-checkmark4 mr-1"></i>Submit</button>
				                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-cancel-circle2 mr-1"></i>Cancel</button>
				                    </div>
				                </form>
				            </div>
				        </div>
				    </div>
				</div>

				<div class="modal fade in" id="modal_purchase_order_payment">
				    <div class="modal-dialog modal-lg">
				        <div class="modal-content">
				            <div class="modal-header header-custom">
				                <h5 class="modal-title font-weight-bold"><em class="icon-coins mr-1"></em>Record Purchase Order Payment <span id="payment_header_purchase_order_number"></span></h5>
				                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
				            </div>
				            <form id="frm_record_purchase_order_payment" name="frm_record_purchase_order_payment" method="post" class="is-alter" onsubmit="return submit_purchase_order_payment();">
				                <div class="modal-body">
				                    <div class="spinner display-none" id="record_purchase_order_payment_loader">
				                        <div class="rect1"></div>
				                        <div class="rect2"></div>
				                        <div class="rect3"></div>
				                    </div>
				                    <div class="row">
				                        <div class="col-md-7">
				                            <div class="card">
				                            	<div class="card-body">
					                                <input type="hidden" id="payment_purchase_order_id" name="purchase_order_id">
					                                <input type="hidden" id="payment_purchase_order_number" name="purchase_order_number">
					                                <input type="hidden" id="payment_context" name="context">

					                                <div class="col-md-12 payments_div">
			                                            <div class="row">
			                                                <div class="col-md-6">
			                                                    <div class="form-group mb-2">
			                                                        <label for="purchase_order_payment_method">Payment Method</label>
			                                                        <select class="form-control select-basic" data-placeholder="Select Paymnent Method"  id="purchase_order_payment_method" name="payment_method">
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
			                                                    <div class="form-group mb-2">
			                                                        <label for="purchase_order_payment_amount">Amount</label>
			                                                        <input type="number" class="form-control" id="purchase_order_payment_amount" name="payment_amount" step="any" min="0">
			                                                    </div>
			                                                </div>
			                                            </div>
			                                            <div class="row" id="div_purchase_order_payment_reference_number">
			                                                <div class="col-md-12">
			                                                    <div class="form-group mb-2">
			                                                        <label id="lbl_payment_reference_number" for="purchase_order_payment_reference_number">Reference #</label>
			                                                        <input type="text" class="form-control" id="purchase_order_payment_reference_number" name="reference_number" placeholder="">
			                                                    </div>
			                                                </div>
			                                            </div>
			                                            <div class="row">
			                                                <div class="col-md-12">
			                                                    <div class="form-group mb-2">
			                                                        <label for="payment_note_1">Payment Note</label>
			                                                        <textarea type="text" class="form-control" id="purchase_order_payment_note" name="payment_note" rows="1" placeholder=""></textarea>
			                                                    </div>
			                                                </div>

			                                                <div class="clearfix"></div>
			                                            </div>			                                        
					                                </div>
					                            </div>
				                            </div>
				                        </div>

				                        <div class="col-md-5">
				                            <div class="row">
				                                <div class="col-md-12">
				                                    <div class="box box-solid bg-light">
				                                        <div class="box-body p-4">
				                                            <div class="row pb-1 border-bottom-grey">
				                                                <div class="col-6 col-md-6">
				                                                    <span class="text-center lead">Subtotal:</span>
				                                                </div>
				                                                <div class="col-6 col-md-6">
				                                                	<span class="text-right lead font-weight-bold" id="div_subtotal">KES 0.00</span>
				                                                </div>
				                                            </div>
				                                            <div class="row pt-1 pb-1 border-bottom-grey">
				                                                <div class="col-6 col-md-6">
				                                                    <span class="text-center lead">Discount:</span>
				                                                </div>
				                                                <div class="col-6 col-md-6">
				                                                    <span class="text-right lead font-weight-bold" id="div_overall_discount">KES 0.00</span>
				                                                </div>
				                                            </div>
				                                            <div class="row pt-1 pb-1 border-bottom-grey">
				                                                <div class="col-6 col-md-6">
				                                                    <span class="text-center lead">Freight Cost:</span>
				                                                </div>
				                                                <div class="col-6 col-md-6">
				                                                    <span class="text-right lead font-weight-bold" id="div_freight_cost">KES 0.00</span>
				                                                </div>
				                                            </div>
				                                            <div class="row pt-1 pb-1 border-bottom-grey bg-danger">
				                                                <div class="col-6 col-md-6">
				                                                    <span class="text-center lead">Total Payable:</span>
				                                                </div>
				                                                <div class="col-6 col-md-6">
				                                                    <span class="text-right lead font-weight-bold" id="div_total_amount">KES 0.00</span>
				                                                </div>
				                                            </div>
				                                            <div class="row pt-1 pb-1 border-bottom-grey">
				                                                <div class="col-6 col-md-6">
				                                                    <span class="text-right lead">Total Paid:</span>
				                                                </div>
				                                                <div class="col-6 col-md-6">
				                                                    <span class="text-right lead font-weight-bold" id="div_total_paid">KES 0.00</span>
				                                                </div>
				                                            </div>
				                                            <div class="row pt-1 bg-orange">
				                                                <div class="col-6 col-md-6">
				                                                    <span class="text-right lead">Balance:</span>
				                                                </div>
				                                                <div class="col-6 col-md-6">
				                                                    <span class="text-right lead font-weight-bold" id="div_payment_balance">KES 0.00</span>
				                                                </div>
				                                            </div>
				                                            <!-- <div class="row">
				                                                <div class="col-md-12 bg-orange pt-1 pb-1">
				                                                    <span class="col-md-6 text-right text-bold">Change:</span>
				                                                    <span class="col-md-6 text-right text-bold custom-font-size" id="div_change">KES 0.00</span>
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
				                    <button type="submit" id="btn_submit_purchase_order_payment" class="btn btn-primary"><i class="icon-checkmark-circle2 mr-1"></i>Submit Payment</button>
				                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-cancel-circle2 mr-1"></i>Cancel</button>
				                </div>
				            </form>
				        </div>
				    </div>
				</div>

				<div class="modal fade" tabindex="-1" id="modal_void_purchase_payment">
				    <div class="modal-dialog" role="document">
				        <div class="modal-content">
				            <div class="modal-header">
				                <h5 class="modal-title font-weight-bold"><i class="icon-trash"></i> Void Purchase Payment</h5>
				                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
				            </div>
				            <div class="modal-body">
				                <div class="spinner display-none" id="void_purchase_payment_loader">
				                    <div class="rect1"></div>
				                    <div class="rect2"></div>
				                    <div class="rect3"></div>
				                </div>
				                <form id="frm_void_purchase_payment" name="frm_void_purchase_payment" method="post" class="is-alter" onsubmit="return submit_void_purchase_payment();">

				                    <input type="hidden" id="void_purchase_payment_id" name="purchase_payment_id">

				                    <div class="row">
				                        <div class="col-md-12">
				                            <div class="card bg-lighter">
				                                <div class="card-body">
				                                    <div class="row">
				                                        <div class="col-md-12">
				                                            <div class="form-group mb-0">
				                                                <textarea name="void_reason" cols="40" rows="5" id="void_reason" class="form-control" placeholder="Void Reason"></textarea>
				                                            </div>
				                                        </div>
				                                        <div class="clearfix"></div>
				                                    </div>
				                                </div>
				                            </div>
				                        </div>
				                    </div>
				                    <div class="form-group text-right">
				                        <button type="submit" id="btn_void_purchase_payment" class="btn btn-primary"><i class="icon-checkmark-circle mr-1"></i>Submit</button>
				                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-cancel-circle2 mr-1"></i>Cancel</button>
				                    </div>
				                </form>
				            </div>
				        </div>
				    </div>
				</div>


				<div class="modal fade in" id="modal_modify_purchase_payment">
				    <div class="modal-dialog">
				        <div class="modal-content">
				            <div class="modal-header header-custom">
				                <h5 class="modal-title font-weight-bold"><em class="icon-pencil6 mr-1"></em>Modify Purchase Payment <span id="payment_header_purchase_order_number"></span></h5>
				                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
				            </div>
				            <form id="frm_modify_purchase_payment" name="frm_modify_purchase_payment" method="post" class="is-alter" onsubmit="return submit_modify_purchase_payment();">
				                <div class="modal-body">
				                    <div class="spinner display-none" id="sale_make_payment_loader">
				                        <div class="rect1"></div>
				                        <div class="rect2"></div>
				                        <div class="rect3"></div>
				                    </div>
				                    <div class="row">
				                        <div class="col-md-12">
				                            <div>

				                                <input type="hidden" id="modify_purchase_payment_id" name="purchase_payment_id">
				                                <input type="hidden" id="modify_payment_purchase_order_id" name="purchase_order_id">
				                                <input type="hidden" id="modify_payment_purchase_order_number" name="purchase_order_number">
				                                <input type="hidden" id="modify_txt_payment_method" name="txt_payment_method">

				                                <div class="col-md-12 payments_div">
				                                    <div class="card">
				                                        <div class="card-body">
				                                            <div class="row">
				                                                <div class="col-md-6">
				                                                    <div class="form-group mb-0">
				                                                        <label for="purchase_payment_method">Payment Method</label>
				                                                        <select class="form-control select-basic" data-placeholder="Select Payment Method"  id="modify_purchase_payment_method" name="payment_method">
				                                                            <option value="">Select Payment Method</option>
				                                                            <option value="Cash">Cash</option>
				                                                            <option value="MPESA">MPESA</option>
				                                                            <option value="Cheque">Cheque</option>
				                                                            <option value="CashApp">CashApp</option>
				                                                            <option value="Wave">Wave</option>
				                                                        </select>
				                                                    </div>
				                                                </div>
				                                                <div class="col-md-6 mb-0">
				                                                    <div class="form-group">
				                                                        <label for="modify_purchase_payment_amount">Amount</label>
				                                                        <input type="number" class="form-control" id="modify_purchase_payment_amount" name="payment_amount" step="any" min="0">
				                                                    </div>
				                                                </div>
				                                            </div>
				                                            <div class="row" id="div_purchase_payment_reference_number">
				                                                <div class="col-md-12 mb-0">
				                                                    <div class="form-group">
				                                                        <label id="lbl_modify_payment_reference_number" for="modify_purchase_payment_reference_number">Reference #</label>
				                                                        <input type="text" class="form-control" id="modify_purchase_payment_reference_number" name="reference_number" placeholder="">
				                                                    </div>
				                                                </div>
				                                            </div>
				                                            <div class="row">
				                                                <div class="col-md-12 mb-0">
				                                                    <div class="form-group">
				                                                        <label for="modify_purchase_payment_note">Payment Note</label>
				                                                        <textarea type="text" class="form-control" id="modify_purchase_payment_note" name="payment_note" placeholder=""></textarea>
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
				                    <button type="submit" id="btn_submit_modify_purchase_payment" class="btn btn-primary"><i class="icon-checkmark-circle2 mr-1"></i>Update</button>
				                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-cancel-circle2 mr-1"></i>Cancel</button>
				                </div>
				            </form>
				        </div>
				    </div>
				</div>