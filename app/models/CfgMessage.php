<?php

namespace app\models;

use Eloquent;
use app\models\BaseModel;

class CfgMessage extends BaseModel
{
	protected $guarded = array('id');
    protected $table = 'cfgmessages';
    public $timestamps = false;
    public $key = 'id';

}
