		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="#" class="breadcrumb-item">Payments</a>
							<span class="breadcrumb-item active">Paybill Payments</span>
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
							<div class="spinner2 display-none" id="paybill_payments_loader">
		                        <div class="rect1"></div>
		                        <div class="rect2"></div>
		                        <div class="rect3"></div>
		                    </div>
							<div class="card-header bg-transparent header-elements-inline p-2">
								<h5 class="card-title font-weight-bold"><i class="icon-cash mr-1"></i> Paybill Payments</h5>			
							</div>

							<div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <form method="post" class="form" id="frm_filter_paybill_payments" name="frm_filter_paybill_payments" method="post">
                                            <div class="">
                                                <div class="row">
                                                    <div class="col-sm-2 font-weight-600">
                                                        <div class="form-group mb-2">
                                                            <select id="paybill_payment_status" name="paybill_payment_status" class="form-control select" data-placeholder="Filter by Status" data-fouc>
                                                                <option value="">Filter by Status</option>
                                                                <option value="0">Pending</option>
                                                                <option value="1">Closed</option>
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
                                                        <button id="btn_paybill_payments_filter" type="button" onclick="filter_paybill_payments();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> FILTER</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="paybill_payments_div" style="min-height: 400px;">
                                    
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>


						</div>
					</div>
				</div>
			</div>

			<div id="modal_assign_paybill_payment_transaction" class="modal fade in" role="dialog">
                <div class="modal-dialog">
                    <div class="modal-content">
                    	<div class="spinner2 display-none" id="assign_paybill_payment_transaction_loader">
	                        <div class="rect1"></div>
	                        <div class="rect2"></div>
	                        <div class="rect3"></div>
	                    </div>
                        <div class="modal-header header-custom">
                            <h4 class="modal-title text-center"><em class="icon-credit-card mr-1"></em>Assign Payment to Transaction</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form class="" method="post" role="form" id="frm_paybill_payment_assign_transaction" name="frm_paybill_payment_assign_transaction" onsubmit="return submit_paybill_payment_assign_transaction();">

                            <div class="modal-body">
                                <input type="hidden" id="paybill_payment_id" name="paybill_payment_id" class="form-control">
                                <input type="hidden" id="assign_transaction_system_user_id" name="system_user_id" class="form-control" value="<?php //foreach ($system_user as $row2){ echo $row2->user_id; } ?>">

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">  
                                            <label>Payment Reference # <span class="text-danger">*</span></label>                                          
                                            <input type="text" id="payment_reference" name="payment_reference" disabled class="form-control">
                                        </div>
                                        <div class="col-sm-6">  
                                            <label>Paid By <span class="text-danger">*</span></label>                                          
                                            <input type="text" id="payment_by" name="payment_by" disabled class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">  
                                            <label>Amount Paid <span class="text-danger">*</span></label>                                          
                                            <input type="text" id="payment_amount" name="payment_amount" disabled class="form-control">
                                        </div>
                                        <div class="col-sm-6">  
                                            <label>Date <span class="text-danger">*</span></label>                                          
                                            <input type="text" id="payment_date" name="payment_date" disabled class="form-control">
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-6">  
                                            <label>Account Paid To <span class="text-danger">*</span></label>                                          
                                            <input type="text" id="payment_account" name="payment_account" disabled class="form-control">
                                        </div>
                                        <div class="col-sm-6">  
                                            <label>Transaction Type <span class="text-danger">*</span></label>                                          
                                            <select data-placeholder="Select Transaction Type" class="form-control form-control-select2" tabindex="2" id="assign_payment_transaction_type" name="transaction_type">
                                                <option value=""></option>
                                                <option value="Online Order">ONLINE ORDER</option>
                                                <option value="Pos Order">POS ORDER</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>                                
                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-sm-12">  
                                            <label>Transaction <span class="text-danger">*</span></label>                                          
                                            <select data-placeholder="Select Transaction" class="form-control form-control-select2" tabindex="2" id="assign_payment_transaction_id" name="transaction_id">
                                                <option value=""></option>
                                            </select>
                                            <div id="div_assign_payment_transaction_ids_loader" class="display-none text-center text-danger mt-5"><i class="fa fa-spinner fa-spin"></i> Loading...Please wait</div>
                                        </div>
                                    </div>
                                </div>

                                <div id="div_assign_transaction_details" class="display-none mt-10">
                                    
                                </div>

                            </div>

                            <div class="modal-footer">
                                <button type="submit" id="btn_assign_paybill_payment_transaction" class="btn btn-success"><i class="icon-checkmark2"></i> Assign Payment</button>
                                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-cross3"></i> Close</button>

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

                    filter_paybill_payments();
                });
                function assign_dates() {
                    $('#date_from').val($('#date_from_to').data('daterangepicker').startDate.format('YYYY-MM-DD'));
                    $('#date_to').val($('#date_from_to').data('daterangepicker').endDate.format('YYYY-MM-DD'));                 
                }
                // filter_paybill_payments();
            </script>