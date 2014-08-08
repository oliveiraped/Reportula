<?php

namespace app\models;

use Eloquent;
use app\models\BaseModel;

class Cfgfilesetexcludeoptions extends BaseModel
{
		protected $guarded = array('id');

    public $key = 'id';
    protected $table = 'cfgfilesetexcludeoptions';
    public $timestamps = false;
}
