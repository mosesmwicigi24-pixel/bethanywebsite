		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="#" class="breadcrumb-item">CMS Content</a>
							<a href="#" class="breadcrumb-item">Home Page</a>
							<span class="breadcrumb-item active">Promo Banners</span>
						</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content pt-0">
				<div class="row">
					<div class="col-lg-12">
						<div class="card rounded-top-0">
							<div class="card-header bg-transparent header-elements-inline p-2">
								<h6 class="card-title font-weight-600">Home Promo Banners</h6>			
								<div class="header-elements">
									<?php if ($sbr_promo_banners_add == true): ?>
										<a href="#"  data-toggle="modal" data-target="#modal_add_home_promo_banner" class="btn btn-sm btn-primary" onclick="home_promo_banner_add_clear();"><i class="icon-plus-circle2"></i> Add Promo Banner</a>
									<?php endif; ?>
								</div>			
							</div>
							<div class="card-body">
								<div id="home_promo_banners_div" style="min-height: 400px;">


								</div>
							</div>
						</div>
					</div>
				</div>
			</div>



			<!-- Add modal form -->
			<div id="modal_add_home_promo_banner" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-plus-circle2"></i> New Home Promo Banner</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_add_home_promo_banner" name="frm_add_home_promo_banner" method="post" onsubmit="return save_home_promo_banner();">
							<fieldset <?php if ($sbr_promo_banners_add == false){ echo 'disabled'; } ?>>
								<div class="modal-body">

									<div id="div_add_home_promo_banner_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_add_home_promo_banner_success" class="alert alert-success display-none font-13"></div>

									<div class="form-group">
										<label>Promo Banner <span class="error">*</span></label>
										<input id="add_promo_banner" name="promo_banner" type="file" class="form-control h-auto">
										<span class="form-text text-muted">Accepted formats: gif, png, jpg</span>
									</div>

									<div class="form-group mb-2">
										<div class="row">
											<div class="col-md-12">
												<label>Banner Link</label>
												<input id="add_home_promo_banner_link" name="home_promo_banner_link" type="text" placeholder="e.g. https://mywebsite.com" class="form-control">
											</div>
										</div>
									</div>
									<div class="form-group mb-2">
										<div class="row">
											<div class="col-md-4">
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
									<button type="submit" id="btn_add_home_promo_banner" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> SAVE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<!-- Edit modal form -->
			<div id="modal_edit_home_promo_banner" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-pencil6"></i> Edit Home Promo Banner</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_edit_home_promo_banner" name="frm_edit_home_promo_banner" method="post" onsubmit="return update_home_promo_banner();">
							<fieldset <?php if ($sbr_promo_banners_edit == false){ echo 'disabled'; } ?>>
								<div class="modal-body">

									<div id="div_edit_home_promo_banner_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_edit_home_promo_banner_success" class="alert alert-success display-none font-13"></div>

	                   				<input id="edit_home_promo_banner_id" name="home_promo_banner_id" type="hidden" placeholder="" class="form-control">

	                                <div class="thumbnail thumbnail-boxed mb-2">
	                                    <img id="img_home_promo_banner" class="img-fluid" src="" alt="">
	                                </div>

									<div class="form-group">
										<label>Promo Banner <small>(Please note that the image you select here will replace the current image)</small></label>
										<input id="edit_promo_banner" name="promo_banner" type="file" class="form-control h-auto">
										<span class="form-text text-muted">Accepted formats: gif, png, jpg</span>
									</div>

									<div class="form-group mb-2">
										<div class="row">
											<div class="col-md-12">
												<label>Banner Link</label>
												<input id="edit_home_promo_banner_link" name="home_promo_banner_link" type="text" placeholder="e.g. https://mywebsite.com" class="form-control">
											</div>
										</div>
									</div>
									<div class="form-group mb-2">
										<div class="row">
											<div class="col-md-4">
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
									<button type="submit" id="btn_update_home_promo_banner" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CANCEL</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>


			<script type="text/javascript">
				load_home_promo_banners();
			</script>
