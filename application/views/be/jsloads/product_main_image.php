												<?php foreach ($product as $row): ?>
													<?php if($row->product_image != '' && file_exists("./uploads/product_images/" . $row->product_image)): ?>
														<div class="card-img-actions d-inline-block mb-2">
															<img class="card-img img-fluid" src="<?php echo base_url(); ?>uploads/product_images/<?php echo $row->product_image; ?>" alt="">
														</div>
														<div class="form-group mb-2">
															<div class="row">
																<div class="col-sm-12">
																	<button type="button" id="btn_modal_change_product_image" class="btn btn-lg btn-link"><i class="icon-image2 mr-1"></i> Change Product Image</button>
																</div>
															</div>
														</div>
													<?php else: ?>
														<div class="form-group mb-2">
															<div class="row">
																<div class="col-sm-12">
																	<button type="button" id="btn_modal_set_product_image" class="btn btn-lg btn-link"><i class="icon-image2 mr-1"></i> Set Product Image</button>
																</div>
															</div>
														</div>
													<?php endif; ?>
												<?php endforeach; ?>