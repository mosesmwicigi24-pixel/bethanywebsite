                                        <div class="ps-block--checkout-total">
                                            <div class="ps-block__content">
                                                <table class="table ps-block__products">
                                                    <tbody>
                                                        <?php foreach($cart_data as $row): ?>
                                                            <tr>
                                                                <td><a href="<?php echo base_url(); ?>product/<?php echo $row['product_reference_id']; ?>"> <?php echo $row['name'];?><?php if ($row['product_variation_description'] != ''){ echo '<br>' . $row['product_variation_description']; } ?> ×<?php echo $row['quantity'];?></a>
                                                                    <p>SKU Code:<strong><?php echo $row['product_code']; ?></strong></p>
                                                                </td>
                                                                <td class="text-right"><?php echo $row['price_total']; ?></td>
                                                            </tr>
                                                        <?php endforeach; ?>
                                                    </tbody>
                                                </table>
                                                <h5 class="ps-block__title text-right"><small>Subtotal:</small> <span><?php echo $this->flexi_cart->sub_total();?></span></h5>
                                                <h5 class="ps-block__title text-right"><small>Tax:</small> <span><?php echo $this->flexi_cart->tax_total();?></span></h5>
                                                <h5 class="ps-block__title text-right"><small>Shipping:</small> <span><?php echo $this->flexi_cart->shipping_total();?></span></h5>
                                                <h5 class="ps-block__title text-right"><small>Discount:</small> <span><?php echo $this->flexi_cart->cart_savings_total();?></span></h5>
                                                <h4 class=" text-right"><small>Total:</small> <span><?php echo $this->flexi_cart->total();?></span></h4>
                                            </div>
                                        </div>