<?php

namespace app\models;

use Eloquent;

class CfgFileset extends Eloquent
{
	protected $guarded = array('id');
	protected $table = 'cfgfileset';
    public $timestamps = false;
}
