<?php

namespace app\models;

use Eloquent;

class Cfgfilesetexclude extends Eloquent
{
	protected $guarded = array('id');
    protected $table = 'cfgfilesetexclude';
    public $timestamps = false;
}
