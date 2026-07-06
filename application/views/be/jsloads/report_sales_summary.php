												<div class="row">
													<div class="col-sm-6 col-xl-2">
														<div class="card card-body bg-success-400 has-bg-image">
															<div class="media">
																<div class="mr-2 align-self-center">
																	<i class="icon-basket icon-3x"></i>
																</div>

																<div class="media-body text-right">
																	<h5 class="font-weight-bold mb-0"><?php echo $default_currency; ?> <?php echo number_format($total_sales_including_tax,2); ?></h5>
																	<span class="text-uppercase font-size-sm"><small>Total Sales (Incl. Tax)</small></span>
																</div>
															</div>
														</div>
													</div>

													<div class="col-sm-6 col-xl-2">
														<div class="card card-body bg-info-400 has-bg-image">
															<div class="media">
																<div class="mr-2 align-self-center">
																	<i class="icon-bag icon-3x"></i>
																</div>

																<div class="media-body text-right">
																	<h5 class="font-weight-bold mb-0"><?php echo $default_currency; ?> <?php echo number_format($total_sales_excluding_tax,2); ?></h5>
																	<span class="text-uppercase font-size-sm"><small>Total Sales (Excl. Tax)</small></span>
																</div>
															</div>
														</div>
													</div>

													<div class="col-sm-6 col-xl-2">
														<div class="card card-body bg-indigo-400 has-bg-image">
															<div class="media">

																<div class="mr-2 align-self-center">
																	<i class="icon-coins icon-3x"></i>
																</div>
																<div class="media-body text-right">
																	<h5 class="font-weight-bold mb-0"><?php echo $default_currency; ?> <?php echo number_format($cost_of_goods_sold,2); ?></h5>
																	<span class="text-uppercase font-size-sm"><small>Cost of Goods Sold</small></span>
																</div>
															</div>
														</div>
													</div>

													<div class="col-sm-6 col-xl-2">
														<div class="card card-body bg-violet-400 has-bg-image">
															<div class="media">
																<div class="mr-2 align-self-center">
																	<i class="icon-coin-pound icon-3x"></i>
																</div>
																<div class="media-body text-right">
																	<h5 class="font-weight-bold mb-0"><?php echo $default_currency; ?> <?php echo number_format(($total_sales_including_tax - $cost_of_goods_sold),2); ?></h5>
																	<span class="text-uppercase font-size-sm"><small>Gross Profit</small></span>
																</div>
															</div>
														</div>
													</div>

													<div class="col-sm-6 col-xl-2">
														<div class="card card-body bg-orange-400 has-bg-image">
															<div class="media">
																<div class="mr-2 align-self-center">
																	<i class="icon-percent icon-3x"></i>
																</div>
																<div class="media-body text-right">
																	<h5 class="font-weight-bold mb-0"><?php if ($total_sales_including_tax == 0){ echo '0.00'; } else { echo number_format(($total_sales_including_tax - $cost_of_goods_sold)/$total_sales_including_tax * 100,2); } ?>%</h5>
																	<span class="text-uppercase font-size-sm"><small>Margin</small></span>
																</div>
															</div>
														</div>
													</div>

													<div class="col-sm-6 col-xl-2">
														<div class="card card-body bg-pink-400 has-bg-image">
															<div class="media">
																<div class="mr-2 align-self-center">
																	<i class="icon-coin-dollar icon-3x"></i>
																</div>
																<div class="media-body text-right">
																	<h5 class="font-weight-bold mb-0"><?php echo $default_currency; ?> <?php echo number_format(($total_sales_including_tax - $total_sales_excluding_tax),2); ?></h5>
																	<span class="text-uppercase font-size-sm"><small>Total Tax</small></span>
																</div>
															</div>
														</div>
													</div>

												</div>

												<script type="text/javascript">
										            google.charts.load('current', {'packages':['corechart']});

										            google.charts.setOnLoadCallback(draw_POS_Sales_Chart);
										            google.charts.setOnLoadCallback(draw_Online_Sales_Chart);

										            var pos_sales_chart;
										            var online_sales_chart;

										            function draw_POS_Sales_Chart() { 
										                function AddNamespaceHandler(){
										                    var svg = $('#div_pos_sales_chart svg');
										                    svg.attr("xmlns", "http://www.w3.org/2000/svg");
										                    svg.css('overflow','visible');
										                } 

										                var date_from = "<?php echo $date_from; ?>";
										                var date_to = "<?php echo $date_to; ?>";
										                var outlet_id = "<?php echo $outlet_id; ?>";

										                var jsonData = $.ajax({ 
										                	type: 'POST',
										                    url: "<?php echo base_url() . 'be/reports/get_pos_sales_summary_chart_data' ?>", 
										                    data: { date_from: date_from, date_to: date_to, outlet_id: outlet_id },
										                    dataType: "json", 
										                    async: false
										                }).responseText;

										                var data = new google.visualization.DataTable(jsonData); 

										                var options = {
										                    fontName: 'Lato',
											                height: 400,
											                fontSize: 12,
											                backgroundColor: 'transparent',
											                chartArea: {
											                    left: '5%',
											                    width: '95%',
											                    height: 350
											                },
											                tooltip: {
											                    textStyle: {
											                        fontName: 'Lato',
											                        fontSize: 13
											                    }
											                },
											                vAxis: {
											                    title: '',
											                    titleTextStyle: {
											                        fontSize: 13,
											                        italic: false,
											                        color: '#333'
											                    },
											                    textStyle: {
											                        color: '#333'
											                    },
											                    baselineColor: '#ccc',
											                    gridlines:{
											                        color: '#eee',
											                        count: 10
											                    },
											                    minValue: 0
											                },
											                hAxis: {
											                    textStyle: {
											                        color: '#333'
											                    }
											                },
											                legend: {
											                    position: 'top',
											                    alignment: 'center',
											                    textStyle: {
											                        color: '#333'
											                    }
											                },
											                series: {
											                    0: { color: '#4DB6AC' },
											                    1: { color: '#b6a2de' }
											                }
										                };

										                var div_pos_sales_chart = document.getElementById('div_pos_sales_chart');

										                pos_sales_chart = new google.visualization.ColumnChart(div_pos_sales_chart);
										                google.visualization.events.addListener(pos_sales_chart, 'ready', AddNamespaceHandler);
										                pos_sales_chart.draw(data,options); 
										            }

										            function draw_Online_Sales_Chart() { 
										                function AddNamespaceHandler(){
										                    var svg = $('#div_online_sales_chart svg');
										                    svg.attr("xmlns", "http://www.w3.org/2000/svg");
										                    svg.css('overflow','visible');
										                } 

										                var date_from = "<?php echo $date_from; ?>";
										                var date_to = "<?php echo $date_to; ?>";
										                var outlet_id = "<?php echo $outlet_id; ?>";

										                var jsonData = $.ajax({ 
										                	type: 'POST',
										                    url: "<?php echo base_url() . 'be/reports/get_online_sales_summary_chart_data' ?>", 
										                    data: { date_from: date_from, date_to: date_to, outlet_id: outlet_id },
										                    dataType: "json", 
										                    async: false
										                }).responseText;

										                var data = new google.visualization.DataTable(jsonData); 

										                var options = {
										                    fontName: 'Lato',
											                height: 400,
											                fontSize: 12,
											                backgroundColor: 'transparent',
											                chartArea: {
											                    left: '5%',
											                    width: '95%',
											                    height: 350
											                },
											                tooltip: {
											                    textStyle: {
											                        fontName: 'Lato',
											                        fontSize: 13
											                    }
											                },
											                vAxis: {
											                    title: '',
											                    titleTextStyle: {
											                        fontSize: 13,
											                        italic: false,
											                        color: '#333'
											                    },
											                    textStyle: {
											                        color: '#333'
											                    },
											                    baselineColor: '#ccc',
											                    gridlines:{
											                        color: '#eee',
											                        count: 10
											                    },
											                    minValue: 0
											                },
											                hAxis: {
											                    textStyle: {
											                        color: '#333'
											                    }
											                },
											                legend: {
											                    position: 'top',
											                    alignment: 'center',
											                    textStyle: {
											                        color: '#333'
											                    }
											                },
											                series: {
											                    0: { color: '#BA68C8' },
											                    1: { color: '#b6a2de' }
											                }
										                };

										                var div_online_sales_chart = document.getElementById('div_online_sales_chart');

										                online_sales_chart = new google.visualization.ColumnChart(div_online_sales_chart);
										                google.visualization.events.addListener(online_sales_chart, 'ready', AddNamespaceHandler);
										                online_sales_chart.draw(data,options); 
										            }
										        </script>
												
												<!-- <script src="<?php echo base_url();?>assets/be/js/demo_charts/google/light/bars/column.js"></script> -->
												<div class="row mt-4">
													<div class="col-md-12">
														<div class="chart-container">
															<div class="chart" id="div_pos_sales_chart"></div>
														</div>
													</div>
												</div>
												<hr>
												<div class="row mt-4">
													<div class="col-md-12">
														<div class="chart-container">
															<div class="chart" id="div_online_sales_chart"></div>
														</div>
													</div>
												</div>