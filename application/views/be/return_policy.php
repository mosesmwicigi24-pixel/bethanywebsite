		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<span class="breadcrumb-item active">Return Policy</span>
						</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content pt-0">

				<div class="row">
					<div class="col-lg-10">
						<div class="card rounded-top-0">
							<div class="card-header header-elements-inline">
								<h5 class="card-title"><i class="icon-info22"></i> Return Policy</h5>
							</div>
							<div class="card-body">
								<?php if ($return_policy_exists == true): ?>
                        			<?php foreach($return_policy as $row): ?>
                        				<form id="frm_return_policy" name="frm_return_policy" method="post" onsubmit="return save_return_policy();" autocomplete="off" enctype="multipart/form-data">
											<fieldset <?php if ($sbr_return_policy_edit == false){ echo 'disabled'; } ?>>
												<div id="div_error" class="alert alert-danger display-none font-13"></div>
			                   					<div id="div_success" class="alert alert-success display-none font-13"></div>

												<div class="row">
													<div class="col-md-12">
														<div class="form-group mb-3">
															<label>Return Policy</label>
															<textarea id="return_policy" name="return_policy" rows="3" cols="3" class="form-control ckeditor"><?php echo $row->return_policy; ?></textarea>
														</div>
													</div>
												</div>

												<div class="text-right">
													<button id="btn_return_policy" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Return Policy</button>
												</div>
											</fieldset>
										</form>
                        			<?php endforeach; ?>
                        		<?php else: ?>
                        			<form id="frm_return_policy" name="frm_return_policy" method="post" onsubmit="return save_return_policy();" autocomplete="off" enctype="multipart/form-data">
                        				<fieldset <?php if ($sbr_return_policy_edit == false){ echo 'disabled'; } ?>>
											<div id="div_error" class="alert alert-danger display-none font-13"></div>
		                   					<div id="div_success" class="alert alert-success display-none font-13"></div>

											<div class="row">
												<div class="col-md-12">
													<div class="form-group mb-3">
														<label>Return Policy</label>
														<textarea id="return_policy" name="return_policy" rows="3" cols="3" class="form-control ckeditor"></textarea>
													</div>
												</div>
											</div>
											<div class="text-right">
												<button id="btn_return_policy" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Return Policy</button>
											</div>
										</fieldset>
									</form>
                        		<?php endif; ?>
							</div>
						</div>
					</div>
					<div class="col-md-6">
					</div>
				</div>
			</div>
			<!-- /content area -->

            <script type="text/javascript">
                CKEDITOR.replace( 'return_policy', {
                    height: 600
                });

            </script>
