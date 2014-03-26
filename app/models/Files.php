<?php

namespace app\models;

use Eloquent;

class Files extends Eloquent
{
    public $primaryKey = 'fileid';
    protected $table = 'file';
    public $timestamps = false;

}
