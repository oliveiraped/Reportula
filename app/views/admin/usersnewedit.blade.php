@extends('admin._layouts.default')
@section('main')
{{ Former::horizontal_open('admin/saveuser','post',array('class'=>'ajax', 'data-replace' => '.response')) }}

{{ HTML::script('assets/js/admin/users.js') }}
<div class="row-fluid">
    <div class="span12 box-content">
        <center><div class="response"></div></center>
        <div class="span4">
            <h3>{{ HTML::image('assets/img/user.png') }} New User</h3>
        </div>
        <div class="span2 pull-right">
            {{Form::submit( ' Save ', array('class' => 'btn btn-large btn-primary' ));}}
             <a href="{{ URL::route('admin.users') }}" class="btn btn-large">
                <i class="icon-fam-cross"></i>Close
            </a>
        </div>
    </div>
</div>  

<div class="row-fluid">
   <div class="span12 box-content">
        <div class="span6 box">
            <div class="box-header well">
                <h2><i class="icon-user"></i> User Data</h2>
            </div>
            <div class="box-content">

                {{Former::hidden('id')->id('id')->value($id);}}
                {{Former::email('email', 'Email')->prepend('<i class="icon-fam-email-add"></i>')->placeholder('Email')->required()->autofocus()->value($email);}}
                {{Former::password('password', 'Password' )->prepend('<i class="icon-fam-key-add"></i>')->placeholder( 'Password')->required()->min(6);}}
                {{Former::text('first_name', 'Name')->prepend('<i class="icon-fam-user"></i>')->placeholder('Name');}}
                {{Former::text('last_name', 'Surename')->prepend('<i class="icon-fam-user"></i>')->placeholder('Surename');}}
            </div>
        </div>
        <div class="span6 box">
            <div class="box-header well">
                <h2><i class="icon-th"></i>User Groups</h2>
            </div>
            <div class="box-content">
                {{Former::select('usergroups[]','Groups')->options($groups, $groupSelected)->multiple('multiple')->id('usergroups') }}
            </div>
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
                {{Former::select('userClients[]','Clients')->options($clients, $clientsSelected)->multiple('multiple')->id('userclients') }}
            </div>
        </div>
        <div class="span6 box">
            <div class="box-header well">
                <h2><i class="icon-th"></i>Jobs Permissions</h2>
            </div>
            <div class="box-content">
                {{Former::select('userJobs[]','Jobs')->options($jobs, $jobsSelected)->multiple('multiple')->id('userjobs') }}
            </div>
        </div>
    </div>
</div>

{{Former::close();}}
@endsection
