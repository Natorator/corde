<?php

namespace Abimo\Session;

class Memcached implements SessionInterface
{
    /**
     * The memcached config array.
     *
     * @var array
     */
    public $config = array();

    /**
     * An array of data to be written to session.
     *
     * @var array
     */
    public $data = array();

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
     */
    public function __construct(\Abimo\Config $config)
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

    /**
     * Get value by key.
     *
     * @param string $key
     *
     * @return string
     */
    public function get($key)
    {
        return $this->data[$key];
    }

    /**
     * Set value with respective key.
     *
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * Delete value by key.
     *
     * @param string $key
     *
     * @return void
     */
    public function delete($key)
    {
        if (isset($this->data[$key])) {
            unset($this->data[$key]);
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