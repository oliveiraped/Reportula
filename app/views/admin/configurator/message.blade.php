{{ Former::horizontal_open('admin/saveconfiguration','post',array('class'=>'ajax', 'data-replace' => '.response')) }}
<div class="span12 box-content breadcrumb">
    <div class="row-fluid">
        <center><div class="response"></div></center>
        <div class="span6">
            <h3>{{ HTML::image('assets/img/message.png') }} Messages - {{ $Name }} | {{Form::submit( ' Save ', array('class' => 'btn btn-small btn-success' ));}}
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
            {{Former::text('MailCommand', 'MailCommand')->placeholder('MailCommand')->value($MailCommand);}}
            {{Former::text('OperatorCommand', 'OperatorCommand')->placeholder('OperatorCommand')->value($OperatorCommand);}}
			{{Former::text('append', 'append')->placeholder('append')->value($append);}}
            {{Former::text('operator', 'operator')->placeholder('operator')->value($operator);}}
            {{Former::text('console', 'console')->placeholder('console')->value($console);}}
            {{Former::text('mailonerror', 'mailonerror')->placeholder('mailonerror')->value($mailonerror);}}
			{{Former::text('catalog', 'catalog')->placeholder('catalog')->value($catalog);}}
        </div>
    <div>
    {{Former::close();}}
</div>
