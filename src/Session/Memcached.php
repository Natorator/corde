<?php

namespace Abimo\Session;

class Memcached
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
     * Memcached handle.
     *
     * @var callable
     */
    public $handle;

    /**
     * Create a new memcached instance.
     *
     * @param \Abimo\Config $config
     */
    public function __construct(\Abimo\Config $config)
    {
//        phpinfo();exit;
        if (!class_exists('\\Memcached', false)) {
            throw new \BadFunctionCallException("Class Memcached not found", 97);
        }
//        echo 123;exit;
        $this->config['app'] = $config->app;
        $this->config['memcached'] = $config->memcached;
        $this->config['session'] = $config->session;

        $memcached = $this->config['memcached'];

        //TODO - check for persistent connections
        $this->handle = new \Memcached();

        $this->handle->addServer($memcached['host'], $memcached['port']);
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

    /**
     * Read session data from session handler.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function read($key)
    {
        if ($data = $this->handle->get($this->config['app']['key'].'_'.$key)) {
            return unserialize($data);
        }

        return $data;
    }

    /**
     * Write session data to session handler.
     *
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    public function write($key, $value)
    {
        $this->handle->set($this->config['app']['key'].'_'.$key, serialize($value), time() + $this->config['session']['expire']);
    }
}