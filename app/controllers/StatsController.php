<?php

namespace app\controllers;
use BaseController, Form, Input, Redirect, Response;
use Sentry, View, Log, Cache, Config, DB, Url;
use Date, App, Former, Datatables, Asset, Vd\Vd;
use Command;

// Models
use app\models\Daystats;
use app\models\Hoursstats;
use app\models\Client;
use app\models\Files;
use app\models\Media;

class StatsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        Asset::add('daterangepicker', 'assets/css/daterangepicker.css');
        Asset::add('momentmin', 'assets/js/moment.min.js');
        Asset::add('daterangepicker', 'assets/js/daterangepicker.js');
        Asset::add('amcharts', 'assets/js/amcharts.js');
        Asset::add('amchartsSerial', 'assets/js/serial.js');
        Asset::container('footer')->add('stats.js', 'assets/js/stats.js');
    }

    public function stats()
    {
        $graph="";
        $daystats = Daystats::all();
        $query = $daystats->toArray();
        $i=0;

        foreach ($query  as $row) {
            $graph[$i]['year']  = $row['data'];

            $graph[$i]['bytes'] = $row['bytes']/1024/1024/1024;

            $graph[$i]['files'] = $row['files'];
            $i++;
        }

       /* $vd= new VD;
        $vd->dump(json_encode((array) $graphBytes));*/

        /*$statsModel = new Stats;

        $statsModel->setConnection('pgsql2');

        $something = $statsModel->find(1);*/

       /* $graphFiles = DB::table('job')->where('name','=', $jobselected)
                  ->where('starttime','>=', $start )
                  ->where('endtime','<=',   $end  )
                  ->orderby('starttime', 'asc')
                  ->get(array(DB::raw('date(job.starttime) as date'), DB::raw('jobfiles as files')));
        $graphFiles = json_encode((array) $graphFiles);*/

        /*"[{"date":"2013-09-02","files":190},{"date":"2013-09-02","files":121},{"date":"2013-09-03","files":138},{"date":"2013-09-04","files":295},{"date":"2013-09-05","files":686},{"date":"2013-09-06","files":249},{"date":"2013-09-08","files":598534},{"date":"2013-09-09","files":195},{"date":"2013-09-09","files":643},{"date":"2013-09-10","files":127},{"date":"2013-09-11","files":127},{"date":"2013-09-12","files":121},{"date":"2013-09-13","files":193},{"date":"2013-09-15","files":598613},{"date":"2013-09-16","files":221},{"date":"2013-09-16","files":116},{"date":"2013-09-17","files":123},{"date":"2013-09-18","files":140},{"date":"2013-09-20","files":125},{"date":"2013-09-20","files":122},{"date":"2013-09-22","files":598641},{"date":"2013-09-23","files":185},{"date":"2013-09-23","files":31530},{"date":"2013-09-25","files":0},{"date":"2013-09-26","files":128},{"date":"2013-09-28","files":163},{"date":"2013-09-29","files":164},{"date":"2013-09-30","files":630069}]" */

        return View::make('stats',array( 'graph' => json_encode((array) $graph)));

    }

    /*Gets Data from the Jobs */
    public function gethoursstas()
    {

      $stats = Hoursstats::select(array('starttime','endtime','bytes','hoursdiff','hourbytes','timediff'));
                  

        return  Datatables::of($stats)
                   /* ->edit_column('volretention','{{ date("d", $volretention)." Days" }}')
                    ->edit_column('volumename', '{{ link_to_route("volumes", $volumename ,array("Volume" => $mediaid)) }} ')
                    ->remove_column('mediaid')*/
                    ->make();
    }


    /* Insert Stats on Database */ 
    public function insertStats()
    {
        /* Get Database Size */
        if ( Config::get('database.default')=="mysql") {
            $dbsize = DB::select('SELECT table_schema "Data Base Name",
                            SUM( data_length + index_length) / 1024 / 1024 "dbsize"
                            FROM information_schema.TABLES
                            WHERE table_schema = "'.Config::get('database.connections.mysql.database').'"
                            GROUP BY table_schema ;');
        } else {
             $dbsize= DB::select("SELECT pg_database_size('".Config::get('database.connections.pgsql.database')."') as dbsize");
        }

         // Get Server Hostname
        $servername = gethostname();

         // Get Number of Clients
        $clientsNumber=Client::all()->count();

        // Get Number of Files Transfered
        $filesNumber = DB::table('file')->select(DB::raw('count(*) AS filesNumber'))->get();

       

        // Get Storage Bytes
        $bytesStorage = Media::sum('volbytes');
       
         //* Query For Hour Starts
        $dataInicio = date('Y-m-d', strtotime("-1 days")).(' 18:29');
        $dataFim = date('Y-m-d').(' 18:29');

 
        /* Query timediff Stats */
        $timediff = DB::table('job')->select(DB::raw('(max(starttime) - min(starttime)) AS timediff'))
                    ->where('starttime','>=', $dataInicio )
                    ->where('endtime','<=', $dataFim)
                    ->get();

        $hoursdiff    = DB::table('job')->select(DB::raw("date_part('hour',  (max(starttime) - min(starttime))) AS hoursdiff"))
                    ->where('starttime','>=', $dataInicio )
                    ->where('endtime','<=', $dataFim)
                    ->get();

        $hoursbytes  = DB::table('job')->select(DB::raw("(sum(jobbytes)/date_part('hour',  (max(starttime) - min(starttime)))) AS hoursbytes"))
                    ->where('starttime','>=', $dataInicio )
                    ->where('endtime','<=', $dataFim)
                    ->get();

        $query = DB::table('job')
                    ->where('starttime','>=', $dataInicio )
                    ->where('endtime','<=', $dataFim);
                   

        $jobbytes  = $query->sum('jobbytes');   
        $starttime = $query->min('starttime');   
        $endtime   = $query->max('endtime');       
           

        /* Data for Stats to Insert*/
        $daystats = array(
            'data'   => date('Y-m-d') ,
            'server' => $servername ,
            'bytes'  => $bytesStorage,
            'files'  => $filesNumber[0]->filesnumber,
            'clients' => $clientsNumber,
            'databasesize' => $dbsize[0]->dbsize
        );


         $hourstats = array(
                'data'      => date('Y-m-d') ,
                'server'    => $servername ,
                'bytes'     => $jobbytes,
                'starttime' => $starttime,
                'endtime'   => $endtime,
                'timediff'  => $timediff[0]->timediff,
                'hoursdiff' => $hoursdiff[0]->hoursdiff,
                'hourbytes' => $hoursbytes[0]->hoursbytes
        );

            $hourstats = Hoursstats::firstOrCreate($hourstats);
            $daystats = Daystats::firstOrCreate($daystats);

    }
   



}


