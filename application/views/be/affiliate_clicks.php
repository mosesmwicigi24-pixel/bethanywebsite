		<?php foreach ($affiliate as $row): ?>
			<!-- Main content -->
			<div class="content-wrapper">

				<!-- Page header -->
				<div class="page-header">
					<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
						<div class="d-flex">
							<div class="breadcrumb">
								<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
								<a href="<?php echo base_url();?>be/affiliates" class="breadcrumb-item">Affiliate Accounts</a>
								<a href="<?php echo base_url();?>be/affiliates/" class="breadcrumb-item">Account (<?php echo $row->first_name . ' ' . $row->last_name; ?>)</a>
								<span class="breadcrumb-item active">Clicks</span>
							</div>

							<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
						</div>
					</div>
				</div>
				<!-- /page header -->


				<!-- Content area -->
				<div class="content pt-0">

					<div class="d-md-flex align-items-md-start">

						<div class="sidebar sidebar-light sidebar-component sidebar-component-left bg-transparent border-0 shadow-0 sidebar-expand-md">

							<!-- Sidebar content -->
							<div class="sidebar-content">

								<!-- Sub navigation -->
								<div class="card">
									<div class="card-header bg-transparent header-elements-inline p-2">
										<span class="text-uppercase font-size-sm font-weight-semibold"><i class="icon-cog7"></i> Manage Affiliate Account</span>
										<div class="header-elements">
											<div class="list-icons">
						                		<a class="list-icons-item" data-action="collapse"></a>
					                		</div>
				                		</div>
									</div>

									<div class="card-body p-0">
										<ul class="nav nav-sidebar" data-nav-type="accordion">
											<li class="nav-item">
												<a href="<?php echo base_url();?>be/affiliates/manage/<?php echo $row->affiliate_id; ?>" class="nav-link"><i class="icon-user"></i> Account Details</a>
											</li>
											<li class="nav-item">
												<a href="<?php echo base_url();?>be/affiliates/account_referrals/<?php echo $row->affiliate_id; ?>" class="nav-link"><i class="icon-cart"></i> Referrals</a>
											</li>
											<li class="nav-item">
												<a href="<?php echo base_url();?>be/affiliates/account_clicks/<?php echo $row->affiliate_id; ?>" class="nav-link active"><i class="icon-mouse"></i> Clicks</a>
											</li>
											<li class="nav-item">
												<a href="#" class="nav-link"><i class="icon-coin-dollar"></i> Withdrawals</a>
											</li>
										</ul>
									</div>
								</div>
							</div>
						</div>


						<div class="w-100">
							<div class="row">
								<div class="col-md-12">
									<div class="card">
										<div class="card-header bg-transparent header-elements-inline p-2">
											<h5 class="card-title font-weight-bold"><i class="icon-cart mr-2"></i> Clicks (<?php echo $row->first_name . ' ' . $row->last_name; ?>)</h5>
										</div>
										<div class="card-body">

											<div class="row">
												<div class="col-md-12">
													<form method="post" class="form">
														<div id="affiliate_clicks_div" style="min-height: 400px;">
												
														</div>
													</form>
												</div>
											</div>

										</div>
									</div>
								</div>	
							</div>
						</div>
					</div>
				</div>
				<script type="text/javascript">
					load_affiliate_clicks(<?php echo $row->affiliate_id; ?>);
				</script>
			<?php endforeach; ?>
					



			