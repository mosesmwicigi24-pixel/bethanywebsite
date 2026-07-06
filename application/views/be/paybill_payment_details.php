		<!-- Main content -->
		<div class="content-wrapper">

				<?php foreach ($paybill_payment as $row): ?>
					<!-- Page header -->
					<div class="page-header">
						<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
							<div class="d-flex">
								<div class="breadcrumb">
									<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
									<a href="#" class="breadcrumb-item">Payments</a>
									<a href="<?php echo base_url();?>be/payments/paybill" class="breadcrumb-item">Paybill</a>
									<span class="breadcrumb-item active">View Paybill Payment</span>
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
										<h5 class="card-title font-weight-bold">Paybill Payment Details</h5>
									</div>

									<div class="card-body">
										<div class="row">
											<div class="col-md-6">
												<div class="table-responsive">
									    			<table class="table table-bordered table-condensed" style="font-size:90%">
									    				<tr>
									    					<td><b>Reference #</b></td>
									    					<td><?php echo $row->transaction_id; ?></td>
									    				</tr>
									    				<tr>
									    					<td><b>Payment Date</b></td>
									    					<td><?php echo date('d M, Y H:i:s', strtotime($row->transaction_time)); ?></td>
									    				</tr>
									    				<tr>
									    					<td><b>Name</b></td>
									    					<td><?php echo $row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name; ?></td>
									    				</tr>
									    				<tr>
									    					<td><b>Phone No</b></td>
									    					<td><?php echo $row->MSISDN; ?></td>
									    				</tr>
									    				<tr>
									    					<td><b>Amount</b></td>
									    					<td><?php echo number_format((float)$row->transaction_amount, 2, '.', ','); ?></td>
									    				</tr>
									    			</table>
									    		</div>
											</div>
											<div class="col-md-6">
												<div class="table-responsive">
									    			<table class="table table-bordered table-condensed" style="font-size:90%">
									    				<tr>
									    					<td><b>Transaction Type</b></td>
									    					<td><?php echo $row->transaction_type; ?></td>
									    				</tr>
									    				<tr>
									    					<td><b>Business Number</b></td>
									    					<td><?php echo $row->business_short_code; ?></td>
									    				</tr>
									    				<tr>
									    					<td><b>Account #</b></td>
									    					<td><?php echo $row->bill_reference_number; ?></td>
									    				</tr>
									    				<tr>
									    					<td><b>Payment For</b></td>
									    					<td>
									    						<?php if ($row->ord_order_number != ''): ?>
			                                                        Online Order : <a href="<?php echo base_url(); ?>be/sales/online_order/<?php echo $row->ord_order_number; ?>"><b><?php echo $row->ord_order_number; ?></b></a>
			                                                    <?php elseif ($row->pos_sale_id != 0): ?>
			                                                        POS Order : <b>SO-<?php echo $row->pos_sale_id; ?></b>
			                                                    <?php endif; ?>
									    					</td>
									    				</tr>
									    				<tr>
									    					<td><b>Status</b></td>
									    					<td>
									    						<?php if ($row->transaction_completed == 1): ?>
			                                                        <span class="badge badge-success"><i class="icon-checkmark2"></i> Closed</span>
			                                                    <?php else: ?>
			                                                        <span class="badge badge-secondary">Pending</span>
			                                                    <?php endif; ?>
									    					</td>
									    				</tr>
									    			</table>
									    		</div>
											</div>
										</div>
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

				