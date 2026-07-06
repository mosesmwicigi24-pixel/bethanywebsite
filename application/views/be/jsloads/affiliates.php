								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 1, "asc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 0, 1, 5, 6] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_affiliates_0" name="chk_affiliate_id0" value="0"></th>
											<th style="width: 250px">Name</th>
											<th style="width: 180px">Email</th>
											<th style="width: 130px">Phone</th>
											<th style="width: 180px">Address</th>
											<th style="width: 130px">Town</th>
											<th style="width: 130px">Country</th>
											<th style="width: 150px">Company</th>
											<th style="width: 150px">URL</th>
											<!-- <th style="width: 90px">Sort Key</th> -->
											<th style="width: 80px" class="text-center">Verified</th>
											<th style="width: 80px" class="text-center">Status</th>
											<th style="width: 80px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($affiliates as $row): ?>
											<tr>
												<td class="text-center"><input type="checkbox" id="chk_affiliates_<?php echo $row->affiliate_id; ?>" name="chk_affiliate_id" value="<?php echo $row->affiliate_id; ?>"></td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<?php if ($sbr_affiliate_accounts_manage == true): ?>
																<a href="<?php echo base_url();?>be/affiliates/manage/<?php echo $row->affiliate_id; ?>" class="font-weight-bold"><?php echo $row->first_name . ' ' . $row->last_name; ?></a>
															<?php else: ?>
																<span class="font-weight-bold"><?php echo $row->first_name . ' ' . $row->last_name; ?></span>
															<?php endif; ?>
															<?php if ($row->affiliate_code != ''): ?>
																<?php echo '<br><b>Affiliate Code:</b> ' . $row->affiliate_code; ?>
															<?php endif; ?>
														</div>
													</div>													
												</td>
												<td><?php echo $row->email_address; ?></td>
												<td><?php echo $row->phone_number; ?></td>
												<td><?php echo $row->physical_address; ?></td>
												<td><?php echo $row->town; ?></td>
												<td><?php echo $row->country_name; ?></td>
												<td><?php echo $row->company_name; ?></td>
												<td><?php echo $row->short_url; ?></td>
												<td class="text-center">
													<?php if ($row->is_verified == 1): ?>
														<span class="badge bg-success">Yes</span>
													<?php else: ?>
														<span class="badge bg-danger">No</span>
													<?php endif; ?>
												</td>
												<td class="text-center">
													<?php if ($row->affiliate_status == 0): ?>
	                                                    <span class="badge badge-info">Pending</span>
	                                                <?php elseif ($row->affiliate_status == 1): ?>
	                                                    <span class="badge badge-success">Active</span>
	                                                <?php elseif ($row->affiliate_status == 2): ?>
	                                                    <span class="badge badge-danger">Suspended</span>
	                                                <?php elseif ($row->affiliate_status == 3): ?>
	                                                    <span class="badge badge-danger">Deleted</span>
	                                                <?php endif; ?>
												</td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<?php if ($sbr_affiliate_accounts_manage == true): ?>
																	<a href="<?php echo base_url();?>be/affiliates/manage/<?php echo $row->affiliate_id; ?>" class="dropdown-item"><i class="icon-cog3 text-primary"></i> Manage Account</a>
																<?php endif; ?>
																<?php if ($sbr_affiliate_accounts_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_affiliate(<?php echo $row->affiliate_id; ?>);" class="dropdown-item"><i class="icon-cancel-circle2 text-danger"></i> Delete Affiliate</a>
																<?php endif; ?>
															</div>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>


								<script type="text/javascript">
									$("#chk_affiliates_0").on('change', function() {
								        if($("#chk_affiliates_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_affiliate_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_affiliate_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								