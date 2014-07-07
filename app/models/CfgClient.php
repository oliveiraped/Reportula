<?php

namespace app\models;

use Eloquent;

class CfgClient extends Eloquent
{
	protected $guarded = array('id');
    protected $table = 'cfgclient';
    public $timestamps = false;
    public $key = 'id';
   
}
