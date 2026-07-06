                    <div class="nk-content">
                        <div class="container-fluid">
                            <div class="nk-content-inner">
                                <div class="nk-content-body">
                                    <div class="nk-block-head nk-block-head-sm">
                                        <div class="nk-block-between">
                                            <div class="nk-block-head-content"><h4 class="nk-block-title page-title">Dashboard</h4></div>
                                        </div>
                                    </div>
                                    <div class="nk-block">

                                        <!-- <div class="row">
                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-green"><em class="icon ni ni-cart"></em></span>
                                                    <div class="info-box-content">
                                                        <span class="text-bold text-uppercase">Total Sales Amount</span>
                                                        <span class="info-box-number">KES 0.00</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-yellow"><em class="icon ni ni-coin-alt"></em></span>
                                                    <div class="info-box-content">
                                                        <span class="text-bold text-uppercase">Total Sales Due</span>
                                                        <span class="info-box-number">KES 0.00</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-aqua"><em class="icon ni ni-coin"></em></span>
                                                    <div class="info-box-content">
                                                        <span class="text-bold text-uppercase">Total Payments Received</span>
                                                        <span class="info-box-number">KES 0.00</span>
                                                    </div>
                                                </div>
                                            </div>                                            
                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-red"><em class="icon ni ni-minus-c"></em></span>
                                                    <div class="info-box-content">
                                                        <span class="text-bold text-uppercase">Total Expense Amount</span>
                                                        <span class="info-box-number">KES 0.00</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> -->

                                        <div class="row">
                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-green"><em class="icon ni ni-cart"></em></span>
                                                    <div class="info-box-content">
                                                        <span class="text-bold text-uppercase">Todays Total Sales</span>
                                                        <span class="info-box-number"><?php echo $default_currency; ?> <?php echo number_format($today_total_sales,2); ?></span>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-aqua"><em class="icon ni ni-coin"></em></span>
                                                    <div class="info-box-content">
                                                        <span class="text-bold text-uppercase">Todays Total Payments</span>
                                                        <span class="info-box-number"><?php echo $default_currency; ?> <?php echo number_format($today_total_sales_payments,2); ?></span>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-yellow"><em class="icon ni ni-coin-alt"></em></span>
                                                    <div class="info-box-content">
                                                        <span class="text-bold text-uppercase">Todays Sales Due</span>
                                                        <span class="info-box-number"><?php echo $default_currency; ?> <?php echo number_format($today_total_sales_due,2); ?></span>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                            
                                            
                                            
                                            <!-- /.col -->
                                            <div class="col-md-3 col-sm-6 col-xs-12">
                                                <div class="info-box">
                                                    <span class="info-box-icon bg-red"><em class="icon ni ni-minus-c"></em></span>
                                                    <div class="info-box-content">
                                                        <span class="text-bold text-uppercase">Todays Total Expenses</span>
                                                        <span class="info-box-number"><?php echo $default_currency; ?> <?php echo number_format($today_total_expenses,2); ?></span>
                                                    </div>
                                                    <!-- /.info-box-content -->
                                                </div>
                                                <!-- /.info-box -->
                                            </div>
                                            <!-- /.col -->
                                        </div>

                                        <div class="row">
                                            <div class="col-lg-3 col-xs-6">
                                                <div class="small-box bg-dream-pink">
                                                    <div class="inner text-uppercase">
                                                        <h3><?php echo number_format($total_sales_orders); ?></h3>
                                                        <p>Sales Orders</p>
                                                    </div>
                                                    <div class="icon">
                                                         <em class="ion ni ni-file-docs"></em>
                                                    </div>

                                                    <a href="#" class="small-box-footer text-uppercase">View <i class="fa fa-arrow-circle-right"></i> </a>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-xs-6">
                                                <div class="small-box bg-dream-purple">
                                                    <div class="inner text-uppercase">
                                                        <h3><?php echo number_format($total_held_orders); ?></h3>
                                                        <p>Held Orders</p>
                                                    </div>
                                                    <div class="icon">
                                                         <em class="ion ni ni-file-docs"></em>
                                                    </div>

                                                    <a href="#" class="small-box-footer text-uppercase">View <i class="fa fa-arrow-circle-right"></i> </a>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-xs-6">
                                                <div class="small-box bg-dream-maroon">
                                                    <div class="inner text-uppercase">
                                                        <h3><?php echo number_format($total_products); ?></h3>
                                                        <p>Products</p>
                                                    </div>
                                                    <div class="icon">
                                                        <i class="ion ni ni-package"></i>
                                                    </div>

                                                    <a href="#" class="small-box-footer text-uppercase">View <i class="fa fa-arrow-circle-right"></i> </a>
                                                </div>
                                            </div>
                                            <div class="col-lg-3 col-xs-6">
                                                <div class="small-box bg-dream-green">
                                                    <div class="inner text-uppercase">
                                                        <h3><?php echo number_format($total_customers); ?></h3>
                                                        <p>Customers</p>
                                                    </div>
                                                    <div class="icon">
                                                        <em class="ion ni ni-users"></em>
                                                    </div>

                                                    <a href="#" class="small-box-footer text-uppercase">View <i class="fa fa-arrow-circle-right"></i> </a>
                                                </div>
                                            </div>

                                            <!-- fix for small devices only -->
                                            <div class="clearfix visible-sm-block"></div>

                                            <!-- /.col -->
                                        </div>

                                        <div class="row">
                                            <div class="col-md-8">
                                                <!-- BAR CHART -->
                                                <div class="box box-success">
                                                    <div class="box-header with-border">
                                                        <h3 class="box-title text-uppercase">Sales Bar Chart</h3>                                                        
                                                    </div>
                                                    <div class="box-body">
                                                        <div class="chart">
                                                            <canvas id="barChart" style="height: 230px;"></canvas>
                                                        </div>
                                                    </div>
                                                    <!-- /.box-body -->
                                                </div>
                                                <!-- /.box -->
                                            </div>
                                            <!-- /.col -->
                                            <div class="col-md-4">
                                                <!-- PRODUCT LIST -->
                                                <div class="box box-primary">
                                                    <div class="box-header">
                                                        <h3 class="box-title text-uppercase">Low Stock Items <a href="<?php echo base_url();?>pos/sales/low_stock" class="ml-1 badge badge-success pull-right">See All <em class="icon ni ni-chevron-right"></em></a></h3>
                                                    </div>
                                                    <!-- /.box-header -->
                                                    <div class="box-body table-responsive">
                                                        <table id="example2" class="table table-bordered table-hover">
                                                            <thead>
                                                                <tr class="bg-blue">
                                                                    <th>#</th>
                                                                    <th>Product</th>
                                                                    <th>Stock</th>
                                                                    <th>Reorder Level</th>
                                                                </tr>
                                                            </thead>
                                                            <tbody>
                                                                <?php $count = 1; ?>
                                                                <?php foreach ($low_stock_list as $row): ?>
                                                                    <tr>
                                                                        <td><?php echo $count; ?></td>
                                                                        <td><?php echo $row->product_name; ?></td>
                                                                        <td><?php echo number_format($row->available_stock,2); ?></td>
                                                                        <td><?php echo number_format($row->reorder_level,2); ?></td>
                                                                    </tr>
                                                                    <?php $count++; ?>
                                                                <?php endforeach; ?>
                                                                <!--  -->
                                                                
                                                            </tbody>
                                                        </table>
                                                    </div>
                                                    <!-- /.box-body -->
                                                </div>
                                                <!-- /.box -->
                                            </div>
                                            <!-- /.col -->
                                        </div>

                                        <script type="text/javascript">
                                            $(document).ready(function() {
                                                //-------------
                                                //- BAR CHART -
                                                //-------------
                                                var barChartData = {
                                                  labels: ["January", "February", "March", "April", "May", "June", "July","August","September","October","November","December"],
                                                  datasets: [                                                    
                                                    {
                                                      label: "Total Sales (in KES)",
                                                      backgroundColor: "rgba(60,141,188,0.9)",
                                                      borderColor: "rgba(60,141,188,0.8)",
                                                      pointColor: "#3b8bba",
                                                      pointStrokeColor: "rgba(60,141,188,1)",
                                                      pointHighlightFill: "#fff",
                                                      pointHighlightStroke: "rgba(60,141,188,1)",
                                                      data: <?php echo json_encode($monthly_sales_statistics); ?>
                                                    }
                                                  ]
                                                };
                                                var barChartCanvas = $("#barChart").get(0).getContext("2d");
                                                new Chart(barChart, {
                                                    type: 'bar',
                                                    data: barChartData,
                                                    options: {
                                                        scaleBeginAtZero: true,
                                                        scaleShowGridLines: true,
                                                        scaleGridLineColor: "rgba(0,0,0,.05)",
                                                        scaleGridLineWidth: 1,
                                                        scaleShowHorizontalLines: true,
                                                        scaleShowVerticalLines: true,
                                                        barShowStroke: true,
                                                        barStrokeWidth: 2,
                                                        barValueSpacing: 5,
                                                        barDatasetSpacing: 1,
                                                        legendTemplate: "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>",
                                                        responsive: true,
                                                        maintainAspectRatio: true
                                                    }
                                                });
                                            });
                                        </script>

