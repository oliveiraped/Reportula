@extends('_layouts.default')
@section('main')
    <div class="container-fluid">
        <div class="row-fluid">
            <div class="row-fluid">
                <div class="span12 center login-header">
                    <center>{{ HTML::image("assets/img/logo2.png")  }}</center>
                    <br><br>
                </div><!--/span-->
            </div><!--/row-->
            <br><br>
            <div class="row-fluid center">
                <div class="well span5 center login-box">
                    <div class="alert alert-info">
                        Please login with your Username and Password.
                    </div>
                    <?php if (!is_null(Session::get('status_error'))) { echo Alert::error(Session::get('status_error')); }?>
                     @if ($errors->has('login'))
                        <div class="alert alert-error">{{ $errors->first('login', ':message') }}</div>
                     @endif
                     <div class="response-login"></div>
                    {{ Former::horizontal_open('login','post',array('class'=>'ajax', 'data-replace' => '.response-login')) }}
                    {{Former::text('username', 'Username')->prepend('<i class="icon-fam-user"></i>')->placeholder(('Username'))->autofocus()->required(); }}
                    {{Former::text('password', 'Password')->prepend('<i class="icon-fam-key"></i>')->placeholder(('Password'))->type('password')->required();}}
                    {{Form::submit(('Login'), array('class' => 'btn btn-primary'));}}
                    {{Former::close();}}
                </div><!--/span-->
            </div><!--/row-->
        </div><!--/fluid-row-->
    </div><!--/.fluid-container-->
    
@stop
