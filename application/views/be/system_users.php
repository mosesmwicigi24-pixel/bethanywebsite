		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<!-- <a href="#" class="breadcrumb-item">Store Setup</a> -->
							<span class="breadcrumb-item active">System Users</span>
						</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content pt-0">
				<div class="row">
					<div class="col-md-12">
						<div class="card rounded-top-0">
							<div class="card-header bg-transparent header-elements-inline p-2">
								<h6 class="card-title font-weight-600">System Users</h6>			
								<div class="header-elements">
									<?php if ($sbr_system_users_add == true): ?>
										<button type="button" data-toggle="modal" data-target="#modal_add_system_user" class="btn btn-sm btn-primary" onclick="system_user_add_clear();" ><i class="icon-plus-circle2"></i> Add System User</button>
									<?php endif; ?>
								</div>			
							</div>

							<div id="system_users_div" style="min-height: 400px;">
								
							</div>
						</div>
					</div>
				</div>
			</div>


			<!-- Add modal form -->
			<div id="modal_add_system_user" class="modal fade">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-plus-circle2"></i> New System User</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_add_system_user" name="frm_add_system_user" method="post" onsubmit="return save_system_user();">
							<fieldset <?php if ($sbr_system_users_add == false){ echo 'disabled'; } ?>>
								<div class="modal-body">

									<div id="div_add_system_user_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_add_system_user_success" class="alert alert-success display-none font-13"></div>

	                   				<div class="row">
	                   					<div class="col-md-8">
	                   						<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-6">
														<label>First Name <span class="error">*</span></label>
														<input id="add_first_name" name="first_name" type="text" placeholder="" class="form-control">
													</div>
													<div class="col-sm-6">
														<label>Last Name <span class="error">*</span></label>
														<input id="add_last_name" name="last_name" type="text" placeholder="" class="form-control">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-6">
														<label>Email Address <span class="error">*</span></label>
														<input id="add_email_address" name="email_address" type="email" placeholder="" class="form-control">
													</div>
													<div class="col-sm-6">
														<label>User Role <span class="error">*</span></label>
														<select id="add_user_role_id" name="user_role_id" class="form-control form-control-select2" data-placeholder="Select User Role" data-fouc>
															<option value="">Select User Role</option>
															<?php foreach ($user_roles as $row): ?>
																<option value="<?php echo $row->user_role_id; ?>"><?php echo $row->user_role_name; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">										
													<div class="col-sm-6">
														<label>Phone Number <span class="error">*</span></label>
														<input id="add_phone_number" name="phone_number" type="text" placeholder="" class="form-control">
													</div>
													<div class="col-sm-6">
														<label>Address</label>
														<input id="add_address" name="address" type="text" placeholder="" class="form-control">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-6">
														<label>Password <span class="error">*</span></label>
														<input id="add_user_password" name="user_password" type="password" placeholder="" class="form-control">
													</div>
													<div class="col-sm-6">
														<label>Confirm Password <span class="error">*</span></label>
														<input id="add_confirm_password" name="confirm_password" type="password" placeholder="" class="form-control">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">										
													<div class="col-sm-6">
														<label class="d-block">Status <span class="error">*</span></label>
														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																Active
																<input type="radio" class="form-check-input" id="add_is_active_1" name="is_active" value="1" checked>
															</label>
														</div>

														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																Inactive
																<input type="radio" class="form-check-input" id="add_is_active_0" name="is_active" value="0">
															</label>
														</div>
													</div>
													<div class="col-sm-3">
														<label>Sort Key <span class="error">*</span></label>
														<input id="add_sort_key" name="sort_key" type="number" class="form-control" min="0" value="0">
													</div>
												</div>
											</div>
	                   					</div>
	                   					<div class="col-md-4">
	                   						<div class="card rounded-top-0">
												<div class="card-header alpha-grey header-elements-inline pt-1 pb-1">
													<h6 class="card-title font-weight-600 text-success text-uppercase">Outlets Access</h6>			
												</div>							
												<div class="card-body">		
													<div class="table-responsive">
														<table class="table">
															<tbody>
																<?php foreach ($outlets as $row): ?>
																	<tr>
																		<td><input id="add_outlet_id_<?php echo $row->outlet_id; ?>" name="outlet_id[]" type="checkbox" value="<?php echo $row->outlet_id; ?>"> <label for="add_outlet_id_<?php echo $row->outlet_id; ?>" class="font-weight-500"><?php echo $row->outlet_name; ?></label></td>
																	</tr>
																<?php endforeach; ?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
	                   					</div>
	                   				</div>

									

									<hr>

								</div>

								<div class="modal-footer">								
									<button type="submit" id="btn_add_system_user" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> SAVE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CLOSE</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
	
			<!-- Edit modal form -->
			<div id="modal_edit_system_user" class="modal fade">
				<div class="modal-dialog modal-lg">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-pencil6"></i> Edit System User</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_edit_system_user" name="frm_edit_system_user" method="post" onsubmit="return update_system_user();">
							<fieldset <?php if ($sbr_system_users_edit == false){ echo 'disabled'; } ?>>
								<div class="modal-body">

									<div id="div_edit_system_user_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_edit_system_user_success" class="alert alert-success display-none font-13"></div>

	                   				<input id="edit_system_user_id" name="system_user_id" type="hidden" placeholder="" class="form-control">
	                   				<div class="row">
	                   					<div class="col-md-8">
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-6">
														<label>First Name <span class="error">*</span></label>
														<input id="edit_first_name" name="first_name" type="text" placeholder="" class="form-control">
													</div>
													<div class="col-sm-6">
														<label>Last Name <span class="error">*</span></label>
														<input id="edit_last_name" name="last_name" type="text" placeholder="" class="form-control">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-6">
														<label>Email Address <span class="error">*</span></label>
														<input id="edit_email_address" name="email_address" type="email" placeholder="" class="form-control">
													</div>
													<div class="col-sm-6">
														<label>User Role <span class="error">*</span></label>
														<select id="edit_user_role_id" name="user_role_id" class="form-control form-control-select2" data-placeholder="Select User Role" data-fouc>
															<option value="">Select User Role</option>
															<?php foreach ($user_roles as $row): ?>
																<option value="<?php echo $row->user_role_id; ?>"><?php echo $row->user_role_name; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">										
													<div class="col-sm-6">
														<label>Phone Number <span class="error">*</span></label>
														<input id="edit_phone_number" name="phone_number" type="text" placeholder="" class="form-control">
													</div>
													<div class="col-sm-6">
														<label>Address</label>
														<input id="edit_address" name="address" type="text" placeholder="" class="form-control">
													</div>
												</div>
											</div>
											<div class="form-group mb-2">
												<div class="row">										
													<div class="col-sm-6">
														<label class="d-block">Status <span class="error">*</span></label>
														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																Active
																<input type="radio" class="form-check-input" id="edit_is_active_1" name="is_active" value="1" checked>
															</label>
														</div>

														<div class="form-check form-check-inline form-check-right">
															<label class="form-check-label font-weight-semibold">
																Inactive
																<input type="radio" class="form-check-input" id="edit_is_active_0" name="is_active" value="0">
															</label>
														</div>
													</div>
													<div class="col-sm-3">
														<label>Sort Key <span class="error">*</span></label>
														<input id="edit_sort_key" name="sort_key" type="number" class="form-control" min="0" value="0">
													</div>
												</div>
											</div>
										</div>
										<div class="col-md-4">
											<div class="card rounded-top-0">
												<div class="card-header alpha-grey header-elements-inline pt-1 pb-1">
													<h6 class="card-title font-weight-600 text-success text-uppercase"><i class="icon-checkmark6"></i> Outlets Access</h6>			
												</div>							
												<div class="card-body">		
													<div class="table-responsive">
														<table class="table">
															<tbody>
																<?php foreach ($outlets as $row): ?>
																	<tr>
																		<td><input id="edit_outlet_id_<?php echo $row->outlet_id; ?>" name="outlet_id[]" type="checkbox" value="<?php echo $row->outlet_id; ?>"> <label for="edit_outlet_id_<?php echo $row->outlet_id; ?>" class="font-weight-500"><?php echo $row->outlet_name; ?></label></td>
																	</tr>
																<?php endforeach; ?>
															</tbody>
														</table>
													</div>
												</div>
											</div>
										</div>
									</div>


									<hr>



								</div>
								<div class="modal-footer">								
									<button type="submit" id="btn_update_system_user" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CLOSE</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<!-- Edit modal form -->
			<div id="modal_change_password" class="modal fade">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-lock2"></i> Change Password</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_system_user_change_password" name="frm_system_user_change_password" method="post" onsubmit="return system_user_change_password();" autocomplete="off">
							<fieldset <?php if ($sbr_system_users_edit == false){ echo 'disabled'; } ?>>
								<div class="modal-body">

									<div id="div_system_user_change_password_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_system_user_change_password_success" class="alert alert-success display-none font-13"></div>

	                   				<input id="edit_system_user_change_password_id" name="system_user_id" type="hidden" placeholder="" class="form-control">

									<div class="form-group mb-2">
										<div class="row">
											<div class="col-sm-12">
												<label>New Password <span class="error">*</span></label>
												<input id="change_user_password" name="user_password" type="password" placeholder="" class="form-control" autocomplete="off">
											</div>
										</div>
									</div>
									<div class="form-group mb-2">
										<div class="row">
											<div class="col-sm-12">
												<label>Confirm Password <span class="error">*</span></label>
												<input id="change_confirm_password" name="confirm_password" type="password" placeholder="" class="form-control" autocomplete="off">
											</div>
										</div>
									</div>
								</div>
								<div class="modal-footer">								
									<button type="submit" id="btn_system_user_change_password" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> CHANGE PASSWORD</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CLOSE</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				load_system_users();
			</script>
	