    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li><a href="<?php echo base_url(); ?>affiliates">Affiliate Program</a></li>
                <li>Affiliate Program Terms &amp; Conditions</li>
            </ul>
        </div>
    </div>

    <div class="ps-page--single" id="about-us">        
        <div class="pt-50 mb-50">
            <div class="container">
                <div class="ps-section__header dynamic-data">
                	<?php foreach ($affiliate_terms as $row): ?>
                    	<?php echo $row->affiliate_terms; ?>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>        
    </div>
