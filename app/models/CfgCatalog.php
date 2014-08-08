<?php

namespace app\models;

use Eloquent;
use app\models\BaseModel;

class CfgCatalog extends BaseModel
{
	protected $guarded = array('id');
    protected $table = 'cfgcatalog';
    public $timestamps = false;
    public $key = 'id';

}
