@extends('_layouts.default')
@section('main')
<div class="row-fluid">
    <div class="span12 box-content">
        <div style="height:500px;" class="demo" id="demo1">
            <ul>

                   {{ $menu }}

             </ul>
      </div>
    </div>
</div>
<hr>

<script type="text/javascript" class="source below">

    $(function () {
    $("#demo1").jstree({
        "plugins" : [  "html_data","themes","search" ]
    });
});

</script>
@endsection
