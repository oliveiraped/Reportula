<?php

namespace app\models;

use Eloquent;

class CfgMessage extends Eloquent
{
	protected $guarded = array('id');
    protected $table = 'cfgmessages';
    public $timestamps = false;
    public $key = 'id';
   
}
