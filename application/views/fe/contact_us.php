    <div class="ps-page--single" id="contact-us">
        <div class="ps-breadcrumb">
            <div class="container">
                <ul class="breadcrumb">
                    <li><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li>Contact Us</li>
                </ul>
            </div>
        </div>
        <div id="contact-map" data-address="Bethany House - #1 Holy Communion Suppliers, Sonalux Building 7th Floor Room 18 Moi Avenue Near Nairobi Sport House The Building with Family Bank" data-title="Bethany House" data-zoom="17"></div>
        <div class="ps-contact-info">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="ps-section__header">
                            <h3>Contact Us For Any Questions</h3>
                        </div>
                        <div class="ps-section__content">
                            <div class="row">
                                <?php foreach ($store_information as $row): ?>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 ">
                                        <div class="ps-block--contact-info">
                                            <h4>Our Contacts</h4>
                                            <p><a href="#"><span class="__cf_email__"><i class="icon-envelope"></i> <?php echo $row->email_address; ?></span></a><span><i class="icon-telephone"></i> <?php echo $row->phone_number; ?></span></p>
                                        </div>
                                    </div>
                                    <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 ">
                                        <div class="ps-block--contact-info">
                                            <h4>Our Location</h4>
                                            <p><span><i class="icon-map-marker"></i> <?php echo $row->physical_address; ?></span></p>
                                        </div>
                                    </div>
                                    <?php if ($row->opening_hours != ''): ?>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 ">
                                            <div class="ps-block--contact-info">
                                                <h4>Our Opening Hours</h4>
                                                <p><span><i class="icon-clock"></i> <?php echo $row->opening_hours; ?></span></p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6 col-12 ">
                                    <div class="ps-block--contact-info">
                                        <h4>Connect With Us</h4>
                                        <ul class="ps-list--social">
                                            <?php foreach ($store_information as $row): ?>
                                                <?php if ($row->sm_facebook != ''): ?>
                                                    <li><a class="facebook" href="<?php echo $row->sm_facebook; ?>" target="_blank"><i class="fa fa-facebook"></i></a></li>
                                                <?php endif; ?>
                                                <?php if ($row->sm_twitter != ''): ?>
                                                    <li><a class="twitter" href="<?php echo $row->sm_twitter; ?>" target="_blank"><i class="fa fa-twitter"></i></a></li>
                                                <?php endif; ?>
                                                <?php if ($row->sm_instagram != ''): ?>
                                                    <li><a class="instagram" href="<?php echo $row->sm_instagram; ?>" target="_blank"><i class="fa fa-instagram"></i></a></li>
                                                <?php endif; ?>
                                                <?php if ($row->sm_youtube != ''): ?>
                                                    <li><a class="youtube" href="<?php echo $row->sm_youtube; ?>" target="_blank"><i class="fa fa-youtube"></i></a></li>
                                                <?php endif; ?>
                                                <?php if ($row->sm_linkedin != ''): ?>
                                                    <li><a class="linkedin" href="<?php echo $row->sm_linkedin; ?>" target="_blank"><i class="fa fa-linkedin"></i></a></li>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="ps-contact-form bg-white">
                            <div class="">
                                <form class="ps-form--contact-us" id="frm_contact" name="frm_contact" onsubmit="return submit_contact();" method="post">
                                    <h3>Send Us A Message</h3>
                                    <div class="row">
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 ">
                                            <div class="form-group">
                                                <input class="form-control" type="text" id="contact_name" name="contact_name" placeholder="Name *">
                                            </div>
                                        </div>
                                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12 ">
                                            <div class="form-group">
                                                <input class="form-control" type="email" id="contact_email" name="contact_email" placeholder="Email *">
                                            </div>
                                        </div>
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                                            <div class="form-group">
                                                <input class="form-control" type="text" id="contact_subject" name="contact_subject" placeholder="Subject *">
                                            </div>
                                        </div>
                                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12 col-12 ">
                                            <div class="form-group">
                                                <textarea class="form-control" rows="5" id="contact_message" name="contact_message" placeholder="Message"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group submit">
                                        <button type="submit" id="btn_contact" class="ps-btn ps-btn--rounded">Send message</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBtTe9hVdmKWxCVpfvCAIX8feOW8ygH_GI"></script>