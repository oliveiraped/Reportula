@extends('admin._layouts.default')
@section('main')
{{ HTML::script('assets/js/bootbox.min.js') }}
{{ HTML::script('assets/js/admin/groups.js') }}

<div class="row-fluid">
    <div class="span12 box-content">
        <div class="span4">
            <h3>{{ HTML::image('assets/img/groups.png') }} {{ trans('messages.groups') }}</h3>
        </div>
        <div class="pull-right">
            <a href="{{  URL::route('admin.creategroup' )}}" class="btn btn-success">
                <span class="icon32 icon-color icon-user"></span> <center> {{ trans('messages.creategroup') }} </center><a></div>
        </div>
</div>
<div class="row-fluid">
    <div class="span12 box-content">
        <table id="groups" class="dashboardTable table table-striped table-bordered">
            <thead>
                <tr>
                    <th><center> Id </center></th>
                    <th><center> {{ trans('messages.name') }} </center></th>
                    <th><center> {{ trans('messages.permissions') }} </center></th>
                    <th><center> {{ trans('messages.date') }} </center></th>
                    <th><center> {{ trans('messages.actions') }} </center></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection
