                                                    <div class="ps-block__content">
                                                        <?php foreach($shipping_zone as $row): ?>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <p class="font-weight-bold">Shipping Fee:</p>
                                                                    <?php if ($row->shipping_method == 0): ?>
                                                                        <p>Free Shipping</p>
                                                                    <?php else: ?>
                                                                        <p><?php echo $default_currency; ?> <?php echo number_format($row->shipping_fee,2); ?></p>
                                                                    <?php endif; ?>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>                                                        
                                                    </div>