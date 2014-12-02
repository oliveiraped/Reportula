
// create chart
AmCharts.ready(function() {
    
    // PIE CHART
    chart = new AmCharts.AmPieChart();
    chart.dataProvider = chartData;
    chart.titleField = "name";
    chart.valueField = "numvols";
    chart.addTitle('Volumes & Pools', 16);
    
    // LEGEND
    legend = new AmCharts.AmLegend();
    legend.align = "center";
    legend.markerType = "circle";
    chart.addLegend(legend);

    // WRITE
    chart.write("volumesGraphs");
});

/* Disable User */
function dashboardTable(type)
{
    $("#type").val(type);
    $('#dashboardStatsTable').dataTable()._fnAjaxUpdate();
    $('#dashboardStatsTable').dataTable().fnDraw();
}

var chart;

$(document).ready(function() {

  
   

    // ToolTips for Calendars
    $('#day').tooltip(options)
    $('#week').tooltip(options)
    $('#month').tooltip(options)


    /* Jobs Table */
    var jobsTable = $('#dashboardStatsTable').dataTable({

        "sAjaxSource": myPath+"/dashboard/getjobs",
        "fnServerParams": function ( aoData ) {
             aoData.push( {"name": "date", "value": $("#datetype").val() },
                          {"name": "type", "value": $("#type").val() } );
        },

        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

            // convert Bytes
            $('td:eq(5)', nRow).html( bytesToSize(aData[5],2) );

            // Code For Full or Incremental and Diferencial Backup
            if ( aData[4] == "I" ) {
                $('td:eq(4)', nRow).html( '<center><i class="icon-inc-backup"></i></center>' );
            } else if ( aData[4] == "F" ) {
                $('td:eq(4)', nRow).html( '<center><i class="icon-full-backup"></i></center>' );
            } else if ( aData[4] == "D" ) {
                $('td:eq(4)', nRow).html( '<center><i class="icon-diferencial-backup"></i></center>' );
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


    /* Volumes Table */
    var volumesTable = $('#dashboardTableVolumes').dataTable({

        "sAjaxSource": myPath + "/dashboard/getvolumes",
        "fnServerParams": function ( aoData ) {
             aoData.push( {"name": "date", "value": $("#datetype").val() },
                          {"name": "type", "value": $("#type").val() } );
        },

        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

            // convert Bytes
            $('td:eq(2)', nRow).html( bytesToSize(aData[2],2) );

            switch(aData[6])
            {
            case 'Full':
                $('td:eq(6)', nRow).html( '<center><i class="icon-fam-database-add"></i></center>' );
                break;
            case 'Append':
                $('td:eq(6)', nRow).html( '<center><i class="icon-fam-database-connect"></i></center>' );
                break;
             case 'Error':
                $('td:eq(6)', nRow).html( '<center><i class="icon-fam-database-edit"></i></center>' );
                break;
             case 'Recycle':
                $('td:eq(6)', nRow).html( '<center><i class="icon-fam-database-save"></i></center>' );
                break;
            case 'Used':
                $('td:eq(6)', nRow).html( '<center><i class="icon-fam-database-go"></i></center>' );
                break;
            }


            return nRow
        },
    });
    
     // Chat Pie Volumes
    var options = {
        chart: {
            renderTo: 'volumesGraphs',
            plotBackgroundColor: null,
            plotBorderWidth: null,
            plotShadow: true
        },
       title: {
            text: 'Pools & Volumes'
        },
        plotArea: {
            shadow: null,
            borderWidth: null,
            backgroundColor: null
        },
        tooltip: {
            formatter: function () {
                return '<b>Pool:</b>' + this.point.name + ' |<b> Volumes:</b>' + this.y ;
            }
        },
        plotOptions: {
            pie: {
                allowPointSelect: true,
                cursor: 'pointer',
                dataLabels: {
                    enabled: true,
                    formatter: function () {
                        if (this.y > 5) return this.point.y + ' Volumes' ;
                    },
                    color: 'black',
                    style: {
                        font: '13px Trebuchet MS, Verdana, sans-serif'
                    }
                },
                showInLegend: true
            }
        },
        series: []
    }

});
