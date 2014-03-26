<?php namespace Orchestra\Support\Ftp;

class RuntimeException extends \RuntimeException
{

    /**
     * Parameters.
     *
     * @var array
     */
    protected $parameters = array();

    /**
     * Construct a new exception.
     *
     * @param  string   $exception
     * @param  array    $parameters
     * @return void
     */
    public function __construct($exception, array $parameters = array())
    {
        $this->parameters = $parameters;
        parent::__construct($exception);
    }

    /**
     * Get exceptions parameters.
     *
     * @return array
     */
    public function getParameters()
    {
        return $this->parameters;
    }
}
