									<div class="row">
										<?php foreach ($home_sliders as $row): ?>
											<div class="col-sm-6 col-xl-3">
												<div class="card">
													<div class="card-img-actions mx-1 mt-1">
														<?php if($row->home_slider_image_thumb != '' && file_exists("./uploads/home_sliders/thumbs/" . $row->home_slider_image_thumb)): ?>
															<img class="card-img img-fluid" src="<?php echo base_url(); ?>uploads/home_sliders/thumbs/<?php echo $row->home_slider_image_thumb; ?>" alt="">
															<div class="card-img-actions-overlay card-img">
																<a href="<?php echo base_url(); ?>uploads/home_sliders/<?php echo $row->home_slider_image; ?>" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
																	<i class="icon-plus3"></i>
																</a>
															</div>
														<?php else: ?>
															<img class="card-img img-fluid" src="<?php echo base_url();?>assets/be/images/placeholder.png" alt="">
															<div class="card-img-actions-overlay card-img">
																<a href="<?php echo base_url();?>assets/be/images/placeholder.png" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
																	<i class="icon-plus3"></i>
																</a>
															</div>
														<?php endif; ?>
													</div>

													<div class="card-body">
														<div class="d-flex align-items-start flex-nowrap">
															<div>
																<?php if ($row->home_slider_title !== ''): ?>
																	<h6 class="font-weight-bold mr-2"><?php echo $row->home_slider_title; ?></h6>
																<?php endif; ?>
															</div>
															<div class="list-icons list-icons-extended ml-auto">
																<a href="<?php echo base_url(); ?>uploads/home_sliders/<?php echo $row->home_slider_image; ?>" class="list-icons-item" download title="Download Image"><i class="icon-download top-0 text-success"></i></a>
																<?php if ($sbr_home_sliders_edit == true): ?>
																	<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_home_slider" class="list-icons-item" title="Edit Slider Image" onclick="home_slider_edit_load(<?php echo $row->home_slider_id; ?>);"><i class="icon-pencil top-0 text-primary"></i></a>
																<?php endif; ?>
																<?php if ($sbr_home_sliders_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_home_slider(<?php echo $row->home_slider_id; ?>);" class="list-icons-item" title="Remove Slider Image"><i class="icon-bin top-0 text-danger"></i></a>
																<?php endif; ?>
															</div>
														</div>
														<div>
															<?php if ($row->home_slider_description !== ''): ?>
																<div class="mb-2"><b>Description:</b> <span><?php echo $row->home_slider_description; ?></span></div>
															<?php endif; ?>
															<?php if ($row->home_slider_link !== ''): ?>
																<div class="mb-2"><b>Link:</b> <span><i><?php echo $row->home_slider_link; ?></i></span></div>
															<?php endif; ?>

															<span><b>Sort Key:</b> <span class="text-primary"><?php echo $row->sort_key; ?></span></span><br>
															<span><b>Status:</b> 
																<?php if ($row->is_active == 1): ?>
																	<span class="badge bg-success">Active</span>
																<?php else: ?>
																	<span class="badge bg-danger">Inactive</span>
																<?php endif; ?>
															</span>
														</div>
													</div>
												</div>
											</div>
										<?php endforeach; ?>
									</div>


									<script type="text/javascript">
										$('[data-popup="lightbox"]').fancybox({
								            padding: 3
								        });
									</script>
