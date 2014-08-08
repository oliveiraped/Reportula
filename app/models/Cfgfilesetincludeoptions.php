<?php

namespace app\models;

use Eloquent;
use app\models\BaseModel;

class Cfgfilesetincludeoptions extends BaseModel
{
		protected $guarded = array('id');

    public $key = 'id';
    protected $table = 'cfgfilesetincludeoptions';
    public $timestamps = false;
}
