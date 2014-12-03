@extends('_layouts.default')
@section('main')

<div class="row-fluid">
    <div class="span12 box-content">
        <div class="span8 box-content breadcrumb">
             {{ Form::hidden('jobid', $jobid, array("id"=>"jobid")) }}
            <h5>Job Files</h5>
            <hr>
            <div class="dropdown btn-group ">
                <a class="btn dropdown-toggle btn-warning" data-toggle="dropdown" href="#">
                    <i class="icon-fam-text-indent"></i> {{ trans('messages.exporttabledata') }} <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="#" onClick ="$('#filesTable').tableExport({type:'json',escape:'false'});"><i class="icon-fam-page-white-coldfusion"></i> JSON</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onClick ="$('#filesTable').tableExport({type:'xml',escape:'false'});"><i class="icon-fam-page-white-compressed"></i> XML</a></li>
                    <li><a href="#" onClick ="$('#filesTable').tableExport({type:'sql'});"><i class="icon-fam-database-key"></i> SQL</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onClick ="$('#filesTable').tableExport({type:'csv',escape:'false'});"><i class="icon-fam-text-columns"></i> CSV</a></li>
                    <li><a href="#" onClick ="$('#filesTable').tableExport({type:'txt',escape:'false'});"><i class="icon-fam-page-white-vector"></i> TXT</a></li>
                    <li class="divider"></li>

                    <li><a href="#" onClick ="$('#filesTable').tableExport({type:'excel',escape:'false'});"><i class="icon-fam-page-white-flash"></i> Excel</a></li>
                    <li><a href="#" onClick ="$('#filesTable').tableExport({type:'doc',escape:'false'});"><i class="icon-fam-page-world"></i> Word</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onClick ="$('#filesTable').tableExport({type:'pdf',pdfFontSize:'7',escape:'false'});"><i class="icon-fam-page-white-add"></i> PDF</a></li>
                </ul>
            </div>
            <br>
            <table id="filesTable" class="dashboardTable table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th><center>Path</center></th>
                        <th><center>Filename </center></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

        </div>
        <div class="span4 box-content breadcrumb">
            <h5>Job Log</h5>
            <hr>
            <p class="text-success">
              {{ $logs }}
            </p>
        </div>
    </div>
</div>
<hr>
@endsection
