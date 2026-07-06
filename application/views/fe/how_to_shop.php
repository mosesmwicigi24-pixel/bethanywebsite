    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li>How To Shop</li>
            </ul>
        </div>
    </div>

    <div class="ps-page--single" id="about-us">        
        <div class="ps-about-intro pt-50">
            <div class="container">
                <div class="ps-section__header dynamic-data">
                	<?php foreach ($how_to_shop as $row): ?>
                    	<?php echo $row->how_to_shop; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>        
    </div>
