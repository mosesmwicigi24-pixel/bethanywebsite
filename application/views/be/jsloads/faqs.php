								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										"order": [[ 2, "asc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 3 ] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th style="width: 180px">Heading (Question)</th>
											<th style="width: 250px">Description</th>
											<th style="width: 80px" class="text-center">Sort Key</th>
											<th style="width: 80px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($faqs as $row): ?>
											<tr>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_faq" onclick="faq_edit_load(<?php echo $row->faq_id; ?>);" class="font-weight-bold"><?php echo $row->faq_heading ?></a>
														</div>
													</div>
												</td>
												<td><?php echo character_limiter(strip_tags($row->faq_description),100); ?></td>
												<td class="text-center"><?php echo $row->sort_key; ?></td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_faq" class="dropdown-item" onclick="faq_edit_load(<?php echo $row->faq_id; ?>);"><i class="icon-pencil6"></i> View/Edit FAQ</a>	
																<?php if ($sbr_faqs_delete == true): ?>															
																	<a href="javascript:void(0);" onclick="delete_faq(<?php echo $row->faq_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete FAQ</a>
																<?php endif; ?>
															</div>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>