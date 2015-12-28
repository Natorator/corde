<?php

namespace Abimo;

class Response
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var array
     */
    public $cookies = [];

    /**
     * @var array
     */
    public $headers = [];

    /**
     * @var array
     */
    public $messages = [
        // Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',

        // Success 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',

        // Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found',
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        307 => 'Temporary Redirect',

        // Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',

        // Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded'
    ];

    /**
     * Response constructor.
     *
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Save the header for the response.
     *
     * @param string $string
     * @param bool $replace
     * @param int $code
     *
     * @return $this
     */
    public function header($string, $replace = true, $code = 200)
    {
        $this->headers[] = [
            'string' => $string,
            'replace' => $replace,
            'code' => $code
        ];

        return $this;
    }

    /**
     * Save the cookie for the response.
     *
     * @param string $key
     * @param null $value
     * @param null $expire
     * @param null $path
     * @param null $domain
     * @param null $secure
     * @param null $httponly
     *
     * @return $this
     */
    public function cookie($key, $value = null, $expire = null, $path = null, $domain = null, $secure = null, $httponly = null)
    {
        $cookie = $this->config->get('cookie');

        $this->cookies[] = [
            'key' => $key,
            'value' => $value,
            'expire' => null === $expire ? $cookie['expire'] : $expire,
            'path' => null === $path ? $cookie['path'] : $path,
            'domain' => null === $domain ? $cookie['domain'] : $domain,
            'secure' => null === $secure ? $cookie['secure'] : $secure,
            'httponly' => null === $httponly ? $cookie['httponly'] : $httponly
        ];

        return $this;
    }

    /**
     * Send the response.
     *
     * @return void
     */
    public function send()
    {
        foreach ($this->headers as $header) {
            header($header['string'], $header['replace'], $header['code']);
        }

        foreach ($this->cookies as $cookie) {
            setcookie($cookie['key'], $cookie['value'], $cookie['expire'], $cookie['path'], $cookie['domain'], $cookie['secure'], $cookie['httponly']);
        }
    }
}
