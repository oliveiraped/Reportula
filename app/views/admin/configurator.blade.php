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
            {{Former::text('messages.search', '')->prepend('<i class="icon-fam-server-add"></i>')->placeholder('messages.search...')->autofocus();}} &nbsp;
             <a id="refreshTree" class="btn btn-mini">
                <span class="icon-fam-arrow-rotate-anticlockwise"></span>
            </a>
            <div id="tree"></div>
        </div>
        <div class="span6 box-content ">
            <div class="dropdown btn-group ">
                <a class="btn dropdown-toggle btn-warning" data-toggle="dropdown" href="#">
                    <i class="icon-fam-text-indent"></i> Configuration Actions <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li class="dropdown-submenu">
                      <a href="#"><i class="icon-fam-wand"></i>New Item ...</a>
                      <ul class="dropdown-menu">
                        <li><a href="#" onClick ="newitem('Directors');"><i class="icon-fam-chart-organisation-add"></i> Director</a></li>
                        <li><a href="#" onClick ="newitem('Storages');"><i class="icon-fam-drive-cd"></i> Storage</a></li>
                        <li><a href="#" onClick ="newitem('Schedules');"><i class="icon-fam-date"></i> Schedules</a></li>
                        <li><a href="#" onClick ="newitem('Clients');"><i class="icon-fam-group"></i> Clients</a></li>
                        <li><a href="#" onClick ="newitem('Jobs');"><i class="icon-fam-drive-edit"></i> Jobs</a></li>
                        <li><a href="#" onClick ="newitem('Filesets');"><i class="icon-fam-page-white-swoosh"></i> Filesets</a></li>
                        <li><a href="#" onClick ="newitem('Pools');"><i class="icon-fam-chart-pie"></i> Pools</a></li>
                        <li><a href="#" onClick ="newitem('Catalogs');"><i class="icon-fam-book-addresses"></i> Catalogs</a></li>
                        <li><a href="#" onClick ="newitem('Consoles');"><i class="icon-fam-application-osx"></i> Consoles</a></li>
                        <li><a href="#" onClick ="newitem('Messages');"><i class="icon-fam-email-open"></i> Messages</a></li>
                      </ul>
                    </li>
                    <li class="divider"></li>
                    <li><a href="#" id="readBacula"><i class="icon-fam-database-save"></i> Read Configuration</a></li>
                    <li><a href="#" id="testBacula" onClick ="writeBacula('test');"><i class="icon-fam-database-gear"></i> Test Configuration </a></li>
                    <li><a href="#" id="writeBacula" onClick ="writeBacula('write');"><i class="icon-fam-database-table"></i> Write Configuration</a></li>
                    <li><a href="#" id="restartBacula" onClick ="restartBacula();"><i class="icon-fam-arrow-refresh-small"></i> Reload Bacula Configuration </a></li>
                </ul>
            </div>
        </div>
        <div class="span9" id="nodeDetails"></div>
    </div>
</div>

@endsection







