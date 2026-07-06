								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 0, "desc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ ] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th style="width: 150px">IP Address</th>
											<th style="width: 150px">Country</th>
											<th style="width: 130px">City</th>
											<th style="width: 130px">Date</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($affiliate_clicks as $row): ?>
											<tr>
												<td><?php echo $row->ip_address; ?></td>
												<td><?php echo $row->country; ?></td>
                                                <td><?php echo $row->city; ?></td>
                                                <td><?php echo date('jS M Y H:i:s', strtotime($row->created_on)); ?></td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>

