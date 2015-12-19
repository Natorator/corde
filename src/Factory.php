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
        $config = null === $config ? $this->config() : $config;

        if (null === static::$database) {
            static::$database = new Database($config);
        }

        return static::$database;
    }

    /**
     * Factory the helper object.
     *
     * @return Helper
     */
    public function helper()
    {
        return new Helper();
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
     * Factory the response object.
     *
     * @param Config $config
     *
     * @return Response
     */
    public function response(Config $config = null)
    {
        $config = null === $config ? $this->config() : $config;

        return new Response($config);
    }

    /**
     * Factory the router object.
     *
     * @param Config $config
     * @param Request $request
     *
     * @return Router
     */
    public function router(Config $config = null, Request $request = null)
    {
        $config = null === $config ? $this->config() : $config;
        $request = null === $request ? $this->request() : $request;

        return new Router($config, $request);
    }

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
        $config = null === $config ? $this->config() : $config;
        $database = null === $database ? $this->database() : $database;

        if (null === static::$session) {
            static::$session = new Session($config, $database);
        }

        return static::$session;
    }

    /**
     * Factory the template object.
     *
     * @return Template
     */
    public function template()
    {
        return new Template();
    }

    /**
     * Factory the throwable object.
     *
     * @param Config $config
     * @param Router $router
     * @param Template $template
     *
     * @return Throwable
     */
    public function throwable(Config $config = null, Router $router = null, Template $template = null)
    {
        $config = null === $config ? $this->config() : $config;
        $router = null === $router ? $this->router() : $router;
        $template = null === $template ? $this->template() : $template;

        return new Throwable($config, $router, $template);
    }
}