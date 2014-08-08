<?php

namespace app\models;


use Eloquent;
use app\models\BaseModel;

class CfgDirector extends BaseModel
{
	 protected $guarded = array('id');
   public $key = 'id';
   protected $table = 'cfgdirector';
   public $timestamps = false;



}
