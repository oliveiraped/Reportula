<?php namespace Orchestra\Support;

use InvalidArgumentException;

abstract class Manager extends \Illuminate\Support\Manager
{
    /**
     * Define blacklisted character in name.
     *
     * @var array
     */
    protected $blacklisted = array('.');

    /**
     * Create a new instance.
     *
     * @param  string   $driver
     * @return object
     * @see    Manager::driver()
     */
    public function make($driver = null)
    {
        return $this->driver($driver);
    }

    /**
     * Create a new driver instance.
     *
     * @param  string  $driverName
     * @return object
     */
    protected function createDriver($driverName)
    {
        list($driver, $name) = $this->getDriverName($driverName);

        $method = 'create'.Str::studly($driver).'Driver';

        // We'll check to see if a creator method exists for the given driver.
        // If not we will check for a custom driver creator, which allows
        // developers to create drivers using their own customized driver
        // creator Closure to create it.
        if (isset($this->customCreators[$driver])) {
            return $this->callCustomCreator($driverName);
        } elseif (method_exists($this, $method)) {
            return call_user_func(array($this, $method), $name);
        }

        throw new InvalidArgumentException("Driver [$driver] not supported.");
    }

    /**
     * Call a custom driver creator.
     *
     * @param  string  $driverName
     * @return object
     */
    protected function callCustomCreator($driverName)
    {
        list($driver, $name) = $this->getDriverName($driverName);

        return call_user_func($this->customCreators[$driver], $this->app, $name);
    }

    /**
     * Get driver name.
     *
     * @param  string   $driverName
     * @return array
     */
    protected function getDriverName($driverName)
    {
        if (false === strpos($driverName, '.')) {
            $driverName = "{$driverName}.default";
        }

        list($driver, $name) = explode('.', $driverName, 2);

        $this->checkNameIsNotBlacklisted($name);

        return array($driver, $name);
    }

    /**
     * Check if name is not blacklisted.
     *
     * @param  string   $name
     * @return void
     * @throws \InvalidArgumentException
     */
    protected function checkNameIsNotBlacklisted($name)
    {
        foreach ($this->blacklisted as $character) {
            if (Str::contains($name, $character)) {
                throw new InvalidArgumentException("Invalid character in driver name [{$name}].");
            }
        }
    }
}
