		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<!-- <a href="#" class="breadcrumb-item">Settings</a> -->
							<span class="breadcrumb-item active">My Profile</span>
						</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content pt-0">

				<div class="row">
					<div class="col-lg-7">
						<div class="card rounded-top-0">
							<div class="card-header header-elements-inline">
								<h5 class="card-title"><i class="icon-user"></i> My Profile</h5>
							</div>
							<div class="card-body">
								<ul class="nav nav-tabs nav-tabs-bottom">
									<li class="nav-item"><a href="#bottom-tab1" class="nav-link active" data-toggle="tab">Profile Information</a></li>
									<li class="nav-item"><a href="#bottom-tab2" class="nav-link" data-toggle="tab">Change Password</a></li>
								</ul>

								<div class="tab-content">
									<div class="tab-pane fade show active" id="bottom-tab1">
										<?php foreach ($profile as $row): ?>
											<div class="card border-left-3 border-left-warning rounded-left-0">
												<div class="card-body">
													<div class="d-sm-flex align-item-sm-center flex-sm-nowrap">
														<div>
															<h5 class="font-weight-bold"><?php echo $row->first_name . ' ' . $row->last_name; ?></h5>
															<ul class="list list-unstyled mb-0">
																<li><span class="font-weight-bold">Email:</span> <?php echo $row->email_address; ?></li>
																<li><span class="font-weight-bold">Phone:</span> <?php echo $row->phone_number; ?></li>
																<li><span class="font-weight-bold">Gender:</span> <?php if ($row->gender != ''){echo $row->gender;}else{echo 'N/A';}; ?></li>
															</ul>
														</div>

														<div class="text-sm-right mb-0 mt-3 mt-sm-0 ml-auto">
															<h6 class="font-weight-semibold">&nbsp;</h6>
															<ul class="list list-unstyled mb-0">
																<li><span class="font-weight-bold">User Role:</span> <?php if ($row->is_super_admin == 1){ echo 'Super Admin'; } else { echo $row->user_role_name; } ?></li>
																<li><span class="font-weight-bold">Address:</span> <?php echo $row->address; ?></li>
																<li class="dropdown">
																	Status: &nbsp;
																	<?php if ($row->is_active == 0): ?>
																		<a href="#" class="badge bg-danger-400 align-top">Inactive</a>
																	<?php else: ?>
																		<a href="#" class="badge bg-success-400 align-top">Active</a>
																	<?php endif; ?>																	
																</li>
															</ul>
														</div>
													</div>
												</div>								
											</div>										
											<form id="frm_edit_profile" name="frm_edit_profile" method="post" onsubmit="return update_profile();">

												<legend class="text-uppercase font-size-sm font-weight-bold">Edit Profile</legend>

												<div id="div_edit_profile_error" class="alert alert-danger display-none font-13"></div>
				                   				<div id="div_edit_profile_success" class="alert alert-success display-none font-13"></div>

				                   				<input id="edit_profile_id" name="profile_id" type="hidden" placeholder="" class="form-control">

				                   				<div class="form-group mb-2">
													<div class="row">
														<div class="col-sm-6">
															<label>First Name <span class="error">*</span></label>
															<input id="first_name" name="first_name" type="text" placeholder="" class="form-control" value="<?php echo $row->first_name; ?>">
														</div>
														<div class="col-sm-6">
															<label>Last Name <span class="error">*</span></label>
															<input id="last_name" name="last_name" type="text" placeholder="" class="form-control" value="<?php echo $row->last_name; ?>">
														</div>
													</div>
												</div>
												<div class="form-group mb-2">
													<div class="row">
														<div class="col-sm-6">
															<label>Email Address <span class="error">*</span></label>
															<input id="email_address" name="email_address" type="email" placeholder="" class="form-control" value="<?php echo $row->email_address; ?>">
														</div>
														<div class="col-sm-6">
															<label>Phone Number <span class="error">*</span></label>
															<input id="phone_number" name="phone_number" type="text" placeholder="" class="form-control" value="<?php echo $row->phone_number; ?>">
														</div>
													</div>
												</div>
												<div class="form-group mb-3 mb-md-2">
													<div class="row">										
														<div class="col-sm-6">
															<label>Gender</label>
															<select id="gender" name="gender" class="form-control form-control-select2" data-fouc>
																<option value="">Select Gender</option>
																<option value="Female" <?php if ($row->gender == 'Female'){echo 'selected';} ?>>Female</option>
																<option value="Male" <?php if ($row->gender == 'Male'){echo 'selected';} ?>>Male</option>
																<option value="Other" <?php if ($row->gender == 'Other'){echo 'selected';} ?>>Other</option>
															</select>
														</div>
														<div class="col-sm-6">
															<label>Address</label>
															<input id="address" name="address" type="text" placeholder="" class="form-control" value="<?php echo $row->address; ?>">
														</div>										
													</div>
												</div>
												<hr>
												<div class="form-group mb-3 mb-md-2">
													<div class="row">										
														<div class="col-sm-12 text-right">
															<button type="submit" id="btn_edit_profile" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE PROFILE</button>
														</div>
													</div>
												</div>
											</form>
										<?php endforeach; ?>
									</div>


									<div class="tab-pane fade" id="bottom-tab2">
										<div class="row">
											<div class="col-lg-9">
												<form id="frm_profile_change_password" name="frm_profile_change_password" method="post" onsubmit="return profile_change_password();" autocomplete="off">
													<div class="modal-body">

														<div id="div_profile_change_password_error" class="alert alert-danger display-none font-13"></div>
						                   				<div id="div_profile_change_password_success" class="alert alert-success display-none font-13"></div>

						                   				<input id="edit_profile_change_password_id" name="system_user_id" type="hidden" placeholder="" class="form-control">

						                   				<div class="form-group mb-2">
															<div class="row">
																<div class="col-sm-12">
																	<label>Old Password <span class="error">*</span></label>
																	<input id="old_password" name="old_password" type="password" placeholder="" class="form-control" autocomplete="off">
																</div>
															</div>
														</div>
														<div class="form-group mb-2">
															<div class="row">
																<div class="col-sm-12">
																	<label>New Password <span class="error">*</span></label>
																	<input id="new_password" name="new_password" type="password" placeholder="" class="form-control" autocomplete="off">
																</div>
															</div>
														</div>
														<div class="form-group mb-2">
															<div class="row">
																<div class="col-sm-12">
																	<label>Confirm Password <span class="error">*</span></label>
																	<input id="confirm_password" name="confirm_password" type="password" placeholder="" class="form-control" autocomplete="off">
																</div>
															</div>
														</div>
													</div>
													<div class="modal-footer">								
														<button type="submit" id="btn_profile_change_password" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> CHANGE PASSWORD</button>
													</div>
												</form>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="col-md-6">

					</div>
				</div>
			</div>
			<!-- /content area -->