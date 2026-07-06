		<!-- Main content -->
		<div class="content-wrapper">

			<!-- Page header -->
			<div class="page-header">
				<div class="breadcrumb-line breadcrumb-line-light header-elements-md-inline">
					<div class="d-flex">
						<div class="breadcrumb">
							<a href="<?php echo base_url();?>be" class="breadcrumb-item"><i class="icon-home2 mr-2"></i> Dashboard</a>
							<a href="#" class="breadcrumb-item">Locations</a>
							<a href="<?php echo base_url();?>be/locations/countries" class="breadcrumb-item">Countries</a>
							<?php foreach($country as $row): ?>
								<a href="<?php echo base_url();?>be/locations/regions/<?php echo $country_id; ?>" class="breadcrumb-item"><?php echo $row->country_name; ?> Regions</a>
							<?php endforeach; ?>
							<span class="breadcrumb-item active"><?php foreach ($region as $row){ echo $row->region_name; } ?> Localities</span>
						</div>

						<a href="#" class="header-elements-toggle text-default d-md-none"><i class="icon-more"></i></a>
					</div>
				</div>
			</div>
			<!-- /page header -->


			<!-- Content area -->
			<div class="content pt-0">
				<div class="row">
					<div class="col-lg-9">
						<div class="card rounded-top-0">
							<div class="card-header bg-transparent header-elements-inline p-2">
								<h6 class="card-title font-weight-600">Localities (<?php foreach ($region as $row){ echo $row->region_name; } ?> Region)</h6>			
								<div class="header-elements">
									<a href="<?php echo base_url();?>be/locations/regions/<?php echo $country_id; ?>" class="btn btn-sm btn-primary"><i class="icon-arrow-left15"></i> <?php foreach ($country as $row){ echo $row->country_name; } ?> Regions</a>
								</div>			
							</div>

							<div class="card-body">
								<div class="row">
									<div class="col-md-4">
										<div class="card rounded-top-0">
											<div class="card-body">
												<form id="frm_add_locality" name="frm_add_locality" method="post" onsubmit="return save_locality();" autocomplete="off" enctype="multipart/form-data">

													<h6 class="text-primary mb-20 font-weight-600"><i class="icon-plus-circle2"></i> Add New Locality</h6>

													<div id="div_add_error" class="alert alert-danger display-none font-13"></div>
				                   					<div id="div_add_success" class="alert alert-success display-none font-13"></div>

				                   					<input type="hidden" id="add_country_id" name="country_id" value="<?php echo $country_id; ?>">
				                   					<input type="hidden" id="add_region_id" name="region_id" value="<?php echo $region_id; ?>">

				                   					<div class="form-group mb-3">
					                   					<div class="row">
															<div class="col-md-9">
																<div class="form-group">
																	<label>Locality Name <span class="text-danger">*</span></label>
																	<input id="add_locality_name" name="locality_name" type="text" class="form-control" placeholder="e.g. CBD">
																</div>
															</div>
															<div class="col-md-3">
																<div class="form-group mb-3">
																	<label>Sort Key <span class="text-danger">*</span></label>
																	<input id="add_sort_key" name="sort_key" type="number" class="form-control" placeholder="" value="0">
																</div>
															</div>
															
														</div>	
													</div>
													<div class="text-right">
														<button id="btn_add_locality" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE</button>
													</div>
												</form>
											</div>
										</div>
									</div>
									<!-- <div class="col-md-1"></div> -->
									<div class="col-md-8">
										<form method="post" class="form">
											<div id="localities_div" style="min-height: 400px;">
									
											</div>
											<div class="form-group mb-2">
												<div class="row">
													<div class="col-sm-2 font-weight-600">
														<select id="sl_localities_bulk_action" name="sl_localities_bulk_action" class="form-control form-control-select2" data-placeholder="Bulk Action" data-fouc>
															<option value="">Bulk Action</option>
															<option value="Delete">Delete</option>
														</select>
													</div>
													<div class="col-sm-2">
														<button id="btn_localities_bulk_action" type="button" class="btn btn-primary btn-sm font-weight-600"><i class="icon-checkmark4"></i> APPLY</button>
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



			<!-- Edit modal form -->
			<div id="modal_edit_locality" class="modal fade">
				<div class="modal-dialog modal-sm">
					<div class="modal-content">
						<div class="modal-header">
							<h5 class="modal-title text-primary"><i class="icon-pencil6"></i> Edit Locality</h5>
							<button type="button" class="close" data-dismiss="modal">&times;</button>
						</div>

						<form id="frm_edit_locality" name="frm_edit_locality" method="post" onsubmit="return update_locality();">
							<div class="modal-body">

								<div id="div_edit_error" class="alert alert-danger display-none font-13"></div>
                   				<div id="div_edit_success" class="alert alert-success display-none font-13"></div>

                   				<input type="hidden" id="edit_country_id" name="country_id" value="<?php echo $country_id; ?>">
                   				<input type="hidden" id="edit_region_id" name="region_id" value="<?php echo $region_id; ?>">
                   				
                   				<input id="edit_locality_id" name="locality_id" type="hidden" placeholder="" class="form-control">

								<div class="form-group mb-2">
									<div class="row">
										<div class="col-sm-8">
											<label>Locality Name <span class="error">*</span></label>
											<input id="edit_locality_name" name="locality_name" type="text" placeholder="" class="form-control">
										</div>
										<div class="col-sm-4">
											<label>Sort Key <span class="text-danger">*</span></label>
											<input id="edit_sort_key" name="sort_key" type="number" placeholder="" class="form-control">
										</div>
									</div>
								</div>
							</div>
							<div class="modal-footer">								
								<button type="submit" id="btn_update_locality" class="btn btn-outline btn-sm bg-success-400 text-success-400 border-success-400"><i class="icon-checkmark4"></i> UPDATE</button>
								<button type="button" class="btn btn-outline btn-sm bg-danger-400 text-danger-400 border-danger-400" data-dismiss="modal"><i class="icon-cancel-circle2"></i> CLOSE</button>
							</div>
						</form>
					</div>
				</div>
			</div>

			<script type="text/javascript">
				load_localities();
			</script>
	