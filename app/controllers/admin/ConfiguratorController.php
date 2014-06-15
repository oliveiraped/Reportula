<?php namespace app\controllers\admin;

use BaseController, Datatables, View, Sentry, URL, Input, Validator, Response, Former, Log, Asset, Vd\Vd, File, Request;
use Debugbar, Cache;
//use DB;

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

class ConfiguratorController extends BaseController
{
   public function __construct()
    {
        parent::__construct();
        
        Asset::add('multi-select', 'assets/css/multi-select.css');
        Asset::add('fancytreecss', 'assets/css/fancytree.css');

        Asset::add('jquery2', 'assets/js/jquery-2.0.3.min.js');
        Asset::add('jqueryui', 'assets/js/jquery.ui.js', 'jquery2');
        Asset::add('jquerymultiselect', 'assets/js/jquery.multi-select.js', 'jquery');
        Asset::add('eldarionform', 'assets/js/eldarion-ajax.min.js', 'jquery');
        Asset::add('bootbox.js', 'assets/js/bootbox.min.js', 'jquery');
        Asset::add('tab', 'assets/js/bootstrap-tab.js', 'jquery2');
        Asset::add('fancytree', 'assets/js/jquery.fancytree-all.min.js', 'jquery');
        Asset::add('fancytreefilter', 'assets/js/jquery.fancytree.filter.js', 'fancytree');
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
        $parent = Input::get('parent','');
        
        if ( $parent=="Director") {
            $viewvalues = CfgDirector::orderBy('Name')->where('Name',$node)->remember(10)->first()->toArray();
            Former::populate(  $viewvalues );    
            $view = 'admin.configurator.director';
        }

        if ( $parent=="Storage") {
            $viewvalues = CfgStorage::orderBy('Name')->where('Name',$node)->remember(10)->first()->toArray();
            Former::populate( $viewvalues );    
            $view = 'admin.configurator.storage';
        }

        if ( $parent=="Clients") {
            $viewvalues = CfgClient::orderBy('Name')->where('Name',$node)->remember(10)->first()->toArray();
            Former::populate( $viewvalues );    
            $view = 'admin.configurator.clients';
        }

        if ( $parent=="Jobs") {
            $viewvalues = CfgJob::orderBy('Name')->where('Name',$node)->remember(10)->first()->toArray();
            Former::populate( $viewvalues );    
            $view = 'admin.configurator.jobs';
        }

       
         if ( $parent=="Filesets") {
            $viewvalues = CfgFileset::with('Cfgfilesetinclude')
                                    ->with('Cfgfilesetexclude')
                                    ->with('Cfgfilesetincludeoptions')
                                    ->with('Cfgfilesetexcludeoptions')
                                    ->orderBy('Name')->where('Name',$node)
                                    ->remember(10)->first()->toArray();
            
            Debugbar::info($viewvalues);
           
            //Debugbar::info($viewvalues-> )
        
           
           
            
            //$cfgFileSetIncludeOptions = Cfgfilesetincludeoptions::orderBy('Name')->where('Name',$node)->first()->toArray();
            //$cfgfilesetexcludeoptions = Cfgfilesetexcludeoptions::orderBy('Name')->where('Name',$node)->first()->toArray();
            

            Former::populate( $viewvalues );    
            $view = 'admin.configurator.filesets';
        }

        if ( $parent=="Catalogs") {
            $viewvalues = CfgCatalog::orderBy('Name')->where('Name',$node)->remember(10)->first()->toArray();
            Former::populate( $viewvalues );    
            $view = 'admin.configurator.catalog';
        }

        if ( $parent=="Pools") {
            $viewvalues = Cfgpool::orderBy('Name')->where('Name',$node)->remember(10)->first()->toArray();
            Former::populate( $viewvalues );    
            $view = 'admin.configurator.pools';
        }

        if ( $parent=="Schedules") {
            $viewvalues = Cfgschedule::orderBy('Name')->where('Name',$node)->remember(10)->first()->toArray();
            Former::populate( $viewvalues );    
            $view = 'admin.configurator.schedules';
        }

        return View::make($view,  $viewvalues)->render();

        
    }

     /**
     * Get Tree Data 
     * @return Json
     */
    public function gettreedata()
    {
        $tree=array();
        $key=2;


        /* Fill Up Directors Name on Tree */
        $dir = CfgDirector::orderBy('Name')->remember(10)->get();
        foreach ($dir as $director)
        {
             $dirarray[]=array('key'=> $key++, 'title' => $director->Name, 'parent' => $director->id);
        }

        $tree[]=array('key'         => 1, 
                      'title'       => 'Director', 
                      'folder'      => 'true',
                      'children'    => $dirarray
                      );  
        $lastkey=$key;

        /* Fill Up Storage Name on Tree */
        $stor = CfgStorage::orderBy('Name')->remember(10)->get();
        foreach ($stor as $storage)
        {
             $storarray[]=array('key'=> $key++, 'title' => $storage->Name , 'id' => $storage->id);
        }

        $tree[]=array('key'         => $lastkey, 
                      'title'       => 'Storage', 
                      'folder'      => 'true',
                      'children'    => $storarray
                      );          

        $lastkey=$key;

        /* Fill Up Clients Name on Tree */
        $clt = CfgClient::orderBy('Name')->remember(10)->get(); 
        foreach ($clt as $client)
        {
             $cltarray[]=array('key'=> $key++, 'title' => $client->Name ,'id' =>  $client->id);
        }
        $tree[]=array('key'         => $lastkey, 
                      'title'       => 'Clients', 
                      'folder'      => 'true',
                      'children'    => $cltarray
                      );          

        $lastkey=$key;


        /* Fill Up Jobs Name on Tree */
        $job = CfgJob::orderBy('Name')->remember(10)->get(); 
        foreach ($job as $jobs)
        {
             $jobarray[]=array('key'=> $key++, 'title' => $jobs->Name );
        }
        $tree[]=array('key'         => $lastkey, 
                      'title'       => 'Jobs', 
                      'folder'      => 'true',
                      'children'    => $jobarray
                      );          
        $lastkey=$key;

        /* Fill Up Filesets Name on Tree */
        $fileset = CfgFileset::orderBy('Name')->remember(10)->get(); 
        foreach ($fileset as $filesets)
        {
             $filesetarray[]=array('key'=> $key++, 'title' => $filesets->Name );
        }
        $tree[]=array('key'         => $lastkey, 
                      'title'       => 'Filesets', 
                      'folder'      => 'true',
                      'children'    => $filesetarray
                      );          
        
        $lastkey=$key;


        /* Fill Up Filesets Name on Tree */
        $schedule = CfgSchedule::orderBy('Name')->remember(10)->get(); 
        foreach ($schedule as $schedules)
        {
             $schedulearray[]=array('key'=> $key++, 'title' => $schedules->Name );
        }
        $tree[]=array('key'         => $lastkey, 
                      'title'       => 'Schedules', 
                      'folder'      => 'true',
                      'children'    => $schedulearray
                      );          
        
        $lastkey=$key;


        /* Fill Up Pools Name on Tree */
        $pool = CfgPool::orderBy('Name')->remember(10)->get(); 
        foreach ($pool as $pools)
        {
             $poolarray[]=array('key'=> $key++, 'title' => $pools->Name );
        }
        $tree[]=array('key'         => $lastkey, 
                      'title'       => 'Pools', 
                      'folder'      => 'true',
                      'children'    => $poolarray
                      );          
        
        $lastkey=$key;
            
        /* Fill Up Catalogs Name on Tree */
        $catalog = CfgCatalog::orderBy('Name')->remember(10)->get(); 
        foreach ($catalog as $catalogs)
        {
             $catalogarray[]=array('key'=> $key++, 'title' => $catalogs->Name );
        }
        $tree[]=array('key'         => $lastkey, 
                      'title'       => 'Catalogs', 
                      'folder'      => 'true',
                      'children'    => $catalogarray
                      );          
        
        $lastkey=$key;
        return Response::json ($tree);

    }
  

        






    /**
     * Read Bacula Configuration Files
     * @return View
     */
    public function readbacula()
    {
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
                
             //   LOG::info($filename);           

                          
                
                /////////////////////////////////////Codigo para Ler os Schedule////////////////////////
                $i=0;
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

                    if (trim($value)=="Job {") {

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
