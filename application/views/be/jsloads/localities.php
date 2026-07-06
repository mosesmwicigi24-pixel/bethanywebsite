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
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_localities_0" name="chk_locality_id0" value="0"></th>
											<th style="width: 250px">Name</th>
											<th style="width: 80px">Sort Key</th>
											<th style="width: 80px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($localities as $row): ?>
											<tr>
												<td class="text-center"><input type="checkbox" id="chk_localities_<?php echo $row->locality_id; ?>" name="chk_locality_id" value="<?php echo $row->locality_id; ?>"></td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_locality" onclick="locality_edit_load(<?php echo $row->locality_id; ?>);" class="font-weight-bold"><?php echo $row->locality_name; ?></a>
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
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_locality" onclick="locality_edit_load(<?php echo $row->locality_id; ?>);" class="dropdown-item"><i class="icon-pencil6"></i> Edit Locality</a>
																<a href="javascript:void(0);" onclick="delete_locality(<?php echo $row->locality_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete Locality</a>
															</div>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>


								<script type="text/javascript">
									$("#chk_localities_0").on('change', function() {
								        if($("#chk_localities_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_locality_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_locality_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								