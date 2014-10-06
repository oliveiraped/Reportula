<!DOCTYPE html>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
	</head>
  <body>
		<center><h3>Reportula - Last 24 Hours - Report - Client </h3></center>
		<div>
      <center>
          <table class="dashboardTable table table-striped table-bordered" style="width:100%">
              <thead>
                <tr>
                  <th> Job Id </th>
                  <th> Name </th>
                  <th> Start Time </th>
                  <th> End Time </th>
                  <th> Level </th>
                  <th> Job Bytes </th>
                  <th> Job Files </th>
                  <th> Job Status </th>
                </tr>
              </thead>
              <tbody>
                @foreach ($table as $user)
                <tr>
                    <td> {{ $user->jobid }}</td>
                    <td> {{ $user->name }}</td>
                    <td> {{ $user->starttime }}</td>
                    <td> {{ $user->endtime }}</td>
                    <td> {{ $user->level }}</td>
                    <td> {{ AppHelper::byte_format($user->jobbytes) }}</td>
                    <td> {{ $user->jobfiles }}</td>
                    <td> {{ $user->jobstatus }}</td>
                </tr>
                @endforeach
            </tbody>
          </table>
      </center>
  	</div>
	</body>
</html>






<style>
  .dashboardTable {
    width: 100%
    border: 1px solid #B0B0B0;
    line-height: 5px;
}
.dashboardTable tbody {
    /* Kind of irrelevant unless your .css is alreadt doing something else */
    margin: 0;
    padding: 0;
    border: 0;
    outline: 0;
    font-size: 100%;
    vertical-align: baseline;
    background: transparent;
}
.dashboardTable thead {
    text-align: left;
}
.dashboardTable thead th  {
    background: -moz-linear-gradient(top, #F0F0F0 0, #DBDBDB 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #F0F0F0), color-stop(100%, #DBDBDB));
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#F0F0F0', endColorstr='#DBDBDB', GradientType=0);
    border: 1px solid #B0B0B0;
    color: #444;
    font-size: 12px;
    font-weight: bold;
    padding: 3px 10px;
    text-align: center;
}
.dashboardTable tfoot th  {
    background: -moz-linear-gradient(top, #F0F0F0 0, #DBDBDB 100%);
    background: -webkit-gradient(linear, left top, left bottom, color-stop(0%, #F0F0F0), color-stop(100%, #DBDBDB));
    filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='#F0F0F0', endColorstr='#DBDBDB', GradientType=0);
    border: 1px solid #B0B0B0;
    color: #444;
    font-size: 12px;
    font-weight: bold;
    padding: 3px 10px;
    text-align: center;
}
.dashboardTable td {
    padding: 3px 10px;
    text-align: center;
}

table {
  max-width: 100%;
  background-color: transparent;
  border-collapse: collapse;
  border-spacing: 0;
}
.table {
  width: 100%;
  margin-bottom: 18px;
}
.table th,
.table td {
  padding: 8px;
  line-height: 18px;
  text-align: center;
  vertical-align: top;
  border-top: 1px solid #dddddd;
}
.table th {
  font-weight: bold;
}
.table thead th {
  vertical-align: bottom;
}
.table caption + thead tr:first-child th,
.table caption + thead tr:first-child td,
.table colgroup + thead tr:first-child th,
.table colgroup + thead tr:first-child td,
.table thead:first-child tr:first-child th,
.table thead:first-child tr:first-child td {
  border-top: 0;
}
.table tbody + tbody {
  border-top: 2px solid #dddddd;
}
.table-condensed th,
.table-condensed td {
  padding: 4px 5px;
}
.table-bordered {
  border: 1px solid #dddddd;
  border-collapse: separate;
  *border-collapse: collapsed;
  border-left: 0;
  -webkit-border-radius: 4px;
  -moz-border-radius: 4px;
  border-radius: 4px;
}
.table-bordered th,
.table-bordered td {
  border-left: 1px solid #dddddd;
}
.table-bordered caption + thead tr:first-child th,
.table-bordered caption + tbody tr:first-child th,
.table-bordered caption + tbody tr:first-child td,
.table-bordered colgroup + thead tr:first-child th,
.table-bordered colgroup + tbody tr:first-child th,
.table-bordered colgroup + tbody tr:first-child td,
.table-bordered thead:first-child tr:first-child th,
.table-bordered tbody:first-child tr:first-child th,
.table-bordered tbody:first-child tr:first-child td {
  border-top: 0;
}
.table-bordered thead:first-child tr:first-child th:first-child,
.table-bordered tbody:first-child tr:first-child td:first-child {
  -webkit-border-top-left-radius: 4px;
  border-top-left-radius: 4px;
  -moz-border-radius-topleft: 4px;
}
.table-bordered thead:first-child tr:first-child th:last-child,
.table-bordered tbody:first-child tr:first-child td:last-child {
  -webkit-border-top-right-radius: 4px;
  border-top-right-radius: 4px;
  -moz-border-radius-topright: 4px;
}
.table-bordered thead:last-child tr:last-child th:first-child,
.table-bordered tbody:last-child tr:last-child td:first-child {
  -webkit-border-radius: 0 0 0 4px;
  -moz-border-radius: 0 0 0 4px;
  border-radius: 0 0 0 4px;
  -webkit-border-bottom-left-radius: 4px;
  border-bottom-left-radius: 4px;
  -moz-border-radius-bottomleft: 4px;
}
.table-bordered thead:last-child tr:last-child th:last-child,
.table-bordered tbody:last-child tr:last-child td:last-child {
  -webkit-border-bottom-right-radius: 4px;
  border-bottom-right-radius: 4px;
  -moz-border-radius-bottomright: 4px;
}
.table-striped tbody tr:nth-child(odd) td,
.table-striped tbody tr:nth-child(odd) th {
  background-color: #f9f9f9;
}
.table tbody tr:hover td,
.table tbody tr:hover th {
  background-color: #f5f5f5;
}
table .span1 {
  float: none;
  width: 44px;
  margin-left: 0;
}
table .span2 {
  float: none;
  width: 124px;
  margin-left: 0;
}
table .span3 {
  float: none;
  width: 204px;
  margin-left: 0;
}
table .span4 {
  float: none;
  width: 284px;
  margin-left: 0;
}
table .span5 {
  float: none;
  width: 364px;
  margin-left: 0;
}
table .span6 {
  float: none;
  width: 444px;
  margin-left: 0;
}
table .span7 {
  float: none;
  width: 524px;
  margin-left: 0;
}
table .span8 {
  float: none;
  width: 604px;
  margin-left: 0;
}
table .span9 {
  float: none;
  width: 684px;
  margin-left: 0;
}
table .span10 {
  float: none;
  width: 764px;
  margin-left: 0;
}
table .span11 {
  float: none;
  width: 844px;
  margin-left: 0;
}
table .span12 {
  float: none;
  width: 924px;
  margin-left: 0;
}
table .span13 {
  float: none;
  width: 1004px;
  margin-left: 0;
}
table .span14 {
  float: none;
  width: 1084px;
  margin-left: 0;
}
table .span15 {
  float: none;
  width: 1164px;
  margin-left: 0;
}
table .span16 {
  float: none;
  width: 1244px;
  margin-left: 0;
}
table .span17 {
  float: none;
  width: 1324px;
  margin-left: 0;
}
table .span18 {
  float: none;
  width: 1404px;
  margin-left: 0;
}
table .span19 {
  float: none;
  width: 1484px;
  margin-left: 0;
}
table .span20 {
  float: none;
  width: 1564px;
  margin-left: 0;
}
table .span21 {
  float: none;
  width: 1644px;
  margin-left: 0;
}
table .span22 {
  float: none;
  width: 1724px;
  margin-left: 0;
}
table .span23 {
  float: none;
  width: 1804px;
  margin-left: 0;
}
table .span24 {
  float: none;
  width: 1884px;
  margin-left: 0;
}
  </style>
