{{ Former::horizontal_open('admin/saveconfiguration','post',array('class'=>'ajax', 'data-replace' => '.response')) }}
<div class="span12 box-content breadcrumb">
    <div class="row-fluid">
        <center><div class="response"></div></center>
        <div class="span12">
                <h3>{{ HTML::image('assets/img/director.png') }} Director - {{ $Name }} | {{Form::submit( ' Save ', array('class' => 'btn btn-small btn-success' ));}}
                @unless ($Name=="")
                   | <a onclick='deleteitem("{{$config}}","{{$id}}" );' class='btn btn-small btn-info btn-danger'> Delete </a></h3>
                @endunless
        </div>
    </div>
    <br>
    <div class="row-fluid">
        <div class="span6">
            {{Former::hidden('config')->id('config')->value($config);}}
            {{Former::hidden('id')->id('id')->value($id);}}

            {{Former::text('Name', 'Name')->placeholder('Name')->required()->autofocus()->value($Name);}}
            {{Former::text('Password', 'Password')->placeholder('Password')->value($Password)->required();}}
            {{Former::text('DirPort', 'Dir Port')->placeholder('DirPort')->value($DirPort)->required();}}

            {{Former::text('Messages', 'Messages')->placeholder('Messages')->value($Messages)->required();}}
            {{Former::text('PidDirectory', 'Pid Directory')->placeholder('PidDirectory')->value($PidDirectory)->required();}}
            {{Former::text('QueryFile', 'Query File')->placeholder('QueryFile')->value($QueryFile)->required();}}
            {{Former::text('WorkingDirectory', 'Working Directory')->placeholder('WorkingDirectory')->value($WorkingDirectory)->required();}}
            {{Former::text('HeartbeatInterval', 'Heartbeat Interval')->placeholder('HeartbeatInterval')->value($HeartbeatInterval)->required();}}
            {{Former::text('MaximumConcurrentJobs', 'Max. Concurrent Jobs')->placeholder('MaximumConcurrentJobs')->value($MaximumConcurrentJobs)->required();}}
             {{Former::text('ScriptsDirectory', 'Scripts Directory')->placeholder('ScriptsDirectory')->value($ScriptsDirectory);}}

        </div>
        <div class="span6">
            {{Former::text('SDConnectTimeout', 'SD Connect Timeout')->placeholder('SDConnectTimeout')->value($SDConnectTimeout);}}
            {{Former::text('Description', 'Description')->placeholder('Description')->value($Description);}}

            {{Former::text('DirAddress', 'Dir Address')->placeholder('DirAddress')->value($DirAddress);}}
            {{Former::text('DirSourceAddress', 'Dir Source Address')->placeholder('DirSourceAddress')->value($DirSourceAddress);}}
            {{Former::text('StatisticsRetention', 'Statistics Retention')->placeholder('StatisticsRetention')->value($StatisticsRetention);}}
            {{Former::text('DirSourceAddress', 'Dir Source Address')->placeholder('DirSourceAddress')->value($DirSourceAddress);}}
            {{Former::text('MaximumConsoleConnections', 'Max. Console Connections')->placeholder('MaximumConsoleConnections')->value($MaximumConsoleConnections);}}
            {{Former::text('MaximumConsoleConnections', 'Max. Console Connections')->placeholder('MaximumConsoleConnections')->value($MaximumConsoleConnections);}}
            {{Former::text('VerId', 'Ver Id')->placeholder('VerId')->value($VerId);}}
            {{Former::text('FDConnectTimeout', 'FD Connect Timeout')->placeholder('FDConnectTimeout')->value($FDConnectTimeout);}}

        </div>
    <div>
    {{Former::close();}}
    </div>

