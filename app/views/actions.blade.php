@extends('_layouts.default')
@section('main')

<div class="row-fluid">
    <div class="span12 box-content">
        <div class="span8 box-content breadcrumb">
             {{ @Form::hidden('jobid', $jobid, array("id"=>"jobid")) }}
            <h5>Job Files</h5>
            <hr>
            <br>
            <table id="filesTable" class="dashboardTable table table-striped table-bordered" style="width:100%">
                <thead>
                    <tr>
                        <th><center>Path</center></th>
                        <th><center>Filename </center></th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

        </div>
        <div class="span4 box-content breadcrumb">
            <h5>Job Log</h5>
            <hr>
            <p class="text-success">
              {{ @$logs }}
            </p>
        </div>
    </div>
</div>
<hr>
@endsection
