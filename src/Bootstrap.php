<?php

namespace Abimo;

class Bootstrap
{
    /**
     * Bootstrap constructor.
     */
    public function __construct()
    {
        //TODO - level-2/dice was used before as DIC
        $factory = new Factory();
        $factory->throwable()->register();
        $factory->router()->match()->run();
    }
}
