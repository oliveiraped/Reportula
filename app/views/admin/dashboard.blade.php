@extends('admin._layouts.default')
@section('main')
<div class="row-fluid">
    <div class="span12 box-content">
        <div class="span6 box-content breadcrumb">
            <center><h3>{{ trans('messages.news') }} </h3></center></br>
            	@foreach ($rss->get_items(0,3) as $item)
    				<div class="item">
						<h4 class="title"><a href="{{ $item->get_permalink(); }}">{{ $item->get_title(); }} </a></h4>
						{{ $item->get_description(); }}
						<p><small> {{ trans('messages.published') }} {{ $item->get_date('j F Y | g:i a'); }} </small></p>
					</div>
				@endforeach
        </div>
        <div class="span6 box-content breadcrumb">
            <center><h3>{{ trans('messages.serverinfo') }}</h3></center></br>
            <b>System : </b> {{ $system }}</br>
			<b>Host   : </b> {{ $host }}</br>
 			<b>Kernel : </b> {{ $kernel }}</br>
            <b>Server Uptime : </b> {{ $uptime }}</br>
            <b>Used Memory   : </b> {{ $used_mem  }}</br>
            <b>Used Swap     : </b> {{$used_swap }}</br>
            <b>Free Memory  : </b> {{  $free_mem }}</br>
		</div>
    </div>
</div>
@endsection
