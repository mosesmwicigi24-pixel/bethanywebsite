<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">

            	<div class="nk-block nk-block-lg">
                    <!-- <div class="nk-block-head">
                        <div class="nk-block-head-content">
                            <h5 class="nk-block-title"><em class="icon ni ni-list-index"></em> Sales List</h5>
                        </div>

                    </div> -->
                    <div class="nk-block-head nk-block-head-sm">
					    <div class="nk-block-between">
					        <div class="nk-block-head-content"><h5 class="nk-block-title page-title"><em class="icon ni ni-redo"></em> Sales Returns List</h5></div>
					        <div class="nk-block-head-content">
					            <div class="toggle-wrap nk-block-tools-toggle">
					                <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
					                <div class="toggle-expand-content" data-content="pageMenu">
					                    <ul class="nk-block-tools g-3">
					                        <li class="nk-block-tools-opt">
                                                <?php if ($sbr_pos_sales_returns_add == true): ?>
    					                            <a href="<?php echo base_url(); ?>pos/sales/new_sales_return" class="btn btn-icon btn-sm btn-primary d-md-none"><em class="icon ni ni-plus-c"></em></a>
    					                            <a href="<?php echo base_url(); ?>pos/sales/new_sales_return" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-plus-c"></em><span>New Sales Return</span></a>
                                                <?php endif; ?>
                                                <!-- <button id="clickat">Click</button> -->
					                        </li>
					                    </ul>
					                </div>
					            </div>
					        </div>
					    </div>
					</div>

                    <div class="card card-preview">
                    	<div class="spinner display-none" id="sales_returns_list_loader">
                            <div class="rect1"></div>
                            <div class="rect2"></div>
                            <div class="rect3"></div>
                        </div>

                        <div class="card-inner">
                            <form method="post" class="form" id="frm_filter_sales_returns" name="frm_filter_sales_returns" method="post">
                                <div class="row gy-4">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <div class="input-daterange date-picker-range input-group">
                                                    <input type="text" class="form-control" id="date_from" name="date_from" />
                                                    <div class="input-group-addon">TO</div>
                                                    <input type="text" class="form-control" id="date_to" name="date_to" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="button" id="btn_sales_returns_filter" class="btn btn-primary" onclick="load_sales_returns_list();">Filter</button>
                                    </div>
                                </div>
                            </form>

                            <div id="div_sales_returns_list" class="div-list">

                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="modal_send_sales_return_order_via_email">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header header-custom">
                <h6 class="modal-title text-center"><em class="icon ni ni-mail mr-1"></em>Send Sales Return via Email</h6>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <form id="frm_send_sales_return_order_via_email" name="frm_send_sales_return_order_via_email" method="post" class="is-alter" onsubmit="return submit_send_sales_return_order_via_email();">
                <div class="modal-body">
                    <div class="spinner display-none" id="send_sales_return_order_via_email_loader">
                        <div class="rect1"></div>
                        <div class="rect2"></div>
                        <div class="rect3"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div>

                                <input type="hidden" id="send_email_pos_sales_return_id" name="pos_sales_return_id">

                                <div class="col-md-12">
                                    <div class="box box-solid bg-lighter">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="sales_return_payment_method">Sender Email Account</label>
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
                    <button type="submit" id="btn_send_pos_sales_return_order_via_email" class="btn btn-success"><em class="icon ni ni-send mr-1"></em>Send Email</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade in" id="modal_sales_return_refund">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header header-custom">
                <h6 class="modal-title text-center"><em class="icon ni ni-cc-alt mr-1"></em>Make a Refund <span id="refund_header_pos_sales_return_number"></span></h6>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <form id="frm_sales_return_make_refund" name="frm_sales_return_make_refund" method="post" class="is-alter" onsubmit="return submit_sales_return_refund();">
                <div class="modal-body">
                    <div class="spinner display-none" id="sales_return_make_refund_loader">
                        <div class="rect1"></div>
                        <div class="rect2"></div>
                        <div class="rect3"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <div>

                                <input type="hidden" id="refund_pos_sales_return_id" name="pos_sales_return_id">
                                <input type="hidden" id="refund_pos_sales_return_number" name="pos_sales_return_number">
                                <input type="hidden" id="refund_context" name="context">

                                <div class="col-md-12 refunds_div">
                                    <div class="box box-solid bg-lighter">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sales_return_refund_method">Refund Method</label>
                                                        <select class="form-control select2" data-placeholder="Select Refund Method"  id="sales_return_refund_method" name="refund_method">
                                                            <option value="">Select Refund Method</option>
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
                                                        <label for="sales_return_refund_amount">Amount</label>
                                                        <input type="number" class="form-control" id="sales_return_refund_amount" name="refund_amount" step="any" min="0">
                                                    </div>
                                                </div>
                                                <div id="div_sales_return_refund_mpesa_btns" class="display-none">
                                                    <div class="col-md-6">
                                                        <button id="btn_sales_return_refund_mpesa_instructions" type="button" tabindex="0"  class="badge badge-sm badge-dot has-bg badge-info" data-toggle="popover" data-placement="top" title="MPESA refund Instructions" data-content="
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
                                                        <button id="btn_sales_return_make_refund_select" type="button" class="badge badge-sm badge-dot has-bg badge-secondary d-none d-mb-inline-flex">Select Transaction <i class="ion-load-c icon-spinner display-none" id="select_mpesa_refund_loader"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="div_sales_return_refund_reference_number">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label id="lbl_refund_reference_number" for="sales_return_refund_reference_number">Reference #</label>
                                                        <input type="text" class="form-control" id="sales_return_refund_reference_number" name="reference_number" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="refund_note_1">Refund Note</label>
                                                        <textarea type="text" class="form-control" id="sales_return_refund_note" name="refund_note" placeholder=""></textarea>
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
                                            <div class="row bg-red">
                                                <div class="col-md-12 border-custom-bottom">
                                                    <span class="col-md-6 text-right text-bold">Total Refundable:</span>
                                                    <span class="col-md-6 text-right text-bold custom-font-size" id="div_sales_return_total_amount">KES 0.00</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 border-custom-bottom">
                                                    <span class="col-md-6 text-right text-bold">Total Refunded:</span>
                                                    <span class="col-md-6 text-right text-bold custom-font-size" id="div_sales_return_total_refunded">KES 0.00</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 border-custom-bottom">
                                                    <span class="col-md-6 text-right text-bold">Balance:</span>
                                                    <span class="col-md-6 text-right text-bold custom-font-size" id="div_sales_return_refund_balance">KES 0.00</span>
                                                </div>
                                            </div>
                                            <!-- <div class="row">
                                                <div class="col-md-12 bg-orange pt-1 pb-1">
                                                    <span class="col-md-6 text-right text-bold">Change:</span>
                                                    <span class="col-md-6 text-right text-bold custom-font-size" id="div_sales_return_change">KES 0.00</span>
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
                    <button type="submit" id="btn_submit_sales_return_refund" class="btn btn-success"><i class="ion-checkmark-circled mr-1"></i>Submit refund</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modal_void_pos_sales_return">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="icon ni ni-trash-alt"></i> Void sales_return</h6>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <div class="modal-body">
                <div class="spinner display-none" id="void_sales_return_loader">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                </div>
                <form id="frm_void_pos_sales_return" name="frm_void_pos_sales_return" method="post" class="is-alter" onsubmit="return submit_void_pos_sales_return();">

                    <input type="hidden" id="void_pos_sales_return_id" name="pos_sales_return_id">
                    <input type="hidden" id="void_context" name="context">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid bg-lighter">
                                <div class="box-body">
                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <textarea name="void_reason" cols="40" rows="5" id="pos_sales_return_void_reason" class="form-control" placeholder="Void Reason"></textarea>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" id="btn_void_pos_sales_return" class="btn btn-success"><i class="ion-checkmark-circled mr-1"></i>Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modal_approve_pos_sales_return" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-primary"><em class="icon ni ni-check-circle-cut"></em> Approve/Reject Sales Return</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" style="min-height: 200px;">
                <div id="div_approve_pos_sales_return">

                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var startDate = moment().subtract(30, 'days').format('YYYY-MM-DD');
        var endDate = moment().format('YYYY-MM-DD');

        $("#date_from").datepicker('setDate', startDate);
        $("#date_to").datepicker('setDate', endDate);

        load_sales_returns_list();

        $(document).on('click', '#clickat', function(e){
            console.log('Clicked');
            $('.sales_return_refund[data-pos-sales-return-id="' + 1 + '"]').trigger('click');
        });
    });
</script>
