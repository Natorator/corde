<?php

namespace Abimo;

class Session
{
    /**
     * @var \SessionHandlerInterface
     */
    private $handler;

    /**
     * @var Config
     */
    private $config;

    /**
     * @var Database
     */
    private $database;

    /**
     * Create a new session instance.
     *
     * @param Config $config
     * @param Database $database
     */
    public function __construct(Config $config, Database $database)
    {
        $this->config = $config;
        $this->database = $database;

        switch ($this->config->session['handler']) {
            case 'database' :
                $this->handler = new Session\Database($this->config, $this->database);
                break;

            default :
                $this->handler = new Session\File($this->config);
                break;
        }

        session_name($this->config->session['name']);
        session_set_cookie_params(
            $this->config->session['expire'],
            $this->config->session['path'],
            $this->config->session['domain'],
            $this->config->session['secure'],
            $this->config->session['httponly']
        );

        session_set_save_handler($this->handler, true);
        session_start();
    }
}