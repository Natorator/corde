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
     * @param string $key
     *
     * @return mixed
     */
    public function get($file, $key = null)
    {
        $file = ucfirst($file);

        if (empty($this->data[$file])) {
            $this->data[$file] = require $this->path.DIRECTORY_SEPARATOR.$file.'.php';
        }

        if (null === $key) {
            return $this->data[$file];
        }

        return $this->data[$file][$key];
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
    public function set($file, $key, $value)
    {
        $file = ucfirst($file);

        $this->data[$file][$key] = $value;
    }
}