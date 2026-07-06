<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">

            	<div class="nk-block nk-block-lg">
                    <div class="nk-block-head nk-block-head-sm">
					    <div class="nk-block-between">
					        <div class="nk-block-head-content"><h5 class="nk-block-title page-title"><em class="icon ni ni-file-docs"></em> Profit &amp; Loss Report</h5></div>
					    </div>
					</div>


                    <div class="row">
                        <div class="col-md-3">
                            <div class="box box-success">
                                <div class="box-header with-border">
                                    <h5 class=""><small><b><em class="icon ni ni-filter"></em> Filter</b></small></h5>
                                </div>
                                <form id="frm_profit_loss_report_filter" name="frm_profit_loss_report_filter" onsubmit="return load_profit_loss_report();">
                                    <div class="box-body">
                                        
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="from_date" class="control-label">From Date: <span class="text-danger">*</span></label>                                                
                                                <div class="form-control-wrap">
                                                    <div class="form-icon form-icon-left"><em class="icon ni ni-calendar"></em></div>
                                                    <input type="text" id="from_date" name="from_date" class="form-control date-picker" data-date-format="yyyy-mm-dd" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <label for="to_date" class="control-label">To Date: <span class="text-danger">*</span></label>
                                                <div class="form-control-wrap">
                                                    <div class="form-icon form-icon-left"><em class="icon ni ni-calendar"></em></div>
                                                    <input type="text" id="to_date" name="to_date" class="form-control date-picker" data-date-format="yyyy-mm-dd" readonly />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="box-footer">
                                        <div class="col-sm-12 text-center">
                                            
                                                <button type="submit" id="btn_view_profit_loss_report" class="btn btn-primary"><em class="icon ni ni-reload-alt mr-1"></em>Generate Report</button>
                                            
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="box box-success">
                                <div class="box-header with-border">
                                    <h5 class="">
                                        <small><b><em class="icon ni ni-list-index"></em> Report Details</b></small>
                                         <!-- <span class="pull-right"><button id="btn_export_report" class="btn btn-sm btn-info"><em class="icon ni ni-file-xls mr-1"></em>Export</button></span> -->
                                    </h5>
                                </div>
                                <div class="spinner display-none" id="profit_loss_report_loader">
                                    <div class="rect1"></div>
                                    <div class="rect2"></div>
                                    <div class="rect3"></div>
                                </div>
                                <div class="box-body" id="div_profit_loss_report" style="min-height: 150px;">

                                    

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>

<script>
	$(document).ready(function() {
		//load_customers_list();
	});
</script>