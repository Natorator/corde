<?php

namespace Abimo\Session;

class Database implements SessionInterface
{
    public $config;

    public function __construct(\Abimo\Config $config)
    {
        $this->config = $config;
    }

    public function save($data)
    {
        $db = new \Abimo\Database($this->config);
    }

    public function load($key)
    {
        // TODO: Implement load() method.
    }
}