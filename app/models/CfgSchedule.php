<?php

namespace app\models;

use Eloquent;

class CfgSchedule extends Eloquent
{
	protected $guarded = array('id');
    protected $table = 'cfgschedule';
    public $timestamps = false;
    public $key = 'id';
   
}
