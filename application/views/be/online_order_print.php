<!DOCTYPE html>
<html>
    <title><?php echo $page_title;?>Bethany House</title>
    <head>
        <link rel="shortcut icon" href="<?php echo base_url();?>assets/pos/images/favicon.png" />

        <style>
            @page {
                            margin: 10px 20px 10px 20px;
                        }
            table, th, td {
                border: 0.5pt solid #09274c;
                border-collapse: collapse;
                
            }
            th, td {
                /*padding: 5px;*/
                text-align: left;
                vertical-align:top;
                padding: 5px;

            }
            body{
              word-wrap: break-word;
              font-family:  'sans-serif','Arial';
              font-size: 11px;
              /*height: 210mm;*/
            }
            .style_hidden{
              border-style: hidden;
            }
            .fixed_table{
              table-layout:fixed;
            }
            .text-center{
              text-align: center;
            }
            .text-left{
              text-align: left;
            }
            .text-right{
              text-align: right;
            }
            .text-bold{
              font-weight: bold;
            }
            .bg-sky{
              background-color: #F3FDF4;
            }
            .font-weight-normal {
                font-weight: normal !important;
            }
            @page { size: A5 margin: 5px; }
            body { margin: 5px; }

             #clockwise {
                   rotate: 90;
                }

                #counterclockwise {
                   rotate: -90;
                }
        </style>
    </head>
    <body onload="window.print();">
        <!-- onload="window.print();" -->

        <caption>
            <center>
                <span style="font-size: 18px; text-transform: uppercase;" class="text-bold">
                    Online Order
                </span>
            </center>
        </caption>

        <table autosize="1" style="overflow: wrap;" id="mytable" align="center" width="100%" height="100%" cellpadding="0" cellspacing="0">
            <!-- <table align="center" width="100%" height='100%'   > -->

            <?php foreach ($online_order as $row): ?>
                <thead>
                    <tr>
                        <th colspan="16">
                            <table width="100%" height="100%" class="style_hidden fixed_table">
                                <tr>
                                    <td colspan="4" style="padding: 1rem">
                                        <?php foreach ($store_information as $row2): ?>
                                            <?php if($row2->store_logo != '' && file_exists("./uploads/store_logo/" . $row2->store_logo)): ?>
                                                <img src="<?php echo base_url();?>uploads/store_logo/<?php echo $row2->store_logo; ?>" width="100%" style="padding-bottom: 1rem">
                                            <?php else: ?>
                                                <img src="<?php echo base_url();?>assets/fe/img/logo.png"  width="100%" style="padding-bottom: 1rem">
                                            <?php endif; ?>
                                        <?php endforeach; ?>
                                        <?php foreach ($store_information as $row2): ?>
                                            <b><?php echo $row2->store_name; ?></b><br />
                                            <span style="font-size: 10px;" class="font-weight-normal">
                                                Phone: <?php echo $row2->phone_number; ?><br />
                                                Address : <?php echo $row2->physical_address; ?><br />
                                                Email: <?php echo $row2->email_address; ?><br />
                                            </span>
                                        <?php endforeach; ?>
                                    </td>

                                    <!-- <td colspan="4">
                                        
                                    </td> -->

                                    <!-- Second Half -->
                                    <td colspan="12" rowspan="1">
                                        <span>
                                            <table style="width: 100%;" class="style_hidden fixed_table">
                                                <tr>
                                                    <td colspan="4" rowspan="3"></td>
                                                    <td colspan="4">
                                                        <h3>ORDER DETAILS</h3>
                                                        <span class="font-weight-normal">Order #:</span>
                                                        <span style="font-size: 10px;">
                                                            <b><?php echo $row->ord_order_number; ?></b>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4">
                                                        <span class="font-weight-normal">Order Date:</span>
                                                        <span style="font-size: 10px;">
                                                            <b><?php echo date('d M, Y', strtotime($row->ord_date)); ?></b>
                                                        </span>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4">
                                                        <span class="font-weight-normal">Status:</span>
                                                        <?php if ($row->ord_order_status == 0): ?>
                                                            <span class="badge badge-pill badge-warning"><i class="icon-hour-glass3 icon-sm"></i> Awaiting Payment</span>
                                                        <?php elseif ($row->ord_order_status == 1): ?>
                                                            <span class="badge badge-pill badge-info"><i class="icon-spinner2 icon-sm"></i> Processing</span>
                                                        <?php elseif ($row->ord_order_status == 2): ?>
                                                            <span class="badge badge-pill badge-primary"><i class="icon-cart-remove icon-sm"></i> Dispatched</span>
                                                        <?php elseif ($row->ord_order_status == 3): ?>
                                                            <span class="badge badge-pill badge-success"><i class="icon-checkmark-circle2 icon-sm"></i> Completed</span>
                                                        <?php elseif ($row->ord_order_status == 4): ?>
                                                            <span class="badge badge-pill badge-danger"><i class="icon-cancel-circle2 icon-sm"></i> Cancelled</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td colspan="4">
                                                        <p><span> <b>ADDRESS:</b></span></p>
                                                        <b><?php echo $row->ord_shipping_first_name . ' ' . $row->ord_shipping_last_name; ?></b><br />
                                                        <?php echo $row->ord_shipping_email_address . ', ' . $row->ord_shipping_phone_number; ?><br />
                                                        <?php //echo $row->ord_shipping_street_address . ', ' . $row->shipping_region_name . ', ' . $row->shipping_country_name; ?>
                                                    </td>
                                                    <td colspan="4">
                                                        <p><span> <b>DELIVERY:</b></span></p>
                                                        <span class="font-weight-normal">Shipping Method:</span> <?php echo $row->ord_shipping_method; ?><br />
                                                        <?php if ($row->ord_shipping_method == 'Delivery'): ?>
                                                            <span class="font-weight-normal">Shipping Zone:</span> <?php echo $row->shipping_zone_name; ?><br />
                                                            <?php if ($row->shipping_method == 0): ?>
                                                                <span class="font-weight-normal">Shipping Fee:</span> Free<br />
                                                            <?php else: ?>
                                                                <span class="font-weight-normal">Shipping Fee:</span> <?php echo $default_currency; ?> <?php echo number_format($row->ord_shipping_total,2); ?><br />
                                                            <?php endif; ?>
                                                        <?php elseif ($row->ord_shipping_method == 'Pickup'): ?>
                                                            <span class="font-weight-normal">Pickup Location:</span>
                                                            <?php echo $row->pickup_location_name . ', <br/>' . $row->pickup_location_address; ?><br />
                                                            <?php if ($row->close_to != ''): ?>
                                                                <span class="font-weight-normal">Close to:</span> <?php echo $row->close_to; ?><br />
                                                            <?php endif; ?>
                                                            <span class="font-weight-normal">Opening Hours:</span> <?php echo $row->opening_hours; ?><br />
                                                            <span class="font-weight-normal">Pickup Period:</span> <?php echo $row->pickup_period; ?><br />
                                                            <span class="font-weight-normal">Shipping Fee:</span> <?php echo $default_currency; ?> <?php echo number_format($row->ord_shipping_total,2); ?><br />
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            </table>
                                        </span>
                                    </td>
                                </tr>

                                
                            </table>
                        </th>
                    </tr>
                    <tr class="bg-sky">
                        <!-- Colspan 10 -->
                        <th colspan="1" class="text-center">#</th>
                        <th colspan="8" class="text-left">Item</th>
                        <th colspan="2" class="text-center">Unit Price</th>
                        <th colspan="3" class="text-center">Qty</th>
                        <th colspan="3" class="text-center">Subtotal</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $count = 1; ?>
                    <?php foreach ($online_order_details as $row2): ?>
                        <tr>
                            <td colspan="1" class="text-center"><?php echo $count; ?></td>
                            <td colspan="8" class="text-left">
                                <?php echo $row2->ord_det_item_name;?><?php if ($row2->ord_det_product_variation_description != ''){ echo '<br>' . $row2->ord_det_product_variation_description; }?><br><b>SKU Code:</b><?php echo $row2->ord_det_product_sku_code;?>
                            </td>
                            <td colspan="2" class="text-center"><?php echo number_format($row2->ord_det_price,2); ?></td>
                            <td colspan="3" class="text-center"><?php echo number_format($row2->ord_det_quantity,0); ?></td>
                            <td colspan="3" class="text-center"><?php echo number_format($row2->ord_det_price_total,2); ?></td>
                        </tr>
                        <?php $count++; ?>
                    <?php endforeach; ?>
                </tbody>

                <tfoot>
                    <tr>
                        <td colspan="14" class="text-right"><b>No. of Items:</b></td>
                        <td colspan="2" class="text-right"><b><?php echo number_format($row->ord_total_items,0);?></b></td>
                    </tr>

                    <tr>
                        <td colspan="14" class="text-right"><b>Sub-Total:</b></td>
                        <td colspan="2" class="text-right"><b><small></small> <?php echo number_format($row->ord_item_summary_total,2);?></b></td>
                    </tr>

                    <tr>
                        <td colspan="14" class="text-right"><b>Total Tax:</b></td>
                        <td colspan="2" class="text-right"><b><small></small> <?php echo number_format($row->ord_tax_total,2);?></b></td>
                    </tr>
                    <tr>
                        <td colspan="14" class="text-right"><b>Shipping:</b></td>
                        <td colspan="2" class="text-right"><b><small></small> <?php echo number_format($row->ord_shipping_total,2);?></b></td>
                    </tr>
                    <tr>
                        <td colspan="14" class="text-right"><b>Discount:</b></td>
                        <td colspan="2" class="text-right"><b><small></small> <?php echo number_format($row->ord_savings_total,2);?></b></td>
                    </tr>
                    <tr>
                        <td colspan="14" class="text-right"><h3>Order Total:</h3></td>
                        <td colspan="2" class="text-right"><b><small></small> <h3><?php echo number_format($row->ord_total,2);?></h3></b></td>
                    </tr>
                    
                    
                   <tr>
                        <td colspan="16">
                            <span class="amt-in-word"><b>Note:</b><br> <i style=""><?php echo $row->ord_shipping_instructions; ?></i></span>
                        </td>
                    </tr>

                    <tr>
                        <td colspan="16" style="text-align: center; font-size: 8px;">
                            Printed on: <?php echo date('d-m-Y'); ?>
                        </td>
                    </tr>
                </tfoot>
            <?php endforeach; ?>
        </table>
    </body>
</html>
