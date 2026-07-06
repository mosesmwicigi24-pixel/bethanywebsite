<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">
                <form id="frm_pos_sale" name="frm_pos_sale" method="post">
                    <div id="register_container" class="sales clearfix">
                        <div class="row register">
                            <input type="hidden" id="transaction_type" name="transaction_type" value="Add">
                            <div class="col-md-12 no-padding-right no-padding-left">

                                <button class="btn btn-sm btn-round btn-dim btn-outline-light" type="button" data-toggle="collapse" data-target="#collapseItems" aria-expanded="false" aria-controls="collapseItems">Show/Hide Items <em class="icon ni ni-dot-box"></em></button>

                                <div class="collapse" id="collapseItems">

                                    <div class="row pt-2">
                                        <div class="col-md-4">
                                            <select id="sale_product_category_id" class="form-control form-control-lg select2" data-placeholder="Select Category" data-search="on" >
                                                <option value="All">All Categories</option>
                                                <?php $level_count = 0; ?>
                                                <?php foreach ($product_categories as $row): ?>
                                                    <option value="<?php echo $row->product_category_id; ?>"><?php echo $row->product_category_name; ?></option>
                                                    <?php
                                                        if(!empty($row->sub)){
                                                            fetch_sub_categories($row->sub, $level_count);
                                                        }
                                                    ?>
                                                <?php endforeach; ?>
                                                <?php
                                                    function fetch_sub_categories($sub_categories, $level_count){
                                                        $level_count = $level_count + 1;
                                                        foreach($sub_categories as $sub_category){
                                                            $mdash = '';
                                                            $mspace = '';
                                                            for($i = 0; $i < $level_count; $i++){$mdash = $mdash . '&mdash;'; $mspace = $mspace . '&nbsp;&nbsp;';}
                                                            echo '<option value="' . $sub_category->product_category_id . '">' . $mspace . $mdash . ' ' . $sub_category->product_category_name . '</option>';
                                                            if(!empty($sub_category->sub)){
                                                                fetch_sub_categories($sub_category->sub, $level_count);
                                                            }
                                                        }
                                                    }
                                                ?>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="box-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <section style="height: 160px;">
                                                    <div class="spinner display-none" id="sale_products_loader">
                                                        <div class="rect1"></div>
                                                        <div class="rect2"></div>
                                                        <div class="rect3"></div>
                                                    </div>
                                                    <div id="div_sale_products" class="row search_div" style="overflow-y: scroll; min-height: 160px; height: 150px;">                                                    
                                                    </div>
                                                </section>
                                            </div>
                                        </div>
                                    </div>

                                </div>


                            </div>

                            <div class="col-md-12">
                                <div class="row" id="div_sale_body">

                                    <div class="col-lg-8 col-md-7 col-sm-12 col-xs-12 no-padding-right no-padding-left">
                                        <div class="spinner display-none" id="details_section_loader">
                                            <div class="rect1"></div>
                                            <div class="rect2"></div>
                                            <div class="rect3"></div>
                                        </div>
                                        <div class="register-box register-items-form">
                                            <a tabindex="-1" href="javascript:;" class="dismissfullscreen hidden"><i class="ion-close-circled"></i></a>
                                            <div id="itemForm" class="item-form form-group">
                                                <div class="input-group contacts register-input-group">
                                                    <div class="spinner" id="ajax-loader" style="display: none;">
                                                        <div class="rect1"></div>
                                                        <div class="rect2"></div>
                                                        <div class="rect3"></div>
                                                    </div>                                                    

                                                    <div class="input-group-addon text-white">
                                                        <em class="icon ni ni-search"></em>
                                                    </div>

                                                    <input type="text" id="txt_sale_product_search" name="item" class="form-control add-item-input pull-left keyboardTop ui-autocomplete-input" placeholder="Search product by name, sku or barcode" data-title="Item Name" autocomplete="off" />

                                                    <div id="sale_products_suggestion" class="display-none"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- Register Items. @contains : Items table -->
                                        <div class="register-box register-items paper-cut">
                                            <div class="register-items-holder table-responsive">
                                                <table id="register" class="table table-hover">
                                                    <thead>
                                                        <tr class="register-items-header bg-lighter">
                                                            <th></th>
                                                            <th class="item_name_heading fw-bold">Item Name</th>
                                                            <th class="sales_price fw-bold">Price</th>
                                                            <th class="sales_quantity fw-bold">Quantity</th>
                                                            <th class="sales_discount fw-bold">Discount</th>
                                                            <th class="sales_discount fw-bold">Tax</th>
                                                            <th class=" fw-bold">Subtotal</th>
                                                            <th class=" fw-bold">Action</th>
                                                        </tr>
                                                    </thead>

                                                    <tbody class="register-item-content">
                                                        
                                                        <?php if ($num_pending_sale > 0): ?>
                                                            <?php foreach ($pending_sale as $row): ?>
                                                                <?php if(!empty($row->details)): ?>
                                                                    <?php foreach ($row->details as $row2): ?>
                                                                        <?php
                                                                            $product_image = '';
                                                                            if ($row2->product_image_thumb != '' && file_exists("./uploads/product_images/thumbs/" . $row2->product_image_thumb)) {
                                                                                $product_image = base_url() . 'uploads/product_images/thumbs/' . $row2->product_image_thumb;
                                                                            } else {
                                                                                $product_image = base_url() . 'assets/fe/img/placeholder.png';
                                                                            }
                                                                        ?>
                                                                        <tr class="register-item-details">
                                                                            <td>
                                                                                <img src="<?php echo $product_image; ?>" style="max-width:64px;" class="img-thumbnail">
                                                                            </td>
                                                                            <td>
                                                                                <a href="javascript:;" data-toggle="modal" data-target="#modal_modify_sale_product" data-product-id="<?php echo $row2->product_id; ?>" data-product-variation-id="<?php echo $row2->product_variation_id; ?>" data-pos-sale-id="<?php echo $row2->pos_sale_id; ?>" data-pos-sale-detail-id="<?php echo $row2->pos_sale_detail_id; ?>" class="register-item-name lnk-modify-sale-product">
                                                                                    <?php echo $row2->product_name; ?><br>
                                                                                    <?php if(!empty($row2->attributes)): ?>
                                                                                        <?php
                                                                                            $variation_description = '';
                                                                                            foreach ($row2->attributes as $row3){
                                                                                                $variation_description = $variation_description . $row3->product_attribute_name . ' : <b>' . $row3->product_attribute_value . '</b>, ';
                                                                                            }
                                                                                            echo '<small><i class="badge badge-mark ml-2"></i> ' . substr($variation_description,0,-2) . '</small>';
                                                                                        ?><br>
                                                                                    <?php endif; ?>
                                                                                    <small><span class="text-muted"><strong>SUK: </strong><?php echo $row2->product_sku_code; ?></span></small>
                                                                                    <?php if ($row2->unit_name != ''): ?>
                                                                                        <br><small><span class="text-muted"><strong>Unit: </strong><?php echo $row2->unit_name . '(' . $row2->unit_code . ')'; ?></span></small>
                                                                                    <?php endif; ?>
                                                                                </a>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <a
                                                                                    href="javascript:;" data-toggle="modal" data-target="#modal_modify_sale_product" data-product-id="<?php echo $row2->product_id; ?>" data-product-variation-id="<?php echo $row2->product_variation_id; ?>" data-pos-sale-id="<?php echo $row2->pos_sale_id; ?>" data-pos-sale-detail-id="<?php echo $row2->pos_sale_detail_id; ?>"
                                                                                    class="xeditable xeditable-price editable editable-click lnk-modify-sale-product"
                                                                                    data-pk="1"
                                                                                >
                                                                                    <?php echo $default_currency; ?> <?php echo number_format($row2->unit_price,2); ?>
                                                                                </a>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <a
                                                                                    href="javascript:;" data-toggle="modal" data-target="#modal_modify_sale_product" data-product-id="<?php echo $row2->product_id; ?>" data-product-variation-id="<?php echo $row2->product_variation_id; ?>" data-pos-sale-id="<?php echo $row2->pos_sale_id; ?>" data-pos-sale-detail-id="<?php echo $row2->pos_sale_detail_id; ?>"
                                                                                    class="xeditable editable editable-click lnk-modify-sale-product"
                                                                                    data-pk="1"
                                                                                >
                                                                                    <?php echo number_format($row2->quantity,2); ?>
                                                                                </a>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <a
                                                                                    href="javascript:;" data-toggle="modal" data-target="#modal_modify_sale_product" data-product-id="<?php echo $row2->product_id; ?>" data-product-variation-id="<?php echo $row2->product_variation_id; ?>" data-pos-sale-id="<?php echo $row2->pos_sale_id; ?>" data-pos-sale-detail-id="<?php echo $row2->pos_sale_detail_id; ?>"
                                                                                    class="xeditable editable editable-click lnk-modify-sale-product"
                                                                                    data-pk="1"
                                                                                >
                                                                                    <?php echo $default_currency; ?> <?php echo number_format($row2->discount_amount,2); ?>
                                                                                </a>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <?php echo $default_currency; ?> <?php echo number_format($row2->tax_amount,2); ?>
                                                                            </td>
                                                                            <td class="text-center font-weight-bold">
                                                                                <?php echo $default_currency; ?> <?php echo number_format($row2->sub_total,2); ?>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <a href="javascript:;" data-toggle="modal" data-target="#modal_modify_sale_product" data-product-id="<?php echo $row2->product_id; ?>" data-product-variation-id="<?php echo $row2->product_variation_id; ?>" data-pos-sale-id="<?php echo $row2->pos_sale_id; ?>" data-pos-sale-detail-id="<?php echo $row2->pos_sale_detail_id; ?>" class="delete-item text-info lnk-modify-sale-product"><em class="icon ni ni-edit"></em> </a>
                                                                                <a href="javascript:void(0);" data-product-id="<?php echo $row2->product_id; ?>" data-product-variation-id="<?php echo $row2->product_variation_id; ?>" data-pos-sale-id="<?php echo $row2->pos_sale_id; ?>" data-pos-sale-detail-id="<?php echo $row2->pos_sale_detail_id; ?>" class="delete-item remove_pos_sale_item" tabindex="-1"><i class="icon ion-android-cancel"></i></a>
                                                                            </td>
                                                                        </tr>

                                                                    <?php endforeach; ?>
                                                                <?php else: ?>
                                                                    <tr class="cart_content_area">
                                                                        <td colspan="7">
                                                                            <div class="text-center p-1">
                                                                                <h6 class=" text-warning">There are no items in the cart</h6>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <tr class="cart_content_area">
                                                                <td colspan="7">
                                                                    <div class="text-center p-1">
                                                                        <h6 class=" text-warning">There are no items in the cart</h6>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-4 col-md-5 col-sm-12 col-xs-12">
                                        <div class="spinner display-none" id="totals_section_loader">
                                            <div class="rect1"></div>
                                            <div class="rect2"></div>
                                            <div class="rect3"></div>
                                        </div>

                                        <div id="div_sale_type_info" class="register-box register-right p-2 pl-3">
                                            <?php if ($num_pending_sale > 0): ?>
                                                <?php foreach ($pending_sale as $row): ?>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <h4 class="text-center mt-1"><?php echo $row->sale_type; ?> <span class="font-size-normal"><a href="javascript:;" id="lnk_change_sale_type" class="xeditable editable editable-click editable-empty">Change</a></span></h4>
                                                            <div class="comment-block text-center">
                                                                <a href="javascript:;" id="lnk_sale_date" class="xeditable editable editable-click editable-empty"><em class="icon ni ni-calendar mr-1"></em><?php if ($row->sale_date == ''){ echo 'Sale Date: <b>' . date('Y-m-d') . '</b>'; } else { echo 'Sale Date: <b>' . $row->sale_date . '</b>'; } ?></a>
                                                            </div>
                                                            <?php if ($row->sale_type == 'CREDIT SALE'): ?>
                                                                <div class="comment-block text-center">Payment Terms: <b><?php echo $row->credit_term; ?> [<?php echo $row->credit_days; ?> Days]</b></div>
                                                                <div class="comment-block text-center">Due Date: <b><?php echo $row->credit_due_date; ?></b></div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <h4 class="text-center mt-1">CASH SALE <span class="font-size-normal"><a href="javascript:;" id="lnk_change_sale_type" class="xeditable editable editable-click editable-empty">Change</a></span></h4>
                                                    </div>
                                                </div>
                                                <div class="comment-block text-center">
                                                    <a href="javascript:;" id="lnk_sale_date" class="xeditable editable editable-click editable-empty"><em class="icon ni ni-calendar mr-1"></em><?php echo 'Sale Date: <b>' . date('Y-m-d') . '</b>'; ?></a>
                                                </div>
                                            <?php endif; ?>
                                        </div>


                                        <div id="div_sale_customer_info" class="register-box register-right">

                                            <!-- If customer is added to the sale -->
                                            <?php if ($num_pending_sale > 0): ?>
                                                <?php foreach ($pending_sale as $row): ?>
                                                    <?php if ($row->customer_id != 0): ?>
                                                        <?php
                                                            $customer_profile_picture = '';
                                                            if ($row->profile_picture_thumb != '' && file_exists("./uploads/customer_profile_pictures/thumbs/" . $row->profile_picture_thumb)) {
                                                                $customer_profile_picture = base_url() . 'uploads/customer_profile_pictures/thumbs/' . $row->profile_picture_thumb;
                                                            } else {
                                                                $customer_profile_picture = base_url() . 'assets/pos/images/user.png';
                                                            }
                                                        ?>
                                                        <div class="customer-badge">
                                                            <div class="avatar">
                                                                <img src="<?php echo $customer_profile_picture; ?>" alt="" />
                                                            </div>
                                                            <div class="details">
                                                                <a href="javascript:;" class="name"><?php echo $row->first_name . ' ' . $row->last_name; ?></a>                                    

                                                                <span class="email">
                                                                    <a><i class="ion-ios-telephone pr-1"></i><?php echo $row->phone_number; ?></a>
                                                                </span>
                                                                <br>
                                                                <span class="email">
                                                                    <a href="mailto:<?php echo $row->email_address; ?>"><i class="ion-email pr-1"><?php echo $row->email_address; ?></i></a>
                                                                </span>

                                                                <a href="<?php echo base_url(); ?>pos/sales/customer_edit/<?php echo $row->customer_id; ?>" id="edit_customer" class="btn btn-edit btn-primary pull-right" title="Edit Customer"><i class="ti-pencil-alt"></i></a>
                                                            </div>
                                                        </div>

                                                        <div class="customer-action-buttons btn-group btn-group-justified">
                                                            <a href="javascript:;" id="btn_detatch_customer" data-pos-sale-id="<?php echo $row->pos_sale_id; ?>" class="btn"><i class="ion-close-circled"></i> Detach Customer</a>
                                                        </div>
                                                    <?php elseif ($row->customer_name != ''): ?>
                                                        <?php if ($row->sale_type == 'CASH SALE'): ?>
                                                            <div class="customer-badge">
                                                                <div class="details pl-0">
                                                                    <a href="javascript:;" class="name">Customer Name: <big class="text-info"><strong><?php echo $row->customer_name; ?></strong></big></a>                                    
                                                                    <a href="javascript:;" class="btn btn-edit btn-primary pull-right lnk_enter_customer_name" title="Edit Customer Name"><i class="ti-pencil-alt"></i></a>
                                                                </div>
                                                            </div>

                                                            <div class="customer-action-buttons btn-group btn-group-justified">
                                                                <a href="javascript:;" id="btn_remove_customer_name" data-pos-sale-id="<?php echo $row->pos_sale_id; ?>" class="btn"><i class="ion-close-circled"></i> Remove</a>
                                                            </div>
                                                        <?php else: ?>
                                                            <div class="customer-form form-group">
                                                                <div class="input-group contacts register-input-group">
                                                                    <div class="input-group-addon">
                                                                        <a href="javascript:;" class="lnk_add_customer none" title="New Customer" id="new-customer" tabindex="-1"><i class="ion-person-add"></i></a>
                                                                    </div>
                                                                    <input type="text" id="txt_sale_customer_search" name="customer" class="form-control add-customer-input keyboardLeft ui-autocomplete-input" data-title="Customer Name" placeholder="Search customer by name, email or phone..." autocomplete="off">

                                                                </div>
                                                                <div id="sale_customers_suggestion" class="display-none"></div>

                                                                <div class="mt-1">
                                                                    <div class="text-center"> - OR - </div>
                                                                    <a href="javascript:;" class="btn btn-sm btn-outline-info mt-1 mb-1 lnk_add_customer"><i class="ion-person mr-1"></i>Add Customer</a>
                                                                </div>
                                                            </div>
                                                        <?php endif; ?>                                                        
                                                    <?php else: ?>
                                                        <div class="customer-form form-group">
                                                            <div class="input-group contacts register-input-group">
                                                                <div class="input-group-addon">
                                                                    <a href="javascript:;" class="lnk_add_customer none" title="New Customer" id="new-customer" tabindex="-1"><i class="ion-person-add"></i></a>
                                                                </div>
                                                                <input type="text" id="txt_sale_customer_search" name="customer" class="form-control add-customer-input keyboardLeft ui-autocomplete-input" data-title="Customer Name" placeholder="Search customer by name, email or phone..." autocomplete="off">

                                                            </div>
                                                            <div id="sale_customers_suggestion" class="display-none"></div>

                                                            <div class="mt-1">
                                                                <div class="text-center"> - OR - </div>
                                                                <a href="javascript:;" class="btn btn-sm btn-outline-info mt-1 mb-1 lnk_add_customer"><i class="ion-person mr-1"></i>Add Customer</a>
                                                                <?php if ($row->sale_type == 'CASH SALE'): ?>
                                                                    <a href="javascript:;" class="btn btn-sm btn-outline-info mt-1 mb-1 lnk_enter_customer_name"><i class="ion-person mr-1"></i>Enter Customer Name</a>
                                                                <?php endif; ?>
                                                            </div>
                                                            
                                                        </div>
                                                        
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <div id="div_sale_search_customer" class="customer-form form-group">
                                                    <div class="input-group contacts register-input-group">
                                                        <div class="input-group-addon">
                                                            <a href="javascript:;" class="lnk_add_customer none" title="New Customer" id="new-customer" tabindex="-1"><i class="ion-person-add"></i></a>
                                                        </div>
                                                        <input type="text" id="txt_sale_customer_search" name="customer" class="form-control add-customer-input keyboardLeft ui-autocomplete-input" data-title="Customer Name" placeholder="Search customer by name, email or phone..." autocomplete="off" />
                                                    </div>
                                                    <div id="sale_customers_suggestion" class="display-none"></div>

                                                    <div class="mt-1">
                                                        <div class="text-center"> - OR - </div>
                                                        <a href="javascript:;" class="btn btn-sm btn-outline-info mt-1 mb-1 lnk_add_customer"><i class="ion-person mr-1"></i>Add Customer</a>
                                                        <a href="javascript:;" class="btn btn-sm btn-outline-info mt-1 mb-1 lnk_enter_customer_name"><i class="ion-person mr-1"></i>Enter Customer Name</a>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                        </div>

                                        <div class="register-box register-summary paper-cut">
                                            <!-- Tiers if its greater than 1 -->

                                            <!-- Tiers if its greater than 1 -->
                                            <?php if ($num_pending_sale > 0): ?>
                                                <?php foreach ($pending_sale as $row): ?>
                                                    <ul class="list-group">
                                                        <li class="list-group-item global-discount-group">
                                                            <div class="key">Total Quantity:</div>
                                                            <div class="value pull-right"><?php echo number_format($row->total_quantity,2); ?></div>
                                                        </li>
                                                        <li class="sub-total list-group-item">
                                                            <span class="key">Sub Total:</span>
                                                            <span class="value"><?php echo $default_currency; ?> <?php echo number_format($row->sub_total,2); ?></span>
                                                        </li>
                                                        <li class="list-group-item global-discount-group">
                                                            <div class="key">Discount:</div>
                                                            <div class="value pull-right">
                                                                <a
                                                                    href="javascript:;"
                                                                    id="sale_overall_discount"
                                                                    class="xeditable editable editable-click editable-empty"
                                                                ><?php echo $default_currency; ?> <?php echo number_format($row->overall_discount,2); ?></a>
                                                            </div>
                                                        </li>
                                                        
                                                        <li class="list-group-item global-discount-group">
                                                            <div class="key">Delivery Fee:</div>
                                                            <div class="value pull-right">
                                                                <a
                                                                    href="javascript:;"
                                                                    id="sale_delivery_fee"
                                                                    class="xeditable editable editable-click editable-empty"
                                                                ><?php echo $default_currency; ?> <?php echo number_format($row->delivery_fee,2); ?></a>
                                                            </div>
                                                        </li>

                                                        
                                                    </ul>

                                                    <div class="comment-block">
                                                        <big><a href="javascript:;" id="lnk_sale_comments" class="xeditable editable editable-click editable-empty"><em class="icon ni ni-comments mr-1"></em>Comments</a></big>
                                                        <p id="sale_comments" class="mt-1"><?php echo nl2br($row->comments); ?></p>
                                                    </div>

                                                    <div class="amount-block">
                                                        <div class="total amount">
                                                            <div class="side-heading">
                                                                Total Sale
                                                            </div>
                                                            <div class="amount total-amount" data-speed="1000">
                                                                <?php echo $default_currency; ?> <?php echo number_format($row->total_sale,2); ?>
                                                            </div>
                                                        </div>
                                                        <div class="total amount-due">
                                                            <div class="side-heading">
                                                                Amount Due
                                                            </div>
                                                            <div class="amount">
                                                                <?php if ($row->total_paid >= $row->total_sale): ?>
                                                                    <?php echo $default_currency; ?> <?php echo number_format(0,2); ?>
                                                                <?php else: ?>
                                                                    <?php echo $default_currency; ?> <?php echo number_format($row->total_sale - $row->total_paid,2); ?>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <ul class="list-group">
                                                    <li class="list-group-item global-discount-group">
                                                        <div class="key">Total Quantity:</div>
                                                        <div class="value pull-right">0</div>
                                                    </li>
                                                    <li class="sub-total list-group-item">
                                                        <span class="key">Sub Total:</span>
                                                        <span class="value">
                                                            
                                                                KES 0.00
                                                            
                                                        </span>
                                                    </li>
                                                    <li class="list-group-item global-discount-group">
                                                        <div class="key">Discount:</div>
                                                        <div class="value pull-right">
                                                            <a
                                                                href="javascript:;"
                                                                id="sale_overall_discount"
                                                                class="xeditable editable editable-click editable-empty"
                                                            >
                                                                KES 0.00
                                                            </a>
                                                        </div>
                                                    </li>
                                                    
                                                    <li class="list-group-item global-discount-group">
                                                        <div class="key">Delivery Fee:</div>
                                                        <div class="value pull-right">
                                                            <a
                                                                href="javascript:;"
                                                                id="sale_delivery_fee"
                                                                class="xeditable editable editable-click editable-empty"
                                                            >
                                                                KES 0.00
                                                            </a>
                                                        </div>
                                                    </li>

                                                    
                                                </ul>

                                                <div class="comment-block">
                                                    <big><a href="javascript:;" id="lnk_sale_comments" class="xeditable editable editable-click editable-empty"><em class="icon ni ni-comments mr-1"></em>Comments</a></big>
                                                    <p id="sale_comments" class="mt-1"></p>
                                                </div>

                                                <div class="amount-block">
                                                    <div class="total amount">
                                                        <div class="side-heading">
                                                            Total Sale
                                                        </div>
                                                        <div class="amount total-amount" data-speed="1000" data-currency="$" data-decimals="2">
                                                            KES 0.00
                                                        </div>
                                                    </div>
                                                    <div class="total amount-due">
                                                        <div class="side-heading">
                                                            Amount Due
                                                        </div>
                                                        <div class="amount">
                                                            KES 0.00
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endif; ?>
                                            

                                            <?php if ($num_pending_sale > 0): ?>
                                                <?php foreach ($pending_sale as $row): ?>
                                                    <?php if(!empty($row->payments)): ?>
                                                        <ul class="list-group payments mb-0">
                                                            <div class="side-heading font-weight-bold">
                                                                Payments:
                                                            </div>
                                                            <?php foreach ($row->payments as $row2): ?>
                                                                <li class="list-group-item">
                                                                    <span class="key">
                                                                        <a href="javascript:;" class="delete-payment remove text-info lnk_modify_sale_payment" data-pos-payment-id="<?php echo $row2->pos_payment_id; ?>" data-toggle="tooltip" data-placement="top" title="Modify Payment"><i class="icon ti-pencil-alt"></i></a>
                                                                        <a href="javascript:;" class="delete-payment remove lnk_void_sale_payment" data-pos-payment-id="<?php echo $row2->pos_payment_id; ?>" data-toggle="tooltip" data-placement="top" title="Void Payment"><i class="icon ni ni-trash-alt"></i></a>
                                                                         <?php echo $row2->payment_method; ?> <small>[<?php echo date('d/m/Y', strtotime($row2->created_on)); ?>]</small>
                                                                    </span>
                                                                    <span class="value"> <?php echo $default_currency; ?> <?php echo number_format($row2->payment_amount,2); ?> </span>
                                                                </li>
                                                            <?php endforeach; ?>
                                                        </ul>
                                                    <?php endif; ?>
                                                <?php endforeach; ?>
                                            <?php endif; ?>

                                            
                                            <div class="sale_controls">
                                                <button type="button" id="btn_make_payment" class="btn btn-info mt-1 mb-1 ml-1">
                                                    <i class="ion-social-usd mr-1"></i>Pay
                                                </button>
                                                <button type="button" id="btn_complete_sale" class="btn btn-success mt-1 mb-1 ml-1">
                                                    <i class="ion-checkmark-circled mr-1"></i>Complete
                                                </button>
                                                <button type="button" id="btn_hold_sale" class="btn btn-warning mt-1 mb-1 ml-1">
                                                    <i class="ion-pause mr-1"></i>Hold Sale
                                                </button>
                                                <button type="button" id="btn_cancel_sale" class="btn btn-danger btn-cancel mt-1 mb-1 ml-1" id="cancel_sale_button">
                                                    <i class="ion-close-circled"></i>Cancel
                                                </button>
                                            </div>

                                            <input type="hidden" id="pos_sale_id" name="pos_sale_id">
                                            <input type="hidden" id="customer_id" name="customer_id">

                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="modal_select_product_variation" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-primary"><i class="icon-plus-circle2"></i> Select Product Variation</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" style="min-height: 200px;">
                <div id="div_select_product_variation">
                    
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modal_set_sale_date">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><em class="icon ni ni-date"></em> Set Sale Date <small>(YYYY-MM-DD)</small></h6>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <div class="modal-body">
                <div class="spinner display-none" id="set_sale_date_loader">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                </div>
                <form id="frm_set_sale_date" name="frm_set_sale_date" method="post" class="is-alter" onsubmit="return submit_sale_date();">

                    <input type="hidden" id="saledate_pos_sale_id" name="pos_sale_id">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid bg-lighter">
                                <div class="box-body">
                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="text" id="saledate_sale_date" name="sale_date" class="form-control datepicker" placeholder="Sale Date">
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>

                                
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" id="btn_set_sale_date" class="btn btn-success"><i class="ion-checkmark-circled mr-1"></i>Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modal_modify_sale_product">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><em class="icon ni ni-edit"></em> Modify Sales Item</h6>
                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <div class="modal-body">
                <div class="spinner display-none" id="modify_sale_loader">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                </div>
                <form id="frm_modify_sales_item" name="frm_modify_sales_item" method="post" class="is-alter" onsubmit="return submit_modify_sales_item();">

                    <input type="hidden" id="mod_product_id" name="product_id">
                    <input type="hidden" id="mod_product_variation_id" name="product_variation_id">
                    <input type="hidden" id="mod_pos_sale_id" name="pos_sale_id">
                    <input type="hidden" id="mod_pos_sale_detail_id" name="pos_sale_detail_id">

                    <div class="row">
                        <div class="col-md-12 mb-2">
                            <div class="row invoice-info">
                                <div class="col-sm-3">
                                    <img id="mod_product_image" src="<?php echo base_url(); ?>assets/fe/img/placeholder.png" style="max-width:100px;" class="img-thumbnail">
                                </div>
                                <div class="col-sm-9">
                                    <span id="mod_product_name" class="font-weight-bold text-info"></span><br>
                                    <span id="mod_product_variation_description" class="font-weight-bold text-info"></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12">
                            
                            <div class="box box-solid bg-lighter">
                                <div class="box-body">
                                    <div class="row">
                                        <input type="hidden" id="mod_current_unit_id" name="current_unit_id">
                                        <input type="hidden" id="mod_current_unit_price" name="current_unit_price">
                                        <input type="hidden" id="mod_base_unit_id" name="base_unit_id">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="mod_unit_id">Unit of Measure</label>
                                                <select class="form-control select2" data-placeholder="Select Unit of Measure" id="mod_unit_id" name="unit_id" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                                    
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group display-none" id="div_mod_conversion_factor">
                                                <label for="mod_conversion_factor">Conversion Factor</label>
                                                <input type="number" class="form-control" id="mod_conversion_factor" name="conversion_factor" min="0"step="any" step="any" readonly required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="mod_unit_price">Unit Price</label>                                                
                                                <input type="number" class="form-control" id="mod_unit_price" name="unit_price" min="0"step="any" step="any" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="mod_quantity">Quantity</label>
                                                <input type="number" class="form-control" id="mod_quantity" name="quantity" step="any" min="0" step="any" required>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="mod_discount_type">Discount Type</label>
                                                <select class="form-control select2" data-placeholder="Select Discount Type" id="mod_discount_type" name="discount_type" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                                    <option value="Percentage">Percentage(%)</option>
                                                    <option value="Fixed">Fixed(<?php echo $default_currency; ?>)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="mod_discount_value">Discount</label>
                                                <input type="number" class="form-control only_currency" id="mod_discount_value" name="discount_value" placeholder="" value="0" step="any" required />
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>

                                
                        </div>
                        <!-- col-md-9 -->
                        <!-- RIGHT HAND -->
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" id="btn_modify_sale_item" class="btn btn-success"><i class="ion-checkmark-circled mr-1"></i>Update</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                    </div>
                </form>
            </div>
            <!-- <div class="modal-footer bg-light"><span class="sub-text">Modal Footer Text</span></div> -->
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modal_sale_add_customer">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><em class="icon ni ni-plus-c"></em> New Customer</h6>
                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <div class="modal-body">
                <div class="spinner display-none" id="sale_add_customer_loader">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                </div>
                <form id="frm_sale_add_customer" name="frm_sale_add_customer" method="post" class="is-alter" onsubmit="return submit_sale_add_customer();">

                    <input type="hidden" id="mod_ac_pos_sale_id" name="pos_sale_id">
                    <input type="hidden" id="mod_ac_transaction_type" name="transaction_type">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid bg-lighter">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="customer_first_name">First Name<span class="text-danger">*</span></label>
                                                <input type="text" class="form-control" id="customer_first_name" name="first_name" required>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="customer_last_name">Last Name</label>
                                                <input type="text" class="form-control" id="customer_last_name" name="last_name">
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="customer_email_address">Email Address</label>                                                
                                                <input type="email" class="form-control" id="customer_email_address" name="email_address">
                                            </div>
                                        </div>

                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="customer_phone_number">Phone Number<span class="text-danger">*</span> <small>(Format: 0700706875)</small></label>
                                                <input type="text" class="form-control" id="customer_phone_number" name="phone_number" required>
                                            </div>
                                        </div>

                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                                
                        </div>
                        <!-- col-md-9 -->
                        <!-- RIGHT HAND -->
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" id="btn_sale_add_customer" class="btn btn-success"><i class="ion-checkmark-circled mr-1"></i>Save Customer</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modal_change_sale_type">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><em class="icon ni ni-date"></em> Change Sale Type</h6>
                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <div class="modal-body">
                <div class="spinner display-none" id="change_sale_type_loader">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                </div>
                <form id="frm_change_sale_type" name="frm_change_sale_type" method="post" class="is-alter" onsubmit="return submit_change_sale_type();">

                    <input type="hidden" id="saletype_pos_sale_id" name="pos_sale_id">
                    <input type="hidden" id="saletype_transaction_type" name="transaction_type" value="Add">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid bg-lighter">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="sale_type">Sale Type</label>
                                                <select class="form-control select2" data-placeholder="Select Sale Type" id="mod_sale_type" name="sale_type" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                                    <option value="">Select Sale Type</option>
                                                    <option value="CASH SALE">CASH SALE</option>
                                                    <option value="CREDIT SALE">CREDIT SALE</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-12" id="div_select_payment_term">
                                            <div class="form-group">
                                                <label for="credit_term_id">Payment Terms</label>
                                                <select class="form-control select2" data-placeholder="Select Payment Term" id="mod_credit_term_id" name="credit_term_id" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                                    <option value="">Select Payment Term</option>
                                                    <?php foreach($credit_terms as $row2): ?>
                                                        <option value="<?php echo $row2->credit_term_id; ?>"><?php echo $row2->credit_term . ' [' . $row2->credit_term_days . ' Days]'; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                   

                    
                    <div class="form-group text-right">
                        <button type="submit" id="btn_change_sale_type" class="btn btn-success"><i class="ion-checkmark-circled mr-1"></i>Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade" tabindex="-1" id="modal_set_overall_discount">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><em class="icon ti ti-gift"></em> Set Discount</h6>
                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <div class="modal-body">
                <div class="spinner display-none" id="set_overall_discount_loader">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                </div>
                <form id="frm_set_overall_discount" name="frm_set_overall_discount" method="post" class="is-alter" onsubmit="return submit_sale_overall_discount();">

                    <input type="hidden" id="od_pos_sale_id" name="pos_sale_id">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid bg-lighter">
                                <div class="box-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="mod_discount_type">Discount Type</label>
                                                <select class="form-control select2" data-placeholder="Select Discount Type" id="od_discount_type" name="overall_discount_type" style="width: 100%;" tabindex="-1" aria-hidden="true" required>
                                                    <option value="Percentage">Percentage(%)</option>
                                                    <option value="Fixed">Fixed(<?php echo $default_currency; ?>)</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="mod_discount_value">Discount</label>
                                                <input type="number" class="form-control only_currency" id="od_discount_value" name="overall_discount_value" placeholder="" step="any" value="0" required />
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>

                                
                        </div>
                        <!-- col-md-9 -->
                        <!-- RIGHT HAND -->
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" id="btn_set_overall_discount" class="btn btn-success"><i class="ion-checkmark-circled mr-1"></i>Set Discount</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                    </div>
                </form>
            </div>
            <!-- <div class="modal-footer bg-light"><span class="sub-text">Modal Footer Text</span></div> -->
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modal_set_delivery_fee">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><em class="icon ti ti-truck"></em> Set Delivery Fee</h6>
                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <div class="modal-body">
                <div class="spinner display-none" id="set_delivery_fee_loader">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                </div>
                <form id="frm_set_delivery_fee" name="frm_set_delivery_fee" method="post" class="is-alter" onsubmit="return submit_sale_delivery_fee();">

                    <input type="hidden" id="del_pos_sale_id" name="pos_sale_id">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid bg-lighter">
                                <div class="box-body">
                                    <div class="row mt-1">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="mod_discount_type">Delivery Fee</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="number" class="form-control only_currency" id="del_delivery_fee" name="delivery_fee" placeholder="" step="any" value="0" required />
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>

                                
                        </div>
                        <!-- col-md-9 -->
                        <!-- RIGHT HAND -->
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" id="btn_set_delivery_fee" class="btn btn-success"><i class="ion-checkmark-circled mr-1"></i>Set Delivery Fee</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                    </div>
                </form>
            </div>
            <!-- <div class="modal-footer bg-light"><span class="sub-text">Modal Footer Text</span></div> -->
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modal_set_sale_comments">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><em class="icon ni ni-comments"></em> Set Comments</h6>
                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <div class="modal-body">
                <div class="spinner display-none" id="set_sale_comments_loader">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                </div>
                <form id="frm_set_sale_comments" name="frm_set_sale_comments" method="post" class="is-alter" onsubmit="return submit_sale_comments();">

                    <input type="hidden" id="comm_pos_sale_id" name="pos_sale_id">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid bg-lighter">
                                <div class="box-body">
                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="chk_default_comments">
                                                    <input type="checkbox" id="chk_default_comments" name="default_comments"> Use Default Comments
                                                </label>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <textarea id="comm_default_comments" name="default_comments" hidden></textarea>
                                                <textarea name="comments" cols="40" rows="5" id="comm_comments" class="form-control" data-title="Comments" placeholder="Comments"></textarea>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>                                
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" id="btn_set_sale_comments" class="btn btn-success"><i class="ion-checkmark-circled mr-1"></i>Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade in" id="modal_sale_payment">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header header-custom">
                <h6 class="modal-title text-center"><em class="icon ni ni-cc-alt mr-1"></em>Make Payment <span id="payment_header_pos_sale_number"></span></h6>
                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <form id="frm_sale_make_payment" name="frm_sale_make_payment" method="post" class="is-alter" onsubmit="return submit_sale_payment();">
                <div class="modal-body">
                    <div class="spinner display-none" id="sale_make_payment_loader">
                        <div class="rect1"></div>
                        <div class="rect2"></div>
                        <div class="rect3"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-7">
                            <div>

                                <input type="hidden" id="payment_pos_sale_id" name="pos_sale_id">
                                <input type="hidden" id="payment_pos_sale_number" name="pos_sale_number">
                                <input type="hidden" id="payment_context" name="context">

                                <div class="col-md-12 payments_div">
                                    <div class="box box-solid bg-lighter">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sale_payment_method">Payment Method</label>
                                                        <select class="form-control select2" data-placeholder="Select Payment Method"  id="sale_payment_method" name="payment_method">
                                                            <option value="">Select Payment Method</option>
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
                                                        <label for="sale_payment_amount">Amount</label>
                                                        <input type="number" class="form-control" id="sale_payment_amount" name="payment_amount" step="any" min="0">
                                                    </div>
                                                </div>
                                                <div id="div_sale_payment_mpesa_btns" class="display-none">
                                                    <div class="col-md-6">
                                                        <button id="btn_sale_payment_mpesa_instructions" type="button" tabindex="0"  class="badge badge-sm badge-dot has-bg badge-info" data-toggle="popover" data-placement="top" title="MPESA Payment Instructions" data-content="
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
                                                        <button id="btn_sale_make_payment_select" type="button" class="badge badge-sm badge-dot has-bg badge-secondary d-none d-mb-inline-flex">Select Transaction <i class="ion-load-c icon-spinner display-none" id="select_mpesa_payment_loader"></i></button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="div_sale_payment_reference_number">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label id="lbl_payment_reference_number" for="sale_payment_reference_number">Reference #</label>
                                                        <input type="text" class="form-control" id="sale_payment_reference_number" name="reference_number" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="payment_note_1">Payment Note</label>
                                                        <textarea type="text" class="form-control" id="sale_payment_note" name="payment_note" placeholder=""></textarea>
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
                                            <div class="row">
                                                <div class="col-md-12 border-custom-bottom">
                                                    <span class="col-md-6 text-right text-bold">Subtotal:</span>
                                                    <span class="col-md-6 text-right text-bold custom-font-size" id="div_sale_subtotal">KES 0.00</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 border-custom-bottom">
                                                    <span class="col-md-6 text-right text-bold">Discount:</span>
                                                    <span class="col-md-6 text-right text-bold custom-font-size" id="div_sale_overall_discount">KES 0.00</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 border-custom-bottom">
                                                    <span class="col-md-6 text-right text-bold">Delivery Fee:</span>
                                                    <span class="col-md-6 text-right text-bold custom-font-size" id="div_sale_delivery_fee">KES 0.00</span>
                                                </div>
                                            </div>
                                            <div class="row bg-red">
                                                <div class="col-md-12 border-custom-bottom">
                                                    <span class="col-md-6 text-right text-bold">Total Payable:</span>
                                                    <span class="col-md-6 text-right text-bold custom-font-size" id="div_sale_total_sale">KES 0.00</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 border-custom-bottom">
                                                    <span class="col-md-6 text-right text-bold">Total Paid:</span>
                                                    <span class="col-md-6 text-right text-bold custom-font-size" id="div_sale_total_paid">KES 0.00</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 border-custom-bottom">
                                                    <span class="col-md-6 text-right text-bold">Balance:</span>
                                                    <span class="col-md-6 text-right text-bold custom-font-size" id="div_sale_payment_balance">KES 0.00</span>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12 bg-orange pt-1 pb-1">
                                                    <span class="col-md-6 text-right text-bold">Change:</span>
                                                    <span class="col-md-6 text-right text-bold custom-font-size" id="div_sale_change">KES 0.00</span>
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
                    <button type="submit" id="btn_submit_sale_payment" class="btn btn-success"><i class="ion-checkmark-circled mr-1"></i>Submit Payment</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modal_void_sale_payment">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="icon ni ni-trash-alt"></i> Void Payment</h6>
                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <div class="modal-body">
                <div class="spinner display-none" id="set_sale_comments_loader">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                </div>
                <form id="frm_void_sale_payment" name="frm_void_sale_payment" method="post" class="is-alter" onsubmit="return submit_void_sale_payment();">

                    <input type="hidden" id="void_pos_payment_id" name="pos_payment_id">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid bg-lighter">
                                <div class="box-body">
                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <textarea name="void_reason" cols="40" rows="5" id="void_reason" class="form-control" placeholder="Void Reason"></textarea>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" id="btn_void_sale_payment" class="btn btn-success"><i class="ion-checkmark-circled mr-1"></i>Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<div class="modal fade in" id="modal_modify_sale_payment">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header header-custom">
                <h6 class="modal-title text-center"><em class="icon ni ni-cc-alt mr-1"></em>Modify Payment <span id="payment_header_pos_sale_number"></span></h6>
                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <form id="frm_sale_modify_payment" name="frm_sale_modify_payment" method="post" class="is-alter" onsubmit="return submit_modify_sale_payment();">
                <div class="modal-body">
                    <div class="spinner display-none" id="sale_make_payment_loader">
                        <div class="rect1"></div>
                        <div class="rect2"></div>
                        <div class="rect3"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div>

                                <input type="hidden" id="modify_pos_payment_id" name="pos_payment_id">
                                <input type="hidden" id="modify_payment_pos_sale_id" name="pos_sale_id">
                                <input type="hidden" id="modify_payment_pos_sale_number" name="pos_sale_number">
                                <input type="hidden" id="modify_txt_payment_method" name="txt_payment_method">

                                <div class="col-md-12 payments_div">
                                    <div class="box box-solid bg-lighter">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="sale_payment_method">Payment Method</label>
                                                        <select class="form-control select2" data-placeholder="Select Payment Method"  id="modify_sale_payment_method" name="payment_method">
                                                            <option value="">Select Payment Method</option>
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
                                                        <label for="modify_sale_payment_amount">Amount</label>
                                                        <input type="number" class="form-control" id="modify_sale_payment_amount" name="payment_amount" step="any" min="0">
                                                    </div>
                                                </div>
                                                <div id="div_modify_sale_payment_mpesa_btns" class="display-none">
                                                    <div class="col-md-6">
                                                        <button id="btn_modify_sale_payment_mpesa_instructions" type="button" tabindex="0"  class="badge badge-sm badge-dot has-bg badge-info" data-toggle="popover" data-placement="top" title="MPESA Payment Instructions" data-content="
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
                                                        <button id="btn_modify_sale_make_payment_select" type="button" class="badge badge-sm badge-dot has-bg badge-secondary d-none d-mb-inline-flex">Select Transaction</button>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row" id="div_sale_payment_reference_number">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label id="lbl_modify_payment_reference_number" for="modify_sale_payment_reference_number">Reference #</label>
                                                        <input type="text" class="form-control" id="modify_sale_payment_reference_number" name="reference_number" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="modify_sale_payment_note">Payment Note</label>
                                                        <textarea type="text" class="form-control" id="modify_sale_payment_note" name="payment_note" placeholder=""></textarea>
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
                    <button type="submit" id="btn_submit_modify_sale_payment" class="btn btn-success"><i class="ion-checkmark-circled mr-1"></i>Update</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modal_sale_customer_name">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><em class="ion-person"></em> Enter Customer Name</h6>
                <a href="javascript:;" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <div class="modal-body">
                <div class="spinner display-none" id="enter_customer_name_loader">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                </div>
                <form id="frm_enter_customer_name" name="frm_enter_customer_name" method="post" class="is-alter" onsubmit="return submit_enter_customer_name();">

                    <input type="hidden" id="customer_name_pos_sale_id" name="pos_sale_id">
                    <input type="hidden" id="customer_name_transaction_type" name="customer_name_transaction_type" value="Add">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid bg-lighter">
                                <div class="box-body">
                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="customer_name_customer_name" name="customer_name" placeholder="Customer Name" required />
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>

                                
                        </div>
                        <!-- col-md-9 -->
                        <!-- RIGHT HAND -->
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" id="btn_enter_customer_name" class="btn btn-success"><i class="ion-checkmark-circled mr-1"></i>Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                    </div>
                </form>
            </div>
            <!-- <div class="modal-footer bg-light"><span class="sub-text">Modal Footer Text</span></div> -->
        </div>
    </div>
</div>

<div id="modal_select_mpesa_payment_transaction" class="modal fade">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title text-primary"><i class="icon-plus-circle2"></i> Select MPESA Transaction</h6>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body" style="min-height: 200px;">
                <div id="div_select_mpesa_payment_transactions">
                    
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        load_sale_products();
    });
</script>
