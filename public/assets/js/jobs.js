
/* Disable User */
function jobsTable(type)
{
    $("#type").val(type);
    $('#jobsTable').dataTable()._fnAjaxUpdate();
    $('#jobsTable').dataTable().fnDraw();
}

var Bytes;
var Files;

$(document).ready(function() {

    $("#job").select2({
        placeholder: "Select a Job...."
    });

    var start = $("#start").val();
    var end = $("#end").val();
    var Job = $("#job").select2("val");

     /* Jobs Table */

    var jobsTable = $('#jobsTable').dataTable({

        "sAjaxSource": myPath+"/jobs/getjobs",
        "fnServerParams": function ( aoData ) {
             aoData.push( {"name": "type", "value": $("#type").val()          },
                          {"name": "start", "value": $("#start").val()        },
                          {"name": "end", "value": $("#end").val()            },
                          {"name": "Job", "value": $("#job").select2("val")   }
                        );
        },


        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

            // convert Bytes
            $('td:eq(5)', nRow).html( bytesToSize(aData[5],2) );

            // Code For Full or Incremental Backup
            if ( aData[4] == "I" ) {
                $('td:eq(4)', nRow).html( '<center><i class="icon-inc-backup"></i></center>' );
            } else if ( aData[4] == "F" ) {
                $('td:eq(4)', nRow).html( '<center><i class="icon-full-backup"></i></center>' );
            } else if ( aData[4] == "D" ) {
                $('td:eq(4)', nRow).html( '<center><i class="icon-diferencial-backup"></center>' );
            }

            // Code For Full or Incremental Backup
            if ( aData[7] == "T" ) {
                $('td:eq(7)', nRow).html( '<center><i class="icon-fam-accept"></i></center>' );
            } else if ( aData[7] == "E" ) {
                $('td:eq(7)', nRow).html( '<center><i class="icon-fam-delete"></i></center>' );
            } else if ( aData[7] == "D" ) {
                $('td:eq(7)', nRow).html( '<center><i class="icon-full-backup"></i></center>' );
            }

            return nRow
        },
    });



    $('#date').daterangepicker(
    {
        ranges: {
           'Today': [new Date(), new Date()],
           'Yesterday': [moment().subtract('days', 1), moment().subtract('days', 1)],
           'Last 7 Days': [moment().subtract('days', 6), new Date()],
           'Last 30 Days': [moment().subtract('days', 29), new Date()],
           'This Month': [moment().startOf('month'), moment().endOf('month')],
           'Last Month': [moment().subtract('month', 1).startOf('month'), moment().subtract('month', 1).endOf('month')]
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
         
            $("#start").val (moment(start).format('YYYY-M-D h:mm:ss'));
            $("#end").val   (moment(end).format('YYYY-M-D h:mm:ss'));
        }
    );

   

// create chart
AmCharts.ready(function() {
    
    // SERIAL CHART
    chart = new AmCharts.AmSerialChart();
    chart.dataProvider = chartDataFiles;
    chart.categoryField = "date";
    chart.startDuration = 1;
    chart.addTitle('Transfered Files', 16);
    
    // AXES
    // category
    var categoryAxis = chart.categoryAxis;
    categoryAxis.labelRotation = 90;
    categoryAxis.gridPosition = "start";

    var graph = new AmCharts.AmGraph();
    graph.valueField = "files";
    graph.balloonText = "[[category]]: [[value]]";
    graph.type = "column";
    graph.lineAlpha = 0;
    graph.fillAlphas = 0.8;
    chart.addGraph(graph);

    // WRITE
    chart.write("filesGraphs");

    // SERIAL CHART
    chartBytes = new AmCharts.AmSerialChart();
    chartBytes.dataProvider = chartDataBytes;
    chartBytes.categoryField = "date";
    chartBytes.startDuration = 1;
    chartBytes.addTitle('Transfered Bytes', 16);
    
    // AXES
    // category
    var categoryAxis = chartBytes.categoryAxis;
    categoryAxis.labelRotation = 90;
    categoryAxis.gridPosition = "start";

    var graphBytes = new AmCharts.AmGraph();
    graphBytes.valueField = "bytes";
    graphBytes.balloonText = "[[category]]:[[value]]";
    graphBytes.type = "column";
    graphBytes.lineAlpha = 0;
    graphBytes.fillAlphas = 0.8;
    chartBytes.addGraph(graphBytes);

    // WRITE
    chartBytes.write("bytesGraphs");
});


});