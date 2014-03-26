<?php namespace Orchestra\Support;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\MessageBag as M;

class Messages extends M
{
    /**
     * Cached messages to be extends to current request.
     *
     * @var self
     */
    protected $instance = null;

    /**
     * Retrieve Message instance from Session, the data should be in
     * serialize, so we need to unserialize it first.
     *
     * @return Messages
     */
    public function retrieve()
    {
        $messages = null;

        if (is_null($this->instance)) {
            $this->instance = new static();

            if (Session::has('message')) {
                $messages = @unserialize(Session::get('message', ''));
            }

            Session::forget('message');

            if (is_array($messages)) {
                $this->instance->merge($messages);
            }
        }

        return $this->instance;
    }

    /**
     * Extend Messages instance from session.
     *
     * @param  \Closure $callback
     * @return void
     */
    public function extend(Closure $callback)
    {
        $instance = $this->retrieve();
        call_user_func($callback, $instance);
    }

    /**
     * Store current instance.
     *
     * @return void
     */
    public function save()
    {
        Session::flash('message', $this->serialize());
    }

    /**
     * Compile the instance into serialize.
     *
     * @return string   serialize of this instance
     */
    public function serialize()
    {
        return serialize($this->messages);
    }
}
