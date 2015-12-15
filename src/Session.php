<?php

namespace Abimo;

//TODO - fix this class

class Session
{
    /**
     * The session config array.
     *
     * @var array
     */
    public $config;

    /**
     * An instance of config class.
     *
     * @var \Abimo\Config
     */
    public $configObject;

    /**
     * The session data array.
     *
     * @var array
     */
    public $data = array();

    /**
     * Create a new session instance.
     *
     * @param \Abimo\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config['app'] = $config->app;
        $this->config['session'] = $config->session;
        $this->configObject = $config;
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
                $driver = new Session\Database($this->configObject);
                break;
            case 'memcached' :
                $driver = new Session\Memcached($this->configObject);
                break;
        }

        $driver->save($key, $this->data);
    }
}
