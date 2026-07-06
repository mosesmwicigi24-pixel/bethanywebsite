                                    <div class="col-lg-8 col-md-7 col-sm-12 col-xs-12 no-padding-right no-padding-left">
                                        <div class="spinner display-none" id="details_section_loader">
                                            <div class="rect1"></div>
                                            <div class="rect2"></div>
                                            <div class="rect3"></div>
                                        </div>
                                        <div class="register-box register-items-form">
                                            <a tabindex="-1" href="#" class="dismissfullscreen hidden"><i class="ion-close-circled"></i></a>
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
                                                                                <a href="#" data-toggle="modal" data-target="#modal_modify_sale_product" data-product-id="<?php echo $row2->product_id; ?>" data-product-variation-id="<?php echo $row2->product_variation_id; ?>" data-pos-sale-id="<?php echo $row2->pos_sale_id; ?>" data-pos-sale-detail-id="<?php echo $row2->pos_sale_detail_id; ?>" class="register-item-name lnk-modify-sale-product">
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
                                                                                    href="#" data-toggle="modal" data-target="#modal_modify_sale_product" data-product-id="<?php echo $row2->product_id; ?>" data-product-variation-id="<?php echo $row2->product_variation_id; ?>" data-pos-sale-id="<?php echo $row2->pos_sale_id; ?>" data-pos-sale-detail-id="<?php echo $row2->pos_sale_detail_id; ?>"
                                                                                    class="xeditable xeditable-price editable editable-click lnk-modify-sale-product"
                                                                                    data-pk="1"
                                                                                >
                                                                                    <?php echo $default_currency; ?> <?php echo number_format($row2->unit_price,2); ?>
                                                                                </a>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <a
                                                                                    href="#" data-toggle="modal" data-target="#modal_modify_sale_product" data-product-id="<?php echo $row2->product_id; ?>" data-product-variation-id="<?php echo $row2->product_variation_id; ?>" data-pos-sale-id="<?php echo $row2->pos_sale_id; ?>" data-pos-sale-detail-id="<?php echo $row2->pos_sale_detail_id; ?>"
                                                                                    class="xeditable editable editable-click lnk-modify-sale-product"
                                                                                    data-pk="1"
                                                                                >
                                                                                    <?php echo number_format($row2->quantity,2); ?>
                                                                                </a>
                                                                            </td>
                                                                            <td class="text-center">
                                                                                <a
                                                                                    href="#" data-toggle="modal" data-target="#modal_modify_sale_product" data-product-id="<?php echo $row2->product_id; ?>" data-product-variation-id="<?php echo $row2->product_variation_id; ?>" data-pos-sale-id="<?php echo $row2->pos_sale_id; ?>" data-pos-sale-detail-id="<?php echo $row2->pos_sale_detail_id; ?>"
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
                                                                                <a href="#" data-toggle="modal" data-target="#modal_modify_sale_product" data-product-id="<?php echo $row2->product_id; ?>" data-product-variation-id="<?php echo $row2->product_variation_id; ?>" data-pos-sale-id="<?php echo $row2->pos_sale_id; ?>" data-pos-sale-detail-id="<?php echo $row2->pos_sale_detail_id; ?>" class="delete-item text-info lnk-modify-sale-product"><em class="icon ni ni-edit"></em> </a>
                                                                                <a href="javascript:void(0);" data-product-id="<?php echo $row2->product_id; ?>" data-product-variation-id="<?php echo $row2->product_variation_id; ?>" data-pos-sale-id="<?php echo $row2->pos_sale_id; ?>" data-pos-sale-detail-id="<?php echo $row2->pos_sale_detail_id; ?>" class="delete-item remove_pos_sale_item" tabindex="-1"><i class="icon ion-android-cancel"></i></a>
                                                                            </td>
                                                                        </tr>

                                                                    <?php endforeach; ?>
                                                                <?php else: ?>
                                                                    <tr class="cart_content_area">
                                                                        <td colspan="6">
                                                                            <div class="text-center p-1">
                                                                                <h6 class=" text-warning">There are no items in the cart</h6>
                                                                            </div>
                                                                        </td>
                                                                    </tr>
                                                                <?php endif; ?>
                                                            <?php endforeach; ?>
                                                        <?php else: ?>
                                                            <tr class="cart_content_area">
                                                                <td colspan="6">
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
                                                                    href="#"
                                                                    id="sale_overall_discount"
                                                                    class="xeditable editable editable-click editable-empty"
                                                                ><?php echo $default_currency; ?> <?php echo number_format($row->overall_discount,2); ?></a>
                                                            </div>
                                                        </li>
                                                        
                                                        <li class="list-group-item global-discount-group">
                                                            <div class="key">Delivery Fee:</div>
                                                            <div class="value pull-right">
                                                                <a
                                                                    href="#"
                                                                    id="sale_delivery_fee"
                                                                    class="xeditable editable editable-click editable-empty"
                                                                ><?php echo $default_currency; ?> <?php echo number_format($row->delivery_fee,2); ?></a>
                                                            </div>
                                                        </li>

                                                        
                                                    </ul>

                                                    <div class="comment-block">
                                                        <big><a href="#" id="lnk_sale_comments" class="xeditable editable editable-click editable-empty"><em class="icon ni ni-comments mr-1"></em>Comments</a></big>
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
                                                                href="#"
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
                                                                href="#"
                                                                id="sale_delivery_fee"
                                                                class="xeditable editable editable-click editable-empty"
                                                            >
                                                                KES 0.00
                                                            </a>
                                                        </div>
                                                    </li>

                                                    
                                                </ul>

                                                <div class="comment-block">
                                                    <big><a href="#" id="lnk_sale_comments" class="xeditable editable editable-click editable-empty"><em class="icon ni ni-comments mr-1"></em>Comments</a></big>
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
                                                                        <a href="#" class="delete-payment remove text-info lnk_modify_sale_payment" data-pos-payment-id="<?php echo $row2->pos_payment_id; ?>" data-toggle="tooltip" data-placement="top" title="Modify Payment"><i class="icon ti-pencil-alt"></i></a>
                                                                        <a href="#" class="delete-payment remove lnk_void_sale_payment" data-pos-payment-id="<?php echo $row2->pos_payment_id; ?>" data-toggle="tooltip" data-placement="top" title="Void Payment"><i class="icon ni ni-trash-alt"></i></a>
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