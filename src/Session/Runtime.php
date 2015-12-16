<?php

namespace Abimo\Session;

class Runtime implements SessionInterface
{
    public function __construct(\Abimo\Config $config)
    {
        $this->config = $config;
    }

    public function save($data)
    {
        //
    }

    public function load($key)
    {
        // TODO: Implement load() method.
    }
}