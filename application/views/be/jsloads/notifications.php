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
											<th>Date</th>
											<th>Notification Details</th>
                                            <th>Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($notifications as $row): ?>
											<?php $sort_date = strtotime($row->created_on); ?>
                                            <tr class="<?php if ($row->is_read == 0){echo 'bg-light';} ?>">
                                            	<td data-sort="<?php echo $sort_date; ?>"><?php echo date('d M, Y H:i:s', strtotime($row->created_on)); ?></td>
                                            	<td><?php echo $row->notification_details; ?></td>
                                                <td>
                                                	<a href="<?php echo base_url() . $row->notification_ref_link; ?>" class="badge badge-primary badge-pill lnk-notification" data-notification-id="<?php echo $row->notification_id; ?>">View</a>
                                                </td>
                                            </tr>
										<?php endforeach; ?>
									</tbody>
								</table>



								