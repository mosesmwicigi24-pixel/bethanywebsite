<div class="nk-content">
    <div class="container-fluid">
        <div class="nk-content-inner">
            <div class="nk-content-body">

            	<div class="nk-block nk-block-lg">
                    <div class="nk-block-head nk-block-head-sm">
					    <div class="nk-block-between">
					        <div class="nk-block-head-content"><h5 class="nk-block-title page-title"><em class="icon ni ni-coin"></em> Expenses</h5></div>
                            <div class="nk-block-head-content">
                                <div class="toggle-wrap nk-block-tools-toggle">
                                    <a href="#" class="btn btn-icon btn-trigger toggle-expand mr-n1" data-target="pageMenu"><em class="icon ni ni-more-v"></em></a>
                                    <div class="toggle-expand-content" data-content="pageMenu">
                                        <ul class="nk-block-tools g-3">
                                            <li class="nk-block-tools-opt">
                                                <?php if ($sbr_pos_expenses_add == true): ?>
                                                    <a href="#" data-toggle="modal" data-target="#modal_add_expense" class="btn btn-icon btn-sm btn-primary d-md-none"><em class="icon ni ni-plus-c"></em></a>
                                                    <a href="#" data-toggle="modal" data-target="#modal_add_expense" class="btn btn-primary btn-sm d-none d-md-inline-flex"><em class="icon ni ni-plus-c"></em><span>New Expense</span></a>
                                                <?php endif; ?>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
					    </div>
					</div>

                    <div class="card card-preview">
                    	<div class="spinner display-none" id="expenses_list_loader">
                            <div class="rect1"></div>
                            <div class="rect2"></div>
                            <div class="rect3"></div>
                        </div>
                        <div class="card-inner">
                            <form method="post" class="form" id="frm_filter_expenses" name="frm_filter_expenses" method="post">
                                <div class="row gy-4">
                                    <div class="col-sm-4">
                                        <div class="form-group">
                                            <div class="form-control-wrap">
                                                <div class="input-daterange date-picker-range input-group">
                                                    <input type="text" class="form-control" id="date_from" name="date_from" />
                                                    <div class="input-group-addon">TO</div>
                                                    <input type="text" class="form-control" id="date_to" name="date_to" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-sm-1">
                                        <button type="button" id="btn_expenses_filter" class="btn btn-secondary" onclick="load_expenses_list();">Filter</button>
                                    </div>
                                </div>
                            </form>

                            <div id="div_expenses_list" class="div-list">
                                
                            </div>


                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade in" id="modal_add_expense">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header header-custom">
                <h6 class="modal-title text-center"><em class="icon ni ni-plus-c mr-1"></em>New Expense</h6>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <form id="frm_add_expense" name="frm_add_expense" method="post" class="is-alter" onsubmit="return save_expense();">
                <div class="modal-body">
                    <div class="spinner display-none" id="add_expense_loader">
                        <div class="rect1"></div>
                        <div class="rect2"></div>
                        <div class="rect3"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div>

                                <div class="col-md-12">
                                    <div class="box box-solid bg-lighter">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="add_expense_date">Expense Date<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control date-picker" id="add_expense_date" name="expense_date"data-date-format="yyyy-mm-dd" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="add_expense_date">Reference #</label>
                                                        <input type="text" class="form-control" id="add_expense_reference_number" name="expense_reference_number" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="add_expense_description">Description<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="add_expense_description" name="expense_description" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="add_expense_amount">Amount<span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control" id="add_expense_amount" name="expense_amount" step="any" min="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="add_expense_note">Note</label>
                                                        <textarea type="text" class="form-control" id="add_expense_note" name="expense_note" placeholder=""></textarea>
                                                    </div>
                                                </div>

                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn_add_expense" class="btn btn-success"><i class="ion-checkmark-circled mr-1"></i>Save Expense</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade in" id="modal_edit_expense">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header header-custom">
                <h6 class="modal-title text-center"><em class="icon ni ni-edit mr-1"></em>Edit Expense</h6>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <form id="frm_edit_expense" name="frm_edit_expense" method="post" class="is-alter" onsubmit="return update_expense();">
                <div class="modal-body">
                    <div class="spinner display-none" id="edit_expense_loader">
                        <div class="rect1"></div>
                        <div class="rect2"></div>
                        <div class="rect3"></div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div>

                                <input type="hidden" id="edit_expense_id" name="expense_id">

                                <div class="col-md-12">
                                    <div class="box box-solid bg-lighter">
                                        <div class="box-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="edit_expense_date">Expense Date<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control date-picker" id="edit_expense_date" name="expense_date"data-date-format="yyyy-mm-dd" placeholder="">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="edit_expense_date">Reference #</label>
                                                        <input type="text" class="form-control" id="edit_expense_reference_number" name="expense_reference_number" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-12">
                                                    <div class="form-group">
                                                        <label for="edit_expense_description">Description<span class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" id="edit_expense_description" name="expense_description" placeholder="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="edit_expense_amount">Amount<span class="text-danger">*</span></label>
                                                        <input type="number" class="form-control" id="edit_expense_amount" name="expense_amount" step="any" min="0">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="edit_expense_note">Note</label>
                                                        <textarea type="text" class="form-control" id="edit_expense_note" name="expense_note" placeholder=""></textarea>
                                                    </div>
                                                </div>

                                                <div class="clearfix"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" id="btn_edit_expense" class="btn btn-success"><i class="ion-checkmark-circled mr-1"></i>Update Expense</button>
                    <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" tabindex="-1" id="modal_void_expense">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title"><i class="icon ni ni-trash-alt"></i> Void Expense</h6>
                <a href="#" class="close" data-dismiss="modal" aria-label="Close"><em class="icon ni ni-cross"></em></a>
            </div>
            <div class="modal-body">
                <div class="spinner display-none" id="void_expense_loader">
                    <div class="rect1"></div>
                    <div class="rect2"></div>
                    <div class="rect3"></div>
                </div>
                <form id="frm_void_expense" name="frm_void_expense" method="post" class="is-alter" onsubmit="return submit_void_expense();">

                    <input type="hidden" id="void_expense_id" name="expense_id">

                    <div class="row">
                        <div class="col-md-12">
                            <div class="box box-solid bg-lighter">
                                <div class="box-body">
                                    <div class="row mt-1">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <textarea name="void_reason" cols="40" rows="5" id="expense_void_reason" class="form-control" placeholder="Void Reason"></textarea>
                                            </div>
                                        </div>
                                        <div class="clearfix"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" id="btn_void_expense" class="btn btn-success"><i class="ion-checkmark-circled mr-1"></i>Submit</button>
                        <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="ion-close-circled mr-1"></i>Cancel</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function() {
        var startDate = moment().subtract(30, 'days').format('YYYY-MM-DD');
        var endDate = moment().format('YYYY-MM-DD');

        $("#date_from").datepicker('setDate', startDate);
        $("#date_to").datepicker('setDate', endDate);

        load_expenses_list();
    });
</script>