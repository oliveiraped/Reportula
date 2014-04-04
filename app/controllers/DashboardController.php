<?php

namespace app\controllers;
use BaseController, Form, Input, Redirect;
use Sentry, View, Log, Cache, Config, DB;
use Date, App, Former, Datatables, Asset, Vd\Vd, Schema ;

// Models
use app\models\Client;
use app\models\Media;
use app\models\Job;
use app\models\Pool;
use app\models\Files;

//use \App\lib\vd;

class DashboardController extends BaseController
{
    public function __construct()
    {

        parent::__construct();
       // Asset::add('tabletools', 'assets/js/TableTools.min.js');
        Asset::add('amcharts', 'assets/js/amcharts.js');
        Asset::add('amchartsPie', 'assets/js/pie.js');

        Asset::add('bootstrap-tooltip.js', 'assets/js/bootstrap-tooltip.js');
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

public function makeList($array, $depth=0, $key_map=FALSE)
{
    $whitespace = str_repeat("\t", $depth*2);
    //Base case: an empty array produces no list
    if (empty($array)) return '';
    //Recursive Step: make a list with child lists
    $output = "$whitespace<ul>\n";
    foreach ($array as $key => $subArray) {
        $subList = $this->makeList($subArray, $depth+1, $key_map);
        if($key_map AND $key_map[$key]) $key = $key_map[$key];
        if($subList) $output .= "$whitespace\t<li>" . $key . "\n" . $subList . "$whitespace\t</li>\n";
        else $output .= "$whitespace\t<li>" . $key . $subList . "</li>\n";
    }
    $output .= "$whitespace</ul>\n";

    return $output;
}

    public function recursion($multi_dimensional_array)
    {
        $m = $multi_dimensional_array;

        $keys = array();
        foreach ($m as $key=>$value) {
            $keys[] = $key;
        }

        $i = 0;
        $ul='';
        while ($i < count($multi_dimensional_array)) {
            $ul.= '<li><a href="#">'.$keys[$i].'</a>';
            if (is_array($multi_dimensional_array[$keys[$i]])) {
                $ul.= '<ul>';
                $ul.= $this->recursion($multi_dimensional_array[$keys[$i]]);
                $ul.= '</ul>';
            }
            $ul.= '</li>';
            $i++;
        }

        return $ul;
    }

    public function test()
    {
        $vd = new Vd;
        $files= Files::select(array('path.path','filename.name'))
                  ->join('filename','file.filenameid', '=', 'filename.filenameid')
                  ->join('path','file.pathid', '=', 'path.pathid')
                  ->where('jobid','=', '172202')
                // 172202  1990
                  ->orderBy('path.path','asc');

        $files=$files->get();//->toArray();

        //$vd->dump($files->toArray());

        foreach ($files as $file) {
            $ficheiro[$file->path.$file->name]='';
        }

       $tree = $this->explodeTree($ficheiro, "/");

   // $vd->dump($tree);

    $menu = $this->recursion($tree);

        //$menu= $this->MakeMenu($tree);

       //$vd->dump($menu);

        //exit();

       // $this->plotTree($tree);
       // $key_files = array_combine(array_values($files), array_values($files));

        //$vd->dump($tree);

/*
        $vd->dump($result);*/
       // exit();
        return View::make('teste', array( 'menu' => $menu));

    }

    public function dashboard($data=null)
    {

        /* Possbilidade de utilizar as datas*/
        $datetype=$data;

        if ($data == null || $data == 'day') {
            $datetype='day' ;
            $date = Date::forge('last day')->format('datetime');
            $nameDate = 'Last 24 Hours';
        } elseif ($data == 'week') {
            $date = Date::forge('last week')->format('datetime');
            $nameDate =  'Last Week';
        } elseif ($data == 'month') {
            $date = Date::forge('last month')->format('datetime');
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
        $nTransFiles = array_sum( array_fetch ($tjobs, 'jobfiles')) ;
        $nTransBytes = array_sum( array_fetch ($tjobs, 'jobbytes') );
        $user = Sentry::getUser();

        /* Gets Pools And Convert Objets to Arrays*/
        $pools = json_encode((array) DB::table('pool')->select('name','numvols')->orderby('numvols', 'desc')->get());

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

       $tjobs = Job::select(array('jobid','name','starttime','endtime',
                                  'level','jobbytes','jobfiles','jobstatus'))

                    ->where('starttime','>=', (string) Date::forge('last '.Input::get('date'))->format('datetime')  );

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

          $volumes = Media::join('pool','media.poolid', '=', 'pool.poolid')
                            ->where('lastwritten','>=', (string) Date::forge('last '.Input::get('date'))->format('datetime') )
                            ->select(array('volumename','slot','volbytes','mediatype','pool.name','lastwritten','volstatus'));

           return (Datatables::of($volumes)
                           // ->edit_column('pool','{{ HTML::link_to_action("pool@index", $name, array("Job" => $name)) }}')
                           // ->edit_column('volumename','{{ HTML::link_to_action("volumes@index", $volumename, array("Volumes" => $volumename)) }}')
                            ->make());
    }

    // Ajax Graph Volumes Pool
    public function getgraph()
    {
      $pools = Cache::remember('poolsgraph', function () {
            return DB::table('pool')->order_by('numvols', 'desc')->get(array('name','numvols'));
        },15);
      echo json_encode($pools);
    }

}
