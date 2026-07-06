<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">

            	<div class="nk-block nk-block-lg">
                    <div class="nk-block-head nk-block-head-sm">
					    <div class="nk-block-between">
					        <div class="nk-block-head-content"><h5 class="nk-block-title page-title"><em class="icon ni ni-file-docs"></em> Stock Report</h5></div>
					    </div>
					</div>

                    <div class="card card-preview">
                    	<div class="spinner display-none" id="stock_report_loader">
                            <div class="rect1"></div>
                            <div class="rect2"></div>
                            <div class="rect3"></div>
                        </div>
                        <div class="card-header">
                            <h6>Report Details <span class="pull-right"><button id="btn_export_report" class="btn btn-sm btn-info"><em class="icon ni ni-file-xls mr-1"></em>Export</button></span></h6>
                        </div>
                        <div class="card-inner" id="div_stock_report">
                            
                            <div class="box-body table-responsive no-padding">
                                <table id="tbl_report" class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="bg-primary">
                                            <th style="">#</th>
                                            <th style="">Product Name</th>
                                            <th style="">Product Code</th>
                                            <th style="" class="text-right">Unit Price(<?php echo $default_currency; ?>)</th>
                                            <!-- <th style="">Unit</th> -->
                                            <th style="" class="text-right">Current Stock</th>
                                            <th style="" class="text-right">Stock Value(<?php echo $default_currency; ?>)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count = 1; $total_stock_value = 0; $total_quantity = 0; ?>
                                        <?php foreach ($products as $row): ?>
                                            <?php if ($row->sale_price > 0){ $product_price = $row->sale_price; } else { $product_price = $row->regular_price; } ?>
                                            <tr>
                                                <td style=""><?php echo number_format($count); ?></td>
                                                <td style=""><?php echo $row->product_name; ?></td>
                                                <td style=""><?php echo $row->product_sku_code; ?></td>
                                                <td style="" class="text-right"><?php echo number_format($product_price,2); ?></td>
                                                <!-- <td style=""><?php echo $row->unit_name; ?></td> -->
                                                <td style="" class="text-right"><?php echo number_format($row->available_stock); ?></td>
                                                <td style="" class="text-right"><?php echo number_format(($product_price * $row->available_stock),2); ?></td>
                                            </tr>
                                            <?php $total_stock_value = $total_stock_value + ($product_price * $row->available_stock); $total_quantity = $total_quantity + $row->available_stock; ?>
                                            <?php $count++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="font-weight-bold">
                                            <td colspan="4" class="text-right">TOTAL</td>
                                            <td class="text-right"><?php echo number_format($total_quantity,2); ?></td>
                                            <td class="text-right"><?php echo number_format($total_stock_value,2); ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>



                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
	$(document).ready(function() {
		
	});
</script>