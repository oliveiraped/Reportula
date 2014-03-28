@extends('admin._layouts.default')
@section('main')
<div class="row-fluid">
    <div class="span12 box-content">
        <div class="span6 box-content breadcrumb">
            <a href="{{ URL::route('admin.readbacula') }}"> 1. Read Bacula Config </a><br>
            2. Write Bacula Config <br>
            3. Add/Edit Configurations<br>
                !!!!! THIS IS BETA !!!!! NO YET DEVELOPED 
        </div>
        <div class="span6 box-content breadcrumb">

        </div>
    </div>
</div>
@endsection
