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
                            <h3 class="text-uppercase text-normal mb-0">POS Sales Transactions Report<br><small class="text-primary"><?php //echo $row->pos_sale_number; ?></small></h3>
                            
                            <p style="font-size: 12px">
                                <?php if ($date_from != '' && $date_to != ''): ?>
                                    <b>Date:</b> <?php echo $date_from . ' to ' . $date_to; ?><br />
                                <?php endif; ?>
                                <?php if ($outlet_id != ''): ?>
                                    <b>Outlet:</b> <?php echo $outlet_name; ?><br />  
                                <?php endif; ?>  
                                <?php if ($sale_type != ''): ?>                            
                                    <b>Sale Type:</b> <?php echo $sale_type; ?><br />
                                <?php endif; ?>
                                <?php if ($sale_status != ''): ?>
                                    <b>Sale Status:</b> <?php echo $sale_status_text; ?><br />
                                <?php endif; ?>
                                <?php if ($system_user_id != ''): ?>
                                    <b>User:</b> <?php echo $system_user_name; ?><br />
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
                    <th class="">Sale No</th>
                    <th class="">Outlet</th>
                    <th class="">Sale Type</th>
                    <th class="">Date</th>
                    <th class="">Customer</th>
                    <th class="">Total (<?php echo $default_currency; ?>)</th>
                    <th class="">Paid (<?php echo $default_currency; ?>)</th>
                    <th class="">Due (<?php echo $default_currency; ?>)</th>
                    <th class="">Status</th>
                    <th class="">User</th>
                </tr>
            </thead>
            <?php 
                $total_sale = 0;
                $total_paid = 0;
            ?>
            <tbody>                       
                <?php foreach ($pos_sales_transactions as $row): ?>
                    <?php $sale_date = strtotime($row->sale_date); ?>
                    <?php $payment_balance = $row->total_sale - $row->total_paid; ?>
                    <tr class="nk-tb-item <?php if ($row->is_void == 1){ echo 'text-muted'; } ?>">
                        <td class=""><b>#<?php echo $row->pos_sale_number; ?></b></td>
                        <td><?php echo $row->outlet_name; ?></td>
                        <td><?php echo $row->sale_type; ?></td>
                        <td data-sort="<?php echo $sale_date; ?>"><span><?php echo date('d-m-Y', strtotime($row->sale_date)); ?></span></td>
                        <td class="">
                            <span><?php if ($row->customer_id == 0){ echo $row->customer_name; } else { echo $row->first_name . ' ' . $row->last_name; } ?></span>
                        </td>
                        <td>
                            <?php echo number_format($row->total_sale,2); ?>
                        </td>
                        <td>
                            <?php echo number_format($row->total_paid,2); ?>
                        </td>
                        <td>
                            <?php echo number_format(($row->total_sale - $row->total_paid),2); ?>
                        </td>                                        
                        
                        <td class="">
                            <?php if ($row->is_void == 1): ?>
                                <span class="">Void</span>
                            <?php else: ?>
                                <?php if ($payment_balance == $row->total_sale): ?>
                                    <span class="">Unpaid</span>
                                <?php elseif ($payment_balance > 0): ?>
                                    <span class="">Partially Paid</span>
                                <?php else: ?>
                                    <span class="">Paid</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td class=""><span><?php echo $row->system_user_first_name . ' ' . $row->system_user_last_name; ?> </span></td>
                    </tr>
                    <?php 
                        $total_sale = $total_sale + $row->total_sale;
                        $total_paid = $total_paid + $row->total_paid;
                    ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="5" style="text-align:right">Totals:</th>
                    <th><?php echo number_format($total_sale,2); ?></th>
                    <th><?php echo number_format($total_paid,2); ?></th>
                    <th><?php echo number_format($total_sale - $total_paid,2); ?></th>
                    <th colspan="2" style="text-align:left"></th>
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
