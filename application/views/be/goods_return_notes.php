		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="#" class="breadcrumb-item">Inventory</a>
							<span class="breadcrumb-item active">Goods Return Notes</span>
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
							<div class="spinner2 display-none" id="goods_return_notes_loader">
		                        <div class="rect1"></div>
		                        <div class="rect2"></div>
		                        <div class="rect3"></div>
		                    </div>
							<div class="card-header bg-transparent header-elements-inline p-2">
								<h5 class="card-title font-weight-bold"><i class="icon-list3 mr-1"></i> Goods Return Notes</h5>			
								<div class="header-elements">
									<?php if ($sbr_goods_returned_add == true): ?>
										<a href="<?php echo base_url(); ?>be/inventory/return" class="btn btn-sm btn-primary"><i class="icon-plus-circle2"></i> Return Stock</a>
									<?php endif; ?>
								</div>			
							</div>

							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<form method="post" class="form" id="frm_filter_goods_return_notes" name="frm_filter_goods_return_notes" method="post">
											<div class="">
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
															<select id="status" name="status" class="form-control select" data-placeholder="Filter by Status" data-fouc>
																<option value="">Filter by Status</option>
																<option value="Active">Active</option>
		                                                        <option value="Void">Void</option>
															</select>
														</div>
													</div>
													<div class="col-sm-2 font-weight-600">
														<div class="form-group mb-2">
															<select id="outlet_id" name="outlet_id" class="form-control select" data-placeholder="Filter by Outlet" data-fouc>
																<option value="">Filter by Outlet</option>
																<?php foreach ($outlets as $row): ?>
																	<option value="<?php echo $row->outlet_id; ?>"><?php echo $row->outlet_name; ?></option>
																<?php endforeach; ?>
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
														<button id="btn_goods_return_notes_filter" type="button" onclick="filter_goods_return_notes();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> FILTER</button>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12 text-right">
													<button id="btn_export_goods_return_notes" type="button" onclick="export_goods_return_notes();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-file-pdf"></i> Export</button>
												</div>
											</div>
											<div id="goods_return_notes_div" style="min-height: 400px;">
									
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="modal fade in" id="modal_send_goods_return_note_via_email">
			    <div class="modal-dialog modal-lg">
			        <div class="modal-content">
			            <div class="modal-header header-custom">
			                <h4 class="modal-title text-center"><em class="icon ni ni-mail mr-1"></em>Send Goods Return Note via Email</h4>
			                <button type="button" class="close" data-dismiss="modal">&times;</button>
			            </div>
			            <form id="frm_send_goods_return_note_via_email" name="frm_send_goods_return_note_via_email" method="post" class="is-alter" onsubmit="return submit_send_goods_return_note_via_email();">
			                <div class="modal-body">
			                    <div class="spinner2 display-none" id="send_goods_return_note_via_email_loader">
			                        <div class="rect1"></div>
			                        <div class="rect2"></div>
			                        <div class="rect3"></div>
			                    </div>
			                    <div class="row">
			                        <div class="col-md-12">
			                            <div>

			                                <input type="hidden" id="send_email_goods_return_note_id" name="goods_return_note_id">

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
			                    <button type="submit" id="btn_send_goods_return_note_via_email" class="btn btn-success"><em class="icon ni ni-send mr-1"></em>Send Email</button>
			                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
			                </div>
			            </form>
			        </div>
			    </div>
			</div>

			<div class="modal fade" tabindex="-1" id="modal_void_goods_return_note">
			    <div class="modal-dialog" role="document">
			        <div class="modal-content">
			            <div class="modal-header">
			                <h4 class="modal-title font-weight-bold"><i class="icon ni ni-trash-alt"></i> Void Goods Return Note [<span id="void_goods_return_note_number"></span>]</h4>
			                <button type="button" class="close" data-dismiss="modal">&times;</button>
			            </div>
			            <div class="modal-body">
			                <div class="spinner display-none" id="void_goods_return_note_loader">
			                    <div class="rect1"></div>
			                    <div class="rect2"></div>
			                    <div class="rect3"></div>
			                </div>
			                <form id="frm_void_goods_return_note" name="frm_void_goods_return_note" method="post" class="is-alter" onsubmit="return submit_void_goods_return_note();">

			                    <input type="hidden" id="void_goods_return_note_id" name="goods_return_note_id">
			                    <input type="hidden" id="void_context" name="context">

			                    <div class="row">
			                        <div class="col-md-12">
			                            <div class="">
			                                <div class="">
			                                    <div class="row mt-1">
			                                        <div class="col-md-12">
			                                            <div class="form-group">
			                                                <textarea name="void_reason" cols="40" rows="5" id="goods_return_note_void_reason" class="form-control" placeholder="Void Reason"></textarea>
			                                            </div>
			                                        </div>
			                                        <div class="clearfix"></div>
			                                    </div>
			                                </div>
			                            </div>
			                        </div>
			                    </div>
			                    <div class="form-group text-right">
			                        <button type="submit" id="btn_void_goods_return_note" class="btn btn-primary"><i class="icon-checkmark4 mr-1"></i>Submit</button>
			                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-cancel-circle2 mr-1"></i>Cancel</button>
			                    </div>
			                </form>
			            </div>
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

				    filter_goods_return_notes();
				});
				function assign_dates() {
					$('#date_from').val($('#date_from_to').data('daterangepicker').startDate.format('YYYY-MM-DD'));
				   	$('#date_to').val($('#date_from_to').data('daterangepicker').endDate.format('YYYY-MM-DD'));					
				}
				// filter_goods_return_notes();
			</script>