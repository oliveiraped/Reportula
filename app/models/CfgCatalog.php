<?php

namespace app\models;

use Eloquent;

class CfgCatalog extends Eloquent
{
	protected $guarded = array('id');
    protected $table = 'cfgcatalog';
    public $timestamps = false;
    public $key = 'id';
   
}
