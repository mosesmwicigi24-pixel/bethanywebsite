																<div class="row">
																	<?php $numimages = $product_num_images;?>
						                                            <?php $i = 1; ?>
						                                            <?php foreach ($product_images as $poi): ?>
						                                            	<div class="col-md-6">
																			<div class="card">
																				<?php if($poi->image_filename != '' && file_exists("./uploads/product_images/" . $poi->image_filename)): ?>
																					<div class="card-img-actions mx-1 mt-1">
																						<img class="card-img img-fluid" src="<?php echo base_url(); ?>uploads/product_images/<?php echo $poi->image_filename; ?>" alt="">
																						<div class="card-img-actions-overlay card-img">
																							<a href="<?php echo base_url(); ?>uploads/product_images/<?php echo $poi->image_filename; ?>" class="btn btn-outline bg-white text-white border-white border-2 btn-icon rounded-round" data-popup="lightbox" rel="group">
																								<i class="icon-plus3"></i>
																							</a>
																						</div>
																					</div>
																				<?php endif; ?>

																				<div class="card-body">
																					<div class="d-flex align-items-start flex-nowrap">
																						<div class="list-icons list-icons-extended ml-auto">
																							<a href="javascript:;" role="button" class="list-icons-item lnk_edit_product_gallery_image" data-product-image-id ="<?php echo $poi->product_image_id; ?>" title="Edit Image"><i class="icon-pencil top-0"></i></a>
																							<a href="<?php echo base_url(); ?>uploads/product_images/<?php echo $poi->image_filename; ?>" class="list-icons-item" download title="Download Image"><i class="icon-download top-0"></i></a>
																							<a onclick="delete_product_image(<?php echo $poi->product_image_id; ?>);" href="javascript:void(0);" class="list-icons-item" title="Delete Image"><i class="icon-trash top-0"></i></a>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div>

						                                                <?php if($i == 2): ?>
						                                                    <div class="clearfix"></div>
						                                                <?php endif; ?>
						                                                <?php $i++; ?>
						                                            <?php endforeach; ?>
						                                        </div>