                            <div class="box-body table-responsive no-padding">
                                <table id="tbl_report" class="table table-bordered table-hover">
                                    <thead>
                                        </th>
                                        <th class="nk-tb-col"><span class="">Sale No</span></th>
                                        <th class="nk-tb-col tb-col-mb"><span class="">Total Amount</span></th>
                                        <th class="nk-tb-col tb-col-mb"><span class="">Date</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Customer Name</span></th>
                                        <th class="nk-tb-col tb-col-lg"><span class="">Total Amount (<?php echo $default_currency; ?>)</span></th>
                                        <th class="nk-tb-col tb-col-lg"><span class="">Paid Amount (<?php echo $default_currency; ?>)</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Payment Status (<?php echo $default_currency; ?>)</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Status</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Created By</span></th>
                                        <th class="nk-tb-col nk-tb-col-tools text-right"></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count = 1; $total_sales = 0; $total_paid = 0; $total_balance = 0; ?>
                                        <?php foreach ($sales_detailed_report as $row): ?>
                                            <?php if ($row->total_paid > $row->total_sale){ $balance = 0; } else { $balance = $row->total_sale - $row->total_paid; }  ?>
                                            <tr class="font-weight-bold bg-light">
                                                <td style=""><?php echo number_format($count); ?></td>
                                                <td style=""><?php echo $row->pos_sale_number; ?></td>
                                                <td style=""><?php echo date('d-m-Y', strtotime($row->created_on)); ?></td>
                                                <td style=""><?php echo $row->first_name . ' ' . $row->last_name; ?> </td>
                                                <td style=""><?php echo $row->system_user_first_name . ' ' . $row->system_user_last_name; ?></td>
                                                <td style="" class="text-right"><?php echo number_format($row->total_sale,2); ?></td>
                                                <td style="" class="text-right"><?php echo number_format($row->total_paid,2); ?></td>
                                                <td style="" class="text-right"><?php echo number_format($balance,2); ?></td>
                                            </tr>
                                            <?php if(!empty($row->details)): ?>
                                                <tr>
                                                    <td colspan="8">
                                                        <table class="table table-bordered">
                                                            <thead>
                                                                <tr class="text-italic">
                                                                    <th>Item Name</th>
                                                                    <th>Unit Price</th>
                                                                    <th>Quantity</th>
                                                                    <th>Net Cost</th>
                                                                    <th>Tax</th>
                                                                    <th>Tax Amount</th>
                                                                    <th>Discount</th>
                                                                    <th>Discount Amount</th>
                                                                    <th>Unit Cost</th>
                                                                    <th>Subtotal</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($row->details as $row2): ?>
                                                                    <tr class="text-italic">
                                                                        <td><?php echo $row2->product_name; ?><br><small><i>SKU: <?php echo $row2->product_sku_code; ?></i></small></td>
                                                                        <td><?php echo number_format($row2->unit_price,2); ?></td>
                                                                        <td><?php echo number_format($row2->quantity,2); ?></td>
                                                                        <td><?php echo number_format($row2->price_excl_tax,2); ?></td>
                                                                        <td>
                                                                            <?php echo number_format($row2->tax_rate_value,2); ?>%<br />
                                                                            <?php echo $row2->tax_rate_name; ?>[<?php echo $row2->tax_rate_code; ?>]
                                                                        </td>
                                                                        <td><?php echo number_format($row2->unit_tax,2); ?></td>
                                                                        <td><?php echo $row2->discount_type; ?><br>[<?php echo number_format($row2->discount_value,2); ?>]</td>
                                                                        <td><?php echo number_format($row2->discount_amount,2); ?></td>
                                                                        <td><?php echo number_format(($row2->unit_price - $row2->discount_amount),2); ?></td>
                                                                        <td><?php echo number_format($row2->sub_total,2); ?></td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            <?php endif; ?>
                                            <?php $total_sales = $total_sales + $row->total_sale; $total_paid = $total_paid + $row->total_paid; $total_balance = $total_balance + $balance; ?>
                                            <?php $count++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="font-weight-bold">
                                            <td colspan="5" class="text-right"><big>TOTAL</big></td>
                                            <td class="text-right"><big><?php echo number_format($total_sales,2); ?></big></td>
                                            <td class="text-right"><big><?php echo number_format($total_paid,2); ?></big></td>
                                            <td class="text-right"><big><?php echo number_format($total_balance,2); ?></big></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>