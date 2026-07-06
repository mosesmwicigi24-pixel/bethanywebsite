                            <div class="box-body table-responsive no-padding">
                                <table id="tbl_report" class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="bg-primary">
                                            <th style="">#</th>
                                            <th style="">Date</th>
                                            <th style="">Description</th>
                                            <th style="">Reference No.</th>
                                            <th style="" class="text-right">Amount(<?php echo $default_currency; ?>)</th>
                                            <th style="">Note</th>
                                            <th style="">Created By</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $count = 1; $total_expenses = 0; ?>
                                        <?php foreach ($expense_report as $row): ?>
                                            <tr>
                                                <td style=""><?php echo number_format($count); ?></td>
                                                <td style=""><?php echo date('d-m-Y', strtotime($row->created_on)); ?></td>
                                                <td style=""><?php echo $row->expense_description; ?> </td>
                                                <td style=""><?php echo $row->expense_reference_number; ?> </td>
                                                <td style="" class="text-right"><?php echo number_format($row->expense_amount,2); ?></td>
                                                <td style="" class="text-right"><?php echo $row->expense_note; ?></td>
                                                <td style=""><?php echo $row->system_user_first_name . ' ' . $row->system_user_last_name; ?></td>
                                            </tr>
                                            <?php $total_expenses = $total_expenses + $row->expense_amount; ?>
                                            <?php $count++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="font-weight-bold">
                                            <td colspan="4" class="text-right">TOTAL</td>
                                            <td class="text-right"><?php echo number_format($total_expenses,2); ?></td>
                                            <td colspan="2"></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>