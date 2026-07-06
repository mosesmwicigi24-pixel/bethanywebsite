								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										"order": [[ 3, "asc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 0, 3, 4 ] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_product_sizes_0" name="chk_product_size_id0" value="0"></th>
											<th style="width: 180px">Name</th>
											<th style="width: 120px">Code</th>
											<th style="width: 90px">Sort Key</th>
											<th style="width: 90px">Status</th>
											<th style="width: 90px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($product_sizes as $row): ?>
											<tr>
												<td class="text-center"><input type="checkbox" id="chk_product_sizes_<?php echo $row->product_size_id; ?>" name="chk_product_size_id" value="<?php echo $row->product_size_id; ?>"></td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_product_size" onclick="product_size_edit_load(<?php echo $row->product_size_id; ?>);" class="font-weight-bold"><?php echo $row->product_size_name; ?></a>
														</div>
													</div>													
												</td>
												<td><?php echo $row->product_size_code; ?></td>
												<td><?php echo $row->sort_key; ?></td>
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
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_product_size" onclick="product_size_edit_load(<?php echo $row->product_size_id; ?>);" class="dropdown-item"><i class="icon-pencil6"></i> View/Edit Product Size</a>
																<?php if ($sbr_product_sizes_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_product_size(<?php echo $row->product_size_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete Product Size</a>
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
									$("#chk_product_sizes_0").on('change', function() {
								        if($("#chk_product_sizes_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_product_size_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_product_size_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								