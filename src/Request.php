<?php

namespace Abimo;

class Request
{
    /**
     * Get http user agent.
     *
     * @return mixed
     */
    public function agent()
    {
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            return $_SERVER['HTTP_USER_AGENT'];
        }

        return null;
    }

    /**
     * Get domain.
     *
     * @param string $url
     *
     * @return mixed
     */
    public function domain($url)
    {
        $parse = parse_url($url);

        return $parse['host'];
    }

    /**
     * Get ip address.
     *
     * @return mixed
     */
    public function ip()
    {
        if (!empty($_SERVER['REMOTE_ADDR'])) {
            return $_SERVER['REMOTE_ADDR'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            return $_SERVER['HTTP_X_FORWARDED_FOR'];
        }

        return null;
    }

    /**
     * Get http request method.
     *
     * @return mixed
     */
    public function method()
    {
        if (!empty($_SERVER['REQUEST_METHOD'])) {
            return strtolower($_SERVER['REQUEST_METHOD']);
        }

        return null;
    }

    /**
     * Get server name.
     *
     * @return mixed
     */
    public function name()
    {
        if (!empty($_SERVER['SERVER_NAME'])) {
            return $_SERVER['SERVER_NAME'];
        }

        return null;
    }

    /**
     * Get http request protocol.
     *
     * @return mixed
     */
    public function protocol()
    {
        if (!empty($_SERVER['SERVER_PROTOCOL'])) {
            return $_SERVER['SERVER_PROTOCOL'];
        }

        return 'HTTP/1.1';
    }

    /**
     * Get segment.
     *
     * @param int $index
     *
     * @return mixed
     */
    public function segment($index = 1)
    {
        if ($uri = $this->uri()) {
            $segments = explode('/', $uri);

            $count = count($segments);

            if ($count <= $index || $index < 1) {
                return $segments[$count - 1];
            }

            return $segments[$index];
        }

        return null;
    }

    /**
     * Get http request uri.
     *
     * @return mixed
     */
    public function uri()
    {
        if (!empty($_SERVER['PATH_INFO'])) {
            return parse_url($_SERVER['PATH_INFO'], PHP_URL_PATH);
        } elseif (!empty($_SERVER['REQUEST_URI'])) {
            return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        }

        return null;
    }
}
