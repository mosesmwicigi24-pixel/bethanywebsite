
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
                            <h3 class="text-uppercase text-normal mb-0">Stock Adjustments<br><small class="text-primary"><?php //echo $row->pos_sale_number; ?></small></h3>
                            
                            <p style="font-size: 12px">
                                <?php if ($date_from != '' && $date_to != ''): ?>
                                    <b>Date:</b> <?php echo $date_from . ' to ' . $date_to; ?><br />
                                <?php endif; ?>
                                <?php if ($status != ''): ?>
                                    <b>Status:</b> <?php echo $status_text; ?><br />  
                                <?php endif; ?>  
                                <?php if ($outlet_id != ''): ?>
                                    <b>Outlet:</b> <?php echo $outlet_name; ?><br />
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
                    <th>Reference #</th>
                    <th>Adjustment Date</th>
                    <th>Outlet</th>
                    <th>User</th>
                    <th>Status</th>
                </tr>
            </thead>
            <?php 
                // $total_amount = 0;
            ?>
            <tbody> 
                <?php $count = 1; ?>
                <?php foreach ($stock_adjustments as $row): ?>
                    <tr class="<?php if ($row->is_void == 1){ echo 'text-muted'; } ?>">    
                        <td class="text-center"><?php echo $count; ?></td>
                        <td><?php echo $row->stock_adjustment_number; ?></td>
                        <td><?php echo date('d M, Y', strtotime($row->adjustment_date)); ?></td>
                        <td><?php echo $row->outlet_name; ?></td>
                        <td><?php echo $row->first_name . ' ' . $row->last_name; ?></td>
                        <td>
                            <?php if ($row->is_void == 1): ?>
                                <span class="">Voided</span>
                            <?php else: ?>
                                <span class="">Active</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php $count++; ?>
                    <?php 
                        // $total_amount = $total_amount + $row->total_amount;
                    ?>
                <?php endforeach; ?>
            </tbody>
            <!-- <tfoot>
                <tr>
                    <th class="font-weight-bold" colspan="5" style="text-align:right">Total:</th>
                    <th class="font-weight-bold" colspan="1"><?php echo number_format($total_amount,2); ?></th> 
                    <th class="font-weight-bold" colspan="3"></th>                                      
                </tr>
            </tfoot> -->
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
