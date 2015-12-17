<?php

namespace Abimo;

class Response
{
    /**
     * An instance of config class.
     *
     * @var \Abimo\Config
     */
    public $config;

    /**
     * An instance of cookie class.
     *
     * @var \Abimo\Cookie
     */
    public $cookie;

    /**
     * The headers array.
     *
     * @var array
     */
    public $headers = [];

    /**
     * The response messages array.
     *
     * @var array
     */
    public $messages = [
        100 => 'Continue',
        101 => 'Switching Protocols',
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Moved Temporarily',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Time-out',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Large',
        415 => 'Unsupported Media Type',
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Time-out',
        505 => 'HTTP Version not supported'
    ];

    /**
     * An instance of session class.
     *
     * @var \Abimo\Session
     */
    public $session;

    /**
     * Create a new response instance.
     *
     * @param \Abimo\Config $config
     * @param \Abimo\Cookie $cookie
     * @param \Abimo\Session $session
     */
    public function __construct(Config $config, Cookie $cookie, Session $session)
    {
        $this->config = $config;
        $this->cookie = $cookie;
        $this->session = $session;
    }

    /**
     * Add new response headers.
     *
     * @param array $headers
     *
     * @return void
     */
    public function headers(array $headers = [])
    {
        foreach ($headers as $name => $value) {
            $this->headers[$name] = $value;
        }
    }

    /**
     * Send a response.
     *
     * @param int $code
     *
     * @return void
     */
    public function send($code = 200)
    {
        //save headers
        if (empty($this->headers)) {
            header('HTTP/1.1 '.$code.' '.$this->messages[$code], true, $code);
        } else {
            foreach ($this->headers as $name => $value) {
                header($name.' : '.$value, true, $code);
            }
        }

        //save session
        $this->session->save();

        //save cookies
        $this->cookie->save();
    }
}
