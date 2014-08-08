
/* Modal Box Delete User */
function userDelete (id){

    bootbox.setIcons({
        "CANCEL"  : "fam-cancel",
        "CONFIRM" : "fam-accept"
        });
    bootbox.confirm("Are you sure do want to delete this user ?",
    function(result) {
        if (result) {
             $.ajax({
                 url: 'deleteuser/'+id,
            });
            $('#users').dataTable()._fnAjaxUpdate();
        }
    });
}



$(document).ready(function() {
    var value;
    var usersTable = $('#users').dataTable({
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
        "sAjaxSource": "getusers",
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            $('td:eq(5)', nRow).html( aData[5]+' <a href="#" onclick="userDelete('+aData[0]+');" class="btn btn-danger btn-mini"><i class="icon-trash icon-white"></i> Delete </a> </center>');
            return nRow
        },
    });

    $('#userjobs').multiSelect();
    $('#userclients').multiSelect();


    $('#usergroups').multiSelect({
        /* afterSelect: function(value, text){
             $.ajax({
                    type: 'POST',
                    // url: BASE+'/index.php/admin/users/add_user_to_group',
                     data:  { id: $('#id').val(), grupo: value },
                     dataType: 'JSON'
                });
          },
          afterDeselect: function(value, text){
                $.ajax({
                    type: 'POST',
                   //  url: BASE+'/index.php/admin/users/remove_user_from_group',
                     data:  { id: $('#id').val(), grupo: value },
                     dataType: 'JSON'
                });
            }*/
    });
} );
