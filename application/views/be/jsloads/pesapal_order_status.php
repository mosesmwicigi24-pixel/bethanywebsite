	<?php if (isset($order_status) && $order_status != ''): ?>
		<p style="text-align:center" class="alert alert-info"><strong>
        	PESAPAL ORDER PAYMENT STATUS:<br /><br />
        	Order Number: <?php echo $ord_order_number; ?><br />
        	Payment Status: <?php echo $order_status; ?>
        </strong></p>
        <?php if($order_status == 'COMPLETED'): ?>
            <form id="frm_dispatch_online_order" name="frm_dispatch_online_order" method="post" class="is-alter" onsubmit="return submit_dispatch_online_order('<?php echo $context; ?>');">
                <div class="modal-body">
                    <div class="spinner2 display-none" id="dispatch_online_order_loader">
                        <div class="rect1"></div>
                        <div class="rect2"></div>
                        <div class="rect3"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div>

                                <input type="hidden" id="disptch_order_number" name="ord_order_number" value="<?php echo $ord_order_number; ?>">

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
        	<!-- <div class="alert text-right">
        	   <a href="javascript:void(0);" class="btn btn-success fcomplete" data-order-number="<?php echo $ord_order_number; ?>"><i class="icon-cart-remove"></i> Dispatch Order</a>
        	   <a href="javascript:void(0);" class="btn btn-danger fclose"><i class="icon-close2"></i> Close</a>
            </div> -->
        <?php else: ?>
        	<div class="alert">
            <p style="text-align:right"><a href="javascript:void(0);" class="btn btn-danger fclose"><i class="icon-close2"></i> Close</a></p>
            </div>
        <?php endif; ?>
    <?php else: ?>
    	<p style="text-align:center" class="alert alert-danger"><strong>
        	PESAPAL ORDER PAYMENT STATUS::<br /><br />
        	Sorry. The payment status could not be retrieved. Please check your network connection.
        </strong></p>
        	<div class="alert">
            <p style="text-align:right"><a href="javascript:void(0);" class="btn btn-danger fclose"><i class="icon-close2"></i> Close</a></p>
            </div>
    <?php endif; ?>
	
    
        <script type="text/javascript">
			$(document).ready(function() {
                $('.form-control-select2').select2({
                    allowClear: true
                });
				$('.fclose').click(function() {
    				$('#modal_verify_pesapal_payment').modal('toggle');
				})
				$('.fcomplete').click(function() {
					var order_number =  $(this).data('order-number');

                    swal({
                        text: 'Do you wish to dispatch this Order?',
                        type: 'question',
                        showCancelButton: true,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                        allowOutsideClick: false
                    }).then(function(result) {
                        if (result.value) {
                            $.ajax({
                                url: baseDir + 'be/sales/dispatch_order/' + order_number,
                                type: 'POST',
                                data: '',
                                xhr: function() {
                                    var myXhr = $.ajaxSettings.xhr();
                                    return myXhr;
                                },
                                cache: false,
                                contentType: false,
                                processData: false,
                                success: function(res) {
                                    try {
                                        var obj1 = res;
                                        var obj = $.parseJSON(obj1);

                                        if (obj['status'] == 'ERR') {
                                            swal({
                                                type: 'warning',
                                                title: 'Error',
                                                html: obj['message']
                                            });
                                        } else if (obj['status'] == 'SUCCESS') {
                                            swal({
                                                type: 'success',
                                                title: 'Success!',
                                                html: obj['message']
                                            });
                                            $('#modal_verify_pesapal_payment').modal('toggle');
                                            filter_online_sales();
                                        }
                                    } catch (err) {
                                        swal({
                                            type: 'warning',
                                            title: 'Error',
                                            html: err
                                        });
                                    }
                                },
                                error: function() {
                                    swal({
                                        type: 'warning',
                                        title: 'Error',
                                        html: "Something went wrong. Please check your network and try again."
                                    });
                                }
                            });
                        } else {}
                    });
					
				});

                //FRM_DISPATCH_ONLINE_ORDER
                $("#frm_dispatch_online_order").validate({
                    errorPlacement: function(error, element) {
                        if (element.parent().parent().attr("class") == "checker" || element.parent().parent().attr("class") == "choice") {
                            error.appendTo(element.parent().parent().parent().parent().parent());
                        } else if (element.parent().parent().attr("class") == "checkbox" || element.parent().parent().attr("class") == "radio") {
                            error.appendTo(element.parent().parent().parent());
                        } else {
                            error.insertAfter(element);
                        }
                    },
                    rules: {
                        outlet_id: {
                            required: true
                        }
                    },
                    messages: {
                        custom_message: {
                            required: "This field is required",
                        },
                        agree: "Please accept our policy"
                    },
                    success: function(label) {
                        label.parent().removeClass('error');
                        label.remove();
                    }
                });
			});
