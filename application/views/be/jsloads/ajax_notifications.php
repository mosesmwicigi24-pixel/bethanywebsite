							<?php if ($num_notifications > 0): ?>
								<ul class="media-list">
									<?php foreach ($notifications as $row): ?>
										<li class="media">
											<div class="mr-3">
												<?php if ($row->notification_type == 'Affiliate Account Creation'): ?>
													<a href="<?php echo base_url() . $row->notification_ref_link; ?>" class="btn bg-secondary rounded-round btn-icon lnk-notification" data-notification-id="<?php echo $row->notification_id; ?>"><i class="icon-user"></i></a>
												<?php elseif ($row->notification_type == 'Customer Account Creation'): ?>
													<a href="<?php echo base_url() . $row->notification_ref_link; ?>" class="btn bg-success-400 rounded-round btn-icon lnk-notification" data-notification-id="<?php echo $row->notification_id; ?>"><i class="icon-user"></i></a>
												<?php elseif ($row->notification_type == 'Online Order Creation'): ?>
													<a href="<?php echo base_url() . $row->notification_ref_link; ?>" class="btn bg-success-400 rounded-round btn-icon lnk-notification" data-notification-id="<?php echo $row->notification_id; ?>"><i class="icon-basket"></i></a>
												<?php elseif ($row->notification_type == 'Online Order Cancellation'): ?>
													<a href="<?php echo base_url() . $row->notification_ref_link; ?>" class="btn bg-danger rounded-round btn-icon lnk-notification" data-notification-id="<?php echo $row->notification_id; ?>"><i class="icon-exclamation"></i></a>
												<?php elseif ($row->notification_type == 'Expense Voided'): ?>
													<a href="<?php echo base_url() . $row->notification_ref_link; ?>" class="btn bg-danger rounded-round btn-icon lnk-notification" data-notification-id="<?php echo $row->notification_id; ?>"><i class="icon-coins"></i></a>
												<?php else: ?>
													<a href="<?php echo base_url() . $row->notification_ref_link; ?>" class="btn bg-primary-400 rounded-round btn-icon lnk-notification" data-notification-id="<?php echo $row->notification_id; ?>"><i class="icon-info3"></i></a>
												<?php endif; ?>
											</div>

											<div class="media-body">
												<?php echo $row->notification_details; ?> <a href="<?php echo base_url() . $row->notification_ref_link; ?>" class="badge badge-pill badge-primary lnk-notification" data-notification-id="<?php echo $row->notification_id; ?>">VIEW <i class="icon-arrow-right15 font-size-sm"></i></a>
												<div class="font-size-sm text-muted mt-1"><?php echo date('d M, Y H:i:s', strtotime($row->created_on)); ?></div>
											</div>
										</li>
									<?php endforeach; ?>
								</ul>
							<?php else: ?>
								<p><i>There are no new notifications</i></p>
							<?php endif; ?>
