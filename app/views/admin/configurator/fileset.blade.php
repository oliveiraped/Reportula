 {{ Former::horizontal_open('admin/saveconfiguration','post',array('class'=>'ajax', 'data-replace' => '.response')) }}
<div class="span12 box-content breadcrumb">
    <div class="row-fluid">
        <center><div class="response"></div></center>
        <div class="span6">
            <h3>{{ HTML::image('assets/img/files.png') }} Fileset - {{ $Name }} | {{Form::submit( ' Save ', array('class' => 'btn btn-small btn-success' ));}}
                @unless ($Name=="")
                   | <a onclick='deleteitem("{{$config}}","{{$id}}" );' class='btn btn-small btn-danger'> Delete </a>
                @endunless
            </h3>
        </div>
    </div>
    <br>
    <div class="row-fluid">
   <!-- {{ Former::horizontal_open('configurator/savedirector','post',array('class'=>'ajax', 'data-replace' => '.response')) }} -->
        <div class="span6">
            {{Former::hidden('config')->id('config')->value($config);}}
            {{Former::hidden('id')->id('id')->value($id);}}
            {{Former::text('Name', 'Name')->placeholder('Name')->required()->autofocus()->value($Name);}}
            {{Former::text('IgnoreFileSetChanges', 'IgnoreFileSetChanges')->placeholder('IgnoreFileSetChanges');}}
            {{Former::text('EnableVSS', 'EnableVSS')->placeholder('EnableVSS');}}
            <div id="includeexclude">
                <ul class="nav nav-tabs" id="myTab">
                    <li class="active"><a data-target="#include" data-toggle="tab" >Include Files</a></li>
                    <li><a data-target="#exclude" data-toggle="tab" >Exclude Files</a></li>
                </ul>
                <div class="tab-content">
                    <div class="tab-pane active" id="include">
                            <div class="span6" id="include">
                                <center>
                                    <h4>Files Include</h4>
                                    <a id="btnAddNewIncludes" onclick="addPath('includes');" class="btn btn-success btn-mini">Add</a>
                                </center>
                                <table id="includes" class="dashboardTable table table-striped table-bordered " style="width:100%" >
                                    <thead>
                                        <tr>
                                            <th><center> Action </center></th>
                                            <th><center> Path </center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="span6">
                                <center>
                                    <h4>Files Otions</h4>
                                    <button id="btnAddNewincludesOptions" onclick="addOption('includesoptions');" class="btn btn-success btn-mini" >Add</button>
                                </center>
                                <table id="includesoptions" class="dashboardTable table table-striped table-bordered " style="width:100%" >
                                    <thead>
                                        <tr>
                                            <th><center> Action </center></th>
                                            <th><center> Option </center></th>
                                            <th><center> Value </center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                    </div>
                    <div class="tab-pane" id="exclude">
                             <div class="span6">
                                <center><h4>Files Excludes</h4>
                                    <a id="btnAddNewIncludes" onclick="addPath('excludes');" class="btn btn-success btn-mini">Add</a>
                                </center>
                                <table id="excludes" class="dashboardTable table table-striped table-bordered " style="width:100%" >
                                    <thead>
                                        <tr>
                                            <th><center> Action </center></th>
                                            <th><center> Path </center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                            <div class="span6">
                                <center>
                                    <h4>Files Exclude Otions</h4>
                                    <button id="btnAddNewExcludesOptions" onclick="addOption('excludesoptions');" class="btn btn-success btn-mini" >Add</button>
                                </center>
                                <table id="excludesoptions" class="dashboardTable table table-striped table-bordered " style="width:100%" >
                                    <thead>
                                        <tr>
                                            <th><center> Action </center></th>
                                            <th><center> Option </center></th>
                                            <th><center> Value </center></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                    </div>
                </div>
            </div>
        </div>
    <div>
     {{Former::close();}}
</div>

<script type="text/javascript">

/* Function to Delete path items */
function deleteitems(id,type)
{
    bootbox.confirm("Are you sure? ", function(r) {
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
            $('#'+type).dataTable()._fnAjaxUpdate();
            $('#'+type).dataTable().fnDraw();
             bootbox.hideAll();
        }
    });
}

function addPath(type)
{
    bootbox.prompt("Add File "+type, function(result) {
        if (result === null) {
            bootbox.hideAll();
        } else {
            bootbox.hideAll();
            $.ajax({
                type: "POST",
                url: "add"+type,
                data: ({
                  'id': $("#id").val(),
                  'path': result
                }),
                dataType: "json"
            });
        $('#'+type).dataTable()._fnAjaxUpdate();
        $('#'+type).dataTable().fnDraw();
        }
    });
}

function addOption(type)
{
    bootbox.confirm("<form id='infos' action=''>\
        Option :<input type='text' id='option'></input><br/>\
        Value  :<input type='text' id='value'></input>\
    </form>"
    , function(result) {
        if(result)
            $.ajax({
                type: "POST",
                url: "add"+type,
                data: ({
                  'id'    : $("#id").val(),
                  'value' : $("#value").val(),
                  'option': $("#option").val()
                }),
                dataType: "json"
            });
        $('#'+type).dataTable()._fnAjaxUpdate();
        $('#'+type).dataTable().fnDraw();
    });
}

jQuery(document).ready(function($){
    /* Table Includes*/
    var table = $('#includes').dataTable({

        "sAjaxSource": "getincludes",
        "fnServerParams": function ( aoData ) {
             aoData.push( {"name": "filesetid", "value": $("#id").val() } );
        },
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
           $('td:eq(0)', nRow).html('<a class="order-delete" onclick="deleteitems('+aData[0]+',\'includes\')"><i class="icon-fam-cancel"></i></a></center>');
            return nRow
        },
        "info":   false,
        "bFilter" : false,
        "bLengthChange": false,
    });

    /* Table Includes Options*/
    var Table2 = $('#includesoptions').dataTable({

        "sAjaxSource": "getincludesoptions",
        "fnServerParams": function ( aoData ) {
             aoData.push( {"name": "filesetid", "value": $("#id").val() } );
        },
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
           $('td:eq(0)', nRow).html('<a class="order-delete" onclick="deleteitems('+aData[0]+',\'includesoptions\')"><i class="icon-fam-cancel"></i></a></center>');
            return nRow
        },
        "info":   false,
        "bFilter" : false,
        "bLengthChange": false,
    });

    /* Table Excludes Options*/
    var Table3 = $('#excludes').dataTable({

        "sAjaxSource": "getexcludes",
        "fnServerParams": function ( aoData ) {
             aoData.push( {"name": "filesetid", "value": $("#id").val() } );
        },
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
           $('td:eq(0)', nRow).html('<a class="order-delete" onclick="deleteitems('+aData[0]+',\'excludes\')"><i class="icon-fam-cancel"></i></a></center>');
            return nRow
        },
        "info":   false,
        "bFilter" : false,
        "bLengthChange": false,
    });

    /* Table Includes Options*/
    var Table4 = $('#excludesoptions').dataTable({
        "sAjaxSource": "getexcludesoptions",
        "fnServerParams": function ( aoData ) {
             aoData.push( {"name": "filesetid", "value": $("#id").val() } );
        },
        "fnRowCallback": function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {
           $('td:eq(0)', nRow).html('<a class="order-delete" onclick="deleteitems('+aData[0]+',\'excludesoptions\')"><i class="icon-fam-cancel"></i></a></center>');
            return nRow
        },
        "info":   false,
        "bFilter" : false,
        "bLengthChange": false,
    });
});
</script>
