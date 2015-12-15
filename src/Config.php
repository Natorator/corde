<?php

namespace Abimo;

class Config
{
    /**
     * The cookie config array.
     *
     * @var array
     */
    public $data = array();

    /**
     * Get the config value by key.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        $keys = explode('.', $key);
        $file = ucfirst(array_shift($keys));

        $this->data[$file] = require APP_PATH.DIRECTORY_SEPARATOR.'Config'.DIRECTORY_SEPARATOR.$file.'.php';

        if (!empty($keys)) {
            return $this->data[$file][array_shift($keys)];
        }

        return $this->data[$file];
    }

    /**
     * Set the config key and value.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function set($key, $value)
    {
        $keys = explode('.', $key);
        $file = array_shift($keys);

        if (!empty($keys)) {
            $this->data[$file][array_shift($keys)] = $value;
        }

        $this->data[$file] = $value;
    }

    /**
     * Magically call get method.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->get($key);
    }

    /**
     * Magically call set method.
     *
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function __set($key, $value)
    {
        $this->set($key, $value);
    }
}
