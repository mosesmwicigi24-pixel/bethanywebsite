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
                            <h3 class="text-uppercase text-normal mb-0">POS Payments<br><small class="text-primary"><?php //echo $row->pos_sale_number; ?></small></h3>
                            
                            <p style="font-size: 12px">
                                <?php if ($date_from != '' && $date_to != ''): ?>
                                    <b>Date:</b> <?php echo $date_from . ' to ' . $date_to; ?><br />
                                <?php endif; ?>
                                <?php if ($payment_method != ''): ?>
                                    <b>Payment Method:</b> <?php echo $payment_method; ?><br />
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
                    <th>Date</th>
                    <th>Sale Order</th>
                    <th>Amount (<?php echo $default_currency; ?>)</th>
                    <th>Payment Method</th>
                    <th>Reference #</th>
                    <th>Payment Note</th>
                </tr>
            </thead>
            <tbody> 
                <?php
                    $count = 1;
                    $total = 0;
                ?>
                <?php foreach ($pos_payments as $row): ?>
                    <tr>    
                        <td class="text-center"><?php echo $count; ?></td>
                        <td><?php echo date('d M, Y', strtotime($row->created_on)); ?></td>
                        <td>#REC<?php echo $row->pos_sale_id; ?></td>
                        <td><?php echo number_format($row->payment_amount,2); ?></td>
                        <td><?php echo $row->payment_method; ?></td>
                        <td><?php echo $row->reference_number; ?></td>
                        <td><?php echo $row->payment_note; ?></td>
                    </tr>
                    <?php
                        $count++;
                        $total = $total + $row->payment_amount;
                    ?>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <th class="font-weight-bold" colspan="3" style="text-align:right">Total:</th>
                    <th class="font-weight-bold" colspan="4"><?php echo number_format($total,2); ?></th>                                          
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
