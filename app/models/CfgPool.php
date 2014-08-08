<?php

namespace app\models;

use Eloquent;
use app\models\BaseModel;

class CfgPool extends BaseModel
{
	protected $guarded = array('id');
	public $key = 'id';
	protected $table = 'cfgpool';
    public $timestamps = false;
}
