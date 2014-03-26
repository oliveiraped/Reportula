@extends('_layouts.default')
@section('main')
<div class="row-fluid">
    <div class="span12 box-content">
        <center><h4>Transfered Bytes</h4></center>
        <div id="chartBytes" style="min-width: 100%; height: 400px; margin: 0 auto" class="span12 box-content breadcrumb"></div>
    </div>
</div>
<div class="row-fluid">
    <div class="span12 box-content ">
         <center><h4>Transfered Files</h4></center>
        <div id="chartFiles" style="min-width: 100%; height: 400px; margin: 0 auto" class="span12 box-content breadcrumb"></div>
    </div>
</div>

<div class="row-fluid">
    <div class="span12 box-content ">
        <center><h4>Average Day Backup Times/Size </h4></center>
        <table id="statsTable" class="dashboardTable table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th><center> Started Time </center></th>
                    <th><center> Ended Time </center></th>
                    <th><center> Bytes </center></th>
                    <th><center> Backup Hours </center></th>
                    <th><center> Bytes/Hour </center></th>
                    <th><center> Time Taken </center></th>
                </tr>
            </thead>
            <tbody>
            </tbody>

        </table>
    </div>
</div>

<script type="text/javascript">
    /* Code For The Graphs */
    <?php echo "var chartData = ". $graph . ";\n"; ?>
</script>
@endsection
