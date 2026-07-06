								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										"order": [[ 1, "asc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 0, 3 ] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_regions_0" name="chk_region_id0" value="0"></th>
											<th style="width: 250px">Name</th>
											<th style="width: 100px">Sort Key</th>
											<th style="width: 100px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($regions as $row): ?>
											<tr>
												<td class="text-center"><input type="checkbox" id="chk_regions_<?php echo $row->region_id; ?>" name="chk_region_id" value="<?php echo $row->region_id; ?>"></td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_region" onclick="region_edit_load(<?php echo $row->region_id; ?>);" class="font-weight-bold"><?php echo $row->region_name; ?></a>
														</div>
													</div>													
												</td>
												<td><?php echo $row->sort_key; ?></td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<!-- <a href="<?php echo base_url();?>be/locations/localities/<?php echo $row->region_id; ?>" class="dropdown-item"><i class="icon-list"></i> Manage Localities</a> -->
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_region" onclick="region_edit_load(<?php echo $row->region_id; ?>);" class="dropdown-item"><i class="icon-pencil6"></i> View/Edit Region</a>
																<?php if ($sbr_regions_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_region(<?php echo $row->region_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete Region</a>
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
									$("#chk_regions_0").on('change', function() {
								        if($("#chk_regions_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_region_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_region_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								