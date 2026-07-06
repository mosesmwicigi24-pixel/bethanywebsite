								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 2, "asc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 0, 1, 8, 9] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_businesses_0" name="chk_business_id0" value="0"></th>
											<th style="width: 150px" class="text-center">Logo</th>
											<th>Business Name</th>
											<th>Business Code</th>
											<th>Business Category</th>
											<th>Contact Person</th>
											<th>Email Address</th>
											<th>Phone Number</th>
											<th style="width: 120px" class="text-center">Status</th>
											<th style="width: 120px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($businesses as $row): ?>
											<tr>
												<td class="text-center"><input type="checkbox" id="chk_businesses_<?php echo $row->business_id; ?>" name="chk_business_id" value="<?php echo $row->business_id; ?>"></td>
												<td class="text-center">
													<div class="media">
														<div class="media-body align-self-center">
															<?php if($row->business_thumb_logo != '' && file_exists("./uploads/business_logos/thumbs/" . $row->business_thumb_logo)): ?>
																<img src="<?php echo base_url();?>uploads/business_logos/thumbs/<?php echo $row->business_thumb_logo; ?>" class="" width="100" alt="">
						                                    <?php else: ?>
						                                    	&mdash;
						                                    	<!-- <img src="<?php echo base_url();?>assets/be/images/placeholder.png" class="" width="100" alt=""> -->
						                                    <?php endif; ?>
														</div>
													</div>
												</td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="<?php echo base_url();?>be/businesses/edit/<?php echo $row->business_id; ?>" class="font-weight-bold"><?php echo $row->business_name; ?></a>
														</div>
													</div>													
												</td>
												<td><?php echo $row->business_sku_code; ?></td>
												<td><?php echo $row->business_category_name; ?></td>
												<td><?php echo $row->business_contact_person; ?></td>
												<td><?php echo $row->business_email_address; ?></td>
												<td><?php echo $row->business_phone_number; ?></td>
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
																<a href="<?php echo base_url();?>be/businesses/edit/<?php echo $row->business_id; ?>" class="dropdown-item"><i class="icon-pencil6"></i> Edit</a>
																<a href="javascript:void(0);" onclick="delete_business(<?php echo $row->business_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete</a>
															</div>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>


								<script type="text/javascript">
									$("#chk_businesses_0").on('change', function() {
								        if($("#chk_businesses_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_business_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_business_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								