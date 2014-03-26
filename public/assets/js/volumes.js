

$(document).ready(function() {
    $("#volume").select2();
   

    /* Table */
    var volumesTable = $('#volumesTable').dataTable({

        "sAjaxSource": myPath+"/volumes/getvolumes",
        "fnServerParams": function ( aoData ) {
             aoData.push( {"name": "Volume", "value": $("#volume").select2("val") } );
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

});