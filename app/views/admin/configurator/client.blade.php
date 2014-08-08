{{ Former::horizontal_open('admin/saveconfiguration','post',array('class'=>'ajax', 'data-replace' => '.response')) }}
<div class="span12 box-content breadcrumb">
    <div class="row-fluid">
        <center><div class="response"></div></center>
        <div class="span6">
            <h3>{{ HTML::image('assets/img/clients.jpg') }} Client - {{ $Name }} | {{Form::submit( ' Save ', array('class' => 'btn btn-small btn-success' ));}}
                @unless ($Name=="")
                   | <a onclick='deleteitem("{{$config}}","{{$id}}" );' class='btn btn-small btn-danger'> Delete </a>
                @endunless
            </h3>
        </div>
    </div>
    <br>
    <div class="row-fluid">
        <div class="span6">
            {{Former::hidden('config')->id('config')->value($config);}}
            {{Former::hidden('id')->id('id')->value($id);}}
            {{Former::text('Name', 'Name')->placeholder('Name')->required()->autofocus()->value($Name);}}
            {{Former::text('Address', 'Address')->placeholder('Address')->required();}}
            {{Former::text('FDPort', 'FDPort')->placeholder('FDPort');}}
            {{Former::text('Password', 'Password')->placeholder('Password')->required();}}
            {{Former::text('Catalog', 'Catalog')->placeholder('Catalog')->required();}}
            {{Former::text('FileRetention', 'File Retention')->placeholder('FileRetention')->required();}}
            {{Former::select('AutoPrune', 'AutoPrune')->options(array("Yes"=>"Yes","No"=>"No") )->id('AutoPrune'); }}
            {{Former::text('JobRetention', 'Job Retention')->placeholder('JobRetention')->required();}}
            {{Former::text('HeartbeatInterval', 'Heartbeat Interval')->placeholder('HeartbeatInterval')->required();}}
            {{Former::text('MaximumConcurrentJobs', 'Max. Concurrent Jobs')->placeholder('MaximumConcurrentJobs')->required();}}
            {{Former::text('Priority', 'Priority')->placeholder('Priority');}}
        </div>
    <div>
    {{Former::close();}}
</div>




