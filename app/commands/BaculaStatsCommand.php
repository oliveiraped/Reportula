<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;


// Models
use app\models\Daystats;
use app\models\Hoursstats;
use app\models\Client;
use app\models\Files;
use app\models\Media;

class BaculaStatsCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'BaculaStats:collect';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Collects Daily Bacula Stats';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire(){


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

        $hoursdiff    = DB::table('job')->select(DB::raw("(date_part('hour',  (max(starttime) - min(starttime))) + (date_part('minutes',  (max(starttime) - min(starttime))) / 60.0)) AS hoursdiff"))
                    ->where('starttime','>=', $dataInicio )
                    ->where('endtime','<=', $dataFim)
                    ->get();

        $hoursbytes  = DB::table('job')->select(DB::raw("(sum(jobbytes)/(date_part('hour',  (max(starttime) - min(starttime))) + (date_part('minutes',  (max(starttime) - min(starttime))) / 60.0))) AS hoursbytes"))
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
            'data' => date('Y-m-d') ,
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
                'hoursdiff' => (int)$hoursdiff[0]->hoursdiff,
                'hourbytes' => $hoursbytes[0]->hoursbytes
        );

        $hourstats = Hoursstats::firstOrCreate($hourstats);
        $daystats = Daystats::firstOrCreate($daystats);
	}
}
