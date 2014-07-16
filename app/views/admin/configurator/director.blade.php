{{ Former::horizontal_open('admin/saveconfiguration','post',array('class'=>'ajax', 'data-replace' => '.response')) }}
<div class="span12 box-content breadcrumb">
    <div class="row-fluid">
        <center><div class="response"></div></center>
        <div class="span6">
                <h3>{{ HTML::image('assets/img/director.png') }} Director - {{ $Name }} | {{Form::submit( ' Save ', array('class' => 'btn btn-small btn-success' ));}}</h3>
        </div>
    </div>
    <br>
    <div class="row-fluid">
        <div class="span6">
            {{Former::hidden('config')->id('config')->value($config);}}
            {{Former::hidden('id')->id('id')->value($id);}}
            {{Former::text('Name', 'Name')->placeholder('Name')->required()->autofocus()->value($Name);}}
            {{Former::text('Description', 'Description')->placeholder('Description')->value($Description);}}
            {{Former::text('Password', 'Password')->placeholder('Password')->value($Password);}}
            {{Former::text('Messages', 'Messages')->placeholder('Messages')->value($Messages);}}
            {{Former::text('PidDirectory', 'Pid Directory')->placeholder('PidDirectory')->value($PidDirectory);}}
            {{Former::text('ScriptsDirectory', 'Scripts Directory')->placeholder('ScriptsDirectory')->value($ScriptsDirectory);}}
            {{Former::text('QueryFile', 'Query File')->placeholder('QueryFile')->value($QueryFile);}}
            {{Former::text('HeartbeatInterval', 'Heartbeat Interval')->placeholder('HeartbeatInterval')->value($HeartbeatInterval);}}
            {{Former::text('MaximumConcurrentJobs', 'Max. Concurrent Jobs')->placeholder('MaximumConcurrentJobs')->value($MaximumConcurrentJobs);}}
            {{Former::text('FDConnectTimeout', 'FD Connect Timeout')->placeholder('FDConnectTimeout')->value($FDConnectTimeout);}}
        </div>
        <div class="span6">
            {{Former::text('SDConnectTimeout', 'SD Connect Timeout')->placeholder('SDConnectTimeout')->value($SDConnectTimeout);}}
            {{Former::text('DirPort', 'Dir Port')->placeholder('DirPort')->value($DirPort);}}
            {{Former::text('DirAddress', 'Dir Address')->placeholder('DirAddress')->value($DirAddress);}}
            {{Former::text('DirSourceAddress', 'Dir Source Address')->placeholder('DirSourceAddress')->value($DirSourceAddress);}}
            {{Former::text('StatisticsRetention', 'Statistics Retention')->placeholder('StatisticsRetention')->value($StatisticsRetention);}}
            {{Former::text('DirSourceAddress', 'Dir Source Address')->placeholder('DirSourceAddress')->value($DirSourceAddress);}}
            {{Former::text('MaximumConsoleConnections', 'Max. Console Connections')->placeholder('MaximumConsoleConnections')->value($MaximumConsoleConnections);}}
            {{Former::text('MaximumConsoleConnections', 'Max. Console Connections')->placeholder('MaximumConsoleConnections')->value($MaximumConsoleConnections);}}
            {{Former::text('VerId', 'Ver Id')->placeholder('VerId')->value($VerId);}}
            {{Former::text('WorkingDirectory', 'Working Directory')->placeholder('WorkingDirectory')->value($WorkingDirectory);}}
        </div>
    <div>
    {{Former::close();}}
    </div>  
 