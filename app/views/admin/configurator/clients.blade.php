<div class="span12 box-content breadcrumb">
    <div class="row-fluid">
        <center><div class="response"></div></center>
        <div class="span6">
                <h3>{{ HTML::image('assets/img/user.png') }} Client - {{ $Name }}</h3>
        </div>
        <div class="span4 pull-right">
                {{Form::submit( ' Save ', array('class' => 'btn btn-large btn-primary' ));}}
                 <a href="{{ URL::route('admin.users') }}" class="btn btn-large">
                    <i class="icon-fam-cross"></i>Close
                </a>
        </div>
    </div>
    <div class="row-fluid">

    {{ Former::horizontal_open('configurator/savedirector','post',array('class'=>'ajax', 'data-replace' => '.response')) }}
        <div class="span6">
            {{Former::hidden('id')->id('id')->value($id);}}
            {{Former::text('Name', 'Name')->placeholder('Name')->required()->autofocus()->value($Name);}}
            {{Former::text('Address', 'Address')->placeholder('Address');}}
            {{Former::text('FDPort', 'FDPort')->placeholder('FDPort');}}
            {{Former::text('FileRetention', 'File Retention')->placeholder('FileRetention');}}
            {{Former::text('AutoPrune', 'Auto Prune')->placeholder('AutoPrune');}}
            {{Former::text('Priority', 'Priority')->placeholder('Priority');}}
            {{Former::text('JobRetention', 'Job Retention')->placeholder('JobRetention');}}
            {{Former::text('HeartbeatInterval', 'Heartbeat Interval')->placeholder('HeartbeatInterval');}}
            {{Former::text('MaximumConcurrentJobs', 'Max. Concurrent Jobs')->placeholder('MaximumConcurrentJobs');}}
        </div>
    <div>
    {{Former::close();}}
</div>  
 

  
  
