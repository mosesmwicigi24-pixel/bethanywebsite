		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<span class="breadcrumb-item active">Blog</span>
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
								<h6 class="card-title font-weight-600">Blog</h6>			
								<div class="header-elements">
									<?php if ($sbr_blog_add == true): ?>
										<a href="<?php echo base_url();?>be/blog/add" class="btn btn-sm btn-primary"><i class="icon-plus-circle2"></i> Add Blog Article</a>
									<?php endif; ?>
								</div>			
							</div>

							<div id="blog_div" style="min-height: 400px;">
								
							</div>
						</div>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				load_blog();
			</script>
	