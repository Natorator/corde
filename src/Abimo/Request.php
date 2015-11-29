<?php

/*
 * This file is part of Abimo.
 * 
 * The MIT License (MIT)
 *
 * Copyright (c) 2015 Martins Eglitis
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is furnished
 * to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Abimo;

/**
 * Request class.
 *
 * @author Martins Eglitis
 */
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
