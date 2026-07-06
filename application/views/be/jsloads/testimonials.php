									<?php if ($num_testimonials > 0): ?>
										<ul class="media-list">
											<?php foreach ($testimonials as $row): ?>
												<li class="media">
													<div class="mr-3">
														<?php if($row->testimonial_image_thumb != '' && file_exists("./uploads/testimonial_images/thumbs/" . $row->testimonial_image_thumb)): ?>
															<img src="<?php echo base_url();?>uploads/testimonial_images/thumbs/<?php echo $row->testimonial_image_thumb; ?>" class="rounded-circle" width="70" alt="">
					                                    <?php else: ?>
					                                    	<img src="<?php echo base_url();?>assets/be/images/avi.png" class="rounded-circle" width="70" alt="">
					                                    <?php endif; ?>
													</div>
													<div class="media-body">
														<div class="media-title font-weight-bold"><?php echo $row->testimonial_name; ?></div>
														<span class="text-muted"><?php echo $row->testimonial_title; ?></span>
													</div>
													<div class="align-self-center ml-3">
														<div class="list-icons list-icons-extended">
									                    	<a href="javascript:void(0);" class="list-icons-item text-primary" data-popup="tooltip" title="View/Edit Testimonial" data-toggle="modal" data-trigger="hover" data-target="#modal_edit_testimonial" onclick="testimonial_edit_load(<?php echo $row->testimonial_id; ?>);"><i class="icon-pencil"></i></a>
									                    	<?php if ($sbr_testimonials_delete == true): ?>
									                    		<a href="javascript:void(0);" onclick="delete_testimonial(<?php echo $row->testimonial_id; ?>);" class="list-icons-item text-danger" data-popup="tooltip" title="Delete Testimonial" data-toggle="modal" data-trigger="hover" data-target="#chat"><i class="icon-bin"></i></a>
									                    	<?php endif; ?>
								                    	</div>
													</div>
												</li>
											<?php endforeach; ?>
										</ul>
									<?php else: ?>
										<div class="alert alert-info alert-styled-left alert-dismissible p-3">
											<span class="font-weight-bold">Heads Up!</span> No Testimonials have been added yet.
									    </div>
									<?php endif; ?>
