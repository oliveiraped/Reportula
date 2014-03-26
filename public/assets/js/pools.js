$(document).ready(function() {
    $("#Pool").select2();

    var poolTable = $('#poolTable').dataTable({

        "sAjaxSource": myPath+"/pools/getpools",
        "fnServerParams": function ( aoData ) {
             aoData.push( {"name": "Pool", "value": $("#Pool").select2("val") });
        },

        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {

            // convert Bytes
            $('td:eq(6)', nRow).html( bytesToSize(aData[6],2) );

            if ( aData[8] == "Error" ) {
                $('td:eq(8)', nRow).html( '<center><i class="icon-fam-cd-edit"></i></center>' );
            } else if ( aData[8] == "Full" ) {
                $('td:eq(8)', nRow).html( '<center><i class="icon-fam-cd-add"></i></center>' );
            } else if ( aData[8] == "Recycle" ) {
                $('td:eq(8)', nRow).html( '<center><i class="icon-fam-cd"></i></center>' );
            } else if ( aData[8] == "Append" ) {
                $('td:eq(8)', nRow).html( '<center><i class="icon-fam-cd-burn"></i></center>' );
            }





           



            return nRow
        },
    });
});