		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="#" class="breadcrumb-item">Sales</a>
							<span class="breadcrumb-item active">Online Sales</span>
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
							<div class="card-header bg-transparent header-elements-inline p-2">
								<h5 class="card-title font-weight-bold"><i class="icon-cart2 mr-1"></i>  Online Sales</h5>			
							</div>

							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<form method="post" class="form" id="frm_filter_online_sales" name="frm_filter_online_sales" method="post">
											<div class="">
												<div class="row">
													<div class="col-sm-2 font-weight-600">
														<div class="form-group mb-2">
															<select id="online_sale_status" name="online_sale_status" class="form-control select" data-placeholder="Filter by Status" data-fouc>
																<option value="">Filter by Status</option>
																<option value="0">Awaiting Payment</option>
		                                                        <option value="1">Processing</option>
		                                                        <option value="2">Dispatched</option>
		                                                        <option value="3">Completed</option>
		                                                        <option value="4">Cancelled</option>
															</select>
														</div>
													</div>
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
													<div class="col-sm-2">
														<button id="btn_online_sales_filter" type="button" onclick="filter_online_sales();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> FILTER</button>
													</div>
												</div>
											</div>
											<div id="online_sales_div" style="min-height: 400px;">
									
											</div>
										</form>
									</div>
								</div>
							</div>



						</div>
					</div>
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
			                                                        <label for="spo_email_account_id">Sender Email Account</label>
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

			<div class="modal fade in" id="modal_dispatch_online_order">
			    <div class="modal-dialog">
			        <div class="modal-content">
			            <div class="modal-header header-custom">
			                <h4 class="modal-title text-center"><i class="icon-cart-remove"></i> Dispatch Order [<span id="spn_dispatch_order_number"></span>]</h4>
			                <button type="button" class="close" data-dismiss="modal">&times;</button>
			            </div>
			            <form id="frm_dispatch_online_order" name="frm_dispatch_online_order" method="post" class="is-alter" onsubmit="return submit_dispatch_online_order('List');">
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
			                                <input type="hidden" id="send_customer_email_customer_id" name="customer_id">

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
			                                                        <input type="text" class="form-control" id="customer_sender_name" name="sender_name" placeholder="">
			                                                    </div>
			                                                </div>
			                                                <div class="col-md-6">
			                                                    <div class="form-group mb-2">
			                                                        <label for="customer_sender_email_address">Sender Email Address<span class="text-danger">*</span></label>
			                                                        <input type="email" class="form-control" id="customer_sender_email_address" name="sender_email_address" placeholder="">
			                                                    </div>
			                                                </div>
			                                            </div>
			                                            <div class="row">
			                                                <div class="col-md-6">
			                                                    <div class="form-group mb-2">
			                                                        <label for="customer_mail_server_name">Mail Server (SMTP)<span class="text-danger">*</span></label>
			                                                        <input type="text" class="form-control" id="customer_mail_server_name" name="mail_server_name" placeholder="">
			                                                    </div>
			                                                </div>
			                                                <div class="col-md-3">
			                                                    <div class="form-group mb-2">
			                                                        <label for="customer_mail_server_port">Mail Server Port<span class="text-danger">*</span></label>
			                                                        <input type="number" class="form-control" id="customer_mail_server_port" name="mail_server_port" placeholder="">
			                                                    </div>
			                                                </div>
			                                                <div class="col-md-3">
			                                                    <div class="form-group mb-2">
			                                                        <label for="">&nbsp;</label><br>
			                                                        <div class="custom-control custom-checkbox">
			                                                          <input type="checkbox" class="custom-control-input" id="customer_chk_use_ssl" name="chk_use_ssl" />
			                                                          <label class="custom-control-label" for="customer_chk_use_ssl">Use SSL</label>
			                                                        </div>
			                                                    </div>
			                                                </div>
			                                            </div>
			                                            <div class="row">
			                                                <div class="col-md-6">
			                                                    <div class="form-group mb-2">
			                                                        <label for="customer_sender_username">Username<span class="text-danger">*</span></label>
			                                                        <input type="email" class="form-control" id="customer_sender_username" name="sender_username" placeholder="">
			                                                    </div>
			                                                </div>
			                                                <div class="col-md-6">
			                                                    <div class="form-group mb-2">
			                                                        <label for="customer_sender_password">Password<span class="text-danger">*</span></label>
			                                                        <input type="password" class="form-control" id="customer_sender_password" name="sender_password" placeholder="">
			                                                    </div>
			                                                </div>
			                                            </div>
			                                            <div class="row">
			                                                <div class="col-md-12">
			                                                    <div class="form-group mb-2">
			                                                        <label for="customer_recipient_email_address">Recipient Email Address<span class="text-danger">*</span></label>
			                                                        <input type="email" class="form-control" id="customer_recipient_email_address" name="recipient_email_address" placeholder="">
			                                                    </div>
			                                                </div>
			                                            </div>
			                                            <div class="row">
			                                                <div class="col-md-12">
			                                                    <div class="form-group mb-2">
			                                                        <label for="sooc_email_template_id">Sender Email Template</label>
			                                                        <select class="form-control form-control-select2" data-placeholder="Select Email Template" id="sooc_email_template_id" name="email_template_id">
			                                                            <option value="">Select Email Template</option>
			                                                            <?php foreach ($email_templates as $row2): ?>
			                                                                <option value="<?php echo $row2->email_template_id; ?>"><?php echo $row2->email_template_name; ?></option>
			                                                            <?php endforeach; ?>
			                                                            
			                                                        </select>
			                                                    </div>
			                                                </div>
			                                            </div>
			                                            <div class="row">
			                                                <div class="col-md-12">
			                                                    <div class="form-group mb-2">
			                                                        <label for="customer_email_subject">Subject<span class="text-danger">*</span></label>
			                                                        <input type="text" class="form-control" id="customer_email_subject" name="email_subject" placeholder="">
			                                                    </div>
			                                                </div>
			                                            </div>
			                                            <div class="row">
			                                                <div class="col-md-12">
			                                                    <div class="form-group mb-2">
			                                                        <label for="customer_email_message">Message<span class="text-danger">*</span></label>
			                                                        <textarea type="text" class="form-control ckeditor" id="customer_email_message" name="email_message" rows="6" placeholder=""></textarea>
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

			<script type="text/javascript">
				CKEDITOR.replace( 'customer_email_message', {
                    height: 150,
                    toolbar: [
						{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo', 'Styles', 'Format', 'Font', 'FontSize', 'NumberedList', 'BulletedList' ] }
					]
                });
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

				    filter_online_sales();
				});
				function assign_dates() {
					$('#date_from').val($('#date_from_to').data('daterangepicker').startDate.format('YYYY-MM-DD'));
				   	$('#date_to').val($('#date_from_to').data('daterangepicker').endDate.format('YYYY-MM-DD'));					
				}
			</script>

		
