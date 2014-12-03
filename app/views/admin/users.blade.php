@extends('admin._layouts.default')
@section('main')
{{ HTML::script('assets/js/bootbox.min.js') }}
{{ HTML::script('assets/js/admin/users.js') }}

<div class="row-fluid">
    <div class="span12 box-content">
        <div class="span4">
            <h3>{{ HTML::image('assets/img/user.png') }} {{ trans('messages.users') }}</h3>
        </div>
        <div class="pull-right">
            <a href="{{  URL::route('admin.createuser' )}}" class="btn btn-success">
                <span class="icon32 icon-color icon-user"></span> <center> {{ trans('messages.createuser') }} </center><a></div>
        </div>
</div>
<div class="row-fluid">
    <div class="span12 box-content">
        <table id="users" class="dashboardTable table table-striped table-bordered">
            <thead>
                <tr>
                    <th><center> Id </center></th>
                    <th><center> {{ trans('messages.emails') }} </center></th>
                    <th><center> {{ trans('messages.permissions') }} </center></th>
                    <th><center> {{ trans('messages.lastlogin') }} </center></th>
                    <th><center> {{ trans('messages.date') }}  </center></th>
                    <th><center> {{ trans('messages.actions') }} </center></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection
