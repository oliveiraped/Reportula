<?php

namespace app\models;

use Eloquent, Config;

class Media extends Eloquent
{
     protected $table =  'Media';
     public $timestamps = false;

    function __construct() {
      if ( Config::get('database.default')=='pgsql' ) {
          $this->table = strtolower($this->table);
      }
    }

}
