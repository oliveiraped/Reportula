<?php

namespace app\models;

use Eloquent;

class Logs extends Eloquent
{
    public $primaryKey = 'logid';
    protected $table   =  'log';
    public $timestamps = false;

}
