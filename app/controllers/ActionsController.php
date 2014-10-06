<?php

namespace app\controllers;
use BaseController, Form, Input, Redirect, Response;
use Sentry, View, Log, Cache, Config, DB, Url;
use Date, App, Former, Datatables, Asset;
use Command;

// Models
use app\models\Daystats;
use app\models\Hoursstats;
use app\models\Client;
use app\models\Files;
use app\models\Media;

class ActionsController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        Asset::add('daterangepicker', 'assets/css/daterangepicker.css');

        Asset::add('momentmin', 'assets/js/moment.min.js');
        Asset::add('daterangepicker', 'assets/js/daterangepicker.js');


        Asset::container('footer')->add('stats.js', 'assets/js/stats.js');
    }

    public function actions()
    {
        return View::make('actions');
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


