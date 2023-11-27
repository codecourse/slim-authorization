<?php

namespace App\Authorisation;

class Gate
{
    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $abilities = [];

    /**
     * Undocumented variable
     *
     * @var array
     */
    protected $beforeCallbacks = [];

    /**
     * Undocumented variable
     *
     * @var [type]
     */
    protected $userResolver;

    /**
     * Undocumented function
     *
     * @param [type] $userResolver
     */
    public function __construct($userResolver)
    {
        $this->userResolver = $userResolver;
    }

    /**
     * Undocumented function
     *
     * @param [type] $ability
     * @param Callable $callback
     * @return void
     */
    public function define($ability, Callable $callback)
    {
        $this->abilities[$ability] = $callback;
    }

    /**
     * Undocumented function
     *
     * @param Callable $callback
     * @return void
     */
    public function before(Callable $callback)
    {
        $this->beforeCallbacks[] = $callback;
    }

    /**
     * Undocumented function
     *
     * @param [type] $ability
     * @param array $arguments
     * @return void
     */
    public function allows($ability, $arguments = [])
    {
        return $this->check($ability, $arguments);
    }

    /**
     * Undocumented function
     *
     * @param [type] $ability
     * @param array $arguments
     * @return void
     */
    public function denies($ability, $arguments = [])
    {
        return !$this->check($ability, $arguments);
    }

    /**
     * Undocumented function
     *
     * @param [type] $abilities
     * @param array $arguments
     * @return void
     */
    public function any($abilities, $arguments = [])
    {
        foreach ($abilities as $ability) {
            if ($this->check($ability, $arguments)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Undocumented function
     *
     * @param [type] $abilities
     * @param array $arguments
     * @return void
     */
    public function all($abilities, $arguments = [])
    {
        return $this->check($abilities, $arguments);
    }

    /**
     * Undocumented function
     *
     * @param [type] $abilities
     * @param [type] $arguments
     * @return void
     */
    protected function check($abilities, $arguments)
    {
        foreach ((array) $abilities as $ability) {
            if (!$this->inspect($ability, $arguments)) {
                return false;
            }
        }

        return true;
    }

    /**
     * Undocumented function
     *
     * @param [type] $ability
     * @param [type] $arguments
     * @return void
     */
    protected function inspect($ability, $arguments)
    {
        foreach ($this->beforeCallbacks as $beforeCallback) {
            if ($beforeCallback($this->resolveUser(), $ability)) {
                return true;
            }
        }

        return $this->callAuthCallback(
            $this->resolveUser(),
            $ability,
            is_array($arguments) ? $arguments : [$arguments]
        );
    }

    /**
     * Undocumented function
     *
     * @param [type] $user
     * @param [type] $ability
     * @param [type] $arguments
     * @return void
     */
    protected function callAuthCallback($user, $ability, $arguments)
    {
        return $this->abilities[$ability]($user, ...$arguments);
    }

    /**
     * Undocumented function
     *
     * @return void
     */
    protected function resolveUser()
    {
        return call_user_func($this->userResolver);
    }
}
