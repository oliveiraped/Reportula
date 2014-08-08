{{ Former::horizontal_open('admin/saveconfiguration','post',array('class'=>'ajax', 'data-replace' => '.response')) }}
<div class="span12 box-content breadcrumb">
    <div class="row-fluid">
        <center><div class="response"></div></center>
        <div class="span6">
            <h3>{{ HTML::image('assets/img/console.png') }} Console - {{ $Name }} | {{Form::submit( ' Save ', array('class' => 'btn btn-small btn-success' ));}}
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
            {{Former::text('Password', 'Password')->placeholder('Password')->value($Password);}}
            {{Former::text('JobACL', 'JobACL')->placeholder('JobACL')->value($JobACL);}}
			{{Former::text('ClientACL', 'ClientACL')->placeholder('ClientACL')->value($ClientACL);}}
            {{Former::text('StorageACL', 'StorageACL')->placeholder('StorageACL')->value($StorageACL);}}
            {{Former::text('ScheduleACL', 'ScheduleACL')->placeholder('ScheduleACL')->value($ScheduleACL);}}
            {{Former::text('PoolACL', 'PoolACL')->placeholder('PoolACL')->value($PoolACL);}}
			{{Former::text('FileSetACL', 'FileSetACL')->placeholder('FileSetACL')->value($FileSetACL);}}
            {{Former::text('CatalogACL', 'CatalogACL')->placeholder('CatalogACL')->value($CatalogACL);}}
            {{Former::text('CommandACL', 'CommandACL')->placeholder('CommandACL')->value($CommandACL);}}
            {{Former::text('WhereACL', 'WhereACL')->placeholder('WhereACL')->value($WhereACL);}}
        </div>
    <div>
    {{Former::close();}}
</div>
