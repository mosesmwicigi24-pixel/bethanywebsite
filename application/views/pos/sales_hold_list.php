<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">

            	<div class="nk-block nk-block-lg">
                    <div class="nk-block-head nk-block-head-sm">
					    <div class="nk-block-between">
					        <div class="nk-block-head-content"><h5 class="nk-block-title page-title"><em class="icon ni ni-pause"></em> Hold List</h5></div>
					    </div>
					</div>

                    <div class="card card-preview">
                    	<div class="spinner display-none" id="sales_hold_list_loader">
                            <div class="rect1"></div>
                            <div class="rect2"></div>
                            <div class="rect3"></div>
                        </div>
                        <div class="card-inner div-list" id="div_sales_hold_list">
                            



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
	$(document).ready(function() {
		load_sales_hold_list();
	});
</script>