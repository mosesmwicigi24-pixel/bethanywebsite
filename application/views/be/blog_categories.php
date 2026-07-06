		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<span class="breadcrumb-item active">Blog Categories</span>
						</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content pt-0">
				<div class="row">
					<div class="col-lg-6">
						<div class="card rounded-top-0">
							<div class="card-header bg-transparent header-elements-inline p-2">
								<h6 class="card-title font-weight-600">Blog Categories</h6>			
								<div class="header-elements">
									<?php if ($sbr_blog_categories_add == true): ?>
										<a href="#"  data-toggle="modal" data-target="#modal_add_blog_category" class="btn btn-sm btn-primary" onclick="blog_category_add_clear();"><i class="icon-plus-circle2"></i> Add Blog Category</a>
									<?php endif; ?>
								</div>			
							</div>

							<div id="blog_categories_div" style="min-height: 400px;">
								
							</div>
						</div>
					</div>
				</div>
			</div>


			<!-- Add modal form -->
			<div id="modal_add_blog_category" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-plus-circle2"></i> Add Blog Category</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_add_blog_category" name="frm_add_blog_category" method="post" onsubmit="return save_blog_category();">
							<fieldset <?php if ($sbr_blog_categories_add == false){ echo 'disabled'; } ?>>
								<div class="modal-body">

									<div id="div_add_blog_category_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_add_blog_category_success" class="alert alert-success display-none font-13"></div>

									<div class="form-group mb-2">
										<div class="row">
											<div class="col-md-10">
												<label>Name <span class="error">*</span></label>
												<input id="add_blog_category_name" name="blog_category_name" type="text" placeholder="" class="form-control">
											</div>
											<div class="col-md-2">
												<label>Sort Key <span class="error">*</span></label>
												<input id="add_sort_key" name="sort_key" type="number" placeholder="" value="0" class="form-control">
											</div>										
										</div>
									</div>	
									<div class="form-group mb-3 mb-md-2">
										<div class="row">
											<div class="col-sm-12">
												<label>Description</label>
												<textarea id="add_description" name="description" rows="2" cols="3" class="form-control ckeditor"></textarea>
											</div>
										</div>
									</div>
								</div>

								<div class="modal-footer">								
									<button type="submit" id="btn_add_blog_category" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> SAVE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CLOSE</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>
	
			<!-- Edit modal form -->
			<div id="modal_edit_blog_category" class="modal fade">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-pencil6"></i> Edit Blog Category</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_edit_blog_category" name="frm_edit_blog_category" method="post" onsubmit="return update_blog_category();">
							<fieldset <?php if ($sbr_blog_categories_edit == false){ echo 'disabled'; } ?>>
								<div class="modal-body">

									<div id="div_edit_blog_category_error" class="alert alert-danger display-none font-13"></div>
	                   				<div id="div_edit_blog_category_success" class="alert alert-success display-none font-13"></div>

	                   				<input id="edit_blog_category_id" name="blog_category_id" type="hidden" placeholder="" class="form-control">

									<div class="form-group mb-2">
										<div class="row">
											<div class="col-md-10">
												<label>Name <span class="error">*</span></label>
												<input id="edit_blog_category_name" name="blog_category_name" type="text" placeholder="" class="form-control">
											</div>
											<div class="col-md-2">
												<label>Sort Key <span class="error">*</span></label>
												<input id="edit_sort_key" name="sort_key" type="number" placeholder="" value="0" class="form-control">
											</div>										
										</div>
									</div>	
									<div class="form-group mb-3 mb-md-2">
										<div class="row">
											<div class="col-sm-12">
												<label>Description</label>
												<textarea id="edit_description" name="description" rows="2" cols="3" class="form-control ckeditor"></textarea>
											</div>
										</div>
									</div>
								</div>

								<div class="modal-footer">								
									<button type="submit" id="btn_update_blog_category" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
									<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CLOSE</button>
								</div>
							</fieldset>
						</form>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				CKEDITOR.replace( 'add_description', {
                    height: 150
                });
                CKEDITOR.replace( 'edit_description', {
                    height: 150
                });

				load_blog_categories();
			</script>
	