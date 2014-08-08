{{ Former::horizontal_open('admin/saveconfiguration','post',array('class'=>'ajax', 'data-replace' => '.response')) }}
<div class="span12 box-content breadcrumb">
    <div class="row-fluid">
        <center><div class="response"></div></center>
        <div class="span12">
            <h3>{{ HTML::image('assets/img/schedule.png') }} Schedule - {{ $Name }} | {{Form::submit( ' Save ', array('class' => 'btn btn-small btn-success' ));}}
                @unless ($Name=="")
                   | <a onclick='deleteitem("{{$config}}","{{$id}}" );' class='btn btn-small btn-danger'> Delete </a>
                @endunless
            </h3>
        </div>
    </div>
    <br>
    <div class="row-fluid">
        <div class="span6">
            {{Former::hidden('config')->id('config')->value($config);}}
            {{Former::hidden('id')->id('id')->value($id);}}
            {{Former::text('Name', 'Name')->placeholder('Name')->required()->autofocus()->value($Name);}}
            <hr>
            <div id="schedulerun">
                <center><h4>Schedules Runs on </h4>
                    <a id="btnAddNewSchedule" onclick="addRun('Schedulerun');" class="btn btn-success btn-mini">Add</a>
                </center>
                <table id="schedule" class="dashboardTable table table-striped table-bordered " style="width:100%" >
                    <thead>
                        <tr>
                            <th><center> Action </center></th>
                            <th><center> Schedule </center></th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>

    <div>
    {{Former::close();}}
</div>

<script type="text/javascript">

/* Function to Delete path items */
function deleteitems(id,type)
{
    bootbox.confirm("Are you sure ? ", function(r) {
        if (r) {
            //sent request to delete order with given id
            $.ajax({
                type: "POST",
                url: "delete"+type,
                data: ({
                  'id': id,
                }),
                dataType: "json"
            });
            $('#schedule').dataTable()._fnAjaxUpdate();
            $('#schedule').dataTable().fnDraw();
             bootbox.hideAll();
        }
    });
}

function addRun(type)
{
    bootbox.prompt("Add Schedule ", function(result) {
        if (result === null) {
            bootbox.hideAll();
        } else {
            bootbox.hideAll();
            $.ajax({
                type: "POST",
                url: "add"+type,
                data: ({
                  'id': $("#id").val(),
                  'Run': result
                }),
                dataType: "json"
            });
        $('#schedule').dataTable()._fnAjaxUpdate();
        $('#schedule').dataTable().fnDraw();
        }
    });
}


jQuery(document).ready(function($){
    /* Table Includes*/
    var table = $('#schedule').dataTable({

        "sAjaxSource": "getschedule",
        "fnServerParams": function ( aoData ) {
             aoData.push( {"name": "idschedule", "value": $("#id").val() } );
        },
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
           $('td:eq(0)', nRow).html('<a class="order-delete" onclick="deleteitems('+aData[0]+',\'Schedulerun\')"><i class="icon-fam-cancel"></i></a></center>');
            return nRow
        },
        "info":   false,
        "bFilter" : false,
        "bLengthChange": false,
    });

});

</script>
