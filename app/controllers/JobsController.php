<?php

namespace app\controllers;
use BaseController, Form, Input, Redirect;
use Sentry, View, Log, Cache, Config, DB;
use Date, App, Former, Datatables, Asset;

// Models
use app\models\Client;
use app\models\Media;
use app\models\Job;
use app\models\Cfgjob;
use app\models\Userspermissions;

class JobsController extends BaseController
{
    public $jobSelectBox = array();

    public function __construct()
    {
        parent::__construct();
        Asset::add('select2', 'assets/css/select2.css');
        Asset::add('daterangepicker', 'assets/css/daterangepicker.css');

        Asset::add('amcharts', 'assets/js/amcharts.js');
        Asset::add('amchartsSerial', 'assets/js/serial.js');

        Asset::container('footer')->add('select2min', 'assets/js/select2.min.js');

        Asset::container('footer')->add('momentmin', 'assets/js/moment.min.js');
        Asset::container('footer')->add('daterangepicker', 'assets/js/daterangepicker.js');

    }

    public function jobs($job=null)
    {
         Asset::container('footer')->add('amcharts', 'assets/js/amcharts.js');
         Asset::container('footer')->add('jobs', 'assets/js/jobs.js');

        $start = Input::get('start', Date::forge('yesterday')->format('date'));
        $end   = Input::get('end',   Date::forge('today')->format('date'));

        $jobselected = Input::get('Job', $job);

        if (empty($jobselected)) {
            $jobselected=0;
        } else {
            $jobselected = Job::select('name')
                ->where('jobid','=', $jobselected )->get();

            $jobselected = $jobselected->first()->name;
            /* Array Search for Get Id and set the select box when passing url id of job*/
            $job= array_search ($jobselected, Job::jobSelectBox());

        }

        $user=Sentry::getUser();

        // Get Clients to fill the Client Select Box And Select Values From Permissions
        $permissions = Userspermissions::where('id', '=', $user->id)->first();
        if ($permissions<>null) {
            $permissions=unserialize($permissions->jobs);
            $jobs = Job::wherein('jobid', $permissions)->get();
            $jobsSelectBox=Job::jobSelectBox($jobs->toArray());
        } else {
            $jobsSelectBox=Job::jobSelectBox();
        }
        ///// End Permissions

        /* Get Included Files & Excluded Files Configuration */

          $cfgjob =  ""; //cfgJob::wherename($jobselected)->first();

          if ($cfgjob<>"") {
              $cfgfileset  = cfgFileset::wherename($cfgjob->fileset)->first();
              $fileinclude = cfgFileset::find($cfgfileset->id)->filesinclude()->get();

              $fileexclude = cfgFileset::find($cfgfileset->id)->filesexclude()->get();
          } else {
              $fileinclude = "";
              $fileexclude = "";
          }

        ///////////////////////////

        /* Get Terminated Jobs */
        $tjobs = Job::where('jobstatus','=', 'T')
                  ->where('starttime','>=',  $start)
                  ->where('endtime','<=',$end)
                  ->where('name','=', $jobselected)
                  ->get();

        //dd ($tjobs);
        // Number Terminate Jobs
        $terminatedJobs=count($tjobs);

        /* Get Canceled Jobs */
        $canceledJobs = Job::where('jobstatus','=', 'A')
                ->where('starttime','>=',$start)
                ->where('endtime','<=',$end)
                 ->where('name','=',$jobselected)
                ->get();

        // Number Terminate Jobs
        $cancelJobs=count($canceledJobs);

        /* Get Running Jobs */
        $runJobs = Job::where('jobstatus','=', 'R')
                ->where('starttime','>=',$start)
                ->where('endtime','<=',$end)
                ->where('name','=',$jobselected)
                ->get();

        // Number Running Jobs
        $runningJobs=count($runJobs);

         /* Get Watting Jobs */
        $wateJobs = Job::wherein('jobstatus', array('c', 'F', 'j','M','m','p','s','t'))
                ->where('endtime','<=',$end)
                ->where('starttime','>=',$start)
                ->where('name','=',$jobselected)
                ->get();

        // Number Watting Jobs
        $wattingJobs=count($wateJobs);

          /* Get Error Jobs */
        $errJobs = Job::wherein('jobstatus', array('e', 'f', 'E'))
                ->where('starttime','>=',$start)
                ->where('endtime','<=',$end)
                ->where('name','=',$jobselected)
                ->get();

        // Number Error Jobs
        $errorJobs=count($errJobs);

        // Indicates Failed and Okay Jobs
        $nTransBytes=0;
        $nTransFiles=0;

        $terminatedJobs=count($tjobs);

        /* Calculate Jobs and Bytes */
        $tjobs=$tjobs->toArray();
        $nTransFiles = array_sum( array_fetch ($tjobs, 'jobfiles')) ;
        $nTransBytes = array_sum( array_fetch ($tjobs, 'jobbytes') );

        /* Draw GRaphs */

        $graphBytes = DB::table('job')->where('name','=', $jobselected )
                  ->where('starttime','>=',  $start)
                  ->where('endtime','<=',    $end )
                  ->orderby('starttime', 'asc')
                  ->get(array(DB::raw('date(job.starttime) as date'), DB::raw('jobbytes as bytes')));
        $graphBytes= json_encode((array) $graphBytes);

        $graphFiles = DB::table('job')->where('name','=', $jobselected)
                  ->where('starttime','>=', $start )
                  ->where('endtime','<=',   $end  )
                  ->orderby('starttime', 'asc')
                  ->get(array(DB::raw('date(job.starttime) as date'), DB::raw('jobfiles as files')));
        $graphFiles = json_encode((array) $graphFiles);

       // var_dump($graphFiles);
        Former::populate( array('date' => $start .' - '.$end));
                          //array('Job' => $jobselected) );
        return View::make('jobs',array(
                                'terminatedJobs' => $terminatedJobs,
                                'nTransFiles'    => preg_replace("/(?<=\d)(?=(\d{3})+(?!\d))/",",",$nTransFiles),
                                'cancelJobs'     => $cancelJobs,
                                'runningJobs'    => $runningJobs,
                                'wattingJobs'    => $wattingJobs,
                                'errorJobs'      => $errorJobs,
                                'nTransBytes'    => $this->byte_format($nTransBytes),
                                'start'          => $start,
                                'end'            => $end,
                                'job'            => $job,
                                'type'           => 'terminated',
                                'graphOkJob'     => ($terminatedJobs <> 0) ?  ($terminatedJobs/($terminatedJobs+$errorJobs))*100 : 0 ,
                                'graphFailedJob' => ($errorJobs<> 0) ? ($errorJobs/($errorJobs+$terminatedJobs))*100 : 0,
                                'filesinclude'   => $fileinclude,
                                'filesexclude'   => $fileexclude,
                                'jobSelectBox'   => $jobsSelectBox,
                                'graphBytes'     => $graphBytes,
                                'graphFiles'     => $graphFiles

                            )
                         );

    }

    /*Gets Data from the Jobs */

    public function getjobs()
    {

        $start = Input::get('start', Date::forge('yesterday')->format('date'));
        $end   = Input::get('end',   Date::forge('today')->format('date'));

        $jobselected = Job::select('name')
                ->where('jobid','=', Input::get('Job') )->get()->first()->name;

        $tjobs = Job::select(array('media.mediaid','job.jobid','starttime','endtime',
                                   'volumename','level','jobbytes','jobfiles','jobstatus'))
                  ->join('jobmedia','jobmedia.jobid', '=', 'job.jobid')
                  ->join('media','media.mediaid', '=', 'jobmedia.mediaid')
                  ->where('name','=',  $jobselected)
                  ->where('starttime','>=',  $start )
                  ->where('endtime','<=', $end )
                  ->groupby('job.jobid')
                  ->groupby('job.name')
                  ->groupby('job.starttime')
                  ->groupby('job.endtime')
                  ->groupby('media.volumename')
                  ->groupby('media.mediaid')
                  ->groupby('job.level')
                  ->groupby('job.jobbytes')
                  ->groupby('job.jobfiles')
                  ->groupby('job.jobstatus');

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

        return Datatables::of($tjobs)
                    ->edit_column('volumename', '{{ link_to_route("volumes", $volumename ,array("Volume" => $mediaid)) }} ')
                    ->edit_column('jobid', '{{ link_to_route("files", $jobid ,array("Files" => $jobid)) }} ')
                    ->remove_column('mediaid')

                  ->make();

    }

}
