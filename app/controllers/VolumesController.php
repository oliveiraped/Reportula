<?php

namespace app\controllers;
use BaseController, Form, Input, Redirect;
use Sentry, View, Log, Cache, Config, DB, Request;
use Date, App, Former, Datatables, Asset, Time;

// Models
use app\models\Media;
use app\models\Job;

class VolumesController extends BaseController
{
    public $volumeSelectBox = array();

    public function __construct()
    {
        parent::__construct();

        Asset::add('select2.css', 'assets/css/select2.css');
        Asset::add('select2.min.js', 'assets/js/select2.min.js');

        /* Html Exports Tables */
        Asset::add('bootstrap-dropdown.js', 'assets/js/bootstrap-dropdown.js');
        Asset::add('tableExport.js', 'assets/js/tableExport.js');
        Asset::add('jquery.base64.js', 'assets/js/jquery.base64.js');
        Asset::add('html2canvas.js', 'assets/js/html2canvas.js');
        Asset::add('sprintf.js', 'assets/js/sprintf.js');
        Asset::add('jspdf.js', 'assets/js/jspdf.js');
        Asset::add('base64.js', 'assets/js/base64.js');

        Asset::add('volumes', 'assets/js/volumes.js');

        /* Fill Up the Select Box */
        $volumesall = Media::select(array('mediaid','volumename'))
                ->groupby('volumename')->groupby('mediaid')->orderby('volumename','asc')->get()->toArray();
        $volumeName = array_fetch ($volumesall, 'volumename') ;
        $volumeId   = array_fetch ($volumesall, 'mediaid') ;
        $this->volumeSelectBox  = array_combine ($volumeId, $volumeName);

    }

    public function volumes($volumes=null)
    {
        $volumeSelected = Input::get('Volume', $volumes);
        $media = Media::where('mediaid', '=', $volumeSelected )->first();
        if ($media == Null) {
            $slot="";
            $pool="";
            $firstwritten="";
            $lastwritten="";
            $labeldate="";
            $voljobs="";
            $volfiles="";
            $labeldate="";
            $volretention="";
            $volbytes="";
            $volstatus="";
            $volumeSelected="";
        } else {
            $slot=$media->slot;
            $pool=$media->pool;
            $firstwritten=$media->firstwritten;
            $lastwritten=$media->lastwritten;
            $labeldate=$media->labeldate;
            $voljobs=$media->voljobs;
            $volfiles=$media->volfiles;
            $labeldate=$media->labeldate;

            $to =Date::now();
            $text=" Days";

            /* 86400  -> equal to seconds in i day*/
             $volretention = ($media->volretention/86400).$text;

            if ($volretention >= 365) {
                $type = ' Year';
                $volretention =intval($volretention/31536000).$type;
            }

            $volbytes=$this->byte_format($media->VolBytes);
            $volstatus=$media->volstatus;
        }

        return View::make('volumes',array(
                                    'slot'          => $slot,
                                    'volume'        => $volumeSelected,
                                    'firstwritten'  => $firstwritten,
                                    'lastwritten'   => $lastwritten,
                                    'labeldate'     => $labeldate,
                                    'voljobs'       => $voljobs,
                                    'volfiles'      => $volfiles,
                                    'labeldate'     => $labeldate,
                                    'volretention'  => $volretention,
                                    'volbytes'      => $volbytes,
                                    'volstatus'     => $volstatus,
                                    'volumeSelectBox'=>  $this->volumeSelectBox
                         ));

    }

    /*Gets Data from the Volumes */
    public function getvolumes()
    {

      $tjobs = Job::select(array($this->tables['job'].'.jobid','name','starttime','endtime',
                                   'level','jobbytes','jobfiles','jobstatus'))
                  ->join($this->tables['jobmedia'],$this->tables['jobmedia'].'.jobid', '=', $this->tables['job'].'.jobid')
                  ->join($this->tables['media'],$this->tables['media'].'.mediaid', '=', $this->tables['jobmedia'].'.mediaid')
                  ->where($this->tables['media'].'.mediaid','=', Input::get('Volume'))
                  ->groupby($this->tables['job'].'.jobid')
                  ->groupby('starttime')
                  ->groupby('endtime')
                  ->groupby('level')
                  ->groupby('jobbytes')
                  ->groupby('jobfiles')
                  ->groupby('jobstatus')
                  ->groupby('name')
                  ->groupby('volumename');

        return Datatables::of($tjobs)
                          ->edit_column('name', '{{ link_to_route("jobs", $name ,array("Job" => $jobid)) }} ')
                          ->edit_column('jobid', '{{ link_to_route("jobs", $jobid ,array("Job" => $jobid)) }} ')
                          ->make();
    }

}
