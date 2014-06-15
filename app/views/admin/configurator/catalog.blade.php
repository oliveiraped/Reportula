<div class="span12 box-content breadcrumb">
    <div class="row-fluid">
        <center><div class="response"></div></center>
        <div class="span6">
                <h3>{{ HTML::image('assets/img/user.png') }} Catalog - {{ $Name }}</h3>
        </div>
        <div class="span4 pull-right">
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
            {{Former::text('DBPassword', 'DBPassword')->placeholder('DBPassword');}}
            {{Former::text('DBName', 'DBName')->placeholder('DBName');}}
			{{Former::text('DBuser', 'DBuser')->placeholder('DBuser');}}
            {{Former::text('DBSocket', 'DBSocket')->placeholder('DBSocket');}}
            {{Former::text('DBAddress', 'DBAddress')->placeholder('DBAddress');}}
            {{Former::text('DBPort', 'DBPort')->placeholder('DBPort');}}
			{{Former::text('DBDriver', 'DBDriver')->placeholder('DBDriver');}}
        </div>
    <div>
    {{Former::close();}}
</div>  
