<?php

namespace Abimo\Session;

class Database implements SessionInterface
{
    public $config;

    public $cookie;

    public $instance;

    public function __construct(\Abimo\Config $config, \Abimo\Cookie $cookie)
    {
        $this->config = $config;
        $this->cookie = $cookie;

        if (null === $this->instance) {
            $this->instance = new \Abimo\Database($this->config);
        }
    }

    public function save($data)
    {
        $sessionConfig = $this->config->session;

        $sessionTable = $sessionConfig['table'];

        $statementTableExists = $this->instance->handle->prepare("SHOW TABLES LIKE '$sessionTable'");

        $statementTableExists->execute();

        $sessionColumnIdBacktick = $this->instance->backtick('id');
        $sessionColumnKeyBacktick = $this->instance->backtick('key');
        $sessionColumnValueBacktick = $this->instance->backtick('value');

        if (empty($statementTableExists->rowCount())) {
            $statementTableCreate = $this->instance->handle->prepare(
                "CREATE TABLE $sessionTable (
                $sessionColumnIdBacktick INT(11) AUTO_INCREMENT PRIMARY KEY,
                $sessionColumnKeyBacktick VARCHAR(255) NOT NULL,
                $sessionColumnValueBacktick BLOB
                )"
            );

            $statementTableCreate->execute();
        }

        $statementInsert = $this->instance->handle->prepare(
            "INSERT INTO $sessionTable (
              $sessionColumnKeyBacktick,
              $sessionColumnValueBacktick)
            VALUES (
              :key,
              :value
            )
            "
        );

        foreach ($data as $key => $value) {
            $statementInsert->execute(['key' => $key, 'value' => serialize($value)]);
        }
    }

    public function load($key)
    {
        $sessionConfig = $this->config->session;

        $sessionTable = $sessionConfig['table'];

        $sessionColumnIdBacktick = $this->instance->backtick('id');
        $sessionColumnKeyBacktick = $this->instance->backtick('key');
        $sessionColumnValueBacktick = $this->instance->backtick('value');

        $statementSelect = $this->instance->handle->prepare(
            "SELECT
              $sessionColumnIdBacktick,
              $sessionColumnKeyBacktick,
              $sessionColumnValueBacktick
            FROM $sessionTable
            WHERE $sessionColumnKeyBacktick = :key
            "
        );

        $statementSelect->execute(['key' => $key]);

        return $statementSelect->fetchAll();
    }
}