<?php

namespace app\models;

use Eloquent;

class Emails extends Eloquent
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'emails';
    public $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = array('id');

}
