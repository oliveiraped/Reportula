<?php namespace Orchestra\Support;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator as V;
use Illuminate\Support\Fluent;

abstract class Validator
{
    /**
     * List of rules.
     *
     * @var array
     */
    protected $rules = array();

    /**
     * List of events.
     *
     * @var array
     */
    protected $events = array();

    /**
     * List of bindings.
     *
     * @var array
     */
    protected $bindings = array();

    /**
     * Current scope.
     *
     * @var string
     */
    protected $scope = null;

    /**
     * Create a new instance.
     */
    public function __construct()
    {
        $this->setRules($this->rules);
    }

    /**
     * Create a scope scenario.
     *
     * @param  string   $scenario
     * @param  array    $parameters
     * @return Validator
     */
    public function on($scenario, $parameters = array())
    {
        $this->scope = $scenario;

        $method = 'on'.ucfirst($scenario);

        if (method_exists($this, $method)) {
            call_user_func_array(array($this, $method), $parameters);
        }

        return $this;
    }

    /**
     * Add bindings.
     *
     * @param  array    $bindings
     * @return Validator
     */
    public function bind($bindings)
    {
        $this->bindings = array_merge($this->bindings, $bindings);

        return $this;
    }

    /**
     * Execute validation service.
     *
     * @param  array    $input
     * @param  string   $event
     * @return \Illuminate\Validation\Factory
     */
    public function with($input, $events = array())
    {
        $rules      = $this->runValidationEvents($events);
        $validation = V::make($input, $rules);

        if (is_null($this->scope)) {
            return $validation;
        }

        $method = 'extend'.ucfirst($this->scope);

        if (method_exists($this, $method)) {
            call_user_func(array($this, $method), $validation);
        }

        return $validation;
    }

    /**
     * Run rules bindings.
     *
     * @return array
     */
    protected function getBindedRules()
    {
        $rules    = $this->rules;
        $bindings = $this->prepareBindings('{', '}');

        $filter = function ($value) use ($bindings) {
            return strtr($value, $bindings);
        };

        foreach ($rules as $key => $value) {
            if (is_array($value)) {
                $value = array_map($filter, $value);
            } else {
                $value = strtr($value, $bindings);
            }

            $rules[$key] = $value;
        }

        return $rules;
    }

    /**
     * Prepare strtr() bindings.
     *
     * @param  string   $prefix
     * @param  string   $suffix
     * @return array
     */
    protected function prepareBindings($prefix = '{', $suffix = '}')
    {
        $bindings = $this->bindings;

        foreach ($bindings as $key => $value) {
            $bindings["{$prefix}{$key}{$suffix}"] = $value;
        }

        return $bindings;
    }

    /**
     * Run validation events and return the finalize rules.
     *
     * @param  array    $events
     * @return array
     */
    protected function runValidationEvents($events)
    {
        // Merge all the events.
        $events = array_merge($this->events, (array) $events);

        // Convert rules array to Fluent, in order to pass it by references
        // in all event listening to this validation.
        $rules  = new Fluent($this->getBindedRules());

        foreach ((array) $events as $event) {
            Event::fire($event, array(& $rules));
        }

        return $rules->getAttributes();
    }

    /**
     * Set validation rules, this would override all previously defined
     * rules.
     *
     * @return array
     */
    public function setRules($rules = array())
    {
        return $this->rules = $rules;
    }
}
