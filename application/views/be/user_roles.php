		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="#" class="breadcrumb-item">System Users</a>
							<span class="breadcrumb-item active">User Roles</span>
						</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content pt-0">
				<div class="row">
					<div class="col-md-9">
						<div class="card rounded-top-0">
							<div class="card-header bg-transparent header-elements-inline p-2">
								<h6 class="card-title font-weight-600">User Roles</h6>			
								<div class="header-elements">
									<?php if ($sbr_user_roles_add == true): ?>
										<a href="<?php echo base_url();?>be/user_roles/add" class="btn btn-sm btn-primary"><i class="icon-plus-circle2"></i> Add New User Role</a>
									<?php endif; ?>
									<!-- <button type="button" data-toggle="modal" data-target="#modal_add_user_role" class="btn btn-sm btn-primary" onclick="user_role_add_clear();" ><i class="icon-plus-circle2"></i> Add New User Role</button> -->									
								</div>			
							</div>

							<div id="user_roles_div" style="min-height: 400px;">
								
							</div>
						</div>
					</div>
				</div>
			</div>


			<!-- Add modal form -->
			<div id="modal_add_user_role" class="modal fade">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-plus-circle2"></i> New User Role</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_add_user_role" name="frm_add_user_role" method="post" onsubmit="return save_user_role();">
							<div class="modal-body">

								<div id="div_add_user_role_error" class="alert alert-danger display-none font-13"></div>
                   				<div id="div_add_user_role_success" class="alert alert-success display-none font-13"></div>

								<div class="form-group mb-2">
									<div class="row">
										<div class="col-sm-12">
											<label>User Role Name <span class="error">*</span></label>
											<input id="add_user_role_name" name="user_role_name" type="text" placeholder="" class="form-control">
										</div>										
									</div>
								</div>	
								<div class="form-group mb-2">
									<div class="row">
										<div class="col-sm-12">
											<label>Description</label>
											<textarea name="user_role_description" id="add_user_role_description" rows="3" cols="3" class="form-control" placeholder=""></textarea>
										</div>
									</div>
								</div>
								<div class="form-group mb-3 mb-md-2">
									<div class="row">
										<div class="col-sm-6">
											<label>Sort Key <span class="error">*</span></label>
											<input id="add_sort_key" name="sort_key" type="number" class="form-control" min="0" value="0">
										</div>
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
									</div>
								</div>
							</div>

							<div class="modal-footer">								
								<button type="submit" id="btn_add_user_role" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> SAVE</button>
								<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CLOSE</button>
							</div>
						</form>
					</div>
				</div>
			</div>
	
			<!-- Edit modal form -->
			<div id="modal_edit_user_role" class="modal fade">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-pencil6"></i> Edit User Role</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_edit_user_role" name="frm_edit_user_role" method="post" onsubmit="return update_user_role();">
							<div class="modal-body">

								<div id="div_edit_user_role_error" class="alert alert-danger display-none font-13"></div>
                   				<div id="div_edit_user_role_success" class="alert alert-success display-none font-13"></div>

                   				<input id="edit_user_role_id" name="user_role_id" type="hidden" placeholder="" class="form-control">

								<div class="form-group mb-2">
									<div class="row">
										<div class="col-sm-12">
											<label>User Role Name <span class="error">*</span></label>
											<input id="edit_user_role_name" name="user_role_name" type="text" placeholder="" class="form-control">
										</div>										
									</div>
								</div>	
								<div class="form-group mb-2">
									<div class="row">
										<div class="col-sm-12">
											<label>Description</label>
											<textarea name="user_role_description" id="edit_user_role_description" rows="3" cols="3" class="form-control" placeholder=""></textarea>
										</div>
									</div>
								</div>
								<div class="form-group mb-3 mb-md-2">
									<div class="row">
										<div class="col-sm-6">
											<label>Sort Key <span class="error">*</span></label>
											<input id="edit_sort_key" name="sort_key" type="number" class="form-control" min="0" value="0">
										</div>
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
									</div>
								</div>
							</div>

							<div class="modal-footer">								
								<button type="submit" id="btn_update_user_role" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
								<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CLOSE</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				load_user_roles();
			</script>
	