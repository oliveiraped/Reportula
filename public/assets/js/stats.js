var Bytes;
var Files;

$(document).ready(function() {

     /* Stats Table */

    var statsTable = $('#statsTable').dataTable({

        "sAjaxSource": myPath+"/stats/gethoursstas",
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

            // convert Bytes
            $('td:eq(2)', nRow).html( bytesToSize(aData[2],2) );
            $('td:eq(4)', nRow).html( bytesToSize(aData[4],2) );

            return nRow
        }
    });



    $('#date').daterangepicker(
    {
        ranges: {
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')],
           'Last 6 Months': [moment().subtract('days', 29).calendar()], 
           'Last Year ': [moment().subtract('days', 365).calendar()],
           'Last Two Years': [moment().subtract('days', 720).calendar()]
           
           
        },
        opens: 'left',
                        format: 'YYYY-MM-DD',
                        separator: ' - ',
                        startDate: moment().subtract('days', 29),
                        endDate: new Date(),
                        locale: {
                            applyLabel: 'Submit',
                            fromLabel: 'From',
                            toLabel: 'To',
                            customRangeLabel: 'Custom Range',
                            daysOfWeek: ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr','Sa'],
                            monthNames: ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'],
                            firstDay: 1
                        },
                        showWeekNumbers: true,
                        buttonClasses: ['btn-danger'],
                        dateLimit: false
                     },
        function(start, end){
            $('#date span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));
         
           // $("#start").val (moment(start).format('YYYY-M-D h:mm:ss'));
           // $("#end").val   (moment(end).format('YYYY-M-D h:mm:ss'));
        }
    );

   
    var chart;
    var graph;
  


    AmCharts.ready(function () {
        // SERIAL CHART
        chart = new AmCharts.AmSerialChart();
        chart.pathToImages = "../assets/img/amcharts/";
        chart.dataProvider = chartData;
        chart.marginLeft = 5;
        chart.categoryField = "year";
        chart.dataDateFormat = "YYYY-MM-DD";

        // listen for "dataUpdated" event (fired when chart is inited) and call zoomChart method when it happens
        //chart.addListener("dataUpdated", zoomChart);

        // AXES
        // category
        var categoryAxis = chart.categoryAxis;
            categoryAxis.parseDates = true; // as our data is date-based, we set parseDates to true
            categoryAxis.minPeriod = "DD"; // our data is yearly, so we set minPeriod to YYYY
            categoryAxis.dashLength = 5;
            categoryAxis.minorGridEnabled = true;
            categoryAxis.minorGridAlpha = 0.1;
            categoryAxis.dateFormats = [{
                    period: 'DD',
                    format: 'DD'
                }, {
                    period: 'WW',
                    format: 'MMM DD'
                }, {
                    period: 'MM',
                    format: 'MMM'
                }, {
                    period: 'YYYY',
                    format: 'YYYY'
                }];

        

        // GRAPH                
        graph = new AmCharts.AmGraph();
            graph.type = "smoothedLine"; // this line makes the graph smoothed line.
            graph.lineColor = "#d1655d";
            graph.negativeLineColor = "#637bb6"; // this line makes the graph to change color when it drops below 0
            graph.bullet = "round";
            graph.bulletSize = 8;
            graph.bulletBorderColor = "#FFFFFF";
            graph.bulletBorderAlpha = 1;
            graph.bulletBorderThickness = 2;
            graph.lineThickness = 1;
            graph.valueField = "bytes";
            graph.balloonText = "[[category]]<br><b><span style='font-size:14px;'>[[value]]</span></b>";

            chart.addGraph(graph);

        // CURSOR
        var chartCursor = new AmCharts.ChartCursor();
            chartCursor.cursorAlpha = 0;
            chartCursor.cursorPosition = "mouse";
            chartCursor.categoryBalloonDateFormat = "YYYY-MM-DD";
            chart.addChartCursor(chartCursor);

        // SCROLLBAR
        var chartScrollbar = new AmCharts.ChartScrollbar();
            chart.addChartScrollbar(chartScrollbar);

        // value For Bytes
        var valueAxis = new AmCharts.ValueAxis();
            valueAxis.title = "Bytes";
            valueAxis.axisAlpha = 0;
            valueAxis.inside = true;
            valueAxis.dashLength = 3;
            chart.addValueAxis(valueAxis);

        // WRITE CHART BYTES
        chart.write("chartBytes");
  });

  // this method is called when chart is first inited as we listen for "dataUpdated" event
    function zoomChart() {
        // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
        chart.zoomToDates(new Date(1972, 0), new Date(1984, 0));
    }

 AmCharts.ready(function () {
        // SERIAL CHART
        chart = new AmCharts.AmSerialChart();
        chart.pathToImages = "../assets/img/amcharts/";
        chart.dataProvider = chartData;
        chart.marginLeft = 5;
        chart.categoryField = "year";
        chart.dataDateFormat = "YYYY-MM-DD";

       
        // AXES
        // category
        var categoryAxis = chart.categoryAxis;
            categoryAxis.parseDates = true; // as our data is date-based, we set parseDates to true
            categoryAxis.minPeriod = "DD"; // our data is yearly, so we set minPeriod to YYYY
            categoryAxis.dashLength = 5;
            categoryAxis.minorGridEnabled = true;
            categoryAxis.minorGridAlpha = 0.1;
            categoryAxis.dateFormats = [{
                    period: 'DD',
                    format: 'DD'
                }, {
                    period: 'WW',
                    format: 'MMM DD'
                }, {
                    period: 'MM',
                    format: 'MMM'
                }, {
                    period: 'YYYY',
                    format: 'YYYY'
                }];

        

        // GRAPH                
        graph = new AmCharts.AmGraph();
            graph.type = "smoothedLine"; // this line makes the graph smoothed line.
            graph.lineColor = "#d1655d";
            graph.negativeLineColor = "#637bb6"; // this line makes the graph to change color when it drops below 0
            graph.bullet = "round";
            graph.bulletSize = 8;
            graph.bulletBorderColor = "#FFFFFF";
            graph.bulletBorderAlpha = 1;
            graph.bulletBorderThickness = 2;
            graph.lineThickness = 1;
            graph.valueField = "files";
            graph.balloonText = "[[category]]<br><b><span style='font-size:14px;'>[[value]]</span></b>";

            chart.addGraph(graph);

        // CURSOR
        var chartCursor = new AmCharts.ChartCursor();
            chartCursor.cursorAlpha = 0;
            chartCursor.cursorPosition = "mouse";
            chartCursor.categoryBalloonDateFormat = "YYYY-MM-DD";
            chart.addChartCursor(chartCursor);

        // SCROLLBAR
        var chartScrollbar = new AmCharts.ChartScrollbar();
            chart.addChartScrollbar(chartScrollbar);

        // value For Bytes
        var valueAxis = new AmCharts.ValueAxis();
            valueAxis.title = "Files";
            valueAxis.axisAlpha = 0;
            valueAxis.inside = true;
            valueAxis.dashLength = 3;
            chart.addValueAxis(valueAxis);

        // WRITE CHART BYTES
        chart.write("chartFiles");
  });

  // this method is called when chart is first inited as we listen for "dataUpdated" event
    function zoomChart() {
        // different zoom methods can be used - zoomToIndexes, zoomToDates, zoomToCategoryValues
        chart.zoomToDates(new Date(1972, 0), new Date(1984, 0));
    }

});