		<?php foreach ($affiliate as $row): ?>
			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Page header -->
				<div class="page-header">
					<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
						<div class="d-flex">
							<div class="breadcrumb">
								<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
								<a href="<?php echo base_url();?>be/affiliates" class="breadcrumb-item">Affiliate Accounts</a>
								<span class="breadcrumb-item active">Manage Account</span>
							</div>

							<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
						</div>
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content pt-0">

					<div class="d-md-flex align-items-md-start">

						<div class="sidebar sidebar-light sidebar-component sidebar-component-left bg-transparent border-0 shadow-0 sidebar-expand-md">

							<!-- Sidebar content -->
							<div class="sidebar-content">

								<!-- Sub navigation -->
								<div class="card">
									<div class="card-header bg-transparent header-elements-inline p-2">
										<span class="text-uppercase font-size-sm font-weight-semibold"><i class="icon-cog7"></i> Manage Affiliate Account</span>
										<div class="header-elements">
											<div class="list-icons">
						                		<a class="list-icons-item" data-action="collapse"></a>
					                		</div>
				                		</div>
									</div>

									<div class="card-body p-0">
										<ul class="nav nav-sidebar" data-nav-type="accordion">
											<li class="nav-item">
												<a href="<?php echo base_url();?>be/affiliates/manage/<?php echo $row->affiliate_id; ?>" class="nav-link active"><i class="icon-user"></i> Account Details</a>
											</li>
											<li class="nav-item">
												<a href="<?php echo base_url();?>be/affiliates/account_referrals/<?php echo $row->affiliate_id; ?>" class="nav-link"><i class="icon-cart"></i> Referrals</a>
											</li>
											<li class="nav-item">
												<a href="<?php echo base_url();?>be/affiliates/account_clicks/<?php echo $row->affiliate_id; ?>" class="nav-link"><i class="icon-mouse"></i> Clicks</a>
											</li>
											<li class="nav-item">
												<a href="<?php echo base_url();?>be/affiliates/account_withdrawals/<?php echo $row->affiliate_id; ?>" class="nav-link"><i class="icon-coin-dollar"></i> Withdrawals</a>
											</li>
										</ul>
									</div>
								</div>
								<!-- /sub navigation -->
							</div>
							<!-- /sidebar content -->

						</div>
						<!-- /left sidebar component -->


						<div class="w-100">
							<div class="row">
								<div class="col-md-7">
									<div class="card">
										<div class="card-body">
											<h3><a class="text-dark"><?php echo $row->first_name . ' ' . $row->last_name; ?></a></h3>
											<p><a><b>Affiliate Code :</b> <?php echo '#' . $row->affiliate_code; ?></a>&nbsp;|&nbsp;Joined: <span class="font-weight-bold"><?php echo date('d M, Y', strtotime($row->created_on)); ?></span><!-- &nbsp;|&nbsp;Price: <b><big>KES <?php //echo number_format($row->price,2) ?></big></b> -->&nbsp;|&nbsp;Status:
												<?php if ($row->affiliate_status == 0): ?>
                                                    <a href="#" class="badge badge-info">Pending</a>
                                                <?php elseif ($row->affiliate_status == 1): ?>
                                                    <a href="#" class="badge badge-success">Active</a>
                                                <?php elseif ($row->affiliate_status == 2): ?>
                                                    <a href="#" class="badge badge-danger">Suspended</a>
                                                <?php elseif ($row->affiliate_status == 3): ?>
                                                    <a href="#" class="badge badge-danger">Deleted</a>
                                                <?php endif; ?>
                                                &nbsp;|&nbsp;Verified:
                                                <?php if ($row->is_verified == 1): ?>
                                                    <a href="#" class="badge badge-success">Yes</a>
                                                <?php else: ?>
                                                    <a href="#" class="badge badge-danger">No</a>
                                                <?php endif; ?>
											</p>

											<?php if ($row->affiliate_status != 5): ?>
												<p><a href="javascript:void(0);" data-toggle="modal" data-target="#modal_suspend_listing" class="btn btn-xs btn-danger rounded-round"><i class="icon-warning"></i> Suspend Account</a></p>
											<?php else: ?>
												<div class="alert alert-warning">
													<b>Suspension Reason: </b> <?php echo $row->suspension_reason; ?>
													<br><b>Suspended By: </b> <?php echo $row->system_user_first_name . ' ' . $row->system_user_last_name . ' [' . $row->system_user_email_address . ']'; ?>
												</div>												
											<?php endif; ?>

											<div class="row mt-20">
												<div class="col-md-12">
													<h6 class="text-uppercase font-weight-bold">Account Details</h6>
													<div class="table-responsive">
		                                                <table class="table table-bordered w-100 m-0 text-nowrap">
		                                                    <tbody>
		                                                        <tr>
		                                                            <td><span class="font-weight-bold">First Name :</span> <?php echo $row->first_name; ?></td>
		                                                            <td><span class="font-weight-bold">Last Name :</span> <?php echo $row->last_name; ?></td>
		                                                        </tr>
		                                                        <tr>
		                                                            <td><span class="font-weight-bold">Email Address :</span> <?php echo $row->email_address; ?></td>
		                                                            <td><span class="font-weight-bold">Phone Number :</span> <?php echo $row->phone_number; ?></td>
		                                                        </tr>
		                                                        <tr>
		                                                            <td><span class="font-weight-bold">Physical Address :</span> <?php echo $row->physical_address; ?></td>
		                                                            <td><span class="font-weight-bold">Town/Country :</span> <?php echo $row->town . ', ' . $row->country_name; ?></td>
		                                                        </tr>
		                                                        <tr>
		                                                            <td><span class="font-weight-bold">Company :</span> <?php echo $row->company_name; ?></td>
		                                                            <td><span class="font-weight-bold">Website :</span> <?php echo $row->website; ?></td>
		                                                        </tr>
		                                                    </tbody>
		                                                </table>
		                                            </div>
												</div>
											</div>

											<?php if ($row->short_url != ''): ?>
												<div class="row mt-40">
													<div class="col-md-12">
														<p><big><i><i class="icon-link2"></i> Affiliate URL Link:</i> <a href="<?php echo $row->short_url; ?>" target="_blank"><b><?php echo $row->short_url; ?></b></a></big></p>
													</div>
												</div>
											<?php endif; ?>

											<div class="row mt-20">
												<div class="col-md-12">
													<h6 class="text-uppercase font-weight-bold">Package Information</h6>
													<div class="row">
														<?php if ($row->affiliate_package_id == 0): ?>
															<?php if ($row->affiliate_status != 2 && $row->affiliate_status != 2): ?>
																<?php if ($row->affiliate_status == 0): ?>
																	<p><a href="javascript:void(0);" data-toggle="modal" data-target="#modal_approve_assign_package" class="btn btn-sm btn-success btn-labeled btn-labeled-left rounded-round"><b><i class="icon-checkmark-circle"></i></b>Approve Account &amp; Assign Package</a></p>
																<?php else: ?>
																	<p><a href="javascript:void(0);" data-toggle="modal" data-target="#modal_assign_package" class="btn btn-sm btn-success btn-labeled btn-labeled-left rounded-round"><b><i class="icon-checkmark-circle"></i></b>Assign Package</a></p>
																<?php endif; ?>
															<?php endif; ?>
														<?php else: ?>
															<div class="col-md-6">
																<?php if ($row->affiliate_status != 2 && $row->affiliate_status !=2): ?>
																	<p><a href="javascript:void(0);" data-toggle="modal" data-target="#modal_assign_package" class="btn btn-sm btn-success btn-labeled btn-labeled-left rounded-round"><b><i class="icon-checkmark-circle"></i></b>Change Package</a></p>
																<?php endif; ?>
					                                            <div class="table-responsive">
					                                                <table class="table table-bordered border-top mb-0">
					                                                    <tbody>
				                                                    		<tr>
					                                                            <td><b>Package</b></td>
					                                                            <td><span style="color: <?php echo $row->affiliate_package_colour_code; ?>" class="icon-star-full2"></span> <?php echo $row->affiliate_package_name; ?></td>
					                                                        </tr>
					                                                    </tbody>
					                                                </table>
					                                            </div>
					                                        </div>
														<?php endif; ?>
				                                    </div>
												</div>
											</div>



										</div>
									</div>

								</div>	


								<div class="col-md-5">
									<div class="card">
										<div class="card-body">
											<div class="row">
												<div class="col-sm-6 col-xl-6">
													<div class="card card-body bg-warning-400 has-bg-image">
														<div class="media">
															<div class="media-body">
																<h3 class="mb-0"><?php echo number_format($total_clicks,0); ?></h3>
																<span class="text-uppercase font-size-xs">Clicks</span>
															</div>

															<div class="ml-3 align-self-center">
																<i class="icon-mouse icon-3x opacity-75"></i>
															</div>
														</div>
													</div>
												</div>

												<div class="col-sm-6 col-xl-6">
													<div class="card card-body bg-success-400 has-bg-image">
														<div class="media">
															<div class="media-body">
																<h3 class="mb-0"><?php echo number_format($total_referrals,0); ?></h3>
																<span class="text-uppercase font-size-xs">Conversions</span>
															</div>

															<div class="ml-3 align-self-center">
																<i class="icon-bag icon-3x opacity-75"></i>
															</div>
														</div>
													</div>
												</div>

												<div class="col-sm-6 col-xl-6">
													<?php if ($total_clicks <= 0){ $conversion_rate = 0; } else { $conversion_rate = (($total_referrals/$total_clicks) * 100); } ?>
													<div class="card card-body bg-info-400 has-bg-image">
														<div class="media">
															<div class="mr-3 align-self-center">
																<i class="icon-chart icon-3x opacity-75"></i>
															</div>

															<div class="media-body text-right">
																<h3 class="mb-0"><?php echo number_format($conversion_rate,0); ?>%</h3>
																<span class="text-uppercase font-size-xs">Convesion Rate</span>
															</div>
														</div>
													</div>
												</div>

											</div>

											<div class="row">
			                                    <div class="col-md-12 mb-3">
			                                        <table class="table table-bordered table-rounded">
			                                            <tbody>
			                                                <tr>
			                                                    <td class="text-right">Affiliate Package</td>
			                                                    <td><strong><?php echo $row->affiliate_package_name; ?></strong></td>
			                                                </tr>
			                                                <tr>
			                                                    <td class="text-right">Commission</td>
			                                                    <td><strong><?php echo number_format($row->commission); ?>%</strong></td>
			                                                </tr>
			                                                <tr>
			                                                    <td class="text-right">Minimum Withdrawal:</td>
			                                                    <td><strong><?php echo $default_currency; ?> <?php echo number_format($row->minimum_pay_out,2); ?></strong></td>
			                                                </tr>
			                                            </tbody>
			                                        </table>
			                                    </div>
			                                    <div class="col-md-12">
			                                        <table class="table table-bordered table-rounded font-size-lg">
			                                            <tbody>
			                                                <tr>
			                                                    <td class="text-right">Commissions Balance:</td>
			                                                    <td><strong><?php echo $default_currency; ?> <?php echo number_format($row->commissions_balance,2); ?></strong></td>
			                                                </tr>
			                                                <tr>
			                                                    <td class="text-right">Total Commissions:</td>
			                                                    <td><strong><?php echo $default_currency; ?> <?php echo number_format($row->total_commissions,2); ?></strong></td>
			                                                </tr>
			                                                <tr>
			                                                    <td class="text-right">Total Amount Withdrawn:</td>
			                                                    <td><strong><?php echo $default_currency; ?> <?php echo number_format($row->withdrawn_commissions,2); ?></strong></td>
			                                                </tr>
			                                            </tbody>
			                                        </table>
			                                    </div>
			                                </div>


										</div>
									</div>
								</div>

							</div>
						</div>
					</div>
				</div>

				<div id="modal_suspend_listing" class="modal fade">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title text-primary"><i class="icon-warning"></i> Suspend Listing</h5>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>

							<form id="frm_suspend_listing" name="frm_suspend_listing" method="post" onsubmit="return suspend_listing();">
								<div class="modal-body">

									<div id="div_edit_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_edit_success" class="alert alert-success display-none font-13"></div>

	                   				<input id="affiliate_id" name="affiliate_id" type="hidden" placeholder="" class="form-control" value="<?php echo $row->affiliate_id; ?>">

									<div class="row">
										<div class="col-md-12">
											<div class="form-group mb-3">
												<label>Suspension Reason*</label>
												<textarea id="suspension_reason" name="suspension_reason" rows="4" cols="3" class="form-control"></textarea>
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">								
									<button type="submit" id="btn_suspend_listing" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> SUBMIT</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div id="modal_approve_assign_package" class="modal fade">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title text-success"><i class="icon-checkmark-circle"></i> Approve Account &amp; Assign Package</h5>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>

							<form id="frm_approve_affiliate" name="frm_approve_affiliate" method="post" onsubmit="return approve_affiliate();">
								<div class="modal-body">

									<div id="div_approve_affiliate_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_approve_affiliate_success" class="alert alert-success display-none font-13"></div>

	                   				<input id="approve_affiliate_id" name="affiliate_id" type="hidden" placeholder="" class="form-control" value="<?php echo $row->affiliate_id; ?>">

									<div class="row">
										<div class="col-md-12">
											<div class="form-group mb-3">
												<label>Select Package <span class="error">*</span></label>
												<select id="approve_affiliate_package_id" name="affiliate_package_id" class="form-control form-control-select2" data-placeholder="Select Package" data-fouc>
													<option value="">Select Package</option>
													<?php foreach ($affiliate_packages as $row2): ?>
														<option value="<?php echo $row2->affiliate_package_id; ?>"><?php echo $row2->affiliate_package_name; ?></option>
													<?php endforeach; ?>
												</select>
												<div id="approve_affiliate_package_details_loader" class="display-none">
													<i class="icon-spinner2 spinner text-success"></i>
												</div>
											</div>
											<div id="div_approve_affiliate_package_details" class="bg-primary-300 display-none">
												
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">								
									<button type="submit" id="btn_approve_affiliate" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> APPROVE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
								</div>
							</form>
						</div>
					</div>
				</div>

				<div id="modal_assign_package" class="modal fade">
					<div class="modal-dialog modal-sm">
						<div class="modal-content">
							<div class="modal-header">
								<h5 class="modal-title text-success"><i class="icon-checkmark-circle"></i> Assign Package</h5>
								<button type="button" class="close" data-dismiss="modal">&times;</button>
							</div>

							<form id="frm_assign_affiliate" name="frm_assign_affiliate" method="post" onsubmit="return assign_affiliate();">
								<div class="modal-body">

									<div id="div_assign_affiliate_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_assign_affiliate_success" class="alert alert-success display-none font-13"></div>

	                   				<input id="assign_affiliate_id" name="affiliate_id" type="hidden" placeholder="" class="form-control" value="<?php echo $row->affiliate_id; ?>">

									<div class="row">
										<div class="col-md-12">
											<div class="form-group mb-3">
												<label>Select Package <span class="error">*</span></label>
												<select id="assign_affiliate_package_id" name="affiliate_package_id" class="form-control form-control-select2" data-placeholder="Select Package" data-fouc>
													<option value="">Select Package</option>
													<?php foreach ($affiliate_packages as $row2): ?>
														<option value="<?php echo $row2->affiliate_package_id; ?>"><?php echo $row2->affiliate_package_name; ?></option>
													<?php endforeach; ?>
												</select>
												<div id="assign_affiliate_package_details_loader" class="display-none">
													<i class="icon-spinner2 spinner text-success"></i>
												</div>
											</div>
											<div id="div_assign_affiliate_package_details" class="bg-primary-300 display-none">
												
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">								
									<button type="submit" id="btn_assign_affiliate" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> ASSIGN PACKAGE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
								</div>
							</form>
						</div>
					</div>
				</div>

			<?php endforeach; ?>
					



			