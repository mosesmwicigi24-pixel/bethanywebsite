								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 2, "asc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 0, 1, 6, 7] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_customers_0" name="chk_customer_id0" value="0"></th>
											<th style="width: 150px" class="text-center">Profile Picture</th>
											<th style="width: 300px">Name</th>
											<th style="width: 180px">Email</th>
											<th style="width: 150px">Phone</th>
											<th style="width: 90px">Sort Key</th>
											<th style="width: 80px" class="text-center">Status</th>
											<th style="width: 80px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($customers as $row): ?>
											<tr>
												<td class="text-center"><input type="checkbox" id="chk_customers_<?php echo $row->customer_id; ?>" name="chk_customer_id" value="<?php echo $row->customer_id; ?>"></td>
												<td class="text-center">
													<div class="media">
														<div class="media-body align-self-center">
															<?php if($row->profile_picture_thumb != '' && file_exists("./uploads/customer_profile_pictures/thumbs/" . $row->profile_picture_thumb)): ?>
																<img src="<?php echo base_url();?>uploads/customer_profile_pictures/thumbs/<?php echo $row->profile_picture_thumb; ?>" class="" width="100" alt="">
						                                    <?php else: ?>
						                                    	&mdash;
						                                    <?php endif; ?>
														</div>
													</div>
												</td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<?php //if ($sbr_customers_edit == true): ?>
																<a href="<?php echo base_url();?>be/customers/edit/<?php echo $row->customer_id; ?>" class="font-weight-bold"><?php echo $row->first_name . ' ' . $row->last_name; ?></a>
															<?php //else: ?>
																<!-- <span class="font-weight-bold"><?php echo $row->first_name . ' ' . $row->last_name; ?></span> -->
															<?php //endif; ?>
															<?php if ($row->customer_code != ''): ?>
																<?php echo '<br><b>Customer Code:</b> ' . $row->customer_code; ?>
															<?php endif; ?>
														</div>
													</div>													
												</td>
												<td><?php echo $row->email_address; ?></td>
												<td><?php echo $row->phone_number; ?></td>
												<td><?php echo $row->sort_key; ?></td>
												<td class="text-center">
													<?php if ($row->is_active == 1): ?>
														<span class="badge bg-success">Active</span>
													<?php else: ?>
														<span class="badge bg-danger">Inactive</span>
													<?php endif; ?>
												</td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<?php //if ($sbr_customers_edit == true): ?>
																	<a href="<?php echo base_url();?>be/customers/edit/<?php echo $row->customer_id; ?>" class="dropdown-item"><i class="icon-pencil6 text-primary"></i> View/Edit Customer</a>
																<?php //endif; ?>
																<?php if ($sbr_customers_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_customer(<?php echo $row->customer_id; ?>);" class="dropdown-item"><i class="icon-cancel-circle2 text-danger"></i> Delete Customer</a>
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
									$("#chk_customers_0").on('change', function() {
								        if($("#chk_customers_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_customer_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_customer_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								