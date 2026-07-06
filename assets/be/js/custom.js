$(document).ready(function() {

	$('.select').select2({
        placeholder: "Enter at least 1 character",
        allowClear: true
    });
    $('.select-basic').select2({
        placeholder: "Enter at least 1 character"
    });
    $('.select-multiple').select2({
        placeholder: "Enter at least 1 character"
    });
    $('.select-multiple-max5').select2({
        placeholder: "Enter at least 1 character",
        maximumSelectionLength: 5
    });

    var elems = Array.prototype.slice.call(document.querySelectorAll('.form-check-input-switchery'));
    elems.forEach(function(html) {
      var switchery = new Switchery(html);
    });

    $('.form-check-input-switch').bootstrapSwitch();

	$('.pickadate').pickadate({
		format: 'yyyy-mm-dd',
		formatSubmit: 'yyyy-mm-dd',
		selectMonths: true,
		selectYears: 100
	});
	$('.pickadatemax').pickadate({
		format: 'yyyy-mm-dd',
		formatSubmit: 'yyyy-mm-dd',
		selectMonths: true,
		selectYears: 100,
  		max: true
	});

    $('.form-check-input-styled').uniform();
    var setCustomDefaults = function() {
        swal.setDefaults({
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-primary',
            cancelButtonClass: 'btn btn-light'
        });
    }
    $('.colorpicker-show-input').spectrum({
    	showInitial: true,
        showInput: true,
        preferredFormat: "hex"
    });

    $('.pop-over').popover();

    $('.tagsinput-custom-tag-class').tagsinput({
        tagClass: function(item){
            return 'bg-primary text-white';
        }
    });


    setCustomDefaults();

    load_ajax_notifications();

    $(".chk_sbr_overall").on("change", function() {
	    $tr = $(this).closest('tr');
	    $index=$('tr').index($tr);
       	$('tr:eq(' + $index + ') td input').prop("checked", this.checked);
	});

    $(document).on("change","#chk_product_outlet_regular_prices",(function(e){$(this).is(":checked")?$("#div_product_outlet_regular_prices").slideUp():$("#div_product_outlet_regular_prices").slideDown()}));
    $(document).on("change","#chk_product_outlet_sale_prices",(function(e){$(this).is(":checked")?$("#div_product_outlet_sale_prices").slideUp():$("#div_product_outlet_sale_prices").slideDown()}));
    $(document).on("change","#chk_product_outlet_minimum_prices",(function(e){$(this).is(":checked")?$("#div_product_outlet_minimum_prices").slideUp():$("#div_product_outlet_minimum_prices").slideDown()}));

    $(document).on("change",".chk_related_unit_outlet_unit_prices",(function(e){
        var unit_id = $(this).attr("data-unit-id");
        $(this).is(":checked")?$("#div_related_unit_outlet_unit_prices_" + unit_id).slideUp():$("#div_related_unit_outlet_unit_prices_" + unit_id).slideDown();
    }));


	$("#add_shipping_method").on('change', function() {
		var shipping_method = this.value;

        var shippingRule = {
			add_shipping_fee: {
				required: true
			}
		};
		if (shipping_method != '' && shipping_method != null && shipping_method != 0){
			$("#div_add_shipping_fee").fadeIn("fast");
	    	addRules(shippingRule);
		}else{
			$("#div_add_shipping_fee").fadeOut("fast");
			removeRules(shippingRule);
		}
	});

	$("#edit_shipping_method").on('change', function() {
		var shipping_method = this.value;

        var shippingRule = {
			edit_shipping_fee: {
				required: true
			}
		};
		if (shipping_method != '' && shipping_method != null && shipping_method != 0){
			$("#div_edit_shipping_fee").fadeIn("fast");
	    	addRules(shippingRule);
		}else{
			$("#div_edit_shipping_fee").fadeOut("fast");
			removeRules(shippingRule);
		}
	});

	$("#add_default_currency").on('change', function() {

		var currencyRules = {
			add_exchange_rate: {
				required: true
			}
		};

		if (this.checked) {
			$("#div_add_exchange_rate").fadeOut("fast");
			removeRules(currencyRules);
		}else {
			$("#div_add_exchange_rate").fadeIn("fast");
			addRules(currencyRules);
		}
	});

	$("#edit_default_currency").on('change', function() {

		var currencyRules = {
			edit_exchange_rate: {
				required: true
			}
		};

		if (this.checked) {
			$("#div_edit_exchange_rate").fadeOut("fast");
			removeRules(currencyRules);
		}else {
			$("#div_edit_exchange_rate").fadeIn("fast");
			addRules(currencyRules);
		}
	});

	$("#use_sender_id").on('change', function() {

		var smsRules = {
			sender_id: {
				required: true
			}
		};

		if (this.checked) {
			addRules(smsRules);
		}else {
			removeRules(smsRules);
		}
	});

	$("#use_short_code").on('change', function() {

		var smsRules = {
			short_code: {
				required: true
			}
		};

		if (this.checked) {
			addRules(smsRules);
		}else {
			removeRules(smsRules);
		}
	});

	$("#loyalty_enrolled").on('change', function() {

		var loyaltyRules = {
			loyalty_enrollment_date: {
				required: true
			}
		};

		if (this.checked) {
			addRules(loyaltyRules);
		}else {
			removeRules(loyaltyRules);
		}
	});

	//ADD PRODUCT COLOURS
	$("#btn_add_product_color").on('click', function() {
		var to_add = '<div class="form-group mt-1 mb-1"><div class="row"><div class="col-md-6"><input type="text" name="product_color[]"  data-color="#000000" value="#000000" class="form-control product-color colorpicker-show-input" data-show-buttons="false" data-fouc><a href="javascript:void(0)" class="badge badge-danger remove-color ml-1" title="Remove Color"><i class="icon-cancel-circle2"></i></a></div></div></div>';
		$('#div_product_colors').append(to_add);
		$(".colorpicker-show-input").each(function() {
			$(this).spectrum({
				showInitial: true,
		        showInput: true,
		        preferredFormat: "hex"
		    });
		});
	});

	$(document).on('click', '.remove-color', function(e) {
	   e.preventDefault();
	   $(this).closest('.form-group').remove();
	   return false;
	});

	//ADD PRODUCT ATTRIBUTES
	// $("#btn_add_product_attribute").on('click', function() {
	// 	var to_add = '<div class="form-group mt-1 mb-1"><div class="row"><div class="col-md-5"><label>Name</label><input type="text" name="product_attribute_name[]" value="" class="form-control product-attribute-name"></div><div class="col-md-5"><label>Value</label><input type="text" name="product_attribute_value[]" value="" class="form-control"></div><div class="col-md-2"><label class="d-block">&nbsp;</label><a href="javascript:void(0)" class="badge badge-danger remove-attribute" title="Remove Attribute"><i class="icon-cancel-circle2"></i></a></div></div></div>';
	// 	$('#div_product_attributes').append(to_add);
	// });

	$(document).on('click', '.remove-attribute', function(e) {
	   e.preventDefault();
	   $(this).closest('.form-group').remove();
	   return false;
	});

	//PRODUCT IMAGES
	$("#btn_add_product_image").on('click', function() {
		var to_add = '<div class="form-group mt-2 mb-1"><div class="row"><div class="col-md-10"><input type="file" name="product_gallery_image[]" value="" class="form-control product-gallery-image"></div><div class="col-md-2"><a href="javascript:void(0)" class="badge badge-danger remove-image" title="Remove Image"><i class="icon-cancel-circle2"></i></a></div></div></div>';
		$('#div_product_images').append(to_add);
	});

	$(document).on('click', '.remove-image', function(e) {
	   e.preventDefault();
	   $(this).closest('.form-group').remove();
	   return false;
	});

    //ADD PRODUCT UNIT OF MEASURE
    $(document).on('click', '#btn_add_product_unit_of_measure', function(e) {
       e.preventDefault();

        $("#div_pa_unit_of_measure_error").fadeOut("fast");
        $("#div_pa_unit_of_measure_success").fadeOut("fast");

        $('#frm_pa_unit_of_measure').each(function() {
            this.reset();
        });
       $('#pa_unit_id').val('').change();
       $('#pa_context').val('Add');
       $('#btn_pa_unit_of_measure').html('<i class="icon-checkmark4"></i> ADD');

    });

    $(document).on('change', '#product_type', function(e) {
        var product_type = $(this).val();

        if (product_type == 'Simple') {
            $('#lnk-tab-variations').fadeOut('slow');
            $('#lnk-tab-inventory').fadeIn('slow');
            $('#div_simple_product_prices').fadeIn('slow');
            $('.nav-tabs a[href="#tab-general"]').tab('show');
        } else if (product_type == 'Variable') {
            $('#lnk-tab-variations').fadeIn('slow');
            $('#lnk-tab-inventory').fadeOut('slow');
            $('#div_simple_product_prices').fadeOut('slow');
            $('.nav-tabs a[href="#tab-general"]').tab('show');
        }
    });

    $(document).on('click', '#btn_modal_add_product_attribute', function(e){
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/products/attribute_add_valid',
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#product_loader").fadeIn("fast");
            },
            success: function(res){
                $("#product_loader").fadeOut("fast");

                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: 'Error',
                        html: res.message
                    });
                } else {

                    $('#frm_add_product_attribute').each(function() {
                        this.reset();
                    });
                    $('#add_product_attribute_values').tagsinput('removeAll');

                    $("#apa_product_id").val(res.product_id);

                    $('#modal_add_product_attribute').modal('toggle');

                }
            },
            error: function(){
                $("#product_loader").fadeOut("fast");
            }
        });

    });

    $(document).on('click', '#btn_modal_edit_add_product_attribute', function(e){
        var product_id = $('#product_id').val();

        $('#frm_add_product_attribute').each(function() {
            this.reset();
        });
        $('#add_product_attribute_values').tagsinput('removeAll');

        $("#apa_product_id").val(product_id);

        $('#modal_add_product_attribute').modal('toggle');

    });


    // btn_modal_edit_add_product_attribute

    $(document).on('click', '.lnk-edit-product-attribute', function(e){
        e.preventDefault();

        $('#frm_edit_product_attribute').each(function() {
            this.reset();
        });
        $('#edit_product_attribute_values').tagsinput('removeAll');

        var product_attribute_id = $(this).attr("data-product-attribute-id");

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/products/get_product_attribute/' + product_attribute_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#product_loader").fadeIn("fast");
            },
            success: function(res){
                $("#product_loader").fadeOut("fast");

                $.each(res, function(index, element) {
                    $("#epa_product_attribute_id").val(element.product_attribute_id);
                    $("#epa_product_id").val(element.product_id);
                    $("#edit_product_attribute_name").val(element.product_attribute_name);

                    $.each(element.values, function(index2, element2) {
                        $('#edit_product_attribute_values').tagsinput('add', element2.product_attribute_value);
                    });
                });

                $('#modal_edit_product_attribute').modal('toggle');
            },
            error: function(){
                $("#product_loader").fadeOut("fast");
            }
        });
    });

    $(document).on('click', '.lnk-delete-product-attribute', function(e){
        e.preventDefault();

        var product_attribute_id = $(this).attr("data-product-attribute-id");

        swal({
            text: 'Do you wish to delete this product attribute?',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: baseDir + 'be/products/delete_attribute/' + product_attribute_id,
                    type: 'POST',
                    data: '',
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 'ERR') {
                            swal({
                                type: 'warning',
                                title: 'Error',
                                html: res.message
                            });
                        } else if (res.status == 'SUCCESS') {
                            swal({
                                type: 'success',
                                title: 'Success!',
                                html: res.message
                            });
                            load_product_attributes();
                            load_product_variations();
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

    $(document).on('click', '#btn_modal_add_product_variation', function(e){
        e.preventDefault();

        var product_id = $('#product_id').val();

        if (product_id == '' || product_id == null){
            $.ajax({
                type: 'POST',
                url: baseDir + 'be/products/variation_add_valid',
                data:'',
                dataType: 'json',
                beforeSend: function(){
                    $("#product_loader").fadeIn("fast");
                },
                success: function(res){
                    $("#product_loader").fadeOut("fast");

                    if (res.status == 'ERR') {
                        swal({
                            type: 'warning',
                            title: 'Error',
                            html: res.message
                        });
                    } else {

                        $("#div_variation_add_form").html(res.variation_add_form);

                        $('#modal_add_product_variation').modal('toggle');

                    }
                },
                error: function(){
                    $("#product_loader").fadeOut("fast");
                }
            });
        } else {
            $.ajax({
                type: 'POST',
                url: baseDir + 'be/products/variation_edit_add_valid',
                data: { product_id: product_id },
                dataType: 'json',
                beforeSend: function(){
                    $("#product_loader").fadeIn("fast");
                },
                success: function(res){
                    $("#product_loader").fadeOut("fast");

                    if (res.status == 'ERR') {
                        swal({
                            type: 'warning',
                            title: 'Error',
                            html: res.message
                        });
                    } else {

                        $("#div_variation_add_form").html(res.variation_add_form);

                        $('#modal_add_product_variation').modal('toggle');

                    }
                },
                error: function(){
                    $("#product_loader").fadeOut("fast");
                }
            });
        }
    });

    $(document).on('click', '#btn_modal_edit_add_product_variation', function(e){
        e.preventDefault();
        var product_id = $('#product_id').val();
        $.ajax({
            type: 'POST',
            url: baseDir + 'be/products/variation_edit_add_valid',
            data: { product_id: product_id },
            dataType: 'json',
            beforeSend: function(){
                $("#product_loader").fadeIn("fast");
            },
            success: function(res){
                $("#product_loader").fadeOut("fast");

                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: 'Error',
                        html: res.message
                    });
                } else {

                    $("#div_variation_add_form").html(res.variation_add_form);

                    $('#modal_add_product_variation').modal('toggle');

                }
            },
            error: function(){
                $("#product_loader").fadeOut("fast");
            }
        });

    });

    // btn_modal_edit_add_product_variation

    $(document).on('click', '.lnk-edit-product-variation', function(e){
        e.preventDefault();

        var product_variation_id = $(this).attr("data-product-variation-id");
        var product_id = $(this).attr("data-product-id");

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/products/get_edit_product_variation/' + product_variation_id,
            data: { product_id: product_id },
            dataType: 'json',
            beforeSend: function(){
                $("#product_loader").fadeIn("fast");
            },
            success: function(res){
                $("#product_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: 'Error',
                        html: res.message
                    });
                } else {

                    $("#div_variation_edit_form").html(res.variation_edit_form);

                    $('#modal_edit_product_variation').modal('toggle');

                }
            },
            error: function(){
                $("#product_loader").fadeOut("fast");
            }
        });
    });

    $(document).on('click', '.lnk-delete-product-variation', function(e){
        e.preventDefault();

        var product_variation_id = $(this).attr("data-product-variation-id");

        swal({
            text: 'Do you wish to delete this product variation?',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: baseDir + 'be/products/delete_variation/' + product_variation_id,
                    type: 'POST',
                    data: '',
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 'ERR') {
                            swal({
                                type: 'warning',
                                title: 'Error',
                                html: res.message
                            });
                        } else if (res.status == 'SUCCESS') {
                            swal({
                                type: 'success',
                                title: 'Success!',
                                html: res.message
                            });
                            load_product_variations();
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

    $(document).on('click', '#btn_modal_set_product_image', function(e){
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/products/set_product_image_valid',
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#product_loader").fadeIn("fast");
            },
            success: function(res){
                $("#product_loader").fadeOut("fast");

                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: 'Error',
                        html: res.message
                    });
                } else {
                    
                    $('#frm_set_product_image').each(function() {
                        this.reset();
                    });

                    $("#spi_product_id").val(res.product_id);

                    $('#modal_set_product_image').modal('toggle');

                }
            },
            error: function(){
                $("#product_loader").fadeOut("fast");
            }
        });

    });

    $(document).on('click', '#btn_modal_edit_set_product_image', function(e){
        e.preventDefault();

        var product_id = $('#product_id').val();

        $('#frm_set_product_image').each(function() {
            this.reset();
        });

        $("#spi_product_id").val(product_id);

        $('#modal_set_product_image').modal('toggle');

    });

    $(document).on('click', '#btn_modal_change_product_image', function(e){
        e.preventDefault();

        $("#div_set_product_image_success").fadeOut("fast");
        $("#div_set_product_image_error").fadeOut("fast");

        $('#frm_set_product_image').each(function() {
            this.reset();
        });

        $("#spi_product_id").val($("#product_id").val());

        $('#modal_set_product_image').modal('toggle');

    });


    $(document).on('click', '#btn_modal_add_product_gallery_image', function(e){
        e.preventDefault();

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/products/set_product_image_valid',
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#product_loader").fadeIn("fast");
            },
            success: function(res){
                $("#product_loader").fadeOut("fast");

                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: 'Error',
                        html: res.message
                    });
                } else {
                    
                    $('#frm_add_product_gallery_image').each(function() {
                        this.reset();
                    });

                    $("#apgi_product_id").val(res.product_id);

                    $('#modal_add_product_gallery_image').modal('toggle');

                }
            },
            error: function(){
                $("#product_loader").fadeOut("fast");
            }
        });

    });

    $(document).on('click', '#btn_modal_edit_add_product_gallery_image', function(e){
        e.preventDefault();

        var product_id = $('#product_id').val();

        $('#frm_add_product_gallery_image').each(function() {
            this.reset();
        });

        $("#apgi_product_id").val(product_id);

        $('#modal_add_product_gallery_image').modal('toggle');

    });

    // btn_modal_edit_add_product_gallery_image

    $(document).on('click', '.lnk_edit_product_gallery_image', function(e){
        e.preventDefault();

        $("#div_edit_product_gallery_image_success").fadeOut("fast");
        $("#div_edit_product_gallery_image_error").fadeOut("fast");

        $('#frm_edit_product_gallery_image').each(function() {
            this.reset();
        });
        var product_image_id = $(this).attr("data-product-image-id");

        $("#epgi_product_image_id").val(product_image_id);

        $('#modal_edit_product_gallery_image').modal('toggle');
        //  href="#modal_edit_product_gallery_image"

    });

    $(document).on('click', '#btn_save_product_draft', function(e){
        e.preventDefault();

        autosave_product();

    });

	$("#customer_use_same_shipping_address").on('change', function() {
        if ($('#customer_use_same_shipping_address').is(':checked')) {
        	$('#shipping_first_name').val($('#billing_first_name').val());
        	$('#shipping_last_name').val($('#billing_last_name').val());
        	$('#shipping_email_address').val($('#billing_email_address').val());
        	$('#shipping_phone_number').val($('#billing_phone_number').val());
        	$('#shipping_street_address').val($('#billing_street_address').val());
        	$('#shipping_postal_code').val($('#billing_postal_code').val());
        	$('#shipping_country_id').val($('#billing_country_id').val()).change();
        	$('#shipping_region_id').val($('#billing_region_id').val()).change();

        	cur_shipping_region_id = $('#billing_region_id').val();
        }
    });


    //ADD PAYMENT OPTION
    $("#add_payment_option_cash").on('change', function() {
        if ($('#add_payment_option_cash').is(':checked')) {
            $('#add_process_credit_card').prop('checked', false);
            $('#add_process_credit_card').prop('disabled', true);
            $('#div_add_process_credit_card').addClass('disabled');
            $('#add_process_loyalty_card').prop('checked', false);
            $('#add_process_loyalty_card').prop('disabled', true);
            $('#div_add_process_loyalty_card').addClass('disabled');
        }
    });
    $("#add_payment_option_mpesa").on('change', function() {
        if ($('#add_payment_option_mpesa').is(':checked')) {
            $('#add_process_credit_card').prop('checked', false);
            $('#add_process_credit_card').prop('disabled', true);
            $('#div_add_process_credit_card').addClass('disabled');
            $('#add_process_loyalty_card').prop('checked', false);
            $('#add_process_loyalty_card').prop('disabled', true);
            $('#div_add_process_loyalty_card').addClass('disabled');
        }
    });
    $("#add_payment_option_cheque").on('change', function() {
        if ($('#add_payment_option_cheque').is(':checked')) {
            $('#add_process_credit_card').prop('checked', false);
            $('#add_process_credit_card').prop('disabled', true);
            $('#div_add_process_credit_card').addClass('disabled');
            $('#add_process_loyalty_card').prop('checked', false);
            $('#add_process_loyalty_card').prop('disabled', true);
            $('#div_add_process_loyalty_card').addClass('disabled');
        }
    });
    $("#add_payment_option_credit_card").on('change', function() {
        if ($('#add_payment_option_credit_card').is(':checked')) {
            $('#add_process_credit_card').prop('checked', false);
            $('#add_process_credit_card').prop('disabled', false);
            $('#div_add_process_credit_card').removeClass('disabled');
            $('#add_process_loyalty_card').prop('checked', false);
            $('#add_process_loyalty_card').prop('disabled', true);
            $('#div_add_process_loyalty_card').addClass('disabled');
        }
    });
    $("#add_payment_option_loyalty_card").on('change', function() {
        if ($('#add_payment_option_loyalty_card').is(':checked')) {
            $('#add_process_credit_card').prop('checked', false);
            $('#add_process_credit_card').prop('disabled', true);
            $('#div_add_process_credit_card').addClass('disabled');
            $('#add_process_loyalty_card').prop('checked', false);
            $('#add_process_loyalty_card').prop('disabled', false);
            $('#div_add_process_loyalty_card').removeClass('disabled');
        }
    });

    //EDIT PAYMENT OPTION
    $("#edit_payment_option_cash").on('change', function() {
        if ($('#edit_payment_option_cash').is(':checked')) {
            $('#edit_process_credit_card').prop('checked', false);
            $('#edit_process_credit_card').prop('disabled', true);
            $('#div_edit_process_credit_card').addClass('disabled');
            $('#edit_process_loyalty_card').prop('checked', false);
            $('#edit_process_loyalty_card').prop('disabled', true);
            $('#div_edit_process_loyalty_card').addClass('disabled');
        }
    });
    $("#edit_payment_option_mpesa").on('change', function() {
        if ($('#edit_payment_option_mpesa').is(':checked')) {
            $('#edit_process_credit_card').prop('checked', false);
            $('#edit_process_credit_card').prop('disabled', true);
            $('#div_edit_process_credit_card').addClass('disabled');
            $('#edit_process_loyalty_card').prop('checked', false);
            $('#edit_process_loyalty_card').prop('disabled', true);
            $('#div_edit_process_loyalty_card').addClass('disabled');
        }
    });
    $("#edit_payment_option_cheque").on('change', function() {
        if ($('#edit_payment_option_cheque').is(':checked')) {
            $('#edit_process_credit_card').prop('checked', false);
            $('#edit_process_credit_card').prop('disabled', true);
            $('#div_edit_process_credit_card').addClass('disabled');
            $('#edit_process_loyalty_card').prop('checked', false);
            $('#edit_process_loyalty_card').prop('disabled', true);
            $('#div_edit_process_loyalty_card').addClass('disabled');
        }
    });
    $("#edit_payment_option_credit_card").on('change', function() {
        if ($('#edit_payment_option_credit_card').is(':checked')) {
            $('#edit_process_credit_card').prop('checked', false);
            $('#edit_process_credit_card').prop('disabled', false);
            $('#div_edit_process_credit_card').removeClass('disabled');
            $('#edit_process_loyalty_card').prop('checked', false);
            $('#edit_process_loyalty_card').prop('disabled', true);
            $('#div_edit_process_loyalty_card').addClass('disabled');
        }
    });
    $("#edit_payment_option_loyalty_card").on('change', function() {
        if ($('#edit_payment_option_loyalty_card').is(':checked')) {
            $('#edit_process_credit_card').prop('checked', false);
            $('#edit_process_credit_card').prop('disabled', true);
            $('#div_edit_process_credit_card').addClass('disabled');
            $('#edit_process_loyalty_card').prop('checked', false);
            $('#edit_process_loyalty_card').prop('disabled', false);
            $('#div_edit_process_loyalty_card').removeClass('disabled');
        }
    });

    $('#add_currency_symbol').keyup(function() {
        $('#add_lbl_currency_symbol').html($('#add_currency_symbol').val());
    });

    $('#edit_currency_symbol').keyup(function() {
        $('#edit_lbl_currency_symbol').html($('#edit_currency_symbol').val());
    });

    $("#stock_adjustment_outlet_id").on('change', function() {    	
    	$("#stock_adjustment_product_id").find('option').remove().end().append('<option value="">Select Product</option>').val('').change();
    	if (this.value != ''){
    		$.ajax({
                type: 'POST',
                url: baseDir+'be/inventory/get_products_by_outlet/'+this.value,
                data:'',
                dataType: 'json',
                beforeSend: function(){
                    $("#stock_adjustment_product_loader").fadeIn("fast");
                },
                success: function(res){
                    $("#stock_adjustment_product_loader").fadeOut("fast");

                    $.each(res, function(index, element) {
                    	$("#stock_adjustment_product_id").append($("<option>").attr('value',element.product_id).text(element.product_name));

                    	if($('#sadj_detail_stock_' + element.product_id).length){
	                    	$('#sadj_detail_stock_' + element.product_id).val(element.available_stock);
							$('#sadj_span_detail_stock_' + element.product_id).html(element.available_stock);
						}

                    });
                },
                error: function(){
                    $("#stock_adjustment_product_loader").fadeOut("fast");
                }
            });
    	}
    });

    $("#stock_writeoff_outlet_id").on('change', function() {      
        $("#stock_writeoff_product_id").find('option').remove().end().append('<option value="">Select Product</option>').val('').change();
        if (this.value != ''){
            $.ajax({
                type: 'POST',
                url: baseDir+'be/inventory/get_products_by_outlet/'+this.value,
                data:'',
                dataType: 'json',
                beforeSend: function(){
                    $("#stock_writeoff_product_loader").fadeIn("fast");
                },
                success: function(res){
                    $("#stock_writeoff_product_loader").fadeOut("fast");

                    $.each(res, function(index, element) {
                        $("#stock_writeoff_product_id").append($("<option>").attr('value',element.product_id).text(element.product_name));

                        if($('#swri_detail_stock_' + element.product_id).length){
                            $('#swri_detail_stock_' + element.product_id).val(element.available_stock);
                            //$('#swri_span_detail_stock_' + element.product_id).html(element.available_stock);
                        }

                    });
                },
                error: function(){
                    $("#stock_writeoff_product_loader").fadeOut("fast");
                }
            });
        }
    });

    $("#goods_receipt_note_purchase_order_id").on('change', function() {      
        var purchase_order_id = $(this).val();
        if (purchase_order_id != ''){

            $(".grn_detail_qty").each(function() {
                var lnk = $(this);
                lnk.closest('tr').remove();
            });

            $.ajax({
                type: 'POST',
                url: baseDir+'be/inventory/get_purchase_order_details/' + purchase_order_id,
                data:'',
                dataType: 'json',
                beforeSend: function(){
                    $("#goods_receipt_note_loader").fadeIn("fast");
                },
                success: function(res){
                    $("#goods_receipt_note_loader").fadeOut("fast");

                    $.each(res, function(index, element) {

                        var product_id = element.product_id;
                        var product_variation_id = element.product_variation_id;
                        var line_id = '' + product_id + product_variation_id;
                        var qty_to_receive = element.detail_quantity - element.received_quantity;
                        var variation_description = '';
                        if (element.attributes != null && element.attributes != '') {
                            $.each(element.attributes, function(index2, element2) {
                                variation_description = variation_description + element2.product_attribute_name + ' : <b>' + element2.product_attribute_value + '</b>, ';
                            }); 
                            variation_description = '<br><i class="badge badge-mark ml-2"></i> ' + variation_description;
                            variation_description = variation_description.substring(0, variation_description.length -2);
                        }

                        $('#supplier_id').val(element.supplier_id);
                        $('#supplier_name').val(element.supplier_name);

                        $('#goods_receipt_note_details_table tr:first').before('<tr> \
                            <td>' + element.product_name + variation_description + '<br><div class="text-muted font-size-sm pt-0"><b>SKU:</b>' + element.product_sku_code + '</div> \
                                <input id="grn_detail_id_' + line_id + '" name="grn_detail_id[]" type="hidden" class="form-control grn_detail_id" value="' + line_id + '"> \
                                <input id="grn_detail_product_id_' + line_id + '" name="grn_detail_product_id[]" type="hidden" class="grn_detail_product_id" value="' + product_id + '">\
                                <input id="grn_detail_product_variation_id_' + line_id + '" name="grn_detail_product_variation_id[]" type="hidden" class="grn_detail_product_variation_id" value="' + product_variation_id + '"></td> \
                            <td>' + element.unit_name + ' (' + element.unit_code + ')<input id="grn_unit_id_' + line_id + '" name="grn_unit_id[]" type="hidden" class="form-control grn_unit_id" value="' + element.unit_id + '"></td> \
                            <td>' + element.detail_quantity + '</td> \
                            <td>' + element.received_quantity + '</td> \
                            <td><input id="grn_detail_qty_' + line_id + '" name="grn_detail_qty[]" type="number" class="form-control grn_detail_qty" required min="0.1" max="' + qty_to_receive + '" value="' + qty_to_receive + '" autocomplete="off"></td> \
                            <td><input id="grn_detail_cost_' + line_id + '" name="grn_detail_cost[]" type="number" class="form-control grn_detail_cost" required value="' + element.unit_price + '" autocomplete="off"></td> \
                            <td><span id="grn_label_detail_total_' + line_id + '">0.00</span><input id="grn_detail_total_' + line_id + '" name="grn_detail_total[]" type="hidden" class="form-control grn_detail_total" value="0"></td> \
                            <td><a href="javascript:void(0);" class="grn_detail_remove" title="Remove product"><i class="icon-cancel-circle2 text-danger"></i></a></td></tr>');
                        calculate_grn_detail_total(line_id);
                        //$("#goods_receipt_note_product_id").append($("<option>").attr('value',element.product_id).text(element.product_name));
                    });
                    calculate_grn_totals();
                },
                error: function(){
                    $("#goods_receipt_note_loader").fadeOut("fast");
                }
            });
        }
    });

    $("#add_unit_type_id").on('change', function() {      
        var unit_type_id = $(this).val();
        if (unit_type_id != ''){
            $.ajax({
                type: 'POST',
                url: baseDir+'be/units/get_add_unit_related_units/' + unit_type_id,
                data:'',
                dataType: 'json',
                beforeSend: function(){
                    $("#add_unit_loader").fadeIn("fast");
                },
                success: function(res){
                    $("#add_unit_loader").fadeOut("fast");

                    if (res.num_related_units > 0) {
                        $('#add_related_units').html(res.related_units);                        
                    } else {
                        $('#add_related_units').html('');
                    }
                },
                error: function(){
                    $("#add_unit_loader").fadeOut("fast");
                }
            });
        } else {
            $('#add_related_units').html('');
        }
    });

    $("#edit_unit_type_id").on('change', function() {      
        var unit_type_id = $(this).val();
        var unit_id = $('#edit_unit_id').val();

        if (unit_type_id != ''){
            $.ajax({
                type: 'POST',
                url: baseDir+'be/units/get_edit_unit_related_units/' + unit_type_id,
                data: { unit_id: unit_id },
                dataType: 'json',
                beforeSend: function(){
                    $("#edit_unit_loader").fadeIn("fast");
                },
                success: function(res){
                    $("#edit_unit_loader").fadeOut("fast");

                    if (res.num_related_units > 0) {
                        $('#edit_related_units').html(res.related_units);
                    } else {
                        $('#edit_related_units').html('');
                    }
                },
                error: function(){
                    $("#edit_unit_loader").fadeOut("fast");
                }
            });
        } else {
            $('#edit_related_units').html('');
        }
    });

    // add_product_unit_id
    $("#add_product_unit_id").on('change', function() {      
        var unit_id = $(this).val();
        var product_id = $('#product_id').val();
        if (unit_id != ''){
            $.ajax({
                type: 'POST',
                url: baseDir+'be/products/get_add_product_related_units/' + unit_id,
                data: { product_id: product_id },
                dataType: 'json',
                beforeSend: function(){
                    $("#units_loader").fadeIn("fast");
                },
                success: function(res){
                    $("#units_loader").fadeOut("fast");

                    if (res.num_related_units > 0) {
                        $('#add_related_units').html(res.related_units);
                    } else {
                        $('#add_related_units').html('');
                    }
                    $(".chk_ru_unit_id").each(function() {
                        if ($(this).is(":checked")) {
                            $(this).closest('.row').removeClass('text-muted');
                            $(this).closest('.row').find('input').prop('readonly', false);
                            $(this).closest('.row').find('input[type="number"]').prop('required',true);
                            $(this).closest('.row').find('.hid_chk_ru_unit_id').prop('disabled',true);
                        } else {
                            $(this).closest('.row').addClass('text-muted');
                            $(this).closest('.row').find('input').prop('readonly', true);
                            $(this).closest('.row').find('input[type="number"]').prop('required',false);
                            $(this).closest('.row').find('.hid_chk_ru_unit_id').prop('disabled',true);
                        }
                    });
                },
                error: function(){
                    $("#units_loader").fadeOut("fast");
                }
            });
        } else {
            $('#add_related_units').html('');
        }
    });
    $("#edit_product_unit_id").on('change', function() {      
        var unit_id = $(this).val();
        var product_id = $('#product_id').val();
        if (unit_id != ''){
            $.ajax({
                type: 'POST',
                url: baseDir+'be/products/get_edit_product_related_units/' + unit_id,
                data: { product_id: product_id },
                dataType: 'json',
                beforeSend: function(){
                    $("#units_loader").fadeIn("fast");
                },
                success: function(res){
                    $("#units_loader").fadeOut("fast");

                    if (res.num_related_units > 0) {
                        $('#add_related_units').html(res.related_units);
                    } else {
                        $('#add_related_units').html('');
                    }
                    $(".chk_ru_unit_id").each(function() {
                        if ($(this).is(":checked")) {
                            $(this).closest('.row').removeClass('text-muted');
                            $(this).closest('.row').find('input[type="number"]').prop('readonly', false);
                        } else {
                            $(this).closest('.row').addClass('text-muted');
                            $(this).closest('.row').find('input[type="number"]').prop('readonly', true);
                        }
                    });
                },
                error: function(){
                    $("#units_loader").fadeOut("fast");
                }
            });
        } else {
            $('#add_related_units').html('');
        }
    });



    $("#billing_country_id").on('change', function() {
    	$("#billing_region_loader").fadeIn("fast");
    	$("#billing_region_id")
    		.find('option')
    		.remove()
    		.end()
    		.append('<option value="">Select City</option>')
    		.val('').change()
		;
    	if (this.value != ''){
			$.ajax({
		     	url: baseDir+'be/locations/get_regions_by_country/'+this.value,
		       	type: 'POST',
		       	data: '',
		       	xhr: function() {
		       		var myXhr = $.ajaxSettings.xhr();
		       		return myXhr;
		       	},
		       	cache: false,
		       	contentType: false,
		       	processData: false,
		     	success: function (res) {
		     		try{
		     			var obj1 = res;
						// preserve newlines, etc - use valid JSON
						obj1 = obj1.replace(/\\n/g, "\\n")  
						        .replace(/\\'/g, "\\'")
						        .replace(/\\"/g, '\\"')
						        .replace(/\\&/g, "\\&")
						        .replace(/\\r/g, "\\r")
						        .replace(/\\t/g, "\\t")
						        .replace(/\\b/g, "\\b")
						        .replace(/\\f/g, "\\f");
						// remove non-printable and other non-valid JSON chars
						obj1 = obj1.replace(/[\u0000-\u0019]+/g,""); 
			     		var obj = JSON.parse(obj1);
			     		for (i=0; i< obj.length; i++){ 
         					$("#billing_region_id").append($("<option>").attr('value',obj[i]['region_id']).text(obj[i]['region_name']));
  						};	
  						$("#billing_region_id").val(cur_billing_region_id);
  						cur_billing_region_id = '';
  						$("#billing_region_loader").fadeOut("fast");
		     		}catch(err){
		     			$("#billing_region_loader").fadeOut("fast");
		     		}
		   		},
				error: function(){
					$("#billing_region_loader").fadeOut("fast");
				}
		    });
    	}else{
    		$("#billing_region_loader").fadeOut("fast");
    	}
    });

    $("#shipping_country_id").on('change', function() {
    	$("#shipping_region_loader").fadeIn("fast");
    	$("#shipping_region_id")
    		.find('option')
    		.remove()
    		.end()
    		.append('<option value="">Select City</option>')
    		.val('').change()
		;
    	if (this.value != ''){
			$.ajax({
		     	url: baseDir+'be/locations/get_regions_by_country/'+this.value,
		       	type: 'POST',
		       	data: '',
		       	xhr: function() {
		       		var myXhr = $.ajaxSettings.xhr();
		       		return myXhr;
		       	},
		       	cache: false,
		       	contentType: false,
		       	processData: false,
		     	success: function (res) {
		     		try{
		     			var obj1 = res;
						// preserve newlines, etc - use valid JSON
						obj1 = obj1.replace(/\\n/g, "\\n")  
						        .replace(/\\'/g, "\\'")
						        .replace(/\\"/g, '\\"')
						        .replace(/\\&/g, "\\&")
						        .replace(/\\r/g, "\\r")
						        .replace(/\\t/g, "\\t")
						        .replace(/\\b/g, "\\b")
						        .replace(/\\f/g, "\\f");
						// remove non-printable and other non-valid JSON chars
						obj1 = obj1.replace(/[\u0000-\u0019]+/g,""); 
			     		var obj = JSON.parse(obj1);
			     		for (i=0; i< obj.length; i++){ 
         					$("#shipping_region_id").append($("<option>").attr('value',obj[i]['region_id']).text(obj[i]['region_name']));
  						};	
  						$("#shipping_region_id").val(cur_shipping_region_id);
  						cur_shipping_region_id = '';
  						$("#shipping_region_loader").fadeOut("fast");
		     		}catch(err){
		     			$("#shipping_region_loader").fadeOut("fast");
		     		}
		   		},
				error: function(){
					$("#shipping_region_loader").fadeOut("fast");
				}
		    });
    	}else{
    		$("#shipping_region_loader").fadeOut("fast");
    	}
    });
    

    $("#add_supplier_country_id").on('change', function() {
    	$("#add_supplier_city_loader").fadeIn("fast");
    	$("#add_supplier_city_id")
    		.find('option')
    		.remove()
    		.end()
    		.append('<option value="">Select City</option>')
    		.val('').change()
		;
    	if (this.value != ''){
			$.ajax({
		     	url: baseDir+'be/locations/get_regions_by_country/'+this.value,
		       	type: 'POST',
		       	data: '',
		       	xhr: function() {
		       		var myXhr = $.ajaxSettings.xhr();
		       		return myXhr;
		       	},
		       	cache: false,
		       	contentType: false,
		       	processData: false,
		     	success: function (res) {
		     		try{
		     			var obj1 = res;
						// preserve newlines, etc - use valid JSON
						obj1 = obj1.replace(/\\n/g, "\\n")  
						        .replace(/\\'/g, "\\'")
						        .replace(/\\"/g, '\\"')
						        .replace(/\\&/g, "\\&")
						        .replace(/\\r/g, "\\r")
						        .replace(/\\t/g, "\\t")
						        .replace(/\\b/g, "\\b")
						        .replace(/\\f/g, "\\f");
						// remove non-printable and other non-valid JSON chars
						obj1 = obj1.replace(/[\u0000-\u0019]+/g,""); 
			     		var obj = JSON.parse(obj1);
			     		for (i=0; i< obj.length; i++){ 
         					$("#add_supplier_city_id").append($("<option>").attr('value',obj[i]['region_id']).text(obj[i]['region_name']));
  						};	
  						$("#add_supplier_city_loader").fadeOut("fast");
		     		}catch(err){
		     			$("#add_supplier_city_loader").fadeOut("fast");
		     			//alert(err);
		     		}
		   		},
				error: function(){
					$("#add_supplier_city_loader").fadeOut("fast");
				}
		    });
    	}else {
    		$("#add_supplier_city_loader").fadeOut("fast");
    	}
    });
    $("#edit_supplier_country_id").on('change', function() {
    	$("#edit_supplier_city_loader").fadeIn("fast");
    	$("#edit_supplier_city_id")
    		.find('option')
    		.remove()
    		.end()
    		.append('<option value="">Select City</option>')
    		.val('').change()
		;
    	if (this.value != ''){
			$.ajax({
		     	url: baseDir+'be/locations/get_regions_by_country/'+this.value,
		       	type: 'POST',
		       	data: '',
		       	xhr: function() {
		       		var myXhr = $.ajaxSettings.xhr();
		       		return myXhr;
		       	},
		       	cache: false,
		       	contentType: false,
		       	processData: false,
		     	success: function (res) {
		     		try{
		     			var obj1 = res;
						// preserve newlines, etc - use valid JSON
						obj1 = obj1.replace(/\\n/g, "\\n")  
						        .replace(/\\'/g, "\\'")
						        .replace(/\\"/g, '\\"')
						        .replace(/\\&/g, "\\&")
						        .replace(/\\r/g, "\\r")
						        .replace(/\\t/g, "\\t")
						        .replace(/\\b/g, "\\b")
						        .replace(/\\f/g, "\\f");
						// remove non-printable and other non-valid JSON chars
						obj1 = obj1.replace(/[\u0000-\u0019]+/g,""); 
			     		var obj = JSON.parse(obj1);
			     		for (i=0; i< obj.length; i++){ 
         					$("#edit_supplier_city_id").append($("<option>").attr('value',obj[i]['region_id']).text(obj[i]['region_name']));
  						};	

  						$("#edit_supplier_city_id").val(cur_city_id).trigger('change');

  						$("#edit_supplier_city_loader").fadeOut("fast");

		     		}catch(err){
		     			//alert(err);
		     			$("#edit_supplier_city_loader").fadeOut("fast");
		     		}
		   		},
				error: function(){
					$("#edit_supplier_city_loader").fadeOut("fast");
				}
		    });
    	}else{
    		$("#edit_supplier_city_loader").fadeOut("fast");
    	}
    });

    $("#home_top_product_category_id").on('change', function() {
    	$("#home_top_product_subcategory_loader").fadeIn("fast");
    	$("#home_top_product_subcategory_id")
    		.find('option')
    		.remove()
    		.end()
    		.val('').change()
		;
    	if (this.value != ''){
			$.ajax({
		     	url: baseDir+'be/product_categories/get_product_category_subcategories/'+this.value,
		       	type: 'POST',
		       	data: '',
		       	xhr: function() {
		       		var myXhr = $.ajaxSettings.xhr();
		       		return myXhr;
		       	},
		       	cache: false,
		       	contentType: false,
		       	processData: false,
		     	success: function (res) {
		     		try{
		     			var obj1 = res;
						// preserve newlines, etc - use valid JSON
						obj1 = obj1.replace(/\\n/g, "\\n")  
						        .replace(/\\'/g, "\\'")
						        .replace(/\\"/g, '\\"')
						        .replace(/\\&/g, "\\&")
						        .replace(/\\r/g, "\\r")
						        .replace(/\\t/g, "\\t")
						        .replace(/\\b/g, "\\b")
						        .replace(/\\f/g, "\\f");
						// remove non-printable and other non-valid JSON chars
						obj1 = obj1.replace(/[\u0000-\u0019]+/g,""); 
			     		var obj = JSON.parse(obj1);
			     		for (i=0; i< obj.length; i++){ 
         					$("#home_top_product_subcategory_id").append($("<option>").attr('value',obj[i]['product_category_id']).text(obj[i]['product_category_name']));
  						};	

  						//$("#edit_supplier_city_id").val(cur_city_id).trigger('change');

  						$("#home_top_product_subcategory_loader").fadeOut("fast");

		     		}catch(err){
		     			//alert(err);
		     			$("#home_top_product_subcategory_loader").fadeOut("fast");
		     		}
		   		},
				error: function(){
					$("#home_top_product_subcategory_loader").fadeOut("fast");
				}
		    });
    	}else{
    		$("#home_top_product_subcategory_loader").fadeOut("fast");
    	}
    });

    $("#edit_ht_product_category_id").on('change', function() {
    	$("#edit_home_top_product_subcategory_loader").fadeIn("fast");
    	$("#edit_home_top_product_subcategory_id")
    		.find('option')
    		.remove()
    		.end()
    		.val('').change()
		;
    	if (this.value != ''){
			$.ajax({
		     	url: baseDir+'be/product_categories/get_product_category_subcategories/'+this.value,
		       	type: 'POST',
		       	data: '',
		       	xhr: function() {
		       		var myXhr = $.ajaxSettings.xhr();
		       		return myXhr;
		       	},
		       	cache: false,
		       	contentType: false,
		       	processData: false,
		     	success: function (res) {
		     		try{
		     			var obj1 = res;
						// preserve newlines, etc - use valid JSON
						obj1 = obj1.replace(/\\n/g, "\\n")  
						        .replace(/\\'/g, "\\'")
						        .replace(/\\"/g, '\\"')
						        .replace(/\\&/g, "\\&")
						        .replace(/\\r/g, "\\r")
						        .replace(/\\t/g, "\\t")
						        .replace(/\\b/g, "\\b")
						        .replace(/\\f/g, "\\f");
						// remove non-printable and other non-valid JSON chars
						obj1 = obj1.replace(/[\u0000-\u0019]+/g,""); 
			     		var obj = JSON.parse(obj1);
			     		for (i=0; i< obj.length; i++){ 
         					$("#edit_home_top_product_subcategory_id").append($("<option>").attr('value',obj[i]['product_category_id']).text(obj[i]['product_category_name']));
  						};	

  						//$("#edit_supplier_city_id").val(cur_city_id).trigger('change');
  						$('#edit_home_top_product_subcategory_id').val(selValues).trigger('change');

  						$("#edit_home_top_product_subcategory_loader").fadeOut("fast");

		     		}catch(err){
		     			//alert(err);
		     			$("#edit_home_top_product_subcategory_loader").fadeOut("fast");
		     		}
		   		},
				error: function(){
					$("#edit_home_top_product_subcategory_loader").fadeOut("fast");
				}
		    });
    	}else{
    		$("#edit_home_top_product_subcategory_loader").fadeOut("fast");
    	}
    });

    //PURCHASE ORDER ADD    
	$(document).on('change', '.po_detail_qty, .po_detail_cost', function() {
		var element_id = $(this).attr('id');
		var test = element_id.split("_");
		var id = test[test.length - 1];
		calculate_po_detail_total(id);
		calculate_po_totals();
	});
	$(document).on('change', '#po_freight_cost', function() {
		calculate_po_totals();
	});
	$(document).on('click', '.po_detail_remove', function() {
		var lnk = $(this);
		swal({
	        text: 'Are you sure you want to remove this product?',
	        type: 'question',
	        showCancelButton: true,
	        confirmButtonText: 'Yes',
	        cancelButtonText: 'No',
	        allowOutsideClick: false
	    }).then(function(result) {
	        if (result.value) {
	            lnk.closest('tr').remove();
	            calculate_po_totals();
	        } else {}
	    });
	});
    $(document).on('change', '.po_unit_id', function(){
        if ($(this).val() != '' && $(this).val() != null){
            var line_id = $(this).find(':selected').data('line-id');
            var detail_cost = $(this).find(':selected').data('unit-price');
            $('#po_detail_cost_' + line_id).val(detail_cost);

            calculate_po_detail_total(line_id);
            calculate_po_totals();
        }
    });
    $(document).on('click', '.pa_detail_remove', function() {
        var lnk = $(this);
        swal({
            text: 'Are you sure you want to remove this unit?',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                lnk.closest('tr').remove();
                check_display_pa_units_of_measure();
            } else {}
        });
    });

    $(document).on('click', '.void-purchase-order', function(e){
        e.preventDefault();
        
        var purchase_order_id = $(this).attr("data-purchase-order-id");
        var context = $(this).attr("data-context");

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/purchase_order_void_valid/' + purchase_order_id,
            data: { context: context },
            dataType: 'json',
            beforeSend: function(){
                $("#purchase_orders_loader").fadeIn("fast");
            },
            success: function(res){
                $("#purchase_orders_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: res.message
                    });
                } else {
                    $.each(res.data, function(index, element) {
                        //$("#void_pos_payment_id").val(pos_payment_id);
                        $("#void_purchase_order_number").html(element.purchase_order_number);
                    });
                    $("#void_purchase_order_id").val(purchase_order_id);
                    $("#void_context").val(context);
                    
                    $("#purchase_order_void_reason").val('');
                    $('#modal_void_purchase_order').modal('toggle');

                }
            },
            error: function(){
                $("#purchase_orders_loader").fadeOut("fast");
            }
        });

    });

    $(document).on('click', '.void-goods-receipt-note', function(e){
        e.preventDefault();
        
        var goods_receipt_note_id = $(this).attr("data-goods-receipt-note-id");
        var context = $(this).attr("data-context");

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/goods_receipt_note_void_valid/' + goods_receipt_note_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#goods_receipt_notes_loader").fadeIn("fast");
            },
            success: function(res){
                $("#goods_receipt_notes_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: res.message
                    });
                } else {
                    $.each(res.data, function(index, element) {
                        //$("#void_pos_payment_id").val(pos_payment_id);
                        $("#void_goods_receipt_note_number").html(element.goods_receipt_note_number);
                    });
                    $("#void_goods_receipt_note_id").val(goods_receipt_note_id);
                    $("#void_context").val(context);
                    
                    $("#goods_receipt_note_void_reason").val('');
                    $('#modal_void_goods_receipt_note').modal('toggle');

                }
            },
            error: function(){
                $("#goods_receipt_notes_loader").fadeOut("fast");
            }
        });

    });

    $(document).on('click', '.void-goods-return-note', function(e){
        e.preventDefault();

        var goods_return_note_id = $(this).attr("data-goods-return-note-id");
        var context = $(this).attr("data-context");

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/goods_return_note_void_valid/' + goods_return_note_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#goods_return_notes_loader").fadeIn("fast");
            },
            success: function(res){
                $("#goods_return_notes_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: res.message
                    });
                } else {
                    $.each(res.data, function(index, element) {
                        //$("#void_pos_payment_id").val(pos_payment_id);
                        $("#void_goods_return_note_number").html(element.goods_return_note_number);
                    });
                    $("#void_goods_return_note_id").val(goods_return_note_id);
                    $("#void_context").val(context);
                    
                    $("#goods_return_note_void_reason").val('');
                    $('#modal_void_goods_return_note').modal('toggle');

                }
            },
            error: function(){
                $("#goods_return_notes_loader").fadeOut("fast");
            }
        });

    });

    $(document).on('click', '.void-stock-adjustment', function(e){
        e.preventDefault();

        var stock_adjustment_id = $(this).attr("data-stock-adjustment-id");
        var context = $(this).attr("data-context");

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/stock_adjustment_void_valid/' + stock_adjustment_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#stock_adjustments_loader").fadeIn("fast");
            },
            success: function(res){
                $("#stock_adjustments_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: res.message
                    });
                } else {
                    $.each(res.data, function(index, element) {
                        //$("#void_pos_payment_id").val(pos_payment_id);
                        $("#void_stock_adjustment_number").html(element.stock_adjustment_number);
                    });
                    $("#void_stock_adjustment_id").val(stock_adjustment_id);
                    $("#void_context").val(context);
                    
                    $("#stock_adjustment_void_reason").val('');
                    $('#modal_void_stock_adjustment').modal('toggle');

                }
            },
            error: function(){
                $("#stock_adjustments_loader").fadeOut("fast");
            }
        });

    });

    $(document).on('click', '.void-stock-transfer', function(e){
        e.preventDefault();

        var stock_transfer_id = $(this).attr("data-stock-transfer-id");
        var context = $(this).attr("data-context");

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/stock_transfer_void_valid/' + stock_transfer_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#stock_transfers_loader").fadeIn("fast");
            },
            success: function(res){
                $("#stock_transfers_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: res.message
                    });
                } else {
                    $.each(res.data, function(index, element) {
                        //$("#void_pos_payment_id").val(pos_payment_id);
                        $("#void_stock_transfer_number").html(element.stock_transfer_number);
                    });
                    $("#void_stock_transfer_id").val(stock_transfer_id);
                    $("#void_context").val(context);
                    
                    $("#stock_transfer_void_reason").val('');
                    $('#modal_void_stock_transfer').modal('toggle');

                }
            },
            error: function(){
                $("#stock_transfers_loader").fadeOut("fast");
            }
        });

    });

    $(document).on('click', '.void-stock-writeoff', function(e){
        e.preventDefault();

        var stock_writeoff_id = $(this).attr("data-stock-writeoff-id");
        var context = $(this).attr("data-context");

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/stock_writeoff_void_valid/' + stock_writeoff_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#stock_writeoffs_loader").fadeIn("fast");
            },
            success: function(res){
                $("#stock_writeoffs_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: res.message
                    });
                } else {
                    $.each(res.data, function(index, element) {
                        //$("#void_pos_payment_id").val(pos_payment_id);
                        $("#void_stock_writeoff_number").html(element.stock_writeoff_number);
                    });
                    $("#void_stock_writeoff_id").val(stock_writeoff_id);
                    $("#void_context").val(context);
                    
                    $("#stock_writeoff_void_reason").val('');
                    $('#modal_void_stock_writeoff').modal('toggle');

                }
            },
            error: function(){
                $("#stock_writeoffs_loader").fadeOut("fast");
            }
        });
    });

    $(document).on('click', '.purchase_order_record_payment', function(e){
        e.preventDefault();

        var purchase_order_id = $(this).attr("data-purchase-order-id");
        var context = $(this).attr("data-context");

        $("#purchase_order_payment_method").val('Cash').change();
        $("#payment_context").val(context);

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/purchase_order_make_payment_valid/' + purchase_order_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#purchase_orders_loader").fadeIn("fast");
            },
            success: function(res){
                $("#purchase_orders_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    swal({ type: 'warning', title: 'Error', html: res.message });
                } else {
                    $.each(res.data, function(index, element) {
                        $("#payment_header_purchase_order_number").html("[" + element.purchase_order_number + "]");
                        $("#payment_purchase_order_id").val(element.purchase_order_id);
                        $("#payment_purchase_order_number").val(element.purchase_order_number);
                        $("#div_subtotal").html(getCommaSeparatedTwoDecimalsNumber(parseFloat(element.sub_total)));
                        $("#div_overall_discount").html(getCommaSeparatedTwoDecimalsNumber(parseFloat(element.discount_amount)));
                        $("#div_freight_cost").html(getCommaSeparatedTwoDecimalsNumber(parseFloat(element.freight_cost)));
                        $("#div_total_amount").html(getCommaSeparatedTwoDecimalsNumber(parseFloat(element.total_amount)));
                        $("#div_total_paid").html(getCommaSeparatedTwoDecimalsNumber(parseFloat(element.total_paid)));

                        var payment_balance = 0;
                        // var change = 0;

                        if (parseFloat(element.total_amount) > parseFloat(element.total_paid)) {
                            payment_balance = parseFloat(element.total_amount) - parseFloat(element.total_paid);
                        }

                        // if (parseFloat(element.total_paid) > parseFloat(element.total_sale)) {
                        //     sale_change = parseFloat(element.total_paid) - parseFloat(element.total_sale);
                        // }

                        $("#div_payment_balance").html(getCommaSeparatedTwoDecimalsNumber(payment_balance));
                        // $("#div_sale_change").html(getCommaSeparatedTwoDecimalsNumber(sale_change));

                        //$("#sale_payment_amount").val(sale_payment_balance);
                        $("#purchase_order_payment_amount").val('0');
                    });
                    $('#modal_purchase_order_payment').modal('toggle');
                }
            },
            error: function(){
                $("#purchase_orders_loader").fadeOut("fast");
            }
        });
    });

    $(document).on('click', '.modify-purchase-payment', function(e){
        e.preventDefault();
        var purchase_payment_id = $(this).attr("data-purchase-payment-id");

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/purchase_payment_modify_valid/' + purchase_payment_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#purchase_orders_loader").fadeIn("fast");
            },
            success: function(res){
                $("#purchase_orders_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    swal({ type: 'warning', title: 'Error', html: res.message });
                } else {
                    $.each(res.data, function(index, element) {
                        $("#modify_purchase_payment_id").val(purchase_payment_id);
                        $("#modify_purchase_payment_method").val(element.payment_method).change();
                        $("#modify_txt_payment_method").val(element.payment_method);
                        $("#modify_purchase_payment_amount").val(element.payment_amount);
                        $("#modify_purchase_payment_reference_number").val(element.reference_number);
                        $("#modify_purchase_payment_note").val(element.payment_note);
                   });
                    $.each(res.purchase_order, function(index, element) {
                        //$("#payment_header_purchase_order_number").html("[" + element.purchase_order_number + "]");
                        $("#modify_payment_purchase_order_id").val(element.purchase_order_id);
                        $("#modify_payment_purchase_order_number").val(element.purchase_order_number);
                    
                        $('#modal_modify_purchase_payment').modal('toggle');
                    });



                }
            },
            error: function(){
                $("#purchase_orders_loader").fadeOut("fast");
            }
        });

    });

    $(document).on('click', '.void-purchase-payment', function(e){
        e.preventDefault();
        var purchase_payment_id = $(this).attr("data-purchase-payment-id");

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/purchase_payment_void_valid/' + purchase_payment_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#purchase_orders_loader").fadeIn("fast");
            },
            success: function(res){
                $("#purchase_orders_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    swal({ type: 'warning', title: 'Error', html: res.message });
                } else {

                    $("#void_purchase_payment_id").val(purchase_payment_id);
                    $("#void_reason").val('');
                    $('#modal_void_purchase_payment').modal('toggle');
                }
            },
            error: function(){
                $("#purchase_orders_loader").fadeOut("fast");
            }
        });

    });

    // lnk-notification
    $(document).on('click', '.lnk-notification', function(e){
        e.preventDefault();
        var notification_id = $(this).attr("data-notification-id");
        var href = $(this).attr('href');
        console.log(href);

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/support/update_read_notification/' + notification_id,
            data:'',
            success: function(){                
                window.location.href = href;
            },
            error: function(){
            }
        });

    });

    //GOODS RECEIPT NOTE ADD   
	$(document).on('change', '.grn_detail_qty, .grn_detail_cost', function() {
		var element_id = $(this).attr('id');
		var test = element_id.split("_");
		var id = test[test.length - 1];
		calculate_grn_detail_total(id);
		calculate_grn_totals();
	});
	$(document).on('change', '#grn_freight_cost', function() {
		calculate_grn_totals();
	});    
	$(document).on('click', '.grn_detail_remove', function() {
		var lnk = $(this);
		swal({
	        text: 'Are you sure you want to remove this product?',
	        type: 'question',
	        showCancelButton: true,
	        confirmButtonText: 'Yes',
	        cancelButtonText: 'No',
	        allowOutsideClick: false
	    }).then(function(result) {
	        if (result.value) {
	            lnk.closest('tr').remove();
	            calculate_grn_totals();
	        } else {}
	    });
	});
	
	//GOODS RETURN NOTE ADD   
	$(document).on('change', '.gren_detail_qty, .gren_detail_cost', function() {
		var element_id = $(this).attr('id');
		var test = element_id.split("_");
		var id = test[test.length - 1];
		calculate_gren_detail_total(id);
		calculate_gren_totals();
	});
	$(document).on('change', '#gren_freight_cost', function() {
		calculate_gren_totals();
	});
    $(document).on('change', '.gren_unit_id', function(){
        if ($(this).val() != '' && $(this).val() != null){
            var line_id = $(this).find(':selected').data('line-id');
            var detail_cost = $(this).find(':selected').data('unit-price');
            $('#gren_detail_cost_' + line_id).val(detail_cost);

            calculate_gren_detail_total(line_id);
            calculate_gren_totals();
        }
    });
	$(document).on('click', '.gren_detail_remove', function() {
		var lnk = $(this);
		swal({
	        text: 'Are you sure you want to remove this product?',
	        type: 'question',
	        showCancelButton: true,
	        confirmButtonText: 'Yes',
	        cancelButtonText: 'No',
	        allowOutsideClick: false
	    }).then(function(result) {
	        if (result.value) {
	            lnk.closest('tr').remove();
	            calculate_gren_totals();
	        } else {}
	    });
	});
	
	//STOCK TRANSFER ADD   
	$(document).on('change', '.stck_detail_qty, .stck_detail_cost', function() {
		var element_id = $(this).attr('id');
		var test = element_id.split("_");
		var id = test[test.length - 1];
		calculate_stck_detail_total(id);
		calculate_stck_totals();
	});
	$(document).on('change', '#stck_freight_cost', function() {
		calculate_stck_totals();
	});
	$(document).on('click', '.stck_detail_remove', function() {
		var lnk = $(this);
		swal({
	        text: 'Are you sure you want to remove this product?',
	        type: 'question',
	        showCancelButton: true,
	        confirmButtonText: 'Yes',
	        cancelButtonText: 'No',
	        allowOutsideClick: false
	    }).then(function(result) {
	        if (result.value) {
	            lnk.closest('tr').remove();
	            calculate_stck_totals();
	        } else {}
	    });
	});


	//STOCK ADJUSTMENT ADD   
	$(document).on('change', '.sadj_detail_qty, .sadj_detail_cost', function() {
		var element_id = $(this).attr('id');
		var test = element_id.split("_");
		var id = test[test.length - 1];
		calculate_sadj_detail_total(id);
		calculate_sadj_totals();
	});
	$(document).on('change', '#sadj_freight_cost', function() {
		calculate_sadj_totals();
	});
	$(document).on('click', '.sadj_detail_remove', function() {
		var lnk = $(this);
		swal({
	        text: 'Are you sure you want to remove this product?',
	        type: 'question',
	        showCancelButton: true,
	        confirmButtonText: 'Yes',
	        cancelButtonText: 'No',
	        allowOutsideClick: false
	    }).then(function(result) {
	        if (result.value) {
	            lnk.closest('tr').remove();
	            calculate_sadj_totals();
	        } else {}
	    });
	});

    //STOCK WRITEOFF ADD   
    $(document).on('change', '.swri_detail_qty, .swri_detail_cost', function() {
        var element_id = $(this).attr('id');
        var test = element_id.split("_");
        var id = test[test.length - 1];
        calculate_swri_detail_total(id);
        calculate_swri_totals();
    });
    $(document).on('change', '#swri_freight_cost', function() {
        calculate_swri_totals();
    });
    $(document).on('click', '.swri_detail_remove', function() {
        var lnk = $(this);
        swal({
            text: 'Are you sure you want to remove this product?',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                lnk.closest('tr').remove();
                calculate_swri_totals();
            } else {}
        });
    });

	$("#approve_affiliate_package_id").on('change', function() {
		$("#div_approve_affiliate_package_details").fadeOut("fast");
    	$("#approve_affiliate_package_details_loader").fadeIn("fast");

    	if (this.value != ''){
			$.ajax({
		     	url: baseDir+'be/affiliate_packages/get_affiliate_package_details/'+this.value,
		       	type: 'POST',
		       	data: '',
		     	success: function (res) {
		     		$("#div_approve_affiliate_package_details").html(res);
		     		$("#div_approve_affiliate_package_details").fadeIn("fast");
		     		$("#approve_affiliate_package_details_loader").fadeOut("fast");
		   		},
				error: function(){
					$("#approve_affiliate_package_details_loader").fadeOut("fast");
				}
		    });
    	}else{
    		$("#approve_affiliate_package_details_loader").fadeOut("fast");
    	}
    });

    $("#assign_affiliate_package_id").on('change', function() {
		$("#div_assign_affiliate_package_details").fadeOut("fast");
    	$("#assign_affiliate_package_details_loader").fadeIn("fast");

    	if (this.value != ''){
			$.ajax({
		     	url: baseDir+'be/affiliate_packages/get_affiliate_package_details/'+this.value,
		       	type: 'POST',
		       	data: '',
		     	success: function (res) {
		     		$("#div_assign_affiliate_package_details").html(res);
		     		$("#div_assign_affiliate_package_details").fadeIn("fast");
		     		$("#assign_affiliate_package_details_loader").fadeOut("fast");
		   		},
				error: function(){
					$("#assign_affiliate_package_details_loader").fadeOut("fast");
				}
		    });
    	}else{
    		$("#assign_affiliate_package_details_loader").fadeOut("fast");
    	}
    });

    $("#spo_email_account_id").on('change', function() {
        var email_account_id = $(this).val();

        if (email_account_id != '') {
            $.ajax({
                type: 'POST',
                url: baseDir + 'be/settings/get_email_account/' + email_account_id,
                data:'',
                dataType: 'json',
                beforeSend: function(){
                    $("#send_purchase_order_via_email_loader").fadeIn("fast");
                },
                success: function(res){
                    $("#send_purchase_order_via_email_loader").fadeOut("fast");

                    $.each(res, function(index, element) {
                        $("#sender_name").val(element.sender_name);
                        $("#sender_email_address").val(element.sender_email_address);
                        $("#mail_server_name").val(element.mail_server_name);
                        $("#mail_server_port").val(element.mail_server_port);
                        $("#sender_username").val(element.user_name);
                        $("#sender_password").val(element.password);

                        if (element.use_ssl == 1) {
                            $("#chk_use_ssl").prop('checked', true);
                        } else {
                            $("#chk_use_ssl").prop('checked', false);
                        }
                    });
                },
                error: function(){
                    $("#send_purchase_order_via_email_loader").fadeOut("fast");
                }
            }); 
        }
    });

    $("#sooc_email_account_id").on('change', function() {
        var email_account_id = $(this).val();

        if (email_account_id != '') {
            $.ajax({
                type: 'POST',
                url: baseDir + 'be/settings/get_email_account/' + email_account_id,
                data:'',
                dataType: 'json',
                beforeSend: function(){
                    $("#send_online_order_customer_email_loader").fadeIn("fast");
                },
                success: function(res){
                    $("#send_online_order_customer_email_loader").fadeOut("fast");

                    $.each(res, function(index, element) {
                        $("#customer_sender_name").val(element.sender_name);
                        $("#customer_sender_email_address").val(element.sender_email_address);
                        $("#customer_mail_server_name").val(element.mail_server_name);
                        $("#customer_mail_server_port").val(element.mail_server_port);
                        $("#customer_sender_username").val(element.user_name);
                        $("#customer_sender_password").val(element.password);

                        if (element.use_ssl == 1) {
                            $("#customer_chk_use_ssl").prop('checked', true);
                        } else {
                            $("#customer_chk_use_ssl").prop('checked', false);
                        }
                    });
                },
                error: function(){
                    $("#send_online_order_customer_email_loader").fadeOut("fast");
                }
            }); 
        }
    });

    $("#sooc_email_template_id").on('change', function() {
        var email_template_id = $(this).val();

        if (email_template_id != '') {
            $.ajax({
                type: 'POST',
                url: baseDir + 'be/settings/get_email_template/' + email_template_id,
                data:'',
                dataType: 'json',
                beforeSend: function(){
                    $("#send_online_order_customer_email_loader").fadeIn("fast");
                },
                success: function(res){
                    $("#send_online_order_customer_email_loader").fadeOut("fast");

                    $.each(res, function(index, element) {
                        //$("#customer_email_subject").val(element.email_template_name);
                        //$("#customer_email_message").val(element.email_template);
                        CKEDITOR.instances['customer_email_message'].setData(element.email_template);
                    });
                },
                error: function(){
                    $("#send_online_order_customer_email_loader").fadeOut("fast");
                }
            }); 
        }
    });

    $(document).on('click', '.lnk_send_purchase_order_via_email', function(){
        
        var purchase_order_id = $(this).attr("data-purchase-order-id");
        $("#send_email_purchase_order_id").val(purchase_order_id);

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/get_purchase_order/' + purchase_order_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#send_purchase_order_via_email_loader").fadeIn("fast");
            },
            success: function(res){
                $("#send_purchase_order_via_email_loader").fadeOut("fast");

                $.each(res, function(index, element) {
                    $("#recipient_email_address").val(element.email_address);
                    $("#email_subject").val('Bethany House PURCHASE ORDER [' + element.purchase_order_number + ']');
                });
            },
            error: function(){
                $("#send_purchase_order_via_email_loader").fadeOut("fast");
            }
        });
    });

    $(document).on('click', '.lnk_send_goods_receipt_note_via_email', function(){
        
        var goods_receipt_note_id = $(this).attr("data-goods-receipt-note-id");
        $("#send_email_goods_receipt_note_id").val(goods_receipt_note_id);

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/get_goods_receipt_note/' + goods_receipt_note_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#send_goods_receipt_note_via_email_loader").fadeIn("fast");
            },
            success: function(res){
                $("#send_goods_receipt_note_via_email_loader").fadeOut("fast");

                $.each(res, function(index, element) {
                    $("#recipient_email_address").val(element.email_address);
                    $("#email_subject").val('Bethany House GOODS RECEIPT NUMBER [' + element.goods_receipt_note_number + ']');
                });
            },
            error: function(){
                $("#send_goods_receipt_note_via_email_loader").fadeOut("fast");
            }
        });
    });

    $(document).on('click', '.lnk_send_goods_return_note_via_email', function(){
        
        var goods_return_note_id = $(this).attr("data-goods-return-note-id");
        $("#send_email_goods_return_note_id").val(goods_return_note_id);

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/get_goods_return_note/' + goods_return_note_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#send_goods_return_note_via_email_loader").fadeIn("fast");
            },
            success: function(res){
                $("#send_goods_return_note_via_email_loader").fadeOut("fast");

                $.each(res, function(index, element) {
                    $("#recipient_email_address").val(element.email_address);
                    $("#email_subject").val('Bethany House GOODS RETURN NUMBER [' + element.goods_return_note_number + ']');
                });
            },
            error: function(){
                $("#send_goods_return_note_via_email_loader").fadeOut("fast");
            }
        });
    });

    $(document).on('click', '.lnk_send_stock_adjustment_via_email', function(){
        
        var stock_adjustment_id = $(this).attr("data-stock-adjustment-id");
        $("#send_email_stock_adjustment_id").val(stock_adjustment_id);

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/get_stock_adjustment/' + stock_adjustment_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#send_stock_adjustment_via_email_loader").fadeIn("fast");
            },
            success: function(res){
                $("#send_stock_adjustment_via_email_loader").fadeOut("fast");

                $.each(res, function(index, element) {
                    $("#recipient_email_address").val(element.email_address);
                    $("#email_subject").val('Bethany House STOCK ADJUSTMENT NUMBER [' + element.stock_adjustment_number + ']');
                });
            },
            error: function(){
                $("#send_stock_adjustment_via_email_loader").fadeOut("fast");
            }
        });
    });

    $(document).on('click', '.lnk_send_stock_writeoff_via_email', function(){
        
        var stock_writeoff_id = $(this).attr("data-stock-writeoff-id");
        $("#send_email_stock_writeoff_id").val(stock_writeoff_id);

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/get_stock_writeoff/' + stock_writeoff_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#send_stock_writeoff_via_email_loader").fadeIn("fast");
            },
            success: function(res){
                $("#send_stock_writeoff_via_email_loader").fadeOut("fast");

                $.each(res, function(index, element) {
                    $("#recipient_email_address").val(element.email_address);
                    $("#email_subject").val('Bethany House STOCK WITEOFF [' + element.stock_writeoff_number + ']');
                });
            },
            error: function(){
                $("#send_stock_writeoff_via_email_loader").fadeOut("fast");
            }
        });
    });

    $(document).on('click', '.lnk_send_online_order_via_email', function(){
        
        var ord_order_number = $(this).attr("data-ord-order-number");
        $("#send_email_ord_order_number").val(ord_order_number);

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/sales/get_online_order/' + ord_order_number,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#send_online_order_via_email_loader").fadeIn("fast");
            },
            success: function(res){
                $("#send_online_order_via_email_loader").fadeOut("fast");

                $.each(res, function(index, element) {
                    $("#recipient_email_address").val(element.ord_shipping_email_address);
                    $("#email_subject").val('Bethany House ONLINE ORDER [' + element.ord_order_number + ']');
                });
            },
            error: function(){
                $("#send_online_order_via_email_loader").fadeOut("fast");
            }
        });
    });

    $(document).on('click', '.lnk_send_online_order_customer_email', function(){

        $('#frm_send_online_order_customer_email').each(function() {
            this.reset();
        });
        CKEDITOR.instances['customer_email_message'].setData('');
        
        var ord_order_number = $(this).attr("data-ord-order-number");
        $("#send_customer_email_ord_order_number").val(ord_order_number);


        $.ajax({
            type: 'POST',
            url: baseDir + 'be/sales/get_online_order/' + ord_order_number,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#send_online_order_customer_email_loader").fadeIn("fast");
            },
            success: function(res){
                $("#send_online_order_customer_email_loader").fadeOut("fast");

                $.each(res, function(index, element) {
                    $("#customer_recipient_email_address").val(element.email_address);
                    $("#send_customer_email_customer_id").val(element.customer_id);
                    //$("#email_subject").val('Bethany House ONLINE ORDER [' + element.ord_order_number + ']');
                });
            },
            error: function(){
                $("#send_online_order_customer_email_loader").fadeOut("fast");
            }
        });
    });

    $(document).on('click', '.lnk_dispatch_online_order', function(){
        
        var ord_order_number = $(this).attr("data-ord-order-number");
        $("#disptch_order_number").val(ord_order_number);
        $("#spn_dispatch_order_number").html(ord_order_number);
    });

    $("#assign_payment_transaction_type").on('change', function() {
        $("#div_assign_payment_transaction_ids_loader").fadeIn(500);
        $("#assign_payment_transaction_id").find('option').remove().end().append('<option value=""></option>').val('').change();
        if (this.value != ''){
            var transaction_type = this.value;
            $.ajax({
                url: baseDir+'be/payments/get_assign_paybill_payment_transactions',
                type: 'POST',
                data: { transaction_type: transaction_type},
                dataType: 'json',
                success: function (res) {
                    try{
                        $.each(res, function(index, element) {
                            if (transaction_type == 'Online Order'){
                                $("#assign_payment_transaction_id").append($("<option>").attr('value',element.ord_order_number).text(element.ord_order_number + ' [' + element.email_address + ']'));
                            } else if (transaction_type == 'Pos Order') {
                                $("#assign_payment_transaction_id").append($("<option>").attr('value',element.pos_sale_id).text('SO-' + element.pos_sale_id + ' [' + element.total_sale + ']'));
                            }
                        });
                        $("#div_assign_payment_transaction_ids_loader").fadeOut(500);
                    }catch(err){
                        $("#div_assign_payment_transaction_ids_loader").fadeOut(500);
                    }
                },
                error: function(){
                    $("#div_assign_payment_transaction_ids_loader").fadeOut(500);
                }
            });
        }else{
            $("#div_assign_payment_transaction_ids_loader").fadeOut(500);
        }
    });


    /*===========================================================
    =======================VALIDATION CODE=======================
    ============================================================*/

    //VALIDATE FRM_LOGIN
    $("#frm_login").validate({
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
            login_email_address: {
                required: true
            },
            login_password: {
                required: true
            },
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

    //VALIDATE FRM_REGISTER
    $("#frm_admin_register").validate({
        errorPlacement: function(error, element) {
            if (element.parent().parent().attr("class") == "checker" || element.parent().parent().attr("class") == "choice") {
                error.appendTo(element.parent().parent().parent().parent().parent());
            } else if (element.parent().parent().attr("class") == "checkbox" || element.parent().attr("class") == "checkbox" || element.parent().parent().attr("class") == "radio") {
                error.appendTo(element.parent().parent().parent());
            } else {
                error.insertAfter(element);
            }
        },
        rules: {
            register_first_name: {
                required: true,
                maxlength: 50
            },
            register_last_name: {
                required: true,
                maxlength: 50
            },
            register_user_name: {
                required: true,
                maxlength: 50
            },
            register_email_address: {
                required: true,
                email: true,
                maxlength: 200
            },
            register_phone_number: {
                required: true,
                maxlength: 50,
                number: true
            },
            register_password: {
                required: true
            },
            register_confirm_password: {
                required: true,
                equalTo: "#register_password"
            }

        },
        messages: {
            custom_message: {
                required: "This field is required",
                equalTo: "Please retype the correct password"
            },
            agree: "Please accept our policy",
            hiddenRecaptcha: "Please confirm that you are not a robot."
        },
        success: function(label) {
            label.parent().removeClass('error');
            label.remove();
        }
    });

    //VALIDATE FRM_STORE_INFORMATION
    $("#frm_store_information").validate({
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
            store_name: {
                required: true,
                maxlength: 200
            },
            email_address: {
                required: true,
                email: true,
                maxlength: 200
            },
            phone_number: {
                required: true,
                maxlength: 200
            },
            login_password: {
                required: true
            },
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

    //VALIDATE FRM_ADD_OUTLET
    $("#frm_add_outlet").validate({
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
            outlet_name: {
                required: true
            },
            outlet_physical_location: {
                required: true
            },
            outlet_contact_person: {
                required: true
            },
            outlet_phone_number: {
                required: true
            },
            outlet_email_address: {
                email: true
            },
            is_active: {
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

    //VALIDATE FRM_EDIT_OUTLET
    $("#frm_edit_outlet").validate({
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
            outlet_name: {
                required: true
            },
            outlet_physical_location: {
                required: true
            },
            outlet_contact_person: {
                required: true
            },
            outlet_phone_number: {
                required: true
            },
            outlet_email_address: {
                email: true
            },
            is_active: {
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

    //VALIDATE FRM_ADD_REGISTER
    $("#frm_add_register").validate({
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
            register_name: {
                required: true
            },
            first_receipt_number: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_EDIT_REGISTER
    $("#frm_edit_register").validate({
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
            register_name: {
                required: true
            },
            first_receipt_number: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_ADD_PRODUCT_CATEGORY
    $("#frm_add_product_category").validate({
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
            product_category_parent_id: {
                required: true
            },
            product_category_name: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_EDIT_PRODUCT_CATEGORY
    $("#frm_edit_product_category").validate({
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
            product_category_parent_id: {
                required: true
            },
            product_category_name: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_ADD_PRODUCT_ATTRIBUTE
    $("#frm_add_product_attribute").validate({
        ignore: [],
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
            product_attribute_name: {
                required: true
            },
            product_attribute_values: {
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

    //VALIDATE FRM_EDIT_PRODUCT_ATTRIBUTE
    $("#frm_edit_product_attribute").validate({
        ignore: [],
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
            product_attribute_name: {
                required: true
            },
            product_attribute_values: {
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


    //VALIDATE FRM_ADD_BRAND
    $("#frm_add_brand").validate({
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
            brand_name: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_EDIT_BRAND
    $("#frm_edit_brand").validate({
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
            brand_name: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_ADD_UNIT
    $("#frm_add_unit").validate({
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
            unit_type_id: {
                required: true
            },
            unit_name: {
                required: true
            },
            unit_code: {
                required: true,
                maxlength: 4
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_EDIT_UNIT
    $("#frm_edit_unit").validate({
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
            unit_type_id: {
                required: true
            },
            unit_name: {
                required: true
            },
            unit_code: {
                required: true,
                maxlength: 4
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_ADD_PRODUCT_SIZE
    $("#frm_add_product_size").validate({
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
            product_size_name: {
                required: true
            },
            product_size_code: {
                required: true,
                maxlength: 4
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_ADD_UNIT
    $("#frm_add_unit").validate({
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
            unit_name: {
                required: true
            },
            unit_code: {
                required: true,
                maxlength: 4
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_EDIT_UNIT
    $("#frm_edit_unit").validate({
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
            unit_name: {
                required: true
            },
            unit_code: {
                required: true,
                maxlength: 4
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE UNIT TYPES
    $("#frm_add_unit_type").validate({
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
            unit_type_name: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    $("#frm_edit_unit_type").validate({
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
            unit_type_name: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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


    //VALIDATE FRM_ADD_PRODUCT_SIZE
    $("#frm_add_product_size").validate({
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
            product_size_name: {
                required: true
            },
            product_size_code: {
                required: true,
                maxlength: 4
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_EDIT_PRODUCT_SIZE
    $("#frm_edit_product_size").validate({
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
            product_size_name: {
                required: true
            },
            product_size_code: {
                required: true,
                maxlength: 4
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_ADD_TAX_RATE
    $("#frm_add_tax_rate").validate({
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
            tax_rate_name: {
                required: true
            },
            tax_rate_code: {
                required: true,
                maxlength: 4
            },
            tax_rate_value: {
                required: true,
                number: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_EDIT_TAX_RATE
    $("#frm_edit_tax_rate").validate({
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
            tax_rate_name: {
                required: true
            },
            tax_rate_code: {
                required: true,
                maxlength: 4
            },
            tax_rate_value: {
                required: true,
                number: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_ADD_PAYMENT_METHOD
    $("#frm_add_payment_method").validate({
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
            payment_method_name: {
                required: true
            },
            payment_option: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_EDIT_PAYMENT_METHOD
    $("#frm_edit_payment_method").validate({
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
            payment_method_name: {
                required: true
            },
            payment_option: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_ADD_CURRENCY
    $("#frm_add_currency").validate({
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
            country_id: {
                required: true
            },
            currency_name: {
                required: true
            },
            currency_symbol: {
                required: true
            },
            exchange_rate: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_EDIT_CURRENCY
    $("#frm_edit_currency").validate({
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
            country_id: {
                required: true
            },
            currency_name: {
                required: true
            },
            currency_symbol: {
                required: true
            },
            exchange_rate: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_ADD_COUNTRY
    $("#frm_add_country").validate({
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
            country_name: {
                required: true
            },
            country_code: {
                number: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_EDIT_COUNTRY
    $("#frm_edit_country").validate({
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
            country_name: {
                required: true
            },
            country_code: {
                number: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_ADD_REGIONS
    $("#frm_add_region").validate({
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
            region_name: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_EDIT_REGIONS
    $("#frm_edit_region").validate({
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
            region_name: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_ADD_SHIPPING_ZONE
    $("#frm_add_shipping_zone").validate({
    	ignore: [],
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
            shipping_zone_name: {
                required: true
            },
            region_id: {
                required: true
            },
            shipping_method: {
                required: true
            },
            sort_key: {
                required: true
            },
            is_active: {
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

    //VALIDATE FRM_EDIT_SHIPPING_ZONE
    $("#frm_edit_shipping_zone").validate({
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
            shipping_zone_name: {
                required: true
            },
            region_id: {
                required: true
            },
            shipping_method: {
                required: true
            },
            sort_key: {
                required: true
            },
            is_active: {
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

    //VALIDATE FRM_ADD_PICKUP_LOCATION
    $("#frm_add_pickup_location").validate({
    	ignore: [],
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
        	region_id: {
                required: true
            },
            pickup_location_name: {
                required: true
            },
            pickup_location_address: {
                required: true
            },            
            opening_hours: {
                required: true
            },
            shipping_fee: {
                required: true
            },
            pickup_period: {
                required: true
            },
            sort_key: {
                required: true
            },
            is_active: {
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

    //VALIDATE FRM_EDIT_PICKUP_LOCATION
    $("#frm_edit_pickup_location").validate({
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
        	region_id: {
                required: true
            },
            pickup_location_name: {
                required: true
            },
            pickup_location_address: {
                required: true
            },            
            opening_hours: {
                required: true
            },
            shipping_fee: {
                required: true
            },
            pickup_period: {
                required: true
            },
            sort_key: {
                required: true
            },
            is_active: {
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

    //VALIDATE FRM_ADD_BANK
    $("#frm_add_bank").validate({
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
            bank_name: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_EDIT_BANK
    $("#frm_edit_bank").validate({
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
            bank_name: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_ADD_BANK_BRANCH
    $("#frm_add_bank_branch").validate({
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
            bank_branch_name: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_EDIT_BANK_BRANCH
    $("#frm_edit_bank_branch").validate({
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
            bank_branch_name: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_ADD_VOID_REASON
    $("#frm_add_void_reason").validate({
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
            void_reason: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_EDIT_VOID_REASON
    $("#frm_edit_void_reason").validate({
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
            void_reason: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_BITLY_SETTINGS
    $("#frm_bitly_settings").validate({
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
            generic_access_token: {
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

    //VALIDATE FRM_SALE_COMMENTS
    $("#frm_sale_comments").validate({
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

    //VALIDATE FRM_ADD_CREDIT_TERM
    $("#frm_add_credit_term").validate({
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
            credit_term: {
                required: true
            },
            credit_term_days: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_EDIT_CREDIT_TERM
    $("#frm_edit_credit_term").validate({
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
            credit_term: {
                required: true
            },
            credit_term_days: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

   	//VALIDATE FRM_MPESA_SETTINGS
    $("#frm_mpesa_settings").validate({
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
            consumer_key: {
                required: true
            },
            consumer_secret: {
                required: true
            },
            passkey: {
                required: true
            },
            environment: {
                required: true
            },
            short_code: {
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

    //VALIDATE FRM_PESAPAL_SETTINGS
    $("#frm_pesapal_settings").validate({
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
            consumer_key: {
                required: true
            },
            consumer_secret: {
                required: true
            },
            environment: {
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

   	//VALIDATE FRM_SMS_SETTINGS
    $("#frm_sms_settings").validate({
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
            api_username: {
                required: true
            },
            api_key: {
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

    //VALIDATE FRM_EMAIL_NOTIFICATION_SETTINGS
    $("#frm_email_notification_settings").validate({
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
            email_address: {
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


    //VALIDATE FRM_ADD_EMAIL_ACCOUNT
    $("#frm_add_email_account").validate({
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
            sender_name: {
                required: true
            },
            sender_email_address: {
                required: true,
                email: true
            },
            mail_server_name: {
                required: true
            },
            mail_server_port: {
                required: true
            },
            user_name: {
                required: true,
                email: true
            },
            password: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_EDIT_EMAIL_ACCOUNT
    $("#frm_edit_email_account").validate({
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
            sender_name: {
                required: true
            },
            sender_email_address: {
                required: true,
                email: true
            },
            mail_server_name: {
                required: true
            },
            mail_server_port: {
                required: true
            },
            user_name: {
                required: true,
                email: true
            },
            password: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_ADD_EMAIL_TEMPLATE
    $("#frm_add_email_template").validate({
        errorPlacement: function(error, element) {
            if (element.parent().parent().attr("class") == "checker" || element.parent().parent().attr("class") == "choice") {
                error.appendTo(element.parent().parent().parent().parent().parent());
            } else if (element.parent().parent().attr("class") == "checkbox" || element.parent().parent().attr("class") == "radio") {
                error.appendTo(element.parent().parent().parent());
            } else {
                error.insertAfter(element);
            }
        },
        ignore: [],
        rules: {
            email_template_name: {
                required: true
            },
            email_template: {
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

    //VALIDATE FRM_EDIT_EMAIL_TEMPLATE
    $("#frm_edit_email_template").validate({
        errorPlacement: function(error, element) {
            if (element.parent().parent().attr("class") == "checker" || element.parent().parent().attr("class") == "choice") {
                error.appendTo(element.parent().parent().parent().parent().parent());
            } else if (element.parent().parent().attr("class") == "checkbox" || element.parent().parent().attr("class") == "radio") {
                error.appendTo(element.parent().parent().parent());
            } else {
                error.insertAfter(element);
            }
        },
        ignore: [],
        rules: {
            email_template_name: {
                required: true
            },
            email_template: {
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

    //VALIDATE FRM_ADD_SYSTEM_USER
    $("#frm_add_system_user").validate({
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
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            user_name: {
                required: true
            },
            user_role_id: {
                required: true
            },
            user_password: {
                required: true
            },
            confirm_password: {
                required: true,
                equalTo: "#add_user_password"
            },
            email_address: {
                required: true,
                email: true
            },
            phone_number: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_EDIT_SYSTEM_USER
    $("#frm_edit_system_user").validate({
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
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            user_name: {
                required: true
            },
            user_role_id: {
                required: true
            },
            email_address: {
                required: true,
                email: true
            },
            phone_number: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_EDIT_PROFILE
    $("#frm_edit_profile").validate({
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
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            email_address: {
                required: true,
                email: true
            },
            phone_number: {
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

    //VALIDATE FRM_PROFILE_CHANGE_PASSWORD
    $("#frm_profile_change_password").validate({
        errorPlacement: function(error, element) {
            if (element.parent().parent().attr("class") == "checker" || element.parent().parent().attr("class") == "choice") {
                error.appendTo(element.parent().parent().parent().parent().parent());
            } else if (element.parent().parent().attr("class") == "checkbox" || element.parent().attr("class") == "checkbox" || element.parent().parent().attr("class") == "radio") {
                error.appendTo(element.parent().parent().parent());
            } else {
                error.insertAfter(element);
            }
        },
        rules: {
            old_password: {
                required: true
            },
            new_password: {
                required: true
            },
            confirm_password: {
                required: true,
                equalTo: "#new_password"
            }

        },
        messages: {
            custom_message: {
                required: "This field is required",
                equalTo: "Please retype the correct password"
            },
            agree: "Please accept our policy",
            hiddenRecaptcha: "Please confirm that you are not a robot."
        },
        success: function(label) {
            label.parent().removeClass('error');
            label.remove();
        }
    });

    //VALIDATE FRM_SYSTEM_USER_CHANGE_PASSWORD
    $("#frm_system_user_change_password").validate({
        errorPlacement: function(error, element) {
            if (element.parent().parent().attr("class") == "checker" || element.parent().parent().attr("class") == "choice") {
                error.appendTo(element.parent().parent().parent().parent().parent());
            } else if (element.parent().parent().attr("class") == "checkbox" || element.parent().attr("class") == "checkbox" || element.parent().parent().attr("class") == "radio") {
                error.appendTo(element.parent().parent().parent());
            } else {
                error.insertAfter(element);
            }
        },
        rules: {
            user_password: {
                required: true
            },
            confirm_password: {
                required: true,
                equalTo: "#change_user_password"
            }

        },
        messages: {
            custom_message: {
                required: "This field is required",
                equalTo: "Please retype the correct password"
            },
            agree: "Please accept our policy",
            hiddenRecaptcha: "Please confirm that you are not a robot."
        },
        success: function(label) {
            label.parent().removeClass('error');
            label.remove();
        }
    });

    //VALIDATE FRM_ADD_USER_ROLE
    $("#frm_add_user_role").validate({
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
            user_role_name: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_EDIT_USER_ROLE
    $("#frm_edit_user_role").validate({
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
            user_role_name: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_ADD_PRODUCT
    $("#frm_add_product").validate({
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
            product_name: {
                required: true
            },
            regular_price: {
                required: true
            },
            product_type: {
                required: true
            },
            tax_rate_id: {
                required: true
            },
            'product_category_id[]': {
                required: true
            },
            is_online: {
                required: true
            },
            is_featured: {
                required: true
            },
            is_new_arrival: {
                required: true
            },
            is_special_offer: {
                required: true
            },
            is_deal_of_the_week: {
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

    //VALIDATE FRM_EDIT_PRODUCT
    $("#frm_edit_product").validate({
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
            product_name: {
                required: true
            },
            regular_price: {
                required: true
            },
            unit_id: {
                required: true
            },
            tax_rate_id: {
                required: true
            },
            'product_category_id[]': {
                required: true
            },
            is_online: {
                required: true
            },
            is_featured: {
                required: true
            },
            is_new_arrival: {
                required: true
            },
            is_special_offer: {
                required: true
            },
            is_deal_of_the_week: {
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

    //VALIDATE FRM_PA_UNIT_OF_MEASURE   
    // input-group
    $("#frm_pa_unit_of_measure").validate({
        errorPlacement: function(error, element) {
            if (element.parent().parent().attr("class") == "checker" || element.parent().parent().attr("class") == "choice") {
                error.appendTo(element.parent().parent().parent().parent().parent());
            } else if (element.parent().parent().attr("class") == "checkbox" || element.parent().parent().attr("class") == "radio") {
                error.appendTo(element.parent().parent().parent());
            } else if (element.parent().attr("class") == "input-group") {
                error.appendTo(element.parent().parent());
            } else {
                error.insertAfter(element);
            }
        },
        rules: {
            pa_unit_id: {
                required: true
            },
            pa_regular_price: {
                required: true,
                number: true
            },
            pa_sale_price: {
                number: true
            },
            pa_minimum_selling_price: {
                number: true
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

    //VALIDATE FRM_PRODUCT_EDIT_OTHER_IMAGE
    $("#frm_product_edit_other_image").validate({
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
            product_edit_other_image: {
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

    //VALIDATE FRM_PRODUCT_ADD_OTHER_IMAGE
    $("#frm_product_add_other_image").validate({
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
            product_add_other_image: {
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

    //VALIDATE FRM_ADD_CUSTOMER_GROUP
    $("#frm_add_customer_group").validate({
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
            customer_group_name: {
                required: true
            },
            is_active: {
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

    //VALIDATE FRM_EDIT_CUSTOMER_GROUP
    $("#frm_edit_customer_group").validate({
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
            customer_group_name: {
                required: true
            },
            is_active: {
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

    //VALIDATE FRM_ADD_CUSTOMER
    $("#frm_add_customer").validate({
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
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            phone_number: {
                required: true
            },
            email_address: {
                required: true,
                email: true
            },
            password: {
                required: true
            },
            confirm_password: {
                required: true,
                equalTo: "#password"
            },
            is_active: {
                required: true
            },
            sort_key: {
                required: true
            },
            opening_balance: {
                required: true
            },
            credit_limit: {
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

    //VALIDATE FRM_EDIT_CUSTOMER
    $("#frm_edit_customer").validate({
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
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            phone_number: {
                required: true
            },
            email_address: {
                required: true,
                email: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_ADD_SUPPLIER
    $("#frm_add_supplier").validate({
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
            supplier_name: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_EDIT_SUPPLIER
    $("#frm_edit_supplier").validate({
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
            supplier_name: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

   	//VALIDATE FRM_EDIT_PREFIX
    $("#frm_edit_prefix").validate({
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
            prefix_name: {
                required: true
            },
            document_name: {
                required: true
            },
            current_value: {
                required: true,
                number: true
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

    //VALIDATE FRM_ADD_PURCHASE_ORDER
    $("#frm_add_purchase_order").validate({
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
            supplier_id: {
                required: true
            },
            purchase_order_date: {
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
    //VALIDATE FRM_EDIT_PURCHASE_ORDER
    $("#frm_edit_purchase_order").validate({
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
            supplier_id: {
                required: true
            },
            purchase_order_date: {
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

    //VALIDATE FRM_RECORD_PURCHASE_ORDER_PAYMENT
    $("#frm_record_purchase_order_payment").validate({
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
            payment_method: {
                required: true
            },
            payment_amount: {
                required: true,
                min: 1,
                number: true
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

    //VALIDATE FRM_VOID_PURCHASE_PAYMENT
    $("#frm_void_purchase_payment").validate({
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
            void_reason: {
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

    //FRM_MODIFY_PURCHASE_PAYMENT
    $("#frm_modify_purchase_payment").validate({
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
            payment_method: {
                required: true
            },
            payment_amount: {
                required: true,
                min: 1,
                number: true
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

    //VALIDATE FRM_ADD_GOODS_RECEIPT_NOTE
    $("#frm_add_goods_receipt_note").validate({
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
            },
            supplier_name: {
                required: true
            },
            receival_date: {
                required: true
            },
            purchase_order_id: {
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
    
    //VALIDATE FRM_EDIT_GOODS_RECEIPT_NOTE
    $("#frm_edit_goods_receipt_note").validate({
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
            },
            receival_date: {
                required: true
            },
            purchase_order_id: {
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

    //VALIDATE FRM_ADD_GOODS_RETURN_NOTE
    $("#frm_add_goods_return_note").validate({
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
            },
            supplier_id: {
                required: true
            },
            return_date: {
                required: true
            },
            return_reason: {
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

    //VALIDATE FRM_EDIT_GOODS_RETURN_NOTE
    $("#frm_edit_goods_return_note").validate({
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
            },
            supplier_id: {
                required: true
            },
            return_date: {
                required: true
            },
            return_reason: {
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

    //VALIDATE FRM_ADD_STOCK_TRANSFER
    $("#frm_add_stock_transfer").validate({
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
            source_outlet_id: {
                required: true
            },
            destination_outlet_id: {
                required: true
            },
            transfer_date: {
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

    //VALIDATE FRM_EDIT_STOCK_TRANSFER
    $("#frm_edit_stock_transfer").validate({
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
            source_outlet_id: {
                required: true
            },
            destination_outlet_id: {
                required: true
            },
            transfer_date: {
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

    //VALIDATE FRM_ADD_STOCK_ADJUSTMENT
    $("#frm_add_stock_adjustment").validate({
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
            },
            adjustment_date: {
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

    //VALIDATE FRM_EDIT_STOCK_ADJUSTMENT
    $("#frm_edit_stock_adjustment").validate({
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
            },
            adjustment_date: {
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

    //VALIDATE FRM_ADD_STOCK_WRITEOFF
    $("#frm_add_stock_writeoff").validate({
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
            },
            writeoff_date: {
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

    //VALIDATE FRM_EDIT_STOCK_WRITEOFF
    $("#frm_edit_stock_writeoff").validate({
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
            },
            writeoff_date: {
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

   	//VALIDATE FRM_ADD_HOME_SLIDERS
    $("#frm_add_home_slider").validate({
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
            slider_image: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_EDIT_HOME_SLIDERS
    $("#frm_edit_home_slider").validate({
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
            is_active: {
                required: true
            },
            sort_key: {
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

   	//VALIDATE FRM_ADD_HOME_PROMO_BANNER
    $("#frm_add_home_promo_banner").validate({
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
            promo_banner: {
                required: true
            },
            is_active: {
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

    //VALIDATE FRM_EDIT_HOME_PROMO_BANNER
    $("#frm_edit_home_promo_banner").validate({
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
            is_active: {
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

    //VALIDATE FRM_ADD_HOME_TOP_PRODUCT_CATEGORY
    $("#frm_add_home_top_product_category").validate({
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
            home_top_product_category_id: {
                required: true
            },
            position: {
                required: true
            },
            'home_top_product_subcategory_id[]': {
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

    //VALIDATE FRM_EDIT_HOME_TOP_PRODUCT_CATEGORY
    $("#frm_edit_home_top_product_category").validate({
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
            ht_product_category_id: {
                required: true
            },
            position: {
                required: true
            },
            'home_top_product_subcategory_id[]': {
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

        //VALIDATE FRM_ADD_TESTIMONIAL
    $("#frm_add_testimonial").validate({
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
            testimonial_name: {
                required: true
            },
            testimonial_title: {
                required: true
            },
            testimonial_description: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_EDIT_TESTIMONIAL
    $("#frm_edit_testimonial").validate({
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
            testimonial_name: {
                required: true
            },
            testimonial: {
                required: true
            },
            testimonial_description: {
                required: true
            },
            is_active: {
                required: true
            },
            sort_key: {
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

        //VALIDATE FRM_ADD_BLOG_CATEGORY
    $("#frm_add_blog_category").validate({
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
            blog_category_name: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_EDIT_BLOG_CATEGORY
    $("#frm_edit_blog_category").validate({
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
            blog_category_name: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_ADD_BLOG_ARTICLE
    $("#frm_add_blog_article").validate({
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
            blog_article_title: {
                required: true
            },
            blog_article_date: {
                required: true
            },
            blog_article_author: {
                required: true
            }, 
            is_published: {
                required: true
            },
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

    //VALIDATE FRM_EDIT_BLOG_ARTICLE
    $("#frm_edit_blog_article").validate({
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
            blog_article_title: {
                required: true
            },
            blog_article_date: {
                required: true
            },
            blog_article_author: {
                required: true
            }, 
            blog_article_content: {
                required: true
            },
            is_published: {
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

    //VALIDATE FRM_ABOUT_US
    $("#frm_about_us").validate({
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

    //VALIDATE FRM_SEO
    $("#frm_seo").validate({
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

    //VALIDATE FRM_TERMS_AND_CONDITIONS
    $("#frm_terms_and_conditions").validate({
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

    //VALIDATE FRM_PRIVACY_POLICY   
    $("#frm_privacy_policy").validate({
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

    //VALIDATE FRM_RETURN_POLICY   
    $("#frm_return_policy").validate({
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

    //HOW TO SHOP
    $("#frm_how_to_shop").validate({
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

    //VALIDATE FRM_ADD_FAQ
    $("#frm_add_faq").validate({
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
            faq_heading: {
                required: true
            },
            faq_description: {
                required: true
            },
            sort_key: {
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

    $("#frm_edit_faq").validate({
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
            faq_heading: {
                required: true
            },
            faq_description: {
                required: true
            },
            sort_key: {
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

    //VALIDATE FRM_AFFILIATE_TERMS
    $("#frm_affiliate_terms").validate({
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

    //VALIDATE FRM_ADD_AFFILIATE
    $("#frm_add_affiliate").validate({
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
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            phone_number: {
                required: true
            },
            email_address: {
                required: true,
                email: true
            },
            physical_address: {
                required: true
            },
            town: {
                required: true
            },
            country_id: {
                required: true,
                email: true
            },
            is_active: {
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

    //VALIDATE FRM_EDIT_AFFILIATE
    $("#frm_edit_affiliate").validate({
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
            first_name: {
                required: true
            },
            last_name: {
                required: true
            },
            phone_number: {
                required: true
            },
            email_address: {
                required: true,
                email: true
            },
            physical_address: {
                required: true
            },
            town: {
                required: true
            },
            country_id: {
                required: true,
                email: true
            },
            is_active: {
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

    //VALIDATE FRM_ADD_AFFILIATE_PACKAGE
    $("#frm_add_affiliate_package").validate({
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
            affiliate_package_name: {
                required: true
            },
            affiliate_package_colour_code: {
                required: true
            },
            is_active: {
                required: true
            },
            commission: {
                required: true
            },
            minimum_pay_out: {
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

    //VALIDATE FRM_EDIT_AFFILIATE_PACKAGE
    $("#frm_edit_affiliate_package").validate({
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
            affiliate_package_name: {
                required: true
            },
            affiliate_package_colour_code: {
                required: true
            },
            is_active: {
                required: true
            },
            commission: {
                required: true
            },
            minimum_pay_out: {
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

    //VALIDATE FRM_APPROVE_AFFILIATE
    $("#frm_approve_affiliate").validate({
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
            affiliate_package_id: {
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

    //VALIDATE FRM_ASSIGN_AFFILIATE
    $("#frm_assign_affiliate").validate({
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
            affiliate_package_id: {
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

        //VALIDATE FRM_ADD_PROMO_CODE
    $("#frm_add_promo_code").validate({
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
            promo_code_name: {
                required: true
            },
            promo_code: {
                required: true
            },
            promo_mode: {
                required: true
            },
            promo_value: {
                required: true
            },
            sort_key: {
                required: true
            },
            is_active: {
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

    //VALIDATE FRM_EDIT_PROMO_CODE
    $("#frm_edit_promo_code").validate({
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
            promo_code_name: {
                required: true
            },
            promo_code: {
                required: true
            },
            promo_mode: {
                required: true
            },
            promo_value: {
                required: true
            },
            sort_key: {
                required: true
            },
            is_active: {
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

    //FRM_SEND_PURCHASE_ORDER_VIA_EMAIL
    $("#frm_send_purchase_order_via_email").validate({
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
            sender_name: {
                required: true
            },
            sender_email_address: {
                required: true,
                email: true
            },
            sender_name: {
                required: true
            },
            mail_server_name: {
                required: true
            },
            mail_server_port: {
                required: true,
                number: true
            },
            sender_username: {
                required: true
            },
            sender_password: {
                required: true
            },
            recipient_email_address: {
                required: true,
                email: true
            },
            email_subject: {
                required: true
            },
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

    //FRM_SEND_GOODS_RECEIPT_NOTE_VIA_EMAIL
    $("#frm_send_goods_receipt_note_via_email").validate({
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
            sender_name: {
                required: true
            },
            sender_email_address: {
                required: true,
                email: true
            },
            sender_name: {
                required: true
            },
            mail_server_name: {
                required: true
            },
            mail_server_port: {
                required: true,
                number: true
            },
            sender_username: {
                required: true
            },
            sender_password: {
                required: true
            },
            recipient_email_address: {
                required: true,
                email: true
            },
            email_subject: {
                required: true
            },
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

    //FRM_SEND_GOODS_RETURN_NOTE_VIA_EMAIL
    $("#frm_send_goods_return_note_via_email").validate({
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
            sender_name: {
                required: true
            },
            sender_email_address: {
                required: true,
                email: true
            },
            sender_name: {
                required: true
            },
            mail_server_name: {
                required: true
            },
            mail_server_port: {
                required: true,
                number: true
            },
            sender_username: {
                required: true
            },
            sender_password: {
                required: true
            },
            recipient_email_address: {
                required: true,
                email: true
            },
            email_subject: {
                required: true
            },
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

    //FRM_SEND_STOCK_ADJUSTMENT_VIA_EMAIL
    $("#frm_send_stock_adjustment_via_email").validate({
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
            sender_name: {
                required: true
            },
            sender_email_address: {
                required: true,
                email: true
            },
            sender_name: {
                required: true
            },
            mail_server_name: {
                required: true
            },
            mail_server_port: {
                required: true,
                number: true
            },
            sender_username: {
                required: true
            },
            sender_password: {
                required: true
            },
            recipient_email_address: {
                required: true,
                email: true
            },
            email_subject: {
                required: true
            },
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

    //FRM_SEND_STOCK_WRITEOFF_VIA_EMAIL
    $("#frm_send_stock_writeoff_via_email").validate({
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
            sender_name: {
                required: true
            },
            sender_email_address: {
                required: true,
                email: true
            },
            sender_name: {
                required: true
            },
            mail_server_name: {
                required: true
            },
            mail_server_port: {
                required: true,
                number: true
            },
            sender_username: {
                required: true
            },
            sender_password: {
                required: true
            },
            recipient_email_address: {
                required: true,
                email: true
            },
            email_subject: {
                required: true
            },
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

    //FRM_SEND_STOCK_TRANSFER_VIA_EMAIL
    $("#frm_send_stock_transfer_via_email").validate({
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
            sender_name: {
                required: true
            },
            sender_email_address: {
                required: true,
                email: true
            },
            sender_name: {
                required: true
            },
            mail_server_name: {
                required: true
            },
            mail_server_port: {
                required: true,
                number: true
            },
            sender_username: {
                required: true
            },
            sender_password: {
                required: true
            },
            recipient_email_address: {
                required: true,
                email: true
            },
            email_subject: {
                required: true
            },
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

    //FRM_SEND_ONLINE_ORDER_VIA_EMAIL
    $("#frm_send_online_order_via_email").validate({
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
            sender_name: {
                required: true
            },
            sender_email_address: {
                required: true,
                email: true
            },
            sender_name: {
                required: true
            },
            mail_server_name: {
                required: true
            },
            mail_server_port: {
                required: true,
                number: true
            },
            sender_username: {
                required: true
            },
            sender_password: {
                required: true
            },
            recipient_email_address: {
                required: true,
                email: true
            },
            email_subject: {
                required: true
            },
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

    //FRM_SEND_ONLINE_ORDER_CUSTOMER_EMAIL
    $("#frm_send_online_order_customer_email").validate({
        errorPlacement: function(error, element) {
            if (element.parent().parent().attr("class") == "checker" || element.parent().parent().attr("class") == "choice") {
                error.appendTo(element.parent().parent().parent().parent().parent());
            } else if (element.parent().parent().attr("class") == "checkbox" || element.parent().parent().attr("class") == "radio") {
                error.appendTo(element.parent().parent().parent());
            } else {
                error.insertAfter(element);
            }
        },
        ignore: [],
        rules: {
            sender_name: {
                required: true
            },
            sender_email_address: {
                required: true,
                email: true
            },
            sender_name: {
                required: true
            },
            mail_server_name: {
                required: true
            },
            mail_server_port: {
                required: true,
                number: true
            },
            sender_username: {
                required: true
            },
            sender_password: {
                required: true
            },
            recipient_email_address: {
                required: true,
                email: true
            },
            email_subject: {
                required: true
            },
            email_message: {
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

    //FRM_VOID_PURCHASE_ORDER
    $("#frm_void_purchase_order").validate({
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
            void_reason: {
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

    //FRM_VOID_GOODS_RECEIPT_NOTE
    $("#frm_void_goods_receipt_note").validate({
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
            void_reason: {
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

    //FRM_VOID_RETURN_NOTE
    $("#frm_void_goods_return_note").validate({
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
            void_reason: {
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

    //FRM_VOID_STOCK_TRANSFER
    $("#frm_void_stock_transfer").validate({
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
            void_reason: {
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

    //FRM_VOID_STOCK_ADJUSTMENT
    $("#frm_void_stock_adjustment").validate({
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
            void_reason: {
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

    //FRM_VOID_STOCK_WRITEOFF
    $("#frm_void_stock_writeoff").validate({
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
            void_reason: {
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

    //FRM_PAYBILL_PAYMENT_ASSIGN_TRANSACTION
    $("#frm_paybill_payment_assign_transaction").validate({
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
            payment_reference: {
                required: true
            },
            payment_by: {
                required: true
            },
            payment_amount: {
                required: true
            },
            payment_date: {
                required: true
            },
            payment_account: {
                required: true
            },
            transaction_type: {
                required: true
            },
            transaction_id: {
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

    //FRM_SET_PRODUCT_IMAGE
    $("#frm_set_product_image").validate({
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
            product_image: {
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

    //FRM_ADD_PRODUCT_GALLERY_IMAGE
    $("#frm_add_product_gallery_image").validate({
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
            'product_gallery_image[]': {
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

    //FRM_EDIT_PRODUCT_GALLERY_IMAGE
    $("#frm_edit_product_gallery_image").validate({
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
            'product_gallery_image': {
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

    //FRM_LNM_V2_CONTACTS_RECONCILIATION
    $("#frm_lnm_v2_contacts_reconciliation").validate({
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
            'excel_file': {
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

function addRules(rulesObj){
    for (var item in rulesObj){
       $('#'+item).rules('add',rulesObj[item]);  
    } 
}

function removeRules(rulesObj){
    for (var item in rulesObj){
       $('#'+item).rules('remove');  
    } 
}

function getCommaSeparatedTwoDecimalsNumber(number) {
    const fixedNumber = Number.parseFloat(number).toFixed(2);
    return String(fixedNumber).replace(/\B(?=(\d{3})+(?!\d))/g, ",");

}

Number.prototype.formatMoney = function(decPlaces, thouSeparator, decSeparator) {
    var n = this,
        decPlaces = isNaN(decPlaces = Math.abs(decPlaces)) ? 2 : decPlaces,
        decSeparator = decSeparator == undefined ? "." : decSeparator,
        thouSeparator = thouSeparator == undefined ? "," : thouSeparator,
        sign = n < 0 ? "-" : "",
        i = parseInt(n = Math.abs(+n || 0).toFixed(decPlaces)) + "",
        j = (j = i.length) > 3 ? j % 3 : 0;
    return sign + (j ? i.substr(0, j) + thouSeparator : "") + i.substr(j).replace(/(\d{3})(?=\d)/g, "$1" + thouSeparator) + (decPlaces ? decSeparator + Math.abs(n - i).toFixed(decPlaces).slice(2) : "");
};

function nextInDOM(_selector, _subject) {
    var next = getNext(_subject);
    while(next.length != 0) {
        var found = searchFor(_selector, next);
        if(found != null) return found;
        next = getNext(next);
    }
    return null;
}
function getNext(_subject) {
    if(_subject.next().length > 0) return _subject.next();
    return getNext(_subject.parent());
}
function searchFor(_selector, _subject) {
    if(_subject.is(_selector)) return _subject;
    else {
        var found = null;
        _subject.children().each(function() {
            found = searchFor(_selector, $(this));
            if(found != null) return false;
        });
        return found;
    }
    return null; // will/should never get here
}

//VALIDATE REGISTER
function validate_admin_register() {
    $("#div_register_error").fadeOut("fast");
    $("#div_register_success").fadeOut("fast");

    if ($("#frm_admin_register").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_admin_register").html('<b><i class="fa fa-spinner fa-spin"></i></b> Processing');

        if ($valmsg != $valmsg2) {
            $("#btn_admin_register").html('<b><i class="icon-plus3"></i></b> Create Account');
            $("#div_register_error").html($valmsg);
            $("#div_register_error").fadeIn("fast");
        } else {
            $("#div_register_error").fadeOut("fast");

            var form = document.getElementById('frm_admin_register');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/auth/validate_register',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    if (res.status == 'ERR') {
                        $("#btn_admin_register").html('<b><i class="icon-plus3"></i></b> Create Account');
                        $("#div_register_error").html(res.message);
                        $("#div_register_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_register_success").html(res.message);
                        $("#div_register_success").fadeIn("fast");

                        setTimeout(function() {
                            window.location = baseDir + 'be/auth';
                        }, 2000);

                    }
                },
                error: function() {
                    $("#btn_admin_register").html('<b><i class="icon-plus3"></i></b> Create Account');
                    $("#div_register_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_register_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

//VALIDATE LOGIN
function validate_login() {
    $("#div_login_error").fadeOut("fast");
    $("#div_login_success").fadeOut("fast");

    if ($("#frm_login").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_login").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_login").html('Sign In <i class="icon-circle-right2 ml-2"></i>');
            $("#div_login_error").html($valmsg);
            $("#div_login_error").fadeIn("fast");
        } else {
            $("#div_login_error").fadeOut("fast");

            var form = document.getElementById('frm_login');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/auth/validate_login',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    if (res.status == 'ERR') {
                        $("#btn_login").html('Sign In <i class="icon-circle-right2 ml-2"></i>');
                        $("#div_login_error").html(res.message);
                        $("#div_login_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_login_success").html(res.message);
                        $("#div_login_success").fadeIn("fast");

                        setTimeout(function() {
                            window.location = baseDir + 'be';
                        }, 2000);

                    }
                },
                error: function() {
                    $("#btn_login").html('Sign In <i class="icon-circle-right2 ml-2"></i>');
                    $("#div_login_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_login_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

//GENERAL INFORMATION
function save_store_information() {
    $("#div_error").fadeOut("fast");
    $("#div_success").fadeOut("fast");

    if ($("#frm_store_information").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_store_information").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_store_information").html('<i class="icon-checkmark4"></i> SAVE STORE INFORMATION');
            $("#div_error").html($valmsg);
            $("#div_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_error").fadeOut("fast");

            var form = document.getElementById('frm_store_information');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/save_store_information',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_store_information").html('<i class="icon-checkmark4"></i> SAVE STORE INFORMATION');
                    if (res.status == 'ERR') {
                        $("#div_error").html(res.message);
                        $("#div_error").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_success").html(res.message);
                        $("#div_success").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_success').offset().top - 90
                        }, 'slow');

                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_store_information").html('<i class="icon-checkmark4"></i> SAVE STORE INFORMATION');
                    $("#div_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_error").fadeIn("fast");
                    $('html, body').animate({
                        scrollTop: $('#div_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}

//OUTLETS
function outlet_add_clear() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    $('#frm_add_outlet').each(function() {
        this.reset();
    });
}

//SAVE OUTLET
function save_outlet() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    if ($("#frm_add_outlet").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_outlet").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_outlet").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_outlet');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/save_outlet',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_outlet").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_outlet').each(function() {
                            this.reset();
                        });

                        load_outlets();

                        setTimeout(function() {
                            $("#div_add_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_add_outlet").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_outlets() {
    $("#outlets_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/settings/loadjs_outlets',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#outlets_div").html(result);

            setInterval(function() {
                $("#outlets_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#outlets_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function outlet_edit_load(outlet_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $.ajax({
        url: baseDir + 'be/settings/get_outlet2/' + outlet_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_outlet_id").val(obj['outlet_id']);
                $("#edit_outlet_name").val(obj['outlet_name']);
                $("#edit_outlet_physical_location").val(obj['outlet_physical_location']);
                $("#edit_outlet_description").val(obj['outlet_description']);
                $("#edit_outlet_contact_person").val(obj['outlet_contact_person']);
                $("#edit_outlet_phone_number").val(obj['outlet_phone_number']);
                $("#edit_outlet_email_address").val(obj['outlet_email_address']);
                $("#edit_sort_key").val(obj['sort_key']);

                if (obj['is_main'] == 1) {
                	$("#edit_is_main").attr('checked', true).change();
                }else {
                	$("#edit_is_main").removeAttr("checked").change();
                }

                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").attr('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").attr('checked', true).change();
                }

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
//UPDATE OUTLET
function update_outlet() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    if ($("#frm_edit_outlet").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_outlet").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_outlet").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_outlet');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/update_outlet',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_outlet").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        load_outlets();

                        setTimeout(function() {
                            $("#div_edit_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_edit_outlet").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_outlet(outlet_id) {
    swal({
        text: 'Do you wish to delete this Outlet?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/settings/delete_outlet/' + outlet_id,
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
                            load_outlets();
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

}

//REGISTERS
function register_add_clear() {
    $("#div_add_register_error").fadeOut("fast");
    $("#div_add_register_success").fadeOut("fast");

    $('#frm_add_register').each(function() {
        this.reset();
    });
}

//SAVE REGISTER
function save_register() {
    $("#div_add_register_error").fadeOut("fast");
    $("#div_add_register_success").fadeOut("fast");

    if ($("#frm_add_register").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_register").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_register").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_register_error").html($valmsg);
            $("#div_add_register_error").fadeIn("fast");
        } else {
            $("#div_add_register_error").fadeOut("fast");

            var form = document.getElementById('frm_add_register');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/outlets/save_register',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_register").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_register_error").html(res.message);
                        $("#div_add_register_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_register_success").html(res.message);
                        $("#div_add_register_success").fadeIn("fast");

                        $('#frm_add_register').each(function() {
                            this.reset();
                        });

                        load_registers();
                    }
                },
                error: function() {
                    $("#btn_add_register").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_register_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_register_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_registers() {
    $("#registers_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    var outlet_id = $("#outlet_id").val();
    $.ajax({
        url: baseDir + 'be/outlets/load_registers_js/' + outlet_id,
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#registers_div").html(result);

            setInterval(function() {
                $("#registers_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#registers_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function register_edit_load(register_id) {
    $("#div_edit_register_error").fadeOut("fast");
    $("#div_edit_register_success").fadeOut("fast");

    $('#frm_edit_register').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/outlets/get_register/' + register_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_register_id").val(obj['register_id']);
                $("#edit_register_name").val(obj['register_name']);
                $("#edit_register_code").val(obj['register_code']);
                $("#edit_first_receipt_number").val(obj['first_receipt_number']);
                $("#edit_receipt_number_prefix").val(obj['receipt_number_prefix']);
                $("#edit_sort_key").val(obj['sort_key']);
                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").prop('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").prop('checked', true).change();
                }

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
//UPDATE REGISTER
function update_register() {
    $("#div_edit_register_error").fadeOut("fast");
    $("#div_edit_register_success").fadeOut("fast");

    if ($("#frm_edit_register").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_register").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_register").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_register_error").html($valmsg);
            $("#div_edit_register_error").fadeIn("fast");
        } else {
            $("#div_edit_register_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_register');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/outlets/update_register',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_register").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_register_error").html(res.message);
                        $("#div_edit_register_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_register_success").html(res.message);
                        $("#div_edit_register_success").fadeIn("fast");

                        load_registers();
                    }
                },
                error: function() {
                    $("#btn_edit_register").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_register_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_register_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_register(register_id) {
    swal({
        text: 'Do you wish to delete this Register?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/outlets/delete_register/' + register_id,
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
                            load_registers();
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
}

//PRODUCT CATEGORIES
function save_product_category() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    CKEDITOR.instances['add_description'].updateElement();

    if ($("#frm_add_product_category").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_product_category").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_product_category").html('<i class="icon-checkmark4"></i> SAVE CATEGORY');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_product_category');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/product_categories/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_product_category").html('<i class="icon-checkmark4"></i> SAVE CATEGORY');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_product_category').each(function() {
                            this.reset();
                        });
                        CKEDITOR.instances['add_description'].setData('');                        
                        load_select_product_categories("#product_category_parent_id");
                        $("#product_category_parent_id").val('0').change();
                        $("#icon_id").val('').change();

                        load_product_categories();

                        setTimeout(function() {
                            $("#div_add_success").fadeOut("fast");
                        }, 3000);

                        // $('html, body').animate({
                        //     scrollTop: $('#div_add_success').offset().top - 90
                        // }, 'slow');
                    }
                },
                error: function() {
                    $("#btn_add_product_category").html('<i class="icon-checkmark4"></i> SAVE CATEGORY');
                    $("#div_add_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                    $('html, body').animate({
                        scrollTop: $('#div_add_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}

function load_product_categories() {
    $("#product_categories_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/product_categories/loadjs',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#product_categories_div").html(result);

            setInterval(function() {
                $("#product_categories_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#product_categories_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}
function load_select_product_categories(select_id) {
    var level_count = 0;
    $(select_id).find('option').remove().end().append('<option value="0">None</option>').val('').change();
    $.ajax({
        url: baseDir+'be/product_categories/get_select_product_categories',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function (res) {
            try{
                json_data = JSON.parse(res);
                var x = 0;
                var selectList =$(select_id);

                $.each(json_data, function (key,val) {
                    var $newElem;
                    $newElem = $(document.createElement('option')).attr('value', val.product_category_id).attr('label', val.product_category_name).html(val.product_category_name).appendTo(selectList.last());

                    if (val.sub.length > 0) {
                        fetch_sub_categories(val.sub, level_count, selectList);
                    }
                });
            }catch(err){
            }
        },
        error: function(){
        }
    });
}

function fetch_sub_categories (sub_categories, level_count, selectList) {
    level_count = level_count + 1;
    $.each(sub_categories, function (key,val) {
        var $newElem;
        var mdash = '';
        var nspace = '';
        for (i = 0; i < level_count; i++) {
            mdash = mdash + "&mdash;";
            nspace = nspace + "&nbsp;&nbsp;";
        }
        $newElem = $(document.createElement('option')).attr('value', val.product_category_id).attr('label', nspace + mdash + ' ' + val.product_category_name).html(nspace + mdash + ' ' + val.product_category_name).appendTo(selectList.last());
        
        if (val.sub.length > 0) {
            fetch_sub_categories(val.sub, level_count, selectList);
        }
    });

}

function update_product_category() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    CKEDITOR.instances['edit_description'].updateElement();

    if ($("#frm_edit_product_category").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_product_category").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        var product_category_parent_id = $("#product_category_parent_id").val();
        var product_category_id = $("#product_category_id").val();

        if (product_category_parent_id == product_category_id) {
            $valmsg = $valmsg + "<i class='fa fa-exclamation-circle'></i> Category cannot be it's own parent category. Please select a different parent category. <br/>";
        }

        if ($valmsg != $valmsg2) {
            $("#btn_edit_product_category").html('<i class="icon-checkmark4"></i> UPDATE CATEGORY');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_edit_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_product_category');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/product_categories/update',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_product_category").html('<i class="icon-checkmark4"></i> UPDATE CATEGORY');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_edit_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_edit_success').offset().top - 90
                        }, 'slow');
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_edit_product_category").html('<i class="icon-checkmark4"></i> UPDATE CATEGORY');
                    $("#div_edit_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                    $('html, body').animate({
                        scrollTop: $('#div_edit_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}

function delete_product_category(product_category_id) {
    swal({
        text: 'Do you wish to delete this product Category?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/product_categories/delete/' + product_category_id,
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
                            load_product_categories();
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

}

function delete_product_category_cover_image(product_category_id) {
    swal({
        text: 'Do you wish to delete this Cover Image? Please note that this action is irreversible.',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/product_categories/delete_cover_image/'+product_category_id,
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
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
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
}

//PRODUCT ATTRIBUTES
function save_product_attribute() {
    $("#div_add_product_attribute_error").fadeOut("fast");
    $("#div_add_product_attribute_success").fadeOut("fast");

    // CKEDITOR.instances['add_description'].updateElement();

    if ($("#frm_add_product_attribute").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_product_attribute").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_product_attribute").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_product_attribute_error").html($valmsg);
            $("#div_add_product_attribute_error").fadeIn("fast");
        } else {

            var form = document.getElementById('frm_add_product_attribute');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/products/save_attribute',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_product_attribute").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_product_attribute_error").html(res.message);
                        $("#div_add_product_attribute_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_product_attribute_success").html(res.message);
                        $("#div_add_product_attribute_success").fadeIn("fast");

                        $('#frm_add_product_attribute').each(function() {
                            this.reset();
                        });
                        $('#add_product_attribute_values').tagsinput('removeAll');

                        load_product_attributes();
                        load_product_variations();

                        setTimeout(function() {
                            $("#div_add_product_attribute_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_add_product_attribute").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_product_attribute_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_add_product_attribute_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_product_attributes() {
    $("#div_product_attributes").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    var product_id = $('#product_id').val();
    $.ajax({
        url: baseDir + 'be/products/loadjs_product_attributes',
        type: 'POST',
        data: {product_id: product_id},
        success: function(result) {
            $("#div_product_attributes").html(result);

            setInterval(function() {
                $("#div_product_attributes").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#div_product_attributes").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function update_product_attribute() {
    $("#div_edit_product_attribute_error").fadeOut("fast");
    $("#div_edit_product_attribute_success").fadeOut("fast");

    if ($("#frm_edit_product_attribute").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_product_attribute").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_product_attribute").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_product_attribute_error").html($valmsg);
            $("#div_edit_product_attribute_error").fadeIn("fast");
        } else {

            var form = document.getElementById('frm_edit_product_attribute');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/products/update_attribute',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_product_attribute").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_product_attribute_error").html(res.message);
                        $("#div_edit_product_attribute_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_product_attribute_success").html(res.message);
                        $("#div_edit_product_attribute_success").fadeIn("fast");
                        
                        load_product_attributes();
                        load_product_variations();

                        setTimeout(function() {
                            $("#div_edit_product_attribute_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_edit_product_attribute").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_product_attribute_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_edit_product_attribute_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_product_attribute(product_attribute_id) {
    swal({
        text: 'Do you wish to delete this product attribute?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/product_attributes/delete/' + product_attribute_id,
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
                            load_product_attributes();
                            load_product_variations();
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

}

//PRODUCT VARIATIONS
function save_product_variation() {
    $("#div_add_product_variation_error").fadeOut("fast");
    $("#div_add_product_variation_success").fadeOut("fast");

    if ($("#frm_add_product_variation").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_product_variation").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($(".pavi").filter(function() { return $(this).val(); }).length <= 0) {
            $valmsg = "Please select atleast one (1) attribute";
        }

        if ($valmsg != $valmsg2) {
            $("#btn_add_product_variation").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_product_variation_error").html($valmsg);
            $("#div_add_product_variation_error").fadeIn("fast");
        } else {

            var form = document.getElementById('frm_add_product_variation');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/products/save_variation',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_product_variation").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_product_variation_error").html(res.message);
                        $("#div_add_product_variation_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_product_variation_success").html(res.message);
                        $("#div_add_product_variation_success").fadeIn("fast");

                        $('#frm_add_product_variation').each(function() {
                            this.reset();
                        });

                        load_product_variations();

                        $('#modal_add_product_variation').modal('toggle');

                        setTimeout(function() {
                            $("#div_add_product_variation_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_add_product_variation").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_product_variation_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_add_product_variation_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_product_variations() {
    $("#tab-variations").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    var product_id = $('#product_id').val();
    $.ajax({
        url: baseDir + 'be/products/loadjs_product_variations',
        type: 'POST',
        data: {product_id: product_id},
        success: function(result) {
            $("#tab-variations").html(result);

            setInterval(function() {
                $("#tab-variations").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#tab-variations").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function update_product_variation() {
    $("#div_edit_product_variation_error").fadeOut("fast");
    $("#div_edit_product_variation_success").fadeOut("fast");

    if ($("#frm_edit_product_variation").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_product_variation").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($(".pavi").filter(function() { return $(this).val(); }).length <= 0) {
            $valmsg = "Please select atleast one (1) attribute";
        }

        if ($valmsg != $valmsg2) {
            $("#btn_edit_product_variation").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_product_variation_error").html($valmsg);
            $("#div_edit_product_variation_error").fadeIn("fast");
        } else {

            var form = document.getElementById('frm_edit_product_variation');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/products/update_variation',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_product_variation").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_product_variation_error").html(res.message);
                        $("#div_edit_product_variation_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_product_variation_success").html(res.message);
                        $("#div_edit_product_variation_success").fadeIn("fast");

                        load_product_variations();

                        setTimeout(function() {
                            $("#div_edit_product_variation_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_edit_product_variation").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_product_variation_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_edit_product_variation_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

//PRODUCT IMAGE
function upload_set_product_image(){
    $("#div_set_product_image_success").fadeOut("fast");
    $("#div_set_product_image_error").fadeOut("fast");

    if ($("#frm_set_product_image").valid()) {

        $product_image = $("#spi_product_image").val();

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_set_product_image").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($product_image != ""){
            $allowed_extensions = new Array("png","jpg","jpeg","gif");
            $file_extension = $product_image.split('.').pop();

            $found = false;
            for(var i = 0; i <= $allowed_extensions.length; i++){
                if($allowed_extensions[i]==$file_extension){
                    $found = true;
                    break;
                }
            }

            if ($found == false){
                $valmsg = $valmsg + "<i class='fa fa-exclamation-circle'></i> The image file you chose has an incorrect format. Only files with the following extensions are allowed: .png, .jpg, .jpeg, .gif <br/>";
            }
        }

        if ($valmsg != $valmsg2){
            $("#btn_set_product_image").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_set_product_image_error").html($valmsg);
            $("#div_set_product_image_error").fadeIn("fast");
        }else{

            var form = document.getElementById("frm_set_product_image");
            var formData = new FormData(form);

            $.ajax({
                url: baseDir+'be/products/upload_set_product_image',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function (res) {
                    $("#btn_set_product_image").html('<i class="icon-checkmark4"></i> SAVE');
                    if(res.status == 'ERR'){
                        $("#div_set_product_image_error").html(res.message);
                        $("#div_set_product_image_error").fadeIn("fast");
                    }else if (res.status == 'SUCCESS'){
                        $("#div_set_product_image_success").html(res.message);
                        $("#div_set_product_image_success").fadeIn("fast");

                        load_product_main_image();

                        $('#modal_set_product_image').modal('toggle');

                        // setTimeout(function() {
                        //     location.reload();
                        // }, 2000);
                    }
                },
                error: function(){
                    $("#btn_set_product_image").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_set_product_image_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_set_product_image_error").fadeIn("fast");
                }
            });
        }
    }
    return false; 
}

function load_product_main_image(){
    var product_id = $('#product_id').val();
    $.ajax({
        type: 'POST',
        url: baseDir + 'be/products/loadjs_product_main_image',
        data: {product_id: product_id},
        beforeSend: function(){
            $("#product_loader").fadeIn("fast");
        },
        success: function(res){
            $("#product_loader").fadeOut("fast");
            $("#div_product_main_image").html(res);
        },
        error: function(){
            $("#product_loader").fadeOut("fast");
        }
    });
}

//PRODUCT GALLERY IMAGES
function upload_add_product_gallery_image() {
    $("#div_add_product_gallery_image_success").fadeOut("fast");
    $("#div_add_product_gallery_image_error").fadeOut("fast");

    if ($("#frm_add_product_gallery_image").valid()) {

        var product_image = $("#apgi_product_gallery_image");

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_product_gallery_image").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        var found = false;
        for (var i = 0; i < product_image.get(0).files.length; ++i) {
            var file1 = product_image.get(0).files[i].name;
            var ext = file1.split('.').pop().toLowerCase();                            
            
            if($.inArray(ext,['png','jpg','jpeg','gif'])===-1){
                found = true;
                break;
            }
        }
        if (found == true){
            $valmsg = $valmsg + "<i class='fa fa-exclamation-circle'></i> <b>Invalid Extension.</b> Only files with the following extensions are allowed: .png, .jpg, .jpeg, .gif <br/>";
        }
        if ($valmsg != $valmsg2){
            $("#btn_add_product_gallery_image").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_product_gallery_image_error").html($valmsg);
            $("#div_add_product_gallery_image_error").fadeIn("fast");
        }else{

            var form = document.getElementById("frm_add_product_gallery_image");
            var formData = new FormData(form);

            $.ajax({
                url: baseDir+'be/products/upload_add_product_gallery_image',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function (res) {
                    $("#btn_add_product_gallery_image").html('<i class="icon-checkmark4"></i> SAVE');
                    if(res.status == 'ERR'){
                        $("#div_add_product_gallery_image_error").html(res.message);
                        $("#div_add_product_gallery_image_error").fadeIn("fast");
                    }else if (res.status == 'SUCCESS'){
                        $("#div_add_product_gallery_image_success").html(res.message);
                        $("#div_add_product_gallery_image_success").fadeIn("fast");

                        load_product_gallery_images();

                        $('#modal_add_product_gallery_image').modal('toggle');

                        // setTimeout(function() {
                        //     location.reload();
                        // }, 2000);
                    }
                },
                error: function(){
                    $("#btn_add_product_gallery_image").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_product_gallery_image_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_product_gallery_image_error").fadeIn("fast");
                }
            });
        }
    }
    return false;
}

function load_product_gallery_images() {
    var product_id = $('#product_id').val();
    $.ajax({
        type: 'POST',
        url: baseDir + 'be/products/loadjs_product_gallery_images',
        data: {product_id: product_id},
        beforeSend: function(){
            $("#product_loader").fadeIn("fast");
        },
        success: function(res){
            $("#product_loader").fadeOut("fast");
            $("#div_product_gallery_images").html(res);
        },
        error: function(){
            $("#product_loader").fadeOut("fast");
        }
    });
}

function upload_edit_product_gallery_image(){

    $("#div_edit_product_gallery_image_success").fadeOut("fast");
    $("#div_edit_product_gallery_image_error").fadeOut("fast");

    if ($("#frm_edit_product_gallery_image").valid()) {

        $product_gallery_image = $("#epgi_product_gallery_image").val();

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_product_gallery_image").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($product_gallery_image != ""){
            $allowed_extensions = new Array("png","jpg","jpeg","gif");
            $file_extension = $product_gallery_image.split('.').pop();

            $found = false;
            for(var i = 0; i <= $allowed_extensions.length; i++){
                if($allowed_extensions[i]==$file_extension){
                    $found = true;
                    break;
                }
            }

            if ($found == false){
                $valmsg = $valmsg + "<i class='fa fa-exclamation-circle'></i> The file you chose has an incorrect format. Only files with the following extensions are allowed: .png, .jpg, .jpeg, .gif <br/>";
            }
        }

        if ($valmsg != $valmsg2){
            $("#btn_edit_product_gallery_image").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_edit_product_gallery_image_error").html($valmsg);
            $("#div_edit_product_gallery_image_error").fadeIn("fast");
        }else{

            var form = document.getElementById("frm_edit_product_gallery_image");
            var formData = new FormData(form);

            $.ajax({
                url: baseDir+'be/products/upload_edit_product_gallery_image',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function (res) {
                    $("#btn_edit_product_gallery_image").html('<i class="icon-checkmark4"></i> SAVE');
                    if(res.status == 'ERR'){
                        $("#div_edit_product_gallery_image_error").html(res.message);
                        $("#div_edit_product_gallery_image_error").fadeIn("fast");
                    }else if (res.status == 'SUCCESS'){
                        $("#div_edit_product_gallery_image_success").html(res.message);
                        $("#div_edit_product_gallery_image_success").fadeIn("fast");

                        load_product_gallery_images();

                        $('#modal_edit_product_gallery_image').modal('toggle');
                    }
                },
                error: function(){
                    $("#btn_edit_product_gallery_image").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#edit_product_gallery_image_loader").hide();
                    $("#div_edit_product_gallery_image_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_product_gallery_image_error").fadeIn("fast");
                }
            });
        }
    }
    return false;
}


function delete_product_image(product_image_id) {
    swal({
        text: 'Do you wish to delete this Product Image? Please note that this action is irreversible.',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/products/delete_product_image/'+product_image_id,
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
                            load_product_gallery_images();
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
}

//BRANDS
function save_brand() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    if ($("#frm_add_brand").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_brand").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_brand").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_brand');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/brands/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_brand").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_brand').each(function() {
                            this.reset();
                        });

                        load_brands();

                        setTimeout(function() {
                            $("#div_add_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_add_brand").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_brands() {
    $("#brands_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/brands/load_js',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#brands_div").html(result);

            setInterval(function() {
                $("#brands_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#brands_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function brand_edit_load(brand_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");
    $("#div_delete_brand_logo").fadeOut("fast");

    $('#frm_edit_brand').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/brands/get_brand2/' + brand_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_brand_id").val(obj['brand_id']);
                $("#edit_brand_name").val(obj['brand_name']);
                $("#edit_sort_key").val(obj['sort_key']);
                $("#edit_seo_title").val(obj['seo_title']);
                $("#edit_seo_description").val(obj['seo_description']);
                $("#edit_seo_keywords").val(obj['seo_keywords']);
                if (obj['is_active'] == 0){
                    $("#edit_is_active_1").removeAttr( "checked").change();
                    $("#edit_is_active_0").attr('checked', true).change();
                    
                }else if (obj['is_active'] == 1){
                    $("#edit_is_active_0").removeAttr( "checked").change();
                    $("#edit_is_active_1").attr('checked', true).change(); 
                }

                if (obj['logo'] == ''){
                    $("#img_brand_logo").attr('src','');
                    $("#div_delete_brand_logo").fadeOut("fast");
                }else{
                    $image_path = baseDir + 'uploads/brand_logos/' + obj['logo'];
                    check_brand_logo_exists($image_path);
                }               

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
function check_brand_logo_exists($image_path){
    $.ajax({
        url: $image_path,
        type: 'HEAD',
        error: function(){
            $("#img_brand_logo").attr('src', '');
            $("#div_delete_brand_logo").fadeOut("fast");
        },
        success: function(){
            $("#img_brand_logo").attr('src', $image_path);
            $("#div_delete_brand_logo").fadeIn("fast");
        }
    });
}
function update_brand() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    var brand_id = $("#edit_brand_id").val();

    if ($("#frm_edit_brand").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_brand").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_brand").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_brand');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/brands/update',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_brand").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_edit_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        load_brands();
                        brand_edit_load(brand_id);

                        setTimeout(function() {
                            $("#div_edit_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_edit_brand").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_brand(brand_id) {
    swal({
        text: 'Do you wish to delete this Brand?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/brands/delete/' + brand_id,
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
                            load_brands();
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

}

function delete_brand_logo() {
    var brand_id = $("#edit_brand_id").val();
    swal({
        text: 'Do you wish to delete this Brand Logo?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/brands/delete_logo/'+brand_id,
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
                            load_brands();
                            brand_edit_load(brand_id);
                            // setTimeout(function() {
                            //     location.reload();
                            // }, 2000);
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
}

//UNITS
function unit_add_clear() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    $('#frm_add_unit').each(function() {
        this.reset();
    });
}

//SAVE UNITS
function save_unit() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    if ($("#frm_add_unit").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_unit").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_unit").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_unit');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/units/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_unit").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_unit').each(function() {
                            this.reset();
                        });

                        $('#add_unit_type_id').val('').change();
                        $('#add_related_units').html('');

                        load_units();

                        setTimeout(function() {
                            $("#div_add_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_add_unit").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_units() {
    $("#units_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/units/load_js',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#units_div").html(result);

            setInterval(function() {
                $("#units_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#units_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function unit_edit_load(unit_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $.ajax({
        url: baseDir + 'be/units/get_unit2/' + unit_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_unit_id").val(obj['unit_id']);
                $("#edit_unit_type_id").val(obj['unit_type_id']).change();
                $("#edit_unit_name").val(obj['unit_name']);
                $("#edit_unit_code").val(obj['unit_code']);
                $("#edit_sort_key").val(obj['sort_key']);

                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").attr('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").attr('checked', true).change();
                }

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
//UPDATE unit
function update_unit() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    if ($("#frm_edit_unit").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_unit").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_unit").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_unit');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/units/update',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_unit").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        load_units();

                        setTimeout(function() {
                            $("#div_edit_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_edit_unit").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_unit(unit_id) {
    swal({
        text: 'Do you wish to delete this Unit?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/units/delete/' + unit_id,
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
                            load_units();
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
}

//UNITS
function unit_add_clear() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    $('#frm_add_unit').each(function() {
        this.reset();
    });
}

//SAVE UNIT TYPES
function save_unit_type() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    if ($("#frm_add_unit_type").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_unit_type").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        var form = document.getElementById('frm_add_unit_type');
        var formData = new FormData(form);

        $.ajax({
            url: baseDir + 'be/units/save_unit_type',
            type: 'POST',
            data: formData,
            dataType: 'json',
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                return myXhr;
            },
            cache: false,
            contentType: false,
            processData: false,
            success: function(res) {
                $("#btn_add_unit_type").html('<i class="icon-checkmark4"></i> SAVE');
                if (res.status == 'ERR') {
                    $("#div_add_error").html(res.message);
                    $("#div_add_error").fadeIn("fast");
                } else if (res.status == 'SUCCESS') {
                    $("#div_add_success").html(res.message);
                    $("#div_add_success").fadeIn("fast");

                    $('#frm_add_unit_type').each(function() {
                        this.reset();
                    });

                    load_unit_types();

                    setTimeout(function() {
                        $("#div_add_success").fadeOut("fast");
                    }, 3000);
                }
            },
            error: function() {
                $("#btn_add_unit_type").html('<i class="icon-checkmark4"></i> SAVE');
                $("#div_add_error").html("Something went wrong. Please check your network and try again.");
                $("#div_add_error").fadeIn("fast");
            }
        });

        
    }
    return false;
}

function load_unit_types() {
    $("#unit_types_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/units/load_js_unit_types',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#unit_types_div").html(result);

            setInterval(function() {
                $("#unit_types_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#unit_types_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function unit_type_edit_load(unit_type_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $.ajax({
        url: baseDir + 'be/units/get_unit_type2/' + unit_type_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_unit_type_id").val(obj['unit_type_id']);
                $("#edit_unit_type_name").val(obj['unit_type_name']);
                $("#edit_unit_type_description").val(obj['unit_type_description']);
                $("#edit_sort_key").val(obj['sort_key']);

                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").attr('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").attr('checked', true).change();
                }

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
function update_unit_type() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    if ($("#frm_edit_unit_type").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_unit_type").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        var form = document.getElementById('frm_edit_unit_type');
        var formData = new FormData(form);

        $.ajax({
            url: baseDir + 'be/units/update_unit_type',
            type: 'POST',
            data: formData,
            dataType: 'json',
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                return myXhr;
            },
            cache: false,
            contentType: false,
            processData: false,
            success: function(res) {
                $("#btn_edit_unit_type").html('<i class="icon-checkmark4"></i> UPDATE');
                if (res.status == 'ERR') {
                    $("#div_edit_error").html(res.message);
                    $("#div_edit_error").fadeIn("fast");
                } else if (res.status == 'SUCCESS') {
                    $("#div_edit_success").html(res.message);
                    $("#div_edit_success").fadeIn("fast");

                    load_unit_types();

                    setTimeout(function() {
                        $("#div_edit_success").fadeOut("fast");
                    }, 3000);
                }
            },
            error: function() {
                $("#btn_edit_unit_type").html('<i class="icon-checkmark4"></i> UPDATE');
                $("#div_edit_error").html("Something went wrong. Please check your network and try again.");
                $("#div_edit_error").fadeIn("fast");
            }
        });        
    }
    return false;
}

function delete_unit_type(unit_type_id) {
    swal({
        text: 'Do you wish to delete this Unit Type?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/units/delete_unit_type/' + unit_type_id,
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
                            load_unit_types();
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
}

//PRODUCT SIZES
function product_size_add_clear() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    $('#frm_add_product_size').each(function() {
        this.reset();
    });
}

function save_product_size() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    if ($("#frm_add_product_size").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_product_size").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_product_size").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_product_size');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/product_sizes/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_product_size").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_product_size').each(function() {
                            this.reset();
                        });

                        load_product_sizes();

                        setTimeout(function() {
                            $("#div_add_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_add_product_size").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_product_sizes() {
    $("#product_sizes_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/product_sizes/load_js',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#product_sizes_div").html(result);

            setInterval(function() {
                $("#product_sizes_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#product_sizes_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function product_size_edit_load(product_size_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $.ajax({
        url: baseDir + 'be/product_sizes/get_product_size2/' + product_size_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_product_size_id").val(obj['product_size_id']);
                $("#edit_product_size_name").val(obj['product_size_name']);
                $("#edit_product_size_code").val(obj['product_size_code']);
                $("#edit_sort_key").val(obj['sort_key']);

                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").attr('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").attr('checked', true).change();
                }

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
function update_product_size() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    if ($("#frm_edit_product_size").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_product_size").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_product_size").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_product_size');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/product_sizes/update',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_product_size").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        load_product_sizes();

                        setTimeout(function() {
                            $("#div_edit_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_edit_product_size").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_product_size(product_size_id) {
    swal({
        text: 'Do you wish to delete this Product Size?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/product_sizes/delete/' + product_size_id,
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
                            load_product_sizes();
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
}


//TAX RATES
function tax_rate_add_clear() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    $('#frm_add_tax_rate').each(function() {
        this.reset();
    });
}

//SAVE TAX RATES
function save_tax_rate() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    if ($("#frm_add_tax_rate").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_tax_rate").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_tax_rate").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_tax_rate');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/save_tax_rate',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_tax_rate").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_tax_rate').each(function() {
                            this.reset();
                        });

                        load_tax_rates();

                        setTimeout(function() {
                            $("#div_add_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_add_tax_rate").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_tax_rates() {
    $("#tax_rates_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/settings/loadjs_tax_rates',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#tax_rates_div").html(result);

            setInterval(function() {
                $("#tax_rates_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#tax_rates_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function tax_rate_edit_load(tax_rate_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $.ajax({
        url: baseDir + 'be/settings/get_tax_rate2/' + tax_rate_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_tax_rate_id").val(obj['tax_rate_id']);
                $("#edit_tax_rate_name").val(obj['tax_rate_name']);
                $("#edit_tax_rate_code").val(obj['tax_rate_code']);
                $("#edit_tax_rate_value").val(obj['tax_rate_value']);
                $("#edit_sort_key").val(obj['sort_key']);

                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").attr('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").attr('checked', true).change();
                }

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
//UPDATE TAX RATE
function update_tax_rate() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    if ($("#frm_edit_tax_rate").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_tax_rate").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_tax_rate").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_tax_rate');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/update_tax_rate',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_tax_rate").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        load_tax_rates();

                        setTimeout(function() {
                            $("#div_edit_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_edit_tax_rate").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_tax_rate(tax_rate_id) {
    swal({
        text: 'Do you wish to delete this Tax Rate?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/settings/delete_tax_rate/' + tax_rate_id,
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
                            load_tax_rates();
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
}

//PAYMENT METHODS
function payment_method_add_clear() {
    $("#div_add_payment_method_error").fadeOut("fast");
    $("#div_add_payment_method_success").fadeOut("fast");

    $('#frm_add_payment_method').each(function() {
        this.reset();
    });
}

//SAVE PAYMENT METHOD
function save_payment_method() {
    $("#div_add_payment_method_error").fadeOut("fast");
    $("#div_add_payment_method_success").fadeOut("fast");

    if ($("#frm_add_payment_method").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_payment_method").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_payment_method").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_payment_method_error").html($valmsg);
            $("#div_add_payment_method_error").fadeIn("fast");
        } else {
            $("#div_add_payment_method_error").fadeOut("fast");

            var form = document.getElementById('frm_add_payment_method');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/payment_methods/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_payment_method").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_payment_method_error").html(res.message);
                        $("#div_add_payment_method_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_payment_method_success").html(res.message);
                        $("#div_add_payment_method_success").fadeIn("fast");

                        $('#frm_add_payment_method').each(function() {
                            this.reset();
                        });

                        load_payment_methods();
                    }
                },
                error: function() {
                    $("#btn_add_payment_method").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_payment_method_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_payment_method_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_payment_methods() {
    $("#payment_methods_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/payment_methods/load_js',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#payment_methods_div").html(result);

            setInterval(function() {
                $("#payment_methods_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#payment_methods_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function payment_method_edit_load(payment_method_id) {
    $("#div_edit_payment_method_error").fadeOut("fast");
    $("#div_edit_payment_method_success").fadeOut("fast");

    $('#frm_edit_payment_method').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/payment_methods/get_payment_method/' + payment_method_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_payment_method_id").val(obj['payment_method_id']);
                $("#edit_payment_method_name").val(obj['payment_method_name']);
                if (obj['payment_option'] === 'Cash') {
                    $("#edit_payment_option_mpesa").prop('checked', false).change();
                    $("#edit_payment_option_cheque").prop('checked', false).change();
                    $("#edit_payment_option_credit_card").prop('checked', false).change();
                    $("#edit_payment_option_loyalty_card").prop('checked', false).change();
                    $("#edit_payment_option_cash").prop('checked', true).change();
                } else if (obj['payment_option'] === 'MPESA') {
                    $("#edit_payment_option_cash").prop('checked', false).change();
                    $("#edit_payment_option_cheque").prop('checked', false).change();
                    $("#edit_payment_option_credit_card").prop('checked', false).change();
                    $("#edit_payment_option_loyalty_card").prop('checked', false).change();
                    $("#edit_payment_option_mpesa").prop('checked', true).change();
                } else if (obj['payment_option'] === 'Cheque') {
                    $("#edit_payment_option_mpesa").prop('checked', false).change();
                    $("#edit_payment_option_cash").prop('checked', false).change();
                    $("#edit_payment_option_credit_card").prop('checked', false).change();
                    $("#edit_payment_option_loyalty_card").prop('checked', false).change();
                    $("#edit_payment_option_cheque").prop('checked', true).change();
                } else if (obj['payment_option'] === 'Credit Card') {
                    $("#edit_payment_option_mpesa").prop('checked', false).change();
                    $("#edit_payment_option_cheque").prop('checked', false).change();
                    $("#edit_payment_option_cash").prop('checked', false).change();
                    $("#edit_payment_option_loyalty_card").prop('checked', false).change();
                    $("#edit_payment_option_credit_card").prop('checked', true).change();
                } else if (obj['payment_option'] === 'Loyalty Card') {
                    $("#edit_payment_option_mpesa").prop('checked', false).change();
                    $("#edit_payment_option_cheque").prop('checked', false).change();
                    $("#edit_payment_option_credit_card").prop('checked', false).change();
                    $("#edit_payment_option_cash").prop('checked', false).change();
                    $("#edit_payment_option_loyalty_card").prop('checked', true).change();
                }
                if (obj['process_credit_card'] == 1) {
                    $('#edit_process_credit_card').prop('disabled', false).change();
                    $("#edit_process_credit_card").prop('checked', true).change();
                } else {
                    $("#edit_process_credit_card").prop('checked', false).change();
                }
                if (obj['process_loyalty_card'] == 1) {
                    $('#edit_process_loyalty_card').prop('disabled', false).change();
                    $("#edit_process_loyalty_card").prop('checked', true).change();
                } else {
                    $("#edit_process_loyalty_card").prop('checked', false).change();
                }
                if (obj['open_cash_drawer'] == 1) {
                    $("#edit_open_cash_drawer").prop('checked', true).change();
                } else {
                    $("#edit_open_cash_drawer").prop('checked', false).change();
                }
                $("#edit_sort_key").val(obj['sort_key']);
                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").prop('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").prop('checked', true).change();
                }

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
//UPDATE PAYMENT METHOD
function update_payment_method() {
    $("#div_edit_payment_method_error").fadeOut("fast");
    $("#div_edit_payment_method_success").fadeOut("fast");

    if ($("#frm_edit_payment_method").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_payment_method").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_payment_method").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_payment_method_error").html($valmsg);
            $("#div_edit_payment_method_error").fadeIn("fast");
        } else {
            $("#div_edit_payment_method_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_payment_method');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/payment_methods/update',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_payment_method").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_payment_method_error").html(res.message);
                        $("#div_edit_payment_method_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_payment_method_success").html(res.message);
                        $("#div_edit_payment_method_success").fadeIn("fast");

                        load_payment_methods();
                    }
                },
                error: function() {
                    $("#btn_edit_payment_method").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_payment_method_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_payment_method_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_payment_method(payment_method_id) {
    swal({
        text: 'Do you wish to delete this Payment Method?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/payment_methods/delete/' + payment_method_id,
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
                            load_payment_methods();
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
}

//CURRENCIES
function currency_add_clear() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    $('#frm_add_currency').each(function() {
        this.reset();
    });

    $("#add_country_id").val('').change();
}

//SAVE CURRENCY
function save_currency() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    if ($("#frm_add_currency").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_currency").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_currency").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_currency');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/save_currency',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_currency").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_currency').each(function() {
                            this.reset();
                        });

                        $("#add_country_id").val('').change();

                        load_currencies();

                        setTimeout(function() {
                            $("#div_add_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_add_currency").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_currencies() {
    $("#currencies_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/settings/loadjs_currencies',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#currencies_div").html(result);

            setInterval(function() {
                $("#currencies_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#currencies_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function currency_edit_load(currency_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $('#frm_edit_currency').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/settings/get_currency2/' + currency_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_currency_id").val(obj['currency_id']);
                $("#edit_country_id").val(obj['country_id']).change();
                $("#edit_currency_name").val(obj['currency_name']);
                $("#edit_currency_symbol").val(obj['currency_symbol']);
                $("#edit_exchange_rate").val(obj['exchange_rate']);
                $("#edit_sort_key").val(obj['sort_key']);
                if (obj['default_currency'] == 0) {
                    $("#edit_default_currency").removeAttr("checked").change();
                } else if (obj['default_currency'] == 1) {
                    $("#edit_default_currency").prop('checked', true).change();
                }
                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").prop('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").prop('checked', true).change();
                }

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
//UPDATE CURRENCY
function update_currency() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    if ($("#frm_edit_currency").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_currency").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_currency").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_currency');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/update_currency',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_currency").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        load_currencies();

                        setTimeout(function() {
                            $("#div_edit_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_edit_currency").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_currency(currency_id) {
    swal({
        text: 'Do you wish to delete this Currency?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/settings/delete_currency/' + currency_id,
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
                            load_currencies();
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
}

//COUNTRIES
function country_add_clear() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    $('#frm_add_country').each(function() {
        this.reset();
    });
}

//SAVE COUNTRY
function save_country() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    if ($("#frm_add_country").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_country").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_country").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_country');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/save_country',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_country").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_country').each(function() {
                            this.reset();
                        });

                        load_countries();

                        setTimeout(function() {
                            $("#div_add_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_add_country").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_countries() {
    $("#countries_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/settings/loadjs_countries',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#countries_div").html(result);

            setInterval(function() {
                $("#countries_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#countries_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function country_edit_load(country_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $('#frm_edit_country').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/settings/get_country2/' + country_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_country_id").val(obj['country_id']);
                $("#edit_country_name").val(obj['country_name']);
                $("#edit_country_code").val(obj['country_code']);
                $("#edit_country_abbreviation").val(obj['country_abbreviation']);
                $("#edit_nationality").val(obj['nationality']);
                $("#edit_sort_key").val(obj['sort_key']);
                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").prop('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").prop('checked', true).change();
                }

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
//UPDATE COUNTRY
function update_country() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    if ($("#frm_edit_country").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_country").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_country").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_country');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/update_country',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_country").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        load_countries();

                        setTimeout(function() {
                            $("#div_edit_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_edit_country").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_country(country_id) {
    swal({
        text: 'Do you wish to delete this Country?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/settings/delete_country/' + country_id,
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
                            load_countries();
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
}

//REGIONS
function region_add_clear() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    $('#frm_add_region').each(function() {
        this.reset();
    });
}

function save_region() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    if ($("#frm_add_region").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_region").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_region").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_region');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/save_region',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_region").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_region').each(function() {
                            this.reset();
                        });

                        load_regions();

                        setTimeout(function() {
                            $("#div_add_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_add_region").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_regions() {
    $("#regions_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    var country_id = $("#add_country_id").val();
    $.ajax({
        url: baseDir + 'be/settings/loadjs_regions/' + country_id,
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#regions_div").html(result);

            setInterval(function() {
                $("#regions_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#regions_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function region_edit_load(region_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $('#frm_edit_region').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/settings/get_region2/' + region_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_region_id").val(obj['region_id']);
                $("#edit_region_name").val(obj['region_name']);
                $("#edit_sort_key").val(obj['sort_key']);

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
function update_region() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    if ($("#frm_edit_region").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_region").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_region").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_region');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/update_region',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_region").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        load_regions();

                        setTimeout(function() {
                            $("#div_edit_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_edit_region").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_region(region_id) {
    swal({
        text: 'Do you wish to delete this region?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/settings/delete_region/' + region_id,
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
                            load_regions();
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
}

//SHIPPING ZONES
function shipping_zone_add_clear() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    $('#frm_add_shipping_zone').each(function() {
        this.reset();
    });
}

function save_shipping_zone() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    if ($("#frm_add_shipping_zone").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_shipping_zone").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_shipping_zone").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_shipping_zone');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/save_shipping_zone',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_shipping_zone").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_shipping_zone').each(function() {
                            this.reset();
                        });
                        $('#add_region_id').val('').change();
                        $('#add_shipping_method').val('').change();

                        load_shipping_zones();

                        setTimeout(function() {
                            $("#div_add_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_add_shipping_zone").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_shipping_zones() {
    $("#shipping_zones_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/settings/loadjs_shipping_zones',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#shipping_zones_div").html(result);

            setInterval(function() {
                $("#shipping_zones_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#shipping_zones_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function shipping_zone_edit_load(shipping_zone_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $('#frm_edit_shipping_zone').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/settings/get_shipping_zone2/' + shipping_zone_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_shipping_zone_id").val(obj['shipping_zone_id']);
                $("#edit_shipping_zone_name").val(obj['shipping_zone_name']);
                $("#edit_region_id").val(obj['region_id']).change();
                $("#edit_shipping_method").val(obj['shipping_method']).change();
                $("#edit_shipping_fee").val(obj['shipping_fee']);
                $("#edit_sort_key").val(obj['sort_key']);
                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").prop('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").prop('checked', true).change();
                }

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
function update_shipping_zone() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    if ($("#frm_edit_shipping_zone").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_shipping_zone").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_shipping_zone").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_shipping_zone');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/update_shipping_zone',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_shipping_zone").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        load_shipping_zones();

                        setTimeout(function() {
                            $("#div_edit_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_edit_shipping_zone").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_shipping_zone(shipping_zone_id) {
    swal({
        text: 'Do you wish to delete this Shipping Zone?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/settings/delete_shipping_zone/' + shipping_zone_id,
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
                            load_shipping_zones();
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
}

//PICKUP LOCATIONS
function pickup_location_add_clear() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    $('#frm_add_pickup_location').each(function() {
        this.reset();
    });
}

function save_pickup_location() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    if ($("#frm_add_pickup_location").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_pickup_location").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_pickup_location").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_pickup_location');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/save_pickup_location',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_pickup_location").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_pickup_location').each(function() {
                            this.reset();
                        });
                        $('#add_region_id').val('').change();

                        load_pickup_locations();

                        setTimeout(function() {
                            $("#div_add_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_add_pickup_location").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_pickup_locations() {
    $("#pickup_locations_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/settings/loadjs_pickup_locations',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#pickup_locations_div").html(result);

            setInterval(function() {
                $("#pickup_locations_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#pickup_locations_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function pickup_location_edit_load(pickup_location_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $('#frm_edit_pickup_location').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/settings/get_pickup_location2/' + pickup_location_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_pickup_location_id").val(obj['pickup_location_id']);                
                $("#edit_region_id").val(obj['region_id']).change();
                $("#edit_pickup_location_name").val(obj['pickup_location_name']);
                $("#edit_pickup_location_address").val(obj['pickup_location_address']);
                $("#edit_close_to").val(obj['close_to']);
                $("#edit_opening_hours").val(obj['opening_hours']);
                $("#edit_shipping_fee").val(obj['shipping_fee']);
                $("#edit_pickup_period").val(obj['pickup_period']);
                $("#edit_sort_key").val(obj['sort_key']);
                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").prop('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").prop('checked', true).change();
                }

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
function update_pickup_location() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    if ($("#frm_edit_pickup_location").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_pickup_location").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_pickup_location").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_pickup_location');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/update_pickup_location',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_pickup_location").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        load_pickup_locations();

                        setTimeout(function() {
                            $("#div_edit_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_edit_pickup_location").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_pickup_location(pickup_location_id) {
    swal({
        text: 'Do you wish to delete this Pickup Location?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/settings/delete_pickup_location/' + pickup_location_id,
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
                            load_pickup_locations();
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
}


//BANKS
function bank_add_clear() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    $('#frm_add_bank').each(function() {
        this.reset();
    });
}

//SAVE BANK
function save_bank() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    if ($("#frm_add_bank").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_bank").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_bank").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_bank');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/save_bank',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_bank").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_bank').each(function() {
                            this.reset();
                        });

                        load_banks();

                        setTimeout(function() {
                            $("#div_add_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_add_bank").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_banks() {
    $("#banks_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/settings/loadjs_banks',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#banks_div").html(result);

            setInterval(function() {
                $("#banks_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#banks_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function bank_edit_load(bank_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $('#frm_edit_bank').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/settings/get_bank2/' + bank_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_bank_id").val(obj['bank_id']);
                $("#edit_bank_name").val(obj['bank_name']);
                $("#edit_bank_code").val(obj['bank_code']);
                $("#edit_bank_comment").val(obj['bank_comment']);
                $("#edit_sort_key").val(obj['sort_key']);
                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").prop('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").prop('checked', true).change();
                }

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
//UPDATE BANK
function update_bank() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    if ($("#frm_edit_bank").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_bank").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_bank").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_bank');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/update_bank',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_bank").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        load_banks();

                        setTimeout(function() {
                            $("#div_edit_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_edit_bank").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_bank(bank_id) {
    swal({
        text: 'Do you wish to delete this Bank?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/settings/delete_bank/' + bank_id,
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
                            load_banks();
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
}

//BANK BRANCHES
function bank_branch_add_clear() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    $('#frm_add_bank_branch').each(function() {
        this.reset();
    });
}

//SAVE BANK BRANCH
function save_bank_branch() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    if ($("#frm_add_bank_branch").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_bank_branch").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_bank_branch").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_bank_branch');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/save_bank_branch',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_bank_branch").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_bank_branch').each(function() {
                            this.reset();
                        });

                        load_bank_branches();

                        setTimeout(function() {
                            $("#div_add_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_add_bank_branch").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_bank_branches() {
    $("#bank_branches_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    var bank_id = $("#add_bank_id").val();
    $.ajax({
        url: baseDir + 'be/settings/loadjs_bank_branches/' + bank_id,
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#bank_branches_div").html(result);

            setInterval(function() {
                $("#bank_branches_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#bank_branches_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function bank_branch_edit_load(bank_branch_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $('#frm_edit_bank_branch').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/settings/get_bank_branch2/' + bank_branch_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_bank_branch_id").val(obj['bank_branch_id']);
                $("#edit_bank_branch_name").val(obj['bank_branch_name']);
                $("#edit_bank_branch_code").val(obj['bank_branch_code']);
                $("#edit_account_number").val(obj['account_number']);
                $("#edit_phone_number").val(obj['phone_number']);
                $("#edit_mobile_number").val(obj['mobile_number']);
                $("#edit_email_address").val(obj['email_address']);
                $("#edit_postal_address").val(obj['postal_address']);
                $("#edit_sort_key").val(obj['sort_key']);
                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").prop('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").prop('checked', true).change();
                }

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
//UPDATE BANK BRANCH
function update_bank_branch() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    if ($("#frm_edit_bank_branch").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_bank_branch").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_bank_branch").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_bank_branch');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/update_bank_branch',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_bank_branch").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        load_bank_branches();

                        setTimeout(function() {
                            $("#div_edit_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_edit_bank_branch").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_bank_branch(bank_branch_id) {
    swal({
        text: 'Do you wish to delete this Bank Branch?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/settings/delete_bank_branch/' + bank_branch_id,
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
                            load_bank_branches();
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
}

//VOID REASONS
function void_reason_add_clear() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    $('#frm_add_void_reason').each(function() {
        this.reset();
    });
}

//SAVE VOID REASON
function save_void_reason() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    if ($("#frm_add_void_reason").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_void_reason").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_void_reason").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_void_reason');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/save_void_reason',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_void_reason").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_void_reason').each(function() {
                            this.reset();
                        });

                        load_void_reasons();

                        setTimeout(function() {
                            $("#div_add_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_add_void_reason").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_void_reasons() {
    $("#void_reasons_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/settings/loadjs_void_reasons',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#void_reasons_div").html(result);

            setInterval(function() {
                $("#void_reasons_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#void_reasons_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function void_reason_edit_load(void_reason_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $('#frm_edit_void_reason').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/settings/get_void_reason2/' + void_reason_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_void_reason_id").val(obj['void_reason_id']);
                $("#edit_void_reason").val(obj['void_reason']);
                $("#edit_sort_key").val(obj['sort_key']);
                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").prop('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").prop('checked', true).change();
                }

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
//UPDATE VOID REASON
function update_void_reason() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    if ($("#frm_edit_void_reason").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_void_reason").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_void_reason").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_void_reason');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/update_void_reason',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_void_reason").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        load_void_reasons();

                        setTimeout(function() {
                            $("#div_edit_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_edit_void_reason").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_void_reason(void_reason_id) {
    swal({
        text: 'Do you wish to delete this Void Reason?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/settings/delete_void_reason/' + void_reason_id,
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
                            load_void_reasons();
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
}

//BITLY SETTINGS
function save_bitly_settings() {
    $("#div_error").fadeOut("fast");
    $("#div_success").fadeOut("fast");

    if ($("#frm_bitly_settings").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_bitly_settings").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_bitly_settings").html('<i class="icon-checkmark4"></i> SAVE BITLY SETTINGS');
            $("#div_error").html($valmsg);
            $("#div_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_error").fadeOut("fast");

            var form = document.getElementById('frm_bitly_settings');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/save_bitly_settings',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_bitly_settings").html('<i class="icon-checkmark4"></i> SAVE BITLY SETTINGS');
                    if (res.status == 'ERR') {
                        $("#div_error").html(res.message);
                        $("#div_error").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_success").html(res.message);
                        $("#div_success").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_success').offset().top - 90
                        }, 'slow');

                        setTimeout(function() {
                            $("#div_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_bitly_settings").html('<i class="icon-checkmark4"></i> SAVE BITLY SETTINGS');
                    $("#div_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_error").fadeIn("fast");
                    $('html, body').animate({
                        scrollTop: $('#div_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}

//SALE COMMENTS
function save_sale_comments() {
    $("#div_error").fadeOut("fast");
    $("#div_success").fadeOut("fast");

    if ($("#frm_sale_comments").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_sale_comments").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_sale_comments").html('<i class="icon-checkmark4"></i> SAVE COMMENTS');
            $("#div_error").html($valmsg);
            $("#div_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_error").fadeOut("fast");

            var form = document.getElementById('frm_sale_comments');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/save_sale_comments',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_sale_comments").html('<i class="icon-checkmark4"></i> SAVE COMMENTS');
                    if (res.status == 'ERR') {
                        $("#div_error").html(res.message);
                        $("#div_error").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_success").html(res.message);
                        $("#div_success").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_success').offset().top - 90
                        }, 'slow');

                        setTimeout(function() {
                            $("#div_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_sale_comments").html('<i class="icon-checkmark4"></i> SAVE COMMENTS');
                    $("#div_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_error").fadeIn("fast");
                    $('html, body').animate({
                        scrollTop: $('#div_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}

//CREDIT TERMS
function credit_term_add_clear() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    $('#frm_add_credit_term').each(function() {
        this.reset();
    });
}

//SAVE CREDIT TERM
function save_credit_term() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    if ($("#frm_add_credit_term").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_credit_term").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_credit_term").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_credit_term');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/save_credit_term',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_credit_term").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_credit_term').each(function() {
                            this.reset();
                        });

                        load_credit_terms();

                        setTimeout(function() {
                            $("#div_add_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_add_credit_term").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_credit_terms() {
    $("#credit_terms_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/settings/loadjs_credit_terms',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#credit_terms_div").html(result);

            setInterval(function() {
                $("#credit_terms_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#credit_terms_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function credit_term_edit_load(credit_term_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $('#frm_edit_credit_term').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/settings/get_credit_term2/' + credit_term_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_credit_term_id").val(obj['credit_term_id']);
                $("#edit_credit_term").val(obj['credit_term']);
                $("#edit_credit_term_days").val(obj['credit_term_days']);
                $("#edit_sort_key").val(obj['sort_key']);
                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").prop('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").prop('checked', true).change();
                }

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
//UPDATE CREDIT TERM
function update_credit_term() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    if ($("#frm_edit_credit_term").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_credit_term").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_credit_term").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_credit_term');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/update_credit_term',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_credit_term").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        load_credit_terms();

                        setTimeout(function() {
                            $("#div_edit_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_edit_credit_term").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_credit_term(credit_term_id) {
    swal({
        text: 'Do you wish to delete this Credit Term?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/settings/delete_credit_term/' + credit_term_id,
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
                            load_credit_terms();
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
}

//MPESA SETTINGS
function save_mpesa_settings() {
    $("#div_error").fadeOut("fast");
    $("#div_success").fadeOut("fast");

    if ($("#frm_mpesa_settings").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_mpesa_settings").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_mpesa_settings").html('<i class="icon-checkmark4"></i> SAVE MPESA SETTINGS');
            $("#div_error").html($valmsg);
            $("#div_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_error").fadeOut("fast");

            var form = document.getElementById('frm_mpesa_settings');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/save_mpesa_settings',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_mpesa_settings").html('<i class="icon-checkmark4"></i> SAVE MPESA SETTINGS');
                    if (res.status == 'ERR') {
                        $("#div_error").html(res.message);
                        $("#div_error").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_success").html(res.message);
                        $("#div_success").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_success').offset().top - 90
                        }, 'slow');

                        setTimeout(function() {
                            $("#div_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_mpesa_settings").html('<i class="icon-checkmark4"></i> SAVE MPESA SETTINGS');
                    $("#div_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_error").fadeIn("fast");
                    $('html, body').animate({
                        scrollTop: $('#div_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}

//PESAPAL SETTINGS
function save_pesapal_settings() {
    $("#div_error").fadeOut("fast");
    $("#div_success").fadeOut("fast");

    if ($("#frm_pesapal_settings").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_pesapal_settings").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_pesapal_settings").html('<i class="icon-checkmark4"></i> SAVE PESAPAL SETTINGS');
            $("#div_error").html($valmsg);
            $("#div_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_error").fadeOut("fast");

            var form = document.getElementById('frm_pesapal_settings');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/save_pesapal_settings',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_pesapal_settings").html('<i class="icon-checkmark4"></i> SAVE PESAPAL SETTINGS');
                    if (res.status == 'ERR') {
                        $("#div_error").html(res.message);
                        $("#div_error").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_success").html(res.message);
                        $("#div_success").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_success').offset().top - 90
                        }, 'slow');

                        setTimeout(function() {
                            $("#div_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_pesapal_settings").html('<i class="icon-checkmark4"></i> SAVE PESAPAL SETTINGS');
                    $("#div_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_error").fadeIn("fast");
                    $('html, body').animate({
                        scrollTop: $('#div_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}


//EMAIL ACCOUNTS
function email_account_add_clear() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    $('#frm_add_email_account').each(function() {
        this.reset();
    });
}

//SAVE EMAIL ACCOUNT
function save_email_account() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");
    
    if ($("#frm_add_email_account").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_email_account").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_email_account").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_email_account');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/save_email_account',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_email_account").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_email_account').each(function() {
                            this.reset();
                        });

                        load_email_accounts();

                        setTimeout(function() {
                            $("#div_add_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_add_email_account").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_email_accounts() {
    $("#email_accounts_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/settings/loadjs_email_accounts',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#email_accounts_div").html(result);

            setInterval(function() {
                $("#email_accounts_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#email_accounts_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function email_account_edit_load(email_account_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $('#frm_edit_email_account').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/settings/get_email_account2/' + email_account_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                if (obj['is_default'] == 1) {
                    $("#edit_default").prop('checked', true).change();
                } else {
                    $("#edit_default").prop('checked', false).change();
                }
                $("#edit_email_account_id").val(obj['email_account_id']);
                $("#edit_sender_name").val(obj['sender_name']);
                $("#edit_sender_email_address").val(obj['sender_email_address']);
                $("#edit_mail_server_name").val(obj['mail_server_name']);
                $("#edit_mail_server_port").val(obj['mail_server_port']);
                $("#edit_user_name").val(obj['user_name']);
                $("#edit_password").val(obj['password']);
                if (obj['use_ssl'] == 1) {
                    $("#edit_use_ssl").prop('checked', true).change();
                } else {
                    $("#edit_use_ssl").prop('checked', false).change();
                }
                $("#edit_sort_key").val(obj['sort_key']);
                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").prop('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").prop('checked', true).change();
                }

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
//UPDATE EMAIL ACCOUNT
function update_email_account() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    //CKEDITOR.instances['edit_template'].updateElement();

    if ($("#frm_edit_email_account").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_email_account").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_email_account").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_email_account');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/update_email_account',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_email_account").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        load_email_accounts();

                        setTimeout(function() {
                            $("#div_edit_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_edit_email_account").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_email_account(email_account_id) {
    swal({
        text: 'Do you wish to delete this Email Account?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/settings/delete_email_account/' + email_account_id,
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
                            load_email_accounts();
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
}

//EMAIL TEMPLATES
function email_template_add_clear() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    $('#frm_add_email_template').each(function() {
        this.reset();
    });
}

function save_email_template() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    CKEDITOR.instances['add_email_template'].updateElement();

    if ($("#frm_add_email_template").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_email_template").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_email_template").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_email_template');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/save_email_template',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_email_template").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_email_template').each(function() {
                            this.reset();
                        });
                        CKEDITOR.instances['add_email_template'].setData('');

                        load_email_templates();

                        setTimeout(function() {
                            $("#div_add_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_add_email_template").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_email_templates() {
    $("#email_templates_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/settings/loadjs_email_templates',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#email_templates_div").html(result);

            setInterval(function() {
                $("#email_templates_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#email_templates_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function email_template_edit_load(email_template_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $('#frm_edit_email_template').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/settings/get_email_template2/' + email_template_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_email_template_id").val(obj['email_template_id']);
                $("#edit_email_template_name").val(obj['email_template_name']);
                CKEDITOR.instances['edit_email_template'].setData(obj['email_template']);
            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
function update_email_template() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    CKEDITOR.instances['edit_email_template'].updateElement();

    if ($("#frm_edit_email_template").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_email_template").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_email_template").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_email_template');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/update_email_template',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_email_template").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        load_email_templates();

                        setTimeout(function() {
                            $("#div_edit_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_edit_email_template").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_email_template(email_template_id) {
    swal({
        text: 'Do you wish to delete this Email Template?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/settings/delete_email_template/' + email_template_id,
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
                            load_email_templates();
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
}

//SMS SETTINGS
function save_sms_settings() {
    $("#div_error").fadeOut("fast");
    $("#div_success").fadeOut("fast");

    if ($("#frm_sms_settings").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_sms_settings").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_sms_settings").html('<i class="icon-checkmark4"></i> SAVE SMS SETTINGS');
            $("#div_error").html($valmsg);
            $("#div_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_error").fadeOut("fast");

            var form = document.getElementById('frm_sms_settings');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/save_sms_settings',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_sms_settings").html('<i class="icon-checkmark4"></i> SAVE SMS SETTINGS');
                    if (res.status == 'ERR') {
                        $("#div_error").html(res.message);
                        $("#div_error").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_success").html(res.message);
                        $("#div_success").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_success').offset().top - 90
                        }, 'slow');

                        setTimeout(function() {
                            $("#div_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_sms_settings").html('<i class="icon-checkmark4"></i> SAVE SMS SETTINGS');
                    $("#div_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_error").fadeIn("fast");
                    $('html, body').animate({
                        scrollTop: $('#div_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}

//EMAIL NOTIFICATION SETTINGS
function save_email_notification_settings() {
    $("#div_error").fadeOut("fast");
    $("#div_success").fadeOut("fast");

    if ($("#frm_email_notification_settings").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_email_notification_settings").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_email_notification_settings").html('<i class="icon-checkmark4"></i> SAVE SETTINGS');
            $("#div_error").html($valmsg);
            $("#div_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_error").fadeOut("fast");

            var form = document.getElementById('frm_email_notification_settings');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/save_email_notification_settings',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_email_notification_settings").html('<i class="icon-checkmark4"></i> SAVE SETTINGS');
                    if (res.status == 'ERR') {
                        $("#div_error").html(res.message);
                        $("#div_error").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_success").html(res.message);
                        $("#div_success").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_success').offset().top - 90
                        }, 'slow');

                        setTimeout(function() {
                            $("#div_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_email_notification_settings").html('<i class="icon-checkmark4"></i> SAVE SETTINGS');
                    $("#div_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_error").fadeIn("fast");
                    $('html, body').animate({
                        scrollTop: $('#div_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}


//SYSTEM USERS
function system_user_add_clear() {
    $("#div_add_system_user_error").fadeOut("fast");
    $("#div_add_system_user_success").fadeOut("fast");

    $('#frm_add_system_user').each(function() {
        this.reset();
    });
}

//SAVE SYSTEM USER
function save_system_user() {
    $("#div_add_system_user_error").fadeOut("fast");
    $("#div_add_system_user_success").fadeOut("fast");

    if ($("#frm_add_system_user").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_system_user").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_system_user").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_system_user_error").html($valmsg);
            $("#div_add_system_user_error").fadeIn("fast");
        } else {
            $("#div_add_system_user_error").fadeOut("fast");

            var form = document.getElementById('frm_add_system_user');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/system_users/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_system_user").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_system_user_error").html(res.message);
                        $("#div_add_system_user_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_system_user_success").html(res.message);
                        $("#div_add_system_user_success").fadeIn("fast");

                        $('#frm_add_system_user').each(function() {
                            this.reset();
                        });

                        load_system_users();
                    }
                },
                error: function() {
                    $("#btn_add_system_user").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_system_user_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_system_user_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_system_users() {
    $("#system_users_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/system_users/load_js',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#system_users_div").html(result);

            setInterval(function() {
                $("#system_users_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#system_users_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function system_user_edit_load(system_user_id) {
    $("#div_edit_system_user_error").fadeOut("fast");
    $("#div_edit_system_user_success").fadeOut("fast");

    $('#frm_edit_system_user').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/system_users/get_system_user/' + system_user_id,
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
                obj1 = obj1.replace(/\\n/g, "\\n")  
				        .replace(/\\'/g, "\\'")
				        .replace(/\\"/g, '\\"')
				        .replace(/\\&/g, "\\&")
				        .replace(/\\r/g, "\\r")
				        .replace(/\\t/g, "\\t")
				        .replace(/\\b/g, "\\b")
				        .replace(/\\f/g, "\\f");
				obj1 = obj1.replace(/[\u0000-\u0019]+/g,""); 
	     		var obj = JSON.parse(obj1);

                for (i=0; i< obj.length; i++){ 

	                $("#edit_system_user_id").val(obj[i]['system_user_id']);
	                $("#edit_first_name").val(obj[i]['first_name']);
	                $("#edit_last_name").val(obj[i]['last_name']);
	                $("#edit_user_name").val(obj[i]['user_name']);
	                $("#edit_user_role_id").val(obj[i]['user_role_id']).change();
	                $("#edit_email_address").val(obj[i]['email_address']);
	                $("#edit_phone_number").val(obj[i]['phone_number']);
	                $("#edit_address").val(obj[i]['address']);
	                $("#edit_sort_key").val(obj[i]['sort_key']);
	                if (obj[i]['is_active'] == 0) {
	                    $("#edit_is_active_1").removeAttr("checked").change();
	                    $("#edit_is_active_0").prop('checked', true).change();
	                } else if (obj[i]['is_active'] == 1) {
	                    $("#edit_is_active_0").removeAttr("checked").change();
	                    $("#edit_is_active_1").prop('checked', true).change();
	                }


                	var outlets = obj[i]['outlets'];
                	for (x = 0; x < outlets.length; x++) {
                		$("#edit_outlet_id_" + outlets[x]['outlet_id']).prop('checked', true).change();
                	}
				}

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
function system_user_change_password_load(system_user_id) {
    $("#div_system_user_change_password_error").fadeOut("fast");
    $("#div_system_user_change_password_success").fadeOut("fast");

    $('#frm_system_user_change_password').each(function() {
        this.reset();
    });

    $("#edit_system_user_change_password_id").val(system_user_id);

}
//UPDATE SYSTEM USER
function update_system_user() {
    $("#div_edit_system_user_error").fadeOut("fast");
    $("#div_edit_system_user_success").fadeOut("fast");

    if ($("#frm_edit_system_user").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_system_user").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_system_user").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_system_user_error").html($valmsg);
            $("#div_edit_system_user_error").fadeIn("fast");
        } else {
            $("#div_edit_system_user_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_system_user');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/system_users/update',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_system_user").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_system_user_error").html(res.message);
                        $("#div_edit_system_user_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_system_user_success").html(res.message);
                        $("#div_edit_system_user_success").fadeIn("fast");

                        load_system_users();
                    }
                },
                error: function() {
                    $("#btn_edit_system_user").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_system_user_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_system_user_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

//SYSTEM USER CHANGE PASSWORD
function system_user_change_password() {
    $("#div_system_user_change_password_error").fadeOut("fast");
    $("#div_system_user_change_password_success").fadeOut("fast");

    if ($("#frm_system_user_change_password").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_system_user_change_password").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_system_user_change_password").html('<i class="icon-checkmark4"></i> CHANGE PASSWORD');
            $("#div_system_user_change_password_error").html($valmsg);
            $("#div_system_user_change_password_error").fadeIn("fast");
        } else {

            var form = document.getElementById('frm_system_user_change_password');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/system_users/change_password',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_system_user_change_password").html('<i class="icon-checkmark4"></i> CHANGE PASSWORD');
                    if (res.status == 'ERR') {
                        $("#div_system_user_change_password_error").html(res.message);
                        $("#div_system_user_change_password_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_system_user_change_password_success").html(res.message);
                        $("#div_system_user_change_password_success").fadeIn("fast");

                        $('#frm_system_user_change_password').each(function() {
                            this.reset();
                        });

                        setTimeout(function() {
                            $('#modal_change_password').modal('toggle');
                        }, 2000);
                        
                    }
                },
                error: function() {
                    $("#btn_system_user_change_password").html('<i class="icon-checkmark4"></i> CHANGE PASSWORD');
                    $("#div_system_user_change_password_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_system_user_change_password_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_system_user(system_user_id) {
    swal({
        text: 'Do you wish to delete this System User?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/system_users/delete/' + system_user_id,
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
                            load_system_users();
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
}

//UPDATE PROFILE
function update_profile() {
    $("#div_edit_profile_error").fadeOut("fast");
    $("#div_edit_profile_success").fadeOut("fast");

    if ($("#frm_edit_profile").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_profile").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_profile").html('<i class="icon-checkmark4"></i> UPDATE PROFILE');
            $("#div_edit_profile_error").html($valmsg);
            $("#div_edit_profile_error").fadeIn("fast");
        } else {
            $("#div_edit_profile_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_profile');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/profile/update',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_profile").html('<i class="icon-checkmark4"></i> UPDATE PROFILE');
                    if (res.status == 'ERR') {
                        $("#div_edit_profile_error").html(res.message);
                        $("#div_edit_profile_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_profile_success").html(res.message);
                        $("#div_edit_profile_success").fadeIn("fast");

                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_edit_profile").html('<i class="icon-checkmark4"></i> UPDATE PROFILE');
                    $("#div_edit_profile_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_profile_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

//PROFILE CHANGE PASSWORD
function profile_change_password() {
    $("#div_profile_change_password_error").fadeOut("fast");
    $("#div_profile_change_password_success").fadeOut("fast");

    if ($("#frm_profile_change_password").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_profile_change_password").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_profile_change_password").html('<i class="icon-checkmark4"></i> CHANGE PASSWORD');
            $("#div_profile_change_password_error").html($valmsg);
            $("#div_profile_change_password_error").fadeIn("fast");
        } else {

            var form = document.getElementById('frm_profile_change_password');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/profile/change_password',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_profile_change_password").html('<i class="icon-checkmark4"></i> CHANGE PASSWORD');
                    if (res.status == 'ERR') {
                        $("#div_profile_change_password_error").html(res.message);
                        $("#div_profile_change_password_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_profile_change_password_success").html(res.message);
                        $("#div_profile_change_password_success").fadeIn("fast");

                        $('#frm_profile_change_password').each(function() {
                            this.reset();
                        });
                    }
                },
                error: function() {
                    $("#btn_profile_change_password").html('<i class="icon-checkmark4"></i> CHANGE PASSWORD');
                    $("#div_profile_change_password_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_profile_change_password_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

//USER ROLES
function user_role_add_clear() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    $('#frm_add_user_role').each(function() {
        this.reset();
    });
}

//SAVE USER ROLE
function save_user_role() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    if ($("#frm_add_user_role").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_user_role").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_user_role").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_add_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_user_role');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/user_roles/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_user_role").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                        $('html, body').animate({
			                scrollTop: $('#div_add_error').offset().top - 90
			            }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_user_role').each(function() {
                            this.reset();
                        });

                        //load_user_roles();
                        $('html, body').animate({
			                scrollTop: $('#div_add_success').offset().top - 90
			            }, 'slow');
                    }
                },
                error: function() {
                    $("#btn_add_user_role").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                    $('html, body').animate({
		                scrollTop: $('#div_add_error').offset().top - 90
		            }, 'slow');
                }
            });

        }
    }
    return false;
}

function load_user_roles() {
    $("#user_roles_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/user_roles/load_js',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#user_roles_div").html(result);

            setInterval(function() {
                $("#user_roles_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#user_roles_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function user_role_edit_load(user_role_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $('#frm_edit_user_role').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/user_roles/get_user_role/' + user_role_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_user_role_id").val(obj['user_role_id']);
                $("#edit_user_role_name").val(obj['user_role_name']);
                $("#edit_user_role_description").val(obj['user_role_description']);
                $("#edit_sort_key").val(obj['sort_key']);
                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").prop('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").prop('checked', true).change();
                }

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
//UPDATE USER ROLE
function update_user_role() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    if ($("#frm_edit_user_role").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_user_role").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_user_role").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_edit_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_user_role');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/user_roles/update',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_user_role").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                        $('html, body').animate({
			                scrollTop: $('#div_edit_error').offset().top - 90
			            }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        $('html, body').animate({
			                scrollTop: $('#div_edit_success').offset().top - 90
			            }, 'slow');

                        //load_user_roles();
                    }
                },
                error: function() {
                    $("#btn_edit_user_role").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                    $('html, body').animate({
		                scrollTop: $('#div_edit_error').offset().top - 90
		            }, 'slow');
                }
            });

        }
    }
    return false;
}

function delete_user_role(user_role_id) {
    swal({
        text: 'Do you wish to delete this User Role?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/user_roles/delete/' + user_role_id,
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
                            load_user_roles();
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
}

//PRODUCTS
function calculate_price_ex_tax(){
	var selling_price = $("#selling_price").val();
	var price_markup = $("#price_markup").val();
	if(!isNaN(selling_price)){ selling_price = parseFloat(selling_price); }else{ selling_price = 0; }
	if(!isNaN(price_markup)){ price_markup = parseFloat(price_markup); }else{ price_markup = 0; }

	var price_ex_tax = (selling_price + (selling_price * (price_markup/100)));

	$("#price_ex_tax").val(parseFloat(price_ex_tax));

	calculate_price_incl_tax();
}
function calculate_price_incl_tax(){
	var selling_price = $("#selling_price").val();
	var price_markup = $("#price_markup").val();
	var tax_rate_id = $("#product_tax_rate_id").val();

	if(!isNaN(selling_price)){ selling_price = parseFloat(selling_price); }else{ selling_price = 0; }
	if(!isNaN(price_markup)){ price_markup = parseFloat(price_markup); }else{ price_markup = 0; }
	if(isNaN(tax_rate_id) || tax_rate_id == ''){ tax_rate_id = 0; }else{ tax_rate_id = parseFloat(tax_rate_id); }


	$.ajax({
        url: baseDir + 'be/tax_rates/get_tax_rate/' + tax_rate_id,
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
            	var price_ex_tax = (selling_price + (selling_price * (price_markup/100)));
            	if (tax_rate_id == 0){
            		var price_incl_tax = (price_ex_tax + (price_ex_tax * 0/100));
            	}else{
	                var obj1 = res;
	                obj1 = obj1.replace('[', '');
	                obj1 = obj1.replace(']', '');
	                var obj = $.parseJSON(obj1);
	                
	                var price_incl_tax = (price_ex_tax + (price_ex_tax * (parseFloat(obj['tax_rate_value'])/100)));
            	}

				$("#price_incl_tax").val(parseFloat(price_incl_tax));

            } catch (err) {
                //alert(err);
            }
        },
        error: function() {}
    });

	
}

//SAVE PRODUCT
function save_product() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    CKEDITOR.instances['product_short_description'].updateElement();
    CKEDITOR.instances['product_description'].updateElement();

    if ($("#frm_add_product").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_product").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_product").html('<i class="icon-checkmark4"></i> PUBLISH PRODUCT');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_add_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_product');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/products/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function(){
                    $("#publish_status").val('Publishing');
                },
                success: function(res) {
                    $("#btn_add_product").html('<i class="icon-checkmark4"></i> PUBLISH PRODUCT');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                         $('html, body').animate({
			                scrollTop: $('#div_add_error').offset().top - 90
			            }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('html, body').animate({
			                scrollTop: $('#div_add_success').offset().top - 90
			            }, 'slow');

                        setTimeout(function() {
                            location.reload();
                            // $("#div_add_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_add_product").html('<i class="icon-checkmark4"></i> PUBLISH PRODUCT');
                    $("#div_add_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                     $('html, body').animate({
		                scrollTop: $('#div_add_error').offset().top - 90
		            }, 'slow');
                }
            });

        }
    }
    return false;
}

function autosave_product() {
    CKEDITOR.instances['product_short_description'].updateElement();
    CKEDITOR.instances['product_description'].updateElement();

    var product_id = $('#product_id').val();
    var product_name = $('#product_name').val();
    var publish_status = $("#publish_status").val();

    if (publish_status != 'Publishing') {
        if (product_name != '' && product_name != null) {

            var form = document.getElementById('frm_add_product');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/products/autosave',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function(){
                    $("#btn_save_product_draft").html('<i class="icon-file-text2"></i> SAVE DRAFT <i class="fa fa-spinner fa-spin ml-2"></i>');
                },
                success: function(res) {
                    $("#btn_save_product_draft").html('<i class="icon-file-text2"></i> SAVE DRAFT');
                    if (res.status == 'SUCCESS') {
                        $("#product_id").val(res.product_id);
                    }
                },
                error: function() {
                    $("#btn_save_product_draft").html('<i class="icon-file-text2"></i> SAVE DRAFT');
                }
            });
        }
    }
}

function load_products() {
    $("#products_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/products/load_js',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#products_div").html(result);

            setInterval(function() {
                $("#products_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#products_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

//UPDATE PRODUCT
function update_product() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    CKEDITOR.instances['product_short_description'].updateElement();
    CKEDITOR.instances['product_description'].updateElement();

    if ($("#frm_edit_product").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_product").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_product").html('<i class="icon-checkmark4"></i> UPDATE PRODUCT');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
            $("#div_add_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_edit_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_product');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/products/update',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_product").html('<i class="icon-checkmark4"></i> UPDATE PRODUCT');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        $('html, body').animate({
			                scrollTop: $('#div_edit_error').offset().top - 90
			            }, 'slow');

			            // setTimeout(function() {
               //              location.reload();
               //          }, 2000);
                    }
                },
                error: function() {
                    $("#btn_edit_product").html('<i class="icon-checkmark4"></i> UPDATE PRODUCT');
                    $("#div_edit_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                    $('html, body').animate({
		                scrollTop: $('#div_edit_error').offset().top - 90
		            }, 'slow');
                }
            });

        }
    }
    return false;
}

function delete_product(product_id) {
    swal({
        text: 'Do you wish to delete this Product?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/products/delete/' + product_id,
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
                            load_products();
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
}

function set_product_online_status(product_id, is_online) {
    swal({
        text: "Do you wish to change this Product's online status?",
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/products/set_product_online_status',
                type: 'POST',
                data: {product_id: product_id, is_online: is_online },
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
                            load_products();
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
}

function set_product_featured_status(product_id, is_featured) {
    swal({
        text: "Do you wish to change this Product's Featured status?",
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/products/set_product_featured_status',
                type: 'POST',
                data: {product_id: product_id, is_featured: is_featured },
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
                            load_products();
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
}

function set_product_new_arrival_status(product_id, is_new_arrival) {
    swal({
        text: "Do you wish to change this Product's New Arrival status?",
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/products/set_product_new_arrival_status',
                type: 'POST',
                data: {product_id: product_id, is_new_arrival: is_new_arrival },
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
                            load_products();
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
}

function set_product_special_offer_status(product_id, is_special_offer) {
    swal({
        text: "Do you wish to change this Product's Special Offer status?",
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/products/set_product_special_offer_status',
                type: 'POST',
                data: {product_id: product_id, is_special_offer: is_special_offer },
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
                            load_products();
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
}

function set_product_deal_of_the_week_status(product_id, is_deal_of_the_week) {
    swal({
        text: "Do you wish to change this Product's Deal of the Week status?",
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/products/set_product_deal_of_the_week_status',
                type: 'POST',
                data: {product_id: product_id, is_deal_of_the_week: is_deal_of_the_week },
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
                            load_products();
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
}



function upload_add_product_other_image(){

    $("#div_product_add_other_image_success").fadeOut("fast");
    $("#div_product_add_other_image_error").fadeOut("fast");

    if ($("#frm_product_add_other_image").valid()) {

        $other_image = $("#product_add_other_image").val();
        $other_image_id = $("#product_add_other_image_id").val();

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_product_add_other_image").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($other_image != ""){
            $allowed_extensions = new Array("png","jpg","jpeg","gif");
            $file_extension = $other_image.split('.').pop();

            $found = false;
            for(var i = 0; i <= $allowed_extensions.length; i++){
                if($allowed_extensions[i]==$file_extension){
                    $found = true;
                    break;
                }
            }

            if ($found == false){
                $valmsg = $valmsg + "<i class='fa fa-exclamation-circle'></i> The file you chose has an incorrect format. Only files with the following extensions are allowed: .png, .jpg, .jpeg, .gif <br/>";
            }
        }

        if ($valmsg != $valmsg2){
            $("#btn_product_add_other_image").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_product_add_other_image_error").html($valmsg);
            $("#div_product_add_other_image_error").fadeIn("fast");
        }else{

            var form = document.getElementById("frm_product_add_other_image");
            var formData = new FormData(form);

            $.ajax({
                url: baseDir+'be/products/upload_add_other_image',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function (res) {
                    $("#btn_product_add_other_image").html('<i class="icon-checkmark4"></i> SAVE');
                    if(res.status == 'ERR'){
                        $("#div_product_add_other_image_error").html(res.message);
                        $("#div_product_add_other_image_error").fadeIn("fast");
                    }else if (res.status == 'SUCCESS'){
                        $("#div_product_add_other_image_success").html(res.message);
                        $("#div_product_add_other_image_success").fadeIn("fast");

                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                },
                error: function(){
                    $("#btn_product_add_other_image").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#product_add_other_image_loader").hide();
                    $("#div_product_add_other_image_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_product_add_other_image_error").fadeIn("fast");
                }
            });
        }
    }
    return false;
}

function pa_unit_of_measure(){
    $("#div_pa_unit_of_measure_error").fadeOut("fast");
    $("#div_pa_unit_of_measure_success").fadeOut("fast");

    if ($("#frm_pa_unit_of_measure").valid()) {

        var context = $('#pa_context').val();

        if (context == 'Add') {
            var unit_id = $('#pa_unit_id').val();
            if ($('#unit_id').val() == unit_id){
                $("#div_pa_unit_of_measure_error").html("This unit has already been set as the Base Unit. Please select a different unit.");
                $("#div_pa_unit_of_measure_error").fadeIn("fast");
            } else {
                if($('#pa_unit_id_' + unit_id).length){
                    $("#div_pa_unit_of_measure_error").html("You have already added this unit. Please select a different unit.");
                    $("#div_pa_unit_of_measure_error").fadeIn("fast");
                }else{
                    var unit_name = $("#pa_unit_id option:selected").text();
                    var regular_price = $('#pa_regular_price').val();
                    var sale_price = $('#pa_sale_price').val();
                    var minimum_selling_price = $('#pa_minimum_selling_price').val();
                    
                    $('#pa_units_of_measure_table').append('<tr> \
                        <td><span id="pa_unit_id_label_' + unit_id + '">' + unit_name + '</span><input type="hidden" id="pa_unit_id_' + unit_id + '" class="pa_unit_id" name="pa_unit_id[]" value="' + unit_id + '"></td> \
                        <td><div class="input-group"><span class="input-group-prepend"><span class="input-group-text"></span></span><input type="number" id="pa_regular_price_' + unit_id + '" name="pa_regular_price[]" value="' + regular_price + '" class="form-control"></div></td> \
                        <td><div class="input-group"><span class="input-group-prepend"><span class="input-group-text"></span></span><input type="number" id="pa_sale_price_' + unit_id + '" name="pa_sale_price[]" value="' + sale_price + '" class="form-control"></div></td> \
                        <td><div class="input-group"><span class="input-group-prepend"><span class="input-group-text"></span></span><input type="number" id="pa_minimum_selling_price_' + unit_id + '" name="pa_minimum_selling_price[]" value="' + minimum_selling_price + '" class="form-control"></div></td> \
                        <td><a href="javascript:void(0);" class="pa_detail_remove" title="Remove Unit"><i class="icon-cancel-circle2 text-danger"></i></a></td> \
                    </tr>');                    
                    $('#modal_pa_unit_of_measure').modal('toggle');
                    check_display_pa_units_of_measure();
                }
            }
        } else if (context == 'Edit') {
            
        }

    }
    return false;
}

function check_display_pa_units_of_measure() {
    if($('.pa_unit_id').length){
        $('#div_pa_units_of_measure').fadeIn('slow');
    } else {
        $('#div_pa_units_of_measure').fadeOut('slow');
    }
}



//CUSTOMER GROUPS
function customer_group_add_clear() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    $('#frm_add_customer_group').each(function() {
        this.reset();
    });
}

//SAVE CUSTOMER GROUP
function save_customer_group() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    CKEDITOR.instances['add_customer_group_description'].updateElement();

    if ($("#frm_add_customer_group").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_customer_group").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_customer_group").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_customer_group');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/customers/save_customer_group',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_customer_group").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_customer_group').each(function() {
                            this.reset();
                        });

                        CKEDITOR.instances['add_customer_group_description'].setData('');

                        load_customer_groups();

                        setTimeout(function() {
                            $("#div_add_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_add_customer_group").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_customer_groups() {
    $("#customer_groups_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/customers/loadjs_customer_groups',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#customer_groups_div").html(result);

            setInterval(function() {
                $("#customer_groups_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#customer_groups_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function customer_group_edit_load(customer_group_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $.ajax({
        url: baseDir + 'be/customers/get_customer_group2/' + customer_group_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_customer_group_id").val(obj['customer_group_id']);
                $("#edit_customer_group_name").val(obj['customer_group_name']);
                CKEDITOR.instances['edit_customer_group_description'].setData(obj['customer_group_description']);
                //$("#edit_customer_group_description").val(obj['customer_group_description']);
                $("#edit_sort_key").val(obj['sort_key']);
                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").attr('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").attr('checked', true).change();
                }

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
//UPDATE CUSTOMER GROUP
function update_customer_group() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    CKEDITOR.instances['edit_customer_group_description'].updateElement();

    if ($("#frm_edit_customer_group").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_customer_group").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_customer_group").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_customer_group');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/customers/update_customer_group',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_customer_group").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        load_customer_groups();

                        setTimeout(function() {
                            $("#div_edit_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_edit_customer_group").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_customer_group(customer_group_id) {
    swal({
        text: 'Do you wish to delete this Customer Group?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/customers/delete_customer_group/' + customer_group_id,
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
                            load_customer_groups();
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
}

//CUSTOMERS
function customer_add_clear() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    $('#frm_add_customer').each(function() {
        this.reset();
    });
    $("#add_gender").val('').change();
    $("#add_customer_group_id").val('').change();
    $("#add_customer_country_id").val('').change();
    $("#add_customer_city_id").val('').change();
    $("#add_reference_customer_id").val('').change();
}

//SAVE CUSTOMER
function save_customer() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    if ($("#frm_add_customer").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_customer").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_customer").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_add_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_customer');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/customers/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_customer").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                        $('html, body').animate({
			                scrollTop: $('#div_add_error').offset().top - 90
			            }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_customer').each(function() {
                            this.reset();
                        });
                        $("#gender").val('').change();
					    $("#customer_group_id").val('').change();
					    $("#shipping_country_id").val('').change();
					    $("#shipping_region_id").val('').change();
					    $("#billing_country_id").val('').change();
					    $("#billing_region_id").val('').change();
					    $("#reference_customer_id").val('').change();

                        $('html, body').animate({
			                scrollTop: $('#div_add_success').offset().top - 90
			            }, 'slow');
                    }
                },
                error: function() {
                    $("#btn_add_customer").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                    $('html, body').animate({
		                scrollTop: $('#div_add_error').offset().top - 90
		            }, 'slow');
                }
            });

        }
    }
    return false;
}

function load_customers() {
    $("#customers_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/customers/load_js',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#customers_div").html(result);

            setInterval(function() {
                $("#customers_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#customers_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function customer_edit_load(customer_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $('#frm_edit_customer').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/customers/get_customer/' + customer_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_customer_id").val(obj['customer_id']);
                $("#edit_first_name").val(obj['first_name']);
                $("#edit_last_name").val(obj['last_name']);
                $("#edit_gender").val(obj['gender']).change();
                $("#edit_date_of_birth").val(obj['date_of_birth']);
                $("#edit_company_name").val(obj['company_name']);
                $("#edit_customer_group_id").val(obj['customer_group_id']).change();
                $("#edit_email_address").val(obj['email_address']);
                $("#edit_phone_number").val(obj['phone_number']);
                $("#edit_postal_address").val(obj['postal_address']);
                $("#edit_postal_code").val(obj['postal_code']);
                $("#edit_customer_country_id").val(obj['country_id']).change();
                cur_city_id = obj['city_id'];
                $("#edit_customer_code").val(obj['customer_code']);
                $("#edit_opening_balance").val(obj['opening_balance']);
                $("#edit_credit_limit").val(obj['credit_limit']);
                if (obj['loyalty_enrolled'] == 0) {
                    //$("#edit_loyalty_enrolled").removeAttr("checked").change();
                    $('.form-check-input-switchery').click();
                } else if (obj['loyalty_enrolled'] == 1) {
                    //$("#edit_loyalty_enrolled").attr('checked', true).change();
                }
                $('.form-check-input-switchery').click();
                $("#edit_loyalty_enrollment_date").val(obj['loyalty_enrollment_date']);
                $("#edit_reference_customer_id").val(obj['reference_customer_id']).change();
                $("#edit_sort_key").val(obj['sort_key']);
                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").prop('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").prop('checked', true).change();
                }

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
//UPDATE CUSTOMER
function update_customer() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    if ($("#frm_edit_customer").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_customer").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_customer").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_edit_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_customer');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/customers/update',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_customer").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                        $('html, body').animate({
			                scrollTop: $('#div_edit_error').offset().top - 90
			            }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        $('html, body').animate({
			                scrollTop: $('#div_edit_success').offset().top - 90
			            }, 'slow');

			            setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_edit_customer").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                    $('html, body').animate({
		                scrollTop: $('#div_edit_error').offset().top - 90
		            }, 'slow');
                }
            });

        }
    }
    return false;
}

function delete_customer(customer_id) {
    swal({
        text: 'Do you wish to delete this Customer?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/customers/delete/' + customer_id,
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
                            load_customers();
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
}


//SUPPLIERS
function supplier_add_clear() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    $('#frm_add_supplier').each(function() {
        this.reset();
    });

    $("#add_supplier_country_id").val('').change();
    $("#add_supplier_city_id").val('').change();
}

//SAVE SUPPLIER
function save_supplier() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    if ($("#frm_add_supplier").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_supplier").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_supplier").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_supplier');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/suppliers/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_supplier").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_supplier').each(function() {
                            this.reset();
                        });

                        $("#add_supplier_country_id").val('').change();
    					$("#add_supplier_city_id").val('').change();

                        load_suppliers();

                        setTimeout(function() {
                            $("#div_add_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_add_supplier").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_suppliers() {
    $("#suppliers_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/suppliers/load_js',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#suppliers_div").html(result);

            setInterval(function() {
                $("#suppliers_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#suppliers_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function supplier_edit_load(supplier_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $('#frm_edit_supplier').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/suppliers/get_supplier/' + supplier_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_supplier_id").val(obj['supplier_id']);
                $("#edit_supplier_name").val(obj['supplier_name']);
                $("#edit_supplier_code").val(obj['supplier_code']);
                $("#edit_phone_number").val(obj['phone_number']);
                $("#edit_email_address").val(obj['email_address']);
                $("#edit_postal_address").val(obj['postal_address']);
                $("#edit_website").val(obj['website']);
                $("#edit_postal_code").val(obj['postal_code']);
                $("#edit_supplier_country_id").val(obj['country_id']).change();
                cur_city_id = obj['region_id'];
                $("#edit_supplier_note").val(obj['supplier_note']);
                $("#edit_contact_person_first_name").val(obj['contact_person_first_name']);
                $("#edit_contact_person_last_name").val(obj['contact_person_last_name']);
                $("#edit_contact_person_mobile_number").val(obj['contact_person_mobile_number']);
                $("#edit_contact_person_email_address").val(obj['contact_person_email_address']);
                $("#edit_sort_key").val(obj['sort_key']);
                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").prop('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").prop('checked', true).change();
                }

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
//UPDATE SUPPLIER
function update_supplier() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    if ($("#frm_edit_supplier").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_supplier").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_supplier").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_supplier');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/suppliers/update',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_supplier").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        load_suppliers();

                        setTimeout(function() {
                            $("#div_edit_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_edit_supplier").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_supplier(supplier_id) {
    swal({
        text: 'Do you wish to delete this Supplier?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/suppliers/delete/' + supplier_id,
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
                            load_suppliers();
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
}

//PREFIXES
function load_prefixes() {
    $("#prefixes_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/settings/loadjs_prefixes',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#prefixes_div").html(result);

            setInterval(function() {
                $("#prefixes_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#prefixes_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function prefix_edit_load(prefix_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $.ajax({
        url: baseDir + 'be/settings/get_prefix2/' + prefix_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_prefix_id").val(obj['prefix_id']);
                $("#edit_document_name").val(obj['document_name']);
                $("#edit_prefix_name").val(obj['prefix_name']);
                $("#edit_current_value").val(obj['current_value']);
            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
//UPDATE prefix
function update_prefix() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    if ($("#frm_edit_prefix").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_prefix").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_prefix").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_prefix');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/settings/update_prefix',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_prefix").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        load_prefixes();

                        setTimeout(function() {
                            $("#div_edit_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_edit_prefix").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}


//PURCHASE ORDERS
function filter_purchase_orders(){
    $("#purchase_orders_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $("#btn_purchase_orders_filter").html('FILTER <i class="icon-spinner2 spinner"></i>');

    var form = document.getElementById('frm_filter_purchase_orders');
    var formData = new FormData(form);
    $.ajax({
        url: baseDir + 'be/inventory/filter_js_purchase_orders',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#purchase_orders_div").html(result);

            setInterval(function() {
                $("#purchase_orders_div").LoadingOverlay("hide");
            }, 1000);
            $("#btn_purchase_orders_filter").html('FILTER');
        },
        error: function() {
            setInterval(function() {
                $("#purchase_orders_div").LoadingOverlay("hide");
            }, 1000);
            $("#btn_purchase_orders_filter").html('FILTER');
        }
    });
}
function export_purchases_report(){

    var dateFrom = $('#date_from').val();
    var dateTo = $('#date_to').val();
    var status = $('#purchase_order_status').val();
    var statusText = $("#purchase_order_status option:selected").text();
    var paymentStatus = $('#payment_status').val();
    var supplierId = $('#supplier_id').val();
    var supplierName = $("#supplier_id option:selected").text();
    var systemUserId = $('#system_user_id').val();
    var systemUserName = $("#system_user_id option:selected").text();

    $.redirect(
        baseDir + 'be/inventory/export_purchases_report',
        {
            date_from: dateFrom,
            date_to: dateTo, 
            purchase_order_status: status,
            purchase_order_status_text: statusText,
            payment_status: paymentStatus,
            supplier_id: supplierId,
            supplier_name: supplierName,
            system_user_id: systemUserId,
            system_user_name: systemUserName
        },
        "POST",
        "_blank"
    );
}
function calculate_po_detail_total(id){
	var order_qty = parseFloat($("#po_detail_qty_"+id).val());
	var cost = parseFloat($("#po_detail_cost_"+id).val());
	var total = order_qty * cost;
	document.getElementById("po_label_detail_total_"+id).innerHTML = total.formatMoney(2,',','.');
	$("#po_detail_total_"+id).val(parseFloat(total).toFixed(2));
}
function calculate_po_totals(){
	//TOTAL QUANTITY
	var total_detail_qty = 0;
	$(".po_detail_qty").each(function() {
		var detail_qty = parseFloat($(this).val());
		total_detail_qty = total_detail_qty + detail_qty;
   	});
	document.getElementById("po_label_total_detail_qty").innerHTML = total_detail_qty.formatMoney(2,',','.');
	$("#po_total_detail_qty").val(parseFloat(total_detail_qty));

	//TOTAL TOTAL
	var total_detail_total = 0;
	var freight_cost = parseFloat($("#po_freight_cost").val());
	$(".po_detail_total").each(function() {
		var detail_total = parseFloat($(this).val());
		total_detail_total = total_detail_total + detail_total;
   	});
   	//SUBTOTAL
   	document.getElementById("po_label_total_detail_subtotal").innerHTML = total_detail_total.formatMoney(2,',','.');
   	$("#po_total_detail_subtotal").val(parseFloat(total_detail_total).toFixed(2));

   	//TOTAL
   	total_detail_total = total_detail_total + freight_cost;
	document.getElementById("po_label_total_detail_total").innerHTML = total_detail_total.formatMoney(2,',','.');
	$("#po_total_detail_total").val(parseFloat(total_detail_total).toFixed(2));
}

//SAVE PURCHASE ORDER
function save_purchase_order() {
    $("#div_add_purchase_order_error").fadeOut("fast");
    $("#div_add_purchase_order_success").fadeOut("fast");

    if ($("#frm_add_purchase_order").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_purchase_order").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($(".po_detail_qty").filter(function() { return $(this).val(); }).length <= 0) {
			$valmsg = "Please add atleast one (1) product to purchase";
		}

        if ($valmsg != $valmsg2) {
            $("#btn_add_purchase_order").html('<i class="icon-checkmark4"></i> SAVE PURCHASE ORDER');
            $("#div_add_purchase_order_error").html($valmsg);
            $("#div_add_purchase_order_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_add_purchase_order_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_add_purchase_order_error").fadeOut("fast");

            var form = document.getElementById('frm_add_purchase_order');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/inventory/save_purchase_order',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {                    
                    if (res.status == 'ERR') {
                    	$("#btn_add_purchase_order").html('<i class="icon-checkmark4"></i> SAVE PURCHASE ORDER');
                        $("#div_add_purchase_order_error").html(res.message);
                        $("#div_add_purchase_order_error").fadeIn("fast");
                         $('html, body').animate({
			                scrollTop: $('#div_add_purchase_order_error').offset().top - 90
			            }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_purchase_order_success").html(res.message);
                        $("#div_add_purchase_order_success").fadeIn("fast");

                        $('#frm_add_purchase_order').each(function() {
                            this.reset();
                        });
                        $("#supplier_id").val('').change();

			            setTimeout(function() {
                            window.location = baseDir+'be/inventory/purchase_order_detail/'+res.id;
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_add_purchase_order").html('<i class="icon-checkmark4"></i> SAVE PURCHASE ORDER');
                    $("#div_add_purchase_order_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_purchase_order_error").fadeIn("fast");
                     $('html, body').animate({
		                scrollTop: $('#div_add_purchase_order_error').offset().top - 90
		            }, 'slow');
                }
            });

        }
    }
    return false;
}

function update_purchase_order() {
    $("#div_edit_purchase_order_error").fadeOut("fast");
    $("#div_edit_purchase_order_success").fadeOut("fast");

    if ($("#frm_edit_purchase_order").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_purchase_order").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($(".po_detail_qty").filter(function() { return $(this).val(); }).length <= 0) {
			$valmsg = "Please add atleast one (1) product to the purchase order";
		}

        if ($valmsg != $valmsg2) {
            $("#btn_edit_purchase_order").html('<i class="icon-checkmark4"></i> UPDATE PURCHASE ORDER');
            $("#div_edit_purchase_order_error").html($valmsg);
            $("#div_edit_purchase_order_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_edit_purchase_order_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_edit_purchase_order_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_purchase_order');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/inventory/update_purchase_order',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {                    
                    if (res.status == 'ERR') {
                    	$("#btn_edit_purchase_order").html('<i class="icon-checkmark4"></i> UPDATE PURCHASE ORDER');
                        $("#div_edit_purchase_order_error").html(res.message);
                        $("#div_edit_purchase_order_error").fadeIn("fast");
                         $('html, body').animate({
			                scrollTop: $('#div_edit_purchase_order_error').offset().top - 90
			            }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_purchase_order_success").html(res.message);
                        $("#div_edit_purchase_order_success").fadeIn("fast");

			            setTimeout(function() {
                            window.location = baseDir+'be/inventory/purchase_order_detail/'+res.id;
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_edit_purchase_order").html('<i class="icon-checkmark4"></i> UPDATE PURCHASE ORDER');
                    $("#div_edit_purchase_order_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_purchase_order_error").fadeIn("fast");
                     $('html, body').animate({
		                scrollTop: $('#div_edit_purchase_order_error').offset().top - 90
		            }, 'slow');
                }
            });

        }
    }
    return false;
}

function submit_purchase_order_payment(){
    if ($("#frm_record_purchase_order_payment").valid()) {
        swal({
            text: 'Do you wish to submit this Payment?',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                var form = document.getElementById('frm_record_purchase_order_payment');
                var formData = new FormData(form);

                $.ajax({
                    type: 'POST',
                    url: baseDir + 'be/inventory/submit_purchase_order_payment',
                    data: formData,
                    contentType: false,
                    processData: false, 
                    cache: false,
                    dataType: 'json',
                    beforeSend: function(){
                        $("#btn_submit_purchase_order_payment").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');
                    },
                    success: function(res){
                        $("#btn_submit_purchase_order_payment").html('<i class="icon-checkmark-circle2 mr-1"></i>Submit Payment');
                        if (res.status == 'ERR') {
                            swal({ type: 'warning', title: 'Error', html: res.message });
                        } else {
                            $('#modal_purchase_order_payment').modal('toggle');

                            var context = $('#payment_context').val();

                            if (context == 'View Purchase Order') {
                                location.reload();
                            } else if (context == 'Purchase Orders List') {
                                filter_purchase_orders();
                            }

                        }
                    },
                    error: function(){
                        $("#btn_submit_purchase_order_payment").html('<i class="icon-checkmark-circle2 mr-1"></i>Submit Payment');
                    }
                });
            }
        });
    }

    return false;
}

function submit_void_purchase_payment(){
    if ($("#frm_void_purchase_payment").valid()) {

        swal({
            text: 'Do you wish to void this Payment? Please note that this action is IRREVERSIBLE',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                var form = document.getElementById('frm_void_purchase_payment');
                var formData = new FormData(form);

                $.ajax({
                    type: 'POST',
                    url: baseDir + 'be/inventory/submit_void_purchase_payment',
                    data: formData,
                    contentType: false,
                    processData: false, 
                    cache: false,
                    dataType: 'json',
                    beforeSend: function(){
                        $("#btn_void_purchase_payment").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');
                    },
                    success: function(res){
                        $("#btn_void_purchase_payment").html('<i class="icon-checkmark-circle mr-1"></i>Submit');
                        if (res.status == 'ERR') {
                            swal({ type: 'warning', title: 'Error', html: res.message });
                        } else {
                            $('#modal_void_purchase_payment').modal('toggle');
                            location.reload();
                            //load_pending_sale_info();
                            // var transaction_type = $('#transaction_type').val();

                            // if (transaction_type == 'Add') {
                            //     load_pending_sale_info();
                            // } else if (transaction_type == 'Edit') {
                            //     var purchase_order_id = $('#purchase_order_id').val();
                            //     load_edit_sale_info(purchase_order_id);
                            // }
                            //load_sale_products();
                        }
                    },
                    error: function(){
                        $("#btn_void_purchase_payment").html('<i class="icon-checkmark-circle mr-1"></i>Submit');
                    }
                });
            }
        });
    }

    return false;
}

function submit_modify_purchase_payment(){

    if ($("#frm_modify_purchase_payment").valid()) {

        var form = document.getElementById('frm_modify_purchase_payment');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/submit_modify_purchase_payment',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_submit_modify_purchase_payment").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');
            },
            success: function(res){
                $("#btn_submit_modify_purchase_payment").html('<i class="icon-checkmark-circle2 mr-1"></i>Update');
                if (res.status == 'ERR') {
                    swal({ type: 'warning', title: 'Error', html: res.message });
                } else {
                    $('#modal_modify_purchase_payment').modal('toggle');
                    location.reload();
                    //load_pending_sale_info();
                    // var transaction_type = $('#transaction_type').val();

                    // if (transaction_type == 'Add') {
                    //     load_pending_sale_info();
                    // } else if (transaction_type == 'Edit') {
                    //     var purchase_order_id = $('#purchase_order_id').val();
                    //     load_edit_sale_info(purchase_order_id);
                    // }
                    //load_sale_products();
                }
            },
            error: function(){
                $("#btn_submit_modify_purchase_payment").html('<i class="icon-checkmark-circle2 mr-1"></i>Update');
            }
        });
    }

    return false;
}

function submit_send_purchase_order_via_email(){
    if ($("#frm_send_purchase_order_via_email").valid()) {

        var form = document.getElementById('frm_send_purchase_order_via_email');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/submit_send_purchase_order_via_email',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_send_pos_purchase_order_via_email").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');
            },
            success: function(res){
                $("#btn_send_pos_purchase_order_via_email").html('<em class="icon ni ni-send mr-1"></em>Send Email');
                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: res.message
                    });
                } else {
                    swal({
                        type: 'success',
                        title: res.message
                    });
                }
            },
            error: function(){
                $("#btn_send_pos_purchase_order_via_email").html('<em class="icon ni ni-send mr-1"></em>Send Email');
            }
        });
    }
    return false;
}

function submit_void_purchase_order(){
    if ($("#frm_void_purchase_order").valid()) {

        var form = document.getElementById('frm_void_purchase_order');
        var formData = new FormData(form);

        var context = $('#void_context').val();

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/submit_void_purchase_order',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_void_purchase_order").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');
            },
            success: function(res){
                $("#btn_void_purchase_order").html('<i class="icon-checkmark4 mr-1"></i>Submit');
                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: res.message
                    });
                } else {
                    swal({
                        type: 'success',
                        title: res.message
                    });
                    $('#modal_void_purchase_order').modal('toggle');

                    if (context == 'Purchase Orders List'){
                        filter_purchase_orders();
                    } else if (context == 'View Purchase Order') {
                        location.reload();
                    }
                }
            },
            error: function(){
                $("#btn_void_purchase_order").html('<i class="icon-checkmark4 mr-1"></i>Submit');
            }
        });
    }
    return false; 
}


//GOODS RECEIPT NOTES
function filter_goods_receipt_notes(){
    $("#goods_receipt_notes_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $("#btn_goods_receipt_notes_filter").html('FILTER <i class="icon-spinner2 spinner"></i>');

    var form = document.getElementById('frm_filter_goods_receipt_notes');
    var formData = new FormData(form);

    $.ajax({
        url: baseDir + 'be/inventory/filter_js_goods_receipt_notes',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#goods_receipt_notes_div").html(result);

            setInterval(function() {
                $("#goods_receipt_notes_div").LoadingOverlay("hide");
            }, 1000);
            $("#btn_goods_receipt_notes_filter").html('FILTER');
        },
        error: function() {
            setInterval(function() {
                $("#goods_receipt_notes_div").LoadingOverlay("hide");
            }, 1000);
            $("#btn_goods_receipt_notes_filter").html('FILTER');
        }
    });

}
function export_goods_receipt_notes(){

    var dateFrom = $('#date_from').val();
    var dateTo = $('#date_to').val();
    var status = $('#status').val();
    var statusText = $("#status option:selected").text();
    var outletId = $('#outlet_id').val();
    var outletName = $("#outlet_id option:selected").text();
    var supplierId = $('#supplier_id').val();
    var supplierName = $("#supplier_id option:selected").text();
    var systemUserId = $('#system_user_id').val();
    var systemUserName = $("#system_user_id option:selected").text();

    $.redirect(
        baseDir + 'be/inventory/export_goods_receipt_notes',
        {
            date_from: dateFrom,
            date_to: dateTo, 
            status: status,
            status_text: statusText,
            outlet_id: outletId,
            outlet_name: outletName,
            supplier_id: supplierId,
            supplier_name: supplierName,
            system_user_id: systemUserId,
            system_user_name: systemUserName
        },
        "POST",
        "_blank"
    );
}
function calculate_grn_detail_total(id){
	var order_qty = parseFloat($("#grn_detail_qty_"+id).val());
	var cost = parseFloat($("#grn_detail_cost_"+id).val());
	var total = order_qty * cost;
	document.getElementById("grn_label_detail_total_"+id).innerHTML = total.formatMoney(2,',','.');
	$("#grn_detail_total_"+id).val(parseFloat(total).toFixed(2));
}
function calculate_grn_totals(){
	//TOTAL QUANTITY
	var total_detail_qty = 0;
	$(".grn_detail_qty").each(function() {
		var detail_qty = parseFloat($(this).val());
		total_detail_qty = total_detail_qty + detail_qty;
   	});
	document.getElementById("grn_label_total_detail_qty").innerHTML = total_detail_qty.formatMoney(2,',','.');
	$("#grn_total_detail_qty").val(parseFloat(total_detail_qty));

	//TOTAL TOTAL
	var total_detail_total = 0;
	var freight_cost = parseFloat($("#grn_freight_cost").val());
	$(".grn_detail_total").each(function() {
		var detail_total = parseFloat($(this).val());
		total_detail_total = total_detail_total + detail_total;
   	});
   	//SUBTOTAL
   	document.getElementById("grn_label_total_detail_subtotal").innerHTML = total_detail_total.formatMoney(2,',','.');
   	$("#grn_total_detail_subtotal").val(parseFloat(total_detail_total).toFixed(2));

   	//TOTAL
   	total_detail_total = total_detail_total + freight_cost;
	document.getElementById("grn_label_total_detail_total").innerHTML = total_detail_total.formatMoney(2,',','.');
	$("#grn_total_detail_total").val(parseFloat(total_detail_total).toFixed(2));
}

//SAVE GOODS RECEIPT NOTE
function save_goods_receipt_note() {
    $("#div_add_goods_receipt_note_error").fadeOut("fast");
    $("#div_add_goods_receipt_note_success").fadeOut("fast");

    if ($("#frm_add_goods_receipt_note").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_goods_receipt_note").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($(".grn_detail_qty").filter(function() { return $(this).val(); }).length <= 0) {
			$valmsg = "Please add atleast one (1) product to receive";
		}

        if ($valmsg != $valmsg2) {
            $("#btn_add_goods_receipt_note").html('<i class="icon-checkmark4"></i> ACCEPT STOCK');
            $("#div_add_goods_receipt_note_error").html($valmsg);
            $("#div_add_goods_receipt_note_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_add_goods_receipt_note_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_add_goods_receipt_note_error").fadeOut("fast");

            var form = document.getElementById('frm_add_goods_receipt_note');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/inventory/save_goods_receipt_note',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {                    
                    if (res.status == 'ERR') {
                    	$("#btn_add_goods_receipt_note").html('<i class="icon-checkmark4"></i> ACCEPT STOCK');
                        $("#div_add_goods_receipt_note_error").html(res.message);
                        $("#div_add_goods_receipt_note_error").fadeIn("fast");
                         $('html, body').animate({
			                scrollTop: $('#div_add_goods_receipt_note_error').offset().top - 90
			            }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_goods_receipt_note_success").html(res.message);
                        $("#div_add_goods_receipt_note_success").fadeIn("fast");

                        $('#frm_add_goods_receipt_note').each(function() {
                            this.reset();
                        });
                        $("#supplier_id").val('').change();

			            setTimeout(function() {
                            window.location = baseDir+'be/inventory/goods_receipt_note_detail/'+res.id;
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_add_goods_receipt_note").html('<i class="icon-checkmark4"></i> ACCEPT STOCK');
                    $("#div_add_goods_receipt_note_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_goods_receipt_note_error").fadeIn("fast");
                     $('html, body').animate({
		                scrollTop: $('#div_add_goods_receipt_note_error').offset().top - 90
		            }, 'slow');
                }
            });

        }
    }
    return false;
}

function update_goods_receipt_note() {
    $("#div_edit_goods_receipt_note_error").fadeOut("fast");
    $("#div_edit_goods_receipt_note_success").fadeOut("fast");

    if ($("#frm_edit_goods_receipt_note").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_goods_receipt_note").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($(".grn_detail_qty").filter(function() { return $(this).val(); }).length <= 0) {
			$valmsg = "Please add atleast one (1) product to receive";
		}

        if ($valmsg != $valmsg2) {
            $("#btn_edit_goods_receipt_note").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_goods_receipt_note_error").html($valmsg);
            $("#div_edit_goods_receipt_note_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_edit_goods_receipt_note_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_edit_goods_receipt_note_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_goods_receipt_note');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/inventory/update_goods_receipt_note',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {                    
                    if (res.status == 'ERR') {
                    	$("#btn_edit_goods_receipt_note").html('<i class="icon-checkmark4"></i> UPDATE');
                        $("#div_edit_goods_receipt_note_error").html(res.message);
                        $("#div_edit_goods_receipt_note_error").fadeIn("fast");
                         $('html, body').animate({
			                scrollTop: $('#div_edit_goods_receipt_note_error').offset().top - 90
			            }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_goods_receipt_note_success").html(res.message);
                        $("#div_edit_goods_receipt_note_success").fadeIn("fast");

			            setTimeout(function() {
                            window.location = baseDir+'be/inventory/goods_receipt_note_detail/'+res.id;
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_edit_goods_receipt_note").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_goods_receipt_note_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_goods_receipt_note_error").fadeIn("fast");
                     $('html, body').animate({
		                scrollTop: $('#div_edit_goods_receipt_note_error').offset().top - 90
		            }, 'slow');
                }
            });

        }
    }
    return false;
}
function submit_send_goods_receipt_note_via_email(){
    if ($("#frm_send_goods_receipt_note_via_email").valid()) {

        var form = document.getElementById('frm_send_goods_receipt_note_via_email');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/submit_send_goods_receipt_note_via_email',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_send_goods_receipt_note_via_email").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');
            },
            success: function(res){
                $("#btn_send_goods_receipt_note_via_email").html('<em class="icon ni ni-send mr-1"></em>Send Email');
                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: res.message
                    });
                } else {
                    swal({
                        type: 'success',
                        title: res.message
                    });
                }
            },
            error: function(){
                $("#btn_send_goods_receipt_note_via_email").html('<em class="icon ni ni-send mr-1"></em>Send Email');
            }
        });
    }
    return false;
}

function submit_void_goods_receipt_note(){
    if ($("#frm_void_goods_receipt_note").valid()) {

        var form = document.getElementById('frm_void_goods_receipt_note');
        var formData = new FormData(form);

        var context = $('#void_context').val();

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/submit_void_goods_receipt_note',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_void_goods_receipt_note").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');
            },
            success: function(res){
                $("#btn_void_goods_receipt_note").html('<i class="icon-checkmark4 mr-1"></i>Submit');
                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: res.message
                    });
                } else {
                    swal({
                        type: 'success',
                        title: res.message
                    });
                    $('#modal_void_goods_receipt_note').modal('toggle');                    

                    if (context == 'Goods Receipt Notes List'){
                        filter_goods_receipt_notes();
                    } else if (context == 'View Goods Receipt Note') {
                        location.reload();
                    }
                }
            },
            error: function(){
                $("#btn_void_goods_receipt_note").html('<i class="icon-checkmark4 mr-1"></i>Submit');
            }
        });
    }
    return false; 
}


//GOODS RETURN NOTES
function filter_goods_return_notes(){
    $("#goods_return_notes_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $("#btn_goods_return_notes_filter").html('FILTER <i class="icon-spinner2 spinner"></i>');

    var form = document.getElementById('frm_filter_goods_return_notes');
    var formData = new FormData(form);
    $.ajax({
        url: baseDir + 'be/inventory/filter_js_goods_return_notes',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#goods_return_notes_div").html(result);

            setInterval(function() {
                $("#goods_return_notes_div").LoadingOverlay("hide");
            }, 1000);
            $("#btn_goods_return_notes_filter").html('FILTER');
        },
        error: function() {
            setInterval(function() {
                $("#goods_return_notes_div").LoadingOverlay("hide");
            }, 1000);
            $("#btn_goods_return_notes_filter").html('FILTER');
        }
    });

}
function export_goods_return_notes(){

    var dateFrom = $('#date_from').val();
    var dateTo = $('#date_to').val();
    var status = $('#status').val();
    var statusText = $("#status option:selected").text();
    var outletId = $('#outlet_id').val();
    var outletName = $("#outlet_id option:selected").text();
    var supplierId = $('#supplier_id').val();
    var supplierName = $("#supplier_id option:selected").text();
    var systemUserId = $('#system_user_id').val();
    var systemUserName = $("#system_user_id option:selected").text();

    $.redirect(
        baseDir + 'be/inventory/export_goods_return_notes',
        {
            date_from: dateFrom,
            date_to: dateTo, 
            status: status,
            status_text: statusText,
            outlet_id: outletId,
            outlet_name: outletName,
            supplier_id: supplierId,
            supplier_name: supplierName,
            system_user_id: systemUserId,
            system_user_name: systemUserName
        },
        "POST",
        "_blank"
    );
}
function calculate_gren_detail_total(id){
	var order_qty = parseFloat($("#gren_detail_qty_"+id).val());
	var cost = parseFloat($("#gren_detail_cost_"+id).val());
	var total = order_qty * cost;
	document.getElementById("gren_label_detail_total_"+id).innerHTML = total.formatMoney(2,',','.');
	$("#gren_detail_total_"+id).val(parseFloat(total).toFixed(2));
}
function calculate_gren_totals(){
	//TOTAL QUANTITY
	var total_detail_qty = 0;
	$(".gren_detail_qty").each(function() {
		var detail_qty = parseFloat($(this).val());
		total_detail_qty = total_detail_qty + detail_qty;
   	});
	document.getElementById("gren_label_total_detail_qty").innerHTML = total_detail_qty.formatMoney(2,',','.');
	$("#gren_total_detail_qty").val(parseFloat(total_detail_qty));


	//TOTAL QUANTITY
	var total_detail_qty = 0;
	$(".gren_detail_qty").each(function() {
		var detail_qty = parseFloat($(this).val());
		total_detail_qty = total_detail_qty + detail_qty;
   	});
	document.getElementById("gren_label_total_detail_qty").innerHTML = total_detail_qty.formatMoney(2,',','.');
	$("#gren_total_detail_qty").val(parseFloat(total_detail_qty));

	//TOTAL TOTAL
	var total_detail_total = 0;
	var freight_cost = parseFloat($("#gren_freight_cost").val());
	$(".gren_detail_total").each(function() {
		var detail_total = parseFloat($(this).val());
		total_detail_total = total_detail_total + detail_total;
   	});
   	//SUBTOTAL
   	document.getElementById("gren_label_total_detail_subtotal").innerHTML = total_detail_total.formatMoney(2,',','.');
   	$("#gren_total_detail_subtotal").val(parseFloat(total_detail_total).toFixed(2));

   	//TOTAL
   	total_detail_total = total_detail_total + freight_cost;
	document.getElementById("gren_label_total_detail_total").innerHTML = total_detail_total.formatMoney(2,',','.');
	$("#gren_total_detail_total").val(parseFloat(total_detail_total).toFixed(2));
 }

//SAVE GOODS RETURN NOTE
function save_goods_return_note() {
    $("#div_add_goods_return_note_error").fadeOut("fast");
    $("#div_add_goods_return_note_success").fadeOut("fast");

    if ($("#frm_add_goods_return_note").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_goods_return_note").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($(".gren_detail_qty").filter(function() { return $(this).val(); }).length <= 0) {
			$valmsg = "Please add atleast one (1) product to return";
		}

        if ($valmsg != $valmsg2) {
            $("#btn_add_goods_return_note").html('<i class="icon-checkmark4"></i> ACCEPT STOCK');
            $("#div_add_goods_return_note_error").html($valmsg);
            $("#div_add_goods_return_note_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_add_goods_return_note_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_add_goods_return_note_error").fadeOut("fast");

            var form = document.getElementById('frm_add_goods_return_note');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/inventory/save_goods_return_note',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {                    
                    if (res.status == 'ERR') {
                    	$("#btn_add_goods_return_note").html('<i class="icon-checkmark4"></i> ACCEPT STOCK');
                        $("#div_add_goods_return_note_error").html(res.message);
                        $("#div_add_goods_return_note_error").fadeIn("fast");
                         $('html, body').animate({
			                scrollTop: $('#div_add_goods_return_note_error').offset().top - 90
			            }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_goods_return_note_success").html(res.message);
                        $("#div_add_goods_return_note_success").fadeIn("fast");

                        $('#frm_add_goods_return_note').each(function() {
                            this.reset();
                        });
                        $("#supplier_id").val('').change();
                        $("#outlet_id").val('').change();

			            setTimeout(function() {
                            window.location = baseDir+'be/inventory/goods_return_note_detail/'+res.id;
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_add_goods_return_note").html('<i class="icon-checkmark4"></i> ACCEPT STOCK');
                    $("#div_add_goods_return_note_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_goods_return_note_error").fadeIn("fast");
                     $('html, body').animate({
		                scrollTop: $('#div_add_goods_return_note_error').offset().top - 90
		            }, 'slow');
                }
            });

        }
    }
    return false;
}

function update_goods_return_note() {
    $("#div_edit_goods_return_note_error").fadeOut("fast");
    $("#div_edit_goods_return_note_success").fadeOut("fast");

    if ($("#frm_edit_goods_return_note").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_goods_return_note").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($(".gren_detail_qty").filter(function() { return $(this).val(); }).length <= 0) {
			$valmsg = "Please add atleast one (1) product to return";
		}

        if ($valmsg != $valmsg2) {
            $("#btn_edit_goods_return_note").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_goods_return_note_error").html($valmsg);
            $("#div_edit_goods_return_note_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_edit_goods_return_note_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_edit_goods_return_note_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_goods_return_note');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/inventory/update_goods_return_note',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {                    
                    if (res.status == 'ERR') {
                    	$("#btn_edit_goods_return_note").html('<i class="icon-checkmark4"></i> UPDATE');
                        $("#div_edit_goods_return_note_error").html(res.message);
                        $("#div_edit_goods_return_note_error").fadeIn("fast");
                         $('html, body').animate({
			                scrollTop: $('#div_edit_goods_return_note_error').offset().top - 90
			            }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_goods_return_note_success").html(res.message);
                        $("#div_edit_goods_return_note_success").fadeIn("fast");

			            setTimeout(function() {
                            window.location = baseDir+'be/inventory/goods_return_note_detail/'+res.id;
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_edit_goods_return_note").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_goods_return_note_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_goods_return_note_error").fadeIn("fast");
                     $('html, body').animate({
		                scrollTop: $('#div_edit_goods_return_note_error').offset().top - 90
		            }, 'slow');
                }
            });

        }
    }
    return false;
}

function submit_send_goods_return_note_via_email(){
    if ($("#frm_send_goods_return_note_via_email").valid()) {

        var form = document.getElementById('frm_send_goods_return_note_via_email');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/submit_send_goods_return_note_via_email',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_send_goods_return_note_via_email").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');
            },
            success: function(res){
                $("#btn_send_goods_return_note_via_email").html('<em class="icon ni ni-send mr-1"></em>Send Email');
                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: res.message
                    });
                } else {
                    swal({
                        type: 'success',
                        title: res.message
                    });
                }
            },
            error: function(){
                $("#btn_send_goods_return_note_via_email").html('<em class="icon ni ni-send mr-1"></em>Send Email');
            }
        });
    }
    return false;
}

function submit_void_goods_return_note(){
    if ($("#frm_void_goods_return_note").valid()) {

        var form = document.getElementById('frm_void_goods_return_note');
        var formData = new FormData(form);

        var context = $('#void_context').val();

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/submit_void_goods_return_note',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_void_goods_return_note").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');
            },
            success: function(res){
                $("#btn_void_goods_return_note").html('<i class="icon-checkmark4 mr-1"></i>Submit');
                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: res.message
                    });
                } else {
                    swal({
                        type: 'success',
                        title: res.message
                    });
                    $('#modal_void_goods_return_note').modal('toggle');
                    // filter_goods_return_notes();
                    if (context == 'Goods Return Notes List'){
                        filter_goods_return_notes();
                    } else if (context == 'View Goods Return Note') {
                        location.reload();
                    }
                }
            },
            error: function(){
                $("#btn_void_goods_return_note").html('<i class="icon-checkmark4 mr-1"></i>Submit');
            }
        });
    }
    return false; 
}


//STOCK TRANSFERS
function filter_stock_transfers(){
    $("#stock_transfers_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $("#btn_stock_transfers_filter").html('FILTER <i class="icon-spinner2 spinner"></i>');

    var form = document.getElementById('frm_filter_stock_transfers');
    var formData = new FormData(form);
    $.ajax({
        url: baseDir + 'be/inventory/filter_js_stock_transfers',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#stock_transfers_div").html(result);

            setInterval(function() {
                $("#stock_transfers_div").LoadingOverlay("hide");
            }, 1000);
            $("#btn_stock_transfers_filter").html('FILTER');
        },
        error: function() {
            setInterval(function() {
                $("#stock_transfers_div").LoadingOverlay("hide");
            }, 1000);
            $("#btn_stock_transfers_filter").html('FILTER');
        }
    });

}
function export_stock_transfers(){

    var dateFrom = $('#date_from').val();
    var dateTo = $('#date_to').val();
    var status = $('#status').val();
    var statusText = $("#status option:selected").text();
    var sourceOutletId = $('#source_outlet_id').val();
    var sourceOutletName = $("#source_outlet_id option:selected").text();
    var destinationOutletId = $('#destination_outlet_id').val();
    var destinationOutletName = $("#destination_outlet_id option:selected").text();
    var systemUserId = $('#system_user_id').val();
    var systemUserName = $("#system_user_id option:selected").text();

    $.redirect(
        baseDir + 'be/inventory/export_stock_transfers',
        {
            date_from: dateFrom,
            date_to: dateTo, 
            status: status,
            status_text: statusText,
            source_outlet_id: sourceOutletId,
            source_outlet_name: sourceOutletName,
            destination_outlet_id: destinationOutletId,
            destination_outlet_name: destinationOutletName,
            system_user_id: systemUserId,
            system_user_name: systemUserName
        },
        "POST",
        "_blank"
    );
}
function calculate_stck_detail_total(id){
	var order_qty = parseFloat($("#stck_detail_qty_"+id).val());
}
function calculate_stck_totals(){
	//TOTAL QUANTITY
	var total_detail_qty = 0;
	$(".stck_detail_qty").each(function() {
		var detail_qty = parseFloat($(this).val());
		total_detail_qty = total_detail_qty + detail_qty;
   	});
	document.getElementById("stck_label_total_detail_qty").innerHTML = total_detail_qty;
	$("#stck_total_detail_qty").val(parseFloat(total_detail_qty));

 }

//SAVE STOCK TRANSFER
function save_stock_transfer() {
    $("#div_add_stock_transfer_error").fadeOut("fast");
    $("#div_add_stock_transfer_success").fadeOut("fast");

    if ($("#frm_add_stock_transfer").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_stock_transfer").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($(".stck_detail_qty").filter(function() { return $(this).val(); }).length <= 0) {
			$valmsg = "<i class='fa fa-exclamation-circle'></i> Please add atleast one (1) product to transfer<br>";
		}

		if ($("#source_outlet_id").val() == $("#destination_outlet_id").val()){
			$valmsg = $valmsg +  "<i class='fa fa-exclamation-circle'></i> Destination outlet should not be the same as source outlet.<br>";
		}

        if ($valmsg != $valmsg2) {
            $("#btn_add_stock_transfer").html('<i class="icon-checkmark4"></i> TRANSFER STOCK');
            $("#div_add_stock_transfer_error").html($valmsg);
            $("#div_add_stock_transfer_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_add_stock_transfer_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_add_stock_transfer_error").fadeOut("fast");

            var form = document.getElementById('frm_add_stock_transfer');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/inventory/save_stock_transfer',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {                    
                    if (res.status == 'ERR') {
                    	$("#btn_add_stock_transfer").html('<i class="icon-checkmark4"></i> TRANSFER STOCK');
                        $("#div_add_stock_transfer_error").html(res.message);
                        $("#div_add_stock_transfer_error").fadeIn("fast");
                         $('html, body').animate({
			                scrollTop: $('#div_add_stock_transfer_error').offset().top - 90
			            }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_stock_transfer_success").html(res.message);
                        $("#div_add_stock_transfer_success").fadeIn("fast");

                        $('#frm_add_stock_transfer').each(function() {
                            this.reset();
                        });
                        $("#supplier_id").val('').change();
                        $("#outlet_id").val('').change();

			            setTimeout(function() {
                            window.location = baseDir+'be/inventory/stock_transfer_detail/'+res.id;
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_add_stock_transfer").html('<i class="icon-checkmark4"></i> TRANSFER STOCK');
                    $("#div_add_stock_transfer_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_stock_transfer_error").fadeIn("fast");
                     $('html, body').animate({
		                scrollTop: $('#div_add_stock_transfer_error').offset().top - 90
		            }, 'slow');
                }
            });

        }
    }
    return false;
}

function update_stock_transfer() {
    $("#div_edit_stock_transfer_error").fadeOut("fast");
    $("#div_edit_stock_transfer_success").fadeOut("fast");

    if ($("#frm_edit_stock_transfer").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_stock_transfer").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($(".stck_detail_qty").filter(function() { return $(this).val(); }).length <= 0) {
			$valmsg = "Please add atleast one (1) product to transfer<br>";
		}

		if ($("#source_outlet_id").val() == $("#destination_outlet_id").val()){
			$valmsg = $valmsg +  "Destination outlet should not be the same as source outlet.<br>";
		}

        if ($valmsg != $valmsg2) {
            $("#btn_edit_stock_transfer").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_stock_transfer_error").html($valmsg);
            $("#div_edit_stock_transfer_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_edit_stock_transfer_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_edit_stock_transfer_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_stock_transfer');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/inventory/update_stock_transfer',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {                    
                    if (res.status == 'ERR') {
                    	$("#btn_edit_stock_transfer").html('<i class="icon-checkmark4"></i> UPDATE');
                        $("#div_edit_stock_transfer_error").html(res.message);
                        $("#div_edit_stock_transfer_error").fadeIn("fast");
                         $('html, body').animate({
			                scrollTop: $('#div_edit_stock_transfer_error').offset().top - 90
			            }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_stock_transfer_success").html(res.message);
                        $("#div_edit_stock_transfer_success").fadeIn("fast");

			            setTimeout(function() {
                            window.location = baseDir+'be/inventory/stock_transfer_detail/'+res.id;
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_edit_stock_transfer").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_stock_transfer_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_stock_transfer_error").fadeIn("fast");
                     $('html, body').animate({
		                scrollTop: $('#div_edit_stock_transfer_error').offset().top - 90
		            }, 'slow');
                }
            });

        }
    }
    return false;
}
function submit_send_stock_transfer_via_email(){
    if ($("#frm_send_stock_transfer_via_email").valid()) {

        var form = document.getElementById('frm_send_stock_transfer_via_email');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/submit_send_stock_transfer_via_email',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_send_stock_transfer_via_email").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');
            },
            success: function(res){
                $("#btn_send_stock_transfer_via_email").html('<em class="icon ni ni-send mr-1"></em>Send Email');
                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: res.message
                    });
                } else {
                    swal({
                        type: 'success',
                        title: res.message
                    });
                }
            },
            error: function(){
                $("#btn_send_stock_transfer_via_email").html('<em class="icon ni ni-send mr-1"></em>Send Email');
            }
        });
    }
    return false;
}

function submit_void_stock_transfer(){
    if ($("#frm_void_stock_transfer").valid()) {

        var form = document.getElementById('frm_void_stock_transfer');
        var formData = new FormData(form);

        var context = $('#void_context').val();

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/submit_void_stock_transfer',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_void_stock_transfer").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');
            },
            success: function(res){
                $("#btn_void_stock_transfer").html('<i class="icon-checkmark4 mr-1"></i>Submit');
                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: res.message
                    });
                } else {
                    swal({
                        type: 'success',
                        title: res.message
                    });
                    $('#modal_void_stock_transfer').modal('toggle');

                    if (context == 'Stock Transfers List'){
                        filter_stock_transfers();
                    } else if (context == 'View Stock Transfer') {
                        location.reload();
                    }
                }
            },
            error: function(){
                $("#btn_void_stock_transfer").html('<i class="icon-checkmark4 mr-1"></i>Submit');
            }
        });
    }
    return false; 
}


//STOCK ADJUSTMENTS
function filter_stock_adjustments(){
    $("#stock_adjustments_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $("#btn_stock_adjustments_filter").html('FILTER <i class="icon-spinner2 spinner"></i>');

    var form = document.getElementById('frm_filter_stock_adjustments');
    var formData = new FormData(form);
    $.ajax({
        url: baseDir + 'be/inventory/filter_js_stock_adjustments',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#stock_adjustments_div").html(result);

            setInterval(function() {
                $("#stock_adjustments_div").LoadingOverlay("hide");
            }, 1000);
            $("#btn_stock_adjustments_filter").html('FILTER');
        },
        error: function() {
            setInterval(function() {
                $("#stock_adjustments_div").LoadingOverlay("hide");
            }, 1000);
            $("#btn_stock_adjustments_filter").html('FILTER');
        }
    });

}
function export_stock_adjustments(){

    var dateFrom = $('#date_from').val();
    var dateTo = $('#date_to').val();
    var status = $('#status').val();
    var statusText = $("#status option:selected").text();
    var outletId = $('#outlet_id').val();
    var outletName = $("#outlet_id option:selected").text();
    var systemUserId = $('#system_user_id').val();
    var systemUserName = $("#system_user_id option:selected").text();

    $.redirect(
        baseDir + 'be/inventory/export_stock_adjustments',
        {
            date_from: dateFrom,
            date_to: dateTo, 
            status: status,
            status_text: statusText,
            outlet_id: outletId,
            outlet_name: outletName,
            system_user_id: systemUserId,
            system_user_name: systemUserName
        },
        "POST",
        "_blank"
    );
}
function calculate_sadj_detail_total(id){
	var order_qty = parseFloat($("#sadj_detail_qty_"+id).val());
}
function calculate_sadj_totals(){
	//TOTAL QUANTITY
	var total_detail_qty = 0;
	$(".sadj_detail_qty").each(function() {
		var detail_qty = parseFloat($(this).val());
		total_detail_qty = total_detail_qty + detail_qty;
   	});
	document.getElementById("sadj_label_total_detail_qty").innerHTML = total_detail_qty;
	$("#sadj_total_detail_qty").val(parseFloat(total_detail_qty));

 }

//SAVE STOCK ADJUSTMENT
function save_stock_adjustment() {
    $("#div_add_stock_adjustment_error").fadeOut("fast");
    $("#div_add_stock_adjustment_success").fadeOut("fast");

    if ($("#frm_add_stock_adjustment").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_stock_adjustment").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($(".sadj_detail_qty").filter(function() { return $(this).val(); }).length <= 0) {
			$valmsg = "<i class='fa fa-exclamation-circle'></i> Please add atleast one (1) product to adjust";
		}

        if ($valmsg != $valmsg2) {
            $("#btn_add_stock_adjustment").html('<i class="icon-checkmark4"></i> ADJUST STOCK');
            $("#div_add_stock_adjustment_error").html($valmsg);
            $("#div_add_stock_adjustment_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_add_stock_adjustment_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_add_stock_adjustment_error").fadeOut("fast");

            var form = document.getElementById('frm_add_stock_adjustment');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/inventory/save_stock_adjustment',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {                    
                    if (res.status == 'ERR') {
                    	$("#btn_add_stock_adjustment").html('<i class="icon-checkmark4"></i> ADJUST STOCK');
                        $("#div_add_stock_adjustment_error").html(res.message);
                        $("#div_add_stock_adjustment_error").fadeIn("fast");
                         $('html, body').animate({
			                scrollTop: $('#div_add_stock_adjustment_error').offset().top - 90
			            }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_stock_adjustment_success").html(res.message);
                        $("#div_add_stock_adjustment_success").fadeIn("fast");

                        $('#frm_add_stock_adjustment').each(function() {
                            this.reset();
                        });
                        $("#supplier_id").val('').change();
                        $("#outlet_id").val('').change();

			            setTimeout(function() {
                            window.location = baseDir+'be/inventory/stock_adjustment_detail/'+res.id;
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_add_stock_adjustment").html('<i class="icon-checkmark4"></i> ADJUST STOCK');
                    $("#div_add_stock_adjustment_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_stock_adjustment_error").fadeIn("fast");
                     $('html, body').animate({
		                scrollTop: $('#div_add_stock_adjustment_error').offset().top - 90
		            }, 'slow');
                }
            });

        }
    }
    return false;
}

function update_stock_adjustment() {
    $("#div_edit_stock_adjustment_error").fadeOut("fast");
    $("#div_edit_stock_adjustment_success").fadeOut("fast");

    if ($("#frm_edit_stock_adjustment").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_stock_adjustment").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($(".sadj_detail_qty").filter(function() { return $(this).val(); }).length <= 0) {
			$valmsg = "Please add atleast one (1) product to adjustment";
		}

        if ($valmsg != $valmsg2) {
            $("#btn_edit_stock_adjustment").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_stock_adjustment_error").html($valmsg);
            $("#div_edit_stock_adjustment_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_edit_stock_adjustment_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_edit_stock_adjustment_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_stock_adjustment');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/inventory/update_stock_adjustment',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {                    
                    if (res.status == 'ERR') {
                    	$("#btn_edit_stock_adjustment").html('<i class="icon-checkmark4"></i> UPDATE');
                        $("#div_edit_stock_adjustment_error").html(res.message);
                        $("#div_edit_stock_adjustment_error").fadeIn("fast");
                         $('html, body').animate({
			                scrollTop: $('#div_edit_stock_adjustment_error').offset().top - 90
			            }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_stock_adjustment_success").html(res.message);
                        $("#div_edit_stock_adjustment_success").fadeIn("fast");

			            setTimeout(function() {
                            window.location = baseDir+'be/inventory/stock_adjustment_detail/'+res.id;
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_edit_stock_adjustment").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_stock_adjustment_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_stock_adjustment_error").fadeIn("fast");
                     $('html, body').animate({
		                scrollTop: $('#div_edit_stock_adjustment_error').offset().top - 90
		            }, 'slow');
                }
            });

        }
    }
    return false;
}
function submit_send_stock_adjustment_via_email(){
    if ($("#frm_send_stock_adjustment_via_email").valid()) {

        var form = document.getElementById('frm_send_stock_adjustment_via_email');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/submit_send_stock_adjustment_via_email',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_send_stock_adjustment_via_email").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');
            },
            success: function(res){
                $("#btn_send_stock_adjustment_via_email").html('<em class="icon ni ni-send mr-1"></em>Send Email');
                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: res.message
                    });
                } else {
                    swal({
                        type: 'success',
                        title: res.message
                    });
                }
            },
            error: function(){
                $("#btn_send_stock_adjustment_via_email").html('<em class="icon ni ni-send mr-1"></em>Send Email');
            }
        });
    }
    return false;
}

function submit_void_stock_adjustment(){
    if ($("#frm_void_stock_adjustment").valid()) {

        var form = document.getElementById('frm_void_stock_adjustment');
        var formData = new FormData(form);

        var context = $('#void_context').val();

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/submit_void_stock_adjustment',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_void_stock_adjustment").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');
            },
            success: function(res){
                $("#btn_void_stock_adjustment").html('<i class="icon-checkmark4 mr-1"></i>Submit');
                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: res.message
                    });
                } else {
                    swal({
                        type: 'success',
                        title: res.message
                    });
                    $('#modal_void_stock_adjustment').modal('toggle');

                    if (context == 'Stock Adjustments List'){
                        filter_stock_adjustments();
                    } else if (context == 'View Stock Adjustment') {
                        location.reload();
                    }
                }
            },
            error: function(){
                $("#btn_void_stock_adjustment").html('<i class="icon-checkmark4 mr-1"></i>Submit');
            }
        });
    }
    return false; 
}

//STOCK WRITE-OFFS
function filter_stock_writeoffs(){
    $("#stock_writeoffs_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $("#btn_stock_writeoffs_filter").html('FILTER <i class="icon-spinner2 spinner"></i>');

    var form = document.getElementById('frm_filter_stock_writeoffs');
    var formData = new FormData(form);
    $.ajax({
        url: baseDir + 'be/inventory/filter_js_stock_writeoffs',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#stock_writeoffs_div").html(result);

            setInterval(function() {
                $("#stock_writeoffs_div").LoadingOverlay("hide");
            }, 1000);
            $("#btn_stock_writeoffs_filter").html('FILTER');
        },
        error: function() {
            setInterval(function() {
                $("#stock_writeoffs_div").LoadingOverlay("hide");
            }, 1000);
            $("#btn_stock_writeoffs_filter").html('FILTER');
        }
    });

}
function export_stock_writeoffs(){

    var dateFrom = $('#date_from').val();
    var dateTo = $('#date_to').val();
    var status = $('#status').val();
    var statusText = $("#status option:selected").text();
    var outletId = $('#outlet_id').val();
    var outletName = $("#outlet_id option:selected").text();
    var systemUserId = $('#system_user_id').val();
    var systemUserName = $("#system_user_id option:selected").text();

    $.redirect(
        baseDir + 'be/inventory/export_stock_writeoffs',
        {
            date_from: dateFrom,
            date_to: dateTo, 
            status: status,
            status_text: statusText,
            outlet_id: outletId,
            outlet_name: outletName,
            system_user_id: systemUserId,
            system_user_name: systemUserName
        },
        "POST",
        "_blank"
    );
}
function calculate_swri_detail_total(id){
    var order_qty = parseFloat($("#swri_detail_qty_"+id).val());
}
function calculate_swri_totals(){
    //TOTAL QUANTITY
    var total_detail_qty = 0;
    $(".swri_detail_qty").each(function() {
        var detail_qty = parseFloat($(this).val());
        total_detail_qty = total_detail_qty + detail_qty;
    });
    document.getElementById("swri_label_total_detail_qty").innerHTML = total_detail_qty;
    $("#swri_total_detail_qty").val(parseFloat(total_detail_qty));

 }

function save_stock_writeoff() {
    $("#div_add_stock_writeoff_error").fadeOut("fast");
    $("#div_add_stock_writeoff_success").fadeOut("fast");

    if ($("#frm_add_stock_writeoff").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_stock_writeoff").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($(".swri_detail_qty").filter(function() { return $(this).val(); }).length <= 0) {
            $valmsg = "<i class='fa fa-exclamation-circle'></i> Please add atleast one (1) product to write off";
        }

        if ($valmsg != $valmsg2) {
            $("#btn_add_stock_writeoff").html('<i class="icon-checkmark4"></i> WRITE-OFF STOCK');
            $("#div_add_stock_writeoff_error").html($valmsg);
            $("#div_add_stock_writeoff_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_add_stock_writeoff_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_add_stock_writeoff_error").fadeOut("fast");

            var form = document.getElementById('frm_add_stock_writeoff');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/inventory/save_stock_writeoff',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {                    
                    if (res.status == 'ERR') {
                        $("#btn_add_stock_writeoff").html('<i class="icon-checkmark4"></i> WRITE-OFF STOCK');
                        $("#div_add_stock_writeoff_error").html(res.message);
                        $("#div_add_stock_writeoff_error").fadeIn("fast");
                         $('html, body').animate({
                            scrollTop: $('#div_add_stock_writeoff_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_stock_writeoff_success").html(res.message);
                        $("#div_add_stock_writeoff_success").fadeIn("fast");

                        $('#frm_add_stock_writeoff').each(function() {
                            this.reset();
                        });
                        $("#supplier_id").val('').change();
                        $("#outlet_id").val('').change();

                        setTimeout(function() {
                            window.location = baseDir+'be/inventory/stock_writeoff_detail/'+res.id;
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_add_stock_writeoff").html('<i class="icon-checkmark4"></i> WRITE-OFF STOCK');
                    $("#div_add_stock_writeoff_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_stock_writeoff_error").fadeIn("fast");
                     $('html, body').animate({
                        scrollTop: $('#div_add_stock_writeoff_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}

function update_stock_writeoff() {
    $("#div_edit_stock_writeoff_error").fadeOut("fast");
    $("#div_edit_stock_writeoff_success").fadeOut("fast");

    if ($("#frm_edit_stock_writeoff").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_stock_writeoff").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($(".swri_detail_qty").filter(function() { return $(this).val(); }).length <= 0) {
            $valmsg = "Please add atleast one (1) product to write off";
        }

        if ($valmsg != $valmsg2) {
            $("#btn_edit_stock_writeoff").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_stock_writeoff_error").html($valmsg);
            $("#div_edit_stock_writeoff_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_edit_stock_writeoff_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_edit_stock_writeoff_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_stock_writeoff');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/inventory/update_stock_writeoff',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {                    
                    if (res.status == 'ERR') {
                        $("#btn_edit_stock_writeoff").html('<i class="icon-checkmark4"></i> UPDATE');
                        $("#div_edit_stock_writeoff_error").html(res.message);
                        $("#div_edit_stock_writeoff_error").fadeIn("fast");
                         $('html, body').animate({
                            scrollTop: $('#div_edit_stock_writeoff_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_stock_writeoff_success").html(res.message);
                        $("#div_edit_stock_writeoff_success").fadeIn("fast");

                        setTimeout(function() {
                            window.location = baseDir+'be/inventory/stock_writeoff_detail/'+res.id;
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_edit_stock_writeoff").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_stock_writeoff_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_stock_writeoff_error").fadeIn("fast");
                     $('html, body').animate({
                        scrollTop: $('#div_edit_stock_writeoff_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}
function submit_send_stock_writeoff_via_email(){
    if ($("#frm_send_stock_writeoff_via_email").valid()) {

        var form = document.getElementById('frm_send_stock_writeoff_via_email');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/submit_send_stock_writeoff_via_email',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_send_stock_writeoff_via_email").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');
            },
            success: function(res){
                $("#btn_send_stock_writeoff_via_email").html('<em class="icon ni ni-send mr-1"></em>Send Email');
                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: res.message
                    });
                } else {
                    swal({
                        type: 'success',
                        title: res.message
                    });
                }
            },
            error: function(){
                $("#btn_send_stock_writeoff_via_email").html('<em class="icon ni ni-send mr-1"></em>Send Email');
            }
        });
    }
    return false;
}

function submit_void_stock_writeoff(){
    if ($("#frm_void_stock_writeoff").valid()) {

        var form = document.getElementById('frm_void_stock_writeoff');
        var formData = new FormData(form);

        var context = $('#void_context').val();

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/inventory/submit_void_stock_writeoff',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_void_stock_writeoff").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');
            },
            success: function(res){
                $("#btn_void_stock_writeoff").html('<i class="icon-checkmark4 mr-1"></i>Submit');
                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: res.message
                    });
                } else {
                    swal({
                        type: 'success',
                        title: res.message
                    });
                    $('#modal_void_stock_writeoff').modal('toggle');
                    if (context == 'Stock Write-offs List'){
                        filter_stock_writeoffs();
                    } else if (context == 'View Stock Write-off') {
                        location.reload();
                    }
                }
            },
            error: function(){
                $("#btn_void_stock_writeoff").html('<i class="icon-checkmark4 mr-1"></i>Submit');
            }
        });
    }
    return false; 
}

//HOME SLIDERS
function home_slider_add_clear() {
    $("#div_add_home_slider_error").fadeOut("fast");
    $("#div_add_home_slider_success").fadeOut("fast");

    $('#frm_add_home_slider').each(function() {
        this.reset();
    });
}

//SAVE HOME SLIDER
function save_home_slider() {
    $("#div_add_home_slider_error").fadeOut("fast");
    $("#div_add_home_slider_success").fadeOut("fast");

    if ($("#frm_add_home_slider").valid()) {

        $slider_image = $("#add_slider_image").val();

        $valmsg = "";
        $valmsg2 = "";

        if ($slider_image !== ""){
            $allowed_extensions = new Array("png","jpg","jpeg","gif");
            $file_extension = $slider_image.split('.').pop();

            $found = false;
            for(var i = 0; i <= $allowed_extensions.length; i++){
                if($allowed_extensions[i]==$file_extension){
                    $found = true;
                    break;
                }
            }

            if ($found == false){
                $valmsg = $valmsg + "<i class='fa fa-exclamation-circle'></i> The slider image you selected has an incorrect format. Only files with the following extensions are allowed: .png, .jpg, .jpeg, .gif <br/>";
            }
        }

        $("#btn_add_home_slider").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_home_slider").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_home_slider_error").html($valmsg);
            $("#div_add_home_slider_error").fadeIn("fast");
        } else {
            $("#div_add_home_slider_error").fadeOut("fast");

            var form = document.getElementById('frm_add_home_slider');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/home_sliders/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_home_slider").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_home_slider_error").html(res.message);
                        $("#div_add_home_slider_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_home_slider_success").html(res.message);
                        $("#div_add_home_slider_success").fadeIn("fast");

                        $('#frm_add_home_slider').each(function() {
                            this.reset();
                        });

                        load_home_sliders();
                    }
                },
                error: function() {
                    $("#btn_add_home_slider").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_home_slider_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_add_home_slider_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_home_sliders() {
    $("#home_sliders_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/home_sliders/loadjs',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#home_sliders_div").html(result);

            setInterval(function() {
                $("#home_sliders_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#home_sliders_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}
function check_home_slider_exists($picture_path){
    $.ajax({
        url: $picture_path,
        type: 'HEAD',
        error: function(){
            $("#img_home_slider").attr('src', '');
        },
        success: function(){
            $("#img_home_slider").attr('src', $picture_path);
        }
    });
}

function home_slider_edit_load(home_slider_id) {
    $("#div_edit_home_slider_error").fadeOut("fast");
    $("#div_edit_home_slider_success").fadeOut("fast");

    $('#frm_edit_home_slider').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/home_sliders/get_home_slider/' + home_slider_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_home_slider_id").val(obj['home_slider_id']);
                $("#edit_home_slider_title").val(obj['home_slider_title']);
                $("#edit_home_slider_description").val(obj['home_slider_description']); 
                $("#edit_home_slider_link").val(obj['home_slider_link']);     
                $("#edit_sort_key").val(obj['sort_key']);
                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").prop('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").prop('checked', true).change();
                }

                if (obj['home_slider_image'] == ''){
                    $("#img_home_slider").attr('src', '');
                }else{
                    $picture_path = baseDir + 'uploads/home_sliders/' + obj['home_slider_image'];
                    check_home_slider_exists($picture_path);
                }               

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
//UPDATE HOME SLIDER
function update_home_slider() {
    $("#div_edit_home_slider_error").fadeOut("fast");
    $("#div_edit_home_slider_success").fadeOut("fast");

    if ($("#frm_edit_home_slider").valid()) {

        $home_slider_id = $("#edit_home_slider_id").val();
        $slider_image = $("#edit_slider_image").val();

        $valmsg = "";
        $valmsg2 = "";

        if ($slider_image !== ""){
            $allowed_extensions = new Array("png","jpg","jpeg","gif");
            $file_extension = $slider_image.split('.').pop();

            $found = false;
            for(var i = 0; i <= $allowed_extensions.length; i++){
                if($allowed_extensions[i]==$file_extension){
                    $found = true;
                    break;
                }
            }

            if ($found == false){
                $valmsg = $valmsg + "<i class='fa fa-exclamation-circle'></i> The slider image you selected has an incorrect format. Only files with the following extensions are allowed: .png, .jpg, .jpeg, .gif <br/>";
            }
        }

        $("#btn_edit_home_slider").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_home_slider").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_home_slider_error").html($valmsg);
            $("#div_edit_home_slider_error").fadeIn("fast");
        } else {
            $("#div_edit_home_slider_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_home_slider');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/home_sliders/update',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_home_slider").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_home_slider_error").html(res.message);
                        $("#div_edit_home_slider_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_home_slider_success").html(res.message);
                        $("#div_edit_home_slider_success").fadeIn("fast");

                        load_home_sliders();
                        home_slider_edit_load($home_slider_id);
                    }
                },
                error: function() {
                    $("#btn_edit_home_slider").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_home_slider_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_edit_home_slider_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_home_slider(home_slider_id) {
    swal({
        text: 'Do you wish to delete this Home Slider Image?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/home_sliders/delete/' + home_slider_id,
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
                            load_home_sliders();
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
}

//HOME PROMO BANNER
function home_promo_banner_add_clear() {
    $("#div_add_home_promo_banner_error").fadeOut("fast");
    $("#div_add_home_promo_banner_success").fadeOut("fast");

    $('#frm_add_home_promo_banner').each(function() {
        this.reset();
    });
}

//SAVE HOME PROMO BANNER
function save_home_promo_banner() {
    $("#div_add_home_promo_banner_error").fadeOut("fast");
    $("#div_add_home_promo_banner_success").fadeOut("fast");

    if ($("#frm_add_home_promo_banner").valid()) {

        $promo_banner = $("#add_promo_banner").val();

        $valmsg = "";
        $valmsg2 = "";

        if ($promo_banner !== ""){
            $allowed_extensions = new Array("png","jpg","jpeg","gif");
            $file_extension = $promo_banner.split('.').pop();

            $found = false;
            for(var i = 0; i <= $allowed_extensions.length; i++){
                if($allowed_extensions[i]==$file_extension){
                    $found = true;
                    break;
                }
            }

            if ($found == false){
                $valmsg = $valmsg + "<i class='fa fa-exclamation-circle'></i> The banner you selected has an incorrect format. Only files with the following extensions are allowed: .png, .jpg, .jpeg, .gif <br/>";
            }
        }

        $("#btn_add_home_promo_banner").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_home_promo_banner").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_home_promo_banner_error").html($valmsg);
            $("#div_add_home_promo_banner_error").fadeIn("fast");
        } else {
            $("#div_add_home_promo_banner_error").fadeOut("fast");

            var form = document.getElementById('frm_add_home_promo_banner');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/home_page/save_promo_banner',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_home_promo_banner").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_home_promo_banner_error").html(res.message);
                        $("#div_add_home_promo_banner_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_home_promo_banner_success").html(res.message);
                        $("#div_add_home_promo_banner_success").fadeIn("fast");

                        $('#frm_add_home_promo_banner').each(function() {
                            this.reset();
                        });

                        load_home_promo_banners();
                    }
                },
                error: function() {
                    $("#btn_add_home_promo_banner").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_home_promo_banner_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_add_home_promo_banner_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_home_promo_banners() {
    $("#home_promo_banners_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/home_page/loadjs_promo_banners',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#home_promo_banners_div").html(result);

            setInterval(function() {
                $("#home_promo_banners_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#home_promo_banners_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}
function check_home_promo_banner_exists($picture_path){
    $.ajax({
        url: $picture_path,
        type: 'HEAD',
        error: function(){
            $("#img_home_promo_banner").attr('src', '');
        },
        success: function(){
            $("#img_home_promo_banner").attr('src', $picture_path);
        }
    });
}

function home_promo_banner_edit_load(home_promo_banner_id) {
    $("#div_edit_home_promo_banner_error").fadeOut("fast");
    $("#div_edit_home_promo_banner_success").fadeOut("fast");

    $('#frm_edit_home_promo_banner').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/home_page/get_home_promo_banner/' + home_promo_banner_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_home_promo_banner_id").val(obj['home_promo_banner_id']);
                $("#edit_home_promo_banner_link").val(obj['home_promo_banner_link']);     
                $("#edit_sort_key").val(obj['sort_key']);
                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").prop('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").prop('checked', true).change();
                }

                if (obj['home_promo_banner_image'] == ''){
                    $("#img_home_promo_banner").attr('src', '');
                }else{
                    $picture_path = baseDir + 'uploads/home_promo_banners/' + obj['home_promo_banner_image'];
                    check_home_promo_banner_exists($picture_path);
                }               

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
//UPDATE HOME PROMO BANNER
function update_home_promo_banner() {
    $("#div_edit_home_promo_banner_error").fadeOut("fast");
    $("#div_edit_home_promo_banner_success").fadeOut("fast");

    if ($("#frm_edit_home_promo_banner").valid()) {

        $home_promo_banner_id = $("#edit_home_promo_banner_id").val();
        $promo_banner = $("#edit_promo_banner").val();

        $valmsg = "";
        $valmsg2 = "";

        if ($promo_banner !== ""){
            $allowed_extensions = new Array("png","jpg","jpeg","gif");
            $file_extension = $promo_banner.split('.').pop();

            $found = false;
            for(var i = 0; i <= $allowed_extensions.length; i++){
                if($allowed_extensions[i]==$file_extension){
                    $found = true;
                    break;
                }
            }

            if ($found == false){
                $valmsg = $valmsg + "<i class='fa fa-exclamation-circle'></i> The banner image you selected has an incorrect format. Only files with the following extensions are allowed: .png, .jpg, .jpeg, .gif <br/>";
            }
        }

        $("#btn_edit_home_promo_banner").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_home_promo_banner").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_home_promo_banner_error").html($valmsg);
            $("#div_edit_home_promo_banner_error").fadeIn("fast");
        } else {
            $("#div_edit_home_promo_banner_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_home_promo_banner');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/home_page/update_promo_banner',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_home_promo_banner").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_home_promo_banner_error").html(res.message);
                        $("#div_edit_home_promo_banner_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_home_promo_banner_success").html(res.message);
                        $("#div_edit_home_promo_banner_success").fadeIn("fast");

                        load_home_promo_banners();
                        home_promo_banner_edit_load($home_promo_banner_id);
                    }
                },
                error: function() {
                    $("#btn_edit_home_promo_banner").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_home_promo_banner_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_edit_home_promo_banner_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_home_promo_banner(home_promo_banner_id) {
    swal({
        text: 'Do you wish to delete this Promo Banner?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/home_page/delete_promo_banner/' + home_promo_banner_id,
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
                            load_home_promo_banners();
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
}

//HOME TOP CATEGORIES
function save_home_top_product_category() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    if ($("#frm_add_home_top_product_category").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_home_top_product_category").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_home_top_product_category").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_home_top_product_category');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/home_page/save_home_top_product_category',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_home_top_product_category").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_home_top_product_category').each(function() {
                            this.reset();
                        });
                        $("#home_top_product_category_id").val('').change();
                        $("#home_top_product_subcategory_id").val('').change();

                        load_home_top_product_categories();
                    }
                },
                error: function() {
                    $("#btn_add_home_top_product_category").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_home_top_product_categories() {
    $("#home_top_product_categories_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/home_page/loadjs_top_categories',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#home_top_product_categories_div").html(result);

            setInterval(function() {
                $("#home_top_product_categories_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#home_top_product_categories_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function home_top_product_category_edit_load(home_top_product_category_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $('#frm_edit_home_top_product_category').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/home_page/get_home_top_product_category2/' + home_top_product_category_id,
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
				obj1 = obj1.replace(/\\n/g, "\\n")  
				        .replace(/\\'/g, "\\'")
				        .replace(/\\"/g, '\\"')
				        .replace(/\\&/g, "\\&")
				        .replace(/\\r/g, "\\r")
				        .replace(/\\t/g, "\\t")
				        .replace(/\\b/g, "\\b")
				        .replace(/\\f/g, "\\f");
				obj1 = obj1.replace(/[\u0000-\u0019]+/g,""); 
	     		var obj = JSON.parse(obj1);
	     		for (i=0; i< obj.length; i++){ 

                	var subcat = obj[i]['sub'];
                	var selectedValues = new Array();
                	for (x=0; x< subcat.length; x++) {
                		selectedValues[x] = subcat[x]['product_category_id'];
                	}
                	selValues = [];
                	selValues = selectedValues;

 					$("#edit_home_top_product_category_id").val(obj[i]['home_top_product_category_id']);
                	$("#edit_ht_product_category_id").val(obj[i]['product_category_id']).change();
                	$("#edit_position").val(obj[i]['position']);
				}	

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
function update_home_top_product_category() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    if ($("#frm_edit_home_top_product_category").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_home_top_product_category").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_home_top_product_category").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_home_top_product_category');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/home_page/update_home_top_product_category',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_home_top_product_category").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        load_home_top_product_categories();
                    }
                },
                error: function() {
                    $("#btn_edit_home_top_product_category").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_home_top_product_category(home_top_product_category_id) {
    swal({
        text: 'Do you wish to remove this Top Category?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/home_page/delete_home_top_product_category/' + home_top_product_category_id,
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
                            load_home_top_product_categories();
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
}

function save_home_featured_product_categories(){
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    if ($("#frm_home_featured_product_categories").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_home_featured_product_categories").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_home_featured_product_categories").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_home_featured_product_categories');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/home_page/save_home_featured_product_categories',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_home_featured_product_categories").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");
                    }
                },
                error: function() {
                    $("#btn_home_featured_product_categories").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                }
            });

        }
    }
    return false;

}

//TESTIMONIALS
function testimonial_add_clear() {
    $("#div_add_testimonial_error").fadeOut("fast");
    $("#div_add_testimonial_success").fadeOut("fast");

    $('#frm_add_testimonial').each(function() {
        this.reset();
    });
}

//SAVE TESTIMONIAL
function save_testimonial() {
    $("#div_add_testimonial_error").fadeOut("fast");
    $("#div_add_testimonial_success").fadeOut("fast");

    if ($("#frm_add_testimonial").valid()) {

        $member_image = $("#add_testimonial_image").val();

        $valmsg = "";
        $valmsg2 = "";

        if ($member_image !== ""){
            $allowed_extensions = new Array("png","jpg","jpeg","gif");
            $file_extension = $member_image.split('.').pop();

            $found = false;
            for(var i = 0; i <= $allowed_extensions.length; i++){
                if($allowed_extensions[i]==$file_extension){
                    $found = true;
                    break;
                }
            }

            if ($found == false){
                $valmsg = $valmsg + "<i class='fa fa-exclamation-circle'></i> The photo you selected has an incorrect format. Only files with the following extensions are allowed: .png, .jpg, .jpeg, .gif <br/>";
            }
        }

        $("#btn_add_testimonial").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_testimonial").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_testimonial_error").html($valmsg);
            $("#div_add_testimonial_error").fadeIn("fast");
        } else {
            $("#div_add_testimonial_error").fadeOut("fast");

            var form = document.getElementById('frm_add_testimonial');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/testimonials/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_testimonial").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_testimonial_error").html(res.message);
                        $("#div_add_testimonial_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_testimonial_success").html(res.message);
                        $("#div_add_testimonial_success").fadeIn("fast");

                        $('#frm_add_testimonial').each(function() {
                            this.reset();
                        });

                        load_testimonials();
                    }
                },
                error: function() {
                    $("#btn_add_testimonial").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_testimonial_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_add_testimonial_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_testimonials() {
    $("#testimonials_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/testimonials/loadjs',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#testimonials_div").html(result);

            setInterval(function() {
                $("#testimonials_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#testimonials_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}
function check_testimonial_exists($picture_path){
    $.ajax({
        url: $picture_path,
        type: 'HEAD',
        error: function(){
            $("#img_testimonial").attr('src', '');
        },
        success: function(){
            $("#img_testimonial").attr('src', $picture_path);
        }
    });
}

function testimonial_edit_load(testimonial_id) {
    $("#div_edit_testimonial_error").fadeOut("fast");
    $("#div_edit_testimonial_success").fadeOut("fast");

    $('#frm_edit_testimonial').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/testimonials/get_testimonial/' + testimonial_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_testimonial_id").val(obj['testimonial_id']);
                $("#edit_testimonial_name").val(obj['testimonial_name']);
                $("#edit_testimonial_title").val(obj['testimonial_title']);
                $("#edit_testimonial_description").val(obj['testimonial_description']);
                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").prop('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").prop('checked', true).change();
                }

                if (obj['testimonial_image'] == ''){
                    $("#img_testimonial").attr('src', '');
                }else{
                    $picture_path = baseDir + 'uploads/testimonial_images/' + obj['testimonial_image'];
                    check_testimonial_exists($picture_path);
                }               

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
//UPDATE TESTIMONIAL
function update_testimonial() {
    $("#div_edit_testimonial_error").fadeOut("fast");
    $("#div_edit_testimonial_success").fadeOut("fast");

    $testimonial_id = $("#edit_testimonial_id").val();

    if ($("#frm_edit_testimonial").valid()) {

        $member_image = $("#edit_testimonial_image").val();

        $valmsg = "";
        $valmsg2 = "";

        if ($member_image !== ""){
            $allowed_extensions = new Array("png","jpg","jpeg","gif");
            $file_extension = $member_image.split('.').pop();

            $found = false;
            for(var i = 0; i <= $allowed_extensions.length; i++){
                if($allowed_extensions[i]==$file_extension){
                    $found = true;
                    break;
                }
            }

            if ($found == false){
                $valmsg = $valmsg + "<i class='fa fa-exclamation-circle'></i> The photo you selected has an incorrect format. Only files with the following extensions are allowed: .png, .jpg, .jpeg, .gif <br/>";
            }
        }

        $("#btn_edit_testimonial").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_testimonial").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_testimonial_error").html($valmsg);
            $("#div_edit_testimonial_error").fadeIn("fast");
        } else {
            $("#div_edit_testimonial_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_testimonial');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/testimonials/update',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_testimonial").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_testimonial_error").html(res.message);
                        $("#div_edit_testimonial_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_testimonial_success").html(res.message);
                        $("#div_edit_testimonial_success").fadeIn("fast");

                        load_testimonials();
                        testimonial_edit_load($testimonial_id);
                    }
                },
                error: function() {
                    $("#btn_edit_testimonial").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_testimonial_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_edit_testimonial_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_testimonial(testimonial_id) {
    swal({
        text: 'Do you wish to delete this Testimonial?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/testimonials/delete/' + testimonial_id,
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
                            load_testimonials();
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
}

//BLOG CATEGORIES
function blog_category_add_clear() {
    $("#div_add_blog_category_error").fadeOut("fast");
    $("#div_add_blog_category_success").fadeOut("fast");

    $('#frm_add_blog_category').each(function() {
        this.reset();
    });
    CKEDITOR.instances['add_description'].setData('');
}

//SAVE BLOG CATEGORY
function save_blog_category() {
    $("#div_add_blog_category_error").fadeOut("fast");
    $("#div_add_blog_category_success").fadeOut("fast");

    CKEDITOR.instances['add_description'].updateElement();

    if ($("#frm_add_blog_category").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_blog_category").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_blog_category").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_blog_category_error").html($valmsg);
            $("#div_add_blog_category_error").fadeIn("fast");
        } else {
            $("#div_add_blog_category_error").fadeOut("fast");

            var form = document.getElementById('frm_add_blog_category');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/blog_categories/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_blog_category").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_blog_category_error").html(res.message);
                        $("#div_add_blog_category_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_blog_category_success").html(res.message);
                        $("#div_add_blog_category_success").fadeIn("fast");

                        $('#frm_add_blog_category').each(function() {
                            this.reset();
                        });
                        CKEDITOR.instances['add_description'].setData('');

                        load_blog_categories();
                    }
                },
                error: function() {
                    $("#btn_add_blog_category").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_blog_category_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_add_blog_category_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_blog_categories() {
    $("#blog_categories_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/blog_categories/loadjs',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#blog_categories_div").html(result);

            setInterval(function() {
                $("#blog_categories_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#blog_categories_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function blog_category_edit_load(blog_category_id) {
    $("#div_edit_blog_category_error").fadeOut("fast");
    $("#div_edit_blog_category_success").fadeOut("fast");

    $('#frm_edit_blog_category').each(function() {
        this.reset();
    });
    CKEDITOR.instances['edit_description'].setData('');

    $.ajax({
        url: baseDir + 'be/blog_categories/get_blog_category/' + blog_category_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_blog_category_id").val(obj['blog_category_id']);
                $("#edit_blog_category_name").val(obj['blog_category_name']);
                //$("#edit_description").val(obj['blog_category_description']);
                CKEDITOR.instances['edit_description'].setData(obj['description']);
                $("#edit_sort_key").val(obj['sort_key']);

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
//UPDATE BLOG CATEGORY
function update_blog_category() {
    $("#div_edit_blog_category_error").fadeOut("fast");
    $("#div_edit_blog_category_success").fadeOut("fast");

    CKEDITOR.instances['edit_description'].updateElement();

    if ($("#frm_edit_blog_category").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_blog_category").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_blog_category").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_blog_category_error").html($valmsg);
            $("#div_edit_blog_category_error").fadeIn("fast");
        } else {
            $("#div_edit_blog_category_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_blog_category');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/blog_categories/update',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_blog_category").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_blog_category_error").html(res.message);
                        $("#div_edit_blog_category_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_blog_category_success").html(res.message);
                        $("#div_edit_blog_category_success").fadeIn("fast");

                        load_blog_categories();
                    }
                },
                error: function() {
                    $("#btn_edit_blog_category").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_blog_category_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_edit_blog_category_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_blog_category(blog_category_id) {
    swal({
        text: 'Do you wish to delete this Category?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/blog_categories/delete/' + blog_category_id,
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
                            load_blog_categories();
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
}

//BLOG ARTICLE
//SAVE BLOG ARTICLE
function save_blog_article() {
    $("#div_error").fadeOut("fast");
    $("#div_success").fadeOut("fast");

    CKEDITOR.instances['blog_article_content'].updateElement();

    if ($("#frm_add_blog_article").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_blog_article").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_blog_article").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_error").html($valmsg);
            $("#div_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_error").fadeOut("fast");

            var form = document.getElementById('frm_add_blog_article');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/blog/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_blog_article").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_error").html(res.message);
                        $("#div_error").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_success").html(res.message);
                        $("#div_success").fadeIn("fast");

                        $('html, body').animate({
                            scrollTop: $('#div_success').offset().top - 90
                        }, 'slow');

                        $('#frm_add_blog_article').each(function() {
                            this.reset();
                        });
                        $("#blog_category_id").val('').change();

                        CKEDITOR.instances['blog_article_content'].setData('');

                        //load_blog();
                    }
                },
                error: function() {
                    $("#btn_add_blog_article").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_error").fadeIn("fast");
                    $('html, body').animate({
                        scrollTop: $('#div_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}

function load_blog() {
    $("#blog_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/blog/loadjs',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#blog_div").html(result);

            setInterval(function() {
                $("#blog_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#blog_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

//UPDATE BLOG ARTICLE
function update_blog_article() {
    $("#div_error").fadeOut("fast");
    $("#div_success").fadeOut("fast");

    CKEDITOR.instances['blog_article_content'].updateElement();

    if ($("#frm_edit_blog_article").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_blog_article").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_blog_article").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_error").html($valmsg);
            $("#div_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_blog_article');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/blog/update',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_blog_article").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_error").html(res.message);
                        $("#div_error").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_success").html(res.message);
                        $("#div_success").fadeIn("fast");

                        $('html, body').animate({
                            scrollTop: $('#div_success').offset().top - 90
                        }, 'slow');

                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_edit_blog_article").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_error").fadeIn("fast");
                    $('html, body').animate({
                        scrollTop: $('#div_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}

function delete_blog_article(blog_article_id) {
    swal({
        text: 'Do you wish to delete this Article?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/blog/delete/' + blog_article_id,
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
                            load_blog();
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
}

function delete_blog_article_cover_image(blog_article_id) {
    swal({
        text: 'Do you wish to delete this Cover Image? Please note that this action is irreversible.',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/blog/delete_cover_image/'+blog_article_id,
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
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
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
}

//ABOUT US
function save_about_us() {
    $("#div_error").fadeOut("fast");
    $("#div_success").fadeOut("fast");

    CKEDITOR.instances['about_us'].updateElement();
    CKEDITOR.instances['mission'].updateElement();
    CKEDITOR.instances['vision'].updateElement();
    CKEDITOR.instances['core_values'].updateElement();

    if ($("#frm_about_us").valid()) {

        $cover_image = $("#cover_image").val();

        $valmsg = "";
        $valmsg2 = "";

        if ($cover_image !== ""){
            $allowed_extensions = new Array("png","jpg","jpeg","gif");
            $file_extension = $cover_image.split('.').pop();

            $found = false;
            for(var i = 0; i <= $allowed_extensions.length; i++){
                if($allowed_extensions[i]==$file_extension){
                    $found = true;
                    break;
                }
            }

            if ($found == false){
                $valmsg = $valmsg + "<i class='fa fa-exclamation-circle'></i> The cover image you selected has an incorrect format. Only files with the following extensions are allowed: .png, .jpg, .jpeg, .gif <br/>";
            }
        }

        $("#btn_about_us").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_about_us").html('<i class="fa fa-save"></i> Save About Us');
            $("#div_error").html($valmsg);
            $("#div_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_error").fadeOut("fast");

            var form = document.getElementById('frm_about_us');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/about_us/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_about_us").html('<i class="fa fa-save"></i> Save About Us');
                    if (res.status == 'ERR') {
                        $("#div_error").html(res.message);
                        $("#div_error").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_success").html(res.message);
                        $("#div_success").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_success').offset().top - 90
                        }, 'slow');

                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_about_us").html('<i class="fa fa-save"></i> Save About Us');
                    $("#div_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_error").fadeIn("fast");
                    $('html, body').animate({
                        scrollTop: $('#div_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}

function delete_about_us_image() {
    swal({
        text: 'Do you wish to delete this Image? Please note that this action is irreversible.',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/about_us/delete_cover_image',
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
                            setTimeout(function() {
                                location.reload();
                            }, 2000);
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

}

//SEO
function save_seo() {
    $("#div_error").fadeOut("fast");
    $("#div_success").fadeOut("fast");

    if ($("#frm_seo").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_seo").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_seo").html('<i class="fa fa-save"></i> Save SEO Settings');
            $("#div_error").html($valmsg);
            $("#div_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_error").fadeOut("fast");

            var form = document.getElementById('frm_seo');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/seo/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_seo").html('<i class="fa fa-save"></i> Save SEO Settings');
                    if (res.status == 'ERR') {
                        $("#div_error").html(res.message);
                        $("#div_error").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_success").html(res.message);
                        $("#div_success").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_success').offset().top - 90
                        }, 'slow');

                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_seo").html('<i class="fa fa-save"></i> Save SEO Settings');
                    $("#div_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_error").fadeIn("fast");
                    $('html, body').animate({
                        scrollTop: $('#div_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}

//TERMS AND CONDITIONS
function save_terms_and_conditions() {
    $("#div_error").fadeOut("fast");
    $("#div_success").fadeOut("fast");

    CKEDITOR.instances['terms_and_conditions'].updateElement();

    if ($("#frm_terms_and_conditions").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_terms_and_conditions").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_terms_and_conditions").html('<i class="fa fa-save"></i> Save Terms and Conditions');
            $("#div_error").html($valmsg);
            $("#div_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_error").fadeOut("fast");

            var form = document.getElementById('frm_terms_and_conditions');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/terms_and_conditions/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_terms_and_conditions").html('<i class="fa fa-save"></i> Save Terms and Conditions');
                    if (res.status == 'ERR') {
                        $("#div_error").html(res.message);
                        $("#div_error").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_success").html(res.message);
                        $("#div_success").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_success').offset().top - 90
                        }, 'slow');

                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_terms_and_conditions").html('<i class="fa fa-save"></i> Save Terms and Conditions');
                    $("#div_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_error").fadeIn("fast");
                    $('html, body').animate({
                        scrollTop: $('#div_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}

//PRIVACY POLICY
function save_privacy_policy() {
    $("#div_error").fadeOut("fast");
    $("#div_success").fadeOut("fast");

    CKEDITOR.instances['privacy_policy'].updateElement();

    if ($("#frm_privacy_policy").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_privacy_policy").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_privacy_policy").html('<i class="fa fa-save"></i> Save Privacy Policy');
            $("#div_error").html($valmsg);
            $("#div_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_error").fadeOut("fast");

            var form = document.getElementById('frm_privacy_policy');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/privacy_policy/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_privacy_policy").html('<i class="fa fa-save"></i> Save Privacy Policy');
                    if (res.status == 'ERR') {
                        $("#div_error").html(res.message);
                        $("#div_error").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_success").html(res.message);
                        $("#div_success").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_success').offset().top - 90
                        }, 'slow');

                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_privacy_policy").html('<i class="fa fa-save"></i> Save Privacy Policy');
                    $("#div_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_error").fadeIn("fast");
                    $('html, body').animate({
                        scrollTop: $('#div_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}

//RETURN POLICY
function save_return_policy() {
    $("#div_error").fadeOut("fast");
    $("#div_success").fadeOut("fast");

    CKEDITOR.instances['return_policy'].updateElement();

    if ($("#frm_return_policy").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_return_policy").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_return_policy").html('<i class="fa fa-save"></i> Save Return Policy');
            $("#div_error").html($valmsg);
            $("#div_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_error").fadeOut("fast");

            var form = document.getElementById('frm_return_policy');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/return_policy/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_return_policy").html('<i class="fa fa-save"></i> Save Return Policy');
                    if (res.status == 'ERR') {
                        $("#div_error").html(res.message);
                        $("#div_error").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_success").html(res.message);
                        $("#div_success").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_success').offset().top - 90
                        }, 'slow');

                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_return_policy").html('<i class="fa fa-save"></i> Save Return Policy');
                    $("#div_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_error").fadeIn("fast");
                    $('html, body').animate({
                        scrollTop: $('#div_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}


//HOW TO SHOP
function save_how_to_shop() {
    $("#div_error").fadeOut("fast");
    $("#div_success").fadeOut("fast");

    CKEDITOR.instances['how_to_shop'].updateElement();

    if ($("#frm_how_to_shop").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_how_to_shop").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_how_to_shop").html('<i class="fa fa-save"></i> Save How To Shop');
            $("#div_error").html($valmsg);
            $("#div_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_error").fadeOut("fast");

            var form = document.getElementById('frm_how_to_shop');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/how_to_shop/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_how_to_shop").html('<i class="fa fa-save"></i> Save How To Shop');
                    if (res.status == 'ERR') {
                        $("#div_error").html(res.message);
                        $("#div_error").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_success").html(res.message);
                        $("#div_success").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_success').offset().top - 90
                        }, 'slow');

                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_how_to_shop").html('<i class="fa fa-save"></i> Save How To Shop');
                    $("#div_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_error").fadeIn("fast");
                    $('html, body').animate({
                        scrollTop: $('#div_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}

//FAQS
function faq_add_clear() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    $('#frm_add_faq').each(function() {
        this.reset();
    });
    CKEDITOR.instances['add_description'].setData('');
}

//SAVE FAQ
function save_faq() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    CKEDITOR.instances['add_faq_description'].updateElement();

    if ($("#frm_add_faq").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_faq").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_faq").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_faq');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/faqs/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_faq").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_faq').each(function() {
                            this.reset();
                        });
                        CKEDITOR.instances['add_faq_description'].setData('');

                        load_faqs();
                    }
                },
                error: function() {
                    $("#btn_add_faq").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function load_faqs() {
    $("#faqs_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/faqs/loadjs',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#faqs_div").html(result);

            setInterval(function() {
                $("#faqs_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#faqs_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function faq_edit_load(faq_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $('#frm_edit_faq').each(function() {
        this.reset();
    });
    CKEDITOR.instances['edit_faq_description'].setData('');

    $.ajax({
        url: baseDir + 'be/faqs/get_faq2/' + faq_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_faq_id").val(obj['faq_id']);
                $("#edit_faq_heading").val(obj['faq_heading']);
                //$("#edit_description").val(obj['faq_description']);
                CKEDITOR.instances['edit_faq_description'].setData(obj['faq_description']);
                $("#edit_sort_key").val(obj['sort_key']);

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
//UPDATE FAQ
function update_faq() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    CKEDITOR.instances['edit_faq_description'].updateElement();

    if ($("#frm_edit_faq").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_faq").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_faq").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_faq');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/faqs/update',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_faq").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        load_faqs();
                    }
                },
                error: function() {
                    $("#btn_edit_faq").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                }
            });

        }
    }
    return false;
}

function delete_faq(faq_id) {
    swal({
        text: 'Do you wish to delete this FAQ?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/faqs/delete/' + faq_id,
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
                            load_faqs();
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
}

//AFFILIATE TERMS AND CONDITIONS
function save_affiliate_terms() {
    $("#div_error").fadeOut("fast");
    $("#div_success").fadeOut("fast");

    CKEDITOR.instances['affiliate_terms'].updateElement();

    if ($("#frm_affiliate_terms").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_affiliate_terms").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_affiliate_terms").html('<i class="fa fa-save"></i> Save Affiliate Terms and Conditions');
            $("#div_error").html($valmsg);
            $("#div_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_error").fadeOut("fast");

            var form = document.getElementById('frm_affiliate_terms');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/affiliates/save_terms',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_affiliate_terms").html('<i class="fa fa-save"></i> Save Affiliate Terms and Conditions');
                    if (res.status == 'ERR') {
                        $("#div_error").html(res.message);
                        $("#div_error").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_success").html(res.message);
                        $("#div_success").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_success').offset().top - 90
                        }, 'slow');

                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_affiliate_terms").html('<i class="fa fa-save"></i> Save Affiliate Terms and Conditions');
                    $("#div_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_error").fadeIn("fast");
                    $('html, body').animate({
                        scrollTop: $('#div_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}

//AFFILIATES
function save_affiliate() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    if ($("#frm_add_affiliate").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_affiliate").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_affiliate").html('<i class="icon-checkmark4"></i> SAVE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_add_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_affiliate');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/affiliates/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_affiliate").html('<i class="icon-checkmark4"></i> SAVE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                        $('html, body').animate({
			                scrollTop: $('#div_add_error').offset().top - 90
			            }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_affiliate').each(function() {
                            this.reset();
                        });
                        //$("#gender").val('').change();
					    $("#affiliate_group_id").val('').change();
					    //$("#shipping_country_id").val('').change();
					    //$("#shipping_region_id").val('').change();
					    //$("#billing_country_id").val('').change();
					    //$("#billing_region_id").val('').change();
					    $("#country_id").val('').change();

                        $('html, body').animate({
			                scrollTop: $('#div_add_success').offset().top - 90
			            }, 'slow');
                    }
                },
                error: function() {
                    $("#btn_add_affiliate").html('<i class="icon-checkmark4"></i> SAVE');
                    $("#div_add_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                    $('html, body').animate({
		                scrollTop: $('#div_add_error').offset().top - 90
		            }, 'slow');
                }
            });

        }
    }
    return false;
}

function load_affiliates() {
    $("#affiliates_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/affiliates/load_js',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#affiliates_div").html(result);

            setInterval(function() {
                $("#affiliates_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#affiliates_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function affiliate_edit_load(affiliate_id) {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    $('#frm_edit_affiliate').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/affiliates/get_affiliate/' + affiliate_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_affiliate_id").val(obj['affiliate_id']);
                $("#edit_first_name").val(obj['first_name']);
                $("#edit_last_name").val(obj['last_name']);
                $("#edit_gender").val(obj['gender']).change();
                $("#edit_date_of_birth").val(obj['date_of_birth']);
                $("#edit_company_name").val(obj['company_name']);
                $("#edit_affiliate_group_id").val(obj['affiliate_group_id']).change();
                $("#edit_email_address").val(obj['email_address']);
                $("#edit_phone_number").val(obj['phone_number']);
                $("#edit_postal_address").val(obj['postal_address']);
                $("#edit_postal_code").val(obj['postal_code']);
                $("#edit_affiliate_country_id").val(obj['country_id']).change();
                cur_city_id = obj['city_id'];
                $("#edit_affiliate_code").val(obj['affiliate_code']);
                $("#edit_opening_balance").val(obj['opening_balance']);
                $("#edit_credit_limit").val(obj['credit_limit']);
                if (obj['loyalty_enrolled'] == 0) {
                    //$("#edit_loyalty_enrolled").removeAttr("checked").change();
                    $('.form-check-input-switchery').click();
                } else if (obj['loyalty_enrolled'] == 1) {
                    //$("#edit_loyalty_enrolled").attr('checked', true).change();
                }
                $('.form-check-input-switchery').click();
                $("#edit_loyalty_enrollment_date").val(obj['loyalty_enrollment_date']);
                $("#edit_reference_affiliate_id").val(obj['reference_affiliate_id']).change();
                $("#edit_sort_key").val(obj['sort_key']);
                if (obj['is_active'] == 0) {
                    $("#edit_is_active_1").removeAttr("checked").change();
                    $("#edit_is_active_0").prop('checked', true).change();
                } else if (obj['is_active'] == 1) {
                    $("#edit_is_active_0").removeAttr("checked").change();
                    $("#edit_is_active_1").prop('checked', true).change();
                }

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}
//UPDATE affiliate
function update_affiliate() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    if ($("#frm_edit_affiliate").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_affiliate").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_affiliate").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_edit_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_affiliate');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/affiliates/update',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_affiliate").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                        $('html, body').animate({
			                scrollTop: $('#div_edit_error').offset().top - 90
			            }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");

                        $('html, body').animate({
			                scrollTop: $('#div_edit_success').offset().top - 90
			            }, 'slow');

			            setTimeout(function() {
                            location.reload();
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_edit_affiliate").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                    $('html, body').animate({
		                scrollTop: $('#div_edit_error').offset().top - 90
		            }, 'slow');
                }
            });

        }
    }
    return false;
}

function delete_affiliate(affiliate_id) {
    swal({
        text: 'Do you wish to delete this Affiliate?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/affiliates/delete/' + affiliate_id,
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
                            load_affiliates();
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
}

//AFFILIATE PACKAGES
function save_affiliate_package() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    CKEDITOR.instances['add_affiliate_package_features'].updateElement();

    if ($("#frm_add_affiliate_package").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_affiliate_package").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_affiliate_package").html('<i class="icon-checkmark4"></i> SAVE NEW PACKAGE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_affiliate_package');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/affiliate_packages/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_affiliate_package").html('<i class="icon-checkmark4"></i> SAVE NEW PACKAGE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_affiliate_package').each(function() {
                            this.reset();
                        });
                        CKEDITOR.instances['add_affiliate_package_features'].setData('');

                        load_affiliate_packages();

                        setTimeout(function() {
                            $("#div_add_success").fadeOut("fast");
                        }, 3000);

                        // $('html, body').animate({
                        //     scrollTop: $('#div_add_success').offset().top - 90
                        // }, 'slow');
                    }
                },
                error: function() {
                    $("#btn_add_affiliate_package").html('<i class="icon-checkmark4"></i> SAVE NEW PACKAGE');
                    $("#div_add_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                    $('html, body').animate({
                        scrollTop: $('#div_add_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}

function load_affiliate_packages() {
    $("#affiliate_packages_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/affiliate_packages/loadjs',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#affiliate_packages_div").html(result);

            setInterval(function() {
                $("#affiliate_packages_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#affiliate_packages_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function affiliate_package_edit_load(affiliate_package_id) {
    $("#div_edit_affiliate_package_error").fadeOut("fast");
    $("#div_edit_affiliate_package_success").fadeOut("fast");

    $('#frm_edit_affiliate_package').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/affiliate_packages/get_affiliate_package2/' + affiliate_package_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_affiliate_package_id").val(obj['affiliate_package_id']);
                $("#edit_affiliate_package_name").val(obj['affiliate_package_name']);
                $("#edit_affiliate_package_colour_code").spectrum("set", obj['affiliate_package_colour_code']);
                //$("#edit_affiliate_package_colour_code").val(obj['affiliate_package_colour_code']);
                $("#edit_commission").val(obj['commission']);
                $("#edit_minimum_pay_out").val(obj['minimum_pay_out']);
                CKEDITOR.instances['edit_affiliate_package_features'].setData(obj['affiliate_package_features']);
                if (obj['is_active'] == 0){
                    $("#edit_is_active_1").removeAttr( "checked").change();
                    $("#edit_is_active_2").attr('checked', true).change();
                    
                }else if (obj['is_active'] == 1){
                    $("#edit_is_active_2").removeAttr( "checked").change();
                    $("#edit_is_active_1").attr('checked', true).change(); 
                }

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}

function update_affiliate_package() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    CKEDITOR.instances['edit_affiliate_package_features'].updateElement();

    if ($("#frm_edit_affiliate_package").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_affiliate_package").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_affiliate_package").html('<i class="icon-checkmark4"></i> UPDATE affiliate_package');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_edit_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_affiliate_package');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/affiliate_packages/update',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_affiliate_package").html('<i class="icon-checkmark4"></i> UPDATE affiliate_package');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_edit_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");
                        load_affiliate_packages();
                        // $('html, body').animate({
                        //     scrollTop: $('#div_edit_success').offset().top - 90
                        // }, 'slow');
                        setTimeout(function() {
                            $("#div_edit_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_edit_affiliate_package").html('<i class="icon-checkmark4"></i> UPDATE affiliate_package');
                    $("#div_edit_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                    $('html, body').animate({
                        scrollTop: $('#div_edit_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}

function delete_affiliate_package(affiliate_package_id) {
    swal({
        text: 'Do you wish to delete this affiliate_package?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/affiliate_packages/delete/' + affiliate_package_id,
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
                            load_affiliate_packages();
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

}




//ONLINE SALES
function filter_online_sales(){
    $("#online_sales_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $("#btn_online_sales_filter").html('FILTER <i class="icon-spinner2 spinner"></i>');

    var form = document.getElementById('frm_filter_online_sales');
    var formData = new FormData(form);
    $.ajax({
        url: baseDir + 'be/sales/filter_js_online_sales',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#online_sales_div").html(result);

            setInterval(function() {
                $("#online_sales_div").LoadingOverlay("hide");
            }, 1000);
            $("#btn_online_sales_filter").html('FILTER');
        },
        error: function() {
            setInterval(function() {
                $("#online_sales_div").LoadingOverlay("hide");
            }, 1000);
            $("#btn_online_sales_filter").html('FILTER');
        }
    });

}

function submit_dispatch_online_order(context) {
    if ($("#frm_dispatch_online_order").valid()) {
        swal({
            text: 'Do you wish to dispatch this Order?',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {

                var form = document.getElementById('frm_dispatch_online_order');
                var formData = new FormData(form);

                $.ajax({
                    url: baseDir + 'be/sales/submit_dispatch_order',
                    type: 'POST',
                    data: formData,
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
                                $("#modal_dispatch_online_order").modal('hide');
                                $("#modal_verify_pesapal_payment").modal('hide');
                                if (context == 'List') {
                                    filter_online_sales();
                                } else if (context == 'View') {
                                    location.reload();
                                }                                
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
    }
    return false;
}

function verify_pesapal_payment_load(ord_order_number, context) {
	$("#div_pesapal_payment_verification").html('');
	$("#div_pesapal_payment_verification").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/sales/verify_pesapal_payment/' + ord_order_number,
        type: 'POST',
        data: {context: context},
        success: function(result) {
            $("#div_pesapal_payment_verification").html(result);
            $("#div_pesapal_payment_verification").LoadingOverlay("hide");
        },
        error: function() {
            setTimeout(function() {
                $("#div_pesapal_payment_verification").LoadingOverlay("hide");
            }, 1000);
        }
    });
}


function complete_order(ord_order_number) {
    swal({
        text: 'Do you wish to complete this Order?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/sales/complete_order/' + ord_order_number,
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

}

function submit_send_online_order_via_email(){
    if ($("#frm_send_online_order_via_email").valid()) {

        var form = document.getElementById('frm_send_online_order_via_email');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/sales/submit_send_online_order_via_email',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_send_online_order_via_email").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');
            },
            success: function(res){
                $("#btn_send_online_order_via_email").html('<em class="icon ni ni-send mr-1"></em>Send Email');
                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: res.message
                    });
                } else {
                    swal({
                        type: 'success',
                        title: res.message
                    });
                }
            },
            error: function(){
                $("#btn_send_online_order_via_email").html('<em class="icon ni ni-send mr-1"></em>Send Email');
            }
        });
    }
    return false;
}

function submit_send_online_order_customer_email(){
    CKEDITOR.instances['customer_email_message'].updateElement();
    if ($("#frm_send_online_order_customer_email").valid()) {

        var form = document.getElementById('frm_send_online_order_customer_email');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'be/sales/submit_send_online_order_customer_email',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_send_online_order_customer_email").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');
            },
            success: function(res){
                $("#btn_send_online_order_customer_email").html('<em class="icon ni ni-send mr-1"></em>Send Email');
                if (res.status == 'ERR') {
                    swal({
                        type: 'warning',
                        title: res.message
                    });
                } else {
                    swal({
                        type: 'success',
                        title: res.message
                    });
                }
            },
            error: function(){
                $("#btn_send_online_order_customer_email").html('<em class="icon ni ni-send mr-1"></em>Send Email');
            }
        });
    }
    return false;
}

//PAYBILL PAYMENTS
function filter_paybill_payments(){
    $("#paybill_payments_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $("#btn_paybill_payments_filter").html('FILTER <i class="icon-spinner2 spinner"></i>');

    var form = document.getElementById('frm_filter_paybill_payments');
    var formData = new FormData(form);
    $.ajax({
        url: baseDir + 'be/payments/filter_js_paybill_payments',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#paybill_payments_div").html(result);

            setInterval(function() {
                $("#paybill_payments_div").LoadingOverlay("hide");
            }, 1000);
            $("#btn_paybill_payments_filter").html('FILTER');
        },
        error: function() {
            setInterval(function() {
                $("#paybill_payments_div").LoadingOverlay("hide");
            }, 1000);
            $("#btn_paybill_payments_filter").html('FILTER');
        }
    });

}

function paybill_payment_assign_transaction_load(paybill_payment_id) {
   $('#frm_paybill_payment_assign_transaction').each(function() {
        this.reset();
    }); 
   $("#assign_payment_transaction_type").val('').change();
   $("#assign_payment_transaction_id").val('').change();

   $("#paybill_payment_id").val(paybill_payment_id);

   $.ajax({
        url: baseDir+'be/payments/get_paybill_payment/' + paybill_payment_id,
        type: 'POST',
        data: '',
        dataType: 'json',
        beforeSend: function(){
            $("#paybill_payments_loader").fadeIn("fast");
        },
        success: function (res) {
            $("#paybill_payments_loader").fadeOut("fast");
            try{
                $.each(res, function(index, element) {
                    $("#payment_reference").val(element.transaction_id);
                    $("#payment_by").val(element.first_name + ' ' + element.middle_name + ' ' + element.last_name);
                    $("#payment_amount").val(element.transaction_amount);
                    $("#payment_date").val(element.transaction_time);
                    $("#payment_account").val(element.bill_reference_number);
                });                
            }catch(err){
                $("#paybill_payments_loader").fadeOut("fast");
            }
        },
        error: function(){
        }
    });
}

function submit_paybill_payment_assign_transaction(){
    if ($("#frm_paybill_payment_assign_transaction").valid()) {
        
        swal({
            text: 'Do you wish to assign the payment to this transaction? The system will attempt to complete the transaction if the amount will be sufficient.',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                var form = document.getElementById('frm_paybill_payment_assign_transaction');
                var formData = new FormData(form);

                $.ajax({
                    url: baseDir+'be/payments/submit_paybill_payment_assign_transaction',
                    type: 'POST',
                    data: formData,
                    dataType: 'json',
                    xhr: function() {
                        var myXhr = $.ajaxSettings.xhr();
                        return myXhr;
                    },
                    cache: false,
                    contentType: false,
                    processData: false,
                    beforeSend: function(){
                        $("#btn_assign_paybill_payment_transaction").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');
                    },
                    success: function (res) {
                        $("#btn_assign_paybill_payment_transaction").html('<i class="icon-checkmark2"></i> Assign Payment');
                        if(res.status == 'ERR'){
                            if (res.status_code == 1) {
                                swal({
                                    type: 'warning',
                                    title: 'Error',
                                    html: res.message
                                });
                            }else if (res.status_code == 2) {
                                swal({
                                    html: res.message,
                                    type: 'question',
                                    showCancelButton: true,
                                    confirmButtonText: 'Yes',
                                    cancelButtonText: 'No',
                                    allowOutsideClick: false
                                }).then(function(result) {
                                    if (result.value) {
                                        var form = document.getElementById('frm_paybill_payment_assign_transaction');
                                        var formData = new FormData(form);

                                        $.ajax({
                                            url: baseDir+'be/payments/submit_paybill_overpayment_assign_transaction',
                                            type: 'POST',
                                            data: formData,
                                            dataType: 'json',
                                            xhr: function() {
                                                var myXhr = $.ajaxSettings.xhr();
                                                return myXhr;
                                            },
                                            cache: false,
                                            contentType: false,
                                            processData: false,
                                            beforeSend: function(){
                                                $("#btn_assign_paybill_payment_transaction").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');
                                            },
                                            success: function (res) {
                                                $("#btn_assign_paybill_payment_transaction").html('<i class="icon-checkmark2"></i> Assign Payment');
                                                if(res.status == 'ERR'){
                                                    swal({
                                                        type: 'warning',
                                                        title: 'Error',
                                                        html: res.message
                                                    });
                                                }else if (res.status == 'SUCCESS'){
                                                    swal({
                                                        type: 'success',
                                                        title: 'Success!',
                                                        html: res.message
                                                    });
                                                    
                                                    filter_paybill_payments();

                                                    setTimeout(function() {
                                                        $('#modal_assign_paybill_payment_transaction').modal('hide');
                                                    }, 3000);
                                                }
                                            },
                                            error: function(){
                                                $("#btn_assign_paybill_payment_transaction").html('<i class="icon-checkmark2"></i> Assign Payment');
                                                swal({
                                                    type: 'warning',
                                                    title: 'Error',
                                                    html: 'Something went wrong. Please check your network and try again.'
                                                });
                                            }
                                        });
                                    }
                                });
                            }
                        }else if (res.status == 'SUCCESS'){
                            swal({
                                type: 'success',
                                title: 'Success!',
                                html: res.message
                            });
                            
                            filter_paybill_payments();

                            setTimeout(function() {
                                $('#modal_assign_paybill_payment_transaction').modal('hide');
                            }, 3000);
                        }
                    },
                    error: function(){
                        $("#btn_assign_paybill_payment_transaction").html('<i class="icon-checkmark2"></i> Assign Payment');
                        swal({
                            type: 'warning',
                            title: 'Error',
                            html: 'Something went wrong. Please check your network and try again.'
                        });
                    }
                });
            }
        });
    }
    return false;

}

//PESAPAL PAYMENTS
function filter_pesapal_payments(){
    $("#pesapal_payments_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $("#btn_pesapal_payments_filter").html('FILTER <i class="icon-spinner2 spinner"></i>');

    var form = document.getElementById('frm_filter_pesapal_payments');
    var formData = new FormData(form);
    $.ajax({
        url: baseDir + 'be/payments/filter_js_pesapal_payments',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#pesapal_payments_div").html(result);

            setInterval(function() {
                $("#pesapal_payments_div").LoadingOverlay("hide");
            }, 1000);
            $("#btn_pesapal_payments_filter").html('FILTER');
        },
        error: function() {
            setInterval(function() {
                $("#pesapal_payments_div").LoadingOverlay("hide");
            }, 1000);
            $("#btn_pesapal_payments_filter").html('FILTER');
        }
    });

}

//NOTIFICATIONS
function filter_notifications(){
    $("#notifications_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/support/filter_js_notifications',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#notifications_div").html(result);

            setInterval(function() {
                $("#notifications_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#notifications_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function load_ajax_notifications(){
    $.ajax({
        url: baseDir+'be/support/get_ajax_notifications',
        type: 'POST',
        data: '',
        dataType: 'json',
        success: function (result) {
            $("#notifications_pull_down").html(result.data);
            $("#notifications_label_count").html(result.count);
        },
        error: function(){
        }
    });
}

//APPROVE AFFILIATE
function approve_affiliate() {
	if ($("#frm_approve_affiliate").valid()) {
	    swal({
	        text: 'Do you wish to Approve this Affiliate Account?',
	        type: 'question',
	        showCancelButton: true,
	        confirmButtonText: 'Yes',
	        cancelButtonText: 'No',
	        allowOutsideClick: false
	    }).then(function(result) {
	        if (result.value) {
	        	var form = document.getElementById('frm_approve_affiliate');
	            var formData = new FormData(form);
	            $.ajax({
	                url: baseDir + 'be/affiliates/approve',
	                type: 'POST',
	                data: formData,
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
	                            setTimeout(function() {
		                            location.reload();
		                        }, 2000);
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
	}
	return false;
}

//ASSIGN AFFILIATE
function assign_affiliate() {
	if ($("#frm_assign_affiliate").valid()) {
	    swal({
	        text: 'Do you wish to assign this Affiliate Package?',
	        type: 'question',
	        showCancelButton: true,
	        confirmButtonText: 'Yes',
	        cancelButtonText: 'No',
	        allowOutsideClick: false
	    }).then(function(result) {
	        if (result.value) {
	        	var form = document.getElementById('frm_assign_affiliate');
	            var formData = new FormData(form);
	            $.ajax({
	                url: baseDir + 'be/affiliates/assign_package',
	                type: 'POST',
	                data: formData,
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
	                            setTimeout(function() {
		                            location.reload();
		                        }, 2000);
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
	}
	return false;
}


//PROMO CODES
function save_promo_code() {
    $("#div_add_error").fadeOut("fast");
    $("#div_add_success").fadeOut("fast");

    if ($("#frm_add_promo_code").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_add_promo_code").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_add_promo_code").html('<i class="icon-checkmark4"></i> SAVE NEW PROMO CODE');
            $("#div_add_error").html($valmsg);
            $("#div_add_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_add_error").fadeOut("fast");

            var form = document.getElementById('frm_add_promo_code');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/promo_codes/save',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_add_promo_code").html('<i class="icon-checkmark4"></i> SAVE NEW PROMO CODE');
                    if (res.status == 'ERR') {
                        $("#div_add_error").html(res.message);
                        $("#div_add_error").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_add_success").html(res.message);
                        $("#div_add_success").fadeIn("fast");

                        $('#frm_add_promo_code').each(function() {
                            this.reset();
                        });

                        $("#add_promo_mode").val('').change();

                        load_promo_codes();

                        setTimeout(function() {
                            $("#div_add_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_add_promo_code").html('<i class="icon-checkmark4"></i> SAVE NEW PROMO CODE');
                    $("#div_add_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_add_error").fadeIn("fast");
                    $('html, body').animate({
                        scrollTop: $('#div_add_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}

function load_promo_codes() {
    $("#promo_codes_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/promo_codes/loadjs',
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#promo_codes_div").html(result);

            setInterval(function() {
                $("#promo_codes_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#promo_codes_div").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function promo_code_edit_load(promo_code_id) {
    $("#div_edit_promo_code_error").fadeOut("fast");
    $("#div_edit_promo_code_success").fadeOut("fast");

    $('#frm_edit_promo_code').each(function() {
        this.reset();
    });

    $.ajax({
        url: baseDir + 'be/promo_codes/get_promo_code2/' + promo_code_id,
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
                obj1 = obj1.replace('[', '');
                obj1 = obj1.replace(']', '');
                var obj = $.parseJSON(obj1);

                $("#edit_promo_code_id").val(obj['promo_code_id']);
                $("#edit_promo_code_name").val(obj['promo_code_name']);
                $("#edit_promo_code").val(obj['promo_code']);
                $("#edit_promo_mode").val(obj['promo_mode']).change();
                $("#edit_promo_value").val(obj['promo_value']);
                $("#edit_sort_key").val(obj['sort_key']);
                if (obj['is_active'] == 0){
                    $("#edit_is_active_1").removeAttr( "checked").change();
                    $("#edit_is_active_0").attr('checked', true).change();
                    
                }else if (obj['is_active'] == 1){
                    $("#edit_is_active_0").removeAttr( "checked").change();
                    $("#edit_is_active_1").attr('checked', true).change(); 
                }

            } catch (err) {
                alert(err);
            }
        },
        error: function() {}
    });
}

function update_promo_code() {
    $("#div_edit_error").fadeOut("fast");
    $("#div_edit_success").fadeOut("fast");

    if ($("#frm_edit_promo_code").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_edit_promo_code").html('PROCESSING <i class="fa fa-spinner fa-spin ml-2"></i>');

        if ($valmsg != $valmsg2) {
            $("#btn_edit_promo_code").html('<i class="icon-checkmark4"></i> UPDATE');
            $("#div_edit_error").html($valmsg);
            $("#div_edit_error").fadeIn("fast");
            $('html, body').animate({
                scrollTop: $('#div_edit_error').offset().top - 90
            }, 'slow');
        } else {
            $("#div_edit_error").fadeOut("fast");

            var form = document.getElementById('frm_edit_promo_code');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'be/promo_codes/update',
                type: 'POST',
                data: formData,
                dataType: 'json',
                xhr: function() {
                    var myXhr = $.ajaxSettings.xhr();
                    return myXhr;
                },
                cache: false,
                contentType: false,
                processData: false,
                success: function(res) {
                    $("#btn_edit_promo_code").html('<i class="icon-checkmark4"></i> UPDATE');
                    if (res.status == 'ERR') {
                        $("#div_edit_error").html(res.message);
                        $("#div_edit_error").fadeIn("fast");
                        $('html, body').animate({
                            scrollTop: $('#div_edit_error').offset().top - 90
                        }, 'slow');
                    } else if (res.status == 'SUCCESS') {
                        $("#div_edit_success").html(res.message);
                        $("#div_edit_success").fadeIn("fast");
                        load_promo_codes();
                        setTimeout(function() {
                            $("#div_edit_success").fadeOut("fast");
                        }, 3000);
                    }
                },
                error: function() {
                    $("#btn_edit_promo_code").html('<i class="icon-checkmark4"></i> UPDATE');
                    $("#div_edit_error").html("<i class='fa fa-exclamation-circle'></i> Something went wrong. Please check your network and try again.");
                    $("#div_edit_error").fadeIn("fast");
                    $('html, body').animate({
                        scrollTop: $('#div_edit_error').offset().top - 90
                    }, 'slow');
                }
            });

        }
    }
    return false;
}

function delete_promo_code(promo_code_id) {
    swal({
        text: 'Do you wish to delete this Promo Code?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'be/promo_codes/delete/' + promo_code_id,
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
                            load_promo_codes();
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
}

function load_affiliate_referrals(affiliate_id) {
    $("#affiliate_referalls_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/affiliates/loadjs_account_referrals/'+affiliate_id,
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#affiliate_referalls_div").html(result);

            setInterval(function() {
                $("#affiliate_referalls_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#affiliate_referalls_div").LoadingOverlay("hide");
            }, 1000);
        }
    });

}

function load_affiliate_clicks(affiliate_id) {
    $("#affiliate_clicks_div").LoadingOverlay("show", {
        background: "rgba(255, 255, 255, 0)",
        imageColor: "#666",
        imageResizeFactor: 0.3
    });
    $.ajax({
        url: baseDir + 'be/affiliates/loadjs_account_clicks/'+affiliate_id,
        type: 'POST',
        data: '',
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        success: function(result) {
            $("#affiliate_clicks_div").html(result);

            setInterval(function() {
                $("#affiliate_clicks_div").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setInterval(function() {
                $("#affiliate_clicks_div").LoadingOverlay("hide");
            }, 1000);
        }
    });

}

function filter_sales_summary() {

    var form = document.getElementById('frm_filter_sales_summary');
    var formData = new FormData(form);

    $.ajax({
        url: baseDir + 'be/reports/get_sales_summary_report',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $('#sales_summary_loader').fadeIn();
        },
        success: function(result) {
            $("#sales_summary_report_div").html(result);

            $('#sales_summary_loader').fadeOut();
        },
        error: function() {
            $('#sales_summary_loader').fadeOut();
        }
    });
}

function filter_credit_list_report() {

    var form = document.getElementById('frm_filter_credit_list_report');
    var formData = new FormData(form);

    $.ajax({
        url: baseDir + 'be/reports/get_credit_list_report',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $('#credit_list_report_loader').fadeIn();
        },
        success: function(result) {
            $("#credit_list_report_div").html(result);

            $('#credit_list_report_loader').fadeOut();
        },
        error: function() {
            $('#credit_list_report_loader').fadeOut();
        }
    });
}

function export_credit_list_report() {

    var customerId = $('#clr_customer_id').val();
    var customerName = $("#clr_customer_id option:selected").text();
    var systemUserId = $('#clr_system_user_id').val();
    var systemUserName = $("#clr_system_user_id option:selected").text();
    var chk_cash_sales = 'off';

    if ($('#clr_chk_cash_sales').is(':checked') == true){
        chk_cash_sales = 'on';
    } else {
        chk_cash_sales = 'off';
    }

    $.redirect(
        baseDir + 'be/reports/export_credit_list_report',
        {
            customer_id: customerId,
            customer_name: customerName,
            system_user_id: systemUserId,
            system_user_name: systemUserName,
            chk_cash_sales: chk_cash_sales
        },
        "POST",
        "_blank"
    );
}

function filter_customer_aging_report() {

    var form = document.getElementById('frm_filter_customer_aging_report');
    var formData = new FormData(form);

    $.ajax({
        url: baseDir + 'be/reports/get_customer_aging_report',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $('#customer_aging_report_loader').fadeIn();
        },
        success: function(result) {
            $("#customer_aging_report_div").html(result);

            $('#customer_aging_report_loader').fadeOut();
        },
        error: function() {
            $('#customer_aging_report_loader').fadeOut();
        }
    });
}

function export_customer_aging_report() {

    var car_date = $('#car_date').val();

    $.redirect(
        baseDir + 'be/reports/export_customer_aging_report',
        {
            car_date: car_date
        },
        "POST",
        "_blank"
    );
}

function filter_sales_by_items() {

    var form = document.getElementById('frm_filter_sales_by_items');
    var formData = new FormData(form);

    $.ajax({
        url: baseDir + 'be/reports/get_sales_by_items_report',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $('#sales_by_items_loader').fadeIn();
        },
        success: function(result) {
            $("#sales_by_items_report_div").html(result);

            $('#sales_by_items_loader').fadeOut();
        },
        error: function() {
            $('#sales_by_items_loader').fadeOut();
        }
    });
}

function export_sales_by_items_report() {

    var dateFrom = $('#sbi_date_from').val();
    var dateTo = $('#sbi_date_to').val();
    var outletId = $('#sbi_outlet_id').val();
    var outletName = $("#sbi_outlet_id option:selected").text();
    var transactionType = $('#sbi_transaction_type').val();

    $.redirect(
        baseDir + 'be/reports/export_sales_by_items_report',
        {
            date_from: dateFrom,
            date_to: dateTo, 
            outlet_id: outletId,
            outlet_name: outletName,
            transaction_type: transactionType
        },
        "POST",
        "_blank"
    );
}

function filter_sales_transactions() {

    var form = document.getElementById('frm_filter_sales_transactions');
    var formData = new FormData(form);

    $.ajax({
        url: baseDir + 'be/reports/get_sales_transactions_report',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $('#sales_transactions_loader').fadeIn();
        },
        success: function(result) {
            $("#sales_transactions_report_div").html(result);

            $('#sales_transactions_loader').fadeOut();
        },
        error: function() {
            $('#sales_transactions_loader').fadeOut();
        }
    });
}

function export_sales_transactions() {

    var dateFrom = $('#st_date_from').val();
    var dateTo = $('#st_date_to').val();
    var outletId = $('#st_outlet_id').val();
    var outletName = $("#st_outlet_id option:selected").text();
    var saleType = $('#st_sale_type').val();
    var saleStatus = $('#st_sale_status').val();
    var saleStatusText = $("#st_sale_status option:selected").text();
    var systemUserId = $('#st_system_user_id').val();
    var systemUserName = $("#st_system_user_id option:selected").text();

    $.redirect(
        baseDir + 'be/reports/export_sales_transactions_report',
        {
            date_from: dateFrom,
            date_to: dateTo, 
            outlet_id: outletId,
            outlet_name: outletName,
            sale_type: saleType,
            sale_status: saleStatus,
            sale_status_text: saleStatusText,
            system_user_id: systemUserId,
            system_user_name: systemUserName
        },
        "POST",
        "_blank"
    );
}

function filter_online_sales_transactions() {

    var form = document.getElementById('frm_filter_online_sales_transactions');
    var formData = new FormData(form);

    $.ajax({
        url: baseDir + 'be/reports/get_online_sales_transactions_report',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $('#online_sales_transactions_loader').fadeIn();
        },
        success: function(result) {
            $("#online_sales_transactions_report_div").html(result);

            $('#online_sales_transactions_loader').fadeOut();
        },
        error: function() {
            $('#online_sales_transactions_loader').fadeOut();
        }
    });
}

function export_online_sales_transactions() {

    var dateFrom = $('#ost_date_from').val();
    var dateTo = $('#ost_date_to').val();
    var outletId = $('#ost_outlet_id').val();
    var outletName = $("#ost_outlet_id option:selected").text();

    $.redirect(
        baseDir + 'be/reports/export_online_sales_transactions_report',
        {
            date_from: dateFrom,
            date_to: dateTo, 
            outlet_id: outletId,
            outlet_name: outletName
        },
        "POST",
        "_blank"
    );
}

function filter_item_sales() {

    var form = document.getElementById('frm_filter_item_sales');
    var formData = new FormData(form);

    $.ajax({
        url: baseDir + 'be/reports/get_item_sales_report',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $('#item_sales_loader').fadeIn();
        },
        success: function(result) {
            $("#item_sales_report_div").html(result);

            $('#item_sales_loader').fadeOut();
        },
        error: function() {
            $('#item_sales_loader').fadeOut();
        }
    });
}

function export_item_sales() {

    var dateFrom = $('#is_date_from').val();
    var dateTo = $('#is_date_to').val();
    var outletId = $('#is_outlet_id').val();
    var outletName = $("#is_outlet_id option:selected").text();
    var productId = $('#is_product_id').val();
    var productName = $("#is_product_id option:selected").text();
    var customerId = $('#is_customer_id').val();
    var customerName = $("#is_customer_id option:selected").text();
    var systemUserId = $('#is_system_user_id').val();
    var systemUserName = $("#is_system_user_id option:selected").text();

    $.redirect(
        baseDir + 'be/reports/export_item_sales_report',
        {
            date_from: dateFrom,
            date_to: dateTo, 
            outlet_id: outletId,
            outlet_name: outletName,
            product_id: productId,
            product_name: productName,
            customer_id: customerId,
            customer_name: customerName,
            system_user_id: systemUserId,
            system_user_name: systemUserName
        },
        "POST",
        "_blank"
    );
}

function filter_stock_report() {

    var form = document.getElementById('frm_filter_stock_report');
    var formData = new FormData(form);

    $.ajax({
        url: baseDir + 'be/reports/get_stock_report',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $('#stock_report_loader').fadeIn();
        },
        success: function(result) {
            $("#stock_report_div").html(result);

            $('#stock_report_loader').fadeOut();
        },
        error: function() {
            $('#stock_report_loader').fadeOut();
        }
    });
}

function export_report_stock(outlet_id) {

    $.redirect(
        baseDir + 'be/reports/export_report_stock',
        {
            outlet_id: outlet_id
        },
        "POST",
        "_blank"
    );
}

function filter_low_stocks_report() {

    var form = document.getElementById('frm_filter_low_stocks_report');
    var formData = new FormData(form);

    $.ajax({
        url: baseDir + 'be/reports/get_low_stocks_report',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $('#low_stocks_report_loader').fadeIn();
        },
        success: function(result) {
            $("#low_stocks_report_div").html(result);

            $('#low_stocks_report_loader').fadeOut();
        },
        error: function() {
            $('#low_stocks_report_loader').fadeOut();
        }
    });
}

function export_report_low_stocks(outlet_id) {

    $.redirect(
        baseDir + 'be/reports/export_report_low_stocks',
        {
            outlet_id: outlet_id
        },
        "POST",
        "_blank"
    );
}

function low_stocks_create_purchase_order(outlet_id) {

    $.redirect(
        baseDir + 'be/inventory/purchase_order_new',
        {
            outlet_id: outlet_id
        },
        "POST",
        "_blank"
    );
}

function filter_payments_summary() {

    var form = document.getElementById('frm_filter_payments_summary');
    var formData = new FormData(form);

    $.ajax({
        url: baseDir + 'be/reports/get_payments_summary_report',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $('#payments_summary_loader').fadeIn();
        },
        success: function(result) {
            $("#payments_summary_report_div").html(result);

            $('#payments_summary_loader').fadeOut();
        },
        error: function() {
            $('#payments_summary_loader').fadeOut();
        }
    });
}

function filter_pos_payments(){
    var form = document.getElementById('frm_filter_pos_payments');
    var formData = new FormData(form);

    $.ajax({
        url: baseDir + 'be/reports/get_pos_payments',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $('#pos_payments_loader').fadeIn();
        },
        success: function(result) {
            $("#pos_payments_div").html(result);

            $('#pos_payments_loader').fadeOut();
        },
        error: function() {
            $('#pos_payments_loader').fadeOut();
        }
    });
}

function export_pos_payments_report(){
    var dateFrom = $('#pp_date_from').val();
    var dateTo = $('#pp_date_to').val();
    var paymentMethod = $('#pp_payment_method').val();

    $.redirect(
        baseDir + 'be/reports/export_pos_payments_report',
        {
            date_from: dateFrom,
            date_to: dateTo,
            payment_method: paymentMethod
        },
        "POST",
        "_blank"
    );
}

function filter_online_payments(){
    var form = document.getElementById('frm_filter_online_payments');
    var formData = new FormData(form);

    $.ajax({
        url: baseDir + 'be/reports/get_online_payments',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $('#online_payments_loader').fadeIn();
        },
        success: function(result) {
            $("#online_payments_div").html(result);

            $('#online_payments_loader').fadeOut();
        },
        error: function() {
            $('#online_payments_loader').fadeOut();
        }
    });
}

function export_online_payments_report(){
    var dateFrom = $('#op_date_from').val();
    var dateTo = $('#op_date_to').val();

    $.redirect(
        baseDir + 'be/reports/export_online_payments_report',
        {
            date_from: dateFrom,
            date_to: dateTo
        },
        "POST",
        "_blank"
    );
}

function filter_expenses_report(){
    var form = document.getElementById('frm_filter_expenses_report');
    var formData = new FormData(form);

    $.ajax({
        url: baseDir + 'be/reports/get_expenses_report',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $('#expenses_report_loader').fadeIn();
        },
        success: function(result) {
            $("#expenses_report_div").html(result);

            $('#expenses_report_loader').fadeOut();
        },
        error: function() {
            $('#expenses_report_loader').fadeOut();
        }
    });
}

function export_expenses_report(){

    var dateFrom = $('#er_date_from').val();
    var dateTo = $('#er_date_to').val();
    var outletId = $('#er_outlet_id').val();
    var outletName = $("#er_outlet_id option:selected").text();
    var systemUserId = $('#er_system_user_id').val();
    var systemUserName = $("#er_system_user_id option:selected").text();
    var status = $('#er_status').val();
    var statusText = $("#er_status option:selected").text();

    $.redirect(
        baseDir + 'be/reports/export_expenses_report',
        {
            date_from: dateFrom,
            date_to: dateTo, 
            outlet_id: outletId,
            outlet_name: outletName,
            system_user_id: systemUserId,
            system_user_name: systemUserName,
            status: status,
            status_text: statusText
        },
        "POST",
        "_blank"
    );
}

function filter_income_statement(){
    var form = document.getElementById('frm_filter_income_statement');
    var formData = new FormData(form);

    $.ajax({
        url: baseDir + 'be/reports/get_income_statement',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $('#income_statement_loader').fadeIn();
        },
        success: function(result) {
            $("#income_statement_div").html(result);

            $('#income_statement_loader').fadeOut();
        },
        error: function() {
            $('#income_statement_loader').fadeOut();
        }
    });
}

function export_income_statement(){

    var dateFrom = $('#is_date_from').val();
    var dateTo = $('#is_date_to').val();

    $.redirect(
        baseDir + 'be/reports/export_income_statement',
        {
            date_from: dateFrom,
            date_to: dateTo
        },
        "POST",
        "_blank"
    );
}


function filter_current_stocks() {

    var form = document.getElementById('frm_filter_current_stocks');
    var formData = new FormData(form);

    $.ajax({
        url: baseDir + 'be/inventory/get_current_stocks',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $('#current_stocks_loader').fadeIn();
        },
        success: function(result) {
            $("#current_stocks_div").html(result);

            $('#current_stocks_loader').fadeOut();
        },
        error: function() {
            $('#current_stocks_loader').fadeOut();
        }
    });
}

function filter_low_stocks() {

    var form = document.getElementById('frm_filter_low_stocks');
    var formData = new FormData(form);

    $.ajax({
        url: baseDir + 'be/inventory/get_low_stocks',
        type: 'POST',
        data: formData,
        xhr: function() {
            var myXhr = $.ajaxSettings.xhr();
            return myXhr;
        },
        cache: false,
        contentType: false,
        processData: false,
        beforeSend: function() {
            $('#low_stocks_loader').fadeIn();
        },
        success: function(result) {
            $("#low_stocks_div").html(result);

            $('#low_stocks_loader').fadeOut();
        },
        error: function() {
            $('#low_stocks_loader').fadeOut();
        }
    });
}


//LNM CONTACTS RECONCILIATION
function submit_lnm_v1_contacts_reconciliation() {

    if ($("#frm_lnm_v1_contacts_reconciliation").valid()) {
        
        var form = document.getElementById('frm_lnm_v1_contacts_reconciliation');
        var formData = new FormData(form);

        $.ajax({
            url: baseDir+'be/settings/lnm_v1_contacts_reconciliation',
            type: 'POST',
            data: formData,
            dataType: 'json',
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                return myXhr;
            },
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                $("#btn_lnm_v1_contacts_reconciliation").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');
            },
            success: function (res) {
                $("#btn_lnm_v1_contacts_reconciliation").html('<i class="icon-checkmark2"></i> Reconcile');

                if(res.status == 'ERR'){
                    swal({
                        type: 'warning',
                        title: 'Error',
                        html: res.message
                    });
                }else if (res.status == 'SUCCESS'){
                    swal({
                        type: 'success',
                        title: 'Success!',
                        html: res.message
                    });
                }

            },
            error: function(){
                $("#btn_lnm_v1_contacts_reconciliation").html('<i class="icon-checkmark2"></i> Reconcile');
                swal({
                    type: 'warning',
                    title: 'Error',
                    html: 'Something went wrong. Please check your network and try again.'
                });
            }
        });
    }

    return false;
}

function submit_lnm_v2_contacts_reconciliation() {

    if ($("#frm_lnm_v2_contacts_reconciliation").valid()) {
        
        var form = document.getElementById('frm_lnm_v2_contacts_reconciliation');
        var formData = new FormData(form);

        $.ajax({
            url: baseDir+'be/settings/lnm_v2_contacts_reconciliation',
            type: 'POST',
            data: formData,
            dataType: 'json',
            xhr: function() {
                var myXhr = $.ajaxSettings.xhr();
                return myXhr;
            },
            cache: false,
            contentType: false,
            processData: false,
            beforeSend: function(){
                $("#btn_lnm_v2_contacts_reconciliation").html('Processing <i class="fa fa-spinner fa-spin ml-2"></i>');
            },
            success: function (res) {
                $("#btn_lnm_v2_contacts_reconciliation").html('<i class="icon-checkmark2"></i> Reconcile');

                if(res.status == 'ERR'){
                    swal({
                        type: 'warning',
                        title: 'Error',
                        html: res.message
                    });
                }else if (res.status == 'SUCCESS'){
                    swal({
                        type: 'success',
                        title: 'Success!',
                        html: res.message
                    });
                }

            },
            error: function(){
                $("#btn_lnm_v2_contacts_reconciliation").html('<i class="icon-checkmark2"></i> Reconcile');
                swal({
                    type: 'warning',
                    title: 'Error',
                    html: 'Something went wrong. Please check your network and try again.'
                });
            }
        });
    }

    return false;
}





