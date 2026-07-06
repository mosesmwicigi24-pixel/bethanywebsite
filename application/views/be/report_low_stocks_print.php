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
                            <h3 class="text-uppercase text-normal mb-0">Items on Reorder Level</h3>
                            
                            <p style="font-size: 12px">
                                <?php foreach ($low_stocks_report as $row): ?>
                                    <b>Outlet:</b> <?php echo $row->outlet_name; ?><br />
                                <?php endforeach; ?>  
                                <b>Print Date:</b> <?php echo date('Y-m-d H:i:s'); ?><br />
                            </p>
                        </td>
                    </tr>
                </table>
                
            </div>
        </div>

        <table class="table table-condensed" data-auto-responsive="true">
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="text-center">Reorder Level</th>
                    <th class="text-center">Current Stock</th>
                    <th class="text-center">Difference</th>
                    <th class="text-center">Selling Price (<?php echo $default_currency; ?>)</th>
                </tr>
            </thead>
            <tbody> 
                <?php if(!empty($row->inventory)): ?>
                    <?php foreach ($row->inventory as $row2): ?>
                        <?php
                            if ($row2->sale_price > 0){ $selling_price = $row2->sale_price; } else { $selling_price = $row2->regular_price; }
                        ?>
                        <?php
                            $variation_description = '';
                            if(!empty($row2->attributes)){
                                foreach ($row2->attributes as $row3){
                                    $variation_description = $variation_description . $row3->product_attribute_name . ' : <b>' . $row3->product_attribute_value . '</b>, ';
                                }
                                $variation_description =  '<br>~ ' . substr($variation_description,0,-2) . '<br>';
                            }
                        ?>
                        <tr>
                            <td><?php echo $row2->product_name; ?><?php echo $variation_description; ?></td>
                            <td class="text-center"><?php echo number_format($row2->reorder_level,2); ?></td>
                            <td class="text-center"><?php echo number_format($row2->available_stock,2); ?></td>
                            <td class="text-center"><?php echo number_format($row2->reorder_level - $row2->available_stock,2); ?></td>
                            <td class="text-center"><?php echo number_format($selling_price,2) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
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
