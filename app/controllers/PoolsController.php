<?php

namespace app\controllers;
use BaseController, Form, Input, Redirect;
use Sentry, View, Log, Cache, Config, DB, Url;
use Date, App, Former, Datatables, Asset, Time;

// Models
use app\models\Media;
use app\models\Pool;

class PoolsController extends BaseController
{
    public $poolSelectBox = array();

    public function __construct()
    {
        parent::__construct();

        Asset::add('select2', 'assets/css/select2.css');
        Asset::container('footer')->add('momentmin', 'assets/js/moment.min.js');
        Asset::container('footer')->add('select2min', 'assets/js/select2.min.js');
        Asset::container('footer')->add('pools.js', 'assets/js/pools.js');

        /* Fill Up the Select Box */
        $poolall = Pool::select(array('poolid','name'))->orderBy('name', 'asc')->get()->toArray();
        $poolName = array_fetch ($poolall, 'name') ;
        $poolId   = array_fetch ($poolall, 'poolid') ;
        $this->poolSelectBox  = array_combine ($poolId, $poolName);
    }

    public function pools($pool=null)
    {
        $poolselected = Input::get('Pool',$pool);
        $pool = Pool::find($poolselected);

        if ($pool == Null) {
            $name="";
            $volretension="";
            $recycle="";
            $autoprune="";
            $pooltype="";
        } else {
            $name=$pool->uname;

            $to =Date::now();
            $text=" Days";
           

            /* 86400  -> equal to seconds in i day*/ 
            $volretension = ($pool->volretention/86400).$text;

            if ($volretension >= 365) {
                $type = ' Year';
                $volretension =intval($pool->volretention/31536000).$type;
            }

            $recycle= $pool->recycle;
            $autoprune=$pool->autoprune;
            $pooltype=$pool->pooltype;
        }

        /* Get Stored bytes pool */
        $tjobs = Media::where('poolid','=', $poolselected)->sum('volbytes');

        return View::make('pools',array(
                                    'name'          => $name,
                                    'volretension'  => $volretension,
                                    'recycle'       => $recycle,
                                    'pooltype'      => $pooltype,
                                    'autoprune'     => $autoprune,
                                    'poolSelectBox' => $this->poolSelectBox
                                )
                         );

    }

    /*Gets Data from the Jobs */

    public function getpools()
    {
        $pool = Input::get('Pool', "");

        $volumes = Media::select(array('mediaid','volumename','slot','mediatype','lastwritten',
                                  'voljobs','volfiles','volbytes','volretention','volstatus'))
                  ->where('poolid','=', $pool);

        return  Datatables::of($volumes)
                    ->edit_column('volretention','{{ date("d", $volretention)." Days" }}')
                    ->edit_column('volumename', '{{ link_to_route("volumes", $volumename ,array("Volume" => $mediaid)) }} ')
                    ->remove_column('mediaid')
                    ->make();
        }

}
