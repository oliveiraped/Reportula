<?php

namespace app\models;

use Eloquent;

class Cfgfilesetexcludeoptions extends Eloquent
{	
		protected $guarded = array('id');

    public $key = 'id';
    protected $table = 'cfgfilesetexcludeoptions';
    public $timestamps = false;
}
