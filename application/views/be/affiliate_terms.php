		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<span class="breadcrumb-item active">Affiliates Terms and Conditions</span>
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
								<h5 class="card-title"><i class="icon-info22"></i> Affiliates Terms and Conditions</h5>
							</div>
							<div class="card-body">
								<?php if ($affiliate_terms_exists == true): ?>
                        			<?php foreach($affiliate_terms as $row): ?>
                        				<form id="frm_affiliate_terms" name="frm_affiliate_terms" method="post" onsubmit="return save_affiliate_terms();" autocomplete="off" enctype="multipart/form-data">
											<fieldset <?php if ($sbr_affiliate_terms_edit == false){ echo 'disabled'; } ?>>
												<div id="div_error" class="alert alert-danger display-none font-13"></div>
			                   					<div id="div_success" class="alert alert-success display-none font-13"></div>

												<div class="row">
													<div class="col-md-12">
														<div class="form-group mb-3">
															<label>Affiliates Terms and Conditions</label>
															<textarea id="affiliate_terms" name="affiliate_terms" rows="3" cols="3" class="form-control ckeditor"><?php echo $row->affiliate_terms; ?></textarea>
														</div>
													</div>
												</div>

												<div class="text-right">
													<button id="btn_affiliate_terms" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Affiliates Terms and Conditions</button>
												</div>
											</fieldset>
										</form>
                        			<?php endforeach; ?>
                        		<?php else: ?>
                        			<form id="frm_affiliate_terms" name="frm_affiliate_terms" method="post" onsubmit="return save_affiliate_terms();" autocomplete="off" enctype="multipart/form-data">
                        				<fieldset <?php if ($sbr_affiliate_terms_edit == false){ echo 'disabled'; } ?>>
											<div id="div_error" class="alert alert-danger display-none font-13"></div>
		                   					<div id="div_success" class="alert alert-success display-none font-13"></div>

											<div class="row">
												<div class="col-md-12">
													<div class="form-group mb-3">
														<label>Affiliates Terms and Conditions</label>
														<textarea id="affiliate_terms" name="affiliate_terms" rows="3" cols="3" class="form-control ckeditor"></textarea>
													</div>
												</div>
											</div>
											<div class="text-right">
												<button id="btn_affiliate_terms" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save Affiliates Terms and Conditions</button>
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
                CKEDITOR.replace( 'affiliate_terms', {
                    height: 600
                });

            </script>
