@extends('admin._layouts.default')
@section('main')
<div class="row-fluid">
    <div class="span12">
       <center><h3>{{ HTML::image('assets/img/check.png'),'Install Sucess' }} </h3></center>
    </div>
</div>
<br>
<br>
<br>
<div class="row-fluid">
    <div class="span3">
    </div>
    <div class="span3">
        <a href="{{ URL::route('login') }}" class=" ">
            <center>
                {{ HTML::image('assets/img/login.png') }}
            </center>
            <center>Front Login</center>
        </a>
    </div>
    <div class="span3">
        <a href="{{ URL::route('admin.login') }}" class=" ">
           <center>{{ HTML::image('assets/img/admin.png') }} </center>
           <center>Administration Login</center>
        </a>
    </div>
    <div class="span3">
    </div>
</div>
  <br>
<div class="row-fluid">
     <div class="span12">
        <center>
             <br>  <br> <br>  <br>
        <font color="red"><h3> Dont Forget to add this line to crontab to activate statistics </h3> </font>
        <br>  <br>
        <font color="blue "><h3>00 12 * * * php /var/www/html/reportula/artisan BaculaStats:collect </h3> </font>
          <br>  <br>
            <br>  <br>
        </center>
    </div>
</div>

@endsection
