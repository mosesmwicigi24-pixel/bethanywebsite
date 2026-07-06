/* ------------------------------------------------------------------------------
 *
 *  # Google Visualization - columns
 *
 *  Google Visualization column chart demonstration
 *
 * ---------------------------------------------------------------------------- */


// Setup module
// ------------------------------

var GoogleColumnBasic = function() {


    //
    // Setup module components
    //

    // Column chart
    var _googleColumnBasic = function() {
        if (typeof google == 'undefined') {
            console.warn('Warning - Google Charts library is not loaded.');
            return;
        }

        // Initialize chart
        google.charts.load('current', {
            callback: function () {

                // Draw chart
                drawColumn();

                // drawColumn2();

                // Resize on sidebar width change
               var sidebarToggle = document.querySelector('.sidebar-control');
                sidebarToggle && sidebarToggle.addEventListener('click', drawColumn);

                // Resize on window resize
                var resizeColumn;
                window.addEventListener('resize', function() {
                    clearTimeout(resizeColumn);
                    resizeColumn = setTimeout(function () {
                        drawColumn();
                    }, 200);
                });
            },
            packages: ['corechart']
        });

        // Chart settings
        function drawColumn() {

            // Define charts element
            var line_chart_element = document.getElementById('google-column');

            // Data
            var data = google.visualization.arrayToDataTable([
                ['Day', 'Count'],
                ['01-05-2021',  57],
                ['02-05-2021',  17],
                ['05-05-2021',  20],
                ['10-05-2021',  43]
            ]);


            // Options
            var options_column = {
                fontName: 'Cabin',
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
                        fontName: 'Cabin',
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
                    0: { color: '#2ec7c9' },
                    1: { color: '#b6a2de' }
                }
            };

            // Draw chart
            var column = new google.visualization.ColumnChart(line_chart_element);
            column.draw(data, options_column);
        }

        function drawColumn2() {

            // Define charts element
            var line_chart_element = document.getElementById('google-column2');

            // Data
            var data = google.visualization.arrayToDataTable([
                ['Day', 'Count'],
                ['01-05-2021',  57],
                ['02-05-2021',  17],
                ['05-05-2021',  20],
                ['10-05-2021',  43],
                ['11-05-2021',  12],
                ['12-05-2021',  23],
                ['14-05-2021',  35]
            ]);


            // Options
            var options_column = {
                fontName: 'Cabin',
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
                        fontName: 'Cabin',
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
                    0: { color: '#b6a2de' },
                    1: { color: '#b6a2de' }
                }
            };

            // Draw chart
            var column = new google.visualization.ColumnChart(line_chart_element);
            column.draw(data, options_column);
        }
    };


    //
    // Return objects assigned to module
    //

    return {
        init: function() {
            _googleColumnBasic();
        }
    }
}();


// Initialize module
// ------------------------------

GoogleColumnBasic.init();
