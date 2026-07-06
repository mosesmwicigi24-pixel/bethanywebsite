		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<span class="breadcrumb-item active">How To Shop</span>
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
								<h5 class="card-title"><i class="icon-info22"></i> How To Shop</h5>
							</div>
							<div class="card-body">
								<?php if ($how_to_shop_exists == true): ?>
                        			<?php foreach($how_to_shop as $row): ?>
                        				<form id="frm_how_to_shop" name="frm_how_to_shop" method="post" onsubmit="return save_how_to_shop();" autocomplete="off" enctype="multipart/form-data">
											<fieldset <?php if ($sbr_how_to_shop_edit == false){ echo 'disabled'; } ?>>
												<div id="div_error" class="alert alert-danger display-none font-13"></div>
			                   					<div id="div_success" class="alert alert-success display-none font-13"></div>

												<div class="row">
													<div class="col-md-12">
														<div class="form-group mb-3">
															<label>How To Shop</label>
															<textarea id="how_to_shop" name="how_to_shop" rows="3" cols="3" class="form-control ckeditor"><?php echo $row->how_to_shop; ?></textarea>
														</div>
													</div>
												</div>

												<div class="text-right">
													<button id="btn_how_to_shop" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save How To Shop</button>
												</div>
											</fieldset>
										</form>
                        			<?php endforeach; ?>
                        		<?php else: ?>
                        			<form id="frm_how_to_shop" name="frm_how_to_shop" method="post" onsubmit="return save_how_to_shop();" autocomplete="off" enctype="multipart/form-data">
                        				<fieldset <?php if ($sbr_how_to_shop_edit == false){ echo 'disabled'; } ?>>
											<div id="div_error" class="alert alert-danger display-none font-13"></div>
		                   					<div id="div_success" class="alert alert-success display-none font-13"></div>

											<div class="row">
												<div class="col-md-12">
													<div class="form-group mb-3">
														<label>How To Shop</label>
														<textarea id="how_to_shop" name="how_to_shop" rows="3" cols="3" class="form-control ckeditor"></textarea>
													</div>
												</div>
											</div>
											<div class="text-right">
												<button id="btn_how_to_shop" type="submit" class="btn btn-success"><i class="fa fa-save"></i> Save How To Shop</button>
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
                CKEDITOR.replace( 'how_to_shop', {
                    height: 600
                });

            </script>
