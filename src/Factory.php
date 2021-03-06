<?php

namespace Abimo;

class Factory
{
    /**
     * @var Config
     */
    private static $config;

    /**
     * Factory the config object.
     *
     * @return Config
     */
    public function config()
    {
        if (null === static::$config) {
            static::$config = new Config();
        }

        return static::$config;
    }

    /**
     * @var Database
     */
    private static $database;

    /**
     * Factory the database object.
     *
     * @param Config $config
     *
     * @return Database
     */
    public function database(Config $config = null)
    {
        if (null === static::$database) {
            $config = null === $config ? $this->config() : $config;

            static::$database = new Database($config);
        }

        return static::$database;
    }

    /**
     * Factory the request object.
     *
     * @return Request
     */
    public function request()
    {
        return new Request();
    }

    /**
     * @var Response
     */
    private static $response;

    /**
     * Factory the response object.
     *
     * @param Config $config
     *
     * @return Response
     */
    public function response(Config $config = null)
    {
        if (null === static::$session) {
            $config = null === $config ? $this->config() : $config;

            static::$response = new Response($config);
        }

        return static::$response;
    }

    /**
     * @var Session
     */
    private static $session;

    /**
     * Factory the session object.
     *
     * @param Config $config
     * @param Database $database
     *
     * @return Session
     */
    public function session(Config $config = null, Database $database = null)
    {
        if (null === static::$session) {
            $config = null === $config ? $this->config() : $config;
            $database = null === $database ? $this->database() : $database;

            static::$session = new Session($config, $database);
        }

        return static::$session;
    }

    /**
     * Factory the template object.
     *
     * @param Throwable $throwable
     *
     * @return Template
     */
    public function template(Throwable $throwable = null)
    {
        $throwable = null === $throwable ? $this->throwable() : $throwable;

        return new Template($throwable);
    }

    /**
     * @var Throwable
     */
    private static $throwable;

    /**
     * Factory the throwable object.
     *
     * @param Config $config
     * @param Response $response
     *
     * @return Throwable
     */
    public function throwable(Config $config = null, Response $response = null)
    {
        if (null === static::$throwable) {
            $config = null === $config ? $this->config() : $config;
            $response = null === $response ? $this->response() : $response;

            static::$throwable = new Throwable($config, $response);
        }

        return static::$throwable;
    }
}