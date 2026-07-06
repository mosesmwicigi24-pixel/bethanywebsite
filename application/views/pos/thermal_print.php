<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="shortcut icon" href="<?php echo base_url();?>assets/pos/images/favicon.png" />
        <link rel="stylesheet" href="<?php echo base_url();?>assets/pos/printer/style.css">
        <title><?php echo $page_title;?>Bethany House</title>
    </head>
    <body onload="window.print();">
        <!--  onload="window.print();" -->
        <div class="ticket">
            <?php foreach ($pos_sale as $row): ?>
                <?php $payment_balance = $row->total_sale - $row->total_paid; ?>
                <div class="centered">
                    <?php foreach ($store_information as $row2): ?>
                        <?php if($row2->store_logo != '' && file_exists("./uploads/store_logo/" . $row2->store_logo)): ?>
                            <img class="logo" src="<?php echo base_url();?>uploads/store_logo/<?php echo $row2->store_logo; ?>">
                        <?php else: ?>
                            <img class="logo" src="<?php echo base_url();?>assets/fe/img/logo.png">
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
                <p class="centered" style="border-bottom: 2px dotted; padding-bottom: 5px; margin-bottom: 5px;">
                    <?php foreach ($store_information as $row2): ?>
                        <b><?php echo $row2->store_name; ?></b><br />
                        <span style="font-size: 11px;">
                            <b>Address:</b> <?php echo $row2->physical_address; ?><br />
                            <b>Phone:</b> <?php echo $row2->phone_number; ?><br />
                            <b>Email:</b> <?php echo $row2->email_address; ?><br />
                        </span>
                    <?php endforeach; ?>
                </p>
                <p class="centered" style="border-bottom: 2px dotted; padding-bottom: 5px; margin-top: 0px;">
                    <span style="font-size: 1.3em; font-weight: bold;">SALES ORDER</span>
                    <table width="100%">
                        <tbody>
                            <tr style="border:none !important">
                                <td width="50%">
                                    <b>Sale No:</b> <?php echo $row->pos_sale_number; ?>
                                </td>
                                <td width="50%">
                                    <b>Sale Type:</b> <?php echo $row->sale_type; ?>
                                </td>
                            </tr>
                            <tr style="border:none !important">
                                <td width="50%">
                                    <b>Sale Date:</b> <?php echo date('d M, Y', strtotime($row->sale_date)); ?>
                                </td>
                                <td width="50%">
                                    
                                </td>
                            </tr>
                            <?php if ($row->sale_type == 'CREDIT SALE'): ?>
                                <tr style="border:none !important">
                                    <td width="50%">
                                        <b>Payment Terms:</b> <?php echo $row->credit_days; ?> Days
                                    </td>
                                    <td width="50%">
                                        <b>Due Date:</b> <?php echo date('d M, Y', strtotime($row->credit_due_date)); ?>
                                    </td>
                                </tr>
                            <?php endif; ?>
                            <tr style="border:none !important">
                                <td colspan="2">
                                    <b>Payment Status:</b> 
                                    <?php if ($row->is_void == 1): ?>
                                        VOID
                                    <?php else: ?>
                                        <?php if ($payment_balance == $row->total_sale): ?>
                                            UNPAID
                                        <?php elseif ($payment_balance > 0): ?>
                                            PARTIALLY PAID
                                        <?php else: ?>
                                            PAID
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </td>
                            </tr>  
                        </tbody>
                    </table>
                </p>
                <p style="border-bottom: 2px dotted; padding-bottom: 10px;">
                    <b>Customer:</b> <?php if ($row->customer_id == 0){ echo $row->customer_name; } else { echo $row->first_name . ' ' . $row->last_name; } ?>
                </p>
                <table style="border-bottom: 2px dotted;">
                    <thead>
                        <tr style="border: none !important">
                            <td colspan="4">Item Name</td>
                        </tr>
                        <tr style="border-top: none !important">
                            <td class="pre"></td>
                            <td class="description">Price</td>
                            <td class="quantity text-right">Qty</td>
                            <td class="price text-right">Total</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pos_sale_details as $row2): ?>
                            <?php
                                $variation_description = '';
                                if(!empty($row2->attributes)){
                                    foreach ($row2->attributes as $row3){
                                        $variation_description = $variation_description . $row3->product_attribute_name . ' : <b>' . $row3->product_attribute_value . '</b>, ';
                                    }
                                    $variation_description =  '~ ' . substr($variation_description,0,-2) . '<br>';
                                }
                            ?>
                            <tr style="border-bottom: none !important;">
                                <td colspan="4" style="padding-top: 5px;"><b style="text-transform: uppercase;"><?php echo $row2->product_name; ?></b> (<?php echo $row2->unit_code; ?>)<br><?php echo $variation_description; ?></td>
                            </tr>
                            <tr style="border-top: none !important; padding-bottom: 5px;">
                                <td style="padding-bottom: 5px;"></td>
                                <td style="padding-bottom: 5px;" class="description"><?php echo $default_currency . ' ' . number_format($row2->unit_price,2); ?></td>
                                <td style="padding-bottom: 5px;" class="quantity text-right"><?php echo number_format($row2->quantity,2); ?></td>
                                <td style="padding-bottom: 5px;" class="price text-right"><?php echo $default_currency . ' ' . number_format($row2->sub_total,2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr style="border:none !important" style="padding-top: 5px;">
                            <td colspan="3" style="text-align: right; padding-right: 5px;">Subtotal: </td>
                            <td class="text-right" style="font-size: 1.15em"><?php echo $default_currency . ' ' . number_format($row->sub_total,2); ?></td>
                        </tr>
                        <tr style="border:none !important">
                            <td colspan="3" style="text-align: right; padding-right: 5px;">Discount:</td>
                            <td class="text-right" style="font-size: 1.15em"><?php echo $default_currency . ' ' . number_format($row->overall_discount,2); ?></td>
                        </tr>
                        <tr style="border:none !important">
                            <td colspan="3" style="text-align: right; padding-right: 5px;">Delivery Fee: </td>
                            <td class="text-right" style="font-size: 1.15em"><?php echo $default_currency . ' ' . number_format($row->delivery_fee,2); ?></td>
                        </tr>
                        <tr style="border:none !important; font-weight: bold;">
                            <td colspan="3" style="text-align: right; padding-right: 5px;">Total:</td>
                            <td class="text-right"><b style="font-size: 1.15em"><?php echo $default_currency . ' ' . number_format($row->total_sale,2); ?></b></td>
                        </tr>
                        <!-- <tr style="border:none !important">
                            <td colspan="3" style="text-align: right; padding-right: 5px;">Number of Items Sold: </td>
                            <td class="text-right" style="font-size: 1.15em"><?php echo number_format($row->total_quantity,2); ?></td>
                        </tr> -->
                        <tr style="border:none !important">
                            <td colspan="3" style="text-align: right; padding-right: 5px;">Paid:</td>
                            <td class="text-right" style="font-size: 1.15em"><?php echo $default_currency . ' ' . number_format($row->total_paid,2); ?></td>
                        </tr>
                        <?php if ($payment_balance <= 0): ?>
                            <tr style="border:none !important; font-weight: bold;">
                                <td colspan="3" style="text-align: right; padding-right: 5px;">Change:</td>
                                <td class="text-right" style="font-size: 1.15em"><?php echo $default_currency . ' ' . number_format(($payment_balance * -1),2); ?></td>
                            </tr>
                        <?php elseif ($payment_balance > 0): ?>
                            <tr style="border:none !important; font-weight: bold;">
                                <td colspan="3" style="text-align: right; padding-right: 5px;">Balance: </td>
                                <td class="text-right" style="font-size: 1.15em"><?php echo $default_currency . ' ' . number_format($payment_balance,2); ?></td>
                            </tr>
                        <?php endif; ?>                        
                </table>
                <?php if ($num_pos_sale_payments > 0): ?>
                    <p style="margin-bottom: 0px;"><b>PAYMENTS</b></p>
                    <table style="width: 100%; border-bottom: 2px dotted;">
                        <thead>
                            <tr style="border: none !important; font-weight: 600;">
                                <td>Date</td>
                                <td>Type</td>
                                <td>Ref #</td>
                                <td>Amount</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pos_sale_payments as $row2): ?>
                                <tr style="border: none !important;">
                                    <td><?php echo date('d M, Y', strtotime($row2->created_on)); ?></td>
                                    <td><?php echo $row2->payment_method; ?></td>
                                    <td><?php echo $row2->reference_number; ?></td>
                                    <td><?php echo number_format($row2->payment_amount,2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
                <p style="margin-bottom: 0px;"><b>TAX DETAILS</b></p>
                <table style="width: 100%; border-bottom: 2px dotted;">
                    <thead>
                        <tr style="border: none !important; font-weight: 600;">
                            <td>Code</td>
                            <td>Rate</td>
                            <td>VATable Amt</td>
                            <td>VAT Amt</td>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($pos_sale_tax_details as $row2): ?>
                            <tr style="border: none !important;">
                                <td><?php echo $row2->tax_rate_code; ?></td>
                                <td><?php echo $row2->tax_rate_value; ?>%</td>
                                <td><?php echo number_format($row2->vatable_amount,2); ?></td>
                                <td><?php echo number_format($row2->vat_amount,2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <p style="border-bottom: 2px dotted; padding-bottom: 10px;">
                    **Prices inclusive of taxes where applicable
                </p>
                <?php if ($row->comments != ''): ?>
                    <p><i><?php echo $row->comments; ?></i></p>
                <?php endif; ?>
                <p>
                    You were served by: <b><?php echo $row->system_user_first_name . ' ' . $row->system_user_last_name; ?></b><br>
                    Trnx Time: <b><?php echo date('d M, Y H:i:s', strtotime($row->created_on)); ?></b>
                </p>
                <p class="centered" style="font-size: 0.8rem">POS by Devlab Africa | www.devlabafrica.com | +254 780912916</p>
            <?php endforeach; ?>
        </div>
        <!-- <script src="<?php echo base_url();?>assets/pos/printer/script.js"></script> -->
    </body>
</html>