    <div class="ps-breadcrumb">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                <li>About Us</li>
            </ul>
        </div>
    </div>
    <?php foreach ($about_us as $row): ?>

        <div class="ps-page--single" id="about-us">        
            <?php if($row->cover_image != '' && file_exists("./uploads/about_us_cover_image/" . $row->cover_image)): ?>
                <img class="lazyload" data-src="<?php echo base_url();?>uploads/about_us_cover_image/<?php echo $row->cover_image; ?>" src="<?php echo slider_placeholder; ?>">
            <?php endif; ?>
            <div class="ps-about-intro">
                <div class="container">
                    <div class="ps-section__header dynamic-data">
                        <?php echo $row->about_us; ?>
                    </div>
                </div>
            </div>        
        </div>

        <?php if ($row->mission != '' || $row->vision != '' || $row->core_values != ''): ?>
            <div class="ps-section--vendor ps-vendor-about">
                <div class="container">
                    <div class="ps-section__content">
                        <div class="row">
                            <?php if ($row->mission != ''): ?>
                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 ">
                                    <div class="ps-block--icon-box-2">
                                        <div class="ps-block__thumbnail">
                                            <i class="fa fa-bullseye fa-3x"></i>
                                        </div>
                                        <div class="ps-block__content">
                                            <h4>Our Mission</h4>
                                            <div class="ps-block__desc" data-mh="about-desc">
                                                <?php echo $row->mission; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($row->vision != ''): ?>
                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 ">
                                    <div class="ps-block--icon-box-2">
                                        <div class="ps-block__thumbnail">
                                            <i class="fa fa-eye fa-3x"></i>
                                        </div>
                                        <div class="ps-block__content">
                                            <h4>Our Vision</h4>
                                            <div class="ps-block__desc" data-mh="about-desc">
                                                <?php echo $row->vision; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <?php if ($row->core_values != ''): ?>
                                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 col-12 ">
                                    <div class="ps-block--icon-box-2">
                                        <div class="ps-block__thumbnail">
                                            <i class="fa fa-check-circle fa-3x"></i>
                                        </div>
                                        <div class="ps-block__content">
                                            <h4>Our Core Values</h4>
                                            <div class="ps-block__desc" data-mh="about-desc">
                                                <?php echo $row->core_values; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>