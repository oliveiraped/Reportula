<?php

namespace app\models;

use Eloquent;
use app\models\BaseModel;

class CfgClient extends BaseModel
{
	protected $guarded = array('id');
    protected $table = 'cfgclient';
    public $timestamps = false;
    public $key = 'id';

}
