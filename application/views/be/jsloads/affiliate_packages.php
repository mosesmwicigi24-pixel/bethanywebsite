								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 1, "asc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 0, 4, 5] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_affiliate_packages_0" name="chk_affiliate_package_id0" value="0"></th>
											<th style="width: 200px">Package Name</th>
											<th style="width: 130px" class="text-center">Commission (%)</th>
											<th style="width: 130px" class="text-center">Minimum Pay Out</th>
											<th style="width: 100px" class="text-center">Status</th>
											<th style="width: 100px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($affiliate_packages as $row): ?>
											<tr>
												<td class="text-center"><input type="checkbox" id="chk_affiliate_packages_<?php echo $row->affiliate_package_id; ?>" name="chk_affiliate_package_id" value="<?php echo $row->affiliate_package_id; ?>"></td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<span style="color: <?php echo $row->affiliate_package_colour_code; ?>" class="icon-star-full2"></span> <a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_affiliate_package" onclick="affiliate_package_edit_load(<?php echo $row->affiliate_package_id; ?>);" class="font-weight-bold"><?php echo $row->affiliate_package_name; ?></a>
														</div>
													</div>													
												</td>
												<td class="text-center"><?php echo number_format($row->commission,1); ?></td>
												<td class="text-center">KES <?php echo number_format($row->minimum_pay_out,2); ?></td>
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
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_affiliate_package" onclick="affiliate_package_edit_load(<?php echo $row->affiliate_package_id; ?>);" class="dropdown-item"><i class="icon-pencil6"></i> View/Edit</a>
																<?php if ($sbr_affiliate_packages_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_affiliate_package(<?php echo $row->affiliate_package_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete</a>
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
									$("#chk_affiliate_packages_0").on('change', function() {
								        if($("#chk_affiliate_packages_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_affiliate_package_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_affiliate_package_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								