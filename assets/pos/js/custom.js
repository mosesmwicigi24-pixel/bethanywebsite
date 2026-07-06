$(document).ready(function() {

    //$.fn.editable.defaults.mode = 'inline';

    //$('#discount_all_percent').editable();
    $('.select2, .unit-select').select2({
        placeholder: "Enter at least 1 character",
        allowClear: true
    });

    $('.datepicker').datepicker({ 
        format: 'yyyy-mm-dd',
        autoclose: true,
        todayHighlight: true
    });    

    $(document).on('keyup', '#txt_sale_product_search', function(){
        var txtsearch = $(this);
        if (txtsearch.val() != '' && txtsearch.val() != null) {
            $.ajax({
                type: 'POST',
                url: baseDir + 'pos/sales/sale_product_search',
                data: { search_keyword: txtsearch.val(), context: 'Sale' },
                beforeSend: function(){
                    txtsearch.css("background","#FFF url(" + baseDir + "assets/fe/img/loader9.gif) no-repeat right 10px");
                },
                success: function(data){
                    $('#sale_products_suggestion').show();
                    $('#sale_products_suggestion').html(data);
                    setTimeout(function() {
                         txtsearch.css("background","#FFF");
                     }, 2000);
                }
            });
        }else{
            $('#sale_products_suggestion').hide();
            setTimeout(function() {
                 txtsearch.css("background","#FFF");
             }, 2000);
        }        
    });

    $(document).on('keyup', '#txt_sales_return_product_search', function(){
        var txtsearch = $(this);
        if (txtsearch.val() != '' && txtsearch.val() != null) {
            $.ajax({
                type: 'POST',
                url: baseDir + 'pos/sales/sale_product_search',
                data: { search_keyword: txtsearch.val(), context: 'Sales Return' },
                beforeSend: function(){
                    txtsearch.css("background","#FFF url(" + baseDir + "assets/fe/img/loader9.gif) no-repeat right 10px");
                },
                success: function(data){
                    $('#sales_return_products_suggestion').show();
                    $('#sales_return_products_suggestion').html(data);
                    setTimeout(function() {
                         txtsearch.css("background","#FFF");
                     }, 2000);
                }
            });
        }else{
            $('#sales_return_products_suggestion').hide();
            setTimeout(function() {
                 txtsearch.css("background","#FFF");
             }, 2000);
        }        
    });

    $(document).on('keyup', '#txt_sale_customer_search', function(){
    //$("#txt_sale_customer_search").keyup(function(){
        var txtsearch = $(this);
        if (txtsearch.val() != '' && txtsearch.val() != null) {
            $.ajax({
                type: 'POST',
                url: baseDir + 'pos/sales/sale_customer_search',
                data: { search_keyword: txtsearch.val(), context: 'Sale' },
                beforeSend: function(){
                    txtsearch.css("background","#FFF url(" + baseDir + "assets/fe/img/loader9.gif) no-repeat right 10px");
                },
                success: function(data){
                    $('#sale_customers_suggestion').show();
                    $('#sale_customers_suggestion').html(data);
                    setTimeout(function() {
                         txtsearch.css("background","#FFF");
                     }, 2000);
                }
            });
        }else{
            $('#sale_customers_suggestion').hide();
            setTimeout(function() {
                 txtsearch.css("background","#FFF");
             }, 2000);
        }        
    });

    $(document).on('keyup', '#txt_sales_return_customer_search', function(){
    //$("#txt_sale_customer_search").keyup(function(){
        var txtsearch = $(this);
        if (txtsearch.val() != '' && txtsearch.val() != null) {
            $.ajax({
                type: 'POST',
                url: baseDir + 'pos/sales/sale_customer_search',
                data: { search_keyword: txtsearch.val(), context: 'Sales Return' },
                beforeSend: function(){
                    txtsearch.css("background","#FFF url(" + baseDir + "assets/fe/img/loader9.gif) no-repeat right 10px");
                },
                success: function(data){
                    $('#sales_return_customers_suggestion').show();
                    $('#sales_return_customers_suggestion').html(data);
                    setTimeout(function() {
                         txtsearch.css("background","#FFF");
                     }, 2000);
                }
            });
        }else{
            $('#sales_return_customers_suggestion').hide();
            setTimeout(function() {
                 txtsearch.css("background","#FFF");
             }, 2000);
        }        
    });

    $("#sale_product_category_id").on('change', function() {
        load_sale_products();
    });

    $(document).on('click', '#btn_export_report', function(e){
        // console.log('clicked');
        $("#tbl_report").tableExport({
            headers: true,                      // (Boolean), display table headers (th or td elements) in the <thead>, (default: true)
            footers: true,                      // (Boolean), display table footers (th or td elements) in the <tfoot>, (default: false)
            formats: ["xls", "xlsx", "csv", "txt"],    // (String[]), filetype(s) for the export, (default: ['xlsx', 'csv', 'txt'])
            bootstrap: true,                   // (Boolean), style buttons using bootstrap, (default: true)
            exportButtons: true,                // (Boolean), automatically generate the built-in export buttons for each of the specified formats (default: true)
            position: "top",                 // (top, bottom), position of the caption element relative to table, (default: 'bottom')
            ignoreRows: null,                   // (Number, Number[]), row indices to exclude from the exported file(s) (default: null)
            ignoreCols: null,                   // (Number, Number[]), column indices to exclude from the exported file(s) (default: null)
            trimWhitespace: true,               // (Boolean), remove all leading/trailing newlines, spaces, and tabs from cell text in the exported file(s) (default: false)
        });
    });

    // $("#sale_payment_method").on('change', function() {
    //     var payment_method = $(this).val();
    //     if (payment_method == 'MPESA') {
    //         $("#btn_sale_payment_mpesa_instructions").fadeIn("fast");
    //         $("#lbl_payment_reference_number").text("Phone Number");
    //         $("#sale_payment_reference_number").attr("placeholder", "e.g. 254700123456");
    //     } else {
    //         $("#btn_sale_payment_mpesa_instructions").fadeOut("fast");
    //         $("#lbl_payment_reference_number").text("Reference #");
    //         $("#sale_payment_reference_number").attr("placeholder", "");
    //     }
    // });

    $("#sale_payment_method").on('change', function() {
        var referenceNumberRule = {
            sale_payment_reference_number: {
                required: true
            }
        };
        var payment_method = $(this).val();
        if (payment_method == 'MPESA') {
            $("#div_sale_payment_mpesa_btns").fadeIn("fast");
            addRules(referenceNumberRule);
            // $("#lbl_payment_reference_number").text("Phone Number");
            // $("#sale_payment_reference_number").attr("placeholder", "e.g. 254700123456");
        } else {
            $("#div_sale_payment_mpesa_btns").fadeOut("fast");
            removeRules(referenceNumberRule);
            // $("#lbl_payment_reference_number").text("Reference #");
            // $("#sale_payment_reference_number").attr("placeholder", "");
        }
    });

    // $("#modify_sale_payment_method").on('change', function() {
    //     var payment_method = $(this).val();
    //     if (payment_method == 'MPESA') {
    //         $("#btn_modify_sale_payment_mpesa_instructions").fadeIn("fast");
    //         $("#lbl_modify_payment_reference_number").text("Phone Number");
    //         $("#modify_sale_payment_reference_number").attr("placeholder", "e.g. 254700123456");
    //     } else {
    //         $("#btn_modify_sale_payment_mpesa_instructions").fadeOut("fast");
    //         $("#lbl_modify_payment_reference_number").text("Reference #");
    //         $("#modify_sale_payment_reference_number").attr("placeholder", "");
    //     }
    // });

    $("#modify_sale_payment_method").on('change', function() {
        var referenceNumberRule = {
            modify_sale_payment_reference_number: {
                required: true
            }
        };
        var payment_method = $(this).val();
        if (payment_method == 'MPESA') {
            $("#div_modify_sale_payment_mpesa_btns").fadeIn("fast");
            addRules(referenceNumberRule);
            // $("#lbl_modify_payment_reference_number").text("Phone Number");
            // $("#modify_sale_payment_reference_number").attr("placeholder", "e.g. 254700123456");
        } else {
            $("#div_modify_sale_payment_mpesa_btns").fadeOut("fast");
            removeRules(referenceNumberRule);
            // $("#lbl_modify_payment_reference_number").text("Reference #");
            // $("#modify_sale_payment_reference_number").attr("placeholder", "");
        }
    });

    $("#ssove_email_account_id").on('change', function() {
        var email_account_id = $(this).val();

        if (email_account_id != '') {
            $.ajax({
                type: 'POST',
                url: baseDir + 'pos/sales/get_email_account/' + email_account_id,
                data:'',
                dataType: 'json',
                beforeSend: function(){
                    $("#send_sale_order_via_email_loader").fadeIn("fast");
                },
                success: function(res){
                    $("#send_sale_order_via_email_loader").fadeOut("fast");

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
                    $("#send_sale_order_via_email_loader").fadeOut("fast");
                }
            }); 
        }
    });

    $(document).on('click', '#btn_export_profit_loss_report', function(){
        convert_excel('xlsx','Profit & Loss Report','tbl_profit_loss_report');
    });

    $(document).on('click', '.lnk_send_sale_order_via_email', function(){
        
        var pos_sale_id = $(this).attr("data-pos-sale-id");
        $("#send_email_pos_sale_id").val(pos_sale_id);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/get_pos_sale/' + pos_sale_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#send_sale_order_via_email_loader").fadeIn("fast");
            },
            success: function(res){
                $("#send_sale_order_via_email_loader").fadeOut("fast");

                $.each(res, function(index, element) {
                    $("#recipient_email_address").val(element.email_address);
                    $("#email_subject").val('Bethany House SALES ORDER [' + element.pos_sale_number + ']');
                });
            },
            error: function(){
                $("#send_sale_order_via_email_loader").fadeOut("fast");
            }
        });
    });

    $(document).on('click', '.sale_product_search_option', function(){

        var transaction_type = $('#transaction_type').val();
        var product_id = $(this).attr("data-product-id");
        var product_type = $(this).attr("data-product-type");
        var context = $(this).attr("data-context");

        if (context == 'Sale'){
            $('#sale_products_suggestion').hide();
            $("#txt_sale_product_search").val("");
            if (transaction_type == 'Add') {
                if (product_type == 'Simple'){
                    $.ajax({
                        type: 'POST',
                        url: baseDir + 'pos/sales/sale_add_product',
                        data: { product_id: product_id, product_variation_id: 0 },
                        dataType: 'json',
                        beforeSend: function(){
                            $("#sale_products_loader").fadeIn("fast");
                            $("#details_section_loader").fadeIn("fast");
                            $("#totals_section_loader").fadeIn("fast");
                        },
                        success: function(res){
                            $("#sale_products_loader").fadeOut("fast");
                            $("#details_section_loader").fadeOut("fast");
                            $("#totals_section_loader").fadeOut("fast");
                            if (res.status == 'ERR') {
                                new NioApp.Toast("<h5>Error</h5><p>" + res.data + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                            } else {
                                load_pending_sale_info();
                                load_sale_products();
                            }
                        },
                        error: function(){
                            $("#sale_products_loader").fadeOut("fast");
                            $("#details_section_loader").fadeOut("fast");
                            $("#totals_section_loader").fadeOut("fast");
                        }
                    });
                } else {
                    $.ajax({
                        type: 'POST',
                        url: baseDir + 'pos/sales/loadjs_select_product_variations',
                        data: { product_id: product_id, context: 'New Sale', transaction_context: 'Sale' },
                        beforeSend: function(){
                            $("#sale_products_loader").fadeIn("fast");
                            $("#details_section_loader").fadeIn("fast");
                            $("#totals_section_loader").fadeIn("fast");
                        },
                        success: function(res){
                            $("#sale_products_loader").fadeOut("fast");
                            $("#details_section_loader").fadeOut("fast");
                            $("#totals_section_loader").fadeOut("fast");

                            $('#div_select_product_variation').html(res);
                            $('#modal_select_product_variation').modal('toggle');
                        },
                        error: function(){
                            $("#sale_products_loader").fadeOut("fast");
                            $("#details_section_loader").fadeOut("fast");
                            $("#totals_section_loader").fadeOut("fast");
                        }
                    });
                }
            } else if (transaction_type == 'Edit') {
                var pos_sale_id = $('#pos_sale_id').val();

                if (product_type == 'Simple'){
                    $.ajax({
                        type: 'POST',
                        url: baseDir + 'pos/sales/edit_sale_add_product',
                        data: {product_id: product_id, product_variation_id: 0, pos_sale_id: pos_sale_id },
                        dataType: 'json',
                        beforeSend: function(){
                            $("#sale_products_loader").fadeIn("fast");
                            $("#details_section_loader").fadeIn("fast");
                            $("#totals_section_loader").fadeIn("fast");
                        },
                        success: function(res){
                            $("#sale_products_loader").fadeOut("fast");
                            $("#details_section_loader").fadeOut("fast");
                            $("#totals_section_loader").fadeOut("fast");
                            if (res.status == 'ERR') {
                                new NioApp.Toast("<h5>Error</h5><p>" + res.data + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                            } else {
                                load_edit_sale_info(pos_sale_id);
                                load_sale_products();
                            }
                        },
                        error: function(){
                            $("#sale_products_loader").fadeOut("fast");
                            $("#details_section_loader").fadeOut("fast");
                            $("#totals_section_loader").fadeOut("fast");
                        }
                    });
                } else {
                   $.ajax({
                        type: 'POST',
                        url: baseDir + 'pos/sales/loadjs_select_product_variations',
                        data: { product_id: product_id, transaction_id: pos_sale_id, context: 'Edit Sale', transaction_context: 'Sale' },
                        beforeSend: function(){
                            $("#sale_products_loader").fadeIn("fast");
                            $("#details_section_loader").fadeIn("fast");
                            $("#totals_section_loader").fadeIn("fast");
                        },
                        success: function(res){
                            $("#sale_products_loader").fadeOut("fast");
                            $("#details_section_loader").fadeOut("fast");
                            $("#totals_section_loader").fadeOut("fast");

                            $('#div_select_product_variation').html(res);
                            $('#modal_select_product_variation').modal('toggle');
                        },
                        error: function(){
                            $("#sale_products_loader").fadeOut("fast");
                            $("#details_section_loader").fadeOut("fast");
                            $("#totals_section_loader").fadeOut("fast");
                        }
                    }); 
                }
            } 
        } else if (context == 'Sales Return') {
            $('#sales_return_products_suggestion').hide();
            $("#txt_sales_return_product_search").val("");

            if (transaction_type == 'Add') {
                if (product_type == 'Simple'){
                    $.ajax({
                        type: 'POST',
                        url: baseDir + 'pos/sales/sales_return_add_product',
                        data: { product_id: product_id, product_variation_id: 0 },
                        dataType: 'json',
                        beforeSend: function(){
                            $("#sales_return_products_loader").fadeIn("fast");
                            $("#details_section_loader").fadeIn("fast");
                            $("#totals_section_loader").fadeIn("fast");
                        },
                        success: function(res){
                            $("#sales_return_products_loader").fadeOut("fast");
                            $("#details_section_loader").fadeOut("fast");
                            $("#totals_section_loader").fadeOut("fast");
                            if (res.status == 'ERR') {
                                new NioApp.Toast("<h5>Error</h5><p>" + res.data + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                            } else {
                                load_pending_sales_return_info();
                                load_sales_return_products();
                            }
                        },
                        error: function(){
                            $("#sales_return_products_loader").fadeOut("fast");
                            $("#details_section_loader").fadeOut("fast");
                            $("#totals_section_loader").fadeOut("fast");
                        }
                    });
                } else {
                    $.ajax({
                        type: 'POST',
                        url: baseDir + 'pos/sales/loadjs_select_product_variations',
                        data: { product_id: product_id, context: 'New Sales Return', transaction_context: 'Sales Return'},
                        beforeSend: function(){
                            $("#sales_return_products_loader").fadeIn("fast");
                            $("#details_section_loader").fadeIn("fast");
                            $("#totals_section_loader").fadeIn("fast");
                        },
                        success: function(res){
                            $("#sales_return_products_loader").fadeOut("fast");
                            $("#details_section_loader").fadeOut("fast");
                            $("#totals_section_loader").fadeOut("fast");

                            $('#div_select_product_variation').html(res);
                            $('#modal_select_product_variation').modal('toggle');
                        },
                        error: function(){
                            $("#sales_return_products_loader").fadeOut("fast");
                            $("#details_section_loader").fadeOut("fast");
                            $("#totals_section_loader").fadeOut("fast");
                        }
                    });
                }
            } else if (transaction_type == 'Edit') {
                var pos_sales_return_id = $('#pos_sales_return_id').val();

                if (product_type == 'Simple'){
                    $.ajax({
                        type: 'POST',
                        url: baseDir + 'pos/sales/edit_sales_return_add_product',
                        data: {product_id: product_id, product_variation_id: 0, pos_sales_return_id: pos_sales_return_id },
                        dataType: 'json',
                        beforeSend: function(){
                            $("#sales_return_products_loader").fadeIn("fast");
                            $("#details_section_loader").fadeIn("fast");
                            $("#totals_section_loader").fadeIn("fast");
                        },
                        success: function(res){
                            $("#sales_return_products_loader").fadeOut("fast");
                            $("#details_section_loader").fadeOut("fast");
                            $("#totals_section_loader").fadeOut("fast");
                            if (res.status == 'ERR') {
                                new NioApp.Toast("<h5>Error</h5><p>" + res.data + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                            } else {
                                load_edit_sales_return_info(pos_sales_return_id);
                                load_sales_return_products();
                            }
                        },
                        error: function(){
                            $("#sales_return_products_loader").fadeOut("fast");
                            $("#details_section_loader").fadeOut("fast");
                            $("#totals_section_loader").fadeOut("fast");
                        }
                    });
                } else {
                   $.ajax({
                        type: 'POST',
                        url: baseDir + 'pos/sales/loadjs_select_product_variations',
                        data: { product_id: product_id, transaction_id: pos_sales_return_id, context: 'Edit Sales Return', transaction_context: 'Sales Return' },
                        beforeSend: function(){
                            $("#sales_return_products_loader").fadeIn("fast");
                            $("#details_section_loader").fadeIn("fast");
                            $("#totals_section_loader").fadeIn("fast");
                        },
                        success: function(res){
                            $("#sales_return_products_loader").fadeOut("fast");
                            $("#details_section_loader").fadeOut("fast");
                            $("#totals_section_loader").fadeOut("fast");

                            $('#div_select_product_variation').html(res);
                            $('#modal_select_product_variation').modal('toggle');
                        },
                        error: function(){
                            $("#sales_return_products_loader").fadeOut("fast");
                            $("#details_section_loader").fadeOut("fast");
                            $("#totals_section_loader").fadeOut("fast");
                        }
                    }); 
                }
            }

        } 

    });

    $(document).on('click', '.lnk-select-product-variation', function(e){
        e.preventDefault();

        var product_id = $(this).attr("data-product-id");
        var product_variation_id = $(this).attr("data-product-variation-id");
        var transaction_id = $(this).attr("data-transaction-id");
        var context = $(this).attr("data-context");
        var transaction_context = $(this).attr("data-transaction-context");

        if (transaction_context == 'Sale'){
            if (context == 'New Sale') {
                $.ajax({
                    type: 'POST',
                    url: baseDir + 'pos/sales/sale_add_product',
                    data: { product_id: product_id, product_variation_id: product_variation_id },
                    dataType: 'json',
                    beforeSend: function(){
                        $("#sale_products_loader").fadeIn("fast");
                        $("#details_section_loader").fadeIn("fast");
                        $("#totals_section_loader").fadeIn("fast");
                    },
                    success: function(res){
                        $("#sale_products_loader").fadeOut("fast");
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                        if (res.status == 'ERR') {
                            new NioApp.Toast("<h5>Error</h5><p>" + res.data + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                        } else {
                            load_pending_sale_info();
                            load_sale_products();
                        }
                    },
                    error: function(){
                        $("#sale_products_loader").fadeOut("fast");
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                    }
                });
                $('#modal_select_product_variation').modal('toggle');

            } else if (context == 'Edit Sale') {
                $.ajax({
                    type: 'POST',
                    url: baseDir + 'pos/sales/edit_sale_add_product',
                    data: {product_id: product_id, product_variation_id: product_variation_id, pos_sale_id: transaction_id },
                    dataType: 'json',
                    beforeSend: function(){
                        $("#sale_products_loader").fadeIn("fast");
                        $("#details_section_loader").fadeIn("fast");
                        $("#totals_section_loader").fadeIn("fast");
                    },
                    success: function(res){
                        $("#sale_products_loader").fadeOut("fast");
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                        if (res.status == 'ERR') {
                            new NioApp.Toast("<h5>Error</h5><p>" + res.data + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                        } else {
                            load_edit_sale_info(transaction_id);
                            load_sale_products();
                        }
                    },
                    error: function(){
                        $("#sale_products_loader").fadeOut("fast");
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                    }
                });
            }
        } else if (transaction_context == 'Sales Return') {
            if (context == 'New Sales Return') {
                $.ajax({
                    type: 'POST',
                    url: baseDir + 'pos/sales/sales_return_add_product',
                    data: { product_id: product_id, product_variation_id: product_variation_id },
                    dataType: 'json',
                    beforeSend: function(){
                        $("#sales_return_products_loader").fadeIn("fast");
                        $("#details_section_loader").fadeIn("fast");
                        $("#totals_section_loader").fadeIn("fast");
                    },
                    success: function(res){
                        $("#sales_return_products_loader").fadeOut("fast");
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                        if (res.status == 'ERR') {
                            new NioApp.Toast("<h5>Error</h5><p>" + res.data + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                        } else {
                            load_pending_sales_return_info();
                            load_sales_return_products();
                        }
                    },
                    error: function(){
                        $("#sales_return_products_loader").fadeOut("fast");
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                    }
                });
                $('#modal_select_product_variation').modal('toggle');

            } else if (context == 'Edit Sales Return') {
                $.ajax({
                    type: 'POST',
                    url: baseDir + 'pos/sales/edit_sales_return_add_product',
                    data: {product_id: product_id, product_variation_id: product_variation_id, pos_sales_return_id: transaction_id },
                    dataType: 'json',
                    beforeSend: function(){
                        $("#sales_return_products_loader").fadeIn("fast");
                        $("#details_section_loader").fadeIn("fast");
                        $("#totals_section_loader").fadeIn("fast");
                    },
                    success: function(res){
                        $("#sales_return_products_loader").fadeOut("fast");
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                        if (res.status == 'ERR') {
                            new NioApp.Toast("<h5>Error</h5><p>" + res.data + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                        } else {
                            load_edit_sales_return_info(transaction_id);
                            load_sales_return_products();
                        }
                    },
                    error: function(){
                        $("#sales_return_products_loader").fadeOut("fast");
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                    }
                });
            }
        } else if (transaction_context == 'Quotation') {
            if (context == 'New Quotation') {
                e.preventDefault();

                var line_id = '' + product_id + product_variation_id;
                var product_name = $('#spv_product_name').val();
                var product_sku_code = $('#spv_product_sku_code').val();
                var unit_id = $('#spv_unit_id').val();
                var product_price = $(this).attr("data-product-price");
                var variationDescription = $(this).attr("data-variation-description");
                var variation_description = variationDescription.substring(0, variationDescription.length -2);

                var product_units = new Array();
                product_units =  JSON.parse($('#spv_product_units').val());

                var product_unit_select = '<select class="form-control unit-select q_unit_id" data-placeholder="Select Unit" id="q_unit_id_' + line_id + '" name="q_unit_id[]" style="width: 100%;" tabindex="-1" aria-hidden="true" required> \
                                                <option value="">Select Unit</option>';
                $.each(product_units, function(index2, element2) {
                    var unit_price = product_price;
                    var selected = '';
                    // if (unit_id != element2.unit_id && element2.unit_price != 0) {
                    //     unit_price = element2.unit_price;
                    // }
                    if (element2.universal_prices == 0) {
                        if (unit_id != element2.unit_id && element2.outlet_unit_price != 0 && element2.outlet_unit_price != null) {
                            unit_price = element2.outlet_unit_price;
                        }
                    } else {
                        if (unit_id != element2.unit_id && element2.unit_price != 0 && element2.unit_price != null) {
                            unit_price = element2.unit_price;
                        }
                    }
                    if (unit_id == element2.unit_id) {
                        selected = 'selected';
                    }
                    product_unit_select = product_unit_select + '<option value="' + element2.unit_id + '" data-unit-price="' + unit_price + '" data-line-id="' + line_id + '" ' + selected + '>' + element2.unit_name + ' (' + element2.unit_code + ')</option>';
                });
                product_unit_select = product_unit_select + '</select>';                                

                if($('#q_detail_qty_' + line_id).length && $('#q_detail_qty_' + line_id).val().length){
                    var detailqty = parseFloat($('#q_detail_qty_' + line_id).val()) + 1;
                    $('#q_detail_qty_' + line_id).val(detailqty);
                }else{
                    $('#quotation_details_table').append('<tr> \
                        <td>' + product_name + '<br><i class="icon ni ni-dot ml-2"></i> ' + variation_description + '<br><div class="text-muted font-size-sm pt-0"><b>SKU:</b>' + product_sku_code + '</div><input id="q_detail_id_' + line_id + '" name="q_detail_id[]" type="hidden" class="q_detail_id" value="' + line_id + '"><input id="q_detail_product_id_' + line_id + '" name="q_detail_product_id[]" type="hidden" class="q_detail_product_id" value="' + product_id + '"><input id="q_detail_product_variation_id_' + line_id + '" name="q_detail_product_variation_id[]" type="hidden" class="q_detail_product_variation_id" value="' + product_variation_id + '"></td> \
                        <td>' + product_unit_select + '</td> \
                        <td><input id="q_detail_cost_' + line_id + '" name="q_detail_cost[]" type="number" class="form-control q_detail_cost" value="' + product_price + '" autocomplete="off" required></td> \
                        <td><input id="q_detail_qty_' + line_id + '" name="q_detail_qty[]" type="number" class="form-control q_detail_qty" min="1" value="1" autocomplete="off" required></td> \
                        <td><span id="q_label_detail_total_' + line_id + '">0.00</span><input id="q_detail_total_' + line_id + '" name="q_detail_total[]" type="hidden" class="form-control q_detail_total" value="0"></td> \
                        <td><a href="javascript:void(0);" class="q_detail_remove" title="Remove product"><span class="badge rounded-pill bg-transparent bg-outline-danger"><em class="icon ni ni-cross-circle"></em></span></a></td> \
                    </tr>');
                }
                $('.unit-select').select2({
                    placeholder: "Enter at least 1 character",
                    allowClear: true
                });
                $('#modal_q_select_product_variation').modal('toggle');
                calculate_q_detail_total(line_id);
                calculate_q_totals();
                // <em class="icon ni ni-cross-circle"></em>
            }
        }
    });

    $(document).on('click', '.sale_customer_search_option', function(){

        var transaction_type = $('#transaction_type').val();

        var customer_id = $(this).attr("data-customer-id");
        var context = $(this).attr("data-context");

        if (context == 'Sale'){
            $('#customer_id').val(customer_id);
            $('#sale_customers_suggestion').hide();

            if (transaction_type == 'Add') {
                $.ajax({
                    type: 'POST',
                    url: baseDir + 'pos/sales/select_customer/' + customer_id,
                    data:'',
                    dataType: 'json',
                    beforeSend: function(){
                        $("#details_section_loader").fadeIn("fast");
                        $("#totals_section_loader").fadeIn("fast");
                    },
                    success: function(res){
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                        if (res.status == 'ERR') {
                            new NioApp.Toast("<h5>Error</h5><p>" + res.data + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                        } else {
                            $("#div_sale_customer_info").html(res.data);
                        }
                    },
                    error: function(){
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                    }
                });
            }  else if (transaction_type == 'Edit') {

                var pos_sale_id = $('#pos_sale_id').val();

                $.ajax({
                    type: 'POST',
                    url: baseDir + 'pos/sales/edit_sale_select_customer/' + customer_id,
                    data: {pos_sale_id: pos_sale_id },
                    dataType: 'json',
                    beforeSend: function(){
                        $("#details_section_loader").fadeIn("fast");
                        $("#totals_section_loader").fadeIn("fast");
                    },
                    success: function(res){
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                        if (res.status == 'ERR') {
                            new NioApp.Toast("<h5>Error</h5><p>" + res.data + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                        } else {
                            $("#div_sale_customer_info").html(res.data);
                        }
                    },
                    error: function(){
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                    }
                });

            } 
        } else if (context == 'Sales Return') {
            $('#customer_id').val(customer_id);
            $('#sales_return_customers_suggestion').hide();

            if (transaction_type == 'Add') {
                $.ajax({
                    type: 'POST',
                    url: baseDir + 'pos/sales/sales_return_select_customer/' + customer_id,
                    data:'',
                    dataType: 'json',
                    beforeSend: function(){
                        $("#details_section_loader").fadeIn("fast");
                        $("#totals_section_loader").fadeIn("fast");
                    },
                    success: function(res){
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                        if (res.status == 'ERR') {
                            new NioApp.Toast("<h5>Error</h5><p>" + res.data + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                        } else {
                            $("#div_sales_return_customer_info").html(res.data);
                        }
                    },
                    error: function(){
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                    }
                });
            }  else if (transaction_type == 'Edit') {

                var pos_sales_return_id = $('#pos_sales_return_id').val();

                $.ajax({
                    type: 'POST',
                    url: baseDir + 'pos/sales/edit_sales_return_select_customer/' + customer_id,
                    data: {pos_sales_return_id: pos_sales_return_id },
                    dataType: 'json',
                    beforeSend: function(){
                        $("#details_section_loader").fadeIn("fast");
                        $("#totals_section_loader").fadeIn("fast");
                    },
                    success: function(res){
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                        if (res.status == 'ERR') {
                            new NioApp.Toast("<h5>Error</h5><p>" + res.data + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                        } else {
                            $("#div_sales_return_customer_info").html(res.data);
                        }
                    },
                    error: function(){
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                    }
                });

            } 
        }    
    });

    $(document).on('click', '.lnk_void_pos_sale', function(e){
        e.preventDefault();
        
        //var pos_payment_id = $(this).attr("data-pos-payment-id");
        var pos_sale_id = $(this).attr("data-pos-sale-id");
        var context = $(this).attr("data-context");

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/sale_void_valid/' + pos_sale_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                // $("#sale_products_loader").fadeIn("fast");
                // $("#details_section_loader").fadeIn("fast");
                // $("#totals_section_loader").fadeIn("fast");
            },
            success: function(res){
                // $("#sale_products_loader").fadeOut("fast");
                // $("#details_section_loader").fadeOut("fast");
                // $("#totals_section_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    // $.each(res.data, function(index, element) {
                    //     $("#void_pos_payment_id").val(pos_payment_id);
                    //     $("#del_delivery_fee").val(element.delivery_fee);
                    // });
                    $("#void_pos_sale_id").val(pos_sale_id);
                    $("#void_context").val(context);
                    
                    $("#pos_sale_void_reason").val('');
                    $('#modal_void_pos_sale').modal('toggle');

                }
            },
            error: function(){
                // $("#sale_products_loader").fadeOut("fast");
                // $("#details_section_loader").fadeOut("fast");
                // $("#totals_section_loader").fadeOut("fast");
            }
        });

    });


    $(document).on('click', '.lnk-modify-sale-product', function(){
        //console.log('clicked');
        var product_id = $(this).attr("data-product-id");
        var product_variation_id = $(this).attr("data-product-variation-id");
        var pos_sale_id = $(this).attr("data-pos-sale-id");
        var pos_sale_detail_id = $(this).attr("data-pos-sale-detail-id");
        $("#mod_unit_id").find('option').remove().end().append('<option value="">Select Unit of Measure</option>').val('').change();

        $("#mod_product_id").val(product_id);
        $("#mod_product_variation_id").val(product_variation_id);
        $("#mod_pos_sale_id").val(pos_sale_id);
        $("#mod_pos_sale_detail_id").val(pos_sale_detail_id);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/fetch_modify_pos_sale_details/' + pos_sale_detail_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#modify_sale_loader").fadeIn("fast");
            },
            success: function(res){
                $("#modify_sale_loader").fadeOut("fast");
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $.each(res.units, function(index, element) {
                        $("#mod_unit_id").append($("<option>").attr('value',element.unit_id).text(element.unit_name + ' (' + element.unit_code + ')'));
                    });
                    $.each(res.data, function(index, element) {
                        $("#mod_product_name").html(element.product_name);

                        var variationDescription = '';
                        $.each(element.attributes, function(index2, element2) {
                            variationDescription = variationDescription + element2.product_attribute_name + ' : <b>' + element2.product_attribute_value + '</b>, ';
                        });
                        if (variationDescription != ''){
                            variationDescription = ' <small>(' + variationDescription.substring(0, variationDescription.length -2) + ')</small>';
                        }
                        $("#mod_product_variation_description").html(variationDescription);                       

                        $("#mod_unit_id").val(element.unit_id).change();
                        $("#mod_current_unit_id").val(element.unit_id);
                        $("#mod_unit_price").val(element.unit_price);
                        $("#mod_current_unit_price").val(element.unit_price);
                        $("#mod_quantity").val(element.quantity);
                        if (element.discount_type !== '' && element.discount_type !== null) {
                            $("#mod_discount_type").val(element.discount_type).change();
                        } else {
                            $("#mod_discount_type").val('Percentage').change();   
                        }
                        $("#mod_discount_value").val(element.discount_value);
                        if (element.product_image_thumb != ''){
                            var image_path = baseDir + 'uploads/product_images/thumbs/' + element.product_image_thumb;
                            ajax_load_image('mod_product_image',image_path);
                        }
                    });
                }
            },
            error: function(){
                $("#modify_sale_loader").fadeOut("fast");
            }
        });

    });

    $(document).on('click', '.lnk-modify-sales-return-product', function(){
        //console.log('clicked');
        var product_id = $(this).attr("data-product-id");
        var product_variation_id = $(this).attr("data-product-variation-id");
        var pos_sales_return_id = $(this).attr("data-pos-sales-return-id");
        var pos_sales_return_detail_id = $(this).attr("data-pos-sales-return-detail-id");
        $("#mod_unit_id").find('option').remove().end().append('<option value="">Select Unit of Measure</option>').val('').change();

        $("#mod_product_id").val(product_id);
        $("#mod_product_variation_id").val(product_variation_id);
        $("#mod_pos_sales_return_id").val(pos_sales_return_id);
        $("#mod_pos_sales_return_detail_id").val(pos_sales_return_detail_id);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/fetch_modify_pos_sales_return_details/' + pos_sales_return_detail_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#modify_sales_return_loader").fadeIn("fast");
            },
            success: function(res){
                $("#modify_sales_return_loader").fadeOut("fast");
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $.each(res.units, function(index, element) {
                        $("#mod_unit_id").append($("<option>").attr('value',element.unit_id).text(element.unit_name + ' (' + element.unit_code + ')'));
                    });
                    $.each(res.data, function(index, element) {
                        $("#mod_product_name").html(element.product_name);

                        var variationDescription = '';
                        $.each(element.attributes, function(index2, element2) {
                            variationDescription = variationDescription + element2.product_attribute_name + ' : <b>' + element2.product_attribute_value + '</b>, ';
                        });
                        if (variationDescription != ''){
                            variationDescription = ' <small>(' + variationDescription.substring(0, variationDescription.length -2) + ')</small>';
                        }
                        $("#mod_product_variation_description").html(variationDescription);                       

                        $("#mod_unit_id").val(element.unit_id).change();
                        $("#mod_current_unit_id").val(element.unit_id);
                        $("#mod_unit_price").val(element.unit_price);
                        $("#mod_current_unit_price").val(element.unit_price);
                        $("#mod_quantity").val(element.quantity);
                        if (element.product_image_thumb != ''){
                            var image_path = baseDir + 'uploads/product_images/thumbs/' + element.product_image_thumb;
                            ajax_load_image('mod_product_image',image_path);
                        }
                    });
                }
            },
            error: function(){
                $("#modify_sales_return_loader").fadeOut("fast");
            }
        });

    });

    $(document).on('change', '#mod_unit_id', function(){
        // console.log('changed');
        var unit_id = $(this).val();
        var product_id = $("#mod_product_id").val();
        var unit_price = $("#mod_current_unit_price").val();

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/fetch_modify_product_unit_details',
            data: { unit_id: unit_id, product_id: product_id, unit_price: unit_price },
            dataType: 'json',
            beforeSend: function(){
                $("#modify_sale_loader").fadeIn("fast");
            },
            success: function(res){
                $("#modify_sale_loader").fadeOut("fast");
                $("#mod_base_unit_id").val(res.base_unit_id);
                $("#mod_conversion_factor").val(res.conversion_factor);
                // if (res.is_base_unit == true) {
                //     $('#div_mod_conversion_factor').slideUp();
                // } else {
                //     $('#div_mod_conversion_factor').slideDown();
                // } 

                //Calculate Price
                // var unitPrice = 0;
                if ($('#mod_current_unit_id').val() == $('#mod_unit_id').val()) {
                    $('#mod_unit_price').val($('#mod_current_unit_price').val());
                } else {
                    // unitPrice = parseFloat($('#mod_current_unit_price').val()) * parseFloat($('#mod_conversion_factor').val());
                    $('#mod_unit_price').val(res.unit_price);
                }
            },
            error: function(){
                $("#modify_sale_loader").fadeOut("fast");
            }
        });

    });

    $(document).on('change', '#mod_conversion_factor', function() {
        var unitPrice = 0;
        if ($('#mod_current_unit_id').val() == $('#mod_unit_id').val()) {
            $('#mod_unit_price').val($('#mod_current_unit_price').val());
        } else {
            unitPrice = parseFloat($('#mod_current_unit_price').val()) * parseFloat($('#mod_conversion_factor').val());
            $('#mod_unit_price').val(unitPrice);
        }
    });

    $(document).on('click', '.remove_pos_sale_item', function(){
        var product_id = $(this).attr("data-product-id");
        var pos_sale_id = $(this).attr("data-pos-sale-id");
        var pos_sale_detail_id = $(this).attr("data-pos-sale-detail-id");

        Swal.fire({
            html: 'Do you wish to remove this item from the Sale?',
            icon: 'question',
            showCancelButton: !0,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                $("#mod_product_id").val(product_id);
                $("#mod_pos_sale_id").val(pos_sale_id);
                $("#mod_pos_sale_detail_id").val(pos_sale_detail_id);

                $.ajax({
                    type: 'POST',
                    url: baseDir + 'pos/sales/remove_pos_sale_item/' + pos_sale_detail_id,
                    data:'',
                    dataType: 'json',
                    beforeSend: function(){
                        $("#sale_products_loader").fadeIn("fast");
                        $("#details_section_loader").fadeIn("fast");
                        $("#totals_section_loader").fadeIn("fast");
                    },
                    success: function(res){
                        $("#sale_products_loader").fadeOut("fast");
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                        
                        if (res.status == 'ERR') {
                            new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                        } else {
                            Swal.fire("Deleted!",res.message,"success"); 
                            //load_pending_sale_info();

                            var transaction_type = $('#transaction_type').val();

                            if (transaction_type == 'Add') {
                                load_pending_sale_info();
                            } else if (transaction_type == 'Edit') {
                                var pos_sale_id = $('#pos_sale_id').val();
                                load_edit_sale_info(pos_sale_id);
                            }
                            

                            load_sale_products();
                        }
                    },
                    error: function(){
                        $("#sale_products_loader").fadeOut("fast");
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                    }
                });
            }
        });
    });

    $(document).on('click', '.remove_pos_sales_return_item', function(){
        var product_id = $(this).attr("data-product-id");
        var pos_sales_return_id = $(this).attr("data-pos-sales-return-id");
        var pos_sales_return_detail_id = $(this).attr("data-pos-sales-return-detail-id");

        Swal.fire({
            html: 'Do you wish to remove this item from the Sales Return?',
            icon: 'question',
            showCancelButton: !0,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                $("#mod_product_id").val(product_id);
                $("#mod_pos_sales_return_id").val(pos_sales_return_id);
                $("#mod_pos_sales_return_detail_id").val(pos_sales_return_detail_id);

                $.ajax({
                    type: 'POST',
                    url: baseDir + 'pos/sales/remove_pos_sales_return_item/' + pos_sales_return_detail_id,
                    data:'',
                    dataType: 'json',
                    beforeSend: function(){
                        $("#sales_return_products_loader").fadeIn("fast");
                        $("#details_section_loader").fadeIn("fast");
                        $("#totals_section_loader").fadeIn("fast");
                    },
                    success: function(res){
                        $("#sales_return_products_loader").fadeOut("fast");
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                        
                        if (res.status == 'ERR') {
                            new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                        } else {
                            Swal.fire("Deleted!",res.message,"success"); 
                            //load_pending_sale_info();

                            var transaction_type = $('#transaction_type').val();

                            if (transaction_type == 'Add') {
                                load_pending_sales_return_info();
                            } else if (transaction_type == 'Edit') {
                                var pos_sales_return_id = $('#pos_sales_return_id').val();
                                load_edit_sales_return_info(pos_sales_return_id);
                            }
                            

                            load_sales_return_products();
                        }
                    },
                    error: function(){
                        $("#sales_return_products_loader").fadeOut("fast");
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                    }
                });
            }
        });
    });

    //lnk_hold_sale_resume
    $(document).on('click', '.lnk_hold_sale_resume', function(e){

        e.preventDefault();

        var pos_sale_id = $(this).attr("data-pos-sale-id");

        Swal.fire({
            html: 'Do you wish to resume this Sale?',
            icon: 'question',
            showCancelButton: !0,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: baseDir + 'pos/sales/resume_held_sale/' + pos_sale_id,
                    data:'',
                    dataType: 'json',
                    beforeSend: function(){
                        $("#sales_hold_list_loader").fadeIn("fast");
                    },
                    success: function(res){
                        $("#sales_hold_list_loader").fadeOut("fast");                        

                        if (res.status == 'ERR') {
                            new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                        } else {

                            window.location = baseDir+'pos/sales/new_sale';
                        }
                    },
                    error: function(){
                        $("#sales_hold_list_loader").fadeOut("fast");
                    }
                });
            }
        });
    });

    $(document).on('click', '.lnk_hold_sale_cancel', function(e){

        e.preventDefault();

        var pos_sale_id = $(this).attr("data-pos-sale-id");

        Swal.fire({
            html: 'Do you wish to cancel this Sale? Please note that this action is IRREVERSIBLE.',
            icon: 'question',
            showCancelButton: !0,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: baseDir + 'pos/sales/cancel_held_sale/' + pos_sale_id,
                    data:'',
                    dataType: 'json',
                    beforeSend: function(){
                        $("#sales_hold_list_loader").fadeIn("fast");
                    },
                    success: function(res){
                        $("#sales_hold_list_loader").fadeOut("fast");                        

                        if (res.status == 'ERR') {
                            new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                        } else {

                            load_sales_hold_list();
                        }
                    },
                    error: function(){
                        $("#sales_hold_list_loader").fadeOut("fast");
                    }
                });
            }
        });
    });

    $(document).on('click', '#sale_overall_discount', function(e){
        e.preventDefault();
        //console.log('clicked');
        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/sale_set_discount_valid',
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#sale_products_loader").fadeIn("fast");
                $("#details_section_loader").fadeIn("fast");
                $("#totals_section_loader").fadeIn("fast");
            },
            success: function(res){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $.each(res.data, function(index, element) {
                        $("#od_pos_sale_id").val(element.pos_sale_id);
                        $("#od_discount_type").val(element.overall_discount_type).change();
                        $("#od_discount_value").val(element.overall_discount_value);
                    });
                    $('#modal_set_overall_discount').modal('toggle');

                }
            },
            error: function(){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
            }
        });
    });

    $(document).on('click', '#edit_sale_overall_discount', function(e){
        e.preventDefault();
        //console.log('clicked');
        var pos_sale_id = $("#pos_sale_id").val();
        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/edit_sale_set_discount_valid/' + pos_sale_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#sale_products_loader").fadeIn("fast");
                $("#details_section_loader").fadeIn("fast");
                $("#totals_section_loader").fadeIn("fast");
            },
            success: function(res){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $.each(res.data, function(index, element) {
                        $("#od_pos_sale_id").val(element.pos_sale_id);
                        $("#od_discount_type").val(element.overall_discount_type).change();
                        $("#od_discount_value").val(element.overall_discount_value);
                    });
                    $('#modal_set_overall_discount').modal('toggle');

                }
            },
            error: function(){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
            }
        });
    });

    $(document).on('click', '#sale_delivery_fee', function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/sale_set_delivery_fee_valid',
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#sale_products_loader").fadeIn("fast");
                $("#details_section_loader").fadeIn("fast");
                $("#totals_section_loader").fadeIn("fast");
            },
            success: function(res){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $.each(res.data, function(index, element) {
                        $("#del_pos_sale_id").val(element.pos_sale_id);
                        $("#del_delivery_fee").val(element.delivery_fee);
                    });
                    $('#modal_set_delivery_fee').modal('toggle');

                }
            },
            error: function(){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
            }
        });
    });

    $(document).on('click', '#edit_sale_delivery_fee', function(e){
        e.preventDefault();
        var pos_sale_id = $("#pos_sale_id").val();
        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/edit_sale_set_delivery_fee_valid/' + pos_sale_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#sale_products_loader").fadeIn("fast");
                $("#details_section_loader").fadeIn("fast");
                $("#totals_section_loader").fadeIn("fast");
            },
            success: function(res){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $.each(res.data, function(index, element) {
                        $("#del_pos_sale_id").val(element.pos_sale_id);
                        $("#del_delivery_fee").val(element.delivery_fee);
                    });
                    $('#modal_set_delivery_fee').modal('toggle');

                }
            },
            error: function(){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
            }
        });
    });

    //lnk_sale_date
    $(document).on('click', '#lnk_sale_date', function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/sale_set_date_valid',
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#sale_products_loader").fadeIn("fast");
                $("#details_section_loader").fadeIn("fast");
                $("#totals_section_loader").fadeIn("fast");
            },
            success: function(res){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $.each(res.data, function(index, element) {
                        $("#saledate_pos_sale_id").val(element.pos_sale_id);
                        $("#saledate_sale_date").val(element.sale_date);
                        $("#saledate_sale_date").datepicker('update', element.sale_date);
                    });
                    $('#modal_set_sale_date').modal('toggle');
                }
            },
            error: function(){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
            }
        });
    });

    //lnk_sales_return_date
    $(document).on('click', '#lnk_sales_return_date', function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/sales_return_set_date_valid',
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#sales_return_products_loader").fadeIn("fast");
                $("#details_section_loader").fadeIn("fast");
                $("#totals_section_loader").fadeIn("fast");
            },
            success: function(res){
                $("#sales_return_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $.each(res.data, function(index, element) {
                        $("#date_pos_sales_return_id").val(element.pos_sales_return_id);
                        $("#date_sales_return_date").val(element.sales_return_date);
                        $("#date_sales_return_date").datepicker('update', element.sales_return_date);
                    });
                    $('#modal_set_sales_return_date').modal('toggle');
                }
            },
            error: function(){
                $("#sales_return_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
            }
        });
    });

    //lnk_change_sale_type
    $(document).on('click', '#lnk_change_sale_type', function(e){
        e.preventDefault();

        var pos_sale_id = $('#pos_sale_id').val();
        var transaction_type = $('#transaction_type').val();

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/sale_change_type_valid',
            data: { transaction_type:transaction_type, pos_sale_id: pos_sale_id },
            dataType: 'json',
            beforeSend: function(){
                $("#sale_products_loader").fadeIn("fast");
                $("#details_section_loader").fadeIn("fast");
                $("#totals_section_loader").fadeIn("fast");
            },
            success: function(res){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    $("#saletype_pos_sale_id").val('');
                    $("#mod_sale_type").val('CASH SALE').change();                        
                    $("#mod_credit_term_id").val('').change();
                    $("#div_select_payment_term").fadeOut("fast");
                } else {                    
                    $.each(res.data, function(index, element) {
                        $("#saletype_pos_sale_id").val(element.pos_sale_id);
                        $("#mod_sale_type").val(element.sale_type).change();
                        if (element.sale_type == 'CREDIT SALE'){
                            $("#mod_credit_term_id").val(element.credit_term_id).change();
                            $("#div_select_payment_term").fadeIn("fast");
                        } else {
                            $("#mod_credit_term_id").val('').change();
                            $("#div_select_payment_term").fadeOut("fast");
                        }
                    });
                }
                $('#modal_change_sale_type').modal('toggle');
            },
            error: function(){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
            }
        });
    });

    $(document).on('change', '#mod_sale_type', function(e){

        var paymentTermRule = {
            mod_credit_term_id: {
                required: true
            }
        };

        var sale_type = $(this).val();

        if (sale_type == 'CREDIT SALE') {
            $("#div_select_payment_term").fadeIn("fast");
            addRules(paymentTermRule);
        } else {
            $("#mod_credit_term_id").val('').change();
            $("#div_select_payment_term").fadeOut("fast");
            removeRules(paymentTermRule);
        }

    });

    //lnk_sale_comments
    $(document).on('click', '#lnk_sale_comments', function(e){
        e.preventDefault();
        $('#chk_default_comments').prop('checked', false);
        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/sale_set_comments_valid',
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#sale_products_loader").fadeIn("fast");
                $("#details_section_loader").fadeIn("fast");
                $("#totals_section_loader").fadeIn("fast");
            },
            success: function(res){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $.each(res.data, function(index, element) {
                        $("#comm_pos_sale_id").val(element.pos_sale_id);
                        $("#comm_comments").val(element.comments);
                        if (element.sale_type == 'CASH SALE') {
                            $.each(res.default_comments, function(index2, element2) {
                                $("#comm_default_comments").val(element2.cash_comments);
                            });
                        }else if (element.sale_type == 'CREDIT SALE') {
                            $.each(res.default_comments, function(index2, element2) {
                                // console.log(element2.credit_comments);
                                $("#comm_default_comments").val(element2.credit_comments);
                            });
                        }
                    });

                    $('#modal_set_sale_comments').modal('toggle');
                }
            },
            error: function(){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
            }
        });
    });

    //lnk_sales_return_comments
    $(document).on('click', '#lnk_sales_return_comments', function(e){
        e.preventDefault();
        $('#chk_default_comments').prop('checked', false);
        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/sales_return_set_comments_valid',
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#sales_return_products_loader").fadeIn("fast");
                $("#details_section_loader").fadeIn("fast");
                $("#totals_section_loader").fadeIn("fast");
            },
            success: function(res){
                $("#sales_return_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $.each(res.data, function(index, element) {
                        $("#comm_pos_sales_return_id").val(element.pos_sales_return_id);
                        $("#comm_comments").val(element.comments);
                    });

                    $('#modal_set_sales_return_comments').modal('toggle');
                }
            },
            error: function(){
                $("#sales_return_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
            }
        });
    });

    $(document).on('change', '#chk_default_comments', function(e){
        if ($(this).is(':checked')) {
            $("#comm_comments").val($("#comm_default_comments").val());
        }
    });

    $(document).on('click', '#edit_lnk_sale_comments', function(e){
        e.preventDefault();
        var pos_sale_id = $("#pos_sale_id").val();
        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/edit_sale_set_comments_valid/' + pos_sale_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#sale_products_loader").fadeIn("fast");
                $("#details_section_loader").fadeIn("fast");
                $("#totals_section_loader").fadeIn("fast");
            },
            success: function(res){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $.each(res.data, function(index, element) {
                        $("#comm_pos_sale_id").val(element.pos_sale_id);
                        $("#comm_comments").val(element.comments);
                    });
                    $('#modal_set_sale_comments').modal('toggle');
                }
            },
            error: function(){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
            }
        });
    });

    //Enter Customer Name
    $(document).on('click', '.lnk_enter_customer_name', function(e){
        e.preventDefault();

        var transaction_type = $('#transaction_type').val();

        if (transaction_type == 'Add') {
            $.ajax({
                type: 'POST',
                url: baseDir + 'pos/sales/sale_enter_customer_name_valid',
                data:'',
                dataType: 'json',
                beforeSend: function(){
                    $("#sale_products_loader").fadeIn("fast");
                    $("#details_section_loader").fadeIn("fast");
                    $("#totals_section_loader").fadeIn("fast");
                },
                success: function(res){
                    $("#sale_products_loader").fadeOut("fast");
                    $("#details_section_loader").fadeOut("fast");
                    $("#totals_section_loader").fadeOut("fast");
                    
                    if (res.status == 'ERR') {
                        new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                    } else {
                        $.each(res.data, function(index, element) {
                            $("#customer_name_pos_sale_id").val(element.pos_sale_id);
                            $("#customer_name_customer_name").val(element.customer_name);
                        });
                        $('#modal_sale_customer_name').modal('toggle');
                    }
                },
                error: function(){
                    $("#sale_products_loader").fadeOut("fast");
                    $("#details_section_loader").fadeOut("fast");
                    $("#totals_section_loader").fadeOut("fast");
                }
            });
        } else if (transaction_type == 'Edit') {        
            var pos_sale_id = $("#pos_sale_id").val();

            $.ajax({
                type: 'POST',
                url: baseDir + 'pos/sales/edit_sale_enter_customer_name_valid/' + pos_sale_id,
                data:'',
                dataType: 'json',
                beforeSend: function(){
                    $("#sale_products_loader").fadeIn("fast");
                    $("#details_section_loader").fadeIn("fast");
                    $("#totals_section_loader").fadeIn("fast");
                },
                success: function(res){
                    $("#sale_products_loader").fadeOut("fast");
                    $("#details_section_loader").fadeOut("fast");
                    $("#totals_section_loader").fadeOut("fast");
                    
                    if (res.status == 'ERR') {
                        new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                    } else {
                        $.each(res.data, function(index, element) {
                            $("#customer_name_pos_sale_id").val(element.pos_sale_id);
                            $("#customer_name_customer_name").val(element.customer_name);
                        });
                        $('#modal_sale_customer_name').modal('toggle');
                    }
                },
                error: function(){
                    $("#sale_products_loader").fadeOut("fast");
                    $("#details_section_loader").fadeOut("fast");
                    $("#totals_section_loader").fadeOut("fast");
                }
            });
        }
    });

    //Add New Customer
    $(document).on('click', '.lnk_add_customer', function(e){
        e.preventDefault();

        $('#frm_sale_add_customer').each(function() {
            this.reset();
        });

        $('#mod_ac_pos_sale_id').val($('#pos_sale_id').val());
        $('#mod_ac_transaction_type').val($('#transaction_type').val());

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/sale_add_customer_valid',
            data: '',
            dataType: 'json',
            beforeSend: function(){
                $("#sale_products_loader").fadeIn("fast");
                $("#details_section_loader").fadeIn("fast");
                $("#totals_section_loader").fadeIn("fast");
            },
            success: function(res){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $('#modal_sale_add_customer').modal('toggle');
                }
            },
            error: function(){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
            }
        });
    });

    //Add Sales Return New Customer
    $(document).on('click', '.lnk_return_add_customer', function(e){
        e.preventDefault();

        $('#frm_sales_return_add_customer').each(function() {
            this.reset();
        });

        $('#mod_ac_pos_sales_return_id').val($('#pos_sales_return_id').val());
        $('#mod_ac_transaction_type').val($('#transaction_type').val());

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/sales_return_add_customer_valid',
            data: '',
            dataType: 'json',
            beforeSend: function(){
                $("#sales_return_products_loader").fadeIn("fast");
                $("#details_section_loader").fadeIn("fast");
                $("#totals_section_loader").fadeIn("fast");
            },
            success: function(res){
                $("#sales_return_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $('#modal_sales_return_add_customer').modal('toggle');
                }
            },
            error: function(){
                $("#sales_return_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
            }
        });
    });

    //Payment Button
    $(document).on('click', '#btn_make_payment', function(e){
        e.preventDefault();
        $("#sale_payment_method").val('Cash').change();
        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/sale_make_payment_valid',
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#sale_products_loader").fadeIn("fast");
                $("#details_section_loader").fadeIn("fast");
                $("#totals_section_loader").fadeIn("fast");
            },
            success: function(res){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $.each(res.data, function(index, element) {
                        $("#payment_header_pos_sale_number").html("[" + element.pos_sale_number + "]");
                        $("#payment_pos_sale_id").val(element.pos_sale_id);
                        $("#payment_pos_sale_number").val(element.pos_sale_number);
                        $("#div_sale_subtotal").html(getCommaSeparatedTwoDecimalsNumber(parseFloat(element.sub_total)));
                        $("#div_sale_overall_discount").html(getCommaSeparatedTwoDecimalsNumber(parseFloat(element.overall_discount)));
                        $("#div_sale_delivery_fee").html(getCommaSeparatedTwoDecimalsNumber(parseFloat(element.delivery_fee)));
                        $("#div_sale_total_sale").html(getCommaSeparatedTwoDecimalsNumber(parseFloat(element.total_sale)));
                        $("#div_sale_total_paid").html(getCommaSeparatedTwoDecimalsNumber(parseFloat(element.total_paid)));

                        var sale_payment_balance = 0;
                        var sale_change = 0;

                        if (parseFloat(element.total_sale) > parseFloat(element.total_paid)) {
                            sale_payment_balance = parseFloat(element.total_sale) - parseFloat(element.total_paid);
                        }

                        if (parseFloat(element.total_paid) > parseFloat(element.total_sale)) {
                            sale_change = parseFloat(element.total_paid) - parseFloat(element.total_sale);
                        }

                        $("#div_sale_payment_balance").html(getCommaSeparatedTwoDecimalsNumber(sale_payment_balance));
                        $("#div_sale_change").html(getCommaSeparatedTwoDecimalsNumber(sale_change));

                        //$("#sale_payment_amount").val(sale_payment_balance);
                        $("#sale_payment_amount").val('0');

                        var paybill_number = '';
                        $.each(res.mpesa, function(index2, element2) {
                            paybill_number = element2.short_code;
                        });

                        $("#btn_sale_payment_mpesa_instructions").attr('data-content','- Go to Safaricom Menu <br>- Select Lipa na M-PESA - Paybill Option<br>- Enter Business No: <b>' + paybill_number + '</b><br>- Enter Account No: <b>' + element.pos_sale_number + '</b><br>- Enter Amount: <b>' + sale_payment_balance + '</b><br>- Enter your MPESA PIN and send<br>- You will receive a confirmation SMS from MPESA');
                    });
                    $('#modal_sale_payment').modal('toggle');
                }
            },
            error: function(){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
            }
        });
    });

    //Payment Button
    $(document).on('click', '.edit_btn_make_payment', function(e){
        e.preventDefault();

        //var pos_sale_id = $("#pos_sale_id").val();
        var pos_sale_id = $(this).attr("data-pos-sale-id");
        var context = $(this).attr("data-context");

        $("#sale_payment_method").val('Cash').change();
        $("#payment_context").val(context);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/edit_sale_make_payment_valid/' + pos_sale_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#sale_products_loader").fadeIn("fast");
                $("#details_section_loader").fadeIn("fast");
                $("#totals_section_loader").fadeIn("fast");
            },
            success: function(res){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $.each(res.data, function(index, element) {
                        $("#payment_header_pos_sale_number").html("[" + element.pos_sale_number + "]");
                        $("#payment_pos_sale_id").val(element.pos_sale_id);
                        $("#payment_pos_sale_number").val(element.pos_sale_number);
                        $("#div_sale_subtotal").html(getCommaSeparatedTwoDecimalsNumber(parseFloat(element.sub_total)));
                        $("#div_sale_overall_discount").html(getCommaSeparatedTwoDecimalsNumber(parseFloat(element.overall_discount)));
                        $("#div_sale_delivery_fee").html(getCommaSeparatedTwoDecimalsNumber(parseFloat(element.delivery_fee)));
                        $("#div_sale_total_sale").html(getCommaSeparatedTwoDecimalsNumber(parseFloat(element.total_sale)));
                        $("#div_sale_total_paid").html(getCommaSeparatedTwoDecimalsNumber(parseFloat(element.total_paid)));

                        var sale_payment_balance = 0;
                        var sale_change = 0;

                        if (parseFloat(element.total_sale) > parseFloat(element.total_paid)) {
                            sale_payment_balance = parseFloat(element.total_sale) - parseFloat(element.total_paid);
                        }

                        if (parseFloat(element.total_paid) > parseFloat(element.total_sale)) {
                            sale_change = parseFloat(element.total_paid) - parseFloat(element.total_sale);
                        }

                        $("#div_sale_payment_balance").html(getCommaSeparatedTwoDecimalsNumber(sale_payment_balance));
                        $("#div_sale_change").html(getCommaSeparatedTwoDecimalsNumber(sale_change));

                        //$("#sale_payment_amount").val(sale_payment_balance);
                        $("#sale_payment_amount").val('0');

                        var paybill_number = '';
                        $.each(res.mpesa, function(index2, element2) {
                            paybill_number = element2.short_code;
                        });

                        $("#btn_sale_payment_mpesa_instructions").attr('data-content','- Go to Safaricom Menu <br>- Select Lipa na M-PESA - Paybill Option<br>- Enter Business No: <b>' + paybill_number + '</b><br>- Enter Account No: <b>' + element.pos_sale_number + '</b><br>- Enter Amount: <b>' + sale_payment_balance + '</b><br>- Enter your MPESA PIN and send<br>- You will receive a confirmation SMS from MPESA');
                    });
                    $('#modal_sale_payment').modal('toggle');
                }
            },
            error: function(){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
            }
        });
    });

    $(document).on('click', '.lnk_void_sale_payment', function(e){
        e.preventDefault();
        var pos_payment_id = $(this).attr("data-pos-payment-id");

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/sale_payment_void_valid/' + pos_payment_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#sale_products_loader").fadeIn("fast");
                $("#details_section_loader").fadeIn("fast");
                $("#totals_section_loader").fadeIn("fast");
            },
            success: function(res){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    // $.each(res.data, function(index, element) {
                    //     $("#void_pos_payment_id").val(pos_payment_id);
                    //     $("#del_delivery_fee").val(element.delivery_fee);
                    // });
                    $("#void_pos_payment_id").val(pos_payment_id);
                    $("#void_reason").val('');
                    $('#modal_void_sale_payment').modal('toggle');

                }
            },
            error: function(){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
            }
        });

    });

    $(document).on('click', '.lnk_modify_sale_payment', function(e){
        e.preventDefault();
        var pos_payment_id = $(this).attr("data-pos-payment-id");

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/sale_payment_modify_valid/' + pos_payment_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#sale_products_loader").fadeIn("fast");
                $("#details_section_loader").fadeIn("fast");
                $("#totals_section_loader").fadeIn("fast");
            },
            success: function(res){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $.each(res.data, function(index, element) {
                        $("#modify_pos_payment_id").val(pos_payment_id);
                        $("#modify_sale_payment_method").val(element.payment_method).change();
                        $("#modify_txt_payment_method").val(element.payment_method);
                        $("#modify_sale_payment_amount").val(element.payment_amount);
                        $("#modify_sale_payment_reference_number").val(element.reference_number);
                        $("#modify_sale_payment_note").val(element.payment_note);

                        if (element.payment_method == 'MPESA') {
                            $("#modify_sale_payment_method").prop('disabled', true);
                            $("#modify_sale_payment_amount").prop('disabled', true);
                        } else {
                            $("#modify_sale_payment_method").prop('disabled', false);
                            $("#modify_sale_payment_amount").prop('disabled', false);
                        }
                    });
                    $.each(res.sale, function(index, element) {
                        //$("#payment_header_pos_sale_number").html("[" + element.pos_sale_number + "]");
                        $("#modify_payment_pos_sale_id").val(element.pos_sale_id);
                        $("#modify_payment_pos_sale_number").val(element.pos_sale_number);

                        var paybill_number = '';
                        $.each(res.mpesa, function(index2, element2) {
                            paybill_number = element2.short_code;
                        });

                        $("#btn_modify_sale_payment_mpesa_instructions").attr('data-content','- Go to Safaricom Menu <br>- Select Lipa na M-PESA - Paybill Option<br>- Enter Business No: <b>' + paybill_number + '</b><br>- Enter Account No: <b>' + element.pos_sale_number + '</b><br>- Enter Amount<br>- Enter your MPESA PIN and send<br>- You will receive a confirmation SMS from MPESA');
                        
                        $('#modal_modify_sale_payment').modal('toggle');
                    });



                }
            },
            error: function(){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
            }
        });

    });

    $(document).on('click', '#btn_sale_make_payment_select', function(e){
        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/loadjs_select_payment_mpesa_transactions',
            data: {context: 'New Payment'},
            beforeSend: function(){
                $("#sale_products_loader").fadeIn("fast");
                $("#details_section_loader").fadeIn("fast");
                $("#totals_section_loader").fadeIn("fast");
                $("#select_mpesa_payment_loader").fadeIn("fast");
            },
            success: function(res){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
                $("#select_mpesa_payment_loader").fadeOut("fast");

                $('#div_select_mpesa_payment_transactions').html(res);
                $('#modal_select_mpesa_payment_transaction').modal('toggle');
            },
            error: function(){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
                $("#select_mpesa_payment_loader").fadeOut("fast");
            }
        });
    });

    $(document).on('click', '#btn_modify_sale_make_payment_select', function(e){
        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/loadjs_select_payment_mpesa_transactions',
            data: {context: 'Edit Payment'},
            beforeSend: function(){
                $("#sale_products_loader").fadeIn("fast");
                $("#details_section_loader").fadeIn("fast");
                $("#totals_section_loader").fadeIn("fast");
            },
            success: function(res){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");

                $('#div_select_mpesa_payment_transactions').html(res);
                $('#modal_select_mpesa_payment_transaction').modal('toggle');
            },
            error: function(){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
            }
        });
    });

    $(document).on('click', '.lnk-select-payment-mpesa-transaction', function(e){

        var reference_number = $(this).attr("data-reference-number");
        var payment_amount = $(this).attr("data-payment-amount");
        var context = $(this).attr("data-context");

        if (context == 'New Payment'){
            $('#sale_payment_reference_number').val(reference_number);
            $('#sale_payment_amount').val(payment_amount);
        } else if (context == 'Edit Payment') {
            $('#modify_sale_payment_reference_number').val(reference_number);
            $('#modify_sale_payment_amount').val(payment_amount);
        }

        $('#modal_select_mpesa_payment_transaction').modal('toggle');

    });

    //POS SALES RETURN REFUND
    $(document).on('click', '.sales_return_refund', function(e){
        e.preventDefault();

        var pos_sales_return_id = $(this).attr("data-pos-sales-return-id");
        var context = $(this).attr("data-context");

        $("#sales_return_refund_method").val('Cash').change();
        $("#refund_context").val(context);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/sales_return_make_refund_valid/' + pos_sales_return_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#sales_returns_list_loader").fadeIn("fast");
            },
            success: function(res){
                $("#sales_returns_list_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $.each(res.data, function(index, element) {
                        $("#refund_header_pos_sales_return_number").html("[" + element.pos_sales_return_number + "]");
                        $("#refund_pos_sales_return_id").val(element.pos_sales_return_id);
                        $("#refund_pos_sales_return_number").val(element.pos_sales_return_number);
                        $("#div_sales_return_total_amount").html(getCommaSeparatedTwoDecimalsNumber(parseFloat(element.total_amount)));
                        $("#div_sales_return_total_refunded").html(getCommaSeparatedTwoDecimalsNumber(parseFloat(element.total_refunded)));

                        var sales_return_refund_balance = 0;
                        var sales_return_change = 0;

                        if (parseFloat(element.total_amount) > parseFloat(element.total_refunded)) {
                            sales_return_refund_balance = parseFloat(element.total_amount) - parseFloat(element.total_refunded);
                        }

                        if (parseFloat(element.total_refunded) > parseFloat(element.total_amount)) {
                            sales_return_change = parseFloat(element.total_refunded) - parseFloat(element.total_amount);
                        }

                        $("#div_sales_return_refund_balance").html(getCommaSeparatedTwoDecimalsNumber(sales_return_refund_balance));
                        $("#div_sales_return_change").html(getCommaSeparatedTwoDecimalsNumber(sales_return_change));

                        //$("#sales_return_refund_amount").val(sales_return_refund_balance);
                        $("#sales_return_refund_amount").val('0');

                        var paybill_number = '';
                        $.each(res.mpesa, function(index2, element2) {
                            paybill_number = element2.short_code;
                        });

                        $("#btn_sales_return_refund_mpesa_instructions").attr('data-content','- Go to Safaricom Menu <br>- Select Lipa na M-PESA - Paybill Option<br>- Enter Business No: <b>' + paybill_number + '</b><br>- Enter Account No: <b>' + element.pos_sales_return_number + '</b><br>- Enter Amount: <b>' + sales_return_refund_balance + '</b><br>- Enter your MPESA PIN and send<br>- You will receive a confirmation SMS from MPESA');
                    });
                    $('#modal_sales_return_refund').modal('toggle');
                }
            },
            error: function(){
                $("#sales_returns_list_loader").fadeOut("fast");
            }
        });
    });


    //Complete Sale Button
    $(document).on('click', '#btn_complete_sale', function(e){
        e.preventDefault();
        //$("#sale_payment_method").val('Cash').change();
        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/sale_complete_valid',
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#sale_products_loader").fadeIn("fast");
                $("#details_section_loader").fadeIn("fast");
                $("#totals_section_loader").fadeIn("fast");
            },
            success: function(res){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");

                var pos_sale_id = 0;

                $.each(res.data, function(index, element) {
                    pos_sale_id = element.pos_sale_id;
                });
                
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                     Swal.fire({
                        html: 'Do you wish to complete this sale?',
                        icon: 'question',
                        showCancelButton: !0,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                        allowOutsideClick: false
                    }).then(function(result) {
                        if (result.value) {

                            $.ajax({
                                type: 'POST',
                                url: baseDir + 'pos/sales/complete_sale/' + pos_sale_id,
                                data: '',
                                contentType: false,
                                processData: false, 
                                cache: false,
                                dataType: 'json',
                                beforeSend: function(){
                                    $("#btn_complete_sale").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
                                    $("#sale_products_loader").fadeIn("fast");
                                    $("#details_section_loader").fadeIn("fast");
                                    $("#totals_section_loader").fadeIn("fast");
                                },
                                success: function(res){
                                    $("#btn_complete_sale").html('<i class="ion-checkmark-circled mr-1"></i>Complete');
                                    $("#sale_products_loader").fadeOut("fast");
                                    $("#details_section_loader").fadeOut("fast");
                                    $("#totals_section_loader").fadeOut("fast");
                                    if (res.status == 'ERR') {
                                        new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                                    } else {
                                        Swal.fire({
                                            html: 'Completed Successfully. <br>Do you wish to print this order?',
                                            icon: 'success',
                                            showCancelButton: !0,
                                            confirmButtonText: 'Yes',
                                            cancelButtonText: 'No',
                                            allowOutsideClick: false
                                        }).then(function(result) {
                                            if (result.value) {
                                                window.open(baseDir + 'pos/sales/print_thermal/' + pos_sale_id, '_blank');
                                                location.reload();
                                            } else {
                                                location.reload();
                                            }
                                        });
                                    }
                                },
                                error: function(){
                                    $("#btn_complete_sale").html('<i class="ion-checkmark-circled mr-1"></i>Complete');
                                    $("#sale_products_loader").fadeOut("fast");
                                    $("#details_section_loader").fadeOut("fast");
                                    $("#totals_section_loader").fadeOut("fast");
                                }
                            });
                        }
                    });
                }
            },
            error: function(){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
            }
        });
    });

    //Complete Sales Return Button
    $(document).on('click', '#btn_complete_sales_return', function(e){
        e.preventDefault();
        //$("#sales_return_payment_method").val('Cash').change();
        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/sales_return_complete_valid',
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#sales_return_products_loader").fadeIn("fast");
                $("#details_section_loader").fadeIn("fast");
                $("#totals_section_loader").fadeIn("fast");
            },
            success: function(res){
                $("#sales_return_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");

                var pos_sales_return_id = 0;

                $.each(res.data, function(index, element) {
                    pos_sales_return_id = element.pos_sales_return_id;
                });
                
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                     Swal.fire({
                        html: 'Do you wish to submit this Sales Return?',
                        icon: 'question',
                        showCancelButton: !0,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                        allowOutsideClick: false
                    }).then(function(result) {
                        if (result.value) {

                            $.ajax({
                                type: 'POST',
                                url: baseDir + 'pos/sales/complete_sales_return/' + pos_sales_return_id,
                                data: '',
                                contentType: false,
                                processData: false, 
                                cache: false,
                                dataType: 'json',
                                beforeSend: function(){
                                    $("#btn_complete_sales_return").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
                                    $("#sales_return_products_loader").fadeIn("fast");
                                    $("#details_section_loader").fadeIn("fast");
                                    $("#totals_section_loader").fadeIn("fast");
                                },
                                success: function(res){
                                    $("#btn_complete_sales_return").html('<i class="ion-checkmark-circled mr-1"></i>Complete');
                                    $("#sales_return_products_loader").fadeOut("fast");
                                    $("#details_section_loader").fadeOut("fast");
                                    $("#totals_section_loader").fadeOut("fast");
                                    if (res.status == 'ERR') {
                                        new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                                    } else {
                                        Swal.fire({
                                            html: 'Completed Successfully. <br>Do you wish to add a new Sales Return?',
                                            icon: 'success',
                                            showCancelButton: !0,
                                            confirmButtonText: 'Yes',
                                            cancelButtonText: 'No',
                                            allowOutsideClick: false
                                        }).then(function(result) {
                                            if (result.value) {
                                                // window.open(baseDir + 'pos/sales_returns/print_receipt/' + pos_sales_return_id, '_blank');
                                                location.reload();
                                            } else {
                                                window.open(baseDir + 'pos/sales/view_return/' + pos_sales_return_id, '');
                                            }
                                        });
                                    }
                                },
                                error: function(){
                                    $("#btn_complete_sales_return").html('<i class="ion-checkmark-circled mr-1"></i>Complete');
                                    $("#sales_return_products_loader").fadeOut("fast");
                                    $("#details_section_loader").fadeOut("fast");
                                    $("#totals_section_loader").fadeOut("fast");
                                }
                            });
                        }
                    });
                }
            },
            error: function(){
                $("#sales_return_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
            }
        });
    });

    //Hold Sale Button
    $(document).on('click', '#btn_hold_sale', function(e){
        e.preventDefault();
        //$("#sale_payment_method").val('Cash').change();
        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/sale_hold_valid',
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#sale_products_loader").fadeIn("fast");
                $("#details_section_loader").fadeIn("fast");
                $("#totals_section_loader").fadeIn("fast");
            },
            success: function(res){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");

                var pos_sale_id = 0;

                $.each(res.data, function(index, element) {
                    pos_sale_id = element.pos_sale_id;
                });
                
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                     Swal.fire({
                        html: 'Do you wish to hold this sale?',
                        icon: 'question',
                        showCancelButton: !0,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                        allowOutsideClick: false
                    }).then(function(result) {
                        if (result.value) {

                            $.ajax({
                                type: 'POST',
                                url: baseDir + 'pos/sales/hold_sale/' + pos_sale_id,
                                data: '',
                                contentType: false,
                                processData: false, 
                                cache: false,
                                dataType: 'json',
                                beforeSend: function(){
                                    $("#btn_hold_sale").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
                                    $("#sale_products_loader").fadeIn("fast");
                                    $("#details_section_loader").fadeIn("fast");
                                    $("#totals_section_loader").fadeIn("fast");
                                },
                                success: function(res){
                                    $("#btn_hold_sale").html('<i class="ion-pause mr-1"></i>Hold Sale');
                                    $("#sale_products_loader").fadeOut("fast");
                                    $("#details_section_loader").fadeOut("fast");
                                    $("#totals_section_loader").fadeOut("fast");
                                    if (res.status == 'ERR') {
                                        new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                                    } else {
                                        location.reload();
                                    }
                                },
                                error: function(){
                                    $("#btn_hold_sale").html('<i class="ion-pause mr-1"></i>Hold Sale');
                                    $("#sale_products_loader").fadeOut("fast");
                                    $("#details_section_loader").fadeOut("fast");
                                    $("#totals_section_loader").fadeOut("fast");
                                }
                            });
                        }
                    });
                }
            },
            error: function(){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
            }
        });
    });

    //Cancel Sale Button
    $(document).on('click', '#btn_cancel_sale', function(e){
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/sale_cancel_valid',
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#sale_products_loader").fadeIn("fast");
                $("#details_section_loader").fadeIn("fast");
                $("#totals_section_loader").fadeIn("fast");
            },
            success: function(res){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");

                var pos_sale_id = 0;

                $.each(res.data, function(index, element) {
                    pos_sale_id = element.pos_sale_id;
                });
                
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                     Swal.fire({
                        html: 'Do you wish to cancel this sale? <br>Please note that this action CANNOT be reversed.',
                        icon: 'question',
                        showCancelButton: !0,
                        confirmButtonText: 'Yes',
                        cancelButtonText: 'No',
                        allowOutsideClick: false
                    }).then(function(result) {
                        if (result.value) {

                            $.ajax({
                                type: 'POST',
                                url: baseDir + 'pos/sales/cancel_sale/' + pos_sale_id,
                                data: '',
                                contentType: false,
                                processData: false, 
                                cache: false,
                                dataType: 'json',
                                beforeSend: function(){
                                    $("#btn_cancel_sale").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
                                    $("#sale_products_loader").fadeIn("fast");
                                    $("#details_section_loader").fadeIn("fast");
                                    $("#totals_section_loader").fadeIn("fast");
                                },
                                success: function(res){
                                    $("#btn_cancel_sale").html('<i class="ion-close-circled"></i>Cancel');
                                    $("#sale_products_loader").fadeOut("fast");
                                    $("#details_section_loader").fadeOut("fast");
                                    $("#totals_section_loader").fadeOut("fast");
                                    if (res.status == 'ERR') {
                                        new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                                    } else {
                                        location.reload();
                                    }
                                },
                                error: function(){
                                    $("#btn_cancel_sale").html('<i class="ion-close-circled"></i>Cancel');
                                    $("#sale_products_loader").fadeOut("fast");
                                    $("#details_section_loader").fadeOut("fast");
                                    $("#totals_section_loader").fadeOut("fast");
                                }
                            });
                        }
                    });
                }
            },
            error: function(){
                $("#sale_products_loader").fadeOut("fast");
                $("#details_section_loader").fadeOut("fast");
                $("#totals_section_loader").fadeOut("fast");
            }
        });
    });

    //Edit Expense lnk_edit_expense
    $(document).on('click', '.lnk_edit_expense', function(e){
        e.preventDefault();

        var expense_id = $(this).attr("data-expense-id");

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/expense_edit_valid/' + expense_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#expenses_list_loader").fadeIn("fast");
            },
            success: function(res){
                $("#expenses_list_loader").fadeOut("fast");

                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                     $.each(res.data, function(index, element) {
                        $("#edit_expense_id").val(element.expense_id);
                        $("#edit_expense_date").val(element.expense_date);
                        $("#edit_expense_reference_number").val(element.expense_reference_number);
                        $("#edit_expense_description").val(element.expense_description);
                        $("#edit_expense_amount").val(element.expense_amount);
                        $("#edit_expense_note").val(element.expense_note);
                    });
                    $('#modal_edit_expense').modal('toggle');
                }
            },
            error: function(){
                $("#expenses_list_loader").fadeOut("fast");
            }
        });
    });

    //Void Expense Valid
    $(document).on('click', '.lnk_void_expense', function(e){
        e.preventDefault();

        var expense_id = $(this).attr("data-expense-id");

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/expense_void_valid/' + expense_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#expenses_list_loader").fadeIn("fast");
            },
            success: function(res){
                $("#expenses_list_loader").fadeOut("fast");

                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                     $.each(res.data, function(index, element) {
                        $("#void_expense_id").val(element.expense_id);
                        $("#expense_void_reason").val('');
                    });
                    $('#modal_void_expense').modal('toggle');
                }
            },
            error: function(){
                $("#expenses_list_loader").fadeOut("fast");
            }
        });
    });


    $(document).on('click', '#btn_detatch_customer', function(e){
        e.preventDefault();
        var pos_sale_id = $(this).attr("data-pos-sale-id");

        Swal.fire({
            html: 'Do you wish to detatch this customer from the Sale?',
            icon: 'question',
            showCancelButton: !0,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: baseDir + 'pos/sales/detatch_customer/' + pos_sale_id,
                    data:'',
                    dataType: 'json',
                    beforeSend: function(){
                        $("#details_section_loader").fadeIn("fast");
                        $("#totals_section_loader").fadeIn("fast");
                    },
                    success: function(res){
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                        if (res.status == 'ERR') {
                            new NioApp.Toast("<h5>Error</h5><p>" + res.data + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                        } else {
                            $("#div_sale_customer_info").html(res.data);
                        }
                    },
                    error: function(){
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                    }
                });
            }
        });
    });

    $(document).on('click', '#btn_return_detatch_customer', function(e){
        e.preventDefault();
        var pos_sales_return_id = $(this).attr("data-pos-sales-return-id");

        Swal.fire({
            html: 'Do you wish to detatch this customer from the Sales Return?',
            icon: 'question',
            showCancelButton: !0,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: baseDir + 'pos/sales/sales_return_detatch_customer/' + pos_sales_return_id,
                    data:'',
                    dataType: 'json',
                    beforeSend: function(){
                        $("#details_section_loader").fadeIn("fast");
                        $("#totals_section_loader").fadeIn("fast");
                    },
                    success: function(res){
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                        if (res.status == 'ERR') {
                            new NioApp.Toast("<h5>Error</h5><p>" + res.data + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                        } else {
                            $("#div_sales_return_customer_info").html(res.data);
                        }
                    },
                    error: function(){
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                    }
                });
            }
        });
    });

    $(document).on('click', '#btn_remove_customer_name', function(e){
        e.preventDefault();
        var pos_sale_id = $(this).attr("data-pos-sale-id");

        Swal.fire({
            html: 'Do you wish to remove this customer name?',
            icon: 'question',
            showCancelButton: !0,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                $.ajax({
                    type: 'POST',
                    url: baseDir + 'pos/sales/remove_customer_name/' + pos_sale_id,
                    data:'',
                    dataType: 'json',
                    beforeSend: function(){
                        $("#details_section_loader").fadeIn("fast");
                        $("#totals_section_loader").fadeIn("fast");
                    },
                    success: function(res){
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                        if (res.status == 'ERR') {
                            new NioApp.Toast("<h5>Error</h5><p>" + res.data + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                        } else {
                            $("#div_sale_customer_info").html(res.data);
                        }
                    },
                    error: function(){
                        $("#details_section_loader").fadeOut("fast");
                        $("#totals_section_loader").fadeOut("fast");
                    }
                });
            }
        });
    });

    $(document).on('click', '.sales_return_approve', function() {

        var pos_sales_return_id = $(this).attr("data-pos-sales-return-id");
        var return_context = $(this).attr("data-context");

        $.ajax({
            url: baseDir + 'pos/sales/fetch_approve_sales_return/' + pos_sales_return_id,
            type: 'POST',
            data: { return_context: return_context },
            beforeSend: function(){
                $("#sales_returns_list_loader").fadeIn("fast");
            },
            success: function(res) {
                $("#sales_returns_list_loader").fadeOut("fast");

                $('#div_approve_pos_sales_return').html(res);
                $('#modal_approve_pos_sales_return').modal('toggle');
            },
            error: function() {
                $("#sales_returns_list_loader").fadeOut("fast");
            }
        });
    });

    $(document).on('change', '#approve_pos_sales_return_status', function() {
        var pos_sales_return_status = $('#approve_pos_sales_return_status').val();

        var approveRules = {
            approve_settlement_refund: {
                required: true
            }
        };

        var rejectRules = {
            rejection_reason: {
                required: true
            }
        };

        if (pos_sales_return_status == '') {
            $('#div_pos_sales_return_approve').slideUp();
            $('#div_pos_sales_return_reject').slideUp();
            removeRules(approveRules);
            removeRules(rejectRules);
        } else if (pos_sales_return_status == '1') {
            $('#div_pos_sales_return_approve').slideDown();
            $('#div_pos_sales_return_reject').slideUp();
            addRules(approveRules);
            removeRules(rejectRules);
        } else if (pos_sales_return_status == '2') {
            $('#div_pos_sales_return_approve').slideUp();
            $('#div_pos_sales_return_reject').slideDown();
            removeRules(approveRules);
            addRules(rejectRules);
        }
    });

    //SELECT OUTLET
    $(document).on('click', '.a-select-outlet', function(){
        //e.preventDefault();
        var outlet_id = $(this).attr("data-outlet-id");

        //console.log('clicked');
        var el = $(this);

        el.find('.g:last').html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');

        $.ajax({
            url: baseDir + 'pos/auth/select_outlet/' + outlet_id,
            type: 'POST',
            data: '',
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
                    toastr.clear();
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                    setTimeout(function() {
                        el.find('.g:last').html('<span class="btn btn-icon btn-trigger mr-n1"><em class="icon ni ni-chevron-right"></em></span>');
                    }, 5000);

                    new Noty({ theme: 'limitless', text: res.message, type: 'error', modal: true, timeout: 2500 }).show();
                } else if (res.status == 'SUCCESS') {
                    setTimeout(function() {
                        window.location = baseDir+'pos';
                    }, 3000);
                }
            },
            error: function() { 
                toastr.clear();               
                new NioApp.Toast("<h5>Error</h5><p>An unexpected error was encountered. Please confirm your network is okay and try again.</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                setTimeout(function() {
                    el.find('.g:last').html('<span class="btn btn-icon btn-trigger mr-n1"><em class="icon ni ni-chevron-right"></em></span>');
                }, 5000);

            }
        });
    });


    /// QUOTATIONS ///
    $(document).on('change', '#q_product_id', function(){      
        var product_id = $(this).val();
        if (product_id != ''){

            $.ajax({
                type: 'POST',
                url: baseDir+'pos/quotations/get_product_details/' + product_id,
                data:'',
                dataType: 'json',
                beforeSend: function(){
                    $("#details_section_loader").fadeIn("fast");
                },
                success: function(res){

                    $.each(res, function(index, element) {

                        var product_id = element.product_id;
                        var product_variation_id = '0';
                        var line_id = '' + product_id + product_variation_id;
                        var product_price = 0;

                        if (element.universal_sale_price == 1){
                            if (element.sale_price > 0){
                                product_price = element.sale_price;
                            } else {
                                if (element.universal_regular_price == 1){
                                    product_price = element.regular_price;
                                } else {
                                    product_price = element.outlet_regular_price;
                                }
                            }
                        } else {
                            if (element.outlet_sale_price > 0){
                                product_price = element.outlet_sale_price;
                            } else {
                              if (element.universal_regular_price == 1){
                                    product_price = element.regular_price;
                                } else {
                                    product_price = element.outlet_regular_price;
                                }
                            }
                        }

                        var product_unit_select = '<select class="form-control unit-select q_unit_id" data-placeholder="Select Unit" id="q_unit_id_' + line_id + '" name="q_unit_id[]" style="width: 100%;" tabindex="-1" aria-hidden="true" required> \
                                                        <option value="">Select Unit</option>';
                        $.each(element.units, function(index2, element2) {
                            var unit_price = product_price;
                            var selected = '';
                            if (element2.universal_prices == 0) {
                                if (element.unit_id != element2.unit_id && element2.outlet_unit_price != 0 && element2.outlet_unit_price != null) {
                                    unit_price = element2.outlet_unit_price;
                                }
                            } else {
                                if (element.unit_id != element2.unit_id && element2.unit_price != 0 && element2.unit_price != null) {
                                    unit_price = element2.unit_price;
                                }
                            }
                            
                            if (element.unit_id == element2.unit_id) {
                                selected = 'selected';
                            }
                            product_unit_select = product_unit_select + '<option value="' + element2.unit_id + '" data-unit-price="' + unit_price + '" data-line-id="' +line_id + '" ' + selected + '>' + element2.unit_name + ' (' + element2.unit_code + ')</option>';
                        });
                        product_unit_select = product_unit_select + '</select>';                                

                        if (element.product_type == 'Simple'){
                            if($('#q_detail_qty_' + line_id).length && $('#q_detail_qty_' + line_id).val().length){
                                var detailqty = parseFloat($('#q_detail_qty_' + line_id).val()) + 1;
                                $('#q_detail_qty_' + line_id).val(detailqty);
                            }else{
                                $('#quotation_details_table').append('<tr> \
                                    <td>' + element.product_name + '<br><div class="text-muted font-size-sm pt-0"><b>SKU:</b>' + element.product_sku_code + '</div><input id="q_detail_id_' + line_id + '" name="q_detail_id[]" type="hidden" class="q_detail_id" value="' + line_id + '"><input id="q_detail_product_id_' + line_id + '" name="q_detail_product_id[]" type="hidden" class="q_detail_product_id" value="' + product_id + '"><input id="q_detail_product_variation_id_' + line_id + '" name="q_detail_product_variation_id[]" type="hidden" class="q_detail_product_variation_id" value="' + product_variation_id + '"></td> \
                                    <td>' + product_unit_select + '</td> \
                                    <td><input id="q_detail_cost_' + line_id + '" name="q_detail_cost[]" type="number" class="form-control q_detail_cost" value="' + product_price + '" autocomplete="off" required></td> \
                                    <td><input id="q_detail_qty_' + line_id + '" name="q_detail_qty[]" type="number" class="form-control q_detail_qty" min="1" value="1" autocomplete="off" required></td> \
                                    <td><span id="q_label_detail_total_' + line_id + '">0.00</span><input id="q_detail_total_' + line_id + '" name="q_detail_total[]" type="hidden" class="form-control q_detail_total" value="0"></td> \
                                    <td><a href="javascript:void(0);" class="q_detail_remove" title="Remove product"><span class="badge rounded-pill bg-transparent bg-outline-danger"><em class="icon ni ni-cross-circle"></em></span></a></td> \
                                </tr>');
                            }
                            calculate_q_detail_total(line_id);
                            calculate_q_totals();
                        } else {
                            $.ajax({
                                type: 'POST',
                                url: baseDir + 'pos/sales/loadjs_select_product_variations',
                                data: { product_id: product_id, context: 'New Quotation', transaction_context: 'Quotation'},
                                beforeSend: function(){
                                    $("#details_section_loader").fadeIn("fast");
                                },
                                success: function(res){
                                    $("#details_section_loader").fadeOut("fast");
                                    $('#div_q_select_product_variation').html(res);
                                    $('#modal_q_select_product_variation').modal('toggle');
                                },
                                error: function(){
                                    $("#details_section_loader").fadeOut("fast");
                                }
                            });
                        }

                        $('.unit-select').select2({
                            placeholder: "Enter at least 1 character",
                            allowClear: true
                        });
                    });
                    
                    $("#details_section_loader").fadeOut("fast");
                    $('#q_product_id').val('').change();
                },
                error: function(){
                    $("#details_section_loader").fadeOut("fast");
                }
            });
        }
    });

    $(document).on('change', '.q_unit_id', function(){
        if ($(this).val() != '' && $(this).val() != null){
            var line_id = $(this).find(':selected').data('line-id');
            var detail_cost = $(this).find(':selected').data('unit-price');
            $('#q_detail_cost_' + line_id).val(detail_cost);

            calculate_q_detail_total(line_id);
            calculate_q_totals();
        }
    });

    $(document).on('change', '.q_detail_qty, .q_detail_cost', function() {
        var element_id = $(this).attr('id');
        var test = element_id.split("_");
        var id = test[test.length - 1];
        calculate_q_detail_total(id);
        calculate_q_totals();
    });
    $(document).on('change', '#q_delivery_fee', function() {
        calculate_q_totals();
    });
    $(document).on('change', '#q_discount', function() {
        calculate_q_totals();
    });
    $(document).on('click', '.q_detail_remove', function() {
        var lnk = $(this);

        Swal.fire({
            html: 'Are you sure you want to remove this product?',
            icon: 'question',
            showCancelButton: !0,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                lnk.closest('tr').remove();
                calculate_q_totals();
            }
        });

    });

    $(document).on('click', '.lnk_void_pos_quotation', function(e){
        e.preventDefault();
        
        var pos_quotation_id = $(this).attr("data-pos-quotation-id");
        var context = $(this).attr("data-context");

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/quotations/void_valid/' + pos_quotation_id,
            data:'',
            dataType: 'json',
            beforeSend: function(){
                $("#quotations_list_loader").fadeIn("fast");
            },
            success: function(res){
                $("#quotations_list_loader").fadeOut("fast");
                
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $("#void_pos_quotation_id").val(pos_quotation_id);
                    $("#void_context").val(context);
                    
                    $("#pos_quotation_void_reason").val('');
                    $('#modal_void_pos_quotation').modal('toggle');

                }
            },
            error: function(){
                $("#quotations_list_loader").fadeOut("fast");
            }
        });

    });



	/*===========================================================
    =======================VALIDATION CODE=======================
    ============================================================*/

    //VALIDATE FRM_LOGIN
    $("#frm_pos_login").validate({
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
                required: true,
                email: true
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

    //VALIDATE FRM_SET_OVERALL_DISCOUNT
    $("#frm_set_overall_discount").validate({
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
            overall_discount_type: {
                required: true
            },
            overall_discount_value: {
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

    //VALIDATE FRM_CHANGE_SALE_TYPE
    $("#frm_change_sale_type").validate({
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
            sale_type: {
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

    //VALIDATE FRM_SET_DELIVERY_FEE
    $("#frm_set_delivery_fee").validate({
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
            delivery_fee: {
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

    //VALIDATE FRM_SET_SALE_COMMENTS
    $("#frm_set_sale_comments").validate({
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
            // comments: {
            //     required: true
            // }
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

    //VALIDATE FRM_SET_SALES_RETURN_COMMENTS
    $("#frm_set_sales_return_comments").validate({
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
            // comments: {
            //     required: true
            // }
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

    //VALIDATE FRM_ENTER_CUSTOMER_NAME
    $("#frm_enter_customer_name").validate({
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
            customer_name: {
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

    //VALIDATE FRM_SALE_MAKE_PAYMENT
    $("#frm_sale_make_payment").validate({
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

    //VALIDATE FRM_SALES_RETURN_MAKE_REFUND
    $("#frm_sales_return_make_refund").validate({
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
            refund_method: {
                required: true
            },
            refund_amount: {
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


    //VALIDATE FRM_VOID_SALE_PAYMENT
    $("#frm_void_sale_payment").validate({
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

    //FRM_SALE_MODIFY_PAYMENT
    $("#frm_sale_modify_payment").validate({
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

    //FRM_SEND_SALE_ORDER_VIA_EMAIL
    $("#frm_send_sale_order_via_email").validate({
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

    //VALIDATE FRM_VOID_POS_SALE
    $("#frm_void_pos_sale").validate({
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
            email_address: {
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

    //VALIDATE FRM_SALE_ADD_CUSTOMER
    $("#frm_sale_add_customer").validate({
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
            email_address: {
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

    //VALIDATE FRM_SALES_RETURN_ADD_CUSTOMER
    $("#frm_sales_return_add_customer").validate({
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
            email_address: {
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

    //VALIDATE FRM_ADD_EXPENSE
    $("#frm_add_expense").validate({
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
            expense_date: {
                required: true
            },
            expense_description: {
                required: true
            },
            expense_amount: {
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

    //VALIDATE FRM_EDIT_EXPENSE
    $("#frm_edit_expense").validate({
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
            expense_date: {
                required: true
            },
            expense_description: {
                required: true
            },
            expense_amount: {
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

    //VALIDATE FRM_VOID_EXPENSE
    $("#frm_void_expense").validate({
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

    //VALIDATE FRM_SALES_REPORT_FILTER
    $("#frm_sales_report_filter").validate({
        errorPlacement: function(error, element) {
            if (element.parent().parent().attr("class") == "checker" || element.parent().parent().attr("class") == "choice") {
                error.appendTo(element.parent().parent().parent().parent().parent());
            } else if (element.parent().parent().attr("class") == "checkbox" || element.parent().parent().attr("class") == "radio") {
                error.appendTo(element.parent().parent().parent());
            } else {
                error.insertAfter(element.parent());
            }
        },
        rules: {
            from_date: {
                required: true
            },
            to_date: {
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

    //VALIDATE FRM_SALES_DETAILED_REPORT_FILTER
    $("#frm_sales_detailed_report_filter").validate({
        errorPlacement: function(error, element) {
            if (element.parent().parent().attr("class") == "checker" || element.parent().parent().attr("class") == "choice") {
                error.appendTo(element.parent().parent().parent().parent().parent());
            } else if (element.parent().parent().attr("class") == "checkbox" || element.parent().parent().attr("class") == "radio") {
                error.appendTo(element.parent().parent().parent());
            } else {
                error.insertAfter(element.parent());
            }
        },
        rules: {
            from_date: {
                required: true
            },
            to_date: {
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

    //VALIDATE FRM_PAYMENTS_REPORT_FILTER
    $("#frm_payments_report_filter").validate({
        errorPlacement: function(error, element) {
            if (element.parent().parent().attr("class") == "checker" || element.parent().parent().attr("class") == "choice") {
                error.appendTo(element.parent().parent().parent().parent().parent());
            } else if (element.parent().parent().attr("class") == "checkbox" || element.parent().parent().attr("class") == "radio") {
                error.appendTo(element.parent().parent().parent());
            } else {
                error.insertAfter(element.parent());
            }
        },
        rules: {
            from_date: {
                required: true
            },
            to_date: {
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

    //VALIDATE FRM_EXPENSE_REPORT_FILTER
    $("#frm_expense_report_filter").validate({
        errorPlacement: function(error, element) {
            if (element.parent().parent().attr("class") == "checker" || element.parent().parent().attr("class") == "choice") {
                error.appendTo(element.parent().parent().parent().parent().parent());
            } else if (element.parent().parent().attr("class") == "checkbox" || element.parent().parent().attr("class") == "radio") {
                error.appendTo(element.parent().parent().parent());
            } else {
                error.insertAfter(element.parent());
            }
        },
        rules: {
            from_date: {
                required: true
            },
            to_date: {
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

    //VALIDATE FRM_PROFIT_LOSS_REPORT_FILTER
    $("#frm_profit_loss_report_filter").validate({
        errorPlacement: function(error, element) {
            if (element.parent().parent().attr("class") == "checker" || element.parent().parent().attr("class") == "choice") {
                error.appendTo(element.parent().parent().parent().parent().parent());
            } else if (element.parent().parent().attr("class") == "checkbox" || element.parent().parent().attr("class") == "radio") {
                error.appendTo(element.parent().parent().parent());
            } else {
                error.insertAfter(element.parent());
            }
        },
        rules: {
            from_date: {
                required: true
            },
            to_date: {
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

    //VALIDATE FRM_UPDATE_PROFILE
    $("#frm_update_profile").validate({
        errorPlacement: function(error, element) {
            if (element.parent().parent().attr("class") == "checker" || element.parent().parent().attr("class") == "choice") {
                error.appendTo(element.parent().parent().parent().parent().parent());
            } else if (element.parent().parent().attr("class") == "checkbox" || element.parent().parent().attr("class") == "radio") {
                error.appendTo(element.parent().parent().parent());
            } else {
                error.insertAfter(element.parent());
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

    //VALIDATE FRM_CHANGE_PASSWORD
    $("#frm_change_password").validate({
        errorPlacement: function(error, element) {
            if (element.parent().parent().attr("class") == "checker" || element.parent().parent().attr("class") == "choice") {
                error.appendTo(element.parent().parent().parent().parent().parent());
            } else if (element.parent().parent().attr("class") == "checkbox" || element.parent().parent().attr("class") == "radio") {
                error.appendTo(element.parent().parent().parent());
            } else {
                error.insertAfter(element.parent());
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
            },
            agree: "Please accept our policy"
        },
        success: function(label) {
            label.parent().removeClass('error');
            label.remove();
        }
    });

    //VALIDATE FRM_RESET_PASSWORD
    $("#frm_reset_password").validate({
        errorPlacement: function(error, element) {
            if (element.parent().parent().attr("class") == "checker" || element.parent().parent().attr("class") == "choice") {
                error.appendTo(element.parent().parent().parent().parent().parent());
            } else if (element.parent().parent().attr("class") == "checkbox" || element.parent().parent().attr("class") == "radio") {
                error.appendTo(element.parent().parent().parent());
            } else {
                error.insertAfter(element.parent());
            }
        },
        rules: {
            email_address: {
                required: true,
                email: true
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

    //VALIDATE FRM_SET_SALE_DATE
    $("#frm_set_sale_date").validate({
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
            sale_date: {
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

    //VALIDATE FRM_SET_SALES_RETURN_DATE
    $("#frm_set_sales_return_date").validate({
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
            sales_return_date: {
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


    //VALIDATE FRM_ADD_QUOTATION
    $("#frm_add_quotation").validate({
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
            customer_id: {
                required: true
            },
            q_date: {
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

    //VALIDATE FRM_EDIT_QUOTATION
    $("#frm_edit_quotation").validate({
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
            customer_id: {
                required: true
            },
            q_date: {
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

    $("#frm_void_pos_quotation").validate({
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

function ajax_load_image(div_name,image_path) {
    $.ajax({
        url: image_path,
        type: 'HEAD',
        error: function(){
        },
        success: function(){
            $("#"+div_name).attr('src', image_path);
        }
    });
}

function convert_excel(type,file_name,table_name) {
    var fn; var dl;
    var elt = document.getElementById(table_name);
    var wb = XLSX.utils.table_to_book(elt, {sheet:"Sheet JS"});
    return dl ?
        XLSX.write(wb, {bookType:type, bookSST:true, type: 'base64'}) :
        XLSX.writeFile(wb, fn || (file_name+'.' + (type || 'xlsx')));
}


function submit_login() {
    $("#div_login_error").fadeOut("fast");
    $("#div_login_success").fadeOut("fast");

    if ($("#frm_pos_login").valid()) {

        $valmsg = "";
        $valmsg2 = "";

        $("#btn_login").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');

        if ($valmsg != $valmsg2) {
            $("#btn_login").html('Sign In <em class="icon ni ni-chevron-right ml-1">');
            $("#div_login_error").html($valmsg);
            $("#div_login_error").fadeIn("fast");
        } else {
            $("#div_login_error").fadeOut("fast");

            var form = document.getElementById('frm_pos_login');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'pos/auth/validate_login',
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
                        $("#btn_login").html('Sign In <em class="icon ni ni-chevron-right ml-1">');
                        $("#div_login_error").html(res.message);
                        $("#div_login_error").fadeIn("fast");
                    } else if (res.status == 'SUCCESS') {
                        $("#div_login_success").html(res.message);
                        $("#div_login_success").fadeIn("fast");

                        setTimeout(function() {
                            window.location = baseDir + 'pos';
                        }, 2000);

                    }
                },
                error: function() {
                    $("#btn_login").html('Sign In <em class="icon ni ni-chevron-right ml-1">');
                    $("#div_login_error").html("Something went wrong. Please check your network and try again.");
                    $("#div_login_error").fadeIn("fast");
                }
            });

        }

    }

    return false;

}

function load_sale_products() {
    var product_category_id = $("#sale_product_category_id").val();
    $("#sale_products_loader").fadeIn("fast");

    $.ajax({
        url: baseDir+'pos/sales/load_sale_products',
        type: 'POST',
        data: { product_category_id: product_category_id, context: 'Sale' },
        success: function (res) {
            $("#div_sale_products").html(res);
            $("#sale_products_loader").fadeOut("fast");
        },
        error: function(){
            $("#sale_products_loader").fadeOut("fast");
        }
    });

}

function load_sales_return_products() {
    var product_category_id = $("#sales_return_product_category_id").val();
    $("#sales_return_products_loader").fadeIn("fast");

    $.ajax({
        url: baseDir+'pos/sales/load_sale_products',
        type: 'POST',
        data: { product_category_id: product_category_id, context: 'Sales Return'},
        success: function (res) {
            $("#div_sales_return_products").html(res);
            $("#sales_return_products_loader").fadeOut("fast");
        },
        error: function(){
            $("#sales_return_products_loader").fadeOut("fast");
        }
    });

}

// function load_sale_info() {

// }

function load_pending_sale_info() {
    $.ajax({
        type: 'POST',
        url: baseDir + 'pos/sales/get_pending_sale_info',
        data:'',
        beforeSend: function(){
            $("#sale_products_loader").fadeIn("fast");
            $("#details_section_loader").fadeIn("fast");
            $("#totals_section_loader").fadeIn("fast");
        },
        success: function(res){

            $("#div_sale_body").html(res);

            $("#sale_products_loader").fadeOut("fast");
            $("#details_section_loader").fadeOut("fast");
            $("#totals_section_loader").fadeOut("fast");
        },
        error: function(){
            $("#sale_products_loader").fadeOut("fast");
            $("#details_section_loader").fadeOut("fast");
            $("#totals_section_loader").fadeOut("fast");
        }
    });
}

function load_pending_sales_return_info() {
    $.ajax({
        type: 'POST',
        url: baseDir + 'pos/sales/get_pending_sales_return_info',
        data:'',
        beforeSend: function(){
            $("#sales_return_products_loader").fadeIn("fast");
            $("#details_section_loader").fadeIn("fast");
            $("#totals_section_loader").fadeIn("fast");
        },
        success: function(res){

            $("#div_sales_return_body").html(res);

            $("#sales_return_products_loader").fadeOut("fast");
            $("#details_section_loader").fadeOut("fast");
            $("#totals_section_loader").fadeOut("fast");
        },
        error: function(){
            $("#sales_return_products_loader").fadeOut("fast");
            $("#details_section_loader").fadeOut("fast");
            $("#totals_section_loader").fadeOut("fast");
        }
    });
}

function load_edit_sale_info(pos_sale_id) {
    $.ajax({
        type: 'POST',
        url: baseDir + 'pos/sales/get_edit_sale_info/' + pos_sale_id,
        data:'',
        beforeSend: function(){
            $("#sale_products_loader").fadeIn("fast");
            $("#details_section_loader").fadeIn("fast");
            $("#totals_section_loader").fadeIn("fast");
        },
        success: function(res){

            $("#div_sale_body").html(res);

            $("#sale_products_loader").fadeOut("fast");
            $("#details_section_loader").fadeOut("fast");
            $("#totals_section_loader").fadeOut("fast");
        },
        error: function(){
            $("#sale_products_loader").fadeOut("fast");
            $("#details_section_loader").fadeOut("fast");
            $("#totals_section_loader").fadeOut("fast");
        }
    });
}

function submit_modify_sales_item() {

    if ($("#frm_modify_sales_item").valid()) {        

        var form = document.getElementById('frm_modify_sales_item');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/submit_modify_sales_item',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_modify_sale_item").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
            },
            success: function(res){
                $("#btn_modify_sale_item").html('<i class="ion-checkmark-circled mr-1"></i>Update');
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $('#modal_modify_sale_product').modal('toggle');

                    var transaction_type = $('#transaction_type').val();

                    if (transaction_type == 'Add') {
                        load_pending_sale_info();
                    } else if (transaction_type == 'Edit') {
                        var pos_sale_id = $('#pos_sale_id').val();
                        load_edit_sale_info(pos_sale_id);
                    }
                    load_sale_products();
                }
            },
            error: function(){
                $("#btn_modify_sale_item").html('<i class="ion-checkmark-circled mr-1"></i>Update');
            }
        });

    }

    return false;
}

function submit_modify_sales_return_item() {

    if ($("#frm_modify_sales_return_item").valid()) {        

        var form = document.getElementById('frm_modify_sales_return_item');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/submit_modify_sales_return_item',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_modify_sales_return_item").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
            },
            success: function(res){
                $("#btn_modify_sales_return_item").html('<i class="ion-checkmark-circled mr-1"></i>Update');
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $('#modal_modify_sales_return_product').modal('toggle');

                    var transaction_type = $('#transaction_type').val();

                    if (transaction_type == 'Add') {
                        load_pending_sales_return_info();
                    } else if (transaction_type == 'Edit') {
                        var pos_sales_return_id = $('#pos_sales_return_id').val();
                        load_edit_sales_return_info(pos_sales_return_id);
                    }
                    load_sales_return_products();
                }
            },
            error: function(){
                $("#btn_modify_sales_return_item").html('<i class="ion-checkmark-circled mr-1"></i>Update');
            }
        });

    }

    return false;
}

function submit_sale_add_customer() {

    if ($("#frm_sale_add_customer").valid()) {        

        var form = document.getElementById('frm_sale_add_customer');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/submit_sale_add_customer',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_sale_add_customer").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
            },
            success: function(res){
                $("#btn_sale_add_customer").html('<i class="ion-checkmark-circled mr-1"></i>Save Customer');
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {                    

                    $("#div_sale_customer_info").html(res.data);
                    $('#frm_sale_add_customer').each(function() {
                        this.reset();
                    });
                    $('#modal_sale_add_customer').modal('toggle');

                }
            },
            error: function(){
                $("#btn_sale_add_customer").html('<i class="ion-checkmark-circled mr-1"></i>Save Customer');
            }
        });

    }

    return false;
}

function submit_sales_return_add_customer() {

    if ($("#frm_sales_return_add_customer").valid()) {        

        var form = document.getElementById('frm_sales_return_add_customer');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/submit_sales_return_add_customer',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_sales_return_add_customer").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
            },
            success: function(res){
                $("#btn_sales_return_add_customer").html('<i class="ion-checkmark-circled mr-1"></i>Save Customer');
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {                    

                    $("#div_sales_return_customer_info").html(res.data);
                    $('#frm_sales_return_add_customer').each(function() {
                        this.reset();
                    });
                    $('#modal_sales_return_add_customer').modal('toggle');

                }
            },
            error: function(){
                $("#btn_sales_return_add_customer").html('<i class="ion-checkmark-circled mr-1"></i>Save Customer');
            }
        });

    }

    return false;
}

function submit_change_sale_type(){
    if ($("#frm_change_sale_type").valid()) {

        var form = document.getElementById('frm_change_sale_type');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/submit_change_sale_type',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_change_sale_type").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
            },
            success: function(res){
                $("#btn_change_sale_type").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $('#modal_change_sale_type').modal('toggle');
                    //load_pending_sale_info();
                    var transaction_type = $('#transaction_type').val();

                    if (transaction_type == 'Add') {
                        load_pending_sale_info();
                    } else if (transaction_type == 'Edit') {
                        var pos_sale_id = $('#pos_sale_id').val();
                        load_edit_sale_info(pos_sale_id);
                    }
                    //load_sale_products();
                }
            },
            error: function(){
                $("#btn_change_sale_type").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
            }
        });

    }

    return false;
}

function submit_sale_overall_discount(){
    if ($("#frm_set_overall_discount").valid()) {

        var form = document.getElementById('frm_set_overall_discount');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/submit_sale_overall_discount',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_set_overall_discount").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
            },
            success: function(res){
                $("#btn_set_overall_discount").html('<i class="ion-checkmark-circled mr-1"></i>Set Discount');
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $('#modal_set_overall_discount').modal('toggle');
                    //load_pending_sale_info();
                    var transaction_type = $('#transaction_type').val();

                    if (transaction_type == 'Add') {
                        load_pending_sale_info();
                    } else if (transaction_type == 'Edit') {
                        var pos_sale_id = $('#pos_sale_id').val();
                        load_edit_sale_info(pos_sale_id);
                    }
                    //load_sale_products();
                }
            },
            error: function(){
                $("#btn_set_overall_discount").html('<i class="ion-checkmark-circled mr-1"></i>Set Discount');
            }
        });

    }

    return false;
}

function submit_sale_delivery_fee(){
    if ($("#frm_set_delivery_fee").valid()) {

        var form = document.getElementById('frm_set_delivery_fee');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/submit_sale_delivery_fee',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_set_delivery_fee").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
            },
            success: function(res){
                $("#btn_set_delivery_fee").html('<i class="ion-checkmark-circled mr-1"></i>Set Delivery Fee');
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $('#modal_set_delivery_fee').modal('toggle');
                    //load_pending_sale_info();
                    var transaction_type = $('#transaction_type').val();

                    if (transaction_type == 'Add') {
                        load_pending_sale_info();
                    } else if (transaction_type == 'Edit') {
                        var pos_sale_id = $('#pos_sale_id').val();
                        load_edit_sale_info(pos_sale_id);
                    }
                    //load_sale_products();
                }
            },
            error: function(){
                $("#btn_set_delivery_fee").html('<i class="ion-checkmark-circled mr-1"></i>Set Delivery Fee');
            }
        });

    }

    return false;
}

function submit_sale_comments(){
    if ($("#frm_set_sale_comments").valid()) {

        var form = document.getElementById('frm_set_sale_comments');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/submit_sale_comments',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_set_sale_comments").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
            },
            success: function(res){
                $("#btn_set_sale_comments").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $('#modal_set_sale_comments').modal('toggle');
                    var transaction_type = $('#transaction_type').val();

                    if (transaction_type == 'Add') {
                        load_pending_sale_info();
                    } else if (transaction_type == 'Edit') {
                        var pos_sale_id = $('#pos_sale_id').val();
                        load_edit_sale_info(pos_sale_id);
                    }
                    //load_sale_products();
                }
            },
            error: function(){
                $("#btn_set_sale_comments").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
            }
        });

    }

    return false;
}

function submit_sales_return_comments(){
    if ($("#frm_set_sales_return_comments").valid()) {

        var form = document.getElementById('frm_set_sales_return_comments');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/submit_sales_return_comments',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_set_sales_return_comments").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
            },
            success: function(res){
                $("#btn_set_sales_return_comments").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $('#modal_set_sales_return_comments').modal('toggle');
                    var transaction_type = $('#transaction_type').val();

                    if (transaction_type == 'Add') {
                        load_pending_sales_return_info();
                    } else if (transaction_type == 'Edit') {
                        var pos_sales_return_id = $('#pos_sales_return_id').val();
                        load_edit_sales_return_info(pos_sales_return_id);
                    }
                    //load_sales_return_products();
                }
            },
            error: function(){
                $("#btn_set_sales_return_comments").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
            }
        });

    }

    return false;
}

function submit_sale_date(){
    if ($("#frm_set_sale_date").valid()) {

        var form = document.getElementById('frm_set_sale_date');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/submit_sale_date',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_set_sale_date").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
            },
            success: function(res){
                $("#btn_set_sale_date").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $('#modal_set_sale_date').modal('toggle');
                    var transaction_type = $('#transaction_type').val();

                    if (transaction_type == 'Add') {
                        load_pending_sale_info();
                    } else if (transaction_type == 'Edit') {
                        var pos_sale_id = $('#pos_sale_id').val();
                        load_edit_sale_info(pos_sale_id);
                    }
                    //load_sale_products();
                }
            },
            error: function(){
                $("#btn_set_sale_date").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
            }
        });

    }

    return false;
}

function submit_sales_return_date(){
    if ($("#frm_set_sales_return_date").valid()) {

        var form = document.getElementById('frm_set_sales_return_date');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/submit_sales_return_date',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_set_sales_return_date").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
            },
            success: function(res){
                $("#btn_set_sales_return_date").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $('#modal_set_sales_return_date').modal('toggle');
                    var transaction_type = $('#transaction_type').val();

                    if (transaction_type == 'Add') {
                        load_pending_sales_return_info();
                    } else if (transaction_type == 'Edit') {
                        var pos_sales_return_id = $('#pos_sales_return_id').val();
                        load_edit_sales_return_info(pos_sales_return_id);
                    }
                    //load_sales_return_products();
                }
            },
            error: function(){
                $("#btn_set_sales_return_date").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
            }
        });

    }

    return false;
}

function submit_approve_pos_sales_return(){
    if ($("#frm_approve_pos_sales_return").valid()) {        

        var form = document.getElementById('frm_approve_pos_sales_return');
        var formData = new FormData(form);

        $.ajax({
            url: baseDir + 'pos/sales/approve_pos_sales_return',
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
            beforeSend: function () {
                $("#btn_approve_pos_sales_return").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
        },
            success: function(res) {                
                if (res.status == 'ERR') {
                    $("#btn_approve_pos_sales_return").html('<i class="icon-checkmark4"></i> SUBMIT');
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else if (res.status == 'SUCCESS') {

                    var pos_sales_return_id = $('#approval_pos_sales_return_id').val();
                    var return_status = $('#approve_pos_sales_return_status').val();
                    var approve_settlement = $('input[name="approve_settlement"]:checked').val();
                    var return_context = $('#approval_pos_sales_return_context').val();

                    if (return_context == 'Sales Returns List'){
                        load_sales_returns_list();
                    }

                    if (return_status == 1 && approve_settlement == 'Refund') {
                        $("#btn_approve_pos_sales_return").html('<i class="icon-checkmark4"></i> SUBMIT');
                        Swal.fire({
                            html: res.message + '<br>Do you wish to proceed and make a refund?',
                            icon: 'question',
                            showCancelButton: !0,
                            confirmButtonText: 'Yes',
                            cancelButtonText: 'No',
                            allowOutsideClick: false
                        }).then(function(result) {
                            if (result.value) {
                                if (return_context == 'View Sales Return'){
                                    $.ajax({
                                        type: 'POST',
                                        url: baseDir + 'pos/sales/sales_return_approval_refund_success',
                                        data: '',
                                        success: function(res){
                                            window.location = window.location.href;
                                        },
                                        error: function(){
                                            location.reload();
                                        }
                                    });
                                } else if (return_context == 'Sales Returns List') {                                    
                                    $('#modal_approve_pos_sales_return').modal('toggle');
                                    // $(".sales_return_refund").trigger('click');
                                    $('.sales_return_refund[data-pos-sales-return-id="' + pos_sales_return_id + '"]').trigger('click');
                                }
                            } else {
                                if (return_context == 'View Sales Return'){
                                    location.reload();
                                } else if (return_context == 'Sales Returns List') {
                                    $('#modal_approve_pos_sales_return').modal('toggle');
                                }
                            }
                        });
                    } else {
                        new NioApp.Toast("<h5>Success</h5><p>" + res.message + "</p>", "success", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                        $("#btn_approve_pos_sales_return").html('<i class="icon-checkmark4"></i> SUBMIT');
                        setTimeout(function() {
                            if (return_context == 'View Sales Return'){
                                location.reload();
                            } else if (return_context == 'Sales Returns List') {
                                load_sales_returns_list();
                                $('#modal_approve_pos_sales_return').modal('toggle');
                            }
                        }, 2000);
                    }
                }
            },
            error: function() {
                $("#btn_approve_pos_sales_return").html('<i class="icon-checkmark4"></i> SUBMIT');
                new NioApp.Toast("<h5>Error</h5><p>Something went wrong. Please check your network and try again.</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
            }
        });

    }
    return false; 
}

function submit_enter_customer_name(){
    if ($("#frm_enter_customer_name").valid()) {

        var form = document.getElementById('frm_enter_customer_name');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/submit_enter_customer_name',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_enter_customer_name").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
            },
            success: function(res){
                $("#btn_enter_customer_name").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $('#modal_sale_customer_name').modal('toggle');

                    var transaction_type = $('#customer_name_transaction_type').val();

                    if (transaction_type == 'Add') {
                        load_pending_sale_info();
                    } else if (transaction_type == 'Edit') {
                        var pos_sale_id = $('#pos_sale_id').val();
                        load_edit_sale_info(pos_sale_id);
                    }

                    //var context = $('#payment_context').val();

                    //if (context == '') {
                        //load_pending_sale_info();
                    //} else if (context == 'Edit Sale') {
                        //var pos_sale_id = $('#pos_sale_id').val();
                        //load_edit_sale_info(pos_sale_id);
                    //} else if (context == 'View Sale') {
                        //location.reload();
                    //}
                }
            },
            error: function(){
                $("#btn_enter_customer_name").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
            }
        });
    }

    return false;
}

function submit_sale_payment(){
    if ($("#frm_sale_make_payment").valid()) {

        Swal.fire({
            html: 'Do you wish to submit this Payment?',
            icon: 'question',
            showCancelButton: !0,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                var form = document.getElementById('frm_sale_make_payment');
                var formData = new FormData(form);

                $.ajax({
                    type: 'POST',
                    url: baseDir + 'pos/sales/submit_sale_payment',
                    data: formData,
                    contentType: false,
                    processData: false, 
                    cache: false,
                    dataType: 'json',
                    beforeSend: function(){
                        $("#btn_submit_sale_payment").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
                    },
                    success: function(res){
                        $("#btn_submit_sale_payment").html('<i class="ion-checkmark-circled mr-1"></i>Submit Payment');
                        if (res.status == 'ERR') {
                            new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                        } else {
                            $('#modal_sale_payment').modal('toggle');
                            var transaction_type = $('#transaction_type').val();

                            var context = $('#payment_context').val();

                            if (context == '') {
                                load_pending_sale_info();
                            } else if (context == 'Edit Sale') {
                                var pos_sale_id = $('#pos_sale_id').val();
                                load_edit_sale_info(pos_sale_id);
                            } else if (context == 'View Sale') {
                                location.reload();
                            } else if (context == 'Sales List') {
                                load_sales_list();
                            }
                            //load_sale_products();
                        }
                    },
                    error: function(){
                        $("#btn_submit_sale_payment").html('<i class="ion-checkmark-circled mr-1"></i>Submit Payment');
                    }
                });
            }
        });
    }

    return false;
}

function submit_sales_return_refund(){
    if ($("#frm_sales_return_make_refund").valid()) {

        Swal.fire({
            html: 'Do you wish to submit this refund?',
            icon: 'question',
            showCancelButton: !0,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                var form = document.getElementById('frm_sales_return_make_refund');
                var formData = new FormData(form);

                $.ajax({
                    type: 'POST',
                    url: baseDir + 'pos/sales/submit_sales_return_refund',
                    data: formData,
                    contentType: false,
                    processData: false, 
                    cache: false,
                    dataType: 'json',
                    beforeSend: function(){
                        $("#btn_submit_sales_return_refund").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
                    },
                    success: function(res){
                        $("#btn_submit_sales_return_refund").html('<i class="ion-checkmark-circled mr-1"></i>Submit Refund');
                        if (res.status == 'ERR') {
                            new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                        } else {
                            $('#modal_sales_return_refund').modal('toggle');

                            var context = $('#refund_context').val();

                            if (context == 'View Sales Return') {
                                location.reload();
                            } else if (context == 'Sales Returns List') {
                                load_sales_returns_list();
                            }
                            //load_sales_return_products();
                        }
                    },
                    error: function(){
                        $("#btn_submit_sales_return_refund").html('<i class="ion-checkmark-circled mr-1"></i>Submit Refund');
                    }
                });
            }
        });
    }

    return false;
}


function submit_void_sale_payment(){
    if ($("#frm_void_sale_payment").valid()) {

        Swal.fire({
            html: 'Do you wish to void this Payment? Please note that this action is IRREVERSIBLE',
            icon: 'question',
            showCancelButton: !0,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                var form = document.getElementById('frm_void_sale_payment');
                var formData = new FormData(form);

                $.ajax({
                    type: 'POST',
                    url: baseDir + 'pos/sales/submit_void_sale_payment',
                    data: formData,
                    contentType: false,
                    processData: false, 
                    cache: false,
                    dataType: 'json',
                    beforeSend: function(){
                        $("#btn_void_sale_payment").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
                    },
                    success: function(res){
                        $("#btn_void_sale_payment").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
                        if (res.status == 'ERR') {
                            new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                        } else {
                            $('#modal_void_sale_payment').modal('toggle');
                            //load_pending_sale_info();
                            var transaction_type = $('#transaction_type').val();

                            if (transaction_type == 'Add') {
                                load_pending_sale_info();
                            } else if (transaction_type == 'Edit') {
                                var pos_sale_id = $('#pos_sale_id').val();
                                load_edit_sale_info(pos_sale_id);
                            }
                            //load_sale_products();
                        }
                    },
                    error: function(){
                        $("#btn_void_sale_payment").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
                    }
                });
            }
        });
    }

    return false;
}

function submit_modify_sale_payment(){

    if ($("#frm_sale_modify_payment").valid()) {

        var form = document.getElementById('frm_sale_modify_payment');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/submit_modify_sale_payment',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_submit_modify_sale_payment").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
            },
            success: function(res){
                $("#btn_submit_modify_sale_payment").html('<i class="ion-checkmark-circled mr-1"></i>Update');
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    $('#modal_modify_sale_payment').modal('toggle');
                    //load_pending_sale_info();
                    var transaction_type = $('#transaction_type').val();

                    if (transaction_type == 'Add') {
                        load_pending_sale_info();
                    } else if (transaction_type == 'Edit') {
                        var pos_sale_id = $('#pos_sale_id').val();
                        load_edit_sale_info(pos_sale_id);
                    }
                    //load_sale_products();
                }
            },
            error: function(){
                $("#btn_submit_modify_sale_payment").html('<i class="ion-checkmark-circled mr-1"></i>Update');
            }
        });
    }

    return false;
}

function load_sales_list() {
    var form = document.getElementById('frm_filter_sales');
    var formData = new FormData(form);
    $.ajax({
        type: 'POST',
        url: baseDir + 'pos/sales/load_ajax_sales_list',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function(){
            $("#sales_list_loader").fadeIn("fast");
        },
        success: function(res){
            $("#sales_list_loader").fadeOut("fast");
            $("#div_sales_list").html(res);
        },
        error: function(){
            $("#sales_list_loader").fadeOut("fast");
        }
    });   
}

function load_sales_returns_list() {
    var form = document.getElementById('frm_filter_sales_returns');
    var formData = new FormData(form);
    $.ajax({
        type: 'POST',
        url: baseDir + 'pos/sales/load_ajax_sales_returns_list',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function(){
            $("#sales_returns_list_loader").fadeIn("fast");
        },
        success: function(res){
            $("#sales_returns_list_loader").fadeOut("fast");
            $("#div_sales_returns_list").html(res);
        },
        error: function(){
            $("#sales_returns_list_loader").fadeOut("fast");
        }
    });   
}

function load_sales_hold_list(){
    $.ajax({
        type: 'POST',
        url: baseDir + 'pos/sales/load_ajax_sales_hold_list',
        data: '',
        beforeSend: function(){
            $("#sales_hold_list_loader").fadeIn("fast");
        },
        success: function(res){
            $("#sales_hold_list_loader").fadeOut("fast");
            $("#div_sales_hold_list").html(res);
        },
        error: function(){
            $("#sales_hold_list_loader").fadeOut("fast");
        }
    });   
}

function load_products_list(){
    $.ajax({
        type: 'POST',
        url: baseDir + 'pos/sales/load_ajax_products_list',
        data: '',
        beforeSend: function(){
            $("#products_list_loader").fadeIn("fast");
        },
        success: function(res){
            $("#products_list_loader").fadeOut("fast");
            $("#div_products_list").html(res);
        },
        error: function(){
            $("#products_list_loader").fadeOut("fast");
        }
    });   
}

function load_low_stock_list(){
    $.ajax({
        type: 'POST',
        url: baseDir + 'pos/sales/load_ajax_low_stock_list',
        data: '',
        beforeSend: function(){
            $("#low_stock_list_loader").fadeIn("fast");
        },
        success: function(res){
            $("#low_stock_list_loader").fadeOut("fast");
            $("#div_low_stock_list").html(res);
        },
        error: function(){
            $("#low_stock_list_loader").fadeOut("fast");
        }
    });   
}

function load_customers_list(){
    $.ajax({
        type: 'POST',
        url: baseDir + 'pos/sales/load_ajax_customers_list',
        data: '',
        beforeSend: function(){
            $("#customers_list_loader").fadeIn("fast");
        },
        success: function(res){
            $("#customers_list_loader").fadeOut("fast");
            $("#div_customers_list").html(res);
        },
        error: function(){
            $("#customers_list_loader").fadeOut("fast");
        }
    });   
}

function submit_send_sale_order_via_email(){
    if ($("#frm_send_sale_order_via_email").valid()) {

        var form = document.getElementById('frm_send_sale_order_via_email');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/submit_send_sale_order_via_email',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_send_pos_sale_order_via_email").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
            },
            success: function(res){
                $("#btn_send_pos_sale_order_via_email").html('<em class="icon ni ni-send mr-1"></em>Send Email');
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    //$('#modal_send_sale_order_via_email').modal('toggle');
                    new NioApp.Toast("<h5>Success</h5><p>" + res.message + "</p>", "success", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                }
            },
            error: function(){
                $("#btn_send_pos_sale_order_via_email").html('<em class="icon ni ni-send mr-1"></em>Send Email');
            }
        });
    }
    return false;
}

function submit_void_pos_sale(){
    if ($("#frm_void_pos_sale").valid()) {

        Swal.fire({
            html: 'Do you wish to void this Sale? Please note that this action is IRREVERSIBLE',
            icon: 'question',
            showCancelButton: !0,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                var form = document.getElementById('frm_void_pos_sale');
                var formData = new FormData(form);

                $.ajax({
                    type: 'POST',
                    url: baseDir + 'pos/sales/submit_void_pos_sale',
                    data: formData,
                    contentType: false,
                    processData: false, 
                    cache: false,
                    dataType: 'json',
                    beforeSend: function(){
                        $("#btn_void_pos_sale").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
                    },
                    success: function(res){
                        $("#btn_void_pos_sale").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
                        if (res.status == 'ERR') {
                            new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                        } else {
                            $('#modal_void_pos_sale').modal('toggle');
                            var context = $('#void_context').val();

                            if (context == 'Sales List') {
                                load_sales_list();
                            } else if (context == 'View Sale') {
                                window.location = baseDir+'pos/sales/sales_list';
                            }
                        }
                    },
                    error: function(){
                        $("#btn_void_pos_sale").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
                    }
                });
            }
        });
    }
    return false;
}

function save_customer() {
    if ($("#frm_add_customer").valid()) {

        var form = document.getElementById('frm_add_customer');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/save_customer',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_add_customer").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
            },
            success: function(res){
                $("#btn_add_customer").html('<em class="icon ni ni-save"></em> Save Customer');
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    new NioApp.Toast("<h5>Success</h5><p>" + res.message + "</p>", "success", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                    $('#frm_add_customer').each(function() {
                        this.reset();
                    });
                    $('#gender').val('').change();
                }
            },
            error: function(){
                $("#btn_add_customer").html('<em class="icon ni ni-save"></em> Save Customer');
            }
        });
    }
    return false;
}

function update_customer() {
    if ($("#frm_edit_customer").valid()) {

        var form = document.getElementById('frm_edit_customer');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/update_customer',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_update_customer").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
            },
            success: function(res){
                $("#btn_update_customer").html('<em class="icon ni ni-save"></em> Update Customer');
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    new NioApp.Toast("<h5>Success</h5><p>" + res.message + "</p>", "success", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                }
            },
            error: function(){
                $("#btn_update_customer").html('<em class="icon ni ni-save"></em> Update Customer');
            }
        });
    }
    return false;
}

function load_expenses_list() {
    var form = document.getElementById('frm_filter_expenses');
    var formData = new FormData(form);
    $.ajax({
        type: 'POST',
        url: baseDir + 'pos/sales/load_ajax_expenses_list',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function(){
            $("#expenses_list_loader").fadeIn("fast");
        },
        success: function(res){
            $("#expenses_list_loader").fadeOut("fast");
            $("#div_expenses_list").html(res);
        },
        error: function(){
            $("#expenses_list_loader").fadeOut("fast");
        }
    });
}

function save_expense() {
    if ($("#frm_add_expense").valid()) {

        var form = document.getElementById('frm_add_expense');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/save_expense',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_add_expense").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
            },
            success: function(res){
                $("#btn_add_expense").html('<i class="ion-checkmark-circled mr-1"></i>Save Expense');
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    new NioApp.Toast("<h5>Success</h5><p>" + res.message + "</p>", "success", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                    $('#frm_add_expense').each(function() {
                        this.reset();
                    });
                    load_expenses_list();
                }
            },
            error: function(){
                $("#btn_add_expense").html('<i class="ion-checkmark-circled mr-1"></i>Save Expense');
            }
        });
    }
    return false;
}

function update_expense(){
    if ($("#frm_edit_expense").valid()) {

        var form = document.getElementById('frm_edit_expense');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/update_expense',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_edit_expense").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
            },
            success: function(res){
                $("#btn_edit_expense").html('<i class="ion-checkmark-circled mr-1"></i>Update Expense');
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    new NioApp.Toast("<h5>Success</h5><p>" + res.message + "</p>", "success", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                    load_expenses_list();
                }
            },
            error: function(){
                $("#btn_edit_expense").html('<i class="ion-checkmark-circled mr-1"></i>Update Expense');
            }
        });
    }
    return false;
}

function submit_void_expense(){
    if ($("#frm_void_expense").valid()) {

        var form = document.getElementById('frm_void_expense');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/sales/submit_void_expense',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_void_expense").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
            },
            success: function(res){
                $("#btn_void_expense").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    //new NioApp.Toast("<h5>Success</h5><p>" + res.message + "</p>", "success", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                    $('#modal_void_expense').modal('toggle');
                    load_expenses_list();
                }
            },
            error: function(){
                $("#btn_void_expense").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
            }
        });
    }
    return false;
}

function load_sales_report(){
    if ($("#frm_sales_report_filter").valid()) {

        var form = document.getElementById('frm_sales_report_filter');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/reports/ajax_sales_report',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            beforeSend: function(){
                $("#btn_view_sales_report").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
                $("#sales_report_loader").fadeIn("fast");
            },
            success: function(res){
                $("#btn_view_sales_report").html('<em class="icon ni ni-reload-alt mr-1"></em>Generate Report');
                $("#div_sales_report").html(res);
                $("#sales_report_loader").fadeOut("fast");
            },
            error: function(){
                $("#btn_view_sales_report").html('<em class="icon ni ni-reload-alt mr-1"></em>Generate Report');
                $("#sales_report_loader").fadeOut("fast");
            }
        });
    }
    return false;
}

function load_sales_detailed_report(){
    if ($("#frm_sales_detailed_report_filter").valid()) {

        var form = document.getElementById('frm_sales_detailed_report_filter');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/reports/ajax_sales_detailed_report',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            beforeSend: function(){
                $("#btn_view_sales_detailed_report").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
                $("#sales_detailed_report_loader").fadeIn("fast");
            },
            success: function(res){
                $("#btn_view_sales_detailed_report").html('<em class="icon ni ni-reload-alt mr-1"></em>Generate Report');
                $("#div_sales_detailed_report").html(res);
                $("#sales_detailed_report_loader").fadeOut("fast");
            },
            error: function(){
                $("#btn_view_sales_detailed_report").html('<em class="icon ni ni-reload-alt mr-1"></em>Generate Report');
                $("#sales_detailed_report_loader").fadeOut("fast");
            }
        });
    }
    return false;
}


function load_payments_report(){
    if ($("#frm_payments_report_filter").valid()) {

        var form = document.getElementById('frm_payments_report_filter');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/reports/ajax_payments_report',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            beforeSend: function(){
                $("#btn_view_payments_report").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
                $("#payments_report_loader").fadeIn("fast");
            },
            success: function(res){
                $("#btn_view_payments_report").html('<em class="icon ni ni-reload-alt mr-1"></em>Generate Report');
                $("#div_payments_report").html(res);
                $("#payments_report_loader").fadeOut("fast");
            },
            error: function(){
                $("#btn_view_payments_report").html('<em class="icon ni ni-reload-alt mr-1"></em>Generate Report');
                $("#payments_report_loader").fadeOut("fast");
            }
        });
    }
    return false;
}

function load_expense_report(){
    if ($("#frm_expense_report_filter").valid()) {

        var form = document.getElementById('frm_expense_report_filter');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/reports/ajax_expense_report',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            beforeSend: function(){
                $("#btn_view_expense_report").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
                $("#expense_report_loader").fadeIn("fast");
            },
            success: function(res){
                $("#btn_view_expense_report").html('<em class="icon ni ni-reload-alt mr-1"></em>Generate Report');
                $("#div_expense_report").html(res);
                $("#expense_report_loader").fadeOut("fast");
            },
            error: function(){
                $("#btn_view_expense_report").html('<em class="icon ni ni-reload-alt mr-1"></em>Generate Report');
                $("#expense_report_loader").fadeOut("fast");
            }
        });
    }
    return false;
}

function load_profit_loss_report(){
    if ($("#frm_profit_loss_report_filter").valid()) {

        var form = document.getElementById('frm_profit_loss_report_filter');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/reports/ajax_profit_loss_report',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            beforeSend: function(){
                $("#btn_view_profit_loss_report").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
                $("#profit_loss_report_loader").fadeIn("fast");
            },
            success: function(res){
                $("#btn_view_profit_loss_report").html('<em class="icon ni ni-reload-alt mr-1"></em>Generate Report');
                $("#div_profit_loss_report").html(res);
                $("#profit_loss_report_loader").fadeOut("fast");
            },
            error: function(){
                $("#btn_view_profit_loss_report").html('<em class="icon ni ni-reload-alt mr-1"></em>Generate Report');
                $("#profit_loss_report_loader").fadeOut("fast");
            }
        });
    }
    return false;
}

function submit_update_profile(){
    if ($("#frm_update_profile").valid()) {

        var form = document.getElementById('frm_update_profile');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/auth/update_profile',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_update_profile").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
            },
            success: function(res){
                $("#btn_update_profile").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    new NioApp.Toast("<h5>Success</h5><p>" + res.message + "</p>", "success", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                    setTimeout(function() {
                        location.reload();
                    }, 3000);
                }
            },
            error: function(){
                $("#btn_update_profile").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
            }
        });
    }
    return false;
}

function submit_change_password(){
    if ($("#frm_change_password").valid()) {

        var form = document.getElementById('frm_change_password');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/auth/submit_change_password',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_change_password").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
            },
            success: function(res){
                $("#btn_change_password").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    new NioApp.Toast("<h5>Success</h5><p>" + res.message + "</p>", "success", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                    $('#frm_change_password').each(function() {
                        this.reset();
                    });
                }
            },
            error: function(xhr, textStatus, error){
                $("#btn_change_password").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
                new NioApp.Toast("<h5>Error</h5><p>" + xhr.responseText + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
            }
        });
    }
    return false;
}

function submit_reset_password(){
    if ($("#frm_reset_password").valid()) {

        var form = document.getElementById('frm_reset_password');
        var formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: baseDir + 'pos/auth/submit_reset_password',
            data: formData,
            contentType: false,
            processData: false, 
            cache: false,
            dataType: 'json',
            beforeSend: function(){
                $("#btn_reset_password").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
            },
            success: function(res){
                $("#btn_reset_password").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
                if (res.status == 'ERR') {
                    new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                } else {
                    new NioApp.Toast("<h5>Success</h5><p>" + res.message + "</p>", "success", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                    $('#frm_reset_password').each(function() {
                        this.reset();
                    });
                }
            },
            error: function(xhr, textStatus, error){
                $("#btn_reset_password").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
                new NioApp.Toast("<h5>Error</h5><p>" + xhr.responseText + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
            }
        });
    }
    return false;
}


///// QUOTATIONS ///
function calculate_q_detail_total(id){
    var order_qty = parseFloat($("#q_detail_qty_"+id).val());
    var cost = parseFloat($("#q_detail_cost_"+id).val());
    var total = order_qty * cost;
    document.getElementById("q_label_detail_total_"+id).innerHTML = total.formatMoney(2,',','.');
    $("#q_detail_total_"+id).val(parseFloat(total).toFixed(2));
}
function calculate_q_totals(){
    //TOTAL QUANTITY
    var total_detail_qty = 0;
    $(".q_detail_qty").each(function() {
        var detail_qty = parseFloat($(this).val());
        total_detail_qty = total_detail_qty + detail_qty;
    });
    document.getElementById("q_label_total_detail_qty").innerHTML = total_detail_qty.formatMoney(2,',','.');
    $("#q_total_detail_qty").val(parseFloat(total_detail_qty));

    //TOTAL TOTAL
    var total_detail_total = 0;
    var delivery_fee = parseFloat($("#q_delivery_fee").val());
    var discount = parseFloat($("#q_discount").val());

    $(".q_detail_total").each(function() {
        var detail_total = parseFloat($(this).val());
        total_detail_total = total_detail_total + detail_total;
    });
    //SUBTOTAL
    document.getElementById("q_label_total_detail_subtotal").innerHTML = total_detail_total.formatMoney(2,',','.');
    $("#q_total_detail_subtotal").val(parseFloat(total_detail_total).toFixed(2));

    //TOTAL
    total_detail_total = (total_detail_total - discount) + delivery_fee;
    document.getElementById("q_label_total_detail_total").innerHTML = total_detail_total.formatMoney(2,',','.');
    $("#q_total_detail_total").val(parseFloat(total_detail_total).toFixed(2));
}

function save_quotation() {

    if ($("#frm_add_quotation").valid()) {  

        var valmsg = "";

        if ($(".q_detail_qty").filter(function() { return $(this).val(); }).length <= 0) {
            valmsg = "Please add atleast one (1) product to the quotation";
        }    

        if (valmsg != "") {
            new NioApp.Toast("<h5>Error</h5><p>" + valmsg + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
        } else {
            var form = document.getElementById('frm_add_quotation');
            var formData = new FormData(form);

            $.ajax({
                type: 'POST',
                url: baseDir + 'pos/quotations/save',
                data: formData,
                contentType: false,
                processData: false, 
                cache: false,
                dataType: 'json',
                beforeSend: function(){
                    $("#btn_save_quotation").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
                },
                success: function(res){                    
                    if (res.status == 'ERR') {
                        $("#btn_save_quotation").html('<i class="ion-checkmark-circled mr-1"></i>Save Quotation');
                        new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                    } else {
                        $('#frm_add_quotation').each(function() {
                            this.reset();
                        });
                        $("#q_customer_id").val('').change();

                        $('.q_detail_remove').each(function() {
                            $(this).closest('tr').remove();
                        });

                        calculate_q_totals();

                        setTimeout(function() {
                            window.location = baseDir+'pos/quotations/view/'+res.id;
                        }, 2000);
                    }
                },
                error: function(){
                    $("#btn_save_quotation").html('<i class="ion-checkmark-circled mr-1"></i>Save Quotation');
                }
            });
        }
    }
    return false;
}

function update_quotation() {

    if ($("#frm_edit_quotation").valid()) {

        var valmsg = "";

        if ($(".q_detail_qty").filter(function() { return $(this).val(); }).length <= 0) {
            valmsg = "Please add atleast one (1) product to the quotation";
        }    

        if (valmsg != "") {
            new NioApp.Toast("<h5>Error</h5><p>" + valmsg + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
        } else {

            var form = document.getElementById('frm_edit_quotation');
            var formData = new FormData(form);

            $.ajax({
                url: baseDir + 'pos/quotations/update',
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
                    $("#btn_update_quotation").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
                },
                success: function(res) {                    
                    if (res.status == 'ERR') {
                        $("#btn_update_quotation").html('<i class="ion-checkmark-circled mr-1"></i>Update Quotation');                        
                        new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                    } else if (res.status == 'SUCCESS') {
                        new NioApp.Toast("<h5>Success</h5><p>" + res.message + "</p>", "success", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                    
                        setTimeout(function() {
                            window.location = baseDir+'pos/quotations/view/'+res.id;
                        }, 2000);
                    }
                },
                error: function() {
                    $("#btn_update_quotation").html('<i class="ion-checkmark-circled mr-1"></i>Update Quotation');
                }
            });

        }
    }
    return false;
}

function load_quotations_list() {
    var form = document.getElementById('frm_filter_quotations');
    var formData = new FormData(form);
    $.ajax({
        type: 'POST',
        url: baseDir + 'pos/quotations/load_ajax_quotations_list',
        data: formData,
        processData: false,
        contentType: false,
        beforeSend: function(){
            $("#quotations_list_loader").fadeIn("fast");
        },
        success: function(res){
            $("#quotations_list_loader").fadeOut("fast");
            $("#div_quotations_list").html(res);
        },
        error: function(){
            $("#quotations_list_loader").fadeOut("fast");
        }
    });   
}

function submit_void_pos_quotation(){
    if ($("#frm_void_pos_quotation").valid()) {

        Swal.fire({
            html: 'Do you wish to void this Quotation? Please note that this action is IRREVERSIBLE',
            icon: 'question',
            showCancelButton: !0,
            confirmButtonText: 'Yes',
            cancelButtonText: 'No',
            allowOutsideClick: false
        }).then(function(result) {
            if (result.value) {
                var form = document.getElementById('frm_void_pos_quotation');
                var formData = new FormData(form);

                $.ajax({
                    type: 'POST',
                    url: baseDir + 'pos/quotations/submit_void',
                    data: formData,
                    contentType: false,
                    processData: false, 
                    cache: false,
                    dataType: 'json',
                    beforeSend: function(){
                        $("#btn_void_pos_quotation").html('Processing <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>  <span class="sr-only">Loading...</span>');
                    },
                    success: function(res){
                        $("#btn_void_pos_quotation").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
                        if (res.status == 'ERR') {
                            new NioApp.Toast("<h5>Error</h5><p>" + res.message + "</p>", "error", { position: "top-center", progressBar: true, showDuration: "300", hideDuration: "1000", timeOut: "5000", extendedTimeOut: "1000" });
                        } else {
                            $('#modal_void_pos_quotation').modal('toggle');
                            //load_pending_quotation_info();
                            var context = $('#void_context').val();

                            if (context == 'Quotations List') {
                                load_quotations_list();
                            } else if (context == 'View Quotation') {
                                location.reload();
                            }
                        }
                    },
                    error: function(){
                        $("#btn_void_pos_quotation").html('<i class="ion-checkmark-circled mr-1"></i>Submit');
                    }
                });
            }
        });
    }

    return false;
}