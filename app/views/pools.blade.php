@extends('_layouts.default')
@section('main')
<link href="{{ URL::asset('assets/css/select2.css') }}" rel="stylesheet">

<div class="row-fluid">
    <div class="span12 box-content">
        <div class="span2 box-content breadcrumb">
            {{ Former::open_vertical('pools', 'post', array('class' => 'form-inline')) }}
                {{ Former::select('Pool')->options($poolSelectBox)->id('Pool')->style("width:100%"); }}
                {{Former::submit( 'messages.search')->class('btn btn-primary pull-right btn-info') }}
            {{Former::close();}}
            <hr>
            <br>
            <table id="stats" class="" style="width:100%">
                <thead>
                    <tr>
                        <th></th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="2"><strong>{{ trans('messages.poolinformation') }}</strong></td>
                    </tr>
                    <tr>
                        <td colspan="2"></td>
                    </tr>
                   <tr>
                    </tr>
                    <tr>
                         <td colspan="2">{{ $name }}</td>
                    </tr>
                     <tr>
                        <td><i class="icon-fam-link-delete"></i> {{ trans('messages.retention') }}  </td>
                        <td><strong>{{ $volretension }} </strong></td>
                    </tr>
                     <tr>
                        <td><i class="icon-fam-database-add"></i> {{ trans('messages.type') }}  </td>
                        <td><strong>{{ $pooltype }} </strong></td>
                    </tr>
                     <tr>
                        <td><i class="icon-fam-arrow-refresh"></i> {{ trans('messages.recycle') }} </td>
                        <td><strong>@if ($recycle == 1)
                                        {{ trans('messages.yes') }}
                                    @else
                                        {{ trans('messages.no') }}
                                    @endif
                             </strong>
                        </td>
                    </tr>
                    <tr>
                        <td><i class="icon-fam-database-save"></i> Autoprune </td>
                        <td><strong>@if ($autoprune == 1)
                                       {{ trans('messages.yes') }}
                                    @else
                                       {{ trans('messages.no') }}
                                    @endif
                             </strong>
                        </td>
                    </tr>


               </tbody>
           </table>
        </div>
        <div class="span10 box-content">
            <div class="dropdown btn-group ">
                <a class="btn dropdown-toggle btn-warning" data-toggle="dropdown" href="#">
                    <i class="icon-fam-text-indent"></i> {{ trans('messages.exporttabledata') }} <span class="caret"></span>
                </a>
                <ul class="dropdown-menu">
                    <li><a href="#" onClick ="$('#poolTable').tableExport({type:'json',escape:'false'});"><i class="icon-fam-page-white-coldfusion"></i> JSON</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onClick ="$('#poolTable').tableExport({type:'xml',escape:'false'});"><i class="icon-fam-page-white-compressed"></i> XML</a></li>
                    <li><a href="#" onClick ="$('#poolTable').tableExport({type:'sql'});"><i class="icon-fam-database-key"></i> SQL</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onClick ="$('#poolTable').tableExport({type:'csv',escape:'false'});"><i class="icon-fam-text-columns"></i> CSV</a></li>
                    <li><a href="#" onClick ="$('#poolTable').tableExport({type:'txt',escape:'false'});"><i class="icon-fam-page-white-vector"></i> TXT</a></li>
                    <li class="divider"></li>

                    <li><a href="#" onClick ="$('#poolTable').tableExport({type:'excel',escape:'false'});"><i class="icon-fam-page-white-flash"></i> Excel</a></li>
                    <li><a href="#" onClick ="$('#poolTable').tableExport({type:'doc',escape:'false'});"><i class="icon-fam-page-world"></i> Word</a></li>
                    <li class="divider"></li>
                    <li><a href="#" onClick ="$('#poolTable').tableExport({type:'pdf',pdfFontSize:'7',escape:'false'});"><i class="icon-fam-page-white-add"></i> PDF</a></li>
                </ul>
            </div>
            <br>
            <table id="poolTable" class="dashboardTable table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th><center> {{ trans('messages.volumename') }}       </center></th>
                        <th><center> {{ trans('messages.slot') }}     </center></th>
                        <th><center> {{ trans('messages.type') }} </center></th>
                        <th><center> {{ trans('messages.lastwriten') }}   </center></th>
                        <th><center> {{ trans('messages.jobsnumber') }}    </center></th>
                        <th><center> {{ trans('messages.filesnumber') }}       </center></th>
                        <th><center> {{ trans('messages.bytes') }}       </center></th>
                        <th><center> {{ trans('messages.retention') }}      </center></th>
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


