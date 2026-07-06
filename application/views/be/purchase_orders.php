		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="#" class="breadcrumb-item">Inventory</a>
							<span class="breadcrumb-item active">Purchase Orders</span>
						</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content pt-0">
				<div class="row">
					<div class="col-md-12">
						<div class="card rounded-top-0">
							<div class="spinner2 display-none" id="purchase_orders_loader">
		                        <div class="rect1"></div>
		                        <div class="rect2"></div>
		                        <div class="rect3"></div>
		                    </div>
							<div class="card-header bg-transparent header-elements-inline p-2">
								<h5 class="card-title font-weight-bold"><i class="icon-list3 mr-1"></i> Purchase Orders</h5>			
								<div class="header-elements">
									<?php if ($sbr_purchase_orders_add == true): ?>
										<a href="<?php echo base_url(); ?>be/inventory/purchase_order_new" class="btn btn-sm btn-primary"><i class="icon-plus-circle2"></i> New Purchase Order</a>
									<?php endif; ?>
								</div>			
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<form method="post" class="form" id="frm_filter_purchase_orders" name="frm_filter_purchase_orders" method="post">
											<!-- <div class="card card-body bg-light p-2"> -->
											<div class="row">
												<div class="col-sm-2 font-weight-600">
													<div class="form-group mb-2">
														<input type="hidden" id="date_from" name="date_from" value="">
														<input type="hidden" id="date_to" name="date_to" value="">
														<div class="input-group">
															<input type="text" id="date_from_to" class="form-control daterangepicker" value="2013-01-08 - 2013-01-08">
															<span class="input-group-append">
																<span class="input-group-text"><i class="icon-calendar22"></i></span>
															</span>
														</div>
													</div>
												</div>
												<div class="col-sm-2 font-weight-600">
													<div class="form-group mb-2">
														<select id="purchase_order_status" name="purchase_order_status" class="form-control select" data-placeholder="Filter by Status" data-fouc>
															<option value="">Filter by Status</option>
															<option value="Active">Active (Unreceived, Partially Received & Received)</option>
															<option value="Unreceived">Unreceived</option>
	                                                        <option value="Partially Received">Partially Received</option>
	                                                        <option value="Received">Received</option>
	                                                        <option value="Void">Void</option>
														</select>
													</div>
												</div>
												<div class="col-sm-2 font-weight-600">
													<div class="form-group mb-2">
														<select id="payment_status" name="payment_status" class="form-control select" data-placeholder="Filter by Payment Status" data-fouc>
															<option value="">Filter by Payment Status</option>
															<option value="Unpaid">Unpaid</option>
	                                                        <option value="Partially Paid">Partially Paid</option>
	                                                        <option value="Paid">Paid</option>
														</select>
													</div>
												</div>
												<div class="col-sm-2 font-weight-600">
													<div class="form-group mb-2">
														<select id="supplier_id" name="supplier_id" class="form-control select" data-placeholder="Filter by Supplier" data-fouc>
															<option value="">Filter by Supplier</option>
															<?php foreach ($suppliers as $row): ?>
																<option value="<?php echo $row->supplier_id; ?>"><?php echo $row->supplier_name; ?></option>
															<?php endforeach; ?>																
														</select>
													</div>
												</div>
												<div class="col-sm-2 font-weight-600">
													<div class="form-group mb-2">
														<select id="system_user_id" name="system_user_id" class="form-control select" data-placeholder="Filter by User" data-fouc>
															<option value="">Filter by User</option>
															<?php foreach ($system_users as $row): ?>
																<option value="<?php echo $row->system_user_id; ?>"><?php echo $row->first_name . ' ' . $row->last_name . ' (' . $row->email_address . ')'; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
												<div class="col-sm-2">
													<button id="btn_purchase_orders_filter" type="button" onclick="filter_purchase_orders();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> FILTER</button>
												</div>
											</div>
											<!-- </div> -->
											<div class="row">
												<div class="col-md-12 text-right">
													<button id="btn_purchases_report_export" type="button" onclick="export_purchases_report();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-file-pdf"></i> Export</button>
												</div>
											</div>
											<div id="purchase_orders_div" style="min-height: 400px;">
									
											</div>
										</form>
									</div>
								</div>
							</div>


							<!-- <div id="purchase_orders_div" style="min-height: 400px;">
								
							</div> -->
						</div>
					</div>
				</div>
			</div>

			<div class="modal fade in" id="modal_send_purchase_order_via_email">
			    <div class="modal-dialog modal-lg">
			        <div class="modal-content">
			            <div class="modal-header header-custom">
			                <h4 class="modal-title text-center"><em class="icon ni ni-mail mr-1"></em>Send Purchase Orderdd via Email</h4>
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
			                        <button type="submit" id="btn_void_purchase_order" class="btn btn-success"><i class="icon-checkmark4 mr-1"></i>Submit</button>
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

			<script type="text/javascript">
				$(document).ready(function() {
					$('.daterangepicker').daterangepicker({
				            startDate: moment().subtract(29, 'days'),
				            endDate: moment(),
				            ranges: {
				                'Today': [moment(), moment()],
				                'Yesterday': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
				                'Last 7 Days': [moment().subtract(6, 'days'), moment()],
				                'Last 30 Days': [moment().subtract(29, 'days'), moment()],
				                'This Month': [moment().startOf('month'), moment().endOf('month')],
				                'Last Month': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
				            },
				            applyClass: 'btn-sm btn-primary',
				            cancelClass: 'btn-sm btn-danger'
				        },
				        function(start, end) {
				        	assign_dates();
				        }
				    );
				    assign_dates();

				    filter_purchase_orders();
				});
				function assign_dates() {
					$('#date_from').val($('#date_from_to').data('daterangepicker').startDate.format('YYYY-MM-DD'));
				   	$('#date_to').val($('#date_from_to').data('daterangepicker').endDate.format('YYYY-MM-DD'));					
				}
				// filter_purchase_orders();
			</script>


	