<?php

namespace app\models;

use Eloquent;

class CfgPool extends Eloquent
{
	protected $guarded = array('id');
	public $key = 'id';
	protected $table = 'cfgpool';
    public $timestamps = false;
}
