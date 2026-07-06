                            <script>
                                $(".datatable-init").DataTable({
                                    responsive: { details: !0 },
                                    autoWidth: !1,
                                    dom:
                                        '<"row justify-between g-2"<"col-7 col-sm-6 text-left"f><"col-5 col-sm-6 text-right"<"datatable-filter"l>>><"datatable-wrap my-3"t><"row align-items-center"<"col-7 col-sm-12 col-md-9"p><"col-5 col-sm-12 col-md-3 text-left text-md-right"i>>',
                                    language: {
                                        search: "",
                                        searchPlaceholder: "Type in to Search",
                                        lengthMenu: "<span class='d-none d-sm-inline-block'>Show</span><div class='form-control-select'> _MENU_ </div>",
                                        info: "_START_ -_END_ of _TOTAL_",
                                        infoEmpty: "No records found",
                                        infoFiltered: "( Total _MAX_  )",
                                        paginate: { first: "First", last: "Last", next: "Next", previous: "Prev" },
                                    }
                                });
                            </script>     
                            <table class="datatable-init nk-tb-list nk-tb-ulist table" data-auto-responsive="true">
                                <thead class="bg-lighter">
                                    <tr class="nk-tb-item nk-tb-head">
                                        <th class="nk-tb-col nk-tb-col-check">
                                            <div class="custom-control custom-control-sm custom-checkbox notext">
                                                <input type="checkbox" class="custom-control-input" id="uid" /><label class="custom-control-label" for="uid"></label>
                                            </div>
                                        </th>
                                        <th class="nk-tb-col"><span class="">Description</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Date</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Ref. #</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Amount</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Note</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Status</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Created By</span></th>
                                        <th class="nk-tb-col nk-tb-col-tools text-right"></th>
                                    </tr>
                                </thead>
                                <tbody>                       
                                    <?php foreach ($expenses as $row): ?>

                                        <tr class="nk-tb-item  <?php if ($row->is_void == 1){ echo 'text-muted'; } ?>">
                                            <td class="nk-tb-col nk-tb-col-check">
                                                <div class="custom-control custom-control-sm custom-checkbox notext">
                                                    <input type="checkbox" class="custom-control-input" id="uid1" /><label class="custom-control-label" for="uid1"></label>
                                                </div>
                                            </td>
                                            <td class="nk-tb-col tb-col-lg">
                                                <div class="user-name"><span class="tb-lead"><?php echo $row->expense_description; ?></span></div>
                                            </td>
                                            <td class="nk-tb-col tb-col-mb">
                                                <?php echo date('d-m-Y', strtotime($row->expense_date)); ?>
                                            </td>
                                            <td class="nk-tb-col tb-col-mb">
                                                <?php echo $row->expense_reference_number; ?>
                                            </td>
                                            <td class="nk-tb-col tb-col-mb">
                                                <span class="tb-amount"><?php echo $default_currency . ' ' . number_format($row->expense_amount,2); ?></span>
                                            </td>                                        
                                            <td class="nk-tb-col tb-col-mb">
                                                <?php echo $row->expense_note; ?>
                                            </td>
                                            <td class="nk-tb-col tb-col-mb">
                                                <?php if ($row->is_void == 1): ?>
                                                    <span class="badge badge-sm badge-dot has-bg badge-secondary d-none d-mb-inline-flex">Void</span>
                                                <?php else: ?>
                                                    <span class="badge badge-sm badge-dot has-bg badge-success d-none d-mb-inline-flex">Active</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="nk-tb-col tb-col-mb">
                                                <?php echo $row->system_user_first_name . ' ' . $row->system_user_last_name; ?>
                                            </td>
                                            <td class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1">
                                                    <li>
                                                        <div class="drodown">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <?php if ($row->is_void == 0): ?>
                                                                        <?php if ($sbr_pos_expenses_edit == true): ?>
                                                                            <a href="#" data-expense-id="<?php echo $row->expense_id; ?>" class="lnk_edit_expense"><em class="icon ni ni-edit"></em><span>Edit Expense</span></a>
                                                                        <?php endif; ?>
                                                                        <?php if ($sbr_pos_expenses_delete == true): ?>
                                                                            <a href="#" data-expense-id="<?php echo $row->expense_id; ?>" class="lnk_void_expense"><em class="icon ni ni-shield-off"></em><span>Void Expense</span></a>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </li>
                                                </ul>
                                            </td>
                                        </tr>


                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                                    