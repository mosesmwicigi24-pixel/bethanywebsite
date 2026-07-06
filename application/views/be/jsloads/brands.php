								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 3, "asc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 0, 1, 4, 5] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_brands_0" name="chk_brand_id0" value="0"></th>
											<th style="width: 120px"></th>
											<th style="width: 250px">Name</th>
											<th style="width: 100px">Sort Key</th>
											<th style="width: 90px">Status</th>
											<th style="width: 90px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($brands as $row): ?>
											<tr>												
												<td class="text-center"><input type="checkbox" id="chk_brands_<?php echo $row->brand_id; ?>" name="chk_brand_id" value="<?php echo $row->brand_id; ?>"></td>
												<td>
													<div class="media">
														<div class="media-body align-self-center text-center">
															<?php if($row->logo_thumb != '' && file_exists("./uploads/brand_logos/thumbs/" . $row->logo_thumb)): ?>
																<img src="<?php echo base_url();?>uploads/brand_logos/thumbs/<?php echo $row->logo_thumb; ?>" class="" width="70" alt="">
						                                    <?php endif; ?>
														</div>
													</div>
												</td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_brand" onclick="brand_edit_load(<?php echo $row->brand_id; ?>);" class="font-weight-bold"><?php echo $row->brand_name; ?></a>
														</div>
													</div>													
												</td>
												<td><?php echo $row->sort_key; ?></td>
												<td>
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
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_brand" onclick="brand_edit_load(<?php echo $row->brand_id; ?>);" class="dropdown-item"><i class="icon-pencil6"></i> View/Edit Brand</a>
																<?php if ($sbr_brands_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_brand(<?php echo $row->brand_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete Brand</a>
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
									$("#chk_brands_0").on('change', function() {
								        if($("#chk_brands_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_brand_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_brand_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								