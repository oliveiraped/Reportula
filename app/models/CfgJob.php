<?php

namespace app\models;

use Eloquent;

class CfgJob extends Eloquent
{

    protected $guarded = array('id');
    public $key = 'id';
    protected $table = 'cfgjob';
    public $timestamps = false;
}
