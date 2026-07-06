								<script type="text/javascript">
									$('.datatable-basic').DataTable();
								</script>

								<table class="table datatable-basic table-bordered">
									<thead>
										<tr>
											<th style="width: 120px"></th>
											<th>Title</th>
											<th>Author</th>
											<th>Content</th>
											<th>Is Published</th>
											<th>Date</th>
											<th class="text-center">Action</th>
										</tr>
									</thead>
									<tbody>	
										<?php foreach ($blog as $row): ?>
											<tr>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<?php if($row->thumb_image != '' && file_exists("./uploads/blog_article_cover_images/thumbs/" . $row->thumb_image)): ?>
																<img src="<?php echo base_url();?>uploads/blog_article_cover_images/thumbs/<?php echo $row->thumb_image; ?>" class="" width="100" alt="">
						                                    <?php else: ?>
						                                    	<!-- <img src="<?php echo base_url();?>assets/be/images/placeholder.png" class="" width="100" alt=""> -->
						                                    <?php endif; ?>
														</div>
													</div>
												</td>
												<td>
													<div class="media">
														<div class="media-body align-self-center">
															<a href="<?php echo base_url();?>be/blog/edit/<?php echo $row->blog_article_id; ?>" class="font-weight-bold"><?php echo $row->blog_article_title; ?></a>
														</div>
													</div>													
												</td>
 												<td><?php echo $row->blog_article_author; ?></td>
												<td><?php echo character_limiter(strip_tags($row->blog_article_content),100); ?></td>
												<td>
													<?php if ($row->is_published == 1): ?>
														<span class="badge bg-success">Yes</span>
													<?php else: ?>
														<span class="badge bg-danger">No</span>
													<?php endif; ?>
												</td>
												<td><?php echo $row->blog_article_date; ?></td>
												<td class="text-center">
													<div class="list-icons">
														<div class="dropdown">
															<a href="#" class="list-icons-item" data-toggle="dropdown">
																<i class="icon-menu9"></i>
															</a>

															<div class="dropdown-menu dropdown-menu-right">
																<a href="<?php echo base_url();?>be/blog/edit/<?php echo $row->blog_article_id; ?>" class="dropdown-item"><i class="icon-pencil6"></i> View/Edit Blog Article</a>
																<?php if ($sbr_blog_delete == true): ?>
																	<a href="javascript:void(0);" onclick="delete_blog_article(<?php echo $row->blog_article_id; ?>);" class="dropdown-item"><i class="icon-trash-alt"></i> Delete Blog Article</a>
																<?php endif; ?>
															</div>
														</div>
													</div>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>