<?php

namespace app\models;

use Eloquent;

class Pool extends Eloquent
{
    public $primaryKey = 'poolid';
    protected $table =  'pool';
    public $timestamps = false;

}
