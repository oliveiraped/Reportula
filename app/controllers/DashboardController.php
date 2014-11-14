<?php

namespace app\controllers;
use BaseController, Form, Input, Redirect;
use Sentry, View, Log, Cache, Config, DB;
use Date, App, Former, Datatables, Asset, Schema ;

// Models
use app\models\Client;
use app\models\Media;
use app\models\Job;
use app\models\Pool;
use app\models\Files;

class DashboardController extends BaseController
{
    public function __construct()
    {

        parent::__construct();
        Asset::add('amcharts', 'assets/js/amcharts.js');
        Asset::add('amchartsPie', 'assets/js/pie.js');
        Asset::add('bootstrap-tooltip.js', 'assets/js/bootstrap-tooltip.js');
        Asset::add('bootstrap-dropdown.js', 'assets/js/bootstrap-dropdown.js');

        /* Html Exports Tables */
        Asset::add('tableExport.js', 'assets/js/tableExport.js');
        Asset::add('jquery.base64.js', 'assets/js/jquery.base64.js');
        Asset::add('html2canvas.js', 'assets/js/html2canvas.js');
        Asset::add('sprintf.js', 'assets/js/sprintf.js');
        Asset::add('jspdf.js', 'assets/js/jspdf.js');
        Asset::add('base64.js', 'assets/js/base64.js');

        Asset::container('footer')->add('dashboard', 'assets/js/dashboard.js');

        // Caching DbSize Value
        $dbsize = Cache::rememberForever('dbsize', function () {
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
            return $dbsize[0]->dbsize;
        },15);

        // Caching Number of Clients
        $clients = Cache::rememberForever('nclients', function () {
            $clients = Client::get();
                        //where_in('name', $this->group_permissions_clients)
            return count($clients);
        },15);

        // Caching Number of Files And Bytes
        $media = Media::get(array(DB::raw('sum(volbytes) as bytes'), DB::raw('sum(volfiles) as files')))->first();

        Cache::forever('nFiles',  $media->files);
        Cache::forever('nBytes',  $media->bytes);

    }

    public function dashboard($data=null)
    {
        /* Possbilidade de utilizar as datas*/
        $datetype=$data;

        if ($data == null || $data == 'day') {
            $datetype='day' ;
            $date=Date::now()->sub('1 day');

            //$date = Date::forge('last day')->format('datetime');
            $nameDate = 'Last 24 Hours';
        } elseif ($data == 'week') {

            $date = Date::now()->sub('7 day');
            $nameDate =  'Last Week';
        } elseif ($data == 'month') {
            $date = Date::now()->sub('1 month');
            $nameDate =  'Last Month';
        }



        /* Get Terminated Jobs */
        $tjobs = Job::where('starttime','>=', $date )
                ->where('jobstatus','=', 'T');
               /* if ($this->group_permissions_jobs[0]<>'') {
                    $tjobs->where_in('name', $this->group_permissions_jobs);
                }*/
                $tjobs =  $tjobs->get();

        // Number Terminate Jobs
        $terminatedJobs=count($tjobs);

        /* Get Canceled Jobs */
        $canceledJobs = Job::where('starttime','>=',$date)
                ->where('jobstatus','=', 'A');
                /*if ($this->group_permissions_jobs[0]<>'') {
                    $canceledJobs->where_in('name', $this->group_permissions_jobs);
                }*/
               $canceledJobs = $canceledJobs->get();

        // Number Terminate Jobs
        $cancelJobs=count($canceledJobs);

        /* Get Running Jobs */
        $runJobs = Job::where('starttime','>=',$date)
                ->where('jobstatus','=', 'R');
               /* if ($this->group_permissions_jobs[0]<>'') {
                    $runJobs->where_in('name', $this->group_permissions_jobs);
                }*/
                $runJobs =  $runJobs->get();

        // Number Running Jobs
        $runningJobs=count($runJobs);

         /* Get Watting Jobs */
        $wateJobs = Job::where('starttime','>=',$date)
                ->wherein('jobstatus', array('c', 'F', 'j','M','m','p','s','t'));
               /* if ($this->group_permissions_jobs[0]<>'') {
                    $wateJobs->where_in('name', $this->group_permissions_jobs);
                }*/
                $wateJobs =  $wateJobs->get();

        // Number Watting Jobs
        $wattingJobs=count($wateJobs);

          /* Get Error Jobs */
        $errJobs = Job::where('starttime','>=',$date)
                ->wherein('jobstatus', array('e', 'f', 'E'));
                /*if ($this->group_permissions_jobs[0]<>'') {
                     $errJobs->where_in('name', $this->group_permissions_jobs);
                }*/
                 $errJobs =   $errJobs->get();

        // Number Error Jobs
        $errorJobs=count($errJobs);

        $terminatedJobs=count($tjobs);

        // Indicates Failed and Okay Jobs
        $nTransBytes=0;
        $nTransFiles=0;

       /* Calculate Jobs and Bytes */
        $tjobs=$tjobs->toArray();

        if ( Config::get('database.default')=='pgsql' ) {
            $nTransFiles = array_sum( array_fetch ($tjobs, 'jobfiles')) ;
            $nTransBytes = array_sum( array_fetch ($tjobs, 'jobbytes') );
        } else {
            $nTransFiles = array_sum( array_fetch ($tjobs, 'JobFiles')) ;
            $nTransBytes = array_sum( array_fetch ($tjobs, 'JobBytes') );
        }

        $pools = json_encode((array) DB::table($this->tables['pool'])->select('name','numvols')->orderby('numvols', 'desc')->get());

        $user = Sentry::getUser();
        /* Gets Pools And Convert Objets to Arrays*/

        return View::make('dashboard',array(
                                    'username'      => $user->email,
                                    'terminatedJobs'=> $terminatedJobs,
                                    'nTransFiles'   => preg_replace("/(?<=\d)(?=(\d{3})+(?!\d))/",",",$nTransFiles),
                                    'cancelJobs'    => $cancelJobs,
                                    'runningJobs'   => $runningJobs,
                                    'wattingJobs'   => $wattingJobs,
                                    'errorJobs'     => $errorJobs,
                                    'nTransBytes'   => $this->byte_format($nTransBytes),
                                    'nameDate'      => $nameDate, // Date Name
                                    'datetype'      => $datetype,
                                    'type'          => 'terminated',
                                    'graphOkJob'    => ($terminatedJobs <> 0) ?  ($terminatedJobs/($terminatedJobs+$errorJobs))*100 : 0 ,
                                    'graphFailedJob' => ($errorJobs<> 0) ? ($errorJobs/($errorJobs+$terminatedJobs))*100 : 0,
                                    'dbsize'        =>$this->byte_format(Cache::get('dbsize')),
                                    'nClients'      =>Cache::get('nclients'),
                                    'nFiles'        =>Cache::get('nFiles'),
                                    'nBytes'        =>$this->byte_format(Cache::get('nBytes')),
                                    'pools'         =>$pools
                                )
                         );
    }

    // Ajax Dashboard Jobs Table
    public function getjobs($data=null)
    {
       $date = new Date('last '.Input::get('date'));
       $tjobs = Job::select(array('jobid','name','starttime','endtime',
                                  'level','jobbytes','jobfiles','jobstatus'))
                    ->where('starttime','>=', (string) $date );

        switch (Input::get('type')) {
            case "terminated":
                $tjobs->where('jobstatus','=', 'T');
                break;
            case "running":
                $tjobs->where('jobstatus','=', 'R');
                break;
            case "watting":
                $tjobs->wherein('jobstatus', array('c', 'F', 'j','M','m','p','s','t'));
                break;
            case "error":
                $tjobs->wherein('jobstatus', array('e', 'f', 'E'));
                break;
            case "cancel":
                $tjobs->where('jobstatus','=', 'A');
                break;
        }

        // Group Permissions
       /* if ($this->group_permissions_jobs[0]<>'') {
             $tjobs->where_in('name', $this->group_permissions_jobs);
        }*/

       return Datatables::of($tjobs)
                ->edit_column('name', '{{ link_to_route("jobs", $name ,array("Job" => $jobid)) }} ')
                ->edit_column('jobid', '{{ link_to_route("files", $jobid ,array("Files" => $jobid)) }} ')
                ->make();

    }

    // Ajax Volumes & Pools Table
    public function getvolumes($data=null)
    {
          $date = new Date('last '. Input::get('date'));


                $volumes = Media::join($this->tables['pool'], $this->tables['media'].'.poolid', '=', $this->tables['pool'].'.poolid')
                            ->where('lastwritten','>=', (string)  $date )
                            ->select(array('volumename','slot','volbytes','mediatype',$this->tables['pool'].'.name','lastwritten','volstatus'));

           return (Datatables::of($volumes)
                           // ->edit_column('pool','{{ HTML::link_to_action("pool@index", $name, array("Job" => $name)) }}')
                           // ->edit_column('volumename','{{ HTML::link_to_action("volumes@index", $volumename, array("Volumes" => $volumename)) }}')
                            ->make());
    }

    // Ajax Graph Volumes Pool
    public function getgraph()
    {
      $pools = Cache::remember('poolsgraph', function () {
            return DB::table($this->tables['pool'])->order_by('numvols', 'desc')->get(array('name','numvols'));
        },15);
      echo json_encode($pools);
    }

}
