<?php namespace app\controllers\admin;

use BaseController, Datatables, View, Sentry, URL, Input, Validator, Response, Former, Log, Asset, Vd\Vd, File, Request;
use Debugbar, Cache, DB, Filesystem;

// Models
use app\models\Settings;
use app\models\CfgDirector;
use app\models\CfgSchedule;
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


        Asset::add('jquery', 'assets/js/jquery-2.0.3.min.js');
        Asset::add('jquery-ui.min.js', 'assets/js/jquery-ui.min.js','jquery');

        Asset::add('jquerymultiselect', 'assets/js/jquery.multi-select.js', 'jquery');
        Asset::add('eldarionform', 'assets/js/eldarion-ajax.min.js', 'jquery');
        Asset::add('bootbox.js', 'assets/js/bootbox.min.js', 'jquery');
        Asset::add('tab', 'assets/js/bootstrap-tab.js', 'jquery2');
        Asset::add('fancytree', 'assets/js/jquery.fancytree-all.min.js', 'jquery');
        Asset::add('fancytreefilter', 'assets/js/jquery.fancytree.filter.js', 'fancytree');

        Asset::add('jquery.jeditable.js', 'assets/js/jquery.jeditable.js');
        Asset::add('jquery.validate.js', 'assets/js/jquery.validate.js');
        Asset::add('jquery.dataTables.editable.js', 'assets/js/jquery.dataTables.editable.js','jquery');

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
     * Write Configuration Files
     * @return Json
     */
    public function writebacula()
    {

      $contents ='Director {
        Name = cyclopes.bkp.fccn.pt-dir
        DIRport = 9101
        QueryFile = "/etc/bacula/query.sql"
        WorkingDirectory = "/backup/working-dir"
        PidDirectory = "/var/run"
        Maximum Concurrent Jobs = 18
        Password = "UDy45fQxup8h/zOvqp98OcazmV+Km7VP99nDR94E/oIH"
        Messages = Daemon
        Heartbeat Interval = 60
      }




    #############################################################################################
    ################################## Definicoes dos JOBS ######################################
    #############################################################################################




#@/etc/bacula/conf.d/clients/bancodevideo.fccn.pt-job.conf




      ';

      $contents ="Director {\n";


      $model = Cfgdirector::find(1);
      $model = $model->toArray();

      //dd($model);
      foreach( $model as $key => $value){
        if ( $value!="" && $key!='id') {

          if ($key=='MaximumConcurrentJobs') { $key = "Maximum Concurrent Jobs"; }
          if ($key=='HeartbeatInterval')     { $key = "Heartbeat Interval"; }

            $contents .= "\t". $key .' = '.$value ."\n";
        }
      }

      $contents .= "}\n
      \n

      ###################### JOBS DEFAULTS DEFINITION FILES ###############################################\n
      @/etc/bacula/conf.d/jobdefaults.conf\n

      ###################### Schedule Definition Files ##############################################\n
      @/etc/bacula/conf.d/schedule.conf\n
      \n
      ###################### STORAGE DEFINITION FILES ###############################################\n
      @/etc/bacula/conf.d/storage.conf\n
      \n
      ";
      File::put('/home/pedro/www/laravel/bacula/bacula-dir.conf', $contents);




      ################## JobDefaults ######################

      $model = CfgJob::where('JobDefs', '=', Null)
                      ->where('Type', '<>', 'Admin')
                      ->where('Type', '<>', 'Restore')

                      ->get();


      $model = $model->toArray();


      dd ($model);

      $contents ="JobDefs {\n";
      foreach( $model[0] as $key => $value){
        if ( $value!="" && $key!='id') {
            $contents .= "\t". $key .' = '.$value ."\n";
        }
      }
      $contents .= "}\n\n\n";



      File::put('/home/pedro/www/laravel/bacula/conf.d/jobdefaults.conf', $contents);








      return Response::json(array('html' => $contents));

      //return Response::json(array('html' => '<div class="alert alert-success"> Write Configuration Sucessufull Updated </div>'));

    }


    /*****
     * Save Configuration Items
     * @return Json
     */
    public function saveconfiguration()
    {
      $save = Input::all();
      $config = substr($save['config'], 0, -1);

      $classname="app\models\Cfg".$config;
      $values=$classname::find(Input::get('id'));
      $save = array_except($save, array('config'));

      if ($values->update($save) ) {
        return Response::json(array('html' => '<div class="alert alert-success"> '.$config.' Sucessufull Updated </div>'));
      }

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
     * Edit FileSets excludes
     * @return Json
     */
    public function editexcludes()
    {
      $exclude = Cfgfilesetexclude::find(Input::get('id'));
      $exclude->file = Input::get('value');
      $exclude->save();
      return $exclude->file;
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
        $filesetexclude->file  = Input::get('path','');
        $filesetexclude->save();
        return json_encode(true);


    }

    /**
     * Edit FileSets excludesoptions
     * @return Json
     */
    public function editexcludesoptions()
    {
      $exclude = Cfgfilesetexcludeoptions::find(Input::get('id'));
      $exclude->option = Input::get('option');
      $exclude->value = Input::get('value');
      $exclude->save();
      return $exclude->value;
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
        $filesetinclude->file  = Input::get('path','');
        $filesetinclude->save();
        return json_encode(true);


    }

    /**
     * Edit FileSets Includesoptions
     * @return Json
     */
    public function editincludesoptions()
    {
      $include = Cfgfilesetincludeoptions::find(Input::get('id'));
      $include->option = Input::get('option');
      $include->value = Input::get('value');
      $include->save();
      return $include->value;
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
     * Edit FileSets Includes
     * @return Json
     */
    public function editincludes()
    {
      $include = Cfgfilesetinclude::find(Input::get('id'));
      $include->file = Input::get('value');
      $include->save();
      return $include->file;
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

        //remover o path
        $path.="bacula";

        foreach ($conffiles as $file) {
            $config = file($file);
           // LOG::info($path);
           // LOG::info($file);
            $filename=$file->getFilename();

            if ( $filename!='bacula-fd.conf' || $filename!='bacula-sd.conf' || $filename!='mtx-changer.conf') {
                   $i=0;
             //   LOG::info($filename);

                /////////////////////////////////////Codigo para Ler as Messages////////////////////////
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
                ////////////////////////////////////////////////////////////////////////////////////////////


                /////////////////////////////////////Codigo para Ler os Schedule////////////////////////

                $schedulecfg = new CfgSchedule;
                $schedule = array ();
                // Codigo para Ler Values

                foreach ($config as $key => $value) {
                    if (trim($value)=="Schedule {") {
                        $i=$key;
                        do {
                            $i++;
                            $result = preg_split ('[=]', $config[$i]);
                            // Se não for comentário adiciona
                            if (substr(trim($result[0]), 0, 1) != '#')
                                $schedule[preg_replace('/\s*/m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));
                        } while (trim($config[$i+1]) != "}");

                        $scheduletest = CfgSchedule::where('Name', '=', $schedule['Name']);
                        if ($scheduletest->count()==0) {
                            $schedulecfg = CfgSchedule::create($schedule);
                        };
                    }
                }
                ////////////////////////////////////////////////////////////////////////////////////////////

                ////////////////////////////////// Codigo para Ler o Client////////////////////////
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

                $storagecfg = new CfgStorage;
                $storage = array ();
                // Codigo para Ler O Storage
                foreach ($config as $key => $value) {
                    if (trim($value)=="Storage {") {
                        $i=$key;
                        do {
                            $i++;
                            $result = preg_split ('[ = ]', $config[$i]);
                            $storage[preg_replace('/\s*/m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));
                        } while (trim($config[$i+1]) != "}");
                        $storagetest = Cfgstorage::where('Name', '=', $storage['Name']);
                        if ($storagetest->count()==0) {
                            $storagecfg = Cfgstorage::create($storage);
                        };
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
                if ($filename!='bconsole.conf') {
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
                            // Se não for comentário adiciona
                            if (substr(trim($result[0]), 0, 1) != '#')
                                $job[preg_replace('/\s*/m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));
                        } while (trim($config[$i+1]) != "}");

                        $jobtest = CfgJob::where('Name', '=', $job['Name']);
                        if ($jobtest->count()==0) {
                            $jobcfg = CfgJob::create($job);
                        };
                    }
                }
                ///////////////////////////////////////////////////////////////////////////////////////////////////////////

                ///////////////////////////// Codigo para Ler os Filesets//////////////////////////////////////////////////////
                $filesetcfg = new CfgFileset;
                $fileset = array ();
                // Codigo para Ler Values
                $oinc =array();
                $oexc =array();
                $finc =array();
                $fexc =array();

                foreach ($config as $key => $value) {
                    // Se For FileSet Executa

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
                                                $oinc[$k]['option'] =preg_replace('/\s*/m', '', $options [0]);
                                                $oinc[$k]['value'] = preg_replace('/(\'|")/', '', trim($options[1]));
                                                $k++;
                                            }
                                            $key++;
                                        }
                                        $key++;
                                    } // Fecho do If das Options Include
                                    $include = preg_split ('[=]', $config[$key]);

                                    if (substr(trim($include [0]), 0, 1) != '#') {
                                        $finc[$z][preg_replace('/\s*/m', '', $include [0])]= preg_replace('/(\'|")/', '', trim($include[1]));
                                        $z++;
                                    }
                                    $key++;
                                }
                                $key++;
                            }

                            if (trim($config[$key])=="Exclude {") {
                                $key++;
                                $z=0;
                                while (trim($config[$key]) != "}") {
                                    if (trim($config[$key])=="Options {") {
                                        $k=0;
                                        $key++;
                                        while ( trim($config[$key]) != "}") {
                                            $options = preg_split ('[=]', $config[$key]);
                                            if (substr(trim($options [0]), 0, 1) != '#') {
                                                $oexc[$k]['option'] =preg_replace('/\s*/m', '', $options [0]);
                                                $oexc[$k]['value'] = preg_replace('/(\'|")/', '', trim($options[1]));
                                                $k++;
                                            }
                                            $key++;
                                        }
                                        $key++;
                                    } // Fecho do If das Options Include
                                    $exclude = preg_split ('[=]', $config[$key]);
                                     if (substr(trim($exclude [0]), 0, 1) != '#') {
                                        $fexc[$z][preg_replace('/\s*/m', '', $exclude [0])]= preg_replace('/(\'|")/', '', trim($exclude[1]));
                                        $z++;
                                    }
                                    $key++;
                                }
                            }


                            $result = preg_split ('[=]', $config[$key]);
                           // log::info($result);

                            if ((substr(trim($result[0]), 0, 1) != '#') && (substr(trim($result[0]), 0, 1) != '}'))
                                $fileset[preg_replace('/\s*/m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));

                            if (!array_key_exists($key+1, $config)) {

                                 break;

                            }

                            $key++;
                        }

                        // Codigo para Inserir Fileset na BD
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
        return "$nfiles Configuration Files Readed!" ;

    }

    public function action_index($date=null)
    {

    // READ Directores Jobs
    /*
        Include { Options {file-options} ...; file-list }
        Options { file-options }
        Exclude { file-list }

    */

    /*  Resolver os Espaços, Resolver o codigo do Schedule na parte do level run"
    */
    $directory = "bacula/conf.d/clients/";



    ////////////////////////////////////////////////////////////////////////////

    foreach ($files as $file) {
        $config = file($file);
      //  Log::write('info',  $file);
       // Log::write('info',  explode($path, $file));

        if (($file!="bacula\bacula-fd.conf") || ($file!="bacula\bacula-sd.conf") ) {


            /////////////////////////////////////Codigo para Ler os Schedule////////////////////////

            $i=0;
            $schedulecfg = new Cfgschedule;
            $schedule = array ();
            // Codigo para Ler Values

            foreach ($config as $key => $value) {
                if (trim($value)=="Schedule {") {
                    $i=$key;
                    do {
                        $i++;
                        $result = preg_split ('[=]', $config[$i]);
                        // Se não for comentário adiciona
                        if (substr(trim($result[0]), 0, 1) != '#')
                            $schedule[preg_replace('/\s*/m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));
                    } while (trim($config[$i+1]) != "}");


                    $scheduletest = Cfgschedule::where('Name', '=', $schedule['Name']);
                    if ($scheduletest->count()==0) {
                        $schedulecfg = Cfgschedule::create($schedule);
                    };
                }
            }
            ////////////////////////////////////////////////////////////////////////////////////////////


            ////////////////////////////////// Codigo para Ler o Client////////////////////////
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
                        $clientcfg = CfgClient::create($client);
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

            $storagecfg = new CfgStorage;
            $storage = array ();
            // Codigo para Ler O Storage
            foreach ($config as $key => $value) {
                if (trim($value)=="Storage {") {
                    $i=$key;
                    do {
                        $i++;
                        $result = preg_split ('[ = ]', $config[$i]);
                        $storage[preg_replace('/\s*/m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));
                    } while (trim($config[$i+1]) != "}");
                    $storagetest = Cfgstorage::where('Name', '=', $storage['Name']);
                    if ($storagetest->count()==0) {
                        $storagecfg = Cfgstorage::create($storage);
                    };
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
                        $catalogcfg = Cfgcatalog::create($catalog);
                    };
                }

            }
            //////////////////////////////////////////////////////////////////////////////////////////

            ////////////////////////// Codigo para Ler o Director ////////////////////////////////////////////////

            $directorcfg = new Cfgdirector;
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
                    $directortest = Cfgdirector::where('Name', '=', $director['Name']);
                    if ($directortest->count()==0) {
                        $directorcfg = Cfgdirector::create($director);
                    };
                }

            }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////

            ///////////////////////////// Codigo para Ler os Jobs//////////////////////////////////////////////////////
            $jobcfg = new Cfgjob;
            $job = array ();
            // Codigo para Ler Values

            foreach ($config as $key => $value) {

                if (trim($value)=="Job {") {

                    $i=$key;
                    do {
                        $i++;
                        $result = preg_split ('[=]', $config[$i]);
                        // Se não for comentário adiciona
                        if (substr(trim($result[0]), 0, 1) != '#')
                            $job[preg_replace('/\s*/m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));
                    } while (trim($config[$i+1]) != "}");

                    $jobtest = Cfgjob::where('Name', '=', $job['Name']);
                    if ($jobtest->count()==0) {
                        $jobcfg = Cfgjob::create($job);
                    };
                }
            }
            ///////////////////////////////////////////////////////////////////////////////////////////////////////////

            ///////////////////////////// Codigo para Ler os Filesets//////////////////////////////////////////////////////
            $filesetcfg = new Cfgfileset;
            $fileset = array ();
            // Codigo para Ler Values
            $oinc =array();
            $oexc =array();
            $finc =array();
            $fexc =array();

            foreach ($config as $key => $value) {
                // Se For FileSet Executa

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
                                            $oinc[$k]['option'] =preg_replace('/\s*/m', '', $options [0]);
                                            $oinc[$k]['value'] = preg_replace('/(\'|")/', '', trim($options[1]));
                                            $k++;
                                        }
                                        $key++;
                                    }
                                    $key++;
                                } // Fecho do If das Options Include
                                $include = preg_split ('[=]', $config[$key]);

                                if (substr(trim($include [0]), 0, 1) != '#') {
                                    $finc[$z][preg_replace('/\s*/m', '', $include [0])]= preg_replace('/(\'|")/', '', trim($include[1]));
                                    $z++;
                                }
                                $key++;
                            }
                            $key++;
                        }

                        if (trim($config[$key])=="Exclude {") {
                            $key++;
                            $z=0;
                            while (trim($config[$key]) != "}") {
                                if (trim($config[$key])=="Options {") {
                                    $k=0;
                                    $key++;
                                    while ( trim($config[$key]) != "}") {
                                        $options = preg_split ('[=]', $config[$key]);
                                        if (substr(trim($options [0]), 0, 1) != '#') {
                                            $oexc[$k]['option'] =preg_replace('/\s*/m', '', $options [0]);
                                            $oexc[$k]['value'] = preg_replace('/(\'|")/', '', trim($options[1]));
                                            $k++;
                                        }
                                        $key++;
                                    }
                                    $key++;
                                } // Fecho do If das Options Include
                                $exclude = preg_split ('[=]', $config[$key]);
                                 if (substr(trim($exclude [0]), 0, 1) != '#') {
                                    $fexc[$z][preg_replace('/\s*/m', '', $exclude [0])]= preg_replace('/(\'|")/', '', trim($exclude[1]));
                                    $z++;
                                }
                                $key++;
                            }
                        }

                        $result = preg_split ('[=]', $config[$key]);
                        if ((substr(trim($result[0]), 0, 1) != '#') && (substr(trim($result[0]), 0, 1) != '}')) $fileset[preg_replace('/\s*/m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));

                        if (!array_key_exists($key+1, $config)) {

                             break;

                        }

                        $key++;
                    }

                    // Codigo para Inserir Fileset na BD
                    $filesettest = Cfgfileset::where('Name', '=', $fileset['Name']);
                    if ($filesettest->count()==0) {
                        $filesetcfg = Cfgfileset::create($fileset);
                        $filesetid = $filesetcfg->id;
                    } else {
                        $filesetid = $filesettest->first()->id;
                    };

                    // Insert File Includes
                    foreach ($finc as $valor) {
                        $result = array_merge($valor, array("idfileset" => $filesetid));
                        $filesetcfg = cfgFileSetInclude::create($result);
                    }

                    // Insert File Excludes
                    foreach ($fexc as $valor) {
                        $result = array_merge($valor, array("idfileset" => $filesetid));
                        $filesetcfg = cfgFileSetExclude::create($result);
                    }

                    // Insert File Options Include
                    foreach ($oinc as $valor) {
                        $result = array_merge($valor, array("idfileset" => $filesetid));
                        $filesetcfg = cfgFileSetIncludeOptions::create($result);
                    }

                     // Insert File Options Excludes
                    foreach ($oexc as $valor) {
                        $result = array_merge($valor, array("idfileset" => $filesetid));
                        $filesetcfg = cfgFileSetExcludeOptions::create($result);
                    }
                }
                ////////////////////////////////////////// Fecho do if do FileSet
            }
        }

    }

    // READ Directores Jobs

    //path to directory to scan
/*    $directory = "bacula/conf.d/clients/";

    //get all files with a .conf extension.
    $files = glob($directory . "*.conf");

    foreach ($files as $file) {
        // Codigo para Ler os Jobs
        $config = file($file);
        $i=0;

        $jobcfg = new Cfgjob;
        $job = array ();
        // Codigo para Ler Values

        foreach ($config as $key => $value) {

            if (trim($value)=="Job {") {

                $i=$key;
                do {
                    $i++;
                    $result = preg_split ('[=]', $config[$i]);
                    // Se não for comentário adiciona
                    if (substr(trim($result[0]), 0, 1) != '#')
                        $job[preg_replace('/\s*///m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));
/*                } while (trim($config[$i+1]) != "}");
                 Log::write('info', $job);

                $jobtest = Cfgjob::where('Name', '=', $job['Name']);
                if ($jobtest->count()==0) {
                    $jobcfg = Cfgjob::create($job);
                };
            }

        }

    }*/

     // READ Directores Director

    //path to directory to scan
  /*  $directory = "bacula/";

    //get all files with a .conf extension.
    $files = glob($directory . "bacula-dir.conf");

    foreach ($files as $file) {
        // Codigo para Ler o Catalog
        $config = file($file);
        $i=0;
        $directorcfg = new Cfgdirector;
        $director = array ();
        // Codigo para Ler Values
        foreach ($config as $key => $value) {
            if (trim($value)=="Director {") {

                $i=$key;
                do {
                    $i++;
                    $result = preg_split ('[ = ]', $config[$i]);
                    $director[preg_replace('/\s*///m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));
   /*             } while (trim($config[$i+1]) != "}");
                $directortest = Cfgdirector::where('Name', '=', $director['Name']);
                if ($directortest->count()==0) {
                    $directorcfg = Cfgdirector::create($director);
                };
            }

        }

    }

// READ Directores Catalog

    //path to directory to scan
 /*   $directory = "bacula/";

    //get all files with a .conf extension.
    $files = glob($directory . "bacula-dir.conf");

    foreach ($files as $file) {
        // Codigo para Ler o Catalog
        $config = file($file);
        $i=0;
        $catalogcfg = new CfgCatalog;
        $catalog = array ();
        // Codigo para Ler as Pools
        foreach ($config as $key => $value) {
            if (trim($value)=="Catalog {") {

                $i=$key;
                do {
                    $i++;
                    $result = preg_split ('[ = ]', $config[$i]);
                    $catalog[preg_replace('/\s*///m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));
   /*             } while (trim($config[$i+1]) != "}");
                $catalogtest = Cfgcatalog::where('Name', '=', $catalog['Name']);
                if ($catalogtest->count()==0) {
                    $catalogcfg = Cfgcatalog::create($catalog);
                };
            }

        }

    }

    // READ Directores Storages

    //path to directory to scan
  /*  $directory = "bacula/conf.d/";

    //get all files with a .conf extension.
    $files = glob($directory . "storage.conf");

    foreach ($files as $file) {
        // Codigo para Ler o Storage
        $config = file($file);
        $i=0;
        $storagecfg = new CfgStorage;
        $storage = array ();
        // Codigo para Ler as Pools
        foreach ($config as $key => $value) {
            if (trim($value)=="Storage {") {

                $i=$key;
                do {
                    $i++;
                    $result = preg_split ('[ = ]', $config[$i]);
                    $storage[preg_replace('/\s*///m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));
  /*              } while (trim($config[$i+1]) != "}");
                $storagetest = Cfgstorage::where('Name', '=', $storage['Name']);
                if ($storagetest->count()==0) {
                    $storagecfg = Cfgstorage::create($storage);
                };
            }

        }

    }

 /*
    // READ POOLS
    //path to directory to scan
    $directory = "bacula/conf.d/";

    //get all files with a .conf extension.
    $files = glob($directory . "pools.conf");

    foreach ($files as $file) {
        // Codigo para Ler o Client
        $config = file($file);
        $i=0;
        $poolcfg = new CfgPool;
        $pool = array ();
        // Codigo para Ler as Pools
        foreach ($config as $key => $value) {
            if (trim($value)=="Pool {") {

                $i=$key;
                do {
                    $i++;
                    $result = preg_split ('[ = ]', $config[$i]);
                    $pool[preg_replace('/\s*///m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));
/*                } while (trim($config[$i+1]) != "}");
                $pooltest = CfgPool::where('Name', '=', $pool['Name']);
                if ($pooltest->count()==0) {
                    $poolcfg = CfgPool::create($pool);
                };
            }

        }

    }*/

 // READ Clients

    // Intera sobre todos os ficheiro encontrados e preenche a bd
     //path to directory to scan
    /*$directory = "bacula/conf.d/clients/";

    //get all files with a .conf extension.
    $files = glob($directory . "*.conf");

  /*  foreach ($files as $file) {
        // Codigo para Ler o Client
        $config = file($file);
        $i=0;
        $clientcfg = new CfgClient;
        $client = array ();
        foreach ($config as $key => $value) {
            if (trim($value)=="Client {") {
                $i=$key;
                do {
                    $i++;
                    $result = preg_split ('[ = ]', $config[$i]);
                    $client[preg_replace('/\s*///m', '', $result[0])]= preg_replace('/(\'|")/', '', trim($result[1]));
    /*            } while (trim($config[$i+1]) != "}");
            }
            $clienttest = CfgClient::where('Name', '=', $client['Name']);

            if ($clienttest->count()==0) {
                $clientcfg = CfgClient::create($client);
            };
        }
    }*/
            //Log::write('info', $client);
            //$client[trim($result[1])]=trim($result[2]);
            //ChromePhp::log($result);

           // Log::write('info', $client);
        return View::make('readconfig',array(
                                    'username'      => $this->username,

                                )
                         );
    }

}
