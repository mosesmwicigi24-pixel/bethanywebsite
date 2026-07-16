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
              font-size: 13px; 
              color: #09274c;
            }
            html,body,.h1, .h2, .h3, .h4, .h5, .h6, h1, h2, h3, h4, h5, h6{
              font-family:  'Assistant';
              font-weight: normal;
              letter-spacing: -0.01em;
            }
            .font-weight-bold {
                font-weight: bold;
            }
        </style>
    </head>
    <body>
        
        <div class="row">
            <div class="col-md-12">
                <table class="table" style="margin-bottom: 0px;">
                    <tr>
                        <td width="100%" class="text-center" style="border: 0px">
                            <?php foreach ($store_information as $row2): ?>
                                <?php if($row2->store_logo != '' && file_exists("./uploads/store_logo/" . $row2->store_logo)): ?>
                                    <img src="<?php echo base_url();?>uploads/store_logo/<?php echo $row2->store_logo; ?>"  width="120px">
                                <?php else: ?>
                                    <img src="<?php echo base_url();?>assets/fe/img/logo.png"  width="120px">
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <br><br>
                            <?php foreach ($store_information as $row2): ?>
                                <span style="font-size: 12px;">
                                    <?php echo $row2->physical_address; ?><br />
                                    <?php echo $row2->phone_number; ?><br />
                                    <?php echo $row2->email_address; ?><br />
                                </span>
                            <?php endforeach; ?>
                        </td>
                    </tr>
                    <tr>
                        <td width="100%" class="text-center" style="border: 0px">
                            <h3 class="text-normal mb-0">Income Statement for <?php foreach ($store_information as $row2){ echo $row2->store_name; } ?></h3>
                            <h5 class="text-normal mb-0">for the period between <?php echo date('d M, Y', strtotime($date_from)); ?> to <?php echo date('d M, Y', strtotime($date_to)); ?></h5>
                        </td>
                    </tr>
                </table>
                <br>
            </div>

        </div>
        <div class="table table-condensed" data-auto-responsive="true">
        <table class="table">
            <tbody>
                <tr class="font-14">
                    <td width="70%" class="font-weight-bold">Sales</td>
                    <td width="30%"><?php echo $default_currency; ?> <?php echo number_format($total_sales,2); ?></td>
                </tr>
                <tr class="font-14">
                    <td width="70%" class="font-weight-bold">Tax (VAT)</td>
                    <td width="30%"><?php echo $default_currency; ?> <?php echo number_format($total_sales_tax,2); ?></td>
                </tr>
                <tr class="font-14">
                    <td width="70%" class="font-weight-bold">Cost of Goods Sold</td>
                    <td width="30%"><?php echo $default_currency; ?> <?php echo number_format($cost_of_goods_sold,2); ?></td>
                </tr>
                <?php $gross_profit = $total_sales -$total_sales_tax - $cost_of_goods_sold; ?>
                <tr class="font-14 mb-4">
                    <td width="70%" class="font-weight-bold text-right">Gross Profit:</td>
                    <td width="30%"><?php echo $default_currency; ?> <?php echo number_format($gross_profit,2); ?></td>
                </tr>  
                <tr class="font-14">
                    <td width="70%" class="font-weight-bold">Running Expenses</td>
                    <td width="30%"><?php echo $default_currency; ?> <?php echo number_format($total_expenses,2); ?></td>
                </tr>
                <?php $proprietor_salary = 0.3 * $gross_profit; ?>
                <tr class="font-14">
                    <td width="70%" class="font-weight-bold">Proprietor's Salary</td>
                    <td width="30%"><?php echo $default_currency; ?> <?php echo number_format($proprietor_salary,2); ?></td>
                </tr>   
                <?php $operating_income = $gross_profit - $total_expenses; ?>   
                <tr class="font-14 mb-4">
                    <td width="70%" class="font-weight-bold text-right">Operating Income:</td>
                    <td width="30%"><?php echo $default_currency; ?> <?php echo number_format($operating_income,2); ?></td>
                </tr>   
                <tr class="font-14">
                    <td width="70%" class="font-weight-bold">Other Income</td>
                    <td width="30%"><?php echo $default_currency; ?> <?php echo number_format(0,2); ?></td>
                </tr>
                <?php $tithe = 0.1 * $operating_income; ?> 
                <tr class="font-14">
                    <td width="70%" class="font-weight-bold">Other Expenses (Tithe)</td>
                    <td width="30%"><?php echo $default_currency; ?> <?php echo number_format($tithe,2); ?></td>
                </tr> 
                <!-- <tr class="font-14">
                    <td width="70%" class="font-weight-bold">Income Before Tax</td>
                    <td width="30%"><?php echo $default_currency; ?> <?php echo number_format(0,2); ?></td>
                </tr>  -->  
                <?php $net_profit = $operating_income -$tithe; ?>
                <tr class="font-14 mb-4">
                    <td width="70%" class="font-weight-bold text-right">Net Profit:</td>
                    <td width="30%"><?php echo $default_currency; ?> <?php echo number_format($net_profit,2); ?></td>
                </tr>                                         
            </tbody>
        </table>

        
        <div class="row">                
            <div class="col-md-12">
                <table class="table" style="margin-bottom: 0px;">
                    <tr>
                        <td width="100%" style="border: 0px; text-align: center; font-size: 11px">
                            Generated on <?php echo date('d M, Y H:i:s'); ?><br>
                            Bethany House | www.bethanyhouse.co.ke | +254 727 891 989
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        

    </body>
</html>
