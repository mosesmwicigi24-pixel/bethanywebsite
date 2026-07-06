                            <script>
                                $(document).ready(function() {
                                    $(".datatable-init").DataTable({
                                        responsive: { details: !0 },
                                        autoWidth: !1,
                                        iDisplayLength: 50,
                                        lengthMenu: [50, 100, 150, 200 ],
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
                                        },
                                        "footerCallback": function ( row, data, start, end, display ) {
                                            var api = this.api();
                                 
                                            var intVal = function ( i ) {
                                                return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
                                            };
                                 
                                            total = api.column( 5 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                                            totalPaid = api.column( 6 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                                            totalDue = api.column( 7 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
                                 
                                            pageTotal = api.column( 5, { page: 'current'} ).data().reduce( function (a, b) {
                                                return intVal(a) + intVal(b);
                                            }, 0 );
                                 
                                            $( api.column( 5 ).footer() ).html(total.formatMoney(2,',','.'));
                                            $( api.column( 6 ).footer() ).html(totalPaid.formatMoney(2,',','.'));
                                            $( api.column( 7 ).footer() ).html(totalDue.formatMoney(2,',','.'));
                                            // '$'+pageTotal +' ( $'+ total +' total)'
                                        }
                                    });
                                });
                                function formatNumber(n) {
                                    return n.toLocaleString();
                                }
                            </script>     
                            <table class="datatable-init nk-tb-list nk-tb-ulist table" data-auto-responsive="true">
                                <thead class="bg-lighter">
                                    <tr class="nk-tb-item nk-tb-head">
                                        <th class="nk-tb-col nk-tb-col-check">
                                            <div class="custom-control custom-control-sm custom-checkbox notext">
                                                <input type="checkbox" class="custom-control-input" id="uid" /><label class="custom-control-label" for="uid"></label>
                                            </div>
                                        </th>
                                        <th class="nk-tb-col"><span class="">Sale No</span></th>
                                        <th class="nk-tb-col tb-col-mb"><span class="">Total Amount</span></th>
                                        <th class="nk-tb-col tb-col-mb"><span class="">Amount Paid</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Balance</span></th>
                                        <th class="nk-tb-col tb-col-lg"><span class="">Total Amount (<?php echo $default_currency; ?>)</span></th>
                                        <th class="nk-tb-col tb-col-lg"><span class="">Paid Amount (<?php echo $default_currency; ?>)</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Transaction Status (<?php echo $default_currency; ?>)</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Action </span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Created By</span></th>
                                        <th class="nk-tb-col nk-tb-col-tools text-right"></th>
                                    </tr>
                                </thead>
                                <tbody>                       
                                    <?php foreach ($sales_list as $row): ?>
                                        <?php $payment_balance = $row->total_sale - $row->total_paid; ?>

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
                                                            <?php if ($sbr_pos_sales_orders_view == true): ?>
                                                                <a href="<?php echo base_url(); ?>pos/sales/view/<?php echo $row->pos_sale_id; ?>">#<?php echo $row->pos_sale_number; ?></a>
                                                            <?php else: ?>
                                                                #<?php echo $row->pos_sale_number; ?>
                                                            <?php endif; ?>
                                                        </b></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="nk-tb-col tb-col-lg"><span><?php echo $row->sale_type; ?></span></td>
                                            <td class="nk-tb-col tb-col-lg"><span><?php echo date('d-m-Y', strtotime($row->sale_date)); ?></span></td>
                                            <td class="nk-tb-col tb-col-lg">
                                                <span><?php if ($row->customer_id == 0){ echo $row->customer_name; } else { echo $row->first_name . ' ' . $row->last_name; } ?></span>
                                            </td>
                                            <td class="nk-tb-col tb-col-mb">
                                                <?php echo number_format($row->total_sale,2); ?>
                                            </td>
                                            <td class="nk-tb-col tb-col-mb">
                                                <?php echo number_format($row->total_paid,2); ?>
                                            </td>
                                            <td class="nk-tb-col tb-col-mb">
                                                <?php echo number_format(($row->total_sale - $row->total_paid),2); ?>
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
                                                                    <?php if ($sbr_pos_sales_orders_view == true): ?>
                                                                        <li>
                                                                            <a href="<?php echo base_url(); ?>pos/sales/view/<?php echo $row->pos_sale_id; ?>"><em class="icon ni ni-eye"></em><span>View Sale</span></a>
                                                                        </li>
                                                                    <?php endif; ?>
                                                                    <?php if ($sbr_pos_sales_orders_edit == true): ?>
                                                                        <?php if ($row->is_void == 0): ?>
                                                                            <li>
                                                                                <a href="<?php echo base_url(); ?>pos/sales/edit/<?php echo $row->pos_sale_id; ?>"><em class="icon ni ni-edit"></em><span>Edit Sale</span></a>
                                                                            </li>                                                                    
                                                                            <li>
                                                                                <a href="javascript:;" data-pos-sale-id="<?php echo $row->pos_sale_id; ?>" data-context="Sales List" class="edit_btn_make_payment"><em class="icon ni ni-coin-alt-fill"></em><span>Receive Payment</span></a>
                                                                            </li>
                                                                        <?php endif; ?>
                                                                    <?php endif; ?>
                                                                    <li class="divider"></li>
                                                                    <!-- <li>
                                                                        <a href="<?php echo base_url(); ?>pos/sales/print_receipt/<?php echo $row->pos_sale_id; ?>" target="_blank"><em class="icon ni ni-printer"></em><span>Print Receipt</span></a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="<?php echo base_url(); ?>pos/sales/print_sale/<?php echo $row->pos_sale_id; ?>" target="_blank"><em class="icon ni ni-printer"></em><span>Print A4 Receipt</span></a>
                                                                    </li>
                                                                    <li>
                                                                        <a href="<?php echo base_url(); ?>pos/sales/pdf/<?php echo $row->pos_sale_id; ?>" target="_blank"><em class="icon ni ni-file-pdf"></em><span>PDF</span></a>
                                                                    </li> -->
                                                                    <?php if ($sbr_pos_sales_orders_print == true): ?>
                                                                        <li>
                                                                            <a href="<?php echo base_url(); ?>pos/sales/print_thermal/<?php echo $row->pos_sale_id; ?>" target="_blank"><em class="icon ni ni-printer"></em><span>Print on Thermal Paper</span></a>
                                                                        </li>
                                                                        <li>
                                                                            <a href="<?php echo base_url(); ?>pos/sales/print_a4/<?php echo $row->pos_sale_id; ?>" target="_blank"><em class="icon ni ni-printer"></em><span>Print on A4 Paper</span></a>
                                                                        </li>
                                                                    <?php endif; ?>
                                                                    <li>
                                                                        <a href="#" data-toggle="modal" data-target="#modal_send_sale_order_via_email" data-pos-sale-id="<?php echo $row->pos_sale_id; ?>" class="lnk_send_sale_order_via_email"><em class="icon ni ni-mail"></em><span>Send via Mail</span></a>
                                                                    </li>
                                                                    <?php if ($sbr_pos_sales_orders_delete == true): ?>
                                                                        <?php if ($row->is_void == 0): ?>
                                                                            <li>
                                                                                <a href="#" data-pos-sale-id="<?php echo $row->pos_sale_id; ?>" data-context="Sales List" class="lnk_void_pos_sale"><em class="icon ni ni-shield-off"></em><span>Void Sale</span></a>
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
                                <tfoot>
                                    <tr>
                                        <th colspan="5" style="text-align:right">Totals:</th>
                                        <th></th>
                                        <th></th>
                                        <th></th>
                                        <th colspan="3" style="text-align:left"></th>
                                    </tr>
                                </tfoot>
                            </table>
                                    