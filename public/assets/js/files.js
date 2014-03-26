$(document).ready(function() {
   
    var logsTable = $('#filesTable').dataTable({
        'iDisplayLength' : 50,
        "sAjaxSource": "getfiles",
        "fnServerParams": function ( aoData ) {
             aoData.push( {"name": "jobid", "value": $("#jobid").val()  });
        },
    });
});