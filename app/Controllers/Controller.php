<?php

namespace App\Controllers;

use DI\Container;
use App\Authorisation\Exceptions\GateAuthorisationException;

abstract class Controller
{
    /**
     * The container instance.
     *
     * @var \Interop\Container\ContainerInterface
     */
    protected $c;

    /**
     * Set up controllers to have access to the container.
     *
     * @param \Interop\Container\ContainerInterface $container
     */
    public function __construct(Container $container)
    {
        $this->c = $container;
    }

    /**
     * Undocumented function
     *
     * @param [type] $abilities
     * @param array $arguments
     * @return void
     */
    public function authorise($abilities, $arguments = [])
    {
        if (!$this->c->get('gate')->all($abilities, $arguments)) {
            throw new GateAuthorisationException();
        }
    }
}
