<?php

namespace Abimo;

class Session
{
    /**
     * @var Session\File
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
        $this->databasr = $database;

        if (null === $this->handler) {
            switch ($config->session['handler']) {
                case 'database' :
                    $this->handler = new Session\Database($config, $database);
                    break;

                default :
                    $this->handler = new Session\File($config);
                    break;
            }

            session_set_save_handler($this->handler, true);
//            session_start();
        }
    }
}