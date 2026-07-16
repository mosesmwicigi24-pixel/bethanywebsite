<!DOCTYPE html>
<html lang="zxx" class="js">
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Bethany House">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <link rel="shortcut icon" href="<?php echo base_url();?>assets/pos/images/favicon.png">
        <title>Reset Password | Bethany House POS</title>
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
                                            <h4 class="nk-block-title">Reset Password</h4>
                                            <div class="nk-block-des">
                                                <p>Enter your Email Address below to reset your password.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <form id="frm_reset_password" name="frm_reset_password" method="post" onsubmit="return submit_reset_password();">

                                        <div id="div_reset_password_error" class="alert alert-danger display-none"></div>
                                        <div id="div_reset_password_success" class="alert alert-success display-none"></div>

                                        <div class="form-group">
                                            <input type="text" class="form-control form-control-lg" id="reset_password_email_address" name="email_address" placeholder="Enter your email address">
                                        </div>
                                        <div class="form-group">
                                            <button id="btn_reset_password" class="btn btn-lg btn-primary btn-block">Reset Password <em class="icon ni ni-chevron-right ml-1"></em></em></button>
                                        </div>
                                    </form>
                                    <div class="form-note-s2 text-center pt-4"><a href="<?php echo base_url();?>pos/auth/login">Return to Login</a></div>
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
                                            <p class="text-soft">&copy; <?php echo date('Y'); ?> Bethany House POS.</p>
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