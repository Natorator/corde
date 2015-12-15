<?php

namespace Abimo;

class Template
{
    /**
     * The data array to be used when capturing.
     *
     * @var array
     */
    public $data = array();

    /**
     * The file to be used when capturing.
     *
     * @var array
     */
    public $file;

    /**
     * Capture a file with given data.
     *
     * @param string $file
     * @param array  $data
     *
     * @return string
     */
    public function capture($file, array $data = array())
    {
        ob_start();

        extract($data, EXTR_SKIP);

        require $file;

        return ob_get_clean();
    }

    /**
     * Set a file.
     *
     * @param string $file
     *
     * @return \Abimo\Template
     */
    public function file($file)
    {
        if (false !== strpos($file, PROJECT_PATH)) {
            $this->file = $file;
        } else {
            $this->file = APP_PATH.DIRECTORY_SEPARATOR.'Views'.DIRECTORY_SEPARATOR.$file.'.php';
        }

        return $this;
    }

    /**
     * Set new data.
     *
     * @param string $key
     * @param string $value
     *
     * @return \Abimo\Template
     */
    public function set($key, $value)
    {
        if (is_array($key)) {
            foreach ($key as $name => $value) {
                $this->data[$name] = $value;
            }
        } else {
            $this->data[$key] = $value;
        }

        return $this;
    }

    /**
     * Get data by key.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function get($key)
    {
        return isset($this->data[$key]) ? $this->data[$key] : null;
    }

    /**
     * Render a file.
     *
     * @param string $file
     *
     * @return string
     */
    public function render($file = '')
    {
        if (!empty($file)) {
            $this->file($file);
        }

        return $this->capture($this->file, $this->data);
    }
}
