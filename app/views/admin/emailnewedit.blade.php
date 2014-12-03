@extends('admin._layouts.default')
@section('main')
{{ Former::horizontal_open('admin/saveemails','post',array('class'=>'ajax', 'data-replace' => '.response')) }}
{{ HTML::script('assets/js/admin/emails.js') }}

<div class="row-fluid">
    <div class="span12 box-content">
        <center><div class="response"></div></center>
        <div class="span4">
            <h3>{{ HTML::image('assets/img/email.jpg') }} {{ trans('messages.newreportemail') }} </h3>
        </div>
        <div class="span2 pull-right">
            {{Form::submit( trans('messages.save') , array('class' => 'btn btn-large btn-primary' ));}}
             <a href="{{ URL::route('admin.emails') }}" class="btn btn-large">
                <i class="icon-fam-cross"></i>{{ trans('messages.close') }}
            </a>
        </div>
    </div>
</div>

<div class="row-fluid">
   <div class="span12 box-content">
        <div class="span12 box">
            <div class="box-header well">
                <h2><i class="icon-mail"></i> {{ trans('messages.reportdata') }} </h2>
            </div>
            <div class="box-content">
                {{Former::hidden('id')->id('id')->value($id);}}
                {{Former::text('emails', 'Email\'s')->prepend('<i class="icon-fam-email-add"></i>')->placeholder('email@email.com, email2@email.com, email3@email.com')->required()->autofocus()->value($emails);}}
                {{Former::select('messages.when', 'When')->options($when, $whenSelected)->prepend('<i class="icon-fam-user"></i>');}}
            </div>
        </div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12 box-content">
        <div class="span6 box">
            <div class="box-header well">
                <h2><i class="icon-th"></i> {{ trans('messages.selectclient') }} </h2>
            </div>
            <div class="box-content">
                {{Former::select('emailsClients[]','messages.clients')->options($clients, $clientsSelected)->multiple('multiple')->id('emailsclients') }}
            </div>
        </div>
        <div class="span6 box">
            <div class="box-header well">
                <h2><i class="icon-th"></i> {{ trans('messages.selectjob') }} </h2>
            </div>
            <div class="box-content">
                {{Former::select('emailsJobs[]','messages.jobs')->options($jobs, $jobsSelected)->multiple('multiple')->id('emailsjobs') }}
            </div>
        </div>
    </div>
</div>
{{Former::close();}}
@endsection
