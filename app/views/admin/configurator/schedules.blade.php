{{ Former::horizontal_open('admin/saveschedule','post',array('class'=>'ajax', 'data-replace' => '.response')) }}
<div class="span12 box-content breadcrumb">
    <div class="row-fluid">
        <center><div class="response"></div></center>
        <div class="span6">
            <h3>{{ HTML::image('assets/img/schedule.png') }} Schedule - {{ $Name }} | {{Form::submit( ' Save ', array('class' => 'btn btn-small btn-success' ));}} </h3>
        </div>
    </div>  
    <br>  
    <div class="row-fluid">
        <div class="span6">
            {{Former::hidden('id')->id('id')->value($id);}}
            {{Former::text('Name', 'Name')->placeholder('Name')->required()->autofocus()->value($Name);}}
            {{Former::text('Run', 'Run')->placeholder('Run');}}
        </div>
    <div>
    {{Former::close();}}
</div>  


