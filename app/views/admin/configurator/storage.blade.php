{{ Former::horizontal_open('admin/saveconfiguration','post',array('class'=>'ajax', 'data-replace' => '.response')) }}
<div class="span12 box-content breadcrumb">
    <div class="row-fluid">
        <center><div class="response"></div></center>
        <div class="span12">
            <h3>{{ HTML::image('assets/img/storage.png') }} Storage - {{ $Name }} | {{Form::submit( ' Save ', array('class' => 'btn btn-small btn-success' ));}}
                @unless ($Name=="")
                   |<a onclick='deleteitem("{{$config}}","{{$id}}" );' class='btn btn-small btn-danger'> Delete </a>
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
            {{Former::text('SDPort', 'SDPort')->placeholder('SDPort')->value($SDPort)->required();}}
            {{Former::text('Device', 'Device')->placeholder('Device')->value($Device)->required();}}
            {{Former::text('MediaType', 'Media Type')->placeholder('MediaType')->value($MediaType)->required();}}

            {{Former::select('Autochanger', 'Autochanger')->options(array("Yes"=>"Yes","No"=>"No") )->id('Autochanger'); }}

            {{Former::text('HeartbeatInterval', 'Heartbeat Interval')->placeholder('HeartbeatInterval')->value($HeartbeatInterval)->required();}}
            {{Former::text('MaximumConcurrentJobs', 'Max. Concurrent Jobs')->placeholder('MaximumConcurrentJobs')->value($MaximumConcurrentJobs)->required();}}
            {{Former::text('Address', 'Address')->placeholder('Address')->value($Name)->required();}}
            {{Former::text('Run', 'Run')->placeholder('Run')->value($Run);}}
            {{Former::text('AllowCompression', 'Allow Compression')->placeholder('AllowCompression')->value($AllowCompression);}}
        </div>
    <div>
    {{Former::close();}}
    </div>


