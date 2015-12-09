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
        $container = new \Dice\Dice();

//        $ruleShare = array('share' => true);
//        $container->addRule('\Abimo\Database\Mysql', $ruleShare);
//        $container->addRule('\Abimo\Database\Pdo', $ruleShare);
//        $container->addRule('\Abimo\Database\Sqlite', $ruleShare);
//        $container->addRule('\Abimo\Session\Database', $ruleShare);
//        $container->addRule('\Abimo\Session\Memcached', $ruleShare);
//        $container->addRule('\Abimo\Session\Redis', $ruleShare);
//
//        $this->container = $container;

        $throwable = $container->create('\Abimo\Throwable');
        $throwable->register();

        $router = $container->create('\Abimo\Router');
        $router->match()->run();
    }
}
