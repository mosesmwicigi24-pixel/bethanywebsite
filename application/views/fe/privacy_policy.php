    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li>Privacy Policy</li>
            </ul>
        </div>
    </div>

    <div class="ps-page--single" id="about-us">        
        <div class="ps-about-intro pt-50">
            <div class="container">
                <div class="ps-section__header dynamic-data">
                	<?php foreach ($privacy_policy as $row): ?>
                    	<?php echo $row->privacy_policy; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>        
    </div>
