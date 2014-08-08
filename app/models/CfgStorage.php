<?php

namespace app\models;

use Eloquent;
use app\models\BaseModel;

class CfgStorage extends BaseModel
{
	protected $guarded = array('id');
	public $key = 'id';
    protected $table = 'cfgstorage';
    public $timestamps = false;
}
