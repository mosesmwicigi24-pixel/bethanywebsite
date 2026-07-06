    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li>Terms &amp; Conditions</li>
            </ul>
        </div>
    </div>

    <div class="ps-page--single" id="about-us">        
        <div class="ps-about-intro pt-50">
            <div class="container">
                <div class="ps-section__header dynamic-data">
                	<?php foreach ($terms_and_conditions as $row): ?>
                    	<?php echo $row->terms_and_conditions; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>        
    </div>
