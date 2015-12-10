<?php

namespace Abimo\Session;
use Abimo\Config;

class Database implements SessionInterface
{
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    public function save($key, $data)
    {
        $db = new \Abimo\Database($this->config);
    }
}