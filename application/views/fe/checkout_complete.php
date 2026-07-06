    <div class="ps-page--single">
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li>Order Complete</li>
                </ul>
            </div>
        </div>
        <div class="ps-vendor-banner bg--cover" data-background="<?php echo base_url();?>assets/fe/img/bg/vendor.jpg">
            <div class="container">
                <h2>Order successfully completed</h2>
                <h4 class="text-white mb-30">Please hold on as we process your order and ship it to you.<br>Thank you for shopping with us!</h4>
                <?php foreach ($order as $row): ?>
                    <a class="ps-btn ps-btn--lg" href="<?php echo base_url(); ?>account/track/<?php echo $row->ord_order_number; ?>">Track My Order</a>
                <?php endforeach; ?>
            </div>
        </div>


    </div>
