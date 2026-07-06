				<div class="w-100">
						<div class="row">
							<div class="col-lg-12">
								<div class="card rounded-top-0">
									<div class="card-header bg-transparent header-elements-inline p-2">
										<h5 class="card-title font-weight-bold"><i class="icon-office mr-2"></i> Store Information</h5>
									</div>
									<div class="card-body">
										<?php if ($store_information_exists == true): ?>
		                        			<?php foreach($store_information as $row): ?>
		                        				<form id="frm_store_information" name="frm_store_information" method="post" onsubmit="return save_store_information();" autocomplete="off" enctype="multipart/form-data">
													<div id="div_error" class="alert alert-danger display-none font-13"></div>
				                   					<div id="div_success" class="alert alert-success display-none font-13"></div>

				                   					<fieldset <?php if ($sbr_store_information_edit == false){ echo 'disabled'; } ?>>

					                   					<div class="row">
															<div class="col-md-3">
																<div class="card rounded-top-0">
																	<div class="card-header alpha-grey text-primary header-elements-inline pt-2 pb-2">
																<h6 class="card-title font-weight-bold">Store Logo</h6>			
																	</div>
																	<div class="card-body">
																		<div class="form-group">
																			<?php if($row->store_logo != '' && file_exists("./uploads/store_logo/" . $row->store_logo)): ?>
																				<input id="store_logo" name="store_logo" type="file" class="file-input-edit" data-show-remove="false" data-show-upload="false" data-show-close="false" data-show-caption="false" data-show-upload="false" data-fouc>

																				<script>
																					// Modal template
																			        var modalTemplate = '<div class="modal-dialog modal-lg" role="document">\n' +
																			            '  <div class="modal-content">\n' +
																			            '    <div class="modal-header align-items-center">\n' +
																			            '      <h6 class="modal-title">{heading} <small><span class="kv-zoom-title"></span></small></h6>\n' +
																			            '      <div class="kv-zoom-actions btn-group">{toggleheader}{fullscreen}{borderless}{close}</div>\n' +
																			            '    </div>\n' +
																			            '    <div class="modal-body">\n' +
																			            '      <div class="floating-buttons btn-group"></div>\n' +
																			            '      <div class="kv-zoom-body file-zoom-content"></div>\n' + '{prev} {next}\n' +
																			            '    </div>\n' +
																			            '  </div>\n' +
																			            '</div>\n';

																			        // Buttons inside zoom modal
																			        var previewZoomButtonClasses = {
																			            toggleheader: 'btn btn-light btn-icon btn-header-toggle btn-sm',
																			            fullscreen: 'btn btn-light btn-icon btn-sm',
																			            borderless: 'btn btn-light btn-icon btn-sm',
																			            close: 'btn btn-light btn-icon btn-sm'
																			        };

																			        // Icons inside zoom modal classes
																			        var previewZoomButtonIcons = {
																			            prev: '<i class="icon-arrow-left32"></i>',
																			            next: '<i class="icon-arrow-right32"></i>',
																			            toggleheader: '<i class="icon-menu-open"></i>',
																			            fullscreen: '<i class="icon-screen-full"></i>',
																			            borderless: '<i class="icon-alignment-unalign"></i>',
																			            close: '<i class="icon-cross2 font-size-base"></i>'
																			        };

																			        // File actions
																			        var fileActionSettings = {
																			            zoomClass: '',
																			            zoomIcon: '<i class="icon-zoomin3"></i>',
																			            dragClass: 'p-2',
																			            dragIcon: '<i class="icon-three-bars"></i>',
																			            removeClass: '',
																			            removeErrorClass: 'text-danger',
																			            removeIcon: '<i class="icon-bin"></i>',
																			            indicatorNew: '<i class="icon-file-plus text-success"></i>',
																			            indicatorSuccess: '<i class="icon-checkmark3 file-icon-large text-success"></i>',
																			            indicatorError: '<i class="icon-cross2 text-danger"></i>',
																			            indicatorLoading: '<i class="icon-spinner2 spinner text-muted"></i>'
																			        };

																					$(document).ready(function() {
																						$('#store_logo').fileinput({
																				            browseLabel: 'Browse',
																				            browseIcon: '<i class="icon-file-plus mr-2"></i>',
																				            uploadIcon: '<i class="icon-file-upload2 mr-2"></i>',
																				            removeIcon: '<i class="icon-cross2 font-size-base mr-2"></i>',
																				            layoutTemplates: {
																				                icon: '<i class="icon-file-check"></i>',
																				                modal: modalTemplate
																				            },
																				          	initialPreview: [
																				                baseDir + 'uploads/store_logo/' + '<?php echo $row->store_logo; ?>',
																				            ],
																				            initialPreviewConfig: [
																				                {showDrag: false},
																				            ],
																				            allowedFileExtensions: ["jpg", "gif", "png"],
																				            initialCaption: "No file selected",
																				            initialPreviewAsData: true,
																				            previewZoomButtonClasses: previewZoomButtonClasses,
																				            previewZoomButtonIcons: previewZoomButtonIcons,
																				            fileActionSettings: fileActionSettings
																				        });
																					});
																				</script>
																			<?php else: ?>
																				<input id="store_logo" name="store_logo" type="file" class="file-input" data-show-file-actions="false" data-show-upload="false" data-show-close="false" data-show-caption="false" data-show-upload="false" data-fouc>
																			<?php endif; ?>
																			<span class="form-text text-muted"><b>Accepted formats:</b> jpg, png, gif. Max file size 2Mb</span>
																		</div>

																	</div>
																</div>
															</div>
															<div class="col-md-6">
																<div class="card rounded-top-0">
																	<div class="card-header alpha-grey text-primary header-elements-inline pt-2 pb-2">
																<h6 class="card-title font-weight-bold">General Information</h6>			
																	</div>
																	<div class="card-body">
																		<div class="form-group">
																			<label>Store Name <span class="text-danger">*</span></label>
																			<input id="store_name" name="store_name" type="text" class="form-control form-control-lg font-weight-semi-bold" placeholder="" value="<?php echo $row->store_name; ?>">
																		</div>
																		<div class="row">
																			<div class="col-md-12">
																				<div class="form-group mb-3">
																					<label>Physical Address</label>
																					<textarea id="physical_address" name="physical_address" rows="3" cols="3" class="form-control"><?php echo $row->physical_address; ?></textarea>
																				</div>
																			</div>
																		</div>
																		<div class="row">
																			<div class="col-md-6">
																				<div class="form-group">
																					<label>Registration Number</label>
																					<input id="registration_number" name="registration_number" type="text" class="form-control" placeholder="" value="<?php echo $row->registration_number; ?>">
																				</div>
																			</div>
																			<div class="col-md-6">
																				<div class="form-group">
																					<label>PIN Number</label>
																					<input id="pin_number" name="pin_number" type="text" class="form-control" placeholder="" value="<?php echo $row->pin_number; ?>">
																				</div>
																			</div>
																		</div>
																		<div class="row">
																			<div class="col-md-6">
																				<div class="form-group">
																					<label>Phone Number <span class="text-danger">*</span></label>
																					<input id="phone_number" name="phone_number" type="text" class="form-control" placeholder="" value="<?php echo $row->phone_number; ?>">
																				</div>
																			</div>
																			<div class="col-md-6">
																				<div class="form-group">
																					<label>Mobile Number</label>
																					<input id="mobile_number" name="mobile_number" type="text" class="form-control" placeholder="" value="<?php echo $row->mobile_number; ?>">
																				</div>
																			</div>
																		</div>										
																		<div class="row">
																			<div class="col-md-6">
																				<div class="form-group">
																					<label>Email Address <span class="text-danger">*</span></label>
																					<input id="email_address" name="email_address" type="email" class="form-control" placeholder="" value="<?php echo $row->email_address; ?>">
																				</div>
																			</div>
																			<div class="col-md-6">
																				<div class="form-group">
																					<label>Website</label>
																					<input id="website" name="website" type="text" class="form-control" placeholder="" value="<?php echo $row->website; ?>">
																				</div>
																			</div>
																		</div>
																		<div class="row">
																			<div class="col-md-6">
																				<div class="form-group">
																					<label>Postal Address</label>
																					<input id="postal_address" name="postal_address" type="text" class="form-control" placeholder="" value="<?php echo $row->postal_address; ?>">
																				</div>
																			</div>
																			<div class="col-md-6">
																				<div class="form-group">
																					<label>ZIP Code</label>
																					<input id="postal_code" name="postal_code" type="text" class="form-control" placeholder="" value="<?php echo $row->postal_code; ?>">
																				</div>
																			</div>
																		</div>
																		<div class="row">
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Opening Hours</label>
																					<input id="opening_hours" name="opening_hours" type="text" class="form-control" placeholder="" value="<?php echo $row->opening_hours; ?>">
																				</div>
																			</div>
																		</div>
																		

																	</div>
																</div>
															</div>
															<div class="col-md-3">
																<div class="card rounded-top-0">
																	<div class="card-header alpha-grey text-primary header-elements-inline pt-2 pb-2">
																<h6 class="card-title font-weight-bold">Social Media</h6>			
																	</div>
																	<div class="card-body">
																		<div class="row">
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Facebook (Link)</label>
																					<input id="sm_facebook" name="sm_facebook" type="text" class="form-control" placeholder="e.g.https://facebook.com/mystore" value="<?php echo $row->sm_facebook; ?>">
																				</div>
																			</div>
																		</div>
																		<div class="row">
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Twitter (Link)</label>
																					<input id="sm_twitter" name="sm_twitter" type="text" class="form-control" placeholder="e.g.https://twitter.com/mystore" value="<?php echo $row->sm_twitter; ?>">
																				</div>
																			</div>
																		</div>
																		<div class="row">
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>LinkedIn (Link)</label>
																					<input id="sm_linkedin" name="sm_linkedin" type="text" class="form-control" placeholder="e.g.https://linkedin.com/mystore" value="<?php echo $row->sm_linkedin; ?>">
																				</div>
																			</div>
																		</div>
																		<div class="row">
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Youtube (Link)</label>
																					<input id="sm_youtube" name="sm_youtube" type="text" class="form-control" placeholder="e.g.https://youtube.com/mystore" value="<?php echo $row->sm_youtube; ?>">
																				</div>
																			</div>
																		</div>
																		<div class="row">
																			<div class="col-md-12">
																				<div class="form-group">
																					<label>Instagram (Link)</label>
																					<input id="sm_instagram" name="sm_instagram" type="text" class="form-control" placeholder="e.g.https://instagram.com/mystore" value="<?php echo $row->sm_instagram; ?>">
																				</div>
																			</div>
																		</div>

																	</div>
																</div>
															</div>
														</div>

														<div class="text-right">
															<button id="btn_store_information" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE STORE INFORMATION</button>
														</div>
													</fieldset>
												</form>
		                        			<?php endforeach; ?>
		                        		<?php else: ?>		                        			
		                        			<form id="frm_store_information" name="frm_store_information" method="post" onsubmit="return save_store_information();" autocomplete="off" enctype="multipart/form-data">

												<div id="div_error" class="alert alert-danger display-none font-13"></div>
			                   					<div id="div_success" class="alert alert-success display-none font-13"></div>

			                   					<fieldset <?php if ($sbr_store_information_edit == false){ echo 'disabled'; } ?>>

				                   					<div class="row">
														<div class="col-md-3">
															<div class="card rounded-top-0">
																<div class="card-header alpha-grey text-success-800 header-elements-inline pt-2 pb-2">
																	<h6 class="card-title text-uppercase">Store Logo</h6>			
																</div>
																<div class="card-body">
																	<div class="form-group">
																		<input id="store_logo" name="store_logo" type="file" class="file-input" data-show-file-actions="false" data-show-upload="false" data-show-close="false" data-show-caption="false" data-show-upload="false" data-fouc>
																		<span class="form-text text-muted"><b>Accepted formats:</b> jpg, png, gif. Max file size 2Mb</span>
																	</div>

																</div>
															</div>
														</div>
														<div class="col-md-6">
															<div class="card rounded-top-0">
																<div class="card-header alpha-grey text-success-800 header-elements-inline pt-2 pb-2">
																	<h6 class="card-title text-uppercase">General Information</h6>			
																</div>
																<div class="card-body">
																	<div class="form-group">
																		<label>Store Name <span class="text-danger">*</span></label>
																		<input id="store_name" name="store_name" type="text" class="form-control form-control-lg font-weight-semi-bold" placeholder="">
																	</div>
																	<div class="row">
																		<div class="col-md-12">
																			<div class="form-group mb-3">
																				<label>Physical Address</label>
																				<textarea id="physical_address" name="physical_address" rows="3" cols="3" class="form-control"></textarea>
																			</div>
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-md-6">
																			<div class="form-group">
																				<label>Registration Number</label>
																				<input id="registration_number" name="registration_number" type="text" class="form-control" placeholder="">
																			</div>
																		</div>
																		<div class="col-md-6">
																			<div class="form-group">
																				<label>PIN Number</label>
																				<input id="pin_number" name="pin_number" type="text" class="form-control" placeholder="">
																			</div>
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-md-6">
																			<div class="form-group">
																				<label>Phone Number <span class="text-danger">*</span></label>
																				<input id="phone_number" name="phone_number" type="text" class="form-control" placeholder="">
																			</div>
																		</div>
																		<div class="col-md-6">
																			<div class="form-group">
																				<label>Mobile Number</label>
																				<input id="mobile_number" name="mobile_number" type="text" class="form-control" placeholder="">
																			</div>
																		</div>
																	</div>										
																	<div class="row">
																		<div class="col-md-6">
																			<div class="form-group">
																				<label>Email Address <span class="text-danger">*</span></label>
																				<input id="email_address" name="email_address" type="email" class="form-control" placeholder="">
																			</div>
																		</div>
																		<div class="col-md-6">
																			<div class="form-group">
																				<label>Website</label>
																				<input id="website" name="website" type="text" class="form-control" placeholder="">
																			</div>
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-md-6">
																			<div class="form-group">
																				<label>Postal Address</label>
																				<input id="postal_address" name="postal_address" type="text" class="form-control" placeholder="">
																			</div>
																		</div>
																		<div class="col-md-6">
																			<div class="form-group">
																				<label>ZIP Code</label>
																				<input id="postal_code" name="postal_code" type="text" class="form-control" placeholder="">
																			</div>
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-md-12">
																			<div class="form-group">
																				<label>Opening Hours</label>
																				<input id="opening_hours" name="opening_hours" type="text" class="form-control" placeholder="" value="">
																			</div>
																		</div>
																	</div>

																</div>
															</div>
														</div>
														<div class="col-md-3">
															<div class="card rounded-top-0">
																<div class="card-header alpha-grey text-success-800 header-elements-inline pt-2 pb-2">
																	<h6 class="card-title text-uppercase">Social Media</h6>			
																</div>
																<div class="card-body">
																	<div class="row">
																		<div class="col-md-12">
																			<div class="form-group">
																				<label>Facebook (Link)</label>
																				<input id="sm_facebook" name="sm_facebook" type="text" class="form-control" placeholder="e.g.https://facebook.com/mystore">
																			</div>
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-md-12">
																			<div class="form-group">
																				<label>Twitter (Link)</label>
																				<input id="sm_twitter" name="sm_twitter" type="text" class="form-control" placeholder="e.g.https://twitter.com/mystore">
																			</div>
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-md-12">
																			<div class="form-group">
																				<label>LinkedIn (Link)</label>
																				<input id="sm_linkedin" name="sm_linkedin" type="text" class="form-control" placeholder="e.g.https://linkedin.com/mystore">
																			</div>
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-md-12">
																			<div class="form-group">
																				<label>Youtube (Link)</label>
																				<input id="sm_youtube" name="sm_youtube" type="text" class="form-control" placeholder="e.g.https://youtube.com/mystore">
																			</div>
																		</div>
																	</div>
																	<div class="row">
																		<div class="col-md-12">
																			<div class="form-group">
																				<label>Instagram (Link)</label>
																				<input id="sm_instagram" name="sm_instagram" type="text" class="form-control" placeholder="e.g.https://instagram.com/mystore">
																			</div>
																		</div>
																		<!-- <div class="col-md-6">
																			<div class="form-group">
																				<label>Twitter (Link)</label>
																				<input id="sm_twitter" name="sm_twitter" type="text" class="form-control" placeholder="">
																			</div>
																		</div> -->
																	</div>

																</div>
															</div>
														</div>
													</div>													
													
													<div class="text-right">
														<button id="btn_store_information" type="submit" class="btn btn-sm btn-success"><i class="icon-checkmark4"></i> SAVE STORE INFORMATION</button>
													</div>
												</fieldset>
											</form>
		                        		<?php endif; ?>
									</div>
								</div>
							</div>
							
						</div>

					</div>


				</div>
			</div>


				



		