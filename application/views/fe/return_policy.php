    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li>Return Policy</li>
            </ul>
        </div>
    </div>

    <div class="ps-page--single" id="about-us">        
        <div class="ps-about-intro pt-50">
            <div class="container">
                <div class="ps-section__header dynamic-data">
                    <?php foreach ($return_policy as $row): ?>
                        <?php echo $row->return_policy; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>        
    </div>
