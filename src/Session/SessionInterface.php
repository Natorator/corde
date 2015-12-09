<?php

namespace Abimo\Session;

use Abimo\Config;

interface SessionInterface
{
    public function __construct(Config $config);

    public function save($key, $data);
}