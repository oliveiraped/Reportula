@extends('admin._layouts.default')
@section('main')
<style type="text/css">
ul.fancytree-container {
    height:600px;
    overflow: auto;
    position: relative;
}
</style>

<div class="row-fluid">
    <div class="span12 box-content">
        <div class="span3">
            {{Former::text('search', '')->prepend('<i class="icon-fam-server-add"></i>')->placeholder('Search...')->autofocus();}}
            <div id="tree"></div>
        </div>
       
        <div class="span6 box-content breadcrumb">
            <a id="readBacula" class="btn">
                <span class="icon-fam-database-save"></span> <center> Read Configuration </center>
            </a>
            <a id="readBacula" class="btn">
                <span class="icon-fam-database-table"></span> <center> Write Configuration </center>
            </a>
        </div>
        <div class="span9" id="nodeDetails">
            
        </div>

    </div>
</div>

@endsection


      




