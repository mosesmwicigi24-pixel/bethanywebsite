<footer class="ps-footer ps-footer--2 bg-light">
    <div class="container">
        <div class="ps-footer__content">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6">
                    <aside class="widget widget_footer">
                        <h4 class="widget-title">About Us</h4>
                        <ul class="ps-list--link">
                            <li><a href="<?= base_url('about-us') ?>">About Us</a></li>
                            <li><a href="<?= base_url('terms-and-conditions') ?>">Terms &amp; Conditions</a></li>
                            <li><a href="<?= base_url('return-policy') ?>">Return Policy</a></li>
                            <li><a href="<?= base_url('privacy-policy') ?>">Privacy Policy</a></li>
                        </ul>
                    </aside>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6">
                    <aside class="widget widget_footer">
                        <h4 class="widget-title">Help Center</h4>
                        <ul class="ps-list--link">
                            <li><a href="<?= base_url('track') ?>">Track Order</a></li>
                            <li><a href="<?= base_url('how-to-shop') ?>">How To Shop</a></li>
                            <li><a href="<?= base_url('faqs') ?>">Frequently Asked Questions</a></li>
                            <li><a href="<?= base_url('contact-us') ?>">Contact Us</a></li>
                        </ul>
                        <h4 class="widget-title mt-20">Make Money With Us</h4>
                        <ul class="ps-list--link">
                            <?php if ($this->session->userdata('bgs_affiliate_login_state')): ?>
                                <li><a href="<?= base_url('affiliates/account') ?>">My Affiliate Account</a></li>
                            <?php else: ?>
                                <li><a href="<?= base_url('affiliates') ?>">Become Our Affiliate Partner</a></li>
                                <li><a href="<?= base_url('affiliates/login') ?>">Affiliate Login</a></li>
                            <?php endif; ?>
                        </ul>
                    </aside>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6">
                    <aside class="widget widget_footer">
                        <h4 class="widget-title mb-10">Account</h4>
                        <ul class="ps-list--link">
                            <li><a href="<?= base_url('account') ?>">My Account</a></li>
                            <li><a href="<?= base_url('account/orders') ?>">My Orders</a></li>
                            <li><a href="<?= base_url('account/favorites') ?>">My Favorites</a></li>
                            <li><a href="<?= base_url('compare') ?>">Compare Products</a></li>
                            <li><a href="<?= base_url('cart') ?>">Shopping Cart</a></li>
                        </ul>
                    </aside>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-4 col-sm-6 col-6">
                    <aside class="widget widget_footer">
                        <h4 class="widget-title">Contact Us</h4>
                        <div class="widget_content">
                            <?php foreach ($store_information as $row): ?>
                                <p><b><i class="icon-telephone"></i></b> <?= $row->phone_number . ($row->mobile_number != '' ? ' / ' . $row->mobile_number : '') ?></p>
                                <p><b><i class="icon-envelope"></i></b> <?= $row->email_address ?></p>
                                <p><b><i class="icon-map-marker"></i></b> <?= $row->physical_address ?></p>
                                <?php if ($row->opening_hours != ''): ?>
                                    <p><b><i class="icon-clock"></i></b> <?= $row->opening_hours ?></p>
                                <?php endif; ?>
                            <?php endforeach; ?>
                            <ul class="ps-list--social">
                                <?php foreach ($store_information as $row): ?>
                                    <?php if ($row->sm_facebook != ''): ?>
                                        <li><a class="facebook" href="<?= $row->sm_facebook ?>" target="_blank" rel="noopener noreferrer"><i class="fa fa-facebook"></i></a></li>
                                    <?php endif; ?>
                                    <?php if ($row->sm_twitter != ''): ?>
                                        <li><a class="twitter" href="<?= $row->sm_twitter ?>" target="_blank" rel="noopener noreferrer"><i class="fa fa-twitter"></i></a></li>
                                    <?php endif; ?>
                                    <?php if ($row->sm_instagram != ''): ?>
                                        <li><a class="instagram" href="<?= $row->sm_instagram ?>" target="_blank" rel="noopener noreferrer"><i class="fa fa-instagram"></i></a></li>
                                    <?php endif; ?>
                                    <?php if ($row->sm_youtube != ''): ?>
                                        <li><a class="youtube" href="<?= $row->sm_youtube ?>" target="_blank" rel="noopener noreferrer"><i class="fa fa-youtube"></i></a></li>
                                    <?php endif; ?>
                                    <?php if ($row->sm_linkedin != ''): ?>
                                        <li><a class="linkedin" href="<?= $row->sm_linkedin ?>" target="_blank" rel="noopener noreferrer"><i class="fa fa-linkedin"></i></a></li>
                                    <?php endif; ?>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
        <div class="ps-footer__copyright">
            <p>&copy; <?= date('Y') ?> Bethany House. All Rights Reserved</p>
            <p>
                <span>Pay Safely Via</span>
                <a href="#"><img src="<?= base_url('assets/fe/img/mpesa2.png') ?>" alt="M-Pesa"></a>
                <a href="#"><img src="<?= base_url('assets/fe/img/pesapal.png') ?>" alt="Pesapal"></a>
            </p>
        </div>
    </div>
</footer>
</div>
<div id="back2top"><i class="pe-7s-angle-up"></i></div>
<div class="ps-site-overlay"></div>

<div class="ps-search" id="site-search"><a class="ps-btn--close" href="#"></a>
    <div class="ps-search__content">
        <form class="ps-form--primary-search" action="#" method="post">
            <input class="form-control" type="text" placeholder="Search for...">
            <button><i class="aroma-magnifying-glass"></i></button>
        </form>
    </div>
</div>

<div class="modal fade" id="product-quickview" tabindex="-1" role="dialog" aria-labelledby="product-quickview" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <span class="modal-close" data-dismiss="modal"><i class="icon-cross2"></i></span>
            <div id="div_product_quickview" style="min-height: 500px"></div>
        </div>
    </div>
</div>

<script async src="https://www.googletagmanager.com/gtag/js?id=UA-227860423-1"></script>
<script>
  window.dataLayer = window.dataLayer ||;
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'UA-227860423-1');
</script>

<script async src="<?= base_url('assets/fe/plugins/popper.min.js') ?>"></script>
<script async src="<?= base_url('assets/fe/plugins/owl-carousel/owl.carousel.min.js') ?>"></script>
<script async src="<?= base_url('assets/fe/plugins/bootstrap4/js/bootstrap.min.js') ?>"></script>
<script async src="<?= base_url('assets/fe/plugins/imagesloaded.pkgd.min.js') ?>"></script>
<script async src="<?= base_url('assets/fe/plugins/masonry.pkgd.min.js') ?>"></script>
<script async src="<?= base_url('assets/fe/plugins/isotope.pkgd.min.js') ?>"></script>
<script async src="<?= base_url('assets/fe/plugins/jquery.matchHeight-min.js') ?>"></script>
<script async src="<?= base_url('assets/fe/plugins/slick/slick/slick.min.js') ?>"></script>
<script async src="<?= base_url('assets/fe/plugins/jquery-bar-rating/dist/jquery.barrating.min.js') ?>"></script>
<script async src="<?= base_url('assets/fe/plugins/slick-animation.min.js') ?>"></script>
<script async src="<?= base_url('assets/fe/plugins/lightGallery-master/dist/js/lightgallery-all.min.js') ?>"></script>
<script async src="<?= base_url('assets/fe/plugins/jquery-ui/jquery-ui.min.js') ?>"></script>
<script async src="<?= base_url('assets/fe/plugins/sticky-sidebar/dist/sticky-sidebar.min.js') ?>"></script>
<script async src="<?= base_url('assets/fe/plugins/jquery.slimscroll.min.js') ?>"></script>
<script async src="<?= base_url('assets/fe/plugins/select2/dist/js/select2.full.min.js') ?>"></script>
<script async src="<?= base_url('assets/be/js/plugins/notifications/sweet_alert.min.js') ?>"></script>
<script async src="<?= base_url('assets/be/js/plugins/notifications/noty.min.js') ?>"></script>
<script async src="<?= base_url('assets/be/js/plugins/jquery-validation/jquery.validate.js') ?>"></script>
<script async src="<?= base_url('assets/be/js/plugins/jquery-loading-overlay/src/loadingoverlay.js') ?>"></script>
<script async src="<?= base_url('assets/be/js/plugins/forms/styling/uniform.min.js') ?>"></script>
<script async src="<?= base_url('assets/be/js/plugins/pickers/pickadate/picker.js') ?>"></script>
<script async src="<?= base_url('assets/be/js/plugins/pickers/pickadate/picker.date.js') ?>"></script>
<script async src="<?= base_url('assets/be/js/plugins/pickers/pickadate/picker.time.js') ?>"></script>
<script async src="<?= base_url('assets/be/js/plugins/pickers/pickadate/legacy.js') ?>"></script>
<script async src="<?= base_url('assets/fe/plugins/gmap3.min.js') ?>"></script>
<script async src="<?= base_url('assets/fe/plugins/lazyload/lazyload.min.js') ?>"></script>
<script async src="<?= base_url('assets/fe/js/jquery.redirect.js') ?>"></script>
<script async src="<?= base_url('assets/fe/js/bootstrap-show-password.min.js') ?>"></script>

<script async src="<?= base_url(auto_version('assets/fe/js/main.js')) ?>"></script>
<script async src="<?= base_url(auto_version('assets/fe/js/custom.js')) ?>"></script>

</body>
</html>
