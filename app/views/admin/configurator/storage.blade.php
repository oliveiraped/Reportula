<div class="span12 box-content breadcrumb">
    <div class="row-fluid">
        <center><div class="response"></div></center>
        <div class="span4">
                <h3>{{ HTML::image('assets/img/user.png') }} Storage</h3>
        </div>
        <div class="span2 pull-right">
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
            {{Former::text('Run', 'Run')->placeholder('Run');}}
            {{Former::text('SDPort', 'SDPort')->placeholder('SDPort');}}
            {{Former::text('Device', 'Device')->placeholder('Device');}}
            {{Former::text('MediaType', 'Media Type')->placeholder('MediaType');}}
            {{Former::text('Autochanger', 'Autochanger')->placeholder('Autochanger');}}
            {{Former::text('AllowCompression', 'Allow Compression')->placeholder('AllowCompression');}}
            {{Former::text('HeartbeatInterval', 'Heartbeat Interval')->placeholder('HeartbeatInterval');}}
            {{Former::text('MaximumConcurrentJobs', 'Max. Concurrent Jobs')->placeholder('MaximumConcurrentJobs');}}
            {{Former::text('Address', 'Address')->placeholder('Address');}}
            
        </div>
        
    <div>
    {{Former::close();}}
    </div>  

 
  

 

