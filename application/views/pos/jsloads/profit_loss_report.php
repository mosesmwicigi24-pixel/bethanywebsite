                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="box">
                                                <div class="box-header">
                                                    <button type="button" class="btn btn-info pull-right" id="btn_export_profit_loss_report" title="Download Data in Excel Format">Export</button>
                                                </div>
                                                <div class="table-responsive no-padding">
                                                    <table class="table table-bordered table-hover" id="tbl_profit_loss_report">
                                                        <tbody>
                                                            <tr>
                                                                <td colspan="5" class="text-right"style="vertical-align: middle;">Period: <span class="font-weight-bold"><?php echo date('d-m-Y', strtotime($from_date)); ?> to <?php echo date('d-m-Y', strtotime($to_date)); ?></span></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Total Purchases</td>
                                                                <td class="text-right font-weight-bold"><?php echo number_format($profit_loss_report['total_purchases'],2); ?></td>
                                                                <td rowspan="4"></td>
                                                                <td>Total POS Sales</td>
                                                                <td class="text-right font-weight-bold"><?php echo number_format($profit_loss_report['total_pos_sales'],2); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Total Purchase Returns</td>
                                                                <td class="text-right font-weight-bold"><?php echo number_format($profit_loss_report['total_purchase_returns'],2); ?></td>
                                                                <td>Total POS Paid</td>
                                                                <td class="text-right font-weight-bold"><?php echo number_format($profit_loss_report['total_pos_paid_sales'],2); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td>Total Expenses</td>
                                                                <td class="text-right font-weight-bold"><?php echo number_format($profit_loss_report['total_expenses'],2); ?></td>
                                                                <td>Total POS Due</td>
                                                                <?php $total_pos_due = $profit_loss_report['total_pos_sales'] - $profit_loss_report['total_pos_paid_sales']; ?>
                                                                <td class="text-right font-weight-bold"><?php echo number_format($total_pos_due,2); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <td colspan="2"></td>
                                                                <td>Total Online Sales</td>
                                                                <td class="text-right font-weight-bold"><?php echo number_format($profit_loss_report['total_online_sales'],2); ?></td>
                                                            </tr>
                                                        </tbody>
                                                        <tfoot>
                                                            <tr>
                                                                <?php $gross_profit = ($profit_loss_report['total_pos_paid_sales'] + $profit_loss_report['total_online_sales'])-($profit_loss_report['total_purchases'] - $profit_loss_report['total_purchase_returns']); ?>
                                                                <td colspan="4" class="text-right font-weight-bold">Gross Profit</td>
                                                                <td class="text-right font-weight-bold"><?php echo number_format($gross_profit,2); ?></td>
                                                            </tr>
                                                            <tr>
                                                                <?php $net_profit = ($gross_profit - $profit_loss_report['total_expenses']); ?>
                                                                <td colspan="4" class="text-right font-weight-bold">Net Profit</td>
                                                                <td class="text-right font-weight-bold"><?php echo number_format($net_profit,2); ?></td>
                                                            </tr>
                                                        </tfoot>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>