										<script type="text/javascript">
											$('.table-pos-item-sales').DataTable({
												iDisplayLength: 50,
												lengthMenu: [50, 100, 150, 200 ],
												"order": [[ 2, "desc" ]],
											    "columnDefs": [
											        { "orderable": false, "targets": [ ] }
											    ],
		                                        "footerCallback": function ( row, data, start, end, display ) {
		                                            var api = this.api();
		                                 
		                                            var intVal = function ( i ) {
		                                                return typeof i === 'string' ? i.replace(/[\$,]/g, '')*1 : typeof i === 'number' ? i : 0;
		                                            };
		                                 
		                                            qtytotal = api.column( 7 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
		                                            // totalPaid = api.column( 6 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
		                                            total = api.column( 9 ).data().reduce( function (a, b) { return intVal(a) + intVal(b); }, 0 );
		                                 
		                                            $( api.column( 7 ).footer() ).html(qtytotal.formatMoney(2,',','.'));
		                                            // $( api.column( 6 ).footer() ).html(totalPaid.formatMoney(2,',','.'));
		                                            $( api.column( 9 ).footer() ).html(total.formatMoney(2,',','.'));
		                                        }
											});
										</script>
										<table class="table table-striped table-bordered table-pos-item-sales" data-auto-responsive="true">
			                                <thead>
			                                    <tr>
			                                        <th class="">Sale No</th>
			                                        <th class="">Outlet</th>
			                                        <th class="">Date</th>
			                                        <th class="">Customer</th>
			                                        <th class="">Product</th>
			                                        <th class="">Unit</th>
			                                        <th class="">Unit Price</th>
			                                        <th class="">Quantity</th>
			                                        <th class="">Tax</th>
			                                        <th class="">Total</th>
			                                        <th class="">User</th>
			                                    </tr>
			                                </thead>
			                                <tbody>                       
			                                    <?php foreach ($item_sales as $row): ?>
			                                    	<?php $sale_date = strtotime($row->sale_date); ?>
			                                    	<?php
						                                $variation_description = '';
						                                if(!empty($row->attributes)){
						                                    foreach ($row->attributes as $row3){
						                                        $variation_description = $variation_description . $row3->product_attribute_name . ' : <b>' . $row3->product_attribute_value . '</b>, ';
						                                    }
						                                    $variation_description =  '<br>~ ' . substr($variation_description,0,-2) . '<br>';
						                                }
						                            ?>
			                                        <tr class="nk-tb-item">
			                                            <td class="">
			                                                <div class="user-card">
			                                                    <div class="user-info">
			                                                        <span class="tb-lead font-weight-bold"><a>#<?php echo $row->pos_sale_number; ?></a></span>
			                                                    </div>
			                                                </div>
			                                            </td>
			                                            <td><?php echo $row->outlet_name; ?></td>
			                                            <td data-sort="<?php echo $sale_date; ?>"><span><?php echo date('d-m-Y', strtotime($row->sale_date)); ?></span></td>
			                                            <td class="">
			                                                <span><?php if ($row->customer_id == 0){ echo $row->customer_name; } else { echo $row->first_name . ' ' . $row->last_name; } ?></span>
			                                            </td>
			                                            <td><?php echo $row->product_name; ?><?php echo $variation_description; ?></td>
			                                            <td><?php echo ($row->unit_name . ' (' . $row->unit_code . ')'); ?></td>
			                                            <td><?php echo number_format(($row->unit_price),2); ?></td> 
			                                            <td><?php echo number_format(($row->quantity),2); ?></td>
			                                            <td><?php echo $row->tax_rate_code . ' [' . $row->tax_rate_value . '%]'; ?></td>
			                                            <td><?php echo number_format(($row->sub_total),2); ?></td>
			                                            <td class=""><span><?php echo $row->system_user_first_name . ' ' . $row->system_user_last_name; ?> </span></td>
			                                        </tr>


			                                    <?php endforeach; ?>
			                                </tbody>
			                                <tfoot>
			                                    <tr>
			                                        <th colspan="7" style="text-align:right">Totals:</th>
			                                        <th></th>
			                                        <th></th>
			                                        <th colspan="2"></th>
			                                        <!-- <th colspan="2" style="text-align:left"></th> -->
			                                    </tr>
			                                </tfoot>
			                            </table>
