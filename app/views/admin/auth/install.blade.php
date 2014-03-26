@extends('admin._layouts.default')
@section('main')
<div class="row-fluid">
    <div class="span12">
       <center><h1>{{ HTML::image('assets/img/database-icon.png'),('Reportula Instalation') }} </h1></center>
    </div>
    <div class="span12">
        <center>
            @if (Session::has('status_error')) {{ Alert::error(Session::get('status_error')) }}
            @endif
            @if (Session::has('status')) {{ Alert::success(Session::get('status')) }}
            @endif
        </center>
    </div>
    <hr>
</div>

<div class="row-fluid">
    <div class="span12"><div class="span3"></div>
        <div class="span4">
            <h4>Database Setting</h4>
            <p>
                Please ensure following configuration is correct based on your <code>application/config/database.php</code>.
            </p>
            <hr>
            {{ Former::horizontal_open('install/installSave', 'POST', array('class' => 'form user ajax')) }}

            {{Former::text('databaseengine', ('Database Engine'))->prepend('<i class="icon-fam-wrench"></i>')->placeholder(('mysql postgres'))->value($database['driver']);}}

            {{Former::text('databasehost', ('Database Host'))->prepend('<i class="icon-fam-computer"></i>')->placeholder(('192.168.1.1'))->value($database['host']);}}

            {{Former::text('databasename', ('Database Name'))->prepend('<i class="icon-fam-database"></i>')->placeholder(('bacula'))->value($database['database']);}}

            {{Former::text('databaseuser', ('Database User'))->prepend('<i class="icon-fam-user"></i>')->placeholder(('baculauser'))->value($database['username']);}}

            {{Former::password('databasepassword', ('Database Password'))->prepend('<i class="icon-fam-key"></i>')->placeholder(('password'))->value($database['password']);}}
            <div class="response-check-db"></div>
            <div class="controls">
                <a href="install/testDb" class="btn btn-small btn-info ajax"  data-method="post" data-replace=".response-check-db"><i class="icon-fam-exclamation userstate"></i> Check Database Connection </a>
            </div>
            <br>
            <h4>Administration Setting</h4>
            <p>
                Please configure your user administration.
            </p>
            <hr>
            {{Former::email('email', ('Email'))->prepend('<i class="icon-fam-email"></i>')->placeholder(('xxxxx@xxxx.xx'))->required();}}
            {{Former::password('password', ('Password'))->prepend('<i class="icon-fam-key"></i>')->placeholder(('Password'))->required();}}

            <div class="controls">
                <center>
                    {{ Form::submit( ('Install'), array('class' => 'btn btn-large btn-primary', )); }}
                </center>
            </div>
            {{Former::close();}}
        </div>
    </div>
</div>
@stop
