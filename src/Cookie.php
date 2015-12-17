<?php

namespace Abimo;

class Cookie
{
    /**
     * The cookie config array.
     *
     * @var array
     */
    public $config = [];

    /**
     * The cookie data array.
     *
     * @var array $data
     */
    public $data = [];

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
     * @param string $key
     * @param string $value
     * @param int $expire
     * @param string $path
     * @param string $domain
     * @param bool $secure
     * @param bool $httponly
     *
     * @return void
     */
    public function set($key, $value = null, $expire = null, $path = null, $domain = null, $secure = false, $httponly = false)
    {
        $expire = null === $expire ? $this->config['cookie']['expire'] : $expire;
        $path = null === $path ? $this->config['cookie']['path'] : $path;
        $domain = null === $domain ? $this->config['cookie']['domain'] : $domain;

        $this->data[$key] = ['key' => $key, 'value' => $value, 'expire' => $expire, 'path' => $path, 'domain' => $domain, 'secure' => $secure, 'httponly' => $httponly];
    }

    /**
     * Get a cookie by key.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        if (!empty($this->data[$key])) {
            return $this->data[$key]['value'];
        }

        return null;
    }

    /**
     * Delete a cookie by key.
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
     * Save cookies to client.
     *
     * @param array $cookie
     *
     * @return void
     */
    public function save(array $cookie = [])
    {
        if (!empty($cookie)) {
            array_map([$this, 'set'], [array_shift($cookie)], $cookie);
        }

        foreach ($this->data as $cookie) {
            setcookie($cookie['key'], $cookie['value'], $cookie['expire'], $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httponly']);
        }
    }

    /**
     * Load cookies from client.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function load($key)
    {
        if (isset($_COOKIE[$key])) {
            return $_COOKIE[$key];
        }

        return null;
    }
}