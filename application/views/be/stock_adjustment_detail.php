		<!-- Main content -->
		<div class="content-wrapper">

				<?php foreach ($stock_adjustment as $row): ?>
					<!-- Page header -->
					<div class="page-header">
						<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
							<div class="d-flex">
								<div class="breadcrumb">
									<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
									<a href="#" class="breadcrumb-item">Inventory</a>
									<a href="<?php echo base_url();?>be/inventory/stock_adjustments" class="breadcrumb-item">Stock Adjustments</a>
									<span class="breadcrumb-item active">View Stock Adjustment (<?php echo $row->stock_adjustment_number; ?>)</span>
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
								<h4 class="text-primary font-weight-bold"><i class="icon-eye"></i> View Stock Adjustment (<?php echo $row->stock_adjustment_number; ?>)</h4>
							</div>
							<div class="col-md-6 text-right">
								<a href="<?php echo base_url(); ?>be/inventory/stock_adjustments" class="btn btn-primary btn-sm"><i class="icon-arrow-left15"></i><span>Stock Adjustments List</span></a>
							</div>
						</div>
						<div class="row">
							<div class="col-md-2"></div>
							<div class="col-md-8">
								<div class="invoice card card-preview p-3">
									<div class="spinner2 display-none" id="stock_adjustments_loader">
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
														<?php if ($sbr_stock_adjustments_edit == true): ?>
															<a href="<?php echo base_url(); ?>be/inventory/stock_adjustment_edit/<?php echo $row->stock_adjustment_id; ?>" class="dropdown-item"><i class="icon-pencil6"></i> Edit Stock Adjustment</a>
															<div class="dropdown-divider"></div>
														<?php endif; ?>
													<?php endif; ?>
													<?php if ($sbr_stock_adjustments_print == true): ?>
														<a href="<?php echo base_url(); ?>be/inventory/stock_adjustment_print/<?php echo $row->stock_adjustment_id; ?>" class="dropdown-item" target="_blank"><i class="icon-printer"></i> Print Stock Adjustment</a>
													<?php endif; ?>
													<!-- <a href="<?php echo base_url(); ?>be/inventory/stock_adjustment_pdf/<?php echo $row->stock_adjustment_id; ?>" class="dropdown-item" target="_blank"><i class="icon-file-pdf"></i> Export to PDF</a> -->
													<a href="#" data-toggle="modal" data-target="#modal_send_stock_adjustment_via_email" data-stock-adjustment-id="<?php echo $row->stock_adjustment_id; ?>" class="dropdown-item lnk_send_stock_adjustment_via_email"><i class="icon-mail5"></i> Send via Mail</a>
													<?php if ($row->is_void == 0): ?>
														<?php if ($sbr_stock_adjustments_delete == true): ?>
															<a href="javascript:;" class="dropdown-item void-stock-adjustment" data-stock-adjustment-id="<?php echo $row->stock_adjustment_id; ?>" data-context="View Stock Adjustment"><i class="icon-trash-alt"></i> Void Stock Adjustment</a>
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
												<div class="mb-4">
													<div class="text-sm-right">
														<h2 class="text-uppercase font-weight-light mb-0">Stock Ajustment</h2>
								            			<p class="lead text-info"><?php echo $row->stock_adjustment_number; ?></p>

								            			<p class="mb-3">
										                	<b>Adjustment Date:</b> <?php echo date('d M, Y', strtotime($row->adjustment_date)); ?><br />
								            				<b>Outlet:</b> <?php echo $row->outlet_name; ?><br />															
															<b>Adjusted By:</b> <?php echo $row->first_name . ' ' . $row->last_name; ?><br />
															<b>Status:</b> 
										                	<?php if ($row->is_void == 1): ?>
				                                                <span class="badge badge-danger badge-pill mt-1 font-11">Voided</span>
				                                            <?php else: ?>
																<span class="badge badge-success badge-pill mt-1 font-11">Active</span>
				                                            <?php endif; ?>
											            </p>
													</div>
												</div>
											</div>
										</div>
									</div>

									<div class="table-responsive mb-3">
									    <table class="table table-striped table-bordered">
									        <thead>
									            <tr>
									                <th style="width: 300px">Product Name</th>
									                <th style="width: 100px">Current Quantity</th>
									                <th style="width: 100px">Adjusted Quantity</th>
									            </tr>
									        </thead>
									        <tbody>
									        	<?php foreach ($stock_adjustment_details as $row2): ?>
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
									                	<td><?php echo number_format($row2->current_quantity,2); ?></td>
										                <td><?php echo number_format($row2->adjusted_quantity,2); ?></td>
										            </tr>
										        <?php endforeach; ?>
									        </tbody>
									    </table>
									</div>

									<?php if ($row->remark != ''): ?>
										<div class="row mt-2 mb-2">
								            <div class="col-md-12">	
								            	<h6 class="text-uppercase font-weight-light mt-4">Remarks:</h6>
								            	<p><?php echo nl2br($row->remark); ?></p>
								            </div>
								        </div>
								    <?php endif; ?>
							        

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

				<div class="modal fade in" id="modal_send_stock_adjustment_via_email">
				    <div class="modal-dialog modal-lg">
				        <div class="modal-content">
				            <div class="modal-header header-custom">
				                <h4 class="modal-title text-center"><em class="icon ni ni-mail mr-1"></em>Send Stock Adjustment via Email</h4>
				                <button type="button" class="close" data-dismiss="modal">&times;</button>
				            </div>
				            <form id="frm_send_stock_adjustment_via_email" name="frm_send_stock_adjustment_via_email" method="post" class="is-alter" onsubmit="return submit_send_stock_adjustment_via_email();">
				                <div class="modal-body">
				                    <div class="spinner2 display-none" id="send_stock_adjustment_via_email_loader">
				                        <div class="rect1"></div>
				                        <div class="rect2"></div>
				                        <div class="rect3"></div>
				                    </div>
				                    <div class="row">
				                        <div class="col-md-12">
				                            <div>

				                                <input type="hidden" id="send_email_stock_adjustment_id" name="stock_adjustment_id">

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
				                    <button type="submit" id="btn_send_stock_adjustment_via_email" class="btn btn-success"><em class="icon ni ni-send mr-1"></em>Send Email</button>
				                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
				                </div>
				            </form>
				        </div>
				    </div>
				</div>

				<div class="modal fade" tabindex="-1" id="modal_void_stock_adjustment">
				    <div class="modal-dialog" role="document">
				        <div class="modal-content">
				            <div class="modal-header">
				                <h4 class="modal-title font-weight-bold"><i class="icon ni ni-trash-alt"></i> Stock Transfer [<span id="void_stock_adjustment_number"></span>]</h4>
				                <button type="button" class="close" data-dismiss="modal">&times;</button>
				            </div>
				            <div class="modal-body">
				                <div class="spinner display-none" id="void_stock_adjustment_loader">
				                    <div class="rect1"></div>
				                    <div class="rect2"></div>
				                    <div class="rect3"></div>
				                </div>
				                <form id="frm_void_stock_adjustment" name="frm_void_stock_adjustment" method="post" class="is-alter" onsubmit="return submit_void_stock_adjustment();">

				                    <input type="hidden" id="void_stock_adjustment_id" name="stock_adjustment_id">
				                    <input type="hidden" id="void_context" name="context">

				                    <div class="row">
				                        <div class="col-md-12">
				                            <div class="">
				                                <div class="">
				                                    <div class="row mt-1">
				                                        <div class="col-md-12">
				                                            <div class="form-group">
				                                                <textarea name="void_reason" cols="40" rows="5" id="stock_adjustment_void_reason" class="form-control" placeholder="Void Reason"></textarea>
				                                            </div>
				                                        </div>
				                                        <div class="clearfix"></div>
				                                    </div>
				                                </div>
				                            </div>
				                        </div>
				                    </div>
				                    <div class="form-group text-right">
				                        <button type="submit" id="btn_void_stock_adjustment" class="btn btn-primary"><i class="icon-checkmark4 mr-1"></i>Submit</button>
				                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-cancel-circle2 mr-1"></i>Cancel</button>
				                    </div>
				                </form>
				            </div>
				        </div>
				    </div>
				</div>
