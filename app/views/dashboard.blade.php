@extends('_layouts.default')
@section('main')
{{ Form::hidden('datetype', $datetype, array("id"=>"datetype")) }}
{{ Form::hidden('type', $type, array("id"=>"type")) }}

<div class="row-fluid">
    <div class="span12 box-content">
        <div class="span3 box-content breadcrumb">
            <h3>{{ $nameDate }}
            <div class="pull-right">
                <a href={{ URL::route('dashboard', array('data'=>'day') ) }} class="btn" id="day" data-rel="tooltip" title="Day Stats" data-placement="bottom" ><i class="icon-fam-calendar-view-month"></i></a>
                <a href={{ URL::route('dashboard', array('data'=>'week') ) }} class="btn" id="week" data-rel="tooltip" title="Week Stats" data-placement="bottom" ><i class="icon-fam-calendar"></i></a>
                <a href={{ URL::route('dashboard', array('data'=>'month') ) }} class="btn" id="month" data-rel="tooltip" title="Month Stats" data-placement="bottom" ><i class="icon-fam-calendar-view-week"></i></a>
            </div></h4>
            <hr>

            <table id="stats" class="" style="width:100%">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2"><strong>Jobs Stats </strong></td>
                    </tr>
                    <tr>
                        <td><a href="#" class="" onclick="dashboardTable('terminated');"><i class="icon-fam-accept"></i> {{ 'Terminated Jobs' }}</a></td>
                        <td><span class="label label-success">{{ $terminatedJobs }}</span></td>

                    </tr>
                     <tr>
                        <td><a href="#" class="" onclick="dashboardTable('error');"><i class="icon-fam-delete"></i> {{ 'Error Jobs' }}</a></td>
                        <td><span class="label label-important">{{ $errorJobs }}</span> </td>
                    </tr>
                    <tr>
                        <td><a href="#" class="" onclick="dashboardTable('running');"><i class="icon-fam-database-save"></i>
                                {{ 'Running Jobs' }} </a>
                            </td>
                            <td><span class="label label-warning">{{ $runningJobs }}</span></td>
                    </tr>
                    <tr>
                         <td>
                            <a href="#" class="" onclick="dashboardTable('watting');">
                                <i class="icon-fam-database-link"></i> {{ 'Wating Jobs' }} </a></td>
                        <td> <span class="label label-inverse"> {{ $wattingJobs }} </span></td>

                    </tr>
                    <tr>
                        <td><a href="#" class="" onclick="dashboardTable('cancel');"><i class="icon-fam-database-edit"></i> {{ 'Canceled Jobs' }}</a></td>
                        <td><span class="label label-info">{{ $cancelJobs }}</span></td>
                    </tr>
                    <tr>
                        <td><i class="icon-fam-database-table"></i> {{ 'Transfered Bytes' }}</td>
                        <td><strong>{{ $nTransBytes }}</strong></td>
                    </tr>
                    <tr>
                        <td><i class="icon-fam-database-refresh"></i> {{ 'Transfered Files' }} </td>
                        <td><strong>{{ $nTransFiles }} </strong></td>
                    </tr>
                    <tr>
                        <td colspan="2"><br></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong> Okay Jobs :</strong> <?=$terminatedJobs ?>/<?=$terminatedJobs+$errorJobs ?><span class="pull-right">{{ intval($graphOkJob) }}%</span>
                        <div class="progress progress-striped progress-success active">
                                <div style="width: {{ intval($graphOkJob).'%' }}" class="bar"></div>
                        </div>

                    </tr>
                    <tr>
                        <td colspan="2"><strong> Failed Jobs </strong><?=$errorJobs ?>/<?=$terminatedJobs+$errorJobs ?><span class="pull-right">{{ intval($graphFailedJob) }}%</span>
                        <div style="margin-bottom: 9px;" class="progress progress-danger progress-striped">
                            <div style="width: {{ intval($graphFailedJob).'%' }}" class="bar"></div>
                        </div>

                        </td>
                    </tr>
                     <tr>
                        <td colspan="2"><strong>Server Stats </strong></td>
                    </tr>
                    <tr>
                        <td><i class="icon-fam-group-add"></i> {{ 'Clients Number' }} </td>
                        <td><strong>{{ $nClients }} </strong></td>
                    </tr>
                     <tr>
                        <td><i class="icon-fam-database-go"></i> {{ 'Database Size' }} </td>
                        <td><strong>{{ $dbsize }} </strong></td>
                    </tr>
                     <tr>
                        <td><i class="icon-fam-bullet-disk"></i> {{ 'Stored Bytes' }} </td>
                        <td><strong>{{ $nBytes }} </strong></td>
                    </tr>
                     <tr>
                        <td><i class="icon-fam-link-add"></i> {{ 'Stored Files' }} </td>
                        <td><strong>{{ $nFiles }} </strong></td>
                    </tr>
               </tbody>
           </table>

        </div>
        <div class="span9">
            <div class="dropdown btn-group ">
                <a class="btn dropdown-toggle btn-warning" data-toggle="dropdown" href="#">
                    <i class="icon-fam-text-indent"></i> Export Table Data <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="#" onClick ="$('#dashboardStatsTable').tableExport({type:'json',escape:'false'});"><i class="icon-fam-page-white-coldfusion"></i> JSON</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onClick ="$('#dashboardStatsTable').tableExport({type:'xml',escape:'false'});"><i class="icon-fam-page-white-compressed"></i> XML</a></li>
                    <li><a href="#" onClick ="$('#dashboardStatsTable').tableExport({type:'sql'});"><i class="icon-fam-database-key"></i> SQL</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onClick ="$('#dashboardStatsTable').tableExport({type:'csv',escape:'false'});"><i class="icon-fam-text-columns"></i> CSV</a></li>
                    <li><a href="#" onClick ="$('#dashboardStatsTable').tableExport({type:'txt',escape:'false'});"><i class="icon-fam-page-white-vector"></i> TXT</a></li>
                    <li class="divider"></li>

                    <li><a href="#" onClick ="$('#dashboardStatsTable').tableExport({type:'excel',escape:'false'});"><i class="icon-fam-page-white-flash"></i> Excel</a></li>
                    <li><a href="#" onClick ="$('#dashboardStatsTable').tableExport({type:'doc',escape:'false'});"><i class="icon-fam-page-world"></i> Word</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onClick ="$('#dashboardStatsTable').tableExport({type:'pdf',pdfFontSize:'7',escape:'false'});"><i class="icon-fam-page-white-add"></i> PDF</a></li>
                </ul>
            </div>
            <br>
            <table id="dashboardStatsTable" class="dashboardTable table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th><center> Job Id </center></th>
                        <th><center> Job Name </center></th>
                        <th><center> Started Time </center></th>
                        <th><center> Ended Time </center></th>
                        <th><center> Job Level </center></th>
                        <th><center> Bytes </center></th>
                        <th><center> Files </center></th>
                        <th><center> Status </center></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>

            </table>
        </div>
    </div>
</div>
<hr>
<div class="row-fluid">
    <div class="span12 ">

        <div class="span5 box-content">
            <div id="volumesGraphs" style="width: 100%; height: 400px; " class="span4 box-content breadcrumb"></div>
        </div>
        <div class="span7 box-content">
            <div class="dropdown btn-group ">
                <a class="btn dropdown-toggle btn-warning" data-toggle="dropdown" href="#">
                    <i class="icon-fam-text-indent"></i> Export Table Data <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="#" onClick ="$('#dashboardTableVolumes').tableExport({type:'json',escape:'false'});"><i class="icon-fam-page-white-coldfusion"></i> JSON</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onClick ="$('#dashboardTableVolumes').tableExport({type:'xml',escape:'false'});"><i class="icon-fam-page-white-compressed"></i> XML</a></li>
                    <li><a href="#" onClick ="$('#dashboardTableVolumes').tableExport({type:'sql'});"><i class="icon-fam-database-key"></i> SQL</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onClick ="$('#dashboardTableVolumes').tableExport({type:'csv',escape:'false'});"><i class="icon-fam-text-columns"></i> CSV</a></li>
                    <li><a href="#" onClick ="$('#dashboardTableVolumes').tableExport({type:'txt',escape:'false'});"><i class="icon-fam-page-white-vector"></i> TXT</a></li>
                    <li class="divider"></li>

                    <li><a href="#" onClick ="$('#dashboardTableVolumes').tableExport({type:'excel',escape:'false'});"><i class="icon-fam-page-white-flash"></i> Excel</a></li>
                    <li><a href="#" onClick ="$('#dashboardTableVolumes').tableExport({type:'doc',escape:'false'});"><i class="icon-fam-page-world"></i> Word</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onClick ="$('#dashboardTableVolumes').tableExport({type:'pdf',pdfFontSize:'7',escape:'false'});"><i class="icon-fam-page-white-add"></i> PDF</a></li>
                </ul>
            </div>
            <br>
            <table id="dashboardTableVolumes" class="dashboardTable table table-striped table-bordered " style="width:100%" >
                <thead>
                    <tr>
                        <th><center> Name </center></th>
                        <th><center> Slot </center></th>
                        <th><center> Size </center></th>
                        <th><center> Type </center></th>
                        <th><center> Pool </center></th>
                        <th><center> Last Written </center></th>
                        <th><center> Status </center></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>
     </div>
</div>

<script>
var chart;
var legend;
<?php echo "var chartData = ". $pools . ";\n"; ?>

</script>

@endsection
