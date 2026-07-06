<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">

            	<div class="nk-block nk-block-lg">
                    <div class="nk-block-head nk-block-head-sm">
					    <div class="nk-block-between">
					        <div class="nk-block-head-content"><h5 class="nk-block-title page-title"><em class="icon ni ni-file-docs"></em> Expense Report</h5></div>
					    </div>
					</div>


                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-success">
                                <div class="box-header with-border">
                                    <h5 class=""><small><b><em class="icon ni ni-filter"></em> Filter</b></small></h5>
                                </div>
                                <form id="frm_expense_report_filter" name="frm_expense_report_filter" onsubmit="return load_expense_report();">
                                    <div class="box-body">
                                        <div class="col-md-3"></div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="from_date" class="control-label">From Date: <span class="text-danger">*</span></label>                                                
                                                <div class="form-control-wrap">
                                                    <div class="form-icon form-icon-left"><em class="icon ni ni-calendar"></em></div>
                                                    <input type="text" id="from_date" name="from_date" class="form-control date-picker" data-date-format="yyyy-mm-dd" readonly />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
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
                                            <div class="col-md-5 col-md-offset-3">
                                                <button type="submit" id="btn_view_expense_report" class="btn btn-primary"><em class="icon ni ni-reload-alt mr-1"></em>Generate Report</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>


                    <div class="card card-preview">
                    	<div class="spinner display-none" id="expense_report_loader">
                            <div class="rect1"></div>
                            <div class="rect2"></div>
                            <div class="rect3"></div>
                        </div>
                        <div class="card-header">
                            <h6>Report Details <span class="pull-right"><button id="btn_export_report" class="btn btn-sm btn-info"><em class="icon ni ni-file-xls mr-1"></em>Export</button></span></h6>
                        </div>
                        <div class="card-inner" id="div_expense_report">
                            
                            <div class="box-body table-responsive no-padding">
                                <table id="tbl_report" class="table table-bordered table-hover">
                                    <thead>
                                        <tr class="bg-primary">
                                            <th style="">#</th>
                                            <th style="">Date</th>
                                            <th style="">Description</th>
                                            <th style="">Reference No.</th>
                                            <th style="">Amount(<?php echo $default_currency; ?>)</th>
                                            <th style="">Note</th>
                                            <th style="">Created By</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
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