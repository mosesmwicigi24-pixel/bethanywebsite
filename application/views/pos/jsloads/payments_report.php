                            <div class="box-body table-responsive no-padding">
                                <table id="tbl_report" class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="bg-primary">
                                            <th style="">#</th>
                                            <th style="">Sale No.</th>
                                            <th style="">Date</th>
                                            <th style="">Customer Name</th>
                                            <th style="">Received By</th>
                                            <th style="">Payment Type</th>
                                            <th style="">Reference No.</th>
                                            <th style="">Payment Note</th>
                                            <th style="" class="text-right">Amount Paid(<?php echo $default_currency; ?>)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count = 1; $total_payments = 0; ?>
                                        <?php foreach ($payments_report as $row): ?>
                                            <tr>
                                                <td style=""><?php echo number_format($count); ?></td>
                                                <td style=""><?php echo $row->pos_sale_number; ?></td>
                                                <td style=""><?php echo date('d-m-Y', strtotime($row->created_on)); ?></td>
                                                <td style=""><?php echo $row->first_name . ' ' . $row->last_name; ?> </td>
                                                <td style=""><?php echo $row->system_user_first_name . ' ' . $row->system_user_last_name; ?></td>
                                                <td style="" class="text-right"><?php echo $row->payment_method; ?></td>
                                                <td style="" class="text-right"><?php echo $row->reference_number; ?></td>
                                                <td style="" class="text-right"><?php echo $row->payment_note; ?></td>
                                                <td style="" class="text-right"><?php echo number_format($row->payment_amount,2); ?></td>
                                            </tr>
                                            <?php $total_payments = $total_payments + $row->payment_amount; ?>
                                            <?php $count++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="font-weight-bold">
                                            <td colspan="8" class="text-right">TOTAL</td>
                                            <td class="text-right"><?php echo number_format($total_payments,2); ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>