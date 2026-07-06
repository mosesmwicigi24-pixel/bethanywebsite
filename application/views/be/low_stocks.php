		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="#" class="breadcrumb-item">Inventory</a>
							<span class="breadcrumb-item active">Items on Reorder Level</span>
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
							<div class="spinner2 display-none" id="low_stocks_loader">
		                        <div class="rect1"></div>
		                        <div class="rect2"></div>
		                        <div class="rect3"></div>
		                    </div>
							<div class="card-header bg-transparent header-elements-inline p-2">
								<h5 class="card-title font-weight-bold"><i class="icon-stairs mr-1"></i> Items on Reorder Level</h5>			
							</div>
							<div class="card-body">
								<div class="row">
									<div class="col-md-12">
										<form method="post" class="form" id="frm_filter_low_stocks" name="frm_filter_low_stocks" method="post">
											<div class="row">
												<div class="col-sm-3 font-weight-600">
													<div class="form-group mb-2">
														<select id="st_outlet_id" name="outlet_id" data-placeholder="Select Outlet" class="form-control select" data-fouc>
															<option value="">Select Outlet</option>
															<?php foreach ($outlets as $row): ?>
																<option value="<?php echo $row->outlet_id; ?>"><?php echo $row->outlet_name; ?></option>
															<?php endforeach; ?>
														</select>
													</div>
												</div>
												
												<div class="col-sm-2">
													<button id="btn_low_stocks_filter" type="button" onclick="filter_low_stocks();" class="btn btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> FILTER</button>
												</div>
											</div>
											<div id="low_stocks_div" style="min-height: 400px;">
									
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
				$(document).ready(function() {
				    filter_low_stocks();
				});
			</script>


	