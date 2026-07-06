
                                <script type="text/javascript">
                                    $('.datatable-basic').DataTable({
                                        iDisplayLength: 50,
                                        lengthMenu: [50, 100, 150, 200 ],
                                        "order": [[ 0, "asc" ]],
                                        "columnDefs": [
                                            { "orderable": false, "targets": [ ] }
                                        ],
                                        "footerCallback": function ( row, data, start, end, display ) {
                                            var api = this.api();
                                 
                                            var intVal = function ( i ) {
                                                return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
                                            };
                                 
                                            total = api.column( 4 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );                                 
                                            $( api.column( 4 ).footer() ).html(total.formatMoney(2,',','.'));
                                        }
                                    });
                                </script>
                                <table class="table  datatable-basic table-bordered">
                                    <thead>
                                        <tr>
                                            <th class="text-center" style="width: 50px">#</th>
                                            <th style="width: 100px">Outlet</th>
                                            <th style="width: 100px">Date</th>
                                            <th style="width: 150px">Description</th>
                                            <th style="width: 90px">Amount (<?php echo $default_currency; ?>)</th>
                                            <th style="width: 90px">Reference #</th>
                                            <th style="width: 90px">Payment Note</th>
                                            <th style="width: 90px">User</th>
                                            <th style="width: 90px">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody> 
                                        <?php $count = 1; ?>
                                        <?php foreach ($expenses_report as $row): ?>
                                            <tr class="<?php if ($row->is_void == 1){ echo 'text-muted'; } ?>">    
                                                <td class="text-center"><?php echo $count; ?></td>
                                                <td><?php echo $row->outlet_name; ?></td>
                                                <td><?php echo date('d M, Y', strtotime($row->expense_date)); ?></td>
                                                <td><?php echo $row->expense_description; ?></td>
                                                <td><?php echo number_format($row->expense_amount,2); ?></td>
                                                <td><?php echo $row->expense_reference_number; ?></td>
                                                <td><?php echo $row->expense_note; ?></td>
                                                <td><?php echo $row->system_user_first_name . ' ' . $row->system_user_last_name; ?></td>
                                                <td>
                                                    <?php if ($row->is_void == 0): ?>
                                                        <span class="badge badge-success">Active</span>
                                                    <?php elseif ($row->is_void == 1): ?>
                                                        <span class="badge bg-grey">Void</span>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>
                                            <?php $count++; ?>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th class="font-weight-bold" colspan="4" style="text-align:right">Total:</th>
                                            <th class="font-weight-bold" colspan="4"></th>                                          
                                        </tr>
                                    </tfoot>
                                </table>


                                