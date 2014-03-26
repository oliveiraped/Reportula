<?php

namespace app\models;

use Eloquent;

class CfgStorage extends Eloquent
{
	protected $guarded = array('id');
	public $key = 'id';
    protected $table = 'cfgstorage';
    public $timestamps = false;
}
