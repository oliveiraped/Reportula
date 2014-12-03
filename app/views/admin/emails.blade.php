@extends('admin._layouts.default')
@section('main')
{{ HTML::script('assets/js/bootbox.min.js') }}
{{ HTML::script('assets/js/admin/emails.js') }}

<div class="row-fluid">
    <div class="span12 box-content">
        <div class="span4">
            <h3>{{ HTML::image('assets/img/email.jpg') }} Email Reporting </h3>
        </div>
        <div class="pull-right">
            <a href="{{  URL::route('admin.createemails' )}}" class="btn btn-success">
                <span class="icon32 icon-color icon-user"></span> <center> {{ trans('messages.createemail') }} </center><a></div>
        </div>
</div>
<div class="row-fluid">
    <div class="span12 box-content">
        <table id="emails" class="dashboardTable table table-striped table-bordered">
            <thead>
                <tr>
                    <th><center> Id </center></th>
                    <th><center> {{ trans('messages.emails') }} </center></th>
                    <th><center> {{ trans('messages.clients') }} </center></th>
                    <th><center> {{ trans('messages.jobs') }} </center></th>
                    <th><center> {{ trans('messages.when') }}  </center></th>
                    <th><center> {{ trans('messages.actions') }} </center></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>
@endsection
