<?php

namespace Abimo;

class Config
{
    /**
     * @var array
     */
    public $data = [];

    /**
     * @var string
     */
    public $path;

    /**
     * Set the config path.
     *
     * @param $path
     *
     * @return $this
     */
    public function path($path)
    {
        $this->path = rtrim($path, '/');

        return $this;
    }

    /**
     * Get the config file.
     *
     * @param string $file
     *
     * @return mixed
     */
    private function get($file)
    {
        $file = ucfirst($file);

        if (empty($this->data[$file])) {
            $this->data[$file] = require $this->path.DIRECTORY_SEPARATOR.$file.'.php';
        }

        return $this->data[$file];
    }

    /**
     * Set the config key and the value for the file.
     *
     * @param string $file
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    private function set($file, $key, $value)
    {
        $file = ucfirst($file);

        $this->data[$file][$key] = $value;
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
     * @param string $file
     * @param string $key
     * @param mixed $value
     *
     * @return void
     */
    public function __set($file, $key, $value)
    {
        $this->set($file, $key, $value);
    }
}
