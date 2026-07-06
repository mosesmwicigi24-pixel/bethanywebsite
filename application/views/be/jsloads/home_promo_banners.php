									<div class="row">
										<?php foreach ($home_promo_banners as $row): ?>
											<div class="col-sm-6 col-xl-3">
												<div class="card">
													<div class="card-img-actions mx-1 mt-1">
														<?php if($row->home_promo_banner_image_thumb != '' && file_exists("./uploads/home_promo_banners/thumbs/" . $row->home_promo_banner_image_thumb)): ?>
															<img class="card-img img-fluid" src="<?php echo base_url(); ?>uploads/home_promo_banners/thumbs/<?php echo $row->home_promo_banner_image_thumb; ?>" alt="">
															<div class="card-img-actions-overlay card-img">
																<a href="<?php echo base_url(); ?>uploads/home_promo_banners/<?php echo $row->home_promo_banner_image; ?>" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
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
															<div class="list-icons list-icons-extended ml-auto">
																<a href="<?php echo base_url(); ?>uploads/home_promo_banners/<?php echo $row->home_promo_banner_image; ?>" class="list-icons-item" download title="Download Image"><i class="icon-download top-0 text-success"></i></a>
																<?php if ($sbr_promo_banners_edit == true): ?>
																	<a href="javascript:void(0);" data-toggle="modal" data-target="#modal_edit_home_promo_banner" class="list-icons-item" title="Edit Promo banner" onclick="home_promo_banner_edit_load(<?php echo $row->home_promo_banner_id; ?>);"><i class="icon-pencil top-0 text-primary"></i></a>
																<?php endif; ?>
																<?php if ($sbr_promo_banners_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_home_promo_banner(<?php echo $row->home_promo_banner_id; ?>);" class="list-icons-item" title="Remove Promo banner"><i class="icon-bin top-0 text-danger"></i></a>
																<?php endif; ?>
															</div>
														</div>
														<div>
															<?php if ($row->home_promo_banner_link !== ''): ?>
																<div class="mb-2"><b>Link:</b> <span><i><?php echo $row->home_promo_banner_link; ?></i></span></div>
															<?php endif; ?>

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
