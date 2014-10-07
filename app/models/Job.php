<?php

namespace app\models;

use Eloquent,Config;

class Job extends Eloquent
{

    protected $table =  'Job';
    public $timestamps = false;


    function __construct() {
      if ( Config::get('database.default')=='pgsql' ) {
          $this->table = strtolower($this->table);
      }
    }



    /* Fill Select Box */
    public static function jobSelectBox($jobsall=null)
    {

         // Code to resolve pgsql names
        $f_Name = 'Name';
        $f_JobID = 'JobId';

        if ( Config::get('database.default')=='pgsql' ) {
            $f_Name = strtolower($f_Name);
            $f_JobID = strtolower($f_JobID );
        }


        if ($jobsall==null) {
           $jobsall = Job::select( $f_JobID,$f_Name)->distinct($f_Name)
                ->orderBy($f_Name, 'asc')->get()->toArray();
        }


        $jobsName = array_fetch ($jobsall, $f_Name );
        $jobsId = array_fetch ($jobsall,  $f_JobID );

        $jobsSelectBox  =  array_unique( array_combine ($jobsId, $jobsName));

        natsort ($jobsSelectBox);

        return $jobsSelectBox ;
    }

}
