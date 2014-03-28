<?php

namespace app\models;

use Eloquent;

class CfgDirector extends Eloquent
{
	protected $guarded = array('id');
    public $key = 'id';
    protected $table = 'cfgdirector';
    public $timestamps = false;
}
