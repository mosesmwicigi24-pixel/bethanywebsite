                                                <?php if ($num_orders > 0): ?>
                                                    <div class="table-responsive">
                                                        <table class="table table-bordered table-striped table-condensed" id="my-orders-table">
                                                            <thead>
                                                                <tr class="first last">
                                                                    <th class="font-weight-500">Order #</th>
                                                                    <th class="font-weight-500">Order Date</th>
                                                                    <th class="font-weight-500">Shipping Method</th>
                                                                    <th class="font-weight-500">Order Total</th>
                                                                    <th class="font-weight-500 text-center">Status</th>
                                                                    <th class="font-weight-500 text-center">Action</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php foreach ($orders as $row2): ?>
                                                                    <tr class="first odd">
                                                                        <td  <?php if($row2->ord_status == 5):?> style="color: #999"<?php endif; ?>>
                                                                            <a href="<?php echo base_url(); ?>account/order/<?php echo $row2->ord_order_number; ?>"><strong><?php echo $row2->ord_order_number; ?></strong></a>
                                                                        </td>
                                                                        <td  <?php if($row2->ord_status == 5):?> style="color: #999"<?php endif; ?>><?php echo date('jS M Y', strtotime($row2->ord_date)); ?></td>
                                                                        <td  <?php if($row2->ord_status == 5):?> style="color: #999"<?php endif; ?>><?php echo $row2->ord_shipping_method; ?></td>
                                                                        <td  <?php if($row2->ord_status == 5):?> style="color: #999"<?php endif; ?>><span class="price"><?php echo $row2->ord_currency; ?> <?php echo number_format($row2->ord_total,2); ?></span></td>
                                                                        <td class="text-center">
                                                                            <?php if ($row2->ord_order_status == 0): ?>
                                                                                <span class="badge badge-pill badge-warning"><i class="icon-hourglass"></i> Awaiting Payment</span>
                                                                            <?php elseif ($row2->ord_order_status == 1): ?>
                                                                                <span class="badge badge-pill badge-info"><i class="icon-refresh2"></i> Processing</span>
                                                                            <?php elseif ($row2->ord_order_status == 2): ?>
                                                                                <span class="badge badge-pill badge-primary"><i class="icon-cart-remove"></i> Dispatched</span>
                                                                            <?php elseif ($row2->ord_order_status == 3): ?>
                                                                                <span class="badge badge-pill badge-success"><i class="icon-checkmark-circle"></i> Completed</span>
                                                                            <?php elseif ($row2->ord_order_status == 4): ?>
                                                                                <span class="badge badge-pill badge-danger"><i class="icon-cross-circle"></i> Cancelled</span>
                                                                            <?php endif; ?>
                                                                        </td>
                                                                        <td class="text-center">
                                                                            <?php if ($row2->ord_order_status == 0): ?>
                                                                                <a href="<?php echo base_url();?>checkout/payment/<?php echo $row2->ord_order_number; ?>" class="btn btn-xs btn-success btn-tooltip" title="Pay for my order"><i class="icon-credit-card"></i> Pay</a>
                                                                                <a href="javascript:void(0);" onclick="cancel_order('<?php echo $row2->ord_order_number ?>', 'Orders');" class="btn btn-xs btn-danger btn-tooltip" title="Cancel Order"><i class="icon-cross-circle"></i> Cancel</a>  
                                                                            <?php elseif ($row2->ord_order_status == 4): ?>
                                                                                <a href="javascript:void(0);" onclick="restore_order('<?php echo $row2->ord_order_number ?>', 'Orders');" class="btn btn-xs btn-success btn-tooltip" title="Restore order"><i class="icon-refresh"></i> Restore</a>  
                                                                            <?php endif; ?>
                                                                        </td>
                                                                    </tr>
                                                                <?php endforeach; ?>
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                <?php else: ?>
                                                    <p><i>You have not made any orders yet</i></p>
                                                <?php endif; ?>