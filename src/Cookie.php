<?php

namespace Abimo;

class Cookie
{
    /**
     * The cookie config array.
     *
     * @var array
     */
    public $config = array();

    public $data = array();

    /**
     * Create a new cookie instance.
     *
     * @param \Abimo\Config $config
     */
    public function __construct(Config $config)
    {
        $this->config['app'] = $config->app;
        $this->config['cookie'] = $config->cookie;
    }

    public function set($name, $value, $expire = null, $path = null, $domain = null, $secure = false, $httponly = false)
    {
        if (null === $expire) {
            $expire = $this->config['cookie']['expire'];
        }

        if (null === $path) {
            $path = $this->config['cookie']['path'];
        }

        if (null === $domain) {
            $domain = $this->config['app']['url'];
        }

        $this->data[$name] = array('name' => $name, 'value' => $value, 'expire' => $expire, 'path' => $path, 'domain' => $domain, 'secure' => $secure, 'httponly' => $httponly);
    }

    /**
     * Get a cookie by name.
     *
     * @param string $name
     *
     * @return mixed
     */
    public function get($name)
    {
        return !empty($this->data[$name]) ? $this->data[$name]['value'] : null;
    }

    /**
     * Delete a cookie by name.
     *
     * @param string $name
     *
     * @return void
     */
    public function delete($name)
    {
        if (isset($this->data[$name])) {
            unset($this->data[$name]);
        }
    }
}
