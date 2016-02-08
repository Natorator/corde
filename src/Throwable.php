<?php

namespace Abimo;

class Throwable
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var array
     */
    public $errorMessages = [
        E_ERROR => 'E_ERROR',
        E_WARNING => 'E_WARNING',
        E_PARSE => 'E_PARSE',
        E_NOTICE => 'E_NOTICE',
        E_CORE_ERROR => 'E_CORE_ERROR',
        E_CORE_WARNING => 'E_CORE_WARNING',
        E_COMPILE_ERROR => 'E_COMPILE_ERROR',
        E_COMPILE_WARNING => 'E_COMPILE_WARNING',
        E_USER_ERROR => 'E_USER_ERROR',
        E_USER_WARNING => 'E_USER_WARNING',
        E_USER_NOTICE => 'E_USER_NOTICE',
        E_STRICT => 'E_STRICT',
        E_RECOVERABLE_ERROR => 'E_RECOVERABLE_ERROR',
        E_DEPRECATED => 'E_DEPRECATED',
        E_USER_DEPRECATED => 'E_USER_DEPRECATED'
    ];

    /**
     * @var array
     */
    public $exceptionMessages = [
        //Predefined Exceptions
        89 => 'Exception',
        90 => 'ErrorException',
        91 => 'Error',
        92 => 'ArithmeticError',
        93 => 'AssertionError',
        94 => 'DivisionByZeroError',
        95 => 'ParseError',
        96 => 'TypeError',

        //SPL Exceptions
        97 => 'BadFunctionCallException',
        98 => 'BadMethodCallException',
        99 => 'DomainException',
        100 => 'InvalidArgumentException',
        101 => 'LengthException',
        102 => 'LogicException',
        103 => 'OutOfBoundsException',
        104 => 'OutOfRangeException',
        105 => 'OverflowException',
        106 => 'RangeException',
        107 => 'RuntimeException',
        108 => 'UnderflowException',
        109 => 'UnexpectedValueException'
    ];

    /**
     * @var array
     */
    public $throwable = [];

    /**
     * @var Response
     */
    private $response;

    /**
     * @var string
     */
    private $style = '';

    /**
     * Throwable constructor.
     *
     * @param \Abimo\Config $config
     * @param \Abimo\Response $response
     */
    public function __construct(Config $config, Response $response)
    {
        $this->config = $config;
        $this->response = $response;
    }

    /**
     * Set the initial configuration options.
     *
     * @return $this
     */
    public function configure()
    {
        $throwable = $this->config->get('throwable');

        ini_set('display_errors', $throwable['display']);
        ini_set('reporting', $throwable['reporting']);
        ini_set('log_errors', $throwable['log']);
        ini_set('error_log', $throwable['path']);

        return $this;
    }

    /**
     * Register throwable & shutdown handlers.
     *
     * @return $this
     */
    public function register()
    {
        set_error_handler([$this, 'errorHandler']);
        set_exception_handler([$this, 'exceptionHandler']);
        register_shutdown_function([$this, 'shutdownHandler']);

        return $this;
    }

    /**
     * Unregister throwable handlers.
     *
     * @return $this
     */
    public function unregister()
    {
        restore_error_handler();
        restore_exception_handler();

        return $this;
    }

    /**
     * Make the new throwable.
     *
     * @param int $code
     * @param string $type
     * @param string $message
     * @param string $file
     * @param mixed $line
     *
     * @return void
     */
    public function make($code, $type, $message, $file, $line = '')
    {
        $this->throwable = ['code' => $code, 'type' => $type, 'message' => $message, 'file' => $file, 'line' => $line];
    }

    /**
     * Register the error handler.
     *
     * @param int $code
     * @param string $message
     * @param string $file
     * @param int $line
     */
    public function errorHandler($code, $message, $file, $line)
    {
        $this->make($code, $this->errorMessage($code), $message, $file, $line);
    }

    /**
     * Get the error message.
     *
     * @param int $code
     * @param int $default
     *
     * @return string
     */
    public function errorMessage($code, $default = 1)
    {
        return isset($this->errorMessages[$code]) ? $this->errorMessages[$code] : $this->errorMessages[$default];
    }

    /**
     * Register the exception handler.
     *
     * @param \Exception $exception
     */
    public function exceptionHandler($exception)
    {
        $this->make($exception->getCode(), $this->exceptionMessage($exception->getCode()), $exception->getMessage(), $exception->getFile(), $exception->getLine());
    }

    /**
     * Get the exception message.
     *
     * @param int $code
     * @param int $default
     *
     * @return string
     */
    public function exceptionMessage($code, $default = 89)
    {
        return isset($this->exceptionMessages[$code]) ? $this->exceptionMessages[$code] : $this->exceptionMessages[$default];
    }

    /**
     * Register the shutdown handler.
     *
     * @return void
     */
    public function shutdownHandler()
    {
        if ($throwable = error_get_last()) {
            $this->make(
                $throwable['type'],
                $this->errorMessage($throwable['type']),
                $throwable['message'],
                $throwable['file'],
                $throwable['line']);
        }

        if (!empty($this->throwable)) {
            ob_get_clean();

            $this->response
                ->header('404', true, 404)
                ->send();

            if (empty($this->config->get('app', 'development'))) {
                call_user_func($this->config->get('throwable', 'callable'));
            } else {
                ob_start();
                require(__DIR__.DIRECTORY_SEPARATOR.'Throwable'.DIRECTORY_SEPARATOR.'Style.css');
                $this->style = ob_get_clean();

                ob_start();
                require(__DIR__.DIRECTORY_SEPARATOR.'Throwable'.DIRECTORY_SEPARATOR.'Dashboard.php');
                echo ob_get_clean();
            }
        }
    }
}