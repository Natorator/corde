<?php

namespace Abimo\Session;

class Memcached implements SessionInterface
{
    /**
     * The memcached config array.
     *
     * @var array
     */
    public $config = [];

    /**
     * An array of data to be written to session.
     *
     * @var array
     */
    public $data = [];

    /**
     * Memcached instance.
     *
     * @var callable
     */
    public $instance;

    /**
     * Create a new memcached instance.
     *
     * @param \Abimo\Config $config
     * @param \Abimo\Cookie $cookie
     */
    public function __construct(\Abimo\Config $config, \Abimo\Cookie $cookie)
    {
        if (!class_exists('\\Memcached', false)) {
            throw new \BadFunctionCallException("Class Memcached not found", 97);
        }

        if (null === $this->instance) {
            $this->config['app'] = $config->app;
            $this->config['memcached'] = $config->memcached;
            $this->config['session'] = $config->session;

            $memcached = $this->config['memcached'];

            //TODO - check for persistent connections
            $this->instance = new \Memcached();

            $this->instance->addServer($memcached['host'], $memcached['port']);
        }
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