<?php

namespace Abimo\Session;

class Runtime implements SessionInterface
{
    public function __construct(\Abimo\Config $config, \Abimo\Cookie $cookie)
    {
        $this->config = $config;
    }

    public function save($data)
    {
        // TODO: Implement save() method.
    }

    public function load($key)
    {
        // TODO: Implement load() method.
    }
}