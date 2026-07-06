<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <link rel="shortcut icon" href="<?php echo base_url();?>assets/pos/images/favicon.png" />
        <link rel="stylesheet" href="<?php echo base_url();?>assets/pos/printer/style.css">
        <title><?php echo $page_title;?>Bethany House POS</title>
    </head>
    <body onload="window.print();">
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
                <p class="centered">
                    <?php foreach ($store_information as $row2): ?>
                        <b><?php echo $row2->store_name; ?></b><br />
                        <span style="font-size: 10px;">
                            <b>Address:</b> <?php echo $row2->physical_address; ?><br />
                            <b>Phone:</b> <?php echo $row2->phone_number; ?>  || <b>Phone2:</b> <?php echo $row2->mobile_number; ?><br />
                            <b>Email:</b> <?php echo $row2->email_address; ?><br />
                        </span>
                    <?php endforeach; ?>
                </p>
                <p class="centered"><b>SALES RECEIPT</b></p>
                <p>
                    <table width="100%">
                        <tbody>
                            <tr style="border:none !important">
                                <td width="50%">
                                    <b>Sale No:</b> <?php echo $row->pos_sale_number; ?>
                                </td>
                                <td width="50%">
                                    <b>Date:</b> <?php echo date('d M, Y', strtotime($row->created_on)); ?>
                                </td>
                            </tr>
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
                <p>
                    <b>Customer:</b> <?php if ($row->customer_id == 0){ echo $row->customer_name; } else { echo $row->first_name . ' ' . $row->last_name; } ?>
                </p>
                <table>
                    <thead>
                        <tr>
                            <th class="description">Description</th>
                            <th class="quantity">Price</th>
                            <th class="price">Amount</th>
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
                            <tr>
                                <td class="description"><?php echo $row2->product_name; ?><br><?php echo $variation_description; ?><small><i>SKU: <?php echo $row2->product_sku_code; ?></i></small></td>
                                <td class="quantity"><?php echo number_format($row2->quantity,2); ?> X <br><?php echo number_format($row2->unit_price,2); ?></td>
                                <td class="price"><?php echo number_format($row2->sub_total,2); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr style="border:none !important">
                            <td colspan="2" style="text-align: right; padding-right: 5px;">Subtotal: </td>
                            <td><?php echo number_format($row->sub_total,2); ?></td>
                        </tr>
                        <tr style="border:none !important">
                            <td colspan="2" style="text-align: right; padding-right: 5px;">Overall Discount:</td>
                            <td><?php echo number_format($row->overall_discount,2); ?></td>
                        </tr>
                        <tr style="border:none !important">
                            <td colspan="2" style="text-align: right; padding-right: 5px;">Delivery Fee: </td>
                            <td><?php echo number_format($row->delivery_fee,2); ?></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: right; padding-right: 5px;"><b>TOTAL: </b></td>
                            <td><b><?php echo number_format($row->total_sale,2); ?></b></td>
                        </tr>
                        <tr>
                            <td colspan="2" style="text-align: right; padding-right: 5px;"><b>Paid: </b></td>
                            <td><b><?php echo number_format($row->total_paid,2); ?></b></td>
                        </tr>
                        <?php if ($payment_balance < 0): ?>
                            <tr>
                                <td colspan="2" style="text-align: right; padding-right: 5px;"><b>Change: </b></td>
                                <td><b><?php echo number_format(($payment_balance * -1),2); ?></b></td>
                            </tr>
                        <?php elseif ($payment_balance > 0): ?>
                            <tr>
                                <td colspan="2" style="text-align: right; padding-right: 5px;"><b>Balance: </b></td>
                                <td><b><?php echo number_format($payment_balance,2); ?></b></td>
                            </tr>
                        <?php endif; ?>
                        
                </table>
                <?php if ($row->comments != ''): ?>
                    <p class="centered"><b>Note:</b><br><i><?php echo $row->comments; ?></i></p>
                <?php endif; ?>
                <p class="centered">You were served by: <b><?php echo $row->system_user_first_name; ?></b></p>
                <p class="centered">Printed on: <b><?php echo date('d-m-Y'); ?></b></p>
                <p class="centered">Thanks for your purchase!
                    <br>www.bethanyhouse.co.ke</p>
            <?php endforeach; ?>
        </div>
        <script src="<?php echo base_url();?>assets/pos/printer/script.js"></script>
    </body>
</html>