<?php

namespace app\models;

use Eloquent;

class Cfgfilesetincludeoptions extends Eloquent
{	
		protected $guarded = array('id');

    public $key = 'id';
    protected $table = 'cfgfilesetincludeoptions';
    public $timestamps = false;
}
