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
    public $data = [];

    /**
     * The session driver.
     *
     * @var callable
     */
    public $driver;

    /**
     * Create a new session instance.
     *
     * @param \Abimo\Config $config
     * @param \Abimo\Cookie $cookie
     */
    public function __construct(Config $config, Cookie $cookie)
    {
        switch ($config->session['driver']) {
            case 'database' :
                $this->driver = new Session\Database($config, $cookie);
                break;

            case 'memcached' :
                $this->driver = new Session\Memcached($config, $cookie);
                break;

            default :
                $this->driver = new Session\Runtime($config, $cookie);
                break;
        }

        $this->config['app'] = $config->app;
        $this->config['session'] = $config->session;
    }

    /**
     * Set session data.
     *
     * @param string $key
     * @param mixed $value
     */
    public function set($key, $value = null)
    {
        $this->data[$key] = serialize($value);
    }

    /**
     * Get session data by key.
     *
     * @param $key
     * @return mixed
     */
    public function get($key)
    {
        if (!empty($this->data[$key])) {
            return unserialize($this->data[$key]);
        }

        return null;
    }

    /**
     * Save session.
     *
     * @param array $session
     */
    public function save(array $session = [])
    {
        if (!empty($session)) {
            array_map([$this, 'set'], [array_shift($session)], $session);
        }

        $this->driver->save($this->data);
    }

    /**
     * Load session.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function load($key)
    {
        return $this->driver->load($key);
    }
}