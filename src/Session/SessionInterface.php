<?php

namespace Abimo\Session;

interface SessionInterface
{
    public function __construct(\Abimo\Config $config, \Abimo\Cookie $cookie);

    public function save($data);

    public function load($key);
}