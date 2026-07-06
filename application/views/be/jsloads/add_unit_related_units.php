															<div class="row">
																<div class="col-md-12">
																	<div class="card related-units-card">
																		<div class="card-header pt-2 pb-0">
																			<p class="font-weight-bold text-uppercase">Related Units</p>
																		</div>
																		<div class="card-body">
																			<div class="row">
																				<div class="col-md-8">
																					<h6 class="text-grey text-uppercase">&nbsp;</h6>
																				</div>
																				<div class="col-md-4">
																					<h6 class="text-grey text-uppercase"># of Units in <i class="icon-info22 text-primary ml-1" data-popup="popover" title="" data-trigger="hover" data-content="Enter the number of base units in this related unit. Leave blank for related units that do not apply"></i></h6>
																				</div>
																			</div>
																			<?php foreach ($related_units as $row): ?>
																				<div class="row mb-2">
																					<div class="col-md-8">
																						<?php echo $row->unit_name; ?> (<?php echo $row->unit_code; ?>)
																					</div>
																					<div class="col-md-4">
																						<input name="ru_unit_id[]" type="hidden" value="<?php echo $row->unit_id; ?>">
																						<input name="ru_conversion_factor[]" type="number" class="form-control form-control-sm" min="0">
																					</div>
																				</div>
																			<?php endforeach; ?>
																		</div>
																	</div>
																</div>
															</div>

															<script type="text/javascript">
																$('[data-popup="popover"]').popover();
															</script>