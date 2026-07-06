									<?php if ($num_team_members > 0): ?>
										<ul class="media-list">
											<?php foreach ($team_members as $row): ?>
												<li class="media">
													<div class="mr-3">
														<?php if($row->team_member_image_thumb != '' && file_exists("./uploads/team_member_images/thumbs/" . $row->team_member_image_thumb)): ?>
															<img src="<?php echo base_url();?>uploads/team_member_images/thumbs/<?php echo $row->team_member_image_thumb; ?>" class="rounded-circle" width="70" alt="">
					                                    <?php else: ?>
					                                    	<img src="<?php echo base_url();?>assets/be/images/placeholder.png" class="rounded-circle" width="70" alt="">
					                                    <?php endif; ?>
													</div>
													<div class="media-body">
														<div class="media-title font-weight-bold"><?php echo $row->team_member_name; ?></div>
														<span class="text-muted"><?php echo $row->team_member_title; ?></span>
													</div>
													<div class="align-self-center ml-3">
														<div class="list-icons list-icons-extended">
									                    	<a href="javascript:void(0);" class="list-icons-item text-primary" data-popup="tooltip" title="Edit Team Member" data-toggle="modal" data-trigger="hover" data-target="#modal_edit_team_member" onclick="team_member_edit_load(<?php echo $row->team_member_id; ?>);"><i class="icon-pencil"></i></a>
									                    	<a href="javascript:void(0);" onclick="delete_team_member(<?php echo $row->team_member_id; ?>);" class="list-icons-item text-danger" data-popup="tooltip" title="Delete Team Member" data-toggle="modal" data-trigger="hover" data-target="#chat"><i class="icon-bin"></i></a>
								                    	</div>
													</div>
												</li>
											<?php endforeach; ?>
										</ul>
									<?php else: ?>
										<div class="alert alert-info alert-styled-left alert-dismissible p-3">
											<span class="font-weight-bold">Heads Up!</span> No team members have been added yet.
									    </div>
									<?php endif; ?>
