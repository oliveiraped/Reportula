<?php

namespace app\models;

use Eloquent;
use app\models\BaseModel;


class CfgFileset extends BaseModel
{
	protected $guarded = array('id');
	protected $table = 'cfgfileset';
    public $timestamps = false;


    public function cfgfilesetinclude()
    {
        return $this->hasMany('app\models\Cfgfilesetinclude','idfileset','id');
    }

    public function cfgfilesetexclude()
    {
        return $this->hasMany('app\models\Cfgfilesetexclude','idfileset','id');
    }

    public function cfgfilesetincludeoptions()
    {
        return $this->hasMany('app\models\Cfgfilesetincludeoptions','idfileset','id');
    }

     public function cfgfilesetexcludeoptions()
    {
        return $this->hasMany('app\models\Cfgfilesetexcludeoptions','idfileset','id');
    }



}
