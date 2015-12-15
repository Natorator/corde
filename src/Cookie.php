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

    /**
     * @var array $data
     */
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

    /**
     * Set a cookie.
     *
     * @param string $name
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     *
     * @return void
     */
    public function set($name, $value = null, $expire = null, $path = null, $domain = null, $secure = false, $httponly = false)
    {
        $expire = null === $expire ? $this->config['cookie']['expire'] : $expire;
        $path = null === $path ? $this->config['cookie']['path'] : $path;
        $domain = null === $domain ? $this->config['cookie']['domain'] : $domain;

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
        if (!empty($$this->data[$name])) {
            return $this->data[$name]['value'];
        }

        return null;
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
