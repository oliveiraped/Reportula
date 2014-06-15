<div class="span12 box-content breadcrumb">
    <div class="row-fluid">
        <center><div class="response"></div></center>
        <div class="span4">
                <h3>{{ HTML::image('assets/img/user.png') }} Jobs</h3>
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
            {{Former::text('Description', 'Description')->placeholder('Description');}}
            {{Former::text('Password', 'Password')->placeholder('Password');}}
            {{Former::text('Messages', 'Messages')->placeholder('Messages');}}
            {{Former::text('PidDirectory', 'Pid Directory')->placeholder('PidDirectory');}}
            {{Former::text('ScriptsDirectory', 'Scripts Directory')->placeholder('ScriptsDirectory');}}
            {{Former::text('QueryFile', 'Query File')->placeholder('QueryFile');}}
            {{Former::text('HeartbeatInterval', 'Heartbeat Interval')->placeholder('HeartbeatInterval');}}
            {{Former::text('MaximumConcurrentJobs', 'Max. Concurrent Jobs')->placeholder('MaximumConcurrentJobs');}}
            {{Former::text('FDConnectTimeout', 'FD Connect Timeout')->placeholder('FDConnectTimeout');}}
        </div>
        <div class="span6">
            {{Former::text('SDConnectTimeout', 'SD Connect Timeout')->placeholder('SDConnectTimeout');}}
            {{Former::text('DirPort', 'Dir Port')->placeholder('DirPort');}}
            {{Former::text('DirAddress', 'Dir Address')->placeholder('DirAddress');}}
            {{Former::text('DirSourceAddress', 'Dir Source Address')->placeholder('DirSourceAddress');}}
            {{Former::text('StatisticsRetention', 'Statistics Retention')->placeholder('StatisticsRetention');}}
            {{Former::text('DirSourceAddress', 'Dir Source Address')->placeholder('DirSourceAddress');}}
            {{Former::text('MaximumConsoleConnections', 'Max. Console Connections')->placeholder('MaximumConsoleConnections');}}
            {{Former::text('MaximumConsoleConnections', 'Max. Console Connections')->placeholder('MaximumConsoleConnections');}}
            {{Former::text('VerId', 'Ver Id')->placeholder('VerId');}}
            {{Former::text('WorkingDirectory', 'Working Directory')->placeholder('WorkingDirectory');}}
        </div>
    <div>
    {{Former::close();}}
    </div>  
 