<?php

namespace Abimo;

class Template
{
    /**
     * @var array
     */
    private $data = [];

    /**
     * @var string
     */
    private $file = '';

    /**
     * @var Throwable
     */
    private $throwable;

    public function __construct($throwable)
    {
        $this->throwable = $throwable;
    }

    /**
     * Set the file.
     *
     * @param string $file
     *
     * @return Template
     */
    public function file($file)
    {
        $this->file = $file;

        if (empty(pathinfo($file, PATHINFO_EXTENSION))) {
            $this->file .= '.php';
        }

        return $this;
    }

    /**
     * Set the new data.
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
     * Get the data by the key.
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
     * Render the file or input.
     *
     * @param string $input
     *
     * @return string
     */
    public function render($input = null)
    {
        ob_start();

        extract($this->data, EXTR_SKIP);

        if (null !== $input) {
            echo $input;
        } else {
            require $this->file;
        }

        if (!empty($this->throwable->throwable) && empty($this->throwable->shutdown)) {
            exit;
        }

        return ob_get_clean();
    }
}
