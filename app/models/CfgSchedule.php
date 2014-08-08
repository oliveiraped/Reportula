<?php

namespace app\models;

use Eloquent;
use app\models\BaseModel;

class CfgSchedule extends BaseModel
{
	protected $guarded = array('id');
    protected $table = 'cfgschedule';
    public $timestamps = false;
    public $key = 'id';

}
