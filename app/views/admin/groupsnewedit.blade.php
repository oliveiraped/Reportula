@extends('admin._layouts.default')
@section('main')
{{ Former::horizontal_open('admin/savegroup','post',array('class'=>'ajax', 'data-replace' => '.response')) }}

{{ HTML::script('assets/js/admin/groups.js') }}
<div class="row-fluid">
    <div class="span12 box-content">
        <div class="span4">
            <h3>{{ HTML::image('assets/img/groups.png') }} New Group</h3>
        </div>
</div>

<div class="row-fluid">

   <div class="span12 box-content">
        <div class="span6 box">
            <div class="box-header well">
                <h2><i class="icon-user"></i> Group Data</h2>
            </div>
            <div class="box-content">
                {{Former::hidden('id')->id('id')->value($id);}}
                {{Former::text('name', 'Name')->prepend('<i class="icon-fam-user"></i>')->placeholder('Name')->required()->value($groupname);}}
            </div>
        </div>

        <div class="span6 box">
            <div class="box-header well">
                <h2><i class="icon-th"></i>User Groups</h2>
            </div>
            <div class="box-content">

                {{Former::select('usergroups[]','Users')->options($users, $userSelected)->multiple('multiple')->id('usergroups') }}

            </div>
        </div>
    </div>
    <div class="row-fluid">
    <div class="span12 box-content">
        <div class="span6 box">
            <div class="box-header well">
                <h2><i class="icon-th"></i>Clients Permissions</h2>
            </div>
            <div class="box-content">
                {{Former::select('groupClients[]','Clients')->options($clients, $clientsSelected)->multiple('multiple')->id('groupclients') }}
            </div>
        </div>
        <div class="span6 box">
            <div class="box-header well">
                <h2><i class="icon-th"></i>Jobs Permissions</h2>
            </div>
            <div class="box-content">
                {{Former::select('groupJobs[]','Jobs')->options($jobs, $jobsSelected)->multiple('multiple')->id('groupjobs') }}
            </div>
        </div>
    </div>
</div>
    <div class="span12 box-content">
        <center>
            <div class="response"></div>
            {{Form::submit( ' Save ', array('class' => 'btn btn-large btn-primary' ));}}
             <a href="{{ URL::route('admin.groups') }}" class="btn btn-large">
                <i class="icon-fam-cross"></i>Close
            </a>
        </center>
    </div>
</div>

{{Former::close();}}
@endsection
