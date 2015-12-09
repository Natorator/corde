<?php

namespace Abimo;

class Session
{
    public $config;
    public $cookie;
    public $data = array();

    public function __construct(Config $config, Cookie $cookie)
    {
        $this->configObject = $config;
        $this->config['app'] = $config->app;
        $this->config['session'] = $config->session;
        $this->cookie = $cookie;
    }

    public function get($key = false)
    {
        return unserialize($this->data[$key]);
    }

    public function set($key, $value)
    {
        $this->data[$key] = serialize($value);
    }

    public function save($key)
    {
        switch ($this->config['session']['driver']) {
            case 'database' :
                $driver = new \Abimo\Session\Database($this->configObject);
                break;
            case 'memcached' :
                $driver = new \Abimo\Session\Memcached($this->configObject);
                break;
        }

        $driver->save($key, $this->data);
    }
}
