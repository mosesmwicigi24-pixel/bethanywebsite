								
								<script type="text/javascript">
									var groupColumn = 1;
									$('.datatable-basic').DataTable({
										//iDisplayLength: 50,
										//lengthMenu: [50, 100, 150, 200 ],
										//"order": [[ 3, "desc" ]],
									    //"columnDefs": [
									        //{ "orderable": false, "targets": [ 0, 4, 5 ] }
									    //]
									    "columnDefs": [
								            { "visible": false, "targets": groupColumn },
								            { "orderable": false, "targets": [ 0, 5, 6 ] }
								        ],
								        "order": [[ groupColumn, 'asc' ]],
								        "displayLength": 50,
								        "drawCallback": function ( settings ) {
								            var api = this.api();
								            var rows = api.rows( {page:'current'} ).nodes();
								            var last=null;
								 
								            api.column(groupColumn, {page:'current'} ).data().each( function ( group, i ) {
								                if ( last !== group ) {
								                    $(rows).eq( i ).before(
								                        '<tr class="group"><td colspan="6">'+group+'</td></tr>'
								                    );
								 
								                    last = group;
								                }
								            } );
								        }
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_units_0" name="chk_unit_id0" value="0"></th>
											<th style="width: 120px">Unit Type</th>
											<th style="width: 180px">Name</th>
											<th style="width: 120px">Code</th>
											<th style="width: 90px">Sort Key</th>
											<th style="width: 90px">Status</th>
											<th style="width: 90px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($units as $row): ?>
											<tr>
												<td class="text-center"><input type="checkbox" id="chk_units_<?php echo $row->unit_id; ?>" name="chk_unit_id" value="<?php echo $row->unit_id; ?>"></td>
												<td><?php echo $row->unit_type_name; ?> <?php if ($row->unit_type_description !== ''){ echo '(' . $row->unit_type_description . ')'; } ?></td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_unit" onclick="unit_edit_load(<?php echo $row->unit_id; ?>);" class="font-weight-bold"><?php echo $row->unit_name; ?> </a>
														</div>
													</div>													
												</td>
												<td><?php echo $row->unit_code; ?></td>
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
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_unit" onclick="unit_edit_load(<?php echo $row->unit_id; ?>);" class="dropdown-item"><i class="icon-pencil6"></i> View/Edit Unit</a>
																<?php if ($sbr_units_of_measure_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_unit(<?php echo $row->unit_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete Unit</a>
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
									$("#chk_units_0").on('change', function() {
								        if($("#chk_units_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_unit_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_unit_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								