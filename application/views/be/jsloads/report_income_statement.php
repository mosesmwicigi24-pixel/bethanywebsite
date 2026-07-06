						<div class="row mt-3">
							<div class="col-md-2"></div>
							<div class="col-md-8">
								<div class="card">

									<div class="card-body p-4">
										<div class="row mb-3">
											<div class="col-sm-12 text-center">
												<div class="mb-3">
													<?php foreach ($store_information as $row2): ?>
														<?php if($row2->store_logo != '' && file_exists("./uploads/store_logo/" . $row2->store_logo)): ?>
															<img src="<?php echo base_url();?>uploads/store_logo/<?php echo $row2->store_logo; ?>" class="mb-1 mt-1" alt="" style="height: 40px;">
														<?php endif; ?>
							 							<ul class="list list-unstyled mb-0">
															<li><h5 class="mt-0 mb-0"><?php echo $row2->store_name; ?></h5></li>
															<li><?php echo $row2->email_address; ?></li>
															<li><?php echo $row2->phone_number; ?></li>
														</ul>
													<?php endforeach; ?>
												</div>
												<div class="mb-3">
													<h3>Income Statement for <?php foreach ($store_information as $row2){ echo $row2->store_name; } ?></h3>
													<h5>for the period between <?php echo date('d M, Y', strtotime($date_from)); ?> to <?php echo date('d M, Y', strtotime($date_to)); ?></h5>
												</div>
											</div>
										</div>
										<div class="table-responsive">
										    <table class="table">
	                                            <tbody>
	                                                <tr class="font-14">
	                                                    <td width="70%" class="font-weight-bold">Sales</td>
	                                                    <td width="30%"><?php echo $default_currency; ?> <?php echo number_format($total_sales,2); ?></td>
	                                                </tr>
	                                                <tr class="font-14">
	                                                    <td width="70%" class="font-weight-bold">Tax (VAT)</td>
	                                                    <td width="30%"><?php echo $default_currency; ?> <?php echo number_format($total_sales_tax,2); ?></td>
	                                                </tr>
	                                                <tr class="font-14">
	                                                    <td width="70%" class="font-weight-bold">Cost of Goods Sold</td>
	                                                    <td width="30%"><?php echo $default_currency; ?> <?php echo number_format($cost_of_goods_sold,2); ?></td>
	                                                </tr>
	                                                <?php $gross_profit = $total_sales - $total_sales_tax - $cost_of_goods_sold; ?>
	                                                <tr class="font-14 mb-4">
	                                                    <td width="70%" class="font-weight-bold text-right">Gross Profit:</td>
	                                                    <td width="30%"><?php echo $default_currency; ?> <?php echo number_format($gross_profit,2); ?></td>
	                                                </tr>  
	                                                <tr class="font-14">
	                                                    <td width="70%" class="font-weight-bold">Running Expenses</td>
	                                                    <td width="30%"><?php echo $default_currency; ?> <?php echo number_format($total_expenses,2); ?></td>
	                                                </tr>
	                                                <?php $proprietor_salary = 0.3 * $gross_profit; ?>
	                                                <tr class="font-14">
	                                                    <td width="70%" class="font-weight-bold">Proprietor's Salary</td>
	                                                    <td width="30%"><?php echo $default_currency; ?> <?php echo number_format($proprietor_salary,2); ?></td>
	                                                </tr>   
	                                                <?php $operating_income = $gross_profit - $total_expenses; ?>   
	                                                <tr class="font-14 mb-4">
	                                                    <td width="70%" class="font-weight-bold text-right">Operating Income:</td>
	                                                    <td width="30%"><?php echo $default_currency; ?> <?php echo number_format($operating_income,2); ?></td>
	                                                </tr>   
	                                                <tr class="font-14">
	                                                    <td width="70%" class="font-weight-bold">Other Income</td>
	                                                    <td width="30%"><?php echo $default_currency; ?> <?php echo number_format(0,2); ?></td>
	                                                </tr>
	                                                <?php $tithe = 0.1 * $operating_income; ?> 
	                                                <tr class="font-14">
	                                                    <td width="70%" class="font-weight-bold">Other Expenses (Tithe)</td>
	                                                    <td width="30%"><?php echo $default_currency; ?> <?php echo number_format($tithe,2); ?></td>
	                                                </tr> 
	                                                <!-- <tr class="font-14">
	                                                    <td width="70%" class="font-weight-bold">Income Before Tax</td>
	                                                    <td width="30%"><?php echo $default_currency; ?> <?php echo number_format(0,2); ?></td>
	                                                </tr> -->   
	                                                <?php $net_profit = $operating_income -$tithe; ?>
	                                                <tr class="font-14 mb-4">
	                                                    <td width="70%" class="font-weight-bold text-right">Net Profit:</td>
	                                                    <td width="30%"><?php echo $default_currency; ?> <?php echo number_format($net_profit,2); ?></td>
	                                                </tr>                                         
	                                            </tbody>
	                                        </table>
										</div>
									</div>

									

									

									<div class="card-footer text-center">
										<span class="text-muted">Generated on <?php echo date('d M, Y H:i:s'); ?></span>
									</div>
								</div>
							</div>
							<div class="col-md-1"></div>
						</div>