<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">

            	<div class="nk-block nk-block-lg">
                    <div class="nk-block-head nk-block-head-sm">
					    <div class="nk-block-between">
					        <div class="nk-block-head-content"><h5 class="nk-block-title page-title"><em class="icon ni ni-users-fill"></em> Customers</h5></div>
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li class="nk-block-tools-opt">
                                                <?php if ($sbr_pos_customers_add == true): ?>
                                                    <a href="<?php echo base_url(); ?>pos/sales/customer_new" class="btn btn-icon btn-sm btn-primary d-md-none"><em class="icon ni ni-plus-c"></em></a>
                                                    <a href="<?php echo base_url(); ?>pos/sales/customer_new" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-plus-c"></em><span>New Customer</span></a>
                                                <?php endif; ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
					    </div>
					</div>

                    <div class="card card-preview">
                    	<div class="spinner display-none" id="customers_list_loader">
                            <div class="rect1"></div>
                            <div class="rect2"></div>
                            <div class="rect3"></div>
                        </div>
                        <div class="card-inner div-list" id="div_customers_list">
                            


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
	$(document).ready(function() {
		load_customers_list();
	});
</script>