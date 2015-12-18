<?php

namespace Abimo;

class Config
{
    /**
     * @var array
     */
    public $data = [];

    /**
     * Get the config value by the key.
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
     * Set the config key and the value.
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
     * Magically call the get method.
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
     * Magically call the set method.
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
