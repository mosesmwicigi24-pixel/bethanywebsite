								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],
										"order": [[ 3, "asc" ]],
									    "columnDefs": [
									        { "orderable": false, "targets": [ 0, 1, 4] }
									    ]
									});
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_business_categories_0" name="chk_business_category_id0" value="0"></th>
											<th style="width: 150px" class="text-center">Cover Image</th>
											<th>Name</th>
											<th style="width: 120px" class="text-center">Sort Key</th>
											<th style="width: 120px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($business_categories as $row): ?>
											<tr>
												<td class="text-center"><input type="checkbox" id="chk_business_categories_<?php echo $row->business_category_id; ?>" name="chk_business_category_id" value="<?php echo $row->business_category_id; ?>"></td>
												<td class="text-center">
													<div class="media">
														<div class="media-body align-self-center">
															<?php if($row->thumb_image != '' && file_exists("./uploads/business_category_cover_images/thumbs/" . $row->thumb_image)): ?>
																<img src="<?php echo base_url();?>uploads/business_category_cover_images/thumbs/<?php echo $row->thumb_image; ?>" class="" width="100" alt="">
						                                    <?php else: ?>
						                                    	&mdash;
						                                    	<!-- <img src="<?php echo base_url();?>assets/be/images/placeholder.png" class="" width="100" alt=""> -->
						                                    <?php endif; ?>
														</div>
													</div>
												</td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="<?php echo base_url();?>be/business_categories/edit/<?php echo $row->business_category_id; ?>" class="font-weight-bold"><?php echo $row->business_category_name; ?></a>
														</div>
													</div>													
												</td>
												<td class="text-center"><?php echo $row->sort_key; ?></td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<a href="<?php echo base_url();?>be/business_categories/edit/<?php echo $row->business_category_id; ?>" class="dropdown-item"><i class="icon-pencil6"></i> Edit</a>
																<a href="javascript:void(0);" onclick="delete_business_category(<?php echo $row->business_category_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete</a>
															</div>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>


								<script type="text/javascript">
									$("#chk_business_categories_0").on('change', function() {
								        if($("#chk_business_categories_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_business_category_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_business_category_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								