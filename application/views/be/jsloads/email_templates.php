								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 1, "asc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 0, 2] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_email_templates_0" name="chk_email_template_id0" value="0"></th>
											<th style="width: 200px">Template Name</th>
											<th style="width: 70px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($email_templates as $row): ?>
											<tr>
												<td class="text-center"><input type="checkbox" id="chk_email_templates_<?php echo $row->email_template_id; ?>" name="chk_email_template_id" value="<?php echo $row->email_template_id; ?>"></td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_email_template" onclick="email_template_edit_load(<?php echo $row->email_template_id; ?>);" class="font-weight-bold"><?php echo $row->email_template_name; ?></a>
														</div>
													</div>													
												</td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_email_template" onclick="email_template_edit_load(<?php echo $row->email_template_id; ?>);" class="dropdown-item"><i class="icon-pencil6"></i> Edit Email Template</a>
																<?php if ($sbr_email_templates_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_email_template(<?php echo $row->email_template_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete Email Template</a>
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
									$("#chk_email_templates_0").on('change', function() {
								        if($("#chk_email_templates_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_email_template_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_email_template_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								