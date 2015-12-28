<?php

namespace Abimo;

class Request
{
    /**
     * Get the http user agent.
     *
     * @return mixed
     */
    public function agent()
    {
        if (!empty($_SERVER['HTTP_USER_AGENT'])) {
            return $_SERVER['HTTP_USER_AGENT'];
        }
    }

    /**
     * Get the domain.
     *
     * @param string $url
     *
     * @return mixed
     */
    public function domain($url)
    {
        return parse_url($url, PHP_URL_HOST);
    }

    /**
     * Get the ip address.
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
    }

    /**
     * Get the http request method.
     *
     * @return mixed
     */
    public function method()
    {
        if (!empty($_SERVER['REQUEST_METHOD'])) {
            return $_SERVER['REQUEST_METHOD'];
        }
    }

    /**
     * Get the server name.
     *
     * @return mixed
     */
    public function name()
    {
        if (!empty($_SERVER['SERVER_NAME'])) {
            return $_SERVER['SERVER_NAME'];
        }
    }

    /**
     * Get the http request protocol.
     *
     * @return string
     */
    public function protocol()
    {
        if (!empty($_SERVER['SERVER_PROTOCOL'])) {
            return $_SERVER['SERVER_PROTOCOL'];
        }
    }

    /**
     * Get the segment.
     *
     * @param string $uri
     * @param int $index
     *
     * @return mixed
     */
    public function segment($uri = null, $index = 1)
    {
        if (null === $uri) {
            $uri = $this->uri();
        }

        if ($uri) {
            $segments = explode('/', $uri);

            $count = count($segments);

            if ($count <= $index || $index < 1) {
                return $segments[$count - 1];
            }

            return $segments[$index];
        }
    }

    /**
     * Get the http request uri.
     *
     * @return mixed
     */
    public function uri()
    {
        if (!empty($_SERVER['PATH_INFO'])) {
            return rawurldecode(parse_url($_SERVER['PATH_INFO'], PHP_URL_PATH));
        } elseif (!empty($_SERVER['REQUEST_URI'])) {
            return rawurldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));
        }
    }
}
