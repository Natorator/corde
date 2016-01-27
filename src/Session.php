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
    }

    /**
     * Start the session.
     *
     * @return void
     */
    public function start()
    {
        switch ($this->config->get('session', 'handler')) {
            case 'database' :
                $this->handler = new Session\Database($this->config, $this->database);
                session_set_save_handler($this->handler, true);

                break;

            case 'file' :
                $this->handler = new Session\File($this->config);
                session_set_save_handler($this->handler, true);

                break;
        }

        if (!isset($_SESSION)) {
            $session = $this->config->get('session');

            session_name($session['name']);
            session_set_cookie_params(
                $session['expire'],
                $session['path'],
                $session['domain'],
                $session['secure'],
                $session['httponly']
            );

            session_start();
        }
    }
}