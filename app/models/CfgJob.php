<?php

namespace app\models;

use Eloquent;
use app\models\BaseModel;

class CfgJob extends BaseModel
{

    protected $guarded = array('id');
    public $key = 'id';
    protected $table = 'cfgjob';
    public $timestamps = false;
}
