<script>
	$(document).ready(function() {
		$('.select2').select2({
	        placeholder: "Enter at least 1 character",
	        allowClear: true
	    });

	    //VALIDATE FRM_APPROVE_MEMBER
	    $("#frm_approve_pos_sales_return").validate({
	        errorPlacement: function(error, element) {
	            if (element.parent().attr("class") == "radio-btn") {
	                error.insertAfter(element.parent());
	            } else {
	                error.insertAfter(element);
	            }
	        },
	        rules: {
	            pos_sales_return_status: {
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
</script>
<!-- <h6 class="font-weight-semibold">Sales Return Details</h6> -->
<?php foreach ($pos_sales_return as $row): ?>
	<form id="frm_approve_pos_sales_return" name="frm_approve_pos_sales_return" method="post" onsubmit="return submit_approve_pos_sales_return();">
		<fieldset <?php //if ($sbr_pos_sales_returns_approve == false){ echo 'disabled'; } ?>>
			<div class="">

   				<input id="approval_pos_sales_return_id" name="pos_sales_return_id" type="hidden" value="<?php echo $row->pos_sales_return_id; ?>">
   				<input id="approval_pos_sales_return_customer_id" name="customer_id" type="hidden" value="<?php echo $row->customer_id; ?>">
   				<input id="approval_pos_sales_return_amount" name="return_amount" type="hidden" value="<?php echo $row->total_amount; ?>">
   				<input id="approval_pos_sales_return_context" name="return_context" type="hidden" value="<?php echo $return_context; ?>">

				<div class="form-group mb-3">
					<div class="row">
						<div class="col-md-12">
							<label>Your Action<span class="text-danger">*</span></label>
							<select id="approve_pos_sales_return_status" name="pos_sales_return_status" class="form-control select2" data-placeholder="--Select Your Action--" data-fouc>
								<option value="">--Select Your Action--</option>
								<option value="1">Approve</option>
								<option value="2">Reject</option>
							</select>
						</div>
					</div>
				</div>
				<div id="div_pos_sales_return_approve" class="display-none mt-3">					
					<div class="row">
						<div class="col-md-12">
							<div class="form-group">
								<div class="radio-btn">
									<input type="radio" id="approve_settlement_refund" name="approve_settlement" value="Refund" class="">
									<label class="font-weight-light" for="approve_settlement_refund">Give a Refund</label>
								</div>
							</div>
							<div class="form-group">
								<div class="radio-btn">
									<input type="radio" id="approve_settlement_credit" name="approve_settlement" value="Credit" class="">
									<label class="font-weight-light" for="approve_settlement_credit">Retain as an availble credit</label>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div id="div_pos_sales_return_reject" class="display-none">
					<div class="form-group mb-3">
						<div class="row">
							<div class="col-md-12">
								<label>Rejection Reason<span class="text-danger">*</span></label>
								<input type="text" id="rejection_reason" name="rejection_reason" class="form-control" value="<?php //echo $row->rejection_reason; ?>" >
							</div>
						</div>
					</div>
				</div>

			</div>

			<div class="modal-footer">								
				<button type="submit" id="btn_approve_pos_sales_return" class="btn btn-primary"><i class="icon-checkmark4"></i> SUBMIT</button>
				<button type="button" class="btn btn-danger" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CLOSE</button>
			</div>
		</fieldset>
	</form>

<?php endforeach; ?>