
/* Modal Box Delete User */
function groupDelete (id){
    bootbox.setIcons({
        "CANCEL"  : "fam-cancel",
        "CONFIRM" : "fam-accept"
        });
    bootbox.confirm("Are you sure do want to delete this Group ?",
    function(result) {
        if (result) {
             $.ajax({
                 url: 'deletegroup/'+id,
            });
            $('#groups').dataTable()._fnAjaxUpdate();
        }
    });
}



$(document).ready(function() {
    var value;
    var groupsTable = $('#groups').dataTable({
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
        "sAjaxSource": "getgroups",
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
            $('td:eq(4)', nRow).html( aData[4]+'<a href="#" onclick="groupDelete('+aData[0]+');" class="btn btn-danger btn-mini"><i class="icon-trash icon-white"></i> Delete </a></center>');
            return nRow
        },
    });

    $('#groupjobs').multiSelect();
    $('#groupclients').multiSelect();


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
