<?php

namespace app\models;

use Eloquent;
use app\models\BaseModel;

class CfgSchedulerun extends BaseModel
{
	 protected $guarded = array('id');
    protected $table = 'cfgschedulerun';
    public $timestamps = false;
    public $key = 'id';

}
