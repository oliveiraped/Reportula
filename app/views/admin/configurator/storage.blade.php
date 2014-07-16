{{ Former::horizontal_open('admin/saveconfiguration','post',array('class'=>'ajax', 'data-replace' => '.response')) }}
<div class="span12 box-content breadcrumb">
    <div class="row-fluid">
        <center><div class="response"></div></center>
        <div class="span12">
            <h3>{{ HTML::image('assets/img/storage.png') }} Storage - {{ $Name }} | {{Form::submit( ' Save ', array('class' => 'btn btn-small btn-success' ));}} </h3>
        </div>
    </div>
     <br>
    <div class="row-fluid">
        <div class="span6">
                        {{Former::hidden('config')->id('config')->value($config);}}

            {{Former::hidden('id')->id('id')->value($id);}}
            {{Former::text('Name', 'Name')->placeholder('Name')->required()->autofocus()->value($Name);}}
            {{Former::text('Run', 'Run')->placeholder('Run')->value($Run);}}
            {{Former::text('SDPort', 'SDPort')->placeholder('SDPort')->value($SDPort);}}
            {{Former::text('Device', 'Device')->placeholder('Device')->value($Device);}}
            {{Former::text('MediaType', 'Media Type')->placeholder('MediaType')->value($MediaType);}}
            {{Former::text('Autochanger', 'Autochanger')->placeholder('Autochanger')->value($Autochanger);}}
            {{Former::text('AllowCompression', 'Allow Compression')->placeholder('AllowCompression')->value($AllowCompression);}}
            {{Former::text('HeartbeatInterval', 'Heartbeat Interval')->placeholder('HeartbeatInterval')->value($HeartbeatInterval);}}
            {{Former::text('MaximumConcurrentJobs', 'Max. Concurrent Jobs')->placeholder('MaximumConcurrentJobs')->value($MaximumConcurrentJobs);}}
            {{Former::text('Address', 'Address')->placeholder('Address')->value($Name);}}
        </div>
    <div>
    {{Former::close();}}
    </div>  
 

