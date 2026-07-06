                                                    <div class="ps-block__content">
                                                        <?php foreach($pickup_location as $row): ?>
                                                            <div class="row">
                                                                <div class="col-md-6">
                                                                    <p class="font-weight-bold">Delivery Address:</p>
                                                                    <p><?php echo $row->pickup_location_address; ?></p>
                                                                    <?php if ($row->close_to != ''): ?>
                                                                        <p class="font-weight-bold">Close to:</p>
                                                                        <p><?php echo $row->close_to; ?></p>
                                                                    <?php endif; ?>
                                                                    <p class="font-weight-bold">Shipping Fee:</p>
                                                                    <p><?php echo $default_currency; ?> <?php echo number_format($row->shipping_fee,2); ?></p>
                                                                </div>
                                                                <div class="col-md-6">
                                                                    <p class="font-weight-bold">Opening Hours:</p>
                                                                    <p><?php echo $row->opening_hours; ?></p>
                                                                    <p class="font-weight-bold">Pickup Period:</p>
                                                                    <p><?php echo $row->pickup_period; ?></p>
                                                                </div>
                                                            </div>
                                                        <?php endforeach; ?>                                                        
                                                    </div>