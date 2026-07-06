<!DOCTYPE html>
<html lang="zxx" class="js">
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Devlab Africa">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <link rel="shortcut icon" href="<?php echo base_url();?>assets/pos/images/favicon.png">
        <title>Select Outlet | Bethany House POS</title>
        <?php
            function auto_version($file){
                if(!file_exists($file)) return $file;
                $mtime = filemtime($file);
                return preg_replace('{\\.([^./]+)$}', ".\$1?$mtime", $file);
            }
        ?>

        <link rel="stylesheet" type="text/css" href="<?php echo base_url() . auto_version('assets/pos/css/bethany.css'); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() . auto_version('assets/pos/css/skins/theme-egyptian.css'); ?>">

        <script type="text/javascript">
            var baseDir = '<?php echo base_url(); ?>';
        </script>

    </head>
    <body class="nk-body bg-white npc-general pg-auth" >
        <div class="nk-app-root">
            <div class="nk-main ">
                <div class="nk-wrap nk-wrap-nosidebar">
                    <div class="nk-content ">
                        <div class="nk-block nk-block-middle nk-auth-body  wide-xs">
                            <div class="card">
                                <div class="card-inner card-inner-lg">
                                    <div class="brand-logo pb-5 text-center">
                                        <a href="#" class="logo-link">
                                            <img class="logo-dark logo-img logo-img-lg" src="<?php echo base_url();?>assets/pos/images/logo-dark.png" srcset="<?php echo base_url();?>assets/pos/images/logo-dark2x.png 2x" alt="logo-dark">
                                        </a>
                                    </div>
                                    <div class="nk-block-head">
                                        <div class="nk-block-head-content text-center">
                                            <h4 class="nk-block-title">Select an Outlet</h4>
                                            <!-- <div class="nk-block-des">
                                                <p>Enter your credentials below to login.</p>
                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="row g-gs">
                                        <?php if ($num_user_outlets > 0): ?>
                                            <?php foreach ($user_outlets as $row): ?>
                                                <div class="col-lg-12">
                                                    <a href="javascript:void(0);" data-outlet-id="<?php echo $row->outlet_id; ?>" class="card text-soft a-select-outlet">
                                                        <div class="card-inner alert-pro alert-primary">
                                                            <div class="align-center justify-between">
                                                                <img src="<?php echo base_url();?>assets/pos/images/outlet2.png" class="pr-1" alt="">
                                                                <div class="g">
                                                                    <h6 class="title"><?php echo $row->outlet_name; ?></h6>
                                                                    <p><small><em class="icon ni ni-map-pin-fill"></em> <?php echo $row->outlet_physical_location; ?></small></p>
                                                                </div>
                                                                <div class="g">
                                                                    <span class="btn btn-icon btn-trigger mr-n1">
                                                                        <em class="icon ni ni-chevron-right"></em>
                                                                    </span>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                             <?php endforeach; ?>
                                        <?php else: ?>
                                            <div class="col-lg-12">
                                                <div class="alert alert-warning alert-icon">
                                                    <em class="icon ni ni-alert-circle"></em> You have not been <strong>assigned</strong> to any outlet. Please confirm with the Administrator.
                                                </div>

                                            </div>
                                        <?php endif; ?>   
                                    </div>

                                    <div class="form-note-s2 text-center pt-4"><a href="<?php echo base_url();?>pos/auth/logout"><em class="icon ni ni-signout"></em> Sign Out</a></div>
                                </div>
                            </div>
                        </div>
                        <div class="nk-footer nk-auth-footer-full">
                            <div class="container wide-lg">
                                <div class="row g-3">
                                    <div class="col-lg-6 order-lg-last">
                                        <ul class="nav nav-sm justify-content-center justify-content-lg-end">
                                            <li class="nav-item"><a class="nav-link" href="#">Terms & Condition</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#">Privacy Policy</a></li>
                                            <li class="nav-item"><a class="nav-link" href="#">Help</a></li>
                                        </ul>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="nk-block-content text-center text-lg-left">
                                            <p class="text-soft">&copy; <?php echo date('Y'); ?> Bethany House POS. <small>Powered by <a href="https://devlabafrica.com" target="_blank">Devlab Africa</a></small></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <script src="<?php echo base_url();?>assets/pos/js/bundle.js"></script>

        <script src="<?php echo base_url() . auto_version('assets/pos/js/scripts.js'); ?>"></script>
        <script src="<?php echo base_url() . auto_version('assets/pos/js/settings.js'); ?>"></script>
        <script src="<?php echo base_url() . auto_version('assets/pos/js/custom.js'); ?>"></script>

</html>