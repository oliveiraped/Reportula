<?php namespace Orchestra\Support\Facades;

use Illuminate\Support\Facades\Facade;

class Asset extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'orchestra.asset';
    }
}
