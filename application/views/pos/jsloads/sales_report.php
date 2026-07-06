                            <div class="box-body table-responsive no-padding">
                                <table id="tbl_report" class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="bg-primary">
                                            <th style="">#</th>
                                            <th style="">Sale No.</th>
                                            <th style="">Date</th>
                                            <th style="">Customer Name</th>
                                            <th style="">Created By</th>
                                            <th style="" class="text-right">Sale Total(<?php echo $default_currency; ?>)</th>
                                            <th style="" class="text-right">Total Paid(<?php echo $default_currency; ?>)</th>
                                            <th style="" class="text-right">Amount Due(<?php echo $default_currency; ?>)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count = 1; $total_sales = 0; $total_paid = 0; $total_balance = 0; ?>
                                        <?php foreach ($sales_report as $row): ?>
                                            <?php if ($row->total_paid > $row->total_sale){ $balance = 0; } else { $balance = $row->total_sale - $row->total_paid; }  ?>
                                            <tr>
                                                <td style=""><?php echo number_format($count); ?></td>
                                                <td style=""><?php echo $row->pos_sale_number; ?></td>
                                                <td style=""><?php echo date('d-m-Y', strtotime($row->created_on)); ?></td>
                                                <td style=""><?php echo $row->first_name . ' ' . $row->last_name; ?> </td>
                                                <td style=""><?php echo $row->system_user_first_name . ' ' . $row->system_user_last_name; ?></td>
                                                <td style="" class="text-right"><?php echo number_format($row->total_sale,2); ?></td>
                                                <td style="" class="text-right"><?php echo number_format($row->total_paid,2); ?></td>
                                                <td style="" class="text-right"><?php echo number_format($balance,2); ?></td>
                                            </tr>
                                            <?php $total_sales = $total_sales + $row->total_sale; $total_paid = $total_paid + $row->total_paid; $total_balance = $total_balance + $balance; ?>
                                            <?php $count++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="font-weight-bold">
                                            <td colspan="5" class="text-right">TOTAL</td>
                                            <td class="text-right"><?php echo number_format($total_sales,2); ?></td>
                                            <td class="text-right"><?php echo number_format($total_paid,2); ?></td>
                                            <td class="text-right"><?php echo number_format($total_balance,2); ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>