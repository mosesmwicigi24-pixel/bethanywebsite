<!DOCTYPE html>
<html lang="zxx" class="js">
    <head>
        <meta charset="utf-8" />
        <meta name="author" content="Bethany House" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
         <meta name="description" content=" Communion Hosts and Altar Bread · Altar Wine · 
    Holy Water, Oil & Baptismal Accessories · Candles, Candle holders & accessories · 
    Communion Trays. Church Accessories · Church Supplies · Clergy Apparel · 
    Clergy gowns · Holy Communion Ware · Bread plates · Communion bread · 
    Communion cups · Communion Trays.Holy Communion supplies in Kenya based in Nairobi. 
    we supply Holy Communion Elements, Holy Communion items, wine, Communion bread, 
    Pastors gowns, clergy vestments and all church supplies."/>
        <link rel="shortcut icon" href="<?php echo base_url();?>assets/pos/images/favicon.png" />
        <title><?php echo $page_title;?>Bethany House POS</title>
        <?php
            function auto_version($file){
                if(!file_exists($file)) return $file;
                $mtime = filemtime($file);
                return preg_replace('{\\.([^./]+)$}', ".\$1?$mtime", $file);
            }
        ?>
        
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() . auto_version('assets/pos/css/style.css'); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() . auto_version('assets/pos/css/bethany.css'); ?>">        
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() . auto_version('assets/pos/css/theme.css'); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() . auto_version('assets/pos/plugins/bootstrap3-editable/css/bootstrap-editable.css'); ?>">
        <link rel="stylesheet" type="text/css" href="<?php echo base_url() . auto_version('assets/pos/plugins/tableexport/dist/css/tableexport.css'); ?>">
        

        <script>
            window.dataLayer = window.dataLayer || [];
            function gtag() {
                dataLayer.push(arguments);
            }
            gtag("js", new Date());
            gtag("config", "UA-91615293-4");
        </script>

        <script type="text/javascript">
            var baseDir = '<?php echo base_url(); ?>';
        </script>

        <script src="<?php echo base_url();?>assets/pos/js/bundle.js?ver=2.0.0"></script>
    </head>
    <body class="nk-body bg-lighter npc-general has-sidebar ui-bordered">
        <div class="nk-app-root">
            <div class="nk-main">
                <div class="nk-sidebar nk-sidebar-fixed is-light" data-content="sidebarMenu">
                    <div class="nk-sidebar-element nk-sidebar-head">
                        <div class="nk-sidebar-brand">
                            <a href="<?php echo base_url();?>pos" class="logo-link nk-sidebar-logo">
                                <img class="logo-light logo-img" src="<?php echo base_url();?>assets/pos/images/logo.png" srcset="<?php echo base_url();?>assets/pos/images/logo2x.png 2x" alt="logo" />
                                <img class="logo-dark logo-img" src="<?php echo base_url();?>assets/pos/images/logo-dark.png" srcset="<?php echo base_url();?>assets/pos/images/logo-dark2x.png 2x" alt="logo-dark" />
                                <img class="logo-small logo-img logo-img-small" src="<?php echo base_url();?>assets/pos/images/logo-small.png" srcset="<?php echo base_url();?>assets/pos/images/logo-small2x.png 2x" alt="logo-small" />
                            </a>
                        </div>
                        <div class="nk-menu-trigger mr-n2">
                            <a href="#" class="nk-nav-toggle nk-quick-nav-icon d-xl-none" data-target="sidebarMenu"><em class="icon ni ni-arrow-left"></em></a>
                            <a href="#" class="nk-nav-compact nk-quick-nav-icon d-none d-xl-inline-flex" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
                        </div>
                    </div>
                    <div class="nk-sidebar-element">
                        <div class="nk-sidebar-content">
                            <div class="nk-sidebar-menu" data-simplebar>
                                <ul class="nk-menu">
                                    <li class="nk-menu-item <?php if ($cur == 'Dashboard'){ echo 'active'; } ?>">
                                        <a href="<?php echo base_url();?>pos" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-dashboard-fill"></em></span><span class="nk-menu-text">Dashboard</span>
                                        </a>
                                    </li>
                                    <li class="nk-menu-item has-sub <?php if ($cur == 'Sales Orders'){ echo 'active'; } ?>">
                                        <a href="#" class="nk-menu-link nk-menu-toggle">
                                            <span class="nk-menu-icon"><em class="icon ni ni-cart"></em></span><span class="nk-menu-text">Sales Orders</span>
                                        </a>                                        
                                        <ul class="nk-menu-sub">
                                            <li class="nk-menu-item <?php if ($cur_sub == 'Sales Orders'){ echo 'active'; } ?>">
                                                <a href="<?php echo base_url();?>pos/sales/sales_list" class="nk-menu-link"><span class="nk-menu-text">Sales Orders List</span></a>
                                            </li>
                                            <li class="nk-menu-item <?php if ($cur_sub == 'New Sales Order'){ echo 'active'; } ?>">
                                                <a href="<?php echo base_url();?>pos/sales/new_sale" class="nk-menu-link"><span class="nk-menu-text">New Sales Order</span></a>
                                            </li>
                                            <li class="nk-menu-item <?php if ($cur_sub == 'Hold List'){ echo 'active'; } ?>">
                                                <a href="<?php echo base_url();?>pos/sales/hold_list" class="nk-menu-link"><span class="nk-menu-text">Held Orders List</span></a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="nk-menu-item has-sub <?php if ($cur == 'Sales Returns'){ echo 'active'; } ?>">
                                        <a href="#" class="nk-menu-link nk-menu-toggle">
                                            <span class="nk-menu-icon"><em class="icon ni ni-redo"></em></span><span class="nk-menu-text">Sales Returns</span>
                                        </a>                                        
                                        <ul class="nk-menu-sub">
                                            <li class="nk-menu-item <?php if ($cur_sub == 'Sales Returns'){ echo 'active'; } ?>">
                                                <a href="<?php echo base_url();?>pos/sales/sales_returns" class="nk-menu-link"><span class="nk-menu-text">Sales Returns List</span></a>
                                            </li>
                                            <li class="nk-menu-item <?php if ($cur_sub == 'New Sales Return'){ echo 'active'; } ?>">
                                                <a href="<?php echo base_url();?>pos/sales/new_sales_return" class="nk-menu-link"><span class="nk-menu-text">New Sales Return</span></a>
                                            </li>
                                        </ul>
                                    </li>
                                    <li class="nk-menu-item has-sub <?php if ($cur == 'Quotations'){ echo 'active'; } ?>">
                                        <a href="#" class="nk-menu-link nk-menu-toggle">
                                            <span class="nk-menu-icon"><em class="icon ni ni-notice"></em></span><span class="nk-menu-text">Quotations</span>
                                        </a>                                        
                                        <ul class="nk-menu-sub">
                                            <li class="nk-menu-item <?php if ($cur_sub == 'Quotations'){ echo 'active'; } ?>">
                                                <a href="<?php echo base_url();?>pos/quotations" class="nk-menu-link"><span class="nk-menu-text">Quotations List</span></a>
                                            </li>
                                            <li class="nk-menu-item <?php if ($cur_sub == 'New Quotation'){ echo 'active'; } ?>">
                                                <a href="<?php echo base_url();?>pos/quotations/add" class="nk-menu-link"><span class="nk-menu-text">New Quotation</span></a>
                                            </li>
                                        </ul>
                                    </li>
                                    <!-- <li class="nk-menu-item <?php if ($cur == 'New Sale'){ echo 'active'; } ?>">
                                        <a href="<?php echo base_url();?>pos/sales/new_sale" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ion-ios-calculator-outline"></em></span><span class="nk-menu-text">New Sale</span>
                                        </a>
                                    </li>
                                    <li class="nk-menu-item <?php if ($cur == 'Sales List'){ echo 'active'; } ?>">
                                        <a href="<?php echo base_url();?>pos/sales/sales_list" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-list-index"></em></span><span class="nk-menu-text">Sales List</span>
                                        </a>
                                    </li> -->                                    
                                    
                                    <!-- <li class="nk-menu-item <?php if ($cur == 'New Sales Return'){ echo 'active'; } ?>">
                                        <a href="<?php echo base_url();?>pos/sales/new_sales_return" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-plus-circle"></em></span><span class="nk-menu-text">New Sales Return</span>
                                        </a>
                                    </li>
                                    <li class="nk-menu-item <?php if ($cur == 'Sales Returns'){ echo 'active'; } ?>">
                                        <a href="<?php echo base_url();?>pos/sales/sales_returns" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-redo"></em></span><span class="nk-menu-text">Sales Returns</span>
                                        </a>
                                    </li> -->
                                    <li class="nk-menu-item <?php if ($cur == 'Products'){ echo 'active'; } ?>">
                                        <a href="<?php echo base_url();?>pos/sales/products" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-package-fill"></em></span><span class="nk-menu-text">Products</span>
                                        </a>
                                    </li>
                                    <!-- <li class="nk-menu-item <?php if ($cur == 'Low Stock List'){ echo 'active'; } ?>">
                                        <a href="<?php echo base_url();?>pos/sales/low_stock" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-caution"></em></span><span class="nk-menu-text">Low Stock List</span>
                                        </a>
                                    </li> -->
                                    <li class="nk-menu-item <?php if ($cur == 'Customers'){ echo 'active'; } ?>">
                                        <a href="<?php echo base_url();?>pos/sales/customers" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-users-fill"></em></span><span class="nk-menu-text">Customers</span>
                                        </a>
                                    </li>
                                    <li class="nk-menu-item <?php if ($cur == 'Expenses'){ echo 'active'; } ?>">
                                        <a href="<?php echo base_url();?>pos/sales/expenses" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-coin"></em></span><span class="nk-menu-text">Expenses</span>
                                        </a>
                                    </li>
                                    <!--<li class="nk-menu-item has-sub <?php if ($cur == 'Reports'){ echo 'active'; } ?>">
                                        <a href="#" class="nk-menu-link nk-menu-toggle">
                                            <span class="nk-menu-icon"><em class="icon ni ni-chat-fill"></em></span><span class="nk-menu-text">Reports</span>
                                        </a>
                                        <ul class="nk-menu-sub">
                                             <li class="nk-menu-item <?php if ($cur_sub == 'Profit & Loss Report'){ echo 'active'; } ?>">
                                                <a href="<?php echo base_url();?>pos/reports/profit_loss" class="nk-menu-link"><span class="nk-menu-text">Profit & Loss Report</span></a>
                                            </li>
                                            <li class="nk-menu-item <?php if ($cur_sub == 'Sales Report'){ echo 'active'; } ?>">
                                                <a href="<?php echo base_url();?>pos/reports/sales" class="nk-menu-link"><span class="nk-menu-text">Sales Report</span></a>
                                            </li>
                                            <li class="nk-menu-item <?php if ($cur_sub == 'Sales Detailed Report'){ echo 'active'; } ?>">
                                                <a href="<?php echo base_url();?>pos/reports/sales_detailed" class="nk-menu-link"><span class="nk-menu-text">Sales Detailed Report</span></a>
                                            </li>
                                            <li class="nk-menu-item <?php if ($cur_sub == 'Payments Report'){ echo 'active'; } ?>">
                                                <a href="<?php echo base_url();?>pos/reports/payments" class="nk-menu-link"><span class="nk-menu-text">Payments Report</span></a>
                                            </li>
                                            <li class="nk-menu-item <?php if ($cur_sub == 'Stock Report'){ echo 'active'; } ?>">
                                                <a href="<?php echo base_url();?>pos/reports/stock" class="nk-menu-link"><span class="nk-menu-text">Stock Report</span></a>
                                            </li>
                                            <li class="nk-menu-item <?php if ($cur_sub == 'Expense Report'){ echo 'active'; } ?>">
                                                <a href="<?php echo base_url();?>pos/reports/expense" class="nk-menu-link"><span class="nk-menu-text">Expense Report</span></a>
                                            </li>
                                        </ul>

                                    </li>-->
                                    <li class="nk-menu-heading"><h6 class="overline-title text-primary-alt">More...</h6></li>
                                    <li class="nk-menu-item">
                                        <a href="<?php echo base_url();?>pos/auth/outlet_select" class="nk-menu-link">
                                            <span class="nk-menu-icon"><em class="icon ni ni-swap"></em></span><span class="nk-menu-text">Switch Outlet</span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="nk-wrap">
                    <div class="nk-header nk-header-fixed is-light">
                        <div class="container-fluid">
                            <div class="nk-header-wrap">
                                <div class="nk-menu-trigger d-xl-none ml-n1">
                                    <a href="#" class="nk-nav-toggle nk-quick-nav-icon" data-target="sidebarMenu"><em class="icon ni ni-menu"></em></a>
                                </div>
                                <div class="nk-header-brand d-xl-none">
                                    <a href="<?php echo base_url();?>pos" class="logo-link">
                                        <img class="logo-light logo-img" src="<?php echo base_url();?>assets/pos/images/logo.png" srcset="<?php echo base_url();?>assets/pos/images/logo2x.png 2x" alt="logo" />
                                        <img class="logo-dark logo-img" src="<?php echo base_url();?>assets/pos/images/logo-dark.png" srcset="<?php echo base_url();?>assets/pos/images/logo-dark2x.png 2x" alt="logo-dark" />
                                    </a>
                                </div>
                                <div class="nk-header-app-logo d-none d-xl-block">
                                    <img src="<?php echo base_url();?>assets/pos/images/outlet.png" class="" alt="">
                                </div>
                                <div class="nk-header-app-name d-none d-xl-block">
                                    <div class="nk-header-app-info">
                                        <span class="sub-text">Outlet:</span>
                                        <?php foreach ($active_outlet as $row): ?>
                                            <span class="lead-text"><?php echo $row->outlet_name; ?></span>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <div class="nk-header-tools">
                                    <ul class="nk-quick-nav">
                                        <!-- <li class="dropdown notification-dropdown">
                                            <a href="#" class="dropdown-toggle nk-quick-nav-icon" data-toggle="dropdown">
                                                <div class="icon-status icon-status-info"><em class="icon ni ni-bell"></em></div>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-xl dropdown-menu-right">
                                                <div class="dropdown-head"><span class="sub-title nk-dropdown-title">Notifications</span><a href="#">Mark All as Read</a></div>
                                                <div class="dropdown-body">
                                                    <div class="nk-notification">
                                                        <div class="nk-notification-item dropdown-inner">
                                                            <div class="nk-notification-icon"><em class="icon icon-circle bg-warning-dim ni ni-curve-down-right"></em></div>
                                                            <div class="nk-notification-content">
                                                                <div class="nk-notification-text">You have requested to <span>Widthdrawl</span></div>
                                                                <div class="nk-notification-time">2 hrs ago</div>
                                                            </div>
                                                        </div>
                                                        <div class="nk-notification-item dropdown-inner">
                                                            <div class="nk-notification-icon"><em class="icon icon-circle bg-success-dim ni ni-curve-down-left"></em></div>
                                                            <div class="nk-notification-content">
                                                                <div class="nk-notification-text">Your <span>Deposit Order</span> is placed</div>
                                                                <div class="nk-notification-time">2 hrs ago</div>
                                                            </div>
                                                        </div>
                                                        <div class="nk-notification-item dropdown-inner">
                                                            <div class="nk-notification-icon"><em class="icon icon-circle bg-warning-dim ni ni-curve-down-right"></em></div>
                                                            <div class="nk-notification-content">
                                                                <div class="nk-notification-text">You have requested to <span>Widthdrawl</span></div>
                                                                <div class="nk-notification-time">2 hrs ago</div>
                                                            </div>
                                                        </div>
                                                        <div class="nk-notification-item dropdown-inner">
                                                            <div class="nk-notification-icon"><em class="icon icon-circle bg-success-dim ni ni-curve-down-left"></em></div>
                                                            <div class="nk-notification-content">
                                                                <div class="nk-notification-text">Your <span>Deposit Order</span> is placed</div>
                                                                <div class="nk-notification-time">2 hrs ago</div>
                                                            </div>
                                                        </div>
                                                        <div class="nk-notification-item dropdown-inner">
                                                            <div class="nk-notification-icon"><em class="icon icon-circle bg-warning-dim ni ni-curve-down-right"></em></div>
                                                            <div class="nk-notification-content">
                                                                <div class="nk-notification-text">You have requested to <span>Widthdrawl</span></div>
                                                                <div class="nk-notification-time">2 hrs ago</div>
                                                            </div>
                                                        </div>
                                                        <div class="nk-notification-item dropdown-inner">
                                                            <div class="nk-notification-icon"><em class="icon icon-circle bg-success-dim ni ni-curve-down-left"></em></div>
                                                            <div class="nk-notification-content">
                                                                <div class="nk-notification-text">Your <span>Deposit Order</span> is placed</div>
                                                                <div class="nk-notification-time">2 hrs ago</div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="dropdown-foot center"><a href="#">View All</a></div>
                                            </div>
                                        </li> -->
                                        <li class="dropdown user-dropdown">
                                            <a href="#" class="dropdown-toggle mr-n1" data-toggle="dropdown">
                                                <div class="user-toggle">
                                                    <div class="user-avatar sm"><em class="icon ni ni-user-alt"></em></div>
                                                    <div class="user-info d-none d-xl-block">
                                                        <div class="user-status user-status-active"><?php if ($this->session->userdata('pos_user_is_super_admin') == 1) { echo 'Super Admin'; } else { echo $this->session->userdata('pos_user_role'); } ?></div>
                                                        <div class="user-name dropdown-indicator"><?php echo $this->session->userdata('pos_user_first_name') . ' ' . $this->session->userdata('pos_user_last_name'); ?></div>
                                                    </div>
                                                </div>
                                            </a>
                                            <div class="dropdown-menu dropdown-menu-md dropdown-menu-right">
                                                <div class="dropdown-inner user-card-wrap bg-lighter d-none d-md-block">
                                                    <div class="user-card">
                                                        <div class="user-avatar"><em class="icon ni ni-user-alt"></em></div>
                                                        <div class="user-info"><span class="lead-text"><?php echo $this->session->userdata('pos_user_first_name') . ' ' . $this->session->userdata('pos_user_last_name'); ?></span><span class="sub-text"><?php echo $this->session->userdata('pos_user_email_address'); ?></span></div>
                                                    </div>
                                                </div>
                                                <div class="dropdown-inner">
                                                    <ul class="link-list">
                                                        <li>
                                                            <a href="<?php echo base_url();?>pos/auth/profile"><em class="icon ni ni-user-alt"></em><span>My Profile</span></a>
                                                        </li>
                                                        <li>
                                                            <a href="<?php echo base_url();?>pos/auth/change_password"><em class="icon ni ni-lock-alt-fill"></em><span>Change Password</span></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="dropdown-inner">
                                                    <ul class="link-list">
                                                        <li>
                                                            <a href="<?php echo base_url();?>pos/auth/logout"><em class="icon ni ni-signout"></em><span>Sign Out</span></a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>


