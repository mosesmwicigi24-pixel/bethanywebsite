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
                            <h3 class="text-uppercase text-normal mb-0">Customer Aging Report<br><small class="text-primary"><?php //echo $row->pos_sale_number; ?></small></h3>
                            
                            <p> 
                                <?php if ($car_date != ''): ?>
                                    <b>As of <?php echo date('F d, Y', strtotime($car_date)); ?></b><br />
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
                    <th>#</th>
                    <th>Customer</th>
                    <th>0-30 Days</th>
                    <th>31-60 Days</th>
                    <th>61-90 Days</th>
                    <th>90+ Days</th>
                    <th>Total</th>
                </tr>
            </thead>
            <?php 
                $count = 1;
                $total_0_30 = 0;
                $total_31_60 = 0;
                $total_61_90 = 0;
                $total_gt_90 = 0;
                $total_total = 0;
            ?>
            <tbody>                       
                <?php foreach ($customer_aging_report as $row): ?>
                    <tr>
                        <td><?php echo $count; ?></td>
                        <td><?php echo $row->first_name . ' ' . $row->last_name; ?></td>
                        <td><?php echo number_format($row->age_0_30,2); ?></td>
                        <td><?php echo number_format($row->age_31_60,2); ?></td>
                        <td><?php echo number_format(($row->age_61_90),2); ?></td>
                        <td><?php echo number_format(($row->age_gt_90),2); ?></td>
                        <td><?php echo number_format(($row->total_balance),2); ?></td>
                    </tr>
                    <?php 
                        $count++;
                        $total_0_30 = $total_0_30 + $row->age_0_30;
                        $total_31_60 = $total_31_60 + $row->age_31_60;
                        $total_61_90 = $total_61_90 + $row->age_61_90;
                        $total_gt_90 = $total_gt_90 + $row->age_gt_90;
                        $total_total = $total_total + $row->total_balance;
                    ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="2" style="text-align:right">Totals:</th>
                    <th><?php echo number_format($total_0_30,2); ?></th>
                    <th><?php echo number_format($total_31_60,2); ?></th>
                    <th><?php echo number_format($total_61_90,2); ?></th>
                    <th><?php echo number_format($total_gt_90,2); ?></th>
                    <th><?php echo number_format($total_total,2); ?></th>
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
