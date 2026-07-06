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
                                <thead class="bg-warning">
                                    <tr class="nk-tb-item nk-tb-head">
                                        <th class="nk-tb-col nk-tb-col-check">
                                            <div class="custom-control custom-control-sm custom-checkbox notext">
                                                <input type="checkbox" class="custom-control-input" id="uid" /><label class="custom-control-label" for="uid"></label>
                                            </div>
                                        </th>
                                        <th class="nk-tb-col"><span class="text-white">Sale No</span></th>
                                        <th class="nk-tb-col tb-col-mb"><span class="text-white">Sale Type</span></th>
                                        <th class="nk-tb-col tb-col-mb"><span class="text-white">Date</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="text-white">Customer</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="text-white">Items</span></th>
                                        <th class="nk-tb-col tb-col-lg"><span class="text-white">Total (<?php echo $default_currency; ?>)</span></th>
                                        <th class="nk-tb-col tb-col-lg"><span class="text-white">Paid (<?php echo $default_currency; ?>)</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="text-white">Due (<?php echo $default_currency; ?>)</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="text-white">Status</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="text-white">Created By</span></th>
                                        <th class="nk-tb-col nk-tb-col-tools text-right"></th>
                                    </tr>
                                </thead>
                                <tbody>                       
                                    <?php foreach ($sales_hold_list as $row): ?>
                                        <?php $payment_balance = $row->total_sale - $row->total_paid; ?>

                                        <tr class="nk-tb-item">
                                            <td class="nk-tb-col nk-tb-col-check">
                                                <div class="custom-control custom-control-sm custom-checkbox notext">
                                                    <input type="checkbox" class="custom-control-input" id="uid1" /><label class="custom-control-label" for="uid1"></label>
                                                </div>
                                            </td>
                                            <td class="nk-tb-col">
                                                <div class="user-card">
                                                    <div class="user-info">
                                                        <span class="tb-lead"><a href="javascript:;">#<?php echo $row->pos_sale_number; ?></a></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="nk-tb-col tb-col-lg"><span><?php echo $row->sale_type; ?></span></td>
                                            <td class="nk-tb-col tb-col-lg"><span><?php echo date('d-m-Y', strtotime($row->created_on)); ?></span></td>
                                            <td class="nk-tb-col tb-col-lg">
                                                <span><?php if ($row->customer_id == 0){ echo $row->customer_name; } else { echo $row->first_name . ' ' . $row->last_name; } ?></span>
                                            </td>
                                            <td class="nk-tb-col tb-col-lg">
                                                <?php if(!empty($row->details)): ?>
                                                    <?php foreach ($row->details as $row2){ echo $row2->product_name . ', '; } ?>
                                                <?php endif; ?>
                                            </td>
                                            <td class="nk-tb-col tb-col-mb">
                                                <span class="tb-amount"><?php echo number_format($row->total_sale,2); ?></span>
                                            </td>
                                            <td class="nk-tb-col tb-col-mb">
                                                <span class="tb-amount"><?php echo number_format($row->total_paid,2); ?></span>
                                            </td>
                                            <td class="nk-tb-col tb-col-mb">
                                                <span class="tb-amount"><?php echo number_format(($row->total_sale - $row->total_paid),2); ?></span>
                                            </td>                                        
                                            
                                            <td class="nk-tb-col tb-col-md">
                                                <?php if ($row->is_void == 1): ?>
                                                    <span class="badge badge-sm badge-dot has-bg badge-secondary d-none d-mb-inline-flex">Void</span>
                                                <?php else: ?>
                                                    <?php if ($payment_balance == $row->total_sale): ?>
                                                        <span class="badge badge-sm badge-dot has-bg badge-danger d-none d-mb-inline-flex">Unpaid</span>
                                                    <?php elseif ($payment_balance > 0): ?>
                                                        <span class="badge badge-sm badge-dot has-bg badge-warning d-none d-mb-inline-flex">Partially Paid</span>
                                                    <?php else: ?>
                                                        <span class="badge badge-sm badge-dot has-bg badge-success d-none d-mb-inline-flex">Paid</span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td class="nk-tb-col tb-col-lg"><span><?php echo $row->system_user_first_name . ' ' . $row->system_user_last_name; ?> </span></td>
                                            <td class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1">
                                                    <li>
                                                        <div class="drodown">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <?php if ($sbr_pos_sales_orders_edit == true): ?>
                                                                        <li>
                                                                            <a href="javascript:;" data-pos-sale-id="<?php echo $row->pos_sale_id; ?>" class="lnk_hold_sale_resume"><em class="icon ni ni-invest"></em><span>Resume Sale</span></a>
                                                                        </li>
                                                                    <?php endif; ?>
                                                                    <?php if ($sbr_pos_sales_orders_delete == true): ?>
                                                                        <li>
                                                                            <a href="javascript:;" data-pos-sale-id="<?php echo $row->pos_sale_id; ?>" class="lnk_hold_sale_cancel"><em class="icon ni ni-cross-c"></em><span>Cancel Sale</span></a>
                                                                        </li>
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
                                    