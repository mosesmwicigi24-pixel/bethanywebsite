		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<span class="breadcrumb-item active">Products</span>
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
								<h5 class="card-title font-weight-bold"><i class="icon-grid52"></i> Products</h5>			
								<div class="header-elements">
									<?php if ($sbr_products_add == true): ?>
										<a href="<?php echo base_url();?>be/products/add" class="btn btn-sm btn-primary"><i class="icon-plus-circle2"></i> New Product</a>
									<?php endif; ?>
								</div>			
							</div>

							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<form method="post" class="form">
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-2 font-weight-600">
														<select id="products_fl_category_id" name="products_fl_category_id" class="form-control form-control-select2" data-placeholder="Filter by product Category" data-fouc>
															<option value="">Filter by Product Category</option>
															<?php foreach ($product_categories as $row): ?>
																<option value="<?php echo $row->product_category_id; ?>"><?php echo $row->product_category_name; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
													<div class="col-sm-2">
														<button id="btn_products_filter" type="button" class="btn btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> FILTER</button>
													</div>
												</div>
											</div>
											<div id="products_div" style="min-height: 400px;">
									
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-2 font-weight-600">
														<select id="sl_products_bulk_action" name="sl_products_bulk_action" class="form-control form-control-select2" data-placeholder="Bulk Action" data-fouc>
															<option value="">Bulk Action</option>
															<option value="Edit">Edit</option>
															<option value="Delete">Delete</option>
														</select>
													</div>
													<div class="col-sm-2">
														<button id="btn_products_bulk_action" type="button" class="btn btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> APPLY</button>
													</div>
												</div>
											</div>
										</form>
									</div>
								</div>
							</div>

							
						</div>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				load_products();
			</script>
	