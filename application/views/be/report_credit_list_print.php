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
              font-size: 11px; 
              color: #09274c;
            }
            html,body,.h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6{
              font-family:  'Assistant', monospace;
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
                            <h3 class="text-uppercase text-normal mb-0">Credit List Report<br><small class="text-primary"><?php //echo $row->pos_sale_number; ?></small></h3>
                            
                            <p> 
                                <?php if ($customer_id != ''): ?>
                                    <b>Customer:</b> <?php echo $customer_name; ?><br />
                                <?php endif; ?>
                                <?php if ($system_user_id != ''): ?>
                                    <b>User:</b> <?php echo $system_user_name; ?><br />
                                <?php endif; ?>
                                <?php if ($chk_cash_sales == 'on'): ?>
                                    <i><b>**Include Pending Cash Sales**</b></i><br />
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
                    <th>Sale No</th>
                    <th>Outlet</th>
                    <th>Sale Type</th>
                    <th>Date</th>
                    <th>Customer</th>
                    <th>Terms</th>
                    <th>Due Date</th>
                    <th>Total (<?php echo $default_currency; ?>)</th>
                    <th>Paid (<?php echo $default_currency; ?>)</th>
                    <th>Due (<?php echo $default_currency; ?>)</th>
                    <th>Status</th>
                    <th>User</th>
                </tr>
            </thead>
            <?php 
                $total_sale = 0;
                $total_paid = 0;
            ?>
            <tbody>                       
                <?php foreach ($credit_transactions as $row): ?>
                    <?php $sale_date = strtotime($row->sale_date); ?>
                    <?php $payment_balance = $row->total_sale - $row->total_paid; ?>
                    <tr class="nk-tb-item <?php if ($row->is_void == 1){ echo 'text-muted'; } ?>">
                        <td class=""><b>#<?php echo $row->pos_sale_number; ?></b></td>
                        <td><?php echo $row->outlet_name; ?></td>
                        <td><?php echo $row->sale_type; ?></td>
                        <td><span><?php echo date('d-m-Y', strtotime($row->sale_date)); ?></span></td>
                        <td>
                            <?php if ($row->customer_id == 0){ echo $row->customer_name; } else { echo $row->first_name . ' ' . $row->last_name; } ?>
                        </td>
                        <td>
                            <?php if ($row->credit_term != ''){ echo $row->credit_term . ' [' . $row->credit_days . ' Days]'; } ?>
                        </td>
                        <td>
                            <?php if ($row->credit_due_date != ''){ echo date('d-m-Y', strtotime($row->credit_due_date)); } ?>
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
                        
                        <td>
                            <?php
                                if ($row->credit_due_date != ''){
                                    $today_date = strtotime(date('Y-m-d', time()) . ' 00:00:00');
                                    $due_date = strtotime($row->credit_due_date);

                                    if ($today_date > $due_date) {
                                        echo '<span class="text-danger">Overdue</span>';
                                    } else {
                                        echo '<span class="text-info">Due</span>';
                                    }
                                } else {
                                    echo '<span class="text-info">Due</span>';
                                }
                            ?>
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
                    <th colspan="7" style="text-align:right">Totals:</th>
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
