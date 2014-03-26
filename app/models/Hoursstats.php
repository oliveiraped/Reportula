<?php

namespace app\models;

use Eloquent;

class Hoursstats extends Eloquent
{
	protected $guarded = array('id');
    protected $table =  'hoursstats';
    public $timestamps = false;

}
