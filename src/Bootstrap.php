<?php

namespace Abimo;

class Bootstrap
{
    public $container;

    /**
     * Create a new bootstrap instance.
     */
    public function __construct()
    {
        //TODO - level-2/dice was used before as DIC
        $config = new Config();
        $request = new Request();
        $router = new Router($config, $request);
        $template = new Template();
        $throwable = new Throwable($config, $router, $template);

        $throwable->register();

        $router->match()->run();
    }
}
