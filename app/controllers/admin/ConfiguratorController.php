<?php namespace app\controllers\admin;

use BaseController;
use Datatables;
use View;
use Sentry;
use URL;
use Input;
use Validator;
use Response;
use Former;
use Log;
use Asset;
use Vd\Vd;
use File;
use Request;
use Debugbar;
use Cache;
use DB;
use Filesystem;
use Schema;

// Models
use app\models\Settings;
use app\models\CfgDirector;
use app\models\CfgSchedule;
use app\models\CfgSchedulerun;
use app\models\CfgStorage;
use app\models\CfgCatalog;
use app\models\CfgClient;
use app\models\CfgFileset;
use app\models\Cfgfilesetinclude;
use app\models\Cfgfilesetexclude;
use app\models\Cfgfilesetincludeoptions;
use app\models\Cfgfilesetexcludeoptions;
use app\models\CfgPool;
use app\models\CfgJob;
use app\models\CfgMessage;
use app\models\CfgConsole;

class ConfiguratorController extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        Asset::add('multi-select', 'assets/css/multi-select.css');
        Asset::add('fancytreecss', 'assets/css/fancytree.css');
        Asset::add('jquery-ui-bootstrap.css', 'assets/css/jquery-ui-bootstrap.css');
        Asset::add('bootstrap-wizard.css', 'assets/css/bootstrap-wizard.css');
        Asset::add('jquery-ui.min.js', 'assets/js/jquery-ui.min.js', 'jquery');
        Asset::add('jquerymultiselect', 'assets/js/jquery.multi-select.js', 'jquery');
        Asset::add('eldarionform', 'assets/js/eldarion-ajax.min.js', 'jquery');
        Asset::add('bootbox.js', 'assets/js/bootbox.min.js', 'jquery');
        Asset::add('tab', 'assets/js/bootstrap-tab.js', 'jquery2');
        Asset::add('jquery.cookie.js', 'assets/js/jquery.cookie.js', 'jquery2');
        Asset::add('fancytree', 'assets/js/jquery.fancytree-all.min.js', 'jquery');
        Asset::add('jquery.fancytree.persist.js', 'assets/js/jquery.fancytree.persist.js', 'fancytree');
        Asset::add('fancytreefilter', 'assets/js/jquery.fancytree.filter.js', 'fancytree');
        Asset::add('jquery.validate.js', 'assets/js/jquery.validate.js');
        Asset::add('jquery.dataTables.editable.js', 'assets/js/jquery.dataTables.editable.js', 'jquery');
        Asset::add('bootstrap-dropdown.js', 'assets/js/bootstrap-dropdown.js');
        Asset::add('select2', 'assets/css/select2.css');
        Asset::add('select2min', 'assets/js/select2.min.js');
        Asset::add('bootstrap-wizard.min.js', 'assets/js/bootstrap-wizard.min.js');
        Asset::add('configurator.js', 'assets/js/configurator.js', 'jquery');
    }

    /**
     * Display Configurator page
     * @return View
     */
    public function configurator()
    {
       /*  $vd= new VD;
        $vd->dump(Settings::find(1));*/
        return View::make('admin.configurator');
    }

    /**
     * Get Selected Node Data
     * @return Json
     */
    public function getnode()
    {
        $node = Input::get('node','');
        $parent = substr(Input::get('parent',''),0,-1);
        $classname  = "app\models\Cfg".$parent;
        if ($parent!="Fileset") {
          $viewvalues = $classname::orderBy('Name')->where('Name',$node)->first()->toArray();
        } else {
           $viewvalues = $classname::with('Cfgfilesetinclude')
                                    ->with('Cfgfilesetexclude')
                                    ->with('Cfgfilesetincludeoptions')
                                    ->with('Cfgfilesetexcludeoptions')
                                    ->orderBy('Name')->where('Name',$node)
                                    ->first()->toArray();
        }

        $viewvalues['config'] = $parent.'s';
        Former::populate( $viewvalues );
        $view = 'admin.configurator.'.lcfirst($parent);
        return View::make($view, $viewvalues)->render();
    }

     /**
     * Get Tree Data
     * @return Json
     */
    public function gettreedata()
    {
        $tree=array();
        $key=2;
        $models=array("Director", "Storage",
                      "Client","Job","Fileset",
                      "Schedule","Pool","Catalog",
                      "Console","Message"
                      );
        $lastkey=1;
        foreach ($models as $model)
        {
          $classname="app\models\Cfg".$model;
          $values=$classname::orderBy('Name')->get();
          foreach ($values as $value)
          {
             $valuearray[]=array('key'=> $key++, 'title' => $value->Name, 'parent' => $value->id);
          }
          if (!isset($valuearray)) { $valuearray =""; }

          $tree[]=array('key'         => $lastkey,
                        'title'       => $model."s",
                        'folder'      => 'true',
                        'children'    => $valuearray
                      );
            $valuearray="";
            $lastkey=$key;
        }

        return Response::json ($tree);
    }

    /*****
     * Restart Bacula Daemon
     * @return Json
     */
    public function restartbacula()
    {
      $output = shell_exec('sudo echo reload | bconsole ');

      //$message = array('html' => '<div class="alert alert-danger">'.$output.'</div>');
      //if ($output=="") {
          $message = array('html' => '<div class="alert alert-info"> '.$output.' </div>');
       // }
        return Response::json($message);
    }


    /*****
     * Write Configuration Files
     * @return Json
     */
    public function writebacula()
    {
      $settings = Settings::find(1);

      $directory = $settings->confdir;
      $dirname="bacula-dir.conf";

      if ((Input::get('type')=="test")) {
        $directory=$directory.'/reportulateste';
        $dirname='/bacula-dir.test';

      }

      $success = File::cleanDirectory($directory.'/conf.d');

      /* Check If Bacula Configuration Folder Exists if Not Create */
      File::makeDirectory($directory.'/conf.d','','',true);
      File::makeDirectory($directory.'/conf.d/clients', 0777, true );
      File::makeDirectory($directory.'/conf.d/filesets', 0777, true );
      File::makeDirectory($directory.'/conf.d/jobs', 0777, true );

      $contents ="Director {\n";

      $model = Cfgdirector::find(1);
      $model = $model->toArray();

      foreach( $model as $key => $value){
        if ( $value!="" && $key!='id') {

          if ($key=='MaximumConcurrentJobs') { $key = "Maximum Concurrent Jobs"; }
          if ($key=='HeartbeatInterval')     { $key = "Heartbeat Interval"; }
          if ($key=='PidDirectory') { $key = "Pid Directory"; }
          if ($key=='ScriptsDirectory')     { $key = "Scripts Directory"; }
          if ($key=='FDConnectTimeout') { $key = "FD Connect Timeout"; }
          if ($key=='SDConnectTimeout')     { $key = "SD Connect Timeout"; }
          if ($key=='StatisticsRetention')     { $key = "Statistics Retention"; }

          $contents .= "\t". $key .' = '.$value ."\n";
        }
      }

      $contents .= "}\n
      \n
      ###################### CATALOGS DEFINITION FILES ###############################################\n
      @".$directory."/conf.d/catalog.conf\n

      ###################### MESSAGES DEFINITION FILES ###############################################\n
      @".$directory."/conf.d/messages.conf\n

      ###################### CONSOLE DEFINITION FILES ###############################################\n
      @".$directory."/conf.d/console.conf\n

      ###################### SCHEDULES Definition Files ##############################################\n
      @".$directory."/conf.d/schedule.conf\n
      \n
      ###################### STORAGE DEFINITION FILES ###############################################\n
      @".$directory."/conf.d/storage.conf\n
      \n
      ###################### POOLS DEFINITION FILES ###############################################\n
      @".$directory."/conf.d/pools.conf\n
      \n

      ###################### CLIENTS DEFAULTS DEFINITION FILES ###############################################\n
      @|\"sh -c 'for f in ".$directory."/conf.d/clients/*.conf ; do echo @\${f} ; done'\"

      ###################### FILESETS DEFAULTS DEFINITION FILES ###############################################\n
      @|\"sh -c 'for f in ".$directory."/conf.d/filesets/*.conf ; do echo @\${f} ; done'\"

      ###################### JOBS DEFAULTS DEFINITION FILES ###############################################\n
      @|\"sh -c 'for f in ".$directory."/conf.d/jobs/*.conf ; do echo @\${f} ; done'\"

      \n
      ";

      File::put( $directory.'/'.$dirname , $contents);
      ################## Pools ######################

      $model = CfgPool::get();
      $model = $model->toArray();
      $contents="";
      foreach ($model as $v1) {
        $contents.="Pool {\n";
        foreach ($v1 as $key => $value) {
          if ( $value!="" && $key!='id') {

            if ($key=='PoolType') { $key = "Pool Type"; }
            if ($key=='VolumeRetention') { $key = "Volume Retention"; }
            if ($key=='MaximumVolumeJobs') { $key = "Maximum Volume Jobs"; }
            if ($key=='LabelFormat') { $key = "Label Format"; }
            if ($key=='MaximumVolumes') { $key = "Maximum Volumes"; }

            if ($key=='UseVolumeOnce') { $key = "Use Volume Once"; }
            if ($key=='MaximumVolumeFiles') { $key = "Maximum Volume Files"; }
            if ($key=='MaximumVolumeBytes') { $key = "Maximum Volume Bytes"; }
            if ($key=='VolumeUseDuration') { $key = "Volume Use Duration"; }
            if ($key=='CatalogFiles') { $key = "Catalog Files"; }
            if ($key=='ActionOnPurge') { $key = "Action On Purge"; }

            if ($key=='RecycleOldestVolume') { $key = "Recycle Oldest Volume"; }
            if ($key=='RecycleCurrentVolume') { $key = "Recycle Current Volume"; }
            if ($key=='PurgeOldestVolume') { $key = "Purge Oldest Volume"; }
            //if ($key=='ActionOnPurge') { $key = "ActionOnPurge"; }

            if ($key=='FileRetention') { $key = "File Retention"; }
            if ($key=='JobRetention') { $key = "Job Retention"; }
            if ($key=='CleaningPrefix') { $key = "Cleaning Prefix"; }
            if ($key=='LabelFormat') { $key = "Label Format"; }

            $contents .= "\t". $key .' = '.$value ."\n";
          }
        }
        $contents .= "}\n\n";
      }
      File::put($directory.'/conf.d/pools.conf', $contents);

      ######################################################

      ################## Sechedules ######################

      $model = CfgSchedule::get();
      $model = $model->toArray();
      $contents="";
      foreach ($model as $v1) {
        $idschedule = $v1['id'];
        $contents.="Schedule {\n";
        foreach ($v1 as $key => $value) {
          if ( $value!="" && $key!='id') {
            $contents .= "\t". $key .' = '.$value ."\n";
            $run = cfgSchedulerun::where('idschedule','=',$idschedule)->get();
            $run = $run->toArray();
            if (count( $run) !=0 ) {
              foreach ($run as $v2) {
                  $contents .= "\tRun = ".$v2['Run']."\n";
              }
            }
          }
        }
        $contents .= "}\n\n";
      }
      File::put($directory.'/conf.d/schedule.conf', $contents);

      ######################################################


      ################## Storage ######################

      $model = CfgStorage::get();
      $model = $model->toArray();
      $contents="";
      foreach ($model as $v1) {
        $contents.="Storage {\n";
        foreach ($v1 as $key => $value) {
          if ( $value!="" && $key!='id') {

            if ($key=='SDPort') { $key = "SD Port"; }
            if ($key=='MediaType') { $key = "Media Type"; }
            if ($key=='MaximumConcurrentJobs') { $key = "Maximum Concurrent Jobs"; }
            if ($key=='HeartbeatInterval') { $key = "Heartbeat Interval"; }

            $contents .= "\t". $key .' = '.$value ."\n";
          }
        }
        $contents .= "}\n\n";
      }
      File::put($directory.'/conf.d/storage.conf', $contents);

      ######################################################
      ################## Catalogs ######################

      $model = CfgCatalog::get();

      $model = $model->toArray();
      $contents="";
      foreach ($model as $v1) {
        $contents.="Catalog {\n";
        foreach ($v1 as $key => $value) {
          if ( $value!="" && $key!='id') {

            if ($key=='DBName') { $key = "DB Name"; }
            if ($key=='DBSocket') { $key = "DB Socket"; }
            if ($key=='DBAddress') { $key = "DB Address"; }
            if ($key=='DBPort') { $key = "DB Port"; }
            if ($key=='DBUser') { $key = "DB User"; }


            $contents .= "\t". $key .' = '.$value ."\n";
          }
        }
        $contents .= "}\n\n";
      }
      File::put($directory.'/conf.d/catalog.conf', $contents);

      ######################################################

      ################## Console ######################

      $model = CfgConsole::get();

      $model = $model->toArray();
      $contents="";
      foreach ($model as $v1) {
        $contents.="Console {\n";
        foreach ($v1 as $key => $value) {
          if ( $value!="" && $key!='id') {
            $contents .= "\t". $key .' = '.$value ."\n";
          }
        }
        $contents .= "}\n\n";
      }
      File::put($directory.'/conf.d/console.conf', $contents);

      ######################################################

      ################## Messages ######################

      $model = CfgMessage::get();

      $model = $model->toArray();
      $contents="";
      foreach ($model as $v1) {
        $contents.="Messages {\n";
        foreach ($v1 as $key => $value) {
          if ( $value!="" && $key!='id') {
            $contents .= "\t". $key .' = '.$value ."\n";
          }
        }
        $contents .= "}\n\n";
      }
      File::put($directory.'/conf.d/messages.conf', $contents);

      ######################################################

      ################## Clients ######################

      $model = CfgClient::get();

      $model = $model->toArray();

      foreach ($model as $v1) {
        $contents="";
        $clientname = $v1['Name'];
        $contents.="Client {\n";
        foreach ($v1 as $key => $value) {
          if ( $value!="" && $key!='id') {

            if ($key=='PidDirectory') { $key = "Pid Directory"; }
            if ($key=='WorkingDirectory') { $key = "Working Directory"; }
            if ($key=='HeartbeatInterval') { $key = "Heartbeat Interval"; }
            if ($key=='MaximumConcurrentJobs') { $key = "Maximum Concurrent Jobs"; }
            if ($key=='MaximumNetworkBufferSize') { $key = "Maximum Network Buffer Size"; }
            if ($key=='MaximumBandwidthPerJob') { $key = "Maximum Bandwidth Per Job"; }
            if ($key=='PKIEncryption') { $key = "PKI Encryption"; }
            if ($key=='PKISignatures') { $key = "PKI Signatures"; }
            if ($key=='PKIKeypair') { $key = "PKI Keypair"; }
            if ($key=='PKIMasterKey') { $key = "PKI Master Key"; }

            $contents .= "\t". $key .' = '.$value ."\n";
          }
        }
        $contents .= "}\n\n";

	#tweak filename to remove spaces, they are a bit of a hassle later
	$clientfname = preg_replace("/\s+/","-",$clientname);
        File::put($directory.'/conf.d/clients/'.$clientfname.'.conf', $contents);
      }
      ######################################################

      ################## Jobs ######################

      $model = CfgJob::get();

      $model = $model->toArray();

      foreach ($model as $v1) {
        $contents="";
        $jobname = $v1['Name'];
        $contents.="Job {\n";
        /* check if is Default Job */
        if ($v1['JobDefs']==Null) { $contents=""; $contents.="JobDefs {\n"; }

        foreach ($v1 as $key => $value) {
          if ( $value!="" && $key!='id') {

            if ($key=='VerifyJob') { $key = "Verify Job"; }
            if ($key=='WriteBootstrap')     { $key = "Write Bootstrap"; }
            if ($key=='FullBackupPool') { $key = "Full Backup Pool"; }
            if ($key=='DifferentialBackupPool')     { $key = "Differential Backup Pool"; }
            if ($key=='IncrementalBackupPool') { $key = "Incremental Backup Pool"; }
            if ($key=='IncrementalMaxRunTime') { $key = "Incremental Max Run Time"; }
            if ($key=='DifferentialMaxWaitTime') { $key = "Differential Max Wait Time"; }
            if ($key=='MaxRunSchedTime') { $key = "Max Run Sched Time"; }
            if ($key=='MaxWaitTime') { $key = "Max Wait Time"; }
            if ($key=='MaxRunTime') { $key = "Max Run Time"; }
            if ($key=='MaximumBandwidth') { $key = "Maximum Bandwidth"; }
            if ($key=='MaxFullInterval ') { $key = "Max Full Interval "; }
            if ($key=='PreferMountedVolumes') { $key = "Prefer Mounted Volumes"; }
            if ($key=='PruneJobs') { $key = "Prune Jobs"; }
            if ($key=='PruneFiles') { $key = "Prune Files"; }
            if ($key=='PruneVolumes') { $key = "Prune Volumes"; }
            if ($key=='RunBeforeJob') { $key = "Run Before Job"; }
            if ($key=='RunAfterJob') { $key = "Run After Job"; }
            if ($key=='RunAfterFailedJob') { $key = "Run After Failed Job"; }
            if ($key=='ClientRunBeforeJob') { $key = "Client Run Before Job"; }
            if ($key=='RerunFailedLevels') { $key = "Rerun Failed Levels"; }
            if ($key=='SpoolData') { $key = "Spool Data"; }
            if ($key=='SpoolAttributes') { $key = "Spool Attributes"; }
            if ($key=='AddPrefix') { $key = "Add Prefix"; }
            if ($key=='AddSuffix') { $key = "Add Suffix "; }
            if ($key=='StripPrefix') { $key = "Strip Prefix"; }
            if ($key=='PrefixLinks') { $key = "Prefix Links"; }
            if ($key=='MaximumConcurrentJobs') { $key = "Maximum Concurrent Jobs"; }
            if ($key=='RescheduleOnError') { $key = "Reschedule On Error"; }
            if ($key=='RescheduleInterval') { $key = "Reschedule Interval"; }
            if ($key=='RescheduleTimes') { $key = "Reschedule Times"; }
            if ($key=='AllowDuplicateJobs') { $key = "Allow Duplicate Jobs"; }
            if ($key=='CancelLowerLevelDuplicates') { $key = "Cancel Lower Level Duplicates"; }
            if ($key=='CancelQueuedDuplicates')   { $key = "Cancel Queued Duplicates"; }
            if ($key=='CancelRunningDuplicates') { $key = "Cancel Running Duplicates"; }
            if ($key=='AllowMixedPriority') { $key = "Allow Mixed Priority"; }
            if ($key=='WritePartAfterJob') { $key = "Write Part After Job"; }

            $contents .= "\t". $key .' = '.$value ."\n";
          }
        }
        $contents .= "}\n\n";


	#tweak filename to remove spaces, they are a bit of a hassle later
	$jobfname = preg_replace("/\s+/","-",$jobname);
        File::put($directory.'/conf.d/jobs/'.$jobfname.'.conf', $contents);
      }
      ######################################################*/

      ################## FileSets ######################

      $model = CfgFileset::get();
      $model = $model->toArray();

      foreach ($model as $v1) {
        $contents="";
        $fileset = $v1['id'];
        $filesetname = $v1['Name'];
        $contents.="FileSet {\n";
        foreach ($v1 as $key => $value) {
          if ( $value!="" && $key!='id') {
            if ($key=='IgnoreFileSetChanges') { $key = "Ignore FileSet Changes"; }
            if ($key=='EnableVSS') { $key = "Enable VSS"; }
            $contents .= "\t". $key .' = '.$value ."\n";
          }
        }
        // Includes
        $inc = cfgFileSetInclude::where('idfileset','=',$fileset)->get();
        $inc = $inc->toArray();
        $contents.="\tInclude {\n";
        // Includes Options
        $incfileopt  = cfgFileSetIncludeoptions::where('idfileset','=',$fileset)->get();
        $incfileopt  =  $incfileopt->toArray();
        if (count( $incfileopt) !=0 ) {
          $contents.="\t\tOptions {\n";
          foreach ($incfileopt as $v1) {
              $contents .= "\t\t\t\t". $v1['option'] .' = '.$v1['value'] ."\n";
          }
          $contents .= "\t\t\t}\n";
        }
        /* Include File */
        foreach ($inc as $v1) {
          foreach ( $v1 as $key => $value) {
          if ( $value!="" && $key!='id' && $key!='idfileset') {
                $contents .= "\t\t\t File = ".$value ."\n";
            }
         }
        }
        $contents .= "\t\t}\n";
        // Excludes
        $inc = cfgFileSetExclude::where('idfileset','=',$fileset)->get();
        $inc = $inc->toArray();
        if (count( $inc) !=0 ) {
          $contents.="\tExclude {\n";
          // Excludes Options
          $excludefileopt  = cfgFileSetExcludeoptions::where('idfileset','=',$fileset)->get();
          $excludefileopt  =  $excludefileopt->toArray();
          if (count( $excludefileopt) !=0 ) {
            $contents.="\t\tOptions {\n";
            foreach ($excludefileopt as $v1) {
                $contents .= "\t\t\t\t". $v1['option'] .' = '.$v1['value'] ."\n";
            }
            $contents .= "\t\t\t}\n";
          }
          /* Exclude File */
          foreach ($inc as $v1) {
            foreach ( $v1 as $key => $value) {
            if ( $value!="" && $key!='id' && $key!='idfileset') {
                  $contents .= "\t\t\t File = ".$value ."\n";
              }
           }
          }
          $contents .= "\t\t}\n";
        }
        $contents .= "}\n";

	#tweak filename to remove spaces, they are a bit of a hassle later
	$filesetfname = preg_replace("/\s+/","-",$filesetname);
        File::put($directory.'/conf.d/filesets/'.$filesetfname.'.conf', $contents);
      }
      ######################################################

      if ((Input::get('type')=="test")) {
        $output = shell_exec('sudo bacula-dir -t -c '.$directory.$dirname );
        $message = array('html' => '<div class="alert alert-danger">'.$output.'</div>');
        if ($output=="") {
          $message = array('html' => '<div class="alert alert-success"> Test Configuration Sucessufull </div>');
        }
        return Response::json($message);
      } else {
        return Response::json(array('html' => '<div class="alert alert-success"> Write Configuration Sucessufull Updated </div>'));
      }
    }

    /*****
     * Delete Configuration Items
     * @return Json
     */
    public function deleteitem()
    {
      $parent = substr(Input::get('parent',''),0,-1);
      $classname  = "app\models\Cfg".$parent;

      $item = $classname::find(Input::get('id'));
      $item->delete();
      /* FileSets delete include exclude and options */
      if ($parent="Fileset") {
        $affectedRows = Cfgfilesetexclude::where('idfileset', '=',Input::get('id'))->delete();
        $affectedRows = Cfgfilesetexcludeoptions::where('idfileset', '=',Input::get('id'))->delete();
        $affectedRows = Cfgfilesetinclude::where('idfileset', '=',Input::get('id'))->delete();
        $affectedRows = Cfgfilesetincludeoptions::where('idfileset', '=',Input::get('id'))->delete();
      }
    }


    /*****
     * New Configuration Items
     * @return Json
     */
    public function newitem()
    {
      $parent = substr(Input::get('parent',''),0,-1);
      $classname  = "app\models\Cfg".$parent;
      $model = new  $classname;
      $viewvalues = $model->getAllColumnsNames();
      foreach ($viewvalues as  $value) {
          $values[$value] ="";
      }
      Former::populate( $values);
      $values['config'] = $parent.'s';
      $values['title'] = "New ". $parent;
      $view = 'admin.configurator.'.lcfirst($parent);
      return View::make($view, $values)->render();

    }


    /*****
     * Save Or Insert New items Configuration Items
     * @return Json
     */
    public function saveconfiguration()
    {
      $save = Input::all();
      $config = substr($save['config'], 0, -1);
      $classname="app\models\Cfg".$config;
      $save = array_except($save, array('config','_token'));
      if (Input::get('id')!='') {
        $values=$classname::find(Input::get('id'));
        if ($values->update($save) ) {
          return Response::json(array('html' => '<div class="alert alert-success"> '.$config.' Sucessufull Updated </div>'));
        }
      }else{
        $user = $classname::create($save);
        return Response::json(array('html' => '<div class="alert alert-success"> '.$config.' Sucessufull Created </div>'));
      }
    }


  /**
    * Delete Schedule Run items
    * @return Json
    */
   public function deleteSchedulerun()
   {
      $exclude = CfgSchedulerun::find(Input::get('id',''));
      $exclude->delete();
      return 'ok';
    }

    /**
     * Add Schedule Runs
     * @return Json
     */
    public function addSchedulerun()
    {
        $Schedulerun = new  CfgSchedulerun;
        $Schedulerun->idschedule = Input::get('id','');;
        $Schedulerun->Run      = Input::get('Run','');
        $Schedulerun->save();
        return json_encode(true);
    }






  /**
     * Add FileSets Excludes
     * @return Json
     */
    public function addexcludes()
    {
        $filesetexclude = new  Cfgfilesetexclude;
        $filesetexclude->idfileset = Input::get('id','');;
        $filesetexclude->file      = Input::get('path','');
        $filesetexclude->save();
        return json_encode(true);

    }


    /**
     * Delete FileSets excludes
     * @return Json
     */
    public function deleteexcludes()
    {
      $exclude = Cfgfilesetexclude::find(Input::get('id',''));
      $exclude->delete();
      return 'ok';
    }

    /**
     * Add FileSets excludesoptions
     * @return Json
     */
    public function addexcludesoptions()
    {
        $filesetexclude = new  Cfgfilesetexcludeoptions;
        $filesetexclude->idfileset = Input::get('id','');;
        $filesetexclude->option    = Input::get('option','');
        $filesetexclude->value     = Input::get('value','');
        $filesetexclude->save();
        return json_encode(true);
    }

    /**
     * Delete FileSets excludesoptions
     * @return Json
     */
    public function deleteexcludesoptions()
    {
      $exclude = Cfgfilesetexcludeoptions::find(Input::get('id',''));
      $exclude->delete();
      return 'ok';
    }

    /**
     * Add FileSets Includesoptions
     * @return Json
     */
    public function addincludesoptions()
    {
        $filesetinclude = new  Cfgfilesetincludeoptions;
        $filesetinclude->idfileset = Input::get('id','');;
        $filesetinclude->option    = Input::get('option','');
        $filesetinclude->value     = Input::get('value','');

        $filesetinclude->save();
        return json_encode(true);
    }

    /**
     * Delete FileSets Includesoptions
     * @return Json
     */
    public function deleteincludesoptions()
    {
      $include = Cfgfilesetincludeoptions::find(Input::get('id',''));
      $include->delete();
      return 'ok';
    }

    /**
    * Add FileSets Includes
    * @return Json
    */
    public function addincludes()
    {
        $filesetinclude = new  Cfgfilesetinclude;
        $filesetinclude->idfileset = Input::get('id','');;
        $filesetinclude->file      = Input::get('path','');
        $filesetinclude->save();
        return json_encode(true);
    }

     /**
     * Delete FileSets Includes
     * @return Json
     */
    public function deleteincludes()
    {
      $include = Cfgfilesetinclude::find(Input::get('id',''));
      $include->delete();
      return 'ok';
    }



    /**
     * Get Schedules Run
     * @return Json
     */
    public function getschedule()
    {

      return Datatables::of(CfgSchedulerun::select(array('id','Run'))
                            ->where('idschedule','=', Input::get('idschedule'))
                )->make();
    }



    /**
     * Get FileSets Includes
     * @return Json
     */
    public function getincludes()
    {

      return Datatables::of(Cfgfilesetinclude::select(array('id','file'))
                            ->where('idfileset','=', Input::get('filesetid'))
                )->make();
    }


    /**
     * Get FileSets IncludesOptions
     * @return Json
     */
    public function getincludesoptions()
    {
      return Datatables::of(Cfgfilesetincludeoptions::select(array('id','option','value'))
                                    ->where('idfileset','=', Input::get('filesetid'))
                            )->make();
    }

    /**
     * Get FileSets Excludes
     * @return Json
     */
    public function getexcludes()
    {

      return Datatables::of(Cfgfilesetexclude::select(array('id','file'))
                                    ->where('idfileset','=', Input::get('filesetid'))
                            )->make();

    }

    /**
     * Get FileSets Excludes Options
     * @return Json
     */
    public function getexcludesoptions()
    {

      return Datatables::of(Cfgfilesetexcludeoptions::select(array('id','option','value'))
                                    ->where('idfileset','=', Input::get('filesetid'))
                            )->make();
    }



    /**
     * Read Bacula Configuration Files
     * @return View
     */
    public function readbacula()
    {
        // Delete All Database Rows

          CfgCatalog::truncate();
          CfgClient::truncate();
          CfgDirector::truncate();
          CfgFileset::truncate();
          CfgJob::truncate();
          CfgPool::truncate();
          CfgSchedule::truncate();
          CfgSchedulerun::truncate();
          CfgStorage::truncate();
          cfgfilesetinclude::truncate();
          Cfgfilesetincludeoptions::truncate();
          cfgFileSetExclude::truncate();
          cfgFileSetExcludeOptions::truncate();
          CfgConsole::truncate();
          CfgMessage::truncate();

         //path to directory to scan
         $confdir=Settings::find(1);
         $path = $confdir->confdir;

        /* Delete Test Bacula Configuration Teste*/
        $success = File::cleanDirectory($path.'/reportulateste');


        // Read All Files all Directorys
        $files = File::allFiles($path);

        foreach ($files as $file)
        {
            if (File::extension($file)=="conf" ) {
                $conffiles[]=$file;
            }
        }
        $nfiles=count($conffiles);


        ////////////////////////////////////////////////////////////////////////////
        foreach ($conffiles as $file) {

            $config = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES );
            $filename=$file->getFilename();

	    # strip all comments early, so we needn't be bothered with them later...
	    foreach ( $config as $linenum => $line ) {
		$line = preg_replace("/#.*$/","",$line);
	    }
	    # also, lets convert semicolons to newlines, as they are kinda weird too.
	    $newconfig = array();
	    foreach ( $config as $linenum => $line ) {
		if ( preg_match("/;/",$line) ) {
		      $newlines = preg_split("/;/",$line);
		      foreach ( $newlines as $num => $newline ) {
			array_push( $newconfig, $newline);
		      }

		} else {
			array_push( $newconfig, $line);
		}
	    }
	    $config = $newconfig;


            if ( $filename!='bacula-fd.conf' && $filename!='bacula-sd.conf' && $filename!='mtx-changer.conf') {

              $i=0;

              // /////////////////////////////////////Codigo para Ler as Console////////////////////////
              $Consolecfg = new CfgConsole;
              $Console = array ();
              // Codigo para Ler Values
              foreach ($config as $key => $value) {
                  if (trim($value)=="Console {") {
                      $i=$key;
                      do {
                          $i++;
                          $result = preg_split ('[=]', $config[$i]);
                          // Se não for comentário adiciona
                          if (substr(trim($result[0]), 0, 1) != '#')
                              $Console[preg_replace('/\s*/m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));
                      } while (trim($config[$i+1]) != "}");
                      $Consoletest = CfgConsole::where('Name', '=', $Console['Name']);
                      if ($Consoletest->count()==0) {

                        $Consolecfg = CfgConsole::create($Console);
                      };
                  }
              }
              ////////////////////////////////////////////////////////////////////////////////////////////

                /////////////////////////////////////Codigo para Ler as Messages////////////////////////
                $Messagescfg = new CfgMessage;
                $Messages = array ();
                // Codigo para Ler Values

                foreach ($config as $key => $value) {
                    if (trim($value)=="Messages {") {
                        $i=$key;
                        do {
                            $i++;

                            $result = preg_split ('[=]', $config[$i]);
                            if (count($result)>=3) {
                                $option = array_shift($result);
                                $value = implode("=", $result);
                                $result[0] = $option;
                                $result[1] = $value;
                            }

                            // Se não for comentário adiciona
                            if (substr(trim($result[0]), 0, 1) != '#')
                                $Messages[preg_replace('/\s*/m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));

                        } while (trim($config[$i+1]) != "}");

                       $Messagestest = CfgMessage::where('Name', '=', $Messages['Name']);
                        if ($Messagestest->count()==0) {
                            $Messagescfg = CfgMessage::create($Messages);
                        };

                    }
                }

                ///////////////////////////////////////////////////////////////////////////////////////////

                /////////////////////////////////////Codigo para Ler os Schedule////////////////////////
                $schedulecfg = new CfgSchedule;
                $schedule = array ();
                // Codigo para Ler Values
                $k=0;
                foreach ($config as $key => $value) {
                    if (trim($value)=="Schedule {") {
                        $i=$key;

                        do {
                            $i++;
                            $result = preg_split ('[=]', $config[$i]);
                            // Se não for comentário adiciona
                            //var_dump (trim($result[0]));
                            if (substr(trim($result[0]), 0, 1) != '#' ) {
                              if (trim($result[0])!="Run") {
                                  $schedule[preg_replace('/\s*/m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));
                              } else {
                                  $schedulerun[$k]['Run'] = preg_replace('/(\'|")/', '', trim($result[1]));
                                  $k++;
                              }
                            }
                        } while (trim($config[$i+1]) != "}");
                        $scheduletest = CfgSchedule::where('Name', '=', $schedule['Name']);
                        if ($scheduletest->count()==0) {
                          $scheduleid = CfgSchedule::create($schedule);
                          // Insert Run Options Schedules
                          foreach ($schedulerun as $valor) {
                            $result = array_merge($valor, array("idschedule" => $scheduleid->id));
                            CfgSchedulerun::create($result);
                          }
                        };
                    }
                }
                ////////////////////////////////////////////////////////////////////////////////////////////

               //////////////////////////////// Codigo para Ler as Pools //////////////////////////////
                $poolcfg = new CfgPool;
                $pool = array ();
                // Codigo para Ler as Pools
                foreach ($config as $key => $value) {
                    if (trim($value)=="Pool {") {
                        $i=$key;
                        do {
                            $i++;
                            $result = preg_split ('[ = ]', $config[$i]);
                            $pool[preg_replace('/\s*/m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));
                        } while (trim($config[$i+1]) != "}");
                        $pooltest = CfgPool::where('Name', '=', $pool['Name']);
                        if ($pooltest->count()==0) {
                            $poolcfg = CfgPool::create($pool);
                        };
                    }
                }
                ////////////////////////////////////////////////////////////////////////////////////////////

                //////////////////////////////// Codigo para Ler o Storage//////////////////////////////
                if ( $filename!='bacula-sd.conf' && $filename!='tray-monitor.conf') {
                  $storagecfg = new CfgStorage;
                  $storage = array ();
                  // Codigo para Ler O Storage
                  foreach ($config as $key => $value) {
                      if (trim($value)=="Storage {") {
                          $i=$key;
                          do {
                              $i++;
                              $result = preg_split ('[ = ]', $config[$i]);
			      if ( array_key_exists(1,$result)) {
                              	$storage[preg_replace('/\s*/m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));
			      }
                          } while (trim($config[$i+1]) != "}");
                          $storagetest = Cfgstorage::where('Name', '=', $storage['Name']);
                          if ($storagetest->count()==0) {
                              $storagecfg = Cfgstorage::create($storage);
                          };
                      }
                  }
                }
                ////////////////////////////////////////////////////////////////////////////////////////////

                //////////////////////////////// Codigo para Ler o Catalog

                $catalogcfg = new CfgCatalog;
                $catalog = array ();
                // Codigo para Ler as Pools
                foreach ($config as $key => $value) {
                    if (trim($value)=="Catalog {") {
                        $i=$key;
                        do {
                            $i++;
                            $result = preg_split ('[ = ]', $config[$i]);
                            $catalog[preg_replace('/\s*/m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));
                        } while (trim($config[$i+1]) != "}");
                        $catalogtest = Cfgcatalog::where('Name', '=', $catalog['Name']);
                        if ($catalogtest->count()==0) {
                           $catalogcfg = CfgCatalog::create($catalog);
                        };
                    }

                }
                //////////////////////////////////////////////////////////////////////////////////////////

                ////////////////////////// Codigo para Ler o Director ////////////////////////////////////////////////
                if ($filename!='bconsole.conf' && $filename=='bacula-dir.conf') {
                    $directorcfg = new CfgDirector;
                    $director = array ();
                    // Codigo para Ler Values
                    foreach ($config as $key => $value) {
                        if (trim($value)=="Director {") {

                            $i=$key;
                            do {
                                $i++;
                                $result = preg_split ('[ = ]', $config[$i]);
                                $director[preg_replace('/\s*/m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));
                            } while (trim($config[$i+1]) != "}");
                            $directortest = CfgDirector::where('Name', '=', $director['Name']);
                            if ($directortest->count()==0) {

                                $directorcfg = CfgDirector::create($director);
                            };
                        }

                    }
                }
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////



                ////////////////////////////////// Codigo para Ler o Client////////////////////////
                 if ($filename!='tray-monitor.conf' ) {
                  $clientcfg = new CfgClient;
                  $client = array ();

                  foreach ($config as $key => $value) {
                      if (trim($value)=="Client {") {
                          $i=$key;
                          do {
                              $i++;
                              $result = preg_split ('[=]', $config[$i]);
                              $client[preg_replace('/\s*/m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));
                          } while (trim($config[$i+1]) != "}");
                          $clienttest = CfgClient::where('Name', '=', $client['Name']);

                          if ($clienttest->count()==0) {
                              $clientcfg = Cfgclient::create($client);
                          };
                          //log::info($filename,$client);
                      }
                  }
                }
                ////////////////////////////////////////////////////////////////////////////////////////////
                ///////////////////////////// Codigo para Ler os Jobs//////////////////////////////////////////////////////
                $jobcfg = new CfgJob;
                $job = array ();
                // Codigo para Ler Values

                foreach ($config as $key => $value) {
                    if (trim($value)=="Job {" || trim($value)=="JobDefs {") {
                        $i=$key;
                        do {
                            $i++;
                            $result = preg_split ('[=]', $config[$i]);
			    if ( array_key_exists(1,$result)) {
                            	// Se não for comentário adiciona
                            	//if (substr(trim($result[0]), 0, 1) != '#')
                               	$job[preg_replace('/\s*/m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));
			    }
                        } while (trim($config[$i+1]) != "}");

                        $jobtest = CfgJob::where('Name', '=', $job['Name']);
                        if ($jobtest->count()==0) {
                            $jobcfg = CfgJob::create($job);
                        };
                    }
                }
              //   ///////////////////////////////////////////////////////////////////////////////////////////////////////////


                ///////////////////////////// Codigo para Ler os  Filesets//////////////////////////////////////////////////////
                $filesetcfg = new CfgFileset;
                $fileset = array ();
                // Codigo para Ler Values
                $oinc =array();
                $oexc =array();
                $finc =array();
                $fexc =array();




                foreach ($config as $key => $value) {

                    if (trim($value)=="FileSet {") {
                      $key++;
                      while (trim($config[$key]) != "}") {
                        if (trim($config[$key])=="Include {") {
                          $key++;
                          $z=0;
                          while (trim($config[$key]) != "}") {
                            if (trim($config[$key])=="Options {") {
                              $k=0;
                              $key++;
                              while ( trim($config[$key]) != "}") {
                                $options = preg_split ('[=]', $config[$key]);
                                if (substr(trim($options [0]), 0, 1) != '#') {
                                  $oinc[$k]['option'] = preg_replace('/\s*/m', '', $options [0]);
                                  $oinc[$k]['value']  = preg_replace('/(\'|")/', '', trim($options[1]));
                                  $k++;
                                }
                                $key++;
                              }
                              $key++;
                            } // Fecho do If das Options Include
                            $include = preg_split ('[=]', $config[$key]);
                            if (substr(trim($include [0]), 0, 1) != '#') {
                              if (array_key_exists(1, $result)) {
                                $finc[$z][preg_replace('/\s*/m', '', $include [0])]= preg_replace('/(\'|")/', '', trim($include[1]));
                                $z++;
                              }
                            }
                            $key++;
                          }
                          $key++;
                        }
                        if (trim($config[$key])=="}") {
                          break;
                        }
                        /* Fileset Exclude */
                        if (trim($config[$key])=="Exclude {") {
                          $key++;
                          $h=0;
                          while (trim($config[$key]) != "}") {
                            if (trim($config[$key])=="Options {") {
                              $b=0;
                              $key++;
                              while ( trim($config[$key]) != "}") {
                                $options = preg_split ('[=]', $config[$key]);
                                if (substr(trim($options [0]), 0, 1) != '#') {
                                  $oexc[$b]['option'] =preg_replace('/\s*/m', '', $options [0]);
                                  $oexc[$b]['value'] = preg_replace('/(\'|")/', '', trim($options[1]));
                                  $k++;
                                }
                                $key++;
                              }
                              $key++;
                            } // Fecho do If das Options Include
                            $exclude = preg_split ('[=]', $config[$key]);
                            if (substr(trim($exclude [0]), 0, 1) != '#') {
                              if (array_key_exists(1, $result)) {
                               $fexc[$h][preg_replace('/\s*/m', '', $exclude [0])]= preg_replace('/(\'|")/', '', trim($exclude[1]));
                                $h++;
                              }
                              $key++;
                            }
                          }
                        }
                        $result = preg_split ('[=]', $config[$key]);
                        if ((substr(trim($result[0]), 0, 1) != '#') && (substr(trim($result[0]), 0, 1) != '}')) {
                          if (array_key_exists(1, $result)) {
                            $fileset[preg_replace('/\s*/m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));
                          }
                        }
                        $key++;
                        if (!array_key_exists($key, $config)) {
                          break;
                        }
                      }

                      ////////////////////////////////////////// Fecho do while do FileSet

                      //Codigo para Inserir Fileset na BD
                      $filesettest = CfgFileset::where('Name', '=', $fileset['Name']);
                      if ($filesettest->count()==0) {
                          $filesetcfg = CfgFileset::create($fileset);
                          $filesetid = $filesetcfg->id;
                      } else {
                          $filesetid = $filesettest->first()->id;
                      };

                      // Insert File Includes
                      foreach ($finc as $valor) {
                          $result = array_merge($valor, array("idfileset" => $filesetid));
                          $filesetcfg = Cfgfilesetinclude::create($result);
                      }

                      // Insert File Excludes
                      foreach ($fexc as $valor) {
                          $result = array_merge($valor, array("idfileset" => $filesetid));
                          $filesetcfg = Cfgfilesetexclude::create($result);
                      }

                      // Insert File Options Include
                      foreach ($oinc as $valor) {
                          $result = array_merge($valor, array("idfileset" => $filesetid));
                          $filesetcfg = Cfgfilesetincludeoptions::create($result);
                      }

                       // Insert File Options Excludes
                      foreach ($oexc as $valor) {
                          $result = array_merge($valor, array("idfileset" => $filesetid));
                          $filesetcfg = Cfgfilesetexcludeoptions::create($result);
                      }
                    }
                    ////////////////////////////////////////// Fecho do if do FileSet
                }
           }

        }
        return Response::json(array('html' => '<div class="alert alert-success"> '.$nfiles.' Configuration Files Readed! </div>'));

    }
}
