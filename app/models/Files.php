<?php

namespace app\models;

use Eloquent, Config;

class Files extends Eloquent
{
    public $primaryKey = 'fileid';
    protected $table = 'File';
    public $timestamps = false;

    function __construct() {
      if ( Config::get('database.default')=='pgsql' ) {
          $this->table = strtolower($this->table);
      }
    }

}
