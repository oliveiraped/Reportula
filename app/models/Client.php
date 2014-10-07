<?php

namespace app\models;

use Eloquent,Config;
use vd\vd, Log;

class Client extends Eloquent
{
    protected $guarded = array('id');
    protected $table =  'Client';
    public $timestamps = false;

    function __construct() {
      if ( Config::get('database.default')=='pgsql' ) {
          $this->table = strtolower($this->table);
      }
    }

    /* Fill Select Box */
    public static function clientSelectBox($clientsall=null)
    {
        if ($clientsall==null) {
            $clientsall=Client::all()->toArray();
        }

         // Code to resolve pgsql names
        $f_Name = 'Name';
        $f_ClientID = 'ClientId';

        if ( Config::get('database.default')=='pgsql' ) {
            $f_Name = strtolower($f_Name);
            $f_ClientID = strtolower($f_ClientID);
        }


        //Both Engines :
        $clientsName = array_fetch ($clientsall, $f_Name) ;
        $clientsId = array_fetch ($clientsall, $f_ClientID) ;


        $clientSelectBox  =  (array_combine ($clientsId, $clientsName));
        natsort ($clientSelectBox);

        return $clientSelectBox ;
    }

}
