{{ Former::horizontal_open('admin/saveconfiguration','post',array('class'=>'ajax', 'data-replace' => '.response')) }}
<div class="span12 box-content breadcrumb">
    <div class="row-fluid">
        <center><div class="response"></div></center>
        <div class="span6">
            <h3>{{ HTML::image('assets/img/catalog.jpg') }} Catalog - {{ $Name }} | {{Form::submit( ' Save ', array('class' => 'btn btn-small btn-success' ));}}
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
            {{Former::text('DBPassword', 'DBPassword')->placeholder('DBPassword')->value($DBPassword)->required();}}
            {{Former::text('DBName', 'DBName')->placeholder('DBName')->value($DBName)->required();}}
			{{Former::text('DBUser', 'DBuser')->placeholder('DBUser')->value($DBUser)->required();}}
            {{Former::text('DBSocket', 'DBSocket')->placeholder('DBSocket')->value($DBSocket);}}
            {{Former::text('DBAddress', 'DBAddress')->placeholder('DBAddress')->value($DBAddress);}}
            {{Former::text('DBPort', 'DBPort')->placeholder('DBPort')->value($DBPort);}}
        </div>
    <div>
    {{Former::close();}}
</div>
