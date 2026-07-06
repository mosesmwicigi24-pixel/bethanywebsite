					<div class="w-100">
						<div class="row">
							<div class="col-lg-12">
								<div class="row">
									<div class="col-md-6">
										<div class="card rounded-top-0">
											<div class="card-header alpha-grey text-primary header-elements-inline pt-2 pb-2">
												<h5 class="card-title font-weight-bold">Reconcile LNM V.1 Contacts</h5>			
											</div>
											<div class="card-body">
												<form class="form" id="frm_lnm_v1_contacts_reconciliation" name="frm_lnm_v1_contacts_reconciliation" method="post" onsubmit="return submit_lnm_v1_contacts_reconciliation();">
													<div class="row g-9 mb-5">		
																												
														<div class="col-md-12 fv-row">
															<p>Use this option to reconcile Lipa Na M-Pesa V.1 existing contacts.</p>
														</div>
														<div class="separator border-5 my-3"></div>
														<div class="col-md-12 fv-row">
															<button type="submit" id="btn_lnm_v1_contacts_reconciliation" class="btn btn-success">
																<i class="icon-checkmark2"></i> Reconcile
															</button>
														</div>
													</div>
												</form>

											</div>
										</div>
									</div>
									<div class="col-md-6">
										<div class="card rounded-top-0">
											<div class="card-header alpha-grey text-primary header-elements-inline pt-2 pb-2">
												<h5 class="card-title font-weight-bold">Reconcile LNM V.2 Contacts</h5>			
											</div>
											<div class="card-body">
												<form class="form" id="frm_lnm_v2_contacts_reconciliation" name="frm_lnm_v2_contacts_reconciliation"onsubmit="return submit_lnm_v2_contacts_reconciliation();">
													<div class="row g-9 mb-5">		
														<div class="col-md-12 fv-row">																		
															<label class="required fw-bold mb-2">Browse for Excel Sheet</label>
															<input type="file" class="form-control form-control-solid" name="excel_file" id="excel_file" />
														</div>															
														<div class="col-md-12 fv-row mt-3">
															<h6>Checklist for the reconciliation sheet:</h6>
															<ul>
																<li>It should be in .xls, .xlsx or .csv file format.</li>
																<li>The file should contain only one worksheet.</li>
																<li>The document should have 2 required columns namely - "Transaction ID" and "Phone Number".</li>
															</ul>
															<p>
																<a class="btn btn-link" href="<?php echo base_url(); ?>assets/lnm_reconciliation_sheet.xlsx" target="_blank" download>
																	<span class="svg-icon svg-icon-muted">
																		<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="d-inline">
																			<rect opacity="0.3" width="12" height="2" rx="1" transform="matrix(0 -1 -1 0 12.75 19.75)" fill="currentColor"/>
																			<path d="M12.0573 17.8813L13.5203 16.1256C13.9121 15.6554 14.6232 15.6232 15.056 16.056C15.4457 16.4457 15.4641 17.0716 15.0979 17.4836L12.4974 20.4092C12.0996 20.8567 11.4004 20.8567 11.0026 20.4092L8.40206 17.4836C8.0359 17.0716 8.0543 16.4457 8.44401 16.056C8.87683 15.6232 9.58785 15.6554 9.9797 16.1256L11.4427 17.8813C11.6026 18.0732 11.8974 18.0732 12.0573 17.8813Z" fill="currentColor"/>
																			<path opacity="0.3" d="M18.75 15.75H17.75C17.1977 15.75 16.75 15.3023 16.75 14.75C16.75 14.1977 17.1977 13.75 17.75 13.75C18.3023 13.75 18.75 13.3023 18.75 12.75V5.75C18.75 5.19771 18.3023 4.75 17.75 4.75L5.75 4.75C5.19772 4.75 4.75 5.19771 4.75 5.75V12.75C4.75 13.3023 5.19771 13.75 5.75 13.75C6.30229 13.75 6.75 14.1977 6.75 14.75C6.75 15.3023 6.30229 15.75 5.75 15.75H4.75C3.64543 15.75 2.75 14.8546 2.75 13.75V4.75C2.75 3.64543 3.64543 2.75 4.75 2.75L18.75 2.75C19.8546 2.75 20.75 3.64543 20.75 4.75V13.75C20.75 14.8546 19.8546 15.75 18.75 15.75Z" fill="currentColor"/>
																		</svg>
																	</span>
																	Download Excel Template
																</a>
															</p>
														</div>
														<div class="separator border-5 my-5"></div>
														<div class="col-md-12 fv-row">
															<button type="submit" id="btn_lnm_v2_contacts_reconciliation" class="btn btn-success">
																<i class="icon-checkmark2"></i> Reconcile
															</button>
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