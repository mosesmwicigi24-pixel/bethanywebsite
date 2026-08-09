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
                            <h3 class="text-uppercase text-normal mb-0">Sales by Items Report<br><small class="text-primary"><?php //echo $row->pos_sale_number; ?></small></h3>
                            
                            <p style="font-size: 12px">
                                <?php if ($date_from != '' && $date_to != ''): ?>
                                    <b>Date:</b> <?php echo $date_from . ' to ' . $date_to; ?><br />
                                <?php endif; ?>
                                <?php if ($outlet_id != ''): ?>
                                    <b>Outlet:</b> <?php echo $outlet_name; ?><br />  
                                <?php endif; ?>
                                <?php if ($transaction_type != ''): ?>
                                    <b>Transaction Type:</b> <?php echo $transaction_type; ?><br />  
                                <?php endif; ?>  
                                <b>Print Date:</b> <?php echo date('Y-m-d H:i:s'); ?><br />
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
                    <th class="text-center" style="width: 50px">#</th>
                    <th style="width: 250px">Name</th>
                    <th style="width: 90px" class="text-center">Quantity Sold</th>
                    <th style="width: 90px" class="text-center">Sales Incl. Tax (<?php echo $default_currency; ?>)</th>
                    <th style="width: 90px" class="text-center">Sales Excl. Tax (<?php echo $default_currency; ?>)</th>
                    <th style="width: 90px" class="text-center">Total Tax (<?php echo $default_currency; ?>)</th>
                    <th style="width: 90px" class="text-center">Total Discount (<?php echo $default_currency; ?>)</th>
                </tr>
            </thead>
            <tbody> 
                <?php
                    $count = 1;
                    $totalQuantity = 0;
                    $totalSalesInclTax = 0;
                    $totalSalesExclTax = 0;
                    $totalTax = 0;
                    $totalDiscount = 0;
                ?>
                <?php foreach ($sales_by_items as $row): ?>
                    <?php
                        $variation_description = '';
                        if(!empty($row->attributes)){
                            foreach ($row->attributes as $row3){
                                $variation_description = $variation_description . $row3->product_attribute_name . ' : <b>' . $row3->product_attribute_value . '</b>, ';
                            }
                            $variation_description =  '<br>~ ' . substr($variation_description,0,-2) . '<br>';
                        }
                    ?>
                    <tr>    
                        <td class="text-center"><?php echo $count; ?></td>
                        <td><?php echo $row->product_name; ?><?php echo $variation_description; ?></td>
                        <td class="text-center">
                            <?php
                                if ($transaction_type == ''){
                                    echo number_format($row->posTotalQuantity + $row->onlineTotalQuantity,2);
                                    $totalQuantity = $totalQuantity + $row->posTotalQuantity + $row->onlineTotalQuantity;
                                } elseif ($transaction_type == 'POS') {
                                    echo number_format($row->posTotalQuantity,2);
                                    $totalQuantity = $totalQuantity + $row->posTotalQuantity;
                                } elseif ($transaction_type == 'Online') {
                                    echo number_format($row->onlineTotalQuantity,2);
                                    $totalQuantity = $totalQuantity + $row->onlineTotalQuantity;
                                }
                            ?>
                        </td>
                        <td class="text-center">
                            <?php
                                if ($transaction_type == ''){
                                    echo number_format($row->posTotalSubTotal + $row->onlineTotalSubTotal,2);
                                    $totalSalesInclTax = $totalSalesInclTax + $row->posTotalSubTotal + $row->onlineTotalSubTotal;
                                } elseif ($transaction_type == 'POS') {
                                    echo number_format($row->posTotalSubTotal,2);
                                    $totalSalesInclTax = $totalSalesInclTax + $row->posTotalSubTotal;
                                } elseif ($transaction_type == 'Online') {
                                    echo number_format($row->onlineTotalSubTotal,2);
                                    $totalSalesInclTax = $totalSalesInclTax + $row->onlineTotalSubTotal;
                                }
                            ?>
                        </td>
                        <td class="text-center">
                            <?php
                                if ($transaction_type == ''){
                                    echo number_format(($row->posTotalSubTotal - $row->posTotalTaxAmount) + ($row->onlineTotalSubTotal - $row->onlineTotalTaxAmount),2);
                                    $totalSalesExclTax = $totalSalesExclTax + ($row->posTotalSubTotal - $row->posTotalTaxAmount) + ($row->onlineTotalSubTotal - $row->onlineTotalTaxAmount);
                                } elseif ($transaction_type == 'POS') {
                                    echo number_format($row->posTotalSubTotal - $row->posTotalTaxAmount,2);
                                    $totalSalesExclTax = $totalSalesExclTax + ($row->posTotalSubTotal - $row->posTotalTaxAmount);
                                } elseif ($transaction_type == 'Online') {
                                    echo number_format($row->onlineTotalSubTotal - $row->onlineTotalTaxAmount,2);
                                    $totalSalesExclTax = $totalSalesExclTax + ($row->onlineTotalSubTotal - $row->onlineTotalTaxAmount);
                                }
                            ?>
                        </td>
                        <td class="text-center">
                            <?php
                                if ($transaction_type == ''){
                                    echo number_format($row->posTotalTaxAmount + $row->onlineTotalTaxAmount,2);
                                    $totalTax = $totalTax + ($row->posTotalTaxAmount + $row->onlineTotalTaxAmount);
                                } elseif ($transaction_type == 'POS') {
                                    echo number_format($row->posTotalTaxAmount,2);
                                    $totalTax = $totalTax + $row->posTotalTaxAmount;
                                } elseif ($transaction_type == 'Online') {
                                    echo number_format($row->onlineTotalTaxAmount,2);
                                    $totalTax = $totalTax + $row->onlineTotalTaxAmount;
                                }
                            ?>
                        </td>
                        <td class="text-center">
                            <?php
                                if ($transaction_type == ''){
                                    echo number_format($row->posTotalDiscountAmount + $row->onlineTotalDiscountAmount,2);
                                    $totalDiscount = $totalDiscount + $row->posTotalDiscountAmount + $row->onlineTotalDiscountAmount;
                                } elseif ($transaction_type == 'POS') {
                                    echo number_format($row->posTotalDiscountAmount,2);
                                    $totalDiscount = $totalDiscount + $row->posTotalDiscountAmount;
                                } elseif ($transaction_type == 'Online') {
                                    echo number_format($row->onlineTotalDiscountAmount,2);
                                    $totalDiscount = $totalDiscount + $row->onlineTotalDiscountAmount;
                                }
                            ?>
                        </td>
                    </tr>
                    <?php $count++; ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th class="font-weight-bold" colspan="2" style="text-align:right">Totals:</th>
                    <th class="font-weight-bold text-center"><?php echo number_format($totalQuantity,2); ?></th>
                    <th class="font-weight-bold text-center"><?php echo number_format($totalSalesInclTax,2); ?></th>
                    <th class="font-weight-bold text-center"><?php echo number_format($totalSalesExclTax,2); ?></th>
                    <th class="font-weight-bold text-center"><?php echo number_format($totalTax,2); ?></th>
                    <th class="font-weight-bold text-center"><?php echo number_format($totalDiscount,2); ?></th>                                          
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
