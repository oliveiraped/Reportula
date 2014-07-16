<?php

namespace app\models;

use Eloquent;

class CfgConsole extends Eloquent
{
	protected $guarded = array('id');
    protected $table = 'cfgconsole';
    public $timestamps = false;
    public $key = 'id';
   
}
