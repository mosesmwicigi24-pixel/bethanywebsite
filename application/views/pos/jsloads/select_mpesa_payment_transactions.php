                            <script>
                                $(".datatable2").DataTable({
                                    responsive: { details: !0 },
                                    autoWidth: !1,
                                    order: [[ 1, "desc" ]],
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
                            <table class="datatable-init datatable2 nk-tb-list nk-tb-ulist table" data-auto-responsive="true">
                                <thead class="bg-lighter">
                                    <tr class="nk-tb-item nk-tb-head">
                                        <th class="nk-tb-col"><span class="">Transaction #</span></th>
                                        <th class="nk-tb-col tb-col-mb"><span class="">Date</span></th>
                                        <th class="nk-tb-col tb-col-md"><span class="">Paid By</span></th>
                                        <th class="nk-tb-col tb-col-lg"><span class="">Amount</span></th>
                                    </tr>
                                </thead>
                                <tbody>                       
                                    <?php foreach ($paybill_payments as $row): ?>
                                        <?php $payment_date = strtotime($row->transaction_time); ?>
                                        <tr class="nk-tb-item">
                                            <td class="nk-tb-col">
                                                <div class="user-card">
                                                    <div class="user-info">
                                                        <span class="tb-lead"><a class="lnk-select-payment-mpesa-transaction" data-reference-number="<?php echo $row->transaction_id; ?>" data-payment-amount="<?php echo $row->transaction_amount; ?>" data-context="<?php echo $context; ?>" href="javascript:;"><?php echo $row->transaction_id; ?></a></span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="nk-tb-col tb-col-lg" data-sort="<?php echo $payment_date; ?>"><a class="lnk-select-payment-mpesa-transaction" data-reference-number="<?php echo $row->transaction_id; ?>" data-payment-amount="<?php echo $row->transaction_amount; ?>" data-context="<?php echo $context; ?>" href="javascript:;"><span><?php echo date('d-m-Y H:i:s', strtotime($row->transaction_time)); ?></span></a></td>
                                            <td class="nk-tb-col tb-col-lg">
                                                <a class="lnk-select-payment-mpesa-transaction" data-reference-number="<?php echo $row->transaction_id; ?>" data-payment-amount="<?php echo $row->transaction_amount; ?>" data-context="<?php echo $context; ?>" href="javascript:;"><span><?php echo $row->first_name . ' ' . $row->middle_name . ' ' . $row->last_name;  ?></span></a>
                                            </td>
                                            <td class="nk-tb-col tb-col-mb">
                                                <span class="tb-amount"><a class="lnk-select-payment-mpesa-transaction" data-reference-number="<?php echo $row->transaction_id; ?>" data-payment-amount="<?php echo $row->transaction_amount; ?>" data-context="<?php echo $context; ?>" href="javascript:;"><?php echo number_format($row->transaction_amount,2); ?></a></span>
                                            </td>
                                        </tr>


                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                                    