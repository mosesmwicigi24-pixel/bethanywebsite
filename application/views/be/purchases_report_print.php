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
                            <h3 class="text-uppercase text-normal mb-0">Purchase Orders Report<br><small class="text-primary"><?php //echo $row->pos_sale_number; ?></small></h3>
                            
                            <p style="font-size: 12px">
                                <?php if ($date_from != '' && $date_to != ''): ?>
                                    <b>Date:</b> <?php echo $date_from . ' to ' . $date_to; ?><br />
                                <?php endif; ?>
                                <?php if ($purchase_order_status != ''): ?>
                                    <b>Status:</b> <?php echo $purchase_order_status_text; ?><br />  
                                <?php endif; ?>  
                                <?php if ($payment_status != ''): ?>
                                    <b>Payment Status:</b> <?php echo $payment_status; ?><br />
                                <?php endif; ?>
                                <?php if ($supplier_id != ''): ?>
                                    <b>User:</b> <?php echo $supplier_name; ?><br />
                                <?php endif; ?>
                                <?php if ($system_user_id != ''): ?>
                                    <b>User:</b> <?php echo $system_user_name; ?><br />
                                <?php endif; ?>
                            </p>
                        </td>
                    </tr>
                </table>
                
            </div>
        </div>

        <table class="table table-condensed" data-auto-responsive="true">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px">#</th>
                    <th>PO #</th>
                    <th>Order Date</th>
                    <th>Expected Date</th>
                    <th>Supplier</th>
                    <th>Total Amount (<?php echo $default_currency; ?>)</th>                                            
                    <th>Status</th>
                    <th>Total Paid (<?php echo $default_currency; ?>)</th>
                    <th>Balance (<?php echo $default_currency; ?>)</th>
                    <th>Payment Status</th>
                    <th>User</th>
                </tr>
            </thead>
            <?php 
                $total_amount = 0;
                $total_paid = 0;
                $total_balance = 0;
            ?>
            <tbody> 
                <?php $count = 1; ?>
                <?php foreach ($purchase_orders as $row): ?>
                    <tr class="<?php if ($row->is_void == 1){ echo 'text-muted'; } ?>">    
                        <td class="text-center"><?php echo $count; ?></td>
                        <td><?php echo $row->purchase_order_number; ?></td>
                        <td><?php echo date('d M, Y', strtotime($row->purchase_order_date)); ?></td>
                        <td><?php if ($row->expected_date != ''){ echo date('d M, Y', strtotime($row->expected_date)); } ?></td>
                        <td><?php echo $row->supplier_name; ?></td>
                        <td><?php echo number_format($row->total_amount,2); ?></td>
                        <td>
                            <?php if ($row->is_void == 1): ?>
                                <span class="">Voided</span>
                            <?php else: ?>
                                <?php if ($row->total_received_qty == 0): ?>
                                    <span class="">Unreceived</span>
                                <?php elseif ($row->total_received_qty < $row->total_detail_qty): ?>
                                    <span class="">Partially Received</span>
                                <?php elseif ($row->total_received_qty == $row->total_detail_qty): ?>
                                    <span class="">Received</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td><?php echo number_format($row->total_paid,2); ?></td>
                        <td>
                            <?php 
                                if ($row->total_paid >= $row->total_amount){
                                    echo number_format(0,2);
                                } else{
                                    echo number_format($row->total_amount - $row->total_paid,2);
                                }
                            ?>
                        </td>
                        <td>
                            <?php if ($row->is_void == 1): ?>
                                <?php echo '&mdash;'; ?>
                            <?php else: ?>
                                <?php if ($row->total_paid == 0): ?>
                                    <span class="">Unpaid</span>
                                <?php elseif ($row->total_paid > 0 && $row->total_paid < $row->total_amount): ?>
                                    <span class="">Partially Paid</span>
                                <?php else: ?>
                                    <span class="">Paid</span>
                                <?php endif; ?>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $row->first_name . ' ' . $row->last_name; ?></td>
                    </tr>
                    <?php $count++; ?>
                    <?php 
                        $total_amount = $total_amount + $row->total_amount;
                        $total_paid = $total_paid + $row->total_paid;
                        if ($row->total_paid >= $row->total_amount){ $balance = 0; } else{ $balance = $row->total_amount - $row->total_paid; }
                        $total_balance = $total_balance + $balance;
                    ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th class="font-weight-bold" colspan="5" style="text-align:right">Totals:</th>
                    <th class="font-weight-bold" colspan="1"><?php echo number_format($total_amount,2); ?></th> 
                    <th></th>   
                    <th class="font-weight-bold" colspan="1"><?php echo number_format($total_paid,2); ?></th>
                    <th class="font-weight-bold" colspan="1"><?php echo number_format($total_balance,2); ?></th>
                    <th class="font-weight-bold" colspan="2"></th>                                      
                </tr>
            </tfoot>
        </table>        
        
        <div class="row">                
            <div class="col-md-12">
                <table class="table" style="margin-bottom: 0px;">
                    <tr>
                        <td width="100%" style="border: 0px; text-align: center; font-size: 11px">
                            POS powered by Devlab Africa | www.devlabafrica.com | +254 780912916
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        

    </body>
</html>
