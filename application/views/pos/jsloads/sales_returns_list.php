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
                                        <th class="nk-tb-col tb-col-mb"><span class="">Date</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Customer</span></th>
                                        <th class="nk-tb-col tb-col-lg"><span class="">Total (<?php echo $default_currency; ?>)</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Status</span></th>
                                        <th class="nk-tb-col tb-col-lg"><span class="">Settlement</span></th>
                                        <!-- <th class="nk-tb-col tb-col-md"><span class="">Due (<?php echo $default_currency; ?>)</span></th> -->
                                        
                                        <th class="nk-tb-col tb-col-md"><span class="">Created By</span></th>
                                        <th class="nk-tb-col nk-tb-col-tools text-right"></th>
                                    </tr>
                                </thead>
                                <tbody>                       
                                    <?php foreach ($sales_returns_list as $row): ?>
                                        <?php $refund_balance = $row->total_amount - $row->total_refunded; ?>
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
                                                            <?php if ($sbr_pos_sales_returns_view == true): ?>
                                                                <a href="<?php echo base_url(); ?>pos/sales/view_return/<?php echo $row->pos_sales_return_id; ?>">#<?php echo $row->pos_sales_return_number; ?></a>
                                                            <?php else: ?>
                                                                #<?php echo $row->pos_sales_return_number; ?>
                                                            <?php endif; ?>
                                                        </b></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="nk-tb-col tb-col-lg"><span><?php echo date('d-m-Y', strtotime($row->created_on)); ?></span></td>
                                            <td class="nk-tb-col tb-col-lg">
                                                <span><?php echo $row->first_name . ' ' . $row->last_name;  ?></span>
                                            </td>
                                            <td class="nk-tb-col tb-col-mb">
                                                <span class="tb-amount"><?php echo number_format($row->total_amount,2); ?></span>
                                            </td>
                                            <td class="nk-tb-col tb-col-md">
                                                <?php if ($row->is_void == 1): ?>
                                                    <span class="badge badge-sm badge-dot has-bg badge-secondary d-none d-mb-inline-flex">Void</span>
                                                <?php else: ?>
                                                    <?php if ($row->return_status == 0): ?>
                                                        <span class="badge badge-sm badge-dot has-bg badge-secondary d-none d-mb-inline-flex">Pending</span>
                                                    <?php elseif ($row->return_status == 1): ?>
                                                        <span class="badge badge-sm badge-dot has-bg badge-success d-none d-mb-inline-flex">Approved</span>
                                                    <?php elseif ($row->return_status == 1): ?>
                                                        <span class="badge badge-sm badge-dot has-bg badge-danger d-none d-mb-inline-flex">Rejected</span>
                                                    <?php endif; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td class="nk-tb-col tb-col-mb">
                                                <span>
                                                    <?php
                                                        if ($row->return_status == 1){
                                                            echo $row->return_settlement;
                                                            if ($row->return_settlement == 'Refund') {
                                                                echo '<br><small>Refunded: <b>' . $default_currency . ' ' . number_format($row->total_refunded,2) . '</b></small>';
                                                                if ($refund_balance > 0) {
                                                                    echo '<br><small>Balance: <b>' . $default_currency . ' ' . number_format($refund_balance,2) . '</b></small>';
                                                                } else {
                                                                    echo '<br><small>Balance: <b>' . $default_currency . ' ' . number_format(0,2) . '</b></small>';
                                                                }
                                                            }
                                                        } else {
                                                            echo '-';
                                                        }
                                                    ?>
                                                </span>
                                            </td>
                                            <!-- <td class="nk-tb-col tb-col-mb">
                                                <span class="tb-amount"><?php //echo number_format(($row->total_sales_return - $row->total_paid),2); ?></span>
                                            </td> -->                                        
                                            
                                            
                                            <td class="nk-tb-col tb-col-lg"><span><?php echo $row->system_user_first_name . ' ' . $row->system_user_last_name; ?> </span></td>
                                            <td class="nk-tb-col nk-tb-col-tools">
                                                <ul class="nk-tb-actions gx-1">
                                                    <li>
                                                        <div class="drodown">
                                                            <a href="#" class="dropdown-toggle btn btn-icon btn-trigger" data-toggle="dropdown"><em class="icon ni ni-more-h"></em></a>
                                                            <div class="dropdown-menu dropdown-menu-right">
                                                                <ul class="link-list-opt no-bdr">
                                                                    <?php if ($sbr_pos_sales_returns_view == true): ?>
                                                                        <li>
                                                                            <a href="<?php echo base_url(); ?>pos/sales/view_return/<?php echo $row->pos_sales_return_id; ?>"><em class="icon ni ni-eye"></em><span>View Sales Return</span></a>
                                                                        </li>
                                                                    <?php endif; ?>
                                                                    <?php if ($row->is_void == 0): ?>                                  
                                                                        <?php if ($sbr_pos_sales_returns_manage == true && $row->return_status == 0): ?>
                                                                            <li>
                                                                                <a href="javascript:;" data-pos-sales-return-id="<?php echo $row->pos_sales_return_id; ?>" data-context="Sales Returns List" class="sales_return_approve"><em class="icon ni ni-check-circle-cut"></em><span>Approve/Reject</span></a>
                                                                            </li>
                                                                        <?php endif; ?>
                                                                        <?php if ($sbr_pos_sales_returns_manage == true && $row->return_status == 1 && $row->return_settlement == 'Refund'): ?>
                                                                            <?php if ($row->total_refunded < $row->total_amount): ?>
                                                                                <li>
                                                                                    <a href="javascript:;" data-pos-sales-return-id="<?php echo $row->pos_sales_return_id; ?>" data-context="Sales Returns List" class="sales_return_refund"><em class="icon ni ni-redo"></em><span>Make a Refund</span></a>
                                                                                </li>
                                                                            <?php endif; ?>
                                                                        <?php endif; ?>
                                                                        <?php if ($sbr_pos_sales_returns_edit == true && $row->return_status == 0): ?>
                                                                            <li>
                                                                                <a href="<?php echo base_url(); ?>pos/sales/edit_return/<?php echo $row->pos_sales_return_id; ?>"><em class="icon ni ni-edit"></em><span>Edit Sales Return</span></a>
                                                                            </li>    
                                                                        <?php endif; ?> 
                                                                    <?php endif; ?>
                                                                    <li class="divider"></li>
                                                                    <?php if ($sbr_pos_sales_returns_print == true): ?>
                                                                        <li>
                                                                            <a href="<?php echo base_url(); ?>pos/sales/print_return_a4/<?php echo $row->pos_sales_return_id; ?>" target="_blank"><em class="icon ni ni-printer"></em><span>Print</span></a>
                                                                        </li>
                                                                    <?php endif; ?>
                                                                    <li>
                                                                        <a href="javascript:;" data-toggle="modal" data-target="#modal_send_sales_return_order_via_email" data-pos-sales-return-id="<?php echo $row->pos_sales_return_id; ?>" class="lnk_send_sales_return_order_via_email"><em class="icon ni ni-mail"></em><span>Send via Mail</span></a>
                                                                    </li>
                                                                    <?php if ($sbr_pos_sales_returns_delete == true): ?>
                                                                        <?php if ($row->is_void == 0): ?>
                                                                            <li>
                                                                                <a href="#" data-pos-sales-return-id="<?php echo $row->pos_sales_return_id; ?>" data-context="Sales Returns List" class="lnk_void_pos_sales_return"><em class="icon ni ni-shield-off"></em><span>Void sales_return</span></a>
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
                                    