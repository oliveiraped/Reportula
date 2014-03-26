<?php namespace Orchestra\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Facile extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'orchestra.facile';
    }
}
