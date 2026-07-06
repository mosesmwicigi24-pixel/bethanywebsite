
$(document).ready(function() {

    lazyload();

    var setCustomDefaults = function() {
        swal.setDefaults({
            buttonsStyling: false,
            confirmButtonClass: 'btn btn-success',
            cancelButtonClass: 'btn btn-danger'
        });
    }

    var shippingRules = {
        shipping_first_name: {
            required: true
        },
        shipping_last_name: {
            required: true
        },
        shipping_email_address: {
            required: true
        },
        shipping_phone_number: {
            required: true
        },
        shipping_street_address: {
            required: true
        },
        shipping_country_id: {
            required: true
        },
        shipping_region_id: {
            required: true
        }
    };


    setCustomDefaults();

    $('.show-pswd').password();

    $('.form-check-input-styled').uniform();

    $('.pickadate').pickadate({
        format: 'yyyy-mm-dd',
        formatSubmit: 'yyyy-mm-dd',
        selectMonths: true,
        selectYears: 100
    });

    $("#txt_search, #txt_mobile_search, #txt_sidebar_search").keyup(function(){
        var txtsearch = $(this);
        var searchElementID = txtsearch.attr('id');

        //console.log(searchElementID);

        if (txtsearch.val() != '' && txtsearch.val() != null) {
            $.ajax({
                type: 'POST',
                url: baseDir + 'home/ajax_search',
                data: { search_keyword: txtsearch.val(), search_element_id: searchElementID },
                beforeSend: function(){
                    txtsearch.css("background","#FFF url(" + baseDir + "assets/fe/img/loader9.gif) no-repeat right 10px");
                },
                success: function(data){
                    txtsearch.parent().find('.suggestion-box').show();
                    txtsearch.parent().find('.suggestion-box').html(data);
                    setTimeout(function() {
                         txtsearch.css("background","#FFF");
                     }, 2000);
                }
            });
        }else{
            txtsearch.parent().find('.suggestion-box').hide();
            setTimeout(function() {
                 txtsearch.css("background","#FFF");
             }, 2000);
        }        
    });


    //PRODUCT QUICKVIEW
	$(document).on('click', '.btn-product-quickview', function(){
        var product_id = $(this).attr("data-product-id");

        $("#div_product_quickview").LoadingOverlay("show", {
	        background: "rgba(255, 255, 255, 0)",
	        imageColor: "#666",
	        imageResizeFactor: 0.3
	    });
	    $.ajax({
	        url: baseDir + 'home/load_product_quickview/' + product_id,
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
	            $("#div_product_quickview").html(result);
                $("#div_product_quickview").LoadingOverlay("hide");
	        },
	        error: function() {
	            setTimeout(function() {
	                $("#div_product_quickview").LoadingOverlay("hide");
	            }, 1000);
	        }
	    });
    });


    //ADD TO CART
    $(document).on('click', '.btn-product-addtocart', function(){
        var product_id = $(this).attr("data-product-id");
        $.ajax({
            url: baseDir + 'cart/add',
            type: 'POST',
            data: {product_id: product_id},
            dataType: 'json',
            success: function(res) {
                if (res.status == 'ERR') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 2500 }).show();
                } else if (res.status == 'SUCCESS') {
                    //new Noty({ theme: 'limitless', text: res.message, type: 'success', modal: true, timeout: 2500 }).show();

                    var notyConfirm = new Noty({
                        theme: 'limitless',
                        text: res.message,
                        timeout: false,
                        modal: true,
                        layout: 'center',
                        closeWith: 'button',
                        type: 'confirm',
                        buttons: [
                            Noty.button('<i class="fa fa-angle-left"></i> Continue Shopping', 'btn badge-pill badge-dark', function () {
                                notyConfirm.close();
                            }),
                            Noty.button('<i class="fa fa-shopping-cart"></i> View Cart', 'btn badge-pill badge-info ml-2', function () {
                                window.location = baseDir + 'cart';
                            }),
                            Noty.button('Checkout <i class="fa fa-angle-right"></i>', 'btn badge-pill badge-success ml-2', function () {
                                window.location = baseDir + 'checkout';
                            })
                        ]
                    }).show();

                }
                load_ajax_cart();
                load_ajax_mobile_cart();
                load_ajax_main_cart();
            },
            error: function(xhr, status, error) {
                new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 2500 }).show();
            }
        });
    });

    //ADD TO FAVORITES
    $(document).on('click', '.btn-product-favorite', function(){
        var product_id = $(this).attr("data-product-id");
        $.ajax({
            url: baseDir + 'home/add_to_favorites',
            type: 'POST',
            data: {product_id: product_id},
            dataType: 'json',
            success: function(res) {
                if (res.status == 'ERR') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 2500 }).show();
                } else if (res.status == 'SUCCESS') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'success', modal: true, timeout: 2500 }).show();
                }
            },
            error: function(xhr, status, error) {
                new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 2500 }).show();
            }
        });
    });

    //ADD TO COMPARE
    $(document).on('click', '.btn-product-compare', function(){
        var product_id = $(this).attr("data-product-id");
        $.ajax({
            url: baseDir + 'home/add_to_compare',
            type: 'POST',
            data: {product_id: product_id},
            dataType: 'json',
            success: function(res) {
                if (res.status == 'ERR') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 2500 }).show();
                } else if (res.status == 'SUCCESS') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'success', modal: true, timeout: 2500 }).show();
                }
            },
            error: function(xhr, status, error) {
                new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 2500 }).show();
            }
        });
    });


    //REMOVE FROM CART
    $(document).on('click', '.ps-product__remove, .cart_product_remove', function(){
        var row_id = $(this).attr("data-row-id");

        $.ajax({
            url: baseDir + 'cart/remove/' + row_id,
            type: 'POST',
            data: '',
            dataType: 'json',
            success: function(res) {
                if (res.status == 'ERR') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 2500 }).show();
                } else if (res.status == 'SUCCESS') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'success', modal: true, timeout: 2500 }).show();
                }
                load_ajax_cart();
                load_ajax_mobile_cart();
                load_ajax_main_cart();
            },
            error: function(xhr, status, error) {
                new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 2500 }).show();
            }
        });
    });

    //REMOVE FROM COMPARE
    $(document).on('click', '.btn-remove-compare-product', function(){
        var product_id = $(this).attr("data-product-id");

        $.ajax({
            url: baseDir + 'home/remove_compare_product/' + product_id,
            type: 'POST',
            data: '',
            dataType: 'json',
            success: function(res) {
                if (res.status == 'ERR') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 2500 }).show();
                } else if (res.status == 'SUCCESS') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'success', modal: true, timeout: 2500 }).show();
                    load_compare_products();
                }
            },
            error: function(xhr, status, error) {
                new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 2500 }).show();
            }
        });
    });

    //REMOVE FAVORITE PRODUCT
    $(document).on('click', '.btn-remove-favorite-product', function(){
        var favorite_product_id = $(this).attr("data-favorite-product-id");

        $.ajax({
            url: baseDir + 'account/remove_favorite_product/' + favorite_product_id,
            type: 'POST',
            data: '',
            dataType: 'json',
            success: function(res) {
                if (res.status == 'ERR') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 2500 }).show();
                } else if (res.status == 'SUCCESS') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'success', modal: true, timeout: 2500 }).show();
                    load_favorite_products();
                }
            },
            error: function(xhr, status, error) {
                new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 2500 }).show();
            }
        });
    });

    //UPDATE CART
    $(document).on('click', '.cart_product_update', function(){
        var row_id = $(this).attr("data-row-id");
        var product_qty = $(this).parent().find('.qty-input').val();

        var elem = $(this);

        elem.html('<i class="fa fa-refresh fa-spin"></i>');

        console.log(row_id);
        //console.log(product_qty);

        $.ajax({
            url: baseDir + 'cart/update_item_quantity',
            type: 'POST',
            data: { row_id: row_id, product_qty: Number(product_qty) },
            dataType: 'json',
            success: function(res) {
                elem.html('<i class="fa fa-refresh"></i>');
                if (res.status == 'ERR') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 2500 }).show();
                } else if (res.status == 'SUCCESS') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'success', modal: true, timeout: 2500 }).show();
                }
                load_ajax_cart();
                load_ajax_mobile_cart();
                load_ajax_main_cart();
            },
            error: function(xhr, status, error) {
                new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 2500 }).show();
                elem.html('<i class="fa fa-refresh"></i>');
            }
        });

    });

    $(document).on('change', '#filter_shop_sort_by', function(){
        filter_shop_products();
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
                        //$("#shipping_region_id").val(cur_shipping_region_id);
                        //cur_shipping_region_id = '';
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

    $("#shipping_region_id").on('change', function() {
        load_checkout_pickup_locations();
        load_checkout_shipping_zones();
    });

    $("#chk_different_shipping_address").on('change', function() {
        if($(this).is(':checked')){
            $('#div_shipping_address').fadeIn(650);
            addRules(shippingRules);
        }else{
            $('#div_shipping_address').fadeOut(650);
            removeRules(shippingRules);
        }
        load_checkout_pickup_locations();
        load_checkout_shipping_zones();
    });

    // console.log(cur_shipping_region_id);

    if (cur_billing_region_id != '') {
        $("#billing_country_id").trigger("change");
    }

    if (cur_shipping_region_id != '') {
        $("#shipping_country_id").trigger("change");
    }

    $("#chk_shipping_pickup_location").on('change', function() {
        var pickUpRule = {
            shipping_pickup_location_id: {
                required: true
            }
        }
        var shippingZoneRule = {
            shipping_shipping_zone_id: {
                required: true
            }
        }

        if($(this).is(':checked')){
            $('#div_shipping_pickup_station').fadeIn(650);
            $('#div_shipping_shipping_zone').fadeOut(650);
            addRules(pickUpRule);
            removeRules(shippingZoneRule);
        }else{
            $('#div_shipping_pickup_station').fadeOut(650);
            $('#div_shipping_shipping_zone').fadeIn(650);
            addRules(shippingZoneRule);
            removeRules(pickUpRule);
        }

        checkout_update_shipping_fee();
    });
    $("#chk_shipping_delivery_location").on('change', function() {
        var pickUpRule = {
            shipping_pickup_location_id: {
                required: true
            }
        }
        var shippingZoneRule = {
            shipping_shipping_zone_id: {
                required: true
            }
        }
        if($(this).is(':checked')){
            $('#div_shipping_pickup_station').fadeOut(650);
            $('#div_shipping_shipping_zone').fadeIn(650);
            addRules(shippingZoneRule);
            removeRules(pickUpRule);
        }else{
            $('#div_shipping_pickup_station').fadeIn(650);
            $('#div_shipping_shipping_zone').fadeOut(650);
            addRules(pickUpRule);
            removeRules(shippingZoneRule);
        }

        checkout_update_shipping_fee();
    });

    $("#chk_payment_mpesa").on('change', function() {
        if($(this).is(':checked')){
            $('#div_mpesa_payment').slideDown();
            $('#div_pesapal_payment').slideUp();
        }else{
            $('#div_mpesa_payment').slideUp();
            $('#div_pesapal_payment').slideDown();
        }
    });

    $("#chk_payment_pesapal").on('change', function() {
        if($(this).is(':checked')){
            $('#div_mpesa_payment').slideUp();
            $('#div_pesapal_payment').slideDown();
        }else{
            $('#div_mpesa_payment').slideDown();
            $('#div_pesapal_payment').slideUp();
        }
    });

    $("#shipping_pickup_location_id").on('change', function() {

        var pickup_location_id = $(this).val();

        if (pickup_location_id != '') {
            $('#div_pickup_station_details').fadeIn(650);
             $("#div_pickup_station_details").LoadingOverlay("show", {
                 background: "rgba(255, 255, 255, 0)",
                 imageColor: "#666",
                 imageResizeFactor: 0.3
             });

             $.ajax({
                url: baseDir + 'checkout/get_shipping_pickup_location',
                type: 'POST',
                data: { pickup_location_id: pickup_location_id },
                success: function(result) {
                    $("#div_pickup_station_details").html(result);
                    setTimeout(function() {
                         $("#div_pickup_station_details").LoadingOverlay("hide");
                    }, 1000);
                },
                error: function() {
                    setTimeout(function() {
                         $("#div_pickup_station_details").LoadingOverlay("hide");
                    }, 1000);
                }
            });

        }else{
            $('#div_pickup_station_details').fadeOut(650);
        }
        checkout_update_shipping_fee();

    });

    $("#shipping_shipping_zone_id").on('change', function() {

        var shipping_zone_id = $(this).val();

        if (shipping_zone_id != '') {
            $('#div_shipping_zone_details').fadeIn(650);
             $("#div_shipping_zone_details").LoadingOverlay("show", {
                 background: "rgba(255, 255, 255, 0)",
                 imageColor: "#666",
                 imageResizeFactor: 0.3
             });

             $.ajax({
                url: baseDir + 'checkout/get_shipping_shipping_zone',
                type: 'POST',
                data: { shipping_zone_id: shipping_zone_id },
                success: function(result) {
                    $("#div_shipping_zone_details").html(result);
                    setTimeout(function() {
                         $("#div_shipping_zone_details").LoadingOverlay("hide");
                    }, 1000);
                },
                error: function() {
                    setTimeout(function() {
                         $("#div_shipping_zone_details").LoadingOverlay("hide");
                    }, 1000);
                }
            });
            
        }else{
            $('#div_shipping_zone_details').fadeOut(650);
        }
        checkout_update_shipping_fee();
    });

    


    $("#btn_validate_promo_code").on('click', function() {          
        $('#div_promo_code_error').fadeOut(650);
        $("#btn_validate_promo_code").html('Validate Code <i class="fa fa-spinner fa-spin"></i>');
        var promo_code = $('#promo_code').val();
        if (promo_code == '' || promo_code == null) {
            $("#btn_validate_promo_code").html('Validate Code');
            $("#div_promo_code_error").html('<i class="fa fa-exclamation-circle"></i> Please enter Promo Code before you validate');
            $("#div_promo_code_error").fadeIn("fast");
        } else {

            $('#div_promo_code_info').fadeOut(650);
            $('#promo_code_id').val('');
            $('#promo_mode').val('');
            $('#promo_value').val('');

            $.ajax({
                url: baseDir + 'checkout/validate_promo_code',
                type: 'POST',
                data: { promo_code: promo_code },
                dataType: 'json',
                success: function(res) {
                    if (res.status == 'ERR') {
                        $("#btn_validate_promo_code").html('Validate Code');
                        $("#div_promo_code_error").html(res.message);
                        $("#div_promo_code_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#btn_validate_promo_code").html('Validate Code');
                        $("#div_promo_code_info").html(res.promo_info);
                        $("#div_promo_code_info").fadeIn("fast");

                        $('#promo_code_id').val(res.promo_code_id);
                        $('#promo_mode').val(res.promo_mode);
                        $('#promo_value').val(res.promo_value);                        
                        //calculate_publish_total_due();
                    }
                    load_checkout_cart();
                },
                error: function() {

                }
            });
        }
    });

    $(document).on('click', '.btn-remove-promo-code', function(){   
            swal({
            html: 'Do you wish to remove this this Promo Code?',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    url: baseDir + 'checkout/remove_promo_code',
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
                                $('#div_promo_code_error').fadeOut(650);
                                $('#div_promo_code_info').html('');
                                $('#div_promo_code_info').fadeOut(650);
                                $('#promo_code').val('');
                            }
                        } catch (err) {
                            swal({
                                type: 'warning',
                                title: 'Error',
                                html: err
                            });
                        }
                        load_checkout_cart();
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








    //VALIDATION
    $("#frm_product_quick_view").validate({
        errorPlacement: function(error, element) {
        	if (element.parent().attr("class") == "form-group--number") {
        		error.insertAfter(element.parent().parent().parent());
        	}else{
             	error.insertAfter(element.parent().parent());
        	}
        },
        rules: {
        	product_color_id: {
                required: true
            },
            product_size_id: {
                required: true
            },
            product_qty: {
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

    $("#frm_product_review").validate({
        ignore: [],
        errorPlacement: function(error, element) {
            if (element.parent().attr("class") == "br-wrapper br-theme-fontawesome-stars") {
                error.appendTo(element.parent());
            }else{
                error.insertAfter(element); //.parent().parent()
            }
        },
        rules: {
            review_value: {
                required: true
            },
            review_description: {
                required: true
            },
            review_name: {
                required: true
            },
            review_email: {
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

    $("#frm_checkout_login").validate({
        ignore: [],
        errorPlacement: function(error, element) {
            if (element.parent().attr("class") == "input-group") {
                error.appendTo(element.parent().parent());
            }else{
                error.insertAfter(element); //.parent().parent()
            }
        },
        rules: {
            login_email_address: {
                required: true
            },
            login_password: {
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

    $("#frm_checkout_register").validate({
        ignore: [],
        errorPlacement: function(error, element) {
            if (element.parent().attr("class") == "input-group") {
                error.appendTo(element.parent().parent());
            }else{
                error.insertAfter(element); //.parent().parent()
            }
        },
        rules: {
            register_first_name: {
                required: true
            },
            register_last_name: {
                required: true
            },
            register_email_address: {
                required: true
            },
            register_password: {
                required: true
            },
            register_phone_number: {
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

    $("#frm_login").validate({
        ignore: [],
        errorPlacement: function(error, element) {
            if (element.parent().attr("class") == "input-group") {
                error.appendTo(element.parent().parent());
            }else{
                error.insertAfter(element); //.parent().parent()
            }
        },
        rules: {
            login_email_address: {
                required: true
            },
            login_password: {
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

    $("#frm_register").validate({
        ignore: [],
        errorPlacement: function(error, element) {
            if (element.parent().attr("class") == "input-group") {
                error.appendTo(element.parent().parent());
            }else{
                error.insertAfter(element); //.parent().parent()
            }
        },
        rules: {
            register_first_name: {
                required: true
            },
            register_last_name: {
                required: true
            },
            register_email_address: {
                required: true
            },
            register_phone_number: {
                required: true
            },
            register_password: {
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

    $("#frm_reset_password").validate({
        ignore: [],
        errorPlacement: function(error, element) {
            if (element.parent().attr("class") == "input-group") {
                error.appendTo(element.parent().parent());
            }else{
                error.insertAfter(element);
            }
        },
        rules: {
            reset_email_address: {
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

    $("#frm_checkout_address").validate({
        ignore: [],
        errorPlacement: function(error, element) {
            if (element.parent().attr("class") == "input-group") {
                error.appendTo(element.parent().parent());
            }else{
                error.insertAfter(element);
            }
        },
        rules: {
            chk_shipping_delivery_method: {
                required: true
            },
            shipping_pickup_location_id: {
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

    $("#frm_checkout_shipping").validate({
        ignore: [],
        errorPlacement: function(error, element) {
            if (element.parent().attr("class") == "input-group") {
                error.appendTo(element.parent().parent());
            }else{
                error.insertAfter(element);
            }
        },
        rules: {
            chk_shipping_delivery_method: {
                required: true
            },
            shipping_pickup_location_id: {
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

    $("#frm_send_publish_mpesa_request").validate({
        ignore: [],
        errorPlacement: function(error, element) {
            if (element.parent().attr("class") == "input-group mb-5") {
                error.appendTo(element.parent().parent());
            }else{
                error.insertAfter(element); //.parent().parent()
            }
        },
        rules: {
            payment_phone_number: {
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

    $("#frm_edit_account").validate({
        ignore: [],
        errorPlacement: function(error, element) {
            if (element.parent().attr("class") == "input-group") {
                error.appendTo(element.parent().parent());
            }else{
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
                required: true
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

    //VALIDATE FRM_CHANGE_PASSWORD
    $("#frm_change_password").validate({
        errorPlacement: function(error, element) {
            //console.log($(element).parent().attr('class'));
            if (element.parent().parent().attr("class") == "checker" || element.parent().parent().attr("class") == "choice") {
                error.appendTo(element.parent().parent().parent().parent().parent());
            } else if (element.parent().parent().attr("class") == "checkbox" || element.parent().attr("class") == "checkbox" || element.parent().parent().attr("class") == "radio") {
                error.appendTo(element.parent().parent().parent());
            } else if (element.parent().attr("class") == "input-group") {
                error.insertAfter(element.parent());
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

    //VALIDATE FRM_CHANGE_AFFILIATE_PASSWORD
    $("#frm_affiliate_change_password").validate({
        errorPlacement: function(error, element) {
            //console.log($(element).parent().attr('class'));
            if (element.parent().parent().attr("class") == "checker" || element.parent().parent().attr("class") == "choice") {
                error.appendTo(element.parent().parent().parent().parent().parent());
            } else if (element.parent().parent().attr("class") == "checkbox" || element.parent().attr("class") == "checkbox" || element.parent().parent().attr("class") == "radio") {
                error.appendTo(element.parent().parent().parent());
            } else if (element.parent().attr("class") == "input-group") {
                error.insertAfter(element.parent());
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

    $("#frm_edit_address").validate({
        ignore: [],
        errorPlacement: function(error, element) {
            if (element.parent().attr("class") == "input-group") {
                error.appendTo(element.parent().parent());
            }else{
                error.insertAfter(element);
            }
        },
        rules: {
            shipping_first_name: {
                required: true
            },
            shipping_last_name: {
                required: true
            },
            shipping_email_address: {
                required: true
            },
            shipping_phone_number: {
                required: true
            },
            shipping_street_address: {
                required: true
            },
            shipping_country_id: {
                required: true
            },
            shipping_region_id: {
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

    $("#frm_contact").validate({
        ignore: [],
        errorPlacement: function(error, element) {
            if (element.parent().attr("class") == "input-group") {
                error.appendTo(element.parent().parent());
            }else{
                error.insertAfter(element);
            }
        },
        rules: {
            contact_name: {
                required: true
            },
            contact_email: {
                required: true
            },
            contact_subject: {
                required: true
            },
            contact_message: {
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

    $("#frm_affiliate_register").validate({
        ignore: [],
        errorPlacement: function(error, element) {
            if (element.parent().attr("class") == "ps-checkbox") {
                error.appendTo(element.parent().parent());
            }else{
                error.insertAfter(element); //.parent().parent()
            }
        },
        rules: {
            register_first_name: {
                required: true
            },
            register_last_name: {
                required: true
            },
            register_email_address: {
                required: true
            },
            register_phone_number: {
                required: true
            },
            register_physical_address: {
                required: true
            },
            register_town: {
                required: true
            },
            register_country: {
                required: true
            },
            chk_terms: {
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

    $("#frm_affiliate_login").validate({
        ignore: [],
        errorPlacement: function(error, element) {
            if (element.parent().attr("class") == "ps-checkbox") {
                error.appendTo(element.parent().parent());
            }else{
                error.insertAfter(element); //.parent().parent()
            }
        },
        rules: {
            login_email_address: {
                required: true
            },
            login_password: {
                required: true
            },
            chk_terms: {
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

    
    
    if (sae == 1) {
        addRules(shippingRules);
    } else if (sae == 2) {
        removeRules(shippingRules);
    }

    if (cur_chk_different_shipping_address != '') {
        $("#chk_different_shipping_address").trigger("change");
    }

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

function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

function product_quick_view_add_to_cart() {

    if ($("#frm_product_quick_view").valid()) {

        $("#btn_quick_view_add_to_cart").html('<i class="icon-cart"></i> Add to cart <i class="fa fa-spinner fa-spin"></i>');

    	var form = document.getElementById('frm_product_quick_view');
        var formData = new FormData(form);

        $.ajax({
            url: baseDir + 'cart/add',
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
                    new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 2500 }).show();
                } else if (res.status == 'SUCCESS') {
                	new Noty({ theme: 'limitless', text: res.message, type: 'success', modal: true, timeout: 2500 }).show();
                }
                load_ajax_cart();
                load_ajax_mobile_cart();
                $("#btn_quick_view_add_to_cart").html('<i class="icon-cart"></i> Add to cart');
            },
            error: function(xhr, status, error) {
                new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 2500 }).show();
                $("#btn_quick_view_add_to_cart").html('<i class="icon-cart"></i> Add to cart');
            }
        });
    }
    return false;
}

function load_ajax_cart() {
    $.ajax({
        url: baseDir + 'cart/loadjs_cart',
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
            $(".ps-cart--mini").html(result);
        },
        error: function() {
        }
    });
}

function load_ajax_mobile_cart() {
    $.ajax({
        url: baseDir + 'cart/loadjs_mobile_cart',
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
            $("#ajax-mobile-cart").html(result);
        },
        error: function() {
        }
    });
}

function load_ajax_main_cart() {
     $("#ajax-main-cart").LoadingOverlay("show", {
         background: "rgba(255, 255, 255, 0)",
         imageColor: "#666",
         imageResizeFactor: 0.3
     });
    $.ajax({
        url: baseDir + 'cart/loadjs_main_cart',
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
            $("#ajax-main-cart").html(result);
            setTimeout(function() {
                 $("#ajax-main-cart").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setTimeout(function() {
                 $("#ajax-main-cart").LoadingOverlay("hide");
            }, 1000);
        }
    });
}
function submit_product_review() {

   if ($("#frm_product_review").valid()) {

        $("#btn_product_review").html('Submit Review <i class="fa fa-spinner fa-spin"></i>');

        var form = document.getElementById('frm_product_review');
        var formData = new FormData(form);

        $.ajax({
            url: baseDir + 'home/submit_product_review',
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
                $("#btn_product_review").html('Submit Review');
                if (res.status == 'ERR') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 2500 }).show();
                } else if (res.status == 'SUCCESS') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'success', modal: true, timeout: 2500 }).show();
                    $('#frm_product_review').each(function() {
                        this.reset();
                    });
                }
            },
            error: function(xhr, status, error) {
                new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 2500 }).show();
                $("#btn_product_review").html('Submit Review');
            }
        });
   
   } 

   return false;
}

function filter_shop_products() {

    $("#btn_filter_shop_products").html('<i class="icon-funnel"></i> Filter Products <i class="fa fa-spinner fa-spin"></i>');


    var form = document.getElementById('frm_filter_shop');
    var formData = new FormData(form);

    var sort_by = $('#filter_shop_sort_by').val();
    formData.append('sort_by', sort_by);

     $("#div_products").LoadingOverlay("show", {
         background: "rgba(255, 255, 255, 0)",
         imageColor: "#666",
         imageResizeFactor: 0.3
     });
     $.ajax({
         url: baseDir + 'home/loadjs_filter_products',
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
         success: function(result) {
             $("#div_products").html(result.products);
             $("#shop_products_count").html( numberWithCommas(result.products_count));

             setTimeout(function() {
                 $("#div_products").LoadingOverlay("hide");
             }, 1000);
             $("#btn_filter_shop_products").html('<i class="icon-funnel"></i> Filter Products');
         },
         error: function(xhr, status, error) {
             setTimeout(function() {
                 $("#div_products").LoadingOverlay("hide");
             }, 1000);
             $("#btn_filter_shop_products").html('<i class="icon-funnel"></i> Filter Products');
         }
    });

     return false;
}

function submit_search(search_element_id) {

    //console.log(search_element_id);

    var search_keyword =  $("#" + search_element_id).val();

    $.redirect(
        baseDir+"home/search",
        { search_keyword: search_keyword }
        , "POST"
    );

    return false;
}

function submit_checkout_login() {
   if ($("#frm_checkout_login").valid()) {

        $("#btn_checkout_login").html('<i class="icon-unlock"></i> Login <i class="fa fa-spinner fa-spin"></i>');

        var form = document.getElementById('frm_checkout_login');
        var formData = new FormData(form);

        $.ajax({
            url: baseDir + 'checkout/submit_login',
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
                    new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 4000 }).show();
                    $("#btn_checkout_login").html('<i class="icon-unlock"></i> Login');
                } else if (res.status == 'SUCCESS') {
                    setTimeout(function() {
                        window.location = baseDir + 'checkout';
                    }, 3000);
                }
            },
            error: function(xhr, status, error) {
                new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 4000 }).show();
                $("#btn_checkout_login").html('<i class="icon-unlock"></i> Login');
            }
        });
   } 

   return false;
}

function submit_checkout_register() {
   if ($("#frm_checkout_register").valid()) {

        $("#btn_checkout_register").html('<i class="icon-user-plus"></i> Create Account <i class="fa fa-spinner fa-spin"></i>');

        var form = document.getElementById('frm_checkout_register');
        var formData = new FormData(form);

        $.ajax({
            url: baseDir + 'checkout/submit_register',
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
                    new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 4000 }).show();
                    $("#btn_checkout_register").html('<i class="icon-user-plus"></i> Create Account');
                } else if (res.status == 'SUCCESS') {
                    setTimeout(function() {
                        window.location = baseDir + 'checkout';
                    }, 3000);
                }
            },
            error: function(xhr, status, error) {
                new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 4000 }).show();
                $("#btn_checkout_register").html('<i class="icon-user-plus"></i> Create Account');
            }
        });
   } 

   return false;
}

function submit_login() {
   if ($("#frm_login").valid()) {

        $("#btn_login").html('<i class="icon-unlock"></i> Login <i class="fa fa-spinner fa-spin"></i>');

        var form = document.getElementById('frm_login');
        var formData = new FormData(form);

        $.ajax({
            url: baseDir + 'account/submit_login',
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
                    new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 4000 }).show();
                    $("#btn_login").html('<i class="icon-unlock"></i> Login');
                } else if (res.status == 'SUCCESS') {
                    setTimeout(function() {
                        window.location = baseDir + 'account';
                    }, 3000);
                }
            },
            error: function(xhr, status, error) {
                new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 4000 }).show();
                $("#btn_login").html('<i class="icon-unlock"></i> Login');
            }
        });
   } 

   return false;
}

function submit_register() {
   if ($("#frm_register").valid()) {

        $("#btn_register").html('<i class="icon-user-plus"></i> Create Account <i class="fa fa-spinner fa-spin"></i>');

        var form = document.getElementById('frm_register');
        var formData = new FormData(form);

        $.ajax({
            url: baseDir + 'account/submit_register',
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
                    new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 4000 }).show();
                    $("#btn_register").html('<i class="icon-user-plus"></i> Create Account');
                } else if (res.status == 'SUCCESS') {
                    setTimeout(function() {
                        window.location = baseDir + 'account';
                    }, 3000);
                }
            },
            error: function(xhr, status, error) {
                new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 4000 }).show();
                $("#btn_register").html('<i class="icon-user-plus"></i> Create Account');
            }
        });
   } 

   return false;
}

function submit_reset_password() {
   if ($("#frm_reset_password").valid()) {

        $("#btn_reset_password").html('Reset Password <i class="fa fa-spinner fa-spin"></i>');

        var form = document.getElementById('frm_reset_password');
        var formData = new FormData(form);

        $.ajax({
            url: baseDir + 'account/submit_reset_password',
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
                $("#btn_reset_password").html('Reset Password');
                if (res.status == 'ERR') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 4000 }).show();
                } else if (res.status == 'SUCCESS') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'success', modal: true, timeout: 4000 }).show();

                }
            },
            error: function(xhr, status, error) {
                new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 4000 }).show();
                $("#btn_reset_password").html('Reset Password');
            }
        });
   } 

   return false;
}

function submit_checkout_address() {
   if ($("#frm_checkout_address").valid()) {


        swal({
            html: 'Do you wish to save this Order and proceed to payment? <br>Please note that you will not be able to make any more changes to the order after saving.',
            type: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                $("#btn_checkout_address").html('Save Order &amp; Proceed to Pay <i class="fa fa-spinner fa-spin"></i>');

                var form = document.getElementById('frm_checkout_address');
                var formData = new FormData(form);

                $.ajax({
                    url: baseDir + 'checkout/submit_checkout_address',
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
                            new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 4000 }).show();
                            $("#btn_checkout_address").html('Save Order &amp; Proceed to Pay <i class="icon-chevron-right"></i>');
                        } else if (res.status == 'SUCCESS') {
                            setTimeout(function() {
                                window.location = baseDir + res.redir;
                            }, 1000);
                        }
                    },
                    error: function(xhr, status, error) {
                        new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 4000 }).show();
                        $("#btn_checkout_address").html('Save Order &amp; Proceed to Pay <i class="icon-chevron-right"></i>');
                    }
                });
            } else {}
        });
   } 

   return false;
}

function submit_checkout_shipping() {

    if ($("#frm_checkout_shipping").valid()) {
        $("#btn_checkout_shipping").html('Proceed to Next Step <i class="fa fa-spinner fa-spin"></i>');

        var form = document.getElementById('frm_checkout_shipping');
        var formData = new FormData(form);

        $.ajax({
            url: baseDir + 'checkout/submit_checkout_shipping',
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
                    new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 4000 }).show();
                    $("#btn_checkout_shipping").html('Proceed to Next Step <i class="icon-chevron-right"></i>');
                    if (res.redir != '') {
                        setTimeout(function() {
                            window.location = baseDir + res.redir;
                        }, 3500);
                    }
                } else if (res.status == 'SUCCESS') {
                    setTimeout(function() {
                        window.location = baseDir + res.redir;
                    }, 1000);
                }
            },
            error: function(xhr, status, error) {
                new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 4000 }).show();
                $("#btn_checkout_shipping").html('Proceed to Next Step <i class="icon-chevron-right"></i>');
            }
        });
    }
    return false;
}

function send_publish_mpesa_request(){

    if ($("#frm_send_publish_mpesa_request").valid()){

        $("#btn_send_publish_mpesa_request").html('Processing <i class="fa fa-spinner fa-spin ml-5"></i>');
                        
        var form = document.getElementById('frm_send_publish_mpesa_request');
        var formData = new FormData(form);

        $.ajax({
            url: baseDir+'checkout/publish_stk_request',
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
                $("#btn_send_publish_mpesa_request").html('Send Request to Phone <em class="fa fa-chevron-circle-right"></em>');
                if(res.errorMessage != null && res.errorMessage != ''){
                    new Noty({ theme: 'limitless', text: res.errorMessage, type: 'error', modal: true, timeout: 4000 }).show();
                }else{
                    new Noty({ theme: 'limitless', text: res.ResponseDescription + '<br>Please check your phone', type: 'success', modal: true, timeout: 4000 }).show();
                }
            },
            error: function(){
                $("#btn_send_publish_mpesa_request").html('Send Request to Phone <em class="fa fa-chevron-circle-right"></em>');
                new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 4000 }).show();
            }
        });

    }
    return false;   
}

function submit_checkout_payment() {
    $("#btn_checkout_payment").html('Proceed to Next Step <i class="fa fa-spinner fa-spin"></i>');

    var form = document.getElementById('frm_checkout_payment');
    var formData = new FormData(form);

    $.ajax({
        url: baseDir + 'checkout/submit_checkout_payment',
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
                new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 4000 }).show();
                $("#btn_checkout_payment").html('Complete Order <i class="icon-chevron-right"></i>');
                if (res.redir != '') {
                    setTimeout(function() {
                        window.location = baseDir + res.redir;
                    }, 3500);
                }
            } else if (res.status == 'SUCCESS') {
                new Noty({ theme: 'limitless', text: res.message, type: 'success', modal: true, timeout: 5000 }).show();
                setTimeout(function() {
                    window.location = baseDir + res.redir;
                }, 4000);
            }
        },
        error: function(xhr, status, error) {
            new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 4000 }).show();
            $("#btn_checkout_payment").html('Complete Order <i class="icon-chevron-right"></i>');
        }
    });
    
    return false;    
}

function load_compare_products(){
     $("#div_compare_products").LoadingOverlay("show", {
         background: "rgba(255, 255, 255, 0)",
         imageColor: "#666",
         imageResizeFactor: 0.3
     });
    $.ajax({
        url: baseDir + 'home/loadjs_compare_products',
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
            $("#div_compare_products").html(result);
            setTimeout(function() {
                 $("#div_compare_products").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setTimeout(function() {
                 $("#div_compare_products").LoadingOverlay("hide");
            }, 1000);
        }
    });

}

function load_favorite_products(){
     $("#div_favorite_products").LoadingOverlay("show", {
         background: "rgba(255, 255, 255, 0)",
         imageColor: "#666",
         imageResizeFactor: 0.3
     });
    $.ajax({
        url: baseDir + 'account/loadjs_favorite_products',
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
            $("#div_favorite_products").html(result);
            setTimeout(function() {
                 $("#div_favorite_products").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setTimeout(function() {
                 $("#div_favorite_products").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function submit_update_account() {
   if ($("#frm_edit_account").valid()) {

        $("#btn_edit_account").html('<i class="icon-check"></i> UPDATE ACCOUNT <i class="fa fa-spinner fa-spin"></i>');

        var form = document.getElementById('frm_edit_account');
        var formData = new FormData(form);

        $.ajax({
            url: baseDir + 'account/update_account',
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
                $("#btn_edit_account").html('<i class="icon-check"></i> UPDATE ACCOUNT');
                if (res.status == 'ERR') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 3500 }).show();
                } else if (res.status == 'SUCCESS') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'success', modal: true, timeout: 3500 }).show();
                }
            },
            error: function(xhr, status, error) {
                new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 3500 }).show();
                $("#btn_edit_account").html('<i class="icon-check"></i> UPDATE ACCOUNT');
            }
        });
   
   } 

   return false;

}

function submit_change_password() {
   if ($("#frm_change_password").valid()) {

        $("#btn_change_password").html('<i class="icon-check"></i> CHANGE PASSWORD <i class="fa fa-spinner fa-spin"></i>');

        var form = document.getElementById('frm_change_password');
        var formData = new FormData(form);

        $.ajax({
            url: baseDir + 'account/change_password',
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
                $("#btn_change_password").html('<i class="icon-check"></i> CHANGE PASSWORD');
                if (res.status == 'ERR') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 3500 }).show();
                } else if (res.status == 'SUCCESS') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'success', modal: true, timeout: 4500 }).show();

                    $('#frm_change_password').each(function() {
                        this.reset();
                    });
                }
            },
            error: function(xhr, status, error) {
                new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 3500 }).show();
                $("#btn_change_password").html('<i class="icon-check"></i> CHANGE PASSWORD');
            }
        });
   
   } 

   return false;

}

function submit_update_address() {
   if ($("#frm_edit_address").valid()) {

        $("#btn_edit_address").html('<i class="icon-check"></i> UPDATE ADDRESS <i class="fa fa-spinner fa-spin"></i>');

        var form = document.getElementById('frm_edit_address');
        var formData = new FormData(form);

        $.ajax({
            url: baseDir + 'account/update_address',
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
                $("#btn_edit_address").html('<i class="icon-check"></i> UPDATE ADDRESS');
                if (res.status == 'ERR') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 3500 }).show();
                } else if (res.status == 'SUCCESS') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'success', modal: true, timeout: 3500 }).show();
                }
            },
            error: function(xhr, status, error) {
                new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 3500 }).show();
                $("#btn_edit_address").html('<i class="icon-check"></i> UPDATE ADDRESS');
            }
        });   
   }
   return false;
}

function cancel_order(order_number, cntxt) {
    swal({
        html: 'Do you wish to cancel this Order?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'account/cancel_order/' + order_number,
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
                            if (cntxt == 'Account') {
                                load_recent_orders();
                            } else if (cntxt == 'Orders') {
                                load_orders();
                            } else if (cntxt == 'Order') {
                                setTimeout(function() {
                                     location.reload();
                                }, 3000);
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

function restore_order(order_number, cntxt) {
    swal({
        html: 'Do you wish to restore this Order?',
        type: 'question',
        showCancelButton: true,
        confirmButtonText: 'Yes',
        cancelButtonText: 'No',
        allowOutsideClick: false
    }).then(function(result) {
        if (result.value) {
            $.ajax({
                url: baseDir + 'account/restore_order/' + order_number,
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
                            if (cntxt == 'Account') {
                                load_recent_orders();
                            } else if (cntxt == 'Orders') {
                                load_orders();
                            } else if (cntxt == 'Order') {
                                setTimeout(function() {
                                     location.reload();
                                }, 3000);
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

function load_orders(){
     $("#div_orders").LoadingOverlay("show", {
         background: "rgba(255, 255, 255, 0)",
         imageColor: "#666",
         imageResizeFactor: 0.3
     });
    $.ajax({
        url: baseDir + 'account/loadjs_orders',
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
            $("#div_orders").html(result);
            setTimeout(function() {
                 $("#div_orders").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setTimeout(function() {
                 $("#div_orders").LoadingOverlay("hide");
            }, 1000);
        }
    });

}

function load_recent_orders(){
     $("#div_recent_orders").LoadingOverlay("show", {
         background: "rgba(255, 255, 255, 0)",
         imageColor: "#666",
         imageResizeFactor: 0.3
     });
    $.ajax({
        url: baseDir + 'account/loadjs_recent_orders',
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
            $("#div_recent_orders").html(result);
            setTimeout(function() {
                 $("#div_recent_orders").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setTimeout(function() {
                 $("#div_recent_orders").LoadingOverlay("hide");
            }, 1000);
        }
    });

}

function submit_contact() {
   if ($("#frm_contact").valid()) {

        $("#btn_contact").html('Send Message <i class="fa fa-spinner fa-spin"></i>');

        var form = document.getElementById('frm_contact');
        var formData = new FormData(form);

        $.ajax({
            url: baseDir + 'home/submit_contact',
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
                $("#btn_contact").html('Send Message');
                if (res.status == 'ERR') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 3500 }).show();
                } else if (res.status == 'SUCCESS') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'success', modal: true, timeout: 3500 }).show();
                    $('#frm_contact').each(function() {
                        this.reset();
                    });
                }
            },
            error: function(xhr, status, error) {
                new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 3500 }).show();
                $("#btn_contact").html('Send Message');
            }
        });   
   }
   return false;
}


function submit_affiliate_register() {
   if ($("#frm_affiliate_register").valid()) {

        $("#btn_affiliate_register").html('Register <i class="fa fa-spinner fa-spin"></i>');

        var form = document.getElementById('frm_affiliate_register');
        var formData = new FormData(form);

        $.ajax({
            url: baseDir + 'affiliates/submit_register',
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
                $("#btn_affiliate_register").html('Register');
                if (res.status == 'ERR') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 4000 }).show();
                } else if (res.status == 'SUCCESS') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'success', modal: true, timeout: 6000 }).show();
                    $('#frm_affiliate_register').each(function() {
                        this.reset();
                    });
                }
            },
            error: function(xhr, status, error) {
                new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 4000 }).show();
                $("#btn_affiliate_register").html('Register');
            }
        });
   } 

   return false;
}

function submit_affiliate_login() {
   if ($("#frm_affiliate_login").valid()) {

        $("#btn_affiliate_login").html('<i class="icon-unlock"></i> Login <i class="fa fa-spinner fa-spin"></i>');

        var form = document.getElementById('frm_affiliate_login');
        var formData = new FormData(form);

        $.ajax({
            url: baseDir + 'affiliates/submit_login',
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
                    new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 4000 }).show();
                    $("#btn_affiliate_login").html('<i class="icon-unlock"></i> Login');
                } else if (res.status == 'SUCCESS') {
                    setTimeout(function() {
                        window.location = baseDir + 'affiliates/account';
                    }, 3000);
                }
            },
            error: function(xhr, status, error) {
                new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 4000 }).show();
                $("#btn_affiliate_login").html('<i class="icon-unlock"></i> Login');
            }
        });
   } 

   return false;
}

function load_checkout_cart() {
     $("#div_checkout_cart").LoadingOverlay("show", {
         background: "rgba(255, 255, 255, 0)",
         imageColor: "#666",
         imageResizeFactor: 0.3
     });
    $.ajax({
        url: baseDir + 'checkout/loadjs_checkout_cart',
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
            $("#div_checkout_cart").html(result);
            setTimeout(function() {
                 $("#div_checkout_cart").LoadingOverlay("hide");
            }, 1000);
        },
        error: function() {
            setTimeout(function() {
                 $("#div_checkout_cart").LoadingOverlay("hide");
            }, 1000);
        }
    });
}

function load_checkout_pickup_locations() {
    var shipping_region_id = 0;

    var sae = $('#sae').val();
    if (sae == '1') {
        shipping_region_id = $('#shipping_region_id').val();
    } else if (sae == '2') {
        if($('#chk_different_shipping_address').is(':checked')){
            shipping_region_id = $('#shipping_region_id').val();
        } else {
            shipping_region_id = $('#cur_shipping_region_id').val();
        }  
    }
    if (shipping_region_id == '' || shipping_region_id == null){ shipping_region_id = 0; }

    $("#shipping_pickup_location_loader").fadeIn("fast");
    $("#shipping_pickup_location_id")
        .find('option')
        .remove()
        .end()
        .append('<option value="">Select Pickup Station</option>')
        .val('').change()
    ;

    //url: baseDir+'be/settings/get_pickup_locations_by_region/'+shipping_region_id,
    if (this.value != ''){
        $.ajax({
            url: baseDir+'be/settings/get_pickup_locations',
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
                        $("#shipping_pickup_location_id").append($("<option>").attr('value',obj[i]['pickup_location_id']).text(obj[i]['pickup_location_name'] + ' ~ ' + obj[i]['pickup_location_address']));
                    };  
                    $("#shipping_pickup_location_loader").fadeOut("fast");
                }catch(err){
                    $("#shipping_pickup_location_loader").fadeOut("fast");
                }
            },
            error: function(){
                $("#shipping_pickup_location_loader").fadeOut("fast");
            }
        });
    }else{
        $("#shipping_pickup_location_loader").fadeOut("fast");
    }

}

function load_checkout_shipping_zones() {
    var shipping_region_id = 0;

    var sae = $('#sae').val();
    if (sae == '1') {
        shipping_region_id = $('#shipping_region_id').val();
    } else if (sae == '2') {
        if($('#chk_different_shipping_address').is(':checked')){
            shipping_region_id = $('#shipping_region_id').val();
        } else {
            shipping_region_id = $('#cur_shipping_region_id').val();
        }  
    }
    if (shipping_region_id == '' || shipping_region_id == null){ shipping_region_id = 0; }

    $("#shipping_shipping_zone_loader").fadeIn("fast");
    $("#shipping_shipping_zone_id")
        .find('option')
        .remove()
        .end()
        .append('<option value="">Select Shipping Zone</option>')
        .val('').change()
    ;
    // baseDir+'be/settings/get_shipping_zones_by_region/'+shipping_region_id,
    if (this.value != ''){
        $.ajax({
            url: baseDir+'be/settings/get_shipping_zones',
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
                        $("#shipping_shipping_zone_id").append($("<option>").attr('value',obj[i]['shipping_zone_id']).text(obj[i]['shipping_zone_name']));
                    };  
                    $("#shipping_shipping_zone_loader").fadeOut("fast");
                }catch(err){
                    $("#shipping_shipping_zone_loader").fadeOut("fast");
                }
            },
            error: function(){
                $("#shipping_shipping_zone_loader").fadeOut("fast");
            }
        });
    }else{
        $("#shipping_shipping_zone_loader").fadeOut("fast");
    }
}

function checkout_update_shipping_fee() {
    
    var form = document.getElementById('frm_checkout_address');
    var formData = new FormData(form);

    $.ajax({
        url: baseDir + 'checkout/checkout_update_shipping_fee',
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
            load_checkout_cart();
        },
        error: function(xhr, status, error) {
            //new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 4000 }).show();
            //$("#btn_affiliate_login").html('<i class="icon-unlock"></i> Login');
        }
    }); 
}

function submit_affiliate_change_password() {
   if ($("#frm_affiliate_change_password").valid()) {

        $("#btn_affiliate_change_password").html('<i class="icon-check"></i> CHANGE PASSWORD <i class="fa fa-spinner fa-spin"></i>');

        var form = document.getElementById('frm_affiliate_change_password');
        var formData = new FormData(form);

        $.ajax({
            url: baseDir + 'affiliates/submit_change_password',
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
                $("#btn_affiliate_change_password").html('<i class="icon-check"></i> CHANGE PASSWORD');
                if (res.status == 'ERR') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 3500 }).show();
                } else if (res.status == 'SUCCESS') {
                    new Noty({ theme: 'limitless', text: res.message, type: 'success', modal: true, timeout: 4500 }).show();

                    $('#frm_affiliate_change_password').each(function() {
                        this.reset();
                    });
                }
            },
            error: function(xhr, status, error) {
                new Noty({ theme: 'limitless', text: xhr.responseText, type: 'error', modal: true, timeout: 3500 }).show();
                $("#btn_affiliate_change_password").html('<i class="icon-check"></i> CHANGE PASSWORD');
            }
        });
   
   } 

   return false;

}

