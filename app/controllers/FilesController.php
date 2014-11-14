<?php

namespace app\controllers;
use BaseController, Form, Input, Redirect;
use Sentry, View, Log, Cache, Config, DB;
use Date, App, Former, Datatables, Asset,Vd\Vd;

// Models
use app\models\Logs;
use app\models\Files;
use app\models\Filessearch;

class FilesController extends BaseController
{
    public $jobSelectBox = array();

    public function __construct()
    {
        parent::__construct();
        Asset::add('files.js', 'assets/js/files.js');

        /* Html Exports Tables */
        Asset::add('bootstrap-dropdown.js', 'assets/js/bootstrap-dropdown.js');
        Asset::add('tableExport.js', 'assets/js/tableExport.js');
        Asset::add('jquery.base64.js', 'assets/js/jquery.base64.js');
        Asset::add('html2canvas.js', 'assets/js/html2canvas.js');
        Asset::add('sprintf.js', 'assets/js/sprintf.js');
        Asset::add('jspdf.js', 'assets/js/jspdf.js');
        Asset::add('base64.js', 'assets/js/base64.js');


    }

    public function files($job)
    {

       $find = Filessearch::where('jobid', '=', $job)->get();
       $find =  $find->toArray();

       if (empty($find)) {
            $filessearch = new Filessearch;

            $files = Files::select(array($this->tables['path'].'.path', $this->tables['filename'].'.name as filename','jobid'))
                  ->join($this->tables['filename'],$this->tables['file'].'.filenameid', '=', $this->tables['filename'].'.filenameid')
                  ->join($this->tables['path'],$this->tables['file'].'.pathid', '=', $this->tables['path'].'.pathid')
                  ->where('jobid','=', $job)->remember(10)->get();

            $files = $files->toArray();
            if (!empty($files)) {
                $t= Filessearch::insert($files);
            }
        }

        /* Mostra o log do Job */
        $logs = Logs::select(array('logtext'))->where('jobid','=', $job)->get();
        $logs2="";
        foreach ($logs as $log) {
             $logs2[]=preg_replace("/[\t\n]+/", '</br>', $log->logtext);
        }
        //////

        $files= Filessearch::select(array('path','filename'))
                  ->where('jobid','=', $job )
                  ->orderBy('path','asc');

        $files=$files->get();//->toArray();

        //$files="";

        if (empty($files)) {
            foreach ($files as $file) {
                $ficheiro[$file->path.$file->name]='';
            }
            $tree = $this->explodeTree($ficheiro, "/");
            $tree = $this->recursion($tree);

        }else{
            $tree="";
        }


       // $menu = $this->recursion($tree);
          return View::make('files',array(
                                    'jobid' => $job,
                                    'logs'  => implode($logs2),
                                    'menu'  => $menu =$tree
                                )
                         );
    }

    // Ajax Files Table
    public function getfiles()
    {
        $files = Filessearch::select(array('path','filename'))
                  ->where('jobid','=', Input::get('jobid'));

        return Datatables::of($files)->make();
    }

}
