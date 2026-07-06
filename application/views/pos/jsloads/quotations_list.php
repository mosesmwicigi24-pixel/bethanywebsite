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
                                        <th class="nk-tb-col"><span class="">No</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Quotation Date</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Valid Until</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Customer</span></th>
                                        <th class="nk-tb-col tb-col-lg"><span class="">Total Amount (<?php echo $default_currency; ?>)</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Status</span></th>
                                        <th class="nk-tb-col tb-col-mb"><span class="">Created On</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Created By</span></th>
                                        <th class="nk-tb-col nk-tb-col-tools text-right"></th>
                                    </tr>
                                </thead>
                                <tbody>                       
                                    <?php foreach ($quotations_list as $row): ?>
                                        <tr class="nk-tb-item <?php if ($row->is_void == 1){ echo 'text-muted'; } ?>">
                                            <td class="nk-tb-col nk-tb-col-check">
                                                <div class="custom-control custom-control-sm custom-checkbox notext">
                                                    <input type="checkbox" class="custom-control-input" id="uid1" /><label class="custom-control-label" for="uid1"></label>
                                                </div>
                                            </td>
                                            <td class="nk-tb-col">
                                                <div class="user-card">
                                                    <div class="user-info">
                                                        <span class="tb-lead"><b>
                                                            <?php if ($sbr_pos_quotations_view == true): ?>
                                                                <a href="<?php echo base_url(); ?>pos/quotations/view/<?php echo $row->pos_quotation_id; ?>">#<?php echo $row->pos_quotation_number; ?></a>
                                                            <?php else: ?>
                                                                #<?php echo $row->pos_quotation_number; ?>
                                                            <?php endif; ?>
                                                        </b></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="nk-tb-col tb-col-lg">
                                                <span class="tb-amount"><?php echo date('d-m-Y', strtotime($row->quotation_date)); ?></span>
                                            </td>
                                            <td class="nk-tb-col tb-col-lg">
                                                <span class="tb-amount"><?php if ($row->valid_until != ''){ echo date('d-m-Y', strtotime($row->valid_until)); } ?></span>
                                            </td>                                        
                                            <td class="nk-tb-col tb-col-lg">
                                                <span><?php if ($row->customer_id == 0){ echo $row->customer_name; } else { echo $row->first_name . ' ' . $row->last_name; } ?></span>
                                            </td>
                                            <td class="nk-tb-col tb-col-mb">
                                                <span class="tb-amount"><?php echo number_format($row->total_amount,2); ?></span>
                                            </td>
                                            <td class="nk-tb-col tb-col-md">
                                                <?php if ($row->is_void == 1): ?>
                                                    <span class="badge badge-sm badge-dot has-bg badge-danger d-none d-mb-inline-flex">Void</span>
                                                <?php else: ?>
                                                    <span class="badge badge-sm badge-dot has-bg badge-success d-none d-mb-inline-flex">Active</span>
                                                <?php endif; ?>
                                            </td>
                                            <td class="nk-tb-col tb-col-lg"><span><?php echo date('d-m-Y H:i:s', strtotime($row->created_on)); ?></span></td>
                                            <td class="nk-tb-col tb-col-lg"><span><?php echo $row->system_user_first_name . ' ' . $row->system_user_last_name; ?> </span></td>
                                            <td class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1">
                                                    <li>
                                                        <div class="drodown">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <?php if ($sbr_pos_quotations_view == true): ?>
                                                                        <li>
                                                                            <a href="<?php echo base_url(); ?>pos/quotations/view/<?php echo $row->pos_quotation_id; ?>"><em class="icon ni ni-eye"></em><span>View Quotation</span></a>
                                                                        </li>
                                                                    <?php endif; ?>
                                                                    <?php if ($sbr_pos_quotations_edit == true): ?>
                                                                        <?php if ($row->is_void == 0): ?>
                                                                            <li>
                                                                                <a href="<?php echo base_url(); ?>pos/quotations/edit/<?php echo $row->pos_quotation_id; ?>"><em class="icon ni ni-edit"></em><span>Edit Quotation</span></a>
                                                                            </li>                                                                    
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                    <li class="divider"></li>
                                                                    <?php if ($sbr_pos_quotations_print == true): ?>
                                                                        <li>
                                                                            <a href="<?php echo base_url(); ?>pos/quotations/print_a4/<?php echo $row->pos_quotation_id; ?>" target="_blank"><em class="icon ni ni-printer"></em><span>Print Quotation</span></a>
                                                                        </li>
                                                                    <?php endif; ?>
                                                                    <li>
                                                                        <a href="#" data-toggle="modal" data-target="#modal_send_quotation_order_via_email" data-pos-quotation-id="<?php echo $row->pos_quotation_id; ?>" class="lnk_send_quotation_order_via_email"><em class="icon ni ni-mail"></em><span>Send via Mail</span></a>
                                                                    </li>
                                                                    <?php if ($sbr_pos_quotations_delete == true): ?>
                                                                        <?php if ($row->is_void == 0): ?>
                                                                            <li>
                                                                                <a href="#" data-pos-quotation-id="<?php echo $row->pos_quotation_id; ?>" data-context="Quotations List" class="lnk_void_pos_quotation"><em class="icon ni ni-shield-off"></em><span>Void Quotation</span></a>
                                                                            </li>
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
                                    