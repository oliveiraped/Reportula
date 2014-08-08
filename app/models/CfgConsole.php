<?php

namespace app\models;

use Eloquent;
use app\models\BaseModel;

class CfgConsole extends BaseModel
{
	protected $guarded = array('id');
    protected $table = 'cfgconsole';
    public $timestamps = false;
    public $key = 'id';

}
