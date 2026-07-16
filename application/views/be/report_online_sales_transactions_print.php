<!DOCTYPE html>
<html>
    <title><?php echo $page_title;?>Bethany House</title>
    <head>
        <link rel="icon" href="<?php echo base_url();?>assets/be/images/favicon.png">
        <?php
            function auto_version($file){
                if(!file_exists($file)) return $file;
                $mtime = filemtime($file);
                return preg_replace('{\\.([^./]+)$}', ".\$1?$mtime", $file);
            }
        ?>
        
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() . auto_version('assets/pos/css/style.css'); ?>">

        <style> 
            html{
                margin:30px 40px;
            }
            html, body {
              background: #fff; 
              font-size: 12px; 
              color: #09274c;
            }
            html,body,.h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6{
              font-family:  'Assistant';
              font-weight: normal;
              letter-spacing: -0.01em;
            }
        </style>
    </head>
    <body>
        
        <div class="row">
            <div class="col-md-12">
                <table class="table" style="margin-bottom: 0px;">
                    <tr>
                        <td width="50%" style="border: 0px">
                            <?php foreach ($store_information as $row2): ?>
                                <?php if($row2->store_logo != '' && file_exists("./uploads/store_logo/" . $row2->store_logo)): ?>
                                    <img src="<?php echo base_url();?>uploads/store_logo/<?php echo $row2->store_logo; ?>"  width="120px">
                                <?php else: ?>
                                    <img src="<?php echo base_url();?>assets/fe/img/logo.png"  width="120px">
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <br><br>
                            <?php foreach ($store_information as $row2): ?>
                                <!-- <span>
                                    <?php echo $row2->physical_address; ?><br />
                                    <?php echo $row2->phone_number; ?><br />
                                    <?php echo $row2->email_address; ?><br />
                                </span> -->
                            <?php endforeach; ?>
                        </td>
                        <td width="50%" class="text-right" style="border: 0px">
                            <h3 class="text-uppercase text-normal mb-0">Online Sales Transactions Report<br><small class="text-primary"><?php //echo $row->pos_sale_number; ?></small></h3>
                            
                            <p style="font-size: 12px">
                                <?php if ($date_from != '' && $date_to != ''): ?>
                                    <b>Date:</b> <?php echo $date_from . ' to ' . $date_to; ?><br />
                                <?php endif; ?>
                                <?php if ($outlet_id != ''): ?>
                                    <b>Outlet:</b> <?php echo $outlet_name; ?><br />  
                                <?php endif; ?>  
                            </p>
                        </td>
                    </tr>
                </table>
                
            </div>
            <div class="col-md-6 text-right">
                
            </div>
        </div>

        <table class="table table-condensed" data-auto-responsive="true">
            <thead>
                <tr>
                    <th>Order #</th>
                    <th>Order Date</th>
                    <th>Customer</th>
                    <th>Total Amount (<?php echo $default_currency; ?>)</th>
                    <th>Shipping Method</th>
                    <th>Payment Method</th>
                    <th>Status</th>
                </tr>
            </thead>
            <?php 
                $total_sale = 0;
            ?>
            <tbody> 
                <?php foreach ($online_sales_transactions as $row): ?>
                    <?php $ord_date = strtotime($row->ord_date); ?>
                    <tr>
                        <td><b>#<?php echo $row->ord_order_number; ?></b></td>
                        <td><?php echo date('d M, Y', strtotime($row->ord_date)); ?></td>
                        <td><?php echo $row->first_name . ' ' . $row->last_name; ?></td>
                        <td><?php echo number_format($row->ord_total,2); ?></td>
                        <td><?php echo $row->ord_shipping_method; ?></td>
                        <td><?php echo $row->ord_payment_method; ?></td>
                        <td class="text-center">
                            <?php if ($row->ord_order_status == 0): ?>
                                <span class="">Awaiting Payment</span>
                            <?php elseif ($row->ord_order_status == 1): ?>
                                <span class="">Processing</span>
                            <?php elseif ($row->ord_order_status == 2): ?>
                                <span class="">Dispatched</span>
                            <?php elseif ($row->ord_order_status == 3): ?>
                                <span class="">Completed</span>
                            <?php elseif ($row->ord_order_status == 4): ?>
                                <span class="">Cancelled</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php 
                        $total_sale = $total_sale + $row->ord_total;
                    ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th class="font-weight-bold" colspan="3" style="text-align:right">Total:</th>
                    <th class="font-weight-bold"><?php echo number_format($total_sale,2); ?></th>
                    <th colspan="3" style="text-align:left"></th>
                </tr>
            </tfoot>
        </table>

        
        <div class="row">                
            <div class="col-md-12">
                <table class="table" style="margin-bottom: 0px;">
                    <tr>
                        <td width="100%" style="border: 0px; text-align: center; font-size: 11px">
                            Bethany House | www.bethanyhouse.co.ke | +254 727 891 989
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        

    </body>
</html>
