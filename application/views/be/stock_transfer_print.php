<!DOCTYPE html>
<html>
    <title><?php echo $page_title;?>Bethany House</title>
    <head>
        <link rel="shortcut icon" href="<?php echo base_url();?>assets/pos/images/favicon.png" />
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
            .lead {
                font-family:  'Assistant';
            }

        </style>
    </head>
    <body>
        <?php foreach ($stock_transfer as $row): ?>
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
                                    <span>
                                        <?php echo $row2->physical_address; ?><br />
                                        <?php echo $row2->phone_number; ?><br />
                                        <?php echo $row2->email_address; ?><br />
                                    </span>
                                <?php endforeach; ?>
                            </td>
                            <td width="50%" class="text-right" style="border: 0px">
                                <h3 class="text-uppercase text-normal mb-0">Stock Transfer<br><small class="text-primary"><?php echo $row->stock_transfer_number; ?></small></h3>
                                                                
                                <p style="font-size: 12px">
                                    <b>Transfer Date:</b> <?php echo date('d M, Y', strtotime($row->transfer_date)); ?><br />
                                    <b>Source Outlet:</b> <?php echo $row->source_outlet_name; ?><br />
                                    <b>Destination Outlet:</b> <?php echo $row->destination_outlet_name; ?><br />                                    
                                    <!-- <b>Received By:</b> <?php echo $row->first_name . ' ' . $row->last_name; ?><br /> -->
                                    <b>Status:</b> 
                                    <?php if ($row->is_void == 1): ?>
                                        <span class="">VOIDED</span>
                                    <?php else: ?>
                                        <span class="">Active</span>
                                    <?php endif; ?>
                                </p>
                                
                            </td>
                        </tr>
                    </table>
                    
                </div>
                <div class="col-md-6 text-right">
                    
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <table class="table table-condensed table-bordered table-striped" style="margin-bottom: 0px;">
                        <thead>
                            <tr>
                                <th width="5%">#</th>
                                <th width="70%">Product</th>
                                <th width="25%">Transferred Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            <?php foreach ($stock_transfer_details as $row2): ?>
                                <?php
                                    $variation_description = '';
                                    if(!empty($row2->attributes)){
                                        foreach ($row2->attributes as $row3){
                                            $variation_description = $variation_description . $row3->product_attribute_name . ' : <b>' . $row3->product_attribute_value . '</b>, ';
                                        }
                                        $variation_description =  '~ ' . substr($variation_description,0,-2) . '<br>';
                                    }
                                ?>
                                <tr>
                                    <td><?php echo $count; ?></td>
                                    <td><?php echo $row2->product_name; ?><br><?php echo $variation_description; ?></td>
                                    <td><?php echo number_format($row2->transferred_quantity,2); ?></td>
                                    
                                </tr>
                                <?php $count++; ?>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="2" class="text-right">Total Qty:</th>
                                <th colspan="1"><?php echo number_format($row->total_quantity,2); ?></th>
                            </tr>
                            
                        </tfoot>
                    </table>
                </div>
            </div>
            
            <div class="row">                
                <div class="col-md-12">
                    <table class="table" style="margin-bottom: 0px;">
                        <tr>
                            <td width="100%" style="border: 0px">
                                <?php if ($row->remark != ''): ?>
                                    <h5 class="text-uppercase" style="margin-bottom: 3px;">Remark:</h5>
                                    <p><?php echo nl2br($row->remark); ?></p>
                                <?php endif; ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">                
                <div class="col-md-12">
                    <table class="table" style="margin-bottom: 0px;">
                        <tr>
                            <td width="100%" class="text-center" style="border: 0px; font-size: 12px">
                                <p>Created by: <b><?php echo $row->first_name . ' ' . $row->last_name; ?></b> | Trnx Time: <b><?php echo date('d M, Y H:i:s', strtotime($row->created_on)); ?></b></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <!-- <div class="row">
                <div class="col-md-12">
                    <table class="table" style="margin-bottom: 0px;">
                        <tr>
                            <td width="33%" style="border: 0px"></td>
                            <td width="33%" style="border: 0px">
                                <?php
                                    foreach ($store_information as $row2){ 
                                        $storeName = $row2->store_name;
                                        $phoneNumber = $row2->phone_number;
                                        $emailAddress = $row2->email_address;
                                    }
                                    $svg = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 200 75" width="200" height="75">
                                            <rect x="0" y="0" width="200" height="75" stroke="#303f9f" stroke-width="2px" fill="white"/>
                                            <text transform="matrix(1 0 0 1 100 20)" style="font-family:Arial" font-size="14px" font-weight="bold" fill="#303f9f" text-anchor="middle">' . strtoupper($storeName) . '</text>
                                            <text transform="matrix(1 0 0 1 100 35)" style="font-family:Arial" font-size="13px" font-weight="bold" fill="#f44336" text-anchor="middle">' . strtoupper(date('d M Y', strtotime($row->created_on))) . '</text>
                                            <text transform="matrix(1 0 0 1 100 50)" style="font-family:Arial" font-size="12px" font-weight="bold" fill="#303f9f" text-anchor="middle">' . $phoneNumber . '</text>
                                            <text transform="matrix(1 0 0 1 100 65)" style="font-family:Arial" font-size="12px" font-weight="bold" fill="#303f9f" text-anchor="middle">' . $emailAddress . '</text>
                                        </svg>';
                                ?>
                                <img src="data:image/svg+xml;base64,<?php echo base64_encode($svg); ?>" />
                            </td>
                            <td width="34%" style="border: 0px"></td>
                        </tr>
                    </table>
                </div>
            </div> -->
            
            <div class="row">                
                <div class="col-md-12">
                    <table class="table" style="margin-bottom: 0px;">
                        <tr>
                            <td width="100%" style="border: 0px; text-align: center; font-size: 10px">
                                Bethany House | www.bethanyhouse.co.ke | +254 727 891 989
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
        <?php endforeach; ?>

    </body>
</html>
