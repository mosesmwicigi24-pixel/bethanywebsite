								<!-- <ul class="nav nav-tabs nav-tabs-bottom font-13">
									<li class="nav-item"><a href="#bottom-tab1" class="nav-link active" data-toggle="tab">POS Sales</a></li>
									<li class="nav-item"><a href="#bottom-tab2" class="nav-link" data-toggle="tab">Online Sales</a></li>
								</ul>

								<div class="tab-content">
									<div class="tab-pane fade show active" id="bottom-tab1"> -->
										<script type="text/javascript">
											$('.table-pos-sales-transactions').DataTable({
												iDisplayLength: 50,
												lengthMenu: [50, 100, 150, 200 ],
												"order": [[ 3, "desc" ]],
											    "columnDefs": [
											        { "orderable": false, "targets": [ ] }
											    ],
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
										</script>
										<table class="table table-striped table-bordered table-pos-sales-transactions" data-auto-responsive="true">
			                                <thead>
			                                    <tr>
			                                        <th class="">Sale No</th>
			                                        <th class="">Outlet</th>
			                                        <th class="">Sale Type</th>
			                                        <th class="">Date</th>
			                                        <th class="">Customer</th>
			                                        <th class="">Total (<?php echo $default_currency; ?>)</th>
			                                        <th class="">Paid (<?php echo $default_currency; ?>)</th>
			                                        <th class="">Due (<?php echo $default_currency; ?>)</th>
			                                        <th class="">Status</th>
			                                        <th class="">User</th>
			                                    </tr>
			                                </thead>
			                                <tbody>                       
			                                    <?php foreach ($pos_sales_transactions as $row): ?>
			                                    	<?php $sale_date = strtotime($row->sale_date); ?>
			                                        <?php $payment_balance = $row->total_sale - $row->total_paid; ?>
			                                        <tr class="nk-tb-item <?php if ($row->is_void == 1){ echo 'text-muted'; } ?>">
			                                            <td class="">
			                                                <div class="user-card">
			                                                    <div class="user-info">
			                                                        <span class="tb-lead font-weight-bold"><a>#<?php echo $row->pos_sale_number; ?></a></span>
			                                                    </div>
			                                                </div>
			                                            </td>
			                                            <td><?php echo $row->outlet_name; ?></td>
			                                            <td><?php echo $row->sale_type; ?></td>
			                                            <td data-sort="<?php echo $sale_date; ?>"><span><?php echo date('d-m-Y', strtotime($row->sale_date)); ?></span></td>
			                                            <td class="">
			                                                <span><?php if ($row->customer_id == 0){ echo $row->customer_name; } else { echo $row->first_name . ' ' . $row->last_name; } ?></span>
			                                            </td>
			                                            <td>
			                                                <?php echo number_format($row->total_sale,2); ?>
			                                            </td>
			                                            <td>
			                                                <?php echo number_format($row->total_paid,2); ?>
			                                            </td>
			                                            <td>
			                                                <?php echo number_format(($row->total_sale - $row->total_paid),2); ?>
			                                            </td>                                        
			                                            
			                                            <td class="">
			                                                <?php if ($row->is_void == 1): ?>
			                                                    <span class="badge badge-pill bg-grey">Void</span>
			                                                <?php else: ?>
			                                                    <?php if ($payment_balance == $row->total_sale): ?>
			                                                        <span class="badge badge-pill badge-danger">Unpaid</span>
			                                                    <?php elseif ($payment_balance > 0): ?>
			                                                        <span class="badge badge-pill badge-info">Partially Paid</span>
			                                                    <?php else: ?>
			                                                        <span class="badge badge-pill badge-success">Paid</span>
			                                                    <?php endif; ?>
			                                                <?php endif; ?>
			                                            </td>
			                                            <td class=""><span><?php echo $row->system_user_first_name . ' ' . $row->system_user_last_name; ?> </span></td>
			                                        </tr>


			                                    <?php endforeach; ?>
			                                </tbody>
			                                <tfoot>
			                                    <tr>
			                                        <th colspan="5" style="text-align:right">Total:</th>
			                                        <th></th>
			                                        <th></th>
			                                        <th></th>
			                                        <th colspan="2" style="text-align:left"></th>
			                                    </tr>
			                                </tfoot>
			                            </table>
									<!--</div>

									<div class="tab-pane fade" id="bottom-tab2">
										
									</div>
								</div> -->