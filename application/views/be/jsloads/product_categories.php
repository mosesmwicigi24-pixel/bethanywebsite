								<script type="text/javascript">
									$('.datatable-basic').DataTable({
										iDisplayLength: 50,
										lengthMenu: [50, 100, 150, 200 ],										
									    "columnDefs": [
									        { "orderable": false, "targets": [ 0, 1, 5] }
									    ]
									});
									//"order": [[ 4, "asc" ]],
								</script>

								<?php $level_count = 0; ?>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th class="text-center" style="width: 50px"><input type="checkbox" id="chk_product_categories_0" name="chk_product_category_id0" value="0"></th>
											<th style="width: 120px" class="text-center">Cover Image</th>
											<th style="width: 250px">Name</th>
											<th style="width: 80px" class="text-center">Icon</th>
											<th style="width: 80px" class="text-center">Sort Key</th>
											<th style="width: 80px" class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($product_categories as $row): ?>
											<tr>
												<td class="text-center"><input type="checkbox" id="chk_product_categories_<?php echo $row->product_category_id; ?>" name="chk_product_category_id" value="<?php echo $row->product_category_id; ?>"></td>
												<td class="text-center">
													<div class="media">
														<div class="media-body align-self-center">
															<?php if($row->thumb_image != '' && file_exists("./uploads/product_category_cover_images/thumbs/" . $row->thumb_image)): ?>
																<img src="<?php echo base_url();?>uploads/product_category_cover_images/thumbs/<?php echo $row->thumb_image; ?>" class="" width="100" alt="">
						                                    <?php else: ?>
						                                    	&mdash;
						                                    <?php endif; ?>
														</div>
													</div>
												</td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<?php if ($sbr_product_categories_edit == true): ?>
																<a href="<?php echo base_url();?>be/product_categories/edit/<?php echo $row->product_category_id; ?>" class="font-weight-bold"><?php echo $row->product_category_name; ?></a>
															<?php else: ?>
																<span class="font-weight-bold"><?php echo $row->product_category_name; ?></span>
															<?php endif; ?>
														</div>
													</div>													
												</td>
												<td class="text-center">
													<?php if ($row->icon_name != '' && $row->icon_id != 0): ?>
														<span class="badge badge-flat badge-icon border-secondary text-secondary-600 rounded-circle"><i class="icon-<?php echo $row->icon_name; ?>"></i></span>
													<?php else: ?>
														&mdash;
													<?php endif; ?>
												</td>
												<td class="text-center"><?php echo $row->sort_key; ?></td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<?php if ($sbr_product_categories_edit == true): ?>
																	<a href="<?php echo base_url();?>be/product_categories/edit/<?php echo $row->product_category_id; ?>" class="dropdown-item"><i class="icon-pencil6"></i> View/Edit</a>
																<?php endif; ?>
																<?php if ($sbr_product_categories_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_product_category(<?php echo $row->product_category_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete</a>
																<?php endif; ?>
															</div>
														</div>
													</div>
												</td>
											</tr>

											<?php
												if(!empty($row->sub)){
													fetch_sub_categories($row->sub, $level_count, $sbr_product_categories_edit, $sbr_product_categories_delete);
												}
											?>

										<?php endforeach; ?>

										<?php
											function fetch_sub_categories($sub_categories, $level_count, $sbr_product_categories_edit, $sbr_product_categories_delete){
												$level_count = $level_count + 1;
												foreach($sub_categories as $sub_category){
													$mdash = '';
													$mspace = '';
													$icon = '';
													if ($sub_category->icon_name != '' && $sub_category->icon_id != 0) {
														$icon = '<span class="badge badge-flat badge-icon border-secondary text-secondary-600 rounded-circle"><i class="icon-' . $sub_category->icon_name . '"></i></span>';
													}else{
														$icon = '&mdash;';
													}
													for($i = 0; $i < $level_count; $i++){$mdash = $mdash . '&mdash;'; $mspace = $mspace . '&nbsp;&nbsp;';}
													echo '<tr>
															<td class="text-center"><input type="checkbox" id="chk_product_categories_' . $sub_category->product_category_id .'" name="chk_product_category_id" value="' . $sub_category->product_category_id .'"></td>
															<td class="text-center">
																<div class="media">
																	<div class="media-body align-self-center">';
																		if($sub_category->thumb_image != '' && file_exists("./uploads/product_category_cover_images/thumbs/" . $sub_category->thumb_image)) {
																			echo '<img src="' . base_url() . 'uploads/product_category_cover_images/thumbs/' . $sub_category->thumb_image . '" class="" width="100" alt="">';
																		}else{
																			echo '&mdash;';
																		}
																	echo '</div>
																</div>
															</td>
															<td>
																<div class="media">
																	<div class="media-body align-self-center">';
																		if ($sbr_product_categories_edit == true){
																			echo '<a href="' . base_url() . 'be/product_categories/edit/' . $sub_category->product_category_id .'" class="font-weight-bold">' . $mspace . $mdash . ' ' . $sub_category->product_category_name .'</a>';
																		} else {
																			echo '<span class="font-weight-bold">' . $mspace . $mdash . ' ' . $sub_category->product_category_name .'</span>';
																		}																		
																	echo '</div>
																</div>													
															</td>
															<td class="text-center">' . $icon .'</td>
															<td class="text-center">' . $sub_category->sort_key .'</td>
															<td class="text-center">
																<div class="list-icons">
																	<div class="dropdown">
																		<a href="#" class="list-icons-item" data-toggle="dropdown">
																			<i class="icon-menu9"></i>
																		</a>

																		<div class="dropdown-menu dropdown-menu-right">';
																			if ($sbr_product_categories_edit == true){
																				echo '<a href="' . base_url() . 'be/product_categories/edit/' . $sub_category->product_category_id .'" class="dropdown-item"><i class="icon-pencil6"></i> View/Edit</a>';
																			}
																			if ($sbr_product_categories_delete == true){
																				echo '<a href="javascript:void(0);" onclick="delete_product_category(' . $sub_category->product_category_id .');" class="dropdown-item"><i class="icon-trash-alt"></i> Delete</a>';
																			}
																		echo '</div>
																	</div>
																</div>
															</td>
														</tr>';
													
													if(!empty($sub_category->sub)){
														fetch_sub_categories($sub_category->sub, $level_count, $sbr_product_categories_edit, $sbr_product_categories_delete);
													}
												}
											}
										?>
									</tbody>
								</table>


								<script type="text/javascript">
									$("#chk_product_categories_0").on('change', function() {
								        if($("#chk_product_categories_0"). prop("checked") == true){
								            var i;
								            var chks = document.getElementsByName("chk_product_category_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = true;
											     }
											}
								        }else {
								            var i;
								            var chks = document.getElementsByName("chk_product_category_id");
								            for (i = 0; i <  chks.length; i++) {
											    if(chks[i].type == 'checkbox'){
											    	chks[i].checked = false;
											     }
											}
								        }
								    });
								</script>


								