<?php

namespace app\models;

use Eloquent;
use app\models\BaseModel;

class Cfgfilesetexclude extends BaseModel
{
	protected $guarded = array('id');
    protected $table = 'cfgfilesetexclude';
    public $timestamps = false;
    public $key = 'id';

}
