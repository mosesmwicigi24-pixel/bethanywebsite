												<div class="row">
													<div class="col-sm-6 col-xl-2">
														<div class="card card-body bg-pink-400 has-bg-image">
															<div class="media">
																<div class="mr-2 align-self-center">
																	<i class="icon-coins icon-3x"></i>
																</div>

																<div class="media-body text-right">
																	<h5 class="font-weight-bold mb-0"><?php echo $default_currency; ?> <?php echo number_format($total_payments,2); ?></h5>
																	<span class="text-uppercase font-size-sm"><small>Total Payments</small></span>
																</div>
															</div>
														</div>
													</div>

													<div class="col-sm-6 col-xl-2">
														<div class="card card-body bg-indigo-400 has-bg-image">
															<div class="media">
																<div class="mr-2 align-self-center">
																	<i class="icon-coin-pound icon-3x"></i>
																</div>

																<div class="media-body text-right">
																	<h5 class="font-weight-bold mb-0"><?php echo $default_currency; ?> <?php echo number_format($total_pos_payments,2); ?></h5>
																	<span class="text-uppercase font-size-sm"><small>POS Payments</small></span>
																</div>
															</div>
														</div>
													</div>

													<div class="col-sm-6 col-xl-2">
														<div class="card card-body bg-orange-400 has-bg-image">
															<div class="media">

																<div class="mr-2 align-self-center">
																	<i class="icon-coin-dollar icon-3x"></i>
																</div>
																<div class="media-body text-right">
																	<h5 class="font-weight-bold mb-0"><?php echo $default_currency; ?> <?php echo number_format($total_online_payments,2); ?></h5>
																	<span class="text-uppercase font-size-sm"><small>Online Payments</small></span>
																</div>
															</div>
														</div>
													</div>

													<div class="col-sm-6 col-xl-2">
														<div class="card card-body bg-violet-400 has-bg-image">
															<div class="media">
																<div class="mr-2 align-self-center">
																	<i class="icon-calculator3 icon-3x"></i>
																</div>
																<div class="media-body text-right">
																	<h5 class="font-weight-bold mb-0"><?php echo number_format($total_payment_transactions); ?></h5>
																	<span class="text-uppercase font-size-sm"><small>Transactions</small></span>
																</div>
															</div>
														</div>
													</div>

													<div class="col-sm-6 col-xl-2">
														<div class="card card-body bg-success-400 has-bg-image">
															<div class="media">
																<div class="mr-2 align-self-center">
																	<i class="icon-coin-euro icon-3x"></i>
																</div>
																<div class="media-body text-right">
																	<h5 class="font-weight-bold mb-0"><?php echo $default_currency; ?> <?php if ($total_payment_transactions == 0){ echo '0.00'; } else { echo number_format($total_payments/$total_payment_transactions,2); } ?></h5>
																	<span class="text-uppercase font-size-sm"><small>Average Pay</small></span>
																</div>
															</div>
														</div>
													</div>

													<div class="col-sm-6 col-xl-2">
														<div class="card card-body bg-teal-400 has-bg-image">
															<div class="media">
																<div class="mr-2 align-self-center">
																	<i class="icon-coin-dollar icon-3x"></i>
																</div>
																<div class="media-body text-right">
																	<h5 class="font-weight-bold mb-0"><?php echo $default_currency; ?> <?php echo number_format($total_payments_balance,2); ?></h5>
																	<span class="text-uppercase font-size-sm"><small>Payments Balance</small></span>
																</div>
															</div>
														</div>
													</div>

												</div>

												<script type="text/javascript">
										            google.charts.load('current', {'packages':['corechart']});

										            google.charts.setOnLoadCallback(draw_POS_Payments_Donut);
										            google.charts.setOnLoadCallback(draw_Online_Payments_Donut);
										            google.charts.setOnLoadCallback(draw_POS_Payments_Chart);
										            google.charts.setOnLoadCallback(draw_Online_Payments_Chart);

										            var pos_payments_donut;
										            var pos_payments_chart;
										            var online_payments_donut;
										            var online_payments_chart;

										            function draw_POS_Payments_Donut() { 
										                function AddNamespaceHandler(){
										                    var svg = $('#div_pos_payments_donut svg');
										                    svg.attr("xmlns", "http://www.w3.org/2000/svg");
										                    svg.css('overflow','visible');
										                } 

										                var date_from = "<?php echo $date_from; ?>";
										                var date_to = "<?php echo $date_to; ?>";

										                var jsonData = $.ajax({ 
										                	type: 'POST',
										                    url: "<?php echo base_url() . 'be/reports/get_pos_payments_donut_data' ?>", 
										                    data: { date_from: date_from, date_to: date_to },
										                    dataType: "json", 
										                    async: false
										                }).responseText;

										                var div_pos_payments_donut = document.getElementById('div_pos_payments_donut');

											            var data = new google.visualization.DataTable(jsonData); 

											            // Options
											            var options_donut = {
											                fontName: 'Lato',
											                pieHole: 0.55,
											                height: 300,
											                backgroundColor: 'transparent',
											                colors: [
											                    '#2ec7c9','#b6a2de','#5ab1ef','#ffb980',
											                    '#d87a80','#8d98b3','#e5cf0d','#97b552'
											                ],
											                chartArea: {
											                    left: 50,
											                    width: '90%',
											                    height: '90%'
											                }
											            };
											            
											            // Instantiate and draw our chart, passing in some options.
											            pos_payments_donut = new google.visualization.PieChart(div_pos_payments_donut);
											            pos_payments_donut.draw(data, options_donut);
										            }

										            function draw_POS_Payments_Chart() { 
										                function AddNamespaceHandler(){
										                    var svg = $('#div_pos_payments_chart svg');
										                    svg.attr("xmlns", "http://www.w3.org/2000/svg");
										                    svg.css('overflow','visible');
										                } 

										                var date_from = "<?php echo $date_from; ?>";
										                var date_to = "<?php echo $date_to; ?>";

										                var jsonData = $.ajax({ 
										                	type: 'POST',
										                    url: "<?php echo base_url() . 'be/reports/get_pos_payments_summary_chart_data' ?>", 
										                    data: { date_from: date_from, date_to: date_to },
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

										                var div_pos_payments_chart = document.getElementById('div_pos_payments_chart');

										                pos_payments_chart = new google.visualization.ColumnChart(div_pos_payments_chart);
										                google.visualization.events.addListener(pos_payments_chart, 'ready', AddNamespaceHandler);
										                pos_payments_chart.draw(data,options); 
										            }

										            function draw_Online_Payments_Donut() { 
										                function AddNamespaceHandler(){
										                    var svg = $('#div_online_payments_donut svg');
										                    svg.attr("xmlns", "http://www.w3.org/2000/svg");
										                    svg.css('overflow','visible');
										                } 

										                var date_from = "<?php echo $date_from; ?>";
										                var date_to = "<?php echo $date_to; ?>";

										                var jsonData = $.ajax({ 
										                	type: 'POST',
										                    url: "<?php echo base_url() . 'be/reports/get_online_payments_donut_data' ?>", 
										                    data: { date_from: date_from, date_to: date_to },
										                    dataType: "json", 
										                    async: false
										                }).responseText;

										                var div_online_payments_donut = document.getElementById('div_online_payments_donut');

											            var data = new google.visualization.DataTable(jsonData); 

											            // Options
											            var options_donut = {
											                fontName: 'Lato',
											                pieHole: 0.55,
											                height: 300,
											                backgroundColor: 'transparent',
											                colors: [
											                    '#2ec7c9','#b6a2de','#5ab1ef','#ffb980',
											                    '#d87a80','#8d98b3','#e5cf0d','#97b552'
											                ],
											                chartArea: {
											                    left: 50,
											                    width: '90%',
											                    height: '90%'
											                }
											            };
											            
											            // Instantiate and draw our chart, passing in some options.
											            online_payments_donut = new google.visualization.PieChart(div_online_payments_donut);
											            online_payments_donut.draw(data, options_donut);
										            }

										            function draw_Online_Payments_Chart() { 
										                function AddNamespaceHandler(){
										                    var svg = $('#div_online_payments_chart svg');
										                    svg.attr("xmlns", "http://www.w3.org/2000/svg");
										                    svg.css('overflow','visible');
										                } 

										                var date_from = "<?php echo $date_from; ?>";
										                var date_to = "<?php echo $date_to; ?>";

										                var jsonData = $.ajax({ 
										                	type: 'POST',
										                    url: "<?php echo base_url() . 'be/reports/get_online_payments_summary_chart_data' ?>", 
										                    data: { date_from: date_from, date_to: date_to },
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
											                    onlineition: 'top',
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

										                var div_online_payments_chart = document.getElementById('div_online_payments_chart');

										                online_payments_chart = new google.visualization.ColumnChart(div_online_payments_chart);
										                google.visualization.events.addListener(online_payments_chart, 'ready', AddNamespaceHandler);
										                online_payments_chart.draw(data,options); 
										            }										            
										        </script>

										        <div class="row mt-4">
										        	<div class="col-sm-6">
										        		<div class="card rounded-top-0">							
															<div class="card-header bg-transparent header-elements-inline p-2">
																<h6 class="card-title font-weight-600"><i class="icon-coins mr-1"></i> POS Payments</h6>			
															</div>
															<div class="card-body">
																<div class="row mt">
																	<div class="col-md-12">
																		<div class="chart-container">
																			<div class="chart" id="div_pos_payments_donut"></div>
																		</div>
																	</div>
																</div>
																<hr>
																<div class="row mt-4">
																	<div class="col-md-12">
																		<div class="chart-container">
																			<div class="chart" id="div_pos_payments_chart"></div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
										        	</div>
										        	<div class="col-sm-6">
										        		<div class="card rounded-top-0">							
															<div class="card-header bg-transparent header-elements-inline p-2">
																<h6 class="card-title font-weight-600"><i class="icon-coins mr-1"></i> Online Payments</h6>			
															</div>
															<div class="card-body">
																<div class="row mt">
																	<div class="col-md-12">
																		<div class="chart-container">
																			<div class="chart" id="div_online_payments_donut"></div>
																		</div>
																	</div>
																</div>
																<hr>
																<div class="row mt-4">
																	<div class="col-md-12">
																		<div class="chart-container">
																			<div class="chart" id="div_online_payments_chart"></div>
																		</div>
																	</div>
																</div>
															</div>
														</div>
										        	</div>
										        </div>

												