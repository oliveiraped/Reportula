 {{ Former::horizontal_open('admin/saveconfiguration','post',array('class'=>'ajax', 'data-replace' => '.response')) }}
<div class="span12 box-content breadcrumb">
    <div class="row-fluid">
        <center><div class="response"></div></center>
        <div class="span6">
            <h3>{{ HTML::image('assets/img/files.png') }} Fileset - {{ $Name }} | {{Form::submit( ' Save ', array('class' => 'btn btn-small btn-success' ));}} </h3>
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
            <ul class="nav nav-tabs" id="myTab">
                <li class="active"><a data-target="#include" data-toggle="tab" >Include Files</a></li>
                <li><a data-target="#exclude" data-toggle="tab" >Exclude Files</a></li>
            </ul>            
            <div class="tab-content">
                <div class="tab-pane active" id="include">
                        <div class="span6" id="include">
                            <center>
                                <h4>Files Include</h4>
                                <button id="btnAddNewIncludes" url="#" class="btn btn-success" >Add</button>
                                <button id="btnDeleteIncludes" class="btn btn-danger" >Delete</button>
                            </center>
                            <table id="tableFilesetIncludes" class="dashboardTable table table-striped table-bordered " style="width:100%" >
                                <thead>
                                    <tr>
                                        <th class="dpass"><center> Id </center></th>
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
                                <button id="btnAddNewIncludesOptions" url="#" class="btn btn-success" >Add</button>
                                <button id="btnDeleteIncludesOptions" class="btn btn-danger" >Delete</button>
                            </center>
                            
                            <table id="tableFilesetIncludesOptions" class="dashboardTable table table-striped table-bordered " style="width:100%" >
                                <thead>
                                    <tr>
                                        <th class="dpass"><center> Id </center></th>
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
                                <button id="btnAddNewExcludes" url="#" class="btn btn-success" >Add</button>
                                <button id="btnDeleteExcludes" class="btn btn-danger" >Delete</button>
                            </center>
                           
                            <table id="tableFilesetExcludes" class="dashboardTable table table-striped table-bordered " style="width:100%" >
                                <thead>
                                    <tr>
                                        <th class="dpass"><center> Id </center></th>
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
                                <button id="btnAddNewExcludesOptions" url="#" class="btn btn-success" >Add</button>
                                <button id="btnDeleteExcludesOptions" class="btn btn-danger" >Delete</button>
                            </center>
                            
                            <table id="tableFilesetExcludesOptions" class="dashboardTable table table-striped table-bordered " style="width:100%" >
                                <thead>
                                    <tr>
                                        <th class="dpass"><center> Id </center></th>
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
    <div>
    <!-- {{Former::close();}} -->
</div>  


<!-- Form for Adding Include Files -->
<form id="formAddNewRowIncludes" action="#" class="form-horizontal">
    {{Former::hidden('id')->id('id')->value($id);}}
    <br>
    <div class="control-group">
      <label class="control-label" for="textinput">Path : </label>
      <div class="controls">
        <input id="path" name="path" type="text" placeholder="Path" class="input-large" required="" value="/var" rel="0">
        <input type="hidden" type="text"  class="input-large"  value="/var" rel="1">
        <p class="help-block">Example :  /var</p>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="button1id"></label>
      <div class="controls">
        <button id="btnAddNewOkIncludes" class="btn btn-success">Save</button>
        <button type="button" class="ui-button ui-widget ui-state-default ui-corner-all ui-button-icon-only ui-dialog-titlebar-close" role="button" title="Close"><span class="ui-button-text">Close</span></button>


        <button id="btnAddNewCancelIncludes" url="#" class="btn btn-danger">Cancel</button>
      </div>
    </div>
</form>

<!-- Form for Adding Options Include Files -->
<form id="formAddNewRowIncludesOptions" action="#" class="form-horizontal">
    {{Former::hidden('id')->id('id')->value($id);}}
    <br>
    <input type="hidden" type="text"  class="input-large"  value="/var" rel="0">
    <div class="control-group">
      <label class="control-label" for="textinput">Option : </label>
      <div class="controls">
        <input id="textinput" name="textinput" type="text" placeholder="Option" class="input-large" required="" rel="1">
        <p class="help-block">Example :  option</p>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="textinput">Value : </label>
      <div class="controls">
        <input id="value" name="value" type="text" placeholder="Value" class="input-large" required="" rel="2">
        <p class="help-block">Example :  Value</p>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="button1id"></label>
      <div class="controls">
        <button id="btnAddNewOkIncludesOptions" class="btn btn-success">Save</button>
        <button id="btnAddNewCancelIncludesOptions" url="#" class="btn btn-danger">Cancel</button>
      </div>
    </div>
</form>


<!-- Form for Adding Include Files -->
<form id="formAddNewRowExcludes" action="#" class="form-horizontal">
    {{Former::hidden('id')->id('id')->value($id);}}
    <br>
    <div class="control-group">
      <label class="control-label" for="textinput">Path : </label>
      <div class="controls">
        <input id="path" name="path" type="text" placeholder="Path" class="input-large" required="" value="/var" rel="1">
        <input type="hidden" type="text"  class="input-large"  value="/var" rel="0">
        <p class="help-block">Example :  /var</p>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="button1id"></label>
      <div class="controls">
        <button id="btnAddNewOkExcludes" class="btn btn-success">Save</button>
        <button id="btnAddNewCancelExcludes" url="#" class="btn btn-danger">Cancel</button>
      </div>
    </div>
</form>

<!-- Form for Adding Options Excludes Files -->
<form id="formAddNewRowExcludesOptions" action="#" class="form-horizontal">
    {{Former::hidden('id')->id('id')->value($id);}}
    <br>
    <input type="hidden" type="text"  class="input-large"  value="/var" rel="0">
    <div class="control-group">
      <label class="control-label" for="textinput">Option : </label>
      <div class="controls">
        <input id="textinput" name="textinput" type="text" placeholder="Option" class="input-large" required="" rel="1">
        <p class="help-block">Example :  Option</p>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="textinput">Value : </label>
      <div class="controls">
        <input id="value" name="textinput" type="text" placeholder="Value" class="input-large" required="" rel="2">
        <p class="help-block">Example :  Value</p>
      </div>
    </div>
    <div class="control-group">
      <label class="control-label" for="button1id"></label>
      <div class="controls">
        <button id="btnAddNewOkExcludesOptions" class="btn btn-success">Save</button>
        <button id="btnAddNewCancelExcludeOptions" class="btn btn-danger">Cancel</button>
      </div>
    </div>
</form>



<!-- Style to hide close button on Jquery Dialog Bar -->
<style>
    .no-close .ui-dialog-titlebar-close {
        display: none; 
       
    }

    th.dpass, td.dpass {
        display: none;
    }
</style>
   



<script type="text/javascript">
jQuery(document).ready(function($){
    

    /* Table Includes*/
    var Table = $('#tableFilesetIncludes').dataTable({

        "sAjaxSource": "getincludes",
        "fnServerParams": function ( aoData ) {
             aoData.push( {"name": "filesetid", "value": $("#id").val() } );
        },
        "info":   false,
        "bFilter" : false,               
        "bLengthChange": false,
        "aoColumnDefs": [ { "sClass": "dpass", "aTargets": [ 0 ] } ] 
    
    }).makeEditable({
        sDeleteURL: "deleteincludes",
        sUpdateURL: "editincludes",
        sAddURL: "addincludes",
       
        sAddNewRowFormId: "formAddNewRowIncludes",
        sDeleteRowButtonId: "btnDeleteIncludes",
        sAddNewRowButtonId: "btnAddNewIncludes",
        sAddNewRowOkButtonId:     "btnAddNewOkIncludes",
        sAddNewRowCancelButtonId: "btnAddNewCancelIncludes",
        fnOnNewRowPosted: function(data)
        { 
            //Table.fnDraw();
        },
        "oAddNewRowFormOptions": {
            dialogClass: "no-close",
              "minWidth": 400,
              title: 'Add Backup Path',
              show: "blind",
              hide: "explode"
           }         
    });

   

    /* Table Includes Options*/
    var Table2 = $('#tableFilesetIncludesOptions').dataTable({

        "sAjaxSource": "getincludesoptions",
        "fnServerParams": function ( aoData ) {
             aoData.push( {"name": "filesetid", "value": $("#id").val() } );
        },
        "info":   false,
        "bFilter" : false,               
        "bLengthChange": false,
        "aoColumnDefs": [ { "sClass": "dpass", "aTargets": [ 0 ] } ] 
    }).makeEditable({
            sDeleteURL: "deleteincludesoptions",
            sUpdateURL: "editincludesoptions",
            sAddURL:    "addincludesoptions",

            sAddNewRowFormId: "formAddNewRowIncludesOptions",
            sDeleteRowButtonId: "btnDeleteIncludesOptions",
            sAddNewRowButtonId: "btnAddNewIncludesOptions",
            sAddNewRowOkButtonId:     "btnAddNewOkIncludesOptions",
            sAddNewRowCancelButtonId: "btnAddNewCancelIncludesOptions",
            "oAddNewRowFormOptions": {
                dialogClass: "no-close",
                  "minWidth": 400,
                  title: 'Add Options Backup Path',
                  show: "blind",
                  hide: "explode"
               }         
    });

    /* Table Excludes Options*/
    var Table3 = $('#tableFilesetExcludes').dataTable({

        "sAjaxSource": "getexcludes",
        "fnServerParams": function ( aoData ) {
             aoData.push( {"name": "filesetid", "value": $("#id").val() } );
        },
        "info":   false,
        "bFilter" : false,               
        "bLengthChange": false,
        "aoColumnDefs": [ { "sClass": "dpass", "aTargets": [ 0 ] } ] 
    
    }).makeEditable({
            sDeleteURL: "deleteexcludes",
            sUpdateURL: "editexcludes",
            sAddURL:    "addexcludes",

            sAddNewRowFormId: "formAddNewRowExcludes",
            sDeleteRowButtonId: "btnDeleteExcludes",
            sAddNewRowButtonId: "btnAddNewExcludes",
            sAddNewRowOkButtonId:     "btnAddNewOkExcludes",
            sAddNewRowCancelButtonId: "btnAddNewCancelExcludes",
            "oAddNewRowFormOptions": {
                dialogClass: "no-close",
                  "minWidth": 400,
                  title: 'Add Options Backup Path',
                  show: "blind",
                  hide: "explode"
               }         
    });

    /* Table Includes Options*/
    var Table4 = $('#tableFilesetExcludesOptions').dataTable({

        "sAjaxSource": "getexcludesoptions",
        "fnServerParams": function ( aoData ) {
             aoData.push( {"name": "filesetid", "value": $("#id").val() } );
        },
        "info":   false,
        "bFilter" : false,               
        "bLengthChange": false,
        "aoColumnDefs": [ { "sClass": "dpass", "aTargets": [ 0 ] } ] 
    
    }).makeEditable({
            sDeleteURL: "deleteexcludesoptions",
            sUpdateURL: "editexcludesoptions",
            sAddURL:    "addexcludesoptions",

            sAddNewRowFormId: "formAddNewRowExcludesOptions",
            sDeleteRowButtonId: "btnDeleteExcludesOptions",
            sAddNewRowButtonId: "btnAddNewExcludesOptions",
            sAddNewRowOkButtonId:     "btnAddNewOkExcludesOptions",
            sAddNewRowCancelButtonId: "btnAddNewCancelExcludesOptions",
            "oAddNewRowFormOptions": {
                dialogClass: "no-close",
                  "minWidth": 400,
                  title: 'Add Options Backup Path',
                  show: "blind",
                  hide: "blind"
               }         
    });


    


});
</script>