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
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_countries_0" name="chk_country_id0" value="0"></th>
											<th style="width: 250px">Name</th>
											<th style="width: 150px">Abbreviation</th>
											<th style="width: 250px">Nationality</th>
											<th style="width: 150px">Country Code</th>
											<th style="width: 90px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($countries as $row): ?>
											<tr>
												<td class="text-center"><input type="checkbox" id="chk_countries_<?php echo $row->country_id; ?>" name="chk_country_id" value="<?php echo $row->country_id; ?>"></td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_country" onclick="country_edit_load(<?php echo $row->country_id; ?>);" class="font-weight-bold"><?php echo $row->country_name; ?></a>
														</div>
													</div>													
												</td>
												<td><?php echo $row->country_abbreviation; ?></td>
												<td><?php echo $row->nationality; ?></td>
												<td><?php echo $row->country_code; ?></td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<?php if ($sbr_countries_manage == true): ?>
																	<a href="<?php echo base_url();?>be/settings/regions/<?php echo $row->country_id; ?>" class="dropdown-item"><i class="icon-list"></i> Manage Regions</a>
																<?php endif; ?>
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_country" onclick="country_edit_load(<?php echo $row->country_id; ?>);" class="dropdown-item"><i class="icon-pencil6"></i> View/Edit Country</a>
																<?php if ($sbr_countries_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_country(<?php echo $row->country_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete Country</a>
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
									$("#chk_countries_0").on('change', function() {
								        if($("#chk_countries_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_country_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_country_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								