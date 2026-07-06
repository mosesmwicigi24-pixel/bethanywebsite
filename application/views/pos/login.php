<!DOCTYPE html>
<html lang="zxx" class="js">
    <head>
        <meta charset="utf-8">
        <meta name="author" content="Devlab Africa">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="">
        <link rel="shortcut icon" href="<?php echo base_url();?>assets/pos/images/favicon.png">
        <title>Login | Bethany House POS</title>
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
                                            <h4 class="nk-block-title">Point of Sale Login</h4>
                                            <div class="nk-block-des">
                                                <p>Enter your credentials below to login.</p>
                                            </div>
                                        </div>
                                    </div>
                                    <form id="frm_pos_login" name="frm_pos_login" method="post" onsubmit="return submit_login();">

                                        <div id="div_login_error" class="alert alert-danger display-none"></div>
                                        <div id="div_login_success" class="alert alert-success display-none"></div>

                                        <div class="form-group">
                                            <div class="form-label-group">
                                                <label class="form-label" for="login_email_address">Email Address</label>
                                            </div>
                                            <input type="text" class="form-control form-control-lg" id="login_email_address" name="login_email_address" placeholder="Enter your email address">
                                        </div>
                                        <div class="form-group">
                                            <div class="form-label-group">
                                                <label class="form-label" for="login_password">Password</label>
                                                <a class="link link-primary link-sm" href="<?php echo base_url();?>pos/auth/reset_password">Forgot Password?</a>
                                            </div>
                                            <div class="form-control-wrap">
                                                <a href="#" class="form-icon form-icon-right passcode-switch" data-target="login_password">
                                                    <em class="passcode-icon icon-show icon ni ni-eye"></em>
                                                    <em class="passcode-icon icon-hide icon ni ni-eye-off"></em>
                                                </a>
                                                <input type="password" class="form-control form-control-lg" id="login_password" name="login_password" placeholder="Enter your password" autocomplete="new-password">
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <button id="btn_login" class="btn btn-lg btn-primary btn-block">Sign In <em class="icon ni ni-chevron-right ml-1"></em></em></button>
                                        </div>
                                    </form>
                                    <!-- <div class="form-note-s2 text-center pt-4"> Trouble Logging In? <a href="#">Submit Ticket</a></div> -->
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