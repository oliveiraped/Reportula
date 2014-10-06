
/* Modal Box Delete EmailReport */
function emailsDelete (id){
    bootbox.setIcons({
        "CANCEL"  : "fam-cancel",
        "CONFIRM" : "fam-accept"
        });
    bootbox.confirm("Are you sure do want to delete this Email Reporting ?",
    function(result) {
        if (result) {
             $.ajax({
                 url: 'deleteemails/'+id,
            });
            $('#emails').dataTable()._fnAjaxUpdate();
        }
    });
}

$(document).ready(function() {
    var value;
    var emailsTable = $('#emails').dataTable({
        "sDom": "<'row-fluid'<'span6'l><'span6'f>r>t<'row-fluid'<'span12'i><'span12 center'p>>",
        "sPaginationType": "bootstrap",
        "bPaginate": true,
        "bLengthChange": false,
        "bFilter": true,
        "bSort":  true,
        "bInfo": false,
        "bAutoWidth": true,
        "bProcessing": false,
        "bServerSide": true,
        "sAjaxSource": "getemails",
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            $('td:eq(5)', nRow).html( aData[5]+' <a href="#" onclick="emailsDelete('+aData[0]+');" class="btn btn-danger btn-mini"><i class="icon-trash icon-white"></i> Delete </a> </center>');
            return nRow
        },
    });

    $('#emailsjobs').multiSelect();
    $('#emailsclients').multiSelect();
    $('#emailsgroups').multiSelect();

} );
