<?php

namespace app\models;

use Eloquent, Config;

class Pool extends Eloquent
{
    public $primaryKey = 'poolid';
    protected $table =  'Pool';

    function __construct() {
      if ( Config::get('database.default')=='pgsql' ) {
          $this->table = strtolower($this->table);
      }
    }

    public $timestamps = false;

}
