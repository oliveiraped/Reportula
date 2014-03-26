<?php

namespace app\models;

use Eloquent;

class Daystats extends Eloquent
{
	protected $guarded = array('id');
    protected $table =  'daystats';
    public $timestamps = false;

}
