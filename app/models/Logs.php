<?php

namespace app\models;

use Eloquent, Config;

class Logs extends Eloquent
{
    public $primaryKey = 'logid';
    protected $table   =  'Log';

    function __construct() {
      if ( Config::get('database.default')=='pgsql' ) {
          $this->table = strtolower($this->table);
      }
    }

    public $timestamps = false;

}
