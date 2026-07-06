		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<span class="breadcrumb-item active">Search Engine Optimization</span>
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
								<h5 class="card-title"><i class="icon-wrench"></i> Search Engine Optimization</h5>
							</div>
							<div class="card-body">
								<?php if ($seo_exists == true): ?>
                        			<?php foreach($seo as $row): ?>
                        				<form id="frm_seo" name="frm_seo" method="post" onsubmit="return save_seo();" autocomplete="off" enctype="multipart/form-data">
                        					<fieldset <?php //if ($sbr_seo_edit == false){ echo 'disabled'; } ?>>
												<!-- <legend class="font-weight-semibold text-uppercase font-size-sm">Enter your information</legend> -->
												<div id="div_error" class="alert alert-danger display-none font-13"></div>
			                   					<div id="div_success" class="alert alert-success display-none font-13"></div>

												<div class="row">
													<div class="col-md-12">
														<div class="form-group mb-3">
															<label>Site Title</label>
															<input type="text" name="site_title" id="site_title" class="form-control" value="<?php echo $row->site_title; ?>">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group mb-3">
															<label>Description</label>
															<textarea id="site_description" name="site_description" rows="3" cols="3" class="form-control "><?php echo $row->site_description; ?></textarea>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group mb-3">
															<label>Keywords</label>
															<textarea id="site_keywords" name="site_keywords" rows="1" cols="3" class="form-control "><?php echo $row->site_keywords; ?></textarea>
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="form-group mb-3">
															<label>Sitemap Link</label>
															<input type="text" name="sitemap_link" id="sitemap_link" class="form-control" value="<?php echo $row->sitemap_link; ?>">
														</div>
													</div>
												</div>

												<div class="text-right">
													<button id="btn_seo" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save SEO Settings</button>
												</div>
											</fieldset>
										</form>
                        			<?php endforeach; ?>
                        		<?php else: ?>
                        			<form id="frm_seo" name="frm_seo" method="post" onsubmit="return save_seo();" autocomplete="off" enctype="multipart/form-data">
                        				<fieldset <?php //if ($sbr_seo_edit == false){ echo 'disabled'; } ?>>
											<div id="div_error" class="alert alert-danger display-none font-13"></div>
		                   					<div id="div_success" class="alert alert-success display-none font-13"></div>

											<div class="row">
												<div class="col-md-12">
													<div class="form-group mb-3">
														<label>Site Title</label>
														<input type="text" name="site_title" id="site_title" class="form-control">
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="form-group mb-3">
														<label>Description</label>
														<textarea id="site_description" name="site_description" rows="3" cols="3" class="form-control "></textarea>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="form-group mb-3">
														<label>Keywords</label>
														<textarea id="site_keywords" name="site_keywords" rows="1" cols="3" class="form-control "></textarea>
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-md-12">
													<div class="form-group mb-3">
														<label>Sitemap Link</label>
														<input type="text" name="sitemap_link" id="sitemap_link" class="form-control">
													</div>
												</div>
											</div>
											<div class="text-right">
												<button id="btn_seo" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save SEO Settings</button>
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
