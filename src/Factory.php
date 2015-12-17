<?php

namespace Abimo;

class Factory
{
    /**
     * Factory config object.
     *
     * @return \Abimo\Config
     */
    public function config()
    {
        return new Config();
    }

    /**
     * Factory cookie object.
     *
     * @param \Abimo\Config $config
     *
     * @return \Abimo\Cookie
     */
    public function cookie(Config $config = null)
    {
        $config = null === $config ? $this->config() : $config;

        return new Cookie($config);
    }

    /**
     * Factory database object.
     *
     * @param \Abimo\Config $config
     *
     * @return \Abimo\Database
     */
    public function database(Config $config = null)
    {
        $config = null === $config ? $this->config() : $config;

        return new Database($config);
    }

    /**
     * Factory helper object.
     *
     * @return \Abimo\Helper
     */
    public function helper()
    {
        return new Helper();
    }

    /**
     * Factory request object.
     *
     * @return \Abimo\Request
     */
    public function request()
    {
        return new Request();
    }

    /**
     * Factory response object.
     *
     * @param \Abimo\Config $config
     * @param \Abimo\Cookie $cookie
     * @param \Abimo\Session $session
     *
     * @return \Abimo\Response
     */
    public function response(Config $config = null, Cookie $cookie = null, Session $session = null)
    {
        $config = null === $config ? $this->config() : $config;
        $cookie = null === $cookie ? $this->cookie() : $cookie;
        $session = null === $session ? $this->session() : $session;

        return new Response($config, $cookie, $session);
    }

    /**
     * Factory router object.
     *
     * @param \Abimo\Config $config
     * @param \Abimo\Request $request
     *
     * @return \Abimo\Router
     */
    public function router(Config $config = null, Request $request = null)
    {
        $config = null === $config ? $this->config() : $config;
        $request = null === $request ? $this->request() : $request;

        return new Router($config, $request);
    }

    /**
     * Factory session object.
     *
     * @param \Abimo\Config $config
     * @param \Abimo\Cookie $cookie
     *
     * @return \Abimo\Session
     */
    public function session(Config $config = null, Cookie $cookie = null)
    {
        $config = null === $config ? $this->config() : $config;
        $cookie = null === $cookie ? $this->cookie() : $cookie;

        return new Session($config, $cookie);
    }

    /**
     * Factory template object.
     *
     * @return \Abimo\Template
     */
    public function template()
    {
        return new Template();
    }

    /**
     * Factory throwable object.
     *
     * @param \Abimo\Config $config
     * @param \Abimo\Router $router
     * @param \Abimo\Template $template
     *
     * @return \Abimo\Throwable
     */
    public function throwable(Config $config = null, Router $router = null, Template $template = null)
    {
        $config = null === $config ? $this->config() : $config;
        $router = null === $router ? $this->router() : $router;
        $template = null === $template ? $this->template() : $template;

        return new Throwable($config, $router, $template);
    }
}