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

        $statement = $this->instance->handle->prepare("SHOW TABLES LIKE '$sessionTable'");
        $statement->execute();

        $statementId = $this->instance->backtick('id');
        $statementCookieKey = $this->instance->backtick('cookie_key');
        $statementCookieValue = $this->instance->backtick('cookie_value');
        $statementKey = $this->instance->backtick('key');
        $statementValue = $this->instance->backtick('value');
        $statementDateCreated = $this->instance->backtick('date_created');
        $statementDateUpdated = $this->instance->backtick('date_updated');

        if (empty($statement->rowCount())) {
            $this->instance->handle
                ->prepare(
                    "CREATE TABLE $sessionTable (
                    $statementId INT (11) AUTO_INCREMENT PRIMARY KEY,
                    $statementCookieKey VARCHAR (255) NOT NULL,
                    $statementCookieValue VARCHAR (255) NOT NULL,
                    $statementKey VARCHAR (255) NOT NULL,
                    $statementValue BLOB NULL,
                    $statementDateCreated TIMESTAMP NULL,
                    $statementDateUpdated TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP )
                ENGINE=InnoDB DEFAULT CHARSET=utf8
                ")
                ->execute();

            $this->instance->handle
                ->prepare(
                    "CREATE UNIQUE INDEX uk_$sessionTable on $sessionTable (
                        $statementCookieKey,
                        $statementCookieValue,
                        $statementKey
                )")
                ->execute();
        }

        $statement = $this->instance->handle
            ->prepare(
                "INSERT INTO $sessionTable (
                $statementCookieKey,
                $statementCookieValue,
                $statementKey,
                $statementValue,
                $statementDateCreated)
            VALUES (
                :cookieKeyInsert,
                :cookieValueInsert,
                :keyInsert,
                :valueInsert,
                :dateCreatedInsert)
            ON DUPLICATE KEY UPDATE
                $statementCookieKey = :cookieKeyUpdate,
                $statementCookieValue = :cookieValueUpdate,
                $statementKey = :keyUpdate,
                $statementValue = :valueUpdate
            ");

        $cookieKey = $this->config->app['name'];

        //TODO - optimize here
        if (null === $this->cookie->load($cookieKey)) {
            $cookieValue = bin2hex(openssl_random_pseudo_bytes(10));
        } else {
            $cookieValue = $this->cookie->load($cookieKey);
        }

        $this->cookie->save([$cookieKey, $cookieValue]);

        foreach ($data as $key => $value ) {
            $statement->execute([
                //insert params
                'cookieKeyInsert' => $cookieKey,
                'cookieValueInsert' => $cookieValue,
                'keyInsert' => $key,
                'valueInsert' => $value = serialize($value),
                'dateCreatedInsert' => date('Y-m-d H:i:s', time()),
                //update params
                'cookieKeyUpdate' => $cookieKey,
                'cookieValueUpdate' => $cookieValue,
                'keyUpdate' => $key,
                'valueUpdate' => $value
            ]);
        }
    }

    public function load($key)
    {
        $sessionConfig = $this->config->session;

        $sessionTable = $sessionConfig['table'];

        $statementCookieKey = $this->instance->backtick('cookie_key');
        $statementCookieValue = $this->instance->backtick('cookie_value');
        $statementKey = $this->instance->backtick('key');

        $statement = $this->instance->handle
            ->prepare(
                "SELECT
                    *
                FROM $sessionTable
                WHERE
                    $statementCookieKey = :cookieKey AND
                    $statementCookieValue = :cookieValue AND
                    $statementKey = :key
                "
            );

        $cookieKey = $this->config->app['name'];
        $cookieValue = $this->cookie->load($cookieKey);

        $statement->execute([
            'cookieKey' => $cookieKey,
            'cookieValue' => $cookieValue,
            'key' => $key
        ]);

        return $statement->fetchAll();
    }
}