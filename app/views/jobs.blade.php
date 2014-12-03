@extends('_layouts.default')
@section('main')

<div class="row-fluid">
    <div class="span12 box-content">
        <div class="span3 box-content breadcrumb">
            {{ Former::open_vertical('jobs', 'post', array('class' => 'form-inline')) }}
                {{ Form::hidden('start', $start, array("id"=>"start")) }}
                {{ Form::hidden('end', $end, array("id"=>"end")) }}
                {{ Form::hidden('type', $type, array("id"=>"type")) }}
                {{ Former::select('Job')->label('messages.selectjob')->options($jobSelectBox, $job)->id('job')->style("width:100%"); }}
                {{ Former::text('messages.date')->prepend('<i class="icon-fam-calendar-add"></i>')->placeholder('messages.selectdate')->required()->id('date')->name('date'); }}
                {{Former::submit( 'messages.search')->class('btn btn-primary pull-right btn-info') }}
            {{Former::close();}}
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
                        <td colspan="2"><strong>{{ trans('messages.jobsstats') }}</strong></td>
                    </tr>
                    <tr>
                        <td><a href="#" class="" onclick="jobsTable('terminated');"><i class="icon-fam-accept"></i> {{ trans('messages.terminatedjobs') }}</a></td>
                        <td><span class="label label-success">{{ $terminatedJobs }}</span>
                    </tr>
                     <tr>
                        <td><a href="#" class="" onclick="jobsTable('error');"><i class="icon-fam-delete"></i> {{ trans('messages.errorjobs') }} </a></td>
                        <td><span class="label label-important">{{ $errorJobs }}</span> </td>
                    </tr>
                    <tr>
                        <td><a href="#" class="" onclick="jobsTable('running');"><i class="icon-fam-database-save"></i>
                               {{ trans('messages.runningjobs') }} </a>
                            </td>
                            <td><span class="label label-warning"> {{ $runningJobs }}</span></td>
                    </tr>
                    <tr>
                         <td>
                            <a href="#" class="" onclick="jobsTable('watting');">
                                <i class="icon-fam-database-link"></i> {{ trans('messages.waitingjobs') }} </a></td>
                        <td> <span class="label label-inverse"> {{ $wattingJobs }} </span></td>

                    </tr>
                    <tr>
                        <td><a href="#" class="" onclick="jobsTable('cancel');"><i class="icon-fam-database-edit"></i> {{ trans('messages.canceledjobs') }} </a></td>
                        <td><span class="label label-inverse"> {{ $cancelJobs }}</span></td>
                    </tr>
                    <tr>
                        <td><i class="icon-fam-database-table"></i> {{ trans('messages.transferedbytes') }} </td>
                        <td><strong>{{ $nTransBytes }}</strong></td>
                    </tr>
                    <tr>
                        <td><i class="icon-fam-database-refresh"></i> {{ trans('messages.transferedfiles') }} </td>
                        <td><strong>{{ $nTransFiles }} </strong></td>
                    </tr>
                    <tr>
                        <td colspan="2"><br></td>
                    </tr>
                    <tr>
                        <td colspan="2"><strong> {{ trans('messages.successjobs') }} :</strong> <?=$terminatedJobs ?>/<?=$terminatedJobs+$errorJobs ?>
                         <div class="progress progress-striped progress-success active">
                                <div style="width: {{ intval($graphOkJob).'%' }} " class="bar"></div>

                        </div>
                    </tr>
                    <tr>
                        <td colspan="2"><strong> {{ trans('messages.failedjobs') }} </strong><?=$errorJobs ?>/<?=$terminatedJobs+$errorJobs ?>
                        <div style="margin-bottom: 9px;" class="progress progress-danger progress-striped">
                            <div style="width: {{ intval($graphFailedJob).'%' }}" class="bar"></div>
                        </div>
                    </tr>
                     @if ( is_array($filesinclude))
                        <tr>
                            <td colspan="2"><strong> {{ trans('messages.includedfiles') }} </strong></td>
                        </tr>
                        <tr class="success" >
                            <td colspan="2">
                                <ul>
                                    @foreach ($filesinclude as $include)
                                        <li>{{ $include->file }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endif
                    @if ( is_array($filesexclude))
                        <tr>
                            <td colspan="2"><strong> {{ trans('messages.excludedfiles') }} </strong><td>
                        </tr>
                        <tr>
                            <td colspan="2">
                                <ul>
                                    @foreach ($filesexclude as $exclude)
                                         <li>{{ "\t".$exclude->file }}</li>
                                    @endforeach
                                </ul>
                            </td>
                        </tr>
                    @endif
               </tbody>
            </table>
        </div>
    <div class="span9 box-content">
        <div class="dropdown btn-group ">
            <a class="btn dropdown-toggle btn-warning" data-toggle="dropdown" href="#">
                <i class="icon-fam-text-indent"></i> {{ trans('messages.exporttabledata') }} <span class="caret"></span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="#" onClick ="$('#jobsTable').tableExport({type:'json',escape:'false'});"><i class="icon-fam-page-white-coldfusion"></i> JSON</a></li>
                <li class="divider"></li>
                <li><a href="#" onClick ="$('#jobsTable').tableExport({type:'xml',escape:'false'});"><i class="icon-fam-page-white-compressed"></i> XML</a></li>
                <li><a href="#" onClick ="$('#jobsTable').tableExport({type:'sql'});"><i class="icon-fam-database-key"></i> SQL</a></li>
                <li class="divider"></li>
                <li><a href="#" onClick ="$('#jobsTable').tableExport({type:'csv',escape:'false'});"><i class="icon-fam-text-columns"></i> CSV</a></li>
                <li><a href="#" onClick ="$('#jobsTable').tableExport({type:'txt',escape:'false'});"><i class="icon-fam-page-white-vector"></i> TXT</a></li>
                <li class="divider"></li>

                <li><a href="#" onClick ="$('#jobsTable').tableExport({type:'excel',escape:'false'});"><i class="icon-fam-page-white-flash"></i> Excel</a></li>
                <li><a href="#" onClick ="$('#jobsTable').tableExport({type:'doc',escape:'false'});"><i class="icon-fam-page-world"></i> Word</a></li>
                <li class="divider"></li>
                <li><a href="#" onClick ="$('#jobsTable').tableExport({type:'pdf',pdfFontSize:'7',escape:'false'});"><i class="icon-fam-page-white-add"></i> PDF</a></li>
            </ul>
        </div>
        <br>
        <table id="jobsTable" class="dashboardTable table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th><center> {{ trans('messages.jobid') }}       </center></th>
                    <th><center> {{ trans('messages.startedtime') }} </center></th>
                    <th><center> {{ trans('messages.endedtime') }}   </center></th>
                    <th><center> {{ trans('messages.volumename') }}  </center></th>
                    <th><center> {{ trans('messages.joblevel') }}    </center></th>
                    <th><center> {{ trans('messages.bytes') }}       </center></th>
                    <th><center> {{ trans('messages.files') }}       </center></th>
                    <th><center> {{ trans('messages.status') }}      </center></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
<hr>
<div class="row-fluid">
    <div class="span12 box-content">
        <div class="span6 box-content breadcrumb">
            <div id="bytesGraphs" style="min-width: 100%; height: 400px; margin: 0 auto"></div>
        </div>
        <div class="span6 box-content breadcrumb">
            <div id="filesGraphs" style="min-width: 100%;  height: 400px; margin: 0 auto"></div>
        </div>
    </div>
</div>

<script>
/* Code For The Graphs */
var chart;
var legend;
<?php echo "var chartDataFiles = ". $graphFiles . ";\n";
      echo "var chartDataBytes = ". $graphBytes . ";\n";
      echo "var titlefiles = '".trans('messages.transferedfiles')."';\n";
      echo "var titlebytes = '".trans('messages.transferedbytes')."';\n";
?>

</script>

@endsection
