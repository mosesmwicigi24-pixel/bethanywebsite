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

            .badge-dot.badge-primary {
                color: #09274c;
            }
            .badge-dot.badge-primary.has-bg {
                background-color: rgba(133, 79, 255, 0.15);
            }
            .badge-dot.badge-secondary {
                color: #364a63;
            }
            .badge-dot.badge-secondary.has-bg {
                background-color: rgba(54, 74, 99, 0.15);
            }
            .badge-dot.badge-success {
                color: #1ee0ac;
            }
            .badge-dot.badge-success.has-bg {
                background-color: rgba(30, 224, 172, 0.15);
            }
            .badge-dot.badge-info {
                color: #09c2de;
            }
            .badge-dot.badge-info.has-bg {
                background-color: rgba(9, 194, 222, 0.15);
            }
            .badge-dot.badge-warning {
                color: #f4bd0e;
            }
            .badge-dot.badge-warning.has-bg {
                background-color: rgba(244, 189, 14, 0.15);
            }
            .badge-dot.badge-danger {
                color: #e85347;
            }
            .badge-dot.badge-danger.has-bg {
                background-color: rgba(232, 83, 71, 0.15);
            }
            .badge-dot.badge-dark {
                color: #1c2b46;
            }
            .badge-dot.badge-dark.has-bg {
                background-color: rgba(28, 43, 70, 0.15);
            }
            .badge-dot.badge-gray {
                color: #8091a7;
            }
            .badge-dot.badge-gray.has-bg {
                background-color: rgba(128, 145, 167, 0.15);
            }
            .badge-dot.badge-light {
                color: #b7c2d0;
            }
            .badge-dot.badge-light.has-bg {
                background-color: rgba(183, 194, 208, 0.15);
            }
            .badge-dot.badge-lighter {
                color: #e5e9f2;
            }
            .badge-dot.badge-lighter.has-bg {
                background-color: rgba(229, 233, 242, 0.15);
            }

        </style>
    </head>
    <body>
        <?php foreach ($pos_quotation as $row): ?>
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
                                <h3 class="text-uppercase text-normal mb-0">Quotation<br><small class="text-primary"><?php echo $row->pos_quotation_number; ?></small></h3>
                                <br>
                                <p class="mb-0">
                                    <small><b>CUSTOMER</b></small><br>
                                    <big>
                                        <?php 
                                            if ($row->customer_id != 0){
                                                echo $row->first_name . ' ' . $row->last_name;
                                            } else {
                                                if ($row->customer_name != ''){
                                                    echo $row->customer_name;
                                                } else {
                                                    echo '-';
                                                }
                                            }
                                        ?>
                                    </big>
                                </p>
                                
                                <p style="font-size: 12px">
                                    <b>Quotation Date:</b> <?php echo date('d M, Y', strtotime($row->quotation_date)); ?><br />
                                    <b>Valid Until:</b> <?php if ($row->valid_until != ''){ echo date('d M, Y', strtotime($row->valid_until)); } ?><br />
                                    <b>Status:</b> 
                                    <?php if ($row->is_void == 1): ?>
                                        <span class="">VOID</span>
                                    <?php else: ?>
                                        <span class="">ACTIVE</span>
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
                                <th width="37%">Description</th>
                                <th width="8%">Unit</th>
                                <th width="10%">Qty</th>
                                <th width="5%">Tax</th>
                                <th width="15%">Rate (<?php echo $default_currency; ?>)</th>
                                <th width="15%">Total (<?php echo $default_currency; ?>)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $count = 1; ?>
                            <?php foreach ($pos_quotation_details as $row2): ?>
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
                                    <td><?php echo $row2->unit_name . ' (' . $row2->unit_code . ')'; ?></td>
                                    <td><?php echo number_format($row2->quantity,2); ?></td>
                                    <td><?php echo $row2->tax_rate_code; ?></td>
                                    <td><?php echo number_format($row2->unit_price,2); ?></td>
                                    <td><?php echo number_format($row2->sub_total,2); ?></td>
                                </tr>
                                <?php $count++; ?>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="6">Total Quantity</th>
                                <th><?php echo number_format($row->total_quantity,2); ?></th>
                            </tr>

                            <tr>
                                <th colspan="6">Subtotal</th>
                                <th><?php echo number_format($row->sub_total,2); ?></th>
                            </tr>                           

                            <tr>
                                <th colspan="6">Discount</b></th>
                                <th><?php echo number_format($row->discount,2); ?></th>
                            </tr>

                            <tr>
                                <th colspan="6">Delivery Fee</th>
                                <th><?php echo number_format($row->delivery_fee,2); ?></th>
                            </tr>

                            <tr>
                                <th colspan="6"><big>Grand Total</big></th>
                                <th><big><?php echo number_format($row->total_amount,2); ?></big></th>
                            </tr>

                        </tfoot>
                    </table>
                </div>
            </div>
            <div class="row">                
                <div class="col-md-12">
                    <table class="table" style="margin-bottom: 0px;">
                        <tr>
                            <td width="50%" style="border: 0px">
                                <h5 class="text-uppercase" style="margin-bottom: 3px;">Tax Details</h5>
                                <table class="table table-condensed table-striped" style="margin-bottom: 0px; font-size: 12px;">
                                    <thead>
                                        <tr>
                                            <th width="20%">Code</th>
                                            <th width="20%">Rate</th>
                                            <th width="30%">VATABLE AMT</th>
                                            <th width="30%">VAT AMT</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count = 1; ?>
                                        <?php foreach ($pos_quotation_tax_details as $row2): ?>
                                            <tr>
                                                <td><?php echo $row2->tax_rate_code; ?></td>
                                                <td><?php echo $row2->tax_rate_value; ?>%</td>
                                                <td><?php echo number_format($row2->vatable_amount,2); ?></td>
                                                <td><?php echo number_format($row2->vat_amount,2); ?></td>
                                            </tr>
                                            <?php $count++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                                **Prices inclusive of taxes where applicable
                            </td>
                            <td width="50%" style="border: 0px">
                                
                            </td>
                        </tr>
                    </table>                    
                </div>
            </div>
            <div class="row">                
                <div class="col-md-12">
                    <table class="table" style="margin-bottom: 0px;">
                        <tr>
                            <td width="100%" style="border: 0px">
                                <?php if ($row->comments != ''): ?>
                                    <h5 class="text-uppercase" style="margin-bottom: 3px;">Comments</h5>
                                    <p><?php echo nl2br($row->comments); ?></p>
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
                            <td width="100%" style="border: 0px; font-size: 12px">
                                <p>You were served by: <b><?php echo $row->system_user_first_name . ' ' . $row->system_user_last_name; ?></b> | Trnx Time: <b><?php echo date('d M, Y H:i:s', strtotime($row->created_on)); ?></b></p>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
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
            </div>
            
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
        <?php endforeach; ?>

    </body>
</html>
