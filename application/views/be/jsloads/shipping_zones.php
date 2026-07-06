								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 1, "asc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 0, 5] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_shipping_zones_0" name="chk_shipping_zone_id0" value="0"></th>
											<th style="width: 250px">Name</th>
											<th style="width: 200px">Region</th>
											<th style="width: 150px" class="text-center">Shipping Method</th>
											<th style="width: 120px" class="text-center">Shipping Fee</th>
											<th style="width: 90px" class="text-center">Status</th>
											<th style="width: 90px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($shipping_zones as $row): ?>
											<tr>
												<td class="text-center"><input type="checkbox" id="chk_shipping_zones_<?php echo $row->shipping_zone_id; ?>" name="chk_shipping_zone_id" value="<?php echo $row->shipping_zone_id; ?>"></td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_shipping_zone" onclick="shipping_zone_edit_load(<?php echo $row->shipping_zone_id; ?>);" class="font-weight-bold"><?php echo $row->shipping_zone_name; ?></a>
														</div>
													</div>													
												</td>
												<td><?php echo $row->region_name . ', ' . $row->country_name; ?></td>
												<td class="text-center">
													<?php if ($row->shipping_method == 0){ echo 'Free Shipping'; }else{ echo 'Shipping Fee'; } ?>
												</td>
												<td class="text-center"><?php if ($row->shipping_method == 0){ echo '&mdash;'; }else{ echo number_format($row->shipping_fee, 2); } ?></td>
												<td class="text-center">
													<?php if ($row->is_active == 1): ?>
														<span class="badge badge-success">Active</span>
													<?php else: ?>
														<span class="badge badge-secondary">Inactive</span>
													<?php endif; ?>
												</td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_shipping_zone" onclick="shipping_zone_edit_load(<?php echo $row->shipping_zone_id; ?>);" class="dropdown-item"><i class="icon-pencil6"></i> View/Edit Shipping Zone</a>
																<?php if ($sbr_shipping_zones_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_shipping_zone(<?php echo $row->shipping_zone_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete Shipping Zone</a>
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
									$("#chk_shipping_zones_0").on('change', function() {
								        if($("#chk_shipping_zones_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_shipping_zone_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_shipping_zone_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								