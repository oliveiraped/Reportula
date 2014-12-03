@extends('_layouts.default')
@section('main')


<link href="{{ URL::asset('assets/css/select2.css') }}" rel="stylesheet">

<div class="row-fluid">
    <div class="span12 box-content">
        <div class="span3 box-content breadcrumb">
             {{ Former::open_vertical('volumes', 'post', array('class' => 'form-inline')) }}
                {{ Former::select('messages.volume')->options($volumeSelectBox)->id('volume')->style("width:100%")->value($volume); }}
                {{ Former::submit( 'messages.search')->class('btn btn-primary pull-right btn-info') }}
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
                        <td colspan="2"><strong>{{ trans('messages.volumeinfo') }}</strong></td>
                    </tr>
                    <tr>
                        <td><i class="icon-fam-briefcase"></i> {{ trans('messages.slot') }}</td>
                        <td>{{ $slot }}</td>
                    </tr>
                    <tr>
                        <td><i class="icon-fam-user-green"></i> {{ trans('messages.jobsnumber') }}</td>
                        <td>{{ $voljobs }}</td>
                    </tr>
                    <tr>
                        <td><i class="icon-fam-link-delete"></i> {{ trans('messages.files') }}</td>
                        <td>{{ $volfiles }}</td>
                    </tr>
                    <tr>
                        <td><i class="icon-fam-database-go"></i> {{ trans('messages.bytes') }}</td>
                        <td>{{ $volbytes}}</td>
                    </tr>
                    <tr>
                        <td><i class="icon-fam-database-save"></i> {{ trans('messages.retention') }} </td>
                        <td>{{ $volretention }}</td>
                    </tr>
                    <tr>
                        <td><i class="icon-fam-cd-eject"></i> {{ trans('messages.labeldate') }} </td>
                        <td>{{ $labeldate; }}</td>
                    </tr>
                    <tr>
                        <td><i class="icon-fam-cd-burn"></i> {{ trans('messages.firstwriten') }}</td>
                        <td>{{ $firstwritten }}</td>
                    </tr>
                    <tr>
                        <td><i class="icon-fam-cd-go"></i> {{ trans('messages.lastwriten') }}</td>
                        <td>{{ $lastwritten }}</td>
                    </tr>
                    <tr>
                        <td><i class="icon-fam-cd"></i> {{ trans('messages.status') }} </td>
                        <td>@if ($volstatus == "Error")
                                        {{ $volstatus }}
                                    @else
                                       {{  $volstatus }}
                                    @endif
                       </td>
                    </tr>
               </tbody>
           </table>
        </div>
        <div class="span9 box-content">
            <div class="dropdown btn-group ">
                <a class="btn dropdown-toggle btn-warning" data-toggle="dropdown" href="#">
                    <i class="icon-fam-text-indent"></i> {{ trans('messages.exporttabledata') }} <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="#" onClick ="$('#volumesTable').tableExport({type:'json',escape:'false'});"><i class="icon-fam-page-white-coldfusion"></i> JSON</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onClick ="$('#volumesTable').tableExport({type:'xml',escape:'false'});"><i class="icon-fam-page-white-compressed"></i> XML</a></li>
                    <li><a href="#" onClick ="$('#volumesTable').tableExport({type:'sql'});"><i class="icon-fam-database-key"></i> SQL</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onClick ="$('#volumesTable').tableExport({type:'csv',escape:'false'});"><i class="icon-fam-text-columns"></i> CSV</a></li>
                    <li><a href="#" onClick ="$('#volumesTable').tableExport({type:'txt',escape:'false'});"><i class="icon-fam-page-white-vector"></i> TXT</a></li>
                    <li class="divider"></li>

                    <li><a href="#" onClick ="$('#volumesTable').tableExport({type:'excel',escape:'false'});"><i class="icon-fam-page-white-flash"></i> Excel</a></li>
                    <li><a href="#" onClick ="$('#volumesTable').tableExport({type:'doc',escape:'false'});"><i class="icon-fam-page-world"></i> Word</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onClick ="$('#volumesTable').tableExport({type:'pdf',pdfFontSize:'7',escape:'false'});"><i class="icon-fam-page-white-add"></i> PDF</a></li>
                </ul>
            </div>
            <br>
            <table id="volumesTable" class="dashboardTable table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th><center> {{ trans('messages.jobid') }}       </center></th>
                        <th><center> {{ trans('messages.jobname') }}     </center></th>
                        <th><center> {{ trans('messages.startedtime') }} </center></th>
                        <th><center> {{ trans('messages.endedtime') }}   </center></th>
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
</div>




@endsection


