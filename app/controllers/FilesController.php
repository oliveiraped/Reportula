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
    //    Asset::add('jstree', 'assets/css/jstree.css');
    //    Asset::add('jstree', 'assets/js/jstree.js');

    }

  /*  public function recursion($multi_dimensional_array)
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
    }*/

    public function files($job)
    {
      
       $find = Filessearch::where('jobid', '=', $job)->get();
       $find =  $find->toArray();

       if (empty($find)) {
            $filessearch = new Filessearch;

            $files = Files::select(array('path.path','filename.name as filename','jobid'))
                  ->join('filename','file.filenameid', '=', 'filename.filenameid')
                  ->join('path','file.pathid', '=', 'path.pathid')
                  ->where('jobid','=', $job)->get();
            $files = $files->toArray();
           $t= Filessearch::insert($files);
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
